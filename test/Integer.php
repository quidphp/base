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
        
        // fromString
        assert(Base\Integer::fromString('30MB') === 30);
        assert(Base\Integer::fromString('abc') === null);

        // fromBool
        assert(1 === Base\Integer::fromBool(true));
        assert(0 === Base\Integer::fromBool(false));
        
        // toggle
        assert(null === Base\Integer::toggle('0'));
        assert(1 === Base\Integer::toggle(0));
        
        return true;
    }
}
?>