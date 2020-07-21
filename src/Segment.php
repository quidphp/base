<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// segment
// class that provides the logic to replace bracket segment within a string
final class Segment extends Root
{
    // config
    protected static array $config = [
        'default'=>'[]', // délimiteur par défaut
        'column'=>'/', // séparateur pour column
        'lang'=>'%lang%', // à remplacer lors du prepare, pour mettre %lang% dans un segment
        'escape'=>['[',']','(',')','{','}',',','.','|','$','?','*','+','/'], // délimiteur qu'il faut escape
        'replace'=>['/'=>'\/','$'=>'\$'], // valeur de remplacement pour certains caractère spéciaux
        'delimiter'=>[ // liste des délimiteurs strings
            '[]'=>['[',']'],
            '()'=>['(',')'],
            '{}'=>['{','}'],
            '``'=>['`','`'],
            '%%'=>['%','%']]
    ];


    // isWrapped
    // retourne vrai si la valeur est wrap dans le segment
    final public static function isWrapped($delimiter,string $value):bool
    {
        $return = false;
        $delimiter = self::getDelimiter($delimiter);

        if(!empty($delimiter))
        $return = Str::isStartEnd($delimiter[0],$delimiter[1],$value);

        return $return;
    }


    // has
    // retourne vrai si la chaîne contient des segments
    final public static function has($delimiter,string $value):bool
    {
        $return = false;
        $delimiter = self::getDelimiter($delimiter,false);

        if(!empty($delimiter))
        {
            $in = Str::pos($delimiter[0],$value);
            $out = Str::posRev($delimiter[1],$value);
            $return = (is_int($in) && is_int($out) && $out > $in);
        }

        return $return;
    }


    // getDelimiter
    // retourne le délimiteur à utiliser
    // si escape est true, envoie chaque délimiteur à la méthode escape
    final public static function getDelimiter($delimiter=null,bool $escape=false):?array
    {
        $return = null;
        $delimiter ??= self::def();

        if(is_string($delimiter))
        {
            if(array_key_exists($delimiter,self::$config['delimiter']))
            $delimiter = self::$config['delimiter'][$delimiter];

            else
            $delimiter = [$delimiter,$delimiter];
        }

        if(is_array($delimiter) && count($delimiter) === 2)
        {
            $return = $delimiter;

            if($escape === true)
            {
                $return[0] = self::escape($return[0]);
                $return[1] = self::escape($return[1]);
            }
        }

        return $return;
    }


    // wrap
    // wrap la chaîne du délimiteur
    final public static function wrap($delimiter,string $return):string
    {
        $delimiter = self::getDelimiter($delimiter);

        if(!empty($delimiter))
        $return = Str::wrapStartEnd($delimiter[0],$delimiter[1],$return);

        return $return;
    }


    // strip
    // enlève les délimiteurs enrobbant la chaîne
    final public static function strip($delimiter,string $return):string
    {
        $delimiter = self::getDelimiter($delimiter);

        if(!empty($delimiter))
        $return = Str::stripStartEnd($delimiter[0],$delimiter[1],$return);

        return $return;
    }


    // escape
    // ajoute le caractère d'escape si la valeur est dans le tableau config:escape
    final public static function escape(string $return):string
    {
        if(in_array($return,self::$config['escape'],true))
        $return = '\\'.$return;

        return $return;
    }


    // count
    // count le nombre de segments dans la chaîne
    final public static function count($delimiter,string $str):int
    {
        return count(self::get($delimiter,$str));
    }


    // exists
    // retourne vrai si un ou plusieurs segments existent
    final public static function exists($delimiter,$keys,string $str):bool
    {
        $return = false;
        $delimiter = self::getDelimiter($delimiter,true);

        if(!empty($delimiter) && !empty($keys))
        {
            $return = true;
            $keys = (array) $keys;

            foreach ($keys as $key)
            {
                if(is_scalar($key))
                {
                    $key = (string) $key;

                    if(!preg_match("/{$delimiter[0]}$key{$delimiter[1]}/",$str))
                    {
                        $return = false;
                        break;
                    }
                }
            }
        }

        return $return;
    }


    // are
    // retourne vrai si les clés de segments fournis représentent tous les segments de la chaîne
    final public static function are($delimiter,array $segments,string $str):bool
    {
        return Arr::valuesAre($segments,self::get($delimiter,$str));
    }


    // get
    // retourne un tableau avec tous les segments dans la chaîne
    // possible d'envoyer le tableau de retour dans prepare, si prepare est true
    // si un même segment est présent à multiples reprises, il est retourné plusieurs fois dans le bon ordre
    final public static function get($delimiter,string $str,bool $prepare=false):array
    {
        $return = [];
        $delimiter = self::getDelimiter($delimiter,true);

        if(!empty($delimiter))
        {
            $match = [];

            preg_match_all("/{$delimiter[0]}(.*?){$delimiter[1]}/",$str,$match);

            if(!empty($match[1]) && is_array($match[1]))
            {
                $return = $match[1];

                if($prepare === true)
                {
                    foreach ($return as $key => $value)
                    {
                        $return[$key] = self::prepare($value);
                    }
                }
            }
        }

        return $return;
    }


