<?php
declare(strict_types=1);
namespace Quid\Base;

// slug
class Slug extends Set
{
	// config
	public static $config = array(
		'option'=>array( // tableau d'options
			'caseImplode'=>'lower', // les valeurs sont ramenés dans cette case lors du implode
			'replaceAccent'=>true, // replace les accents par les caractères non accentés
			'prepend'=>null, // prepend une valeur au slug
			'append'=>null, // append une valeur au slug
			'sliceLength'=>array(2,20), // garde seulement les slugs entre une certaine longueur
			'keepLast'=>true, // garde le dernier slice peu importe la longueur
			'keepNumeric'=>true, // garde toutes les slices numériques
			'totalLength'=>null), // longueur total du slug admise
		'separator'=>array('-','-') // séparateur pour les slug
	);
	
	
	// is
	// retourne vrai si la valeur est un slug
	public static function is($value):bool
	{
		return (is_string($value) && Validate::regex('alphanumericSlug',$value))? true:false;
	}
	
	
	// keepAlphanumeric
	// enleve tous les caractères non alphanumérique et garde - et _
	// keep permet de garder des caractères supplémentaires
	public static function keepAlphanumeric(string $value,string $keep=''):string
	{
		return preg_replace("/[^A-Za-z0-9_\-$keep]/", '', $value);	
	}
	
	
	// parse
	// parse le tableau arr de slug
	public static function parse(array $array,array $option):array 
	{
		$return = array();
		$separator = static::getSeparator(1);
		$segment = Segment::getDelimiter(null,true);
		
		if(is_string($option['prepend']))
		{
			$prepend = static::parseValue($option['prepend'],$option['replaceAccent']);
			$return[] = $prepend;
		}
		
		foreach ($array as $key => $value) 
		{
			$value = preg_split('~[^\\pL\d'.$segment[0].$segment[1].']+~u',$value,-1,PREG_SPLIT_NO_EMPTY);
			
			if(!empty($value))
			{
				foreach ($value as $v) 
				{
					if(is_string($v))
					{
						$v = static::parseValue($v,$option['replaceAccent']);
						
						if(!empty($v))
						$return[] = $v;
					}
				}
			}
		}

		if(is_string($option['append']))
		{
			$append = static::parseValue($option['append'],$option['replaceAccent']);
			$return[] = $append;
		}
		
		if(is_array($option['sliceLength']) && count($option['sliceLength']) === 2)
		{
			$keep = $return;
			
			$option['sliceLength'] = array_values($option['sliceLength']);
			$return = Arr::valuesSliceLength($option['sliceLength'][0],$option['sliceLength'][1],$return);
			
			if($option['keepNumeric'] === true)
			{
				foreach ($keep as $k => $v) 
				{
					if(!array_key_exists($k,$return) && is_numeric($v))
					$return[$k] = $v;
				}
				
				ksort($return);
			}
			
			if($option['keepLast'] === true)
			{
				if(Arr::keyLast($keep) !== Arr::keyLast($return))
				$return[] = Arr::valueLast($keep);
			}
		}
		
		if(is_numeric($option['totalLength']) && !empty($option['totalLength']))
		{
			$reverse = Arr::valuesTotalLength($option['totalLength'],array_reverse($return));
			$return = array_reverse($reverse);
		}
		
		return $return;
	}
	
	
	// parseValue
	// parse une valeur string déjà explosé
	public static function parseValue(string $return,?bool $replaceAccent=null):string
	{
		$segment = Segment::getDelimiter(null,true);
		
		if($replaceAccent === true)
		$return = Str::replaceAccent($return);
		
		$return = static::keepAlphanumeric($return,$segment[0].$segment[1]);
		
		return $return;
	}
}

// config
Slug::__config();
?>