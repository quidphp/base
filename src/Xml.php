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

// xml
// class with some static methods related to XML
class Xml extends Root
{
    // config
    public static array $config = [
        'urlset'=>[ // défini les urlset utilisés par xml
            'sitemap'=>"<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>"]
    ];


    // is
    // retourne vrai si la valeur est xml
    final public static function is($value):bool
    {
        return is_string($value) && Str::isStart('<?xml',$value);
    }


    // urlset
    // retourne un urlset, tel que paramétré dans config
    // si inexistant retourne la string
    final public static function urlset(string $value):?string
    {
        return Arr::get($value,static::$config['urlset']);
    }
}
?>