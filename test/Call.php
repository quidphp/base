<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// call
class Call extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// cast
		$date = new \DateTime;
		$cast = Base\Call::cast($date);
		assert($cast() === $date);
		$test = 'bla';
		Base\Call::typecast($test);
		assert($test() === 'bla');
		assert(Base\Call::type($test) === 'closure');

		// is
		assert(Base\Call::is('strtolower'));
		assert(!Base\Call::is('strtolowerz'));
		assert(Base\Call::is(array(Base\Str::class,'lower')));
		assert(Base\Call::is(array($date,'setDate')));
		assert(Base\Call::is(function() { }));
		
		// isSafeStaticMethod
		assert(!Base\Call::isSafeStaticMethod(function() { }));
		assert(Base\Call::isSafeStaticMethod(array(Base\Str::class,'lower')));
		assert(Base\Call::isSafeStaticMethod(array($date,'setDatez')));
		assert(!Base\Call::isSafeStaticMethod(array(\Datetime::class,'setDate')));
		assert(!Base\Call::isSafeStaticMethod(array("\Datetime",'setDate')));
		assert(Base\Call::isSafeStaticMethod(array("D\atetime",'setDate')));
		assert(!Base\Call::isSafeStaticMethod(array('test'=>Base\Str::class,'lower')));
		assert(!Base\Call::isSafeStaticMethod('strtolower'));
		
		// isCallable
		assert(Base\Call::isCallable(function() { }));
		assert(Base\Call::isCallable(array(Base\Str::class,'lower')));
		assert(Base\Call::isCallable(array($date,'setDate')));
		assert(!Base\Call::isCallable(array($date,'setDatez')));
		assert(!Base\Call::isCallable(array('test'=>Base\Str::class,'lower')));
		assert(!Base\Call::isCallable('strtolower'));
		
		// isFunction
		assert(Base\Call::isFunction('strtolower'));

		// isClosure
		assert(Base\Call::isClosure(function() { }));

		// isDynamicMethod
		assert(Base\Call::isDynamicMethod(array($date,'setDate')));

		// isStaticMethod
		assert(Base\Call::isStaticMethod(array(Base\Str::class,'lower')));

		// type
		assert("function" === Base\Call::type('strtolower'));
		assert("staticMethod" === Base\Call::type(array(Base\Str::class,'lower')));
		assert("closure" === Base\Call::type(function() { }));
		assert("closure" === Base\Call::type(static function() { }));
		assert("dynamicMethod" === Base\Call::type(array($date,'setDate')));

		// able
		assert("BLA" === Base\Call::able("strtoupper","bla"));
		assert("BLA" === Base\Call::able("strtoupper",'bla'));
		assert("b" === Base\Call::able("substr",'bla',0,1));

		// ableArgs
		assert("b" === Base\Call::ableArgs("substr",array('bla',0,1)));
		assert("BLA" === Base\Call::ableArgs("strtoupper",array('bla')));
		assert("BLA" === Base\Call::ableArgs("strtoupper",array('what'=>'bla')));

		// ableArray
		assert("b" === Base\Call::ableArray(array("substr",array('bla',0,1))));
		assert("BLA" === Base\Call::ableArray(array("strtoupper",['bla'])));
		$array = array('upper'=>'strtoupper','closure'=>function($x) { if($x === 'bla') return true; });
		
		// ableArrs
		
		// staticClass
		assert(Base\Call::staticClass(Base\Str::class,'is','bla'));

		// staticClasses
		assert(Base\Call::staticClasses(array(Base\Str::class),'is','bla')[Base\Str::class] === true);
		
		// back
		assert("BLA" === Base\Call::back("upper",$array,"bla"));

		// backBool
		assert(!Base\Call::backBool("upper",$array,"bla"));
		assert(Base\Call::backBool("closure",$array,"bla"));

		// arr
		$array = array('upper'=>'strtoupper','closure'=>function($x) { if($x === 'bla') return true; });
		Base\Call::arr("upper",$array,"bla");
		assert($array['upper'] === 'BLA');

		// bool
		assert(Base\Call::bool(array(Base\Str::class,'is'),'test','test2'));
		assert(!Base\Call::bool(array(Base\Str::class,'is'),'test','test2',3));

		// map
		$array = array(1,2,'test@gmail.com');
		assert(Base\Call::map('email','strtoupper',$array) === array(1,2,'TEST@GMAIL.COM'));
		$array = array(1,2,'test');
		assert(Base\Call::map('string','strtoupper',$array) === array(1,2,'TEST'));
		$array = array(array('test@gmail.com'));
		assert(Base\Call::map('string','strtoupper',$array) === array(array('TEST@GMAIL.COM')));
		assert(Base\Call::map('email','strtoupper',"test@gmail.com") === 'TEST@GMAIL.COM');
		assert(Base\Call::map('string',array(Base\Str::class,'upper'),"éste@gmail.com") === 'éSTE@GMAIL.COM');
		assert(Base\Call::map('string',array(Base\Str::class,'upper'),"éste@gmail.com",true) === 'ÉSTE@GMAIL.COM');

		// withObj
		
		// digStaticMethod
		$test = array('test'=>array(Base\Request::class,'host'),'well'=>array('ok'=>function() { return true; },'james'=>array(Base\Request::class,'isSsl')));
		assert(Base\Call::digStaticMethod($test)['well']['james'] === Base\Request::isSsl());
		assert(Base\Call::digStaticMethod($test)['well']['ok'] instanceof \Closure);
		
		return true;
	}
}
?>