<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// request
// class with static methods to analyze the current request
class Request extends Root
{
    // config
    public static $config = [
        'idLength'=>10, // longueur du id de la requête
        'safe'=>[ // paramètre par défaut pour la méhode isPathSafe
            'regex'=>'uriPath'],
        'lang'=>[ // option par défaut pour détection de la langue d'un path, index de langue dans le path est 0
            'length'=>2, // longueur de lang
            'all'=>['en']], // possibilité de lang
        'default'=>[
            'scheme'=>'http', // scheme par défaut si le tableau serveur est vide
            'port'=>80] // port par défaut si le tableau serveur est vide
    ];


    // id
    protected static $id = null; // id unique de la requête


    // isSsl
    // retourne vrai si la requete courante est ssl
    public static function isSsl():bool
    {
        return (Superglobal::getServer('HTTPS') === 'on')? true:false;
    }


    // isAjax
    // retourne vrai si la requête courante est ajax
    public static function isAjax():bool
    {
        return (static::getHeader('X-Requested-With') === 'XMLHttpRequest')? true:false;
    }


    // isGet
    // retourne vrai si la requête courante est get
    public static function isGet():bool
    {
        return (static::method() === 'get')? true:false;
    }


    // isPost
    // retourne vrai si la requête courante est post
    public static function isPost():bool
    {
        return (static::method() === 'post')? true:false;
    }


    // isPostWithoutData
    // retourne vrai si la requête courante est post mais qu'il n'y a pas de données post
    // ceci peut arriver lors du chargement d'un fichier plus lourd que php ini
    public static function isPostWithoutData():bool
    {
        return (static::isPost() && empty(static::post()))? true:false;
    }


    // isRefererInternal
    // retourne vrai si le referrer est interne (donc même host que le courant)
    // possible de fournir un tableau d'autres hosts considérés comme internal
    public static function isRefererInternal($hosts=null):bool
    {
        return (!empty(static::referer(true,$hosts)))? true:false;
    }


    // isInternalPost
    // retourne vrai si la requête semble être un post avec un referer provenant du même domaine
    // possible de fournir un tableau d'autres hosts considérés comme internal
    public static function isInternalPost($hosts=null):bool
    {
        return (static::isPost() && static::isRefererInternal($hosts))? true:false;
    }


    // isExternalPost
    // retourne vrai si la requête semble être un post avec un referer provenant d'un autre domaine
    // possible de fournir un tableau d'autres hosts considérés comme internal
    public static function isExternalPost($hosts=null):bool
    {
        return (static::isPost() && !static::isRefererInternal($hosts))? true:false;
    }


    // isStandard
    // retourne vrai si la requête courante est de méthode get et pas ajax
    public static function isStandard():bool
    {
        return (static::isGet() && !static::isAjax())? true:false;
    }


    // isPathEmpty
    // retourne vrai si le chemin est vide (ou seulement /)
    public static function isPathEmpty():bool
    {
        return (static::pathStripStart() === '')? true:false;
    }


    // isPathSafe
    // retourne vrai si le chemin est sécuritaire
    public static function isPathSafe():bool
    {
        return (Path::isSafe(static::path(),static::$config['safe']))? true:false;
    }


    // isCli
    // retourne vrai si les données indiquent que la requête courante provient du cli
    public static function isCli():bool
    {
        return (!Superglobal::serverExists('REQUEST_METHOD') || Superglobal::envExists('SHELL'))? true:false;
    }


    // isFailedFileUpload
    // retourne vrai si la requête semble être un envoie de fichier raté
    public static function isFailedFileUpload():bool
    {
        return (static::isPostWithoutData() && Base\Superglobal::hasServerLengthWithoutPost())? true:false;
    }


    // hasQuery
    // retourne vrai si la requête courante a un query string
    public static function hasQuery():bool
    {
        return (strlen(static::query()))? true:false;
    }


