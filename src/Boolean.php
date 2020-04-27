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

// boolean
// class with static methods to deal with boolean type
final class Boolean extends Root
{
    // config
    protected static array $config = [];


    // typecast
    // typecasts des valeurs par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (bool) $value;
        }

        return;
    }


    // cast
    // cast une boolean
    // boolCast à 1 remplace les string
    final public static function cast($value,bool $extra=true)
    {
        $return = null;

        if(is_scalar($value))
        {
            $return = $value;

            if(is_string($value))
            {
                $value = strtolower($value);

                if($value === 'true')
                $return = true;

                elseif($value === 'false')
                $return = false;

                elseif($value === 'null')
                $return = null;
            }

            if($extra === true)
            {
                if(is_numeric($value))
                {
                    if((int) $value === 1)
                    $return = true;

                    elseif((int) $value === 0)
                    $return = false;
                }

                elseif(is_string($value))
                {
                    if($value === 'on')
                    $return = true;

                    elseif($value === 'off')
                    $return = false;

                    elseif($value === 'undefined')
                    $return = null;
                }
            }
        }

        return $return;
    }


    // is
    // retourne vrai si la valeur est boolean
    final public static function is($value):bool
    {
        return is_bool($value);
    }


    // isEmpty
    // retourne vrai si la valeur est false
    final public static function isEmpty($value):bool
    {
        return $value === false;
    }


    // isNotEmpty
    // retourne vrai si la valeur est vrai
    final public static function isNotEmpty($value):bool
    {
        return $value === true;
    }


    // random
    // génère un boolean random
    // si min est 1 et max est 1 alors 100% de générer un true
    // option pour utiliser csprng
    final public static function random(int $min=0,int $max=1,bool $csprng=false):bool
    {
        $return = false;

        if($csprng === true)
        $return = Crypt::randomBool($min,$max);
        else
        {
            $int = mt_rand($min,$max);
            $return = ($int === 1);
        }

        return $return;
    }


    // str
    // retourne la version str d'un booléan
    final public static function str(?bool $value):string
    {
        $return = '';

        if($value === true)
        $return = 'TRUE';

        elseif($value === false)
        $return = 'FALSE';

        elseif($value === null)
        $return = 'NULL';

        return $return;
    }


    // toggle
    // toggle des valeurs primaires (true/false)
    final public static function toggle(bool $value):bool
    {
        $return = null;

        if($value === true)
        $return = false;

        elseif($value === false)
        $return = true;

        return $return;
    }


    // toInt
    // retourne un numéro à partir d'un boolean
    final public static function toInt(bool $bool):?int
    {
        $return = null;

        if($bool === true)
        $return = 1;

        elseif($bool === false)
        $return = 0;

        return $return;
    }
}
?>