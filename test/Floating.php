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

// floating
// class for testing Quid\Base\Floating
class Floating extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // typecast
        $a = '23.2';
        $b = 'string';
        Base\Floating::typecast($a,$b);
        assert($a === 23.2);
        assert($b === (float) 0);

        // cast
        assert(Base\Floating::cast('30MB') === null);
        assert(Base\Floating::cast(true) === 1.0);
        assert(Base\Floating::cast('1.5') === 1.5);

        // is
        assert(Base\Floating::is((float) 1));
        assert(!Base\Floating::is(1));
        assert(Base\Floating::is(log(0)));

        // isEmpty
        assert(!Base\Floating::isEmpty(1));
        assert(Base\Floating::isEmpty((float) 0));

        // isNotEmpty
        assert(Base\Floating::isNotEmpty(1.2));

        // isCast
        assert(!Base\Floating::isCast(-1));
        assert(Base\Floating::isCast(1.5));
        assert(Base\Floating::isCast('1.5'));
        assert(!Base\Floating::isCast('2'));

        // isCastNotEmpty
        assert(Base\Floating::isCastNotEmpty(1.5));
        assert(!Base\Floating::isCastNotEmpty(0));

        // fromString
        assert(Base\Floating::fromString('3.2') === 3.2);
        assert(Base\Floating::fromString('3') === (float) 3);

        return true;
    }
}
?>