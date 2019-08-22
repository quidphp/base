<?php
declare(strict_types=1);
namespace Quid\Base;

// superglobal
class Superglobal extends Root
{
	// config
	public static $config = [
		'postKeys'=>['MAX_FILE_SIZE'] // clés post pouvant être enlevés
	];
	
	
	// hasSession
	// retourne vrai si la superglobale session est déclarée
	public static function hasSession():bool
	{
		return (isset($_SESSION))? true:false;
	}
	
	
	// getExists
	// vérifie l'existence d'une clé dans la superglobale get
	public static function getExists($key):bool 
	{
		return Arrs::keyExists($key,static::get());
	}


	// getsExists
	// vérifie l'existence de plusieurs clés dans la superglobale get
	public static function getsExists(array $keys):bool 
	{
		return Arrs::keysExists($keys,static::get());
	}
	

	// postExists
	// vérifie l'existence d'une clé dans la superglobale post
	public static function postExists($key):bool 
	{
		return Arrs::keyExists($key,static::post());
	}
	

	// postsExists
	// vérifie l'existence de plusieurs clés dans la superglobale post
	public static function postsExists(array $keys):bool 
	{
		return Arrs::keysExists($keys,static::post());
	}
	

	// cookieExists
	// vérifie l'existence d'une clé dans la superglobale cookie
	public static function cookieExists(string $key):bool 
	{
		return Arrs::keyExists($key,static::cookie());
	}
	

	// sessionExists
	// vérifie l'existence d'une clé dans la superglobale session si démarré
	public static function sessionExists($key):bool 
	{
		return (static::hasSession() && Arrs::keyExists($key,static::session()))? true:false;
	}
	

	// sessionsExists
	// vérifie l'existence de plusieurs clés dans la superglobale session si démarré
	public static function sessionsExists(array $keys):bool 
	{
		return (static::hasSession() && Arrs::keysExists($keys,static::session()))? true:false;
	}
	
	
	// fileExists
	// vérifie l'existence d'une clé dans la superglobale files
	public static function fileExists(string $key):bool 
	{
		return Arrs::keyExists($key,static::files());
	}
	

	// envExists
	// vérifie l'existence d'une clé dans la superglobale env
	public static function envExists(string $key):bool 
	{
		return Arrs::keyExists($key,static::env());
	}
	

	// requestExists
	// vérifie l'existence d'une clé dans la superglobale request
	public static function requestExists($key):bool 
	{
		return Arrs::keyExists($key,static::request());
	}
	

