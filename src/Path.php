<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Base;

// path
// class with static methods to deal with filesystem paths
class Path extends Set
{
    // config
    public static array $config = [
        'option'=>[ // tableau d'options
            'start'=>true,
            'clean'=>true],
        'separator'=>['/'], // sépareur de chemin, n'utilise pas directorySeparator
        'safe'=>[
            'length'=>500, // longueur maximale permise pour un path
            'regex'=>null, // regex à spécifier
            'extension'=>null, // extension permises, tout est permis si null
            'pattern'=>['./','/.','..','//','?',' ','.\\','\\.','\\\\']], // pattern de chemin non sécuritaire
        'safeBasenameReplace'=>[' '=>'_','-'=>'_','.'=>'_',','=>'_'], // caractère à remplacer sur un safebasename
        'extensionReplace'=>['jpeg'=>'jpg'], // gère le remplacement d'extension, utiliser par safeBasename
        'build'=>[ // pour reconstuire à partir d'un array
            'dirname'=>'/',
            'basename'=>null,
            'filename'=>null,
            'extension'=>'.'],
        'infoConstant'=>[ // tableau liant des strings aux constantes
            'dirname'=>PATHINFO_DIRNAME,
            'basename'=>PATHINFO_BASENAME,
            'filename'=>PATHINFO_FILENAME,
            'extension'=>PATHINFO_EXTENSION],
        'lang'=>[ // option par défaut pour détection de la langue d'un path, index de langue dans le path est 0
            'length'=>2, // longueur de lang
            'all'=>null], // possibilité de lang
        'argument'=>'-' // caractère utilisé pour un chemin argument, utilisé dans isArgument
    ];


    // is
    // retourne vrai si la valeur est un path
    // trop de possibilité, alors seulement is_string
    final public static function is($value):bool
    {
        return is_string($value);
    }


    // isWindowsDrive
    // retourne vrai si l'entrée semble être un drive windows, comme c:
    final public static function isWindowsDrive($value):bool
    {
        return is_string($value) && strlen($value) === 2 && substr($value,1,1) === ':';
    }


    // hasWindowsDrive
    // retourne vrai si le chemin contient un windows drive
    final public static function hasWindowsDrive($value):bool
    {
        return is_string($value) && strlen($value) >= 2 && static::isWindowsDrive(substr($value,0,2));
    }


    // hasExtension
    // retourne vrai si le chemin a une extension
    final public static function hasExtension(string $path):bool
    {
        return !empty(static::extension($path));
    }


    // hasLang
    // retourne vrai si le path a une lang
    final public static function hasLang(string $path,?array $option=null):bool
    {
        return static::lang($path,$option) !== null;
    }


