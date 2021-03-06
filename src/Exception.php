<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// exception
// class with methods a layer over the native PHP exception functions and handler
final class Exception extends Root
{
    // config
    protected static array $config = [
        'separator'=>[' -> ',', ',': '] // séparateur si le message est un tableau
    ];


    // setHandler
    // lie une callable comme gestionnaire d'exceptions
    // si la valeur passé est null, le handler est remis à son état initial
    final public static function setHandler(?callable $value=null):void
    {
        set_exception_handler($value);
    }


    // restoreHandler
    // remet le handler à ce qu'il était avant le dernière appel à set
    final public static function restoreHandler():bool
    {
        return restore_exception_handler();
    }


    // message
    // prépare le message
    // la valeur est passé dans obj cast préalablement
    // une entrée du message tableau associatif ou à multiple niveaux est passé dans json encode
    final public static function message($value):string
    {
        $return = '';
        $value = Obj::cast($value);

        if(is_scalar($value))
        $value = (string) $value;

        if(is_string($value))
        $value = (array) $value;

        if(is_array($value))
        {
            foreach ($value as $k => $v)
            {
                if(is_array($v) && (Arr::isAssoc($v) || Arrs::is($v)))
                $value[$k] = Json::encode($v);
            }

            $value = Arrs::implode(self::$config['separator'],$value,true,true);
        }

        if(is_string($value))
        $return = $value;

        return $return;
    }


    // classFunction
    // prepend les clés classe et function au tableau pour le message
    // possible de spécifier une classe statique qui prend le dessus sur celle de trace
    final public static function classFunction($trace,?string $staticClass,array $return):array
    {
        $class = null;
        $function = null;

        if(is_array($trace) && array_key_exists('class',$trace) && array_key_exists('function',$trace))
        {
            $class = $trace['class'];
            $function = $trace['function'];
        }

        if(is_string($staticClass))
        $class = $staticClass;

        if(!empty($function))
        array_unshift($return,$function);

        if(!empty($class))
        array_unshift($return,$class);

        return $return;
    }
}
?>