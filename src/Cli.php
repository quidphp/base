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

// cli
// class with static methods to generate output for cli
class Cli extends Root
{
    // config
    public static $config = [
        'escape'=>"\033", // caractère d'échappement
        'eol'=>PHP_EOL, // caractère de fin de ligne
        'foreground'=>[ // code pour couleur avant-plan
            'black'=>['0;30',['color'=>'black']],
            'darkGray'=>['1;30',['color'=>'darkgray']],
            'red'=>['0;31',['color'=>'red']],
            'lightRed'=>['1;31',['color'=>'palevioletred']],
            'green'=>['0;32',['color'=>'green']],
            'lightGreen'=>['1;32',['color'=>'lightgreen']],
            'brown'=>['0;33',['color'=>'brown']],
            'yellow'=>['1;33',['color'=>'yellow']],
            'blue'=>['0;34',['color'=>'blue']],
            'lightBlue'=>['1;34',['color'=>'lightblue']],
            'purple'=>['0;35',['color'=>'purple']],
            'lightPurple'=>['1;35',['color'=>'mediumpurple']],
            'cyan'=>['0;36',['color'=>'cyan']],
            'lightCyan'=>['1;36',['color'=>'lightcyan']],
            'lightGray'=>['0;37',['color'=>'lightgray']],
            'white'=>['1;37',['color'=>'white']]],
        'background'=>[ // code pour couleur arrière-plan
            'black'=>[40,['background-color'=>'black']],
            'red'=>[41,['background-color'=>'red']],
            'green'=>[42,['background-color'=>'green']],
            'yellow'=>[43,['background-color'=>'yellow']],
            'blue'=>[44,['background-color'=>'blue']],
            'magenta'=>[45,['background-color'=>'magenta']],
            'cyan'=>[46,['background-color'=>'cyan']],
            'gray'=>[47,['background-color'=>'gray']]],
        'style'=>[ // code pour des styles
            'bold'=>[1,['font-weight'=>'bold']],
            'italic'=>[3,['font-style'=>'italic']],
            'underline'=>[4,['text-decoration'=>'underline']]],
        'preset'=>[ // règle de styles pour certaines presets
            'pos'=>['green','bold','black'],
            'neg'=>['white','bold','red'],
            'neutral'=>['black',null,'gray']],
        'htmlPadding'=>'5px', // valeur utilisé pour le padding de html
        'htmlOverload'=>false // permet d'overload les appels aux méthodes clis avec du html
    ];


    // is
    // retourne vrai si php est présentement dans cli
    final public static function is():bool
    {
        return Server::isCli();
    }


    // isHtmlOverload
    // retourne vrai si les méthodes cli doivent générer du html
    final public static function isHtmlOverload():bool
    {
        return static::$config['htmlOverload'] === true;
    }


    // isInLine
    // retourne vrai si la dernière ligne du stdin est la valeur en argument
    final public static function isInLine($value,$stdin,bool $lower=false):bool
    {
        $return = false;

        if(is_resource($stdin))
        {
            $line = static::inLine($stdin,$lower);

            if(!is_array($value))
            $value = (array) $value;

            if(in_array($line,$value,true))
            $return = true;
        }

        return $return;
    }


    // parseLongOptions
    // prend un tableau des argv et retourne un tableau avec toutes les options longues
    // l'ordre des options n'a pas d'importance, mais il faut que l'entrée commence par --
    final public static function parseLongOptions(string ...$values):array
    {
        $return = [];

        foreach ($values as $key => $value)
        {
            if(Str::isStart('--',$value))
            {
                $value = substr($value,2);

                if(strlen($value) && !Str::isStart('=',$value))
                {
                    if(strpos($value,'=') === false)
                    $value .= '=';

                    $x = Str::explodeKeyValue('=',$value,true);
                    $x = Arr::cast($x);

                    $return = Arr::replace($return,$x);
                }
            }
        }

        return $return;
    }


    // callStatic
    // méthode qui attrape tous les appels à des méthodes non reconnus
    // renvoie vers flushPreset ou vers preset si camelCase a une longueur de deux et commence par get
    final public static function __callStatic(string $key,array $arg)
    {
        $return = null;
        $lower = strtolower($key);
        $camelCase = null;

        if($lower !== $key)
        $camelCase = Str::explodeCamelCase($key);

        if(is_array($camelCase) && count($camelCase) === 2 && $camelCase[0] === 'get')
        {
            $key = strtolower($camelCase[1]);
            $return = static::preset($key,...$arg);
        }

        else
        $return = static::flushPreset($key,...$arg);

        return $return;
    }


    // flush
    // écrit et flush une valeur au cli
    final public static function flush($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):void
    {
        Buffer::flushEcho(static::make($value,$foreground,$style,$background,$eol));

        return;
    }


    // flushPreset
    // écrit et flush une valeur au cli en utilisant le style d'un preset
    final public static function flushPreset(string $key,$value,int $eol=1):void
    {
        Buffer::flushEcho(static::preset($key,$value,$eol));

        return;
    }


