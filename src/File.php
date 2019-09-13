<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// file
// class with static methods to create, read and write files (accepts path strings and resources)
class File extends Finder
{
    // config
    public static $config = [
        'mimeGroup'=>null, // mime groupe de la classe, pour les classes qui étendent
        'load'=>['php','html'], // extension permise pour la méthode file::load, peut être une string
        'notFoundCallable'=>[Error::class,'trigger'], // callable à déclencher si un path n'est pas chargable dans la méthode load
        'defaultPermission'=>644, // permission par défaut pour un fichier
        'option'=>null, // option à surcharger dans la méthode option
        'prefix'=>[ // option pour file::temp
            'extension'=>'txt',
            'dateFormat'=>'YmdHis',
            'separator'=>'_',
            'random'=>11]
    ];


    // is
    // retourne vrai si le chemin est un fichier
    // value peut être une string ou une resource
    public static function is($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = static::path($path);

        if(is_string($path) && is_file($path))
        {
            $return = true;

            $mimeGroup = static::$config['mimeGroup'];
            if(is_string($mimeGroup))
            $return = Mime::isGroup($mimeGroup,$path,true);
        }

        return $return;
    }


    // isEmpty
    // retourne vrai si le fichier est vide
    // value peut être une string ou une resource
    public static function isEmpty($value):bool
    {
        return (static::is($value) && static::size($value) === 0)? true:false;
    }


    // isNotEmpty
    // retourne vrai si le fichier n'est pas vide
    // value peut être une string ou une resource
    public static function isNotEmpty($value):bool
    {
        return (static::is($value) && static::size($value) !== 0)? true:false;
    }


    // isUploaded
    // retourne vrai le chemin est un fichier upload
    // value peut être une string ou une resource
    public static function isUploaded($value):bool
    {
        $return = false;
        $value = static::path($value);

        if(static::is($value,false) && is_uploaded_file($value))
        $return = true;

        return $return;
    }


    // isUploadArray
    // retourne vrai si la ou les valeurs sont des tableau de chargement de fichier php
    public static function isUploadArray(...$values):bool
    {
        $return = false;

        foreach ($values as $value)
        {
            $return = (is_array($value) && Arr::keysExists(['name','type','tmp_name','error','size'],$value))? true:false;

            if($return === false)
            break;
        }

        return $return;
    }


