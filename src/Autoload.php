<?php
declare(strict_types=1);
namespace Quid\Base;

// autoload
class Autoload
{
	// config
	public static $config = [
		'psr4'=>[] // garde une copie du racine de l'auto chargement des classes
	];
	
	
	// construct
	// constructeur protégé
	protected function __construct() { }
	
	
	// call
	// recherche un nom de classe à travers tout le pool autoload
	public static function call(string $class):bool
	{
		$return = false;

		if(!class_exists($class,false))
		{
			spl_autoload_call($class);
			
			if(class_exists($class,false))
			$return = true;
		}
		
		return $return;
	}
	
	
	// getExtensions
	// retourne les extensions utilisés dans l'implémentation par défaut
	public static function getExtensions():string
	{
		return spl_autoload_extensions();
	}
	
	
	// setExtensions
	// change les extensions utilisés dans l'implémentation par défaut
	public static function setExtensions($value):bool
	{
		$return = false;
		
		if($value === true)
		$value = ".".static::phpExtension();
		
		if(is_array($value))
		$value = implode(',',$value);
		
		if(is_string($value))
		{
			$extension = spl_autoload_extensions($value);
			
			if($extension === $value)
			$return = true;
		}
		
		return $return;
	}
	
	
	// register
	// ajoute une fonction dans le pool des autoload
	// si tout est vide, utilise l'implémentation spl_autoload par défaut qui se base sur les include path
	// si call est true, utilise la méthode manual
	// note: l'implémentation spl_autoload par défaut ne déclenche pas le callback __init sur les classes
	public static function register(?callable $call=null,bool $throw=true,bool $prepend=false):bool
	{
		$return = false;
		
		if(!empty($call))
		$return = spl_autoload_register($call,$throw,$prepend);
		
		else
		$return = spl_autoload_register();
		
		return $return;
	}
	
	
	// unregister
	// enlève une fonction du pool autoload
	public static function unregister(callable $call):bool
	{
		return spl_autoload_unregister($call);
	}
	
	
	// unregisterAll
	// enlève toutes les fonctions autoload enregistrés
	public static function unregisterAll():array
	{
		$return = [];
		
		foreach (static::all() as $key => $value) 
		{
			$return[$key] = static::unregister($value);
		}
		
		return $return;
	}
	
	
	// all
	// retourne les fonctions autoload enregistrés
	public static function all():array
	{
		$return = spl_autoload_functions();
		
		if(!is_array($return))
		$return = [];
		
		return $return;
	}
	

	// index
	// permet de retourner une callable autoload, via index
	public static function index(int $index):?callable 
	{
		return Arr::index($index,static::all());
	}
	
	
	// getPath
	// retourne le psr4 ainsi que le path à utiliser pour autoload la classe
	// ceci est utilisé par getFilePath et getDirPath
	// méthode protégé
	protected static function getPath(string $class,bool $different=true):?string 
	{
		$return = null;
		$psr4 = static::getPsr4($class,$different);
		
		if(!empty($psr4))
		{
			$return = current($psr4);
			$key = key($psr4);
			$len = (strlen($key) + 1);
			$after = substr($class,$len);
			
			if(is_string($after) && strlen($after))
			{
				$after = str_replace("\\","/",$after);
				$return .= "/".$after;
			}
		}
		
		return $return;
	}
	
	
	// getFilePath
	// retourne un chemin de classe à partir d'un fqcn
	// possible de spécifier s'il doit exister
	public static function getFilePath(string $class,bool $exists=true,bool $different=true):?string
	{
		$return = static::getPath($class,$different);
		
		if(is_string($return))
		{
			$return .= ".".static::phpExtension();
			
			if($exists === true && !file_exists($return))
			$return = null;
		}
		
		return $return;
	}
	
	
	// getDirPath
	// retourne un chemin de dossier à partir d'un fqcn
	// possible de spécifier s'il doit exister
	public static function getDirPath(string $class,bool $exists=true,bool $different=false):?string
	{
		$return = static::getPath($class,$different);
		
		if(is_string($return) && $exists === true && !is_dir($return))
		$return = null;
		
		return $return;
	}
	
	
	// getPsr4
	// retourne un tableau clé valeur avec le psr4 à utiliser
	// sinon null
	public static function getPsr4(string $class,bool $different=false):?array 
	{
		$return = null;
		$source = static::$config['psr4'];
		
		foreach ($source as $key => $value) 
		{
			if($different === false || $class !== $key)
			{
				if(strpos($class,$key) === 0)
				{
					$return = [$key=>$value];
					break;
				}
			}
		}
		
		return $return;
	}
	
		
	// setPsr4
	// change ou ajoute un point racine
	public static function setPsr4(string $key,string $value):void 
	{
		static::$config['psr4'][$key] = $value;
		
		return;
	}
	
	
	// setsPsr4
	// change ou ajoute plusieurs racine de classe
	public static function setsPsr4(array $keyValue):void 
	{
		foreach ($keyValue as $key => $value) 
		{
			if(is_string($key) && is_string($value))
			static::$config['psr4'][$key] = $value;
		}
		
		return;
	}
	
	
	// unsetPsr4
	// enlève un point racine
	public static function unsetPsr4(string $key):void 
	{
		if(array_key_exists($key,static::$config['psr4']))
		unset(static::$config['psr4'][$key]);
		
		return;
	}
	
	
	// allPsr4
	// retourne le tableau des psr4
	// possible de seulement retourner si le namespace commence par start
	public static function allPsr4(?string $start=null,?string $end=null,bool $endContains=true):array 
	{
		$return = static::$config['psr4'];
		
		if(is_string($start) || is_string($end))
		{
			if(is_string($start))
			{
				foreach ($return as $key => $value) 
				{
					if(stripos($key,$start) !== 0)
					unset($return[$key]);
				}
			}
			
			if(is_string($end))
			{
				foreach ($return as $key => $value) 
				{
					$ipos = stripos(substr($key,-strlen($end)),$end);
					
					if(($endContains === true && $ipos !== 0) || ($endContains === false && $ipos === 0))
					unset($return[$key]);
				}
			}
		}
		
		return $return;
	}
	
	
	// removeAlias
	// retirer les noms de classes qui semblent être des alias
	// les alias sont stockés en lowercase par php
	// la logique quid vaut que la classe ait un namespace et que le nom termine par alias
	public static function removeAlias(array $return):array 
	{
		foreach ($return as $key => $value) 
		{
			if(strtolower($value) === $value && strpos($value,"\\") && substr($value,-5) === 'alias')
			unset($return[$key]);
		}
		
		return $return;
	}
	
	
	// overview
	// génère un tableau multidimensionnel avec le count, size et line pour chaque namespace dans psr4
	// possible de filtrer par début de namespace
	public static function overview(?string $start=null,?string $end=null,bool $endContains=true,bool $sort=true):array 
	{
		$return = [];
		$extension = static::phpExtension();
		
		foreach (static::allPsr4($start,$end,$endContains) as $key => $value) 
		{
			$array = [];
			$array['count'] = Dir::count($value,$extension,true);
			$array['size'] = Dir::size($value,true);
			$array['line'] = Dir::line($value);
			$return[$key] = $array;
		}
		
		if($sort === true)
		ksort($return);
		
		return $return;
	}
	
	
	// phpExtension
	// retourne l'extension de php
	public static function phpExtension():string 
	{
		return 'php';
	}
}
?>