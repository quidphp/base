<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _root
// trait that provides some basic fqcn methods
trait _root
{
    // trait
    use _init;
    use _config;
    use _cacheStatic;
    use _cacheFile;


    // classFqcn
    // retourne le fqcn de la classe
    final public static function classFqcn():string
    {
        return static::class;
    }


    // classNamespace
    // retourne le namespace de la classe
    final public static function classNamespace():string
    {
        return Fqcn::namespace(static::class);
    }


    // className
    // retourne le nom de classe, sans le namespace
    final public static function className(bool $lcfirst=false):string
    {
        $return = Fqcn::name(static::class);

        if($lcfirst === true)
        $return = lcfirst($return);

        return $return;
    }


    // classParents
    // retourne un tableau avec tous les parents de la classe
    // possible d'inclure la classe si self est true, et de pop des éléments à la fin
    final public static function classParents(bool $self=false,?int $pop=null):array
    {
        return Classe::parents(static::class,$self,$pop);
    }


    // classHelp
    // retourne un tableau d'aide sur la classe
    final public static function classHelp(bool $deep=true):array
    {
        return Classe::info(static::class,$deep);
    }


    // classIsCallable
    // retourne vrai si la classe peut appeler la callable closure ou dans un array
    // la validation est très stricte pour éviter des bogues de mauvais call
    // retourne vrai aux méthodes protégés pour la classe courante
    final public static function classIsCallable($value):bool
    {
        return ($value instanceof \Closure || (Call::isSafeStaticMethod($value) && is_callable($value)))? true:false;
    }


    // classFile
    // retourne le chemin du fichier de la classe courante
    // pourrait retourner null
    final public static function classFile():?string
    {
        return Autoload::getFilePath(static::class,false);
    }


    // classDir
    // retourne le chemin du directoire de la classe courante
    // pourrait retourner null
    final public static function classDir():?string
    {
        return Autoload::getDirPath(static::classNamespace(),false);
    }


    // classLines
    // retourne un tableau avec les lignes du fichier ou null
    final public static function classLines($offset=true,$length=true):?array
    {
        $return = null;
        $file = static::classFile();

        if(!empty($file))
        $return = File::lines($offset,$length,$file);

        return $return;
    }


    // classSubCount
    // count le nombre d'occurence d'un terme dans le code de la classe
    // peut retourner null si le fichier de la classe n'est pas trouvable
    final public static function classSubCount(string $value):?int
    {
        $return = null;
        $file = static::classFile();

        if(!empty($file))
        $return = File::subCount($value,$file);

        return $return;
    }


    // classTest
    // retourne la classe à utiliser pour tester la classe courante
    // cette méthode peut être étendu
    public static function classTest():?string
    {
        $return = false;
        $root = Fqcn::root(static::class);

        if(!empty($root))
        $return = Fqcn::spliceRoot(static::class,[$root,'Test']);

        return $return;
    }


    // classTestTrigger
    // méthode pour lancer les tests sur la classe
    final public static function classTestTrigger(array $data)
    {
        $return = false;
        $class = static::classTest();

        if(!empty($class) && class_exists($class,true))
        {
            $return = $class::trigger($data);

            if($return === true)
            $return = $class::classSubCount('assert(');
        }

        return $return;
    }
}
?>