<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// timezone
// class for testing Quid\Base\Timezone
class Timezone extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $timezone = Base\Ini::getTimezone();
        //Base\Ini::set('date.sunrise_zenith',90.583333);
        //Base\Ini::set('date.sunset_zenith',90.583333);

        // is
        assert(Base\Timezone::is($timezone));

        // get
        assert(Base\Timezone::get() === Base\Ini::getTimezone());

        // set
        assert(Base\Timezone::set('America/Los_Angeles'));
        assert(Base\Timezone::get() !== Base\Ini::getTimezone());
        assert(Base\Timezone::set($timezone));
        assert(Base\Timezone::get() === Base\Ini::getTimezone());
        assert(Base\Timezone::set('America/Los_Angeles',true));
        assert(Base\Timezone::get() === Base\Ini::getTimezone());
        assert(Base\Ini::getTimezone() === 'America/Los_Angeles');

        // reset
        assert(Base\Timezone::reset());
        assert(Base\Timezone::get() === $timezone);
        assert(Base\Ini::getTimezone() === 'America/Los_Angeles');
        assert(Base\Timezone::reset(true));
        assert(Base\Timezone::get() === 'America/Los_Angeles');
        assert(Base\Ini::getTimezone() === 'America/Los_Angeles');
        assert(Base\Timezone::set($timezone,true));
        assert(Base\Timezone::get() === $timezone);
        assert(Base\Ini::getTimezone() === $timezone);
        assert(Base\Timezone::reset(true));
        assert(Base\Timezone::get() === $timezone);
        assert(Base\Ini::getTimezone() === $timezone);

        // name
        assert(Base\Timezone::name('acdt') === 'Australia/Adelaide');

        // location
        assert(count(Base\Timezone::location($timezone)) === 4);

        // transitions
        assert(count(Base\Timezone::transitions($timezone,10000000)) > 100);

        // suninfo
        assert(Base\Timezone::suninfo($timezone,Base\Datetime::make([2017,1,23])) !== Base\Timezone::suninfo($timezone,Base\Datetime::make([2017,8,23])));
        assert(count(Base\Timezone::suninfo($timezone)) === 9);
        assert(count(Base\Timezone::suninfo(['latitude'=>40.71,'longitude'=>-74])) === 9);

        // version
        assert(is_string(Base\Timezone::version()));

        // abbreviations

        // all
        assert(count(Base\Timezone::all()) >= 400);

        return true;
    }
}
?>