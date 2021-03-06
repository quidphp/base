<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// header
// class with static methods to work with HTTP headers
final class Header extends Listing
{
    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'implode'=>1, // index du séparateur à utiliser lors du implode
            'caseImplode'=>[self::class,'keyCase']], // les clés sont ramenés dans cette case lors du implode
        'separator'=>[ // les séparateurs de header
            ["\r\n","\r\n"],
            [':',': '],
            [';']], // séparateur pour content-type
        'sensitive'=>false, // la classe est sensible ou non à la case
        'status'=>'status' // clé réservé pour header status
    ];


    // isStatus
    // retourne vrai si la string header est une entrée status
    final public static function isStatus($value):bool
    {
        return is_string($value) && stripos($value,'HTTP/') === 0;
    }


    // is200
    // retourne vrai le code status dans le tableau header est 200
    final public static function is200($header):bool
    {
        return self::isCode(200,$header);
    }


    // isCodePositive
    // retourne vrai si le code de la réponse est positive (200 à 399)
    final public static function isCodePositive($header):bool
    {
        return self::isCodeBetween(200,399,$header);
    }


    // isCodeLoggable
    // retourne vrai si le code de la réponse est positive mais pas 301
    final public static function isCodeLoggable($header):bool
    {
        return !self::isCodePositive($header) || self::isCode(301,$header);
    }


    // isCodeError
    // retourne vrai si le code dans le tableau header est 400 ou 404
    final public static function isCodeError($header):bool
    {
        return self::isCodeIn(400,$header);
    }


    // isCodeServerError
    // retourne vrai si le code dans le tableau header est 500
    final public static function isCodeServerError($header):bool
    {
        return self::isCodeIn(500,$header);
    }


    // isCodeValid
    // retourne vrai si le code existe dans le tableau config
    final public static function isCodeValid($value):bool
    {
        return is_int($value) && array_key_exists($value,Lang\En::getConfig('header/responseStatus'));
    }


    // isStatusTextValid
    // retourne vrai si le texte de status existe dans le tableau config
    final public static function isStatusTextValid($value):bool
    {
        return is_string($value) && in_array($value,Lang\En::getConfig('header/responseStatus'),true);
    }


    // isHtml
    // retourne vrai si le tableau header a un content type html
    final public static function isHtml($header):bool
    {
        return self::isContentType('html',$header);
    }


    // isJson
    // retourne vrai si le tableau header a un content type json
    final public static function isJson($header):bool
    {
        return self::isContentType('json',$header);
    }


    // isXml
    // retourne vrai si le tableau header a un content type xml
    final public static function isXml($header):bool
    {
        return self::isContentType('xml',$header);
    }


    // hasStatus
    // retourne vrai si le tableau header a une entrée status
    final public static function hasStatus($header):bool
    {
        $return = false;

        if(is_array($header))
        {
            $statusKey = self::$config['status'];
            $assoc = self::arr($header);
            $return = (is_string($statusKey) && array_key_exists($statusKey,$assoc) && is_string($assoc[$statusKey]));
        }

        return $return;
    }


    // isCode
    // retourne vrai si le code du tableau header est un de ceux donnés en argument
    final public static function isCode($value,$header):bool
    {
        $return = false;

        if(is_array($header))
        {
            $code = self::code($header);

            if(is_int($code))
            {
                if(!is_array($value))
                $value = [$value];

                $return = Arr::some($value,fn($v) => is_numeric($v) && $code === (int) $v);
            }
        }

        return $return;
    }


    // isCodeBetween
    // retourne vrai si le code est entre les valeurs from et to
    final public static function isCodeBetween($from,$to,$header):bool
    {
        $return = false;

        if(is_array($header))
        {
            $code = self::code($header);
            $return = (is_int($from) && is_int($to) && $code >= $from && $code <= $to);
        }

        return $return;
    }


    // isCodeIn
    // retourne vrai si le code se trouve dans le groupe (la centaine) donné en argument
    final public static function isCodeIn($value,$header):bool
    {
        $return = false;

        if(is_int($value))
        {
            $from = (int) (floor($value / 100) * 100);
            $to = ($from + 99);
            $return = self::isCodeBetween($from,$to,$header);
        }

        return $return;
    }


    // isContentType
    // retourne vrai si le tableau header contient le content-type donné en argument
    // supporte le content type exact, le content type short et le mime
    final public static function isContentType($value,$header):bool
    {
        $return = false;

        if(is_string($value) && is_array($header))
        {
            $contentType = self::contentType($header,0);

            if(!empty($contentType))
            {
                if($value === $contentType)
                $return = true;

                else
                {
                    $parsedContentType = self::parseContentType($contentType,false);
                    $mimedContentType = self::parseContentType($contentType,true);
                    $return = (in_array($value,[$parsedContentType,$mimedContentType],true));
                }
            }
        }

        return $return;
    }


    // isHttp1
    // retourne vrai si le protocol est http1
    final public static function isHttp1($value):bool
    {
        return in_array(self::protocol($value),['HTTP/1','HTTP/1.0','HTTP/1.1'],true);
    }


    // isHttp2
    // retourne vrai si le protocol est http2
    final public static function isHttp2($value):bool
    {
        return in_array(self::protocol($value),['HTTP/2','HTTP/2.0'],true);
    }


    // keyCase
    // change la case d'une clé header
    final public static function keyCase(string $key):string
    {
        return ucwords(strtolower($key),'-');
    }


    // parse
    // parse un tableau header
    // la clé status est ramené au début, et il ne peut y en avoir qu'une
    // peut retourner un tableau multidimensionnel
    final public static function parse(array $array,array $option):array
    {
        $return = [];
        $separator = self::getSeparator(1,$option['explode']);
        $statusKey = self::$config['status'];

        foreach ($array as $key => $value)
        {
            if(is_numeric($key) && is_string($value))
            {
                if(!empty($statusKey) && self::isStatus($value))
                $key = $statusKey;

                else
                {
                    $keyValue = Str::explodeKeyValue($separator,$value,$option['trim'],$option['clean']);
                    if(!empty($keyValue))
                    {
                        $key = key($keyValue);
                        $value = current($keyValue);
                    }
                }
            }

            if(is_string($key) && (is_scalar($value) || Arr::isIndexed($value)))
            {
                if($key === $statusKey)
                {
                    if(array_key_exists($key,$return))
                    $return[$key] = $value;
                    else
                    $return = Arr::merge([$key=>$value],$return);
                }

                elseif(Arr::keyExists($key,$return,self::getSensitive()))
                {
                    $target = Arr::ikey($key,$return);

                    if(is_scalar($return[$target]))
                    $return[$target] = [$return[$target]];

                    if(is_array($return[$target]))
                    $return[$target] = Arr::merge($return[$target],$value);
                }

                else
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // parseStatus
    // parse un header status à partir d'une chaîne ou d'un tableau header
    // retourne un tableau à 3 entrées protocol, code et text
    final public static function parseStatus($value):?array
    {
        $return = null;

        if(is_string($value) || is_array($value))
        {
            $value = (array) $value;

            foreach ($value as $v)
            {
                if(is_string($v) && self::isStatus($v))
                {
                    $explode = Str::explodeTrim(' ',$v,3);

                    if(count($explode) >= 2 && is_numeric($explode[1]))
                    {
                        $return = [];
                        $return['protocol'] = strtoupper($explode[0]);
                        $return['code'] = (int) $explode[1];
                        $text = $explode[2] ?? self::statusTextFromCode($return['code']);
                        $return['text'] = $text;
                        break;
                    }
                }
            }
        }

        return $return;
    }


    // parseContentType
    // parse une string content type
    // si mime est true, retourne simplement l'extension
    final public static function parseContentType(string $return,bool $mime=true):string
    {
        $contentType = self::getSeparator(2);
        $explode = Str::explodeTrim($contentType,$return,2);

        if(!empty($explode[0]))
        {
            $return = $explode[0];

            if($mime === true)
            $return = Mime::toExtension($explode[0]) ?? $explode[0];
        }

        return $return;
    }


    // list
    // les options de list peuvent être null
    // explose et implose une valeur header
    // la clé status est ramené au début, et il ne peut y en avoir qu'une
    // retourne un tableau unidimensionnel avec clé numérique, parfait pour ajouter dans la fonction header
    final public static function list($array,?array $option=null):array
    {
        $option = self::option($option);
        $arr = self::arr($array,$option);
        return self::keyValue($arr,$option);
    }


    // keyValue
    // fait la liste, sans appeler arr avant
    // gère les case implode
    final public static function keyValue(array $array,array $option):array
    {
        $return = [];
        $separator = self::getSeparator(1,$option['implode']);
        $statusKey = self::$config['status'];

        if($option['caseImplode'] !== null)
        $array = Arr::keysChangeCase($option['caseImplode'],$array);

        foreach ($array as $key => $value)
        {
            if(is_string($key) && (is_scalar($value) || is_array($value)))
            {
                $value = (array) $value;

                if(strtolower($key) === $statusKey)
                {
                    $v = (is_array($value))? Arr::valueLast($value):$value;
                    if(is_scalar($v))
                    {
                        $v = Str::cast($v);
                        array_unshift($return,$v);
                    }
                }

                else
                {

                    foreach ($value as $v)
                    {
                        if(is_scalar($v))
                        {
                            $v = Str::cast($v);
                            $return[] = implode($separator,[$key,$v]);
                        }
                    }
                }
            }
        }

        return $return;
    }


    // prepareArr
    // prépare un array dans la méthode arr
    final public static function prepareArr(array $value,?array $option=null):array
    {
        return $value;
    }


    // prepareStr
    // prépare une string dans la méthode arr
    final public static function prepareStr(string $value,?array $option=null):array
    {
        return self::explodeStr($value);
    }


    // explodeStr
    // explode une string header
    // retourne un tableau unidimensionnel avec clés numérique
    final public static function explodeStr(string $value,?array $option=null):array
    {
        return Str::lines($value);
    }


    // setMerge
    // permet de merge une valeur sur un clé existante de assoc
    // ne supporte pas multi-niveau
    // si la clé n'existe pas, c'est simplement un set
    // sinon un tableau est formé avec les deux valeurs
    final public static function setMerge($key,$value,$assoc,?array $option=null):array
    {
        return self::arr(Arr::setMerge($key,$value,self::arr($assoc,$option),self::getSensitive()),$option);
    }


    // setsMerge
    // permet de merge plusieurs valeurs sur des clés existantes de assoc
    // ne supporte pas multi-niveau
    // si les clés n'existent pas, c'est simplement un set
    // sinon un tableau est formé avec les différentes valeurs
    final public static function setsMerge(array $values,$assoc,?array $option=null):array
    {
        return self::arr(Arr::setsMerge($values,self::arr($assoc,$option),self::getSensitive()),$option);
    }


    // prepareContentType
    // prépare une valeur content type à partir d'une extension
    // le charset est automatiquement ajouté si le content type est de type text
    final public static function prepareContentType(string $value,bool $charset=true):?string
    {
        $return = null;

        if(!empty($value))
        {
            $return = Mime::fromExtension($value) ?? $value;

            if($charset === true && Str::isStart('text',$return) && strpos($return,'charset') === false)
            {
                $charset = Encoding::getCharset();
                $separator = self::getSeparator(2);
                if(!empty($separator))
                $return .= $separator.' charset='.$charset;
            }
        }

        return $return;
    }


    // code
    // retourne le code status en provenance d'un int, string ou array
    final public static function code($value):?int
    {
        $return = null;

        if(is_int($value))
        $return = $value;

        else
        {
            $status = self::parseStatus($value);
            if(!empty($status))
            $return = $status['code'];
        }

        return $return;
    }


    // protocol
    // retourne le protocol http à partir d'une int, string ou array
    // si input est un int, alors utiliser server::httpProtocol
    final public static function protocol($value=null):?string
    {
        $return = null;

        if(is_int($value))
        $return = Server::httpProtocol();

        else
        {
            $status = self::parseStatus($value);
            if(!empty($status))
            $return = $status['protocol'];
        }

        return $return;
    }


    // statusText
    // retourne le texte relié à un code status en provenance d'un int, string ou array
    final public static function statusText($value):?string
    {
        $return = null;

        if(is_int($value))
        $return = self::statusTextFromCode($value);

        else
        {
            $status = self::parseStatus($value);
            if(!empty($status))
            $return = $status['text'];
        }

        return $return;
    }


    // statusTextFromCode
    // retourne le texte relié à un code status en provenance d'un code
    final public static function statusTextFromCode(int $value):?string
    {
        $return = null;
        $code = self::code($value);

        if(!empty($code) && array_key_exists($code,Lang\En::getConfig('header/responseStatus')))
        $return = Lang\En::getConfig("header/responseStatus/$code");

        return $return;
    }


    // codeFromStatusText
    // retourne le code à partir d'un texte de statut de réponse
    final public static function codeFromStatusText(string $value):?int
    {
        $return = null;

        if(in_array($value,Lang\En::getConfig('header/responseStatus'),true))
        $return = array_search($value,Lang\En::getConfig('header/responseStatus'),true);

        return $return;
    }


    // getResponseStatus
    // retourne le tableau de statut de réponse, mergé avec celui de lang au besoin
    // seul méthode à utiliser lang, toutes les autres méthodes utilisent uniquemment les headers et status text en anglais
    final public static function getResponseStatus(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('header/responseStatus'),Lang::headerResponseStatus(null,$lang));
    }


    // status
    // retourne la string header status à partir d'une int, string ou array
    final public static function status($value):?string
    {
        $status['protocol'] = self::protocol($value);
        $status['code'] = self::code($value);
        $status['text'] = self::statusText($value);
        return self::makeStatus($status);
    }


    // makeStatus
    // retorne la string header status à partir d'un tableau
    final public static function makeStatus(array $value):?string
    {
        $return = null;

        if(Arr::keysAre(['protocol','code','text'],$value) && is_int($value['code']) && is_string($value['text']))
        $return = $value['protocol'].' '.$value['code'].' '.$value['text'];

        return $return;
    }


    // contentType
    // retourne le content type à partir d'un tableau header list ou assoc
    // si la variable parse n'est pas vide, le content type est envoyé dans parseContentType
    // si la variable parse est 2, la valeur mime est true dans parseContentType
    // si parse est false, parse devient 1 (enlève le charset)
    // si parse est true, parse devient 0 (garde le charset)
    final public static function contentType(array $header,$parse=1):?string
    {
        $return = self::get('Content-Type',$header);

        if(is_array($return))
        $return = current($return);

        if(is_string($return) && strlen($return))
        {
            if($parse === false)
            $parse = 1;

            elseif($parse === true)
            $parse = 0;

            if(!empty($parse))
            $return = self::parseContentType($return,($parse === 2));
        }

        return $return;
    }


    // contentLength
    // retourne le content length à partir d'un tabealu header list ou assoc
    // cast le retour en int
    final public static function contentLength(array $header):?int
    {
        $return = null;
        $contentLength = self::get('Content-Length',$header);

        if(is_numeric($contentLength))
        $return = (int) $contentLength;

        return $return;
    }


    // setContentType
    // change la valeur du content type dans le tableau de headers
    // retourne un tableau header assoc
    final public static function setContentType(string $value,array $return=[]):array
    {
        return self::set('Content-Type',self::prepareContentType($value),$return);
    }


    // setProtocol
    // change le protocol du header status dans le tableau
    // il doit déjà y avoir une entrée status - garde code et text
    final public static function setProtocol($value,array $return):array
    {
        $status = self::parseStatus($return);
        if(!empty($status) && (is_string($value) || is_float($value)))
        {
            if(is_string($value))
            $status['protocol'] = $value;

            elseif(is_numeric($value))
            {
                $value = Str::cast($value);
                $status['protocol'] = 'HTTP/'.$value;
            }

            if(!empty($status['protocol']))
            $return = self::setStatus(self::makeStatus($status),$return);
        }

        return $return;
    }


    // setCode
    // comme setStatus mais s'il y a un protocol existant dans le tableau de réponse, la méthode le garde
    // sinon envoi à setStatus
    final public static function setCode(int $value,array $return=[]):array
    {
        $status = self::parseStatus($return);

        if(!empty($status))
        {
            $status['code'] = $value;
            $status['text'] = self::statusTextFromCode($value);

            $return = self::setStatus(self::makeStatus($status),$return);
        }

        else
        $return = self::setStatus($value,$return);

        return $return;
    }


    // setStatusText
    // change le texte du code du header status dans le tableau
    // il doit déjà y avoir une entrée status - garde protocole et code
    final public static function setStatusText(string $value,array $return):array
    {
        $status = self::parseStatus($return);
        if(!empty($status))
        {
            $status['text'] = $value;
            $return = self::setStatus(self::makeStatus($status),$return);
        }

        return $return;
    }


    // setStatus
    // change le header status du tableau
    // accepte un int, string ou array
    final public static function setStatus($value,array $return=[]):array
    {
        $status = self::status($value);
        $statusKey = self::$config['status'];

        if(!empty($status) && !empty($statusKey))
        $return = self::set($statusKey,$status,$return);

        return $return;
    }


    // ok
    // change le code du tableau header pour 200
    final public static function ok(array $return=[]):array
    {
        return self::setCode(200,$return);
    }


    // moved
    // change le code du tableau header pour 301 ou 302 ou un autre code
    // par défaut 301
    final public static function moved($code=null,array $return=[]):array
    {
        if($code === true || $code === null)
        $code = 301;

        elseif($code === false)
        $code = 302;

        if(is_int($code))
        $return = self::setCode($code,$return);

        return $return;
    }


    // notFound
    // change le code du tableau header pour 404
    final public static function notFound(array $return=[]):array
    {
        return self::setCode(404,$return);
    }


    // redirect
    // crée une redirection dans le tableau header
    // permanent permet de spécifier si le code est 301 ou 302
    final public static function redirect(string $value,$code=null,array $return=[]):array
    {
        $return = self::moved($code,$return);
        return self::set('Location',$value,$return);
    }


    // download
    // crée un téléchargement dans le tableau header à partir d'un tableau resource::responseMeta
    final public static function download(array $value,array $return=[]):array
    {
        if(Arr::keysExists(['mime','size','basename'],$value))
        {
            $return = self::setContentType($value['mime'],$return);
            $return = self::sets([
                'Content-Transfer-Encoding'=>'binary',
                'Content-Description'=>'File Transfer',
                'Content-Length'=>$value['size'],
                'Content-Disposition'=>'attachment; filename="'.$value['basename'].'"'
            ],$return);
        }

        return $return;
    }


    // toScreen
    // crée un affichage dans le tableau header à partir d'un tableau resource::responseMeta
    final public static function toScreen(array $value,array $return=[]):array
    {
        if(Arr::keysExists(['mime','size'],$value))
        {
            $return = self::setContentType($value['mime'],$return);
            $return = self::sets([
                'Content-Length'=>$value['size'],
                'Content-Disposition'=>'inline; filename="'.$value['basename'].'"'
            ],$return);
        }

        return $return;
    }


    // fingerprint
    // retourne un fingerprint sha1 des entêtes de requêtes
    final public static function fingerprint(array $headers,array $keys):?string
    {
        $return = null;
        $fingerprint = [];

        foreach ($keys as $key)
        {
            if(is_string($key))
            {
                $header = Arr::get($key,$headers,false);
                if(is_string($header))
                $fingerprint[] = $header;
            }
        }

        if(!empty($fingerprint))
        {
            $string = implode('-',$fingerprint);
            $return = Crypt::sha($string,1);
        }

        return $return;
    }
}

// init
Header::__init();
?>