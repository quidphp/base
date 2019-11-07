<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// assert
// class with methods a layer over the native PHP assert functions
class Assert extends Root
{
    // config
    public static $config = [];


    // call
    // fait une assertion sur une callable
    final public static function call(callable $call,$extra=null):bool
    {
        return assert($call(),$extra);
    }


    // get
    // retourne la valeur d'une option
    final public static function get(int $key)
    {
        return assert_options($key);
    }


    // set
    // change la valeur d'une option d'assertion
    final public static function set(int $key,$value):bool
    {
        $return = false;

        if(assert_options($key,$value) !== false)
        $return = true;

        return $return;
    }


    // setHandler
    // lie un handler lors des erreurs d'assertions
    final public static function setHandler(callable $call):bool
    {
        return static::set(ASSERT_CALLBACK,$call);
    }
}
?>