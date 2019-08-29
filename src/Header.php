<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// header
// class with static methods to work with HTTP headers
class Header extends Listing
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'implode'=>1, // index du séparateur à utiliser lors du implode
			'caseImplode'=>[self::class,'keyCase']], // les clés sont ramenés dans cette case lors du implode
		'separator'=>[ // les séparateurs de header
			["\r\n","\r\n"],
			[':',': '],
			[';']], // séparateur pour content-type
		'sensitive'=>false, // la classe est sensible ou non à la case
		'status'=>'status' // clé réservé pour header status
	];


	// isStatus
	// retourne vrai si la string header est une entrée status
	public static function isStatus($value):bool
	{
		return (is_string($value) && stripos($value,'HTTP/') === 0)? true:false;
	}


	// is200
	// retourne vrai le code status dans le tableau header est 200
	public static function is200($header):bool
	{
		return static::isCode(200,$header);
	}


	// isCodePositive
	// retourne vrai si le code dans le tableau header est 200, 301 ou 302
	public static function isCodePositive($header):bool
	{
		return (static::isCodeBetween(200,399,$header))? true:false;
	}


	// isCodeError
	// retourne vrai si le code dans le tableau header est 400 ou 404
	public static function isCodeError($header):bool
	{
		return (static::isCodeIn(400,$header))? true:false;
	}


	// isCodeServerError
	// retourne vrai si le code dans le tableau header est 500
	public static function isCodeServerError($header):bool
	{
		return (static::isCodeIn(500,$header))? true:false;
	}


	// isCodeValid
	// retourne vrai si le code existe dans le tableau config
	public static function isCodeValid($value):bool
	{
		return (is_int($value) && array_key_exists($value,Lang\En::$config['header']['responseStatus']))? true:false;
	}


	// isStatusTextValid
	// retourne vrai si le texte de status existe dans le tableau config
	public static function isStatusTextValid($value):bool
	{
		return (is_string($value) && in_array($value,Lang\En::$config['header']['responseStatus'],true))? true:false;
	}


	// isHtml
	// retourne vrai si le tableau header a un content type html
	public static function isHtml($header):bool
	{
		return static::isContentType('html',$header);
	}


	// isJson
	// retourne vrai si le tableau header a un content type json
	public static function isJson($header):bool
	{
		return static::isContentType('json',$header);
	}


	// isXml
	// retourne vrai si le tableau header a un content type xml
	public static function isXml($header):bool
	{
		return static::isContentType('xml',$header);
	}


	// hasStatus
	// retourne vrai si le tableau header a une entrée status
	public static function hasStatus($header):bool
	{
		$return = false;

		if(is_array($header))
		{
			$statusKey = static::$config['status'];
			$assoc = static::arr($header);

			if(is_string($statusKey) && array_key_exists($statusKey,$assoc) && is_string($assoc[$statusKey]))
			$return = true;
		}

		return $return;
	}


	// isCode
	// retourne vrai si le code du tableau header est un de ceux donnés en argument
	public static function isCode($value,$header):bool
	{
		$return = false;

		if(is_array($header))
		{
			$code = static::code($header);

			if(is_int($code))
			{
				if(!is_array($value))
				$value = [$value];

				foreach ($value as $v)
				{
					if(is_numeric($v) && $code === (int) $v)
					{
						$return = true;
						break;
					}
				}
			}
		}

		return $return;
	}


	// isCodeBetween
	// retourne vrai si le code est entre les valeurs from et to
	public static function isCodeBetween($from,$to,$header):bool
	{
		$return = false;

		if(is_array($header))
		{
			$code = static::code($header);

			if(is_int($from) && is_int($to) && $code >= $from && $code <= $to)
			$return = true;
		}

		return $return;
	}


	// isCodeIn
	// retourne vrai si le code se trouve dans le groupe (la centaine) donné en argument
	public static function isCodeIn($value,$header):bool
	{
		$return = false;

		if(is_int($value))
		{
			$from = (int) (floor($value / 100) * 100);
			$to = ($from + 99);
			$return = static::isCodeBetween($from,$to,$header);
		}

		return $return;
	}


	// isContentType
	// retourne vrai si le tableau header contient le content-type donné en argument
	// supporte le content type exact, le content type short et le mime
	public static function isContentType($value,$header):bool
	{
		$return = false;

		if(is_string($value) && is_array($header))
		{
			$contentType = static::contentType($header,0);

			if(!empty($contentType))
			{
				if($value === $contentType)
				$return = true;

				else
				{
					$parsedContentType = static::parseContentType($contentType,false);
					$mimedContentType = static::parseContentType($contentType,true);

					if(in_array($value,[$parsedContentType,$mimedContentType],true))
					$return = true;
				}
			}
		}

		return $return;
	}


	// isHttp1
	// retourne vrai si le protocol est http1
	public static function isHttp1($value):bool
	{
		return (in_array(static::protocol($value),['HTTP/1','HTTP/1.0','HTTP/1.1'],true))? true:false;
	}


	// isHttp2
	// retourne vrai si le protocol est http2
	public static function isHttp2($value):bool
	{
		return (in_array(static::protocol($value),['HTTP/2','HTTP/2.0'],true))? true:false;
	}


	// keyCase
	// change la case d'une clé header
	public static function keyCase(string $key):string
	{
		return ucwords(strtolower($key),'-');
	}


	// parse
	// parse un tableau header
	// la clé status est ramené au début, et il ne peut y en avoir qu'une
	// peut retourner un tableau multidimensionnel
	public static function parse(array $array,array $option):array
	{
		$return = [];
		$separator = static::getSeparator(1,$option['explode']);
		$statusKey = static::$config['status'];

		foreach ($array as $key => $value)
		{
			if(is_numeric($key) && is_string($value))
			{
				if(!empty($statusKey) && static::isStatus($value))
				$key = $statusKey;

				else
				{
					$keyValue = Str::explodekeyValue($separator,$value,$option['trim'],$option['clean']);
					if(!empty($keyValue))
					{
						$key = key($keyValue);
						$value = current($keyValue);
					}
				}
			}

			if(is_string($key) && (is_scalar($value) || Arr::isIndexed($value)))
			{
				if($key === $statusKey)
				{
					if(array_key_exists($key,$return))
					$return[$key] = $value;
					else
					$return = Arr::prepend($return,[$key=>$value]);
				}

				elseif(Arr::keyExists($key,$return,static::getSensitive()))
				{
					$target = Arr::ikey($key,$return);

					if(is_scalar($return[$target]))
					$return[$target] = [$return[$target]];

					if(is_array($return[$target]))
					$return[$target] = Arr::append($return[$target],$value);
				}

				else
				$return[$key] = $value;
			}
		}

		return $return;
	}


	// parseStatus
	// parse un header status à partir d'une chaîne ou d'un tableau header
	// retourne un tableau à 3 entrées protocol, code et text
	public static function parseStatus($value):?array
	{
		$return = null;

		if(is_string($value) || is_array($value))
		{
			$value = (array) $value;

			foreach ($value as $v)
			{
				if(is_string($v) && static::isStatus($v))
				{
					$explode = Str::explodeTrim(' ',$v,3);

					if(count($explode) >= 2 && is_numeric($explode[1]))
					{
						$return = [];
						$return['protocol'] = strtoupper($explode[0]);
						$return['code'] = (int) $explode[1];
						$text = $explode[2] ?? static::statusTextFromCode($return['code']);
						$return['text'] = $text;
						break;
					}
				}
			}
		}

		return $return;
	}


	// parseContentType
	// parse une string content type
	// si mime est true, retourne simplement l'extension
	public static function parseContentType(string $return,bool $mime=true):string
	{
		$contentType = static::getSeparator(2);
		$explode = Str::explodeTrim($contentType,$return,2);

		if(!empty($explode[0]))
		{
			$return = $explode[0];

			if($mime === true)
			$return = Mime::toExtension($explode[0]) ?? $explode[0];
		}

		return $return;
	}


	// list
	// les options de list peuvent être null
	// explose et implose une valeur header
	// la clé status est ramené au début, et il ne peut y en avoir qu'une
	// retourne un tableau unidimensionnel avec clé numérique, parfait pour ajouter dans la fonction header
	public static function list($array,?array $option=null):array
	{
		$return = [];

		$option = static::option($option);
		$arr = static::arr($array,$option);
		$return = static::keyValue($arr,$option);

		return $return;
	}


	// keyValue
	// fait la liste, sans appeler arr avant
	// gère les case implode
	public static function keyValue(array $array,array $option):array
	{
		$return = [];
		$separator = static::getSeparator(1,$option['implode']);
		$statusKey = static::$config['status'];

		if($option['caseImplode'] !== null)
		$array = Arr::keysChangeCase($option['caseImplode'],$array);

		foreach ($array as $key => $value)
		{
			if(is_string($key) && (is_scalar($value) || is_array($value)))
			{
				$value = (array) $value;

				if(strtolower($key) === $statusKey)
				{
					$v = (is_array($value))? Arr::valueLast($value):$value;
					if(is_scalar($v))
					{
						$v = Str::cast($v);
						array_unshift($return,$v);
					}
				}

				else
				{

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


	// prepareArr
	// prépare un array dans la méthode arr
	public static function prepareArr(array $value,?array $option=null):array
	{
		return $value;
	}


	// prepareStr
	// prépare une string dans la méthode arr
	public static function prepareStr(string $value,?array $option=null):array
	{
		return static::explodeStr($value);
	}


	// explodeStr
	// explode une string header
	// retourne un tableau unidimensionnel avec clés numérique
	public static function explodeStr(string $value,?array $option=null):array
	{
		return Str::lines($value,true);
	}


	// setMerge
	// permet de merge une valeur sur un clé existante de assoc
	// ne supporte pas multi-niveau
	// si la clé n'existe pas, c'est simplement un set
	// sinon un tableau est formé avec les deux valeurs
	public static function setMerge($key,$value,$assoc,?array $option=null):array
	{
		return static::arr(Arr::setMerge($key,$value,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// setsMerge
	// permet de merge plusieurs valeurs sur des clés existantes de assoc
	// ne supporte pas multi-niveau
	// si les clés n'existent pas, c'est simplement un set
	// sinon un tableau est formé avec les différentes valeurs
	public static function setsMerge(array $values,$assoc,?array $option=null):array
	{
		return static::arr(Arr::setsMerge($values,static::arr($assoc,$option),static::getSensitive()),$option);
	}


	// prepareContentType
	// prépare une valeur content type à partir d'une extension
	// le charset est automatiquement ajouté si le content type est de type text
	public static function prepareContentType(string $value,bool $charset=true):?string
	{
		$return = null;

		if(!empty($value))
		{
			$return = Mime::fromExtension($value) ?? $value;

			if($charset === true && Str::isStart('text',$return) && strpos($return,'charset') === false)
			{
				$charset = Encoding::getCharset();
				$separator = static::getSeparator(2);
				if(!empty($separator))
				$return .= $separator.' charset='.$charset;
			}
		}

		return $return;
	}


	// code
	// retourne le code status en provenance d'un int, string ou array
	public static function code($value):?int
	{
		$return = null;

		if(is_int($value))
		$return = $value;

		else
		{
			$status = static::parseStatus($value);
			if(!empty($status))
			$return = $status['code'];
		}

		return $return;
	}


	// protocol
	// retourne le protocol http à partir d'une int, string ou array
	// si input est un int, alors utiliser server::httpProtocol
	public static function protocol($value=null):?string
	{
		$return = null;

		if(is_int($value))
		$return = Server::httpProtocol();

		else
		{
			$status = static::parseStatus($value);
			if(!empty($status))
			$return = $status['protocol'];
		}

		return $return;
	}


	// statusText
	// retourne le texte relié à un code status en provenance d'un int, string ou array
	public static function statusText($value):?string
	{
		$return = null;

		if(is_int($value))
		$return = static::statusTextFromCode($value);

		else
		{
			$status = static::parseStatus($value);
			if(!empty($status))
			$return = $status['text'];
		}

		return $return;
	}


	// statusTextFromCode
	// retourne le texte relié à un code status en provenance d'un code
	public static function statusTextFromCode(int $value):?string
	{
		$return = null;
		$code = static::code($value);
		if(!empty($code) && array_key_exists($code,Lang\En::$config['header']['responseStatus']))
		$return = Lang\En::$config['header']['responseStatus'][$code];

		return $return;
	}


	// codeFromStatusText
	// retourne le code à partir d'un texte de statut de réponse
	public static function codeFromStatusText(string $value):?int
	{
		$return = null;

		if(in_array($value,Lang\En::$config['header']['responseStatus'],true))
		$return = array_search($value,Lang\En::$config['header']['responseStatus'],true);

		return $return;
	}


	// getResponseStatus
	// retourne le tableau de statut de réponse, mergé avec celui de lang au besoin
	// seul méthode à utiliser lang, toutes les autres méthodes utilisent uniquemment les headers et status text en anglais
	public static function getResponseStatus(?string $lang=null):array
	{
		return Arr::plus(Lang\En::$config['header']['responseStatus'],Lang::headerResponseStatus(null,$lang));
	}


	// status
	// retourne la string header status à partir d'une int, string ou array
	public static function status($value):?string
	{
		$return = null;
		$status['protocol'] = static::protocol($value);
		$status['code'] = static::code($value);
		$status['text'] = static::statusText($value);
		$return = static::makeStatus($status);

		return $return;
	}


	// makeStatus
	// retorne la string header status à partir d'un tableau
	public static function makeStatus(array $value):?string
	{
		$return = null;

		if(Arr::keysAre(['protocol','code','text'],$value) && is_int($value['code']) && is_string($value['text']))
		$return = $value['protocol'].' '.$value['code'].' '.$value['text'];

		return $return;
	}


	// contentType
	// retourne le content type à partir d'un tableau header list ou assoc
	// si la variable parse n'est pas vide, le content type est envoyé dans parseContentType
	// si la variable parse est 2, la valeur mime est true dans parseContentType
	// si parse est false, parse devient 1 (enlève le charset)
	// si parse est true, parse devient 0 (garde le charset)
	public static function contentType(array $header,$parse=1):?string
	{
		$return = static::get('Content-Type',$header);

		if(is_array($return))
		$return = current($return);

		if(is_string($return) && strlen($return))
		{
			if($parse === false)
			$parse = 1;

			elseif($parse === true)
			$parse = 0;

			if(!empty($parse))
			$return = static::parseContentType($return,($parse === 2)? true:false);
		}

		return $return;
	}


	// contentLength
	// retourne le content length à partir d'un tabealu header list ou assoc
	// cast le retour en int
	public static function contentLength(array $header):?int
	{
		$return = null;
		$contentLength = static::get('Content-Length',$header);

		if(is_numeric($contentLength))
		$return = (int) $contentLength;

		return $return;
	}


	// setContentType
	// change la valeur du content type dans le tableau de headers
	// retourne un tableau header assoc
	public static function setContentType(string $value,array $return=[]):array
	{
		return static::set('Content-Type',static::prepareContentType($value),$return);
	}


	// setProtocol
	// change le protocol du header status dans le tableau
	// il doit déjà y avoir une entrée status - garde code et text
	public static function setProtocol($value,array $return):array
	{
		$status = static::parseStatus($return);
		if(!empty($status) && (is_string($value) || is_float($value)))
		{
			if(is_string($value))
			$status['protocol'] = $value;

			elseif(is_numeric($value))
			{
				$value = Str::cast($value);
				$status['protocol'] = 'HTTP/'.$value;
			}

			if(!empty($status['protocol']))
			$return = static::setStatus(static::makeStatus($status),$return);
		}

		return $return;
	}


	// setCode
	// comme setStatus mais s'il y a un protocol existant dans le tableau de réponse, la méthode le garde
	// sinon envoi à setStatus
	public static function setCode(int $value,array $return=[]):array
	{
		$status = static::parseStatus($return);
		if(!empty($status))
		{
			$status['code'] = $value;
			$status['text'] = static::statusTextFromCode($value);

			$return = static::setStatus(static::makeStatus($status),$return);
		}

		else
		$return = static::setStatus($value,$return);

		return $return;
	}


	// setStatusText
	// change le texte du code du header status dans le tableau
	// il doit déjà y avoir une entrée status - garde protocole et code
	public static function setStatusText(string $value,array $return):array
	{
		$status = static::parseStatus($return);
		if(!empty($status))
		{
			$status['text'] = $value;
			$return = static::setStatus(static::makeStatus($status),$return);
		}

		return $return;
	}


	// setStatus
	// change le header status du tableau
	// accepte un int, string ou array
	public static function setStatus($value,array $return=[]):array
	{
		$status = static::status($value);
		$statusKey = static::$config['status'];

		if(!empty($status) && !empty($statusKey))
		$return = static::set($statusKey,$status,$return);

		return $return;
	}


	// ok
	// change le code du tableau header pour 200
	public static function ok(array $return=[]):array
	{
		return static::setCode(200,$return);
	}


	// moved
	// change le code du tableau header pour 301 ou 302 ou un autre code
	// par défaut 301
	public static function moved($code=null,array $return=[]):array
	{
		if($code === true || $code === null)
		$code = 301;

		elseif($code === false)
		$code = 302;

		if(is_int($code))
		$return = static::setCode($code,$return);

		return $return;
	}


	// notFound
	// change le code du tableau header pour 404
	public static function notFound(array $return=[]):array
	{
		return static::setCode(404,$return);
	}


	// redirect
	// crée une redirection dans le tableau header
	// permanent permet de spécifier si le code est 301 ou 302
	public static function redirect(string $value,$code=null,array $return=[]):array
	{
		$return = static::moved($code,$return);
		$return = static::set('Location',$value,$return);

		return $return;
	}


	// download
	// crée un téléchargement dans le tableau header à partir d'un tableau resource::responseMeta
	public static function download(array $value,array $return=[]):array
	{
		if(Arr::keysExists(['mime','size','basename'],$value))
		{
			$return = static::setContentType($value['mime'],$return);
			$return = static::sets([
				'Content-Transfer-Encoding'=>'binary',
				'Content-Description'=>'File Transfer',
				'Content-Length'=>$value['size'],
				'Content-Disposition'=>'attachment; filename="'.$value['basename'].'"'
			],$return);
		}

		return $return;
	}


	// toScreen
	// crée un affichage dans le tableau header à partir d'un tableau resource::responseMeta
	public static function toScreen(array $value,array $return=[]):array
	{
		if(Arr::keysExists(['mime','size'],$value))
		{
			$return = static::setContentType($value['mime'],$return);
			$return = static::sets([
				'Content-Length'=>$value['size'],
				'Content-Disposition'=>'inline; filename="'.$value['basename'].'"'
			],$return);
		}

		return $return;
	}


	// fingerprint
	// retourne un fingerprint sha1 des entêtes de requêtes
	public static function fingerprint(array $headers,array $keys):?string
	{
		$return = null;
		$fingerprint = [];

		foreach ($keys as $key)
		{
			if(is_string($key))
			{
				$header = Arr::get($key,$headers,false);
				if(is_string($header))
				$fingerprint[] = $header;
			}
		}

		if(!empty($fingerprint))
		{
			$string = implode('-',$fingerprint);
			$return = Crypt::sha($string,1);
		}

		return $return;
	}
}

// config
Header::__config();
?>