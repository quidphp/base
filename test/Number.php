<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// number
// class for testing Quid\Base\Number
class Number extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// typecast
		$a = '23.2';
		$b = 'string';
		Base\Number::typecast($a,$b);
		assert($a === 23.2);
		assert($b === 'string');

		// typecastInt
		$a = '23.2';
		$b = 'string';
		Base\Number::typecastInt($a,$b);
		assert($a === 23);
		assert($b === 0);
		$a = '23.2';
		$b = 'string';

		// typecastFloat
		Base\Number::typecastFloat($a,$b);
		assert($a === 23.2);
		assert($b === (float) 0);

		// cast
		assert(Base\Number::cast('000000') === 0);
		assert(Base\Number::cast('000111') === 111);
		assert(Base\Number::cast('000000',false) === '000000');
		assert(Base\Number::cast('000111',false) === '000111');
		assert((float) '1.22323321213321222' === Base\Number::cast('1.22323321213321222'));
		assert(null === Base\Number::cast([]));
		assert((int) '2' === Base\Number::cast('2'));
		assert((int) true === Base\Number::cast(true));
		assert((int) false === Base\Number::cast(false));
		assert(0 === Base\Number::cast(false));
		assert(2 === Base\Number::cast(2.0));
		assert('22323321213321222' === Base\Number::cast('22323321213321222'));
		assert('22323321213321222' === Base\Number::cast(22323321213321222));
		assert((float) '2.1' === Base\Number::cast('2.1'));
		assert((float) '2.1' === Base\Number::cast(2.1));
		assert((float) '0.1' === Base\Number::cast('0.1'));
		assert((float) '-0.1' === Base\Number::cast('-0.1'));
		assert((float) '-0.1' === Base\Number::cast(-0.1));
		assert(-0.1 === Base\Number::cast('-0,1'));
		assert('-0,1' === Base\Number::cast('-0,1',false));
		assert((int) 0 === Base\Number::cast('0'));
		assert((int) 0 === Base\Number::cast(0));
		assert('aaaa0' === Base\Number::cast('aaaa0'));
		assert('1aaaaa1' === Base\Number::cast('1aaaaa1'));
		assert('-0,50$' === Base\Number::cast('-0,50$'));
		assert('-1,50$' === Base\Number::cast('-1,50$'));
		assert(1 === Base\Number::cast(true));
		assert(0 === Base\Number::cast(false));
		assert(null === Base\Number::cast(null));
		assert(Base\Number::cast('30MB') === '30MB');

		// castToInt
		assert(Base\Number::castToInt('30MB') === null);
		assert(Base\Number::castToInt(true) === 1);
		assert(Base\Number::castToInt('1.5') === 1);

		// castToFloat
		assert(Base\Number::castToFloat('30MB') === null);
		assert(Base\Number::castToFloat(true) === 1.0);
		assert(Base\Number::castToFloat('1.5') === 1.5);

		// castFromString
		assert(Base\Number::castFromString('30MB') === 30);
		assert(Base\Number::castFromString('abc') === null);

		// is
		assert(Base\Number::is('2'));
		assert(!Base\Number::is(true));

		// isEmpty
		assert(Base\Number::isEmpty(0));
		assert(!Base\Number::isEmpty(1));

		// isNotEmpty
		assert(Base\Number::isNotEmpty(1));
		assert(!Base\Number::isNotEmpty(0));

		// isString
		assert(Base\Number::isString('1'));
		assert(!Base\Number::isString(2));

		// isInt
		assert(Base\Number::isInt(1));
		assert(!Base\Number::isInt(2.3));

		// isFloat
		assert(Base\Number::isFloat((float) 1));
		assert(!Base\Number::isFloat(1));
		assert(Base\Number::isFloat(log(0)));

		// isFinite
		assert(!Base\Number::isFinite(log(0)));
		assert(Base\Number::isFinite('2'));
		assert(Base\Number::isFinite(Base\Number::pi()));

		// isInfinite
		assert(Base\Number::isInfinite(INF));
		assert(!Base\Number::isInfinite('2'));
		assert(!Base\Number::isInfinite(Base\Number::pi()));

		// isNan
		assert(Base\Number::isNan(acos(8)));
		assert(Base\Number::isNan(NAN));
		assert(!Base\Number::isNan('2'));
		assert(!Base\Number::isNan(Base\Number::pi()));

		// isPositive
		assert(Base\Number::isPositive('2'));
		assert(!Base\Number::isPositive(0));
		assert(!Base\Number::isPositive(-2));
		assert(!Base\Number::isPositive('test'));

		// isNegative
		assert(!Base\Number::isNegative(0));
		assert(!Base\Number::isNegative('2'));
		assert(Base\Number::isNegative(-2));
		assert(!Base\Number::isNegative('test'));

		// isOdd
		assert(Base\Number::isOdd('1'));
		assert(Base\Number::isOdd(3));
		assert(Base\Number::isOdd(3.0));
		assert(!Base\Number::isOdd(3.1));
		assert(!Base\Number::isOdd(-2));

		// isEven
		assert(!Base\Number::isEven('1'));
		assert(!Base\Number::isEven(3));
		assert(Base\Number::isEven(-2));
		assert(Base\Number::isEven(4));

		// isWhole
		assert(!Base\Number::isWhole('1.5'));
		assert(Base\Number::isWhole(-1));
		assert(!Base\Number::isWhole(1.5));
		assert(Base\Number::isWhole(0));

		// isWholeNotEmpty
		assert(!Base\Number::isWholeNotEmpty('1.5'));
		assert(Base\Number::isWholeNotEmpty(-1));
		assert(!Base\Number::isWholeNotEmpty(1.5));
		assert(!Base\Number::isWholeNotEmpty(0));

		// isDecimal
		assert(!Base\Number::isDecimal(-1));
		assert(Base\Number::isDecimal(1.5));
		assert(Base\Number::isDecimal('1.5'));
		assert(!Base\Number::isDecimal('2'));

		// isOverflow
		assert(Base\Number::isOverflow('123321123312231312213123312312312312'));

		// isLength
		assert(Base\Number::isLength(3,123));
		assert(Base\Number::isLength(5,123.2));
		assert(!Base\Number::isLength(5,'test'));
		assert(Base\Number::isLength(2,'12'));

		// isMinLength
		assert(Base\Number::isMinLength(3,123));

		// isMaxLength
		assert(Base\Number::isMaxLength(3,123));

		// isCountable
		assert(!Base\Number::isCountable(1234));
		assert(Base\Number::isCountable([1,2,3]));

		// prepend
		assert(Base\Number::prepend('23',4.2,4) === 44.223);

		// append
		assert(Base\Number::append('23',4.2,4) === 234.24);
		assert(Base\Number::append('23',4.2,2.4) === '234.22.4');

		// fromBool
		assert(1 === Base\Number::fromBool(true));
		assert(0 === Base\Number::fromBool(false));

		// commaToDecimal
		assert(1.1 === Base\Number::commaToDecimal(1.1));
		assert(2.1 === Base\Number::commaToDecimal('2,1'));
		assert('2.1.2' === Base\Number::commaToDecimal('2,1,2'));

		// len
		assert(3 === Base\Number::len('2.5'));
		assert(4 === Base\Number::len(2033));
		assert(6 === Base\Number::len(2033.5));

		// sub
		assert(33 === Base\Number::sub(2,2,2033));
		assert(3 === Base\Number::sub(-1,null,2033));
		assert(3.21 === Base\Number::sub(-4,null,2033.21));
		assert(2033.21 === Base\Number::sub(0,20,2033.21));

		// round
		assert(3 === Base\Number::round('2.5',0));
		assert(2 === Base\Number::round('2.5',0,PHP_ROUND_HALF_DOWN));
		assert(2.6 === Base\Number::round('2.55',1));
		assert(2.6 === Base\Number::round(2.55,1));
		assert(2 === Base\Number::round(2,1));

		// roundLower
		assert(2 === Base\Number::roundLower('2.5',0));

		// ceil
		assert(3 === Base\Number::ceil('2.5'));
		assert(3 === Base\Number::ceil(2.2));
		assert(0 === Base\Number::ceil('bla'));

		// floor
		assert(2 === Base\Number::floor('2.5'));
		assert(2 === Base\Number::floor(2.2));
		assert(0 === Base\Number::floor('bla'));

		// positive
		assert(2 === Base\Number::positive('-2'));
		assert(2 === Base\Number::positive('-2a'));
		assert(abs('a-2a') === Base\Number::positive('a-2a'));
		assert(0 === Base\Number::positive('a-2a'));
		assert(2.5 === Base\Number::positive('-2.5'));

		// negative
		assert(-2 === Base\Number::negative('2'));
		assert(-2.5 === Base\Number::negative(2.5));
		assert(-2 === Base\Number::negative('-2a'));
		assert(abs('-2a') * -1 === Base\Number::negative('-2a'));

		// invert
		assert(-2 === Base\Number::invert('2'));
		assert(0 === Base\Number::invert(0));
		assert(0 === Base\Number::invert('test'));
		assert(0 === Base\Number::invert('0'));
		assert(2.5 === Base\Number::invert(-2.5));

		// increment
		assert(2 === Base\Number::increment(1,1));
		assert(2.2 === Base\Number::increment(2,0.2));
		assert(2.5 === Base\Number::increment(2.3,'0.2'));
		assert(3 === Base\Number::increment(2,1));
		assert(Base\Number::increment('2',-4) === -2);
		assert(6.1 === Base\Number::increment('2',4.1));
		assert((string) -2.1 === (string) Base\Number::increment('2',-4.1));

		// decrement
		assert(-1 === Base\Number::decrement(1,2));
		assert(2.9 === Base\Number::decrement('3',0.1));

		// in
		assert(Base\Number::in(1,2,3));
		assert(Base\Number::in(1,3,3));
		assert(Base\Number::in(1,1,3));
		assert(!Base\Number::in(1,4,3));

		// inExclusive
		assert(Base\Number::inExclusive(1,3,4));
		assert(!Base\Number::inExclusive(1,4,3));

		// pi
		assert(Base\Number::pi(2) === 3.14);
		assert(Base\Number::pi(4) === 3.1416);

		// math
		assert(1 === Base\Number::math('+',[1]));
		assert(3 === Base\Number::math('+',[2,1]));
		assert(1 === Base\Number::math('-',[2,1]));
		assert(4 === Base\Number::math('*',[2,2]));
		assert(null === Base\Number::math('*',[2]));
		assert(8 === Base\Number::math('**',[2,3]));
		assert(1 === Base\Number::math('%',[5,2]));
		assert(1 === Base\Number::math('avg',[1,1]));
		assert(1 === Base\Number::math('/',[2,2]));
		assert(5 === Base\Number::math('avg',[2,4,6,8]));
		assert(5 === Base\Number::math('average',[2,4,6,8]));
		assert(6.044 === Base\Number::math('avg',[2,4,6,8,10.22]));
		assert(6.04 === Base\Number::math('avg',[2,4,6,8,10.22],2));
		assert(6 === Base\Number::math('avg',[2,4,6,8,10.22],0));
		assert(!Base\Number::math('/',[2,0]));
		assert(Base\Number::math('max',[2,0]) === 2);
		assert(Base\Number::math('min',[2,0]) === 0);
		assert(Base\Number::math('>',[4,3]) === 4);
		assert(Base\Number::math('<',[4,1]) === 1);

		// combine
		assert(Base\Number::combine('+',[1,2],[3,4],[1,2,3]) === [5,8,3]);
		assert(Base\Number::combine('*',[1,2],[3,4],[1,2,3],['test',[]]) === [3,16,3]);
		assert(Base\Number::combine('/',[1,0],[0,0],[0,2,3]) === [null,null,3]);
		assert(Base\Number::combine('>',[1,0],[3,0],[0,2,3]) === [3,2,3]);

		// addition
		assert(Base\Number::addition(2) === 2);
		assert(3 === Base\Number::addition(2,1));
		assert(is_float(Base\Number::addition('121221212121121212121212',1)));
		assert(6 === Base\Number::addition(2,1,3));
		assert((string) 6.3 === (string) Base\Number::addition(2,'1.1',3.2));  // pourquoi ?

		// subtraction
		assert(Base\Number::subtraction(2) === 2);
		assert(1 === Base\Number::subtraction(2,1));
		assert(-19 === Base\Number::subtraction(2,1,20));
		assert(-19 === Base\Number::subtraction(2.0,'1','20'));

		// multiplication
		assert(4.4 === Base\Number::multiplication(2,2.2));
		assert(8 === Base\Number::multiplication(2,2,2));

		// pow
		assert(4 === Base\Number::pow(2,2));
		assert(2 === Base\Number::pow('2','1'));
		assert(4 === Base\Number::pow('2','2'));
		assert(1 === Base\Number::pow('2',0));
		assert(4.41 === Base\Number::pow('2.1',2));
		assert((string) 5.1154335606641 === (string) Base\Number::pow('2.1','2.2'));

		// division
		assert(1 === Base\Number::division(2,2));
		assert(0.01 === Base\Number::division(2,2,100));
		assert(0.01 === Base\Number::division(2,'2',100.0));
		assert(null === Base\Number::division(2,2,0,2));

		// modulo
		assert(1 === Base\Number::modulo(5,2));
		assert(0 === Base\Number::modulo(9,3));

		// average
		assert(0 === Base\Number::average(0,0,0));
		assert(55 === Base\Number::average(100,10));
		assert(57.5 === Base\Number::average(100,15));
		assert(65.4 === Base\Number::average(100,15,60,32,120));

		// min
		assert(Base\Number::min(1,'2','3.4','james') === 1);

		// max
		assert(Base\Number::max(1,'2','3.4',[],'bla','z') === 3.4);

		// random
		assert(1 === Base\Number::random(1,1,1));
		assert(1 === Base\Number::random(10,1,1));
		assert(1 === Base\Number::random(10,1,1,true));
		assert(1 === Base\Number::random(10,1,1,false));

		// zerofill
		assert(Base\Number::zerofill(5,123) === '00123');
		assert(Base\Number::zerofill(2,123) === '123');

		// fromOctal
		assert(Base\Number::fromOctal('100775') === 33277);
		assert(Base\Number::fromOctal(100775) === 33277);
		assert(Base\Number::fromOctal(775) === 509);
		assert(0775 === 509);

		// toOctal
		assert(Base\Number::toOctal(33277) === '100775');
		assert(Base\Number::toOctal(33277,2) === 775);
		assert(Base\Number::toOctal(509) === '775');
		assert(Base\Number::toOctal(509,true) === 775);

		// format
		assert('2.00' === Base\Number::format(2));
		assert('2.22' === Base\Number::format(2.22));
		assert('2.24' === Base\Number::format(2.238));
		assert('2.23' === Base\Number::format(2.232));
		assert('2.238' === Base\Number::format(2.238,null,['decimal'=>3]));
		assert('200021321312312321321123321' === Base\Number::format('200021321312312321321123321'));
		assert(null === Base\Number::format([]));
		assert('2|000,000' === Base\Number::format(2000,null,['decimal'=>3,'separator'=>',','thousand'=>'|']));
		assert('2.00' === Base\Number::format(2));

		// formats
		assert(Base\Number::formats('%',[2,3,4,5])[0] === '2%');
		assert(Base\Number::formats('$',[2,3,4,5])[0] === '$2.00');
		assert(Base\Number::formats('size',[200,3000,4000,5000])[0] === '200 Bytes');
		assert(Base\Number::formats('number',[200,3000,4000,5000])[0] === '200.00');
		assert(Base\Number::formats('phone',[5145140000])[0] === '(514) 514-0000');

		// formatsMethod
		assert(Base\Number::formatsMethod('$') === 'moneyFormat');

		// getFormat
		assert(Base\Number::getFormat('en',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>',']);

		// percentFormat
		assert(Base\Number::percentFormat('100') === '100%');
		assert(Base\Number::percentFormat(100) === '100%');

		// getPercentFormat
		assert(count(Base\Number::getPercentFormat()) === 4);

		// moneyFormat
		assert('$2,000.00' === Base\Number::moneyFormat(2000));
		assert('$2:000.00' === Base\Number::moneyFormat(2000,null,['thousand'=>':','output'=>'$%v%']));
		assert('$ 2,000|00' === Base\Number::moneyFormat(2000,null,['thousand'=>',','separator'=>'|','output'=>'$ %v%']));

		// getMoneyFormat
		assert(Base\Number::getMoneyFormat('en',['decimal'=>3]) === ['decimal'=>3,'separator'=>'.','thousand'=>',','output'=>'$%v%']);

		// phoneFormat
		assert('(514) 483-5603' === Base\Number::phoneFormat('5144835603'));
		assert('(514) 483-5603' === Base\Number::phoneFormat('51.448356.03'));
		assert('(514) 483-5603' === Base\Number::phoneFormat(5144835603));
		assert('(514) 483-5603' === Base\Number::phoneFormat(51.44835603));
		assert(null === Base\Number::phoneFormat(514483560));
		assert('(514) 483-5603 #212' === Base\Number::phoneFormat('5144835603212'));
		assert('(514) 483-5603' === Base\Number::phoneFormat('5144835603212',null,['extension'=>false]));
		assert(Base\Number::phoneFormat('5144835603212',null,['parenthesis'=>false,'extension'=>false]) === '514 483-5603');

		// getPhoneFormat
		assert(count(Base\Number::getPhoneFormat()) === 2);

		// sizeFormat
		assert('43.35 MB' === Base\Number::sizeFormat(45456546,2));
		assert('1 KB' === Base\Number::sizeFormat(1024,2));
		assert('1.03 KB' === Base\Number::sizeFormat(1055,2));
		assert('518.24 GB' === Base\Number::sizeFormat(556456767878,2));
		assert(Base\Number::sizeFormat(2226,2) === '2.17 KB');
		assert(Base\Number::sizeFormat(2226) === '2 KB');
		assert(Base\Number::sizeFormat(556456767878) === '518.24 GB');
		assert(Base\Number::sizeFormat(0) === '0 Byte');

		// getSizeFormat
		assert(count(Base\Number::getSizeFormat('en',['text'=>[1=>'James']])) === 2);
		assert(Base\Number::getSizeFormat('en')['text'][0] === 'Byte');

		// fromSizeFormat
		assert(Base\Number::fromSizeFormat('30MB') === 31457280);
		assert(Base\Number::fromSizeFormat('1 m') === 1048576);
		assert(Base\Number::fromSizeFormat('1mb') === 1048576);
		assert(Base\Number::fromSizeFormat('2mb') === 2097152);
		assert(Base\Number::fromSizeFormat('2 MBS') === 2097152);
		assert(Base\Number::fromSizeFormat('19kb') === 19456);
		assert(Base\Number::fromSizeFormat('10 byte') === 10);

		// fromSizeFormatMb
		assert(Base\Number::fromSizeFormatMb('30MB') === 31457280);
		assert(Base\Number::fromSizeFormatMb('1 m') === 1048576);
		assert(Base\Number::fromSizeFormatMb('1mb') === 1048576);
		assert(Base\Number::fromSizeFormatMb('2mb') === 2097152);
		assert(Base\Number::fromSizeFormatMb('2 MBS') === 2097152);

		// percentCalc
		assert([4.6,35.9,31.3,26.6,1.6,0] === Base\Number::percentCalc([3,'23',20,'17',1.0,0]));
		$percent = [1213,222,1223.124,'6124'];
		assert([13.9,2.5,13.9,69.7] === Base\Number::percentCalc($percent));
		assert([10,20,30,40] === Base\Number::percentCalc([1,2,3,4]));
		assert([0,0,7.1,92.8] === Base\Number::percentCalc([1,2,323,4213],false));
		assert([0,0,7.2,92.8] === Base\Number::percentCalc([1,2,323,4213]));
		assert([0,0,7,93] === Base\Number::percentCalc([1,2,323,4213],true,0));

		// percentAdjustTotal
		assert([100] === Base\Number::percentAdjustTotal([1]));
		assert([1,99] === Base\Number::percentAdjustTotal([1,99]));
		assert([2,98] === Base\Number::percentAdjustTotal([1,98]));
		assert([3,98] === Base\Number::percentAdjustTotal([1,98],null,1,101));
		assert([5,96] === Base\Number::percentAdjustTotal([5,98],1,1,101));

		return true;
	}
}
?>