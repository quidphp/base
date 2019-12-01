<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Base;

// num
// class with static methods to work with strings, ints and floats numbers
class Num extends Root
{
    // config
    public static $config = [
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
    // typecasts des valeurs par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = static::cast($value);
        }

        return;
    }


    // typecastInt
    // typecasts des valeurs int par référence
    final public static function typecastInt(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (int) $value;
        }

        return;
    }


    // typecastFloat
    // typecasts des valeurs float par référence
    final public static function typecastFloat(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (float) $value;
        }

        return;
    }


    // cast
    // retourne la valeur dans le type (int ou float) si celle ci est numérique
    // extra permet de remplacer la virgule par un décimal et aussi de forcer le cast des chaînes numériques commençant par zéro
    final public static function cast($value,bool $extra=true)
    {
        $return = null;

        if(is_scalar($value))
        {
            $return = $value;
            $string = Str::cast($value);

            // commaToDecimal
            if($extra === true)
            $string = Str::commaToDecimal($string);

            if(is_numeric($string))
            {
                $stringLength = strlen($string);
                $float = (float) $string;
                $int = (int) $float;

                // string si longueur trop longue
                if(strpos($string,'.') === false && $stringLength > static::$config['intMaxLength'])
                $return = $string;

                // si premier caractère est zéro, contient plus d'un caractère et extra est false
                elseif($extra === false && $stringLength > 1 && $string[0] === '0')
                $return = $string;

                // si valeur égale à int
                elseif($int == $string)
                $return = $int;

                // si valeur égale à int
                elseif($float == $string)
                $return = $float;
            }
        }

        return $return;
    }


    // castToInt
    // comme cast, mais la valeur de retour est soit null ou int
    final public static function castToInt($value,bool $extra=true):?int
    {
        $return = null;
        $value = static::cast($value,$extra);
        if(is_numeric($value))
        $return = (int) $value;

        return $return;
    }


    // castToFloat
    // comme cast, mais la valeur de retour est soit null ou float
    final public static function castToFloat($value,bool $extra=true):?float
    {
        $return = null;
        $value = static::cast($value,$extra);
        if(is_numeric($value))
        $return = (float) $value;

        return $return;
    }


    // castFromString
    // permet de cast une valeur string en gardant seulement ces caractères numériques
    final public static function castFromString(string $value):?int
    {
        $return = null;
        $value = Str::keepNumeric($value);
        $value = static::cast($value);

        if(is_int($value))
        $return = $value;

        return $return;
    }


    // is
    // retourne vrai si la valeur est numerique
    final public static function is($value):bool
    {
        return (is_numeric($value))? true:false;
    }


    // isEmpty
    // retourne vrai si la valeur est numerique et vide
    final public static function isEmpty($value):bool
    {
        return (is_numeric($value) && empty($value))? true:false;
    }


    // isNotEmpty
    // retourne vrai si la valeur est numerique et non vide
    final public static function isNotEmpty($value):bool
    {
        return (is_numeric($value) && !empty($value))? true:false;
    }


    // isString
    // retourne vrai si la valeur est numérique et string
    final public static function isString($value):bool
    {
        return (is_numeric($value) && is_string($value))? true:false;
    }


    // isInt
    // retourne vrai si la valeur est int
    final public static function isInt($value):bool
    {
        return (is_int($value))? true:false;
    }


    // isFloat
    // retourne vrai si la valeur est float
    final public static function isFloat($value):bool
    {
        return (is_float($value))? true:false;
    }


    // isFinite
    // retourne vrai si le nombre est fini
    final public static function isFinite($value):bool
    {
        return (is_numeric($value) && is_finite((float) $value))? true:false;
    }


    // isInfinite
    // retourne vrai si le nombre est infini
    final public static function isInfinite($value):bool
    {
        return (is_numeric($value) && is_infinite((float) $value))? true:false;
    }


    // isNan
    // retourne vrai si le nombre est nan
    final public static function isNan($value):bool
    {
        return (is_numeric($value) && is_nan((float) $value))? true:false;
    }


    // isPositive
    // vérifie que la valeur est un chiffre positif
    final public static function isPositive($value):bool
    {
        $return = false;
        static::typecast($value);

        if($value > 0)
        $return = true;

        return $return;
    }


    // isNegative
    // vérifie que la valeur est un chiffre négatif
    final public static function isNegative($value):bool
    {
        $return = false;
        static::typecast($value);

        if($value < 0)
        $return = true;

        return $return;
    }


    // isOdd
    // vérifie que la valeur est un chiffre impair
    final public static function isOdd($value):bool
    {
        $return = false;
        static::typecast($value);

        if(is_int($value) && is_float($value / 2))
        $return = true;

        return $return;
    }


    // isEven
    // vérifie que la valeur est un chiffre pair
    final public static function isEven($value):bool
    {
        $return = false;
        static::typecast($value);

        if(is_int($value) && is_int($value / 2))
        $return = true;

        return $return;
    }


    // isWhole
    // vérifie que la valeur est numérique et un int après cast
    final public static function isWhole($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            static::typecast($value);

            if(is_int($value))
            $return = true;
        }

        return $return;
    }


    // isWholeNotEmpty
    // vérifie que la valeur est numérique et un int après cast et n'est pas 0
    final public static function isWholeNotEmpty($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            static::typecast($value);

            if(is_int($value) && !empty($value))
            $return = true;
        }

        return $return;
    }


    // isDecimal
    // vérifie que la valeur est numérique et un chiffre flottant après cast
    final public static function isDecimal($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            static::typecast($value);

            if(is_float($value))
            $return = true;
        }

        return $return;
    }


    // isOverflow
    // vérifie que la valeur est numérique et une string après cast
    // peu signifé que le numéro est out of bounds (plus grand que php_int_max)
    final public static function isOverflow($value):bool
    {
        $return = false;

        if(is_numeric($value))
        {
            static::typecast($value);

            if(is_string($value))
            $return = true;
        }

        return $return;
    }


    // isLength
    // retourne vrai si la length est celle spécifié
    final public static function isLength(int $length,$value):bool
    {
        static::typecast($value);
        return (is_numeric($value) && static::len($value) === $length)? true:false;
    }


    // isMinLength
    // retourne vrai si la length est plus grande ou égale que celle spécifié
    final public static function isMinLength(int $length,$value):bool
    {
        static::typecast($value);
        return (is_numeric($value) && static::len($value) >= $length)? true:false;
    }


    // isMaxLength
    // retourne vrai si la length est plus petite ou égale que celui spécifié
    final public static function isMaxLength(int $length,$value):bool
    {
        static::typecast($value);
        return (is_numeric($value) && static::len($value) <= $length)? true:false;
    }


    // isCountable
    // retourne vrai si la valeur est countable par la méthode count
    // cette méthode est inutile en php 7.3
    final public static function isCountable($value):bool
    {
        return is_countable($value);
    }


    // prepend
    // ajoute des chaînes numériques une en arrière de l'autre
    final public static function prepend(...$values)
    {
        $return = Str::prepend(...$values);
        static::typecast($return);

        return $return;
    }


    // append
    // ajoute des chaînes numériques une après l'autre
    final public static function append(...$values)
    {
        $return = Str::append(...$values);
        static::typecast($return);

        return $return;
    }


    // fromBool
    // retourne un numéro à partir d'un boolean
    final public static function fromBool(bool $bool):?int
    {
        $return = null;

        if($bool === true)
        $return = 1;

        elseif($bool === false)
        $return = 0;

        return $return;
    }


    // commaToDecimal
    // transforme la virgule en decimal et cast
    final public static function commaToDecimal($value)
    {
        $return = null;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = Str::commaToDecimal($value);
            static::typecast($return);
        }

        return $return;
    }


    // len
    // retourne la longeur de la chaîne numérique
    final public static function len($value):?int
    {
        return Str::len((string) static::cast($value));
    }


    // sub
    // coupe un numéro avec un début et un length
    final public static function sub(int $start,?int $length=null,$value)
    {
        $return = null;
        static::typecast($value);
        $value = (string) $value;
        $return = Str::sub($start,$length,$value);
        static::typecast($return);

        return $return;
    }


    // round
    // arrondie un chiffre
    final public static function round($value,int $round=0,int $mode=PHP_ROUND_HALF_UP)
    {
        $return = null;
        static::typecast($value);

        $return = round($value,$round,$mode);
        static::typecast($return);

        return $return;
    }


    // roundLower
    // arrondie un chiffre
    final public static function roundLower($value,int $round=0)
    {
        return static::round($value,$round,PHP_ROUND_HALF_DOWN);
    }


    // ceil
    // amène le float au int plus grand
    final public static function ceil($value):?int
    {
        $return = null;
        static::typecast($value);

        $return = (int) ceil($value);

        return $return;
    }


    // floor
    // amène le float au int plus petit
    final public static function floor($value):?int
    {
        $return = null;
        static::typecast($value);

        $return = (int) floor($value);

        return $return;
    }


    // positive
    // retourne la version positive d'un chiffre
    final public static function positive($value)
    {
        $return = null;
        static::typecast($value);

        $return = abs($value);

        return $return;
    }


    // negative
    // retourne la version négative d'un chiffre
    final public static function negative($value)
    {
        $return = false;
        static::typecast($value);

        $return = abs($value) * -1;

        return $return;
    }


    // invert
    // inverse un chiffre (négatif devient positif ou vice versa)
    final public static function invert($value)
    {
        $return = null;
        static::typecast($value);

        if($value < 0)
        $return = abs($value);

        else
        $return = abs($value) * -1;

        return $return;
    }


    // increment
    // augment une valeur
    final public static function increment($value,$amount=1)
    {
        $return = null;
        static::typecast($value,$amount);

        $return = $value + $amount;

        return $return;
    }


    // decrement
    // réduit une valeur
    final public static function decrement($value,$amount=1)
    {
        $return = null;
        static::typecast($value,$amount);

        $return = $value - $amount;

        return $return;
    }


    // in
    // vérifie si un numéro est entre les valeurs incluses dans from et to
    final public static function in($from,$value,$to,bool $inclusive=true):?bool
    {
        $return = null;
        static::typecast($from,$value,$to);

        if($inclusive === true)
        {
            if($value >= $from && $value <= $to)
            $return = true;
        }

        else
        {
            if($value > $from && $value < $to)
            $return = true;
        }

        return $return;
    }


    // inExclusive
    // comme in, mais est inclusif
    final public static function inExclusive($from,$value,$to):?bool
    {
        return static::in($from,$value,$to,false);
    }


    // pi
    // retourne pi, peut être arrondi
    final public static function pi(?int $round=null):float
    {
        $return = M_PI;

        if(is_int($round))
        $return = static::round($return,$round);

        return $return;
    }


    // math
    // calcule mathématique entre plusieurs valeurs
    // symbol sont + - * / avg et average
    final public static function math(string $operation,array $values,?int $round=null)
    {
        $return = null;

        if(!empty(static::$config['symbol'][$operation]))
        $method = static::$config['symbol'][$operation];

        elseif(!empty(static::$config['alias'][$operation]))
        $method = static::$config['alias'][$operation];

        if(is_string($method) && method_exists(static::class,$method))
        {
            $values = array_values($values);
            $return = static::$method(...$values);

            if(is_numeric($round))
            $return = static::round($return,$round);
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
                    $return[$k] = static::math($operation,[$return[$k],$v]);
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
        $return = null;

        if(!empty($args))
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $return += $z;
                }
            }
        }

        return $return;
    }


    // subtraction
    // performe une soustraction entre plusieurs valeurs
    // peut y avoir une seule valeur
    final public static function subtraction(...$args)
    {
        $return = null;

        if(!empty($args))
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $return -= $z;
                }
            }
        }

        return $return;
    }


    // multiplication
    // performe une multiplication entre plusieurs valeurs
    // doit au moins y avoir deux valeurs
    final public static function multiplication(...$args)
    {
        $return = null;

        if(count($args) > 1)
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $return *= $z;
                }
            }
        }

        return $return;
    }


    // pow
    // performe une expression exponentielle
    // doit au moins y a voir deux valeurs
    final public static function pow(...$args)
    {
        $return = null;

        if(count($args) > 1)
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $return **= $z;
                }
            }
        }

        return $return;
    }


    // division
    // performe une division entre plusieurs valeurs
    // doit au moins y avoir deux valeurs
    final public static function division(...$args)
    {
        $return = null;

        if(count($args) > 1)
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);

                    if($z == 0)
                    {
                        $return = null;
                        break;
                    }

                    $return /= $z;
                }
            }
        }

        return $return;
    }


    // modulo
    // performe un calcul modulo, retourne le reste d'une division
    // doit au moins y a voir deux valeurs
    final public static function modulo(...$args)
    {
        $return = null;

        if(count($args) > 1)
        {
            $return = static::cast($args[0]);
            unset($args[0]);

            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $return %= $z;
                }
            }
        }

        return $return;
    }


    // average
    // performe une moyenne entre deux valeurs
    // doit au moins y avoir deux valeurs
    final public static function average(...$args)
    {
        $return = false;
        $total = 0;
        $count = 0;

        if(count($args) > 1)
        {
            foreach ($args as $z)
            {
                if(is_scalar($z))
                {
                    static::typecast($z);
                    $total += $z;
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
        $return = null;

        static::typecast(...$values);
        $values = Arr::validateSlice('numeric',$values);
        $return = min(...$values);

        return $return;
    }


    // max
    // retourne la valeur la plus grande
    final public static function max(...$values)
    {
        $return = null;

        static::typecast(...$values);
        $values = Arr::validateSlice('numeric',$values);
        $return = max(...$values);

        return $return;
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
            $return = static::sub(0,$length,$rand);
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
        {
            $value = (string) $value;
            $return = octdec($value);
        }

        return $return;
    }


    // toOctal
    // transforme un chiffre en string octal
    final public static function toOctal($value,$format=null)
    {
        $return = null;
        static::typecast($value);

        if(is_int($value))
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
        $option = static::getFormat($lang,$option);

        if(!empty($option))
        {
            static::typecast($value);

            if(is_numeric($value))
            {
                if(!is_string($value))
                $return = number_format($value,$option['decimal'],$option['separator'],$option['thousand']);
                else
                $return = $value;

                if(is_string($return) && !empty($option['output']) && is_string($option['output']))
                $return = str_replace('%v%',$return,$option['output']);
            }
        }

        return $return;
    }


    // formats
    // permet de formater un tableau de valeur
    // supporte format, percentFormat, moneyFormat, phoneFormat et sizeFormat
    final public static function formats(string $type,array $return,?string $lang=null,?array $option=null):array
    {
        $method = static::formatsMethod($type);

        if(is_string($method))
        {
            foreach ($return as $key => $value)
            {
                if($method === 'sizeFormat')
                $return[$key] = static::sizeFormat($value,true,$lang,$option);

                elseif($method === 'phoneFormat')
                $return[$key] = static::phoneFormat($value,$lang,$option);

                else
                $return[$key] = static::$method($value,$lang,$option);
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
        return Arr::plus(Lang\En::$config['number']['format'],Lang::numberFormat(null,$lang),$option);
    }


    // percentFormat
    // formate un numéro, ajoute un pourcentage
    final public static function percentFormat($value,?string $lang=null,?array $option=null)
    {
        return static::format($value,$lang,static::getPercentFormat($lang,$option));
    }


    // getPercentFormat
    // retourne le tableau de format pour la méthode moneyFormat
    final public static function getPercentFormat(?string $lang=null,?array $option=null):array
    {
        return Arr::plus(Lang\En::$config['number']['percentFormat'],Lang::numberPercentFormat(null,$lang),$option);
    }


    // moneyFormat
    // format un numéro en format monétaire (selon une langue défini dans static config)
    // option decimal|separator|thousand
    final public static function moneyFormat($value,?string $lang=null,?array $option=null):?string
    {
        return static::format($value,$lang,static::getMoneyFormat($lang,$option));
    }


    // getMoneyFormat
    // retourne le tableau de format pour la méthode moneyFormat
    final public static function getMoneyFormat(?string $lang=null,?array $option=null):array
    {
        return Arr::plus(Lang\En::$config['number']['moneyFormat'],Lang::numberMoneyFormat(null,$lang),$option);
    }


    // phoneFormat
    // format un numéro de téléphone
    final public static function phoneFormat($value,?string $lang=null,?array $option=null):?string
    {
        $return = null;
        $option = static::getPhoneFormat($lang,$option);

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
                    $return .= ')';
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
        return Arrs::replace(Lang\En::$config['number']['phoneFormat'],Lang::numberPhoneFormat(null,$lang),$option);
    }


    // sizeFormat
    // transforme une entrée de taille numérique en bytes en une taille formatté
    // si round est true, le niveau d'arrondissement est défini dans les config selon le niveau de taille
    final public static function sizeFormat(int $size,$round=true,?string $lang=null,?array $option=null):string
    {
        $return = '';
        $option = static::getSizeFormat($lang,$option);
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
                $return = static::round(($size / $pow),$round);

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
        return Arrs::replace(Lang\En::$config['number']['sizeFormat'],Lang::numberSizeFormat(null,$lang),$option);
    }


    // fromSizeFormat
    // retourne le nombre de byte à partir d'une string de size format
    // supporte tous les formats décrits dans le tableau sizeFormat
    // utilise le tableau de la langue courante ou le tableau original (anglais)
    // si la string est m, alors considère que c'est mb
    final public static function fromSizeFormat(string $value,?string $lang=null,?array $option=null):?int
    {
        $return = null;
        $formatOriginal = Lang\En::$config['number']['sizeFormat'];
        $format = static::getSizeFormat($lang,$option);
        $alpha = Str::keepAlpha($value);
        $alpha = strtolower($alpha);
        $alpha = Str::stripEnd('s',$alpha,false);
        $value = static::castFromString($value);

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
        $value = static::castFromString($value);

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
            static::typecast($value);

            if(is_numeric($value))
            {
                $count += $value;
                $array[$key] = $value;
            }
        }

        if($count > 0)
        {
            foreach ($array as $key => $value)
            {
                if(is_numeric($value))
                {
                    $calc = (($value / $count) * $total);
                    $return[$key] = static::round($calc,$round);
                }
            }

            // adjustTotal
            if($adjustTotal === true)
            $return = static::percentAdjustTotal($return,null,$round,$total);
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
            static::typecast($value);

            if(is_numeric($value))
            {
                $array[$key] = $value;

                if($adjustKey === null && $value > 0)
                $adjustKey = $key;
            }
        }

        if((is_string($adjustKey) || is_numeric($adjustKey)) && array_key_exists($adjustKey,$return))
        {
            // calc
            $calc = static::math('+',$return);

            if((float) $calc !== (float) $total)
            {
                if($calc > $total)
                {
                    $number = ($return[$adjustKey] - ($calc - $total));
                    $return[$adjustKey] = static::round($number,$round);

                    if($return[$adjustKey] < 0)
                    $return[$adjustKey] = 0;
                }

                if($calc < $total)
                {
                    $number = ($return[$adjustKey] + ($total - $calc));
                    $return[$adjustKey] = static::round($number,$round);

                    if($return[$adjustKey] > $total)
                    $return[$adjustKey] = $total;
                }
            }
        }

        return $return;
    }
}
?>