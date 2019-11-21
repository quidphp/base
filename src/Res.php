<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Base;

// res
// class with static methods to create and modify resources of all types
class Res extends Root
{
    // config
    public static $config = [
        'binary'=>'b', // caractère pour représenter un mode binaire
        'readable'=>['r','r+','w+','a+','x+','c+'], // mode de stream readable
        'writable'=>['r+','w','w+','a','a+','x','x+','c','c+'], // mode de resource writable
        'creatable'=>['c+','w','w+'],
        'lineSeparatorLength'=>300, // longueur utilisé pour tenter de trouver le séparateur de ligne
        'base64'=>[ // si convert est true dans la méthode base64, converti un mime en un autre
            'image/svg'=>'image/svg+xml'],
        'phpStream'=>[ // paramètre par défaut pour stream php
            'mime'=>'txt',
            'basenameLength'=>10],
        'openKind'=>[ // kind de resource qui se détermine selon le nom
            'finfo'=>'finfo',
            'file_info'=>'finfo',
            'context'=>'context',
            'curl'=>'curl',
            'php://output'=>'phpOutput',
            'php://input'=>'phpInput',
            'php://temp'=>'phpTemp',
            'php://memory'=>'phpMemory'],
        'mode'=>[ // mode pour l'ouverture des resources
            'read'=>'r',
            'readWrite'=>'r+',
            'readWriteCreate'=>'c+',
            'writeCreate'=>'a',
            'writeTruncateCreate'=>'w',
            'readWriteTruncateCreate'=>'w+'],
        'curl'=>[ // option par défaut pour curl
            'timeout'=>10,
            'dnsGlobalCache'=>false,
            'userPassword'=>null,
            'proxyHost'=>null,
            'proxyPort'=>8080,
            'proxyPassword'=>null,
            'followLocation'=>false,
            'ssl'=>null,
            'port'=>null,
            'sslCipher'=>null,
            'userAgent'=>null,
            'postJson'=>false]
    ];


    // is
    // retourne vrai si la variable est une resource
    final public static function is($value):bool
    {
        return (is_resource($value))? true:false;
    }


    // isEmpty
    // retourne vrai si la resource est vide
    // size peut être calculer à partir de stat ou d'un header d'une requête http
    // une ressource directoire retourne false, car ce n'est pas possible d'obtenir sa taille via size
    final public static function isEmpty($value):bool
    {
        $return = false;

        if(is_resource($value))
        {
            $kind = static::kind($value);

            if($kind === 'dir')
            $return = (static::readDir(null,1,$value,['dot'=>false]) === null)? true:false;

            else
            $return = empty(static::size($value))? true:false;
        }

        return $return;
    }


    // isNotEmpty
    // retourne vrai si la resource n'est pas vide
    // size peut être calculer à partir de stat ou d'un header d'une requête http
    // une ressource directoire retourne false, car ce n'est pas possible d'obtenir sa taille via size
    final public static function isNotEmpty($value):bool
    {
        return (static::isEmpty($value))? false:true;
    }


    // isReadable
    // retourne vrai si le flux est accessible en lecture
    final public static function isReadable($value):bool
    {
        $return = false;
        $mode = static::mode($value,true);

        if(is_string($mode) && in_array($mode,static::$config['readable'],true))
        $return = true;

        return $return;
    }


    // isWritable
    // retourne vrai si le flux est accessible en écriture
    final public static function isWritable($value):bool
    {
        $return = false;
        $mode = static::mode($value,true);

        if(is_string($mode) && in_array($mode,static::$config['writable'],true))
        $return = true;

        return $return;
    }


    // isBinary
    // retourne vrai si le mode du flux est binaire
    final public static function isBinary($value):bool
    {
        $return = false;
        $mode = static::mode($value);

        if(is_string($mode) && strpos($mode,static::$config['binary']) !== false)
        $return = true;

        return $return;
    }


    // isStream
    // retourne vrai si la resource est de type stream
    final public static function isStream($value):bool
    {
        return (static::type($value) === 'stream')? true:false;
    }


    // isRegularType
    // retourne vrai si le type de la resource est régulier
    // pas curl ni file_info
    final public static function isRegularType($value):bool
    {
        $return = false;
        $type = static::type($value);

        if(is_string($type) && !in_array($type,['curl','file_info'],true))
        $return = true;

        return $return;
    }


    // isCurl
    // retourne vrai si la resource est de type curl
    final public static function isCurl($value):bool
    {
        return (static::type($value) === 'curl')? true:false;
    }


    // isFinfo
    // retourne vrai si la resource est de type finfo
    final public static function isFinfo($value):bool
    {
        return (static::type($value) === 'file_info')? true:false;
    }


    // isContext
    // retourne vrai si la resource est de type stream context
    final public static function isContext($value):bool
    {
        return (static::type($value) === 'stream-context')? true:false;
    }


    // isStreamMetaFile
    // retourne vrai si le tableau de meta représente une ressource fichier
    final public static function isStreamMetaFile($value):bool
    {
        $return = false;

        if(is_array($value) && !empty($value['stream_type']) && $value['stream_type'] === 'STDIO')
        $return = true;

        return $return;
    }


    // isFile
    // retourne vrai si la resource est de kind fichier
    // ne vérifie pas l'existence
    final public static function isFile($value):bool
    {
        $return = false;

        if(static::isStream($value))
        {
            $meta = stream_get_meta_data($value);

            if(static::isStreamMetaFile($meta))
            $return = true;
        }

        return $return;
    }


    // isFileExists
    // retourne vrai si la resource est de kind fichier et existe
    final public static function isFileExists($value):bool
    {
        $return = static::isFile($value);

        if($return === true)
        {
            $path = static::uriRemoveScheme($value);

            if(is_string($path) && File::is($path))
            $return = true;
        }

        return $return;
    }


    // isFileLike
    // retourne vrai si la resource est fileLike, c'est à dire file, phpMemory ou phpTemp
    final public static function isFileLike($value):bool
    {
        return (static::isFileExists($value) || static::isPhpMemory($value) || static::isPhpTemp($value))? true:false;
    }


    // isFileUploaded
    // retourne vrai si la resource est un fichier uploadé
    final public static function isFileUploaded($value):bool
    {
        return (File::isUploaded($value))? true:false;
    }


    // isFileVisible
    // retourne vrai si la resource est un fichier visible
    final public static function isFileVisible($value):bool
    {
        return (File::isVisible($value))? true:false;
    }


    // isFilePathToUri
    // retourne vrai si le chemin de la resource peut être transformé en uri
    final public static function isFilePathToUri($value):bool
    {
        return (File::isPathToUri($value))? true:false;
    }


    // isFileParentExists
    // vérifie que le parent direct de la resource fichier existe et est un directoire
    final public static function isFileParentExists($value):bool
    {
        return (File::isVisible($value))? true:false;
    }


    // isFileParentReadable
    // vérifie que le parent direct de la resource fichier existe et est un directoire accessible en lecture
    final public static function isFileParentReadable($value):bool
    {
        return (File::isParentReadable($value))? true:false;
    }


    // isFileParentWritable
    // vérifie que le parent direct de la resource fichier existe et est un directoire accessible en écriture
    final public static function isFileParentWritable($value):bool
    {
        return (File::isParentWritable($value))? true:false;
    }


    // isFileParentExecutable
    // vérifie que le parent direct de la resource fichier existe et est un directoire éxécutable
    final public static function isFileParentExecutable($value):bool
    {
        return (File::isParentExecutable($value))? true:false;
    }


    // isDir
    // retourne vrai si la resource est de kind directoire
    final public static function isDir($value):bool
    {
        return (static::streamType($value) === 'dir')? true:false;
    }


    // isHttp
    // retourne vrai si la resource est de kind http
    final public static function isHttp($value):bool
    {
        return (static::wrapperType($value) === 'http')? true:false;
    }


    // isPhp
    // retourne vrai si la resource est une des ressources PHP
    final public static function isPhp($value):bool
    {
        return (static::wrapperType($value) === 'PHP')? true:false;
    }


    // isPhpWritable
    // retourne vrai si le stream est de type php et écrivable
    final public static function isPhpWritable($value):bool
    {
        return (static::isPhp($value) && static::streamType($value) !== 'Input')? true:false;
    }


    // isPhpInput
    // retourne vrai si la resource est de kind PHP input
    final public static function isPhpInput($value):bool
    {
        return (static::isPhp($value) && static::streamType($value) === 'Input')? true:false;
    }


    // isPhpOutput
    // retourne vrai si la resource est de kind PHP output
    final public static function isPhpOutput($value):bool
    {
        return (static::isPhp($value) && static::streamType($value) === 'Output')? true:false;
    }


    // isPhpTemp
    // retourne vrai si la resource est de kind PHP temp
    final public static function isPhpTemp($value):bool
    {
        return (static::isPhp($value) && static::streamType($value) === 'TEMP')? true:false;
    }


    // isPhpMemory
    // retourne vrai si la resource est de kind PHP memory
    final public static function isPhpMemory($value):bool
    {
        return (static::isPhp($value) && static::streamType($value) === 'MEMORY')? true:false;
    }


    // isResponsable
    // retourne vrai si la resource peut être utiliser dans une réponse
    // les flux fichiers et http lisibles retournent vrai
    final public static function isResponsable($value):bool
    {
        return (static::isPhpWritable($value) || (static::isReadable($value) && (static::isFileExists($value) || static::isHttp($value))))? true:false;
    }


    // isLocal
    // retourne vrai si la resource est local
    final public static function isLocal($value):bool
    {
        return (static::canLocal($value) && stream_is_local($value))? true:false;
    }


    // isRemote
    // retourne vrai si la resource est remote
    final public static function isRemote($value):bool
    {
        return (static::canLocal($value) && !stream_is_local($value))? true:false;
    }


    // isTimedOut
    // retourne vrai si le flux a atteint le délai d'expiration
    final public static function isTimedOut($value):bool
    {
        return (static::metaValue('timed_out',$value) === true)? true:false;
    }


    // isBlocked
    // retourne vrai si le flux est en mode bloquant
    final public static function isBlocked($value):bool
    {
        return (static::metaValue('blocked',$value) === true)? true:false;
    }


