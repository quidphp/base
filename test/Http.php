<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// http
class Http extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// isScheme
		assert(Base\Http::isScheme('http'));
		assert(Base\Http::isScheme('HTTPS'));
		assert(!Base\Http::isScheme('HTTPSz'));

		// isHost
		assert(Base\Http::isHost('test.com'));

		// isPort
		assert(Base\Http::isPort(80));
		assert(Base\Http::isPort(443));
		assert(!Base\Http::isPort(81));

		// isMethod
		assert(Base\Http::isMethod('POST'));
		assert(Base\Http::isMethod('get'));
		assert(!Base\Http::isMethod('getz'));

		// scheme
		assert(Base\Http::scheme(true) === 'https');
		assert(Base\Http::scheme('https') === 'https');
		assert(Base\Http::scheme(443) === 'https');
		assert(Base\Http::scheme(80) === 'http');
		assert(Base\Http::scheme(200) === null);

		// port
		assert(Base\Http::port(true) === 443);
		assert(Base\Http::port(443) === 443);
		assert(Base\Http::port('http') === 80);
		assert(Base\Http::port('ftp') === null);

		// ssl
		assert(Base\Http::ssl(true));
		assert(Base\Http::ssl(443) === true);
		assert(Base\Http::ssl('https') === true);
		assert(!Base\Http::ssl('http'));

		// str
		assert(Base\Str::isStart(Base\Request::absolute(),Base\Http::str(Base\Request::info())));
		assert(Base\Str::isEnd(strtoupper(Base\Request::method()),Base\Http::str(Base\Request::info())));
		assert(!empty(Base\Http::str(Base\Request::info())));
		assert(Base\Str::isEnd('FALSE',Base\Http::str(Base\Request::info(),['ajax'])));

		// arr
		assert(count(Base\Http::arr(Base\Http::str(Base\Request::info()))) === 3);
		
		return true;
	}
}
?>