<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// scalar
// class for testing Quid\Base\Scalar
class Scalar extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // typecast
        $y = 'true';
        $x = '1.1';
        Base\Scalar::typecast($y,$x);
        assert($y === 'true');
        assert($x === 1.1);

        // typecastMore
        $array = [true,'true','null',1.2,'3.5','4,8'];
        Base\Scalar::typecastMore(...$array);
        assert($array === [true,true,null,1.2,3.5,4.8]);
        $array = [true,'true','null',1.2,'3.5','4,8'];
        Base\Scalar::typecastMore($array);
        assert($array === null);

        // cast
        assert(true === Base\Scalar::cast('true',1,1));
        assert('true' === Base\Scalar::cast('true'));
        assert(true === Base\Scalar::cast(true));
        assert(1.5 === Base\Scalar::cast('1.5'));
        assert(Base\Scalar::cast('000111') === '000111');

        // castMore
        assert(true === Base\Scalar::castMore(true));
        assert(true === Base\Scalar::castMore('true'));
        assert(1.3 === Base\Scalar::castMore('1,3'));
        assert(2 === Base\Scalar::castMore(2));
        assert(1 === Base\Scalar::castMore(1));
        assert(Base\Scalar::castMore('000111') === 111);

        // is
        assert(Base\Scalar::is(1));
        assert(Base\Scalar::is('2'));
        assert(!Base\Scalar::is([]));

        // isEmpty
        assert(Base\Scalar::isEmpty(false));
        assert(!Base\Scalar::isEmpty(1));

        // isNotEmpty
        assert(Base\Scalar::isNotEmpty('2'));
        assert(!Base\Scalar::isNotEmpty(0));

        // isNotBool
        assert(Base\Scalar::isNotBool('2'));
        assert(!Base\Scalar::isNotBool(false));

        // isNotNumeric
        assert(!Base\Scalar::isNotNumeric('2'));
        assert(Base\Scalar::isNotNumeric(false));

        // isNotInt
        assert(!Base\Scalar::isNotInt(2));
        assert(Base\Scalar::isNotInt('2'));

        // isNotFloat
        assert(!Base\Scalar::isNotFloat(2.1));
        assert(Base\Scalar::isNotFloat('2.1'));

        // isNotString
        assert(Base\Scalar::isNotString(2.1));
        assert(!Base\Scalar::isNotString('2.1'));

        // isLength
        assert(Base\Scalar::isLength(2,'te'));

        // isMinLength
        assert(Base\Scalar::isMinLength(1,'1'));

        // isMaxLength
        assert(Base\Scalar::isMaxLength(3,'1.3'));

        return true;
    }
}
?>