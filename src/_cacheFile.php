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

// _cacheFile
// trait that provides methods to get or set a cached value from a file
trait _cacheFile
{
    // cacheFile
    protected static $cacheFile = null; // dirname pour le storage, peut être changé par classe mais il faut répéter cette propriété


    // cacheFile
    // retourne la cache si existante, sinon crée la cache à partir de la closure sans argument
    // si callable est null, unset
    final public static function cacheFile($key,?\Closure $closure=null,bool $cache=true)
    {
        $return = null;

        if($cache === true)
        {
            $key = Obj::cast($key);
            $key = Str::cast($key,'-');

            if(is_string($key) && strlen($key))
            {
                $key = str_replace('.','_',$key);
                $key = Path::safeBasename($key);
                $storage = static::getCacheFileStorage();
                $path = Path::addBasename($key,$storage);

                if($closure === null)
                $return = File::unlink($path);

                else
                {
                    if(File::isReadable($path))
                    {
                        $get = File::get($path);
                        if(is_string($get))
                        $return = Crypt::unserialize($get);
                    }

                    else
                    {
                        $return = $closure();
                        $set = Crypt::serialize($return);
                        File::set($path,$set);
                    }
                }
            }
        }

        elseif(!empty($closure))
        $return = $closure();

        return $return;
    }


    // getCacheFileStorage
    // retourne le chemin du storage pour la classe
    // va envoyer une exception si cacheFile est toujours null
    final public static function getCacheFileStorage():string
    {
        $return = null;

        if(is_string(static::$cacheFile))
        {
            $return = Finder::normalize(static::$cacheFile);
            $class = str_replace('\\','',static::class);
            $return = Str::replace(['%class%'=>$class],$return);
        }

        return $return;
    }


    // setCacheFileStorage
    // permet de changer le chemin du storage pour la file cache
    final public static function setCacheFileStorage(string $value):void
    {
        static::$cacheFile = $value;

        return;
    }


    // emptyCacheFile
    // efface tous les fichiers de cache pour la classe
    final public static function emptyCacheFile():bool
    {
        return Dir::emptyAndUnlink(static::getCacheFileStorage());
    }


    // allCacheFile
    // retourne tous les fichiers de cache pour la classe
    final public static function allCacheFile():array
    {
        return Dir::get(static::getCacheFileStorage());
    }
}
?>