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

// slugPath
// class for testing Quid\Base\SlugPath
class SlugPath extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\SlugPath::is('test-ok/ok/what'));
        assert(Base\SlugPath::is('test-ok'));
        assert(!Base\SlugPath::is('test_ok/ok/what'));

        // parse

        // prepareArr

        // makeSlugs

        // other
        assert(Base\SlugPath::arr(['test','ok']) === ['test','ok']);
        assert(Base\SlugPath::arr(['ok'=>'bla','okz'=>'bla2']) === ['ok-bla','okz-bla2']);
        assert(Base\SlugPath::str(['test','ok']) === 'test/ok');
        assert(Base\SlugPath::str(['ok'=>'bla','okz'=>'bla2']) === 'ok-bla/okz-bla2');
        assert(Base\SlugPath::str(['test james ok','y','la vie y ést belle','ok'=>'what']) === 'test-james-ok/y/la-vie-est-belle/ok-what');
        assert(Base\SlugPath::str(['test james ok','y','la vie y ést belle','ok'=>'what'],['slug'=>['keepLast'=>false]]) === 'test-james-ok/la-vie-est-belle/ok-what');
        assert(Base\SlugPath::str([123,456,'what'=>'lol',10=>'meh']) === '123/456/what-lol/meh');
        assert(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['sliceLength'=>[5,10]]]) === 'james-ok/y/12345/123/belle/what');
        assert(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['keepLast'=>false,'keepNum'=>false,'sliceLength'=>[5,10]]]) === 'james/12345/belle');
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'])) === 50);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>10]])) === 50);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>15]])) === 11);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>20]])) === 16);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>30]])) === 23);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>80]])) === 41);
        assert(strlen(Base\SlugPath::str(['test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'],['slug'=>['totalLength'=>250]])) === 50);

        return true;
    }
}
?>