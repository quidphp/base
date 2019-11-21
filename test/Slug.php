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

// slug
// class for testing Quid\Base\Slug
class Slug extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\Slug::is('test-testtes1'));

        // keepAlphanumeric
        assert('test-testtes1' === Base\Slug::keepAlphanumeric('testé-test tes1'));

        // parse
        assert(Base\Slug::parse(['testé ok! la vie','JAMÉS'],Base\Slug::option(['sliceLength'=>[3,30]])) === ['teste',3=>'vie',4=>'JAMES']);

        // parseValue
        assert(Base\Slug::parseValue('test!é',true) === 'teste');
        assert(Base\Slug::parseValue('test!é',false) === 'test');

        // other
        assert(Base\Slug::str(['test_k',2]) === 'test-2');
        assert(Base\Slug::str(['test_2','ok']) === 'test-2-ok');
        assert(Base\Slug::sameWithSegments('lavie-[james]','lavie-ok'));
        assert(Base\Slug::arr('tÉst bla - ok la') === ['tEst','bla','ok','la']);
        assert('' === Base\Slug::str(''));
        assert('teste-bla-ca123-html-tag-html-asds' === Base\Slug::str('-testé_bla!ça123 <html>tag</html>-ASDS !-'));
        assert(Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','prepend'=>'MAX! -asd@#@']) === 'max-asd-teste-bla-ca123-asds-test');
        assert('max-teste-bla-ca123-asds-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','prepend'=>'MAX!']));
        assert('teste-bla-ca123-asds-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test']));
        assert('teste-ca123-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','sliceLength'=>[5,50]]));
        assert('teste-ca123-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','sliceLength'=>[5,50]]));
        assert('asds-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','totalLength'=>10]));
        assert('ca123-asds-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','totalLength'=>18]));
        assert('teste-bla-ca123-asds-test' === Base\Slug::str('-testé_bla!ça123 ASDS !----',['append'=>'test','totalLength'=>25]));

        return true;
    }
}
?>