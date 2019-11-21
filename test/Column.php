<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Test\Base;
use Quid\Base;

// column
// class for testing Quid\Base\Column
class Column extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        $array = [1,2,3,[]];
        assert(!Base\Column::is($array));
        assert(Base\Column::is([[1],[2]]));
        assert(Base\Column::is([[1],[2,3]]));
        $array = [1,2,3];
        assert(!Base\Column::is($array));
        assert(!Base\Column::is(true));
        assert(Base\Column::is([[]]));
        assert(Base\Column::is([[]],[[]]));
        assert(!Base\Column::is([1,[],[2]]));

        // isEmpty
        assert(Base\Column::isEmpty([[]]));
        assert(Base\Column::isEmpty([[],[]]));
        assert(!Base\Column::isEmpty([[],[2]]));
        assert(!Base\Column::isEmpty([[1]]));
        assert(!Base\Column::isEmpty([2]));

        // isNotEmpty
        $array = [[1,3],[1,2,3]];
        assert(Base\Column::isNotEmpty($array));
        assert(!Base\Column::isNotEmpty([[]]));
        assert(Base\Column::isNotEmpty([[1],['test']]));
        assert(!Base\Column::isNotEmpty([[1],[]]));

        // isIndexed
        $array = [[1,2,3],[4,7=>5,6]];
        assert(Base\Column::isIndexed($array));
        $array = [[1,2,3],['test'=>4,5,6]];
        assert(!Base\Column::isIndexed($array));
        assert(Base\Column::isIndexed([[1=>2],[1=>2]]));

        // isSequential
        $array = [[1,2,3],[4,5,6]];
        assert(Base\Column::isSequential($array));
        $array = [[1,2,3],['test'=>4,5,6]];
        assert(!Base\Column::isSequential($array));
        assert(!Base\Column::isSequential([[1=>2],[1=>2]]));

        // isAssoc
        $array = [[1,8=>2,3],[4,7=>5,6]];
        assert(!Base\Column::isAssoc($array));
        $array = [['testa'=>1,2,3],['test'=>4,5,6]];
        assert(Base\Column::isAssoc($array));
        assert(!Base\Column::isAssoc([[1=>2],[1=>2]]));

        // isUni
        $array = [[1,8=>2,3],[4,7=>5,6]];
        assert(Base\Column::isUni($array));
        $array = [['testa'=>1,2,3],['test'=>4,5,6]];
        assert(Base\Column::isUni($array));
        assert(Base\Column::isUni([[1=>2],[1=>2]]));
        assert(Base\Column::isUni([[],[]]));
        assert(!Base\Column::isUni([[1=>[2]],[1=>3]]));

        // same
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::same($multi));
        $multi[2] = false;
        assert(!Base\Column::same($multi));
        $multi[2] = ['name'=>'test2','user'=>'testa2','ok'=>2];
        assert(!Base\Column::same($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],4=>['name'=>'test2','user'=>'testa2'],'test'];
        assert(!Base\Column::same($multi));

        // sameCount
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::sameCount($multi));
        $multi = [0=>['name'=>'test','user'=>'testa','what'],'x'=>['name'=>'test2','user'=>'testa2']];
        assert(!Base\Column::sameCount($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2];
        assert(!Base\Column::sameCount($multi));
        assert(!Base\Column::sameCount('string'));
        assert(Base\Column::sameCount([[1,2,3]]));

        // sameKey
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::sameKey($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['namez'=>'test2','user'=>'testa2']];
        assert(!Base\Column::sameKey($multi));
        $multi[1]['name'] = 'test2';
        $multi['x'] = ['name'=>'test2','user'=>'testa2',2];
        assert(Base\Column::sameKey($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],'test'];
        assert(!Base\Column::sameKey($multi));

        // merge
        $array = [['test'=>2],['test'=>3,'james'=>'ok','what'=>['la'=>'ok','bla'=>'bzz']]];
        assert(Base\Column::merge($array,['test'=>4,'james'=>'ok'],true)[0]['test'][1] === 4);
        assert(Base\Column::merge($array,['test'=>4,'james'=>'ok'],false)[0]['test'] === 4);
        assert(Base\Column::merge($array,['test'=>4,'james'=>'ok','what'=>['la'=>'no']])[1]['what']['la'][1] === 'no');
        assert(Base\Column::merge($array,['test'=>4,'james'=>'ok','what'=>['la'=>'no']],false)[1]['what']['la'] === 'no');

        // replace
        assert(Base\Column::replace($array,['test'=>4,'james'=>'ok','what'=>['la'=>'no']],false)[1]['what'] === ['la'=>'no']);
        assert(Base\Column::replace($array,['test'=>4,'james'=>'ok','what'=>['la'=>'no']])[1]['what'] === ['la'=>'no','bla'=>'bzz']);

        // clean
        $array = [[1,2],[3,4],[5],[6,7],[8,1,2]];
        assert(Base\Column::clean($array) === [[1,2],[3,4],3=>[6,7]]);

        // count
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::count($multi) === [2,3]);
        $multi = [0=>['name'=>'test','user'=>'testa'],true,['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::count($multi) === [2,2=>3]);

        // countSame
        $multi = [0=>['name'=>'test','user'=>'testa','bla'=>2],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(3 === Base\Column::countSame($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>[1]];
        assert(null === Base\Column::countSame($multi));
        $multi = [0=>['name'=>'test','user'=>'testa','james'],1=>['name'=>'test2','user'=>'testa2']];
        assert(null === Base\Column::countSame($multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],false];
        assert(null === Base\Column::countSame($multi));
        $multi = [0=>[0,1,2],1=>[0,1,2],2=>[1,2,3]];
        assert(3 === Base\Column::countSame($multi));
        assert(null === Base\Column::countSame([1,2,3]));

        // math
        $calc = [['name'=>1],['name'=>2],['name'=>3],['name'=>4]];
        assert(10 === Base\Column::math('name','+',$calc));
        assert(-8 === Base\Column::math('name','-',$calc));
        assert(24 === Base\Column::math('name','*',$calc));
        assert(2.5 === Base\Column::math('name','average',$calc));
        assert(4 === Base\Column::math('name','>',$calc));

        // validate
        $multi = [0=>['name'=>'emozwa@test.lz','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::validate(['name'=>'email','user'=>'strNotEmpty'],$multi)) === 1);
        $multi = [0=>['name'=>2,'user'=>'testa'],1=>['name'=>4,'user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::validate(['name'=>['>'=>3],'user'=>'strNotEmpty'],$multi)) === 1);
        assert(count(Base\Column::validate(['name'=>['>'=>5],'user'=>'strNotEmpty'],$multi)) === 0);

        // in
        $multi = [0=>['name'=>'emozwa@test.lz','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::in('name','emozwa@test.lz',$multi));
        assert(Base\Column::in('name','Emozwa@test.lz',$multi,false));
        $multi2 = [0=>['name'=>'Émozwa@test.lz','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::in('name','émozwa@test.lz',$multi2,false));

        // ins
        assert(Base\Column::ins('name',['emozwa@test.lz'],$multi));
        assert(Base\Column::ins('name',['Emozwa@test.lz','teSt2'],$multi,false));
        $multi2 = [0=>['name'=>'Émozwa@test.lz','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::ins('name',['émozwa@test.lz'],$multi2,false));

        // inFirst
        assert(Base\Column::inFirst('name',['Emozwa@test.lz','teSt2'],$multi,false) === 'emozwa@test.lz');
        assert(Base\Column::inFirst('name',['Emozwa@test.lz','test2'],$multi) === 'test2');
        $multi2 = [0=>['name'=>'Émozwa@test.lz','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(Base\Column::inFirst('name',['émozwa@test.lz'],$multi2,false) === 'émozwa@test.lz');

        // search
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::search(['name'=>'test'],$multi)) === 1);
        assert(count(Base\Column::search(['name'=>'test','user'=>'testa'],$multi)) === 1);
        assert(count(Base\Column::search(['name'=>'test','user'=>'testaz'],$multi)) === 0);
        assert(count(Base\Column::search(['name'=>'test','userz'=>'testa'],$multi)) === 0);
        $multi = [0=>['name'=>'TÉST','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::search(['name'=>'tést'],$multi)) === 0);
        assert(count(Base\Column::search(['name'=>'tést'],$multi,false)) === 1);

        // searchFirst
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::searchFirst(['name'=>'test2'],$multi)) === 3);
        assert(Base\Column::searchFirst(['namez'=>'test2'],$multi) === []);
        $multi = [0=>['name'=>'TÉST','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'a']];
        assert(count(Base\Column::searchFirst(['name'=>'tést'],$multi,false)) === 2);

        // unique
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test','user'=>'testa'],'bla'=>['name'=>'test2','user'=>'testa','bla'=>'a']];
        assert(count(Base\Column::unique(['name','user'],$multi)) === 2);
        assert(count(Base\Column::unique(['name'],$multi)) === 2);
        assert(count(Base\Column::unique(['name'],$multi,true)) === 1);
        assert(count(Base\Column::unique(['user'],$multi)) === 1);
        $multi = [0=>['name'=>'test','user'=>'TESTA'],1=>['name'=>'test','user'=>'testa'],'bla'=>['name'=>'test2','user'=>'testa','bla'=>'a']];
        assert(count(Base\Column::unique(['user'],$multi,false,false)) === 1);

        // duplicate
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test','user'=>'testa'],'bla'=>['name'=>'test2','user'=>'testa','bla'=>'a']];
        assert(count(Base\Column::duplicate(['name','user'],$multi)) === 1);
        assert(count(Base\Column::duplicate(['name'],$multi)) === 1);
        assert(count(Base\Column::duplicate(['user'],$multi)) === 2);
        assert(count(Base\Column::duplicate(['name'],$multi,true)) === 2);
        $multi = [0=>['name'=>'test','user'=>'TÉSTA'],1=>['name'=>'test','user'=>'tésta'],'bla'=>['name'=>'test2','user'=>'testa','bla'=>'a']];
        assert(count(Base\Column::duplicate(['user'],$multi,false,false)) === 1);

        // sort
        $sort = ['test4'=>['name'=>'z'],'test2'=>['name'=>'b']];
        assert(['test2'=>['name'=>'b'],'test4'=>['name'=>'z']] === Base\Column::sort('name','asc',$sort));
        $sort = ['test4'=>['name'=>'z'],'test2'=>['name'=>'z'],'test3'=>['name'=>'z']];
        assert(['test4'=>['name'=>'z'],'test2'=>['name'=>'z'],'test3'=>['name'=>'z']] === Base\Column::sort('name','ASC',$sort));
        $sort = ['test4'=>['name'=>'z'],'test2'=>['name'=>'b']];
        assert(['test2'=>['name'=>'b'],'test4'=>['name'=>'z']] === Base\Column::sort('name',true,$sort));
        $sort = ['test4'=>['name'=>'z'],'test2'=>['name'=>'z'],'test3'=>['name'=>'z']];
        assert(['test4'=>['name'=>'z'],'test2'=>['name'=>'z'],'test3'=>['name'=>'z']] === Base\Column::sort('name','asc',$sort));
        $sort = ['test4'=>['name'=>'z'],'test3'=>['name'=>'a'],'test2'=>['name'=>'z']];
        assert(['test4'=>['name'=>'z'],'test2'=>['name'=>'z'],'test3'=>['name'=>'a']] === Base\Column::sort('name','desc',$sort));

        // sorts
        $sort = ['test4'=>['name'=>'z','id'=>2],'test2'=>['name'=>'w','id'=>5],'test3'=>['name'=>'a','id'=>2]];
        $sort = Base\Column::sorts(['id'=>'asc','name'=>'asc'],$sort);
        assert(['test3'=>['name'=>'a','id'=>2],'test4'=>['name'=>'z','id'=>2],'test2'=>['name'=>'w','id'=>5]] === $sort);
        $sort = ['test4'=>['name'=>'z','id'=>2],'test2'=>['name'=>'w','id'=>5],'test3'=>['name'=>'a','id'=>2]];
        $sort = Base\Column::sorts(['id'=>'desc','name'=>'asc'],$sort);
        assert(['test2'=>['name'=>'w','id'=>5],'test3'=>['name'=>'a','id'=>2],'test4'=>['name'=>'z','id'=>2]] === $sort);
        $sort = ['test4'=>['name'=>'z','id'=>2],'test2'=>['name'=>'w','id'=>5],'test3'=>['name'=>'a','id'=>2]];
        $sort = Base\Column::sorts(['id'=>'desc','name'=>'desc'],$sort);
        assert(['test2'=>['name'=>'w','id'=>5],'test4'=>['name'=>'z','id'=>2],'test3'=>['name'=>'a','id'=>2]] === $sort);
        $sort = ['test4'=>['name'=>'z','id'=>2,'test'=>1],'test2'=>['name'=>'w','id'=>5,'test'=>1],'test3'=>['name'=>'a','id'=>2,'test'=>0]];
        $sort = Base\Column::sorts(['test'=>'desc','id'=>'desc','name'=>'asc'],$sort);
        assert(['test2'=>['name'=>'w','id'=>5,'test'=>1],'test4'=>['name'=>'z','id'=>2,'test'=>1],'test3'=>['name'=>'a','id'=>2,'test'=>0]] === $sort);

        // sortByLength
        $sort = [['test','ok',3],['ok'],['james',3]];
        assert(Base\Column::sortByLength($sort,true) === Base\Column::sortByLength($sort));
        assert(Base\Column::sortByLength($sort,false)[1] = ['james',3]);

        // map
        $col = [[1=>'yes'],[1=>'no','test'=>'ok']];
        assert([[1=>'YES'],[1=>'NO','test'=>'ok']] === Base\Column::map(1,'strtoupper',$col));
        assert([[1=>'yes'],[1=>'no','test'=>'OK']] === Base\Column::map('test','strtoupper',$col));
        assert([[1=>'YES'],[1=>'NO','test'=>'ok']] === Base\Column::map(1,[Base\Str::class,'upper'],$col));
        Base\Column::map(1,function($v,$k,$value) {
            assert(is_string($v));
            assert(is_int($k));
            assert(is_array($value));
        },$col);

        // filter
        $col = [['test'=>'yes',2=>'TESa'],['test'=>'no',2=>'TEST'],['testa'=>'bla']];
        $col = Base\Column::filter('test',function($v,$k,$value) {
            if($v === 'yes' && is_array($value))
            return true;
        },$col);
        assert(count($col) === 1);
        $col = [['test'=>'yes',2=>'TESa'],['test'=>'no',2=>'TEST'],['testa'=>'bla']];

        // arr
        $multi = [0=>['name'=>'test','user'=>'testa'],3=>['name'=>'test2','user'=>'testa2'],2=>['user'=>['testa3'],'meh'=>'test','bla'=>'2']];
        assert(['testa','testa2',['testa3']] === Base\Column::keyValue(null,'user',$multi));
        assert([] === Base\Column::keyValue([],'user',$multi));
        assert(['2'] === Base\Column::keyValue('user','bla',$multi));
        assert(['test'=>'2'] === Base\Column::keyValue('meh','bla',$multi));
        assert([] === Base\Column::keyValue(null,'namez',$multi));
        assert([] === Base\Column::keyValue([],[],$multi));
        assert([] === Base\Column::keyValue(null,null,$multi));
        assert([] === Base\Column::keyValue(null,0,[1,2,3]));

        // arrIndex
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['user'=>'testa3','zz'=>true]];
        assert(['test'=>'testa','test2'=>'testa2','testa3'=>true] === Base\Column::keyValueIndex(0,1,$multi));
        assert(['testa'=>'testa','testa2'=>'testa2',1=>true] === Base\Column::keyValueIndex(1,1,$multi)); // note, changement dans array_column php 7.2.9
        assert(['test'=>'testa','test2'=>'testa2','testa3'=>true] === Base\Column::keyValueIndex(0,-1,$multi));
        assert(['test'=>'testa','test2'=>'testa2','testa3'=>true] === Base\Column::keyValueIndex(0,1,$multi));
        assert(['test'=>'test','test2'=>'test2','testa3'=>'testa3'] === Base\Column::keyValueIndex(0,0,$multi));
        assert(['test','test2','testa3'] === Base\Column::keyValueIndex(null,0,$multi));

        // arrSegment
        assert(['testa'=>'namez','testa2'=>'namez','testa3'=>'namez'] === Base\Column::arrSegment('user','namez',$multi));
        assert([] === Base\Column::arrSegment('userz','name',$multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>'test','user'=>'testa2']];
        assert(['testa'=>'test','testa2'=>'test'] === Base\Column::arrSegment('user','name',$multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>['test'],'user'=>'testa3']];
        assert(['testa'=>'test','testa2'=>'test2','testa3'=>['test']] === Base\Column::arrSegment('user','name',$multi));
        assert([] === Base\Column::arrSegment(null,'name',$multi));
        assert(['testa'=>'test - testa','testa2'=>'test2 - testa2','testa3'=>'[name] - testa3'] === Base\Column::arrSegment('user','[name] - [user]',$multi));

        // splice
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>'test','user'=>'testa2']];
        assert(Base\Column::splice('name','user',$multi)[0] === []);
        assert(Base\Column::splice('name',true,$multi)[0] === ['user'=>'testa']);
        assert(Base\Column::splice('namez',true,$multi) === $multi);
        $multi = [0=>['namÉ'=>'test','user'=>'testa'],1=>['namÉ'=>'test2','user'=>'testa2'],2=>['namÉ'=>'test','user'=>'testa2']];
        assert(count(Base\Column::splice('NAMé',true,$multi,null,true)[0]) === 2);
        assert(count(Base\Column::splice('NAMé',true,$multi,null,false)[0]) === 1);
        assert(count(Base\Column::splice('NAMé',true,$multi,['USERz'=>'LOL'],false)[0]) === 2);
        assert(count(Base\Column::splice('NAMé',true,$multi,['USER'=>'LOL'],false)[0]) === 1);

        // spliceIndex
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>'test','user'=>'testa2']];
        assert(Base\Column::spliceIndex(0,2,$multi)[0] === []);
        assert(Base\Column::spliceIndex(0,null,$multi)[0] === ['user'=>'testa']);
        assert(Base\Column::spliceIndex(-1,null,$multi,['date'=>1234])[0] === ['name'=>'test','date'=>1234]);

        // spliceFirst
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>'test','user'=>'testa2']];
        assert(Base\Column::spliceFirst($multi)[0] === ['user'=>'testa']);

        // spliceLast
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2'],2=>['name'=>'test','user'=>'testa2']];
        assert(Base\Column::spliceLast($multi)[0] === ['name'=>'test']);

        // insert
        assert(Base\Column::insert('name',['date'=>1234],$multi)[0] === ['date'=>1234,'name'=>'test','user'=>'testa']);
        assert(Base\Column::insert('namez',['replace'=>true],$multi) === $multi);

        // insertIndex
        assert(Base\Column::insertIndex(0,['date'=>1234],$multi)[0] === ['date'=>1234,'name'=>'test','user'=>'testa']);
        assert(Base\Column::insertIndex(2000,['replace'=>true],$multi)[0] === ['name'=>'test','user'=>'testa','replace'=>true]);
        assert(Base\Column::insertIndex(0,['date'=>1234],$multi)[0] === ['date'=>1234,'name'=>'test','user'=>'testa']);
        assert(Base\Column::insertIndex(-1,['date'=>1234],$multi)[0] === ['name'=>'test','date'=>1234,'user'=>'testa']);

        // keyExists
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2','bla'=>'test']];
        assert(Base\Column::keyExists('name',$multi));
        assert(!Base\Column::keyExists('bla',$multi));

        // keysExists
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::keysExists(['name','user'],$multi));
        assert(!Base\Column::keysExists(['name','userz'],$multi));

        // keysAre
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::keysAre(['name','user'],$multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['namez'=>'test2','user'=>'testa2']];
        assert(!Base\Column::keysAre(['name','user'],$multi));
        assert(!Base\Column::keysAre(['name'],$multi));

        // keyTo
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert([0=>['name'=>'test','user'=>'testa','key'=>0],1=>['name'=>'test2','user'=>'testa2','key'=>1]] === Base\Column::keyTo('key',$multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>true];
        assert([0=>['name'=>'test','user'=>'testa','key'=>0],1=>true] === Base\Column::keyTo('key',$multi));

        // keyFrom
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>'testa2']];
        assert(['testa'=>['name'=>'test','user'=>'testa'],'testa2'=>['name'=>'test2','user'=>'testa2']] === Base\Column::keyFrom('user',$multi));
        $multi = [0=>['name'=>'test','user'=>'testa'],1=>['name'=>'test2','user'=>[]]];
        assert(['testa'=>['name'=>'test','user'=>'testa']] === Base\Column::keyFrom('user',$multi));

        // keyFromIndex
        $multi = [2=>['name'=>'test','user'=>'testa'],3=>['name'=>'test2','user'=>'testa2']];
        assert(Base\Column::keyFromIndex(1,$multi) === ['testa'=>['name'=>'test','user'=>'testa'],'testa2'=>['name'=>'test2','user'=>'testa2']]);

        // keySwap
        $multi = ['name'=>['well','test'],'error'=>['james','none']];
        assert(Base\Column::keySwap($multi) === [['name'=>'well','error'=>'james'],['name'=>'test','error'=>'none']]);

        // value
        $multi = [0=>['name'=>'test','user'=>'testa'],3=>['name'=>'test2','user'=>'testa2'],2=>['user'=>['testa3'],'meh'=>'test','bla'=>'2']];
        assert(['testa','testa2',['testa3']] === Base\Column::value('user',$multi));

        // valueIndex
        $multi = [0=>['name'=>'test','user'=>'testa'],3=>['name'=>'test2','user'=>'testa2'],2=>['user'=>['testa3'],'meh'=>'test']];
        assert(['test','test2',['testa3']] === Base\Column::valueIndex(0,$multi));
        assert(['testa','testa2','test'] === Base\Column::valueIndex(-1,$multi));

        // valueSegment
        $multi = [0=>['name'=>'test','user'=>'testa'],3=>['name'=>'test2','user'=>'testa2'],2=>['user'=>['testa3'],'meh'=>'test','bla'=>'2']];
        assert(Base\Column::valueSegment('[name] :) [user]',$multi)[0] === 'test :) testa');
        assert(Base\Column::valueSegment('[name] [user]',$multi)[2] === '[name] [user]');
        assert(Base\Column::valueSegment('user',$multi)[0] === 'testa');

        return true;
    }
}
?>