    // hasGet
    // retourne vrai si la requête courante contient des données get
    public static function hasGet():bool
    {
        return (!empty(static::get()))? true:false;
    }


    // hasPost
    // retourne vrai si la requête courante contient des données post
    public static function hasPost():bool
    {
        return (!empty(static::post()))? true:false;
    }


    // hasData
    // retourne vrai si la requête courante contient des données get ou post
    public static function hasData():bool
    {
        return (static::hasGet() || static::hasPost())? true:false;
    }


    // hasEmptyGenuine
    // retourne vrai si post contient la clé genuine et le contenu est vide
    public static function hasEmptyGenuine():bool
    {
        $return = false;
        $post = static::post();
        $genuine = Html::getGenuineName();

        if(!empty($genuine) && !empty($post) && array_key_exists($genuine,$post) && empty($post['genuine']))
        $return = true;

        return $return;
    }


    // hasUser
    // retourne vrai si la requête courante contient un user
    public static function hasUser():bool
    {
        return (is_string(static::user()))? true:false;
    }


    // hasPass
    // retourne vrai si la requête courante contient un pass
    public static function hasPass():bool
    {
        return (is_string(static::pass()))? true:false;
    }


    // hasFragment
    // retourne vrai si la requête courante contient un fragment
    public static function hasFragment():bool
    {
        return (is_string(static::fragment()))? true:false;
    }


    // hasIp
    // retourne vrai si la requête courante contient un ip
    public static function hasIp(bool $validIp=true):bool
    {
        return (!empty(static::ip($validIp)))? true:false;
    }


    // hasLangHeader
    // retourne vrai si la requête courante contient un header lang
    public static function hasLangHeader():bool
    {
        return (!empty(static::langHeader()))? true:false;
    }


    // hasUserAgent
    // retourne vrai si la requête courante contient un userAgent
    public static function hasUserAgent():bool
    {
        return (!empty(static::userAgent()))? true:false;
    }


    // isDesktop
    // retourne vrai si le useragent est desktop
    public static function isDesktop():bool
    {
        return Browser::isDesktop(static::userAgent())? true:false;
    }


    // isMobile
    // retourne vrai si le useragent est mobile
    public static function isMobile():bool
    {
        return Browser::isMobile(static::userAgent())? true:false;
    }


    // isOldIe
    // retourne vrai si le useragent est Internet Explorer < 9
    public static function isOldIe():bool
    {
        return Browser::isOldIe(static::userAgent())? true:false;
    }


    // isMac
    // retourne vrai si le useragent est sur MacOs
    public static function isMac():bool
    {
        return Browser::isMac(static::userAgent())? true:false;
    }


    // isLinux
    // retourne vrai si le useragent est sur Linux
    public static function isLinux():bool
    {
        return Browser::isLinux(static::userAgent())? true:false;
    }


    // isWindows
    // retourne vrai si le useragent est sur Windows
    public static function isWindows():bool
    {
        return Browser::isWindows(static::userAgent())? true:false;
    }


    // isBot
    // retourne vrai si le userAgent est un bot
    public static function isBot():bool
    {
        return Browser::isBot(static::userAgent())? true:false;
    }


    // isIpLocal
    // retourne vrai si le ip est local
    public static function isIpLocal(bool $validIp=true):bool
    {
        return Ip::isLocal(static::ip($validIp));
    }


    // isLang
    // retourne vrai si la langue est celle fourni
    public static function isLang($value):bool
    {
        return (is_string($value) && $value === static::lang())? true:false;
    }


    // isScheme
    // retourne vrai si le scheme est celui fourni
    public static function isScheme($value):bool
    {
        return (is_string($value) && $value === static::scheme())? true:false;
    }


    // isHost
    // retourne vrai si l'host est celui fourni
    public static function isHost($value):bool
    {
        return (is_string($value) && $value === static::host())? true:false;
    }


    // isSchemeHost
    // retourne vrai si le scheme host est celui fourni
    public static function isSchemeHost($value):bool
    {
        return (is_string($value) && $value === static::schemeHost())? true:false;
    }


