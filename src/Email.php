<?php
declare(strict_types=1);
namespace Quid\Base;

// email
class Email extends Root
{
	// config
	public static $config = array(
		'active'=>true, // permet d'activer ou non l'envoie d'email
		'message'=>array( // contenu par défaut pour un tableau message
			'priority'=>null,
			'xmailer'=>array(self::class,'xmailer'),
			'mimeVersion'=>'1.0',
			'charset'=>'UTF-8',
			'contentType'=>'txt',
			'date'=>null,
			'to'=>null,
			'cc'=>null,
			'bcc'=>null,
			'replyTo'=>null,
			'subject'=>null,
			'body'=>null,
			'from'=>null,
			'header'=>null),
		'contact'=>array('to','cc','bcc','replyTo'), // champs contact qui supportent multiples addresses, from n'accepte que un
		'headers'=>array( 
			'default'=>array(), // headers par défaut à ajouter à chaque message
			'message'=>array( // nom des headers pour le champ additional_headers
				'mimeVersion'=>'MIME-Version',
				'priority'=>'X-Priority',
				'xmailer'=>'X-Mailer',
				'contentTypeCharset'=>'Content-Type',
				'date'=>'Date',
				'cc'=>'Cc',
				'bcc'=>'Bcc',
				'replyTo'=>'Reply-To',
				'from'=>'From')),
		'test'=>array( // contenu par défaut pour un message test
			'destination'=>array(
				'to'=>null,
				'from'=>null,
				'cc'=>null,
				'bcc'=>null),
			'message'=>array(
				'subject'=>'Test',
				'body'=>'Test')),
		'contentType'=>array( // différents contentType supportés, supporte le remplacement par clé
			1=>'text/plain',
			2=>'text/html'),
	);
	
	
	// is
	// retourne vrai si la valeur donné est un courriel
	// strpos sur un slash car utilisé par base/attr, pour accélérer
	public static function is($value):bool
	{
		$return = false;
		
		if(is_string($value) && strpos($value,'/') === false)
		$return = Validate::isEmail($value);
		
		return $return;
	}
	
	
	// isActive
	// retourne vrai si l'envoie de courriel est activé
	public static function isActive():bool 
	{
		return (static::$config['active'] === true)? true:false;
	}
	
	
	// arr
	// explode une adrese courriel et retourne le nom et host
	public static function arr(string $value):?array 
	{
		$return = null;
		
		if(static::is($value))
		{
			$explode = explode("@",$value);
			if(count($explode) === 2)
			$return = array('name'=>$explode[0],'host'=>$explode[1]);
		}
		
		return $return;
	}
	
	
	// name
	// retourne seulement le nom du courriel (avant le @)
	public static function name(string $value):?string
	{
		$return = null;
		$arr = static::arr($value);
		
		if(!empty($arr))
		$return = $arr['name'];
		
		return $return;
	}
	
	
	// host
	// retourne seulement l'hôte du courriel (après le @)
	public static function host(string $value):?string
	{
		$return = null;
		$arr = static::arr($value);
		
		if(!empty($arr))
		$return = $arr['host'];
		
		return $return;
	}
	
	
	// send
	// permet d'envoyer un courriel à partir d'un tableau message
	// to peut avoir plusieurs destinataires
	// si l'envoie de courriel est désactivé globalement, retourne true comme si le message avait été bien envoyé
	public static function send(array $value):bool 
	{
		$return = false;
		$message = static::prepareMessage($value);

		if(!empty($message))
		{
			$mb = Encoding::isCharsetMb($message['charset']);
			$to = static::prepareAddress($message['to']);
			$subject = $message['subject'];
			$body = $message['body'];
			$headers = Header::str($message['header']);
			
			if(static::isActive())
			{	
				if($mb === true)
				$return = mb_send_mail($to,$subject,$body,$headers);
				else
				$return = mail($to,$subject,$body,$headers);
			}
			
			else
			$return = true;
		}
		
		return $return;
	}
	
	
	// sendTest
	// permet d'envoyer un courriel test
	public static function sendTest(?array $value=null):bool 
	{
		return static::send(static::prepareTestMessage($value));
	}
	
	
	// sendLoop
	// permet d'envoyer plusieurs messages à partir d'un tableau multidimensionnel
	public static function sendLoop(array $values):array 
	{
		$return = array();
		
		foreach ($values as $key => $value) 
		{
			if(is_array($value))
			$return[$key] = static::send($value);
		}
		
		return $return;
	}
	
	
	// prepareMessage
	// reformatte un tableau de message
	// les champs contact peuvent recevoir multiples destinataires, sauf from qui n'accepte que un
	// retourne le tableau ou null s'il n'y pas to, subject, body, from et un contentType
	public static function prepareMessage(array $value,bool $headerMessage=true):?array
	{
		$return = null;
		$value = Obj::cast($value);
		$message = Call::ableArrs(static::$config['message']);
		$value = Arr::replace($message,$value);
		$value['from'] = static::address($value['from']);
		$value['date'] = (is_int($value['date']))? $value['date']:Date::timestamp();
		$value = Arr::replace($value,static::prepareContentTypeCharset($value['contentType'],$value['charset']));
		
		foreach (static::$config['contact'] as $v) 
		{
			if(!empty($value[$v]))
			$value[$v] = static::addresses($value[$v]);
		}

		if(in_array($value['contentType'],static::$config['contentType'],true) && !empty($value['charset']) && is_string($value['charset']))
		{
			if(!empty($value['to']) && !empty($value['from']) && is_string($value['subject']) && is_string($value['body']))
			{
				$return = $value;
				$return['header'] = static::prepareHeader($return,$headerMessage);
			}
		}

		return $return;
	}
	
	
	// prepareTestMessage
	// prépare un tableau message test
	// destination de config a priorité sur tout
	public static function prepareTestMessage(?array $value=null) 
	{
		return Arr::replace(static::$config['test']['message'],$value,static::$config['test']['destination']);
	}
	
	
	// prepareContentTypeCharset
	// prépare le contentType, le charset et le contentTypeCharset
	// est utilisé dans prepareMessage
	public static function prepareContentTypeCharset($value=null,?string $charset=null):array
	{
		$return = array();
		$charset = $charset ?? Encoding::getCharset();
		$contentType = 'txt';
		$contentTypes = static::$config['contentType'];
		$showCharset = Encoding::isCharsetMb($charset);
		
		if(!empty($value))
		{
			if(is_int($value) && array_key_exists($value,$contentTypes))
			$contentType = $contentTypes[$value];
			
			elseif(is_string($value))
			$contentType = $value;
		}
		
		$return['charset'] = $charset;
		$return['contentType'] = Header::prepareContentType($contentType,false);
		$return['contentTypeCharset'] = Header::prepareContentType($contentType,$showCharset);
		
		return $return;
	}
	
	
	// prepareHeader
	// prepare le tableau d'en-tête
	// le tableau value doit avoir été préparé au préalable à partir de la méthode prepareMessage
	public static function prepareHeader(array $value,bool $headerMessage=true):array 
	{
		$return = array();
		
		if(!empty(static::$config['headers']['default']))
		$return = static::$config['headers']['default'];
		
		if(!empty($value['header']) && is_array($value['header']))
		$return = Arr::replace($return,$value['header']);
		
		if($headerMessage === true)
		{
			foreach (static::$config['headers']['message'] as $k => $v) 
			{
				if(array_key_exists($k,$value) && !empty($value[$k]))
				{
					if(in_array($k,static::$config['contact'],true) && is_array($value[$k]))
					$return[$v] = static::prepareAddress($value[$k],true);
					
					elseif($k === 'from')
					$return[$v] = static::prepareAddress($value[$k],false);
					
					elseif($k === 'date')
					$return[$v] = Date::rfc822($value[$k]);
					
					elseif(is_scalar($value[$k]))
					$return[$v] = $value[$k];
				}
			}
		}
		
		$return = Header::arr($return);
		
		return $return;
	}
	

