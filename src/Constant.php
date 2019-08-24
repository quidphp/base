<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// constant
class Constant extends Root
{
	// config
	public static $config = [];


	// is
	// retourne vrai si la constante est défini
	public static function is($name):bool
	{
		return (is_string($name) && defined($name))? true:false;
	}


	// get
	// retourne la valeur d'une constante définie
	public static function get(string $name)
	{
		$return = null;

		if(defined($name))
		$return = constant($name);

		return $return;
	}


	// set
	// crée une nouvelle constante si elle n'existe pas
	public static function set(string $name,$value,bool $sensitive=false):bool
	{
		$return = false;

		if(!empty($name) && !static::is($name))
		$return = define($name,$value,$sensitive);

		return $return;
	}


	// all
	// retourne toutes les constantes définis
	public static function all(?string $key=null,bool $categorize=true):array
	{
		$return = [];
		$constants = get_defined_constants($categorize);

		if(!empty($key))
		{
			if(array_key_exists($key,$constants))
			$return = $constants[$key];
		}

		else
		$return = $constants;

		return $return;
	}


	// user
	// retourne les constantes définis par l'utilisateur
	public static function user(bool $categorize=true):array
	{
		return static::all('user',$categorize);
	}
}
?>