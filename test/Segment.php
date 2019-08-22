<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// segment
class Segment extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// isWrapped
		assert(Base\Segment::isWrapped("[]","[test]"));
		assert(!Base\Segment::isWrapped("[]","[test"));

		// has
		assert(Base\Segment::has("[]","test/[ok]-1/test3/[four]/[five]"));
		assert(!Base\Segment::has("[]","test/ok]-1/test3/[four/five}"));
		assert(Base\Segment::has("[]","test/ok]-1/test3/[four/[five]"));
		assert(!Base\Segment::has("[]","test/ok]-1/test3/[four/[five"));

		// getDelimiter
		assert(Base\Segment::getDelimiter("[]") === array('[',']'));
		assert(Base\Segment::getDelimiter(null) === array('[',']'));
		assert(Base\Segment::getDelimiter(null,true) === array('\[','\]'));
		assert(Base\Segment::getDelimiter(array('a','b')) === array('a','b'));
		assert(Base\Segment::getDelimiter(array('a','b'),true) === array('a','b'));

		// wrap
		assert(Base\Segment::wrap("[]","test") === "[test]");
		assert(Base\Segment::wrap("[]","[test]") === "[test]");

		// strip
		assert(Base\Segment::strip("[]","[test]") === "test");
		assert(Base\Segment::strip("[]","test") === "test");

		// escape
		assert(Base\Segment::escape("[]") === "[]");
		assert(Base\Segment::escape("[") === "\[");

		// count
		$string = "test [test] asd [test2] [test3][test4]";
		assert(4 === Base\Segment::count("[]",$string));
		assert(0 === Base\Segment::count("()",$string));
		$string = "test %test%";
		assert(1 === Base\Segment::count("%",$string));
		$string = "test -test- -bla-";
		assert(2 === Base\Segment::count("-",$string));
		$string = "bla1bla bla2bla bla3bla";
		assert(3 === Base\Segment::count("bla",$string));
		assert(3 === Base\Segment::count("[]","test/[ok]-1/test3/[four]/[five]"));
		assert(2 === Base\Segment::count("[]","test/[ok]-1/test3/[four]/five"));

		// exists
		$string = "test [test] asd [test2] [test3][test4]";
		assert(Base\Segment::exists("[]","test2",$string));
		assert(Base\Segment::exists("[]",array("test2",'test3'),$string));
		assert(!Base\Segment::exists("[]",array("test2",'test3','z'),$string));
		assert(!Base\Segment::exists("[]","test2z",$string));

		// are
		$string = "test [test] asd [test2] [test3][test4]";
		assert(Base\Segment::are("[]",array("test","test2","test3","test4"),$string));
		assert(!Base\Segment::are("[]",array("test","test2","test3","test5"),$string));
		assert(!Base\Segment::are("[]",array("test","test2","test3"),$string));

		// get
		$string = "test [test] asd [test2] [test3][test4]";
		assert(array("test","test2","test3","test4") === Base\Segment::get("[]",$string));
		assert(array() === Base\Segment::get("()",$string));
		$string = "test %test%";
		assert(count(Base\Segment::get("%",$string))===1);
		assert(array("ok","four","five") === Base\Segment::get("[]","test/[ok]-1/test3/[four]/[five]"));
		assert(Base\Segment::get(null,"test [name_[lang]] [ok]") === array('name_[lang','ok'));
		assert(Base\Segment::get(null,"test [name_%lang%] [ok]") === array('name_%lang%','ok'));
		assert(Base\Segment::get(null,"test [name_%lang%] [ok]",true) === array('name_en','ok'));
		$string = "test [test] asd [test] [test3][test]";
		assert(Base\Segment::get(null,$string) === array('test','test','test3','test'));
		$string = "test [test/james] asd [test2] [test3][test4]";
		assert(Base\Segment::get(null,$string)[0] === 'test/james');

		// set
		$string = "test [test] asd [tést2/test3]";
		assert(Base\Segment::set(null,"tést2/test3",'bla',$string) === 'test [test] asd bla');
		$string = "test [test] asd [tést2] [test3][test4]";
		assert("test [test] asd blaé [test3][test4]" === Base\Segment::set(array("[","]"),"tést2","blaé",$string));
		$string = "test [test] asd [tést2/name] [test3][test4]";
		assert("test [test] asd blaé [test3][test4]" === Base\Segment::set(array("[","]"),"tést2",array('name'=>"blaé"),$string));
		assert("test [test] asd [tést2/name] [test3][test4]" === Base\Segment::set(array("[","]"),"tést2",array('name'=>array('HWAT')),$string));
		$string = "test [test] asd [tést2] [test3][test4]";
		assert(Base\Segment::set(null,"tést2",null,$string) === 'test [test] asd  [test3][test4]');

		// setArray
		$array = array('ok'=>"test [test] asd [tést2] [test3][test4]");
		assert(array('ok'=>"test [test] asd blaé [test3][test4]") === Base\Segment::setArray(array("[","]"),"tést2","blaé",$array));

		// sets
		$string = "test [test] asd [test2] [test3][test4]";
		$replace = array('test'=>'oui','test3'=>'non','test2'=>'ok');
		assert("test oui asd ok non[test4]" === Base\Segment::sets(array("[","]"),$replace,$string));
		assert("test/ok1-1/test3/four2/five2" === Base\Segment::sets("[]",array('ok'=>'ok1','four'=>'four2','five'=>'five2'),"test/[ok]-1/test3/[four]/[five]"));
		assert(Base\Segment::sets(null,array('ok'=>2,'name_en'=>'well'),"test [ok] [name_%lang%]") === "test 2 well");
		$string = "test [test] asd [test] [test3][test]";
		assert(Base\Segment::sets(null,array('test'=>'ok'),$string) === 'test ok asd ok [test3]ok');
		$string = "test [test] asd [test2] [test3][test4]";
		$replace = array('test'=>null,'test3'=>'non','test2'=>null);
		assert(Base\Segment::sets(null,$replace,$string) === 'test  asd  non[test4]');
		$string = "test [test] asd [test2/test3]";
		$replace = array('test'=>null,'test/test3'=>'non');
		assert(Base\Segment::sets(null,$replace,$string) === 'test  asd [test2/test3]');

		// setsArray
		$array = array("test [test] asd [test2] [test3][test4]");
		$replace = array('test'=>'oui','test3'=>'non','test2'=>'ok');
		assert(array("test oui asd ok non[test4]") === Base\Segment::setsArray(array("[","]"),$replace,$array));

		// unset
		$string = "test [test] asd [test2] [test3][test4]";
		assert(Base\Segment::unset("[]","test",$string) === "test  asd [test2] [test3][test4]");
		assert(Base\Segment::unset("[]","testz",$string) === $string);

		// unsets
		assert(Base\Segment::unsets("[]",array("test"),$string) === "test  asd [test2] [test3][test4]");
		assert(Base\Segment::unsets("[]",array("test","test3","bla",array("as")),$string) === "test  asd [test2] [test4]");
		assert(Base\Segment::unsets("[]",array(),$string) === $string);

		// prepare
		assert(Base\Segment::prepare('[test_%lang%]') === '[test_en]');
		assert(Base\Segment::prepare('[test_en]') === '[test_en]');

		// def
		assert(Base\Segment::def() === '[]');
		
		return true;
	}
}
?>