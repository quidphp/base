<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// call
class Call extends Root
{
	// config
	public static $config = [];


	// typecast
	// envoie à la méthode cast
	public static function typecast(&...$values):void
	{
		foreach ($values as &$value)
		{
			$value = static::cast($value);
		}

		return;
	}


	// cast
	// cast la variable dans une closure
	public static function cast($value):\Closure
	{
		return function() use($value) {
			return $value;
		};
	}


	// is
	// retourne vrai si la valeur est callable
	public static function is($value):bool
	{
		return (is_callable($value))? true:false;
	}


	// isSafeStaticMethod
	// retourne vrai seulement si la valeur est un tableau callable, très stricte
	public static function isSafeStaticMethod($value,bool $callable=false):bool
	{
		$return = false;

		if(is_array($value) && count($value) === 2 && array_key_exists(0,$value) && array_key_exists(1,$value) && is_string($value[1]))
		{
			if(is_object($value[0]) || (is_string($value[0]) && strpos($value[0],'\\') > 0))
			{
				if($callable === false || is_callable($value))
				$return = true;
			}
		}

		return $return;
	}


	// isCallable
	// retourne vrai si la valeur est une closure ou une callable dans un array qui est considéré comme safe (isSafe)
	public static function isCallable($value):bool
	{
		return ($value instanceof \Closure || static::isSafeStaticMethod($value,true))? true:false;
	}


	// isFunction
	// retourne vrai si la valeur est callable et function
	public static function isFunction($value):bool
	{
		return (is_string($value) && function_exists($value))? true:false;
	}


	// isClosure
	// retourne vrai si la valeur est callable et closure
	public static function isClosure($value):bool
	{
		return ($value instanceof \Closure)? true:false;
	}


	// isDynamicMethod
	// retourne vrai si la valeur est callable et dynamic method
	public static function isDynamicMethod($value):bool
	{
		return (static::type($value) === 'dynamicMethod')? true:false;
	}


	// isStaticMethod
	// retourne vrai si la valeur est callable et static method
	public static function isStaticMethod($value):bool
	{
		return (static::type($value) === 'staticMethod')? true:false;
	}


	// type
	// retourne le type de callable
	public static function type($value):?string
	{
		$return = null;

		if(static::is($value))
		{
			if(is_string($value) && function_exists($value))
			$return = 'function';

			elseif($value instanceof \Closure)
			$return = 'closure';

			elseif(is_array($value) && array_key_exists(0,$value) && array_key_exists(1,$value))
			{
				if(is_object($value[0]))
				$return = 'dynamicMethod';

				else
				$return = 'staticMethod';
			}
		}

		return $return;
	}


	// able
	// fonction static pour appeler un callable
	// argument 0 = callable, tous les autres = un tableau d'argument
	public static function able(callable $callable,...$arg)
	{
		return $callable(...$arg);
	}


	// ableArgs
	// fonction static pour créer un callable
	// callable en argument 0, et tableau arg en argument 1
	public static function ableArgs(callable $callable,array $args=[])
	{
		return $callable(...array_values($args));
	}


	// ableArray
	// appele un callable
	// tout est dans un tableau, clé 0 = callable, clé 1 = table d'argument argument
	public static function ableArray(array $array)
	{
		$return = null;

		if(count($array) === 2)
		{
			$callable = Arr::get(0,$array);

			if(static::is($callable))
			{
				$args = (array) Arr::get(1,$array);
				$return = $callable(...array_values($args));
			}
		}

		return $return;
	}


	// ableArrs
	// permet de loop un tableau et d'appeler tous les tableaux étant des callables
	public static function ableArrs(array $return):array
	{
		foreach ($return as $key => $value)
		{
			if(static::isCallable($value))
			$return[$key] = $value();
		}

		return $return;
	}


	// staticClass
	// fonction static pour appeler une méthode statique dans une classe
	public static function staticClass(string $class,string $method,...$arg)
	{
		return $class::$method(...$arg);
	}


	// staticClasses
	// permet de looper un tableau de classes et appelé la même méthode pour chaque
	// possible de fournir des arguments
	public static function staticClasses(array $classes,string $method,...$arg):array
	{
		$return = [];

		foreach ($classes as $class)
		{
			if(is_string($class))
			$return[$class] = static::staticClass($class,$method,...$arg);
		}

		return $return;
	}


	// back
	// envoie un tableau et une clé
	// retourne null ou le résultat du callable si existant
	public static function back($key,array $array,...$arg)
	{
		$return = null;

		if(Arr::isKey($key) && array_key_exists($key,$array) && static::is($array[$key]))
		$return = $array[$key](...$arg);

		return $return;
	}


	// backBool
	// envoie un tableau et une clé
	// fonction de callback qui va retourner true seulement si la fonction de rappel retourne true
	public static function backBool(string $key,array $array,...$arg):bool
	{
		$return = false;

		if(static::back($key,$array,...$arg) === true)
		$return = true;

		return $return;
	}


	// arr
	// envoie un tableau et une clé
	// s'il y a une callable, remplace la callable par le résultat de la fonction
	// le tableau est passé par référence
	public static function arr($key,array &$array,...$arg):void
	{
		if(Arr::isKey($key) && array_key_exists($key,$array) && static::is($array[$key]))
		$array[$key] = $array[$key](...$arg);

		return;
	}


	// bool
	// la callable est appelé pour chaque stack de values
	// si un retour de callable est vide, arrêt du loop et retourne false
	// cette fonction remplace les loop dans toutes les fonctions is (trop de code)
	public static function bool(callable $callable,...$values):bool
	{
		$return = false;

		if(!empty($values))
		{
			$return = true;

			foreach ($values as $value)
			{
				if(!$callable($value))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// map
	// lance la callable pour chaque valeur qui retourne vrai à la condition validate::is
	// args permet de spécifier des arguments supplémentaires à envoyer dans la callable
	// si la valeur est un tableau, passe chaque valeur dans la fonction et creuse le tableau si multidimensionnel
	// retourne la valeur
	public static function map($condition,callable $callable,$return,...$args)
	{
		if(is_array($return))
		{
			foreach ($return as $key => $value)
			{
				if(is_array($value))
				$return[$key] = static::map($condition,$callable,$value,...$args);

				elseif(Validate::is($condition,$value))
				$return[$key] = $callable($value,...$args);
			}
		}

		elseif(Validate::is($condition,$return))
		$return = $callable($return,...$args);

		return $return;
	}


	// withObj
	// permet de lancer une callable
	// si callable est closure, alors obj est le new this
	// sinon l'objet est mis comme dernier argument
	public static function withObj(object $obj,callable $callable,...$args)
	{
		$return = false;

		if($callable instanceof \Closure)
		$return = $callable->call($obj,...$args);

		else
		{
			$args[] = $obj;
			$return = $callable(...$args);
		}

		return $return;
	}


	// digStaticMethod
	// creuse dans un tableau et call toutes les méthodes statiques safe
	// les closures ne sont pas appelés
	public static function digStaticMethod($return,...$args)
	{
		if(is_array($return))
		{
			foreach ($return as $key => $value)
			{
				if(static::isSafeStaticMethod($value,true))
				$return[$key] = $value(...$args);

				elseif(is_array($value))
				$return[$key] = static::digStaticMethod($value,...$args);
			}
		}

		return $return;
	}
}
?>