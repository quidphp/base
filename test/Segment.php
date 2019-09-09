<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// segment
// class for testing Quid\Base\Segment
class Segment extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // isWrapped
        assert(Base\Segment::isWrapped('[]','[test]'));
        assert(!Base\Segment::isWrapped('[]','[test'));

        // has
        assert(Base\Segment::has('[]','test/[ok]-1/test3/[four]/[five]'));
        assert(!Base\Segment::has('[]','test/ok]-1/test3/[four/five}'));
        assert(Base\Segment::has('[]','test/ok]-1/test3/[four/[five]'));
        assert(!Base\Segment::has('[]','test/ok]-1/test3/[four/[five'));

        // getDelimiter
        assert(Base\Segment::getDelimiter('[]') === ['[',']']);
        assert(Base\Segment::getDelimiter(null) === ['[',']']);
        assert(Base\Segment::getDelimiter(null,true) === ['\[','\]']);
        assert(Base\Segment::getDelimiter(['a','b']) === ['a','b']);
        assert(Base\Segment::getDelimiter(['a','b'],true) === ['a','b']);

        // wrap
        assert(Base\Segment::wrap('[]','test') === '[test]');
        assert(Base\Segment::wrap('[]','[test]') === '[test]');

        // strip
        assert(Base\Segment::strip('[]','[test]') === 'test');
        assert(Base\Segment::strip('[]','test') === 'test');

        // escape
        assert(Base\Segment::escape('[]') === '[]');
        assert(Base\Segment::escape('[') === "\[");

        // count
        $string = 'test [test] asd [test2] [test3][test4]';
        assert(4 === Base\Segment::count('[]',$string));
        assert(0 === Base\Segment::count('()',$string));
        $string = 'test %test%';
        assert(1 === Base\Segment::count('%',$string));
        $string = 'test -test- -bla-';
        assert(2 === Base\Segment::count('-',$string));
        $string = 'bla1bla bla2bla bla3bla';
        assert(3 === Base\Segment::count('bla',$string));
        assert(3 === Base\Segment::count('[]','test/[ok]-1/test3/[four]/[five]'));
        assert(2 === Base\Segment::count('[]','test/[ok]-1/test3/[four]/five'));

        // exists
        $string = 'test [test] asd [test2] [test3][test4]';
        assert(Base\Segment::exists('[]','test2',$string));
        assert(Base\Segment::exists('[]',['test2','test3'],$string));
        assert(!Base\Segment::exists('[]',['test2','test3','z'],$string));
        assert(!Base\Segment::exists('[]','test2z',$string));

        // are
        $string = 'test [test] asd [test2] [test3][test4]';
        assert(Base\Segment::are('[]',['test','test2','test3','test4'],$string));
        assert(!Base\Segment::are('[]',['test','test2','test3','test5'],$string));
        assert(!Base\Segment::are('[]',['test','test2','test3'],$string));

        // get
        $string = 'test [test] asd [test2] [test3][test4]';
        assert(['test','test2','test3','test4'] === Base\Segment::get('[]',$string));
        assert([] === Base\Segment::get('()',$string));
        $string = 'test %test%';
        assert(count(Base\Segment::get('%',$string)) === 1);
        assert(['ok','four','five'] === Base\Segment::get('[]','test/[ok]-1/test3/[four]/[five]'));
        assert(Base\Segment::get(null,'test [name_[lang]] [ok]') === ['name_[lang','ok']);
        assert(Base\Segment::get(null,'test [name_%lang%] [ok]') === ['name_%lang%','ok']);
        assert(Base\Segment::get(null,'test [name_%lang%] [ok]',true) === ['name_en','ok']);
        $string = 'test [test] asd [test] [test3][test]';
        assert(Base\Segment::get(null,$string) === ['test','test','test3','test']);
        $string = 'test [test/james] asd [test2] [test3][test4]';
        assert(Base\Segment::get(null,$string)[0] === 'test/james');

        // set
        $string = 'test [test] asd [tést2/test3]';
        assert(Base\Segment::set(null,'tést2/test3','bla',$string) === 'test [test] asd bla');
        $string = 'test [test] asd [tést2] [test3][test4]';
        assert('test [test] asd blaé [test3][test4]' === Base\Segment::set(['[',']'],'tést2','blaé',$string));
        $string = 'test [test] asd [tést2/name] [test3][test4]';
        assert('test [test] asd blaé [test3][test4]' === Base\Segment::set(['[',']'],'tést2',['name'=>'blaé'],$string));
        assert('test [test] asd [tést2/name] [test3][test4]' === Base\Segment::set(['[',']'],'tést2',['name'=>['HWAT']],$string));
        $string = 'test [test] asd [tést2] [test3][test4]';
        assert(Base\Segment::set(null,'tést2',null,$string) === 'test [test] asd  [test3][test4]');

        // setArray
        $array = ['ok'=>'test [test] asd [tést2] [test3][test4]'];
        assert(['ok'=>'test [test] asd blaé [test3][test4]'] === Base\Segment::setArray(['[',']'],'tést2','blaé',$array));

        // sets
        $string = 'test [test] asd [test2] [test3][test4]';
        $replace = ['test'=>'oui','test3'=>'non','test2'=>'ok'];
        assert('test oui asd ok non[test4]' === Base\Segment::sets(['[',']'],$replace,$string));
        assert('test/ok1-1/test3/four2/five2' === Base\Segment::sets('[]',['ok'=>'ok1','four'=>'four2','five'=>'five2'],'test/[ok]-1/test3/[four]/[five]'));
        assert(Base\Segment::sets(null,['ok'=>2,'name_en'=>'well'],'test [ok] [name_%lang%]') === 'test 2 well');
        $string = 'test [test] asd [test] [test3][test]';
        assert(Base\Segment::sets(null,['test'=>'ok'],$string) === 'test ok asd ok [test3]ok');
        $string = 'test [test] asd [test2] [test3][test4]';
        $replace = ['test'=>null,'test3'=>'non','test2'=>null];
        assert(Base\Segment::sets(null,$replace,$string) === 'test  asd  non[test4]');
        $string = 'test [test] asd [test2/test3]';
        $replace = ['test'=>null,'test/test3'=>'non'];
        assert(Base\Segment::sets(null,$replace,$string) === 'test  asd [test2/test3]');

        // setsArray
        $array = ['test [test] asd [test2] [test3][test4]'];
        $replace = ['test'=>'oui','test3'=>'non','test2'=>'ok'];
        assert(['test oui asd ok non[test4]'] === Base\Segment::setsArray(['[',']'],$replace,$array));

        // unset
        $string = 'test [test] asd [test2] [test3][test4]';
        assert(Base\Segment::unset('[]','test',$string) === 'test  asd [test2] [test3][test4]');
        assert(Base\Segment::unset('[]','testz',$string) === $string);

        // unsets
        assert(Base\Segment::unsets('[]',['test'],$string) === 'test  asd [test2] [test3][test4]');
        assert(Base\Segment::unsets('[]',['test','test3','bla',['as']],$string) === 'test  asd [test2] [test4]');
        assert(Base\Segment::unsets('[]',[],$string) === $string);

        // prepare
        assert(Base\Segment::prepare('[test_%lang%]') === '[test_en]');
        assert(Base\Segment::prepare('[test_en]') === '[test_en]');

        // def
        assert(Base\Segment::def() === '[]');

        return true;
    }
}
?>