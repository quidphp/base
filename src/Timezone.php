<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// timezone
// class with static methods to deal with timezone
final class Timezone extends Root
{
    // config
    protected static array $config = [
        'current'=>null, // conserve le timezone courant pour le set/reset
        'default'=>'UTC' // timezone par défaut, lorsque value est true
    ];


    // is
    // retourne vrai si la timezone existe
    final public static function is(mixed $value):bool
    {
        return is_string($value) && in_array($value,self::all(),true);
    }


    // get
    // retourne le timezone courant
    final public static function get():string
    {
        return date_default_timezone_get();
    }


    // set
    // change le timezone courant
    // cette valeur prend le dessus sur le ini default_timezone
    // possible de set dans ini aussi
    final public static function set(bool|string $value,bool $ini=false):bool
    {
        $return = false;

        if($value === true)
        $value = self::$config['default'];

        if(is_string($value))
        {
            self::$config['current'] = self::get();
            $return = date_default_timezone_set($value);

            if($return === true && $ini === true)
            Ini::setTimezone($value);
        }

        return $return;
    }


    // reset
    // remet le timezone de ini comme timezone courant
    final public static function reset(bool $ini=false):bool
    {
        $return = false;
        $timezone = null;

        if($ini === false)
        $timezone = self::$config['current'];

        if($timezone === null)
        $timezone = Ini::getTimezone();

        $return = date_default_timezone_set($timezone);
        self::$config['current'] = $timezone;

        return $return;
    }


    // name
    // retourne le nom d'un timezone à partir d'une abbréviation
    final public static function name(string $abbr,int $offset=-1,int $isDst=-1):?string
    {
        return timezone_name_from_abbr($abbr,$offset,$isDst);
    }


    // location
    // retourne l'emplacement d'un timezone à partir d'un nom de timezone
    final public static function location(string $value):array
    {
        return timezone_location_get(timezone_open($value));
    }


    // transitions
    // retourne les transitions d'un timezone à partir d'un nom de timezone
    final public static function transitions(string $value,?int $begin=null,?int $end=null):array
    {
        $return = [];
        $timezone = timezone_open($value);

        if(!empty($begin))
        {
            if(!empty($end))
            $return = timezone_transitions_get($timezone,$begin,$end);
            else
            $return = timezone_transitions_get($timezone,$begin);
        }

        else
        $return = timezone_transitions_get($timezone);

        return $return;
    }


    // suninfo
    // retourne les informations relatives au soleil pour une timezone
    // possible de fournir un tableau avec une clé latitude et une clé longitude
    final public static function suninfo(string|array $value,mixed $timestamp=null,mixed $format=null):?array
    {
        $return = null;
        $timestamp = Datetime::time($timestamp);
        $timezone = null;

        if(is_string($value))
        {
            $timezone = $value;
            $value = self::location($value);
        }

        if(is_array($value) && is_int($timestamp) && array_key_exists('latitude',$value) && array_key_exists('longitude',$value))
        {
            $return = date_sun_info($timestamp,$value['latitude'],$value['longitude']);

            if(!empty($format) && !empty($return))
            $return = Datetime::formats($format,$return);
        }

        return $return;
    }


    // version
    // retourne la version de la db timezone
    final public static function version():string
    {
        return timezone_version_get();
    }


    // abbreviations
    // retourne un tableau multidimensionnel avec tous les timezone dans leur abbréviations
    final public static function abbreviations():array
    {
        return timezone_abbreviations_list();
    }


    // all
    // retourne un tableau avec tous les timezone
    final public static function all():array
    {
        return timezone_identifiers_list();
    }
}
?>