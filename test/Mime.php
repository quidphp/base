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

// mime
// class for testing Quid\Base\Mime
class Mime extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $file = Base\Finder::normalize('[assertCommon]/class.php');
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaRes = Base\Res::open($mediaJpg);

        // isEmpty
        assert(!Base\Mime::isEmpty('text/csv; charset=us-ascii'));
        assert(Base\Mime::isEmpty('inode/x-empty'));
        assert(Base\Mime::isEmpty('application/x-empty'));

        // isGroup
        assert(Base\Mime::isGroup('php',$file,true));
        assert(Base\Mime::isGroup('audio','audio/mpeg'));
        assert(Base\Mime::isGroup('calendar','text/calendar'));
        assert(!Base\Mime::isGroup('csv','text/x-php; charset=us-ascii'));
        assert(Base\Mime::isGroup('csv','text/csv; charset=us-ascii'));
        assert(Base\Mime::isGroup('csv','TEXT/csv; charset=us-ascii'));
        assert(Base\Mime::isGroup('css','text/css; charset=us-ascii'));
        assert(Base\Mime::isGroup('doc','application/msword'));
        assert(Base\Mime::isGroup('font','application/octet-stream'));
        assert(Base\Mime::isGroup('font','ttf'));
        assert(Base\Mime::isGroup('html','text/html'));
        assert(Base\Mime::isGroup('imageRaster','image/jpeg'));
        assert(!Base\Mime::isGroup('imageRaster','image/svg'));
        assert(Base\Mime::isGroup('imageVector','image/svg'));
        assert(!Base\Mime::isGroup('imageVector','image/jpeg'));
        assert(Base\Mime::isGroup('js','text/javascript; charset=us-ascii'));
        assert(Base\Mime::isGroup('json','text/json'));
        assert(!Base\Mime::isGroup('pdf','text/x-php; charset=us-ascii'));
        assert(Base\Mime::isGroup('pdf','application/pdf'));
        assert(Base\Mime::isGroup('php','text/x-php; charset=us-ascii'));
        assert(!Base\Mime::isGroup('php',$file));
        assert(Base\Mime::isGroup('php',$file,true));
        assert(Base\Mime::isGroup('txt','text/plain'));
        assert(Base\Mime::isGroup('video','video/mp4'));
        assert(!Base\Mime::isGroup('xml','text/json'));
        assert(Base\Mime::isGroup('xml','text/xml'));
        assert(Base\Mime::isGroup('zip','application/zip'));

        // isFamily
        assert(Base\Mime::isFamily('text','text/plain'));
        assert(Base\Mime::isFamily('text',$file,true));
        assert(!Base\Mime::isFamily('text',$file));
        assert(Base\Mime::isFamily('binary','video/mp4'));
        assert(Base\Mime::isFamily('image','image/svg'));
        assert(Base\Mime::isFamily('text','text/x-php; charset=us-ascii'));

        // isExtensionInGroup
        assert(Base\Mime::isExtensionInGroup('GIF','imageRaster'));
        assert(Base\Mime::isExtensionInGroup('jpg','imageRaster'));
        assert(!Base\Mime::isExtensionInGroup('jpg','txt'));

        // get
        assert(strpos(Base\Mime::get($file),'text/x-php') === 0);
        assert(strpos(Base\Mime::get($mediaJpg),'image/jpeg') === 0);
        assert(strpos(Base\Mime::get($mediaRes),'image/jpeg') === 0);
        assert(Base\Mime::get('/Server/what.jpg') === null);

        // getFromResource
        assert(strpos(Base\Mime::getFromResource($mediaRes),'image/jpeg') === 0);
        assert(Base\Mime::getFromResource('/Server/what.jpg') === null);

        // getGroup
        assert(Base\Mime::getGroup($file) === 'php');
        assert(Base\Mime::getGroup($file.'a') === null);
        assert(Base\Mime::getGroup($mediaRes) === 'imageRaster');
        assert(Base\Mime::getGroup('/Server/what.jpg') === 'imageRaster');
        assert(Base\Mime::getGroup('/Server/what.jpg',false) === null);

        // getFamilies
        assert(Base\Mime::getFamilies($file) === ['text']);
        assert(Base\Mime::getFamilies($mediaRes) === ['image','binary']);

        // getFamily
        assert(Base\Mime::getFamily($file) === 'text');
        assert(Base\Mime::getFamily($mediaRes) === 'image');

        // getCorrectExtension
        assert(Base\Mime::getCorrectExtension($file) === 'php');
        assert(Base\Mime::getCorrectExtension($mediaRes) === 'jpg');

        // group
        assert(Base\Mime::group('image/jpeg') === 'imageRaster');

        // families
        assert(Base\Mime::families('imageRaster') === ['image','binary']);
        assert(Base\Mime::families('imageRasterz') === []);

        // family
        assert(Base\Mime::family('imageRaster') === 'image');
        assert(Base\Mime::family('imageRasterz') === null);

        // fromPath
        assert(Base\Mime::fromPath($file) === 'text/x-php');
        assert(Base\Mime::fromPath('php') === 'text/x-php');
        assert(Base\Mime::fromPath('phpz') === null);
        assert(Base\Mime::fromPath('/test.docx') === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        assert(Base\Mime::fromPath('/test.doc') === 'application/msword');

        // fromExtension
        assert(Base\Mime::fromExtension('CSV') === 'text/csv');
        assert(Base\Mime::fromExtension('csv') === 'text/csv');
        assert(Base\Mime::fromExtension('php') === 'text/x-php');
        assert(Base\Mime::fromExtension('phpz') === null);
        assert(Base\Mime::fromExtension('jpg') === 'image/jpeg');
        assert(Base\Mime::fromExtension('jpeg') === 'image/jpeg');
        assert(Base\Mime::fromExtension('notebook') === null);

        // groupFromBasename
        assert(Base\Mime::groupFromBasename('test.TXT') === 'txt');
        assert(Base\Mime::groupFromBasename('/bla/ok/test.jpg') === 'imageRaster');

        // groupFromExtension
        assert(Base\Mime::groupFromExtension('TXT') === 'txt');
        assert(Base\Mime::groupFromExtension('txt') === 'txt');
        assert(Base\Mime::groupFromExtension('php') === 'php');
        assert(Base\Mime::groupFromExtension('jpg') === 'imageRaster');
        assert(Base\Mime::groupFromExtension('docx') === 'doc');

        // toExtension
        assert(Base\Mime::toExtension('jpg',false) === null);
        assert(Base\Mime::toExtension('jpg') === 'jpg');
        assert(Base\Mime::toExtension('JPG') === 'jpg');
        assert(Base\Mime::toExtension('text/x-php') === 'php');
        assert(Base\Mime::toExtension('text/x-php; charset=us-ascii') === 'php');
        assert(Base\Mime::toExtension('image/JPEG;sdaasdadssa') === 'jpg');
        assert(Base\Mime::toExtension(' image/JPEG;sdaasdadssa') === null);

        // extensionsFromGroup
        assert(count(Base\Mime::extensionsFromGroup('imageRaster')) === 4);
        assert(count(Base\Mime::extensionsFromGroup('imageVector')) === 1);
        assert(count(Base\Mime::extensionsFromGroup('doc')) === 4);

        // extensionFromGroup
        assert(Base\Mime::extensionFromGroup('imageRaster') === 'jpg');
        assert(Base\Mime::extensionFromGroup('imageRaster',1) === 'gif');

        // removeCharset
        assert(Base\Mime::removeCharset('text/x-php; charset=us-ascii') === 'text/x-php');
        assert(Base\Mime::removeCharset('text/x-php') === 'text/x-php');

        // register
        assert(Base\Mime::register('text/weird',['weird','weirdx'],'weirdG'));
        assert(Base\Mime::families('weirdG') === ['binary']);
        assert(Base\Mime::isGroup('weirdG','weirdx'));
        assert(Base\Mime::isGroup('weirdG','text/weird'));
        assert(Base\Mime::group('text/weird') === 'weirdG');
        assert(Base\Mime::group('weird') === 'weirdG');
        assert(Base\Mime::group('weirdG') === null);
        assert(Base\Mime::register('text/weirdo','weirdo','weirdG',['text']));
        assert(count(Base\Mime::getConfig(['mimeToExtension','text/weird'])) === 2);
        assert(Base\Mime::register('text/weird','weirdMore','weirdG'));
        assert(count(Base\Mime::getConfig(['mimeToExtension','text/weird'])) === 3);

        return true;
    }
}
?>