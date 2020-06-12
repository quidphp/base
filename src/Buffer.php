<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Base;

// buffer
// class with methods a layer over the native PHP output buffering functions
final class Buffer extends Root
{
    // config
    protected static array $config = [];


    // has
    // retourne vrai s'il y a un buffer d'ouvert
    final public static function has():bool
    {
        return ob_get_level() > 0;
    }


    // count
    // retourne le nombre de buffer ouvert
    final public static function count():int
    {
        return ob_get_level();
    }


    // status
    // retourne les informations sur le ou les buffer
    final public static function status(bool $all=true):array
    {
        return ob_get_status($all);
    }


    // handler
    // retourne les informations sur les handlers du ou des buffer
    final public static function handler():array
    {
        return ob_list_handlers();
    }


    // size
    // retourne la taille du buffer courant
    final public static function size():?int
    {
        $return = ob_get_length();

        if(!is_int($return))
        $return = null;

        return $return;
    }


    // start
    // démarre un buffer, permet d'y joindre une fonction de rappel
    final public static function start(?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        return ob_start($callback,$chunk,$flag);
    }


    // startEcho
    // démarre un buffer et echo des données
    final public static function startEcho($data,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = ob_start($callback,$chunk,$flag);
        $data = Str::cast($data);
        echo $data;

        return $return;
    }


    // startCallGet
    // démarre un buffer, lance le callable, ferme le buffer et retourne les données
    final public static function startCallGet(callable $callable,array $arg=[],?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):?string
    {
        $return = null;
        self::start($callback,$chunk,$flag);
        $callable(...$arg);
        $return = self::getClean();

        return $return;
    }


    // get
    // retourne le contenu du niveau actuel de buffer, ne ferme pas le buffer
    final public static function get():?string
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
    final public static function getAll(bool $keep=true):string
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
    final public static function getClean():?string
    {
        return (ob_get_level())? ob_get_clean():null;
    }


    // getCleanAll
    // retourne le contenu de tous les buffers et ferme les buffer
    // par défaut les buffer sont retournés dans l'ordre inverse
    final public static function getCleanAll():array
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
    final public static function getCleanAllEcho(?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = false;

        $value = self::getCleanAll();
        $value = implode('',$value);
        $return = self::startEcho($value,$callback,$chunk,$flag);

        return $return;
    }


    // keepFlush
    // flush et vide un buffer s'il y en a un d'ouvert, le buffer reste ouvert
    final public static function keepFlush(bool $flush=true):void
    {
        if(ob_get_level())
        ob_flush();

        if($flush === true)
        flush();

        return;
    }


    // endFlush
    // flush le buffer et ferme le buffer
    final public static function endFlush(bool $flush=true):bool
    {
        $return = false;

        if(ob_get_level())
        $return = ob_end_flush();

        if($flush === true)
        flush();

        return $return;
    }


    // endFlushAll
    // flush les buffers et ferme les buffers
    final public static function endFlushAll(bool $flush=true):array
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
        flush();

        return $return;
    }


    // clean
    // vide un buffer, le buffer reste ouvert
    // note que ob_clean sur le dernier buffer envoie quand même le contenu dans la fonction callback même s'il le buffer ne ferme pas
    final public static function clean():bool
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
    final public static function cleanAll():array
    {
        $return = [];

        while ($level = ob_get_level())
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
    final public static function cleanEcho($value,bool $flush=false,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
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
        $return = self::startEcho($value,$callback,$chunk,$flag);

        if($flush === true)
        self::keepFlush();

        return $return;
    }


    // cleanAllEcho
    // clean les buffer ou ouvre un niveau si non existant
    // remplace le contenu du premier buffer par les données echo
    // ne ferme pas le buffer
    // possibilité de flush si flush est true (le buffer n'est pas fermé)
    final public static function cleanAllEcho($value,bool $flush=false,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = false;

        if(ob_get_level())
        {
            $return = true;
            self::cleanAll();

            if(is_array($value))
            $value = implode('',$value);

            echo $value;
        }
        else
        $return = self::startEcho($value,$callback,$chunk,$flag);

        if($flush === true)
        self::keepFlush();

        return $return;
    }


    // endClean
    // vide le buffer, ferme le buffer et rien d'afficher
    final public static function endClean():bool
    {
        return (ob_get_level())? ob_end_clean():false;
    }


    // endCleanAll
    // vide les buffer, ferme les buffer et rien d'afficher
    final public static function endCleanAll():array
    {
        $return = [];

        while (ob_get_level())
        {
            $return[] = ob_end_clean();
        }

        return $return;
    }


    // flush
    // flush les buffers et ferme les buffers
    // démarre un buffer, permet d'y joindre une fonction de rappel
    final public static function flush(bool $flush=true,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        self::endFlushAll($flush);

        return self::start($callback,$chunk,$flag);
    }


    // flushEcho
    // ouvre un buffer, echo des donnés et flush ferme tous les buffer
    // ouvre un autre buffer à la fin du processus
    final public static function flushEcho($value,bool $flush=true,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = self::startEcho($value,$callback,$chunk,$flag);
        self::flush($flush,$callback,$chunk,$flag);

        return $return;
    }


    // prependEcho
    // echo du contenu au début du buffer
    // les buffer sont applatis, ramenés à un niveau
    final public static function prependEcho($value,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = false;

        if(!ob_get_level())
        self::start($callback,$chunk,$flag);

        $buffer = self::getAll(false);

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
    final public static function appendEcho($value,?callable $callback=null,int $chunk=0,int $flag=PHP_OUTPUT_HANDLER_STDFLAGS):bool
    {
        $return = true;

        if(is_array($value))
        $value = implode('',$value);

        if(!ob_get_level())
        self::start($callback,$chunk,$flag);

        if(is_string($value))
        echo $value;

        return $return;
    }
}
?>