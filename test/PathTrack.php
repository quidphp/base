<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// pathTrack
// class for testing Quid\Base\PathTrack
class PathTrack extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$_file_ = Base\Finder::shortcut('[assertCommon]/class.php');
		$_dir_ = dirname($_file_);

		// path
		assert($_file_ === Base\PathTrack::build(pathinfo($_file_)));
		assert(dirname($_file_) === Base\PathTrack::build(pathinfo(dirname($_file_))));
		assert(Base\PathTrack::build(['dirname'=>'/test/ok','extension'=>'jpg']) === '/test/ok/.jpg');
		assert(Base\PathTrack::build(['dirname'=>'test/ok','extension'=>'jpg']) === 'test/ok/.jpg');
		assert($_file_ === Base\PathTrack::rebuild($_file_));
		assert('test/test2/test3.zip' === Base\PathTrack::rebuild('test/test2/test3.zip'));
		assert($_dir_ === Base\PathTrack::rebuild($_dir_));
		assert(Base\PathTrack::extension(Base\PathTrack::change(['extension'=>'jpg'],$_file_)) === 'jpg');
		assert('class.php' === Base\PathTrack::change(['dirname'=>null],$_file_));
		assert('class.php' === Base\PathTrack::change(['dirname'=>false],$_file_));
		assert('class.php' === Base\PathTrack::change(['dirname'=>''],$_file_));
		assert('/class.php' === Base\PathTrack::change(['dirname'=>'/'],$_file_));
		assert('a/class.php' === Base\PathTrack::change(['dirname'=>'a'],$_file_));
		assert('/a/class.php' === Base\PathTrack::change(['dirname'=>'/a'],$_file_));
		assert('.php' === Base\PathTrack::keep('extension',$_file_));
		assert('class' === Base\PathTrack::keep('filename',$_file_));
		assert('class.php' === Base\PathTrack::keep(['filename','extension'],$_file_));
		assert(substr($_file_,0,-4) === Base\PathTrack::remove('extension',$_file_));
		assert('class' === Base\PathTrack::remove(['dirname','extension'],$_file_));
		assert(Base\PathTrack::dirname('/test/taa/ta.jpg') === '/test/taa');
		assert(Base\PathTrack::dirname('test/taa/ta.jpg') === 'test/taa');
		assert(Base\PathTrack::dirname('ta.jpg') === null);
		assert('bla/bla' === Base\PathTrack::dirname('bla/bla/bla.jpg'));
		assert('james/ok/bla.zip' === Base\PathTrack::changeDirname('james/ok','bla/bla/bla.zip'));
		assert('james/ok/bla.zip' === Base\PathTrack::changeDirname('james/ok/','/bla.zip'));
		assert('bla.zip' === Base\PathTrack::changeDirname('','bla/bla.zip'));
		assert('/bla.zip' === Base\PathTrack::changeDirname('/','bla/bla.zip'));
		assert('bla/bla/james/ok/bla.zip' === Base\PathTrack::addDirname('james/ok','bla/bla/bla.zip'));
		assert('/bla/bla/james/ok/bla.zip' === Base\PathTrack::addDirname('/james/ok/','/bla//bla/bla.zip'));
		assert('bla.zip' === Base\PathTrack::removeDirname('bla/bla/bla.zip'));
		assert('bla' === Base\PathTrack::removeDirname('bla/bla/bla'));
		assert('/bla/bla' === Base\PathTrack::parent('/bla/bla/bla'));
		assert(['bla/bla','bla',''] === Base\PathTrack::parents('bla/bla/bla'));
		assert(Base\Arr::valueLast(Base\PathTrack::parents($_file_)) === '');
		assert(Base\PathTrack::parents('bla') === ['']);
		assert('/bla/bla/bla/bzzz.zip' === Base\PathTrack::addBasename('bzzz.zip','/bla/bla/bla'));
		assert('bla/bla/bla/bzzz.zip' === Base\PathTrack::addBasename('/blabla/bla/bzzz.zip','bla/bla/bla'));
		assert('bla/bla/bla/' === Base\PathTrack::addBasename('','bla/bla/bla/'));
		assert('bla/bla/bzzz.zip' === Base\PathTrack::changeBasename('bzzz.zip','bla/bla/bla'));
		assert('bla/bla/bzzz.zip' === Base\PathTrack::changeBasename('/blabla/bla/bzzz.zip','bla/bla/bla'));
		assert('/bla/bla/bzzz' === Base\PathTrack::changeBasename('/blabla/bla/bzzz','/bla/bla/bla/'));
		assert('/bla/bla' === Base\PathTrack::changeBasename('','/bla/bla/bla'));
		assert('bla/bla' === Base\PathTrack::removeBasename('bla/bla/bla.zip'));
		assert('' === Base\PathTrack::removeBasename('bla.zip'));
		assert(Base\PathTrack::filename('/test/ta.jpg') === 'ta');
		assert(Base\PathTrack::filename('/test/') === 'test');
		assert('bla' === Base\PathTrack::filename('bla/bla/bla.jpg'));
		assert('/bla/bla/bla/bzzz' === Base\PathTrack::addFilename('bzzz.zip','/bla/bla/bla'));
		assert('bla/bla/bla/bzzz' === Base\PathTrack::addFilename('/blabla/bla/bzzz.zip','bla/bla/bla'));
		assert('bla/bla/bla/ba' === Base\PathTrack::addFilename('ba','bla/bla/bla'));
		assert('bla/bla/bla' === Base\PathTrack::addFilename('','bla/bla/bla'));
		assert('bla/bla/bzzz.zip' === Base\PathTrack::changeFilename('bzzz.jpg','bla/bla/bla.zip'));
		assert('bla/bla/bzzz' === Base\PathTrack::changeFilename('bzzz.jpg','bla/bla/bla'));
		assert('bla/bla' === Base\PathTrack::changeFilename('','bla/bla/bla'));
		assert('bla/bla/.zip' === Base\PathTrack::removeFilename('bla/bla/bla.zip'));
		assert('.zip' === Base\PathTrack::removeFilename('bla.zip'));
		assert('' === Base\PathTrack::removeFilename('bla'));
		assert(null === Base\PathTrack::extension('/test'));
		assert('php' === Base\PathTrack::extension('/test.php'));
		assert(null === Base\PathTrack::extension('bla/bla/bla'));
		assert('jpg' === Base\PathTrack::extension('bla/bla/bla.jpg'));
		assert('/bla/bla/bla/.zip' === Base\PathTrack::addExtension('bzzz.zip','/bla/bla/bla'));
		assert('bla/bla/bla/.zip' === Base\PathTrack::addExtension('/blabla/bla/bzzz.zip','bla/bla/bla'));
		assert('bla/bla/bla/.ba' === Base\PathTrack::addExtension('.ba','bla/bla/bla'));
		assert('bla/bla/bla/.ba' === Base\PathTrack::addExtension('ba','bla/bla/bla'));
		assert('bla/bla/bla' === Base\PathTrack::addExtension('','bla/bla/bla'));
		assert('bla/bla/bla.zip/.jpg' === Base\PathTrack::addExtension('jpg','bla/bla/bla.zip'));
		assert('bla/.jpg' === Base\PathTrack::addExtension('jpg','bla'));
		assert('bla/bla/bla.jpg' === Base\PathTrack::changeExtension('bzzz.jpg','bla/bla/bla.zip'));
		assert('bla/bla/bla.jpg' === Base\PathTrack::changeExtension('/test/2/bzzz.jpg','bla/bla/bla'));
		assert('bla/bla/bla.jpg' === Base\PathTrack::changeExtension('.jpg','bla/bla/bla'));
		assert('bla/bla/bla.jpg' === Base\PathTrack::changeExtension('jpg','bla/bla/bla'));
		assert('bla.jpg' === Base\PathTrack::changeExtension('jpg','bla'));
		assert('bla/bla/bla' === Base\PathTrack::changeExtension('','bla/bla/bla'));
		assert('bla/bla/bla' === Base\PathTrack::removeExtension('bla/bla/bla.zip'));
		assert('bla' === Base\PathTrack::removeExtension('bla.zip'));
		assert('' === Base\PathTrack::removeExtension('.zip'));
		assert(Base\PathTrack::addLang('fr','/test/testa') === 'fr/test/testa');
		assert(Base\PathTrack::addLang('fr','test/testa') === 'fr/test/testa');
		assert(Base\PathTrack::addLang('fr','en/test/testa') === 'fr/en/test/testa');
		assert(Base\PathTrack::addLang('frz','test/testa') === null);
		assert(Base\PathTrack::changeLang('fr','/test/testa') === 'fr/test/testa');
		assert(Base\PathTrack::changeLang('fr','test/testa') === 'fr/test/testa');
		assert(Base\PathTrack::changeLang('fr','en/test/testa') === 'fr/test/testa');
		assert(Base\PathTrack::changeLang('frz','test/testa') === null);
		assert(Base\PathTrack::removeLang('/fr/test/testa') === 'test/testa');
		assert(Base\PathTrack::removeLang('test/testa') === 'test/testa');
		assert(Base\PathTrack::removeLang('en/test/testa') === 'test/testa');
		assert(Base\PathTrack::removeLang('frr/test/testa') === 'frr/test/testa');
		assert('/oups.com/ok' === Base\PathTrack::separator('//oups.com//ok'));
		assert('bla/bla/bla/' === Base\PathTrack::separator('bla//bla/bla/'));
		assert('/bla/bla/bla' === Base\PathTrack::separator('/bla//bla/bla'));
		assert('test/test2/test3' === Base\PathTrack::str(['test','test2','test3']));
		assert('/test/test2/test3/' === Base\PathTrack::str('/test/test2/test3/'));
		assert('test/test2/test3/' === Base\PathTrack::str('test/test2/test3/',Base\PathTrack::option(['end'=>true])));
		assert('test/test2/test3/' === Base\PathTrack::str('test/test2/test3/'));
		assert('test/test2/test3' === Base\PathTrack::str('test/test2//test3'));
		assert('/test/test2/test3/' === Base\PathTrack::str('//test/test2//test3/'));
		assert('' === Base\PathTrack::str(''));
		assert('bla/bla/bla' === Base\PathTrack::str('bla/bla/bla'));
		assert('bla/bla/bla' === Base\PathTrack::str(['bla','','bla','bla']));
		assert('/what/test/ok/test' === Base\PathTrack::prepend('test','ok/','/test/','/what'));
		assert('james/ok/bla/bla/bla.zip' === Base\PathTrack::prepend('bla/bla/bla.zip','james/ok'));
		assert('/james/ok/bla/bla/bla.zip' === Base\PathTrack::prepend('/bla//bla/bla.zip','/james/ok/'));
		assert('test/ok/test/what' === Base\PathTrack::append('test','ok/','/test/','/what '));
		assert('/test/ok/test/what' === Base\PathTrack::append('/test','ok/','/test/','/what/ '));
		assert('path/to/test/james/' === Base\PathTrack::append('path/to/','/test/james/'));

		return true;
	}
}
?>