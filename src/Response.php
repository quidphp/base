<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// response
// class with static methods to alter the current response
final class Response extends Root
{
    // config
    protected static array $config = [
        'idLength'=>10, // longueur du id de la réponse
        'closeDown'=>[], // callbacks à lancer lors d'un closeDown
        'closeBody'=>[], // callbacks à lancer lors d'un closeBody
        'default'=>[
            'code'=>200, // code de réponse par défaut
            'headers'=>[ // header par défaut, le cacheLimiter dans session peut prendre le dessus sur certains de ces défauts
                'Expires'=>0, // temps d'expiration d'une réponse (maintenant)
                'Cache-Control'=>'no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
                'Pragma'=>'no-cache',
                'Connection'=>'keep-alive',
                'Last-Modified'=>0,
                'X-UA-Compatible'=>'IE=Edge'], // désactive compatibility mode pour IE
            'headersCallbackSpeed'=>null, // nom du header pour le speed, avant c'était un tableau headersCallback mais ça causait un bug bizarre sur php 7.3
            'contentType'=>true] // contentType par défaut, si true alors utilise le callback autoContentType
    ];


    // id
    protected static ?string $id = null; // id unique de la réponse


    // is200
    // retourne vrai si le code de la réponse est 200 (OK)
    final public static function is200():bool
    {
        return self::isCode(200);
    }


    // isCodePositive
    // retourne vrai si le code de la réponse est positive (200 à 399)
    final public static function isCodePositive():bool
    {
        return self::isCodeBetween(200,399);
    }


    // isCodeLoggable
    // retourne vrai si le code de la réponse est positive (200 à 399) mais pas 301
    final public static function isCodeLoggable():bool
    {
        return !self::isCodePositive() || self::isCode(301);
    }


    // isCodeError
    // retourne vrai si le code de la réponse est erreur (400 à 499)
    final public static function isCodeError():bool
    {
        return self::isCodeIn(400);
    }


    // isCodeServerError
    // retourne vrai si le code de la réponse est server error (500+)
    final public static function isCodeServerError():bool
    {
        return self::isCodeIn(500);
    }


    // isHttp1
    // retourne vrai si la le protocol est http 1 ou 1.1
    final public static function isHttp1():bool
    {
        return Server::isHttp1();
    }


    // isHttp2
    // retourne vrai si la le protocol est http 2
    final public static function isHttp2():bool
    {
        return Server::isHttp2();
    }


    // isHtml
    // retourne vrai si la réponse est de content type html
    final public static function isHtml():bool
    {
        return self::isContentType('html');
    }


    // isHtmlOrAuto
    // retourne vrai si la réponse est de content type html ou automatique
    final public static function isHtmlOrAuto():bool
    {
        return empty(self::contentType()) || self::isHtml();
    }


    // isJson
    // retourne vrai si la réponse est de content type json
    final public static function isJson():bool
    {
        return self::isContentType('json');
    }


    // isXml
    // retourne vrai si la réponse est de content type xml
    final public static function isXml():bool
    {
        return self::isContentType('xml');
    }


    // isConnectionNormal
    // retourne vrai si la connection est normal, renvoie à base/server
    final public static function isConnectionNormal():bool
    {
        return Server::isConnectionNormal();
    }


    // isConnectionAborted
    // retourne vrai si la connection est aborted, renvoie à base/server
    final public static function isConnectionAborted():bool
    {
        return Server::isConnectionAborted();
    }


    // areHeadersSent
    // retourne vrai si les en-têtes ont été envoyés
    final public static function areHeadersSent():bool
    {
        return headers_sent();
    }


    // isCode
    // retourne vrai le code de la réponse est un de ceux donnés
    final public static function isCode(...$values):bool
    {
        $return = false;

        foreach ($values as $value)
        {
            $return = (is_numeric($value) && http_response_code() === (int) $value);

            if($return === true)
            break;
        }

        return $return;
    }


    // isCodeBetween
    // retourne vrai si le code est entre les valeurs from et to
    // possible de spécifier le code de comparaison en troisième argument, sinon utilise le courant
    final public static function isCodeBetween($from,$to,?int $code=null):bool
    {
        $code ??= self::code();
        return is_int($from) && is_int($to) && $code >= $from && $code <= $to;
    }


