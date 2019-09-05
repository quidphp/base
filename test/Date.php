<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// date
// class for testing Quid\Base\Date
class Date extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$timestamp = 1512139242;

		// isNow
		assert(Base\Date::isNow(Base\Date::getTimestamp()));
		assert(!Base\Date::isNow($timestamp));

		// isValid
		assert(Base\Date::isValid(2017,12,12));
		assert(!Base\Date::isValid(2017,12,50));
		assert(Base\Date::isValid(['year'=>2017,'month'=>12,'day'=>1]));

		// isYearValid
		assert(Base\Date::isYearValid(2017));
		assert(!Base\Date::isYearValid($timestamp));

		// isYearLeap
		assert(!Base\Date::isYearLeap());
		assert(Base\Date::isYearLeap(2016));
		assert(!Base\Date::isYearLeap(2017));
		assert(Base\Date::isYearLeap(Base\Date::make([2016,2,2])));

		// isToday
		assert(Base\Date::isToday(Base\Date::getTimestamp()));
		assert(Base\Date::isToday(Base\Date::format('ymd',Base\Date::getTimestamp()),'ymd'));

		// isTomorrow
		assert(!Base\Date::isTomorrow(Base\Date::getTimestamp()));
		assert(Base\Date::isTomorrow(Base\Date::addDay(1)));

		// isYesterday
		assert(!Base\Date::isYesterday(Base\Date::getTimestamp()));
		assert(Base\Date::isYesterday(Base\Date::addDay(-1)));

		// isYear
		assert(Base\Date::isYear(Base\Date::getTimestamp()));
		assert(!Base\Date::isYear(Base\Date::addYear(1)));

		// isMonth
		assert(Base\Date::isMonth(Base\Date::getTimestamp()));
		assert(!Base\Date::isMonth(Base\Date::addMonth(1)));
		assert(!Base\Date::isMonth(Base\Date::addYear(1)));
		assert(Base\Date::isMonth(Base\Date::changeDay(2)));

		// isDay
		assert(Base\Date::isDay(Base\Date::getTimestamp()));
		assert(!Base\Date::isDay(Base\Date::addDay(1)));
		assert(!Base\Date::isDay(Base\Date::addMonth(1)));
		assert(Base\Date::isDay(Base\Date::changeHour(2)));

		// isDayStart
		assert(Base\Date::isDayStart('2-3-2017','d-m-Y'));
		assert(Base\Date::isDayStart('2-3-2017 00:00:00','d-m-Y H:i:s'));
		assert(!Base\Date::isDayStart('2-3-2017 00:00:01','d-m-Y H:i:s'));

		// isHour
		assert(Base\Date::isHour(Base\Date::getTimestamp()));
		assert(!Base\Date::isHour(Base\Date::addHour(1)));

		// isMinute
		assert(Base\Date::isMinute(Base\Date::getTimestamp()));
		assert(!Base\Date::isMinute(Base\Date::addMinute(1)));

		// isSecond
		assert(Base\Date::isSecond(Base\Date::getTimestamp()));
		assert(!Base\Date::isSecond(Base\Date::addSecond(2)));

		// isFormat
		assert(Base\Date::isFormat('ym','2014-02'));
		assert(Base\Date::isFormat('d-m-Y','2-3-2017'));
		assert(Base\Date::isFormat('d-m-Y','12-03-2017'));
		assert(!Base\Date::isFormat('d-m-Y','12-03-17'));
		assert(!Base\Date::isFormat('d-m-Y H:i:s','12-03-17 12:40:02'));
		assert(Base\Date::isFormat('d-m-Y H:i:s','12-03-2017 12:40:02'));

		// isFormatDateToDay
		assert(Base\Date::isFormatDateToDay('12-03-2017'));
		assert(!Base\Date::isFormatDateToDay('12-03-17'));

		// isFormatDateToMinute
		assert(Base\Date::isFormatDateToMinute('12-03-2017 11:40'));
		assert(!Base\Date::isFormatDateToMinute('12-03-2017 11:40:30'));

		// isFormatDateToSecond
		assert(Base\Date::isFormatDateToSecond('12-03-2017 11:40:30'));
		assert(!Base\Date::isFormatDateToSecond('12-03-2017 11:40'));

		// getTimestamp
		assert(is_int(Base\Date::time()));

		// setTimestamp
		Base\Date::setTimestamp(1);
		assert(Base\Date::time() === 1);
		Base\Date::setTimestamp(time());

		// getMicrotime
		assert(is_float(Base\Date::getMicrotime()));

		// setMicrotime
		Base\Date::setMicrotime(Base\Date::getMicrotime());

		// seconds
		assert(count(Base\Date::seconds()) === 6);

		// getAmount
		assert(count(Base\Date::getAmount()) === 9);

		// getStr
		assert(count(Base\Date::getStr()) === 7);

		// getMonths
		assert(count(Base\Date::getMonths()) === 12);

		// getDays
		assert(count(Base\Date::getDays()) === 7);

		// getDaysShort
		assert(count(Base\Date::getDaysShort()) === 7);

		// local
		assert(count(Base\Date::local($timestamp)) === 9);

		// timeOfDay
		assert(count(Base\Date::timeOfDay()) === 4);

		// timestamp
		assert(is_int(Base\Date::timestamp()));

		// microtime
		assert(is_float(Base\Date::microtime()));

		// strtotime
		assert(Base\Date::strtotime('3 weeks from now') === null);
		assert(Base\Date::strtotime('+3 weeks') > Base\Date::time());
		assert(Base\Date::strtotime('-3 weeks') < Base\Date::time());
		assert(is_int(Base\Date::strtotime('tomorrow',Base\Date::make([2017]))));
		assert(Base\Date::sql(Base\Date::strtotime('tomorrow',Base\Date::make([2017]))) === '2017-01-02 00:00:00');

		// getLocaleFormat
		assert(Base\Date::getLocaleFormat('ymd') === '%G-%m-%d');

		// localeFormat
		assert(Base\Date::localeFormat('ymd',$timestamp) === '2017-12-01');
		assert(Base\Date::localeFormat('ymdhis',$timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::localeFormat('ym',$timestamp) === '2017-12');

		// gmtLocaleFormat
		assert(Base\Date::gmtLocaleFormat('ymdhis',$timestamp) === '2017-12-01 14:40:42');

		// getFormats
		assert(count(Base\Date::getFormats()) > 5);

		// getFormat
		assert(Base\Date::getFormat('sql') === 'Y-m-d H:i:s');
		assert(Base\Date::getFormat('sqlz') === 'sqlz');
		assert(Base\Date::getFormat('') === null);
		assert(Base\Date::getFormat(true) === 'F j, Y');
		assert(Base\Date::getFormat(0) === 'F j, Y');

		// getFormatReplace
		assert(Base\Date::getFormatReplace('sql') === ['format'=>'Y-m-d H:i:s','replace'=>null]);
		assert(count(Base\Date::getFormatReplace('j %m% Y')['replace']['%']) === 12);

		// setFormat
		assert(Base\Date::setFormat('yearz','Y'));
		assert(Base\Date::format('yearz',$timestamp) === '2017');

		// unsetFormat
		assert(Base\Date::unsetFormat('yearz'));
		assert(Base\Date::format('yearz') !== '2017');

		// parseFormat
		assert(Base\Date::parseFormat('ymd') === ['format'=>'Y-m-d','timezone'=>null,'replace'=>null]);
		assert(Base\Date::parseFormat(['ymd','America/New_York']) === ['format'=>'Y-m-d','timezone'=>'America/New_York','replace'=>null]);
		assert(Base\Date::parseFormat(['timezone'=>'America/New_York','format'=>'ymd']) === ['format'=>'Y-m-d','timezone'=>'America/New_York','replace'=>null]);
		assert(count(Base\Date::parseFormat('j %m% Y')['replace']['%']) === 12);
		assert(Base\Date::parseFormat(['format'=>'j %m% Y','replace'=>['%'=>[12=>'James']]])['replace']['%'][12] === 'James');

		// format
		assert(Base\Date::format('d-m-Y h:i:s','12-03-2017','d-m-Y') === '12-03-2017 12:00:00');
		assert(Base\Date::format('d-m-Y h:i:s',$timestamp) === '01-12-2017 09:40:42');
		assert(Base\Date::format('sql',$timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::format('ymdhis',$timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::format(['sql','Europe/Prague'],$timestamp) === '2017-12-01 15:40:42');
		assert(Base\Date::format(['sql','America/New_York'],$timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::format(['sql',true],$timestamp) === '2017-12-01 14:40:42');
		assert(Base\Date::format('j %m% Y',$timestamp) === '1 december 2017');
		assert(Base\Date::format(['j %m% Y','replace'=>['%'=>[12=>'James']]],$timestamp) === '1 James 2017');
		assert(Base\Date::format(0,$timestamp) === 'December 1, 2017');
		assert(Base\Date::format(true,$timestamp) === 'December 1, 2017');
		assert(Base\Date::format(1,$timestamp) === 'December 1, 2017 09:40:42');
		assert(Base\Date::format(2,$timestamp) === 'December 2017');
		assert(Base\Date::format(3,$timestamp) === '12-01-2017');
		assert(Base\Date::format(4,$timestamp) === '12-01-2017 09:40:42');

		// formatDayStart
		assert(Base\Date::formatDayStart(Base\Date::mk(2018,12,12,12,12,12)) === '2018-12-12 12:12:12');
		assert(Base\Date::formatDayStart(Base\Date::mk(2018,12,12)) === '2018-12-12');
		assert(Base\Date::formatDayStart(Base\Date::mk(2018,12,12,0,0,0)) === '2018-12-12');
		assert(Base\Date::formatDayStart(Base\Date::mk(2018,12,12,0,0,1)) === '2018-12-12 00:00:01');

		// formatStartEnd
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,12,12,12),Base\Date::mk(2018,12,14,12,12,14)) === '2018-12-12 12:12:12 - 2018-12-14 12:12:14');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,12,12,12),Base\Date::mk(2018,12,12,12,12,14)) === '2018-12-12 12:12:12 - 12:12:14');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,0),Base\Date::mk(2018,12,13,12,12,14)) === '2018-12-12 00:00:00 - 2018-12-13 12:12:14');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,12,12,12),Base\Date::mk(2018,12,12,12,12,12)) === '2018-12-12 12:12:12');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,0),Base\Date::mk(2018,12,12,0,0,0)) === '2018-12-12');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,0),Base\Date::mk(2018,12,12,0,0,0),['dayStart'=>false]) === '2018-12-12 00:00:00');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,0),Base\Date::mk(2018,12,13,0,0,0)) === '2018-12-12 - 2018-12-13');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,0),Base\Date::mk(2018,12,13,0,0,1)) === '2018-12-12 00:00:00 - 2018-12-13 00:00:01');
		assert(Base\Date::formatStartEnd(Base\Date::mk(2018,12,12,0,0,1),Base\Date::mk(2018,12,13,0,0,0)) === '2018-12-12 00:00:01 - 2018-12-13 00:00:00');

		// gmtFormat
		assert(Base\Date::gmtFormat('d-m-Y h:i:s',$timestamp) === '01-12-2017 02:40:42');
		assert(Base\Date::gmtFormat('ymdhis',$timestamp) === '2017-12-01 14:40:42');

		// formats
		assert(Base\Date::formats('ymd',[$timestamp,['year'=>2017]]) === ['2017-12-01','2017-01-01']);
		assert(Base\Date::formats(0,[$timestamp,['year'=>2017]]) === ['December 1, 2017','January 1, 2017']);

		// formatReplace
		assert(Base\Date::formatReplace('1 %2% 2017',['%'=>['2'=>'octobre']]) === '1 octobre 2017');

		// getPlaceholders
		assert(count(Base\Date::getPlaceholders('fr')) === 3);

		// placeholder
		assert(Base\Date::placeholder('dateToDay') === 'MM-DD-YYYY');

		// ymd
		assert(Base\Date::ymd($timestamp) === '2017-12-01');

		// ymdhis
		assert(Base\Date::ymdhis($timestamp) === '2017-12-01 09:40:42');

		// ym
		assert(Base\Date::ym($timestamp) === '2017-12');

		// his
		assert(Base\Date::his($timestamp) === '09:40:42');

		// rfc822
		assert(Base\Date::rfc822($timestamp) === 'Fri, 1 Dec 2017 09:40:42 -0500');

		// sql
		assert(Base\Date::sql($timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::sql($timestamp,true) === '2017-12-01 14:40:42');
		assert(Base\Date::sql($timestamp,'Europe/Prague') === '2017-12-01 15:40:42');

		// compact
		assert(Base\Date::compact($timestamp) === '20171201094042');

		// gmt
		assert(Base\Date::gmt($timestamp) === 'Fri, 01 Dec 2017 14:40:42 GMT');

		// iformat
		assert(Base\Date::iformat('Y',$timestamp) === 2017);
		assert(Base\Date::iformat('year',$timestamp) === 2017);
		assert(Base\Date::iformat('Y-m-d',$timestamp) === null);
		assert(Base\Date::iformat(['h','Europe/Prague'],$timestamp) === 3);
		assert(Base\Date::iformat(['h','America/New_York'],$timestamp) === 9);

		// countDaysInMonth
		assert(Base\Date::countDaysInMonth($timestamp) === 31);
		assert(Base\Date::countDaysInMonth(['year'=>2018,'month'=>11]) === 30);
		assert(Base\Date::countDaysInMonth(['year'=>2018,'month'=>2]) === 28);
		assert(Base\Date::countDaysInMonth(Base\Date::make([2017,2,2,10,0,0])) === 28);
		assert(Base\Date::countDaysInMonth([2017,2,2,10,0,0]) === 28);

		// weekNo
		assert(Base\Date::weekNo($timestamp) === 48);

		// weekDay
		assert(Base\Date::weekDay($timestamp) === 5);

		// year
		assert(Base\Date::year($timestamp) === 2017);

		// month
		assert(Base\Date::month($timestamp) === 12);

		// day
		assert(Base\Date::day($timestamp) === 1);

		// hour
		assert(Base\Date::hour($timestamp) === 9);
		assert(Base\Date::hour($timestamp,true) === 14);
		assert(Base\Date::hour($timestamp,'Asia/Bangkok') === 21);

		// minute
		assert(Base\Date::minute($timestamp) === 40);

		// second
		assert(Base\Date::second($timestamp) === 42);

		// parse
		assert(Base\Date::parse('j %m% Y','1 december 2017') === ['year'=>2017,'month'=>12,'day'=>1]);
		assert(Base\Date::parse(['j %m% Y','replace'=>['%'=>[12=>'James']]],'1 James 2017') === ['year'=>2017,'month'=>12,'day'=>1]);
		assert(count(Base\Date::parse('sql','2017-12-01 09:40:42')) === 6);
		assert(Base\Date::parse('gmt','2017-12-01 09:40:42') === null);
		assert(count(Base\Date::parse('gmt','Fri, 01 Dec 2017 09:40:42 GMT')) === 6);

		// parseMake
		assert(Base\Date::parseMake('sql','2017-12-01 09:40:42') === $timestamp);
		assert(Base\Date::parseMake('gmt','Fri, 01 Dec 2017 09:40:42 GMT') === $timestamp);
		assert(Base\Date::parseMake(['sql','America/New_York'],'2017-12-01 09:40:42') === 1512139242);
		assert(Base\Date::parseMake(['sql','Europe/Prague'],'2017-12-01 09:40:42') === 1512117642);
		assert(Base\Date::format(['sql','Europe/Prague'],1512117642) === '2017-12-01 09:40:42');

		// parseLocale
		assert(count(Base\Date::parseLocale('%Y','2017')) === 8);

		// parseStr
		assert(Base\Date::parseStr('2017-12-10 12:20 -1day')['day'] === 9);
		assert(count(Base\Date::parseStr('2017-12-10 12:20 -1week')) === 6);
		assert(count(Base\Date::parseStr('2017-12-10')) === 3);
		assert(Base\Date::sql(Base\Date::make(Base\Date::parseStr('2017-12-10 12:20 -1day'))) === '2017-12-09 12:20:00');

		// parseReplace
		assert(Base\Date::parseReplace('1 december 2017',['%'=>[12=>'december']]) === '1 %12% 2017');

		// parsePrepare
		assert(Base\Date::parsePrepare(['warning'=>0]) === []);

		// time
		assert(Base\Date::time($timestamp) === $timestamp);
		assert(Base\Date::make([2017,1]) === Base\Date::time(['year'=>2017,'month'=>1]));
		assert(Base\Date::time(['year'=>2017,'month'=>1,'day'=>1,'hour'=>1,'minute'=>1,'second'=>1],['timezone'=>true]) === 1483232461);
		assert(Base\Date::time(['year'=>2017,'month'=>1,'day'=>1,'hour'=>1,'minute'=>1,'second'=>1],[true,'Asia/Bangkok']) === 1483207261);
		assert(Base\Date::time() !== $timestamp);
		assert(Base\Date::time(2017,null,true) === 1483246800);
		assert(Base\Date::time(2017) === 2017);
		assert(Base\Date::time(null) === Base\Date::time());
		assert(Base\Date::time() === Base\Date::time());
		assert(Base\Date::time('2017-12-01','ymd') === 1512104400);
		assert(Base\Date::time(-1212212121121) === -1212212121121);
		assert(Base\Date::time('1 december 2017',['j %m% Y',null,['december'=>'12']]) === 1512104400);
		assert(Base\Date::time(0) === 0);
		assert(Base\Date::time(123,null,true) < 0);
		assert(Base\Date::time(123) === 123);
		assert(Base\Date::time('1483246800') === 1483246800);
		assert(Base\Date::time('20 décembre 2017',['format'=>'j %m% Y','lang'=>'en']) === null);

		// get
		assert(count(Base\Date::get($timestamp)) === 6);
		assert(Base\Date::get(1)['year'] === 1969);
		assert(Base\Date::isValid(Base\Date::get(1)));
		assert(Base\Date::make(Base\Date::get(1)) === 1);
		assert(Base\Date::make(Base\Date::get($timestamp)) === $timestamp);
		assert(Base\Date::get(['year'=>2017])['month'] === 1);
		assert(Base\Date::get('1483246802')['second'] === 2);
		assert(Base\Date::get($timestamp,'Europe/Prague')['hour'] === 15);
		assert(Base\Date::get($timestamp,true)['hour'] === 14);

		// keep
		assert(Base\Date::keep(2,$timestamp) === ['year'=>2017,'month'=>12]);
		assert(Base\Date::keep(4,$timestamp) === ['year'=>2017,'month'=>12,'day'=>1,'hour'=>9]);
		assert(Base\Date::keep(3,3720) === ['year'=>1969,'month'=>12,'day'=>31]);
		assert(Base\Date::keep(2,['year'=>0,'month'=>0,'day'=>14,'hour'=>10]) === ['day'=>14,'hour'=>10]);

		// str
		assert(Base\Date::str(6,['year'=>0,'month'=>0,'day'=>14,'hour'=>10]) === '14 days and 10 hours');
		assert(Base\Date::str(6,['year'=>12,'month'=>3,'day'=>14,'hour'=>10]) === '12 years 3 months 14 days and 10 hours');
		assert(Base\Date::str(1,['year'=>12,'month'=>3,'day'=>14,'hour'=>10]) === '12 years');

		// make
		assert(Base\Date::make([2017]) === 1483246800);
		assert(Base\Date::make([2017,2]) === 1485925200);
		assert(Base\Date::sql(Base\Date::make([2017])) === '2017-01-01 00:00:00');
		assert(Base\Date::make(['year'=>2017,'month'=>2]) === 1485925200);
		assert(Base\Date::make(['year'=>2017,'month'=>'2']) === 1485925200);
		assert(Base\Date::make([2017]) === Base\Date::make([2017,1,1]));
		assert(Base\Date::make([2017,0,0]) !== Base\Date::make([2017,1,1]));
		assert(Base\Date::make([2017,1,1,1,1,1],true) === 1483232461);
		assert(Base\Date::make([2017,1,1,1,1,1],'America/New_York') === 1483250461);

		// gmtMake
		assert(Base\Date::gmtMake([2017,2]) === 1485907200);
		assert(Base\Date::gmtMake([2017,1,1,1,1,1]) === 1483232461);

		// mk
		assert(Base\Date::mk(2017,1,1,1,1,1,true) === 1483232461);
		assert(Base\Date::mk(2017,1,1,1,1,1,'America/New_York') === 1483250461);

		// add
		assert(Base\Date::add(['year'=>1],$timestamp) === 1543675242);
		assert(Base\Date::sql(Base\Date::add(['year'=>1],$timestamp)) === '2018-12-01 09:40:42');
		assert(Base\Date::sql(Base\Date::add(['year'=>-1],$timestamp)) === '2016-12-01 09:40:42');
		assert(Base\Date::ymd(Base\Date::add(['year'=>1],'2017-08-23','ymd')) === '2018-08-23');
		assert(Base\Date::sql(Base\Date::add(['day'=>1],'2017-12-02 4:40:42',['sql','Europe/Prague']),'Europe/Prague') === '2017-12-03 04:40:42');

		// change
		assert(Base\Date::sql(Base\Date::change(['year'=>2010,'month'=>'4'],$timestamp)) === '2010-04-01 09:40:42');

		// remove
		assert(Base\Date::sql(Base\Date::remove(['year','month','day','hour','minute','second'],$timestamp)) === '2000-01-01 00:00:00');
		assert(Base\Date::sql(Base\Date::remove(['year','month','day','hour'],$timestamp)) === '2000-01-01 00:40:42');

		// getFloor
		assert(Base\Date::sql(Base\Date::getFloor('month',$timestamp)) === '2017-12-01 00:00:00');
		assert(Base\Date::sql(Base\Date::getFloor('minute',$timestamp)) === '2017-12-01 09:40:00');
		assert(Base\Date::sql(Base\Date::getFloor('day',2017)) === '2017-01-01 00:00:00');
		assert(Base\Date::sql(Base\Date::getFloor('year',['year'=>2017,'month'=>2])) === '2017-01-01 00:00:00');
		assert(Base\Date::sql(Base\Date::getFloor('day','2017-12-02 09:40:42','sql')) === '2017-12-02 00:00:00');
		assert(Base\Date::sql(Base\Date::getFloor('day','2017-12-02 09:40:42',['sql','Europe/Prague'])) === '2017-12-02 00:00:00');
		assert(Base\Date::sql(Base\Date::getFloor('day','2017-12-02 4:40:42',['sql','Europe/Prague'])) === '2017-12-01 00:00:00');

		// getCeil
		assert(Base\Date::sql(Base\Date::getCeil('day',[2017,2,1])) === '2017-02-01 23:59:59');
		assert(Base\Date::sql(Base\Date::getCeil('month',[2017,2,1])) === '2017-02-28 23:59:59');
		assert(Base\Date::sql(Base\Date::getCeil('year',[2017,2,1])) === '2017-12-31 23:59:59');
		assert(Base\Date::sql(Base\Date::getCeil('hour',[2017,2,1,5])) === '2017-02-01 05:59:59');
		assert(Base\Date::sql(Base\Date::getCeil('minute',[2017,2,1,0,0])) === '2017-02-01 00:00:59');

		// getFloorCeil
		assert(count(Base\Date::getFloorCeil('day',[2017,2,1,5])) === 2);

		// floor
		assert(Base\Date::sql(Base\Date::floor('month',$timestamp)) === '2017-12-01 00:00:00');

		// ceil
		assert(Base\Date::sql(Base\Date::ceil('day',Base\Date::mk(2017,1,1,0,0,0))) === '2017-01-01 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('month',Base\Date::make([2017,1,1,0,0,0]))) === '2017-01-31 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('year',Base\Date::make([2017,1,1,0,0,0]))) === '2017-12-31 23:59:59');
		assert(Base\Date::sql($timestamp) === '2017-12-01 09:40:42');
		assert(Base\Date::sql(Base\Date::ceil('day',$timestamp)) === '2017-12-01 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('minute',$timestamp)) === '2017-12-01 09:40:59');
		assert(Base\Date::sql(Base\Date::ceil('day',2017)) === '2017-01-01 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('day',Base\Date::make([2017,1,1,0,0,0]))) === '2017-01-01 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('day',Base\Date::mk(2017,1,1,0,0,1))) === '2017-01-01 23:59:59');
		assert(Base\Date::ceil('day',2017) !== Base\Date::ceil('month',2017));
		assert(Base\Date::sql(Base\Date::ceil('year',['year'=>2017,'month'=>2])) === '2017-12-31 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('day','2017-12-02 09:40:42',['sql','Europe/Prague'])) === '2017-12-02 23:59:59');
		assert(Base\Date::sql(Base\Date::ceil('day','2017-12-03 5:40:42',['sql','Europe/Prague'])) === '2017-12-02 23:59:59');

		// floorCeil
		assert(count(Base\Date::floorCeil('day',[2017,2,1,5])) === 2);

		// addYear
		assert(Base\Date::sql(Base\Date::addYear(1,$timestamp)) === '2018-12-01 09:40:42');

		// changeYear
		assert(Base\Date::changeYear(2010,$timestamp) === 1291214442);
		assert(Base\Date::sql(Base\Date::changeYear(2010,$timestamp)) === '2010-12-01 09:40:42');

		// removeYear
		assert(Base\Date::sql(Base\Date::removeYear($timestamp)) === '2000-12-01 09:40:42');

		// floorYear
		assert(Base\Date::sql(Base\Date::floorYear($timestamp)) === '2017-01-01 00:00:00');

		// ceilYear
		assert(Base\Date::sql(Base\Date::ceilYear(['year'=>2017,'month'=>1])) === '2017-12-31 23:59:59');
		assert(Base\Date::sql(Base\Date::ceilYear(['year'=>2017,'month'=>2])) === '2017-12-31 23:59:59');

		// floorCeilYear
		assert(count(Base\Date::floorCeilYear($timestamp)) === 2);

		// addMonth
		assert(Base\Date::sql(Base\Date::addMonth(2,$timestamp)) === '2018-02-01 09:40:42');

		// changeMonth
		assert(Base\Date::sql(Base\Date::changeMonth(9,$timestamp)) === '2017-09-01 09:40:42');
		assert(Base\Date::sql(Base\Date::changeMonth(24,$timestamp)) === '2018-12-01 09:40:42');

		// removeMonth
		assert(Base\Date::sql(Base\Date::removeMonth($timestamp)) === '2017-01-01 09:40:42');

		// floorMonth
		assert(Base\Date::sql(Base\Date::floorMonth($timestamp)) === '2017-12-01 00:00:00');

		// ceilMonth
		assert(Base\Date::sql(Base\Date::ceilMonth(['year'=>2017,'month'=>1,'hour'=>0])) === '2017-01-31 23:59:59');
		assert(Base\Date::sql(Base\Date::ceilMonth(['year'=>2017,'month'=>1,'second'=>1])) === '2017-01-31 23:59:59');

		// floorCeilMonth
		assert(count(Base\Date::floorCeilMonth($timestamp)) === 2);

		// addDay
		assert(Base\Date::sql(Base\Date::addDay(32,$timestamp)) === '2018-01-02 09:40:42');
		assert(Base\Date::addDay(1) > Base\Date::getTimestamp());

		// changeDay
		assert(Base\Date::sql(Base\Date::changeDay(13,$timestamp)) === '2017-12-13 09:40:42');
		assert(Base\Date::sql(Base\Date::changeDay(-1,$timestamp)) === '2017-11-29 09:40:42');
		assert(Base\Date::ymd(Base\Date::changeDay(3,'2017-08-23','ymd')) === '2017-08-03');
		assert(Base\Date::ymd(Base\Date::changeDay(33,'2017-08-23','ymd')) === '2017-09-02');
		assert(Base\Date::get(Base\Date::changeDay(1))['day'] === 1);

		// removeDay
		assert(Base\Date::sql(Base\Date::removeDay($timestamp)) === '2017-12-01 09:40:42');

		// floorDay
		assert(Base\Date::sql(Base\Date::floorDay($timestamp)) === '2017-12-01 00:00:00');
		assert(Base\Date::sql(Base\Date::floorDay('2017-12-04 4:00:00','sql')) === '2017-12-04 00:00:00');

		// ceilDay
		assert(Base\Date::sql(Base\Date::ceilDay(['year'=>2017,'month'=>1,'day'=>2,'hour'=>0])) === '2017-01-02 23:59:59');
		assert(Base\Date::sql(Base\Date::ceilDay(['year'=>2017,'month'=>1,'day'=>2,'second'=>1])) === '2017-01-02 23:59:59');

		// floorCeilDay
		assert(count(Base\Date::floorCeilDay($timestamp)) === 2);

		// addHour
		assert(Base\Date::sql(Base\Date::addHour(25,$timestamp)) === '2017-12-02 10:40:42');

		// changeHour
		assert(Base\Date::sql(Base\Date::changeHour(22,$timestamp)) === '2017-12-01 22:40:42');
		assert(Base\Date::sql(Base\Date::changeHour(25,$timestamp)) === '2017-12-02 01:40:42');

		// removeHour
		assert(Base\Date::sql(Base\Date::removeHour($timestamp)) === '2017-12-01 00:40:42');

		// floorHour
		assert(Base\Date::sql(Base\Date::floorHour($timestamp)) === '2017-12-01 09:00:00');

		// ceilHour
		assert(Base\Date::sql(Base\Date::ceilHour(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1])) === '2017-01-02 01:59:59');
		assert(Base\Date::sql(Base\Date::ceilHour(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1,'second'=>1])) === '2017-01-02 01:59:59');

		// floorCeilHour
		assert(count(Base\Date::floorCeilHour($timestamp)) === 2);

		// addMinute
		assert(Base\Date::sql(Base\Date::addMinute(61,$timestamp)) === '2017-12-01 10:41:42');

		// changeMinute
		assert(Base\Date::sql(Base\Date::changeMinute(45,$timestamp)) === '2017-12-01 09:45:42');
		assert(Base\Date::sql(Base\Date::changeMinute(-45,$timestamp)) === '2017-12-01 08:15:42');

		// removeMinute
		assert(Base\Date::sql(Base\Date::removeMinute($timestamp)) === '2017-12-01 09:00:42');

		// floorMinute
		assert(Base\Date::sql(Base\Date::floorMinute($timestamp)) === '2017-12-01 09:40:00');

		// ceilMinute
		assert(Base\Date::sql(Base\Date::ceilMinute(['year'=>2017,'month'=>1,'day'=>2,'minute'=>1])) === '2017-01-02 00:01:59');
		assert(Base\Date::sql(Base\Date::ceilMinute(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1,'minute'=>1,'second'=>1])) === '2017-01-02 01:01:59');

		// floorCeilMinute
		assert(count(Base\Date::floorCeilMinute($timestamp)) === 2);

		// addSecond
		assert(Base\Date::sql(Base\Date::addSecond(62,$timestamp)) === '2017-12-01 09:41:44');

		// changeSecond
		assert(Base\Date::sql(Base\Date::changeSecond(55,$timestamp)) === '2017-12-01 09:40:55');
		assert(Base\Date::sql(Base\Date::changeSecond(3600,$timestamp)) === '2017-12-01 10:40:00');

		// removeSecond
		assert(Base\Date::sql(Base\Date::removeSecond($timestamp)) === '2017-12-01 09:40:00');

		// diff
		assert(Base\Date::diff('2017-12-01 09:40:00','2017-12-01 09:40:00',['sql','timezone'=>true],['sql','timezone'=>'Asia/Bangkok'])['hour'] === 7);
		assert(Base\Date::diff(7201)['hour'] === 2);
		assert(count(Base\Date::diff($timestamp)) === 6);
		assert(Base\Date::diff($timestamp,($timestamp - 9002))['hour'] === 2);
		assert(Base\Date::diff(200,400)['minute'] === 3);
		assert(Base\Date::diff(400,200)['minute'] === 3);

		// diffTotal
		assert(Base\Date::diffTotal(Base\Date::make([2016,1,1]),Base\Date::make([2019,4,28,12,7,54]),false) === ['year'=>3,'month'=>39,'day'=>1213,'hour'=>29123,'minute'=>1747387,'second'=>104843274]);
		assert(Base\Date::diffTotal(Base\Date::make([2016,1,1]),Base\Date::make([2019,4,28,12,7,54]),true)['year'] === 4);
		assert(Base\Date::diffTotal('2017-12-01 09:40:00','2017-12-01 09:40:00',true,['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok'])['hour'] === 13);

		// diffNow
		assert(count(Base\Date::diffNow(Base\Date::make([2016,1,1]))) === 6);
		assert(Base\Date::diffNow('2017-12-01 09:40:00',['sql',true]) !== Base\Date::diffNow('2017-12-01 09:40:00',['sql','Asia/Bangkok']));
		assert(count(Base\Date::diffNow(Base\Date::mk(2020,1,1))) === 6);

		// ago
		assert(Base\Date::ago(Base\Date::mk(2020,1,1)) === null);
		assert(count(Base\Date::ago(Base\Date::mk(2014,1,1))) === 6);

		// diffKeep
		assert(Base\Date::diffKeep(4,Base\Date::mk(2018,1,2),Base\Date::mk(2021,3,4)) === ['year'=>3,'month'=>2,'day'=>2]);

		// diffStr
		assert(Base\Date::diffStr(4,Base\Date::mk(2018,1,2),Base\Date::mk(2021,3,4)) === '3 years 2 months and 2 days');

		// diffNowKeep
		assert(!empty(Base\Date::diffNowKeep(3,Base\Date::mk(2018,4,2))));

		// diffNowStr
		assert(strlen(Base\Date::diffNowStr(4,Base\Date::mk(2018,1,7))) > 10);

		// agoKeep
		assert(!empty(Base\Date::agoKeep(4,Base\Date::mk(2012,8,2))));

		// agoStr
		assert(Base\Date::agoStr(5,time()) === '');
		assert(strlen(Base\Date::agoStr(2,Base\Date::mk(2010,1,9))) > 15);
		assert(Base\Date::agoStr(5,Base\Date::mk(2040,1,9)) === '');

		// calendar
		$calendar = Base\Date::calendar([2018,6],true,false);
		assert(Base\Arrs::is($calendar));
		assert(count(Base\Date::calendar([2018,12],true,false)) === 6);
		assert(count(Base\Date::calendar([2018,12],true,true)) === 6);
		assert(count(Base\Date::calendar([2010,1],false,true)) === 5);
		assert(count(Base\Date::calendar([2010,1],true,true)) === 6);

		// fillCalendar
		assert(Base\Date::fillCalendar($calendar) !== $calendar);

		// daysDiff
		assert(Base\Date::daysDiff($timestamp,$timestamp) === 0);
		assert(Base\Date::daysDiff(null,null) === 0);
		assert(Base\Date::daysDiff(Base\Date::make([2018,1,1]),Base\Date::make([2018,1,1])) === 0);
		assert(Base\Date::daysDiff([2015,1,1],[2016,1,1]) === 365);
		assert(Base\Date::daysDiff(2016,2015) === 365);
		assert(Base\Date::daysDiff([2016,1,2],[2015,1,1]) === 366);
		assert(Base\Date::daysDiff(2015,Base\Date::make([2016,null,null,0,0,1]),null) === 365);
		assert(Base\Date::daysDiff(2015,Base\Date::make([2016,null,null,0,0,1]),false) === null);
		assert(Base\Date::daysDiff(2015,Base\Date::make([2016,1,2,0,0,0])) === 366);
		assert(Base\Date::daysDiff([2016,1,3],[2016,1,5]) === 2);
		assert(Base\Date::daysDiff([2016,1,5],[2016,1,3]) === 2);
		assert(Base\Date::daysDiff('2017-12-01 11:40:00','2017-12-01 10:40:00',['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 1);
		assert(Base\Date::daysDiff('2017-12-01 11:40:00','2017-12-01 12:40:00',['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 0);

		// monthsDiff
		assert(Base\Date::monthsDiff(2016,2017) === 12);
		assert(Base\Date::monthsDiff($timestamp,2019) === 13);
		assert(Base\Date::monthsDiff(2016,Base\Date::make([2017,null,null,1])) === 12);
		assert(Base\Date::monthsDiff(null,null) === 0);
		assert(Base\Date::monthsDiff(Base\Date::make([2018,1,5]),Base\Date::make([2018,1,5])) === 0);
		assert(Base\Date::monthsDiff(Base\Date::make([2018,1,3]),Base\Date::make([2018,1,6])) === 0);
		assert(Base\Date::monthsDiff('2017-01-01 00:00:00','2017-01-01 00:00:01','sql','sql') === 0);
		assert(Base\Date::monthsDiff('2017-01-01 00:00:00','2017-03-03 00:00:01','sql','sql') === 2);
		assert(Base\Date::monthsDiff('2017-01-01 00:00:00','2017-01-01 00:00:00',['sql','America/New_York'],['sql','Asia/Bangkok']) === 1);

		// yearsDiff
		assert(Base\Date::yearsDiff(2016,2018) === 2);
		assert(Base\Date::yearsDiff(2016,Base\Date::make([2018,2])) === 2);
		assert(Base\Date::yearsDiff(2016,2016) === 0);
		assert(Base\Date::yearsDiff(null,null) === 0);
		assert(Base\Date::yearsDiff('2017-01-01 00:00:00','2017-01-01 00:00:00','sql','sql') === 0);
		assert(Base\Date::yearsDiff('2017-12-31 23:40:00','2018-1-1 03:40:00',['sql','America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 0);
		assert(Base\Date::yearsDiff('2017-12-31 23:40:00','2018-1-1 03:40:00','sql','sql') === 1);

		// days
		assert(Base\Date::days(2016,2017,20,'sql')[1451624400] === '2016-01-01 00:00:00');
		assert(count(Base\Date::days(2016,Base\Date::make([2017,null,null,0,0,1]),20)) === 19);
		assert(count(Base\Date::days(2015,2016)) === count(Base\Date::days(2016,2015)));
		assert(Base\Date::days(Base\Date::mk(2017,1,2),Base\Date::mk(2017,1,2)) === [1=>1483333200]);
		assert(current(Base\Date::days(Base\Date::mk(2017,1,1),Base\Date::mk(2017,1,4),null,'sql')) === '2017-01-01 00:00:00');
		assert(current(Base\Date::days(Base\Date::mk(2017,1,4),Base\Date::mk(2017,1,1),null,'sql')) === '2017-01-04 00:00:00');
		assert(current(Base\Date::days(Base\Date::mk(2017,1,2,12),Base\Date::mk(2017,1,4),null,'sql',false)) === '2017-01-02 12:00:00');
		assert(current(Base\Date::days(Base\Date::mk(2017,1,2,12),Base\Date::mk(2017,1,4),null,'sql',true)) === '2017-01-02 00:00:00');

		// secondsInDay
		assert(Base\Date::secondsInDay('2016-01-01 00:00:00','sql') === 0);
		assert(Base\Date::secondsInDay('2016-01-01 00:00:23','sql') === 23);
		assert(Base\Date::secondsInDay('2016-01-01 00:10:23','sql') === 623);
		assert(Base\Date::secondsInDay('2016-01-01 20:10:23','sql') === 72623);

		// daysInMonth
		assert(count(Base\Date::daysInMonth()) >= 28);
		assert(count(Base\Date::daysInMonth([2017,02,20],20,'sql')) === 2);
		assert(Base\Date::daysInMonth([2017,02,20],1,'sql')[1485925200] === '2017-02-01 00:00:00');
		assert(count(Base\Date::daysInMonth([2010,1])) === 31);

		// daysDefault
		assert(count(Base\Date::daysDefault()) === 31);

		// months
		assert(current(Base\Date::months(Base\Date::mk(2017,4,2),Base\Date::mk(2017,4,2),null,'sql')) === '2017-04-01 00:00:00');
		assert(count(Base\Date::months(2016,2017,1,'sql')) === 13);
		assert(Base\Date::months(2016,2017,1,'sql')[1451624400] === '2016-01-01 00:00:00');
		assert(current(Base\Date::months(Base\Date::mk(2017,2,3),Base\Date::mk(2017,4,2),null,'sql',false)) === '2017-02-03 00:00:00');
		assert(count(Base\Date::months(Base\Date::mk(2017,2,3),Base\Date::mk(2017,4,2),null,'sql')) === 3);
		assert(current(Base\Date::months(Base\Date::mk(2017,4,2),Base\Date::mk(2017,2,3),null,'sql',false)) === '2017-04-02 00:00:00');
		assert(Base\Arr::index(0,Base\Date::months(2018,2017,1,'sql')) === Base\Arr::valueLast(Base\Date::months(2017,2018,1,'sql')));

		// monthsInYear
		assert(count(Base\Date::monthsInYear(2018,1,'sql')) === 12);
		assert(count(Base\Date::monthsInYear(2018,2,'sql')) === 6);
		assert(Base\Date::monthsInYear(2018,2,'sql')[1514782800] === '2018-01-01 00:00:00');
		assert(count(Base\Date::monthsInYear(null,1,'sql')) === 12);

		// monthsDefault
		assert(count(Base\Date::monthsDefault()) === 12);

		// years
		assert(count(Base\Date::years(1984,1994)) === 11);
		assert(Base\Date::years(1984,1994,1,'sql')[441781200] === '1984-01-01 00:00:00');
		assert(Base\Date::years(1994,1992,null,'sql') !== Base\Date::years(1992,1994,null,'sql'));
		assert(Base\Date::years([1994,03,03],1992,null,'sql',false) !== Base\Date::years([1994,03,03],1992,null,'sql'));

		// yearsDefault
		assert(count(Base\Date::yearsDefault(-100,0,null,'sql')) === 101);
		assert(count(Base\Date::yearsDefault()) === 131);

		// onSet
		assert(Base\Date::onSet('12-3-2017','d-m-Y') === 1489294800);
		assert(Base\Date::onSet(1489294800,'d-m-Y') === 1489294800);

		// onGet
		assert(is_string(Base\Date::onGet(time())));

		return true;
	}
}
?>