<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// imageRaster
class ImageRaster extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$mediaJpg = "[assertMedia]/jpg.jpg";
		$res = Base\Res::open(Base\ImageRaster::path($mediaJpg));
		$currentFile = Base\Finder::path("[assertCommon]/class.php");
		$captcha = Base\ImageRaster::captcha("testasdas","[assertCommon]/ttf.ttf");
		
		// captcha
		assert(Base\Res::size($captcha) > 4000);
		
		// bestFit
		assert(Base\ImageRaster::bestFit(1000,600,300,300,true) === array('width'=>600,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,200,500,true) === array('width'=>240,'height'=>600));
		assert(Base\ImageRaster::bestFit(600,1000,200,500,true) === array('width'=>400,'height'=>1000));
		assert(Base\ImageRaster::bestFit(600,1000,200,500) === array('width'=>200,'height'=>500));
		assert(Base\ImageRaster::bestFit(500,150,500,250) === array('width'=>300,'height'=>150));
		assert(Base\ImageRaster::bestFit(500,150,500,134) === array('width'=>500,'height'=>134));
		assert(Base\ImageRaster::bestFit(1000,600,4032,3024) === array('width'=>800,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,450,600) === array('width'=>450,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,500,1200) === array('width'=>250,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,2400,1200) === array('width'=>1000,'height'=>500));
		assert(Base\ImageRaster::bestFit(1000,600,1000,1200) === array('width'=>500,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,1000,1200) === array('width'=>500,'height'=>600));
		assert(Base\ImageRaster::bestFit(1000,600,1500,1200) === array('width'=>750,'height'=>600));
		assert(Base\ImageRaster::bestFit(600,1000,800,500) === array('width'=>600,'height'=>375));
		
		// bestFitExpand
		assert(Base\ImageRaster::bestFitExpand(600,800,200,250) === array('width'=>600,'height'=>750));
		assert(Base\ImageRaster::bestFitExpand(600,800,700,250) === array('width'=>700,'height'=>250));
		assert(Base\ImageRaster::bestFitExpand(600,800,600,800) === array('width'=>600,'height'=>800));
		
		// file
		assert(!Base\ImageRaster::is($currentFile));
		assert(Base\ImageRaster::is($mediaJpg));
		assert(Base\ImageRaster::is($res));
		assert(!Base\ImageRaster::isResource($currentFile));
		assert(!Base\ImageRaster::isResource($mediaJpg));
		assert(Base\ImageRaster::isResource($res));
		assert(Base\ImageRaster::option() === array('useIncludePath'=>true));
		
		return true;
	}
}
?>