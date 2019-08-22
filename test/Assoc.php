<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// assoc
class Assoc extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$array = array('test'=>2,1,2);

		// arr
		assert(Base\Assoc::arr($array) === $array);

		// exist
		assert(Base\Assoc::exist("test",$array));

		// exists
		assert(Base\Assoc::exists(array("test"),$array));

		// same
		assert(Base\Assoc::same($array,$array));

		// isCount
		assert(Base\Assoc::isCount(3,$array));

		// isMinCount
		assert(Base\Assoc::isMinCount(2,$array));
		assert(Base\Assoc::isMinCount(3,$array));

		// isMaxCount
		assert(Base\Assoc::isMaxCount(3,$array));
		assert(!Base\Assoc::isMaxCount(1,$array));

		// sameCount
		assert(Base\Assoc::sameCount($array,$array));

		// sameKey
		assert(Base\Assoc::sameKey($array,$array));

		// getSensitive
		assert(Base\Assoc::getSensitive());

		// prepend
		assert(Base\Assoc::prepend(array('bla'=>4),$array)['bla'] === 4);

		// append
		assert(Base\Assoc::append(array('bla'=>4),$array)['bla'] === 4);

		// count
		assert(Base\Assoc::count($array) === 3);

		// index
		assert(Base\Assoc::index(0,$array) === 2);

		// indexes
		assert(Base\Assoc::indexes(array(0),$array) === array(2));

		// get
		assert(Base\Assoc::index("test",$array) === 2);

		// gets
		assert(Base\Assoc::indexes(array("test"),$array) === array("test"=>2));

		// set
		assert(Base\Assoc::set("test2","ok",$array)['test2'] === 'ok');

		// sets
		assert(Base\Assoc::sets(array("test2"=>"ok"),$array)['test2'] === 'ok');

		// unset
		assert(count(Base\Assoc::unset('test',$array)));

		// unsets
		assert(count(Base\Assoc::unsets(array('test'),$array)));

		// slice
		assert(Base\Assoc::slice('test','test',$array)['test'] === 2);

		// sliceIndex
		assert(Base\Assoc::sliceIndex(0,1,$array)['test'] === 2);

		// splice
		assert(Base\Assoc::splice('test','test',$array) === array(1,2));

		// spliceIndex
		assert(Base\Assoc::spliceIndex(0,1,$array) === array(1,2));

		// spliceFirst
		assert(Base\Assoc::spliceFirst($array) === array(1,2));

		// spliceLast
		assert(Base\Assoc::spliceLast($array) === array('test'=>2,1));

		// insert
		assert(Base\Assoc::insert(1,'bla',$array)[1] === 'bla');

		// insertIndex
		assert(Base\Assoc::insert(1,'bla',$array)[1] === 'bla');

		// keysStart
		assert(Base\Assoc::keysStart('te',$array)['test'] === 2);
		Base\Assoc::$config['sensitive'] = false;
		assert(Base\Assoc::keysStart('TE',$array)['test'] === 2);
		Base\Assoc::$config['sensitive'] = true;

		// keysEnd
		assert(Base\Assoc::keysEnd('st',$array)['test'] === 2);

		// option
		assert(count(Base\Assoc::option(array('test'=>2))) === 3);
		
		return true;
	}
}
?>