    // isSeekable
    // retourne vrai si on peut rechercher dans le flux courant via le pointeur
    final public static function isSeekable($value):bool
    {
        return (static::metaValue('seekable',$value) === true)? true:false;
    }


    // isSeekableTellable
    // retourne vrai si on peut rechercher dans le flux courant via le pointeur et si la resource peut retourner la bonne position via ftell
    // ceci exclut donc les directoires
    final public static function isSeekableTellable($value):bool
    {
        return (!static::isDir($value) && static::metaValue('seekable',$value) === true)? true:false;
    }


    // isLockable
    // retourne vrai si la ressource peut être barré et débarré par flock
    final public static function isLockable($value):bool
    {
        return (static::isFileExists($value))? true:false;
    }


    // isStart
    // retourne vrai si le pointeur du flux est au début
    // la resource doit être seekable et ne fonctionne pas avec les dir
    final public static function isStart($value):bool
    {
        return (static::position($value) === 0)? true:false;
    }


    // isEnd
    // retourne vrai si le pointeur du flux est à la fin
    // la resource doit être seekable et ne fonctionne pas avec les dir
    final public static function isEnd($value):bool
    {
        return (is_resource($value) && feof($value))? true:false;
    }


    // canStat
    // retourne vrai si la resource supporte les appels à stat
    final public static function canStat($value):bool
    {
        return static::isFileLike($value);
    }


    // canLocal
    // retourne vrai si la resource supporte les appels à stream_is_local
    final public static function canLocal($value):bool
    {
        return (static::is($value) && !static::isContext($value) && static::isRegularType($value))? true:false;
    }


    // canMeta
    // retourne vrai si la resource supporte les appels à stream_get_meta
    final public static function canMeta($value):bool
    {
        return (static::is($value) && !static::isContext($value) && static::isRegularType($value))? true:false;
    }


    // canContext
    // retourne vrai si la resource supporte les appels à stream_context
    final public static function canContext($value):bool
    {
        return (static::is($value) && static::isRegularType($value))? true:false;
    }


    // hasScheme
    // retourne vrai si la resource a un scheme dans son uri
    // attention: certains types de resources, comme file peuvent être fonctionnelles sans avoir de scheme
    final public static function hasScheme($value):bool
    {
        return (static::scheme($value) !== null)? true:false;
    }


    // hasExtension
    // retourne vrai si la resource a une extension dans son uri
    final public static function hasExtension($value):bool
    {
        return (static::extension($value) !== null)? true:false;
    }


    // isScheme
    // retourne vrai si la resource a le scheme spécifié dans son uri
    // attention: certains types de resources, comme file peuvent être fonctionnelles sans avoir de scheme
    final public static function isScheme(string $target,$value):bool
    {
        return (!empty($target) && static::scheme($value) === $target)? true:false;
    }


    // isExtension
    // retourne vrai si la resource a l'extension spécifié dans son uri
    final public static function isExtension($target,$value):bool
    {
        $return = false;

        if(is_string($target) || is_array($target))
        {
            $extension = static::extension($value);

            if(!empty($extension) && in_array($extension,(array) $target,true))
            $return = true;
        }

        return $return;
    }


    // isMimeGroup
    // retourne vrai si le mime type est du group spécifé
    final public static function isMimeGroup($group,$value,bool $fromPath=true):bool
    {
        return (static::is($value))? Mime::isGroup($group,$value,true,$fromPath):false;
    }


    // isMimeFamily
    // retourne vrai si le mime type est de la famille spécifé
    final public static function isMimeFamily($family,$value,bool $fromPath=true):bool
    {
        return (static::is($value))? Mime::isFamily($family,$value,true,$fromPath):false;
    }


    // isFilePermission
    // vérifie s'il est possible d'accéder à la resource fichier en lecture, écriture ou éxécution
    // possibilité de spécifier un user ou un groupe, par défaut le user et groupe courant
    final public static function isFilePermission(string $type,$value,$user=null,$group=null):bool
    {
        return File::isPermission($type,$value,$user,$group);
    }


    // isOwner
    // retourne vrai si l'utilisateur est propriétraire de la resource
    // si user est null, utilise l'utilisateur courant
    final public static function isOwner($value,$user=null):bool
    {
        return Server::isOwner(static::owner($value),$user);
    }


    // isGroup
    // retourne vrai si le groupe est le même que le groupe du fichier
    // si group est null, utilise le groupe courant
    final public static function isGroup($value,$group=null):bool
    {
        return Server::isGroup(static::group($value),$group);
    }


    // stat
    // retourne les informations stat de la resource
    // possibilité de reformater via la méthode dans finder
    final public static function stat($value,bool $formatKey=false,bool $formatValue=false):?array
    {
        $return = null;

        if(static::canStat($value))
        {
            $stat = fstat($value);

            if(is_array($stat))
            {
                $return = $stat;

                if($formatKey === true)
                $return = Finder::statReformat($return,$formatValue);
            }
        }

        return $return;
    }


    // statValue
    // retourne une valeur du tableau de stat
    final public static function statValue($key,$value,bool $formatKey=false,bool $formatValue=false)
    {
        $return = null;

        if(is_string($key) || is_numeric($key))
        {
            $stat = static::stat($value,$formatKey,$formatValue);
            if(!empty($stat) && array_key_exists($key,$stat))
            $return = $stat[$key];
        }

        return $return;
    }


    // inode
    // retourne le numéro d'inode de la resource
    final public static function inode($value):?int
    {
        return static::statValue('inode',$value,true);
    }


    // permission
    // retourne la permission de la resource
    final public static function permission($value,bool $format=false)
    {
        return static::statValue('permission',$value,true,$format);
    }


    // owner
    // retourne l'identifiant du propriétaire de la resource
    final public static function owner($value)
    {
        return static::statValue('owner',$value,true);
    }


    // group
    // retourne l'identifiant du groupe de la resource
    final public static function group($value)
    {
        return static::statValue('group',$value,true);
    }


    // permissionChange
    // change la permission de la resource fichier
    final public static function permissionChange($mode,$path):bool
    {
        return File::permissionChange($mode,$path);
    }


    // ownerChange
    // change le owner de la resource fichier
    final public static function ownerChange($user,$path):bool
    {
        return File::ownerChange($user,$path);
    }


    // groupChange
    // change le groupe de la resource fichier
    final public static function groupChange($group,$path):bool
    {
        return File::groupChange($group,$path);
    }


    // dateAccess
    // retourne la dernière date d'accès de la resource
    final public static function dateAccess($value,bool $format=false)
    {
        return static::statValue('dateAccess',$value,true,$format);
    }


    // dateModify
    // retourne la date de modification de la resource
    final public static function dateModify($value,bool $format=false)
    {
        return static::statValue('dateModify',$value,true,$format);
    }


    // dateInodeModify
    // retourne la date de modification de l'inode de la resource
    final public static function dateInodeModify($value,bool $format=false)
    {
        return static::statValue('dateInodeModify',$value,true,$format);
    }


    // info
    // retourne un tableau d'informations maximal sur la resource
    final public static function info($value,bool $format=true,bool $clearStatCache=false):?array
    {
        $return = null;

        if(is_resource($value))
        {
            $return['type'] = get_resource_type($value);
            $return['kind'] = static::kind($value);

            if($clearStatCache === true)
            Finder::clearStatCache();

            $return['uri'] = static::uri($value);
            $return['path'] = static::path($value);
            $return['mode'] = static::mode($value);
            $return['readable'] = static::isReadable($value);
            $return['writable'] = static::isWritable($value);
            $return['isLocal'] = static::isLocal($value);
            $return['responsable'] = static::isResponsable($value);
            $return['parse_url'] = static::parse($value);
            $return['mime'] = static::mime($value);
            $return['mimeGroup'] = static::mimeGroup($value);
            $return['mimeFamilies'] = static::mimeFamilies($value);
            $return['size'] = static::size($value);
            $return['pathinfo'] = static::pathinfo($value);
            $return['stat'] = static::stat($value,$format,$format);
            $return['meta'] = static::meta($value);
            $return['option'] = static::contextOption($value);
        }

        return $return;
    }


    // responseMeta
    // retourne les informations nécessaires à une réponse
    // retourne null si une des valeurs est manquantes
    final public static function responseMeta($value,bool $contextOption=true):?array
    {
        $return = null;

        if(static::isResponsable($value))
        {
            $kind = static::kind($value);
            $basename = static::basename($value,$contextOption);
            $mime = static::mime($value,true,$contextOption);
            $size = static::size($value);

            if(!empty($kind) && !empty($basename) && !empty($mime))
            {
                $return['kind'] = $kind;
                $return['basename'] = $basename;
                $return['mime'] = $mime;
                $return['size'] = $size;
            }
        }

        return $return;
    }


    // type
    // retourne le type de la resource
    final public static function type($value):?string
    {
        $return = null;

        if(is_resource($value))
        $return = get_resource_type($value);

        return $return;
    }


    // kind
    // retourne le kind de la resource
    final public static function kind($value):?string
    {
        $return = null;
        $meta = static::meta($value);

        if(!empty($meta) && array_key_exists('stream_type',$meta) && array_key_exists('wrapper_type',$meta))
        {
            $wrapperType = $meta['wrapper_type'];
            $streamType = $meta['stream_type'];

            // dir
            if($streamType === 'dir')
            $return = 'dir';

            // file
            elseif(static::isStreamMetaFile($meta))
            $return = 'file';

            // http
            elseif($wrapperType === 'http')
            $return = 'http';

            elseif($wrapperType === 'PHP')
            {
                // phpOutput
                if($streamType === 'Output')
                $return = 'phpOutput';

                // phpInput
                elseif($streamType === 'Input')
                $return = 'phpInput';

                // phpTemp
                elseif($streamType === 'TEMP')
                $return = 'phpTemp';

                // phpMemory
                elseif($streamType === 'MEMORY')
                $return = 'phpMemory';
            }
        }

        elseif(static::isContext($value))
        $return = 'context';

        elseif(static::isCurl($value))
        $return = 'curl';

        elseif(static::isFinfo($value))
        $return = 'finfo';

        return $return;
    }


