<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// curl
// class with basic logics for managing requests via curl
final class Curl extends Root
{
    // config
    protected static array $config = [
        'method'=>null,
        'timeout'=>10,
        'dnsGlobalCache'=>false,
        'userPassword'=>null,
        'proxyHost'=>null,
        'proxyPort'=>null,
        'proxyPassword'=>null,
        'followLocation'=>false,
        'ssl'=>null,
        'port'=>null,
        'sslCipher'=>null,
        'userAgent'=>null,
        'postJson'=>false
    ];


    // is
    // retourne vrai si la resource (php7) ou l'objet (php8) est de type curl
    final public static function is($value):bool
    {
        $return = false;

        if(is_resource($value) && Res::type($value) === 'curl')
        $return = true;

        elseif(is_a($value,\CurlHandle::class,true))
        $return = true;

        return $return;
    }


    // open
    // ouvre une resource ou objet curl
    final public static function open(?string $value=null)
    {
        return (is_string($value))? curl_init($value):curl_init();
    }


    // close
    // ferme la resource curl, n'a pas d'effet en php8
    final public static function close($value):void
    {
        if(self::is($value))
        curl_close($value);
    }


    // meta
    // retourne les meta données de curl
    final public static function meta($value):?array
    {
        $return = null;

        if(self::is($value))
        {
            $return = [];
            $info = self::info($value);

            if(!empty($info['url']))
            $return['uri'] = $info['url'];

            $return['error'] = curl_error($value);
            $return['errorNo'] = curl_errno($value);
            $return['info'] = $info;
        }

        return $return;
    }


    // info
    // retourne le tableau curl info
    final public static function info($value):?array
    {
        return (self::is($value))? curl_getinfo($value):null;
    }


    // make
    // crée et retourne une resource curl
    // n'envoie pas la requête
    final public static function make(string $value,bool $exec=false,$post=null,$header=null,?array $option=null)
    {
        $return = self::open();
        $option = Arr::plus(self::$config,$option);

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
            $option['ssl'] = (Uri::isSsl($value));

            if($option['ssl'] === true)
            {
                $verifyPeer = 0;
                $verifyHost = 2;

                if(Server::allowSelfSignedCertificate())
                {
                    $verifyPeer = false;
                    $verifyHost = false;
                }

                curl_setopt($return,CURLOPT_SSL_VERIFYPEER,$verifyPeer);
                curl_setopt($return,CURLOPT_SSL_VERIFYHOST,$verifyHost);
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

            // method
            if(is_string($option['method']) && !empty($option['method']))
            curl_setopt($return,CURLOPT_CUSTOMREQUEST,strtoupper($option['method']));

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
                $header = Header::list($header);
                curl_setopt($return,CURLOPT_HTTPHEADER,$header);
            }

            if($exec === true)
            $return = self::exec($return);
        }

        return $return;
    }


    // exec
    // éxécute la requête curl
    // retourne un tableau avec code, contentType, basename, meta, header et resource si c'est bien une requête http
    // la resource est une resource php temporaire
    final public static function exec($value,bool $close=true):?array
    {
        $return = null;

        if(self::is($value))
        {
            $return = ['code'=>null,'contentType'=>null,'basename'=>null,'meta'=>null,'header'=>null,'resource'=>null];
            $exec = curl_exec($value);
            $meta = self::meta($value);
            $uri = $meta['uri'] ?? '';
            $basename = Uri::basename($uri);

            $return['meta'] = $meta;
            $return['uri'] = $uri;
            $return['basename'] = $basename;

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
                            $resource = Res::temp($contentType,$basename,['write'=>$write]);

                            if(!empty($resource) && Res::write($body,$resource))
                            $return['resource'] = $resource;
                        }
                    }
                }
            }

            if($close === true)
            self::close($value);
        }

        return $return;
    }
}
?>