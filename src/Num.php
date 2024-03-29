<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// num
// class with static methods to work with strings, ints and floats numbers
final class Num extends Root
{
    // config
    protected static array $config = [
        'intMaxLength'=>11, // longueur maximale d'une int pour le cast
        'alias'=>[ // alias de méthode pour math
            'avg'=>'average',
            'average'=>'average',
            'min'=>'min',
            'max'=>'max'],
        'symbol'=>[ // symbol pour méthode math
            '+'=>'addition',
            '-'=>'subtraction',
            '*'=>'multiplication',
            '**'=>'pow',
            '/'=>'division',
            '%'=>'modulo',
            '>'=>'max',
            '<'=>'min']
    ];


    // typecast
    // typecasts des valeurs par référence, utilise castMore donc les float sont cast
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = self::castMoreOrNull($value);
        }
    }


    // cast
    // retourne la valeur dans le type (int ou float) si celle ci est numérique
    // castFloat permet de remplacer la virgule par un décimal et de cast les float, castFloat est false par défaut car risqué
    final public static function cast($value,bool $more=false,bool $commaToDecimal=false,bool $cleanDecimal=true)
    {
        $return = null;

        if(is_int($value))
        $return = $value;

        elseif(is_scalar($value))
        {
            $return = $value;
            $string = Str::cast($value);

            // commaToDecimal
            if($commaToDecimal === true)
            $string = Str::commaToDecimal($string);

            // cleanDecimal
            if($cleanDecimal === true)
            $string = Str::cleanDecimal($string);

            if(self::isReallyNumeric($string))
            {
                $stringLength = strlen($string);
                $float = (float) $string;
                $int = (int) $float;
                $hasDot = (strpos($string,'.') !== false);

                // string si longueur trop longue
                if($hasDot === false && $stringLength > self::$config['intMaxLength'])
                $return = $string;

                // si premier caractère est zéro, contient plus d'un caractère et extra est false
                elseif($hasDot === false && $more === false && $stringLength > 1 && $string[0] === '0')
                $return = $string;

                // si valeur égale à int
                elseif($int == $string)
                $return = $int;

                // si valeur égale à int
                elseif($float == $string)
                $return = ($more === true || is_float($value))? $float:$string;
            }
        }

        return $return;
    }


    // castMore
    // comme cast mais castFloat est true
    final public static function castMore($value,bool $commaToDecimal=true,?bool $cleanDecimal=true)
    {
        return static::cast($value,true,$commaToDecimal,$cleanDecimal);
    }


    // castMoreOrNull
    // comme cast mais retourne null si la valeur de retour n'est pas numérique
    final public static function castMoreOrNull($value,bool $commaToDecimal=true,?bool $cleanDecimal=true)
    {
        $return = self::castMore($value,$commaToDecimal,$cleanDecimal);
        return (is_numeric($return))? $return:null;
    }


    // is
    // retourne vrai si la valeur est numerique
    final public static function is($value):bool
    {
        return is_numeric($value);
    }


    // isEmpty
    // retourne vrai si la valeur est numerique et vide
    final public static function isEmpty($value):bool
    {
        return is_numeric($value) && empty($value);
    }


    // isNotEmpty
    // retourne vrai si la valeur est numerique et non vide
    final public static function isNotEmpty($value):bool
    {
        return is_numeric($value) && !empty($value);
    }


    // isString
    // retourne vrai si la valeur est numérique et string
    final public static function isString($value):bool
    {
        return is_numeric($value) && is_string($value);
    }


    // isFinite
    // retourne vrai si le nombre est fini
    final public static function isFinite($value):bool
    {
        return is_numeric($value) && is_finite((float) $value);
    }


    // isInfinite
    // retourne vrai si le nombre est infini
    final public static function isInfinite($value):bool
    {
        return is_numeric($value) && is_infinite((float) $value);
    }


    // isNan
    // retourne vrai si le nombre est nan
    final public static function isNan($value):bool
    {
        return is_numeric($value) && is_nan((float) $value);
    }


    // isReallyNumeric
    // retourne vrai si la valeur est réelement numérique, va retourner faux à quelque chose que comme 21E1
    final public static function isReallyNumeric($value):bool
    {
        return is_numeric($value) && Str::keepNum((string) $value) === (string) $value;
    }


    // isPositive
    // vérifie que la valeur est un chiffre positif
    // si allowZero est true, retourne true si zero
    final public static function isPositive($value,bool $allowZero=false):bool
    {
        $value = self::castMoreOrNull($value);
        return $value !== null && ($value > 0 || ($allowZero === true && $value === 0));
    }


    // isNegative
    // vérifie que la valeur est un chiffre négatif
    // si allowZero est true, retourne true si zero
    final public static function isNegative($value,bool $allowZero=false):bool
    {
        $value = self::castMoreOrNull($value);
        return $value !== null && ($value < 0 || ($allowZero === true && $value === 0));
    }


    // isOdd
    // vérifie que la valeur est un chiffre impair
    final public static function isOdd($value):bool
    {
        $value = self::castMoreOrNull($value);
        return is_int($value) && is_float($value / 2);
    }


    // isEven
    // vérifie que la valeur est un chiffre pair
    final public static function isEven($value):bool
    {
        $value = self::castMoreOrNull($value);
        return is_int($value) && is_int($value / 2);
    }


    // isOverflow
    // vérifie que la valeur est numérique et une string après cast
    // peu signifé que le numéro est out of bounds (plus grand que php_int_max)
    final public static function isOverflow($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            $value = self::castMore($value);
            $return = is_string($value);
        }

        return $return;
    }


    // isLength
    // retourne vrai si la length est celle spécifié
    final public static function isLength(int $length,$value):bool
    {
        $value = self::castMoreOrNull($value);
        return $value !== null && self::len($value) === $length;
    }


    // isMinLength
    // retourne vrai si la length est plus grande ou égale que celle spécifié
    final public static function isMinLength(int $length,$value):bool
    {
        $value = self::castMoreOrNull($value);
        return $value !== null && self::len($value) >= $length;
    }


    // isMaxLength
    // retourne vrai si la length est plus petite ou égale que celui spécifié
    final public static function isMaxLength(int $length,$value):bool
    {
        $value = self::castMoreOrNull($value);
        return $value !== null && self::len($value) <= $length;
    }


    // isCountable
    // retourne vrai si la valeur est countable par la méthode count
    // cette méthode est inutile en php 7.3
    final public static function isCountable($value):bool
    {
        return is_countable($value);
    }


    // append
    // ajoute des chaînes numériques une après l'autre
    final public static function append(...$values)
    {
        $return = Str::append(...$values);
        return self::castMoreOrNull($return);
    }


    // commaToDecimal
    // transforme la virgule en decimal et cast
    final public static function commaToDecimal($value)
    {
        $return = null;

        if(is_scalar($value))
        {
            $return = Str::commaToDecimal((string) $value);
            $return = self::castMoreOrNull($return);
        }

        return $return;
    }


    // len
    // retourne la longeur de la chaîne numérique
    final public static function len($value):?int
    {
        $value = self::castMoreOrNull($value);
        return ($value !== null)? Str::len((string) $value):null;
    }


    // sub
    // coupe un numéro avec un début et un length
    final public static function sub(int $start,?int $length,$value)
    {
        $return = null;
        $value = self::castMoreOrNull($value);

        if($value !== null)
        {
            $return = Str::sub($start,$length,(string) $value);
            $return = self::castMoreOrNull($return);
        }

        return $return;
    }


    // decimal
    // similaire à round, mais n'arrondie rien (coupe seulement à la décimale spécifiée)
    // de même, la valeur est retourne en string et non pas float
    // possible de cleaner les decimal vide (0)
    final public static function decimal($value,int $decimal=2,bool $clean=false):?string
    {
        $return = null;
        $value = Floating::cast($value);

        if($value !== null)
        {
            $return = number_format($value,$decimal,'.','');

            if($clean === true)
            $return = Str::cleanDecimal($return);
        }

        return $return;
    }


    // round
    // arrondie un chiffre
    final public static function round($value,int $round=0,int $mode=PHP_ROUND_HALF_UP)
    {
        $return = null;
        $value = Floating::cast($value);

        if($value !== null)
        {
            $return = round($value,$round,$mode);
            $return = self::castMoreOrNull($return);
        }

        return $return;
    }


    // roundLower
    // arrondie un chiffre
    final public static function roundLower($value,int $round=0)
    {
        return self::round($value,$round,PHP_ROUND_HALF_DOWN);
    }


    // ceil
    // amène le float au int plus grand
    final public static function ceil($value):?int
    {
        $value = Floating::cast($value);
        return ($value !== null)? (int) ceil($value):null;
    }


    // floor
    // amène le float au int plus petit
    final public static function floor($value):?int
    {
        $value = Floating::cast($value);
        return ($value !== null)? (int) floor($value):null;
    }


    // positive
    // retourne la version positive d'un chiffre
    final public static function positive($value)
    {
        $value = self::castMoreOrNull($value);
        return ($value !== null)? abs($value):null;
    }


    // negative
    // retourne la version négative d'un chiffre
    final public static function negative($value)
    {
        $value = self::castMoreOrNull($value);
        return ($value !== null)? (abs($value) * -1):null;
    }


    // invert
    // inverse un chiffre (négatif devient positif ou vice versa)
    final public static function invert($value)
    {
        $return = null;
        $value = self::castMoreOrNull($value);

        if($value !== null)
        {
            $abs = abs($value);
            $return = ($value < 0)? $abs:($abs * -1);
        }

        return $return;
    }


    // increment
    // augment une valeur
    final public static function increment($value,$amount=1)
    {
        $return = null;
        self::typecast($value,$amount);

        if($value !== null && $amount !== null)
        $return = $value + $amount;

        return $return;
    }


    // decrement
    // réduit une valeur
    final public static function decrement($value,$amount=1)
    {
        $return = null;
        self::typecast($value,$amount);

        if($value !== null && $amount !== null)
        $return = $value - $amount;

        return $return;
    }


    // in
    // vérifie si un numéro est entre les valeurs incluses dans from et to
    final public static function in($from,$value,$to,bool $inclusive=true):?bool
    {
        $return = null;
        self::typecast($from,$value,$to);

        if($value !== null && $from !== null && $to !== null)
        {
            if($inclusive === true)
            $return = ($value >= $from && $value <= $to);

            else
            $return = ($value > $from && $value < $to);
        }

        return $return;
    }


    // inExclusive
    // comme in, mais est inclusif
    final public static function inExclusive($from,$value,$to):?bool
    {
        return self::in($from,$value,$to,false);
    }


    // pi
    // retourne pi, peut être arrondi
    final public static function pi(?int $round=null):float
    {
        $return = M_PI;
        return (is_int($round))? self::round($return,$round):$return;
    }


    // math
    // calcule mathématique entre plusieurs valeurs
    // symbol sont + - * / avg et average
    final public static function math(string $operation,array $values,?int $round=null)
    {
        $return = null;

        if(!empty(self::$config['symbol'][$operation]))
        $method = self::$config['symbol'][$operation];

        elseif(!empty(self::$config['alias'][$operation]))
        $method = self::$config['alias'][$operation];

        if(is_string($method) && self::classHasMethod($method))
        {
            $values = array_values($values);
            $return = self::$method(...$values);

            if(is_numeric($round))
            $return = self::round($return,$round);
        }

        return $return;
    }


    // mathCommon
    // méthode commune utilisé par plusieurs fonctions de calculations
    protected static function mathCommon(string $symbol,int $minArgs=1,...$args)
    {
        $return = null;

        if(count($args) >= $minArgs)
        {
            $return = self::castMoreOrNull($args[0]);

            if($return !== null)
            {
                unset($args[0]);

                foreach ($args as $v)
                {
                    $v = self::castMoreOrNull($v);

                    if($v !== null)
                    {
                        if($symbol === '+')
                        $return += $v;

                        elseif($symbol === '-')
                        $return -= $v;

                        elseif($symbol === '*')
                        $return *= $v;

                        elseif($symbol === '**')
                        $return **= $v;

                        elseif($symbol === '%')
                        $return %= $v;

                        elseif($symbol === '/')
                        {
                            if($v == 0)
                            {
                                $return = null;
                                break;
                            }

                            else
                            $return /= $v;
                        }
                    }
                }
            }
        }

        return $return;
    }


    // combine
    // combine des tableaux en faisant une opération mathématique sur leurs valeurs numériques
    // les valeurs non numériques ne sont pas conservés
    final public static function combine(string $operation,array ...$values):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            foreach ($value as $k => $v)
            {
                if(is_numeric($v))
                {
                    if(!array_key_exists($k,$return))
                    $return[$k] = $v;

                    else
                    $return[$k] = self::math($operation,[$return[$k],$v]);
                }
            }
        }

        return $return;
    }


    // addition
    // performe une addition entre plusieurs valeurs
    // peut y avoir une seule valeur
    final public static function addition(...$args)
    {
        return self::mathCommon('+',1,...$args);
    }


    // subtraction
    // performe une soustraction entre plusieurs valeurs
    // peut y avoir une seule valeur
    final public static function subtraction(...$args)
    {
        return self::mathCommon('-',1,...$args);
    }


    // multiplication
    // performe une multiplication entre plusieurs valeurs
    // doit au moins y avoir deux valeurs
    final public static function multiplication(...$args)
    {
        return self::mathCommon('*',2,...$args);
    }


    // pow
    // performe une expression exponentielle
    // doit au moins y a voir deux valeurs
    final public static function pow(...$args)
    {
        return self::mathCommon('**',2,...$args);
    }


    // division
    // performe une division entre plusieurs valeurs
    // doit au moins y avoir deux valeurs
    final public static function division(...$args)
    {
        return self::mathCommon('/',2,...$args);
    }


    // modulo
    // performe un calcul modulo, retourne le reste d'une division
    // doit au moins y a voir deux valeurs
    final public static function modulo(...$args)
    {
        return self::mathCommon('%',2,...$args);
    }


    // average
    // performe une moyenne entre deux valeurs
    // doit au moins y avoir deux valeurs
    final public static function average(...$args)
    {
        $return = null;
        $total = 0;
        $count = 0;

        if(count($args) > 1)
        {
            foreach ($args as $v)
            {
                $v = self::castMoreOrNull($v);
                if($v !== null)
                {
                    $total += $v;
                    $count++;
                }
            }

            if($count > 0)
            $return = $total / $count;
        }

        return $return;
    }


    // min
    // retourne la valeur la plus petite
    final public static function min(...$values)
    {
        self::typecast(...$values);
        $values = Arr::filter($values,fn($value) => is_numeric($value));

        return min(...$values);
    }


    // max
    // retourne la valeur la plus grande
    final public static function max(...$values)
    {
        self::typecast(...$values);
        $values = Arr::filter($values,fn($value) => is_numeric($value));

        return max(...$values);
    }


    // random
    // génère un string random en utilisant mt_rand
    final public static function random(?int $length=null,int $min=0,int $max=PHP_INT_MAX,bool $csprng=false):int
    {
        $return = 0;

        if($csprng === true)
        $return = Crypt::randomInt($length,$min,$max);

        else
        {
            $rand = mt_rand($min,$max);

            if(is_int($length))
            $return = self::sub(0,$length,$rand);
        }

        return $return;
    }


    // zerofill
    // retourne une string avec le int zérofill (à gauche)
    final public static function zerofill(int $length,int $value):string
    {
        return Str::padLeft('0',$length,(string) $value);
    }


    // fromOctal
    // transforme une chaîne octale en décimale
    final public static function fromOctal($value):?int
    {
        $return = null;

        if(is_numeric($value))
        $return = octdec((string) $value);

        return $return;
    }


    // toOctal
    // transforme un chiffre en string octal
    final public static function toOctal($value,$format=null)
    {
        $return = null;
        $value = Integer::cast($value);

        if($value !== null)
        {
            $return = decoct($value);

            if(!empty($format))
            {
                if(is_int($format))
                $return = substr($return,$format);

                $return = (int) $return;
            }
        }

        return $return;
    }


    // format
    // format un numéro en string (via number_format)
    // option decimal|separator|thousand
    final public static function format($value,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $value = self::castMoreOrNull($value);
        $option = self::getFormat($lang,$option);

        if(!empty($option) && $value !== null)
        {
            $return = $value;

            if(!is_string($return))
            $return = number_format($return,$option['decimal'],$option['separator'],$option['thousand']);

            if(is_string($return) && !empty($option['output']) && is_string($option['output']))
            $return = str_replace('%v%',$return,$option['output']);
        }

        return $return;
    }


    // formats
    // permet de formater un tableau de valeur
    // supporte format, percentFormat, moneyFormat, phoneFormat et sizeFormat
    final public static function formats(string $type,array $return,?string $lang=null,?array $option=null):array
    {
        $method = self::formatsMethod($type);

        if(is_string($method))
        {
            foreach ($return as $key => $value)
            {
                if($method === 'sizeFormat')
                $return[$key] = self::sizeFormat($value,true,$lang,$option);

                elseif($method === 'phoneFormat')
                $return[$key] = self::phoneFormat($value,$lang,$option);

                else
                $return[$key] = self::$method($value,$lang,$option);
            }
        }

        return $return;
    }


    // formatsMethod
    // retourne la méthode à utiliser pour formats
    final public static function formatsMethod(string $type):?string
    {
        $return = null;

        if(in_array($type,['number','format'],true))
        $return = 'format';

        elseif(in_array($type,['%','percent','percentFormat'],true))
        $return = 'percentFormat';

        elseif(in_array($type,['$','money','moneyFormat'],true))
        $return = 'moneyFormat';

        elseif(in_array($type,['phone','phoneFormat'],true))
        $return = 'phoneFormat';

        elseif(in_array($type,['size','sizeFormat'],true))
        $return = 'sizeFormat';

        return $return;
    }


    // getFormat
    // retourne le tableau de format pour la méthode format
    final public static function getFormat(?string $lang=null,?array $option=null):array
    {
        return Arr::replace(Lang\En::getConfig('number/format'),Lang::numberFormat(null,$lang),$option);
    }


    // percentFormat
    // formate un numéro, ajoute un pourcentage
    final public static function percentFormat($value,?string $lang=null,?array $option=null)
    {
        return self::format($value,$lang,self::getPercentFormat($lang,$option));
    }


    // getPercentFormat
    // retourne le tableau de format pour la méthode moneyFormat
    final public static function getPercentFormat(?string $lang=null,?array $option=null):array
    {
        return Arr::replace(Lang\En::getConfig('number/percentFormat'),Lang::numberPercentFormat(null,$lang),$option);
    }


    // moneyFormat
    // format un numéro en format monétaire (selon une langue défini dans static config)
    // option decimal|separator|thousand
    final public static function moneyFormat($value,?string $lang=null,?array $option=null):?string
    {
        return self::format($value,$lang,self::getMoneyFormat($lang,$option));
    }


    // getMoneyFormat
    // retourne le tableau de format pour la méthode moneyFormat
    final public static function getMoneyFormat(?string $lang=null,?array $option=null):array
    {
        return Arr::replace(Lang\En::getConfig('number/moneyFormat'),Lang::numberMoneyFormat(null,$lang),$option);
    }


    // phoneFormat
    // format un numéro de téléphone
    // ajoute de l'option areaDash, pour ajouter un tiret entre code régional et reste du numéro
    final public static function phoneFormat($value,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $option = self::getPhoneFormat($lang,$option);

        if(is_scalar($value))
        {
            $value = (string) $value;
            $value = Str::keepNumber($value);
            $valueLength = strlen($value);

            if($valueLength >= 10)
            {
                preg_match("/(\d{3})(\d{3})(\d{4})\.?(\d+)?/",$value,$match);
                if(!empty($match[0]) && $match[0] === $value)
                {
                    $return = '';

                    if($option['parenthesis'] === true)
                    $return .= '(';
                    $return .= $match[1];

                    if($option['parenthesis'] === true)
                    $return .= ') ';
                    elseif($option['areaDash'] === true)
                    $return .= '-';
                    else
                    $return .= ' ';

                    $return .= "{$match[2]}-{$match[3]}";

                    // extension
                    if(is_string($option['extension']) && !empty($match[4]))
                    {
                        $return .= ' ';
                        $return .= $option['extension'];
                        $return .= $match[4];
                    }
                }
            }
        }

        return $return;
    }


    // getPhoneFormat
    // retourne le tableau de format pour la méthode phoneFormat
    final public static function getPhoneFormat(?string $lang=null,?array $option=null):array
    {
        return Arrs::replace(Lang\En::getConfig('number/phoneFormat'),Lang::numberPhoneFormat(null,$lang),$option);
    }


    // sizeFormat
    // transforme une entrée de taille numérique en bytes en une taille formatté
    // si round est true, le niveau d'arrondissement est défini dans les config selon le niveau de taille
    final public static function sizeFormat(float $size,$round=true,?string $lang=null,?array $option=null):string
    {
        $return = '';
        $option = self::getSizeFormat($lang,$option);
        $texts = $option['text'];
        $rounds = $option['round'];

        if(is_numeric($size) && $size >= 0 && !empty($option))
        {
            $log = log($size,1024);
            $log = (int) floor($log);
            $pow = pow(1024,$log);

            if(array_key_exists($log,$texts) && is_string($texts[$log]))
            {
                $text = $texts[$log];
                if($log === 0)
                $text .= Str::s($size);

                if($round === true && array_key_exists($log,$rounds) && is_int($rounds[$log]))
                $round = $rounds[$log];

                if(is_int($round))
                $return = self::round(($size / $pow),$round);

                $return = (string) $return;
                $return .= ' '.$text;
            }
        }

        return $return;
    }


    // getSizeFormat
    // retourne le tableau de format pour la méthode sizeFormat
    final public static function getSizeFormat(?string $lang=null,?array $option=null):array
    {
        return Arrs::replace(Lang\En::getConfig('number/sizeFormat'),Lang::numberSizeFormat(null,$lang),$option);
    }


    // fromSizeFormat
    // retourne le nombre de byte à partir d'une string de size format
    // supporte tous les formats décrits dans le tableau sizeFormat
    // utilise le tableau de la langue courante ou le tableau original (anglais)
    // si la string est m, alors considère que c'est mb
    final public static function fromSizeFormat(string $value,?string $lang=null,?array $option=null):?int
    {
        $return = null;
        $formatOriginal = Lang\En::getConfig('number/sizeFormat');
        $format = self::getSizeFormat($lang,$option);
        $alpha = Str::keepAlpha($value);
        $alpha = strtolower($alpha);
        $alpha = Str::stripEnd('s',$alpha,false);
        $value = Str::toInt($value);

        if(!empty($formatOriginal['text']) && strlen($alpha) && is_int($value))
        {
            if($alpha === 'm')
            $key = 2;

            else
            {
                $key = Arr::search($alpha,$formatOriginal['text'],false);

                if($key === null && !empty($format['text']))
                $key = Arr::search($alpha,$format['text'],false);
            }

            if(is_int($key))
            {
                $return = $value;
                $pow = pow(1024,$key);

                if($pow > 0)
                $return *= $pow;
            }
        }

        return $return;
    }


    // fromSizeFormatMb
    // comme sizeFormat, mais traite seulement les MB
    // n'utilisera pas lang
    final public static function fromSizeFormatMb(string $value):?int
    {
        $return = null;
        $value = Str::toInt($value);

        if(is_int($value))
        {
            $pow = pow(1024,2);
            $return = ($value * $pow);
        }

        return $return;
    }


    // percentCalc
    // transforme un tableau numérique en tableau pourcentage
    // si la variable adjustTotal est vrai, le tableau est envoyé dans la méthode percentAdjustTotal
    final public static function percentCalc(array $array,bool $adjustTotal=true,int $round=1,int $total=100):array
    {
        $return = [];
        $count = 0;

        // type cast et count
        foreach ($array as $key => $value)
        {
            $value = self::castMoreOrNull($value);

            if($value !== null)
            {
                $count += $value;
                $array[$key] = $value;
            }
        }

        if($count > 0)
        {
            foreach ($array as $key => $value)
            {
                $calc = (($value / $count) * $total);
                $return[$key] = self::round($calc,$round);
            }

            // adjustTotal
            if($adjustTotal === true)
            $return = self::percentAdjustTotal($return,null,$round,$total);
        }

        return $return;
    }


    // percentAdjustTotal
    // ajuste le total d'un tableau de pourcentage pour que l'addition de toutes les valeurs arrivent à total
    // un ajustement pourrait etre fait sur la clé adjustKey si l'addition n'arrive pas au total
    final public static function percentAdjustTotal(array $return,$adjustKey=null,int $round=1,int $total=100):array
    {
        // type cast et adjustKey
        foreach ($return as $key => $value)
        {
            $value = self::castMoreOrNull($value);

            if($value !== null)
            {
                $array[$key] = $value;

                if($adjustKey === null && $value > 0)
                $adjustKey = $key;
            }
        }

        if((is_string($adjustKey) || is_numeric($adjustKey)) && array_key_exists($adjustKey,$return))
        {
            // calc
            $calc = self::math('+',$return);

            if((float) $calc !== (float) $total)
            {
                if($calc > $total)
                {
                    $number = ($return[$adjustKey] - ($calc - $total));
                    $return[$adjustKey] = self::round($number,$round);

                    if($return[$adjustKey] < 0)
                    $return[$adjustKey] = 0;
                }

                if($calc < $total)
                {
                    $number = ($return[$adjustKey] + ($total - $calc));
                    $return[$adjustKey] = self::round($number,$round);

                    if($return[$adjustKey] > $total)
                    $return[$adjustKey] = $total;
                }
            }
        }

        return $return;
    }


    // compoundInterest
    // permet de calculer les interets sur une somme alors qu'un pourcentage est ajouté chaque itération et conservé
    final public static function compoundInterest(float $return,float $percent,int $iteration=12,bool $add=true):float
    {
        for ($i=0; $i < $iteration; $i++)
        {
            if($add === true)
            $return += ($return * $percent);
            else
            $return -= ($return * $percent);
        }

        return $return;
    }
}
?>