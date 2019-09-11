<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// server
// class that provides a set of methods to analyze the current server
class Server extends Root
{
    // config
    public static $config = [
        'version'=>null, // version courante de quid
        'online'=>'google.com' // domaine à utiliser pour tester si le serveur est online
    ];


    // isMac
    // retourne vrai si le système est macOs
    public static function isMac():bool
    {
        $return = false;
        $os = static::os();

        if(stripos($os,'darwin') === 0)
        $return = true;

        return $return;
    }


    // isWindows
    // retourne vrai si le système est Windows
    public static function isWindows(bool $fast=true):bool
    {
        $return = false;

        if($fast === true)
        $return = (DIRECTORY_SEPARATOR === '\\')? true:false;

        else
        {
            $os = static::os();

            if(stripos($os,'win') === 0)
            $return = true;
        }

        return $return;
    }


    // isLinux
    // retourne vrai si le système est Linux
    public static function isLinux():bool
    {
        $return = false;
        $os = static::os();

        if(stripos($os,'linux') === 0)
        $return = true;

        return $return;
    }


    // isApache
    // retourne vrai si le serveur est sur apache
    public static function isApache():bool
    {
        return (strpos(static::software(),'Apache') !== false)? true:false;
    }


    // isIis
    // retourne vrai si le serveur est sur iis
    public static function isIis():bool
    {
        return (strpos(static::software(),'IIS') !== false)? true:false;
    }


    // isHttp1
    // retourne vrai si le protocol http utilisé est 1.0 ou 1.1
    public static function isHttp1():bool
    {
        return (in_array(static::httpProtocol(),['HTTP/1','HTTP/1.0','HTTP/1.1'],true))? true:false;
    }


    // isHttp2
    // retourne vrai si le protocol http utilisé est 2.0
    public static function isHttp2():bool
    {
        return (in_array(static::httpProtocol(),['HTTP/2','HTTP/2.0'],true))? true:false;
    }


    // isOnline
    // retourne vrai si le serveur a accès à Internet
    public static function isOnline():bool
    {
        return Network::isOnline(static::$config['online'],80);
    }


    // isOffline
    // retourne vrai si le serveur n'a pas accès à Internet
    public static function isOffline():bool
    {
        return (static::isOnline() === true)? false:true;
    }


    // isCli
    // retourne vrai si php roule à partir d'un commande line (plutôt qu'un browser)
    public static function isCli():bool
    {
        return (static::sapi() === 'cli' || defined('STDIN'))? true:false;
    }


    // isCaseSensitive
    // retourne vrai si le filesystem est sensible à la casse
    public static function isCaseSensitive():bool
    {
        return (!file_exists(strtoupper(__FILE__)) || !file_exists(strtolower(__FILE__)))? true:false;
    }


    // isCaseInsensitive
    // retourne vrai si le filesystem est insensible à la casse
    public static function isCaseInsensitive():bool
    {
        return (static::isCaseSensitive() === true)? false:true;
    }


    // isPhpVersion
    // retourne vrai si la version php est égale
    public static function isPhpVersion(string $value):bool
    {
        return (version_compare(static::phpVersion(),$value) === 0)? true:false;
    }


    // isPhpVersionOlder
    // retourne vrai si la version php est plus vieille que celle fourni
    public static function isPhpVersionOlder(string $value):bool
    {
        return (version_compare(static::phpVersion(),$value) === -1)? true:false;
    }


    // isPhpVersionNewer
    // retourne vrai si la version php est plus récente que celle fourni
    public static function isPhpVersionNewer(string $value):bool
    {
        return (version_compare(static::phpVersion(),$value) === 1)? true:false;
    }


    // hasApacheFunctions
    // retourne vrai les fonctions d'apache sont chargés
    public static function hasApacheFunctions():bool
    {
        return (function_exists('apache_get_modules'))? true:false;
    }


    // isApacheModule
    // retourne vrai si le module apache est chargé
    public static function isApacheModule(string $value):bool
    {
        $return = false;
        $modules = static::apacheModules();

        if(is_array($modules) && in_array($value,$modules,true))
        $return = true;

        return $return;
    }


    // hasApacheModRewrite
    // retourne vrai si le module apache mod rewrite est chargé
    public static function hasApacheModRewrite():bool
    {
        return static::isApacheModule('mod_rewrite');
    }


    // isOwner
    // retourne vrai si value est le même int que celui du user
    public static function isOwner(int $value,$user=null):bool
    {
        $return = false;
        $user = ($user === null)? self::user():$user;

        if($value === $user)
        $return = true;

        return $return;
    }


    // isGroup
    // retourne vrai si value est le même int que celui du groupe
    public static function isGroup(int $value,$group=null):bool
    {
        $return = false;
        $group = ($group === null)? self::group():$group;

        if($value === $group)
        $return = true;

        return $return;
    }


    // isConnectionNormal
    // retourne vrai si la connection est normal
    public static function isConnectionNormal():bool
    {
        return (static::connectionStatus() === CONNECTION_NORMAL)? true:false;
    }


