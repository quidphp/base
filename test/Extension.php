<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// extension
// class for testing Quid\Base\Extension
class Extension extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\Extension::is('fileinfo'));
        assert(!Base\Extension::is('fileinfoz'));

        // hasOpCache
        assert(Base\Extension::hasOpCache());

        // hasXdebug
        assert(is_bool(Base\Extension::hasXdebug()));

        // hasApcu
        assert(is_bool(Base\Extension::hasApcu()));

        // functions
        assert(count(Base\Extension::functions('fileinfo')) === 6);
        assert(count(Base\Extension::functions('fileinfoz')) === 0);

        // important
        assert(count(Base\Extension::important()) === 3);

        // all
        assert(count(Base\Extension::all()) > 50);

        // requirement
        assert(empty(Base\Extension::requirement()));

        return true;
    }
}
?>