    // isUploadEmptyNotEmpty
    // retourne vrai les fichiers uploads sont vide ou non vides
    // méthode protégé
    protected static function isUploadEmptyNotEmpty(bool $empty,...$values):bool
    {
        $return = false;

        if(static::isUploadArray(...$values))
        {
            foreach ($values as $value)
            {
                if($value['error'] === 4 || $value['size'] === 0)
                $return = ($empty === true)? true:false;

                else
                $return = ($empty === true)? false:true;

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // isUploadEmpty
    // retourne vrai les fichiers uploads sont vide
    public static function isUploadEmpty(...$values):bool
    {
        return static::isUploadEmptyNotEmpty(true,...$values);
    }


    // isUploadNotEmpty
    // retourne vrai les fichiers uploads ne sont pas vide
    public static function isUploadNotEmpty(...$values):bool
    {
        return static::isUploadEmptyNotEmpty(false,...$values);
    }


    // isUploadTooBig
    // retourne vrai si le upload dépasse le maximum autorisé par php ini
    public static function isUploadTooBig(...$values):bool
    {
        $return = false;

        if(static::isUploadArray(...$values))
        {
            foreach ($values as $value)
            {
                if(in_array($value['error'],[1,2],true))
                {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }


    // isLoaded
    // retourne vrai si le fichier a été inclus au moins une fois
    // utilise la méthode getLoadPath pour obtenir le chemin, compatible avec realpath
    // value peut être une string ou une resource
    // normalize les chemins si c'est Windows
    public static function isLoaded($value):bool
    {
        $return = false;
        $value = static::getLoadPath($value);
        $normalize = (Server::isWindows())? true:false;

        if(!empty($value) && in_array($value,static::loaded($normalize),true))
        $return = true;

        return $return;
    }


    // isResource
    // retourne vrai si la ressource est de type fichier
    // gère aussi le mime group pour les classes qui étendent
    public static function isResource($value):bool
    {
        $return = (is_resource($value) && Res::isFileLike($value))? true:false;

        if($return === true)
        {
            $mimeGroup = static::$config['mimeGroup'];
            if(is_string($mimeGroup))
            $return = Mime::isGroup($mimeGroup,$value,true);
        }

        return $return;
    }


    // isMimeGroup
    // retourne vrai si le mime type est du group spécifé
    public static function isMimeGroup($group,$value,bool $fromPath=true):bool
    {
        return (static::is($value))? Mime::isGroup($group,$value,true,$fromPath):false;
    }


    // isMimeFamily
    // retourne vrai si le mime type est de la famille spécifé
    public static function isMimeFamily($family,$value,bool $fromPath=true):bool
    {
        return (static::is($value))? Mime::isFamily($family,$value,true,$fromPath):false;
    }


    // isMaxSize
    // retourne vrai si le fichier est plus petit que la taille maximale
    public static function isMaxSize(int $size,$value):bool
    {
        $return = false;
        $v = static::size($value);

        if(is_int($v) && $size >= $v)
        $return = true;

        return $return;
    }


    // isCount
    // retourne vrai si le count du json est égal à la valeur donné
    // utilisé par base/validate
    public static function isCount(int $count,$value,?array $option=null):bool
    {
        return Json::isCount($count,$value,$option);
    }


    // isMinCount
    // retourne vrai si le count du json est plus grand ou égal que celui spécifié
    // utilisé par base/validate
    public static function isMinCount(int $count,$value,?array $option=null):bool
    {
        return Json::isMinCount($count,$value,$option);
    }


    // isMaxCount
    // retourne vrai si le count du json est plus petit ou égal que celui spécifié
    // utilisé par base/validate
    public static function isMaxCount(int $count,$value,?array $option=null):bool
    {
        return Json::isMaxCount($count,$value,$option);
    }


    // path
    // retourne le path et passe le retour dans la méthode path parent si value est string
    // fonctionne aussi avec les resources
    public static function path($value,bool $isSafe=false,?array $option=null):?string
    {
        $return = null;

        if(is_object($value))
        $value = Obj::cast($value);

        if(static::isResource($value))
        $return = Res::path($value);

        else
        {
            if(is_array($value))
            $value = static::uploadPath($value);

            if(is_string($value))
            {
                $return = parent::path($value,$isSafe,$option);

                $mimeGroup = static::$config['mimeGroup'];
                if(!empty($return) && is_string($mimeGroup))
                {
                    $extension = Path::extension($return);
                    if(!empty($extension) && !Mime::isExtensionInGroup($extension,$mimeGroup))
                    $return = null;
                }
            }
        }

        return $return;
    }


    // resource
    // retourne la resource ou la resource d'une path fourni
    public static function resource($value,?array $option=null)
    {
        $return = null;

        if(is_object($value))
        $value = Obj::cast($value);

        if(static::isResource($value))
        $return = $value;

        else
        {
            if(is_array($value))
            $value = static::uploadPath($value);

            if(is_string($value))
            $return = static::open($value,$option);
        }

        return $return;
    }


    // resources
    // retourne les resources ou les resources de plusieurs paths
    public static function resources(...$values):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            $return[$key] = static::resource($value);
        }

        return $return;
    }


    // res
    // méthode protégé utilisé pour faire les appels à la classe res
    // sauve 400 lignes de code
    protected static function res(string $method,bool $create=false,int $v,?int $o=null,...$args)
    {
        $return = null;
        $value = $args[$v];
        $close = (is_resource($value))? false:true;
        $args[$v] = static::resource($value,['create'=>$create]);

        if(is_int($o))
        $args[$o] = static::option($args[$o]);

        if(!empty($args[$v]))
        {
            $return = Res::$method(...$args);

            if($close === true)
            Res::close($args[$v]);
        }

        return $return;
    }


    // option
    // permet de mettre à jour le tableau d'option selon la classe
    public static function option(?array $option=null):array
    {
        return Arr::plus(['useIncludePath'=>true],$option,static::$config['option']);
    }


    // getLoadPath
    // fonction utiliser pour obtenir un load path
    // accepte aussi une resource ou un tableau
    // ajoute l'extension si inexistante
    public static function getLoadPath($value):string
    {
        $return = '';
        $value = static::path($value);

        if(is_string($value) && strlen($value))
        {
            if(Path::isSafe($value))
            {
                $realpath = static::realpath($value);
                if(!empty($realpath))
                $return = $realpath;
            }

            if(empty($return))
            {
                $path = static::path($value,true);
                if(is_string($path))
                $return = $path;
            }

            if(!empty(static::$config['load']) && !empty($return))
            {
                $load = (array) static::$config['load'];

                // utilise relative, ne force pas l'ajout d'un forward slash pour compatibilité avec realpath
                if(empty(Path::extension($return)))
                $return = PathTrack::changeExtension(current($load),$return);
            }
        }

        return $return;
    }


    // makeLoadPath
    // fonction utiliser pour obtenir un load path vérifié
    // vérifie que l'extension est accepté
    // ne vérifie pas que le chemin existe ou est lisible
    public static function makeLoadPath($value):string
    {
        $return = '';
        $value = static::getLoadPath($value);

        if(strlen($value) && !empty(static::$config['load']))
        {
            $extension = Path::extension($value);
            $load = (array) static::$config['load'];

            if(!empty($extension) && in_array($extension,$load,true))
            $return = $value;
        }

        return $return;
    }


    // load
    // permet de charger un fichier dont l'extension est accepté par la classe
    // extract permet d'envoyer un tableau de donnée et de le passer à la fonction extract
    // definedVars va retourner un tableau de toutes les valeurs déclarés dans le fichier inclu
    // once permet d'utiliser la fonction require_once plutôt que require
    // si le fichier n'existe pas ou n'est pas lisible, la notFoundCallable sera lancé
    public static function load($value,?array $extract=null,bool $definedVars=false,bool $once=false)
    {
        $return = null;
        $original = $value;
        $value = static::makeLoadPath($value);

        if(strlen($value) && static::isReadable($value))
        {
            unset($extension);
            unset($original);

            if(is_array($extract))
            extract($extract,EXTR_SKIP);
            unset($extract);

            if($once === true)
            $return = require_once $value;
            else
            $return = require $value;
            unset($value);
            unset($once);

            if($definedVars === true)
            {
                $return = get_defined_vars();
                unset($return['return']);
                unset($return['definedVars']);
            }

            elseif($return === 1)
            $return = true;
        }

        elseif(static::classIsCallable(static::$config['notFoundCallable']))
        {
            if(empty($value) && is_string($original))
            $value = $original;

            $return = static::$config['notFoundCallable']([$value]);
        }

        return $return;
    }


    // loadOnce
    // comme la méthode load mais utilise seulement la fonction require_once
    // à noter que si le fichier a déjà été inclut, require_once retourne true et extract et defined vars réagisse de la même manière
    public static function loadOnce($value,?array $extract=null,bool $definedVars=false)
    {
        return static::load($value,$extract,$definedVars,true);
    }


    // loads
    // permet de charger plusieurs fichier un après l'autre
    // on ne peut pas utiliser extract et defined vars
    public static function loads(...$values):array
    {
        $return = [];

        foreach ($values as $value)
        {
            $return[] = static::load($value);
        }

        return $return;
    }


    // loaded
    // retournes les fichiers inclus
    // possible de normalizer tous les chemins pour Windows
    public static function loaded(bool $normalize=false):array
    {
        $return = get_included_files();

        if($normalize === true)
        $return = array_map([Path::class,'normalize'],$return);

        return $return;
    }


    // safeBasename
    // retourne un nom de fichier sécuritaire à partir d'un chemin complet
    public static function safeBasename($value):?string
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $basename = Path::basename($path);
            $return = Path::safeBasename($basename);
        }

        return $return;
    }


    // mimeBasename
    // retourne le mime basename
    // l'extension est remplacé par celle du mime si existante
    // possible de fournir un autre basename que celui du chemin
    public static function mimeBasename($value,?string $basename=null):?string
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $basename = (is_string($basename))? $basename:Path::basename($path);
            $extension = static::mimeExtension($path);

            if(empty($extension))
            $extension = (is_string($basename))? Path::extension($basename):Path::extension($path);

            $return = Path::changeBasenameExtension($extension,$basename);
        }

        return $return;
    }


