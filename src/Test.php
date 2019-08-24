<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// test
abstract class Test extends Root
{
	// config
	public static $config = [];


	// start
	// lance les tests sur le fichier
	public static function start(?array $data=null)
	{
		$return = false;
		$data = (array) $data;

		static::before($data);
		$bool = static::trigger($data);
		static::after($data);

		if($bool === true)
		$return = static::count() ?? $bool;

		return $return;
	}


	// before
	// méthode appelé avant trigger
	public static function before(array $data):void
	{
		return;
	}


	// after
	// méthode appelé après trigger
	public static function after(array $data):void
	{
		return;
	}


	// trigger
	// méthode abstrait à implenter sur les classes qui étendent
	abstract public static function trigger(array $data):bool;


	// count
	// count le nombre d'assertions dans le fichier de test courant
	// peut retourner null si le fichier de la classe n'est pas trouvable
	public static function count():?int
	{
		$return = null;
		$file = static::classFile();

		if(!empty($file))
		$return = File::subCount('assert(',$file);

		return $return;
	}
}
?>