    // meta
    // retourne les meta données de la resource, si disponible
    // fonctionne pour curl
    final public static function meta($value):?array
    {
        $return = null;

        if(static::canMeta($value))
        {
            $return = stream_get_meta_data($value);

            if(static::isStreamMetaFile($return))
            $return['uri'] = Path::normalize($return['uri']);
        }

        elseif(static::isCurl($value))
        {
            $return = [];
            $info = curl_getinfo($value);

            if(!empty($info['url']))
            $return['uri'] = $info['url'];

            $return['error'] = curl_error($value);
            $return['errorNo'] = curl_errno($value);
            $return['info'] = $info;
        }

        return $return;
    }


    // metaValue
    // retourne une valeur du tableau meta
    final public static function metaValue(string $key,$value)
    {
        $return = null;
        $meta = static::meta($value);

        if(!empty($meta) && array_key_exists($key,$meta))
        $return = $meta[$key];

        return $return;
    }


    // mode
    // retourne le mode d'ouverture de la resource, si disponible
    // c'est identique au code utilisé dans la fonction fopen
    // si removeBinary est true, le caractère représentant une resource binaire est retiré
    final public static function mode($value,bool $removeBinary=false):?string
    {
        $return = null;
        $mode = static::metaValue('mode',$value);

        if(is_string($mode))
        {
            $return = $mode;

            if($removeBinary === true)
            $return = str_replace(static::$config['binary'],'',$return);
        }

        return $return;
    }


    // wrapperType
    // retourne le type de wrapper de la resource, si disponible
    final public static function wrapperType($value):?string
    {
        return static::metaValue('wrapper_type',$value);
    }


    // wrapperData
    // retourne les données du wrapper de la resource, si disponible
    // on y retrouve entre autre les headers d'une requête http
    final public static function wrapperData($value):?array
    {
        return static::metaValue('wrapper_data',$value);
    }


    // streamType
    // retourne le type de stream de la resource, si disponible
    final public static function streamType($value):?string
    {
        return static::metaValue('stream_type',$value);
    }


    // unreadBytes
    // retourne le nombre de bytes non lu, si disponible
    final public static function unreadBytes($value):?int
    {
        return static::metaValue('unread_bytes',$value);
    }


    // uri
    // retourne l'uri de la resource, si disponible
    // directoire ne retourne pas d'uri
    final public static function uri($value):?string
    {
        return static::metaValue('uri',$value);
    }


    // uriRemoveScheme
    // retourne l'uri de la resource en prenant bien soin d'enlever le scheme si présent
    // ne retire pas le windows drive
    final public static function uriRemoveScheme($value):?string
    {
        $return = static::uri($value);

        if(is_string($return))
        {
            if(!Path::hasWindowsDrive($return))
            $return = Uri::removeScheme($return);
        }

        return $return;
    }


    // headers
    // retourne le tableau des headers http si la resource est de type http
    // retourne sous une forme associative
    final public static function headers($value):?array
    {
        $return = null;

        if(static::isHttp($value))
        {
            $return = static::metaValue('wrapper_data',$value);

            if(is_array($return))
            $return = Header::arr($return);
        }

        elseif(static::isPhpWritable($value))
        $return = static::getPhpContextOption('header',$value);

        return $return;
    }


    // parse
    // retourne le tableau avec les résultats de parse_url si la resource a une uri
    // gestion particulière pour les chemins files
    // ne fonctionne pas avec les directoires
    final public static function parse($value):?array
    {
        $return = null;
        $uri = static::uri($value);

        if(is_string($uri))
        {
            if(static::isFile($value))
            {
                $return = Uri::getEmptyParse();
                $return['path'] = $uri;
            }

            else
            $return = Uri::parse($uri,false);
        }

        return $return;
    }


    // parseOne
    // retourne une partie des résultats de parse_url si la resource a une uri
    // gestion particulière pour les chemins files
    // ne fonctionne pas avec les directoires
    final public static function parseOne($key,$value):?string
    {
        $return = null;
        $uri = static::uri($value);

        if(is_string($uri))
        {
            if(static::isFile($value))
            {
                $key = Uri::getParseConstant($key);

                if($key === PHP_URL_PATH)
                $return = $uri;
            }

            else
            $return = Uri::parseOne($key,$uri,false);
        }

        return $return;
    }


    // scheme
    // retourne le scheme de la resource, si disponible
    // attention: certains types de resources peuvent être fonctionnelles sans avoir de scheme
    // ne fonctionne pas avec les directoires
    final public static function scheme($value):?string
    {
        return static::parseOne('scheme',$value);
    }


    // host
    // retourne le host de la resource, si disponible
    // ne fonctionne pas avec les directoires
    final public static function host($value):?string
    {
        return static::parseOne('host',$value);
    }


    // path
    // retourne le path de la resource, si disponible
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    // l'option de contexte a priorité
    final public static function path($value,bool $contextOption=false):?string
    {
        $return = null;

        if($contextOption === true || static::isPhpWritable($value))
        $return = static::getContextBasename($value);

        if(empty($return))
        {
            if(static::isFile($value))
            $return = static::uriRemoveScheme($value);

            else
            $return = static::parseOne('path',$value);
        }

        return $return;
    }


    // pathinfo
    // retourne le tableau pathinfo
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    final public static function pathinfo($value):?array
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        $return = Path::info($path);

