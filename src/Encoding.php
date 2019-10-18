<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// encoding
// class which contains methods related to string encoding (manages mb overload)
class Encoding extends Root
{
    // config
    public static $config = [
        'charset'=>null, // charset
        'charsetMb'=>['UTF-8'], // charset qui retourne vrai à la méthode isCharsetMb
        'mb'=>false // active les fonctions multibyte
    ];


    // is
    // retourne vrai si la chaîne est valide pour un encodage spécifique
    public static function is($value,?string $charset=null):bool
    {
        return (is_string($value))? mb_check_encoding($value,static::getCharset($charset)):false;
    }


    // isMb
    // retourne vrai si la valeur est string et multibyte
    public static function isMb($value):bool
    {
        return (is_string($value) && strlen($value) !== mb_strlen($value,static::$config['charset']))? true:false;
    }


    // isMbs
    // retourne vrai si une des chaînes est multibyte
    public static function isMbs(...$values):bool
    {
        $return = false;

        foreach ($values as $value)
        {
            if(static::isMb($value))
            {
                $return = true;
                break;
            }
        }

        return $return;
    }


    // isCharsetMb
    // retourne vrai si le charset est multibyte
    public static function isCharsetMb($value):bool
    {
        return (is_string($value) && Arr::in($value,static::$config['charsetMb'],false))? true:false;
    }


    // exists
    // retourne vrai si l'encodage existe
    public static function exists(string $value):bool
    {
        return (in_array($value,mb_list_encodings(),true))? true:false;
    }


    // get
    // retourne l'encodage de la chaîne
    public static function get(string $value,$list=null,bool $strict=false):?string
    {
        return mb_detect_encoding($value,$list,$strict);
    }


    // set
    // change l'encodage de la chaîne
    public static function set(string $return,?string $charset=null,?string $from=null)
    {
        $charset = static::getCharset($charset);
        $from = static::getCharset($from);

        if($charset !== $from)
        $return = mb_convert_encoding($return,$charset,$from);

        return $return;
    }

    
    // scrub
    // permet de remplacer les caractères illégaux en ?
    public static function scrub(string $return,?string $charset=null):string 
    {
        return mb_scrub($return,static::getCharset($charset));
    }
    
    
    // getInternal
    // retourne l'encoding interne de l'extension mbstring
    public static function getInternal():string
    {
        return static::$config['charset'] = mb_internal_encoding();
    }

    
    // setInternal
    // change l'encoding interne de l'extension mbstring
    public static function setInternal(string $charset):bool
    {
        $return = mb_internal_encoding($charset);
        if($return === true)
        static::$config['charset'] = $charset;

        return $return;
    }


    // getCharset
    // retourne le charset actuel ou celui donné en argument si c'est une string
    public static function getCharset(?string $charset=null):string
    {
        return (is_string($charset))? $charset:static::$config['charset'];
    }


    // setCharset
    // change le charset actuel, change aussi dans la classe str
    public static function setCharset(string $value):void
    {
        static::$config['charset'] = $value;
        Str::$config['charset'] = $value;

        return;
    }


    // getMb
    // retourne si multibyte est true ou false
    public static function getMb($mb=null,$value=null):bool
    {
        $return = false;

        if(is_bool($mb))
        $return = $mb;

        elseif(is_bool(static::$config['mb']))
        $return = static::$config['mb'];

        elseif(is_string($value))
        $return = static::isMb($value);

        return $return;
    }


    // getMbs
    // retourne si multibyte est true ou false
    // si une des string contient un caractère multibyte, alors retourne true
    public static function getMbs($mb=null,...$values):bool
    {
        $return = false;

        if(is_bool($mb))
        $return = $mb;

        elseif(is_bool(static::$config['mb']))
        $return = static::$config['mb'];

        elseif(!empty($values))
        $return = static::isMbs(...$values);

        return $return;
    }


    // setMb
    // change la valeur par défaut de mb
    public static function setMb($mb):bool
    {
        $return = false;

        if(is_string($mb))
        $mb = static::isMb($mb);

        if(is_bool($mb) || $mb === null)
        {
            static::$config['mb'] = $mb;
            $return = true;
        }

        return $return;
    }


    // toUtf8
    // alias pour utf8_encode
    public static function toUtf8(string $value):string
    {
        return utf8_encode($value);
    }


    // fromUtf8
    // alias pour utf8_decode
    public static function fromUtf8(string $value):string
    {
        return utf8_decode($value);
    }


    // info
    // retourne les informations sur l'extension mbstring
    public static function info():array
    {
        return mb_get_info();
    }


    // all
    // affiche tous les encodages supportés
    public static function all():array
    {
        return mb_list_encodings();
    }
}

// init
Encoding::setCharset('UTF-8');
?>