    // isCodeIn
    // retourne vrai si le code se trouve dans le groupe (la centaine) donné en argument
    // possible de spécifier le code de comparaison en deuxième argument, sinon utilise le courant
    final public static function isCodeIn($value,?int $code=null):bool
    {
        $return = false;

        if(is_int($value))
        {
            $from = (int) (floor($value / 100) * 100);
            $to = ($from + 99);
            $return = self::isCodeBetween($from,$to,$code);
        }

        return $return;
    }


    // isContentType
    // retourne vrai si la réponse est d'un content-type
    // supporte le content type exact, le content type short et le mime
    final public static function isContentType(string $value):bool
    {
        return Header::isContentType($value,self::headers(false));
    }


    // headerExists
    // retourne vrai si la réponse contient le header
    final public static function headerExists(string $key):bool
    {
        return Header::exist($key,self::headers(false));
    }


    // headersExists
    // retourne vrai si la réponse contient les headers
    final public static function headersExists(array $keys):bool
    {
        return Header::exists($keys,self::headers(false));
    }


    // id
    // retourne le id unique de la réponse
    final public static function id():string
    {
        if(self::$id === null && is_int(self::$config['idLength']))
        self::$id = Str::random(self::$config['idLength']);

        return self::$id;
    }


    // timeLimit
    // alias pour set_time_limit, renvoie à base/server
    final public static function timeLimit(int $value=0):bool
    {
        return Server::timeLimit($value);
    }


    // connectionStatus
    // retourne le statut de la connection, renvoie à base/server
    // 0 normal, 1 aborted, 2 timeout, 3 aborted and timeout
    final public static function connectionStatus():int
    {
        return Server::connectionStatus();
    }


    // ignoreUserAbort
    // n'arrête pas le script si la connection est aborted, renvoie à base/server
    // retourne la valeur courante de ignore_user_abort
    final public static function ignoreUserAbort(bool $value=true):bool
    {
        return Server::ignoreUserAbort($value);
    }


    // headersSentFrom
    // retourne null ou un tableau contenant le fichier et la ligne ou les headers ont été envoyés
    final public static function headersSentFrom():?array
    {
        $return = null;
        $file = null;
        $line = null;
        $headersSent = headers_sent($file,$line);

        if($headersSent === true)
        $return = ['file'=>$file,'line'=>$line];

        return $return;
    }


    // code
    // retourne le code actuel de la réponse
    // va retourner null dans un environnement cli
    final public static function code():?int
    {
        $return = http_response_code();

        if(!is_int($return))
        $return = null;

        return $return;
    }


    // protocol
    // retourne le protocole de la réponse
    final public static function protocol():?string
    {
        return Server::httpProtocol();
    }


    // statusText
    // retourne le texte relié au code status
    final public static function statusText():?string
    {
        return Header::statusText(self::code());
    }


    // status
    // retourne le header status de la réponse
    // si le message est custom, ajouté via setStatusText, cette méthode ne le retournera pas
    final public static function status():?string
    {
        return Header::status(self::code());
    }


    // setCode
    // change le code actuel de la réponse
    // http_response_code va automatiquement mettre à jour le protocole et status text
    // retourne vrai si le code courant après la modification est celui donné en argument
    final public static function setCode(int $code):bool
    {
        return self::setStatus($code);
    }


    // setStatus
    // change le header status
    // l'argument peut être un int (code) ou une string (status text)
    // le response code doit être défini dans config de header, ce n'est pas possible de changer le status text ou créer un code non défini
    final public static function setStatus($value):bool
    {
        $return = false;

        if(!self::areHeadersSent())
        {
            $code = null;
            $text = null;

            if(is_int($value) && Header::isCodeValid($value))
            {
                $code = $value;
                $text = Header::statusTextFromCode($value);
            }

            elseif(is_string($value) && Header::isStatusTextValid($value))
            {
                $code = Header::codeFromStatusText($value);
                $text = $value;
            }

            if(is_int($code) && is_string($text))
            {
                $array['protocol'] = self::protocol();
                $array['code'] = $code;
                $array['text'] = $text;
                $status = Header::makeStatus($array);

                if(Header::isStatus($status))
                {
                    header($status,true);
                    $return = (http_response_code() === $code);
                }
            }
        }

        return $return;
    }


