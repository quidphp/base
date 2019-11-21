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

// str
// class for testing Quid\Base\Str
class Str extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $isCli = Base\Server::isCli();

        // typecast
        $b = 2;
        $y = 1.2;
        $c = null;
        Base\Str::typecast($b,$y,$c);
        assert('2' === $b);
        assert('1.2' === $y);
        assert($c === '');

        // typecastNotNull
        $b = 2;
        $y = 1.2;
        $c = null;
        Base\Str::typecastNotNull($b,$y,$c);
        assert('2' === $b);
        assert($c === null);

        // is
        assert(Base\Str::is('test2'));
        assert(Base\Str::is(''));
        assert(!Base\Str::is([]));

        // isEmpty
        assert(!Base\Str::isEmpty(null));
        assert(Base\Str::isEmpty(''));

        // isNotEmpty
        assert(!Base\Str::isNotEmpty(null));
        assert(Base\Str::isNotEmpty('test2'));
        assert(!Base\Str::isNotEmpty(''));
        assert(!Base\Str::isNotEmpty([]));

        // isLength
        assert(Base\Str::isLength(2,'te'));
        assert(!Base\Str::isLength(2,'té'));
        assert(Base\Str::isLength(2,'té',true));

        // isMinLength
        assert(Base\Str::isMinLength(2,'te'));
        assert(Base\Str::isMinLength(1,'te'));
        assert(!Base\Str::isMinLength(3,'te'));

        // isMaxLength
        assert(!Base\Str::isMaxLength(1,'te'));
        assert(Base\Str::isMaxLength(2,'te'));
        assert(Base\Str::isMaxLength(3,'te'));

        // isStart
        $string = 'Testéblabla';
        assert(Base\Str::isStart('Testéblabla',$string));
        assert(Base\Str::isStart('Test',$string));
        assert(!Base\Str::isStart('test',$string));
        assert(Base\Str::isStart('test',$string,false));
        $string = 'àTestéblabla';
        assert(Base\Str::isStart('àT',$string));
        $string = 'étesta';
        assert(Base\Str::isStart('É',$string,false));

        // isStarts
        $string = 'Testéblabla';
        assert(Base\Str::isStarts(['Wp','Testéblabla'],$string));

        // isEnd
        $string = 'Testéblabla';
        assert(Base\Str::isEnd('blabla',$string));
        assert(Base\Str::isEnd('a',$string));
        assert(Base\Str::isEnd('éblabla',$string));
        assert(Base\Str::isEnd('Éblabla',$string,false));
        assert(false === Base\Str::isEnd('Éblabla',$string,true));
        $string = 'Testéblablaéàa';
        assert(Base\Str::isEnd('éàa',$string));
        $string = 'Testéblablaéàa0';
        assert(Base\Str::isEnd('0',$string));

        // isEnds
        $string = 'Testéblabla';
        assert(Base\Str::isEnds(['zA','blabla'],$string));

        // isStartEnd
        $string = 'testouitest';
        assert(Base\Str::isStartEnd('test','test',$string));
        assert(!Base\Str::isStartEnd('testo','testo',$string));
        $string = '<testouitest>';
        assert(!Base\Str::isStartEnd('<','<',$string));
        assert(Base\Str::isStartEnd('<','>',$string));

        // isPattern
        assert(Base\Str::isPattern('*_id','test_id'));
        assert(!Base\Str::isPattern('*_id','test_ids'));
        assert(Base\Str::isPattern('ok_*','ok_fr'));
        assert(!Base\Str::isPattern('ok_*','okz_fr'));

        // isLatin
        assert(Base\Str::isLatin('james123éèàsda^çsad!@%&?#@&*#(()_)ÉÈÀSAD:l1/*-+  assd_ ++_ __ @#_@!+
		'));
        assert(!Base\Str::isLatin('Привет всем участникам форума! Класный у вас сайт! '));
        assert(Base\Str::isLatin(''));

        // hasNullByte
        assert(!Base\Str::hasNullByte('james123éèàsda^çsad!@%&?#@&*#(()_)ÉÈÀSAD:l1/*-+  assd_ ++_ __ @#_@!+
		'));
        assert(!Base\Str::hasNullByte('Привет всем участникам форума! Класный у вас сайт! '));

        // icompare
        assert(Base\Str::icompare('string','String'));
        assert(Base\Str::icompare('string','string'));
        assert(Base\Str::icompare('ÉÈstring','Éèstring'));

        assert(Base\Str::icompare([['é']],[['É']]));
        assert(Base\Str::icompare([['é']],[['É']],[['é']]));
        assert(Base\Str::icompare([['é','la','B']],[['É','LA','b']]));
        assert(!Base\Str::icompare([['é']],null,[['É']],[['é']]));
        assert(Base\Str::icompare('string','String'));
        assert(Base\Str::icompare('string','string'));
        assert(Base\Str::icompare('ÉÈstring','Éèstring'));

        // prepend
        assert(Base\Str::prepend('test',1,2.4,'what') === 'what2.41test');

        // append
        assert(Base\Str::append('test',1,2.4,'what') === 'test12.4what');

        // cast
        assert(Base\Str::cast([1,2]) === '[1,2]');
        assert(Base\Str::cast(2) === '2');
        assert(Base\Str::cast(new \stdclass()) === 'O:8:"stdClass":0:{}');
        assert(Base\Str::cast([1,2],'-') === '1-2');
        assert(Base\Str::cast(true) === '1');
        assert(Base\Str::cast(false) === '0');

        // castFix
        assert(Base\Str::castFix([1,2]) === '[1,2]');

        // toNumeric
        assert((float) 1.2 === Base\Str::toNumeric('1.2'));
        assert(1.3 === Base\Str::toNumeric('1,3'));
        assert((float) 1 === Base\Str::toNumeric('1,3',false));
        assert((int) 1 === Base\Str::toNumeric('1'));
        assert((float) 'aaa' === Base\Str::toNumeric('aaa'));
        assert((float) 'aaa1' === Base\Str::toNumeric('aaa1'));
        assert((float) 'aaa1aaa' === Base\Str::toNumeric('aaa1aaa'));
        assert('123321123312312132213321123312321312321123213231312312' === Base\Str::toNumeric('123321123312312132213321123312321312321123213231312312'));

        // toInt
        assert((int) 1.2 === Base\Str::toInt('1.2'));
        assert(1 === Base\Str::toInt('1,3'));
        assert((int) 1 === Base\Str::toInt('1'));
        assert((int) 'aaa' === Base\Str::toInt('aaa'));
        assert((int) 'aaa1' === Base\Str::toInt('aaa1'));
        assert((int) 'aaa1aaa' === Base\Str::toInt('aaa1aaa'));
        assert((int) '1aaa1aaa' === Base\Str::toInt('1aaa1aaa'));
        assert(PHP_INT_MAX === Base\Str::toInt('123321123312312132213321123312321312321123213231312312'));

        // toFloat
        assert((float) 1.2 === Base\Str::toFloat('1.2'));
        assert(1.3 === Base\Str::toFloat('1,3'));
        assert((float) 1 === Base\Str::toFloat('1,3',false));
        assert((float) 1 === Base\Str::toFloat('1'));
        assert((float) 'aaa' === Base\Str::toFloat('aaa'));
        assert((float) 'aaa1' === Base\Str::toFloat('aaa1'));
        assert((float) 'aaa1aaa' === Base\Str::toFloat('aaa1aaa'));
        assert((float) '1aaa1aaa' === Base\Str::toFloat('1aaa1aaa'));

        // len
        assert(5 === Base\Str::len('teste'));
        assert(6 === Base\Str::len('testé'));
        assert(5 === Base\Str::len('testé',true));
        assert(0 === Base\Str::len('',true));

        // lenWith
        assert(Base\Str::lenWith('abc','abcde') === 3);
        assert(Base\Str::lenWith('abc','abcde',1,3) === 2);
        assert(Base\Str::lenWith('abc','abcde',1) === 2);
        assert(Base\Str::lenWith('','abcde') === 0);

        // lenWithout
        assert(Base\Str::lenWithout('zyx','abcde') === 5);
        assert(Base\Str::lenWithout('abc','abcde') === 0);
        assert(Base\Str::lenWithout('abc','zbcde') === 1);
        assert(Base\Str::lenWithout('abc','zycde',1,2) === 1);
        assert(Base\Str::lenWithout('','zbcde') === 5);

        // pos
        assert(2 === Base\Str::pos('test','astestasc'));
        assert(3 === Base\Str::pos('test','aétestasc'));
        assert(3 === Base\Str::pos('z','asZztestasc'));
        assert(null === Base\Str::pos('zaaaaa','asZztestasc'));
        assert(2 === Base\Str::pos('test','aétestasc',0,true));
        assert(3 === Base\Str::pos('es','aetestesc',0,true));
        assert(3 === Base\Str::pos('es','aetestesc',2,true));
        assert(6 === Base\Str::pos('es','aetestesc',4,true));
        assert(6 === Base\Str::pos('es','aetestesc','1234',true));

        // posRev
        assert(6 === Base\Str::posRev('es','aetestesc'));
        assert(8 === Base\Str::posRev('és','aetestéésc'));
        assert(7 === Base\Str::posRev('és','aetestéésc',0,true));
        assert(7 === Base\Str::posRev('és','aetestéésc',1,true));
        assert(7 === Base\Str::posRev('és','aetestéésc','a',true));
        assert(Base\Str::posRev('te','aetestéésc',-9,true) === null);
        assert(Base\Str::posRev('te','aetestéésc',-9,false) === 2);
        assert(5 === Base\Str::posRev('té','aetestéésc',1));

        // ipos
        assert(2 === Base\Str::ipos('z','asZtestasc'));
        assert(null === Base\Str::ipos('é','asÉtestasc'));
        assert(2 === Base\Str::ipos('é','asÉtestasc',2,true));
        assert(2 === Base\Str::ipos('é','asÉtestasc','12',true));
        assert(null === Base\Str::ipos('é','asÉtestasc',3,true));
        assert(null === Base\Str::ipos('esqweewqqweeqw','aetestéesc',0,false));
        assert(Base\Str::ipos('TestÉ','Testéblabla') === null);
        assert(Base\Str::ipos('TestÉ','Testéblabla',0,true) === 0);

        // iposRev
        assert(6 === Base\Str::iposRev('es','aetestesc',0,true));
        assert(7 === Base\Str::iposRev('es','aetestéesc',0,true));
        assert(8 === Base\Str::iposRev('es','aetestéesc',0,false));
        assert(null === Base\Str::iposRev('ée','ÉetestÉÉesc',0,false));
        assert(7 === Base\Str::iposRev('ée','ÉetestÉÉesc',0,true));
        assert(null === Base\Str::iposRev('esqqqqq','aetestéesc',0,false));

        // posIpos
        assert(Base\Str::posIpos('test','testa') === 0);
        assert(Base\Str::posIpos('TEST','testa') === null);
        assert(Base\Str::posIpos('TEST','testa',false) === 0);
        assert(Base\Str::posIpos('TÉST','tésta',false) === 0);

        // in
        $string = 'testlalablabla';
        assert(Base\Str::in('test',$string));
        assert(!Base\Str::in('testz',$string));
        assert(Base\Str::in('bla',$string));
        assert(!Base\Str::in('BLA',$string));
        assert(Base\Str::in('BLA',$string,false));
        $string = 'éèç !';
        assert(Base\Str::in('èç !',$string));
        assert(Base\Str::in('Èç !',$string,false));

        // ins
        $string = 'testlalablabla';
        assert(!Base\Str::ins(['test','blaz'],$string));
        assert(Base\Str::ins(['test','bla'],'Testlalablabla',false));
        assert(Base\Str::ins(['test','aé'],'Testlalablablaée',false));
        assert(Base\Str::ins(['TEST','AÉ'],'Testlalablablaée',false));

        // inFirst
        $string = 'testlalablabla';
        assert(Base\Str::inFirst(['test','blaz'],$string) === 'test');
        assert(Base\Str::inFirst(['testz','blaz'],$string) === null);
        assert(Base\Str::inFirst(['testz','blaz','t'],$string) === 't');

        // search
        assert(Base\Str::search('james','james2',true));
        assert(!Base\Str::search('ja + mes','james2',true));
        assert(Base\Str::search('ja + mes','james2',true,true,true,'+'));
        assert(!Base\Str::search('JA + mes','james2',true,true,true,'+'));
        assert(Base\Str::search('JA + mes','james2',false,true,true,'+'));
        assert(!Base\Str::search('ja + mes','james2',true,true,true));
        assert(Base\Str::search('ja mes 2','james2',true,true,true));
        assert(!Base\Str::search('ja mes 3','james2',true,true,true));
        assert(Base\Str::search('jamés','jamés',false,true,true));
        assert(Base\Str::search('JAMÉS','jamés',false,true,true));
        assert(!Base\Str::search('JAMÉS','jamés',true,true,true));
        assert(Base\Str::search('ja + MÉS','jamés',false,true,true,'+'));
        assert(!Base\Str::search('ja + MES','jamés',false,true,true,'+'));
        assert(Base\Str::search('ja + MES','jamés',false,false,true,'+'));

        // prepareSearch
        assert(Base\Str::prepareSearch('test +  james','+') === ['test','james']);
        assert(Base\Str::prepareSearch('test +  james') === ['test','+','james']);
        assert(Base\Str::prepareSearch(2) === ['2']);

        // sub
        assert('este' === Base\Str::sub(1,null,'teste'));
        assert('es' === Base\Str::sub(1,2,'teste'));
        assert('te' === Base\Str::sub(-2,2,'teste'));
        assert('té' === Base\Str::sub(0,3,'téste'));
        assert('té' === Base\Str::sub(0,2,'téste',true));
        assert('' === Base\Str::sub(1000,1000,'téste',true));

        // subFirst
        assert(Base\Str::subFirst('testa') === 't');
        assert(Base\Str::subFirst('testa',2) === 'te');

        // subLast
        assert(Base\Str::subLast('testa') === 'a');
        assert(Base\Str::subLast('testa',2) === 'ta');

        // cut
        assert('t' === Base\Str::cut(0,2,'téste',true));
        assert('té' === Base\Str::cut(0,3,'téste',true));
        assert('té' === Base\Str::cut(0,3,'téste',false));

        // subCount
        $string = 'testé test test test test test blabla test';
        assert(Base\Str::subCount('test',$string) === 7);
        assert(Base\Str::subCount('testé',$string,null,null,false) === 1);
        assert(Base\Str::subCount('testé',$string,null,null,true) === 1);
        assert(Base\Str::subCount('testé',$string,'','123456',true) === 1);
        assert(Base\Str::subCount('teste',$string,1,20,true) === 0);
        assert(Base\Str::subCount('test',$string,'1',20,true) === 3);
        assert(Base\Str::subCount('test',$string,1,20,false) === 3);

        // subReplace
        $string = 'etesté test test test test test blabla test';
        assert(Base\Str::subReplace(7,4,' whattt',$string) === 'etesté whatttt test test test test blabla test');
        assert(Base\Str::subReplace(6,'1234',' whattt',$string,true) === 'etesté whatttt test test test test blabla test');
        assert(Base\Str::subReplace('123456','1234',' whattt',$string,true) === 'etesté whatttt test test test test blabla test');
        assert(Base\Str::subReplace(0,0,'whattt ',$string,true) === 'whattt etesté test test test test test blabla test');
        assert(Base\Str::subReplace(0,0,'whattt ',$string,false) === 'whattt etesté test test test test test blabla test');
        assert(Base\Str::subReplace(-25,25,' éenndd ',$string,false) === 'etesté test test t éenndd ');
        assert(Base\Str::subReplace(-25,25,' éenndd ',$string,true) === 'etesté test test t éenndd ');
        assert(Base\Str::subReplace(0,0,' éenndd ','',true) === ' éenndd ');
        assert(Base\Str::subReplace(1,2,['o','k'],$string) === 'eoksté test test test test test blabla test');

        // subCompare
        assert(Base\Str::subCompare('lala',1,4,'ilalai') === 0);
        assert(Base\Str::subCompare('lala',1,4,'iLalai',false) === 0);
        assert(Base\Str::subCompare('lala','i','lala','iLalai',false) === 0);
        assert(Base\Str::subCompare('Lalaé',1,4,'iLalaéi') === 0);
        assert(Base\Str::subCompare('xyz',1,4,'iLalai') < 0); // ici j'ai mis < 0, le résultat est inconsitent sur les serveur (est -44 sur le portable)
        assert(Base\Str::subCompare('éil',1,4,'iÉili',true) < 0);
        assert(Base\Str::subCompare('éil',1,4,'iéili',true) === 0);
        assert(Base\Str::subCompare('éil',1,4,'iÉili',false,true) === 0);
        assert(Base\Str::subCompare('éil',1,'1234','iéili',false,true) === 0);

        // subSearch
        $string = 'aBcDéf';
        assert(Base\Str::subSearch('Bf',$string) === 'BcDéf');
        assert(Base\Str::subSearch('yéz',$string) === 'éf');
        assert(Base\Str::subSearch('Éz',$string) === 'éf'); // étrange, le caractère accenté majuscule est trouvé
        assert(Base\Str::subSearch('d',$string) === '');
        assert(Base\Str::subSearch('',$string) === '');
        assert(Base\Str::subSearch('a','') === '');

        // startEndIndex
        $string = 'testouitest';
        assert(-1 === Base\Str::startEndIndex('itest',$string));
        assert(0 === Base\Str::startEndIndex('testo',$string));
        assert(null === Base\Str::startEndIndex('itestzzz',$string));

        // stripWrap
        assert(Base\Str::stripWrap('-','test',true,true) === '-test-');
        assert(Base\Str::stripWrap('-','test-',true,false) === '-test');

        // stripStart
        $string = 'Testéblabla';
        assert('éblabla' === Base\Str::stripStart('Test',$string));
        assert('blabla' === Base\Str::stripStart('Testé',$string,true));
        assert('blabla' === Base\Str::stripStart('TestÉ',$string,false));
        assert('éblabla' === Base\Str::stripStart('test',$string,false));
        assert('' === Base\Str::stripStart('Testéblabla',$string,false));
        assert($string === Base\Str::stripStart('lala',$string));
        assert($string === Base\Str::stripStart('',$string));
        assert('testa' === Base\Str::stripStart('É','étesta',false));
        assert('!test!' === Base\Str::stripStart('!','!!test!'));

        // stripEnd
        $string = 'Testéblabla';
        assert('Testé' === Base\Str::stripEnd('blabla',$string));
        assert('Testé' === Base\Str::stripEnd('blabla','Testéblabla',false));
        assert('Testéblablaz' === Base\Str::stripEnd('blabla','Testéblablaz',false));
        assert('étest' === Base\Str::stripEnd('É','étesté',false,true));
        assert('!!test' === Base\Str::stripEnd('!','!!test!'));

        // stripStartEnd
        $string = '[test[';
        assert('test' === Base\Str::stripStartEnd('[','[',$string));
        assert($string === Base\Str::stripStartEnd('.','.',$string));
        $string = '.test[';
        assert($string === Base\Str::stripStartEnd('.','.',$string));
        $string = '[test]';
        assert('test' === Base\Str::stripStartEnd('[',']',$string));
        $string = 'ÉtestÉ';
        assert('test' === Base\Str::stripStartEnd('é','é',$string,false));

        // stripStartOrEnd
        $string = '.test[';
        assert('test[' === Base\Str::stripStartOrEnd('.','.',$string));
        assert('test[' === Base\Str::stripStartOrEnd('.','.',$string));
        assert($string === Base\Str::stripStartOrEnd('!','!',$string));
        assert('tst' === Base\Str::stripStartOrEnd("'","'","'tst'"));
        assert('xc' === Base\Str::stripStartOrEnd('z','z','zxc'));
        assert('tst' === Base\Str::stripStartOrEnd("'","'","'tst'"));
        assert('tst' === Base\Str::stripStartOrEnd("'","'","'tst'"));
        assert('tst' === Base\Str::stripStartOrEnd('é','é','étsté'));
        assert('tst' === Base\Str::stripStartOrEnd('É','É','étsté',false));

        // wrapStart
        assert('!test' === Base\Str::wrapStart('!','test'));
        assert('data-test' === Base\Str::wrapStart('data-','data-test'));
        assert('data-test' === Base\Str::wrapStart('data-','test'));

        // wrapEnd
        assert('test!' === Base\Str::wrapEnd('!','test'));
        assert('test!' === Base\Str::wrapEnd('!','test!'));

        // wrapStartEnd
        assert('!!test!' === Base\Str::wrapStartEnd('!','!','!test'));
        assert('!test!' === Base\Str::wrapStartEnd('!','!','!test!'));

        // wrapStartOrEnd
        assert('!test!' === Base\Str::wrapStartOrEnd('!','!','!test'));
        assert('!test!' === Base\Str::wrapStartOrEnd('!','!','!test'));
        assert('!test!' === Base\Str::wrapStartOrEnd('!','!','test'));
        assert("''" === Base\Str::wrapStartOrEnd("'","'",''));
        assert('éèèé' === Base\Str::wrapStartOrEnd('é','é','èèé'));
        assert('éèèé' === Base\Str::wrapStartOrEnd('é','é','èèé',true));

        // stripFirst
        $string = 'Téstéblabla';
        assert(Base\Str::stripFirst($string) === 'éstéblabla');
        assert(Base\Str::stripFirst($string,2,true) === 'stéblabla');

        // stripLast
        $string = 'Testéblabla';
        assert(Base\Str::stripLast($string) === 'Testéblabl');
        assert(Base\Str::stripLast($string,2) === 'Testéblab');

        // addPattern
        assert(Base\Str::addPattern('*_fr','james') === 'james_fr');

        // stripPattern
        assert(Base\Str::stripPattern('*_id','test_id') === 'test');
        assert(Base\Str::stripPattern('*_id','test_ids') === null);
        assert(Base\Str::stripPattern('ok_*','ok_fr') === 'fr');
        assert(Base\Str::stripPattern('ok_*','okz_fr') === null);

        // stripBefore
        $string = 'emondppÉh@Gmail.com';
        assert(Base\Str::stripBefore('@',$string) === '@Gmail.com');
        assert(Base\Str::stripBefore('@',$string,false) === 'Gmail.com');
        assert(Base\Str::stripBefore('g',$string,false) === '');
        assert(Base\Str::stripBefore('g',$string,false,false) === 'mail.com');
        assert(Base\Str::stripBefore('g',$string,true,false) === 'Gmail.com');
        assert(Base\Str::stripBefore('pp',$string) === 'ppÉh@Gmail.com');
        assert(Base\Str::stripBefore('pé',$string) === '');
        assert(Base\Str::stripBefore('pé',$string,true,false,true) === 'pÉh@Gmail.com');
        assert(Base\Str::stripBefore('pé',$string,false,false,true) === 'h@Gmail.com');
        assert(Base\Str::stripBefore('pé',$string,false,false,true) === 'h@Gmail.com');
        $string = 'emondppÉh@Gmail@com';
        assert(Base\Str::stripBefore('@',$string) === '@Gmail@com');
        $string = 'test/test2/test3/test4';
        assert(Base\Str::stripBefore('/',$string) === '/test2/test3/test4');

        // stripBeforeReverse
        $string = 'test/test2/test3/test4';
        assert(Base\Str::stripBeforeReverse('/',$string) === '/test4');
        assert(Base\Str::stripBeforeReverse('/',$string,false) === 'test4');
        $string = 'emondppÉh@Gmail@com';
        assert(Base\Str::stripBeforeReverse('@',$string) === '@com');
        assert(Base\Str::stripBeforeReverse('@',$string,false) === 'com');
        assert(Base\Str::stripBeforeReverse('O',$string,true,true) === '');
        assert(Base\Str::stripBeforeReverse('O',$string,true,false) === 'om');

        // stripAfter
        $string = 'emondppÉh@Gmail.com';
        assert(Base\Str::stripAfter('@',$string) === 'emondppÉh');
        assert(Base\Str::stripAfter('@',$string,true) === 'emondppÉh@');
        assert(Base\Str::stripAfter('é',$string,true) === '');
        assert(Base\Str::stripAfter('é',$string,true,false) === '');
        assert(Base\Str::stripAfter('g',$string,true,false) === 'emondppÉh@g');
        assert(Base\Str::stripAfter('g',$string,false,false) === 'emondppÉh@');
        assert(Base\Str::stripAfter('é',$string,true,false,true) === 'emondppé');

        // stripAfterReverse
        $string = 'test/test2/test3/test4';
        assert(Base\Str::stripAfterReverse('/',$string) === 'test/test2/test3');
        assert(Base\Str::stripAfterReverse('/',$string,true) === 'test/test2/test3/');
        $string = 'emondppÉh@Gmail@com';
        assert(Base\Str::stripAfterReverse('@',$string) === 'emondppÉh@Gmail');
        assert(Base\Str::stripAfterReverse('@',$string,true) === 'emondppÉh@Gmail@');
        assert(Base\Str::stripAfterReverse('O',$string,true,true) === '');
        assert(Base\Str::stripAfterReverse('O',$string,true,false) === 'emondppÉh@Gmail@cO');

        // changeBefore
        assert('key_en' === Base\Str::changeBefore('_','key','name_en'));
        assert('nameDELen' === Base\Str::changeBefore('del','KEY','nameDELen',true));
        assert('keydelen' === Base\Str::changeBefore('del','key','nameDELen',false));
        assert('key--en' === Base\Str::changeBefore('--','key','name--en'));
        assert('nameen' === Base\Str::changeBefore('_','key','nameen'));

        // changeAfter
        assert('name+fr' === Base\Str::changeAfter('+','fr','name+en'));
        assert('name_fr' === Base\Str::changeAfter('_','fr','name_en'));
        assert('name_fr' === Base\Str::changeAfter('_','fr','name_fr'));
        assert('nameen' === Base\Str::changeAfter('_','fr','nameen'));

        // lower
        assert('testé' === Base\Str::lower('TESTÉ',true));
        assert('testé' !== Base\Str::lower('TESTÉ',false));

        // lowerFirst
        assert(Base\Str::lowerFirst('Éeee') !== 'éeee'); // sur windows ça retourne un caractère unicode corrompu
        assert(Base\Str::lowerFirst('Éeee',true) === 'éeee');

        // upper
        assert('TESTÉ' === Base\Str::upper('testé',true));
        assert('TESTÉ' !== Base\Str::upper('testé',false));
        assert('TESTE' === Base\Str::upper('teste',false));

        // upperFirst
        assert(Base\Str::upperFirst('éeee') !== 'Éeee'); // je ne sais pas ce qui cause ceci sur cli
        assert(Base\Str::upperFirst('éeee',true) === 'Éeee');

        // capitalize
        assert('Testé testà Étest' !== Base\Str::capitalize('testé testà étest'));
        assert('étesté testà Étest' !== Base\Str::capitalize('étesté testà étest'));
        assert('Étesté testà étest 123' === Base\Str::capitalize('étesté testà étest 123',true));
        assert('Tétesté testà étest 123' === Base\Str::capitalize('tétesté testà étest 123',true));

        // title
        assert('Testé Testà Étest' !== Base\Str::title('testé testà étest'));
        assert('Testé Éestà Étest 123' === Base\Str::title('testé éestà étest 123',true));

        // reverse
        $string = "abcdéfg '";
        assert(Base\Str::reverse($string) !== "' gfédcba");
        assert(Base\Str::reverse($string,true) === "' gfédcba");

        // shuffle
        $string = "abcdéfg '";
        assert(strlen(Base\Str::shuffle($string)) === 10);
        assert(strlen(Base\Str::shuffle($string,true)) === 10);

        // pad
        assert(Base\Str::pad('e',28,'lavie') === 'eeeeeeeeeeelavieeeeeeeeeeeee');
        assert(Base\Str::pad('é',28,'lavie',true) === 'ééééééééééélavieéééééééééééé');
        assert(Base\Str::pad('e!e',28,'lavie') === 'e!ee!ee!ee!laviee!ee!ee!ee!e');
        assert(Base\Str::pad('é!è',28,'lavie',true) === 'é!èé!èé!èé!lavieé!èé!èé!èé!è');
        assert(Base\Str::pad('é!è',2,'lavie',true) === 'lavie');
        assert(Base\Str::pad('e|e',2,'lavie',false) === 'lavie');
        assert(Base\Str::pad('é!è',6,'lavie',true) === 'lavieé');
        assert(Base\Str::pad('e|e',6,'lavie',false) === 'laviee');
        assert(Base\Str::pad('é|è',4,'',true) === 'é|é|');
        assert(Base\Str::pad('a|s',4,'',false) === 'a|a|');

        // padLeft
        assert(Base\Str::padLeft('e',24,'lavie') === 'eeeeeeeeeeeeeeeeeeelavie');
        assert(Base\Str::padLeft('é',24,'lavie',true) === 'ééééééééééééééééééélavie');
        assert(Base\Str::padLeft('e!e',24,'lavie') === 'e!ee!ee!ee!ee!ee!eelavie');
        assert(Base\Str::padLeft('é!è',24,'lavie',true) === 'é!èé!èé!èé!èé!èé!èélavie');
        assert(Base\Str::padLeft('é!è',0,'lavie',true) === 'lavie');
        assert(Base\Str::padLeft('e|e',0,'lavie',false) === 'lavie');
        assert(Base\Str::padLeft('é!è',6,'lavie',true) === 'élavie');
        assert(Base\Str::padLeft('e|e',6,'lavie',false) === 'elavie');
        assert(Base\Str::padLeft('é|è',4,'',true) === 'é|èé');
        assert(Base\Str::padLeft('a|s',4,'',false) === 'a|sa');

        // padRight
        assert(Base\Str::padRight('e',23,'lavie') === 'lavieeeeeeeeeeeeeeeeeee');
        assert(Base\Str::padRight('é',23,'lavie',true) === 'lavieéééééééééééééééééé');
        assert(Base\Str::padRight('e!e',23,'lavie') === 'laviee!ee!ee!ee!ee!ee!e');
        assert(Base\Str::padRight('é!è',23,'lavie',true) === 'lavieé!èé!èé!èé!èé!èé!è');
        assert(Base\Str::padRight('é!è',5,'lavie',true) === 'lavie');
        assert(Base\Str::padRight('e|e',5,'lavie',false) === 'lavie');
        assert(Base\Str::padRight('é!è',6,'lavie',true) === 'lavieé');
        assert(Base\Str::padRight('e|e',6,'lavie',false) === 'laviee');
        assert(Base\Str::padRight('e|e',1,'lavie',false) === 'lavie');
        assert(Base\Str::padRight('e|e',1,'lavie',true) === 'lavie');
        assert(Base\Str::padRight('é|è',4,'',true) === 'é|èé');
        assert(Base\Str::padRight('a|s',4,'',false) === 'a|sa');

        // split
        assert(['t','a',' ','a',' '] === Base\Str::split(1,'ta a '));
        assert(count(Base\Str::split(1,'ta a é')) === 7);
        assert(['ta',' a',' '] === Base\Str::split(2,'ta a '));
        assert(['ta a '] === Base\Str::split(20,'ta a '));
        assert(['t','é','a'] !== Base\Str::split(1,'téa'));
        assert(['t','é','a'] === Base\Str::split(1,'téa',true));
        assert(['té','è ','à'] === Base\Str::split(2,'téè à',true));

        // chars
        assert(count(Base\Str::chars('lavieé',true)) === 6);

        // charCount
        $string = "la vie éest très belle mon ami !? '";
        assert(count(Base\Str::charCount($string,1)) === 19);
        assert(count(Base\Str::charCount($string,true)) === 18);
        assert(count(Base\Str::charCount($string,false)) === 19);
        assert(count(Base\Str::charCount($string)) === 19);

        // charImplode
        assert(Base\Str::charImplode(['a','É','e']) === 'aÉe');

        // charSplice
        assert(Base\Str::charSplice(1,2,'ok','blabla') === 'bokbla');
        assert(Base\Str::charSplice(0,1,1,'blabla') === '1labla');

        // normalizeLine
        $x = "test\rlala\nok\r\nbla";
        assert(Base\Str::normalizeLine($x) === 'test'.PHP_EOL.'lala'.PHP_EOL.'ok'.PHP_EOL.'bla');

        // lines
        assert(count(Base\Str::lines($x)) === 4);
        $y = "test \r lala \nok \r\nbla ";
        assert(Base\Str::lines($y,true)[0] === 'test');

        // lineCount
        assert(Base\Str::lineCount($x) === 4);

        // lineImplode
        assert(Base\Str::lineImplode(['test','test2']) === 'test'.PHP_EOL.'test2');

        // lineSplice
        $x = "test\rlala\nok\r\nbla";
        assert(Base\Str::lineSplice(1,1,'WHAT',$x) === 'test'.PHP_EOL.'WHAT'.PHP_EOL.'ok'.PHP_EOL.'bla');
        assert(Base\Str::lineSplice(1,1,2,$x) === 'test'.PHP_EOL.'2'.PHP_EOL.'ok'.PHP_EOL.'bla');
        assert(Base\Str::lineSplice(0,2,[1,'ok',3],$x) === '1'.PHP_EOL.'ok'.PHP_EOL.'3'.PHP_EOL.'ok'.PHP_EOL.'bla');
        assert(Base\Str::lineSplice(1,null,null,$x) === 'test'.PHP_EOL.'ok'.PHP_EOL.'bla');

        // words
        assert(Base\Str::words('asddas@asd.la.ca') === ['asddas@asd.la.ca']);
        assert(Base\Str::words('james()') === ['james()']);
        assert(['ééé',4=>'èè'] === Base\Str::words('ééé èè',true));
        assert(['eee',7=>'ee'] === Base\Str::words("eee  \n ee",false));
        assert(['eee',7=>'ee'] === Base\Str::words("eee  \n ee",true));
        assert([0=>'la',3=>'petite',10=>'ecole'] === Base\Str::words('la petite ecole',false));
        assert([0=>'la',3=>'petite',10=>'ecole'] === Base\Str::words('la petite ecole',true));
        assert([0=>'la',3=>'petite',10=>'école'] === Base\Str::words('la petite école',false));
        assert([0=>'la',3=>'petite',10=>'école'] === Base\Str::words('la petite école',true));
        assert([0=>'la',3=>'petite',11=>'école'] === Base\Str::words('la petite  école',true));
        assert([0=>'la',3=>'petite',11=>'école'] === Base\Str::words('la petite  école',false));

        // wordCount
        assert(3 === Base\Str::wordCount('la petite école'));
        assert(4 === Base\Str::wordCount('la petite  écoleé éèa'));

        // wordExplode
        assert(Base\Str::wordExplode('test test2      test4') === ['test','test2','test4']);

        // wordExplodeIndex
        assert(Base\Str::wordExplodeIndex(0,' test test2      test4') === 'test');

        // wordImplode
        assert(Base\Str::wordImplode(['a','b','c']) === 'a b c');

        // wordSplice
        $string = 'etesté test test test test test blabla test';
        assert(Base\Str::wordSplice(0,3,'hahaha',$string) === 'hahaha test test test blabla test');
        assert(Base\Str::wordSplice(0,3,2,$string) === '2 test test test blabla test');
        assert(Base\Str::wordSplice(0,0,['hahahaè','hihi'],$string) === 'hahahaè hihi etesté test test test test test blabla test');
        assert(Base\Str::wordSplice(3,7,['hahaha','hihi'],$string) === 'etesté test test hahaha hihi');
        assert(Base\Str::wordSplice(6,null,null,$string) === 'etesté test test test test test test');

        // wordSliceLength
        assert('word bla' === Base\Str::wordSliceLength(3,5,'word wa bla z'));
        assert('word' === Base\Str::wordSliceLength(4,100,'word wa bla z'));
        assert('ééé' === Base\Str::wordSliceLength(3,100,'ééé èè',true));
        assert('ééé èè' === Base\Str::wordSliceLength(2,null,'ééé èè',true));
        assert('wa bla z' === Base\Str::wordSliceLength(0,3,'word wa bla z'));
        assert('word wa bla z' === Base\Str::wordSliceLength(0,4,'word wa bla z'));
        assert('ééé èè' === Base\Str::wordSliceLength(0,3,'ééé èè',true));
        assert('' === Base\Str::wordSliceLength(0,3,'ééé èè',false));

        // wordStripLength
        assert('word' === Base\Str::wordStripLength(0,3,'word wa bla z'));
        assert('word' === Base\Str::wordStripLength(1,3,'word wa  bla z'));
        assert('' === Base\Str::wordStripLength(0,4,'word wa bla z'));

        // wordTotalLength
        assert('wor' === Base\Str::wordTotalLength(3,'word wa bla z'));
        assert('éééé' === Base\Str::wordTotalLength(4,'ééééé',true));
        assert('word' === Base\Str::wordTotalLength(4,'word wa bla z'));
        assert('word wa' === Base\Str::wordTotalLength(7,'word wa bla z'));
        assert('word waé' === Base\Str::wordTotalLength(8,'word waé bla z',true));
        assert('word wa bla' === Base\Str::wordTotalLength(11,'word wa bla z'));
        assert('word wa z' === Base\Str::wordTotalLength(11,'word wa blaz z'));
        assert('worda' === Base\Str::wordTotalLength(5,'wordaaaaaa'));
        assert(Base\Str::wordTotalLength(12,'zzzzzzzz@gmail.com.ca') === 'zzzzzzzz@gma');

        // wordwrap
        $string = 'Portez ceci lol tres bon bla';
        assert(Base\Str::wordwrap(10,$string,'\n',true) === 'Portez\nceci lol\ntres bon\nbla');
        assert(Base\Str::wordwrap(10,$string,'\n',true,true) === 'Portez\nceci lol\ntres bon\nbla');
        $string = 'loremipsumloremipsumloremipsum';
        assert(Base\Str::wordWrap(10,$string,'\n',true) === 'loremipsum\nloremipsum\nloremipsum');
        assert(Base\Str::wordWrap(10,$string,'\n',true,true) === 'loremipsum\nloremipsum\nloremipsum');
        assert(Base\Str::wordWrap(10,$string,'\n',false) === $string);
        assert(Base\Str::wordWrap(10,$string,'\n',false,true) === $string);
        $string = 'éééaéééÈ;ç ceci lol très bon bla';
        assert(Base\Str::wordwrap(10,$string,'\n',true,true) === 'éééaéééÈ;ç\nceci lol\ntrès bon\nbla');
        assert(Base\Str::wordwrap(10,$string,'',true,true) === '');
        assert(Base\Str::wordwrap(10,$string,'',true,false) === '');

        // replace
        $string = 'La petites %a%venir';
        assert('Le petites %e%venir' === Base\Str::replace(['a'=>'e'],$string));
        assert('La petites evenir' === Base\Str::replace(['%a%'=>'e'],$string));
        assert('234' === Base\Str::replace(['%a%'=>'e'],'234'));
        assert('234' === Base\Str::replace(['%a%'=>'e'],'234'));
        assert('true' === Base\Str::replace(['%a%'=>'e'],'true'));
        assert('bla' === Base\Str::replace(['%a%'=>['e']],'bla'));
        assert($string === Base\Str::replace(['PETITE'=>'grandé'],$string));
        assert('La grandés %a%venir' === Base\Str::replace(['PETITE'=>'grandé'],$string,false,false));
        $string = 'éÉè';
        assert('eEe' === Base\Str::replace(['éÉè'=>'eEe'],$string));
        assert('eEe' === Base\Str::replace(['éÉè'=>'eEe'],$string));
        assert('éÉè' === Base\Str::replace(['ééè'=>'eEe'],$string,false,false));
        assert(Base\Str::replace(['É'=>null],$string) === 'éè');
        assert(Base\Str::replace(['É'=>''],$string) === 'éè');
        assert(Base\Str::replace(['a'=>'z','b'=>'y','c'=>'x'],'La betice') === 'Lz yetixe');
        assert(Base\Str::replace(['a'=>'z','be'=>'y','b'=>'z','y'=>'z'],'La betice') === 'Lz ytice');
        assert(Base\Str::replace(['é'=>'è'],'La bétice') === 'La bètice');
        assert(Base\Str::replace(['é'=>'è'],'La bÉtice') === 'La bÉtice');

        // ireplace
        $string = 'La petites %a%venir';
        assert('La grandés %a%venir' === Base\Str::ireplace(['PETITE'=>'grandé'],$string));
        $string = 'La pétites %a%venir';
        assert(Base\Str::ireplace(['PéTITE'=>'grandé'],$string) === 'La grandés %a%venir');
        assert(Base\Str::ireplace(['PÉTITE'=>'grandé'],$string) === 'La pétites %a%venir'); // ireplace ne remplace pas les caractère accentés pas dans la bonne case

        // explode
        assert(['test','test2','test3'] === Base\Str::explode('|','test|test2|test3'));
        assert(['test',' test2 ','test3'] === Base\Str::explode('|','test| test2 |test3'));
        assert([''] === Base\Str::explode('|',''));
        assert(['test','test2|test3'] === Base\Str::explode('|','test|test2|test3',2));
        assert(['test','test2','test3'] === Base\Str::explode('|','test| test2 |test3 ',null,true));
        assert(['test','test2','test3'] === Base\Str::explode('|','test||test2|test3',null,true,true));
        assert(['test','test2 |test3'] === Base\Str::explode('|','test| test2 |test3 ',2,true));

        // explodeTrim
        assert(['test','test2','test3'] === Base\Str::explodeTrim('|','test| test2 |test3 '));

        // explodeClean
        assert(['test','test2','test3'] === Base\Str::explodeClean('|','test||test2|test3'));

        // explodeTrimClean
        assert(['test','test2','test3'] === Base\Str::explodeTrimClean('|','test|| test2|test3'));

        // explodeIndex
        assert('test' === Base\Str::explodeIndex(0,'|','test|test2|test3',2));
        assert('test3' === Base\Str::explodeIndex(-1,'|','test|test2|test3',3));
        assert(null === Base\Str::explodeIndex(4,'|','test|test2|test3',2));

        // explodeIndexes
        assert(['test','test2|test3'] === Base\Str::explodeIndexes([0,-1],'|','test|test2|test3',2));
        assert(['test',2=>'test3'] === Base\Str::explodeIndexes([0,-1],'|','test|test2|test3'));
        assert(['test',-4=>null] === Base\Str::explodeIndexes([0,-4],'|','test|test2|test3'));

        // explodeIndexesExists
        assert(['test','test2','test3'] === Base\Str::explodeIndexesExists([0],'|','test|test2|test3'));
        assert(null === Base\Str::explodeIndexesExists([0,-4],'|','test|test2|test3'));

        // explodeKeyValue
        assert(Base\Str::explodeKeyValue(':','test: bla',true,true) === ['test'=>'bla']);
        assert(Base\Str::explodeKeyValue(':','test: bla: ok ',true,true) === ['test'=>'bla: ok']);
        assert(Base\Str::explodeKeyValue(':','test',true,true) === []);

        // explodes
        $string = 'test:test,test2:test2,test3:test3';
        assert([['test','test'],['test2','test2'],['test3','test3']] === Base\Str::explodes([',',':'],$string));
        $string = 'te|st:te|st,te|st2:te|st2,te|st3:te/st3';
        assert(Base\Str::explodes([',',':','|'],$string)[2][0][1] === 'st3');

        // trim
        assert(Base\Str::trim(' asdasdé ') === 'asdasdé');
        assert(Base\Str::trim(' asdasdé ','é') === 'asdasd');
        assert(Base\Str::trim(' asdasdé ','é',false) === ' asdasdé ');

        // trimLeft
        assert(Base\Str::trimLeft(' asdasdé ') === 'asdasdé ');
        assert(Base\Str::trimLeft(' asdasdé ','a') === 'sdasdé ');
        assert(Base\Str::trimLeft(' asdasdé ','a',false) === ' asdasdé ');

        // trimRight
        assert(Base\Str::trimRight(' asdasdé ') === ' asdasdé');
        assert(Base\Str::trimRight(' asdasdé ','é') === ' asdasd');
        assert(Base\Str::trimRight(' asdasdé ','é',false) === ' asdasdé ');
        assert(Base\Str::trimRight('lololooo@hotmial.ca.com') === 'lololooo@hotmial.ca.com');

        // repeatLeft
        assert('zzzcamel' === Base\Str::repeatLeft('z',3,'camel'));
        assert('Éècamel' === Base\Str::repeatLeft('Éè',1,'camel'));
        assert('camel' === Base\Str::repeatLeft('Éè',0,'camel'));
        assert('camel' === Base\Str::repeatLeft('Éè',-4,'camel'));

        // repeatRight
        assert('camelzzz' === Base\Str::repeatRight('z',3,'camel'));
        assert('camelzaza' === Base\Str::repeatRight('za',2,'camel'));

        // addSlash
        assert(Base\Str::addSlash("tesé l'article de l\"alc") === 'tesé l\\\'article de l\"alc');
        assert(Base\Str::addSlash('tesé l\'article de l"alc') === 'tesé l\\\'article de l\"alc');

        // stripSlash
        assert(Base\Str::stripSlash('tesé l\\\'ar\ticle \de l\"alc') === 'tesé l\'article de l"alc');

        // quote
        assert(Base\Str::quote('test') === "'test'");
        assert(Base\Str::quote('test',true) === '"test"');

        // unquote
        assert(Base\Str::unquote('"test"') === 'test');
        assert(Base\Str::unquote("'test'") === 'test');

        // doubleToSingleQuote
        assert("'L'article'" === Base\Str::doubleToSingleQuote('"L\'article"'));

        // singleToDoubleQuote
        assert('"L"article"' === Base\Str::singleToDoubleQuote("'L'article\""));

        // quoteChar
        assert(Base\Str::quoteChar("te@st+@ok_wel\l",'@_') === "te\@st+\@ok\_wel\l");
        assert(Base\Str::quoteChar("te@st+@ok_wel\l",['@','_']) === "te\@st+\@ok\_wel\l");
        assert(Base\Str::quoteChar('@','@') === "\@");

        // commaToDecimal
        assert('1.3' === Base\Str::commaToDecimal('1,3'));
        assert('1.3' === Base\Str::commaToDecimal('1.3'));

        // decimalToComma
        assert('1,3' === Base\Str::decimalToComma('1.3'));
        assert('1,3' === Base\Str::decimalToComma('1.3'));
        assert('1,3' === Base\Str::decimalToComma('1,3'));

        // similar
        assert(Base\Str::similar('testlavie','testLavie') === (float) 100);
        assert(Base\Str::similar('tÉstlavie','téstlavie') === (float) 100);
        assert(Base\Str::similar('tÉstlavie','téstlavie',false) === (float) 90);

        // levenshtein
        assert(Base\Str::levenshtein('testlavie','testLavie') === 0);
        assert(Base\Str::levenshtein('téstlavie','tÉstlavie') === 0);
        assert(Base\Str::levenshtein('téstlavie','tÉstlavie',false) === 1);

        // random
        assert(strlen(Base\Str::random(10)) === 10);
        assert(Base\Str::random(3,'a',true) === 'aaa');
        assert(Base\Str::random(3,'a',false) === 'aaa');

        // randomPrefix
        assert(strlen(Base\Str::randomPrefix('WHAT',10)) === 14);

        // fromCamelCase
        assert([] === Base\Str::fromCamelCase(''));
        assert([0=>'camel'] === Base\Str::fromCamelCase('camel'));
        assert([0=>'camel',1=>'Case'] === Base\Str::fromCamelCase('camelCase'));
        assert([0=>'camel',1=>'CaseÉtest'] === Base\Str::fromCamelCase('camelCaseÉtest'));
        assert([0=>'camel',1=>'Case',2=>'Eest'] === Base\Str::fromCamelCase('camelCaseEest'));
        assert(Base\Str::fromCamelCase('jAmesOk') === ['j','Ames','Ok']);
        assert(Base\Str::fromCamelCase('JAmesOk') === ['J','Ames','Ok']);
        assert(Base\Str::fromCamelCase('JamesOk') === ['James','Ok']);

        // toCamelCase
        assert('camelCaseTest' === Base\Str::toCamelCase('_','camel_case_test'));
        if($isCli === false)
        assert('cameléCaseTest' === Base\Str::toCamelCase('_','camelé_case_test'));
        assert('camelCaseTest23' === Base\Str::toCamelCase('_','camel_case_test_2_3'));
        assert('testTest23Test4' === Base\Str::toCamelCase('_',['test','test2',3,'3','test4']));
        assert('testÉtst23Test4' === Base\Str::toCamelCase('_',['test','étst2',3,'3','test4'],true));
        assert(Base\Str::toCamelCase('_','Camel_CAse_Test') === 'camelCaseTest');

        // loremIpsum
        assert(strlen(Base\Str::loremIpsum()) > 40);

        // s
        assert('' === Base\Str::s(0));
        assert('s' === Base\Str::s(2));
        assert('y' === Base\Str::s(2,'y'));
        assert(Base\Str::s('asdas') === 's');
        assert(Base\Str::s([1]) === '');
        assert(Base\Str::s([1,2]) === 's');

        // plural
        assert('test' === Base\Str::plural(0,'test'));
        assert('tests' === Base\Str::plural([1,2],'test'));
        assert('mois' === Base\Str::plural(2,'mois'));
        $string = 'Le%s% cheva%l% %est% grand%s%';
        assert('Le cheval est grand' === Base\Str::plural([],$string,['l'=>'ux','est'=>'sont']));
        assert('Les chevaux sont grands' === Base\Str::plural(2,$string,['l'=>'ux','est'=>'sont']));

        // replaceAccent
        assert('TESTEeeac' === Base\Str::replaceAccent('TESTÉéèàç'));

        // removeAccent
        assert('TEST' === Base\Str::removeAccent('TESTÉéèàç'));
        assert('test' === Base\Str::removeAccent('test'));

        // fixUnicode

        // removeUnicode
        assert('camel' === Base\Str::removeUnicode('camel'));
        assert('123' === Base\Str::removeUnicode('123'));
        assert("l'articlé" === Base\Str::removeUnicode("l'articlé"));
        assert('Uncidoe ' === Base\Str::removeUnicode('Uncidoe ❄❄❄'));
        assert('éè ç !' === Base\Str::removeUnicode('👦🏼👦🏼👦🏼👦éè ç !'));

        // removeSymbols
        assert('camel' === Base\Str::removeSymbols('camel'));
        assert('b' === Base\Str::removeSymbols('<b>'));
        assert('Uncidoe ' === Base\Str::removeSymbols('Uncidoe ❄❄❄'));
        assert('1Uncidoe ' === Base\Str::removeSymbols('1Uncidoe ❄❄❄-|+<@'));
        assert('éè ç ' === Base\Str::removeSymbols("éè ç !'"));

        // removeLineBreaks
        assert(' test asdsa ' === Base\Str::removeLineBreaks(' test asdsa '));
        assert(' test asdsa  ' === Base\Str::removeLineBreaks("\n test asdsa  \n"));

        // removeTabs
        assert(Base\Str::removeTabs('	test asdsa') === 'test asdsa');

        // removeWhitespace
        assert(Base\Str::removeWhitespace('rand()') === 'rand()');
        assert('test asdsa' === Base\Str::removeWhitespace(' test asdsa '));
        assert('test asdsa' === Base\Str::removeWhitespace(" test  \n  asdsa "));
        assert('test asdsa' === Base\Str::removeWhitespace(' test asdsa '));
        assert(Base\Str::removeWhitespace(' test asdsa &nbsp; ') === 'test asdsa');
        assert('<test asdsa></span> <span>dsa</span>' === Base\Str::removeWhitespace('<test asdsa></span> <span>dsa</span>'));
        assert('test asdsa' === Base\Str::removeWhitespace(' 
		test asdsa
		  '));
        assert(Base\Str::removeWhitespace('Conseillère cadre  innovation') === 'Conseillère cadre innovation');
        assert(Base\Str::removeWhitespace('    ') === '');

        // removeAllWhitespace
        assert('testasdsa' === Base\Str::removeAllWhitespace(' test&nbsp; asdsa '));
        assert('testasdsa' === Base\Str::removeAllWhitespace(' 
		test asdsa
		  '));
        assert(Base\Str::removeAllWhitespace('    ') === '');

        // removeConsecutive
        assert(Base\Str::removeConsecutive('_','la__ok') === 'la_ok');
        assert(Base\Str::removeConsecutive(' ','la      ok') === 'la ok');
        assert(Base\Str::removeConsecutive(' ','la      ok','-') === 'la-ok');

        // removeBom
        assert(Base\Str::removeBom('abc') === 'abc');
        assert(Base\Str::removeBom(Base\Str::bom().'abc'.Base\Str::bom()) === 'abc');
        assert(Base\Str::removeBom(Base\Str::bom()) === '');

        // remove
        $string = 'La petites avenir';
        assert('La ptits avnir' === Base\Str::remove('e',$string));
        assert('La ptits avni' === Base\Str::remove(['e','r'],$string));

        // keepNumeric
        assert('123.40' === Base\Str::keepNumeric('123.40'));
        assert('-123.40' === Base\Str::keepNumeric('-123.40'));
        assert('12340' === Base\Str::keepNumeric('123,40'));

        // keepNumber
        assert('' === Base\Str::keepNumber('TESTÉéèàç'));
        assert('1234' === Base\Str::keepNumber('z1234acc'));
        assert('z1234z' === Base\Str::keepNumber('z1234zacc','z'));
        assert('12340' === Base\Str::keepNumber('-123.40'));

        // keepAlpha
        assert('TEST' === Base\Str::keepAlpha('TESTÉéèàç1234 _ - + @'));

        // keepAlphanumeric
        assert('123testacxzc' === Base\Str::keepAlphanumeric('!123testacxzcéè'));
        assert('!123testacxzc z' === Base\Str::keepAlphanumeric('!123testacxzcéè z','! '));

        // keepAlphanumericPlus
        assert('lololooo@gmail.com' === Base\Str::keepAlphanumericPlus('lololooo@gmail.com'));
        assert('lololooo@gmail.com1lololooo@gmail.com' === Base\Str::keepAlphanumericPlus('lololooo@gmail.com,1lololooo@gmail.com'));

        // keepAlphanumericPlusSpace
        assert('lololooo@gmail.com' === Base\Str::keepAlphanumericPlusSpace('lololooo@gmail.com'));
        assert('lololooo@gmail.com 1lololooo@gmail.com' === Base\Str::keepAlphanumericPlusSpace('lololooo@gmail.com, 1lololooo@gmail.com'));

        // ascii
        $string = "La❄ vie est bellé OK L'article !?!#$@ de la m...or,";
        assert(Base\Str::ascii($string) === "La vie est belle OK L'article !?!#$@ de la m...or,");
        assert(Base\Str::ascii($string,false) === "La vie est bell OK L'article !?!#$@ de la m...or,");

        // asciiLower
        $string = "La❄ vie est bellé OK L'article !?!#$@ de la m...or,";
        assert(Base\Str::asciiLower($string) === "la vie est belle ok l'article !?!#$@ de la m...or,");

        // clean
        assert('Larticle' === Base\Str::clean(" L'articlé ! "));
        assert('1Uncidoea' === Base\Str::clean('1Uncidoe a ❄❄❄-| +<@'));
        assert('2' === Base\Str::clean('2'));

        // cleanLower
        assert('larticle' === Base\Str::cleanLower(" L'articlé ! "));
        assert('1uncidoe' === Base\Str::cleanLower('1Uncidoe ❄❄❄-| +<@'));

        // cleanKeepSpace
        assert('Lar ticle' === Base\Str::cleanKeepSpace(" L'ar ticlé ! "));
        assert('1Unc idoe' === Base\Str::cleanKeepSpace('1Unc idoe ❄❄❄-| +<@'));

        // def
        $string = ' test_lala ';
        assert('Test lala' === Base\Str::def($string));
        assert('Test lala' === Base\Str::def(' tést_lala!❄❄❄-| +<@ '));

        // pointer
        assert(Base\Str::pointer('user-2') === ['user',2]);
        assert(Base\Str::pointer('user/2','/') === ['user',2]);
        assert(Base\Str::pointer('user-a') === null);

        // toPointer
        assert(Base\Str::toPointer('user',2) === 'user-2');
        assert(Base\Str::toPointer('user',2,'/') === 'user/2');

        // map
        $array = [' test ',2=>' test2',3,[]];
        assert(Base\Str::map('trim',$array) === ['test',2=>'test2',3,[]]);

        // excerpt
        assert(Base\Str::excerpt(22,'emondppph@hotmail.com.ca',['trim'=>false]) === 'emondppph@hotmail.c...');
        assert('la <b>petite</b> école' === Base\Str::excerpt(null," la <b>petite</b>\n école "));
        assert('la petite école' === Base\Str::excerpt(0," la petite\n école "));
        assert('la' === Base\Str::excerpt(3,'la petite école'));
        assert('la petit...' === Base\Str::excerpt(13,'la petite.,; école',['rtrim'=>'e']));
        assert('la' === Base\Str::excerpt(3,'la petite école',['suffix'=>false]));
        assert('l::' === Base\Str::excerpt(3,'la petite école',['suffix'=>'::']));
        assert(Base\Str::excerpt(21,"Centre d'hébergement Cédicl-Goidnasd ok dsad sa") === "Centre d'hébergem...");
        assert(Base\Str::excerpt(20,"Centre d'hébergement Cédicl-Goidnasd ok dsad sa",['mb'=>true]) === "Centre d'hébergem...");
        assert(Base\Str::excerpt(6,'ééééé') === 'é...');
        assert(Base\Str::excerpt(5,'ééééé') === 'é...');
        assert(strlen(Base\Str::excerpt(75,'Accueillir un stagiaire – des avantages à découvrir | Intranet du wwwwwww')) === 72);

        // lengthTrimSuffix
        assert(Base\Str::lengthTrimSuffix(3,'la petite école')['strSuffix'] === 'la');

        // output
        assert("la <b>petite</b>\n école" === Base\Str::output(" la <b>petite</b>\n école "));
        assert('la <b>petite</b> école' === Base\Str::output(' la <b>petite</b> école '));
        assert('z' === Base\Str::output('👦🏼👦🏼👦🏼👦🏼 z '));

        // getEol
        assert(Base\Str::getEol("la <b>petite</b>\n école") === "\n");
        assert(Base\Str::getEol("la <b>petite</b>\r école") === null);
        assert(Base\Str::getEol("la <b>petite</b>\r\n école") === "\r\n");
        assert(Base\Str::getEol("la \n<b>petite</b>\r\n école") === "\r\n");

        // eol
        assert(Base\Str::eol(3,"\n") === "\n\n\n");
        assert(Base\Str::eol(2,"\r\n") === "\r\n\r\n");

        // bom
        assert(strlen(Base\Str::bom()) === 3);

        return true;
    }
}
?>