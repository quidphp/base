<?php
declare(strict_types=1);
namespace Quid\Base {

// debug
class Debug extends Root
{
	// config
	public static $config = [
		'method'=>true, // méthode par défaut pour générer l'affichage des détails d'une variable, si true c'est automatique
		'helper'=>null, // closure pour helper
		'inc'=>0 // permet de test le nombre d'appel
	];
	
	
	// data
	public static $data = []; // peut être utilisé comme variable statique pour le débogagge
	
	
	// helper
	// charge les helpers
	public static function helper():void
	{
		if(!empty(static::$config['helper']))
		{
			static::$config['helper']();
			static::$config['helper'] = null;
		}
		
		return;
	}
	
	
	// var
	// génère le détail d'une variable selon la méthode dans config
	// si echo est false, la string est retourné mais pas affiché
	// si echo est true, flush peut être true
	public static function var($value=null,bool $wrap=true,bool $echo=true,bool $flush=false):string
	{
		$return = '';
		$method = static::varMethod();
		
		if(is_string($method))
		{
			$return = static::$method($value,$wrap);
			
			if($echo === true)
			static::echoFlush($return,$flush);
		}
		
		elseif($echo === true)
		static::echoFlush($value,$flush);
		
		return $return;
	}
	

	// varFlush
	// comme var mais echo et flush
	public static function varFlush($value=null,bool $wrap=true):string
	{
		return static::var($value,$wrap,true,true);
	}
	
	
	// varGet
	// retourne le détail d'une variable selon la méthode dans config, pas de echo
	public static function varGet($value=null,bool $wrap=true):string
	{
		return static::var($value,$wrap,false);
	}
	
	
	// vars
	// génère le détail de variables selon la méthode dans config
	// echo et retourne un array
	public static function vars(...$values):array
	{
		$return = [];
		
		foreach ($values as $value)
		{
			$return[] = static::var($value);
		}
		
		return $return;
	}
	
	
	// varsFlush
	// génère le détail de variables selon la méthode dans config
	// echo, et retourne un array, flush est utilisé
	public static function varsFlush(...$values):array
	{
		$return = [];
		
		foreach ($values as $value)
		{
			$return[] = static::varFlush($value);
		}
		
		return $return;
	}
	
	
	// varsGet
	// génère le détail de variables selon la méthode dans config
	// n'écho rien, retourne une string
	public static function varsGet(...$values):string
	{
		$return = '';
		
		foreach ($values as $value)
		{
			$return .= static::varGet($value);
		}
		
		return $return;
	}
	
	
	// dead
	// génère le détail d'une variable selon la méthode dans config et meurt
	// utilise la méthode par défaut
	public static function dead($value=null):void
	{
	 	static::var($value);
		Response::kill();
	}
	
	
	// deads
	// génère le détail de variables selon la méthode dans config et meurt
	// utilise la méthode par défaut
	public static function deads(...$values):void
	{
		foreach ($values as $value)
		{
			static::var($value);
		}
		
		Response::kill();
	}
	
	
	// echoFlush
	// permet d'echo la valeur ou de la echo + flush si flush est true
	public static function echoFlush($value,bool $flush=true):void
	{
		if($flush === true)
		Buffer::startEchoEndFlushAllStart($value);
		
		else
		echo $value;
		
		return;
	}
	
	
	// varMethod
	// retourne la méthode à utiliser pour faire un dump de données
	// détecte si xdebug est installé sur le système
	public static function varMethod():?string
	{
		$return = static::$config['method'];
		
		if($return === true)
		{
			if(Ini::isVarDumpOverloaded())
			$return = 'dump';
			
			else
			$return = 'export';
		}
		
		return $return;
	}
	
	
	// printr
	// retourne les détail d'une variable avec print_r
	// si wrap est true, la string est enrobbé de pre
	public static function printr($value=null,bool $wrap=true):string
	{
		$return = print_r($value,true);

		if($wrap === true)
		$return = "<pre>$return</pre>";

		return $return;
	}
	
	
	// dump
	// retourne les détails d'une variable avec var_dump
	// ouvre et ferme un buffer
	// si wrap est true, la string est enrobbé de pre
	// si extra est true, passe la string dans specialChars et met la longueur entre paranthèses avant (utile pour html)
	public static function dump($value=null,bool $wrap=true,bool $extra=true):string
	{
		$return = '';
		$isOverloaded = Ini::isVarDumpOverloaded();
		
		if($isOverloaded === false && is_string($value) && $extra === true)
		{
			$strlen = strlen($value);
			$specialChars = Html::specialChars($value);
			
			if(strlen($specialChars) !== $strlen)
			$value = $specialChars."---$strlen";
		}
		
		Buffer::start();
		var_dump($value);
		$return = Buffer::getClean();
		
		if($isOverloaded === false && $wrap === true)
		$return = "<pre>$return</pre>";
		
		return $return;
	}
	

