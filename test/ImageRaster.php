<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Test\Base;
use Quid\Base;

// imageRaster
// class for testing Quid\Base\ImageRaster
class ImageRaster extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $res = Base\Res::open(Base\ImageRaster::path($mediaJpg));
        $currentFile = Base\Finder::path('[assertCommon]/class.php');
        $captcha = Base\ImageRaster::captcha('testasdas','[assertCommon]/ttf.ttf');

        // captcha
        assert(Base\Res::size($captcha) > 4000);

        // bestFit
        assert(Base\ImageRaster::bestFit(1000,600,300,300,true) === ['width'=>600,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,200,500,true) === ['width'=>240,'height'=>600]);
        assert(Base\ImageRaster::bestFit(600,1000,200,500,true) === ['width'=>400,'height'=>1000]);
        assert(Base\ImageRaster::bestFit(600,1000,200,500) === ['width'=>200,'height'=>500]);
        assert(Base\ImageRaster::bestFit(500,150,500,250) === ['width'=>300,'height'=>150]);
        assert(Base\ImageRaster::bestFit(500,150,500,134) === ['width'=>500,'height'=>134]);
        assert(Base\ImageRaster::bestFit(1000,600,4032,3024) === ['width'=>800,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,450,600) === ['width'=>450,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,500,1200) === ['width'=>250,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,2400,1200) === ['width'=>1000,'height'=>500]);
        assert(Base\ImageRaster::bestFit(1000,600,1000,1200) === ['width'=>500,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,1000,1200) === ['width'=>500,'height'=>600]);
        assert(Base\ImageRaster::bestFit(1000,600,1500,1200) === ['width'=>750,'height'=>600]);
        assert(Base\ImageRaster::bestFit(600,1000,800,500) === ['width'=>600,'height'=>375]);

        // bestFitExpand
        assert(Base\ImageRaster::bestFitExpand(600,800,200,250) === ['width'=>600,'height'=>750]);
        assert(Base\ImageRaster::bestFitExpand(600,800,700,250) === ['width'=>700,'height'=>250]);
        assert(Base\ImageRaster::bestFitExpand(600,800,600,800) === ['width'=>600,'height'=>800]);

        // file
        assert(!Base\ImageRaster::is($currentFile));
        assert(Base\ImageRaster::is($mediaJpg));
        assert(Base\ImageRaster::is($res));
        assert(!Base\ImageRaster::isResource($currentFile));
        assert(!Base\ImageRaster::isResource($mediaJpg));
        assert(Base\ImageRaster::isResource($res));
        assert(Base\ImageRaster::option() === ['useIncludePath'=>true]);

        return true;
    }
}
?>