<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// ini
// class for testing Quid\Base\Ini
class Ini extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$publicPath = Base\Finder::shortcut('[public]');
		$includePath = Base\Ini::getIncludePath();

		// is
		assert(Base\Ini::is('upload_max_filesize'));

		// isVarDumpOverloaded
		assert(is_bool(Base\Ini::isVarDumpOverloaded()));

		// get
		assert(!empty(Base\Ini::get('upload_max_filesize')));
		assert(null === Base\Ini::get('upload_max_filesizez'));
		assert(Base\Ini::get('allow_url_fopen') === 1);

		// gets
		assert(count(Base\Ini::gets('upload_max_filesize','display_errors')) === 2);

		// set
		assert(Base\Ini::set('upload_max_filesize','300MB') === false);
		assert(Base\Ini::get('display_errors') === 1);
		assert(Base\Ini::set('display_errors','false'));
		assert(false === Base\Ini::get('display_errors'));
		assert(Base\Ini::set('display_errors',true));
		assert(Base\Ini::set('display_errors','off'));
		assert(Base\Ini::get('display_errors') === false);
		assert(Base\Ini::set('display_errors','on'));
		assert(Base\Ini::get('display_errors') === true);

		// sets
		assert(count(Base\Ini::sets(['display_errors'=>true])) === 1);

		// unset
		assert(Base\Ini::get('display_errors') === 1);
		Base\Ini::unset('display_errors');
		assert(Base\Ini::get('display_errors') === 1);
		assert(Base\Ini::set('display_errors','true'));
		Base\Ini::unset('display_errors');
		assert(Base\Ini::get('display_errors') === 1);
		assert(Base\Ini::set('display_errors','true'));
		assert(Base\Ini::get('display_errors') === true);

		// all
		assert(count(Base\Ini::all('mbstring')) < 20);
		assert(count(Base\Ini::all()) > 100);
		assert(Base\Ini::all('test') === []);

		// parse
		$files = Base\Ini::files();
		assert(!empty(Base\Ini::parse($files['loaded'])));
		assert(count(Base\Ini::parse('display_startup_errors = On 
		display_errors = On')) === 2);

		// files
		assert(count(Base\Ini::files()) === 2);

		// sizeFormat
		assert(Base\Ini::sizeFormat('100M') === 100);
		assert(Base\Ini::sizeFormat('100M',1) === 104857600);
		assert(Base\Ini::sizeFormat('100M',2) === '100 MB');

		// uploadMaxFilesize
		assert(is_int(Base\Ini::uploadMaxFilesize()));
		assert(is_int(Base\Ini::uploadMaxFilesize(1)));
		assert(is_string(Base\Ini::uploadMaxFilesize(2)));

		// postMaxSize
		assert(is_int(Base\Ini::postMaxSize()));
		assert(is_int(Base\Ini::postMaxSize(1)));
		assert(is_string(Base\Ini::postMaxSize(2)));

		// memoryLimit
		assert(is_int(Base\Ini::memoryLimit()));
		assert(is_int(Base\Ini::memoryLimit(1)));
		assert(is_string(Base\Ini::memoryLimit(2)));

		// tempDir
		assert(Base\Ini::tempDir() === '');

		// getCharset
		assert(Base\Ini::getCharset() === Base\Encoding::getCharset());

		// setCharset
		assert(Base\Ini::setCharset('latin1'));
		assert(Base\Ini::getCharset() === 'latin1');
		assert(Base\Ini::setCharset(Base\Encoding::getCharset()));

		// getTimezone
		assert(Base\Ini::getTimezone() === date_default_timezone_get());

		// setTimezone
		assert(Base\Ini::setTimezone('America/Los_Angeles'));
		assert(Base\Ini::getTimezone() === 'America/Los_Angeles');
		Base\Ini::unset('date.timezone');
		assert(Base\Ini::getTimezone() === 'America/New_York');

		// getTimeLimit
		assert(is_int(Base\Ini::getTimeLimit()));

		// setTimeLimit
		assert(Base\Ini::setTimeLimit(60));
		assert(Base\Ini::getTimeLimit() === 60);

		// getErrorReporting
		assert(Base\Ini::getErrorReporting() === -1);

		// setErrorReporting
		assert(Base\Ini::setErrorReporting(E_ALL));
		assert(Base\Ini::getErrorReporting() === E_ALL);
		assert(Base\Ini::setErrorReporting(-1));
		assert(Base\Ini::getErrorReporting() === -1);

		// getErrorLog
		assert(file_exists(Base\Ini::getErrorLog()));

		// setErrorLog
		assert(Base\Ini::setErrorLog(Base\Ini::getErrorLog()));

		// getIncludePath
		assert(count(Base\Ini::getIncludePath()) === 1);

		// setIncludePath
		assert(Base\Ini::setIncludePath($includePath));
		assert(count(Base\Ini::getIncludePath()) === 1);

		// addIncludePath

		// opcache
		assert(is_bool(Base\Ini::opcache()));

		// xdebug
		assert(is_bool(Base\Ini::xdebug()));

		// apcu
		assert(is_bool(Base\Ini::apcu()));

		// important
		assert(count(Base\Ini::important()) === 34);

		// session
		assert(count(Base\Ini::session()) === 31);

		// requirement
		assert(empty(Base\Ini::requirement()));

		// setDefault

		return true;
	}
}
?>