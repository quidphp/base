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

// ini
// class with methods a layer over the native PHP ini functions
final class Ini extends Root
{
    // config
    protected static array $config = [
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
            'apc.enabled']
    ];


    // is
    // retourne vrai si la valeur ini existe
    final public static function is($value):bool
    {
        return is_string($value) && array_key_exists($value,self::all());
    }


    // isVarDumpOverloaded
    // retourne vrai si l'extension xdebug est active et var dump est overloadé
    final public static function isVarDumpOverloaded():bool
    {
        return Extension::hasXdebug() && self::xdebug();
    }


    // get
    // retourne une valeur ini
    // si format est int, les ini string vide retournent false
    // le retour est passé dans scalar cast avec maximum de conversion de type
    final public static function get(string $key,?int $format=null)
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
                $return = self::sizeFormat($return,$format);
            }
        }

        return $return;
    }


    // gets
    // retourne plusieurs valeurs ini
    final public static function gets(string ...$keys):array
    {
        $return = [];

        foreach ($keys as $key)
        {
            $return[$key] = self::get($key);
        }

        return $return;
    }


    // set
    // change une valeur ini
    // la valeur est cast en string avant d'être mis dans ini_set
    final public static function set(string $key,$value):bool
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
    final public static function sets(array $array):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_string($key))
            $return[$key] = self::set($key,$value);
        }

        return $return;
    }


    // unset
    // retourne une ou plusieurs valeurs ini à leurs valeurs originales
    final public static function unset(string ...$keys):void
    {
        foreach ($keys as $key)
        {
            ini_restore($key);
        }

        return;
    }


    // all
    // retourne toutes les configurations ini
    final public static function all(?string $extension=null,bool $details=false):array
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
    final public static function parse(string $ini,bool $processSections=false,int $scannerMode=INI_SCANNER_NORMAL):?array
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
    final public static function files():array
    {
        $return = [];
        $return['loaded'] = php_ini_loaded_file();

        $scanned = php_ini_scanned_files();

        if(!empty($scanned))
        {
            $explode = explode(',',$scanned);
            $return['scanned'] = Arr::map($explode,fn($value) => trim($value));
        }

        return $return;
    }


    // sizeFormat
    // format une string de taille dans ini de style 100M et retourne un int en byte
    // si format est true, la valeur est renvoyé dans sizeFormat
    final public static function sizeFormat(string $return,int $format=0)
    {
        if($format >= 1)
        {
            $return = Num::fromSizeFormatMb($return);

            if(is_int($return) && $format === 2)
            $return = Num::sizeFormat($return,$format);
        }

        else
        $return = Str::toInt($return);

        return $return;
    }


    // uploadMaxFilesize
    // retourne la valeur de uploadMaxFilesize
    final public static function uploadMaxFilesize(?int $format=0)
    {
        return self::get('upload_max_filesize',$format);
    }


    // postMaxSize
    // retourne la valeur de postMaxSize
    final public static function postMaxSize(?int $format=0)
    {
        return self::get('post_max_size',$format);
    }


    // memoryLimit
    // retourne la valeur de memoryLimit
    final public static function memoryLimit(?int $format=0)
    {
        return self::get('memory_limit',$format);
    }


    // tempDir
    // retourne le chemin du dossier temporaire tel que spécifié dans ini
    // note ce chemin est souvent vide -> utilise dir::temp
    final public static function tempDir()
    {
        return self::get('sys_temp_dir');
    }


    // getCharset
    // retourne la valeur de default charset
    final public static function getCharset():string
    {
        return self::get('default_charset');
    }


    // setCharset
    // change la valeur de default charset
    final public static function setCharset(string $value):bool
    {
        return self::set('default_charset',$value);
    }


    // getTimezone
    // retourne la valeur de timezone
    final public static function getTimezone():string
    {
        return self::get('date.timezone');
    }


    // setTimezone
    // change la timezone
    final public static function setTimezone(string $value):bool
    {
        return self::set('date.timezone',$value);
    }


    // getTimeLimit
    // applique une limite de temps au process
    final public static function getTimeLimit():int
    {
        return self::get('max_execution_time');
    }


    // setTimeLimit
    // applique une limite de temps au process
    final public static function setTimeLimit($value):bool
    {
        return self::set('max_execution_time',$value);
    }


    // getErrorReporting
    // retourne la valeur actuel de error reporting
    final public static function getErrorReporting()
    {
        return self::get('error_reporting');
    }


    // setErrorReporting
    // change la valeur de error reporting
    final public static function setErrorReporting($value):bool
    {
        return self::set('error_reporting',$value);
    }


    // getErrorLog
    // retourne la valeur du log d'erreur
    final public static function getErrorLog()
    {
        return self::get('error_log');
    }


    // setErrorLog
    // change la valeur de error log
    // la valeur est passé dans finder shortcut
    final public static function setErrorLog(string $value):bool
    {
        return self::set('error_log',Finder::normalize($value));
    }


    // getIncludePathSeparator
    // retourne le séparateur pour include path selon le os du serveur
    final public static function getIncludePathSeparator():string
    {
        return (Server::isWindows())? ';':':';
    }


    // getIncludePath
    // retourne la ou les valeurs actuels de include path
    // retourne un tableau
    final public static function getIncludePath():array
    {
        $return = [];

        $paths = self::get('include_path');
        if(is_string($paths) && !empty($paths))
        $return = explode(self::getIncludePathSeparator(),$paths);

        return $return;
    }


    // setIncludePath
    // change la valeur de include path
    // input peut être tableau ou string
    final public static function setIncludePath($path):bool
    {
        $return = false;

        if(is_array($path) && !empty($path))
        $path = implode(self::getIncludePathSeparator(),$path);

        if(is_string($path))
        $return = self::set('include_path',$path);

        return $return;
    }


    // addIncludePath
    // ajoute un include path à ceux déjà paramétré
    final public static function addIncludePath(string $path):bool
    {
        $return = false;

        if(!empty($path))
        {
            $paths = self::getIncludePath();
            $paths[] = $path;
            $return = self::setIncludePath($paths);
        }

        return $return;
    }


    // opcache
    // retourne vrai si opcache roule
    final public static function opcache():bool
    {
        return !empty(self::get('opcache.enable'));
    }


    // xdebug
    // retourne vrai si xdebug roule
    final public static function xdebug():bool
    {
        return !empty(self::get('xdebug.overload_var_dump'));
    }


    // apcu
    // retourne vrai si apcu roule
    final public static function apcu():bool
    {
        return !empty(self::get('apc.enabled'));
    }


    // important
    // retourne toutes les ini importantes
    final public static function important(?int $format=null):array
    {
        $return = [];
        $important = (array) self::$config['important'];

        foreach ($important as $key => $value)
        {
            $return[$value] = self::get($value,$format);
        }

        return $return;
    }


    // session
    // retourne toutes les ini de session
    final public static function session():array
    {
        return self::all('session');
    }


    // requirement
    // lance les tests de requirement
    final public static function requirement():array
    {
        $return = [];

        if(self::postMaxSize() <= 1)
        $return[] = 'post_max_size_too_small';

        if(self::postMaxSize() < self::uploadMaxFilesize())
        $return[] = 'post_max_size_smaller_than_upload_max_filesize';

        if(self::memoryLimit() < 128)
        $return[] = 'memory_limit';

        return $return;
    }


    // setDefault
    // fait les changements ini par défaut
    final public static function setDefault(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(self::$config['default'],$option);

        if(is_array($option) && !empty($option))
        {
            foreach ($option as $key => $value)
            {
                $return[$key] = self::set($key,$value);
            }
        }

        return $return;
    }
}
?>