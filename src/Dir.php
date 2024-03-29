<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// dir
// class with static methods to list, open and parse directories
final class Dir extends Finder
{
    // config
    protected static array $config = [
        'defaultPermission'=>755 // permission par défaut pour un directoire
    ];


    // is
    // retourne vrai si le chemin est un directoire
    final public static function is($path,bool $makePath=true):bool
    {
        if($makePath === true)
        $path = self::path($path);

        return is_string($path) && is_dir($path);
    }


    // isEmpty
    // retourne vrai si le chemin est un directoire vide
    final public static function isEmpty($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isReadable($path,false))
        {
            $res = self::open($path);
            $return = (Res::isEmpty($res));
        }

        return $return;
    }


    // isNotEmpty
    // retourne vrai si le chemin est un directoire non vide
    final public static function isNotEmpty($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isReadable($path,false))
        {
            $res = self::open($path);
            $return = (Res::isNotEmpty($res));
        }

        return $return;
    }


    // isDeep
    // retourne vrai si le chemin est un directoire qui contient au moins un directoire
    final public static function isDeep($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isReadable($path,false))
        {
            $get = self::get($path);
            if(!empty($get))
            $return = Arr::some($get,fn($v) => self::is($v,false));
        }

        return $return;
    }


    // isOlderThanFrom
    // retourne vrai si le fichier path est plus vieux qu'un des fichiers dans from
    // support pour dig, false par défaut
    // possible de spécifier in pour les dossiers dans from
    final public static function isOlderThanFrom($path,$from,bool $dig=false,array $in=null):bool
    {
        $return = true;
        $path = File::path($path);

        if(is_string($path))
        {
            $dateModify = File::dateModify($path);

            if(!empty($dateModify))
            {
                $return = false;
                $from = self::getFileFromFileAndDir($from,$in,$dig);

                foreach ($from as $v)
                {
                    $vMod = File::dateModify($v);

                    if(empty($vMod) || $vMod > $dateModify)
                    {
                        $return = true;
                        break;
                    }
                }
            }
        }

        return $return;
    }


    // isResource
    // retourne vrai si la ressource est de type directoire
    final public static function isResource($value):bool
    {
        return is_resource($value) && Res::isDir($value);
    }


    // temp
    // retourne le chemin du dossier temporaire
    final public static function temp():string
    {
        return Path::normalize(sys_get_temp_dir());
    }


    // getCurrent
    // retourne le dossier de travail courant
    final public static function getCurrent():string
    {
        return Path::normalize(getcwd());
    }


    // setCurrent
    // change le dossier de travail courant
    final public static function setCurrent($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::is($path,false))
        $return = chdir($path);

        return $return;
    }


    // scan
    // wrapper pour scan dir
    // enlève les fichiers invisibles
    // gère un problème sous windows ou un fichier effacé apparaît encore dans scandir
    // retourne un tableau avec des chemins absoluts
    final public static function scan($path,?int $sort=null):?array
    {
        $return = null;
        $path = self::path($path);

        if(self::isReadable($path,false))
        {
            $return = [];
            $isWindows = Server::isWindows();
            $sort ??= SCANDIR_SORT_ASCENDING;

            foreach (scandir($path,$sort) as $value)
            {
                $fullPath = Path::append($path,$value);

                if(!self::isDot($fullPath) && ($isWindows === false || Finder::is($fullPath,false)))
                $return[] = $fullPath;
            }
        }

        return $return;
    }


    // get
    // retourne le contenu d'un directoire
    // dig permet de faire un listing recursif
    // option sort|in|out|format|relative|fqcn
    final public static function get($path,bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $option = Arr::plus(['sort'=>null,'in'=>null,'out'=>null,'format'=>null,'formatExtra'=>false,'relative'=>null,'fqcn'=>null,'fqcnClass'=>null,'fqcnTrait'=>null,'fqcnInterface'=>null],$option);
        $scan = self::scan($path,$option['sort']);

        if(!is_string($option['relative']))
        $option['relative'] = self::path($path);

        if(is_array($scan))
        {
            $return = [];

            foreach ($scan as $fullPath)
            {
                $keep = true;
                $filter = [];

                if(!empty($option['in']) || !empty($option['out']))
                $keep = self::getKeepInOut($fullPath,$option['in'],$option['out']);

                if($keep === true)
                {
                    $makeFormat = self::getMakeFormat($fullPath,$option);
                    if($makeFormat === false)
                    continue;

                    elseif(is_array($makeFormat) && !empty($makeFormat))
                    $return = Arr::merge($return,$makeFormat);
                }

                if($dig === true && self::isReadable($fullPath,false))
                {
                    if($option['format'] === 'tree')
                    $return[$fullPath] = self::get($fullPath,$dig,$option);
                    else
                    $return = Arr::merge($return,self::get($fullPath,$dig,$option));
                }
            }
        }

        return $return;
    }


    // getKeepInOut
    // utilisé par get pour filtrer les résultats
    final protected static function getKeepInOut(string $path,$in=null,$out=null):bool
    {
        $return = true;
        $in = (array) $in;
        $out = (array) $out;
        $type = Finder::type($path);
        $filterExtension = (array_key_exists('extension',$in) || array_key_exists('extension',$out));
        $filterBasename = (array_key_exists('basename',$in) || array_key_exists('basename',$out));
        $filterFilename = (array_key_exists('filename',$in) || array_key_exists('filename',$out));
        $filterVisible = (array_key_exists('visible',$in) || array_key_exists('visible',$out));
        $filterEmpty = (array_key_exists('empty',$in) || array_key_exists('empty',$out));

        $filter = [];
        $filter['type'] = $type;

        if($filterExtension === true)
        $filter['extension'] = Path::extension($path);

        if($filterBasename === true)
        $filter['basename'] = Path::basename($path);

        if($filterFilename === true)
        $filter['filename'] = Path::filename($path);

        if($filterVisible === true)
        $filter['visible'] = Finder::isVisible($path);

        if($filterEmpty === true)
        {
            $filter['empty'] = false;

            if($type === 'dir')
            $filter['empty'] = self::isEmpty($path);
            elseif($type === 'file')
            $filter['empty'] = File::isEmpty($path);
        }

        if(!empty($in) && !Arr::compareIn($in,$filter))
        $return = false;

        if(!empty($out) && Arr::compareOut($out,$filter))
        $return = false;

        return $return;
    }


    // getMakeFormat
    // utilisé par get pour gérer le format de sortie
    final protected static function getMakeFormat(string $path,array $option)
    {
        $return = null;

        if(!empty($option['format']) && is_string($option['format']))
        {
            if(in_array($option['format'],['relative','fqcn'],true))
            {
                $value = Str::stripStart($option['relative'],$path);
                $value = Path::stripStart($value);
                $isPathInterface = Path::isInterface($path);

                if($option['format'] === 'fqcn')
                {
                    if($option['fqcnClass'] === false && Classe::isNameClass($value) && !$isPathInterface)
                    $return = false;

                    if($option['fqcnTrait'] === false && Classe::isNameTrait($value))
                    $return = false;

                    if($option['fqcnInterface'] === false && (Classe::isNameInterface($value) || $isPathInterface))
                    $return = false;

                    if($return !== false)
                    {
                        if(is_string($option['fqcn']))
                        $value = Path::append($option['fqcn'],$value);

                        $value = Fqcn::fromPath($value);
                    }
                }

                if($return !== false)
                $return = [$path=>$value];
            }

            elseif($option['format'] === 'tree')
            $return = [$path=>true];

            else
            {
                $formatPath = Finder::formatPath($option['format'],$path,$option['formatExtra']);
                $return = [$path=>$formatPath];
            }
        }

        else
        $return = [$path];

        return $return;
    }


    // getVisible
    // retourne le contenu d'un directoire
    // filtre les fichiers invisibles
    final public static function getVisible($path,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['in'=>['visible'=>true]]));
    }


    // getInvisible
    // retourne le contenu d'un directoire
    // filtre les fichiers visibles
    final public static function getInvisible($path,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['in'=>['visible'=>false]]));
    }


    // getIn
    // retourne le contenu d'un directoire
    // filtre in
    final public static function getIn($path,array $in,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['in'=>$in]));
    }


    // getExtension
    // retourne le contenu d'un directoire
    // filtre extension
    final public static function getExtension($path,$extension=null,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['in'=>['extension'=>$extension]]));
    }


    // getPhp
    // retourne le contenu d'un directoire
    // filtre extension php
    final public static function getPhp($path,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['in'=>['extension'=>self::phpExtension()]]));
    }


    // getOut
    // retourne le contenu d'un directoire
    // filtre out
    final public static function getOut($path,array $out,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['out'=>$out]));
    }


    // getFormat
    // retourne le contenu d'un directoire
    // format en deuxième argument
    final public static function getFormat($path,string $format,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['format'=>$format]));
    }


    // getFormatExtra
    // retourne le contenu d'un directoire
    // format en deuxième argument et formatExtra est true
    final public static function getFormatExtra($path,string $format,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['format'=>$format,'formatExtra'=>true]));
    }


    // getSize
    // retourne le contenu d'un directoire
    // le format est size
    final public static function getSize($path,$extension=null,bool $dig=false,?array $option=null):?array
    {
        return self::get($path,$dig,Arrs::replace($option,['format'=>'size','in'=>['extension'=>$extension],'out'=>['type'=>'dir']]));
    }


    // getLine
    // retourne le contenu d'un directoire
    // le format est line, le deuxième champ est pour l'extension
    // dig est true, ne peut pas être changé
    final public static function getLine($path,$extension=null,bool $dig=true,?array $option=null):?array
    {
        return self::getExtension($path,$extension,$dig,Arrs::replace($option,['format'=>'line','out'=>['type'=>'dir']]));
    }


    // getEmptyDir
    // retourne tous les directoires vides dans le directoire donné
    // dig est true, ne peut pas être changé
    final public static function getEmptyDir($path,?array $option=null)
    {
        return self::get($path,true,Arrs::replace($option,['in'=>['type'=>'dir','empty'=>true]]));
    }


    // getRelative
    // retourne le contenu d'un directoire
    // le format est relative
    // dig est true, ne peut pas être changé
    final public static function getRelative($path,?array $option=null):?array
    {
        return self::getFormat($path,'relative',true,$option);
    }


    // getFqcn
    // retourne le contenu d'un directoire
    // deuxième argument est extension
    // le format est fqcn et invisible est false
    // dig est true, ne peut pas être changé
    final public static function getFqcn($path,?string $fqcn=null,bool $dig=true,$extension=null,?array $option=null):?array
    {
        $extension ??= self::phpExtension();
        return self::getExtension($path,$extension,$dig,Arrs::replace($option,['in'=>['visible'=>true,'type'=>'file'],'format'=>'fqcn','fqcn'=>$fqcn]));
    }


    // getFormatSymbol
    // retourne le contenu d'un directoire
    // inclut seulement les fichiers avec une valeur de format respectant la comparaison selon le symbol fourni
    // utilisé par getFormatSmaller et getFormatBigger
    final public static function getFormatSymbol($path,string $symbol='=',$value=null,string $format='dateAccess',bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $get = self::getFormat($path,$format,$dig,$option);
        $value ??= Datetime::now();

        if(is_array($get))
        {
            $return = [];

            foreach ($get as $k => $v)
            {
                if(is_int($v) && Validate::compare($v,$symbol,$value))
                $return[$k] = $v;
            }
        }

        return $return;
    }


    // getFormatSmaller
    // retourne le contenu d'un directoire
    // inclut seulement les fichiers avec une valeur de format respectant la comparaison selon le symbol <
    final public static function getFormatSmaller($path,$value=null,string $format='dateAccess',bool $dig=false,?array $option=null):?array
    {
        return self::getFormatSymbol($path,'<',$value,$format,$dig,$option);
    }


    // getFormatBigger
    // retourne le contenu d'un directoire
    // inclut seulement les fichiers avec une valeur de format respectant la comparaison selon le symbol >
    final public static function getFormatBigger($path,$value=null,string $format='dateAccess',bool $dig=false,?array $option=null):?array
    {
        return self::getFormatSymbol($path,'>',$value,$format,$dig,$option);
    }


    // getFormatSort
    // retourne le contenu d'un directoire
    // permet de sort par la valeur du format
    final public static function getFormatSort($path,string $format='dateAccess',$sort=true,bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $gets = self::getFormat($path,$format,$dig,$option);

        if(is_array($gets))
        $return = Arr::valuesSortKeepAssoc($gets,$sort);

        return $return;
    }


    // getFormatSortMax
    // retourne le contenu d'un directoire
    // permet de sort par la valeur du format et de limiter le nombre de résultats retournés
    final public static function getFormatSortMax($path,int $max,string $format='dateAccess',$sort=true,bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $gets = self::getFormatSort($path,$format,$sort,$dig,$option);
        if(is_array($gets))
        $return = Arr::unsetAfterCount($max,$gets);

        return $return;
    }


    // getFormatSortSkip
    // retourne le contenu d'un directoire
    // permet de sort par la valeur du format et d'afficher les résultats seulement après un certain nombre
    final public static function getFormatSortSkip($path,int $skip,string $format='dateAccess',$sort=true,bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $gets = self::getFormatSort($path,$format,$sort,$dig,$option);
        if(is_array($gets))
        $return = Arr::unsetBeforeCount(($skip + 1),$gets);

        return $return;
    }


    // getTree
    // retourne le contenu d'un directoire sous la forme d'un arbre
    // dig est true par défaut
    final public static function getTree($path,bool $dig=true,?array $option=null):?array
    {
        return self::get($path,true,Arrs::replace($option,['format'=>'tree']));
    }


    // getTemp
    // retourne le contenu du dossier temporaire
    final public static function getTemp(bool $dig=false,?array $option=null)
    {
        return self::get(self::temp(),$dig,$option);
    }


    // gets
    // permet de faire une requête get sur plusieurs chemins
    // retourne un tableau multidimensionnel
    final public static function gets(array $paths,bool $dig=false,?array $option=null):array
    {
        $return = [];

        foreach ($paths as $path)
        {
            $return[$path] = self::get($path,$dig,$option);
        }

        return $return;
    }


    // getsAppend
    // permet de faire une requête get sur plusieurs chemins
    // retoure un tableau unidimensionnel, les nouvelles entrées sont append dans le même tableau
    final public static function getsAppend(array $paths,bool $dig=false,?array $option=null):array
    {
        $return = [];

        foreach ($paths as $path)
        {
            $return = Arr::merge($return,self::get($path,$dig,$option));
        }

        return $return;
    }


    // getFileFromFileAndDir
    // retourne un tableau de fichiers à partir d'une valeur qui peut contenir soit des fichiers ou des directoires
    // possible de spécifier in
    final public static function getFileFromFileAndDir($value,?array $in=null,bool $dig=false):array
    {
        $return = [];
        $in = (array) $in;

        if(!is_array($value))
        $value = (array) $value;

        foreach ($value as $v)
        {
            if(File::is($v))
            $return[] = $v;

            elseif(self::is($v))
            {
                $get = self::getIn($v,$in,$dig);
                $return = Arr::merge($return,$get);
            }
        }

        return $return;
    }


    // getDirFromFileAndDir
    // retourne un tableau contenant tous les directoires à partir d'une valeur qui peut contenir soit des fichiers ou des directoires
    final public static function getDirFromFileAndDir($value):array
    {
        $return = [];

        if(!is_array($value))
        $value = (array) $value;

        foreach ($value as $v)
        {
            if(is_string($v))
            {
                $dirname = null;

                if(File::is($v))
                $dirname = dirname($v);

                elseif(self::is($v))
                $dirname = $v;

                if(is_string($dirname) && !in_array($dirname,$return,true))
                $return[] = $dirname;
            }
        }

        return $return;
    }


    // fromToCatchAll
    // prend un tableau avec des path from -> to
    // si un from finit par le caractère catchAll (*), prend tout ce qu'il y a dans le dossier et ajoute au tableau
    final public static function fromToCatchAll(array $return,string $catchAll='*'):array
    {
        foreach ($return as $key => $value)
        {
            if(is_string($key) && is_string($value) && Str::isEnd($catchAll,$key))
            {
                $value = self::normalize($value);
                $path = Str::stripEnd($catchAll,$key);
                $get = self::getVisible($path);

                if(is_array($get))
                {
                    foreach ($get as $source)
                    {
                        $basename = Path::basename($source);
                        $destination = Path::addBasename($basename,$value);
                        $return[$source] = $destination;
                    }
                }

                unset($return[$key]);
            }
        }

        return $return;
    }


    // fromToFilterDate
    // cette méthode prend un tableau avec des paths from->to
    // retire les entrées dont la date de modification du to est plus grande ou égal à from
    final public static function fromToFilterDate(array $return):array
    {
        foreach ($return as $from => $to)
        {
            $dateFrom = Finder::dateModify($from);
            $dateTo = Finder::dateModify($to);

            if(!empty($dateFrom) && !empty($dateTo) && $dateTo >= $dateFrom)
            unset($return[$from]);
        }

        return $return;
    }


    // getChangeBasename
    // fait une requête get
    // permet de passer une closure pour changer les basenames de tous les fichiers incluents
    final public static function getChangeBasename(\Closure $closure,$path,bool $dig=false,?array $option=null):array
    {
        $return = [];
        $get = self::get($path,$dig,$option);

        if(!empty($get))
        {
            foreach (array_reverse($get) as $value)
            {
                $return[$value] = Finder::changeBasename($closure,$value);
            }
        }

        return $return;
    }


    // sortPriority
    // méthode utilisé pour metre certains fichiers d'un dossier avant l'autre
    // possible de mettre le chemin du dossier en troisième argument
    // à utiliser après l'appel à get
    final public static function sortPriority(array $source,$priority,?string $path=null):array
    {
        $return = [];
        $priority = (array) $priority;

        foreach ($priority as $value)
        {
            $value = self::normalize($value);

            if(is_string($path))
            {
                $path = self::normalize($path);
                $value = Path::append($path,$value);
            }

            $key = array_search($value,$source,true);
            if($key !== false)
            {
                unset($source[$key]);
                $return[] = $value;
            }
        }

        return Arr::merge($return,$source);
    }


    // remove
    // permet d'enlever des fichiers d'un tableau de chemins
    // possible de mettre le chemin du dossier en troisième argument
    // à utiliser après l'appel à get
    final public static function remove(array $return,$remove,?string $path=null):array
    {
        $remove = (array) $remove;

        foreach ($remove as $value)
        {
            $value = self::normalize($value);

            if(is_string($path))
            {
                $path = self::normalize($path);
                $value = Path::append($path,$value);
            }

            $key = array_search($value,$return,true);

            if($key !== false)
            unset($return[$key]);
        }

        return $return;
    }


    // parent
    // retourne tous les fichiers contenus dans le parent
    // option self permet d'inclure l'enfant du parent
    final public static function parent($path,bool $dig=false,?array $option=null):?array
    {
        $return = null;
        $path = self::path($path);

        if(is_string($path))
        {
            $option = Arrs::replace(['self'=>true],$option);
            $parent = Path::parent($path);

            if(!empty($parent) && self::isReadable($parent,false))
            {
                $return = self::get($parent,$dig,$option);

                if($option['self'] === false)
                $return = Arr::valueStrip($path,$return);
            }
        }

        return $return;
    }


    // concatenate
    // permet de concatener plusieurs ressources et écrire le rendu dans le fichier
    // un séparateur doit être fourni, une closure peut être fourni
    final public static function concatenate($value,$path,$extension=null,?\Closure $closure=null,string $separator=PHP_EOL)
    {
        return File::concatenate($value,$closure,$separator,...self::getExtension($path,$extension) ?? []);
    }


    // concatenateString
    // permet de concatener plusieurs ressources et retourner le rendu combiné dans une string
    // un séparateur doit être fourni, une closure peut être fourni
    final public static function concatenateString($path,$extension=null,?\Closure $closure=null,string $separator=PHP_EOL):?string
    {
        return File::concatenateString($closure,$separator,...self::getExtension($path,$extension) ?? []);
    }


    // load
    // charge tous les fichiers dans un directoire, utilise file::load
    // once permet de spécifier si require ou require_once est utilisé
    final public static function load($path,$extension=null,bool $dig=true,bool $once=false,?array $option=null):?array
    {
        $return = null;
        $load = self::getExtension($path,$extension,$dig,$option);

        if(!empty($load))
        {
            $return = [];

            foreach ($load as $path)
            {
                $return[$path] = File::load($path,null,false,$once);
            }
        }

        return $return;
    }


    // loadOnce
    // charge tous les fichiers php dans un directoire, utilise file::load
    // utilise require_once
    final public static function loadOnce($path,$extension=null,bool $dig=true,?array $option=null):?array
    {
        return self::load($path,$extension,$dig,true,$option);
    }


    // loadPhp
    // charge tous les fichiers php dans un directoire, utilise file::load
    final public static function loadPhp($path,bool $dig=true,bool $once=false,?array $option=null):?array
    {
        return self::load($path,self::phpExtension(),$dig,$once,$option);
    }


    // loadPhpOnce
    // charge tous les fichiers php dans un directoire, utilise file::load
    // utilise require_once
    final public static function loadPhpOnce($path,bool $dig=true,?array $option=null):?array
    {
        return self::load($path,self::phpExtension(),$dig,true,$option);
    }


    // count
    // retourne le nombre d'élément dans le dossier
    // deuxième argument est extension
    final public static function count($path,$extension=null,bool $dig=true,?array $option=null):?int
    {
        $return = null;
        $path = self::path($path);

        if(self::is($path,false))
        $return = count(self::getExtension($path,$extension,$dig,Arrs::replace($option,['format'=>null])));

        return $return;
    }


    // line
    // count le nombre total de ligne dans les fichiers du dossier
    // deuxième argument est extension
    final public static function line($path,$extension=null,?array $option=null):?int
    {
        $return = null;
        $path = self::path($path);

        if(self::is($path,false))
        {
            $return = 0;

            foreach (self::getExtension($path,$extension,true,Arrs::replace($option,['format'=>null])) as $in)
            {
                $line = File::lineCount($in);
                if(is_int($line))
                $return += $line;
            }
        }

        return $return;
    }


    // size
    // retourne la taille du dossier
    // deuxième argument est format
    final public static function size($path,bool $format=false,$extension=null,?array $option=null)
    {
        $return = null;
        $path = self::path($path);

        if(self::is($path,false))
        {
            $return = 0;

            foreach (self::getExtension($path,$extension,true,Arrs::replace($option,['format'=>null])) as $in)
            {
                $size = Finder::size($in);
                if(is_int($size))
                $return += $size;
            }

            if($format === true)
            $return = Num::sizeFormat($return);
        }

        return $return;
    }


    // subDirLine
    // compte les lignes dans chaque sous-directoire du chemin donné
    final public static function subDirLine(string $path,?array $in=null,$extension=null):?array
    {
        $return = null;
        $in = Arr::replace(['type'=>'dir'],$in);
        $get = self::getIn($path,$in);

        if(is_array($get))
        {
            $return = [];

            foreach ($get as $value)
            {
                $basename = Path::basename($value);
                $line = self::line($value,$extension);

                if(is_int($line) && !empty($line))
                $return[$basename] = $line;
            }
        }

        return $return;
    }


    // overview
    // génère un tableau avec count, size et line pour un dossier
    final public static function overview($value,$extension=null):array
    {
        $return = [];
        $return['count'] = self::count($value,$extension,true);
        $return['size'] = self::size($value,true);
        $return['line'] = self::line($value);

        return $return;
    }


    // set
    // créer une structure de dossier, si non existante et si créable
    // cette méthode ne crée pas de fichier, seulement des dossiers
    // par défaut le chmod est celui par défaut de la classe Dir
    // recursive par défaut
    final public static function set($path,bool $recursive=true,$mode=true):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isCreatable($path))
        {
            if($mode === true)
            $mode = self::defaultPermission();

            if(is_int($mode))
            {
                $mode = self::permissionOctal($mode);
                $return = mkdir($path,$mode,true);
            }
        }

        return $return;
    }


    // setParent
    // créer le dossier parent si non existant
    // recursive par défaut
    final public static function setParent($path,bool $recursive=true):bool
    {
        $return = false;
        $path = self::path($path);

        if(is_string($path))
        {
            $parent = Path::parent($path);

            if(!empty($parent))
            $return = self::set($parent,$recursive);
        }

        return $return;
    }


    // setOrWritable
    // crée une structure de dossier si non existante
    // retourne vrai si le dossier existe déjà et est writable ou s'il a été crée
    // recursive true par défaut
    final public static function setOrWritable($path,bool $recursive=true):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isWritable($path,false))
        $return = true;

        else
        $return = self::set($path,$recursive);

        return $return;
    }


    // copy
    // copy un directoire de façon récursive
    // les symlink ne sont pas suivi, c'est une copie identique d'un dossier
    // retourne un tableau de chaque élément copié from -> to ou null si le dossier n'est pas existant
    final public static function copy($to,$from)
    {
        $return = null;
        $to = self::path($to);
        $from = self::path($from);

        if(self::isReadable($from,false) && self::setOrWritable($to))
        {
            $return = [];

            foreach (self::getRelative($from) as $path =>$value)
            {
                $target = Path::append($to,$value);
                if(!empty($target))
                {
                    $copy = false;

                    if(is_link($path))
                    $copy = Symlink::copy($target,$path);

                    elseif(is_dir($path))
                    $copy = self::set($target);

                    else
                    $copy = copy($path,$target);

                    if($copy === true)
                    $return[$path] = $target;

                    else
                    $return[$path] = false;
                }
            }
        }

        return $return;
    }


    // empty
    // vide un directoire
    // si un fichier est un symlink, efface le symlink et non pas le fichier vers lequel il pointe
    // retourne un tableau des fichiers, symlinks et directoires avec un boolean indiquant si l'effacement a réussi
    // vide aussi les sous-directoires
    final public static function empty($path):array
    {
        $return = [];
        $get = self::get($path,true);

        if(!empty($get))
        {
            foreach (array_reverse($get) as $value)
            {
                if(is_link($value))
                $return[$value] = Symlink::unset($value);

                else
                $return[$value] = Finder::unlink($value);
            }
        }

        return $return;
    }


    // emptyAndUnlink
    // vide et efface un directoire
    final public static function emptyAndUnlink($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(is_string($path))
        {
            self::empty($path);

            if(self::isWritable($path,false) && self::isEmpty($path))
            $return = parent::unlink($path);
        }

        return $return;
    }


    // reset
    // vide un directoire si existante
    // sinon crée le directoire
    final public static function reset($path,bool $recursive=true):bool
    {
        $return = false;
        $path = self::path($path);

        if(is_string($path))
        {
            if(self::is($path))
            {
                if(self::isWritable($path))
                {
                    self::empty($path);
                    $return = true;
                }
            }

            elseif(self::set($path,$recursive))
            $return = true;
        }

        return $return;
    }


    // unlinkIfEmpty
    // efface tous les directoires vides dans le chemin
    final public static function unlinkIfEmpty($path):array
    {
        $return = [];
        $get = self::getIn($path,['type'=>'dir'],true);

        if(!empty($get))
        {
            foreach (array_reverse($get) as $value)
            {
                if(self::isEmpty($value))
                $return[$value] = self::unlink($value);
            }
        }

        return $return;
    }


    // unlinkWhileEmpty
    // permet d'effacer tous les directoires vides dans une structure de chemin
    // la boucle est arrêté lorsque max est atteint ou lorsque'un dossier n'est pas vide
    final public static function unlinkWhileEmpty($path,int $max=3):array
    {
        $return = [];
        $path = self::path($path);

        if(is_string($path) && $max > 0)
        {
            $i = 0;

            foreach (Path::parents($path) as $v)
            {
                $i++;
                $empty = self::unlinkIfEmpty($v);

                if($i === $max || self::isNotEmpty($v))
                break;

                else
                $return[] = $v;
            }
        }

        return $return;
    }


    // open
    // ouvre une resource directoire
    // une resource directoire ne peut pas retourner son path, donc resource très peu utilisé dans la classe
    final public static function open(string $value)
    {
        $return = null;
        $value = self::path($value);

        if(!empty($value) && self::isReadable($value,false))
        {
            $return = Res::open($value);

            if(!self::isResource($return))
            $return = null;
        }

        return $return;
    }


    // getUmaskFromPermission
    // retourne le umask à utiliser à partir de la permission
    final public static function getUmaskFromPermission(int $value,bool $format=false):int
    {
        $return = 777 - $value;

        if($format === true)
        $return = self::permissionOctal($return);

        return $return;
    }


    // permissionChange
    // change la permission du directoire
    // utilise defaultPermission si mode est true
    final public static function permissionChange($mode,$path):bool
    {
        $return = false;

        if(self::is($path))
        {
            $mode = ($mode === true)? self::defaultPermission():$mode;
            $return = parent::permissionChange($mode,$path);
        }

        return $return;
    }


    // setDefaultPermission
    // change la permission par défaut pour les directoires
    final public static function setDefaultPermission(int $value):void
    {
        self::$config['defaultPermission'] = $value;
    }


    // defaultPermission
    // retourne la permission par défaut pour les directoires
    final public static function defaultPermission():int
    {
        return self::$config['defaultPermission'];
    }
}

// init
Dir::__init();
?>