    // mime
    // retourne le mimetype du fichier à partir de finfo
    // le fichier doit existé
    public static function mime($value,bool $charset=true,bool $strict=true):?string
    {
        return (static::is($value))? Mime::get($value,$charset,$strict):null;
    }


    // mimeGroup
    // retourne le groupe du mimetype du fichier à partir de finfo
    // le fichier doit existé, sauf si fromPath est true
    public static function mimeGroup($value,bool $fromPath=true):?string
    {
        return (static::is($value))? Mime::getGroup($value,$fromPath):null;
    }


    // mimeFamilies
    // retourne les familles du mimetype du fichier à partir de finfo
    // le fichier doit existé, sauf si fromPath est true
    public static function mimeFamilies($value,bool $fromPath=true):?array
    {
        return (static::is($value))? Mime::getFamilies($value,$fromPath):null;
    }


    // mimeFamily
    // retourne les familles du mimetype du fichier à partir de finfo
    // le fichier doit existé, sauf si fromPath est true
    public static function mimeFamily($value,bool $fromPath=true):?string
    {
        return (static::is($value))? Mime::getFamily($value,$fromPath):null;
    }


    // mimeExtension
    // retourne l'extension que devrait utiliser le fichier en fonction de son mime
    // le fichier doit existé, sauf si fromPath est true
    public static function mimeExtension($value):?string
    {
        return (static::is($value))? Mime::getCorrectExtension($value):null;
    }


