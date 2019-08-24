<?php
declare(strict_types=1);
namespace Quid\Base;

// error
class Error extends Root
{
	// config
	public static $config = [];


	// setHandler
	// lie une callable comme gestionnaire d'erreur
	// si la valeur passé est null, le handler est remis à son état initial
	public static function setHandler(?callable $value=null,int $type=E_ALL | E_STRICT):void
	{
		set_error_handler($value,$type);

		return;
	}


	// restoreHandler
	// remet le handler à ce qu'il était avant le dernière appel à set
	public static function restoreHandler():bool
	{
		return restore_error_handler();
	}


	// last
	// retourne les informations sur la dernière erreur
	public static function last():?array
	{
		return error_get_last();
	}


	// clearLast
	// efface les informations sur la dernière erreur
	public static function clearLast():void
	{
		error_clear_last();

		return;
	}


	// log
	// log une erreur à l'endroit défini dans error_log
	public static function log($message):bool
	{
		return error_log(static::logPrepareMessage($message),0);
	}


	// logEmail
	// log une erreur et envoie par email
	public static function logEmail($message,string $email,string $headers=''):bool
	{
		$return = false;

		if(Validate::isEmail($email))
		$return = error_log(static::logPrepareMessage($message),1,$email,$headers);

		return $return;
	}


	// logFile
	// log une erreur dans un autre fichier que celui par défaut
	// accepte des chemins (pas besoin d'exister) ou des resources
	public static function logFile($message,$path):bool
	{
		$return = false;

		if(Res::is($path))
		$path = Res::uri($path);

		if(Finder::isWritableOrCreatable($path))
		$return = error_log(static::logPrepareMessage($message),3,$path);

		return $return;
	}


	// logPrepareMessage
	// prépare le message en fonction de la fonction error_log
	public static function logPrepareMessage($value):string
	{
		$return = '';

		if(is_scalar($value))
		$return = (string) $value;

		elseif(is_array($value))
		{
			foreach ($value as $v)
			{
				if(is_scalar($v))
				{
					$return .= (strlen($return))? ' ':'';
					$return .= (string) $v;
				}
			}
		}

		return $return;
	}


	// trigger
	// génère une erreur, le type est par défaut E_USER_ERROR
	// aucune erreur de générer si le tableau est vide ou si le message final n'est pas string
	public static function trigger($value,int $type=E_USER_ERROR):bool
	{
		$return = false;

		if(is_array($value) && !empty($value))
		$value = Arr::implodeTrimClean(' -> ',$value);

		if(is_string($value))
		$return = trigger_error($value,$type);

		return $return;
	}


	// triggers
	// tente de générer des erreurs à partir de différentes valeurs
	// aucune erreur de générer si le tableau est vide ou si le message final n'est pas string
	public static function triggers(...$values):bool
	{
		$return = false;

		foreach ($values as $value)
		{
			$return = static::trigger($value);

			if($return === true)
			break;
		}

		return $return;
	}


	// reporting
	// retourne le niveau actuel de error reporting
	public static function reporting():int
	{
		return error_reporting();
	}


	// getCodes
	// retourne le tableau de codes, mergé avec celui de lang au besoin
	public static function getCodes(?string $lang=null):array
	{
		return Arr::plus(Lang\En::$config['error']['code'],Lang::errorCode(null,$lang));
	}


	// code
	// cette fonctionne retourne le nom associé au code erreur de PHP
	public static function code(int $code,?string $lang=null):?string
	{
		$return = null;
		$codes = static::getCodes($lang);

		if(array_key_exists($code,$codes))
		$return = $codes[$code];

		return $return;
	}


	// init
	// initialise la prise en charge des erreurs
	public static function init():void
	{
		Uri::setNotFound([static::class,'trigger']);
		File::setNotFound([static::class,'trigger']);
		Obj::setCastError([static::class,'trigger']);

		return;
	}
}
?>