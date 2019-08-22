<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// column
class Column extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		$array = array(1,2,3,array());
		assert(!Base\Column::is($array));
		assert(Base\Column::is(array(array(1),array(2))));
		assert(Base\Column::is(array(array(1),array(2,3))));
		$array = array(1,2,3);
		assert(!Base\Column::is($array));
		assert(!Base\Column::is(true));
		assert(Base\Column::is(array(array())));
		assert(Base\Column::is(array(array()),array(array())));
		assert(!Base\Column::is(array(1,array(),array(2))));

		// isEmpty
		assert(Base\Column::isEmpty(array(array())));
		assert(Base\Column::isEmpty(array(array(),array())));
		assert(!Base\Column::isEmpty(array(array(),array(2))));
		assert(!Base\Column::isEmpty(array(array(1))));
		assert(!Base\Column::isEmpty(array(2)));

		// isNotEmpty
		$array = array(array(1,3),array(1,2,3));
		assert(Base\Column::isNotEmpty($array));
		assert(!Base\Column::isNotEmpty(array(array())));
		assert(Base\Column::isNotEmpty(array(array(1),array('test'))));
		assert(!Base\Column::isNotEmpty(array(array(1),array())));

		// isIndexed
		$array = array(array(1,2,3),array(4,7=>5,6));
		assert(Base\Column::isIndexed($array));
		$array = array(array(1,2,3),array('test'=>4,5,6));
		assert(!Base\Column::isIndexed($array));
		assert(Base\Column::isIndexed(array(array(1=>2),array(1=>2))));

		// isSequential
		$array = array(array(1,2,3),array(4,5,6));
		assert(Base\Column::isSequential($array));
		$array = array(array(1,2,3),array('test'=>4,5,6));
		assert(!Base\Column::isSequential($array));
		assert(!Base\Column::isSequential(array(array(1=>2),array(1=>2))));

		// isAssoc
		$array = array(array(1,8=>2,3),array(4,7=>5,6));
		assert(!Base\Column::isAssoc($array));
		$array = array(array('testa'=>1,2,3),array('test'=>4,5,6));
		assert(Base\Column::isAssoc($array));
		assert(!Base\Column::isAssoc(array(array(1=>2),array(1=>2))));

		// isUni
		$array = array(array(1,8=>2,3),array(4,7=>5,6));
		assert(Base\Column::isUni($array));
		$array = array(array('testa'=>1,2,3),array('test'=>4,5,6));
		assert(Base\Column::isUni($array));
		assert(Base\Column::isUni(array(array(1=>2),array(1=>2))));
		assert(Base\Column::isUni(array(array(),array())));
		assert(!Base\Column::isUni(array(array(1=>array(2)),array(1=>3))));

		// same
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::same($multi));
		$multi[2] = false;
		assert(!Base\Column::same($multi));
		$multi[2] = array("name"=>"test2","user"=>"testa2",'ok'=>2);
		assert(!Base\Column::same($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),4=>array("name"=>"test2","user"=>"testa2"),'test');
		assert(!Base\Column::same($multi));

		// sameCount
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::sameCount($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa",'what'),'x'=>array("name"=>"test2","user"=>"testa2"));
		assert(!Base\Column::sameCount($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2);
		assert(!Base\Column::sameCount($multi));
		assert(!Base\Column::sameCount('string'));
		assert(Base\Column::sameCount(array(array(1,2,3))));

		// sameKey
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::sameKey($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("namez"=>"test2","user"=>"testa2"));
		assert(!Base\Column::sameKey($multi));
		$multi[1]['name'] = 'test2';
		$multi['x'] = array("name"=>"test2","user"=>"testa2",2);
		assert(Base\Column::sameKey($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),'test');
		assert(!Base\Column::sameKey($multi));

		// merge
		$array = array(array('test'=>2),array('test'=>3,'james'=>'ok','what'=>array('la'=>'ok','bla'=>'bzz')));
		assert(Base\Column::merge($array,array('test'=>4,'james'=>'ok'),true)[0]['test'][1] === 4);
		assert(Base\Column::merge($array,array('test'=>4,'james'=>'ok'),false)[0]['test'] === 4);
		assert(Base\Column::merge($array,array('test'=>4,'james'=>'ok','what'=>array('la'=>'no')))[1]['what']['la'][1] === 'no');
		assert(Base\Column::merge($array,array('test'=>4,'james'=>'ok','what'=>array('la'=>'no')),false)[1]['what']['la'] === 'no');

		// replace
		assert(Base\Column::replace($array,array('test'=>4,'james'=>'ok','what'=>array('la'=>'no')),false)[1]['what'] === array('la'=>'no'));
		assert(Base\Column::replace($array,array('test'=>4,'james'=>'ok','what'=>array('la'=>'no')))[1]['what'] === array('la'=>'no','bla'=>'bzz'));

		// clean
		$array = array(array(1,2),array(3,4),array(5),array(6,7),array(8,1,2));
		assert(Base\Column::clean($array) === array(array(1,2),array(3,4),3=>array(6,7)));

		// count
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::count($multi) === array(2,3));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),true,array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::count($multi) === array(2,2=>3));

		// countSame
		$multi = array(0=>array("name"=>"test","user"=>"testa",'bla'=>2),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(3 === Base\Column::countSame($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array(1));
		assert(null === Base\Column::countSame($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa",'james'),1=>array("name"=>"test2","user"=>"testa2"));
		assert(null === Base\Column::countSame($multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),false);
		assert(null === Base\Column::countSame($multi));
		$multi = array(0=>array(0,1,2),1=>array(0,1,2),2=>array(1,2,3));
		assert(3 === Base\Column::countSame($multi));
		assert(null === Base\Column::countSame(array(1,2,3)));

		// math
		$calc = array(array('name'=>1),array('name'=>2),array('name'=>3),array('name'=>4));
		assert(10 === Base\Column::math("name",'+',$calc));
		assert(-8 === Base\Column::math("name",'-',$calc));
		assert(24 === Base\Column::math("name",'*',$calc));
		assert(2.5 === Base\Column::math("name",'average',$calc));
		assert(4 === Base\Column::math("name",'>',$calc));

		// validate
		$multi = array(0=>array("name"=>"emozwa@test.lz","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::validate(array('name'=>'email','user'=>'strNotEmpty'),$multi)) === 1);
		$multi = array(0=>array("name"=>2,"user"=>"testa"),1=>array("name"=>4,"user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::validate(array('name'=>array('>'=>3),'user'=>'strNotEmpty'),$multi)) === 1);
		assert(count(Base\Column::validate(array('name'=>array('>'=>5),'user'=>'strNotEmpty'),$multi)) === 0);

		// in
		$multi = array(0=>array("name"=>"emozwa@test.lz","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::in('name','emozwa@test.lz',$multi));
		assert(Base\Column::in('name','Emozwa@test.lz',$multi,false));
		$multi2 = array(0=>array("name"=>"Émozwa@test.lz","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::in('name','émozwa@test.lz',$multi2,false));

		// ins
		assert(Base\Column::ins('name',array('emozwa@test.lz'),$multi));
		assert(Base\Column::ins('name',array('Emozwa@test.lz','teSt2'),$multi,false));
		$multi2 = array(0=>array("name"=>"Émozwa@test.lz","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::ins('name',array('émozwa@test.lz'),$multi2,false));

		// inFirst
		assert(Base\Column::inFirst('name',array('Emozwa@test.lz','teSt2'),$multi,false) === 'emozwa@test.lz');
		assert(Base\Column::inFirst('name',array('Emozwa@test.lz','test2'),$multi) === 'test2');
		$multi2 = array(0=>array("name"=>"Émozwa@test.lz","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(Base\Column::inFirst('name',array('émozwa@test.lz'),$multi2,false) === 'émozwa@test.lz');

		// search
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::search(array('name'=>'test'),$multi)) === 1);
		assert(count(Base\Column::search(array('name'=>'test','user'=>'testa'),$multi)) === 1);
		assert(count(Base\Column::search(array('name'=>'test','user'=>'testaz'),$multi)) === 0);
		assert(count(Base\Column::search(array('name'=>'test','userz'=>'testa'),$multi)) === 0);
		$multi = array(0=>array("name"=>"TÉST","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::search(array('name'=>'tést'),$multi)) === 0);
		assert(count(Base\Column::search(array('name'=>'tést'),$multi,false)) === 1);

		// searchFirst
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::searchFirst(array('name'=>'test2'),$multi)) === 3);
		assert(Base\Column::searchFirst(array('namez'=>'test2'),$multi) === array());
		$multi = array(0=>array("name"=>"TÉST","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>"a"));
		assert(count(Base\Column::searchFirst(array('name'=>'tést'),$multi,false)) === 2);

		// unique
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array('name'=>'test','user'=>'testa'),'bla'=>array("name"=>"test2","user"=>"testa",'bla'=>"a"));
		assert(count(Base\Column::unique(array('name','user'),$multi)) === 2);
		assert(count(Base\Column::unique(array('name'),$multi)) === 2);
		assert(count(Base\Column::unique(array('name'),$multi,true)) === 1);
		assert(count(Base\Column::unique(array('user'),$multi)) === 1);
		$multi = array(0=>array("name"=>"test","user"=>"TESTA"),1=>array('name'=>'test','user'=>'testa'),'bla'=>array("name"=>"test2","user"=>"testa",'bla'=>"a"));
		assert(count(Base\Column::unique(array('user'),$multi,false,false)) === 1);

		// duplicate
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array('name'=>'test','user'=>'testa'),'bla'=>array("name"=>"test2","user"=>"testa",'bla'=>"a"));
		assert(count(Base\Column::duplicate(array('name','user'),$multi)) === 1);
		assert(count(Base\Column::duplicate(array('name'),$multi)) === 1);
		assert(count(Base\Column::duplicate(array('user'),$multi)) === 2);
		assert(count(Base\Column::duplicate(array('name'),$multi,true)) === 2);
		$multi = array(0=>array("name"=>"test","user"=>"TÉSTA"),1=>array('name'=>'test','user'=>'tésta'),'bla'=>array("name"=>"test2","user"=>"testa",'bla'=>"a"));
		assert(count(Base\Column::duplicate(array('user'),$multi,false,false)) === 1);

		// sort
		$sort = array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'b'));
		assert(array('test2'=>array('name'=>'b'),'test4'=>array('name'=>'z')) === Base\Column::sort('name','asc',$sort));
		$sort = array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'z'),'test3'=>array('name'=>'z'));
		assert(array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'z'),'test3'=>array('name'=>'z')) === Base\Column::sort('name','ASC',$sort));
		$sort = array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'b'));
		assert(array('test2'=>array('name'=>'b'),'test4'=>array('name'=>'z')) === Base\Column::sort('name',true,$sort));
		$sort = array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'z'),'test3'=>array('name'=>'z'));
		assert(array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'z'),'test3'=>array('name'=>'z')) === Base\Column::sort('name','asc',$sort));
		$sort = array('test4'=>array('name'=>'z'),'test3'=>array('name'=>'a'),'test2'=>array('name'=>'z'));
		assert(array('test4'=>array('name'=>'z'),'test2'=>array('name'=>'z'),'test3'=>array('name'=>'a')) === Base\Column::sort('name','desc',$sort));

		// sorts
		$sort = array('test4'=>array('name'=>'z','id'=>2),'test2'=>array('name'=>'w','id'=>5),'test3'=>array('name'=>'a','id'=>2));
		$sort = Base\Column::sorts(array('id'=>'asc','name'=>'asc'),$sort);
		assert(array('test3'=>array('name'=>'a','id'=>2),'test4'=>array('name'=>'z','id'=>2),'test2'=>array('name'=>'w','id'=>5)) === $sort);
		$sort = array('test4'=>array('name'=>'z','id'=>2),'test2'=>array('name'=>'w','id'=>5),'test3'=>array('name'=>'a','id'=>2));
		$sort = Base\Column::sorts(array('id'=>'desc','name'=>'asc'),$sort);
		assert(array('test2'=>array('name'=>'w','id'=>5),'test3'=>array('name'=>'a','id'=>2),'test4'=>array('name'=>'z','id'=>2)) === $sort);
		$sort = array('test4'=>array('name'=>'z','id'=>2),'test2'=>array('name'=>'w','id'=>5),'test3'=>array('name'=>'a','id'=>2));
		$sort = Base\Column::sorts(array('id'=>'desc','name'=>'desc'),$sort);
		assert(array('test2'=>array('name'=>'w','id'=>5),'test4'=>array('name'=>'z','id'=>2),'test3'=>array('name'=>'a','id'=>2)) === $sort);
		$sort = array('test4'=>array('name'=>'z','id'=>2,'test'=>1),'test2'=>array('name'=>'w','id'=>5,'test'=>1),'test3'=>array('name'=>'a','id'=>2,'test'=>0));
		$sort = Base\Column::sorts(array('test'=>'desc','id'=>'desc','name'=>'asc'),$sort);
		assert(array('test2'=>array('name'=>'w','id'=>5,'test'=>1),'test4'=>array('name'=>'z','id'=>2,'test'=>1),'test3'=>array('name'=>'a','id'=>2,'test'=>0)) === $sort);

		// sortByLength
		$sort = array(array('test','ok',3),array('ok'),array('james',3));
		assert(Base\Column::sortByLength($sort,true) === Base\Column::sortByLength($sort));
		assert(Base\Column::sortByLength($sort,false)[1] = array('james',3));

		// map
		$col = array(array(1=>'yes'),array(1=>'no','test'=>'ok'));
		assert(array(array(1=>"YES"),array(1=>"NO",'test'=>'ok')) === Base\Column::map(1,"strtoupper",$col));
		assert(array(array(1=>"yes"),array(1=>"no",'test'=>'OK')) === Base\Column::map('test',"strtoupper",$col));
		assert(array(array(1=>"YES"),array(1=>"NO",'test'=>'ok')) === Base\Column::map(1,array(Base\Str::class,'upper'),$col));
		Base\Column::map(1,function($v,$k,$value) {
			assert(is_string($v));
			assert(is_int($k));
			assert(is_array($value));
		},$col);

		// filter
		$col = array(array('test'=>'yes',2=>'TESa'),array('test'=>'no',2=>'TEST'),array('testa'=>'bla'));
		$col = Base\Column::filter('test',function($v,$k,$value) {
			if($v === 'yes' && is_array($value))
			return true;
		},$col);
		assert(count($col) === 1);
		$col = array(array('test'=>'yes',2=>'TESa'),array('test'=>'no',2=>'TEST'),array('testa'=>'bla'));

		// arr
		$multi = array(0=>array("name"=>"test","user"=>"testa"),3=>array("name"=>"test2","user"=>"testa2"),2=>array("user"=>array("testa3"),'meh'=>'test','bla'=>'2'));
		assert(array('testa','testa2',array('testa3')) === Base\Column::keyValue(null,"user",$multi));
		assert(array() === Base\Column::keyValue(array(),"user",$multi));
		assert(array('2') === Base\Column::keyValue("user","bla",$multi));
		assert(array('test'=>'2') === Base\Column::keyValue("meh","bla",$multi));
		assert(array() === Base\Column::keyValue(null,"namez",$multi));
		assert(array() === Base\Column::keyValue(array(),array(),$multi));
		assert(array() === Base\Column::keyValue(null,null,$multi));
		assert(array() === Base\Column::keyValue(null,0,array(1,2,3)));

		// arrIndex
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("user"=>"testa3","zz"=>true));
		assert(array("test"=>"testa","test2"=>"testa2","testa3"=>true) === Base\Column::keyValueIndex(0,1,$multi));
		assert(array("testa"=>"testa","testa2"=>"testa2",1=>true) === Base\Column::keyValueIndex(1,1,$multi)); // note, changement dans array_column php 7.2.9
		assert(array("test"=>"testa","test2"=>"testa2","testa3"=>true) === Base\Column::keyValueIndex(0,-1,$multi));
		assert(array("test"=>"testa","test2"=>"testa2","testa3"=>true) === Base\Column::keyValueIndex(0,1,$multi));
		assert(array("test"=>"test","test2"=>"test2","testa3"=>"testa3") === Base\Column::keyValueIndex(0,0,$multi));
		assert(array("test","test2","testa3") === Base\Column::keyValueIndex(null,0,$multi));

		// arrSegment
		assert(array('testa'=>"namez",'testa2'=>"namez",'testa3'=>"namez") === Base\Column::arrSegment("user","namez",$multi));
		assert(array() === Base\Column::arrSegment("userz","name",$multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>"test","user"=>"testa2"));
		assert(array("testa"=>"test","testa2"=>"test") === Base\Column::arrSegment("user","name",$multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>array("test"),"user"=>"testa3"));
		assert(array("testa"=>"test","testa2"=>"test2","testa3"=>array("test")) === Base\Column::arrSegment("user","name",$multi));
		assert(array() === Base\Column::arrSegment(null,"name",$multi));
		assert(array("testa"=>'test - testa','testa2'=>'test2 - testa2','testa3'=>'[name] - testa3') === Base\Column::arrSegment("user","[name] - [user]",$multi));

		// splice
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>"test","user"=>"testa2"));
		assert(Base\Column::splice("name","user",$multi)[0] === array());
		assert(Base\Column::splice("name",true,$multi)[0] === array('user'=>'testa'));
		assert(Base\Column::splice("namez",true,$multi) === $multi);
		$multi = array(0=>array("namÉ"=>"test","user"=>"testa"),1=>array("namÉ"=>"test2","user"=>"testa2"),2=>array("namÉ"=>"test","user"=>"testa2"));
		assert(count(Base\Column::splice("NAMé",true,$multi,null,true)[0]) === 2);
		assert(count(Base\Column::splice("NAMé",true,$multi,null,false)[0]) === 1);
		assert(count(Base\Column::splice("NAMé",true,$multi,array('USERz'=>'LOL'),false)[0]) === 2);
		assert(count(Base\Column::splice("NAMé",true,$multi,array('USER'=>'LOL'),false)[0]) === 1);

		// spliceIndex
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>"test","user"=>"testa2"));
		assert(Base\Column::spliceIndex(0,2,$multi)[0] === array());
		assert(Base\Column::spliceIndex(0,null,$multi)[0] === array('user'=>'testa'));
		assert(Base\Column::spliceIndex(-1,null,$multi,array('date'=>1234))[0] === array('name'=>'test','date'=>1234));

		// spliceFirst
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>"test","user"=>"testa2"));
		assert(Base\Column::spliceFirst($multi)[0] === array('user'=>'testa'));

		// spliceLast
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"),2=>array("name"=>"test","user"=>"testa2"));
		assert(Base\Column::spliceLast($multi)[0] === array("name"=>"test"));

		// insert
		assert(Base\Column::insert("name",array('date'=>1234),$multi)[0] === array('date'=>1234,'name'=>'test','user'=>'testa'));
		assert(Base\Column::insert("namez",array('replace'=>true),$multi) === $multi);

		// insertIndex
		assert(Base\Column::insertIndex(0,array('date'=>1234),$multi)[0] === array('date'=>1234,'name'=>'test','user'=>'testa'));
		assert(Base\Column::insertIndex(2000,array('replace'=>true),$multi)[0] === array('name'=>'test','user'=>'testa','replace'=>true));
		assert(Base\Column::insertIndex(0,array('date'=>1234),$multi)[0] === array('date'=>1234,'name'=>'test','user'=>'testa'));
		assert(Base\Column::insertIndex(-1,array('date'=>1234),$multi)[0] === array('name'=>'test','date'=>1234,'user'=>'testa'));

		// keyExists
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2",'bla'=>'test'));
		assert(Base\Column::keyExists('name',$multi));
		assert(!Base\Column::keyExists('bla',$multi));

		// keysExists
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::keysExists(array("name","user"),$multi));
		assert(!Base\Column::keysExists(array("name","userz"),$multi));

		// keysAre
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::keysAre(array("name","user"),$multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("namez"=>"test2","user"=>"testa2"));
		assert(!Base\Column::keysAre(array("name","user"),$multi));
		assert(!Base\Column::keysAre(array("name"),$multi));

		// keyTo
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(array(0=>array("name"=>"test","user"=>"testa","key"=>0),1=>array("name"=>"test2","user"=>"testa2","key"=>1)) === Base\Column::keyTo("key",$multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>true);
		assert(array(0=>array("name"=>"test","user"=>"testa","key"=>0),1=>true) === Base\Column::keyTo("key",$multi));

		// keyFrom
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>"testa2"));
		assert(array("testa"=>array("name"=>"test","user"=>"testa"),"testa2"=>array("name"=>"test2","user"=>"testa2")) === Base\Column::keyFrom("user",$multi));
		$multi = array(0=>array("name"=>"test","user"=>"testa"),1=>array("name"=>"test2","user"=>array()));
		assert(array("testa"=>array("name"=>"test","user"=>"testa")) === Base\Column::keyFrom("user",$multi));

		// keyFromIndex
		$multi = array(2=>array("name"=>"test","user"=>"testa"),3=>array("name"=>"test2","user"=>"testa2"));
		assert(Base\Column::keyFromIndex(1,$multi) === array("testa"=>array("name"=>"test","user"=>"testa"),"testa2"=>array("name"=>"test2","user"=>"testa2")));

		// keySwap
		$multi = array('name'=>array('well','test'),'error'=>array('james','none'));
		assert(Base\Column::keySwap($multi) === array(array('name'=>'well','error'=>'james'),array('name'=>'test','error'=>'none')));

		// value
		$multi = array(0=>array("name"=>"test","user"=>"testa"),3=>array("name"=>"test2","user"=>"testa2"),2=>array("user"=>array("testa3"),'meh'=>'test','bla'=>'2'));
		assert(array('testa','testa2',array('testa3')) === Base\Column::value("user",$multi));

		// valueIndex
		$multi = array(0=>array("name"=>"test","user"=>"testa"),3=>array("name"=>"test2","user"=>"testa2"),2=>array("user"=>array("testa3"),'meh'=>'test'));
		assert(array('test','test2',array('testa3')) === Base\Column::valueIndex(0,$multi));
		assert(array('testa','testa2',"test") === Base\Column::valueIndex(-1,$multi));

		// valueSegment
		$multi = array(0=>array("name"=>"test","user"=>"testa"),3=>array("name"=>"test2","user"=>"testa2"),2=>array("user"=>array("testa3"),'meh'=>'test','bla'=>'2'));
		assert(Base\Column::valueSegment("[name] :) [user]",$multi)[0] === 'test :) testa');
		assert(Base\Column::valueSegment("[name] [user]",$multi)[2] === "[name] [user]");
		assert(Base\Column::valueSegment("user",$multi)[0] === "testa");
		
		return true;
	}
}
?>