    // stat
    // retourne les informations stat du chemin fichier ou de la resource fichier
    public static function stat($value,bool $formatKey=false,bool $formatValue=false):?array
    {
        $return = null;

        if(static::isResource($value))
        $return = Res::stat($value,$formatKey,$formatValue);

        else
        $return = parent::stat($value,$formatKey,$formatValue);

        return $return;
    }


    // info
    // retourne un tableau d'informations maximal sur le chemin fichier ou la resource fichier
    // l'entrée mime est ajouté par rapport à Finder dans le cas d'un chemin fichier
    // si value est une ressource, beaucoup plus d'informations s'affichent
    public static function info($value,bool $format=true,bool $clearStatCache=false):?array
    {
        $return = null;

        if(static::isResource($value))
        $return = Res::info($value,$format,$clearStatCache);

        else
        {
            $return = parent::info($value,$format,$clearStatCache);
            if(!empty($return))
            {
                $return['mime'] = static::mime($value);
                $return['mimeGroup'] = static::mimeGroup($value);
            }
        }

        return $return;
    }


    // prefixFilename
    // génère un nom de fichier random, avec certaines options
    // est utilisé par la méthode prefix
    public static function prefixFilename(?string $prefix=null,?array $option=null):?string
    {
        $return = null;
        $option = Arr::plus(static::$config['prefix'],$option);
        $separator = (!empty($option['separator']))? $option['separator']:'';
        $basename = [];

        if(is_string($prefix))
        $array[] = $prefix;

        if(!empty($option['dateFormat']) && is_string($option['dateFormat']))
        $array[] = Date::format($option['dateFormat']);

        if(!empty($option['random']) && is_int($option['random']))
        $array[] = Str::random($option['random']);

        if(!empty($array))
        $return = implode($separator,$array);

        return $return;
    }


    // prefixBasename
    // génère un basename de fichier random, avec certaines options
    // possibilité de mettre une extension, certaines classes qui étendent peuvent avoir une extension par défaut
    // est utilisé par la méthode prefix
    public static function prefixBasename(?string $prefix=null,?string $extension=null,?array $option=null):?string
    {
        $return = static::prefixFilename($prefix,$option);
        $option = Arr::plus(static::$config['prefix'],$option);
        $extension = (is_string($extension))? $extension:$option['extension'];

        if(is_string($return) && is_string($extension))
        $return .= '.'.$extension;

        return $return;
    }


    // prefix
    // crée un fichier avec prefix dans un dossier
    // possibilité de mettre une extension, certaines classes qui étendent peuvent avoir une extension par défaut
    // si dirname est null utilise le dossier système temporaire
    // retourne le chemin du fichier
    public static function prefix(?string $dirname=null,?string $prefix=null,?string $extension=null,?array $option=null):?string
    {
        $return = null;
        $dirname = (is_string($dirname))? parent::path($dirname):Dir::temp();
        $basename = static::prefixBasename($prefix,$extension,$option);

        if(!empty($basename))
        {
            $path = Path::addBasename($basename,$dirname);

            if(!empty($path) && !file_exists($path) && static::set($path))
            $return = $path;
        }

        return $return;
    }


    // prefixResource
    // crée un fichier temporaire via la méthode temp et retourne la ressource
    public static function prefixResource(?string $dirname=null,?string $prefix=null,?string $extension=null,?array $option=null)
    {
        $return = null;
        $path = static::prefix($dirname,$prefix,$extension,$option);

        if(!empty($path))
        $return = static::open($path);

        return $return;
    }


    // open
    // ouvre un fichier seulement un chemin et un mode
    // retourne null ou la resource fichier
    public static function open($value,?array $option=null)
    {
        $return = null;
        $value = static::path($value);
        $option = static::option($option);

        if(!empty($value) && static::isReadableOrCreatable($value))
        {
            $return = Res::open($value,$option);

            if(!static::isResource($return))
            $return = null;
        }

        return $return;
    }


    // binary
    // comme open, mais force l'ouverture en mode binaire
    public static function binary($value,?array $option=null)
    {
        return static::open($value,Arr::plus($option,['binary'=>true]));
    }


