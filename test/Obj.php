<?php
declare(strict_types=1);
namespace Quid\Base\Test 
{
	use Quid\Base;
	
	// obj
	class Obj extends Base\Test
	{
		// trigger
		public static function trigger(array $data):bool
		{
			// prepare
			$obj = new \stdclass();
			$obj->test = 2;
			$obj->{'2'} = 2;
			$datetime = new \Datetime("now");
			$datetime2 = new \Datetime("@".Base\Date::mk(2017,1,1));
			$datetime3 = new \Datetime("@".Base\Date::mk(2016,1,1));
			$datetime4 = new \Datetime("@".Base\Date::mk(2015,1,1));
			$class = new Obj\MyClass();
			$classname = Obj\MyClass::class;
			$parentClass = Obj\ParentClass::class;
			$parent = new $parentClass();
			$trait = Obj\Trai::class;
			$parentTrai = Obj\ParentTrai::class;
			$interface = Obj\Inter::class;
			$parentInterface = Obj\ParentInter::class;
			$anonymous = new class { };
			$_file_ = Base\Finder::shortcut("[assertCommon]/class.php");
			
			// typecast
			$array = ["test"=>2];
			Base\Obj::typecast($array);
			assert($array instanceof \stdClass);

			// is
			assert(Base\Obj::is($obj));
			assert(!Base\Obj::is([]));

			// isIncomplete
			$o = 'O:14:"BogusTestClass":0:{}';
			assert(Base\Obj::isIncomplete(unserialize($o)));
			assert(!Base\Obj::isIncomplete(new \DateTime("now")));
			
			// isAnonymous
			assert(Base\Obj::isAnonymous($anonymous));
			assert(!Base\Obj::isAnonymous($obj));
			
			// extend
			assert(Base\Obj::extend($parent,$class));

			// extendOne
			assert(Base\Obj::extendOne([new \DateTime("now"),$parent],$class));
			assert(!Base\Obj::extendOne([new \DateTime("now"),new \DateTime("now")],$class));
			
			// hasMethod
			assert(Base\Obj::hasMethod('test',$class));
			assert(Base\Obj::hasMethod('parentPrivDyn',$class));

			// hasProperty
			assert(Base\Obj::hasProperty('parentPubDyn',$class));
			assert(Base\Obj::hasProperty('traiPrivDyn',$class));
			assert(Base\Obj::hasProperty('traiPrivStat',$class));
			assert(Base\Obj::hasProperty('2',$obj));

			// hasInterface
			assert(Base\Obj::hasInterface($interface,$class));
			assert(Base\Obj::hasInterface($parentInterface,$class));

			// hasTrait
			assert(Base\Obj::hasTrait($trait,$class));
			assert(Base\Obj::hasTrait($parentTrai,$class));
			assert(!Base\Obj::hasTrait($parentTrai,$class,false));

			// hasNamespace
			assert(Base\Obj::hasNamespace(Obj::class,$class));
			assert(Base\Obj::hasNamespace(Obj::class,$class));
			assert(Base\Obj::hasNamespace([Obj::class],$class));
			assert(Base\Obj::hasNamespace('',$obj));
			assert(Base\Obj::hasNamespace(null,$obj));

			// inNamespace
			assert(Base\Obj::inNamespace(Obj::class,$class));
			assert(Base\Obj::inNamespace([Obj::class],$class));
			assert(!Base\Obj::inNamespace('',$obj));

			// instance
			assert(Base\Obj::instance($class,$class,$class));
			assert(!Base\Obj::instance($class,$class,$class,$obj));
			assert(!Base\Obj::instance($obj,$class,$class));
			assert(!Base\Obj::instance($class,$parent,$class));
			assert(Base\Obj::instance($parent,$parent,$class,$class));

			// sameInterface
			assert(Base\Obj::sameInterface($class,$class,$class));
			assert(Base\Obj::sameInterface(new \SplFileObject($_file_),new \SplTempFileObject()));

			// sameNamespace
			assert(Base\Obj::sameNamespace($class,$class,$class));
			assert(Base\Obj::sameNamespace(new \SplFileObject($_file_),new \SplTempFileObject()));

			// fqcn
			assert(Base\Obj::fqcn($class) === Obj::class.'\MyClass');
			assert(Base\Obj::fqcn($obj) === 'stdClass');

			// namespace
			assert(Base\Obj::namespace($class) === Obj::class);
			assert(Base\Obj::namespace($obj) === null);

			// name
			assert(Base\Obj::name($class) === 'MyClass');
			assert(Base\Obj::name($obj) === "stdClass");

			// hash
			assert(strlen(Base\Obj::hash($obj)) === 32);
			assert(strlen(Base\Obj::hash($class)) === 32);

			// parent
			assert(Base\Obj::parent($class) === Obj::class.'\ParentClass');
			assert(Base\Obj::parent($obj) === null);

			// parents
			assert(Base\Obj::parents($class) === [Obj::class.'\ParentClass']);
			assert(Base\Obj::parents($obj) === []);

			// top
			assert(Base\Obj::top($class) === Obj::class."\ParentClass");
			assert(Base\Obj::top($obj) === "stdClass");

			// topParent
			assert(Base\Obj::topParent($class) === Obj::class."\ParentClass");
			assert(Base\Obj::topParent($obj) === null);

			// methods
			assert(count(Base\Obj::methods($class)) === 10);
			assert(Base\Obj::methods($obj) === []);

			// properties
			assert(count(Base\Obj::properties($class)) === 8);
			assert(count(Base\Obj::properties($obj)) === 2);

			// interfaces
			assert(count(Base\Obj::interfaces($class)) === 3);
			assert(Base\Obj::interfaces($obj) === []);

			// traits
			assert(count(Base\Obj::traits($class)) === 3);
			assert(Base\Obj::traits($obj) === []);

			// info
			assert(count(Base\Obj::info($class)) === 9);
			assert(count(Base\Obj::info($obj)) === 9);
			assert(count(Base\Obj::info($class,get_object_vars($class),get_class_methods($class))) === 9);
			assert(count(Base\Obj::info($class)) === 9);

			// get
			assert(Base\Obj::get('test',$obj) === 2);
			assert(Base\Obj::get('testz',$obj) === null);

			// gets
			assert(Base\Obj::gets(['test','testz'],$obj) === ['test'=>2,'testz'=>null]);

			// set
			assert(Base\Obj::set('what',2,$obj) === $obj);
			assert(Base\Obj::get('what',$obj) === 2);
			Base\Obj::set('what',3,$obj);
			assert(Base\Obj::get('what',$obj) === 3);

			// sets
			Base\Obj::sets(['what'=>4,'ok'=>'james'],$obj);
			assert(Base\Obj::get('what',$obj) === 4);

			// unset
			Base\Obj::unset('ok',$obj);
			assert(Base\Obj::get('ok',$obj) === null);

			// unsets
			Base\Obj::unsets(['what'],$obj);
			assert(Base\Obj::get('what',$obj) === null);

			// create
			assert(Base\Obj::create(\DateTime::class,'now') instanceof \DateTime);

			// createArgs
			assert(Base\Obj::createArgs(\DateTime::class,['now']) instanceof \DateTime);
			assert(Base\Obj::createArgs(\DateTime::class,['now']) instanceof \DateTime);

			// createArray
			assert(Base\Obj::createArray([\DateTime::class,['now']]) instanceof \DateTime);

			// sort
			assert(Base\Obj::sort('getTimestamp','desc',[$datetime,$datetime2,$datetime3,$datetime4])[0] === $datetime);
			assert(Base\Obj::sort('getTimestamp','asc',[$datetime,$datetime2,$datetime3,$datetime4])[3] === $datetime4);

			// sorts
			assert(current(Base\Obj::sorts([['getTimestamp',true]],[$datetime,$datetime2,$datetime3,$datetime4])) === $datetime4);
			assert(current(Base\Obj::sorts([['getTimestamp',false]],[$datetime2,$datetime,$datetime3,$datetime4])) === $datetime);

			// cast
			assert(Base\Obj::cast([$datetime]) === [$datetime]);
			assert(is_array(Base\Obj::cast(['test'=>2,'OK'=>[Base\Request::class,'absolute']])['OK']));

			// casts
			assert(Base\Obj::casts(0,$datetime) === [$datetime]);
			assert(is_array(Base\Obj::casts(0,['test'=>2,'OK'=>[Base\Request::class,'absolute']])[0]['OK']));

			// setCastError
			
			return true;
		}
	}
}

