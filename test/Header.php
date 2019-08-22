<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// header
class Header extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		Base\Response::setContentType("html");
		$request = Base\Request::headers();
		$response = Base\Response::headers();
		$contentType = Base\Header::setContentType('json',$response);
		$status = array("HTTP/1.0 200 OK","test: ok");
		$status2 = array("HTTP/1.0 404 Not Found","HTTP/1.0 200 OK","test: ok");
		$http2 = array("HTTP/2.0 200 OK","test: ok");
		$meta = array('mime'=>'text/json','basename'=>'json.json','size'=>4000);
		$protocol = Base\Server::httpProtocol();

		// isStatus
		assert(Base\Header::isStatus("HTTP/1.0 200 OK"));
		assert(Base\Header::isStatus("http/1.0 200 OK"));
		assert(!Base\Header::isStatus("test"));

		// is200
		assert(Base\Header::is200($status));
		assert(!Base\Header::is200($contentType));
		assert(Base\Header::is200($http2));

		// isCodePositive
		assert(Base\Header::isCodePositive($status));
		assert(Base\Header::isCodePositive($http2));
		
		// isCodeError
		assert(!Base\Header::isCodeError($status));
		assert(Base\Header::isCodeError($status2));
		
		// isCodeServerError
		assert(!Base\Header::isCodeServerError($status2));
		
		// isCodeValid
		assert(!Base\Header::isCodeValid(350));
		assert(Base\Header::isCodeValid(301));

		// isStatusTextValid
		assert(Base\Header::isStatusTextValid("Found"));
		assert(!Base\Header::isStatusTextValid("Foundz"));

		// isHtml
		assert(!Base\Header::isHtml($status));
		assert(!Base\Header::isHtml($contentType));

		// isJson
		assert(!Base\Header::isJson($status));
		assert(Base\Header::isJson($contentType));

		// isXml
		assert(!Base\Header::isXml($status));
		assert(!Base\Header::isXml($contentType));

		// hasStatus
		assert(!Base\Header::hasStatus($request));
		assert(!Base\Header::hasStatus($response));
		assert(Base\Header::hasStatus($status));
		assert(Base\Header::hasStatus($http2));

		// isCode
		assert(Base\Header::isCode(200,$status));
		assert(Base\Header::isCode(array(301,'200'),$status));
		assert(Base\Header::isCode("200",$status));
		assert(!Base\Header::isCode(200,$request));
		
		// isCodeBetween
		assert(Base\Header::isCodeBetween(100,300,$status));
		assert(!Base\Header::isCodeBetween(250,300,$status));
		
		// isCodeIn
		assert(Base\Header::isCodeIn(250,$status));
		assert(Base\Header::isCodeIn(299,$status));
		assert(!Base\Header::isCodeIn(300,$status));
		
		// isContentType
		assert(Base\Header::isContentType('json',$contentType));
		assert(Base\Header::isContentType('text/json',$contentType));
		assert(Base\Header::isContentType('text/json; charset=UTF-8',$contentType));
		assert(!Base\Header::isContentType('html',$request));

		// isHttp1
		assert(Base\Header::isHttp1($status));
		assert(!Base\Header::isHttp1($http2));

		// isHttp2
		assert(!Base\Header::isHttp2($status));
		assert(Base\Header::isHttp2($http2));
		assert(Base\Header::isHttp2("HTTP/2.0 200 OK"));
		assert(!Base\Header::isHttp2("HTTP/1.0 200 OK"));

		// keyCase
		assert(Base\Header::keyCase("accept-language") === "Accept-Language");
		assert(Base\Header::keyCase("test") === "Test");
		assert(Base\Header::keyCase("TEST") === "Test");

		// parse
		assert(Base\Header::parse(array("HTTP/1.0 404 Not Found"),Base\Header::option()) === array('status'=>"HTTP/1.0 404 Not Found"));
		assert(count(Base\Header::parse(array("HTTP/1.0 404 Not Found","Set-Cookie: james","Set-Cookie: james2"),Base\Header::option())['Set-Cookie']) === 2);
		assert(Base\Header::parse(array('test'=>2,"HTTP/1.0 404 Not Found","HTTP/1.0 404 Test"),Base\Header::option()) === array('status'=>"HTTP/1.0 404 Test",'test'=>2));

		// parseStatus
		assert(Base\Header::parseStatus("HTTP/1.0 404 Not Found") === array('protocol'=>'HTTP/1.0','code'=>404,'text'=>'Not Found'));
		assert(Base\Header::parseStatus("Mon, 13 Nov 2017 19:58:44 GMT") === null);
		assert(Base\Header::parseStatus(array("HTTP/1.0 404 Not Found")) === array('protocol'=>'HTTP/1.0','code'=>404,'text'=>'Not Found'));
		assert(Base\Header::parseStatus("HTTP/2 200") === array('protocol'=>'HTTP/2','code'=>200,'text'=>'OK'));

		// parseContentType
		assert(Base\Header::parseContentType('text/html; charset=UTF-8') === 'html');
		assert(Base\Header::parseContentType('text/html; charset=UTF-8',false) === 'text/html');
		assert(Base\Header::parseContentType('text/html') === 'html');
		assert(Base\Header::parseContentType('application/pdf') === 'pdf');
		assert(Base\Header::parseContentType('application/test') === 'application/test');

		// list
		assert(Base\Header::list(array('HTTP/1.0 404 NOT FOUND','Location'=>'test')) === array('HTTP/1.0 404 NOT FOUND','Location: test'));
		assert(Base\Header::list($status) === array('HTTP/1.0 200 OK','Test: ok'));
		assert(Base\Header::list("test: ok") === array("Test: ok"));
		assert(Base\Header::list(array("test"=>"ok")) === array("Test: ok"));
		assert(Base\Header::list(array("test"=>array("ok","ok2"))) === array("Test: ok","Test: ok2"));
		assert(Base\Header::list("HTTP/1.1 200 OK") === array("HTTP/1.1 200 OK"));
		assert(Base\Header::list(array("status"=>"HTTP/1.1 200 OK")) === array("HTTP/1.1 200 OK"));
		assert(Base\Header::list(array("status"=>array("HTTP/1.1 404 Not Found","HTTP/1.1 200 OK"))) === array("HTTP/1.1 200 OK"));
		assert(count(Base\Header::list($request)) === count($request));
		assert(Base\Header::list($response) === headers_list());
		assert(Base\Header::list(Base\Header::arr($status)) === Base\Header::list($status));
		assert(Base\Header::list("Test: ok \r\nHTTP/1.1 OK 200 \r\n Test2:what2") === array('HTTP/1.1 OK 200','Test: ok','Test2: what2'));
		assert(Base\Header::list(array('set-cookie '=>array('test','test2'))) === array('Set-Cookie: test','Set-Cookie: test2'));
		assert(Base\Header::list(array('james'=>function() { return 'OK'; })) === array(0=>'James: OK'));

		// keyValue
		assert(Base\Header::keyValue(array('james'=>function() { return 'OK'; }),Base\Header::option()) === array());

		// uni
		assert(Base\Header::list(array('set-cookie'=>array('test','test'))) === array('Set-Cookie: test','Set-Cookie: test'));
		assert(Base\Header::list(array('set-cookie'=>array('test','test'),"HTTP/1.1 200 OK"))[0] === "HTTP/1.1 200 OK");

		// prepareArr
		assert(Base\Header::prepareArr(array(' test '=>' test ')) === array(' test '=>' test '));

		// prepareStr
		assert(Base\Header::prepareStr("Test:  ok \r\nHTTP/1.1 OK 200 \r\n Test2 : what2") === array('Test:  ok','HTTP/1.1 OK 200','Test2 : what2'));

		// explodeStr
		assert(Base\Header::explodeStr("Test:  ok \r\nHTTP/1.1 OK 200 \r\n Test2 : what2") === array('Test:  ok','HTTP/1.1 OK 200','Test2 : what2'));

		// setMerge
		assert(Base\Header::setMerge('cookie','123',array('cookie'=>'456')) === array('cookie'=>array('456','123')));

		// setsMerge
		assert(Base\Header::setsMerge(array('cookie'=>'123'),array('cookie'=>'456')) === array('cookie'=>array('456','123')));

		// prepareContentType
		assert(Base\Header::prepareContentType("txt") === 'text/plain; charset=UTF-8');
		assert(Base\Header::prepareContentType("html") === 'text/html; charset=UTF-8');
		assert(Base\Header::prepareContentType("pdf") === 'application/pdf');
		assert(Base\Header::prepareContentType("text/html") === 'text/html; charset=UTF-8');
		assert(Base\Header::prepareContentType("text/html",false) === 'text/html');

		// code
		assert(Base\Header::code("HTTP/1.0 404 Not Found") === 404);
		assert(Base\Header::code(array("HTTP/1.0 200 Not Found")) === 200);
		assert(Base\Header::code($status) === 200);

		// protocol
		assert(Base\Header::protocol("HTTP/1.0 404 Not Found") === 'HTTP/1.0');
		assert(Base\Header::protocol(array("HTTP/2.0 404 Not Found")) === 'HTTP/2.0');
		assert(Base\Header::protocol($status) === 'HTTP/1.0');

		// statusText
		assert(Base\Header::statusText("HTTP/1.0 404 Not Found") === 'Not Found');
		assert(Base\Header::statusText(301) === 'Moved Permanently');
		assert(Base\Header::statusText(array("HTTP/1.0 404 Not Found")) === 'Not Found');
		assert(Base\Header::statusText($status) === 'OK');

		// statusTextFromCode
		assert(Base\Header::statusTextFromCode(301) === 'Moved Permanently');
		assert(Base\Header::statusTextFromCode(3012) === null);

		// codeFromStatusText
		assert(Base\Header::codeFromStatusText("Found") === 302);
		assert(Base\Header::codeFromStatusText("Foundz") === null);

		// getResponseStatus
		assert(count(Base\Header::getResponseStatus()) === 61);

		// status
		assert(Base\Header::status(404) === $protocol.' 404 Not Found');
		assert(Base\Header::status("HTTP/1.0 404 Not Found") === 'HTTP/1.0 404 Not Found');
		assert(Base\Header::status(array("HTTP/1.0 404 Not Found")) === 'HTTP/1.0 404 Not Found');
		assert(Base\Header::status($status) === 'HTTP/1.0 200 OK');

		// makeStatus
		assert(Base\Header::makeStatus(array('protocol'=>'HTTP/1.1','code'=>200,'text'=>'ok')) === 'HTTP/1.1 200 ok');

		// contentType
		assert(Base\Header::contentType($request) === null);
		assert(Base\Header::contentType($response) === 'text/html');
		assert(Base\Header::contentType($response,2) === 'html');
		assert(Base\Header::contentType(array("Content-type: text/csv; charset=latin-1"),0) === 'text/csv; charset=latin-1');
		assert(Base\Header::contentType(array("Content-type: text/csv; charset=latin-1"),false) === 'text/csv');
		assert(Base\Header::contentType(array("Content-type: text/csv; charset=latin-1"),true) === 'text/csv; charset=latin-1');
		assert(Base\Header::contentType(array("Content-type: text/csv; charset=latin-1")) === 'text/csv');
		assert(Base\Header::contentType(array("Content-type: text/csv; charset=latin-1"),2) === 'csv');

		// contentLength
		assert(Base\Header::contentLength(array("Content-type: text/csv; charset=latin-1")) === null);
		assert(Base\Header::contentLength(array("Content-length: 200","Content-type: text/csv; charset=latin-1")) === 200);

		// setContentType
		assert(Base\Header::setContentType("json",$response)['Content-Type'] === 'text/json; charset=UTF-8');
		assert(Base\Header::contentType(Base\Header::setContentType("json",$response),0) === 'text/json; charset=UTF-8');
		assert(Base\Header::contentType(Base\Header::setContentType("json",$response),1) === 'text/json');
		assert(Base\Header::contentType(Base\Header::setContentType("json",$response),2) === 'json');

		// setProtocol
		assert(Base\Header::setProtocol(2.0,$status)['status'] === 'HTTP/2 200 OK');
		assert(empty(Base\Header::setProtocol(2.0,$request)['status']));

		// setCode
		assert(Base\Header::setCode(301,$status)['status'] === 'HTTP/1.0 301 Moved Permanently');
		assert(Base\Header::setStatus(301,$status)['status'] === $protocol.' 301 Moved Permanently');

		// setStatusText
		assert(Base\Header::setStatusText("WHAT",$status)['status'] === 'HTTP/1.0 200 WHAT');
		assert(empty(Base\Header::setStatusText("WHAT",$request)['status']));

		// setStatus
		assert(Base\Header::setStatus(301,$status)['status'] === $protocol.' 301 Moved Permanently');
		assert(Base\Header::setStatus("HTTP/1.0 404 Not Found",$status)['status'] === 'HTTP/1.0 404 Not Found');
		assert(Base\Header::list(Base\Header::setStatus("HTTP/1.1 404 Not Found",$response))[0] === 'HTTP/1.1 404 Not Found');

		// ok
		assert(Base\Header::ok($response)['status'] === $protocol.' 200 OK');
		assert(Base\Header::ok()['status'] === $protocol.' 200 OK');

		// moved
		assert(Base\Header::moved(true,$response)['status'] === $protocol.' 301 Moved Permanently');
		assert(Base\Header::moved(false,array("HTTP/1.1 301 Moved Permanently"))['status'] === 'HTTP/1.1 302 Found');
		assert(Base\Header::moved()['status'] === $protocol.' 301 Moved Permanently');

		// notFound
		assert(Base\Header::notFound($response)['status'] === $protocol.' 404 Not Found');
		assert(Base\Header::notFound()['status'] === $protocol.' 404 Not Found');

		// redirect
		assert(Base\Header::redirect("http://google.com",false,$status) === array('status'=>'HTTP/1.0 302 Found','test'=>'ok','Location'=>'http://google.com'));
		assert(Base\Header::redirect("http://google.com")['Location'] === 'http://google.com');

		// download
		assert(Base\Header::download($meta,$response)['Content-Length'] === 4000);
		assert(count(Base\Header::download($meta)) === 5);

		// toScreen
		assert(Base\Header::toScreen($meta,$response)['Content-Type'] === 'text/json; charset=UTF-8');
		assert(count(Base\Header::toScreen($meta)) === 3);

		// other
		assert(strpos(Base\Header::str($request,array('sort'=>true)),'Accept') === 0);
		assert(count(Base\Header::setMerge('set-cookie','lala',array('set-Cookie'=>'test'))['set-Cookie']) === 2);
		assert(count(Base\Header::setMerge('set-cookie','lala',array('set-Cookie'=>array('test','test2')))['set-Cookie']) === 3);
		assert(strlen(Base\Header::str(Base\Header::setMerge('set-cookie','lala',array('set-Cookie'=>array('test','test2'))))) === 53);
		assert(Base\Header::str(Base\Arr::keysLower($request)) === Base\Header::str($request));
		assert(Base\Header::arr(array('set-cookie '=>array('test','test2'))) === array('set-cookie'=>array('test','test2')));
		assert(strlen(Base\Header::str(array('SET-cookie '=>array('test','test2')))) === 35);
		assert(Base\Header::str(array("accept-language"=>"okz")) === "Accept-Language: okz");
		assert(Base\Header::arr(array("Test : bla","Test2: james")) === array('Test'=>'bla','Test2'=>'james'));
		assert(Base\Header::arr(array("Test: bla","Test: james",'Testt2: ok')) === array('Test'=>array('bla','james'),'Testt2'=>'ok'));
		assert(Base\Header::arr(array("Test: bla ","test2"=>"james","test"=>" ok")) === array('Test'=>array('bla','ok'),'test2'=>'james'));
		assert(Base\Header::arr($request) === $request);
		assert(Base\Header::arr($response) === $response);
		assert(Base\Header::arr($status) === array('status'=>'HTTP/1.0 200 OK','test'=>'ok'));
		assert(Base\Header::arr($status2) === Base\Header::arr($status));
		assert(Base\Header::arr("Test:  ok \r\nHTTP/1.1 OK 200 \r\n Test2 : what2") === array('status'=>'HTTP/1.1 OK 200','Test'=>'ok','Test2'=>'what2'));
		assert(Base\Header::set("host","ok",$request)['Host'] === 'ok');
		assert(Base\Header::sets(array("host"=>"okz",'jaems'=>'ok',"accept"=>array("test","test2",3)),$request)['jaems'] === 'ok');
		assert(Base\Header::sets(array("host"=>"okz","accept"=>array("test","test2",3)),$request)['Accept'] === array('test','test2',3));
		assert(count(Base\Header::sets(array("host"=>"okz","accept"=>array("test","test2",3)),array())['accept']) === 3);
		assert(Base\Header::exist('Host',$request));
		assert(Base\Header::exist('HOST',$request));
		assert(Base\Header::exist('test',array("Test: 2")));
		$count = count($response);
		assert(count(Base\Header::unset("pragma",$response)) === $count - 1);
		assert(count(Base\Header::unsets(array("pragma"),$response)) === $count - 1);
		assert(Base\Header::exists(array('Host'),$request));
		assert(Base\Header::exists(array('HOST','ACCEPT'),$request));
		assert(Base\Header::exists(array('TEST'),array("Test: 2")));
		assert(is_string(Base\Header::get('host',$request)));
		assert(Base\Header::get("status",$status) === 'HTTP/1.0 200 OK');
		assert(is_string(Base\Header::gets(array('host'),$request)['host']));
		assert(Base\Header::gets(array("status"),$status) === array('status'=>'HTTP/1.0 200 OK'));
		assert(strlen(Base\Header::implode($status)) === 25);
		assert(Base\Header::arr(array('test'=>2,'OK'=>function() {
			return 'James';
		})) === array('test'=>2,'OK'=>'James'));
		assert(is_string(Base\Header::arr(array('test'=>2,'OK'=>Base\Request::absolute()))['OK']));
		assert(Base\Header::arr(array('test2'=>true,'test'=>0,'user-agent'=>false,'ok'=>'0','james'=>'','james-test'=>null)) === array('test2'=>true,'test'=>0,'user-agent'=>false,'ok'=>'0'));
		assert(strlen(Base\Header::str(array('test2'=>true,'test'=>0,'user-agent'=>false,'ok'=>'0','james'=>'','james-test'=>null))) === 39);
		$headers = Base\Response::$config['default']['headers'];
		assert(count(Base\Header::arr($headers)) === count($headers));
		$headers = array('test'=>array(Base\Request::class,'host'),'james'=>array(Base\Request::class,'isSsl'));
		assert(Base\Header::arr(Base\Call::digStaticMethod($headers))['james'] === Base\Request::isSsl());
		
		// fingerprint
		assert(strlen(Base\Header::fingerprint(apache_request_headers(),array('User-Agent'))) === 40);
		assert(strlen(Base\Header::fingerprint(apache_request_headers(),array('user-Agent'))) === 40);
		assert(Base\Header::fingerprint(apache_request_headers(),array('User-Agentz')) === null);

		// clean
		Base\Response::unsetHeader("Content-Type");
		
		return true;
	}
}
?>