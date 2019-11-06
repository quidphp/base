<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _init
// trait that provides the logic to recursively merge the static properties with the parent's properties
trait _init
{
    // static
    protected static $initStaticProp = []; // tableau qui garde en mémoire les classes qui ont été init
    protected static $initCallable = null; // garde une copie de la callable à utiliser, par défaut array_replace_recursive


    // __init
    // initialise la configuration de la classe ayant le trait
    // merge une ou plusieurs propriétés static de la classe avec la même propriété de ces parents
    // possible de faire un merge recursif custom, selon la callable défini dans callableConfig
    // permet de merge une configuration des traits en dessous de la configuration courante de la classe
    // les config traits sont effacé pour éviter que d'autres classes utilisent les mêmes config pour le merge
    public static function __init(bool $force=false):void
    {
        $class = static::class;

        if($force === true || empty(static::$initStaticProp[$class]))
        {
            foreach (static::getInitProp() as $prop)
            {
                if(property_exists($class,$prop) && is_array(static::$$prop))
                {
                    $init = false;
                    $callable = static::getInitCallable();

                    $merge = [];
                    $vars = get_class_vars($class);
                    foreach (array_reverse($vars) as $key => $value)
                    {
                        if($key !== $prop && strpos($key,$prop) === 0 && is_array($value) && !empty($value))
                        {
                            $merge[] = $value;
                            static::$$key = [];
                        }
                    }

                    if(!empty($merge))
                    {
                        $merge[] = static::$$prop;
                        static::$$prop = $callable($class,...$merge);
                        $init = true;
                    }

                    $merge = [];

                    $parent = get_parent_class($class);
                    if(!empty($parent) && property_exists($parent,$prop) && is_array($parent::$$prop) && !empty($parent::$$prop))
                    $merge[] = $parent::$$prop;

                    if(!empty($merge) || $init === false)
                    {
                        $merge[] = static::$$prop;
                        static::$$prop = $callable($class,...$merge);
                        static::$initStaticProp[$class] = true;
                    }
                }
            }
        }

        return;
    }


    // getInitProp
    // retourne un tableau de propritéés statiques à merger
    protected static function getInitProp():array
    {
        return ['config'];
    }


    // getInitCallable
    // retourne la closure à utiliser pour le merge des propriétés static
    public static function getInitCallable():\Closure
    {
        $return = static::$initCallable;

        if(empty($return))
        {
            $return = function(string $class,...$values) {
                foreach ($values as &$value)
                {
                    $value = (array) $value;
                }

                return array_replace_recursive(...$values);
            };
        }

        return $return;
    }


    // setInitCallable
    // permet de changer la closure à utiliser pour le merge des propriétés static
    public static function setInitCallable(?\Closure $value=null):void
    {
        static::$initCallable = $value;

        return;
    }


    // initReplaceMode
    // retourne le tableau des clés à ne pas merger recursivement
    public static function initReplaceMode():array
    {
        return [];
    }
}
?>