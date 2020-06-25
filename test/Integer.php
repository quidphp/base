<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// integer
// class for testing Quid\Base\Integer
class Integer extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // typecast
        $a = '23.2';
        $b = 'string';
        Base\Integer::typecast($a,$b);
        assert($a === 23);
        assert($b === 0);

        // cast
        assert(Base\Integer::cast('30MB') === null);
        assert(Base\Integer::cast(true) === 1);
        assert(Base\Integer::cast('1.5') === 1);

        // is
        assert(Base\Integer::is(1));
        assert(!Base\Integer::is(2.3));

        // isEmpty
        assert(!Base\Integer::isEmpty(1));
        assert(Base\Integer::isEmpty(0));

        // isNotEmpty
        assert(Base\Integer::isNotEmpty(1));

        // isCast
        assert(!Base\Integer::isCast('1.5'));
        assert(Base\Integer::isCast(-1));
        assert(!Base\Integer::isCast(1.5));
        assert(Base\Integer::isCast(0));

        // isCastNotEmpty
        assert(!Base\Integer::isCastNotEmpty('1.5'));
        assert(Base\Integer::isCastNotEmpty(-1));
        assert(!Base\Integer::isCastNotEmpty(1.5));
        assert(!Base\Integer::isCastNotEmpty(0));

        // toBool
        assert(Base\Integer::toBool(1) === true);
        assert(Base\Integer::toBool(0) === false);
        assert(Base\Integer::toBool(2) === null);

        // toggle
        assert(1 === Base\Integer::toggle(0));

        // range
        assert([0,2,4] === Base\Integer::range(0,5,2));
        assert([2] === Base\Integer::range(2,3,2));
        assert(Base\Integer::range(2,3,2,true) === [2=>2]);
        assert(count(Base\Integer::range(1,100,1)) === 100);
        assert(count(Base\Integer::range(2,18,3)) === 6);

        return true;
    }
}
?>