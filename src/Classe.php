<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// classe
// class with static methods to deal with classes using fully qualified class name strings
final class Classe extends Root
{
    // config
    protected static array $config = [];


    // is
    // retourne vrai si la valeur est un objet ou une classe existante
    final public static function is($value,bool $autoload=true):bool
    {
        $return = false;

        if(is_object($value))
        $return = true;

        elseif(is_string($value) && class_exists($value,false))
        $return = true;

        else
        {
            $value = Fqcn::str($value);
            $return = (!empty($value) && class_exists($value,$autoload));
        }

        return $return;
    }


    // isInterface
    // retourne vrai si la valeur est une interface existante
    final public static function isInterface($value,bool $autoload=true):bool
    {
        $return = false;

        if(!is_object($value))
        {
            $value = Fqcn::str($value);
            $return = (!empty($value) && interface_exists($value,$autoload));
        }

        return $return;
    }


    // isTrait
    // retourne vrai si la valeur est un trait existant
    final public static function isTrait($value,bool $autoload=true):bool
    {
        $return = false;

        if(!is_object($value))
        {
            $value = Fqcn::str($value);
            $return = (!empty($value) && trait_exists($value,$autoload));
        }

        return $return;
    }


    // isAny
    // retourne vrai si la valeur est un objet, une classe existante, un trait existant ou une interface existante
    final public static function isAny($value,bool $autoload=true)
    {
        $return = false;

        if(is_object($value))
        $return = true;

        else
        {
            $value = Fqcn::str($value);
            if(!empty($value))
            $return = (class_exists($value,$autoload) || interface_exists($value,$autoload) || trait_exists($value,$autoload));
        }

        return $return;
    }


    // isIncomplete
    // retourne vrai si la valeur est une instance de la classe incomplete
    final public static function isIncomplete($value):bool
    {
        return self::instance($value,'__PHP_Incomplete_Class');
    }


    // isAnonymous
    // retourne vrai si le nom de la classe représente une classe anonyme
    final public static function isAnonymous($value):bool
    {
        $value = self::fqcn($value);
        return is_string($value) && strpos($value,'@') !== false;
    }


    // isNameClass
    // retourne vrai si le nom du fichier semble être une classe
    final public static function isNameClass($value):bool
    {
        return is_string($value) && strlen($value) && strpos($value,'_') === false && stripos($value,'Interface') === false;
    }


    // isNameTrait
    // retourne vrai si le nom du fichier semble être un trait
    // doit commencer par un _
    final public static function isNameTrait($value):bool
    {
        return is_string($value) && strlen($value) && strpos($value,'_') === 0;
    }


    // isNameInterface
    // retourne vrai si le nom du fichier semble être une interface
    // doit contenir Interface avec un i majuscule
    final public static function isNameInterface($value):bool
    {
        return is_string($value) && strlen($value) && strpos($value,'_') === false && stripos($value,'Interface') > 0;
    }


    // extend
    // retourne vrai si parent est étendu par value
    final public static function extend($parent,$value,bool $autoload=true):bool
    {
        $parent = Fqcn::str($parent);
        $value = self::get($value,$autoload);
        return !empty($parent) && !empty($value) && is_subclass_of($value,$parent,$autoload);
    }


