<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// server
// class that provides a set of methods to analyze the current server
final class Server extends Root
{
    // config
    protected static array $config = [
        'version'=>null, // version courante de quid
        'online'=>'google.com', // domaine à utiliser pour tester si le serveur est online
        'allowSelfSignedCertificate'=>false, // permet le fonctionnement de requête si le certificat ssl est self-signed
    ];


    // isOs
    // retourne vrai si le os est la valeur donné en argument
    final public static function isOs($value):bool
    {
        $os = self::os();
        return is_string($os) && is_string($value) && stripos($os,$value) !== false;
    }


    // isMac
    // retourne vrai si le système est macOs
    final public static function isMac():bool
    {
        return self::isOs('darwin');
    }


    // isWindows
    // retourne vrai si le système est Windows
    final public static function isWindows(bool $ds=true):bool
    {
        $return = false;

        if($ds === true)
        $return = (DIRECTORY_SEPARATOR === '\\');

        else
        $return = self::isOs('win');

        return $return;
    }


    // isLinux
    // retourne vrai si le système est Linux
    final public static function isLinux():bool
    {
        return self::isOs('linux');
    }


    // isSoftware
    // retourne vrai si le software est la valeur donné en argument
    final public static function isSoftware($value):bool
    {
        $software = self::software();
        return is_string($software) && is_string($value) && stripos($software,$value) !== false;
    }


    // isApache
    // retourne vrai si le serveur est apache
    final public static function isApache():bool
    {
        return self::isSoftware('apache');
    }


    // isNginx
    // retourne vrai si le serveur est nginx
    final public static function isNginx():bool
    {
        return self::isSoftware('nginx');
    }


    // isIis
    // retourne vrai si le serveur est iis
    final public static function isIis():bool
    {
        return self::isSoftware('iis');
    }


    // isHttp1
    // retourne vrai si le protocol http utilisé est 1.0 ou 1.1
    final public static function isHttp1():bool
    {
        return in_array(self::httpProtocol(),['HTTP/1','HTTP/1.0','HTTP/1.1'],true);
    }


    // isHttp2
    // retourne vrai si le protocol http utilisé est 2.0
    final public static function isHttp2():bool
    {
        return in_array(self::httpProtocol(),['HTTP/2','HTTP/2.0'],true);
    }


    // isOnline
    // retourne vrai si le serveur a accès à Internet
    final public static function isOnline():bool
    {
        return Network::isOnline(self::$config['online'],80);
    }


    // isOffline
    // retourne vrai si le serveur n'a pas accès à Internet
    final public static function isOffline():bool
    {
        return (self::isOnline() === true)? false:true;
    }


    // isCli
    // retourne vrai si php roule à partir d'un commande line (plutôt qu'un browser)
    final public static function isCli():bool
    {
        return self::sapi() === 'cli' || defined('STDIN');
    }


    // isCaseSensitive
    // retourne vrai si le filesystem est sensible à la casse
    final public static function isCaseSensitive():bool
    {
        return !file_exists(strtoupper(__FILE__)) || !file_exists(strtolower(__FILE__));
    }


    // isCaseInsensitive
    // retourne vrai si le filesystem est insensible à la casse
    final public static function isCaseInsensitive():bool
    {
        return (self::isCaseSensitive() === true)? false:true;
    }


    // isPhpVersion
    // retourne vrai si la version php est égale
    final public static function isPhpVersion(string $value):bool
    {
        return version_compare(self::phpVersion(),$value) === 0;
    }


    // isPhpVersionOlder
    // retourne vrai si la version php est plus vieille que celle fourni
    final public static function isPhpVersionOlder(string $value):bool
    {
        return version_compare(self::phpVersion(),$value) === -1;
    }


    // isPhpVersionNewer
    // retourne vrai si la version php est plus récente que celle fourni
    final public static function isPhpVersionNewer(string $value):bool
    {
        return version_compare(self::phpVersion(),$value) === 1;
    }


    // hasApacheFunctions
    // retourne vrai les fonctions d'apache sont chargés
    final public static function hasApacheFunctions():bool
    {
        return function_exists('apache_get_modules');
    }


    // isApacheModule
    // retourne vrai si le module apache est chargé
    final public static function isApacheModule(string $value):bool
    {
        $modules = self::apacheModules();
        return is_array($modules) && in_array($value,$modules,true);
    }


    // hasApacheModRewrite
    // retourne vrai si le module apache mod rewrite est chargé
    final public static function hasApacheModRewrite():bool
    {
        return self::isApacheModule('mod_rewrite');
    }


