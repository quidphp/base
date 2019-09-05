<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// xml
// class for testing Quid\Base\Xml
class Xml extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\Xml::is('<?xml'));
		assert(!Base\Xml::is('?xml'));

		// urlset
		assert(Base\Xml::urlset('sitemap') === "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>");

		return true;
	}
}
?>