    // isIp
    // retourne vrai si le ip est celui fourni
    public static function isIp($value,bool $validIp=true):bool
    {
        return (is_string($value) && $value === static::ip($validIp))? true:false;
    }


    // isLangHeader
    // retourne vrai si la langue du header est celle fournie
    public static function isLangHeader(string $value):bool
    {
        return ($value === static::langHeader())? true:false;
    }


    // getExists
    // retourne vrai si la clé existe dans get
    public static function getExists($key):bool
    {
        return Superglobal::getExists($key);
    }


    // postExists
    // retourne vrai si la clé existe dans post
    public static function postExists($key):bool
    {
        return Superglobal::postExists($key);
    }


    // headerExists
    // retourne vrai si la requête contient le header
    public static function headerExists(string $key):bool
    {
        return Header::exist($key,static::headers());
    }


    // hasFiles
    // retourne vrai si la requête contient des fichiers
    public static function hasFiles():bool
    {
        return (!empty(static::files()))? true:false;
    }


    // id
    // retourne le id unique de la requête
    public static function id():string
    {
        if(static::$id === null)
        static::$id = Str::random(static::$config['idLength']);

        return static::$id;
    }


    // info
    // retourne l'ensemble des informations en lien avec la requête courante
    // possible d'exporter le id
    public static function info(bool $id=false,bool $validIp=true):array
    {
        $return = static::parse();

        $return['relative'] = static::relative();
        $return['absolute'] = static::absolute();
        $return['schemeHost'] = static::schemeHost();
        $return['method'] = static::method();
        $return['ajax'] = static::isAjax();
        $return['ssl'] = static::isSsl();
        $return['timestamp'] = static::timestamp();
        $return['ip'] = static::ip($validIp);
        $return['get'] = static::get();
        $return['post'] = static::post();
        $return['files'] = static::files();
        $return['userAgent'] = static::userAgent();
        $return['referer'] = static::referer();
        $return['headers'] = static::headers();
        $return['lang'] = static::lang();
        $return['safe'] = static::isPathSafe();

        if($id === true)
        $return['id'] = static::id();

        return $return;
    }


    // export
    // exporte les informations liés à la requête courante
    // utile pour créer un objet core request
    // possible d'exporter le id
    public static function export(bool $id=false,bool $validIp=true):array
    {
        $return = static::parse();
        $return['method'] = static::method();
        $return['timestamp'] = static::timestamp();
        $return['ip'] = static::ip($validIp);
        $return['post'] = static::post();
        $return['files'] = static::files();
        $return['headers'] = static::headers();
        $return['lang'] = static::lang();

        if($id === true)
        $return['id'] = static::id();

        return $return;
    }


    // parse
    // retourne le tableau parse de la requête courante
    // comme parse_str
    public static function parse():array
    {
        $return = [];

        $return['scheme'] = static::scheme();
        $return['user'] = static::user();
        $return['pass'] = static::pass();
        $return['host'] = static::host();
        $return['port'] = static::port();
        $return['path'] = static::path();
        $return['query'] = static::query();
        $return['fragment'] = static::fragment();

        return $return;
    }


    // lang
    // retourne la langue de la requête
    public static function lang():string
    {
        $return = Lang::default();

        $lang = static::pathLang();
        if(!empty($lang))
        $return = $lang;

        else
        {
            $langHeader = static::langHeader();
            if(!empty($langHeader))
            $return = $langHeader;
        }

        return $return;
    }


    // setLangs
    // change les langues dans les config de la classe
    public static function setLangs(array $value):void
    {
        Arr::setRef('all',$value,static::$config['lang']);

        return;
    }


    // setSsl
    // change la valeur ssl de la requête courante
    public static function setSsl(bool $value=true):void
    {
        Superglobal::setServer('REQUEST_SCHEME',Http::scheme($value));
        Superglobal::setServer('SERVER_PORT',Http::port($value));
        Superglobal::setServer('HTTPS',($value === true)? 'on':'off');

        return;
    }


