<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// session
// class with static methods to manage a session (built over the native PHP session functions)
class Session extends Root
{
    // config
    public static $config = [
        'default'=>[
            'name'=>true, // le nom de la session, si true utilise type
            'sid'=>null, // permet de set un sid avant le lancement de la session
            'prefix'=>true, // prefix du sid, si true utilise type
            'lifetime'=>null, // spécifie la lifetime de la session
            'cacheExpire'=>3600, // l'expiration du cache http
            'cacheLimiter'=>'', // le module de cache http, cette valeur a le dessus sur les défaut dans réponse
            'module'=>'files', // le module utilisé
            'serializeHandler'=>'php_serialize', // handler de serialization
            'savePath'=>null, // la valeur savePath
            'cookieParams'=>[
                'lifetime'=>(3600 * 24 * 30), // durée de vie, 0 signifie fermeture du browser
                'expires'=>null, // timestamp d'expiration de la session, a priorité sur lifetime
                'path'=>'/', // chemin dans le domaine
                'domain'=>'', // ce paramètre est étrange, le plus strict est de laisser domain comme chaîne vide
                'secure'=>null, // cookie doit être servis via https
                'httponly'=>true, // cookie ne peut pas être modifié dans javaScript
                'samesite'=>'Lax'], // une requête post externe termine la session
            'garbageCollect'=>[
                'probability'=>1, // probabilité de garbage collect, mettre 0 pour off
                'divisor'=>1000, // probabilité de garbage collect - diviseur
                'lifetime'=>(3600 * 24 * 30), // durée de vie d'une session, peut effacer après ce délai
                'expires'=>null, // timestamp d'expiration de la session, a priorité sur lifetime
                'buffer'=>3600], // temps additionnelle, à additionner sur lifetime et expire
            'ini'=>[ // autres ini à mettre comme défaut
                'session.use_strict_mode'=>1,
                'session.use_cookies'=>1,
                'session.use_only_cookies'=>1,
                'session.sid_length'=>40,
                'session.sid_bits_per_character'=>5,
                'session.lazy_write'=>1],
            'env'=>null, // environnement, pour structureEnv
            'type'=>null, // type, pour structureType
            'version'=>null, // version, pour structureVersion
            'versionMatch'=>true, // si la version doit match lors de structureHistory
            'userAgentMatch'=>true, // si le userAgent doit match lors de structureUserAgent
            'fingerprintKeys'=>null], // les clés à utiliser pour générer le fingerprint, si vide pas de validation de fingerprint
        'cacheLimiter'=>['public','private_no_expire','private','nocache',''], // valeur possible pour cacheLimiter
        'module'=>['files','user','memcached','redis','rediscluster'], // valeur possible pour module
        'serializeHandler'=>['php_serialize','php','php_binary','wddx','igbinary'], // valeur possible pour serializer handler
        'captcha'=>['name'=>'-captcha-','possible'=>'alphaUpper','length'=>6,'csprng'=>false,'sensitive'=>false], // configuration pour générer une chaîne captcha
        'csrf'=>['name'=>'-csrf-','possible'=>null,'length'=>40,'csprng'=>false], // configuration pour générer une clé csrf
        'structure'=>[  // structure de la session avec clé et callback
            'env'=>[self::class,'structureEnv'],
            'type'=>[self::class,'structureType'],
            'version'=>[self::class,'structureVersion'],
            'expire'=>[self::class,'structureExpire'],
            'timestamp'=>[self::class,'structureTimestamp'],
            'requestCount'=>[self::class,'structureRequestCount'],
            'userAgent'=>[self::class,'structureUserAgent'],
            'ip'=>[self::class,'structureIp'],
            'fingerprint'=>[self::class,'structureFingerprint'],
            'lang'=>[self::class,'structureLang'],
            'csrf'=>[self::class,'structureCsrf'],
            'captcha'=>[self::class,'structureCaptcha'],
            'remember'=>[self::class,'structureRemember']]
    ];


    // handler
    protected static $handler = null; // garde une copie du handler, utiliser lors de setSaveHandler


    // is
    // retourne vrai si la valeur existe dans le tableau superglobale session
    // la session doit être active
    final public static function is($value):bool
    {
        return Superglobal::sessionExists($value);
    }


    // isLang
    // retourne vrai si la langue est celle fourni
    // la session doit être active
    final public static function isLang($value):bool
    {
        return (is_string($value) && $value === static::lang())? true:false;
    }


    // isIp
    // retourne vrai si le ip est celui fourni
    // la session doit être active
    final public static function isIp($value):bool
    {
        return (is_string($value) && $value === static::ip())? true:false;
    }


    // isCsrf
    // retourne vrai si le csrf est celui fourni
    // la session doit être active
    final public static function isCsrf($value):bool
    {
        return (is_string($value) && $value === static::csrf())? true:false;
    }


