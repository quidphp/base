<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// response
class Response extends Root
{
	// config
	public static $config = [
		'idLength'=>10, // longueur du id de la réponse
		'closeDown'=>[], // callbacks à lancer lors d'un closeDown
		'closeBody'=>[], // callbacks à lancer lors d'un closeBody
		'default'=>[
			'code'=>200, // code de réponse par défaut
			'headers'=>[ // header par défaut, le cacheLimiter dans session peut prendre le dessus sur certains de ces défauts
				'Expires'=>0, // temps d'expiration d'une réponse (maintenant)
				'Cache-Control'=>'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
				'Pragma'=>'no-cache',
				'Connection'=>'keep-alive',
				'Last-Modified'=>0,
				'X-UA-Compatible'=>'IE=Edge'], // désactive compatibility mode pour IE
			'headersCallbackSpeed'=>null, // nom du header pour le speed, avant c'était un tableau headersCallback mais ça causait un bug bizarre sur php 7.3
			'contentType'=>true] // contentType par défaut, si true alors utilise le callback autoContentType
	];


	// id
	protected static $id = null; // id unique de la réponse


	// is200
	// retourne vrai si le code de la réponse est 200 (OK)
	public static function is200():bool
	{
		return static::isCode(200);
	}


	// isCodePositive
	// retourne vrai si le code de la réponse est positive (200 à 399)
	public static function isCodePositive():bool
	{
		return static::isCodeBetween(200,399);
	}


	// isCodeError
	// retourne vrai si le code de la réponse est erreur (400 à 499)
	public static function isCodeError():bool
	{
		return static::isCodeIn(400);
	}


	// isCodeServerError
	// retourne vrai si le code de la réponse est server error (500+)
	public static function isCodeServerError():bool
	{
		return static::isCodeIn(500);
	}


	// isHttp1
	// retourne vrai si la le protocol est http 1 ou 1.1
	public static function isHttp1():bool
	{
		return Server::isHttp1();
	}


	// isHttp2
	// retourne vrai si la le protocol est http 2
	public static function isHttp2():bool
	{
		return Server::isHttp2();
	}


	// isHtml
	// retourne vrai si la réponse est de content type html
	public static function isHtml():bool
	{
		return static::isContentType('html');
	}


	// isHtmlOrAuto
	// retourne vrai si la réponse est de content type html ou automatique
	public static function isHtmlOrAuto():bool
	{
		return (empty(static::contentType()) || static::isHtml())? true:false;
	}


	// isJson
	// retourne vrai si la réponse est de content type json
	public static function isJson():bool
	{
		return static::isContentType('json');
	}


	// isXml
	// retourne vrai si la réponse est de content type xml
	public static function isXml():bool
	{
		return static::isContentType('xml');
	}


	// isConnectionNormal
	// retourne vrai si la connection est normal, renvoie à base/server
	public static function isConnectionNormal():bool
	{
		return Server::isConnectionNormal();
	}


	// isConnectionAborted
	// retourne vrai si la connection est aborted, renvoie à base/server
	public static function isConnectionAborted():bool
	{
		return Server::isConnectionAborted();
	}


	// areHeadersSent
	// retourne vrai si les en-têtes ont été envoyés
	public static function areHeadersSent():bool
	{
		return headers_sent();
	}


	// isCode
	// retourne vrai le code de la réponse est un de ceux donnés
	public static function isCode(...$values):bool
	{
		$return = false;

		foreach ($values as $value)
		{
			$return = (is_numeric($value) && http_response_code() === (int) $value)? true:false;

			if($return === true)
			break;
		}

		return $return;
	}


	// isCodeBetween
	// retourne vrai si le code est entre les valeurs from et to
	// possible de spécifier le code de comparaison en troisième argument, sinon utilise le courant
	public static function isCodeBetween($from,$to,?int $code=null):bool
	{
		$return = false;
		$code = (is_int($code))? $code:static::code();

		if(is_int($from) && is_int($to) && $code >= $from && $code <= $to)
		$return = true;

		return $return;
	}


