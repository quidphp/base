<?php 
declare(strict_types=1);
namespace Quid\Base;

// extension
class Extension extends Root
{
	// config
	public static $config = [
		'required'=>[
			'PDO','pdo_mysql','dom','fileinfo','curl','openssl','posix'] // modules requis
	];
	
	
	// is
	// retourne vrai si une extension est chargé 
	public static function is(string $name):bool 
	{
		return (\is_string($name) && \extension_loaded($name))? true:false;
	}
	
	
	// hasOpCache
	// retourne vrai si l'extension opcache est chargé
	public static function hasOpCache(bool $ini=false):bool 
	{
		$return = static::is('Zend OPcache');
		
		if($return === true && $ini === true)
		$return = Ini::opcache();
		
		return $return;
	}
	
	
	// hasXdebug
	// retourne vrai si l'extension xdebug est chargé
	public static function hasXdebug(bool $ini=false):bool 
	{
		$return = static::is('xdebug');
		
		if($return === true && $ini === true)
		$return = Ini::xdebug();
		
		return $return;
	}
	

	// hasApcu
	// retourne vrai si l'extension apcu est chargé
	public static function hasApcu(bool $ini=false):bool 
	{
		$return = static::is('apcu');
		
		if($return === true && $ini === true)
		$return = Ini::apcu();
		
		return $return;
	}
	
	
	// functions
	// retourne les fonctions d'une extension
	public static function functions(string $name):array
	{
		$return = [];
		
		if(static::is($name))
		$return = \get_extension_funcs($name);
		
		return $return;
	}
	
	
	// important
	// retourn un tableau avec les résultats des méthodes pour détecter opcache, xdebug et apcu
	public static function important(bool $ini=false):array 
	{
		$return = [];
		$return['opcache'] = static::hasOpCache($ini);
		$return['xdebug'] = static::hasXdebug($ini);
		$return['apcu'] = static::hasApcu($ini);
		
		return $return;
	}
	
	
	// all
	// retourne les extensions php
	public static function all():array
	{
		return \get_loaded_extensions();
	}
	
	
	// requirement
	// lance les tests de requirement
	public static function requirement():array
	{
		$return = [];
		
		foreach (static::$config['required'] as $value) 
		{
			if(!static::is($value))
			$return[] = $value;
		}
		
		return $return;
	}
}
?>