<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// timezone
class Timezone extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$timezone = Base\Ini::getTimezone();

		// is
		assert(Base\Timezone::is($timezone));
		
		// get
		assert(Base\Timezone::get() === Base\Ini::getTimezone());

		// set
		assert(Base\Timezone::set("America/Los_Angeles"));
		assert(Base\Timezone::get() !== Base\Ini::getTimezone());
		assert(Base\Timezone::set($timezone));
		assert(Base\Timezone::get() === Base\Ini::getTimezone());
		assert(Base\Timezone::set("America/Los_Angeles",true));
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
		assert(Base\Timezone::name("acdt") === "Australia/Adelaide");

		// location
		assert(count(Base\Timezone::location($timezone)) === 4);

		// transitions
		assert(count(Base\Timezone::transitions($timezone,10000000)) > 100);

		// sunrise
		assert(Base\Timezone::sunrise($timezone) === Base\Timezone::suninfo($timezone)['sunrise']);
		assert(Base\Timezone::sunrise($timezone,Base\Date::make(array(2017,1,1)),'sql') === "2017-01-01 07:20:00");
		assert(Base\Timezone::sunrise("Asia/Bangkok",Base\Date::make(array(2017,1,1)),array('sql',"Asia/Bangkok")) === "2017-01-01 06:41:28");

		// sunset
		assert(Base\Timezone::sunset($timezone) === Base\Timezone::suninfo($timezone)['sunset']);
		assert(Base\Timezone::sunset($timezone) !== Base\Timezone::sunset($timezone,Base\Date::make(array(2017,8,23))));
		assert(Base\Timezone::sunset($timezone,Base\Date::make(array(2017,1,1)),'sql') === "2017-01-01 16:39:35");
		assert(Base\Timezone::sunset("Asia/Bangkok",Base\Date::make(array(2017,1,1)),array('sql',"Asia/Bangkok")) === "2017-01-01 18:01:29");

		// suninfo
		assert(Base\Timezone::suninfo($timezone,Base\Date::make(array(2017,1,23))) !== Base\Timezone::suninfo($timezone,Base\Date::make(array(2017,8,23))));
		assert(count(Base\Timezone::suninfo($timezone)) === 9);
		assert(count(Base\Timezone::suninfo(array('latitude'=>40.71,'longitude'=>-74))) === 9);
		assert(Base\Timezone::suninfo("Asia/Bangkok",Base\Date::make(array(2017,1,1)),array('sql',"Asia/Bangkok"))['sunset'] === "2017-01-01 18:01:29");

		// version
		assert(is_string(Base\Timezone::version()));

		// abbreviations 
		
		// all
		assert(count(Base\Timezone::all()) === 426);
		
		return true;
	}
}
?>