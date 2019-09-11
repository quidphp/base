<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// str
// class with static methods to work with strings
class Str extends Root
{
    // config
    public static $config = [
        'charset'=>'UTF-8', // charset, peut être changé via encoding
        'plural'=>['letter'=>'s','wrap'=>'%'], // pour la fonction plural
        'excerpt'=>['suffix'=>'...'], // suffix pour la méthode excerpt
        'trim'=>" \t\n\r\0\x0B", // liste des caractères trimmés par défaut par les fonctions trim
        'search'=>' ', // séparateur pour prepareSearch
        'pointer'=>'/', // séparateur pour pointer
        'loremIpsum'=>[ // contenu source pour la méthode loremIpsum
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Aenean ullamcorper nunc non gravida ornare. Interdum et malesuada fames ac ante ipsum primis in faucibus.',
            'Vestibulum lacinia sapien posuere risus viverra accumsan. Curabitur tempor lorem accumsan nunc scelerisque vehicula.',
            'Donec id enim nibh.',
            'Mauris porta facilisis nibh in condimentum.',
            'Donec et odio sed tortor tincidunt cursus.',
            'Proin imperdiet nisi orci, eget semper mauris rhoncus aliquet.',
            'Nam ac mollis purus. Nullam interdum ligula quis posuere euismod.',
            'Pellentesque vel sollicitudin sapien.',
            'Aenean bibendum lorem id sagittis lacinia.',
            'Nam dui magna, tempus vitae erat et, pretium elementum diam.',
            'Suspendisse scelerisque enim augue, ut scelerisque mi porta et.',
            'In condimentum est eu felis feugiat, sed sagittis nunc aliquet.',
            'Duis venenatis id tellus in congue.',
            'Sed iaculis tincidunt est eget mattis.',
            'Duis ut elit leo.',
            'Vestibulum ligula augue, ullamcorper eu faucibus non, imperdiet a purus.',
            'Vivamus id leo purus.',
            'Vestibulum sodales iaculis convallis.',
            'Nam ut luctus sapien.',
            'Nulla facilisi.',
            'Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.']
    ];


