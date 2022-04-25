<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// xml
// class with some static methods related to XML
final class Xml extends Root
{
    // config
    protected static array $config = [
        'urlset'=>[ // défini les urlset utilisés par xml
            'sitemap'=>"<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>"]
    ];


    // is
    // retourne vrai si la valeur est xml
    final public static function is(mixed $value):bool
    {
        return is_string($value) && Str::isStart('<?xml',$value);
    }


    // urlset
    // retourne un urlset, tel que paramétré dans config
    // si inexistant retourne la string
    final public static function urlset(string $value):?string
    {
        return self::$config['urlset'][$value] ?? null;
    }
}
?>