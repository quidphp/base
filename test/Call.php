<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Test\Base;
use Quid\Base;

// call
// class for testing Quid\Base\Call
class Call extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // cast
        $date = new \DateTime();
        $cast = Base\Call::cast($date);
        assert($cast() === $date);
        $test = 'bla';
        Base\Call::typecast($test);
        assert($test() === 'bla');
        assert(Base\Call::type($test) === 'closure');

        // is
        assert(Base\Call::is('strtolower'));
        assert(!Base\Call::is('strtolowerz'));
        assert(Base\Call::is([Base\Str::class,'lower']));
        assert(Base\Call::is([$date,'setDate']));
        assert(Base\Call::is(function() { }));

        // isSafeArray
        assert(!Base\Call::isSafeArray(function() { }));
        assert(Base\Call::isSafeArray([Base\Str::class,'lower']));
        assert(Base\Call::isSafeArray([$date,'setDatez']));
        assert(!Base\Call::isSafeArray([\Datetime::class,'setDate']));
        assert(!Base\Call::isSafeArray(["\Datetime",'setDate']));
        assert(Base\Call::isSafeArray(["D\atetime",'setDate']));
        assert(!Base\Call::isSafeArray(['test'=>Base\Str::class,'lower']));
        assert(!Base\Call::isSafeArray('strtolower'));

        // isFunction
        assert(Base\Call::isFunction('strtolower'));

        // isClosure
        assert(Base\Call::isClosure(function() { }));

        // isDynamicMethod
        assert(Base\Call::isDynamicMethod([$date,'setDate']));

        // isStaticMethod
        assert(Base\Call::isStaticMethod([Base\Str::class,'lower']));

        // type
        assert('function' === Base\Call::type('strtolower'));
        assert('staticMethod' === Base\Call::type([Base\Str::class,'lower']));
        assert('closure' === Base\Call::type(function() { }));
        assert('closure' === Base\Call::type(static function() { }));
        assert('dynamicMethod' === Base\Call::type([$date,'setDate']));

        // able
        assert('BLA' === Base\Call::able('strtoupper','bla'));
        assert('BLA' === Base\Call::able('strtoupper','bla'));
        assert('b' === Base\Call::able('substr','bla',0,1));

        // loop

        // back
        $array = ['upper'=>'strtoupper','closure'=>fn($x) => ($x === 'bla')];
        assert('BLA' === Base\Call::back('upper',$array,'bla'));

        // dig
        $test = ['test'=>[Base\Request::class,'host'],'well'=>['ok'=>fn() => true,'james'=>[Base\Request::class,'isSsl']]];
        assert(Base\Call::dig(true,$test)['well']['james'] === Base\Request::isSsl());
        assert(Base\Call::dig(true,$test)['well']['ok'] instanceof \Closure);
        assert(!Base\Call::dig(false,$test)['well']['ok'] instanceof \Closure);

        // staticClass
        $array = ['upper'=>'strtoupper','closure'=>fn($x) => ($x === 'bla')];
        assert(Base\Call::staticClass(Base\Str::class,'is','bla'));

        // staticClasses
        assert(Base\Call::staticClasses([Base\Str::class],'is','bla')[Base\Str::class] === true);

        // bindTo

        // root
        assert(Base\Call::isCallable(function() { }));
        assert(Base\Call::isCallable([Base\Str::class,'lower']));
        assert(Base\Call::isCallable([$date,'setDate']));
        assert(!Base\Call::isCallable([$date,'setDatez']));
        assert(!Base\Call::isCallable(['test'=>Base\Str::class,'lower']));
        assert(!Base\Call::isCallable('strtolower'));

        return true;
    }
}
?>