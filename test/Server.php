<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// server
// class for testing Quid\Base\Server
class Server extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$_file_ = Base\Finder::shortcut('[assertCommon]/class.php');

		// isMac
		assert(is_bool(Base\Server::isMac()));

		// isWindows
		assert(Base\Server::isWindows() === false);

		// isLinux
		assert(is_bool(Base\Server::isLinux()));

		// isApache
		assert(Base\Server::isApache());

		// isIis
		assert(!Base\Server::isIis());

		// isHttp1
		assert(is_bool(Base\Server::isHttp1()));

		// isHttp2
		assert(is_bool(Base\Server::isHttp2()));

		// isOnline

		// isOffline

		// isCli
		assert(!Base\Server::isCli());

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
		assert(count(Base\Server::phpImportantIni()) === 34);

		// zendVersion
		assert(Base\Server::zendVersion() > 3);

		// quidVersion
		assert(Base\Server::quidVersion() === QUID_VERSION);

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
		assert(PHP_OS === Base\Server::os());
		assert(PHP_OS !== Base\Server::os(true));

		// osType
		assert(is_string(Base\Server::osType()));

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
		assert(Base\Validate::isIp(Base\Server::ip()));

		// ipPublic
		$public = Base\Server::ipPublic();
		assert($public === null || Base\Validate::isIp($public));

		// software
		assert(is_string(Base\Server::software()));

		// gatewayInterface
		assert(is_string(Base\Server::gatewayInterface()));

		// httpProtocol
		assert(in_array(Base\Server::httpProtocol(),['HTTP/1.1','HTTP/2.0'],true));

		// sapi
		assert(is_string(Base\Server::sapi()));

		// script
		assert(count(Base\Server::script()) === 6);

		// processId
		assert(is_int(Base\Server::processId()));

		// process
		assert(count(Base\Server::process()) === 11);

		// currentUser
		assert(is_int(Base\Server::currentUser()));

		// user
		assert(count(Base\Server::user()) === 7);
		assert(Base\Server::user(100000) === null);
		assert(Base\Server::user(0)['name'] === 'root');
		assert(Base\Server::user('root')['name'] === 'root');
		assert(Base\Server::user('root',true) === 'root');

		// currentGroup
		assert(is_int(Base\Server::currentGroup()));

		// group
		assert(is_string(Base\Server::group(null,true)));
		assert(count(Base\Server::group()) === 4);
		assert(count(Base\Server::group(20)) === 4);
		assert(Base\Server::group(100000) === null);

		// email
		assert(is_string(Base\Server::email()));

		// resourceLimit
		assert(count(Base\Server::resourceLimit()) > 4);

		// resourceUsage
		assert(count(Base\Server::resourceUsage()) > 10);

		// processUsage
		assert(count(Base\Server::processUsage()) > 4);

		// loadAverage
		assert(count(Base\Server::loadAverage()) === 3);

		// memory
		assert(count(Base\Server::memory()) === 4);

		// diskSpace
		assert(count(Base\Server::diskSpace()) === 2);

		// phpInfo

		// overview
		assert(count(Base\Server::overview()) === 16);

		// info
		assert(count(Base\Server::info()) === 29);

		// requirement
		assert(empty(Base\Server::requirement()));

		// setQuidVersion

		return true;
	}
}
?>