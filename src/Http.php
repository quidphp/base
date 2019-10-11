<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// http
// class with static methods related to the HTTP protocol
class Http extends Root
{
    // config
    public static $config = [
        'str'=>[ // configuration pour la méthode str
            'all'=>['absolute','timestamp','method'],
            'delimiter'=>'|']
    ];


    // isScheme
    // retourne vrai si le scheme est compatible http
    public static function isScheme($value):bool
    {
        return (is_string($value) && in_array(strtolower($value),['http','https'],true))? true:false;
    }


    // isHost
    // retourne vrai si la valeur est un host potentiel
    public static function isHost($value):bool
    {
        return (is_string($value) && !empty($value))? true:false;
    }


    // isPort
    // retourne vrai si le port fourni est compatible http
    public static function isPort($value):bool
    {
        return ($value === 80 || $value === 443)? true:false;
    }


    // isPortSsl
    // retourne vrai si le port fourni est https
    public static function isPortSsl($value):bool
    {
        return ($value === 443)? true:false;
    }
    
    
    // isMethod
    // retourne vrai si la méthode de requête http est valide
    public static function isMethod($value)
    {
        $return = false;

        if(is_string($value))
        {
            $value = strtolower($value);

            if(in_array($value,['get','post'],true))
            $return = true;
        }

        return $return;
    }

    
    // protocol
    // retourne le protocole selon si c'est http2 ou non
    public static function protocol(bool $http2=false):?string
    {
        $return = "HTTP/";
        $return .= ($http2 === true)? '2.0':'1.1';

        return $return;
    }
    
    
    // scheme
    // retourne le scheme à partir d'un booléan ssl ou un numéro de port
    public static function scheme($value):?string
    {
        $return = null;

        if(is_scalar($value))
        {
            if($value === 443 || $value === true)
            $return = 'https';

            elseif($value === 80 || $value === false)
            $return = 'http';

            elseif(in_array($value,['http','https'],true))
            $return = $value;
        }

        return $return;
    }


    // port
    // retourne le numéro de port à partir d'un scheme ou un booléean ssl
    public static function port($value):?int
    {
        $return = null;
        $value = (is_string($value))? strtolower($value):$value;

        if(is_scalar($value))
        {
            if($value === 'https' || $value === true)
            $return = 443;

            elseif($value === 'http' || $value === false)
            $return = 80;

            elseif(in_array($value,[80,443],true))
            $return = $value;
        }

        return $return;
    }


    // ssl
    // retourne le booléean ssl à partir d'un scheme, d'un booléen ou d'un numéro de port
    public static function ssl($value):bool
    {
        $return = false;
        $value = (is_string($value))? strtolower($value):$value;

        if(is_scalar($value) && ($value === 'https' || $value === 443 || $value === true))
        $return = true;

        return $return;
    }


    // str
    // retourne un tableau info requête http en une string
    // possible de donner des all en plus via option
    public static function str(array $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::append(static::$config['str']['all'],$option);
        $str = [];

        foreach ($option as $key)
        {
            if(is_string($key) && array_key_exists($key,$value) && is_scalar($value[$key]))
            {
                $v = $value[$key];

                if($key === 'method' && is_string($v))
                $v = strtoupper($v);

                if(is_bool($v))
                $v = Boolean::str($v);

                $str[] = $v;
            }
        }

        if(!empty($str))
        $return = implode(static::$config['str']['delimiter'],$str);

        return $return;
    }


    // arr
    // retourne un tableau à partir d'une string généré par la méthode str
    // possible de donner des all en plus via option
    public static function arr(string $value,?array $option=null):array
    {
        $return = [];
        $option = Arr::append(static::$config['str']['all'],$option);
        $explode = Arr::castMore(Str::explode(static::$config['str']['delimiter'],$value));

        if(count($explode) === count($option))
        {
            foreach ($option as $i => $key)
            {
                if(array_key_exists($i,$explode))
                $return[$key] = $explode[$i];
            }
        }

        return $return;
    }
}
?>