<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// arrs
class Arrs extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// typecast
		$y = 2;
		Base\Arrs::typecast($y);
		assert($y === array(array(2)));
		$y = array(2);
		Base\Arrs::typecast($y);
		assert($y === array(array(2)));
		$y = array(array(2));
		Base\Arrs::typecast($y);
		assert($y === array(array(2)));

		// cast
		assert(array(array(1,'true','false')) === Base\Arrs::cast(array(array('1','true','false'))));

		// castMore
		assert(array(array(1,array(true),false)) === Base\Arrs::castMore(array(array('1',array('true'),'false'))));

		// is
		assert(!Base\Arrs::is(array(1,2,3)));
		assert(Base\Arrs::is(array(1,array(),3)));
		assert(Base\Arrs::is(array(array(1),array(),array(2))));

		// isCleanEmpty
		assert(!Base\Arrs::isCleanEmpty(array('',null)));
		assert(Base\Arrs::isCleanEmpty(array('',array(''))));
		assert(Base\Arrs::isCleanEmpty(array('',array(null))));
		assert(!Base\Arrs::isCleanEmpty(array('',array(true))));

		// hasKeyCaseConflict
		assert(Base\Arrs::hasKeyCaseConflict(array('test'=>array('test'=>true,'TEST'=>false))));
		assert(!Base\Arrs::hasKeyCaseConflict(array('test'=>array('test'=>true,'TEST2'=>false))));

		// merge
		$merge1 = array("test"=>"test","test2"=>array(0=>"test3"));
		$merge2 = array("test"=>"test6","test2"=>array(0=>"test3"),"test5"=>array(0=>"test4"));
		assert(array("test"=>array("test","test6"),"test2"=>array(0=>"test3",1=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2));
		$merge2['test2'][0] = 'test1000';
		assert(array("test"=>array("test","test6"),"test2"=>array("test3","test1000"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2));
		assert(array("test"=>array("test","test6","test"),"test2"=>array("test3","test1000","test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2,$merge1));
		$merge1 = array("test"=>1,"test2"=>array(1,2,'ok'=>'james'),'test3'=>'ok','4'=>'ok','james'=>'what');
		$merge2 = array("test"=>2,"test2"=>array(9,'test'=>'ok'),"test3"=>"bla");
		assert(array("test"=>array(1,2),"test2"=>array(1,2,'ok'=>'james',9,'test'=>'ok'),"test3"=>array('ok','bla'),'0'=>'ok','james'=>'what') === Base\Arrs::merge($merge1,$merge2));
		$merge1 = array("test"=>"test","test2"=>array(0=>"test3"));
		$merge2 = array("test"=>"test6","test2"=>array(1=>"test3"),"test5"=>array(0=>"test4"));
		assert(array("test"=>array("test","test6"),"test2"=>array(0=>"test3",1=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2));
		$merge1 = array("test"=>"test","test2"=>array('test'=>"test3"));
		$merge2 = array("test"=>"test6","test2"=>array('test'=>'test1000',1=>"test3"),"test5"=>array(0=>"test4"));
		assert(array("test"=>array("test","test6"),"test2"=>array('test'=>array("test3","test1000"),0=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2));
		assert(array("test"=>array("test","test6","test"),"test2"=>array('test'=>array("test3","test1000","test3"),0=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2,$merge1));
		$merge1 = array("test"=>"test","test2"=>array("test3"));
		$merge2 = array("test"=>"test6","test2"=>array('test1000',1=>"test3"),"test5"=>array(0=>"test4"));
		assert(array("test"=>array("test","test6"),"test2"=>array("test3","test1000",2=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2));
		assert(array("test"=>array("test","test6","test"),"test2"=>array("test3","test1000","test3",'test3'),"test5"=>array(0=>"test4")) === Base\Arrs::merge($merge1,$merge2,$merge1));

		// replace
		$merge1 = array("test"=>"test","test2"=>array(0=>"test3"));
		$merge2 = array("test"=>"test6","test2"=>array(1=>"test3"),"test5"=>array(0=>"test4"));
		assert(array("test"=>"test6","test2"=>array(0=>"test3",1=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::replace($merge1,$merge2));
		assert(array("test"=>"test","test2"=>array(0=>"test3",1=>"test3"),"test5"=>array(0=>"test4")) === Base\Arrs::replace($merge1,$merge2,$merge1));
		assert(Base\Arrs::replace(array('test'=>array(0=>2,'test'=>'ok')),array('test'=>array(0=>2,'test2'=>'what'))) === array('test'=>array(0=>2,'test'=>'ok','test2'=>'what')));
		$merge1 = array("test"=>1,"test2"=>array(1,2),'test3'=>'ok','4'=>'ok');
		$merge2 = array("test"=>2,"test2"=>array('test'=>'ok'),"test3"=>"bla");
		assert(array("test"=>2,"test2"=>array(1,2,'test'=>'ok'),"test3"=>"bla",'4'=>'ok') === Base\Arrs::replace($merge1,$merge2));
		assert(Base\Arrs::replace(array('test')) === array('test'));

		// replaceWithMode
		$merge1 = array("test"=>"test","test2"=>array(2=>'well',1=>array('bla','test'=>'ok')));
		$merge2 = array("test"=>"test6","test2"=>array(1=>array("test3",'test2'=>'ok2')),"test5"=>array(0=>"test4"));
		assert(Base\Arrs::replaceWithMode(array(),$merge1,$merge2)['test2'][1] === array('test3','test'=>'ok','test2'=>'ok2'));
		assert(Base\Arrs::replaceWithMode(array('test2'),$merge1,$merge2)['test2'][1] === array('test3','test2'=>'ok2'));
		assert(Base\Arrs::replaceWithMode(array('test2'),$merge1,$merge2)['test2'][2] === 'well');
		assert(empty(Base\Arrs::replaceWithMode(array('=test2'),$merge1,$merge2)['test2'][2]));

		// replaceSpecial
		$target = array('@dev','@app');
		$all = array('@dev','@prod','@app','@cms');
		$replaceKeys = array();
		$array = array('test'=>'OK','bla'=>'yeah','meh'=>'nop','@dev'=>array('test'=>'MEH','meh'=>'ok'));
		$array2 = array('test'=>'OK2','@dev'=>array('meh'=>'more'),'@prod'=>array('test'=>'MEH2'));
		assert(Base\Arrs::replaceSpecial($target,$all,$replaceKeys,$array,$array2) === array('test'=>'OK2','bla'=>'yeah','meh'=>'more'));

		// clean
		$clean = array('',null,array());
		assert(array() === Base\Arrs::clean($clean));
		$clean = array('',null,array('',null));
		assert(array() === Base\Arrs::clean($clean));
		$clean = array('',null,true,false);
		assert(array(2=>true,3=>false) === Base\Arrs::clean($clean));
		$clean = array('',null,true,array(true,'s',''));
		assert(array(2=>true,3=>array(true,'s')) === Base\Arrs::clean($clean));
		$array = array(true,1,array(false));
		assert(array(true,1,2=>array(false)) === Base\Arrs::clean($array));
		$array = array(1,array());
		assert(array(1) === Base\Arrs::clean($array));

		// trim
		assert(Base\Arrs::trim(array(' test ',array('james '=>' test2 ')))[1]['james '] === 'test2');
		assert(Base\Arrs::trim(array(' test ',array('james '=>' test2 ')),true)[1]['james'] === 'test2');

		// trimClean
		assert(Base\Arrs::trimClean(array(' test ',array('james '=>' test2 ','',null)),true) === array('test',array('james'=>'test2')));
		assert(Base\Arrs::trimClean(array(' test ',array('james '=>' test2 ','',null)),true,true,true,true) === array('test',array('test2')));

		// get
		$array = array("test"=>array(2=>array("test3"=>"oui",'1t'=>"non")));
		assert("oui" === Base\Arrs::get("test/2/test3",$array));
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")));
		assert("oui" === Base\Arrs::get("test/test2/test3",$array));
		assert(null === Base\Arrs::get("test/test4/test2/test3",$array));
		assert(null === Base\Arrs::get("test/test2//test3",$array));
		assert("oui" === Base\Arrs::get(array("test","test2","test3"),$array));
		assert(null === Base\Arrs::get(array("test","test2/test3"),$array));
		assert(array("test3"=>"oui",'1t'=>"non") === Base\Arrs::get(array("test","test2"),$array));
		assert(null === Base\Arrs::get(array("test","test8","test2"),$array));
		assert(Base\Arrs::get(array("test"),$array)['test2']['test3'] === 'oui');
		$array = array('TEST'=>array(2=>array('TÉST'=>true)));
		assert(Base\Arrs::get('test/2/tést',$array,false) === true);

		// gets
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")));
		assert(array("test/test2/test3"=>"oui","test/test2/1t"=>"non") === Base\Arrs::gets(array(array("test","test2","test3"),"test/test2/1t"),$array));
		assert(array("test/test2/test3"=>"oui","ztest/test2/1t"=>null) === Base\Arrs::gets(array("test/test2/test3","ztest/test2/1t"),$array));
		$array = array('TEST'=>array(2=>array('TÉST'=>true)));
		assert(Base\Arrs::gets(array('Test/2/tést'),$array,false,true) === array('Test/2/tést'=>true));
		assert(Base\Arrs::gets(array(array('tESt',2,'TÉST')),$array,false,true) === array('tESt/2/TÉST'=>true));

		// indexPrepare
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")));
		assert(array(0,0,1) === Base\Arrs::indexPrepare("0/0/1",$array));
		assert(array(0,0,1) === Base\Arrs::indexPrepare(array(0,0,-1),$array));
		assert(array() === Base\Arrs::indexPrepare(array(0,0,-4,1),$array));
		assert(Base\Arrs::indexPrepare(array(-1,-1,-1),$array) === array(0,0,1));
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")),"test2"=>array("test22"=>array("test32"=>"oui2",'1t'=>"non2")),"test3"=>array("test23"=>array("test33"=>"oui3",'1t'=>"non3")));
		assert(Base\Arrs::indexPrepare(-1000,$array) === array(-1000));
		assert(Base\Arrs::indexPrepare(array(0,-2),$array) === array(0,-2));
		assert(Base\Arrs::indexPrepare(array(2,-2),$array) === array(2,-2));
		assert(Base\Arrs::indexPrepare(array(0,-2,-1),$array) === array());
		assert(Base\Arr::indexPrepare(-1000,$array) === -1000);

		// keyPrepare
		assert(Base\Arrs::keyPrepare(1) === '1');
		assert(Base\Arrs::keyPrepare(array(1,2,3)) === "1/2/3");
		assert(Base\Arrs::keyPrepare(array('test','/test2')) === 'test/test2');

		// keyPrepares
		assert(Base\Arrs::keyPrepares('test/test2',array('test3','test4')) === "test/test2/test3/test4");
		assert(Base\Arrs::keyPrepares(1,2,3,4) === "1/2/3/4");

		// keyExplode
		assert(Base\Arrs::keyExplode("test/test2") === array('test','test2'));
		assert(Base\Arrs::keyExplode("1/2") === array('1','2'));
		assert(Base\Arrs::keyExplode(2) === array('2'));
		assert(Base\Arrs::keyExplode(array(1,2)) === array(1,2));

		// index
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")));
		assert("non" === Base\Arrs::index("0/0/1",$array));
		assert("non" === Base\Arrs::index("0/0/-1",$array));
		assert(null === Base\Arrs::index("0/0/2",$array));
		assert(null === Base\Arrs::index("0/0/-4/1",$array));
		assert(Base\Arrs::index(array(-1,-1,-1),$array) === 'non');

		// indexes
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>"non")));
		assert(array("-1/-1/-1"=>'non',"0/0/0"=>'oui') === Base\Arrs::indexes(array("-1/-1/-1",array(0,0,0)),$array));
		assert(array("-1/-1/-1/-1"=>null,"0/0/0"=>'oui') === Base\Arrs::indexes(array("-1/-1/-1/-1",array(0,0,0)),$array));
		assert(Base\Arrs::indexes(array(array(-1,-1,-1)),$array) === array('-1/-1/-1'=>'non'));

		// climb
		assert(Base\Arrs::climb(array('dev','app'),array('dev'=>array('app'=>false),'prod'=>false)) === false);
		$array2 = array('dev'=>array('test','test2'),'prod'=>null);
		assert(array('test','test2') === Base\Arrs::climb(array('dev','app','fr'),$array2));
		$array = array("test"=>array("test2"=>array("test3"=>"oui",'1t'=>array(0))));
		assert("oui" === Base\Arrs::climb("test/test2/test3",$array));
		assert(array(0) === Base\Arrs::climb("test/test2/1t",$array));
		assert($array === Base\Arrs::climb("test4/test4/test2/1t",$array));
		assert(array("test2"=>array("test3"=>"oui",'1t'=>array(0))) === Base\Arrs::climb("test4/test4/test2/1t/test",$array));
		assert("oui" === Base\Arrs::climb(array("test","test2","testz","test3"),$array));
		assert("oui" === Base\Arrs::climb(array("testz","k"=>"test","test2","testz","test3"),$array));
		$array = array('dev'=>'test','dev2'=>'bla');
		assert("test" === Base\Arrs::climb(array("testz","k"=>"test","test2","testz","test3","dev","z"),$array));
		assert($array === Base\Arrs::climb(array("asdasds"),$array));
		assert('test' === Base\Arrs::climb(array("asdasds",'dev'),$array));
		$array2 = array('dev'=>array('app'=>array('test'),'test2'),'prod'=>null);
		assert(Base\Arrs::climb(array('DEV','APP','JAMES'),$array2) === $array2);
		assert(Base\Arrs::climb(array('DEV','APP','JAMES'),$array2,false) === array('test'));
		assert(Base\Arrs::climb(array('dev','app'),array('dev'=>true)));
		assert(Base\Arrs::climb(array('dev','app'),array('dev'=>true,'prod'=>false)));
		assert(Base\Arrs::climb(array('james','dev','app'),$array2) === array('test'));
		$array3 = array('james'=>array('dev'=>array('app'=>array('test')),'test2'),'prod'=>null);
		assert(Base\Arrs::climb(array('james','dev','app'),$array3) === array('test'));
		assert(Base\Arrs::climb(array('dev','app'),$array3) === $array3);

		// climbReplaceMode
		$array = array('dev'=>array('app'=>array(2=>'test','bla'=>'test2')),'prod'=>null,'app'=>array(4=>'NOP',2=>'testAPP'),'james'=>array('OK'));
		assert(Base\Arrs::climbReplaceMode(array('dev','app'),array('dev','app','prod'),null,$array) === array('james'=>array('OK'),4=>'NOP',2=>'test','bla'=>'test2'));
		assert(Base\Arrs::climbReplaceMode(array('app','dev'),array('dev','app','prod'),null,$array) === array('james'=>array('OK'),4=>'NOP',2=>'test','bla'=>'test2'));
		$array = array('dev'=>array('app'=>array(2=>'test','bla'=>'test2')),'prod'=>null,'app'=>'NOP','james'=>array('OK'));
		assert(Base\Arrs::climbReplaceMode(array('dev','app'),array('dev','app','prod'),null,$array) === 'NOP');
		$array = array('dev'=>array('appz'=>array('test','bla'=>'test2')),'prod'=>null,'app'=>'NOP','james'=>array('OK'));
		assert(Base\Arrs::climbReplaceMode(array('dev','app'),array('dev','app','prod'),null,$array) === 'NOP');
		$array = array('dev'=>true,'prod'=>false);
		assert(Base\Arrs::climbReplaceMode(array('dev'),array('dev','app','prod'),null,$array) === true);
		assert(Base\Arrs::climbReplaceMode(array('prod'),array('dev','app','prod'),null,$array) === false);
		$array = array('dev'=>array('app'=>null),'prod'=>null,'app'=>'NOP','james'=>array('OK'));
		assert(Base\Arrs::climbReplaceMode(array('dev','app'),array('dev','app','prod'),array(),$array) === 'NOP');
		$array = array('dev'=>array('app'=>array('lol'=>array('TEST'),'bla'=>'test2')),'prod'=>null,'app'=>array('lol'=>array('NOP',1=>'MEH'),'zing'=>'lala'),'james'=>array('OK'));
		assert(Base\Arrs::climbReplaceMode(array('dev'),array('dev','prod'),array(),$array)['app']['lol'] === array('TEST','MEH'));
		assert(Base\Arrs::climbReplaceMode(array('dev'),array('dev','prod'),array('app'),$array)['app']['lol'] === array('TEST'));
		assert(Base\Arrs::climbReplaceMode(array('appz'),array(),array('app'),$array) === $array);

		// set
		$array = array("test"=>array("test2"=>3));
		assert(array("test"=>array("test2"=>4)) === Base\Arrs::set("test/test2",4,$array));
		assert(array("test"=>4) === Base\Arrs::set("test",4,$array));
		assert(array("test"=>array("test2"=>array("test3"=>4))) === Base\Arrs::set(array("test","test2","test3"),4,$array));
		assert(array("test"=>array("test2"=>array("test3"=>array("test4"=>array())))) === Base\Arrs::set("test/test2/test3/test4",array(),$array));
		assert(array("test"=>array("test2"=>3,"test3"=>4)) === Base\Arrs::set("test/test3",4,$array));
		assert(array("test"=>array("test2"=>null)) === Base\Arrs::set("test/test2",null,$array));
		assert(array("test"=>array("test2"=>null)) === Base\Arrs::set("test/test2",null,$array,false));
		assert(array("test"=>array("test2"=>3),'z'=>array('v'=>array(2=>4))) === Base\Arrs::set("z/v/2",4,$array));
		$array = array("Test"=>array("test2"=>3));
		assert(Base\Arrs::set("TEST/test1",true,$array,false) === array('Test'=>array('test2'=>3,'test1'=>true)));
		assert(Base\Arrs::set(array(null,null,'ok'),'VALUE',$array)[0][0]['ok'] === 'VALUE');
		assert(Base\Arrs::set(array(null,null,null),'VALUE',$array)[0][0][0] === 'VALUE');
		assert(Base\Arrs::set(null,'test',$array)[0] === 'test');
		assert(Base\Arrs::set(array('Test',null,'james'),'test',$array)['Test'][0]['james'] === 'test');

		// sets
		$array = array("test"=>array("test2"=>3));
		assert(array("test"=>array("test2"=>4,"test3"=>1)) === Base\Arrs::sets(array("test/test2"=>4,"test/test3"=>1),$array));
		assert(Base\Arrs::sets(array('TEST/test3'=>true),$array,false) === array('test'=>array('test2'=>3,'test3'=>true)));

		// setRef
		$array = array("test"=>array("test2"=>3));
		Base\Arrs::setRef('test2','ok',$array);
		assert($array['test2'] === 'ok');
		Base\Arrs::setRef(null,'okz',$array);
		assert($array[0] === 'okz');
		Base\Arrs::setRef(array('test',null,'ok'),'okz',$array);
		assert($array['test'][0]['ok'] === 'okz');

		// setsRef
		Base\Arrs::setsRef(array('test3/test2'=>'ok'),$array);
		assert($array['test3']['test2'] === 'ok');

		// unset
		$array = array("test"=>array("test2"=>3,'test3'=>'2'));
		assert(array() === Base\Arrs::unset("test",$array));
		assert($array===Base\Arrs::unset("test4",$array));
		assert($array===Base\Arrs::unset("test4/test5",$array));
		assert(array('test'=>array('test3'=>'2')) === Base\Arrs::unset("test/test2",$array));
		assert(Base\Arrs::unset('TEST/TEST2',$array) === $array);
		assert(Base\Arrs::unset('TEST/TEST2',$array,false) === array('test'=>array('test3'=>'2')));

		// unsets
		$array = array("test"=>array("test2"=>3,'test3'=>'2'));
		assert(array() === Base\Arrs::unsets(array("test"),$array));
		assert(array('test'=>array()) === Base\Arrs::unsets(array("test/test2",'test/test3'),$array));
		assert(array('test'=>array()) === Base\Arrs::unsets(array("TEST/TEST2",'TEST/TEST3'),$array,false));

		// unsetRef
		$array = array("test"=>array("test2"=>3));
		Base\Arrs::unsetRef(array('test','test2'),$array);
		assert($array === array('test'=>array()));

		// unsetsRef
		Base\Arrs::setRef('test2/test3','ok',$array);
		Base\Arrs::unsetsRef(array(array('test2','test3')),$array);
		assert($array === array('test'=>array(),'test2'=>array()));

		// getSet
		$array = array('test'=>2);
		assert(2===Base\Arrs::getSet("test",null,$array));
		assert($array===Base\Arrs::getSet(null,null,$array));
		assert(true===Base\Arrs::getSet("test4",44,$array));
		assert(44===$array['test4']);
		assert(true===Base\Arrs::getSet(array("test"=>2,"test3"=>4),null,$array));
		assert(4===$array['test3']);
		assert(true===Base\Arrs::getSet("testa/23",44,$array));
		assert(44===$array['testa'][23]);

		// count
		$arr = array(array(1,2),array(2,3));
		assert(6 === Base\Arrs::count($arr));

		// countLevel
		$arr = array(array(1,2),array(2,3));
		assert(2 === Base\Arrs::countLevel(0,$arr));
		assert(4 === Base\Arrs::countLevel(1,$arr));

		// depth
		assert(Base\Arrs::depth(array(array(array(array(3,array('lol')),'meh'),array('OK')),array('wll'))) === 5);
		assert(Base\Arrs::depth(array(array(4),array(5),array(3))) === 2);
		assert(Base\Arrs::depth(array(array(4),array(5,array(2)),array(3))) === 3);
		assert(Base\Arrs::depth(array(array(4),array(5,array(2)),array(3,array(3,array(4))))) === 4);
		assert(Base\Arrs::depth(array(2)) === 1);

		// keys
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('meh'=>'OK',true)))),"test2"=>"z");
		assert(count(Base\Arrs::keys($array)) === 6);
		assert(count(Base\Arrs::keys($array,'JAMES')) === 0);
		assert(count(Base\Arrs::keys($array,true)) === 2);
		assert(empty(Base\Arrs::keys($array,'ok',true)));
		assert(!empty(Base\Arrs::keys($array,'ok',false)));

		// crush
		assert(array(1,2,3,4) === Base\Arrs::crush(array(1,2,3,4)));
		assert(array(1,2,'1/0'=>3,'1/1'=>4) === Base\Arrs::crush(array(array(1,2),array(3,4))));
		assert(array("test/test2"=>"bla","test2"=>"z") === Base\Arrs::crush(array("test"=>array("test2"=>"bla"),"test2"=>"z")));
		assert(Base\Arrs::crush(array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false))),"test2"=>"z")) === array('test/test2/test3/ok'=>2,'test/test2/test3/0'=>true,'test/test2/test3/1'=>false,'test2'=>'z'));
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('meh'=>'MÉH',true)))),"test2"=>"z");
		assert(count(Base\Arrs::crush($array)) === 6);
		assert(count(Base\Arrs::crush($array,'JAMES')) === 0);
		assert(count(Base\Arrs::crush($array,true)) === 2);
		assert(count(Base\Arrs::crush($array,"méh",false)) === 1);

		// crushReplace
		assert(array(1,2,3,4) === Base\Arrs::crushReplace(array(1,2,3,4)));
		assert(array(3,4) === Base\Arrs::crushReplace(array(array(1,2),array(3,4))));
		assert(array(3,4,'james'=>'notOk') === Base\Arrs::crushReplace(array(array(1,2,'test'=>array('ok')),array(3,4,'test'=>array('james'=>'notOk')))));
		assert(array('ok',4,'x'=>3,'james'=>'notOk') === Base\Arrs::crushReplace(array(array(1,2,'test'=>array('ok')),array('x'=>3,1=>4,'test'=>array('james'=>'notOk')))));
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('meh'=>'MÉH',4=>true)))),"test2"=>"z");
		assert(count(Base\Arrs::crushReplace($array)) === 6);
		assert(count(Base\Arrs::crushReplace($array,true)) === 2);
		assert(count(Base\Arrs::crushReplace($array,2)) === 1);
		assert(count(Base\Arrs::crushReplace($array,"méh",false)) === 1);
		assert(count(Base\Arrs::crushReplace($array,"méh",true)) === 0);

		// values
		assert(array(0=>1,1=>2) === Base\Arrs::values(array(1=>1,2=>2)));
		assert(array(0=>1,1=>2,2=>array('test')) === Base\Arrs::values(array(1=>1,2=>2,6=>array(6=>'test'))));
		assert(array(array('test')) === Base\Arrs::values(array(1=>1,2=>2,6=>array(6=>'test')),'string'));
		assert(array(1,2,array()) === Base\Arrs::values(array(1=>1,2=>2,6=>array(6=>'test')),'int'));

		// search
		$array = array(array('test'=>array(2,3,4,'ok'=>array(true))));
		$search = Base\Arrs::search(true,$array);
		assert(Base\Arrs::get($search,$array) === true);
		$search = Base\Arrs::search(2,$array);
		assert(Base\Arrs::get($search,$array) === 2);
		assert(Base\Arrs::search('bla',$array) === null);
		assert(array(1) === Base\Arrs::search(2,array(1,2,'test'=>'ok')));
		assert(Base\Arrs::search('é',array('a'=>array('É')),false) === array('a',0));

		// searchFirst
		$array = array(true,2=>false,'2',false,'bla'=>array(array('james'=>array('OK'))));
		assert(Base\Arrs::searchFirst(array(false,true),$array) === array(2));
		assert(Base\Arrs::searchFirst(array('2',array(2)),$array) === array(3));
		assert(Base\Arrs::searchFirst(array('OK','2',array(2)),$array) === array('bla',0,'james',0));
		assert(Base\Arrs::searchFirst(array('é'),array('a'=>array('É')),false) === array('a',0));

		// in
		assert(Base\Arrs::in(1,array(0,array(1))));
		assert(Base\Arrs::in(0,array(0,1)));
		assert(Base\Arrs::in('b',array('A','b'),false));
		assert(Base\Arrs::in('a',array(array('A'),'b'),false));
		assert(Base\Arrs::in('é',array(array('É'),'b'),false));
		assert(!Base\Arrs::in('a',array(array('A'),'b')));

		// ins
		assert(Base\Arrs::ins(array(1),array(0,array(1))));
		assert(Base\Arrs::ins(array(0,1),array(array(0),1)));
		assert(Base\Arrs::ins(array(array(0)),array(array(0))));
		assert(!Base\Arrs::ins(array(0,1,2),array(array(0),array(1))));
		assert(!Base\Arrs::ins(array('1'),array(0,1)));
		assert(!Base\Arrs::ins(array('a','b'),array('A','b')));
		assert(Base\Arrs::ins(array('a','b'),array(array('A'),'b'),false));
		assert(Base\Arrs::ins(array('a'),array('A','b'),false));
		assert(Base\Arrs::ins(array('a'),array(array(array('A')),'b'),false));

		// inFirst
		$array = array(array(true),array(2=>false,'2','A'),array(false),array(array(2)));
		assert(Base\Arrs::inFirst(array(false,true),$array) === false);
		assert(Base\Arrs::inFirst(array('2',array(2)),$array) === '2');
		assert(Base\Arrs::inFirst(array(null,'2',array(2)),$array) === '2');
		assert(Base\Arrs::inFirst(array('a'),$array) === null);
		assert(Base\Arrs::inFirst(array('a'),$array,false) === 'a');
		$slice = array('1',2,array(2),'test');
		assert(Base\Arrs::inFirst(array(1),$slice) === null);
		assert(Base\Arrs::inFirst(array('B'),array(array(array('A')),'b'),false) === 'B');

		// map
		$array = array(" test ",2=>array(0=>" test2 ",1=>"test2"));
		assert(array("test",2=>array(0=>"test2",1=>"test2")) === Base\Arrs::map('trim',$array));
		$array = Base\Arrs::map(function($a,$b,$c) { assert(is_array($c)); return true; },$array);
		assert($array = array(true,2=>true));

		// walk
		$array = array("test",array(2),array(4),"test4");
		Base\Arrs::walk(function(&$v,$k,$extra) {
			if(is_int($v))
			$v += 1000;
			else
			$v .= $extra;
		},$array,"bla");
		assert($array === array('testbla',array(1002),array(1004),'test4bla'));

		// shuffle
		$array = Base\Arrs::shuffle(array(1,'test'=>2,3,array('key'=>'bla','what',array(1,2,3,4,5))));
		assert(count($array) === 4);
		assert($array[2]['key'] === 'bla');

		// reverse
		$array = array(1,'test'=>2,3);
		assert(Base\Arrs::reverse(array(1,2,3),false) === array(3,2,1));
		assert(Base\Arrs::reverse(array(1,2,3)) === array(2=>3,1=>2,0=>1));
		$array = array(1,'test'=>2,3,array('key'=>'bla','what',array(1,2,3,4,5)));
		$reverse = Base\Arrs::reverse(array(1,'test'=>2,3,array('key'=>'bla','what',array(1,2,3,4,5))));
		assert(count($reverse) === 4);
		assert($reverse[2]['key'] === 'bla');
		assert($reverse !== $array);
		$reverse2 = Base\Arrs::reverse(array(1,'test'=>2,3,array('key'=>'bla','what',array(1,2,3,4,5))),false);
		assert($reverse !== $reverse2);

		// flip
		assert(array("test"=>0,"test2"=>1) === Base\Arrs::flip(array("test","test2")));
		assert(array("test"=>"test","test2"=>"test2") === Base\Arrs::flip(array("test"=>"test","test2"=>"test2")));
		assert(array(array("test"=>true)) === Base\Arrs::flip(array(array("test")),true));
		assert(array("test"=>array("test"=>true,"test2"=>true)) === Base\Arrs::flip(array("test"=>array("test","test2")),true));
		assert(array("test"=>array("test3","test2")) === Base\Arrs::flip(array("test"=>array("test3","test2")),true,'test'));
		assert(array("test"=>array("test3","test2"=>true)) === Base\Arrs::flip(array("test"=>array("test3","test2")),true,array(0)));
		assert(array(1=>"test") === Base\Arrs::flip(array(1=>"test"),true,1));

		// implode
		assert("test|test2"===Base\Arrs::implode("|",array("test"=>"test","test2")));
		assert('test|test2|test4'===Base\Arrs::implode("|",array("test"=>"test","test2","test3"=>array("test4"))));
		assert(Base\Arrs::implode(array('|',','),array("test"=>"test","test2","test3"=>array("test4",'test5'))) === "test|test2|test4,test5");
		assert('test|test2|test4'===Base\Arrs::implode(array("|"),array("test"=>"test","test2","test3"=>array("test4"))));
		assert(Base\Arrs::implode('',array('test','test2')) === 'testtest2');

		// explode
		assert(array("test","test2","test3","test4") === Base\Arrs::explode("|",array("test|test2","test3|test4")));
		assert(array("test",'test2','test3') === Base\Arrs::explode("|",array(2=>"test",array("test2|test3"))));

		// fill
		$fill = Base\Arrs::fill(array(array(0,5,1),array(0,6,2),array(0,6,3),array(0,40,20),array(0,5)));
		assert(count(Base\Arrs::crush($fill)) === 1296);

		// fillKeys
		$fill = Base\Arrs::fillKeys(array(array('test','test2'),array('ok',2),array('WHAT!','jjj')));
		assert(count(Base\Arrs::crush($fill)) === 8);
		assert(Base\Arrs::fillKeys(array(array(array(),'test','test2'),array(array(),'ok'),array('WHAT!'))) === array('WHAT!'=>true));

		// hierarchy
		assert(Base\Arrs::hierarchy(array('test'=>null,'ok'=>null,2=>'test','test5'=>2,'test3'=>'test4')) === array('test'=>array(2=>array('test5'=>null)),'ok'=>null));
		assert(Base\Arrs::hierarchy(array('test'=>null,'ok'=>null,2=>'test','test5'=>2,'test3'=>'test4'),false)['test4'] === array('test3'=>null));
		
		// hierarchyStructure
		assert(Base\Arrs::hierarchyStructure(array('test'=>'well','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4'),false)[4] === array('well','test','test2','test5'));
		assert(Base\Arrs::hierarchyStructure(array('test'=>'well','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4')) === array(array('ok')));
		assert(Base\Arrs::hierarchyStructure(array('test'=>'ok','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4'))[1] === array('ok','test'));
		assert(Base\Arrs::hierarchyStructure(array('test'=>null,'ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4')) === array(array('test'),array('ok'),array('test','test2'),array('test','test2','test5')));

		// hierarchyAppend
		assert(Base\Arrs::hierarchyAppend(array('test2'=>'test5'),array(array('ok'),array('ok','james','test5')))[2] === array('ok','james','test5','test2'));
		assert(Base\Arrs::hierarchyAppend(array('test2'=>'test5'),array(array('ok'),array('ok','james','test5'),array('well')))[3] === array('ok','james','test5','test2'));

		// keyExists
		assert(Base\Arrs::keyExists('1/2',array(1=>array(2=>true))));
		assert(Base\Arrs::keyExists(array(1,2),array(1=>array(2=>true))));
		assert(Base\Arrs::keyExists('test',array('test'=>array('test2'=>false))));
		assert(Base\Arrs::keyExists('test/test2',array('test'=>array('test2'=>false))));
		assert(Base\Arrs::keyExists('test',array('test'=>array('test2'=>false))));
		assert(Base\Arrs::keyExists('TEST',array('test'=>array('test2'=>false)),false));
		assert(!Base\Arrs::keyExists('test/test3',array('test'=>array('test2'=>false))));

		// keysExists
		assert(Base\Arrs::keysExists(array('test/test2'),array('test'=>array('test2'=>false))));
		assert(Base\Arrs::keysExists(array('test/test2','TEST'),array('test'=>array('test2'=>false)),false));

		// keyPath
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('key'=>'james')));
		assert(array(1,2,'key') === Base\Arrs::keyPath('key',$array));
		assert(null === Base\Arrs::keyPath('key3',$array));
		assert(array(1,2,'key2') === Base\Arrs::keyPath('key2',$array));

		// keyPaths
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('key'=>'james')));
		assert(array(array(1,2,'key'),array(1,3,'key')) === Base\Arrs::keyPaths('key',$array));
		assert(array() === Base\Arrs::keyPaths('keyz',$array));
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('key'=>'james')),'key'=>'yes');
		assert(array(array('key'),array(1,2,'key'),array(1,3,'key')) === Base\Arrs::keyPaths('key',$array));

		// keyValue
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('key'=>'james')),'key'=>'yes');
		assert('yes' === Base\Arrs::keyValue('key',$array));
		assert(null === Base\Arrs::keyValue('keyz',$array));
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('keyz'=>'james')),'key'=>'yes');
		assert('james' === Base\Arrs::keyValue('keyz',$array));
		$array = array(1=>array(2=>array('keyz'=>true,'key2'=>false),3=>array('keyz'=>'james')),'key'=>'yes');
		assert(true === Base\Arrs::keyValue('keyz',$array));

		// keyValues
		$array = array(1=>array(2=>array('key'=>true,'key2'=>false),3=>array('key'=>'james')),'key'=>'yes');
		assert(array('key'=>'yes','1/2/key'=>true,'1/3/key'=>'james') === Base\Arrs::keyValues('key',$array));
		assert(array() === Base\Arrs::keyValues('keyz',$array));

		// keysLower
		assert(Base\Arrs::keysLower(array('Test'=>array('TesT2'=>'ok','test2'=>'james'))) === array('test'=>array('test2'=>'james')));

		// keysUpper
		assert(Base\Arrs::keysUpper(array('Tést'=>array('TesT2'=>'ok')),true) === array('TÉST'=>array('TEST2'=>'ok')));

		// keysInsensitive
		assert(Base\Arrs::keysInsensitive(array('Tést'=>array('TésT2'=>'ok','TÉST2'=>true))) === array('Tést'=>array('TÉST2'=>true)));

		// keysSort
		$sort = array("z"=>"test","b"=>"test","c"=>array("z"=>"a","b"=>"b"));
		$sort = Base\Arrs::keysSort($sort,true);
		assert(array("b"=>"test","c"=>array("b"=>"b","z"=>"a"),"z"=>"test") === $sort);
		$sort = Base\Arrs::keysSort($sort,'desc');
		assert(array("z"=>"test","c"=>array("z"=>"a","b"=>"b"),"b"=>"test",) === $sort);
		$sort = array("z"=>"test","b"=>"test","c"=>array("z"=>"a","b"=>"b"));
		$sort = Base\Arrs::keysSort($sort,'krsort');
		assert(array("z"=>"test","c"=>array("z"=>"a","b"=>"b"),"b"=>"test",) === $sort);

		// valueKey
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('ok'=>'mÉh',true)))),"test2"=>"z");
		assert(count(Base\Arrs::valueKey(true,$array)) === 2);
		assert(count(Base\Arrs::valueKey(null,$array)) === 0);
		assert(count(Base\Arrs::valueKey("MéH",$array,false)) === 1);

		// valuesKey
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('meh'=>'mÉh',true)))),"test2"=>"z");
		assert(count(Base\Arrs::valuesKey(array(true,2),$array)) === 3);
		assert(Base\Arrs::valuesKey(array(array()),$array) === array());
		assert(count(Base\Arrs::valuesKey(array("MéH"),$array,false)) === 1);

		// valueStrip
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('ok'=>'mÉh',true)))),"test2"=>"z");
		assert(Base\Arrs::count(Base\Arrs::valueStrip(true,$array)) === 8);

		// valuesStrip
		$array = array("test"=>array("test2"=>array('test3'=>array('ok'=>2,true,false,'bla'=>array('ok'=>'mÉh',true)))),"test2"=>"z");
		array(Base\Arrs::valuesStrip(array(true,false,2,'z','mÉh'),$array)['test']['test2']['test3']['bla'] === array());

		// valuesCrush
		assert(Base\Arrs::valuesCrush(array('ok')) === array());
		assert(Base\Arrs::valuesCrush(array(array('Ok'))) === array(array('Ok')));
		assert(count(Base\Arrs::valuesCrush(array(array('Ok'),array('ok2','ok3'),array('ok4','ok5'),array('ok6')))) === 4);
		assert(count(Base\Arrs::valuesCrush(array(array('Ok'),array('ok2','ok3'),array('ok4','ok5'),array('ok6')))[0]) === 4);

		// valuesChange
		$array = array(true,array(2,array(true)));
		assert(array(false,array(2,array(false))) === Base\Arrs::valuesChange(true,false,$array));
		assert(array(false,array(2,array(true))) === Base\Arrs::valuesChange(true,false,$array,1));
		assert(array(false,array(2,array(false))) === Base\Arrs::valuesChange(true,false,$array,2));

		// valuesReplace
		assert(array("test"=>"lapa","lapa2") === Base\Arrs::valuesReplace(array("test"=>"lapa"),array("test"=>"test","test2")));
		assert(array("test"=>"lapa","lapa2",'2') === Base\Arrs::valuesReplace(array("test"=>"lapa"),array("test"=>"test","test2",'2')));
		assert(array("test"=>array('lapa'),"lapa2",'2') === Base\Arrs::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"test2",'2')));
		assert(array("test"=>array('lapa'),"lapa2",'2') === Base\Arrs::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"TEST2",'2'),false));
		assert(array("test"=>array('lapa'),"TEST2",'2') === Base\Arrs::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"TEST2",'2'),true));

		// valuesLower
		assert(Base\Arrs::valuesLower(array('Test',array('OK','TÉS',array('VlÉ'))))[1][2][0] === 'vlé');

		// valuesUpper
		assert(Base\Arrs::valuesUpper(array('Test',array('OK','TÉS',array('Vlé'))))[1][2][0] === 'VLÉ');

		// keysValuesLower
		assert(Base\Arrs::keysValuesLower(array('A'=>array('A'=>'É'))) === array('a'=>array('a'=>'é')));

		// keysValuesUpper
		assert(Base\Arrs::keysValuesUpper(array('a'=>array('a'=>'é'))) === array('A'=>array('A'=>'É')));
		
		return true;
	}
}
?>