    // isCaptcha
    // retourne vrai si le captcha est celui fourni
    // possible de définir si la comparaison est sensible ou non à la case
    // la session doit être active
    final public static function isCaptcha($value,?bool $sensitive=null):bool
    {
        $return = false;

        if(is_string($value))
        {
            $captcha = static::captcha();

            if($sensitive === null)
            {
                $option = static::getCaptchaOption();
                $sensitive = $option['sensitive'] || false;
            }

            if($sensitive === true && $value === $captcha)
            $return = true;

            elseif($sensitive === false && Str::icompare($value,$captcha))
            $return = true;
        }

        return $return;
    }


    // isDesktop
    // retourne vrai si le useragent est desktop
    // la session doit être active
    final public static function isDesktop():bool
    {
        return Browser::isDesktop(static::userAgent())? true:false;
    }


    // isMobile
    // retourne vrai si le useragent est mobile
    // la session doit être active
    final public static function isMobile():bool
    {
        return Browser::isMobile(static::userAgent())? true:false;
    }


    // isOldIe
    // retourne vrai si le useragent est Internet Explorer < 9
    // la session doit être active
    final public static function isOldIe():bool
    {
        return Browser::isOldIe(static::userAgent())? true:false;
    }


    // isMac
    // retourne vrai si le useragent est sur MacOs
    // la session doit être active
    final public static function isMac():bool
    {
        return Browser::isMac(static::userAgent())? true:false;
    }


    // isLinux
    // retourne vrai si le useragent est sur Linux
    // la session doit être active
    final public static function isLinux():bool
    {
        return Browser::isLinux(static::userAgent())? true:false;
    }


    // isWindows
    // retourne vrai si le useragent est sur Windows
    // la session doit être active
    final public static function isWindows():bool
    {
        return Browser::isWindows(static::userAgent())? true:false;
    }


    // isBot
    // retourne vrai si le userAgent est celui d'un bot
    // la session doit être active
    final public static function isBot():bool
    {
        return (Browser::isBot(static::userAgent()))? true:false;
    }


    // isStarted
    // retourne vrai si la session est active
    final public static function isStarted():bool
    {
        return (session_status() === PHP_SESSION_ACTIVE)? true:false;
    }


    // isEmpty
    // retourne vrai si la session est démarré et vide
    final public static function isEmpty():bool
    {
        return (static::isStarted() && empty(Superglobal::session()))? true:false;
    }


    // isNotEmpty
    // retourne vrai si la session est démarré et pas vide
    final public static function isNotEmpty():bool
    {
        return (static::isStarted() && !empty(Superglobal::session()))? true:false;
    }


    // hasSaveHandler
    // retourne vrai si la classe a un save handler
    final public static function hasSaveHandler():bool
    {
        return (!empty(static::$handler))? true:false;
    }


    // isStructureValid
    // vérifie que la structure de la session actuelle est valide
    // lance le init avant le is, le init permet par exemple d'aller chercher un objet row à partir d'une int
    // value fait référence au tableau structureCallback, si tableau est vide la session est valide par défaut
    // la session doit être active
    final public static function isStructureValid($value=true):bool
    {
        $return = false;

        if(static::isStarted())
        {
            $callback = static::getStructure($value);

            if(!empty($callback))
            {
                foreach ($callback as $k => $v)
                {
                    $return = false;

                    if(static::isCallable($v))
                    {
                        $val = static::get($k);

                        if($v('is',$val) === true)
                        $return = true;
                    }

                    if($return === false)
                    break;
                }
            }

            else
            $return = true;
        }

        return $return;
    }


    // status
    // retourne le code status de la session
    final public static function status():int
    {
        return session_status();
    }


    // ini
    // retourne toutes les ini de session
    final public static function ini(?int $format=null):array
    {
        return Ini::session($format);
    }


    // getSid
    // retourne le id courant de la session
    final public static function getSid():string
    {
        return session_id();
    }


    // setSid
    // change le id de la session
    final public static function setSid(string $value):bool
    {
        $return = false;

        if(!is_numeric($value) && !empty($value))
        {
            session_id($value);
            if(session_id() === $value)
            $return = true;
        }

        return $return;
    }


    // createSid
    // crée un id de session
    // le id sera unique si la session est ouverte
    final public static function createSid(?string $prefix=null):string
    {
        $return = '';

        if(is_string($prefix))
        $return = session_create_id($prefix);

        else
        $return = session_create_id();

        return $return;
    }


    // validateId
    // validate un id de session
    final public static function validateId(string $id,?string $prefix=null):bool
    {
        $return = false;

        $length = Ini::get('session.sid_length');
        if(is_int($length) && ($prefix === null || (!empty($prefix) && strpos($id,$prefix) === 0)))
        {
            if(is_string($prefix))
            $length += strlen($prefix);

            if($length === strlen($id))
            $return = true;
        }

        return $return;
    }


    // getPrefix
    // retourne le prefix du sid
    // si prefix est true, utilise le type
    final public static function getPrefix():?string
    {
        $return = static::$config['default']['prefix'] ?? null;

        if($return === true)
        {
            $return = static::$config['default']['type'] ?? null;
            if(is_string($return))
            $return .= '-';
        }

        return $return;
    }


    // name
    // retourne le nom de la session
    final public static function name():string
    {
        return session_name();
    }


