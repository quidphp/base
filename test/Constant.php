<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// constant
class Constant extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\Constant::is('QUID_VERSION'));

		// get

		// set
		assert(Base\Constant::set('QUID_TEST','test') === true);
		assert(Base\Constant::get('QUID_TEST') === 'test');
		assert(Base\Constant::is('QUID_TEST'));

		// all
		assert(count(Base\Constant::all()) > 30);

		// user
		assert(count(Base\Constant::user()) < 30);
		
		return true;
	}
}
?>