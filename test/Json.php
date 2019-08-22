<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// json
class Json extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$obj = new \stdclass();
		$obj->test = "test";

		// is
		assert(Base\Json::is('{"test":"testé"}'));
		assert(Base\Json::is("true"));
		assert(Base\Json::is("0"));
		assert(Base\Json::is('[1,2,3]'));
		assert(!Base\Json::is("string"));

		// isEmpty
		assert(Base\Json::isEmpty("0"));
		assert(Base\Json::isEmpty("[]"));

		// isNotEmpty
		assert(Base\Json::isNotEmpty("1"));
		assert(Base\Json::isNotEmpty("[0]"));

		// encode
		assert('["test"]' === Base\Json::encode(array('test')));
		assert('{"0":"test"}' === Base\Json::encode(array('test'),JSON_FORCE_OBJECT));
		assert('{"test":"test"}' === Base\Json::encode($obj));
		assert('true' === Base\Json::encode(true));
		assert('2' === Base\Json::encode(2));
		assert('1.1' === Base\Json::encode(1.1)); // note, j'ai mis le ini serialize_precision à 14 sur le serveur pour que le résultat soit le même que sur le portable
		assert('[2,3,1,1.1]' === Base\Json::encode(array(2,3,1,1.1)));
		assert('"http://google.com"' === Base\Json::encode('http://google.com'));
		assert('"http:\/\/google.com"' === Base\Json::encode('http://google.com',0));
		assert('"\ud83c\udffc\ud83d\udc66\ud83c\udffc\ud83d\udc66\ud83c\udffc\ud83d\udc66"' === Base\Json::encode('🏼👦🏼👦🏼👦',0));
		assert('"👦🏼👦🏼👦"' === Base\Json::encode('👦🏼👦🏼👦'));

		// encodeOption

		// encodePretty
		$array = array('test'=>array('👦🏼👦🏼👦','http://google.com'));
		assert(Base\Json::encodePretty($array) === Base\Json::encodeOption($array,JSON_PRETTY_PRINT));

		// encodeSpecialchars
		assert(htmlspecialchars('{"test":"test<>"}',ENT_QUOTES,Base\Encoding::getCharset(),false) === Base\Json::encodeSpecialchars(array('test'=>'test<>')));

		// encodeVar
		assert(Base\Json::encodeVar("app",array(1,2,3)) === "app = [1,2,3];");
		assert(Base\Json::encodeVar("app",array('test'=>'ok')) === 'app = {"test":"ok"};');

		// var
		assert(Base\Json::var("app","test") === "app = test;");

		// decode
		assert(2 === Base\Json::decode('2'));
		assert(2.1 === Base\Json::decode('2.1'));
		assert(true === Base\Json::decode('true'));
		assert(null === Base\Json::decode('string'));
		assert(array("test"=>"testé") === Base\Json::decode('{"test":"testé"}'));
		assert(array("test"=>12345) === Base\Json::decode('{"test":12345}'));
		assert(array("test"=>"12345678901234567890") === Base\Json::decode('{"test":12345678901234567890}'));
		assert(array("test"=>123456789012345) === Base\Json::decode('{"test":123456789012345}'));
		assert($array === Base\Json::decode('{"test":["👦🏼👦🏼👦","http://google.com"]}'));

		// decodeKeys
		assert(array('test'=>1,'test2'=>null) === Base\Json::decodeKeys(array('test','test2'),'{"test":1}'));

		// decodeKeysExists
		assert($array === Base\Json::decodeKeysExists(array('test'),'{"test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert(null === Base\Json::decodeKeysExists(array('test','test2'),'{"test":["👦🏼👦🏼👦","http://google.com"]}'));

		// error
		assert(count(Base\Json::error()) === 2);

		// arr
		assert(Base\Json::arr(Base\Json::encode(array(1,"tet"=>2,3))) === array(1,"tet"=>2,3));

		// onSet
		assert(Base\Json::onSet(array(1,2,3)) === '[1,2,3]');

		// onGet
		assert(Base\Json::onGet(array(1,2,3)) === array(1,2,3));
		assert(Base\Json::onGet('[1,2,3]') === array(1,2,3));

		// other
		assert(Base\Json::count('1') === 0);
		assert(1 === Base\Json::count('{"test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert(3 === Base\Json::count('{"test":["👦🏼👦🏼👦","http://google.com"]}',true));
		assert('👦🏼👦🏼👦' === Base\Json::index("0/0",'{"test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert(array('OK',"1/-1"=>'http://google.com',"2"=>null) === Base\Json::indexes(array("0","1/-1",'2'),'{"bla":"OK","test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert('👦🏼👦🏼👦' === Base\Json::get("test/0",'{"test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert('http://google.com' === Base\Json::get("test/1",'{"test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert(array('bla'=>'OK',"test/1"=>'http://google.com','blaz'=>null) === Base\Json::gets(array("bla","test/1",'blaz'),'{"bla":"OK","test":["👦🏼👦🏼👦","http://google.com"]}'));
		assert(array('test'=>1) === Base\Json::slice("test","test",'{"test":1,"test2":2,"test3":3}'));
		assert(array('test'=>1,'test2'=>2) === Base\Json::slice("test","test2",'{"test":1,"test2":2,"test3":3}'));
		assert(array('test'=>1,'test2'=>2) === Base\Json::sliceIndex(0,2,'{"test":1,"test2":2,"test3":3}'));
		assert(Base\Json::set("test",2,'{"test":["👦🏼👦🏼👦","http://google.com"]}') === array('test'=>2));
		assert(Base\Json::set("test2",2,'{"test":["👦🏼👦🏼👦","http://google.com"]}')['test2'] === 2);
		assert(Base\Json::sets(array("test"=>2),'{"test":["👦🏼👦🏼👦","http://google.com"]}') === array('test'=>2));
		assert(Base\Json::unset("test/1",'{"test":["👦🏼👦🏼👦","http://google.com"]}') === array('test'=>array("👦🏼👦🏼👦")));
		assert(Base\Json::unset("test/0",'{"test":["👦🏼👦🏼👦","http://google.com"]}')['test'][1] === "http://google.com");
		assert(Base\Json::unsets(array('test/0','test/1'),'{"test":["👦🏼👦🏼👦","http://google.com"]}') === array('test'=>array()));
		assert(Base\Json::splice("test",true,'{"test":1,"test2":2,"test3":3}') === array('test2'=>2,'test3'=>3));
		assert(Base\Json::splice("test",true,'{"test":1,"test2":2,"test3":3}','{"what":"ok"}') === array('what'=>'ok','test2'=>2,'test3'=>3));
		assert(Base\Json::spliceIndex(0,1,'{"test":1,"test2":2,"test3":3}') === array('test2'=>2,'test3'=>3));
		assert(Base\Json::spliceIndex(0,1,'{"test":1,"test2":2,"test3":3}',array('what'=>'ok'))['what'] === 'ok');
		assert(count(Base\Json::insert("test",array('what'=>'ok'),'{"test":1,"test2":2,"test3":3}')) === 4);
		assert(count(Base\Json::insert("test",'{"what":"ok"}','{"test":1,"test2":2,"test3":3}')) === 4);
		assert(count(Base\Json::insertIndex(0,array('what'=>'ok'),'{"test":1,"test2":2,"test3":3}')) === 4);
		assert(Base\Json::isCount(3,"[1,2,3]"));
		assert(Base\Json::isMinCount(1,"[1,2,3]"));
		assert(!Base\Json::isMaxCount(2,"[1,2,3]"));
		
		return true;
	}
}
?>