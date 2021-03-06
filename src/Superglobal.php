<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// superglobal
// class with static methods to deal with superglobal variables
final class Superglobal extends Root
{
    // config
    protected static array $config = [
        'postKeys'=>['MAX_FILE_SIZE'] // clés post pouvant être enlevés
    ];


    // hasSession
    // retourne vrai si la superglobale session est déclarée
    final public static function hasSession():bool
    {
        return isset($_SESSION);
    }


    // getExists
    // vérifie l'existence d'une clé dans la superglobale get
    final public static function getExists($key):bool
    {
        return Arrs::keyExists($key,self::get());
    }


    // getsExists
    // vérifie l'existence de plusieurs clés dans la superglobale get
    final public static function getsExists(array $keys):bool
    {
        return Arrs::keysExists($keys,self::get());
    }


    // postExists
    // vérifie l'existence d'une clé dans la superglobale post
    final public static function postExists($key):bool
    {
        return Arrs::keyExists($key,self::post());
    }


    // postsExists
    // vérifie l'existence de plusieurs clés dans la superglobale post
    final public static function postsExists(array $keys):bool
    {
        return Arrs::keysExists($keys,self::post());
    }


    // cookieExists
    // vérifie l'existence d'une clé dans la superglobale cookie
    final public static function cookieExists(string $key):bool
    {
        return Arrs::keyExists($key,self::cookie());
    }


    // sessionExists
    // vérifie l'existence d'une clé dans la superglobale session si démarré
    final public static function sessionExists($key):bool
    {
        return self::hasSession() && Arrs::keyExists($key,self::session());
    }


    // sessionsExists
    // vérifie l'existence de plusieurs clés dans la superglobale session si démarré
    final public static function sessionsExists(array $keys):bool
    {
        return self::hasSession() && Arrs::keysExists($keys,self::session());
    }


    // fileExists
    // vérifie l'existence d'une clé dans la superglobale files
    final public static function fileExists(string $key):bool
    {
        return Arrs::keyExists($key,self::files());
    }


    // envExists
    // vérifie l'existence d'une clé dans la superglobale env
    final public static function envExists(string $key):bool
    {
        return Arrs::keyExists($key,self::env());
    }


    // requestExists
    // vérifie l'existence d'une clé dans la superglobale request
    final public static function requestExists($key):bool
    {
        return Arrs::keyExists($key,self::request());
    }


    // serverExists
    // vérifier l'existence d'une clé dans la superglobale superglobale
    // possibilité d'une recherche insensible à la case
    final public static function serverExists(string $key,bool $sensitive=true):bool
    {
        return Arrs::keyExists($key,self::server(),$sensitive);
    }


    // hasServerLength
    // retourne vrai si le tableau serveur a la clé content_length de spécifié
    final public static function hasServerLength():bool
    {
        return is_numeric(self::getServer('CONTENT_LENGTH'));
    }


    // hasServerLengthWithoutPost
    // retourne vrai si le tableau serveur a la clé content_length de spécifié et que le tableau post est vide
    // ceci signifie normalement un chargement de fichier qui a échoué
    final public static function hasServerLengthWithoutPost():bool
    {
        return self::hasServerLength() && empty(self::post());
    }


    // get
    // retourne la superglobale get
    // retourne une référence
    final public static function &get():array
    {
        return $_GET;
    }


    // post
    // retourne la superglobale post
    // retourne une référence
    final public static function &post():array
    {
        return $_POST;
    }


    // cookie
    // retourne la superglobale cookie
    // retourne une référence
    final public static function &cookie():array
    {
        return $_COOKIE;
    }


    // session
    // retourne la superglobale session si démarré
    // peut retourner null
    // retourne une référence
    final public static function &session():?array
    {
        $return = null;

        if(self::hasSession())
        $return =& $_SESSION;

        return $return;
    }


    // files
    // retourne la superglobale files
    // retourne une référence
    final public static function &files():array
    {
        return $_FILES;
    }


    // env
    // retourne la superglobale env
    // retourne une référence
    final public static function &env():array
    {
        return $_ENV;
    }


    // request
    // retourne la superglobale request
    // retourne une référence
    final public static function &request():array
    {
        return $_REQUEST;
    }


    // server
    // retourne la superglobale server
    // retourne une référence
    final public static function &server():array
    {
        return $_SERVER;
    }