    // setAjax
    // change la valeur ajax de la requête courante
    public static function setAjax(bool $value=true):void
    {
        if($value === true)
        static::setHeader('X-Requested-With','XMLHttpRequest');
        else
        static::unsetHeader('X-Requested-With');

        return;
    }


    // scheme
    // retourne le scheme de la requete
    public static function scheme():string
    {
        return (is_string($scheme = Superglobal::getServer('REQUEST_SCHEME')))? $scheme:static::$config['default']['scheme'];
    }


    // setScheme
    // change la valeur du scheme de la requête courante
    public static function setScheme(string $value):void
    {
        if(in_array($value,['http','https'],true))
        static::setSsl(($value === 'https')? true:false);

        return;
    }


    // user
    // retourne le nom d'utilisateur de la requête courante
    // request_user est crée par quid, ce n'est pas utilisé dans le tableau serveur par php
    public static function user():?string
    {
        return Superglobal::getServer('REQUEST_USER');
    }


    // setUser
    // change la valeur de user de la requête courante
    // peut être null pour retirer
    public static function setUser(?string $value):void
    {
        if(is_string($value))
        Superglobal::setServer('REQUEST_USER',$value);
        else
        Superglobal::unsetServer('REQUEST_USER');

        return;
    }


    // pass
    // retourne le mot de passe de la requête courante
    // request_pass est crée par quid, ce n'est pas utilisé dans le tableau serveur par php
    public static function pass():?string
    {
        return Superglobal::getServer('REQUEST_PASS');
    }


    // setPass
    // change la valeur de pass de la requête courante
    // peut être null pour retirer
    public static function setPass(?string $value):void
    {
        if(is_string($value))
        Superglobal::setServer('REQUEST_PASS',$value);
        else
        Superglobal::unsetServer('REQUEST_PASS');

        return;
    }


    // host
    // retourne le domaine courant
    public static function host():?string
    {
        $return = null;

        if(($serverName = Superglobal::getServer('SERVER_NAME')) !== null)
        $return = $serverName;

        elseif(($httpHost = static::getHeader('Host')) !== null)
        $return = $httpHost;

        return $return;
    }


    // setHost
    // change la valeur de host dans la requête courante
    public static function setHost(string $value):void
    {
        Superglobal::setServer('SERVER_NAME',$value);
        Superglobal::setServer('HTTP_HOST',$value);

        return;
    }


    // port
    // retourne le port de la requête courante
    public static function port():?int
    {
        return (is_numeric($port = Superglobal::getServer('SERVER_PORT')))? (int) $port:static::$config['default']['port'];
    }


    // setPort
    // change la valeur de port dans la requête courante
    public static function setPort(int $value):void
    {
        Superglobal::setServer('SERVER_PORT',$value);

        return;
    }


    // path
    // retourne le path courant, sans query, en fonction de server request_uri
    public static function path():string
    {
        $return = '';
        $requestUri = Superglobal::getServer('REQUEST_URI');
        if(is_string($requestUri))
        $return = Uri::path($requestUri) ?? '';

        return $return;
    }


    // setPath
    // change la valeur de path dans la requête courante
    // la valeur est wrapper par / à gauche
    public static function setPath(string $value):void
    {
        Superglobal::setServer('REQUEST_URI',Path::wrapStart($value));

        return;
    }


    // pathinfo
    // retourne pathinfo du path courant
    public static function pathinfo()
    {
        return Path::info(static::path());
    }


    // dirname
    // retourne le dirname du path courant
    public static function dirname():?string
    {
        return Path::infoOne(PATHINFO_DIRNAME,static::path());
    }


    // basename
    // retourne le basename du path courant
    public static function basename():?string
    {
        return Path::infoOne(PATHINFO_BASENAME,static::path());
    }