    // isConnectionAborted
    // retourne vrai si la connection est aborted
    public static function isConnectionAborted():bool
    {
        return (connection_aborted() === 1)? true:false;
    }


    // timeLimit
    // alias pour set_time_limit
    public static function timeLimit(int $value=0):bool
    {
        return set_time_limit($value);
    }


    // connectionStatus
    // retourne le statut de la connection
    // 0 normal, 1 aborted, 2 timeout, 3 aborted and timeout
    public static function connectionStatus():int
    {
        return connection_status();
    }


    // ignoreUserAbort
    // n'arrête pas le script si la connection est aborted
    // retourne la valeur courante de ignore_user_abort
    public static function ignoreUserAbort(bool $value=true):bool
    {
        $return = false;
        $int = ignore_user_abort($value);

        if(is_int($int))
        $return = (bool) ignore_user_abort();

        return $return;
    }


    // phpVersion
    // retourne la version de PHP
    public static function phpVersion(?string $extension=null):?string
    {
        $return = null;

        if(is_string($extension))
        $return = phpversion($extension);

        else
        $return = PHP_VERSION;

        return $return;
    }


    // phpImportantIni
    // retourne toutes les ini de php importantes
    public static function phpImportantIni():array
    {
        return Ini::important();
    }


    // zendVersion
    // retourne la version de Zend
    public static function zendVersion():string
    {
        return zend_version();
    }


    // quidVersion
    // retourne la version courant de quid
    // retourne une string
    public static function quidVersion():string
    {
        return static::$config['version'];
    }


    // apacheVersion
    // retourne la version apache
    // peut retourner null si les fonctions apaches ne sont pas disponibles
    public static function apacheVersion(bool $onlyVersion=false):?string
    {
        $return = null;

        if(static::hasApacheFunctions())
        {
            $return = apache_get_version();

            if($onlyVersion === true)
            {
                $return = Str::stripAfter(' ',$return);
                $return = Str::stripBefore('/',$return,false);
            }
        }

        return $return;
    }


    // apacheModules
    // retourne les modules apaches actifs
    // peut retourner null si les fonctions apaches ne sont pas disponibles
    public static function apacheModules():?array
    {
        return (static::hasApacheFunctions())? apache_get_modules():null;
    }


    // uname
    // retourne un tableau d'informations à propos du système
    public static function uname():array
    {
        $return = [];
        $return['sysname'] = php_uname('s');
        $return['nodename'] = php_uname('n');
        $return['release'] = php_uname('r');
        $return['version'] = php_uname('v');
        $return['machine'] = php_uname('m');

        return $return;
    }


    // unameKey
    // retourne une clé du tableau uname
    public static function unameKey(string $key):?string
    {
        $return = null;
        $uname = static::uname();
        if(array_key_exists($key,$uname))
        $return = $uname[$key];

        return $return;
    }


    // os
    // retourne le os du serveur
    // mix entre sysname et release
    public static function os(bool $release=false):?string
    {
        $return = static::sysname();

        if($release === true)
        $return .= ' '.static::release();

        return $return;
    }


    // osType
    // retourne le type de os
    public static function osType():?string
    {
        $return = null;

        if(static::isMac())
        $return = 'mac';

        elseif(static::isWindows())
        $return = 'windows';

        elseif(static::isLinux())
        $return = 'linux';

        return $return;
    }


    // sysname
    // retourne le sysname, soit le os
    public static function sysname():?string
    {
        return static::unameKey('sysname');
    }


    // nodename
    // retourne le nodename, soit le nom du serveur sur le réseau
    public static function nodename():?string
    {
        return static::unameKey('nodename');
    }


    // release
    // retourne la version de l'os
    public static function release():?string
    {
        return static::unameKey('release');
    }


    // version
    // retourne la version du kernel
    public static function version():?string
    {
        return static::unameKey('version');
    }


    // machine
    // retourne l'information sur le type de la machine
    public static function machine():?string
    {
        return static::unameKey('machine');
    }


    // hostname
    // retourne le hostname du serveur
    public static function hostname():string
    {
        return gethostname();
    }


    // superglobal
    // retourne la superglobal server
    public static function superglobal():array
    {
        return Superglobal::server();
    }


    // ip
    // retourne le IP du serveur
    // pourrait être l'adresse IP local
    public static function ip():?string
    {
        return Ip::normalize(Superglobal::getServer('SERVER_ADDR'));
    }


    // ipPublic
    // le script utilise gethostbyname ce qui va retourner l'adresse ip public du serveur
    public static function ipPublic():?string
    {
        $return = null;
        $ip = gethostbyname(gethostname());

        if(Ip::is($ip))
        $return = $ip;

        return $return;
    }


    // software
    // retourne le logiciel du serveur
    public static function software():?string
    {
        return Superglobal::getServer('SERVER_SOFTWARE');
    }


    // gatewayInterface
    // retourne l'interface de la gateway du serveur
    public static function gatewayInterface():?string
    {
        return Superglobal::getServer('GATEWAY_INTERFACE');
    }


