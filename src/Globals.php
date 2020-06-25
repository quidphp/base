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

// globals
// class with static methods to manage global variables
final class Globals extends Root
{
    // config
    protected static array $config = [];


    // is
    // retourne vrai si la variable est une globale
    final public static function is($key):bool
    {
        return Arr::isKey($key) && array_key_exists($key,$GLOBALS);
    }


    // get
    // retourne la valeur d'une variable globale
    final public static function get($key)
    {
        $return = null;

        if(Arr::isKey($key) && array_key_exists($key,$GLOBALS))
        $return = $GLOBALS[$key];

        return $return;
    }


    // all
    // retourne toutes les variables globales
    // retourne une référence
    final public static function &all():array
    {
        return $GLOBALS;
    }


    // set
    // change la valeur d'une variable globale
    final public static function set($key,$value):bool
    {
        $return = false;

        if(Arr::isKey($key))
        {
            $GLOBALS[$key] = $value;
            $return = true;
        }

        return $return;
    }


    // unset
    // enlève une ou plusieurs variables globales
    final public static function unset(...$keys):void
    {
        Arr::unsetsRef($keys,$GLOBALS);
    }
}
?>