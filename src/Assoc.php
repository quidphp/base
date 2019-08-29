<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// assoc
// class to deal with associative strings and arrays
class Assoc extends Root
{
	// trait
	use _option;


	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'trim'=>false, // chaque partie de assoc est trim
			'clean'=>false], // une partie assoc vide est retiré
		'sensitive'=>true // la classe est sensible ou non à la case
	];


	// arr
	// explose une string assoc
	// de même si assoc est déjà un array, retourne le après parse
	public static function arr($value,?array $option=null):array
	{
		$return = [];
		$option = static::option($option);
		$value = Obj::cast($value);

		if(is_scalar($value))
		$value = (array) $value;

		if(is_array($value) && !empty($value))
		$return = Arr::trimClean($value,$option['trim'],$option['trim'],$option['clean']);

		return $return;
	}


	// exist
	// retourne vrai si la clé existe dans le assoc
	public static function exist($key,$assoc,?array $option=null):bool
	{
		return Arrs::keyExists($key,static::arr($assoc,$option),static::getSensitive());
	}


	// exists
	// retourne vrai si les clés existent dans le assoc
	public static function exists(array $keys,$assoc,?array $option=null):bool
	{
		return Arrs::keysExists($keys,static::arr($assoc,$option),static::getSensitive());
	}


	// same
	// compare que les assoc ont toutes les clés du premier et ont le même count
	public static function same(...$values):bool
	{
		$return = false;

		foreach ($values as $key => $value)
		{
			$values[$key] = static::arr($value);
		}

		$return = Arr::same(...$values);

		return $return;
	}


	// isCount
	// retourne vrai si le count du assoc est égal à la valeur donné
	public static function isCount(int $count,$set,?array $option=null):bool
	{
		return Arr::isCount($count,static::arr($set,$option));
	}


	// isMinCount
	// retourne vrai si le count du assoc est plus grand ou égal que celui spécifié
	public static function isMinCount(int $count,$set,?array $option=null):bool
	{
		return Arr::isMinCount($count,static::arr($set,$option));
	}


	// isMaxCount
	// retourne vrai si le count du assoc est plus petit ou égal que celui spécifié
	public static function isMaxCount(int $count,$set,?array $option=null):bool
	{
		return Arr::isMaxCount($count,static::arr($set,$option));
	}


	// sameCount
	// compare que les assoc ont le même compte que le premier
	public static function sameCount(...$values):bool
	{
		$return = false;

		foreach ($values as $key => $value)
		{
			$values[$key] = static::arr($value);
		}

		$return = Arr::sameCount(...$values);

		return $return;
	}


	// sameKey
	// compare que les assoc ont toutes les clés du premier
	public static function sameKey(...$values):bool
	{
		$return = false;

		foreach ($values as $key => $value)
		{
			$values[$key] = static::arr($value);
		}

		$return = Arr::sameKey(...$values);

		return $return;
	}


	// getSensitive
	// retourne si la classe est sensible à la case
	public static function getSensitive():bool
	{
		return static::$config['sensitive'];
	}


	// prepend
	// ajoute des assocs un en arrière de l'autre
	// input string ou array
	public static function prepend(...$values):array
	{
		return static::append(...array_reverse($values));
	}


	// append
	// ajoute des assocs un après l'autre
	// input string ou array
	public static function append(...$values):array
	{
		$return = '';
		$array = [];

		foreach ($values as $k => $value)
		{
			$value = static::arr($value);

			if(is_array($value))
			$array = Arr::replace($array,$value);
		}

		$return = static::arr($array);

		return $return;
	}


	// count
	// count le nombre d'éléments dans le assoc
	public static function count($assoc,bool $recursive=false,?array $option=null):int
	{
		return count(static::arr($assoc,$option),($recursive === true)? COUNT_RECURSIVE:COUNT_NORMAL);
	}


	// index
	// retourne un index de l'explosion de assoc ou null si n'existe pas
	public static function index($index,$assoc,?array $option=null)
	{
		return Arrs::index($index,static::arr($assoc,$option));
	}


	// indexes
	// retourne plusieurs indexes de l'explosion de assoc
	public static function indexes(array $indexes,$assoc,?array $option=null):array
	{
		return Arrs::indexes($indexes,static::arr($assoc,$option));
	}


	// get
	// retourne une clé de l'explosion de assoc ou null si n'existe pas
	public static function get($key,$assoc,?array $option=null)
	{
		return Arrs::get($key,static::arr($assoc,$option),static::getSensitive());
	}


	// gets
	// retourne plusieurs clés de l'explosion de assoc
	public static function gets(array $keys,$assoc,?array $option=null):array
	{
		return Arrs::gets($keys,static::arr($assoc,$option),static::getSensitive());
	}


	// set
	// change une slice de assoc
	public static function set($key,$value,$assoc,?array $option=null):array
	{
		return static::arr(Arrs::set($key,$value,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// sets
	// change plusieurs slices de assoc
	public static function sets(array $values,$assoc,?array $option=null):array
	{
		return static::arr(Arrs::sets($values,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// unset
	// enlève une slice de assoc
	public static function unset($key,$assoc,?array $option=null):array
	{
		return static::arr(Arrs::unset($key,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// unsets
	// enlève plusieurs slices de assoc
	public static function unsets(array $keys,$assoc,?array $option=null):array
	{
		return static::arr(Arrs::unsets($keys,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// slice
	// tranche des slices d'un assoc en utilisant start et end
	public static function slice($start,$end,$assoc,?array $option=null):array
	{
		return Arr::slice($start,$end,static::arr($assoc,$option),static::getSensitive());
	}


	// sliceIndex
	// tranche des slices d'un assoc en utilisant offset et length
	public static function sliceIndex(int $offset,?int $length,$assoc,?array $option=null):array
	{
		return Arr::sliceIndex($offset,$length,static::arr($assoc,$option));
	}


	// splice
	// efface et remplace des slices d'un assoc en utilisant start et end
	public static function splice($start,$end,$assoc,$replace=null,?array $option=null):array
	{
		return static::arr(Arr::splice($start,$end,static::arr($assoc,$option),static::arr($replace,$option),static::getSensitive()),$option);
	}


	// spliceIndex
	// efface et remplace des slices d'un assoc en utilisant offset et length
	public static function spliceIndex(int $offset,?int $length,$assoc,$replace=null,?array $option=null):array
	{
		return static::arr(Arr::spliceIndex($offset,$length,static::arr($assoc,$option),static::arr($replace,$option),static::getSensitive()),$option);
	}


	// spliceFirst
	// efface et remplace la première slice d'un assoc
	public static function spliceFirst($assoc,$replace=null,?array $option=null):array
	{
		return static::arr(Arr::spliceFirst(static::arr($assoc,$option),static::arr($replace,$option),static::getSensitive()),$option);
	}


	// spliceLast
	// efface et remplace la dernière slice d'un assoc
	public static function spliceLast($assoc,$replace=null,?array $option=null):array
	{
		return static::arr(Arr::spliceLast(static::arr($assoc,$option),static::arr($replace,$option),static::getSensitive()),$option);
	}


	// insert
	// ajoute un élément dans le assoc sans ne rien effacer
	public static function insert($start,$replace,$assoc,?array $option=null):array
	{
		return static::arr(Arr::insert($start,static::arr($replace,$option),static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// insertIndex
	// ajoute un élément dans le assoc sans ne rien effacer via index
	public static function insertIndex(int $offset,$replace,$assoc,?array $option=null):array
	{
		return static::arr(Arr::insertIndex($offset,static::arr($replace,$option),static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// keysStart
	// retourne les slices des clés du assoc commençant par la chaîne
	public static function keysStart(string $str,$assoc,?array $option=null):array
	{
		return Arr::keysStart($str,static::arr($assoc,$option),static::getSensitive());
	}


	// keysEnd
	// retourne les slices des clés du assoc finissant par la chaîne
	public static function keysEnd(string $str,$assoc,?array $option=null):array
	{
		return Arr::keysEnd($str,static::arr($assoc,$option),static::getSensitive());
	}
}
?>