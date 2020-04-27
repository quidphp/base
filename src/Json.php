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

// json
// class with static methods to encode and decode JSON
final class Json extends Assoc
{
    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'encode'=>JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES, // flag encode
            'decode'=>JSON_INVALID_UTF8_IGNORE | JSON_BIGINT_AS_STRING, // flag decode
            'depth'=>512, // depth pour encode et decode
            'assoc'=>true, // option assoc pour decode
            'case'=>null, // les clés sont ramenés dans cette case dans arr
            'sort'=>null] // les clés sont sort
    ];


    // is
    // retourne vrai si la chaîne est du json
    final public static function is($value):bool
    {
        return is_string($value) && self::decode($value) !== null;
    }


    // isEmpty
    // retourne vrai si la chaîne est du json mais vide
    final public static function isEmpty($value):bool
    {
        return is_string($value) && ($json = self::decode($value)) !== null && empty($json);
    }


    // isNotEmpty
    // retourne vrai si la chaîne est du json non vide
    final public static function isNotEmpty($value):bool
    {
        return is_string($value) && ($json = self::decode($value)) !== null && !empty($json);
    }


    // encode
    // encode une variable en json
    // option à null enlève les options, si option est set remplace les options par défaut
    // note: json_encode retourne false si une erreur survient, à ce moment une erreur est déclenché
    final public static function encode($value,?int $flag=null,?int $depth=null):?string
    {
        $return = null;
        $option = self::option();

        $flag = ($flag === null)? $option['encode']:$flag;
        $depth = ($depth === null)? $option['depth']:$depth;
        $return = json_encode($value,$flag,$depth);

        return $return;
    }


    // encodeOption
    // encode une variable en json
    // option append les options par défaut
    final public static function encodeOption($value,int $flag,?int $depth=null):?string
    {
        $return = null;
        $option = self::option();
        $flag = $option['encode'] | $flag;
        $return = self::encode($value,$flag,$depth);

        return $return;
    }


    // encodePretty
    // encode une variable en json
    // append json_pretty_print aux options par défaut
    final public static function encodePretty($value,int $depth=null):?string
    {
        $return = null;
        $option = self::option();
        $flag = $option['encode'] | JSON_PRETTY_PRINT;
        $return = self::encode($value,$flag,$depth);

        return $return;
    }


    // encodeSpecialchars
    // encode en json et envoie la string dans specialchars
    final public static function encodeSpecialchars($value,?int $flag=null,?int $depth=null):?string
    {
        $return = '';
        $json = self::encode($value,$flag,$depth);

        if(is_string($json))
        $return = Html::specialchars($json);

        return $return;
    }


    // encodeVar
    // encode une valeur et retourne la dans une variable javascript
    final public static function encodeVar(string $var,$value,?int $flag=null,?int $depth=null):?string
    {
        $return = null;
        $value = self::encode($value,$flag,$depth);

        if(is_string($value))
        $return = self::var($var,$value);

        return $return;
    }


    // var
    // écrit une valeur js dans une variable javascript
    final public static function var(string $var,string $value):string
    {
        $return = $var;
        $return .= ' = ';
        $return .= $value;
        $return .= ';';

        return $return;
    }


    // decode
    // decode une chaine json
    // option à null enlève les options
    // note: json_decode retourne false si une erreur survient
    final public static function decode(string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
    {
        $return = null;
        $option = self::option();

        $assoc = ($assoc === null)? $option['assoc']:$assoc;
        $depth = ($depth === null)? $option['depth']:$depth;
        $flag = ($flag === null)? $option['decode']:$flag;

        $return = json_decode($value,$assoc,$depth,$flag);

        return $return;
    }


    // decodeKeys
    // decode une chaîne json et retourne les clés demandés
    final public static function decodeKeys(array $keys,string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
    {
        $return = null;
        $decode = self::decode($value,$assoc,$flag,$depth);

        if(is_array($decode))
        $return = Arr::gets($keys,$decode);

        return $return;
    }


    // decodeKeysExists
    // decode une chaîne json et retourne le tableau seulement si les clés existent
    final public static function decodeKeysExists(array $keys,string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
    {
        $return = null;
        $decode = self::decode($value,$assoc,$flag,$depth);

        if(is_array($decode) && Arr::keysExists($keys,$decode))
        $return = $decode;

        return $return;
    }


    // error
    // retourne les informations sur la dernière erreur json
    final public static function error():array
    {
        return ['code'=>json_last_error(),'msg'=>json_last_error_msg()];
    }


    // arr
    // explose une string json
    // retourne tableau vide si après decode ce n'est pas un tableau
    final public static function arr($value,?array $option=null):array
    {
        $return = [];
        $option = self::option($option);

        if(is_scalar($value))
        $value = self::decode($value,$option['assoc'],$option['decode'],$option['depth']);

        if(is_array($value))
        {
            $return = Arr::trimClean($value,$option['trim'],$option['trim'],$option['clean']);

            if($option['case'] !== null)
            $return = Arr::keysChangeCase($option['case'],$return);

            if($option['sort'] !== null)
            $return = Arr::sort($return,$option['sort']);
        }

        return $return;
    }


    // onSet
    // helper pour une méthode onSet de colonne
    // encode en json si array ou objet
    final public static function onSet($return)
    {
        if(is_array($return) || is_object($return))
        $return = self::encode($return);

        return $return;
    }


    // onGet
    // helper pour une méthode onGet de colonne
    // décode de json si scalar
    final public static function onGet($return)
    {
        if(is_scalar($return))
        $return = self::decode($return);

        return $return;
    }
}

// init
Json::__init();
?>