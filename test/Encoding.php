<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// encoding
// class for testing Quid\Base\Encoding
class Encoding extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// is
		assert(Base\Encoding::is("lala'"));
		assert(Base\Encoding::is("lalaé'"));
		assert(!Base\Encoding::is("lalaé'",'JIS'));
		$nonUtf8 = "\xF0";
		assert(!Base\Encoding::is($nonUtf8));

		// isMb
		assert(!Base\Encoding::isMb(null));
		assert(!Base\Encoding::isMb("lala'"));
		assert(Base\Encoding::isMb('lalaé'));
		assert(Base\Encoding::isMb('👦🏼👦🏼👦🏼👦🏼 zz'));

		// isMbs
		assert(!Base\Encoding::isMbs("lala'",'okok?'));
		assert(Base\Encoding::isMbs("lala'",'okok?','é'));

		// isCharsetMb
		assert(Base\Encoding::isCharsetMb('UTF-8'));
		assert(Base\Encoding::isCharsetMb('utf-8'));
		assert(!Base\Encoding::isCharsetMb('utf-16'));

		// exists
		assert(Base\Encoding::exists('UTF-8'));

		// get
		assert(Base\Encoding::get('lalaé') === 'UTF-8');
		assert(Base\Encoding::get('lala') === 'ASCII');

		// set
		assert(Base\Encoding::set('lalaé','ISO-8859-1','UTF-8') !== 'lalaé');
		assert(Base\Encoding::set('lalaé','ISO-8859-1') !== 'lalaé');

		// getInternal
		assert(Base\Encoding::getInternal() === Base\Encoding::getCharset());

		// setInternal
		assert(Base\Encoding::setInternal('UTF-16'));
		assert(Base\Encoding::getCharset() === 'UTF-16');
		assert(Base\Encoding::getInternal() === 'UTF-16');
		assert(Base\Encoding::setInternal('UTF-8'));

		// getCharset
		assert(Base\Encoding::getCharset() === 'UTF-8');

		// setCharset
		Base\Encoding::setCharset('UTF-8');

		// getMb
		assert(Base\Encoding::getMb(true));
		assert(!Base\Encoding::getMb(false));
		Base\Encoding::setMb(null);
		assert(!Base\Encoding::getMb(null,'lala'));
		assert(Base\Encoding::getMb(null,'lalaé'));
		Base\Encoding::setMb(false);

		// getMbs
		assert(Base\Encoding::getMbs(true));
		assert(!Base\Encoding::getMbs(false));
		assert(!Base\Encoding::getMbs(null,'lala','lala'));
		Base\Encoding::setMb(null);
		assert(Base\Encoding::getMbs(null,'lala','laé'));
		Base\Encoding::setMb(false);

		// setMb
		Base\Encoding::setMb(true);
		assert(Base\Encoding::getMb(null));
		Base\Encoding::setMb(false);
		assert(!Base\Encoding::getMb(null));
		Base\Encoding::setMb('alalaé');
		assert(Base\Encoding::getMb(null));
		Base\Encoding::setMb('alala');
		assert(!Base\Encoding::getMb(null));

		// toUtf8
		assert(strlen(Base\Encoding::toUtf8('testé')) === 8);

		// fromUtf8
		assert(strlen(Base\Encoding::fromUtf8('testé')) === 5);

		// info
		assert(count(Base\Encoding::info()) >= 14);

		// all
		assert(in_array(count(Base\Encoding::all()),[86,87],true));

		return true;
	}
}
?>