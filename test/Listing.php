<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// listing
class Listing extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$string = "test:bla\njames:ok \nwhat:123\nJames: YEHA ";

		// isSeparatorStart
		assert(Base\Listing::isSeparatorStart("\nok"));

		// isSeparatorEnd
		assert(Base\Listing::isSeparatorEnd("ok\n"));

		// hasSeparatorDouble
		assert(Base\Listing::hasSeparatorDouble("ok\n\nok"));
		assert(!Base\Listing::hasSeparatorDouble($string));

		// getSeparator
		assert(Base\Listing::getSeparator(0) === "\n");
		assert(Base\Listing::getSeparator(1) === ':');
		assert(Base\Listing::getSeparator(1,1) === ': ');
		assert(Base\Listing::getSeparator(2) === null);

		// str
		assert(strlen(Base\Listing::str($string)) === 37);

		// parse
		assert(Base\Listing::parse(array('key'=>'test '),Base\Listing::option()) === array('key'=>'test '));

		// arr
		assert(Base\Listing::arr($string) === array('test'=>'bla','james'=>'ok','what'=>'123','James'=>'YEHA'));
		assert(Base\Listing::arr(array("jameszzz"=>"ok")) === array("jameszzz"=>"ok"));

		// prepareStr
		assert(count(Base\Listing::prepareStr($string,Base\Listing::option())) === 4);

		// prepareArr
		assert(Base\Listing::prepareArr(array('key'=>'test '),Base\Listing::option()) === array('key'=>'test '));

		// list 
		assert(Base\Listing::list("test:bla\njames: ok \n lol: ooo") === array('test:bla','james:ok','lol:ooo'));
		assert(count(Base\Listing::list(array('test'=>'bla','james'=>'ok'))) === 2);
		assert(count(Base\Listing::list(array('test'=>'bla','james'=>array('ok','ok2')))) === 3);

		// uni
		assert(Base\Listing::keyValue(array('test'=>'bla','james'=>'ok'),Base\Listing::option()) === array('test'=>'bla','james'=>'ok'));
		assert(Base\Listing::keyValue(array('test'=>'bla','james'=>'ok'),array('caseImplode'=>CASE_UPPER)) === array('TEST'=>'bla','JAMES'=>'ok'));

		// implode
		assert(strlen(Base\Listing::implode(array('test'=>'bla','james'=>'ok'))) === 17);
		assert(strlen(Base\Listing::implode(Base\Listing::list("test:bla\njames: ok \n lol: ooo"))) === 25);
		assert(strlen(Base\Listing::implode(Base\Listing::list(array('test'=>'bla','james'=>'ok',' lol '=>' ooo ')))) === 25);
		assert(strpos(Base\Listing::implode(array('test'=>'bla','james'=>'ok','lol'=>'ooo'),array('caseImplode'=>'ucfirst')),'Test') === 0);
		assert(strlen(Base\Listing::implode(array('test'=>'ok'),array('start'=>true,'end'=>true))) === 9);

		// stripWrap
		assert(Base\Listing::stripWrap("test",true,true) === "\ntest\n");
		assert(Base\Listing::stripWrap("test",true,false) === "\ntest");
		assert(Base\Listing::stripWrap("\ntest\n",false,false) === "test");

		// stripStart
		assert("test" === Base\Listing::stripStart("\ntest"));

		// stripEnd
		assert("\ntest" === Base\Listing::stripEnd("\ntest"));

		// wrapStart
		assert("\ntest\n" === Base\Listing::wrapStart("\ntest\n"));

		// wrapEnd
		assert("\ntest\n" === Base\Listing::wrapEnd("\ntest"));

		// other
		assert(Base\Listing::exist('test',$string));
		assert(Base\Listing::exists(array('test','what'),$string));
		assert(!Base\Listing::exists(array('test','what','bla'),$string));
		assert(Base\Listing::same($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz "));
		assert(!Base\Listing::same($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
		assert(Base\Listing::sameCount($string,"tesz:bla\njames:ok \nwhat:123\nJames: YEHA "));
		assert(Base\Listing::sameKey($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
		assert(!Base\Listing::sameKey($string,"ztest:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
		assert(count(Base\Listing::prepend($string,"jameszzz:ok")) === 5);
		assert(count(Base\Listing::prepend($string,array("jameszzz"=>"ok"))) === 5);
		assert(count(Base\Listing::append($string,"jameszzz:ok")) === 5);
		assert(count(Base\Listing::append($string,array("jameszzz"=>"ok"))) === 5);
		assert(count(Base\Listing::append($string,"james:meh")) === 4);
		assert(Base\Listing::count($string) === 4);
		assert(Base\Listing::index(0,$string) === 'bla');
		assert(Base\Listing::indexes(array(1),$string) === array(1=>'ok'));
		assert(Base\Listing::get('test',$string) === 'bla');
		assert(Base\Listing::gets(array('test'),$string) === array('test'=>'bla'));
		assert(count(Base\Listing::set('test2','WHAT',$string)) === 5);
		assert(count(Base\Listing::sets(array('test2'=>'WHAT'),$string)) === 5);
		assert(count(Base\Listing::unset('test',$string)) === 3);
		assert(count(Base\Listing::unsets(array('test'),$string)) === 3);
		assert(count(Base\Listing::slice("test","what",$string)) === 3);
		assert(!(Base\Listing::slice("TEST","WHAT",$string)));
		Base\Listing::$config['sensitive'] = false;
		assert(count(Base\Listing::slice("TEST","WHAT",$string)) === 3);
		Base\Listing::$config['sensitive'] = true;
		assert(Base\Listing::sliceIndex(0,2,$string) === array('test'=>'bla','james'=>'ok'));
		assert(Base\Listing::sliceIndex(0,1,array('james'=>'ok','test'=>2)) === array('james'=>'ok'));
		assert(count(Base\Listing::splice("test","what",$string,"lalal:ok\nlala2:ok2")) === 3);
		assert(count(Base\Listing::splice("test","what",$string,array('lalal'=>'ok','lala2'=>'ok2'))) === 3);
		assert(Base\Listing::splice("james",true,array('james'=>'ok','test'=>2)) === array('test'=>2));
		assert(count(Base\Listing::spliceIndex(1,2,$string,"ok:lol\nja:noway")) === 4);
		assert(count(Base\Listing::spliceFirst($string,"lalal:ok\nlala2:ok2")) === 5);
		assert(count(Base\Listing::spliceLast($string,"lalal:ok\nlala2:ok2")) === 5);
		assert(count(Base\Listing::insert("test","lala:ok",$string)) === 5);
		assert(count(Base\Listing::insertIndex(0,array('lala'=>'ok','jameszz'=>true),$string)) === 6);
		assert(Base\Listing::keysStart("james",$string) === array('james'=>'ok'));
		assert(Base\Listing::keysStart("james",array('james_2'=>true,'asds'=>false)) === array('james_2'=>true));
		assert(count(Base\Listing::keysEnd("es",$string)) === 2);
		
		return true;
	}
}
?>