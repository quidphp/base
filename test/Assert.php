<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// assert
class Assert extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// call
		assert(Base\Assert::call(function() { return true; },'what'));

		// get
		assert(Base\Assert::get(ASSERT_ACTIVE) === 1);

		// set

		// setHandler

		return true;
	}
}
?>