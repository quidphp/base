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

// request
// class with static methods to analyze the current request
final class Request extends Root
{
    // config
    protected static array $config = [
        'idLength'=>10, // longueur du id de la requête
        'safe'=>[ // paramètre par défaut pour la méhode isPathSafe
            'regex'=>'uriPath'],
        'lang'=>[ // option par défaut pour détection de la langue d'un path, index de langue dans le path est 0
            'length'=>2, // longueur de lang
            'all'=>['en']] // possibilité de lang
    ];


    // id
    protected static ?string $id = null; // id unique de la requête


    // isSsl
    // retourne vrai si la requete courante est ssl
    final public static function isSsl():bool
    {
        return Superglobal::getServer('HTTPS') === 'on';
    }


    // isAjax
    // retourne vrai si la requête courante est ajax
    final public static function isAjax():bool
    {
        return self::getHeader('X-Requested-With') === 'XMLHttpRequest';
    }


    // isGet
    // retourne vrai si la requête courante est get
    final public static function isGet():bool
    {
        return self::method() === 'get';
    }


    // isPost
    // retourne vrai si la requête courante est post
    final public static function isPost():bool
    {
        return self::method() === 'post';
    }


    // isPostWithoutData
    // retourne vrai si la requête courante est post mais qu'il n'y a pas de données post
    // ceci peut arriver lors du chargement d'un fichier plus lourd que php ini
    final public static function isPostWithoutData():bool
    {
        return self::isPost() && empty(self::post());
    }


    // isRefererInternal
    // retourne vrai si le referrer est interne (donc même host que le courant)
    // possible de fournir un tableau d'autres hosts considérés comme internal
    final public static function isRefererInternal($hosts=null):bool
    {
        return !empty(self::referer(true,$hosts));
    }


    // isInternalPost
    // retourne vrai si la requête semble être un post avec un referer provenant du même domaine
    // possible de fournir un tableau d'autres hosts considérés comme internal
    final public static function isInternalPost($hosts=null):bool
    {
        return self::isPost() && self::isRefererInternal($hosts);
    }


    // isExternalPost
    // retourne vrai si la requête semble être un post avec un referer provenant d'un autre domaine
    // possible de fournir un tableau d'autres hosts considérés comme internal
    final public static function isExternalPost($hosts=null):bool
    {
        return self::isPost() && !self::isRefererInternal($hosts);
    }


    // isStandard
    // retourne vrai si la requête courante est de méthode get et pas ajax
    final public static function isStandard():bool
    {
        return self::isGet() && !self::isAjax();
    }


    // isPathEmpty
    // retourne vrai si le chemin est vide (ou seulement /)
    final public static function isPathEmpty():bool
    {
        return self::pathStripStart() === '';
    }


    // isPathSafe
    // retourne vrai si le chemin est sécuritaire
    final public static function isPathSafe():bool
    {
        return Path::isSafe(self::path(),self::$config['safe']);
    }


    // isPathArgument
    // retourne vrai si le chemin est un argument (commence par - )
    final public static function isPathArgument():bool
    {
        return Path::isArgument(self::path());
    }


    // isPathArgumentNotCli
    // retourne vrai si le chemin est un argument (commence par - ) mais que la requête n'est pas cli
    final public static function isPathArgumentNotCli():bool
    {
        return self::isPathArgument() && !self::isCli();
    }


    // isCli
    // retourne vrai si la requête courante provient du cli, renvoie vers server
    final public static function isCli():bool
    {
        return Server::isCli();
    }


    // isFailedFileUpload
    // retourne vrai si la requête semble être un envoie de fichier raté
    final public static function isFailedFileUpload():bool
    {
        return self::isPostWithoutData() && Base\Superglobal::hasServerLengthWithoutPost();
    }


    // hasQuery
    // retourne vrai si la requête courante a un query string
    final public static function hasQuery():bool
    {
        return strlen(self::query()) > 0;
    }


    // hasGet
    // retourne vrai si la requête courante contient des données get
    final public static function hasGet():bool
    {
        return !empty(self::get());
    }


    // hasPost
    // retourne vrai si la requête courante contient des données post
    final public static function hasPost():bool
    {
        return !empty(self::post());
    }


    // hasData
    // retourne vrai si la requête courante contient des données get ou post
    final public static function hasData():bool
    {
        return self::hasGet() || self::hasPost();
    }


