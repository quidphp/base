<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// arrs
// class for testing Quid\Base\Arrs
class Arrs extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// typecast
		$y = 2;
		Base\Arrs::typecast($y);
		assert($y === [[2]]);
		$y = [2];
		Base\Arrs::typecast($y);
		assert($y === [[2]]);
		$y = [[2]];
		Base\Arrs::typecast($y);
		assert($y === [[2]]);

		// cast
		assert([[1,'true','false']] === Base\Arrs::cast([['1','true','false']]));

		// castMore
		assert([[1,[true],false]] === Base\Arrs::castMore([['1',['true'],'false']]));

		// is
		assert(!Base\Arrs::is([1,2,3]));
		assert(Base\Arrs::is([1,[],3]));
		assert(Base\Arrs::is([[1],[],[2]]));

		// isCleanEmpty
		assert(!Base\Arrs::isCleanEmpty(['',null]));
		assert(Base\Arrs::isCleanEmpty(['',['']]));
		assert(Base\Arrs::isCleanEmpty(['',[null]]));
		assert(!Base\Arrs::isCleanEmpty(['',[true]]));

		// hasKeyCaseConflict
		assert(Base\Arrs::hasKeyCaseConflict(['test'=>['test'=>true,'TEST'=>false]]));
		assert(!Base\Arrs::hasKeyCaseConflict(['test'=>['test'=>true,'TEST2'=>false]]));

		// merge
		$merge1 = ['test'=>'test','test2'=>[0=>'test3']];
		$merge2 = ['test'=>'test6','test2'=>[0=>'test3'],'test5'=>[0=>'test4']];
		assert(['test'=>['test','test6'],'test2'=>[0=>'test3',1=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2));
		$merge2['test2'][0] = 'test1000';
		assert(['test'=>['test','test6'],'test2'=>['test3','test1000'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2));
		assert(['test'=>['test','test6','test'],'test2'=>['test3','test1000','test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2,$merge1));
		$merge1 = ['test'=>1,'test2'=>[1,2,'ok'=>'james'],'test3'=>'ok','4'=>'ok','james'=>'what'];
		$merge2 = ['test'=>2,'test2'=>[9,'test'=>'ok'],'test3'=>'bla'];
		assert(['test'=>[1,2],'test2'=>[1,2,'ok'=>'james',9,'test'=>'ok'],'test3'=>['ok','bla'],'0'=>'ok','james'=>'what'] === Base\Arrs::merge($merge1,$merge2));
		$merge1 = ['test'=>'test','test2'=>[0=>'test3']];
		$merge2 = ['test'=>'test6','test2'=>[1=>'test3'],'test5'=>[0=>'test4']];
		assert(['test'=>['test','test6'],'test2'=>[0=>'test3',1=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2));
		$merge1 = ['test'=>'test','test2'=>['test'=>'test3']];
		$merge2 = ['test'=>'test6','test2'=>['test'=>'test1000',1=>'test3'],'test5'=>[0=>'test4']];
		assert(['test'=>['test','test6'],'test2'=>['test'=>['test3','test1000'],0=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2));
		assert(['test'=>['test','test6','test'],'test2'=>['test'=>['test3','test1000','test3'],0=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2,$merge1));
		$merge1 = ['test'=>'test','test2'=>['test3']];
		$merge2 = ['test'=>'test6','test2'=>['test1000',1=>'test3'],'test5'=>[0=>'test4']];
		assert(['test'=>['test','test6'],'test2'=>['test3','test1000',2=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2));
		assert(['test'=>['test','test6','test'],'test2'=>['test3','test1000','test3','test3'],'test5'=>[0=>'test4']] === Base\Arrs::merge($merge1,$merge2,$merge1));

		// replace
		$merge1 = ['test'=>'test','test2'=>[0=>'test3']];
		$merge2 = ['test'=>'test6','test2'=>[1=>'test3'],'test5'=>[0=>'test4']];
		assert(['test'=>'test6','test2'=>[0=>'test3',1=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::replace($merge1,$merge2));
		assert(['test'=>'test','test2'=>[0=>'test3',1=>'test3'],'test5'=>[0=>'test4']] === Base\Arrs::replace($merge1,$merge2,$merge1));
		assert(Base\Arrs::replace(['test'=>[0=>2,'test'=>'ok']],['test'=>[0=>2,'test2'=>'what']]) === ['test'=>[0=>2,'test'=>'ok','test2'=>'what']]);
		$merge1 = ['test'=>1,'test2'=>[1,2],'test3'=>'ok','4'=>'ok'];
		$merge2 = ['test'=>2,'test2'=>['test'=>'ok'],'test3'=>'bla'];
		assert(['test'=>2,'test2'=>[1,2,'test'=>'ok'],'test3'=>'bla','4'=>'ok'] === Base\Arrs::replace($merge1,$merge2));
		assert(Base\Arrs::replace(['test']) === ['test']);

		// replaceWithMode
		$merge1 = ['test'=>'test','test2'=>[2=>'well',1=>['bla','test'=>'ok']]];
		$merge2 = ['test'=>'test6','test2'=>[1=>['test3','test2'=>'ok2']],'test5'=>[0=>'test4']];
		assert(Base\Arrs::replaceWithMode([],$merge1,$merge2)['test2'][1] === ['test3','test'=>'ok','test2'=>'ok2']);
		assert(Base\Arrs::replaceWithMode(['test2'],$merge1,$merge2)['test2'][1] === ['test3','test2'=>'ok2']);
		assert(Base\Arrs::replaceWithMode(['test2'],$merge1,$merge2)['test2'][2] === 'well');
		assert(empty(Base\Arrs::replaceWithMode(['=test2'],$merge1,$merge2)['test2'][2]));

		// replaceSpecial
		$target = ['@dev','@app'];
		$all = ['@dev','@prod','@app'];
		$replaceKeys = [];
		$array = ['test'=>'OK','bla'=>'yeah','meh'=>'nop','@dev'=>['test'=>'MEH','meh'=>'ok']];
		$array2 = ['test'=>'OK2','@dev'=>['meh'=>'more'],'@prod'=>['test'=>'MEH2']];
		assert(Base\Arrs::replaceSpecial($target,$all,$replaceKeys,$array,$array2) === ['test'=>'OK2','bla'=>'yeah','meh'=>'more']);

		// clean
		$clean = ['',null,[]];
		assert([] === Base\Arrs::clean($clean));
		$clean = ['',null,['',null]];
		assert([] === Base\Arrs::clean($clean));
		$clean = ['',null,true,false];
		assert([2=>true,3=>false] === Base\Arrs::clean($clean));
		$clean = ['',null,true,[true,'s','']];
		assert([2=>true,3=>[true,'s']] === Base\Arrs::clean($clean));
		$array = [true,1,[false]];
		assert([true,1,2=>[false]] === Base\Arrs::clean($array));
		$array = [1,[]];
		assert([1] === Base\Arrs::clean($array));

		// trim
		assert(Base\Arrs::trim([' test ',['james '=>' test2 ']])[1]['james '] === 'test2');
		assert(Base\Arrs::trim([' test ',['james '=>' test2 ']],true)[1]['james'] === 'test2');

		// trimClean
		assert(Base\Arrs::trimClean([' test ',['james '=>' test2 ','',null]],true) === ['test',['james'=>'test2']]);
		assert(Base\Arrs::trimClean([' test ',['james '=>' test2 ','',null]],true,true,true,true) === ['test',['test2']]);

		// get
		$array = ['test'=>[2=>['test3'=>'oui','1t'=>'non']]];
		assert('oui' === Base\Arrs::get('test/2/test3',$array));
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']]];
		assert('oui' === Base\Arrs::get('test/test2/test3',$array));
		assert(null === Base\Arrs::get('test/test4/test2/test3',$array));
		assert(null === Base\Arrs::get('test/test2//test3',$array));
		assert('oui' === Base\Arrs::get(['test','test2','test3'],$array));
		assert(null === Base\Arrs::get(['test','test2/test3'],$array));
		assert(['test3'=>'oui','1t'=>'non'] === Base\Arrs::get(['test','test2'],$array));
		assert(null === Base\Arrs::get(['test','test8','test2'],$array));
		assert(Base\Arrs::get(['test'],$array)['test2']['test3'] === 'oui');
		$array = ['TEST'=>[2=>['TÉST'=>true]]];
		assert(Base\Arrs::get('test/2/tést',$array,false) === true);

		// gets
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']]];
		assert(['test/test2/test3'=>'oui','test/test2/1t'=>'non'] === Base\Arrs::gets([['test','test2','test3'],'test/test2/1t'],$array));
		assert(['test/test2/test3'=>'oui','ztest/test2/1t'=>null] === Base\Arrs::gets(['test/test2/test3','ztest/test2/1t'],$array));
		$array = ['TEST'=>[2=>['TÉST'=>true]]];
		assert(Base\Arrs::gets(['Test/2/tést'],$array,false,true) === ['Test/2/tést'=>true]);
		assert(Base\Arrs::gets([['tESt',2,'TÉST']],$array,false,true) === ['tESt/2/TÉST'=>true]);

		// indexPrepare
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']]];
		assert([0,0,1] === Base\Arrs::indexPrepare('0/0/1',$array));
		assert([0,0,1] === Base\Arrs::indexPrepare([0,0,-1],$array));
		assert([] === Base\Arrs::indexPrepare([0,0,-4,1],$array));
		assert(Base\Arrs::indexPrepare([-1,-1,-1],$array) === [0,0,1]);
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']],'test2'=>['test22'=>['test32'=>'oui2','1t'=>'non2']],'test3'=>['test23'=>['test33'=>'oui3','1t'=>'non3']]];
		assert(Base\Arrs::indexPrepare(-1000,$array) === [-1000]);
		assert(Base\Arrs::indexPrepare([0,-2],$array) === [0,-2]);
		assert(Base\Arrs::indexPrepare([2,-2],$array) === [2,-2]);
		assert(Base\Arrs::indexPrepare([0,-2,-1],$array) === []);
		assert(Base\Arr::indexPrepare(-1000,$array) === -1000);

		// keyPrepare
		assert(Base\Arrs::keyPrepare(1) === '1');
		assert(Base\Arrs::keyPrepare([1,2,3]) === '1/2/3');
		assert(Base\Arrs::keyPrepare(['test','/test2']) === 'test/test2');

		// keyPrepares
		assert(Base\Arrs::keyPrepares('test/test2',['test3','test4']) === 'test/test2/test3/test4');
		assert(Base\Arrs::keyPrepares(1,2,3,4) === '1/2/3/4');

		// keyExplode
		assert(Base\Arrs::keyExplode('test/test2') === ['test','test2']);
		assert(Base\Arrs::keyExplode('1/2') === ['1','2']);
		assert(Base\Arrs::keyExplode(2) === ['2']);
		assert(Base\Arrs::keyExplode([1,2]) === [1,2]);

		// index
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']]];
		assert('non' === Base\Arrs::index('0/0/1',$array));
		assert('non' === Base\Arrs::index('0/0/-1',$array));
		assert(null === Base\Arrs::index('0/0/2',$array));
		assert(null === Base\Arrs::index('0/0/-4/1',$array));
		assert(Base\Arrs::index([-1,-1,-1],$array) === 'non');

		// indexes
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>'non']]];
		assert(['-1/-1/-1'=>'non','0/0/0'=>'oui'] === Base\Arrs::indexes(['-1/-1/-1',[0,0,0]],$array));
		assert(['-1/-1/-1/-1'=>null,'0/0/0'=>'oui'] === Base\Arrs::indexes(['-1/-1/-1/-1',[0,0,0]],$array));
		assert(Base\Arrs::indexes([[-1,-1,-1]],$array) === ['-1/-1/-1'=>'non']);

		// climb
		assert(Base\Arrs::climb(['dev','app'],['dev'=>['app'=>false],'prod'=>false]) === false);
		$array2 = ['dev'=>['test','test2'],'prod'=>null];
		assert(['test','test2'] === Base\Arrs::climb(['dev','app','fr'],$array2));
		$array = ['test'=>['test2'=>['test3'=>'oui','1t'=>[0]]]];
		assert('oui' === Base\Arrs::climb('test/test2/test3',$array));
		assert([0] === Base\Arrs::climb('test/test2/1t',$array));
		assert($array === Base\Arrs::climb('test4/test4/test2/1t',$array));
		assert(['test2'=>['test3'=>'oui','1t'=>[0]]] === Base\Arrs::climb('test4/test4/test2/1t/test',$array));
		assert('oui' === Base\Arrs::climb(['test','test2','testz','test3'],$array));
		assert('oui' === Base\Arrs::climb(['testz','k'=>'test','test2','testz','test3'],$array));
		$array = ['dev'=>'test','dev2'=>'bla'];
		assert('test' === Base\Arrs::climb(['testz','k'=>'test','test2','testz','test3','dev','z'],$array));
		assert($array === Base\Arrs::climb(['asdasds'],$array));
		assert('test' === Base\Arrs::climb(['asdasds','dev'],$array));
		$array2 = ['dev'=>['app'=>['test'],'test2'],'prod'=>null];
		assert(Base\Arrs::climb(['DEV','APP','JAMES'],$array2) === $array2);
		assert(Base\Arrs::climb(['DEV','APP','JAMES'],$array2,false) === ['test']);
		assert(Base\Arrs::climb(['dev','app'],['dev'=>true]));
		assert(Base\Arrs::climb(['dev','app'],['dev'=>true,'prod'=>false]));
		assert(Base\Arrs::climb(['james','dev','app'],$array2) === ['test']);
		$array3 = ['james'=>['dev'=>['app'=>['test']],'test2'],'prod'=>null];
		assert(Base\Arrs::climb(['james','dev','app'],$array3) === ['test']);
		assert(Base\Arrs::climb(['dev','app'],$array3) === $array3);

		// climbReplaceMode
		$array = ['dev'=>['app'=>[2=>'test','bla'=>'test2']],'prod'=>null,'app'=>[4=>'NOP',2=>'testAPP'],'james'=>['OK']];
		assert(Base\Arrs::climbReplaceMode(['dev','app'],['dev','app','prod'],null,$array) === ['james'=>['OK'],4=>'NOP',2=>'test','bla'=>'test2']);
		assert(Base\Arrs::climbReplaceMode(['app','dev'],['dev','app','prod'],null,$array) === ['james'=>['OK'],4=>'NOP',2=>'test','bla'=>'test2']);
		$array = ['dev'=>['app'=>[2=>'test','bla'=>'test2']],'prod'=>null,'app'=>'NOP','james'=>['OK']];
		assert(Base\Arrs::climbReplaceMode(['dev','app'],['dev','app','prod'],null,$array) === 'NOP');
		$array = ['dev'=>['appz'=>['test','bla'=>'test2']],'prod'=>null,'app'=>'NOP','james'=>['OK']];
		assert(Base\Arrs::climbReplaceMode(['dev','app'],['dev','app','prod'],null,$array) === 'NOP');
		$array = ['dev'=>true,'prod'=>false];
		assert(Base\Arrs::climbReplaceMode(['dev'],['dev','app','prod'],null,$array) === true);
		assert(Base\Arrs::climbReplaceMode(['prod'],['dev','app','prod'],null,$array) === false);
		$array = ['dev'=>['app'=>null],'prod'=>null,'app'=>'NOP','james'=>['OK']];
		assert(Base\Arrs::climbReplaceMode(['dev','app'],['dev','app','prod'],[],$array) === 'NOP');
		$array = ['dev'=>['app'=>['lol'=>['TEST'],'bla'=>'test2']],'prod'=>null,'app'=>['lol'=>['NOP',1=>'MEH'],'zing'=>'lala'],'james'=>['OK']];
		assert(Base\Arrs::climbReplaceMode(['dev'],['dev','prod'],[],$array)['app']['lol'] === ['TEST','MEH']);
		assert(Base\Arrs::climbReplaceMode(['dev'],['dev','prod'],['app'],$array)['app']['lol'] === ['TEST']);
		assert(Base\Arrs::climbReplaceMode(['appz'],[],['app'],$array) === $array);

		// set
		$array = ['test'=>['test2'=>3]];
		assert(['test'=>['test2'=>4]] === Base\Arrs::set('test/test2',4,$array));
		assert(['test'=>4] === Base\Arrs::set('test',4,$array));
		assert(['test'=>['test2'=>['test3'=>4]]] === Base\Arrs::set(['test','test2','test3'],4,$array));
		assert(['test'=>['test2'=>['test3'=>['test4'=>[]]]]] === Base\Arrs::set('test/test2/test3/test4',[],$array));
		assert(['test'=>['test2'=>3,'test3'=>4]] === Base\Arrs::set('test/test3',4,$array));
		assert(['test'=>['test2'=>null]] === Base\Arrs::set('test/test2',null,$array));
		assert(['test'=>['test2'=>null]] === Base\Arrs::set('test/test2',null,$array,false));
		assert(['test'=>['test2'=>3],'z'=>['v'=>[2=>4]]] === Base\Arrs::set('z/v/2',4,$array));
		$array = ['Test'=>['test2'=>3]];
		assert(Base\Arrs::set('TEST/test1',true,$array,false) === ['Test'=>['test2'=>3,'test1'=>true]]);
		assert(Base\Arrs::set([null,null,'ok'],'VALUE',$array)[0][0]['ok'] === 'VALUE');
		assert(Base\Arrs::set([null,null,null],'VALUE',$array)[0][0][0] === 'VALUE');
		assert(Base\Arrs::set(null,'test',$array)[0] === 'test');
		assert(Base\Arrs::set(['Test',null,'james'],'test',$array)['Test'][0]['james'] === 'test');

		// sets
		$array = ['test'=>['test2'=>3]];
		assert(['test'=>['test2'=>4,'test3'=>1]] === Base\Arrs::sets(['test/test2'=>4,'test/test3'=>1],$array));
		assert(Base\Arrs::sets(['TEST/test3'=>true],$array,false) === ['test'=>['test2'=>3,'test3'=>true]]);

		// setRef
		$array = ['test'=>['test2'=>3]];
		Base\Arrs::setRef('test2','ok',$array);
		assert($array['test2'] === 'ok');
		Base\Arrs::setRef(null,'okz',$array);
		assert($array[0] === 'okz');
		Base\Arrs::setRef(['test',null,'ok'],'okz',$array);
		assert($array['test'][0]['ok'] === 'okz');

		// setsRef
		Base\Arrs::setsRef(['test3/test2'=>'ok'],$array);
		assert($array['test3']['test2'] === 'ok');

		// unset
		$array = ['test'=>['test2'=>3,'test3'=>'2']];
		assert([] === Base\Arrs::unset('test',$array));
		assert($array === Base\Arrs::unset('test4',$array));
		assert($array === Base\Arrs::unset('test4/test5',$array));
		assert(['test'=>['test3'=>'2']] === Base\Arrs::unset('test/test2',$array));
		assert(Base\Arrs::unset('TEST/TEST2',$array) === $array);
		assert(Base\Arrs::unset('TEST/TEST2',$array,false) === ['test'=>['test3'=>'2']]);

		// unsets
		$array = ['test'=>['test2'=>3,'test3'=>'2']];
		assert([] === Base\Arrs::unsets(['test'],$array));
		assert(['test'=>[]] === Base\Arrs::unsets(['test/test2','test/test3'],$array));
		assert(['test'=>[]] === Base\Arrs::unsets(['TEST/TEST2','TEST/TEST3'],$array,false));

		// unsetRef
		$array = ['test'=>['test2'=>3]];
		Base\Arrs::unsetRef(['test','test2'],$array);
		assert($array === ['test'=>[]]);

		// unsetsRef
		Base\Arrs::setRef('test2/test3','ok',$array);
		Base\Arrs::unsetsRef([['test2','test3']],$array);
		assert($array === ['test'=>[],'test2'=>[]]);

		// getSet
		$array = ['test'=>2];
		assert(2 === Base\Arrs::getSet('test',null,$array));
		assert($array === Base\Arrs::getSet(null,null,$array));
		assert(true === Base\Arrs::getSet('test4',44,$array));
		assert(44 === $array['test4']);
		assert(true === Base\Arrs::getSet(['test'=>2,'test3'=>4],null,$array));
		assert(4 === $array['test3']);
		assert(true === Base\Arrs::getSet('testa/23',44,$array));
		assert(44 === $array['testa'][23]);

		// count
		$arr = [[1,2],[2,3]];
		assert(6 === Base\Arrs::count($arr));

		// countLevel
		$arr = [[1,2],[2,3]];
		assert(2 === Base\Arrs::countLevel(0,$arr));
		assert(4 === Base\Arrs::countLevel(1,$arr));

		// depth
		assert(Base\Arrs::depth([[[[3,['lol']],'meh'],['OK']],['wll']]) === 5);
		assert(Base\Arrs::depth([[4],[5],[3]]) === 2);
		assert(Base\Arrs::depth([[4],[5,[2]],[3]]) === 3);
		assert(Base\Arrs::depth([[4],[5,[2]],[3,[3,[4]]]]) === 4);
		assert(Base\Arrs::depth([2]) === 1);

		// keys
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['meh'=>'OK',true]]]],'test2'=>'z'];
		assert(count(Base\Arrs::keys($array)) === 6);
		assert(count(Base\Arrs::keys($array,'JAMES')) === 0);
		assert(count(Base\Arrs::keys($array,true)) === 2);
		assert(empty(Base\Arrs::keys($array,'ok',true)));
		assert(!empty(Base\Arrs::keys($array,'ok',false)));

		// crush
		assert([1,2,3,4] === Base\Arrs::crush([1,2,3,4]));
		assert([1,2,'1/0'=>3,'1/1'=>4] === Base\Arrs::crush([[1,2],[3,4]]));
		assert(['test/test2'=>'bla','test2'=>'z'] === Base\Arrs::crush(['test'=>['test2'=>'bla'],'test2'=>'z']));
		assert(Base\Arrs::crush(['test'=>['test2'=>['test3'=>['ok'=>2,true,false]]],'test2'=>'z']) === ['test/test2/test3/ok'=>2,'test/test2/test3/0'=>true,'test/test2/test3/1'=>false,'test2'=>'z']);
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['meh'=>'MÉH',true]]]],'test2'=>'z'];
		assert(count(Base\Arrs::crush($array)) === 6);
		assert(count(Base\Arrs::crush($array,'JAMES')) === 0);
		assert(count(Base\Arrs::crush($array,true)) === 2);
		assert(count(Base\Arrs::crush($array,'méh',false)) === 1);

		// crushReplace
		assert([1,2,3,4] === Base\Arrs::crushReplace([1,2,3,4]));
		assert([3,4] === Base\Arrs::crushReplace([[1,2],[3,4]]));
		assert([3,4,'james'=>'notOk'] === Base\Arrs::crushReplace([[1,2,'test'=>['ok']],[3,4,'test'=>['james'=>'notOk']]]));
		assert(['ok',4,'x'=>3,'james'=>'notOk'] === Base\Arrs::crushReplace([[1,2,'test'=>['ok']],['x'=>3,1=>4,'test'=>['james'=>'notOk']]]));
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['meh'=>'MÉH',4=>true]]]],'test2'=>'z'];
		assert(count(Base\Arrs::crushReplace($array)) === 6);
		assert(count(Base\Arrs::crushReplace($array,true)) === 2);
		assert(count(Base\Arrs::crushReplace($array,2)) === 1);
		assert(count(Base\Arrs::crushReplace($array,'méh',false)) === 1);
		assert(count(Base\Arrs::crushReplace($array,'méh',true)) === 0);

		// values
		assert([0=>1,1=>2] === Base\Arrs::values([1=>1,2=>2]));
		assert([0=>1,1=>2,2=>['test']] === Base\Arrs::values([1=>1,2=>2,6=>[6=>'test']]));
		assert([['test']] === Base\Arrs::values([1=>1,2=>2,6=>[6=>'test']],'string'));
		assert([1,2,[]] === Base\Arrs::values([1=>1,2=>2,6=>[6=>'test']],'int'));

		// search
		$array = [['test'=>[2,3,4,'ok'=>[true]]]];
		$search = Base\Arrs::search(true,$array);
		assert(Base\Arrs::get($search,$array) === true);
		$search = Base\Arrs::search(2,$array);
		assert(Base\Arrs::get($search,$array) === 2);
		assert(Base\Arrs::search('bla',$array) === null);
		assert([1] === Base\Arrs::search(2,[1,2,'test'=>'ok']));
		assert(Base\Arrs::search('é',['a'=>['É']],false) === ['a',0]);

		// searchFirst
		$array = [true,2=>false,'2',false,'bla'=>[['james'=>['OK']]]];
		assert(Base\Arrs::searchFirst([false,true],$array) === [2]);
		assert(Base\Arrs::searchFirst(['2',[2]],$array) === [3]);
		assert(Base\Arrs::searchFirst(['OK','2',[2]],$array) === ['bla',0,'james',0]);
		assert(Base\Arrs::searchFirst(['é'],['a'=>['É']],false) === ['a',0]);

		// in
		assert(Base\Arrs::in(1,[0,[1]]));
		assert(Base\Arrs::in(0,[0,1]));
		assert(Base\Arrs::in('b',['A','b'],false));
		assert(Base\Arrs::in('a',[['A'],'b'],false));
		assert(Base\Arrs::in('é',[['É'],'b'],false));
		assert(!Base\Arrs::in('a',[['A'],'b']));

		// ins
		assert(Base\Arrs::ins([1],[0,[1]]));
		assert(Base\Arrs::ins([0,1],[[0],1]));
		assert(Base\Arrs::ins([[0]],[[0]]));
		assert(!Base\Arrs::ins([0,1,2],[[0],[1]]));
		assert(!Base\Arrs::ins(['1'],[0,1]));
		assert(!Base\Arrs::ins(['a','b'],['A','b']));
		assert(Base\Arrs::ins(['a','b'],[['A'],'b'],false));
		assert(Base\Arrs::ins(['a'],['A','b'],false));
		assert(Base\Arrs::ins(['a'],[[['A']],'b'],false));

		// inFirst
		$array = [[true],[2=>false,'2','A'],[false],[[2]]];
		assert(Base\Arrs::inFirst([false,true],$array) === false);
		assert(Base\Arrs::inFirst(['2',[2]],$array) === '2');
		assert(Base\Arrs::inFirst([null,'2',[2]],$array) === '2');
		assert(Base\Arrs::inFirst(['a'],$array) === null);
		assert(Base\Arrs::inFirst(['a'],$array,false) === 'a');
		$slice = ['1',2,[2],'test'];
		assert(Base\Arrs::inFirst([1],$slice) === null);
		assert(Base\Arrs::inFirst(['B'],[[['A']],'b'],false) === 'B');

		// map
		$array = [' test ',2=>[0=>' test2 ',1=>'test2']];
		assert(['test',2=>[0=>'test2',1=>'test2']] === Base\Arrs::map('trim',$array));
		$array = Base\Arrs::map(function($a,$b,$c) { assert(is_array($c)); return true; },$array);
		assert($array = [true,2=>true]);

		// walk
		$array = ['test',[2],[4],'test4'];
		Base\Arrs::walk(function(&$v,$k,$extra) {
			if(is_int($v))
			$v += 1000;
			else
			$v .= $extra;
		},$array,'bla');
		assert($array === ['testbla',[1002],[1004],'test4bla']);

		// shuffle
		$array = Base\Arrs::shuffle([1,'test'=>2,3,['key'=>'bla','what',[1,2,3,4,5]]]);
		assert(count($array) === 4);
		assert($array[2]['key'] === 'bla');

		// reverse
		$array = [1,'test'=>2,3];
		assert(Base\Arrs::reverse([1,2,3],false) === [3,2,1]);
		assert(Base\Arrs::reverse([1,2,3]) === [2=>3,1=>2,0=>1]);
		$array = [1,'test'=>2,3,['key'=>'bla','what',[1,2,3,4,5]]];
		$reverse = Base\Arrs::reverse([1,'test'=>2,3,['key'=>'bla','what',[1,2,3,4,5]]]);
		assert(count($reverse) === 4);
		assert($reverse[2]['key'] === 'bla');
		assert($reverse !== $array);
		$reverse2 = Base\Arrs::reverse([1,'test'=>2,3,['key'=>'bla','what',[1,2,3,4,5]]],false);
		assert($reverse !== $reverse2);

		// flip
		assert(['test'=>0,'test2'=>1] === Base\Arrs::flip(['test','test2']));
		assert(['test'=>'test','test2'=>'test2'] === Base\Arrs::flip(['test'=>'test','test2'=>'test2']));
		assert([['test'=>true]] === Base\Arrs::flip([['test']],true));
		assert(['test'=>['test'=>true,'test2'=>true]] === Base\Arrs::flip(['test'=>['test','test2']],true));
		assert(['test'=>['test3','test2']] === Base\Arrs::flip(['test'=>['test3','test2']],true,'test'));
		assert(['test'=>['test3','test2'=>true]] === Base\Arrs::flip(['test'=>['test3','test2']],true,[0]));
		assert([1=>'test'] === Base\Arrs::flip([1=>'test'],true,1));

		// implode
		assert('test|test2' === Base\Arrs::implode('|',['test'=>'test','test2']));
		assert('test|test2|test4' === Base\Arrs::implode('|',['test'=>'test','test2','test3'=>['test4']]));
		assert(Base\Arrs::implode(['|',','],['test'=>'test','test2','test3'=>['test4','test5']]) === 'test|test2|test4,test5');
		assert('test|test2|test4' === Base\Arrs::implode(['|'],['test'=>'test','test2','test3'=>['test4']]));
		assert(Base\Arrs::implode('',['test','test2']) === 'testtest2');

		// explode
		assert(['test','test2','test3','test4'] === Base\Arrs::explode('|',['test|test2','test3|test4']));
		assert(['test','test2','test3'] === Base\Arrs::explode('|',[2=>'test',['test2|test3']]));

		// fill
		$fill = Base\Arrs::fill([[0,5,1],[0,6,2],[0,6,3],[0,40,20],[0,5]]);
		assert(count(Base\Arrs::crush($fill)) === 1296);

		// fillKeys
		$fill = Base\Arrs::fillKeys([['test','test2'],['ok',2],['WHAT!','jjj']]);
		assert(count(Base\Arrs::crush($fill)) === 8);
		assert(Base\Arrs::fillKeys([[[],'test','test2'],[[],'ok'],['WHAT!']]) === ['WHAT!'=>true]);

		// hierarchy
		assert(Base\Arrs::hierarchy(['test'=>null,'ok'=>null,2=>'test','test5'=>2,'test3'=>'test4']) === ['test'=>[2=>['test5'=>null]],'ok'=>null]);
		assert(Base\Arrs::hierarchy(['test'=>null,'ok'=>null,2=>'test','test5'=>2,'test3'=>'test4'],false)['test4'] === ['test3'=>null]);

		// hierarchyStructure
		assert(Base\Arrs::hierarchyStructure(['test'=>'well','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4'],false)[4] === ['well','test','test2','test5']);
		assert(Base\Arrs::hierarchyStructure(['test'=>'well','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4']) === [['ok']]);
		assert(Base\Arrs::hierarchyStructure(['test'=>'ok','ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4'])[1] === ['ok','test']);
		assert(Base\Arrs::hierarchyStructure(['test'=>null,'ok'=>null,'test2'=>'test','test5'=>'test2','test3'=>'test4']) === [['test'],['ok'],['test','test2'],['test','test2','test5']]);

		// hierarchyAppend
		assert(Base\Arrs::hierarchyAppend(['test2'=>'test5'],[['ok'],['ok','james','test5']])[2] === ['ok','james','test5','test2']);
		assert(Base\Arrs::hierarchyAppend(['test2'=>'test5'],[['ok'],['ok','james','test5'],['well']])[3] === ['ok','james','test5','test2']);

		// keyExists
		assert(Base\Arrs::keyExists('1/2',[1=>[2=>true]]));
		assert(Base\Arrs::keyExists([1,2],[1=>[2=>true]]));
		assert(Base\Arrs::keyExists('test',['test'=>['test2'=>false]]));
		assert(Base\Arrs::keyExists('test/test2',['test'=>['test2'=>false]]));
		assert(Base\Arrs::keyExists('test',['test'=>['test2'=>false]]));
		assert(Base\Arrs::keyExists('TEST',['test'=>['test2'=>false]],false));
		assert(!Base\Arrs::keyExists('test/test3',['test'=>['test2'=>false]]));

		// keysExists
		assert(Base\Arrs::keysExists(['test/test2'],['test'=>['test2'=>false]]));
		assert(Base\Arrs::keysExists(['test/test2','TEST'],['test'=>['test2'=>false]],false));

		// keyPath
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['key'=>'james']]];
		assert([1,2,'key'] === Base\Arrs::keyPath('key',$array));
		assert(null === Base\Arrs::keyPath('key3',$array));
		assert([1,2,'key2'] === Base\Arrs::keyPath('key2',$array));

		// keyPaths
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['key'=>'james']]];
		assert([[1,2,'key'],[1,3,'key']] === Base\Arrs::keyPaths('key',$array));
		assert([] === Base\Arrs::keyPaths('keyz',$array));
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['key'=>'james']],'key'=>'yes'];
		assert([['key'],[1,2,'key'],[1,3,'key']] === Base\Arrs::keyPaths('key',$array));

		// keyValue
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['key'=>'james']],'key'=>'yes'];
		assert('yes' === Base\Arrs::keyValue('key',$array));
		assert(null === Base\Arrs::keyValue('keyz',$array));
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['keyz'=>'james']],'key'=>'yes'];
		assert('james' === Base\Arrs::keyValue('keyz',$array));
		$array = [1=>[2=>['keyz'=>true,'key2'=>false],3=>['keyz'=>'james']],'key'=>'yes'];
		assert(true === Base\Arrs::keyValue('keyz',$array));

		// keyValues
		$array = [1=>[2=>['key'=>true,'key2'=>false],3=>['key'=>'james']],'key'=>'yes'];
		assert(['key'=>'yes','1/2/key'=>true,'1/3/key'=>'james'] === Base\Arrs::keyValues('key',$array));
		assert([] === Base\Arrs::keyValues('keyz',$array));

		// keysLower
		assert(Base\Arrs::keysLower(['Test'=>['TesT2'=>'ok','test2'=>'james']]) === ['test'=>['test2'=>'james']]);

		// keysUpper
		assert(Base\Arrs::keysUpper(['Tést'=>['TesT2'=>'ok']],true) === ['TÉST'=>['TEST2'=>'ok']]);

		// keysInsensitive
		assert(Base\Arrs::keysInsensitive(['Tést'=>['TésT2'=>'ok','TÉST2'=>true]]) === ['Tést'=>['TÉST2'=>true]]);

		// keysSort
		$sort = ['z'=>'test','b'=>'test','c'=>['z'=>'a','b'=>'b']];
		$sort = Base\Arrs::keysSort($sort,true);
		assert(['b'=>'test','c'=>['b'=>'b','z'=>'a'],'z'=>'test'] === $sort);
		$sort = Base\Arrs::keysSort($sort,'desc');
		assert(['z'=>'test','c'=>['z'=>'a','b'=>'b'],'b'=>'test'] === $sort);
		$sort = ['z'=>'test','b'=>'test','c'=>['z'=>'a','b'=>'b']];
		$sort = Base\Arrs::keysSort($sort,'krsort');
		assert(['z'=>'test','c'=>['z'=>'a','b'=>'b'],'b'=>'test'] === $sort);

		// valueKey
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['ok'=>'mÉh',true]]]],'test2'=>'z'];
		assert(count(Base\Arrs::valueKey(true,$array)) === 2);
		assert(count(Base\Arrs::valueKey(null,$array)) === 0);
		assert(count(Base\Arrs::valueKey('MéH',$array,false)) === 1);

		// valuesKey
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['meh'=>'mÉh',true]]]],'test2'=>'z'];
		assert(count(Base\Arrs::valuesKey([true,2],$array)) === 3);
		assert(Base\Arrs::valuesKey([[]],$array) === []);
		assert(count(Base\Arrs::valuesKey(['MéH'],$array,false)) === 1);

		// valueStrip
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['ok'=>'mÉh',true]]]],'test2'=>'z'];
		assert(Base\Arrs::count(Base\Arrs::valueStrip(true,$array)) === 8);

		// valuesStrip
		$array = ['test'=>['test2'=>['test3'=>['ok'=>2,true,false,'bla'=>['ok'=>'mÉh',true]]]],'test2'=>'z'];
		[Base\Arrs::valuesStrip([true,false,2,'z','mÉh'],$array)['test']['test2']['test3']['bla'] === []];

		// valuesCrush
		assert(Base\Arrs::valuesCrush(['ok']) === []);
		assert(Base\Arrs::valuesCrush([['Ok']]) === [['Ok']]);
		assert(count(Base\Arrs::valuesCrush([['Ok'],['ok2','ok3'],['ok4','ok5'],['ok6']])) === 4);
		assert(count(Base\Arrs::valuesCrush([['Ok'],['ok2','ok3'],['ok4','ok5'],['ok6']])[0]) === 4);

		// valuesChange
		$array = [true,[2,[true]]];
		assert([false,[2,[false]]] === Base\Arrs::valuesChange(true,false,$array));
		assert([false,[2,[true]]] === Base\Arrs::valuesChange(true,false,$array,1));
		assert([false,[2,[false]]] === Base\Arrs::valuesChange(true,false,$array,2));

		// valuesReplace
		assert(['test'=>'lapa','lapa2'] === Base\Arrs::valuesReplace(['test'=>'lapa'],['test'=>'test','test2']));
		assert(['test'=>'lapa','lapa2','2'] === Base\Arrs::valuesReplace(['test'=>'lapa'],['test'=>'test','test2','2']));
		assert(['test'=>['lapa'],'lapa2','2'] === Base\Arrs::valuesReplace(['test'=>'lapa'],['test'=>['test'],'test2','2']));
		assert(['test'=>['lapa'],'lapa2','2'] === Base\Arrs::valuesReplace(['test'=>'lapa'],['test'=>['test'],'TEST2','2'],false));
		assert(['test'=>['lapa'],'TEST2','2'] === Base\Arrs::valuesReplace(['test'=>'lapa'],['test'=>['test'],'TEST2','2'],true));

		// valuesLower
		assert(Base\Arrs::valuesLower(['Test',['OK','TÉS',['VlÉ']]])[1][2][0] === 'vlé');

		// valuesUpper
		assert(Base\Arrs::valuesUpper(['Test',['OK','TÉS',['Vlé']]])[1][2][0] === 'VLÉ');

		// keysValuesLower
		assert(Base\Arrs::keysValuesLower(['A'=>['A'=>'É']]) === ['a'=>['a'=>'é']]);

		// keysValuesUpper
		assert(Base\Arrs::keysValuesUpper(['a'=>['a'=>'é']]) === ['A'=>['A'=>'É']]);

		return true;
	}
}
?>