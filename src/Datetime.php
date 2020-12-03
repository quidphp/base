<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// datetime
// class with static methods to generate, format and parse dates
final class Datetime extends Root
{
    // config
    protected static array $config = [
        'format'=>[
            'replace'=>[ // contenu de remplacement pour date, via une callable et éventuellement un map
                '%'=>['call'=>[self::class,'getMonths'],'map'=>[Str::class,'lower'],'args'=>[true]],
                '@'=>['call'=>[self::class,'getMonths'],'map'=>[Str::class,'upperFirst'],'args'=>[true]]],
            'date'=>[ // format compatible avec date, gmdate et idate (certains)
                'year'=>'Y',
                'month'=>'m',
                'day'=>'d',
                'hour'=>'H',
                'minute'=>'i',
                'second'=>'s',
                'ymd'=>'Y-m-d',
                'ymdhis'=>'Y-m-d H:i:s',
                'ym'=>'Y-m',
                'his'=>'H:i:s',
                'rfc822'=>'D, j M Y H:i:s O',
                'sql'=>'Y-m-d H:i:s',
                'compact'=>'YmdHis',
                'gmt'=>'D, d M Y H:i:s \G\M\T',
                'ics'=>'Ymd\THis',
                'office365'=>'Ymd\THisP',
                'daysInMonth'=>'t',
                'weekDay'=>'w',
                'weekNo'=>'W'],
            'locale'=>[ // format locale compatible avec strftime et gmstrftime
                'ymd'=>'%G-%m-%d',
                'ymdhis'=>'%G-%m-%d %H:%M:%S',
                'ym'=>'%G-%m']],
        'floor'=>[ // données pour date floor
            'month'=>1,
            'day'=>1,
            'hour'=>0,
            'minute'=>0,
            'second'=>0],
        'ceil'=>[ // données pour date ceils
            'month'=>12,
            'hour'=>23,
            'minute'=>59,
            'second'=>59],
        'amount'=>[
            'secondsInMinute'=>60, // seconde par minute
            'secondsInHour'=>3600, // seconde par heure
            'secondsInDay'=>86400, // seconde par jour
            'minutesInHour'=>60, // minute par heure
            'hoursInDay'=>24, // heure par jour
            'daysInMonth'=>31, // jour par mois
            'daysInYear'=>365, // jour par année
            'monthsInYear'=>12, // mois par année
            'maxYear'=>9999], // année maximale
        'timestamp'=>null, // timestamp de début de script
        'microtime'=>null // microtime de début de script
    ];


    // isNow
    // retourne vrai si la temps donné est maintenant
    final public static function isNow($value=null,$format=null):bool
    {
        return self::time($value,$format) === self::now();
    }


    // isValid
    // retourne vrai si une date est valide
    // value peut être int ou array, si c'est int ça représente year
    final public static function isValid($value,?int $month=null,?int $day=null):bool
    {
        $return = false;
        $year = (is_int($value))? $value:null;

        if(is_array($value))
        {
            foreach (['year','month','day'] as $v)
            {
                if(array_key_exists($v,$value) && is_int($value[$v]))
                $$v = $value[$v];
            }
        }

        if(is_int($year) && !empty($year) && is_int($month) && is_int($day))
        $return = checkdate($month,$day,$year);

        return $return;
    }


    // isYearValid
    // retourne vrai si le timestamp est une année valide
    final public static function isYearValid($value):bool
    {
        return is_int($value) && $value >= 0 && $value <= self::$config['amount']['maxYear'];
    }


    // isYearLeap
    // retourne vrai si l'année est bisextile
    // la valeur peut être une année 4 chiffres ou un timestamp
    final public static function isYearLeap($value=null,$format=null):bool
    {
        $value = self::time($value,$format,true);
        return is_int($value) && date('L',$value) === '1';
    }


    // isToday
    // retourne vrai si la temps donné est aujourd'hui
    final public static function isToday($value=null,$format=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::floorDay($value) === self::floorDay(null);
    }


    // isTomorrow
    // retourne vrai si la temps donné est demain
    final public static function isTomorrow($value=null,$format=null):bool
    {
        $value = self::time($value,$format);
        $timestamp = self::addDay(1);
        return is_int($value) && self::floorDay($value) === self::floorDay($timestamp);
    }


    // isYesterday
    // retourne vrai si la temps donné est hier
    final public static function isYesterday($value=null,$format=null):bool
    {
        $value = self::time($value,$format);
        $timestamp = self::addDay(-1);
        return is_int($value) && self::floorDay($value) === self::floorDay($timestamp);
    }


    // isWeekend
    // retourne vrai si le temps est durant le week-end (samedi, dimanche)
    final public static function isWeekend($value=null,$format=null):bool
    {
        $dayNo = self::weekDay($value,null,$format);
        return in_array($dayNo,[0,6],true);
    }


    // isYear
    // retourne vrai si le temps donné a la même année que timestamp
    final public static function isYear($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::year($value) === self::year($timestamp);
    }


    // isMonth
    // retourne vrai si le temps donné est identique jusqu'au mois de timestamp
    final public static function isMonth($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::floorMonth($value) === self::floorMonth($timestamp);
    }


    // isDay
    // retourne vrai si le temps donné est identique jusqu'au jour de timestamp
    final public static function isDay($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::floorDay($value) === self::floorDay($timestamp);
    }


    // isDayStart
    // retourne vrai si le temps est exactement le début d'un jour
    final public static function isDayStart($value=null,$format=null):bool
    {
        return self::secondsInDay($value,$format) === 0;
    }


    // isHour
    // retourne vrai si le temps donné est identique jusqu'à l'heure de timestamp
    final public static function isHour($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::floorHour($value) === self::floorHour($timestamp);
    }


    // isMinute
    // retourne vrai si le temps donné est identique jusqu'à la minute de timestamp
    final public static function isMinute($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && self::floorMinute($value) === self::floorMinute($timestamp);
    }


    // isSecond
    // retourne vrai si le temps donné est identique à timestamp
    final public static function isSecond($value=null,$format=null,?int $timestamp=null):bool
    {
        $value = self::time($value,$format);
        return is_int($value) && $value === self::time($timestamp);
    }


    // isFormat
    // retourne vrai si la date est bien du format spécifé
    final public static function isFormat($format,$value):bool
    {
        $return = false;

        if(is_string($value) && !empty($value))
        {
            $parse = self::parse($format,$value);
            $get = self::get(self::time($value,$format));

            if(is_array($parse) && is_array($get))
            {
                $parse = Arr::cleanEmpty($parse);
                $get = Arr::cleanEmpty($get);
                $keys = array_keys($parse);
                $return = (Arr::gets($keys,$get) === $parse);
            }
        }

        return $return;
    }


    // isFormatDateToDay
    // retourne vrai si le format est dateToDay
    // ce format change selon la langue
    final public static function isFormatDateToDay($value):bool
    {
        return self::isFormat('dateToDay',$value);
    }


    // isFormatDateToMinute
    // retourne vrai si le format est dateToMinute
    // ce format change selon la langue
    final public static function isFormatDateToMinute($value):bool
    {
        return self::isFormat('dateToMinute',$value);
    }


    // isFormatDateToSecond
    // retourne vrai si le format est dateToSecond
    // ce format change selon la langue
    final public static function isFormatDateToSecond($value):bool
    {
        return self::isFormat('dateToSecond',$value);
    }


