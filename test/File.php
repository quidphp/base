<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// file
// class for testing Quid\Base\File
class File extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$mediaJpg = '[assertMedia]/jpg.jpg';
		$storagePath = Base\Finder::shortcut('[storage]');
		$storage = '[assertCurrent]';
		$common = '[assertCommon]';
		$currentFile = Base\Finder::path('[assertCommon]/class.php');
		assert(Base\Dir::reset($storage));
		$tmp = tmpfile();
		$_file_ = Base\Finder::shortcut('[assertCommon]/class.php');
		$_dir_ = dirname($_file_);
		$temp = Base\File::prefix('[assertCurrent]');
		$open = Base\Res::open($currentFile);
		$dir = Base\Res::open($_dir_);
		$sym = Base\Symlink::set($currentFile,'[assertCurrent]/sym');
		$write = '[assertCurrent]/splice.txt';
		$storage = '[assertCurrent]';
		$array = Base\File::makeUploadArray($currentFile);

		// is
		assert(Base\File::is($currentFile));
		assert(Base\File::is($temp));
		assert(Base\File::is($tmp));
		assert(!Base\File::is('[assertCurrent]'));
		assert(Base\File::is($array));

		// isEmpty
		$empty = "$storage/empty.php";
		assert(Base\File::set($empty,''));
		assert(Base\File::isEmpty($temp));
		assert(!Base\File::isEmpty($currentFile));
		assert(!Base\File::isEmpty($open));
		assert(Base\File::isEmpty($tmp));
		assert(Base\File::isEmpty($empty));

		// isNotEmpty
		assert(!Base\File::isNotEmpty($temp));
		assert(Base\File::isNotEmpty($currentFile));
		assert(Base\File::isNotEmpty($open));
		assert(!Base\File::isNotEmpty($tmp));
		assert(Base\File::isNotEmpty($array));

		// isUploaded
		assert(!Base\File::isUploaded($temp));
		assert(!Base\File::isUploaded($currentFile));
		assert(!Base\File::isUploaded($tmp));
		assert(!Base\File::isUploaded($array));

		// isUploadArray
		assert(Base\File::isUploadArray(['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>0],['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>0]));
		assert(!Base\File::isUploadArray(['namez'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>0]));
		assert(!Base\File::isUploadArray(['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>0],['namze'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>0]));
		assert(Base\File::isUploadArray($array));

		// isUploadEmptyNotEmpty

		// isUploadEmpty
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>4,'size'=>0];
		assert(Base\File::isUploadEmpty($file));

		// isUploadNotEmpty
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>4,'size'=>0];
		assert(!Base\File::isUploadNotEmpty($file));
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>2,'size'=>2];
		assert(Base\File::isUploadNotEmpty($file));

		// isUploadTooBig
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>2,'size'=>2];
		assert(Base\File::isUploadTooBig($file));
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>2];
		assert(Base\File::isUploadTooBig($file));
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>3,'size'=>2];
		assert(!Base\File::isUploadTooBig($file));

		// isLoaded
		assert(Base\File::isLoaded(__FILE__));
		assert(!Base\File::isLoaded($tmp));
		assert(!Base\File::isLoaded($temp));

		// isResource
		assert(Base\File::isResource($open));
		assert(!Base\File::isResource($currentFile));

		// isMimeGroup
		assert(Base\File::isMimeGroup('php',$currentFile));
		assert(!Base\File::isMimeGroup('text',$tmp));
		assert(Base\File::isMimeGroup('php',$array));

		// isMimeFamily
		assert(Base\File::isMimeFamily('text',$currentFile));

		// isMaxSize
		assert(Base\File::isMaxSize(40000000,$currentFile));
		assert(!Base\File::isMaxSize(5,$currentFile));
		assert(Base\File::isMaxSize(40000000,$array));
		assert(Base\File::size($currentFile) === Base\File::size($array));

		// isCount
		assert(Base\File::isCount(2,[1,2]));
		assert(!Base\File::isCount(2,[1]));

		// isMinCount
		assert(!Base\File::isMinCount(3,[1,2]));

		// isMaxCount
		assert(Base\File::isMaxCount(2,[1,2]));

		// path
		assert(Base\File::path($currentFile) === $currentFile);
		assert(is_string(Base\File::path($temp)));

		// resource
		$res = Base\File::resource($temp);
		assert(is_resource($res));
		assert(is_resource(Base\File::resource($res)));
		assert(is_resource(Base\File::resource($array)));

		// resources
		assert(count(Base\File::resources($currentFile,$temp)) === 2);
		assert(is_resource(Base\File::resources($currentFile,$temp)[0]));

		// res

		// option
		assert(Base\File::option(['test'=>'deux']) === ['test'=>'deux','useIncludePath'=>true]);

		// getLoadPath
		assert(Base\File::getLoadPath($currentFile) === $currentFile);

		// makeLoadPath
		assert(file_exists(Base\File::makeLoadPath($open)));
		assert(file_exists(Base\File::makeLoadPath($currentFile)));
		assert(Base\File::makeLoadPath($_dir_) === $_dir_.'.php');
		assert(file_exists(Base\File::makeLoadPath($common.'/load')));

		// load
		assert(Base\File::load($common.'/load') === ['test'=>42,'b'=>'a']);
		assert(Base\File::load($common.'/load.php',['james'=>2]) === ['test'=>2,'b'=>'a']);
		assert(Base\File::load($common.'/load.php',['james'=>2],true) === ['james'=>2,'a'=>2,'b'=>'c']);
		assert(Base\File::isLoaded($common.'/load.php'));

		// loadOnce
		assert(Base\File::loadOnce($common.'/load') === true);

		// loads
		assert(count(Base\File::loads($common.'/load',$common.'/load.php')) === 2);

		// loaded
		assert(count(Base\File::loaded()) >= 2);

		// safeBasename
		assert(Base\File::safeBasename($currentFile) === basename($currentFile));

		// mimeBasename
		assert(Base\File::mimeBasename($currentFile) === basename($currentFile));
		assert(Base\File::mimeBasename($currentFile,'test.jpg') === 'test.php');

		// mime
		assert(Base\File::mime($temp) === 'inode/x-empty; charset=binary');
		assert(null === Base\File::mime('bla/bla/bla'));
		assert(is_string(Base\File::mime($currentFile)));
		assert(!empty(Base\File::mime($array)));

		// mimeGroup
		assert(Base\File::mimeGroup($currentFile) === 'php');
		assert(Base\File::mimeGroup($array) === 'php');
		assert(Base\File::mimeGroup($array) === 'php');

		// mimeFamilies
		assert(Base\File::mimeFamilies($currentFile) === ['text']);

		// mimeFamily
		assert(Base\File::mimeFamily($currentFile) === 'text');
		assert(Base\File::mimeFamily($array) === 'text');

		// mimeExtension
		assert(Base\File::mimeExtension($currentFile) === 'php');

		// stat
		assert(count(Base\File::stat($open,true)) === 13);
		assert(count(Base\File::stat($currentFile,true)) === count(Base\File::stat($open,true)));
		assert(Base\File::stat($_dir_) === null);
		assert(!empty(Base\File::stat($array)));

		// info
		assert(count(Base\File::info($currentFile)) === 13);
		assert(count(Base\File::info($open)) === 18);
		assert(Base\File::info($_dir_) === null);
		assert(!empty(Base\File::info($array)));

		// prefixFilename
		assert(strlen(Base\File::prefixFilename('bla')) === 30);

		// prefixBasename
		assert(strlen(Base\File::prefixBasename()) === 30);
		assert(strlen(Base\File::prefixBasename('bla')) === 34);
		assert(strlen(Base\File::prefixBasename('bla','jpgz')) === 35);
		assert(strlen(Base\File::prefixBasename('bla','txt',['dateFormat'=>'Ymd+His','separator'=>'-','random'=>10])) === 34);

		// prefix
		$prefix = Base\File::prefix('[assertCurrent]','QUID',null,['dateformat'=>'Ymd+His','separator'=>'-','random'=>10]);
		assert(is_string($prefix));
		assert(Base\Path::extension($prefix) === 'txt');
		assert(is_string(Base\File::prefix('[assertCurrent]')));
		assert(Base\Path::str(dirname(Base\File::prefix())) === Base\Path::str(Base\Dir::temp()));

		// prefixResource
		assert(count(Base\Res::info(Base\File::prefixResource('[assertCurrent]','QUID','jpg',['dateformat'=>'Ymd+His','separator'=>'-','random'=>10]))) === 18);
		assert(Base\Res::isFile(Base\File::prefixResource('[assertCurrent]')));
		assert(Base\Res::isFile(Base\File::prefixResource()));

		// open
		$exists = Base\File::open($currentFile);
		assert(Base\Res::meta($exists)['uri'] === $currentFile);
		assert(Base\File::open('[assertCurrent]') === null);
		$new = Base\File::create('[assertCurrent]/new.php');
		assert(Base\Res::meta($new)['mode'] === 'c+');
		assert(Base\Res::param($new) === ['options'=>[]]);
		assert(Base\Res::option($new) === []);
		assert(Base\File::info(Base\File::open('[assertCurrent]/sym'))['path'] === $currentFile);
		assert(Base\File::open('[assertCurrent]/bla.txt') === null);
		assert(is_resource(Base\File::open($array)));

		// binary
		assert(Base\File::binary('[assertCurrent]/bla.txt') === null);
		assert(Base\Res::isBinary(Base\File::binary($currentFile)));

		// create
		assert(!empty(Base\File::create('[assertCurrent]/bla.txt')));

		// line
		$res = Base\File::open($currentFile);
		assert(!empty(Base\File::line($res)));
		assert(Base\File::line($res) !== Base\File::line($res));

		// get
		assert(strlen(Base\File::get($common.'/load.php')) === 123);
		assert(strlen(Base\File::get($common.'/load.php',10,20)) === 20);
		assert(!empty(Base\File::get($array)));

		// read
		assert(Base\File::read(100,500,$currentFile) === Base\File::read(100,500,$open));
		assert(Base\File::read(0,true,$currentFile) === Base\File::read(0,true,$open));
		assert(Base\File::read(100,true,$currentFile) === Base\File::read(100,true,$open));
		assert(Base\File::read(0,true,'http://google.com') === null);
		assert(Base\File::read(0,true,$dir) === null);
		assert(Base\File::read(0,true,$_dir_) === null);
		assert(!empty(Base\File::read(0,true,$common.'/load.php')));
		assert(!empty(Base\File::read(0,true,$mediaJpg)));
		assert(Base\Res::seekRewind($open));
		assert(Base\File::read(null,null,$currentFile) === Base\File::read(null,null,$open));
		assert(Base\File::read(0,10,$open) === Base\File::get($currentFile,0,10));

		// getLines
		assert(count(Base\File::getLines($res)) > 100);
		assert(count(Base\File::getLines($currentFile)) > 100);
		assert(count(Base\File::getLines($currentFile,true,true,['skipEmpty'=>true])) < count(Base\File::getLines($currentFile)));
		assert(Base\File::set('[assertCurrent]/slices.php',Base\File::getLines($currentFile)));
		assert(Base\File::get($currentFile) === Base\File::get('[assertCurrent]/slices.php'));
		assert(!empty(Base\File::getLines($array)));

		// lines
		assert(count(Base\File::lines(3,10,$currentFile)) === 10);
		assert(count(Base\File::lines(-1,2,$currentFile)) === 1);
		assert(Base\File::lines(3,10,$currentFile) === Base\File::lines(3,10,$res));

		// lineCount
		assert(Base\File::lineCount('[assertCurrent]/lineCount.txt') === null);
		assert(Base\File::set('[assertCurrent]/lineCount.txt',''));
		assert(Base\File::lineCount('[assertCurrent]/lineCount.txt') === 0);
		assert(Base\File::set('[assertCurrent]/lineCount.txt','a'));
		assert(Base\File::lineCount('[assertCurrent]/lineCount.txt') === 1);
		assert(Base\File::set('[assertCurrent]/lineCount.txt',"\n"));
		assert(Base\File::lineCount('[assertCurrent]/lineCount.txt') === 1);
		assert(Base\File::lineCount($currentFile) > 100);
		assert(Base\File::lineCount($temp) === 0);
		assert(Base\File::lineCount($open) === Base\File::lineCount($currentFile));
		$res = Base\File::open($currentFile);
		assert(Base\File::lineCount($res) > 100);

		// subCount
		assert(Base\File::subCount('assert(',$currentFile) > 20);

		// lineFirst
		assert(!empty(Base\File::lineFirst($currentFile)));
		assert(Base\File::lineFirst($currentFile) === Base\File::lineFirst($res));

		// lineLast
		assert(!empty(Base\File::lineLast($currentFile)));
		assert(Base\File::lineLast($currentFile) === Base\File::lineLast($res));

		// lineChunk
		assert(Base\Arr::is(Base\File::lineChunk(4,$currentFile)));

		// lineChunkWalk
		assert(Base\Arrs::is(Base\File::lineChunkWalk(function($line,$key) {
			if(strpos(trim($line),'//') === 0)
			return true;
			if(empty(trim($line)))
			return false;
		},$currentFile)));

		// set
		assert(Base\File::set('[assertCurrent]/what/ok/file.php','IBELIEVE IN LIFE') === true);
		assert(Base\File::set('[assertCurrent]/what/ok/file.php','ME TOO') === true);
		assert(Base\File::get('[assertCurrent]/what/ok/file.php') === 'ME TOO');
		assert(Base\File::set('[assertCurrent]/what/ok/file.php','OH YEAH',true) === true);
		assert(Base\File::get('[assertCurrent]/what/ok/file.php') === 'ME TOOOH YEAH');

		// setBasename
		assert(Base\File::setBasename('[assertCurrent]/what/ok','file2.php','FIRST') === Base\File::path('[assertCurrent]/what/ok/file2.php'));
		assert(Base\File::get('[assertCurrent]/what/ok/file2.php') === 'FIRST');
		assert(is_string(Base\File::setBasename('[assertCurrent]/what/ok','file2.php','FIRST')));
		assert(Base\File::get('[assertCurrent]/what/ok/file2.php') === 'FIRST');
		assert(is_string(Base\File::setBasename('[assertCurrent]/what/ok','file2.php','SECOND',true)));
		assert(Base\File::get('[assertCurrent]/what/ok/file2.php') === 'FIRSTSECOND');

		// setFilenameExtension
		assert(Base\File::setFilenameExtension('[assertCurrent]/what/ok','file3','php','FIRST') === Base\File::path('[assertCurrent]/what/ok/file3.php'));
		assert(Base\File::get('[assertCurrent]/what/ok/file3.php') === 'FIRST');
		assert(is_string(Base\File::setFilenameExtension('[assertCurrent]/what/ok','file4',null,'FIRST')));
		assert(Base\File::get('[assertCurrent]/what/ok/file4.txt') === 'FIRST');

		// setPrefix
		assert(is_string(Base\File::setPrefix('[assertCurrent]/temp','QUID','php',['ok','yes'])));

		// base64
		assert(is_string(Base\File::base64('[assertCurrent]/what/ok/file4.txt')));

		// write
		assert(Base\File::set('[assertCurrent]/testwrite.php','WHATT'));
		assert(Base\File::write('newz','[assertCurrent]/testwrite.php'));
		assert(Base\File::get('[assertCurrent]/testwrite.php') === 'newzT');

		// overwrite
		$path = $storage.'/writePreApp.php';
		assert(Base\File::set($path));
		assert(Base\File::empty($path));
		$res = Base\File::create($storage.'/writePreApp2.php');
		assert(Base\File::empty($res) === true);
		assert(Base\File::overwrite('TEST',$path) === true);
		assert(Base\File::overwrite('TEST',$res) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));
		assert(Base\File::overwrite('TEST2',$path) === true);
		assert(Base\File::overwrite('TEST2',$res) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));

		// prepend
		assert(Base\File::prepend('TEST3',$path) === true);
		assert(Base\File::prepend('TEST3',$res) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));
		assert(Base\File::prepend('TESTX',$path,['newline'=>true]) === true);
		assert(Base\File::prepend('TESTX',$res,['newline'=>true]) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));

		// append
		assert(Base\File::prepend('TEST4',$path) === true);
		assert(Base\File::prepend('TEST4',$res) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));
		assert(Base\File::prepend('TEST5',$path,['newline'=>true]) === true);
		assert(Base\File::prepend('TEST5',$res,['newline'=>true]) === true);
		assert(Base\File::read(0,true,$path) === Base\File::read(0,true,$res));

		// appendNewline
		assert(Base\File::appendNewline("\nTESTzzz",$res) === true);
		assert(strpos(Base\File::get($res),"\n\nTESTzzz") !== false);

		// concatenate
		$tempConcat = Base\File::prefix('[assertCurrent]');
		assert(Base\File::concatenate($tempConcat,null,PHP_EOL,Base\File::makeUploadArray($currentFile),$currentFile,$res));
		assert(strlen(Base\File::get($tempConcat)) > 1000);

		// concatenateString
		assert(strlen(Base\File::concatenateString(null,PHP_EOL,$currentFile,$res)) > 1000);

		// lineSplice
		$original = Base\File::get($currentFile);
		assert(Base\File::set($write));
		assert(Base\File::overwrite($original,$write));
		assert(Base\File::lineSplice(0,1,$write,'OK',true));
		assert(Base\Arr::slice(1,3,Base\File::lineSplice(1,1,$write,['WHAT','OK','JAMES'],true)) === [1=>'WHAT',2=>'OK',3=>'JAMES']);
		assert(strpos(Base\File::get($write),'OK') === 0);

		// lineSpliceFirst
		assert(Base\File::lineSpliceFirst($write,'OK2',true));
		assert(strpos(Base\File::get($write),'OK2') === 0);

		// lineSpliceLast
		assert(Base\File::lineSpliceLast($write,'BLABLABAL',true));
		assert(strpos(Base\File::get($write),'BLABLABAL') !== false);

		// lineInsert
		assert(Base\File::lineInsert(1,'INSERT',$write,true));
		assert(strpos(Base\File::get($write),'INSERT') === 4);

		// lineFilter
		assert(count(Base\File::lineFilter(function($v) {
			if(empty(trim($v)))
			return false;
			return true;
		},$currentFile,false)) < 700);

		// lineMap
		assert(Base\File::lineMap(function($v,$k) {
			if(empty(trim($v)))
			return 'VIDE!';
			return $v;
		},$currentFile,false)[4] === 'VIDE!');

		// empty
		assert(Base\File::set('[assertCurrent]/what/ok/file.php','IBELIEVE IN LIFE'));
		assert(Base\File::empty('[assertCurrent]/what/ok/file.php') === true);
		assert(Base\File::set('[assertCurrent]/what/ok/file.php','IBELIEVE IN LIFE'));
		assert(Base\File::empty('[assertCurrent]/what/ok/file.php',2) === true);
		assert(Base\File::get('[assertCurrent]/what/ok/file.php') === 'IB');

		// link
		$link = '[assertCurrent]/links';
		assert(Base\File::set($link.'/link.php','IBELIEVE IN LIFE'));
		assert(Base\File::link($link.'/hardlink.php',$link.'/link.php'));
		assert(!Base\File::link('[assertCurrent]/dirHardLink',$link));
		assert(Base\File::inode($link.'/hardlink.php') === Base\File::inode($link.'/link.php'));

		// changeExtension
		$path = '[assertCurrent]/move/move';
		assert(Base\File::set($path.'.php','IBELIEVE IN LIFE'));
		assert(Base\File::changeExtension('txt',$path.'.php'));
		assert(Base\File::get($path.'.txt') === 'IBELIEVE IN LIFE');

		// removeExtension
		assert(Base\File::removeExtension($path.'.txt'));
		assert(Base\File::get($path) === 'IBELIEVE IN LIFE');

		// moveUploaded
		assert(!Base\File::moveUploaded('[assertCurrent]/uploaded',$path));

		// makeUploadArray
		assert(count(Base\File::makeUploadArray($currentFile)) === 5);
		assert(Base\File::makeUploadArray($currentFile.'asd') === null);

		// uploadBasename
		assert(Base\File::uploadBasename(['name'=>'james.php','type'=>'image/jpeg','tmp_name'=>$currentFile,'error'=>0,'size'=>231]) === 'james.php');
		assert(Base\File::uploadBasename(['name'=>'james.php','type'=>'image/jpeg','tmp_name'=>$currentFile.'a','error'=>0,'size'=>231]) === 'james.php');

		// uploadPath
		assert(Base\File::uploadPath(['name'=>'james.php','type'=>'image/jpeg','tmp_name'=>$currentFile,'error'=>0,'size'=>231]) === $currentFile);

		// uploadSize
		assert(Base\File::uploadSize(['name'=>'james.php','type'=>'image/jpeg','tmp_name'=>$currentFile,'error'=>0,'size'=>231]) === 231);

		// uploadValidate
		$file = [];
		assert(Base\File::uploadValidate(null) === 'fileUploadInvalid');
		assert(Base\File::uploadValidate($file) === 'fileUploadInvalid');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>223];
		assert(Base\File::uploadValidate($file) === 'fileUploadSizeIni');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>2,'size'=>23];
		assert(Base\File::uploadValidate($file) === 'fileUploadSizeForm');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>3,'size'=>23];
		assert(Base\File::uploadValidate($file) === 'fileUploadPartial');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>6,'size'=>123];
		assert(Base\File::uploadValidate($file) === 'fileUploadTmpDir');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>7,'size'=>123];
		assert(Base\File::uploadValidate($file) === 'fileUploadWrite');
		$file = ['name'=>'','type'=>'','tmp_name'=>'','error'=>0,'size'=>123];
		assert(Base\File::uploadValidate($file) === 'fileUploadExists');

		// uploadValidates
		assert(Base\File::uploadValidates([['name'=>'','type'=>'','tmp_name'=>'','error'=>1,'size'=>23],['name'=>'','type'=>'','tmp_name'=>'','error'=>6,'size'=>123]]) === ['fileUploadSizeIni','fileUploadTmpDir']);
		assert(Base\File::uploadValidates([null]));

		// getUmaskFromPermission
		assert(Base\File::getUmaskFromPermission(664,false) === 2);
		assert(Base\File::getUmaskFromPermission(664,true) === 2);
		assert(Base\File::getUmaskFromPermission(100,true) === 374);
		assert(Base\File::getUmaskFromPermission(100,false) === 566);
		assert(Base\File::getUmaskFromPermission(100) === 566);

		// permissionChange
		$temp = Base\File::prefix();
		assert(Base\File::permissionChange(true,$temp));

		// setDefaultPermission

		// defaultPermission
		assert(!empty(Base\File::defaultPermission()));

		// setNotFound

		// finder
		assert(Base\File::isReadable($currentFile));
		assert(Base\File::isReadable($currentFile));
		assert(Base\File::isReadable($open));
		assert(Base\File::isReadable($tmp));
		assert(Base\File::isReadable($array));
		assert(Base\File::isWritable($temp));
		assert(Base\File::isWritable($tmp));
		assert(!Base\File::isExecutable($temp));
		assert(Base\File::isVisible($res));
		assert(Base\File::isParentExists($res));

		// cleanup
		assert(Base\Res::close($dir));
		assert(Base\Res::close($new));
		assert(!Base\Res::close($new));
		assert(Base\Res::close($exists));
		assert(Base\Res::close($res));
		assert(Base\Res::close($open));
		assert(!Base\Res::close($exists));
		Base\Dir::empty('[assertCurrent]');

		return true;
	}
}
?>