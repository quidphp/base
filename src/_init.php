<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// _init
// trait that provides the logic to recursively merge the static properties with the parent's properties
trait _init
{
    // static
    protected static array $initStaticProp = []; // tableau qui garde en mémoire les classes qui ont été init
    protected static $initCallable; // garde une copie de la callable à utiliser, par défaut array_replace_recursive


    // __init
    // initialise la configuration de la classe ayant le trait
    // merge une ou plusieurs propriétés static de la classe avec la même propriété de ces parents
    // possible de faire un merge recursif custom, selon la callable défini dans callableConfig
    // permet de merge une configuration des traits en dessous de la configuration courante de la classe
    // les config traits sont effacé pour éviter que d'autres classes enfants utilisent les mêmes config pour le merge
    final public static function __init(bool $force=false):void
    {
        $class = static::class;

        if($force === true || empty(static::$initStaticProp[$class]))
        {
            foreach (static::getInitProp() as $prop)
            {
                if(property_exists($class,$prop) && is_array(static::$$prop))
                {
                    $init = false;
                    $merge = [];
                    $callable = static::getInitCallable();
                    $vars = static::getInitClassVars($prop,true);

                    foreach ($vars as $key => $value)
                    {
                        $merge[] = $value;
                        static::$$key = [];
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
    }


    // getInitClassVars
    // retourne le tableau des valeurs à merger en fonction de la propriété
    // attention, en php 8.1 la fonction retourne maintenant la valeur par défaut de la propriété
    // dans les versions précédentes c'étaient la valeur courante
    final protected static function getInitClassVars(string $prop,bool $reverse=true):array
    {
        $return = [];
        $vars = get_class_vars(static::class);

        if(is_array($vars))
        {
            foreach ($vars as $key => $value)
            {
                if($key !== $prop && strpos($key,$prop) === 0 && !empty(static::$$key) && is_array(static::$$key))
                $return[$key] = static::$$key;
            }

            if($reverse === true)
            $return = array_reverse($return);
        }

        return $return;
    }


    // getInitProp
    // retourne un tableau de propritéés statiques à merger
    protected static function getInitProp():array
    {
        return ['config'];
    }


    // getInitCallable
    // retourne la closure à utiliser pour le merge des propriétés static
    final protected static function getInitCallable():\Closure
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
    final protected static function setInitCallable(?\Closure $value=null):void
    {
        static::$initCallable = $value;
    }


    // initReplaceMode
    // retourne le tableau des clés à ne pas merger recursivement
    protected static function initReplaceMode():array
    {
        return [];
    }
}
?>