    // ok
    // change le code la requête pour 200
    final public static function ok():bool
    {
        return self::setCode(200);
    }


    // moved
    // change le code la requête un code entre 300 et 399
    // par défaut 301, si false utilise 302
    final public static function moved($code=null):bool
    {
        $return = false;
        $code = self::movedCode($code);

        if(is_int($code))
        $return = self::setCode($code);

        return $return;
    }


    // movedCode
    // retourne le code à utiliser pour la redirection
    final public static function movedCode($value):?int
    {
        $return = null;

        if($value === true || $value === null)
        $value = 302;

        elseif($value === false)
        $value = 301;

        if(self::isCodeIn(300,$value))
        $return = $value;

        return $return;
    }


    // error
    // change le code de la requête entre 400 et 499
    // kill permet de tuer la réponse
    final public static function error($code=null,bool $kill=false)
    {
        $return = false;

        if(!is_int($code))
        $code = 404;

        if(self::isCodeIn(400,$code))
        $return = self::setCode($code);

        if($kill === true)
        self::kill(['error',$code]);

        return $return;
    }


    // notFound
    // change le code de la requête pour 404
    // kill permet de tuer la réponse
    final public static function notFound(bool $kill=false):bool
    {
        return self::error(404,$kill);
    }


    // serverError
    // change le code de la requête pour un code 500+
    // kill permet de tuer la réponse
    final public static function serverError($code=null,bool $kill=false):bool
    {
        $return = false;

        if(!is_int($code))
        $code = 500;

        if(self::isCodeIn(500,$code))
        $return = self::setCode($code);

        if($kill === true)
        self::kill(['serverError',$code]);

        return $return;
    }


    // redirect
    // redirige la réponse vers une autre adresse
    // kill permet de tuer la réponse
    final public static function redirect($value,$code=null,bool $kill=true,bool $encode=true):bool
    {
        $return = false;

        if(is_object($value))
        $value = Obj::cast($value);

        if(is_string($value) && self::moved($code))
        {
            $return = true;

            if($encode === true)
            $value = Uri::encodeAll($value);

            self::setHeader('Location',$value);

            if($kill === true)
            self::kill(['redirect',$value,self::movedCode($code)]);
        }

        return $return;
    }


    // redirectReferer
    // redirige la réponse vers le referrer s'il est interene
    // par défaut, vérifier si le referer est safe
    // le code utilisé sera par défaut 301
    // si pas de referer, ou refered unsafe et que fallback est true, renvoie vers le schemeHost
    final public static function redirectReferer(bool $fallback=true,bool $safe=true,$code=true,bool $kill=true,bool $encode=true):bool
    {
        $return = false;
        $referer = Request::referer(true);

        if(!empty($referer) && $safe === true)
        {
            $path = Uri::path($referer);

            if(!is_string($path) || !Path::isSafe($path))
            $referer = null;
        }

        if(empty($referer) && $fallback === true)
        $referer = self::redirectSchemeHost();

        elseif(!empty($referer))
        $return = self::redirect($referer,$code,$kill,$encode);

        return $return;
    }


    // redirectSchemeHost
    // redirige vers le scheme host courant
    final public static function redirectSchemeHost($code=true,bool $kill=true,bool $encode=true):bool
    {
        return self::redirect(Request::schemeHost(),$code,$kill,$encode);
    }


    // download
    // force le téléchargement de la valeur donné en argument dans le navigateur
    // si value est string, alors ouvre une resource
    // option kill, length et sleep
    // il est aussi possible de changer le mime ou le basename si la resource est de type phpWritable (via les options de stream)
    final public static function download($value,?array $option=null):bool
    {
        return self::downloadToScreen('download',$value,$option);
    }


    // toScreen
    // force l'affichage de la valeur donné en argument dans le navigateur
    // si value est string, alors ouvre une resource
    // option kill, length et sleep
    final public static function toScreen($value,?array $option=null):bool
    {
        return self::downloadToScreen('toScreen',$value,$option);
    }


