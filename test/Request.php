<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// request
class Request extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$path = Base\Request::path();
		$ssl = Base\Request::isSsl();
		Base\Request::setPath('');
		$requestUri = Base\Request::path();
		$host = Base\Request::host();
		$port = Base\Request::port();
		$ip = Base\Request::ip();
		$userAgent = Base\Request::userAgent();
		$lang = Base\Request::getHeader("accept-language");

		// isSsl
		assert(is_bool(Base\Request::isSsl()));

		// isAjax
		assert(!Base\Request::isAjax());

		// isGet
		assert(Base\Request::isGet());

		// isPost
		assert(!Base\Request::isPost());

		// isPostWithoutData
		assert(!Base\Request::isPostWithoutData());

		// isRefererInternal
		assert(Base\Request::isRefererInternal() === false);

		// isInternalPost
		assert(Base\Request::isInternalPost() === false);

		// isExternalPost
		assert(Base\Request::isExternalPost() === false);

		// isStandard
		assert(Base\Request::isStandard());

		// isPathEmpty
		assert(Base\Request::isPathEmpty());

		// isPathSafe
		assert(Base\Request::isPathSafe());
		Base\Request::setPath("/test/../../ok.pass");
		assert(!Base\Request::isPathSafe());
		Base\Request::setPath($path);

		// isCli
		assert(!Base\Request::isCli());
		
		// isFailedFileUpload
		assert(!Base\Request::isFailedFileUpload());
		
		// hasQuery
		assert(!Base\Request::hasQuery());

		// hasGet
		assert(!Base\Request::hasGet());
		$_GET['ok'] = 2;
		assert(Base\Request::hasGet());

		// hasPost
		assert(!Base\Request::hasPost());

		// hasData
		assert(Base\Request::hasData());
		unset($_GET['ok']);
		assert(!Base\Request::hasData());

		// hasEmptyGenuine
		assert(!Base\Request::hasEmptyGenuine());

		// hasUser
		assert(!Base\Request::hasUser());

		// hasPass
		assert(!Base\Request::hasPass());

		// hasFragment
		assert(!Base\Request::hasFragment());

		// hasIp
		assert(Base\Request::hasIp());

		// hasLangHeader
		assert(Base\Request::hasLangHeader());

		// hasUserAgent
		assert(Base\Request::hasUserAgent());

		// isDesktop
		assert(is_bool(Base\Request::isDesktop()));

		// isMobile
		assert(is_bool(Base\Request::isMobile()));

		// isOldIe
		assert(is_bool(Base\Request::isOldIe()));

		// isMac
		assert(is_bool(Base\Request::isMac()));

		// isLinux
		assert(is_bool(Base\Request::isLinux()));

		// isWindows
		assert(is_bool(Base\Request::isWindows()));

		// isBot
		assert(!Base\Request::isBot());

		// isIpLocal
		assert(is_bool(Base\Request::isIpLocal()));

		// isLang
		assert(Base\Request::isLang(Base\Request::lang()));

		// isScheme
		assert(Base\Request::isScheme(Base\Request::scheme()));

		// isHost
		assert(Base\Request::isHost($_SERVER['SERVER_NAME']));

		// isSchemeHost
		assert(Base\Request::isSchemeHost(Base\Request::schemeHost()));

		// isIp
		assert(Base\Request::isIp($_SERVER['REMOTE_ADDR']));

		// isLangHeader
		assert(Base\Request::isLangHeader(Base\Request::langHeader()));

		// getExists
		$_GET['test'] = 'ok';
		$_POST['test2'] = 'ok';
		$_SERVER['REQUEST_METHOD'] = 'POST';
		assert(Base\Request::getExists('test'));

		// postExists
		assert(Base\Request::postExists('test2'));
		assert(!Base\Request::postExists('test'));
		unset($_GET['test']);
		unset($_POST['test2']);
		$_SERVER['REQUEST_METHOD'] = 'GET';

		// headerExists
		assert(Base\Request::headerExists('User-Agent'));
		
		// hasFiles
		assert(!Base\Request::hasFiles());
		
		// id
		assert(strlen(Base\Request::id()) === 10);

		// info
		assert(count(Base\Request::info()) === 24);
		assert(count(Base\Request::info(true)) === 25);

		// export
		assert(count(Base\Request::export()) === 15);
		assert(count(Base\Request::export(true)) === 16);

		// lang
		assert(strlen(Base\Request::lang()) === 2);
		Base\Request::setPath("/en/james/fr");
		assert(Base\Request::pathLang() === 'en');
		Base\Request::setPath($requestUri);

		// setLangs
		Base\Request::setLangs(['en','fr']);

		// parse
		assert(count(Base\Request::parse()) === 8);

		// setSsl
		Base\Request::setSsl(true);
		assert(Base\Request::isSsl());
		assert(Base\Request::port() === 443);
		Base\Request::setSsl(false);
		assert(!Base\Request::isSsl());

		// setAjax
		Base\Request::setAjax();
		assert(Base\Request::isAjax());
		Base\Request::setAjax(false);
		assert(!Base\Request::isAjax());

		// scheme
		assert(in_array(Base\Request::scheme(),['http','https'],true));

		// setScheme
		Base\Request::setScheme("https");
		assert(Base\Request::isSsl());
		Base\Request::setScheme("http");
		assert(!Base\Request::isSsl());
		Base\Request::setSsl($ssl);

		// user
		assert(!Base\Request::user());

		// setUser
		Base\Request::setUser("test");
		assert(Base\Request::user() === 'test');
		Base\Request::setUser(null);
		assert(Base\Request::user() === null);

		// pass
		assert(!Base\Request::pass());

		// setPass
		Base\Request::setPass("test");
		assert(Base\Request::pass() === 'test');
		Base\Request::setPass(null);

		// host
		assert($_SERVER['SERVER_NAME'] === Base\Request::host());

		// setHost
		Base\Request::setHost("test.dev");
		assert(Base\Request::host() === "test.dev");
		Base\Request::setHost($host);

		// port
		assert(is_int(Base\Request::port()));

		// setPort
		Base\Request::setPort(2);
		assert(Base\Request::port() === 2);
		Base\Request::setPort($port);

		// path
		assert(is_string(Base\Request::path()));

		// setPath
		Base\Request::setPath("bla/test.jpg");
		assert(Base\Request::path() === '/bla/test.jpg');

		// pathinfo
		assert(count(Base\Request::pathinfo()) === 4);

		// dirname
		assert(Base\Request::dirname() === "/bla");

		// basename
		assert(Base\Request::basename() === "test.jpg");

		// filename
		assert(Base\Request::filename() === "test");

		// extension
		assert(Base\Request::extension() === "jpg");

		// mime
		assert(Base\Request::mime() === 'image/jpeg');

		// pathStripStart
		$_SERVER['REQUEST_URI'] = '/test/blabla/james';
		assert(Base\Request::pathStripStart() === 'test/blabla/james');

		// pathExplode
		assert(count(Base\Request::pathExplode()) === 3);

		// pathGet
		assert(Base\Request::pathGet(0) === 'test');

		// pathGets
		assert(Base\Request::pathGets([-1,1000]) === [2=>'james',1000=>null]);

		// pathCount
		assert(Base\Request::pathCount() === 3);

		// pathSlice
		assert(Base\Request::pathSlice(0,2) === ['test','blabla']);
		$_SERVER['REQUEST_URI'] = $requestUri;

		// pathQuery
		assert($_SERVER['REQUEST_URI'] === Base\Request::pathQuery());

		// pathLang
		assert(Base\Request::pathLang() === null);
		Base\Request::setPath("/en/james/fr");
		assert(Base\Request::pathLang() === 'en');

		// query
		assert($_SERVER['QUERY_STRING'] === Base\Request::query());

		// setQuery
		Base\Request::setQuery("testé=2&bla=oké");
		assert(Base\Request::query() === "testé=2&bla=oké");
		assert($_GET === ["testé"=>2,"bla"=>"oké"]);
		Base\Request::setQuery(["testé"=>2,"bla"=>"oké"]);
		assert(Base\Request::query() === "testé=2&bla=oké");
		Base\Request::setQuery(["testé"=>2,"bla"=>"oké"],true);
		assert(Base\Request::query() === "test%C3%A9=2&bla=ok%C3%A9");
		assert($_GET === ["testé"=>2,"bla"=>"oké"]);

		// removeQuery
		Base\Request::removeQuery();
		assert(Base\Request::query() === '');
		assert($_GET === []);

		// fragment
		assert(!Base\Request::fragment());

		// setFragment
		Base\Request::setFragment("what");
		assert(Base\Request::fragment() === "what");
		Base\Request::setFragment(null);
		assert(!Base\Request::fragment());

		// method
		assert(Base\Str::lower($_SERVER['REQUEST_METHOD']) === Base\Request::method());

		// setMethod
		Base\Request::setMethod("post");
		assert(Base\Request::isPost());
		Base\Request::setMethod("GET");
		assert(Base\Request::isGet());

		// timestamp
		assert(is_int(Base\Request::timestamp()));

		// timestampFloat
		assert(is_float(Base\Request::timestampFloat()));

		// setTimestamp
		Base\Request::setTimestamp(1);
		assert(Base\Request::timestamp() === 1);

		// setTimestampFloat
		Base\Request::setTimestampFloat(Base\Date::microtime());

		// schemeHost
		assert(Base\Request::scheme()."://".Base\Request::host() === Base\Request::schemeHost());

		// get
		assert($_GET === Base\Request::get());

		// post
		assert([] === Base\Request::post());

		// files
		assert(Base\Request::files() === []);

		// csrf
		assert(Base\Request::csrf() === null);
		$_POST['-csrf-'] = Base\Str::random(40);
		assert(strlen(Base\Request::csrf()) === 40);
		$_POST['-csrf-'] = Base\Str::random(41);
		assert(Base\Request::csrf() === null);
		$_POST['-csrf-'] = null;
		assert(Base\Request::csrf() === null);

		// setPost
		Base\Request::setPost(['test'=>2,'-what-'=>'ok','MAX_FILE_SIZE'=>200]);
		assert(Base\Request::post() === ['test'=>2,'-what-'=>'ok','MAX_FILE_SIZE'=>200]);
		assert(Base\Request::post(true,true) === ['test'=>2]);
		Base\Request::setPost([]);

		// headers
		assert(is_array(Base\Request::headers()));

		// getHeader
		assert(Base\Request::getHeader('User-Agent') === Base\Request::userAgent());
		assert(Base\Request::getHeader('user-Agent') === Base\Request::userAgent());

		// setHeader
		assert(empty($_SERVER['HTTP_JAMES']));
		Base\Request::setHeader("JAMES",2);
		assert(Base\Request::getHeader("james") === 2);
		assert($_SERVER['HTTP_JAMES'] === 2);

		// unsetHeader
		Base\Request::unsetHeader("james");
		assert(Base\Request::getHeader("james") === null);
		assert(empty($_SERVER['HTTP_JAMES']));

		// setHeaders

		// ip
		assert(Base\Validate::isIp(Base\Request::ip()));
		$_SERVER['REMOTE_ADDR'] = 'abc';
		assert(Base\Request::ip(false) === 'abc');
		assert(Base\Request::ip(true) === '0.0.0.0');
		assert(Base\Request::ip() === 'abc');

		// setIp
		Base\Request::setIp("4.168.1.1");
		assert(Base\Request::ip() ==="4.168.1.1");
		Base\Request::setIp($ip);

		// userAgent
		assert($_SERVER['HTTP_USER_AGENT'] === Base\Request::userAgent());

		// setUserAgent
		Base\Request::setUserAgent("Mozilla");
		assert(Base\Request::userAgent() === "Mozilla");
		assert(array_key_exists('User-Agent',Base\Request::headers()));
		Base\Request::setUserAgent(null);
		assert(!array_key_exists('User-Agent',Base\Request::headers()));
		assert(Base\Request::userAgent() === null);
		assert(Base\Request::browserCap() === null);
		Base\Request::setUserAgent($userAgent);

		// referer

		// setReferer
		assert(Base\Request::setReferer("https://google.com/test") === null);
		assert(Base\Request::referer() === "https://google.com/test");
		assert(Base\Request::referer(true) === null);
		assert(Base\Request::setReferer(null) === null);
		assert(Base\Request::referer() === null);

		// browserCap
		assert(is_array(Base\Request::browserCap()));

		// browserName
		assert(is_string(Base\Request::browserName()));

		// browserPlatform
		assert(is_string(Base\Request::browserPlatform()));

		// browserDevice
		assert(is_string(Base\Request::browserDevice()));

		// langHeader
		assert(strlen(Base\Request::langHeader()) === 2);

		// setLangHeader
		Base\Request::setLangHeader("de_de");
		assert(Base\Request::langHeader() === 'de');

		// fingerprint
		assert(strlen(Base\Request::fingerprint(['User-Agent'])) === 40);

		// redirect
		Base\Request::setPath("/test/../bla");
		assert(Base\Request::redirect()  === '/');
		Base\Request::setPath("/test/bla//ok");
		assert(Base\Request::redirect() === '/en/test/bla/ok');
		Base\Request::setPath("/test/bla/");
		assert(Base\Request::redirect() === '/en/test/bla');
		Base\Request::setPath("/test/bla");
		assert(Base\Request::redirect() === "/en/test/bla");
		Base\Request::setPath("/en/test/bla");
		assert(Base\Request::redirect() === null);
		Base\Request::setPath("/fr/test/bla");
		assert(Base\Request::redirect() === null);
		Base\Request::setPath("");
		assert(Base\Request::redirect() === null);
		Base\Request::setPath("home");
		assert(Base\Request::redirect() === "/en/home");
		Base\Request::setPath("home/");
		assert(Base\Request::redirect() === "/en/home");
		Base\Request::setPath("/test/bla");
		assert(is_string(Base\Request::redirect(true)));
		Base\Request::setPath("sitemap.xml");
		assert(Base\Request::redirect() === null);
		Base\Request::setPath($requestUri);
		Base\Request::setPath("/media");
		assert(Base\Request::redirect() === '/en/media');
		Base\Request::setPath("/media/");
		assert(Base\Request::redirect() === '/en/media');
		Base\Request::setPath($requestUri);

		// str
		assert(!empty(Base\Request::str()));

		// uri
		assert(Base\Request::uri(true) !== Base\Request::uri(false));

		// output
		assert(Base\Request::output() === Base\Request::relative());

		// relative
		assert(!empty(Base\Request::relative()));
		assert(Base\Request::relative() !== Base\Request::absolute());
		assert(Base\Request::relative() === '/');
		Base\Request::setQuery(['test'=>'ok','blaé'=>'ok']);
		Base\Request::setFragment("BAH");
		assert(Base\Request::relative() === '/?test=ok&bla%C3%A9=ok#BAH');

		// absolute
		Base\Request::setQuery('');
		Base\Request::setFragment('');
		assert(!Base\Str::isEnd('/',Base\Request::absolute()));
		Base\Request::setQuery("");
		Base\Request::setFragment(null);
		Base\Request::setLangHeader($lang);
		assert(Base\Request::absolute() === Base\Request::schemeHost());

		// cleanup
		Base\Request::setMethod('get');
		
		return true;
	}
}
?>