    // allowSelfSignedCertificate
    // retourne vrai s'il faut permettre les requêtes dont les certificats SSL sont self-signed
    final public static function allowSelfSignedCertificate():bool
    {
        return static::$config['allowSelfSignedCertificate'];
    }


    // isOwner
    // retourne vrai si value est le même int que celui du user
    final public static function isOwner(int $value,$user=null):bool
    {
        $user ??= self::user();
        return $value === $user;
    }


    // isGroup
    // retourne vrai si value est le même int que celui du groupe
    final public static function isGroup(int $value,$group=null):bool
    {
        $group ??= self::group();
        return $value === $group;
    }


    // isConnectionNormal
    // retourne vrai si la connection est normal
    final public static function isConnectionNormal():bool
    {
        return self::connectionStatus() === CONNECTION_NORMAL;
    }


    // isConnectionAborted
    // retourne vrai si la connection est aborted
    final public static function isConnectionAborted():bool
    {
        return connection_aborted() === 1;
    }


    // isRoot
    // retourne vrai si l'utilisateur éxécutant est root ou système
    // se base seulement sur la superglobale serveur
    final public static function isRoot():bool
    {
        $return = false;
        $serverUser = self::userExecute();

        if(is_string($serverUser))
        {
            $serverUser = strtolower($serverUser);

            if(self::isWindows())
            $return = in_array($serverUser,['system','administrator',true], true);

            else
            $return = ($serverUser === 'root');
        }

        return $return;
    }


    // timeLimit
    // alias pour set_time_limit
    final public static function timeLimit(int $value=0):bool
    {
        return set_time_limit($value);
    }


    // connectionStatus
    // retourne le statut de la connection
    // 0 normal, 1 aborted, 2 timeout, 3 aborted and timeout
    final public static function connectionStatus():int
    {
        return connection_status();
    }


    // ignoreUserAbort
    // n'arrête pas le script si la connection est aborted
    // retourne la valeur courante de ignore_user_abort
    final public static function ignoreUserAbort(bool $value=true):bool
    {
        $return = false;
        $int = ignore_user_abort($value);

        if(is_int($int))
        $return = (bool) ignore_user_abort();

        return $return;
    }


    // phpVersion
    // retourne la version de PHP
    final public static function phpVersion(?string $extension=null):?string
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
    final public static function phpImportantIni():array
    {
        return Ini::important();
    }


    // phpImportantExtension
    // retourne toutes les extensions de php importantes
    final public static function phpImportantExtension(bool $ini=false):array
    {
        return Extension::important($ini);
    }


    // zendVersion
    // retourne la version de Zend
    final public static function zendVersion():string
    {
        return zend_version();
    }


    // quidVersion
    // retourne la version courant de quid
    // retourne une string
    final public static function quidVersion():string
    {
        return self::$config['version'];
    }


    // quidName
    // retourne le nom de quid, utilisez pour useragent email et cli
    final public static function quidName():string
    {
        return 'QUID/'.self::quidVersion().'|PHP/'.self::phpVersion();
    }