    // extendOne
    // retourne vrai si au moins un parents est étendu par value
    final public static function extendOne(array $parents,$value,bool $autoload=true):bool
    {
        $return = false;
        $value = self::get($value,$autoload);

        if(!empty($value))
        {
            foreach ($parents as $v)
            {
                $v = Fqcn::str($v);
                if(!empty($v) && is_subclass_of($value,$v,$autoload))
                {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }


    // hasMethod
    // retourne vrai si la méthode existe dans la valeur qu'elle soit publique ou privé
    final public static function hasMethod($method,$value,bool $autoload=true):bool
    {
        $value = self::get($value,$autoload);
        return !empty($value) && is_string($method) && method_exists($value,$method);
    }


    // hasProperty
    // retourne vrai si la propriété existe dans la valeur qu'elle soit publique ou privé
    final public static function hasProperty($property,$value,bool $autoload=true):bool
    {
        $value = self::get($value,$autoload);
        return !empty($value) && is_string($property) && property_exists($value,$property);
    }


    // hasInterface
    // retourne vrai si la valeur utilise l'interface
    final public static function hasInterface($interface,$value,bool $autoload=true):bool
    {
        $return = false;
        $class = self::get($value,$autoload);

        if(!empty($value))
        {
            $interface = Fqcn::str($interface);
            $interfaces = self::interfaces($value,$autoload);
            $return = (!empty($interface) && !empty($interfaces) && Arr::in($interface,$interfaces,false));
        }

        return $return;
    }


    // hasTrait
    // retourne vrai si la valeur utilise le trait
    // si deep est true, alors la recherche se fait aussi dans les traits et dans les parents
    final public static function hasTrait($trait,$value,bool $deep=true,bool $autoload=true):bool
    {
        $return = false;
        $value = self::get($value,$autoload);

        if(!empty($value))
        {
            $trait = Fqcn::str($trait);
            $traits = self::traits($value,$deep,$autoload);
            $return = (!empty($trait) && !empty($traits) && Arr::in($trait,$traits,false));
        }

        return $return;
    }


    // hasNamespace
    // retourne vrai si la valeur a exactement le namespace spécifié
    // la valeur doit exister
    // la comparaison est insensible à la case
    final public static function hasNamespace($namespace,$value,bool $autoload=true):bool
    {
        $return = false;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = Fqcn::hasNamespace($namespace,$value);

        return $return;
    }


    // inNamespace
    // retourne vrai si la valeur fait partie du namespace spécifié
    // la valeur doit exister
    // la comparaison est insensible à la case
    final public static function inNamespace($namespace,$value,bool $autoload=true):bool
    {
        $return = false;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = Fqcn::inNamespace($namespace,$value);

        return $return;
    }


    // instance
    // retourne vrai si le premier objet a la même instance que tous les autres
    // autoload est true
    final public static function instance(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $instance = null;

            foreach ($values as $value)
            {
                $return = false;
                $value = Fqcn::str($value);

                if(!empty($value))
                {
                    if($instance === null)
                    {
                        $instance = $value;
                        $return = true;
                    }

                    elseif(is_a($value,$instance,true))
                    $return = true;
                }

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // sameInterface
    // retourne vrai si toutes les valeurs implémentes les mêmes interfaces
    // autoload est true par défaut
    final public static function sameInterface(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $interface = null;

            foreach ($values as $value)
            {
                $return = false;
                $value = self::get($value,true);

                if(!empty($value))
                {
                    if($interface === null)
                    {
                        $i = self::interfaces($value,true);
                        if(is_array($i))
                        {
                            $interface = $i;
                            $return = true;
                        }
                    }

                    elseif(is_array($interface))
                    {
                        $i = self::interfaces($value,true);
                        $return = ((empty($i) && empty($interface)) || Arr::ins($i,$interface,false,true));
                    }
                }

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // sameNamespace
    // retourne vrai si les valeurs ont le même namespace
    // les valeurs doivent existés, autoload est true par défaut
    // la comparaison est insensible à la case
    final public static function sameNamespace(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $namespace = null;

            foreach ($values as $value)
            {
                $return = false;
                $value = self::get($value,true);

                if(!empty($value))
                {
                    if($namespace === null)
                    {
                        $namespace = $value;
                        $return = true;
                    }

                    elseif(Fqcn::sameNamespace($value,$namespace))
                    $return = true;
                }

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // alias
    // crée un alias de classe, interface ou trait
    final public static function alias($alias,$value,bool $autoload=true):bool
    {
        $return = false;
        $alias = Fqcn::str($alias);
        $value = self::get($value,$autoload);

        if(!empty($alias) && !empty($value) && !self::isAny($alias))
        $return = class_alias($value,$alias,$autoload);

        return $return;
    }


    // aliases
    // crée plusieurs alias de classe, interface ou trait
    final public static function aliases(array $aliases,bool $autoload=true):array
    {
        $return = [];

        foreach ($aliases as $alias => $value)
        {
            $return[$alias] = self::alias($alias,$value,$autoload);
        }

        return $return;
    }


    // get
    // de préférence, retourne la valeur sous forme objet
    // si autoload est true et que la valeur est une classe, interface ou trait, retourne la string
    final public static function get($value,bool $autoload=false)
    {
        $return = null;

        if(is_object($value))
        $return = $value;

        elseif(is_string($value) && class_exists($value,false))
        $return = $value;

        elseif($autoload === true)
        {
            $value = Fqcn::str($value);

            if(!empty($value))
            {
                if(class_exists($value,$autoload) || interface_exists($value,$autoload) || trait_exists($value,$autoload))
                $return = $value;
            }
        }

        return $return;
    }


    // first
    // retourne le fqcn de la première classe existante
    // autoload est true par défaut
    final public static function first(...$values):?string
    {
        $return = null;

        foreach ($values as $value)
        {
            $return = self::get($value,true);

            if($return !== null)
            break;
        }

        return $return;
    }


    // fqcn
    // retourne le fully qualified class name si la classe, interface ou trait existe
    final public static function fqcn($value,bool $autoload=true):?string
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = Fqcn::str($value);

        return $return;
    }


    // namespace
    // retourne le namespace de la valeur si la classe, interface ou trait existe
    final public static function namespace($value,bool $autoload=true):?string
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = Fqcn::namespace($value);

        return $return;
    }


    // name
    // retour le nom de la valeur si la classe, interface ou trait existe
    final public static function name($value,bool $autoload=true):?string
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = Fqcn::name($value);

        return $return;
    }


    // type
    // retourne le type de la valeur
    // peut être une classe, interface ou trait
    final public static function type($value,bool $autoload=true):?string
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        {
            if(is_object($value) || class_exists($value,$autoload))
            $return = 'class';

            elseif(interface_exists($value,$autoload))
            $return = 'interface';

            elseif(trait_exists($value,$autoload))
            $return = 'trait';
        }

        return $return;
    }


    // parent
    // retourne le premier parent d'une classe
    // retourne null si interface, trait ou classe non existante
    final public static function parent($class,bool $autoload=true):?string
    {
        $return = null;

        if($autoload === true ||  self::is($class,$autoload))
        {
            $class = self::get($class,$autoload);

            if(!empty($class))
            {
                $parent = get_parent_class($class);
                if($parent !== false)
                $return = $parent;
            }
        }

        return $return;
    }


    // parents
    // retourne tous les parents d'une classe
    // retourne null si interface, trait ou classe non existante
    // possible d'inclure la classe si self est true
    // possible de pop des éléments à la fin du tableau de retour
    final public static function parents($class,bool $self=false,?int $pop=null,bool $autoload=true):?array
    {
        $return = null;

        if($autoload === true || self::is($class,$autoload))
        {
            $class = self::get($class,$autoload);

            if(!empty($class))
            {
                $return = [];

                $parents = class_parents($class,$autoload);
                if($parents !== false)
                $return = array_values($parents);

                if($self === true)
                array_unshift($return,self::fqcn($class));

                if(!empty($pop))
                Arr::pop($return,$pop);
            }
        }

        return $return;
    }


    // top
    // retourne le top parent de la classe ou la classe elle-même
    final public static function top($class,bool $autoload=true):?string
    {
        $return = null;
        $topParent = self::topParent($class,$autoload);

        if(!empty($topParent))
        $return = $topParent;

        else
        $return = self::fqcn($class,$autoload);

        return $return;
    }


    // topParent
    // retourne le top parent de la classe
    final public static function topParent($class,bool $autoload=true):?string
    {
        $return = null;
        $parents = self::parents($class,false,null,$autoload);

        if(!empty($parents))
        $return = Arr::valueLast($parents);

        return $return;
    }


    // methods
    // retourne toutes les méthodes publiques d'une classe, interface ou trait
    // retourne null si non existant
    final public static function methods($value,bool $autoload=true):?array
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        $return = get_class_methods($value);

        return $return;
    }


    // properties
    // retourne toutes les propriétés publiques par défaut d'une classe ou trait
    // si la variable est un objet, merge les propriétés dynamiques actuelles de l'objet
    // retourne null si interface ou non existant
    final public static function properties($value,bool $autoload=true):?array
    {
        $return = null;

        if(!self::isInterface($value))
        {
            $value = self::get($value,$autoload);

            if(!empty($value))
            {
                $name = Fqcn::str($value);
                $vars = get_class_vars($name);
                if($vars !== false)
                $return = $vars;

                if(is_object($value))
                $return = Arr::replace($return,get_object_vars($value));
            }
        }

        return $return;
    }


    // propertyMerge
    // permet de merge une propriété statique de la classe avec d'autres classes
    // la méthode retourne une closure, de cette façon les propriétés protégés peuvent aussi être lu (via bindTo)
    final public static function propertyMerge(string $property,string $class,array $others,?\Closure $closure=null):\Closure
    {
        return function() use($property,$class,$others,$closure):array {
            $return = [];

            if(empty($closure))
            $closure = fn(...$values) => Arrs::replace(...$values);

            if(property_exists($class,$property) && is_array($class::$$property))
            $return[] = $class::$$property;

            if(!empty($others))
            {
                foreach ($others as $other)
                {
                    if(property_exists($other,$property) && is_array($other::$$property))
                    {
                        $key = Arr::keyLast($return);

                        if(empty($return[$key]) || $other::$$property !== $return[$key])
                        $return[] = $other::$$property;
                    }
                }
            }

            if(!empty($return))
            {
                if(count($return) === 1)
                $return = current($return);

                else
                $return = $closure(...array_reverse($return));
            }

            return $return;
        };
    }


    // interfaces
    // retourne un tableau de toutes les interfaces implémentés par la classe ou interface
    // retourne null si trait ou non existant
    final public static function interfaces($value,bool $autoload=true):?array
    {
        $return = null;

        if(!self::isTrait($value))
        {
            $value = self::get($value,$autoload);

            if(!empty($value))
            {
                $interfaces = class_implements($value,$autoload);
                if($interfaces !== false)
                $return = array_values($interfaces);
            }
        }

        return $return;
    }


    // traits
    // retourne un tableau de tous les traits utilisés par une classe ou un trait
    // si deep est true, alors la recherche se fait aussi dans les traits et dans les les parents
    // retourne null si interface ou non existant
    final public static function traits($value,bool $deep=true,bool $autoload=true):?array
    {
        $return = null;

        if(!self::isInterface($value))
        {
            $value = self::get($value,$autoload);

            if(!empty($value))
            {
                $uses = class_uses($value,$autoload);
                if($uses !== false)
                {
                    $return = array_values($uses);

                    if($deep === true)
                    {
                        $deep = [];

                        // trait
                        foreach ($return as $v)
                        {
                            $deep = Arr::merge($deep,self::traits($v,true,$autoload));
                        }

                        // parent
                        $parents = (array) self::parents($value,false,null,$autoload);
                        foreach ($parents as $v)
                        {
                            $deep = Arr::merge($deep,self::traits($v,false,$autoload));
                        }

                        if(!empty($deep))
                        $return = Arr::imergeUnique($return,$deep);
                    }
                }
            }
        }

        return $return;
    }


    // namespaces
    // retourne un tableau contenant tous les namespaces uniques incluent dans un tableau
    // ne vérifie pas l'existence des éléments
    final public static function namespaces(array $array):array
    {
        $return = [];

        foreach ($array as $value)
        {
            $namespace = Fqcn::namespace($value);

            if(!empty($namespace) && !Arr::in($namespace,$return,false,true))
            $return[] = $namespace;
        }

        return $return;
    }


    // spl
    // retourne un tableau avec les classes spl déclarés
    final public static function spl():array
    {
        return spl_classes();
    }


    // declared
    // méhtode pour retourner les classes déclarés (possible d'inclure les traits et interfaces aussi)
    // possible de filtrer par namespace
    // dig permet de retenir aussi les namespaces non exact (mais contenant la valeur namespace)
    final public static function declared(?string $namespace=null,bool $onlyClass=true,bool $dig=false):array
    {
        $return = [];
        $declared = get_declared_classes();

        if($onlyClass === false)
        $declared = Arr::merge($declared,get_declared_interfaces(),get_declared_traits());

        if(empty($namespace))
        $return = $declared;

        else
        {
            foreach ($declared as $class)
            {
                if(strpos($class,'\\') !== false)
                {
                    $classNamespace = Fqcn::namespace($class);

                    if(!empty($classNamespace))
                    {
                        $keep = false;

                        if($dig === false && strtolower($classNamespace) === strtolower($namespace))
                        $keep = true;

                        elseif($dig === true && stripos($classNamespace,$namespace) === 0)
                        $keep = true;

                        if($keep === true)
                        $return[] = $class;
                    }
                }
            }
        }

        return $return;
    }


    // overview
    // retourne un tableau de toutes les classes, traits et interfaces déclarés
    // retourne aussi un tableau contenant les différents namespaces
    // possibilité de filtrer par namespace, interface ou trait
    // si méthode true, classe interface et trait sont passé dans methods, enfin le nombre total de méthode est retourné
    final public static function overview($filter=null,bool $namespace=false,bool $method=false,bool $sort=false):array
    {
        $return = [];

        $return['class'] = get_declared_classes();
        $return['interface'] = get_declared_interfaces();
        $return['trait'] = get_declared_traits();

        if($filter !== null)
        {
            $return['class'] = self::filter($filter,$return['class']);
            $return['interface'] = self::filter($filter,$return['interface']);
            $return['trait'] = self::filter($filter,$return['trait']);
        }

        if($namespace === true)
        {
            $append = Arr::merge($return['class'],$return['interface'],$return['trait']);
            $return['namespace'] = self::namespaces($append);
        }

        if($method === true)
        {
            foreach (['class','interface','trait'] as $k)
            {
                foreach ($return[$k] as $key => $value)
                {
                    unset($return[$k][$key]);
                    $methods = self::methods($value);
                    $return[$k][$value] = $methods;
                }
            }

            if($sort === true)
            $return = Arrs::keysSort($return);
        }

        return $return;
    }


    // total
    // retourne un tableau contenant le compte des classes, traits, interfaces, namespaces et méthodes
    // possible d'appliquer un filtre
    final public static function total($filter=null,bool $method=true):array
    {
        $return = ['class'=>0,'interface'=>0,'trait'=>0,'namespace'=>0];
        $all = self::overview($filter,true);

        $return['class'] = count($all['class']);
        $return['interface'] = count($all['interface']);
        $return['trait'] = count($all['trait']);
        $return['namespace'] = count($all['namespace']);

        if($method === true)
        {
            $return['method'] = 0;

            foreach (['class','interface','trait'] as $key)
            {
                foreach ($all[$key] as $value)
                {
                    $methods = self::methods($value);
                    if(is_array($methods))
                    $return['method'] += count($methods);
                }
            }
        }

        return $return;
    }


    // filter
    // filtre un tableau de classe, interface et trait selon leur utilisation d'un fqcn, namespace, interface, ou trait
    // si filter est scalar, il représente un namespace
    // si un filtre est null, il est ignoré
    // si un filtre est true, il doit avoir un namespace, trait ou interface
    // si un filtre est false, il ne doit pas avoir un namespace, trait ou interface
    // pour namespace, si le filtre est string il doit être exactement le namespace, sinon utilise le filtre fqcn
    // ne vérifie pas l'existence des éléments
    final public static function filter($filter,array $values,bool $autoload=true):array
    {
        $return = [];
        $filter = (is_scalar($filter))? ['namespace'=>$filter]:$filter;
        $filter = Arr::plus(['fqcn'=>null,'namespace'=>null,'interface'=>null,'trait'=>null],$filter);

        foreach ($values as $value)
        {
            $value = Fqcn::str($value);
            $keep = true;

            // fqcn
            if(is_string($filter['fqcn']) && stripos($value,$filter['fqcn']) !== 0)
            $keep = false;

            // namespace
            if($filter['namespace'] !== null && $keep === true)
            {
                $namespace = Fqcn::namespace($value);

                if(is_object($filter['namespace']))
                $filter['namespace'] = Fqcn::namespace($filter['namespace']);

                elseif(is_array($filter['namespace']) || is_string($filter['namespace']))
                $filter['namespace'] = Fqcn::str($filter['namespace']);

                if($filter['namespace'] === true && empty($namespace))
                $keep = false;

                elseif($filter['namespace'] === false && !empty($namespace))
                $keep = false;

                elseif(is_string($filter['namespace']) && !Str::icompare($filter['namespace'],$namespace))
                $keep = false;
            }

            // interface
            if($filter['interface'] !== null && $keep === true)
            {
                $array = self::interfaces($value,$autoload);

                if(empty($array) && !empty($filter['interface']))
                $keep = false;

                elseif(!empty($array) && empty($filter['interface']))
                $keep = false;

                elseif(!empty($array) && is_string($filter['interface']) && !Arr::in($filter['interface'],$array,false))
                $keep = false;
            }

            // trait
            if($filter['trait'] !== null && $keep === true)
            {
                $array = self::traits($value,$autoload);

                if(empty($array) && !empty($filter['trait']))
                $keep = false;

                elseif(!empty($array) && empty($filter['trait']))
                $keep = false;

                elseif(!empty($array) && is_string($filter['trait']) && !Arr::in($filter['trait'],$array,false))
                $keep = false;
            }

            if($keep === true)
            $return[] = $value;
        }

        return $return;
    }


    // info
    // exporte un tableau contenant le maximum d'informations sur une classe, interface ou trait
    // n'exporte pas les caractéristiques non compatible avec une classe, interface ou trait
    final public static function info($value,bool $deep=true,bool $autoload=true):?array
    {
        $return = null;
        $value = self::get($value,$autoload);

        if(!empty($value))
        {
            $return = [];
            $return['fqcn'] = self::fqcn($value);
            $return['namespace'] = self::namespace($value);
            $return['name'] = self::name($value);
            $return['type'] = self::type($value,$autoload);

            if($return['type'] === 'class')
            $return['parent'] = self::parents($value,false,null,$autoload);

            if($return['type'] !== 'interface')
            $return['trait'] = self::traits($value,$deep,$autoload);

            if($return['type'] !== 'trait')
            $return['interface'] = self::interfaces($value,$autoload);

            $return['method'] = self::methods($value,$autoload);

            if($return['type'] !== 'interface')
            $return['property'] = self::properties($value,$autoload);
        }

        return $return;
    }


    // sort
    // permet de sort un tableau unidimensionnel contenant des noms de classes via le résultat d'une méthode statique de la classe
    // possible de mettre des arguments pour la méthode pack après l'argument return
    final public static function sort($method,$sort=true,array $return,...$args):array
    {
        return Arr::methodSort($method,$sort,$return,...$args);
    }


    // sorts
    // permet de sort un tableau unidimensionnel contenant des noms classes via le résultat de plusiuers méthodes de la classe
    // pour chaque sort, il faur fournir un tableau array(method,sort,arg)
    // le sort conserve l'ordre naturel du tableau si les valeurs sont égales dans la comparaison et si un seul niveau de sort est envoyé
    // direction asc ou desc
    final public static function sorts(array $sorts,array $return):array
    {
        return Arr::methodSorts($sorts,$return);
    }
}
?>