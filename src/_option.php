<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _option
// trait that grants static methods to deal with static options (within the $config static property)
trait _option
{
	// isOption
	// retourne vrai si l'option existe
	public static function isOption($key):bool
	{
		return Arrs::keyExists($key,static::$config['option']);
	}


	// getOption
	// retourne une option du tableau d'option
	public static function getOption($key)
	{
		return Arrs::get($key,static::$config['option']);
	}


	// setOption
	// ajoute ou change une option dans le tableau d'option
	public static function setOption($key,$value):void
	{
		Arrs::setRef($key,$value,static::$config['option']);

		return;
	}


	// unsetOption
	// enlève une option du tableau d'option
	public static function unsetOption($key):void
	{
		Arrs::unsetRef($key,static::$config['option']);

		return;
	}


	// option
	// retourne le tableau d'options
	// possibilité de faire un merge sur la valeur de retour, n'écrit pas dans la variable statique
	// par défaut, cette méthode n'écrit pas dans la variable statique (à l'inverse de config)
	public static function option(?array $value=null,bool $write=false):array
	{
		$return = static::$config['option'];

		if($value !== null)
		{
			if(array_keys($value) !== array_keys(static::$config['option']))
			$return = array_replace_recursive(static::$config['option'],$value);

			else
			$return = $value;

			if($write === true)
			static::$config['option'] = $return;
		}

		return $return;
	}
}
?>