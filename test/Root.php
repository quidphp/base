<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// root
// class for testing Quid\Base\Root
class Root extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // root
        assert(Base\Root::classFqcn() === Base\Root::class);
        assert(Base\Root::classNamespace() === 'Quid\Base');
        assert(Base\Root::className() === 'Root');
        assert(Base\Root::classParents() === []);
        assert(count(Base\Root::classHelp()) === 9);
        assert(Base\Root::classIsCallable([Base\Str::class,'upper']));
        assert(!Base\Root::classIsCallable('strtoupper'));

        return true;
    }
}
?>