<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// cli
// class with static methods to generate output for cli
final class Cli extends Root
{
    // config
    protected static array $config = [
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
        return self::$config['htmlOverload'] === true;
    }


    // isStdinLine
    // retourne vrai si la dernière ligne du stdin est la valeur en argument
    final public static function isStdinLine($value,$stdin,bool $lower=false):bool
    {
        $return = false;

        if(is_resource($stdin))
        {
            $line = self::stdinLine($stdin,$lower);

            if(!is_array($value))
            $value = (array) $value;

            if(in_array($line,$value,true))
            $return = true;
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
            $return = self::preset($key,...$arg);
        }

        else
        $return = self::flushPreset($key,...$arg);

        return $return;
    }


    // flush
    // écrit et flush une valeur au cli
    final public static function flush($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):void
    {
        Buffer::flushEcho(self::make($value,$foreground,$style,$background,$eol));
    }


    // flushPreset
    // écrit et flush une valeur au cli en utilisant le style d'un preset
    final public static function flushPreset(string $key,$value,int $eol=1):void
    {
        Buffer::flushEcho(self::preset($key,$value,$eol));
    }


    // eol
    // écrit et flush une ou plusieurs fins de lignes
    final public static function eol(int $value=1):void
    {
        $eol = self::getEol();

        while ($value > 0)
        {
            Buffer::flushEcho($eol);
            $value--;
        }
    }


    // make
    // cette méthode envoie à makeCli pour générer la string
    // possible d'envoyer à makeHtml si la configuration htmlOverload est true
    final public static function make($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):string
    {
        $return = '';

        if(self::isHtmlOverload())
        $return .= self::makeHtml($value,$foreground,$style,$background,$eol);

        else
        $return .= self::makeCli($value,$foreground,$style,$background,$eol);

        return $return;
    }


    // makeCli
    // génère une version avec couleur d'une valeur à envoyer au cli
    // possible de générer des newlines après
    final public static function makeCli($value,?string $foreground=null,?string $style=null,?string $background=null,int $eol=1):string
    {
        $return = '';
        $value = self::prepareValue($value);
        $foreground = (is_string($foreground))? self::getForegroundColor($foreground,0):null;
        $style = (is_string($style))? self::getStyle($style,0):null;
        $background = (is_string($background))? self::getBackgroundColor($background,0):null;
        $changed = false;
        $escape = self::getEscape();
        $eolChar = self::getEol();

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
        $value = self::prepareValue($value);
        $foreground = (is_string($foreground))? self::getForegroundColor($foreground,1):null;
        $style = (is_string($style))? self::getStyle($style,1):null;
        $background = (is_string($background))? self::getBackgroundColor($background,1):null;
        $styles = Arr::replace($foreground,$style,$background);
        $styles['padding'] = self::$config['htmlPadding'] ?? null;

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
        $arg = self::getPreset($key);

        if(!empty($arg))
        {
            $arg[] = $eol;
            $return = self::make($value,...$arg);
        }

        return $return;
    }


    // getEol
    // retourne le caractère de fin de ligne
    final public static function getEol():string
    {
        return self::$config['eol'];
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
        return self::$config['preset'][$value] ?? [];
    }


    // setPreset
    // permet d'ajouter un nouveau preset dans la configuration
    // value doit être un tableau avec trois valeurs
    final public static function setPreset(string $key,array $value):void
    {
        if(count($value) === 3)
        self::$config['preset'][$key] = array_values($value);
    }


    // getEscape
    // retourne le caractère d'échappement
    final public static function getEscape():string
    {
        return self::$config['escape'];
    }


    // getForegroundColor
    // retourne le code de la couleur de texte à utiliser
    final public static function getForegroundColor(string $value,int $index=0)
    {
        return self::$config['foreground'][$value][$index] ?? null;
    }


    // getBackgroundColor
    // retourne le code de la couleur d'arrière-plan à utiliser
    final public static function getBackgroundColor(string $value,int $index=0)
    {
        return self::$config['background'][$value][$index] ?? null;
    }