    // eol
    // écrit et flush une ou plusieurs fins de lignes
    final public static function eol(int $value=1):void
    {
        $eol = static::getEol();

        while ($value > 0)
        {
            Buffer::flushEcho($eol);
            $value--;
        }

        return;
    }


    // make
    // cette méthode envoie à makeCli pour générer la string
    // possible d'envoyer à makeHtml si la configuration htmlOverload est true
    final public static function make($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):string
    {
        $return = '';

        if(static::isHtmlOverload())
        $return .= static::makeHtml($value,$foreground,$style,$background,$eol);

        else
        $return .= static::makeCli($value,$foreground,$style,$background,$eol);

        return $return;
    }


    // makeCli
    // génère une version avec couleur d'une valeur à envoyer au cli
    // possible de générer des newlines après
    final public static function makeCli($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):string
    {
        $return = '';
        $value = static::prepareValue($value);
        $foreground = (is_string($foreground))? static::getForegroundColor($foreground,0):null;
        $style = (is_string($style))? static::getStyle($style,0):null;
        $background = (is_string($background))? static::getBackgroundColor($background,0):null;
        $changed = false;
        $escape = static::getEscape();
        $eolChar = static::getEol();

        if(is_string($value) && strlen($value))
        {
            if(is_string($foreground) || is_int($style) || is_int($background))
            {
                $changed = true;

                if(is_string($foreground))
                $return .= $escape.'['.$foreground.'m';

                if(is_int($style))
                $return .= $escape.'['.$style.'m';

                if(is_int($background))
                $return .= $escape.'['.$background.'m';
            }

            $return .= $value;

            if($changed === true)
            $return .= $escape.'[0m';
        }

        $return .= Str::eol($eol,$eolChar);

        return $return;
    }


    // makeHtml
    // génère une version html couleur d'une valeur
    // possible de générer des br après
    final public static function makeHtml($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):string
    {
        $return = '';
        $value = static::prepareValue($value);
        $foreground = (is_string($foreground))? static::getForegroundColor($foreground,1):null;
        $style = (is_string($style))? static::getStyle($style,1):null;
        $background = (is_string($background))? static::getBackgroundColor($background,1):null;
        $styles = Arr::replace($foreground,$style,$background);
        $styles['padding'] = static::$config['htmlPadding'] ?? null;

        if(is_string($value) && strlen($value))
        {
            $value = Html::nl2br($value);
            $return .= Html::div($value,['style'=>$styles]);
        }

        $return .= Html::brs($eol);

        return $return;
    }


    // preset
    // génère une string via un preset
    final public static function preset(string $key,$value,int $eol=1):string
    {
        $return = '';
        $arg = static::getPreset($key);
        $arg[] = $eol;

        $return = static::make($value,...$arg);

        return $return;
    }


    // getEol
    // retourne le caractère de fin de ligne
    final public static function getEol():string
    {
        return static::$config['eol'];
    }


    // prepareValue
    // prépare la valeur à envoyer au cli
    // si c'est un tableau, utilise print_r
    final public static function prepareValue($value):?string
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
    final public static function getPreset(string $value):array
    {
        return static::$config['preset'][$value] ?? [];
    }


    // setPreset
    // permet d'ajouter un nouveau preset dans la configuration
    // value doit être un tableau avec trois valeurs
    final public static function setPreset(string $key,array $value):void
    {
        if(count($value) === 3)
        static::$config['preset'][$key] = array_values($value);

        return;
    }


    // getEscape
    // retourne le caractère d'échappement
    final public static function getEscape():string
    {
        return static::$config['escape'];
    }


    // getForegroundColor
    // retourne le code de la couleur de texte à utiliser
    final public static function getForegroundColor(string $value,int $index=0)
    {
        return static::$config['foreground'][$value][$index] ?? null;
    }


    // getBackgroundColor
    // retourne le code de la couleur d'arrière-plan à utiliser
    final public static function getBackgroundColor(string $value,int $index=0)
    {
        return static::$config['background'][$value][$index] ?? null;
    }


    // getStyle
    // retourne le code du style à utiliser
    final public static function getStyle(string $value,int $index=0)
    {
        return static::$config['style'][$value][$index] ?? null;
    }


    // setHtmlOverload
    // active ou désactive le overload du html
    final public static function setHtmlOverload(bool $value):void
    {
        static::$config['htmlOverload'] = $value;

        return;
    }


    // in
    // retourne la resource stdin
    final public static function in(bool $block=true)
    {
        return Res::stdin(['block'=>$block]);
    }


    // inLine
    // retourne la dernière ligne du stdin
    // par défaut tout est remené en lowerCase
    final public static function inLine($stdin,bool $lower=false):?string
    {
        $return = null;

        if(is_resource($stdin))
        {
            $line = fgets($stdin);

            if(is_string($line))
            {
                $return = trim($line);

                if($lower === true)
                $return = strtolower($return);
            }
        }

        return $return;
    }
}
?>