	// isCodeIn
	// retourne vrai si le code se trouve dans le groupe (la centaine) donné en argument
	// possible de spécifier le code de comparaison en deuxième argument, sinon utilise le courant
	public static function isCodeIn($value,?int $code=null):bool
	{
		$return = false;

		if(is_int($value))
		{
			$from = (int) (floor($value / 100) * 100);
			$to = ($from + 99);
			$return = static::isCodeBetween($from,$to,$code);
		}

		return $return;
	}


	// isContentType
	// retourne vrai si la réponse est d'un content-type
	// supporte le content type exact, le content type short et le mime
	public static function isContentType(string $value):bool
	{
		return Header::isContentType($value,static::headers(false));
	}


	// headerExists
	// retourne vrai si la réponse contient le header
	public static function headerExists(string $key):bool
	{
		return Header::exist($key,static::headers(false));
	}


	// headersExists
	// retourne vrai si la réponse contient les headers
	public static function headersExists(array $keys):bool
	{
		return Header::exists($keys,static::headers(false));
	}


	// id
	// retourne le id unique de la réponse
	public static function id():string
	{
		if(static::$id === null && is_int(static::$config['idLength']))
		static::$id = Str::random(static::$config['idLength']);

		return static::$id;
	}


	// timeLimit
	// alias pour set_time_limit, renvoie à base/server
	public static function timeLimit(int $value=0):bool
	{
		return Server::timeLimit($value);
	}


	// connectionStatus
	// retourne le statut de la connection, renvoie à base/server
	// 0 normal, 1 aborted, 2 timeout, 3 aborted and timeout
	public static function connectionStatus():int
	{
		return Server::connectionStatus();
	}


	// ignoreUserAbort
	// n'arrête pas le script si la connection est aborted, renvoie à base/server
	// retourne la valeur courante de ignore_user_abort
	public static function ignoreUserAbort(bool $value=true):bool
	{
		return Server::ignoreUserAbort($value);
	}


	// headersSentFrom
	// retourne null ou un tableau contenant le fichier et la ligne ou les headers ont été envoyés
	public static function headersSentFrom():?array
	{
		$return = null;
		$file = null;
		$line = null;
		$headersSent = headers_sent($file,$line);

		if($headersSent === true)
		$return = ['file'=>$file,'line'=>$line];

		return $return;
	}


	// code
	// retourne le code actuel de la réponse
	public static function code():int
	{
		return http_response_code();
	}


	// protocol
	// retourne le protocole de la réponse
	public static function protocol():?string
	{
		return Server::httpProtocol();
	}


	// statusText
	// retourne le texte relié au code status
	public static function statusText():?string
	{
		return Header::statusText(static::code());
	}


	// status
	// retourne le header status de la réponse
	// si le message est custom, ajouté via setStatusText, cette méthode ne le retournera pas
	public static function status():?string
	{
		return Header::status(static::code());
	}


	// setCode
	// change le code actuel de la réponse
	// http_response_code va automatiquement mettre à jour le protocole et status text
	// retourne vrai si le code courant après la modification est celui donné en argument
	public static function setCode(int $code):bool
	{
		return static::setStatus($code);
	}


	// setStatus
	// change le header status
	// l'argument peut être un int (code) ou une string (status text)
	// le response code doit être défini dans config de header, ce n'est pas possible de changer le status text ou créer un code non défini
	public static function setStatus($value):bool
	{
		$return = false;

		if(!static::areHeadersSent())
		{
			$code = null;
			$text = null;

			if(is_int($value) && Header::isCodeValid($value))
			{
				$code = $value;
				$text = Header::statusTextFromCode($value);
			}

			elseif(is_string($value) && Header::isStatusTextValid($value))
			{
				$code = Header::codeFromStatusText($value);
				$text = $value;
			}

			if(is_int($code) && is_string($text))
			{
				$array['protocol'] = static::protocol();
				$array['code'] = $code;
				$array['text'] = $text;
				$status = Header::makeStatus($array);

				if(Header::isStatus($status))
				{
					header($status,true);

					if(http_response_code() === $code)
					$return = true;
				}
			}
		}

		return $return;
	}