    // getGet
    // retourne une variable dans la superglobale get
    final public static function getGet($key)
    {
        return Arrs::get($key,self::get());
    }


    // getPost
    // retourne une variable dans la superglobale post
    final public static function getPost($key)
    {
        return Arrs::get($key,self::post());
    }


    // getCookie
    // retourne une variable dans la superglobale cookie
    final public static function getCookie(string $key)
    {
        return Arrs::get($key,self::cookie());
    }


    // getSession
    // retourne une variable dans la superglobale session si démarré
    // peut retourner null
    final public static function getSession($key)
    {
        return (self::hasSession())? Arrs::get($key,self::session()):null;
    }


    // getFiles
    // retourne une variable dans la superglobale files
    final public static function getFiles(string $key)
    {
        return Arrs::get($key,self::files());
    }


    // getEnv
    // retourne une variable dans la superglobale env
    final public static function getEnv(string $key)
    {
        return Arrs::get($key,self::env());
    }


    // getRequest
    // retourne une variable dans la superglobale request
    final public static function getRequest($key)
    {
        return Arrs::get($key,self::request());
    }


    // getServer
    // retourne une variable dans la superglobale server
    // possibilité d'une recherche insensible à la case
    final public static function getServer(string $key,bool $sensitive=true)
    {
        return Arrs::get($key,self::server(),$sensitive);
    }


    // getServerStart
    // retourne toutes les clés du tableau serveur commençant par la clé fourni
    // possible de faire une recherche de clé de façon insensible à la case
    // possible d'envoyer le retour dans la méthode format
    final public static function getServerStart(string $key,bool $sensitive=true,bool $format=false):array
    {
        $return = [];

        foreach (self::server() as $k => $v)
        {
            if(($sensitive === true && strpos($k,$key) === 0) || ($sensitive === false && stripos($k,$key) === 0))
            $return[$k] = $v;
        }

        if($format === true && is_array($return))
        $return = self::reformatServer($return);

        return $return;
    }


    // getServerHeader
    // retourne les headers du tableau server
    final public static function getServerHeader(bool $format=false):array
    {
        return self::getServerStart('HTTP_',true,$format);
    }