    // filename
    // retourne le filename du path courant
    public static function filename():?string
    {
        return Path::infoOne(PATHINFO_FILENAME,static::path());
    }


    // extension
    // retourne l'extension du path courant
    public static function extension():?string
    {
        return Path::infoOne(PATHINFO_EXTENSION,static::path());
    }


    // mime
    // retourne le mimetype du path courant
    public static function mime():?string
    {
        return Path::mime(static::path());
    }


    // pathStripStart
    // retourne le path courant, sans query, et sans le slash au début
    public static function pathStripStart():string
    {
        return Path::stripStart(static::path());
    }


    // pathExplode
    // explode le path courant
    public static function pathExplode(?array $option=null):array
    {
        return Path::arr(static::pathStripStart(),$option);
    }


    // pathGet
    // retourne un index du path courant
    public static function pathGet(int $index,?array $option=null):?string
    {
        return Path::get($index,static::pathStripStart(),$option);
    }


    // pathGets
    // retourne un tableau des index existants dans le path courant
    public static function pathGets(array $indexes,?array $option=null):array
    {
        return Path::gets($indexes,static::pathStripStart(),$option);
    }


    // pathCount
    // count le nombre de niveau dans le path courant
    public static function pathCount(?array $option=null):int
    {
        return Path::count(static::pathStripStart(),$option);
    }


    // pathSlice
    // tranche des slices du path courant en utilisant offset et length
    public static function pathSlice(int $offset,?int $length,?array $option=null):array
    {
        return Path::slice($offset,$length,static::pathStripStart(),$option);
    }


    // pathQuery
    // retourne l'uri courant, avec query, en fonction de server request_uri
    public static function pathQuery():string
    {
        return (is_string($requestUri = Superglobal::getServer('REQUEST_URI')))? $requestUri:'';
    }


    // pathLang
    // retourne le code de langue si présent dans le path
    public static function pathLang():?string
    {
        return Path::lang(static::pathStripStart(),static::$config['lang']);
    }


    // query
    // retourne la valeur get courante
    public static function query():string
    {
        return (is_string($queryString = Superglobal::getServer('QUERY_STRING')))? $queryString:'';
    }


    // setQuery
    // change la valeur de la query et met à jour le tableau get
    public static function setQuery($value,bool $encode=false):void
    {
        $string = '';
        $array = [];

        if(is_string($value) && strlen($value))
        $value = Uri::parseQuery($value);

        if(is_array($value))
        {
            $array = $value;
            $string = Uri::buildQuery($value,$encode);
        }

        if(is_string($string))
        {
            Globals::set('_GET',$array);
            Superglobal::setServer('QUERY_STRING',$string);
        }

        return;
    }


    // removeQuery
    // vide le query string
    public static function removeQuery():void
    {
        static::setQuery(null);

        return;
    }


    // fragment
    // retourne le fragment de la requête courante
    // request_fragment est une invention de quid, ce n'est pas utilisé dans le tableau serveur par php
    public static function fragment():?string
    {
        return Superglobal::getServer('REQUEST_FRAGMENT');
    }


    // setFragment
    // change la valeur de fragment de la requête courante
    // peut être null pour retirer
    public static function setFragment(?string $value):void
    {
        if(is_string($value))
        Superglobal::setServer('REQUEST_FRAGMENT',$value);
        else
        Superglobal::unsetServer('REQUEST_FRAGMENT');

        return;
    }


    // method
    // retourne la method courante
    public static function method():string
    {
        return (($requestMethod = Superglobal::getServer('REQUEST_METHOD')) && strtolower($requestMethod) === 'post')? 'post':'get';
    }


    // setMethod
    // change la méthode de la requête courante
    public static function setMethod(string $value):void
    {
        $value = strtoupper($value);

        if(in_array($value,['GET','POST'],true))
        Superglobal::setServer('REQUEST_METHOD',$value);

        return;
    }


