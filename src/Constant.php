<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// constant
// class with static methods to work with PHP constants
final class Constant extends Root
{
    // config
    protected static array $config = [];


    // is
    // retourne vrai si la constante est défini
    final public static function is($name):bool
    {
        return is_string($name) && defined($name);
    }


    // get
    // retourne la valeur d'une constante définie
    final public static function get(string $name)
    {
        return (defined($name))? constant($name):null;
    }


    // set
    // crée une nouvelle constante si elle n'existe pas
    final public static function set(string $name,$value):bool
    {
        return (!empty($name) && !self::is($name))? define($name,$value):false;
    }


    // all
    // retourne toutes les constantes définis
    final public static function all(?string $key=null,bool $categorize=true):array
    {
        $return = [];
        $constants = get_defined_constants($categorize);

        if(!empty($key))
        {
            if(array_key_exists($key,$constants))
            $return = $constants[$key];
        }

        else
        $return = $constants;

        return $return;
    }


    // user
    // retourne les constantes définis par l'utilisateur
    final public static function user(bool $categorize=true):array
    {
        return self::all('user',$categorize);
    }
}
?>