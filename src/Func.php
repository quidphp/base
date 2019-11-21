<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Base;

// func
// class with static methods to work with simple functions
class Func extends Root
{
    // config
    public static $config = [];


    // is
    // retourne vrai si une fonction existe
    final public static function is($name):bool
    {
        return (is_string($name) && function_exists($name))? true:false;
    }


    // call
    // appelle une fonction
    final public static function call(string $name,...$arg)
    {
        $return = false;

        if(static::is($name))
        $return = $name(...$arg);

        return $return;
    }


    // all
    // retourne toutes les fonctions définis
    final public static function all():array
    {
        return get_defined_functions();
    }


    // user
    // retourne les fonctions définis par l'utilisateur
    final public static function user():array
    {
        $return = [];

        $all = static::all();
        if(array_key_exists('user',$all) && is_array($all['user']))
        $return = $all['user'];

        return $return;
    }
}
?>