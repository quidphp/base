<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// json
class Json extends Assoc
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'encode'=>JSON_INVALID_UTF8_IGNORE | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES, // flag encode
			'decode'=>JSON_INVALID_UTF8_IGNORE | JSON_BIGINT_AS_STRING, // flag decode
			'depth'=>512, // depth pour encode et decode
			'assoc'=>true, // option assoc pour decode
			'case'=>null, // les clés sont ramenés dans cette case dans arr
			'sort'=>null] // les clés sont sort
	];


	// is
	// retourne vrai si la chaîne est du json
	public static function is($value):bool
	{
		return (is_string($value) && static::decode($value) !== null)? true:false;
	}


	// isEmpty
	// retourne vrai si la chaîne est du json mais vide
	public static function isEmpty($value):bool
	{
		return (is_string($value) && ($json = static::decode($value)) !== null && empty($json))? true:false;
	}


	// isNotEmpty
	// retourne vrai si la chaîne est du json non vide
	public static function isNotEmpty($value):bool
	{
		return (is_string($value) && ($json = static::decode($value)) !== null && !empty($json))? true:false;
	}


	// encode
	// encode une variable en json
	// option à null enlève les options, si option est set remplace les options par défaut
	// note: json_encode retourne false si une erreur survient, à ce moment une erreur est déclenché
	public static function encode($value,?int $flag=null,?int $depth=null):?string
	{
		$return = null;
		$option = static::option();

		$flag = ($flag === null)? $option['encode']:$flag;
		$depth = ($depth === null)? $option['depth']:$depth;
		$return = json_encode($value,$flag,$depth);

		return $return;
	}


	// encodeOption
	// encode une variable en json
	// option append les options par défaut
	public static function encodeOption($value,int $flag,?int $depth=null):?string
	{
		$return = null;
		$option = static::option();
		$flag = $option['encode'] | $flag;
		$return = static::encode($value,$flag,$depth);

		return $return;
	}


	// encodePretty
	// encode une variable en json
	// append json_pretty_print aux options par défaut
	public static function encodePretty($value,int $depth=null):?string
	{
		$return = null;
		$option = static::option();
		$flag = $option['encode'] | JSON_PRETTY_PRINT;
		$return = static::encode($value,$flag,$depth);

		return $return;
	}


	// encodeSpecialchars
	// encode en json et envoie la string dans specialchars
	public static function encodeSpecialchars($value,?int $flag=null,?int $depth=null):?string
	{
		$return = '';
		$json = static::encode($value,$flag,$depth);

		if(is_string($json))
		$return = Html::specialchars($json);

		return $return;
	}


	// encodeVar
	// encode une valeur et retourne la dans une variable javascript
	public static function encodeVar(string $var,$value,?int $flag=null,?int $depth=null):?string
	{
		$return = null;
		$value = static::encode($value,$flag,$depth);

		if(is_string($value))
		$return = static::var($var,$value);

		return $return;
	}


	// var
	// écrit une valeur js dans une variable javascript
	public static function var(string $var,string $value):string
	{
		$return = $var;
		$return .= ' = ';
		$return .= $value;
		$return .= ';';

		return $return;
	}


	// decode
	// decode une chaine json
	// option à null enlève les options
	// note: json_decode retourne false si une erreur survient
	public static function decode(string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
	{
		$return = null;
		$option = static::option();

		$assoc = ($assoc === null)? $option['assoc']:$assoc;
		$depth = ($depth === null)? $option['depth']:$depth;
		$flag = ($flag === null)? $option['decode']:$flag;

		$return = json_decode($value,$assoc,$depth,$flag);

		return $return;
	}


	// decodeKeys
	// decode une chaîne json et retourne les clés demandés
	public static function decodeKeys(array $keys,string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
	{
		$return = null;
		$decode = static::decode($value,$assoc,$flag,$depth);

		if(is_array($decode))
		$return = Arr::gets($keys,$decode);

		return $return;
	}


	// decodeKeysExists
	// decode une chaîne json et retourne le tableau seulement si les clés existent
	public static function decodeKeysExists(array $keys,string $value,?bool $assoc=null,?int $flag=null,?int $depth=null)
	{
		$return = null;
		$decode = static::decode($value,$assoc,$flag,$depth);

		if(is_array($decode) && Arr::keysExists($keys,$decode))
		$return = $decode;

		return $return;
	}


	// error
	// retourne les informations sur la dernière erreur json
	public static function error():array
	{
		return ['code'=>json_last_error(),'msg'=>json_last_error_msg()];
	}


	// arr
	// explose une string json
	// retourne tableau vide si après decode ce n'est pas un tableau
	public static function arr($value,?array $option=null):array
	{
		$return = [];
		$option = static::option($option);

		if(is_scalar($value))
		$value = static::decode($value,$option['assoc'],$option['decode'],$option['depth']);

		if(is_array($value))
		{
			$return = Arr::trimClean($value,$option['trim'],$option['trim'],$option['clean']);

			if($option['case'] !== null)
			$return = Arr::keysChangeCase($option['case'],$return);

			if($option['sort'] !== null)
			$return = Arr::sort($return,$option['sort']);
		}

		return $return;
	}


	// onSet
	// helper pour une méthode onSet de colonne
	// encode en json si array ou objet
	public static function onSet($return)
	{
		if(is_array($return) || is_object($return))
		$return = static::encode($return);

		return $return;
	}


	// onGet
	// helper pour une méthode onGet de colonne
	// décode de json si scalar
	public static function onGet($return)
	{
		if(is_scalar($return))
		$return = static::decode($return);

		return $return;
	}
}

// config
Json::__config();
?>