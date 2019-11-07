<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// ip
// class for testing Quid\Base\Ip
class Ip extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\Ip::is('2.2.2.2'));
        assert(!Base\Ip::is('2.2.2.2.3'));
        assert(!Base\Ip::is('2.2.2.2a'));
        assert(Base\Ip::is('0.0.0.0'));

        // isLocal
        assert(Base\Ip::isLocal('127.0.0.1'));
        assert(Base\Ip::isLocal('192.168.1.1'));
        assert(!Base\Ip::isLocal('2.2.2.2.3'));
        assert(!Base\Ip::isLocal('2.2.2.2.3.4'));

        // normalize
        assert(Base\Ip::normalize('127.0.0.1') === '127.0.0.1');
        assert(Base\Ip::normalize('127.0.0') === '0.0.0.0');
        assert(Base\Ip::normalize('::1') === '127.0.0.1');

        // allowed
        assert(Base\Ip::allowed('127.0.0.1',['whiteList'=>['127.0.*.*']]));
        assert(!Base\Ip::allowed('127.0.0.1',['whiteList'=>['127.0.1.1']]));
        assert(!Base\Ip::allowed('127.0.0.1',['whiteList'=>['127.0.*.2']]));
        assert(!Base\Ip::allowed('127.0.0.1',['blackList'=>['127.0.*.*']]));
        assert(Base\Ip::allowed('127.0.0.1',['blackList'=>['127.0.1.1']]));
        assert(Base\Ip::allowed('127.0.0.1',['blackList'=>['127.0.*.2']]));

        // compareRange
        assert(Base\Ip::compareRange('127.0.0.1','127.0.*.*'));
        assert(Base\Ip::compareRange('127.0.0.1','*.0.*.*'));
        assert(!Base\Ip::compareRange('127.1.0.1','*.0.*.*'));
        assert(Base\Ip::compareRange('10.1.0.1','10.*.*.1'));
        assert(!Base\Ip::compareRange('127.*.0.1','*.0.*.*'));

        // compareLevel
        assert(Base\Ip::compareLevel('1.2.3.4','1.2.3.5'));
        assert(Base\Ip::compareLevel('1.2.3.4','1.2.3.4'));
        assert(!Base\Ip::compareLevel('1.2.3.3','1.2.4.4',3));
        assert(Base\Ip::compareLevel('1.2.3.3','1.2.4.4',2));
        assert(!Base\Ip::compareLevel('1.4.3.3','1.3.4.4',2));
        assert(Base\Ip::compareLevel('1.3.3.3','1.4.4.4',1));
        assert(Base\Ip::compareLevel('1.2.3.4','1.2.3.4',4));

        // in
        assert(Base\Ip::in('1.2.3.4',['1.2.3.4']));
        assert(!Base\Ip::in('1.2.3.4',['1.2.3.5']));
        assert(Base\Ip::in('1.2.3.4',['1.2.3.*']));
        assert(!Base\Ip::in('2.2.3.4',['1.2.3.*']));
        assert(Base\Ip::in('2.2.3.4',['1.2.3.*','2.2.3.4']));
        assert(Base\Ip::in('2.2.3.4',['1.2.3.*','2.2.3.5'],true,3));

        // reformat
        assert(count(Base\Ip::reformat('10.105.100.0/22')) === 4);
        assert(count(Base\Ip::reformat('10.103.188.0/23')) === 2);
        assert(count(Base\Ip::reformat('10.214.246.0/24')) === 1);
        assert(Base\Ip::reformat('10.105.100.0/19') === null);
        assert(count(Base\Ip::reformat('10.105.100.0/20')) === 16);

        // reformats
        assert(count(Base\Ip::reformats('10.105.100.0/22','10.105.100.0/19','10.105.100.0/20')) === 3);

        // reformatsUnique
        assert(count(Base\Ip::reformatsUnique('10.108.100.0/22','10.105.100.0/19','10.105.100.0/20')) === 20);
        assert(count(Base\Ip::reformatsUnique('10.105.100.0/22','10.105.100.0/19','10.105.100.0/20')) === 16);

        // toLong
        assert(Base\Ip::toLong('1.2.3.4') === 16909060);
        assert(Base\Ip::toLong('1.2.3.4.5') === null);

        // fromLong
        assert(Base\Ip::fromLong(16909060) === '1.2.3.4');
        assert(Base\Ip::fromLong(16909060222222) === '242.135.45.14');

        // explode
        assert(Base\Ip::explode('1.2.3.4') === ['1','2','3','4']);
        assert(Base\Ip::explode('1.2.3.4a') === ['1','2','3','4a']);

        // implode
        assert(Base\Ip::implode(['1','2','3','4']) === '1.2.3.4');
        assert(Base\Ip::implode(['1','2','3','4z']) === '1.2.3.4z');

        return true;
    }
}
?>