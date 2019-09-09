<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// config
// class for testing Quid\Base\Config
class Config extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        return true;
    }
}
?>