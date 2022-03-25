<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
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
        return (Arr::isKey($key) && array_key_exists($key,$GLOBALS))? $GLOBALS[$key]:null;
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
    final public static function set($key,$value):void
    {
        if(Arr::isKey($key))
        $GLOBALS[$key] = $value;

        return;
    }


    // unset
    // enlève une ou plusieurs variables globales
    final public static function unset(...$keys):void
    {
        foreach ($keys as $key)
        {
            if(array_key_exists($key,$GLOBALS))
            unset($GLOBALS[$key]);
        }
    }
}
?>