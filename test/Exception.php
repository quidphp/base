<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// exception
// class for testing Quid\Base\Exception
class Exception extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // setHandler

        // restoreHandler

        // message
        assert(Base\Exception::message(2) === '2');
        assert(Base\Exception::message('test') === 'test');
        assert(Base\Exception::message(['test',2,3,'ok',['LOL','OK'],['LOL','bleh'=>'OK'],['LOL','ok',['meh'=>'JAEMS']]]) === 'test -> 2 -> 3 -> ok -> LOL, OK -> {"0":"LOL","bleh":"OK"} -> ["LOL","ok",{"meh":"JAEMS"}]');

        // classFunction
        assert(Base\Exception::classFunction(['class'=>'test','function'=>'lol'],null,['OK']) === ['test','lol','OK']);
        assert(Base\Exception::classFunction(['class'=>'test','function'=>'lol'],'well',['OK']) === ['well','lol','OK']);

        return true;
    }
}
?>