    // create
    // comme open, mais active l'option de création de fichier
    public static function create($value,?array $option=null)
    {
        return static::open($value,Arr::plus($option,['create'=>true]));
    }


    // line
    // retourne la ligne courante de la resource fichier ou la première ligne si c'est un chemin qui est fourni
    public static function line($value,?array $option=null)
    {
        $return = null;

        if(is_string($value))
        $return = static::lineFirst($value,$option);

        elseif(static::isResource($value))
        {
            $option = static::option($option);
            $return = Res::line($value,$option);
        }

        return $return;
    }


    // get
    // retourne le contenu d'un fichier
    // possibilité d'utiliser seek et length
    public static function get($value,$seek=true,$length=true,?array $option=null):?string
    {
        $return = null;
        $close = (is_resource($value))? false:true;
        $handle = static::resource($value);
        $option = static::option($option);

        if(!empty($handle))
        {
            $return = Res::get($handle,$seek,$length,$option);

            if($close === true)
            Res::close($handle);
        }

        return $return;
    }


    // findEol
    // va tenter de détecter le séparateur de ligne si seekable tellable
    // enregistre dans les options de la ressource
    public static function findEol($value):string
    {
        return static::res('findEol',false,0,null,$value);
    }


    // getEolLength
    // retourne la longueur du séparateur de ligne (1 ou 2)
    public static function getEolLength($value):int
    {
        return static::res('getEolLength',false,0,null,$value);
    }


    // read
    // lit le contenu d'un chemin de fichier ou d'une resource fichier
    // possibilité d'utiliser seek et length
    public static function read($seek,$length,$value,?array $option=null):?string
    {
        return static::res('read',false,2,3,$seek,$length,$value,$option);
    }


    // getLines
    // retourne un tableau de toutes les lignes du fichier, divisé par EOL
    // argument inversé par rapport à lines
    // option skipEmpty disponible
    public static function getLines($value,$offset=true,$length=true,?array $option=null):?array
    {
        return static::res('getLines',false,0,3,$value,$offset,$length,$option);
    }


    // lines
    // retourne un tableau contenant les lignes du fichier ou de la resource fichier incluent entre offset et length
    // offset accepte un chiffre négatif
    // option skipEmpty disponible
    public static function lines($offset,$length,$value,?array $option=null):?array
    {
        return static::res('lines',false,2,3,$offset,$length,$value,$option);
    }


    // lineCount
    // lineCount le nombre de lignes dans un fichier ou une ressource fichier
    // un fichier vide retourne 0 ligne
    public static function lineCount($value,?array $option=null):?int
    {
        return static::res('lineCount',false,0,1,$value,$option);
    }


    // subCount
    // retourne le nombre d'occurences d'une substring dans un fichier ou une ressource de fichier
    // si sub contient le separateur, la recherche se fait dans tout le fichier et non pas par ligne
    // cette méthode ne prend pas le type de fichier csv
    public static function subCount(string $sub,$value,?array $option=null):?int
    {
        return static::res('subCount',false,1,2,$sub,$value,$option);
    }


    // lineFirst
    // retourne la première ligne du fichier ou de la ressource fichier
    public static function lineFirst($value,?array $option=null)
    {
        return static::res('lineFirst',false,0,1,$value,$option);
    }


    // lineLast
    // retourne la dernière ligne du fichier ou de la ressource fichier
    public static function lineLast($value,?array $option=null)
    {
        return static::res('lineLast',false,0,1,$value,$option);
    }


    // lineChunk
    // permet de subdiviser le tableau des lignes par longueur
    // retourne un tableau multidimensionnel colonne
    public static function lineChunk(int $each,$value,bool $preserve=true,?array $option=null):?array
    {
        return static::res('lineChunk',false,1,3,$each,$value,$preserve,$option);
    }


    // lineChunkWalk
    // permet de subdiviser le tableau des lignes du fichier ou de la resource fichier selon le retour d'un callback
    // si callback retourne true, la colonne existante est stocké et une nouvelle colonne est crée
    // si callback retourne faux, la colonne existante est stocké et fermé
    // si callback retourne null, la ligne est stocké si la colonne est ouverte, sinon elle est ignoré
    // retourne un tableau multidimensionnel colonne
    public static function lineChunkWalk(callable $callback,$value,?array $option=null):?array
    {
        return static::res('lineChunkWalk',false,1,2,$callback,$value,$option);
    }


    // set
    // permet de créer un fichier et remplacer ou append son contenu
    // les dossiers seront crées si non existant
    // retourne vrai si le content a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function set($value,$content=null,bool $append=false,?array $option=null)
    {
        return static::res('set',true,0,3,$value,$content,$append,$option);
    }


