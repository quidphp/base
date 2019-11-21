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

// validate
// class for testing Quid\Base\Validate
class Validate extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        global $a;
        $publicPath = Base\Finder::normalize('[public]');
        $fp = Base\Res::tmpFile();
        $ua = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0';
        $nonUtf8 = "\xF0";
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');
        $current = Base\Res::open($_file_);
        $headers = ['HTTP/1.0 200 OK','Content-Type: text/json; charset=UTF-8','test: ok'];
        Base\Response::setContentType('html');

        // is
        assert(Base\Validate::is('email','lololooo@mail.zom'));
        assert(!Base\Validate::is('username','lololooo@mail.zom'));
        assert(Base\Validate::is('string','lololooo@mail.zom'));
        assert(Base\Validate::is(function($x) { if(is_string($x)) return true; },'lololooo@mail.zom'));
        assert(!Base\Validate::is('arr','lololooo@mail.zom'));
        assert(Base\Validate::is(['='=>'test'],'test'));
        assert(Base\Validate::is(['>='=>2],2));
        assert(!Base\Validate::is(['>='=>3],2));
        assert(!Base\Validate::is(['!='=>'test'],'test'));
        assert(Base\Validate::is(new \DateTime('now'),new \DateTime('now')));
        assert(Base\Validate::is(\DateTime::class,new \DateTime('now')));
        assert(Base\Validate::is(function($x) { if(is_object($x)) return true; },new \DateTime('now')));
        assert(Base\Validate::is("/^\d{4}$/",2013));
        assert(!Base\Validate::is('',2013));
        assert(Base\Validate::is(\DateTime::class,new \DateTime('now')));
        assert(!Base\Validate::is(\DateTime::class,\DateTime::class));

        // isNot
        assert(Base\Validate::isNot(['strLength'=>23],'lop'));

        // isCom
        assert(Base\Validate::isCom('string',[]) === 'string');
        assert(Base\Validate::isCom('email',[]) === 'email');
        assert(Base\Validate::isCom(function() { },[]) === 'closure');
        assert(Base\Validate::isCom(new \DateTime('now'),[]) === ['instance'=>'DateTime']);
        assert(Base\Validate::isCom(['<'=>3],3) === ['<'=>3]);
        assert(Base\Validate::isCom(['strLength'=>3],3) === ['strLength'=>3]);
        assert(Base\Validate::isCom(\James::class,new \DateTime('now')) === 'James');
        assert(Base\Validate::isCom(function() { return 'well'; },2) === 'well');
        assert(Base\Validate::isCom(['extension'=>'jpg'],$_file_) === ['extension'=>'jpg']);
        assert(Base\Validate::isCom(['extension'=>['jpg','php']],$_file_));
        assert(Base\Validate::isCom(['extensions'=>['jpg','php']],[$_file_]));
        assert(Base\Validate::isCom(['maxFilesize'=>524288000],$_file_));
        assert(Base\Validate::isCom(['maxFilesize'=>3000],$_file_) === ['maxFilesize'=>3000]);
        assert(Base\Validate::isCom(['maxFilesizes'=>524288000],[$_file_]));
        assert(Base\Validate::isCom('strLatin','Привет') === 'strLatin');

        // isAnd
        assert(Base\Validate::isAnd(['string','strNotEmpty',['strLength'=>3]],'lop'));
        assert(!Base\Validate::isAnd(['string','strNotEmpty',['strLength'=>2]],'lop'));
        assert(Base\Validate::isAnd(['string','strNotEmpty','strLength'=>3],'lop'));
        assert(Base\Validate::isAnd(['>'=>2,'>='=>3],3));
        assert(!Base\Validate::isAnd(['>'=>2,'>='=>3],2));

        // isAndCom
        assert(Base\Validate::isAndCom(['string','strNotEmpty',['strLength'=>2]],'lop') === [['strLength'=>2]]);
        assert(Base\Validate::isAndCom(['string','strNotEmpty','strLength'=>3],'lop'));

        // isOr
        assert(Base\Validate::isOr(['string','strNotEmpty',['strLength'=>3]],'lop'));
        assert(Base\Validate::isOr(['string','strNotEmpty',['strLength'=>2]],'lop'));
        assert(Base\Validate::isOr(['array','strEmpty','strLength'=>3],'lop'));

        // isXor
        assert(!Base\Validate::isXor(['string','strNotEmpty',['strLength'=>3]],'lop'));
        assert(!Base\Validate::isXor(['string','strNotEmpty',['strLength'=>2]],'lop'));
        assert(Base\Validate::isXor(['string','strEmpty',['strLength'=>2]],'lop'));
        assert(Base\Validate::isXor(['array','strEmpty','strLength'=>3],'lop'));

        // are
        assert(Base\Validate::are(function($x) { return true; },'lop','zip'));
        assert(Base\Validate::are(['strLength'=>3],'lop','zip'));
        assert(!Base\Validate::are(['strLength'=>3],'lozp','zip'));

        // areNot
        assert(!Base\Validate::areNot(['strLength'=>3],'lop','zip'));
        assert(Base\Validate::areNot(['strLength'=>3],'lozp','zip'));

        // areAnd
        assert(Base\Validate::areAnd(['strNotEmpty',['strLength'=>3]],'lop','zip'));
        assert(!Base\Validate::areAnd(['strEmpty',['strLength'=>3]],'lop','zip'));

        // areOr
        assert(Base\Validate::areOr(['strEmpty',['strLength'=>3]],'lop','zip'));

        // areXor
        assert(Base\Validate::areXor(['strEmpty',['strLength'=>3]],'lop','zip'));
        assert(!Base\Validate::areXor(['strNotEmpty',['strLength'=>3]],'lop','zip'));

        // one
        assert(Base\Validate::one('array',[]));
        assert(Base\Validate::one('bool',true));
        assert(Base\Validate::one('callable','strlen'));
        assert(Base\Validate::one('float',1.2));
        assert(Base\Validate::one('int',2));
        assert(Base\Validate::one('numeric','2'));
        assert(Base\Validate::one('null',null));
        assert(Base\Validate::one('object',new \DateTime('now')));
        assert(Base\Validate::one('resource',$fp));
        assert(Base\Validate::one('scalar',2));
        assert(Base\Validate::one('string','ok'));
        assert(Base\Validate::one('empty',[]));
        assert(Base\Validate::one('notEmpty',[1]));
        assert(Base\Validate::one('reallyEmpty',[]));
        assert(Base\Validate::one('notReallyEmpty',0));
        assert(Base\Validate::one('arrKey',2));
        assert(Base\Validate::one('arrNotEmpty',[1]));
        assert(Base\Validate::one('dateToDay','12-03-2017'));
        assert(Base\Validate::one('dateToDay','01-08-2016'));
        assert(Base\Validate::one('dateToMinute','12-03-2017 11:40'));
        assert(Base\Validate::one('dateToSecond','12-03-2017 11:40:30'));
        assert(Base\Validate::one('numberNotEmpty',2));
        assert(Base\Validate::one('numberPositive',2));
        assert(Base\Validate::one('numberNegative',-2));
        assert(Base\Validate::one('numberOdd',1));
        assert(Base\Validate::one('numberEven',2));
        assert(Base\Validate::one('numberWhole','0'));
        assert(Base\Validate::one('numberWholeNotEmpty',1));
        assert(Base\Validate::one('numberDecimal','2.3'));
        assert(!Base\Validate::one('scalarNotBool',true));
        assert(Base\Validate::one('scalarNotBool',2));
        assert(Base\Validate::one('slug','ok-lala'));
        assert(!Base\Validate::one('slug','ok-lala/test'));
        assert(Base\Validate::one('fragment','ok-lala'));
        assert(Base\Validate::one('slugPath','ok-lala/test'));
        assert(Base\Validate::one('strNotEmpty','o'));
        assert(Base\Validate::one('uriRelative','/james.php'));
        assert(Base\Validate::one('uriAbsolute','https://google.com'));
        assert(!Base\Validate::one('fileUploads',[[]]));

        // two
        assert(Base\Validate::two('length',2,'te'));
        assert(Base\Validate::two('minLength',1,2));
        assert(Base\Validate::two('maxLength',2,'te'));
        assert(Base\Validate::two('arrCount',2,[1,2]));
        assert(Base\Validate::two('arrMinCount',2,[1,2]));
        assert(Base\Validate::two('arrMaxCount',2,[1,2]));
        assert(Base\Validate::two('fileCount',2,[1,2]));
        assert(Base\Validate::two('fileMinCount',2,[1,2]));
        assert(Base\Validate::two('fileMaxCount',2,'[1,2]'));
        assert(Base\Validate::two('dateFormat','d-m-Y','12-03-2017'));
        assert(Base\Validate::two('numberLength',3,1.3));
        assert(Base\Validate::two('numberMinLength',1,1));
        assert(Base\Validate::two('numberMaxLength',2,'12'));
        assert(Base\Validate::two('setCount',2,'1,2'));
        assert(Base\Validate::two('setMinCount',1,'1,2'));
        assert(Base\Validate::two('setMaxCount',3,'1,2'));
        assert(Base\Validate::two('strLength',2,'te'));
        assert(Base\Validate::two('strMinLength',1,'te'));
        assert(Base\Validate::two('strMaxLength',2,'te'));
        assert(Base\Validate::two('uriHost','vimeo.com','https://vimeo.com/ok'));
        assert(Base\Validate::two('extension',['jpg','php'],$_file_));

        // regex
        assert(Base\Validate::regex('email','lololooo@mailz.com'));

        // preg
        assert(Base\Validate::preg('/^.{0,20}$/','lololooo@mailz.com'));

        // instance
        assert(Base\Validate::instance(new \DateTime('now'),new \DateTime('now')));
        assert(!Base\Validate::instance(new \stdclass(),new \DateTime('now')));

        // isCompareSymbol
        assert(!Base\Validate::isCompareSymbol('-'));
        assert(Base\Validate::isCompareSymbol('>='));

        // compare
        assert(Base\Validate::compare(1,'=',1));
        assert(Base\Validate::compare(1,'===',1));
        assert(!Base\Validate::compare(1,'=','1'));
        assert(Base\Validate::compare(1,'==','1'));
        assert(Base\Validate::compare(1,'!','2'));
        assert(Base\Validate::compare(2,'>',1));
        assert(Base\Validate::compare(3,'<=',3));
        assert(!Base\Validate::compare(3,'<',3));

        // pattern
        assert(Base\Validate::pattern(['minLength'=>2]) === '.{2,}');
        assert(Base\Validate::pattern(['james','minLength'=>'3']) === '.{3,}');
        assert(Base\Validate::pattern(['minLength'=>[]]) === '.{%%%,}');
        assert(Base\Validate::pattern(['james','password','minLength'=>[]]) === '^(?=.{5,30})(?=.*\d)(?=.*[A-z]).*');
        assert(Base\Validate::pattern('^(?=.{5,30})(?=.*\d)(?=.*[A-z]).*') === '^(?=.{5,30})(?=.*\d)(?=.*[A-z]).*');

        // patternKey
        assert(Base\Validate::patternKey(['james','minLength'=>'3']) === ['minLength'=>'3']);

        // prepareConditions

        // sameType
        assert(Base\Validate::sameType(false,false));
        assert(Base\Validate::sameType(2,1));
        assert(Base\Validate::sameType(2,1,3,4,5,6,7));
        assert(!Base\Validate::sameType(2,'1',3,4,5,6,7));
        assert(Base\Validate::sameType(new \stdclass(),new \stdclass()));
        assert(!Base\Validate::sameType(new \DateTime('now'),new \stdclass()));
        assert(Base\Validate::sameType(new \DateTime('now'),new \DateTime('now'),new \DateTime('now')));

        // isEmpty
        assert(Base\Validate::isEmpty(false));
        assert(Base\Validate::isEmpty(0));
        assert(!Base\Validate::isEmpty(1));

        // isNotEmpty
        assert(Base\Validate::isNotEmpty(true));

        // isReallyEmpty
        assert(Base\Validate::isReallyEmpty(''));
        assert(Base\Validate::isReallyEmpty(null));
        assert(Base\Validate::isReallyEmpty([]));
        assert(!Base\Validate::isReallyEmpty(false));
        assert(!Base\Validate::isReallyEmpty(0));
        assert(!Base\Validate::isReallyEmpty('0'));
        assert(!Base\Validate::isReallyEmpty(' '));
        assert(Base\Validate::isReallyEmpty(' ',true));

        // isNotReallyEmpty
        assert(Base\Validate::isNotReallyEmpty(0));
        assert(!Base\Validate::isNotReallyEmpty(null));

        // isAlpha
        assert(Base\Validate::isAlpha('abcd'));
        assert(!Base\Validate::isAlpha('ab1cd'));

        // isAlphanumeric
        assert(Base\Validate::isAlphanumeric('1234abcd'));
        assert(!Base\Validate::isAlphanumeric('1234a-bcd'));

        // isAlphanumericDash
        assert(Base\Validate::isAlphanumericDash('1234-a_bcd'));
        assert(!Base\Validate::isAlphanumericDash('1234-a_bcd!'));

        // isAlphanumericSlug
        assert(Base\Validate::isAlphanumericSlug('1234-abcd'));
        assert(!Base\Validate::isAlphanumericSlug('1234-a@bcd'));
        assert(!Base\Validate::isAlphanumericSlug('1234-abcd/test'));

        // isAlphanumericSlugPath
        assert(Base\Validate::isAlphanumericSlugPath('1234-abcd/what/ok'));
        assert(!Base\Validate::isAlphanumericSlugPath('1234-ab_cd/what/ok'));

        // isAlphanumericPlus
        assert(Base\Validate::isAlphanumericPlus('12_.34-ab@cd'));
        assert(!Base\Validate::isAlphanumericPlus('12_.34-a b@cd'));

        // isAlphanumericPlusSpace
        assert(Base\Validate::isAlphanumericPlusSpace('12_.@34-ab cd'));
        assert(!Base\Validate::isAlphanumericPlusSpace('12_.@34-ab+cd'));

        // isUsername
        assert(!Base\Validate::isUsername('bla'));
        assert(!Base\Validate::isUsername('blaé'));
        assert(Base\Validate::isUsername('bla2'));
        assert(Base\Validate::isUsername('wwezsas'));
        assert(Base\Validate::isUsername('wwezsas-pl'));
        assert(!Base\Validate::isUsername("AriD'a66113SRSOR"));
        assert(!Base\Validate::isUsername('wwezsas.bla'));
        assert(Base\Validate::isUsername('wwezsas.bla','loose'));
        assert(Base\Validate::isUsername('wwezsas@bla.com','loose'));

        // isPassword
        assert(Base\Validate::isPassword('bla22'));
        assert(!Base\Validate::isPassword('@#&*()*#!'));
        assert(!Base\Validate::isPassword('bla'));
        assert(!Base\Validate::isPassword('12324323'));
        assert(!Base\Validate::isPassword('blabla'));
        assert(Base\Validate::isPassword('blas','loose'));
        assert(Base\Validate::isPassword(1243,'loose'));

        // isEmail
        assert(Base\Validate::isEmail('bla@bla.bla'));
        assert(!Base\Validate::isEmail('blabla.bla'));
        assert(Base\Validate::isEmail('bla@bla.z'));
        assert(Base\Validate::isEmail('bla@bla.zzzzzzz'));

        // isHex
        assert(Base\Validate::isHex('ffffff'));
        assert(Base\Validate::isHex('fff'));
        assert(!Base\Validate::isHex('ffffffff'));
        assert(!Base\Validate::isHex('#ffffff'));

        // isTag
        assert(Base\Validate::isTag('<b>bla</b>'));
        assert(!Base\Validate::isTag('<br/>'));

        // isYear
        assert(Base\Validate::isYear(2014));
        assert(Base\Validate::isYear('2015'));
        assert(!Base\Validate::isYear('15'));

        // isAmericanZipcode
        assert(Base\Validate::isAmericanZipcode(12345));
        assert(Base\Validate::isAmericanZipcode('98764'));
        assert(!Base\Validate::isAmericanZipcode('198764'));

        // isCanadianPostalcode
        assert(Base\Validate::isCanadianPostalcode('H3X1R6'));
        assert(!Base\Validate::isCanadianPostalcode('HHX1R6'));

        // isNorthAmericanPhone
        assert(Base\Validate::isNorthAmericanPhone('5144835603'));
        assert(!Base\Validate::isNorthAmericanPhone('15144835603'));
        assert(!Base\Validate::isNorthAmericanPhone('+15144835603'));

        // isPhone
        assert(Base\Validate::isPhone('(513) 502-1503'));
        assert(!Base\Validate::isPhone('(513) 502-150'));
        assert(!Base\Validate::isPhone('1234'));
        assert(!Base\Validate::isPhone('123456789'));
        assert(Base\Validate::isPhone('1234567890'));
        assert(!Base\Validate::isPhone('123456789a'));
        assert(Base\Validate::isPhone('123456789a0'));
        assert(Base\Validate::isPhone('5144835603'));
        assert(Base\Validate::isPhone('+4215144835603'));
        assert(Base\Validate::isPhone('5145081203 poste 201'));

        // isIp
        assert(Base\Validate::isIp('1.2.3.4'));
        assert(!Base\Validate::isIp('1.2.3.4.6.7'));
        assert(!Base\Validate::isIp('1234'));

        // isDate
        assert(Base\Validate::isDate('2017-08-23'));
        assert(!Base\Validate::isDate('2017-08'));

        // isDatetime
        assert(Base\Validate::isDatetime('2017-08-23 12:10:15'));
        assert(!Base\Validate::isDatetime('2017-08-23'));
        assert(!Base\Validate::isDatetime('2017-08-23 12:10'));

        // isTime
        assert(Base\Validate::isTime('12:10:15'));
        assert(!Base\Validate::isTime('12:10'));
        assert(!Base\Validate::isTime('12-10-15'));

        // isUri
        assert(Base\Validate::isUri('http://google.com'));
        assert(Base\Validate::isUri('http://google.com/test'));
        assert(Base\Validate::isUri('test/test2/test3'));
        assert(Base\Validate::isUri('/test/test2/test3-tes4_test5/james.jpg'));
        assert(Base\Validate::isUri('/test/test2/test3-tes4_test5/james,jpg'));
        assert(Base\Validate::isUri('/test/test2/t?est3-tes4_test5/james.jpg'));
        assert(!Base\Validate::isUri(''));
        assert(!Base\Validate::isUri('test'));
        assert(Base\Validate::isUri('/'));
        assert(!Base\Validate::isUri(null));
        assert(!Base\Validate::isUri(true));
        assert(!Base\Validate::isUri(1));

        // isUriPath
        assert(Base\Validate::isUriPath('test/test2/test3'));
        assert(Base\Validate::isUriPath('/test/test2/test3-tes4_test5/james.jpg'));
        assert(!Base\Validate::isUriPath('/test/test2/test3-tes4_test5/james,jpg'));
        assert(!Base\Validate::isUriPath('/test/test2/t?est3-tes4_test5/james.jpg'));
        assert(Base\Validate::isUriPath(''));
        assert(Base\Validate::isUriPath('/'));
        assert(!Base\Validate::isUriPath(null));
        assert(!Base\Validate::isUriPath(true));
        assert(Base\Validate::isUriPath(1));

        // isFqcn
        assert(Base\Validate::isFqcn('test'));
        assert(!Base\Validate::isFqcn('test/test2'));
        assert(Base\Validate::isFqcn("\Quid\RootTestBla"));

        // isTable
        assert(!Base\Validate::isTable('asdas-as'));
        assert(Base\Validate::isTable('asd_as'));
        assert(!Base\Validate::isTable('123asd_as'));
        assert(Base\Validate::isTable('a123asd_as'));
        assert(!Base\Validate::isTable('Asdasas'));
        assert(Base\Validate::isTable('asdaAas'));

        // isCol
        assert(Base\Validate::isCol('asdas'));
        assert(Base\Validate::isCol('asDas'));
        assert(!Base\Validate::isCol('Asdas'));
        assert(!Base\Validate::isCol('asdas-as'));
        assert(!Base\Validate::isCol('123asdasas'));

        // isEqual
        assert(Base\Validate::isEqual(2,2));

        // isSoftEqual
        assert(Base\Validate::isSoftEqual('2',2));

        // isInequal
        assert(Base\Validate::isInequal('2',2));

        // isSoftInequal
        assert(Base\Validate::isSoftInequal(3,2));

        // isBigger
        assert(Base\Validate::isBigger(3,2));

        // isBiggerOrEqual
        assert(Base\Validate::isBiggerOrEqual(2,2));

        // isSmaller
        assert(Base\Validate::isSmaller(1,2));

        // isSmallerOrEqual
        assert(Base\Validate::isSmallerOrEqual(2,2));

        // arr
        assert(Base\Validate::arr(null,[]));
        assert(!Base\Validate::arr(true,[]));
        assert(Base\Validate::arr(true,[1,2,3]));
        assert(Base\Validate::arr(false,[]));
        assert(!Base\Validate::arr(false,[1,2,3]));
        assert(Base\Validate::arr(3,[1,2,3]));
        assert(!Base\Validate::arr(3,[1,3]));
        $array = ['key'=>1,'james'=>'bla','ok'=>0,'z'=>null];
        assert(Base\Validate::arr('key',$array));
        assert(!Base\Validate::arr('key2',$array));
        assert(Base\Validate::arr(['key','james','ok'],$array));
        assert(!Base\Validate::arr(['key','james','okz'],$array));
        assert(Base\Validate::arr(['ok'=>true],$array));
        assert(Base\Validate::arr(['james'=>true],$array));
        assert(!Base\Validate::arr(['z'=>true],$array));
        assert(!Base\Validate::arr(['ok'=>false],$array));
        assert(Base\Validate::arr(['z'=>false],$array));
        assert(Base\Validate::arr(['key'=>1,'ok'=>true],$array));
        assert(!Base\Validate::arr(['key'=>1,'ok'=>false],$array));
        assert(Base\Validate::arr(['james'=>'string','ok'=>'int'],$array));
        assert(!Base\Validate::arr(['james'=>'string','ok'=>'array'],$array));
        assert(Base\Validate::arr(['key'=>['='=>1]],$array));

        // dig
        assert(Base\Validate::dig(['minLength'=>2],'test'));
        assert(!Base\Validate::dig(['minLength'=>2],'t'));
        assert(Base\Validate::dig(['minLength'=>2],['test','ok']));
        assert(Base\Validate::dig(['minLength'=>2],['test',['ok','well']]));
        assert(!Base\Validate::dig(['minLength'=>2],['test',['ok','n']]));
        assert(!Base\Validate::dig(['minLength'=>2],['test',['n','nk']]));

        // cleanup
        assert(Base\File::unlink($fp));

        return true;
    }
}
?>