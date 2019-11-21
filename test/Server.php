<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Test\Base;
use Quid\Base;

// server
// class for testing Quid\Base\Server
class Server extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $isCli = Base\Server::isCli();
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');

        // isOs
        assert(is_bool(Base\Server::isOs('darwin')));

        // isMac
        assert(is_bool(Base\Server::isMac()));

        // isWindows
        assert(is_bool(Base\Server::isWindows()));

        // isLinux
        assert(is_bool(Base\Server::isLinux()));

        // isSoftware
        assert(is_bool(Base\Server::isSoftware('apache')));

        // isApache
        assert(is_bool(Base\Server::isApache()));

        // isNginx
        assert(is_bool(Base\Server::isNginx()));

        // isIis
        assert(!Base\Server::isIis());

        // isHttp1
        assert(is_bool(Base\Server::isHttp1()));

        // isHttp2
        assert(is_bool(Base\Server::isHttp2()));

        // isOnline

        // isOffline

        // isCli
        assert(is_bool(Base\Server::isCli()));

        // isCaseSensitive
        if(Base\Server::isCaseSensitive())
        assert(!file_exists(strtoupper($_file_)));
        else
        assert(file_exists(strtoupper($_file_)));

        // isCaseInsensitive

        // isPhpVersion
        assert(Base\Server::isPhpVersion(PHP_VERSION));

        // isPhpVersionOlder
        assert(Base\Server::isPhpVersionOlder('7.5'));

        // isPhpVersionNewer
        assert(Base\Server::isPhpVersionNewer('6.5'));
        assert(!Base\Server::isPhpVersionNewer('7.5'));

        // hasApacheFunctions
        assert(is_bool(Base\Server::hasApacheFunctions()));

        // isApacheModule
        assert(is_bool(Base\Server::isApacheModule('core')));

        // hasApacheModRewrite
        assert(is_bool(Base\Server::hasApacheModRewrite()));

        // isOwner
        assert(!Base\Server::isOwner(999));

        // isGroup
        assert(!Base\Server::isGroup(999));

        // isConnectionNormal
        assert(Base\Server::isConnectionNormal());

        // isConnectionAborted
        assert(Base\Server::isConnectionAborted() === false);

        // timeLimit

        // connectionStatus
        assert(Base\Server::connectionStatus() === 0);

        // ignoreUserAbort
        assert(Base\Server::ignoreUserAbort(true) === true);
        assert(Base\Server::ignoreUserAbort(true) === true);
        assert(Base\Server::ignoreUserAbort(false) === false);

        // phpVersion
        assert(PHP_VERSION === Base\Server::phpVersion());
        assert(PHP_VERSION !== Base\Server::phpVersion('DOM'));

        // phpImportantIni
        assert(count(Base\Server::phpImportantIni()) === 30);

        // phpImportantExtension
        assert(count(Base\Server::phpImportantExtension()) === 3);

        // zendVersion
        assert(Base\Server::zendVersion() > 3);

        // quidVersion
        assert(Base\Server::quidVersion() === QUID_VERSION);

        // quidName
        assert(Base\Server::quidName() === 'QUID/'.Base\Server::quidVersion().'|PHP/'.Base\Server::phpVersion());

        // apacheVersion
        if(Base\Server::hasApacheFunctions())
        assert(is_string(Base\Server::apacheVersion()));

        // apacheModules
        if(Base\Server::hasApacheFunctions())
        assert(is_array(Base\Server::apacheModules()));

        // uname
        assert(count(Base\Server::uname()) > 4);

        // unameKey
        assert(Base\Server::unameKey('test') === null);
        assert(is_string(Base\Server::unameKey('release')));

        // os
        assert(!empty(Base\Server::os()));
        assert(Base\Server::os() !== Base\Server::os(true));
        assert(Base\Server::os(true) !== Base\Server::os(true,true));

        // osType
        assert(is_string(Base\Server::osType()));

        // serverType
        if($isCli === true)
        assert(Base\Server::serverType() === null);
        else
        assert(is_string(Base\Server::serverType()));

        // sysname
        assert(is_string(Base\Server::sysname()));

        // nodename
        assert(is_string(Base\Server::nodename()));

        // release
        assert(is_string(Base\Server::release()));

        // version
        assert(is_string(Base\Server::version()));

        // machine
        assert(is_string(Base\Server::machine()));

        // hostname
        assert(is_string(Base\Server::hostname()));

        // superglobal
        assert(count(Base\Server::superglobal()) > 10);

        // ip
        $public = Base\Server::ip();
        assert($public === null || Base\Validate::isIp($public));

        // addr
        assert(Base\Validate::isIp(Base\Server::addr()));

        // software
        if($isCli === false)
        assert(is_string(Base\Server::software()));
        else
        assert(Base\Server::software() === null);

        // gatewayInterface
        if($isCli === false)
        assert(is_string(Base\Server::gatewayInterface()));
        else
        assert(Base\Server::gatewayInterface() === null);

        // httpProtocol
        assert(in_array(Base\Server::httpProtocol(),[null,'HTTP/1.1','HTTP/2.0'],true));

        // sapi
        assert(is_string(Base\Server::sapi()));

        // script
        assert(count(Base\Server::script()) === 6);

        // processId
        assert(is_int(Base\Server::processId()));

        // user
        assert(is_int(Base\Server::user()));
        assert(is_string(Base\Server::user(true)));
        assert(Base\Server::user(true,true) !== Base\Server::user(true));

        // group
        assert(is_int(Base\Server::group()));

        // email
        assert(is_string(Base\Server::email()) || Base\Server::email() === null);

        // resourceUsage
        assert(count(Base\Server::resourceUsage()) > 5);

        // memory
        assert(count(Base\Server::memory()) === 4);

        // diskSpace
        assert(count(Base\Server::diskSpace()) === 2);

        // phpInfo

        // overview
        assert(count(Base\Server::overview()) === 16);

        // info
        assert(count(Base\Server::info()) === 25);

        // requirement
        assert(empty(Base\Server::requirement()));

        // setQuidVersion

        return true;
    }
}
?>