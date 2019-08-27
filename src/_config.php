<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _config
trait _config
{
	// static
	protected static $initConfig = []; // tableau qui garde en mémoire les classes qui ont été init
	protected static $callableConfig = null; // garde une copie de la callable à utiliser, par défaut array_replace_recursive


	// __config
	// initialise la configuration de la classe ayant le trait
	// merge le tableau config de la classe et/ou de tous ses parents
	// possible de faire un merge recursif custom, selon la callable défini dans callableConfig
	// permet de merge une configuration des traits en dessous de la configuration courante de la classe
	// les config traits sont effacé pour éviter que d'autres classes utilisent les mêmes config pour le merge
	public static function __config(bool $force=false):void
	{
		$class = static::class;

		if($force === true || empty(static::$initConfig[$class]))
		{
			if(property_exists($class,'config') && is_array(static::$config))
			{
				$method = '__config';
				$init = false;
				$callable = static::getConfigCallable();

				$merge = [];
				$vars = get_class_vars($class);
				foreach ($vars as $key => $value)
				{
					if($key !== 'config' && is_array($value) && !empty($value) && strpos($key,'config') === 0)
					$merge[] = $value;
				}

				if(!empty($merge))
				{
					$merge[] = static::$config;
					static::$config = $callable($class,...$merge);
					$init = true;
				}

				$merge = [];

				$parent = get_parent_class($class);
				if(!empty($parent) && property_exists($parent,'config') && is_array($parent::$config) && !empty($parent::$config))
				$merge[] = $parent::$config;

				if(!empty($merge) || $init === false)
				{
					$merge[] = static::$config;
					static::$config = $callable($class,...$merge);
					static::$initConfig[$class] = true;
				}
			}
		}

		return;
	}


	// getConfigCallable
	// retourne la closure à utiliser pour le merge de config
	public static function getConfigCallable():\Closure
	{
		$return = static::$callableConfig;

		if(empty($return))
		{
			$return = function(string $class,...$values) {
				foreach ($values as &$value)
				{
					$value = (array) $value;
				}

				return array_replace_recursive(...$values);
			};
		}

		return $return;
	}


	// setConfigCallable
	// permet de changer la closure à utiliser pour le merge de config
	public static function setConfigCallable(?\Closure $value=null):void
	{
		static::$callableConfig = $value;

		return;
	}


	// configReplaceMode
	// retourne le tableau des clés à ne pas merger recursivement
	public static function configReplaceMode():array
	{
		return [];
	}


	// config
	// retourne le tableau de config
	// possibilité de faire un merge sur la valeur de retour
	// par défaut, cette méthode écrit dans la variable statique (à l'inverse de option)
	public static function config(?array $value=null,bool $write=true):?array
	{
		$return = null;
		$class = static::class;

		if(property_exists($class,'config') && is_array(static::$config))
		{
			$return = static::$config;

			if($value !== null)
			{
				$callable = static::getConfigCallable();
				$return = $callable($class,$return,$value);

				if($write === true)
				static::$config = $return;
			}
		}

		return $return;
	}
}
?>