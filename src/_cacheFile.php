<?php
declare(strict_types=1);
namespace Quid\Base;

// _cacheFile
trait _cacheFile
{
	// cacheFile
	protected static $cacheFile = null; // dirname pour le storage, peut être changé par classe mais il faut répéter cette propriété


	// cacheFile
	// retourne la cache si existante, sinon crée la cache à partir de la closure sans argument
	// si callable est null, unset
	public static function cacheFile($key,?\Closure $closure=null,bool $cache=true)
	{
		$return = null;

		if($cache === true)
		{
			$key = Obj::cast($key);
			$key = Str::cast($key,'-');

			if(is_string($key) && strlen($key))
			{
				$key = str_replace('.','_',$key);
				$key = Path::safeBasename($key);
				$storage = static::getCacheFileStorage();
				$path = Path::addBasename($key,$storage);

				if($closure === null)
				$return = File::unlink($path);

				else
				{
					if(File::isReadable($path))
					{
						$get = File::get($path);
						if(is_string($get))
						$return = Crypt::unserialize($get);
					}

					else
					{
						$return = $closure();
						$set = Crypt::serialize($return);
						File::set($path,$set);
					}
				}
			}
		}

		elseif(!empty($closure))
		$return = $closure();

		return $return;
	}


	// getCacheFileStorage
	// retourne le chemin du storage pour la classe
	// va envoyer une exception si cacheFile est toujours null
	public static function getCacheFileStorage():string
	{
		$return = null;

		if(is_string(static::$cacheFile))
		{
			$return = Finder::shortcut(static::$cacheFile);
			$class = str_replace('\\','',static::class);
			$return = Str::replace(['%class%'=>$class],$return);
		}

		return $return;
	}


	// setCacheFileStorage
	// permet de changer le chemin du storage pour la file cache
	public static function setCacheFileStorage(string $value):void
	{
		static::$cacheFile = $value;

		return;
	}


	// emptyCacheFile
	// efface tous les fichiers de cache pour la classe
	public static function emptyCacheFile():bool
	{
		return Dir::emptyAndUnlink(static::getCacheFileStorage());
	}


	// allCacheFile
	// retourne tous les fichiers de cache pour la classe
	public static function allCacheFile():array
	{
		return Dir::get(static::getCacheFileStorage());
	}
}
?>