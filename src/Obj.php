<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// obj
// class with static methods to deal with objects, does not accept fqcn strings
final class Obj extends Root
{
    // config
    protected static array $config = [
        'method'=>'_cast', // méthode pour cast
        'cast'=>null // méthode à appeler si cast ne respecte pas le type
    ];


    // typecast
    // typecasts des valeurs par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            $value = (object) $value;
        }
    }


    // is
    // retourne vrai si la valeur est objet
    final public static function is($value):bool
    {
        return is_object($value);
    }


    // isIncomplete
    // retourne vrai si l'objet est une instance de la classe incomplete
    final public static function isIncomplete($value):bool
    {
        return is_object($value) && $value instanceof \__PHP_Incomplete_Class;
    }


    // isAnonymous
    // retourne vrai si c'est un objet anonyme
    final public static function isAnonymous($value):bool
    {
        return Classe::isAnonymous($value);
    }


    // extend
    // retourne vrai si le premier objet est étendu par le deuxième objet
    final public static function extend($parent,object $value):bool
    {
        return Classe::extend($parent,$value);
    }


    // extendOne
    // retourne vrai si un des premiers objet est étendu par le deuxième objet
    final public static function extendOne(array $parents,object $value):bool
    {
        return Classe::extendOne($parents,$value);
    }


    // hasMethod
    // retourne vrai si l'objet a la méthode qu'elle soit publique ou privé
    final public static function hasMethod($method,object $value):bool
    {
        return is_string($method) && method_exists($value,$method);
    }


    // hasProperty
    // retourne vrai si la propriété existe dans l'objet qu'elle soit publique ou privé
    final public static function hasProperty($property,object $value):bool
    {
        return is_string($property) && property_exists($value,$property);
    }


    // hasInterface
    // retourne vrai si l'objet implémente l'interface
    final public static function hasInterface($interface,object $value):bool
    {
        return Classe::hasInterface($interface,$value);
    }


    // hasTrait
    // retourne vrai si l'objet utilise le trait
    final public static function hasTrait($trait,object $value,bool $deep=true):bool
    {
        return Classe::hasTrait($trait,$value,$deep);
    }


    // hasNamespace
    // retourne vrai si l'objet a exactement le namespace
    final public static function hasNamespace($namespace,object $value):bool
    {
        return Fqcn::hasNamespace($namespace,$value);
    }


    // inNamespace
    // retourne vrai si l'objet est dans ce namespace
    final public static function inNamespace($namespace,object $value):bool
    {
        return Fqcn::inNamespace($namespace,$value);
    }


    // instance
    // retourne vrai si le premier objet a la même instance que tous les autres
    final public static function instance(object ...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            $instance = null;

            foreach ($values as $value)
            {
                $return = false;

                if($instance === null)
                {
                    $instance = $value;
                    $return = true;
                }

                elseif($value instanceof $instance)
                $return = true;

                if($return === false)
                break;
            }
        }

        return $return;
    }


    // sameInterface
    // retourne vrai si tous les objets implémentes les mêmes interfaces
    final public static function sameInterface(object ...$values):bool
    {
        return Classe::sameInterface(...$values);
    }


    // sameNamespace
    // retourne vrai si tous les objets ont le même namespace
    final public static function sameNamespace(object ...$values):bool
    {
        return Classe::sameNamespace(...$values);
    }


    // fqcn
    // retourne le fully qualified class name de l'objet
    final public static function fqcn(object $value):string
    {
        return Fqcn::str($value);
    }


    // namespace
    // retourne le namespace de l'objet ou null si non existant
    final public static function namespace(object $value):?string
    {
        return Fqcn::namespace($value);
    }


    // name
    // retourne le nom de l'objet sans le namespace
    final public static function name(object $value):string
    {
        return Fqcn::name($value);
    }


    // id
    // retourne l'id unique de l'objet
    final public static function id(object $value):int
    {
        return spl_object_id($value);
    }


    // hash
    // retourne le hash de l'objet
    final public static function hash(object $value):string
    {
        return spl_object_hash($value);
    }


    // parent
    // retourne le fqcn du parent de l'objet si existant, sinon null
    final public static function parent(object $value):?string
    {
        return Classe::parent($value);
    }


    // parents
    // retourne un tableau de tous les fqcn des parents de l'objet
    final public static function parents(object $value,bool $self=false,?int $pop=null):array
    {
        return Classe::parents($value,$self,$pop);
    }


    // top
    // retourne le fqcn du top parent de l'objet ou le fqcn de l'objet lui même
    final public static function top(object $value):?string
    {
        return Classe::top($value);
    }


    // topParent
    // retourne le fqcn du top parent de l'objet ou null
    final public static function topParent(object $value):?string
    {
        return Classe::topParent($value);
    }


    // methods
    // retourne un tableau contenant les méthodes publiques de l'objet
    final public static function methods(object $value):array
    {
        return Classe::methods($value);
    }


    // properties
    // retourne un tableau contenant les propriétés publiques de l'objet
    final public static function properties(object $value):array
    {
        return Classe::properties($value);
    }


    // interfaces
    // retourne un tableau contenant les interfaces implémentés par l'objet
    final public static function interfaces(object $value):array
    {
        return Classe::interfaces($value);
    }


    // traits
    // retourne un tableau contenant les traits utilisés par l'objet
    final public static function traits(object $value,bool $deep=true):array
    {
        return Classe::traits($value,$deep);
    }


    // info
    // export un objet en tableau, affiche le maximum d'informations disponible
    // si un tableau de propriété est donné en deuxième argument, ajoute les propriétés protégés ou privés de l'objet
    // si un tableau de methode est donné en troisième argument, ajoute les méthodes protégés ou privés de l'objet
    final public static function info(object $value,?array $properties=null,?array $methods=null,bool $deep=true):array
    {
        $return = Classe::info($value,$deep);

        if(!empty($methods) || !empty($properties))
        {
            if(!empty($methods))
            {
                foreach ($methods as $value)
                {
                    if(!in_array($value,$return['method'],true))
                    $return['*method'][] = $value;
                }
            }

            if(!empty($properties))
            {
                foreach ($properties as $key => $value)
                {
                    if(!array_key_exists($key,$return['property']))
                    $return['*property'][$key] = $value;
                }
            }
        }

        return $return;
    }


    // get
    // retourne la valeur d'une propriété
    // la propriété doit être public, pas accès au propriété protégé ou privé
    final public static function get(string $property,object $object)
    {
        return (property_exists($object,$property))? $object->$property:null;
    }


    // gets
    // retourne les valeurs de plusieurs propriétés
    // les propriétés doivent être public, pas accès au variable protégé ou privé
    final public static function gets(array $properties,object $object):array
    {
        $return = [];

        foreach ($properties as $property)
        {
            if(is_string($property))
            {
                if(property_exists($object,$property))
                $return[$property] = $object->$property;
                else
                $return[$property] = null;
            }
        }

        return $return;
    }


    // set
    // change la valeur d'une propriété publique
    // crée la propriété si elle n'existe pas
    // retourne l'objet
    final public static function set(string $property,$value,object $return):object
    {
        if(is_string($property))
        $return->$property = $value;

        return $return;
    }


    // sets
    // change la valeur de plusieurs propriétés publiques
    // crée les propriétés si elles n'existent pas
    // retourne l'objet
    final public static function sets(array $propertyValue,object $return):object
    {
        foreach ($propertyValue as $property => $value)
        {
            $return->$property = $value;
        }

        return $return;
    }


    // unset
    // enlève une propriété de l'objet si existante
    // retourne l'objet
    final public static function unset(string $property,object $return):object
    {
        if(property_exists($return,$property))
        unset($return->$property);

        return $return;
    }


    // unsets
    // enlève plusieurs propriétés de l'objet si existantes
    // retourne l'objet
    final public static function unsets(array $properties,object $return):object
    {
        foreach ($properties as $property)
        {
            if(self::hasProperty($property,$return))
            unset($return->$property);
        }

        return $return;
    }


    // create
    // créer un objet à partir d'un nom de classe et et d'arguments
    final public static function create($class,...$args):?object
    {
        $return = null;
        $class = Fqcn::str($class);

        if(!empty($class))
        $return = new $class(...$args);

        return $return;
    }


    // createArgs
    // créer un objet à partir d'un nom de classe et et d'un tableau d'arguments
    final public static function createArgs($class,array $args=[]):?object
    {
        $return = null;
        $class = Fqcn::str($class);

        if(!empty($class))
        $return = new $class(...array_values($args));

        return $return;
    }


    // createArray
    // créer un objet à partir d'un seul tableau
    // clé 0 = class, clé 1 = args
    final public static function createArray(array $array):?object
    {
        $return = null;

        if(count($array) === 2)
        {
            $class = Arr::get(0,$array);
            $class = Fqcn::str($class);

            if(!empty($class))
            {
                $args = (array) Arr::get(1,$array);
                $return = new $class(...array_values($args));
            }
        }

        return $return;
    }


    // sort
    // permet de sort un tableau unidimensionnel contenant des objets via le résultat d'une méthode de l'objet
    // possible de mettre des arguments pour la méthode pack après l'argument return
    final public static function sort($method,$sort=true,array $return,...$args):array
    {
        return Arr::methodSort('obj',$method,$sort,$return,...$args);
    }


    // sorts
    // permet de sort un tableau unidimensionnel contenant des objets via le résultat de plusiuers méthodes de l'objet
    // pour chaque sort, il faur fournir un tableau array(method,sort,arg)
    // le sort conserve l'ordre naturel du tableau si les valeurs sont égales dans la comparaison et si un seul niveau de sort est envoyé
    // direction asc ou desc
    final public static function sorts(array $sorts,array $return):array
    {
        return Arr::methodSorts('obj',$sorts,$return);
    }


    // cast
    // fonction de remplacement sur une valeur étant un objet ou pouvant contenir des objets
    // si l'objet a la méthode cast, l'objet est remplacé par le retour de la méthode
    // mode permet de spécifier une valeur de retour de la valeur, une erreur est lancé si non respect du type de retour
    // une resource est transformé en sa version base64
    final public static function cast($return,int $mode=0,?string $method=null)
    {
        if(is_object($return))
        {
            $method ??= self::$config['method'];

            if($return instanceof \Closure)
            $return = $return();

            elseif(method_exists($return,$method))
            $return = $return->$method();
        }

        elseif(is_array($return))
        {
            foreach ($return as $key => $value)
            {
                $return[$key] = self::cast($value,0,$method);
            }
        }

        elseif(is_resource($return))
        $return = Res::pathToUriOrBase64($return);

        if(!empty($mode))
        {
            if($mode === 1 && !is_string($return))
            $error = 'castStr';

            elseif($mode === 2 && !is_string($return) && $return !== null)
            $error = 'castStrOrNull';

            elseif($mode === 3 && !is_string($return) && !is_int($return))
            $error = 'castStrOrInt';

            elseif($mode === 4 && !is_int($return))
            $error = 'castInt';

            elseif($mode === 5 && !is_int($return) && $return !== null)
            $error = 'castIntOrNull';

            elseif($mode === 6 && !is_array($return))
            $error = 'castArray';

            if(!empty($error))
            {
                $callable = self::$config['cast'];

                if(!empty($callable))
                $callable($error);
            }
        }

        return $return;
    }


    // casts
    // fait le remplacement de plusieurs valeurs
    final public static function casts(int $mode=0,...$return):array
    {
        foreach ($return as $key => $value)
        {
            $return[$key] = self::cast($value,$mode);
        }

        return $return;
    }


    // setCastError
    // change la callable d'error pour cast
    final public static function setCastError(callable $callable):void
    {
        self::$config['cast'] = $callable;
    }
}
?>