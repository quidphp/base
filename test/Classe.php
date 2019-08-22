<?php
declare(strict_types=1);
namespace Quid\Base\Test 
{
	use Quid\Base;

	// classe
	class Classe extends Base\Test
	{
		// trigger
		public static function trigger(array $data):bool
		{
			// prepare
			$datetime = new \Datetime("now");
			$class = new Classe\MyClass();
			$classname = Classe\MyClass::class;
			$parentClass = Classe\ParentClass::class;
			$trait = Classe\Trai::class;
			$parentTrai = Classe\ParentTrai::class;
			$interface = Classe\Inter::class;
			$parentInterface = Classe\ParentInter::class;
			$anonymous = new class { };
			
			// is
			assert(Base\Classe::is(Base\Classe::class));
			assert(Base\Classe::is("Quid\Base\Classe"));
			assert(Base\Classe::is("\Quid\Base\Classe"));
			assert(Base\Classe::is($class));
			assert(Base\Classe::is($datetime));
			assert(Base\Classe::is($anonymous));
			
			// isInterface
			assert(Base\Classe::isInterface("Traversable"));
			assert(!Base\Classe::isInterface("\Quid\Base\What"));
			assert(!Base\Classe::isInterface($datetime));

			// isTrait
			assert(Base\Classe::isTrait("Quid\Base\_option"));
			assert(Base\Classe::isTrait(['Quid','Base','_option']));
			assert(Base\Classe::isTrait("\Quid\Base\_option"));
			assert(Base\Classe::isTrait($trait));
			assert(!Base\Classe::isTrait("\Quid\Base\What"));
			assert(!Base\Classe::isTrait($datetime));
			assert(!Base\Classe::isTrait($anonymous));
			
			// isAny
			assert(Base\Classe::isAny("Quid\Base\_option"));
			assert(Base\Classe::isAny("Traversable"));
			assert(Base\Classe::isAny(Base\Classe::class));
			assert(Base\Classe::isAny($datetime));
			assert(!Base\Classe::isAny("what"));
			assert(Base\Classe::isAny($anonymous));
			
			// isIncomplete
			$o = 'O:14:"BogusTestClass":0:{}';
			assert(Base\Classe::isIncomplete(unserialize($o)));
			assert(Base\Classe::isIncomplete("__PHP_Incomplete_Class"));
			assert(!Base\Classe::isIncomplete(new \DateTime("now")));
			
			// isAnonymous
			assert(!Base\Classe::isAnonymous(unserialize($o)));
			assert(!Base\Classe::isAnonymous("class@blabla"));
			assert(Base\Classe::isAnonymous($anonymous));
			
			// isNameClass
			assert(Base\Classe::isNameClass('test.php'));
			assert(!Base\Classe::isNameClass('testInterface.php'));
			assert(!Base\Classe::isNameClass('testinterface.php'));

			// isNameTrait
			assert(Base\Classe::isNameTrait('_test.php'));
			assert(!Base\Classe::isNameTrait('test.php'));

			// isNameInterface
			assert(!Base\Classe::isNameInterface('_testInterface.php'));
			assert(Base\Classe::isNameInterface('testInterface.php'));

			// extend
			assert(!Base\Classe::extend(Base\Dir::class,Base\Finder::class));
			assert(!Base\Classe::extend(Base\Finder::class,Base\Finder::class));
			assert(Base\Classe::extend(Base\Finder::class,Base\Dir::class));
			assert(!Base\Classe::extend($datetime,$datetime));
			assert(!Base\Classe::extend(Base\Dir::class,Arr::class));
			assert(Base\Classe::extend(new \Exception("what"),new \LogicException("what")));
			assert(!Base\Classe::extend($trait,$trait));
			assert(!Base\Classe::extend($interface,$interface));
			assert(Base\Classe::extend($parentInterface,$interface));
			assert(!Base\Classe::extend($class,$trait));
			assert(!Base\Classe::extend($trait,$class));
			assert(Base\Classe::extend($interface,$class));
			assert(Base\Classe::extend($parentInterface,$class));
			assert(Base\Classe::extend($parentClass,$class));
			
			// extendOne
			assert(Base\Classe::extendOne([File::class,Base\Finder::class],Base\Dir::class));
			
			// hasMethod
			assert(Base\Classe::hasMethod('is',Base\Dir::class));
			assert(!Base\Classe::hasMethod('is',$datetime));
			assert(!Base\Classe::hasMethod('is',"\Quid\Waht"));
			assert(Base\Classe::hasMethod('pubDyn',$interface));
			assert(Base\Classe::hasMethod('proStat',$class));
			assert(Base\Classe::hasMethod('privStat',$class));
			assert(Base\Classe::hasMethod('parentPrivStat',$class));
			assert(Base\Classe::hasMethod('traiPrivDyn',$class));
			assert(Base\Classe::hasMethod('traiPubDyn',$interface));
			assert(Base\Classe::hasMethod('pubDyn',$interface));
			assert(Base\Classe::hasMethod('privDyn',$class));
			assert(Base\Classe::hasMethod("traiPrivDyn",$trait));

			// hasProperty
			assert(Base\Classe::hasProperty("config",Base\Dir::class));
			assert(!Base\Classe::hasProperty("config","\Quid\Waht"));
			assert(Base\Classe::hasProperty("pubStat",$class));
			assert(Base\Classe::hasProperty("parentTraiPubStat",$class));
			assert(!Base\Classe::hasProperty("parentTraiPrivStat",$class));
			assert(Base\Classe::hasProperty("parentTraiProStat",$class));
			assert(Base\Classe::hasProperty("traiPrivStat",$class));
			assert(Base\Classe::hasProperty("traiProStat",$class));
			assert(Base\Classe::hasProperty("parentPubDyn",$class));
			assert(Base\Classe::hasProperty("privStat",$class));
			assert(Base\Classe::hasProperty("privDyn",$class));
			assert(!Base\Classe::hasProperty("parentPrivStat",$class));
			assert(Base\Classe::hasProperty("parentProStat",$class));
			assert(Base\Classe::hasProperty("traiPrivStat",$trait));

			// hasInterface
			assert(!Base\Classe::hasInterface("test",Base\Finder::class));
			assert(!Base\Classe::hasInterface("test","bla"));
			assert(Base\Classe::hasInterface('DateTimeInterface',$datetime));
			assert(Base\Classe::hasInterface('DateTimeInterface',"Datetime"));
			assert(Base\Classe::hasInterface($interface,$class));
			assert(!Base\Classe::hasInterface($trait,$class));
			assert(Base\Classe::hasInterface($parentInterface,$class));

			// hasTrait
			assert(Base\Classe::hasTrait("Quid\Base\_option",['Quid','Base','Sql']));
			assert(Base\Classe::hasTrait("Quid\Base\_option","\Quid\Base\Sql"));
			assert(Base\Classe::hasTrait("Quid\Base\_option","Quid\Base\Sql"));
			assert(Base\Classe::hasTrait($trait,$class));
			assert(Base\Classe::hasTrait($parentTrai,$class));
			assert(!Base\Classe::hasTrait($trait,$interface));

			// hasNamespace
			assert(!Base\Classe::hasNamespace("Quid","Quid\What"));
			assert(!Base\Classe::hasNamespace("Quid","Quid\What\Bla"));
			assert(Base\Classe::hasNamespace("Quid\Base",Base\Finder::class));
			assert(Base\Classe::hasNamespace(Classe::class,$interface));
			assert(Base\Classe::hasNamespace(Classe::class,$class));
			assert(Base\Classe::hasNamespace(Classe::class,$interface));

			// inNamespace
			assert(!Base\Classe::inNamespace("Quid","Quid\What\Bla"));
			assert(!Base\Classe::inNamespace("Quid\What","Quid\What\Bla"));
			assert(Base\Classe::inNamespace("Quid\Base",Base\Finder::class));
			assert(Base\Classe::inNamespace("quid\BASE",Base\Finder::class));
			assert(Base\Classe::inNamespace("Quid",$class));

			// instance
			assert(Base\Classe::instance($class,$class,$class));
			assert(!Base\Classe::instance($class,$class,"DateTime"));
			assert(Base\Classe::instance($datetime,$datetime,"DateTime"));
			assert(Base\Classe::instance("DateTime",$datetime,"DateTime"));
			assert(!Base\Classe::instance($class,$interface,$interface,$datetime));
			assert(Base\Classe::instance($interface,$class,$class));
			assert(!Base\Classe::instance($class,$interface,$interface));
			assert(!Base\Classe::instance(Base\Dir::class,Base\Finder::class));
			assert(Base\Classe::instance(Base\Finder::class,Base\Finder::class));
			assert(Base\Classe::instance(Base\Finder::class,Base\Dir::class));
			assert(Base\Classe::instance("Datetime",$datetime));
			assert(Base\Classe::instance($datetime,"Datetime"));
			assert(Base\Classe::instance($trait,$trait));
			assert(Base\Classe::instance($interface,$interface));
			assert(!Base\Classe::instance($class,$trait));
			assert(!Base\Classe::instance($trait,$class));
			assert(!Base\Classe::instance($class,$interface));
			assert(Base\Classe::instance($interface,$class));

			// sameInterface
			assert(Base\Classe::sameInterface(Base\Finder::class,Base\Finder::class));
			assert(Base\Classe::sameInterface($class,$class,$class));
			assert(Base\Classe::sameInterface($datetime,$datetime));
			assert(!Base\Classe::sameInterface($class,$datetime));
			assert(Base\Classe::sameInterface("SplFileObject","SplTempFileObject","SplFileObject"));

			// sameNamespace
			assert(Base\Classe::sameNamespace(Base\Dir::class,Base\Finder::class));
			assert(Base\Classe::sameNamespace(Base\Finder::class,Base\Finder::class));
			assert(!Base\Classe::sameNamespace(\James::class,Base\Finder::class));
			
			// alias

			// aliases

			// storeAlias

			// get
			assert(Base\Classe::get($class) === $class);
			assert(Base\Classe::get($interface) === null);
			assert(Base\Classe::get(Classe::class."\ParentInter",true)  === Classe::class."\ParentInter");
			assert(Base\Classe::get(Classe::class."\ParentClass",true) === Classe::class."\ParentClass");

			// first
			assert(Base\Classe::first("James",Classe::class."\ParentInter",Classe::class."\ParentClass") === Classe::class."\ParentInter");
			assert(Base\Classe::first("James","James2") === null);

			// fqcn
			assert(Base\Classe::fqcn($class) === Classe::class.'\MyClass');
			assert(Base\Classe::fqcn($interface) === Classe::class.'\Inter');
			assert(Base\Classe::fqcn($trait) === Classe::class.'\Trai');
			assert(Base\Classe::fqcn("Quid\What") === null);

			// namespace
			assert(Base\Classe::namespace($class) === Classe::class);
			assert(Base\Classe::namespace($interface) === Classe::class);
			assert(Base\Classe::namespace($trait) === Classe::class);
			assert(Base\Classe::namespace("Quid\What") === null);
			assert(Base\Classe::namespace($anonymous) === null);
			
			// name
			assert(Base\Classe::name($class) === 'MyClass');
			assert(Base\Classe::name($interface) === 'Inter');
			assert(Base\Classe::name($trait) === 'Trai');
			assert(Base\Classe::name("Quid\What") === null);
			assert(is_string(Base\Classe::name($anonymous)));
			
			// type
			assert(Base\Classe::type($class) === 'class');
			assert(Base\Classe::type($interface) === 'interface');
			assert(Base\Classe::type($trait) === 'trait');
			assert(Base\Classe::type("Quid\What") === null);
			assert(Base\Classe::type($anonymous) === 'class');
			
			// parent
			assert(Base\Classe::parent($parentInterface) === null);
			assert(Base\Classe::parent($trait) === null);
			assert(Base\Classe::parent(Base\Dir::class) === Base\Finder::class);
			assert(Base\Classe::parent(Base\Finder::class) === Base\Root::class);
			assert(Base\Classe::parent("bla") === null);
			assert(Base\Classe::parent(new \LogicException("what")) === 'Exception');
			assert(Base\Classe::parent(new \LengthException("what")) === 'LogicException');

			// parents
			assert(Base\Classe::parents($parentInterface,false,null,false) === null);
			assert(Base\Classe::parents($trait,false,null,false) === null);
			assert(Base\Classe::parents($parentClass) === []);
			assert(count(Base\Classe::parents($class,true)) === 2);
			assert(count(Base\Classe::parents($class,true,1)) === 1);
			assert(count(Base\Classe::parents($class,true,2)) === 0);
			assert(Base\Classe::parents($class) === [$parentClass]);
			assert(Base\Classe::parents("\Quid\What") === null);
			assert(Base\Classe::parents(Base\Finder::class) === [Base\Root::class]);
			assert(Base\Classe::parents(Base\Dir::class) === [Base\Finder::class,Base\Root::class]);
			assert(count(Base\Classe::parents(new \LengthException("what"))) === 2);

			// top
			assert(Base\Classe::top(Base\Csv::class) === Base\Root::class);
			assert(Base\Classe::top(Base\Root::class) === Base\Root::class);

			// topParent
			assert(Base\Classe::topParent(Base\Csv::class) === Base\Root::class);
			assert(Base\Classe::topParent(Base\Root::class) === null);

			// methods
			assert(Base\Classe::methods("\Quid\What") === null);
			assert(count(Base\Classe::methods(Base\Dir::class)) > 40);
			assert(count(Base\Classe::methods($datetime)) > 10);
			assert(count(Base\Classe::methods($trait)) === 3);
			assert(count(Base\Classe::methods($interface)) === 2);
			assert(count(Base\Classe::methods($class)) === 11);

			// properties
			$class->parentPubDyn = ['test2'=>'bla'];
			assert(Base\Classe::properties($class)['parentPubDyn']['test2'] === 'bla');
			assert(empty(Base\Classe::properties($classname)['parentPubDyn']['test2']));
			assert(Base\Classe::properties($classname) !== Base\Classe::properties($class));
			assert(count(Base\Classe::properties(Base\Dir::class)) === 5);
			assert(Base\Classe::properties("\Quid\Waht") === null);
			assert(count(Base\Classe::properties($class)) === 8);
			assert(count(Base\Classe::properties($classname)) === 8);
			assert(count(Base\Classe::properties($parentClass)) === 4);
			assert(count(Base\Classe::properties($trait)) === 2);
			assert(count(Base\Classe::properties($parentTrai)) === 2);
			assert(Base\Classe::properties($interface) === null);
			assert(Base\Classe::properties($parentInterface) === null);

			// propertyMergeCallable
			assert(Base\Classe::propertyMergeCallable(null) === [Base\Arrs::class,'replace']);
			assert(Base\Classe::propertyMergeCallable(false) === [Base\Arr::class,'replace']);

			// propertyMerge
			assert(Base\Classe::propertyMerge('config',Base\Dir::class,['ok'=>'yeah'])['ok'] === 'yeah');
			unset(Base\Dir::$config['ok']);

			// propertyMergeWith
			assert(count(Base\Classe::propertyMergeWith('config',Base\Dir::class,[Base\Sql::class],null,false)) === 12);

			// propertyMergeParent
			assert(count(Base\Classe::propertyMergeParent('config',Base\Dir::class,null,false)) === 3);
			assert(Base\Classe::propertyMergeParent('james',Base\Dir::class,null,false) === []);
			assert(Base\Classe::propertyMergeParent('config',Base\Finder::class,null,false) === Base\Finder::$config);

			// propertyMergeParents
			assert(count(Base\Classe::propertyMergeParents('config',Base\Dir::class,null,false)) === 3);

			// interfaces
			assert(Base\Classe::interfaces("Quid\What") === null);
			assert(Base\Classe::interfaces(Base\Finder::class) === []);
			assert(Base\Classe::interfaces($datetime) === ['DateTimeInterface']);
			assert(count(Base\Classe::interfaces($class)) === 3);
			assert(count(Base\Classe::interfaces($classname)) === 3);
			assert(Base\Classe::interfaces($trait) === null);
			assert(count(Base\Classe::interfaces($interface)) === 2);
			assert(count(Base\Classe::interfaces($parentInterface)) === 0);

			// traits
			assert(Base\Classe::traits("Quid\What") === null);
			assert(Base\Classe::traits("\Quid\Base\Sql") === ["Quid\Base\_option",'Quid\Base\_shortcut','Quid\Base\_root']);
			assert(Base\Classe::traits($datetime) === []);
			assert(Base\Classe::traits("\Quid\Base\Sql",false) === ['Quid\Base\_option','Quid\Base\_shortcut']);
			assert(count(Base\Classe::traits($trait)) === 1);
			assert(count(Base\Classe::traits($class,true)) === 3);
			assert(count(Base\Classe::traits($class,false)) === 1);
			assert(Base\Classe::traits($interface) === null);

			// namespaces
			assert(Base\Classe::namespaces([Base\Finder::class,Base\Dir::class,'DateTime',"Quid\What\Bla",'QUid\What','Quid\What']) === ['Quid\Base','Quid\What','QUid']);

			// spl
			assert(count(Base\Classe::spl()) > 50);

			// declared
			$total = Base\Classe::declared('Quid\Base');
			assert(count($total) < 100);
			assert(count(Base\Classe::declared()) > 200);
			assert(count(Base\Classe::declared('Quid\Base',true,true)) > count($total));
			assert(count(Base\Classe::declared('Quid\Base',false,true)) > count($total));
			assert(count(Base\Classe::declared('Quid\Base',false,false)) > count($total));
			
			// overview
			assert(count(Base\Classe::overview(null,true)) === 4);
			assert(count(Base\Classe::overview("Quid")['class']) < 300);
			assert(count(Base\Classe::overview(['trait'=>'Quid\Base\_option'],true)['namespace']) >= 1);
			
			// total
			assert(count(Base\Classe::total('Quid\Base')) === 5);

			// filter
			assert(Base\Classe::filter(['interface'=>true],[$datetime,Base\Finder::class]) === ['DateTime']);
			assert(Base\Classe::filter(['interface'=>"DateTimeInterface"],["\DateTime",Base\Finder::class]) === ['\DateTime']);
			assert(Base\Classe::filter(['interface'=>"bla"],["DateTime",Base\Finder::class]) === []);
			assert(Base\Classe::filter(['interface'=>false],["DateTime",Base\Finder::class,"Bla"]) === [Base\Finder::class,'Bla']);
			assert(Base\Classe::filter(['trait'=>true],["Quid\Base\Sql","James"]) === ['Quid\Base\Sql']);
			assert(Base\Classe::filter(['trait'=>"asd"],["Quid\Base\Sql","James"]) === []);
			assert(Base\Classe::filter(['trait'=>false],[Base\Finder::class,"Quid\Base\Sql","James"]) === ['James']);
			assert(Base\Classe::filter("Quid",[["Quid\Test"],"Bla","Datetime","\Quid\James\Ok"]) === ['Quid\Test']);
			assert(Base\Classe::filter(['fqcn'=>"Quid"],[["Quid\Test"],"Bla","Datetime","\Quid\James\Ok"]) === ['Quid\Test','Quid\James\Ok']);
			assert(Base\Classe::filter(['namespace'=>"Quid\James"],["Quid\Test","Bla","Datetime","Quid\James\Ok"]) === ['Quid\James\Ok']);
			assert(Base\Classe::filter(['namespace'=>["Quid\James"]],["Quid\Test","Bla","Datetime","Quid\James\Ok"]) === ['Quid\James\Ok']);
			assert(Base\Classe::filter(['namespace'=>[""]],["Quid\Test","Bla","Datetime","Quid\James\Ok"]) === []);
			assert(Base\Classe::filter(false,[["Quid\Test"],"Bla","Datetime","\Quid\James\Ok"]) === ['Bla','Datetime']);
			assert(Base\Classe::filter(true,[["Quid\Test"],"Bla","Datetime","\Quid\James\Ok"]) === ['Quid\Test','Quid\James\Ok']);

			// info
			assert(Base\Classe::info("Quid\Whatz") === null);
			assert(count(Base\Classe::info($class)) === 9);
			assert(count(Base\Classe::info($trait)) === 7);
			assert(count(Base\Classe::info($interface)) === 6);

			// sort
			assert(Base\Classe::sort('sort',false,[$classname,$parentClass],1) === [1=>$parentClass,0=>$classname]);

			// sorts
			assert(Base\Classe::sorts([['sort',true,1]],[$classname,$parentClass]) === [0=>$classname,1=>$parentClass]);
			
			return true;
		}
	}
}

namespace Quid\Base\Test\Classe
{
	// ancienTrai
	trait AncienTrai
	{
		
	}

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

	// ParentInterExtra
	interface ParentInterExtra
	{
		// pubDyn
		public function pubDyn();
	}

	// parentInter
	interface ParentInter
	{
		
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
		
		
		// sort
		public static function sort($arg) 
		{
			return ($arg + 10);
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
		
		
		// sort
		public static function sort($arg) 
		{
			return ($arg + 5);
		}
	}
}
?>