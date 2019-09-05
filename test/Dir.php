<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// dir
// class for testing Quid\Base\Dir
class Dir extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		Base\Finder::clearStatCache();
		$_file_ = Base\Finder::shortcut('[assertCommon]/class.php');
		$_dir_ = dirname($_file_);
		$storagePath = Base\Finder::shortcut('[storage]');
		$common = Base\Finder::shortcut('[assertCommon]');
		$storage = '[assertCurrent]';
		assert(Base\Dir::reset($storage));
		$res = Base\Res::open(Base\Dir::path($storage));

		// is
		assert(Base\Dir::is($_dir_));
		assert(!Base\Dir::is($_file_));

		// isEmpty
		assert(!Base\Dir::isEmpty($_dir_));

		// isNotEmpty
		assert(Base\Dir::isNotEmpty($_dir_));

		// isDeep
		assert(Base\Dir::isDeep('[storage]'));

		// isResource
		assert(Base\Dir::isResource($res));

		// temp
		assert(is_string(Base\Dir::temp()));

		// getCurrent
		assert(Base\Dir::getCurrent() === $storagePath);

		// setCurrent
		assert(Base\Dir::setCurrent($_dir_));
		assert(strtolower(Base\Dir::getCurrent()) === strtolower($_dir_));
		assert(Base\Dir::setCurrent('[storage]'));
		assert(Base\Dir::getCurrent() === $storagePath);

		// get
		assert(count(Base\Dir::get($common)) <= 14);
		assert(!Base\Arrs::is(Base\Dir::get($common)));
		$get = Base\Dir::get($common,true,['in'=>['visible'=>true],'out'=>['type'=>'dir','extension'=>['scss','js','php','sql','ini','txt','json','md','ttf']]]);
		assert(count($get) === 6);

		// getKeepInOut

		// getMakeFormat

		// getVisible
		assert(count(Base\Dir::getVisible($common)) === 14);

		// getInvisible
		assert(is_array(Base\Dir::getInvisible($common)));

		// getIn
		assert(is_file(Base\Dir::getIn($common,['type'=>'file'],true)[0]));

		// getExtension
		assert(count(Base\Dir::getExtension('[storage]','log',true)) === 1);

		// getPhp
		assert(count(Base\Dir::getPhp('[public]',true)) >= 1);

		// getOut
		assert(count(Base\Dir::getOut($common,['type'=>['dir','link']],true)) > 5);

		// getFormat
		assert(is_int(current(Base\Dir::getFormat(dirname($_dir_),'size'))));

		// getFormatExtra
		assert(is_string(current(Base\Dir::getFormatExtra(dirname($_dir_),'size'))));
		assert(is_array(Base\Dir::getFormatExtra(dirname($_dir_),'stat')));

		// getSize
		assert(count(Base\Dir::getSize(dirname($_dir_),null,true)) > 5);
		assert(is_int(current(Base\Dir::getSize(dirname($_dir_),null,true,['formatExtra'=>false]))));

		// getLine
		assert(count(Base\Dir::getLine($common,'php')) > 5);

		// getEmptyDir
		assert(count(Base\Dir::getEmptyDir('[storage]')) >= 1);

		// getRelative
		assert(!empty(Base\Dir::getRelative($common,['in'=>['type'=>'file','visible'=>true]])));

		// getFqcn
		assert(count(Base\Dir::getFqcn($common,'Quid\James',true,'php')) > 5);

		// getFormatSymbol
		assert(Base\Dir::getFormatSymbol($common,'>',5000,'size') !== Base\Dir::getFormat($common,'size'));

		// getFormatSmaller
		assert(is_array(Base\Dir::getFormatSmaller($common,Base\Date::addMinute(-1))));

		// getFormatBigger
		assert(is_array(Base\Dir::getFormatBigger($common,Base\Date::addMinute(-1))));

		// getFormatSort

		// getFormatSortMax
		assert(count(Base\Dir::getFormatSortMax($common,3)) === 3);

		// getFormatSortSkip
		assert(count(Base\Dir::getFormatSortSkip($common,3)) > 3);

		// getTree
		assert(count(Base\Dir::getTree(dirname($common))) < 15);
		assert(count(Base\Dir::getTree(dirname($common),true,['format'=>'size'])) < 15);

		// getTemp

		// gets
		assert(count(Base\Dir::gets(['[assertCommon]','[storage]'])) === 2);

		// getsAppend
		assert(count(Base\Dir::getsAppend(['[assertCommon]','[storage]'])) > 2);

		// fromToCatchAll
		assert(count(Base\Dir::fromToCatchAll([dirname($common).'/*'=>'[public]'])) === 2);

		// getChangeBasename
		assert(Base\Dir::set($storage.'/what'));
		assert(count(Base\Dir::getChangeBasename(function($value) { return $value.'OK'; },$storage)) === 1);
		assert(Base\Dir::is($storage.'/whatOK'));

		// sortPriority
		$get = Base\Dir::get($common);
		assert(Base\Dir::sortPriority($get,['zip.zip'],$common) !== $get);
		assert(Base\Dir::sortPriority($get,'zip.zip',$common) !== $get);

		// remove
		assert(Base\Dir::remove($get,'class.php',$common) !== $get);
		assert(Base\Dir::remove($get,'classz.php',$common) === $get);

		// parent
		$current = Base\Dir::parent($common,false,['out'=>['visible'=>false],'self'=>false]);
		assert(is_array($current));

		// concatenate
		$tempConcat = Base\File::prefix('[assertCurrent]');
		assert(Base\Dir::concatenate($tempConcat,$common,'php'));
		assert(strlen(Base\File::get($tempConcat)) > 1000);

		// concatenateString
		assert(strlen(Base\Dir::concatenateString($common,'php')) > 1000);

		// load

		// loadOnce

		// loadPhp

		// loadPhpOnce

		// count
		assert(is_int(Base\Dir::count($common)));

		// line
		assert(Base\Dir::line($common,'php') < 1300);

		// size
		assert(is_int(Base\Dir::size($common)));
		assert(is_string(Base\Dir::size($common,true)));

		// subDirLine
		assert(count(Base\Dir::subDirLine($common)) === 0);

		// set
		assert(!Base\Dir::set($_dir_));
		assert(Base\Dir::set($storage.'/what'));

		// setParent
		assert(!Base\Dir::setParent('[assertCurrent]/whatt'));

		// setOrWritable
		assert(Base\Dir::setOrWritable('[assertCurrent]/what/ok'));

		// copy
		$copy = $common;
		assert(count(Base\Dir::copy($storage.'/quid',$copy)) > 2);
		assert(count(Base\Dir::copyInDirname('quid2','[assertCurrent]/quid')) > 2);

		// empty
		assert(Base\Dir::set($storage.'/what/james'));
		assert(Base\Dir::set($storage.'/what/james/ok'));
		Base\File::prefix($storage.'/what/james/ok');
		assert(count(Base\Dir::empty('[assertCurrent]/what')) === 4);

		// emptyAndUnlink
		assert(Base\Dir::emptyAndUnlink('[assertCurrent]/what'));

		// reset
		assert(Base\Dir::reset('[assertCurrent]'));

		// unlinkIfEmpty
		assert(Base\Dir::set($storage.'/whatzz/what'));
		assert(Base\Dir::set($storage.'/whatzz/ok'));
		assert(count(Base\Dir::unlinkIfEmpty('[assertCurrent]')) === 3);
		assert(Base\Dir::isEmpty($storage));
		assert(Base\Dir::set($storage.'/whatzz/what'));
		assert(Base\Dir::set('[assertCurrent]/whatzz/ok'));
		Base\Finder::clearStatCache(true);
		assert(Base\Dir::is('[assertCurrent]/whatzz/ok'));
		tempnam(Base\Dir::shortcut($storage).'/whatzz/ok','bla');
		assert(count(Base\Dir::unlinkIfEmpty($storage)) === 1);
		assert(!Base\Dir::isEmpty($storage));
		assert(Base\Dir::emptyAndUnlink('[assertCurrent]/whatzz'));
		assert(Base\Dir::isEmpty('[assertCurrent]'));

		// unlinkWhileEmpty
		assert(Base\Dir::set('[assertCurrent]/whatzz/ok'));
		assert(count(Base\Dir::unlinkWhileEmpty('[assertCurrent]/whatzz/ok')) === 2);

		// open

		// getUmaskFromPermission
		assert(Base\Dir::getUmaskFromPermission(775,false) === 2);
		assert(Base\Dir::getUmaskFromPermission(775,true) === 2);
		assert(Base\Dir::getUmaskFromPermission(100,true) === 447);
		assert(Base\Dir::getUmaskFromPermission(100,false) === 677);
		assert(Base\Dir::getUmaskFromPermission(100) === 677);

		// permissionChange

		// setDefaultPermission

		// defaultPermission
		assert(!empty(Base\Dir::defaultPermission()));

		// finder
		assert(Base\Dir::isReadable($_dir_));
		assert(!Base\Dir::isReadable($_file_));
		assert(Base\Dir::isExecutable($_dir_));
		assert(!Base\Dir::isExecutable($_file_));

		// cleanup
		Base\Dir::empty('[assertCurrent]');

		return true;
	}
}
?>