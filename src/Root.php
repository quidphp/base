<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// root
// base class extended by all other Quid\Base classes
abstract class Root
{
	// trait
	use _root;


	// config
	public static $config = [];


	// static
	// remet une copie des propriétés des traits incluent car c'est plus facile à gérer
	protected static $initConfig = []; // voir _config
	protected static $callableConfig = null; // voir _config
	protected static $cacheStatic = []; // voir _cacheStatic
	protected static $cacheFile = null; // voir _cacheFile


	// construct
	// constructeur protégé
	protected function __construct() { }
}
?>