	// ok
	// change le code la requête pour 200
	public static function ok():bool
	{
		return static::setCode(200);
	}


	// moved
	// change le code la requête un code entre 300 et 399
	// par défaut 301, si false utilise 302
	public static function moved($code=null):bool
	{
		$return = false;

		if($code === true || $code === null)
		$code = 301;

		elseif($code === false)
		$code = 302;

		if(static::isCodeIn(300,$code))
		$return = static::setCode($code);

		return $return;
	}


	// error
	// change le code de la requête entre 400 et 499
	// kill permet de tuer la réponse
	public static function error($code=null,$kill=false)
	{
		$return = false;

		if(!is_int($code))
		$code = 404;

		if(static::isCodeIn(400,$code))
		$return = static::setCode($code);

		if($kill !== false)
		static::kill($kill);

		return $return;
	}


	// notFound
	// change le code de la requête pour 404
	// kill permet de tuer la réponse
	public static function notFound($kill=false):bool
	{
		return static::error(404,$kill);
	}


	// serverError
	// change le code de la requête pour un code 500+
	// kill permet de tuer la réponse
	public static function serverError($code=null,$kill=false):bool
	{
		$return = false;

		if(!is_int($code))
		$code = 500;

		if(static::isCodeIn(500,$code))
		$return = static::setCode($code);

		if($kill !== false)
		static::kill($kill);

		return $return;
	}


	// redirect
	// redirige la réponse vers une autre adresse
	// kill permet de tuer la réponse
	public static function redirect($value,$code=null,$kill=true,bool $encode=true):bool
	{
		$return = false;

		if(is_object($value))
		$value = Obj::cast($value);

		if(is_string($value) && static::moved($code))
		{
			$return = true;

			if($encode === true)
			$value = Uri::encodeAll($value);

			static::setHeader('Location',$value);

			if($kill !== false)
			static::kill($kill);
		}

		return $return;
	}


	// redirectReferer
	// redirige la réponse vers le referrer s'il est interene
	// par défaut, vérifier si le referer est safe
	// si pas de referer, ou refered unsafe et que fallback est true, renvoie vers le schemeHost
	public static function redirectReferer(bool $fallback=true,bool $safe=true,$code=null,$kill=true,bool $encode=true):bool
	{
		$return = false;
		$referer = Request::referer(true);

		if(!empty($referer) && $safe === true)
		{
			$path = Uri::path($referer);

			if(!is_string($path) || !Path::isSafe($path))
			$referer = null;
		}

		if(empty($referer) && $fallback === true)
		$referer = Request::schemeHost();

		if(!empty($referer))
		$return = static::redirect($referer,$code,$kill,$encode);

		return $return;
	}


	// download
	// force le téléchargement de la valeur donné en argument dans le navigateur
	// si value est string, alors ouvre une resource
	// option kill, length et sleep
	// il est aussi possible de changer le mime ou le basename si la resource est de type phpWritable (via les options de stream)
	public static function download($value,?array $option=null):bool
	{
		return static::downloadToScreen('download',$value,$option);
	}


	// toScreen
	// force l'affichage de la valeur donné en argument dans le navigateur
	// si value est string, alors ouvre une resource
	// option kill, length et sleep
	public static function toScreen($value,?array $option=null):bool
	{
		return static::downloadToScreen('toScreen',$value,$option);
	}


	// downloadToScreen
	// utilisé par les méthodes download et toScreen
	// si tout va, met un code réponse 200
	// méthode protégé
	protected static function downloadToScreen(string $method,$value,?array $option=null):bool
	{
		$return = false;
		$option = Arr::plus(['kill'=>true,'length'=>true,'sleep'=>false,'mime'=>null,'basename'=>null],$option);

		if(in_array($method,['download','toScreen'],true))
		{
			if(is_object($value))
			$value = Obj::cast($value);

			if(is_string($value))
			$value = Res::open($value);

			if(is_resource($value))
			{
				if(is_string($option['mime']))
				Res::setContextMime($option['mime'],$value);

				if(is_string($option['basename']))
				Res::setContextBasename($option['basename'],$value);

				$meta = Res::responseMeta($value);

				if(!empty($meta) && is_array($meta))
				{
					$headers = Header::$method($meta);

					if(!empty($headers))
					{
						$return = true;

						static::ok();
						static::setsHeader($headers);

						if($meta['kind'] !== 'phpOutput')
						Res::passthruChunk($option['length'],$value,$option);

						if($option['kill'] !== false)
						static::kill($option['kill']);
					}
				}
			}
		}

		return $return;
	}


