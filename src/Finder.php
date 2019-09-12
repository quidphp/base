<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// finder
// class that provides methods to deal with the filesystem (used by Dir, File and Symlink)
class Finder extends Root
{
    // trait
    use _shortcut;


    // config
    public static $config = [
        'perms'=>[ // les numéros de permission valables selon le type d'accès
            'readable'=>[4,5,6,7],
            'writable'=>[2,3,6,7],
            'executable'=>[1,3,5,7]],
        'stat'=>[ // renomme les clés de stat
            'dev'=>'volume',
            'ino'=>'inode',
            'mode'=>'permission',
            'nlink'=>'linkAmount',
            'uid'=>'owner',
            'gid'=>'group',
            'rdev'=>'volumeType',
            'size'=>'size',
            'atime'=>'dateAccess',
            'mtime'=>'dateModify',
            'ctime'=>'dateInodeModify',
            'blksize'=>'blockSize',
            'blocks'=>'blockAmount']
    ];


    // host
    protected static $host = []; // tableau associatif entre host et chemin serveur, le même tableau est utilisé par toutes les classes


    // is
    // retourne vrai si le chemin existe
    // si makePath est true, passe à la méthode path
    public static function is($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = static::path($path);

        if(is_string($path) && file_exists($path))
        $return = true;

        return $return;
    }


    // isReadable
    // retourne vrai si le chemin existe et est lisible
    public static function isReadable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = static::path($path);

        if(static::is($path,false) && is_readable($path))
        $return = true;