    // setBasename
    // comme set mais permet de mettre basename et dirname en deux arguments
    // retourne le chemin du fichier si le contenu a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function setBasename($dirname,string $basename,$content=null,bool $append=false,?array $option=null)
    {
        $return = null;
        $dirname = parent::path($dirname);

        if(!empty($dirname))
        {
            $path = Path::addBasename($basename,$dirname);
            $return = static::set($path,$content,$append,$option);

            if($return === true)
            $return = $path;
        }

        return $return;
    }


    // setFilenameExtension
    // comme set mais permet de mettre filename, extension et dirname en trois arguments
    // retourne le chemin du fichier si le contenu a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function setFilenameExtension($dirname,string $filename,?string $extension=null,$content=null,bool $append=false,?array $option=null)
    {
        $return = false;
        $dirname = parent::path($dirname);
        $extension = (is_string($extension))? $extension:static::$config['prefix']['extension'];

        if(!empty($dirname) && !empty($filename) && !empty($extension))
        {
            $path = Path::build(['dirname'=>$dirname,'filename'=>$filename,'extension'=>$extension]);
            $return = static::set($path,$content,$append,$option);

            if($return === true)
            $return = $path;
        }

        return $return;
    }


    // setPrefix
    // crée un fichier en utilisant la méthode path pour le générer
    // retourne le chemin du fichier si le contenu a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function setPrefix(?string $dirname=null,?string $prefix=null,?string $extension=null,$content=null,bool $append=false,?array $option=null)
    {
        $return = false;
        $path = static::prefix($dirname,$prefix,$extension);

        if(!empty($path))
        {
            $return = static::set($path,$content,$append,$option);

            if($return === true)
            $return = $path;
        }

        return $return;
    }


    // base64
    // retourne le contenu du fichier dans l'encodage base64
    public static function base64($value,bool $meta=true,bool $convert=true,?array $option=null):?string
    {
        return static::res('base64',false,0,3,$value,$meta,$convert,$option);
    }


    // write
    // écrit du contenu dans un fichier ou une ressource de fichier
    // si la valeur est un chemin, l'écriture se fait à partir du début et remplace le contenu existant au fur à mesure de l'écriture
    // si la valeur est une ressource, l'écriture se fait à l'endroit du pointeur
    // possibilité de barrer la ressource pendant l'opération
    // possibilité de flush le buffer pour que le contenu soit écrit immédiatement dans la ressource
    // crée le fichier si non existant
    // retourne vrai si le content a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function write($content,$value,?array $option=null)
    {
        return static::res('write',true,1,2,$content,$value,$option);
    }


    // overwrite
    // écrit du contenu dans un fichier ou une ressource de fichier
    // le contenu est entièrement remplacé
    // crée le fichier si non existant
    // retourne vrai si le content a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function overwrite($content,$value,?array $option=null)
    {
        return static::res('overwrite',true,1,2,$content,$value,$option);
    }


    // prepend
    // prepend du contenu dans un fichier ou une ressource de fichier
    // si newline est true, ajoute le caractère EOL à la fin de content
    // crée le fichier si non existant
    // retourne vrai si le content a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function prepend($content,$value,?array $option=null)
    {
        return static::res('prepend',true,1,2,$content,$value,$option);
    }


    // append
    // append du contenu dans un fichier ou une ressource de fichier
    // si newline est true, ajoute le caractère EOL du début de content
    // crée le fichier si non existant
    // retourne vrai si le content a été écrit en entier, sinon retourne un int indiquant le nombre d'octets écrit
    public static function append($content,$value,?array $option=null)
    {
        return static::res('append',true,1,2,$content,$value,$option);
    }


    // appendNewline
    // comme append, mais ajoute une newline au contenu
    public static function appendNewline($content,$value,?array $option=null)
    {
        return static::append($content,$value,Arr::plus($option,['newline'=>true]));
    }


    // concatenate
    // permet de concatener plusieurs ressources et écrire le rendu dans le fichier
    // crée le fichier destination si non existant
    // un séparateur doit être fourni, une callable peut être fourni
    public static function concatenate($value,?callable $callable=null,string $separator,...$values)
    {
        return Res::concatenate(static::resource($value,['create'=>true]),$callable,$separator,...static::resources(...$values));
    }


    // concatenateString
    // permet de concatener plusieurs ressources et retourner le rendu combiné dans une string
    // un séparateur doit être fourni, une callable peut être fourni
    public static function concatenateString(?callable $callable=null,string $separator,...$values):?string
    {
        return Res::concatenateString($callable,$separator,...static::resources(...$values));
    }


