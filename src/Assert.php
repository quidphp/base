<?php
declare(strict_types=1);
namespace Quid\Base;

// assert
class Assert extends Root
{
	// config
	public static $config = [];
	
	
	// call
	// fait une assertion sur une callable
	public static function call(callable $call,$extra=null):bool
	{
		return assert($call(),$extra);
	}
	
	
	// get
	// retourne la valeur d'une option
	public static function get(int $key)
	{
		return assert_options($key);
	}
	
	
	// set
	// change la valeur d'une option d'assertion
	public static function set(int $key,$value):bool
	{
		$return = false;
		
		if(assert_options($key,$value) !== false)
		$return = true;
		
		return $return;
	}
	
	
	// setHandler
	// lie un handler lors des erreurs d'assertions
	public static function setHandler(callable $call):bool
	{
		return static::set(ASSERT_CALLBACK,$call);
	}
}
?>