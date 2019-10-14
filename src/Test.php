<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// test
// abstract class used to create a testsuite for a class
abstract class Test extends Root
{
    // config
    public static $config = [];


    // start
    // lance les tests sur le fichier
    public static function start(?array $data=null)
    {
        $return = false;
        $data = (array) $data;

        static::before($data);
        $bool = static::trigger($data);
        static::after($data);

        if($bool === true)
        $return = static::classSubCount('assert(') ?? $bool;

        return $return;
    }


    // before
    // méthode appelé avant trigger
    public static function before(array $data):void
    {
        return;
    }


    // after
    // méthode appelé après trigger
    public static function after(array $data):void
    {
        return;
    }


    // trigger
    // méthode abstrait à implenter sur les classes qui étendent
    abstract public static function trigger(array $data):bool;
}
?>