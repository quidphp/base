<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// arr
class Arr extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// typecast
		$x = 1;
		$y = '2';
		$z = array();
		$obj = new \DateTime("now");
		Base\Arr::typecast($x,$y,$z,$obj);
		assert(array(1) === $x);
		assert(array('2') === $y);
		assert(array() === $z);
		assert($obj[0] instanceof \DateTime);
		$obj2 = new \DateTime("now");
		
		// cast
		assert(array(1,1.2,'1,3',12345678901,'true','false','null',null) === Base\Arr::cast(array('1','1.2','1,3','12345678901','true','false','null',null)));
		assert(array(1,'true','false','NULL',null) === Base\Arr::cast(array('1','true','false','NULL',null)));
		assert(array('1','true','false','NULL',null) === Base\Arr::cast(array('1','true','false','NULL',null),0));
		assert(array(1,1.2,'1,3',12345678901,true,false,null,null) === Base\Arr::cast(array('1','1.2','1,3','12345678901','true','false','null',null),1,1));
		assert(array('000111',1,1.2,'12345678901123123',true,false,null,null) === Base\Arr::cast(array('000111','1','1.2','12345678901123123','true','false','null',null),1,1));
		assert(array(1,1.2,1.3,12345678901,true,false,null,null) === Base\Arr::cast(array('1','1.2','1,3','12345678901','true','false','null',null),2,1));
		assert(array(1,1.2,1.3,12345678901,true,false,null,null,true,false) === Base\Arr::cast(array('1','1.2','1,3','12345678901','true','false','null',null,'on','off'),2,2));

		// castMore
		assert(array(111,1,1.2,1.3,12345678901,true,false,null,null) === Base\Arr::castMore(array('000111','1','1.2','1,3','12345678901','true','false','null',null)));

		// is
		assert(Base\Arr::is(array()));
		assert(!Base\Arr::is(1));

		// isEmpty
		assert(Base\Arr::isEmpty(array()));
		assert(!Base\Arr::isEmpty(array(1)));

		// isNotEmpty
		assert(!Base\Arr::isNotEmpty(array()));
		assert(Base\Arr::isNotEmpty(array(1)));

		// isCleanEmpty
		assert(Base\Arr::isCleanEmpty(array('',null)));
		assert(Base\Arr::isCleanEmpty(array('',array())));
		assert(!Base\Arr::isCleanEmpty(array('',array(null))));
		assert(!Base\Arr::isCleanEmpty(array('',null,false)));

		// hasNumericKey
		assert(!Base\Arr::hasNumericKey(array()));
		assert(Base\Arr::hasNumericKey(array(0,1,2,'test')));
		assert(!Base\Arr::hasNumericKey(array("test"=>"oui")));
		assert(Base\Arr::hasNumericKey(array("test"=>"oui",2)));

		// hasNonNumericKey
		assert(!Base\Arr::hasNonNumericKey(array(0,1,2,'test')));
		assert(Base\Arr::hasNonNumericKey(array("test"=>"oui")));

		// hasKeyCaseConflict
		assert(!Base\Arr::hasKeyCaseConflict(array()));
		assert(!Base\Arr::hasKeyCaseConflict(array(1,2)));
		assert(Base\Arr::hasKeyCaseConflict(array('test'=>'ok','TEST'=>'yeah','TÉST'=>'ok')));
		assert(Base\Arr::hasKeyCaseConflict(array('tést'=>'ok','TÉST'=>'yeah')));
		assert(!Base\Arr::hasKeyCaseConflict(false,true));

		// isIndexed
		assert(Base\Arr::isIndexed(array(0,1,2,'test')));
		assert(!Base\Arr::isIndexed(array("test"=>"oui")));
		assert(Base\Arr::isIndexed(array(1,2)));
		assert(Base\Arr::isIndexed(array()));
		assert(Base\Arr::isIndexed(array(1=>'ok')));
		assert(!Base\Arr::isIndexed(false));

		// isSequential
		assert(Base\Arr::isSequential(array(0,1,2,'test')));
		assert(!Base\Arr::isSequential(array("test"=>"oui"),array(0)));
		assert(Base\Arr::isSequential(array(1,2),array()));
		assert(!Base\Arr::isSequential(array(1=>'ok')));
		assert(Base\Arr::isSequential(array()));

		// isAssoc
		assert(Base\Arr::isAssoc(array(0,1,2,'test'=>2)));
		assert(!Base\Arr::isAssoc(array(0,1,2)));
		assert(Base\Arr::isAssoc(array()));

		// isUni
		assert(Base\Arr::isUni(array()));
		assert(Base\Arr::isUni(array(1,2)));
		assert(!Base\Arr::isUni(array(1,2,array())));

		// isMulti
		assert(!Base\Arr::isMulti(array()));
		assert(Base\Arr::isMulti(array(1,2,array())));

		// onlyNumeric
		assert(Base\Arr::onlyNumeric(array(1,2)));
		assert(!Base\Arr::onlyNumeric(array(1,'test'=>2)));
		assert(!Base\Arr::onlyNumeric(array(1,'test')));
		assert(!Base\Arr::onlyNumeric(false));

		// onlyString
		assert(!Base\Arr::onlyString(array(1,'test'=>2)));
		assert(!Base\Arr::onlyString(array('2'=>'2','test'=>2)));
		assert(Base\Arr::onlyString(array('2a'=>'2','test'=>'ok')));

		// isSet
		assert(Base\Arr::isSet(array(1,2)));
		assert(!Base\Arr::isSet(array(1,'test'=>2)));
		assert(Base\Arr::isSet(array(1,'test')));
		assert(!Base\Arr::isSet(false));

		// isKey
		assert(Base\Arr::isKey(2));
		assert(Base\Arr::isKey('test'));
		assert(!Base\Arr::isKey(false));
		assert(Base\Arr::isKey(''));

		// isKeyNotEmpty
		assert(!Base\Arr::isKeyNotEmpty(''));

		// isCount
		assert(Base\Arr::isCount(2,array(1,2)));
		assert(Base\Arr::isCount(array('bla','ok'),array(1,2)));

		// isMinCount
		assert(Base\Arr::isMinCount(1,array(1,2)));
		assert(!Base\Arr::isMinCount(3,array(1,2)));
		assert(Base\Arr::isMinCount(array(2),array(1,2)));

		// isMaxCount
		assert(Base\Arr::isMaxCount(3,array(1,2)));
		assert(Base\Arr::isMaxCount(2,array(1,2)));
		assert(!Base\Arr::isMaxCount(1,array(1,2)));
		assert(Base\Arr::isMaxCount(array(3,4),array(1,2)));

		// same
		$array1 = array(1=>1,"tst"=>2,2=>3);
		$array2 = array(1=>2,"tst"=>3,2=>4,5=>2);
		assert(Base\Arr::same($array1,$array1,$array1));
		assert(!Base\Arr::same($array1,$array1,$array1,true,false,1));
		assert(!Base\Arr::same($array1,$array1,$array2));
		assert(!Base\Arr::same(array(),array()));

		// sameCount
		$array1 = array(1=>1,"tst"=>2,2=>3);
		$array2 = array(1=>1,4=>2);
		assert(Base\Arr::sameCount($array1,$array1));
		assert(Base\Arr::sameCount($array1,$array2)===false);
		assert(Base\Arr::sameCount(array(),array()));

		// sameKey
		$array1 = array(1=>1,"tst"=>2,2=>3);
		$array2 = array(1=>1,0=>2,2=>2,4=>2,"tst"=>"test");
		assert(Base\Arr::sameKey($array1,$array2,$array1));
		assert(Base\Arr::sameKey($array1,$array1,$array1,array(1,2,3))===false);
		assert(!Base\Arr::sameKey(array(),array()));

		// sameKeyValue
		assert(Base\Arr::sameKeyValue(array(2,3,4),array(2,3,4)));
		assert(Base\Arr::sameKeyValue(array(2,3,4),array(2=>4,1=>3,0=>2)));
		assert(!Base\Arr::sameKeyValue(array(),array()));

		// hasValueStart
		assert(Base\Arr::hasValueStart('test2.jpg',array('test','james','test3')));
		assert(!Base\Arr::hasValueStart('test2.jpg',array('test4','james','test3')));
		assert(Base\Arr::hasValueStart('test2.jpg',array('te','james','test3')));

		// plus
		$merge1 = array("test"=>"test","test2"=>array(0=>"test3"));
		$merge2 = array("test"=>"test6","test2"=>array(1=>"test3"),"test5"=>array(0=>"test4"));
		assert(array('test'=>2) === Base\Arr::plus(array('test'=>1),array('test'=>2)));
		assert(array(0=>2,'test'=>1,'james'=>1) === Base\Arr::plus(array('test'=>1,'james'=>1),2));
		assert(array('test'=>array(0=>3)) === Base\Arr::plus(array('test'=>array(0=>2)),array('test'=>array(0=>3))));
		assert(array('test'=>3) === Base\Arr::plus(array('test'=>1),array('test'=>2),array("test"=>3)));
		assert(array(0=>4,'test'=>3) === Base\Arr::plus(array('test'=>1),array('test'=>2),array("test"=>3),4));
		assert(array(0=>4,'test'=>3) === Base\Arr::plus(array('test'=>1),array('test'=>2),array("test"=>3),array(4)));
		assert(array("test"=>"test6","test2"=>array(1=>"test3"),"test5"=>array(0=>"test4")) === Base\Arr::plus($merge1,$merge2));
		assert(Base\Arr::plus(array('test'=>array(0=>2,'test'=>'ok')),array('test'=>array(0=>3,'test2'=>'what'))) === array('test'=>array(0=>3,'test2'=>'what')));
		assert(Base\Arr::plus(array('test')) === array('test'));
		assert(Base\Arr::plus(array('test'),$obj2,$obj2) === array($obj2));
		assert(Base\Arr::plus($obj,$obj,array('test'),$obj) === array($obj[0]));

		// merge
		$merge1 = array("test"=>1,"test2"=>array(1,2,'ok'=>'james'),'test3'=>'ok','4'=>'ok','james'=>'what');
		$merge2 = array("test"=>2,"test2"=>array('test'=>'ok'),"test3"=>"bla");
		assert(array("test"=>2,"test2"=>array('test'=>'ok'),"test3"=>"bla",'0'=>'ok','james'=>'what') === Base\Arr::merge($merge1,$merge2));
		assert(Base\Arr::merge(array('test'),$obj2,$obj2) === array('test',$obj2,$obj2));

		// replace
		$merge1 = array("test"=>1,"test2"=>array(1,2),'test3'=>'ok','4'=>'ok');
		$merge2 = array("test"=>2,"test2"=>array('test'=>'ok'),"test3"=>"bla");
		assert(array("test"=>2,"test2"=>array('test'=>'ok'),"test3"=>"bla",'4'=>'ok') === Base\Arr::replace($merge1,$merge2));
		assert(Base\Arr::replace(array('test'),$obj2,$obj2) === array($obj2));

		// replaceIf
		$slice = array("test"=>"test","test2"=>"test2","test3"=>"test3");
		assert(array("test"=>"test4","test2"=>"test2","test3"=>"test3") === Base\Arr::replaceIf('exists',$slice,array("test"=>"test4")));
		assert(array("test"=>"test","test2"=>"test2","test3"=>"test3") === Base\Arr::replaceIf('exists',$slice,array("test4"=>"test4")));
		$slice = array("test"=>"test","test2"=>"test2","test3"=>"test3");
		assert(array("test"=>"test","test2"=>"test2","test3"=>"test3") === Base\Arr::replaceIf('notExists',$slice,array("test"=>"test4")));
		assert(array("test"=>"test","test2"=>"test2","test3"=>"test3","test4"=>"test4") === Base\Arr::replaceIf('notExists',$slice,array("test4"=>"test4")));
		$merge1 = array("test"=>1,"test2"=>2,'test3'=>'ok','test4'=>array(1));
		$merge2 = array("test"=>2,"test2"=>1,"test3"=>"bla",'test4'=>array(2,2),'test5'=>4);
		assert(array("test"=>2,"test2"=>2,'test3'=>'ok','test4'=>array(2,2),'test5'=>4) === Base\Arr::replaceIf('bigger',$merge1,$merge2));
		$merge1 = array("test"=>1,"test2"=>2,'test3'=>'ok','test4'=>array(1,4));
		$merge2 = array("test"=>2,"test2"=>1,"test3"=>"bla",'test4'=>array(2),'test5'=>3);
		assert(array("test"=>1,"test2"=>1,'test3'=>'bla','test4'=>array(2)) === Base\Arr::replaceIf('smaller',$merge1,$merge2));

		// replaceCleanNull
		assert(Base\Arr::replaceCleanNull(array('test2'=>'ok',12,3),array('test2'=>null,'james'=>'ok')) === array(12,3,'james'=>'ok'));

		// unshift
		assert(Base\Arr::unshift("test","test2") === array('test2','test'));
		assert(Base\Arr::unshift(array("test"),array("test2")) === array(array('test2'),'test'));
		assert(Base\Arr::unshift(array("test","test3"),array("test2")) === array(array('test2'),'test','test3'));
		assert(Base\Arr::unshift(array("test",'test'=>'ok'),'james','james2') === array('james','james2','test','test'=>'ok'));

		// push
		assert(Base\Arr::push("test","test2",array("test3","test4")) === array("test","test2",array("test3","test4")));
		assert(Base\Arr::push("test","test2",array("test3",array("test4"))) === array("test","test2",array("test3",array("test4"))));
		assert(Base\Arr::push(array("test"),"test2",array("test3","test4")) === array("test","test2",array("test3","test4")));
		assert(Base\Arr::push(array('test'),$obj,$obj2) === array('test',$obj,$obj2));

		// prepend
		assert(Base\Arr::prepend('test',array("test2"),array("test3","test4")) === array('test2','test3','test4','test'));
		assert(Base\Arr::prepend(array('test'),"test2",array(30=>"test3","test4")) === array(0=>'test2',30=>'test3',31=>'test4',32=>'test'));
		assert(Base\Arr::prepend(array("test",'test'=>'ok'),array('james','what'=>'james','test'=>'BURP'),$obj2) === array('james','what'=>'james','test'=>'ok',$obj2,'test'));
		assert(Base\Arr::prepend(array("test",'test'=>'ok'),array('james','what'=>'james','test'=>'BURP')) === array('james','what'=>'james','test'=>'ok',1=>'test'));
		assert(Base\Arr::prepend(array('test'=>2,'ok'=>3),array('TEST'=>3)) === array('TEST'=>3,'test'=>2,'ok'=>3));

		// iprepend
		assert(Base\Arr::iprepend(array('test'=>2,'ok'=>3),array('TEST'=>3)) === array('test'=>2,'ok'=>3));
		assert(Base\Arr::iprepend(array('test'=>2,'ok'=>3),array('TEST'=>3),2,3,'james') === array(2,3,'james','test'=>2,'ok'=>3));

		// append
		assert(Base\Arr::append(array('test'),"test2",array("test3","test4")) === array("test","test2","test3","test4"));
		assert(Base\Arr::append("test","test2",array("test3","test4")) === array("test","test2","test3","test4"));
		assert(Base\Arr::append("test","test2",array(20=>"test3",array("test4"))) === array("test","test2",20=>"test3",21=>array("test4")));
		assert(Base\Arr::append(array("test",'test'=>'ok'),array('james','what'=>'james','test'=>'BURP',$obj2),$obj,$obj2) === array('test','test'=>'BURP',1=>'james','what'=>'james',2=>$obj2,3=>$obj[0],4=>$obj2));
		assert(Base\Arr::append(array("test",'test'=>'ok'),array('james','what'=>'james','test'=>'BURP')) === array('test','test'=>'BURP',1=>'james','what'=>'james'));
		assert(Base\Arr::append("test","test2",array("testz",'test'=>array(array("test4"))),array("testx",'test'=>array("test4"))) === array('test','test2','testz','test'=>array('test4'),'testx'));
		assert(Base\Arr::append(array('test')) === array('test'));
		assert(Base\Arr::append(array('test'=>2,'ok'=>3),array('TEST'=>3)) === array('test'=>2,'ok'=>3,'TEST'=>3));
		assert(Base\Arr::append('test','test2',array('ok','james',8=>'ok','bla')) === array('test','test2','ok','james',8=>'ok',9=>'bla'));

		// iappend
		assert(Base\Arr::iAppend(array('test'=>2,'ok'=>3),array('TEST'=>3)) === array('ok'=>3,'TEST'=>3));

		// appendUnique
		assert(Base\Arr::appendUnique(true,false,"what",array('test'=>"What")) === array(true,false,'what','test'=>'What'));
		assert(Base\Arr::appendUnique(true,false,array(10=>"what","What")) === array(true,false,10=>'what',11=>'What'));

		// appendiUnique
		assert(Base\Arr::appendiUnique(true,false,array(5=>"what"),array('test'=>"What")) === array(true,false,5=>'what'));

		// smart
		$smart = array(array("test"));
		assert(array("test") === Base\Arr::smart($smart));

		// clean
		$clean = array('',0,null,array());
		assert(array(1=>0) === Base\Arr::clean($clean));
		$clean = array('',null,true,false);
		assert(array(2=>true,false) === Base\Arr::clean($clean));
		assert(array(true,false) === Base\Arr::clean($clean,true));

		// cleanEmpty
		$clean = array('',0,null,array());
		assert(array() === Base\Arr::cleanEmpty($clean));

		// cleanNull
		assert(Base\Arr::cleanNull(array('',null,array())) === array('',2=>array()));

		// cleanNullBool
		assert(Base\Arr::cleanNullBool(array(null,'',2,true)) === array(1=>'',2=>2));
		assert(Base\Arr::cleanNullBool(array(null,'',2,true),true) === array('',2));

		// reallyEmptyToNull
		assert(Base\Arr::reallyEmptyToNull(array('test'=>'','james'=>0,'ok'=>true)) === array('test'=>null,'james'=>0,'ok'=>true));

		// trim
		assert(Base\Arr::trim(array(' test '=>' ok ',2=>true),true) === array('test'=>'ok',2=>true));

		// trimClean
		assert(Base\Arr::trimClean(array(' test '=>' ok ','',2=>true),true,true,true) === array('test'=>'ok',2=>true));
		assert(Base\Arr::trimClean(array(' test '=>' ok ','',2=>true),false,true,true) === array(' test '=>'ok',2=>true));
		assert(Base\Arr::trimClean(array(' test '=>' ok ','',2=>true),false,false,true) === array(' test '=>' ok ',2=>true));

		// validate
		assert(Base\Arr::validate("bool",array("test"=>false,"test2"=>true)));
		assert(Base\Arr::validate("email",array("test@gmail.com","e@test.ca")));
		assert(!Base\Arr::validate("/^\d{4}$/",array(2013,"bla")));
		assert(Base\Arr::validate("/^\d{4}$/",array(2013,"2014")));
		assert(Base\Arr::validate("year",array(2013,"2014")));
		assert(!Base\Arr::validate("year",array(2013,"2014",'bla')));
		assert(Base\Arr::validate('scalar',array("test"=>false,"test2"=>"bla")));
		assert(Base\Arr::validate('bool',array("test"=>false,"test2"=>true)));
		assert(!Base\Arr::validate("array",array("test"=>false,"test2"=>true)));
		assert(Base\Arr::validate("scalar",array("test"=>1,"test2"=>'2','test'=>'string')));

		// validates
		assert(Base\Arr::validates("bool",array("test"=>false),array("test2"=>true)));
		assert(Base\Arr::validates("email",array("test@gmail.com"),array("e@test.ca")));
		assert(!Base\Arr::validates("email",array("test@gmail.com"),array(true)));
		assert(!Base\Arr::validates("bool",array("test"=>false),array("test2"=>'ok')));

		// validateSlice
		assert(array("test@gmail.com") === Base\Arr::validateSlice("email",array("test@gmail.com",true)));
		assert(array("test@gmail.com") === Base\Arr::validateSlice("email",array("test@gmail.com",true)));
		assert(array(1=>true) === Base\Arr::validateSlice('bool',array("test@gmail.com",true)));
		assert(array() === Base\Arr::validateSlice("email",array(array("test@gmail.com"),true)));
		assert(array(1=>true) === Base\Arr::validateSlice('bool',array("test@gmail.com",true)));

		// validateStrip
		assert(array(1=>true) === Base\Arr::validateStrip('email',array("test@gmail.com",true)));
		assert(array(array("test@gmail.com"),1=>true) === Base\Arr::validateStrip("email",array(array("test@gmail.com"),true)));
		assert(array("test@gmail.com") === Base\Arr::validateStrip('bool',array("test@gmail.com",true)));

		// validateMap
		$array = array("test",2=>'test2',array());
		assert(Base\Arr::validateMap('string','strtoupper',$array) === array('TEST',2=>'TEST2',3=>array()));
		$array = array("test"=>"test","test2"=>"test2!");
		assert(array("test"=>"!test!","test2"=>"!test2!!") === Base\Arr::validateMap('string',function($a) { return "!$a!"; },$array));
		assert(array("test"=>"test!","test2"=>"test2!!") === Base\Arr::validateMap('string',function($a) { return "$a!"; },$array));
		assert(array("test"=>"!test!","test2"=>"!test2!") === Base\Arr::validateMap('string',function($a) { return Base\Str::wrapStartOrEnd('!','!',$a); },$array));
		assert(array("test"=>"test!","test2"=>"test2!") === Base\Arr::validateMap('string',function($a) { return Base\Str::wrapEnd('!',$a); },$array));
		$array = array("test"=>"test","!test2!"=>"!test2!");
		assert(array("test"=>"test","!test2!"=>"test2") === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStartEnd('!','!',$a); },$array));
		$array = array("test"=>"test","!test2!"=>"test2!");
		assert(array("test"=>"test","!test2!"=>"test2") === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStartOrEnd('!','!',$a); },$array));
		assert(array("test"=>"test","!test2!"=>"test2!") === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStart('!',$a); },$array));
		assert(array("test"=>"test","!test2!"=>"test2") === Base\Arr::validateMap('string',function($a) { return Base\Str::stripEnd('!',$a); },$array));
		$slice = array('key_fr','key_en','key_es','what',array());
		assert(array('name_fr','name_en','name_es','what',array()) === Base\Arr::validateMap('string',function($a) { return Base\Str::changeBefore('_','name',$a); },$slice));
		$slice = array('name_fr','key_fr','content_fr','what',array());
		assert(array('name_en','key_en','content_en','what',array()) === Base\Arr::validateMap('string',function($a) { return Base\Str::changeAfter('_','en',$a); },$slice));

		// validateFilter
		$array = array("test",2=>'test2',array());
		assert(Base\Arr::validateFilter('string',function($a,$b,$c) { 
			if($a === 'test2') return true; 
		}, $array) === array(2=>'test2',array()));
		assert(Base\Arr::validateFilter('string',function($a,$b,$c) { 
			if($a === 'test2') return true; 
		}, $array,false) === array(2=>'test2'));
		$array = array("test"=>"test","!test2!"=>"!test2!");
		assert(array("!test2!"=>"!test2!") === Base\Arr::validateFilter('string',function($a) { return Base\Str::isStart('!',$a); },$array,true));
		$array = array("test"=>"testzz","!test2!"=>"!test2!");
		assert(array("!test2!"=>"!test2!") === Base\Arr::validateFilter('string',function($a) { return Base\Str::isEnd('!',$a); },$array,true));

		// get
		$array = array('test'=>'ok','james'=>2,3=>'ok');
		assert(Base\Arr::get('test',$array) === 'ok');
		assert(Base\Arr::get('Test',$array) === null);
		$array = array('test'=>'ok','jamÉs'=>2,3=>'ok');
		assert(Base\Arr::get('Test',$array,false) === 'ok');
		assert(Base\Arr::get('jamés',$array,false) === 2);

		// getSafe
		assert(Base\Arr::getSafe('test','ok') === null);
		assert(Base\Arr::getSafe('test',array('test'=>'k')) === 'k');
		assert(Base\Arr::getSafe('test',array('test2'=>'k')) === null);
		assert(Base\Arr::getSafe(null,array('test2'=>'k')) === array('test2'=>'k'));

		// gets
		$array = array('Test'=>'ok','james'=>2,3=>'ok');
		assert(Base\Arr::gets(array('Test','james',3),$array) === $array);
		assert(Base\Arr::gets(array('Test',4),$array) === array('Test'=>'ok',4=>null));
		$array = array('test'=>'ok','TEsT'=>'ok2','james'=>2,3=>'ok');
		assert(Base\Arr::gets(array('TEST',3,'jAmes'),$array,false) === array('TEST'=>'ok2',3=>'ok','jAmes'=>2));

		// getsExists
		$array = array('Test'=>'ok','james'=>2,3=>'ok');
		assert(Base\Arr::getsExists(array('Test',4),$array) === array('Test'=>'ok'));

		// indexPrepare
		assert(Base\Arr::indexPrepare(-3,count(array(1,2,3))) === 0);
		assert(Base\Arr::indexPrepare(-1,count(array(1,2,3))) === 2);
		assert(Base\Arr::indexPrepare(0,count(array(1,2,3))) === 0);
		assert(Base\Arr::indexPrepare(2,array(1,2,3)) === 2);
		assert(Base\Arr::indexPrepare(3,count(array(1,2,3))) === 3);
		assert(Base\Arr::indexPrepare(-4,count(array(1,2,3))) === -4);
		assert(Base\Arr::indexPrepare(array(0,-1),array(1,2,3)) === array(0,2));
		assert(Base\Arr::indexPrepare(array(0,-1),count(array(1,2,3))) === array(0,2));
		assert(Base\Arr::indexPrepare(array(0,-444),count(array(1,2,3))) === array(0,-444));

		// index
		$array = array(1,2,'bla'=>3);
		assert(Base\Arr::index(-1,$array) === 3);
		assert(Base\Arr::index(100,$array) === null);

		// indexes
		assert(Base\Arr::indexes(array(0,-1),array(1,2,'bla'=>3)) === array(1,2=>3));
		assert(Base\Arr::indexes(array(0,40,-10),array(1,2,'bla'=>3)) === array(1,40=>null,-10=>null));

		// set
		$array = array('test'=>'ok','james'=>2,3=>'ok');
		assert($array = Base\Arr::set(4,0,$array));
		assert(Base\Arr::get(4,$array) === 0);
		assert($array = Base\Arr::set("TEST","ok2",$array,false));
		assert(count($array) === 4);
		assert(Base\Arr::get("test",$array) === "ok2");
		assert(Base\Arr::set(Null,'mehg',$array)[5] === 'mehg');

		// sets
		$array = Base\Arr::sets(array('TEST'=>'yeah',4=>1),$array);
		assert($array['TEST'] === 'yeah');
		assert(Base\Arr::get(4,$array) === 1);
		assert(count(Base\Arr::sets(array('JAMES'=>'ok'),$array,false)) === 5);

		// setRef
		$arr = array();
		Base\Arr::setRef('test',2,$arr);
		assert($arr['test'] === 2);
		Base\Arr::setRef('TEST',3,$arr,false);
		assert(count($arr) === 1);
		Base\Arr::setRef(null,'meh',$arr);
		assert($arr[0] === 'meh');

		// setsRef
		Base\Arr::setsRef(array('Test'=>3),$arr);
		assert($arr['test'] === 3);
		assert(count($arr) === 3);
		Base\Arr::setsRef(array('TesT'=>4),$arr,false);
		assert(count($arr) === 2);

		// setMerge
		assert(Base\Arr::setMerge('test',2,array('test2'=>true)) === array('test2'=>true,'test'=>2));
		assert(Base\Arr::setMerge('test2',2,array('test2'=>true)) === array('test2'=>array(true,2)));
		assert(Base\Arr::setMerge('TEST2',2,array('test2'=>true),false) === array('test2'=>array(true,2)));
		assert(Base\Arr::setMerge(null,2,array('test2'=>true),false) === array('test2'=>true,2));

		// setsMerge
		assert(Base\Arr::setsMerge(array('test2'=>2),array('test2'=>true)) === array('test2'=>array(true,2)));

		// unset
		$array = Base\Arr::sets(array('TEST'=>'yeah',4=>1),$array);
		assert($array = Base\Arr::unset('test',$array,false));
		assert(Base\Arr::get('test',$array) === null);

		// unsets
		assert($array = Base\Arr::unsets(array(4,3),$array));
		assert(count($array) === 1);
		assert(array() === Base\Arr::unsets(array('JaMeS'),$array,false));

		// unsetRef
		$arr = array('test'=>true,'test2'=>'ok');
		Base\Arr::unsetRef('test',$arr);
		assert(count($arr) === 1);
		Base\Arr::unsetRef('TEST2',$arr,false);
		assert(count($arr) === 0);

		// unsetsRef 
		$arr = array('test'=>true,'test2'=>'ok');
		Base\Arr::unsetsRef(array('TEST2'),$arr,false);
		assert(count($arr) === 1);
		Base\Arr::unsetsRef(array('test','test2'),$arr);
		assert(count($arr) === 0);

		// getSet
		$array = array('test'=>2);
		assert(2===Base\Arr::getSet("test",null,$array));
		assert($array===Base\Arr::getSet(null,null,$array));
		assert(true===Base\Arr::getSet("test4",44,$array));
		assert(44===$array['test4']);
		assert(true===Base\Arr::getSet(array("test"=>2,"test3"=>4),null,$array));
		assert(4===$array['test3']);
		assert(true===Base\Arr::getSet("testa/23",44,$array));
		assert(44===$array['testa/23']);
		assert(true===Base\Arr::getSet(array("test"=>2,"test3"=>4),true,$array));
		assert($array === array("test"=>2,"test3"=>4));

		// keyValue
		assert(Base\Arr::keyValue('lol','ok',array('lol'=>2,'ok'=>'bla','bleu')) === array(2=>'bla'));

		// keyValueIndex
		assert(Base\Arr::keyValueIndex(1,0,array('lol'=>2,'ok'=>'bla','bleu')) === array('bla'=>2));

		// keys
		assert(array(0,1,'test') === Base\Arr::keys(array(1,2,'test'=>'ok')));
		assert(array(0,1,'test') === Base\Arr::keys(array(1,2,'test'=>'ok'),null));
		assert(array('test','test2') === Base\Arr::keys(array(1,2,'test'=>'ok','test2'=>'ok'),'ok'));
		assert(array() === Base\Arr::keys(array(1,2,'test'=>'OK'),'ok'));
		assert(array('test') === Base\Arr::keys(array(1,2,'test'=>'OK'),'ok',false));
		assert(array('test') === Base\Arr::keys(array(1,2,'test'=>'ÉÉ'),'éé',false));

		// values
		assert(array(0=>1,1=>2) === Base\Arr::values(array(1=>1,2=>2)));
		assert(array(0=>1,1=>2,2=>array(6=>'test')) === Base\Arr::values(array(1=>1,2=>2,6=>array(6=>'test'))));
		assert(array(0=>1,1=>2) === Base\Arr::values(array(1=>1,2=>2,6=>array(6=>'test')),'int'));
		assert(array('test@gmail.com') === Base\Arr::values(array(1=>'test@gmail.com',2=>2,6=>array(6=>'test')),'email'));

		// shift
		$array = array('test'=>1,2,2.5,3);
		$shift = Base\Arr::shift($array);
		assert($shift === 1);
		assert(count($array) === 3);
		$shift = Base\Arr::shift($array,3);
		assert($shift === array(2,2.5,3));
		assert(count($array) === 0);

		// pop
		$array = array('test'=>1,2,2.5,3);
		$pop = Base\Arr::pop($array);
		assert($pop === 3);
		assert(count($array) === 3);
		$pop = Base\Arr::pop($array,3);
		assert($pop === array(2.5,2,1));
		assert(count($array) === 0);

		// walk
		$array = array("test",2,4,"test4");
		Base\Arr::walk(function(&$v,$k,$extra) {
			if(is_int($v))
			$v += 1000;
			else
			$v .= $extra;
		},$array,"bla");
		assert($array === array('testbla',1002,1004,'test4bla'));
		$array = array("test",array(2),array(4),"test4");
		Base\Arr::walk(function(&$v,$k,$extra) {
			if(is_int($v))
			$v += 1000;
			elseif(is_string($v))
			$v .= $extra;
		},$array,"bla");
		assert($array === array('testbla',array(2),array(4),'test4bla'));

		// map
		$array = array(" test ",2=>'test2');
		$array2 = array(" test3 ",2=>'test4');
		assert(array("test",2=>'test2') === Base\Arr::map('trim',$array));
		assert(Base\Arr::map(function($a,$b,$c) { if(is_array($c)) return trim($a).$b; },$array) === array('test0',2=>'test22'));

		// filter
		$array = array("test",2,4,"test4");
		assert(Base\Arr::filter(function($v,$k,$a) {
			if(is_array($a) && is_int($v))
			return true;
		},$array) === array(1=>2,2=>4));
		assert(Base\Arr::filter('is_string',$array) === array('test',3=>'test4'));

		// reduce
		$array = array("test",2,4,"test4");
		assert(Base\Arr::reduce(function($carry,$item) {
			return $carry.$item;
		},$array,'bla') === 'blatest24test4');

		// diffAssoc
		$simple1 = array('test'=>true,'test3'=>'bla');
		$simple2 = array('testz'=>4,'test3'=>'bla');
		$simple3 = array('test'=>2);
		assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === array_diff_assoc($simple1,$simple2,$simple3));
		assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === array('test'=>true));
		$simple1 = array('test'=>true,'test3'=>array(2));
		$simple2 = array('testz'=>4,'test3'=>array(3));
		$simple3 = array('test'=>2);
		assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === array('test'=>true,'test3'=>array(2)));
		$simple1 = array('test'=>true,'test3'=>array(2));
		$simple2 = array('testz'=>4,'test3'=>array(2));
		$simple3 = array('test'=>true);
		assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === array());
		$array1 = array('test'=>'testx','test2'=>'test2x','test3'=>'test3x','test4'=>array('test5'=>'oui','ok'=>'ok'));
		$array2 = array('testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>array('test5'=>'oui','ok'=>'ok'));
		assert(array('test'=>'testx','test3'=>'test3x') === Base\Arr::diffAssoc($array1,$array2));

		// diffKey
		$array1 = array('key'=>1,'key2'=>2,'key4'=>4);
		$array2 = array('key3'=>2,'key4'=>3);
		assert(array('key'=>1,'key2'=>2) === Base\Arr::diffKey($array1,$array2));
		assert(array_diff_key($array1,$array2) === Base\Arr::diffKey($array1,$array2));
		$array1 = array('key'=>1,'key2'=>array(4),'key4'=>array(3));
		$array2 = array('key3'=>2,'key4'=>3);
		assert(Base\Arr::diffKey($array1,$array2) === array('key'=>1,'key2'=>array(4)));

		// diff
		$array1 = array('key'=>1,'key2'=>2);
		$array2 = array('key3'=>2);
		assert(array('key'=>1) === Base\Arr::diff($array1,$array2));
		assert(array_diff($array1,$array2) === Base\Arr::diff($array1,$array2));
		$simple1 = array('test'=>true,'test4'=>array(3));
		$simple2 = array('testz'=>4,'test3'=>array(3));
		$simple3 = array('test'=>2);
		assert(Base\Arr::diff($simple1,$simple2,$simple3) === array('test'=>true));
		$simple3 = array('test'=>2,'bla'=>true);
		assert(Base\Arr::diff($simple1,$simple2,$simple3) === array());

		// intersectAssoc
		$array1 = array('test'=>'ok','bla'=>2);
		$array2 = array('test'=>'ok','bla'=>3);
		$array3 = array('test'=>'ok','bla'=>4);
		assert(array_intersect_assoc($array1,$array2,$array3) === Base\Arr::intersectAssoc($array1,$array2,$array3));
		assert(array('test'=>'ok') === Base\Arr::intersectAssoc($array1,$array2,$array3));
		$array1 = array('test'=>'testx','test2'=>'test2x','test3'=>'test3x','test4'=>array('test5'=>'oui','ok'=>'ok'));
		$array2 = array('testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>array('test5'=>'ouiz','ok'=>'ok'));
		$array3 = array('testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>array('test5'=>'ouiz','ok'=>'ok'));
		assert(array('test2'=>'test2x') === Base\Arr::intersectAssoc($array1,$array2,$array3));

		// intersectKey
		$array1 = array('key3'=>array(2),'key2'=>'bla');
		$array2 = array('key3'=>2);
		assert(array('key3'=>array(2)) === Base\Arr::intersectKey($array1,$array2));
		assert(array_intersect_key($array1,$array2) === Base\Arr::intersectKey($array1,$array2));
		$array3 = array('key4'=>2);
		assert(array() === Base\Arr::intersectKey($array1,$array2,$array3));

		// intersect
		$array1 = array('key3'=>1,'key2'=>2);
		$array2 = array('key3'=>2);
		assert(array('key2'=>2) === Base\Arr::intersect($array1,$array2));
		assert(array_intersect($array1,$array2) === Base\Arr::intersect($array1,$array2));
		$array1 = array('test'=>'ok','bla'=>array(2));
		$array2 = array('test'=>array(2),'bla'=>3);
		$array3 = array('test'=>'ok','bla'=>array(2));
		assert(Base\Arr::intersect($array1,$array2,$array3) === array('bla'=>array(2)));

		// unsetBeforeKey
		assert(array(2=>3,3=>4,4=>5) === Base\Arr::unsetBeforeKey(2,array(1,2,3,4,5)));
		assert(array(1,2,3) === Base\Arr::unsetBeforeKey(0,array(1,2,3)));

		// unsetAfterKey
		assert(array(1,2,3) === Base\Arr::unsetAfterKey(2,array(1,2,3,4,5)));
		assert(array(1) === Base\Arr::unsetAfterKey(0,array(1,2,3)));

		// unsetBeforeValue
		assert(array(2=>3,3=>4,4=>5) === Base\Arr::unsetBeforeValue(3,array(1,2,3,4,5)));
		assert(array() === Base\Arr::unsetBeforeValue(0,array(1,2,3)));

		// unsetAfterValue
		assert(array(1,2) === Base\Arr::unsetAfterValue(2,array(1,2,3,4,5)));
		assert(array(1) === Base\Arr::unsetAfterValue(1,array(1,2,3)));

		// unsetBeforeIndex
		assert(array(2=>3,3=>4,4=>5) === Base\Arr::unsetBeforeIndex(2,array(1,2,3,4,5)));
		assert(array(1,2,3) === Base\Arr::unsetBeforeIndex(0,array(1,2,3)));
		assert(array(1=>2,2=>3) === Base\Arr::unsetBeforeIndex(1,array(1,2,3)));

		// unsetAfterIndex
		assert(array(1,2,3) === Base\Arr::unsetAfterIndex(2,array(1,2,3)));
		assert(array(1) === Base\Arr::unsetAfterIndex(0,array(1,2,3)));

		// unsetBeforeCount
		assert(array(1=>2,2=>3,3=>4,4=>5) === Base\Arr::unsetBeforeCount(2,array(1,2,3,4,5)));
		assert(array(1,2,3) === Base\Arr::unsetBeforeCount(0,array(1,2,3)));
		assert(array(1,2,3) === Base\Arr::unsetBeforeCount(1,array(1,2,3)));

		// unsetAfterCount
		assert(array(1,2) === Base\Arr::unsetAfterCount(2,array(1,2,3)));
		assert(array(1,2,3) === Base\Arr::unsetAfterCount(100,array(1,2,3)));
		assert(array(1) === Base\Arr::unsetAfterCount(1,array(1,2,3)));

		// count
		assert(Base\Arr::count(array()) === 0);
		assert(Base\Arr::count(array(1,array(2))) === 2);

		// countValues
		assert(array("test"=>2,"test2"=>1) === Base\Arr::countValues(array("test","test","test2")));
		assert(array("test"=>2,'TEST'=>1) === Base\Arr::countValues(array("test","TEST","test",true,false)));
		assert(array("test"=>3) === Base\Arr::countValues(array("TEST","test","tEst",true,false),false));
		assert(array('tést'=>3) === Base\Arr::countValues(array("TÉST","tést","TÉSt",true,false),false));
		assert(array() === Base\Arr::countValues(array(array(),false,true)));

		// search
		assert(1 === Base\Arr::search(2,array(1,2,'test'=>'ok')));
		assert(Base\Arr::search("A",array("a","asdsd"),false) === 0);
		assert(Base\Arr::search("éé",array("a","ÉÉ"),false) === 1);
		assert(Base\Arr::search("èè",array("a","ÉÉ"),false) === null);

		// searchFirst
		$array = array(true,2=>false,'2',false,array(2));
		assert(Base\Arr::searchFirst(array(false,true),$array) === 2);
		assert(Base\Arr::searchFirst(array('2',array(2)),$array) === 3);
		assert(Base\Arr::searchFirst(array(null,'2',array(2)),$array) === 3);
		$slice = array('1',2,array(2),'test');
		assert(Base\Arr::searchFirst(array(1),$slice) === null);
		$slice = array('1',2,array(2),'test','éé');
		assert(Base\Arr::searchFirst(array('TEST'),$slice) === null);
		assert(Base\Arr::searchFirst(array('TEST'),$slice,false) === 3);
		assert(Base\Arr::searchFirst(array('ÉÉ'),$slice,false) === 4);

		// in
		assert(Base\Arr::in(1,array(0,1)));
		assert(Base\Arr::in(0,array(0,1)));
		assert(Base\Arr::in('b',array('A','b'),false));
		assert(Base\Arr::in('a',array('A','b'),false));
		assert(Base\Arr::in(array('a'),array(array('A'),'b'),false));
		assert(Base\Arr::in(array('A'),array(array('A'),'b'),false));
		assert(Base\Arr::in('É',array('é','b'),false));

		// ins
		assert(Base\Arr::ins(array(1),array(0,1)));
		assert(Base\Arr::ins(array(0,1),array(0,1)));
		assert(Base\Arr::ins(array(array(0)),array(array(0))));
		assert(!Base\Arr::ins(array(0,1,2),array(0,1)));
		assert(!Base\Arr::ins(array('1'),array(0,1)));
		assert(!Base\Arr::ins(array('a'),array('A','b')));
		assert(Base\Arr::ins(array('a','b'),array('A','b'),false));
		assert(!Base\Arr::ins(array('a','b','c'),array('A','b'),false));
		assert(Base\Arr::ins(array('a'),array('A','b'),false));
		assert(Base\Arr::ins(array('é'),array('É','b'),false));
		assert(Base\Arr::ins(array(array('a')),array(array('A'),'b'),false));
		assert(Base\Arr::ins(array(array('A')),array(array('A'),'b'),false));

		// inFirst
		$array = array(true,2=>false,'2','A',false,array(2));
		assert(Base\Arr::inFirst(array(false,true),$array) === false);
		assert(Base\Arr::inFirst(array('2',array(2)),$array) === '2');
		assert(Base\Arr::inFirst(array(null,'2',array(2)),$array) === '2');
		assert(Base\Arr::inFirst(array('a'),$array) === null);
		assert(Base\Arr::inFirst(array('a'),$array,false) === 'a');
		$slice = array('1',2,array(2),'test');
		assert(Base\Arr::inFirst(array(1),$slice) === null);
		assert(Base\Arr::inFirst(array('é','e'),array('É','e')) === 'e');
		assert(Base\Arr::inFirst(array('é','e'),array('É','e'),false) === 'é');

		// combine
		assert(Base\Arr::combine(array('test'),array('bla')) === array('test'=>'bla'));
		assert(Base\Arr::combine('test','bla') === array('test'=>'bla'));
		assert(Base\Arr::combine('test',array('bla','bla')) === array());
		assert(Base\Arr::combine('',array('bla')) === array(''=>'bla'));
		assert(Base\Arr::combine(null,array('bla')) === array());
		assert(Base\Arr::combine(array('bla','ok'),true) === array('bla'=>true,'ok'=>true));
		assert(Base\Arr::combine(array('bla','ok'),null) === array('bla'=>null,'ok'=>null));
		assert(Base\Arr::combine(array(array("bla")),array('bla')) === array());

		// uncombine
		$source = array(array(0),array(1));
		assert($x = Base\Arr::uncombine($source));
		assert(Base\Arr::combine(...$x) === $source);

		// range
		assert(array(0,2,4) === Base\Arr::range(0,5,2));
		assert(array(2) === Base\Arr::range(2,3,2));

		// shuffle
		$array = array(1,'test'=>2,3);
		$array2 = Base\Arr::shuffle($array);
		assert(count($array2) === 3);
		assert(!Base\Arr::isIndexed($array2));
		assert(Base\Arr::isSequential(Base\Arr::shuffle($array,false)));
		assert(!Base\Arr::isSequential(Base\Arr::shuffle($array,true)));

		// reverse
		assert(Base\Arr::reverse(array(1,2,3),false) === array(3,2,1));
		assert(Base\Arr::reverse(array(1,2,3)) === array(2=>3,1=>2,0=>1));

		// getSortAscDesc
		assert(Base\Arr::getSortAscDesc(true) === 'asc');
		assert(Base\Arr::getSortAscDesc(false) === 'desc');
		assert(Base\Arr::getSortAscDesc(23) === null);

		// sort
		assert(Base\Arr::sort(array('x'=>'a','b'=>'c','w'=>2),1) === array('b'=>'c','w'=>2,'x'=>'a'));
		assert(Base\Arr::sort(array('x'=>'a','b'=>'c','w'=>2),2) === array('x'=>'a','w'=>2,'b'=>'c'));
		assert(Base\Arr::sort(array('x'=>'a','b'=>'c','w'=>2),3) === array('w'=>2,'x'=>'a','b'=>'c'));
		assert(Base\Arr::sort(array('x'=>'a','b'=>'c','w'=>2),4) === array('b'=>'c','x'=>'a','w'=>2));

		// sortNumbersFirst
		assert(Base\Arr::sortNumbersFirst(array('meh'=>2,2=>true,'test'=>'ok',0=>'what')) === array('what',2=>true,'meh'=>2,'test'=>'ok'));
		assert(Base\Arr::sortNumbersFirst(array('meh'=>2,2=>true,'test'=>'ok',0=>'what'),false) === array(2=>true,0=>'what','meh'=>2,'test'=>'ok'));

		// random
		assert(1 === count(Base\Arr::random(array(1,2,3),1)));
		assert(2 === count(Base\Arr::random(array(1,2,3),2)));
		assert(3 === count(Base\Arr::random(array(1,2,3),4)));

		// pad
		assert(Base\Arr::pad(5,true,array(1,2,3)) === array(1,2,3,true,true));
		assert(Base\Arr::pad(2,true,array(1,2,3)) === array(1,2,3));

		// flip
		assert(array("test"=>0,"test2"=>1) === Base\Arr::flip(array("test","test2")));
		assert(array("test"=>"test","test2"=>"test2") === Base\Arr::flip(array("test"=>"test","test2"=>"test2")));
		assert(array(array("test")) === Base\Arr::flip(array(array("test")),false,true));
		assert(array("test"=>array("test","test2")) === Base\Arr::flip(array("test"=>array("test","test2")),false,true));
		assert(array("test"=>array("test","test2")) === Base\Arr::flip(array("test"=>array("test","test2")),array('test'),true));
		assert(array(1=>"test") === Base\Arr::flip(array(1=>"test"),true,1));

		// unique
		$array = array(1,2,2,2,'2',array(1),array(2));
		assert(count(Base\Arr::unique($array)) === 5);
		assert(count(Base\Arr::unique($array,true,true,true)) === 4);
		assert(count(Base\Arr::unique($array)) === 5);
		$arr = array("test","TEST","tEST","tést","TÉST");
		assert(count(Base\Arr::unique($arr,true,false)) === 0);
		assert(count(Base\Arr::unique($arr,false,false)) === 2);

		// duplicate
		$arr = array("test","TEST","tEST","tést","TÉST");
		assert(count(Base\Arr::duplicate($arr,true,false)) === 5);
		assert(count(Base\Arr::duplicate($arr,false,false)) === 3);
		assert(count(Base\Arr::duplicate($array)) === 2);
		assert(count(Base\Arr::duplicate($array,true)) === 3);

		// implode
		assert("test|test2" === Base\Arr::implode("|",array("test"=>"test","test2")));
		assert('test|test2' === Base\Arr::implode("|",array("test"=>"test","test2","test3"=>array("test4"))));
		assert('test|test2' === Base\Arr::implode("|",array("test"=>" test ","test2","test3"=>""),true,true));

		// implodeTrim
		assert(Base\Arr::implodeTrim("|",array("test"=>" test ","test2","test3"=>"")) === 'test|test2|');

		// implodeClean
		assert(Base\Arr::implodeClean("|",array("test"=>" test ","test2","test3"=>"")) === ' test |test2');

		// implodeTrimClean
		assert(Base\Arr::implodeTrimClean("|",array("test"=>" test ","test2","test3"=>"")) === 'test|test2');

		// implodeKey
		assert(Base\Arr::implodeKey("|",":",array('test'=>'test2','test2 '=>3,'test4'=>array(),'test5'=>'')) === "test:test2|test2 :3|test5:");
		assert(Base\Arr::implodeKey("|",":",array('test'=>'test2','test2 '=>3,'test4'=>array(),'test5'=>''),true) === "test:test2|test2:3|test5:");
		assert(Base\Arr::implodeKey("|",":",array('test'=>'test2','test2 '=>3,'test4'=>array(),'test5'=>''),true,true) === "test:test2|test2:3");

		// explode
		assert(array("test","test2","test3","test4") === Base\Arr::explode("|",array("test|test2","test3|test4")));
		assert(array("test") === Base\Arr::explode("|",array(2=>"test",array("test2|test3"))));
		assert(array("test","test2","test3","test4") === Base\Arr::explode("|",array("test | test2","test3 | | test4"),null,true,true));

		// explodekeyValue
		assert(Base\Arr::explodekeyValue(":",array("test: what","james2: ok","test : new"),true,true) === array('test'=>'new','james2'=>'ok'));

		// fill
		assert(count(Base\Arr::fill(0,5)) === 6);
		assert(count(Base\Arr::fill(-2,10,1,5)) === 13);

		// fillKeys
		assert(count(Base\Arr::fillKeys(range(0,5))) === 6);

		// chunk
		$array = array(1,2,3,4,5,6,7,8,'test'=>9);
		assert(array(array(1,2),array(3,4),array(5,6),array(7,8),array(9)) === Base\Arr::chunk(2,$array,false));
		assert(array(array(1,2),array(2=>3,3=>4),array(4=>5,5=>6),array(6=>7,7=>8),array('test'=>9)) === Base\Arr::chunk(2,$array,true));
		assert(array($array) === Base\Arr::chunk(10,$array));

		// chunkGroup
		$array = array(1,2,3,4,5,6,7,8,'test'=>9);
		assert(array(array(1,2,3,4,5),array(6,7,8,9)) === Base\Arr::chunkGroup(2,$array,false));
		assert(array(array(1,2,3,4,5),array(5=>6,6=>7,7=>8,'test'=>9)) === Base\Arr::chunkGroup(2,$array,true));

		// chunkMix
		$array = array(1,2,3,4);
		assert(array(array(1,4),array(2),array(3)) === Base\Arr::chunkMix(3,$array,false));
		assert(array(array(1,3),array(2,4)) === Base\Arr::chunkMix(2,$array,false));
		$array = array(1,2,3,4,5,6,7,8,'test'=>9,10,11,12);
		assert(array(array(1,4,7,10),array(2,5,8,11),array(3,6,9,12)) === Base\Arr::chunkMix(3,$array,false));
		assert(Base\Arr::chunkMix(3,$array,true) === array(array(1,3=>4,6=>7,8=>10),array(1=>2,4=>5,7=>8,9=>11),array(2=>3,5=>6,'test'=>9,10=>12)));

		// chunkWalk
		$array = array(1,'what',2,null,3,array(),4,'test',null);
		assert(Base\Arr::chunkWalk(function($v) {
			if(is_numeric($v))
			return true;
			
			if($v === null)
			return false;
		},$array) === array(array(1,'what'),array(2),array(3,array()),array(4,'test')));

		// compareIn
		assert(Base\Arr::compareIn(array('type'=>null),array('type'=>'file')));
		assert(Base\Arr::compareIn(array('type'=>array('file','dir')),array('type'=>'file')));
		assert(!Base\Arr::compareIn(array('type'=>array('file','dir')),array('type'=>'dirs')));
		assert(Base\Arr::compareIn(array('type'=>array('file','dir'),'ok'=>'yes'),array('type'=>'file','ok'=>'yes')));
		assert(!Base\Arr::compareIn(array('type'=>array('file','dir'),'ok'=>'yes'),array('type'=>'file')));
		assert(!Base\Arr::compareIn(array('type'=>array('file','dir'),'ok'=>'yes'),array('type'=>'file','ok'=>'yzes')));
		assert(Base\Arr::compareIn(array('type'=>array(array('file'),'dir'),'ok'=>'yes'),array('type'=>array('file'),'ok'=>'yes')));

		// compareOut
		assert(!Base\Arr::compareOut(array('type'=>null),array('type'=>'dir')));
		assert(Base\Arr::compareOut(array('type'=>array('dir')),array('type'=>'dir')));
		assert(!Base\Arr::compareOut(array('type'=>array('dir')),array('type'=>array('dir'))));
		assert(Base\Arr::compareOut(array('type'=>array(array('dir'))),array('type'=>array('dir'))));
		assert(Base\Arr::compareOut(array('type'=>array('dir'),'basename'=>'test'),array('type'=>'file','basename'=>'test')));
		assert(Base\Arr::compareOut(array('type'=>array('dir'),'basename'=>'test'),array('type'=>'dir','basename'=>'test')));
		assert(!Base\Arr::compareOut(array('type'=>array('dir','file'),'basename'=>'test'),array('type'=>'filez','basename'=>'testz')));
		assert(Base\Arr::compareOut(array('type'=>array('dir','file'),'basename'=>'test'),array('type'=>'file','basename'=>'testz')));

		// hasSlices
		assert(Base\Arr::hasSlices(array('test'=>2),array('test'=>2,'ok'=>3)));
		assert(Base\Arr::hasSlices(array(),array('test'=>2,'ok'=>3)));
		assert(Base\Arr::hasSlices(array('ok'=>3,'test'=>2),array('test'=>2,'ok'=>3)));
		assert(!Base\Arr::hasSlices(array('ok'=>3,'test'=>3),array('test'=>2,'ok'=>3)));
		assert(!Base\Arr::hasSlices(array('TEST'=>'OKÉ'),array('test'=>'oké','ok'=>2)));
		assert(Base\Arr::hasSlices(array('TEST'=>'OKÉ'),array('test'=>'oké','ok'=>2),false));

		// slice
		$slice = array(1,2,'test'=>'ok',8,9,'james'=>1,'bla'=>true);
		assert(Base\Arr::slice("test","bla",$slice) === array('test'=>'ok',2=>8,3=>9,'james'=>1,'bla'=>true));
		assert(Base\Arr::slice("bla","bla",$slice) === array('bla'=>true));
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(array("test"=>"testv","test2"=>"test2v","test3"=>"test3v") === Base\Arr::slice("test","test3",$slice));
		$slice = array('test','bla'=>'test2','test3');
		assert(Base\Arr::slice('bla',null,$slice) === array('bla'=>'test2'));
		assert(Base\Arr::slice("BLA",null,$slice) === array());
		$slice = array(1,2,3,4);
		assert(array(1,2,3) === Base\Arr::slice(0,2,$slice));

		// sliceIndex
		$slice = array(1,2,3,4);
		assert(array(1,2) === Base\Arr::sliceIndex(0,2,$slice));
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(Base\Arr::sliceIndex(-2,2,$slice) === array("test2"=>"test2v","test3"=>"test3v"));
		$slice = array('test','bla'=>'test2','test3');
		assert(Base\Arr::sliceIndex(1,0,$slice) === array());
		assert(Base\Arr::sliceIndex(0,2,$slice) === array('test','bla'=>'test2'));
		assert(Base\Arr::sliceIndex(0,-1,$slice) === array('test','bla'=>'test2'));
		assert(Base\Arr::sliceIndex(2,1,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(-1,1,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(-1,null,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(0,null,$slice) === array('test'));
		assert(Base\Arr::sliceIndex(-2,2,$slice) === array('bla'=>'test2',1=>'test3'));
		assert(Base\Arr::sliceIndex(1,0,$slice) === array());
		assert(Base\Arr::sliceIndex(0,2,$slice) === array('test','bla'=>'test2'));
		assert(Base\Arr::sliceIndex(0,-1,$slice) === array('test','bla'=>'test2'));
		assert(Base\Arr::sliceIndex(2,1,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(-1,1,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(-1,1,$slice) === array(1=>'test3'));
		assert(Base\Arr::sliceIndex(0,null,$slice) === array('test'));
		$slice2 = array(1,2,3,'test'=>4,5,6,7,8,'ok'=>'james',10);
		assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(3,6,$slice2));
		assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(3,6,$slice2));
		assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(-7,6,$slice2));

		// sliceFirst
		$slice = array("test"=>"test","test2"=>"test2","test3"=>"test3");
		assert(array("test"=>"test") === Base\Arr::sliceFirst($slice));

		// sliceLast
		assert(array("test3"=>"test3") === Base\Arr::sliceLast($slice));

		// sliceNav
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(array("test3"=>"test3v") === Base\Arr::sliceNav("test2",1,$slice));
		assert("test3v" === current(Base\Arr::sliceNav("test2",1,$slice)));
		assert(null === Base\Arr::sliceNav("test2",4,$slice));
		assert("test" === key(Base\Arr::sliceNav("test2",-1,$slice)));
		assert("test2" === key(Base\Arr::sliceNav("test2",0,$slice)));

		// sliceNavIndex
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(array("test2"=>"test2v") === Base\Arr::sliceNavIndex(0,1,$slice));
		assert(array("test"=>"testv") === Base\Arr::sliceNavIndex(-1,-2,$slice));

		// splice
		$array = array(1,'test'=>2,3,4,5,6,'end'=>'what',8);
		assert(Base\Arr::splice('test','test',array('test'=>2,'what'=>'ok')) === array('what'=>'ok'));
		assert(Base\Arr::splice(1,'end',$array,array('bla')) === array(1,'test'=>2,1=>'bla',5=>8));
		assert(Base\Arr::splice('test',true,$array) === array(1,3,4,5,6,'end'=>'what',8));
		assert(Base\Arr::splice('test','end',$array) === array(1,5=>8));
		assert(Base\Arr::splice('test','end',$array,array('james'=>'ok')) === array(1,'james'=>'ok',5=>8));
		assert(Base\Arr::splice('test','end',$array,array('bla')) === array(1,'bla',5=>8));
		assert(Base\Arr::splice('test','end',array(1,'test'=>2,3,4,5,6,'end'=>'what',8),array('bla')) === array(1,'bla',5=>8));
		assert(Base\Arr::splice('test','end',array(1,'test'=>2,3,4,5,6,'end'=>'what',8),array('test'=>'OK')) === array(1,'test'=>'OK',5=>8));
		assert(Base\Arr::splice('testz',null,$array,array('test'=>'OK')) === $array);
		assert(Base\Arr::splice('end','end',$array,array('end'=>'what2')) === array(1,'test'=>2,3,4,5,6,'end'=>'what2',5=>8));
		assert(Base\Arr::splice('end',0,$array,array('end'=>'what2')) === array(1,'end'=>'what2',5=>8));
		assert(Base\Arr::splice('end',5,$array,array('TEST'=>'OK'),false) === array(1,3,4,5,6,'TEST'=>'OK'));
		assert(Base\Arr::splice('END',5,$array,array('TEST'=>'OK'),false) === array(1,3,4,5,6,'TEST'=>'OK'));
		assert(Base\Arr::splice('Test',1,$array,array('ENDz'=>'OK'),false) === array(1,'ENDz'=>'OK',2=>4,3=>5,4=>6,'end'=>'what',5=>8));
		assert(Base\Arr::splice('Test',1,$array,array('END'=>'OK'),false) === array(1,2=>4,3=>5,4=>6,'end'=>'what',5=>8));

		// spliceIndex
		$array = array(1,'test'=>2,3,4,5,6,'end'=>'what',8);
		assert(Base\Arr::spliceIndex(0,null,$array) === array('test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8));
		assert(Base\Arr::spliceIndex(0,2,$array) === array(1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8));
		assert(Base\Arr::spliceIndex(-2,2,$array) === array(1,'test'=>2,3,4,5,6));
		assert(Base\Arr::spliceIndex(1000,1000,$array) === $array);

		// spliceFirst
		$array = array(1,'test'=>2,3,4,5,6,'end'=>'what',8);
		assert(Base\Arr::spliceFirst($array) === array('test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8));
		assert(Base\Arr::spliceFirst($array,array('ohoh')) === array('ohoh','test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8));

		// spliceLast
		$array = array(1,'test'=>2,3,4,5,6,'end'=>'what',8);
		assert(Base\Arr::spliceLast($array) === array(1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what'));
		assert(Base\Arr::spliceLast($array,array('beurp')) === array(1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>'beurp'));
		assert(Base\Arr::spliceLast($array,array(6=>'beurp')) === array(1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',6=>'beurp'));
		assert(Base\Arr::spliceLast($array,array('end'=>'beurp')) === array(1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'beurp'));

		// insert
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(array(0=>"testa","test"=>"testv","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insert('test',array('testa'),$slice));
		assert(array("key"=>"testa","test"=>"testv","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insert('test',array("key"=>"testa"),$slice));
		assert(array("test"=>"testv","key"=>"testa","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insert("test2",array("key"=>"testa"),$slice));
		assert(array("testv","testa","test2v","test3v") === Base\Arr::values(Base\Arr::insert("test2",array("key"=>"testa"),$slice)));
		assert(array("test"=>"testv","key"=>"testa") === Base\Arr::unsetAfterCount(2,Base\Arr::insert("test2",array("key"=>"testa"),$slice)));
		assert(array() === Base\Arr::unsetAfterCount(0,Base\Arr::insert("test2",array("key"=>"testa"),$slice)));
		assert(Base\Arr::insert('test2',array('TEST3'=>'what'),$slice,false) === array('test'=>'testv','test2'=>'test2v','test3'=>'test3v'));
		assert(Base\Arr::insert('test3',array('TEST2'=>'what'),$slice,false) === array('test'=>'testv','TEST2'=>'what','test3'=>'test3v'));
		$slice = array(1,2,3,4);
		assert(array(0=>1,1=>4,2=>2,3=>3,4=>4) === Base\Arr::insert(1,array(1=>4),$slice));
		assert(array(1,2,"test"=>"test",3,4) === Base\Arr::insert(2,array("test"=>"test"),$slice));
		assert(array(1,2,"test",3,4) === Base\Arr::insert(2,array("test"),$slice));
		assert($slice === Base\Arr::insert(800,array("test"),$slice));
		$array = array(2=>4,8=>12,12=>13);
		assert(Base\Arr::insert(3,array('OK'),$array) === array(2=>4,8=>12,12=>13));

		// insertIndex
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert(Base\Arr::insertIndex(0,array('testa'),$slice) === array(0=>"testa","test"=>"testv","test2"=>"test2v","test3"=>"test3v"));
		assert(array("key"=>"testa","test"=>"testv","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insertIndex(-3,array("key"=>"testa"),$slice));
		assert(array("test"=>"testv","key"=>"testa","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insertIndex(-2,array("key"=>"testa"),$slice));
		assert(array("test"=>"testv","test2"=>"test2v","key"=>"testa","test3"=>"test3v") === Base\Arr::insertIndex(-1,array("key"=>"testa"),$slice));
		assert(array("key"=>"testa","test"=>"testv","test2"=>"test2v","test3"=>"test3v") === Base\Arr::insertIndex(0,array("key"=>"testa"),$slice));
		assert(Base\Arr::insertIndex(1000,array('replace'=>true),$slice)['replace'] === true);

		// insertFirst
		$slice = array(1,2,3,4);
		assert(Base\Arr::insertFirst(array(0),$slice) === array(0,1,2,3,4));
		assert(Base\Arr::insertFirst(array(0),$slice) === Base\Arr::prepend($slice,array(0)));

		// insertLast
		$slice = array(1,2,3,4);
		assert(Base\Arr::insertLast(array(0),$slice) === array(1,2,3,0,4));
		assert(Base\Arr::insertLast(array(0),$slice) !== Base\Arr::append($slice,array(0)));

		// insertInOrder
		$array = array(2=>4,8=>12,12=>13);
		assert(Base\Arr::insertInOrder(array(11=>'OK'),$array) === array(2=>4,8=>12,11=>'OK',12=>13));
		assert(Base\Arr::insertInOrder(array(11=>'OK',3=>'well'),$array) === array(2=>4,3=>'well',8=>12,11=>'OK',12=>13));
		$array = array('b'=>4,'d'=>12,'f'=>13);
		assert(Base\Arr::insertInOrder(array('a'=>'begin','z'=>'end','c'=>'OK'),$array) === array('a'=>'begin','b'=>4,'c'=>'OK','d'=>12,'f'=>13,'z'=>'end'));
		assert(Base\Arr::insertInOrder(array('a'=>'begin','z'=>'end','c'=>'OK'),array()) === array('a'=>'begin','c'=>'OK','z'=>'end'));

		// firstWithKey
		assert(array("user"=>null) === Base\Arr::firstWithKey("user",array("bla"),array("user"=>null)));
		assert(null === Base\Arr::firstWithKey("user",array(),array()));

		// firstWithValue
		assert(array("user") === Base\Arr::firstWithValue("user",array("user")));
		assert(null === Base\Arr::firstWithValue("user",array(array("user"))));
		assert(array(array("user")) === Base\Arr::firstWithValue(array("user"),array(array("user"))));
		assert(null === Base\Arr::firstWithValue("user",array(),array()));

		// indexFirst
		assert(Base\Arr::indexFirst(array(1,2,3)) === 0);
		assert(Base\Arr::indexFirst(array()) === null);

		// indexLast
		assert(Base\Arr::indexLast(array(1,2,3)) === 2);
		assert(Base\Arr::indexLast(array()) === null);

		// indexExists
		assert(Base\Arr::indexExists(1,array(1,2,3)));
		assert(!Base\Arr::indexExists(4,array(1,2,3)));

		// indexesExists
		assert(Base\Arr::indexesExists(array(0,1,2),array(1,2,3)));
		assert(!Base\Arr::indexesExists(array(0,1,2,8),array(1,2,3)));

		// indexesAre
		assert(Base\Arr::indexesAre(array(0,1,2),array(1,2,3)));
		assert(!Base\Arr::indexesAre(array(0,1,3),array(1,2,3)));
		assert(!Base\Arr::indexesAre(array(0,1,2,3),array(1,2,3)));
		assert(!Base\Arr::indexesAre(array(0,1),array(1,2,3)));

		// indexesFirst
		assert(Base\Arr::indexesFirst(array(4,1,2),array(1,2,3)) === 1);
		assert(Base\Arr::indexesFirst(array(4,4,8),array(1,2,3)) === null);

		// indexesFirstValue
		assert(Base\Arr::indexesFirstValue(array(4,1,2),array(1,2,3)) === 2);

		// indexKey
		assert(Base\Arr::indexKey(-1,array(1,2,'bla'=>3)) === 'bla');
		assert(Base\Arr::indexKey(1,array(1,2,'bla'=>3)) === 1);
		assert(Base\Arr::indexKey(-100,array(1,2,'bla'=>3)) === null);

		// indexesKey
		assert(Base\Arr::indexesKey(array(0,-1),array(1,2,'bla'=>3)) === array(0,2=>'bla'));
		assert(Base\Arr::indexesKey(array(1000,-1000),array(1,2,'bla'=>3)) === array(1000=>null,-1000=>null));

		// indexSlice
		assert(Base\Arr::indexSlice(-1,array(1,2,'bla'=>3)) === array('bla'=>3));

		// indexesSlice
		assert(Base\Arr::indexesSlice(array(0,-1),array(1,2,'bla'=>3)) === array(1,'bla'=>3));
		assert(Base\Arr::indexesSlice(array(0,-1,100),array(1,2,'bla'=>3)) === array(1,'bla'=>3));

		// indexStrip
		assert(Base\Arr::indexStrip(-1,array(1,2,'bla'=>3)) === array(1,2));

		// indexesStrip
		assert(Base\Arr::indexesStrip(array(0,-1),array(1,2,'bla'=>3)) === array(1=>2));

		// indexNav
		assert(Base\Arr::indexNav(-1,-1,array(0,1,2,3,4)) === 3);
		assert(Base\Arr::indexNav(-1,0,array(0,1,2,3,4)) === 4);
		assert(Base\Arr::indexNav(0,1,array(0,1,2,3,4)) === 1);
		assert(Base\Arr::indexNav(4,0,array(0,1,2,3,4)) === 4);
		assert(Base\Arr::indexNav(4,1,array(0,1,2,3,4)) === null);
		assert(Base\Arr::indexNav(0,-1,array(0,1,2,3,4)) === null);

		// keyFirst
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert("test" === Base\Arr::keyFirst($slice));
		assert(null === Base\Arr::keyFirst(array()));

		// keyLast
		assert("test3" === Base\Arr::keyLast($slice));
		assert(null === Base\Arr::keyLast(array()));

		// ikey
		assert("TÉST" === Base\Arr::ikey("TésT",array("TÉST"=>1,"tést"=>2,"test"=>3)));
		assert(null === Base\Arr::ikey("TésTzzz",array("TÉST"=>1,"tést"=>2,"test"=>3)));

		// ikeys
		assert(array("TÉST","tést") === Base\Arr::ikeys("TésT",array("TÉST"=>1,"tést"=>2,"test"=>3)));

		// keyExists
		assert(!Base\Arr::keyExists(array("test"),array("test"=>false,"test2"=>true)));
		assert(Base\Arr::keyExists("test",array("test"=>false,"test2"=>true)));
		assert(!Base\Arr::keyExists("test",array("TEST"=>false,"test2"=>true)));
		assert(Base\Arr::keyExists("test",array("TEST"=>false,"test2"=>true),false));
		assert(Base\Arr::keyExists("tést",array("TÉST"=>false,"test2"=>true),false,true));

		// keysExists
		assert(Base\Arr::keysExists(array("test"),array("test"=>false)));
		assert(Base\Arr::keysExists(array(0,"test"),array(0=>null,"test"=>false)));
		assert(!Base\Arr::keysExists(array(1,"test"),array(0=>null,"test"=>false)));
		assert(!Base\Arr::keysExists(array("test",'TEST2'),array("TEST"=>false,"test2"=>true)));
		assert(Base\Arr::keysExists(array("test"),array("TEST"=>false,"test2"=>true),false));
		assert(Base\Arr::keysExists(array("tést"),array("TÉST"=>false,"test2"=>true),false,true));

		// keysAre
		assert(Base\Arr::keysAre(array("test","test2"),array("test"=>false,"test2"=>true)));
		assert(!Base\Arr::keysAre(array("test","test3"),array("test"=>false,"test2"=>true)));
		assert(!Base\Arr::keysAre(array("test"),array("test"=>false,"test2"=>true)));
		assert(!Base\Arr::keysAre(array("TEST","TEST2"),array("test"=>false,"test2"=>true)));
		assert(Base\Arr::keysAre(array("TEST","TEST2"),array("test"=>false,"test2"=>true),false));
		assert(Base\Arr::keysAre(array("TEST"),array("test"=>false,"TESt"=>true),false));

		// keysFirst
		assert("test2" === Base\Arr::keysFirst(array("test2","test"),array("test"=>1,"test2"=>2)));
		assert(null === Base\Arr::keysFirst(array("test3","test4"),array("test"=>1,"test2"=>2)));
		assert("test2" === Base\Arr::keysFirst(array("test3","test4","test2"),array("test"=>1,"test2"=>2)));
		assert("test2" === Base\Arr::keysFirst(array("test3","TEST","test2"),array("test"=>1,"test2"=>2)));
		assert("TEST" === Base\Arr::keysFirst(array("test3","TEST","test2"),array("test"=>1,"test2"=>2),false));

		// keysIndexesFirst
		$array = array('test'=>'james','ok','lol');
		assert(Base\Arr::keysIndexesFirst(array('test',1),$array) === 'test');
		assert(Base\Arr::keysIndexesFirst(array('TEST',1,),$array,false) === 'TEST');
		assert(Base\Arr::keysIndexesFirst(array('james2',1),$array) === 0);

		// keysFirstValue
		assert(2 === Base\Arr::keysFirstValue(array("test2","test"),array("test"=>1,"test2"=>2)));
		assert(1 === Base\Arr::keysFirstValue(array("test3","TEST","test2"),array("test"=>1,"test2"=>2),false));
		assert(null === Base\Arr::keysFirstValue(array("test3"),array("test"=>1,"test2"=>2),false));

		// keysIndexesFirstValue
		$array = array('test'=>'james','ok','lol');
		assert(Base\Arr::keysIndexesFirstValue(array('test',1),$array) === 'james');
		assert(Base\Arr::keysIndexesFirstValue(array('TEST',1,),$array,false) === 'james');
		assert(Base\Arr::keysIndexesFirstValue(array('james2',1),$array) === "ok");

		// keyIndex
		assert(1 === Base\Arr::keyIndex("test2",array("test"=>"test","test2"=>true)));
		assert(!Base\Arr::keyIndex("test3",array("test"=>"test","test2"=>true)));
		assert(!Base\Arr::keyIndex(true,array("test"=>"test","test2"=>true)));
		$slice = array(1,2,'test'=>'ok',8,9,'james'=>1,'bla'=>true);
		assert(Base\Arr::keyIndex('test',$slice) === 2);
		assert(Base\Arr::keyIndex('testz',$slice) === null);
		assert(Base\Arr::keyIndex('TÉST',array("test"=>1,"tést"=>2),false) === 1);

		// keysIndex
		assert(array('test2'=>1) === Base\Arr::keysIndex(array("test2"),array("test"=>"test","test2"=>true)));
		assert(array('test'=>0,'test2'=>1) === Base\Arr::keysIndex(array("test","test2"),array("test"=>"test","test2"=>true)));
		assert(array('test'=>0,'test2'=>1,'WHAT'=>null) === Base\Arr::keysIndex(array("test","test2",'WHAT'),array("test"=>"test","test2"=>true)));
		assert(array('TEST'=>0,'test2'=>1,'WHAT'=>null) === Base\Arr::keysIndex(array("TEST","test2",'WHAT'),array("test"=>"test","test2"=>true),false));

		// keySlice
		$slice = array("test"=>"testv",2=>"test2v","test3"=>"test3v");
		assert(array('test3'=>'test3v') === Base\Arr::keySlice("test3",$slice));
		assert(array(2=>'test2v') === Base\Arr::keySlice("2",$slice));
		assert(array(2=>'test2v') === Base\Arr::keySlice(2,$slice));
		assert(array() === Base\Arr::keySlice(9,$slice));
		assert(array() === Base\Arr::keySlice("test",array("TEST"=>2)));
		assert(array("test"=>2) === Base\Arr::keySlice("test",array("TEST"=>2),false));

		// keysSlice
		$slice = array("test"=>"test","test2"=>"test2","test3"=>"test3");
		assert(array("test"=>"test","test2"=>"test2") === Base\Arr::keysSlice(array("test","test2"),$slice));
		assert(array('test3'=>'test3',"test"=>"test") === Base\Arr::keysSlice(array("test3",'test'),$slice));
		assert(array('test3'=>'test3',"test"=>"test") === Base\Arr::keysSlice(array("test3",'test','WHAT'),$slice));
		assert(array("tést"=>2) === Base\Arr::keysSlice(array("tést"),array("TÉST"=>2),false,true));

		// ikeySlice
		assert(array("TÉST"=>1,"tést"=>2) === Base\Arr::ikeySlice("TésT",array("TÉST"=>1,"tést"=>2,"test"=>3)));

		// keyStrip
		$slice = array("test"=>"testv",2=>"test2v","test3"=>"test3v");
		assert(array("test"=>"testv",2=>"test2v") === Base\Arr::keyStrip("test3",$slice));
		assert(array("tÉST2"=>3) === Base\Arr::keyStrip("TÉST",array("tést"=>1,"TÉst"=>2,"tÉST2"=>3),false));

		// keysStrip
		$slice = array("test"=>"test","test2"=>"test2","test3"=>"test3");
		assert(array("test3"=>"test3") === Base\Arr::keysStrip(array("test","test2"),$slice));
		assert(array("tÉST2"=>3) === Base\Arr::keysStrip(array("TÉST"),array("tést"=>1,"TÉst"=>2,"tÉST2"=>3),false));
		assert(array() === Base\Arr::keysStrip(array("TÉST","tést2"),array("tést"=>1,"TÉst"=>2,"tÉST2"=>3),false));

		// keyNav
		assert(Base\Arr::keyNav("test",1,array("test"=>2,'test2'=>true,'tres'=>'ok')) === 'test2');
		assert(Base\Arr::keyNav("test",3,array("test"=>2,'test2'=>true,'tres'=>'ok')) === null);
		assert(Base\Arr::keyNav("tres",-1,array("test"=>2,'test2'=>true,'tres'=>'ok')) === 'test2');

		// keysStart
		$array = array('test'=>2,'test_fr'=>2,'test_en'=>3,'bla_en'=>5);
		assert(array('test'=>2,'test_fr'=>2,'test_en'=>3) === Base\Arr::keysStart("test",$array));
		assert(array('bla_en'=>5) === Base\Arr::keysStart("bla",$array));
		assert(array() === Base\Arr::keysStart("BLA",$array));
		assert(array('bla_en'=>5) === Base\Arr::keysStart("BLA",$array,false));

		// keysEnd
		$array = array('test'=>2,'test_fr'=>2,'test_en'=>3,'bla_en'=>5);
		assert(array('test_en'=>3,'bla_en'=>5) === Base\Arr::keysEnd("en",$array));
		assert(array('test_fr'=>2) === Base\Arr::keysEnd("fr",$array));
		assert(array() === Base\Arr::keysEnd("FR",$array));
		assert(array('test_fr'=>2) === Base\Arr::keysEnd("FR",$array,false));

		// keysMap
		$array = array('test'=>2,'james'=>'OK',2);
		assert(Base\Arr::keysMap(function($k,$z) {
			return $k."bla".$z;
		},$array,false,'ok') === array('testblaok'=>2,'jamesblaok'=>'OK','0blaok'=>2));

		// keysChangeCase
		assert(Base\Arr::keysChangeCase(CASE_UPPER,$array) === array('TEST'=>2,'JAMES'=>'OK',2));
		assert(Base\Arr::keysChangeCase(CASE_LOWER,$array) === array('test'=>2,'james'=>'OK',2));
		assert(Base\Arr::keysChangeCase('ucfirst',$array) === array('Test'=>2,'James'=>'OK',2));

		// keysLower
		$array = array(1=>'no',1.2=>'ok','1.2'=>'ok','test'=>'no','TEST'=>'no','tEST'=>'ok','TÉST'=>'mb');
		assert(Base\Arr::keysLower($array,false) === array(1=>'ok','1.2'=>'ok','test'=>'ok','tÉst'=>'mb'));
		assert(Base\Arr::keysLower($array,true) === array(1=>'ok','1.2'=>'ok','test'=>'ok','tést'=>'mb'));

		// keysUpper
		$array = array(1=>'no',1.2=>'ok','1.2'=>'ok','test'=>'no','TEST'=>'no','tEST'=>'ok','téST'=>'mb');
		assert(Base\Arr::keysUpper($array,false) === array(1=>'ok','1.2'=>'ok','TEST'=>'ok','TéST'=>'mb'));
		assert(Base\Arr::keysUpper($array,true) === array(1=>'ok','1.2'=>'ok','TEST'=>'ok','TÉST'=>'mb'));

		// keysInsensitive
		$array = array('NO'=>1,'no'=>2,'yes'=>3,'YES'=>4,'james'=>true,2=>'test','3'=>'ok');
		assert(Base\Arr::keysInsensitive($array) === array('no'=>2,'YES'=>4,'james'=>true,2=>'test',3=>'ok'));

		// keysWrap
		$array = array("test"=>"test","!test2"=>"test2!");
		assert(array("!test!"=>"test","!!test2!"=>"test2!") === Base\Arr::keysWrap('!',null,$array,0));
		assert(array("!test"=>"test","!!test2"=>"test2!") === Base\Arr::keysWrap('!',null,$array,3));
		$array = array("test"=>"test","!test2"=>"test2!");
		assert(array("!test!"=>"test","!test2!"=>"test2!") === Base\Arr::keysWrap('!',null,$array,1));
		assert(array("!test!"=>"test","!!test2!"=>"test2!") === Base\Arr::keysWrap('!',null,$array,2));
		assert(array("!test"=>"test","!test2"=>"test2!") === Base\Arr::keysWrap('!',null,$array,4));
		$array = array("test"=>"test","!test2!"=>"test2!");
		assert(array("test!"=>"test","!test2!!"=>"test2!") === Base\Arr::keysWrap('!',null,$array,5));
		assert(array("test!"=>"test","!test2!"=>"test2!") === Base\Arr::keysWrap('!',null,$array,6));

		// keysUnwrap
		$array = array("test"=>"test","!test2!"=>"test2!");
		assert(array("test"=>"test","test2"=>"test2!") === Base\Arr::keysUnwrap('!',null,$array));
		assert(array("test"=>"test","test2"=>"test2!") === Base\Arr::keysUnwrap('!',null,$array,1));
		assert(array("test"=>"test","test2!"=>"test2!") === Base\Arr::keysUnwrap('!',null,$array,2));
		assert(array("test"=>"test","!test2"=>"test2!") === Base\Arr::keysUnwrap('!',null,$array,3));

		// keysReplace
		assert(array("test2","lapa"=>array(2)) === Base\Arr::keysReplace(array("test"=>"lapa"),array("test"=>array(2),"test2")));
		assert(array("lapa"=>array(2)) === Base\Arr::keysReplace(array("test"=>"lapa"),array("test"=>array(2),"lapa"=>true)));
		assert(array("test"=>array(2),"lapa"=>true) === Base\Arr::keysReplace(array("TEST"=>"lapa"),array("test"=>array(2),"lapa"=>true)));
		assert(array("lapa"=>array(2)) === Base\Arr::keysReplace(array("TEST"=>"lapa"),array("test"=>array(2),"lapa"=>true),false));

		// keysChange
		assert(array("zla"=>"test","zla2"=>"test2") === Base\Arr::keysChange(array("bla"=>"zla","bla2"=>"zla2"),array("bla"=>"test","bla2"=>"test2")));

		// keysMissing
		$fill = array(0=>"zero",3=>"test",5=>"test9");
		assert(array(0=>"zero",1=>false,2=>false,3=>"test",4=>false,5=>"test9") === Base\Arr::keysMissing($fill));
		assert(array(0=>"zero",1=>true,2=>true,3=>"test",4=>true,5=>"test9") === Base\Arr::keysMissing($fill,true));
		$fill = array(0=>1);
		assert(array(0=>1) === Base\Arr::keysMissing($fill,true));
		assert(array() === Base\Arr::keysMissing(array(),true));
		$fill = array(2=>2,5=>5);
		assert(array(0=>null,1=>null,2=>2,3=>null,4=>null,5=>5) === Base\Arr::keysMissing($fill,null,0));
		assert(array(2=>2,3=>null,4=>null,5=>5) === Base\Arr::keysMissing($fill,null));

		// keysReindex
		assert(Base\Arr::keysReindex(array(2=>3,3=>2,'test'=>'ok',4,'8'=>true)) === array(3,2,'test'=>'ok',4,true));
		assert(Base\Arr::keysReindex(array('test','test2'),2) === array(2=>'test',3=>'test2'));
		
		// keysSort
		$sort = array("z"=>"test","b"=>"test","c"=>array("z"=>"a","b"=>"b"));
		$sort = Base\Arr::keysSort($sort,true);
		assert(array("b"=>"test","c"=>array("z"=>"a","b"=>"b"),"z"=>"test") === $sort);
		$sort = array("z"=>"test","b"=>"test","c"=>array("z"=>"a","b"=>"b"));
		$sort = Base\Arr::keysSort($sort,true);
		assert(array("b"=>"test","c"=>array("z"=>"a","b"=>"b"),"z"=>"test") === $sort);
		
		// keyRandom
		assert(1 === strlen((string) Base\Arr::keyRandom(array(1,2,3))));

		// valuesAre
		$slice = array('test','TEST','a','TÉST','tést');
		assert(Base\Arr::valuesAre(array('test','a','TÉST'),$slice,false));
		$array = array(true,2=>false,'2',false,array(2));
		assert(Base\Arr::valuesAre(array(true,false,array(2),'2'),$array));
		assert(!Base\Arr::valuesAre(array(array(2),'2'),$array));
		assert(!Base\Arr::valuesAre(array(true,false,array(2),2),$array));
		$slice = array('1',2,array(2),'test');
		assert(!Base\Arr::valuesAre(array(1,2,'test',array(2)),$slice));

		// valueFirst
		$slice = array("test"=>"testv","test2"=>"test2v","test3"=>"test3v");
		assert("testv" === Base\Arr::valueFirst($slice));
		assert(null === Base\Arr::valueFirst(array()));

		// valueLast
		assert("test3v" === Base\Arr::valueLast($slice));
		assert(null === Base\Arr::valueLast(array()));

		// valueIndex
		$array = array(true,2=>false,true,false,true);
		assert(Base\Arr::valueIndex(true,$array) === array(0,2,4));
		assert(Base\Arr::valueIndex(false,$array) === array(1,3));
		$slice = array('1',2,array(2),'test');
		assert(Base\Arr::valueIndex(1,$slice) === array());
		$slice = array('É','testé','TEST','tEst');
		assert(Base\Arr::valueIndex("test",$slice,false) === array(2,3));
		assert(Base\Arr::valueIndex("testÉ",$slice,false) === array(1));

		// valuesIndex
		$array = array(true,2=>false,true,false,true);
		assert(Base\Arr::valuesIndex(array(true),$array) === array(0,2,4));
		assert(Base\Arr::valuesIndex(array(false),$array) === array(1,3));
		assert(Base\Arr::valuesIndex(array(false,true),$array) === array(1,3,0,2,4));
		$slice = array('1',2,array(2),'test');
		assert(Base\Arr::valuesIndex(array(1),$slice) === array());
		$slice = array('É','testé','TEST','tEst');
		assert(Base\Arr::valuesIndex(array("test"),$slice,false) === array(2,3));
		assert(Base\Arr::valuesIndex(array("testÉ"),$slice,false) === array(1));

		// valueKey
		$array = array(true,2=>false,true,false,true);
		assert(Base\Arr::valueKey(true,$array) === array(0,3,5));
		assert(Base\Arr::valueKey(false,$array) === array(2,4));
		$array = array(true,2=>false,true,false,true,1);
		assert(array(1) === Base\Arr::valueKey(2,array('ok',2,'test'=>'ok')));
		assert(array(0,'test') === Base\Arr::valueKey('ok',array('ok',2,'test'=>'ok')));
		assert(Base\Arr::valueKey(null,array('ok',2,'test'=>'ok')) === array());
		$slice = array('É','testé','TEST','tEst');
		assert(Base\Arr::valueKey("test",$slice,false) === array(2,3));
		assert(Base\Arr::valueKey("testÉ",$slice,false) === array(1));

		// valuesKey
		$array = array(true,2=>false,true,1=>false,true);
		assert(Base\Arr::valuesKey(array(false),$array) === array(2,1));
		assert(Base\Arr::valuesKey(array(false,true,false),$array) === array(2,1,0,3,4));
		$slice = array('1',2,array(2),'test');
		assert(Base\Arr::valuesKey(array(1),$slice) === array());
		$slice = array('É','testé','TEST');
		assert(count(Base\Arr::valuesKey(array('test','é'),$slice,false)) === 2);
		assert(count(Base\Arr::valuesKey(array('test'),$slice)) === 0);

		// valueSlice
		$slice = array('1',2,array(2),'test');
		assert(array(1=>2) === Base\Arr::valueSlice(2,$slice));
		assert(Base\Arr::valueSlice(1,$slice) === array());
		$slice = array('É','testé','TEST');
		assert(Base\Arr::valueSlice("é",$slice,false) === array('É'));

		// valuesSlice
		$slice = array('1',2,array(2),'test');
		assert(array('1',2) === Base\Arr::valuesSlice(array('1',2),$slice));
		assert($slice === Base\Arr::valuesSlice(array('1',2,array(2),'test'),$slice));
		assert(Base\Arr::valuesSlice(array(1),$slice) === array());
		$slice = array('É','testé','TEST');
		assert(Base\Arr::valuesSlice(array("é",'test'),$slice,false) === array('É',2=>'TEST'));

		// valueStrip
		$slice = array('1',2,array(2),'test');
		assert(array(1=>2,2=>array(2),3=>'test') === Base\Arr::valueStrip('1',$slice));
		$slice = array('É','testé','TEST');
		assert(Base\Arr::valueStrip("é",$slice,false) === array(1=>'testé',2=>'TEST'));

		// valuesStrip
		$slice = array('1',2,array(2),'test');
		assert(array(1=>2,2=>array(2),3=>'test') === Base\Arr::valuesStrip(array('1'),$slice));
		assert(array(2=>array(2)) === Base\Arr::valuesStrip(array('1',2,2,'test'),$slice));
		assert(Base\Arr::valuesStrip(array(1),$slice) === $slice);
		$slice = array('É','testé','TEST');
		assert(Base\Arr::valuesStrip(array("é"),$slice,false) === array(1=>'testé',2=>'TEST'));

		// valueNav
		assert(Base\Arr::valueNav(2,-1,array(0,1,2,3,4,5)) === 1);
		assert(Base\Arr::valueNav(2,2,array(0,1,2,3,6,5)) === 6);
		assert(Base\Arr::valueNav(2,1,array(0,1,2,3,2,5)) === 3);
		assert(Base\Arr::valueNav(2,1112,array(0,1,2,3,6,5)) === null);
		assert(Base\Arr::valueNav(1112,1,array(0,1,2,3,6,5)) === null);

		// valueRandom
		assert(1 === strlen((string) Base\Arr::valueRandom(array(1,2,3))));
		assert(null === (Base\Arr::valueRandom(array())));

		// valuesChange
		$array = array(true,array(2,array(true)));
		assert(array(false,array(2,array(true))) === Base\Arr::valuesChange(true,false,$array));
		$array = array(true,true,array(2,array(true)));
		assert(array(false,false,array(2,array(true))) === Base\Arr::valuesChange(true,false,$array));
		assert(array(false,true,array(2,array(true))) === Base\Arr::valuesChange(true,false,$array,1));
		assert(array(false,false,array(2,array(true))) === Base\Arr::valuesChange(true,false,$array,2));

		// valuesReplace
		assert(array("test"=>"lapa","lapa2") === Base\Arr::valuesReplace(array("test"=>"lapa"),array("test"=>"test","test2")));
		assert(array("test"=>"lapa","lapa2",'2') === Base\Arr::valuesReplace(array("test"=>"lapa"),array("test"=>"test","test2",'2')));
		assert(array("test"=>array('test'),"lapa2",'2') === Base\Arr::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"test2",'2')));
		assert(array("test"=>array('test'),"lapa2",'2') === Base\Arr::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"TEST2",'2'),false));
		assert(array("test"=>array('test'),"TEST2",'2') === Base\Arr::valuesReplace(array("test"=>"lapa"),array("test"=>array('test'),"TEST2",'2'),true));

		// valuesSearch
		$array = array('test','okÉÉÉ','TEST','james',3);
		assert(Base\Arr::valuesSearch('test',$array,true) === array('test'));
		assert(Base\Arr::valuesSearch('test',$array,false) === array('test',2=>'TEST'));
		assert(Base\Arr::valuesSearch('okééé',$array,false) === array(1=>'okÉÉÉ'));
		assert(Base\Arr::valuesSearch('3',$array,false) === array(4=>3));
		assert(Base\Arr::valuesSearch('what',$array,false) === array());
		assert(Base\Arr::valuesSearch('ja + me',$array,false) === array());
		assert(Base\Arr::valuesSearch('ja + me',$array,false,true,true,'+') === array(3=>'james'));
		assert(Base\Arr::valuesSearch('Ja + me',$array,false,true,true,'+') === array(3=>'james'));
		assert(Base\Arr::valuesSearch('Ja + me',$array,true,true,true,'+') === array());
		assert(Base\Arr::valuesSearch('ja + me',$array,false,true,true) === array());
		assert(Base\Arr::valuesSearch('ja me',$array,false,true,true) === array(3=>'james'));

		// valuesSubReplace
		$array = array('string','string2','mon monde');
		assert(Base\Arr::valuesSubReplace(0,2,"hahah",$array) === array('hahahring','hahahring2','hahahn monde'));
		$array = array('string','string2','mon monde',array());
		assert(Base\Arr::valuesSubReplace(0,2,"hahah",$array) === array('hahahring','hahahring2','hahahn monde',array()));

		// valuesStart
		assert(Base\Arr::valuesStart('/bla',array('/bla2','/bla.jpg','asd')) === array('/bla2','/bla.jpg'));
		assert(Base\Arr::valuesStart('/zz',array('/bla2','/bla.jpg','asd')) === array());

		// valuesEnd
		assert(Base\Arr::valuesEnd('sd',array('/bla2','/bla.jpgsd','asd')) === array(1=>'/bla.jpgsd',2=>'asd'));
		assert(Base\Arr::valuesEnd('zzz',array('/bla2','/bla.jpgsd','asd')) === array());

		// valuesChangeCase
		assert(Base\Arr::valuesChangeCase('lower',array(1,'TEST','test',array('TEST'))) === array(1,'test','test',array('test')));
		assert(Base\Arr::valuesChangeCase('strtoupper',array(1,'TEST','test',array('test'))) === array(1,'TEST','TEST',array('TEST')));

		// valuesLower
		assert(Base\Arr::valuesLower(array(1,'TÉST','test',array('TEST'))) === array(1,'tést','test',array('test')));

		// valuesUpper
		assert(Base\Arr::valuesUpper(array(1,'TEST','tést',array('test'))) === array(1,'TEST','TÉST',array('TEST')));

		// valuesSliceLength
		assert(Base\Arr::valuesSliceLength(1,3,array('a','blbbb',array(),'OK')) === array('a',3=>'OK'));

		// valuesStripLength
		assert(Base\Arr::valuesStripLength(1,3,array('a','blbbb',array(),'OK')) === array(1=>'blbbb'));

		// valuesTotalLength
		assert(Base\Arr::valuesTotalLength(1,array('ab','blbbb',array(),'OK')) === array('a'));
		assert(Base\Arr::valuesTotalLength(3,array('ab','blbbb',array(),'OK')) === array('ab'));
		assert(Base\Arr::valuesTotalLength(8,array('ab','blbbb',array(),'OK')) === array('ab','blbbb'));

		// valuesWrap
		assert(Base\Arr::valuesWrap(":",";",array('test','ok',array('james'),true,2)) === array(':test;',':ok;',array('james'),":1;",":2;"));
		assert(Base\Arr::valuesWrap(':',null,array('james',':james'),4) === array(':james',':james'));

		// valuesUnwrap
		assert(Base\Arr::valuesUnwrap(":",";",array(':test;',':ok;',array('james'),true,2)) === array('test','ok',array('james'),true,2));
		assert(Base\Arr::valuesUnwrap(':',null,array(':james:','james:'),2) === array('james:','james:'));

		// valuesSort
		$sort = array("z"=>"test","b"=>"atest","c"=>"z",4,true,array());
		assert(Base\Arr::valuesSort($sort,true) === array(true,4,'atest','test','z'));
		assert(Base\Arr::valuesSort($sort,false) === array('z','test','atest',4,true));

		// valuesSortKeepAssoc
		$sort = array("z"=>"test","b"=>"atest","c"=>"z",4,true,array());
		assert(Base\Arr::valuesSortKeepAssoc($sort,true) === array(1=>true,0=>4,'b'=>'atest','z'=>'test','c'=>'z'));
		assert(Base\Arr::valuesSortKeepAssoc($sort,false) === array('c'=>'z','z'=>'test','b'=>'atest',0=>4,1=>true));

		// valuesExcerpt
		assert(Base\Arr::valuesExcerpt(5,array('ok',9=>'sdasdadasdas',3,true,false,null)) === array('ok',9=>'sd...',10=>'3'));
		assert(Base\Arr::valuesExcerpt(5,array('ok',9=>"L'hébergemnt",3,true,false,null))[9] === "L'...");

		// keysValuesLower
		assert(Base\Arr::keysValuesLower(array('A'=>'É')) === array('a'=>'é'));

		// keysValuesUpper
		assert(Base\Arr::keysValuesUpper(array('a'=>'é')) === array('A'=>'É'));

		// keysStrToArrs
		assert(Base\Arr::keysStrToArrs(array('str'=>'test',array('ok'),'james'=>array(23,32),'ok'=>false))[0] === array('str','test'));
		assert(Base\Arr::keysStrToArrs(array('str'=>'test',array('ok'),'james'=>array(23,32),'ok'=>false))[3] === array('ok',false));

		// camelCaseParent
		$array = array('test','testJames','testJamesOk','testJamesLaVie');
		assert(Base\Arr::camelCaseParent($array) === array('test'=>null,'testJames'=>'test','testJamesOk'=>'testJames','testJamesLaVie'=>'testJames'));
		assert(Base\Arr::camelCaseParent(array_reverse($array)) === array('testJamesLaVie'=>'testJames','testJamesOk'=>'testJames','testJames'=>'test','test'=>null));
		$array = array('test','testJames','testJames','LalaOk','ok','testJames2','testJamesLavie_ok');
		assert(Base\Arr::camelCaseParent($array) === array('test'=>null,'testJames'=>'test','LalaOk'=>null,'ok'=>null,'testJames2'=>'test','testJamesLavie_ok'=>'testJames'));
		
		// combination
		assert(count(Base\Arr::combination(array(3,2,6))) === 7);

		// methodSort
		// voir obj et classe sort pour test

		// methodSorts
		// voir obj et classe sorts pour test
		
		return true;
	}
}
?>