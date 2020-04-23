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

// scalar
// class with static methods to deal with scalar types
class Scalar extends Root
{
    // config
    public static $config = [];


    // typecast
    // envoie à la méthode cast
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = static::cast($value);
        }

        return;
    }


    // typecastMore
    // envoie à la méthode castMore
    final public static function typecastMore(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = static::castMore($value);
        }

        return;
    }


    // cast
    // cast une scalaire dans son type naturel
    // par défaut, seul les nombres sont convertis
    final public static function cast($value,int $numberCast=1,int $boolCast=0)
    {
        $return = null;

        if(is_scalar($value))
        {
            $return = $value;

            // numberCast
            if(!is_bool($value) && $numberCast > 0)
            {
                $extra = ($numberCast === 2);
                $value = Num::cast($value,$extra);
            }

            // boolCast
            if(!is_numeric($value) && $boolCast > 0)
            {
                $extra = ($boolCast === 2);
                $value = Boolean::cast($value,$extra);
            }

            // retour
            if($return !== $value)
            $return = $value;
        }

        return $return;
    }


    // castMore
    // envoie à cast avec paramètre 2,1
    // nombre sont convertis, virgule remplacer par décimal, et les string booleans sont transformés en bool
    final public static function castMore($value)
    {
        return static::cast($value,2,1);
    }


    // is
    // retourne vrai si la valeur est scalar
    final public static function is($value):bool
    {
        return (is_scalar($value));
    }


    // isEmpty
    // retourne vrai si la valeur est scalar et vide
    final public static function isEmpty($value):bool
    {
        return (is_scalar($value) && empty($value));
    }


    // isNotEmpty
    // retourne vrai si la valeur est scalar et non vide
    final public static function isNotEmpty($value):bool
    {
        return (is_scalar($value) && !empty($value));
    }


    // isNotBool
    // retourne vrai si scalar mais pas bool
    final public static function isNotBool($value):bool
    {
        return (is_scalar($value) && !is_bool($value));
    }


    // isNotNumeric
    // retourne vrai si scalar mais pas numérique
    final public static function isNotNumeric($value):bool
    {
        return (is_scalar($value) && !is_numeric($value));
    }


    // isNotInt
    // retourne vrai si scalar mais pas int
    final public static function isNotInt($value):bool
    {
        return (is_scalar($value) && !is_int($value));
    }


    // isNotFloat
    // retourne vrai si scalar mais pas float
    final public static function isNotFloat($value):bool
    {
        return (is_scalar($value) && !is_float($value));
    }


    // isNotString
    // retourne vrai si scalar mais pas string
    final public static function isNotString($value):bool
    {
        return (is_scalar($value) && !is_string($value));
    }


    // isLength
    // retourne vrai si la length est celle spécifié
    final public static function isLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_scalar($value) && Str::len((string) $value,$mb) === $length);
    }


    // isMinLength
    // retourne vrai si la length est plus grande ou égale que celle spécifié
    final public static function isMinLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_scalar($value) && Str::len((string) $value,$mb) >= $length);
    }


    // isMaxLength
    // retourne vrai si la length est plus petite ou égale que celui spécifié
    final public static function isMaxLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_scalar($value) && Str::len((string) $value,$mb) <= $length);
    }
}
?>