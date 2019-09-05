<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// autoload
// class for testing Quid\Base\Autoload
class Autoload extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// call

		// getExtensions
		assert(Base\Autoload::getExtensions() === '.inc,.php');

		// setExtensions

		// register

		// unregister

		// unregisterAll

		// all
		assert(count(Base\Autoload::all()) >= 3);

		// index
		assert(is_callable(Base\Autoload::index(0)));

		// getPath

		// getFilePath

		// getDirPath

		// getPsr4
		assert(Base\Autoload::getPsr4('Appz') === null);

		// setPsr4
		Base\Autoload::setPsr4('Appz','test');

		// setsPsr4

		// unsetPsr4
		assert(Base\Autoload::getPsr4('Appz') === ['Appz'=>'test']);
		Base\Autoload::unsetPsr4('Appz');

		// allPsr4
		assert(!empty(Base\Autoload::allPsr4()));

		// removeAlias
		assert(count(Base\Autoload::removeAlias([static::class,'jamesAlias','row\rowalias'])) === 2);

		// overview

		// phpExtension

		return true;
	}
}
?>