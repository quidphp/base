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

		// printr
		assert(print_r('test',true) === Base\Debug::printr('test',false));

		// dump
		if(Base\Ini::isVarDumpOverloaded())
		{
			assert(Base\Debug::dump('asdasé',true,true) === "<pre class='xdebug-var-dump' dir='ltr'><small>string</small> <font color='#cc0000'>'asdasé'</font> <i>(length=7)</i>\n</pre>");
			assert(Base\Debug::dump('<asdasé>ok',false,false) === "<pre class='xdebug-var-dump' dir='ltr'><small>string</small> <font color='#cc0000'>'&lt;asdasé&gt;ok'</font> <i>(length=11)</i>\n</pre>");
			assert(Base\Debug::dump('<asdasé>ok',true,true) === "<pre class='xdebug-var-dump' dir='ltr'><small>string</small> <font color='#cc0000'>'&lt;asdasé&gt;ok'</font> <i>(length=11)</i>\n</pre>");
		}

		else
		{
			assert(Base\Debug::dump('james') === "<pre>string(5) \"james\"\n</pre>");
			assert(Base\Debug::dump('james',false) === "string(5) \"james\"\n");
			assert(Base\Debug::dump('<test>ok</test>') === "<pre>string(32) \"&lt;test&gt;ok&lt;/test&gt;---15\"\n</pre>");
			assert(Base\Debug::dump('<test>ok</test>',false) === "string(32) \"&lt;test&gt;ok&lt;/test&gt;---15\"\n");
			assert(Base\Debug::dump('<test>ok</test>',false,false) === "string(15) \"<test>ok</test>\"\n");
		}

		// export
		assert(strlen(Base\Debug::export([2,3,4,5])) === 892);
		assert(strlen(Base\Debug::export([2,3,4,5],true,false)) === 877);
		assert(strlen(Base\Debug::export([1,2,3],true)) === 710);
		assert(strlen(Base\Debug::export([1,2,3],false)) === 43);
		assert(Base\Debug::export('test',false,false) === "'test'");
		assert(strlen(Base\Debug::export(1.24)) === 98);

		// highlight
		assert(strlen(Base\Debug::highlight('$x = array(1,2,"test");',true,true)) === 379);
		assert(strlen(Base\Debug::highlight('$x = array(1,2,"test");',false,true)) === 84);
		assert(strlen(Base\Debug::highlight('<?php $x = array(1,2,"test"); ?>',false,false)) === 434);
		assert(strlen(Base\Debug::highlight('    <?php  $x = array(1,2,"test");   ?>   ',false,true)) === 438);

		// sourceStrip
		assert(strlen(Base\Debug::sourceStrip(__FILE__)) > 200);
		assert(Base\Debug::sourceStrip('james') === null);

		// trace

		// traceStart
		assert(Base\Debug::traceStart(__FILE__,__LINE__)[0]['file'] === __FILE__);
		assert(Base\Debug::traceStart('james.php') === []);

		// traceIndex
		assert(Base\Debug::traceIndex(0,__FILE__)['file'] === __FILE__);

		// traceSlice
		assert(count(Base\Debug::traceSlice(0,2,__FILE__,__LINE__)) === 2);

		// traceBeforeClass

		// traceLastCall
		assert(Base\Debug::traceLastCall(__FILE__,__LINE__) === Base\Debug::class.'::traceLastCall');

		// traceBeforeFile
		assert(Base\Debug::traceBeforeFile(__FILE__)['file'] !== __FILE__);

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