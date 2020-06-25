<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// constant
// class for testing Quid\Base\Constant
class Constant extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
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