    // isSafe
    // retourne vrai si le chemin est sécuritaire
    final public static function isSafe(string $path,?array $option=null):bool
    {
        $return = true;
        $option = Arr::plus(static::$config['safe'],$option);

        // ascii
        if(!empty($return) && $path !== Str::ascii($path))
        $return = false;

        // regex
        if(!empty($return) && !empty($option['regex']) && !Validate::regex($option['regex'],$path))
        $return = false;

        // length
        if(!empty($option['length']) && is_int($option['length']) && !Str::isMaxLength($option['length'],$path))
        $return = false;

        // extension
        if(!empty($return) && !empty($option['extension']))
        {
            $extension = static::extension($path);

            if(!empty($extension) && !in_array($extension,(array) $option['extension'],true))
            $return = false;
        }

        // unsafePattern
        if(!empty($return) && !empty($option['pattern']))
        {
            $patterns = (array) $option['pattern'];

            foreach ($patterns as $v)
            {
                if(strpos($path,$v) !== false)
                {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }


    // isArgument
    // retourne vrai si le chemin est un argument, donc commence par -
    final public static function isArgument($value):bool
    {
        $return = false;

        if(is_string($value))
        {
            $value = static::stripStart($value);

            if(strpos($value,static::$config['argument']) === 0)
            $return = true;
        }

        return $return;
    }


    // isLangCode
    // retourne vrai si la valeur est un code de langue
    final public static function isLangCode(string $value,?array $option=null):bool
    {
        $return = false;
        $option = Arr::plus(static::$config['lang'],$option);

        if(!empty($value) && Str::keepAlpha($value) === $value)
        {
            $return = true;

            if(is_int($option['length']) && strlen($value) !== $option['length'])
            $return = false;

            elseif(is_array($option['all']) && !in_array($value,$option['all'],true))
            $return = false;
        }

        return $return;
    }


    // isParent
    // retourne vrai si la valeur parent est un sous-directoire dans le chemin de path
    final public static function isParent(string $parent,string $path):bool
    {
        $return = false;
        $parent = static::str($parent);
        $parents = static::parents($path);

        if(in_array($parent,$parents,true))
        $return = true;

        return $return;
    }


    // isExtension
    // retourne vrai si le chemin à une extension du type
    // possibilité de mettre une ou plusieurs target
    // insensible à la case
    final public static function isExtension($target,string $path):bool
    {
        $return = false;

        if(is_string($target) || is_array($target))
        {
            $extension = static::extension($path);

            if(!empty($extension) && Arr::in($extension,(array) $target,false))
            $return = true;
        }

        return $return;
    }


    // isLang
    // retourne vrai si le path à la langue spécifié
    final public static function isLang($value,string $path,?array $option=null):bool
    {
        return is_string($value) && $value === static::lang($path,$option);
    }


    // isMimeGroup
    // retourne vrai si le mime est du group spécifé
    final public static function isMimeGroup($group,$value):bool
    {
        return is_string($value) && is_string($group) && static::mimeGroup($value) === $group;
    }


    // isMimeFamily
    // retourne vrai si le mime est de la famille spécifiée
    final public static function isMimeFamily($family,$value):bool
    {
        return is_string($value) && is_string($family) && in_array($family,static::mimeFamilies($value),true);
    }


    // isInterface
    // retourne vrai si le chemin semble pointer vers des interfaces
    final public static function isInterface($value):bool
    {
        return is_string($value) && Str::isEnd('/contract',static::dirname($value),false);
    }


    // normalize
    // permet de normalize un path, change tous les séparateurs pour /
    // retire le protocol file://
    // gère aussi les chemins windows (comme c:)
    final public static function normalize(string $return,bool $stripWrap=false):string
    {
        $separator = static::getSeparator();

        if(!empty($separator))
        {
            $windowsDrive = false;

            if(strpos($return,'\\') !== false)
            $return = str_replace('\\',$separator,$return);

            if(static::hasWindowsDrive($return))
            {
                $windowsDrive = true;
                $return = ucfirst($return);
            }

            if(stripos($return,'file://') === 0)
            $return = substr($return,7);

            $return = preg_replace('#'.$separator.'+#',$separator,$return);

            if($stripWrap === true && $windowsDrive === false)
            $return = static::stripWrap($return,static::getOption('start'),static::getOption('end'));
        }

        return $return;
    }


    // prepareStr
    // prépare une string dans la méthode arr, envoie à normalize
    final public static function prepareStr(string $value,array $option):array
    {
        return parent::prepareStr(static::normalize($value),$option);
    }


    // implode
    // implose un tableau dans une string set
    // gestion particulière si le path commence par un windows drive
    final public static function implode(array $value,?array $option=null):string
    {
        $return = '';
        $option = (array) $option;

        if(!empty($value))
        {
            $first = current($value);
            if(static::isWindowsDrive($first))
            $option['start'] = null;
        }

        $return = parent::implode($value,$option);
        $return = static::normalize($return);

        return $return;
    }


    // info
    // retourne le tableau pathinfo
    // dirname est passé dans separator si pas false, '', ou '.'
    final public static function info(string $path):?array
    {
        $path = static::normalize($path);
        $return = pathinfo($path);

        if(array_key_exists('dirname',$return))
        $return['dirname'] = static::infoDirname($return['dirname'],$path);

        foreach ($return as $key => $value)
        {
            if(in_array($value,['',false,null],true))
            unset($return[$key]);
        }

        if(empty($return))
        $return = null;

        return $return;
    }


    // infoOne
    // retourne une entrée de pathinfo
    final public static function infoOne($key,string $path):?string
    {
        if(is_string($key))
        $key = static::getInfoConstant($key);

        if(is_int($key))
        {
            $path = static::normalize($path);
            $return = pathinfo($path,$key);

            if($return === false || $return === '')
            $return = null;

            elseif($key === PATHINFO_DIRNAME && is_string($return))
            $return = static::infoDirname($return,$path);
        }

        return $return;
    }


    // infoDirname
    // méthode utilisé par info et infoOne pour gérer la valeur dirname retournée
    // gestion particulière pour windows drive
    final protected static function infoDirname(string $value,string $path):?string
    {
        $return = null;

        if($value !== '.' && $value !== $path)
        {
            $normalize = static::normalize($value,true);

            if($normalize !== $path)
            {
                $return = $normalize;

                if(static::hasWindowsDrive($return))
                $return = static::stripEnd($return);
            }
        }

        return $return;
    }


    // getInfoConstant
    // retourne la constante à partir d'une string
    // utilisé pour pathinfo
    final public static function getInfoConstant(string $key):?int
    {
        return static::$config['infoConstant'][$key] ?? null;
    }


    // getEmptyInfo
    // retourne un tableau vide similaire au retour de pathinfo
    final public static function getEmptyInfo():array
    {
        return Arr::valuesAll(null,static::$config['infoConstant']);
    }


    // build
    // construit un path à partir d'un tableau info
    // note: build et les méthodes de path ajoute un slash en début de la chaîne de retour
    // cette classe ajoute toujours un slash au début et enlève celui de la fin
    // utiliser la classe track pour générer des paths respectant les slashs fournis en argument
    final public static function build(array $parse):string
    {
        $return = '';

        // remove basename
        if(!empty($parse['filename']) && !empty($parse['extension']) && !empty($parse['basename']))
        unset($parse['basename']);

        $i = 0;
        $previous = false;
        foreach (static::$config['build'] as $k => $v)
        {
            if(array_key_exists($k,$parse) && is_string($parse[$k]) && !empty($parse[$k]))
            {
                if($previous === 'dirname' && !empty($return) && !empty(static::$config['build'][$previous]))
                $return .= static::$config['build'][$previous];

                if($k === 'extension' && !empty(static::$config['build'][$k]))
                $return .= static::$config['build'][$k];

                $return .= $parse[$k];

                if($k === 'basename')
                break;

                $previous = $k;
                $i++;
            }
        }

        $return = static::normalize($return,true);

        return $return;
    }


    // rebuild
    // reconstruit un path à partir d'une string
    final public static function rebuild(string $return):string
    {
        $info = static::info($return);
        $return = static::build($info);

        return $return;
    }


    // change
    // change une ou plusieurs partis d'un path
    final public static function change(array $change,string $return):string
    {
        $info = static::info($return);

        if(!empty($change) && !empty($info))
        {
            $info = Arr::replace($info,$change);
            $return = static::build($info);
        }

        return $return;
    }


    // keep
    // garde une ou plusieurs partis d'un path
    final public static function keep($change,string $return):string
    {
        $info = static::info($return);

        if(!empty($change) && !empty($info))
        {
            $info = Arr::getsExists((array) $change,$info);
            $return = static::build($info);
        }

        return $return;
    }


    // remove
    // enlève une ou plusieurs partis d'un path
    final public static function remove($change,string $return):string
    {
        $info = static::info($return);

        if(!empty($change) && !empty($info))
        {
            $change = (array) $change;

            if(array_key_exists('basename',$info) && Arr::inFirst(['filename','extension'],$change) !== null)
            unset($info['basename']);

            $info = Arr::keysStrip($change,$info);
            $return = static::build($info);
        }

        return $return;
    }


    // dirname
    // retourne le dirname du path
    // ne tient pas compte des dirname .
    final public static function dirname(string $path):?string
    {
        return static::infoOne('dirname',$path);
    }


    // changeDirname
    // change le dirname d'un path
    final public static function changeDirname(string $change,string $path):string
    {
        $return = '';

        $build['dirname'] = $change;
        $build['basename'] = static::basename($path);
        $return = static::build($build);

        return $return;
    }


    // addDirname
    // ajoute un dirname à un path, le dirname s'ajoute au dirname existant
    final public static function addDirname(string $change,string $path):string
    {
        $return = '';

        $dirname = static::dirname($path);
        $build['dirname'] = static::append($dirname,$change);
        $build['basename'] = static::basename($path);
        $return = static::build($build);

        return $return;
    }


    // removeDirname
    // enlève un dirname à un path
    final public static function removeDirname(string $path):string
    {
        return static::normalize(static::basename($path) ?? '',true);
    }


    // parent
    // retourne le chemin absolut du parent
    final public static function parent(string $path):?string
    {
        return static::dirname($path);
    }


    // parents
    // retourne les chemins absolus de tous les parents
    // gestion particulière pour un chemin qui est un windows drive
    final public static function parents(string $path):array
    {
        $return = [];
        $x = static::arr($path);

        if(!empty($x))
        {
            while (!empty($x))
            {
                array_pop($x);
                $return[] = static::str($x);

                if(count($x) === 1 && static::isWindowsDrive(current($x)))
                break;
            }
        }

        return $return;
    }


    // basename
    // retourne le basename du path
    final public static function basename(string $path):?string
    {
        return static::infoOne('basename',$path);
    }


    // changeBasenameExtension
    // ajoute une extension à un basename, ne considère pas comme un path
    // possible de mettre l'extension lowerCase
    final public static function changeBasenameExtension(string $extension,string $basename,bool $lowerCase=false):string
    {
        return static::makeBasename(static::filename($basename),$extension,$lowerCase);
    }


    // makeBasename
    // fait un basename à partir d'un filename et d'une extension
    // l'extension peut être ramené en lowercase
    final public static function makeBasename(string $return,?string $extension=null,bool $lowerCase=false):string
    {
        if(is_string($extension))
        {
            $return .= '.';
            if($lowerCase === true)
            $extension = strtolower($extension);

            $return .= $extension;
        }

        return $return;
    }


    // safeBasename
    // retourne un nom de fichier sécuritaire à partir d'un path ou basename
    // l'extension est toujours mise en lowercase et est passé dans extensionReplace
    final public static function safeBasename(string $value,?string $extension=null):?string
    {
        $return = null;

        if($extension === null)
        $extension = static::extension($value);

        $filename = static::filename($value);

        if(is_string($filename))
        {
            $return = $filename;
            $replace = static::$config['safeBasenameReplace'];
            $return = Str::replace($replace,$return);
            $return = Str::clean($return,'_');
            $return = Str::trim($return,'_');
            $return = Str::removeConsecutive('_',$return);

            if(is_string($extension) && !empty($return))
            {
                $extension = static::extensionReplace($extension);
                $return = static::makeBasename($return,$extension,true);
            }
        }

        return $return;
    }


    // parentBasename
    // fonction pour obtenir rapidement le basename du parent
    final public static function parentBasename(string $path):?string
    {
        $return = null;

        $dirname = static::dirname($path);
        if(!empty($dirname))
        $return = static::basename($dirname);

        return $return;
    }


    // addBasename
    // ajoute le basename à un path
    final public static function addBasename(string $change,string $path):string
    {
        $return = '';

        $build['basename'] = static::basename($change);
        $build['dirname'] = $path;
        $return = static::build($build);

        return $return;
    }


    // changeBasename
    // change le basename d'un path
    final public static function changeBasename(string $change,string $path):string
    {
        $return = '';

        $build['basename'] = static::basename($change);
        $build['dirname'] = static::dirname($path);
        $return = static::build($build);

        return $return;
    }


    // removeBasename
    // enlève un basename à un path
    final public static function removeBasename(string $path):?string
    {
        return static::normalize(static::dirname($path) ?? '',true);
    }


    // filename
    // retourne le filename du path
    final public static function filename(string $path):?string
    {
        return static::infoOne('filename',$path);
    }


    // addFilename
    // ajoute le filename à un path
    final public static function addFilename(string $change,string $path):string
    {
        $return = '';

        $build['filename'] = static::filename($change);
        $build['dirname'] = $path;
        $return = static::build($build);

        return $return;
    }


    // changeFilename
    // change le filename d'un path
    final public static function changeFilename(string $change,string $path):string
    {
        $return = '';

        $build['filename'] = static::filename($change);
        $build['dirname'] = static::dirname($path);
        $build['extension'] = static::extension($path);
        $return = static::build($build);

        return $return;
    }


    // removeFilename
    // enlève un filename à un path
    final public static function removeFilename(string $path):string
    {
        return static::remove('filename',$path);
    }


    // extension
    // retourne l'extension du path
    final public static function extension(string $path,bool $lowerCase=false):?string
    {
        $return = static::infoOne('extension',$path);

        if(is_string($return) && $lowerCase === true)
        $return = strtolower($return);

        return $return;
    }


    // extensionLowerCase
    // retourne le chemin avec l'extension en lowercase
    final public static function extensionLowerCase(string $return):?string
    {
        $extension = static::extension($return);
        if(is_string($extension))
        {
            $lowerCase = strtolower($extension);
            if($lowerCase !== $extension)
            $return = static::changeExtension($lowerCase,$return);
        }

        return $return;
    }


    // extensionReplace
    // permet de remplacer une extension par une autre, utiliser par safeBasename
    final public static function extensionReplace(string $return):string
    {
        $return = strtolower($return);

        if(array_key_exists($return,static::$config['extensionReplace']))
        $return = static::$config['extensionReplace'][$return];

        return $return;
    }


    // addExtension
    // ajoute l'extension à un path
    final public static function addExtension(string $change,string $path):string
    {
        $return = '';

        $extension = static::extension($change);
        if(empty($extension))
        $extension = static::filename($change);

        $build['extension'] = $extension;
        $build['dirname'] = $path;
        $return = static::build($build);

        return $return;
    }


    // changeExtension
    // change l'extension d'un path
    final public static function changeExtension(string $change,string $path):string
    {
        $return = '';

        $extension = static::extension($change);
        if(empty($extension))
        $extension = static::filename($change);

        $build['extension'] = $extension;
        $build['dirname'] = static::dirname($path);
        $build['filename'] = static::filename($path);
        $return = static::build($build);

        return $return;
    }


    // removeExtension
    // enlève une extension à un path
    final public static function removeExtension(string $path):string
    {
        return static::remove(['extension'],$path);
    }


    // mime
    // retourne le mimetype du fichier à partir de son chemin ou extension
    // si path est seulement l'extension, la fonction retourne également le mime type
    // pratique pour les fichiers qui n'existent pas
    final public static function mime(string $value):?string
    {
        return Mime::fromPath($value);
    }


    // mimeGroup
    // retourne le groupe mimetype du fichier à partir de son extension
    // pour les fichiers qui n'existent pas
    final public static function mimeGroup(string $value):?string
    {
        $return = null;
        $mime = static::mime($value);

        if(!empty($mime))
        $return = Mime::group($mime);

        return $return;
    }


    // mimeFamilies
    // retourne les familles du mime type du chemin
    // pour les fichiers qui n'existent pas
    final public static function mimeFamilies(string $value):?array
    {
        $return = null;
        $group = static::mimeGroup($value);

        if(!empty($group))
        $return = Mime::families($group);

        return $return;
    }


    // mimeFamily
    // retourne la première famille du mime type du chemin
    // pour les fichiers qui n'existent pas
    final public static function mimeFamily(string $value):?string
    {
        $return = null;
        $group = static::mimeGroup($value);

        if(!empty($group))
        $return = Mime::family($group);

        return $return;
    }


    // lang
    // retourne la lang de l'uri ou null si non existante
    final public static function lang(string $path,?array $option=null):?string
    {
        $return = null;
        $value = static::get(0,$path);

        if(is_string($value) && static::isLangCode($value,$option))
        $return = $value;

        return $return;
    }


    // addLang
    // ajoute un code de langue à un path
    // ajoute même si le code existe déjà
    final public static function addLang(string $value,string $path,?array $option=null):?string
    {
        $return = null;

        if(static::isLangCode($value,$option))
        $return = static::insert(0,$value,$path,$option);

        return $return;
    }


    // changeLang
    // ajoute ou remplace un code de langue à un path
    final public static function changeLang(string $value,string $path,?array $option=null):?string
    {
        $return = null;

        if(static::isLangCode($value,$option))
        {
            $lang = static::lang($path,$option);
            if(empty($lang))
            $return = static::insert(0,$value,$path,$option);

            else
            $return = static::splice(0,1,$path,$value,$option);
        }

        return $return;
    }


    // removeLang
    // enlève un code de langue à un path
    // retourne le chemin dans tous les cas
    final public static function removeLang(string $return,?array $option=null):string
    {
        $lang = static::lang($return,$option);

        if(!empty($lang))
        $return = static::splice(0,1,$return,null,$option);

        return $return;
    }


    // match
    // retourne le chemin sans le code de langue et sans le wrap du début
    // si pas de lang, retourne pathStripStart quand même
    final public static function match(string $return,?array $option=null):string
    {
        $return = static::removeLang($return,$option);
        $return = static::stripStart($return);

        return $return;
    }


    // redirect
    // retourne le chemin de redirection si le path présente des défauts
    // par exemple path unsafe, double slash, slash à la fin ou manque pathLang
    final public static function redirect(string $path,?array $safeOpt=null,?array $langOpt=null):?string
    {
        $return = null;
        $path = static::stripStart($path);

        if(!static::hasExtension($path) && !static::isArgument($path))
        {
            // protection double slash ou slash à la fin
            if(static::hasSeparatorDouble($path) || static::isSeparatorEnd($path))
            $return = $path = static::str($path);

            // protection s'il manque lang uri (sauf si vide)
            if(strlen($path))
            {
                $lang = static::lang($path,$langOpt);
                $langDefault = Lang::default();

                if(empty($lang) && !empty($langDefault))
                {
                    $path = static::str([$langDefault,$path]);
                    $return = (static::isSafe($path,$safeOpt))? $path:static::wrapStart('');
                }
            }
        }

        return $return;
    }
}

// init
Path::__init();
?>