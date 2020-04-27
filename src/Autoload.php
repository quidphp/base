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

// autoload
// class with methods a layer over the native PHP autoload logic
final class Autoload extends Root
{
    // config
    protected static array $config = [
        'psr4'=>[] // garde une copie du racine de l'auto chargement des classes
    ];


    // construct
    // constructeur protégé
    final protected function __construct() { }


    // call
    // recherche un nom de classe à travers tout le pool autoload
    final public static function call(string $class):bool
    {
        $return = false;

        if(!class_exists($class,false))
        {
            spl_autoload_call($class);

            if(class_exists($class,false))
            $return = true;
        }

        return $return;
    }


    // getExtensions
    // retourne les extensions utilisés dans l'implémentation par défaut
    final public static function getExtensions():string
    {
        return spl_autoload_extensions();
    }


    // setExtensions
    // change les extensions utilisés dans l'implémentation par défaut
    final public static function setExtensions($value):bool
    {
        $return = false;

        if($value === true)
        $value = '.'.self::phpExtension();

        if(is_array($value))
        $value = implode(',',$value);

        if(is_string($value))
        {
            $extension = spl_autoload_extensions($value);

            if($extension === $value)
            $return = true;
        }

        return $return;
    }


    // register
    // ajoute une fonction dans le pool des autoload
    // si tout est vide, utilise l'implémentation spl_autoload par défaut qui se base sur les include path
    // si call est true, utilise la méthode manual
    // note: l'implémentation spl_autoload par défaut ne déclenche pas le callback __init sur les classes
    final public static function register(?callable $call=null,bool $throw=true,bool $prepend=false):bool
    {
        $return = false;

        if(!empty($call))
        $return = spl_autoload_register($call,$throw,$prepend);

        else
        $return = spl_autoload_register();

        return $return;
    }


    // unregister
    // enlève une fonction du pool autoload
    final public static function unregister(callable $call):bool
    {
        return spl_autoload_unregister($call);
    }


    // unregisterAll
    // enlève toutes les fonctions autoload enregistrés
    final public static function unregisterAll():array
    {
        $return = [];

        foreach (self::all() as $key => $value)
        {
            $return[$key] = self::unregister($value);
        }

        return $return;
    }


    // all
    // retourne les fonctions autoload enregistrés
    final public static function all():array
    {
        $return = spl_autoload_functions();

        if(!is_array($return))
        $return = [];

        return $return;
    }


    // index
    // permet de retourner une callable autoload, via index
    final public static function index(int $index):?callable
    {
        return Arr::index($index,self::all());
    }


    // getPath
    // retourne le psr4 ainsi que le path à utiliser pour autoload la classe
    // ceci est utilisé par getFilePath et getDirPath
    final protected static function getPath(string $class,bool $different=true):?string
    {
        $return = null;
        $psr4 = self::getPsr4($class,$different);

        if(!empty($psr4))
        {
            $return = current($psr4);
            $key = key($psr4);
            $len = (strlen($key) + 1);
            $after = substr($class,$len);

            if(is_string($after) && strlen($after))
            {
                $after = str_replace('\\','/',$after);
                $return .= '/'.$after;
            }
        }

        return $return;
    }


    // getFilePath
    // retourne un chemin de classe à partir d'un fqcn
    // possible de spécifier s'il doit exister
    final public static function getFilePath(string $class,bool $exists=true,bool $different=true):?string
    {
        $return = self::getPath($class,$different);

        if(is_string($return))
        {
            $return .= '.'.self::phpExtension();

            if($exists === true && !file_exists($return))
            $return = null;
        }

        return $return;
    }


    // getDirPath
    // retourne un chemin de dossier à partir d'un fqcn
    // possible de spécifier s'il doit exister
    final public static function getDirPath(string $class,bool $exists=true,bool $different=false):?string
    {
        $return = self::getPath($class,$different);

        if(is_string($return) && $exists === true && !is_dir($return))
        $return = null;

        return $return;
    }


    // getPsr4
    // retourne un tableau clé valeur avec le psr4 à utiliser
    // sinon null
    final public static function getPsr4(string $class,bool $different=false):?array
    {
        $return = null;
        $source = self::$config['psr4'];

        foreach ($source as $key => $value)
        {
            if($different === false || $class !== $key)
            {
                if(strpos($class,$key) === 0)
                {
                    $return = [$key=>$value];
                    break;
                }
            }
        }

        return $return;
    }


    // setPsr4
    // change ou ajoute un point racine
    final public static function setPsr4(string $key,string $value):void
    {
        self::$config['psr4'][$key] = $value;

        return;
    }


    // setsPsr4
    // change ou ajoute plusieurs racine de classe
    final public static function setsPsr4(array $keyValue):void
    {
        foreach ($keyValue as $key => $value)
        {
            if(is_string($key) && is_string($value))
            self::$config['psr4'][$key] = $value;
        }

        return;
    }


    // unsetPsr4
    // enlève un point racine
    final public static function unsetPsr4(string $key):void
    {
        if(array_key_exists($key,self::$config['psr4']))
        unset(self::$config['psr4'][$key]);

        return;
    }


    // allPsr4
    // retourne le tableau des psr4
    // possible de fournir un callback et de sort
    final public static function allPsr4(?\Closure $closure=null,bool $sort=false):array
    {
        $return = self::$config['psr4'];

        if(!empty($closure))
        {
            foreach ($return as $key => $value)
            {
                if($closure($key,$value) !== true)
                unset($return[$key]);
            }
        }

        if($sort === true)
        ksort($return);

        return $return;
    }


    // removeAlias
    // retirer les noms de classes qui semblent être des alias
    // les alias sont stockés en lowercase par php
    // la logique quid vaut que la classe ait un namespace et que le nom termine par alias
    final public static function removeAlias(array $return):array
    {
        foreach ($return as $key => $value)
        {
            if(strtolower($value) === $value && strpos($value,'\\') && substr($value,-5) === 'alias')
            unset($return[$key]);
        }

        return $return;
    }


    // overview
    // génère un tableau multidimensionnel avec le count, size et line pour chaque namespace dans psr4
    // possible de filtrer par une closure
    final public static function overview(?\Closure $closure=null,bool $sort=true):array
    {
        $return = [];
        $extension = self::phpExtension();

        foreach (self::allPsr4($closure,$sort) as $key => $value)
        {
            $return[$key] = Dir::overview($value,$extension);
        }

        return $return;
    }


    // phpExtension
    // retourne l'extension de php
    final public static function phpExtension():string
    {
        return 'php';
    }
}
?>