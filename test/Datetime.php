<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// datetime
// class for testing Quid\Base\Datetime
class Datetime extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $timestamp = 1512139242;

        // isNow
        assert(Base\Datetime::isNow(Base\Datetime::now()));
        assert(!Base\Datetime::isNow($timestamp));

        // isValid
        assert(Base\Datetime::isValid(2017,12,12));
        assert(!Base\Datetime::isValid(2017,12,50));
        assert(Base\Datetime::isValid(['year'=>2017,'month'=>12,'day'=>1]));

        // isYearValid
        assert(Base\Datetime::isYearValid(2017));
        assert(!Base\Datetime::isYearValid($timestamp));

        // isYearLeap
        assert(Base\Datetime::isYearLeap(2016));
        assert(!Base\Datetime::isYearLeap(2017));
        assert(Base\Datetime::isYearLeap(Base\Datetime::make([2016,2,2])));

        // isToday
        assert(Base\Datetime::isToday(Base\Datetime::now()));
        assert(Base\Datetime::isToday(Base\Datetime::format('ymd',Base\Datetime::now()),'ymd'));

        // isTomorrow
        assert(!Base\Datetime::isTomorrow(Base\Datetime::now()));
        assert(Base\Datetime::isTomorrow(Base\Datetime::addDay(1)));

        // isYesterday
        assert(!Base\Datetime::isYesterday(Base\Datetime::now()));
        assert(Base\Datetime::isYesterday(Base\Datetime::addDay(-1)));

        // isWeekend
        assert(!Base\Datetime::isWeekend($timestamp));
        assert(Base\Datetime::isWeekend('2020-06-06','Y-m-d'));
        assert(Base\Datetime::isWeekend('2020-06-07','Y-m-d'));
        assert(!Base\Datetime::isWeekend('2020-06-05','Y-m-d'));

        // isYear
        assert(Base\Datetime::isYear(Base\Datetime::now()));
        assert(!Base\Datetime::isYear(Base\Datetime::addYear(1)));

        // isMonth
        assert(Base\Datetime::isMonth(Base\Datetime::now()));
        assert(!Base\Datetime::isMonth(Base\Datetime::addMonth(1)));
        assert(!Base\Datetime::isMonth(Base\Datetime::addYear(1)));
        assert(Base\Datetime::isMonth(Base\Datetime::changeDay(2)));

        // isDay
        assert(Base\Datetime::isDay(Base\Datetime::now()));
        assert(!Base\Datetime::isDay(Base\Datetime::addDay(1)));
        assert(!Base\Datetime::isDay(Base\Datetime::addMonth(1)));
        assert(Base\Datetime::isDay(Base\Datetime::changeHour(2)));

        // isDayStart
        assert(Base\Datetime::isDayStart('2-3-2017','d-m-Y'));
        assert(Base\Datetime::isDayStart('2-3-2017 00:00:00','d-m-Y H:i:s'));
        assert(!Base\Datetime::isDayStart('2-3-2017 00:00:01','d-m-Y H:i:s'));

        // isHour
        assert(Base\Datetime::isHour(Base\Datetime::now()));
        assert(!Base\Datetime::isHour(Base\Datetime::addHour(1)));

        // isMinute
        assert(Base\Datetime::isMinute(Base\Datetime::now()));
        assert(!Base\Datetime::isMinute(Base\Datetime::addMinute(1)));

        // isSecond
        assert(Base\Datetime::isSecond(Base\Datetime::now()));
        assert(!Base\Datetime::isSecond(Base\Datetime::addSecond(2)));

        // isFormat
        assert(Base\Datetime::isFormat('ym','2014-02'));
        assert(Base\Datetime::isFormat('d-m-Y','2-3-2017'));
        assert(Base\Datetime::isFormat('d-m-Y','12-03-2017'));
        assert(!Base\Datetime::isFormat('d-m-Y','12-03-17'));
        assert(!Base\Datetime::isFormat('d-m-Y H:i:s','12-03-17 12:40:02'));
        assert(Base\Datetime::isFormat('d-m-Y H:i:s','12-03-2017 12:40:02'));

        // isFormatDateToDay
        assert(Base\Datetime::isFormatDateToDay('12-03-2017'));
        assert(!Base\Datetime::isFormatDateToDay('12-03-17'));

        // isFormatDateToMinute
        assert(Base\Datetime::isFormatDateToMinute('12-03-2017 11:40'));
        assert(!Base\Datetime::isFormatDateToMinute('12-03-2017 11:40:30'));

        // isFormatDateToSecond
        assert(Base\Datetime::isFormatDateToSecond('12-03-2017 11:40:30'));
        assert(!Base\Datetime::isFormatDateToSecond('12-03-2017 11:40'));

        // getInitTimestamp
        assert(is_int(Base\Datetime::getInitTimestamp()));

        // setInitTimestamp
        Base\Datetime::setInitTimestamp(1);
        assert(Base\Datetime::getInitTimestamp() === 1);
        Base\Datetime::setInitTimestamp(time());

        // getInitMicrotime
        assert(is_float(Base\Datetime::getInitMicrotime()));

        // setInitMicrotime
        Base\Datetime::setInitMicrotime(Base\Datetime::getInitMicrotime());

        // seconds
        assert(count(Base\Datetime::seconds()) === 6);

        // getAmount
        assert(count(Base\Datetime::getAmount()) === 9);

        // getStr
        assert(count(Base\Datetime::getStr()) === 7);

        // getMonths
        assert(count(Base\Datetime::getMonths()) === 12);

        // getDays
        assert(count(Base\Datetime::getDays()) === 7);

        // getDaysShort
        assert(count(Base\Datetime::getDaysShort()) === 7);

        // local
        assert(count(Base\Datetime::local($timestamp)) === 9);

        // timeOfDay
        assert(count(Base\Datetime::timeOfDay()) === 4);

        // now
        assert(is_int(Base\Datetime::now()));

        // microtime
        assert(is_float(Base\Datetime::microtime()));

        // nowWithDecimal
        assert(count(Base\Datetime::nowWithDecimal()) === 2);

        // strtotime
        assert(Base\Datetime::strtotime('3 weeks from now') === null);
        assert(Base\Datetime::strtotime('+3 weeks') > Base\Datetime::time());
        assert(Base\Datetime::strtotime('-3 weeks') < Base\Datetime::time());
        assert(is_int(Base\Datetime::strtotime('tomorrow',Base\Datetime::make([2017]))));
        assert(Base\Datetime::sql(Base\Datetime::strtotime('tomorrow',Base\Datetime::make([2017]))) === '2017-01-02 00:00:00');

        // getLocaleFormat
        assert(Base\Datetime::getLocaleFormat('ymd') === '%G-%m-%d');

        // localeFormat
        assert(Base\Datetime::localeFormat('ymd',$timestamp) === '2017-12-01');
        assert(Base\Datetime::localeFormat('ymdhis',$timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::localeFormat('ym',$timestamp) === '2017-12');

        // gmtLocaleFormat
        assert(Base\Datetime::gmtLocaleFormat('ymdhis',$timestamp) === '2017-12-01 14:40:42');

        // getFormats
        assert(count(Base\Datetime::getFormats()) > 5);

        // getFormat
        assert(Base\Datetime::getFormat('sql') === 'Y-m-d H:i:s');
        assert(Base\Datetime::getFormat('sqlz') === 'sqlz');
        assert(Base\Datetime::getFormat('') === null);
        assert(Base\Datetime::getFormat(true) === 'F j, Y');
        assert(Base\Datetime::getFormat(0) === 'F j, Y');

        // getFormatReplace
        assert(Base\Datetime::getFormatReplace('sql') === ['format'=>'Y-m-d H:i:s','replace'=>null]);
        assert(count(Base\Datetime::getFormatReplace('j %m% Y')['replace']['%']) === 12);

        // setFormat
        Base\Datetime::setFormat('yearz','Y');
        assert(Base\Datetime::format('yearz',$timestamp) === '2017');

        // unsetFormat
        Base\Datetime::unsetFormat('yearz');
        assert(Base\Datetime::format('yearz') !== '2017');

        // parseFormat
        assert(Base\Datetime::parseFormat('ymd') === ['format'=>'Y-m-d','timezone'=>null,'replace'=>null]);
        assert(Base\Datetime::parseFormat(['ymd','America/New_York']) === ['format'=>'Y-m-d','timezone'=>'America/New_York','replace'=>null]);
        assert(Base\Datetime::parseFormat(['timezone'=>'America/New_York','format'=>'ymd']) === ['format'=>'Y-m-d','timezone'=>'America/New_York','replace'=>null]);
        assert(count(Base\Datetime::parseFormat('j %m% Y')['replace']['%']) === 12);
        assert(Base\Datetime::parseFormat(['format'=>'j %m% Y','replace'=>['%'=>[12=>'James']]])['replace']['%'][12] === 'James');

        // format
        assert(Base\Datetime::format('d-m-Y h:i:s','12-03-2017','d-m-Y') === '12-03-2017 12:00:00');
        assert(Base\Datetime::format('d-m-Y h:i:s',$timestamp) === '01-12-2017 09:40:42');
        assert(Base\Datetime::format('sql',$timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::format('ymdhis',$timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::format(['sql','Europe/Prague'],$timestamp) === '2017-12-01 15:40:42');
        assert(Base\Datetime::format(['sql','America/New_York'],$timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::format(['sql',true],$timestamp) === '2017-12-01 14:40:42');
        assert(Base\Datetime::format('j %m% Y',$timestamp) === '1 december 2017');
        assert(Base\Datetime::format(['j %m% Y','replace'=>['%'=>[12=>'James']]],$timestamp) === '1 James 2017');
        assert(Base\Datetime::format(0,$timestamp) === 'December 1, 2017');
        assert(Base\Datetime::format(true,$timestamp) === 'December 1, 2017');
        assert(Base\Datetime::format(1,$timestamp) === 'December 1, 2017 09:40:42');
        assert(Base\Datetime::format(2,$timestamp) === 'December 2017');
        assert(Base\Datetime::format(3,$timestamp) === '12-01-2017');
        assert(Base\Datetime::format(4,$timestamp) === '12-01-2017 09:40:42');

        // formatDayStart
        assert(Base\Datetime::formatDayStart(Base\Datetime::mk(2018,12,12,12,12,12)) === '2018-12-12 12:12:12');
        assert(Base\Datetime::formatDayStart(Base\Datetime::mk(2018,12,12)) === '2018-12-12');
        assert(Base\Datetime::formatDayStart(Base\Datetime::mk(2018,12,12,0,0,0)) === '2018-12-12');
        assert(Base\Datetime::formatDayStart(Base\Datetime::mk(2018,12,12,0,0,1)) === '2018-12-12 00:00:01');

        // formatStartEnd
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,12,12,12),Base\Datetime::mk(2018,12,14,12,12,14)) === '2018-12-12 12:12:12 - 2018-12-14 12:12:14');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,12,12,12),Base\Datetime::mk(2018,12,12,12,12,14)) === '2018-12-12 12:12:12 - 12:12:14');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,0),Base\Datetime::mk(2018,12,13,12,12,14)) === '2018-12-12 00:00:00 - 2018-12-13 12:12:14');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,12,12,12),Base\Datetime::mk(2018,12,12,12,12,12)) === '2018-12-12 12:12:12');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,0),Base\Datetime::mk(2018,12,12,0,0,0)) === '2018-12-12');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,0),Base\Datetime::mk(2018,12,12,0,0,0),['dayStart'=>false]) === '2018-12-12 00:00:00');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,0),Base\Datetime::mk(2018,12,13,0,0,0)) === '2018-12-12 - 2018-12-13');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,0),Base\Datetime::mk(2018,12,13,0,0,1)) === '2018-12-12 00:00:00 - 2018-12-13 00:00:01');
        assert(Base\Datetime::formatStartEnd(Base\Datetime::mk(2018,12,12,0,0,1),Base\Datetime::mk(2018,12,13,0,0,0)) === '2018-12-12 00:00:01 - 2018-12-13 00:00:00');

        // gmtFormat
        assert(Base\Datetime::gmtFormat('d-m-Y h:i:s',$timestamp) === '01-12-2017 02:40:42');
        assert(Base\Datetime::gmtFormat('ymdhis',$timestamp) === '2017-12-01 14:40:42');

        // formats
        assert(Base\Datetime::formats('ymd',[$timestamp,['year'=>2017]]) === ['2017-12-01','2017-01-01']);
        assert(Base\Datetime::formats(0,[$timestamp,['year'=>2017]]) === ['December 1, 2017','January 1, 2017']);

        // formatReplace
        assert(Base\Datetime::formatReplace('1 %2% 2017',['%'=>['2'=>'octobre']]) === '1 octobre 2017');

        // getLocale
        assert(Base\Datetime::getLocale() === 'en-US');

        // getPlaceholders
        assert(count(Base\Datetime::getPlaceholders('fr')) === 4);
        assert(count(Base\Datetime::getPlaceholders('en')) === 4);

        // placeholder
        assert(Base\Datetime::placeholder('dateToDay') === 'MM-DD-YYYY');

        // ymd
        assert(Base\Datetime::ymd($timestamp) === '2017-12-01');

        // ymdhis
        assert(Base\Datetime::ymdhis($timestamp) === '2017-12-01 09:40:42');

        // ym
        assert(Base\Datetime::ym($timestamp) === '2017-12');

        // his
        assert(Base\Datetime::his($timestamp) === '09:40:42');

        // rfc822
        assert(Base\Datetime::rfc822($timestamp) === 'Fri, 1 Dec 2017 09:40:42 -0500');

        // sql
        assert(Base\Datetime::sql($timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::sql($timestamp,true) === '2017-12-01 14:40:42');
        assert(Base\Datetime::sql($timestamp,'Europe/Prague') === '2017-12-01 15:40:42');

        // dateToSecond
        assert(Base\Datetime::dateToSecond($timestamp) === '12-01-2017 09:40:42');

        // compact
        assert(Base\Datetime::compact($timestamp) === '20171201094042');

        // gmt
        assert(Base\Datetime::gmt($timestamp) === 'Fri, 01 Dec 2017 14:40:42 GMT');

        // iformat
        assert(Base\Datetime::iformat('Y',$timestamp) === 2017);
        assert(Base\Datetime::iformat('year',$timestamp) === 2017);
        assert(Base\Datetime::iformat('Y-m-d',$timestamp) === null);
        assert(Base\Datetime::iformat(['h','Europe/Prague'],$timestamp) === 3);
        assert(Base\Datetime::iformat(['h','America/New_York'],$timestamp) === 9);

        // countDaysInMonth
        assert(Base\Datetime::countDaysInMonth($timestamp) === 31);
        assert(Base\Datetime::countDaysInMonth(['year'=>2018,'month'=>11]) === 30);
        assert(Base\Datetime::countDaysInMonth(['year'=>2018,'month'=>2]) === 28);
        assert(Base\Datetime::countDaysInMonth(Base\Datetime::make([2017,2,2,10,0,0])) === 28);
        assert(Base\Datetime::countDaysInMonth([2017,2,2,10,0,0]) === 28);

        // weekNo
        assert(Base\Datetime::weekNo($timestamp) === 48);

        // weekDay
        assert(Base\Datetime::weekDay($timestamp) === 5);

        // year
        assert(Base\Datetime::year($timestamp) === 2017);

        // month
        assert(Base\Datetime::month($timestamp) === 12);

        // day
        assert(Base\Datetime::day($timestamp) === 1);

        // hour
        assert(Base\Datetime::hour($timestamp) === 9);
        assert(Base\Datetime::hour($timestamp,true) === 14);
        assert(Base\Datetime::hour($timestamp,'Asia/Bangkok') === 21);

        // minute
        assert(Base\Datetime::minute($timestamp) === 40);

        // second
        assert(Base\Datetime::second($timestamp) === 42);

        // parse
        assert(Base\Datetime::parse('j %m% Y','1 december 2017') === ['year'=>2017,'month'=>12,'day'=>1]);
        assert(Base\Datetime::parse(['j %m% Y','replace'=>['%'=>[12=>'James']]],'1 James 2017') === ['year'=>2017,'month'=>12,'day'=>1]);
        assert(count(Base\Datetime::parse('sql','2017-12-01 09:40:42')) === 6);
        assert(Base\Datetime::parse('gmt','2017-12-01 09:40:42') === null);
        assert(count(Base\Datetime::parse('gmt','Fri, 01 Dec 2017 09:40:42 GMT')) === 6);

        // parseMake
        assert(Base\Datetime::parseMake('sql','2017-12-01 09:40:42') === $timestamp);
        assert(Base\Datetime::parseMake('gmt','Fri, 01 Dec 2017 09:40:42 GMT') === $timestamp);
        assert(Base\Datetime::parseMake(['sql','America/New_York'],'2017-12-01 09:40:42') === 1512139242);
        assert(Base\Datetime::parseMake(['sql','Europe/Prague'],'2017-12-01 09:40:42') === 1512117642);
        assert(Base\Datetime::format(['sql','Europe/Prague'],1512117642) === '2017-12-01 09:40:42');

        // parseStr
        assert(Base\Datetime::parseStr('2017-12-10 12:20 -1day')['day'] === 9);
        assert(count(Base\Datetime::parseStr('2017-12-10 12:20 -1week')) === 6);
        assert(count(Base\Datetime::parseStr('2017-12-10')) === 3);
        assert(Base\Datetime::sql(Base\Datetime::make(Base\Datetime::parseStr('2017-12-10 12:20 -1day'))) === '2017-12-09 12:20:00');

        // parseReplace
        assert(Base\Datetime::parseReplace('1 december 2017',['%'=>[12=>'december']]) === '1 %12% 2017');

        // parsePrepare
        assert(Base\Datetime::parsePrepare(['warning'=>0]) === []);

        // time
        assert(Base\Datetime::time($timestamp) === $timestamp);
        assert(Base\Datetime::make([2017,1]) === Base\Datetime::time(['year'=>2017,'month'=>1]));
        assert(Base\Datetime::time(['year'=>2017,'month'=>1,'day'=>1,'hour'=>1,'minute'=>1,'second'=>1],['timezone'=>true]) === 1483232461);
        assert(Base\Datetime::time(['year'=>2017,'month'=>1,'day'=>1,'hour'=>1,'minute'=>1,'second'=>1],[true,'Asia/Bangkok']) === 1483207261);
        assert(Base\Datetime::time() !== $timestamp);
        assert(Base\Datetime::time(2017,null,true) === 1483246800);
        assert(Base\Datetime::time(2017) === 2017);
        assert(Base\Datetime::time(null) === Base\Datetime::time());
        assert(Base\Datetime::time() === Base\Datetime::time());
        assert(Base\Datetime::time('2017-12-01','ymd') === 1512104400);
        assert(Base\Datetime::time(-1212212121121) === -1212212121121);
        assert(Base\Datetime::time('1 december 2017',['j %m% Y',null,['december'=>'12']]) === 1512104400);
        assert(Base\Datetime::time(0) === 0);
        assert(Base\Datetime::time(123,null,true) < 0);
        assert(Base\Datetime::time(123) === 123);
        assert(Base\Datetime::time('1483246800') === 1483246800);
        assert(Base\Datetime::time('20 décembre 2017',['format'=>'j %m% Y','lang'=>'en']) === null);

        // get
        assert(count(Base\Datetime::get($timestamp)) === 6);
        assert(Base\Datetime::get(1)['year'] === 1969);
        assert(Base\Datetime::isValid(Base\Datetime::get(1)));
        assert(Base\Datetime::make(Base\Datetime::get(1)) === 1);
        assert(Base\Datetime::make(Base\Datetime::get($timestamp)) === $timestamp);
        assert(Base\Datetime::get(['year'=>2017])['month'] === 1);
        assert(Base\Datetime::get('1483246802')['second'] === 2);
        assert(Base\Datetime::get($timestamp,'Europe/Prague')['hour'] === 15);
        assert(Base\Datetime::get($timestamp,true)['hour'] === 14);

        // keep
        assert(Base\Datetime::keep(2,$timestamp) === ['year'=>2017,'month'=>12]);
        assert(Base\Datetime::keep(4,$timestamp) === ['year'=>2017,'month'=>12,'day'=>1,'hour'=>9]);
        assert(Base\Datetime::keep(3,3720) === ['year'=>1969,'month'=>12,'day'=>31]);
        assert(Base\Datetime::keep(2,['year'=>0,'month'=>0,'day'=>14,'hour'=>10]) === ['day'=>14,'hour'=>10]);

        // str
        assert(Base\Datetime::str(6,['year'=>0,'month'=>0,'day'=>14,'hour'=>10]) === '14 days and 10 hours');
        assert(Base\Datetime::str(6,['year'=>12,'month'=>3,'day'=>14,'hour'=>10]) === '12 years 3 months 14 days and 10 hours');
        assert(Base\Datetime::str(1,['year'=>12,'month'=>3,'day'=>14,'hour'=>10]) === '12 years');

        // make
        assert(Base\Datetime::make([2017]) === 1483246800);
        assert(Base\Datetime::make([2017,2]) === 1485925200);
        assert(Base\Datetime::sql(Base\Datetime::make([2017])) === '2017-01-01 00:00:00');
        assert(Base\Datetime::make(['year'=>2017,'month'=>2]) === 1485925200);
        assert(Base\Datetime::make(['year'=>2017,'month'=>'2']) === 1485925200);
        assert(Base\Datetime::make([2017]) === Base\Datetime::make([2017,1,1]));
        assert(Base\Datetime::make([2017,0,0]) !== Base\Datetime::make([2017,1,1]));
        assert(Base\Datetime::make([2017,1,1,1,1,1],true) === 1483232461);
        assert(Base\Datetime::make([2017,1,1,1,1,1],'America/New_York') === 1483250461);

        // gmtMake
        assert(Base\Datetime::gmtMake([2017,2]) === 1485907200);
        assert(Base\Datetime::gmtMake([2017,1,1,1,1,1]) === 1483232461);

        // mk
        assert(Base\Datetime::mk(2017,1,1,1,1,1,true) === 1483232461);
        assert(Base\Datetime::mk(2017,1,1,1,1,1,'America/New_York') === 1483250461);

        // add
        assert(Base\Datetime::add(['year'=>1],$timestamp) === 1543675242);
        assert(Base\Datetime::sql(Base\Datetime::add(['year'=>1],$timestamp)) === '2018-12-01 09:40:42');
        assert(Base\Datetime::sql(Base\Datetime::add(['year'=>-1],$timestamp)) === '2016-12-01 09:40:42');
        assert(Base\Datetime::ymd(Base\Datetime::add(['year'=>1],'2017-08-23','ymd')) === '2018-08-23');
        assert(Base\Datetime::sql(Base\Datetime::add(['day'=>1],'2017-12-02 4:40:42',['sql','Europe/Prague']),'Europe/Prague') === '2017-12-03 04:40:42');

        // change
        assert(Base\Datetime::sql(Base\Datetime::change(['year'=>2010,'month'=>'4'],$timestamp)) === '2010-04-01 09:40:42');

        // remove
        assert(Base\Datetime::sql(Base\Datetime::remove(['year','month','day','hour','minute','second'],$timestamp)) === '2000-01-01 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::remove(['year','month','day','hour'],$timestamp)) === '2000-01-01 00:40:42');

        // getFloor
        assert(Base\Datetime::sql(Base\Datetime::getFloor('month',$timestamp)) === '2017-12-01 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::getFloor('minute',$timestamp)) === '2017-12-01 09:40:00');
        assert(Base\Datetime::sql(Base\Datetime::getFloor('year',['year'=>2017,'month'=>2])) === '2017-01-01 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::getFloor('day','2017-12-02 09:40:42','sql')) === '2017-12-02 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::getFloor('day','2017-12-02 09:40:42',['sql','Europe/Prague'])) === '2017-12-02 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::getFloor('day','2017-12-02 4:40:42',['sql','Europe/Prague'])) === '2017-12-01 00:00:00');

        // getCeil
        assert(Base\Datetime::sql(Base\Datetime::getCeil('day',[2017,2,1])) === '2017-02-01 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::getCeil('month',[2017,2,1])) === '2017-02-28 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::getCeil('year',[2017,2,1])) === '2017-12-31 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::getCeil('hour',[2017,2,1,5])) === '2017-02-01 05:59:59');
        assert(Base\Datetime::sql(Base\Datetime::getCeil('minute',[2017,2,1,0,0])) === '2017-02-01 00:00:59');

        // getFloorCeil
        assert(count(Base\Datetime::getFloorCeil('day',[2017,2,1,5])) === 2);

        // floor
        assert(Base\Datetime::sql(Base\Datetime::floor('month',$timestamp)) === '2017-12-01 00:00:00');

        // ceil
        assert(Base\Datetime::sql(Base\Datetime::ceil('day',Base\Datetime::mk(2017,1,1,0,0,0))) === '2017-01-01 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('month',Base\Datetime::make([2017,1,1,0,0,0]))) === '2017-01-31 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('year',Base\Datetime::make([2017,1,1,0,0,0]))) === '2017-12-31 23:59:59');
        assert(Base\Datetime::sql($timestamp) === '2017-12-01 09:40:42');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day',$timestamp)) === '2017-12-01 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('minute',$timestamp)) === '2017-12-01 09:40:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day',Base\Datetime::make([2017]))) === '2017-01-01 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day',Base\Datetime::make([2017,1,1,0,0,0]))) === '2017-01-01 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day',Base\Datetime::mk(2017,1,1,0,0,1))) === '2017-01-01 23:59:59');
        assert(Base\Datetime::ceil('day',Base\Datetime::make([2017])) !== Base\Datetime::ceil('month',2017));
        assert(Base\Datetime::sql(Base\Datetime::ceil('year',['year'=>2017,'month'=>2])) === '2017-12-31 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day','2017-12-02 09:40:42',['sql','Europe/Prague'])) === '2017-12-02 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceil('day','2017-12-03 5:40:42',['sql','Europe/Prague'])) === '2017-12-02 23:59:59');

        // floorCeil
        assert(count(Base\Datetime::floorCeil('day',[2017,2,1,5])) === 2);

        // addYear
        assert(Base\Datetime::sql(Base\Datetime::addYear(1,$timestamp)) === '2018-12-01 09:40:42');

        // changeYear
        assert(Base\Datetime::changeYear(2010,$timestamp) === 1291214442);
        assert(Base\Datetime::sql(Base\Datetime::changeYear(2010,$timestamp)) === '2010-12-01 09:40:42');

        // removeYear
        assert(Base\Datetime::sql(Base\Datetime::removeYear($timestamp)) === '2000-12-01 09:40:42');

        // floorYear
        assert(Base\Datetime::sql(Base\Datetime::floorYear($timestamp)) === '2017-01-01 00:00:00');

        // ceilYear
        assert(Base\Datetime::sql(Base\Datetime::ceilYear(['year'=>2017,'month'=>1])) === '2017-12-31 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceilYear(['year'=>2017,'month'=>2])) === '2017-12-31 23:59:59');

        // floorCeilYear
        assert(count(Base\Datetime::floorCeilYear($timestamp)) === 2);

        // addMonth
        assert(Base\Datetime::sql(Base\Datetime::addMonth(2,$timestamp)) === '2018-02-01 09:40:42');

        // changeMonth
        assert(Base\Datetime::sql(Base\Datetime::changeMonth(9,$timestamp)) === '2017-09-01 09:40:42');
        assert(Base\Datetime::sql(Base\Datetime::changeMonth(24,$timestamp)) === '2018-12-01 09:40:42');

        // removeMonth
        assert(Base\Datetime::sql(Base\Datetime::removeMonth($timestamp)) === '2017-01-01 09:40:42');

        // floorMonth
        assert(Base\Datetime::sql(Base\Datetime::floorMonth($timestamp)) === '2017-12-01 00:00:00');

        // ceilMonth
        assert(Base\Datetime::sql(Base\Datetime::ceilMonth(['year'=>2017,'month'=>1,'hour'=>0])) === '2017-01-31 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceilMonth(['year'=>2017,'month'=>1,'second'=>1])) === '2017-01-31 23:59:59');

        // floorCeilMonth
        assert(count(Base\Datetime::floorCeilMonth($timestamp)) === 2);

        // addDay
        assert(Base\Datetime::sql(Base\Datetime::addDay(32,$timestamp)) === '2018-01-02 09:40:42');
        assert(Base\Datetime::addDay(1) > Base\Datetime::now());

        // changeDay
        assert(Base\Datetime::sql(Base\Datetime::changeDay(13,$timestamp)) === '2017-12-13 09:40:42');
        assert(Base\Datetime::sql(Base\Datetime::changeDay(-1,$timestamp)) === '2017-11-29 09:40:42');
        assert(Base\Datetime::ymd(Base\Datetime::changeDay(3,'2017-08-23','ymd')) === '2017-08-03');
        assert(Base\Datetime::ymd(Base\Datetime::changeDay(33,'2017-08-23','ymd')) === '2017-09-02');
        assert(Base\Datetime::get(Base\Datetime::changeDay(1))['day'] === 1);

        // removeDay
        assert(Base\Datetime::sql(Base\Datetime::removeDay($timestamp)) === '2017-12-01 09:40:42');

        // floorDay
        assert(Base\Datetime::sql(Base\Datetime::floorDay($timestamp)) === '2017-12-01 00:00:00');
        assert(Base\Datetime::sql(Base\Datetime::floorDay('2017-12-04 4:00:00','sql')) === '2017-12-04 00:00:00');

        // ceilDay
        assert(Base\Datetime::sql(Base\Datetime::ceilDay(['year'=>2017,'month'=>1,'day'=>2,'hour'=>0])) === '2017-01-02 23:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceilDay(['year'=>2017,'month'=>1,'day'=>2,'second'=>1])) === '2017-01-02 23:59:59');

        // floorCeilDay
        assert(count(Base\Datetime::floorCeilDay($timestamp)) === 2);

        // addHour
        assert(Base\Datetime::sql(Base\Datetime::addHour(25,$timestamp)) === '2017-12-02 10:40:42');

        // changeHour
        assert(Base\Datetime::sql(Base\Datetime::changeHour(22,$timestamp)) === '2017-12-01 22:40:42');
        assert(Base\Datetime::sql(Base\Datetime::changeHour(25,$timestamp)) === '2017-12-02 01:40:42');

        // removeHour
        assert(Base\Datetime::sql(Base\Datetime::removeHour($timestamp)) === '2017-12-01 00:40:42');

        // floorHour
        assert(Base\Datetime::sql(Base\Datetime::floorHour($timestamp)) === '2017-12-01 09:00:00');

        // ceilHour
        assert(Base\Datetime::sql(Base\Datetime::ceilHour(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1])) === '2017-01-02 01:59:59');
        assert(Base\Datetime::sql(Base\Datetime::ceilHour(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1,'second'=>1])) === '2017-01-02 01:59:59');

        // floorCeilHour
        assert(count(Base\Datetime::floorCeilHour($timestamp)) === 2);

        // addMinute
        assert(Base\Datetime::sql(Base\Datetime::addMinute(61,$timestamp)) === '2017-12-01 10:41:42');

        // changeMinute
        assert(Base\Datetime::sql(Base\Datetime::changeMinute(45,$timestamp)) === '2017-12-01 09:45:42');
        assert(Base\Datetime::sql(Base\Datetime::changeMinute(-45,$timestamp)) === '2017-12-01 08:15:42');

        // removeMinute
        assert(Base\Datetime::sql(Base\Datetime::removeMinute($timestamp)) === '2017-12-01 09:00:42');

        // floorMinute
        assert(Base\Datetime::sql(Base\Datetime::floorMinute($timestamp)) === '2017-12-01 09:40:00');

        // ceilMinute
        assert(Base\Datetime::sql(Base\Datetime::ceilMinute(['year'=>2017,'month'=>1,'day'=>2,'minute'=>1])) === '2017-01-02 00:01:59');
        assert(Base\Datetime::sql(Base\Datetime::ceilMinute(['year'=>2017,'month'=>1,'day'=>2,'hour'=>1,'minute'=>1,'second'=>1])) === '2017-01-02 01:01:59');

        // floorCeilMinute
        assert(count(Base\Datetime::floorCeilMinute($timestamp)) === 2);

        // addSecond
        assert(Base\Datetime::sql(Base\Datetime::addSecond(62,$timestamp)) === '2017-12-01 09:41:44');

        // changeSecond
        assert(Base\Datetime::sql(Base\Datetime::changeSecond(55,$timestamp)) === '2017-12-01 09:40:55');
        assert(Base\Datetime::sql(Base\Datetime::changeSecond(3600,$timestamp)) === '2017-12-01 10:40:00');

        // removeSecond
        assert(Base\Datetime::sql(Base\Datetime::removeSecond($timestamp)) === '2017-12-01 09:40:00');

        // diff
        assert(Base\Datetime::diff('2017-12-01 09:40:00','2017-12-01 09:40:00',['sql','timezone'=>true],['sql','timezone'=>'Asia/Bangkok'])['hour'] === 7);
        assert(Base\Datetime::diff(7201)['hour'] === 2);
        assert(count(Base\Datetime::diff($timestamp)) === 6);
        assert(Base\Datetime::diff($timestamp,($timestamp - 9002))['hour'] === 2);
        assert(Base\Datetime::diff(200,400)['minute'] === 3);
        assert(Base\Datetime::diff(400,200)['minute'] === 3);

        // diffTotal
        assert(Base\Datetime::diffTotal(Base\Datetime::make([2016,1,1]),Base\Datetime::make([2019,4,28,12,7,54]),false) === ['year'=>3,'month'=>39,'day'=>1213,'hour'=>29123,'minute'=>1747387,'second'=>104843274]);
        assert(Base\Datetime::diffTotal(Base\Datetime::make([2016,1,1]),Base\Datetime::make([2019,4,28,12,7,54]),true)['year'] === 4);
        assert(Base\Datetime::diffTotal('2017-12-01 09:40:00','2017-12-01 09:40:00',true,['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok'])['hour'] === 13);

        // diffKeep
        assert(Base\Datetime::diffKeep(4,Base\Datetime::mk(2018,1,2),Base\Datetime::mk(2021,3,4)) === ['year'=>3,'month'=>2,'day'=>2]);

        // diffStr
        assert(Base\Datetime::diffStr(4,Base\Datetime::mk(2018,1,2),Base\Datetime::mk(2021,3,4)) === '3 years 2 months and 2 days');

        // ago
        assert(Base\Datetime::ago(Base\Datetime::mk(2120,1,1)) === null);
        assert(count(Base\Datetime::ago(Base\Datetime::mk(2014,1,1))) === 6);
        assert(!empty(Base\Datetime::ago(Base\Datetime::mk(2012,8,2),null,4)));

        // agoStr
        assert(Base\Datetime::agoStr(5,time()) === '');
        assert(strlen(Base\Datetime::agoStr(2,Base\Datetime::mk(2010,1,9))) > 15);
        assert(Base\Datetime::agoStr(5,Base\Datetime::mk(2040,1,9)) === '');

        // diffNow
        assert(count(Base\Datetime::diffNow(Base\Datetime::make([2016,1,1]))) === 6);
        assert(Base\Datetime::diffNow('2017-12-01 09:40:00',['sql',true]) !== Base\Datetime::diffNow('2017-12-01 09:40:00',['sql','Asia/Bangkok']));
        assert(count(Base\Datetime::diffNow(Base\Datetime::mk(2020,1,1))) === 6);
        assert(!empty(Base\Datetime::diffNow(Base\Datetime::mk(2018,4,2),null,3)));

        // diffNowStr
        assert(strlen(Base\Datetime::diffNowStr(4,Base\Datetime::mk(2018,1,7))) > 10);

        // amount
        assert(Base\Datetime::amount(4)['second'] === 4);
        assert(Base\Datetime::amount(61)['minute'] === 1);
        assert(count(Base\Datetime::amount(4321132123)) === 6);
        assert(Base\Datetime::amount(4321132123,2) === ['year'=>136,'month'=>11]);

        // amountStr
        assert(Base\Datetime::amountStr(2,4) === '4 seconds');
        assert(Base\Datetime::amountStr(2,61) === '1 minute and 1 second');
        assert(Base\Datetime::amountStr(1,61) === '1 minute');

        // calendar
        $calendar = Base\Datetime::calendar([2018,6],true,false);
        assert(Base\Arrs::is($calendar));
        assert(count(Base\Datetime::calendar([2018,12],true,false)) === 6);
        assert(count(Base\Datetime::calendar([2018,12],true,true)) === 6);
        assert(count(Base\Datetime::calendar([2010,1],false,true)) === 5);
        assert(count(Base\Datetime::calendar([2010,1],true,true)) === 6);

        // fillCalendar
        assert(Base\Datetime::fillCalendar($calendar) !== $calendar);

        // daysDiff
        assert(Base\Datetime::daysDiff(10,20) === 0);
        assert(Base\Datetime::daysDiff($timestamp,$timestamp) === 0);
        assert(Base\Datetime::daysDiff(null,null) === 0);
        assert(Base\Datetime::daysDiff(Base\Datetime::make([2018,1,1]),Base\Datetime::make([2018,1,1])) === 0);
        assert(Base\Datetime::daysDiff([2015,1,1],[2016,1,1]) === 365);
        assert(Base\Datetime::daysDiff([2016,1,2],[2015,1,1]) === 366);
        assert(Base\Datetime::daysDiff(Base\Datetime::make([2015]),Base\Datetime::make([2016,null,null,0,0,1]),null) === 365);
        assert(Base\Datetime::daysDiff(Base\Datetime::make([2015]),Base\Datetime::make([2015]),Base\Datetime::make([2016,null,null,0,0,1]),false) === null);
        assert(Base\Datetime::daysDiff(Base\Datetime::make([2015]),Base\Datetime::make([2016,1,2,0,0,0])) === 366);
        assert(Base\Datetime::daysDiff([2016,1,3],[2016,1,5]) === 2);
        assert(Base\Datetime::daysDiff([2016,1,5],[2016,1,3]) === 2);
        assert(Base\Datetime::daysDiff('2017-12-01 11:40:00','2017-12-01 10:40:00',['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 1);
        assert(Base\Datetime::daysDiff('2017-12-01 11:40:00','2017-12-01 12:40:00',['sql','timezone'=>'America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 0);

        // monthsDiff
        assert(Base\Datetime::monthsDiff(Base\Datetime::make([2016]),Base\Datetime::make([2017])) === 12);
        assert(Base\Datetime::monthsDiff($timestamp,Base\Datetime::make([2019])) === 13);
        assert(Base\Datetime::monthsDiff(Base\Datetime::make([2016]),Base\Datetime::make([2017,null,null,1])) === 12);
        assert(Base\Datetime::monthsDiff(null,null) === 0);
        assert(Base\Datetime::monthsDiff(Base\Datetime::make([2018,1,5]),Base\Datetime::make([2018,1,5])) === 0);
        assert(Base\Datetime::monthsDiff(Base\Datetime::make([2018,1,3]),Base\Datetime::make([2018,1,6])) === 0);
        assert(Base\Datetime::monthsDiff('2017-01-01 00:00:00','2017-01-01 00:00:01','sql','sql') === 0);
        assert(Base\Datetime::monthsDiff('2017-01-01 00:00:00','2017-03-03 00:00:01','sql','sql') === 2);
        assert(Base\Datetime::monthsDiff('2017-01-01 00:00:00','2017-01-01 00:00:00',['sql','America/New_York'],['sql','Asia/Bangkok']) === 1);

        // yearsDiff
        assert(Base\Datetime::yearsDiff(Base\Datetime::make([2016]),Base\Datetime::make([2018])) === 2);
        assert(Base\Datetime::yearsDiff(Base\Datetime::make([2016]),Base\Datetime::make([2018,2])) === 2);
        assert(Base\Datetime::yearsDiff(Base\Datetime::make([2016]),Base\Datetime::make([2016])) === 0);
        assert(Base\Datetime::yearsDiff(null,null) === 0);
        assert(Base\Datetime::yearsDiff('2017-01-01 00:00:00','2017-01-01 00:00:00','sql','sql') === 0);
        assert(Base\Datetime::yearsDiff('2017-12-31 23:40:00','2018-1-1 03:40:00',['sql','America/New_York'],['sql','timezone'=>'Asia/Bangkok']) === 0);
        assert(Base\Datetime::yearsDiff('2017-12-31 23:40:00','2018-1-1 03:40:00','sql','sql') === 1);

        // days
        assert(Base\Datetime::days(Base\Datetime::make([2016]),Base\Datetime::make([2017]),20,'sql')[1451624400] === '2016-01-01 00:00:00');
        assert(count(Base\Datetime::days(Base\Datetime::make([2016]),Base\Datetime::make([2017,null,null,0,0,1]),20)) === 19);
        assert(count(Base\Datetime::days(Base\Datetime::make([2015]),Base\Datetime::make([2016]))) === count(Base\Datetime::days(Base\Datetime::make([2016]),Base\Datetime::make([2015]))));
        assert(Base\Datetime::days(Base\Datetime::mk(2017,1,2),Base\Datetime::mk(2017,1,2)) === [1=>1483333200]);
        assert(current(Base\Datetime::days(Base\Datetime::mk(2017,1,1),Base\Datetime::mk(2017,1,4),null,'sql')) === '2017-01-01 00:00:00');
        assert(current(Base\Datetime::days(Base\Datetime::mk(2017,1,4),Base\Datetime::mk(2017,1,1),null,'sql')) === '2017-01-04 00:00:00');
        assert(current(Base\Datetime::days(Base\Datetime::mk(2017,1,2,12),Base\Datetime::mk(2017,1,4),null,'sql',false)) === '2017-01-02 12:00:00');
        assert(current(Base\Datetime::days(Base\Datetime::mk(2017,1,2,12),Base\Datetime::mk(2017,1,4),null,'sql',true)) === '2017-01-02 00:00:00');

        // secondsInDay
        assert(Base\Datetime::secondsInDay('2016-01-01 00:00:00','sql') === 0);
        assert(Base\Datetime::secondsInDay('2016-01-01 00:00:23','sql') === 23);
        assert(Base\Datetime::secondsInDay('2016-01-01 00:10:23','sql') === 623);
        assert(Base\Datetime::secondsInDay('2016-01-01 20:10:23','sql') === 72623);

        // daysInMonth
        assert(count(Base\Datetime::daysInMonth()) >= 28);
        assert(count(Base\Datetime::daysInMonth([2017,02,20],20,'sql')) === 2);
        assert(Base\Datetime::daysInMonth([2017,02,20],1,'sql')[1485925200] === '2017-02-01 00:00:00');
        assert(count(Base\Datetime::daysInMonth([2010,1])) === 31);

        // daysDefault
        assert(count(Base\Datetime::daysDefault()) === 31);

        // months
        assert(current(Base\Datetime::months(Base\Datetime::mk(2017,4,2),Base\Datetime::mk(2017,4,2),null,'sql')) === '2017-04-01 00:00:00');
        assert(count(Base\Datetime::months(Base\Datetime::make([2016]),Base\Datetime::make([2017]),1,'sql')) === 13);
        assert(Base\Datetime::months(Base\Datetime::make([2016]),Base\Datetime::make([2017]),1,'sql')[1451624400] === '2016-01-01 00:00:00');
        assert(current(Base\Datetime::months(Base\Datetime::mk(2017,2,3),Base\Datetime::mk(2017,4,2),null,'sql',false)) === '2017-02-03 00:00:00');
        assert(count(Base\Datetime::months(Base\Datetime::mk(2017,2,3),Base\Datetime::mk(2017,4,2),null,'sql')) === 3);
        assert(current(Base\Datetime::months(Base\Datetime::mk(2017,4,2),Base\Datetime::mk(2017,2,3),null,'sql',false)) === '2017-04-02 00:00:00');
        assert(Base\Arr::index(0,Base\Datetime::months(2018,2017,1,'sql')) === Base\Arr::valueLast(Base\Datetime::months(2017,2018,1,'sql')));

        // monthsInYear
        assert(count(Base\Datetime::monthsInYear(2018,1,'sql')) === 12);
        assert(count(Base\Datetime::monthsInYear(2018,2,'sql')) === 6);
        assert(Base\Datetime::monthsInYear(2018,2,'sql')[1514782800] === '2018-01-01 00:00:00');
        assert(count(Base\Datetime::monthsInYear(null,1,'sql')) === 12);

        // monthsDefault
        assert(count(Base\Datetime::monthsDefault()) === 12);

        // years
        assert(count(Base\Datetime::years(Base\Datetime::make([1984]),Base\Datetime::make([1994]))) === 11);
        assert(Base\Datetime::years(Base\Datetime::make([1984]),Base\Datetime::make([1994]),1,'sql')[441781200] === '1984-01-01 00:00:00');
        assert(Base\Datetime::years(Base\Datetime::make([1994]),Base\Datetime::make([1992]),null,'sql') !== Base\Datetime::years(Base\Datetime::make([1992]),Base\Datetime::make([1994]),null,'sql'));
        assert(Base\Datetime::years([1994,03,03],1992,null,'sql',false) !== Base\Datetime::years([1994,03,03],1992,null,'sql'));

        // yearsDefault
        assert(count(Base\Datetime::yearsDefault(-100,0,null,'sql')) === 101);
        assert(count(Base\Datetime::yearsDefault()) === 131);

        return true;
    }
}
?>