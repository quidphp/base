<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// ini
// class with methods a layer over the native PHP ini functions
class Ini extends Root
{
    // config
    public static $config = [
        'default'=>[ // ini à appliquer par défaut lors du chargement
            'default_charset'=>'UTF-8',
            'auto_detect_line_endings'=>true,
            'error_log'=>'error_log',
            'log_errors'=>true,
            'html_errors'=>true,
            'display_errors'=>true,
            'error_reporting'=>-1,
            'date.timezone'=>'America/New_York'],
        'important'=>[ // ini considéré importante
            'opcache.enable',
            'always_populate_raw_post_data',
            'register_argc_argv',
            'request_order',
            'variables_order',
            'auto_detect_line_endings',
            'display_startup_errors',
            'display_errors',
            'error_log',
            'default_charset',
            'html_errors',
            'error_reporting',
            'memory_limit',
            'upload_max_filesize',
            'max_file_uploads',
            'post_max_size',
            'max_input_time',
            'session.gc_maxlifetime',
            'precision',
            'mysql.default_socket',
            'date.timezone',
            'allow_url_fopen',
            'allow_url_include',
            'mail.log',
            'browscap',
            'xdebug.remote_enable',
            'xdebug.coverage_enable',
            'xdebug.profiler_enable',
            'xdebug.overload_var_dump',
            'xdebug.var_display_max_depth',
            'apc.enabled',
            'apc.shm_size',
            'apc.ttl',
            'apc.enable_cli'],
        'session'=>[ // ini lié à session
            'session.save_path',
            'session.name',
            'session.save_handler',
            'session.auto_start',
            'session.gc_probability',
            'session.gc_divisor',
            'session.gc_maxlifetime',
            'session.serialize_handler',
            'session.cookie_lifetime',
            'session.cookie_path',
            'session.cookie_domain',
            'session.cookie_secure',
            'session.cookie_httponly',
            'session.use_strict_mode',
            'session.use_cookies',
            'session.use_only_cookies',
            'session.referer_check',
            'session.cache_limiter',
            'session.cache_expire',
            'session.use_trans_sid',
            'session.trans_sid_tags',
            'session.trans_sid_hosts',
            'session.sid_length',
            'session.sid_bits_per_character',
            'session.upload_progress.enabled',
            'session.upload_progress.cleanup',
            'session.upload_progress.prefix',
            'session.upload_progress.name',
            'session.upload_progress.freq',
            'session.upload_progress.min_freq',
            'session.lazy_write']
    ];


    // is
    // retourne vrai si la valeur ini existe
    public static function is($value):bool
    {
        return (is_string($value) && array_key_exists($value,static::all()))? true:false;
    }


    // isVarDumpOverloaded
    // retourne vrai si l'extension xdebug est active et var dump est overloadé
    public static function isVarDumpOverloaded():bool
    {
        return (Extension::hasXdebug() && static::xdebug())? true:false;
    }


    // get
    // retourne une valeur ini
    // si format est int, les ini string vide retournent false
    // le retour est passé dans scalar cast avec maximum de conversion de type
    public static function get(string $key,?int $format=null)
    {
        $return = null;
        $get = ini_get($key);

        if(is_string($get))
        {
            $return = Scalar::cast($get,1,2);

            if(is_string($return) && is_int($format))
            {
                if($return === '')
                $return = false;

                if(!empty((int) $return))
                $return = static::sizeFormat($return,$format);
            }
        }

        return $return;
    }


    // gets
    // retourne plusieurs valeurs ini
    public static function gets(string ...$keys):array
    {
        $return = [];

        foreach ($keys as $key)
        {
            $return[$key] = static::get($key);
        }

        return $return;
    }


    // set
    // change une valeur ini
    // la valeur est cast en string avant d'être mis dans ini_set
    public static function set(string $key,$value):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;

