<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Test\Base;
use Quid\Base;

// boolean
// class for testing Quid\Base\Boolean
class Boolean extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // typecast
        $true = true;
        $number = 0;
        Base\Boolean::typecast($true,$number);
        assert($true === true);
        assert($number === false);

        // cast
        assert(true === Base\Boolean::cast('true'));
        assert('test' === Base\Boolean::cast('test'));
        assert(true === Base\Boolean::cast('true'));
        assert(true === Base\Boolean::cast(1));
        assert(true === Base\Boolean::cast(1));
        assert(1 === Base\Boolean::cast(1,false));
        assert(false === Base\Boolean::cast('0'));
        assert(false === Base\Boolean::cast('0'));
        assert('0' === Base\Boolean::cast('0',false));
        assert(true === Base\Boolean::cast('on'));
        assert(false === Base\Boolean::cast('off'));
        assert(null === Base\Boolean::cast('undefined'));

        // is
        assert(Base\Boolean::is(true));
        assert(!Base\Boolean::is(null));

        // isEmpty
        assert(Base\Boolean::isEmpty(false));
        assert(!Base\Boolean::isEmpty(true));
        assert(!Base\Boolean::isEmpty(0));

        // isNotEmpty
        assert(Base\Boolean::isNotEmpty(true));
        assert(!Base\Boolean::isNotEmpty(1));

        // random
        assert(Base\Boolean::random(1,1));
        assert(Base\Boolean::random(1,1,false));
        assert(Base\Boolean::random(1,1,true));

        // str
        assert(Base\Boolean::str(false) === 'FALSE');
        assert(Base\Boolean::str(true) === 'TRUE');
        assert(Base\Boolean::str(null) === 'NULL');

        // fromInt
        assert(Base\Boolean::fromInt(1) === true);
        assert(Base\Boolean::fromInt(0) === false);
        assert(Base\Boolean::fromInt(2) === null);

        // toggle
        assert(Base\Boolean::toggle(true) === false);

        return true;
    }
}
?>