    // setName
    // change le nom de la session
    // la session ne doit pas être active
    // si value est true, utilise le type
    final public static function setName($value):bool
    {
        $return = false;

        if($value === true)
        $value = static::$config['default']['type'] ?? null;

        if(is_string($value) && !is_numeric($value) && !static::isStarted())
        {
            session_name($value);
            if(session_name() === $value)
            $return = true;
        }

        return $return;
    }


    // getCacheExpire
    // retourne la valeur des minutes d'expiration de la cache
    final public static function getCacheExpire():int
    {
        return session_cache_expire();
    }


    // setCacheExpire
    // change la valeur des minutes d'expiration de la cache
    // la session ne doit pas être active
    final public static function setCacheExpire(int $value):bool
    {
        $return = false;

        if($value >= 0 && !static::isStarted())
        {
            session_cache_expire($value);
            if(session_cache_expire() === $value)
            $return = true;
        }

        return $return;
    }


    // getCacheLimiter
    // retourne les informations relatives au type de limiteur de cache http généré par la session
    final public static function getCacheLimiter():string
    {
        return session_cache_limiter();
    }


    // setCacheLimiter
    // change le type de cache limiter
    // la session ne doit pas être active
    final public static function setCacheLimiter(string $value):bool
    {
        $return = false;

        if(in_array($value,static::$config['cacheLimiter'],true) && !static::isStarted())
        {
            session_cache_limiter($value);
            if(session_cache_limiter() === $value)
            $return = true;
        }

        return $return;
    }


    // getModule
    // retourne le module de la session
    final public static function getModule():string
    {
        return session_module_name();
    }


    // setModule
    // change le module de la session
    // la session ne doit pas être active
    final public static function setModule(string $value):bool
    {
        $return = false;

        if(in_array($value,static::$config['module'],true) && !static::isStarted())
        {
            session_module_name($value);
            if(session_module_name() === $value)
            $return = true;
        }

        return $return;
    }


    // getSaveHandler
    // retourne le savehandler
    final public static function getSaveHandler():?object
    {
        return static::$handler;
    }


    // setSaveHandler
    // change le save handler de session, permet de seulement de mettre un objet
    // registerShutdown enregistre session::commit comme shutdown function
    // la session ne doit pas être active
    final public static function setSaveHandler(object $handler,bool $registerShutdown=true):bool
    {
        $return = false;

        if(!static::isStarted())
        {
            $return = session_set_save_handler($handler,true);

            if($return === true)
            static::$handler = $handler;
        }

        return $return;
    }


    // getSerializeHandler
    // retourne le handler de serialization
    final public static function getSerializeHandler():string
    {
        return Ini::get('session.serialize_handler');
    }


    // setSerializeHandler
    // change le handler de serialization
    // la session ne doit pas être active
    final public static function setSerializeHandler(string $value):bool
    {
        $return = false;

        if(in_array($value,static::$config['serializeHandler'],true) && !static::isStarted())
        $return = Ini::set('session.serialize_handler',$value);

        return $return;
    }


    // getSavePath
    // retourne le savepath de la session
    // possible de passer le chemin dans la finder/shortcut
    final public static function getSavePath(bool $shortcut=false):string
    {
        $return = session_save_path();

        if($shortcut === true)
        $return = Finder::normalize($return);

        return $return;
    }


    // setSavePath
    // change le savepath de la session
    // la session ne doit pas être active
    final public static function setSavePath(string $value):bool
    {
        $return = false;

        if(!static::isStarted())
        {
            session_save_path($value);
            if(session_save_path() === $value)
            $return = true;
        }

        return $return;
    }


    // getLifetime
    // retourne la durée de vie de la session
    final public static function getLifetime():?int
    {
        $return = null;
        $cookie = static::getCookieParams();
        $gc = Ini::get('session.gc_maxlifetime');

        if(array_key_exists('lifetime',$cookie) && is_int($cookie['lifetime']))
        $return = $cookie['lifetime'];

        if(is_int($gc) && ($return === null || $gc < $return))
        $return = $gc;

        return $return;
    }


    // setLifetime
    // change la durée de vie de la session
    // buffer permet de spécifier une durée additionnelle pour garbage collect
    // la session ne doit pas être active
    final public static function setLifetime(int $value,?int $buffer=null):bool
    {
        $return = false;

        if(!static::isStarted())
        {
            Ini::set('session.cookie_lifetime',$value);

            if(is_int($buffer))
            $value += $buffer;

            $return = Ini::set('session.gc_maxlifetime',$value);
        }

        return $return;
    }


    // getExpire
    // retourne le timestamp d'expiration de la session
    final public static function getExpire():?int
    {
        $return = null;
        $lifetime = static::getLifetime();

        if(is_int($lifetime))
        $return = Date::time() + $lifetime;

        return $return;
    }


    // setExpire
    // change le timestamp d'expiration de la session
    // buffer permet de spécifier une durée additionnelle pour garbage collect
    // la session ne doit pas être active
    final public static function setExpire(int $value,?int $buffer=null):bool
    {
        $return = false;
        $time = Date::time();

        if($value > $time)
        $return = static::setLifetime($value - $time,$buffer);

        return $return;
    }


