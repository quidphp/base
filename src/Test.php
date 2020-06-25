<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// test
// abstract class used to create a testsuite for a class
abstract class Test extends Root
{
    // config
    protected static array $config = [];


    // trigger
    // méthode abstrait à implenter sur les classes qui étendent
    abstract public static function trigger(array $data):bool;


    // classTest
    // retourne la classe courante
    final public static function classTest():?string
    {
        return (self::class !== static::class)? static::class:null;
    }
}
?>