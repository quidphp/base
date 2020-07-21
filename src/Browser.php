<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// browser
// class with methods a layer over the native PHP get_browser function
final class Browser extends Root
{
    // config
    protected static array $config = [
        'bots'=>[ // noms pouvant se retrouver dans le user-agent signifiant un bot
            'msnbot',
            'googlebot',
            'bingbot',
            'voilabot',
            'ezooms',
            'linkchecker',
            'hitbot',
            'mj12bot',
            'exabot',
            'slurp',
            'jeeves',
            'spider',
            'yandex',
            'feedburner',
            'monitoring',
            'garlikcrawler',
            'blexbot']
    ];


    // cacheStatic
    protected static array $cacheStatic = []; // conserve les données de la cache statique


    // isBot
    // retourne vrai si le user agent est un bot
    final public static function isBot($value):bool
    {
        return (is_string($value))? Arr::some(self::$config['bots'],fn($v) => stripos($value,$v) !== false):false;
    }


    // is
    // retourne vrai si le browser est reconnu par browscap
    final public static function is($value):bool
    {
        return is_string($value) && self::name($value) !== 'Default Browser';
    }


    // isDesktop
    // retourne vrai si le browser est desktop
    final public static function isDesktop($value):bool
    {
        return is_string($value) && self::device($value) === 'Desktop';
    }


    // isMobile
    // retourne vrai si le browser est mobile
    final public static function isMobile($value):bool
    {
        return (is_string($value))? !empty(self::cap($value)['ismobiledevice']):false;
    }


    // isOldIe
    // retourne vrai si le browser est Internet Explorer < 11
    final public static function isOldIe($value):bool
    {
        $return = false;

        if(is_string($value))
        {
            $cap = self::cap($value);
            $return = (!empty($cap['browser']) && $cap['browser'] === 'IE' && !empty($cap['version']) && (int) $cap['version'] < 11);
        }

        return $return;
    }


    // isMac
    // retourne vrai si le browser est sur MacOs
    final public static function isMac($value):bool
    {
        return is_string($value) && ($platform = self::platform($value)) && stripos($platform,'mac') !== false;
    }


    // isLinux
    // retourne vrai si le browser est sur Linux
    final public static function isLinux($value):bool
    {
        return is_string($value) && ($platform = self::platform($value)) && stripos($platform,'linux') !== false;
    }


    // isWindows
    // retourne vrai si le browser est sur Windows
    final public static function isWindows($value):bool
    {
        return is_string($value) && ($platform = self::platform($value)) && stripos($platform,'win') !== false;
    }


    // cap
    // retourne les informations sur le browser en fonction du user agent
    // les informations sont mis en cache selon le user agent, donc multiple appel n'est pas lourd
    // utilise la fonction php get_browser
    final public static function cap(string $value):?array
    {
        return (strlen($value))? self::cacheStatic([__METHOD__,$value],fn() => get_browser($value,true)):null;
    }


    // name
    // retourne le nom browser en fonction du user agent
    final public static function name(string $value):?string
    {
        return self::cap($value)['browser'] ?? null;
    }


    // platform
    // retourne la plateforme browser en fonction du user agent
    final public static function platform(string $value):?string
    {
        return self::cap($value)['platform'] ?? null;
    }


    // device
    // retourne le device du browser en fonction du user agent
    final public static function device(string $value):?string
    {
        return self::cap($value)['device_type'] ?? null;
    }
}
?>