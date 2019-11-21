<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Base;

// cookie
// class with static methods to add and remove cookies
class Cookie extends Root
{
    // config
    public static $config = [
        'lifetime'=>3600, // durée de vie, 0 signifie fermeture du browser, a priorité sur expire car le timestamp courant est ajouté
        'expires'=>null, // expiration, 0 signifie fermeture du browser, le timestamp n'est pas additionné, a priorité sur lifetime
        'path'=>'/', // chemin dans le domaine
        'domain'=>'', // ce paramètre est étrange, le plus strict est de laisser domain comme chaîne vide
        'secure'=>null, // cookie doit être servis via https
        'httponly'=>true, // cookie ne peut pas être modifié dans javaScript
        'samesite'=>'Lax' // une requête post externe termine la session
    ];


    // is
    // retoune vrai si le cookie existe
    final public static function is($name):bool
    {
        return Superglobal::cookieExists($name);
    }


    // get
    // retourne la valeur du cookie
    final public static function get(string $name):?string
    {
        return Superglobal::getCookie($name);
    }


    // set
    // crée ou met à jour un cookie
    // si global est true, le cookie est ajouté dans la superglobale cookie
    final public static function set(string $name,string $value,?array $option=null):bool
    {
        $return = false;
        $option = static::option('set',$option);

        if(!empty($option) && !Response::areHeadersSent())
        $return = setcookie($name,$value,$option);

        return $return;
    }


    // unset
    // enlève un cookie
    // si global est true, le cookie est enlevé de la superglobale cookie
    final public static function unset(string $name,?array $option=null):bool
    {
        $return = false;
        $option = static::option('unset',$option);

        if(!empty($option) && !Response::areHeadersSent())
        $return = setcookie($name,'',$option);

        return $return;
    }


    // option
    // prépare le tableau option pour cookie
    final public static function option(string $mode,?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(static::$config,$option);

        if(in_array($mode,['set','unset','cookieParams'],true))
        {
            $time = Date::time();
            $return = $option;

            // expire et lifetime set
            if(in_array($mode,['set','cookieParams'],true))
            {
                if(is_int($return['expires']))
                {
                    if($return['expires'] > $time)
                    $return['lifetime'] = $return['expires'] - $time;
                    else
                    $return['lifetime'] = 0;
                }

                elseif(is_int($return['lifetime']))
                $return['expires'] = $time + $return['lifetime'];

                if(!is_int($return['expires']))
                $return['expires'] = 0;

                if(!is_int($return['lifetime']))
                $return['lifetime'] = 0;

                if($mode === 'set')
                unset($return['lifetime']);
                else
                unset($return['expires']);
            }

            // expire et lifetime unset
            elseif($mode === 'unset')
            {
                $return['expires'] = $time - 3600;
                unset($return['lifetime']);
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

            // samesite
            if(!in_array($return['samesite'],['Lax','Strict'],true))
            $return['samesite'] = '';
        }

        return $return;
    }
}
?>