            if(!empty($key) && ini_set($key,$value) !== false)
            $return = true;
        }

        return $return;
    }


    // sets
    // change pluieurs valeurs ini
    public static function sets(array $array):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_string($key))
            $return[$key] = static::set($key,$value);
        }

        return $return;
    }


    // unset
    // retourne une ou plusieurs valeurs ini à leurs valeurs originales
    public static function unset(string ...$keys):void
    {
        foreach ($keys as $key)
        {
            ini_restore($key);
        }

        return;
    }


    // all
    // retourne toutes les configurations ini
    public static function all(?string $extension=null,bool $details=false):array
    {
        $return = [];

        if(is_string($extension))
        {
            if(Extension::is($extension))
            $return = ini_get_all($extension,$details);
        }

        else
        $return = ini_get_all(null,$details);

        return $return;
    }


    // parse
    // parse un fichier ou une string ini et retourne un tableau
    public static function parse(string $ini,bool $processSections=false,int $scannerMode=INI_SCANNER_NORMAL):?array
    {
        $return = null;

        if(!empty($ini))
        {
            if(is_file($ini))
            $return = parse_ini_file($ini,$processSections,$scannerMode);
            else
            $return = parse_ini_string($ini,$processSections,$scannerMode);
        }

        return $return;
    }


    // files
    // retourne tous les fichiers ini loaded ou scanned
    public static function files():array
    {
        $return = [];
        $return['loaded'] = php_ini_loaded_file();

        $scanned = php_ini_scanned_files();

        if(!empty($scanned))
        $return['scanned'] = array_map('trim',explode(',',$scanned));

        return $return;
    }


    // sizeFormat
    // format une string de taille dans ini de style 100M et retourne un int en byte
    // si format est true, la valeur est renvoyé dans sizeFormat
    public static function sizeFormat(string $return,int $format=0)
    {
        if($format >= 1)
        {
            $return = Number::fromSizeFormatMb($return);

            if(is_int($return) && $format === 2)
            $return = Number::sizeFormat($return,$format);
        }

        else
        $return = Number::castFromString($return);

        return $return;
    }


    // uploadMaxFilesize
    // retourne la valeur de uploadMaxFilesize
    public static function uploadMaxFilesize(?int $format=0)
    {
        return static::get('upload_max_filesize',$format);
    }


    // postMaxSize
    // retourne la valeur de postMaxSize
    public static function postMaxSize(?int $format=0)
    {
        return static::get('post_max_size',$format);
    }


    // memoryLimit
    // retourne la valeur de memoryLimit
    public static function memoryLimit(?int $format=0)
    {
        return static::get('memory_limit',$format);
    }


    // tempDir
    // retourne le chemin du dossier temporaire tel que spécifié dans ini
    // note ce chemin est souvent vide -> utilise dir::temp
    public static function tempDir()
    {
        return static::get('sys_temp_dir');
    }


    // getCharset
    // retourne la valeur de default charset
    public static function getCharset():string
    {
        return static::get('default_charset');
    }


    // setCharset
    // change la valeur de default charset
    public static function setCharset(string $value):bool
    {
        return static::set('default_charset',$value);
    }


    // getTimezone
    // retourne la valeur de timezone
    public static function getTimezone():string
    {
        return static::get('date.timezone');
    }


    // setTimezone
    // change la timezone
    public static function setTimezone(string $value):bool
    {
        return static::set('date.timezone',$value);
    }


    // getTimeLimit
    // applique une limite de temps au process
    public static function getTimeLimit():int
    {
        return static::get('max_execution_time');
    }


    // setTimeLimit
    // applique une limite de temps au process
    public static function setTimeLimit($value):bool
    {
        return static::set('max_execution_time',$value);
    }


    // getErrorReporting
    // retourne la valeur actuel de error reporting
    public static function getErrorReporting()
    {
        return static::get('error_reporting');
    }


    // setErrorReporting
    // change la valeur de error reporting
    public static function setErrorReporting($value):bool
    {
        return static::set('error_reporting',$value);
    }


    // getErrorLog
    // retourne la valeur du log d'erreur
    public static function getErrorLog()
    {
        return static::get('error_log');
    }


    // setErrorLog
    // change la valeur de error log
    // la valeur est passé dans finder shortcut
    public static function setErrorLog(string $value):bool
    {
        return static::set('error_log',Finder::normalize($value));
    }


    // getIncludePathSeparator
    // retourne le séparateur pour include path selon le os du serveur
    public static function getIncludePathSeparator():string
    {
        return (Server::isWindows())? ';':':';
    }


    // getIncludePath
    // retourne la ou les valeurs actuels de include path
    // retourne un tableau
    public static function getIncludePath():array
    {
        $return = [];

        $paths = static::get('include_path');
        if(is_string($paths) && !empty($paths))
        $return = explode(static::getIncludePathSeparator(),$paths);

        return $return;
    }


    // setIncludePath
    // change la valeur de include path
    // input peut être tableau ou string
    public static function setIncludePath($path):bool
    {
        $return = false;

        if(is_array($path) && !empty($path))
        $path = implode(static::getIncludePathSeparator(),$path);

        if(is_string($path))
        $return = static::set('include_path',$path);

        return $return;
    }


    // addIncludePath
    // ajoute un include path à ceux déjà paramétré
    public static function addIncludePath(string $path):bool
    {
        $return = false;

        if(!empty($path))
        {
            $paths = static::getIncludePath();
            $paths[] = $path;
            $return = static::setIncludePath($paths);
        }

        return $return;
    }


    // opcache
    // retourne vrai si opcache roule
    public static function opcache():bool
    {
        return (!empty(static::get('opcache.enable')))? true:false;
    }


    // xdebug
    // retourne vrai si xdebug roule
    public static function xdebug():bool
    {
        return (!empty(static::get('xdebug.overload_var_dump')))? true:false;
    }


    // apcu
    // retourne vrai si apcu roule
    public static function apcu():bool
    {
        return (!empty(static::get('apc.enabled')))? true:false;
    }


    // important
    // retourne toutes les ini importantes
    public static function important(?int $format=null):array
    {
        $return = [];
        $important = (array) static::$config['important'];

        foreach ($important as $key => $value)
        {
            $return[$value] = static::get($value,$format);
        }

        return $return;
    }


    // session
    // retourne toutes les ini de session
    public static function session(?int $format=null):array
    {
        $return = [];
        $session = (array) static::$config['session'];

        foreach ($session as $key => $value)
        {
            $return[$value] = static::get($value,$format);
        }

        return $return;
    }


    // requirement
    // lance les tests de requirement
    public static function requirement():array
    {
        $return = [];

        if(static::postMaxSize() <= 1)
        $return[] = 'post_max_size_too_small';

        if(static::postMaxSize() < static::uploadMaxFilesize())
        $return[] = 'post_max_size_smaller_than_upload_max_filesize';

        if(static::memoryLimit() < 128)
        $return[] = 'memory_limit';

        if(!static::get('browscap'))
        $return[] = 'browscap';

        return $return;
    }


    // setDefault
    // fait les changements ini par défaut
    public static function setDefault(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(static::$config['default'],$option);

        if(is_array($option) && !empty($option))
        {
            foreach ($option as $key => $value)
            {
                $return[$key] = static::set($key,$value);
            }
        }

        return $return;
    }
}
?>