    // hasValidGenuine
    // retourne vrai si post contient la clé genuine et le contenu est vide
    // genuine 2 est un champ ajouté sur le front-end
    final public static function hasValidGenuine(bool $two=true):bool
    {
        $return = false;
        $post = self::post();
        $genuine = Html::getGenuineName();
        $genuine2 = Html::getGenuineName(2);

        if(!empty($genuine) && !empty($post) && array_key_exists($genuine,$post) && empty($post[$genuine]))
        {
            if($two === false || (array_key_exists($genuine2,$post) && !empty($post[$genuine2])))
            $return = true;
        }

        return $return;
    }


    // hasUser
    // retourne vrai si la requête courante contient un user
    final public static function hasUser():bool
    {
        return is_string(self::user());
    }


    // hasPass
    // retourne vrai si la requête courante contient un pass
    final public static function hasPass():bool
    {
        return is_string(self::pass());
    }


    // hasFragment
    // retourne vrai si la requête courante contient un fragment
    final public static function hasFragment():bool
    {
        return is_string(self::fragment());
    }


    // hasIp
    // retourne vrai si la requête courante contient un ip
    final public static function hasIp(bool $validIp=true):bool
    {
        return !empty(self::ip($validIp));
    }


    // hasLangHeader
    // retourne vrai si la requête courante contient un header lang
    final public static function hasLangHeader():bool
    {
        return !empty(self::langHeader());
    }


    // hasUserAgent
    // retourne vrai si la requête courante contient un userAgent
    final public static function hasUserAgent():bool
    {
        return !empty(self::userAgent());
    }


    // isBot
    // retourne vrai si le userAgent est un bot
    final public static function isBot():bool
    {
        return Browser::isBot(self::userAgent());
    }


    // isIpLocal
    // retourne vrai si le ip est local
    final public static function isIpLocal(bool $validIp=true):bool
    {
        return Ip::isLocal(self::ip($validIp));
    }


    // isLang
    // retourne vrai si la langue est celle fourni
    final public static function isLang($value):bool
    {
        return is_string($value) && $value === self::lang();
    }


    // isScheme
    // retourne vrai si le scheme est celui fourni
    final public static function isScheme($value):bool
    {
        return is_string($value) && $value === self::scheme();
    }


    // isHost
    // retourne vrai si l'host est celui fourni
    final public static function isHost($value):bool
    {
        return is_string($value) && $value === self::host();
    }


    // isSchemeHost
    // retourne vrai si le scheme host est celui fourni
    final public static function isSchemeHost($value):bool
    {
        return is_string($value) && $value === self::schemeHost();
    }


    // isIp
    // retourne vrai si le ip est celui fourni
    final public static function isIp($value,bool $validIp=true):bool
    {
        return is_string($value) && $value === self::ip($validIp);
    }


    // isLangHeader
    // retourne vrai si la langue du header est celle fournie
    final public static function isLangHeader(string $value):bool
    {
        return $value === self::langHeader();
    }


    // getExists
    // retourne vrai si la clé existe dans get
    final public static function getExists($key):bool
    {
        return Superglobal::getExists($key);
    }


    // postExists
    // retourne vrai si la clé existe dans post
    final public static function postExists($key):bool
    {
        return Superglobal::postExists($key);
    }


    // headerExists
    // retourne vrai si la requête contient le header
    final public static function headerExists(string $key):bool
    {
        return Header::exist($key,self::headers());
    }


    // hasFiles
    // retourne vrai si la requête contient des fichiers
    final public static function hasFiles():bool
    {
        return !empty(self::files());
    }


    // id
    // retourne le id unique de la requête
    final public static function id():string
    {
        if(self::$id === null)
        self::$id = Str::random(self::$config['idLength']);

        return self::$id;
    }


    // info
    // retourne l'ensemble des informations en lien avec la requête courante
    // possible d'exporter le id
    final public static function info(bool $id=false,bool $validIp=true):array
    {
        $return = self::parse();

        $return['relative'] = self::relative();
        $return['absolute'] = self::absolute();
        $return['schemeHost'] = self::schemeHost();
        $return['method'] = self::method();
        $return['ssl'] = self::isSsl();
        $return['ajax'] = self::isAjax();
        $return['timestamp'] = self::timestamp();
        $return['ip'] = self::ip($validIp);
        $return['get'] = self::get();
        $return['post'] = self::post();
        $return['files'] = self::files();
        $return['userAgent'] = self::userAgent();
        $return['referer'] = self::referer();
        $return['headers'] = self::headers();
        $return['lang'] = self::lang();
        $return['safe'] = self::isPathSafe();
        $return['cli'] = self::isCli();

        if($id === true)
        $return['id'] = self::id();

        return $return;
    }