    // apacheVersion
    // retourne la version apache
    // peut retourner null si les fonctions apaches ne sont pas disponibles
    final public static function apacheVersion(bool $onlyVersion=false):?string
    {
        $return = null;

        if(self::hasApacheFunctions())
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
    final public static function apacheModules():?array
    {
        return (self::hasApacheFunctions())? apache_get_modules():null;
    }


    // uname
    // retourne un tableau d'informations à propos du système
    final public static function uname():array
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
    final public static function unameKey(string $key):?string
    {
        $return = null;
        $uname = self::uname();
        if(array_key_exists($key,$uname))
        $return = $uname[$key];

        return $return;
    }


    // os
    // retourne le os du serveur
    // mix entre sysname et release
    final public static function os(bool $release=false,bool $type=false):?string
    {
        $return = self::sysname();

        if(is_string($return))
        {
            if($release === true)
            $return .= ' '.self::release();

            if($type === true)
            {
                $type = self::osType();
                if(is_string($type))
                $return .= " ($type)";
            }
        }

        return $return;
    }


    // osType
    // retourne le type de os
    final public static function osType():?string
    {
        $return = null;

        if(self::isMac())
        $return = 'mac';

        elseif(self::isWindows())
        $return = 'windows';

        elseif(self::isLinux())
        $return = 'linux';

        return $return;
    }


    // serverType
    // retourne le type de serveur
    final public static function serverType():?string
    {
        $return = null;

        if(self::isApache())
        $return = 'apache';

        elseif(self::isNginx())
        $return = 'nginx';

        elseif(self::isIis())
        $return = 'iis';

        return $return;
    }


    // sysname
    // retourne le sysname, soit le os
    final public static function sysname():?string
    {
        return self::unameKey('sysname');
    }


    // nodename
    // retourne le nodename, soit le nom du serveur sur le réseau
    final public static function nodename():?string
    {
        return self::unameKey('nodename');
    }


    // release
    // retourne la version de l'os
    final public static function release():?string
    {
        return self::unameKey('release');
    }


    // version
    // retourne la version du kernel
    final public static function version():?string
    {
        return self::unameKey('version');
    }


    // machine
    // retourne l'information sur le type de la machine
    final public static function machine():?string
    {
        return self::unameKey('machine');
    }


    // hostname
    // retourne le hostname du serveur
    final public static function hostname():string
    {
        return gethostname();
    }


    // superglobal
    // retourne la superglobal server
    final public static function superglobal():array
    {
        return Superglobal::server();
    }


    // ip
    // le script utilise gethostbyname ce qui va retourner l'adresse ip public du serveur
    // possible d'inscrire l'autre ip entre paranthèse
    final public static function ip(bool $addr=false):?string
    {
        $return = null;
        $ip = gethostbyname(gethostname());

        if(Ip::is($ip))
        {
            $return = $ip;

            if($addr === true)
            {
                $addr = self::addr(true);
                if($addr !== $return)
                $return .= " ($addr)";
            }
        }

        return $return;
    }


    // addr
    // retourne le IP du serveur à partir de la superglobale server
    // pourrait être l'adresse IP local
    // si pas de ip et normalize est true, retourne 0.0.0.0
    final public static function addr(bool $normalize=true):?string
    {
        $return = Superglobal::getServer('SERVER_ADDR');

        if($normalize === true)
        $return = Ip::normalize(Superglobal::getServer('SERVER_ADDR'));

        return $return;
    }


    // software
    // retourne le logiciel du serveur
    final public static function software():?string
    {
        return Superglobal::getServer('SERVER_SOFTWARE');
    }


    // gatewayInterface
    // retourne l'interface de la gateway du serveur
    final public static function gatewayInterface():?string
    {
        return Superglobal::getServer('GATEWAY_INTERFACE');
    }


    // httpProtocol
    // retourne le protocol http utilisé, toujours en strtoupper
    final public static function httpProtocol():?string
    {
        $return = Superglobal::getServer('SERVER_PROTOCOL');

        if(is_string($return))
        $return = strtoupper($return);

        return $return;
    }


    // sapi
    // retourne le nom de l'interface de liaison entre le serveur et php
    final public static function sapi():string
    {
        return PHP_SAPI;
    }


    // script
    // retourne des informations sur le script courant
    final public static function script():array
    {
        $return = [];

        $return['processId'] = self::processId();
        $return['groupId'] = self::group();
        $return['userId'] = self::user();
        $return['inode'] = getmyinode();
        $return['username'] = self::user(true);
        $return['lastModification'] = getlastmod();

        return $return;
    }


    // processId
    // retourne le id du process courant
    final public static function processId():int
    {
        return getmypid();
    }


    // user
    // retourne l'utilisateur qui possède le script courant, peut être string ou int
    final public static function user(bool $name=false)
    {
        return ($name === true)? get_current_user():getmyuid();
    }


    // userExecute
    // retourne l'utilisateur éxécutant du script si disponible
    // n'utilise pas posix, retourne seulement le nom dans la superglobale serveur sinon null
    final public static function userExecute():?string
    {
        return Superglobal::getServer('USERNAME');
    }


    // userStr
    // retourne le username avec son id entre paranthèse
    // possible d'inclure le nom de l'utilisateur éxécutant le fichier (dans la superglobale server)
    final public static function userStr(bool $withExecute=false):string
    {
        $return = '';
        $id = self::user(false);
        $name = self::user(true);

        if($withExecute === true)
        {
            $execute = self::userExecute();

            if(is_string($execute) && $execute !== $name)
            $return .= $execute.' -> ';
        }

        $return .= $name;
        $return .= " ($id)";

        return $return;
    }


    // group
    // retourne le groupe qui possède le script courant
    final public static function group():int
    {
        return getmygid();
    }


    // email
    // retourne l'adresse courriel de l'admin du serveur
    final public static function email():?string
    {
        return Superglobal::getServer('SERVER_ADMIN');
    }


    // resourceUsage
    // retourne le niveau d'utilisation des resources
    final public static function resourceUsage(int $who=0):array
    {
        return getrusage($who);
    }


    // memory
    // retourne l'usage de mémoire par PHP
    final public static function memory(bool $format=true):array
    {
        $return = [];
        $return['usage'] = memory_get_usage();
        $return['realUsage'] = memory_get_usage(true);
        $return['peakUsage'] = memory_get_peak_usage();
        $return['peakRealUsage'] = memory_get_peak_usage(true);

        if($format === true)
        $return = Arr::map($return,fn($value) => Num::sizeFormat($value));

        return $return;
    }


    // getMemoryChunk
    // retourne un chunk de bites valable en considérant la limite de mémoire
    // utilisé pour les download afin d'éviter que tout le fichier soit mis en mémoire
    final public static function getMemoryChunk(int $divider=10):int
    {
        return (int) (Ini::memoryLimit(1) / $divider);
    }


    // diskSpace
    // retourne l'espace disque sur le serveur
    final public static function diskSpace(string $directory='/',bool $format=true):array
    {
        $return = [];
        $return['free'] = disk_free_space($directory);
        $return['total'] = disk_total_space($directory);

        if($format === true)
        $return = Arr::map($return,fn($value) => Num::sizeFormat($value));

        return $return;
    }


    // phpInfo
    // affiche les informations php
    final public static function phpInfo($what=null):void
    {
        $what = (is_int($what))? $what:INFO_ALL;
        phpinfo($what);
    }


    // overview
    // génère un overview du serveur courant
    final public static function overview():array
    {
        $return = [];
        $return['quid'] = self::quidVersion();
        $return['protocol'] = self::httpProtocol();
        $return['software'] = self::software();
        $return['php'] = self::phpVersion();
        $return['zend'] = self::zendVersion();
        $return['os'] = self::os(true,true);
        $return['serverType'] = self::serverType();
        $return['sapi'] = self::sapi();
        $return['ip'] = self::ip(true);
        $return['hostname'] = self::hostname();
        $return['root'] = self::isRoot();
        $return['username'] = self::userStr(true);
        $return['group'] = self::group();
        $return['caseSensitive'] = self::isCaseSensitive();
        $return = Arr::merge($return,Extension::important(true));

        return $return;
    }


    // info
    // génère un tableau d'information complet sur le serveur courant
    final public static function info(bool $extra=true):array
    {
        $return = [];
        $return['quid'] = self::quidVersion();
        $return['protocol'] = self::httpProtocol();
        $return['software'] = self::software();
        $return['php'] = self::phpVersion();
        $return['zend'] = self::zendVersion();
        $return['os'] = self::os(true,true);
        $return['serverType'] = self::serverType();
        $return['caseSensitive'] = self::isCaseSensitive();
        $return['uname'] = self::uname();
        $return['sapi'] = self::sapi();
        $return['cli'] = self::isCli();
        $return['ip'] = self::ip(true);
        $return['online'] = self::isOnline();
        $return['hostname'] = self::hostname();
        $return['script'] = self::script();
        $return['root'] = self::isRoot();
        $return['user'] = self::user();
        $return['username'] = self::userStr(true);
        $return['group'] = self::group();
        $return['resourceUsage'] = self::resourceUsage();
        $return['memory'] = self::memory();
        $return['diskSpace'] = self::diskSpace();

        if($extra === true)
        {
            $return['phpImportantIni'] = self::phpImportantIni();
            $return['superglobal'] = self::superglobal();
            $return['ini'] = Ini::important();
            $return['extension'] = Extension::important(true);
        }

        return $return;
    }


    // requirement
    // lance les tests de requirement
    final public static function requirement():array
    {
        $return = [];

        if(self::isPhpVersionOlder('7.3'))
        $return[] = 'phpVersion';

        return $return;
    }


    // setAllowSelfSignedCertificate
    // permet de supporter des requêtes dont les certificats sont self-signed
    final public static function setAllowSelfSignedCertificate(bool $value):void
    {
        static::$config['allowSelfSignedCertificate'] = $value;
    }


    // setQuidVersion
    // change la version de quid
    final public static function setQuidVersion($version):void
    {
        if(is_scalar($version))
        self::$config['version'] = $version;
    }
}
?>