    // getGarbageCollect
    // retourne les paramètres de garbage collect
    final public static function getGarbageCollect():array
    {
        $return = [];
        $return['probability'] = Ini::get('session.gc_probability');
        $return['divisor'] = Ini::get('session.gc_divisor');
        $return['lifetime'] = Ini::get('session.gc_maxlifetime');

        return $return;
    }


    // setGarbageCollect
    // change les paramètres de garbage collect
    // si la clé expire est présente, elle prend le dessus sur lifetime
    // si la clé buffer est présente, le temps est ajouté à lifetime
    // la session ne doit pas être active
    final public static function setGarbageCollect(array $value):bool
    {
        $return = false;
        $time = Date::time();

        if(!static::isStarted())
        {
            if(array_key_exists('expires',$value) && is_int($value['expires']) && $value['expires'] > $time)
            $value['lifetime'] = $value['expires'] - $time;

            if(Arr::keysFirst(['probability','divisor','lifetime'],$value))
            {
                if(array_key_exists('probability',$value) && is_int($value['probability']))
                Ini::set('session.gc_probability',$value['probability']);

                if(array_key_exists('divisor',$value) && is_int($value['divisor']))
                Ini::set('session.gc_divisor',$value['divisor']);

                if(array_key_exists('lifetime',$value) && is_int($value['lifetime']))
                {
                    if(array_key_exists('buffer',$value) && is_int($value['buffer']) && $value['buffer'] > 0)
                    $value['lifetime'] += $value['buffer'];

                    Ini::set('session.gc_maxlifetime',$value['lifetime']);
                }

                $return = true;
            }
        }

        return $return;
    }


    // getCookieParams
    // retourne les paramètres cookie de la session
    // la clé lifetime est remplacé par expire pour être compatible avec la classe cookie
    final public static function getCookieParams():array
    {
        return session_get_cookie_params();
    }


    // setCookieParams
    // change les paramètres du cookie de la session
    // le tableau donné se merge au défaut dans cookie
    // la session ne doit pas être active
    final public static function setCookieParams(?array $option=null):bool
    {
        $return = false;

        if(!static::isStarted())
        {
            $option = Cookie::option('cookieParams',$option);

            if(!empty($option))
            $return = session_set_cookie_params($option);
        }

        return $return;
    }


    // setDefault
    // applique les configurations de session par défaut
    // les nouvelles configuration sont merge sur le tableau config/default
    // une configuration à null est ignoré
    // la session ne doit pas être active
    final public static function setDefault(?array $option=null):?array
    {
        $return = null;

        if(!static::isStarted())
        {
            $return = [];
            $keys = array_keys(static::$config['default']);
            $option = (array) $option;
            static::$config['default'] = Arrs::replace(static::$config['default'],Arr::getsExists($keys,$option));
            $option = static::$config['default'];
            $lifetime = null;

            if($option['name'] !== null)
            $return['name'] = static::setName($option['name']);

            if($option['sid'] !== null)
            $return['sid'] = static::setSid($option['sid']);

            if($option['lifetime'] !== null && is_int($option['lifetime']))
            {
                $lifetime = $option['lifetime'];
                $return['lifetime'] = static::setLifetime($option['lifetime']);
            }

            if(is_array($option['cookieParams']) && !empty($option['cookieParams']))
            {
                if(is_int($lifetime))
                $option['cookieParams']['lifetime'] = $lifetime;
                $return['cookieParams'] = static::setCookieParams($option['cookieParams']);
            }

            if($option['cacheExpire'] !== null)
            $return['cacheExpire'] = static::setCacheExpire($option['cacheExpire']);

            if($option['cacheLimiter'] !== null)
            $return['cacheLimiter'] = static::setCacheLimiter($option['cacheLimiter']);

            if($option['module'] !== null)
            $return['module'] = static::setModule($option['module']);

            if($option['serializeHandler'] !== null)
            $return['serializeHandler'] = static::setSerializeHandler($option['serializeHandler']);

            if($option['savePath'] !== null)
            $return['savePath'] = static::setSavePath($option['savePath']);

            if(is_array($option['garbageCollect']) && !empty($option['garbageCollect']))
            {
                if(empty($option['garbageCollect']['lifetime']) || $option['garbageCollect']['lifetime'] < $lifetime)
                $option['garbageCollect']['lifetime'] = $lifetime;
                $return['garbageCollect'] = static::setGarbageCollect($option['garbageCollect']);
            }

            if(is_array($option['ini']) && !empty($option['ini']))
            $return['ini'] = Ini::sets($option['ini']);
        }

        return $return;
    }


    // info
    // retourne un tableau contenant un maximum d'information sur la session
    final public static function info():array
    {
        $return = [];

        $return['isStarted'] = static::isStarted();
        $return['hasSaveHandler'] = static::hasSaveHandler();
        $return['status'] = static::status();
        $return['id'] = static::getSid();
        $return['name'] = static::name();
        $return['lifetime'] = static::getLifetime();
        $return['expire'] = static::getExpire();
        $return['cacheExpire'] = static::getCacheExpire();
        $return['cacheLimiter'] = static::getCacheLimiter();
        $return['module'] = static::getModule();
        $return['serializeHandler'] = static::getSerializeHandler();
        $return['savePath'] = static::getSavePath();
        $return['garbageCollect'] = static::getGarbageCollect();
        $return['cookieParams'] = static::getCookieParams();
        $return['ini'] = static::ini();
        $return['data'] = static::data();

        return $return;
    }


