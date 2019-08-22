<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// exception
class Exception extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// setHandler

		// restoreHandler

		// message
		assert(Base\Exception::message(2) === "2");
		assert(Base\Exception::message('test') === "test");
		assert(Base\Exception::message(array('test',2,3,'ok',array('LOL','ok',array('JAEMS','non')))) === "test -> 2 -> 3 -> ok -> LOL, ok, JAEMS: non");

		// classFunction
		assert(Base\Exception::classFunction(array('class'=>'test','function'=>'lol'),null,array('OK')) === array('test','lol','OK'));
		assert(Base\Exception::classFunction(array('class'=>'test','function'=>'lol'),'well',array('OK')) === array('well','lol','OK'));
		
		return true;
	}
}
?>