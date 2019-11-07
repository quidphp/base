<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// func
// class for testing Quid\Base\Func
class Func extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\Func::is('strlen'));
        assert(!Base\Func::is('strlenz'));
        assert(!Base\Func::is([Base\Func::class,'is']));
        assert(!Base\Func::is([new \DateTime(),'setDate']));

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