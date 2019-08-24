<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// buffer
class Buffer extends Root
{
	// config
	public static $config = [];


	// has
	// retourne vrai s'il y a un buffer d'ouvert
	public static function has():bool
	{
		return (ob_get_level() > 0)? true:false;
	}


	// count
	// retourne le nombre de buffer ouvert
	public static function count():int
	{
		return ob_get_level();
	}


	// status
	// retourne les informations sur le ou les buffer
	public static function status(bool $all=true):array
	{
		return ob_get_status($all);
	}


	// handler
	// retourne les informations sur les handlers du ou des buffer
	public static function handler():array
	{
		return ob_list_handlers();
	}


	// size
	// retourne la taille du buffer courant
	public static function size():?int
	{
		$return = ob_get_length();

		if(!is_int($return))
		$return = null;

		return $return;
	}


	// start
	// démarre un buffer, permet d'y joindre une fonction de rappel
	public static function start(?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		return ob_start($callback,$chunk,$flag);
	}


	// startEcho
	// démarre un buffer et echo des données
	public static function startEcho($data,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = ob_start($callback,$chunk,$flag);
		$data = Str::cast($data);
		echo $data;

		return $return;
	}


	// startCallGet
	// démarre un buffer, lance le callable, ferme le buffer et retourne les données
	public static function startCallGet(callable $callable,array $arg=[],?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):?string
	{
		$return = null;
		static::start($callback,$chunk,$flag);
		$callable(...$arg);

		$return = static::getClean();

		return $return;
	}


	// get
	// retourne le contenu du niveau actuel de buffer, ne ferme pas le buffer
	public static function get():?string
	{
		$return = ob_get_contents();

		if(!is_string($return))
		$return = null;

		return $return;
	}


	// getAll
	// retourne le contenu de tous les buffers en string
	// si keep est true, les données sont retournés et conservés dans un nouveau buffer
	// garde toujours le dernier buffer ouvert que keep soit true ou false
	// note que le ob_clean sur le dernier buffer envoie quand même le contenu dans la fonction callback même s'il le buffer ne ferme pas
	public static function getAll(bool $keep=true):string
	{
		$return = '';
		$buffer = [];

		while (($level = ob_get_level()))
		{
			if($level > 1)
			$buffer[] = ob_get_clean();

			elseif($level === 1)
			{
				$buffer[] = ob_get_contents();
				ob_clean();
				break;
			}

			else
			break;
		}

		if(!empty($buffer))
		{
			$buffer = array_reverse($buffer,true);
			$return = implode('',$buffer);

			if($keep === true)
			echo $return;
		}

		return $return;
	}


	// getClean
	// retourne le contenu du niveau actuel de buffer et ferme le buffer
	public static function getClean():?string
	{
		$return = null;

		if(ob_get_level())
		$return = ob_get_clean();

		return $return;
	}


	// getCleanAll
	// retourne le contenu de tous les buffers et ferme les buffer
	// par défaut les buffer sont retournés dans l'ordre inverse
	public static function getCleanAll():array
	{
		$return = [];

		while (ob_get_level())
		{
			$return[] = ob_get_clean();
		}

		if(!empty($return))
		$return = array_reverse($return,true);

		return $return;
	}


	// getCleanAllEcho
	// echo le contenu de getCleanAll dans un nouvel outbut buffer
	public static function getCleanAllEcho(?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = false;

		$value = static::getCleanAll();
		$value = implode('',$value);
		$return = static::startEcho($value,$callback,$chunk,$flag);

		return $return;
	}


	// flush
	// utilise la fonction flush
	public static function flush():void
	{
		flush();

		return;
	}


	// keepFlush
	// flush et vide un buffer s'il y en a un d'ouvert, le buffer reste ouvert
	public static function keepFlush(bool $flush=true):void
	{
		if(ob_get_level())
		ob_flush();

		if($flush === true)
		static::flush();

		return;
	}


	// endFlush
	// flush le buffer et ferme le buffer
	public static function endFlush(bool $flush=true):bool
	{
		$return = false;

		if(ob_get_level())
		$return = ob_end_flush();

		if($flush === true)
		static::flush();

		return $return;
	}


	// endFlushAll
	// flush les buffers et ferme les buffers
	public static function endFlushAll(bool $flush=true):array
	{
		$return = [];

		if(ob_get_level())
		{
			while (ob_get_level())
			{
				$return[] = ob_end_flush();
			}
		}

		if($flush === true)
		static::flush();

		return $return;
	}


