<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// vari
// class for testing Quid\Base\Vari
class Vari extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // isEmpty
        assert(Base\Vari::isEmpty(false));
        assert(Base\Vari::isEmpty(0));
        assert(!Base\Vari::isEmpty(1));

        // isNotEmpty
        assert(Base\Vari::isNotEmpty(true));

        // isReallyEmpty
        assert(Base\Vari::isReallyEmpty(''));
        assert(Base\Vari::isReallyEmpty(null));
        assert(Base\Vari::isReallyEmpty([]));
        assert(!Base\Vari::isReallyEmpty(false));
        assert(!Base\Vari::isReallyEmpty(0));
        assert(!Base\Vari::isReallyEmpty('0'));
        assert(!Base\Vari::isReallyEmpty(' '));
        assert(Base\Vari::isReallyEmpty(' ',true));

        // isNotReallyEmpty
        assert(Base\Vari::isNotReallyEmpty(0));
        assert(!Base\Vari::isNotReallyEmpty(null));

        // isNull
        assert(!Base\Vari::isNull(false));
        assert(Base\Vari::isNull(null));

        // isType
        assert(Base\Vari::isType('NULL',null));
        assert(Base\Vari::isType('array',[]));

        // sameType
        assert(Base\Vari::sameType(false,false));
        assert(Base\Vari::sameType(2,1));
        assert(Base\Vari::sameType(2,1,3,4,5,6,7));
        assert(!Base\Vari::sameType(2,'1',3,4,5,6,7));
        assert(Base\Vari::sameType(new \stdclass(),new \stdclass()));
        assert(!Base\Vari::sameType(new \DateTime('now'),new \stdclass()));
        assert(Base\Vari::sameType(new \DateTime('now'),new \DateTime('now'),new \DateTime('now')));

        // type
        assert(Base\Vari::type(true) === 'boolean');
        assert(Base\Vari::type(new \stdclass()) === 'object');

        return true;
    }
}
?>