    // downloadToScreen
    // utilisé par les méthodes download et toScreen
    // si tout va, met un code réponse 200
    final protected static function downloadToScreen(string $method,$value,?array $option=null):bool
    {
        $return = false;
        $option = Arr::plus(['kill'=>true,'length'=>true,'sleep'=>false,'mime'=>null,'basename'=>null],$option);

        if(in_array($method,['download','toScreen'],true))
        {
            if(is_object($value))
            $value = Obj::cast($value);

            if(is_string($value))
            $value = Res::open($value);

            if(is_resource($value))
            {
                if(is_string($option['mime']))
                Res::setContextMime($option['mime'],$value);

                if(is_string($option['basename']))
                Res::setContextBasename($option['basename'],$value);

                $meta = Res::responseMeta($value);

                if(!empty($meta) && is_array($meta))
                {
                    $headers = Header::$method($meta);

                    if(!empty($headers))
                    {
                        $return = true;

                        self::ok();
                        self::setsHeader($headers);

                        if($meta['kind'] !== 'phpOutput')
                        Res::passthruChunk($option['length'],$value,$option);

                        if($option['kill'] === true)
                        self::kill();
                    }
                }
            }
        }

        return $return;
    }


    // headers
    // retourne la liste des headers par défaut formatté en tableau associatif
    // mettre parse à false pour envoyer le résultat de headers_list
    final public static function headers(bool $parse=true):array
    {
        $return = headers_list();

        if($parse === true)
        $return = Header::arr($return);

        return $return;
    }


    // getHeader
    // retourne un header
    // insensible à la case
    final public static function getHeader(string $key)
    {
        return Header::get($key,self::headers(false));
    }


    // getsHeader
    // retourne plusieurs headers
    // insensible à la case
    final public static function getsHeader(array $keys):array
    {
        return Header::gets($keys,self::headers(false));
    }


    // contentType
    // retourne le content type de la réponse
    // si parse n'est pas vide, le content type est envoyé dans parse
    // si parse est 2, la valeur mime est true dans parseContentType
    final public static function contentType(?int $parse=2):?string
    {
        return Header::contentType(self::headers(false),$parse);
    }


    // setHeader
    // modifie un header
    // possibilité de remplace ou append si header déjà existant
    // retourne le nombre de ligne header ajoute
    // retourne null si headers sont envoyés
    final public static function setHeader(string $key,$value,bool $replace=true):?int
    {
        $return = null;
        $sets = self::setsHeader([$key=>$value],$replace);

        if(!empty($sets))
        $return = current($sets);

        return $return;
    }


    // setsHeader
    // modifie plusieurs headers
    // possibilité de remplace ou append si header déjà existant
    // retourne null si headers sont envoyés
    final public static function setsHeader(array $values,bool $replace=true):?array
    {
        $return = null;

        if(!self::areHeadersSent())
        {
            $return = [];

            foreach ($values as $key => $value)
            {
                $return[$key] = 0;
                $list = Header::list([$key=>$value]);

                foreach ($list as $i => $header)
                {
                    $r = ($i === 0)? $replace:false;
                    header($header,$r);
                    $return[$key]++;
                }
            }
        }

        return $return;
    }


    // setContentType
    // change le content type de la réponse courante
    final public static function setContentType(string $value):bool
    {
        $contentType = Header::prepareContentType($value);
        return !empty($contentType) && self::setHeader('Content-Type',$contentType) === 1;
    }


    // unsetHeader
    // enlève un header
    // retourne null si headers sont envoyés
    final public static function unsetHeader(string $key):?bool
    {
        $return = null;

        if(!self::areHeadersSent())
        {
            $return = false;

            if(self::headerExists($key))
            {
                header_remove($key);
                $return = true;
            }
        }

        return $return;
    }


    // unsetsHeader
    // enlève plusieurs headers
    // retourne null si headers sont envoyés
    final public static function unsetsHeader(array $keys):?array
    {
        $return = null;

        if(!self::areHeadersSent())
        {
            $return = [];

            foreach ($keys as $key)
            {
                $return[$key] = false;

                if(self::headerExists($key))
                {
                    header_remove($key);
                    $return[$key] = true;
                }
            }
        }

        return $return;
    }


    // emptyHeader
    // vide tous les headers y compris ceux définis par PHP par défaut
    // retourne null si headers sont envoyés
    final public static function emptyHeader():?array
    {
        return self::unsetsHeader(array_keys(self::headers(true)));
    }


