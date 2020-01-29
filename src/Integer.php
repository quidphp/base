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

// integer
// class with static methods to work integers
class Integer extends Root
{
    // config
    public static $config = [];


    // typecast
    // typecasts des valeurs int par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (int) $value;
        }

        return;
    }


    // cast
    // cast une valeur en int, utilise num::cast
    final public static function cast($value,bool $extra=true):?int
    {
        $return = null;
        $value = Num::cast($value,$extra);
        if(is_numeric($value))
        $return = (int) $value;

        return $return;
    }


    // is
    // retourne vrai si la valeur est int
    final public static function is($value):bool
    {
        return is_int($value);
    }


    // isEmpty
    // retourne vrai si la valeur est int et vide
    final public static function isEmpty($value):bool
    {
        return is_int($value) && empty($value);
    }


    // isNotEmpty
    // retourne vrai si la valeur est int et non vide
    final public static function isNotEmpty($value):bool
    {
        return is_int($value) && !empty($value);
    }


    // isCast
    // vérifie que la valeur est numérique et un int après cast
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
    // vérifie que la valeur est numérique et un int après cast et n'est pas 0
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
    final public static function fromString(string $value):?int
    {
        $return = null;
        $value = Str::keepNumeric($value);
        $value = static::cast($value);

        if(is_int($value))
        $return = $value;

        return $return;
    }


    // fromBool
    // retourne un numéro à partir d'un boolean
    final public static function fromBool(bool $bool):?int
    {
        $return = null;

        if($bool === true)
        $return = 1;

        elseif($bool === false)
        $return = 0;

        return $return;
    }


    // toggle
    // toggle des valeurs primaires (1/0)
    final public static function toggle($value)
    {
        $return = null;

        if($value === 1)
        $return = 0;

        elseif($value === 0)
        $return = 1;

        return $return;
    }
    
    
    // range
    // fonction crée pour contourner un bogue dans range -> si min = 2, max = 3, inc = 2
    // si combine est true, alors les clés seront la même chose que les valeurs
    final public static function range(int $min,int $max,int $inc=1,bool $combine=false):array
    {
        $return = [];

        if(($max > 0 && (($max - $inc) < $min)) || $max < $min)
        $max = $min;

        $return = range($min,$max,$inc);

        if($combine === true)
        $return = array_combine($return,$return);

        return $return;
    }
}
?>