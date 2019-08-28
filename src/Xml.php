<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// xml
// some static methods related to XML
class Xml extends Root
{
	// config
	public static $config = [
		'urlset'=>[ // défini les urlset utilisés par xml
			'sitemap'=>"<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>"]
	];


	// is
	// retourne vrai si la valeur est xml
	public static function is($value):bool
	{
		return (is_string($value) && Str::isStart('<?xml',$value))? true:false;
	}


	// urlset
	// retourne un urlset, tel que paramétré dans config
	// si inexistant retourne la string
	public static function urlset(string $value):?string
	{
		return Arr::get($value,static::$config['urlset']);
	}
}
?>