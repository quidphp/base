<?php
declare(strict_types=1);
namespace Quid\Base;

// root
abstract class Root
{
	// trait
	use _root;
	
	
	// config
	public static $config = array();
	
	
	// static
	// remet une copie des propriétés des traits incluent car c'est plus facile à gérer
	protected static $initConfig = array(); // voir _config
	protected static $callableConfig = null; // voir _config
	protected static $cacheStatic = array(); // voir _cacheStatic
	protected static $cacheFile = null; // voir _cacheFile


	// construct
	// constructeur protégé
	protected function __construct() { }
}
?>