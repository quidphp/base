<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// network
// class for testing Quid\Base\Network
class Network extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // isOnline

        // hasDns

        // ping

        // dns

        // mx

        // getHostname

        // getIp

        // getProtocolNumber
        assert(Base\Network::getProtocolNumber('tcp') === 6);
        assert(Base\Network::getProtocolNumber('tcpz') === null);

        // getProtocolName
        assert(Base\Network::getProtocolName(6) === 'tcp');
        assert(Base\Network::getProtocolName(6000) === null);

        // getServiceName
        assert(Base\Network::getServiceName(80,'tcp') === 'http');
        assert(Base\Network::getServiceName(81123,'tcp') === null);

        // getServicePort
        assert(Base\Network::getServicePort('http','tcp') === 80);
        assert(Base\Network::getServicePort('httpz','tcpw') === null);

        return true;
    }
}
?>