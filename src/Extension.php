<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Base;

// extension
// class which contains methods to deal with PHP extensions
class Extension extends Root
{
    // config
    public static $config = [
        'required'=>[ // extensions requises
            'curl','date','fileinfo','gd','iconv','json','mbstring','pcre',
            'PDO','pdo_mysql','openssl','session','SimpleXML','Zend OPcache','zip']
    ];


    // is
    // retourne vrai si une extension est chargé
    final public static function is(string $name):bool
    {
        return (is_string($name) && extension_loaded($name))? true:false;
    }


    // hasOpCache
    // retourne vrai si l'extension opcache est chargé
    final public static function hasOpCache(bool $ini=false):bool
    {
        $return = static::is('Zend OPcache');

        if($return === true && $ini === true)
        $return = Ini::opcache();

        return $return;
    }


    // hasXdebug
    // retourne vrai si l'extension xdebug est chargé
    final public static function hasXdebug(bool $ini=false):bool
    {
        $return = static::is('xdebug');

        if($return === true && $ini === true)
        $return = Ini::xdebug();

        return $return;
    }


    // hasApcu
    // retourne vrai si l'extension apcu est chargé
    final public static function hasApcu(bool $ini=false):bool
    {
        $return = static::is('apcu');

        if($return === true && $ini === true)
        $return = Ini::apcu();

        return $return;
    }


    // functions
    // retourne les fonctions d'une extension
    final public static function functions(string $name):array
    {
        $return = [];

        if(static::is($name))
        $return = get_extension_funcs($name);

        return $return;
    }


    // important
    // retourn un tableau avec les résultats des méthodes pour détecter opcache, xdebug et apcu
    final public static function important(bool $ini=false):array
    {
        $return = [];
        $return['opcache'] = static::hasOpCache($ini);
        $return['xdebug'] = static::hasXdebug($ini);
        $return['apcu'] = static::hasApcu($ini);

        return $return;
    }


    // all
    // retourne les extensions php
    final public static function all():array
    {
        return get_loaded_extensions();
    }


    // requirement
    // lance les tests de requirement
    final public static function requirement():array
    {
        $return = [];

        foreach (static::$config['required'] as $value)
        {
            if(!static::is($value))
            $return[] = $value;
        }

        return $return;
    }
}
?>