    // typecast
    // typecasts des valeurs par référence
    public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (string) $value;
        }

        return;
    }


    // typecastNotNull
    // typecasts des valeurs par référence
    // ne typecast pas null
    public static function typecastNotNull(&...$values):void
    {
        foreach ($values as &$value)
        {
            if($value !== null)
            $value = (string) $value;
        }

        return;
    }


    // is
    // retourne vrai si la valeur est string
    public static function is($value):bool
    {
        return (is_string($value))? true:false;
    }


    // isEmpty
    // retourne vrai si la valeur est string et vide
    public static function isEmpty($value):bool
    {
        return (is_string($value) && !strlen($value))? true:false;
    }


    // isNotEmpty
    // retourne vrai si la valeur est string et non vide
    public static function isNotEmpty($value):bool
    {
        return (is_string($value) && strlen($value))? true:false;
    }


    // isLength
    // retourne vrai si la length est celle spécifié
    public static function isLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_string($value) && static::len($value,$mb) === $length)? true:false;
    }


    // isMinLength
    // retourne vrai si la length est plus grande ou égale que celle spécifié
    public static function isMinLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_string($value) && static::len($value,$mb) >= $length)? true:false;
    }


    // isMaxLength
    // retourne vrai si la length est plus petite ou égale que celui spécifié
    public static function isMaxLength(int $length,$value,?bool $mb=null):bool
    {
        return (is_string($value) && static::len($value,$mb) <= $length)? true:false;
    }


    // isStart
    // retourne vrai si la chaine contient le needle en début de chaine
    public static function isStart(string $needle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;

            if($sensitive === true)
            $return = (static::pos($needle,$value,0,false) === 0)? true:false;
            else
            $return = (static::ipos($needle,$value,0,true) === 0)? true:false;
        }

        return $return;
    }


    // isStarts
    // retourne vrai si un des needles se retrouvent en début de chaîne
    public static function isStarts(array $needles,$value,bool $sensitive=true):bool
    {
        $return = false;

        foreach ($needles as $needle)
        {
            $return = static::isStart($needle,$value,$sensitive);

            if($return === true)
            break;
        }

        return $return;
    }


    // isEnd
    // retourne vrai si la chaine contient le needle en fin de chaine
    public static function isEnd(string $needle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $mb = ($sensitive === false)? true:false;
            $length = static::len($value,$mb) - static::len($needle,$mb);

            if($sensitive === true)
            $return = (static::posRev($needle,$value,0,false) === $length)? true:false;
            else
            $return = (static::iposRev($needle,$value,0,true) === $length)? true:false;
        }

        return $return;
    }


    // isEnds
    // retoune vrai si un des needles se retrouvent en fin de chaîne
    public static function isEnds(array $needles,$value,bool $sensitive=true):bool
    {
        $return = false;

        foreach ($needles as $needle)
        {
            $return = static::isEnd($needle,$value,$sensitive);

            if($return === true)
            break;
        }

        return $return;
    }


    // isStartEnd
    // retourne vrai si la chaine contient le needle en début et en fin de chaine
    public static function isStartEnd(string $startNeedle,string $endNeedle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = static::isStart($startNeedle,$value,$sensitive);

            if(!empty($return))
            $return = static::isEnd($endNeedle,$value,$sensitive);
        }

        return $return;
    }


    // isPattern
    // retourne vrai si la chaîne respecte le pattern donné en premier argument
    // utilisé pour détecté si *_id match avec session_id par exemple
    // supporte que le caractère char soit au début ou à la fin
    public static function isPattern(string $pattern,$value,string $char='*',bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $len = strlen($value);
            $charLen = strlen($char);

            if($charLen > 0 && $len > 0)
            {
                if(strpos($pattern,$char) === 0)
                {
                    $pattern = substr($pattern,$charLen);
                    if(static::isEnd($pattern,$value,$sensitive))
                    $return = true;
                }

                elseif(strpos($pattern,$char) === (strlen($pattern) - $charLen))
                {
                    $pattern = substr($pattern,0,-$charLen);
                    if(static::isStart($pattern,$value,$sensitive))
                    $return = true;
                }
            }
        }

        return $return;
    }


    // isLatin
    // retourne vrai si la chaîne contient seulement des caractères latin
    // utilisé pour bloquer des formulaires contact qui sont du spam
    public static function isLatin($value):bool
    {
        $return = false;

        if(is_string($value) && !preg_match('/[^\\p{Common}\\p{Latin}]/u', $value))
        $return = true;

        return $return;
    }


    // hasNullByte
    // retourne vrai si la chaîne contient une null byte
    // peut être utilie pour détecter qu'une chaîne contient des caractères binaires
    public static function hasNullByte($value):bool
    {
        $return = false;

        if(is_string($value) && strpos($value,"\0") !== false)
        $return = true;

        return $return;
    }


    // icompare
    // compare que des valeurs sont égales, insensibles à la case
    // peut fournir des strings mais aussi des array uni ou multidimensinnel
    // mb true par défaut
    public static function icompare(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $compare = null;

            foreach ($values as $value)
            {
                $return = false;

                if(is_string($value))
                $value = static::lower($value,true);

                elseif(is_array($value))
                $value = static::map([self::class,'lower'],$value,true);

                if($compare === null)
                {
                    $return = true;
                    $compare = $value;
                }

                elseif($value === $compare)
                $return = true;

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // prepend
    // ajoute des chaînes une en arrière de l'autre
    public static function prepend(...$values):string
    {
        $return = '';
        static::typecast(...$values);

        foreach ($values as $value)
        {
            $return = $value.$return;
        }

        return $return;
    }


    // append
    // ajoute des chaînes une après l'autre
    public static function append(...$values):string
    {
        $return = '';
        static::typecast(...$values);

        foreach ($values as $value)
        {
            $return .= $value;
        }

        return $return;
    }


    // cast
    // cast une valeur en string, que le type soit simple ou complexe
    // possible de forcer un array à impode si un separateur est fourni, sinon toujours json:encode
    // la grosse différence avec cette méthode pour les scalaires, est que false retourne '0' plutôt que ''
    public static function cast($return,?string $separator=null,bool $fixUnicode=false):string
    {
        if(!is_string($return))
        {
            if($return === null)
            $return = '';

            elseif($return === false)
            $return = '0';

            elseif($return === true)
            $return = '1';

            elseif(is_int($return) || is_float($return))
            $return = (string) $return;

            elseif(is_array($return))
            {
                if(is_string($separator) && Arr::isUni($return))
                $return = implode($separator,$return);

                else
                $return = Json::encode($return);
            }

            elseif(is_object($return))
            $return = Crypt::serialize($return);

            elseif(is_resource($return))
            $return = Res::pathToUriOrBase64($return);
        }

        if($fixUnicode === true && is_string($return))
        $return = static::fixUnicode($return);

        return $return;
    }


    // castFix
    // comme cast, mais la valeur fixUnicode est true
    public static function castFix($return,?string $separator=null):string
    {
        return static::cast($return,$separator,true);
    }


    // toNumeric
    // transforme une string en int ou float, ou en string si la longueur dépasse le maximum autorisé
    // extra remplace , en .
    public static function toNumeric(string $value,bool $extra=true)
    {
        $return = null;

        $return = Number::cast($value,$extra);
        if(!is_numeric($return))
        $return = (float) $return;

        return $return;
    }


    // toInt
    // transforme un numéro en string en int
    public static function toInt(string $value):?int
    {
        $return = null;

        $number = Number::cast($value);
        $return = (int) $number;

        return $return;
    }


    // toFloat
    // transforme un numéro en string en float
    // extra remplace , en .
    public static function toFloat(string $value,bool $extra=true):?float
    {
        $return = null;

        $number = Number::cast($value,$extra);
        $return = (float) $number;

        return $return;
    }


    // len
    // count le nombre de caractère dans une string
    public static function len(string $value,?bool $mb=null):int
    {
        $return = 0;
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strlen($value,static::$config['charset']);
        else
        $return = strlen($value);

        return $return;
    }


    // lenWith
    // retourne la longueur de la chaîne tant qu'elle contient les caractères spécifiés dans chars
    public static function lenWith(string $chars,string $str,int $start=0,?int $length=null):int
    {
        $return = 0;

        if(is_int($length))
        $return = strspn($str,$chars,$start,$length);
        else
        $return = strspn($str,$chars,$start);

        return $return;
    }


    // lenWithout
    // retourne la longueur de la chaîne tant qu'elle ne contient pas les caractères spécifiés dans chars
    public static function lenWithout(string $chars,string $str,int $start=0,?int $length=null):int
    {
        $return = 0;

        if(is_int($length))
        $return = strcspn($str,$chars,$start,$length);
        else
        $return = strcspn($str,$chars,$start);

        return $return;
    }


    // pos
    // retourne la position de needle dans la string
    // si offset est string, prend la length de la string
    public static function pos(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = static::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strpos($str,$needle,$offset,static::$config['charset']);
            else
            $pos = strpos($str,$needle,$offset);

            if(is_int($pos))
            $return = $pos;
        }

        return $return;
    }


    // posRev
    // retourne la position inversée de needle dans string
    // si offset est string, prend la length de la string
    public static function posRev(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = static::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strrpos($str,$needle,$offset,static::$config['charset']);
            else
            $pos = strrpos($str,$needle,$offset);

            if(is_int($pos))
            $return = $pos;
        }

        return $return;
    }


    // ipos
    // retourne la position sans tenir compte de la casse
    // si offset est string, prend la length de la string
    public static function ipos(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = static::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_stripos($str,$needle,$offset,static::$config['charset']);

            else
            $pos = stripos($str,$needle,$offset);

            if(is_int($pos))
            $return = $pos;
        }

        return $return;
    }


    // iposRev
    // retourne la position inversée, non sensible à la case
    // si offset est string, prend la length de la string
    // cette logique est à la base du fonctionnement des méthodes avec argument sensitive dans les autres classes
    public static function iposRev(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = static::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strripos($str,$needle,$offset,static::$config['charset']);
            else
            $pos = strripos($str,$needle,$offset);

            if(is_int($pos))
            $return = $pos;
        }

        return $return;
    }


    // posIpos
    // fonction rapide pour faire strpo ou stripos selon un boolean sensitive en troisième argument
    // mb est utilisé pour la version insensible à la case
    public static function posIpos(string $needle,string $str,bool $sensitive=true):?int
    {
        $return = null;

        if($sensitive === true)
        $pos = strpos($str,$needle);

        else
        $pos = mb_stripos($str,$needle,0,static::$config['charset']);

        if(is_int($pos))
        $return = $pos;

        return $return;
    }


    // in
    // retourne vrai si la chaine contient le needle
    public static function in(string $needle,string $str,bool $sensitive=true,int $offset=0):bool
    {
        $return = false;

        if($sensitive === true)
        $position = static::pos($needle,$str,$offset,false);

        else
        $position = static::ipos($needle,$str,$offset,true);

        if(is_numeric($position))
        $return = true;

        return $return;
    }


    // ins
    // retourne vrai si la chaine contient tous les needle
    public static function ins(array $needles,string $str,bool $sensitive=true,int $offset=0):bool
    {
        $return = false;

        if(!empty($needles))
        {
            $return = true;

            foreach ($needles as $needle)
            {
                if($sensitive === true)
                $position = static::pos($needle,$str,$offset,false);

                else
                $position = static::ipos($needle,$str,$offset,true);

                if(!is_numeric($position))
                {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }


    // inFirst
    // retourne le premier needle trouvé dans le chaîne ou null si rien n'est trouvé
    public static function inFirst(array $needles,string $str,bool $sensitive=true,int $offset=0)
    {
        $return = null;

        foreach ($needles as $needle)
        {
            if($sensitive === true)
            $position = static::pos($needle,$str,$offset,false);

            else
            $position = static::ipos($needle,$str,$offset,true);

            if(is_numeric($position))
            {
                $return = $needle;
                break;
            }
        }

        return $return;
    }


    // search
    // permet de faire une recherche dans une string
    // support pour multiples termes via espace par défaut si prepare est true
    // peut être insensible à la case, les accents peuvent aussi être insensibles
    public static function search($needle,string $str,bool $sensitive=true,bool $accentSensitive=true,bool $prepare=false,?string $separator=null):bool
    {
        $return = false;

        if(is_string($needle))
        {
            if($prepare === true)
            $needle = static::prepareSearch($needle,$separator);
            else
            $needle = [$needle];
        }

        if(is_array($needle) && !empty($needle))
        {
            $return = true;

            if($accentSensitive === false)
            $str = static::replaceAccent($str);

            foreach ($needle as $n)
            {
                if(is_string($n) && $accentSensitive === false)
                $n = static::replaceAccent($n);

                if(!is_string($n) || !static::in($n,$str,$sensitive))
                {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }


    // prepareSearch
    // prépare un term de recherche like
    // par défaut support pour espace pour diviser le terme
    public static function prepareSearch($value,?string $separator=null):array
    {
        $return = [];
        $separator = (is_string($separator))? $separator:static::$config['search'];

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = static::explodeTrimClean($separator,$value);
        }

        return $return;
    }


    // sub
    // coupe une chaîne avec un début et un length
    // si offset ou length sont des chaînes, calcule leur longueur avec len
    // si len est null, prend la longueur de value
    public static function sub($offset,$length,string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$value,$offset,$length);
        $offset = (is_string($offset))? static::len($offset,$mb):$offset;
        $length = (is_string($length))? static::len($length,$mb):$length;
        $length = ($length === null)? static::len($value,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($mb === true)
            $return = mb_substr($value,$offset,$length,static::$config['charset']);
            else
            $return = substr($value,$offset,$length);
        }

        return $return;
    }


    // subFirst
    // retourne le premier caractère d'une string
    public static function subFirst(string $str,int $amount=1,?bool $mb=null):string
    {
        return static::sub(0,$amount,$str,$mb);
    }


    // subLast
    // retourne le dernier caractère d'une string
    public static function subLast(string $str,int $amount=1,?bool $mb=null):string
    {
        return static::sub(-$amount,$amount,$str,$mb);
    }


    // cut
    // comme sub mais utilise mb_strcut plutôt que mb_substr
    // donc utilise le nombre de bytes plutôt que de caractères
    // si offset ou length sont des chaînes, calcule leur longueur avec len
    // si len est null, prend la longueur de value
    public static function cut($offset,$length,string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$value,$offset,$length);
        $offset = (is_string($offset))? static::len($offset,$mb):$offset;
        $length = (is_string($length))? static::len($length,$mb):$length;
        $length = ($length === null)? static::len($value,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($mb === true)
            $return = mb_strcut($value,$offset,$length,static::$config['charset']);
            else
            $return = substr($value,$offset,$length);
        }

        return $return;
    }


    // subCount
    // compe le nombre d'occurence d'une sous-chaîne
    // si offset ou length sont des chaînes, calculent leur longueur avec strlen
    public static function subCount(string $needle,string $value,$offset=null,$length=null,?bool $mb=null):int
    {
        $return = 0;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$value,$offset,$length);
        $offset = (is_string($offset))? static::len($offset,$mb):$offset;
        $length = (is_string($length))? static::len($length,$mb):$length;

        if($offset !== null)
        $value = static::sub($offset,$length,$value,$mb);

        if($mb === true)
        $return = mb_substr_count($value,$needle,static::$config['charset']);
        else
        $return = substr_count($value,$needle);

        return $return;
    }


    // subReplace
    // fonction simplifé de substr_replace qui gère les multibyte
    // la fonction n'accepte pas de tableau en argument
    // si start ou length sont des chaînes, calculent leur longueur avec strlen
    // replace peut être scalar, string ou un tableau
    public static function subReplace($offset,$length,$replace,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$offset,$length,$str,$replace);
        $offset = (is_string($offset))? static::len($offset,$mb):$offset;
        $length = (is_string($length))? static::len($length,$mb):$length;

        if(is_array($replace))
        $replace = static::charImplode($replace);

        if(is_scalar($replace))
        $replace = (string) $replace;

        if(is_int($offset) && is_int($length) && is_string($replace))
        {
            if($mb === true)
            {
                $split = static::chars($str,$mb);
                $replace = static::chars($replace,$mb);
                $splice = Arr::spliceIndex($offset,$length,$split,$replace);
                $return = static::charImplode($splice);
            }

            else
            $return = substr_replace($str,$replace,$offset,$length);
        }

        return $return;
    }


    // subCompare
    // compare la substring de main avec str donné en premier argument
    // la fonction supporte multibyte et que offset et length soient des string
    public static function subCompare(string $str,$offset,$length,string $main,bool $sensitive=true,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$offset,$length,$main);
        $offset = (is_string($offset))? static::len($offset,$mb):$offset;
        $length = (is_string($length))? static::len($length,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($sensitive === false)
            {
                $str = static::lower($str,$mb);
                $main = static::lower($main,$mb);
            }

            $return = substr_compare($main,$str,$offset,$length,($sensitive === true)? false:true);
        }

        return $return;
    }


    // subSearch
    // retourne la string coupé à la première occurence d'un des caractère chars
    public static function subSearch(string $chars,string $str):string
    {
        $return = '';

        if(!empty($chars))
        {
            $brk = strpbrk($str,$chars);
            if($brk !== false)
            $return = $brk;
        }

        return $return;
    }


    // startEndIndex
    // retourne 0 si le needle est en début de chaîne, -1 s'il est en fin de chaîne
    public static function startEndIndex(string $needle,string $str,bool $sensitive=true):?int
    {
        $return = null;

        if(static::isStart($needle,$str,$sensitive))
        $return = 0;

        elseif(static::isEnd($needle,$str,$sensitive))
        $return = -1;

        return $return;
    }


    // stripWrap
    // permet de faire un wrapStart, stripStart, wrapEnd ou stripEnd selon deux valeurs booléens
    public static function stripWrap(string $needle,string $return,?bool $start=null,?bool $end=null,bool $sensitive=true):string
    {
        if($start === true)
        $return = static::wrapStart($needle,$return,$sensitive);

        if($end === true)
        $return = static::wrapEnd($needle,$return,$sensitive);

        if($start === false)
        $return = static::stripStart($needle,$return,$sensitive);

        if($end === false && $return !== $needle)
        $return = static::stripEnd($needle,$return,$sensitive);

        return $return;
    }


    // stripStart
    // retourne la chaine sans le needle du debut
    public static function stripStart(string $needle,string $return,bool $sensitive=true):string
    {
        $mb = ($sensitive === false)? true:false;

        if(static::isStart($needle,$return,$sensitive))
        $return = static::sub($needle,null,$return,$mb);

        return $return;
    }


    // stripEnd
    // retourne la chaine sans le needle de la fin
    public static function stripEnd(string $needle,string $return,bool $sensitive=true):string
    {
        $mb = ($sensitive === false)? true:false;

        if(static::isEnd($needle,$return,$sensitive))
        $return = static::sub(0,-static::len($needle,$mb),$return,$mb);

        return $return;
    }


    // stripStartEnd
    // retourne le chaîne sans le needle de la fin et du début si existant
    public static function stripStartEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        if(static::isStartEnd($startNeedle,$endNeedle,$return,$sensitive))
        {
            $mb = ($sensitive === false)? true:false;
            $return = static::sub($startNeedle,null,$return,$mb);
            $return = static::sub(0,-static::len($endNeedle,$mb),$return,$mb);
        }

        return $return;
    }


    // stripStartOrEnd
    // retourne le chaîne sans le needle de la fin et du début
    public static function stripStartOrEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        $return = static::stripStart($startNeedle,$return,$sensitive);
        $return = static::stripEnd($endNeedle,$return,$sensitive);

        return $return;
    }


    // wrapStart
    // wrap une chaîne au début si elle ne l'est pas déjà
    public static function wrapStart(string $needle,string $return,bool $sensitive=true):string
    {
        if(!static::isStart($needle,$return,$sensitive))
        $return = $needle.$return;

        return $return;
    }


    // wrapEnd
    // wrap une chaîne à la fin si elle ne l'est pas déjà
    public static function wrapEnd(string $needle,string $return,bool $sensitive=true):string
    {
        if(!static::isEnd($needle,$return,$sensitive))
        $return = $return.$needle;

        return $return;
    }


    // wrapStartEnd
    // wrap une chaîne si elle n'est pas déjà wrap au début et à la fin
    public static function wrapStartEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        if(!static::isStartEnd($startNeedle,$endNeedle,$return,$sensitive))
        $return = $startNeedle.$return.$endNeedle;

        return $return;
    }


    // wrapStartOrEnd
    // wrap une chaîne si elle n'est pas déjà wrap au début ou à la fin
    public static function wrapStartOrEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        $isStart = static::isStart($startNeedle,$return,$sensitive);
        $isEnd = static::isEnd($endNeedle,$return,$sensitive);

        if(!$isStart)
        $return = $startNeedle.$return;

        if(!$isEnd)
        $return = $return.$endNeedle;

        return $return;
    }


    // stripFirst
    // enlève le premier caractère d'une string
    public static function stripFirst(string $str,int $amount=1,?bool $mb=null):string
    {
        return static::sub($amount,null,$str,$mb);
    }


    // stripLast
    // enlève le dernier caractère d'une string
    public static function stripLast(string $str,int $amount=1,?bool $mb=null):string
    {
        return static::sub(0,-$amount,$str,$mb);
    }


    // addPattern
    // ajoute un pattern à une chaîne, utiliser par les colonnes et cellules
    public static function addPattern(string $pattern,$value,string $char='*'):?string
    {
        $return = null;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = str_replace($char,$value,$pattern);
        }

        return $return;
    }


    // stripPattern
    // comme isPattern mais plutôt que retourner un boolean, retour la chaîne sans le pattern
    // par exemple avec le pattern *_id et une valeur de session_id, session sera retourné
    public static function stripPattern(string $pattern,$value,string $char='*',bool $sensitive=true):?string
    {
        $return = null;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $len = strlen($value);
            $charLen = strlen($char);

            if($charLen > 0 && $len > 0)
            {
                if(strpos($pattern,$char) === 0)
                {
                    $pattern = substr($pattern,$charLen);
                    if(static::isEnd($pattern,$value,$sensitive))
                    $return = static::stripEnd($pattern,$value,$sensitive);
                }

                elseif(strpos($pattern,$char) === (strlen($pattern) - $charLen))
                {
                    $pattern = substr($pattern,0,-$charLen);
                    if(static::isStart($pattern,$value,$sensitive))
                    $return = static::stripStart($pattern,$value,$sensitive);
                }
            }
        }

        return $return;
    }


    // stripBefore
    // retourne la chaîne avant la première occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    public static function stripBefore(string $char,string $value,bool $includeChar=true,bool $sensitive=true,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$char,$value);

        if(!empty($char))
        {
            if($sensitive === true)
            {
                if($mb === true)
                $strip = mb_strstr($value,$char,false,static::$config['charset']);
                else
                $strip = strstr($value,$char,false);
            }

            else
            {
                if($mb === true)
                $strip = mb_stristr($value,$char,false,static::$config['charset']);
                else
                $strip = stristr($value,$char,false);
            }

            if($strip !== false)
            {
                $return = $strip;
                if($includeChar === false)
                $return = static::sub($char,null,$strip,$mb);
            }
        }

        return $return;
    }


    // stripBeforeReverse
    // retourne la chaîne après la dernière occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    // seulement les fonctions mb sont utilisés
    public static function stripBeforeReverse(string $char,string $value,bool $includeChar=true,bool $sensitive=true):string
    {
        $return = '';

        if(!empty($char))
        {
            if($sensitive === true)
            $strip = mb_strrchr($value,$char,false,static::$config['charset']);

            else
            $strip = mb_strrichr($value,$char,false,static::$config['charset']);

            if($strip !== false)
            {
                $return = $strip;

                if($includeChar === false)
                $return = static::sub($char,null,$strip,true);
            }
        }

        return $return;
    }


    // stripAfter
    // retourne la chaîne après la première occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    public static function stripAfter(string $char,string $value,bool $includeChar=false,bool $sensitive=true,?bool $mb=null):?string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$char,$value);

        if(!empty($char))
        {
            if($sensitive === true)
            {
                if($mb === true)
                $strip = mb_strstr($value,$char,true,static::$config['charset']);
                else
                $strip = strstr($value,$char,true);
            }

            else
            {
                if($mb === true)
                $strip = mb_stristr($value,$char,true,static::$config['charset']);
                else
                $strip = stristr($value,$char,true);
            }

            if($strip !== false)
            {
                $return = $strip;
                if($includeChar === true)
                $return .= $char;
            }
        }

        return $return;
    }


    // stripAfterReverse
    // retourne la chaîne avant la dernière occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    // seulement les fonctions mb sont utilisés
    public static function stripAfterReverse(string $char,string $value,bool $includeChar=false,bool $sensitive=true):string
    {
        $return = '';

        if(!empty($char))
        {
            if($sensitive === true)
            $strip = mb_strrchr($value,$char,true,static::$config['charset']);

            else
            $strip = mb_strrichr($value,$char,true,static::$config['charset']);

            if($strip !== false)
            {
                $return = $strip;
                if($includeChar === true)
                $return .= $char;
            }
        }

        return $return;
    }


    // changeBefore
    // change le début d'une string avant la présence de char
    // si aucun changement n'est effectué, retourne la string initiale
    public static function changeBefore(string $char,string $change,string $return,bool $sensitive=true,?bool $mb=null):string
    {
        if(!empty($char))
        {
            $after = static::stripBefore($char,$return,false,$sensitive,$mb);

            if(!empty($after))
            $return = $change.$char.$after;
        }

        return $return;
    }


    // changeAfter
    // change la fin d'une string après la présence de char
    // si aucun changement n'est effectué, retourne la string initiale
    public static function changeAfter(string $char,string $change,string $return,bool $sensitive=true,?bool $mb=null):string
    {
        if(!empty($char))
        {
            $before = static::stripAfter($char,$return,false,$sensitive,$mb);

            if(!empty($before))
            $return = $before.$char.$change;
        }

        return $return;
    }


    // lower
    // lowercase pour tous les caractères de la chaîne
    public static function lower(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strtolower($value,static::$config['charset']);
        else
        $return = strtolower($value);

        return $return;
    }


    // lowerFirst
    // met lowercase la première lettre de la chaîne, support pour mb
    public static function lowerFirst(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $lower = static::lower(static::subFirst($value,1,$mb),$mb);
            $return = $lower.static::stripFirst($value,1,$mb);
        }

        else
        $return = lcfirst($value);

        return $return;
    }


    // upper
    // uppercase pour tous les caractères de la chaîne
    public static function upper(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strtoupper($value,static::$config['charset']);
        else
        $return = strtoupper($value);

        return $return;
    }


    // upperFirst
    // capitalize la première lettre de la chaîne, support pour mb
    public static function upperFirst(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $higher = static::upper(static::subFirst($value,1,$mb),$mb);
            $return = $higher.static::stripFirst($value,1,$mb);
        }

        else
        $return = ucfirst($value);

        return $return;
    }


    // capitalize
    // capitalize le premier mot de la chaîne
    public static function capitalize(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $higher = static::upper(static::subFirst($value,1,$mb),$mb);
            $lower = static::lower(static::stripFirst($value,1,$mb),$mb);
            $return = $higher.$lower;
        }

        else
        $return = ucfirst(strtolower($value));

        return $return;
    }


    // title
    // capitalize chaque mot dans la chaîne
    public static function title(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_convert_case($value,MB_CASE_TITLE,static::$config['charset']);
        else
        $return = ucwords(strtolower($value));

        return $return;
    }


    // reverse
    // invertit une string
    public static function reverse(string $str,?bool $mb=null):string
    {
        $return = '';

        if($mb === true)
        {
            $split = static::chars($str,$mb);
            if(!empty($split))
            {
                $reverse = array_reverse($split);
                $return = static::charImplode($reverse);
            }
        }
        else
        $return = strrev($str);

        return $return;
    }


    // shuffle
    // mélange une string
    public static function shuffle(string $str,?bool $mb=null):string
    {
        $return = '';

        if($mb === true)
        {
            $split = static::chars($str,$mb);
            if(!empty($split))
            {
                shuffle($split);
                $return = static::charImplode($split);
            }
        }
        else
        $return = str_shuffle($str);

        return $return;
    }


    // pad
    // pad la chaîne à gauche et à droite avec le caractère input jusqu'à la length donnée
    public static function pad(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $return = $str;
            $strLength = static::len($str,$mb);

            if($length > $strLength)
            {
                $return = $str;
                $start = '';
                $startLength = (int) floor(($length - $strLength) / 2);
                $end = '';
                $endLength = (int) ceil(($length - $strLength) / 2);

                while (static::len($start,$mb) < $startLength)
                {
                    $start .= $input;
                }
                $start = static::sub(0,$startLength,$start,$mb);

                while (static::len($end,$mb) < $endLength)
                {
                    $end .= $input;
                }
                $end = static::sub(0,$endLength,$end,$mb);

                $return = $start.$str.$end;
            }
        }
        else
        $return = str_pad($str,$length,$input,STR_PAD_BOTH);

        return $return;
    }


    // padLeft
    // pad la chaîne à gauche avec le caractère input jusqu'à la length donnée
    public static function padLeft(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $strLength = static::len($str,$mb);

            if($length > $strLength)
            {
                while (static::len($return,$mb) < $length)
                {
                    $return .= $input;
                }

                $return = static::sub(0,$length,$return,$mb);
                $return = static::subReplace(-$strLength,$strLength,$str,$return,$mb);
            }

            else
            $return = $str;
        }
        else
        $return = str_pad($str,$length,$input,STR_PAD_LEFT);

        return $return;
    }


    // padRight
    // pad la chaîne à droite avec le caractère input jusqu'à la length donnée
    public static function padRight(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $return = $str;

            if($length > static::len($str,$mb))
            {
                while (static::len($return,$mb) < $length)
                {
                    $return .= $input;
                }

                $return = static::sub(0,$length,$return,$mb);
            }
        }
        else
        $return = str_pad($str,$length,$input,STR_PAD_RIGHT);

        return $return;
    }


    // split
    // split une chaîne par longueur
    // length permet de définir la longueur du split
    public static function split(int $length=1,string $str,?bool $mb=null):array
    {
        $return = [];
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);

        if($length > 0)
        {
            if($mb === true)
            {
                $len = static::len($str,$mb);
                for($i = 0; $i < $len; $i += $length)
                {
                    $return[] = static::sub($i,$length,$str,$mb);
                }
            }

            else
            $return = str_split($str,$length);
        }

        return $return;
    }


    // chars
    // retourne un tableau avec une entrée par caractère
    public static function chars(string $str,?bool $mb=null):array
    {
        return static::split(1,$str,$mb);
    }


    // charCount
    // retourne un tableau avec chaque caractère et sa fréquence dans la chaîne
    // si mb est numérique, on envoie à la fonction count_chars qui retourne les octets pour des caractères
    public static function charCount(string $str,$mb=null):array
    {
        $return = [];

        if(is_int($mb))
        $return = count_chars($str,$mb);

        elseif(is_scalar($mb) || $mb === null)
        {
            $split = static::chars($str,$mb);

            if(!empty($split))
            $return = Arr::countValues($split);
        }

        return $return;
    }


    // charImplode
    // implode un tableau de caractère en une chaîne
    public static function charImplode(array $array):string
    {
        return implode('',$array);
    }


    // charSplice
    // alias de subReplace
    public static function charSplice($offset,$length,$replace,string $str,?bool $mb=null):string
    {
        return static::subReplace($offset,$length,$replace,$str,$mb);
    }


    // lineBreaks
    // permet de régurilariser la situation des line breaks dans une chaîne
    public static function lineBreaks(string $str,string $separator=PHP_EOL):string
    {
        return preg_replace('~\R~u',$separator,$str);
    }


    // lines
    // retourne un tableau de la chaîne divisé par ligne
    // support pour tous les diviseurs de ligne
    // trim permet d'enlever les espaces au début et à la fin de chaque ligne
    public static function lines(string $str,bool $trim=false):array
    {
        $return = explode(PHP_EOL,static::lineBreaks($str,PHP_EOL));

        if($trim === true)
        $return = array_map('trim',$return);

        return $return;
    }


    // lineCount
    // retourne le nombre de ligne dans la chaîne
    public static function lineCount(string $str):int
    {
        return count(static::lines($str));
    }


    // lineImplode
    // implode un tableau dans une string, le séparateur est PHP_EOL
    public static function lineImplode(array $value,string $separator=PHP_EOL):string
    {
        return implode($separator,$value);
    }


    // lineSplice
    // permet d'ajouter ou remplacer une ou plusieurs lignes dans la chaîne
    // replace peut être array, string ou null
    public static function lineSplice(int $offset,?int $length,$replace=[],string $str):string
    {
        $return = '';
        $lines = static::lines($str);

        if(is_scalar($replace))
        $replace = (array) $replace;

        if(!empty($lines) && (is_array($replace) || $replace === null))
        {
            $splice = Arr::spliceIndex($offset,$length,$lines,$replace);
            $return = static::lineImplode($splice);
        }

        return $return;
    }


    // words
    // retourne un tableau des mots
    // str_word_count n'est pas utilisé pour pour cette méthode car les résultats de la fonction sont inconsistents
    public static function words(string $str,?bool $mb=null):array
    {
        $return = [];
        $i = 0;

        foreach (explode(' ',$str) as $v)
        {
            $oriLen = static::len($v,$mb);
            $v = static::removeWhiteSpace($v);
            $len = static::len($v,$mb);

            if($len > 0)
            $return[$i] = $v;

            $i += ($oriLen + 1);
        }

        return $return;
    }


    // wordCount
    // retourne le nombre de mot dans la string
    public static function wordCount(string $str):int
    {
        return count(static::words($str));
    }


    // wordExplode
    // explode un tableau de mot, plus rapide que words
    public static function wordExplode(string $value,?int $limit=null,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $value = static::removeWhiteSpace($value);
        $return = static::explode(' ',$value,$limit,$trim,$clean);

        return $return;
    }


    // wordExplodeIndex
    // retoure un index du tableau de words explosé
    public static function wordExplodeIndex(int $index,string $value,?int $limit=null,bool $trim=false,bool $clean=false):?string
    {
        return Arr::index($index,static::wordExplode($value,$limit,$trim,$clean));
    }


    // wordImplode
    // implode un tableau en une string de mots
    public static function wordImplode(array $array):string
    {
        return implode(' ',$array);
    }


    // wordSplice
    // permet d'ajouter ou remplacer un ou plusieurs mots dans la chaîne
    // replace peut être array, string ou null
    public static function wordSplice(int $offset,?int $length,$replace=[],string $str,?bool $mb=null):string
    {
        $return = '';
        $words = static::words($str,$mb);

        if(is_scalar($replace))
        $replace = (array) $replace;

        if(!empty($words) && (is_array($replace) || $replace === null))
        {
            $splice = Arr::spliceIndex($offset,$length,$words,$replace);
            $return = static::wordImplode($splice);
        }

        return $return;
    }


    // wordSliceLength
    // retourne une string avec les mots avec une longueur entre min et max
    public static function wordSliceLength(int $min,?int $max,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);
        $max = ($max === null)? PHP_INT_MAX:$max;
        $array = [];

        foreach (static::words($str,$mb) as $word)
        {
            $wordLen = static::len($word,$mb);

            if(Number::in($min,$wordLen,$max))
            $array[] = $word;
        }

        if(!empty($array))
        $return = static::wordImplode($array);

        return $return;
    }


    // wordStripLength
    // retourne une string sans les mots avec une longueur entre min et max
    public static function wordStripLength(int $min,?int $max,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);
        $array = [];
        $max = ($max === null)? PHP_INT_MAX:$max;

        foreach (static::words($str,$mb) as $word)
        {
            $wordLen = static::len($word,$mb);

            if(!Number::in($min,$wordLen,$max))
            $array[] = $word;
        }

        if(!empty($array))
        $return = static::wordImplode($array);

        return $return;
    }


    // wordTotalLength
    // retourne une string avec les mots rentrant dans une longueur totale
    // va retourne un mot truncate si le premier mot est plus court que length
    public static function wordTotalLength(int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);

        if(static::len($str,$mb) <= $length)
        $return = $str;

        else
        {
            $array = [];
            $inLength = 0;

            foreach ($words = static::words($str) as $word)
            {
                $wordLength = static::len($word,$mb);

                if(empty($inLength) && $wordLength >= $length)
                {
                    $array[] = static::sub(0,$length,$word,$mb);
                    $inLength += $wordLength + 1;
                }

                elseif(($inLength + $wordLength) <= $length)
                {
                    $array[] = $word;
                    $inLength += $wordLength + 1;
                }
            }

            if(!empty($array))
            $return = static::wordImplode($array);
        }

        return $return;
    }


    // wordwrap
    // wrapper pour word_wrap
    // fonctionne avec mb
    public static function wordwrap(int $width=75,string $str,string $break=PHP_EOL,bool $cut=false,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$break);

        if(!empty($break))
        {
            if($mb === true)
            {
                $array = [];

                foreach (explode($break,$str) as $line)
                {
                    $actual = '';
                    $line = rtrim($line);
                    if(static::len($line,$mb) <= $width)
                    continue;

                    $words = explode(' ',$line);
                    $line = '';
                    foreach ($words as $word)
                    {
                        if(static::len($actual.$word,$mb) <= $width)
                        $actual .= $word.' ';
                        else
                        {
                            if($actual !== '')
                            $line .= rtrim($actual).$break;

                            $actual = $word;
                            if($cut === true)
                            {
                                while (static::len($actual,$mb) > $width)
                                {
                                    $line .= static::sub(0,$width,$actual,$mb).$break;
                                    $actual = static::sub($width,null,$actual,$mb);
                                }
                            }
                            $actual .= ' ';
                        }
                    }

                    $line .= trim($actual);
                    $array[] = $line;
                }

                $return = implode($break,$array);
            }

            else
            $return = wordwrap($str,$width,$break,$cut);
        }

        return $return;
    }


    // replace
    // remplace tous les éléments dans une chaîne à partir d'un tableau from => to
    // si value est scalar transformé en string, si null transformé en empty string
    public static function replace(array $replace,string $return,bool $sensitive=true):string
    {
        if(!empty($replace))
        {
            foreach ($replace as $k => $v)
            {
                $k = (string) $k;

                if(is_object($v))
                $v = Obj::cast($v);

                if(is_scalar($v))
                $v = (string) $v;

                if($v === null)
                $v = '';

                if(is_string($v))
                {
                    if($sensitive === true)
                    $return = str_replace($k,$v,$return);

                    else
                    $return = str_ireplace($k,$v,$return);
                }
            }
        }

        return $return;
    }


    // ireplace
    // remplace tous les éléments dans une chaîne à partir d'un tableau from => to
    // non sensible à la case
    public static function ireplace(array $replace,string $return):string
    {
        return static::replace($replace,$return,false);
    }


    // explode
    // explose une string selon un delimiter
    // si trim est true, passe chaque élément du tableau dans trim
    // si clean est true, enlève les entrées du tableau cleanable
    public static function explode(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        $return = explode($delimiter,$value,($limit === null)? PHP_INT_MAX:$limit);

        if(!empty($return))
        {
            if($trim === true)
            $return = array_map('trim',$return);

            if($clean === true)
            {
                $return = Arr::clean($return);
                $return = array_values($return);
            }
        }

        return $return;
    }


    // explodeTrim
    // explose une string selon un delimiter avec le paramètre trim à true
    public static function explodeTrim(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $clean=false):array
    {
        return static::explode($delimiter,$value,$limit,true,$clean);
    }


    // explodeClean
    // explose une string selon un delimiter avec le paramètre clean à true
    public static function explodeClean(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false):array
    {
        return static::explode($delimiter,$value,$limit,$trim,true);
    }


    // explodeTrimClean
    // explose une string selon un delimiter avec les paramètres trim et clean à true
    public static function explodeTrimClean(string $delimiter,string $value,?int $limit=PHP_INT_MAX):array
    {
        return static::explode($delimiter,$value,$limit,true,true);
    }


    // explodeIndex
    // explose une string selon un delimiter
    // retourne un index de l'explostion ou null si n'existe pas
    public static function explodeIndex(int $index,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):?string
    {
        return Arr::index($index,static::explode($delimiter,$value,$limit,$trim,$clean));
    }


    // explodeIndexes
    // explose une string selon un delimiter
    // retourne un tableau des index existants
    public static function explodeIndexes(array $indexes,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        return Arr::indexes($indexes,static::explode($delimiter,$value,$limit,$trim,$clean));
    }


    // explodeIndexesExists
    // explose une string selon un delimiter
    // retourne le tableau si les index existent sinon null
    public static function explodeIndexesExists(array $indexes,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):?array
    {
        $return = null;
        $explode = static::explode($delimiter,$value,$limit,$trim,$clean);

        if(Arr::indexesExists($indexes,$explode))
        $return = $explode;

        return $return;
    }


    // explodekeyValue
    // explose les valeurs d'une string et retourne un tableau sous une forme clé -> valeur
    public static function explodekeyValue(string $delimiter,string $value,bool $trim=false,bool $clean=false):array
    {
        $return = [];

        $x = static::explode($delimiter,$value,2,$trim,$clean);
        if(count($x) === 2 && Arr::isKey($x[0]))
        $return[$x[0]] = $x[1];

        return $return;
    }


    // explodes
    // explosion d'une chaîne en fonction de multiples delimiteurs
    public static function explodes(array $delimiters,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $delimiter = array_shift($delimiters);

        if(is_string($delimiter) && !empty($delimiter))
        {
            $return = static::explode($delimiter,$value,$limit,$trim,$clean);

            if(!empty($delimiters))
            {
                $explodes = [];
                foreach ($return as $v)
                {
                    $explodes[] = static::explodes($delimiters,$v,$limit,$trim,$clean);
                }

                $return = $explodes;
            }
        }

        return $return;
    }


    // trim
    // wrapper pour la fonction php trim, permet d'ajouter des caractères à effacer
    // ajouter des caractères extra à effacer dans extraTrim
    // default permet d'utiliser les caractères effacés par défaut comme base
    public static function trim(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? static::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = trim($value,$trim);

        return $return;
    }


    // trimLeft
    // wrapper pour la fonction php ltrim
    // ajouter des caractères extra à effacer dans extraTrim
    // default permet d'utiliser les caractères effacés par défaut comme base
    public static function trimLeft(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? static::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = ltrim($value,$trim);

        return $return;
    }


    // trimRight
    // wrapper pour la fonction php rtrim
    // ajouter des caractères extra à effacer dans extraTrim
    // default permet d'utiliser les caractères effacés par défaut comme base
    public static function trimRight(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? static::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = rtrim($value,$trim);

        return $return;
    }


    // repeatLeft
    // repeat un caractère un nombre de fois au début d'une chaine
    public static function repeatLeft(string $input,int $multiplier=2,string $return):string
    {
        if($multiplier > 0)
        $return = str_repeat($input,$multiplier).$return;

        return $return;
    }


    // repeatRight
    // repeat un caractère un nombre de fois à la fin d'une chaine
    public static function repeatRight(string $input,int $multiplier=2,string $return):string
    {
        if($multiplier > 0)
        $return = $return.str_repeat($input,$multiplier);

        return $return;
    }


    // addSlash
    // ajoute un backslash avant chaque quote ou doubleQuote
    public static function addSlash(string $value):string
    {
        return addslashes($value);
    }


    // stripSlash
    // enlève les backslash à gauche de chaque quote ou doubleQuote
    public static function stripSlash(string $value):string
    {
        return stripslashes($value);
    }


    // quote
    // enrobe une string de quote single ou double
    public static function quote(string $return,bool $double=false):string
    {
        return static::wrapStartOrEnd($quote = ($double === true)? '"':"'",$quote,$return);
    }


    // unquote
    // dérobe une string de quote single et/ou double
    public static function unquote(string $return,bool $single=true,bool $double=true):string
    {
        if($single === true)
        $return = static::stripStartOrEnd("'","'",$return);

        if($double === true)
        $return = static::stripStartOrEnd('"','"',$return);

        return $return;
    }


    // doubleToSingleQuote
    // transforme les doubles quotes en single quotes
    public static function doubleToSingleQuote(string $return):string
    {
        return str_replace('"',"'",$return);
    }


    // singleToDoubleQuote
    // transforme les single quotes en double quotes
    public static function singleToDoubleQuote(string $return):string
    {
        return str_replace("'",'"',$return);
    }


    // quoteChar
    // permet d'ajouter un slash à tous les caractères données en argument présent dans la string
    public static function quoteChar(string $return,$chars):string
    {
        if(is_array($chars))
        $chars = implode('',$chars);

        $return = addcslashes($return,$chars);

        return $return;
    }


    // commaToDecimal
    // transforme la virgule en decimal
    public static function commaToDecimal(string $value):string
    {
        return str_replace(',','.',$value);
    }


    // decimalToComma
    // transforme la decimal en virgule
    public static function decimalToComma(string $value):string
    {
        return str_replace('.',',',$value);
    }


    // similar
    // calcule le pourcentage de similitude entre deux chaînes
    // par défaut les string sont passés à la méthode asciiLower
    public static function similar(string $value,string $value2,bool $asciiLower=true):?float
    {
        $return = null;

        if($asciiLower === true)
        {
            $value = static::asciiLower($value);
            $value2 = static::asciiLower($value2);
        }

        similar_text($value,$value2,$return);

        return $return;
    }


    // levenshtein
    // calcule la distance levenshtein entre deux chaînes
    // par défaut les string sont passés à la méthode asciiLower
    public static function levenshtein(string $value,string $value2,bool $asciiLower=true):?int
    {
        $return = null;

        if($asciiLower === true)
        {
            $value = static::asciiLower($value);
            $value2 = static::asciiLower($value2);
        }

        $return = levenshtein($value,$value2);

        return $return;
    }


    // random
    // génère un string random en utilisant mt_rand ou csprng
    public static function random(int $length=40,?string $random=null,bool $csprng=false,?bool $mb=null):string
    {
        $return = '';

        if($csprng === true)
        $return = Crypt::randomString($length,$random);

        else
        {
            $random = Crypt::getRandomString($random);

            if($length > 0 && is_string($random) && !empty($random))
            {
                $counter = 0;
                $split = static::chars($random,$mb);
                $count = count($split);

                if($count > 0)
                {
                    $max = $count - 1;

                    while ($counter < $length)
                    {
                        $key = mt_rand(0,$max);

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
        }

        return $return;
    }


    // randomPrefix
    // génère un string random avec un prefix au début
    public static function randomPrefix(string $return='',int $length=40,?string $random=null,bool $csprng=false,?bool $mb=null):string
    {
        if(!empty($return))
        $return = static::keepAlphanumeric($return);

        $return .= static::random($length,$random,$csprng,$mb);

        return $return;
    }


    // fromCamelCase
    // explose une string camelCase
    public static function fromCamelCase(string $str):array
    {
        return preg_split('#([A-Z][^A-Z]*)#',$str,0,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }


    // toCamelCase
    // permet de transformer une string ou un tableau en camelCase bien formatté
    public static function toCamelCase(string $delimiter,$value,?bool $mb=null):string
    {
        $return = '';

        if(is_string($value) && !empty($delimiter))
        $value = explode($delimiter,$value);

        if(is_array($value))
        {
            $i = 0;
            $camelCase = [];

            foreach ($value as $v)
            {
                if(is_string($v))
                {
                    if($i)
                    $camelCase[] = static::capitalize($v,$mb);
                    else
                    $camelCase[] = static::lower($v,$mb);

                    $i++;
                }
            }

            $return = static::charImplode($camelCase);
        }

        return $return;
    }


    // loremIpsum
    // génère du texte lorem ipsum
    // separator new line
    public static function loremIpsum(int $amount=3,int $paragraph=1,string $separator=PHP_EOL):string
    {
        $return = '';
        $source = static::$config['loremIpsum'];

        if(is_array($source) && !empty($source) && $amount > 0 && $paragraph > 0)
        {
            for ($i=0; $i < $paragraph; $i++)
            {
                $r = '';

                for ($o=0; $o < $amount; $o++)
                {
                    $r .= (!empty($r))? ' ':'';
                    $r .= Arr::valueRandom($source);
                }

                // separator
                if(!empty($separator) && !empty($return))
                $return .= $separator;

                // met dans return
                $return .= $r;
            }
        }

        return $return;
    }


    // s
    // retourne la lettre pluriel si plusieurs
    public static function s($value,?string $letter=null):string
    {
        $return = '';
        $letter = (is_string($letter))? $letter:static::$config['plural']['letter'];

        if(is_string($letter))
        {
            $s = false;

            if(is_numeric($value) && $value > 1)
            $s = true;

            elseif(is_string($value) && strlen($value) > 1)
            $s = true;

            elseif(is_array($value) && count($value) > 1)
            $s = true;

            if($s === true)
            $return = $letter;
        }

        return $return;
    }


    // plural
    // retourne une version plurielle de la chaine à partir d'un tableau de remplacement
    // value peut être int ou array ou objet comptable
    // retourne peut etre une string ou une valeur qui sera passé dans obj:cast
    public static function plural($value,$return,?array $replace=null,?string $letter=null,?string $wrap=null):string
    {
        if(!is_int($value))
        $value = count($value);

        if(!is_string($return))
        $return = (string) Obj::cast($return);

        if(is_int($value) && is_string($return))
        {
            $isPlural = ($value > 1)? true:false;
            $letter = (is_string($letter))? $letter:static::$config['plural']['letter'];
            $wrap = (!empty($wrap))? $wrap:static::$config['plural']['wrap'];
            $delimiter = Segment::getDelimiter($wrap);
            $default = $delimiter[0].$letter.$delimiter[1];
            $replace = (array) $replace;

            if(strpos($return,$default) !== false)
            $replace[$letter] = $letter;

            if(is_array($replace) && !empty($replace))
            {
                if($isPlural === false)
                {
                    $replace = array_combine(array_keys($replace),array_keys($replace));
                    if(is_string($letter))
                    $replace[$letter] = '';
                }

                if(is_string($wrap))
                $replace = Arr::keysWrap($delimiter[0],$delimiter[1],$replace);

                $return = static::replace($replace,$return);
            }

            elseif($isPlural && is_string($letter) && !self::isEnd($letter,$return))
            $return .= $letter;
        }

        return $return;
    }


    // replaceChar
    // wrapper pour strtr qui fonctionne avec deux string
    // supporte multibyte
    public static function replaceChar(string $from,string $to,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$from,$to,$str);

        if($mb === true)
        {
            $from = static::chars($from,$mb);
            $to = static::chars($to,$mb);
            $replace = array_combine(array_values($from),array_values($to));
            $return = strtr($str,$replace);
        }
        else
        $return = strtr($str,$from,$to);

        return $return;
    }


    // replaceAccent
    // remplace tous les caractères accentés d'une chaîne
    public static function replaceAccent(string $return):string
    {
        if(Encoding::isMb($return))
        {
            $from = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'];

            $to = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'];

            $return = str_replace($from,$to,$return);
        }

        return $return;
    }


    // removeAccent
    // enleve tous les caractères accentés d'une chaîne
    public static function removeAccent(string $return):string
    {
        if(Encoding::isMb($return))
        {
            $from = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'];

            $return = str_replace($from,'',$return);
        }

        return $return;
    }


    // fixUnicode
    // enlève les caractères non utf8, genre un é divisé en deux par substr
    // enlève les caractères unicodes forbidden d'une string
    // ces caractères invisibles proviennent parfois d'une copie de Microsoft Word
    public static function fixUnicode(string $return):string
    {
        $return .= '....';

        $return = iconv('UTF-8', 'UTF-8//IGNORE', $return);
        $return = substr($return,0,-4);
        $return = preg_replace('/[\x{0092}]/u','',$return);

        return $return;
    }


    // removeUnicode
    // enlève les caractères unicode d'une string
    public static function removeUnicode(string $return):string
    {
        if(Encoding::isMb($return))
        {
            $return = static::fixUnicode($return);
            $return = preg_replace('/[\x{1F600}-\x{1F64F}]/u','',$return);
            $return = preg_replace('/[\x{1F300}-\x{1F5FF}]/u','',$return);
            $return = preg_replace('/[\x{1F680}-\x{1F6FF}]/u','',$return);
            $return = preg_replace('/[\x{2600}-\x{26FF}]/u','',$return);
            $return = preg_replace('/[\x{2700}-\x{27BF}]/u','',$return);
        }

        return $return;
    }


    // removeSymbols
    // enlève les symbols d'une string
    public static function removeSymbols(string $return):string
    {
        return preg_replace('/[^\p{L}\p{N}\s]/u','',$return);
    }


    // removeLineBreaks
    // enleve les line breaks d'une string
    public static function removeLineBreaks(string $return):string
    {
        return str_replace(["\n","\r","\r\n","\t"],'',$return);
    }


    // removeTabs
    // enlève les tabs d'une string
    public static function removeTabs(string $return):string
    {
        return str_replace("\t",'',$return);
    }


    // removeWhitespace
    // enleve les whitespace d'une string (saut de ligne, tab, &nbsp;)
    // enlève aussi un caractère espace qui peut apparaître après avoir encode une string iso en utf8 via utf8_encode
    public static function removeWhitespace(string $return):string
    {
        $return = str_replace(["\n","\r","\r\n","\t",'&nbsp;',' '],' ',$return);
        $return = static::removeConsecutive(' ',$return);
        $return = trim($return);

        return $return;
    }


    // removeAllWhitespace
    // enleve toutes les whitespace d'une string (saut de ligne, tab, &nbsp; et espace)
    public static function removeAllWhitespace(string $return):string
    {
        $return = str_replace(["\n","\r","\r\n","\t",'&nbsp;'],'',$return);
        $return = str_replace(' ','',$return);
        $return = trim($return);

        return $return;
    }


    // removeConsecutive
    // enlève les caracètres consécutifs identiques, remplace par une seule instance du caractère
    // possible de mettre un autre caractère de remplacement
    public static function removeConsecutive(string $remove,string $return,?string $replace=null)
    {
        return preg_replace("/$remove+/",(is_string($replace))? $replace:$remove,$return);
    }


    // remove
    // enlève un ou plusieurs caractères d'une chaîne en utilisant str_replace
    public static function remove($remove,string $return):string
    {
        $replace = [];
        $remove = (array) $remove;

        foreach ($remove as $v)
        {
            if(is_scalar($v))
            {
                $v = (string) $v;
                $replace[$v] = '';
            }
        }

        if(!empty($replace))
        $return = static::replace($replace,$return);

        return $return;
    }


    // keepNumeric
    // enleve tous les caractères non numérique, sauf la décimal et le négatif
    // keep permet de garder des caractères supplémentaires
    public static function keepNumeric(string $value,string $keep=''):string
    {
        return preg_replace("/[^0-9\-\.$keep]/", '', $value);
    }


    // keepNumber
    // enleve tous les caractères non numérique
    // keep permet de garder des caractères supplémentaires
    public static function keepNumber(string $value,string $keep=''):string
    {
        return preg_replace("/[^0-9$keep]/", '', $value);
    }


    // keepAlpha
    // enleve tous les caractères non alpha
    // keep permet de garder des caractères supplémentaires
    public static function keepAlpha(string $value,string $keep=''):string
    {
        return preg_replace('/[^A-Za-z]/', '', $value);
    }


    // keepAlphanumeric
    // enleve tous les caractères non alphanumérique
    // keep permet de garder des caractères supplémentaires
    public static function keepAlphanumeric(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9$keep]/", '', $value);
    }


    // keepAlphanumericPlus
    // va garder _ - . @, enleve tous les autres caractères spéciaux
    // keep permet de garder des caractères supplémentaires
    public static function keepAlphanumericPlus(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9_\-\.\@$keep]/", '', $value);
    }


    // keepAlphanumericPlusSpace
    // va garder _ - . @ et espace, enleve tous les autres caractères spéciaux
    // keep permet de garder des caractères supplémentaires
    public static function keepAlphanumericPlusSpace(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9_\-\.\@ $keep]/", '', $value);
    }


    // ascii
    // garde seulement les caractères ascii
    public static function ascii(string $return,bool $replaceAccent=true):string
    {
        if($replaceAccent === true)
        $return = static::replaceAccent($return);

        $return = preg_replace("/[^\x01-\x7F]/",'',$return);

        return $return;
    }


    // asciiLower
    // garde seulement les caractères ascii et envoie en lowerCase
    public static function asciiLower(string $return,bool $replaceAccent=true):string
    {
        $return = static::ascii($return);
        $return = strtolower($return);

        return $return;
    }


    // clean
    // fonction pour nettoyer une string (remplace accent, garde alphanumeric et trim)
    public static function clean(string $return,string $keep=''):string
    {
        $return = static::replaceAccent($return);
        $return = static::keepAlphanumeric($return,$keep);
        $return = trim($return);

        return $return;
    }


    // cleanLower
    // comme clé mais envoie en lowercase
    public static function cleanLower(string $return,string $keep=''):string
    {
        $return = static::replaceAccent($return);
        $return = static::keepAlphanumeric($return,$keep);
        $return = strtolower($return);
        $return = trim($return);

        return $return;
    }


    // cleanKeepSpace
    // fonction pour nettoyer une string (trim, remplace accent, garde alphanumeric et espace)
    public static function cleanKeepSpace(string $return):string
    {
        return static::clean($return,' ');
    }


    // def
    // retourne une string formaté par défaut
    // remplace _ par espace, cleanKeepSpace, lower, capitalizeTitle et trim
    public static function def(string $return,string $keep=''):string
    {
        $return = static::replace(['_'=>' '],$return);
        $return = static::cleanKeepSpace($return,$keep);
        $return = strtolower($return);
        $return = ucfirst($return);
        $return = trim($return);

        return $return;
    }


    // pointer
    // explose une chaîne pointeur (de type table/id)
    // le retour est passé dans cast
    // peut retourner null
    public static function pointer(string $value,?string $separator=null):?array
    {
        $return = null;
        $separator = (is_string($separator))? $separator:static::$config['pointer'];
        $value = static::explodeTrimClean($separator,$value);
        $value = Arr::cast($value);

        if(count($value) === 2 && is_string($value[0]) && is_int($value[1]))
        $return = $value;

        return $return;
    }


    // toPointer
    // génère un pointeur à partir d'une string et chiffre
    public static function toPointer(string $key,int $value,?string $separator=null):string
    {
        $separator = (is_string($separator))? $separator:static::$config['pointer'];
        return $key.$separator.$value;
    }


    // map
    // permet de map toutes les strings dans la valeur
    // peut fournir une valeur scalaire ou un tableau
    public static function map(callable $callable,$return,...$args)
    {
        if(is_array($return))
        {
            foreach ($return as $key => $value)
            {
                if(is_array($value))
                $return[$key] = static::map($callable,$value,...$args);

                elseif(is_string($value))
                $return[$key] = $callable($value,...$args);
            }
        }

        elseif(is_string($return))
        $return = $callable($return,...$args);

        return $return;
    }


    // excerpt
    // fonction pour faire un résumé sécuritaire
    // mb, removeLineBreaks, removeUnicode, excerpt par length (rtrim et suffix) et trim
    // prendre note que le suffix est maintenant comptabilisé dans la longueur de la string
    public static function excerpt(?int $length,string $return,?array $option=null):string
    {
        $option = Arr::plus(['removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true],$option);

        // enleve les sauts de ligne et les tabulations
        if(!empty($option['removeLineBreaks']))
        $return = static::removeLineBreaks($return);

        // unicode
        if(!empty($option['removeUnicode']))
        $return = static::removeUnicode($return);
        else
        $return = static::fixUnicode($return);

        // length
        if(!empty($length))
        {
            $lts = static::lengthTrimSuffix($length,$return,$option);
            $return = $lts['strSuffix'];
        }

        // trim les espaces
        if(!empty($option['trim']))
        $return = trim($return);

        return $return;
    }


    // lengthTrimSuffix
    // méthode utilisé par excerpt dans str et html
    // gère le total length, le rtrim et l'ajout d'un suffix
    // retourne un array avec la string et le suffix
    // important: mb est null par défaut
    public static function lengthTrimSuffix(int $length,string $value,?array $option=null):array
    {
        $return = ['str'=>'','strSuffix'=>'','suffix'=>null];
        $option = Arr::plus(['mb'=>null,'rtrim'=>null,'suffix'=>static::$config['excerpt']['suffix']],$option);
        $mb = Encoding::getMb($option['mb'],$value);
        $suffix = $option['suffix'];
        $sliced = static::wordTotalLength($length,$value,$mb);

        $rtrim = '.,:;';
        $rtrim .= (!empty($option['rtrim']) && is_string($option['rtrim']))? $option['rtrim']:'';
        $sliced = static::trimRight($sliced,$rtrim);

        if(is_string($suffix) && strlen($suffix) && $sliced !== $value)
        {
            $suffixLength = static::len($suffix,$mb);
            $newLength = $length - $suffixLength;

            if($newLength > 0)
            {
                $sliced = static::sub(0,$newLength,$sliced,$mb);
                $return['suffix'] = $suffix;
            }
        }

        $return['str'] = static::fixUnicode($sliced);
        $return['strSuffix'] = $return['str'].$return['suffix'];

        return $return;
    }


    // output
    // fonction pour sortir une string
    // removeUnicode et trim
    public static function output(string $return,?array $option=null):string
    {
        $option = Arr::plus(['removeLineBreaks'=>false,'removeUnicode'=>true,'trim'=>true],$option);

        // enleve les sauts de ligne et les tabulations
        if(!empty($option['removeLineBreaks']))
        $return = static::removeLineBreaks($return);

        // removeUnicode
        if(!empty($option['removeUnicode']))
        $return = static::removeUnicode($return);
        else
        $return = static::fixUnicode($return);

        // trim les espaces
        if(!empty($option['trim']))
        $return = trim($return);

        return $return;
    }


    // eol
    // génère un ou plusieurs end of line
    // support pour ajouter le \r au besoin
    public static function eol(int $amount,bool $r=false):string
    {
        $return = '';
        $eol = ($r === true)? "\r\n":"\n";

        while ($amount > 0)
        {
            $return .= $eol;
            $amount--;
        }

        return $return;
    }
}
?>