namespace Quid\Base\Test\Obj 
{
	// parentTrai
	trait ParentTrai
	{
		// property
		public $parentTraiPubDyn = null;
		protected $parentTraiProDyn = null;
		private $parentTraiPrivDyn = null;
		public static $parentTraiPubStat = null;
		protected static $parentTraiProStat = null;
		private static $parentTraiPrivStat = null;
		
		
		// parentTraiPubDyn
		public function parentTraiPubDyn() 
		{
			return true;
		}
		
		
		// parentTraiProDyn
		protected function parentTraiProDyn() 
		{
			return true;
		}
		
		
		// parentTraiPrivDyn
		private function parentTraiPrivDyn() 
		{
			return true;
		}
		
		
		// parentTraiPubStat
		public static function parentTraiPubStat() 
		{
			return true;
		}
		
		
		// parentTraiProStat
		protected static function parentTraiProStat() 
		{
			return true;
		}
		
		
		// parentTraiPrivStat
		private static function parentTraiPrivStat() 
		{
			return true;
		}
	}

	// ancienTrai
	trait AncienTrai
	{
		
	}

	// trai
	trait Trai
	{
		// trait
		use AncienTrai;
		
		
		// property
		public $traiPubDyn = null;
		protected $traiProDyn = null;
		private $traiPrivDyn = null;
		public static $traiPubStat = null;
		protected static $traiProStat = null;
		private static $traiPrivStat = null;
		
		
		// abstrai
		public abstract function abstrai();
		
		
		// traiPubDyn
		public function traiPubDyn() 
		{
			return true;
		}
		
		
		// traiProDyn
		protected function traiProDyn() 
		{
			return true;
		}
		
		
		// traiPrivDyn
		private function traiPrivDyn() 
		{
			return true;
		}
		
		
		// traiPubStat
		public static function traiPubStat() 
		{
			return true;
		}
		
		
		// traiProStat
		protected static function traiProStat() 
		{
			return true;
		}
		
		
		// traiPrivStat
		private static function traiPrivStat() 
		{
			return true;
		}
	}