    // getStructure
    // retourne un tableau de callback pour les structures
    // si value est null, retourne un tableau vide
    // si value est true, retourne les structures dans config
    // si value est array, merge le array avec les structures dans config
    final public static function getStructure($value=true):array
    {
        $return = [];

        if($value !== null)
        {
            $return = (array) static::$config['structure'];

            if(is_array($value))
            $return = Arr::plus($return,$value);
        }

        return $return;
    }


    // prepareStructure
    // prépare les éléments de structure dans la session
    // type peut être init, insert ou update
    // la session doit être active
    final public static function prepareStructure(string $type,$value=true):?array
    {
        $return = null;

        if(static::isStarted() && in_array($type,['init','insert','update'],true))
        {
            $return = [];
            $callback = static::getStructure($value);

            if(!empty($callback))
            {
                foreach ($callback as $k => $v)
                {
                    if(static::isCallable($v))
                    {
                        $val = static::get($k);

                        if($type !== 'init' || $val !== null)
                        $return[$k] = static::set($k,$v($type,$val));
                    }
                }
            }
        }

        return $return;
    }


    // structureEnv
    // gère le champ structure env
    // mode insert, update ou is
    final public static function structureEnv(string $mode,$value=null)
    {
        $return = $value;
        $env = static::$config['default']['env'] ?? null;

        if($mode === 'insert')
        $return = $env;

        elseif($mode === 'is')
        $return = (is_string($value) && $value === $env)? true:false;

        return $return;
    }


    // structureType
    // gère le champ structure type
    // mode insert, update ou is
    final public static function structureType(string $mode,$value=null)
    {
        $return = $value;
        $type = static::$config['default']['type'] ?? null;

        if($mode === 'insert')
        $return = $type;

        elseif($mode === 'is')
        $return = (is_string($value) && $value === $type)? true:false;

        return $return;
    }


    // structureVersion
    // gère le champ structure version
    // mode insert, update ou is
    final protected static function structureVersion(string $mode,$value=null)
    {
        $return = $value;
        $version = static::$config['default']['version'] ?? null;
        $versionMatch = static::$config['default']['versionMatch'] ?? null;

        if($mode === 'insert' || $mode === 'update')
        $return = $version;

        elseif($mode === 'is')
        $return = (is_string($value) && ($versionMatch === false || $value === $version))? true:false;

        return $return;
    }


    // structureExpire
    // gère le champ structure expire
    // mode insert, update ou is
    final protected static function structureExpire(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert' || $mode === 'update')
        $return = static::getExpire();

        elseif($mode === 'is')
        $return = (is_int($value) && $value > Date::time())? true:false;

        return $return;
    }


    // structureTimestamp
    // gère le champ structure timestamp
    // mode insert, update ou is
    final protected static function structureTimestamp(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = ['current'=>Date::timestamp(),'previous'=>null];

        elseif($mode === 'update')
        {
            $return['previous'] = $return['current'];
            $return['current'] = Date::timestamp();
        }

        elseif($mode === 'is')
        $return = (is_array($value) && Arr::keysAre(['current','previous'],$value) && is_int($return['current']))? true:false;

        return $return;
    }


    // structureRequestCount
    // gère le champ structure requestCount
    // mode insert, update ou is
    final protected static function structureRequestCount(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = 1;

        elseif($mode === 'update')
        $return = $value + 1;

        elseif($mode === 'is')
        $return = (is_int($value) && $value > 0)? true:false;

        return $return;
    }


    // structureUserAgent
    // gère le champ structure userAgent
    // mode insert, update ou is
    final protected static function structureUserAgent(string $mode,$value=null)
    {
        $return = $value;
        $userAgentMatch = static::$config['default']['userAgentMatch'];
        $userAgent = Request::userAgent();

        if($mode === 'insert' || $mode === 'update')
        $return = $userAgent;

        elseif($mode === 'is')
        $return = (is_string($value) && ($userAgentMatch === false || $userAgent === $value))? true:false;

        return $return;
    }


    // structureIp
    // gère le champ structure ip
    // mode insert, update ou is
    final protected static function structureIp(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert' || $mode === 'update')
        $return = Request::ip(true);

        elseif($mode === 'is')
        $return = (Ip::is($value))? true:false;

        return $return;
    }


    // structureFingerprint
    // gère le champ structure fingerprint
    // mode insert, update ou is
    final protected static function structureFingerprint(string $mode,$value=null)
    {
        $return = $value;
        $fingerprintKeys = static::$config['default']['fingerprintKeys'];
        $fingerprint = null;

        if(is_array($fingerprintKeys) && !empty($fingerprintKeys))
        $fingerprint = Request::fingerprint($fingerprintKeys);

        if($mode === 'insert' || $mode === 'update')
        $return = $fingerprint;

        elseif($mode === 'is')
        $return = (!is_string($value) || !is_string($fingerprint) || $fingerprint === $value)? true:false;

        return $return;
    }


