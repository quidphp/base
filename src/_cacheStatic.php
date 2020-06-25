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

// _cacheStatic
// trait that provides methods to get or set a cached value from a static property
trait _cacheStatic
{
    // cacheStatic
    protected static array $cacheStatic = []; // conserve les données de la cache statique, important de recréer la propriété dans chaque classe sinon la cache sera partagée


    // cacheStatic
    // retourne la cache si existante, sinon crée la cache à partir de la closure sans argument
    // si callable est null, unset
    final public static function cacheStatic($key,?\Closure $closure=null,bool $cache=true)
    {
        $return = null;

        if($cache === true)
        {
            $key = Obj::cast($key);
            $key = Str::cast($key,'-');

            if(is_string($key) && strlen($key))
            {
                if($closure === null)
                Arr::unsetRef($key,static::$cacheStatic);

                else
                {
                    if(Arr::keyExists($key,static::$cacheStatic))
                    $return = Arr::get($key,static::$cacheStatic);

                    else
                    {
                        $return = $closure();
                        Arr::setRef($key,$return,static::$cacheStatic);
                    }
                }
            }
        }

        elseif(!empty($closure))
        $return = $closure();

        return $return;
    }


    // emptyCacheStatic
    // vide le tableau de cache statique
    final public static function emptyCacheStatic():void
    {
        static::$cacheStatic = [];
    }


    // allCacheStatic
    // retourne le tableau de la cache statique
    final public static function allCacheStatic():array
    {
        return static::$cacheStatic;
    }
}
?>