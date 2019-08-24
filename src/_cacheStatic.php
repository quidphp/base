<?php
declare(strict_types=1);
namespace Quid\Base;

// _cacheStatic
trait _cacheStatic
{
	// cacheStatic
	protected static $cacheStatic = []; // conserve les données de la cache statique, important de recréer la propriété dans chaque classe sinon la cache sera partagée
	
	
	// cacheStatic
	// retourne la cache si existante, sinon crée la cache à partir de la closure sans argument
	// si callable est null, unset
	public static function cacheStatic($key,?\Closure $closure=null,bool $cache=true)
	{
		$return = null;
		
		if($cache === true)
		{
			$key = Obj::cast($key);
			$key = Str::cast($key,'-');
			
			if(is_string($key) && strlen($key))
			{
				if($closure === null)
				Arr::unsetRef($key,static::$cacheStatic);
				
				else
				{
					if(Arr::keyExists($key,static::$cacheStatic))
					$return = Arr::get($key,static::$cacheStatic);
					
					else
					{
						$return = $closure();
						Arr::setRef($key,$return,static::$cacheStatic);
					}
				}
			}
		}
		
		elseif(!empty($closure))
		$return = $closure();
		
		return $return;
	}
	
	
	// emptyCacheStatic
	// vide le tableau de cache statique
	public static function emptyCacheStatic():bool
	{
		$return = true;
		static::$cacheStatic = [];
		
		return $return;
	}
	
	
	// allCacheStatic
	// retourne le tableau de la cache statique
	public static function allCacheStatic():array
	{
		return static::$cacheStatic;
	}
}
?>