<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// num
// class for testing Quid\Base\Num
class Num extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // typecast
        $a = '23.2';
        $b = 'string';
        Base\Num::typecast($a,$b);
        assert($a === 23.2);
        assert($b === 'string');

        // cast
        assert(Base\Num::cast('000000') === '000000');
        assert(Base\Num::cast('000000',false) === '000000');
        assert(Base\Num::cast('000111',false) === '000111');
        assert('1.22323321213321222' === Base\Num::cast('1.22323321213321222'));
        assert((float) '1.22323321213321222' === Base\Num::cast('1.22323321213321222',true));
        assert(null === Base\Num::cast([]));
        assert((int) '2' === Base\Num::cast('2'));
        assert((int) true === Base\Num::cast(true));
        assert((int) false === Base\Num::cast(false));
        assert(0 === Base\Num::cast(false));
        assert(2 === Base\Num::cast(2.0));
        assert('22323321213321222' === Base\Num::cast('22323321213321222'));
        assert(22323321213321222 === Base\Num::cast(22323321213321222));
        assert((float) '2.1' === Base\Num::cast('2.1',true));
        assert((float) '2.1' === Base\Num::cast(2.1));
        assert('0.1' === Base\Num::cast('0.1'));
        assert('-0.1' === Base\Num::cast('-0.1'));
        assert((float) '-0.1' === Base\Num::cast(-0.1));
        assert('-0,1' === Base\Num::cast('-0,1'));
        assert((int) 0 === Base\Num::cast('0'));
        assert((int) 0 === Base\Num::cast(0));
        assert('aaaa0' === Base\Num::cast('aaaa0'));
        assert('1aaaaa1' === Base\Num::cast('1aaaaa1'));
        assert('-0,50$' === Base\Num::cast('-0,50$'));
        assert('-1,50$' === Base\Num::cast('-1,50$'));
        assert(1 === Base\Num::cast(true));
        assert(0 === Base\Num::cast(false));
        assert(null === Base\Num::cast(null));
        assert(Base\Num::cast('30MB') === '30MB');
        assert(Base\Num::cast('0.02316447') === '0.02316447');
        assert(Base\Num::cast('0.02316447',false) === '0.02316447');
        assert(Base\Num::cast('0.02316447',true) === 0.02316447);
        assert(Base\Num::cast('0.100000200000',true) === 0.1000002);
        assert(Base\Num::cast('0.10000000000000200000000',true) === 0.100000000000002);
        assert(Base\Num::cast('0.10000000000000200000000',false) === '0.100000000000002');
        assert(Base\Num::cast('0.77207400',true) === 0.772074);

        // castMore
        assert(Base\Num::castMore('000000') === 0);
        assert(Base\Num::castMore('000111') === 111);
        assert((float) '0.1' === Base\Num::castMore('0.1'));
        assert((float) '-0.1' === Base\Num::castMore('-0.1'));
        assert((float) '-0.1' === Base\Num::castMore('-0.1'));
        assert(-0.1 === Base\Num::castMore('-0,1'));
        assert(Base\Num::castMore('0.02316447') === 0.02316447);

        // is
        assert(Base\Num::is('2'));
        assert(!Base\Num::is(true));

        // isEmpty
        assert(Base\Num::isEmpty(0));
        assert(!Base\Num::isEmpty(1));

        // isNotEmpty
        assert(Base\Num::isNotEmpty(1));
        assert(!Base\Num::isNotEmpty(0));

        // isString
        assert(Base\Num::isString('1'));
        assert(!Base\Num::isString(2));

        // isFinite
        assert(!Base\Num::isFinite(log(0)));
        assert(Base\Num::isFinite('2'));
        assert(Base\Num::isFinite(Base\Num::pi()));

        // isInfinite
        assert(Base\Num::isInfinite(INF));
        assert(!Base\Num::isInfinite('2'));
        assert(!Base\Num::isInfinite(Base\Num::pi()));

        // isNan
        assert(Base\Num::isNan(acos(8)));
        assert(Base\Num::isNan(NAN));
        assert(!Base\Num::isNan('2'));
        assert(!Base\Num::isNan(Base\Num::pi()));

        // isPositive
        assert(Base\Num::isPositive('2'));
        assert(!Base\Num::isPositive(0));
        assert(Base\Num::isPositive(0,true));
        assert(!Base\Num::isPositive(-2));
        assert(!Base\Num::isPositive('test'));

        // isNegative
        assert(!Base\Num::isNegative(0));
        assert(Base\Num::isNegative('0',true));
        assert(!Base\Num::isNegative('2'));
        assert(Base\Num::isNegative(-2));
        assert(!Base\Num::isNegative('test'));

        // isOdd
        assert(Base\Num::isOdd('1'));
        assert(Base\Num::isOdd(3));
        assert(Base\Num::isOdd(3.0));
        assert(!Base\Num::isOdd(3.1));
        assert(!Base\Num::isOdd(-2));
        assert(!Base\Num::isOdd(0));

        // isEven
        assert(!Base\Num::isEven('1'));
        assert(!Base\Num::isEven(3));
        assert(Base\Num::isEven(-2));
        assert(Base\Num::isEven(4));
        assert(Base\Num::isEven(0));

        // isOverflow
        assert(Base\Num::isOverflow('123321123312231312213123312312312312'));

        // isLength
        assert(Base\Num::isLength(3,123));
        assert(Base\Num::isLength(5,123.2));
        assert(!Base\Num::isLength(5,'test'));
        assert(Base\Num::isLength(2,'12'));

        // isMinLength
        assert(Base\Num::isMinLength(3,123));

        // isMaxLength
        assert(Base\Num::isMaxLength(3,123));

        // isCountable
        assert(!Base\Num::isCountable(1234));
        assert(Base\Num::isCountable([1,2,3]));

        // append
        assert(Base\Num::append('23',4.2,4) === 234.24);
        assert(Base\Num::append('23',4.2,2.4) === '234.22.4');

        // commaToDecimal
        assert(1.1 === Base\Num::commaToDecimal(1.1));
        assert(2.1 === Base\Num::commaToDecimal('2,1'));
        assert('2.1.2' === Base\Num::commaToDecimal('2,1,2'));

        // len
        assert(3 === Base\Num::len('2.5'));
        assert(4 === Base\Num::len(2033));
        assert(6 === Base\Num::len(2033.5));

        // sub
        assert(33 === Base\Num::sub(2,2,2033));
        assert(3 === Base\Num::sub(-1,null,2033));
        assert(3.21 === Base\Num::sub(-4,null,2033.21));
        assert(2033.21 === Base\Num::sub(0,20,2033.21));

        // decimal
        assert('2.55' === Base\Num::decimal('2.5534',2));
        assert(Base\Num::decimal('2.55340000',8) === '2.55340000');
        assert(Base\Num::decimal('2.55340000',8,true) === '2.5534');

        // round
        assert(3 === Base\Num::round('2.5',0));
        assert(2 === Base\Num::round('2.5',0,PHP_ROUND_HALF_DOWN));
        assert(2.6 === Base\Num::round('2.55',1));
        assert(2.6 === Base\Num::round(2.55,1));
        assert(2 === Base\Num::round(2,1));

        // roundLower
        assert(2 === Base\Num::roundLower('2.5',0));

        // ceil
        assert(3 === Base\Num::ceil('2.5'));
        assert(3 === Base\Num::ceil(2.2));
        assert(0 === Base\Num::ceil('bla'));

        // floor
        assert(2 === Base\Num::floor('2.5'));
        assert(2 === Base\Num::floor(2.2));
        assert(0 === Base\Num::floor('bla'));

        // positive
        assert(2 === Base\Num::positive('-2'));
        assert(2 === Base\Num::positive('-2a'));
        assert(abs('a-2a') === Base\Num::positive('a-2a'));
        assert(0 === Base\Num::positive('a-2a'));
        assert(2.5 === Base\Num::positive('-2.5'));

        // negative
        assert(-2 === Base\Num::negative('2'));
        assert(-2.5 === Base\Num::negative(2.5));
        assert(-2 === Base\Num::negative('-2a'));
        assert(abs('-2a') * -1 === Base\Num::negative('-2a'));

        // invert
        assert(-2 === Base\Num::invert('2'));
        assert(0 === Base\Num::invert(0));
        assert(0 === Base\Num::invert('test'));
        assert(0 === Base\Num::invert('0'));
        assert(2.5 === Base\Num::invert(-2.5));

        // increment
        assert(2 === Base\Num::increment(1,1));
        assert(2.2 === Base\Num::increment(2,0.2));
        assert(2.5 === Base\Num::increment(2.3,'0.2'));
        assert(3 === Base\Num::increment(2,1));
        assert(Base\Num::increment('2',-4) === -2);
        assert(6.1 === Base\Num::increment('2',4.1));
        assert((string) -2.1 === (string) Base\Num::increment('2',-4.1));

        // decrement
        assert(-1 === Base\Num::decrement(1,2));
        assert(2.9 === Base\Num::decrement('3',0.1));

        // in
        assert(Base\Num::in(1,2,3));
        assert(Base\Num::in(1,3,3));
        assert(Base\Num::in(1,1,3));
        assert(!Base\Num::in(1,4,3));

        // inExclusive
        assert(Base\Num::inExclusive(1,3,4));
        assert(!Base\Num::inExclusive(1,4,3));

        // pi
        assert(Base\Num::pi(2) === 3.14);
        assert(Base\Num::pi(4) === 3.1416);

        // math
        assert(1 === Base\Num::math('+',[1]));
        assert(3 === Base\Num::math('+',[2,1]));
        assert(1 === Base\Num::math('-',[2,1]));
        assert(4 === Base\Num::math('*',[2,2]));
        assert(null === Base\Num::math('*',[2]));
        assert(8 === Base\Num::math('**',[2,3]));
        assert(1 === Base\Num::math('%',[5,2]));
        assert(1 === Base\Num::math('avg',[1,1]));
        assert(1 === Base\Num::math('/',[2,2]));
        assert(5 === Base\Num::math('avg',[2,4,6,8]));
        assert(5 === Base\Num::math('average',[2,4,6,8]));
        assert(6.044 === Base\Num::math('avg',[2,4,6,8,10.22]));
        assert(6.04 === Base\Num::math('avg',[2,4,6,8,10.22],2));
        assert(6 === Base\Num::math('avg',[2,4,6,8,10.22],0));
        assert(!Base\Num::math('/',[2,0]));
        assert(Base\Num::math('max',[2,0]) === 2);
        assert(Base\Num::math('min',[2,0]) === 0);
        assert(Base\Num::math('>',[4,3]) === 4);
        assert(Base\Num::math('<',[4,1]) === 1);

        // combine
        assert(Base\Num::combine('+',[1,2],[3,4],[1,2,3]) === [5,8,3]);
        assert(Base\Num::combine('*',[1,2],[3,4],[1,2,3],['test',[]]) === [3,16,3]);
        assert(Base\Num::combine('/',[1,0],[0,0],[0,2,3]) === [null,0,3]);
        assert(Base\Num::combine('>',[1,0],[3,0],[0,2,3]) === [3,2,3]);

        // addition
        assert(Base\Num::addition(2) === 2);
        assert(3 === Base\Num::addition(2,1));
        assert(is_float(Base\Num::addition('121221212121121212121212',1)));
        assert(6 === Base\Num::addition(2,1,3));
        assert((string) 6.3 === (string) Base\Num::addition(2,'1.1',3.2));  // pourquoi ?

        // subtraction
        assert(Base\Num::subtraction(2) === 2);
        assert(1 === Base\Num::subtraction(2,1));
        assert(-19 === Base\Num::subtraction(2,1,20));
        assert(-19 === Base\Num::subtraction(2.0,'1','20'));

        // multiplication
        assert(4.4 === Base\Num::multiplication(2,2.2));
        assert(8 === Base\Num::multiplication(2,2,2));

        // pow
        assert(4 === Base\Num::pow(2,2));
        assert(2 === Base\Num::pow('2','1'));
        assert(4 === Base\Num::pow('2','2'));
        assert(1 === Base\Num::pow('2',0));
        assert(4.41 === Base\Num::pow('2.1',2));
        assert((string) 5.1154335606641 === (string) Base\Num::pow('2.1','2.2'));

        // division
        assert(1 === Base\Num::division(2,2));
        assert(0.01 === Base\Num::division(2,2,100));
        assert(0.01 === Base\Num::division(2,'2',100.0));
        assert(null === Base\Num::division(2,2,0,2));

        // modulo
        assert(1 === Base\Num::modulo(5,2));
        assert(0 === Base\Num::modulo(9,3));

        // average
        assert(0 === Base\Num::average(0,0,0));
        assert(55 === Base\Num::average(100,10));
        assert(57.5 === Base\Num::average(100,15));
        assert(65.4 === Base\Num::average(100,15,60,32,120));

        // min
        assert(Base\Num::min(1,'2','3.4','james') === 1);

        // max
        assert(Base\Num::max(1,'2','3.4',[],'bla','z') === 3.4);

        // random
        assert(1 === Base\Num::random(1,1,1));
        assert(1 === Base\Num::random(10,1,1));
        assert(1 === Base\Num::random(10,1,1,true));
        assert(1 === Base\Num::random(10,1,1,false));

        // zerofill
        assert(Base\Num::zerofill(5,123) === '00123');
        assert(Base\Num::zerofill(2,123) === '123');

        // fromOctal
        assert(Base\Num::fromOctal('100775') === 33277);
        assert(Base\Num::fromOctal(100775) === 33277);
        assert(Base\Num::fromOctal(775) === 509);
        assert(0775 === 509);

        // toOctal
        assert(Base\Num::toOctal(33277) === '100775');
        assert(Base\Num::toOctal(33277,2) === 775);
        assert(Base\Num::toOctal(509) === '775');
        assert(Base\Num::toOctal(509,true) === 775);

        // format
        assert('2.00' === Base\Num::format(2));
        assert('2.22' === Base\Num::format(2.22));
        assert('2.24' === Base\Num::format(2.238));
        assert('2.23' === Base\Num::format(2.232));
        assert('2.238' === Base\Num::format(2.238,null,['decimal'=>3]));
        assert('200021321312312321321123321' === Base\Num::format('200021321312312321321123321'));
        assert(null === Base\Num::format([]));
        assert('2|000,000' === Base\Num::format(2000,null,['decimal'=>3,'separator'=>',','thousand'=>'|']));
        assert('2.00' === Base\Num::format(2));

        // formats
        assert(Base\Num::formats('%',[2,3,4,5])[0] === '2%');
        assert(Base\Num::formats('$',[2,3,4,5])[0] === '$2.00');
        assert(Base\Num::formats('size',[200,3000,4000,5000])[0] === '200 Bytes');
        assert(Base\Num::formats('number',[200,3000,4000,5000])[0] === '200.00');
        assert(Base\Num::formats('phone',[5145140000])[0] === '(514) 514-0000');

        // formatsMethod
        assert(Base\Num::formatsMethod('$') === 'moneyFormat');

        // getFormat
        assert(Base\Num::getFormat('en',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>',']);

        // percentFormat
        assert(Base\Num::percentFormat('100') === '100%');
        assert(Base\Num::percentFormat(100) === '100%');

        // getPercentFormat
        assert(count(Base\Num::getPercentFormat()) === 4);

        // moneyFormat
        assert('$2,000.00' === Base\Num::moneyFormat(2000));
        assert('$2:000.00' === Base\Num::moneyFormat(2000,null,['thousand'=>':','output'=>'$%v%']));
        assert('$ 2,000|00' === Base\Num::moneyFormat(2000,null,['thousand'=>',','separator'=>'|','output'=>'$ %v%']));

        // getMoneyFormat
        assert(Base\Num::getMoneyFormat('en',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>',','output'=>'$%v%']);

        // phoneFormat
        assert('(514) 483-5603' === Base\Num::phoneFormat('5144835603'));
        assert('(514) 483-5603' === Base\Num::phoneFormat('51.448356.03'));
        assert('(514) 483-5603' === Base\Num::phoneFormat(5144835603));
        assert('(514) 483-5603' === Base\Num::phoneFormat(51.44835603));
        assert(null === Base\Num::phoneFormat(514483560));
        assert('(514) 483-5603 #212' === Base\Num::phoneFormat('5144835603212'));
        assert('(514) 483-5603' === Base\Num::phoneFormat('5144835603212',null,['extension'=>false]));
        assert(Base\Num::phoneFormat('5144835603212',null,['parenthesis'=>false,'extension'=>false]) === '514 483-5603');

        // getPhoneFormat
        assert(count(Base\Num::getPhoneFormat()) === 2);

        // sizeFormat
        assert('43.35 MB' === Base\Num::sizeFormat(45456546,2));
        assert('1 KB' === Base\Num::sizeFormat(1024,2));
        assert('1.03 KB' === Base\Num::sizeFormat(1055,2));
        assert('518.24 GB' === Base\Num::sizeFormat(556456767878,2));
        assert(Base\Num::sizeFormat(2226,2) === '2.17 KB');
        assert(Base\Num::sizeFormat(2226) === '2 KB');
        assert(Base\Num::sizeFormat(556456767878) === '518.24 GB');
        assert(Base\Num::sizeFormat(0) === '0 Byte');

        // getSizeFormat
        assert(count(Base\Num::getSizeFormat('en',['text'=>[1=>'James']])) === 2);
        assert(Base\Num::getSizeFormat('en')['text'][0] === 'Byte');

        // fromSizeFormat
        assert(Base\Num::fromSizeFormat('30MB') === 31457280);
        assert(Base\Num::fromSizeFormat('1 m') === 1048576);
        assert(Base\Num::fromSizeFormat('1mb') === 1048576);
        assert(Base\Num::fromSizeFormat('2mb') === 2097152);
        assert(Base\Num::fromSizeFormat('2 MBS') === 2097152);
        assert(Base\Num::fromSizeFormat('19kb') === 19456);
        assert(Base\Num::fromSizeFormat('10 byte') === 10);

        // fromSizeFormatMb
        assert(Base\Num::fromSizeFormatMb('30MB') === 31457280);
        assert(Base\Num::fromSizeFormatMb('1 m') === 1048576);
        assert(Base\Num::fromSizeFormatMb('1mb') === 1048576);
        assert(Base\Num::fromSizeFormatMb('2mb') === 2097152);
        assert(Base\Num::fromSizeFormatMb('2 MBS') === 2097152);

        // percentCalc
        assert([4.6,35.9,31.3,26.6,1.6,0] === Base\Num::percentCalc([3,'23',20,'17',1.0,0]));
        $percent = [1213,222,1223.124,'6124'];
        assert([13.9,2.5,13.9,69.7] === Base\Num::percentCalc($percent));
        assert([10,20,30,40] === Base\Num::percentCalc([1,2,3,4]));
        assert([0,0,7.1,92.8] === Base\Num::percentCalc([1,2,323,4213],false));
        assert([0,0,7.2,92.8] === Base\Num::percentCalc([1,2,323,4213]));
        assert([0,0,7,93] === Base\Num::percentCalc([1,2,323,4213],true,0));

        // percentAdjustTotal
        assert([100] === Base\Num::percentAdjustTotal([1]));
        assert([1,99] === Base\Num::percentAdjustTotal([1,99]));
        assert([2,98] === Base\Num::percentAdjustTotal([1,98]));
        assert([3,98] === Base\Num::percentAdjustTotal([1,98],null,1,101));
        assert([5,96] === Base\Num::percentAdjustTotal([5,98],1,1,101));

        return true;
    }
}
?>