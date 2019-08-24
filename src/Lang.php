<?php
declare(strict_types=1);
namespace Quid\Base;

// lang
class Lang extends Root
{
	// config
	public static $config = [
		'default'=>'en', // langue par défaut à appliquer au chargement de la classe
		'field'=>'_', // délimiteur pour les méthodes field
	];
	
	
	// static
	protected static $current = null; // langue courante
	protected static $all = []; // toute les langues, la première est la langue par défaut
	protected static $callable = null; // callable d'un objet lang
	
	
	// is
	// retourne vrai si la langue est valide
	public static function is($value):bool
	{
		$return = false;
		$value = static::prepareCode($value);
		
		if(!empty($value) && in_array($value,static::all(),true))
		$return = true;
		
		return $return;
	}
	
	
	// isCurrent
	// retourne vrai si la langue courante est la valeur
	public static function isCurrent($value):bool
	{
		return (static::prepareCode($value) === static::$current)? true:false;
	}
	
	
	// isOther
	// retourne vrai si la langue est valide, et n'est pas la langue courante
	public static function isOther($value):bool
	{
		return (static::is($value) && !static::isCurrent($value))? true:false;
	}
	
	
	// isCallable
	// retourne vrai si la callable est celle fourni
	public static function isCallable($value):bool
	{
		return (static::classIsCallable($value) && static::$callable === $value)? true:false;
	}
	
	
	// hasCallable
	// retourne vrai s'il y a une callable lang
	public static function hasCallable():bool
	{
		return (static::classIsCallable(static::$callable))? true:false;
	}
	
	
	// current
	// retourne la langue courante
	public static function current():string
	{
		return static::$current;
	}
	
	
	// default
	// retourne la langue par défaut, la première déclaré dans le tableau all
	public static function default():?string
	{
		return (!empty(static::$all))? current(static::$all):null;
	}
	
	
	// defaultConfig
	// retourne la langue par defaut de config
	public static function defaultConfig():string
	{
		return static::$config['default'];
	}
	
	
	// other
	// retourne une langue autre par index ou string
	// ne peut pas retourner la courante
	public static function other($arg=0,?string $value=null):?string
	{
		$return = null;
		$others = static::others($value);
		$arg = ($arg === true)? 0:$arg;
		
		if(is_int($arg) && array_key_exists($arg,$others))
		$return = $others[$arg];
		
		elseif(is_string($arg) && in_array($arg,$others,true))
		$return = $arg;
		
		return $return;
	}
	
	
	// others
	// retourne un tableau avec toutes les autres langues
	public static function others(?string $value=null):array
	{
		$return = [];
		$value = static::code($value);
		$return = Arr::valueStrip($value,static::all());
		$return = array_values($return);
		
		return $return;
	}
	
	
	// all
	// retourne toutes les langues
	public static function all():array
	{
		return static::$all;
	}
	
	
	// count
	// count le nombre de langues déclarés
	public static function count():int
	{
		return count(static::all());
	}
	
	
	// code
	// retourne le code formatté ou la langue courante si le code formatté est invalide
	public static function code(?string $value=null):string
	{
		$return = static::prepareCode($value);
		
		if(!is_string($return))
		$return = static::current();
		
		return $return;
	}
	
	
	// prepareCode
	// retourne le code de langue formatté ou null
	// doit être une string avec deux caractères
	public static function prepareCode(?string $value):?string
	{
		return (is_string($value) && strlen($value) === 2)? $value:null;
	}
	
	
	// set
	// permet d'ajouter les langues et de changer la langue courante
	// vide le tableau des langues courantes avant de faire l'ajout et changement
	public static function set(?string $value,$all):bool
	{
		$return = false;
		$current = static::prepareCode($value);
		
		if(is_string($all))
		$all = [$all];
		
		if(is_array($all) && !empty($all) && ($value === null || in_array($value,$all,true)))
		{
			$return = true;
			static::$all = [];
			static::add(...array_values($all));
			
			if($value === null)
			$value = static::default();
			
			static::change($value);
		}
		
		return $return;
	}
	
	
	// onChange
	// callback après un ajout, rettait ou changement de langue
	protected static function onChange():void
	{
		if(is_string(static::$current))
		{
			$current = static::current();
			Request::setLangs(static::all());
			Finder::setShortcut('lang',$current);
			Sql::setShortcut('lang',$current);
			Uri::setShortcut('lang',$current);
		}
		
		return;
	}
	
	
	// add
	// ajoute une ou plusieurs langues
	public static function add(string ...$values):array
	{
		$return = [];
		$change = false;
		
		foreach ($values as $value)
		{
			$value = static::prepareCode($value);
			
			if(is_string($value))
			{
				$return[$value] = false;
				
				if(!static::is($value))
				{
					$return[$value] = true;
					static::$all[] = $value;
					static::$all = array_values(static::$all);
					$change = true;
				}
			}
		}
		
		if($change === true)
		static::onChange();
		
		return $return;
	}
	
	
	// remove
	// enlève une ou plusieurs langues
	// la langue doit exister et ne pas être la courante
	public static function remove(string ...$values):array
	{
		$return = [];
		$change = false;
		
		foreach ($values as $value)
		{
			$value = static::prepareCode($value);
			
			if(is_string($value))
			{
				$return[$value] = false;
				
				if(static::is($value) && !static::isCurrent($value))
				{
					$return[$value] = true;
					static::$all = array_values(Arr::valueStrip($value,static::$all));
					$change = true;
				}
			}
		}
		
		if($change === true)
		static::onChange();
		
		return $return;
	}
	
	
	// change
	// change la langue courante si la nouvelle lang existe
	// callback déclenché s'il y a un changement
	public static function change(string $value):bool
	{
		$return = false;
		$value = static::prepareCode($value);
		$current = static::$current;
		
		if(static::is($value))
		{
			$return = true;
			
			if($value !== $current)
			{
				static::$current = $value;
				static::onChange();
			}
		}
		
		return $return;
	}

	
	// getCallable
	// retourne la callable lang liée
	public static function getCallable():?callable
	{
		return static::$callable;
	}
	
	
	// setCallable
	// lie une callable lang à la classe
	public static function setCallable(callable $callable):void
	{
		static::$callable = $callable;
		
		return;
	}
	
	
	// unsetCallable
	// délie la callable de la classe
	public static function unsetCallable():void
	{
		static::$callable = null;
		
		return;
	}
	
	
	// call
	// utilise la callable lié pour faire une requête de contenu langue
	// aucun envoie d'erreur si contenu inexistant
	public static function call(string $value,...$args)
	{
		$return = null;
		$callable = static::getCallable();
		
		if(!empty($callable))
		$return = $callable($value,...$args);
		
		return $return;
	}
	
	
	// numberFormat
	// retourne le tableau format numérique de la langue, si callable lié
	public static function numberFormat(...$args)
	{
		return static::call('numberFormat',...$args);
	}
	
	
	// numberPercentFormat
	// retourne le tableau format numérique en pourcentage de la langue, si callable lié
	public static function numberPercentFormat(...$args)
	{
		return static::call('numberPercentFormat',...$args);
	}
	
	
	// numberMoneyFormat
	// retourne le tableau format monétaire de la langue, si callable lié
	public static function numberMoneyFormat(...$args)
	{
		return static::call('numberMoneyFormat',...$args);
	}
	
	
	// numberPhoneFormat
	// retourne le tableau format de size de phone, si callable lié
	public static function numberPhoneFormat(...$args)
	{
		return static::call('numberPhoneFormat',...$args);
	}
	
	
	// numberSizeFormat
	// retourne le tableau format de size de la langue, si callable lié
	public static function numberSizeFormat(...$args)
	{
		return static::call('numberSizeFormat',...$args);
	}
	
	
	// dateMonth
	// retourne le tableau des mois, si callable lié
	public static function dateMonth(...$args)
	{
		return static::call('dateMonth',...$args);
	}
	
	
	// dateFormat
	// retourne le tableau des formats de date, si callable lié
	public static function dateFormat(...$args)
	{
		return static::call('dateFormat',...$args);
	}
	
	
	// dateStr
	// retourne le tableau pour date str, si callable lié
	public static function dateStr(...$args)
	{
		return static::call('dateStr',...$args);
	}
	
	
	// datePlaceholder
	// retourne le tableau pour date placeholder, si callable lié
	public static function datePlaceholder(...$args)
	{
		return static::call('datePlaceholder',...$args);
	}
	
	
	// dateDay
	// retourne le tableau pour date day, si callable lié
	public static function dateDay(...$args)
	{
		return static::call('dateDay',...$args);
	}
	
	
	// dateDayShort
	// retourne le tableau pour date dayShort, si callable lié
	public static function dateDayShort(...$args)
	{
		return static::call('dateDayShort',...$args);
	}
	
	
	// headerResponseStatus
	// retourne le tableau headerResponseStatus, si callable lié
	public static function headerResponseStatus(...$args)
	{
		return static::call('headerResponseStatus',...$args);
	}
	
	
	// errorCode
	// retourne le tableau pour error codes, si callable lié
	public static function errorCode(...$args)
	{
		return static::call('errorCode',...$args);
	}
	
	
	// validate
	// retourne le tableau pour validate, si callable lié
	public static function validate(...$args)
	{
		return static::call('validate',...$args);
	}
	
	
	// compare
	// retourne le tableau pour compare, si callable lié
	public static function compare(...$args)
	{
		return static::call('compare',...$args);
	}
	
	
	// required
	// retourne le tableau pour required, si callable lié
	public static function required(...$args)
	{
		return static::call('required',...$args);
	}
	
	
	// unique
	// retourne le tableau pour unique, si callable lié
	public static function unique(...$args)
	{
		return static::call('unique',...$args);
	}
	
	
	// editable
	// retourne le tableau pour editable, si callable lié
	public static function editable(...$args)
	{
		return static::call('editable',...$args);
	}
	
	
	// content
	// retourne le contenu en vue d'un overwrite ou replace
	// si le tableau est unidimensionnel, il est passé dans arrs::sets avant le retour
	public static function content($value)
	{
		$return = null;
		
		if(is_array($value))
		$return = $value;
		
		elseif(is_string($value))
		$return = File::load($value);
		
		if(Arr::isUni($return))
		$return = Arrs::sets($return,[]);
		
		return $return;
	}
	
	
	// field
	// retourne une valeur dans le format: valeur delimiteur lang
	public static function field(string $value,?string $lang=null,?string $delimiter=null):?string
	{
		$return = null;
		$lang = static::code($lang);
		$delimiter = (is_string($delimiter))? $delimiter:static::$config['field'];

		if(strlen($value) && !empty($lang) && is_string($delimiter) && strlen($delimiter))
		$return = $value.$delimiter.$lang;
		
		return $return;
	}
	
	
	// arr
	// retourne une clé de champ dans un tableau après avoir formatté la valeur via la méthode field
	public static function arr(string $value,array $array,?string $lang=null,?string $delimiter=null)
	{
		$return = null;
		$field = static::field($value,$lang,$delimiter);
		
		if(is_string($field) && array_key_exists($field,$array))
		$return = $array[$field];
		
		return $return;
	}
	
	
	// arrs
	// retourne une valeur de champ dans un tableau multidimensionnel après avoir formatté la valeur via la méthode field
	// retourne un array multi de type crush
	public static function arrs(string $value,array $array,?string $lang=null,?string $delimiter=null):?array
	{
		$return = null;
		$field = static::field($value,$lang,$delimiter);
		
		if(is_string($field))
		$return = Arrs::keyValues($field,$array);
		
		return $return;
	}
	
	
	// reformat
	// cette méthode garde les clés finissant par _lang et retire la lang du nom de la clé
	// la méthode enlève les clés finissant par _autrelangs
	// accepte un tableau unidimensionnel seulement
	public static function reformat(array $array,?string $lang=null,?string $delimiter=null)
	{
		$return = [];
		$lang = static::code($lang);
		$others = static::others();
		$delimiter = (is_string($delimiter))? $delimiter:static::$config['field'];
		$not = [];
		
		if(!empty($lang) && !empty($others) && strlen($delimiter) && !empty($array))
		{
			foreach ($array as $key => $value)
			{
				$keep = true;
				
				if(is_string($key))
				{
					if(in_array($key,$not,true))
					$keep = false;
					
					elseif(Str::isEnd($delimiter.$lang,$key))
					{
						$key = Str::stripEnd($delimiter.$lang,$key);
						$not[] = $key;
					}
					
					else
					{
						foreach ($others as $l)
						{
							if(Str::isEnd($delimiter.$l,$key))
							$keep = false;
						}
					}
				}
				
				if($keep === true)
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
	
	
	// reformatColumn
	// cette méthode garde les clés finissant par _lang et retire la lang du nom de la clé
	// la méthode enlève les clés finissant par _autrelangs
	// accepte seulement un tableau column
	public static function reformatColumn(array $array,?string $lang=null,?string $delimiter=null)
	{
		$return = [];
		
		foreach ($array as $key => $value)
		{
			if(is_array($value))
			$return[$key] = static::reformat($value,$lang,$delimiter);
		}
		
		return $return;
	}
}

// set
Lang::set(null,Lang::defaultConfig());
?>