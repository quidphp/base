<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// nav
// class for testing Quid\Base\Nav
class Nav extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// isPage
		assert(Base\Nav::isPage(2,20,6));
		assert(!Base\Nav::isPage(5,20,6));

		// isPageFull
		assert(Base\Nav::isPageFull(2,20,6));
		assert(Base\Nav::isPageFull(3,20,6));
		assert(!Base\Nav::isPageFull(4,20,6));

		// isSpecificInPage
		assert(Base\Nav::isSpecificInPage('test',2,['ok',1,'test',2,'bla'],2));
		assert(Base\Nav::isSpecificInPage('bla',3,['ok',1,'test',2,'bla'],2));
		assert(!Base\Nav::isSpecificInPage('test',3,['ok',1,'test',2,'bla'],2));

		// parseLimit
		assert(Base\Nav::parseLimit(3) === ['offset'=>0,'limit'=>3,'page'=>1]);
		assert(Base\Nav::parseLimit([2,3]) === ['offset'=>2,'limit'=>3,'page'=>1]);
		assert(Base\Nav::parseLimit([3,3]) === ['offset'=>3,'limit'=>3,'page'=>2]);
		assert(Base\Nav::parseLimit('1,2') === ['offset'=>1,'limit'=>2,'page'=>1]);
		assert(Base\Nav::parseLimit('2,2')['page'] === 2);
		assert(Base\Nav::parseLimit('3,2')['page'] === 2);
		assert(Base\Nav::parseLimit('1,4')['page'] === 1);
		assert(Base\Nav::parseLimit('3,4')['page'] === 1);
		assert(Base\Nav::parseLimit([150,50])['page'] === 4);
		assert(Base\Nav::parseLimit([149,50])['page'] === 3);
		assert(Base\Nav::parseLimit([151,50])['page'] === 4);
		assert(Base\Nav::parseLimit([0=>2])) === ['offset'=>0,'limit'=>2,'page'=>1];
		assert(Base\Nav::parseLimit([2=>2]) === ['offset'=>2,'limit'=>2,'page'=>2]);
		assert(Base\Nav::parseLimit([2=>3]) === ['offset'=>3,'limit'=>3,'page'=>2]);
		assert(Base\Nav::parseLimit([24,3]) === ['offset'=>24,'limit'=>3,'page'=>9]);
		assert(Base\Nav::parseLimit([1=>20]) === ['offset'=>0,'limit'=>20,'page'=>1]);
		assert(Base\Nav::parseLimit([3=>30]) === ['offset'=>60,'limit'=>30,'page'=>3]);

		// limitPage
		assert(Base\Nav::limitPage(1,10) === [0,10]);
		assert(Base\Nav::limitPage(3,10) === [20,10]);

		// pageSlice
		assert(Base\Nav::pageSlice(1,2,[1,2,3,4,5,6]) === [1,2]);
		assert(Base\Nav::pageSlice(3,2,[1,2,3,4,5,6]) === [4=>5,5=>6]);
		assert(Base\Nav::pageSlice(4,2,[1,2,3,4,5,6]) === []);

		// slice
		assert(Base\Nav::slice(3,[1,2,3,4,5,6]) === [1,2,3]);
		assert(Base\Nav::slice([2=>2],[1,2,3,4,5,6]) === [2=>3,3=>4]);
		assert(Base\Nav::slice([2=>0],[1,2,3,4,5,6]) === []);

		// pageMax
		assert(Base\Nav::pageMax(25,10) === 3);
		assert(Base\Nav::pageMax(2,10) === 1);
		assert(Base\Nav::pageMax([0,1],10) === 1);
		assert(Base\Nav::pageMax(50,2) === 25);
		assert(Base\Nav::pageMax(0,2) === null);
		assert(Base\Nav::pageMax(2,0) === null);
		assert(Base\Nav::pageMax(25,10) === 3);
		assert(Base\Nav::pageMax(20,6) === 4);

		// pageFromIndex
		assert(Base\Nav::pageFromIndex(0,7,1) === 1);
		assert(Base\Nav::pageFromIndex(1,7,2) === 1);
		assert(Base\Nav::pageFromIndex(2,7,2) === 2);
		assert(Base\Nav::pageFromIndex(6,7,1) === 7);
		assert(Base\Nav::pageFromIndex(5,7,2) === 3);
		assert(Base\Nav::pageFromIndex(7,7,1) === null);

		// pages
		assert(Base\Nav::pages(20,6) === [1,2,3,4]);
		assert(Base\Nav::pages([1,2],1) === [1,2]);
		assert(Base\Nav::pages(20,0) === []);

		// pagesPosition
		assert(Base\Nav::pagesPosition(2,20,6) === [1=>-1,2=>0,3=>1,4=>2]);
		assert(Base\Nav::pagesPosition(3,20,5) === [1=>-2,2=>-1,3=>0,4=>1]);
		assert(Base\Nav::pagesPosition(10,20,5) === null);

		// pagesClose
		assert(Base\Nav::pagesClose(3,30,4,1) === [2,3,4]);
		assert(Base\Nav::pagesClose(6,30,4,2) === [4,5,6,7,8]);
		assert(Base\Nav::pagesClose(8,30,4,2) === [6,7,8]);

		// pageSpecificCount
		assert(Base\Nav::pageSpecificCount(2,[1,2,4,3,5,6,7],2) === 2);
		assert(Base\Nav::pageSpecificCount(2,3,2) === 1);
		assert(Base\Nav::pageSpecificCount(3,3,2) === null);

		// pageFirst
		assert(Base\Nav::pageFirst(50,2) === 1);
		assert(Base\Nav::pageFirst(0,2) === null);

		// pagePrev
		assert(Base\Nav::pagePrev(10,50,2) === 9);
		assert(Base\Nav::pagePrev(2,50,2) === 1);
		assert(Base\Nav::pagePrev(1,50,2) === null);
		assert(Base\Nav::pagePrev(0,0,2) === null);

		// pageNext
		assert(Base\Nav::pageNext(24,50,2) === 25);
		assert(Base\Nav::pageNext(25,50,2) === null);
		assert(Base\Nav::pageNext(0,0,2) === null);

		// pageLast
		assert(Base\Nav::pageLast(50,2) === 25);
		assert(Base\Nav::pageLast(0,2) === null);

		// general
		assert(Base\Nav::general(1114,50,4,3)['next'] === null);
		assert(count(Base\Nav::general(4,50,4,3)) === 9);
		assert(count(Base\Nav::general(2,[1,2,3,4,5,6,7,8,1,2,3],4,3)) === 9);

		// pagesWithSpecific
		assert(Base\Nav::pagesWithSpecific([1,2,4,3,5,6,7],2) === [1=>[1,2],2=>[4,3],3=>[5,6],4=>[7]]);

		// pageWithSpecific
		assert(Base\Nav::pageWithSpecific(2,[1,2,4,3,5,6,7],2) === [4,3]);

		// pageFirstSpecific
		assert(Base\Nav::pageFirstSpecific(2,[1,2,4,3,5,6,7],2) === 4);
		assert(Base\Nav::pageFirstSpecific(12,[1,2,4,3,5,6,7],2) === null);

		// pageLastSpecific
		assert(Base\Nav::pageLastSpecific(2,[1,2,4,3,5,6,7],2) === 3);

		// specificIndex
		assert(Base\Nav::specificIndex(2,[1,2]) === 1);

		// specificPage
		assert(Base\Nav::specificPage(3,[1,2,3,4,5,6],2) === 2);
		assert(Base\Nav::specificPage(7,[1,2,4,3,5,6,7],2) === 4);

		// specificFirst
		assert(Base\Nav::specificFirst([1,2,3]) === 1);

		// specificPrev
		assert(Base\Nav::specificPrev(2,[1,2,3,2,4]) === 1);
		assert(Base\Nav::specificPrev(1,[1,2,3,2,4]) === null);

		// specificPrevInPage
		assert(Base\Nav::specificPrevInPage(3,[1,2,4,3,5,6,7],2) === 4);
		assert(Base\Nav::specificPrevInPage(4,[1,2,4,3,5,6,7],2) === null);

		// specificNext
		assert(Base\Nav::specificNext(2,[1,2,3,2,4]) === 3);
		assert(Base\Nav::specificNext(1,[1,2,3,2,4]) === 2);
		assert(Base\Nav::specificNext(4,[1,2,3,2,4]) === null);

		// specificNextInPage
		assert(Base\Nav::specificNextInPage(3,[1,2,4,3,5,6,7],2) === null);
		assert(Base\Nav::specificNextInPage(4,[1,2,4,3,5,6,7],2) === 3);

		// specificLast
		assert(Base\Nav::specificLast([1,2,3]) === 3);

		// specific
		assert(Base\Nav::specific(1114,[1,2,3,4,5,6,7,8,4],3) === null);
		assert(count(Base\Nav::specific(4,[1,2,3,4,5,6,7,8,4],3)) === 9);

		return true;
	}
}
?>