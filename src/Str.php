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

// str
// class with static methods to work with strings
final class Str extends Root
{
    // config
    protected static array $config = [
        'charset'=>'UTF-8', // charset, peut être changé via encoding
        'plural'=>['letter'=>'s','wrap'=>'%'], // pour la fonction plural
        'excerpt'=>['suffix'=>'...'], // suffix pour la méthode excerpt
        'trim'=>" \t\n\r\0\x0B", // liste des caractères trimmés par défaut par les fonctions trim
        'eol'=>["\r\n","\n"], // défini les caractères end of line
        'search'=>' ', // séparateur pour prepareSearch
        'pointer'=>'-', // séparateur pour pointer
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
    final public static function typecast(&...$values):void
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
    final public static function typecastNotNull(&...$values):void
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
    final public static function is($value):bool
    {
        return is_string($value);
    }


    // isEmpty
    // retourne vrai si la valeur est string et vide
    final public static function isEmpty($value):bool
    {
        return is_string($value) && !strlen($value);
    }


    // isNotEmpty
    // retourne vrai si la valeur est string et non vide
    final public static function isNotEmpty($value):bool
    {
        return is_string($value) && strlen($value);
    }


    // isLength
    // retourne vrai si la length est celle spécifié
    final public static function isLength(int $length,$value,?bool $mb=null):bool
    {
        return is_string($value) && self::len($value,$mb) === $length;
    }


    // isMinLength
    // retourne vrai si la length est plus grande ou égale que celle spécifié
    final public static function isMinLength(int $length,$value,?bool $mb=null):bool
    {
        return is_string($value) && self::len($value,$mb) >= $length;
    }


    // isMaxLength
    // retourne vrai si la length est plus petite ou égale que celui spécifié
    final public static function isMaxLength(int $length,$value,?bool $mb=null):bool
    {
        return is_string($value) && self::len($value,$mb) <= $length;
    }


    // isStart
    // retourne vrai si la chaine contient le needle en début de chaine
    final public static function isStart(string $needle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;

            if($sensitive === true)
            $return = (self::pos($needle,$value,0,false) === 0);
            else
            $return = (self::ipos($needle,$value,0,true) === 0);
        }

        return $return;
    }


    // isStarts
    // retourne vrai si un des needles se retrouvent en début de chaîne
    final public static function isStarts(array $needles,$value,bool $sensitive=true):bool
    {
        $return = false;

        foreach ($needles as $needle)
        {
            $return = self::isStart($needle,$value,$sensitive);

            if($return === true)
            break;
        }

        return $return;
    }


    // isEnd
    // retourne vrai si la chaine contient le needle en fin de chaine
    final public static function isEnd(string $needle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $mb = ($sensitive === false);
            $length = self::len($value,$mb) - self::len($needle,$mb);

            if($sensitive === true)
            $return = (self::posRev($needle,$value,0,false) === $length);
            else
            $return = (self::iposRev($needle,$value,0,true) === $length);
        }

