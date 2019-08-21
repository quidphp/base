<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// extension
class Extension extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		\assert(Base\Extension::is('fileinfo'));
		\assert(!Base\Extension::is('fileinfoz'));

		// hasOpCache
		\assert(Base\Extension::hasOpCache());

		// hasXdebug
		\assert(\is_bool(Base\Extension::hasXdebug()));

		// hasApcu
		\assert(Base\Extension::hasApcu());

		// functions
		\assert(\count(Base\Extension::functions('fileinfo')) === 6);
		\assert(\count(Base\Extension::functions('fileinfoz')) === 0);

		// important
		\assert(\count(Base\Extension::important()) === 3);

		// all
		\assert(\count(Base\Extension::all()) > 50);

		// requirement
		\assert(empty(Base\Extension::requirement()));
		
		return true;
	}
}
?>