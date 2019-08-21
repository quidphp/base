<?php
declare(strict_types=1);
namespace Quid\Base;

// ip
class Ip extends Root
{
	// config
	public static $config = [
		'allowed'=>[ // option par défaut pour la méthode allowed
			'whiteList'=>null,'blackList'=>null,'range'=>true,'level'=>null],
		'range'=>'*', // caractère pour range
		'reformat'=>[ // défini le nombre de groupe de 256 ips par niveaux
			20=>16,
			21=>8,
			22=>4,
			23=>2,
			24=>1]
	];
	
	
	// is
	// retourne vrai si la valeur est un ip
	public static function is($value):bool
	{
		return (\is_string($value) && Validate::regex('ip',$value))? true:false;
	}
	
	
	// isLocal
	// retourne vrai si l'ip est local
	public static function isLocal($value):bool
	{
		return (static::is($value) && ($value === '127.0.0.1' || \strpos($value,"192.168.") === 0))? true:false;
	}
	
	
	// allowed
	// retourne vrai si le ip passe le test du whitelist et blacklist
	// si option est un tableau indexé, c'est un whitelist
	public static function allowed(string $value,?array $option=null):bool
	{
		$return = false;
		
		if(Arr::isIndexed($option))
		$option = ['whiteList'=>$option];
		
		$option = Arr::plus(static::$config['allowed'],$option);
		
		if(!empty($option['whiteList']) && !\is_array($option['whiteList']))
		$option['whiteList'] = (array) $option['whiteList'];
		
		if(!empty($option['blackList']) && !\is_array($option['blackList']))
		$option['blackList'] = (array) $option['blackList'];
		
		if(static::is($value) && \is_bool($option['range']))
		{
			$return = true;
			
			if(\is_array($option['whiteList']) && !static::in($value,$option['whiteList'],$option['range'],$option['level']))
			$return = false;
			
			elseif(\is_array($option['blackList']) && static::in($value,$option['blackList'],$option['range'],$option['level']))
			$return = false;
		}
		
		return $return;
	}
	
	
	// compareRange
	// retourne vrai si le ip est dans le range
	// le range identifié par le caractère *
	public static function compareRange(string $value,string $range):bool
	{
		$return = false;
		$value = static::explode($value);
		$range = static::explode($range);
		
		if(!empty($value) && !empty($range) && !empty(static::$config['range']))
		{
			$return = true;
			
			foreach ($value as $k => $v) 
			{
				if(!\is_numeric($v) || ($range[$k] !== static::$config['range'] && $range[$k] !== $v))
				{
					$return = false;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// compareLevel
	// compare deux valeurs ip
	// level permet de spécifier jusqu'à quel hauteur ils doivent être identique (1 à 4)
	public static function compareLevel(string $value,string $compare,int $level=2):bool
	{
		$return = false;
		
		if($level >= 1 && $level <= 4)
		{
			$value = static::explode($value);
			$compare = static::explode($compare);
			
			if(!empty($value) && !empty($compare))
			{
				$return = true;
				$i = 0;
				
				while ($i < $level) 
				{
					if(!\is_numeric($value[$i]) || $value[$i] !== $compare[$i])
					{
						$return = false;
						break;
					}
					
					$i++;
				}
			}
		}
		
		return $return;
	}
	
	
	// in
	// retourne vrai si le ip est dans la list
	// un test via compareRange et compareLevel peut être fait
	public static function in(string $value,array $array,bool $range=true,?int $level=null):bool
	{
		$return = false;
		
		if(static::is($value))
		{
			if(\in_array($value,$array,true))
			$return = true;
			
			else
			{
				foreach ($array as $ip) 
				{
					if($range === true && static::compareRange($value,$ip))
					{
						$return = true;
						break;
					}
					
					elseif(\is_int($level) && static::compareLevel($value,$ip,$level))
					{
						$return = true;
						break;
					}
				}
			}
		}
		
		return $return;
	}
	
	
	// reformat
	// permet de reformater un ip dont la dernière partie est de type 0/22 ou 0/24
	// représente des groupes de 256 ips
	public static function reformat(string $value):?array 
	{
		$return = null;
		$explode = static::explode($value);
		
		if(!empty($explode) && \count($explode) === 4)
		{
			$x = \explode('/',$explode[3]);
			$start = $explode[2];
			unset($explode[2]);
			unset($explode[3]);
			$explode = Arr::cast($explode);
			$x = Arr::cast($x);
			
			if(\count($x) === 2 && $x[0] === 0 && \is_int($x[1]) && \array_key_exists($x[1],static::$config['reformat']))
			{
				$return = [];
				$i = 0;
				$group = static::$config['reformat'][$x[1]];
				
				while ($i < $group) 
				{
					$ip = $explode;
					$ip[] = ($start + $i);
					$ip[] = '*';
					$implode = static::implode($ip);
					
					if(!empty($implode))
					$return[] = $implode;
					
					$i++;
				}
			}
		}
		
		return $return;
	}
	
	
	// reformats
	// permet de reformater plusieurs ips dont la dernière partie est de type 0/22 ou 0/24
	// un tableau multidimensionnel est retourné
	public static function reformats(string ...$values):array 
	{
		$return = [];
		
		foreach ($values as $value) 
		{
			$return[$value] = static::reformat($value);
		}
		
		return $return;
	}
	
	
	// reformatsUnique
	// permet de reformater plusieurs ips dont la dernière partie est de type 0/22 ou 0/24
	// un tableau unidimensionnel avec les ips unique sont retournés
	public static function reformatsUnique(string ...$values):array 
	{
		$return = [];
		
		foreach ($values as $value) 
		{
			$reformat = static::reformat($value);
			
			if(!empty($reformat))
			$return = Arr::appendUnique($return,$reformat);
		}
		
		return $return;
	}
	
	
	// toLong
	// convertit une ip en int
	public static function toLong(string $value):?int 
	{
		$return = null;
		
		$long = \ip2long($value);
		if(\is_int($long) && !empty($long))
		$return = $long;
		
		return $return;
	}
	
	
	// fromLong
	// retourne une string ip à partir d'un int long
	public static function fromLong(int $long):?string 
	{
		$return = null;
		
		$ip = \long2ip($long);
		if(\is_string($ip) && !empty($ip))
		$return = $ip;
		
		return $return;
	}
	

	// explode
	// explode un ip et retourne un tableau à 4 clés
	public static function explode(string $value):?array
	{
		$return = null;
		
		$explode = \explode('.',$value);
		if(\count($explode) === 4)
		$return = $explode;
		
		return $return;
	}
	
	
	// implode
	// implode un tableau à 4 clés en un chaîne
	public static function implode(array $value):?string 
	{
		$return = null;
		
		if(\count($value) === 4)
		$return = \implode('.',$value);
		
		return $return;
	}
}
?>