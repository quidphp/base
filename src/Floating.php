<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// floating
// class with static methods to work with floating numbers
final class Floating extends Root
{
    // config
    protected static array $config = [];


    // typecast
    // typecasts des valeurs float par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (float) $value;
        }
    }


    // cast
    // cast une valeur en float, utilise num::cast
    final public static function cast($value,bool $commaToDecimal=true):?float
    {
        $float = Num::castMore($value,$commaToDecimal);
        return (is_numeric($float))? (float) $float:null;
    }


    // is
    // retourne vrai si la valeur est float
    final public static function is($value):bool
    {
        return is_float($value);
    }


    // isEmpty
    // retourne vrai si la valeur est float et vide
    final public static function isEmpty($value):bool
    {
        return is_float($value) && empty($value);
    }


    // isNotEmpty
    // retourne vrai si la valeur est float et non vide
    final public static function isNotEmpty($value):bool
    {
        return is_float($value) && !empty($value);
    }


    // isCast
    // vérifie que la valeur est numérique et float après cast
    final public static function isCast($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            Num::typecast($value);
            $return = (self::is($value));
        }

        return $return;
    }


    // isCastNotEmpty
    // vérifie que la valeur est numérique et un après après cast et n'est pas 0
    final public static function isCastNotEmpty($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            Num::typecast($value);
            $return = (self::isNotEmpty($value));
        }

        return $return;
    }
}
?>