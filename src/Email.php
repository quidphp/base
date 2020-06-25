<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// email
// class with methods a layer over the native PHP send_mail function, handles mail headers
final class Email extends Root
{
    // config
    protected static array $config = [
        'active'=>true, // permet d'activer ou non l'envoie d'email
        'message'=>[ // contenu par défaut pour un tableau message
            'priority'=>null,
            'xmailer'=>[Server::class,'quidName'],
            'mimeVersion'=>'1.0',
            'charset'=>'UTF-8',
            'contentType'=>'txt',
            'date'=>null,
            'to'=>null,
            'cc'=>null,
            'bcc'=>null,
            'replyTo'=>null,
            'subject'=>null,
            'body'=>null,
            'from'=>null,
            'header'=>null],
        'contact'=>['to','cc','bcc','replyTo'], // champs contact qui supportent multiples addresses, from n'accepte que un
        'headers'=>[
            'default'=>[], // headers par défaut à ajouter à chaque message
            'message'=>[ // nom des headers pour le champ additional_headers
                'mimeVersion'=>'MIME-Version',
                'priority'=>'X-Priority',
                'xmailer'=>'X-Mailer',
                'contentTypeCharset'=>'Content-Type',
                'date'=>'Date',
                'cc'=>'Cc',
                'bcc'=>'Bcc',
                'replyTo'=>'Reply-To',
                'from'=>'From']],
        'test'=>[ // contenu par défaut pour un message test
            'destination'=>[
                'to'=>null,
                'from'=>null,
                'cc'=>null,
                'bcc'=>null],
            'message'=>[
                'subject'=>'Test',
                'body'=>'Test']],
        'contentType'=>[ // différents contentType supportés, supporte le remplacement par clé
            1=>'text/plain',
            2=>'text/html'],
    ];


    // is
    // retourne vrai si la valeur donné est un courriel
    // strpos sur un slash car utilisé par base/attr, pour accélérer
    final public static function is($value):bool
    {
        return (is_string($value) && strpos($value,'/') === false)? Validate::isEmail($value):false;
    }


    // isActive
    // retourne vrai si l'envoie de courriel est activé
    final public static function isActive():bool
    {
        return self::$config['active'] === true;
    }


    // arr
    // explode une adrese courriel et retourne le nom et host
    final public static function arr(string $value):?array
    {
        $return = null;

        if(self::is($value))
        {
            $explode = explode('@',$value);
            if(count($explode) === 2)
            $return = ['name'=>$explode[0],'host'=>$explode[1]];
        }

        return $return;
    }


    // name
    // retourne seulement le nom du courriel (avant le @)
    final public static function name(string $value):?string
    {
        $return = null;
        $arr = self::arr($value);

        if(!empty($arr))
        $return = $arr['name'];

        return $return;
    }


    // host
    // retourne seulement l'hôte du courriel (après le @)
    final public static function host(string $value):?string
    {
        $return = null;
        $arr = self::arr($value);

        if(!empty($arr))
        $return = $arr['host'];

        return $return;
    }


    // send
    // permet d'envoyer un courriel à partir d'un tableau message
    // to peut avoir plusieurs destinataires
    // si l'envoie de courriel est désactivé globalement, retourne true comme si le message avait été bien envoyé
    final public static function send(array $value):bool
    {
        $return = false;
        $message = self::prepareMessage($value);

        if(!empty($message))
        {
            $mb = Encoding::isCharsetMb($message['charset']);
            $to = self::prepareAddress($message['to']);
            $subject = $message['subject'];
            $body = $message['body'];
            $headers = Header::str($message['header']);

            if(self::isActive())
            {
                if($mb === true)
                $return = mb_send_mail($to,$subject,$body,$headers);
                else
                $return = mail($to,$subject,$body,$headers);
            }

            else
            $return = true;
        }

        return $return;
    }


    // sendTest
    // permet d'envoyer un courriel test
    final public static function sendTest(?array $value=null):bool
    {
        return self::send(self::prepareTestMessage($value));
    }


