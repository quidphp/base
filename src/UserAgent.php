<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// userAgent
// class with methods related to useragent
final class UserAgent extends Root
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


    // isBot
    // retourne vrai si le user agent est un bot
    final public static function isBot($value):bool
    {
        return (is_string($value))? Arr::some(self::$config['bots'],fn($v) => stripos($value,$v) !== false):false;
    }
}
?>