    // timestamp
    // retourne le timestamp de la requête
    public static function timestamp():?int
    {
        return Superglobal::getServer('REQUEST_TIME');
    }


    // timestampFloat
    // retourne le timestamp float de la requête courante
    public static function timestampFloat():?float
    {
        return Superglobal::getServer('REQUEST_TIME_FLOAT');
    }


    // setTimestamp
    // change le timestamp de la requête courante
    public static function setTimestamp(int $value):void
    {
        Superglobal::setServer('REQUEST_TIME',$value);

        return;
    }


    // setTimestampFloat
    // change le timestamp float de la requête courante
    public static function setTimestampFloat(float $value):void
    {
        Superglobal::setServer('REQUEST_TIME_FLOAT',$value);

        return;
    }


    // schemeHost
    // retourne la string scheme + host
    public static function schemeHost():?string
    {
        $return = null;
        $scheme = static::scheme();
        $host = static::host();

        if(!empty($scheme) && !empty($host))
        $return = Uri::build(['scheme'=>$scheme,'host'=>$host]);

        return $return;
    }


    // get
    // retourne le tableau get courant
    public static function get():array
    {
        return Superglobal::get();
    }


    // post
    // retourne la valeur post courante
    public static function post(bool $safeKey=false,bool $stripTags=false,bool $includeFiles=false):?array
    {
        return Superglobal::postReformat(Superglobal::post(),$safeKey,$stripTags,$includeFiles);
    }


    // files
    // retourne la valeur files courante
    public static function files():?array
    {
        return Superglobal::files();
    }


    // csrf
    // retoure la valeur csrf de la requête si disponible
    public static function csrf():?string
    {
        $return = null;
        $attr = Session::getCsrfOption();

        if(!empty($attr))
        {
            $csrf = Superglobal::getPost($attr['name']);
            if(!empty($csrf) && is_string($csrf) && strlen($csrf) === $attr['length'])
            $return = $csrf;
        }

        return $return;
    }


    // setPost
    // remplace les donnés de post
    public static function setPost(array $value):void
    {
        Globals::set('_POST',$value);

        return;
    }


    // headers
    // retourne les en-têtes de la requête courante
    public static function headers():array
    {
        return Superglobal::getServerHeader(true);
    }


    // getHeader
    // retourne un header
    // insensible à la case
    public static function getHeader(string $key)
    {
        return Header::get($key,static::headers());
    }


    // setHeader
    // change un header de la requête
    // insensible à la case
    public static function setHeader(string $key,$value):void
    {
        static::setHeaders(Header::set($key,$value,static::headers()));

        return;
    }


    // unsetHeader
    // enlève un header de la requête
    // insensible à la case
    public static function unsetHeader(string $key):void
    {
        static::setHeaders(Header::unset($key,static::headers()));

        return;
    }


    // setHeaders
    // replace les headers de la requête par ceux fournis
    // refait le tableau server
    public static function setHeaders(array $values):void
    {
        Superglobal::unsetServerHeader();

        foreach ($values as $key => $value)
        {
            $key = Superglobal::formatServerKey('HTTP_'.$key);
            Superglobal::setServer($key,$value);
        }

        return;
    }


    // ip
    // retourne le ip courant
    // si valid est true et que le ip de retourne n'est pas valide, retourne 0.0.0.0 plutôt que null
    public static function ip(bool $valid=true):?string
    {
        $return = null;

        if(($forwardedFor = static::getHeader('X-Forwarded-For')) !== null)
        $return = $forwardedFor;

        elseif(($remoteAddr = Superglobal::getServer('REMOTE_ADDR')) !== null)
        $return = $remoteAddr;

        if($valid === true)
        $return = Ip::normalize($return);

        return $return;
    }


    // setIp
    // change le ip de la requête courante
    public static function setIp(string $value):void
    {
        if(Ip::is($value))
        {
            if(static::headerExists('X-Forwarded-For'))
            static::setHeader('X-Forwarded-For',$value);

            Superglobal::setServer('REMOTE_ADDR',$value);
        }

        return;
    }


