<?php
declare(strict_types=1);
namespace Quid\Base;

// _root
trait _root
{
	// trait
	use _config, _cacheStatic, _cacheFile;


	// classFqcn
	// retourne le fqcn de la classe
	public static function classFqcn():string
	{
		return static::class;
	}


	// classNamespace
	// retourne le namespace de la classe
	public static function classNamespace():string
	{
		return Fqcn::namespace(static::class);
	}


	// classRoot
	// retourne le root du namespace de la classe
	public static function classRoot():string
	{
		return Fqcn::root(static::class);
	}


	// className
	// retourne le nom de classe, sans le namespace
	public static function className(bool $lcfirst=false):string
	{
		$return = Fqcn::name(static::class);

		if($lcfirst === true)
		$return = lcfirst($return);

		return $return;
	}


	// classParents
	// retourne un tableau avec tous les parents de la classe
	// possible d'inclure la classe si self est true, et de pop des éléments à la fin
	public static function classParents(bool $self=false,?int $pop=null):array
	{
		return Classe::parents(static::class,$self,$pop);
	}


	// classHelp
	// retourne un tableau d'aide sur la classe
	public static function classHelp(bool $deep=true):array
	{
		return Classe::info(static::class,$deep);
	}


	// classIsCallable
	// retourne vrai si la classe peut appeler la callable closure ou dans un array
	// la validation est très stricte pour éviter des bogues de mauvais call
	// retourne vrai aux méthodes protégés pour la classe courante
	public static function classIsCallable($value):bool
	{
		return ($value instanceof \Closure || (Call::isSafeStaticMethod($value) && is_callable($value)))? true:false;
	}


	// classFile
	// retourne le chemin du fichier de la classe courante
	// pourrait retourner null
	public static function classFile():?string
	{
		return Autoload::getFilePath(static::class,false);
	}


	// classDir
	// retourne le chemin du directoire de la classe courante
	// pourrait retourner null
	public static function classDir():?string
	{
		return Autoload::getDirPath(static::classNamespace(),false);
	}
}
?>