    // prepare
    // applique les paramètres par défaut pour la réponse
    // code de réponse, headers par défaut, contentType ou autoContentType, buffer start, onShutDownCloseDown et onCloseDownCloseBody
    // note headersCallbackSpeed -> avant c'était un tableau headersCallback mais ça causait un bug bizarre sur php 7.3
    final public static function prepare(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(self::$config['default'],$option);
        $bufferCallback = null;

        // code
        if(is_int($option['code']))
        $return['code'] = self::setCode($option['code']);

        // headers
        if($option['headers'] !== null)
        $return['headers'] = self::setsHeaderDefault($option['headers']);

        // headersCallbackSpeed
        if(is_string($option['headersCallbackSpeed']))
        {
            $name = $option['headersCallbackSpeed'];
            $return['headersCallbackSpeed'] = self::setHeaderCallback(function() use ($name) {
                $speed = Debug::speed();
                $header = $name.': '.$speed;
                header($header);
            });
        }

        // contentType
        if(!empty($option['contentType']))
        {
            if(is_string($option['contentType']))
            $return['setContentType'] = self::setContentType($option['contentType']);

            elseif($option['contentType'] === true)
            $bufferCallback = [self::class,'autoContentType'];
        }

        // buffer
        $return['getCleanAllEcho'] = Buffer::getCleanAllEcho($bufferCallback);

        // onCloseDownCloseBody
        self::onCloseDownCloseBody();

        // onShutDownCloseDown
        self::onShutDownCloseDown();

        return $return;
    }


    // setHeaderCallback
    // permet d'enregistrer un callback lors de l'envoie du output
    // permet de changer les headers avant l'envoie
    final public static function setHeaderCallback(callable $callable):bool
    {
        return header_register_callback($callable);
    }


    // prepareHeaderDefault
    // méthode utilisé par setsHeaderDefault
    // si une value d'une header par défaut est int, alors additionne le timestamp courant et formate au format GMT
    final public static function prepareHeaderDefault(array $return):array
    {
        $time = Datetime::time();

        foreach ($return as $key => $value)
        {
            if(is_int($value))
            $return[$key] = Datetime::gmt($value += $time);
        }

        return $return;
    }


    // setsHeaderDefault
    // applique les headers par défaut
    // les headers présents avant seront vidés
    final public static function setsHeaderDefault(?array $option=null):?array
    {
        $return = null;
        $option = Arr::plus(self::$config['default']['headers'],$option);

        if(!empty($option) && is_array($option))
        {
            self::emptyHeader();
            $option = self::prepareHeaderDefault($option);
            $return = self::setsHeader($option);
        }

        return $return;
    }


    // body
    // retourne le body complet dans le buffer
    // le buffer est conservé mais aplati à un niveau
    final public static function body():string
    {
        return Buffer::getAll(true);
    }


    // setBody
    // remplace le body complet de la réponse par la valeur fourni en argument
    final public static function setBody(string $value):bool
    {
        return Buffer::cleanAllEcho($value);
    }


    // flushEchoBody
    // ouvre un buffer, echo des donnés et flush ferme tous les buffer, ouvre un autre buffer à la fin du processus
    final public static function flushEchoBody(string $value):bool
    {
        return Buffer::flushEcho($value);
    }


    // prependBody
    // ajoute du contenu au début du body de la réponse
    final public static function prependBody(string $value):bool
    {
        return Buffer::prependEcho($value);
    }


    // appendBody
    // ajoute du contenu au body de la réponse
    final public static function appendBody(string $value):bool
    {
        return Buffer::appendEcho($value);
    }


    // emptyBody
    // vide le contenu du body de la réponse
    final public static function emptyBody():bool
    {
        return !empty(Buffer::cleanAll());
    }


    // sleep
    // pause l'éxécution du script
    // permet de mettre une valeur flottante, par exemple 0.5 seconde
    // retourne null ou le temps que le script a sleep
    final public static function sleep(float $value):?float
    {
        $return = null;

        if($value > 0)
        {
            $microtime = Datetime::microtime();
            $uvalue = (int) ($value * 1000000);
            usleep($uvalue);
            $return = Datetime::microtime() - $microtime;
        }

        return $return;
    }


