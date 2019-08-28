<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _shortcut
// static methods to declare and replace shortcuts (bracketed segments within strings)
trait _shortcut
{
	// config
	protected static $shortcut = []; // conserve les shortcuts de la classe


	// isShortcut
	// retourne vrai si le shortcut existe
	public static function isShortcut(string $key):bool
	{
		return (array_key_exists($key,static::$shortcut))? true:false;
	}


	// getShortcut
	// retourne la valeur du shortcut ou null si non existant
	public static function getShortcut(string $key):?string
	{
		return Arr::get($key,static::$shortcut);
	}


	// setShortcut
	// ajoute ou change un shortcut
	// le shortcut est passé dans la méthode shortcut avant d'être conservé dans config
	public static function setShortcut(string $key,string $value):void
	{
		Arr::setRef($key,static::shortcut($value),static::$shortcut);

		return;
	}


	// setsShortcut
	// ajoute ou change plusieurs shortcuts
	public static function setsShortcut(array $keyValue):void
	{
		foreach ($keyValue as $key => $value)
		{
			if(is_string($key) && is_string($value))
			static::setShortcut($key,$value);
		}

		return;
	}


	// unsetShortcut
	// enlève un shortcut
	public static function unsetShortcut(string $key):void
	{
		Arr::unsetRef($key,static::$shortcut);

		return;
	}


	// shortcut
	// remplace des segments dans une string ou un tableau à partir des shortcuts
	public static function shortcut($return)
	{
		if(!empty(static::$shortcut))
		{
			if(is_string($return))
			$return = Segment::sets(null,static::$shortcut,$return);

			elseif(is_array($return))
			$return = Segment::setsArray(null,static::$shortcut,$return);
		}

		return $return;
	}


	// shortcuts
	// permet de remplacer plusieurs valeurs contenants un shortcut
	public static function shortcuts(array $return):array
	{
		foreach ($return as $key => $value)
		{
			$return[$key] = static::shortcut($value);
		}

		return $return;
	}


	// allShortcuts
	// retourne tous les shortcuts
	public static function allShortcuts():array
	{
		return static::$shortcut;
	}
}
?>