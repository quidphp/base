<?php
declare(strict_types=1);
namespace Quid\Base;

// listing
class Listing extends Assoc
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'implode'=>0, // index du séparateur à utiliser lors du implode
			'explode'=>0, // index du séparateur à utiliser lors du explode
			'case'=>null, // les clés sont ramenés dans cette case lors du explode
			'caseImplode'=>null, // les clés sont ramenés dans cette case lors du implode
			'limit'=>null, // limit par défaut lors du explode
			'trim'=>true, // chaque partie de listing est trim
			'clean'=>true, // une partie listing vide est retiré
			'start'=>false, // ajoute le premier séparateur au début lors du implode
			'end'=>false,  // ajoute le premier séparateur à la fin lors du implode
			'sort'=>null], // les clés sont sort
		'separator'=>[ // les séparateurs de listing, le deuxième index est la version avec espace
			["\n","\n"],
			[':',': ']]
	];


	// isSeparatorStart
	// retourne vrai si le listing a un separator au début
	public static function isSeparatorStart(string $listing):bool
	{
		return Str::isStart(static::getSeparator(),$listing,static::getSensitive());
	}


	// isSeparatorEnd
	// retourne vrai si le listing a un separator à la fin et n'est pas seulement le séparateur
	public static function isSeparatorEnd(string $listing):bool
	{
		return ($listing !== ($separator = static::getSeparator()) && Str::isEnd($separator,$listing,static::getSensitive()))? true:false;
	}


	// hasSeparatorDouble
	// retourne vrai si le listing contient un double séparateur
	public static function hasSeparatorDouble(string $listing):bool
	{
		return (!empty($separator = static::getSeparator()) && Str::posIpos($separator.$separator,$listing,static::getSensitive()) !== null)? true:false;
	}


	// getSeparator
	// retourne un des séparateurs via index
	// possiblité de retourner la version avec espace via index2
	public static function getSeparator(int $index=0,int $index2=0):?string
	{
		$return = null;

		if(array_key_exists($index,static::$config['separator']) && is_array(static::$config['separator'][$index]))
		{
			if(array_key_exists($index2,static::$config['separator'][$index]) && is_string(static::$config['separator'][$index][$index2]))
			$return = static::$config['separator'][$index][$index2];
		}

		return $return;
	}


	// str
	// explose et implose une valeur
	// retourne une string correctement formattée
	public static function str($value,?array $option=null):string
	{
		return static::implode(static::arr($value,$option),$option);
	}


	// list
	// les options de list peuvent être null
	// explose et implose une valeur
	// retourne un tableau unidimensionnel avec clé numérique
	public static function list($array,?array $option=null):array
	{
		$return = [];
		$option = static::option($option);
		$separator = static::getSeparator(1,$option['implode']);
		$array = static::arr($array,$option);

		if(is_array($array) && !empty($array))
		{
			foreach ($array as $key => $value)
			{
				if(is_string($key) && (is_scalar($value) || is_array($value)))
				{
					$value = (array) $value;

					foreach ($value as $v)
					{
						if(is_scalar($v))
						{
							$v = Str::cast($v);
							$return[] = implode($separator,[$key,$v]);
						}
					}
				}
			}
		}

		return $return;
	}


	// parse
	// parse un tableau arr
	// pas utilisé dans listing
	public static function parse(array $return,array $option):array
	{
		return $return;
	}


	// arr
	// explose une string listing
	// de même si listing est déjà un array, retourne le après parse
	public static function arr($value,?array $option=null):array
	{
		$return = [];
		$option = static::option($option);
		$value = Obj::cast($value);

		if(is_scalar($value))
		{
			$value = Str::cast($value);
			$value = static::prepareStr($value,$option);
		}

		if(is_array($value))
		$value = static::prepareArr($value,$option);

		if(is_array($value) && !empty($value))
		{
			$value = Arr::trimClean($value,$option['trim'],$option['trim'],$option['clean']);

			if($option['case'] !== null)
			$value = Arr::keysChangeCase($option['case'],$value);

			$return = static::parse($value,$option);

			if($option['sort'] !== null)
			$return = Arr::keysSort($return,$option['sort']);
		}

		return $return;
	}


	// prepareStr
	// prépare une string dans la méthode arr
	public static function prepareStr(string $value,array $option):array
	{
		$return = [];
		$separator = static::getSeparator(0,$option['explode']);
		$return = Str::explode($separator,$value,$option['limit']);

		return $return;
	}


	// prepareArr
	// prépare un array dans la méthode arr
	public static function prepareArr(array $value,array $option):array
	{
		$return = [];

		if(Arr::isIndexed($value))
		{
			$separator = static::getSeparator(1,$option['explode']);
			$return = Arr::explodekeyValue($separator,$value);
		}
		else
		$return = $value;

		return $return;
	}


	// keyValue
	// retourne la version unidimensionnel du tableau explode
	// traite aussi les demandes de caseImplode
	public static function keyValue(array $array,array $option):array
	{
		$return = [];

		foreach ($array as $key => $value)
		{
			if(is_scalar($value))
			$return[$key] = Str::cast($value);
		}

		if($option['caseImplode'] !== null)
		$return = Arr::keysChangeCase($option['caseImplode'],$return);

		return $return;
	}


	// implode
	// implose un tableau qui a été passé dans arr
	// fonctionne aussi avec les tableaux list
	public static function implode(array $value,?array $option=null):string
	{
		$return = '';
		$option = static::option($option);
		$separator = static::getSeparator(0,$option['implode']);

		if(Arr::isIndexed($value))
		$return = implode($separator,$value);

		else
		{
			$value = static::keyValue($value,$option);

			if(Arr::isIndexed($value))
			$return = implode($separator,$value);
			else
			$return = Arr::implodeKey($separator,static::getSeparator(1,$option['implode']),$value);
		}

		$return = static::stripWrap($return,$option['start'],$option['end']);

		return $return;
	}


	// stripWrap
	// ajoute ou enlève le séparateur en début ou fin de chaîne
	public static function stripWrap(string $listing,?bool $start=null,?bool $end=null):string
	{
		return Str::stripWrap(static::getSeparator(),$listing,$start,$end,static::getSensitive());
	}


	// stripStart
	// retourne le listing sans le séparateur du début
	public static function stripStart(string $listing):string
	{
		return Str::stripStart(static::getSeparator(),$listing,static::getSensitive());
	}


	// stripEnd
	// retourne le listing sans le séparateur de la fin
	public static function stripEnd(string $listing):string
	{
		return Str::stripEnd(static::getSeparator(),$listing,static::getSensitive());
	}


	// wrapStart
	// wrap un listing au début s'il ne l'est pas déjà
	public static function wrapStart(string $listing):string
	{
		return Str::wrapStart(static::getSeparator(),$listing,static::getSensitive());
	}


	// wrapEnd
	// wrap un listing à la fin s'il ne l'est pas déjà
	public static function wrapEnd(string $listing):string
	{
		return Str::wrapEnd(static::getSeparator(),$listing,static::getSensitive());
	}
}

// config
Listing::__config();
?>