    // export
    // exporte les informations liés à la requête courante
    // utile pour créer un objet core request
    // possible d'exporter le id
    final public static function export(bool $id=false,bool $validIp=true):array
    {
        $return = self::parse();
        $return['method'] = self::method();
        $return['timestamp'] = self::timestamp();
        $return['ip'] = self::ip($validIp);
        $return['post'] = self::post();
        $return['files'] = self::files();
        $return['headers'] = self::headers();
        $return['lang'] = self::lang();
        $return['cli'] = self::isCli();

        if($id === true)
        $return['id'] = self::id();

        return $return;
    }


    // parse
    // retourne le tableau parse de la requête courante
    // comme parse_str
    final public static function parse():array
    {
        $return = [];

        $return['scheme'] = self::scheme();
        $return['user'] = self::user();
        $return['pass'] = self::pass();
        $return['host'] = self::host();
        $return['port'] = self::port();
        $return['path'] = self::path();
        $return['query'] = self::query();
        $return['fragment'] = self::fragment();

        return $return;
    }


    // lang
    // retourne la langue de la requête
    final public static function lang():string
    {
        $return = Lang::default();

        $lang = self::pathLang();
        if(!empty($lang))
        $return = $lang;

        else
        {
            $langHeader = self::langHeader();
            if(!empty($langHeader))
            $return = $langHeader;
        }

        return $return;
    }


    // setLangs
    // change les langues dans les config de la classe
    final public static function setLangs(array $value):void
    {
        Arr::setRef('all',$value,self::$config['lang']);

        return;
    }


    // setSsl
    // change la valeur ssl de la requête courante
    final public static function setSsl(bool $value=true):void
    {
        $protocol = Http::protocol(Server::isHttp2());
        Superglobal::setServer('REQUEST_SCHEME',Http::scheme($value));
        Superglobal::setServer('SERVER_PORT',Http::port($value));
        Superglobal::setServer('HTTPS',($value === true)? 'on':'off');
        Superglobal::setServer('SERVER_PROTOCOL',$protocol);

        return;
    }


    // setAjax
    // change la valeur ajax de la requête courante
    final public static function setAjax(bool $value=true):void
    {
        if($value === true)
        self::setHeader('X-Requested-With','XMLHttpRequest');
        else
        self::unsetHeader('X-Requested-With');

        return;
    }


    // scheme
    // retourne le scheme de la requete
    final public static function scheme():?string
    {
        return (is_string($scheme = Superglobal::getServer('REQUEST_SCHEME')))? $scheme:null;
    }


    // setScheme
    // change la valeur du scheme de la requête courante
    final public static function setScheme(string $value):void
    {
        if(in_array($value,['http','https'],true))
        self::setSsl(($value === 'https'));

        return;
    }


    // user
    // retourne le nom d'utilisateur de la requête courante
    // request_user est crée par quid, ce n'est pas utilisé dans le tableau serveur par php
    final public static function user():?string
    {
        return Superglobal::getServer('REQUEST_USER');
    }


    // setUser
    // change la valeur de user de la requête courante
    // peut être null pour retirer
    final public static function setUser(?string $value):void
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
    final public static function pass():?string
    {
        return Superglobal::getServer('REQUEST_PASS');
    }


    // setPass
    // change la valeur de pass de la requête courante
    // peut être null pour retirer
    final public static function setPass(?string $value):void
    {
        if(is_string($value))
        Superglobal::setServer('REQUEST_PASS',$value);
        else
        Superglobal::unsetServer('REQUEST_PASS');

        return;
    }


    // host
    // retourne le domaine courant
    final public static function host():?string
    {
        $return = null;

        if(($serverName = Superglobal::getServer('SERVER_NAME')) !== null)
        $return = $serverName;

        elseif(($httpHost = self::getHeader('Host')) !== null)
        $return = $httpHost;

        return $return;
    }


