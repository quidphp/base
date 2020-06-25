<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// assert
// class for testing Quid\Base\Assert
class Assert extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // call
        assert(Base\Assert::call(fn() => true,'what'));

        // get
        assert(Base\Assert::get(ASSERT_ACTIVE) === 1);

        // set

        // setHandler

        return true;
    }
}
?>