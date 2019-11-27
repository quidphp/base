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

// mime
// class with static methods to get or guess mime types
class Mime extends Root
{
    // config
    public static $config = [
        'defaultFamily'=>'binary', // famille par défaut pour la méthode add
        'groupToExtension'=>[ // permet de lier des extensions à des groupes
            'audio'=>'mp3',
            'calendar'=>'ics',
            'css'=>['css','scss'],
            'csv'=>'csv',
            'doc'=>['doc','doct','docx','docxt'],
            'font'=>'ttf',
            'html'=>'html',
            'imageRaster'=>['jpg','gif','jpeg','png'],
            'imageVector'=>'svg',
            'js'=>array('js','jsx'),
            'json'=>'json',
            'pdf'=>'pdf',
            'php'=>'php',
            'txt'=>'txt',
            'video'=>['mp4','mov'],
            'xml'=>'xml',
            'zip'=>'zip'],
        'mimeToExtension'=>[ // liste de mimetype commun avec leur extension
            'audio/mpeg'=>'mp3',
            'text/calendar'=>'ics',
            'text/csv'=>'csv',
            'application/octet-stream'=>['ttf'],
            'text/html'=>'html',
            'image/gif'=>'gif',
            'image/jpeg'=>['jpg','jpeg'],
            'image/png'=>'png',
            'image/svg'=>'svg',
            'text/json'=>'json',
            'application/json'=>'json',
            'application/pdf'=>'pdf',
            'text/plain'=>'txt',
            'text/css'=>'css',
            'text/x-scss'=>'scss',
            'text/javascript'=>'js',
            'text/x-php'=>'php',
            'application/msword'=>['doc','doct'],
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=>'docx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template'=>'docxt',
            'video/mp4'=>'mp4',
            'video/quicktime'=>'mov',
            'text/xml'=>'xml',
            'application/zip'=>'zip'],
        'family'=>[ // permet de lier des groupes à des familles
            'image'=>['imageRaster','imageVector'],
            'binary'=>['audio','font','imageRaster','imageVector','pdf','video','zip'],
            'text'=>['calendar','css','csv','doc','html','js','json','php','txt','xml']]
    ];


    // isEmpty
    // retourne vrai si le mime type en est un d'un fichier vide
    final public static function isEmpty($value):bool
    {
        return (is_string($value) && strpos($value,'inode/x-empty') === 0)? true:false;
    }


    // isGroup
    // retourne vrai si le mime type est du group spécifé
    // possible de fournir un fichier ou une resource si get est true
    final public static function isGroup($group,$value,bool $get=false,bool $fromPath=true):bool
    {
        $return = false;

        if(is_string($group))
        {
            if($get === true && static::getGroup($value,$fromPath) === $group)
            $return = true;

            elseif(is_string($value) && static::group($value) === $group)
            $return = true;
        }

        return $return;
    }