	// headers
	// retourne la liste des headers par défaut formatté en tableau associatif
	// mettre parse à false pour envoyer le résultat de headers_list
	public static function headers(bool $parse=true):array
	{
		$return = headers_list();

		if($parse === true)
		$return = Header::arr($return);

		return $return;
	}


	// getHeader
	// retourne un header
	// insensible à la case
	public static function getHeader(string $key)
	{
		return Header::get($key,static::headers(false));
	}


	// getsHeader
	// retourne plusieurs headers
	// insensible à la case
	public static function getsHeader(array $keys):array
	{
		return Header::gets($keys,static::headers(false));
	}


	// contentType
	// retourne le content type de la réponse
	// si parse n'est pas vide, le content type est envoyé dans parse
	// si parse est 2, la valeur mime est true dans parseContentType
	public static function contentType(?int $parse=2):?string
	{
		return Header::contentType(static::headers(false),$parse);
	}


	// setHeader
	// modifie un header
	// possibilité de remplace ou append si header déjà existant
	// retourne le nombre de ligne header ajoute
	// retourne null si headers sont envoyés
	public static function setHeader(string $key,$value,bool $replace=true):?int
	{
		$return = null;
		$sets = static::setsHeader([$key=>$value],$replace);

		if(!empty($sets))
		$return = current($sets);

		return $return;
	}


	// setsHeader
	// modifie plusieurs headers
	// possibilité de remplace ou append si header déjà existant
	// retourne null si headers sont envoyés
	public static function setsHeader(array $values,bool $replace=true):?array
	{
		$return = null;

		if(!static::areHeadersSent())
		{
			$return = [];

			foreach ($values as $key => $value)
			{
				$return[$key] = 0;
				$list = Header::list([$key=>$value]);

				foreach ($list as $i => $header)
				{
					$r = ($i === 0)? $replace:false;
					header($header,$r);
					$return[$key]++;
				}
			}
		}

		return $return;
	}


	// setContentType
	// change le content type de la réponse courante
	public static function setContentType(string $value):bool
	{
		$return = false;
		$contentType = Header::prepareContentType($value);

		if(!empty($contentType) && static::setHeader('Content-Type',$contentType) === 1)
		$return = true;

		return $return;
	}


	// unsetHeader
	// enlève un header
	// retourne null si headers sont envoyés
	public static function unsetHeader(string $key):?bool
	{
		$return = null;

		if(!static::areHeadersSent())
		{
			$return = false;

			if(static::headerExists($key))
			{
				header_remove($key);
				$return = true;
			}
		}

		return $return;
	}


	// unsetsHeader
	// enlève plusieurs headers
	// retourne null si headers sont envoyés
	public static function unsetsHeader(array $keys):?array
	{
		$return = null;

		if(!static::areHeadersSent())
		{
			$return = [];

			foreach ($keys as $key)
			{
				$return[$key] = false;

				if(static::headerExists($key))
				{
					header_remove($key);
					$return[$key] = true;
				}
			}
		}

		return $return;
	}


	// emptyHeader
	// vide tous les headers y compris ceux définis par PHP par défaut
	// retourne null si headers sont envoyés
	public static function emptyHeader():?array
	{
		return static::unsetsHeader(array_keys(static::headers(true)));
	}


