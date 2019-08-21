<?php
declare(strict_types=1);
namespace Quid\Base;

// xml
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
		return (\is_string($value) && Str::isStart("<?xml",$value))? true:false;
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