    // sleepUntil
    // pause l'éxécution du script jusqu'au timestamp ou microtimestamp donné en argument
    // important d'utiliser microtime comme valeur de base si la valeur du sleep est moins d'une seconde
    // retourne null ou le temps que le script a sleep
    final public static function sleepUntil(float $value):?float
    {
        $return = null;
        $microtime = Datetime::microtime();

        if($value > $microtime && time_sleep_until($value))
        $return = Datetime::microtime() - $microtime;

        return $return;
    }


    // kill
    // tue la réponse et l'éxécution du script
    // si valeur est int entre 0 et 254, code de sortie non affiché
    // si value est scalar, message de sortie
    final public static function kill($value=null):void
    {
        $kill = null;

        if(is_int($value) && $value >= 0 && $value < 255)
        $kill = $value;

        elseif(!empty($value) && Server::isCli())
        {
            $value = Debug::varGet($value);
            Buffer::flushEcho($value);
        }

        elseif(is_string($value))
        $kill = $value;

        exit($kill);
    }


    // onShutDown
    // enregistre une callable à appeler au shutdown
    // des arguments peuvent être passés au callable
    final public static function onShutDown(callable $call,...$args):void
    {
        register_shutdown_function($call,...$args);
    }


    // onCloseDown
    // enregistre une callable à appeler au close down, des arguments peuvent être passés au callable
    final public static function onCloseDown(callable $call,...$args):void
    {
        self::$config['closeDown'][] = [$call,$args];
    }


    // emptyCloseDown
    // vide le tableau des callbacks sur closeDown
    // remet on closeBody sur closeDown
    final public static function emptyCloseDown():void
    {
        self::$config['closeDown'] = [];
        self::onCloseDownCloseBody();
    }


    // closeDown
    // lance tous les callbacks closeDown dans l'ordre d'ajout
    final public static function closeDown():void
    {
        self::closeDownBody('closeDown');
    }


    // onShutDownCloseDown
    // sur shutDown lance closeDown
    final public static function onShutDownCloseDown():void
    {
        self::onShutDown([self::class,'closeDown']);
    }


    // onCloseBody
    // enregistre une callable à appeler au close doc, des arguments peuvent être passés au callable
    final public static function onCloseBody(callable $call,...$args):void
    {
        self::$config['closeBody'][] = [$call,$args];
    }


    // emptyCloseBody
    // vide le tableau des callbacks sur closeBody
    final public static function emptyCloseBody():void
    {
        self::$config['closeBody'] = [];
    }


    // closeBody
    // lance tous les callbacks closeBody dans l'ordre d'ajout
    final public static function closeBody():void
    {
        self::closeDownBody('closeBody');
    }


    // onCloseDownCloseBody
    // sur closeDown lance closeBody
    final public static function onCloseDownCloseBody():void
    {
        self::onCloseDown([self::class,'closeBody']);
    }


    // speedOnCloseBody
    // affiche speed sur closeBody
    final public static function speedOnCloseBody():void
    {
        self::onCloseBody(function() {
            if(self::isHtmlOrAuto())
            Debug::var(Debug::speed());
        });
    }


    // closeDownBody
    // lance tous les callbacks closeDown dans l'ordre d'ajout
    // les callbacks sont effacés au fur et à mesure
    final protected static function closeDownBody(string $type):void
    {
        if(in_array($type,['closeDown','closeBody'],true))
        {
            foreach (self::$config[$type] as $key => $callback)
            {
                if(is_array($callback) && !empty($callback))
                {
                    $callable = Arr::valueFirst($callback);
                    $args = Arr::valueLast($callback);
                    $callable(...$args);
                }

                unset(self::$config[$type][$key]);
            }
        }
    }


    // getAutoContentType
    // détecte le content type à partir d'une string
    final public static function getAutoContentType(string $value):string
    {
        $return = 'html';

        if(Json::is($value))
        $return = 'json';

        elseif(Xml::is($value))
        $return = 'xml';

        return $return;
    }


    // autoContentType
    // applique le content type en fonction de la valeur donné en argument
    // aucune content type appliquer si la réponse en a déjà un
    // est utilisé comme callback pour ob_start
    final public static function autoContentType(string $return):?string
    {
        $contentType = self::contentType();

        if(empty($contentType))
        {
            $autoContentType = self::getAutoContentType($return);
            self::setContentType($autoContentType);
        }

        return $return;
    }
}
?>