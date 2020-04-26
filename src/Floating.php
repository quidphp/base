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

// floating
// class with static methods to work with floating numbers
class Floating extends Root
{
    // config
    public static array $config = [];


    // typecast
    // typecasts des valeurs float par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (float) $value;
        }

        return;
    }


    // cast
    // cast une valeur en float, utilise num::cast
    final public static function cast($value,bool $extra=true):?float
    {
        $return = null;
        $value = Num::cast($value,$extra);
        if(is_numeric($value))
        $return = (float) $value;

        return $return;
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

            if(self::is($value))
            $return = true;
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

            if(self::isNotEmpty($value))
            $return = true;
        }

        return $return;
    }


    // fromString
    // permet de cast une valeur string en gardant seulement ces caractères numériques
    final public static function fromString(string $value):?float
    {
        $return = null;
        $value = Str::keepNumeric($value);
        $value = static::cast($value);

        if(is_float($value))
        $return = $value;

        return $return;
    }
}
?>