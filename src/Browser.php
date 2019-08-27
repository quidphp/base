<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// browser
class Browser extends Root
{
	// config
	public static $config = [
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
	protected static $cacheStatic = []; // conserve les données de la cache statique


	// is
	// retourne vrai si le browser est reconnu par browscap
	public static function is($value):bool
	{
		return (is_string($value) && static::name($value) !== 'Default Browser')? true:false;
	}


	// isDesktop
	// retourne vrai si le browser est desktop
	public static function isDesktop($value):bool
	{
		return (is_string($value) && static::device($value) === 'Desktop')? true:false;
	}


	// isMobile
	// retourne vrai si le browser est mobile
	public static function isMobile($value):bool
	{
		$return = false;

		if(is_string($value))
		{
			$cap = static::cap($value);
			if(!empty($cap['ismobiledevice']))
			$return = true;
		}

		return $return;
	}


	// isOldIe
	// retourne vrai si le browser est Internet Explorer < 9
	public static function isOldIe($value):bool
	{
		$return = false;

		if(is_string($value))
		{
			$cap = static::cap($value);
			if(!empty($cap['browser']) && $cap['browser'] === 'IE' && !empty($cap['version']) && (int) $cap['version'] < 9)
			$return = true;
		}

		return $return;
	}


	// isMac
	// retourne vrai si le browser est sur MacOs
	public static function isMac($value):bool
	{
		return (is_string($value) && ($platform = static::platform($value)) && stripos($platform,'mac') !== false)? true:false;
	}


	// isLinux
	// retourne vrai si le browser est sur Linux
	public static function isLinux($value):bool
	{
		return (is_string($value) && ($platform = static::platform($value)) && stripos($platform,'linux') !== false)? true:false;
	}


	// isWindows
	// retourne vrai si le browser est sur Windows
	public static function isWindows($value):bool
	{
		return (is_string($value) && ($platform = static::platform($value)) && stripos($platform,'win') !== false)? true:false;
	}


	// isBot
	// retourne vrai si le user agent est un bot
	public static function isBot($value):bool
	{
		$return = false;

		if(is_string($value) && !empty(static::$config['bots']))
		{
			foreach(static::$config['bots'] as $v)
			{
				if(stripos($value,$v) !== false)
				{
					$return = true;
					break;
				}
			}
		}

		return $return;
	}


	// cap
	// retourne les informations sur le browser en fonction du user agent
	// les informations sont mis en cache selon le user agent, donc multiple appel n'est pas lourd
	// utilise la fonction php get_browser
	public static function cap(string $value):?array
	{
		return static::cacheStatic([__METHOD__,$value],function() use ($value) {
			return (strlen($value))? get_browser($value,true):null;
		});
	}


	// name
	// retourne le nom browser en fonction du user agent
	// utilise la fonction php get_browser
	public static function name(string $value):?string
	{
		$return = null;
		$cap = static::cap($value);

		if(!empty($cap['browser']))
		$return = $cap['browser'];

		return $return;
	}


	// platform
	// retourne la plateforme browser en fonction du user agent
	// utilise la fonction php get_browser
	public static function platform(string $value):?string
	{
		$return = null;
		$cap = static::cap($value);

		if(!empty($cap['platform']))
		$return = $cap['platform'];

		return $return;
	}


	// device
	// retourne le device du browser en fonction du user agent
	// utilise la fonction php get_browser
	public static function device(string $value):?string
	{
		$return = null;
		$cap = static::cap($value);

		if(!empty($cap['device_type']))
		$return = $cap['device_type'];

		return $return;
	}
}
?>