	// serverExists
	// vérifier l'existence d'une clé dans la superglobale superglobale
	// possibilité d'une recherche insensible à la case
	public static function serverExists(string $key,bool $sensitive=true):bool 
	{
		return Arrs::keyExists($key,static::server(),$sensitive);
	}
	
	
	// hasServerLength
	// retourne vrai si le tableau serveur a la clé content_length de spécifié
	public static function hasServerLength():bool 
	{
		return static::serverExists('CONTENT_LENGTH');
	}
	
	
	// hasServerLengthWithoutPost
	// retourne vrai si le tableau serveur a la clé content_length de spécifié et que le tableau post est vide
	// ceci signifie normalement un chargement de fichier qui a échoué
	public static function hasServerLengthWithoutPost():bool 
	{
		return (static::hasServerLength() && empty(static::post()))? true:false;
	}
	
	
	// get
	// retourne la superglobale get
	// retourne une référence
	public static function &get():array
	{
		return $_GET;
	}
	
	
	// post
	// retourne la superglobale post
	// retourne une référence
	public static function &post():array
	{
		return $_POST;
	}
	
	
	// cookie
	// retourne la superglobale cookie
	// retourne une référence
	public static function &cookie():array
	{
		return $_COOKIE;
	}
	
	
	// session
	// retourne la superglobale session si démarré
	// peut retourner null
	// retourne une référence
	public static function &session():?array
	{
		$return = null;
		
		if(static::hasSession())
		$return =& $_SESSION;
		
		return $return;
	}
	
	
	// files
	// retourne la superglobale files
	// retourne une référence
	public static function &files():array
	{
		return $_FILES;
	}
	
	
	// env
	// retourne la superglobale env
	// retourne une référence
	public static function &env():array
	{
		return $_ENV;
	}
	
	
	// request
	// retourne la superglobale request
	// retourne une référence
	public static function &request():array
	{
		return $_REQUEST;
	}
	
	
	// server
	// retourne la superglobale server
	// retourne une référence
	public static function &server():array
	{
		return $_SERVER;
	}

	
	// getGet
	// retourne une variable dans la superglobale get
	public static function getGet($key) 
	{
		return Arrs::get($key,static::get());
	}
	
	
	// getPost
	// retourne une variable dans la superglobale post
	public static function getPost($key) 
	{
		return Arrs::get($key,static::post());
	}
	
	
	// getCookie
	// retourne une variable dans la superglobale cookie
	public static function getCookie(string $key) 
	{
		return Arrs::get($key,static::cookie());
	}
	
	
	// getSession
	// retourne une variable dans la superglobale session si démarré
	// peut retourner null
	public static function getSession($key) 
	{
		return (static::hasSession())? Arrs::get($key,static::session()):null;
	}
	
	
	// getFiles
	// retourne une variable dans la superglobale files
	public static function getFiles(string $key) 
	{
		return Arrs::get($key,static::files());
	}
	
	
	// getEnv
	// retourne une variable dans la superglobale env
	public static function getEnv(string $key) 
	{
		return Arrs::get($key,static::env());
	}
	
	
	// getRequest
	// retourne une variable dans la superglobale request
	public static function getRequest($key) 
	{
		return Arrs::get($key,static::request());
	}
	
	
	// getServer
	// retourne une variable dans la superglobale server
	// possibilité d'une recherche insensible à la case
	public static function getServer(string $key,bool $sensitive=true) 
	{
		return Arrs::get($key,static::server(),$sensitive);
	}
	
	
	// getServerStart
	// retourne toutes les clés du tableau serveur commençant par la clé fourni
	// possible de faire une recherche de clé de façon insensible à la case
	// possible d'envoyer le retour dans la méthode format
	public static function getServerStart(string $key,bool $sensitive=true,bool $format=false):array
	{
		$return = [];
		
		foreach (static::server() as $k => $v) 
		{
			if(($sensitive === true && strpos($k,$key) === 0) || ($sensitive === false && stripos($k,$key) === 0))
			$return[$k] = $v;
		}
		
		if($format === true && is_array($return))
		$return = static::reformatServer($return);
		
		return $return;
	}
	
	
	// getServerHeader
	// retourne les headers du tableau server
	public static function getServerHeader(bool $format=false):array 
	{
		return static::getServerStart("HTTP_",true,$format);
	}
	
	
	// reformatServer
	// reformate un tableau comme $_SERVER
	// divise par _ et ensuite enlève la première partie et implode avec le séparateur -
	// la clé est ramener en strtolower et ucfirst
	public static function reformatServer(array $array):array
	{
		$return = [];
		
		if(!empty($array))
		{
			foreach ($array as $key => $value) 
			{
				$explode = explode("_",$key);
				if(is_array($explode))
				{
					if(count($explode) > 1)
					array_shift($explode);
					$explode = array_map('strtolower',$explode);
					$explode = array_map('ucfirst',$explode);
					$key = implode('-',$explode);
					$return[$key] = $value;
				}
			}
		}
		
		return $return;
	}
	
	
	// setGet
	// change la valeur d'une variable dans la superglobale get
	public static function setGet($key,$value):void
	{
		Arrs::setRef($key,$value,$_GET);
		
		return;
	}
	
	
	// setPost
	// change la valeur d'une variable dans la superglobale post
	public static function setPost($key,$value):void
	{
		Arrs::setRef($key,$value,$_POST);
		
		return;
	}

	
	// setCookie
	// change la valeur d'une variable dans la superglobale cookie
	public static function setCookie(string $key,$value):void
	{
		Arrs::setRef($key,$value,$_COOKIE);
		
		return;
	}
	
	
	// setSession
	// change la valeur d'une variable dans la superglobale session si démarré
	// peut retourner null
	public static function setSession($key,$value):void
	{
		if(static::hasSession())
		Arrs::setRef($key,$value,$_SESSION);
		
		return;
	}
	
	
	// setFiles
	// change la valeur d'une variable dans la superglobale files
	public static function setFiles(string $key,$value):void
	{
		Arrs::setRef($key,$value,$_FILES);
		
		return;
	}
	
	
	// setEnv
	// change la valeur d'une variable dans la superglobale env
	public static function setEnv(string $key,$value):void
	{
		Arrs::setRef($key,$value,$_ENV);
		
		return;
	}
	
	
	// setRequest
	// change la valeur d'une variable dans la superglobale request
	public static function setRequest($key,$value):void
	{
		Arrs::setRef($key,$value,$_REQUEST);
		
		return;
	}
	
	
	// setServer
	// change la valeur d'une variable dans la superglobale server
	// possibilité d'une opération insensible à la case
	public static function setServer(string $key,$value,bool $sensitive=true):void
	{
		Arrs::setRef($key,$value,$_SERVER,$sensitive);
		
		return;
	}
	
	
	// unsetGet
	// enlève une entrée dans le tableau superglobale get
	public static function unsetGet($key):void 
	{
		Arrs::unsetRef($key,$_GET);
		
		return;
	}
	
	
	// unsetPost
	// enlève une entrée dans le tableau superglobale post
	public static function unsetPost($key):void 
	{
		Arrs::unsetRef($key,$_POST);
		
		return;
	}

	
	// unsetCookie
	// enlève une entrée dans le tableau superglobale cookie
	public static function unsetCookie(string $key):void 
	{
		Arrs::unsetRef($key,$_COOKIE);
		
		return;
	}


