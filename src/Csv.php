<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// csv
class Csv extends File
{
	// config
	public static $config = [
		'mimeGroup'=>'csv', // mime groupe de la classe
		'format'=>['delimiter'=>';','enclosure'=>'"','escape'=>'\\'],
		'load'=>'csv', // extension permise pour la méthode csv::load
		'option'=>['csv'=>true], // option pour la classe
		'prefix'=>[ // option csv file::temp
			'extension'=>'csv']
	];


	// getFormat
	// retourne les configuration de format pour csv
	public static function getFormat():array
	{
		return static::$config['format'];
	}


	// same
	// retourne vrai si toutes les colonnes du tableau csv ont le même count et les mêmes clés
	public static function same(array $value):bool
	{
		return Column::same($value);
	}


	// clean
	// efface toutes les colonnes qui n'ont pas la même longueur et les mêmes clés que la première
	// si removeEmpty est true, une colonne dont toutes les valeurs sont vides est éliminé
	public static function clean(array $return,bool $removeEmpty=true):array
	{
		$return = Column::clean($return);

		if($removeEmpty === true && !empty($return))
		{
			foreach ($return as $key => $value)
			{
				$remove = true;

				if(!empty($value) && is_array($value))
				{
					foreach ($value as $k => $v)
					{
						if(!empty($v))
						{
							$remove = false;
							break;
						}
					}
				}

				if($remove === true)
				unset($return[$key]);
			}
		}

		return $return;
	}


	// assoc
	// la première colonne contient les headers
	// le nom des headers est appliqué comme clé à chaque colonne
	public static function assoc(array $array):array
	{
		$return = [];

		if(static::same($array))
		{
			$header = array_shift($array);

			if(!empty($header) && !empty($array))
			{
				foreach ($array as $key => $value)
				{
					foreach (array_values($value) as $k => $v)
					{
						$newKey = Arr::index($k,$header);

						if(Arr::isKey($newKey))
						$return[$key][$newKey] = $v;
					}
				}
			}
		}

		return $return;
	}


	// list
	// inverse de assoc
	// prend un tableau sans headers mais avec clés associatives
	// retourne un tableau avec headers et des colonnes indexés
	public static function list(array $array):array
	{
		$return = [];

		if(static::same($array))
		{
			$return[0] = [];
			$first = current($array);

			foreach ($first as $key => $value)
			{
				$return[0][] = $key;
			}

			foreach ($array as $key => $value)
			{
				$newKey = $key + 1;
				$return[$newKey] = array_values($value);
			}
		}

		return $return;
	}


	// str
	// parse une string ou un tableau de strings csv et retourne un tableau uni ou multi-dimensionnel
	public static function str($value,?array $option=null):?array
	{
		$return = null;
		$option = Arr::plus(static::getFormat(),$option);

		if(is_array($value))
		{
			foreach ($value as $v)
			{
				if(is_string($v) && !empty($v))
				$return[] = str_getcsv($v,$option['delimiter'],$option['enclosure'],$option['escape']);
			}
		}

		elseif(is_string($value) && !empty($value))
		$return = str_getcsv($value,$option['delimiter'],$option['enclosure'],$option['escape']);

		return $return;
	}


	// put
	// parse un tableau uni ou multi-dimensionnel csv et retourne une string
	// utilise une ressource php temp
	public static function put(array $array,?array $option=null):?string
	{
		$return = null;

		if(!empty($array))
		{
			$temp = Res::temp('csv');
			static::resWrite($array,$temp,$option);
			$return = Res::get($temp);
			Res::close($temp);
		}

		return $return;
	}


	// prepareContent
	// méthode utilisé pour préparer le contenu avant écriture dans une resource csv
	// peut retourner un tableau ou null
	public static function prepareContent($value):?array
	{
		$return = null;

		if(is_string($value))
		$return = [[$value]];

		elseif(is_array($value))
		{
			if(Arrs::is($value))
			$return = $value;

			else
			$return = [$value];
		}

		return $return;
	}


	// prepareContentPrepend
	// méthode utilisé pour préparer le contenu à ajouter avant le contenu de la resource
	// peut retourner un tableau ou null
	public static function prepareContentPrepend(array $prepend,$value,?array $option=null):?array
	{
		$return = null;

		if(static::is($value))
		{
			$append = static::getLines($value,true,true,$option);

			if(is_array($append))
			$return = Arr::append($prepend,$append);
		}

		return $return;
	}


	// resLine
	// permet de lire une ligne d'un fichier csv, au pointeur
	public static function resLine($value,?array $option=null):?array
	{
		$return = null;
		$option = Arr::plus(static::getFormat(),$option);

		if(static::isResource($value))
		{
			$return = fgetcsv($value,0,$option['delimiter'],$option['enclosure'],$option['escape']);

			if($return === false)
			$return = null;
		}

		return $return;
	}


	// resWrite
	// écrit dans une ressource fichier csv, content doit être array uni ou multidimensionnel
	// retourne vrai si du contenu a été écrit
	public static function resWrite(array $content,$value,?array $option=null):bool
	{
		$return = false;
		$option = Arr::plus(static::getFormat(),$option);

		if(static::isResource($value) && Res::isWritable($value))
		{
			$put = null;

			if(Arrs::is($content))
			{
				foreach ($content as $w)
				{
					if(is_array($w))
					$put = fputcsv($value,$w,$option['delimiter'],$option['enclosure'],$option['escape']);
				}
			}

			else
			$put = fputcsv($value,$content,$option['delimiter'],$option['enclosure'],$option['escape']);

			if(is_int($put))
			$return = true;
		}

		return $return;
	}
}

// config
Csv::__config();
?>