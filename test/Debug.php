<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// debug
// class for testing Quid\Base\Debug
class Debug extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $isVarDumpOverload = Base\Ini::isVarDumpOverloaded();
        $isCli = Base\Server::isCli();

        // helper

        // var

        // varFlush

        // varGet

        // vars

        // varsFlush

        // varsGet

        // dead

        // deads

        // echoFlush

        // varMethod
        assert(is_string(Base\Debug::varMethod()));

        // wrap

        // printr
        assert(print_r('test',true) === Base\Debug::printr('test',false));

        // dump
        if(!$isVarDumpOverload)
        {
            assert(Base\Debug::dump('<test>ok</test>',false,false) === 'string(15) "<test>ok</test>"');

            if(!$isCli)
            {
                assert(Base\Debug::dump('james') === '<pre>string(5) "james"</pre>');
                assert(Base\Debug::dump('james',false) === 'string(5) "james"');
                assert(Base\Debug::dump('<test>ok</test>') === '<pre>string(32) "&lt;test&gt;ok&lt;/test&gt;---15"</pre>');
                assert(Base\Debug::dump('<test>ok</test>',false) === 'string(32) "&lt;test&gt;ok&lt;/test&gt;---15"');
            }
        }

        // export
        assert(strlen(Base\Debug::export([1,2,3],false)) === 43);
        assert(Base\Debug::export('test',false,false) === "'test'");

        if(!$isCli)
        {
            assert(strlen(Base\Debug::export([2,3,4,5])) === 892);
            assert(strlen(Base\Debug::export([2,3,4,5],true,false)) === 877);
            assert(strlen(Base\Debug::export([1,2,3],true)) === 710);
            assert(strlen(Base\Debug::export(1.24)) === 98);
        }

        // highlight
        assert(strlen(Base\Debug::highlight('$x = array(1,2,"test");',true,true)) === 379);
        assert(strlen(Base\Debug::highlight('$x = array(1,2,"test");',false,true)) === 84);
        assert(strlen(Base\Debug::highlight('<?php $x = array(1,2,"test"); ?>',false,false)) === 434);
        assert(strlen(Base\Debug::highlight('    <?php  $x = array(1,2,"test");   ?>   ',false,true)) === 438);

        // sourceStrip
        assert(Base\Debug::sourceStrip('james') === null);

        // trace

        // traceStart
        assert(Base\Debug::traceStart('james.php') === []);

        // traceIndex

        // traceSlice

        // traceBeforeClass

        // traceLastCall

        // traceBeforeFile

        // traceRemoveArgs

        // speed
        assert(round(23500.45) === round(Base\Debug::speed(Base\Date::microtime() - 23500.45)));
        assert(round(23500) === round(Base\Debug::speed(Base\Date::microtime() - 23500)));

        // call

        // data
        assert(is_array(Base\Debug::data()));

        return true;
    }
}
?>