<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// crypt
// class which contains methods to deal with the most common PHP cryptography functions
final class Crypt extends Root
{
    // config
    protected static array $config = [
        'passwordHash'=>[ // configuration pour password_hash et password_verify
            'algo'=>PASSWORD_DEFAULT,
            'options'=>['cost'=>11]],
        'passwordNew'=>10, // longueur d'un nouveau mot de passe
        'openssl'=>[ // configuration pour encrypt/decrypt openssl
            'method'=>'AES-256-CBC',
            'multiplier'=>7843,
            'divider'=>747,
            'sha'=>256],
        'randomString'=>[
            'alphanumeric'=>'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyWwZz0123456789', // caractère possible pour alphanumeric
            'alpha'=>'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyWwZz', // caractère possible pour alpha
            'alphaUpper'=>'ABCDEFGHIJKLMNOPQRSTUVXYWZ', // caractère possible pour alphaUpper
            'alphaLower'=>'abcdefghijklmnopqrstuvxywz'] // caractère possible pour alphaLower
    ];


    // passwordInfo
    // retourne les informations sur la variable hash
    final public static function passwordInfo(string $hash):array
    {
        return password_get_info($hash);
    }


    // passwordHash
    // hash une string avec password_pash
    // cette méthode cast les scalaires en string, pouvait crée des problèmes avec les données cast provenant de post
    final public static function passwordHash(string $value,?array $option=null):?string
    {
        $return = null;
        $option = Arr::plus(self::$config['passwordHash'],$option);
        $hash = password_hash($value,$option['algo'],$option['options']);

        if(is_string($hash))
        $return = $hash;

        return $return;
    }


    // passwordVerify
    // verifie le mot de passe avec password_verify
    final public static function passwordVerify(string $value,string $hash):bool
    {
        return password_verify($value,$hash);
    }


    // passwordNeedsRehash
    // retourne vrai si le hash n'est pas compatible avec l'algorythme et les options
    // de même, seul un password qui passe validate/isPassword est considéré
    final public static function passwordNeedsRehash(string $value,?array $option=null):bool
    {
        $return = false;
        $option = Arr::plus(self::$config['passwordHash'],$option);
        $return = password_needs_rehash($value,$option['algo'],$option['options']);

        return $return;
    }


    // passwordNew
    // crée un nouveau mot de passe qui sera comptable avec la méthode validate/isPassword
    // longueur minimum de 5, le password aura toujours au moins 2 chiffres
    // utiliser lors du reset des mots de passe
    final public static function passwordNew(?int $length=null):?string
    {
        $return = null;
        $length ??= self::$config['passwordNew'] ?? null;

        if(is_int($length) && $length > 4)
        {
            $return = self::randomString($length - 2,'alpha');
            $return .= (string) self::randomInt(2);
        }

        return $return;
    }


    // passwordValidate
    // retourne les messages négatifs en lien avec un changement de mot de passe
    // newPasswordConfirm, oldPassword et oldPasswordHash sont facultatifs
    // ceci est utilisé dans la classe user pour changer le mot de passe
    final public static function passwordValidate(string $newPassword,?string $newPasswordConfirm=null,?string $oldPassword=null,?string $oldPasswordHash=null,?string $security=null,?array $option=null):?string
    {
        $return = null;

        if(empty($newPassword))
        $return = 'invalidValues';

        elseif(!Validate::isPassword($newPassword,$security))
        $return = 'invalidPassword';

        elseif(is_string($newPasswordConfirm) && $newPassword !== $newPasswordConfirm)
        $return = 'newPasswordMismatch';

        elseif(is_string($oldPasswordHash) && !empty($oldPasswordHash) && !self::passwordNeedsRehash($oldPasswordHash,$option) && self::passwordVerify($newPassword,$oldPasswordHash,$option))
        $return = 'noChange';

        elseif(is_string($oldPassword))
        {
            if(!Validate::isPassword($oldPassword,$security))
            $return = 'invalidOldPassword';

            elseif($newPassword === $oldPassword)
            $return = 'noChange';

            elseif(is_string($oldPasswordHash) && !empty($oldPasswordHash) && !self::passwordVerify($oldPassword,$oldPasswordHash))
            $return = 'oldPasswordMismatch';
        }

        return $return;
    }


    // passwordActivate
    // retourne une string sha1, tel qu'utilisé pour générer la hash d'activation d'un password
    final public static function passwordActivate(string $value):string
    {
        return self::sha($value,1);
    }


    // passwordAlgos
    // retourne la liste des algorythmes de hash pour mot de passe
    final public static function passwordAlgos():array
    {
        return password_algos();
    }


    // md5
    // retourne un hash md5
    final public static function md5(string $value,bool $binary=false):string
    {
        return md5($value,$binary);
    }


    // hash
    // retourne un hash
    final public static function hash(string $algo,string $value,bool $binary=false):string
    {
        return hash($algo,$value,$binary);
    }


    // sha
    // retourne un hash sha
    final public static function sha(string $value,int $type=256,bool $binary=false):string
    {
        return self::hash('sha'.$type,$value,$binary);
    }


    // hashHmac
    // retourne un hash avec une clé
    final public static function hashHmac(string $algo,string $value,string $key,bool $binary=false):string
    {
        return hash_hmac($algo,$value,$key,$binary);
    }


    // randomBytes
    // à partir d'une fonction CSPRNG
    final public static function randomBytes(int $length=11):string
    {
        return random_bytes($length);
    }


    // randomBool
    // à partir d'une fonction CSPRNG
    final public static function randomBool(int $min=0,int $max=1):bool
    {
        $random = random_int($min,$max);
        return $random === $min;
    }