    // sendLoop
    // permet d'envoyer plusieurs messages à partir d'un tableau multidimensionnel
    final public static function sendLoop(array $values):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            if(is_array($value))
            $return[$key] = self::send($value);
        }

        return $return;
    }


    // prepareMessage
    // reformatte un tableau de message
    // les champs contact peuvent recevoir multiples destinataires, sauf from qui n'accepte que un
    // retourne le tableau ou null s'il n'y pas to, subject, body, from et un contentType
    final public static function prepareMessage(array $value,bool $headerMessage=true):?array
    {
        $return = null;
        $value = Obj::cast($value);
        $message = Call::loop(self::$config['message']);
        $value = Arr::replace($message,$value);
        $value['from'] = self::address($value['from']);
        $value['date'] = (is_int($value['date']))? $value['date']:Datetime::now();
        $value = Arr::replace($value,self::prepareContentTypeCharset($value['contentType'],$value['charset']));

        foreach (self::$config['contact'] as $v)
        {
            if(!empty($value[$v]))
            $value[$v] = self::addresses($value[$v]);
        }

        if(in_array($value['contentType'],self::$config['contentType'],true) && !empty($value['charset']) && is_string($value['charset']))
        {
            if(!empty($value['to']) && !empty($value['from']) && is_string($value['subject']) && is_string($value['body']))
            {
                $return = $value;
                $return['header'] = self::prepareHeader($return,$headerMessage);
            }
        }

        return $return;
    }


    // prepareTestMessage
    // prépare un tableau message test
    // destination de config a priorité sur tout
    final public static function prepareTestMessage(?array $value=null)
    {
        return Arr::replace(self::$config['test']['message'],$value,self::$config['test']['destination']);
    }


    // prepareContentTypeCharset
    // prépare le contentType, le charset et le contentTypeCharset
    // est utilisé dans prepareMessage
    final public static function prepareContentTypeCharset($value=null,?string $charset=null):array
    {
        $return = [];
        $charset ??= Encoding::getCharset();
        $contentType = 'txt';
        $contentTypes = self::$config['contentType'];
        $showCharset = Encoding::isCharsetMb($charset);

        if(!empty($value))
        {
            if(is_int($value) && array_key_exists($value,$contentTypes))
            $contentType = $contentTypes[$value];

            elseif(is_string($value))
            $contentType = $value;
        }

        $return['charset'] = $charset;
        $return['contentType'] = Header::prepareContentType($contentType,false);
        $return['contentTypeCharset'] = Header::prepareContentType($contentType,$showCharset);

        return $return;
    }


    // prepareHeader
    // prepare le tableau d'en-tête
    // le tableau value doit avoir été préparé au préalable à partir de la méthode prepareMessage
    final public static function prepareHeader(array $value,bool $headerMessage=true):array
    {
        $return = [];

        if(!empty(self::$config['headers']['default']))
        $return = self::$config['headers']['default'];

        if(!empty($value['header']) && is_array($value['header']))
        $return = Arr::replace($return,$value['header']);

        if($headerMessage === true)
        {
            foreach (self::$config['headers']['message'] as $k => $v)
            {
                if(array_key_exists($k,$value) && !empty($value[$k]))
                {
                    if(in_array($k,self::$config['contact'],true) && is_array($value[$k]))
                    $return[$v] = self::prepareAddress($value[$k],true);

                    elseif($k === 'from')
                    $return[$v] = self::prepareAddress($value[$k],false);

                    elseif($k === 'date')
                    $return[$v] = Datetime::rfc822($value[$k]);

                    elseif(is_scalar($value[$k]))
                    $return[$v] = $value[$k];
                }
            }
        }

        $return = Header::arr($return);

        return $return;
    }


    // prepareAddress
    // prépare une string avec une ou plusieurs adresses
    // les addresses doivent avoir été préparés au préalable via la méthode adresses
    // si multi est false, seule la première adresse sera retournée
    final public static function prepareAddress(array $values,bool $multi=true):string
    {
        $return = '';

        if(Arr::isUni($values))
        $values = [$values];

        foreach ($values as $value)
        {
            if(is_array($value) && array_key_exists('email',$value) && is_string($value['email']) && array_key_exists('name',$value))
            {
                $string = self::addressStr($value['email'],$value['name']);

                if(!empty($string))
                {
                    if(!empty($return))
                    $return .= ', ';

                    $return .= $string;

                    if($multi === false)
                    break;
                }
            }
        }

        return $return;
    }


    // addresses
    // prépare plusieurs adresses pour les champs compatibles avec mulitples destinaires
    // compatible avec un maximum de format input
    // retourne un tableau multidimensionnel
    final public static function addresses($values):array
    {
        $return = [];

        if(!is_array($values))
        $values = [$values];

        if(array_key_exists('email',$values))
        {
            $email = $values['email'];
            unset($values['email']);

            if(array_key_exists('name',$values))
            {
                $name = $values['name'];
                unset($values['name']);
                $values[$email] = $name;
            }

            else
            $values[] = $email;
        }

        foreach ($values as $key => $value)
        {
            if(is_string($key))
            $value = [$key=>$value];

            $prepare = self::address($value);

            if(!empty($prepare))
            $return[] = $prepare;
        }

        return $return;
    }


    // address
    // prépare une adresse pour qu'elle soit compatible avec la méthode d'envoie courriel
    // compatible avec un maximum de format input
    // si le nom est null, retourne le nom à partir du courriel
    // retourne un tableau unidimensionnel ou null
    final public static function address($value)
    {
        $return = null;

        if(is_string($value))
        $value = [$value=>null];

        if(is_array($value))
        {
            $email = null;
            $name = null;

            if(array_key_exists('email',$value))
            {
                $email = $value['email'];

                if(array_key_exists('name',$value) && is_string($value['name']))
                $name = $value['name'];
            }

            else
            {
                $k = key($value);
                $v = current($value);

                if(is_numeric($k) && is_string($v))
                $email = $v;

                elseif(is_string($k))
                {
                    $email = $k;

                    if(is_string($v))
                    $name = $v;
                }
            }

            if(self::is($email))
            {
                $name = ($name === null)? self::name($email):$name;
                $return = ['email'=>$email,'name'=>$name];
            }
        }

        return $return;
    }


    // addressStr
    // génère une string avec arguments email et nom
    final public static function addressStr(string $email,$name=null):string
    {
        $return = trim($email);

        if(is_string($name))
        $return = trim($name).' <'.$return.'>';

        return $return;
    }


    // setTestTo
    // permet d'attribuer un to pour les courriels test
    // si value est true, utilise le email lié au serveur
    final public static function setTestTo($value):void
    {
        if($value === true)
        $value = Server::email();

        if(!empty($value))
        self::$config['test']['destination']['to'] = $value;
    }


    // setActive
    // active ou désactive l'envoie de courriel globalement
    final public static function setActive(bool $value=true):void
    {
        self::$config['active'] = $value;
    }
}
?>