	// prepare
	// applique les paramètres par défaut pour la réponse
	// code de réponse, headers par défaut, contentType ou autoContentType, buffer start, onShutDownCloseDown et onCloseDownCloseBody
	// note headersCallbackSpeed -> avant c'était un tableau headersCallback mais ça causait un bug bizarre sur php 7.3
	public static function prepare(?array $option=null):array
	{
		$return = [];
		$option = Arr::plus(static::$config['default'],$option);
		$bufferCallback = null;

		// code
		if(is_int($option['code']))
		$return['code'] = static::setCode($option['code']);

		// headers
		if($option['headers'] !== null)
		$return['headers'] = static::setsHeaderDefault($option['headers']);

		// headersCallbackSpeed
		if(is_string($option['headersCallbackSpeed']))
		{
			$name = $option['headersCallbackSpeed'];
			$return['headersCallbackSpeed'] = static::setHeaderCallback(function() use ($name) {
				$speed = Debug::speed();
				$header = $name.': '.$speed;
				header($header);
			});
		}

		// contentType
		if(!empty($option['contentType']))
		{
			if(is_string($option['contentType']))
			$return['setContentType'] = static::setContentType($option['contentType']);

			elseif($option['contentType'] === true)
			$bufferCallback = [static::class,'autoContentType'];
		}

		// buffer
		$return['getCleanAllEcho'] = Buffer::getCleanAllEcho($bufferCallback);

		// onCloseDownCloseBody
		static::onCloseDownCloseBody();

		// onShutDownCloseDown
		static::onShutDownCloseDown();

		return $return;
	}


	// setHeaderCallback
	// permet d'enregistrer un callback lors de l'envoie du output
	// permet de changer les headers avant l'envoie
	public static function setHeaderCallback(callable $callable):bool
	{
		return header_register_callback($callable);
	}


	// prepareHeaderDefault
	// méthode utilisé par setsHeaderDefault
	// si une value d'une header par défaut est int, alors additionne le timestamp courant et formate au format GMT
	public static function prepareHeaderDefault(array $return):array
	{
		$time = Date::time();

		foreach ($return as $key => $value)
		{
			if(is_int($value))
			$return[$key] = Date::gmt($value += $time);
		}

		return $return;
	}


	// setsHeaderDefault
	// applique les headers par défaut
	// les headers présents avant seront vidés
	public static function setsHeaderDefault(?array $option=null):?array
	{
		$return = null;
		$option = Arr::plus(static::$config['default']['headers'],$option);

		if(!empty($option) && is_array($option))
		{
			static::emptyHeader();
			$option = static::prepareHeaderDefault($option);
			$return = static::setsHeader($option);
		}

		return $return;
	}


	// body
	// retourne le body complet dans le buffer
	// le buffer est conservé mais aplati à un niveau
	public static function body():string
	{
		return Buffer::getAll(true);
	}


	// setBody
	// remplace le body complet de la réponse par la valeur fourni en argument
	public static function setBody(string $value):bool
	{
		return Buffer::cleanAllEcho($value);
	}


	// prependBody
	// ajoute du contenu au début du body de la réponse
	public static function prependBody(string $value):bool
	{
		return Buffer::prependEcho($value);
	}


	// appendBody
	// ajoute du contenu au body de la réponse
	public static function appendBody(string $value):bool
	{
		return Buffer::appendEcho($value);
	}


	// emptyBody
	// vide le contenu du body de la réponse
	public static function emptyBody():bool
	{
		return (!empty(Buffer::cleanAll()))? true:false;
	}


	// sleep
	// pause l'éxécution du script
	// permet de mettre une valeur flottante, par exemple 0.5 seconde
	// retourne null ou le temps que le script a sleep
	public static function sleep(float $value):?float
	{
		$return = null;

		if($value > 0)
		{
			$microtime = Date::microtime();
			$uvalue = (int) ($value * 1000000);
			usleep($uvalue);
			$return = Date::microtime() - $microtime;
		}

		return $return;
	}


	// sleepUntil
	// pause l'éxécution du script jusqu'au timestamp ou microtimestamp donné en argument
	// important d'utiliser microtime comme valeur de base si la valeur du sleep est moins d'une seconde
	// retourne null ou le temps que le script a sleep
	public static function sleepUntil(float $value):?float
	{
		$return = null;
		$microtime = Date::microtime();

		if($value > $microtime && time_sleep_until($value))
		$return = Date::microtime() - $microtime;

		return $return;
	}


