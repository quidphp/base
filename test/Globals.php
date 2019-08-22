<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// globals
class Globals extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		global $a,$b;
		$a = 'TEST';
		$b = 'TEST2';

		// is
		assert(Base\Globals::is('a'));
		assert(Base\Globals::is('b'));
		assert(!Base\Globals::is('c'));

		// get
		assert(Base\Globals::get('a') === 'TEST');
		assert(Base\Globals::get('c') === null);
		global $c;

		// all
		assert(count(Base\Globals::all()) > 4);

		// set
		assert(Base\Globals::set('2',21));
		assert(Base\Globals::get('2') === 21); // la globale $2 exite mais aucune façon d'aller chercher sa valeur
		assert(Base\Globals::set('c',2));
		assert($c === 2);
		assert(Base\Globals::get('c') === 2);
		$c = 3;
		assert(Base\Globals::get('c') === 3);
		Base\Globals::set('a',1);
		Base\Globals::set('b',3);
		assert($a === 1);

		// unset
		Base\Globals::unset('c');
		global $c;
		assert(empty($c));
		Base\Globals::unset('a','b');
		assert(!Base\Globals::is('b'));
		assert(!empty($b)); // la globale n'existe plus, mais la variable au niveau de la fonction existe toujours
		global $b; // en faisant ceci, tu réinitialises b à null dans le tableau des globales
		assert(empty($b));
		
		return true;
	}
}
?>