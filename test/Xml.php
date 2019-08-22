<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// xml
class Xml extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\Xml::is("<?xml"));
		assert(!Base\Xml::is("?xml"));

		// urlset
		assert(Base\Xml::urlset('sitemap') === "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'></urlset>");

		return true;
	}
}
?>