        return $return;
    }


    // isEnds
    // retoune vrai si un des needles se retrouvent en fin de chaîne
    final public static function isEnds(array $needles,$value,bool $sensitive=true):bool
    {
        $return = false;

        foreach ($needles as $needle)
        {
            $return = self::isEnd($needle,$value,$sensitive);

            if($return === true)
            break;
        }

        return $return;
    }


    // isStartEnd
    // retourne vrai si la chaine contient le needle en début et en fin de chaine
    final public static function isStartEnd(string $startNeedle,string $endNeedle,$value,bool $sensitive=true):bool
    {
        $return = false;

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = self::isStart($startNeedle,$value,$sensitive);

            if(!empty($return))
            $return = self::isEnd($endNeedle,$value,$sensitive);
        }

        return $return;
    }


    // isPattern
    // retourne vrai si la chaîne respecte le pattern donné en premier argument
    // utilisé pour détecté si *_id match avec session_id par exemple
    // supporte que le caractère char soit au début ou à la fin
    final public static function isPattern(string $pattern,$value,string $char='*',bool $sensitive=true):bool
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
                    if(self::isEnd($pattern,$value,$sensitive))
                    $return = true;
                }

                elseif(strpos($pattern,$char) === (strlen($pattern) - $charLen))
                {
                    $pattern = substr($pattern,0,-$charLen);
                    if(self::isStart($pattern,$value,$sensitive))
                    $return = true;
                }
            }
        }

        return $return;
    }


    // isLatin
    // retourne vrai si la chaîne contient seulement des caractères latin
    // utilisé pour bloquer des formulaires contact qui sont du spam
    final public static function isLatin($value):bool
    {
        $return = false;

        if(is_string($value) && !preg_match('/[^\\p{Common}\\p{Latin}]/u', $value))
        $return = true;

        return $return;
    }


    // hasNullByte
    // retourne vrai si la chaîne contient une null byte
    // peut être utilie pour détecter qu'une chaîne contient des caractères binaires
    final public static function hasNullByte($value):bool
    {
        return is_string($value) && strpos($value,"\0") !== false;
    }


    // isPrintable
    // retourne vrai si la chaîne est imprimable (tous les caractères)
    final public static function isPrintable($value):bool
    {
        return is_string($value) && ctype_print($value);
    }


    // icompare
    // compare que des valeurs sont égales, insensibles à la case
    // peut fournir des strings mais aussi des array uni ou multidimensinnel
    // mb true par défaut
    final public static function icompare(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $compare = null;

            foreach ($values as $value)
            {
                $return = false;

                if(is_string($value))
                $value = self::lower($value,true);

                elseif(is_array($value))
                $value = Arrs::map($value,fn($value) => (is_string($value))? self::lower($value,true):$value);

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
    final public static function prepend(...$values):string
    {
        $return = '';
        self::typecast(...$values);

        foreach ($values as $value)
        {
            $return = $value.$return;
        }

        return $return;
    }


    // append
    // ajoute des chaînes une après l'autre
    final public static function append(...$values):string
    {
        $return = '';
        self::typecast(...$values);

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
    final public static function cast($return,?string $separator=null,bool $fixUnicode=false):string
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
        $return = self::fixUnicode($return);

        return $return;
    }


    // castFix
    // comme cast, mais la valeur fixUnicode est true
    final public static function castFix($return,?string $separator=null):string
    {
        return self::cast($return,$separator,true);
    }


    // toNum
    // transforme une string en int ou float, ou en string si la longueur dépasse le maximum autorisé
    // peut retourner un int ou float
    final public static function toNum(string $value,string $keep=',')
    {
        $return = null;
        $value = self::keepNum($value,$keep);

        if(strlen($value))
        $return = Num::cast($value,true);

        return $return;
    }


    // toInt
    // transforme un numéro en string en int
    final public static function toInt(string $value):?int
    {
        $value = self::toNum($value);

        return ($value !== null)? (int) $value:null;
    }


    // toFloat
    // transforme un numéro en string en float
    final public static function toFloat(string $value,string $keep=','):?float
    {
        $value = self::toNum($value,$keep);

        return ($value !== null)? (float) $value:null;
    }


    // len
    // count le nombre de caractère dans une string
    final public static function len(string $value,?bool $mb=null):int
    {
        $return = 0;
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strlen($value,self::$config['charset']);
        else
        $return = strlen($value);

        return $return;
    }


    // lenWith
    // retourne la longueur de la chaîne tant qu'elle contient les caractères spécifiés dans chars
    final public static function lenWith(string $chars,string $str,int $start=0,?int $length=null):int
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
    final public static function lenWithout(string $chars,string $str,int $start=0,?int $length=null):int
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
    final public static function pos(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = self::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strpos($str,$needle,$offset,self::$config['charset']);
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
    final public static function posRev(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = self::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strrpos($str,$needle,$offset,self::$config['charset']);
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
    final public static function ipos(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = self::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_stripos($str,$needle,$offset,self::$config['charset']);

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
    final public static function iposRev(string $needle,string $str,$offset=0,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$str,$offset);

        if(is_string($offset))
        $offset = self::len($offset,$mb);

        if(is_int($offset) && strlen($needle))
        {
            if($mb === true)
            $pos = mb_strripos($str,$needle,$offset,self::$config['charset']);
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
    final public static function posIpos(string $needle,string $str,bool $sensitive=true):?int
    {
        $return = null;

        if($sensitive === true)
        $pos = strpos($str,$needle);

        else
        $pos = mb_stripos($str,$needle,0,self::$config['charset']);

        if(is_int($pos))
        $return = $pos;

        return $return;
    }


    // in
    // retourne vrai si la chaine contient le needle
    final public static function in(string $needle,string $str,bool $sensitive=true,int $offset=0):bool
    {
        $return = false;

        if($sensitive === true)
        $position = self::pos($needle,$str,$offset,false);

        else
        $position = self::ipos($needle,$str,$offset,true);

        if(is_numeric($position))
        $return = true;

        return $return;
    }


    // ins
    // retourne vrai si la chaine contient tous les needle
    final public static function ins(array $needles,string $str,bool $sensitive=true,int $offset=0):bool
    {
        $return = false;

        if(!empty($needles))
        {
            $return = true;

            foreach ($needles as $needle)
            {
                if($sensitive === true)
                $position = self::pos($needle,$str,$offset,false);

                else
                $position = self::ipos($needle,$str,$offset,true);

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
    final public static function inFirst(array $needles,string $str,bool $sensitive=true,int $offset=0)
    {
        $return = null;

        foreach ($needles as $needle)
        {
            if($sensitive === true)
            $position = self::pos($needle,$str,$offset,false);

            else
            $position = self::ipos($needle,$str,$offset,true);

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
    final public static function search($needle,string $str,bool $sensitive=true,bool $accentSensitive=true,bool $prepare=false,?string $separator=null):bool
    {
        $return = false;

        if(is_string($needle))
        {
            if($prepare === true)
            $needle = self::prepareSearch($needle,$separator);
            else
            $needle = [$needle];
        }

        if(is_array($needle) && !empty($needle))
        {
            $return = true;

            if($accentSensitive === false)
            $str = self::replaceAccent($str);

            foreach ($needle as $n)
            {
                if(is_string($n) && $accentSensitive === false)
                $n = self::replaceAccent($n);

                if(!is_string($n) || !self::in($n,$str,$sensitive))
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
    final public static function prepareSearch($value,?string $separator=null):array
    {
        $return = [];
        $separator = (is_string($separator))? $separator:self::$config['search'];

        if(is_scalar($value))
        {
            $value = (string) $value;
            $return = self::explodeTrimClean($separator,$value);
        }

        return $return;
    }


    // sub
    // coupe une chaîne avec un début et un length
    // si offset ou length sont des chaînes, calcule leur longueur avec len
    // si len est null, prend la longueur de value
    final public static function sub($offset,$length,string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$value,$offset,$length);
        $offset = (is_string($offset))? self::len($offset,$mb):$offset;
        $length = (is_string($length))? self::len($length,$mb):$length;
        $length = ($length === null)? self::len($value,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($mb === true)
            $return = mb_substr($value,$offset,$length,self::$config['charset']);
            else
            $return = substr($value,$offset,$length);
        }

        return $return;
    }


    // subFirst
    // retourne le premier caractère d'une string
    final public static function subFirst(string $str,int $amount=1,?bool $mb=null):string
    {
        return self::sub(0,$amount,$str,$mb);
    }


    // subLast
    // retourne le dernier caractère d'une string
    final public static function subLast(string $str,int $amount=1,?bool $mb=null):string
    {
        return self::sub(-$amount,$amount,$str,$mb);
    }


    // cut
    // comme sub mais utilise mb_strcut plutôt que mb_substr
    // donc utilise le nombre de bytes plutôt que de caractères
    // si offset ou length sont des chaînes, calcule leur longueur avec len
    // si len est null, prend la longueur de value
    final public static function cut($offset,$length,string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$value,$offset,$length);
        $offset = (is_string($offset))? self::len($offset,$mb):$offset;
        $length = (is_string($length))? self::len($length,$mb):$length;
        $length = ($length === null)? self::len($value,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($mb === true)
            $return = mb_strcut($value,$offset,$length,self::$config['charset']);
            else
            $return = substr($value,$offset,$length);
        }

        return $return;
    }


    // subCount
    // compe le nombre d'occurence d'une sous-chaîne
    // si offset ou length sont des chaînes, calculent leur longueur avec strlen
    final public static function subCount(string $needle,string $value,$offset=null,$length=null,?bool $mb=null):int
    {
        $return = 0;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$needle,$value,$offset,$length);
        $offset = (is_string($offset))? self::len($offset,$mb):$offset;
        $length = (is_string($length))? self::len($length,$mb):$length;

        if($offset !== null)
        $value = self::sub($offset,$length,$value,$mb);

        if($mb === true)
        $return = mb_substr_count($value,$needle,self::$config['charset']);
        else
        $return = substr_count($value,$needle);

        return $return;
    }


    // subReplace
    // fonction simplifé de substr_replace qui gère les multibyte
    // la fonction n'accepte pas de tableau en argument
    // si start ou length sont des chaînes, calculent leur longueur avec strlen
    // replace peut être scalar, string ou un tableau
    final public static function subReplace($offset,$length,$replace,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$offset,$length,$str,$replace);
        $offset = (is_string($offset))? self::len($offset,$mb):$offset;
        $length = (is_string($length))? self::len($length,$mb):$length;

        if(is_array($replace))
        $replace = self::charImplode($replace);

        if(is_scalar($replace))
        $replace = (string) $replace;

        if(is_int($offset) && is_int($length) && is_string($replace))
        {
            if($mb === true)
            {
                $split = self::chars($str,$mb);
                $replace = self::chars($replace,$mb);
                $splice = Arr::spliceIndex($offset,$length,$split,$replace);
                $return = self::charImplode($splice);
            }

            else
            $return = substr_replace($str,$replace,$offset,$length);
        }

        return $return;
    }


    // subCompare
    // compare la substring de main avec str donné en premier argument
    // la fonction supporte multibyte et que offset et length soient des string
    final public static function subCompare(string $str,$offset,$length,string $main,bool $sensitive=true,?bool $mb=null):?int
    {
        $return = null;
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$offset,$length,$main);
        $offset = (is_string($offset))? self::len($offset,$mb):$offset;
        $length = (is_string($length))? self::len($length,$mb):$length;

        if(is_int($offset) && is_int($length))
        {
            if($sensitive === false)
            {
                $str = self::lower($str,$mb);
                $main = self::lower($main,$mb);
            }

            $return = substr_compare($main,$str,$offset,$length,($sensitive === true)? false:true);
        }

        return $return;
    }


    // subSearch
    // retourne la string coupé à la première occurence d'un des caractère chars
    final public static function subSearch(string $chars,string $str):string
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
    final public static function startEndIndex(string $needle,string $str,bool $sensitive=true):?int
    {
        $return = null;

        if(self::isStart($needle,$str,$sensitive))
        $return = 0;

        elseif(self::isEnd($needle,$str,$sensitive))
        $return = -1;

        return $return;
    }


    // stripWrap
    // permet de faire un wrapStart, stripStart, wrapEnd ou stripEnd selon deux valeurs booléens
    final public static function stripWrap(string $needle,string $return,?bool $start=null,?bool $end=null,bool $sensitive=true):string
    {
        if($start === true)
        $return = self::wrapStart($needle,$return,$sensitive);

        if($end === true)
        $return = self::wrapEnd($needle,$return,$sensitive);

        if($start === false)
        $return = self::stripStart($needle,$return,$sensitive);

        if($end === false && $return !== $needle)
        $return = self::stripEnd($needle,$return,$sensitive);

        return $return;
    }


    // stripStart
    // retourne la chaine sans le needle du debut
    final public static function stripStart(string $needle,string $return,bool $sensitive=true):string
    {
        $mb = ($sensitive === false);

        if(self::isStart($needle,$return,$sensitive))
        $return = self::sub($needle,null,$return,$mb);

        return $return;
    }


    // stripEnd
    // retourne la chaine sans le needle de la fin
    final public static function stripEnd(string $needle,string $return,bool $sensitive=true):string
    {
        $mb = ($sensitive === false);

        if(self::isEnd($needle,$return,$sensitive))
        $return = self::sub(0,-self::len($needle,$mb),$return,$mb);

        return $return;
    }


    // stripStartEnd
    // retourne le chaîne sans le needle de la fin et du début si existant
    final public static function stripStartEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        if(self::isStartEnd($startNeedle,$endNeedle,$return,$sensitive))
        {
            $mb = ($sensitive === false);
            $return = self::sub($startNeedle,null,$return,$mb);
            $return = self::sub(0,-self::len($endNeedle,$mb),$return,$mb);
        }

        return $return;
    }


    // stripStartOrEnd
    // retourne le chaîne sans le needle de la fin et du début
    final public static function stripStartOrEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        $return = self::stripStart($startNeedle,$return,$sensitive);
        $return = self::stripEnd($endNeedle,$return,$sensitive);

        return $return;
    }


    // wrapStart
    // wrap une chaîne au début si elle ne l'est pas déjà
    final public static function wrapStart(string $needle,string $return,bool $sensitive=true):string
    {
        if(!self::isStart($needle,$return,$sensitive))
        $return = $needle.$return;

        return $return;
    }


    // wrapEnd
    // wrap une chaîne à la fin si elle ne l'est pas déjà
    final public static function wrapEnd(string $needle,string $return,bool $sensitive=true):string
    {
        if(!self::isEnd($needle,$return,$sensitive))
        $return = $return.$needle;

        return $return;
    }


    // wrapStartEnd
    // wrap une chaîne si elle n'est pas déjà wrap au début et à la fin
    final public static function wrapStartEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        if(!self::isStartEnd($startNeedle,$endNeedle,$return,$sensitive))
        $return = $startNeedle.$return.$endNeedle;

        return $return;
    }


    // wrapStartOrEnd
    // wrap une chaîne si elle n'est pas déjà wrap au début ou à la fin
    final public static function wrapStartOrEnd(string $startNeedle,string $endNeedle,string $return,bool $sensitive=true):string
    {
        $isStart = self::isStart($startNeedle,$return,$sensitive);
        $isEnd = self::isEnd($endNeedle,$return,$sensitive);

        if(!$isStart)
        $return = $startNeedle.$return;

        if(!$isEnd)
        $return = $return.$endNeedle;

        return $return;
    }


    // stripFirst
    // enlève le premier caractère d'une string
    final public static function stripFirst(string $str,int $amount=1,?bool $mb=null):string
    {
        return self::sub($amount,null,$str,$mb);
    }


    // stripLast
    // enlève le dernier caractère d'une string
    final public static function stripLast(string $str,int $amount=1,?bool $mb=null):string
    {
        return self::sub(0,-$amount,$str,$mb);
    }


    // addPattern
    // ajoute un pattern à une chaîne, utiliser par les colonnes et cellules
    final public static function addPattern(string $pattern,$value,string $char='*'):?string
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
    final public static function stripPattern(string $pattern,$value,string $char='*',bool $sensitive=true):?string
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
                    if(self::isEnd($pattern,$value,$sensitive))
                    $return = self::stripEnd($pattern,$value,$sensitive);
                }

                elseif(strpos($pattern,$char) === (strlen($pattern) - $charLen))
                {
                    $pattern = substr($pattern,0,-$charLen);
                    if(self::isStart($pattern,$value,$sensitive))
                    $return = self::stripStart($pattern,$value,$sensitive);
                }
            }
        }

        return $return;
    }


    // stripBefore
    // retourne la chaîne avant la première occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    final public static function stripBefore(string $char,string $value,bool $includeChar=true,bool $sensitive=true,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$char,$value);

        if(!empty($char))
        {
            if($sensitive === true)
            {
                if($mb === true)
                $strip = mb_strstr($value,$char,false,self::$config['charset']);
                else
                $strip = strstr($value,$char,false);
            }

            else
            {
                if($mb === true)
                $strip = mb_stristr($value,$char,false,self::$config['charset']);
                else
                $strip = stristr($value,$char,false);
            }

            if($strip !== false)
            {
                $return = $strip;
                if($includeChar === false)
                $return = self::sub($char,null,$strip,$mb);
            }
        }

        return $return;
    }


    // stripBeforeReverse
    // retourne la chaîne après la dernière occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    // seulement les fonctions mb sont utilisés
    final public static function stripBeforeReverse(string $char,string $value,bool $includeChar=true,bool $sensitive=true):string
    {
        $return = '';

        if(!empty($char))
        {
            if($sensitive === true)
            $strip = mb_strrchr($value,$char,false,self::$config['charset']);

            else
            $strip = mb_strrichr($value,$char,false,self::$config['charset']);

            if($strip !== false)
            {
                $return = $strip;

                if($includeChar === false)
                $return = self::sub($char,null,$strip,true);
            }
        }

        return $return;
    }


    // stripAfter
    // retourne la chaîne après la première occurence de char
    // possibilité d'inclure le caractère diviseur via includeChar
    final public static function stripAfter(string $char,string $value,bool $includeChar=false,bool $sensitive=true,?bool $mb=null):?string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$char,$value);

        if(!empty($char))
        {
            if($sensitive === true)
            {
                if($mb === true)
                $strip = mb_strstr($value,$char,true,self::$config['charset']);
                else
                $strip = strstr($value,$char,true);
            }

            else
            {
                if($mb === true)
                $strip = mb_stristr($value,$char,true,self::$config['charset']);
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
    final public static function stripAfterReverse(string $char,string $value,bool $includeChar=false,bool $sensitive=true):string
    {
        $return = '';

        if(!empty($char))
        {
            if($sensitive === true)
            $strip = mb_strrchr($value,$char,true,self::$config['charset']);

            else
            $strip = mb_strrichr($value,$char,true,self::$config['charset']);

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
    final public static function changeBefore(string $char,string $change,string $return,bool $sensitive=true,?bool $mb=null):string
    {
        if(!empty($char))
        {
            $after = self::stripBefore($char,$return,false,$sensitive,$mb);

            if(!empty($after))
            $return = $change.$char.$after;
        }

        return $return;
    }


    // changeAfter
    // change la fin d'une string après la présence de char
    // si aucun changement n'est effectué, retourne la string initiale
    final public static function changeAfter(string $char,string $change,string $return,bool $sensitive=true,?bool $mb=null):string
    {
        if(!empty($char))
        {
            $before = self::stripAfter($char,$return,false,$sensitive,$mb);

            if(!empty($before))
            $return = $before.$char.$change;
        }

        return $return;
    }


    // lower
    // lowercase pour tous les caractères de la chaîne
    final public static function lower(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strtolower($value,self::$config['charset']);
        else
        $return = strtolower($value);

        return $return;
    }


    // lowerFirst
    // met lowercase la première lettre de la chaîne, support pour mb
    final public static function lowerFirst(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $lower = self::lower(self::subFirst($value,1,$mb),$mb);
            $return = $lower.self::stripFirst($value,1,$mb);
        }

        else
        $return = lcfirst($value);

        return $return;
    }


    // upper
    // uppercase pour tous les caractères de la chaîne
    final public static function upper(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_strtoupper($value,self::$config['charset']);
        else
        $return = strtoupper($value);

        return $return;
    }


    // upperFirst
    // capitalize la première lettre de la chaîne, support pour mb
    final public static function upperFirst(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $higher = self::upper(self::subFirst($value,1,$mb),$mb);
            $return = $higher.self::stripFirst($value,1,$mb);
        }

        else
        $return = ucfirst($value);

        return $return;
    }


    // capitalize
    // capitalize le premier mot de la chaîne
    final public static function capitalize(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        {
            $higher = self::upper(self::subFirst($value,1,$mb),$mb);
            $lower = self::lower(self::stripFirst($value,1,$mb),$mb);
            $return = $higher.$lower;
        }

        else
        $return = ucfirst(strtolower($value));

        return $return;
    }


    // title
    // capitalize chaque mot dans la chaîne
    final public static function title(string $value,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$value);

        if($mb === true)
        $return = mb_convert_case($value,MB_CASE_TITLE,self::$config['charset']);
        else
        $return = ucwords(strtolower($value));

        return $return;
    }


    // reverse
    // invertit une string
    final public static function reverse(string $str,?bool $mb=null):string
    {
        $return = '';

        if($mb === true)
        {
            $split = self::chars($str,$mb);
            if(!empty($split))
            {
                $reverse = array_reverse($split);
                $return = self::charImplode($reverse);
            }
        }
        else
        $return = strrev($str);

        return $return;
    }


    // shuffle
    // mélange une string
    final public static function shuffle(string $str,?bool $mb=null):string
    {
        $return = '';

        if($mb === true)
        {
            $split = self::chars($str,$mb);
            if(!empty($split))
            {
                shuffle($split);
                $return = self::charImplode($split);
            }
        }
        else
        $return = str_shuffle($str);

        return $return;
    }


    // pad
    // pad la chaîne à gauche et à droite avec le caractère input jusqu'à la length donnée
    final public static function pad(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $return = $str;
            $strLength = self::len($str,$mb);

            if($length > $strLength)
            {
                $return = $str;
                $start = '';
                $startLength = (int) floor(($length - $strLength) / 2);
                $end = '';
                $endLength = (int) ceil(($length - $strLength) / 2);

                while (self::len($start,$mb) < $startLength)
                {
                    $start .= $input;
                }
                $start = self::sub(0,$startLength,$start,$mb);

                while (self::len($end,$mb) < $endLength)
                {
                    $end .= $input;
                }
                $end = self::sub(0,$endLength,$end,$mb);

                $return = $start.$str.$end;
            }
        }
        else
        $return = str_pad($str,$length,$input,STR_PAD_BOTH);

        return $return;
    }


    // padLeft
    // pad la chaîne à gauche avec le caractère input jusqu'à la length donnée
    final public static function padLeft(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $strLength = self::len($str,$mb);

            if($length > $strLength)
            {
                while (self::len($return,$mb) < $length)
                {
                    $return .= $input;
                }

                $return = self::sub(0,$length,$return,$mb);
                $return = self::subReplace(-$strLength,$strLength,$str,$return,$mb);
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
    final public static function padRight(string $input,int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMbs($mb,$str,$input);

        if($mb === true)
        {
            $return = $str;

            if($length > self::len($str,$mb))
            {
                while (self::len($return,$mb) < $length)
                {
                    $return .= $input;
                }

                $return = self::sub(0,$length,$return,$mb);
            }
        }
        else
        $return = str_pad($str,$length,$input,STR_PAD_RIGHT);

        return $return;
    }


    // split
    // split une chaîne par longueur
    // length permet de définir la longueur du split
    final public static function split(int $length=1,string $str,?bool $mb=null):array
    {
        $return = [];
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);

        if($length > 0)
        {
            if($mb === true)
            $return = mb_str_split($str,$length,self::$config['charset']);

            else
            $return = str_split($str,$length);
        }

        return $return;
    }


    // chars
    // retourne un tableau avec une entrée par caractère
    final public static function chars(string $str,?bool $mb=null):array
    {
        return self::split(1,$str,$mb);
    }


    // charCount
    // retourne un tableau avec chaque caractère et sa fréquence dans la chaîne
    // si mb est numérique, on envoie à la fonction count_chars qui retourne les octets pour des caractères
    final public static function charCount(string $str,$mb=null):array
    {
        $return = [];

        if(is_int($mb))
        $return = count_chars($str,$mb);

        elseif(is_scalar($mb) || $mb === null)
        {
            $split = self::chars($str,$mb);

            if(!empty($split))
            $return = Arr::countValues($split);
        }

        return $return;
    }


    // charImplode
    // implode un tableau de caractère en une chaîne
    final public static function charImplode(array $array):string
    {
        return implode('',$array);
    }


    // charSplice
    // alias de subReplace
    final public static function charSplice($offset,$length,$replace,string $str,?bool $mb=null):string
    {
        return self::subReplace($offset,$length,$replace,$str,$mb);
    }


    // normalizeLine
    // permet de régurilariser la situation des line breaks dans une chaîne
    final public static function normalizeLine(string $str,string $separator=PHP_EOL):string
    {
        return preg_replace('~\R~u',$separator,$str);
    }


    // lines
    // retourne un tableau de la chaîne divisé par ligne
    // support pour tous les diviseurs de ligne
    // trim permet d'enlever les espaces au début et à la fin de chaque ligne
    final public static function lines(string $str,bool $trim=false):array
    {
        $return = explode(PHP_EOL,self::normalizeLine($str));

        if($trim === true)
        $return = Arr::map($return,fn($value) => trim($value));

        return $return;
    }


    // lineCount
    // retourne le nombre de ligne dans la chaîne
    final public static function lineCount(string $str):int
    {
        return count(self::lines($str));
    }


    // lineImplode
    // implode un tableau dans une string, le séparateur est PHP_EOL
    final public static function lineImplode(array $value,string $separator=PHP_EOL):string
    {
        return implode($separator,$value);
    }


    // lineSplice
    // permet d'ajouter ou remplacer une ou plusieurs lignes dans la chaîne
    // replace peut être array, string ou null
    final public static function lineSplice(int $offset,?int $length,$replace=[],string $str):string
    {
        $return = '';
        $lines = self::lines($str);

        if(is_scalar($replace))
        $replace = (array) $replace;

        if(!empty($lines) && (is_array($replace) || $replace === null))
        {
            $splice = Arr::spliceIndex($offset,$length,$lines,$replace);
            $return = self::lineImplode($splice);
        }

        return $return;
    }


    // words
    // retourne un tableau des mots
    // str_word_count n'est pas utilisé pour pour cette méthode car les résultats de la fonction sont inconsistents
    final public static function words(string $str,?bool $mb=null):array
    {
        $return = [];
        $i = 0;

        foreach (explode(' ',$str) as $v)
        {
            $oriLen = self::len($v,$mb);
            $v = self::removeWhiteSpace($v);
            $len = self::len($v,$mb);

            if($len > 0)
            $return[$i] = $v;

            $i += ($oriLen + 1);
        }

        return $return;
    }


    // wordCount
    // retourne le nombre de mot dans la string
    final public static function wordCount(string $str):int
    {
        return count(self::words($str));
    }


    // wordExplode
    // explode un tableau de mot, plus rapide que words
    final public static function wordExplode(string $value,?int $limit=null,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $value = self::removeWhiteSpace($value);
        $return = self::explode(' ',$value,$limit,$trim,$clean);

        return $return;
    }


    // wordExplodeIndex
    // retoure un index du tableau de words explosé
    final public static function wordExplodeIndex(int $index,string $value,?int $limit=null,bool $trim=false,bool $clean=false):?string
    {
        return Arr::index($index,self::wordExplode($value,$limit,$trim,$clean));
    }


    // wordImplode
    // implode un tableau en une string de mots
    final public static function wordImplode(array $array):string
    {
        return implode(' ',$array);
    }


    // wordSplice
    // permet d'ajouter ou remplacer un ou plusieurs mots dans la chaîne
    // replace peut être array, string ou null
    final public static function wordSplice(int $offset,?int $length,$replace=[],string $str,?bool $mb=null):string
    {
        $return = '';
        $words = self::words($str,$mb);

        if(is_scalar($replace))
        $replace = (array) $replace;

        if(!empty($words) && (is_array($replace) || $replace === null))
        {
            $splice = Arr::spliceIndex($offset,$length,$words,$replace);
            $return = self::wordImplode($splice);
        }

        return $return;
    }


    // wordSliceLength
    // retourne une string avec les mots avec une longueur entre min et max
    final public static function wordSliceLength(int $min,?int $max,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);
        $max = ($max === null)? PHP_INT_MAX:$max;
        $array = [];

        foreach (self::words($str,$mb) as $word)
        {
            $wordLen = self::len($word,$mb);

            if(Num::in($min,$wordLen,$max))
            $array[] = $word;
        }

        if(!empty($array))
        $return = self::wordImplode($array);

        return $return;
    }


    // wordStripLength
    // retourne une string sans les mots avec une longueur entre min et max
    final public static function wordStripLength(int $min,?int $max,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);
        $array = [];
        $max = ($max === null)? PHP_INT_MAX:$max;

        foreach (self::words($str,$mb) as $word)
        {
            $wordLen = self::len($word,$mb);

            if(!Num::in($min,$wordLen,$max))
            $array[] = $word;
        }

        if(!empty($array))
        $return = self::wordImplode($array);

        return $return;
    }


    // wordTotalLength
    // retourne une string avec les mots rentrant dans une longueur totale
    // va retourne un mot truncate si le premier mot est plus court que length
    final public static function wordTotalLength(int $length,string $str,?bool $mb=null):string
    {
        $return = '';
        $mb = (is_bool($mb))? $mb:Encoding::getMb($mb,$str);

        if(self::len($str,$mb) <= $length)
        $return = $str;

        else
        {
            $array = [];
            $inLength = 0;

            foreach ($words = self::words($str) as $word)
            {
                $wordLength = self::len($word,$mb);

                if(empty($inLength) && $wordLength >= $length)
                {
                    $array[] = self::sub(0,$length,$word,$mb);
                    $inLength += $wordLength + 1;
                }

                elseif(($inLength + $wordLength) <= $length)
                {
                    $array[] = $word;
                    $inLength += $wordLength + 1;
                }
            }

            if(!empty($array))
            $return = self::wordImplode($array);
        }

        return $return;
    }


    // wordwrap
    // wrapper pour word_wrap
    // fonctionne avec mb
    final public static function wordwrap(int $width=75,string $str,string $break=PHP_EOL,bool $cut=false,?bool $mb=null):string
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
                    if(self::len($line,$mb) <= $width)
                    continue;

                    $words = explode(' ',$line);
                    $line = '';
                    foreach ($words as $word)
                    {
                        if(self::len($actual.$word,$mb) <= $width)
                        $actual .= $word.' ';
                        else
                        {
                            if($actual !== '')
                            $line .= rtrim($actual).$break;

                            $actual = $word;
                            if($cut === true)
                            {
                                while (self::len($actual,$mb) > $width)
                                {
                                    $line .= self::sub(0,$width,$actual,$mb).$break;
                                    $actual = self::sub($width,null,$actual,$mb);
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


    // replacePrepare
    // prépare le tableau de remplacement pour replace, ireplace et replaceOne
    // si value est scalar transformé en string, si null transformé en empty string
    final protected static function replacePrepare(array $array):array
    {
        $return = [];

        foreach ($array as $k => $v)
        {
            $k = (string) $k;

            if(is_object($v))
            $v = Obj::cast($v);

            if(is_scalar($v))
            $v = (string) $v;

            if($v === null)
            $v = '';

            if(is_string($v))
            $return[$k] = $v;
        }

        return $return;
    }


    // replace
    // remplace tous les éléments dans une chaîne à partir d'un tableau from => to
    // il est possible qu'une même chaîne soit remplacé plusieurs fois
    final public static function replace(array $replace,string $return,bool $once=true,bool $sensitive=true):string
    {
        $replace = self::replacePrepare($replace);

        if($sensitive === false)
        $once = false;

        if($once === true)
        $return = strtr($return,$replace);

        else
        {
            $keys = array_keys($replace);
            $values = array_values($replace);

            if($sensitive === true)
            $return = str_replace($keys,$values,$return);

            else
            $return = str_ireplace($keys,$values,$return);
        }

        return $return;
    }


    // ireplace
    // remplace tous les éléments dans une chaîne à partir d'un tableau from => to
    // non sensible à la case, utilise str_ireplace dont un remplacement peut avoir l'air plus d'une fois
    final public static function ireplace(array $replace,string $return):string
    {
        return self::replace($replace,$return,false,false);
    }


    // explode
    // explose une string selon un delimiter
    // si trim est true, passe chaque élément du tableau dans trim
    // si clean est true, enlève les entrées du tableau cleanable
    final public static function explode(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        $return = explode($delimiter,$value,($limit === null)? PHP_INT_MAX:$limit);

        if(!empty($return))
        {
            if($trim === true)
            $return = Arr::map($return,fn($value) => trim($value));

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
    final public static function explodeTrim(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $clean=false):array
    {
        return self::explode($delimiter,$value,$limit,true,$clean);
    }


    // explodeClean
    // explose une string selon un delimiter avec le paramètre clean à true
    final public static function explodeClean(string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false):array
    {
        return self::explode($delimiter,$value,$limit,$trim,true);
    }


    // explodeTrimClean
    // explose une string selon un delimiter avec les paramètres trim et clean à true
    final public static function explodeTrimClean(string $delimiter,string $value,?int $limit=PHP_INT_MAX):array
    {
        return self::explode($delimiter,$value,$limit,true,true);
    }


    // explodeIndex
    // explose une string selon un delimiter
    // retourne un index de l'explostion ou null si n'existe pas
    final public static function explodeIndex(int $index,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):?string
    {
        return Arr::index($index,self::explode($delimiter,$value,$limit,$trim,$clean));
    }


    // explodeIndexes
    // explose une string selon un delimiter
    // retourne un tableau des index existants
    final public static function explodeIndexes(array $indexes,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        return Arr::indexes($indexes,self::explode($delimiter,$value,$limit,$trim,$clean));
    }


    // explodeIndexesExists
    // explose une string selon un delimiter
    // retourne le tableau si les index existent sinon null
    final public static function explodeIndexesExists(array $indexes,string $delimiter,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):?array
    {
        $return = null;
        $explode = self::explode($delimiter,$value,$limit,$trim,$clean);

        if(Arr::indexesExists($indexes,$explode))
        $return = $explode;

        return $return;
    }


    // explodeKeyValue
    // explose les valeurs d'une string et retourne un tableau sous une forme clé -> valeur
    final public static function explodeKeyValue(string $delimiter,string $value,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $x = self::explode($delimiter,$value,2,$trim,$clean);

        if(Arr::isKey($x[0]) && count($x) === 2)
        $return[$x[0]] = $x[1];

        return $return;
    }


    // explodes
    // explosion d'une chaîne en fonction de multiples delimiteurs
    final public static function explodes(array $delimiters,string $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $delimiter = array_shift($delimiters);

        if(is_string($delimiter) && !empty($delimiter))
        {
            $return = self::explode($delimiter,$value,$limit,$trim,$clean);

            if(!empty($delimiters))
            {
                $explodes = [];
                foreach ($return as $v)
                {
                    $explodes[] = self::explodes($delimiters,$v,$limit,$trim,$clean);
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
    final public static function trim(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? self::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = trim($value,$trim);

        return $return;
    }


    // trimLeft
    // wrapper pour la fonction php ltrim
    // ajouter des caractères extra à effacer dans extraTrim
    // default permet d'utiliser les caractères effacés par défaut comme base
    final public static function trimLeft(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? self::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = ltrim($value,$trim);

        return $return;
    }


    // trimRight
    // wrapper pour la fonction php rtrim
    // ajouter des caractères extra à effacer dans extraTrim
    // default permet d'utiliser les caractères effacés par défaut comme base
    final public static function trimRight(string $value,$extraTrim=null,bool $default=true):string
    {
        $return = '';
        $trim = ($default === true)? self::$config['trim']:'';
        if(is_string($extraTrim))
        $trim .= $extraTrim;

        $return = rtrim($value,$trim);

        return $return;
    }


    // repeatLeft
    // repeat un caractère un nombre de fois au début d'une chaine
    final public static function repeatLeft(string $input,int $multiplier=2,string $return):string
    {
        if($multiplier > 0)
        $return = str_repeat($input,$multiplier).$return;

        return $return;
    }


    // repeatRight
    // repeat un caractère un nombre de fois à la fin d'une chaine
    final public static function repeatRight(string $input,int $multiplier=2,string $return):string
    {
        if($multiplier > 0)
        $return = $return.str_repeat($input,$multiplier);

        return $return;
    }


    // addSlash
    // ajoute un backslash avant chaque quote ou doubleQuote
    final public static function addSlash(string $value):string
    {
        return addslashes($value);
    }


    // stripSlash
    // enlève les backslash à gauche de chaque quote ou doubleQuote
    final public static function stripSlash(string $value):string
    {
        return stripslashes($value);
    }


    // quote
    // enrobe une string de quote single ou double
    final public static function quote(string $return,bool $double=false):string
    {
        return self::wrapStartOrEnd($quote = ($double === true)? '"':"'",$quote,$return);
    }


    // unquote
    // dérobe une string de quote single et/ou double
    final public static function unquote(string $return,bool $single=true,bool $double=true):string
    {
        if($single === true)
        $return = self::stripStartOrEnd("'","'",$return);

        if($double === true)
        $return = self::stripStartOrEnd('"','"',$return);

        return $return;
    }


    // doubleToSingleQuote
    // transforme les doubles quotes en single quotes
    final public static function doubleToSingleQuote(string $return):string
    {
        return str_replace('"',"'",$return);
    }


    // singleToDoubleQuote
    // transforme les single quotes en double quotes
    final public static function singleToDoubleQuote(string $return):string
    {
        return str_replace("'",'"',$return);
    }


    // quoteChar
    // permet d'ajouter un slash à tous les caractères données en argument présent dans la string
    final public static function quoteChar(string $return,$chars):string
    {
        if(is_array($chars))
        $chars = implode('',$chars);

        $return = addcslashes($return,$chars);

        return $return;
    }


    // commaToDecimal
    // transforme la virgule en decimal
    final public static function commaToDecimal(string $value):string
    {
        return str_replace(',','.',$value);
    }


    // decimalToComma
    // transforme la decimal en virgule
    final public static function decimalToComma(string $value):string
    {
        return str_replace('.',',',$value);
    }


    // similar
    // calcule le pourcentage de similitude entre deux chaînes
    // par défaut les string sont passés à la méthode asciiLower
    final public static function similar(string $value,string $value2,bool $asciiLower=true):?float
    {
        $return = null;

        if($asciiLower === true)
        {
            $value = self::asciiLower($value);
            $value2 = self::asciiLower($value2);
        }

        similar_text($value,$value2,$return);

        return $return;
    }


    // levenshtein
    // calcule la distance levenshtein entre deux chaînes
    // par défaut les string sont passés à la méthode asciiLower
    final public static function levenshtein(string $value,string $value2,bool $asciiLower=true):?int
    {
        $return = null;

        if($asciiLower === true)
        {
            $value = self::asciiLower($value);
            $value2 = self::asciiLower($value2);
        }

        $return = levenshtein($value,$value2);

        return $return;
    }


    // random
    // génère un string random en utilisant mt_rand ou csprng
    final public static function random(int $length=40,?string $random=null,bool $csprng=false,?bool $mb=null):string
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
                $split = self::chars($random,$mb);
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
    final public static function randomPrefix(string $return='',int $length=40,?string $random=null,bool $csprng=false,?bool $mb=null):string
    {
        if(!empty($return))
        $return = self::keepAlphanumeric($return);

        $return .= self::random($length,$random,$csprng,$mb);

        return $return;
    }


    // explodeCamelCase
    // explose une string camelCase, retourne un tableau
    final public static function explodeCamelCase(string $str):array
    {
        return preg_split('#([A-Z][^A-Z]*)#',$str,0,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }


    // fromCamelCase
    // permet de transformer une string camelcase vers une string avec séparateur
    final public static function fromCamelCase(string $delimiter,string $value):string
    {
        $return = '';
        $explode = self::explodeCamelCase($value);

        if(!empty($explode))
        {
            $closure = fn(string $value):string => strtolower(trim($value,$delimiter));
            $explode = Arr::map($explode,$closure);
            $return = implode($delimiter,$explode);
        }

        return $return;
    }


    // toCamelCase
    // permet de transformer une string ou un tableau en camelCase bien formatté
    final public static function toCamelCase(string $delimiter,$value,?bool $mb=null):string
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
                    $camelCase[] = self::capitalize($v,$mb);
                    else
                    $camelCase[] = self::lower($v,$mb);

                    $i++;
                }
            }

            $return = self::charImplode($camelCase);
        }

        return $return;
    }


    // loremIpsum
    // génère du texte lorem ipsum
    // separator new line
    final public static function loremIpsum(int $amount=3,int $paragraph=1,string $separator=PHP_EOL):string
    {
        $return = '';
        $source = self::$config['loremIpsum'];

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
    final public static function s($value,?string $letter=null):string
    {
        $return = '';
        $letter = (is_string($letter))? $letter:self::$config['plural']['letter'];

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
    final public static function plural($value,$return,?array $replace=null,?string $letter=null,?string $wrap=null):string
    {
        if(!is_int($value))
        $value = count($value);

        if(!is_string($return))
        $return = (string) Obj::cast($return);

        if(is_int($value) && is_string($return))
        {
            $isPlural = ($value > 1);
            $letter = (is_string($letter))? $letter:self::$config['plural']['letter'];
            $wrap = (!empty($wrap))? $wrap:self::$config['plural']['wrap'];
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

                $return = self::replace($replace,$return);
            }

            elseif($isPlural && is_string($letter) && !self::isEnd($letter,$return))
            $return .= $letter;
        }

        return $return;
    }


    // replaceAccent
    // remplace tous les caractères accentés d'une chaîne
    final public static function replaceAccent(string $return):string
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
    final public static function removeAccent(string $return):string
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
    final public static function fixUnicode(string $return):string
    {
        $return .= '....';

        $return = iconv('UTF-8','UTF-8//IGNORE',$return);
        $return = substr($return,0,-4);
        $return = preg_replace('/[\x{0092}]/u','',$return);

        return $return;
    }


    // removeUnicode
    // enlève les caractères unicode d'une string
    final public static function removeUnicode(string $return):string
    {
        if(Encoding::isMb($return))
        {
            $return = self::fixUnicode($return);
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
    final public static function removeSymbols(string $return):string
    {
        return preg_replace('/[^\p{L}\p{N}\s]/u','',$return);
    }


    // removeLineBreaks
    // enleve les line breaks d'une string
    final public static function removeLineBreaks(string $return):string
    {
        return str_replace(["\n","\r","\r\n","\t"],'',$return);
    }


    // removeTabs
    // enlève les tabs d'une string
    final public static function removeTabs(string $return):string
    {
        return str_replace("\t",'',$return);
    }


    // removeWhitespace
    // enleve les whitespace d'une string (saut de ligne, tab, &nbsp;)
    // enlève aussi un caractère espace qui peut apparaître après avoir encode une string iso en utf8 via utf8_encode
    final public static function removeWhitespace(string $return):string
    {
        $return = str_replace(["\n","\r","\r\n","\t",'&nbsp;',' '],' ',$return);
        $return = self::removeConsecutive(' ',$return);
        $return = trim($return);

        return $return;
    }


    // removeAllWhitespace
    // enleve toutes les whitespace d'une string (saut de ligne, tab, &nbsp; et espace)
    final public static function removeAllWhitespace(string $return):string
    {
        $return = str_replace(["\n","\r","\r\n","\t",'&nbsp;'],'',$return);
        $return = str_replace(' ','',$return);
        $return = trim($return);

        return $return;
    }


    // removeConsecutive
    // enlève les caracètres consécutifs identiques, remplace par une seule instance du caractère
    // possible de mettre un autre caractère de remplacement
    final public static function removeConsecutive(string $remove,string $return,?string $replace=null)
    {
        return preg_replace("/$remove+/",(is_string($replace))? $replace:$remove,$return);
    }


    // removeBom
    // permet de retirer toutes les occurences du bom utf8 dans une string
    final public static function removeBom(string $return):string
    {
        return str_replace("\xEF\xBB\xBF",'',$return);
    }


    // remove
    // enlève un ou plusieurs caractères d'une chaîne en utilisant str_replace
    final public static function remove($remove,string $return):string
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
        $return = self::replace($replace,$return);

        return $return;
    }


    // keepNum
    // garde les nombres de la chaîne jusqu'à temps que le caractère ne soit plus un nombre
    // keep permet de garder des caractères supplémentaires
    final public static function keepNum(string $value,string $keep=''):string
    {
        $return = '';

        foreach (self::chars($value) as $v)
        {
            if(preg_match("/[0-9\-\.$keep]/",$v))
            $return .= $v;

            else
            break;
        }

        return $return;
    }


    // keepNumber
    // enleve tous les caractères non numérique
    // keep permet de garder des caractères supplémentaires
    final public static function keepNumber(string $value,string $keep=''):string
    {
        return preg_replace("/[^0-9$keep]/", '', $value);
    }


    // keepAlpha
    // enleve tous les caractères non alpha
    // keep permet de garder des caractères supplémentaires
    final public static function keepAlpha(string $value,string $keep=''):string
    {
        return preg_replace('/[^A-Za-z]/', '', $value);
    }


    // keepAlphanumeric
    // enleve tous les caractères non alphanumérique
    // keep permet de garder des caractères supplémentaires
    final public static function keepAlphanumeric(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9$keep]/", '', $value);
    }


    // keepAlphanumericPlus
    // va garder _ - . @, enleve tous les autres caractères spéciaux
    // keep permet de garder des caractères supplémentaires
    final public static function keepAlphanumericPlus(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9_\-\.\@$keep]/", '', $value);
    }


    // keepAlphanumericPlusSpace
    // va garder _ - . @ et espace, enleve tous les autres caractères spéciaux
    // keep permet de garder des caractères supplémentaires
    final public static function keepAlphanumericPlusSpace(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9_\-\.\@ $keep]/", '', $value);
    }


    // ascii
    // garde seulement les caractères ascii
    final public static function ascii(string $return,bool $replaceAccent=true):string
    {
        if($replaceAccent === true)
        $return = self::replaceAccent($return);

        $return = preg_replace("/[^\x01-\x7F]/",'',$return);

        return $return;
    }


    // asciiLower
    // garde seulement les caractères ascii et envoie en lowerCase
    final public static function asciiLower(string $return,bool $replaceAccent=true):string
    {
        $return = self::ascii($return);
        $return = strtolower($return);

        return $return;
    }


    // clean
    // fonction pour nettoyer une string (remplace accent, garde alphanumeric et trim)
    final public static function clean(string $return,string $keep=''):string
    {
        $return = self::replaceAccent($return);
        $return = self::keepAlphanumeric($return,$keep);
        $return = trim($return);

        return $return;
    }


    // cleanLower
    // comme clé mais envoie en lowercase
    final public static function cleanLower(string $return,string $keep=''):string
    {
        $return = self::replaceAccent($return);
        $return = self::keepAlphanumeric($return,$keep);
        $return = strtolower($return);
        $return = trim($return);

        return $return;
    }


    // cleanKeepSpace
    // fonction pour nettoyer une string (trim, remplace accent, garde alphanumeric et espace)
    final public static function cleanKeepSpace(string $return):string
    {
        return self::clean($return,' ');
    }


    // def
    // retourne une string formaté par défaut
    // remplace _ par espace, cleanKeepSpace, lower, capitalizeTitle et trim
    final public static function def(string $return,string $keep=''):string
    {
        $return = self::replace(['_'=>' '],$return);
        $return = self::cleanKeepSpace($return,$keep);
        $return = strtolower($return);
        $return = ucfirst($return);
        $return = trim($return);

        return $return;
    }


    // pointer
    // explose une chaîne pointeur (de type table/id)
    // le retour est passé dans cast
    // peut retourner null
    final public static function pointer(string $value,?string $separator=null):?array
    {
        $return = null;
        $separator = (is_string($separator))? $separator:self::$config['pointer'];
        $value = self::explodeTrimClean($separator,$value);
        $value = Arr::cast($value);

        if(count($value) === 2 && is_string($value[0]) && is_int($value[1]))
        $return = $value;

        return $return;
    }


    // toPointer
    // génère un pointeur à partir d'une string et chiffre
    final public static function toPointer(string $key,int $value,?string $separator=null):string
    {
        $separator = (is_string($separator))? $separator:self::$config['pointer'];
        return $key.$separator.$value;
    }


    // excerpt
    // fonction pour faire un résumé sécuritaire
    // mb, removeLineBreaks, removeUnicode, excerpt par length (rtrim et suffix) et trim
    // prendre note que le suffix est maintenant comptabilisé dans la longueur de la string
    final public static function excerpt(?int $length,string $return,?array $option=null):string
    {
        $option = Arr::plus(['removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true],$option);

        // enleve les sauts de ligne et les tabulations
        if(!empty($option['removeLineBreaks']))
        $return = self::removeLineBreaks($return);

        // unicode
        if(!empty($option['removeUnicode']))
        $return = self::removeUnicode($return);
        else
        $return = self::fixUnicode($return);

        // length
        if(!empty($length))
        {
            $lts = self::lengthTrimSuffix($length,$return,$option);
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
    final public static function lengthTrimSuffix(int $length,string $value,?array $option=null):array
    {
        $return = ['str'=>'','strSuffix'=>'','suffix'=>null];
        $option = Arr::plus(['mb'=>null,'rtrim'=>null,'suffix'=>self::$config['excerpt']['suffix']],$option);
        $mb = Encoding::getMb($option['mb'],$value);
        $suffix = $option['suffix'];
        $sliced = self::wordTotalLength($length,$value,$mb);

        $rtrim = '.,:;';
        $rtrim .= (!empty($option['rtrim']) && is_string($option['rtrim']))? $option['rtrim']:'';
        $sliced = self::trimRight($sliced,$rtrim);

        if(is_string($suffix) && strlen($suffix) && $sliced !== $value)
        {
            $suffixLength = self::len($suffix,$mb);
            $newLength = $length - $suffixLength;

            if($newLength > 0)
            {
                $sliced = self::sub(0,$newLength,$sliced,$mb);
                $return['suffix'] = $suffix;
            }
        }

        $return['str'] = self::fixUnicode($sliced);
        $return['strSuffix'] = $return['str'].$return['suffix'];

        return $return;
    }


    // output
    // fonction pour sortir une string
    // removeUnicode et trim
    final public static function output(string $return,?array $option=null):string
    {
        $option = Arr::plus(['removeLineBreaks'=>false,'removeUnicode'=>true,'trim'=>true],$option);

        // enleve les sauts de ligne et les tabulations
        if(!empty($option['removeLineBreaks']))
        $return = self::removeLineBreaks($return);

        // removeUnicode
        if(!empty($option['removeUnicode']))
        $return = self::removeUnicode($return);
        else
        $return = self::fixUnicode($return);

        // trim les espaces
        if(!empty($option['trim']))
        $return = trim($return);

        return $return;
    }


    // getEol
    // retourne le premier eol trouvé dans la chaîne
    final public static function getEol(string $content):?string
    {
        $return = null;

        foreach (self::$config['eol'] as $v)
        {
            if(strpos($content,$v) !== false)
            {
                $return = $v;
                break;
            }
        }

        return $return;
    }


    // eol
    // génère un ou plusieurs end of line
    final public static function eol(int $amount,string $separator=PHP_EOL):string
    {
        $return = '';

        while ($amount > 0)
        {
            $return .= $separator;
            $amount--;
        }

        return $return;
    }


    // bom
    // retourne le bom pour utf8
    final public static function bom():string
    {
        return chr(0xEF).chr(0xBB).chr(0xBF);
    }


    // setCharset
    // permet de garder en mémoire le charset pour la classe
    final public static function setCharset(string $value):void
    {
        self::$config['charset'] = $value;

        return;
    }
}
?>