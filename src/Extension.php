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

// extension
// class which contains methods to deal with PHP extensions
final class Extension extends Root
{
    // config
    protected static array $config = [
        'required'=>[ // extensions requises
            'ctype','curl','date','fileinfo','gd','iconv','json','mbstring','pcre',
            'PDO','pdo_mysql','openssl','session','SimpleXML','zip']
    ];


    // is
    // retourne vrai si une extension est chargé
    final public static function is(string $name):bool
    {
        return is_string($name) && extension_loaded($name);
    }


    // hasOpCache
    // retourne vrai si l'extension opcache est chargé
    final public static function hasOpCache(bool $ini=false):bool
    {
        $return = self::is('Zend OPcache');

        if($return === true && $ini === true)
        $return = Ini::opcache();

        return $return;
    }


    // hasXdebug
    // retourne vrai si l'extension xdebug est chargé
    final public static function hasXdebug(bool $ini=false):bool
    {
        $return = self::is('xdebug');

        if($return === true && $ini === true)
        $return = Ini::xdebug();

        return $return;
    }


    // hasApcu
    // retourne vrai si l'extension apcu est chargé
    final public static function hasApcu(bool $ini=false):bool
    {
        $return = self::is('apcu');

        if($return === true && $ini === true)
        $return = Ini::apcu();

        return $return;
    }


    // functions
    // retourne les fonctions d'une extension
    final public static function functions(string $name):array
    {
        return (self::is($name))? get_extension_funcs($name):[];
    }


    // important
    // retourn un tableau avec les résultats des méthodes pour détecter opcache, xdebug et apcu
    final public static function important(bool $ini=false):array
    {
        return [
            'opcache'=>self::hasOpCache($ini),
            'xdebug'=>self::hasXdebug($ini),
            'apcu'=>self::hasApcu($ini)
        ];
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
        return Arr::accumulate([],self::$config['required'],fn($value) => !self::is($value)? $value:null);
    }
}
?>