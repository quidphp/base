<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// userAgent
// class for testing Quid\Base\UserAgent
class UserAgent extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // isBot
        assert(!Base\UserAgent::isBot('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert(Base\UserAgent::isBot('googlebot'));

        return true;
    }
}
?>