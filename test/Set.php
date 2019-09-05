<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// set
// class for testing Quid\Base\Set
class Set extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// isSeparatorStart
		assert(Base\Set::isSeparatorStart(',bla,bla'));
		assert(Base\Set::isSeparatorStart(','));
		assert(Base\Set::isSeparatorStart(',,a'));

		// isSeparatorEnd
		assert(Base\Set::isSeparatorEnd(',bla,bla,'));
		assert(!Base\Set::isSeparatorEnd(','));
		assert(Base\Set::isSeparatorEnd('/a1,,'));

		// hasSeparatorDouble
		assert(!Base\Set::hasSeparatorDouble(',bla,bla,'));
		assert(Base\Set::hasSeparatorDouble(',bla,,bla,'));
		assert(Base\Set::hasSeparatorDouble(',bla,bla,,'));
		assert(Base\Set::hasSeparatorDouble(',,,bla,bla,,'));

		// hasSegment
		assert(!Base\Set::hasSegment(',bla,bla,'));
		assert(Base\Set::hasSegment(',bla,[pl],'));

		// exist
		assert(Base\Set::exist(-1,'1,2,3'));
		assert(!Base\Set::exist(1000,'1,2,3'));

		// exists
		assert(Base\Set::exists([0,-1,-2],'1,2,3'));

		// in
		assert(Base\Set::in('1','1,2'));

		// ins
		assert(Base\Set::ins(['1','2'],'1,2,3'));
		assert(!Base\Set::ins(['a','B'],'A,B,C'));

		// isCount
		assert(Base\Set::isCount(2,'1,2'));

		// isMinCount
		assert(Base\Set::isMinCount(2,'1,2'));
		assert(!Base\Set::isMinCount(3,'1,2'));

		// isMaxCount
		assert(Base\Set::isMaxCount(2,'1,2'));
		assert(!Base\Set::isMaxCount(1,'1,2'));

		// sameCount
		$r = 'test,test2-test3,test4';
		assert(Base\Set::sameCount($r,'test,[key]-[key2],[key3]',$r,['1','2','3']));
		assert(!Base\Set::sameCount($r,'test,[key]-[key2],[key3]','bla'));
		assert(!Base\Set::sameCount($r,'test,[key]-[key2]'));

		// sameWithSegments
		assert(Base\Set::sameWithSegments('test,[key],[key2],[key3]','test,ok,léé,2'));
		assert(Base\Set::sameWithSegments('test,[key],[key2],[key3]','test,[key],[key2],[key3]'));
		assert(!Base\Set::sameWithSegments('test,[key],[key2],[key3],ok','test,ok,léé,2'));
		assert(!Base\Set::sameWithSegments('test,ok,léé,2','test,[key],[key2],[key3]'));

		// getSeparator
		assert(Base\Set::getSeparator() === ',');
		assert(Base\Set::getSeparator(1) === ', ');

		// getSensitive
		assert(Base\Set::getSensitive());

		// prepend
		assert(Base\Set::prepend('test,bla','ok','lala,yeah',['what,james','Ok']) === 'what,james,Ok,lala,yeah,ok,test,bla');

		// append
		assert(Base\Set::append('test,bla','ok','lala,yeah',['what,james','Ok']) === 'test,bla,ok,lala,yeah,what,james,Ok');

		// count
		assert(Base\Set::count('1,2') === 2);

		// get
		assert(Base\Set::get(1,'1,2,3') === '2');

		// gets
		assert(Base\Set::gets([0,-1],'1,2,3') === ['1',2=>'3']);
		assert(Base\Set::gets([100,-1],'1,2,3') === [100=>null,2=>'3']);

		// set
		assert(Base\Set::set(4,'bla','1,2,3,4,5,6') === '1,2,3,4,bla,6');
		assert(Base\Set::set(12,'bla','1,2,3,4,5,6') === '1,2,3,4,5,6,bla');

		// sets
		assert(Base\Set::sets([4=>'bla',2=>'za'],'1,2,3,4,5,6') === '1,2,za,4,bla,6');

		// unset
		assert(Base\Set::unset(-1,'1,2,3') === '1,2');

		// unsets
		assert(Base\Set::unsets([0,-1],'1,2,3') === '2');
		assert(Base\Set::unsets([0,1,-1,4],'1,2,3') === '');

		// slice
		assert(Base\Set::slice(0,2,'1,2,3') === ['1','2']);
		assert(Base\Set::slice(1,1,'1,2,3') === [1=>'2']);
		assert(Base\Set::slice(1,1,'1,2,3',['limit'=>2]) === [1=>'2,3']);
		assert(Base\Set::slice(0,2,['1','2','3']) === ['1','2']);

		// splice
		assert(Base\Set::splice(1,1,'1,2,3','A') === '1,A,3');
		assert(Base\Set::splice(1,2,'1,2,3','A') === '1,A');
		assert(Base\Set::splice(1,2,'1,2,3',['A','b']) === '1,A,b');
		assert(Base\Set::splice(1,1,'1,2,3','A') === '1,A,3');
		assert(Base\Set::splice(1,2,'1,2,3',['A','b']) === '1,A,b');
		assert(Base\Set::splice(1,2,['1','2','3'],['A','b']) === '1,A,b');

		// spliceFirst
		assert(Base\Set::spliceFirst('1,2,3') === '2,3');
		assert(Base\Set::spliceFirst('1,2,3','a') === 'a,2,3');

		// spliceLast
		assert(Base\Set::spliceLast('1,2,3') === '1,2');
		assert(Base\Set::spliceLast('1,2,3','c') === '1,2,c');

		// insert
		assert(Base\Set::insert(1,'A','1,2,3') === '1,A,2,3');
		assert(Base\Set::insert(1,['a','B'],'1,2,3') === '1,a,B,2,3');
		assert(Base\Set::insert(0,'A','1,2,3') === 'A,1,2,3');
		assert(Base\Set::insert(0,['A'],'1,2,3') === 'A,1,2,3');
		assert(Base\Set::insert(-1,'A','1,2,3') === '1,2,A,3');
		assert(Base\Set::insert(-1,['A'],'1,2,3') === '1,2,A,3');

		// str
		assert(Base\Set::str([1,2]) === '1,2');
		assert(Base\Set::str('1,2,',['clean'=>true]) === '1,2');

		// parse
		assert(Base\Set::parse([1,2],Base\Set::option()) === [1,2]);

		// arr
		assert(Base\Set::arr('1,2') === ['1','2']);
		assert(Base\Set::arr('1,,2',['clean'=>false]) === ['1','','2']);
		assert(Base\Set::arr('1,,2') === ['1','2']);
		assert(Base\Set::arr(['test','aaa',2],['sort'=>true,'case'=>'upper']) === ['2','AAA','TEST']);

		// prepareStr
		assert(Base\Set::prepareStr('1, 2,,',Base\Set::option()) === ['1','2']);

		// prepareArr
		assert(Base\Set::prepareArr(['1, james, ',2,3,null,4],Base\Set::option()) === ['1','james','2','3','4']);
		assert(Base\Set::prepareArr(['1',2,3,null,4],Base\Set::option()) === ['1','2','3','4']);

		// implode
		assert(Base\Set::implode(['1','2']) === '1,2');
		assert(Base\Set::implode(['1','','2']) === '1,,2');
		assert(Base\Set::implode(['Quid','Root','Test','TestBla'],['start'=>true]) === ',Quid,Root,Test,TestBla');
		assert(Base\Set::implode(['1','2']) === '1,2');
		assert(Base\Set::implode([1,2,3]) === '1,2,3');
		assert(Base\Set::implode([1,2,3],['start'=>true,'end'=>true]) === ',1,2,3,');
		assert(Base\Set::implode(['AAA','TEST'],['caseImplode'=>'lower']) === 'aaa,test');

		// first
		assert(Base\Set::first('1,2,3') === '1');

		// last
		assert(Base\Set::last('1,2,3') === '3');
		assert(Base\Set::last('') === null);

		// valueIndex
		assert(Base\Set::valueIndex('2','1,2,2,3') === [1,2]);

		// valuesIndex
		assert(Base\Set::valuesIndex(['2','3'],'1,2,2,3') === [1,2,3]);

		// valueSlice
		assert(Base\Set::valueSlice('2','1,2,2,3') === [1=>'2',2=>'2']);
		assert(Base\Set::valueSlice('a','A,B,2,3') === []);

		// valuesSlice
		assert(Base\Set::valuesSlice(['1','2'],'1,2,2,3') === ['1','2','2']);

		// valueStrip
		assert(Base\Set::valueStrip('2','1,2,2,3') === '1,3');

		// valuesStrip
		assert(Base\Set::valuesStrip(['2','3'],'1,2,2,3') === '1');
		assert(Base\Set::valuesStrip(['2','3'],['1','2','2','3']) === '1');

		// valuesChange
		assert(Base\Set::valuesChange('2','4','1,2,2,3') === '1,4,4,3');
		assert(Base\Set::valuesChange('2','4','1,2,2,3',1) === '1,4,2,3');
		assert(Base\Set::valuesChange('2','4','1,2,2,2',2) === '1,4,4,2');

		// valuesReplace
		assert(Base\Set::valuesReplace(['test'=>'bla'],'1,test,2,3') === '1,bla,2,3');

		// sliceLength
		assert(Base\Set::sliceLength(1,2,'bla,a,z,q') === 'a,z,q');
		assert(Base\Set::sliceLength(1,2,['bla','a','z','q']) === 'a,z,q');

		// stripLength
		assert(Base\Set::stripLength(1,2,'bla,a,z,q') === 'bla');
		assert(Base\Set::stripLength(3,4,'bla,a,z,q') === 'a,z,q');

		// totalLength
		assert(Base\Set::totalLength(10,'blaas,aasdas,z,asdasdq') === 'blaas,z');
		assert(Base\Set::totalLength(10,'blaasdsaasddasdsadas,aasdas,z,asdasdq') === 'blaasdsaas');
		assert(Base\Set::totalLength(5,'bla') === 'bla');
		assert(Base\Set::totalLength(2,['bla']) === 'bl');

		// getSegments
		assert(Base\Set::getSegments('test,[key],[key2],[key3]','test,ok,léé,2') === ['key'=>'ok','key2'=>'léé','key3'=>'2']);
		assert(Base\Set::getSegments('test,[key],[key2],[key3]','test,ok,léé,2,lol') === null);

		// stripWrap
		assert('test,' === Base\Set::stripWrap(',test',false,true));
		assert(',test,' === Base\Set::stripWrap(',test',true,true));

		// stripStart
		assert('test' === Base\Set::stripStart(',test'));

		// stripEnd
		assert(',test' === Base\Set::stripEnd(',test'));
		assert(',test' === Base\Set::stripEnd(',test,'));

		// wrapStart
		assert(',test,' === Base\Set::wrapStart(',test,'));

		// wrapEnd
		assert(',test,' === Base\Set::wrapEnd(',test'));
		assert(',test,' === Base\Set::wrapEnd(',test'));

		// onSet
		assert(Base\Set::onSet('lala,2,3') === 'lala,2,3');
		assert(Base\Set::onSet(['test','3',3]) === 'test,3,3');

		// onGet
		assert(Base\Set::onGet('lala,2,3') === ['lala',2,3]);
		assert(Base\Set::onGet(['test',2,3]) === ['test',2,3]);

		// option
		assert(count(Base\Set::option()) === 11);

		return true;
	}
}
?>