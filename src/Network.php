<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// network
class Network extends Root
{
	// config
	public static $config = [];


	// isOnline
	// retourne vrai si le hostname est accessible ua port spécifié
	public static function isOnline(string $hostname,int $port=80,int $timeout=2,&$errno=null,&$errstr=null):bool
	{
		$return = false;
		$ping = static::ping($hostname,$port,$timeout,$errno,$errstr);

		if(is_numeric($ping))
		$return = true;

		return $return;
	}


	// hasDns
	// retourne vrai si un enregistrement dns existe
	public static function hasDns(string $hostname,string $type='MX'):bool
	{
		return checkdnsrr($hostname,$type);
	}


	// ping
	// essaie de rejoindre un hostname sur un port
	// utilise fsocketopen, donc n'est pas si précis
	// retour le délai d'attente
	// si le host n'est pas joignable une erreur est généré mais caché par le gestionnaire d'erreur
	public static function ping(string $hostname,int $port=80,int $timeout=10,&$errno=null,&$errstr=null):?float
	{
		$return = null;
		$microtime = Date::microtime();
		$socket = @fsockopen($hostname,$port,$errno,$errstr,$timeout);

		if(!empty($socket))
		{
			$return = Debug::speed($microtime);
			Res::close($socket);
		}

		return $return;
	}


	// dns
	// retourne les enregistrements dns pour un hostname
	public static function dns(string $hostname,int $type=DNS_ALL):array
	{
		return dns_get_record($hostname,$type);
	}


	// mx
	// retourne les enregistrements mx pour un hostname
	public static function mx(string $hostname,bool $weight=true):array
	{
		$return = [];
		$weights = [];

		if(getmxrr($hostname,$return,$weights))
		{
			if($weight === true)
			$return = ['mx'=>$return,'weight'=>$weights];
		}

		return $return;
	}


	// getHostname
	// retourne un hostname à partir d'un ip
	public static function getHostname(string $ip):?string
	{
		$return = null;

		if(Validate::isIp($ip))
		{
			$hostname = gethostbyaddr($ip);
			if(is_string($hostname) && $hostname !== $ip)
			$return = $hostname;
		}

		return $return;
	}


	// getIp
	// retourne un ip à partir d'un hostname
	public static function getIp(string $hostname):?string
	{
		$return = null;

		$ip = gethostbyname($hostname);
		if(Validate::isIp($ip))
		$return = $ip;

		return $return;
	}


	// getProtocolNumber
	// retourne un numéro de protocole à partir de son nom
	public static function getProtocolNumber(string $name):?int
	{
		$return = null;

		$no = getprotobyname($name);
		if(is_int($no))
		$return = $no;

		return $return;
	}


	// getProtocolName
	// retourne un nom de protocole à partir de son numéro
	public static function getProtocolName(int $no):?string
	{
		$return = null;

		$name = getprotobynumber($no);
		if(is_string($name))
		$return = $name;

		return $return;
	}


	// getServiceName
	// retourne un nom de service en fonction de son port et protocol
	public static function getServiceName(int $port,string $protocol='tcp'):?string
	{
		$return = null;

		$name = getservbyport($port,$protocol);
		if(is_string($name) && !empty($name))
		$return = $name;

		return $return;
	}


	// getServicePort
	// retourne un port de service en fonction de son nom et protocol
	public static function getServicePort(string $name,string $protocol='tcp'):?int
	{
		$return = null;

		$port = getservbyname($name,$protocol);
		if(is_int($port))
		$return = $port;

		return $return;
	}
}
?>