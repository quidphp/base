<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// call
// class with static methods to manage callables and callbacks
final class Call extends Root
{
    // config
    protected static array $config = [];


    // typecast
    // envoie à la méthode cast
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = self::cast($value);
        }
    }


    // cast
    // cast la variable dans une closure
    final public static function cast($value):\Closure
    {
        return fn() => $value;
    }


    // is
    // retourne vrai si la valeur est callable
    final public static function is($value):bool
    {
        return is_callable($value);
    }


    // isSafeArray
    // retourne vrai seulement si la valeur est un tableau callable, très stricte
    final public static function isSafeArray($value,bool $callable=false):bool
    {
        $return = self::isStaticMethod($value) || self::isDynamicMethod($value);

        if($return === true && $callable === true)
        $return = is_callable($value);

        return $return;
    }


    // isFunction
    // retourne vrai si la valeur est callable et function
    final public static function isFunction($value):bool
    {
        return is_string($value) && function_exists($value);
    }


    // isClosure
    // retourne vrai si la valeur est callable et closure
    final public static function isClosure($value):bool
    {
        return $value instanceof \Closure;
    }


    // isDynamicMethod
    // retourne vrai si la valeur est callable et dynamic method
    final public static function isDynamicMethod($value):bool
    {
        return is_array($value) && array_key_exists(0,$value) && array_key_exists(1,$value) && is_object($value[0]) && is_string($value[1]);
    }


    // isStaticMethod
    // retourne vrai si la valeur est callable et static method
    final public static function isStaticMethod($value):bool
    {
        return is_array($value) && array_key_exists(0,$value) && array_key_exists(1,$value) && is_string($value[0]) && strpos($value[0],'\\') > 0 && is_string($value[1]);
    }


    // type
    // retourne le type de callable
    final public static function type($value):?string
    {
        $return = null;

        if(self::is($value))
        {
            if(self::isFunction($value))
            $return = 'function';

            elseif(self::isClosure($value))
            $return = 'closure';

            elseif(self::isDynamicMethod($value))
            $return = 'dynamicMethod';

            elseif(self::isStaticMethod($value))
            $return = 'staticMethod';
        }

        return $return;
    }


    // able
    // fonction static pour appeler un callable
    // argument 0 = callable, tous les autres = un tableau d'argument
    final public static function able(callable $callable,...$arg)
    {
        return $callable(...$arg);
    }


    // loop
    // permet de loop un tableau et d'appeler toutes les callables
    final public static function loop(array $return):array
    {
        foreach ($return as $key => $value)
        {
            if(self::isCallable($value))
            $return[$key] = $value();
        }

        return $return;
    }


    // back
    // envoie un tableau et une clé
    // retourne null ou le résultat du callable si existant
    final public static function back($key,array $array,...$arg)
    {
        $return = null;

        if(Arr::isKey($key) && array_key_exists($key,$array) && self::is($array[$key]))
        $return = $array[$key](...$arg);

        return $return;
    }


    // dig
    // creuse dans un tableau et call toutes les méthodes
    // possible d'appeler seulement les safeArray, pas les autres méthodes
    final public static function dig(bool $onlySafeArray=false,array $return,...$args)
    {
        foreach ($return as $key => $value)
        {
            if($onlySafeArray === true && self::isSafeArray($value,true))
            $return[$key] = $value(...$args);

            elseif($onlySafeArray === false && self::is($value))
            $return[$key] = $value(...$args);

            elseif(is_array($value))
            $return[$key] = self::dig($onlySafeArray,$value,...$args);
        }

        return $return;
    }


    // staticClass
    // fonction static pour appeler une méthode statique dans une classe
    final public static function staticClass(string $class,string $method,...$arg)
    {
        return $class::$method(...$arg);
    }


    // staticClasses
    // permet de looper un tableau de classes et appelé la même méthode pour chaque
    // possible de fournir des arguments
    final public static function staticClasses(array $classes,string $method,...$arg):array
    {
        $return = [];

        foreach ($classes as $class)
        {
            if(is_string($class))
            $return[$class] = self::staticClass($class,$method,...$arg);
        }

        return $return;
    }


    // bindTo
    // bind un objet à une closure et lance la closure
    // permet d'appeler les méthodes protégés à l'intérieeur d'un objet
    final public static function bindTo(object $obj,\Closure $closure,...$args)
    {
        return $closure->bindTo($obj,$obj)(...$args);
    }
}
?>