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

// symlink
// class with static methods to manage symlinks
final class Symlink extends Finder
{
    // config
    protected static array $config = [];


    // is
    // retourne vrai si le chemin est un lien symbolique
    final public static function is($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = self::path($path);

        if(is_string($path) && is_link($path))
        $return = true;

        return $return;
    }


    // isReadable
    // retourne vrai si le chemin est un lien symbolique existant et lisible
    final public static function isReadable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = self::path($path);

        if(self::is($path,false) && self::isPermission('readable',$path))
        $return = true;

        return $return;
    }


    // isWritable
    // retourne vrai si le chemin est un lien symbolique existant et accessible en écriture
    final public static function isWritable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = self::path($path);

        if(self::is($path,false) && self::isPermission('writable',$path))
        $return = true;

        return $return;
    }


    // isExecutable
    // retourne vrai le chemin est un lien symbolique existant et éxécutable
    final public static function isExecutable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = self::path($path);

        if(self::is($path,false) && self::isPermission('executable',$path))
        $return = true;

        return $return;
    }


    // inode
    // retourne le numéro d'inode du symlink
    final public static function inode($path):?int
    {
        $return = null;
        $ino = self::statValue('ino',$path);

        if(is_int($ino))
        $return = $ino;

        return $return;
    }


    // permission
    // retourne la permission du symlink
    final public static function permission($path,bool $format=false)
    {
        $return = null;
        $mode = self::statValue('mode',$path);

        if(is_int($mode))
        $return = ($format === true)? self::formatValue('permission',$mode):$mode;

        return $return;
    }


    // owner
    // retourne l'identifiant du propriétaire du symlink
    final public static function owner($path,bool $format=false)
    {
        $return = null;
        $owner = self::statValue('uid',$path);

        if(is_int($owner))
        $return = ($format === true)? self::formatValue('owner',$owner):$owner;

        return $return;
    }


    // ownerChange
    // change le owner du symlink
    final public static function ownerChange($user,$path):bool
    {
        $return = false;
        $path = self::path($path);

        if(is_scalar($user) && self::isWritable($path,false) && lchown($path,$user))
        $return = true;

        return $return;
    }


    // group
    // retourne l'identifiant du groupe du symlink
    final public static function group($path,bool $format=false)
    {
        $return = null;
        $group = self::statValue('gid',$path);

        if(is_int($group))
        $return = ($format === true)? self::formatValue('group',$group):$group;

        return $return;
    }


    // groupChange
    // change le groupe du symlink
    final public static function groupChange($group,$path):bool
    {
        $return = false;
        $path = self::path($path);

        if(is_scalar($group) && self::isWritable($path,false) && lchgrp($path,$group))
        $return = true;

        return $return;
    }


    // size
    // retourne la taille du symlink
    final public static function size($path,bool $format=false)
    {
        $return = null;
        $size = self::statValue('size',$path);

        if(is_int($size))
        $return = ($format === true)? self::formatValue('size',$size):$size;

        return $return;
    }


    // dateAccess
    // retourne la dernière date d'accès du symlink
    final public static function dateAccess($path,bool $format=false)
    {
        $return = null;
        $atime = self::statValue('atime',$path);

        if(is_int($atime))
        $return = ($format === true)? self::formatValue('dateAccess',$atime):$atime;

        return $return;
    }


    // dateModify
    // retourne la date de modification du symlink
    final public static function dateModify($path,bool $format=false)
    {
        $return = null;
        $mtime = self::statValue('mtime',$path);

        if(is_int($mtime))
        $return = ($format === true)? self::formatValue('dateModify',$mtime):$mtime;

        return $return;
    }


    // dateInodeModify
    // retourne la date de modification de l'inode du symlink
    final public static function dateInodeModify($path,bool $format=false)
    {
        $return = null;
        $ctime = self::statValue('ctime',$path);

        if(is_int($ctime))
        $return = ($format === true)? self::formatValue('dateInodeModify',$ctime):$ctime;

        return $return;
    }


    // stat
    // retourne les informations stat du symlink
    // le chemin de symlink n'est pas suivi
    final public static function stat($path,bool $formatKey=false,bool $formatValue=false):?array
    {
        $return = null;
        $path = self::path($path);

        if(self::is($path,false))
        {
            $stat = lstat($path);

            if(is_array($stat))
            {
                $return = $stat;

                if($formatKey === true)
                $return = self::statReformat($return,$formatValue);
            }
        }

        return $return;
    }


    // info
    // étend la méthode info de finder
    // ajoute la target, enlève size
    final public static function info($path,bool $clearStatCache=false,bool $format=true):?array
    {
        $return = null;
        $path = self::path($path);
        $is = self::isReadable($path,false);

        if($is === true)
        {
            $return = [];

            if($clearStatCache === true)
            self::clearStatCache();

            $return['path'] = $path;
            $return['target'] = self::get($path);
            $return['type'] = filetype($path);
            $return['readable'] = $is;
            $return['writable'] = self::isWritable($path,false);
            $return['executable'] = self::isExecutable($path,false);
            $return['dir'] = is_dir($path);
            $return['file'] = is_file($path);
            $return['link'] = is_link($path);
            $return['pathinfo'] = Path::info($path);
            $return['stat'] = (array) self::stat($path,$format);
        }

        return $return;
    }


    // get
    // retourne le contenu d'un symlink
    final public static function get($path):?string
    {
        $return = null;
        $path = self::path($path);

        if(self::isReadable($path,false))
        {
            $link = readlink($path);

            if(is_string($link) && !empty($link))
            $return = self::normalize($link,false);
        }

        return $return;
    }


    // getStat
    // retourne le tableau stat du fichier ou directoire référencé, si existant
    // sinon retourne null
    final public static function getStat($path,bool $format=true):?array
    {
        $return = null;
        $path = self::get($path);

        if(!empty($path))
        $return = Finder::stat($path,$format);

        return $return;
    }


    // getInfo
    // retourne le tableau info du fichier ou directoire référencé, si le symlink existe
    // sinon retourne null
    final public static function getInfo($path,bool $format=true,bool $clearStatCache=false):?array
    {
        $return = null;
        $path = self::get($path);

        if(!empty($path))
        $return = Finder::info($path,$format,$clearStatCache);

        return $return;
    }


    // set
    // créer un nouveau lien symbolique si la target est lisible et que la destination est créable
    // ne remplace pas si existant par défaut (mais on peut mettre true)
    final public static function set($to,$from,bool $replace=false):bool
    {
        $return = false;
        $to = self::path($to);
        $from = self::path($from);

        if(is_string($to) && is_string($from))
        {
            if($replace === true && Finder::is($to,false))
            {
                if(is_link($to))
                self::unset($to);
                else
                self::unlink($to);
            }

            if(Finder::isReadable($from,false) && !Finder::is($to,false))
            {
                $dirname = Path::dirname($to);

                if(!empty($dirname) && Dir::setOrWritable($dirname))
                $return = symlink($from,$to);
            }
        }

        return $return;
    }


    // sets
    // permet de créer plusieurs symlinks, un tableau from->to doit être fourni
    // retourne un tableau multidimensionnel avec status et from
    // un status a null signifie que le symlink existe déjà (vers le bon chemin)
    // support pour catchAll, et dig si from et to sont des directoires
    final public static function sets(array $array,bool $replace=false,bool $dig=false):array
    {
        $return = [];

        foreach ($array as $from => $to)
        {
            $r = ['status'=>null,'from'=>$from];
            $from = self::normalize($from);
            $to = self::normalize($to);
            $get = self::get($to);
            $go = true;

            // symlink impossible, mais dig et deux directoires
            if($dig === true && $get === null && !self::is($to) && Dir::is($to) && Dir::is($from))
            {
                $go = false;
                $from = Path::append($from,'*');
                $catchAll = Dir::fromToCatchAll([$from=>$to]);

                if(!empty($catchAll))
                {
                    $sets = self::sets($catchAll,$replace);
                    $return = Arr::merge($return,$sets);
                }
            }

            // mauvais symlink
            elseif(is_string($get) && !Finder::is($get))
            {
                self::unset($to);
                $get = null;
            }

            if($go === true)
            {
                if($get !== $from)
                $r['status'] = self::set($to,$from,$replace);

                $return[$to] = $r;
            }
        }

        return $return;
    }


    // touch
    // touche un symlink, pour ce faire il faut le détruire et le recréer
    final public static function touch($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isWritable($path,false))
        {
            $target = self::get($path);
            if(!empty($target) && self::unset($path))
            $return = self::set($path,$target);
        }

        return $return;
    }


    // rename
    // renome un symlink
    // le symlink garde le même target après avoir été déplacé
    final public static function rename($target,$path):bool
    {
        $return = false;
        $target = self::path($target);
        $path = self::path($path);

        if(self::isWritable($path,false) && self::isCreatable($target))
        {
            $dirname = Path::dirname($target);
            if(!empty($dirname) && Dir::setOrWritable($dirname))
            $return = rename($path,$target);
        }

        return $return;
    }


    // copy
    // copy un symlink, pour ce faire il faut le recréer
    // le symlink garde la même target, il fait juste se créer à un nouvel endroit
    final public static function copy($to,$path):bool
    {
        $return = false;
        $to = self::path($to);
        $path = self::path($path);

        if(is_string($to) && is_string($path) && self::isReadable($path,false))
        {
            $target = self::get($path);
            if(!empty($target))
            $return = self::set($to,$target);
        }

        return $return;
    }


    // unset
    // efface un symlink, n'efface pas le fichier vers lequel il pointe
    // gestion d'un problème sous windows ou il faut utiliser rmdir si le symlink pointe vers un directoire
    final public static function unset($path):bool
    {
        $return = false;
        $path = self::path($path);

        if(self::isWritable($path,false))
        {
            if(Server::isWindows() && Dir::is($path))
            $return = rmdir($path);

            else
            $return = unlink($path);
        }

        return $return;
    }


    // reset
    // efface et recrée un symlink avec une nouvelle target
    final public static function reset($target,$path):bool
    {
        $return = false;
        $target = self::path($target);
        $path = self::path($path);

        if(is_string($target) && is_string($path))
        {
            self::unset($path);

            if(self::set($path,$target))
            $return = true;
        }

        return $return;
    }
}

// init
Symlink::__init();
?>