    // setHost
    // change la valeur de host dans la requête courante
    final public static function setHost(string $value):void
    {
        Superglobal::setServer('SERVER_NAME',$value);
        Superglobal::setServer('HTTP_HOST',$value);

        return;
    }


    // port
    // retourne le port de la requête courante
    final public static function port():?int
    {
        return (is_numeric($port = Superglobal::getServer('SERVER_PORT')))? (int) $port:null;
    }


    // setPort
    // change la valeur de port dans la requête courante
    final public static function setPort(int $value):void
    {
        self::setSsl(Http::isPortSsl($value));

        return;
    }


    // path
    // retourne le path courant, sans query, en fonction de server request_uri
    final public static function path():string
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
    final public static function setPath(string $value):void
    {
        Superglobal::setServer('REQUEST_URI',Path::wrapStart($value));

        return;
    }


    // pathinfo
    // retourne pathinfo du path courant
    final public static function pathinfo()
    {
        return Path::info(self::path());
    }


    // dirname
    // retourne le dirname du path courant
    final public static function dirname():?string
    {
        return Path::infoOne('dirname',self::path());
    }


    // basename
    // retourne le basename du path courant
    final public static function basename():?string
    {
        return Path::infoOne('basename',self::path());
    }


    // filename
    // retourne le filename du path courant
    final public static function filename():?string
    {
        return Path::infoOne('filename',self::path());
    }


    // extension
    // retourne l'extension du path courant
    final public static function extension():?string
    {
        return Path::infoOne('extension',self::path());
    }


    // mime
    // retourne le mimetype du path courant
    final public static function mime():?string
    {
        return Path::mime(self::path());
    }


    // pathStripStart
    // retourne le path courant, sans query, et sans le slash au début
    final public static function pathStripStart():string
    {
        return Path::stripStart(self::path());
    }


    // pathExplode
    // explode le path courant
    final public static function pathExplode(?array $option=null):array
    {
        return Path::arr(self::pathStripStart(),$option);
    }


    // pathGet
    // retourne un index du path courant
    final public static function pathGet(int $index,?array $option=null):?string
    {
        return Path::get($index,self::pathStripStart(),$option);
    }


    // pathGets
    // retourne un tableau des index existants dans le path courant
    final public static function pathGets(array $indexes,?array $option=null):array
    {
        return Path::gets($indexes,self::pathStripStart(),$option);
    }


    // pathCount
    // count le nombre de niveau dans le path courant
    final public static function pathCount(?array $option=null):int
    {
        return Path::count(self::pathStripStart(),$option);
    }


    // pathSlice
    // tranche des slices du path courant en utilisant offset et length
    final public static function pathSlice(int $offset,?int $length,?array $option=null):array
    {
        return Path::slice($offset,$length,self::pathStripStart(),$option);
    }


    // pathQuery
    // retourne l'uri courant, avec query, en fonction de server request_uri
    final public static function pathQuery():string
    {
        return (is_string($requestUri = Superglobal::getServer('REQUEST_URI')))? $requestUri:'';
    }


    // pathLang
    // retourne le code de langue si présent dans le path
    final public static function pathLang():?string
    {
        return Path::lang(self::pathStripStart(),self::$config['lang']);
    }


    // query
    // retourne la valeur get courante
    final public static function query():string
    {
        return (is_string($queryString = Superglobal::getServer('QUERY_STRING')))? $queryString:'';
    }