    // httpProtocol
    // retourne le protocol http utilisé, toujours en strtoupper
    public static function httpProtocol():?string
    {
        return strtoupper(Superglobal::getServer('SERVER_PROTOCOL'));
    }


    // sapi
    // retourne le nom de l'interface de liaison entre le serveur et php
    public static function sapi():string
    {
        return PHP_SAPI;
    }


    // script
    // retourne des informations sur le script courant
    public static function script():array
    {
        $return = [];

        $return['processId'] = static::processId();
        $return['groupId'] = static::group();
        $return['userId'] = static::user();
        $return['inode'] = getmyinode();
        $return['username'] = static::user(true);
        $return['lastModification'] = getlastmod();

        return $return;
    }


    // processId
    // retourne le id du process courant
    public static function processId():int
    {
        return getmypid();
    }


    // user
    // retourne l'utilisateur courant
    // peut être string ou int
    public static function user(bool $name=false)
    {
        return ($name === true)? get_current_user():getmyuid();
    }


    // group
    // retourne le groupe courant
    public static function group():int
    {
        return getmygid();
    }


    // email
    // retoure l'adresse courriel de l'admin du serveur
    public static function email():?string
    {
        return Superglobal::getServer('SERVER_ADMIN');
    }


    // resourceUsage
    // retourne le niveau d'utilisation des resources
    public static function resourceUsage(int $who=0):array
    {
        return getrusage($who);
    }


    // memory
    // retourne l'usage de mémoire par PHP
    public static function memory(bool $format=true):array
    {
        $return = [];
        $return['usage'] = memory_get_usage();
        $return['realUsage'] = memory_get_usage(true);
        $return['peakUsage'] = memory_get_peak_usage();
        $return['peakRealUsage'] = memory_get_peak_usage(true);

        if($format === true)
        $return = array_map([Number::class,'sizeFormat'],$return);

        return $return;
    }


    // diskSpace
    // retourne l'espace disque sur le serveur
    public static function diskSpace(string $directory='/',bool $format=true):array
    {
        $return = [];
        $return['free'] = disk_free_space($directory);
        $return['total'] = disk_total_space($directory);

        if($format === true)
        $return = array_map([Number::class,'sizeFormat'],$return);

        return $return;
    }


    // phpInfo
    // affiche les informations php
    public static function phpInfo($what=null):void
    {
        $what = (is_int($what))? $what:INFO_ALL;
        phpinfo($what);
    }


    // overview
    // génère un overview du serveur courant
    public static function overview():array
    {
        $return = [];
        $return['quid'] = static::quidVersion();
        $return['protocol'] = static::httpProtocol();
        $return['software'] = static::software();
        $return['php'] = static::phpVersion();
        $return['zend'] = static::zendVersion();
        $return['os'] = static::os(true);
        $return['osType'] = static::osType();
        $return['sapi'] = static::sapi();
        $return['ip'] = static::ip();
        $return['hostname'] = static::hostname();
        $return['user'] = static::user();
        $return['username'] = static::user(true);
        $return['group'] = static::group();
        $return['caseSensitive'] = static::isCaseSensitive();
        $return = Arr::append($return,Extension::important(true));

        return $return;
    }


    // info
    // génère un tableau d'information complet sur le serveur courant
    public static function info():array
    {
        $return = [];
        $return['quid'] = static::quidVersion();
        $return['protocol'] = static::httpProtocol();
        $return['software'] = static::software();
        $return['php'] = static::phpVersion();
        $return['zend'] = static::zendVersion();
        $return['os'] = static::os(true);
        $return['osType'] = static::osType();
        $return['caseSensitive'] = static::isCaseSensitive();
        $return['uname'] = static::uname();
        $return['sapi'] = static::sapi();
        $return['cli'] = static::isCli();
        $return['ip'] = static::ip();
        $return['ipPublic'] = static::ipPublic();
        $return['online'] = static::isOnline();
        $return['hostname'] = static::hostname();
        $return['script'] = static::script();
        $return['user'] = static::user();
        $return['username'] = static::user(true);
        $return['group'] = static::group();
        $return['resourceUsage'] = static::resourceUsage();
        $return['memory'] = static::memory();
        $return['diskSpace'] = static::diskSpace();
        $return['phpImportantIni'] = static::phpImportantIni();
        $return['superglobal'] = static::superglobal();
        $return['ini'] = Ini::important();
        $return['extension'] = Extension::important(true);

        return $return;
    }


    // requirement
    // lance les tests de requirement
    public static function requirement():array
    {
        $return = [];

        if(static::isPhpVersionOlder('7.2'))
        $return[] = 'phpVersion';

        if(!static::isApache())
        $return[] = 'apache';

        return $return;
    }


    // setQuidVersion
    // change la version de quid
    public static function setQuidVersion($version):void
    {
        if(is_scalar($version))
        static::$config['version'] = $version;

        return;
    }
}
?>