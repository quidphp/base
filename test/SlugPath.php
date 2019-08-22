<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// slugPath
class SlugPath extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\SlugPath::is("test-ok/ok/what"));
		assert(Base\SlugPath::is("test-ok"));
		assert(!Base\SlugPath::is("test_ok/ok/what"));

		// parse

		// prepareArr

		// makeSlugs

		// other
		assert(Base\SlugPath::arr(array('test','ok')) === array('test','ok'));
		assert(Base\SlugPath::arr(array('ok'=>'bla','okz'=>'bla2')) === array('ok-bla','okz-bla2'));
		assert(Base\SlugPath::str(array('test','ok')) === 'test/ok');
		assert(Base\SlugPath::str(array('ok'=>'bla','okz'=>'bla2')) === 'ok-bla/okz-bla2');
		assert(Base\SlugPath::str(array('test james ok','y','la vie y ést belle','ok'=>'what')) === 'test-james-ok/y/la-vie-est-belle/ok-what');
		assert(Base\SlugPath::str(array('test james ok','y','la vie y ést belle','ok'=>'what'),array('slug'=>array('keepLast'=>false))) === 'test-james-ok/la-vie-est-belle/ok-what');
		assert(Base\SlugPath::str(array(123,456,'what'=>'lol',10=>'meh')) === '123/456/what-lol/meh');
		assert(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('sliceLength'=>array(5,10)))) === 'james-ok/y/12345/123/belle/what');
		assert(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('keepLast'=>false,'keepNumeric'=>false,'sliceLength'=>array(5,10)))) === 'james/12345/belle');
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'))) === 50);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>10)))) === 50);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>15)))) === 11);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>20)))) === 16);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>30)))) === 23);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>80)))) === 41);
		assert(strlen(Base\SlugPath::str(array('test james ok','y',12345,123,'la vie y ést belle','ok'=>'what'),array('slug'=>array('totalLength'=>250)))) === 50);
		
		return true;
	}
}
?>