        return $return;
    }


    // pathinfoOne
    // retourne une entrée du tableau pathinfo
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    final public static function pathinfoOne($key,$value,bool $contextOption=false):?string
    {
        $return = null;
        $path = static::path($value,$contextOption);

        if(is_string($path))
        $return = Path::infoOne($key,$path);

        return $return;
    }


    // dirname
    // retourne le dirname d'une resource à partir de son chemin
    // ne fonctionne pas avec les directoires
    final public static function dirname($value,bool $contextOption=false):?string
    {
        return static::pathinfoOne('dirname',$value,$contextOption);
    }


    // basename
    // retourne le basename d'une resource à partir de son chemin
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    final public static function basename($value,bool $contextOption=false):?string
    {
        return static::pathinfoOne('basename',$value,$contextOption);
    }


    // safeBasename
    // retourne le safeBasename de la ressource
    final public static function safeBasename($value):?string
    {
        return File::safeBasename($value);
    }


    // mimeBasename
    // retourne le mimeBasename de la ressource
    final public static function mimeBasename($value,?string $basename=null):?string
    {
        return File::mimeBasename($value,$basename);
    }


    // filename
    // retourne le filename d'une resource à partir de son chemin
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    final public static function filename($value,bool $contextOption=false):?string
    {
        return static::pathinfoOne('filename',$value,$contextOption);
    }


    // extension
    // retourne l'extension d'une resource à partir de son chemin
    // ne fonctionne pas avec les directoires
    // fonctionne avec les ressources phpWritable
    final public static function extension($value,bool $contextOption=false):?string
    {
        return static::pathinfoOne('extension',$value,$contextOption);
    }


    // size
    // retourne la taille de la resource
    // fonctionne seulement si la resource supporte stat, un élément http avec headers ou phpOutput
    final public static function size($value,bool $format=false)
    {
        $return = null;

        if(static::canStat($value))
        $return = static::statValue('size',$value,true,$format);

        elseif(static::isHttp($value))
        {
            $header = static::headers($value);
            if(!empty($header))
            $return = Header::contentLength($header);
        }

        elseif(static::isPhpOutput($value))
        $return = Buffer::size();

        return $return;
    }


    // mime
    // retourne le mime type de la resource
    // fonctionne seulement si la resource a l'option php mime, est un fichier ou un élément http
    final public static function mime($value,bool $charset=true,bool $contextOption=false):?string
    {
        return (static::is($value))? Mime::getFromResource($value,$charset,$contextOption):null;
    }


    // mimeGroup
    // retourne le groupe du mime type de la resource
    // fonctionne seulement si la resource est un fichier, un élément http ou phpWritable
    final public static function mimeGroup($value,bool $fromPath=true):?string
    {
        return (static::is($value))? Mime::getGroup($value,$fromPath):null;
    }


    // mimeFamilies
    // retourne les familles du mime type de la resource
    // fonctionne seulement si la resource est un fichier, un élément http ou phpWritable
    final public static function mimeFamilies($value,bool $fromPath=true):?array
    {
        return (static::is($value))? Mime::getFamilies($value,$fromPath):null;
    }


    // mimeFamily
    // retourne la premire famille du mime type de la resource
    final public static function mimeFamily($value,bool $fromPath=true):?string
    {
        return (static::is($value))? Mime::getFamily($value,$fromPath):null;
    }


    // param
    // retourne les paramètres de la resource, si disponible
    final public static function param($value):?array
    {
        return (static::canContext($value))? stream_context_get_params($value):null;
    }


    // contextOption
    // retourne les options de la resource, si disponible
    final public static function contextOption($value):?array
    {
        return (static::canContext($value))? stream_context_get_options($value):null;
    }


    // all
    // retournes les resources actives
    final public static function all(?string $type=null):array
    {
        $return = [];

        if(!empty($type))
        $return = get_resources($type);

        else
        $return = get_resources();

        return $return;
    }


    // transport
    // retourne un tableau des sockets transports accessible
    final public static function transport():array
    {
        return stream_get_transports();
    }


    // wrapper
    // retourne un tableau des types de stream accessible
    final public static function wrapper():array
    {
        return stream_get_wrappers();
    }


    // open
    // ouvre une resource à partir d'un chemin
    // si le chemin est un symlink, celui-ci est suivi
    // support pour un chemin avec scheme procol-relative //
    // le mode est passé dans la méthode openMode pour retourner le plus pertinent
    // le kind http est seulement permis si allow_url_fopen est vrai
    // si le kind est fichier et qu'il n'existe pas, il sera crée si le mode le permet
    // possibilité de forcer un mode ou une ouverture binaire
    // retourne null ou la resource ouverte
    // note pour directoire: cette fonction ne retourne pas le même sort selon le serveur
    // si value est true, retourne une resource php writable temporaire
    // si value est array, considère que c'est un upload via FILES
    final public static function open($value,?array $option=null)
    {
        $return = null;
        $option = Arr::plus(['useIncludePath'=>false,'context'=>null],$option);

        if($value === true)
        $return = static::phpWritable('temp',$option);

        elseif(is_array($value) && File::isUploadNotEmpty($value))
        $value = File::uploadPath($value);

        elseif(is_string($value) && empty(static::uriSchemeNotWindowsDrive($value)))
        $value = Finder::normalize($value);

        if(is_string($value))
        {
            if(is_link($value))
            $value = Symlink::get($value);

            elseif(Uri::isSchemeProtocolRelative($value))
            $value = Uri::changeProtocolRelativeScheme($value);

            $kind = static::openKind($value);

            if(!empty($kind))
            $return = static::openFromKind($value,$kind,$option);
        }

        return $return;
    }


    // openFromKind
    // utilisé lors de l'ouverture de la resource, une fois le kind déterminé
    final protected static function openFromKind(string $value,string $kind,array $option)
    {
        $return = null;

        if($kind === 'dir')
        $return = opendir($value);

        elseif($kind === 'finfo')
        $return = finfo_open(FILEINFO_MIME);

        elseif($kind === 'curl')
        $return = curl_init();

        elseif($kind === 'context')
        $return = stream_context_create();

        elseif($kind === 'http' && !Ini::get('allow_url_fopen'))
        $return = null;

        else
        {
            $mode = static::openMode($value,$kind,$option);

            if(!empty($mode))
            {
                $open = true;
                $context = null;

                if(!empty($option['context']))
                {
                    if(static::isContext($option['context']))
                    $context = $option['context'];
                    else
                    $context = static::context($option['context'],$kind);
                }

                if($kind === 'file')
                {
                    $open = false;

                    if(file_exists($value))
                    $open = true;

                    elseif(in_array($mode,static::$config['creatable'],true))
                    {
                        Dir::setParent($value);
                        $open = true;
                    }
                }

                if($open === true)
                {
                    if(is_resource($context))
                    $return = fopen($value,$mode,$option['useIncludePath'],$context);

                    else
                    $return = fopen($value,$mode,$option['useIncludePath']);
                }
            }
        }

        return $return;
    }


    // openKind
    // retourne le kind d'ouverture de la resource
    final public static function openKind(string $value):?string
    {
        $return = null;

        if(array_key_exists($value,static::$config['openKind']))
        $return = static::$config['openKind'][$value];

        elseif(is_dir($value))
        $return = 'dir';

        else
        {
            $scheme = static::uriSchemeNotWindowsDrive($value);

            if(is_string($scheme) && in_array($scheme,['http','https'],true))
            $return = 'http';
        }

        if(empty($return))
        $return = 'file';

        return $return;
    }


    // openMode
    // retourne le mode d'ouverture de resource
    // possibilité de forcer un mode ou de demander un mode binaire
    // si create est true et que le kind est fichier, le fichier aura un mode permettant la création
    final public static function openMode(string $value,string $kind,?array $option=null):?string
    {
        $return = null;
        $option = Arr::plus(['mode'=>null,'create'=>false,'binary'=>false],$option);

        if(is_string($option['mode']))
        {
            if(in_array($option['mode'],static::$config['mode'],true))
            $return = $option['mode'];

            elseif(array_key_exists($option['mode'],static::$config['mode']))
            $return = static::$config['mode'][$option['mode']];
        }

        // dir
        elseif($kind === 'dir')
        $return = static::$config['mode']['read'];

        // file
        elseif($kind === 'file')
        {
            if(is_file($value))
            {
                $readable = File::isReadable($value);
                $writable = File::isWritable($value);

                if($readable === true && $writable === true)
                $return = static::$config['mode']['readWrite'];

                elseif($writable === true)
                $return = static::$config['mode']['writeCreate'];

                elseif($readable === true)
                $return = static::$config['mode']['read'];
            }

            elseif(!file_exists($value) && $option['create'] === true)
            $return = static::$config['mode']['readWriteCreate'];
        }

        // http
        elseif($kind === 'http')
        $return = static::$config['mode']['read'];

        // phpOutput
        elseif($kind === 'phpOutput')
        $return = static::$config['mode']['writeTruncateCreate'];

        // phpInput
        elseif($kind === 'phpInput')
        $return = static::$config['mode']['read'];

        // phpTemp
        elseif($kind === 'phpTemp')
        $return = static::$config['mode']['readWrite'];

        // phpMemory
        elseif($kind === 'phpMemory')
        $return = static::$config['mode']['readWrite'];

        // binary
        if(!empty($return) && $option['binary'] === true)
        $return .= static::$config['binary'];

        return $return;
    }


    // binary
    // comme open, mais force l'ouverture en mode binaire
    final public static function binary($value,?array $option=null)
    {
        return static::open($value,Arr::plus($option,['binary'=>true]));
    }


    // create
    // comme open, mais active l'option de création de fichier
    final public static function create($value,?array $option=null)
    {
        return static::open($value,Arr::plus($option,['create'=>true]));
    }


    // phpWritable
    // ouvre une resource de type php qui est écrivable (exclut input)
    // un tableau d'options doit être fourni à l'inverse des méthodes temp / output et memory
    // le mime type et le basename sont inscrits dans les options de la resource
    // possible d'inscrire d'autres informations dans la resource via l'option write
    final public static function phpWritable(string $type,?array $option=null)
    {
        $return = null;
        $option = Arr::plus(['basename'=>null,'mime'=>null,'binary'=>true,'write'=>null],$option);

        if(in_array($type,['output','temp','memory'],true))
        {
            $basename = $option['basename'] ?? File::prefixFilename();
            $mime = $option['mime'];
            $option = Arr::unsets(['basename','mime'],$option);

            $return = static::open('php://'.$type,$option);
            $mime = ($mime === null)? static::$config['phpStream']['mime']:$mime;

            if(!empty($return))
            {
                $mimeType = Path::mime($mime);
                $mimeType = (empty($mimeType))? $mime:$mimeType;
                $extension = (!empty($mimeType))? Mime::toExtension($mimeType):null;
                $basename = Obj::cast($basename);

                if(!is_string($basename))
                $basename = Str::random(static::$config['phpStream']['basenameLength']);

                if(is_string($basename))
                $basename = Path::safeBasename($basename,$extension);

                static::setContextMime($mimeType,$return);
                static::setContextBasename($basename,$return);

                if(!empty($option['write']) && is_array($option['write']))
                {
                    foreach ($option['write'] as $key => $value)
                    {
                        if(is_string($key))
                        static::setPhpContextOption($key,$value,$return);
                    }
                }
            }
        }

        return $return;
    }


    // setPhpContextOption
    // permet de lier une clé -> valeur à l'intérieur du contexte de la ressource
    // n'a pas besoin d'être phpWritable
    final public static function setPhpContextOption(string $key,$value,$res):bool
    {
        return stream_context_set_option($res,'php',$key,$value);
    }


    // setContextMime
    // permet de lier un mime au sein du contexte de la ressource
    // n'a pas besoin d'être phpWritable
    final public static function setContextMime(string $mime,$res):bool
    {
        return static::setPhpContextOption('mime',$mime,$res);
    }


    // setContextBasename
    // permet de lier un basename au sein du contexte de la ressource
    // n'a pas besoin d'être phpWritable
    final public static function setContextBasename(string $basename,$res):bool
    {
        return static::setPhpContextOption('basename',$basename,$res);
    }


    // setContextEol
    // permet de changer la valeur eol au sein du contexte de la ressource
    // separator peut être null ou false
    final public static function setContextEol($separator,$res):bool
    {
        return static::setPhpContextOption('eol',$separator,$res);
    }


    // getPhpContextOption
    // retourne une option de contexte ou null
    // possible de creuser dans le tableau ou mettre null comme clé (retourne tout le tableau php)
    final public static function getPhpContextOption($key=null,$value)
    {
        $return = null;
        $option = static::contextOption($value);

        if(is_array($option) && !empty($option['php']) && is_array($option['php']))
        {
            if($key === null)
            $return = $option['php'];

            else
            $return = Arrs::get($key,$option['php']);
        }

        return $return;
    }


    // getContextMime
    // retourne le mime storé dans le contexte de la resource
    final public static function getContextMime($value):?string
    {
        return static::getPhpContextOption('mime',$value);
    }


    // getContextBasename
    // retourne le basename storé dans le contexte de la resource
    final public static function getContextBasename($value):?string
    {
        return static::getPhpContextOption('basename',$value);
    }


    // getContextEol
    // retourne la valeur eol storé dans le contexte de la resource
    final public static function getContextEol($value):?string
    {
        return static::getPhpContextOption('eol',$value);
    }


    // output
    // ouvre une resource de type php output
    // par défaut le buffer actuel est clean
    final public static function output(?string $mime=null,?string $basename=null,?array $option=null)
    {
        $option = Arr::plus(['clean'=>true,'mime'=>$mime,'basename'=>$basename],$option);
        $return = static::phpWritable('output',$option);

        if(!empty($return) && $option['clean'] === true)
        Buffer::cleanAll();

        return $return;
    }


    // temp
    // ouvre une resource de type php temp
    final public static function temp(?string $mime=null,$basename=null,?array $option=null)
    {
        return static::phpWritable('temp',Arr::plus(['mime'=>$mime,'basename'=>$basename],$option));
    }


    // memory
    // ouvre une resource de type php temp
    final public static function memory(?string $mime=null,?string $basename=null,?array $option=null)
    {
        return static::phpWritable('memory',Arr::plus(['mime'=>$mime,'basename'=>$basename],$option));
    }


    // tmpFile
    // retourne une ressource fichier dans le dossier temporaire
    // par défaut change l'extension si ce n'est pas la même
    // windows donne une extension .tmp au fichier temporaire
    final public static function tmpFile(?string $extension='tmp')
    {
        $return = tmpfile();

        if(!empty($return) && is_string($extension) && static::extension($return) !== $extension)
        $return = static::changeExtension($extension,$return);

        return $return;
    }


    // http
    // fait une requête http, value doit être une uri absolute
    // retourne un tableau avec code, contenttype, basename, meta, header et resource si c'est bien une requête http
    // si post n'est pas vide, la méthode devient post et le content-type application/x-www-form-urlencoded
    // headers peut être string ou array
    final public static function http(string $value,?array $post=null,$header=null):?array
    {
        $return = null;

        if(Uri::isAbsolute($value))
        {
            $return = ['code'=>null,'contentType'=>null,'basename'=>null,'meta'=>null,'header'=>null,'resource'=>null];
            $context = [];

            if(!empty($post))
            $context['post'] = $post;

            if(!empty($header))
            $context['header'] = $header;

            $resource = static::open($value,['context'=>$context]);
            $header = static::headers($resource);

            $return['basename'] = static::basename($resource);
            $return['meta'] = static::meta($resource);

            if(!empty($header))
            {
                $return['code'] = Header::code($header);
                $return['contentType'] = Header::contentType($header);
                $return['header'] = $header;
                $return['resource'] = $resource;
            }
        }

        return $return;
    }


    // curl
    // crée et retourne une resource curl
    // n'éxécute pas la resource
    final public static function curl(string $value,bool $exec=false,$post=null,$header=null,?array $option=null)
    {
        $return = static::open('curl');
        $option = Arr::plus(static::$config['curl'],$option);

        if(Uri::isAbsolute($value))
        {
            // base
            curl_setopt($return,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($return,CURLOPT_HEADER,true);

            // dnsGlobalCache
            if(is_bool($option['dnsGlobalCache']) && !PHP_ZTS)
            curl_setopt($return,CURLOPT_DNS_USE_GLOBAL_CACHE,$option['dnsGlobalCache']);

            // timeout
            if(is_int($option['timeout']))
            {
                curl_setopt($return,CURLOPT_CONNECTTIMEOUT,$option['timeout']);
                curl_setopt($return,CURLOPT_TIMEOUT,$option['timeout']);
            }

            // followLocation
            if($option['followLocation'] === true)
            curl_setopt($return,CURLOPT_FOLLOWLOCATION,true);

            // userPassword
            if(!empty($option['userPassword']))
            {
                if(is_array($option['userPassword']))
                $option['userPassword'] = implode(':',$option['userPassword']);

                curl_setopt($return,CURLOPT_USERPWD,$option['userPassword']);
            }

            // proxy
            if(is_string($option['proxyHost']) && is_int($option['proxyPort']))
            {
                $hostPort = Uri::makehostPort($option['proxyHost'],$option['proxyPort']);
                curl_setopt($return,CURLOPT_PROXY,$hostPort);

                if(is_string($option['proxyPassword']))
                curl_setopt($return,CURLOPT_PROXYUSERPWD,$option['proxyPassword']);
            }

            // uri
            curl_setopt($return,CURLOPT_URL,$value);

            // ssl
            if($option['ssl'] === null)
            $option['ssl'] = (Uri::isSsl($value))? true:false;

            if($option['ssl'] === true)
            {
                curl_setopt($return,CURLOPT_SSL_VERIFYPEER,0);
                curl_setopt($return,CURLOPT_SSL_VERIFYHOST,2);
            }

            // port
            $port = (is_int($option['port']))? $option['port']:Http::port($option['ssl']);
            curl_setopt($return,CURLOPT_PORT,$port);

            // sslCipher
            if(is_string($option['sslCipher']) && !empty($option['sslCipher']))
            curl_setopt($return,CURLOPT_SSL_CIPHER_LIST,$option['sslCipher']);

            // userAgent
            if(is_string($option['userAgent']) && !empty($option['userAgent']))
            curl_setopt($return,CURLOPT_USERAGENT,$option['userAgent']);

            // post
            if($post !== null)
            {
                curl_setopt($return,CURLOPT_POST,1);

                if(is_array($post))
                {
                    if($option['postJson'] === true)
                    $post = Json::encode($post);
                    else
                    $post = Uri::buildQuery($post,true);
                }

                if(is_string($post))
                curl_setopt($return,CURLOPT_POSTFIELDS,$post);
            }

            // header
            if(!empty($header))
            {
                $header = Header::arr($header);
                curl_setopt($return,CURLOPT_HTTPHEADER,$header);
            }

            if($exec === true)
            $return = static::curlExec($return);
        }

        return $return;
    }


    // context
    // crée un context de flux à joindre avec l'ouverture d'une resource
    // simplifié pour les type http (joindre un array avec clé post et/ou header)
    final public static function context($value,string $kind)
    {
        $return = static::open('context');

        if(is_array($value) && !empty($value))
        {
            if(!array_key_exists($kind,$value))
            $value = [$kind=>$value];

            // http
            if($kind === 'http')
            {
                $header = (array_key_exists('header',$value[$kind]))? Header::arr($value[$kind]['header']):[];

                if(array_key_exists('post',$value[$kind]))
                {
                    $content = null;
                    $contentType = Header::contentType($header);
                    $post = $value[$kind]['post'];

                    if(is_string($post))
                    $content = $post;

                    elseif(is_array($post))
                    $content = Uri::buildQuery($post);

                    $value[$kind]['method'] = 'POST';
                    $value[$kind]['content'] = $content;
                    unset($value[$kind]['post']);

                    if(empty($contentType))
                    $header = Header::setContentType('application/x-www-form-urlencoded',$header);
                }

                if(!empty($header))
                $value[$kind]['header'] = Header::implode($header);
            }

            if(!empty($value))
            stream_context_set_option($return,$value);
        }

        return $return;
    }


    // curlExec
    // éxécute la ressource curl
    // retourne un tableau avec code, contentType, basename, meta, header et resource si c'est bien une requête http
    // la resource est une resource php temporaire
    final public static function curlExec($value,bool $close=true):?array
    {
        $return = null;

        if(static::isCurl($value))
        {
            $return = ['code'=>null,'contentType'=>null,'basename'=>null,'meta'=>null,'header'=>null,'resource'=>null];
            $exec = curl_exec($value);
            $meta = static::meta($value);
            $basename = static::basename($value);

            $return['basename'] = $basename;
            $return['meta'] = $meta;

            if(is_string($exec) && !empty($exec))
            {
                $explode = Str::explodeTrim("\r\n\r\n",$exec);

                if(count($explode) >= 2)
                {
                    $body = Arr::valueLast($explode);
                    $explode = Arr::spliceLast($explode);
                    $header = Arr::valueLast($explode);

                    if(is_string($header) && !empty($header))
                    {
                        $header = Header::arr($header);
                        $contentType =  Header::contentType($header);

                        $return['code'] = Header::code($header);
                        $return['contentType'] = $contentType;
                        $return['header'] = Header::arr($header);

                        if(is_string($body))
                        {
                            $write = ['meta'=>$return['meta'],'header'=>$return['header']];
                            $resource = static::temp($contentType,$basename,['write'=>$write]);

                            if(!empty($resource) && static::write($body,$resource))
                            $return['resource'] = $resource;
                        }
                    }
                }
            }

            if($close === true)
            static::close($value);
        }

        return $return;
    }


    // curlInfo
    // retourne le tableau curl info
    // supporte curl ou une resource temporaire avec des données emmagasinés
    final public static function curlInfo($value):?array
    {
        $return = null;

        if(static::isCurl($value))
        $return = curl_getinfo($value);

        else
        $return = static::getPhpContextOption(['meta','info'],$value);

        return $return;
    }


    // position
    // retourne la position courante du pointeur dans la resource
    // ne fonctionne pas avec les ressources directoires
    final public static function position($value):?int
    {
        $return = null;
        $kind = static::kind($value);

        if(!empty($kind) && static::isSeekableTellable($value))
        $return = ftell($value);

        return $return;
    }


    // seek
    // envoie le pointeur de la resource à un endroit précis à partir du début du pointeur
    // pour les resources seekable, une bit additionnelle est lu pour tester la fin de la resource
    // si seek est true, alors seek vaut 0
    // si ce n'est pas la fin de la resource, le pointeur est ramener une bit à l'arrière
    final public static function seek($seek=0,$value,?int $type=SEEK_SET,?array $option=null):bool
    {
        $return = false;

        if(static::isDir($value))
        $return = static::seekDir($seek,$value,($type === SEEK_SET)? true:false,$option['dot'] ?? false);

        elseif(static::isSeekable($value))
        {
            if($seek === true)
            $seek = 0;

            if(is_int($seek))
            {
                $fseek = fseek($value,$seek,$type);

                if($fseek === 0)
                {
                    fread($value,1);

                    if(!feof($value))
                    fseek($value,-1,SEEK_CUR);

                    $return = true;
                }
            }
        }

        return $return;
    }


    // seekDir
    // seek dans une resource de directoire
    // si rewind est true, renvoie le pointeur au début
    // inclut les dot si dot est true, sinon ils ne font pas partie du calcul de position
    // pas de seek end avec une resource directoire
    // note, readdir ne donne pas le même résultat sur tous les serveurs
    final protected static function seekDir(int $position,$value,bool $rewind=true,bool $dot=false):bool
    {
        $return = false;

        if(static::isDir($value))
        {
            $return = true;
            $i = 0;

            if($rewind === true)
            rewinddir($value);

            if($position > $i)
            {
                while ($position > 0)
                {
                    $read = readdir($value);

                    if($read === false)
                    break;

                    if($dot === true || !Finder::isDot($read))
                    $position--;
                }
            }
        }

        return $return;
    }


    // seekCurrent
    // déplace le pointeur de la resource à partir de sa position courante
    // position ne peut pas être PHP_INT_MAX pour SET_CUR
    // support pour directoire
    final public static function seekCurrent($seek,$value,?array $option=null):bool
    {
        return static::seek($seek,$value,SEEK_CUR,$option);
    }


    // seekEnd
    // envoie le pointeur de la resource à la fin
    // ne fonctionne pas avec une ressource directoire
    final public static function seekEnd($seek=0,$value,?array $option=null):bool
    {
        return static::seek($seek,$value,SEEK_END,$option);
    }


    // seekRewind
    // rewind le pointeur de la resource au début
    // contrairement à seek, rewind ne vérifie pas la fin du fichier avec une lecture une bit plus loin
    // support pour directoire
    final public static function seekRewind($value):bool
    {
        $return = false;
        $kind = static::kind($value);

        if(!empty($kind) && static::isSeekable($value))
        {
            if($kind === 'dir')
            {
                rewinddir($value);
                $return = true;
            }

            else
            $return = rewind($value);
        }

        return $return;
    }


    // lock
    // acquiert un verrou sur une resource
    // pour un verrou exclusif en écriture il faut mettre true à exclusive
    // le verrou continue même après la destruction de la resource
    final public static function lock($value,bool $exclusive=false):bool
    {
        $return = false;

        if(static::isLockable($value))
        {
            $lock = ($exclusive === true)? LOCK_EX:LOCK_SH;
            $return = flock($value,$lock);
        }

        return $return;
    }


    // unlock
    // libère un verrou sur une resource
    final public static function unlock($value):bool
    {
        $return = false;

        if(static::isLockable($value))
        $return = flock($value,LOCK_UN);

        return $return;
    }


    // passthru
    // envoie tout le contenu de la resource au output buffer
    // ne peut pas être une resource directoire
    // option clean, rewind et flush
    final public static function passthru($value,?array $option=null):bool
    {
        $return = false;
        $option = Arr::plus(['clean'=>true,'rewind'=>true,'flush'=>true],$option);

        if(static::isReadable($value) && !static::isDir($value))
        {
            if($option['clean'] === true)
            Buffer::endCleanAll();

            if($option['rewind'] === true)
            static::seekRewind($value);

            $passthru = fpassthru($value);

            if(is_int($passthru))
            {
                if($option['flush'] === true)
                Buffer::flush();

                $return = true;
            }
        }

        return $return;
    }


    // passthruChunk
    // lit le contenu d'une resource en la divisant par une longueur
    // la resource est immédiatement envoyé dans le buffer via echo
    // possibilité de sleep entre chaque longueur
    // retourne le nombre de chunk de données envoyés ou null si la resource n'est pas lisible
    // ne peut pas être une resource directoire
    // endCleanAll est utilisé -> donc fermeture complête du output buffer
    // option clean, rewind, flush et sleep
    final public static function passthruChunk($length,$value,?array $option=null):?int
    {
        $return = null;
        $option = Arr::plus(['clean'=>true,'rewind'=>true,'flush'=>true,'sleep'=>null],$option);

        if(static::isReadable($value) && !static::isDir($value))
        {
            $return = 0;

            if($option['clean'] === true)
            Buffer::endCleanAll();

            if($option['rewind'] === true)
            static::seekRewind($value);

            while (!feof($value))
            {
                if($return > 0 && is_numeric($option['sleep']) && $option['sleep'] > 0)
                Response::sleep($option['sleep']);

                echo static::read(-1,$length,$value);

                if($option['flush'] === true)
                Buffer::flush();

                $return++;
            }
        }

        return $return;
    }


    // flush
    // écrit immédiatement tout le contenu en buffer dans la ressource
    final public static function flush($value):bool
    {
        $return = false;

        if(static::isWritable($value))
        $return = fflush($value);

        return $return;
    }


    // line
    // retourne la ligne courante de la resource
    // support pour des ressources normales ou csv
    final public static function line($value,?array $option=null)
    {
        $return = null;
        $option = Arr::plus(['csv'=>false,'amount'=>PHP_INT_MAX,'separator'=>null],$option);

        if(is_resource($value))
        {
            if(empty($option['separator']))
            $option['separator'] = static::findEol($value);

            if(!empty($option['csv']) && $option['csv'] === true)
            $line = Csv::resLine($value,$option);

            else
            $line = stream_get_line($value,$option['amount'],$option['separator']);

            if($line !== false && $line !== null)
            $return = $line;
        }

        return $return;
    }


    // lineRef
    // retourne la ligne courante de la resource à partir d'un offset, length et un i
    // le i doit être passé par référence
    final public static function lineRef($value,$offset=true,$length=true,int &$i,?array $option=null)
    {
        $return = null;

        if(is_resource($value))
        {
            $offset = (is_int($offset) && $offset >= 0)? $offset:0;
            $length = (is_int($length) && $length >= 0)? $length:PHP_INT_MAX;

            while ($line = static::line($value,$option))
            {
                if($i >= $offset)
                {
                    if($i < ($offset + $length))
                    {
                        $return = $line;
                        break;
                    }
                }

                $i++;
            }
        }

        return $return;
    }


    // get
    // retourne le contenu d'une resource
    // possibilité de fournir un chemin et d'ouvrir la resource via cette méthode
    // possibilité d'utiliser seek et amount, par défaut la méthode lit l'ensemble du début
    // argument inversé par rapport à read
    final public static function get($value,$seek=true,$length=true,?array $option=null)
    {
        $return = null;
        $close = false;

        if(!is_resource($value))
        {
            $value = static::open($value,$option);
            $close = true;
        }

        if(static::isCurl($value))
        {
            $exec = static::curlExec($value,false);

            if(!empty($exec))
            $value = $exec['resource'];

            else
            $value = null;
        }

        if(is_resource($value))
        {
            $return = static::read($seek,$length,$value,$option);

            if($close === true)
            static::close($value);
        }

        return $return;
    }


    // read
    // lit le contenu d'une resource
    // possibilité de seek à l'endroit du début de la lecture, laisser null pour partir de la position courante
    // lit entièrement la resource si length est true
    // les resources non seekable ne peuvent être lu qu'une fois, pas de retour en arrière
    // possible de retirer le/les bom
    // support pour un callback avant la lecture
    final public static function read($seek,$length,$value,?array $option=null)
    {
        $return = null;

        if(static::isDir($value))
        $return = static::readDir($seek,$length,$value,$option);

        elseif(static::isReadable($value))
        {
            $option = Arr::plus(['callback'=>null,'removeBom'=>false],$option);
            $seekTo = -1;

            if(static::isSeekable($value))
            {
                if($seek === true)
                $seekTo = 0;

                if(is_int($seek))
                $seekTo = $seek;
            }
            $length = (is_int($length))? $length:-1;

            $return = stream_get_contents($value,$length,$seekTo);

            if($option['removeBom'] === true)
            $return = Str::removeBom($return);

            if(static::isCallable($option['callback']))
            $return = $option['callback']($return);
        }

        return $return;
    }


    // readDir
    // lit une ou plusieurs entrées à partir de la resource du directoire
    // lit entièrement le directoire si amount est true
    // inclut les dot si option dot est true
    // possibilité de rewind ou seek avec seekRewind
    // peut retourner null, une string ou un array
    final protected static function readDir($seek,$amount,$handle,?array $option=null)
    {
        $return = null;
        $option = Arr::plus(['dot'=>false],$option);

        if($amount === true)
        $amount = PHP_INT_MAX;

        if(static::isDir($handle) && is_int($amount))
        {
            if($seek === true)
            static::seekRewind($handle);

            elseif(is_int($seek))
            static::seek($seek,$handle,SEEK_SET,$option);

            $return = [];
            $array = ($amount > 1)? true:false;

            while ($amount > 0)
            {
                $read = readdir($handle);

                if($read === false)
                break;

                if($option['dot'] === true || !Finder::isDot($read))
                {
                    $return[] = $read;
                    $amount--;
                }
            }

            if($array === false)
            $return = Arr::valueFirst($return);
        }

        return $return;
    }


    // findEol
    // va tenter de détecter le séparateur de ligne si seekable tellable
    // enregistre dans les options de la ressource
    final public static function findEol($value):string
    {
        $return = static::getPhpContextOption('eol',$value);

        if($return === null)
        {
            $eol = static::parseEol($value);
            $return = (is_string($eol))? $eol:false;
            static::setContextEol($return,$value);
        }

        if(!is_string($return))
        $return = PHP_EOL;

        return $return;
    }


    // getEolLength
    // retourne la longueur du séparateur de ligne (1 ou 2)
    final public static function getEolLength($value):int
    {
        return strlen(static::findEol($value));
    }


    // parseEol
    // tente de trouver le séparateur de ligne dans la resource
    // va seek et retourner à la position originale
    final public static function parseEol($value):?string
    {
        $return = null;

        if(static::isSeekableTellable($value))
        {
            $length = static::$config['lineSeparatorLength'];
            $pos = static::position($value);
            static::seekRewind($value);
            $content = stream_get_contents($value,$length);

            if(is_string($content) && !empty($content))
            $return = Str::getEol($content);

            static::seek($pos,$value);
        }

        return $return;
    }


    // getLines
    // retourne un tableau contenant toutes les lignes de la resource
    // argument inversé par rapport à lines
    // option skipEmpty disponible
    final public static function getLines($value,$offset=true,$length=true,?array $option=null):?array
    {
        return static::lines($offset,$length,$value,$option);
    }


    // lines
    // retourne un tableau contenant les lignes de la resource incluent entre offset et length
    // offset accepte un chiffre négatif
    // option skipEmpty et trim
    final public static function lines($offset,$length,$value,?array $option=null):?array
    {
        $return = null;
        $option = Arr::plus(['skipEmpty'=>false,'trim'=>false],$option);

        if(static::isSeekable($value) && static::isReadable($value))
        {
            $return = [];

            if($offset === null)
            $offset = ftell($value);

            elseif($offset === true)
            $offset = 0;

            if($length === true)
            $length = PHP_INT_MAX;

            if($offset < 0)
            {
                $count = static::lineCount($value,$option);
                $offset = $count + $offset;
            }

            if(is_int($offset) && $offset >= 0 && is_int($length) && $length >= 0)
            {
                static::seekRewind($value);
                $i = 0;

                while(!feof($value))
                {
                    $line = static::line($value,$option);

                    if($i >= $offset && $line !== null)
                    {
                        if(count($return) < $length)
                        {
                            if($option['skipEmpty'] === false || !empty($line))
                            {
                                if($option['trim'] === true)
                                $line = trim($line);

                                $return[$i] = $line;
                            }
                        }

                        else
                        break;
                    }

                    $i++;
                }
            }
        }

        return $return;
    }


    // lineCount
    // compte le nombre de ligne dans la resource
    // un fichier vide retourne 0 ligne
    final public static function lineCount($value,?array $option=null):?int
    {
        $return = null;
        $option = Arr::plus(['rewind'=>true],$option);

        if(static::isSeekable($value) && static::isReadable($value))
        {
            $return = 0;

            if($option['rewind'] === true)
            static::seekRewind($value);

            // lit une première bite pour tester la fin du fichier
            fread($value,1);

            while(!feof($value))
            {
                static::line($value,$option);
                $return++;
            }

            if($option['rewind'] === true)
            static::seekRewind($value);
        }

        return $return;
    }


    // subCount
    // retourne le nombre d'occurences d'une substring dans une ressource
    // si sub contient le separateur, la recherche se fait dans tout le fichier et non pas par ligne
    // les fichiers csv seront traités en tant que string et non pas array
    final public static function subCount(string $sub,$value,?array $option=null):?int
    {
        $return = null;
        $option = Arr::plus(['rewind'=>true,'mb'=>null,'separator'=>null],$option,['csv'=>false]);

        if(static::isSeekable($value) && static::isReadable($value) && !empty($sub))
        {
            $return = 0;

            if(empty($option['separator']))
            $option['separator'] = static::findEol($value);

            if($option['rewind'] === true)
            static::seekRewind($value);

            if(strpos($sub,$option['separator']) !== false)
            $return = Str::subCount($sub,static::read(null,true,$value),null,null,$option['mb']);

            else
            {
                while(!feof($value))
                {
                    $line = static::line($value,$option);
                    if(is_string($line))
                    $return += Str::subCount($sub,$line,null,null,$option['mb']);
                }
            }

            if($option['rewind'] === true)
            static::seekRewind($value);
        }

        return $return;
    }


    // base64
    // lit le contenu d'une resource et retourne l'encodage base64 de la resource
    // si convert est true, le mime type sera converti en utilisant le tableau dans static config
    final public static function base64($value,bool $meta=true,bool $convert=true,?array $option=null):?string
    {
        $return = null;

        if(static::isReadable($value))
        {
            $read = static::read(true,true,$value,$option);

            if(is_string($read))
            {
                $base64 = Crypt::base64($read);

                if(is_string($base64) && strlen($base64))
                {
                    if($meta === true)
                    {
                        $mime = static::mime($value,false);
                        if(is_string($mime))
                        {
                            if($convert === true && array_key_exists($mime,static::$config['base64']))
                            $mime = static::$config['base64'][$mime];

                            $return = "data:$mime;base64,";
                            $return .= $base64;
                        }
                    }

                    else
                    $return = $base64;
                }
            }
        }

        return $return;
    }


    // lineFirst
    // retourne la première ligne de la resource
    final public static function lineFirst($value,?array $option=null)
    {
        $return = null;

        if(static::isSeekable($value) && static::isReadable($value))
        {
            $slice = static::lines(0,1,$value,$option);
            if(!empty($slice))
            $return = current($slice);
        }

        return $return;
    }


    // lineLast
    // retourne la dernière ligne de la resource
    final public static function lineLast($value,?array $option=null)
    {
        $return = null;

        if(static::isSeekable($value) && static::isReadable($value))
        {
            $slice = static::lines(-1,1,$value,$option);
            if(!empty($slice))
            $return = current($slice);
        }

        return $return;
    }


    // lineChunk
    // permet de subdiviser le tableau de l'ensemble des lignes de la resource par longueur
    // retourne un tableau multidimensionnel colonne
    final public static function lineChunk(int $each,$value,bool $preserve=true,?array $option=null):?array
    {
        $return = null;
        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        $return = Arr::chunk($each,$lines,$preserve);

        return $return;
    }


    // lineChunkWalk
    // permet de subdiviser le tableau de l'ensemble des lignes de la resource selon le retour d'un callback
    // si callback retourne true, la colonne existante est stocké et une nouvelle colonne est crée
    // si callback retourne faux, la colonne existante est stocké et fermé
    // si callback retourne null, la ligne est stocké si la colonne est ouverte, sinon elle est ignoré
    // retourne un tableau multidimensionnel colonne
    final public static function lineChunkWalk(callable $callback,$value,?array $option=null):?array
    {
        $return = null;
        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        $return = Arr::chunkWalk($callback,$lines);

        return $return;
    }


    // prepareType
    // cast les données fournis dans les méthodes
    // utiliser par la méthode prepareContent, creuse dans un tableau
    final protected static function prepareType($return)
    {
        if(is_object($return))
        $return = Obj::cast($return);

        if(is_scalar($return) || is_object($return))
        $return = Str::cast($return);

        if(is_resource($return))
        $return = static::get($return);

        if(is_array($return))
        {
            foreach ($return as $key => $value)
            {
                $return[$key] = static::prepareType($value);
            }
        }

        return $return;
    }


    // prepareContent
    // prepare le contenu pour l'écriture d'une ressource
    // support pour fichier csv, et écriture standard
    // méthode public uniquemment car beaucoup de test
    final public static function prepareContent($value,array $option=null)
    {
        $return = null;
        $option = Arr::plus(['separator'=>PHP_EOL,'csv'=>false,'replace'=>false],$option);
        $value = static::prepareType($value);

        if($option['csv'] === true)
        $return = Csv::prepareContent($value);

        else
        {
            if($option['replace'] === true)
            {
                if(is_string($value))
                $return = [$value];

                elseif(is_array($value))
                $return = $value;
            }

            else
            {
                if(is_string($value))
                $return = $value;

                elseif(is_array($value))
                $return = (string) (Arr::isUni($value))? implode($option['separator'],$value):Json::encode($value);

                else
                $return = '';
            }
        }

        return $return;
    }


    // set
    // permet d'overwrite ou d'append dans une resource
    // possibilité de fournir un chemin et de créer la ressource via cette méthode
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    final public static function set($value,$content=null,bool $append=false,?array $option=null)
    {
        $return = null;
        $close = false;

        if(is_string($value))
        {
            $value = static::create($value,$option);
            $close = true;
        }

        if(is_resource($value))
        {
            if($content === null)
            {
                if($append === true)
                $return = true;
                else
                $return = static::empty($value);
            }

            else
            {
                if($append === true)
                $return = static::append($content,$value,$option);
                else
                $return = static::overwrite($content,$value,$option);
            }

            if($close === true)
            static::close($value);
        }

        return $return;
    }


    // write
    // écrit du contenu dans une ressource à l'endoit où est le pointeur
    // possibilité de barrer la ressource pendant l'opération
    // possibilité de flush le buffer pour que le contenu soit écrit immédiatement dans la ressource
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    // support pour un callback avant l'écriture
    final public static function write($content,$value,?array $option=null)
    {
        $return = false;
        $option = Arr::plus(['callback'=>null,'seek'=>null,'lock'=>false,'flush'=>false,'csv'=>false],$option);

        if(static::isCallable($option['callback']))
        $content = $option['callback']($content);

        $content = static::prepareContent($content,$option);

        if($content !== null && ($option['lock'] === false || static::lock($value,true)))
        {
            if(($option['seek'] === true || is_int($option['seek'])))
            static::seek($seek,$value);

            elseif($option['csv'] === true)
            $return = Csv::resWrite($content,$value,$option);

            else
            $return = static::writeStream($content,$value,$option);

            if($option['flush'] === true)
            static::flush($value);

            if($option['lock'] === true)
            static::unlock($value);
        }

        return $return;
    }


    // writeStream
    // écrit dans un stream, content doit être string
    // le pointeur est envoyé à seek current à la fin de l'écriture pour tester la fin de la ressource
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    final public static function writeStream(string $content,$value,?array $option=null)
    {
        $return = false;
        $option = Arr::plus(['latin1'=>false,'newline'=>false,'separator'=>PHP_EOL],$option);

        if(static::isWritable($value))
        {
            if(!empty($option['newline']) && !empty($option['separator']))
            $content = $option['separator'].$content;

            if(!empty($option['latin1']))
            $content = Encoding::fromUtf8($content);

            if(!empty($option['amount']) && is_int($option['amount']))
            $return = fwrite($value,$content,$option['amount']);
            else
            $return = fwrite($value,$content);

            if(is_int($return))
            {
                static::seekCurrent(0,$value);
                $checkReturn = (!empty($option['amount']))? $option['amount']:strlen($content);

                if($return === $checkReturn)
                $return = true;
            }
        }

        return $return;
    }


    // writeBom
    // écrit le bom au début du fichier
    final public static function writeBom($value)
    {
        $return = false;

        if(static::isWritable($value) && static::isSeekableTellable($value))
        {
            static::seekRewind($value);
            $bom = Str::bom();
            $return = static::writeStream($bom,$value);
        }

        return $return;
    }


    // overwrite
    // effacer le contenu de la ressource et ensuite écrit le nouveau contenu
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    final public static function overwrite($content,$value,?array $option=null)
    {
        $return = false;
        $option = Arr::plus(['lock'=>false],$option);

        if(static::isWritable($value))
        {
            if($option['lock'] === false || static::lock($value,true))
            {
                if(static::empty($value,0))
                {
                    $return = static::write($content,$value,Arr::plus($option,['lock'=>false]));

                    if($option['lock'] === true)
                    static::unlock($value);
                }
            }
        }

        return $return;
    }


    // prepend
    // prepend du contenu dans une ressource
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    // si newline est true, ajoute une newline à la fin du nouveau contenu
    final public static function prepend($content,$value,?array $option=null)
    {
        $return = false;
        $option = Arr::plus(['newline'=>false,'separator'=>PHP_EOL,'csv'=>false],$option);

        if(static::isWritable($value) && static::isSeekable($value))
        {
            $write = null;
            $prepend = static::prepareContent($content,$option);

            if($option['csv'] === true)
            $write = Csv::prepareContentPrepend($prepend,$value,$option);

            else
            {
                $append = static::prepareContent(static::get($value),$option);

                if(is_string($prepend) && is_string($append))
                {
                    if($option['newline'] === true)
                    $write = $prepend.$option['separator'].$append;

                    else
                    $write = $prepend.$append;
                }
            }

            if($write !== null)
            $return = static::overwrite($write,$value,Arr::plus($option,['newline'=>false]));
        }

        return $return;
    }


    // append
    // append du contenu dans une ressource
    // retourne vrai si le content a été écrit en entier, sinon retourne un int pour dire le nombre d'octets écrit
    final public static function append($content,$value,?array $option=null)
    {
        static::seekEnd(0,$value);
        return static::write($content,$value,$option);
    }


    // concatenate
    // permet de concatener plusieurs ressources et écrire le rendu dans une resource
    // un séparateur doit être fourni, une callable peut être fourni
    final public static function concatenate($value,?callable $callable=null,string $separator,...$values)
    {
        $return = false;
        $content = static::concatenateString($callable,$separator,...$values);

        if(is_string($content))
        $return = static::write($content,$value);

        return $return;
    }


    // concatenateString
    // permet de concatener plusieurs ressources et retourner le rendu combiné dans une string
    // un séparateur doit être fourni, une callable peut être fourni
    final public static function concatenateString(?callable $callable=null,string $separator,...$values):?string
    {
        $return = null;

        foreach ($values as $value)
        {
            $read = static::read(true,true,$value);

            if(is_string($read))
            {
                if(is_string($return))
                $return .= $separator;

                $return .= $read;
            }
        }

        if(!empty($return) && !empty($callable))
        $return = $callable($return);

        return $return;
    }


    // lineSplice
    // permet d'enlever et éventuellement remplacer des lignes dans la ressource
    // offset accepte un chiffre négatif
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    final public static function lineSplice(int $offset,int $length,$value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;
        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        {
            $replace = static::prepareContent($replace,Arr::plus($option,['replace'=>true]));
            $return = Arr::spliceIndex($offset,$length,$lines,$replace);

            if($overwrite === true)
            static::overwrite($return,$value,$option);
        }

        return $return;
    }


    // lineSpliceFirst
    // permet d'enlever et éventuellement remplacer la première ligne de la ressource
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    final public static function lineSpliceFirst($value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;

        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        {
            $replace = static::prepareContent($replace,Arr::plus($option,['replace'=>true]));
            $return = Arr::spliceFirst($lines,$replace);

            if($overwrite === true)
            static::overwrite($return,$value,$option);
        }

        return $return;
    }


    // lineSpliceLast
    // permet d'enlever et éventuellement remplacer la dernière ligne de la ressource
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    final public static function lineSpliceLast($value,$replace=null,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;
        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        {
            $replace = static::prepareContent($replace,Arr::plus($option,['replace'=>true]));
            $return = Arr::spliceLast($lines,$replace);

            if($overwrite === true)
            static::overwrite($return,$value,$option);
        }

        return $return;
    }


    // lineInsert
    // permet d'insérer du nouveau contenu à un numéro de ligne dans la ressource
    // le reste du contenu est repoussé
    // offset accepte un chiffre négatif
    // retourne un tableau des lignes, si overwrite est true les changements sont inscrits au fichier
    final public static function lineInsert(int $offset,$replace,$value,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;
        $lines = static::lines(true,true,$value,$option);

        if(!empty($lines))
        {
            $replace = static::prepareContent($replace,Arr::plus($option,['replace'=>true]));
            $return = Arr::insertIndex($offset,$replace,$lines);

            if($overwrite === true)
            static::overwrite($return,$value,$option);
        }

        return $return;
    }


    // lineFilter
    // permet de passer chaque ligne de la resource dans un callback
    // si le callback retourne faux, la ligne est retiré
    // la ressource est automatiquement modifié
    // retourne un tableau des lignes filtrés, possibilité d'écrire le résultat dans la ressource si overwrite est true
    final public static function lineFilter(callable $callback,$value,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;

        if($overwrite === false || static::isWritable($value))
        {
            $lines = static::lines(true,true,$value,$option);

            if(!empty($lines))
            {
                $return = Arr::filter($callback,$lines);

                if($overwrite === true)
                static::overwrite($return,$value,$option);
            }
        }

        return $return;
    }


    // lineMap
    // permet de passer chaque ligne de la resource dans un callback
    // la ligne est remplacé par la valeur de retour du callback
    // retourne un tableau des nouvelles lignes, possibilité d'écrire le résultat dans la ressource si overwrite est true
    final public static function lineMap(callable $callback,$value,bool $overwrite=true,?array $option=null):?array
    {
        $return = null;

        if($overwrite === false || static::isWritable($value))
        {
            $lines = static::lines(true,true,$value,$option);

            if(!empty($lines))
            {
                $return = Arr::map($callback,$lines);

                if($overwrite === true)
                static::overwrite($return,$value,$option);
            }
        }

        return $return;
    }


    // empty
    // vide une resource
    // size permet de définir quel taille la ressource doit avoir après l'opération, donc la méthode truncate à partir de la fin
    // possibilité de barrer la ressource pendant l'opération
    // retourne vrai si la ressource a été vidé ou si elle est déjà vide
    final public static function empty($value,int $size=0,?array $option=null):bool
    {
        $return = false;
        $option = Arr::plus(['rewind'=>true,'lock'=>false],$option);

        if(static::isSeekable($value) && static::isWritable($value))
        {
            if($option['lock'] === false || static::lock($value,true))
            {
                $return = ftruncate($value,$size);

                if(!empty($return) && $option['rewind'] === true)
                static::seekRewind($value);

                if($option['lock'] === true)
                static::unlock($value);
            }
        }

        return $return;
    }


    // download
    // force le téléchargement de la resource
    // option kill, length et sleep
    final public static function download($value,?array $option=null):?bool
    {
        return (static::isResponsable($value))? Response::download($value,$option):null;
    }


    // toScreen
    // force l'affichage de la resource dans le navigateur
    // option kill, length et sleep
    final public static function toScreen($value,?array $option=null):?bool
    {
        return (static::isResponsable($value))? Response::toScreen($value,$option):null;
    }


    // toFile
    // force l'écriture de la resource dans un nouveau fichier
    final public static function toFile($path,$value,?array $option=null)
    {
        $return = null;

        if(static::isResponsable($value))
        $return = File::set($path,$value,false,$option);

        return $return;
    }


    // pathToUri
    // retourne l'uri à partir d'un path
    final public static function pathToUri($value,?bool $absolute=null):?string
    {
        return File::pathToUri($value,$absolute);
    }


    // pathToUriOrBase64
    // retourne le pathToUri ou base64
    // utiliser pour html/img
    final public static function pathToUriOrBase64($value):?string
    {
        return static::pathToUri($value) ?? static::base64($value);
    }


    // touch
    // touche une resource fichier et change les dates d'accès et de modification
    final public static function touch($value):bool
    {
        return File::touch($value);
    }


    // rename
    // renomme une resource fichier, retourne la nouvelle resource en cas de succès
    // retourne null ou la resource du nouveau fichier
    final public static function rename($target,$value)
    {
        $return = null;
        $rename = File::rename($target,$value);

        if($rename === true)
        $return = static::open($target);

        return $return;
    }


    // changeDirname
    // renomme le dirname de la resource fichier, garde le basename
    // retourne null ou la resource du nouveau fichier
    final public static function changeDirname($dirname,$value)
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $basename = Path::basename($path);
            $rename = File::changeDirname($dirname,$value);

            if($rename === true)
            {

                $target = Path::addBasename($basename,$dirname);
                $return = static::open($target);
            }
        }

        return $return;
    }


    // changeBasename
    // renomme le basename de la resource fichier, garde le dirname
    // retourne null ou la resource du nouveau fichier
    final public static function changeBasename(string $basename,$value)
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $dirname = Path::dirname($path);
            $rename = File::changeBasename($basename,$value);

            if($rename === true)
            {
                $target = Path::addBasename($basename,$dirname);
                $return = static::open($target);
            }
        }

        return $return;
    }


    // changeExtension
    // change l'extension d'une resource fichier, garde le dirname et filename
    // retourne null ou la resource du nouveau fichier
    final public static function changeExtension(string $extension,$value)
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $rename = File::changeExtension($extension,$value);

            if($rename === true)
            {
                $target = Path::changeExtension($extension,$path);
                $return = static::open($target);
            }
        }

        return $return;
    }


    // removeExtension
    // enlève l'extension d'une resource fichier, garde le dirname et filename
    // retourne null ou la resource du nouveau fichier
    final public static function removeExtension($value)
    {
        $return = null;
        $path = static::path($value);

        if(is_string($path))
        {
            $rename = File::removeExtension($value);

            if($rename === true)
            {
                $target = Path::removeExtension($path);
                $return = static::open($target);
            }
        }

        return $return;
    }


    // moveUploaded
    // déplace une resource fichier venant d'être chargé
    final public static function moveUploaded($target,$value)
    {
        $return = null;
        $rename = File::moveUploaded($target,$value);

        if($rename === true)
        $return = static::open($target);

        return $return;
    }


    // copy
    // copy une resource fichier
    final public static function copy($to,$value):?bool
    {
        return File::copy($to,$value);
    }


    // copyInDirname
    // copy une resource fichier, garde le même dirname
    final public static function copyInDirname(string $basename,$value):?bool
    {
        return File::copyInDirname($basename,$value);
    }


    // copyWithBasename
    // copy une resource fichier, garde le même basename
    final public static function copyWithBasename($dirname,$value):?bool
    {
        return File::copyWithBasename($dirname,$value);
    }


    // unlink
    // efface le fichier de la resource, ferme la resource en cas de succès
    // retourne un booléean
    final public static function unlink($value):bool
    {
        $return = File::unlink($value);

        if($return === true)
        static::close($value);

        return $return;
    }


    // close
    // ferme la resource selon le kind
    final public static function close($value):bool
    {
        $return = false;
        $kind = static::kind($value);

        if(!empty($kind))
        {
            if($kind === 'dir')
            {
                $return = true;
                closedir($value);
            }

            elseif($kind === 'curl')
            {
                $return = true;
                curl_close($value);
            }

            elseif($kind === 'finfo')
            $return = finfo_close($value);

            else
            $return = fclose($value);

            unset($value);
        }

        return $return;
    }


    // closes
    // ferme plusieurs ressources
    final public static function closes(...$values):array
    {
        $return = [];

        foreach ($values as $key => &$value)
        {
            $return[$key] = static::close($value);
        }

        return $return;
    }


    // uriSchemeNotWindowsDrive
    // retourne le scheme de l'uri s'il n'y a pas de windows drive
    final protected static function uriSchemeNotWindowsDrive(string $value):?string
    {
        return (!Path::hasWindowsDrive($value))? Uri::scheme($value):null;
    }
}
?>