	// parentInter
	interface ParentInter
	{
		
	}

	// ParentInterExtra
	interface ParentInterExtra
	{
		// pubDyn
		public function pubDyn();
	}

	// inter
	interface Inter extends ParentInter, ParentInterExtra
	{	
		// traiPrivDyn
		public function traiPubDyn(); 
	}

	// parentClass
	class ParentClass
	{
		// trait
		use ParentTrai;
		
		
		// property
		public $parentPubDyn = ['test'=>['what'=>[1,2,3]]];
		protected $parentProDyn = null;
		private $parentPrivDyn = null;
		public static $parentPubStat = ['test'=>['what'=>[1,2,3]]];
		protected static $parentProStat = null;
		private static $parentPrivStat = null;
		
		
		// absTest
		public function test() 
		{
			return true;
		}
		
		
		// parentPubDyn
		public function parentPubDyn() 
		{
			return true;
		}
		
		
		// parentProDyn
		protected function parentProDyn() 
		{
			return true;
		}
		
		
		// parentPrivDyn
		protected function parentPrivDyn() 
		{
			return true;
		}
		
		
		// parentPubStat
		public static function parentPubStat() 
		{
			return true;
		}
		
		
		// parentProStat
		protected static function parentProStat() 
		{
			return true;
		}
		
		
		// parentPrivStat
		private static function parentPrivStat() 
		{
			return true;
		}
	}

	// myclass
	final class MyClass extends ParentClass implements Inter
	{
		// trait
		use Trai;
		
		
		// property
		public $pubDyn = null;
		protected $proDyn = null;
		private $privDyn = null;
		public static $pubStat = null;
		protected static $proStat = null;
		private static $privStat = null;
		
		
		// test
		public function test() 
		{
			return true;
		}
		
		
		// abstrai
		public function abstrai() 
		{
			return true;
		}
		
		
		// pubDyn
		public function pubDyn() 
		{
			return true;
		}
		
		
		// proDyn
		protected function proDyn() 
		{
			return true;
		}
		
		
		// privDyn
		private function privDyn() 
		{
			return true;
		}
		
		
		// pubStat
		public static function pubStat() 
		{
			return true;
		}
		
		
		// proStat
		protected static function proStat() 
		{
			return true;
		}
		
		
		// priStat
		private static function privStat() 
		{
			return true;
		}
	}
} 
?>