    // structureLang
    // gère le champ structure lang
    // utilise les langs de request et lang pour retourner la lang la plus pertinente
    // mode insert, update ou is
    final protected static function structureLang(string $mode,$value=null)
    {
        $return = $value;
        $default = Lang::default();
        $requestPath = Request::pathLang();
        $request = Request::lang();

        if($mode === 'insert')
        $return = (Lang::is($request))? $request:$default;

        elseif($mode === 'update')
        {
            if(Lang::is($requestPath))
            $return = $requestPath;

            elseif(Lang::is($value))
            $return = $value;

            elseif(Lang::is($request))
            $return = $request;

            else
            $return = $default;
        }

        elseif($mode === 'is')
        $return = (is_string($value) && Lang::is($value))? true:false;

        return $return;
    }


    // structureCsrf
    // gère le champ structure csrf
    // mode insert, update ou is
    final protected static function structureCsrf(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = static::makeCsrf();

        elseif($mode === 'is')
        {
            $option = static::getCsrfOption();
            $return = (is_string($value) && strlen($value) === $option['length'])? true:false;
        }

        return $return;
    }


    // structureCaptcha
    // gère le champ structure captcha
    // mode is
    final protected static function structureCaptcha(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'is')
        {
            $option = static::getCaptchaOption();
            $return = ($value === null || (is_string($value) && strlen($value) === $option['length']))? true:false;
        }

