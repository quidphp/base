<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// fqcn
// class with static methods to deal with fully qualified class name strings
final class Fqcn extends Set
{
    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'extension'=>'php'], // extension du path
        'separator'=>['\\'], // séparateur pour les fully qualified classname
        'plusSeperator'=>'+', // séparateur pour la méthode many
        'sensitive'=>false // la classe est sensible ou non à la case
    ];


    // is
    // retourne vrai si la valeur est un fqcn
    final public static function is($value):bool
    {
        return Validate::regex('fqcn',$value);
    }


    // sameName
    // retourne vrai si les deux valeurs ont le même nom
    // la comparaison est insensible à la case
    final public static function sameName($same,$value):bool
    {
        $same = (string) self::name($same);
        $value = (string) self::name($value);

        return $same === $value || strtolower($same) === strtolower($value);
    }


    // sameNamespace
    // retourne vrai si les deux valeurs ont le même namespace
    // la comparaison est insensible à la case
    final public static function sameNamespace($same,$value):bool
    {
        $same = (string) self::namespace($same);
        $value = (string) self::namespace($value);

        return $same === $value || strtolower($same) === strtolower($value);
    }


    // hasNamespace
    // retourne vrai si la valeur a exactement le namespace spécifié
    // la comparaison est insensible à la case
    final public static function hasNamespace($namespace,$value):bool
    {
        $return = false;
        $value = self::str($value);

        if(!empty($value))
        {
            $namespace = self::str($namespace);
            $value = (string) self::namespace($value);
            $return = ($namespace === $value || strtolower($namespace) === strtolower($value));
        }

        return $return;
    }


    // inNamespace
    // retourne vrai si la valeur fait partie du namespace spécifié
    // la comparaison est insensible à la case
    final public static function inNamespace($namespace,$value):bool
    {
        $namespace = self::str($namespace);
        $value = self::str($value);

        return !empty($namespace) && !empty($value) && stripos($value,$namespace) === 0;
    }


    // str
    // retourne le fully qualified classname
    // value peut être une string, array ou objet
    final public static function str($value,?array $option=null):string
    {
        $return = '';

        if(is_object($value))
        $return = $value::class;

        elseif(is_string($value) && (strpos($value,'\\') > 0 || class_exists($value,false)))
        $return = $value;

        else
        $return = parent::str($value,$option);

        return $return;
    }


    // arr
    // retourne le nom fully qualified classname sous la forme d'un array
    // étend la méthode de la classe set
    final public static function arr($value,?array $option=null):array
    {
        $return = [];

        if(is_object($value))
        $value = $value::class;

        elseif(is_string($value) && (strpos($value,'\\') > 0 || class_exists($value,false)))
        $return = explode('\\',$value);

        else
        $return = parent::arr($value,$option);

        return $return;
    }


    // root
    // retourne le root du namespace d'un fqcn
    // si la classe n'a pas de namespace, retoure null
    final public static function root($value):?string
    {
        $return = null;
        $value = self::str($value);

        if(is_string($value) && strpos($value,'\\') !== false)
        {
            $array = explode('\\',$value);
            $root = array_shift($array);

            if(is_string($root) && strlen($root))
            $return = $root;
        }

        return $return;
    }


    // name
    // retourne le nom d'un fqcn
    final public static function name($value):?string
    {
        $return = null;
        $value = self::str($value);

        if(is_string($value))
        {
            if(strpos($value,'\\') !== false)
            {
                $array = explode('\\',$value);
                $name = array_pop($array);

                if(is_string($name) && strlen($name))
                $return = $name;
            }

            elseif(strlen($value))
            $return = $value;
        }

        return $return;
    }


    // namespace
    // retourne le namespace, sans le nom de classe, d'un fqcn
    final public static function namespace($value):?string
    {
        $return = null;
        $value = self::str($value);

        if(is_string($value) && strpos($value,'\\') !== false && strpos($value,'@') === false)
        {
            $array = explode('\\',$value);
            array_pop($array);

            if(!empty($array))
            $return = implode('\\',$array);
        }

        return $return;
    }


    // spliceRoot
    // retourne le fqcn sans le root
    final public static function spliceRoot($value,$replace=null):string
    {
        return self::spliceFirst(self::str($value),$replace);
    }


    // sliceMiddle
    // retoure un fqcn sans le root et le nom de classe
    final public static function sliceMiddle($value):string
    {
        return self::unsets([0,-1],self::str($value));
    }


    // many
    // prépare une string classe, qui pourrait contenir plusieurs classes ou namespace séparés par +
    // retourne un tableau
    final public static function many($value):array
    {
        $return = [];
        $plusSeperator = self::$config['plusSeperator'];

        if(is_array($value))
        $value = self::str($value);

        if(is_string($value) && strlen($value))
        {
            $explodes = Str::explodes(['\\',$plusSeperator],$value);
            if(!empty($explodes))
            {
                foreach (Arrs::valuesCrush($explodes) as $v)
                {
                    if(is_array($v) && !empty($v))
                    $return[] = self::str($v);
                }
            }
        }

        return $return;
    }


    // path
    // transforme un fully qualified classname en path
    // utiliser option path pour contrôler le output du path
    final public static function path($value,?array $option=null):?string
    {
        $return = null;
        $option = self::option($option);
        $array = self::arr($value,$option);

        if(!empty($array))
        {
            $option['path'] = !empty($option['path'])? Path::option($option['path']):null;
            $return = Path::implode($array,$option['path']);

            if(!empty($option['extension']))
            $return = self::extension($return,$option['extension']);
        }

        return $return;
    }


    // fromPath
    // transforme un chemin en fully qualified classname
    // utiliser option path pour contrôler le input du path
    final public static function fromPath(string $value,?array $option=null):?string
    {
        $return = null;
        $option = self::option($option);
        $value = Path::removeExtension($value);

        if(!empty($value))
        {
            $option['path'] = !empty($option['path'])? Path::option($option['path']):null;
            $array = Path::arr($value,$option['path']);

            if(!empty($array))
            $return = self::str($array,$option);
        }

        return $return;
    }


    // fromPathRoot
    // retourne le fqcn à partir d'un path et d'une string root
    // option root permet de mettre une valeur en prepend du fqcn de retour
    final public static function fromPathRoot(string $value,string $root,?array $option=null):?string
    {
        $return = null;

        if(Str::isStart($root,$value,false))
        {
            $value = Str::stripStart($root,$value,false);
            if(!empty($value))
            {
                if(!empty($option['root']))
                $value = Path::append($option['root'],$value);

                $return = self::fromPath($value,$option);
            }
        }

        return $return;
    }


    // fromPathRoots
    // retourne le fqcn à partir d'un path et un tableau root
    // si les clés de roots sont string non numérique, append au path
    final public static function fromPathRoots(string $value,array $roots,?array $option=null):?string
    {
        $return = null;

        foreach ($roots as $k => $root)
        {
            if(is_string($root))
            {
                $v = self::fromPathRoot($value,$root,(is_string($k))? Arr::plus($option,['root'=>$k]):$option);
                if(!empty($v))
                {
                    $return = $v;
                    break;
                }
            }
        }

        return $return;
    }


    // extension
    // change l'extension du chemin
    // utilise relative, ne force pas l'ajout d'un forward slash au path
    final public static function extension(string $value,$extension=null):string
    {
        return PathTrack::changeExtension($extension ?? self::getOption('extension'),$value);
    }
}

// init
Fqcn::__init();
?>