    // lineSplice
    // permet d'enlever et éventuellement remplacer des lignes dans le fichier ou la ressource fichier
    // offset accepte un chiffre négatif
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    public static function lineSplice(int $offset,int $length,$value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineSplice',false,2,5,$offset,$length,$value,$replace,$overwrite,$option);
    }


    // lineSpliceFirst
    // permet d'enlever et éventuellement remplacer la première ligne du fichier ou de la ressource fichier
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    public static function lineSpliceFirst($value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineSpliceFirst',false,0,3,$value,$replace,$overwrite,$option);
    }


    // lineSpliceLast
    // permet d'enlever et éventuellement remplacer la dernière ligne du fichier ou de la ressource fichier
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    public static function lineSpliceLast($value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineSpliceLast',false,0,3,$value,$replace,$overwrite,$option);
    }


    // lineInsert
    // permet d'insérer du nouveau contenu à un numéro de ligne dans le fichier ou la ressource fichier
    // le reste du contenu est repoussé
    // offset accepte un chiffre négatif
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    public static function lineInsert(int $offset,$replace,$value,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineInsert',false,2,4,$offset,$replace,$value,$overwrite,$option);
    }


    // lineFilter
    // permet de passer chaque ligne du fichier ou de la ressource fichier dans un callaback
    // si le callback retourne faux, la ligne est retiré
    // le fichier est automatiquement modifié
    // retourne un tableau des lignes filtrés, possibilité d'écrire le résultat dans la ressource si overwrite est true
    public static function lineFilter(callable $callback,$value,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineFilter',false,1,3,$callback,$value,$overwrite,$option);
    }


    // lineMap
    // permet de passer chaque ligne du fichier ou de la ressource fichier dans un callaback
    // la ligne est remplacé par la valeur de retour du callback
    // retourne un tableau des nouvelles lignes, possibilité d'écrire le résultat dans la ressource si overwrite est true
    public static function lineMap(callable $callback,$value,bool $overwrite=true,?array $option=null):?array
    {
        return static::res('lineMap',false,1,3,$callback,$value,$overwrite,$option);
    }


    // empty
    // vide un fichier ou une resource de fichier
    // size permet de définir quel taille le fichier doit avoir après l'opération, donc la méthode truncate à partir de la fin
    // retourne vrai si la ressource a été vidé ou si elle est déjà vide
    public static function empty($value,int $size=0,?array $option=null):?bool
    {
        return static::res('empty',false,0,2,$value,$size,$option);
    }


    // link
    // permet de créer un hard link d'un fichier à un autre endroit
    // si le chemin est un symlink, celui-ci est suivi et le lien vers le nouvel emplacement est recrée
    // les deux éléments partageront la même inode
    public static function link(string $target,$path):bool
    {
        $return = false;
        $target = parent::path($target);
        $path = static::path($path);

        if(is_link($path))
        {
            $symlink = $path;
            $path = Symlink::get($path);
        }

        if(static::isReadable($path,false) && Finder::isCreatable($target))
        {
            $dirname = Path::dirname($target);
            if(!empty($dirname) && Dir::setOrWritable($dirname))
            {
                $return = link($path,$target);

                if($return === true && !empty($symlink))
                Symlink::reset($target,$symlink);
            }
        }

        return $return;
    }


    // changeExtension
    // change l'extension d'un fichier, garde le dirname et filename
    // si le chemin est un symlink, celui-ci est suivi et le lien symbolique vers le nouvel emplacement est recée
    // il faut utiliser la même méthode dans la classe Symlink pour renommer directement le symlink
    // n'écrase pas si existant
    public static function changeExtension(string $extension,$path):bool
    {
        $return = false;
        $path = static::path($path);

        if(!empty($path))
        {
            $target = Path::changeExtension($extension,$path);
            $return = static::rename($target,$path);
        }

        return $return;
    }


    // removeExtension
    // enlève l'extension d'un fichier, garde le dirname et filename
    // si le chemin est un symlink, celui-ci est suivi et le lien symbolique vers le nouvel emplacement est recée
    // n'écrase pas si existant
    public static function removeExtension($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(!empty($path))
        {
            $target = Path::removeExtension($path);
            $return = static::rename($target,$path);
        }

        return $return;
    }


