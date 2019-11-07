<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
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

        // isSafeStaticMethod
        assert(!Base\Call::isSafeStaticMethod(function() { }));
        assert(Base\Call::isSafeStaticMethod([Base\Str::class,'lower']));
        assert(Base\Call::isSafeStaticMethod([$date,'setDatez']));
        assert(!Base\Call::isSafeStaticMethod([\Datetime::class,'setDate']));
        assert(!Base\Call::isSafeStaticMethod(["\Datetime",'setDate']));
        assert(Base\Call::isSafeStaticMethod(["D\atetime",'setDate']));
        assert(!Base\Call::isSafeStaticMethod(['test'=>Base\Str::class,'lower']));
        assert(!Base\Call::isSafeStaticMethod('strtolower'));

        // isCallable
        assert(Base\Call::isCallable(function() { }));
        assert(Base\Call::isCallable([Base\Str::class,'lower']));
        assert(Base\Call::isCallable([$date,'setDate']));
        assert(!Base\Call::isCallable([$date,'setDatez']));
        assert(!Base\Call::isCallable(['test'=>Base\Str::class,'lower']));
        assert(!Base\Call::isCallable('strtolower'));

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

        // ableArgs
        assert('b' === Base\Call::ableArgs('substr',['bla',0,1]));
        assert('BLA' === Base\Call::ableArgs('strtoupper',['bla']));
        assert('BLA' === Base\Call::ableArgs('strtoupper',['what'=>'bla']));

        // ableArray
        assert('b' === Base\Call::ableArray(['substr',['bla',0,1]]));
        assert('BLA' === Base\Call::ableArray(['strtoupper',['bla']]));
        $array = ['upper'=>'strtoupper','closure'=>function($x) { if($x === 'bla') return true; }];

        // ableArrs

        // staticClass
        assert(Base\Call::staticClass(Base\Str::class,'is','bla'));

        // staticClasses
        assert(Base\Call::staticClasses([Base\Str::class],'is','bla')[Base\Str::class] === true);

        // back
        assert('BLA' === Base\Call::back('upper',$array,'bla'));

        // backBool
        assert(!Base\Call::backBool('upper',$array,'bla'));
        assert(Base\Call::backBool('closure',$array,'bla'));

        // arr
        $array = ['upper'=>'strtoupper','closure'=>function($x) { if($x === 'bla') return true; }];
        Base\Call::arr('upper',$array,'bla');
        assert($array['upper'] === 'BLA');

        // bool
        assert(Base\Call::bool([Base\Str::class,'is'],'test','test2'));
        assert(!Base\Call::bool([Base\Str::class,'is'],'test','test2',3));

        // map
        $array = [1,2,'test@gmail.com'];
        assert(Base\Call::map('email','strtoupper',$array) === [1,2,'TEST@GMAIL.COM']);
        $array = [1,2,'test'];
        assert(Base\Call::map('string','strtoupper',$array) === [1,2,'TEST']);
        $array = [['test@gmail.com']];
        assert(Base\Call::map('string','strtoupper',$array) === [['TEST@GMAIL.COM']]);
        assert(Base\Call::map('email','strtoupper','test@gmail.com') === 'TEST@GMAIL.COM');
        assert(Base\Call::map('string',[Base\Str::class,'upper'],'éste@gmail.com') === 'éSTE@GMAIL.COM');
        assert(Base\Call::map('string',[Base\Str::class,'upper'],'éste@gmail.com',true) === 'ÉSTE@GMAIL.COM');

        // withObj
        
        // bindTo
        
        // digStaticMethod
        $test = ['test'=>[Base\Request::class,'host'],'well'=>['ok'=>function() { return true; },'james'=>[Base\Request::class,'isSsl']]];
        assert(Base\Call::digStaticMethod($test)['well']['james'] === Base\Request::isSsl());
        assert(Base\Call::digStaticMethod($test)['well']['ok'] instanceof \Closure);

        return true;
    }
}
?>