<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// func
// class with static methods to work with simple functions
final class Func extends Root
{
    // config
    protected static array $config = [];


    // is
    // retourne vrai si une fonction existe
    final public static function is($name):bool
    {
        return is_string($name) && function_exists($name);
    }


    // call
    // appelle une fonction
    final public static function call(string $name,...$arg)
    {
        return (self::is($name))? $name(...$arg):null;
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
        return self::all()['user'] ?? [];
    }
}
?>