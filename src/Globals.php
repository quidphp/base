<?php
declare(strict_types=1);
namespace Quid\Base;

// globals
class Globals extends Root
{
	// config
	public static $config = [];
	
	
	// is
	// retourne vrai si la variable est une globale
	public static function is($key):bool 
	{
		return (Arr::isKey($key) && array_key_exists($key,$GLOBALS))? true:false;
	}
	
	
	// get
	// retourne la valeur d'une variable globale
	public static function get($key) 
	{
		$return = null;
		
		if(Arr::isKey($key) && array_key_exists($key,$GLOBALS))
		$return = $GLOBALS[$key];
		
		return $return;
	}
	
	
	// all
	// retourne toutes les variables globales
	// retourne une référence
	public static function &all():array
	{
		return $GLOBALS;
	}
	

	// set
	// change la valeur d'une variable globale
	public static function set($key,$value):bool
	{
		$return = false;
		
		if(Arr::isKey($key))
		{
			$GLOBALS[$key] = $value;
			$return = true;
		}
		
		return $return;
	}
	

	// unset
	// enlève une ou plusieurs variables globales
	public static function unset(...$keys):void 
	{
		Arr::unsetsRef($keys,$GLOBALS);
		
		return;
	}
}
?>