	// unsetSession
	// enlève une entrée dans le tableau superglobale session si démarré
	// peut retourner null
	public static function unsetSession($key):void
	{
		if(static::hasSession())
		Arrs::unsetRef($key,$_SESSION);
		
		return;
	}


	// unsetFiles
	// enlève une entrée dans le tableau superglobale files
	public static function unsetFiles(string $key):void 
	{
		Arrs::unsetRef($key,$_FILES);
		
		return;
	}


	// unsetEnv
	// enlève une entrée dans le tableau superglobale env
	public static function unsetEnv(string $key):void 
	{
		Arrs::unsetRef($key,$_ENV);
		
		return;
	}


	// unsetRequest
	// enlève une entrée dans le tableau superglobale request
	public static function unsetRequest($key):void 
	{
		Arrs::unsetRef($key,$_REQUEST);
		
		return;
	}


	// unsetServer
	// enlève une entrée dans le tableau superglobale server
	// possibilité d'une opération insensible à la case
	public static function unsetServer(string $key,bool $sensitive=true):void 
	{
		Arrs::unsetRef($key,$_SERVER,$sensitive);
		
		return;
	}


	// unsetServerStart
	// enlève toutes les clés du tableau serveur commençant par la valeur fourni
	// possible de faire une recherche de clé de façon insensible à la case
	public static function unsetServerStart(string $key,bool $sensitive=true):void
	{
		$values = static::getServerStart($key,$sensitive,false);
		
		if(!empty($values))
		Arrs::unsetsRef(array_keys($values),$_SERVER,$sensitive);
		
		return;
	}
	
	
	// unsetServerHeader
	// enlève tous les headers du tableau serveur
	public static function unsetServerHeader():void
	{
		static::unsetServerStart("HTTP_");
		
		return;
	}
	
	
	// formatServerKey
	// format une chaîne dans le format du tableau serveur
	public static function formatServerKey(string $return):string 
	{
		return str_replace("-","_",strtoupper($return));
	}
	
	
	// postReformat
	// possibilité d'enlever les clés de post qui ne sont pas des noms de colonnes ou nom de clés réservés
	// possibilité d'enlever les tags html dans le tableau de retour
	// possibilité d'inclure les données chargés en provenance de files comme variable post
	// les données de files sont reformat par défaut, mais post a toujours précédente sur files
	public static function postReformat(array $return,bool $safeKey=false,bool $stripTags=false,bool $includeFiles=false,?array $files=null):array 
	{
		if($safeKey === true)
		{
			$postKeys = static::$config['postKeys'];
			
			foreach ($return as $key => $value) 
			{
				if(in_array($key,$postKeys,true) || !Validate::isCol($key))
				unset($return[$key]);
			}
		}
		
		if($stripTags === true)
		$return = Call::map('string','strip_tags',$return);
		
		if($includeFiles === true)
		{
			$files = (is_array($files))? $files:static::files();
			$files = static::filesReformat($files);
			$return = Arrs::replace($files,$return);
		}
		
		return $return;
	}
	
	
	// filesReformat
	// reformat un tableau en provenance de files, seulement s'il est multiple
	// retourne un tableau multidimensionnel si plusieurs fichiers
	public static function filesReformat(array $value):array 
	{
		$return = [];
		
		foreach ($value as $key => $value) 
		{
			if(is_string($key) && is_array($value))
			{
				if(Arrs::is($value))
				$return[$key] = Column::keySwap($value);
				
				else
				$return[$key] = $value;
			}
		}
		
		return $return;
	}
}
?>