        return $return;
    }


    // structureRemember
    // gère le champ structure remember
    // mode insert, update ou is
    final protected static function structureRemember(string $mode,$value=null)
    {
        $return = $value;

        if($mode === 'insert')
        $return = null;

        elseif($mode === 'is')
        $return = (is_array($value) || $value === null)? true:false;

        return $return;
    }


    // start
    // démarre la session
    // si option est true, alors c'est read_and_close
    // option peut être un array de changement ini à faire (ne pas mettre le prefix session)
    // si structure n'est pas vide, le contenu de la session est validé et redémarré si invalide
    // si structure est true, prend le défaut de la config
    // si setCookie n'est pas vide, le cookie est réenvoyé (donc la durée est étendu)
    // si setCookie est true, prend le défaut de la config
    // note: par défaut, le cookie dans response::headers n'apparaît que lors du commit (ou headers_sent)
    // la session ne doit pas être active
    final public static function start($structure=null,$setCookie=null,$option=null):bool
    {
        $return = false;

        if(!static::isStarted())
        {
            if($option === true)
            $option = ['read_and_close'=>true];

            if(is_array($option))
            $return = session_start($option);
            else
            $return = session_start();

            if(!empty($structure))
            {
                static::prepareStructure('init',$structure);

                if(!static::isStructureValid($structure))
                {
                    if(static::isEmpty())
                    static::prepareStructure('insert',$structure);

                    else
                    {
                        $return = static::restart($structure,$setCookie,$option);
                        $setCookie = null;
                    }
                }

                else
                static::prepareStructure('update',$structure);
            }

            if(!empty($setCookie))
            {
                if($setCookie === true)
                $setCookie = static::getCookieParams();

                if(is_array($setCookie))
                static::setCookie(true,$setCookie);
            }
        }

        return $return;
    }


    // restart
    // vide, détruit et démarrer la session
    // le id de la session est aussi changé lors du redémarrage
    // la session doit être active
    final public static function restart($structure=null,$setCookie=null,$option=null):bool
    {
        $return = false;

        if(static::isStarted())
        {
            static::empty();
            if(static::destroy())
            $return = static::start($structure,$setCookie,$option);
        }

        return $return;
    }


    // setCookie
    // permet de regénérer le header set-cookie pour la session une fois la session démarré
    // par défaut le header set-cookie est enlevé au moment du rester (pour éviter deux set-cookie du même cookie) -> ne pas set de cookie avant le démarrage de la session
    // le tableau donné se merge au défaut dans cookie
    // la session doit être active ou avoir un id de set
    final public static function setCookie(bool $removeHeader=false,?array $option=null):bool
    {
        $return = false;
        $name = static::name();
        $value = static::getSid();

        if(is_string($name) && !empty($value))
        {
            if($removeHeader === true)
            Response::unsetHeader('Set-Cookie');

            $return = Cookie::set($name,$value,$option);
        }

        return $return;
    }


    // regenerateId
    // replace le id de session et garde les données
    // possibilité de delete l'ancienne session
    // la session doit être active
    final public static function regenerateId(bool $delete=true):bool
    {
        $return = false;

        if(static::isStarted())
        $return = session_regenerate_id($delete);

        return $return;
    }


    // encode
    // encode le contenu du tableau session selon le serialize handler
    // la session doit être active
    final public static function encode():?string
    {
        $return = null;

        if(static::isStarted())
        $return = session_encode();

        return $return;
    }


    // decode
    // decode une string et remplace le contenu du tableau session
    // la session doit être active
    final public static function decode(string $value):bool
    {
        $return = false;

        if(static::isStarted())
        $return = session_decode($value);

        return $return;
    }


    // reset
    // remplace le contenu du tableau session par les valeurs originales
    // la session doit être active
    final public static function reset():bool
    {
        $return = false;

        if(static::isStarted())
        $return = session_reset();

        return $return;
    }


    // abort
    // termine la session sans écrire les changements
    // la session doit être active et ne sera pas effacé
    // si unsetArray est true, la superglobale session sera vidé
    final public static function abort(bool $unsetArray=true):bool
    {
        $return = false;

        if(static::isStarted())
        {
            $return = session_abort();

            if($return === true && $unsetArray === true)
            static::unsetArray();
        }

        return $return;
    }


    // commit
    // écrit les changements et termine la session
    // la session doit être active et ne sera pas effacé
    // si unsetArray est true, le tableau session sera vidé
    final public static function commit(bool $unsetArray=true):bool
    {
        $return = false;

        if(static::isStarted())
        {
            $return = session_write_close();

            if($return === true && $unsetArray === true)
            static::unsetArray();
        }

        return $return;
    }


    // empty
    // efface le contenu de la session et du tableau session
    // la session elle-même n'est pas effacé, seul son contenu est effacé
    // la session doit être active
    final public static function empty():bool
    {
        $return = false;

        if(static::isStarted())
        $return = session_unset();

        return $return;
    }


    // unsetArray
    // vide le tableau de la superglobale session
    // n'efface pas le contenu de la session
    // la session n'a pas à être active
    final public static function unsetArray():void
    {
        $_SESSION = [];

        return;
    }


    // unsetCookie
    // enlève le cookie de la session
    // n'efface pas la session ni le contenu de la session
    // la session n'a pas à être active
    final public static function unsetCookie(?array $option=null):bool
    {
        $return = false;
        $name = static::name();

        if(is_string($name))
        $return = Cookie::unset($name,$option);

        return $return;
    }


    // destroy
    // efface la session
    // possibilité de empty les données et/ou unset le cookie
    // session_id('') est utilisé pour forcer la regénération d'un nouvel id à la prochaine ouverture de session
    // la session doit être active
    final public static function destroy(bool $empty=true,bool $unsetCookie=true):bool
    {
        $return = false;

        if(static::isStarted())
        {
            if($empty === true)
            static::empty();

            $return = session_destroy();

            if($return === true)
            {
                session_id('');

                if($unsetCookie === true)
                static::unsetCookie();
            }
        }

        return $return;
    }


    // garbageCollect
    // lance le processus de garbageCollect
    // important: si la méthode est files et que la session est entièrement vide, la date d'accès du fichier n'est pas mis à jour par PHP à chaque commit
    // la session doit être active
    final public static function garbageCollect():?int
    {
        $return = null;

        if(static::isStarted())
        {
            $return = 0;
            $gc = session_gc();

            if(is_int($gc))
            $return = $gc;
        }

        return $return;
    }


    // data
    // retourne la référence du tableau superglobale session
    // la variable qui appele la méthode doit aussi avoir le symbole de référence
    // la session doit être active
    final public static function &data():?array
    {
        return Superglobal::session();
    }


    // get
    // retourne une variable dans la superglobale session
    // la session doit être active
    final public static function get($key)
    {
        return Superglobal::getSession($key);
    }


    // env
    // retourne l'environnement de la session
    final public static function env():string
    {
        return static::get('env');
    }


    // type
    // retourne la clé de l'application roulant présentement
    final public static function type():string
    {
        return static::get('type');
    }


    // version
    // retourne la version courante
    // la session doit être active
    final public static function version(?string $key=null):string
    {
        return static::get('version');
    }


    // expire
    // retourne le timestamp d'expiration présent dans le tableau de données session
    final public static function expire():?int
    {
        return static::get('expire');
    }


    // timestampCurrent
    // retourne le timestamp courant dans la session
    final public static function timestampCurrent():int
    {
        return static::get('timestamp/current');
    }


    // timestampPrevious
    // retourne le timestamp précédent de la session
    final public static function timestampPrevious():?int
    {
        return static::get('timestamp/previous');
    }


    // timestampDifference
    // retourne la différence entre le timestamp courant et précédent
    final public static function timestampDifference():?int
    {
        $return = null;
        $previous = static::timestampPrevious();

        if(is_int($previous))
        $return = static::timestampCurrent() - $previous;

        return $return;
    }


    // requestCount
    // retourne le nombre de requête
    // la session doit être active
    final public static function requestCount():?int
    {
        return static::get('requestCount');
    }


    // resetRequestCount
    // ramène la valeur requestCount à 1
    // la session doit être active
    final public static function resetRequestCount():void
    {
        static::set('requestCount',1);

        return;
    }


    // userAgent
    // retourne le user agent
    // la session doit être active
    final public static function userAgent():?string
    {
        return static::get('userAgent');
    }


    // browserCap
    // retourne les capacités du browser en fonction du useragent
    final public static function browserCap():?array
    {
        return (is_string($userAgent = static::userAgent()))? Browser::cap($userAgent):null;
    }


    // browserName
    // retourne le nom du browser du useragent
    final public static function browserName():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::name($userAgent):null;
    }


    // browserPlatform
    // retourne la plateforme du browser du useragent
    final public static function browserPlatform():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::platform($userAgent):null;
    }


    // browserDevice
    // retourne le device du browser du useragent
    final public static function browserDevice():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::device($userAgent):null;
    }


    // ip
    // retourne le ip
    // la session doit être active
    final public static function ip():?string
    {
        return Ip::normalize(static::get('ip'));
    }


    // fingerprint
    // retourne le fingerprint
    // la session doit être active
    final public static function fingerprint():?string
    {
        return static::get('fingerprint');
    }


    // lang
    // retourne la langue
    // la session doit être active
    final public static function lang():?string
    {
        return static::get('lang');
    }


    // setLang
    // change la langue de la session, la langue doit exister dans lang
    // la session doit être active
    final public static function setLang(string $value):bool
    {
        $return = false;

        if(Lang::is($value))
        {
            $return = true;
            $lang = static::get('lang');

            if($lang !== $value)
            static::set('lang',$value);
        }

        return $return;
    }


    // csrf
    // retourne la string csrf
    // possible de la rafraîchir si refresh est true
    // la session doit être active
    final public static function csrf(bool $refresh=false):?string
    {
        $return = null;

        if($refresh === true)
        static::refreshCsrf();

        $return = static::get('csrf');

        return $return;
    }


    // getCsrfOption
    // retourne la config pour csrf
    final public static function getCsrfOption(?array $option=null):array
    {
        return Arr::plus(static::$config['csrf'],$option);
    }


    // getCsrfName
    // retourne le nom pour csrf
    final public static function getCsrfName(?array $option=null):string
    {
        return static::getCsrfOption($option)['name'];
    }


    // makeCsrf
    // retourne une nouvelle string csrf
    final public static function makeCsrf(?array $option=null):string
    {
        $return = '';
        $option = static::getCsrfOption($option);
        if(!empty($option))
        $return = Str::random($option['length'],$option['possible'],$option['csprng']);

        return $return;
    }


    // refreshCsrf
    // rafraîchit la valeur csrf
    // la session doit être active
    final public static function refreshCsrf(?array $option=null):void
    {
        static::set('csrf',static::makeCsrf($option));

        return;
    }


    // captcha
    // retourne la string captcha
    // possible de la rafraîchir si refresh est true
    // la session doit être active
    final public static function captcha(bool $refresh=false):?string
    {
        $return = null;

        if($refresh === true)
        static::refreshCaptcha();

        $return = static::get('captcha');

        return $return;
    }


    // getCaptchaOption
    // retourne la config pour captcha
    final public static function getCaptchaOption(?array $option=null):array
    {
        return Arr::plus(static::$config['captcha'],$option);
    }


    // getCaptchaName
    // retourne le nom pour captcha
    final public static function getCaptchaName(?array $option=null):string
    {
        return static::getCaptchaOption($option)['name'];
    }


    // makeCaptcha
    // retourne une nouvelle string captcha
    final public static function makeCaptcha(?array $option=null):string
    {
        $return = '';
        $option = static::getCaptchaOption($option);

        if(!empty($option))
        $return = Str::random($option['length'],$option['possible'],$option['csprng']);

        return $return;
    }


    // refreshCaptcha
    // rafraîchit la valeur captcha
    // la session doit être active
    final public static function refreshCaptcha(?array $option=null):void
    {
        static::set('captcha',static::makeCaptcha($option));

        return;
    }


    // emptyCaptcha
    // remet la valeur captcha à null
    // la session doit être active
    final public static function emptyCaptcha():void
    {
        static::set('captcha',null);

        return;
    }


    // remember
    // retourne la ou les valeur remember
    // la session doit être active
    final public static function remember(?string $key=null)
    {
        return Arr::getSafe($key,static::get('remember'));
    }


    // setRemember
    // change une valeur pour remember
    // la valeur est cast
    // la session doit être active
    final public static function setRemember(string $key,$value,bool $cast=true):void
    {
        static::set(['remember',$key],$value,$cast);

        return;
    }


    // setsRemember
    // change plusieurs valeurs pour remember
    final public static function setsRemember(array $keyValue,bool $cast=true):void
    {
        foreach ($keyValue as $key => $value)
        {
            static::setRemember($key,$value,$cast);
        }

        return;
    }


    // unsetRemember
    // enlève une valeur du tableau remember
    final public static function unsetRemember(string $key):void
    {
        static::unset(['remember',$key]);

        return;
    }


    // rememberEmpty
    // vide le tableau remember
    final public static function rememberEmpty():void
    {
        static::set('remember',null);

        return;
    }


    // set
    // change la valeur d'une variable dans la superglobale session
    // possible de cast automatiquement la valeur, par défaut false
    // la session doit être active
    final public static function set($key,$value,bool $cast=false):void
    {
        if($cast === true)
        $value = Obj::cast($value);

        Superglobal::setSession($key,$value);

        return;
    }


    // unset
    // enlève une entrée dans le tableau superglobale session
    // la session doit être active
    final public static function unset($key):void
    {
        Superglobal::unsetSession($key);

        return;
    }
}
?>