    // reformatServer
    // reformate un tableau comme $_SERVER
    // divise par _ et ensuite enlève la première partie et implode avec le séparateur -
    // la clé est ramener en strtolower et ucfirst
    final public static function reformatServer(array $array):array
    {
        $return = [];

        if(!empty($array))
        {
            foreach ($array as $key => $value)
            {
                $explode = explode('_',$key);
                if(is_array($explode))
                {
                    if(count($explode) > 1)
                    array_shift($explode);
                    $explode = Arr::map($explode,fn($v) => strtolower($v));
                    $explode = Arr::map($explode,fn($v) => ucfirst($v));
                    $key = implode('-',$explode);
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }


    // setGet
    // change la valeur d'une variable dans la superglobale get
    final public static function setGet($key,$value):void
    {
        Arrs::setRef($key,$value,$_GET);
    }


    // setPost
    // change la valeur d'une variable dans la superglobale post
    final public static function setPost($key,$value):void
    {
        Arrs::setRef($key,$value,$_POST);
    }


    // setCookie
    // change la valeur d'une variable dans la superglobale cookie
    final public static function setCookie(string $key,$value):void
    {
        Arrs::setRef($key,$value,$_COOKIE);
    }


    // setSession
    // change la valeur d'une variable dans la superglobale session si démarré
    // peut retourner null
    final public static function setSession($key,$value):void
    {
        if(self::hasSession())
        Arrs::setRef($key,$value,$_SESSION);
    }


    // setFiles
    // change la valeur d'une variable dans la superglobale files
    final public static function setFiles(string $key,$value):void
    {
        Arrs::setRef($key,$value,$_FILES);
    }


    // setEnv
    // change la valeur d'une variable dans la superglobale env
    final public static function setEnv(string $key,$value):void
    {
        Arrs::setRef($key,$value,$_ENV);
    }


    // setRequest
    // change la valeur d'une variable dans la superglobale request
    final public static function setRequest($key,$value):void
    {
        Arrs::setRef($key,$value,$_REQUEST);
    }


    // setServer
    // change la valeur d'une variable dans la superglobale server
    // possibilité d'une opération insensible à la case
    final public static function setServer(string $key,$value,bool $sensitive=true):void
    {
        Arrs::setRef($key,$value,$_SERVER,$sensitive);
    }


    // unsetGet
    // enlève une entrée dans le tableau superglobale get
    final public static function unsetGet($key):void
    {
        Arrs::unsetRef($key,$_GET);
    }


    // unsetPost
    // enlève une entrée dans le tableau superglobale post
    final public static function unsetPost($key):void
    {
        Arrs::unsetRef($key,$_POST);
    }


    // unsetCookie
    // enlève une entrée dans le tableau superglobale cookie
    final public static function unsetCookie(string $key):void
    {
        Arrs::unsetRef($key,$_COOKIE);
    }


    // unsetSession
    // enlève une entrée dans le tableau superglobale session si démarré
    // peut retourner null
    final public static function unsetSession($key):void
    {
        if(self::hasSession())
        Arrs::unsetRef($key,$_SESSION);
    }


    // unsetFiles
    // enlève une entrée dans le tableau superglobale files
    final public static function unsetFiles(string $key):void
    {
        Arrs::unsetRef($key,$_FILES);
    }


    // unsetEnv
    // enlève une entrée dans le tableau superglobale env
    final public static function unsetEnv(string $key):void
    {
        Arrs::unsetRef($key,$_ENV);
    }


    // unsetRequest
    // enlève une entrée dans le tableau superglobale request
    final public static function unsetRequest($key):void
    {
        Arrs::unsetRef($key,$_REQUEST);
    }


    // unsetServer
    // enlève une entrée dans le tableau superglobale server
    // possibilité d'une opération insensible à la case
    final public static function unsetServer(string $key,bool $sensitive=true):void
    {
        Arrs::unsetRef($key,$_SERVER,$sensitive);
    }


    // unsetServerStart
    // enlève toutes les clés du tableau serveur commençant par la valeur fourni
    // possible de faire une recherche de clé de façon insensible à la case
    final public static function unsetServerStart(string $key,bool $sensitive=true):void
    {
        $values = self::getServerStart($key,$sensitive,false);

        if(!empty($values))
        Arrs::unsetsRef(array_keys($values),$_SERVER,$sensitive);
    }


    // unsetServerHeader
    // enlève tous les headers du tableau serveur
    final public static function unsetServerHeader():void
    {
        self::unsetServerStart('HTTP_');
    }


    // formatServerKey
    // format une chaîne dans le format du tableau serveur
    final public static function formatServerKey(string $return):string
    {
        return str_replace('-','_',strtoupper($return));
    }


    // postReformat
    // possibilité d'enlever les clés de post qui ne sont pas des noms de colonnes ou nom de clés réservés
    // possibilité d'enlever les tags html dans le tableau de retour
    // possibilité d'inclure les données chargés en provenance de files comme variable post
    // les données de files sont reformat par défaut, mais post a toujours précédente sur files
    // possible d'exclure les champs qui commencent par notStart
    final public static function postReformat(array $return,bool $safeKey=false,bool $stripTags=false,?string $notStart=null,bool $includeFiles=false,?array $files=null):array
    {
        if($safeKey === true)
        {
            $postKeys = self::$config['postKeys'];

            foreach ($return as $key => $value)
            {
                if(in_array($key,$postKeys,true) || !Validate::isCol($key))
                unset($return[$key]);
            }
        }

        if($stripTags === true)
        $return = Arrs::map($return,fn($v) => is_string($v)? strip_tags($v):$v);

        if(is_string($notStart))
        $return = Arr::filter($return,fn($value,$key) => !Str::isStart($notStart,$key));

        if($includeFiles === true)
        {
            $files ??= self::files();
            $files = self::filesReformat($files);
            $return = Arrs::replace($files,$return);
        }

        return $return;
    }


    // filesReformat
    // reformat un tableau en provenance de files, seulement s'il est multiple
    // retourne un tableau multidimensionnel si plusieurs fichiers
    // il est possible de passer un tableau déjà formatté dans cette fonction, celui-ci ne sera pas changé
    final public static function filesReformat(array $value):array
    {
        $return = [];

        foreach ($value as $key => $value)
        {
            if(is_string($key) && is_array($value))
            {
                if(Arrs::is($value) && Arr::isAssoc($value))
                $return[$key] = Column::keySwap($value);

                else
                $return[$key] = $value;
            }
        }

        return $return;
    }
}
?>