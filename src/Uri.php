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

// uri
// class with static methods to generate URI (absolute and relative)
final class Uri extends Root
{
    // trait
    use _option;
    use _shortcut;


    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'absolute'=>null, // si l'uri doit être absolute
            'schemeHost'=>null, // schemehost pour uri relative passé dans une méthode absolute, aussi pour exists
            'encode'=>true, // l'uri out doit être encodé
            'decode'=>false, // l'uri in doit être décodé
            'append'=>false, // ce qu'il faut append aux uri, true signifie timestamp
            'exists'=>false,  // vérifie si le uri existe sur le serveur
            'notFoundCallable'=>[Error::class,'trigger']], // callable si l'uri n'existe pas
        'protocolRelative'=>'//', // caractère pour désigner un chemin protocol-relative
        'query'=>[ // paramètres pour la construction et encodage des query
            'parse'=>['+'=>'%2B'], // remplacement pour parse_str
            'separator'=>['&','&amp;'], // séparateur pour http_build_query
            'encoding'=>PHP_QUERY_RFC3986, // encodage pour http_build_query
            'default'=>'t'], // clé de query a utilisé si append est true
        'parseConstant'=>[ // tableau liant des strings aux constantes
            'scheme'=>PHP_URL_SCHEME,
            'user'=>PHP_URL_USER,
            'pass'=>PHP_URL_PASS,
            'host'=>PHP_URL_HOST,
            'port'=>PHP_URL_PORT,
            'path'=>PHP_URL_PATH,
            'query'=>PHP_URL_QUERY,
            'fragment'=>PHP_URL_FRAGMENT],
        'scheme'=>['http','https','ftp'], // scheme accepté par la méthode isSchemeValid
        'build'=>[ // pour reconstruire à partir d'un array
            'scheme'=>'://',
            'user'=>'',
            'pass'=>':',
            'host'=>'@',
            'port'=>':',
            'path'=>'/',
            'query'=>'?',
            'fragment'=>'#'],
        'redirection'=>'*' // caractère pour les redirections via tableau
    ];


    // absolute
    protected static bool $absolute = false; // détermine si toutes les requêtes dans output doivent être absolute


    // scheme
    protected static array $scheme = []; // tableau associatif entre host et scheme


    // is
    // retourne vrai si la valeur est uri relative ou absolut
    final public static function is($value,bool $decode=false):bool
    {
        return is_string($value) && self::info($value,$decode)['type'] !== false;
    }


    // isRelative
    // retourne vrai si la valeur est uri relative
    final public static function isRelative($value):bool
    {
        $return = false;

        if(is_string($value))
        {
            $type = self::type($value);

            if($type === 'relative')
            $return = true;
        }

        return $return;
    }


    // isAbsolute
    // retourne vrai si la valeur est uri absolute
    final public static function isAbsolute($value):bool
    {
        $return = false;

        if(is_string($value))
        {
            $type = self::type($value);

            if($type === 'absolute')
            $return = true;
        }

        return $return;
    }


    // isSsl
    // retourne vrai si l'uri est https
    final public static function isSsl(string $uri,bool $decode=false):bool
    {
        return self::scheme($uri,$decode) === Http::scheme(true);
    }


    // isSchemeValid
    // retourne vrai si le scheme est supporté par la classe
    final public static function isSchemeValid($value):bool
    {
        return is_string($value) && in_array(strtolower($value),self::$config['scheme'],true);
    }


    // isSchemeProtocolRelative
    // retourne vrai si le scheme est relatif au protocl
    final public static function isSchemeProtocolRelative(string $uri):bool
    {
        return substr($uri,0,2) === self::$config['protocolRelative'];
    }


    // hasScheme
    // retourne vrai si l'uri a un scheme
    final public static function hasScheme(string $uri,bool $decode=false):bool
    {
        return !empty(self::scheme($uri,$decode));
    }


    // hasExtension
    // retourne vrai si le chemin a une extension
    final public static function hasExtension(string $uri,bool $decode=false):bool
    {
        return !empty(self::extension($uri,$decode));
    }


    // hasQuery
    // retourne vrai si le chemin a un query
    final public static function hasQuery(string $uri,bool $decode=false):bool
    {
        return !empty(self::query($uri,$decode));
    }


    // hasLang
    // retourne vrai si l'uri a une lang
    final public static function hasLang(string $uri,bool $decode=false):bool
    {
        return self::lang($uri,$decode) !== null;
    }


    // isInternal
    // retourne vrai si l'uri donné est interne
    // un ou plusieurs host peut être fourni, sinon utilise celui de request
    // si l'uri n'a pas de host, retourne vrai
    final public static function isInternal(string $value,$host=null):bool
    {
        $return = false;

        if(self::isAbsolute($value))
        {
            if(empty($host))
            $host = Request::host();

            if(self::isHost($host,$value))
            $return = true;
        }
        else
        $return = true;

        return $return;
    }


    // isExternal
    // retourne vrai si l'uri donné est externe
    // un host ou plusieurs peut être fourni, sinon utilise celui de request
    // si l'uri n'a pas de host, retourne faux
    final public static function isExternal(string $value,$host=null):bool
    {
        $return = false;

        if(self::isAbsolute($value))
        {
            if(empty($host))
            $host = Request::host();

            if(!self::isHost($host,$value))
            $return = true;
        }

        return $return;
    }


    // isFragment
    // retourne vrai si l'url commence par le caractère de fragment
    final public static function isFragment($value):bool
    {
        return is_string($value) && strpos($value,self::$config['build']['fragment']) === 0;
    }


    // isScheme
    // retourne vrai si l'uri a un scheme du type spécifié
    final public static function isScheme($value,string $uri,bool $decode=false):bool
    {
        $return = false;
        $scheme = self::scheme($uri,$decode);

        if(is_string($value) && $value === $scheme)
        $return = true;

        elseif(is_bool($value) && Http::scheme($value) === $scheme)
        $return = true;

        return $return;
    }


    // isHost
    // retourne vrai si l'uri a l'hôte spécifié
    // possibilité de mettre une ou plusieurs target
    // comparaison insensible à la case
    final public static function isHost($target,string $uri,bool $decode=false):bool
    {
        $return = false;

        if(is_string($target) || is_array($target))
        {
            $host = self::host($uri,$decode);

            if(!empty($host) && Arr::in($host,(array) $target,false))
            $return = true;
        }

        return $return;
    }


    // isSchemeHost
    // retourne vrai si l'uri a le scheme host spécifié
    final public static function isSchemeHost($value,string $uri,bool $decode=false):bool
    {
        $return = false;
        $schemeHost = self::schemeHost($uri,$decode);

        if(is_string($value) && $value === $schemeHost)
        $return = true;

        return $return;
    }


    // isExtension
    // retourne vrai si l'uri à une extension du type
    // possibilité de mettre une ou plusieurs target
    // comparaison insensible à la case
    final public static function isExtension($target,string $uri,bool $decode=false):bool
    {
        $return = false;

        if(is_string($target) || is_array($target))
        {
            $extension = self::extension($uri,$decode);

            if(!empty($extension) && Arr::in($extension,(array) $target,false))
            $return = true;
        }

        return $return;
    }


    // isQuery
    // retourne vrai si l'uri a des query
    // keys permet de retourner vrai si une ou plusieurs key de get sont présents
    final public static function isQuery($keys,string $uri,bool $decode=false):bool
    {
        $return = false;

        if(is_string($keys) || is_array($keys))
        {
            $keys = (array) $keys;
            $query = self::queryArray($uri,true,$decode);

            if(Arr::keysExists($keys,$query))
            $return = true;
        }

        return $return;
    }


    // isLang
    // retourne vrai si l'uri a la langue spécifié
    final public static function isLang($value,string $uri,bool $decode=false):bool
    {
        return is_string($value) && $value === self::lang($uri,$decode);
    }


    // sameSchemeHost
    // retourne vrai si les deux uris ont le même schemeHost
    final public static function sameSchemeHost($value,string $uri,bool $decode=false):bool
    {
        $return = false;
        $schemeHost = self::schemeHost($uri,$decode);

        if(is_string($value) && self::schemeHost($value,$decode) === $schemeHost)
        $return = true;

        return $return;
    }


    // areAllAbsolute
    // retourne vrai si toutes les urls générés par output doivent être en absolute
    final public static function areAllAbsolute():bool
    {
        return self::$absolute;
    }


    // type
    // retourne rapidement le type de l'uri
    final public static function type(string $uri,bool $decode=false):?string
    {
        $return = null;

        if(self::isSchemeProtocolRelative($uri))
        $return = 'absolute';

        elseif(strpos($uri,'http:') === 0)
        $return = 'absolute';

        elseif(strpos($uri,'https:') === 0)
        $return = 'absolute';

        elseif(strpos($uri,'ftp:') === 0)
        $return = 'absolute';

        elseif(strpos($uri,'/') === 0)
        $return = 'relative';

        return $return;
    }


    // output
    // format et encode une uri
    // peut être relative, absolute ou tel que fourni
    // si areAllAbsolute est true, et que la valeur n'est pas un fragment, alors on peut forcer l'absolute (passe par-dessus les options)
    final public static function output(string $return,?array $option=null):string
    {
        $return = self::shortcut($return);
        $option = self::option($option);
        $absolute = $option['absolute'] ?? null;
        $schemeHost = $option['schemeHost'] ?? self::schemeHost($return);

        if(self::areAllAbsolute() && !self::isFragment($return))
        $return = self::absolute($return,$schemeHost,$option);

        else
        {
            if(is_string($schemeHost) && !empty($schemeHost) && $absolute === null)
            $option['absolute'] = (Request::isSchemeHost($schemeHost) || Request::isHost($schemeHost))? false:true;

            if($option['absolute'] === false)
            $return = self::relative($return,$option);

            elseif($option['absolute'] === true || self::isAbsolute($return))
            $return = self::absolute($return,$schemeHost,$option);

            else
            $return = self::relative($return,$option);
        }

        return $return;
    }


    // outputExists
    // output une uri avec option exists à true
    final public static function outputExists(string $uri,?array $option=null):string
    {
        return self::output($uri,self::option(Arr::plus($option,['exists'=>true])));
    }


    // relative
    // output une uri en relatif
    // si return est vide, retourne /
    final public static function relative(string $return,?array $option=null):string
    {
        $return = self::shortcut($return);
        $option = self::option($option);

        if(self::isAbsolute($return))
        $return = self::removeBefore('path',$return,$option['decode']);

        if($return !== '#')
        {
            if($return === '')
            $return = '/';

            if(strlen($return))
            {
                if(!empty($option['append']))
                $return = self::append($option['append'],$return);

                if($option['encode'] === true)
                $return = self::encodeAll($return,$option['decode']);

                if($option['exists'] === true && self::isCallable($option['notFoundCallable']))
                {
                    $host = (is_string($option['schemeHost']))? $option['schemeHost']:Request::schemeHost();
                    if(!Finder::isUriToPath($return,$host))
                    $return = self::existsCallable($return,$option['notFoundCallable']);
                }
            }
        }

        return $return;
    }


    // absolute
    // output une uri en absolute
    // une uri avec un scheme et un nom de domaine peut être fourni
    // si pas de scheme domain fournit, utilise current scheme/domain de request
    // l'option exists est seulement déclenché si le host de l'uri est présent dans finder/host
    final public static function absolute(string $return,$schemeHost=null,?array $option=null):string
    {
        $return = self::shortcut($return);
        $option = self::option($option);

        if(!self::isAbsolute($return))
        {
            if(is_string($schemeHost) && !empty($schemeHost))
            {
                $static = self::getSchemeHostStatic($schemeHost);
                if(is_string($static))
                $schemeHost = $static;
            }

            if(empty($schemeHost))
            $schemeHost = (!empty($option['schemeHost']))? $option['schemeHost']:Request::schemeHost();

            if(!empty($schemeHost))
            {
                $scheme = self::scheme($schemeHost);
                if($scheme === null)
                $schemeHost = self::changeScheme(Request::scheme(),$schemeHost);

                $return = self::combine($schemeHost,$return,'path',$option['decode']);
            }
        }

        if(strlen($return))
        {
            $return = self::changeProtocolRelativeScheme($return);

            if(!empty($option['append']))
            $return = self::append($option['append'],$return);

            if($option['encode'] === true)
            $return = self::encodeAll($return,$option['decode']);

            if($option['exists'] === true && self::isCallable($option['notFoundCallable']))
            {
                $host = self::host($return);
                if(Finder::isHost($host) && !Finder::isUriToPath($return))
                $return = self::existsCallable($return,$option['notFoundCallable']);
            }
        }

        return $return;
    }


    // existsCallable
    // utilisé par relative et absolute pour vérifier l'existence du fichier à partir d'une uri
    // si la callable retourne une string, il est considéré que c'est une uri de remplacement
    final public static function existsCallable(string $uri,callable $callable):?string
    {
        $return = null;
        $result = $callable($uri);

        if(is_string($result))
        $return = $result;

        return $return;
    }


    // append
    // gère l'ajout automatique de contenu à la fin de uri
    // si value est true, ça signifie timestamp et utilise la clé query par défaut
    // si dans le tableau, value est true ça signifie timestamp
    final public static function append($value,string $return,bool $cast=true,bool $encode=false,bool $decode=false):string
    {
        $query = [];

        if($value === true && is_string(self::$config['query']['default']))
        $value = [self::$config['query']['default']=>$value];

        if(is_array($value))
        {
            foreach ($value as $k => $v)
            {
                if(is_scalar($v))
                {
                    if($v === true)
                    $v = Datetime::time();

                    $query[$k] = $v;
                }
            }
        }

        if(!empty($query))
        $return = self::setsQuery($query,$return,$cast,$encode,$decode);

        return $return;
    }


    // encode
    // wrapper pour la fonction d'encodage
    // par défaut, utilise rawurlencode
    final public static function encode(string $return,int $type=0)
    {
        if($type === 0)
        $return = rawurlencode($return);

        if($type === 1)
        $return = urlencode($return);

        return $return;
    }


    // decode
    // wrapper pour la fonction de décodage
    // par défaut, utilise rawurldecode
    final public static function decode(string $return,int $type=0):?string
    {
        if($type === 0)
        $return = rawurldecode($return);

        if($type === 1)
        $return = urldecode($return);

        if(!empty(self::$config['query']['separator']))
        $return = str_replace(self::$config['query']['separator'][1],self::$config['query']['separator'][0],$return);

        return $return;
    }


    // encodeAll
    // encode toutes les partis de l'uri
    final public static function encodeAll(string $uri,bool $decode=false):string
    {
        $return = '';
        $parse = self::parse($uri,$decode);

        foreach ($parse as $key => $value)
        {
            if(is_string($value))
            {
                if($key === 'fragment')
                $parse[$key] = self::encode($value);

                elseif($key === 'path')
                $parse[$key] = self::encodePath($value);

                elseif($key === 'query')
                $parse[$key] = self::encodeQuery($value);
            }
        }

        $return = self::build($parse,false);

        return $return;
    }


    // encodePath
    // encode les différentes étages du path uri
    final public static function encodePath(string $path):string
    {
        $array = [];
        $separatorEnd = Path::isSeparatorEnd($path);

        foreach (Path::arr($path) as $key => $value)
        {
            $array[$key] = self::encode($value);
        }
        $return = Path::str($array,['end'=>$separatorEnd]);

        return $return;
    }


    // encodeQuery
    // encode le query
    final public static function encodeQuery(string $query):string
    {
        $array = self::parseQuery($query);
        $return = self::buildQuery($array,true);

        return $return;
    }


    // parseQuery
    // prend une chaîne de type get et retourne un array
    final public static function parseQuery(string $query,bool $cast=true,?bool $mb=null):array
    {
        $return = [];
        $mb = Encoding::getMb($mb,$query);

        $replace = self::$config['query']['parse'];
        if(!empty($replace))
        $query = Str::replace($replace,$query);

        if($mb === true)
        mb_parse_str($query,$return);
        else
        parse_str($query,$return);

        if($cast === true)
        $return = Arr::cast($return);

        return $return;
    }


    // buildQuery
    // prend un array et retourne une string de type query
    final public static function buildQuery(array $query,bool $encode=false):?string
    {
        $return = null;
        $separator = ($encode === false)? self::$config['query']['separator'][1]:self::$config['query']['separator'][0];
        $encoding = self::$config['query']['encoding'];
        $return = http_build_query($query,'',$separator,$encoding);

        if($encode === false)
        $return = self::decode($return);

        if(empty($return))
        $return = null;

        return $return;
    }


    // parse
    // decode et parse_url
    // les shortcut sont remplacés
    final public static function parse(string $uri,bool $decode=false):?array
    {
        $return = null;
        $uri = self::shortcut($uri);

        if($decode === true)
        $uri = self::decode($uri);

        $parse = parse_url($uri,-1);

        if(is_array($parse))
        {
            $return = ['scheme'=>null,'user'=>null,'pass'=>null,'host'=>null,'port'=>null,'path'=>null,'query'=>null,'fragment'=>null];

            if(self::isSchemeProtocolRelative($uri))
            $return['scheme'] = Request::scheme();

            foreach ($parse as $key => $value)
            {
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // parseOne
    // decode et parse_url
    // les shortcut sont remplacés
    // retourne une partie de l'uri
    final public static function parseOne($key,string $uri,bool $decode=false)
    {
        $return = null;

        if(is_string($key))
        $key = self::getParseConstant($key);

        if(is_int($key))
        {
            $uri = self::shortcut($uri);

            if($decode === true)
            $uri = self::decode($uri);

            $return = parse_url($uri,$key);

            if($return === false || $return === '')
            $return = null;
        }

        return $return;
    }


    // getParseConstant
    // retourne la constante à partir d'une string
    // utilisé pour parse_url
    final public static function getParseConstant(string $key):?int
    {
        return self::$config['parseConstant'][$key] ?? null;
    }


    // getEmptyParse
    // retourne un tableau vide similaire au retour de parse_url
    final public static function getEmptyParse():array
    {
        return Arr::valuesAll(null,self::$config['parseConstant']);
    }


    // info
    // retourne un tableau d'information sur l'uri
    final public static function info(string $uri,bool $decode=false):array
    {
        $return = [];
        $parse = self::parse($uri,$decode);

        $return['source'] = $uri;
        $return['type'] = false;
        $return['protocolRelative'] = false;
        $return['ssl'] = false;
        $return['parse'] = $parse;
        $return['pathinfo'] = ['dirname'=>null,'basename'=>null,'filename'=>null,'extension'=>null];

        // scheme
        if(!empty($parse['scheme']) && $parse['scheme'] === 'https')
        $return['ssl'] = true;

        // host
        if(!empty($parse['host']))
        {
            // protocol-relative scheme
            if(self::isSchemeProtocolRelative($uri))
            {
                $return['type'] = 'absolute';
                $return['protocolRelative'] = true;
                $return['parse']['scheme'] = Request::scheme();
            }

            elseif(!empty($parse['scheme']))
            $return['type'] = 'absolute';
        }

        // path
        if(!empty($parse['path']))
        {
            // pathinfo
            $pathinfo = self::pathinfo($parse['path']);
            if($pathinfo !== null)
            $return['pathinfo'] = array_merge($return['pathinfo'],$pathinfo);

            // vérifie relative
            if(empty($return['type']) && Path::isSeparatorStart($parse['path']))
            $return['type'] = 'relative';
        }

        return $return;
    }


    // scheme
    // retourne le scheme de l'uri
    final public static function scheme($value,bool $decode=false):?string
    {
        $return = null;

        if(is_bool($value) || is_int($value))
        $return = Http::scheme($value);

        elseif(is_string($value))
        {
            if(in_array($value,['http','https'],true))
            $return = $value;

            elseif(self::isSchemeProtocolRelative($value))
            {
                $host = self::host($value);
                if(is_string($host))
                $return = self::getSchemeStatic($host);

                if(empty($return))
                $return = Request::scheme();
            }

            else
            $return = self::parseOne('scheme',$value,$decode);

            if(empty($return))
            $return = null;
        }

        return $return;
    }


    // changeScheme
    // change le scheme d'une uri
    // ajoute le scheme si le uri n'a pas de scheme
    final public static function changeScheme($scheme,string $uri,bool $decode=false):string
    {
        $return = '';
        $scheme = self::scheme($scheme,$decode);
        $uriScheme = self::scheme($uri,$decode);

        if($uriScheme === null && is_string($scheme))
        $uri = $scheme.'://'.$uri;

        $return = self::change(['scheme'=>$scheme],$uri,$decode);

        return $return;
    }


    // changeProtocolRelativeScheme
    // change le scheme d'une uri si le scheme actuelle est relative au procole
    final public static function changeProtocolRelativeScheme(string $return,bool $decode=false)
    {
        if(self::isSchemeProtocolRelative($return))
        {
            $scheme = self::scheme($return,$decode);
            $return = self::changeScheme($scheme,$return,$decode);
        }

        return $return;
    }


    // removeScheme
    // enlève le scheme d'une uri
    final public static function removeScheme(string $uri,bool $decode=false):string
    {
        return self::remove('scheme',$uri,$decode);
    }


    // user
    // retourne le username de l'uri
    final public static function user(string $uri,bool $decode=false):?string
    {
        return self::parseOne('user',$uri,$decode);
    }


    // changeUser
    // change le user d'une uri
    final public static function changeUser($user,string $uri,bool $decode=false):string
    {
        return self::change(['user'=>$user],$uri,$decode);
    }


    // removeUser
    // enlève le user d'une uri
    final public static function removeUser(string $uri,bool $decode=false):string
    {
        return self::remove('user',$uri,$decode);
    }


    // pass
    // retourne le password de l'uri
    final public static function pass(string $uri,bool $decode=false):?string
    {
        return self::parseOne('pass',$uri,$decode);
    }


    // changePass
    // change le pass d'une uri
    final public static function changePass($pass,string $uri,bool $decode=false):string
    {
        return self::change(['pass'=>$pass],$uri,$decode);
    }


    // removePass
    // enlève le pass d'une uri
    final public static function removePass(string $uri,bool $decode=false):string
    {
        return self::remove('pass',$uri,$decode);
    }


    // host
    // retourne le host de l'uri
    final public static function host(string $uri,bool $decode=false):?string
    {
        return self::parseOne('host',$uri,$decode);
    }


    // changeHost
    // change le host d'une uri
    final public static function changeHost($host,string $uri,bool $decode=false):string
    {
        return self::change(['host'=>$host],$uri,$decode);
    }


    // removeHost
    // enlève le host d'une uri
    final public static function removeHost(string $uri,bool $decode=false):string
    {
        return self::remove('host',$uri,$decode);
    }


    // port
    // retourne le port de l'uri
    final public static function port(string $uri,bool $decode=false):?int
    {
        return self::parseOne('port',$uri,$decode);
    }


    // changePort
    // change le port d'une uri
    final public static function changePort($port,string $uri,bool $decode=false):string
    {
        return self::change(['port'=>$port],$uri,$decode);
    }


    // removePort
    // enlève le port d'une uri
    final public static function removePort(string $uri,bool $decode=false):string
    {
        return self::remove('port',$uri,$decode);
    }


    // path
    // retourne le path de l'uri
    final public static function path(string $uri,bool $decode=false):?string
    {
        return self::parseOne('path',$uri,$decode);
    }


    // pathStripStart
    // retourne le path de l'uri sans le séparateur au début
    final public static function pathStripStart(string $uri,bool $decode=false):?string
    {
        $return = self::path($uri,$decode);

        if(!is_string($return))
        $return = null;

        else
        $return = Path::stripStart($return);

        return $return;
    }


    // changePath
    // change le path d'une uri
    final public static function changePath($path,string $uri,bool $decode=false):string
    {
        return self::change(['path'=>$path],$uri,$decode);
    }


    // removePath
    // enlève le path d'une uri
    final public static function removePath(string $uri,bool $decode=false):string
    {
        return self::remove('path',$uri,$decode);
    }


    // pathinfo
    // retourne le tableau pathinfo
    final public static function pathinfo(string $uri,bool $decode=false):?array
    {
        $return = null;
        $path = self::path($uri,$decode);

        if(is_string($path))
        $return = Path::info($path);

        return $return;
    }


    // pathInfoOne
    // retourne une entrée du tableau de pathinfo
    final public static function pathinfoOne($key,string $uri,bool $decode=false):?string
    {
        $return = null;
        $path = self::path($uri,$decode);

        if(is_string($path))
        $return = Path::infoOne($key,$path);

        return $return;
    }


    // changePathinfo
    // change un ou plusieurs éléments du path de l'uri
    final public static function changePathinfo(array $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::change($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // keepPathinfo
    // garde un ou plusieurs éléments du path de l'uri
    final public static function keepPathinfo($change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::keep($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removePathinfo
    // enlève un ou plusieurs éléments du path de l'uri
    final public static function removePathinfo($change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::remove($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // dirname
    // retourne le dirname du path
    final public static function dirname(string $uri,bool $decode=false):?string
    {
        return self::pathinfoOne('dirname',$uri,$decode);
    }


    // addDirname
    // ajoute un dirname au dirname d'une uri
    final public static function addDirname(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::addDirname($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // changeDirname
    // change le dirname d'une uri
    final public static function changeDirname(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::changeDirname($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removeDirname
    // enlève un dirname à une uri
    final public static function removeDirname(string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::removeDirname($path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // basename
    // retourne le basename du path
    final public static function basename(string $uri,bool $decode=false):?string
    {
        return self::pathinfoOne('basename',$uri,$decode);
    }


    // addBasename
    // ajoute le basename à une uri
    final public static function addBasename(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::addBasename($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // changeBasename
    // change le basename d'une uri
    final public static function changeBasename(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::changeBasename($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removeBasename
    // enlève un basename à une uri
    final public static function removeBasename(string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::removeBasename($path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // filename
    // retourne le filename du path
    final public static function filename(string $uri,bool $decode=false):?string
    {
        return self::pathinfoOne('filename',$uri,$decode);
    }


    // addFilename
    // ajoute le filename à une uri
    final public static function addFilename(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::addFilename($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // changeFilename
    // change le filename d'une uri
    final public static function changeFilename(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::changeFilename($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removeFilename
    // enlève un filename à une uri
    final public static function removeFilename(string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::removeFilename($path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // extension
    // retourne l'extension du path
    final public static function extension(string $uri,bool $decode=false):?string
    {
        return self::pathinfoOne('extension',$uri,$decode);
    }


    // addExtension
    // ajoute l'extension à une uri
    final public static function addExtension(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::addExtension($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // changeExtension
    // change l'extension d'une uri
    final public static function changeExtension(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::changeExtension($change,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removeExtension
    // enlève une extension à une uri
    final public static function removeExtension(string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::removeExtension($path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // mime
    // retourne le mimetype du path à partir de son extension
    // ne vérifie pas l'existence du fichier
    final public static function mime(string $uri,bool $decode=false):?string
    {
        return Path::mime(self::path($uri,$decode) ?? '');
    }


    // addLang
    // ajoute un code de langue à une uri
    // ajoute même si le code existe déjà
    // le path sera retourné vide si le code langue est invalide
    final public static function addLang(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::addLang($change,$path) ?? '';
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // changeLang
    // ajoute ou remplace un code de langue à une uri
    // le path sera retourné vide si le code langue est invalide
    final public static function changeLang(string $change,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::changeLang($change,$path) ?? '';
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // removeLang
    // enlève un code de langue à une uri
    // retourne le chemin dans tous les cas
    final public static function removeLang(string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::removeLang($path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // pathPrepend
    // prepend un ou plusieurs path derrière le path de l'uri
    // decode est false
    final public static function pathPrepend(string $uri,string ...$values):string
    {
        $return = '';

        $path = self::path($uri) ?? '';
        $path = Path::prepend($path,...$values);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // pathAppend
    // append un ou plusieurs path devant le path de l'uri
    // decode est false
    final public static function pathAppend(string $uri,string ...$values):string
    {
        $return = '';

        $path = self::path($uri) ?? '';
        $path = Path::append($path,...$values);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // pathExplode
    // explode un path
    final public static function pathExplode(string $uri,bool $decode=false):array
    {
        return Path::arr(self::path($uri,$decode) ?? '');
    }


    // pathGet
    // retourne un index du path
    final public static function pathGet(int $index,string $uri,bool $decode=false):?string
    {
        return Path::get($index,self::path($uri,$decode) ?? '');
    }


    // pathCount
    // count le nombre de niveau dans le path
    final public static function pathCount(string $uri,bool $decode=false):int
    {
        return Path::count(self::path($uri,$decode) ?? '');
    }


    // pathSlice
    // tranche des slices d'un path en utilisant offset et length
    final public static function pathSlice(int $offset,?int $length,string $uri,bool $decode=false):array
    {
        return Path::slice($offset,$length,self::path($uri,$decode) ?? '');
    }


    // pathSplice
    // efface et remplace des slices d'un path en utilisant offset et length
    final public static function pathSplice(int $offset,?int $length,string $uri,$replace=null,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::splice($offset,$length,$path,$replace);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // pathInsert
    // ajoute un ou plusieurs éléments dans le path sans ne rien effacer
    final public static function pathInsert(int $offset,$replace,string $uri,bool $decode=false):string
    {
        $return = '';

        $path = self::path($uri,$decode) ?? '';
        $path = Path::insert($offset,$replace,$path);
        $return = self::change(['path'=>$path],$uri);

        return $return;
    }


    // query
    // retourne le query de l'uri
    final public static function query(string $uri,bool $decode=false):?string
    {
        return self::parseOne('query',$uri,$decode);
    }


    // queryArray
    // retourne le tableau de query
    final public static function queryArray(string $uri,bool $cast=true,bool $decode=false):array
    {
        $return = [];
        $query = self::query($uri,$decode);

        if(!empty($query))
        $return = self::parseQuery($query,$cast);

        return $return;
    }


    // changeQuery
    // change le query d'une uri
    final public static function changeQuery($query,string $uri,bool $decode=false):string
    {
        return self::change(['query'=>$query],$uri,$decode);
    }


    // removeQuery
    // enlève le query d'une uri
    final public static function removeQuery(string $uri,bool $decode=false):string
    {
        return self::remove('query',$uri,$decode);
    }


    // getQuery
    // retourne la valeur d'une clé dans query
    final public static function getQuery(string $key,string $uri,bool $cast=true,bool $decode=false)
    {
        return Arr::get($key,self::queryArray($uri,$cast,$decode));
    }


    // getsQuery
    // retournes plusieurs valeurs dans query
    final public static function getsQuery(array $keys,string $uri,bool $cast=true,bool $decode=false):array
    {
        return Arr::gets($keys,self::queryArray($uri,$cast,$decode));
    }


    // setQuery
    // insert ou update la valeur dans query
    // retourne l'uri
    final public static function setQuery($key,$value,string $uri,bool $cast=true,bool $encode=false,bool $decode=false):string
    {
        $return = '';

        $query = Arr::set($key,$value,self::queryArray($uri,$cast,$decode));
        $return = self::change(['query'=>self::buildQuery($query,$encode)],$uri);

        return $return;
    }


    // setsQuery
    // insert ou update une ou plusieurs valeurs dans query
    // retourne l'uri
    final public static function setsQuery(array $keyValue,string $uri,bool $cast=true,bool $encode=false,bool $decode=false):string
    {
        $return = '';

        $query = Arr::sets($keyValue,self::queryArray($uri,$cast,$decode));
        $return = self::change(['query'=>self::buildQuery($query,$encode)],$uri);

        return $return;
    }


    // unsetQuery
    // enlève une clé dans query
    // retourne l'uri
    final public static function unsetQuery(string $key,string $uri,bool $cast=true,bool $encode=false,bool $decode=false):string
    {
        $return = '';

        $query = Arr::unset($key,self::queryArray($uri,$cast,$decode));
        $return = self::change(['query'=>self::buildQuery($query,$encode)],$uri);

        return $return;
    }


    // unsetsQuery
    // enlève plusieurs clés dans query
    // retourne l'uri
    final public static function unsetsQuery(array $keys,string $uri,bool $cast=true,bool $encode=false,bool $decode=false):string
    {
        $return = '';

        $query = Arr::unsets($keys,self::queryArray($uri,$cast,$decode));
        $return = self::change(['query'=>self::buildQuery($query,$encode)],$uri);

        return $return;
    }


    // fragment
    // retourne le fragment de l'uri
    final public static function fragment(string $uri,bool $decode=false):?string
    {
        return self::parseOne('fragment',$uri,$decode);
    }


    // changeFragment
    // change le fragment d'une uri
    final public static function changeFragment($fragment,string $uri,bool $decode=false):string
    {
        return self::change(['fragment'=>$fragment],$uri,$decode);
    }


    // removeFragment
    // enlève le fragment d'une uri
    final public static function removeFragment(string $uri,bool $decode=false):string
    {
        return self::remove('fragment',$uri,$decode);
    }


    // schemeHost
    // retourne le scheme et host de l'uri
    final public static function schemeHost(string $uri,bool $decode=false):string
    {
        return self::keep(['scheme','user','pass','host','port'],$uri,$decode);
    }


    // schemeHostPath
    // retourne le scheme, domaine et path de l'uri
    final public static function schemeHostPath(string $uri,bool $decode=false):string
    {
        return self::keep(['scheme','user','pass','host','port','path'],$uri,$decode);
    }


    // hostPath
    // retourne le domaine et path de l'uri
    final public static function hostPath(string $uri,bool $decode=false):string
    {
        return self::keep(['host','port','path'],$uri,$decode);
    }


    // pathQuery
    // retourne le path et la query de l'uri
    final public static function pathQuery(string $uri,bool $decode=false):string
    {
        return self::keep(['path','query'],$uri,$decode);
    }


    // lang
    // retourne la lang de l'uri ou null si non existante
    final public static function lang(string $uri,bool $decode=false):?string
    {
        $return = null;
        $path = self::path($uri,$decode);

        if(!empty($path))
        $return = Path::lang($path);

        return $return;
    }


    // build
    // construit une uri à partir d'un tableau info
    // compliqué cette méthode
    final public static function build(array $parse,bool $decode=false):string
    {
        $return = '';
        $previous = null;
        $usernamePassword = null;
        $pathSlash = null;
        $pathOnlySlash = false;
        $slash = self::$config['build']['path'];

        foreach (self::$config['build'] as $k => $v)
        {
            if(array_key_exists($k,$parse))
            {
                $value = $parse[$k];

                if($k === 'query' && is_array($value))
                $value = self::buildQuery($value,false);

                if(is_scalar($value))
                {
                    if($k === 'port' && Http::isPort($value))
                    continue;

                    elseif($k === 'query' && empty($value))
                    continue;

                    $value = (string) $value;

                    if($k === 'scheme' && self::isSchemeProtocolRelative($value))
                    $value = self::scheme($value,$decode);

                    if(strlen($return))
                    {
                        if($previous === 'scheme')
                        $return .= self::$config['build']['scheme'];

                        if($k === 'host' && $usernamePassword === true)
                        $return .= self::$config['build']['host'];

                        if(strlen($value) && in_array($k,['path','query','fragment'],true) && $pathSlash !== true)
                        {
                            $return .= $slash;
                            $pathSlash = true;
                        }

                        if(!empty($v) && !in_array($k,['scheme','host','path'],true))
                        $return .= $v;
                    }

                    elseif($k === 'fragment' && strlen($value))
                    $return .= $v;

                    elseif(strlen($value) && in_array($k,['path','query','fragment'],true) && $pathSlash !== true)
                    {
                        $return .= self::$config['build']['path'];
                        $pathSlash = true;
                    }

                    if($k === 'path' && strlen($value) > 0 && $value[0] === '/')
                    {
                        if(strlen($return) && strlen($value) === 1)
                        $pathOnlySlash = true;

                        if(substr($return,-1) === '/')
                        $return = substr($return,0,-1);

                        elseif($pathOnlySlash === true)
                        $value = '';
                    }

                    $return .= $value;

                    if(in_array($k,['user','pass'],true))
                    $usernamePassword = true;

                    $previous = $k;
                }
            }
        }

        if($pathOnlySlash === true && strlen($return) > 1 && substr($return,-1) === '/')
        $return = substr($return,0,-1);

        return $return;
    }


    // rebuild
    // reconstruit une uri à partir d'une string
    final public static function rebuild(string $uri,bool $decode=false):?string
    {
        $return = null;
        $parse = self::parse($uri,$decode);

        if(is_array($parse))
        $return = self::build($parse,false);

        return $return;
    }


    // change
    // change une ou plusieurs partis d'une uri
    final public static function change(array $change,string $return,bool $decode=false):string
    {
        $parse = self::parse($return,$decode);

        if(!empty($change) && !empty($parse))
        {
            $parse = Arr::replace($parse,$change);
            $return = self::build($parse,false);
        }

        return $return;
    }


    // keep
    // garde une ou plusieurs partis d'une uri
    final public static function keep($keep,string $uri,bool $decode=false):string
    {
        $return = '';
        $parse = self::parse($uri,$decode);

        if(!empty($keep) && !empty($parse))
        {
            $keep = (array) $keep;

            foreach ($parse as $k => $v)
            {
                if(!in_array($k,$keep,true))
                $parse[$k] = null;
            }

            $return = self::build($parse,false);
        }

        return $return;
    }


    // remove
    // enlève une ou plusieurs partis d'une uri
    final public static function remove($remove,string $uri,bool $decode=false):string
    {
        $return = '';
        $parse = self::parse($uri,$decode);

        if(!empty($remove) && !empty($parse))
        {
            $remove = (array) $remove;

            foreach ($remove as $z)
            {
                if(array_key_exists($z,$parse))
                $parse[$z] = null;
            }

            $return = self::build($parse,false);
        }

        return $return;
    }


    // removeBefore
    // enlève des partis d'une uri avant celle donnée
    final public static function removeBefore(string $remove,string $uri,bool $decode=false):string
    {
        $return = '';
        $parse = self::parse($uri,$decode);

        if(!empty($remove) && !empty($parse))
        {
            $new = $parse;

            foreach ($parse as $k => $z)
            {
                if($k === $remove)
                {
                    $new = Arr::unsetBeforeKey($k,$parse);
                    break;
                }
            }

            $return = self::build($new,false);
        }

        return $return;
    }


    // removeAfter
    // enlève des partis d'une uri après celle donnée
    final public static function removeAfter(string $remove,string $uri,bool $decode=false):string
    {
        $return = '';
        $parse = self::parse($uri,$decode);

        if(!empty($remove) && !empty($parse))
        {
            foreach ($parse as $k => $z)
            {
                if($k === $remove)
                {
                    $parse = Arr::unsetAfterKey($k,$parse);
                    break;
                }
            }

            $return = self::build($parse,false);
        }

        return $return;
    }


    // combine
    // combine deux uri à un point de jonction
    final public static function combine(string $uri1,string $uri2,string $key='path',bool $decode=false):string
    {
        $return = '';

        if(!empty($key) && !empty(self::$config['build']))
        {
            $uri = [];
            $parse = [];
            $uri[0] = (array) self::parse($uri1,false);
            $uri[1] = (array) self::parse($uri2,false);

            $i = 0;
            foreach (self::$config['build'] as $k => $v)
            {
                if($k === $key)
                $i = 1;

                if(array_key_exists($k,$uri[$i]))
                $parse[$k] = $uri[$i][$k];
            }

            $return = self::build($parse,false);
        }

        return $return;
    }


    // redirection
    // permet de retourner une uri de redirection à partir d'un tableau
    // support pour le caractère * à la fin de clé
    final public static function redirection(string $uri,array $array):?string
    {
        $return = null;
        $char = self::$config['redirection'];

        if(!empty($uri) && !empty($array) && !empty($char))
        {
            foreach ($array as $key => $value)
            {
                if(is_string($key) && !empty($key) && is_string($value) && !empty($value))
                {
                    if(strpos($key,$char) === false && $uri === $key)
                    {
                        $return = $value;
                        break;
                    }

                    elseif(Str::isEnd($char,$key))
                    {
                        $key = Str::stripEnd($char,$key);
                        if(strpos($uri,$key) === 0)
                        {
                            $uri = Str::stripStart($key,$uri);

                            if(Str::isEnd($char,$value))
                            {
                                $value = Str::stripEnd($char,$value);
                                $return = $value.$uri;
                            }

                            else
                            $return = $value;
                        }
                    }
                }
            }
        }

        return $return;
    }


    // makeHostPort
    // génère une string avec un host et un port
    final public static function makeHostPort(string $host,int $port):string
    {
        return $host.':'.$port;
    }


    // getSchemeStatic
    // retourne le scheme à utiliser pour un host mis dans le tableau static
    final public static function getSchemeStatic(string $host):?string
    {
        $return = null;

        if(array_key_exists($host,self::$scheme))
        $return = Http::scheme(self::$scheme[$host]);

        return $return;
    }


    // getSchemeHostStatic
    // retourne le schemeHost à utiliser pour un host mis dans le tableau static
    final public static function getSchemeHostStatic(string $host):?string
    {
        $return = null;
        $scheme = self::getSchemeStatic($host);

        if(is_string($scheme))
        $return = self::changeScheme($scheme,$host);

        return $return;
    }


    // schemeStatic
    // fait un arr::replaceUnset sur le tableau des scheme
    // permet de retourner, ajouter, modifier et enlever des host/scheme en une méthode
    final public static function schemeStatic(?array $array=null):array
    {
        return (is_array($array))? (self::$scheme = Arr::cleanNull(Arr::replace(self::$scheme,$array))):self::$scheme;
    }


    // emptySchemeStatic
    // vide le tableau de schem static
    final public static function emptySchemeStatic():void
    {
        self::$scheme = [];

        return;
    }


    // setNotFound
    // lie une callable aux options
    // cette callable sera appelé si une uri existe pas
    final public static function setNotFound(?callable $callable):void
    {
        self::setOption('notFoundCallable',$callable);

        return;
    }


    // setAllAbsolute
    // permet de marquer que toutes les uris générés par output doivent être en absolutes
    final public static function setAllAbsolute(bool $value):void
    {
        self::$absolute = $value;

        return;
    }
}
?>