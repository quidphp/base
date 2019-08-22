<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// cookie
class Cookie extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(!Base\Cookie::is('test'));

		// get
		assert(Base\Cookie::get('test') !== 'blaz');

		// set
		assert(Base\Cookie::set("test","blaz"));

		// unset
		assert(Base\Cookie::unset('test'));

		// option
		assert(count(Base\Cookie::option('set')) === 6);
		assert(Base\Cookie::option('set',['lifetime'=>null,'expire'=>Base\Date::time() + 1]) === Base\Cookie::option('set',['lifetime'=>1,'expire'=>null]));
		assert(Base\Cookie::option('set',['lifetime'=>0])['expire'] === Base\Date::time());
		assert(Base\Cookie::option('set',['lifetime'=>0])['lifetime'] === 0);
		assert(Base\Cookie::option('set')['expire'] > Base\Date::getTimestamp());
		assert(count(Base\Cookie::option('unset')) === 6);
		assert(Base\Cookie::option('unset')['expire'] < Base\Date::getTimestamp());
		assert(Base\Cookie::option('unset',['lifetime'=>0])['expire'] < Base\Date::time());
		assert(Base\Cookie::option('unset',['lifetime'=>0])['lifetime'] === 0);
		assert(empty(Base\Cookie::option('removez')));
		assert(Base\Cookie::option('set',['lifetime'=>200])['expire'] > 200);
		assert(Base\Cookie::option('set',['expire'=>200])['expire'] === 200);
		
		return true;
	}
}
?>