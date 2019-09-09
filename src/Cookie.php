<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// cookie
// class with static methods to add and remove cookies
class Cookie extends Root
{
    // config
    public static $config = [
        'lifetime'=>3600, // durée de vie, 0 signifie fermeture du browser, a priorité sur expire car le timestamp courant est ajouté
        'expire'=>null, // expiration, 0 signifie fermeture du browser, le timestamp n'est pas additionné, a priorité sur lifetime
        'path'=>'/', // chemin dans le domaine
        'domain'=>'', // ce paramètre est étrange, le plus strict est de laisser domain comme chaîne vide
        'secure'=>null, // cookie doit être servis via https
        'httponly'=>true // cookie ne peut pas être modifié dans javaScript
    ];


    // is
    // retoune vrai si le cookie existe
    public static function is($name):bool
    {
        return Superglobal::cookieExists($name);
    }


    // get
    // retourne la valeur du cookie
    public static function get(string $name):?string
    {
        return Superglobal::getCookie($name);
    }


    // set
    // crée ou met à jour un cookie
    // si global est true, le cookie est ajouté dans la superglobale cookie
    public static function set(string $name,string $value,?array $option=null):bool
    {
        $return = false;
        $option = static::option('set',$option);

        if(!empty($option) && !Response::areHeadersSent())
        $return = setcookie($name,$value,$option['expire'],$option['path'],$option['domain'],$option['secure'],$option['httponly']);

        return $return;
    }


    // unset
    // enlève un cookie
    // si global est true, le cookie est enlevé de la superglobale cookie
    public static function unset(string $name,?array $option=null):bool
    {
        $return = false;
        $option = static::option('unset',$option);

        if(!empty($option) && !Response::areHeadersSent())
        $return = setcookie($name,'',$option['expire'],$option['path'],$option['domain'],$option['secure'],$option['httponly']);

        return $return;
    }


    // option
    // prépare le tableau option pour cookie
    public static function option(string $mode,?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(static::$config,$option);
        $time = Date::time();

        if(in_array($mode,['set','unset'],true))
        {
            $return = $option;

            // expire et lifetime set
            if($mode === 'set')
            {
                if(is_int($return['expire']))
                {
                    if($return['expire'] > $time)
                    $return['lifetime'] = $return['expire'] - $time;
                    else
                    $return['lifetime'] = 0;
                }

                elseif(is_int($return['lifetime']))
                $return['expire'] = $time + $return['lifetime'];

                if(!is_int($return['expire']))
                $return['expire'] = 0;

                if(!is_int($return['lifetime']))
                $return['lifetime'] = 0;
            }

            // expire et lifetime unset
            elseif($mode === 'unset')
            {
                $return['lifetime'] = 0;
                $return['expire'] = $time - 3600;
            }

            // path
            if(!is_string($return['path']))
            $return['path'] = '/';

            // domain
            if($return['domain'] === true)
            $return['domain'] = Request::host();

            if(!is_string($return['domain']))
            $return['domain'] = '';

            // secure
            if(!is_bool($return['secure']))
            $return['secure'] = Request::isSsl();

            // httponly
            if(!is_bool($return['httponly']))
            $return['httponly'] = true;
        }

        return $return;
    }
}
?>