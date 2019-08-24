<?php
declare(strict_types=1);
namespace Quid\Base;

// arr
class Arr extends Root
{	
	// config
	public static $config = [];
	
	
	// typecast
	// typecasts des valeurs par référence
	public static function typecast(&...$values):void
	{
		foreach ($values as &$value) 
		{
			if($value === null)
			$value = (array) $value;
			
			elseif(!is_array($value))
			$value = [$value];
		}
		
		return;
	}
	
	
	// cast
	// permet de ramener les valeurs contenus dans un tableau dans leur cast naturel
	// par défaut, seul les nombres sont convertis
	public static function cast($return,int $numberCast=1,int $boolCast=0):array
	{
		$return = (array) $return;
		foreach ($return as $key => $value) 
		{
			if(is_scalar($value))
			$return[$key] = Scalar::cast($value,$numberCast,$boolCast);
		}

		return $return;
	}
	
	
	// castMore
	// envoie à scalar cast avec paramètre 2,1
	// nombre sont convertis, virgule remplacer par décimal, et les string booleans sont transformés en bool
	public static function castMore($return):array
	{
		$return = (array) $return;
		foreach ($return as $key => $value) 
		{
			if(is_scalar($value))
			$return[$key] = Scalar::castMore($value);
		}

		return $return;
	}
	
	
	// is
	// retourne vrai si la valeur est array
	public static function is($value):bool
	{
		return (is_array($value))? true:false;
	}
	
	
	// isEmpty
	// retourne vrai si la valeur est array et vide
	public static function isEmpty($value):bool
	{
		return (is_array($value) && empty($value))? true:false;
	}
	
	
	// isNotEmpty
	// retourne vrai si la valeur est array et non vide
	public static function isNotEmpty($value):bool
	{
		return (is_array($value) && !empty($value))? true:false;
	}
	
	
	// isCleanEmpty
	// retourne vrai si le tableau est vide après avoir utiliser la methode clean
	public static function isCleanEmpty($value):bool
	{
		$return = false;
		
		if(is_array($value))
		{
			$value = static::clean($value);
			if(empty($value))
			$return = true;
		}
		
		return $return;
	}
	
	
	// hasNumericKey
	// retourne vrai si le tableau contient au moins une clé numérique
	// retourne faux si le tableau est vide
	public static function hasNumericKey($value):bool
	{
		$return = false;
		
		if(is_array($value))
		{
			foreach ($value as $key => $value) 
			{
				if(is_numeric($key))
				{
					$return = true;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// hasNonNumericKey
	// retourne vrai si le tableau contient au moins une clé non numérique
	// retourne faux si le tableau est vide
	public static function hasNonNumericKey($value):bool
	{
		$return = false;
		
		if(is_array($value))
		{
			foreach ($value as $key => $value) 
			{
				if(!is_numeric($key))
				{
					$return = true;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// hasKeyCaseConflict
	// retourne vrai si le tableau contient au moins une clé en conflit de case si le tableau est insensible à la case
	public static function hasKeyCaseConflict($value):bool 
	{
		return (is_array($value) && count($value) !== count(static::keysInsensitive($value)))? true:false;
	}
	
	
	// isIndexed
	// retourne vrai si le tableau est vide ou contient seulement des clés numériques
	public static function isIndexed($value):bool
	{
		$return = false;
		
		if(is_array($value))
		{
			$return = true;
			
			foreach ($value as $key => $value) 
			{
				if(!is_numeric($key))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// isSequential
	// retourne vrai si le tableau est vide ou séquentielle
	public static function isSequential($value):bool
	{
		return (is_array($value) && (empty($value) || array_keys($value) === range(0, (count($value) - 1))))? true:false;
	}
	
	
	// isAssoc
	// retourne vrai si le tableau est vide ou associatif
	// doit contenir au moins une clé non numérique
	public static function isAssoc($value):bool 
	{
		return (is_array($value) && (empty($value) || !static::isIndexed($value)))? true:false;
	}

	
	// isUni
	// retourne vrai si le tableau est vide ou unidimensionnel
	public static function isUni($value):bool
	{
		$return = false;
		
		if(is_array($value))
		{
			$return = true;
			
			foreach ($value as $v) 
			{
				if(is_array($v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// isMulti
	// retourne vrai si le tableau est multidimensionel, retourne faux si vide
	public static function isMulti($value):bool
	{
		return Arrs::is($value);
	}
	
	
	// onlyNumeric
	// retourne vrai si le tableau est vide ou a seulement des clés et valeurs numérique
	public static function onlyNumeric($value):bool 
	{
		$return = false;
		
		if(is_array($value))
		{
			$return = true;
			
			foreach ($value as $k => $v) 
			{
				if(!is_numeric($k) || !is_numeric($v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// onlyString
	// retourne vrai si le tableau est vide ou a seulement des clés non numériques et valeurs string
	public static function onlyString($value):bool 
	{
		$return = false;
		
		if(is_array($value))
		{
			$return = true;
			
			foreach ($value as $k => $v) 
			{
				if(!is_string($k) || !is_string($v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// isSet
	// retourne vrai si le tableau est vide ou contient seulement des clés numériques et valeurs scalar
	public static function isSet($value) 
	{
		$return = false;
		
		if(is_array($value))
		{
			$return = true;
			
			foreach ($value as $k => $v) 
			{
				if(!is_numeric($k) || !is_scalar($v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// isKey
	// retourne vrai si la valeur est une clé
	public static function isKey($value):bool
	{
		return (is_scalar($value) && !is_bool($value))? true:false;
	}
	
	
	// isKeyNotEmpty
	// retourne vrai si la valeur est une clé
	// la clé doit avoir une longueur
	public static function isKeyNotEmpty($value):bool
	{
		return (is_scalar($value) && !is_bool($value) && strlen((string) $value))? true:false;
	}
	
	
	// isCount
	// retourne vrai si le count est celui spécifié
	// possible de donner un tableau comme count
	public static function isCount($count,$value):bool
	{
		$count = (is_array($count))? count($count):$count;
		return (is_int($count) && is_array($value) && count($value) === $count)? true:false;
	}
	
	
	// isMinCount
	// retourne vrai si le count est plus grand ou égal que celui spécifié
	// possible de donner un tableau comme count
	public static function isMinCount($count,$value):bool
	{
		$count = (is_array($count))? count($count):$count;
		return (is_int($count) && is_array($value) && count($value) >= $count)? true:false;
	}
	
	
	// isMaxCount
	// retourne vrai si le count est plus petit ou égal que celui spécifié
	// possible de donner un tableau comme count
	public static function isMaxCount($count,$value):bool
	{
		$count = (is_array($count))? count($count):$count;
		return (is_int($count) && is_array($value) && count($value) <= $count)? true:false;
	}
	
	
	// same
	// compare que les tableaux ont toutes les clés du premier et ont le même count
	public static function same(...$values):bool
	{
		$return = false;
		
		if(count($values) > 1 && is_array($values[0]))
		{
			$return = true;
			$keys = array_keys($values[0]);
			unset($values[0]);
			
			foreach ($values as $v) 
			{
				if(!is_array($v) || !static::keysAre($keys,$v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// sameCount
	// compare que les tableaux ont le même compte que le premier
	public static function sameCount(...$values):bool
	{
		$return = false;
		
		if(count($values) > 1 && is_array($values[0]))
		{
			$return = true;
			$count = count($values[0]);
			unset($values[0]);
			
			foreach ($values as $v) 
			{
				if(!is_array($v) || count($v) !== $count)
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// sameKey
	// compare que les tableaux ont toutes les clés du premier
	public static function sameKey(...$values):bool
	{
		$return = false;
		
		if(count($values) > 1 && is_array($values[0]))
		{
			$return = true;
			$keys = array_keys($values[0]);
			unset($values[0]);
			
			foreach ($values as $v) 
			{
				if(!is_array($v) || !static::keysExists($keys,$v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// sameKeyValue
	// compare que les tableaux ont toutes les clés et valeurs du premier, pas nécessairement dans le même ordre
	public static function sameKeyValue(...$values):bool
	{
		$return = false;
		
		if(count($values) > 1 && is_array($values[0]))
		{
			$array = Arr::keysSort($values[0]);
			unset($values[0]);
			
			foreach ($values as $value) 
			{
				$return = (is_array($value) && !empty($value) && Arr::keysSort($value) === $array)? true:false;
				
				if($return === false)
				break;
			}
		}
		
		return $return;
	}
	
	
	// hasValueStart
	// retourne vrai si une des valeurs du tableaux est le début de la valeur donné en premier argument
	public static function hasValueStart(string $value,array $array,bool $sensitive=true):bool 
	{
		$return = false;
		
		foreach ($array as $v) 
		{
			if(is_string($v) && Str::isStart($v,$value,$sensitive))
			{
				$return = true;
				break;
			}
		}
		
		return $return;
	}
	
	
	// plus
	// combine plusieurs array ensemble + rapide
	// fonctionne si une valeur n'est pas un tableau
	public static function plus(...$values):array
	{
		if(count($values) === 2 && is_array($values[0]) && ($values[1] === null || $values[0] === $values[1]))
		return $values[0];
		else
		{
			$return = [];
			static::typecast(...$values);
			
			foreach ($values as $v) 
			{
				if(!is_array($v) && $v !== n)
				$value = [$value];
				
				$return = (array) $v + $return;
			}

			return $return;
		}
	}
	
	
	// merge
	// wrapper pour array_merge
	// fonctionne si une valeur n'est pas un tableau
	public static function merge(...$values):array 
	{
		static::typecast(...$values);
		return array_merge(...$values);
	}
	

	// replace
	// wrapper pour array_replace
	// fonctionne si une valeur n'est pas un tableau
	public static function replace(...$values):array 
	{
		if(count($values) === 2 && is_array($values[0]) && ($values[1] === null || $values[0] === $values[1]))
		return $values[0];
		else
		{
			static::typecast(...$values);
			return array_replace(...$values);
		}
	}
	
	
	// replaceIf
	// méthode qui regroupe 4 méthodes de remplacements spéciales
	// exists remplacement seulement si la valeur existe dans le premier tableau
	// notExists remplacement seulement si la valeur n'existe pas dans le premier tableau
	// bigger remplacement seulement si la nouvelle valeur scalar ou array est plus grande que celle dans le premier tableau
	// smaller remplacement seulement si la nouvelle valeur scalar ou array est plus petite que celle dans le premier tableau
	// cette méthode n'est pas récursive
	public static function replaceIf(string $type,...$values):array 
	{		
		if(in_array($type,['exists','notExists','bigger','smaller'],true))
		{
			static::typecast(...$values);
			$return = $values[0];
			unset($values[0]);
			
			foreach ($values as $v) 
			{
				foreach ($v as $key => $value) 
				{
					if(array_key_exists($key,$return))
					{
						if($type === 'exists')
						$return[$key] = $value;
						
						elseif($type === 'bigger')
						{
							if(is_scalar($return[$key]) && is_scalar($value) && $value > $return[$key])
							$return[$key] = $value;
							
							elseif(is_array($return[$key]) && is_array($value) && count($value) > count($return[$key]))
							$return[$key] = $value;
						}
						
						elseif($type === 'smaller')
						{
							if(is_scalar($return[$key]) && is_scalar($value) && $value < $return[$key])
							$return[$key] = $value;
							
							elseif(is_array($return[$key]) && is_array($value) && count($value) < count($return[$key]))
							$return[$key] = $value;
						}
					}
					
					elseif($type === 'notExists' || $type === 'bigger')
					$return[$key] = $value;
				}
			}
		}
		
		return $return;
	}
	
	
	// replaceCleanNull
	// comme array_replace
	// efface toutes les clés du tableau de retour dont la valeur est null
	// fonctionne si une valeur n'est pas un tableau
	public static function replaceCleanNull(...$values):array 
	{
		return static::cleanNull(static::replace(...$values));
	}
	
	
	// unshift
	// permet de unshift plusieurs valeurs au début d'un tableau, l'ordre des values est respecté 
	// la première valeur n'a pas à être un tableau
	// aussi n'est pas passé par référence
	public static function unshift($return,...$values) 
	{
		static::typecast($return);
		
		foreach (array_reverse($values) as $v) 
		{	
			array_unshift($return,$v);
		}
		
		return $return;
	}
	
	
	// push
	// permet de push plusieurs valeurs à la fin du tableau return, l'ordre des values est respecté
	// la première valeur n'a pas à être un tableau
	// aussi n'est pas passé par référence
	public static function push($return,...$values):array 
	{
		static::typecast($return);
		
		foreach ($values as $v) 
		{	
			array_push($return,$v);
		}
		
		return $return;
	}
	
	
	// prepend
	// prepend les values au début du tableau return, l'ordre des values est respecté
	// si une valeur secondaire est un array, les clés -> valeurs sont ajoutés au premier niveau du tableau de retour
	// les clés numériques existantes sont conservés, les clés string sont remplacés
	public static function prepend($return,...$values) 
	{
		$values[] = $return;
		return static::append(...$values);
	}
	
	
	// iprepend
	// comme prepend mais les clés sont insensibles à la case
	public static function iprepend($return,...$values) 
	{
		$values[] = $return;
		return static::iappend(...$values);
	}
	

	// append
	// similaire à array_push 
	// si une valeur secondaire est un array, les clés -> valeurs sont ajoutés au premier niveau du tableau de retour
	// les clés numériques existantes sont conservés, les clés string sont remplacés
	public static function append($return,...$values):array 
	{
		static::typecast($return);
		static::typecast(...$values);
		
		foreach ($values as $k => $value) 
		{	
			foreach ($value as $k => $v) 
			{
				if(is_numeric($k) && array_key_exists($k,$return))
				$return[] = $v;
				
				else
				$return[$k] = $v;
			}
		}
		
		return $return;
	}
	
	
	// iappend
	// comme append mais les clés sont insensibles à la case
	public static function iappend($return,...$values):array 
	{
		static::typecast($return);
		static::typecast(...$values);
		
		foreach ($values as $k => $value) 
		{	
			foreach ($value as $k => $v) 
			{
				if(is_numeric($k) && array_key_exists($k,$return))
				$return[] = $v;
				
				else
				{
					if(is_string($k))
					$return = static::keyStrip($k,$return,false);
					
					$return[$k] = $v;
				}
			}
		}
		
		return $return;
	}
	
	
	// appendUnique
	// append des valeurs si non existantes dans le tableau
	// les clés numériques existantes sont conservés, les clés string sont remplacés
	public static function appendUnique($return,...$values):array
	{
		static::typecast($return);
		static::typecast(...$values);
		
		foreach ($values as $value) 
		{
			foreach ($value as $k => $v) 
			{
				if(!static::in($v,$return,true,true))
				{
					if(is_numeric($k) && array_key_exists($k,$return))
					$return[] = $v;
					
					else
					$return[$k] = $v;
				}
			}
		}
		
		return $return;
	}
	
	
	// appendiUnique
	// pousse des valeurs si non existantes dans le tableau de façon insensible à la case
	// les clés numériques existantes sont conservés, les clés string sont remplacés
	public static function appendiUnique($return,...$values):array
	{
		static::typecast($return);
		static::typecast(...$values);
		
		foreach ($values as $value) 
		{
			foreach ($value as $k => $v) 
			{
				if(!static::in($v,$return,false,true))
				{
					if(is_numeric($k) && array_key_exists($k,$return))
					$return[] = $v;
					
					else
					$return[$k] = $v;
				}
			}
		}
		
		return $return;
	}
	
	
	// smart
	// si la valeur est un tableau et contient un seul element retourne l'élément, sinon retourne le tableau
	public static function smart(array $return):array
	{
		if(count($return) === 1)
		$return = current($return);
		
		return $return;
	}
	
	
	// clean
	// enlève des éléments du tableau vraiment vide
	// si reset est true, reset les clés du tableau
	public static function clean(array $return,bool $reset=false):array
	{
		foreach ($return as $k => $v) 
		{
			if(Validate::isReallyEmpty($v))
			unset($return[$k]);
		}
		
		if($reset === true)
		$return = array_values($return);
		
		return $return;
	}
	
	
	// cleanEmpty
	// enlève des éléments du tableau vide
	// si reset est true, reset les clés du tableau
	public static function cleanEmpty(array $return,bool $reset=false):array
	{
		foreach ($return as $k => $v) 
		{
			if(Validate::isEmpty($v))
			unset($return[$k]);
		}
		
		if($reset === true)
		$return = array_values($return);
		
		return $return;
	}
	
	
	// cleanNull
	// enlève les clés dont la valeur est null
	// si reset est true, reset les clés du tableau
	public static function cleanNull(array $return,bool $reset=false):array
	{
		foreach ($return as $k => $v) 
		{
			if($v === null)
			unset($return[$k]);
		}
		
		if($reset === true)
		$return = array_values($return);
		
		return $return;
	}
	
	
	// cleanNullBool
	// enlève les clés dont la valeur est null ou bool
	// si reset est true, reset les clés du tableau
	public static function cleanNullBool(array $return,bool $reset=false):array
	{
		foreach ($return as $k => $v) 
		{
			if($v === null || is_bool($v))
			unset($return[$k]);
		}
		
		if($reset === true)
		$return = array_values($return);
		
		return $return;
	}
	
	
	// reallyEmptyToNull
	// change les éléments vides du tableau pour null
	public static function reallyEmptyToNull(array $return):array
	{
		foreach ($return as $k => $v) 
		{
			if(Validate::isReallyEmpty($v))
			$return[$k] = null;
		}
		
		return $return;
	}
	
	
	// trim
	// fait un trim sur les clés et/ou valeurs string du tableau
	public static function trim(array $array,bool $key=false,bool $value=true):array 
	{
		$return = [];
		
		foreach ($array as $k => $v) 
		{
			if($key === true && is_string($k))
			$k = trim($k);
			
			if($value === true && is_string($v))
			$v = trim($v);
			
			$return[$k] = $v;
		}
		
		return $return;
	}
	
	
	// trimClean
	// fait trim et clean sur le tableau
	// trimKey permet de faire un trim sur les clés aussi
	public static function trimClean(array $return,?bool $trimKey=false,?bool $trim=true,?bool $clean=true,?bool $reset=false):array
	{
		if($clean === true)
		$return = static::clean($return,$reset ?? false);
		
		if(is_bool($trim))
		$return = static::trim($return,$trimKey ?? false,$trim);
		
		return $return;
	}
	
	
	// validate
	// envoie chaque valeur du tableau dans validate::is
	public static function validate($condition,array $value):bool 
	{
		return Validate::are($condition,...array_values($value));
	}
	
	
	// validates
	// envoie plusieurs tableaux dans validate::is
	public static function validates($condition,array ...$values):bool 
	{
		$return = false;
		
		foreach ($values as $value) 
		{
			$return = Validate::are($condition,...array_values($value));
			
			if($return === false)
			break;
		}
		
		return $return;
	}
	
	
	// validateSlice
	// garde les slices dont les valeurs répondent vrai à validate::is
	public static function validateSlice($condition,array $return):array
	{
		foreach ($return as $key => $value) 
		{
			if(!Validate::is($condition,$value))
			unset($return[$key]);
		}
		
		return $return;
	}
	

	// validateStrip
	// enlève les slices dont les valeurs répondent vrai à validate::is
	public static function validateStrip($condition,array $return):array 
	{
		foreach ($return as $key => $value) 
		{
			if(Validate::is($condition,$value))
			unset($return[$key]);
		}
		
		return $return;
	}
	
	
	// validateMap
	// comme map, mais la fonction de rappel est seulement utilisé lorsque la valeur passe la condition de validation
	// ne permet pas l'utilisation de multiples tableaux
	public static function validateMap($condition,callable $callable,array $return,...$args):array 
	{
		foreach ($return as $key => $value) 
		{
			if(Validate::is($condition,$value))
			$return[$key] = $callable($value,...$args);
		}
		
		return $return;
	}
	
	
	// validateFilter
	// comme filter, mais la fonction de rappel est seulement utilisé lorsque la valeur passe la condition de validation 
	// si callable est closure, à ce moment trois arguments sont envoyés à la fonction = value, key et array
	// keep permet de spécifier si les valeurs qui n'ont pas passé la validation sont conservés
	public static function validateFilter($condition,callable $callable,array $array,bool $keep=true):array 
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(Validate::is($condition,$value))
			{
				if($callable instanceof \Closure)
				{
					if($callable($value,$key,$return))
					$return[$key] = $value;
				}
				
				elseif($callable($value))
				$return[$key] = $value;
			}
			
			elseif($keep === true)
			$return[$key] = $value;
		}
		
		return $return;
	}
	

	// get
	// retourne une valeur d'un tableau
	// support pour clé insensitive
	public static function get($key,array $array,bool $sensitive=true) 
	{
		$return = null;
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$array = static::keysLower($array,true);
				$key = (is_string($key))? Str::lower($key,true):$key;
			}
			
			if(array_key_exists($key,$array))
			$return = $array[$key];
		}
		
		return $return;
	}
	
	
	// getSafe
	// retourne une valeur du tableau si key est scalar et array est array
	// sinon, array est retourné s'il est bien un tableau
	// sinon retoure null
	public static function getSafe($key,$array,$sensitive=true) 
	{
		$return = null;
		
		if(is_array($array))
		{
			if(is_scalar($key))
			$return = static::get($key,$array,$sensitive);
			
			else
			$return = $array;
		}
		
		return $return;
	}
	
	
	// gets
	// retourne plusieurs valeurs d'un tableau
	// par défaut les valeurs des clés non existentes sont retournés comme null
	// support pour clé insensitive
	public static function gets(array $keys,array $array,bool $sensitive=true,bool $exists=false):array
	{
		$return = [];
				
		if($sensitive === false)
		$array = static::keysLower($array,true);
		
		foreach ($keys as $key) 
		{
			if(static::isKey($key))
			{
				$target = (is_string($key) && $sensitive === false)? Str::lower($key,true):$key;
				
				if(array_key_exists($target,$array))
				$return[$key] = $array[$target];
				
				elseif($exists === false)
				$return[$key] = null;
			}
		}
		
		return $return;
	}
	
	
	// getsExists
	// comme gets mais la différence est que les clés non existentes ne sont pas retournés avec une valeur null
	public static function getsExists(array $keys,array $array,bool $sensitive=true):array
	{
		return static::gets($keys,$array,$sensitive,true);
	}
	
	
	// indexPrepare
	// retourne la valeur positive d'un index négatif
	// count peut être numérique ou un tableau
	public static function indexPrepare($index,$count)
	{
		$return = (is_scalar($index))? (int) $index:null;

		if(is_array($count))
		$count = count($count);
		
		if(is_int($count))
		{
			if(is_int($index))
			{
				$return = $index;
				
				if($index < 0 && ($count + $index) >= 0)
				$return = $count + $index;
			}
			
			elseif(is_array($index))
			{
				foreach ($index as $i) 
				{
					if($i < 0 && ($count + $i) >= 0)
					$return[] = $count + $i;
					
					else
					$return[] = $i;
				}
			}
		}
		
		return $return;
	}
	
	
	// index
	// retourne une valeur à partir d'un index de tableau
	public static function index(int $index,array $array) 
	{
		$return = null;
		$array = array_values($array);
		
		if($index < 1)
		$index = static::indexPrepare($index,count($array));
		
		if(array_key_exists($index,$array))
		$return = $array[$index];
		
		return $return;
	}
	
	
	// indexes
	// retourne des valeurs à partir d'index de tableau
	public static function indexes(array $indexes,array $array):array
	{
		$return = [];
		$array = array_values($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(is_int($index))
			{
				if(array_key_exists($index,$array))
				$return[$index] = $array[$index];
				
				else
				$return[$index] = null;
			}
		}
		
		return $return;
	}
	
	
	// set
	// change la valeur d'un tableau
	// support pour clé insensitive
	// si key est null, append []
	// retourne le tableau
	public static function set($key,$value,array $return,bool $sensitive=true):array
	{
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$ikey = Arr::ikey($key,$return);
				if(!empty($ikey))
				$key = $ikey;
			}
			
			$return[$key] = $value;
		}
		
		elseif($key === null)
		$return[] = $value;
		
		return $return;
	}
	
	
	// sets
	// change plusieurs valeurs d'un tableau
	// support pour clés insensitives
	// retourne le tableau
	public static function sets(array $keyValue,array $return,bool $sensitive=true):array
	{
		if(!empty($keyValue))
		{
			if($sensitive === false)
			$return = static::keysStrip(array_keys($keyValue),$return,$sensitive);
			
			foreach ($keyValue as $key => $value) 
			{
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// setRef
	// change une valeur d'un tableau passé par référence
	// possibilité d'une opération insensible à la case
	// si key est null, append []
	public static function setRef($key,$value,array &$array,bool $sensitive=true):void
	{
		$array = static::set($key,$value,$array,$sensitive);
		
		return;
	}
	
	
	// setsRef
	// change plusieurs valeurs d'un tableau passé par référence
	// possibilité d'une opération insensible à la case
	public static function setsRef(array $keyValue,array &$array,bool $sensitive=true):void
	{
		$array = static::sets($keyValue,$array,$sensitive);
		
		return;
	}
	
	
	// setMerge
	// change la valeur d'un tableau ou merge la valeur dans un tableau si déjà existante
	// support pour clé insensitive
	// si key est null, append []
	// retourne le tableau
	public static function setMerge($key,$value,array $return,bool $sensitive=true):array
	{
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$ikey = static::ikey($key,$return);
				if(!empty($ikey))
				$key = $ikey;
			}
			
			if(array_key_exists($key,$return))
			$return[$key] = static::append($return[$key],$value);
			else
			$return[$key] = $value;
		}
		
		elseif($key === null)
		$return[] = $value;
		
		return $return;
	}
	
	
	// setsMerge
	// change plusieurs valeurs d'un tableau ou merge les valeurs dans un tableau si déjà existante
	// support pour clé insensitive
	// retourne le tableau
	public static function setsMerge(array $values,array $return,bool $sensitive=true):array 
	{
		foreach ($values as $key => $value) 
		{
			$return = static::setMerge($key,$value,$return,$sensitive);
		}
		
		return $return;
	}
	
	
	// unset
	// enlève une slice d'un tableau
	// support pour clé insensitive
	// retourne le tableau
	public static function unset($key,array $return,bool $sensitive=true):array
	{
		return static::keyStrip($key,$return,$sensitive);
	}
	
	
	// unsets
	// efface plusieurs slices dans un tableau
	// support pour clés insensitives
	// retourne le tableau
	public static function unsets(array $keys,array $return,bool $sensitive=true):array
	{
		return static::keysStrip($keys,$return,$sensitive);
	}
	
	
	// unsetRef
	// enlève une slice d'un tableau passé par référence
	// possibilité d'une opération insensible à la case
	public static function unsetRef($key,array &$array,bool $sensitive=true):void 
	{
		$array = static::unset($key,$array,$sensitive);
		
		return;
	}
	
	
	// unsetsRef
	// enlève plusieurs slices d'un tableau passé par référence
	// possibilité d'une opération insensible à la case
	public static function unsetsRef(array $keys,array &$array,bool $sensitive=true):void
	{
		$array = static::unsets($keys,$array,$sensitive);
		
		return;
	}
	
	
	// getSet
	// permet de faire des modifications get/set sur un tableau unidimensionnel
	// le tableau est passé par référence
	// pas de support pour clé insensible à la case
	public static function getSet($get=null,$set=null,array &$source)
	{
		$return = null;
		
		// get tout
		if($get === null && $set === null)
		$return = $source;

		// get un
		elseif(static::isKey($get) && $set === null)
		$return = static::get($get,$source);

		// tableau, écrase ou merge
		elseif(is_array($get))
		{
			if($set === true)
			$source = $get;
			else
			$source = static::replace($source,$get);
			
			$return = true;
		}

		// set un
		elseif(static::isKey($get) && $set !== null)
		{
			$source = static::set($get,$set,$source);
			$return = true;
		}
		
		return $return;
	}
	
	
	// keyValue
	// retourne un tableau clé valeur à partir d'une clé pour clé et une clé pour valeur
	public static function keyValue($key,$value,array $array):array 
	{
		$return = [];
		
		if(static::isKey($key) && static::isKey($value))
		{
			if(array_key_exists($key,$array) && static::isKey($array[$key]) && array_key_exists($value,$array))
			$return[$array[$key]] = $array[$value]; 
		}
		
		return $return;
	}
	
	
	// keyValueIndex 
	// retourne un tableau clé valeur à partir d'un index pour clé et un index pour valeur
	public static function keyValueIndex(int $key=0,int $value=1,array $array):array 
	{
		$return = [];
		$array = array_values($array);
		
		if(array_key_exists($key,$array) && is_scalar($array[$key]) && array_key_exists($value,$array))
		$return[$array[$key]] = $array[$value]; 
		
		return $return;
	}
	
	
	// keys
	// retourne toutes les clés d'un tableau ou toutes les clés ayant la valeur donnée en deuxième argument
	// si valeur est null, la fonction ne cherche pas à moins que searchNull soit true
	// donc impossible de cherccher la valeur null avec cette fonction (utiliser arr::valueKey)
	// mb par défaut lors de la recherche insensitive
	public static function keys(array $array,$value=null,bool $sensitive=true,bool $searchNull=false):array
	{
		$return = [];
		
		if($value === null && $searchNull === false)
		$return = array_keys($array);
		
		else
		{
			if($sensitive === false)
			{
				$array = static::valuesLower($array);
				$value = Str::map([Str::class,'lower'],$value,true);
			}
			
			$return = array_keys($array,$value,true);
		}
		
		return $return;
	}
	
	
	// values
	// retourne les valeurs d'un tableau
	// is permet de spécifier le type de valeurs à garder dans le tableau réindexé
	public static function values(array $array,$is=null):array
	{
		$return = [];
		
		if($is !== null)
		{
			foreach ($array as $value) 
			{
				if(Validate::is($is,$value))
				$return[] = $value;
			}
		}
		
		else
		$return = array_values($array);
		
		return $return;
	}
	
	
	// shift
	// dépile un ou plusieurs éléments au début d'un tableau
	// array est passé par référence
	public static function shift(array &$array,int $amount=1)
	{
		$return = null;
		
		if($amount === 1)
		$return = array_shift($array);
		
		elseif($amount > 1)
		{
			$return = [];
			
			while ($amount > 0) 
			{
				$return[] = array_shift($array);
				$amount--;
			}
		}
		
		return $return;
	}
	
	
	// pop
	// enlève un ou plusieurs éléments à la fin du tableau
	// array est passé par référence
	public static function pop(array &$array,int $amount=1)
	{
		$return = null;

		if($amount === 1)
		$return = array_pop($array);
		
		elseif($amount > 1)
		{
			$return = [];
			
			while ($amount > 0) 
			{
				$return[] = array_pop($array);
				$amount--;
			}
		}
		
		return $return;
	}
	
	
	// walk
	// wrapper pour array_walk
	// array est passé par référence
	public static function walk(callable $callable,array &$array,$data=null):bool
	{
		return array_walk($array,$callable,$data);
	}
	
	
	// map
	// similaire à array_map, mais permet de spécifier des arguments en troisième arguments
	// pas possible de mettre plusieurs tableaux
	// si callable est closure à ce moment au moins trois arguments sont envoyés à la fonction = value, key et array
	public static function map(callable $callable,array $array,...$args):array
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if($callable instanceof \Closure)
			$return[$key] = $callable($value,$key,$array,...$args);
			
			else
			$return[$key] = $callable($value,...$args);
		}
		
		return $return;
	}


	// filter
	// wrapper pour array_filter
	// si callable est closure, à ce moment trois arguments sont envoyés à la fonction = value, key et array
	public static function filter(callable $callable,array $array,int $flag=0):array
	{
		$return = [];
		
		if($callable instanceof \Closure)
		{
			foreach ($array as $key => $value) 
			{
				if($callable($value,$key,$array))
				$return[$key] = $value;
			}
		}
		else
		$return = array_filter($array,$callable,$flag);
		
		return $return;
	}
	

	// reduce
	// wrapper pour array_reduce
	public static function reduce(callable $callable,array $array,$data=null) 
	{
		return array_reduce($array,$callable,$data);
	}
	
	
	// diffAssoc
	// retourne les slices des clés et valeurs du premier tableau qui ne sont trouvés dans aucun autre tableau
	// possibilité de mettre des valeurs non scalar
	public static function diffAssoc(array ...$values):array
	{
		$return = [];
		
		if(static::validates('scalar',...$values))
		$return = array_diff_assoc(...$values);
		
		elseif(count($values) > 1)
		{
			$main = $values[0];
			unset($values[0]);
			
			foreach ($main as $key => $value) 
			{
				$found = false;
				foreach ($values as $v) 
				{
					if(array_key_exists($key,$v) && $v[$key] === $value)
					$found = true;
				}
				
				if($found === false)
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// diffKey
	// retourne les slices des clés du premier tableau qui ne sont trouvés dans aucun autre tableau
	public static function diffKey(array ...$values):array
	{
		return array_diff_key(...$values);
	}
	
	
	// diff
	// retourne les slices des valeurs du premier tableau qui ne sont trouvés dans aucun autre tableau
	// possibilité de mettre des valeurs non scalar
	public static function diff(array ...$values):array
	{
		$return = [];

		if(static::validates('scalar',...$values))
		$return = array_diff(...$values);
		
		elseif(count($values) > 1)
		{
			$main = $values[0];
			unset($values[0]);
			
			foreach ($main as $key => $value) 
			{
				$found = false;
				foreach ($values as $v) 
				{
					if(in_array($value,$v,true))
					$found = true;
				}
				
				if($found === false)
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// intersectAssoc
	// retourne les slices identiques dans tous les tableaux
	// possibilité de mettre des valeurs non scalar
	public static function intersectAssoc(array ...$values):array
	{
		$return = [];
		
		if(static::validates('scalar',...$values))
		$return = array_intersect_assoc(...$values);
		
		elseif(count($values) > 1)
		{
			$main = $values[0];
			unset($values[0]);
			
			foreach ($main as $key => $value) 
			{
				$found = true;
				
				foreach ($values as $v) 
				{
					if(!array_key_exists($key,$v) || $v[$key] !== $value)
					$found = false;
				}
				
				if($found === true)
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// intersectKey
	// retourne les slices des clés identiques dans tous les tableaux
	public static function intersectKey(array ...$values):array
	{
		return array_intersect_key(...$values);
	}
	
	
	// intersect
	// retourne les slices des valeurs identiques dans tous les tableaux
	// possibilité de mettre des valeurs non scalar
	public static function intersect(array ...$values):array
	{
		$return = [];
		
		if(static::validates('scalar',...$values))
		$return = array_intersect(...$values);
		
		elseif(count($values) > 1)
		{
			$main = $values[0];
			unset($values[0]);
			
			foreach ($main as $key => $value) 
			{
				$found = true;
				
				foreach ($values as $v) 
				{
					if(!in_array($value,$v,true))
					$found = false;
				}
				
				if($found === true)
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// unsetBeforeKey
	// enlève les entrées avant une clé
	public static function unsetBeforeKey($key,array $return):array
	{
		foreach ($return as $k => $v) 
		{
			if($k === $key)
			break;
			
			else
			unset($return[$k]);
		}
		
		return $return;
	}
	
	
	// unsetAfterKey
	// enlève les entrées après une clé
	public static function unsetAfterKey($key,array $return):array
	{
		$delete = false;
		
		foreach ($return as $k => $v) 
		{
			if($k === $key)
			$delete = true;
			
			elseif($delete === true)
			unset($return[$k]);
		}
		
		return $return;
	}
	
	
	// unsetBeforeValue
	// enlève les entrées avant une valeur
	// sensible à la case
	public static function unsetBeforeValue($value,array $return):array
	{
		foreach ($return as $k => $v) 
		{
			if($v === $value)
			break;
			
			else
			unset($return[$k]);
		}
		
		return $return;
	}
	
	
	// unsetAfterValue
	// enlève les entrées après une valeur
	// sensible à la case
	public static function unsetAfterValue($value,array $return):array
	{
		$delete = false;
		
		foreach ($return as $k => $v) 
		{
			if($v === $value)
			$delete = true;
			
			elseif($delete === true)
			unset($return[$k]);
		}
		
		return $return;
	}
	
	
	// unsetBeforeIndex
	// enlève les entrées avant un certain index
	public static function unsetBeforeIndex(int $index,array $return):array
	{
		$i = 0;
		foreach ($return as $key => $value) 
		{
			if($i < $index)
			unset($return[$key]);
			
			$i++;
		}
		
		return $return;
	}
	
	
	// unsetAfterIndex
	// enlève les entrées après un certain index
	public static function unsetAfterIndex(int $index,array $return):array
	{
		$i = 0;
		foreach ($return as $key => $value) 
		{
			if($i > $index)
			unset($return[$key]);
			
			$i++;
		}
		
		return $return;
	}
	
	
	// unsetBeforeCount
	// enlève les entrées avant un certain nombre
	public static function unsetBeforeCount(int $count,array $return):array
	{
		$i = 1;
		foreach ($return as $key => $value) 
		{
			if($i < $count)
			unset($return[$key]);
			
			$i++;
		}
		
		return $return;
	}
	
	
	// unsetAfterCount
	// enlève les entrées après un certain nombre
	public static function unsetAfterCount(int $count,array $return):array
	{
		$i = 1;
		foreach ($return as $key => $value) 
		{
			if($i > $count)
			unset($return[$key]);
			
			$i++;
		}
		
		return $return;
	}
	
	
	// count
	// count les clés d'un tableau
	public static function count(array $array):int
	{
		return count($array,COUNT_NORMAL);
	}
	
	
	// countValues
	// compte le nombre d'occurence d'une même valeur scalar dans un tableau
	// si une valeur n'est pas scalarNotBool, n'utilise pas la fonction php
	// mb par défaut lors de la recherche insensitive
	public static function countValues(array $array,bool $sensitive=true):array
	{
		$return = [];
		
		if(static::validate('scalarNotBool',$array))
		$return = array_count_values($array);
		
		else
		{
			if($sensitive === false)
			$array = static::valuesLower($array);
			
			foreach ($array as $key => $value) 
			{
				if(static::isKey($value))
				{
					if(array_key_exists($value,$return))
					$return[$value] += 1;
					else
					$return[$value] = 1;
				}
			}
		}
		
		return $return;
	}
	
	
	// search
	// retourne la clé de la première valeur trouvé
	// possibilité de faire une recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function search($value,array $array,bool $sensitive=true) 
	{
		$return = null;
		
		if($sensitive === false)
		{
			$array = static::valuesLower($array);
			$value = Str::map([Str::class,'lower'],$value,true);
		}
		
		$search = array_search($value,$array,true);
		if($search !== false)
		$return = $search;
		
		return $return;
	}
	
	
	// searchFirst
	// retourne la première clé qui existe dans le tableau
	// possibilité de faire une recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function searchFirst(array $values,array $array,bool $sensitive=true)
	{
		$return = null;
		
		if($sensitive === false)
		{
			$array = static::valuesLower($array);
			$values = static::valuesLower($values);
		}
		
		foreach ($values as $v) 
		{
			$key = array_search($v,$array,true);
			
			if($key !== false)
			{
				$return = $key;
				break;
			}
		}
		
		return $return;
	}
	
	
	// in
	// recherche si la valeur est dans un tableau via la fonction in_array
	// possibilité de faire une recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function in($value,array $array,bool $sensitive=true):bool
	{
		$return = false;

		if($sensitive === false)
		{
			$array = static::valuesLower($array);
			$value = Str::map([Str::class,'lower'],$value,true);
		}

		if(in_array($value,$array,true))
		$return = true;

		return $return;
	}
	
	
	// ins
	// recherche que toutes les valeurs fournis sont dans le tableau via la fonction in_array
	// possibilité de faire une recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function ins(array $values,array $array,bool $sensitive=true):bool
	{
		$return = false;

		if(!empty($values))
		{
			$return = true;
			
			if($sensitive === false)
			{
				$array = static::valuesLower($array);
				$values = static::valuesLower($values);
			}
			
			foreach ($values as $value) 
			{
				if(!in_array($value,$array,true))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}
	
	
	// inFirst
	// retourne la première valeur trouvé dans le tableau ou null si rien n'est trouvé
	// possibilité de faire une recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function inFirst(array $values,array $array,bool $sensitive=true)
	{
		$return = null;
		
		if($sensitive === false)
		{
			$array = static::valuesLower($array);
			$values = static::valuesLower($values);
		}
		
		foreach ($values as $value) 
		{
			if(in_array($value,$array,true))
			{
				$return = $value;
				break;
			}
		}
		
		return $return;
	}
	
	
	// combine
	// permet de créer des tableaux à partir d'une variable pour les clés et une autre pour les valeurs
	// si value est scalar ou null, la valeur est utilisé pour chaque clé
	// pas obligé de fournir des array
	// retourne un array vide en cas d'erreur
	public static function combine($keys,$values):array 
	{
		$return = [];
		$keys = (array) $keys;
		
		if(is_scalar($values) || $values === null)
		$values = array_fill_keys($keys,$values);
		
		if(!empty($keys) && static::validate('arrKey',$keys) && is_array($values) && count($keys) === count($values))
		$return = array_combine($keys,$values);
		
		return $return;
	}
	
	
	// uncombine
	// retourne un tableau à deux clés, avec array_keys et array_values
	public static function uncombine(array $array):array 
	{
		return [array_keys($array),array_values($array)];
	}
	
	
	// range
	// fonction crée pour contourner un bogue dans range -> si min = 2, max = 3, inc = 2
	public static function range(int $min,int $max,int $inc=1):array
	{
		$return = [];
		
		if(($max > 0 && (($max - $inc) < $min)) || $max < $min)
		$max = $min;
		
		$return = range($min,$max,$inc);

		return $return;
	}
	
	
	// shuffle
	// mélange un tableau, mais conserve les clés
	public static function shuffle(array $array,bool $preserve=true):array
	{
		$return = [];
		
		if($preserve === true)
		{
			$keys = array_keys($array);
			shuffle($keys);
			$return = static::keysSlice($keys,$array);
		}
		
		else
		{
			$return = $array;
			shuffle($return);
		}
		
		return $return;
	}
	
	
	// reverse
	// invertit un tableau
	public static function reverse(array $array,bool $preserve=true):array 
	{
		return array_reverse($array,$preserve);
	}
	
	
	// getSortAscDesc
	// méthode utilisé par toutes les méthodes sortent pour déterminer ordre ascendant ou descendant
	public static function getSortAscDesc($sort):?string 
	{
		$return = null;
		
		if(in_array($sort,[true,'asc','ASC',1],true))
		$return = 'asc';
		
		elseif(in_array($sort,[false,'desc','DESC',2],true))
		$return = 'desc';
		
		return $return;
	}
	
	
	// sort
	// sort les clés ou valeurs d'un tableau
	// les clés sont conservés dans tous les cas
	// sort peut aussi être un int 1, 2, 3 ou 4
	public static function sort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
	{
		$ascDesc = static::getSortAscDesc($sort);
		
		if($ascDesc === 'asc')
		$sort = 'ksort';
		
		elseif($ascDesc === 'desc')
		$sort = 'krsort';
		
		if($sort === 3)
		$sort = 'asort';
		
		elseif($sort === 4)
		$sort = 'arsort';
		
		if(Call::is($sort))
		$sort($return,$type);
		
		return $return;
	}
	
	
	// sortNumbersFirst
	// sort un tableau, met les clés avec numéros en premier
	// l'ordre des clés non numériques sont conservés
	public static function sortNumbersFirst(array $array,$sort=true):array 
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_numeric($key))
			{
				$return[$key] = $value;
				unset($array[$key]);
			}
		}
		
		if(!empty($return))
		$return = static::sort($return,$sort);
		
		if(!empty($array))
		$return = static::append($return,$array);
		
		return $return;
	}
	
	
	// random
	// retourne une ou plusieurs clé valeur random d'un tableau
	// si csprng est true, utilise l'extension csprng pour généré le random
	public static function random(array $array,int $count=1,bool $csprng=false):array
	{
		$return = [];
		
		if($csprng === true)
		$return = Crypt::randomArray($array,$count);
		
		else
		{
			$countArray = count($array);
			$count = ($count >= $countArray)? $countArray:$count;
			
			if($count > 0)
			{
				$keys = array_keys($array);
				$rand = array_rand($keys,$count);
				$return = static::gets((array) $rand,$array);
			}
		}
		
		return $return;
	}
	
	
	// pad
	// wrapper pour array_pad
	public static function pad(int $size,$value,array $array):array 
	{
		return array_pad($array,$size,$value);
	}
	
	
	// flip 
	// reformat un tableau en s'assurant que la valeur devienne la clé 
	// value permet de specifier la valeur des nouvelles valeurs du tableau, si null prend la clé
	// exception permet d'exclure le contenu d'une clé du reformatage
	public static function flip(array $array,$value=null,$exception=null):array
	{               
		$return = [];
		
		if(static::isKey($exception))
		$exception = [$exception];
		
		foreach ($array as $k => $v) 
		{
			// exception
			if(!empty($exception) && is_array($exception) && in_array($k,$exception,true))
			$return[$k] = $v;
			
			// cle normal de tableau
			elseif(static::isKey($v))
			$return[$v] = ($value === null)? $k:$value;
			
			// autre valeur
			else
			$return[$k] = $v;
		}
		  
		return $return;
	}
	
	
	// unique
	// support pour recherche non sensible à la case
	// si removeOriginal est true, la première valeur unique sera effacé si un duplicat est trouvé
	public static function unique(array $array,bool $removeOriginal=false,bool $sensitive=true):array
	{
		$return = [];
		
		if($removeOriginal === true)
		$original = [];
		
		foreach ($array as $key => $value) 
		{
			if($removeOriginal === true)
			{
				if(!static::in($value,$return,$sensitive) && !static::in($value,$original,$sensitive))
				$return[$key] = $value;
				
				else
				{
					$search = static::search($value,$return,$sensitive);
					if($search !== null)
					{
						unset($return[$search]);
						$original[] = $value;
					}
				}
			}
			
			elseif(!static::in($value,$return,$sensitive))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// duplicate
	// retourne les valeurs dupliqués, l'inverse de arr::unique
	// support pour recherche non sensible à la case
	public static function duplicate(array $array,bool $keepOriginal=false,bool $sensitive=true):array
	{
		$return = [];
		
		if(!empty($array))
		{
			$unique = static::unique($array,$keepOriginal,$sensitive);
			
			if(!empty($unique))
			$return = static::diffKey($array,$unique);
			else
			$return = $array;
		}
		
		return $return;
	}
	
	
	// implode
	// implode un tableau en chaine
	// le delimiter divise chaque entrée, les tableaux du tableau sont ignorés
	// possibilité de trim et clean
	public static function implode(string $delimiter,array $value,bool $trim=false,bool $clean=false):string
	{
		$return = '';
		
		if($trim === true || $clean === true)
		$value = static::trimClean($value,$trim,$trim,$clean);
		
		if(static::isUni($value))
		$return = implode($delimiter,$value);
		
		else
		{
			foreach ($value as $v) 
			{
				if(is_scalar($v))
				{
					$v = (string) $v;
					$return .= (!empty($return))? $delimiter:'';
					$return .= $v;
				}
			}
		}
		
		return $return;
	}
	
	
	// implodeTrim
	// implode un tableau en chaine
	// trim chaque entrée du tableau au préalable
	public static function implodeTrim(string $delimiter,array $value,bool $clean=false):string
	{
		return static::implode($delimiter,$value,true,$clean);
	}
	
	
	// implodeClean
	// implode un tableau en chaine
	// clean le tableau au préalable
	public static function implodeClean(string $delimiter,array $value,bool $trim=false):string
	{
		return static::implode($delimiter,$value,$trim,true);
	}
	
	
	// implodeTrimClean
	// implode un tableau en chaine
	// trim chaque entrée du tableau et clean le tableau au préalable
	public static function implodeTrimClean(string $delimiter,array $value):string
	{
		return static::implode($delimiter,$value,true,true);
	}
	
	
	// implodeKey
	// fait un implode avec un deuxième délimiteur pour les clés
	// possibilité de trim et clean
	public static function implodeKey(string $delimiter,string $keyDelimiter,array $value,$trim=false,bool $clean=false):string
	{
		$return = '';
		
		if($trim === true || $clean === true)
		$value = static::trimClean($value,$trim,$trim,$clean);
		
		foreach ($value as $k => $v) 
		{
			if(is_scalar($v))
			{
				$k = (string) $k;
				$v = (string) $v;
				$return .= (!empty($return))? $delimiter:'';
				$return .= $k.$keyDelimiter.$v;
			}
		}
		
		return $return;
	}
	
	
	// explode
	// explose et merge toutes les variables scalar d'un tableau
	public static function explode(string $delimiter,array $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
	{
		$return = [];
		$limit = ($limit === null)? PHP_INT_MAX:$limit;
		
		foreach ($value as $k => $v) 
		{
			if(is_scalar($v))
			{
				$v = (string) $v;
				$x = Str::explode($delimiter,$v,$limit,$trim,$clean);
				$limit -= count($x);
				$return = array_merge($return,$x);
			}
		}
		
		return $return;
	}
	
	
	// explodekeyValue
	// explose les valeurs d'un tableau par deux et retourne sous une forme clé -> valeur
	public static function explodekeyValue(string $delimiter,array $value,bool $trim=false,bool $clean=false) 
	{
		$return = [];
		
		foreach ($value as $k => $v) 
		{
			if(is_scalar($v))
			{
				$v = (string) $v;
				$x = Str::explode($delimiter,$v,2,$trim,$clean);
				if(count($x) === 2 && static::isKey($x[0]))
				$return[$x[0]] = $x[1];
			}
		}
		
		return $return;
	}
	
	
	// fill
	// combine entre range et array_fill_keys
	public static function fill(int $start=0,int $end=1,int $step=1,$value=true):array
	{
		$return = [];
		$keys = static::range($start,$end,$step);
		
		if(!empty($keys))
		$return = array_fill_keys($keys,$value);
		
		return $return;
	}
	
	
	// fillKeys
	// wrapper pour array_fill_keys
	public static function fillKeys(array $keys,$value=true):array
	{
		$return = [];
		
		if(!empty($keys))
		$return = array_fill_keys($keys,$value);
		
		return $return;
	}
	

	// chunk
	// prend un tableau et le divise selon la longueur que doit avoir chaque groupe
	// retourne un array multi
	public static function chunk(int $count,array $array,bool $preserve=true):array
	{
		return array_chunk($array,$count,$preserve);
	}
	
	
	// chunkGroup
	// prend un tableau et le divise dans un nombre total de groupe
	// retourne un array multi
	public static function chunkGroup(int $count,array $array,bool $preserve=true):array
	{
		$return = [];
		
		if(!empty($array) && $count > 0)
		{
			$count = (int) ceil(count($array) / $count);
			$return = array_chunk($array,$count,$preserve);
		}
		
		return $return;
	}
	

	// chunkMix
	// prend un tableau et le divise dans le nombre de groupe spécifié
	// mix les entrées selon cette logique -> (1,4),(2,5),(3,6)
	// retourne un array multi
	public static function chunkMix(int $count,array $array,bool $preserve=true):array
	{
		$return = [];
		
		if(!empty($array) && $count > 0)
		{
			$total = (int) ceil(count($array) / $count);
			$int = 0;
			$col = 0;
			
			while (count($array)) 
			{
				for($i=0; $i < $count; $i++) 
				{ 
					$key = key($array);
					
					if(is_numeric($key) || is_string($key))
					{
						$value = $array[$key];

						if($preserve === true)
						$return[$i][$key] = $value;
						else
						$return[$i][] = $value;

						unset($array[$key]);
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// chunkWalk
	// permet de subdiviser un tableau en tableau colonne selon un callback
	// si callback retourne true, la colonne existante est stocké et une nouvelle colonne est crée
	// si callback retourne faux, la colonne existante est stocké et fermé
	// si callback retourne null, la ligne est stocké si la colonne est ouverte, sinon elle est ignoré
	// retourne un tableau multidimensionnel colonne
	public static function chunkWalk(callable $callback,array $array):array 
	{
		$return = [];
		$col = null;
		
		foreach ($array as $key => $value) 
		{
			$result = $callback($value,$key,$array);
			
			if($result === true)
			{
				if(!empty($col))
				$return[] = $col;
				
				$col = [$value];
			}
			
			elseif($result === false)
			{
				if(!empty($col))
				$return[] = $col;
				
				$col = null;
			}
			
			elseif($result === null)
			{
				if(is_array($col))
				$col[] = $value;
			}
		}
		
		if(!empty($col))
		$return[] = $col;
		
		return $return;
	}
	
	
	// compareIn
	// compare un tableau selon un tableau in
	// toutes les clés de in doivent être dans array
	// la valeur dans le array doit être une des valeurs présentes dans le tableau in
	public static function compareIn(array $in,array $array):bool
	{
		$return = true;
		
		if(!empty($in))
		{
			foreach ($in as $key => $value) 
			{
				if($value !== null)
				{
					$r = false;
					
					if(array_key_exists($key,$array))
					{
						$value = (array) $value;
						
						if(in_array($array[$key],$value,true))
						$r = true;
					}
					
					if($r === false)
					{
						$return = false;
						break;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// compareOut
	// filtre un tableau selon un tableau out
	// array n'a pas besoin d'avoir les clés dans out
	// si la valeur dans le array est une des valeurs présentes dans le tableau out pour la même clé, retourne vrai
	public static function compareOut(array $out,array $array):bool
	{
		$return = false;
		
		if(!empty($out))
		{
			foreach ($out as $key => $value) 
			{
				if($value !== null)
				{
					if(array_key_exists($key,$array))
					{
						$value = (array) $value;
						
						if(in_array($array[$key],$value,true))
						$return = true;
					}
					
					if($return === true)
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// hasSlices
	// retourne vrai si toutes les slices (key pairs) du premier argument se retrouvent dans le tableau en deuxième argument 
	// retourne vrai si slices est array mais vide
	public static function hasSlices(array $slices,array $array,bool $sensitive=true):bool 
	{
		$return = true;
		
		if($sensitive === false && !empty($slices))
		{
			$slices = static::keysValuesLower($slices,true);
			$array = static::keysValuesLower($array,true);
		}
		
		foreach ($slices as $key => $value) 
		{
			$return = (array_key_exists($key,$array) && $array[$key] === $value);
			
			if($return === false)
			break;
		}
		
		return $return;
	}
	
	
	// slice
	// tranche des slices d'un array en utilisant start et end
	// start représente la clé de départ
	// end est la clé de fin
	// support pour clé insensible à la case
	public static function slice($start,$end,array $array,bool $sensitive=true):array 
	{
		$return = [];
		
		if(static::isKey($start))
		{
			$offset = static::keyIndex($start,$array,$sensitive);
			$length = 1;
			
			if(static::isKey($end))
			{
				$length = static::keyIndex($end,$array,$sensitive);
				
				if($length >= $offset)
				$length = $length - $offset + 1;
				
				else
				{
					$x = $offset;
					$offset = $length + 1;
					$length = $x - $offset + 1;
				}
			}
			
			if(is_int($offset) && is_int($length) && is_array($array))
			$return = array_slice($array,$offset,$length,true);
		}
		
		return $return;
	}
	
	
	// sliceIndex
	// wrapper pour array_slice
	public static function sliceIndex(int $offset,?int $length,array $array):array
	{
		$length = ($length === null)? 1:$length;
		return array_slice($array,$offset,$length,true);
	}
	

	// sliceFirst
	// retourne la première slice du tableau
	public static function sliceFirst(array $array):array
	{
		return array_slice($array,0,1,true);
	}
	
	
	// sliceLast
	// retourne la dernière slice du tableau
	public static function sliceLast(array $array):array
	{
		return array_slice($array,-1,1,true);
	}
	
	
	// sliceNav
	// permet de naviguer à travers les slices du tableau via l'argument nav
	// la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
	// retourne la slice ou null si non existante
	public static function sliceNav($key,int $nav,array $source):?array
	{
		$return = null;
		$index = static::keyIndex($key,$source);
		
		if(is_int($index))
		{
			$newIndex = static::indexNav($index,$nav,$source);
			
			if(is_int($newIndex))
			$return = static::sliceIndex($newIndex,1,$source);
		}
		
		return $return;
	}
	
	
	// sliceNavIndex
	// navigue les slices du tableau par index
	// la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
	// retourne la slice ou null si non existante
	public static function sliceNavIndex(int $index,int $nav,array $source):?array
	{
		$return = null;
		$newIndex = static::indexNav($index,$nav,$source);
		
		if(is_int($newIndex))
		$return = static::sliceIndex($newIndex,1,$source);

		return $return;
	}
	
	
	// splice
	// efface et remplace des slices d'un array en utilisant start et end
	// start représente la clé de départ
	// end est la clé de fin, si null représente 0 et si bool représente 1
	// à la différence de array_splice, les clés numérique ne sont pas réordonnées
	// important: les clés numériques existantes sont append, les clés string sont remplacés
	// support pour remplacement insensible à la case
	public static function splice($start,$end,array $array,?array $replace=null,bool $sensitive=true):array
	{
		$return = [];
		
		if(static::isKey($start))
		{
			$offset = static::keyIndex($start,$array,$sensitive);
			$length = 1;
			
			if($end === null)
			$length = 0;
			
			elseif(is_bool($end))
			$length = 1;
			
			elseif(static::isKey($end))
			{
				$length = static::keyIndex($end,$array,$sensitive);
				
				if($length > $offset)
				$length = $length - $offset + 1;
				
				elseif($length < $offset)
				{
					$x = $offset;
					$offset = $length + 1;
					$length = $x - $offset + 1;
				}
				
				elseif($length === $offset)
				$length = 1;
			}
			
			if(is_int($offset) && is_int($length))
			$return = static::spliceIndex($offset,$length,$array,$replace,$sensitive);
			
			else
			$return = $array;
		}
		
		return $return;
	}
	
	
	// spliceIndex
	// efface et remplace des slices d'un array en utilisant offset et length
	// à la différence de array_splice, les clés numérique ne sont pas réordonnées
	// important: les clés numériques existantes sont append, les clés string sont remplacés
	// support pour remplacement insensible à la case
	public static function spliceIndex(int $offset,?int $length,array $array,?array $replace=null,bool $sensitive=true):array
	{
		$return = [];
		$length = ($length === null)? 1:$length;
		$keys = array_keys($array);
		$values = array_values($array);
		
		if(is_array($replace))
		{
			array_splice($keys,$offset,$length,array_keys($replace));
			array_splice($values,$offset,$length,array_values($replace));
		}
		
		else
		{
			array_splice($keys,$offset,$length);
			array_splice($values,$offset,$length);
		}
		
		foreach ($keys as $index => $key) 
		{
			if(is_numeric($key) && array_key_exists($key,$return))
			$return[] = $values[$index];
			else
			$return[$key] = $values[$index];
		}
		
		if($sensitive === false)
		$return = static::keysInsensitive($return);
		
		return $return;
	}
	
	
	// spliceFirst
	// retourne le tableau sans la première slice
	// possibilité d'inséré du contenu au début via le tableau replace
	// support pour remplacement insensible à la case
	public static function spliceFirst(array $array,?array $replace=null,bool $sensitive=true):array
	{
		return static::spliceIndex(0,1,$array,$replace,$sensitive);
	}
	
	
	// spliceLast
	// retourne le tableau sans la dernière slice
	// possibilité d'inséré du contenu à la fin via le tableau replace
	// support pour remplacement insensible à la case
	public static function spliceLast(array $array,?array $replace=null,bool $sensitive=true):array
	{
		return static::spliceIndex(-1,1,$array,$replace,$sensitive);
	}
	
	
	// insert
	// effectue un remplacement via la méthode splice
	// n'enlève aucune rangée du tableau
	// support pour ajout insensible à la case
	public static function insert($start,array $replace,array $array,bool $sensitive=true):array
	{
		return static::splice($start,null,$array,$replace,$sensitive);
	}
	
	
	// insertIndex
	// effectue un remplacement via la méthode spliceIndex
	// n'enlève aucune rangée du tableau
	// support pour ajout insensible à la case
	public static function insertIndex(int $offset,array $replace,array $array,bool $sensitive=true):array
	{
		return static::spliceIndex($offset,0,$array,$replace,$sensitive);
	}
	
	
	// insertFirst
	// effectue un remplacement via la méthode spliceIndex
	// fait un remplacement au début du tableau
	// n'enlève aucune rangée du tableau
	// support pour ajout insensible à la case
	public static function insertFirst(array $replace,array $array,bool $sensitive=true):array
	{
		return static::spliceIndex(0,0,$array,$replace,$sensitive);
	}
	
	
	// insertLast
	// effectue un remplacement via la méthode spliceIndex
	// fait un remplacement avant la dernière clé du tableau
	// n'enlève aucune rangée du tableau
	// support pour ajout insensible à la case
	public static function insertLast(array $replace,array $array,bool $sensitive=true):array
	{
		return static::spliceIndex(-1,0,$array,$replace,$sensitive);
	}
	
	
	// insertInOrder
	// permet d'insérer des slices dans un tableau tout en conservant le caractère séquentielle des clés
	// idéaleement les clés des tableaux doivent être toutes du même type, la comparaison entre string et chiffre ne donne pas toujours les résultats souhaités
	public static function insertInOrder(array $replace,array $return) 
	{
		foreach ($replace as $key => $value) 
		{
			$k = null;
			$found = false;
			
			foreach ($return as $k => $v) 
			{
				if($k > $key)
				{
					$found = true;
					$return = static::insert($k,[$key=>$value],$return);
					break;
				}
			}
			
			if($found === false)
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// firstWithKey
	// retourne le premier tableau où la clé existe
	public static function firstWithKey($key,array ...$values):?array
	{
		$return = null;
		
		if(is_scalar($key))
		{
			// pour chaque tableau
			foreach ($values as $array) 
			{
				if(array_key_exists($key,$array))
				{
					$return = $array;
					break;
				}
			}
		}

		return $return;
	}
	
	
	// firstWithValue
	// retourne le premier tableau où la valeur existe
	// sensible à la case
	public static function firstWithValue($value,array ...$values):?array
	{
		$return = null;
		
		// pour chaque tableau
		foreach ($values as $array) 
		{
			if(is_array($array) && in_array($value,$array,true))
			{
				$return = $array;
				break;
			}
		}

		return $return;
	}
	
	
	// indexFirst
	// retourne le premier index du tableau
	public static function indexFirst(array $array) 
	{
		$array = array_values($array);
		return key($array);
	}
	
	
	// indexLast
	// retourne le dernier index du tableau
	public static function indexLast(array $array) 
	{
		$array = array_values($array);
		end($array);
		return key($array);
	}
	
	
	// indexExists
	// retourne vrai si l'index existe dans le tableau
	public static function indexExists(int $index,array $array):bool
	{
		$return = false;
		$array = array_values($array);
		
		if($index < 1)
		$index = static::indexPrepare($index,count($array));
		
		if(array_key_exists($index,$array))
		$return = true;
		
		return $return;
	}
	
	
	// indexesExists
	// retourne vrai si les index existe dans le tableau
	public static function indexesExists(array $indexes,array $array):bool
	{
		$return = false;
		$array = array_values($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		if(!empty($indexes))
		{
			$return = true;
			
			foreach ($indexes as $index) 
			{
				if(!array_key_exists($index,$array))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// indexesAre
	// retourne vrai si les index existent dans le tableau et que ce sont tous les index
	public static function indexesAre(array $indexes,array $array):bool
	{
		$return = false;		
		$indexes = static::indexPrepare($indexes,count($array));
		
		if(count($indexes) === count($array))
		$return = static::indexesExists($indexes,$array);
		
		return $return;
	}
	
	
	// indexesFirst
	// retourne le premier index trouvé dans un tableau
	public static function indexesFirst(array $indexes,array $array):?int
	{
		$return = null;
		$array = array_values($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(array_key_exists($index,$array))
			{
				$return = $index;
				break;
			}
		}
		
		return $return;
	}
	
	
	// indexesFirstValue
	// retourne la valeur du premier index trouvé dans un tableau
	public static function indexesFirstValue(array $indexes,array $array) 
	{
		$return = null;
		$array = array_values($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(array_key_exists($index,$array))
			{
				$return = $array[$index];
				break;
			}
		}
		
		return $return;
	}
	
	
	// indexKey
	// retourne la clé associé à un index de tableau
	public static function indexKey(int $index,array $array) 
	{
		$return = null;
		
		if($index < 0)
		$index = static::indexPrepare($index,count($array));
		
		if(is_int($index))
		{
			$keys = array_keys($array);
			
			if(array_key_exists($index,$keys))
			$return = $keys[$index];
		}
		
		return $return;
	}
	
	
	// indexesKey
	// retourne les clés associés aux index du tableau
	public static function indexesKey(array $indexes,array $array):array
	{
		$return = [];
		$keys = array_keys($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(array_key_exists($index,$keys))
			$return[$index] = $keys[$index];
			
			else
			$return[$index] = null;
		}
		
		return $return;
	}
	

	// indexSlice
	// retourne une slice à partir d'un index de tableau
	public static function indexSlice(int $index,array $array):array
	{
		$return = [];
		$keys = array_keys($array);
		$array = array_values($array);
		
		if($index < 0)
		$index = static::indexPrepare($index,count($array));
		
		if(array_key_exists($index,$array))
		$return[$keys[$index]] = $array[$index];
		
		return $return;
	}
	
	
	// indexesSlice
	// retourne des slices à partir d'index de tableau
	public static function indexesSlice(array $indexes,array $array):array
	{
		$return = [];
		$keys = array_keys($array);
		$array = array_values($array);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(array_key_exists($index,$array))
			$return[$keys[$index]] = $array[$index];
		}
		
		return $return;
	}
	
	
	// indexStrip
	// retourne le tableau sans la slice de l'index
	public static function indexStrip(int $index,array $return):array
	{
		$keys = array_keys($return);
		$array = array_values($return);
		
		if($index < 0)
		$index = static::indexPrepare($index,count($array));
		
		if(array_key_exists($index,$array))
		unset($return[$keys[$index]]);
		
		return $return;
	}
	
	
	// indexesStrip
	// retourne le tableau sans les slice des index
	public static function indexesStrip(array $indexes,array $return):array 
	{
		$keys = array_keys($return);
		$array = array_values($return);
		$indexes = static::indexPrepare($indexes,count($array));
		
		foreach ($indexes as $index) 
		{
			if(array_key_exists($index,$array))
			unset($return[$keys[$index]]);
		}
		
		return $return;
	}
	
	
	// indexNav
	// permet de naviguer à travers les index du tableau via l'argument nav
	// la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
	// retourne le nouvel index ou null
	public static function indexNav(int $index,int $nav,array $array):?int
	{
		$return = null;
		$keys = array_keys($array);
		$index = static::indexPrepare($index,count($array)) + $nav;
		
		if(array_key_exists($index,$keys))
		$return = $index;
		
		return $return;
	}
	
	
	// keyFirst
	// retourne la première key
	public static function keyFirst(array $array)
	{
		reset($array);
		return key($array);
	}
	
	
	// keyLast
	// retourne la dernière key
	public static function keyLast(array $array)
	{
		end($array);
		return key($array);
	}
	
	
	// ikey
	// retourne la première clé se comparant insensible à la case avec la clé donné en argument
	public static function ikey($key,array $array)
	{
		$return = null;

		foreach ($array as $k => $value) 
		{
			if(Str::icompare($key,$k))
			{
				$return = $k;
				break;
			}
		}
		
		return $return;
	}
	

	// ikeys
	// retourne toutes les clés se comparant insensible à la case avec la clé donné en argument
	public static function ikeys($key,array $array):array
	{
		$return = [];
		
		foreach ($array as $k => $value) 
		{
			if(Str::icompare($key,$k))
			$return[] = $k;
		}
		
		return $return;
	}
	
	
	// keyExists
	// pour vérifier l'existence d'une clé dans un tableau
	// support pour clé insensitive
	public static function keyExists($key,array $array,bool $sensitive=true):bool
	{
		$return = false;
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$key = (is_string($key))? Str::lower($key,true):$key;
				$array = static::keysLower($array,true);
			}
			
			if(array_key_exists($key,$array))
			$return = true;
		}
		
		return $return;
	}
	

	// keysExists
	// pour vérifier l'existence de toutes les clés fournis en argument
	// support pour clé insensitive
	public static function keysExists(array $keys,array $array,bool $sensitive=true):bool
	{
		$return = false;
		
		if(!empty($keys))
		{
			$return = true;
			
			if($sensitive === false)
			{
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			
			foreach ($keys as $key) 
			{
				if(!static::isKey($key) || !array_key_exists($key,$array))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	

	// keysAre
	// pour vérifier l'existence de toutes les clés dans un tableau
	// support pour clé insensitive
	public static function keysAre(array $keys,array $array,bool $sensitive=true):bool
	{
		$return = false;		
		
		if(!empty($keys))
		{
			if($sensitive === false)
			{
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			
			if(count($keys) === count($array))
			$return = static::keysExists($keys,$array);
		}
		
		return $return;
	}
	
	
	// keysFirst
	// retourne la première clé trouvé dans un tableau
	// support pour clé insensitive
	public static function keysFirst(array $keys,array $array,bool $sensitive=true)
	{
		$return = null;
		
		if(!empty($keys))
		{
			if($sensitive === false)
			{
				$original = $keys;
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			
			foreach ($keys as $i => $key) 
			{
				if(static::isKey($key) && array_key_exists($key,$array))
				{
					$return = ($sensitive === true)? $key:$original[$i];
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// keysIndexesFirst
	// retourne la première clé ou index trouvé dans un tableau
	// si la valeur est numérique, c'est considéré comme une recherche par index
	// support pour clé insensitive
	public static function keysIndexesFirst(array $keys,array $array,bool $sensitive=true)
	{
		$return = null;
		
		if(!empty($keys))
		{
			if($sensitive === false)
			{
				$original = $keys;
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			$arrayKeys = array_keys($array);
			
			foreach ($keys as $i => $key) 
			{
				if(is_numeric($key))
				{
					if(array_key_exists($key,$arrayKeys))
					{
						$return = ($sensitive === true)? $arrayKeys[$key]:$original[$arrayKeys[$key]];
						break;
					}
				}
				
				elseif(is_string($key))
				{
					if(array_key_exists($key,$array))
					{
						$return = ($sensitive === true)? $key:$original[$i];
						break;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// keysFirstValue
	// retourne la valeur de la première clé trouvé dans un tableau
	// support pour clé insensitive
	public static function keysFirstValue(array $keys,array $array,bool $sensitive=true)
	{
		$return = null;
		$key = static::keysFirst($keys,$array,$sensitive);
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			$key = static::ikey($key,$array);
			
			$return = $array[$key];
		}
		
		return $return;
	}
	
	
	// keysIndexesFirstValue
	// retourne la valeur de la première clé ou index trouvé dans un tableau
	// si la valeur est numérique, c'est considéré comme une recherche par index
	// support pour clé insensitive
	public static function keysIndexesFirstValue(array $keys,array $array,bool $sensitive=true) 
	{
		$return = null;
		$key = static::keysIndexesFirst($keys,$array,$sensitive);
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			$key = static::ikey($key,$array);
			
			$return = $array[$key];
		}
		
		return $return;
	}
	
	
	// keyIndex
	// retourne l'index d'une clé dans un tableau
	// retourne null si clé non existante
	// support pour clé insensitive
	public static function keyIndex($key,array $array,bool $sensitive=true):?int
	{
		$return = null;
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$key = (is_string($key))? Str::lower($key,true):$key;
				$array = static::keysLower($array,true);
			}
			
			if(array_key_exists($key,$array))
			{
				$search = array_search($key,array_keys($array),true);
				
				if($search !== false)
				$return = $search;
			}
		}
		
		return $return;
	}
	
	
	// keysIndex
	// retourne les index de clé dans un tableau, retourne un tableau
	// support pour clé insensitive
	public static function keysIndex(array $keys,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		if(!empty($keys))
		{
			if($sensitive === false)
			{
				$original = $keys;
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			
			$arrayKeys = array_keys($array);
			
			foreach ($keys as $i => $key) 
			{
				if(static::isKey($key))
				{
					$k = ($sensitive === true)? $key:$original[$i];
					$search = array_search($key,$arrayKeys,true);
					
					if($search !== false)
					$return[$k] = $search;
					
					else
					$return[$k] = null;
				}
			}
		}
		
		return $return;
	}
	
	
	// keySlice
	// retourne la slice du tableau à la clé donné
	// support pour clé insensitive, va retourner la dernière slice comparable insensible à la case avec la clé fournie
	public static function keySlice($key,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		if(static::isKey($key))
		{
			if($sensitive === false)
			{
				$original = $key;
				$key = (is_string($key))? Str::lower($key,true):$key;
				$array = static::keysLower($array,true);
			}
			
			if(array_key_exists($key,$array))
			{
				$k = ($sensitive === true)? $key:$original;
				$return[$k] = $array[$key];
			}
		}
		
		return $return;
	}
	
	
	// keysSlice
	// tranche un array à une ou plusieurs clé
	// support pour clé insensitive, va retourner la dernière slice comparable insensible à la case avec la clé fournie
	public static function keysSlice(array $keys,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		if(!empty($keys))
		{
			if($sensitive === false)
			{
				$original = $keys;
				$keys = static::valuesLower($keys);
				$array = static::keysLower($array,true);
			}
			
			foreach ($keys as $i => $key) 
			{
				if(static::isKey($key) && array_key_exists($key,$array))
				{
					$k = ($sensitive === true)? $key:$original[$i];
					$return[$k] = $array[$key];
				}
			}
		}
		
		return $return;
	}
	
	
	// ikeySlice
	// retourne toutes les slices avec des clés se comparant insensible à la case avec la clé donné en argument
	public static function ikeySlice($key,array $array):array
	{
		$return = [];
		
		if(static::isKey($key))
		{
			foreach ($array as $k => $value) 
			{
				if(Str::iCompare($key,$k))
				$return[$k] = $value;
			}
		}
		
		return $return;
	}
	
	
	// keyStrip
	// retourne le tableau sans la slice de la clé
	// support pour clé insensitive, va strip toutes les clés se comparant de façon insensible à la case
	public static function keyStrip($key,array $return,bool $sensitive=true):array 
	{
		if(static::isKey($key))
		{
			if($sensitive === true || is_numeric($key))
			{
				if(array_key_exists($key,$return))
				unset($return[$key]);
			}
			
			else
			{
				foreach ($return as $k => $v) 
				{
					if(is_string($k) && Str::icompare($k,$key))
					unset($return[$k]);
				}
			}
		}
		
		return $return;
	}
	
	
	// keysStrip
	// retourne le tableau sans les slices des clés
	// support pour clé insensitive, va strip toutes les clés se comparant de façon insensible à la case
	public static function keysStrip(array $keys,array $return,bool $sensitive=true):array 
	{
		foreach ($keys as $key) 
		{
			if(static::isKey($key))
			{
				if($sensitive === true || is_numeric($key))
				{
					if(array_key_exists($key,$return))
					unset($return[$key]);
				}
				
				else
				{
					foreach ($return as $k => $v) 
					{
						if(is_string($k) && Str::icompare($k,$key))
						unset($return[$k]);
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// keyNav
	// permet de naviguer à travers les clés du tableau via l'argument nav
	// la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
	// retourne la nouvelle clé ou null
	public static function keyNav($key,int $nav,array $array)
	{
		$return = null;
		$index = static::keyIndex($key,$array);
		
		if(is_int($index))
		{
			$newIndex = static::indexNav($index,$nav,$array);
			
			if(is_int($newIndex))
			{
				$keys = array_keys($array);
				if(array_key_exists($newIndex,$keys))
				$return = $keys[$newIndex];
			}
		}
		
		return $return;
	}
	
	
	// keysStart
	// retourne les slices des clés commençant par la chaîne
	public static function keysStart(string $str,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_string($key) && Str::isStart($str,$key,$sensitive))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysEnd
	// retourne les slices des clés finissant par la chaîne
	public static function keysEnd(string $str,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_string($key) && Str::isEnd($str,$key,$sensitive))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysMap
	// permet de changer les clés d'un tableau via callback
	public static function keysMap(callable $callable,$array,bool $string=false,...$args):array 
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if($string === false || is_string($key))
			$key = $callable($key,...$args);
			
			if(static::isKey($key))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysChangeCase
	// change la case des clés dans le tableau 
	// case peut etre CASE_LOWER, CASE_UPPER ou callable
	public static function keysChangeCase($case,array $return,...$args):array
	{
		if(in_array($case,[CASE_LOWER,'lower','strtolower'],true))
		$return = static::keysLower($return,...$args);
		
		elseif(in_array($case,[CASE_UPPER,'upper','strtoupper'],true))
		$return = static::keysUpper($return,...$args);
		
		elseif(Call::is($case))
		$return = static::keysMap($case,$return,true,...$args);
		
		return $return;
	}
	
	
	// keysLower
	// change la case des clés dans le tableau pour lowercase
	// support pour multibyte
	public static function keysLower(array $array,?bool $mb=null):array
	{
		$return = [];
		$mb = ($mb === null)? Encoding::getMb($mb):$mb;
		
		if($mb === false)
		$return = array_change_key_case($array,CASE_LOWER);
		
		else
		{
			foreach ($array as $key => $value) 
			{
				$key = (is_string($key))? Str::lower($key,true):$key;
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// keysUpper
	// change la case des clés dans le tableau pour uppercase
	// support pour multibyte
	public static function keysUpper(array $array,?bool $mb=null):array
	{
		$return = [];
		$mb = ($mb === null)? Encoding::getMb($mb):$mb;
		
		if($mb === false)
		$return = array_change_key_case($array,CASE_UPPER);
		
		else
		{
			foreach ($array as $key => $value) 
			{
				if(is_string($key))
				$key = Str::upper($key,true);
				
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// keysInsensitive
	// retourne une version du tableau avec les clés en conflit de case retirés
	// garde la même case
	public static function keysInsensitive(array $array):array
	{
		$return = [];
		
		foreach ($array as $k => $v) 
		{
			if(is_string($k))
			$return = static::keyStrip($k,$return,false);
			
			$return[$k] = $v;
		}
		
		return $return;
	}
	
	
	// keysWrap
	// permet de wrapper la clé dans un delimiteur
	// il y a 7 modes, par défaut 0
	// mb sert seulement si le caractère a wrap est accenté minuscule vs majuscule
	public static function keysWrap(string $start,?string $end,array $array,int $mode=0,bool $sensitive=true):array
	{
		$return = [];
		
		if($end === null)
		$end = $start;
		
		foreach ($array as $key => $value) 
		{
			$key = (string) $key;
			
			if($mode === 0)
			$key = $start.$key.$end;
			
			elseif($mode === 1)
			$key = Str::wrapStartOrEnd($start,$end,$key,$sensitive);
			
			elseif($mode === 2)
			$key = Str::wrapStartEnd($start,$end,$key,$sensitive);
			
			elseif($mode === 3)
			$key = $start.$key;
			
			elseif($mode === 4)
			$key = Str::wrapStart($start,$key,$sensitive);
			
			elseif($mode === 5)
			$key = $key.$end;
			
			elseif($mode === 6)
			$key = Str::wrapEnd($end,$key,$sensitive);
			
			if(!array_key_exists($key,$return))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysUnwrap
	// permet de unwrapper la clé d'un délimiteur
	// il y a 4 modes, par défaut 0
	// mb sert seulement si le caractère a unwrap est accenté minuscule vs majuscule
	public static function keysUnwrap(string $start,?string $end,array $array,int $mode=0,bool $sensitive=true):array
	{		
		$return = [];
		
		if($end === null)
		$end = $start;
		
		foreach ($array as $key => $value) 
		{
			$key = (string) $key;
			
			if($mode === 0)
			$key = Str::stripStartOrEnd($start,$end,$key,$sensitive);
			
			elseif($mode === 1)
			$key = Str::stripStartEnd($start,$end,$key,$sensitive);
			
			elseif($mode === 2)
			$key = Str::stripStart($start,$key,$sensitive);
			
			elseif($mode === 3)
			$key = Str::stripEnd($end,$key,$sensitive);
			
			if(!array_key_exists($key,$return))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysReplace
	// str replace sur les clés du tableau
	public static function keysReplace(array $replace,array $return,bool $sensitive=true):array
	{
		if(!empty($replace))
		{
			foreach ($return as $key => $value) 
			{
				$strKey = (string) $key;
				$k = Str::replace($replace,$strKey,$sensitive);
				
				if($k !== $strKey)
				{
					$return[$k] = $return[$key];
					unset($return[$key]);
				}
			}
		}
	
		return $return;
	}
	
	
	// keysChange
	// permet de renommer des clés dans un tableau, tout en conservant les valeurs
	public static function keysChange(array $replace,array $return):array
	{
		foreach ($replace as $what => $to) 
		{
			if(static::isKey($what) && static::isKey($to) && array_key_exists($what,$return))
			{
				$value = $return[$what];
				unset($return[$what]);
				$return[$to] = $value;
			}
		}

		return $return;
	}
	

	// keysMissing
	// remplit les clés manquantes d'un tableau numérique
	// firstKey permet de spécifier à quel clé devrait commencer le remplacent, si null alors c'est à la première clé
	public static function keysMissing(array $array,$value=false,?int $firstKey=null):array
	{
		$return = [];
		
		if(!empty($array) && static::isIndexed($array))
		{
			$lastKey = null;
			if(is_int($firstKey))
			$lastKey = $firstKey - 1;
			
			foreach ($array as $k => $v) 
			{
				$lastKeyPlus = $lastKey + 1;
				if(is_numeric($lastKey) && is_numeric($k) && $k !== $lastKeyPlus)
				{
					$range = static::range($lastKeyPlus,(int) ($k - 1));
					if(!empty($range))
					{
						$missing = array_fill_keys(array_values($range),$value);
						$return = $return + $missing;
					}
				}
				
				$return[$k] = $v;
				$lastKey = $k;
			}
		}
		
		return $return;
	}
	
	
	// keysReindex
	// réindex les clés numériques d'un tableau
	public static function keysReindex(array $array,int $i=0):array 
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_numeric($key))
			{
				$return[$i] = $value;
				$i++;
			}
			
			else
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// keysSort
	// sort un tableau par clé
	// on peut mettre asc, true ou desc, false à sort (ksort ou krsort)
	// renvoie à la méthode sort
	public static function keysSort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
	{
		$ascDesc = static::getSortAscDesc($sort);
		
		if($ascDesc === 'asc')
		$sort = 'ksort';
		
		elseif($ascDesc === 'desc')
		$sort = 'krsort';
		
		return static::sort($return,$sort,$type);
	}
	
	
	// keyRandom
	// retourne la clé random d'un tableau
	public static function keyRandom(array $array)
	{
		$return = null;
		$slice = static::random($array,1);
		
		if(!empty($slice))
		$return = key($slice);
		
		return $return;
	}
	
	
	// valuesAre
	// retourne vrai si toutes les valeurs différentes du tableau sont dans le tableau values
	// support pour recherche insensible à la case
	public static function valuesAre(array $values,array $array,bool $sensitive=true):bool
	{
		$return = false;
		
		$unique = static::unique($array,false,$sensitive);
		if(static::ins($unique,$values,$sensitive))
		$return = true;
		
		return $return;
	}
	
	
	// valueFirst
	// retourne la première valeur
	public static function valueFirst(array $array)
	{
		$return = null;
		
		if(!empty($array))
		{
			reset($array);
			$return = current($array);
		}
		
		return $return;
	}
	
	
	// valueLast
	// retourne la dernière valeur
	public static function valueLast(array $array)
	{
		$return = null;
		
		if(!empty($array))
		{
			end($array);
			return current($array);
		}
		
		return $return;
	}
	

	// valueIndex
	// retourne tous les index contenant la valeur donnée
	// permet la recherche insensible à la case
	public static function valueIndex($value,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		$keys = static::valueKey($value,$array,$sensitive);
		if(!empty($keys))
		$return = array_values(static::keysIndex($keys,$array));
		
		return $return;
	}
	
	
	// valuesIndex
	// retourne tous les index contenant les valeurs données
	// permet la recherche insensible à la case
	public static function valuesIndex(array $values,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		$keys = static::valuesKey($values,$array,$sensitive);
		if(!empty($keys))
		$return = array_values(static::keysIndex($keys,$array));
		
		return $return;
	}


	// valueKey
	// retourne toutes les clés contenant contenant la valeur donnée
	// permet la recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function valueKey($value,array $array,bool $sensitive=true):array
	{
		return static::keys($array,$value,$sensitive,true);
	}
	
	
	// valuesKey
	// retourne toutes les clés contenant les valeurs données
	// permet la recherche insensible à la case
	// mb par défaut lors de la recherche insensitive
	public static function valuesKey(array $values,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		if($sensitive === false)
		{
			$array = static::valuesLower($array);
			$values = static::valuesLower($values);
		}
		
		foreach ($values as $value) 
		{
			foreach (array_keys($array,$value,true) as $key)
			{
				if(!in_array($key,$return,true))
				$return[] = $key;
			}
		}
		
		return $return;
	}


	// valueSlice
	// retourne toutes les slices contenant la valeur donnée
	// permet la recherche insensible à la case
	public static function valueSlice($value,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach (static::valueKey($value,$array,$sensitive) as $key)
		{
			$return[$key] = $array[$key];
		}
		
		return $return;
	}
	
	
	// valuesSlice
	// retourne toutes les slices contenant les valeurs données
	// permet la recherche insensible à la case
	public static function valuesSlice(array $values,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach (static::valuesKey($values,$array,$sensitive) as $key)
		{
			if(!array_key_exists($key,$return))
			$return[$key] = $array[$key];
		}
		
		return $return;
	}


	// valueStrip
	// retourne le tableau sans toutes les slices avec la valeur donnée
	// permet la recherche insensible à la case
	public static function valueStrip($value,array $return,bool $sensitive=true):array
	{
		foreach (static::valueKey($value,$return,$sensitive) as $key)
		{
			unset($return[$key]);
		}
		
		return $return;
	}
	
	
	// valuesStrip
	// retourne le tableau sans toutes les slices avec les valeurs données
	// permet la recherche insensible à la case
	public static function valuesStrip(array $values,array $return,bool $sensitive=true):array
	{
		foreach (static::valuesKey($values,$return,$sensitive) as $key) 
		{
			if(array_key_exists($key,$return))
			unset($return[$key]);
		}
		
		return $return;
	}
	
	
	// valueNav
	// permet de naviguer à travers les valeurs du tableau via l'argument nav
	// si plusieurs valeurs identiques dans le tableau, la méthode prend le premier index
	// la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
	// retourne la nouvelle valeur ou null
	public static function valueNav($value,int $nav,array $array) 
	{
		$return = null;
		$indexes = static::valueIndex($value,$array);
		
		if(is_array($indexes) && !empty($indexes))
		{
			$index = current($indexes);
			$newIndex = static::indexNav($index,$nav,$array);
			
			if(is_int($newIndex))
			{
				$values = array_values($array);
				if(array_key_exists($newIndex,$values))
				$return = $values[$newIndex];
			}
		}
		
		return $return;
	}
	
	
	// valueRandom
	// retourne la valeur random d'un tableau
	public static function valueRandom(array $array)
	{
		$return = null;
		$slice = static::random($array,1);
		
		if(!empty($slice))
		$return = current($slice);
		
		return $return;
	}
	
	
	// valuesChange
	// changement de valeur dans un tableau
	// sensible à la case
	// amount permet de spécifier combien de changements doivent être faire, en partant du début du tableau
	public static function valuesChange($value,$change,array $return,?int $amount=null):array 
	{
		$i = 0;
		foreach ($return as $k => $v) 
		{
			if($v === $value)
			{
				$return[$k] = $change;
				$i++;
				
				if(is_int($amount) && $i >= $amount)
				break;
			}
		}
		
		return $return;
	}
	
	
	// valuesReplace
	// str_replace sur les valeurs du tableau
	public static function valuesReplace(array $replace,array $return,bool $sensitive=true):array
	{
		if(!empty($replace))
		{
			foreach ($return as $key => $value) 
			{
				if(is_string($value))
				{
					$v = Str::replace($replace,$value,$sensitive);

					if($value !== $v)
					$return[$key] = $v;
				}
			}
		}
		
		return $return;
	}
	
	
	// valuesSearch
	// permet de faire la recherche d'un needle dans les valeurs scalaires d'un tableau
	// support pour recherche multiple si prepare est true et needle contient un +
	// retourne toutes les slices ou le needle est trouvé
	public static function valuesSearch(string $needle,array $array,bool $sensitive=true,bool $accentSensitive=true,bool $prepare=false,?string $separator=null):array
	{
		$return = [];
		
		if(strlen($needle) && !empty($array))
		{
			if($prepare === true)
			{
				$needle = Str::prepareSearch($needle,$separator);
				$prepare = false;
			}
			
			foreach ($array as $key => $value) 
			{
				if(is_scalar($value) && Str::search($needle,(string) $value,$sensitive,$accentSensitive,$prepare,$separator))
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// valuesSubReplace
	// fait un remplacement substring sur toutes les valeurs du tableau
	// si le tableau ne contient pas uniquemment des string, utilise validate map
	public static function valuesSubReplace($offset,$length,$replace,array $return):array 
	{
		if(static::validate('string',$return))
		$return = substr_replace($return,$replace,$offset,$length);
		else
		{
			$return = static::validateMap('string',function($value) use($offset,$length,$replace) {
				return substr_replace($value,$replace,$offset,$length);
			},$return);
		}
		
		return $return;
	}
	
	
	// valuesStart
	// retourne les slices des valeurs commençant par la chaîne
	public static function valuesStart(string $str,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_string($value) && Str::isStart($str,$value,$sensitive))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// valuesEnd
	// retourne les slices des valeurs finissant par la chaîne
	public static function valuesEnd(string $str,array $array,bool $sensitive=true):array
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_string($value) && Str::isEnd($str,$value,$sensitive))
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	
	// valuesChangeCase
	// change la case des valeurs string dans le tableau 
	// case peut etre CASE_LOWER, CASE_UPPER ou callable
	public static function valuesChangeCase($case,array $return,...$args):array
	{
		if(in_array($case,[CASE_LOWER,'lower','strtolower'],true))
		$return = static::valuesLower($return,...$args);
		
		elseif(in_array($case,[CASE_UPPER,'upper','strtoupper'],true))
		$return = static::valuesUpper($return,...$args);
		
		elseif(Call::is($case))
		$return = Str::map($case,$return,...$args);
		
		return $return;
	}
	
	
	// valuesLower
	// change la case des valeurs string dans le tableau pour lowercase
	// utilise multibyte
	public static function valuesLower(array $array):array
	{
		return Str::map([Str::class,'lower'],$array,true);
	}
	
	
	// valuesUpper
	// change la case des valeurs string dans le tableau pour uppercase
	// utilise multibyte
	public static function valuesUpper(array $array):array
	{
		return Str::map([Str::class,'upper'],$array,true);
	}

	
	// valuesSliceLength
	// retourne le tableau avec les entrées string ayant une longueur entre min et max
	// utilise multibyte
	// possible de garder les entrées numérique mais si elles n'ont pas la length
	public static function valuesSliceLength(int $min,?int $max,array $array):array
	{
		$return = [];
		$max = ($max === null)? PHP_INT_MAX:$max;
		
		foreach($array as $k => $v) 
		{
			if(is_string($v))
			{
				$len = Str::len($v,true);
				
				if(Number::in($min,$len,$max))
				$return[$k] = $v;
			}
		}
		
		return $return;
	}
	
	
	// valuesStripLength
	// retourne le tableau sans les entrées string ayant une longueur entre min et max
	// enlève aussi les entrées non string
	// utilise multibyte
	public static function valuesStripLength(int $min,?int $max,array $array):array
	{
		$return = [];
		$max = ($max === null)? PHP_INT_MAX:$max;
		
		foreach ($array as $k => $v) 
		{
			if(is_string($v))
			{
				$len = Str::len($v,true);
				
				if(!Number::in($min,$len,$max))
				$return[$k] = $v;
			}
		}
		
		return $return;
	}
	
	
	// valuesTotalLength
	// retourne le tableau avec les entrées string rentrant dans une longueur totale
	// la première entrée pourrait être truncate si plus courte que length
	// utilise multibyte
	public static function valuesTotalLength(int $length,array $array):array
	{
		$return = [];
		$inLength = 0;
		
		foreach ($array as $k => $v) 
		{
			if(is_string($v))
			{
				$len = Str::len($v,true);
				
				if(empty($inLength) && $len >= $length)
				{
					$return[$k] = Str::sub(0,$length,$v,true);
					$inLength += $len + 1;
				}
				
				elseif(($inLength + $len) <= $length)
				{
					$return[$k] = $v;
					$inLength += $len + 1;
				}
			}
		}
		
		return $return;
	}
	
	
	// valuesWrap
	// permet de wrapper les valeurs scalar dans un delimiteur
	// il y a 7 modes, par défaut 0
	// mb sert seulement si le caractère a wrap est accenté minuscule vs majuscule
	public static function valuesWrap(string $start,?string $end,array $return,int $mode=0,bool $sensitive=true):array
	{
		if($end === null)
		$end = $start;
		
		foreach ($return as $key => $value) 
		{
			if(is_scalar($value))
			{
				$value = (string) $value;
				
				if($mode === 0)
				$value = $start.$value.$end;
				
				elseif($mode === 1)
				$value = Str::wrapStartOrEnd($start,$end,$value,$sensitive);
				
				elseif($mode === 2)
				$value = Str::wrapStartEnd($start,$end,$value,$sensitive);
				
				elseif($mode === 3)
				$value = $start.$value;
				
				elseif($mode === 4)
				$value = Str::wrapStart($start,$value,$sensitive);
				
				elseif($mode === 5)
				$value = $value.$end;
				
				elseif($mode === 6)
				$value = Str::wrapEnd($end,$value,$sensitive);
				
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// valuesUnwrap
	// permet de unwrapper les valeurs string d'un délimiteur
	// il y a 4 modes, par défaut 0
	// mb sert seulement si le caractère a unwrap est accenté minuscule vs majuscule
	public static function valuesUnwrap(string $start,?string $end,array $return,int $mode=0,bool $sensitive=true):array
	{		
		if($end === null)
		$end = $start;
		
		foreach ($return as $key => $value) 
		{
			if(is_string($value))
			{
				if($mode === 0)
				$value = Str::stripStartOrEnd($start,$end,$value,$sensitive);
				
				elseif($mode === 1)
				$value = Str::stripStartEnd($start,$end,$value,$sensitive);
				
				elseif($mode === 2)
				$value = Str::stripStart($start,$value,$sensitive);
				
				elseif($mode === 3)
				$value = Str::stripEnd($end,$value,$sensitive);
				
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// valuesSort
	// sort un tableau par valeur
	// on peut mettre asc, true ou desc, false à sort (sort ou rsort)
	// les valeurs non scalar sont retirés et les clés ne sont pas conservés
	// renvoie à la méthode sort
	public static function valuesSort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
	{
		$return = static::validateSlice('scalar',$return);
		$ascDesc = static::getSortAscDesc($sort);
		
		if($ascDesc === 'asc')
		$sort = 'sort';
		
		elseif($ascDesc === 'desc')
		$sort = 'rsort';
		
		return static::sort($return,$sort,$type);
	}
	
	
	// valuesSortKeepAssoc
	// sort un tableau par valeur
	// on peut mettre asc, true ou desc, false à sort (asort ou arsort)
	// les valeurs non scalar sont retirés et les clés sont conservés
	// renvoie à la méthode sort
	public static function valuesSortKeepAssoc(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
	{
		$return = static::validateSlice('scalar',$return);
		$ascDesc = static::getSortAscDesc($sort);
		
		if($ascDesc === 'asc')
		$sort = 'asort';
		
		elseif($ascDesc === 'desc')
		$sort = 'arsort';
		
		return static::sort($return,$sort,$type);
	}
	
	
	// valuesExcerpt
	// permet de passer toutes les valeurs string du tableau dans la méthode str/excerpt
	// mb est true par défaut
	public static function valuesExcerpt(?int $length,array $array,?array $option=null):array 
	{
		$return = [];
		$option = static::plus(['mb'=>true],$option);
		
		foreach ($array as $key => $value) 
		{
			if(is_scalar($value) && !is_bool($value))
			{
				$value = (string) $value;
				$return[$key] = Str::excerpt($length,$value,$option);
			}
		}
		
		return $return;
	}
	
	
	// keysValuesLower
	// change la case des valeurs et clés string dans le tableau pour lowercase
	// valeur mb seulement pour keysLower, values utilise mb
	public static function keysValuesLower(array $return,?bool $mb=null):array
	{
		$return = static::keysLower($return,$mb);
		$return = static::valuesLower($return);
		
		return $return;
	}
	
	
	// keysValuesUpper
	// change la case des valeurs et clés string dans le tableau pour uppercase
	// valeur mb seulement pour keysUpper, values utilise mb
	public static function keysValuesUpper(array $return,?bool $mb=null):array
	{
		$return = static::keysUpper($return,$mb);
		$return = static::valuesUpper($return);
		
		return $return;
	}
	
	
	// keysStrToArrs
	// permet de reformater un tableau assoc
	// toutes les entrées avec clés string sont transformés en array(key,...value)
	// retourne un tableau multidimensionnel séquentielle
	public static function keysStrToArrs(array $array):array 
	{
		$return = [];
		
		foreach ($array as $key => $value) 
		{
			if(is_string($key))
			$return[] = Arr::append($key,$value);
			
			else
			$return[] = $value;
		}
		
		return $return;
	}
	
	
	// camelCaseParent
	// prend un tableau contenant des string camelCase et identifie le premier parent de chacun
	// retourne un tableau clé->valeur, les clés sont parents ont la valeur null mais sont retournés quand même
	public static function camelCaseParent(array $array):array 
	{
		$return = [];
		$camelCase = [];
		
		foreach ($array as $value) 
		{
			if(is_string($value))
			$camelCase[$value] = Str::fromCamelCase($value);
		}
		
		$copy = $camelCase;
		foreach ($camelCase as $key => $value) 
		{
			$count = count($value);
			
			if($count === 1)
			$return[$key] = null;
			
			else
			{
				$splice = $value;
				while ($splice = static::spliceLast($splice)) 
				{
					foreach ($copy as $k => $v) 
					{
						if($key !== $k && $splice === $v)
						{
							$return[$key] = $k;
							break 2;
						}
					}
				}
				
				if(empty($return[$key]))
				$return[$key] = null;
			}
		}
		
		return $return;
	}
	
	
	// combination
	// retourne toutes les combinaisons possibles des slices d'un tableau
	// retourne un tableau multidimensionnel
	public static function combination(array $array,?bool $sort=true):array
	{
		$return = [[]];

		foreach (array_reverse($array) as $v)
		{
			foreach ($return as $combination)
			{
				$merge = array_merge([$v],$combination);
				
				if(!empty($merge))
				array_push($return,$merge);
			}
		}
		
		unset($return[0]);
		$return = array_values($return);
		
		if($sort !== null)
		$return = Column::sortByLength($return,$sort);
		
		return $return;
	}
	
	
	// methodSort
	// permet de faire un sort su un tableau unidimensionnel contenant des noms de classes ou des objets
	// le type doit être obj ou classe
	public static function methodSort(string $type,string $method,$sort=true,array $return,...$args):array 
	{
		uasort($return, function($a,$b) use ($type,$method,$sort,$args)
		{	
			$return = 0;
			$sort = static::getSortAscDesc($sort);
			
			if($type === 'obj')
			{
				$a = $a->$method(...$args);
				$b = $b->$method(...$args);
			}
			
			else
			{
				$a = $a::$method(...$args);
				$b = $b::$method(...$args);
			}
			
			if($sort === 'asc')
			{
				if($a < $b)
				$return = -1;
				
				elseif($a > $b)
				$return = 1;
			}
			
			elseif($sort === 'desc')
			{
				if($a < $b)
				$return = 1;
				
				elseif($a > $b)
				$return = -1;
			}
			
			return $return;
		});
		
		return $return;
	}
	
	
	// methodSorts
	// permet de faire plusieurs sorts su un tableau unidimensionnel contenant des noms de classes ou des objets
	// le type doit être obj ou classe
	public static function methodSorts(string $type,array $sorts,array $return):array
	{	
		uasort($return, function($first,$second) use ($type,$sorts)
		{	
			$return = 0;
			
			foreach ($sorts as $array) 
			{
				if(is_array($array) && count($array) >= 2)
				{
					if(is_string($array[0]))
					{
						$array = array_values($array);
						$method = $array[0];
						$sort = static::getSortAscDesc($array[1]);
						$args = (array_key_exists(2,$array))? $array[2]:[];
						if(!is_array($args))
						$args = [$args];
						
						if($type === 'obj')
						{
							$a = $first->$method(...$args);
							$b = $second->$method(...$args);
						}
						
						else
						{
							$a = $first::$method(...$args);
							$b = $second::$method(...$args);
						}
						
						// asc
						if($sort === 'asc')
						{
							if($a < $b)
							$return = -1;
							
							elseif($a > $b)
							$return = 1;
						}
						
						// desc
						elseif($sort === 'desc')
						{
							if($a < $b)
							$return = 1;
							
							elseif($a > $b)
							$return = -1;
						}
						
						if($return !== 0)
						break;
					}
				}
			}
			
			return $return;
		});
		
		return $return;
	}
}
?>