    // getStyle
    // retourne le code du style à utiliser
    final public static function getStyle(string $value,int $index=0)
    {
        return self::$config['style'][$value][$index] ?? null;
    }


    // write
    // permet d'écrire une valeur au cli
    // si c'est un tableau unidimensionnel, la valeur sera implode avec différents séparateurs et la date sera ajouté au début
    final public static function write(?string $method,$data,$separator=', ',?array $option=null):void
    {
        $option = Arr::plus(['timeSeparator'=>'|','firstSeparator'=>':','dateFormat'=>'sql'],$option);
        $time = Datetime::format($option['dateFormat']);
        $method = (is_string($method))? $method:'flush';

        if(is_string($data) && is_string($separator))
        $data = [$data];

        if(is_array($data))
        $data = Arr::clean($data);

        if(is_array($data) && Arr::isUni($data) && is_string($separator))
        {
            $first = array_shift($data);
            $data = implode($separator,$data);
            $value = $time;

            if(is_scalar($first))
            {
                $value .= ' '.$option['timeSeparator'].' ';
                $value .= $first;

                if(strlen($data))
                {
                    $value .= $option['firstSeparator'].' ';
                    $value .= $data;
                }
            }
        }

        else
        $value = $data;

        self::$method($value);
    }


    // exec
    // permet d'éxécuter une commande au terminal
    // la commande peut être bloquante ou non
    final public static function exec(string $cmd,bool $escape=true,bool $block=true):?string
    {
        if($escape === true)
        $cmd = escapeshellcmd($cmd);

        if($block === false)
        $cmd .= ' <&- > /dev/null 2>&1 & echo $!';

        return exec($cmd);
    }


    // beep
    // permet d'émettre un beep à la console
    final public static function beep($amount=null):void
    {
        if(!is_int($amount))
        $amount = 1;

        while ($amount > 0)
        {
            echo "\x07";
            $amount--;
        }
    }


    // stdin
    // retourne la resource stdin
    final public static function stdin(bool $block=true)
    {
        return Res::stdin(['block'=>$block]);
    }


    // stdinLine
    // retourne la dernière ligne du stdin
    // possible de ramener en lowercase
    final public static function stdinLine($stdin,bool $lower=false):?string
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


    // parseOpt
    // prend un tableau des argv et retourne un tableau avec toutes les options longues
    // l'ordre des options n'a pas d'importance, mais il faut que l'entrée commence par --
    // une entrée sans égale, prend la valeur true
    final public static function parseOpt(string ...$values):array
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
                    $value .= '=1';

                    $x = Str::explodeKeyValue('=',$value,true);
                    $x = Arr::cast($x);

                    $return = Arr::replace($return,$x);
                }
            }
        }

        return $return;
    }


    // parseCmd
    // permet de parse une string contenant une commande et des opts
    // retourne un tableau correctement formatté
    final public static function parseCmd(string $value):?array
    {
        $return = null;
        $value = trim($value);

        if(Str::isStart('->',$value))
        {
            $value = substr($value,2);
            $value = trim($value);

            if(strlen($value))
            {
                $explode = Str::wordExplode($value,2,true,true);
                if(!empty($explode[0]))
                {
                    $opt = [];
                    if(!empty($explode[1]))
                    $opt = Str::wordExplode($explode[1],null,true,true);

                    $return = [];
                    $return['cmd'] = $explode[0];
                    $return['opt'] = static::parseOpt(...$opt);
                }
            }
        }

        return $return;
    }


    // outputMethod
    // retourne la méthode de output cli à utiliser selon une valeur bool ou null
    final public static function outputMethod(?bool $value):string
    {
        return (is_bool($value))? (($value === true) ? 'pos':'neg'):'neutral';
    }


    // setHtmlOverload
    // active ou désactive le overload du html
    final public static function setHtmlOverload(bool $value):void
    {
        self::$config['htmlOverload'] = $value;
    }
}
?>