	// prepareAddress
	// prépare une string avec une ou plusieurs adresses
	// les addresses doivent avoir été préparés au préalable via la méthode adresses
	// si multi est false, seule la première adresse sera retournée
	public static function prepareAddress(array $values,bool $multi=true):string 
	{
		$return = '';
		
		if(Arr::isUni($values))
		$values = array($values);
		
		foreach ($values as $value) 
		{
			if(is_array($value) && array_key_exists('email',$value) && is_string($value['email']) && array_key_exists('name',$value))
			{
				$string = static::addressStr($value['email'],$value['name']);
				
				if(!empty($string))
				{
					if(!empty($return))
					$return .= ", ";
					
					$return .= $string;
					
					if($multi === false)
					break;
				}
			}
		}
		
		return $return;
	}
	
	
	// addresses
	// prépare plusieurs adresses pour les champs compatibles avec mulitples destinaires
	// compatible avec un maximum de format input
	// retourne un tableau multidimensionnel
	public static function addresses($values):array 
	{
		$return = array();
		
		if(!is_array($values))
		$values = array($values);
		
		if(array_key_exists('email',$values))
		{
			$email = $values['email'];
			unset($values['email']);
			
			if(array_key_exists('name',$values))
			{
				$name = $values['name'];
				unset($values['name']);
				$values[$email] = $name;
			}
			
			else
			$values[] = $email;
		}
		
		foreach ($values as $key => $value) 
		{
			if(is_string($key))
			$value = array($key=>$value);
			
			$prepare = static::address($value);
			
			if(!empty($prepare))
			$return[] = $prepare;
		}
		
		return $return;
	}
	
	
	// address
	// prépare une adresse pour qu'elle soit compatible avec la méthode d'envoie courriel
	// compatible avec un maximum de format input
	// si le nom est null, retourne le nom à partir du courriel
	// retourne un tableau unidimensionnel ou null
	public static function address($value)
	{
		$return = null;

		if(is_string($value))
		$value = array($value=>null);
		
		if(is_array($value))
		{
			$email = null;
			$name = null;
			
			if(array_key_exists('email',$value))
			{
				$email = $value['email'];
				
				if(array_key_exists('name',$value) && is_string($value['name']))
				$name = $value['name'];
			}
			
			else
			{
				$k = key($value);
				$v = current($value);
				
				if(is_numeric($k) && is_string($v))
				$email = $v;
				
				elseif(is_string($k))
				{
					$email = $k;
					
					if(is_string($v))
					$name = $v;
				}
			}
			
			if(static::is($email))
			{
				$name = ($name === null)? static::name($email):$name;
				$return = array('email'=>$email,'name'=>$name);
			}
		}
		
		return $return;
	}
	
	
	// addressStr
	// génère une string avec arguments email et nom
	public static function addressStr(string $email,$name=null):string
	{
		$return = trim($email);
		
		if(is_string($name))
		$return = trim($name)." <".$return.">";

		return $return;
	}
	
	
	// xmailer
	// retourne le header xmailer
	public static function xmailer():string 
	{
		return 'PHP/'.Server::phpVersion()."|QUID/".Server::quidVersion();
	}
	
	
	// setTestTo
	// permet d'attribuer un to pour les courriels test
	// si value est true, utilise le email lié au serveur
	public static function setTestTo($value):void 
	{
		if($value === true)
		$value = Server::email();
		
		static::$config['test']['destination']['to'] = $value;
				
		return;
	}
	
	
	// setActive
	// active ou désactive l'envoie de courriel globalement
	public static function setActive(bool $value=true):void 
	{
		static::$config['active'] = $value;
		
		return;
	}
}
?>