    // doSet
    // méthode protégé utilisé par set et sets pour faire le remplacement
    // se charge du escape de la valeur $
    final protected static function doSet(string $return,array $delimiter,array $replace):string
    {
        $replace = Arr::keysWrap('/'.$delimiter[0],$delimiter[1].'/',$replace);
        $pattern = array_keys($replace);
        $replacement = array_values($replace);

        foreach ($replacement as $key => $value)
        {
            $replacement[$key] = str_replace('$','\$',$value);
        }

        $return = preg_replace($pattern,$replacement,$return);

        return $return;
    }


    // set
    // change la valeur d'un segment dans la chaîne
    // support pour column si value est un tableau
    // si value contient un $, celui ci est escape
    final public static function set($delimiter,$key,$value,string $return):string
    {
        $replace = [];
        $delimiter = self::getDelimiter($delimiter);

        if(!empty($delimiter) && is_scalar($key) && strpos($return,$delimiter[0]) !== false)
        {
            $key = (string) $key;
            $return = self::prepare($return);
            $delimiter[0] = self::escape($delimiter[0]);
            $delimiter[1] = self::escape($delimiter[1]);
            $replace = [$key=>''];

            if(is_scalar($value))
            {
                $value = (string) $value;
                $replace = [$key=>$value];
                $replace = Arr::keysReplace(self::$config['replace'],$replace);
            }

            elseif(is_array($value))
            {
                foreach ($value as $k => $v)
                {
                    if(is_scalar($v))
                    {
                        $column = self::$config['column'];
                        $newKey = $key.self::escape($column).$k;
                        $replace[$newKey] = (string) $v;
                    }
                }
            }

            if(!empty($replace))
            $return = self::doSet($return,$delimiter,$replace);
        }

        return $return;
    }


    // setArray
    // change la valeur d'un segment dans le tableau multidimensionnel
    // support pour column si value est un tableau
    final public static function setArray($delimiter,$key,$value,array $return):array
    {
        foreach ($return as $k => $v)
        {
            if(is_array($v))
            $return[$k] = self::setArray($delimiter,$key,$value,$v);

            elseif(is_string($v))
            $return[$k] = self::set($delimiter,$key,$value,$v);
        }

        return $return;
    }


    // sets
    // change la valeur de plusieurs segments dans la chaîne
    // support pour column si replace est multidimensionnel
    final public static function sets($delimiter,array $replace,string $return):string
    {
        $delimiter = self::getDelimiter($delimiter);

        if(!empty($delimiter) && strpos($return,$delimiter[0]) !== false)
        {
            $return = self::prepare($return);
            $delimiter[0] = self::escape($delimiter[0]);
            $delimiter[1] = self::escape($delimiter[1]);

            if(Arrs::is($replace))
            {
                $column = self::$config['column'];
                $replace = Arrs::crush($replace);
                $keysReplace = [$column=>self::escape($column)];
                $replace = Arr::keysReplace($keysReplace,$replace);
            }

            else
            $replace = Arr::keysReplace(self::$config['replace'],$replace);

            foreach ($replace as $key => $value)
            {
                if(is_scalar($value))
                $replace[$key] = (string) $value;
            }

            if(!empty($replace))
            $return = self::doSet($return,$delimiter,$replace);
        }

        return $return;
    }


    // setsArray
    // change la valeur de plusieurs segments dans le tableau multidimensionnel
    // support pour column si replace est multidimensionnel
    final public static function setsArray($delimiter,array $replace,array $return):array
    {
        foreach ($return as $k => $v)
        {
            if(is_array($v))
            $return[$k] = self::setsArray($delimiter,$replace,$v);

            elseif(is_string($v))
            $return[$k] = self::sets($delimiter,$replace,$v);
        }

        return $return;
    }


    // unset
    // enlève un segment de la chaîne
    final public static function unset($delimiter,$key,string $return):string
    {
        return self::set($delimiter,$key,'',$return);
    }


    // unsets
    // enlève plusieurs segments de la chaîne
    final public static function unsets($delimiter,array $replace,string $return):string
    {
        if(!empty($replace))
        {
            $replace = Arr::values($replace,'scalar');
            $replace = array_fill_keys($replace,'');
            $return = self::sets($delimiter,$replace,$return);
        }

        return $return;
    }


    // prepare
    // prépare la string pour set et sets
    // remplate %lang% par la langue courante, permet de mettre la langue à l'intérieur d'un segment
    final public static function prepare(string $return):string
    {
        $lang = self::$config['lang'] ?? null;
        if(is_string($lang) && strpos($return,$lang) !== false)
        $return = str_replace($lang,Lang::current(),$return);

        return $return;
    }


    // def
    // retourne le delimiteur par défaut
    final public static function def():string
    {
        return self::$config['default'];
    }
}
?>