    // getInitTimestamp
    // retourne le timestamp courant archivé dans les config de la classe
    // ceci représente le temps au lancement de l'éxécution du script
    final public static function getInitTimestamp():int
    {
        $return = self::$config['timestamp'];

        if(!is_int($return))
        $return = self::$config['timestamp'] = self::now();

        return $return;
    }


    // setInitTimestamp
    // change le timestamp courant archivé dans les config de la classe
    // ceci représente le temps au lancement de l'éxécution du script
    final public static function setInitTimestamp(int $value):void
    {
        self::$config['timestamp'] = $value;
    }


    // getInitMicrotime
    // retourne le microtime courant archivé dans les config de la classe
    final public static function getInitMicrotime():float
    {
        $return = self::$config['microtime'];

        if(!is_float($return))
        $return = self::$config['microtime'] = self::microtime();

        return $return;
    }


    // setInitMicrotime
    // change le microtime archivé dans les config de la classe
    final public static function setInitMicrotime(float $value):void
    {
        self::$config['microtime'] = $value;
    }


    // seconds
    // retourne le nombre de secondes dans une année, mois, jour, heure, minute ou seconde
    // possibilité de changer le nombre de jour dans une année ou un mois
    final public static function seconds(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(self::getAmount(),$option);

        $return['year'] = ($option['daysInYear'] * $option['secondsInDay']);
        $return['month'] = ($option['daysInMonth'] * $option['secondsInDay']);
        $return['day'] = $option['secondsInDay'];
        $return['hour'] = $option['secondsInHour'];
        $return['minute'] = $option['secondsInMinute'];
        $return['second'] = 1;

        return $return;
    }


    // getAmount
    // retourne le tableau amount, spécifié dans config
    final public static function getAmount():array
    {
        return self::$config['amount'];
    }