	// endFlushAllStart
	// flush les buffers et ferme les buffers
	// démarre un buffer, permet d'y joindre une fonction de rappel
	public static function endFlushAllStart(bool $flush=true,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		static::endFlushAll($flush);

		return static::start($callback,$chunk,$flag);
	}


	// clean
	// vide un buffer, le buffer reste ouvert
	// note que ob_clean sur le dernier buffer envoie quand même le contenu dans la fonction callback même s'il le buffer ne ferme pas
	public static function clean():bool
	{
		$return = false;

		if(ob_get_level())
		{
			$return = true;
			ob_clean();
		}

		return $return;
	}


	// cleanAll
	// vide tous les buffer, le dernier buffer reste ouvert
	// note que ob_clean sur le dernier buffer envoie quand même le contenu dans la fonction callback même s'il le buffer ne ferme pas
	public static function cleanAll():array
	{
		$return = [];

		while (($level = ob_get_level()))
		{
			$return[$level] = true;

			if($level > 1)
			ob_end_clean();

			elseif($level === 1)
			{
				ob_clean();
				break;
			}

			else
			break;
		}

		return $return;
	}


	// cleanEcho
	// clean le buffer ou ouvre un niveau si non existant
	// remplace le contenu du buffer par les données echo
	// ne ferme pas le buffer
	// possibilité de flush si flush est true (le buffer n'est pas fermé)
	public static function cleanEcho($value,bool $flush=false,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = false;

		if(ob_get_level())
		{
			$return = true;
			ob_clean();

			if(is_array($value))
			$value = implode('',$value);

			echo $value;
		}
		else
		$return = static::startEcho($value,$callback,$chunk,$flag);

		if($flush === true)
		static::keepFlush();

		return $return;
	}


	// cleanAllEcho
	// clean les buffer ou ouvre un niveau si non existant
	// remplace le contenu du premier buffer par les données echo
	// ne ferme pas le buffer
	// possibilité de flush si flush est true (le buffer n'est pas fermé)
	public static function cleanAllEcho($value,bool $flush=false,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = false;

		if(ob_get_level())
		{
			$return = true;
			static::cleanAll();

			if(is_array($value))
			$value = implode('',$value);

			echo $value;
		}
		else
		$return = static::startEcho($value,$callback,$chunk,$flag);

		if($flush === true)
		static::keepFlush();

		return $return;
	}


	// endClean
	// vide le buffer, ferme le buffer et rien d'afficher
	public static function endClean():bool
	{
		$return = false;

		if(ob_get_level())
		$return = ob_end_clean();

		return $return;
	}


	// endCleanAll
	// vide les buffer, ferme les buffer et rien d'afficher
	public static function endCleanAll():array
	{
		$return = [];

		while (ob_get_level())
		{
			$return[] = ob_end_clean();
		}

		return $return;
	}


	// startEchoEndFlush
	// ouvre un buffer, echo des donnés et flush ferme le buffer
	public static function startEchoEndFlush($value,bool $flush=true,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = static::startEcho($value,$callback,$chunk,$flag);
		static::endFlush($flush);

		return $return;
	}


	// startEchoEndFlushAllStart
	// ouvre un buffer, echo des donnés et flush ferme tous les buffer
	// ouvre un autre buffer à la fin du processus
	public static function startEchoEndFlushAllStart($value,bool $flush=true,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = static::startEcho($value,$callback,$chunk,$flag);
		static::endFlushAllStart($flush,$callback,$chunk,$flag);

		return $return;
	}


	// prependEcho
	// echo du contenu au début du buffer
	// les buffer sont applatis, ramenés à un niveau
	public static function prependEcho($value,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = false;

		if(!ob_get_level())
		static::start($callback,$chunk,$flag);

		$buffer = static::getAll(false);

		if(is_array($value))
		$value = implode('',$value);

		if(is_string($buffer) && is_string($value))
		{
			$return = true;
			$buffer = $value.$buffer;
			echo $buffer;
		}

		return $return;
	}


	// appendEcho
	// echo du contenu à la fin du buffer
	public static function appendEcho($value,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
	{
		$return = true;

		if(is_array($value))
		$value = implode('',$value);

		if(!ob_get_level())
		static::start($callback,$chunk,$flag);

		if(is_string($value))
		echo $value;

		return $return;
	}
}
?>