	// export
	// retourne les détails d'une variable avec var_export
	// si la variable n'est pas array ou objet, envoie à dump
	// si wrap est true, la string est passé dans stripslashes et ensuite highlight_string
	// si extra est true, ajoute le compte si c'est un tableau ou la longueur si c'est une string
	public static function export($value=null,bool $wrap=true,bool $extra=true):string
	{
		$return = '';
		
		$return = var_export($value,true);
		
		if($wrap === true)
		$return = static::highlight($return,$wrap,true);
		
		if($extra === true)
		{
			$count = null;
			
			if(is_string($value))
			$count = strlen($value);
			
			if(is_array($value))
			$count = count($value);
			
			if(is_int($count))
			{
				$count = "---$count";
				$return .= ($wrap === true)? "<pre>$count</pre>":$count;
			}
		}
		
		return $return;
	}

	
	// highlight
	// highlight une string php ou un fichier source php
	// si wrap est true, enrobe la string des open et close tag de php
	// si unwrap est true, essaie d'enlèver les open et close tag de php du code html
	public static function highlight(string $string,bool $wrap=false,bool $unwrap=false,bool $file=false):string
	{
		$return = '';
		
		if($file === true)
		$return = highlight_file($string,true);
		
		else
		{
			$string = stripslashes($string);
			
			if($wrap === true)
			$string = "<?php\n".$string."\n?>";
			
			$return = highlight_string($string,true);
		}
		
		if($unwrap === true)
		$return = str_replace(['&lt;?php<br />','&lt;?php&nbsp;','<span style="color: #0000BB">?&gt;</span>','?&gt;'],'',$return);
		
		return $return;
	}
	
	
	// sourceStrip
	// retourne la source d'un fichier php sans espace blanc
	public static function sourceStrip(string $value):?string
	{
		$return = null;
		
		if(is_file($value))
		$return = php_strip_whitespace($value);
		
		return $return;
	}
	
	
	// trace
	// génère le debug_backtrace
	// premier argument permet l'affichage ou non des arguments du backtrace
	// shift permet d'enlever un nombre d'entrée au début du tableau
	public static function trace(bool $showArgs=false,int $shift=0):array
	{
		$option = ($showArgs === true)? 0:DEBUG_BACKTRACE_IGNORE_ARGS;
		$return = debug_backtrace($option);
		Arr::shift($return,$shift);
		
		return $return;
	}

	
	// traceStart
	// retourne un trace à partir d'un point de départ file et line (facultatif)
	// showArgs permet d'enlever les arguments
	public static function traceStart(string $file,?int $line=null,bool $showArgs=false,?array $trace=null):array
	{
		$return = [];
		$trace = ($trace === null)? static::trace(false,1):$trace;
		$capture = false;
		
		foreach ($trace as $key => $value)
		{
			if(is_array($value))
			{
				if(array_key_exists('file',$value) && $value['file'] === $file)
				{
					if($line === null || (array_key_exists('line',$value) && $value['line'] === $line))
					$capture = true;
				}
				
				if($capture === true)
				$return[] = $value;
			}
		}
		
		if($showArgs === false)
		$return = static::traceRemoveArgs($return);
		
		return $return;
	}
	
	
	// traceIndex
	// retourne un index de trace
	// les arguments de traceStart peuvent aussi être fournis en 2e et 3e position
	public static function traceIndex(int $index=0,?string $file=null,?int $line=null,bool $showArgs=false,?array $trace=null):?array
	{
		$return = null;
		$trace = ($trace === null)? static::trace(false,1):$trace;
		
		if(is_string($file))
		$trace = static::traceStart($file,$line,$showArgs,$trace);
		
		elseif($showArgs === false)
		$trace = static::traceRemoveArgs($trace);
		
		$return = Arr::index($index,$trace);
		
		return $return;
	}
	
	
	// traceSlice
	// permet de slice un tableau trace via offset et length
	// les arguments de traceStart peuvent aussi être fournis en 3e et 4e position
	public static function traceSlice(int $offset,?int $length=null,?string $file=null,?int $line=null,bool $showArgs=false,?array $trace=null):array
	{
		$return = [];
		$trace = ($trace === null)? static::trace(false,1):$trace;
		
		if(is_string($file))
		$trace = static::traceStart($file,$line,$showArgs,$trace);
		
		elseif($showArgs === false)
		$trace = static::traceRemoveArgs($trace);
		
		$return = Arr::sliceIndex($offset,$length,$trace,false);
		
		return $return;
	}
	
	
	// traceLastCall
	// retourne la dernière fonction appelé
	// les arguments de traceStart peuvent être fournis en 1ère et 2e position
	public static function traceLastCall(?string $file=null,?int $line=null,?array $trace=null):?string
	{
		$return = null;
		$trace = ($trace === null)? static::trace(false,1):$trace;
		
		if(is_string($file))
		$trace = static::traceStart($file,$line,false,$trace);
		
		foreach ($trace as $key => $value)
		{
			if(is_array($value) && !empty($value['function']))
			{
				$return = '';
				
				if(!empty($value['class']))
				$return .= $value['class'];

				if(!empty($value['function']))
				$return .= (empty($return))? $value['function']:'::'.$value['function'];
				
				break;
			}
		}
		
		return $return;
	}
	
	
	// traceBeforeClass
	// retoure la première entrée trace trouvé qui a une classe et qui n'est pas la classe présente ou celle en argument
	// exception si c'est une des classes données et que c'est un constructeur
	public static function traceBeforeClass($class=null,bool $construct=true,?array $trace=null):?array
	{
		$return = null;
		$trace = ($trace === null)? static::trace(false,1):$trace;
		$class = (empty($class))? [static::class]:Arr::append($class,static::class);
		
		foreach ($trace as $key => $value)
		{
			if(is_array($value) && array_key_exists('class',$value) && array_key_exists('function',$value))
			{
				if(!in_array($value['class'],$class,true) || ($construct === true && $value['function'] === '__construct'))
				{
					$return = $value;
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// traceBeforeFile
	// retoure la première entrée trace avant le fichier fourni en argument
	public static function traceBeforeFile(string $file,?array $trace=null):?array
	{
		$return = null;
		$trace = ($trace === null)? static::trace(false,1):$trace;
		
		foreach ($trace as $key => $value)
		{
			if(is_array($value) && array_key_exists('file',$value) && $value['file'] !== $file)
			{
				$return = $value;
				break;
			}
		}
		
		return $return;
	}
	
	
	// traceRemoveArgs
	// enlève les arguments d'un tableau multidimensionnel trace
	public static function traceRemoveArgs(array $return):array
	{
		foreach ($return as $key => $value)
		{
			if(is_array($value) && array_key_exists('args',$value))
			{
				unset($value['args']);
				$return[$key] = $value;
			}
		}
		
		return $return;
	}


	// speed
	// calcule la différence entre deux microtime, par défaut getMicrotime dans date
	// round permet d'arrondir la différence
	public static function speed(?float $value=null,int $round=3):float
	{
		$return = 0;
		$value = (is_numeric($value))? $value:Date::getMicrotime();

		if(is_numeric($value))
		$return = Number::round((Date::microtime() - $value),$round);

		return $return;
	}
	
	
	// call
	// permet de faire des itérations sur une callable
	// retourne le temps d'éxécutions
	public static function call(int $iteration=5000,callable $call,...$arg):float
	{
		$return = 0;
		$microtime = Date::microtime();
		
		if($iteration > 0)
		{
			for ($i=0; $i < $iteration; $i++)
			{
				$call(...$arg);
			}
		}
		
		$return = static::speed($microtime,3);
		
		return $return;
	}
	
	
	// data
	// retourne le tableau data
	public static function data():array
	{
		return static::$data;
	}
}
}


// helper
namespace {
use Quid\Base;

Base\Debug::$config['helper'] = function() {
	// d
	// raccourci pour vars
	if(!function_exists('d'))
	{
		function d(...$values):array
		{
			return Base\Debug::vars(...$values);
		}
	}

	
	// df
	// raccourci pour varsFlush
	if(!function_exists('df'))
	{
		function df(...$values):array
		{
			return Base\Debug::varsFlush(...$values);
		}
	}
	
	
	// dg
	// raccourci pour varsGet
	if(!function_exists('dg'))
	{
		function dg(...$values):string
		{
			return Base\Debug::varsGet(...$values);
		}
	}


	// dd
	// raccourci pour deads
	if(!function_exists('dd'))
	{
		function dd(...$values):void
		{
			Base\Debug::deads(...$values);
			return;
		}
	}

	// trace
	// raccourci pour trace
	if(!function_exists('trace'))
	{
		function trace(bool $showArgs=false,int $shift=1):array
		{
			$return = Base\Debug::trace($showArgs,$shift);
			Base\Debug::var($return);
			return $return;
		}
	}


	// speed
	// raccourci pour speed
	if(!function_exists('speed'))
	{
		function speed(?float $value=null,int $round=3):float
		{
			$return = Base\Debug::speed($value,$round);
			Base\Debug::var($return);
			return $return;
		}
	}


	// speedd
	// raccourci pour speed + dead
	if(!function_exists('speedd'))
	{
		function speedd(?float $value=null,int $round=3):void
		{
			Base\Debug::var(Base\Debug::speed($value,$round));
			Base\Response::kill();
			return;
		}
	}
};

// helper
Base\Debug::helper();
}
?>