        return $return;
    }


    // isWritable
    // retourne vrai si le chemin existe et est accessible en écriture
    public static function isWritable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = static::path($path);

        if(static::is($path,false) && is_writable($path))
        $return = true;

        return $return;
    }


    // isExecutable
    // retourne vrai le chemin est éxécutable
    public static function isExecutable($path,bool $makePath=true):bool
    {
        $return = false;

        if($makePath === true)
        $path = static::path($path);

        if(static::is($path,false) && is_executable($path))
        $return = true;

        return $return;
    }


    // isPathToUri
    // retourne vrai si le chemin peut être transformé en path
    public static function isPathToUri($value):bool
    {
        $return = false;
        $value = static::path($value);

        if(is_string($value))
        {
            $uri = static::pathToUri($value);

            if(is_string($uri))
            $return = true;
        }

        return $return;
    }


    // isUriToPath
    // retourne vrai si l'uri existe et est accessible
    public static function isUriToPath(string $value,?string $host=null):bool
    {
        $return = false;
        $uriPath = static::uriToPath($value,$host);

        if(static::is($uriPath))
        $return = true;

        return $return;
    }


    // isUriToPathReadable
    // retourne vrai si l'uri existe et est accessible en lecture
    public static function isUriToPathReadable(string $value,?string $host=null):bool
    {
        $return = false;
        $uriPath = static::uriToPath($value,$host);

        if(static::isReadable($uriPath))
        $return = true;

        return $return;
    }


    // isUriToPathWritable
    // retourne vrai si l'uri existe et est accessible en écriture
    public static function isUriToPathWritable(string $value,?string $host=null):bool
    {
        $return = false;
        $uriPath = static::uriToPath($value,$host);

        if(static::isWritable($uriPath))
        $return = true;

        return $return;
    }


    // isUriToPathExecutable
    // retourne vrai si l'uri existe et est éxécutable
    public static function isUriToPathExecutable(string $value,?string $host=null):bool
    {
        $return = false;
        $uriPath = static::uriToPath($value,$host);

        if(static::isExecutable($uriPath))
        $return = true;

        return $return;
    }


    // isCreatable
    // vérife que le chemin est créable
    // le chemin ne doit pas existé et le premier directoire parent trouvé doit être écrivable
    public static function isCreatable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path) && !self::is($path,false))
        {
            foreach (Path::parents($path) as $p)
            {
                if(Dir::isReadable($p,false))
                {
                    $return = Dir::isWritable($p,false);
                    break;
                }
            }
        }

        return $return;
    }


    // isReadableOrCreatable
    // vérifie que le chemin est lisible ou créable
    // retourne vrai si le chemin existe et qu'il est lisible ou si le chemin n'existe pas et qu'il est créable
    public static function isReadableOrCreatable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(static::isReadable($path,false) || self::isCreatable($path))
        $return = true;

        return $return;
    }


    // isWritableOrCreatable
    // vérifie que le chemin est writable ou créable
    // retourne vrai si le chemin existe et qu'il est écrivable ou si le chemin n'existe pas et qu'il est créable
    public static function isWritableOrCreatable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(static::isWritable($path,false) || self::isCreatable($path))
        $return = true;

        return $return;
    }


    // isDot
    // retourne vrai si le basename du chemin est un dot qui représente self ou parent
    // ne vérifie pas l'existence du chemin
    public static function isDot(string $path):bool
    {
        return (in_array(Path::basename(static::path($path)),['.','..'],true))? true:false;
    }


    // isVisible
    // retourne vrai si le chemin est visible
    // ne vérifie pas l'existence du chemin
    public static function isVisible($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            $basename = Path::basename($path);

            if(!empty($basename) && !Str::isStart('.',$basename))
            $return = true;
        }

        return $return;
    }


    // isHost
    // retourne vrai si l'host existe dans les paramètres de finder
    public static function isHost(string $value):bool
    {
        return (static::getHostPath($value) !== null)? true:false;
    }


    // isParentExists
    // vérifie que le parent direct de l'élement existe et est un directoire
    public static function isParentExists($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            $parent = Path::parent($path);

            if(!empty($parent) && Dir::is($parent,false))
            $return = true;
        }

        return $return;
    }


    // isParentReadable
    // vérifie que le parent direct de l'élement existe et est un directoire accessible en lecture
    public static function isParentReadable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            $parent = Path::parent($path);

            if(!empty($parent) && Dir::isReadable($parent,false))
            $return = true;
        }

        return $return;
    }


    // isParentWritable
    // vérifie que le parent direct de l'élement existe et est un directoire accessible en écriture
    public static function isParentWritable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            $parent = Path::parent($path);

            if(!empty($parent) && Dir::isWritable($parent,false))
            $return = true;
        }

        return $return;
    }


    // isParentExecutable
    // vérifie que le parent direct de l'élement existe et est un directoire éxécutable
    public static function isParentExecutable($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            $parent = Path::parent($path);

            if(!empty($parent) && Dir::isExecutable($parent,false))
            $return = true;
        }

        return $return;
    }


    // isParent
    // retourne vrai si la valeur parent est un sous-directoire existant dans le chemin de path
    // path n'a pas a existé
    public static function isParent($parent,$path):bool
    {
        $return = false;
        $parent = static::path($parent);
        $path = static::path($path);

        if(Dir::is($parent,false) && is_string($path))
        $return = Path::isParent($parent,$path);

        return $return;
    }


    // isPermission
    // vérifie s'il est possible d'accéder au fichier, symlink ou directoire en lecture, écriture ou éxécution
    // possibilité de spécifier un user ou un groupe, par défaut le user et groupe courant
    public static function isPermission(string $type,$path,$user=null,$group=null):bool
    {
        $return = false;

        if(array_key_exists($type,static::$config['perms']))
        {
            $permission = static::permission($path,true);

            if(is_array($permission))
            $permission = current($permission);

            if(is_int($permission) && Number::len($permission) === 3)
            {
                if(static::isOwner($path,$user))
                $permission = Number::sub(0,1,$permission);

                elseif(static::isGroup($path,$group))
                $permission = Number::sub(1,1,$permission);

                else
                $permission = Number::sub(2,1,$permission);

                if(is_int($permission) && in_array($permission,static::$config['perms'][$type],true))
                $return = true;
            }
        }

        return $return;
    }


    // isOwner
    // retourne vrai si l'utilisateur est propriétraire du fichier
    // si user est null, utilise l'utilisateur courant
    public static function isOwner($path,$user=null):bool
    {
        return Server::isOwner(static::owner($path),$user);
    }


    // isGroup
    // retourne vrai si le groupe est le même que le groupe du fichier
    // si group est null, utilise le groupe courant
    public static function isGroup($path,$group=null):bool
    {
        return Server::isGroup(static::group($path),$group);
    }


    // type
    // retourne le type du fichier, directoire ou symlink
    public static function type($path):?string
    {
        $return = null;
        $path = static::path($path);

        if(self::isReadable($path))
        {
            $type = filetype($path);
            if(is_string($type))
            $return = $type;
        }

        return $return;
    }


    // inode
    // retourne le numéro d'inode du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    public static function inode($path):?int
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $ino = fileinode($path);
            if(is_int($ino))
            $return = $ino;
        }

        return $return;
    }


    // permission
    // retourne la permission du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    // possibilité de formatter le retour
    public static function permission($path,bool $format=false)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $mode = fileperms($path);
            if(is_int($mode))
            $return = ($format === true)? static::formatValue('permission',$mode):$mode;
        }

        return $return;
    }


    // permissionFormat
    // format une valeur de permission, pour obtenir 3 chiffres comme 777 ou 755
    public static function permissionFormat(int $value):int
    {
        return Number::toOctal($value,2);
    }


    // permissionOctal
    // retourne la valeur octale d'une permission comme 777 ou 755
    public static function permissionOctal(int $value):int
    {
        return Number::fromOctal($value);
    }


    // permissionUmask
    // wrapper pour umask
    // permet de changer les permissions données par défaut lors de la création de nouveaux fichiers/dossiers
    public static function permissionUmask(?int $value=null):int
    {
        if(is_int($value))
        {
            $value = static::permissionOctal($value);
            $return = umask($value);
        }

        else
        $return = umask();

        return $return;
    }


    // permissionChange
    // change la permission du fichier ou directoire
    // il n'est pas possible de chmod un symlink en php
    public static function permissionChange($mode,$path):bool
    {
        $return = false;
        $path = static::path($path);

        if(static::isWritable($path,false) && is_int($mode))
        {
            $mode = static::permissionOctal($mode);

            if(is_int($mode) && chmod($path,$mode))
            $return = true;
        }

        return $return;
    }


    // owner
    // retourne l'identifiant du propriétaire du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    public static function owner($path)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        $return = fileowner($path);

        return $return;
    }


    // ownerChange
    // change le owner du fichier ou directoire
    // pour changer le owner d'un symlink une méthode étend celle-ci
    public static function ownerChange($user,$path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_scalar($user) && static::isWritable($path,false) && chown($path,$user))
        $return = true;

        return $return;
    }


    // group
    // retourne l'identifiant du groupe du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    public static function group($path)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        $return = filegroup($path);

        return $return;
    }


    // groupChange
    // change le groupe du fichier ou directoire
    // pour changer le groupe d'un symlink une méthode étend celle-ci
    public static function groupChange($group,$path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_scalar($group) && static::isWritable($path,false) && chgrp($path,$group))
        $return = true;

        return $return;
    }


    // size
    // retourne la taille du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    // possibilité de formatter le retour
    public static function size($path,bool $format=false)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $size = filesize($path);
            if(is_int($size))
            $return = ($format === true)? static::formatValue('size',$size):$size;
        }

        return $return;
    }


    // dateAccess
    // retourne la dernière date d'accès du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    // possibilité de formatter la date de retour en format sql
    public static function dateAccess($path,bool $format=false)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $atime = fileatime($path);
            if(is_int($atime))
            $return = ($format === true)? static::formatValue('dateAccess',$atime):$atime;
        }

        return $return;
    }


    // dateModify
    // retourne la date de modification du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    // possibilité de formatter la date de retour en format sql
    public static function dateModify($path,bool $format=false)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $mtime = filemtime($path);
            if(is_int($mtime))
            $return = ($format === true)? static::formatValue('dateModify',$mtime):$mtime;
        }

        return $return;
    }


    // dateInodeModify
    // retourne la date de modification de l'inode du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    // possibilité de formatter la date de retour en format sql
    public static function dateInodeModify($path,bool $format=false)
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $ctime = filectime($path);
            if(is_int($ctime))
            $return = ($format === true)? static::formatValue('dateInodeModify',$ctime):$ctime;
        }

        return $return;
    }


    // stat
    // retourne les informations stat du fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour interroger directement le symlink
    public static function stat($path,bool $formatKey=false,bool $formatValue=false):?array
    {
        $return = null;
        $path = static::path($path);

        if(static::isReadable($path,false))
        {
            $stat = stat($path);

            if(is_array($stat))
            {
                $return = $stat;

                if($formatKey === true)
                $return = static::statReformat($return,$formatValue);
            }
        }

        return $return;
    }


    // statValue
    // retourne une valeur du tableau de stat
    public static function statValue($key,$path,bool $formatKey=false,bool $formatValue=false)
    {
        $return = null;

        if(is_string($key) || is_numeric($key))
        {
            $stat = static::stat($path,$formatKey,$formatValue);
            if(!empty($stat) && array_key_exists($key,$stat))
            $return = $stat[$key];
        }

        return $return;
    }


    // statReformat
    // reformat un tableau stat, change le nom de certaines clés
    // les champs formatables sont également formattés
    public static function statReformat(array $stat,bool $format=false):array
    {
        $return = [];

        if(!empty($stat))
        {
            foreach ($stat as $key => $value)
            {
                if(is_string($key))
                {
                    if(array_key_exists($key,static::$config['stat']))
                    $key = static::$config['stat'][$key];

                    if($format === true)
                    $value = static::formatValue($key,$value);

                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }


    // clearStatCache
    // vide la cache de stat
    public static function clearStatCache(bool $clearRealPath=false,?string $filename=null):void
    {
        if($clearRealPath === true && !empty($filename))
        clearstatcache($clearRealPath,$filename);
        else
        clearstatcache($clearRealPath);

        return;
    }


    // info
    // retourne un tableau d'information complet sur le chemin
    // retourne null si le fichier n'est pas lisible
    // possibilité de vider la cache de stat
    // format est true par défaut
    public static function info($path,bool $format=true,bool $clearStatCache=false):?array
    {
        $return = null;
        $path = static::path($path);
        $is = static::isReadable($path,false);

        if($is === true && is_link($path))
        $path = Symlink::get($path);

        if($is === true && !empty($path))
        {
            $return = [];

            if($clearStatCache === true)
            static::clearStatCache();

            $return['path'] = $path;
            $return['type'] = filetype($path);
            $return['readable'] = $is;
            $return['writable'] = static::isWritable($path,false);
            $return['executable'] = static::isExecutable($path,false);
            $return['dir'] = is_dir($path);
            $return['file'] = is_file($path);
            $return['link'] = is_link($path);
            $return['size'] = filesize($path);
            $return['pathinfo'] = Path::info($path);
            $return['stat'] = (array) static::stat($path,$format,$format);
        }

        return $return;
    }


    // format
    // fait le format pour un path
    public static function formatPath(string $format,string $return,bool $extra=true)
    {
        if($format === 'permission')
        $return = static::permission($return,$extra);

        elseif($format === 'owner')
        $return = static::owner($return);

        elseif($format === 'group')
        $return = static::group($return);

        elseif($format === 'size')
        $return = static::size($return,$extra);

        elseif($format === 'dateAccess')
        $return = static::dateAccess($return,$extra);

        elseif($format === 'dateModify')
        $return = static::dateModify($return,$extra);

        elseif($format === 'dateInodeModify')
        $return = static::dateInodeModify($return,$extra);

        elseif($format === 'stat')
        $return = static::stat($return,$extra);

        elseif($format === 'info')
        $return = static::info($return,$extra,false);

        elseif($format === 'line')
        $return = File::lineCount($return);

        return $return;
    }


    // formatValue
    // fait le format pour la valeur directement
    public static function formatValue(string $format,int $return)
    {
        if($format === 'permission')
        $return = [$return=>static::permissionFormat($return)];

        elseif($return !== null)
        {
            if($format === 'size')
            $return = Number::sizeFormat($return);

            elseif($format === 'dateAccess')
            $return = Date::sql($return);

            elseif($format === 'dateModify')
            $return = Date::sql($return);

            elseif($format === 'dateInodeModify')
            $return = Date::sql($return);
        }

        return $return;
    }


    // touch
    // touche un fichier ou directoire et change les dates d'accès et de modification
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour toucher directement le symlink
    public static function touch($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(static::isWritable($path,false))
        $return = touch($path);

        return $return;
    }


    // rename
    // renomme un fichier ou dossier
    // si le chemin est un symlink, celui-ci est suivi et le lien vers le nouvel emplacement est recrée
    // il faut utiliser la même méthode dans la classe Symlink pour renommer directement le symlink
    // n'écrase pas si existant
    public static function rename($target,$path):bool
    {
        $return = false;
        $target = static::path($target);
        $path = static::path($path);

        if(is_string($target) && is_string($path))
        {
            if(is_link($path))
            {
                $symlink = $path;
                $path = Symlink::get($path);
            }

            if(static::isWritable($path,false) && self::isCreatable($target))
            {
                $dirname = Path::dirname($target);
                if(!empty($dirname) && Dir::setOrWritable($dirname))
                {
                    $return = rename($path,$target);

                    if($return === true && !empty($symlink))
                    Symlink::reset($target,$symlink);
                }
            }
        }

        return $return;
    }


    // changeDirname
    // renomme le dirname d'un fichier ou dossier, garde le basename
    // si le chemin est un symlink, celui-ci est suivi et le lien symbolique vers le nouvel emplacement est recée
    // il faut utiliser la même méthode dans la classe Symlink pour renommer directement le symlink
    // n'écrase pas si existant
    public static function changeDirname($dirname,$path):bool
    {
        $return = false;
        $dirname = static::path($dirname);
        $path = static::path($path);

        if(is_string($dirname) && is_string($path))
        {
            $basename = Path::basename($path);

            if(!empty($basename))
            {
                $target = Path::addBasename($basename,$dirname);
                $return = static::rename($target,$path);
            }
        }

        return $return;
    }


    // changeBasename
    // renomme le basename d'un fichier ou dossier, garde le dirname
    // si le chemin est un symlink, celui-ci est suivi et le lien symbolique vers le nouvel emplacement est recée
    // il faut utiliser la même méthode dans la classe Symlink pour renommer directement le symlink
    // n'écrase pas si existant
    // value, qui représente le basename, peut être une callable - à ce moment le basename est envoyé en argument
    public static function changeBasename($value,$path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            if(static::classIsCallable($value))
            {
                $basename = Path::basename($path);
                $value = $value($basename);
            }

            if(is_string($value))
            {
                $dirname = Path::dirname($path);

                if(!empty($dirname))
                {
                    $target = Path::addBasename($value,$dirname);
                    $return = static::rename($target,$path);
                }
            }
        }

        return $return;
    }


    // copy
    // copy un fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour copier directement le symlink
    public static function copy($to,$path)
    {
        $return = null;
        $to = static::path($to);
        $path = static::path($path);

        if(static::isReadable($path,false) && self::isCreatable($to))
        {
            $dirname = Path::dirname($to);
            if(!empty($dirname) && Dir::setOrWritable($dirname))
            {
                if(is_dir($path))
                $return = Dir::copy($to,$path);

                else
                $return = copy($path,$to);
            }
        }

        return $return;
    }


    // copyInDirname
    // copy un fichier ou link
    // garde le même dirname
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour copier directement le symlink
    // value, qui représente le basename, peut être une callable - à ce moment le basename est envoyé en argument
    public static function copyInDirname($value,$path)
    {
        $return = null;
        $path = static::path($path);

        if(is_string($path))
        {
            if(static::classIsCallable($value))
            {
                $basename = Path::basename($path);
                $value = $value($basename);
            }

            if(is_string($value))
            {
                $dirname = Path::dirname($path);

                if(!empty($dirname))
                {
                    $target = Path::addBasename($value,$dirname);
                    $return = static::copy($target,$path);
                }
            }
        }

        return $return;
    }


    // copyWithBasename
    // copy un fichier, directoire ou link
    // garde le même basename
    // si le chemin est un symlink, celui-ci est suivi
    // il faut utiliser la même méthode dans la classe Symlink pour copier directement le symlink
    public static function copyWithBasename($dirname,$path)
    {
        $return = null;
        $dirname = static::path($dirname);
        $path = static::path($path);

        if(is_string($dirname) && is_string($path))
        {
            $basename = Path::basename($path);

            if(!empty($basename))
            {
                $target = Path::addBasename($basename,$dirname);
                $return = static::copy($target,$path);
            }
        }

        return $return;
    }


    // unlink
    // efface un fichier ou directoire
    // si le chemin est un symlink, celui-ci est suivi et le symlink est aussi effacé si le pointeur a été effacé
    // il faut utiliser la méthode dans la classe Symlink pour effacer seulement le symlink
    // le dossier doit être vide pour être effacé, utiliser la méthode dans la classe Dir pour effacer et vider un dossier
    public static function unlink($path):bool
    {
        $return = false;
        $path = static::path($path);

        if(is_string($path))
        {
            if(is_link($path))
            {
                $symlink = $path;
                $path = Symlink::get($path);
            }

            if(static::isWritable($path,false))
            {
                if(is_dir($path))
                {
                    if(Dir::isEmpty($path) && rmdir($path))
                    $return = true;
                }

                elseif(unlink($path))
                $return = true;

                if($return === true && !empty($symlink))
                Symlink::unset($symlink);
            }
        }

        return $return;
    }


    // unlinkOnShutdown
    // permet d'effacer un fichier ou un directoire au shutdown s'il existe toujours
    public static function unlinkOnShutdown($path):void
    {
        Response::onShutdown(function($path) {
            static::unlink($path);
        },$path);

        return;
    }


    // unlinks
    // fait plusieurs appels à la méthodes unlink
    // retourne le nombre de fichiers effacés
    public static function unlinks(...$paths):int
    {
        $return = 0;

        foreach ($paths as $path)
        {
            if(static::unlink($path))
            $return++;
        }

        return $return;
    }


    // path
    // remplace des segments dans un path
    // is isSafe est vrai, vérifie que le chemin passe le regex safePath
    // si isReadable est vrai, vérifie que le chemin est lisible
    // pour segment, vérification que le chemin contient le premier caractère du délimiteur par défaut des segment
    public static function path($value,bool $isSafe=false,?array $option=null):?string
    {
        $return = null;

        if(is_string($value) && !Str::hasNullByte($value))
        {
            $return = static::normalize($value);

            if($isSafe === true && !Path::isSafe($return,$option))
            $return = null;
        }

        return $return;
    }


    // normalize
    // gère les shortcut et normalize le chemin au besoin (pour les paths windows)
    public static function normalize(string $return,bool $shortcut=true):string
    {
        if($shortcut === true)
        $return = static::shortcut($return);

        $return = Path::normalize($return);

        return $return;
    }


    // setShortcutMethod
    // remplacement de setShortcutMethod dans le trait shortcut
    public static function setShortcutMethod():callable
    {
        return [static::class,'normalize'];
    }


    // realpath
    // realpath en fonction du dossier courant
    // possibilité de changer le dossier courant
    public static function realpath(string $path='',?string $current=null):?string
    {
        $return = null;

        if($current === null || (is_string($current) && Dir::setCurrent($current)))
        $return = realpath($path);

        if(is_string($return))
        $return = static::normalize($return,false);

        if($return === false)
        $return = null;

        return $return;
    }


    // realpathCache
    // retourne la taille et le contenu de la cache realpath
    public static function realpathCache():array
    {
        return ['size'=>realpath_cache_size(),'cache'=>realpath_cache_get()];
    }


    // getHostPaths
    // retourne un tableau avec tous les chemins pour un host
    // il peut y avoir plusieurs chemins dans le cas d'un symlink
    public static function getHostPaths(string $value):?array
    {
        $return = null;
        $hosts = static::host();

        if(array_key_exists($value,$hosts))
        $return = $hosts[$value];

        else
        {
            $host = Uri::host($value);

            if(!empty($host) && array_key_exists($host,$hosts))
            $return = $hosts[$host];
        }

        if(is_string($return))
        $return = (array) $return;

        return $return;
    }


    // getHostPath
    // retourne le chemin d'un host paramétré ou null si non existant, par défaut le index est 0
    // input peut être un host ou une uri
    public static function getHostPath(string $value,int $index=0):?string
    {
        $return = null;
        $paths = static::getHostPaths($value);

        if(!empty($paths))
        $return = Arr::index($index,$paths);

        return $return;
    }


    // uriToPath
    // retourne le chemin d'une uri avec host paramétré ou null si non existant
    public static function uriToPath(string $value,?string $host=null):?string
    {
        $return = null;
        $host = ($host === null)? static::getHostPath($value):static::getHostPath($host);

        if(!empty($host))
        $return = Path::append($host,Uri::path($value));

        return $return;
    }


    // pathToUri
    // retourne l'uri absolut ou relative à partir d'un chemin serveur
    public static function pathToUri($value,?bool $absolute=null):?string
    {
        $return = null;
        $value = static::path($value);

        if(is_string($value))
        {
            foreach (static::host() as $host => $paths)
            {
                if(!is_array($paths))
                $paths = (array) $paths;

                foreach ($paths as $path)
                {
                    if(strpos($value,$path) === 0)
                    {
                        $return = Str::stripStart($path,$value);
                        $return = Uri::output($return,['schemeHost'=>$host,'absolute'=>$absolute]);

                        break 2;
                    }
                }
            }
        }

        return $return;
    }


    // host
    // fait un arr::replaceUnset sur le tableau des host
    // permet de retourner, ajouter, modifier et enlever des host en une méthode
    public static function host(?array $array=null):array
    {
        return (is_array($array))? (static::$host = Arr::replaceCleanNull(static::$host,$array)):static::$host;
    }


    // umaskGroupWritable
    // change les permissions par défaut selon si le group doit avoir accès en écriture
    // si write est true, fichier change 644 pour 664
    // si write est true, directoire change 755 pour 775
    public static function umaskGroupWritable(bool $write=false):void
    {
        if($write === true)
        {
            File::setDefaultPermission(664,true);
            Dir::setDefaultPermission(775);
        }

        else
        {
            File::setDefaultPermission(644,true);
            Dir::setDefaultPermission(755);
        }

        return;
    }


    // concatenateDirFileString
    // permet de faire une concatenation de plusieurs dossiers et/ou fichiers
    // retourne une string
    public static function concatenateDirFileString(string $separator=PHP_EOL,$extension=null,...$values):?string
    {
        $return = null;

        if(!empty($values))
        {
            foreach ($values as $value)
            {
                if(is_string($value))
                {
                    $res = [];

                    if(Dir::is($value))
                    $res = Dir::getExtension($value,$extension);

                    elseif(File::is($value))
                    $res[] = $value;

                    if(!empty($res))
                    {
                        $append = File::concatenateString($separator,...$res);

                        if(is_string($append))
                        {
                            $return .= (is_string($return) && strlen($return))? $separator:'';
                            $return .= $append;
                        }
                    }
                }
            }
        }

        return $return;
    }


    // phpExtension
    // retourne l'extension de php
    public static function phpExtension():string
    {
        return 'php';
    }
}
?>