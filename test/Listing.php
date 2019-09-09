<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// listing
// class for testing Quid\Base\Listing
class Listing extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $string = "test:bla\njames:ok \nwhat:123\nJames: YEHA ";

        // isSeparatorStart
        assert(Base\Listing::isSeparatorStart("\nok"));

        // isSeparatorEnd
        assert(Base\Listing::isSeparatorEnd("ok\n"));

        // hasSeparatorDouble
        assert(Base\Listing::hasSeparatorDouble("ok\n\nok"));
        assert(!Base\Listing::hasSeparatorDouble($string));

        // getSeparator
        assert(Base\Listing::getSeparator(0) === "\n");
        assert(Base\Listing::getSeparator(1) === ':');
        assert(Base\Listing::getSeparator(1,1) === ': ');
        assert(Base\Listing::getSeparator(2) === null);

        // str
        assert(strlen(Base\Listing::str($string)) === 37);

        // parse
        assert(Base\Listing::parse(['key'=>'test '],Base\Listing::option()) === ['key'=>'test ']);

        // arr
        assert(Base\Listing::arr($string) === ['test'=>'bla','james'=>'ok','what'=>'123','James'=>'YEHA']);
        assert(Base\Listing::arr(['jameszzz'=>'ok']) === ['jameszzz'=>'ok']);

        // prepareStr
        assert(count(Base\Listing::prepareStr($string,Base\Listing::option())) === 4);

        // prepareArr
        assert(Base\Listing::prepareArr(['key'=>'test '],Base\Listing::option()) === ['key'=>'test ']);

        // list
        assert(Base\Listing::list("test:bla\njames: ok \n lol: ooo") === ['test:bla','james:ok','lol:ooo']);
        assert(count(Base\Listing::list(['test'=>'bla','james'=>'ok'])) === 2);
        assert(count(Base\Listing::list(['test'=>'bla','james'=>['ok','ok2']])) === 3);

        // uni
        assert(Base\Listing::keyValue(['test'=>'bla','james'=>'ok'],Base\Listing::option()) === ['test'=>'bla','james'=>'ok']);
        assert(Base\Listing::keyValue(['test'=>'bla','james'=>'ok'],['caseImplode'=>CASE_UPPER]) === ['TEST'=>'bla','JAMES'=>'ok']);

        // implode
        assert(strlen(Base\Listing::implode(['test'=>'bla','james'=>'ok'])) === 17);
        assert(strlen(Base\Listing::implode(Base\Listing::list("test:bla\njames: ok \n lol: ooo"))) === 25);
        assert(strlen(Base\Listing::implode(Base\Listing::list(['test'=>'bla','james'=>'ok',' lol '=>' ooo ']))) === 25);
        assert(strpos(Base\Listing::implode(['test'=>'bla','james'=>'ok','lol'=>'ooo'],['caseImplode'=>'ucfirst']),'Test') === 0);
        assert(strlen(Base\Listing::implode(['test'=>'ok'],['start'=>true,'end'=>true])) === 9);

        // stripWrap
        assert(Base\Listing::stripWrap('test',true,true) === "\ntest\n");
        assert(Base\Listing::stripWrap('test',true,false) === "\ntest");
        assert(Base\Listing::stripWrap("\ntest\n",false,false) === 'test');

        // stripStart
        assert('test' === Base\Listing::stripStart("\ntest"));

        // stripEnd
        assert("\ntest" === Base\Listing::stripEnd("\ntest"));

        // wrapStart
        assert("\ntest\n" === Base\Listing::wrapStart("\ntest\n"));

        // wrapEnd
        assert("\ntest\n" === Base\Listing::wrapEnd("\ntest"));

        // other
        assert(Base\Listing::exist('test',$string));
        assert(Base\Listing::exists(['test','what'],$string));
        assert(!Base\Listing::exists(['test','what','bla'],$string));
        assert(Base\Listing::same($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz "));
        assert(!Base\Listing::same($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
        assert(Base\Listing::sameCount($string,"tesz:bla\njames:ok \nwhat:123\nJames: YEHA "));
        assert(Base\Listing::sameKey($string,"test:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
        assert(!Base\Listing::sameKey($string,"ztest:blaz\njames:okz \nwhat:12z3\nJames: YEHAz\nbla:ok"));
        assert(count(Base\Listing::prepend($string,'jameszzz:ok')) === 5);
        assert(count(Base\Listing::prepend($string,['jameszzz'=>'ok'])) === 5);
        assert(count(Base\Listing::append($string,'jameszzz:ok')) === 5);
        assert(count(Base\Listing::append($string,['jameszzz'=>'ok'])) === 5);
        assert(count(Base\Listing::append($string,'james:meh')) === 4);
        assert(Base\Listing::count($string) === 4);
        assert(Base\Listing::index(0,$string) === 'bla');
        assert(Base\Listing::indexes([1],$string) === [1=>'ok']);
        assert(Base\Listing::get('test',$string) === 'bla');
        assert(Base\Listing::gets(['test'],$string) === ['test'=>'bla']);
        assert(count(Base\Listing::set('test2','WHAT',$string)) === 5);
        assert(count(Base\Listing::sets(['test2'=>'WHAT'],$string)) === 5);
        assert(count(Base\Listing::unset('test',$string)) === 3);
        assert(count(Base\Listing::unsets(['test'],$string)) === 3);
        assert(count(Base\Listing::slice('test','what',$string)) === 3);
        assert(!(Base\Listing::slice('TEST','WHAT',$string)));
        Base\Listing::$config['sensitive'] = false;
        assert(count(Base\Listing::slice('TEST','WHAT',$string)) === 3);
        Base\Listing::$config['sensitive'] = true;
        assert(Base\Listing::sliceIndex(0,2,$string) === ['test'=>'bla','james'=>'ok']);
        assert(Base\Listing::sliceIndex(0,1,['james'=>'ok','test'=>2]) === ['james'=>'ok']);
        assert(count(Base\Listing::splice('test','what',$string,"lalal:ok\nlala2:ok2")) === 3);
        assert(count(Base\Listing::splice('test','what',$string,['lalal'=>'ok','lala2'=>'ok2'])) === 3);
        assert(Base\Listing::splice('james',true,['james'=>'ok','test'=>2]) === ['test'=>2]);
        assert(count(Base\Listing::spliceIndex(1,2,$string,"ok:lol\nja:noway")) === 4);
        assert(count(Base\Listing::spliceFirst($string,"lalal:ok\nlala2:ok2")) === 5);
        assert(count(Base\Listing::spliceLast($string,"lalal:ok\nlala2:ok2")) === 5);
        assert(count(Base\Listing::insert('test','lala:ok',$string)) === 5);
        assert(count(Base\Listing::insertIndex(0,['lala'=>'ok','jameszz'=>true],$string)) === 6);
        assert(Base\Listing::keysStart('james',$string) === ['james'=>'ok']);
        assert(Base\Listing::keysStart('james',['james_2'=>true,'asds'=>false]) === ['james_2'=>true]);
        assert(count(Base\Listing::keysEnd('es',$string)) === 2);

        return true;
    }
}
?>