	// kill
	// tue la réponse et l'éxécution du script
	// si valeur est int entre 0 et 254, code de sortie non affiché
	// si value est scalar, message de sortie
	public static function kill($value=null):void
	{
		$kill = null;

		if(is_int($value) && $value >= 0 && $value < 255)
		$kill = $value;

		elseif(!is_bool($value))
		$kill = $value;

		exit($kill);

		return;
	}


	// onShutDown
	// enregistre une callable à appeler au shutdown
	// des arguments peuvent être passés au callable
	public static function onShutDown(callable $call,...$args):void
	{
		register_shutdown_function($call,...$args);

		return;
	}


	// onCloseDown
	// enregistre une callable à appeler au close down, des arguments peuvent être passés au callable
	public static function onCloseDown(callable $call,...$args):void
	{
		static::$config['closeDown'][] = [$call,$args];

		return;
	}


	// emptyCloseDown
	// vide le tableau des callbacks sur closeDown
	// remet on closeBody sur closeDown
	public static function emptyCloseDown():void
	{
		static::$config['closeDown'] = [];
		static::onCloseDownCloseBody();

		return;
	}


	// closeDown
	// lance tous les callbacks closeDown dans l'ordre d'ajout
	public static function closeDown():void
	{
 		static::closeDownBody('closeDown');

		return;
	}


	// onShutDownCloseDown
	// sur shutDown lance closeDown
	public static function onShutDownCloseDown():void
	{
		static::onShutDown([static::class,'closeDown']);

		return;
	}


	// onCloseBody
	// enregistre une callable à appeler au close doc, des arguments peuvent être passés au callable
	public static function onCloseBody(callable $call,...$args):void
	{
		static::$config['closeBody'][] = [$call,$args];

		return;
	}


	// emptyCloseBody
	// vide le tableau des callbacks sur closeBody
	public static function emptyCloseBody():void
	{
		static::$config['closeBody'] = [];

		return;
	}


	// closeBody
	// lance tous les callbacks closeBody dans l'ordre d'ajout
	public static function closeBody():void
	{
 		static::closeDownBody('closeBody');

		return;
	}


	// onCloseDownCloseBody
	// sur closeDown lance closeBody
	public static function onCloseDownCloseBody():void
	{
		static::onCloseDown([static::class,'closeBody']);

		return;
	}


	// speedOnCloseBody
	// affiche speed sur closeBody
	public static function speedOnCloseBody():void
	{
		static::onCloseBody(function() {
			if(static::isHtmlOrAuto())
			Debug::var(Debug::speed());
			return;
		});
	}


	// closeDownBody
	// lance tous les callbacks closeDown dans l'ordre d'ajout
	// les callbacks sont effacés au fur et à mesure
	// méthode protégé
	protected static function closeDownBody(string $type):void
	{
		if(in_array($type,['closeDown','closeBody'],true))
		{
			foreach (static::$config[$type] as $key => $callback)
			{
				if(is_array($callback) && !empty($callback))
				{
					$callable = Arr::valueFirst($callback);
					$args = Arr::valueLast($callback);
					$callable(...$args);
				}

				unset(static::$config[$type][$key]);
			}
		}

		return;
	}


	// getAutoContentType
	// détecte le content type à partir d'une string
	public static function getAutoContentType(string $value):string
	{
		$return = 'html';

		if(Json::is($value))
		$return = 'json';

		elseif(Xml::is($value))
		$return = 'xml';

		return $return;
	}


	// autoContentType
	// applique le content type en fonction de la valeur donné en argument
	// aucune content type appliquer si la réponse en a déjà un
	// est utilisé comme callback pour ob_start
	public static function autoContentType(string $return):?string
	{
		$contentType = static::contentType();

		if(empty($contentType))
		{
			$autoContentType = static::getAutoContentType($return);
			static::setContentType($autoContentType);
		}

		return $return;
	}
}
?>