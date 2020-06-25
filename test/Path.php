<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// path
// class for testing Quid\Base\Path
class Path extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $common = Base\Finder::normalize('[assertCommon]');
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');
        $_dir_ = dirname($_file_);

        // is
        assert(Base\Path::is('/test/bla/ok.jpg'));
        assert(!Base\Path::is(false));
        assert(Base\Path::is(''));
        assert(Base\Path::is("Windows\well"));
        assert(Base\Path::is("C:\\Windows\well"));
        assert(Base\Path::is('C:/Windows/ok'));
        assert(Base\Path::is('C:'));
        assert(Base\Path::is('/fr/table/user/-/-/-/-/étienne'));

        // isWindowsDrive
        assert(Base\Path::isWindowsDrive('c:'));
        assert(!Base\Path::isWindowsDrive('c'));
        assert(!Base\Path::isWindowsDrive('/c:'));

        // hasWindowsDrive
        assert(Base\Path::hasWindowsDrive('c:'));
        assert(Base\Path::hasWindowsDrive('c:\\ok/trest'));
        assert(!Base\Path::hasWindowsDrive('/c:\\ok/trest'));
        assert(!Base\Path::hasWindowsDrive('c\\ok/trest'));

        // hasExtension
        assert(false === Base\Path::hasExtension('/test'));
        assert(true === Base\Path::hasExtension('/test.php'));
        assert(Base\Path::hasExtension($common.'/class/classtest.php'));
        assert(!Base\Path::hasExtension('c:\\well\\ok'));
        assert(Base\Path::hasExtension('c:\\well\\ok.jpg'));

        // hasLang
        assert(Base\Path::hasLang('/fr/test',['index'=>0,'length'=>2,'all'=>['en','fr']]));
        assert(!Base\Path::hasLang('/fr/test',['index'=>0,'length'=>3]));
        assert(!Base\Path::hasLang('c:\\well\\ok'));
        assert(!Base\Path::hasLang('c:\\fr\\ok.jpg'));

        // isSafe
        assert(Base\Path::isSafe('/fr/table/user/-/-/-/-/-'));
        assert(!Base\Path::isSafe('/fr/table/user/-/-/-/-/étienne'));
        assert(!Base\Path::isSafe('/fr/table/user/-/-/-/-/ aaa '));
        assert(Base\Path::isSafe('/fr/table/user/-/-/-/-/%20aaa'));
        assert(!Base\Path::isSafe('/fr/table/user/-/-/-/-/%20aaa',['regex'=>'uriPath']));
        assert(Base\Path::isSafe(''));
        assert(Base\Path::isSafe('/bla.jpg'));
        assert(Base\Path::isSafe('/bla.php'));
        assert(!Base\Path::isSafe('/bla.php',['extension'=>'jpg']));
        assert(Base\Path::isSafe('/bla.php',['extension'=>'php']));
        assert(Base\Path::isSafe('/sitemap.xml'));
        assert(!Base\Path::isSafe('/.bla.php'));
        assert(!Base\Path::isSafe('sadloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsumloremipsum',['length'=>200]));
        assert(Base\Path::isSafe("Windows\well"));
        assert(Base\Path::isSafe("C:\\Windows\well"));
        assert(!Base\Path::isSafe("C:\\Windows\wellé"));
        assert(!Base\Path::isSafe("C:\\Windows\well o"));
        assert(Base\Path::isSafe('C:/Windows/ok'));
        assert(Base\Path::isSafe('C:'));

        // isArgument
        assert(!Base\Path::isArgument('C:/Windows/ok'));
        assert(Base\Path::isArgument('-version'));
        assert(Base\Path::isArgument('/-v'));
        assert(!Base\Path::isArgument('/v'));

        // isLangCode
        assert(Base\Path::isLangCode('fr'));
        assert(Base\Path::isLangCode('en'));
        assert(!Base\Path::isLangCode('frzzz'));
        assert(!Base\Path::isLangCode('c:'));

        // isParent
        assert(Base\Path::isParent('what','what/lala/test'));
        assert(!Base\Path::isParent('what/az','what/lala/test'));

        // isExtension
        assert(false === Base\Path::isExtension(['php','jpgz'],'/test.jpg'));
        assert(true === Base\Path::isExtension(['php','jpg'],'/test.jpg'));
        assert(Base\Path::isExtension('jpg',$common.'/class/classtest.jpg'));
        assert(!Base\Path::isExtension(['jpgz'],$common.'/class/classtest.jpg'));
        assert(Base\Path::isExtension(['php','JPG'],'/test.jpg'));
        assert(Base\Path::isExtension(['php','JPG'],'C:\\Windows\\test.jpg'));

        // isLang
        assert(Base\Path::isLang('fr','/fr/test',['length'=>2,'all'=>['en','fr']]));

        // isMimeGroup
        assert(Base\Path::isMimeGroup('json','/test.json'));
        assert(Base\Path::isMimeGroup('html','/test.html'));
        assert(Base\Path::isMimeGroup('xml','/test.xml'));
        assert(!Base\Path::isMimeGroup('html','/test.xml'));
        assert(Base\Path::isMimeGroup('json','C:\\Windows\\test.json'));

        // isMimeFamily
        assert(Base\Path::isMimeFamily('text','/test.json'));
        assert(Base\Path::isMimeFamily('binary','/test.jpg'));
        assert(!Base\Path::isMimeFamily('text','/test.jpg'));
        assert(Base\Path::isMimeFamily('binary','C:\\Windows\\test.jpg'));

        // isInterface
        assert(Base\Path::isInterface('/quid/main/contract/test.php'));
        assert(Base\Path::isInterface('/quid/main/Contract/test.php'));
        assert(!Base\Path::isInterface('/quid/main/test.php'));
        assert(Base\Path::isInterface('C:\\Windows\\Contract\\test.jpg'));

        // normalize
        assert(Base\Path::normalize('') === '');
        assert(Base\Path::normalize('\\') === '/');
        assert(Base\Path::normalize('/') === '/');
        assert(Base\Path::normalize('/test/bla/ok.jpg') === '/test/bla/ok.jpg');
        assert(Base\Path::normalize('c:') === 'C:');
        assert(Base\Path::normalize('C:\\Windows\well') === 'C:/Windows/well');
        assert(Base\Path::normalize('C:/Windows/ok') === 'C:/Windows/ok');
        assert('/oups.com/ok' === Base\Path::normalize('//oups.com//ok',true));
        assert('/oups.com/ok' === Base\Path::normalize('//oups.com//ok'));
        assert('bla/bla/bla/' === Base\Path::normalize('bla//bla/bla/'));
        assert('/bla/bla/bla' === Base\Path::normalize('bla//bla/bla/',true));
        assert('/bla/bla/bla' === Base\Path::normalize('/bla//bla/bla'));
        assert(Base\Path::normalize('C:\\Windows\\well') === 'C:/Windows/well');
        assert(Base\Path::normalize("file://$_file_") === $_file_);

        // prepareStr
        assert(Base\Path::prepareStr("c:\\Windows\well",Base\Path::option()) === ['C:','Windows','well']);

        // implode
        assert(Base\Path::implode(['c:','Windows','well']) === 'C:/Windows/well');

        // info
        assert(empty(Base\Path::info('.')['dirname']));
        assert(count(Base\Path::info('/test/bla/ok.jpg')) === 4);
        assert(Base\Path::info('/test/bla/ok.jpg')['extension'] === 'jpg');
        assert(Base\Path::info('') === null);
        assert(Base\Path::info('b/a')['dirname'] === '/b');
        assert(Base\Path::info('c:\\Windows\\meh/top\\well.jpg')['dirname'] === 'C:/Windows/meh/top');
        assert(Base\Path::info('/') === null);
        assert(Base\Path::info('\\') === null);

        // infoOne
        assert(Base\Path::infoOne(PATHINFO_EXTENSION,'/test/bla/ok.jpg') === 'jpg');
        assert(Base\Path::infoOne('dirname','c:\\Windows\\meh/top/ok.jp') === 'C:/Windows/meh/top');
        assert(Base\Path::infoOne(PATHINFO_DIRNAME,'/') === null);
        assert(Base\Path::infoOne('dirname','\\') === null);
        assert(Base\Path::infoOne(PATHINFO_DIRNAME,'D:') === null);
        assert(Base\Path::infoOne(PATHINFO_DIRNAME,'d:') === null);

        // infoDirname

        // getInfoConstant
        assert(Base\Path::getInfoConstant('dirname') === PATHINFO_DIRNAME);

        // getEmptyInfo
        assert(count(Base\Path::getEmptyInfo()) === 4);
        assert(current(Base\Path::getEmptyInfo()) === null);

        // build
        assert($_file_ === Base\Path::build(pathinfo($_file_)));
        assert($_dir_ === Base\Path::build(pathinfo($_dir_)));
        assert(Base\Path::build(['dirname'=>'/test/ok','extension'=>'jpg']) === '/test/ok/.jpg');
        assert(Base\Path::build(['dirname'=>'test/ok','extension'=>'jpg']) === '/test/ok/.jpg');
        assert(Base\Path::build(['dirname'=>'C:\\james\\ok\\\\mewh/well','filename'=>'wellz','extension'=>'jpg']) === 'C:/james/ok/mewh/well/wellz.jpg');

        // rebuild
        assert($_file_ === Base\Path::rebuild($_file_));
        assert('/test/test2/test3.zip' === Base\Path::rebuild('test/test2/test3.zip'));
        assert($_dir_ === Base\Path::rebuild($_dir_));
        assert(Base\Path::rebuild('c:\\test\\test2/test3.zip') === 'C:/test/test2/test3.zip');

        // change
        assert(Base\Path::extension(Base\Path::change(['extension'=>'jpg'],$_file_)) === 'jpg');
        assert('/class.php' === Base\Path::change(['dirname'=>null],$_file_));
        assert('/class.php' === Base\Path::change(['dirname'=>false],$_file_));
        assert('/class.php' === Base\Path::change(['dirname'=>''],$_file_));
        assert('/class.php' === Base\Path::change(['dirname'=>'/'],$_file_));
        assert('/a/class.php' === Base\Path::change(['dirname'=>'a'],$_file_));
        assert('/a/class.php' === Base\Path::change(['dirname'=>'/a'],$_file_));
        assert(Base\Path::change(['dirname'=>'d:\\ok/meh'],'c:\\test\\test2/test3.zip') === 'D:/ok/meh/test3.zip');

        // keep
        assert('/.php' === Base\Path::keep('extension',$_file_));
        assert('/class' === Base\Path::keep('filename',$_file_));
        assert('/class.php' === Base\Path::keep(['filename','extension'],$_file_));
        assert(Base\Path::keep('filename','c:\\test\\test2/test3.zip') === '/test3');
        assert(Base\Path::keep('dirname','c:\\test\\test2/test3.zip') === 'C:/test/test2');

        // remove
        assert(substr($_file_,0,-4) === Base\Path::remove('extension',$_file_));
        assert('/class' === Base\Path::remove(['dirname','extension'],$_file_));
        assert(Base\Path::remove('dirname','/what/test2/test3.zip') === '/test3.zip');
        assert(Base\Path::remove('dirname','c:\\test\\test2/test3.zip') === '/test3.zip');

        // dirname
        assert(Base\Path::dirname('/test/taa/ta.jpg') === '/test/taa');
        assert(Base\Path::dirname('ta.jpg') === null);
        assert('/bla/bla' === Base\Path::dirname('bla/bla/bla.jpg'));
        assert(Base\Path::dirname('c:\\test/test2/test3.zip') === 'C:/test/test2');
        assert(Base\Path::dirname('/') === null);
        assert(Base\Path::dirname('\\') === null);

        // changeDirname
        assert('/james/ok/bla.zip' === Base\Path::changeDirname('james/ok','bla/bla/bla.zip'));
        assert('/james/ok/bla.zip' === Base\Path::changeDirname('james/ok/','/bla.zip'));
        assert('/bla.zip' === Base\Path::changeDirname('','bla/bla.zip'));
        assert('/bla.zip' === Base\Path::changeDirname('/','bla/bla.zip'));

        // addDirname
        assert('/bla/bla/james/ok/bla.zip' === Base\Path::addDirname('james/ok','bla/bla/bla.zip'));
        assert('/bla/bla/james/ok/bla.zip' === Base\Path::addDirname('/james/ok/','/bla//bla/bla.zip'));
        assert(Base\Path::addDirname('\\james/ok\\well','D:\\ok/well\\no.zip') === 'D:/ok/well/james/ok/well/no.zip');

        // removeDirname
        assert('/bla.zip' === Base\Path::removeDirname('bla/bla/bla.zip'));
        assert('/bla' === Base\Path::removeDirname('bla/bla/bla'));

        // parent
        assert('/bla/bla' === Base\Path::parent('/bla/bla/bla'));
        assert(Base\Path::parent('D:\\ok/well\\meh/ok/bla') === 'D:/ok/well/meh/ok');
        assert(Base\Path::parent('/') === null);
        assert(Base\Path::parent('D:') === null);
        assert(Base\Path::parent('D:\\Windows') === 'D:');

        // parents
        assert(['/bla/bla','/bla','/'] === Base\Path::parents('bla/bla/bla'));
        assert(Base\Path::parents('bla') === ['/']);
        assert(count(Base\Path::parents('/ok/well\\meh/ok/no.zip')) === 5);
        assert(count(Base\Path::parents('d:\\ok/well\\meh/ok/no.zip')) === 5);

        // basename
        assert(Base\Path::basename('/test/ta.jpg') === 'ta.jpg');
        assert(Base\Path::basename('/test/') === 'test');
        assert(Base\Path::basename('test.jpg') === 'test.jpg');
        assert('bla.jpg' === Base\Path::basename('bla/bla/bla.jpg'));

        // changeBasenameExtension
        assert(Base\Path::changeBasenameExtension('jpg','james.php') === 'james.jpg');
        assert(Base\Path::changeBasenameExtension('jpg','/ok/lol/james') === 'james.jpg');
        assert(Base\Path::changeBasenameExtension('jpg','c:\\Windows\james') === 'james.jpg');

        // makeBasename
        assert(Base\Path::makeBasename('test','PDF',true) === 'test.pdf');
        assert(Base\Path::makeBasename('test','PDF') === 'test.PDF');

        // safeBasename
        assert(Base\Path::safeBasename('test.jpg','jpg') === 'test.jpg');
        assert(Base\Path::safeBasename('test.jpg','php') === 'test.php');
        assert(Base\Path::safeBasename('test.jpg','PHP') === 'test.php');
        assert(Base\Path::safeBasename('test.JPG') === 'test.jpg');
        assert(Base\Path::safeBasename(" loa-sdldsaééè  _ sdÀ ''") === 'loa_sdldsaeee_sdA');
        assert(Base\Path::safeBasename(" loa-sdldsaééè  _ sdÀ ''",'jpg') === 'loa_sdldsaeee_sdA.jpg');
        assert(Base\Path::safeBasename('james.TEST,ok.JPG') === 'james_TEST_ok.jpg');
        assert(Base\Path::safeBasename('1. La vie est laide.jpg') === '1_La_vie_est_laide.jpg');
        assert(Base\Path::safeBasename('1. La vie est laide.jpg','php') === '1_La_vie_est_laide.php');
        assert(Base\Path::safeBasename('1. La vie est laide','jpg') === '1.jpg');
        assert(Base\Path::safeBasename('1. La vie est laide','JPEG') === '1.jpg');
        assert(Base\Path::safeBasename('1. La vie est laide.jPeG') === '1_La_vie_est_laide.jpg');

        // parentBasename
        assert('zzz' === Base\Path::parentBasename('bla/bla/zzz/bla.jpg'));
        assert(Base\Path::parentBasename('c:\\Windows\bla/bla/zzz/bla.jpg') === 'zzz');

        // addBasename
        assert('/bla/bla/bla/bzzz.zip' === Base\Path::addBasename('bzzz.zip','/bla/bla/bla'));
        assert('/bla/bla/bla/bzzz.zip' === Base\Path::addBasename('/blabla/bla/bzzz.zip','bla/bla/bla'));
        assert('/bla/bla/bla' === Base\Path::addBasename('','bla/bla/bla/'));

        // changeBasename
        assert('/bla/bla/bzzz.zip' === Base\Path::changeBasename('bzzz.zip','bla/bla/bla'));
        assert('/bla/bla/bzzz.zip' === Base\Path::changeBasename('/blabla/bla/bzzz.zip','bla/bla/bla'));
        assert('/bla/bla/bzzz' === Base\Path::changeBasename('/blabla/bla/bzzz','/bla/bla/bla'));
        assert('/bla/bla' === Base\Path::changeBasename('','/bla/bla/bla'));

        // removeBasename
        assert('/bla/bla' === Base\Path::removeBasename('bla/bla/bla.zip'));
        assert('/' === Base\Path::removeBasename('bla.zip'));

        // filename
        assert(Base\Path::filename('/test/ta.jpg') === 'ta');
        assert(Base\Path::filename('/test/') === 'test');
        assert('bla' === Base\Path::filename('bla/bla/bla.jpg'));

        // addFilename
        assert('/bla/bla/bla/bzzz' === Base\Path::addFilename('bzzz.zip','/bla/bla/bla'));
        assert('/bla/bla/bla/bzzz' === Base\Path::addFilename('/blabla/bla/bzzz.zip','bla/bla/bla'));
        assert('/bla/bla/bla/ba' === Base\Path::addFilename('ba','bla/bla/bla'));
        assert('/bla/bla/bla' === Base\Path::addFilename('','bla/bla/bla'));
        assert(Base\Path::addFilename('mea.jpg','c:\\windows\\ok/meh') === 'C:/windows/ok/meh/mea');

        // changeFilename
        assert('/bla/bla/bzzz.zip' === Base\Path::changeFilename('bzzz.jpg','bla/bla/bla.zip'));
        assert('/bla/bla/bzzz' === Base\Path::changeFilename('bzzz.jpg','bla/bla/bla'));
        assert('/bla/bla' === Base\Path::changeFilename('','bla/bla/bla'));

        // removeFilename
        assert('/bla/bla/.zip' === Base\Path::removeFilename('bla/bla/bla.zip'));
        assert('/.zip' === Base\Path::removeFilename('bla.zip'));
        assert('/' === Base\Path::removeFilename('bla'));

        // extension
        assert(null === Base\Path::extension('/test'));
        assert('php' === Base\Path::extension('/test.php'));
        assert('PHP' === Base\Path::extension('/test.PHP'));
        assert('php' === Base\Path::extension('/test.PHP',true));
        assert(null === Base\Path::extension('bla/bla/bla'));
        assert('jpg' === Base\Path::extension('bla/bla/bla.jpg'));
        assert(Base\Path::extension('/bla/ok.jpg.lavie.png') === 'png');

        // extensionLowerCase
        assert(Base\Path::extensionLowerCase('/asdasa/dasdas/test.PHP') === '/asdasa/dasdas/test.php');
        assert(Base\Path::extensionLowerCase('/asdasa/dasdas/test.php') === '/asdasa/dasdas/test.php');
        assert(Base\Path::extensionLowerCase('/bla/ok.JPG.lavie.PNG') === '/bla/ok.JPG.lavie.png');

        // extensionReplace
        assert(Base\Path::extensionReplace('png') === 'png');
        assert(Base\Path::extensionReplace('jpeg') === 'jpg');
        assert(Base\Path::extensionReplace('JPEG') === 'jpg');

        // addExtension
        assert('/bla/bla/bla/.zip' === Base\Path::addExtension('bzzz.zip','/bla/bla/bla'));
        assert('/bla/bla/bla/.zip' === Base\Path::addExtension('/blabla/bla/bzzz.zip','bla/bla/bla'));
        assert('/bla/bla/bla/.ba' === Base\Path::addExtension('.ba','bla/bla/bla'));
        assert('/bla/bla/bla/.ba' === Base\Path::addExtension('ba','bla/bla/bla'));
        assert('/bla/bla/bla' === Base\Path::addExtension('','bla/bla/bla'));
        assert('/bla/bla/bla.zip/.jpg' === Base\Path::addExtension('jpg','bla/bla/bla.zip'));
        assert('/bla/.jpg' === Base\Path::addExtension('jpg','bla'));
        assert(Base\Path::addExtension('jpg','d:') === 'D:/.jpg');

        // changeExtension
        assert('/bla/bla/bla.jpg' === Base\Path::changeExtension('bzzz.jpg','bla/bla/bla.zip'));
        assert('/bla/bla/bla.jpg' === Base\Path::changeExtension('/test/2/bzzz.jpg','bla/bla/bla'));
        assert('/bla/bla/bla.jpg' === Base\Path::changeExtension('.jpg','bla/bla/bla'));
        assert('/bla/bla/bla.jpg' === Base\Path::changeExtension('jpg','bla/bla/bla'));
        assert('/bla.jpg' === Base\Path::changeExtension('jpg','bla'));
        assert('/bla/bla/bla' === Base\Path::changeExtension('','bla/bla/bla'));

        // removeExtension
        assert('/bla/bla/bla' === Base\Path::removeExtension('bla/bla/bla.zip'));
        assert('/bla' === Base\Path::removeExtension('bla.zip'));
        assert('/' === Base\Path::removeExtension('.zip'));

        // mime
        assert(null === Base\Path::mime('bla/bla/bla'));
        assert('text/x-php' === Base\Path::mime($common.'/core/site.php'));
        assert('text/x-php' === Base\Path::mime($common.'/core/sitez.php'));
        assert('text/x-php' === Base\Path::mime('php'));
        assert(Base\Path::mime('c:\\Windows\\image.png') === 'image/png');

        // mimeGroup
        assert(Base\Path::mimeGroup('php') === 'php');
        assert(Base\Path::mimeGroup($_file_) === 'php');
        assert(Base\Path::mimeGroup('c:\\Windows\\image.png') === 'imageRaster');

        // mimeFamilies
        assert(Base\Path::mimeFamilies('php') === ['text']);

        // mimeFamily
        assert(Base\Path::mimeFamily('james/ok.php') === 'text');

        // lang
        assert(Base\Path::lang('/en/testa') === 'en');
        assert(Base\Path::lang('/fr/test',['length'=>2,'all'=>['en','fr']]) === 'fr');
        assert(Base\Path::lang('/fr/test',['length'=>3,'all'=>['en','fr']]) === null);
        assert(Base\Path::lang('/frz/test',['length'=>3,'all'=>['en','fr']]) === null);
        assert(Base\Path::lang('C:\\en\\testa') === null);

        // addLang
        assert(Base\Path::addLang('fr','/test/testa') === '/fr/test/testa');
        assert(Base\Path::addLang('fr','test/testa') === '/fr/test/testa');
        assert(Base\Path::addLang('fr','en/test/testa') === '/fr/en/test/testa');
        assert(Base\Path::addLang('frz','test/testa') === null);
        assert(Base\Path::addLang('fr','C:\\en\\testa') === '/fr/C:/en/testa');

        // changeLang
        assert(Base\Path::changeLang('fr','/test/testa') === '/fr/test/testa');
        assert(Base\Path::changeLang('fr','test/testa') === '/fr/test/testa');
        assert(Base\Path::changeLang('fr','en/test/testa') === '/fr/test/testa');
        assert(Base\Path::changeLang('frz','test/testa') === null);
        assert(Base\Path::changeLang('fr','C:\\en\\testa') === '/fr/C:/en/testa');

        // removeLang
        assert(Base\Path::removeLang('/fr/test/testa') === '/test/testa');
        assert(Base\Path::removeLang('test/testa') === 'test/testa');
        assert(Base\Path::removeLang('en/test/testa') === '/test/testa');
        assert(Base\Path::removeLang('frr/test/testa') === 'frr/test/testa');
        assert(Base\Path::removeLang('C:\\en\\testa') === 'C:\en\testa');

        // match
        assert(Base\Path::match('en/test/testa') === 'test/testa');

        // redirect
        assert(Base\Path::redirect('asddaads/bla/ok') === '/en/asddaads/bla/ok');
        assert(Base\Path::redirect('/en/asddaads/bla/ok') === null);
        assert(Base\Path::redirect('/media') === '/en/media');
        assert(Base\Path::redirect('/media/') === '/en/media');
        assert(Base\Path::redirect('/sitemap.xml') === null);
        assert(Base\Path::redirect('asddaads//bla/ok') === '/en/asddaads/bla/ok');
        assert(Base\Path::redirect('asddaads/bla/ok/') === '/en/asddaads/bla/ok');
        assert(Base\Path::redirect('-v') === null);
        assert(Base\Path::redirect('/-version') === null);

        // other
        assert(Base\Path::arr('c:/Ok/What') === ['C:','Ok','What']);
        assert(Base\Path::arr("C:\Ok\What") === ['C:','Ok','What']);
        assert(Base\Path::append('C:\\ok/bla','james\\ok/test.test') === 'C:/ok/bla/james/ok/test.test');
        assert(Base\Path::str(['C:','what','ok']) === 'C:/what/ok');
        assert(Base\Path::getSegments('/test/[ok]/james','test/lala/jamesz') === ['ok'=>'lala']);
        assert(Base\Path::sameWithSegments('/test/[ok]/james/','test/lala/james'));
        assert(!Base\Path::sameWithSegments('/test/[ok]/jamesz','test/lala/james'));
        assert(!Base\Path::sameWithSegments('/test/[ok]/james','test/lala/jamesz'));
        assert('/test/test2/test3' === Base\Path::str(['test','test2','test3']));
        assert('/test/test2/test3' === Base\Path::str('/test/test2/test3/'));
        assert('/test/test2/test3/' === Base\Path::str('test/test2/test3/',['end'=>true]));
        assert('/test/test2/test3' === Base\Path::str('test/test2/test3/'));
        assert('/test/test2/test3' === Base\Path::str('test/test2//test3'));
        assert('/test/test2/test3' === Base\Path::str('//test/test2//test3/'));
        assert('/' === Base\Path::str(''));
        assert('/bla/bla/bla' === Base\Path::str('bla/bla/bla'));
        assert('/bla/bla/bla' === Base\Path::str(['bla','','bla','bla']));
        assert('/what/test/ok/test' === Base\Path::prepend('test','ok/','/test/','/what'));
        assert('/james/ok/bla/bla/bla.zip' === Base\Path::prepend('bla/bla/bla.zip','james/ok'));
        assert('/james/ok/bla/bla/bla.zip' === Base\Path::prepend('/bla//bla/bla.zip','/james/ok/'));
        assert('/test/ok/test/what' === Base\Path::append('test','ok/','/test/','/what '));
        assert('/test/ok/test/what' === Base\Path::append('/test','ok/','/test/','/what/ '));
        assert('/path/to/test/james' === Base\Path::append('path/to/','/test/james/'));

        return true;
    }
}
?>