    // setQuery
    // change la valeur de la query et met à jour le tableau get
    final public static function setQuery($value,bool $encode=false):void
    {
        $string = '';
        $array = [];

        if(is_string($value) && strlen($value))
        $value = Uri::parseQuery($value);

        if(is_array($value))
        {
            $array = $value;
            $string = Uri::buildQuery($value,$encode) ?? '';
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
    final public static function removeQuery():void
    {
        self::setQuery(null);

        return;
    }


    // setArgv
    // permet de lier des query à la requête à partir d'un tableau d'options de cli
    final public static function setArgv(array $values):void
    {
        $query = Cli::parseOpt(...array_values($values));
        if(!empty($query))
        self::setQuery($query);

        return;
    }


    // fragment
    // retourne le fragment de la requête courante
    // request_fragment est une invention de quid, ce n'est pas utilisé dans le tableau serveur par php
    final public static function fragment():?string
    {
        return Superglobal::getServer('REQUEST_FRAGMENT');
    }


    // setFragment
    // change la valeur de fragment de la requête courante
    // peut être null pour retirer
    final public static function setFragment(?string $value):void
    {
        if(is_string($value))
        Superglobal::setServer('REQUEST_FRAGMENT',$value);
        else
        Superglobal::unsetServer('REQUEST_FRAGMENT');

        return;
    }


    // method
    // retourne la method courante
    final public static function method():string
    {
        return (($requestMethod = Superglobal::getServer('REQUEST_METHOD')) && strtolower($requestMethod) === 'post')? 'post':'get';
    }


    // setMethod
    // change la méthode de la requête courante
    final public static function setMethod(string $value):void
    {
        $value = strtoupper($value);

        if(in_array($value,['GET','POST'],true))
        Superglobal::setServer('REQUEST_METHOD',$value);

        return;
    }


    // timestamp
    // retourne le timestamp de la requête
    // peut retourner le timestamp float
    final public static function timestamp(bool $float=false)
    {
        return ($float === true)? Superglobal::getServer('REQUEST_TIME_FLOAT'):Superglobal::getServer('REQUEST_TIME');
    }


    // setTimestamp
    // change le timestamp de la requête courante
    // gère aussi le timestamp float
    final public static function setTimestamp($value):void
    {
        if(is_numeric($value))
        {
            Superglobal::setServer('REQUEST_TIME',(int) $value);
            Superglobal::setServer('REQUEST_TIME_FLOAT',(float) $value);
        }

        return;
    }


    // schemeHost
    // retourne la string scheme + host
    final public static function schemeHost():?string
    {
        $return = null;
        $scheme = self::scheme();
        $host = self::host();

        if(!empty($scheme) && !empty($host))
        $return = Uri::build(['scheme'=>$scheme,'host'=>$host]);

        return $return;
    }


    // setSchemeHost
    // change le scheme et le host de la requête à partir d'une string schemeHost
    final public static function setSchemeHost(string $value):void
    {
        $scheme = Uri::scheme($value);
        $host = Uri::host($value);

        if(is_string($scheme) && is_string($host))
        {
            self::setScheme($scheme);
            self::setHost($host);
        }

        return;
    }


    // get
    // retourne le tableau get courant
    final public static function get():array
    {
        return Superglobal::get();
    }


    // post
    // retourne la valeur post courante
    final public static function post(bool $safeKey=false,bool $stripTags=false,bool $includeFiles=false):?array
    {
        return Superglobal::postReformat(Superglobal::post(),$safeKey,$stripTags,$includeFiles);
    }


    // files
    // retourne la valeur files courante
    final public static function files():?array
    {
        return Superglobal::files();
    }


    // csrf
    // retoure la valeur csrf de la requête si disponible
    final public static function csrf():?string
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
    final public static function setPost(array $value):void
    {
        Globals::set('_POST',$value);

        return;
    }


    // headers
    // retourne les en-têtes de la requête courante
    final public static function headers():array
    {
        return Superglobal::getServerHeader(true);
    }


    // getHeader
    // retourne un header
    // insensible à la case
    final public static function getHeader(string $key)
    {
        return Header::get($key,self::headers());
    }


    // setHeader
    // change un header de la requête
    // insensible à la case
    final public static function setHeader(string $key,$value):void
    {
        self::setHeaders(Header::set($key,$value,self::headers()));

        return;
    }


    // unsetHeader
    // enlève un header de la requête
    // insensible à la case
    final public static function unsetHeader(string $key):void
    {
        self::setHeaders(Header::unset($key,self::headers()));

        return;
    }


    // setHeaders
    // replace les headers de la requête par ceux fournis
    // refait le tableau server
    final public static function setHeaders(array $values):void
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
    final public static function ip(bool $valid=true):?string
    {
        $return = null;

        if(($forwardedFor = self::getHeader('X-Forwarded-For')) !== null)
        $return = $forwardedFor;

        elseif(($remoteAddr = Superglobal::getServer('REMOTE_ADDR')) !== null)
        $return = $remoteAddr;

        if($valid === true)
        $return = Ip::normalize($return);

        return $return;
    }


    // setIp
    // change le ip de la requête courante
    final public static function setIp(string $value):void
    {
        if(Ip::is($value))
        {
            if(self::headerExists('X-Forwarded-For'))
            self::setHeader('X-Forwarded-For',$value);

            Superglobal::setServer('REMOTE_ADDR',$value);
        }

        return;
    }


    // userAgent
    // retourne le user agent courant
    final public static function userAgent():?string
    {
        return self::getHeader('User-Agent');
    }


    // setUserAgent
    // change le user agent courant
    // peut être null pour retirer le header
    final public static function setUserAgent(?string $value):void
    {
        self::setHeader('User-Agent',$value);

        return;
    }


    // referer
    // retourne l'uri référent à la requête
    // possible de retourner seulement si le referer est interne (et possible de spécifier un tableau d'host considéré comme interne)
    final public static function referer(bool $internal=false,$hosts=null):?string
    {
        $return = null;
        $referer = self::getHeader('Referer');

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
    final public static function setReferer(?string $value):void
    {
        self::setHeader('Referer',$value);

        return;
    }


    // langHeader
    // retourne la valeur du header lang de la requête courante
    final public static function langHeader():?string
    {
        return (is_string($acceptLanguage = self::getHeader('Accept-Language')))? substr($acceptLanguage,0,2):null;
    }


    // setLangHeader
    // change la valeur du header lang de la requête courante
    final public static function setLangHeader(?string $value):void
    {
        self::setHeader('Accept-Language',$value);

        return;
    }


    // fingerprint
    // retourne un fingerprint sha1 des entêtes de requêtes
    final public static function fingerprint(array $keys):?string
    {
        return Header::fingerprint(self::headers(),$keys);
    }


    // redirect
    // retourne l'uri de redirection si l'uri courante présente des défauts
    // par exemple path unsafe, double slash, slash à la fin ou manque pathLang
    // possibilité de retourner le chemin absolut
    final public static function redirect(bool $absolute=false):?string
    {
        $return = null;
        $path = self::pathStripStart();
        $return = Path::redirect($path,self::$config['safe'],self::$config['lang']);

        if(is_string($return) && $absolute === true)
        $return = Uri::absolute($return);

        return $return;
    }


    // str
    // envoie info dans http str qui retourne une string pour représenter la requête
    final public static function str():string
    {
        return Http::str(self::info());
    }


    // uri
    // construit l'uri à partir du tableau parse
    // n'est pas encodé ou décodé, plus rapide que les autres méthodes
    final public static function uri(bool $absolute=false):string
    {
        $return = '';

        if($absolute === true)
        $parse = self::parse();
        else
        $parse = ['path'=>self::path(),'query'=>self::query(),'fragment'=>self::fragment()];

        $return = Uri::build($parse,false);

        return $return;
    }


    // output
    // retourne l'uri, peut être relatif ou absolut dépendamment des options
    final public static function output(?array $option=null):?string
    {
        return Uri::output(self::uri(true),$option);
    }


    // relative
    // retourne l'uri relative de la requête
    final public static function relative(?array $option=null):string
    {
        return Uri::relative(self::uri(false),$option);
    }


    // absolute
    // retourne l'uri absolut de la requête
    final public static function absolute(?array $option=null):?string
    {
        return Uri::absolute(self::uri(true),$option);
    }


    // change
    // cette méthode permet de remplacer le tableau serveur
    // est utilisé par le cli
    final public static function change(array $values,bool $default=false):void
    {
        if($default === true && Server::isCli())
        $values = Arr::replace(self::defaultCli(),$values);

        foreach (self::prepareChangeArray($values) as $key => $value)
        {
            if(is_string($key))
            {
                $method = 'set'.ucfirst($key);

                if(method_exists(self::class,$method))
                self::$method($value);
            }
        }

        return;
    }


    // prepareChangeArray
    // fait une transformation au tableau de changement
    // la clé 0 devient uri
    final public static function prepareChangeArray(array $return):array
    {
        $change = [0=>'uri'];
        $return = Arr::keysChange($change,$return);

        return $return;
    }


    // defaultCli
    // retourne les défauts pour cli
    final public static function defaultCli():array
    {
        $return['scheme'] = 'http';
        $return['query'] = [];
        $return['ip'] = Server::ip();
        $return['userAgent'] = Server::quidName();
        $return['langHeader'] = Lang::default();

        return $return;
    }
}
?>