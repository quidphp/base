<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// finfo
// class with basic logics for managing the finfo extension
final class Finfo extends Root
{
    // config
    protected static array $config = [
        'flag'=>FILEINFO_MIME, // flag lors de l'ouverture
        'database'=>null // base de données utilisés
    ];


    // is
    // retourne vrai si la resource (php7) ou l'objet (php 8.1) est de type finfo
    final public static function is($value):bool
    {
        $return = false;

        if(is_a($value,\finfo::class,true))
        $return = true;

        elseif(is_resource($value) && Res::type($value) === 'file_info')
        $return = true;

        return $return;
    }


    // open
    // ouvre une resource ou objet finfo
    final public static function open(?int $flag=null,?string $database=null)
    {
        $flag ??= static::$config['flag'];
        $database ??= static::$config['database'];
        $args = [$flag];
        if(is_string($database))
        $args[] = $database;

        return finfo_open(...$args);
    }


    // read
    // lit le fichier et retourne le mime en string
    // retourne null en cas de problème
    final public static function read($finfo,string $value,?int $flag=null):?string
    {
        $return = null;

        if(self::is($finfo))
        {
            $flag ??= FILEINFO_NONE;
            $mime = finfo_file($finfo,$value,$flag);

            if(is_string($mime))
            $return = $mime;
        }

        return $return;
    }


    // close
    // ferme la resource finfo, n'a pas d'effet en php8
    final public static function close($value):bool
    {
        $return = false;

        if(self::is($value))
        $return = finfo_close($value);

        return $return;
    }
}
?>