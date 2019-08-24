<?php
declare(strict_types=1);
namespace Quid\Base;

// column
class Column extends Root
{
	// config
	public static $config = [];


	// is
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux
	public static function is($value):bool
	{
		$return = false;

		if(is_array($value) && !empty($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!is_array($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isEmpty
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux vides
	public static function isEmpty($value):bool
	{
		$return = false;

		if(is_array($value) && !empty($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!is_array($v) || !empty($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isNotEmpty
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux non vides
	public static function isNotEmpty($value):bool
	{
		$return = false;

		if(is_array($value) && !empty($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!is_array($v) || empty($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isIndexed
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux vides ou indexés (seulement clé numérique)
	public static function isIndexed($value):bool
	{
		$return = false;

		if(static::is($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!Arr::isIndexed($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isSequential
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux vides ou séquentielles
	public static function isSequential($value):bool
	{
		$return = false;

		if(static::is($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!Arr::isSequential($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isAssoc
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux vides ou associatifs
	public static function isAssoc($value):bool
	{
		$return = false;

		if(static::is($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!Arr::isAssoc($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// isUni
	// retourne vrai si le tableau donnée n'est pas vide et contient seulement des tableaux vides ou unidimensionnel
	public static function isUni($value):bool
	{
		$return = false;

		if(static::is($value))
		{
			$return = true;

			foreach ($value as $v)
			{
				if(!Arr::isUni($v))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// same
	// retourne vrai si les colonnes du tableau multidimensionnel ont le même count et les mêmes clés
	// si le tableau n'a qu'une colonne, retourne vrai
	public static function same($array):bool
	{
		$return = false;

		if(static::is($array))
		$return = (count($array) > 1)? Arr::same(...array_values($array)):true;

		return $return;
	}


	// sameCount
	// retourne vrai si les colonnes du tableau multidimensionnel ont le même count
	// si le tableau n'a qu'une colonne, retourne vrai
	public static function sameCount($array):bool
	{
		$return = false;

		if(static::is($array))
		$return = (count($array) > 1)? Arr::sameCount(...array_values($array)):true;

		return $return;
	}


	// sameKey
	// retourne vrai si les colonnes du tableau multidimensionnel ont les mêmes clés
	// si le tableau n'a qu'une colonne, retourne vrai
	public static function sameKey($array):bool
	{
		$return = false;

		if(static::is($array))
		$return = (count($array) > 1)? Arr::sameKey(...array_values($array)):true;

		return $return;
	}


	// merge
	// merge un tableau dans chaque colonne
	public static function merge(array $return,array $array,bool $recursive=true):array
	{
		foreach ($return as $key => $value)
		{
			if(is_array($value))
			{
				if($recursive === true)
				$return[$key] = Arrs::merge($value,$array);
				else
				$return[$key] = Arr::merge($value,$array);
			}
		}

		return $return;
	}


	// replace
	// replace un tableau dans chaque colonne
	public static function replace(array $return,array $array,bool $recursive=true):array
	{
		foreach ($return as $key => $value)
		{
			if(is_array($value))
			{
				if($recursive === true)
				$return[$key] = Arrs::replace($value,$array);
				else
				$return[$key] = Arr::replace($value,$array);
			}
		}

		return $return;
	}


	// clean
	// enlève toutes les colonnes qui n'ont pas le même count et les mêmes clés que la première colonne
	public static function clean(array $array):array
	{
		$return = [];

		if(static::is($array))
		{
			$return = $array;
			$first = null;

			foreach ($return as $key => $value)
			{
				if($first === null)
				$first = $value;

				elseif(!Arr::same($first,$value))
				unset($return[$key]);
			}
		}

		return $return;
	}


	// count
	// retourne le nombre d'entrée dans chaque colonne du tableau multidimensionnel
	// retoure un tableau avec les différents counts
	public static function count(array $array):array
	{
		$return = [];

		foreach ($array as $key => $value)
		{
			if(is_array($value))
			$return[$key] = count($value);
		}

		return $return;
	}


	// countSame
	// retourne le nombre d'entrée dans une colonne du tableau multidimensionnel
	// retourne null si les colonnes n'ont pas le même count
	public static function countSame(array $array):?int
	{
		$return = null;

		if(static::sameCount($array))
		{
			$current = current($array);

			if(is_array($current))
			$return = count($current);
		}

		return $return;
	}


	// math
	// fait une opération mathématique sur les éléments d'une colonne d'un tableau multidimensionnel
	public static function math(string $col,string $symbol='+',array $array,?int $round=null)
	{
		$return = null;
		$values = static::value($col,$array);

		if(!empty($values))
		$return = Number::math($symbol,$values,$round);

		return $return;
	}


	// validate
	// retourne toutes les colonnes qui contiennent les clés et dont la valeur répond true à validate::is
	// le type de validation is est décrit dans valeur du tableau cols
	public static function validate(array $cols,array $array):array
	{
		$return = [];

		if(static::is($array) && !empty($cols))
		{
			foreach ($array as $key => $value)
			{
				$keep = true;

				foreach ($cols as $k => $v)
				{
					if(!array_key_exists($k,$value) || !Validate::is($v,$value[$k]))
					{
						$keep = false;
						break;
					}
				}

				if($keep === true)
				$return[$key] = $value;
			}
		}

		return $return;
	}


	// in
	// retourne vrai si la valeur est dans le champ spécifié d'une des colonnes
	// support pour recherche insensible à la case
	public static function in(string $col,$value,array $array,bool $sensitive=true):bool
	{
		$return = false;
		$array = static::value($col,$array);

		if(!empty($array) && Arr::in($value,$array,$sensitive))
		$return = true;

		return $return;
	}


	// ins
	// retourne vrai si toutes les valeurs sont dans le champ spécifié d'une des colonnes
	// support pour recherche insensible à la case
	public static function ins(string $col,array $values,array $array,bool $sensitive=true):bool
	{
		$return = false;
		$array = static::value($col,$array);

		if(!empty($array) && Arr::ins($values,$array,$sensitive))
		$return = true;

		return $return;
	}


	// inFirst
	// retourne la première valeur trouvé dans le champ spécifié d'une des colonnes
	// support pour recherche insensible à la case
	public static function inFirst(string $col,array $values,array $array,bool $sensitive=true)
	{
		$return = null;
		$array = static::value($col,$array);

		if(!empty($array))
		$return = Arr::inFirst($values,$array,$sensitive);

		return $return;
	}


	// search
	// retourne toutes les colonnes qui contiennent les clés et valeurs décrient dans l'argument cols
	// support pour recherche insensible à la case
	public static function search(array $cols,array $array,bool $sensitive=true):array
	{
		$return = [];

		if(static::is($array) && !empty($cols))
		{
			foreach ($array as $key => $value)
			{
				$keep = true;

				foreach ($cols as $k => $v)
				{
					if(!array_key_exists($k,$value))
					$keep = false;

					elseif($sensitive === true && $value[$k] !== $v)
					$keep = false;

					elseif($sensitive === false && !Str::icompare($value[$k],$v))
					$keep = false;
				}

				if($keep === true)
				$return[$key] = $value;

				else
				break;
			}
		}

		return $return;
	}


	// searchFirst
	// retourne la première colonne qui contiennent les clés et valeurs décrient dans l'argument cols
	// support pour recherche insensible à la case
	public static function searchFirst(array $cols,array $array,bool $sensitive=true):array
	{
		$return = [];

		if(static::is($array) && !empty($cols))
		{
			foreach ($array as $key => $value)
			{
				$keep = true;

				foreach ($cols as $k => $v)
				{
					if(!array_key_exists($k,$value))
					$keep = false;

					elseif($sensitive === true && $value[$k] !== $v)
					$keep = false;

					elseif($sensitive === false && !Str::icompare($value[$k],$v))
					$keep = false;
				}

				if($keep === true)
				{
					$return = $value;
					break;
				}
			}
		}

		return $return;
	}


	// unique
	// retourne les colonnes avec valeurs uniques sur la ou les colonnes indiqués dans l'argument col
	// support pour recherche insensible à la case
	public static function unique($value,array $array,bool $removeOriginal=false,bool $sensitive=true):array
	{
		$return = [];
		$cols = [];
		$search = [];
		$value = (array) $value;

		foreach ($value as $key => $col)
		{
			if(Arr::isKey($col))
			$cols[$key] = $col;
		}

		if(static::is($array) && !empty($cols))
		{
			foreach ($array as $key => $value)
			{
				foreach ($cols as $col)
				{
					$search[$key][$col] = null;

					if(array_key_exists($col,$value))
					$search[$key][$col] = $value[$col];
				}
			}

			if(!empty($search))
			{
				foreach (Arr::unique($search,$removeOriginal,$sensitive) as $key => $value)
				{
					$return[$key] = $array[$key];
				}
			}
		}

		return $return;
	}


	// duplicate
	// retourne les valeurs dupliqués, l'inverse de column::unique
	// support pour recherche insensible à la case
	public static function duplicate($col,array $array,bool $keepOriginal=false,bool $sensitive=true):array
	{
		$return = [];

		$unique = static::unique($col,$array,$keepOriginal,$sensitive);
		if(!empty($unique))
		$return = Arr::diffKey($array,$unique);

		return $return;
	}


	// sort
	// sort un tableau multidimensionnel par une colonne
	// direction asc ou desc
	public static function sort($col,$sort=true,array $return):array
	{
		if(static::keyExists($col,$return))
		{
			uasort($return, function(array $first,array $second) use ($col,$sort)
			{
				$return = 0;
				$sort = Arr::getSortAscDesc($sort);
				$a = $first[$col];
				$b = $second[$col];

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
		}

		return $return;
	}


	// sorts
	// sort un tableau multidimensionnel par plusieurs colonnes
	// direction asc ou desc
	// le sort conserve l'ordre naturel du tableau si les valeurs sont égales dans la comparaison et si un seul niveau de sort est envoyé
	public static function sorts(array $array,array $return):array
	{
		if(static::is($return) && !empty($array))
		{
			uasort($return, function(array $first,array $second) use ($array)
			{
				$return = 0;

				foreach ($array as $col => $sort)
				{
					if(array_key_exists($col,$first) && array_key_exists($col,$second))
					{
						$sort = Arr::getSortAscDesc($sort);
						$a = $first[$col];
						$b = $second[$col];

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

				return $return;
			});
		}

		return $return;
	}


	// sortByLength
	// permet de sort les colonnes selon leur longueur
	public static function sortByLength(array $return,$sort=true)
	{
		$ascDesc = Arr::getSortAscDesc($sort);

		if($ascDesc === 'asc')
		$sort = SORT_ASC;

		elseif($ascDesc === 'desc')
		$sort = SORT_DESC;

		$counts = array_map('count',$return);

		array_multisort($counts,$sort,$return);

		return $return;
	}


	// map
	// array_map pour un tableau multidimensionnel
	// col spécifie quelle colonne est affecté
	// si la colonne existe, le résultat de la fonction est inscrite dans le tableau de retour
	// si callable est closure, à ce moment au moins trois arguments sont envoyés à la fonction = value, col et array
	public static function map($col,callable $callable,array $return,...$args):array
	{
		if(static::is($return) && Arr::isKey($col))
		{
			foreach ($return as $key => $value)
			{
				if(array_key_exists($col,$value))
				{
					$v = $value[$col];

					if($callable instanceof \Closure)
					$return[$key][$col] = $callable($v,$col,$value,...$args);

					else
					$return[$key][$col] = $callable($v,...$args);
				}
			}
		}

		return $return;
	}


	// filter
	// array_filter pour un tableau multidimensionnel
	// col spécifie quelle colonne est affecté
	// si la callable retourne false, ou si col n'existe pas, toute la colonne est enlevé
	// si callable est closure, à ce moment trois arguments sont envoyés à la fonction = value, col et array
	public static function filter($col,callable $callable,array $return,int $flag=0):array
	{
		if(static::is($return) && Arr::isKey($col))
		{
			foreach ($return as $key => $value)
			{
				$result = false;

				if(array_key_exists($col,$value))
				{
					$v = $value[$col];

					if($callable instanceof \Closure)
					$result = $callable($v,$col,$value);

					else
					{
						if($flag === ARRAY_FILTER_USE_BOTH)
						$result = $callable($v,$col);
						elseif($flag === ARRAY_FILTER_USE_KEY)
						$result = $callable($col);
						else
						$result = $callable($v);
					}
				}

				if(!$result)
				unset($return[$key]);
			}
		}

		return $return;
	}


	// keyValue
	// wrapper pour array_column
	// forme un tableau à partir de deux clés d'une colonne
	public static function keyValue($key,$value,array $array):array
	{
		$return = [];

		if((Arr::isKey($key) || $key === null) && Arr::isKey($value))
		$return = array_column($array,$value,$key);

		return $return;
	}


	// keyValueIndex
	// wrapper pour array_column
	// forme un tableau à partir de deux index d'une colonne
	// fonctionne seulement si les tableaux ont le même count
	public static function keyValueIndex(?int $key,int $value,array $array):array
	{
		$return = [];
		$count = static::countSame($array);

		if(is_int($count))
		{
			if(is_int($key))
			$key = Arr::indexPrepare($key,$count);

			$value = Arr::indexPrepare($value,$count);
			$array = Arrs::values($array);

			if(is_int($value))
			$return = array_column($array,$value,$key);
		}

		return $return;
	}


	// arrSegment
	// va chercher les valeurs et les passent dans segment::replace, permet de mettre plusieurs valeurs dans le tableau
	// la clé doit exister pour que la ligne soit crée
	public static function arrSegment($key,string $value,array $array,$delimiter=null):array
	{
		$return = [];

		if(static::is($array) && Arr::isKey($key))
		{
			foreach ($array as $k => $v)
			{
				if(array_key_exists($key,$v) && Arr::isKey($value))
				{
					if(array_key_exists($value,$v))
					$return[$v[$key]] = $v[$value];
					else
					$return[$v[$key]] = Segment::sets($delimiter,$v,$value);
				}
			}
		}
		return $return;
	}


	// splice
	// efface et remplace des slices de chaque colonne d'un array en utilisant start et mixed
	// start représente la clé de départ
	// end est la clé de fin, si null représente 0 et si bool représente 1
	// à la différence de array_splice, les clés numérique ne sont pas réordonnées
	// important: les clés numériques existantes sont append, les clés string sont remplacés
	// support pour splice et remplacement insensible à la case
	public static function splice($start,$end,array $return,?array $replace=null,bool $sensitive=true):array
	{
		if(static::is($return))
		{
			foreach ($return as $key => $value)
			{
				$return[$key] = Arr::splice($start,$end,$value,$replace,$sensitive);
			}
		}

		return $return;
	}


	// spliceIndex
	// efface et remplace des slices de chaque colonne en utilisant offset et length
	// à la différence de array_splice, les clés numérique ne sont pas réordonnées
	// important: les clés numériques existantes sont append, les clés string sont remplacés
	// support pour remplacement insensible à la case
	public static function spliceIndex(int $offset,?int $length,array $return,?array $replace=null,bool $sensitive=true):array
	{
		if(static::is($return))
		{
			foreach ($return as $key => $value)
			{
				$return[$key] = Arr::spliceIndex($offset,$length,$value,$replace,$sensitive);
			}
		}

		return $return;
	}


	// spliceFirst
	// retourne le tableau sans la première slice de toutes les colonnes
	// possibilité d'inséré du contenu au début via le tableau replace
	// support pour remplacement insensible à la case
	public static function spliceFirst(array $array,?array $replace=null,bool $sensitive=true):array
	{
		return static::spliceIndex(0,1,$array,$replace,$sensitive);
	}


	// spliceLast
	// retourne le tableau sans la dernière slice de toutes les colonnes
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
	public static function insertIndex($offset,array $replace,array $array,bool $sensitive=true):array
	{
		return static::spliceIndex($offset,0,$array,$replace,$sensitive);
	}


	// keyExists
	// retourne vrai si toutes les colonnes ont la clé donné
	public static function keyExists($key,array $array):bool
	{
		$return = false;

		if(static::is($array))
		{
			$return = true;

			foreach ($array as $value)
			{
				if(!Arr::keyExists($key,$value))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// keysExists
	// retourne vrai si toutes les colonnes ont les clés donnés
	public static function keysExists(array $keys,array $array):bool
	{
		$return = false;

		if(static::is($array))
		{
			$return = true;

			foreach ($array as $value)
			{
				if(!Arr::keysExists($keys,$value))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// keysAre
	// retourne vrai si toutes les colonnes ont seulement les clés donnés
	public static function keysAre(array $keys,array $array):bool
	{
		$return = false;

		if(static::is($array))
		{
			$return = true;

			foreach ($array as $value)
			{
				if(!Arr::keysAre($keys,$value))
				{
					$return = false;
					break;
				}
			}
		}

		return $return;
	}


	// keyTo
	// ajoute la valeur de la clé dans chaque colonne du tableau multi
	public static function keyTo($key,array $return):array
	{
		if(Arr::isKey($key))
		{
			foreach ($return as $k => $v)
			{
				if(is_array($v))
				$return[$k][$key] = $k;
			}
		}

		return $return;
	}


	// keyFrom
	// remplace la clé par la valeur d'une colonne
	public static function keyFrom($key,array $array):array
	{
		$return = [];

		if(Arr::isKey($key) && static::is($array))
		{
			foreach ($array as $k => $v)
			{
				if(array_key_exists($key,$v) && Arr::isKey($v[$key]))
				$return[$v[$key]] = $v;
			}
		}

		return $return;
	}


	// keyFromIndex
	// remplace la clé par la valeur d'un index de colonne
	public static function keyFromIndex(int $index,array $array):array
	{
		$return = [];

		if(static::is($array))
		{
			$col = Arr::index(0,$array);

			if(is_array($col))
			{
				$key = Arr::indexKey($index,$col);

				if(Arr::isKey($key))
				$return = static::keyFrom($key,$array);
			}
		}

		return $return;
	}


	// keySwap
	// invertir les deux premiers niveaux d'un tableau multi
	// pratique pour reformater $_FILES ou un tableau post multidimensionnel
	public static function keySwap(array $array):array
	{
		$return = [];

		if(static::is($array))
		{
			foreach ($array as $key => $value)
			{
				if(!empty($value))
				{
					foreach (array_values($value) as $i => $v)
					{
						$return[$i][$key] = $v;
					}
				}
			}
		}

		return $return;
	}


	// value
	// wrapper pour array_column
	// retourne toutes les valeurs d'une colonne à partir d'une clé
	public static function value($value,array $array):array
	{
		$return = [];

		if(Arr::isKey($value))
		$return = array_column($array,$value);

		return $return;
	}


	// valueIndex
	// wrapper pour array_column
	// retourne toutes les valeurs d'une colonne à partir d'un index
	// fonctionne seulement si les tableaux ont le même count
	public static function valueIndex(int $value,array $array):array
	{
		$return = [];
		$count = static::countSame($array);

		if(is_int($count))
		{
			$value = Arr::indexPrepare($value,$count);
			$array = Arrs::values($array);

			if(is_int($value))
			$return = array_column($array,$value);
		}

		return $return;
	}


	// valueSegment
	// va chercher les valeurs et les passent dans segment::set
	// permet de mettre plusieurs valeurs dans le tableau de retour
	public static function valueSegment(string $value,array $array,$delimiter=null):array
	{
		$return = [];

		if(static::is($array) && Arr::isKey($value))
		{
			foreach ($array as $k => $v)
			{
				if(array_key_exists($value,$v))
				$return[] = $v[$value];
				else
				$return[] = Segment::sets($delimiter,$v,$value);
			}
		}

		return $return;
	}
}
?>