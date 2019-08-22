<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// root
class Root extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// root
		assert(Base\Root::classFqcn() === Base\Root::class);
		assert(Base\Root::classNamespace() === 'Quid\Base');
		assert(Base\Root::classRoot() === 'Quid');
		assert(Base\Root::className() === 'Root');
		assert(Base\Root::classParents() === []);
		assert(count(Base\Root::classHelp()) === 9);
		assert(Base\Root::classIsCallable([Base\Str::class,'upper']));
		assert(!Base\Root::classIsCallable('strtoupper'));
		
		return true;
	}
}
?>