    // userAgent
    // retourne le user agent courant
    public static function userAgent():?string
    {
        return static::getHeader('User-Agent');
    }


    // setUserAgent
    // change le user agent courant
    // peut être null pour retirer le header
    public static function setUserAgent(?string $value):void
    {
        static::setHeader('User-Agent',$value);

        return;
    }


    // referer
    // retourne l'uri référent à la requête
    // possible de retourner seulement si le referer est interne (et possible de spécifier un tableau d'host considéré comme interne)
    public static function referer(bool $internal=false,$hosts=null):?string
    {
        $return = null;
        $referer = static::getHeader('Referer');

        if(is_string($referer) && !empty($referer))
        {
            if($internal === false || Uri::isInternal($referer,$hosts))
            $return = $referer;
        }

        return $return;
    }


    // setReferer
    // change le referer courant
    // peut être null pour retirer le header
    public static function setReferer(?string $value):void
    {
        static::setHeader('Referer',$value);

        return;
    }


    // browserCap
    // retourne les capacités du browser en fonction du useragent
    public static function browserCap():?array
    {
        return (is_string($userAgent = static::userAgent()))? Browser::cap($userAgent):null;
    }


    // browserName
    // retourne le nom du browser du useragent
    public static function browserName():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::name($userAgent):null;
    }


    // browserPlatform
    // retourne la plateforme du browser du useragent
    public static function browserPlatform():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::platform($userAgent):null;
    }


    // browserDevice
    // retourne le device du browser du useragent
    public static function browserDevice():?string
    {
        return (is_string($userAgent = static::userAgent()))? Browser::device($userAgent):null;
    }


    // langHeader
    // retourne la valeur du header lang de la requête courante
    public static function langHeader():?string
    {
        return (is_string($acceptLanguage = static::getHeader('Accept-Language')))? substr($acceptLanguage,0,2):null;
    }


    // setLangHeader
    // change la valeur du header lang de la requête courante
    public static function setLangHeader(?string $value):void
    {
        static::setHeader('Accept-Language',$value);

        return;
    }


    // fingerprint
    // retourne un fingerprint sha1 des entêtes de requêtes
    public static function fingerprint(array $keys):?string
    {
        return Header::fingerprint(static::headers(),$keys);
    }


    // redirect
    // retourne l'uri de redirection si l'uri courante présente des défauts
    // par exemple path unsafe, double slash, slash à la fin ou manque pathLang
    // possibilité de retourner le chemin absolut
    public static function redirect(bool $absolute=false):?string
    {
        $return = null;
        $path = static::pathStripStart();
        $return = Path::redirect($path,static::$config['safe'],static::$config['lang']);

        if(is_string($return) && $absolute === true)
        $return = Uri::absolute($return);

        return $return;
    }


    // str
    // envoie info dans http str qui retourne une string pour représenter la requête
    public static function str():string
    {
        return Http::str(static::info());
    }


    // uri
    // construit l'uri à partir du tableau parse
    // n'est pas encodé ou décodé, plus rapide que les autres méthodes
    public static function uri(bool $absolute=false):string
    {
        $return = '';

        if($absolute === true)
        $parse = static::parse();
        else
        $parse = ['path'=>static::path(),'query'=>static::query(),'fragment'=>static::fragment()];

        $return = Uri::build($parse,false);

        return $return;
    }


    // output
    // retourne l'uri, peut être relatif ou absolut dépendamment des options
    public static function output(?array $option=null):?string
    {
        return Uri::output(static::uri(true),$option);
    }


    // relative
    // retourne l'uri relative de la requête
    public static function relative(?array $option=null):string
    {
        return Uri::relative(static::uri(false),$option);
    }


    // absolute
    // retourne l'uri absolut de la requête
    public static function absolute(?array $option=null):?string
    {
        return Uri::absolute(static::uri(true),$option);
    }
}
?>