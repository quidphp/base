<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// fqcn
// class for testing Quid\Base\Fqcn
class Fqcn extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $datetime = new \Datetime('now');
        $storage = Base\Finder::normalize('[storage]');
        $current = Base\Finder::normalize('[assertCurrent]');

        // is
        assert(Base\Fqcn::is("\Quid\Base\Test\TestBla"));
        assert(Base\Fqcn::is("Quid\Base\Test\TestBla"));
        assert(Base\Fqcn::is('TestBla'));
        assert(!Base\Fqcn::is('TestBla/What'));

        // sameName
        assert(!Base\Fqcn::sameName("\Quid\Base\Test\TestBla","Quid\Base\Test\TestBlaz"));
        assert(Base\Fqcn::sameName("\Quid\Base\Test\TestBla","Quid\Base\Testz\TestBla"));
        assert(!Base\Fqcn::sameName('TestBla','TestBlaz'));
        assert(Base\Fqcn::sameName('TestBla','TestBla'));

        // sameNamespace
        assert(Base\Fqcn::sameNamespace("\Quid\Base\Test\TestBla","Quid\Base\Test\TestBlaz"));
        assert(Base\Fqcn::sameNamespace('TestBla','TestBlaz'));

        // hasNamespace
        assert(Base\Fqcn::hasNamespace("Quid\Base\Test","\Quid\Base\Test\TestBlaz"));
        assert(Base\Fqcn::hasNamespace(null,'TestBlaz'));
        assert(Base\Fqcn::hasNamespace('','TestBlaz'));
        assert(!Base\Fqcn::hasNamespace('',''));

        // inNamespace
        assert(Base\Fqcn::inNamespace("\Quid","\Quid\Base\Test\TestBlaz"));
        assert(!Base\Fqcn::inNamespace('',"\Quid\Base\Test\TestBlaz"));

        // str
        assert(Base\Fqcn::str("\Quid\Base\Test\TestBla") === 'Quid\Base\Test\TestBla');
        assert(Base\Fqcn::str(['Quid','Base\Test','Bla']) === 'Quid\Base\Test\Bla');
        assert(Base\Fqcn::str(Base\Classe::class) === Base\Classe::class);
        assert(Base\Fqcn::str(Base\Classe::class) === Base\Classe::class);
        assert(Base\Fqcn::str($datetime) === 'DateTime');
        assert(Base\Fqcn::str(['Quid','Base','Classe']) === Base\Classe::class);
        assert(Base\Fqcn::str(["Quid\Base",'Classe']) === Base\Classe::class);
        assert(Base\Fqcn::str(["Quid\Base\\ Test\TestBla",'jAmes']) === "Quid\Base\Test\TestBla\jAmes");

        // arr
        assert(Base\Fqcn::arr("\Quid\Base\Test\TestBla") === ['Quid','Base','Test','TestBla']);

        // root
        assert(Base\Fqcn::root(\Datetime::class) === null);
        assert(Base\Fqcn::root("\Quid\Base\Test\TestBla") === 'Quid');
        assert(Base\Fqcn::root(['Quid','Base','Test','TestBla']) === 'Quid');
        assert(Base\Fqcn::root(null) === null);

        // name
        assert(Base\Fqcn::name("\Quid\Base\Test\TestBla") === 'TestBla');
        assert(Base\Fqcn::name(['Quid','Root','Test','TestBla']) === 'TestBla');
        assert(Base\Fqcn::name(null) === null);

        // namespace
        assert(Base\Fqcn::namespace("\Quid\Base\Test\TestBla") === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace("Quid\Base\Test\TestBla") === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace("\\\Quid\Base\Test\TestBla") === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace(['Quid','Base','Test','TestBla']) === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace(['','Quid','Base','Test','TestBla']) === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace(['Quid','Base','Test','TestBla']) === 'Quid\Base\Test');
        assert(Base\Fqcn::namespace('class@anonymous') === null);
        assert(Base\Fqcn::namespace(null) === null);

        // stripRoot
        assert(Base\Fqcn::stripRoot("\Quid\Base\Test\TestBla") === 'Base\Test\TestBla');
        assert(Base\Fqcn::stripRoot(['Quid','Base','Test','TestBla']) === 'Base\Test\TestBla');
        assert(Base\Fqcn::stripRoot(null) === '');

        // sliceMiddle
        assert(Base\Fqcn::sliceMiddle("\Quid\Base\Test\TestBla") === 'Base\Test');
        assert(Base\Fqcn::sliceMiddle(['Quid','Base','Test','TestBla']) === 'Base\Test');
        assert(Base\Fqcn::sliceMiddle(null) === '');

        // many
        assert(count(Base\Fqcn::many('Quid+App+ James\Base+ MEH + Core\Assert+ James+Lol+ OK\Final+James')) === 72);
        assert(Base\Fqcn::many('Quid\Core+Base\James+James2+James3') === ['Quid\Core\James','Quid\Core\James2','Quid\Core\James3','Quid\Base\James','Quid\Base\James2','Quid\Base\James3']);
        assert(Base\Fqcn::many('Quid\Core\James+James2+James3') === ['Quid\Core\James','Quid\Core\James2','Quid\Core\James3']);
        assert(Base\Fqcn::many('Quid\Core\James') === ['Quid\Core\James']);

        // path
        assert(Base\Fqcn::path('Testa') === '/Testa.php');
        assert(Base\Fqcn::path("Testa\What") === '/Testa/What.php');
        assert(Base\Fqcn::path('') === null);
        assert(Base\Fqcn::path("Testa\WhatHistoryJames") === '/Testa/WhatHistoryJames.php');

        // fromPath
        assert(Base\Fqcn::fromPath('/quid/base// test/testa.php') === "quid\base\\test\\testa");
        assert(Base\Fqcn::fromPath('testa.php') === 'testa');
        assert(Base\Fqcn::fromPath('quid/base/test/testa') === "quid\base\\test\\testa");
        assert(Base\Fqcn::fromPath('') === null);
        assert(Base\Fqcn::fromPath('Quid/base/test/testaHistory') === 'Quid\base\test\testaHistory');

        // fromPathRoot
        assert(Base\Fqcn::fromPathRoot($current,$storage) === 'private\assert\current');

        // fromPathRoots
        assert(Base\Fqcn::fromPathRoots($current,['james'=>$storage]) === 'james\private\assert\current');

        // extension
        assert(Base\Fqcn::extension('bla') === 'bla.php');

        // other
        assert(Base\Fqcn::append("Quid\Base\Request",'Test',['James','landreville']) === 'Quid\Base\Request\Test\James\landreville');

        return true;
    }
}
?>