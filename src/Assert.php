<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// assert
// class with methods a layer over the native PHP assert functions
final class Assert extends Root
{
    // config
    protected static array $config = [];


    // call
    // fait une assertion sur une closure
    final public static function call(\Closure $closure,$extra=null):bool
    {
        return assert($closure(),$extra);
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
        return assert_options($key,$value) !== false;
    }


    // setHandler
    // lie un handler lors des erreurs d'assertions
    final public static function setHandler(callable $call):bool
    {
        return self::set(ASSERT_CALLBACK,$call);
    }
}
?>