    // moveUploaded
    // déplace un fichier venant d'être chargé
    // pas de support pour les symlinks ni les directoires
    // n'écrase pas si existant
    public static function moveUploaded($target,$path):bool
    {
        $return = false;
        $path = static::path($path);
        $target = static::path($target);

        if(is_string($path) && is_uploaded_file($path) && static::isWritable($path,false) && self::isCreatable($target))
        {
            $dirname = Path::dirname($target);
            if(!empty($dirname) && Dir::setOrWritable($dirname))
            $return = move_uploaded_file($path,$target);
        }

        return $return;
    }


    // makeUploadArray
    // construit un upload array à partir d'un chemin de fichier existant
    // retourne null si non existant
    public static function makeUploadArray($path,int $error=0,bool $is=true):?array
    {
        $return = null;
        $path = static::path($path);

        if($is === false || static::is($path,false))
        {
            $return = [];
            $return['name'] = Path::basename($path);
            $return['tmp_name'] = $path;
            $return['error'] = $error;
            $return['type'] = static::mime($path);
            $return['size'] = static::size($path) ?? 0;
        }

        return $return;
    }


    // uploadBasename
    // retourne un nom de fichier sécuritaire à partir d'un tableau de file upload
    public static function uploadBasename(array $value):?string
    {
        $return = null;

        if(static::isUploadArray($value))
        $return = $value['name'];

        return $return;
    }


    // uploadPath
    // retourne le chemin absolut à partir d'un tableau de chargement de fichier
    public static function uploadPath(array $value):?string
    {
        $return = null;

        if(static::isUploadArray($value))
        $return = $value['tmp_name'];

        return $return;
    }


    // uploadSize
    // retourne le size du fichier à partir d'un tableau de chargement de fichier
    public static function uploadSize(array $value):?int
    {
        $return = null;

        if(static::isUploadArray($value))
        $return = $value['size'];

        return $return;
    }


    // uploadValidate
    // valide un tableau de chargement de fichier et retourne une clé de texte pour représenter la première erreur
    // retourne true si tout est OK, retourne true si pas un upload array ou si le fichier est vide
    public static function uploadValidate($value)
    {
        $return = true;

        if(static::isUploadArray($value))
        {
            $return = true;
            $error = $value['error'];
            $tmp = $value['tmp_name'];

            if($error === 1)
            $return = 'fileUploadSizeIni';

            elseif($error === 2)
            $return = 'fileUploadSizeForm';

            elseif($error === 3)
            $return = 'fileUploadPartial';

            elseif($error === 6)
            $return = 'fileUploadTmpDir';

            elseif($error === 7)
            $return = 'fileUploadWrite';

            elseif($error === 0 && !static::isUploaded($tmp))
            $return = 'fileUploadExists';
        }

        else
        $return = 'fileUploadInvalid';

        return $return;
    }


    // uploadValidates
    // retourne la valeur de validation pour plusieurs fichiers
    // retourne true si tout est ok
    // ne génère pas d'erreur si un des élément de array est null
    public static function uploadValidates(array $array)
    {
        $return = true;

        foreach ($array as $key => $value)
        {
            if($value !== null)
            {
                $v = static::uploadValidate($value);

                if($v !== true)
                {
                    if(!is_array($return))
                    $return = [];

                    $return[$key] = $v;
                }
            }
        }

        return $return;
    }


    // getUmaskFromPermission
    // retourne le umask à utiliser à partir de la permission
    public static function getUmaskFromPermission(int $value,bool $format=false)
    {
        $return = 666 - $value;

        if($format === true)
        $return = static::permissionOctal($return);

        return $return;
    }


    // permissionChange
    // change la permission du fichier
    // utilise defaultPermission si mode est true
    public static function permissionChange($mode,$path):bool
    {
        $return = false;

        if(static::is($path))
        {
            $mode = ($mode === true)? static::defaultPermission():$mode;
            $return = parent::permissionChange($mode,$path);
        }

        return $return;
    }


    // setDefaultPermission
    // change la permission par défaut pour les fichiers
    public static function setDefaultPermission(int $value,bool $umask=false):void
    {
        static::$config['defaultPermission'] = $value;

        if($umask === true)
        {
            $mask = static::getUmaskFromPermission($value);
            static::permissionUmask($mask);
        }

        return;
    }


    // defaultPermission
    // retourne la permission par défaut pour les fichiers
    public static function defaultPermission():int
    {
        return static::$config['defaultPermission'];
    }


    // setNotFound
    // lie une callable aux config
    // cette callable sera appelé si un path n'est pas chargable dans la méthode load
    public static function setNotFound(?callable $callable):void
    {
        static::$config['notFoundCallable'] = $callable;

        return;
    }
}

// config
File::__config();
?>