    // randomInt
    // à partir d'une fonction CSPRNG
    final public static function randomInt(?int $length=null,int $min=0,int $max=PHP_INT_MAX):int
    {
        $return = random_int($min,$max);

        if(is_int($length))
        $return = Num::sub(0,$length,$return);

        return $return;
    }


    // randomString
    // génère une string random à partir d'une chaîne de caracètre possible
    // random généré à partir d'une fonction CSPRNG
    final public static function randomString(int $length=40,?string $random=null,?bool $mb=null):string
    {
        $return = '';
        $random = self::getRandomString($random);

        if($length > 0 && is_string($random) && !empty($random))
        {
            $counter = 0;
            $split = Str::split(1,$random,$mb);
            $count = count($split);

            if($count > 0)
            {
                $max = $count - 1;

                while ($counter < $length)
                {
                    $key = random_int(0,$max);

                    if(array_key_exists($key,$split))
                    {
                        $return .= $split[$key];
                        $counter++;
                    }

                    else
                    break;
                }
            }
        }

        return $return;
    }


    // getRandomString
    // retourne les chars pour la méthode randomString
    final public static function getRandomString(?string $value=null):?string
    {
        $return = null;

        if(is_string($value))
        {
            if(array_key_exists($value,self::$config['randomString']))
            $return = self::$config['randomString'][$value];

            else
            $return = $value;
        }

        elseif($value === null)
        $return = current(self::$config['randomString']);

        return $return;
    }


    // randomArray
    // retourne une ou plusieurs slices d'un array de façon random
    // random généré à partir d'une fonction CSPRNG
    final public static function randomArray(array $array,int $length=1):array
    {
        $return = [];

        for ($i=0; $i < $length; $i++)
        {
            $keys = array_keys($array);
            $values = array_values($array);
            $count = count($keys);

            if($count > 0)
            {
                $max = $count - 1;

                $index = random_int(0,$max);
                $key = $keys[$index];
                $value = $values[$index];

                $return[$key] = $value;
                unset($array[$key]);
            }

            else
            break;
        }

        return $return;
    }


    // microtime
    // retourne une string unique à partir de uniqid qui se base sur microtime
    // 13 caractères si moreEntropy est false, 23 si true
    final public static function microtime($value=null,bool $moreEntropy=false):?string
    {
        $return = null;
        $prefix = '';

        if(is_numeric($value) && $value > 0)
        $prefix = self::randomString($value);

        elseif(is_string($value))
        $prefix = $value;

        if(is_string($prefix))
        $return = uniqid($prefix,$moreEntropy);

        return $return;
    }


    // base64
    // encode une chaîne en base64
    final public static function base64(string $value):string
    {
        return base64_encode($value);
    }


    // base64Decode
    // décode une chaîne présentement en base64
    final public static function base64Decode(string $value,bool $strict=false):string
    {
        return base64_decode($value,$strict);
    }


    // openssl
    // encrypte une chaîne à partir de openssl
    // possible de décrypter la chaîne
    final public static function openssl(string $value,string $key,string $iv=''):?string
    {
        $return = null;

        if(!empty($value) && !empty($key))
        {
            $key .= self::openSslKey($key);
            $method = self::$config['openssl']['method'];
            $key = self::sha($key,self::$config['openssl']['sha']);
            $iv = self::sha($iv,self::$config['openssl']['sha']);
            $iv = substr($iv,0,16);
            $ssl = openssl_encrypt($value,$method,$key,0,$iv);
            $encode = self::base64($ssl);

            if(is_string($encode))
            $return = $encode;
        }

        return $return;
    }


    // opensslDecrypt
    // décrypte une chaîne à partir de openssl
    final public static function opensslDecrypt(string $value,string $key,string $iv=''):?string
    {
        $return = null;

        if(!empty($value) && !empty($key))
        {
            $key .= self::openSslKey($key);
            $method = self::$config['openssl']['method'];
            $key = self::sha($key,self::$config['openssl']['sha']);
            $iv = self::sha($iv,self::$config['openssl']['sha']);
            $iv = substr($iv,0,16);
            $decode = self::base64Decode($value);
            $ssl = openssl_decrypt($decode,$method,$key,0,$iv);

            if(is_string($ssl))
            $return = $ssl;
        }

        return $return;
    }


    // openSslKey
    // génère la clé ssl secrète pour openssl et opensslDecrypt
    protected static function openSslKey(string $return):string
    {
        $return .= (strlen($return) * static::$config['openssl']['multiplier']);
        $return .= (strlen($return) / static::$config['openssl']['divider']);

        return $return;
    }


    // serialize
    // serialize une variable, retourne une string
    final public static function serialize($value):string
    {
        return serialize($value);
    }


    // unserialize
    // unserialize une string, retourne la variable
    // allowed classes permet de spécifier les classes de retour acceptés si la valeur contient des objets
    final public static function unserialize(string $value,$allowedClasses=true)
    {
        $return = null;
        $option = ['allowed_classes'=>($allowedClasses === false)? $allowedClasses:true];

        if(is_object($allowedClasses))
        $allowedClasses = $allowedClasses::class;

        if(is_string($allowedClasses))
        $allowedClasses = [$allowedClasses];

        if(is_array($allowedClasses))
        {
            $allowed = [];
            foreach ($allowedClasses as $v)
            {
                $v = Fqcn::str($v);

                if(!empty($v))
                $allowed[] = $v;
            }

            $option['allowed_classes'] = $allowed;
        }

        $return = unserialize($value,$option);

        return $return;
    }
}
?>