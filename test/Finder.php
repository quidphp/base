<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// finder
// class for testing Quid\Base\Finder
class Finder extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$storagePath = Base\Finder::shortcut('[storage]');
		$storagePublicPath = Base\Finder::shortcut('[storagePublic]');
		$publicPath = Base\Finder::shortcut('[public]');
		$common = Base\Finder::shortcut('[assertCommon]');
		$finderPath = Base\Finder::classDir();
		$mediaCsv = '[assertMedia]/csv.csv';
		$storage = '[assertCurrent]';
		$_file_ = Base\Finder::shortcut('[assertCommon]/class.php');
		$_dir_ = dirname($_file_);
		Base\Finder::clearStatCache(true);
		assert(Base\Dir::reset($storage));
		$tp = tmpfile();
		$stream = stream_get_meta_data($tp);
		$sym = Base\Finder::shortcut($storage.'/symtype');
		$type = ucfirst($data['boot']->type());
		symlink($_file_,$sym);

		// is
		assert(Base\Finder::is($common));
		assert(Base\Finder::is($common,true));
		assert(!Base\Finder::is('doesnotexist'));
		assert(!Base\Finder::is('doesnotexist',true));

		// isReadable
		assert(Base\Finder::isReadable($common));
		assert(Base\Finder::isReadable($common,true));
		assert(!Base\Finder::isReadable($common.'/assertz'));

		// isWritable
		assert(Base\Finder::isWritable($storage));
		assert(!Base\Finder::isWritable('doesnotexist'));

		// isExecutable
		assert(!Base\Finder::isExecutable('doesnotexist'));
		assert(Base\Finder::isExecutable($common));
		assert(Base\Finder::isExecutable($common,true));

		// isPathToUri
		assert(Base\Finder::isPathToUri($publicPath.$mediaCsv));

		// isUriToPath
		assert(!Base\Finder::isUriToPath(Base\Uri::absolute('test/lala.jpg')));
		assert(Base\Finder::isUriToPath(Base\Uri::absolute($mediaCsv)));
		assert(Base\Finder::isUriToPath($mediaCsv,Base\Request::host()));

		// isUriToPathReadable
		assert(!Base\Finder::isUriToPathReadable(Base\Uri::absolute('test/lala.jpg')));
		assert(Base\Finder::isUriToPathReadable(Base\Uri::absolute($mediaCsv)));
		assert(Base\Finder::isUriToPathReadable($mediaCsv,Base\Request::host()));
		assert(Base\File::isUriToPathReadable($mediaCsv,Base\Request::host()));
		assert(!Base\Dir::isUriToPathReadable($mediaCsv,Base\Request::host()));

		// isUriToPathWritable
		assert(!Base\Finder::isUriToPathWritable(Base\Uri::absolute('test/lala.jpg')));
		assert(Base\Finder::isUriToPathWritable(Base\Uri::absolute($mediaCsv)));
		assert(Base\Finder::isUriToPathWritable($mediaCsv,Base\Request::host()));

		// isUriToPathExecutable
		assert(!Base\Finder::isUriToPathExecutable(Base\Uri::absolute('test/lala.jpg')));

		// isCreatable
		assert(!Base\Finder::isCreatable($storage));
		assert(Base\Finder::isCreatable($storage.'/bla/bla/12331223/dsadsa'));
		assert(!Base\Finder::isCreatable($finderPath));
		assert(Base\Finder::isCreatable($storage.'/dynamic/bla/bla/12331223/dsadsaz.jpg'));
		assert(!Base\Finder::isCreatable(true));

		// isReadableOrCreatable
		assert(Base\Finder::isReadableOrCreatable($storage.'/assert/bla/bla/12331223/dsadsa'));
		assert(Base\Finder::isReadableOrCreatable($storage));

		// isWritableOrCreatable
		assert(Base\Finder::isWritableOrCreatable($storage.'/assert/bla/bla/12331223/dsadsa'));
		assert(Base\Finder::isWritableOrCreatable($storage));

		// isDot
		assert(Base\Finder::isDot($common.'/.'));
		assert(!Base\Finder::isDot($common));

		// isVisible
		assert(Base\Finder::isVisible($_file_));
		assert(!Base\Finder::isVisible('blabla/.htaccess'));

		// isHost
		assert(Base\Finder::isHost(Base\Request::host()));

		// isParentExists
		assert(Base\Finder::isParentExists($storage));
		assert(Base\Finder::isParentExists($storage.'/test'));
		assert(!Base\Finder::isParentExists($storage.'/assertasdsadsas/test'));

		// isParentReadable
		assert(Base\Finder::isParentReadable($storage.'/assert'));
		assert(Base\Finder::isParentReadable($storage.'/test'));
		assert(!Base\Finder::isParentReadable($storage.'/assertasdsadsas/test'));

		// isParentWritable
		assert(Base\Finder::isParentWritable($storage.'/test'));
		assert(Base\Finder::isParentWritable($storage));

		// isParentExecutable
		assert(Base\Finder::isParentExecutable($storage.'/test'));
		assert(Base\Finder::isParentExecutable($storage));

		// isParent
		assert(Base\Finder::isParent($_dir_,$_file_));
		assert(Base\Finder::isParent(dirname($_dir_),$_file_));

		// isPermission
		assert(Base\Finder::isPermission('readable',$_file_));
		assert(Base\Finder::isPermission('executable',$storage));
		assert(Base\Finder::isPermission('writable','[assertCurrent]'));

		// isOwner
		assert(Base\Finder::isOwner($_file_,Base\Finder::owner($_file_)));

		// isGroup
		assert(Base\Finder::isGroup($_file_,Base\Finder::group($_file_)));

		// type
		assert(null === Base\Finder::type('bla/bla/bla'));
		assert('dir' === Base\Finder::type($common));
		assert('file' === Base\Finder::type($_file_));
		assert('link' === Base\Finder::type($sym));

		// inode
		assert(is_int(Base\Finder::inode($_file_)));
		assert(Base\Finder::inode($common.'/myclass.phpz') === null);

		// permission
		assert(is_int(Base\Finder::permission($_file_,false)));
		assert(is_array(Base\Finder::permission($_file_,true)));
		assert(Base\Finder::permission($_dir_) !== 755);
		assert(in_array(current(Base\Finder::permission($_dir_,true)),[755,775],true));
		assert(in_array(current(Base\Finder::permission($_file_,true)),[644,664,775,755],true));
		assert(Base\Finder::permission($common.'/myclass.phpz') === null);

		// permissionFormat
		assert(Base\Finder::permissionFormat(33261) === 755);

		// permissionOctal
		assert(Base\Finder::permissionOctal(755) === 493);

		// permissionUmask
		assert(is_int(Base\Finder::permissionUmask()));

		// permissionChange
		assert(!Base\Finder::permissionChange(777,$common.'/myclass.php'));
		assert(Base\Finder::permissionChange(654,$stream['uri']));
		assert(!Base\Finder::permissionChange(true,Base\File::prefix()));

		// owner
		assert(is_int(Base\Finder::owner($_file_)));
		assert(Base\Finder::owner($common.'/myclass.phpz') === null);
		assert(count(Base\Finder::owner($_file_,true)) === 7);

		// ownerChange

		// group
		assert(is_int(Base\Finder::group($_file_)));
		assert(Base\Finder::group($common.'/myclass.phpz') === null);
		assert(count(Base\Finder::group($_file_,true)) === 4);

		// groupChange

		// size
		assert(is_int(Base\Finder::size($_file_)));
		assert(is_string(Base\Finder::size($_file_,true)));
		assert(Base\Finder::size($common.'/myclass.phpz') === null);
		$path = Base\File::path($tp);
		assert(Base\Finder::size($path) === 0);
		assert(Base\Finder::size($path,true) === '0 Byte');

		// dateAccess
		assert(is_int(Base\Finder::dateAccess($_file_)));
		assert(is_string(Base\Finder::dateAccess($_file_,true)));
		assert(Base\Finder::dateAccess($common.'/myclass.phpz') === null);

		// dateModify
		assert(is_int(Base\Finder::dateModify($_file_)));
		assert(is_string(Base\Finder::dateModify($_file_,true)));
		assert(Base\Finder::dateModify($common.'/myclass.phpz') === null);

		// dateInodeModify
		assert(is_int(Base\Finder::dateInodeModify($_file_)));
		assert(is_string(Base\Finder::dateInodeModify($_file_,true)));
		assert(Base\Finder::dateInodeModify($common.'/myclass.phpz') === null);

		// stat
		assert(count(Base\Finder::stat($_file_)) === 26);
		assert(count(Base\Finder::stat($_dir_,true)) === 13);
		assert(is_int(Base\Finder::stat($_dir_,true)['linkAmount']));
		assert(is_string(Base\Finder::stat($_dir_,true,true)['dateAccess']));
		assert(is_int(Base\Finder::stat($_dir_,true)['dateAccess']));
		assert(Base\Finder::stat($_file_.'z') === null);
		assert(is_array(Base\Finder::stat($_file_,true,true)['owner']));

		// statValue
		assert(is_int(Base\Finder::statValue('mode',$_file_)));
		assert(null === Base\Finder::statValue('mode',$_file_,true,true));
		assert(is_array(Base\Finder::statValue('permission',$_file_,true,true)));
		assert(is_int(Base\Finder::statValue('permission',$_file_,true)));

		// statReformat
		assert(count(Base\Finder::statReformat(stat($_file_))) === 13);

		// clearStatCache
		assert(Base\Finder::clearStatCache() === null);

		// info
		$info = Base\Finder::info('[assertCurrent]');
		assert(count($info) === 11);
		$info = Base\Finder::info($storage.'/eugh');
		assert($info === null);

		// formatPath
		assert(is_string(Base\Finder::formatPath('size',$_file_)));
		assert(Base\Finder::formatPath('size','ADSdasd') === null);
		assert(Base\Finder::formatPath('info',$_file_) === Base\Finder::info($_file_));
		assert(Base\Finder::formatPath('stat',$_file_) === Base\Finder::stat($_file_,true));
		assert(Base\Finder::formatPath('line',$_file_) > 100);

		// formatValue
		assert(is_string(Base\Finder::formatValue('size',10000)));

		// touch
		assert(Base\Finder::touch($stream['uri']));

		// rename
		$file = tmpfile();
		assert(Base\Finder::rename('[assertCurrent]/tmp',Base\Res::uri($file)));

		// changeDirname
		$path = $stream['uri'];
		$dirname = dirname($path);
		$newDirname = Base\Path::str(Base\Dir::temp().'/test-quid');
		$basename = basename($path);
		$newBasename = Base\Str::random(10);
		$newPath = Base\Path::addBasename($newBasename,$newDirname);
		assert(Base\Finder::changeDirname($newDirname,$path));
		$file = tmpfile();
		assert(Base\Finder::changeDirname('[assertCurrent]',Base\Res::uri($file)));
		$fileBase = Base\Res::basename($file);
		assert(Base\Dir::set($storage.'/move'));
		$filename = Base\File::prefix('[assertCurrent]/move','wha');
		assert(file_put_contents($filename,'hoho'));
		assert(Base\Finder::changeDirname($storage.'/newMove',$filename));
		$filename2 = Base\File::prefix($storage.'/move','wha');
		assert(file_put_contents($filename2,'haha'));
		$sym = $storage.'/move/sym';
		assert(Base\Symlink::set($filename2,'[assertCurrent]/move/sym'));
		assert(Base\Finder::changeDirname('[assertCurrent]/newMove',$sym));
		assert(Base\Finder::changeDirname($storage.'/dirMove',$storage.'/move'));

		// changeBasename
		assert(Base\Finder::changeBasename($newBasename,Base\Path::addBasename($basename,$newDirname)));
		assert(Base\Finder::isWritable($newPath));
		assert(Base\Finder::changeBasename('WHAT',Base\Path::addBasename($fileBase,$storage)));
		assert(Base\Finder::unlink(Base\Path::addBasename('WHAT',$storage)));
		assert(Base\Finder::changeBasename($storage.'/dirRename',$storage.'/dirMove'));
		assert(Base\Finder::changeBasename(function($value) {
			return 'dirRename2';
		},$storage.'/dirRename'));
		assert(Base\Finder::changeBasename('sym2',$storage.'/dirRename2/move/sym'));

		// copy
		assert(Base\Dir::set($storage.'/asd'));
		$filename = tempnam(Base\Finder::path($storage).'/asd','wha');
		assert(Base\Finder::copy('[assertCurrent]/asd/copy',$filename));
		$sym = $storage.'/asd/sym';
		assert(Base\Symlink::set($_file_,$sym));
		assert(Base\Finder::copy($storage.'/asd/symCopy',$sym));
		assert(count(Base\Finder::copy($storage.'/test',$_dir_)) < 100);

		// copyInDirname
		assert(Base\Finder::copyInDirname('copyKeepDirname',$filename));
		assert(Base\Finder::copyInDirname('symKeepDirname',$sym));
		assert(count(Base\Finder::copyInDirname(function() {
			return 'test2';
		},$storage.'/test')) > 5);

		// copyWithBasename
		assert(Base\Finder::copyWithBasename('[assertCurrent]/copyKeepBasename',$filename));
		assert(Base\Finder::copyWithBasename($storage.'/copyKeepBasename',$sym));

		// unlink
		assert(!Base\Finder::unlink(Base\Path::addBasename('WHAT',$storage)));
		assert(!Base\Finder::unlink($storage.'/test'));
		assert(Base\Dir::empty($storage.'/test'));

		// unlinkOnShutdown

		// unlinks
		assert(Base\Finder::unlink('[assertCurrent]/test'));
		assert(Base\Finder::unlinks('[assertCurrent]/tmp','[assertCurrent]/newMove') === 1);

		// path
		assert(Base\Finder::path('[assertCommon]/test.php') === $common.'/test.php');
		assert(Base\Finder::path('[corez]/test.php') === '[corez]/test.php');
		assert(Base\Finder::path('[assertCommon]',true) === $common);

		// realpath
		assert(Base\Finder::realpath('media',$publicPath) === $storagePublicPath.'/media');
		assert(Base\Finder::realpath('mediaz') === null);
		assert(Base\Finder::realpath('../',$common) === dirname($common));
		assert(Base\Finder::realpath('../z') === null);

		// realpathCache
		assert(count(Base\Finder::realpathCache()) === 2);

		// getHostPaths
		assert(Base\Finder::getHostPaths(Base\Request::host()) === [$publicPath,$storagePublicPath]);

		// getHostPath
		assert(Base\Finder::getHostPath(Base\Request::host()) === $publicPath);
		assert(Base\Finder::getHostPath(Base\Request::host(),1) === $storagePublicPath);
		assert(Base\Finder::getHostPath(Base\Request::host(),2) === null);
		assert(Base\Finder::getHostPath(Base\Uri::absolute('test/lala.jpg')) === $publicPath);
		assert(Base\Finder::getHostPath('meh') === null);

		// uriToPath
		assert(Base\Finder::uriToPath(Base\Uri::absolute('test/lala.jpg')) === $publicPath.'/test/lala.jpg');
		assert(Base\Finder::uriToPath('test/lala.jpg',Base\Request::host()) === $publicPath.'/test/lala.jpg');

		// pathToUri
		assert(Base\Finder::pathToUri($publicPath.'/test/lala.jpg',true) === Base\Request::schemeHost().'/test/lala.jpg');
		assert(Base\Finder::pathToUri($publicPath.'/test/lala.jpg',false) === '/test/lala.jpg');
		assert(Base\Finder::pathToUri($publicPath.'/test/lala.jpg') === '/test/lala.jpg');
		assert(Base\Finder::pathToUri($common.'/test/lala.jpg',false) === null);

		// host
		assert(Base\Finder::host() === [Base\Request::host()=>[$publicPath,$storagePublicPath]]);
		assert(Base\Finder::host() === Base\File::host());

		// umaskGroupWritable

		// concatenateDirFileString

		// phpExtension
		assert(Base\Finder::phpExtension() === 'php');

		// shortcut
		assert(Base\Finder::isShortcut('public'));
		assert(!Base\Finder::isShortcut('publicz'));
		assert(Base\Finder::getShortcut('public') === $publicPath);

		// config
		assert(Base\Finder::getConfigCallable() instanceof \Closure);
		assert(Base\Finder::config(['test'=>2],false)['test'] === 2);
		assert(empty(Base\Finder::$config['test']));

		// cleanup
		Base\Dir::empty('[assertCurrent]');

		return true;
	}
}
?>