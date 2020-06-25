<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// vari
// class with some general static methods related to variables
final class Vari extends Root
{
    // config
    protected static array $config = [];


    // isEmpty
    // retourne vrai si empty
    final public static function isEmpty($value):bool
    {
        return empty($value);
    }


    // isNotEmpty
    // inverse de isEmpty
    final public static function isNotEmpty($value):bool
    {
        return !self::isEmpty($value);
    }


    // isReallyEmpty
    // retourne vrai si empty, sans etre numérique ni boolean ni une string avec une longueur
    // en somme, ca retourne faux pour 0, '0' et false
    // si removeWhiteSpace est true et que c'est une string, envoie dans str::removeWhiteSpace avant
    final public static function isReallyEmpty($value,bool $removeWhiteSpace=false):bool
    {
        $return = false;

        if($removeWhiteSpace === true && is_string($value))
        $value = Str::removeWhiteSpace($value);

        $return = (empty($value) && !is_numeric($value) && !is_bool($value) && !(is_string($value) && strlen($value)));

        return $return;
    }


    // isNotReallyEmpty
    // inverse de isReallyEmpty
    final public static function isNotReallyEmpty($value,bool $removeWhiteSpace=false):bool
    {
        return (self::isReallyEmpty($value,$removeWhiteSpace))? false:true;
    }


    // isNull
    // retourne vrai si la valeur est null
    final public static function isNull($value):bool
    {
        return $value === null;
    }


    // isType
    // retourne vrai si la variable est du type fournie en argument
    final public static function isType($type,$value):bool
    {
        return self::type($value) === $type;
    }


    // sameType
    // vérifie que toutes les valeurs donnés ont le même type ou la même instance de classe
    final public static function sameType(...$values):bool
    {
        $return = false;

        foreach ($values as $v)
        {
            if(!empty($type))
            {
                if(!self::isType($type,$v))
                $return = false;

                elseif(!empty($class) && (!is_object($v) || !is_a($v,$class)))
                $return = false;

                else
                $return = true;

                if($return === false)
                break;
            }

            $type = self::type($v);
            $class = (is_object($v))? get_class($v):false;
        }

        return $return;
    }


    // type
    // retourne le type de la variable
    final public static function type($value):string
    {
        return gettype($value);
    }
}
?>