    // isFamily
    // retourne vrai si la famille est celle spécifié
    final public static function isFamily($family,$value,bool $get=false,bool $fromPath=true):bool
    {
        $return = false;
        $group = ($get === true)? static::getGroup($value,$fromPath):static::group($value);

        if(!empty($group))
        {
            foreach (static::$config['family'] as $key => $array)
            {
                if(is_array($array) && in_array($group,$array,true) && $key === $family)
                {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }


    // isExtensionInGroup
    // retourne vrai si l'extension est dans le group spécifié
    final public static function isExtensionInGroup($value,$mimeGroup):bool
    {
        $return = false;

        if(is_string($value) && is_string($mimeGroup))
        {
            $value = strtolower($value);
            $extensions = static::extensionsFromGroup($mimeGroup);

            if(in_array($value,$extensions,true))
            $return = true;
        }

        return $return;
    }


    // get
    // retourne le mimetype du fichier ou de la resource à partir de finfo
    final public static function get($value,bool $charset=true):?string
    {
        $return = null;

        if(is_resource($value))
        $return = static::getFromResource($value,$charset,true);

        elseif(File::is($value))
        {
            $value = File::path($value);
            $finfo = Res::open('finfo');

            if(!empty($finfo))
            {
                $mime = finfo_file($finfo,$value);

                if(is_string($mime) && !empty($mime))
                {
                    if(static::toExtension($mime,true) === 'txt')
                    $return = static::fromPath($value);

                    else
                    $return = $mime;

                    if(is_string($return) && $charset === false)
                    $return = static::removeCharset($return);
                }

                Res::close($finfo);
            }
        }

        return $return;
    }


    // getFromResource
    // retourne le mime type à partir d'une resource
    // gère le context option
    final public static function getFromResource($value,bool $charset=true,bool $contextOption=true):?string
    {
        $return = null;

        if($contextOption === true || Res::isPhpWritable($value))
        {
            $return = Res::getContextMime($value);

            if(is_string($return))
            {
                $return = static::fromExtension($return) ?? $return;

                if($charset === false)
                $return = static::removeCharset($return);
            }
        }

        if(empty($return))
        {
            if(Res::isFile($value))
            {
                $path = Res::path($value);
                if(is_string($path))
                $return = static::get($path,$charset);
            }

            elseif(Res::isHttp($value))
            {
                $header = Res::wrapperData($value);
                if(is_array($header) && !empty($header))
                {
                    $mime = Header::contentType($header,$charset);
                    if(is_string($mime) && !empty($mime))
                    $return = $mime;
                }
            }
        }

        return $return;
    }


    // getGroup
    // retourne le group du mimetype du fichier ou de la resource à partir de finfo
    // si extension est true, le fichier n'a pas à exister et l'extension sera utilisé pour déterminer
    final public static function getGroup($value,bool $fromPath=true):?string
    {
        $return = null;
        $mime = static::get($value);

        if($fromPath === true && (empty($mime) || static::isEmpty($mime)))
        $mime = static::fromPath($value);

        if(!empty($mime))
        $return = static::group($mime);

        return $return;
    }


    // getFamilies
    // retourne toutes les familles du fichier ou de la resource
    // si extension est true, le fichier n'a pas à exister et l'extension sera utilisé pour déterminer
    final public static function getFamilies($value,bool $fromPath=true):?array
    {
        $return = null;
        $group = static::getGroup($value,$fromPath);

        if(!empty($group))
        $return = static::families($group);

        return $return;
    }


    // getFamily
    // retourne la première famille du fichier ou de la resource
    // si extension est true, le fichier n'a pas à exister et l'extension sera utilisé pour déterminer
    final public static function getFamily($value,bool $fromPath=true):?string
    {
        $return = null;
        $families = static::getFamilies($value,$fromPath);

        if(!empty($families))
        $return = current($families);

        return $return;
    }


    // getCorrectExtension
    // fait un mime type sur un fichier ou la resource et retourne l'extension que celui-ci devrait utilisé
    final public static function getCorrectExtension($value):?string
    {
        $return = null;
        $mime = static::get($value);

        if(is_string($mime))
        $return = static::toExtension($mime,true);

        return $return;
    }


    // group
    // retourne le nom du groupe à partir d'un mimeType
    final public static function group(string $value):?string
    {
        $return = null;
        $extension = static::toExtension($value,true);

        if(!empty($extension))
        {
            $extension = strtolower($extension);

            foreach (static::$config['groupToExtension'] as $k => $v)
            {
                if((is_array($v) && (in_array($extension,$v,true)) || $extension === $v))
                {
                    $return = $k;
                    break;
                }
            }
        }

        return $return;
    }


    // families
    // retourne toutes les familles contenant le groupe donné en argument
    final public static function families(string $value):array
    {
        $return = [];

        foreach (static::$config['family'] as $key => $array)
        {
            if(is_array($array) && in_array($value,$array,true))
            $return[] = $key;
        }

        return $return;
    }


    // family
    // retourne la première famille trouvé contenant le groupe donné en argument
    final public static function family(string $value):?string
    {
        $return = null;
        $families = static::families($value);

        if(!empty($families))
        $return = current($families);

        return $return;
    }


    // fromPath
    // retourne le mimetype du fichier à partir de son extension
    // si path est seulement l'extension, la fonction retourne également le mime type
    // pratique pour les fichiers qui n'existent pas
    final public static function fromPath($value):?string
    {
        $return = null;

        if(is_resource($value))
        $value = Res::extension($value);

        elseif(is_string($value))
        $value = Path::extension($value) ?? $value;

        if(is_string($value))
        $return = static::fromExtension($value);

        return $return;
    }


    // fromExtension
    // retourne le mime type à partir d'une extension
    final public static function fromExtension(string $extension):?string
    {
        $return = null;

        foreach (static::$config['mimeToExtension'] as $key => $value)
        {
            if((is_array($value) && Arr::in($extension,$value,false)) || Str::icompare($extension,$value))
            {
                $return = $key;
                break;
            }
        }

        return $return;
    }


    // groupFromBasename
    // retourne le group mime type à partir d'un basename
    final public static function groupFromBasename(string $basename):?string
    {
        $return = null;
        $extension = Path::extension($basename);

        if(!empty($extension))
        $return = static::groupFromExtension($extension);

        return $return;
    }


    // groupFromExtension
    // retourne le group mime type à partir d'une extension
    final public static function groupFromExtension(string $extension):?string
    {
        $return = null;
        $mime = static::fromExtension($extension);

        if(!empty($mime))
        $return = static::group($mime);

        return $return;
    }


    // toExtension
    // retourne la meilleur extension trouvée à partir d'un mime type
    final public static function toExtension(string $mime,bool $extension=true):?string
    {
        $return = null;

        foreach (static::$config['mimeToExtension'] as $key => $value)
        {
            if(!is_array($value))
            $value = [$value];

            if(stripos($mime,$key) === 0)
            {
                $return = current($value);
                break;
            }

            elseif(Arr::in($mime,$value,false) && $extension === true)
            {
                $return = strtolower($mime);
                break;
            }
        }

        return $return;
    }


    // extensionsFromGroup
    // retourne toutes les extensions admises pour un groupe
    final public static function extensionsFromGroup(string $value):array
    {
        $return = [];

        if(array_key_exists($value,static::$config['groupToExtension']))
        $return = (array) static::$config['groupToExtension'][$value];

        return $return;
    }


    // extensionFromGroup
    // retourne une extension admise dans un group
    // par défaut retourne index 0
    final public static function extensionFromGroup(string $value,int $index=0):?string
    {
        $return = null;
        $extensions = static::extensionsFromGroup($value);

        if(is_array($extensions))
        $return = Arr::index($index,$extensions);

        return $return;
    }


    // removeCharset
    // enlève le charset à partir d'une string mime
    final public static function removeCharset(string $return):string
    {
        if(strpos($return,';') > 0)
        $return = Str::explodeIndex(0,';',$return,null,true);

        return $return;
    }


    // register
    // permet d'ajouter une nouvelle entrée mime dans la configuration de la classe
    // fournir le mime, une ou plusieurs extensions, le nom du group
    // possible de fournir le nom de la famille, sinon ce sera binary par défaut
    final public static function register(string $mime,$extension,string $group,$families=null):bool
    {
        $return = false;

        if(!empty($extension))
        {
            $mime = static::removeCharset($mime);

            if(empty($families))
            $families = static::families($group);

            if(empty($families))
            $families = static::$config['defaultFamily'];

            if(!is_array($families))
            $families = [$families];

            if(!is_array($extension))
            $extension = [$extension];

            if(!array_key_exists($mime,static::$config['mimeToExtension']))
            static::$config['mimeToExtension'][$mime] = $extension;
            else
            static::$config['mimeToExtension'][$mime] = Arr::appendUnique(static::$config['mimeToExtension'][$mime],$extension);

            if(!array_key_exists($group,static::$config['groupToExtension']))
            static::$config['groupToExtension'][$group] = [];
            static::$config['groupToExtension'][$group] = Arr::appendUnique(static::$config['groupToExtension'][$group],$extension);

            foreach ($families as $family)
            {
                if(!array_key_exists($family,static::$config['family']))
                static::$config['family'][$family] = [];

                if(!in_array($group,static::$config['family'][$family], true))
                static::$config['family'][$family][] = $group;
            }

            $return = true;
        }

        return $return;
    }
}
?>