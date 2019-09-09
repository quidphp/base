<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// boolean
// class with static methods to deal with boolean type
class Boolean extends Root
{
    // config
    public static $config = [];


    // typecast
    // typecasts des valeurs par référence
    public static function typecast(&...$values):void
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
    public static function cast($value,bool $extra=true)
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
    public static function is($value):bool
    {
        return (is_bool($value))? true:false;
    }


    // isTrue
    // retourne vrai si la valeur est vrai
    public static function isTrue($value):bool
    {
        return ($value === true)? true:false;
    }


    // isFalse
    // retourne vrai si la valeur est false
    public static function isFalse($value):bool
    {
        return ($value === false)? true:false;
    }


    // isNull
    // retourne vrai si la valeur est null
    public static function isNull($value):bool
    {
        return ($value === null)? true:false;
    }


    // random
    // génère un boolean random
    // si min est 1 et max est 1 alors 100% de générer un true
    // option pour utiliser csprng
    public static function random(int $min=0,int $max=1,bool $csprng=false):bool
    {
        $return = false;

        if($csprng === true)
        $return = Crypt::randomBool($min,$max);
        else
        {
            $int = mt_rand($min,$max);
            $return = ($int === 1)? true:false;
        }

        return $return;
    }


    // str
    // retourne la version str d'un booléan
    public static function str($value):string
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
    // toggle des valeurs primaires
    public static function toggle($value)
    {
        $return = null;

        if($value === true)
        $return = false;

        elseif($value === false)
        $return = true;

        elseif($value === 1)
        $return = 0;

        elseif($value === 0)
        $return = 1;

        elseif($value === '1')
        $return = '0';

        elseif($value === '0')
        $return = '1';

        return $return;
    }
}
?>