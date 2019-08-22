<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// func
class Func extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\Func::is('strlen'));
		assert(!Base\Func::is('strlenz'));
		assert(!Base\Func::is([Base\Func::class,'is']));
		assert(!Base\Func::is([new \DateTime,'setDate']));

		// call
		assert(Base\Func::call('strlen','lala') === 4);

		// all
		assert(count(Base\Func::all()) === 2);

		// user
		assert(count(Base\Func::user()) < 20);
		
		return true;
	}
}
?>