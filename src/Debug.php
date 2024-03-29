<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base {

// debug
// class with tools to help for debugging, also injects some helper functions
final class Debug extends Root
{
    // config
    protected static array $config = [
        'method'=>true, // méthode par défaut pour générer l'affichage des détails d'une variable, si true c'est automatique
        'inc'=>0, // permet de test le nombre d'appel,
        'cliPreset'=>'neutral' // preset à utiliser pour le cli
    ];


    // var
    // génère le détail d'une variable selon la méthode dans config
    // si echo est false, la string est retourné mais pas affiché
    // si echo est true, flush peut être true
    final public static function var($value=null,bool $wrap=true,bool $echo=true,?bool $flush=null):string
    {
        $return = '';
        $method = self::varMethod();

        if(is_string($method))
        {
            $return = self::$method($value,$wrap);

            if($echo === true)
            self::echoFlush($return,$flush);
        }

        elseif($echo === true)
        self::echoFlush($value,$flush);

        return $return;
    }


    // varFlush
    // comme var mais echo et flush
    final public static function varFlush($value=null,bool $wrap=true):string
    {
        return self::var($value,$wrap,true,true);
    }


    // varGet
    // retourne le détail d'une variable selon la méthode dans config, pas de echo
    final public static function varGet($value=null,bool $wrap=true):string
    {
        return self::var($value,$wrap,false);
    }


    // vars
    // génère le détail de variables selon la méthode dans config
    // echo et retourne un array
    final public static function vars(...$values):array
    {
        $return = [];

        foreach ($values as $value)
        {
            $return[] = self::var($value);
        }

        return $return;
    }


    // varsFlush
    // génère le détail de variables selon la méthode dans config
    // echo, et retourne un array, flush est utilisé
    final public static function varsFlush(...$values):array
    {
        $return = [];

        foreach ($values as $value)
        {
            $return[] = self::varFlush($value);
        }

        return $return;
    }


    // varsGet
    // génère le détail de variables selon la méthode dans config
    // n'écho rien, retourne une string
    final public static function varsGet(...$values):string
    {
        $return = '';

        foreach ($values as $value)
        {
            $return .= self::varGet($value);
        }

        return $return;
    }


    // dead
    // génère le détail d'une variable selon la méthode dans config et meurt
    // utilise la méthode par défaut
    final public static function dead($value=null):never
    {
        self::var($value);
        Response::kill();
    }


    // deads
    // génère le détail de variables selon la méthode dans config et meurt
    // utilise la méthode par défaut
    final public static function deads(...$values):never
    {
        foreach ($values as $value)
        {
            self::var($value);
        }

        Response::kill();
    }


    // echoFlush
    // permet d'echo la valeur ou de la echo + flush si flush est true
    final public static function echoFlush($value,?bool $flush=null):void
    {
        $flush = ($flush === null && Server::isCli());

        if($flush === true)
        Buffer::flushEcho($value);

        else
        echo $value;
    }


    // varMethod
    // retourne la méthode à utiliser pour faire un dump de données
    // détecte si xdebug est installé sur le système
    final public static function varMethod():?string
    {
        $return = self::$config['method'];

        if($return === true)
        {
            if(Ini::isVarDumpOverloaded())
            $return = 'dump';

            else
            $return = 'exportExtra';
        }

        return $return;
    }


    // wrap
    // gère l'enrobage de la valeur
    // différent si c'est du cli
    final public static function wrap(?string $return):string
    {
        if(Server::isCli())
        $return = Cli::preset(self::$config['cliPreset'],$return);

        else
        $return = "<pre>$return</pre>";

        return $return;
    }


    // printr
    // retourne les détail d'une variable avec print_r
    // si wrap est true, la string est enrobbé de pre
    final public static function printr($value=null,bool $wrap=false):string
    {
        $return = print_r($value,true);

        if($wrap === true)
        $return = self::wrap($return);

        return $return;
    }


    // dump
    // retourne les détails d'une variable avec var_dump
    // ouvre et ferme un buffer
    // si wrap est true, la string est enrobbé de pre
    // si extra est true, passe la string dans specialChars et met la longueur entre paranthèses avant (utile pour html)
    final public static function dump($value=null,bool $wrap=true,bool $extra=true):string
    {
        $return = '';
        $isOverloaded = Ini::isVarDumpOverloaded();
        $isCli = Server::isCli();

        if($isOverloaded === false && is_string($value) && $extra === true)
        {
            $strlen = strlen($value);
            $specialChars = Html::specialChars($value);

            if(strlen($specialChars) !== $strlen)
            {
                if($isCli === false)
                $value = $specialChars;

                $value .= "---$strlen";
            }
        }

        Buffer::start();
        var_dump($value);
        $return = Buffer::getClean();
        $return = trim($return);

        if($isOverloaded === false && $wrap === true)
        $return = self::wrap($return);

        return $return;
    }


    // export
    // retourne les détails d'une variable avec var_export
    // si la variable n'est pas array ou objet, envoie à dump
    // si wrap est true, la string est passé dans stripslashes et ensuite highlight_string
    // si extra est true, ajoute le compte si c'est un tableau ou la longueur si c'est une string
    final public static function export($value=null,bool $wrap=true,bool $extra=false):string
    {
        $return = var_export($value,true);
        $isCli = Server::isCli();

        if($wrap === true && $isCli === false)
        $return = self::highlight($return,$wrap,true);

        if($extra === true)
        {
            $count = null;

            if(is_string($value))
            $count = strlen($value);

            if(is_array($value))
            $count = count($value);

            if(is_int($count))
            {
                $count = "---$count";

                if($wrap === true && $isCli === false)
                $return .= "<pre>$count</pre>";
                else
                $return .= $count;
            }
        }

        if($wrap === true && $isCli === true)
        $return = self::wrap($return);

        return $return;
    }


    // exportExtra
    // comme export mais extra est true
    final public static function exportExtra($value=null,bool $wrap=true)
    {
        return self::export($value,$wrap,true);
    }


    // highlight
    // highlight une string php ou un fichier source php
    // si wrap est true, enrobe la string des open et close tag de php
    // si unwrap est true, essaie d'enlèver les open et close tag de php du code html
    final public static function highlight(string $string,bool $wrap=false,bool $unwrap=false,bool $file=false):string
    {
        $return = '';

        if($file === true)
        $return = highlight_file($string,true);

        else
        {
            $string = stripslashes($string);

            if($wrap === true)
            $string = "<?php\n".$string."\n?>";

            $return = highlight_string($string,true);
        }

        if($unwrap === true)
        $return = str_replace(['&lt;?php<br />','&lt;?php&nbsp;','<span style="color: #0000BB">?&gt;</span>','?&gt;'],'',$return);

        return $return;
    }


    // sourceStrip
    // retourne la source d'un fichier php sans espace blanc
    final public static function sourceStrip(string $value):?string
    {
        $return = null;

        if(is_file($value))
        $return = php_strip_whitespace($value);

        return $return;
    }


    // trace
    // génère le debug_backtrace
    // premier argument permet l'affichage ou non des arguments du backtrace
    // shift permet d'enlever un nombre d'entrée au début du tableau
    final public static function trace(bool $showArgs=false,int $shift=0):array
    {
        $option = ($showArgs === true)? 0:DEBUG_BACKTRACE_IGNORE_ARGS;
        $return = debug_backtrace($option);
        Arr::shift($return,$shift);

        return $return;
    }


    // traceStart
    // retourne un trace à partir d'un point de départ file et line (facultatif)
    // showArgs permet d'enlever les arguments
    final public static function traceStart(string $file,?int $line=null,bool $showArgs=false,?array $trace=null):array
    {
        $return = [];
        $trace ??= self::trace(false,1);
        $capture = false;

        foreach ($trace as $key => $value)
        {
            if(is_array($value))
            {
                if(array_key_exists('file',$value) && $value['file'] === $file)
                {
                    if($line === null || (array_key_exists('line',$value) && $value['line'] === $line))
                    $capture = true;
                }

                if($capture === true)
                $return[] = $value;
            }
        }

        if($showArgs === false)
        $return = self::traceRemoveArgs($return);

        return $return;
    }


    // traceIndex
    // retourne un index de trace
    // les arguments de traceStart peuvent aussi être fournis en 2e et 3e position
    final public static function traceIndex(int $index=0,?string $file=null,?int $line=null,bool $showArgs=false,?array $trace=null):?array
    {
        $return = null;
        $trace ??= self::trace(false,1);

        if(is_string($file))
        $trace = self::traceStart($file,$line,$showArgs,$trace);

        elseif($showArgs === false)
        $trace = self::traceRemoveArgs($trace);

        $return = Arr::index($index,$trace);

        return $return;
    }


    // traceSlice
    // permet de slice un tableau trace via offset et length
    // les arguments de traceStart peuvent aussi être fournis en 3e et 4e position
    final public static function traceSlice(int $offset,?int $length=null,?string $file=null,?int $line=null,bool $showArgs=false,?array $trace=null):array
    {
        $return = [];
        $trace ??= self::trace(false,1);

        if(is_string($file))
        $trace = self::traceStart($file,$line,$showArgs,$trace);

        elseif($showArgs === false)
        $trace = self::traceRemoveArgs($trace);

        $return = Arr::sliceIndex($offset,$length,$trace,false);

        return $return;
    }


    // traceLastCall
    // retourne la dernière fonction appelé
    // les arguments de traceStart peuvent être fournis en 1ère et 2e position
    final public static function traceLastCall(?string $file=null,?int $line=null,?array $trace=null):?string
    {
        $return = null;
        $trace ??= self::trace(false,1);

        if(is_string($file))
        $trace = self::traceStart($file,$line,false,$trace);

        foreach ($trace as $key => $value)
        {
            if(is_array($value) && !empty($value['function']))
            {
                $return = '';

                if(!empty($value['class']))
                $return .= $value['class'];

                if(!empty($value['function']))
                $return .= (empty($return))? $value['function']:'::'.$value['function'];

                break;
            }
        }

        return $return;
    }


    // traceBeforeClass
    // retoure la première entrée trace trouvé qui a une classe et qui n'est pas la classe présente ou celle en argument
    // exception si c'est une des classes données et que c'est un constructeur
    final public static function traceBeforeClass($class=null,bool $construct=true,?array $trace=null):?array
    {
        $return = null;
        $trace ??= self::trace(false,1);
        $class = (empty($class))? [self::class]:Arr::merge($class,self::class);

        foreach ($trace as $key => $value)
        {
            if(is_array($value) && array_key_exists('class',$value) && array_key_exists('function',$value))
            {
                if(!in_array($value['class'],$class,true) || ($construct === true && $value['function'] === '__construct'))
                {
                    $return = $value;
                    break;
                }
            }
        }

        return $return;
    }


    // traceBeforeFile
    // retoure la première entrée trace avant le fichier fourni en argument
    final public static function traceBeforeFile(string $file,?array $trace=null):?array
    {
        $return = null;
        $trace ??= self::trace(false,1);

        foreach ($trace as $key => $value)
        {
            if(is_array($value) && array_key_exists('file',$value) && $value['file'] !== $file)
            {
                $return = $value;
                break;
            }
        }

        return $return;
    }


    // traceRemoveArgs
    // enlève les arguments d'un tableau multidimensionnel trace
    final public static function traceRemoveArgs(array $return):array
    {
        foreach ($return as $key => $value)
        {
            if(is_array($value) && array_key_exists('args',$value))
            {
                unset($value['args']);
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // speed
    // calcule la différence entre deux microtime, par défaut getInitMicrotime dans date
    // round permet d'arrondir la différence
    // ici utilise la fonction round, car l'envoie à num round me fait parfois des erreurs bizarres dans le log
    final public static function speed(?float $value=null,int $round=3):float
    {
        $return = 0;
        $value ??= Datetime::getInitMicrotime();

        if(is_numeric($value))
        $return = round((Datetime::microtime() - $value),$round);

        return $return;
    }


    // call
    // permet de faire des itérations sur une closure
    // retourne le temps d'éxécutions
    final public static function call(int $iteration,\Closure $closure):float
    {
        $return = 0;
        $microtime = Datetime::microtime();

        if($iteration > 0)
        {
            for ($i=0; $i < $iteration; $i++)
            {
                $closure();
            }
        }

        $return = self::speed($microtime,3);

        return $return;
    }
}
}


// helper
namespace {
use Quid\Base;

(function() {
    // d
    // raccourci pour vars
    if(!function_exists('d'))
    {
        function d(...$values):array
        {
            return Base\Debug::vars(...$values);
        }
    }


    // df
    // raccourci pour varsFlush
    if(!function_exists('df'))
    {
        function df(...$values):array
        {
            return Base\Debug::varsFlush(...$values);
        }
    }


    // dg
    // raccourci pour varsGet
    if(!function_exists('dg'))
    {
        function dg(...$values):string
        {
            return Base\Debug::varsGet(...$values);
        }
    }


    // dd
    // raccourci pour deads
    if(!function_exists('dd'))
    {
        function dd(...$values):void
        {
            Base\Debug::deads(...$values);
        }
    }

    // trace
    // raccourci pour trace
    if(!function_exists('trace'))
    {
        function trace(bool $showArgs=false,int $shift=1):array
        {
            $return = Base\Debug::trace($showArgs,$shift);
            Base\Debug::var($return);
            return $return;
        }
    }


    // speed
    // raccourci pour speed
    if(!function_exists('speed'))
    {
        function speed(?float $value=null,int $round=3):float
        {
            $return = Base\Debug::speed($value,$round);
            Base\Debug::var($return);
            return $return;
        }
    }


    // speedd
    // raccourci pour speed + dead
    if(!function_exists('speedd'))
    {
        function speedd(?float $value=null,int $round=3):never
        {
            Base\Debug::var(Base\Debug::speed($value,$round));
            Base\Response::kill();
        }
    }
})();
}
?>