    // getStr
    // retourne le tableau pour la méthode, mergé avec celui de lang si disponible
    final public static function getStr(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('date/str'),Lang::dateStr(null,$lang));
    }


    // getMonths
    // retourne le tableau de mois, mergé avec celui de lang si disponible
    final public static function getMonths(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('date/month'),Lang::dateMonth(null,$lang));
    }


    // getDays
    // retourne le tableau des jours, mergé avec celui de lang si disponible
    final public static function getDays(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('date/day'),Lang::dateDay(null,$lang));
    }


    // getDaysShort
    // retourne le tableau des jours courts, mergé avec celui de lang si disponible
    final public static function getDaysShort(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('date/dayShort'),Lang::dateDayShort(null,$lang));
    }


    // local
    // retourne le tableau associatif pour localtime
    final public static function local($value=null,bool $assoc=true):?array
    {
        $return = null;
        $value = self::time($value);

        if(is_int($value))
        $return = localtime($value,$assoc);

        return $return;
    }


    // timeOfDay
    // retourne le tableau associatif time of day
    final public static function timeOfDay():array
    {
        return gettimeofday(false);
    }


    // now
    // retourne le timestamp courant
    // il faut utiliser getTimestamp pour avoir le temps du début de requête
    final public static function now():int
    {
        return time();
    }


    // microtime
    // retourne le microtime comme float
    final public static function microtime():float
    {
        return microtime(true);
    }


    // nowWithDecimal
    // retourne un tableau avec le timestamp et la décimale
    final public static function nowWithDecimal():array
    {
        $return = [];
        $microtime = microtime();
        $array = explode(' ', $microtime);
        $return['now'] = (int) $array[1];
        $return['decimal'] = (float) $array[0];

        return $return;
    }


    // strtotime
    // alias pour strtotime
    final public static function strtotime(string $format,$value=null):?int
    {
        $return = null;
        $value = self::time($value);

        if(is_int($value) && strlen($format))
        {
            $time = strtotime($format,$value);
            if(is_int($time))
            $return = $time;
        }

        return $return;
    }


    // getLocaleFormat
    // retourne le format pour locale
    final public static function getLocaleFormat(string $return):string
    {
        if(array_key_exists($return,self::$config['format']['locale']))
        $return = self::$config['format']['locale'][$return];

        return $return;
    }


    // localeFormat
    // format une date via la fonction strftime ou gmstrftime
    // le format doit être string, pas de support pour timezone
    // le format peut être un raccourci pour le tableau config
    final public static function localeFormat(string $format,$value=null):?string
    {
        $return = null;
        $value = self::time($value);

        if(is_int($value) && strlen($format))
        $return = strftime(self::getLocaleFormat($format),$value);

        return $return;
    }


    // gmtLocaleFormat
    // format une date via la fonction gmstrftime, le temps retourné est timezone GMT
    // le format doit être string
    final public static function gmtLocaleFormat(string $format,$value=null):?string
    {
        $return = null;
        $value = self::time($value);

        if(is_int($value) && strlen($format))
        $return = gmstrftime(self::getLocaleFormat($format),$value);

        return $return;
    }


    // getFormats
    // retourne les formats de date en mergant avec lang, si disponible
    final public static function getFormats(?string $lang=null):array
    {
        return Arr::replace(self::$config['format']['date'],Lang\En::getConfig('date/format'),Lang::dateFormat(null,$lang));
    }


    // getFormat
    // retourne le format selon la clé et le type
    // si clé est true, c'est le format par défaut 0
    final public static function getFormat($key,?string $lang=null):?string
    {
        $return = null;
        $formats = self::getFormats($lang);

        if(is_array($formats) && !empty($formats))
        {
            if($key === true)
            $key = 0;

            if(is_int($key) || is_string($key))
            {
                if(array_key_exists($key,$formats))
                $return = $formats[$key];

                elseif(is_string($key) && strlen($key))
                $return = $key;
            }
        }

        return $return;
    }


    // getFormatReplace
    // retourne le format et le remplacement selon la clé et le type
    // si clé est true, c'est le format par défaut
    final public static function getFormatReplace($key,?string $lang=null):?array
    {
        $return = null;
        $format = self::getFormat($key,$lang);

        if(is_string($format))
        {
            $return = ['format'=>$format,'replace'=>null];

            if(!empty(self::$config['format']['replace']))
            {
                foreach (self::$config['format']['replace'] as $char => $array)
                {
                    if(is_string($char) && is_array($array) && array_key_exists('call',$array) && strpos($format,$char) !== false)
                    {
                        if(self::isCallable($array['call']))
                        $return['replace'][$char] = $array['call']($lang);

                        if(array_key_exists('map',$array) && self::isCallable($array['map']))
                        {
                            $args = (array_key_exists('args',$array))? (array) $array['args']:[];
                            $callable = $array['map'];
                            $closure = fn($v) => $callable($v,...$args);

                            $return['replace'][$char] = Arr::map($return['replace'][$char],$closure);
                        }
                    }
                }
            }
        }

        return $return;
    }


    // setFormat
    // ajoute ou modifie un format dans les config
    final public static function setFormat($key,string $value):void
    {
        if((is_string($key) || is_int($key)) && !empty($value))
        self::$config['format']['date'][$key] = $value;
    }


    // unsetFormat
    // enlève un format des config
    final public static function unsetFormat($key):void
    {
        if((is_string($key) || is_int($key)) && array_key_exists($key,self::$config['format']['date']))
        unset(self::$config['format']['date'][$key]);
    }


    // parseFormat
    // retourne un tableau contenant le format, le timezone et le replace en vue d'un formattage ou d'un parse
    final public static function parseFormat($key):array
    {
        $return = [];
        $format = null;
        $timezone = null;
        $replace = null;
        $lang = null;

        if(is_scalar($key))
        $format = $key;

        elseif(is_array($key))
        {
            $format = Arr::keysFirstValue(['format',0],$key);
            $timezone = Arr::keysFirstValue(['timezone',1],$key);
            $replace = Arr::keysFirstValue(['replace',2],$key);
            $lang = Arr::keysFirstValue(['lang',3],$key);
        }

        if(is_scalar($format) || is_scalar($timezone))
        {
            $format = self::getFormatReplace($format,$lang);
            $return['format'] = $format['format'] ?? null;
            $return['timezone'] = (is_scalar($timezone) && !empty($timezone))? $timezone:null;
            $return['replace'] = $format['replace'] ?? null;

            if(is_array($replace) && !empty($replace) && Arrs::is($replace))
            $return['replace'] = Arrs::replace($return['replace'],$replace);
        }

        return $return;
    }


    // format
    // format une date via la fonction date ou gmdate
    // les formats sont définis dans config avec le type date
    // pour le support timezone, utilise date_create
    // remplacement peut être effectué avant le retour
    // possible de spécifier le format from (de value)
    final public static function format($format=true,$value=null,$from=null):?string
    {
        $return = null;
        $value = self::time($value,$from);
        $format = self::parseFormat($format);

        if(is_int($value) && is_array($format) && !empty($format['format']))
        {
            if($format['timezone'] === true)
            $return = gmdate($format['format'],$value);

            elseif(is_string($format['timezone']))
            {
                $date = date_create('@'.$value);
                date_timezone_set($date,timezone_open($format['timezone']));
                $return = date_format($date,$format['format']);
            }

            else
            $return = date($format['format'],$value);

            if(!empty($format['replace']) && is_array($format['replace']))
            $return = self::formatReplace($return,$format['replace']);
        }

        return $return;
    }


    // formatDayStart
    // permet de formatter une date
    // le format est différent si le timestamp est en début de jour (seconde 0 du jour)
    final public static function formatDayStart($start=null,?array $option=null):?string
    {
        $return = null;
        $option = Arr::plus(['format'=>'ymdhis','formatDay'=>'ymd'],$option);
        $start = self::time($start);

        if(is_int($start))
        {
            if(self::isDayStart($start) === true)
            $return = self::format($option['formatDay'],$start);
            else
            $return = self::format($option['format'],$start);
        }

        return $return;
    }


    // formatStartEnd
    // permet de faire un formattage de date à partir de deux variables
    // le jour n'est pas répété si les deux dates sont dans la même journée
    // format différent si start et/ou end sont début du jour
    final public static function formatStartEnd($start=null,$end=null,?array $option=null):?string
    {
        $return = null;
        $option = Arr::plus(['format'=>'ymdhis','formatTime'=>'his','formatDay'=>'ymd','dayStart'=>true,'separator'=>' - '],$option);
        $start = self::time($start);
        $end = self::time($end);

        if(is_int($start))
        {
            $startDayStart = ($option['dayStart'] === true && self::isDayStart($start));

            if(is_int($end) && $end > $start)
            {
                $endDayStart = ($option['dayStart'] === true && self::isDayStart($end));

                if($startDayStart === true && $endDayStart === true)
                {
                    $return = self::format($option['formatDay'],$start);
                    $return .= $option['separator'];
                    $return .= self::format($option['formatDay'],$end);
                }

                else
                {
                    $return = self::format($option['format'],$start);
                    $return .= $option['separator'];

                    if(self::isDay($start,null,$end))
                    $return .= self::format($option['formatTime'],$end);

                    else
                    $return .= self::format($option['format'],$end);
                }
            }

            else
            {
                $format = ($startDayStart === true)? 'formatDay':'format';
                $return = self::format($option[$format],$start);
            }
        }

        return $return;
    }


    // gmtFormat
    // format une date via la fonction gmdate, le temps retourné est timezone GMT
    // les formats sont définis dans config avec le type date
    // remplacement peut être effectué avant le retour
    final public static function gmtFormat($format=true,$value=null):?string
    {
        return self::format(Arr::plus((array) $format,['timezone'=>true]),$value);
    }


    // formats
    // permet de formatter un tableau unidimmensionnel avec plusieurs dates
    final public static function formats($format,array $return):array
    {
        return Arr::map($return,fn($value) => self::format($format,$value));
    }


    // formatReplace
    // fait un remplacement après les méthodes format
    // replace peut être un array ou une callable
    final public static function formatReplace(string $return,array $replace)
    {
        if(strlen($return) && !empty($replace))
        {
            foreach ($replace as $key => $value)
            {
                if(is_string($key) && strlen($key) && is_array($value) && !empty($value))
                {
                    $value = Arr::keysWrap($key,null,$value);
                    $return = Str::replace($value,$return);
                }
            }
        }

        return $return;
    }


    // getLocale
    // retourne la locale
    final public static function getLocale(?string $lang=null):string
    {
        return Lang::dateLocale($lang) ?? Lang\En::getConfig('date/placeholder');
    }


    // getPlaceholders
    // retourne les placeholders de format de date en mergant avec lang, si disponible
    final public static function getPlaceholders(?string $lang=null):array
    {
        return Arr::plus(Lang\En::getConfig('date/placeholder'),Lang::datePlaceholder(null,$lang));
    }


    // placeholder
    // retourne un placeholder de format de date
    final public static function placeholder($value,?string $lang=null):?string
    {
        $return = null;
        $placeholders = self::getPlaceholders($lang);

        if(!empty($placeholders) && Arr::isKey($value) && array_key_exists($value,$placeholders))
        $return = $placeholders[$value];

        return $return;
    }


    // dmy
    // formatte une date au format dmy
    final public static function dmy($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['dmy',$timezone],self::time($value,$format));
    }


    // ymd
    // formatte une date au format ymd
    final public static function ymd($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['ymd',$timezone],self::time($value,$format));
    }


    // ymdhis
    // formatte une date au format ymdhis
    final public static function ymdhis($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['ymdhis',$timezone],self::time($value,$format));
    }


    // ym
    // formatte une date au format ym
    final public static function ym($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['ym',$timezone],self::time($value,$format));
    }


    // his
    // formatte une date au format his
    final public static function his($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['his',$timezone],self::time($value,$format));
    }


    // rfc822
    // formatte une date au format rfc822
    final public static function rfc822($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['rfc822',$timezone],self::time($value,$format));
    }


    // sql
    // formatte une date au format sql
    final public static function sql($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['sql',$timezone],self::time($value,$format));
    }


    // dateToSecond
    // formatte une date au format dateToSecond
    final public static function dateToSecond($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['dateToSecond',$timezone],self::time($value,$format));
    }


    // compact
    // formatte une date au format compact
    final public static function compact($value=null,$timezone=null,$format=null):?string
    {
        return self::format(['compact',$timezone],self::time($value,$format));
    }


    // gmt
    // formatte une date au format gmt
    final public static function gmt($value=null,$format=null):?string
    {
        return self::gmtFormat('gmt',self::time($value,$format));
    }


    // iformat
    // format une date et retourne un int
    // la longueur de key ne peut être que un
    // pour support timezone, renvoie à la méthode format
    final public static function iformat($format,$value=null):?int
    {
        $return = null;
        $value = self::time($value);
        $format = self::parseFormat($format);

        if(is_int($value) && is_array($format) && !empty($format['format']) && strlen($format['format']) === 1)
        {
            if(!empty($format['timezone']))
            {
                $return = self::format($format,$value);

                if(is_string($return))
                $return = (int) $return;
            }

            else
            $return = idate($format['format'],$value);
        }

        return $return;
    }


    // countDaysInMonth
    // retourne le nombre de jour dans un mois
    final public static function countDaysInMonth($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['daysInMonth',$timezone],self::time($value,$format));
    }


    // weekDay
    // retourne le numéro du jour dans la semaine
    final public static function weekDay($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['weekDay',$timezone],self::time($value,$format));
    }


    // weekNo
    // retourne le numéro de la semaine dans l'année
    // possible de mettre dimanche plutôt que lundi comme premier jour de la semaine
    final public static function weekNo($value=null,bool $sundayFirst=false,$timezone=null,$format=null):?int
    {
        $return = self::iformat(['weekNo',$timezone],self::time($value,$format));

        if(is_int($return) && $sundayFirst === true)
        {
            $weekDay = self::weekDay($value,$timezone,$format);
            if($weekDay === 0)
            {
                if($return >= 52)
                $return = 1;

                else
                $return++;
            }
        }

        return $return;
    }


    // year
    // retourne l'année d'un timestamp en int
    final public static function year($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['year',$timezone],self::time($value,$format));
    }


    // month
    // retourne le mois d'un timestamp en int
    final public static function month($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['month',$timezone],self::time($value,$format));
    }


    // day
    // retourne le jour d'un timestamp en int
    final public static function day($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['day',$timezone],self::time($value,$format));
    }


    // hour
    // retourne l'heure d'un timestamp en int
    final public static function hour($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['hour',$timezone],self::time($value,$format));
    }


    // minute
    // retourne la minute d'un timestamp en int
    final public static function minute($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['minute',$timezone],self::time($value,$format));
    }


    // second
    // retourne la seconde d'un timestamp en int
    final public static function second($value=null,$timezone=null,$format=null):?int
    {
        return self::iformat(['second',$timezone],self::time($value,$format));
    }


    // parse
    // retourne un tableau détaillé à partir d'une date formatté
    // possible de remplacer avant le parse
    // retourne null s'il y a une erreur dans le parse
    final public static function parse($format,$value):?array
    {
        $return = null;
        $format = self::parseFormat($format);
        $value = Str::cast($value);

        if(is_array($format) && !empty($format['format']))
        {
            if(!empty($format['replace']) && is_array($format['replace']))
            $value = self::parseReplace($value,$format['replace']);

            $parse = date_parse_from_format($format['format'],$value);

            if(empty($parse['error_count']))
            $return = self::parsePrepare($parse);
        }

        return $return;
    }


    // parseMake
    // retourne un timestamp à partir d'une date formatté
    // possible de remplacer avant le parse
    // retourne null s'il y a une erreur dans le parse
    // la date peut être parse et converti dans timezone
    final public static function parseMake($format,$value):?int
    {
        $return = null;
        $parse = self::parse($format,$value);

        if(!empty($parse))
        $return = self::make($parse,self::parseFormat($format)['timezone'] ?? null);

        return $return;
    }


    // parseStr
    // parse une string compatible avec strtotime et retourne un tableau ou null
    // retourne null s'il y a une erreur dans le parse
    // les éléments relatifs seront additionnés dans le tableau de retour
    final public static function parseStr($value):?array
    {
        $return = null;
        $value = Str::cast($value);
        $parse = date_parse($value);

        if(empty($parse['error_count']))
        $return = self::parsePrepare($parse);

        return $return;
    }


    // parseReplace
    // fait un remplacement avant la méthode parse
    final public static function parseReplace(string $return,array $replace):string
    {
        if(strlen($return) && !empty($replace))
        {
            foreach ($replace as $key => $value)
            {
                if(is_string($key) && strlen($key) && is_array($value) && !empty($value))
                {
                    $value = array_flip($value);
                    $value = Arr::valuesWrap($key,$key,$value);
                    $return = Str::replace($value,$return);
                }
            }
        }

        return $return;
    }


    // parsePrepare
    // méthode pour préparer un tableau parse
    // enlève les clés commeçant par warning ou error
    // enlève les clés dont les valeurs sont false ou ''
    // ajuste les éléments de dates relatifs au besoin
    final public static function parsePrepare(array $return):array
    {
        foreach ($return as $key => $value)
        {
            $keep = true;

            if((strpos($key,'warning') === 0 || strpos($key,'error') === 0) && empty($value))
            $keep = false;

            elseif($key === 'fraction' && empty($value))
            $keep = false;

            elseif($value === false || $value === '')
            $keep = false;

            elseif($key === 'relative' && is_array($value))
            {
                $keep = false;

                foreach ($value as $k => $v)
                {
                    if(is_int($v) && !empty($v) && array_key_exists($k,$return))
                    $return[$k] += $v;
                }
            }

            if($keep === false)
            unset($return[$key]);
        }

        return $return;
    }


    // time
    // retourne un timestamp
    // un format avec timezone peut être fourni pour le parse
    // convertYear permet de convertir une année valide en timestamp, faux par défaut
    final public static function time($value=null,$format=null,bool $convertYear=false):?int
    {
        $return = null;
        $value = Obj::cast($value);

        if($value === null)
        $return = self::now();

        elseif(is_array($value))
        $return = self::make($value,self::parseFormat($format)['timezone'] ?? null);

        elseif($format !== null && is_scalar($value))
        $return = self::parseMake($format,$value);

        elseif(is_numeric($value))
        {
            $return = (int) $value;

            if($convertYear === true && self::isYearValid($return))
            $return = self::make([$return],self::parseFormat($format)['timezone'] ?? null);
        }

        return $return;
    }


    // get
    // parse un timestamp et retourne son année, mois, jour, heure, minute et seconde
    // s'il y a une timezone spécifié, change et remet le timezone par défaut
    final public static function get($value=null,$timezone=null):array
    {
        $return = [];
        $value = self::time($value);

        if(is_int($value))
        {
            if(!empty($timezone))
            Timezone::set($timezone);

            $get = getdate($value);

            if(!empty($timezone))
            Timezone::reset();

            if(!empty($get))
            {
                foreach (['year'=>'year','mon'=>'month','mday'=>'day','hours'=>'hour','minutes'=>'minute','seconds'=>'second'] as $k => $v)
                {
                    if(array_key_exists($k,$get) && is_int($get[$k]))
                    $return[$v] = $get[$k];
                }
            }
        }

        return $return;
    }


    // keep
    // garde un nombre déterminé de slice du tableau
    // commence par les valeurs plus grande (année, mois, jour, etc)
    // capture seulement les valeurs plus grande que zéro
    // si la valeur initial est un tableau, ne la passe pas dans get
    // peut être utilisé par diff
    final public static function keep(int $amount=2,$value=null):?array
    {
        $return = null;
        $value = (!is_array($value))? self::get($value):$value;

        if(is_array($value) && !empty($value) && $amount > 0)
        {
            $return = [];
            $i = 0;

            foreach ($value as $k => $v)
            {
                if(is_int($v) && $v > 0)
                {
                    $return[$k] = $v;
                    $i++;

                    if($i === $amount)
                    break;
                }
            }
        }

        return $return;
    }


    // str
    // passe value dans la méthode keep
    // les différentes clés du tableau sont passés dans lang
    // retourne une string
    final public static function str(int $amount=2,$value=null,?string $lang=null):string
    {
        $return = '';
        $str = self::getStr($lang);
        $keep = self::keep($amount,$value);

        if(!empty($str))
        {
            $i = 0;
            $count = count($keep);

            foreach ($keep as $key => $value)
            {
                $i++;

                if(is_string($key) && array_key_exists($key,$str) && is_int($value) && $value > 0)
                {
                    if($i === $count && !empty($return) && array_key_exists('and',$str))
                    $return .= ' '.$str['and'];

                    $return .= (!empty($return))? ' ':'';
                    $return .= (string) $value;
                    $return .= ' ';
                    $return .= Str::plural($value,$str[$key]);
                }
            }
        }

        return $return;
    }


    // make
    // construit un timestamp via un tableau
    // l'ordre des index du tableau n'est pas le même que mktime
    // possibilité de spécifier un timezone, true est GMT
    final public static function make(array $value,$timezone=null):?int
    {
        $return = null;
        $floor = self::$config['floor'];
        $year = idate('Y');
        $month = $floor['month'];
        $day = $floor['day'];
        $hour = $floor['hour'];
        $minute = $floor['minute'];
        $second = $floor['second'];

        foreach (['year','month','day','hour','minute','second'] as $i => $v)
        {
            if(array_key_exists($v,$value) && is_numeric($value[$v]))
            $$v = (int) $value[$v];

            elseif(array_key_exists($i,$value) && is_numeric($value[$i]))
            $$v = (int) $value[$i];
        }

        if(is_int($year) && $year >= 0)
        {
            if($timezone === true)
            $return = gmmktime($hour,$minute,$second,$month,$day,$year);

            else
            {
                if(is_string($timezone))
                Timezone::set($timezone);

                $return = mktime($hour,$minute,$second,$month,$day,$year);

                if(is_string($timezone))
                Timezone::reset();
            }
        }

        return $return;
    }


    // gmtMake
    // construit un timestamp d'une date GMT via un tableau
    final public static function gmtMake(array $value):?int
    {
        return self::make($value,true);
    }


    // mk
    // comme make mais le premier argument n'est pas un tableau
    final public static function mk(?int $year=null,?int $month=null,?int $day=null,?int $hour=null,?int $minute=null,?int $second=null,$timezone=null)
    {
        return self::make([$year,$month,$day,$hour,$minute,$second],$timezone);
    }


    // add
    // permet d'additionner ou soustraire des composantes temps de la valeur
    // possibilité d'utiliser un format en entrée
    final public static function add(array $values,$value=null,$format=null):?int
    {
        $return = false;
        $value = self::time($value,$format);

        if(!empty($values) && is_int($value))
        {
            $get = self::get($value);

            foreach ($values as $key => $value)
            {
                if(array_key_exists($key,$get) && is_numeric($value))
                $get[$key] += (int) $value;
            }

            $return = self::make($get);
        }

        return $return;
    }


    // change
    // permet de changer des composantes temps de la valeur
    // possibilité d'utiliser un format en entrée
    final public static function change(array $values,$value=null,$format=null):?int
    {
        $return = null;
        $value = self::time($value,$format);

        if(!empty($values) && is_int($value))
        {
            $get = self::get($value);

            if(!empty($get))
            {
                $get = Arr::replace($get,$values);
                $return = self::make($get);
            }
        }

        return $return;
    }


    // remove
    // permet d'enlever des composantes temps de la valeur
    // possibilité d'utiliser un format en entrée
    final public static function remove(array $values,$value=null,$format=null):?int
    {
        $return = null;
        $value = self::time($value,$format);

        if(!empty($values) && is_int($value))
        {
            $get = self::get($value);

            if(!empty($get))
            {
                foreach ($values as $v)
                {
                    if(is_string($v) && array_key_exists($v,$get))
                    {
                        $val = (in_array($v,['day','month'],true))? 1:0;
                        $get[$v] = $val;
                    }
                }

                $return = self::make($get);
            }
        }

        return $return;
    }


    // getFloor
    // retourne le tableau des données pour une date ceil
    // une date floor est la première seconde possible dans une période spécifié
    final public static function getFloor(string $period,$value=null,$format=null):?array
    {
        $return = null;
        $ceil = self::$config['floor'];
        $value = self::time($value,$format);

        if(is_int($value))
        {
            $get = self::get($value);

            if(!empty($get))
            {
                $return = $get;
                $do = false;

                foreach ($return as $k => $v)
                {
                    if($do === true && array_key_exists($k,$ceil))
                    $return[$k] = $ceil[$k];

                    if($k === $period)
                    $do = true;
                }
            }
        }

        return $return;
    }


    // getCeil
    // retourne le tableau des données pour une date ceil
    // une date ceil est la dernière seconde possible dans une période spécifié
    final public static function getCeil(string $period,$value=null,$format=null):?array
    {
        $return = null;
        $ceil = self::$config['ceil'];
        $value = self::time($value,$format);

        if(is_int($value))
        {
            $get = self::get($value);

            if(!empty($get))
            {
                $return = $get;
                $do = false;

                foreach ($return as $k => $v)
                {
                    if($do === true)
                    {
                        if($k === 'day')
                        $return[$k] = self::countDaysInMonth(['year'=>$return['year'],'month'=>$return['month']]);

                        elseif(array_key_exists($k,$ceil))
                        $return[$k] = $ceil[$k];
                    }

                    if($k === $period)
                    $do = true;
                }
            }
        }

        return $return;
    }


    // getFloorCeil
    // retourne un tableau avec les valeur tableau floor et ceil d'une date
    final public static function getFloorCeil(string $period,$value=null,$format=null):array
    {
        $return = [];
        $return['floor'] = self::getFloor($period,$value,$format);
        $return['ceil'] = self::getCeil($period,$value,$format);

        return $return;
    }


    // floor
    // retourne une date floor en timestamp
    final public static function floor(string $period,$value=null,$format=null):?int
    {
        $return = null;
        $floor = self::getFloor($period,$value,$format);

        if(is_array($floor))
        $return = self::make($floor);

        return $return;
    }


    // ceil
    // retourne une date ceil en timestamp
    final public static function ceil(string $period,$value=null,$format=null):?int
    {
        $return = null;
        $ceil = self::getCeil($period,$value,$format);

        if(is_array($ceil))
        $return = self::make($ceil);

        return $return;
    }


    // floorCeil
    // retourne un tableau avec les valeur floor et ceil d'une date
    final public static function floorCeil(string $period,$value=null,$format=null):array
    {
        return [
            'floor'=>self::floor($period,$value,$format),
            'ceil'=>self::ceil($period,$value,$format)
        ];
    }


    // addYear
    // permet d'additionner ou soustraire l'année d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addYear(int $change,$value=null,$format=null):?int
    {
        return self::add(['year'=>$change],$value,$format);
    }


    // changeYear
    // permet de changer l'année d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeYear(int $change,$value=null,$format=null):?int
    {
        return self::change(['year'=>$change],$value,$format);
    }


    // removeYear
    // permet d'enlever l'année d'une valeur date, devient 2000
    // possibilité d'utiliser un format en entrée
    final public static function removeYear($value=null,$format=null):?int
    {
        return self::remove(['year'],$value,$format);
    }


    // floorYear
    // permet de floor l'année d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function floorYear($value=null,$format=null):?int
    {
        return self::floor('year',$value,$format);
    }


    // ceilYear
    // permet de ceil l'année d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function ceilYear($value=null,$format=null):?int
    {
        return self::ceil('year',$value,$format);
    }


    // floorCeilYear
    // retourne le tableau floor ceil d'une année
    // possibilité d'utiliser un format en entrée
    final public static function floorCeilYear($value=null,$format=null):array
    {
        return self::floorCeil('year',$value,$format);
    }


    // addMonth
    // permet d'additionner ou soustraire le mois d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addMonth(int $change,$value=null,$format=null):?int
    {
        return self::add(['month'=>$change],$value,$format);
    }


    // changeMonth
    // permet de changer le mois d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeMonth(int $change,$value=null,$format=null):?int
    {
        return self::change(['month'=>$change],$value,$format);
    }


    // removeMonth
    // permet d'enlever le mois d'une valeur date, devient 1
    // possibilité d'utiliser un format en entrée
    final public static function removeMonth($value=null,$format=null):?int
    {
        return self::remove(['month'],$value,$format);
    }


    // floorMonth
    // permet de floor le mois d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function floorMonth($value=null,$format=null):?int
    {
        return self::floor('month',$value,$format);
    }


    // ceilMonth
    // permet de ceil le mois d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function ceilMonth($value=null,$format=null):?int
    {
        return self::ceil('month',$value,$format);
    }


    // floorCeilMonth
    // retourne le tableau floor ceil d'un mois
    // possibilité d'utiliser un format en entrée
    final public static function floorCeilMonth($value=null,$format=null):array
    {
        return self::floorCeil('month',$value,$format);
    }


    // addDay
    // permet d'additionner ou soustraire le jour d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addDay(int $change,$value=null,$format=null):?int
    {
        return self::add(['day'=>$change],$value,$format);
    }


    // changeDay
    // permet de changer le jour d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeDay(int $change,$value=null,$format=null):?int
    {
        return self::change(['day'=>$change],$value,$format);
    }


    // removeDay
    // permet d'enlever le jour d'une valeur date, devient 1
    // possibilité d'utiliser un format en entrée
    final public static function removeDay($value=null,$format=null):?int
    {
        return self::remove(['day'],$value,$format);
    }


    // floorDay
    // permet de floor le jour d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function floorDay($value=null,$format=null):?int
    {
        return self::floor('day',$value,$format);
    }


    // ceilDay
    // permet de ceil le jour d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function ceilDay($value=null,$format=null):?int
    {
        return self::ceil('day',$value,$format);
    }


    // floorCeilDay
    // retourne le tableau floor ceil d'un jour
    // possibilité d'utiliser un format en entrée
    final public static function floorCeilDay($value=null,$format=null):array
    {
        return self::floorCeil('day',$value,$format);
    }


    // addHour
    // permet d'additionner ou soustraire l'heure d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addHour(int $change,$value=null,$format=null):?int
    {
        return self::add(['hour'=>$change],$value,$format);
    }


    // changeHour
    // permet de changer l'heure d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeHour(int $change,$value=null,$format=null):?int
    {
        return self::change(['hour'=>$change],$value,$format);
    }


    // removeHour
    // permet d'enlever l'heure d'une valeur date, devient 0
    // possibilité d'utiliser un format en entrée
    final public static function removeHour($value=null,$format=null):?int
    {
        return self::remove(['hour'],$value,$format);
    }


    // floorHour
    // permet de floor l'heure d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function floorHour($value=null,$format=null):?int
    {
        return self::floor('hour',$value,$format);
    }


    // ceilHour
    // permet de ceil l'heure d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function ceilHour($value=null,$format=null):?int
    {
        return self::ceil('hour',$value,$format);
    }


    // floorCeilHour
    // retourne le tableau floor ceil d'une heure
    // possibilité d'utiliser un format en entrée
    final public static function floorCeilHour($value=null,$format=null):array
    {
        return self::floorCeil('hour',$value,$format);
    }


    // addMinute
    // permet d'additionner ou soustraire les minutes d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addMinute(int $change,$value=null,$format=null):?int
    {
        return self::add(['minute'=>$change],$value,$format);
    }


    // changeMinute
    // permet de changer les minutes d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeMinute(int $change,$value=null,$format=null):?int
    {
        return self::change(['minute'=>$change],$value,$format);
    }


    // removeMinute
    // permet d'enlever la minute d'une valeur date, devient 0
    // possibilité d'utiliser un format en entrée
    final public static function removeMinute($value=null,$format=null):?int
    {
        return self::remove(['minute'],$value,$format);
    }


    // floorMinute
    // permet de floor la minute d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function floorMinute($value=null,$format=null):?int
    {
        return self::floor('minute',$value,$format);
    }


    // ceilMinute
    // permet de ceil la minute d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function ceilMinute($value=null,$format=null):?int
    {
        return self::ceil('minute',$value,$format);
    }


    // floorCeilMinute
    // retourne le tableau floor ceil d'une minute
    // possibilité d'utiliser un format en entrée
    final public static function floorCeilMinute($value=null,$format=null):array
    {
        return self::floorCeil('minute',$value,$format);
    }


    // addSecond
    // permet d'additionner ou soustraire les secondes d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function addSecond(int $change,$value=null,$format=null):?int
    {
        return self::add(['second'=>$change],$value,$format);
    }


    // changeSecond
    // permet de changer les secondes d'une valeur date
    // possibilité d'utiliser un format en entrée
    final public static function changeSecond(int $change,$value=null,$format=null):?int
    {
        return self::change(['second'=>$change],$value,$format);
    }


    // removeSecond
    // permet d'enlever la minute d'une valeur date, devient 0
    // possibilité d'utiliser un format en entrée
    final public static function removeSecond($value=null,$format=null):?int
    {
        return self::remove(['second'],$value,$format);
    }


    // diff
    // retourne la différence entre deux dates
    // par défaut value2 est 0
    // l'ordre des valeurs n'a pas d'importance
    // peut spécifier un format et timezone pour chaque valeur
    final public static function diff($value=null,$value2=0,$format=null,$format2=null):?array
    {
        $return = null;
        $value = self::time($value,$format);
        $value2 = self::time($value2,$format2);

        if(is_int($value) && is_int($value2))
        {
            $diff = date_diff(date_create("@$value"),date_create("@$value2"));

            if(!empty($diff))
            {
                $return = [];
                $return['year'] = $diff->y;
                $return['month'] = $diff->m;
                $return['day'] = $diff->d;
                $return['hour'] = $diff->h;
                $return['minute'] = $diff->i;
                $return['second'] = $diff->s;
            }
        }

        return $return;
    }


    // diffTotal
    // comme diff mais retourne la différence total pour année, mois, jour, heure, minute et seconde
    // ceil à true permet d'arrondir vers le haut les différentes valeurs du tableau si une des valeurs plus petite n'est pas vide
    // time ne permet pas les années (4 chiffres)
    // peut spécifier un format et timezone pour chaque valeur
    final public static function diffTotal($value=null,$value2=0,bool $ceil=true,$format=null,$format2=null):?array
    {
        $return = null;
        $value = self::time($value,$format);
        $value2 = self::time($value2,$format2);
        $amount = self::getAmount();

        if(is_int($value) && is_int($value2))
        {
            $diff = date_diff(date_create("@$value"),date_create("@$value2"));

            if(!empty($diff))
            {
                $return = [];
                $return['year'] = $diff->y;
                $return['month'] = $diff->m + ($return['year'] * $amount['monthsInYear']);
                $return['day'] = $diff->days;
                $return['hour'] = $diff->h + ($return['day'] * $amount['hoursInDay']);
                $return['minute'] = $diff->i + ($return['hour'] * $amount['minutesInHour']);
                $return['second'] = $diff->s + ($return['minute'] * $amount['secondsInMinute']);

                if($ceil === true)
                {
                    $return['year'] += ($return['month'] || $return['day'] || $return['hour'] || $return['minute'] || $return['second'])? 1:0;
                    $return['month'] += ($return['day'] || $return['hour'] || $return['minute'] || $return['second'])? 1:0;
                    $return['day'] += ($return['hour'] || $return['minute'] || $return['second'])? 1:0;
                    $return['hour'] += ($return['minute'] || $return['second'])? 1:0;
                    $return['minute'] += ($return['second'])? 1:0;
                }
            }
        }

        return $return;
    }


    // diffKeep
    // garde seulement un nombre de valeur du tableau de différence
    final public static function diffKeep(int $amount=2,$value=null,$value2=0,$format=null,$format2=null):?array
    {
        $return = null;
        $diff = self::diff($value,$value2,$format,$format2);

        if(!empty($diff))
        $return = self::keep($amount,$diff);

        return $return;
    }


    // diffStr
    // garde seulement un nombre de valeur du tableau de différence
    // retourne une version string de la différence
    final public static function diffStr(int $amount=2,$value=null,$value2=0,$format=null,$format2=null):string
    {
        $return = '';
        $diff = self::diff($value,$value2,$format,$format2);

        if(!empty($diff))
        $return = self::str($amount,$diff);

        return $return;
    }


    // ago
    // comme diff mais par défaut value2 est null et value doit être plus petit que timestamp
    // peut spécifier un format et timezone pour la valeur
    // possible de garder seulement un nombre de valeur du tableau de différence
    final public static function ago($value=null,$format=null,?int $amount=null):?array
    {
        $return = null;
        $value = self::time($value,$format);

        if($value <= self::time(null))
        {
            $return = self::diff($value,null,$format);

            if(!empty($return) && is_int($amount))
            $return = self::keep($amount,$return);
        }

        return $return;
    }


    // agoStr
    // comme diff mais par défaut value2 est null et value doit être plus petit que timestamp
    // garde seulement un nombre de valeur du tableau de différence
    // retourne une version string de la différence
    final public static function agoStr(int $amount=2,$value=null,$format=null):string
    {
        $return = '';
        $diff = self::ago($value,$format);

        if(!empty($diff))
        $return = self::str($amount,$diff);

        return $return;
    }


    // diffNow
    // comme diff mais par défaut value2 est null, donc timestamp courant
    // peut spécifier un format et timezone pour la valeur
    // possible de garder seulement un nombre de valeur du tableau de différence
    final public static function diffNow($value=null,$format=null,?int $amount=null):?array
    {
        $return = self::diff($value,null,$format);

        if(!empty($return) && is_int($amount))
        $return = self::keep($amount,$return);

        return $return;
    }


    // diffNowStr
    // comme diff mais par défaut value2 est null, donc timestamp courant
    // garde seulement un nombre de valeur du tableau de différence
    // retourne une version string de la différence
    final public static function diffNowStr(int $amount=2,$value=null,$format=null):string
    {
        $return = '';
        $diff = self::diffNow($value,$format);

        if(!empty($diff))
        $return = self::str($amount,$diff);

        return $return;
    }


    // amount
    // cette méthode permet de donner un temps non lié à un timestamp
    // retourne un tableau diff avec nombre de seconde, minute, etc
    // possible de garder seulement un nombre de valeur du tableau de différence
    final public static function amount(int $value,?int $amount=null):?array
    {
        return self::diffNow(($value + self::now()),null,$amount);
    }


    // amountStr
    // comme amountKeep mais retourne plutôt une string formatté
    final public static function amountStr(int $amount,int $value):string
    {
        $return = '';
        $diff = self::amount($value);

        if(!empty($diff))
        $return = self::str($amount,$diff);

        return $return;
    }


    // calendar
    // construit un tableau multidimensionnel calendrier avec le numéro de la semaine, les numéos de jour dans la semaine et les timestamp
    // possible de mettre dimanche comme premier jour de la semaine si sundayFirst est true
    // possible de remplir les trous dans le calendrier avec des mois des autres jours si fill est true
    final public static function calendar($value=null,bool $sundayFirst=false,bool $fill=false):array
    {
        $return = [];
        $daysInMonths = self::daysInMonth($value);

        foreach ($daysInMonths as $day => $timestamp)
        {
            $weekNo = self::weekNo($timestamp,$sundayFirst);
            $weekDay = self::weekDay($timestamp);

            $return[$weekNo][$weekDay] = $timestamp;
        }

        if($fill === true && !empty($return))
        $return = self::fillCalendar($return);

        return $return;
    }


    // fillCalendar
    // remplit les trous dans le tableau multidimnesionnel calendrier
    final public static function fillCalendar(array $return):array
    {
        foreach ($return as $weekNo => $weekDays)
        {
            if(is_int($weekNo) && is_array($weekDays) && !empty($weekDays) && count($weekDays) !== 7)
            {
                $firstKey = key($weekDays);

                // manque à la fin
                if($firstKey === 0)
                {
                    for ($i=0; $i < 7; $i++)
                    {
                        if(array_key_exists($i,$weekDays))
                        $target = $weekDays[$i];

                        elseif(is_int($target))
                        {
                            $target = self::addDay(1,$target);
                            $weekDays[$i] = $target;
                        }
                    }
                }

                // manque au début
                else
                {
                    $target = current($weekDays);
                    $weekDays = Arr::keysMissing($weekDays,null,0);

                    foreach ($weekDays as $k => $v)
                    {
                        if($v === null)
                        $weekDays[$k] = self::addDay(-($firstKey - $k),$target);
                    }
                }

                $return[$weekNo] = $weekDays;
            }
        }

        return $return;
    }


    // daysDiff
    // retourne la différence de jour entre deux valeurs
    // peut spécifier un format et timezone pour chaque valeur
    // la différence est toujours retourner dans un entier positif
    final public static function daysDiff($min=null,$max=null,$format=null,$format2=null):?int
    {
        $return = null;
        $min = self::floorDay($min,$format);
        $max = self::floorDay($max,$format2);

        if(is_int($min) && is_int($max))
        {
            $return = 0;
            $minDate = date_create("@$min");
            $maxDate = date_create("@$max");

            $diff = date_diff($minDate,$maxDate);
            if(!empty($diff->days))
            $return = $diff->days;
        }

        return $return;
    }


    // monthsDiff
    // retourne la différence de mois entre deux valeurs
    // peut spécifier un format et timezone pour chaque valeur
    // la différence est toujours retourner dans un entier positif
    final public static function monthsDiff($min=null,$max=null,$format=null,$format2=null):?int
    {
        $return = null;
        $amount = self::getAmount();
        $min = self::floorMonth($min,$format);
        $max = self::floorMonth($max,$format2);

        if(is_int($min) && is_int($max))
        {
            $yearMaxMin = (self::year($max) - self::year($min));
            $monthMaxMin = (self::month($max) - self::month($min));
            $calc = ($yearMaxMin * $amount['monthsInYear']) + $monthMaxMin;
            $return = abs($calc);
        }

        return $return;
    }


    // yearsDiff
    // retourne la différence d'années entre deux valeurs
    // peut spécifier un format et timezone pour chaque valeur
    // la différence est toujours retourner dans un entier positif
    final public static function yearsDiff($min=null,$max=null,$format=null,$format2=null):?int
    {
        $return = null;
        $min = self::floorYear($min,$format);
        $max = self::floorYear($max,$format2);

        if(is_int($min) && is_int($max))
        {
            $calc = self::year($max) - self::year($min);
            $return = abs($calc);
        }

        return $return;
    }


    // days
    // retourne un tableau de jour entre deux valeurs
    // possibilité de changer interval
    // si un format est spécifié, le timestamp devient la clé et le format la valeur
    // format seulement utilisé pour le output
    // cette méthode est inclusive: max est inclu
    // floor est true par défaut
    final public static function days($min=null,$max=null,?int $interval=null,$format=null,bool $floor=true):array
    {
        $return = [];
        $diff = self::daysDiff($min,$max);
        $interval ??= 1;
        $min = ($floor === true)? self::floorDay($min):self::time($min,null);
        $max = ($floor === true)? self::floorDay($max):self::time($max,null);

        if(is_int($diff) && is_int($min))
        {
            $diff++;
            $timestamp = $min;

            if($min > $max)
            $interval = -$interval;

            $range = Integer::range(1,$diff,$interval);

            foreach ($range as $v)
            {
                $key = $v;

                if($format !== null)
                $return[$timestamp] = self::format($format,$timestamp);
                else
                $return[$key] = $timestamp;

                $timestamp = self::addDay($interval,$timestamp);
            }
        }

        return $return;
    }


    // secondsInDay
    // retourne le nombre de secondes dans le jour
    // retourne 0 si le temps est au début du jour
    final public static function secondsInDay($value=null,$format=null):?int
    {
        $return = null;
        $value = self::time($value,$format);

        if(is_int($value))
        {
            $floor = self::floorDay($value);

            if($value === $floor)
            $return = 0;

            elseif($value > $floor)
            $return = $value - $floor;
        }

        return $return;
    }


    // daysInMonth
    // retourne un tableau de jours dans le mois de la valeur
    // possibilité de changer interval
    // si un format est spécifié, le timestamp devient la clé et le format la valeur
    // format seulement utilisé pour le output
    final public static function daysInMonth($value=null,?int $interval=null,$format=null):array
    {
        $return = [];
        $interval ??= 1;
        $value = self::time($value);

        if(is_int($value))
        {
            $year = self::year($value);
            $month = self::month($value);
            $daysInMonth = self::countDaysInMonth($value);
            $range = Integer::range(1,$daysInMonth,$interval);

            foreach ($range as $v)
            {
                $key = $v;
                $timestamp = self::make([$year,$month,$v]);

                if($format !== null)
                $return[$timestamp] = self::format($format,$timestamp);
                else
                $return[$key] = $timestamp;
            }
        }

        return $return;
    }


    // daysDefault
    // retourne un tableau de jour par défaut
    // possibilité de changer interval
    final public static function daysDefault(int $min=1,?int $max=null,?int $interval=null):array
    {
        return Integer::range($min,$max ?? self::$config['amount']['daysInMonth'],$interval ?? 1);
    }


    // months
    // retourne un tableau de mois entre deux valeurs
    // possibilité de changer interval
    // si un format est spécifié, le timestamp devient la clé et le format la valeur
    // format seulement utilisé pour le output
    // cette méthode est inclusive: max est inclu
    // floor est true par défaut
    final public static function months($min=null,$max=null,?int $interval=null,$format=null,bool $floor=true):array
    {
        $return = [];
        $diff = self::monthsDiff($min,$max);
        $interval ??= 1;
        $min = ($floor === true)? self::floorMonth($min):self::time($min,null);
        $max = ($floor === true)? self::floorMonth($max):self::time($max,null);

        if(is_int($diff) && is_int($min))
        {
            $diff++;
            $timestamp = $min;

            if($min > $max)
            $interval = -$interval;

            $range = Integer::range(1,$diff,$interval);

            foreach ($range as $v)
            {
                $key = $v;

                if($format !== null)
                $return[$timestamp] = self::format($format,$timestamp);
                else
                $return[$key] = $timestamp;

                $timestamp = self::addMonth($interval,$timestamp);
            }
        }

        return $return;
    }


    // monthsInYear
    // retourne un tableau de mois dans une année
    // possibilité de changer interval
    // si un format est spécifié, le timestamp devient la clé et le format la valeur
    // format seulement utilisé pour le output
    // peut convertir les années
    final public static function monthsInYear($value=null,?int $interval=null,$format=null):array
    {
        $return = [];
        $interval ??= 1;
        $value = self::time($value,null,true);
        $amount = self::getAmount();

        if(is_int($value))
        {
            $year = self::year($value);
            $range = Integer::range(1,$amount['monthsInYear'],$interval);

            foreach ($range as $v)
            {
                $key = $v;
                $timestamp = self::make([$year,$v]);

                if($format !== null)
                $return[$timestamp] = self::format($format,$timestamp);
                else
                $return[$key] = $timestamp;
            }
        }

        return $return;
    }


    // monthsDefault
    // retourne un tableau de mois par défaut
    // possibilité de changer interval
    final public static function monthsDefault(int $min=1,?int $max=null,?int $interval=null):array
    {
        return Integer::range($min,$max ?? self::$config['amount']['monthsInYear'],$interval ?? 1);
    }


    // years
    // retourne un tableau d'années entre min et max
    // possibilité de changer interval
    // si un format est spécifié, le timestamp devient la clé et le format la valeur
    // format seulement utilisé pour le output
    // cette méthode est inclusive: max est inclu
    // floor est true par défaut
    final public static function years($min=null,$max=null,?int $interval=null,$format=null,bool $floor=true):array
    {
        $return = [];
        $interval ??= 1;
        $diff = self::yearsDiff($min,$max);
        $min = ($floor === true)? self::floorYear($min):self::time($min,null);
        $max = ($floor === true)? self::floorYear($max):self::time($max,null);

        if(is_int($diff) && is_int($min))
        {
            $diff++;
            $timestamp = $min;

            if($min > $max)
            $interval = -$interval;

            $range = Integer::range(1,$diff,$interval);

            foreach ($range as $v)
            {
                $key = $v;

                if($format !== null)
                $return[$timestamp] = self::format($format,$timestamp);
                else
                $return[$key] = $timestamp;

                $timestamp = self::addYear($interval,$timestamp);
            }
        }

        return $return;
    }


    // yearsDefault
    // retourne un tableau d'années par défaut
    // retourne un nombre d'années avant et après l'année courante
    // possibilité de changer interval
    final public static function yearsDefault(int $before=-100,int $after=30,?int $interval=null,$format=null):array
    {
        $return = [];
        $year = self::year();
        $before = self::make([$year + $before]);
        $after = self::make([$year + $after]);

        $return = self::years($before,$after,$interval,$format);

        return $return;
    }


    // toMicrotime
    // transforme un timestamp en microtime
    final public static function toMicrotime(float $value):float
    {
        return $value * 1000;
    }


    // fromMicrotime
    // transforme un microtime en timestamp
    final public static function fromMicrotime(float $value):int
    {
        return (int) ($value / 1000);
    }
}

// init
Datetime::setInitTimestamp(Datetime::time());
Datetime::setInitMicrotime(Datetime::microtime());
?>