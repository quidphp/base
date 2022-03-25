<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// finfo
// class for testing Quid\Base\Finfo
class Finfo extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $finfo = finfo_open();

        // is
        assert(Base\Finfo::is($finfo));

        // open

        // close

        return true;
    }
}
?>