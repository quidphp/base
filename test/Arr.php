<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// arr
// class for testing Quid\Base\Arr
class Arr extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // typecast
        $x = 1;
        $y = '2';
        $z = [];
        $obj = new \DateTime('now');
        Base\Arr::typecast($x,$y,$z,$obj);
        assert([1] === $x);
        assert(['2'] === $y);
        assert([] === $z);
        assert($obj[0] instanceof \DateTime);
        $obj2 = new \DateTime('now');

        // cast
        assert([1,1.2,'1,3',12345678901,'true','false','null',null] === Base\Arr::cast(['1','1.2','1,3','12345678901','true','false','null',null]));
        assert([1,'true','false','NULL',null] === Base\Arr::cast(['1','true','false','NULL',null]));
        assert(['1','true','false','NULL',null] === Base\Arr::cast(['1','true','false','NULL',null],0));
        assert([1,1.2,'1,3',12345678901,true,false,null,null] === Base\Arr::cast(['1','1.2','1,3','12345678901','true','false','null',null],1,1));
        assert(['000111',1,1.2,'12345678901123123',true,false,null,null] === Base\Arr::cast(['000111','1','1.2','12345678901123123','true','false','null',null],1,1));
        assert([1,1.2,1.3,12345678901,true,false,null,null] === Base\Arr::cast(['1','1.2','1,3','12345678901','true','false','null',null],2,1));
        assert([1,1.2,1.3,12345678901,true,false,null,null,true,false] === Base\Arr::cast(['1','1.2','1,3','12345678901','true','false','null',null,'on','off'],2,2));

        // castMore
        assert([111,1,1.2,1.3,12345678901,true,false,null,null] === Base\Arr::castMore(['000111','1','1.2','1,3','12345678901','true','false','null',null]));

        // is
        assert(Base\Arr::is([]));
        assert(!Base\Arr::is(1));

        // isEmpty
        assert(Base\Arr::isEmpty([]));
        assert(!Base\Arr::isEmpty([1]));

        // isNotEmpty
        assert(!Base\Arr::isNotEmpty([]));
        assert(Base\Arr::isNotEmpty([1]));

        // isCleanEmpty
        assert(Base\Arr::isCleanEmpty(['',null]));
        assert(Base\Arr::isCleanEmpty(['',[]]));
        assert(!Base\Arr::isCleanEmpty(['',[null]]));
        assert(!Base\Arr::isCleanEmpty(['',null,false]));

        // hasNumericKey
        assert(!Base\Arr::hasNumericKey([]));
        assert(Base\Arr::hasNumericKey([0,1,2,'test']));
        assert(!Base\Arr::hasNumericKey(['test'=>'oui']));
        assert(Base\Arr::hasNumericKey(['test'=>'oui',2]));

        // hasNonNumericKey
        assert(!Base\Arr::hasNonNumericKey([0,1,2,'test']));
        assert(Base\Arr::hasNonNumericKey(['test'=>'oui']));

        // hasKeyCaseConflict
        assert(!Base\Arr::hasKeyCaseConflict([]));
        assert(!Base\Arr::hasKeyCaseConflict([1,2]));
        assert(Base\Arr::hasKeyCaseConflict(['test'=>'ok','TEST'=>'yeah','TÉST'=>'ok']));
        assert(Base\Arr::hasKeyCaseConflict(['tést'=>'ok','TÉST'=>'yeah']));
        assert(!Base\Arr::hasKeyCaseConflict(false,true));

        // isIndexed
        assert(Base\Arr::isIndexed([0,1,2,'test']));
        assert(!Base\Arr::isIndexed(['test'=>'oui']));
        assert(Base\Arr::isIndexed([1,2]));
        assert(Base\Arr::isIndexed([]));
        assert(Base\Arr::isIndexed([1=>'ok']));
        assert(!Base\Arr::isIndexed(false));

        // isSequential
        assert(Base\Arr::isSequential([0,1,2,'test']));
        assert(!Base\Arr::isSequential(['test'=>'oui'],[0]));
        assert(Base\Arr::isSequential([1,2],[]));
        assert(!Base\Arr::isSequential([1=>'ok']));
        assert(Base\Arr::isSequential([]));

        // isAssoc
        assert(Base\Arr::isAssoc([0,1,2,'test'=>2]));
        assert(!Base\Arr::isAssoc([0,1,2]));
        assert(Base\Arr::isAssoc([]));

        // isUni
        assert(Base\Arr::isUni([]));
        assert(Base\Arr::isUni([1,2]));
        assert(!Base\Arr::isUni([1,2,[]]));

        // isMulti
        assert(!Base\Arr::isMulti([]));
        assert(Base\Arr::isMulti([1,2,[]]));

        // onlyNumeric
        assert(Base\Arr::onlyNumeric([1,2]));
        assert(!Base\Arr::onlyNumeric([1,'test'=>2]));
        assert(!Base\Arr::onlyNumeric([1,'test']));
        assert(!Base\Arr::onlyNumeric(false));

        // onlyString
        assert(!Base\Arr::onlyString([1,'test'=>2]));
        assert(!Base\Arr::onlyString(['2'=>'2','test'=>2]));
        assert(Base\Arr::onlyString(['2a'=>'2','test'=>'ok']));

        // isSet
        assert(Base\Arr::isSet([1,2]));
        assert(!Base\Arr::isSet([1,'test'=>2]));
        assert(Base\Arr::isSet([1,'test']));
        assert(!Base\Arr::isSet(false));

        // isKey
        assert(Base\Arr::isKey(2));
        assert(Base\Arr::isKey('test'));
        assert(!Base\Arr::isKey(false));
        assert(Base\Arr::isKey(''));

        // isKeyNotEmpty
        assert(!Base\Arr::isKeyNotEmpty(''));

        // isCount
        assert(Base\Arr::isCount(2,[1,2]));
        assert(Base\Arr::isCount(['bla','ok'],[1,2]));

        // isMinCount
        assert(Base\Arr::isMinCount(1,[1,2]));
        assert(!Base\Arr::isMinCount(3,[1,2]));
        assert(Base\Arr::isMinCount([2],[1,2]));

        // isMaxCount
        assert(Base\Arr::isMaxCount(3,[1,2]));
        assert(Base\Arr::isMaxCount(2,[1,2]));
        assert(!Base\Arr::isMaxCount(1,[1,2]));
        assert(Base\Arr::isMaxCount([3,4],[1,2]));

        // same
        $array1 = [1=>1,'tst'=>2,2=>3];
        $array2 = [1=>2,'tst'=>3,2=>4,5=>2];
        assert(Base\Arr::same($array1,$array1,$array1));
        assert(!Base\Arr::same($array1,$array1,$array1,true,false,1));
        assert(!Base\Arr::same($array1,$array1,$array2));
        assert(!Base\Arr::same([],[]));

        // sameCount
        $array1 = [1=>1,'tst'=>2,2=>3];
        $array2 = [1=>1,4=>2];
        assert(Base\Arr::sameCount($array1,$array1));
        assert(Base\Arr::sameCount($array1,$array2) === false);
        assert(Base\Arr::sameCount([],[]));

        // sameKey
        $array1 = [1=>1,'tst'=>2,2=>3];
        $array2 = [1=>1,0=>2,2=>2,4=>2,'tst'=>'test'];
        assert(Base\Arr::sameKey($array1,$array2,$array1));
        assert(Base\Arr::sameKey($array1,$array1,$array1,[1,2,3]) === false);
        assert(!Base\Arr::sameKey([],[]));

        // sameKeyValue
        assert(Base\Arr::sameKeyValue([2,3,4],[2,3,4]));
        assert(Base\Arr::sameKeyValue([2,3,4],[2=>4,1=>3,0=>2]));
        assert(!Base\Arr::sameKeyValue([],[]));

        // hasValueStart
        assert(Base\Arr::hasValueStart('test2.jpg',['test','james','test3']));
        assert(!Base\Arr::hasValueStart('test2.jpg',['test4','james','test3']));
        assert(Base\Arr::hasValueStart('test2.jpg',['te','james','test3']));

        // plus
        $merge1 = ['test'=>'test','test2'=>[0=>'test3']];
        $merge2 = ['test'=>'test6','test2'=>[1=>'test3'],'test5'=>[0=>'test4']];
        assert(['test'=>2] === Base\Arr::plus(['test'=>1],['test'=>2]));
        assert([0=>2,'test'=>1,'james'=>1] === Base\Arr::plus(['test'=>1,'james'=>1],2));
        assert(['test'=>[0=>3]] === Base\Arr::plus(['test'=>[0=>2]],['test'=>[0=>3]]));
        assert(['test'=>3] === Base\Arr::plus(['test'=>1],['test'=>2],['test'=>3]));
        assert([0=>4,'test'=>3] === Base\Arr::plus(['test'=>1],['test'=>2],['test'=>3],4));
        assert([0=>4,'test'=>3] === Base\Arr::plus(['test'=>1],['test'=>2],['test'=>3],[4]));
        assert(['test'=>'test6','test2'=>[1=>'test3'],'test5'=>[0=>'test4']] === Base\Arr::plus($merge1,$merge2));
        assert(Base\Arr::plus(['test'=>[0=>2,'test'=>'ok']],['test'=>[0=>3,'test2'=>'what']]) === ['test'=>[0=>3,'test2'=>'what']]);
        assert(Base\Arr::plus(['test']) === ['test']);
        assert(Base\Arr::plus(['test'],$obj2,$obj2) === [$obj2]);
        assert(Base\Arr::plus($obj,$obj,['test'],$obj) === [$obj[0]]);

        // merge
        $merge1 = ['test'=>1,'test2'=>[1,2,'ok'=>'james'],'test3'=>'ok','4'=>'ok','james'=>'what'];
        $merge2 = ['test'=>2,'test2'=>['test'=>'ok'],'test3'=>'bla'];
        assert(['test'=>2,'test2'=>['test'=>'ok'],'test3'=>'bla','0'=>'ok','james'=>'what'] === Base\Arr::merge($merge1,$merge2));
        assert(Base\Arr::merge(['test'],$obj2,$obj2) === ['test',$obj2,$obj2]);

        // replace
        $merge1 = ['test'=>1,'test2'=>[1,2],'test3'=>'ok','4'=>'ok'];
        $merge2 = ['test'=>2,'test2'=>['test'=>'ok'],'test3'=>'bla'];
        assert(['test'=>2,'test2'=>['test'=>'ok'],'test3'=>'bla','4'=>'ok'] === Base\Arr::replace($merge1,$merge2));
        assert(Base\Arr::replace(['test'],$obj2,$obj2) === [$obj2]);

        // replaceIf
        $slice = ['test'=>'test','test2'=>'test2','test3'=>'test3'];
        assert(['test'=>'test4','test2'=>'test2','test3'=>'test3'] === Base\Arr::replaceIf('exists',$slice,['test'=>'test4']));
        assert(['test'=>'test','test2'=>'test2','test3'=>'test3'] === Base\Arr::replaceIf('exists',$slice,['test4'=>'test4']));
        $slice = ['test'=>'test','test2'=>'test2','test3'=>'test3'];
        assert(['test'=>'test','test2'=>'test2','test3'=>'test3'] === Base\Arr::replaceIf('notExists',$slice,['test'=>'test4']));
        assert(['test'=>'test','test2'=>'test2','test3'=>'test3','test4'=>'test4'] === Base\Arr::replaceIf('notExists',$slice,['test4'=>'test4']));
        $merge1 = ['test'=>1,'test2'=>2,'test3'=>'ok','test4'=>[1]];
        $merge2 = ['test'=>2,'test2'=>1,'test3'=>'bla','test4'=>[2,2],'test5'=>4];
        assert(['test'=>2,'test2'=>2,'test3'=>'ok','test4'=>[2,2],'test5'=>4] === Base\Arr::replaceIf('bigger',$merge1,$merge2));
        $merge1 = ['test'=>1,'test2'=>2,'test3'=>'ok','test4'=>[1,4]];
        $merge2 = ['test'=>2,'test2'=>1,'test3'=>'bla','test4'=>[2],'test5'=>3];
        assert(['test'=>1,'test2'=>1,'test3'=>'bla','test4'=>[2]] === Base\Arr::replaceIf('smaller',$merge1,$merge2));

        // replaceCleanNull
        assert(Base\Arr::replaceCleanNull(['test2'=>'ok',12,3],['test2'=>null,'james'=>'ok']) === [12,3,'james'=>'ok']);

        // unshift
        assert(Base\Arr::unshift('test','test2') === ['test2','test']);
        assert(Base\Arr::unshift(['test'],['test2']) === [['test2'],'test']);
        assert(Base\Arr::unshift(['test','test3'],['test2']) === [['test2'],'test','test3']);
        assert(Base\Arr::unshift(['test','test'=>'ok'],'james','james2') === ['james','james2','test','test'=>'ok']);

        // push
        assert(Base\Arr::push('test','test2',['test3','test4']) === ['test','test2',['test3','test4']]);
        assert(Base\Arr::push('test','test2',['test3',['test4']]) === ['test','test2',['test3',['test4']]]);
        assert(Base\Arr::push(['test'],'test2',['test3','test4']) === ['test','test2',['test3','test4']]);
        assert(Base\Arr::push(['test'],$obj,$obj2) === ['test',$obj,$obj2]);

        // prepend
        assert(Base\Arr::prepend('test',['test2'],['test3','test4']) === ['test2','test3','test4','test']);
        assert(Base\Arr::prepend(['test'],'test2',[30=>'test3','test4']) === [0=>'test2',30=>'test3',31=>'test4',32=>'test']);
        assert(Base\Arr::prepend(['test','test'=>'ok'],['james','what'=>'james','test'=>'BURP'],$obj2) === ['james','what'=>'james','test'=>'ok',$obj2,'test']);
        assert(Base\Arr::prepend(['test','test'=>'ok'],['james','what'=>'james','test'=>'BURP']) === ['james','what'=>'james','test'=>'ok',1=>'test']);
        assert(Base\Arr::prepend(['test'=>2,'ok'=>3],['TEST'=>3]) === ['TEST'=>3,'test'=>2,'ok'=>3]);

        // iprepend
        assert(Base\Arr::iprepend(['test'=>2,'ok'=>3],['TEST'=>3]) === ['test'=>2,'ok'=>3]);
        assert(Base\Arr::iprepend(['test'=>2,'ok'=>3],['TEST'=>3],2,3,'james') === [2,3,'james','test'=>2,'ok'=>3]);

        // append
        assert(Base\Arr::append(['test'],'test2',['test3','test4']) === ['test','test2','test3','test4']);
        assert(Base\Arr::append('test','test2',['test3','test4']) === ['test','test2','test3','test4']);
        assert(Base\Arr::append('test','test2',[20=>'test3',['test4']]) === ['test','test2',20=>'test3',21=>['test4']]);
        assert(Base\Arr::append(['test','test'=>'ok'],['james','what'=>'james','test'=>'BURP',$obj2],$obj,$obj2) === ['test','test'=>'BURP',1=>'james','what'=>'james',2=>$obj2,3=>$obj[0],4=>$obj2]);
        assert(Base\Arr::append(['test','test'=>'ok'],['james','what'=>'james','test'=>'BURP']) === ['test','test'=>'BURP',1=>'james','what'=>'james']);
        assert(Base\Arr::append('test','test2',['testz','test'=>[['test4']]],['testx','test'=>['test4']]) === ['test','test2','testz','test'=>['test4'],'testx']);
        assert(Base\Arr::append(['test']) === ['test']);
        assert(Base\Arr::append(['test'=>2,'ok'=>3],['TEST'=>3]) === ['test'=>2,'ok'=>3,'TEST'=>3]);
        assert(Base\Arr::append('test','test2',['ok','james',8=>'ok','bla']) === ['test','test2','ok','james',8=>'ok',9=>'bla']);

        // iappend
        assert(Base\Arr::iAppend(['test'=>2,'ok'=>3],['TEST'=>3]) === ['ok'=>3,'TEST'=>3]);

        // appendUnique
        assert(Base\Arr::appendUnique(true,false,'what',['test'=>'What']) === [true,false,'what','test'=>'What']);
        assert(Base\Arr::appendUnique(true,false,[10=>'what','What']) === [true,false,10=>'what',11=>'What']);

        // appendiUnique
        assert(Base\Arr::appendiUnique(true,false,[5=>'what'],['test'=>'What']) === [true,false,5=>'what']);

        // smart
        $smart = [['test']];
        assert(['test'] === Base\Arr::smart($smart));

        // clean
        $clean = ['',0,null,[]];
        assert([1=>0] === Base\Arr::clean($clean));
        $clean = ['',null,true,false];
        assert([2=>true,false] === Base\Arr::clean($clean));
        assert([true,false] === Base\Arr::clean($clean,true));

        // cleanEmpty
        $clean = ['',0,null,[]];
        assert([] === Base\Arr::cleanEmpty($clean));

        // cleanNull
        assert(Base\Arr::cleanNull(['',null,[]]) === ['',2=>[]]);

        // cleanNullBool
        assert(Base\Arr::cleanNullBool([null,'',2,true]) === [1=>'',2=>2]);
        assert(Base\Arr::cleanNullBool([null,'',2,true],true) === ['',2]);

        // reallyEmptyToNull
        assert(Base\Arr::reallyEmptyToNull(['test'=>'','james'=>0,'ok'=>true]) === ['test'=>null,'james'=>0,'ok'=>true]);

        // trim
        assert(Base\Arr::trim([' test '=>' ok ',2=>true],true) === ['test'=>'ok',2=>true]);

        // trimClean
        assert(Base\Arr::trimClean([' test '=>' ok ','',2=>true],true,true,true) === ['test'=>'ok',2=>true]);
        assert(Base\Arr::trimClean([' test '=>' ok ','',2=>true],false,true,true) === [' test '=>'ok',2=>true]);
        assert(Base\Arr::trimClean([' test '=>' ok ','',2=>true],false,false,true) === [' test '=>' ok ',2=>true]);

        // validate
        assert(Base\Arr::validate('bool',['test'=>false,'test2'=>true]));
        assert(Base\Arr::validate('email',['test@gmail.com','e@test.ca']));
        assert(!Base\Arr::validate("/^\d{4}$/",[2013,'bla']));
        assert(Base\Arr::validate("/^\d{4}$/",[2013,'2014']));
        assert(Base\Arr::validate('year',[2013,'2014']));
        assert(!Base\Arr::validate('year',[2013,'2014','bla']));
        assert(Base\Arr::validate('scalar',['test'=>false,'test2'=>'bla']));
        assert(Base\Arr::validate('bool',['test'=>false,'test2'=>true]));
        assert(!Base\Arr::validate('array',['test'=>false,'test2'=>true]));
        assert(Base\Arr::validate('scalar',['test'=>1,'test2'=>'2','test'=>'string']));

        // validates
        assert(Base\Arr::validates('bool',['test'=>false],['test2'=>true]));
        assert(Base\Arr::validates('email',['test@gmail.com'],['e@test.ca']));
        assert(!Base\Arr::validates('email',['test@gmail.com'],[true]));
        assert(!Base\Arr::validates('bool',['test'=>false],['test2'=>'ok']));

        // validateSlice
        assert(['test@gmail.com'] === Base\Arr::validateSlice('email',['test@gmail.com',true]));
        assert(['test@gmail.com'] === Base\Arr::validateSlice('email',['test@gmail.com',true]));
        assert([1=>true] === Base\Arr::validateSlice('bool',['test@gmail.com',true]));
        assert([] === Base\Arr::validateSlice('email',[['test@gmail.com'],true]));
        assert([1=>true] === Base\Arr::validateSlice('bool',['test@gmail.com',true]));

        // validateStrip
        assert([1=>true] === Base\Arr::validateStrip('email',['test@gmail.com',true]));
        assert([['test@gmail.com'],1=>true] === Base\Arr::validateStrip('email',[['test@gmail.com'],true]));
        assert(['test@gmail.com'] === Base\Arr::validateStrip('bool',['test@gmail.com',true]));

        // validateMap
        $array = ['test',2=>'test2',[]];
        assert(Base\Arr::validateMap('string','strtoupper',$array) === ['TEST',2=>'TEST2',3=>[]]);
        $array = ['test'=>'test','test2'=>'test2!'];
        assert(['test'=>'!test!','test2'=>'!test2!!'] === Base\Arr::validateMap('string',function($a) { return "!$a!"; },$array));
        assert(['test'=>'test!','test2'=>'test2!!'] === Base\Arr::validateMap('string',function($a) { return "$a!"; },$array));
        assert(['test'=>'!test!','test2'=>'!test2!'] === Base\Arr::validateMap('string',function($a) { return Base\Str::wrapStartOrEnd('!','!',$a); },$array));
        assert(['test'=>'test!','test2'=>'test2!'] === Base\Arr::validateMap('string',function($a) { return Base\Str::wrapEnd('!',$a); },$array));
        $array = ['test'=>'test','!test2!'=>'!test2!'];
        assert(['test'=>'test','!test2!'=>'test2'] === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStartEnd('!','!',$a); },$array));
        $array = ['test'=>'test','!test2!'=>'test2!'];
        assert(['test'=>'test','!test2!'=>'test2'] === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStartOrEnd('!','!',$a); },$array));
        assert(['test'=>'test','!test2!'=>'test2!'] === Base\Arr::validateMap('string',function($a) { return Base\Str::stripStart('!',$a); },$array));
        assert(['test'=>'test','!test2!'=>'test2'] === Base\Arr::validateMap('string',function($a) { return Base\Str::stripEnd('!',$a); },$array));
        $slice = ['key_fr','key_en','key_es','what',[]];
        assert(['name_fr','name_en','name_es','what',[]] === Base\Arr::validateMap('string',function($a) { return Base\Str::changeBefore('_','name',$a); },$slice));
        $slice = ['name_fr','key_fr','content_fr','what',[]];
        assert(['name_en','key_en','content_en','what',[]] === Base\Arr::validateMap('string',function($a) { return Base\Str::changeAfter('_','en',$a); },$slice));

        // validateFilter
        $array = ['test',2=>'test2',[]];
        assert(Base\Arr::validateFilter('string',function($a,$b,$c) {
            if($a === 'test2') return true;
        }, $array) === [2=>'test2',[]]);
        assert(Base\Arr::validateFilter('string',function($a,$b,$c) {
            if($a === 'test2') return true;
        }, $array,false) === [2=>'test2']);
        $array = ['test'=>'test','!test2!'=>'!test2!'];
        assert(['!test2!'=>'!test2!'] === Base\Arr::validateFilter('string',function($a) { return Base\Str::isStart('!',$a); },$array,true));
        $array = ['test'=>'testzz','!test2!'=>'!test2!'];
        assert(['!test2!'=>'!test2!'] === Base\Arr::validateFilter('string',function($a) { return Base\Str::isEnd('!',$a); },$array,true));

        // get
        $array = ['test'=>'ok','james'=>2,3=>'ok'];
        assert(Base\Arr::get('test',$array) === 'ok');
        assert(Base\Arr::get('Test',$array) === null);
        $array = ['test'=>'ok','jamÉs'=>2,3=>'ok'];
        assert(Base\Arr::get('Test',$array,false) === 'ok');
        assert(Base\Arr::get('jamés',$array,false) === 2);

        // getSafe
        assert(Base\Arr::getSafe('test','ok') === null);
        assert(Base\Arr::getSafe('test',['test'=>'k']) === 'k');
        assert(Base\Arr::getSafe('test',['test2'=>'k']) === null);
        assert(Base\Arr::getSafe(null,['test2'=>'k']) === ['test2'=>'k']);

        // gets
        $array = ['Test'=>'ok','james'=>2,3=>'ok'];
        assert(Base\Arr::gets(['Test','james',3],$array) === $array);
        assert(Base\Arr::gets(['Test',4],$array) === ['Test'=>'ok',4=>null]);
        $array = ['test'=>'ok','TEsT'=>'ok2','james'=>2,3=>'ok'];
        assert(Base\Arr::gets(['TEST',3,'jAmes'],$array,false) === ['TEST'=>'ok2',3=>'ok','jAmes'=>2]);

        // getsExists
        $array = ['Test'=>'ok','james'=>2,3=>'ok'];
        assert(Base\Arr::getsExists(['Test',4],$array) === ['Test'=>'ok']);

        // indexPrepare
        assert(Base\Arr::indexPrepare(-3,count([1,2,3])) === 0);
        assert(Base\Arr::indexPrepare(-1,count([1,2,3])) === 2);
        assert(Base\Arr::indexPrepare(0,count([1,2,3])) === 0);
        assert(Base\Arr::indexPrepare(2,[1,2,3]) === 2);
        assert(Base\Arr::indexPrepare(3,count([1,2,3])) === 3);
        assert(Base\Arr::indexPrepare(-4,count([1,2,3])) === -4);
        assert(Base\Arr::indexPrepare([0,-1],[1,2,3]) === [0,2]);
        assert(Base\Arr::indexPrepare([0,-1],count([1,2,3])) === [0,2]);
        assert(Base\Arr::indexPrepare([0,-444],count([1,2,3])) === [0,-444]);

        // index
        $array = [1,2,'bla'=>3];
        assert(Base\Arr::index(-1,$array) === 3);
        assert(Base\Arr::index(100,$array) === null);

        // indexes
        assert(Base\Arr::indexes([0,-1],[1,2,'bla'=>3]) === [1,2=>3]);
        assert(Base\Arr::indexes([0,40,-10],[1,2,'bla'=>3]) === [1,40=>null,-10=>null]);

        // set
        $array = ['test'=>'ok','james'=>2,3=>'ok'];
        assert($array = Base\Arr::set(4,0,$array));
        assert(Base\Arr::get(4,$array) === 0);
        assert($array = Base\Arr::set('TEST','ok2',$array,false));
        assert(count($array) === 4);
        assert(Base\Arr::get('test',$array) === 'ok2');
        assert(Base\Arr::set(null,'mehg',$array)[5] === 'mehg');

        // sets
        $array = Base\Arr::sets(['TEST'=>'yeah',4=>1],$array);
        assert($array['TEST'] === 'yeah');
        assert(Base\Arr::get(4,$array) === 1);
        assert(count(Base\Arr::sets(['JAMES'=>'ok'],$array,false)) === 5);

        // setRef
        $arr = [];
        Base\Arr::setRef('test',2,$arr);
        assert($arr['test'] === 2);
        Base\Arr::setRef('TEST',3,$arr,false);
        assert(count($arr) === 1);
        Base\Arr::setRef(null,'meh',$arr);
        assert($arr[0] === 'meh');

        // setsRef
        Base\Arr::setsRef(['Test'=>3],$arr);
        assert($arr['test'] === 3);
        assert(count($arr) === 3);
        Base\Arr::setsRef(['TesT'=>4],$arr,false);
        assert(count($arr) === 2);

        // setMerge
        assert(Base\Arr::setMerge('test',2,['test2'=>true]) === ['test2'=>true,'test'=>2]);
        assert(Base\Arr::setMerge('test2',2,['test2'=>true]) === ['test2'=>[true,2]]);
        assert(Base\Arr::setMerge('TEST2',2,['test2'=>true],false) === ['test2'=>[true,2]]);
        assert(Base\Arr::setMerge(null,2,['test2'=>true],false) === ['test2'=>true,2]);

        // setsMerge
        assert(Base\Arr::setsMerge(['test2'=>2],['test2'=>true]) === ['test2'=>[true,2]]);

        // unset
        $array = Base\Arr::sets(['TEST'=>'yeah',4=>1],$array);
        assert($array = Base\Arr::unset('test',$array,false));
        assert(Base\Arr::get('test',$array) === null);

        // unsets
        assert($array = Base\Arr::unsets([4,3],$array));
        assert(count($array) === 1);
        assert([] === Base\Arr::unsets(['JaMeS'],$array,false));

        // unsetRef
        $arr = ['test'=>true,'test2'=>'ok'];
        Base\Arr::unsetRef('test',$arr);
        assert(count($arr) === 1);
        Base\Arr::unsetRef('TEST2',$arr,false);
        assert(count($arr) === 0);

        // unsetsRef
        $arr = ['test'=>true,'test2'=>'ok'];
        Base\Arr::unsetsRef(['TEST2'],$arr,false);
        assert(count($arr) === 1);
        Base\Arr::unsetsRef(['test','test2'],$arr);
        assert(count($arr) === 0);

        // getSet
        $array = ['test'=>2];
        assert(2 === Base\Arr::getSet('test',null,$array));
        assert($array === Base\Arr::getSet(null,null,$array));
        assert(true === Base\Arr::getSet('test4',44,$array));
        assert(44 === $array['test4']);
        assert(true === Base\Arr::getSet(['test'=>2,'test3'=>4],null,$array));
        assert(4 === $array['test3']);
        assert(true === Base\Arr::getSet('testa/23',44,$array));
        assert(44 === $array['testa/23']);
        assert(true === Base\Arr::getSet(['test'=>2,'test3'=>4],true,$array));
        assert($array === ['test'=>2,'test3'=>4]);

        // keyValue
        assert(Base\Arr::keyValue('lol','ok',['lol'=>2,'ok'=>'bla','bleu']) === [2=>'bla']);

        // keyValueIndex
        assert(Base\Arr::keyValueIndex(1,0,['lol'=>2,'ok'=>'bla','bleu']) === ['bla'=>2]);

        // keys
        assert([0,1,'test'] === Base\Arr::keys([1,2,'test'=>'ok']));
        assert([0,1,'test'] === Base\Arr::keys([1,2,'test'=>'ok'],null));
        assert(['test','test2'] === Base\Arr::keys([1,2,'test'=>'ok','test2'=>'ok'],'ok'));
        assert([] === Base\Arr::keys([1,2,'test'=>'OK'],'ok'));
        assert(['test'] === Base\Arr::keys([1,2,'test'=>'OK'],'ok',false));
        assert(['test'] === Base\Arr::keys([1,2,'test'=>'ÉÉ'],'éé',false));

        // values
        assert([0=>1,1=>2] === Base\Arr::values([1=>1,2=>2]));
        assert([0=>1,1=>2,2=>[6=>'test']] === Base\Arr::values([1=>1,2=>2,6=>[6=>'test']]));
        assert([0=>1,1=>2] === Base\Arr::values([1=>1,2=>2,6=>[6=>'test']],'int'));
        assert(['test@gmail.com'] === Base\Arr::values([1=>'test@gmail.com',2=>2,6=>[6=>'test']],'email'));

        // shift
        $array = ['test'=>1,2,2.5,3];
        $shift = Base\Arr::shift($array);
        assert($shift === 1);
        assert(count($array) === 3);
        $shift = Base\Arr::shift($array,3);
        assert($shift === [2,2.5,3]);
        assert(count($array) === 0);

        // pop
        $array = ['test'=>1,2,2.5,3];
        $pop = Base\Arr::pop($array);
        assert($pop === 3);
        assert(count($array) === 3);
        $pop = Base\Arr::pop($array,3);
        assert($pop === [2.5,2,1]);
        assert(count($array) === 0);

        // walk
        $array = ['test',2,4,'test4'];
        Base\Arr::walk(function(&$v,$k,$extra) {
            if(is_int($v))
            $v += 1000;
            else
            $v .= $extra;
        },$array,'bla');
        assert($array === ['testbla',1002,1004,'test4bla']);
        $array = ['test',[2],[4],'test4'];
        Base\Arr::walk(function(&$v,$k,$extra) {
            if(is_int($v))
            $v += 1000;
            elseif(is_string($v))
            $v .= $extra;
        },$array,'bla');
        assert($array === ['testbla',[2],[4],'test4bla']);

        // map
        $array = [' test ',2=>'test2'];
        $array2 = [' test3 ',2=>'test4'];
        assert(['test',2=>'test2'] === Base\Arr::map('trim',$array));
        assert(Base\Arr::map(function($a,$b,$c) { if(is_array($c)) return trim($a).$b; },$array) === ['test0',2=>'test22']);

        // filter
        $array = ['test',2,4,'test4'];
        assert(Base\Arr::filter(function($v,$k,$a) {
            if(is_array($a) && is_int($v))
            return true;
        },$array) === [1=>2,2=>4]);
        assert(Base\Arr::filter('is_string',$array) === ['test',3=>'test4']);

        // reduce
        $array = ['test',2,4,'test4'];
        assert(Base\Arr::reduce(function($carry,$item) {
            return $carry.$item;
        },$array,'bla') === 'blatest24test4');

        // diffAssoc
        $simple1 = ['test'=>true,'test3'=>'bla'];
        $simple2 = ['testz'=>4,'test3'=>'bla'];
        $simple3 = ['test'=>2];
        assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === array_diff_assoc($simple1,$simple2,$simple3));
        assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === ['test'=>true]);
        $simple1 = ['test'=>true,'test3'=>[2]];
        $simple2 = ['testz'=>4,'test3'=>[3]];
        $simple3 = ['test'=>2];
        assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === ['test'=>true,'test3'=>[2]]);
        $simple1 = ['test'=>true,'test3'=>[2]];
        $simple2 = ['testz'=>4,'test3'=>[2]];
        $simple3 = ['test'=>true];
        assert(Base\Arr::diffAssoc($simple1,$simple2,$simple3) === []);
        $array1 = ['test'=>'testx','test2'=>'test2x','test3'=>'test3x','test4'=>['test5'=>'oui','ok'=>'ok']];
        $array2 = ['testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>['test5'=>'oui','ok'=>'ok']];
        assert(['test'=>'testx','test3'=>'test3x'] === Base\Arr::diffAssoc($array1,$array2));

        // diffKey
        $array1 = ['key'=>1,'key2'=>2,'key4'=>4];
        $array2 = ['key3'=>2,'key4'=>3];
        assert(['key'=>1,'key2'=>2] === Base\Arr::diffKey($array1,$array2));
        assert(array_diff_key($array1,$array2) === Base\Arr::diffKey($array1,$array2));
        $array1 = ['key'=>1,'key2'=>[4],'key4'=>[3]];
        $array2 = ['key3'=>2,'key4'=>3];
        assert(Base\Arr::diffKey($array1,$array2) === ['key'=>1,'key2'=>[4]]);

        // diff
        $array1 = ['key'=>1,'key2'=>2];
        $array2 = ['key3'=>2];
        assert(['key'=>1] === Base\Arr::diff($array1,$array2));
        assert(array_diff($array1,$array2) === Base\Arr::diff($array1,$array2));
        $simple1 = ['test'=>true,'test4'=>[3]];
        $simple2 = ['testz'=>4,'test3'=>[3]];
        $simple3 = ['test'=>2];
        assert(Base\Arr::diff($simple1,$simple2,$simple3) === ['test'=>true]);
        $simple3 = ['test'=>2,'bla'=>true];
        assert(Base\Arr::diff($simple1,$simple2,$simple3) === []);

        // intersectAssoc
        $array1 = ['test'=>'ok','bla'=>2];
        $array2 = ['test'=>'ok','bla'=>3];
        $array3 = ['test'=>'ok','bla'=>4];
        assert(array_intersect_assoc($array1,$array2,$array3) === Base\Arr::intersectAssoc($array1,$array2,$array3));
        assert(['test'=>'ok'] === Base\Arr::intersectAssoc($array1,$array2,$array3));
        $array1 = ['test'=>'testx','test2'=>'test2x','test3'=>'test3x','test4'=>['test5'=>'oui','ok'=>'ok']];
        $array2 = ['testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>['test5'=>'ouiz','ok'=>'ok']];
        $array3 = ['testa'=>'testx','test2'=>'test2x','test3'=>'test3xz','test4'=>['test5'=>'ouiz','ok'=>'ok']];
        assert(['test2'=>'test2x'] === Base\Arr::intersectAssoc($array1,$array2,$array3));

        // intersectKey
        $array1 = ['key3'=>[2],'key2'=>'bla'];
        $array2 = ['key3'=>2];
        assert(['key3'=>[2]] === Base\Arr::intersectKey($array1,$array2));
        assert(array_intersect_key($array1,$array2) === Base\Arr::intersectKey($array1,$array2));
        $array3 = ['key4'=>2];
        assert([] === Base\Arr::intersectKey($array1,$array2,$array3));

        // intersect
        $array1 = ['key3'=>1,'key2'=>2];
        $array2 = ['key3'=>2];
        assert(['key2'=>2] === Base\Arr::intersect($array1,$array2));
        assert(array_intersect($array1,$array2) === Base\Arr::intersect($array1,$array2));
        $array1 = ['test'=>'ok','bla'=>[2]];
        $array2 = ['test'=>[2],'bla'=>3];
        $array3 = ['test'=>'ok','bla'=>[2]];
        assert(Base\Arr::intersect($array1,$array2,$array3) === ['bla'=>[2]]);

        // unsetBeforeKey
        assert([2=>3,3=>4,4=>5] === Base\Arr::unsetBeforeKey(2,[1,2,3,4,5]));
        assert([1,2,3] === Base\Arr::unsetBeforeKey(0,[1,2,3]));

        // unsetAfterKey
        assert([1,2,3] === Base\Arr::unsetAfterKey(2,[1,2,3,4,5]));
        assert([1] === Base\Arr::unsetAfterKey(0,[1,2,3]));

        // unsetBeforeValue
        assert([2=>3,3=>4,4=>5] === Base\Arr::unsetBeforeValue(3,[1,2,3,4,5]));
        assert([] === Base\Arr::unsetBeforeValue(0,[1,2,3]));

        // unsetAfterValue
        assert([1,2] === Base\Arr::unsetAfterValue(2,[1,2,3,4,5]));
        assert([1] === Base\Arr::unsetAfterValue(1,[1,2,3]));

        // unsetBeforeIndex
        assert([2=>3,3=>4,4=>5] === Base\Arr::unsetBeforeIndex(2,[1,2,3,4,5]));
        assert([1,2,3] === Base\Arr::unsetBeforeIndex(0,[1,2,3]));
        assert([1=>2,2=>3] === Base\Arr::unsetBeforeIndex(1,[1,2,3]));

        // unsetAfterIndex
        assert([1,2,3] === Base\Arr::unsetAfterIndex(2,[1,2,3]));
        assert([1] === Base\Arr::unsetAfterIndex(0,[1,2,3]));

        // unsetBeforeCount
        assert([1=>2,2=>3,3=>4,4=>5] === Base\Arr::unsetBeforeCount(2,[1,2,3,4,5]));
        assert([1,2,3] === Base\Arr::unsetBeforeCount(0,[1,2,3]));
        assert([1,2,3] === Base\Arr::unsetBeforeCount(1,[1,2,3]));

        // unsetAfterCount
        assert([1,2] === Base\Arr::unsetAfterCount(2,[1,2,3]));
        assert([1,2,3] === Base\Arr::unsetAfterCount(100,[1,2,3]));
        assert([1] === Base\Arr::unsetAfterCount(1,[1,2,3]));

        // count
        assert(Base\Arr::count([]) === 0);
        assert(Base\Arr::count([1,[2]]) === 2);

        // countValues
        assert(['test'=>2,'test2'=>1] === Base\Arr::countValues(['test','test','test2']));
        assert(['test'=>2,'TEST'=>1] === Base\Arr::countValues(['test','TEST','test',true,false]));
        assert(['test'=>3] === Base\Arr::countValues(['TEST','test','tEst',true,false],false));
        assert(['tést'=>3] === Base\Arr::countValues(['TÉST','tést','TÉSt',true,false],false));
        assert([] === Base\Arr::countValues([[],false,true]));

        // search
        assert(1 === Base\Arr::search(2,[1,2,'test'=>'ok']));
        assert(Base\Arr::search('A',['a','asdsd'],false) === 0);
        assert(Base\Arr::search('éé',['a','ÉÉ'],false) === 1);
        assert(Base\Arr::search('èè',['a','ÉÉ'],false) === null);

        // searchFirst
        $array = [true,2=>false,'2',false,[2]];
        assert(Base\Arr::searchFirst([false,true],$array) === 2);
        assert(Base\Arr::searchFirst(['2',[2]],$array) === 3);
        assert(Base\Arr::searchFirst([null,'2',[2]],$array) === 3);
        $slice = ['1',2,[2],'test'];
        assert(Base\Arr::searchFirst([1],$slice) === null);
        $slice = ['1',2,[2],'test','éé'];
        assert(Base\Arr::searchFirst(['TEST'],$slice) === null);
        assert(Base\Arr::searchFirst(['TEST'],$slice,false) === 3);
        assert(Base\Arr::searchFirst(['ÉÉ'],$slice,false) === 4);

        // in
        assert(Base\Arr::in(1,[0,1]));
        assert(Base\Arr::in(0,[0,1]));
        assert(Base\Arr::in('b',['A','b'],false));
        assert(Base\Arr::in('a',['A','b'],false));
        assert(Base\Arr::in(['a'],[['A'],'b'],false));
        assert(Base\Arr::in(['A'],[['A'],'b'],false));
        assert(Base\Arr::in('É',['é','b'],false));

        // ins
        assert(Base\Arr::ins([1],[0,1]));
        assert(Base\Arr::ins([0,1],[0,1]));
        assert(Base\Arr::ins([[0]],[[0]]));
        assert(!Base\Arr::ins([0,1,2],[0,1]));
        assert(!Base\Arr::ins(['1'],[0,1]));
        assert(!Base\Arr::ins(['a'],['A','b']));
        assert(Base\Arr::ins(['a','b'],['A','b'],false));
        assert(!Base\Arr::ins(['a','b','c'],['A','b'],false));
        assert(Base\Arr::ins(['a'],['A','b'],false));
        assert(Base\Arr::ins(['é'],['É','b'],false));
        assert(Base\Arr::ins([['a']],[['A'],'b'],false));
        assert(Base\Arr::ins([['A']],[['A'],'b'],false));

        // inFirst
        $array = [true,2=>false,'2','A',false,[2]];
        assert(Base\Arr::inFirst([false,true],$array) === false);
        assert(Base\Arr::inFirst(['2',[2]],$array) === '2');
        assert(Base\Arr::inFirst([null,'2',[2]],$array) === '2');
        assert(Base\Arr::inFirst(['a'],$array) === null);
        assert(Base\Arr::inFirst(['a'],$array,false) === 'a');
        $slice = ['1',2,[2],'test'];
        assert(Base\Arr::inFirst([1],$slice) === null);
        assert(Base\Arr::inFirst(['é','e'],['É','e']) === 'e');
        assert(Base\Arr::inFirst(['é','e'],['É','e'],false) === 'é');

        // combine
        assert(Base\Arr::combine(['test'],['bla']) === ['test'=>'bla']);
        assert(Base\Arr::combine('test','bla') === ['test'=>'bla']);
        assert(Base\Arr::combine('test',['bla','bla']) === []);
        assert(Base\Arr::combine('',['bla']) === [''=>'bla']);
        assert(Base\Arr::combine(null,['bla']) === []);
        assert(Base\Arr::combine(['bla','ok'],true) === ['bla'=>true,'ok'=>true]);
        assert(Base\Arr::combine(['bla','ok'],null) === ['bla'=>null,'ok'=>null]);
        assert(Base\Arr::combine([['bla']],['bla']) === []);

        // uncombine
        $source = [[0],[1]];
        assert($x = Base\Arr::uncombine($source));
        assert(Base\Arr::combine(...$x) === $source);

        // range
        assert([0,2,4] === Base\Arr::range(0,5,2));
        assert([2] === Base\Arr::range(2,3,2));

        // shuffle
        $array = [1,'test'=>2,3];
        $array2 = Base\Arr::shuffle($array);
        assert(count($array2) === 3);
        assert(!Base\Arr::isIndexed($array2));
        assert(Base\Arr::isSequential(Base\Arr::shuffle($array,false)));
        assert(!Base\Arr::isSequential(Base\Arr::shuffle($array,true)));

        // reverse
        assert(Base\Arr::reverse([1,2,3],false) === [3,2,1]);
        assert(Base\Arr::reverse([1,2,3]) === [2=>3,1=>2,0=>1]);

        // getSortAscDesc
        assert(Base\Arr::getSortAscDesc(true) === 'asc');
        assert(Base\Arr::getSortAscDesc(false) === 'desc');
        assert(Base\Arr::getSortAscDesc(23) === null);

        // sort
        assert(Base\Arr::sort(['x'=>'a','b'=>'c','w'=>2],1) === ['b'=>'c','w'=>2,'x'=>'a']);
        assert(Base\Arr::sort(['x'=>'a','b'=>'c','w'=>2],2) === ['x'=>'a','w'=>2,'b'=>'c']);
        assert(Base\Arr::sort(['x'=>'a','b'=>'c','w'=>2],3) === ['w'=>2,'x'=>'a','b'=>'c']);
        assert(Base\Arr::sort(['x'=>'a','b'=>'c','w'=>2],4) === ['b'=>'c','x'=>'a','w'=>2]);

        // sortNumbersFirst
        assert(Base\Arr::sortNumbersFirst(['meh'=>2,2=>true,'test'=>'ok',0=>'what']) === ['what',2=>true,'meh'=>2,'test'=>'ok']);
        assert(Base\Arr::sortNumbersFirst(['meh'=>2,2=>true,'test'=>'ok',0=>'what'],false) === [2=>true,0=>'what','meh'=>2,'test'=>'ok']);

        // random
        assert(1 === count(Base\Arr::random([1,2,3],1)));
        assert(2 === count(Base\Arr::random([1,2,3],2)));
        assert(3 === count(Base\Arr::random([1,2,3],4)));

        // pad
        assert(Base\Arr::pad(5,true,[1,2,3]) === [1,2,3,true,true]);
        assert(Base\Arr::pad(2,true,[1,2,3]) === [1,2,3]);

        // flip
        assert(['test'=>0,'test2'=>1] === Base\Arr::flip(['test','test2']));
        assert(['test'=>'test','test2'=>'test2'] === Base\Arr::flip(['test'=>'test','test2'=>'test2']));
        assert([['test']] === Base\Arr::flip([['test']],false,true));
        assert(['test'=>['test','test2']] === Base\Arr::flip(['test'=>['test','test2']],false,true));
        assert(['test'=>['test','test2']] === Base\Arr::flip(['test'=>['test','test2']],['test'],true));
        assert([1=>'test'] === Base\Arr::flip([1=>'test'],true,1));

        // unique
        $array = [1,2,2,2,'2',[1],[2]];
        assert(count(Base\Arr::unique($array)) === 5);
        assert(count(Base\Arr::unique($array,true,true,true)) === 4);
        assert(count(Base\Arr::unique($array)) === 5);
        $arr = ['test','TEST','tEST','tést','TÉST'];
        assert(count(Base\Arr::unique($arr,true,false)) === 0);
        assert(count(Base\Arr::unique($arr,false,false)) === 2);

        // duplicate
        $arr = ['test','TEST','tEST','tést','TÉST'];
        assert(count(Base\Arr::duplicate($arr,true,false)) === 5);
        assert(count(Base\Arr::duplicate($arr,false,false)) === 3);
        assert(count(Base\Arr::duplicate($array)) === 2);
        assert(count(Base\Arr::duplicate($array,true)) === 3);

        // implode
        assert('test|test2' === Base\Arr::implode('|',['test'=>'test','test2']));
        assert('test|test2' === Base\Arr::implode('|',['test'=>'test','test2','test3'=>['test4']]));
        assert('test|test2' === Base\Arr::implode('|',['test'=>' test ','test2','test3'=>''],true,true));

        // implodeTrim
        assert(Base\Arr::implodeTrim('|',['test'=>' test ','test2','test3'=>'']) === 'test|test2|');

        // implodeClean
        assert(Base\Arr::implodeClean('|',['test'=>' test ','test2','test3'=>'']) === ' test |test2');

        // implodeTrimClean
        assert(Base\Arr::implodeTrimClean('|',['test'=>' test ','test2','test3'=>'']) === 'test|test2');

        // implodeKey
        assert(Base\Arr::implodeKey('|',':',['test'=>'test2','test2 '=>3,'test4'=>[],'test5'=>'']) === 'test:test2|test2 :3|test5:');
        assert(Base\Arr::implodeKey('|',':',['test'=>'test2','test2 '=>3,'test4'=>[],'test5'=>''],true) === 'test:test2|test2:3|test5:');
        assert(Base\Arr::implodeKey('|',':',['test'=>'test2','test2 '=>3,'test4'=>[],'test5'=>''],true,true) === 'test:test2|test2:3');

        // explode
        assert(['test','test2','test3','test4'] === Base\Arr::explode('|',['test|test2','test3|test4']));
        assert(['test'] === Base\Arr::explode('|',[2=>'test',['test2|test3']]));
        assert(['test','test2','test3','test4'] === Base\Arr::explode('|',['test | test2','test3 | | test4'],null,true,true));

        // explodekeyValue
        assert(Base\Arr::explodekeyValue(':',['test: what','james2: ok','test : new'],true,true) === ['test'=>'new','james2'=>'ok']);

        // fill
        assert(count(Base\Arr::fill(0,5)) === 6);
        assert(count(Base\Arr::fill(-2,10,1,5)) === 13);

        // fillKeys
        assert(count(Base\Arr::fillKeys(range(0,5))) === 6);

        // chunk
        $array = [1,2,3,4,5,6,7,8,'test'=>9];
        assert([[1,2],[3,4],[5,6],[7,8],[9]] === Base\Arr::chunk(2,$array,false));
        assert([[1,2],[2=>3,3=>4],[4=>5,5=>6],[6=>7,7=>8],['test'=>9]] === Base\Arr::chunk(2,$array,true));
        assert([$array] === Base\Arr::chunk(10,$array));

        // chunkGroup
        $array = [1,2,3,4,5,6,7,8,'test'=>9];
        assert([[1,2,3,4,5],[6,7,8,9]] === Base\Arr::chunkGroup(2,$array,false));
        assert([[1,2,3,4,5],[5=>6,6=>7,7=>8,'test'=>9]] === Base\Arr::chunkGroup(2,$array,true));

        // chunkMix
        $array = [1,2,3,4];
        assert([[1,4],[2],[3]] === Base\Arr::chunkMix(3,$array,false));
        assert([[1,3],[2,4]] === Base\Arr::chunkMix(2,$array,false));
        $array = [1,2,3,4,5,6,7,8,'test'=>9,10,11,12];
        assert([[1,4,7,10],[2,5,8,11],[3,6,9,12]] === Base\Arr::chunkMix(3,$array,false));
        assert(Base\Arr::chunkMix(3,$array,true) === [[1,3=>4,6=>7,8=>10],[1=>2,4=>5,7=>8,9=>11],[2=>3,5=>6,'test'=>9,10=>12]]);

        // chunkWalk
        $array = [1,'what',2,null,3,[],4,'test',null];
        assert(Base\Arr::chunkWalk(function($v) {
            if(is_numeric($v))
            return true;
            if($v === null)
            return false;
        },$array) === [[1,'what'],[2],[3,[]],[4,'test']]);

        // compareIn
        assert(Base\Arr::compareIn(['type'=>null],['type'=>'file']));
        assert(Base\Arr::compareIn(['type'=>['file','dir']],['type'=>'file']));
        assert(!Base\Arr::compareIn(['type'=>['file','dir']],['type'=>'dirs']));
        assert(Base\Arr::compareIn(['type'=>['file','dir'],'ok'=>'yes'],['type'=>'file','ok'=>'yes']));
        assert(!Base\Arr::compareIn(['type'=>['file','dir'],'ok'=>'yes'],['type'=>'file']));
        assert(!Base\Arr::compareIn(['type'=>['file','dir'],'ok'=>'yes'],['type'=>'file','ok'=>'yzes']));
        assert(Base\Arr::compareIn(['type'=>[['file'],'dir'],'ok'=>'yes'],['type'=>['file'],'ok'=>'yes']));

        // compareOut
        assert(!Base\Arr::compareOut(['type'=>null],['type'=>'dir']));
        assert(Base\Arr::compareOut(['type'=>['dir']],['type'=>'dir']));
        assert(!Base\Arr::compareOut(['type'=>['dir']],['type'=>['dir']]));
        assert(Base\Arr::compareOut(['type'=>[['dir']]],['type'=>['dir']]));
        assert(Base\Arr::compareOut(['type'=>['dir'],'basename'=>'test'],['type'=>'file','basename'=>'test']));
        assert(Base\Arr::compareOut(['type'=>['dir'],'basename'=>'test'],['type'=>'dir','basename'=>'test']));
        assert(!Base\Arr::compareOut(['type'=>['dir','file'],'basename'=>'test'],['type'=>'filez','basename'=>'testz']));
        assert(Base\Arr::compareOut(['type'=>['dir','file'],'basename'=>'test'],['type'=>'file','basename'=>'testz']));

        // hasSlices
        assert(Base\Arr::hasSlices(['test'=>2],['test'=>2,'ok'=>3]));
        assert(Base\Arr::hasSlices([],['test'=>2,'ok'=>3]));
        assert(Base\Arr::hasSlices(['ok'=>3,'test'=>2],['test'=>2,'ok'=>3]));
        assert(!Base\Arr::hasSlices(['ok'=>3,'test'=>3],['test'=>2,'ok'=>3]));
        assert(!Base\Arr::hasSlices(['TEST'=>'OKÉ'],['test'=>'oké','ok'=>2]));
        assert(Base\Arr::hasSlices(['TEST'=>'OKÉ'],['test'=>'oké','ok'=>2],false));

        // slice
        $slice = [1,2,'test'=>'ok',8,9,'james'=>1,'bla'=>true];
        assert(Base\Arr::slice('test','bla',$slice) === ['test'=>'ok',2=>8,3=>9,'james'=>1,'bla'=>true]);
        assert(Base\Arr::slice('bla','bla',$slice) === ['bla'=>true]);
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert(['test'=>'testv','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::slice('test','test3',$slice));
        $slice = ['test','bla'=>'test2','test3'];
        assert(Base\Arr::slice('bla',null,$slice) === ['bla'=>'test2']);
        assert(Base\Arr::slice('BLA',null,$slice) === []);
        $slice = [1,2,3,4];
        assert([1,2,3] === Base\Arr::slice(0,2,$slice));

        // sliceIndex
        $slice = [1,2,3,4];
        assert([1,2] === Base\Arr::sliceIndex(0,2,$slice));
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert(Base\Arr::sliceIndex(-2,2,$slice) === ['test2'=>'test2v','test3'=>'test3v']);
        $slice = ['test','bla'=>'test2','test3'];
        assert(Base\Arr::sliceIndex(1,0,$slice) === []);
        assert(Base\Arr::sliceIndex(0,2,$slice) === ['test','bla'=>'test2']);
        assert(Base\Arr::sliceIndex(0,-1,$slice) === ['test','bla'=>'test2']);
        assert(Base\Arr::sliceIndex(2,1,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(-1,1,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(-1,null,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(0,null,$slice) === ['test']);
        assert(Base\Arr::sliceIndex(-2,2,$slice) === ['bla'=>'test2',1=>'test3']);
        assert(Base\Arr::sliceIndex(1,0,$slice) === []);
        assert(Base\Arr::sliceIndex(0,2,$slice) === ['test','bla'=>'test2']);
        assert(Base\Arr::sliceIndex(0,-1,$slice) === ['test','bla'=>'test2']);
        assert(Base\Arr::sliceIndex(2,1,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(-1,1,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(-1,1,$slice) === [1=>'test3']);
        assert(Base\Arr::sliceIndex(0,null,$slice) === ['test']);
        $slice2 = [1,2,3,'test'=>4,5,6,7,8,'ok'=>'james',10];
        assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(3,6,$slice2));
        assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(3,6,$slice2));
        assert(Base\Arr::slice('test','ok',$slice2) === Base\Arr::sliceIndex(-7,6,$slice2));

        // sliceFirst
        $slice = ['test'=>'test','test2'=>'test2','test3'=>'test3'];
        assert(['test'=>'test'] === Base\Arr::sliceFirst($slice));

        // sliceLast
        assert(['test3'=>'test3'] === Base\Arr::sliceLast($slice));

        // sliceNav
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert(['test3'=>'test3v'] === Base\Arr::sliceNav('test2',1,$slice));
        assert('test3v' === current(Base\Arr::sliceNav('test2',1,$slice)));
        assert(null === Base\Arr::sliceNav('test2',4,$slice));
        assert('test' === key(Base\Arr::sliceNav('test2',-1,$slice)));
        assert('test2' === key(Base\Arr::sliceNav('test2',0,$slice)));

        // sliceNavIndex
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert(['test2'=>'test2v'] === Base\Arr::sliceNavIndex(0,1,$slice));
        assert(['test'=>'testv'] === Base\Arr::sliceNavIndex(-1,-2,$slice));

        // splice
        $array = [1,'test'=>2,3,4,5,6,'end'=>'what',8];
        assert(Base\Arr::splice('test','test',['test'=>2,'what'=>'ok']) === ['what'=>'ok']);
        assert(Base\Arr::splice(1,'end',$array,['bla']) === [1,'test'=>2,1=>'bla',5=>8]);
        assert(Base\Arr::splice('test',true,$array) === [1,3,4,5,6,'end'=>'what',8]);
        assert(Base\Arr::splice('test','end',$array) === [1,5=>8]);
        assert(Base\Arr::splice('test','end',$array,['james'=>'ok']) === [1,'james'=>'ok',5=>8]);
        assert(Base\Arr::splice('test','end',$array,['bla']) === [1,'bla',5=>8]);
        assert(Base\Arr::splice('test','end',[1,'test'=>2,3,4,5,6,'end'=>'what',8],['bla']) === [1,'bla',5=>8]);
        assert(Base\Arr::splice('test','end',[1,'test'=>2,3,4,5,6,'end'=>'what',8],['test'=>'OK']) === [1,'test'=>'OK',5=>8]);
        assert(Base\Arr::splice('testz',null,$array,['test'=>'OK']) === $array);
        assert(Base\Arr::splice('end','end',$array,['end'=>'what2']) === [1,'test'=>2,3,4,5,6,'end'=>'what2',5=>8]);
        assert(Base\Arr::splice('end',0,$array,['end'=>'what2']) === [1,'end'=>'what2',5=>8]);
        assert(Base\Arr::splice('end',5,$array,['TEST'=>'OK'],false) === [1,3,4,5,6,'TEST'=>'OK']);
        assert(Base\Arr::splice('END',5,$array,['TEST'=>'OK'],false) === [1,3,4,5,6,'TEST'=>'OK']);
        assert(Base\Arr::splice('Test',1,$array,['ENDz'=>'OK'],false) === [1,'ENDz'=>'OK',2=>4,3=>5,4=>6,'end'=>'what',5=>8]);
        assert(Base\Arr::splice('Test',1,$array,['END'=>'OK'],false) === [1,2=>4,3=>5,4=>6,'end'=>'what',5=>8]);

        // spliceIndex
        $array = [1,'test'=>2,3,4,5,6,'end'=>'what',8];
        assert(Base\Arr::spliceIndex(0,null,$array) === ['test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8]);
        assert(Base\Arr::spliceIndex(0,2,$array) === [1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8]);
        assert(Base\Arr::spliceIndex(-2,2,$array) === [1,'test'=>2,3,4,5,6]);
        assert(Base\Arr::spliceIndex(1000,1000,$array) === $array);

        // spliceFirst
        $array = [1,'test'=>2,3,4,5,6,'end'=>'what',8];
        assert(Base\Arr::spliceFirst($array) === ['test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8]);
        assert(Base\Arr::spliceFirst($array,['ohoh']) === ['ohoh','test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>8]);

        // spliceLast
        $array = [1,'test'=>2,3,4,5,6,'end'=>'what',8];
        assert(Base\Arr::spliceLast($array) === [1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what']);
        assert(Base\Arr::spliceLast($array,['beurp']) === [1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',5=>'beurp']);
        assert(Base\Arr::spliceLast($array,[6=>'beurp']) === [1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'what',6=>'beurp']);
        assert(Base\Arr::spliceLast($array,['end'=>'beurp']) === [1,'test'=>2,1=>3,2=>4,3=>5,4=>6,'end'=>'beurp']);

        // insert
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert([0=>'testa','test'=>'testv','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insert('test',['testa'],$slice));
        assert(['key'=>'testa','test'=>'testv','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insert('test',['key'=>'testa'],$slice));
        assert(['test'=>'testv','key'=>'testa','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insert('test2',['key'=>'testa'],$slice));
        assert(['testv','testa','test2v','test3v'] === Base\Arr::values(Base\Arr::insert('test2',['key'=>'testa'],$slice)));
        assert(['test'=>'testv','key'=>'testa'] === Base\Arr::unsetAfterCount(2,Base\Arr::insert('test2',['key'=>'testa'],$slice)));
        assert([] === Base\Arr::unsetAfterCount(0,Base\Arr::insert('test2',['key'=>'testa'],$slice)));
        assert(Base\Arr::insert('test2',['TEST3'=>'what'],$slice,false) === ['test'=>'testv','test2'=>'test2v','test3'=>'test3v']);
        assert(Base\Arr::insert('test3',['TEST2'=>'what'],$slice,false) === ['test'=>'testv','TEST2'=>'what','test3'=>'test3v']);
        $slice = [1,2,3,4];
        assert([0=>1,1=>4,2=>2,3=>3,4=>4] === Base\Arr::insert(1,[1=>4],$slice));
        assert([1,2,'test'=>'test',3,4] === Base\Arr::insert(2,['test'=>'test'],$slice));
        assert([1,2,'test',3,4] === Base\Arr::insert(2,['test'],$slice));
        assert($slice === Base\Arr::insert(800,['test'],$slice));
        $array = [2=>4,8=>12,12=>13];
        assert(Base\Arr::insert(3,['OK'],$array) === [2=>4,8=>12,12=>13]);

        // insertIndex
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert(Base\Arr::insertIndex(0,['testa'],$slice) === [0=>'testa','test'=>'testv','test2'=>'test2v','test3'=>'test3v']);
        assert(['key'=>'testa','test'=>'testv','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insertIndex(-3,['key'=>'testa'],$slice));
        assert(['test'=>'testv','key'=>'testa','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insertIndex(-2,['key'=>'testa'],$slice));
        assert(['test'=>'testv','test2'=>'test2v','key'=>'testa','test3'=>'test3v'] === Base\Arr::insertIndex(-1,['key'=>'testa'],$slice));
        assert(['key'=>'testa','test'=>'testv','test2'=>'test2v','test3'=>'test3v'] === Base\Arr::insertIndex(0,['key'=>'testa'],$slice));
        assert(Base\Arr::insertIndex(1000,['replace'=>true],$slice)['replace'] === true);

        // insertFirst
        $slice = [1,2,3,4];
        assert(Base\Arr::insertFirst([0],$slice) === [0,1,2,3,4]);
        assert(Base\Arr::insertFirst([0],$slice) === Base\Arr::prepend($slice,[0]));

        // insertLast
        $slice = [1,2,3,4];
        assert(Base\Arr::insertLast([0],$slice) === [1,2,3,0,4]);
        assert(Base\Arr::insertLast([0],$slice) !== Base\Arr::append($slice,[0]));

        // insertInOrder
        $array = [2=>4,8=>12,12=>13];
        assert(Base\Arr::insertInOrder([11=>'OK'],$array) === [2=>4,8=>12,11=>'OK',12=>13]);
        assert(Base\Arr::insertInOrder([11=>'OK',3=>'well'],$array) === [2=>4,3=>'well',8=>12,11=>'OK',12=>13]);
        $array = ['b'=>4,'d'=>12,'f'=>13];
        assert(Base\Arr::insertInOrder(['a'=>'begin','z'=>'end','c'=>'OK'],$array) === ['a'=>'begin','b'=>4,'c'=>'OK','d'=>12,'f'=>13,'z'=>'end']);
        assert(Base\Arr::insertInOrder(['a'=>'begin','z'=>'end','c'=>'OK'],[]) === ['a'=>'begin','c'=>'OK','z'=>'end']);

        // firstWithKey
        assert(['user'=>null] === Base\Arr::firstWithKey('user',['bla'],['user'=>null]));
        assert(null === Base\Arr::firstWithKey('user',[],[]));

        // firstWithValue
        assert(['user'] === Base\Arr::firstWithValue('user',['user']));
        assert(null === Base\Arr::firstWithValue('user',[['user']]));
        assert([['user']] === Base\Arr::firstWithValue(['user'],[['user']]));
        assert(null === Base\Arr::firstWithValue('user',[],[]));

        // indexFirst
        assert(Base\Arr::indexFirst([1,2,3]) === 0);
        assert(Base\Arr::indexFirst([]) === null);

        // indexLast
        assert(Base\Arr::indexLast([1,2,3]) === 2);
        assert(Base\Arr::indexLast([]) === null);

        // indexExists
        assert(Base\Arr::indexExists(1,[1,2,3]));
        assert(!Base\Arr::indexExists(4,[1,2,3]));

        // indexesExists
        assert(Base\Arr::indexesExists([0,1,2],[1,2,3]));
        assert(!Base\Arr::indexesExists([0,1,2,8],[1,2,3]));

        // indexesAre
        assert(Base\Arr::indexesAre([0,1,2],[1,2,3]));
        assert(!Base\Arr::indexesAre([0,1,3],[1,2,3]));
        assert(!Base\Arr::indexesAre([0,1,2,3],[1,2,3]));
        assert(!Base\Arr::indexesAre([0,1],[1,2,3]));

        // indexesFirst
        assert(Base\Arr::indexesFirst([4,1,2],[1,2,3]) === 1);
        assert(Base\Arr::indexesFirst([4,4,8],[1,2,3]) === null);

        // indexesFirstValue
        assert(Base\Arr::indexesFirstValue([4,1,2],[1,2,3]) === 2);

        // indexKey
        assert(Base\Arr::indexKey(-1,[1,2,'bla'=>3]) === 'bla');
        assert(Base\Arr::indexKey(1,[1,2,'bla'=>3]) === 1);
        assert(Base\Arr::indexKey(-100,[1,2,'bla'=>3]) === null);

        // indexesKey
        assert(Base\Arr::indexesKey([0,-1],[1,2,'bla'=>3]) === [0,2=>'bla']);
        assert(Base\Arr::indexesKey([1000,-1000],[1,2,'bla'=>3]) === [1000=>null,-1000=>null]);

        // indexSlice
        assert(Base\Arr::indexSlice(-1,[1,2,'bla'=>3]) === ['bla'=>3]);

        // indexesSlice
        assert(Base\Arr::indexesSlice([0,-1],[1,2,'bla'=>3]) === [1,'bla'=>3]);
        assert(Base\Arr::indexesSlice([0,-1,100],[1,2,'bla'=>3]) === [1,'bla'=>3]);

        // indexStrip
        assert(Base\Arr::indexStrip(-1,[1,2,'bla'=>3]) === [1,2]);

        // indexesStrip
        assert(Base\Arr::indexesStrip([0,-1],[1,2,'bla'=>3]) === [1=>2]);

        // indexNav
        assert(Base\Arr::indexNav(-1,-1,[0,1,2,3,4]) === 3);
        assert(Base\Arr::indexNav(-1,0,[0,1,2,3,4]) === 4);
        assert(Base\Arr::indexNav(0,1,[0,1,2,3,4]) === 1);
        assert(Base\Arr::indexNav(4,0,[0,1,2,3,4]) === 4);
        assert(Base\Arr::indexNav(4,1,[0,1,2,3,4]) === null);
        assert(Base\Arr::indexNav(0,-1,[0,1,2,3,4]) === null);

        // keyFirst
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert('test' === Base\Arr::keyFirst($slice));
        assert(null === Base\Arr::keyFirst([]));

        // keyLast
        assert('test3' === Base\Arr::keyLast($slice));
        assert(null === Base\Arr::keyLast([]));

        // ikey
        assert('TÉST' === Base\Arr::ikey('TésT',['TÉST'=>1,'tést'=>2,'test'=>3]));
        assert(null === Base\Arr::ikey('TésTzzz',['TÉST'=>1,'tést'=>2,'test'=>3]));

        // ikeys
        assert(['TÉST','tést'] === Base\Arr::ikeys('TésT',['TÉST'=>1,'tést'=>2,'test'=>3]));

        // keyExists
        assert(!Base\Arr::keyExists(['test'],['test'=>false,'test2'=>true]));
        assert(Base\Arr::keyExists('test',['test'=>false,'test2'=>true]));
        assert(!Base\Arr::keyExists('test',['TEST'=>false,'test2'=>true]));
        assert(Base\Arr::keyExists('test',['TEST'=>false,'test2'=>true],false));
        assert(Base\Arr::keyExists('tést',['TÉST'=>false,'test2'=>true],false,true));

        // keysExists
        assert(Base\Arr::keysExists(['test'],['test'=>false]));
        assert(Base\Arr::keysExists([0,'test'],[0=>null,'test'=>false]));
        assert(!Base\Arr::keysExists([1,'test'],[0=>null,'test'=>false]));
        assert(!Base\Arr::keysExists(['test','TEST2'],['TEST'=>false,'test2'=>true]));
        assert(Base\Arr::keysExists(['test'],['TEST'=>false,'test2'=>true],false));
        assert(Base\Arr::keysExists(['tést'],['TÉST'=>false,'test2'=>true],false,true));

        // keysAre
        assert(Base\Arr::keysAre(['test','test2'],['test'=>false,'test2'=>true]));
        assert(!Base\Arr::keysAre(['test','test3'],['test'=>false,'test2'=>true]));
        assert(!Base\Arr::keysAre(['test'],['test'=>false,'test2'=>true]));
        assert(!Base\Arr::keysAre(['TEST','TEST2'],['test'=>false,'test2'=>true]));
        assert(Base\Arr::keysAre(['TEST','TEST2'],['test'=>false,'test2'=>true],false));
        assert(Base\Arr::keysAre(['TEST'],['test'=>false,'TESt'=>true],false));

        // keysFirst
        assert('test2' === Base\Arr::keysFirst(['test2','test'],['test'=>1,'test2'=>2]));
        assert(null === Base\Arr::keysFirst(['test3','test4'],['test'=>1,'test2'=>2]));
        assert('test2' === Base\Arr::keysFirst(['test3','test4','test2'],['test'=>1,'test2'=>2]));
        assert('test2' === Base\Arr::keysFirst(['test3','TEST','test2'],['test'=>1,'test2'=>2]));
        assert('TEST' === Base\Arr::keysFirst(['test3','TEST','test2'],['test'=>1,'test2'=>2],false));

        // keysIndexesFirst
        $array = ['test'=>'james','ok','lol'];
        assert(Base\Arr::keysIndexesFirst(['test',1],$array) === 'test');
        assert(Base\Arr::keysIndexesFirst(['TEST',1],$array,false) === 'TEST');
        assert(Base\Arr::keysIndexesFirst(['james2',1],$array) === 0);

        // keysFirstValue
        assert(2 === Base\Arr::keysFirstValue(['test2','test'],['test'=>1,'test2'=>2]));
        assert(1 === Base\Arr::keysFirstValue(['test3','TEST','test2'],['test'=>1,'test2'=>2],false));
        assert(null === Base\Arr::keysFirstValue(['test3'],['test'=>1,'test2'=>2],false));

        // keysIndexesFirstValue
        $array = ['test'=>'james','ok','lol'];
        assert(Base\Arr::keysIndexesFirstValue(['test',1],$array) === 'james');
        assert(Base\Arr::keysIndexesFirstValue(['TEST',1],$array,false) === 'james');
        assert(Base\Arr::keysIndexesFirstValue(['james2',1],$array) === 'ok');

        // keyIndex
        assert(1 === Base\Arr::keyIndex('test2',['test'=>'test','test2'=>true]));
        assert(!Base\Arr::keyIndex('test3',['test'=>'test','test2'=>true]));
        assert(!Base\Arr::keyIndex(true,['test'=>'test','test2'=>true]));
        $slice = [1,2,'test'=>'ok',8,9,'james'=>1,'bla'=>true];
        assert(Base\Arr::keyIndex('test',$slice) === 2);
        assert(Base\Arr::keyIndex('testz',$slice) === null);
        assert(Base\Arr::keyIndex('TÉST',['test'=>1,'tést'=>2],false) === 1);

        // keysIndex
        assert(['test2'=>1] === Base\Arr::keysIndex(['test2'],['test'=>'test','test2'=>true]));
        assert(['test'=>0,'test2'=>1] === Base\Arr::keysIndex(['test','test2'],['test'=>'test','test2'=>true]));
        assert(['test'=>0,'test2'=>1,'WHAT'=>null] === Base\Arr::keysIndex(['test','test2','WHAT'],['test'=>'test','test2'=>true]));
        assert(['TEST'=>0,'test2'=>1,'WHAT'=>null] === Base\Arr::keysIndex(['TEST','test2','WHAT'],['test'=>'test','test2'=>true],false));

        // keySlice
        $slice = ['test'=>'testv',2=>'test2v','test3'=>'test3v'];
        assert(['test3'=>'test3v'] === Base\Arr::keySlice('test3',$slice));
        assert([2=>'test2v'] === Base\Arr::keySlice('2',$slice));
        assert([2=>'test2v'] === Base\Arr::keySlice(2,$slice));
        assert([] === Base\Arr::keySlice(9,$slice));
        assert([] === Base\Arr::keySlice('test',['TEST'=>2]));
        assert(['test'=>2] === Base\Arr::keySlice('test',['TEST'=>2],false));

        // keysSlice
        $slice = ['test'=>'test','test2'=>'test2','test3'=>'test3'];
        assert(['test'=>'test','test2'=>'test2'] === Base\Arr::keysSlice(['test','test2'],$slice));
        assert(['test3'=>'test3','test'=>'test'] === Base\Arr::keysSlice(['test3','test'],$slice));
        assert(['test3'=>'test3','test'=>'test'] === Base\Arr::keysSlice(['test3','test','WHAT'],$slice));
        assert(['tést'=>2] === Base\Arr::keysSlice(['tést'],['TÉST'=>2],false,true));

        // ikeySlice
        assert(['TÉST'=>1,'tést'=>2] === Base\Arr::ikeySlice('TésT',['TÉST'=>1,'tést'=>2,'test'=>3]));

        // keyStrip
        $slice = ['test'=>'testv',2=>'test2v','test3'=>'test3v'];
        assert(['test'=>'testv',2=>'test2v'] === Base\Arr::keyStrip('test3',$slice));
        assert(['tÉST2'=>3] === Base\Arr::keyStrip('TÉST',['tést'=>1,'TÉst'=>2,'tÉST2'=>3],false));

        // keysStrip
        $slice = ['test'=>'test','test2'=>'test2','test3'=>'test3'];
        assert(['test3'=>'test3'] === Base\Arr::keysStrip(['test','test2'],$slice));
        assert(['tÉST2'=>3] === Base\Arr::keysStrip(['TÉST'],['tést'=>1,'TÉst'=>2,'tÉST2'=>3],false));
        assert([] === Base\Arr::keysStrip(['TÉST','tést2'],['tést'=>1,'TÉst'=>2,'tÉST2'=>3],false));
        
        // keyNav
        assert(Base\Arr::keyNav('test',1,['test'=>2,'test2'=>true,'tres'=>'ok']) === 'test2');
        assert(Base\Arr::keyNav('test',3,['test'=>2,'test2'=>true,'tres'=>'ok']) === null);
        assert(Base\Arr::keyNav('tres',-1,['test'=>2,'test2'=>true,'tres'=>'ok']) === 'test2');

        // keysStart
        $array = ['test'=>2,'test_fr'=>2,'test_en'=>3,'bla_en'=>5];
        assert(['test'=>2,'test_fr'=>2,'test_en'=>3] === Base\Arr::keysStart('test',$array));
        assert(['bla_en'=>5] === Base\Arr::keysStart('bla',$array));
        assert([] === Base\Arr::keysStart('BLA',$array));
        assert(['bla_en'=>5] === Base\Arr::keysStart('BLA',$array,false));

        // keysEnd
        $array = ['test'=>2,'test_fr'=>2,'test_en'=>3,'bla_en'=>5];
        assert(['test_en'=>3,'bla_en'=>5] === Base\Arr::keysEnd('en',$array));
        assert(['test_fr'=>2] === Base\Arr::keysEnd('fr',$array));
        assert([] === Base\Arr::keysEnd('FR',$array));
        assert(['test_fr'=>2] === Base\Arr::keysEnd('FR',$array,false));

        // keysMap
        $array = ['test'=>2,'james'=>'OK',2];
        assert(Base\Arr::keysMap(function($k,$z) {
            return $k.'bla'.$z;
        },$array,false,'ok') === ['testblaok'=>2,'jamesblaok'=>'OK','0blaok'=>2]);

        // keysChangeCase
        assert(Base\Arr::keysChangeCase(CASE_UPPER,$array) === ['TEST'=>2,'JAMES'=>'OK',2]);
        assert(Base\Arr::keysChangeCase(CASE_LOWER,$array) === ['test'=>2,'james'=>'OK',2]);
        assert(Base\Arr::keysChangeCase('ucfirst',$array) === ['Test'=>2,'James'=>'OK',2]);
        
        // keysLower
        $array = [1=>'no',1.2=>'ok','1.2'=>'ok','test'=>'no','TEST'=>'no','tEST'=>'ok','TÉST'=>'mb'];
        assert(Base\Arr::keysLower($array,false) === [1=>'ok','1.2'=>'ok','test'=>'ok','tÉst'=>'mb']);
        assert(Base\Arr::keysLower($array,true) === [1=>'ok','1.2'=>'ok','test'=>'ok','tést'=>'mb']);

        // keysUpper
        $array = [1=>'no',1.2=>'ok','1.2'=>'ok','test'=>'no','TEST'=>'no','tEST'=>'ok','téST'=>'mb'];
        assert(Base\Arr::keysUpper($array,false) === [1=>'ok','1.2'=>'ok','TEST'=>'ok','TéST'=>'mb']);
        assert(Base\Arr::keysUpper($array,true) === [1=>'ok','1.2'=>'ok','TEST'=>'ok','TÉST'=>'mb']);

        // keysInsensitive
        $array = ['NO'=>1,'no'=>2,'yes'=>3,'YES'=>4,'james'=>true,2=>'test','3'=>'ok'];
        assert(Base\Arr::keysInsensitive($array) === ['no'=>2,'YES'=>4,'james'=>true,2=>'test',3=>'ok']);

        // keysWrap
        $array = ['test'=>'test','!test2'=>'test2!'];
        assert(['!test!'=>'test','!!test2!'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,0));
        assert(['!test'=>'test','!!test2'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,3));
        $array = ['test'=>'test','!test2'=>'test2!'];
        assert(['!test!'=>'test','!test2!'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,1));
        assert(['!test!'=>'test','!!test2!'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,2));
        assert(['!test'=>'test','!test2'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,4));
        $array = ['test'=>'test','!test2!'=>'test2!'];
        assert(['test!'=>'test','!test2!!'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,5));
        assert(['test!'=>'test','!test2!'=>'test2!'] === Base\Arr::keysWrap('!',null,$array,6));

        // keysUnwrap
        $array = ['test'=>'test','!test2!'=>'test2!'];
        assert(['test'=>'test','test2'=>'test2!'] === Base\Arr::keysUnwrap('!',null,$array));
        assert(['test'=>'test','test2'=>'test2!'] === Base\Arr::keysUnwrap('!',null,$array,1));
        assert(['test'=>'test','test2!'=>'test2!'] === Base\Arr::keysUnwrap('!',null,$array,2));
        assert(['test'=>'test','!test2'=>'test2!'] === Base\Arr::keysUnwrap('!',null,$array,3));

        // keysReplace
        assert(['test2','lapa'=>[2]] === Base\Arr::keysReplace(['test'=>'lapa'],['test'=>[2],'test2']));
        assert(['lapa'=>[2]] === Base\Arr::keysReplace(['test'=>'lapa'],['test'=>[2],'lapa'=>true]));
        assert(['test'=>[2],'lapa'=>true] === Base\Arr::keysReplace(['TEST'=>'lapa'],['test'=>[2],'lapa'=>true]));
        assert(['lapa'=>[2]] === Base\Arr::keysReplace(['TEST'=>'lapa'],['test'=>[2],'lapa'=>true],false));

        // keysChange
        assert(['zla'=>'test','zla2'=>'test2'] === Base\Arr::keysChange(['bla'=>'zla','bla2'=>'zla2'],['bla'=>'test','bla2'=>'test2']));

        // keysMissing
        $fill = [0=>'zero',3=>'test',5=>'test9'];
        assert([0=>'zero',1=>false,2=>false,3=>'test',4=>false,5=>'test9'] === Base\Arr::keysMissing($fill));
        assert([0=>'zero',1=>true,2=>true,3=>'test',4=>true,5=>'test9'] === Base\Arr::keysMissing($fill,true));
        $fill = [0=>1];
        assert([0=>1] === Base\Arr::keysMissing($fill,true));
        assert([] === Base\Arr::keysMissing([],true));
        $fill = [2=>2,5=>5];
        assert([0=>null,1=>null,2=>2,3=>null,4=>null,5=>5] === Base\Arr::keysMissing($fill,null,0));
        assert([2=>2,3=>null,4=>null,5=>5] === Base\Arr::keysMissing($fill,null));

        // keysReindex
        assert(Base\Arr::keysReindex([2=>3,3=>2,'test'=>'ok',4,'8'=>true]) === [3,2,'test'=>'ok',4,true]);
        assert(Base\Arr::keysReindex(['test','test2'],2) === [2=>'test',3=>'test2']);

        // keysSort
        $sort = ['z'=>'test','b'=>'test','c'=>['z'=>'a','b'=>'b']];
        $sort = Base\Arr::keysSort($sort,true);
        assert(['b'=>'test','c'=>['z'=>'a','b'=>'b'],'z'=>'test'] === $sort);
        $sort = ['z'=>'test','b'=>'test','c'=>['z'=>'a','b'=>'b']];
        $sort = Base\Arr::keysSort($sort,true);
        assert(['b'=>'test','c'=>['z'=>'a','b'=>'b'],'z'=>'test'] === $sort);

        // keyRandom
        assert(1 === strlen((string) Base\Arr::keyRandom([1,2,3])));

        // valuesAre
        $slice = ['test','TEST','a','TÉST','tést'];
        assert(Base\Arr::valuesAre(['test','a','TÉST'],$slice,false));
        $array = [true,2=>false,'2',false,[2]];
        assert(Base\Arr::valuesAre([true,false,[2],'2'],$array));
        assert(!Base\Arr::valuesAre([[2],'2'],$array));
        assert(!Base\Arr::valuesAre([true,false,[2],2],$array));
        $slice = ['1',2,[2],'test'];
        assert(!Base\Arr::valuesAre([1,2,'test',[2]],$slice));

        // valueFirst
        $slice = ['test'=>'testv','test2'=>'test2v','test3'=>'test3v'];
        assert('testv' === Base\Arr::valueFirst($slice));
        assert(null === Base\Arr::valueFirst([]));

        // valueLast
        assert('test3v' === Base\Arr::valueLast($slice));
        assert(null === Base\Arr::valueLast([]));

        // valueIndex
        $array = [true,2=>false,true,false,true];
        assert(Base\Arr::valueIndex(true,$array) === [0,2,4]);
        assert(Base\Arr::valueIndex(false,$array) === [1,3]);
        $slice = ['1',2,[2],'test'];
        assert(Base\Arr::valueIndex(1,$slice) === []);
        $slice = ['É','testé','TEST','tEst'];
        assert(Base\Arr::valueIndex('test',$slice,false) === [2,3]);
        assert(Base\Arr::valueIndex('testÉ',$slice,false) === [1]);

        // valuesIndex
        $array = [true,2=>false,true,false,true];
        assert(Base\Arr::valuesIndex([true],$array) === [0,2,4]);
        assert(Base\Arr::valuesIndex([false],$array) === [1,3]);
        assert(Base\Arr::valuesIndex([false,true],$array) === [1,3,0,2,4]);
        $slice = ['1',2,[2],'test'];
        assert(Base\Arr::valuesIndex([1],$slice) === []);
        $slice = ['É','testé','TEST','tEst'];
        assert(Base\Arr::valuesIndex(['test'],$slice,false) === [2,3]);
        assert(Base\Arr::valuesIndex(['testÉ'],$slice,false) === [1]);

        // valueKey
        $array = [true,2=>false,true,false,true];
        assert(Base\Arr::valueKey(true,$array) === [0,3,5]);
        assert(Base\Arr::valueKey(false,$array) === [2,4]);
        $array = [true,2=>false,true,false,true,1];
        assert([1] === Base\Arr::valueKey(2,['ok',2,'test'=>'ok']));
        assert([0,'test'] === Base\Arr::valueKey('ok',['ok',2,'test'=>'ok']));
        assert(Base\Arr::valueKey(null,['ok',2,'test'=>'ok']) === []);
        $slice = ['É','testé','TEST','tEst'];
        assert(Base\Arr::valueKey('test',$slice,false) === [2,3]);
        assert(Base\Arr::valueKey('testÉ',$slice,false) === [1]);

        // valuesAll
        assert(Base\Arr::valuesAll(null,['test'=>2,3]) === ['test'=>null,null]);

        // valuesKey
        $array = [true,2=>false,true,1=>false,true];
        assert(Base\Arr::valuesKey([false],$array) === [2,1]);
        assert(Base\Arr::valuesKey([false,true,false],$array) === [2,1,0,3,4]);
        $slice = ['1',2,[2],'test'];
        assert(Base\Arr::valuesKey([1],$slice) === []);
        $slice = ['É','testé','TEST'];
        assert(count(Base\Arr::valuesKey(['test','é'],$slice,false)) === 2);
        assert(count(Base\Arr::valuesKey(['test'],$slice)) === 0);

        // valueSlice
        $slice = ['1',2,[2],'test'];
        assert([1=>2] === Base\Arr::valueSlice(2,$slice));
        assert(Base\Arr::valueSlice(1,$slice) === []);
        $slice = ['É','testé','TEST'];
        assert(Base\Arr::valueSlice('é',$slice,false) === ['É']);

        // valuesSlice
        $slice = ['1',2,[2],'test'];
        assert(['1',2] === Base\Arr::valuesSlice(['1',2],$slice));
        assert($slice === Base\Arr::valuesSlice(['1',2,[2],'test'],$slice));
        assert(Base\Arr::valuesSlice([1],$slice) === []);
        $slice = ['É','testé','TEST'];
        assert(Base\Arr::valuesSlice(['é','test'],$slice,false) === ['É',2=>'TEST']);

        // valueStrip
        $slice = ['1',2,[2],'test'];
        assert([1=>2,2=>[2],3=>'test'] === Base\Arr::valueStrip('1',$slice));
        $slice = ['É','testé','TEST'];
        assert(Base\Arr::valueStrip('é',$slice,false) === [1=>'testé',2=>'TEST']);

        // valuesStrip
        $slice = ['1',2,[2],'test'];
        assert([1=>2,2=>[2],3=>'test'] === Base\Arr::valuesStrip(['1'],$slice));
        assert([2=>[2]] === Base\Arr::valuesStrip(['1',2,2,'test'],$slice));
        assert(Base\Arr::valuesStrip([1],$slice) === $slice);
        $slice = ['É','testé','TEST'];
        assert(Base\Arr::valuesStrip(['é'],$slice,false) === [1=>'testé',2=>'TEST']);

        // valueNav
        assert(Base\Arr::valueNav(2,-1,[0,1,2,3,4,5]) === 1);
        assert(Base\Arr::valueNav(2,2,[0,1,2,3,6,5]) === 6);
        assert(Base\Arr::valueNav(2,1,[0,1,2,3,2,5]) === 3);
        assert(Base\Arr::valueNav(2,1112,[0,1,2,3,6,5]) === null);
        assert(Base\Arr::valueNav(1112,1,[0,1,2,3,6,5]) === null);

        // valueRandom
        assert(1 === strlen((string) Base\Arr::valueRandom([1,2,3])));
        assert(null === (Base\Arr::valueRandom([])));

        // valuesChange
        $array = [true,[2,[true]]];
        assert([false,[2,[true]]] === Base\Arr::valuesChange(true,false,$array));
        $array = [true,true,[2,[true]]];
        assert([false,false,[2,[true]]] === Base\Arr::valuesChange(true,false,$array));
        assert([false,true,[2,[true]]] === Base\Arr::valuesChange(true,false,$array,1));
        assert([false,false,[2,[true]]] === Base\Arr::valuesChange(true,false,$array,2));

        // valuesReplace
        assert(['test'=>'lapa','lapa2'] === Base\Arr::valuesReplace(['test'=>'lapa'],['test'=>'test','test2']));
        assert(['test'=>'lapa','lapa2','2'] === Base\Arr::valuesReplace(['test'=>'lapa'],['test'=>'test','test2','2']));
        assert(['test'=>['test'],'lapa2','2'] === Base\Arr::valuesReplace(['test'=>'lapa'],['test'=>['test'],'test2','2']));
        assert(['test'=>['test'],'lapa2','2'] === Base\Arr::valuesReplace(['test'=>'lapa'],['test'=>['test'],'TEST2','2'],false));
        assert(['test'=>['test'],'TEST2','2'] === Base\Arr::valuesReplace(['test'=>'lapa'],['test'=>['test'],'TEST2','2'],true));

        // valuesSearch
        $array = ['test','okÉÉÉ','TEST','james',3];
        assert(Base\Arr::valuesSearch('test',$array,true) === ['test']);
        assert(Base\Arr::valuesSearch('test',$array,false) === ['test',2=>'TEST']);
        assert(Base\Arr::valuesSearch('okééé',$array,false) === [1=>'okÉÉÉ']);
        assert(Base\Arr::valuesSearch('3',$array,false) === [4=>3]);
        assert(Base\Arr::valuesSearch('what',$array,false) === []);
        assert(Base\Arr::valuesSearch('ja + me',$array,false) === []);
        assert(Base\Arr::valuesSearch('ja + me',$array,false,true,true,'+') === [3=>'james']);
        assert(Base\Arr::valuesSearch('Ja + me',$array,false,true,true,'+') === [3=>'james']);
        assert(Base\Arr::valuesSearch('Ja + me',$array,true,true,true,'+') === []);
        assert(Base\Arr::valuesSearch('ja + me',$array,false,true,true) === []);
        assert(Base\Arr::valuesSearch('ja me',$array,false,true,true) === [3=>'james']);

        // valuesSubReplace
        $array = ['string','string2','mon monde'];
        assert(Base\Arr::valuesSubReplace(0,2,'hahah',$array) === ['hahahring','hahahring2','hahahn monde']);
        $array = ['string','string2','mon monde',[]];
        assert(Base\Arr::valuesSubReplace(0,2,'hahah',$array) === ['hahahring','hahahring2','hahahn monde',[]]);

        // valuesStart
        assert(Base\Arr::valuesStart('/bla',['/bla2','/bla.jpg','asd']) === ['/bla2','/bla.jpg']);
        assert(Base\Arr::valuesStart('/zz',['/bla2','/bla.jpg','asd']) === []);

        // valuesEnd
        assert(Base\Arr::valuesEnd('sd',['/bla2','/bla.jpgsd','asd']) === [1=>'/bla.jpgsd',2=>'asd']);
        assert(Base\Arr::valuesEnd('zzz',['/bla2','/bla.jpgsd','asd']) === []);

        // valuesChangeCase
        assert(Base\Arr::valuesChangeCase('lower',[1,'TEST','test',['TEST']]) === [1,'test','test',['test']]);
        assert(Base\Arr::valuesChangeCase('strtoupper',[1,'TEST','test',['test']]) === [1,'TEST','TEST',['TEST']]);

        // valuesLower
        assert(Base\Arr::valuesLower([1,'TÉST','test',['TEST']]) === [1,'tést','test',['test']]);

        // valuesUpper
        assert(Base\Arr::valuesUpper([1,'TEST','tést',['test']]) === [1,'TEST','TÉST',['TEST']]);

        // valuesSliceLength
        assert(Base\Arr::valuesSliceLength(1,3,['a','blbbb',[],'OK']) === ['a',3=>'OK']);

        // valuesStripLength
        assert(Base\Arr::valuesStripLength(1,3,['a','blbbb',[],'OK']) === [1=>'blbbb']);

        // valuesTotalLength
        assert(Base\Arr::valuesTotalLength(1,['ab','blbbb',[],'OK']) === ['a']);
        assert(Base\Arr::valuesTotalLength(3,['ab','blbbb',[],'OK']) === ['ab']);
        assert(Base\Arr::valuesTotalLength(8,['ab','blbbb',[],'OK']) === ['ab','blbbb']);

        // valuesWrap
        assert(Base\Arr::valuesWrap(':',';',['test','ok',['james'],true,2]) === [':test;',':ok;',['james'],':1;',':2;']);
        assert(Base\Arr::valuesWrap(':',null,['james',':james'],4) === [':james',':james']);

        // valuesUnwrap
        assert(Base\Arr::valuesUnwrap(':',';',[':test;',':ok;',['james'],true,2]) === ['test','ok',['james'],true,2]);
        assert(Base\Arr::valuesUnwrap(':',null,[':james:','james:'],2) === ['james:','james:']);

        // valuesSort
        $sort = ['z'=>'test','b'=>'atest','c'=>'z',4,true,[]];
        assert(Base\Arr::valuesSort($sort,true) === [true,4,'atest','test','z']);
        assert(Base\Arr::valuesSort($sort,false) === ['z','test','atest',4,true]);

        // valuesSortKeepAssoc
        $sort = ['z'=>'test','b'=>'atest','c'=>'z',4,true,[]];
        assert(Base\Arr::valuesSortKeepAssoc($sort,true) === [1=>true,0=>4,'b'=>'atest','z'=>'test','c'=>'z']);
        assert(Base\Arr::valuesSortKeepAssoc($sort,false) === ['c'=>'z','z'=>'test','b'=>'atest',0=>4,1=>true]);

        // valuesExcerpt
        assert(Base\Arr::valuesExcerpt(5,['ok',9=>'sdasdadasdas',3,true,false,null]) === ['ok',9=>'sd...',10=>'3']);
        assert(Base\Arr::valuesExcerpt(5,['ok',9=>"L'hébergemnt",3,true,false,null])[9] === "L'...");

        // keysValuesLower
        assert(Base\Arr::keysValuesLower(['A'=>'É']) === ['a'=>'é']);

        // keysValuesUpper
        assert(Base\Arr::keysValuesUpper(['a'=>'é']) === ['A'=>'É']);

        // keysStrToArrs
        assert(Base\Arr::keysStrToArrs(['str'=>'test',['ok'],'james'=>[23,32],'ok'=>false])[0] === ['str','test']);
        assert(Base\Arr::keysStrToArrs(['str'=>'test',['ok'],'james'=>[23,32],'ok'=>false])[3] === ['ok',false]);

        // camelCaseParent
        $array = ['test','testJames','testJamesOk','testJamesLaVie'];
        assert(Base\Arr::camelCaseParent($array) === ['test'=>null,'testJames'=>'test','testJamesOk'=>'testJames','testJamesLaVie'=>'testJames']);
        assert(Base\Arr::camelCaseParent(array_reverse($array)) === ['testJamesLaVie'=>'testJames','testJamesOk'=>'testJames','testJames'=>'test','test'=>null]);
        $array = ['test','testJames','testJames','LalaOk','ok','testJames2','testJamesLavie_ok'];
        assert(Base\Arr::camelCaseParent($array) === ['test'=>null,'testJames'=>'test','LalaOk'=>null,'ok'=>null,'testJames2'=>'test','testJamesLavie_ok'=>'testJames']);

        // combination
        assert(count(Base\Arr::combination([3,2,6])) === 7);

        // methodSort
        // voir obj et classe sort pour test

        // methodSorts
        // voir obj et classe sorts pour test

        return true;
    }
}
?>