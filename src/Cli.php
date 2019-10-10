<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// cli
// class with static methods to generate output for cli
class Cli extends Root
{
    // config
    public static $config = [
        'escape'=>"\033", // caractère d'échappement
        'eol'=>PHP_EOL, // caractère de fin de ligne
        'foreground'=>[ // code pour couleur avant-plan
            'black'=>'0;30',
            'darkGray'=>'1;30',
            'red'=>'0;31',
            'lightRed'=>'1;31',
            'green'=>'0;32',
            'lightGreen'=>'1;32',
            'brown'=>'0;33',
            'yellow'=>'1;33',
            'blue'=>'0;34',
            'lightBlue'=>'1;34',
            'purple'=>'0;35',
            'lightPurple'=>'1;35',
            'cyan'=>'0;36',
            'lightCyan'=>'1;36',
            'lightGray'=>'0;37',
            'white'=>'1;37'],
        'background'=>[ // code pour couleur arrière-plan
            'black'=>40,
            'red'=>41,
            'green'=>42,
            'yellow'=>43,
            'blue'=>44,
            'magenta'=>45,
            'cyan'=>46,
            'gray'=>47],
        'style'=>[ // code pour des styles
            'normal'=>0,
            'bold'=>1,
            'dim'=>2,
            'italic'=>3,
            'underline'=>4,
            'blink'=>5,
            'reverse'=>7,
            'invisible'=>8],
        'preset'=>[ // règle de styles pour certaines presets
            'pos'=>['green','underline',null],
            'neg'=>['red','underline',null],
            'neutral'=>['black',null,'gray']]
    ];


    // write
    // écrit une valeur au cli
    public static function write($value,?string $foreground=null,?string $background=null,int $eol=1):void
    {
        echo static::style($value,$foreground,$background,$eol);

        return;
    }


    // preset
    // écrite une valeur au cli en utilisant le style d'un preset
    public static function preset(string $key,$value,int $eol=1):void
    {
        echo static::makePreset($key,$value,$eol);

        return;
    }


    // eol
    // envoie une ou plusieurs fins de lignes
    public static function eol(int $value=1):void
    {
        $eol = static::getEol();

        while ($value > 0)
        {
            echo $eol;
            $value--;
        }

        return;
    }


    // pos
    // écrit une string positive
    public static function pos($value,int $eol=1):void
    {
        static::preset('pos',$value,$eol);

        return;
    }


    // neg
    // écrit une string négative
    public static function neg($value,int $eol=1):void
    {
        static::preset('neg',$value,$eol);

        return;
    }


    // neutral
    // écrit une string neutre
    public static function neutral($value,int $eol=1):void
    {
        static::preset('neutral',$value,$eol);

        return;
    }


    // make
    // génère une version avec couleur d'une valeur à envoyer au cli
    // possible de générer des newlines aprèes
    public static function make($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=0):string
    {
        $return = '';
        $value = static::prepareValue($value);
        $foreground = (is_string($foreground))? static::getForegroundColor($foreground):null;
        $style = (is_string($style))? static::getStyle($style):null;
        $background = (is_string($background))? static::getBackgroundColor($background):null;
        $changed = false;
        $escape = static::getEscape();
        $eolChar = static::getEol();

        if(is_string($value) && strlen($value))
        {
            if(is_string($foreground))
            {
                $return .= $escape.'['.$foreground.'m';
                $changed = true;
            }

            if(is_int($style))
            {
                $return .= $escape.'['.$style.'m';
                $changed = true;
            }

            if(is_int($background))
            {
                $return .= $escape.'['.$background.'m';
                $changed = true;
            }

            $return .= $value;

            if($changed === true)
            $return .= $escape.'[0m';
        }

        while ($eol > 0)
        {
            $return .= $eolChar;
            $eol--;
        }

        return $return;
    }


    // makePreset
    // génère une string via un preset
    public static function makePreset(string $key,$value,int $eol=0):string
    {
        $return = '';
        $arg = static::getPreset($key);
        $arg[] = $eol;

        $return = static::make($value,...$arg);

        return $return;
    }


    // prepareValue
    // prépare la valeur à envoyer au cli
    // si c'est un tableau, utilise print_r
    public static function prepareValue($value):?string
    {
        $return = null;
        $value = Obj::cast($value);

        if(is_array($value))
        $value = Debug::printr($value);

        $return = Str::cast($value);

        return $return;
    }


    // getPreset
    // retourne les arguments pour générer un preset
    public static function getPreset(string $value):array
    {
        return static::$config['preset'][$value] ?? [];
    }


    // setPreset
    // permet d'ajouter un nouveau preset dans la configuration
    // value doit être un tableau avec trois valeurs
    public static function setPreset(string $key,array $value):void
    {
        if(count($value) === 3)
        static::$config['preset'][$key] = array_values($value);

        return;
    }


    // getEol
    // retourne le caractère de fin de ligne
    public static function getEol():string
    {
        return static::$config['eol'];
    }


    // getEscape
    // retourne le caractère d'échappement
    public static function getEscape():string
    {
        return static::$config['escape'];
    }


    // getForegroundColor
    // retourne le code de la couleur de texte à utiliser
    public static function getForegroundColor(string $value):?string
    {
        return static::$config['foreground'][$value] ?? null;
    }


    // getBackgroundColor
    // retourne le code de la couleur d'arrière-plan à utiliser
    public static function getBackgroundColor(string $value):?int
    {
        return static::$config['background'][$value] ?? null;
    }


    // getStyle
    // retourne le code du style à utiliser
    public static function getStyle(string $value):?int
    {
        return static::$config['style'][$value] ?? null;
    }
}
?>