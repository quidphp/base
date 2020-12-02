<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// arr
// class with static methods to work with unidimensional arrays
final class Arr extends Root
{
    // config
    protected static array $config = [];


    // typecast
    // typecasts des valeurs par référence
    final public static function typecast(&...$values):void
    {
        foreach ($values as &$value)
        {
            if($value === null)
            $value = (array) $value;

            elseif(!is_array($value))
            $value = [$value];
        }
    }


    // cast
    // permet de ramener les valeurs contenus dans un tableau dans leur cast naturel
    // par défaut, seul les nombres sont convertis
    final public static function cast($return,int $numberCast=1,int $boolCast=0):array
    {
        return self::map((array) $return,fn($value,$key) => (is_scalar($value))? Scalar::cast($value,$numberCast,$boolCast):$value);
    }


    // castMore
    // envoie à scalar cast avec paramètre 2,1
    // nombre sont convertis, virgule remplacer par décimal, et les string booleans sont transformés en bool
    final public static function castMore($return):array
    {
        return self::map((array) $return,fn($value,$key) => (is_scalar($value))? Scalar::castMore($value):$value);
    }


    // is
    // retourne vrai si la valeur est array
    final public static function is($value):bool
    {
        return is_array($value);
    }


    // isEmpty
    // retourne vrai si la valeur est array et vide
    final public static function isEmpty($value):bool
    {
        return is_array($value) && empty($value);
    }


    // isNotEmpty
    // retourne vrai si la valeur est array et non vide
    final public static function isNotEmpty($value):bool
    {
        return is_array($value) && !empty($value);
    }


    // hasNumericKey
    // retourne vrai si le tableau contient au moins une clé numérique
    // retourne faux si le tableau est vide
    final public static function hasNumericKey($array):bool
    {
        return self::some($array,fn($value,$key) => is_numeric($key));
    }


    // hasNonNumericKey
    // retourne vrai si le tableau contient au moins une clé non numérique
    // retourne faux si le tableau est vide
    final public static function hasNonNumericKey($array):bool
    {
        return (is_array($array))? self::some($array,fn($value,$key) => !is_numeric($key)):false;
    }


    // hasKeyCaseConflict
    // retourne vrai si le tableau contient au moins une clé en conflit de case si le tableau est insensible à la case
    final public static function hasKeyCaseConflict($value):bool
    {
        return is_array($value) && count($value) !== count(self::keysInsensitive($value));
    }


    // isIndexed
    // retourne vrai si le tableau est vide ou contient seulement des clés numériques
    final public static function isIndexed($array):bool
    {
        return (is_array($array))? self::every($array,fn($value,$key) => is_numeric($key)):false;
    }


    // isSequential
    // retourne vrai si le tableau est vide ou séquentielle
    final public static function isSequential($value):bool
    {
        return is_array($value) && (empty($value) || array_keys($value) === range(0, (count($value) - 1)));
    }


    // isAssoc
    // retourne vrai si le tableau est vide ou associatif
    // doit contenir au moins une clé non numérique
    final public static function isAssoc($value):bool
    {
        return is_array($value) && (empty($value) || !self::isIndexed($value));
    }


    // isUni
    // retourne vrai si le tableau est vide ou unidimensionnel
    final public static function isUni($array):bool
    {
        return (is_array($array))? self::every($array,fn($value) => !is_array($value)):false;
    }


    // isMulti
    // retourne vrai si le tableau est multidimensionel, retourne faux si vide
    final public static function isMulti($value):bool
    {
        return Arrs::is($value);
    }


    // onlyNumeric
    // retourne vrai si le tableau est vide ou a seulement des clés et valeurs numérique
    final public static function onlyNumeric($array):bool
    {
        return (is_array($array))? self::every($array,fn($value,$key) => is_numeric($key) && is_numeric($value)):false;
    }


    // onlyString
    // retourne vrai si le tableau est vide ou a seulement des clés non numériques et valeurs string
    final public static function onlyString($array):bool
    {
        return (is_array($array))? self::every($array,fn($value,$key) => is_string($key) && is_string($value)):false;
    }


    // isSet
    // retourne vrai si le tableau est vide ou contient seulement des clés numériques et valeurs scalar
    final public static function isSet($array):bool
    {
        return (is_array($array))? self::every($array,fn($value,$key) => is_numeric($key) && is_scalar($value)):false;
    }


    // isRange
    // retourne vrai si le tableau est un range valide (min, max, inc)
    final public static function isRange($value):bool
    {
        $return = false;

        if(is_array($value) && count($value) === 3)
        {
            $value = array_values($value);

            if(is_int($value[0]) && $value[0] >= 0)
            {
                if(is_int($value[1]) && $value[1] >= $value[0])
                $return = (is_int($value[2]) && $value[2] > 0);
            }
        }

        return $return;
    }


    // isKey
    // retourne vrai si la valeur est une clé
    final public static function isKey($value):bool
    {
        return is_scalar($value) && !is_bool($value);
    }


    // isCount
    // retourne vrai si le count est celui spécifié
    // possible de donner un tableau comme count
    final public static function isCount($count,$value):bool
    {
        $count = (is_array($count))? count($count):$count;
        return is_int($count) && is_array($value) && count($value) === $count;
    }


    // isMinCount
    // retourne vrai si le count est plus grand ou égal que celui spécifié
    // possible de donner un tableau comme count
    final public static function isMinCount($count,$value):bool
    {
        $count = (is_array($count))? count($count):$count;
        return is_int($count) && is_array($value) && count($value) >= $count;
    }


    // isMaxCount
    // retourne vrai si le count est plus petit ou égal que celui spécifié
    // possible de donner un tableau comme count
    final public static function isMaxCount($count,$value):bool
    {
        $count = (is_array($count))? count($count):$count;
        return is_int($count) && is_array($value) && count($value) <= $count;
    }


    // same
    // compare que les tableaux ont toutes les clés du premier et ont le même count
    final public static function same(...$values):bool
    {
        $return = false;

        if(count($values) > 1 && is_array($values[0]))
        {
            $keys = array_keys($values[0]);
            unset($values[0]);
            $return = self::every($values,fn($v) => is_array($v) && self::keysAre($keys,$v));
        }

        return $return;
    }


    // sameCount
    // compare que les tableaux ont le même compte que le premier
    final public static function sameCount(...$values):bool
    {
        $return = false;

        if(count($values) > 1 && is_array($values[0]))
        {
            $count = count($values[0]);
            unset($values[0]);
            $return = self::every($values,fn($v) => is_array($v) && count($v) === $count);
        }

        return $return;
    }


    // sameKey
    // compare que les tableaux ont toutes les clés du premier
    final public static function sameKey(...$values):bool
    {
        $return = false;

        if(count($values) > 1 && is_array($values[0]))
        {
            $keys = array_keys($values[0]);
            unset($values[0]);
            $return = self::every($values,fn($v) => is_array($v) && self::keysExists($keys,$v));
        }

        return $return;
    }


    // sameKeyValue
    // compare que les tableaux ont toutes les clés et valeurs du premier, pas nécessairement dans le même ordre
    final public static function sameKeyValue(...$values):bool
    {
        $return = false;

        if(count($values) > 1 && is_array($values[0]))
        {
            $array = self::keysSort($values[0]);
            unset($values[0]);
            $return = self::every($values,fn($v) => is_array($v) && !empty($v) && self::keysSort($v) === $array);
        }

        return $return;
    }


    // hasValueStart
    // retourne vrai si une des valeurs du tableaux est le début de la valeur donné en premier argument
    final public static function hasValueStart(string $value,array $array,bool $sensitive=true):bool
    {
        return self::some($array,fn($v) => is_string($v) && Str::isStart($v,$value,$sensitive));
    }


    // plus
    // combine plusieurs array ensemble + rapide
    // fonctionne si une valeur n'est pas un tableau
    final public static function plus(...$values):array
    {
        $return = [];
        self::typecast(...$values);

        foreach ($values as $v)
        {
            $return = $v + $return;
        }

        return $return;
    }


    // replace
    // wrapper pour array_replace, les valeurs sont cast
    final public static function replace(...$values):array
    {
        self::typecast(...$values);
        return array_replace(...$values);
    }


    // merge
    // wrapper pour array_merge, les valeurs sont cast
    final public static function merge(...$values):array
    {
        self::typecast(...$values);
        return array_merge(...$values);
    }


    // imerge
    // comme append mais les clés sont insensibles à la case
    final public static function imerge($return,...$values):array
    {
        self::typecast($return,...$values);

        foreach ($values as $k => $value)
        {
            foreach ($value as $k => $v)
            {
                if(is_numeric($k))
                $return[] = $v;

                else
                {
                    if(is_string($k))
                    $return = self::keyStrip($k,$return,false);

                    $return[$k] = $v;
                }
            }
        }

        return $return;
    }


    // mergeUnique
    // append des valeurs si non existantes dans le tableau
    // les clés numériques existantes sont conservés, les clés string sont remplacés
    final public static function mergeUnique($return,...$values):array
    {
        self::typecast($return,...$values);

        foreach ($values as $value)
        {
            foreach ($value as $k => $v)
            {
                if(!self::in($v,$return,true))
                {
                    if(is_numeric($k))
                    $return[] = $v;

                    else
                    $return[$k] = $v;
                }
            }
        }

        return $return;
    }


    // imergeUnique
    // pousse des valeurs si non existantes dans le tableau de façon insensible à la case
    // les clés numériques existantes sont conservés, les clés string sont remplacés
    final public static function imergeUnique($return,...$values):array
    {
        self::typecast($return,...$values);

        foreach ($values as $value)
        {
            foreach ($value as $k => $v)
            {
                if(!self::in($v,$return,false))
                {
                    if(is_numeric($k))
                    $return[] = $v;

                    else
                    {
                        if(is_string($k))
                        $return = self::keyStrip($k,$return,false);

                        $return[$k] = $v;
                    }
                }
            }
        }

        return $return;
    }


    // unshift
    // permet de unshift plusieurs valeurs au début d'un tableau, l'ordre des values est respecté
    // la première valeur n'a pas à être un tableau
    // aussi n'est pas passé par référence
    final public static function unshift($return,...$values)
    {
        self::typecast($return);

        foreach (array_reverse($values) as $v)
        {
            array_unshift($return,$v);
        }

        return $return;
    }


    // push
    // permet de push plusieurs valeurs à la fin du tableau return, l'ordre des values est respecté
    // la première valeur n'a pas à être un tableau
    // aussi n'est pas passé par référence
    final public static function push($return,...$values):array
    {
        self::typecast($return);

        foreach ($values as $v)
        {
            array_push($return,$v);
        }

        return $return;
    }


    // clean
    // enlève des éléments du tableau vraiment vide
    // si reset est true, reset les clés du tableau
    final public static function clean(array $return,bool $reset=false):array
    {
        foreach ($return as $k => $v)
        {
            if(Vari::isReallyEmpty($v))
            unset($return[$k]);
        }

        if($reset === true)
        $return = array_values($return);

        return $return;
    }


    // cleanEmpty
    // enlève des éléments du tableau vide
    // si reset est true, reset les clés du tableau
    final public static function cleanEmpty(array $return,bool $reset=false):array
    {
        foreach ($return as $k => $v)
        {
            if(Vari::isEmpty($v))
            unset($return[$k]);
        }

        if($reset === true)
        $return = array_values($return);

        return $return;
    }


    // cleanNull
    // enlève les clés dont la valeur est null
    // si reset est true, reset les clés du tableau
    final public static function cleanNull(array $return,bool $reset=false):array
    {
        foreach ($return as $k => $v)
        {
            if($v === null)
            unset($return[$k]);
        }

        if($reset === true)
        $return = array_values($return);

        return $return;
    }


    // cleanNullBool
    // enlève les clés dont la valeur est null ou bool
    // si reset est true, reset les clés du tableau
    final public static function cleanNullBool(array $return,bool $reset=false):array
    {
        foreach ($return as $k => $v)
        {
            if($v === null || is_bool($v))
            unset($return[$k]);
        }

        if($reset === true)
        $return = array_values($return);

        return $return;
    }


    // reallyEmptyToNull
    // change les éléments vides du tableau pour null
    final public static function reallyEmptyToNull(array $return):array
    {
        foreach ($return as $k => $v)
        {
            if(Vari::isReallyEmpty($v))
            $return[$k] = null;
        }

        return $return;
    }


    // trim
    // fait un trim sur les clés et/ou valeurs string du tableau
    final public static function trim(array $array,bool $key=false,bool $value=true):array
    {
        $return = [];

        foreach ($array as $k => $v)
        {
            if($key === true && is_string($k))
            $k = trim($k);

            if($value === true && is_string($v))
            $v = trim($v);

            $return[$k] = $v;
        }

        return $return;
    }


    // trimClean
    // fait trim et clean sur le tableau
    // trimKey permet de faire un trim sur les clés aussi
    final public static function trimClean(array $return,?bool $trimKey=false,?bool $trim=true,?bool $clean=true,?bool $reset=false):array
    {
        if($clean === true)
        $return = self::clean($return,$reset ?? false);

        if(is_bool($trim))
        $return = self::trim($return,$trimKey ?? false,$trim);

        return $return;
    }


    // validate
    // envoie chaque valeur du tableau dans validate::is
    final public static function validate($condition,array $value):bool
    {
        return Validate::are($condition,...array_values($value));
    }


    // validates
    // envoie plusieurs tableaux dans validate::is
    final public static function validates($condition,array ...$values):bool
    {
        return self::every($values,fn($value) => Validate::are($condition,...array_values($value)));
    }


    // get
    // retourne une valeur d'un tableau
    // support pour clé insensitive
    final public static function get($key,array $array,bool $sensitive=true)
    {
        $return = null;

        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $array = self::keysLower($array,true);
                $key = (is_string($key))? Str::lower($key,true):$key;
            }

            if(array_key_exists($key,$array))
            $return = $array[$key];
        }

        return $return;
    }


    // getSafe
    // retourne une valeur du tableau si key est scalar et array est array
    // sinon, array est retourné s'il est bien un tableau
    // sinon retoure null
    final public static function getSafe($key,$array,$sensitive=true)
    {
        $return = null;

        if(is_array($array))
        {
            if(is_scalar($key))
            $return = self::get($key,$array,$sensitive);

            else
            $return = $array;
        }

        return $return;
    }


    // gets
    // retourne plusieurs valeurs d'un tableau
    // par défaut les valeurs des clés non existentes sont retournés comme null
    // support pour clé insensitive
    final public static function gets(array $keys,array $array,bool $sensitive=true,bool $exists=false):array
    {
        $return = [];

        if($sensitive === false)
        $array = self::keysLower($array,true);

        foreach ($keys as $key)
        {
            if(self::isKey($key))
            {
                $target = (is_string($key) && $sensitive === false)? Str::lower($key,true):$key;

                if(array_key_exists($target,$array))
                $return[$key] = $array[$target];

                elseif($exists === false)
                $return[$key] = null;
            }
        }

        return $return;
    }


    // getsExists
    // comme gets mais la différence est que les clés non existentes ne sont pas retournés avec une valeur null
    final public static function getsExists(array $keys,array $array,bool $sensitive=true):array
    {
        return self::gets($keys,$array,$sensitive,true);
    }


    // indexPrepare
    // retourne la valeur positive d'un index négatif
    // count peut être numérique ou un tableau
    final public static function indexPrepare($index,$count)
    {
        $return = (is_scalar($index))? (int) $index:null;

        if(is_array($count))
        $count = count($count);

        if(is_int($count))
        {
            if(is_int($index))
            {
                $return = $index;

                if($index < 0 && ($count + $index) >= 0)
                $return = $count + $index;
            }

            elseif(is_array($index))
            {
                foreach ($index as $i)
                {
                    if($i < 0 && ($count + $i) >= 0)
                    $return[] = $count + $i;

                    else
                    $return[] = $i;
                }
            }
        }

        return $return;
    }


    // index
    // retourne une valeur à partir d'un index de tableau
    final public static function index(int $index,array $array)
    {
        $return = null;
        $array = array_values($array);

        if($index < 1)
        $index = self::indexPrepare($index,count($array));

        if(array_key_exists($index,$array))
        $return = $array[$index];

        return $return;
    }


    // indexes
    // retourne des valeurs à partir d'index de tableau
    final public static function indexes(array $indexes,array $array):array
    {
        $return = [];
        $array = array_values($array);
        $indexes = self::indexPrepare($indexes,count($array));

        foreach ($indexes as $index)
        {
            if(is_int($index))
            {
                if(array_key_exists($index,$array))
                $return[$index] = $array[$index];

                else
                $return[$index] = null;
            }
        }

        return $return;
    }


    // set
    // change la valeur d'un tableau
    // support pour clé insensitive
    // si key est null, append []
    // retourne le tableau
    final public static function set($key,$value,array $return,bool $sensitive=true):array
    {
        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $ikey = self::ikey($key,$return);
                if(!empty($ikey))
                $key = $ikey;
            }

            $return[$key] = $value;
        }

        elseif($key === null)
        $return[] = $value;

        return $return;
    }


    // sets
    // change plusieurs valeurs d'un tableau
    // support pour clés insensitives
    // retourne le tableau
    final public static function sets(array $keyValue,array $return,bool $sensitive=true):array
    {
        if(!empty($keyValue))
        {
            if($sensitive === false)
            $return = self::keysStrip(array_keys($keyValue),$return,$sensitive);

            foreach ($keyValue as $key => $value)
            {
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // setRef
    // change une valeur d'un tableau passé par référence
    // possibilité d'une opération insensible à la case
    // si key est null, append []
    final public static function setRef($key,$value,array &$array,bool $sensitive=true):void
    {
        $array = self::set($key,$value,$array,$sensitive);
    }


    // setsRef
    // change plusieurs valeurs d'un tableau passé par référence
    // possibilité d'une opération insensible à la case
    final public static function setsRef(array $keyValue,array &$array,bool $sensitive=true):void
    {
        $array = self::sets($keyValue,$array,$sensitive);
    }


    // setMerge
    // change la valeur d'un tableau ou merge la valeur dans un tableau si déjà existante
    // support pour clé insensitive
    // si key est null, append []
    // retourne le tableau
    final public static function setMerge($key,$value,array $return,bool $sensitive=true):array
    {
        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $ikey = self::ikey($key,$return);
                if(!empty($ikey))
                $key = $ikey;
            }

            if(array_key_exists($key,$return))
            $return[$key] = self::merge($return[$key],$value);
            else
            $return[$key] = $value;
        }

        elseif($key === null)
        $return[] = $value;

        return $return;
    }


    // setsMerge
    // change plusieurs valeurs d'un tableau ou merge les valeurs dans un tableau si déjà existante
    // support pour clé insensitive
    // retourne le tableau
    final public static function setsMerge(array $values,array $return,bool $sensitive=true):array
    {
        foreach ($values as $key => $value)
        {
            $return = self::setMerge($key,$value,$return,$sensitive);
        }

        return $return;
    }


    // unset
    // enlève une slice d'un tableau
    // support pour clé insensitive
    // retourne le tableau
    final public static function unset($key,array $return,bool $sensitive=true):array
    {
        return self::keyStrip($key,$return,$sensitive);
    }


    // unsets
    // efface plusieurs slices dans un tableau
    // support pour clés insensitives
    // retourne le tableau
    final public static function unsets(array $keys,array $return,bool $sensitive=true):array
    {
        return self::keysStrip($keys,$return,$sensitive);
    }


    // unsetRef
    // enlève une slice d'un tableau passé par référence
    // possibilité d'une opération insensible à la case
    final public static function unsetRef($key,array &$array,bool $sensitive=true):void
    {
        $array = self::unset($key,$array,$sensitive);
    }


    // unsetsRef
    // enlève plusieurs slices d'un tableau passé par référence
    // possibilité d'une opération insensible à la case
    final public static function unsetsRef(array $keys,array &$array,bool $sensitive=true):void
    {
        $array = self::unsets($keys,$array,$sensitive);
    }


    // getSet
    // permet de faire des modifications get/set sur un tableau unidimensionnel
    // le tableau est passé par référence
    // pas de support pour clé insensible à la case
    final public static function getSet($get=null,$set=null,array &$source)
    {
        $return = null;

        // get tout
        if($get === null && $set === null)
        $return = $source;

        // get un
        elseif(self::isKey($get) && $set === null)
        $return = self::get($get,$source);

        // tableau, écrase ou merge
        elseif(is_array($get))
        {
            if($set === true)
            $source = $get;
            else
            $source = self::replace($source,$get);

            $return = true;
        }

        // set un
        elseif(self::isKey($get) && $set !== null)
        {
            $source = self::set($get,$set,$source);
            $return = true;
        }

        return $return;
    }


    // keyValue
    // retourne un tableau clé valeur à partir d'une clé pour clé et une clé pour valeur
    final public static function keyValue($key,$value,array $array):array
    {
        $return = [];

        if(self::isKey($key) && self::isKey($value))
        {
            if(array_key_exists($key,$array) && self::isKey($array[$key]) && array_key_exists($value,$array))
            $return[$array[$key]] = $array[$value];
        }

        return $return;
    }


    // keyValueIndex
    // retourne un tableau clé valeur à partir d'un index pour clé et un index pour valeur
    final public static function keyValueIndex(int $key=0,int $value=1,array $array):array
    {
        $return = [];
        $array = array_values($array);

        if(array_key_exists($key,$array) && is_scalar($array[$key]) && array_key_exists($value,$array))
        $return[$array[$key]] = $array[$value];

        return $return;
    }


    // keys
    // retourne toutes les clés d'un tableau ou toutes les clés ayant la valeur donnée en deuxième argument
    // si valeur est null, la fonction ne cherche pas à moins que searchNull soit true
    // donc impossible de cherccher la valeur null avec cette fonction (utiliser arr::valueKey)
    // mb par défaut lors de la recherche insensitive
    final public static function keys(array $array,$value=null,bool $sensitive=true,bool $searchNull=false):array
    {
        $return = [];

        if($value === null && $searchNull === false)
        $return = array_keys($array);

        else
        {
            if($sensitive === false)
            {
                $array = self::valuesLower($array);
                $closure = fn($value) => (is_string($value))? Str::lower($value,true):$value;
                $value = (is_array($value))? self::map($value,$closure):$closure($value);
            }

            $return = array_keys($array,$value,true);
        }

        return $return;
    }


    // values
    // retourne les valeurs d'un tableau
    // is permet de spécifier le type de valeurs à garder dans le tableau réindexé
    final public static function values(array $array,$is=null):array
    {
        if($is !== null)
        $array = self::filter($array,fn($value) => Validate::is($is,$value));

        return array_values($array);
    }


    // shift
    // dépile un ou plusieurs éléments au début d'un tableau
    // array est passé par référence
    final public static function shift(array &$array,int $amount=1)
    {
        $return = null;

        if($amount === 1)
        $return = array_shift($array);

        elseif($amount > 1)
        {
            $return = [];

            while ($amount > 0)
            {
                $return[] = array_shift($array);
                $amount--;
            }
        }

        return $return;
    }


    // pop
    // enlève un ou plusieurs éléments à la fin du tableau
    // array est passé par référence
    final public static function pop(array &$array,int $amount=1)
    {
        $return = null;

        if($amount === 1)
        $return = array_pop($array);

        elseif($amount > 1)
        {
            $return = [];

            while ($amount > 0)
            {
                $return[] = array_pop($array);
                $amount--;
            }
        }

        return $return;
    }


    // walk
    // wrapper pour array_walk
    // array est passé par référence
    final public static function walk(array &$array,\Closure $closure,$data=null):bool
    {
        return array_walk($array,$closure,$data);
    }


    // find
    // retourne la première valeur qui remplit la condition de la closure
    // la clé est envoyé en deuxième argument
    final public static function find(array $array,\Closure $closure)
    {
        $return = null;

        foreach ($array as $key => $value)
        {
            if($closure($value,$key))
            {
                $return = $value;
                break;
            }
        }

        return $return;
    }


    // findKey
    // retourne la première clé dont la valeur remplit la condition de la closure
    // la clé est envoyé en deuxième argument
    final public static function findKey(array $array,\Closure $closure)
    {
        $return = null;

        foreach ($array as $key => $value)
        {
            if($closure($value,$key))
            {
                $return = $key;
                break;
            }
        }

        return $return;
    }


    // each
    // permet de faire un each dans l'array
    // si un loop retourne false, brise le loop et retourne false
    // pour être utile, il faut passer une valeur par référence dans la closure (pas de arrow func)
    final public static function each(array $array,\Closure $closure):bool
    {
        $return = true;

        foreach ($array as $key => $value)
        {
            $result = $closure($value,$key);

            if($result === false)
            {
                $return = false;
                break;
            }
        }

        return $return;
    }


    // some
    // vérifie qu'au moins une entrée du tableau passe le test de la closure
    final public static function some(array $array,\Closure $closure):bool
    {
        $return = false;

        foreach ($array as $key => $value)
        {
            if($closure($value,$key))
            {
                $return = true;
                break;
            }
        }

        return $return;
    }


    // every
    // vérifie que toutes les entrée du tableau passe le test de la closure
    final public static function every(array $array,\Closure $closure):bool
    {
        $return = true;

        foreach ($array as $key => $value)
        {
            if(!$closure($value,$key))
            {
                $return = false;
                break;
            }
        }

        return $return;
    }


    // map
    // comme array_map
    // la clé est envoyé en deuxième argument
    final public static function map(array $array,\Closure $closure):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            $return[$key] = $closure($value,$key);
        }

        return $return;
    }


    // filter
    // wrapper pour array_filter
    // la clé est envoyé en deuxième argument
    final public static function filter(array $array,\Closure $closure):array
    {
        return array_filter($array,$closure,ARRAY_FILTER_USE_BOTH);
    }


    // reduce
    // wrapper pour array_reduce, retourne une valeur simple à partir d'un tableau
    // changement de l'ordre des arguments, de même la clé est envoyé au callback en troisième argument
    final public static function reduce($return,array $array,\Closure $closure)
    {
        foreach ($array as $key => $value)
        {
            $return = $closure($return,$value,$key);
        }

        return $return;
    }


    // accumulate
    // comme reduce, mais le return est automatiquement append
    // pour les tableaux, la clé n'est pas conservé
    // si le callback retourne null, continue
    final public static function accumulate($return,array $array,\Closure $closure)
    {
        foreach ($array as $key => $value)
        {
            $r = $closure($value,$key);

            if($r === null)
            continue;

            elseif(is_int($return) || is_float($return))
            $return += $r;

            elseif(is_array($return))
            $return[] = $r;

            else
            $return .= $r;
        }

        return $return;
    }


    // diffAssoc
    // retourne les slices des clés et valeurs du premier tableau qui ne sont trouvés dans aucun autre tableau
    // possibilité de mettre des valeurs non scalar
    final public static function diffAssoc(array ...$values):array
    {
        $return = [];

        if(self::validates('scalar',...$values))
        $return = array_diff_assoc(...$values);

        elseif(count($values) > 1)
        {
            $main = $values[0];
            unset($values[0]);

            foreach ($main as $key => $value)
            {
                $found = false;
                foreach ($values as $v)
                {
                    if(array_key_exists($key,$v) && $v[$key] === $value)
                    $found = true;
                }

                if($found === false)
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // diffKey
    // retourne les slices des clés du premier tableau qui ne sont trouvés dans aucun autre tableau
    final public static function diffKey(array ...$values):array
    {
        return array_diff_key(...$values);
    }


    // diff
    // retourne les slices des valeurs du premier tableau qui ne sont trouvés dans aucun autre tableau
    // possibilité de mettre des valeurs non scalar
    final public static function diff(array ...$values):array
    {
        $return = [];

        if(self::validates('scalar',...$values))
        $return = array_diff(...$values);

        elseif(count($values) > 1)
        {
            $main = $values[0];
            unset($values[0]);

            foreach ($main as $key => $value)
            {
                $found = false;
                foreach ($values as $v)
                {
                    if(in_array($value,$v,true))
                    $found = true;
                }

                if($found === false)
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // intersectAssoc
    // retourne les slices identiques dans tous les tableaux
    // possibilité de mettre des valeurs non scalar
    final public static function intersectAssoc(array ...$values):array
    {
        $return = [];

        if(self::validates('scalar',...$values))
        $return = array_intersect_assoc(...$values);

        elseif(count($values) > 1)
        {
            $main = $values[0];
            unset($values[0]);

            foreach ($main as $key => $value)
            {
                $found = true;

                foreach ($values as $v)
                {
                    if(!array_key_exists($key,$v) || $v[$key] !== $value)
                    $found = false;
                }

                if($found === true)
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // intersectKey
    // retourne les slices des clés identiques dans tous les tableaux
    final public static function intersectKey(array ...$values):array
    {
        return array_intersect_key(...$values);
    }


    // intersect
    // retourne les slices des valeurs identiques dans tous les tableaux
    // possibilité de mettre des valeurs non scalar
    final public static function intersect(array ...$values):array
    {
        $return = [];

        if(self::validates('scalar',...$values))
        $return = array_intersect(...$values);

        elseif(count($values) > 1)
        {
            $main = $values[0];
            unset($values[0]);

            foreach ($main as $key => $value)
            {
                $found = true;

                foreach ($values as $v)
                {
                    if(!in_array($value,$v,true))
                    $found = false;
                }

                if($found === true)
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // unsetBeforeKey
    // enlève les entrées avant une clé
    final public static function unsetBeforeKey($key,array $return):array
    {
        foreach ($return as $k => $v)
        {
            if($k === $key)
            break;

            else
            unset($return[$k]);
        }

        return $return;
    }


    // unsetAfterKey
    // enlève les entrées après une clé
    final public static function unsetAfterKey($key,array $return):array
    {
        $delete = false;

        foreach ($return as $k => $v)
        {
            if($k === $key)
            $delete = true;

            elseif($delete === true)
            unset($return[$k]);
        }

        return $return;
    }


    // unsetBeforeValue
    // enlève les entrées avant une valeur
    // sensible à la case
    final public static function unsetBeforeValue($value,array $return):array
    {
        foreach ($return as $k => $v)
        {
            if($v === $value)
            break;

            else
            unset($return[$k]);
        }

        return $return;
    }


    // unsetAfterValue
    // enlève les entrées après une valeur
    // sensible à la case
    final public static function unsetAfterValue($value,array $return):array
    {
        $delete = false;

        foreach ($return as $k => $v)
        {
            if($v === $value)
            $delete = true;

            elseif($delete === true)
            unset($return[$k]);
        }

        return $return;
    }


    // unsetBeforeCount
    // enlève les entrées avant un certain nombre
    final public static function unsetBeforeCount(int $count,array $return):array
    {
        $i = 1;
        foreach ($return as $key => $value)
        {
            if($i < $count)
            unset($return[$key]);

            $i++;
        }

        return $return;
    }


    // unsetAfterCount
    // enlève les entrées après un certain nombre
    final public static function unsetAfterCount(int $count,array $return):array
    {
        $i = 1;
        foreach ($return as $key => $value)
        {
            if($i > $count)
            unset($return[$key]);

            $i++;
        }

        return $return;
    }


    // count
    // count les clés d'un tableau
    final public static function count(array $array):int
    {
        return count($array,COUNT_NORMAL);
    }


    // countValues
    // compte le nombre d'occurence d'une même valeur scalar dans un tableau
    // si une valeur n'est pas scalarNotBool, n'utilise pas la fonction php
    // mb par défaut lors de la recherche insensitive
    final public static function countValues(array $array,bool $sensitive=true):array
    {
        $return = [];

        if(self::validate('scalarNotBool',$array))
        $return = array_count_values($array);

        else
        {
            if($sensitive === false)
            $array = self::valuesLower($array);

            foreach ($array as $key => $value)
            {
                if(self::isKey($value))
                {
                    if(array_key_exists($value,$return))
                    $return[$value] += 1;
                    else
                    $return[$value] = 1;
                }
            }
        }

        return $return;
    }


    // search
    // retourne la clé de la première valeur trouvé
    // possibilité de faire une recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function search($value,array $array,bool $sensitive=true)
    {
        $return = null;

        if($sensitive === false)
        {
            $array = self::valuesLower($array);
            $closure = fn($value) => (is_string($value))? Str::lower($value,true):$value;
            $value = (is_array($value))? self::map($value,$closure):$closure($value);
        }

        $search = array_search($value,$array,true);
        if($search !== false)
        $return = $search;

        return $return;
    }


    // searchFirst
    // retourne la première clé qui existe dans le tableau
    // possibilité de faire une recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function searchFirst(array $values,array $array,bool $sensitive=true)
    {
        $return = null;

        if($sensitive === false)
        {
            $array = self::valuesLower($array);
            $values = self::valuesLower($values);
        }

        foreach ($values as $v)
        {
            $key = array_search($v,$array,true);

            if($key !== false)
            {
                $return = $key;
                break;
            }
        }

        return $return;
    }


    // in
    // recherche si la valeur est dans un tableau via la fonction in_array
    // possibilité de faire une recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function in($value,array $array,bool $sensitive=true,bool $debug=false):bool
    {
        if($sensitive === false)
        {
            $array = self::valuesLower($array);
            $closure = fn($value) => (is_string($value))? Str::lower($value,true):$value;
            $value = (is_array($value))? self::map($value,$closure):$closure($value);
        }

        return in_array($value,$array,true);
    }


    // ins
    // recherche que toutes les valeurs fournis sont dans le tableau via la fonction in_array
    // possibilité de faire une recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function ins(array $values,array $array,bool $sensitive=true):bool
    {
        $return = false;

        if(!empty($values))
        {
            $return = true;

            if($sensitive === false)
            {
                $array = self::valuesLower($array);
                $values = self::valuesLower($values);
            }

            foreach ($values as $value)
            {
                if(!in_array($value,$array,true))
                {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }


    // inFirst
    // retourne la première valeur trouvé dans le tableau ou null si rien n'est trouvé
    // possibilité de faire une recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function inFirst(array $values,array $array,bool $sensitive=true)
    {
        $return = null;

        if($sensitive === false)
        {
            $array = self::valuesLower($array);
            $values = self::valuesLower($values);
        }

        foreach ($values as $value)
        {
            if(in_array($value,$array,true))
            {
                $return = $value;
                break;
            }
        }

        return $return;
    }


    // combine
    // permet de créer des tableaux à partir d'une variable pour les clés et une autre pour les valeurs
    // si value est scalar ou null, la valeur est utilisé pour chaque clé
    // pas obligé de fournir des array
    // retourne un array vide en cas d'erreur
    final public static function combine($keys,$values):array
    {
        $return = [];
        $keys = (array) $keys;

        if(is_scalar($values) || $values === null)
        $values = array_fill_keys($keys,$values);

        if(!empty($keys) && self::validate('arrKey',$keys) && is_array($values) && count($keys) === count($values))
        $return = array_combine($keys,$values);

        return $return;
    }


    // uncombine
    // retourne un tableau à deux clés, avec array_keys et array_values
    final public static function uncombine(array $array):array
    {
        return [array_keys($array),array_values($array)];
    }


    // shuffle
    // mélange un tableau, mais conserve les clés
    final public static function shuffle(array $array,bool $preserve=true):array
    {
        $return = [];

        if($preserve === true)
        {
            $keys = array_keys($array);
            shuffle($keys);
            $return = self::getsExists($keys,$array);
        }

        else
        {
            $return = $array;
            shuffle($return);
        }

        return $return;
    }


    // reverse
    // invertit un tableau
    final public static function reverse(array $array,bool $preserve=true):array
    {
        return array_reverse($array,$preserve);
    }


    // getSortAscDesc
    // méthode utilisé par toutes les méthodes sortent pour déterminer ordre ascendant ou descendant
    final public static function getSortAscDesc($sort):?string
    {
        $return = null;

        if(in_array($sort,[true,'asc','ASC',1],true))
        $return = 'asc';

        elseif(in_array($sort,[false,'desc','DESC',2],true))
        $return = 'desc';

        return $return;
    }


    // sort
    // sort les clés ou valeurs d'un tableau
    // les clés sont conservés dans tous les cas
    // sort peut aussi être un int 1, 2, 3 ou 4
    final public static function sort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
    {
        $ascDesc = self::getSortAscDesc($sort);

        if($ascDesc === 'asc')
        $sort = 'ksort';

        elseif($ascDesc === 'desc')
        $sort = 'krsort';

        if($sort === 3)
        $sort = 'asort';

        elseif($sort === 4)
        $sort = 'arsort';

        if(Call::is($sort))
        $sort($return,$type);

        return $return;
    }


    // sortNumbersFirst
    // sort un tableau, met les clés avec numéros en premier
    // l'ordre des clés non numériques sont conservés
    final public static function sortNumbersFirst(array $array,$sort=true):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_numeric($key))
            {
                $return[$key] = $value;
                unset($array[$key]);
            }
        }

        if(!empty($return))
        $return = self::sort($return,$sort);

        if(!empty($array))
        $return = self::replace($return,$array);

        return $return;
    }


    // random
    // retourne une ou plusieurs clé valeur random d'un tableau
    // si csprng est true, utilise l'extension csprng pour généré le random
    final public static function random(array $array,int $count=1,bool $csprng=false):array
    {
        $return = [];

        if($csprng === true)
        $return = Crypt::randomArray($array,$count);

        else
        {
            $countArray = count($array);
            $count = ($count >= $countArray)? $countArray:$count;

            if($count > 0)
            {
                $keys = array_keys($array);
                $rand = array_rand($keys,$count);
                $return = self::gets((array) $rand,$array);
            }
        }

        return $return;
    }


    // pad
    // wrapper pour array_pad
    final public static function pad(int $size,$value,array $array):array
    {
        return array_pad($array,$size,$value);
    }


    // flip
    // reformat un tableau en s'assurant que la valeur devienne la clé
    // value permet de specifier la valeur des nouvelles valeurs du tableau, si null prend la clé
    // exception permet d'exclure le contenu d'une clé du reformatage
    final public static function flip(array $array,$value=null,$exception=null):array
    {
        $return = [];

        if(self::isKey($exception))
        $exception = [$exception];

        foreach ($array as $k => $v)
        {
            // exception
            if(!empty($exception) && is_array($exception) && in_array($k,$exception,true))
            $return[$k] = $v;

            // cle normal de tableau
            elseif(self::isKey($v))
            $return[$v] = $value ?? $k;

            // autre valeur
            else
            $return[$k] = $v;
        }

        return $return;
    }


    // unique
    // support pour recherche non sensible à la case
    // si removeOriginal est true, la première valeur unique sera effacé si un duplicat est trouvé
    final public static function unique(array $array,bool $removeOriginal=false,bool $sensitive=true):array
    {
        $return = [];

        if($removeOriginal === true)
        $original = [];

        foreach ($array as $key => $value)
        {
            if($removeOriginal === true)
            {
                if(!self::in($value,$return,$sensitive) && !self::in($value,$original,$sensitive))
                $return[$key] = $value;

                else
                {
                    $search = self::search($value,$return,$sensitive);
                    if($search !== null)
                    {
                        unset($return[$search]);
                        $original[] = $value;
                    }
                }
            }

            elseif(!self::in($value,$return,$sensitive))
            $return[$key] = $value;
        }

        return $return;
    }


    // duplicate
    // retourne les valeurs dupliqués, l'inverse de arr::unique
    // support pour recherche non sensible à la case
    final public static function duplicate(array $array,bool $keepOriginal=false,bool $sensitive=true):array
    {
        $return = [];

        if(!empty($array))
        {
            $unique = self::unique($array,$keepOriginal,$sensitive);

            if(!empty($unique))
            $return = self::diffKey($array,$unique);
            else
            $return = $array;
        }

        return $return;
    }


    // implode
    // implode un tableau en chaine
    // le delimiter divise chaque entrée, les tableaux du tableau sont ignorés
    // possibilité de trim et clean
    final public static function implode(string $delimiter,array $value,bool $trim=false,bool $clean=false):string
    {
        $return = '';

        if($trim === true || $clean === true)
        $value = self::trimClean($value,$trim,$trim,$clean);

        if(self::isUni($value))
        $return = implode($delimiter,$value);

        else
        {
            foreach ($value as $v)
            {
                if(is_scalar($v))
                {
                    $v = (string) $v;
                    $return .= (!empty($return))? $delimiter:'';
                    $return .= $v;
                }
            }
        }

        return $return;
    }


    // implodeTrim
    // implode un tableau en chaine
    // trim chaque entrée du tableau au préalable
    final public static function implodeTrim(string $delimiter,array $value,bool $clean=false):string
    {
        return self::implode($delimiter,$value,true,$clean);
    }


    // implodeTrimClean
    // implode un tableau en chaine
    // trim chaque entrée du tableau et clean le tableau au préalable
    final public static function implodeTrimClean(string $delimiter,array $value):string
    {
        return self::implode($delimiter,$value,true,true);
    }


    // implodeKey
    // fait un implode avec un deuxième délimiteur pour les clés
    // possibilité de trim et clean
    final public static function implodeKey(string $delimiter,string $keyDelimiter,array $value,$trim=false,bool $clean=false):string
    {
        $return = '';

        if($trim === true || $clean === true)
        $value = self::trimClean($value,$trim,$trim,$clean);

        foreach ($value as $k => $v)
        {
            if(is_scalar($v))
            {
                $k = (string) $k;
                $v = (string) $v;
                $return .= (!empty($return))? $delimiter:'';
                $return .= $k.$keyDelimiter.$v;
            }
        }

        return $return;
    }


    // explode
    // explose et merge toutes les variables scalar d'un tableau
    final public static function explode(string $delimiter,array $value,?int $limit=PHP_INT_MAX,bool $trim=false,bool $clean=false):array
    {
        $return = [];
        $limit ??= PHP_INT_MAX;

        foreach ($value as $k => $v)
        {
            if(is_scalar($v))
            {
                $v = (string) $v;
                $x = Str::explode($delimiter,$v,$limit,$trim,$clean);
                $limit -= count($x);
                $return = array_merge($return,$x);
            }
        }

        return $return;
    }


    // explodeKeyValue
    // explose les valeurs d'un tableau par deux et retourne sous une forme clé -> valeur
    final public static function explodeKeyValue(string $delimiter,array $value,bool $trim=false,bool $clean=false)
    {
        $return = [];

        foreach ($value as $k => $v)
        {
            if(is_scalar($v))
            {
                $v = (string) $v;
                $x = Str::explodeKeyValue($delimiter,$v,$trim,$clean);
                $return = self::replace($return,$x);
            }
        }

        return $return;
    }


    // fill
    // combine entre range et array_fill_keys
    final public static function fill(int $start=0,int $end=1,int $step=1,$value=true):array
    {
        $return = [];
        $keys = Integer::range($start,$end,$step);

        if(!empty($keys))
        $return = array_fill_keys($keys,$value);

        return $return;
    }


    // fillKeys
    // wrapper pour array_fill_keys
    final public static function fillKeys(array $keys,$value=true):array
    {
        return (!empty($keys))? array_fill_keys($keys,$value):[];
    }


    // chunk
    // prend un tableau et le divise selon la longueur que doit avoir chaque groupe
    // retourne un array multi
    final public static function chunk(int $count,array $array,bool $preserve=true):array
    {
        return array_chunk($array,$count,$preserve);
    }


    // chunkGroup
    // prend un tableau et le divise dans un nombre total de groupe
    // retourne un array multi
    final public static function chunkGroup(int $count,array $array,bool $preserve=true):array
    {
        $return = [];

        if(!empty($array) && $count > 0)
        {
            $count = (int) ceil(count($array) / $count);
            $return = array_chunk($array,$count,$preserve);
        }

        return $return;
    }


    // chunkMix
    // prend un tableau et le divise dans le nombre de groupe spécifié
    // mix les entrées selon cette logique -> (1,4),(2,5),(3,6)
    // retourne un array multi
    final public static function chunkMix(int $count,array $array,bool $preserve=true):array
    {
        $return = [];

        if(!empty($array) && $count > 0)
        {
            $total = (int) ceil(count($array) / $count);
            $int = 0;
            $col = 0;

            while (count($array))
            {
                for($i=0; $i < $count; $i++)
                {
                    $key = key($array);

                    if(is_numeric($key) || is_string($key))
                    {
                        $value = $array[$key];

                        if($preserve === true)
                        $return[$i][$key] = $value;
                        else
                        $return[$i][] = $value;

                        unset($array[$key]);
                    }
                }
            }
        }

        return $return;
    }


    // chunkWalk
    // permet de subdiviser un tableau en tableau colonne selon un callback
    // si callback retourne true, la colonne existante est stocké et une nouvelle colonne est crée
    // si callback retourne faux, la colonne existante est stocké et fermé
    // si callback retourne null, la ligne est stocké si la colonne est ouverte, sinon elle est ignoré
    // retourne un tableau multidimensionnel colonne
    final public static function chunkWalk(\Closure $callback,array $array):array
    {
        $return = [];
        $col = null;

        foreach ($array as $key => $value)
        {
            $result = $callback($value,$key,$array);

            if($result === true)
            {
                if(!empty($col))
                $return[] = $col;

                $col = [$value];
            }

            elseif($result === false)
            {
                if(!empty($col))
                $return[] = $col;

                $col = null;
            }

            elseif($result === null)
            {
                if(is_array($col))
                $col[] = $value;
            }
        }

        if(!empty($col))
        $return[] = $col;

        return $return;
    }


    // compareIn
    // compare un tableau selon un tableau in
    // toutes les clés de in doivent être dans array
    // la valeur dans le array doit être une des valeurs présentes dans le tableau in
    final public static function compareIn(array $in,array $array):bool
    {
        $return = true;

        if(!empty($in))
        {
            foreach ($in as $key => $value)
            {
                if($value !== null)
                {
                    $r = false;

                    if(array_key_exists($key,$array))
                    {
                        $value = (array) $value;

                        if(in_array($array[$key],$value,true))
                        $r = true;
                    }

                    if($r === false)
                    {
                        $return = false;
                        break;
                    }
                }
            }
        }

        return $return;
    }


    // compareOut
    // filtre un tableau selon un tableau out
    // array n'a pas besoin d'avoir les clés dans out
    // si la valeur dans le array est une des valeurs présentes dans le tableau out pour la même clé, retourne vrai
    final public static function compareOut(array $out,array $array):bool
    {
        $return = false;

        if(!empty($out))
        {
            foreach ($out as $key => $value)
            {
                if($value !== null)
                {
                    if(array_key_exists($key,$array))
                    {
                        $value = (array) $value;
                        $return = (in_array($array[$key],$value,true));
                    }

                    if($return === true)
                    break;
                }
            }
        }

        return $return;
    }


    // hasSlices
    // retourne vrai si toutes les slices (key pairs) du premier argument se retrouvent dans le tableau en deuxième argument
    // retourne vrai si slices est array mais vide
    final public static function hasSlices(array $slices,array $array,bool $sensitive=true):bool
    {
        if($sensitive === false && !empty($slices))
        {
            $slices = self::keysValuesLower($slices,true);
            $array = self::keysValuesLower($array,true);
        }

        return self::every($slices,fn($value,$key) => array_key_exists($key,$array) && $array[$key] === $value);
    }


    // slice
    // tranche des slices d'un array en utilisant start et end
    // start représente la clé de départ
    // end est la clé de fin
    // support pour clé insensible à la case
    final public static function slice($start,$end,array $array,bool $sensitive=true):array
    {
        $return = [];

        if(self::isKey($start))
        {
            $offset = self::keyIndex($start,$array,$sensitive);
            $length = 1;

            if(self::isKey($end))
            {
                $length = self::keyIndex($end,$array,$sensitive);

                if($length >= $offset)
                $length = $length - $offset + 1;

                else
                {
                    $x = $offset;
                    $offset = $length + 1;
                    $length = $x - $offset + 1;
                }
            }

            if(is_int($offset) && is_int($length) && is_array($array))
            $return = array_slice($array,$offset,$length,true);
        }

        return $return;
    }


    // sliceIndex
    // wrapper pour array_slice
    final public static function sliceIndex(int $offset,?int $length,array $array):array
    {
        $length ??= 1;
        return array_slice($array,$offset,$length,true);
    }


    // sliceStart
    // fait un slice à partir du début d'un tableau
    final public static function sliceStart(int $start,array $array):array
    {
        return array_slice($array,$start,null,true);
    }


    // sliceNav
    // permet de naviguer à travers les slices du tableau via l'argument nav
    // la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
    // retourne la slice ou null si non existante
    final public static function sliceNav($key,int $nav,array $source):?array
    {
        $return = null;
        $index = self::keyIndex($key,$source);

        if(is_int($index))
        {
            $newIndex = self::indexNav($index,$nav,$source);

            if(is_int($newIndex))
            $return = self::sliceIndex($newIndex,1,$source);
        }

        return $return;
    }


    // sliceNavIndex
    // navigue les slices du tableau par index
    // la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
    // retourne la slice ou null si non existante
    final public static function sliceNavIndex(int $index,int $nav,array $source):?array
    {
        $return = null;
        $newIndex = self::indexNav($index,$nav,$source);

        if(is_int($newIndex))
        $return = self::sliceIndex($newIndex,1,$source);

        return $return;
    }


    // splice
    // efface et remplace des slices d'un array en utilisant start et end
    // start représente la clé de départ
    // end est la clé de fin, si null représente 0 et si bool représente 1
    // à la différence de array_splice, les clés numérique ne sont pas réordonnées
    // important: les clés numériques existantes sont append, les clés string sont remplacés
    // support pour remplacement insensible à la case
    final public static function splice($start,$end,array $array,?array $replace=null,bool $sensitive=true):array
    {
        $return = [];

        if(self::isKey($start))
        {
            $offset = self::keyIndex($start,$array,$sensitive);
            $length = 1;

            if($end === null)
            $length = 0;

            elseif(is_bool($end))
            $length = 1;

            elseif(self::isKey($end))
            {
                $length = self::keyIndex($end,$array,$sensitive);

                if($length > $offset)
                $length = $length - $offset + 1;

                elseif($length < $offset)
                {
                    $x = $offset;
                    $offset = $length + 1;
                    $length = $x - $offset + 1;
                }

                elseif($length === $offset)
                $length = 1;
            }

            if(is_int($offset) && is_int($length))
            $return = self::spliceIndex($offset,$length,$array,$replace,$sensitive);

            else
            $return = $array;
        }

        return $return;
    }


    // spliceIndex
    // efface et remplace des slices d'un array en utilisant offset et length
    // à la différence de array_splice, les clés numérique ne sont pas réordonnées
    // important: les clés numériques existantes sont append, les clés string sont remplacés
    // support pour remplacement insensible à la case
    final public static function spliceIndex(int $offset,?int $length,array $array,?array $replace=null,bool $sensitive=true):array
    {
        $return = [];
        $length ??= 1;
        $keys = array_keys($array);
        $values = array_values($array);

        if(is_array($replace))
        {
            array_splice($keys,$offset,$length,array_keys($replace));
            array_splice($values,$offset,$length,array_values($replace));
        }

        else
        {
            array_splice($keys,$offset,$length);
            array_splice($values,$offset,$length);
        }

        foreach ($keys as $index => $key)
        {
            if(is_numeric($key) && array_key_exists($key,$return))
            $return[] = $values[$index];
            else
            $return[$key] = $values[$index];
        }

        if($sensitive === false)
        $return = self::keysInsensitive($return);

        return $return;
    }


    // spliceFirst
    // retourne le tableau sans la première slice
    // possibilité d'inséré du contenu au début via le tableau replace
    // support pour remplacement insensible à la case
    final public static function spliceFirst(array $array,?array $replace=null,bool $sensitive=true):array
    {
        return self::spliceIndex(0,1,$array,$replace,$sensitive);
    }


    // spliceLast
    // retourne le tableau sans la dernière slice
    // possibilité d'inséré du contenu à la fin via le tableau replace
    // support pour remplacement insensible à la case
    final public static function spliceLast(array $array,?array $replace=null,bool $sensitive=true):array
    {
        return self::spliceIndex(-1,1,$array,$replace,$sensitive);
    }


    // spliceValue
    // permet de splice une valeur d'un tableau et de faire un remplacement
    // à la différence des autres méthodes splice, celle-ci utilise une référence
    final public static function spliceValue($value,array &$array,$replace=null):array
    {
        $return = null;
        $index = self::valueIndex($value,$array);

        if(!empty($index))
        {
            $index = current($index);

            if($replace !== null)
            $return = array_splice($array,$index,1,$replace);
            else
            $return = array_splice($array,$index,1);
        }

        return $return;
    }


    // insert
    // effectue un remplacement via la méthode splice
    // n'enlève aucune rangée du tableau
    // support pour ajout insensible à la case
    final public static function insert($start,array $replace,array $array,bool $sensitive=true):array
    {
        return self::splice($start,null,$array,$replace,$sensitive);
    }


    // insertIndex
    // effectue un remplacement via la méthode spliceIndex
    // n'enlève aucune rangée du tableau
    // support pour ajout insensible à la case
    final public static function insertIndex(int $offset,array $replace,array $array,bool $sensitive=true):array
    {
        return self::spliceIndex($offset,0,$array,$replace,$sensitive);
    }


    // insertFirst
    // effectue un remplacement via la méthode spliceIndex
    // fait un remplacement au début du tableau
    // n'enlève aucune rangée du tableau
    // support pour ajout insensible à la case
    final public static function insertFirst(array $replace,array $array,bool $sensitive=true):array
    {
        return self::spliceIndex(0,0,$array,$replace,$sensitive);
    }


    // insertLast
    // effectue un remplacement via la méthode spliceIndex
    // fait un remplacement avant la dernière clé du tableau
    // n'enlève aucune rangée du tableau
    // support pour ajout insensible à la case
    final public static function insertLast(array $replace,array $array,bool $sensitive=true):array
    {
        return self::spliceIndex(-1,0,$array,$replace,$sensitive);
    }


    // insertInOrder
    // permet d'insérer des slices dans un tableau tout en conservant le caractère séquentielle des clés
    // idéaleement les clés des tableaux doivent être toutes du même type, la comparaison entre string et chiffre ne donne pas toujours les résultats souhaités
    final public static function insertInOrder(array $replace,array $return)
    {
        foreach ($replace as $key => $value)
        {
            $k = null;
            $found = false;

            foreach ($return as $k => $v)
            {
                if($k > $key)
                {
                    $found = true;
                    $return = self::insert($k,[$key=>$value],$return);
                    break;
                }
            }

            if($found === false)
            $return[$key] = $value;
        }

        return $return;
    }


    // firstWithKey
    // retourne le premier tableau où la clé existe
    final public static function firstWithKey($key,array ...$values):?array
    {
        return (is_scalar($key))? self::find($values,fn($array) => array_key_exists($key,$array)):null;
    }


    // firstWithValue
    // retourne le premier tableau où la valeur existe
    // sensible à la case
    final public static function firstWithValue($value,array ...$values):?array
    {
        return self::find($values,fn($array) => in_array($value,$array,true));
    }


    // indexFirst
    // retourne le premier index du tableau
    final public static function indexFirst(array $array):?int
    {
        return (!empty($array))? 0:null;
    }


    // indexLast
    // retourne le dernier index du tableau
    final public static function indexLast(array $array):?int
    {
        return (!empty($array))? (count($array) - 1):null;
    }


    // indexExists
    // retourne vrai si l'index existe dans le tableau
    final public static function indexExists(int $index,array $array):bool
    {
        $array = array_values($array);

        if($index < 1)
        $index = self::indexPrepare($index,count($array));

        return array_key_exists($index,$array);
    }


    // indexesExists
    // retourne vrai si les index existe dans le tableau
    final public static function indexesExists(array $indexes,array $array):bool
    {
        $return = false;
        $array = array_values($array);
        $indexes = self::indexPrepare($indexes,count($array));

        if(!empty($indexes))
        $return = self::every($indexes,fn($index) => array_key_exists($index,$array));

        return $return;
    }


    // indexesFirst
    // retourne le premier index trouvé dans un tableau
    final public static function indexesFirst(array $indexes,array $array):?int
    {
        $array = array_values($array);
        $indexes = self::indexPrepare($indexes,count($array));

        return self::find($indexes,fn($index) => array_key_exists($index,$array));
    }


    // indexKey
    // retourne la clé associé à un index de tableau
    final public static function indexKey(int $index,array $array)
    {
        $return = null;

        if($index < 0)
        $index = self::indexPrepare($index,count($array));

        if(is_int($index))
        {
            $keys = array_keys($array);

            if(array_key_exists($index,$keys))
            $return = $keys[$index];
        }

        return $return;
    }


    // indexesKey
    // retourne les clés associés aux index du tableau
    final public static function indexesKey(array $indexes,array $array):array
    {
        $return = [];
        $keys = array_keys($array);
        $indexes = self::indexPrepare($indexes,count($array));

        foreach ($indexes as $index)
        {
            if(array_key_exists($index,$keys))
            $return[$index] = $keys[$index];

            else
            $return[$index] = null;
        }

        return $return;
    }


    // indexSlice
    // retourne une slice à partir d'un index de tableau
    final public static function indexSlice(int $index,array $array):array
    {
        $return = [];
        $keys = array_keys($array);
        $array = array_values($array);

        if($index < 0)
        $index = self::indexPrepare($index,count($array));

        if(array_key_exists($index,$array))
        $return[$keys[$index]] = $array[$index];

        return $return;
    }


    // indexesSlice
    // retourne des slices à partir d'index de tableau
    final public static function indexesSlice(array $indexes,array $array):array
    {
        $return = [];
        $keys = array_keys($array);
        $array = array_values($array);
        $indexes = self::indexPrepare($indexes,count($array));

        foreach ($indexes as $index)
        {
            if(array_key_exists($index,$array))
            $return[$keys[$index]] = $array[$index];
        }

        return $return;
    }


    // indexStrip
    // retourne le tableau sans la slice de l'index
    final public static function indexStrip(int $index,array $return):array
    {
        $keys = array_keys($return);
        $array = array_values($return);

        if($index < 0)
        $index = self::indexPrepare($index,count($array));

        if(array_key_exists($index,$array))
        unset($return[$keys[$index]]);

        return $return;
    }


    // indexesStrip
    // retourne le tableau sans les slice des index
    final public static function indexesStrip(array $indexes,array $return):array
    {
        $keys = array_keys($return);
        $array = array_values($return);
        $indexes = self::indexPrepare($indexes,count($array));

        foreach ($indexes as $index)
        {
            if(array_key_exists($index,$array))
            unset($return[$keys[$index]]);
        }

        return $return;
    }


    // indexNav
    // permet de naviguer à travers les index du tableau via l'argument nav
    // la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
    // retourne le nouvel index ou null
    final public static function indexNav(int $index,int $nav,array $array):?int
    {
        $return = null;
        $keys = array_keys($array);
        $index = self::indexPrepare($index,count($array)) + $nav;

        if(array_key_exists($index,$keys))
        $return = $index;

        return $return;
    }


    // keyFirst
    // retourne la première key
    final public static function keyFirst(array $array)
    {
        return array_key_first($array);
    }


    // keyLast
    // retourne la dernière key
    final public static function keyLast(array $array)
    {
        return array_key_last($array);
    }


    // ikey
    // retourne la première clé se comparant insensible à la case avec la clé donné en argument
    final public static function ikey($key,array $array)
    {
        return self::findKey($array,fn($v,$k) => Str::icompare($key,$k));
    }


    // ikeys
    // retourne toutes les clés se comparant insensible à la case avec la clé donné en argument
    final public static function ikeys($key,array $array):array
    {
        $return = [];

        foreach ($array as $k => $value)
        {
            if(Str::icompare($key,$k))
            $return[] = $k;
        }

        return $return;
    }


    // keyExists
    // pour vérifier l'existence d'une clé dans un tableau
    // support pour clé insensitive
    final public static function keyExists($key,array $array,bool $sensitive=true):bool
    {
        $return = false;

        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $key = (is_string($key))? Str::lower($key,true):$key;
                $array = self::keysLower($array,true);
            }

            $return = (array_key_exists($key,$array));
        }

        return $return;
    }


    // keysExists
    // pour vérifier l'existence de toutes les clés fournis en argument
    // support pour clé insensitive
    final public static function keysExists(array $keys,array $array,bool $sensitive=true):bool
    {
        $return = false;

        if(!empty($keys))
        {
            if($sensitive === false)
            {
                $keys = self::valuesLower($keys);
                $array = self::keysLower($array,true);
            }

            $return = self::every($keys,fn($key) => self::isKey($key) && array_key_exists($key,$array));
        }

        return $return;
    }


    // keysAre
    // pour vérifier l'existence de toutes les clés dans un tableau
    // support pour clé insensitive
    final public static function keysAre(array $keys,array $array,bool $sensitive=true):bool
    {
        $return = false;

        if(!empty($keys))
        {
            if($sensitive === false)
            {
                $keys = self::valuesLower($keys);
                $array = self::keysLower($array,true);
            }

            if(count($keys) === count($array))
            $return = self::keysExists($keys,$array);
        }

        return $return;
    }


    // keysFirst
    // retourne la première clé trouvé dans un tableau
    // support pour clé insensitive
    final public static function keysFirst(array $keys,array $array,bool $sensitive=true)
    {
        $return = null;

        if(!empty($keys))
        {
            if($sensitive === false)
            {
                $original = $keys;
                $keys = self::valuesLower($keys);
                $array = self::keysLower($array,true);
            }

            foreach ($keys as $i => $key)
            {
                if(self::isKey($key) && array_key_exists($key,$array))
                {
                    $return = ($sensitive === true)? $key:$original[$i];
                    break;
                }
            }
        }

        return $return;
    }


    // keysIndexesFirst
    // retourne la première clé ou index trouvé dans un tableau
    // si la valeur est numérique, c'est considéré comme une recherche par index
    // support pour clé insensitive
    final public static function keysIndexesFirst(array $keys,array $array,bool $sensitive=true)
    {
        $return = null;

        if(!empty($keys))
        {
            if($sensitive === false)
            {
                $original = $keys;
                $keys = self::valuesLower($keys);
                $array = self::keysLower($array,true);
            }
            $arrayKeys = array_keys($array);

            foreach ($keys as $i => $key)
            {
                if(is_numeric($key))
                {
                    if(array_key_exists($key,$arrayKeys))
                    {
                        $return = ($sensitive === true)? $arrayKeys[$key]:$original[$arrayKeys[$key]];
                        break;
                    }
                }

                elseif(is_string($key))
                {
                    if(array_key_exists($key,$array))
                    {
                        $return = ($sensitive === true)? $key:$original[$i];
                        break;
                    }
                }
            }
        }

        return $return;
    }


    // keysFirstValue
    // retourne la valeur de la première clé trouvé dans un tableau
    // support pour clé insensitive
    final public static function keysFirstValue(array $keys,array $array,bool $sensitive=true)
    {
        $return = null;
        $key = self::keysFirst($keys,$array,$sensitive);

        if(self::isKey($key))
        {
            if($sensitive === false)
            $key = self::ikey($key,$array);

            $return = $array[$key];
        }

        return $return;
    }


    // keysIndexesFirstValue
    // retourne la valeur de la première clé ou index trouvé dans un tableau
    // si la valeur est numérique, c'est considéré comme une recherche par index
    // support pour clé insensitive
    final public static function keysIndexesFirstValue(array $keys,array $array,bool $sensitive=true)
    {
        $return = null;
        $key = self::keysIndexesFirst($keys,$array,$sensitive);

        if(self::isKey($key))
        {
            if($sensitive === false)
            $key = self::ikey($key,$array);

            $return = $array[$key];
        }

        return $return;
    }


    // keyIndex
    // retourne l'index d'une clé dans un tableau
    // retourne null si clé non existante
    // support pour clé insensitive
    final public static function keyIndex($key,array $array,bool $sensitive=true):?int
    {
        $return = null;

        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $key = (is_string($key))? Str::lower($key,true):$key;
                $array = self::keysLower($array,true);
            }

            if(array_key_exists($key,$array))
            {
                $search = array_search($key,array_keys($array),true);

                if($search !== false)
                $return = $search;
            }
        }

        return $return;
    }


    // keysIndex
    // retourne les index de clé dans un tableau, retourne un tableau
    // support pour clé insensitive
    final public static function keysIndex(array $keys,array $array,bool $sensitive=true):array
    {
        $return = [];

        if(!empty($keys))
        {
            if($sensitive === false)
            {
                $original = $keys;
                $keys = self::valuesLower($keys);
                $array = self::keysLower($array,true);
            }

            $arrayKeys = array_keys($array);

            foreach ($keys as $i => $key)
            {
                if(self::isKey($key))
                {
                    $k = ($sensitive === true)? $key:$original[$i];
                    $search = array_search($key,$arrayKeys,true);

                    if($search !== false)
                    $return[$k] = $search;

                    else
                    $return[$k] = null;
                }
            }
        }

        return $return;
    }


    // keySlice
    // retourne la slice du tableau à la clé donné
    // support pour clé insensitive, va retourner la dernière slice comparable insensible à la case avec la clé fournie
    final public static function keySlice($key,array $array,bool $sensitive=true):array
    {
        $return = [];

        if(self::isKey($key))
        {
            if($sensitive === false)
            {
                $original = $key;
                $key = (is_string($key))? Str::lower($key,true):$key;
                $array = self::keysLower($array,true);
            }

            if(array_key_exists($key,$array))
            {
                $k = ($sensitive === true)? $key:$original;
                $return[$k] = $array[$key];
            }
        }

        return $return;
    }


    // ikeySlice
    // retourne toutes les slices avec des clés se comparant insensible à la case avec la clé donné en argument
    final public static function ikeySlice($key,array $array):array
    {
        $return = [];

        if(self::isKey($key))
        {
            foreach ($array as $k => $value)
            {
                if(Str::iCompare($key,$k))
                $return[$k] = $value;
            }
        }

        return $return;
    }


    // keyStrip
    // retourne le tableau sans la slice de la clé
    // support pour clé insensitive, va strip toutes les clés se comparant de façon insensible à la case
    final public static function keyStrip($key,array $return,bool $sensitive=true):array
    {
        if(self::isKey($key))
        {
            if($sensitive === true || is_numeric($key))
            {
                if(array_key_exists($key,$return))
                unset($return[$key]);
            }

            else
            {
                foreach ($return as $k => $v)
                {
                    if(is_string($k) && Str::icompare($k,$key))
                    unset($return[$k]);
                }
            }
        }

        return $return;
    }


    // keysStrip
    // retourne le tableau sans les slices des clés
    // support pour clé insensitive, va strip toutes les clés se comparant de façon insensible à la case
    final public static function keysStrip(array $keys,array $return,bool $sensitive=true):array
    {
        foreach ($keys as $key)
        {
            if(self::isKey($key))
            {
                if($sensitive === true || is_numeric($key))
                {
                    if(array_key_exists($key,$return))
                    unset($return[$key]);
                }

                else
                {
                    foreach ($return as $k => $v)
                    {
                        if(is_string($k) && Str::icompare($k,$key))
                        unset($return[$k]);
                    }
                }
            }
        }

        return $return;
    }


    // keyNav
    // permet de naviguer à travers les clés du tableau via l'argument nav
    // la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
    // retourne la nouvelle clé ou null
    final public static function keyNav($key,int $nav,array $array)
    {
        $return = null;
        $index = self::keyIndex($key,$array);

        if(is_int($index))
        {
            $newIndex = self::indexNav($index,$nav,$array);

            if(is_int($newIndex))
            {
                $keys = array_keys($array);
                if(array_key_exists($newIndex,$keys))
                $return = $keys[$newIndex];
            }
        }

        return $return;
    }


    // keysStart
    // retourne les slices des clés commençant par la chaîne
    final public static function keysStart(string $str,array $array,bool $sensitive=true):array
    {
        return self::filter($array,fn($value,$key) => (is_string($key) && Str::isStart($str,$key,$sensitive)));
    }


    // keysEnd
    // retourne les slices des clés finissant par la chaîne
    final public static function keysEnd(string $str,array $array,bool $sensitive=true):array
    {
        return self::filter($array,fn($value,$key) => (is_string($key) && Str::isEnd($str,$key,$sensitive)));
    }


    // keysMap
    // permet de changer les clés d'un tableau via callback
    final public static function keysMap(\Closure $closure,$array,bool $string=false,...$args):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if($string === false || is_string($key))
            $key = $closure($key,...$args);

            if(self::isKey($key))
            $return[$key] = $value;
        }

        return $return;
    }


    // keysChangeCase
    // change la case des clés dans le tableau
    // case peut etre CASE_LOWER, CASE_UPPER ou callable
    final public static function keysChangeCase($case,array $return,...$args):array
    {
        if(in_array($case,[CASE_LOWER,'lower','strtolower'],true))
        $return = self::keysLower($return,...$args);

        elseif(in_array($case,[CASE_UPPER,'upper','strtoupper'],true))
        $return = self::keysUpper($return,...$args);

        elseif(Call::is($case))
        {
            $case = ($case instanceof \Closure)? $case:fn($value) => $case($value);
            $return = self::keysMap($case,$return,true,...$args);
        }

        return $return;
    }


    // keysLower
    // change la case des clés dans le tableau pour lowercase
    // support pour multibyte
    final public static function keysLower(array $array,?bool $mb=null):array
    {
        $return = [];
        $mb ??= Encoding::getMb($mb);

        if($mb === false)
        $return = array_change_key_case($array,CASE_LOWER);

        else
        {
            foreach ($array as $key => $value)
            {
                $key = (is_string($key))? Str::lower($key,true):$key;
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // keysUpper
    // change la case des clés dans le tableau pour uppercase
    // support pour multibyte
    final public static function keysUpper(array $array,?bool $mb=null):array
    {
        $return = [];
        $mb ??= Encoding::getMb($mb);

        if($mb === false)
        $return = array_change_key_case($array,CASE_UPPER);

        else
        {
            foreach ($array as $key => $value)
            {
                if(is_string($key))
                $key = Str::upper($key,true);

                $return[$key] = $value;
            }
        }

        return $return;
    }


    // keysInsensitive
    // retourne une version du tableau avec les clés en conflit de case retirés
    // garde la même case
    final public static function keysInsensitive(array $array):array
    {
        $return = [];

        foreach ($array as $k => $v)
        {
            if(is_string($k))
            $return = self::keyStrip($k,$return,false);

            $return[$k] = $v;
        }

        return $return;
    }


    // keysWrap
    // permet de wrapper la clé dans un delimiteur
    // il y a 7 modes, par défaut 0
    // mb sert seulement si le caractère a wrap est accenté minuscule vs majuscule
    final public static function keysWrap(string $start,?string $end,array $array,int $mode=0,bool $sensitive=true):array
    {
        $return = [];

        if($end === null)
        $end = $start;

        foreach ($array as $key => $value)
        {
            $key = (string) $key;

            if($mode === 0)
            $key = $start.$key.$end;

            elseif($mode === 1)
            $key = Str::wrapStartOrEnd($start,$end,$key,$sensitive);

            elseif($mode === 2)
            $key = Str::wrapStartEnd($start,$end,$key,$sensitive);

            elseif($mode === 3)
            $key = $start.$key;

            elseif($mode === 4)
            $key = Str::wrapStart($start,$key,$sensitive);

            elseif($mode === 5)
            $key = $key.$end;

            elseif($mode === 6)
            $key = Str::wrapEnd($end,$key,$sensitive);

            if(!array_key_exists($key,$return))
            $return[$key] = $value;
        }

        return $return;
    }


    // keysUnwrap
    // permet de unwrapper la clé d'un délimiteur
    // il y a 4 modes, par défaut 0
    // mb sert seulement si le caractère a unwrap est accenté minuscule vs majuscule
    final public static function keysUnwrap(string $start,?string $end,array $array,int $mode=0,bool $sensitive=true):array
    {
        $return = [];

        if($end === null)
        $end = $start;

        foreach ($array as $key => $value)
        {
            $key = (string) $key;

            if($mode === 0)
            $key = Str::stripStartOrEnd($start,$end,$key,$sensitive);

            elseif($mode === 1)
            $key = Str::stripStartEnd($start,$end,$key,$sensitive);

            elseif($mode === 2)
            $key = Str::stripStart($start,$key,$sensitive);

            elseif($mode === 3)
            $key = Str::stripEnd($end,$key,$sensitive);

            if(!array_key_exists($key,$return))
            $return[$key] = $value;
        }

        return $return;
    }


    // keysReplace
    // str replace sur les clés du tableau
    final public static function keysReplace(array $replace,array $return,bool $once=true,bool $sensitive=true):array
    {
        if(!empty($replace))
        {
            foreach ($return as $key => $value)
            {
                $strKey = (string) $key;
                $k = Str::replace($replace,$strKey,$once,$sensitive);

                if($k !== $strKey)
                {
                    $return[$k] = $return[$key];
                    unset($return[$key]);
                }
            }
        }

        return $return;
    }


    // keysChange
    // permet de renommer des clés dans un tableau, tout en conservant les valeurs
    final public static function keysChange(array $replace,array $return):array
    {
        foreach ($replace as $what => $to)
        {
            if(array_key_exists($what,$return) && self::isKey($to))
            {
                $value = $return[$what];
                unset($return[$what]);
                $return[$to] = $value;
            }
        }

        return $return;
    }


    // keysMissing
    // remplit les clés manquantes d'un tableau numérique
    // firstKey permet de spécifier à quel clé devrait commencer le remplacent, si null alors c'est à la première clé
    final public static function keysMissing(array $array,$value=false,?int $firstKey=null):array
    {
        $return = [];

        if(!empty($array) && self::isIndexed($array))
        {
            $lastKey = null;
            if(is_int($firstKey))
            $lastKey = $firstKey - 1;

            foreach ($array as $k => $v)
            {
                $lastKeyPlus = $lastKey + 1;
                if(is_numeric($lastKey) && is_numeric($k) && $k !== $lastKeyPlus)
                {
                    $range = Integer::range($lastKeyPlus,(int) ($k - 1));
                    if(!empty($range))
                    {
                        $missing = array_fill_keys(array_values($range),$value);
                        $return = $return + $missing;
                    }
                }

                $return[$k] = $v;
                $lastKey = $k;
            }
        }

        return $return;
    }


    // keysReindex
    // réindex les clés numériques d'un tableau
    final public static function keysReindex(array $array,int $i=0):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_numeric($key))
            {
                $return[$i] = $value;
                $i++;
            }

            else
            $return[$key] = $value;
        }

        return $return;
    }


    // keysSort
    // sort un tableau par clé
    // on peut mettre asc, true ou desc, false à sort (ksort ou krsort)
    // renvoie à la méthode sort
    final public static function keysSort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
    {
        $ascDesc = self::getSortAscDesc($sort);

        if($ascDesc === 'asc')
        $sort = 'ksort';

        elseif($ascDesc === 'desc')
        $sort = 'krsort';

        return self::sort($return,$sort,$type);
    }


    // keyRandom
    // retourne la clé random d'un tableau
    final public static function keyRandom(array $array)
    {
        $return = null;
        $slice = self::random($array,1);

        if(!empty($slice))
        $return = key($slice);

        return $return;
    }


    // valuesAre
    // retourne vrai si toutes les valeurs différentes du tableau sont dans le tableau values
    // support pour recherche insensible à la case
    final public static function valuesAre(array $values,array $array,bool $sensitive=true):bool
    {
        $unique = self::unique($array,false,$sensitive);
        return self::ins($unique,$values,$sensitive);
    }


    // valueFirst
    // retourne la première valeur
    final public static function valueFirst(array $array)
    {
        $return = null;

        if(!empty($array))
        {
            $key = self::keyFirst($array);
            $return = $array[$key];
        }

        return $return;
    }


    // valueLast
    // retourne la dernière valeur
    final public static function valueLast(array $array)
    {
        $return = null;

        if(!empty($array))
        {
            $key = self::keyLast($array);
            $return = $array[$key];
        }

        return $return;
    }


    // valueIndex
    // retourne tous les index contenant la valeur donnée
    // permet la recherche insensible à la case
    final public static function valueIndex($value,array $array,bool $sensitive=true):array
    {
        $return = [];
        $keys = self::valueKey($value,$array,$sensitive);

        if(!empty($keys))
        $return = array_values(self::keysIndex($keys,$array));

        return $return;
    }


    // valuesIndex
    // retourne tous les index contenant les valeurs données
    // permet la recherche insensible à la case
    final public static function valuesIndex(array $values,array $array,bool $sensitive=true):array
    {
        $return = [];
        $keys = self::valuesKey($values,$array,$sensitive);

        if(!empty($keys))
        $return = array_values(self::keysIndex($keys,$array));

        return $return;
    }


    // valueKey
    // retourne toutes les clés contenant contenant la valeur donnée
    // permet la recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function valueKey($value,array $array,bool $sensitive=true):array
    {
        return self::keys($array,$value,$sensitive,true);
    }


    // valuesAll
    // permet de changer la valeur de toutes les clés du tableau
    final public static function valuesAll($value,array $return)
    {
        return self::map($return,fn() => $value);
    }


    // valuesKey
    // retourne toutes les clés contenant les valeurs données
    // permet la recherche insensible à la case
    // mb par défaut lors de la recherche insensitive
    final public static function valuesKey(array $values,array $array,bool $sensitive=true):array
    {
        $return = [];

        if($sensitive === false)
        {
            $array = self::valuesLower($array);
            $values = self::valuesLower($values);
        }

        foreach ($values as $value)
        {
            foreach (array_keys($array,$value,true) as $key)
            {
                if(!in_array($key,$return,true))
                $return[] = $key;
            }
        }

        return $return;
    }


    // valueSlice
    // retourne toutes les slices contenant la valeur donnée
    // permet la recherche insensible à la case
    final public static function valueSlice($value,array $array,bool $sensitive=true):array
    {
        $return = [];

        foreach (self::valueKey($value,$array,$sensitive) as $key)
        {
            $return[$key] = $array[$key];
        }

        return $return;
    }


    // valuesSlice
    // retourne toutes les slices contenant les valeurs données
    // permet la recherche insensible à la case
    final public static function valuesSlice(array $values,array $array,bool $sensitive=true):array
    {
        $return = [];

        foreach (self::valuesKey($values,$array,$sensitive) as $key)
        {
            if(!array_key_exists($key,$return))
            $return[$key] = $array[$key];
        }

        return $return;
    }


    // valueStrip
    // retourne le tableau sans toutes les slices avec la valeur donnée
    // permet la recherche insensible à la case
    final public static function valueStrip($value,array $return,bool $sensitive=true):array
    {
        foreach (self::valueKey($value,$return,$sensitive) as $key)
        {
            unset($return[$key]);
        }

        return $return;
    }


    // valuesStrip
    // retourne le tableau sans toutes les slices avec les valeurs données
    // permet la recherche insensible à la case
    final public static function valuesStrip(array $values,array $return,bool $sensitive=true):array
    {
        foreach (self::valuesKey($values,$return,$sensitive) as $key)
        {
            if(array_key_exists($key,$return))
            unset($return[$key]);
        }

        return $return;
    }


    // valueNav
    // permet de naviguer à travers les valeurs du tableau via l'argument nav
    // si plusieurs valeurs identiques dans le tableau, la méthode prend le premier index
    // la navigation se fait par une addition donc 1 est à la prochaine clé et -1 est la précédente
    // retourne la nouvelle valeur ou null
    final public static function valueNav($value,int $nav,array $array)
    {
        $return = null;
        $indexes = self::valueIndex($value,$array);

        if(is_array($indexes) && !empty($indexes))
        {
            $index = current($indexes);
            $newIndex = self::indexNav($index,$nav,$array);

            if(is_int($newIndex))
            {
                $values = array_values($array);
                if(array_key_exists($newIndex,$values))
                $return = $values[$newIndex];
            }
        }

        return $return;
    }


    // valueRandom
    // retourne la valeur random d'un tableau
    final public static function valueRandom(array $array)
    {
        $return = null;
        $slice = self::random($array,1);

        if(!empty($slice))
        $return = current($slice);

        return $return;
    }


    // valuesChange
    // changement de valeur dans un tableau
    // sensible à la case
    // amount permet de spécifier combien de changements doivent être faire, en partant du début du tableau
    final public static function valuesChange($value,$change,array $return,?int $amount=null):array
    {
        $i = 0;
        foreach ($return as $k => $v)
        {
            if($v === $value)
            {
                $return[$k] = $change;
                $i++;

                if(is_int($amount) && $i >= $amount)
                break;
            }
        }

        return $return;
    }


    // valuesReplace
    // str_replace sur les valeurs du tableau
    final public static function valuesReplace(array $replace,array $return,bool $once=true,bool $sensitive=true):array
    {
        if(!empty($replace))
        {
            foreach ($return as $key => $value)
            {
                if(is_string($value))
                {
                    $v = Str::replace($replace,$value,$once,$sensitive);

                    if($value !== $v)
                    $return[$key] = $v;
                }
            }
        }

        return $return;
    }


    // valuesSearch
    // permet de faire la recherche d'un needle dans les valeurs scalaires d'un tableau
    // support pour recherche multiple si prepare est true et needle contient un +
    // retourne toutes les slices ou le needle est trouvé
    final public static function valuesSearch(string $needle,array $array,bool $sensitive=true,bool $accentSensitive=true,bool $prepare=false,?string $separator=null):array
    {
        $return = [];

        if(strlen($needle) && !empty($array))
        {
            if($prepare === true)
            {
                $needle = Str::prepareSearch($needle,$separator);
                $prepare = false;
            }

            foreach ($array as $key => $value)
            {
                if(is_scalar($value) && Str::search($needle,(string) $value,$sensitive,$accentSensitive,$prepare,$separator))
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // valuesSubReplace
    // fait un remplacement substring sur toutes les valeurs du tableau
    // si le tableau ne contient pas uniquemment des string, utilise validate map
    final public static function valuesSubReplace($offset,$length,$replace,array $return):array
    {
        if(self::validate('string',$return))
        $return = substr_replace($return,$replace,$offset,$length);

        else
        {
            $closure = fn($value) => (is_string($value))? substr_replace($value,$replace,$offset,$length):$value;
            $return = self::map($return,$closure);
        }

        return $return;
    }


    // valuesStart
    // retourne les slices des valeurs commençant par la chaîne
    final public static function valuesStart(string $str,array $array,bool $sensitive=true):array
    {
        return self::filter($array,fn($value) => (is_string($value) && Str::isStart($str,$value,$sensitive)));
    }


    // valuesEnd
    // retourne les slices des valeurs finissant par la chaîne
    final public static function valuesEnd(string $str,array $array,bool $sensitive=true):array
    {
        return self::filter($array,fn($value) => (is_string($value) && Str::isEnd($str,$value,$sensitive)));
    }


    // valuesChangeCase
    // change la case des valeurs string dans le tableau
    // case peut etre CASE_LOWER, CASE_UPPER ou callable
    // le changement de case se fait sur tous les sous-tableaux
    final public static function valuesChangeCase($case,array $return):array
    {
        if(in_array($case,[CASE_LOWER,'lower','strtolower'],true))
        $return = self::valuesLower($return);

        elseif(in_array($case,[CASE_UPPER,'upper','strtoupper'],true))
        $return = self::valuesUpper($return);

        elseif(Call::is($case))
        $return = Arrs::map($return,fn($value) => (is_string($value))? $case($value):$value);

        return $return;
    }


    // valuesLower
    // change la case des valeurs string dans le tableau pour lowercase
    // utilise multibyte
    // le changement de case se fait sur tous les sous-tableaux
    final public static function valuesLower(array $array):array
    {
        return Arrs::map($array,fn($value) => (is_string($value))? Str::lower($value,true):$value);
    }


    // valuesUpper
    // change la case des valeurs string dans le tableau pour uppercase
    // utilise multibyte
    // le changement de case se fait sur tous les sous-tableaux
    final public static function valuesUpper(array $array):array
    {
        return Arrs::map($array,fn($value) => (is_string($value))? Str::upper($value,true):$value);
    }


    // valuesSliceLength
    // retourne le tableau avec les entrées string ayant une longueur entre min et max
    // utilise multibyte
    // possible de garder les entrées numérique mais si elles n'ont pas la length
    final public static function valuesSliceLength(int $min,?int $max,array $array):array
    {
        $return = [];
        $max ??= PHP_INT_MAX;

        foreach ($array as $k => $v)
        {
            if(is_string($v))
            {
                $len = Str::len($v,true);

                if(Num::in($min,$len,$max))
                $return[$k] = $v;
            }
        }

        return $return;
    }


    // valuesStripLength
    // retourne le tableau sans les entrées string ayant une longueur entre min et max
    // enlève aussi les entrées non string
    // utilise multibyte
    final public static function valuesStripLength(int $min,?int $max,array $array):array
    {
        $return = [];
        $max ??= PHP_INT_MAX;

        foreach ($array as $k => $v)
        {
            if(is_string($v))
            {
                $len = Str::len($v,true);

                if(!Num::in($min,$len,$max))
                $return[$k] = $v;
            }
        }

        return $return;
    }


    // valuesTotalLength
    // retourne le tableau avec les entrées string rentrant dans une longueur totale
    // la première entrée pourrait être truncate si plus courte que length
    // utilise multibyte
    final public static function valuesTotalLength(int $length,array $array):array
    {
        $return = [];
        $inLength = 0;

        foreach ($array as $k => $v)
        {
            if(is_string($v))
            {
                $len = Str::len($v,true);

                if(empty($inLength) && $len >= $length)
                {
                    $return[$k] = Str::sub(0,$length,$v,true);
                    $inLength += $len + 1;
                }

                elseif(($inLength + $len) <= $length)
                {
                    $return[$k] = $v;
                    $inLength += $len + 1;
                }
            }
        }

        return $return;
    }


    // valuesWrap
    // permet de wrapper les valeurs scalar dans un delimiteur
    // il y a 7 modes, par défaut 0
    // mb sert seulement si le caractère a wrap est accenté minuscule vs majuscule
    final public static function valuesWrap(string $start,?string $end,array $return,int $mode=0,bool $sensitive=true):array
    {
        if($end === null)
        $end = $start;

        foreach ($return as $key => $value)
        {
            if(is_scalar($value))
            {
                $value = (string) $value;

                if($mode === 0)
                $value = $start.$value.$end;

                elseif($mode === 1)
                $value = Str::wrapStartOrEnd($start,$end,$value,$sensitive);

                elseif($mode === 2)
                $value = Str::wrapStartEnd($start,$end,$value,$sensitive);

                elseif($mode === 3)
                $value = $start.$value;

                elseif($mode === 4)
                $value = Str::wrapStart($start,$value,$sensitive);

                elseif($mode === 5)
                $value = $value.$end;

                elseif($mode === 6)
                $value = Str::wrapEnd($end,$value,$sensitive);

                $return[$key] = $value;
            }
        }

        return $return;
    }


    // valuesUnwrap
    // permet de unwrapper les valeurs string d'un délimiteur
    // il y a 4 modes, par défaut 0
    // mb sert seulement si le caractère a unwrap est accenté minuscule vs majuscule
    final public static function valuesUnwrap(string $start,?string $end,array $return,int $mode=0,bool $sensitive=true):array
    {
        if($end === null)
        $end = $start;

        foreach ($return as $key => $value)
        {
            if(is_string($value))
            {
                if($mode === 0)
                $value = Str::stripStartOrEnd($start,$end,$value,$sensitive);

                elseif($mode === 1)
                $value = Str::stripStartEnd($start,$end,$value,$sensitive);

                elseif($mode === 2)
                $value = Str::stripStart($start,$value,$sensitive);

                elseif($mode === 3)
                $value = Str::stripEnd($end,$value,$sensitive);

                $return[$key] = $value;
            }
        }

        return $return;
    }


    // valuesSort
    // sort un tableau par valeur
    // on peut mettre asc, true ou desc, false à sort (sort ou rsort)
    // les valeurs non scalar sont retirés et les clés ne sont pas conservés
    // renvoie à la méthode sort
    final public static function valuesSort(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
    {
        $return = self::filter($return,fn($value) => is_scalar($value));
        $ascDesc = self::getSortAscDesc($sort);

        if($ascDesc === 'asc')
        $sort = 'sort';

        elseif($ascDesc === 'desc')
        $sort = 'rsort';

        return self::sort($return,$sort,$type);
    }


    // valuesSortKeepAssoc
    // sort un tableau par valeur
    // on peut mettre asc, true ou desc, false à sort (asort ou arsort)
    // les valeurs non scalar sont retirés et les clés sont conservés
    // renvoie à la méthode sort
    final public static function valuesSortKeepAssoc(array $return,$sort=true,int $type=SORT_FLAG_CASE | SORT_NATURAL):array
    {
        $return = self::filter($return,fn($value) => is_scalar($value));
        $ascDesc = self::getSortAscDesc($sort);

        if($ascDesc === 'asc')
        $sort = 'asort';

        elseif($ascDesc === 'desc')
        $sort = 'arsort';

        return self::sort($return,$sort,$type);
    }


    // valuesExcerpt
    // permet de passer toutes les valeurs string du tableau dans la méthode str/excerpt ou html/excerpt
    // mb est true par défaut
    final public static function valuesExcerpt(?int $length,array $array,bool $html=false,?array $option=null):array
    {
        $return = [];
        $option = self::plus(['mb'=>true],$option);
        $callable = ($html === true)? [Html::class,'excerpt']:[Str::class,'excerpt'];

        foreach ($array as $key => $value)
        {
            if(is_scalar($value) && !is_bool($value))
            {
                $value = (string) $value;
                $return[$key] = $callable($length,$value,$option);
            }
        }

        return $return;
    }


    // keysValuesLower
    // change la case des valeurs et clés string dans le tableau pour lowercase
    // valeur mb seulement pour keysLower, values utilise mb
    final public static function keysValuesLower(array $return,?bool $mb=null):array
    {
        $return = self::keysLower($return,$mb);
        return self::valuesLower($return);
    }


    // keysValuesUpper
    // change la case des valeurs et clés string dans le tableau pour uppercase
    // valeur mb seulement pour keysUpper, values utilise mb
    final public static function keysValuesUpper(array $return,?bool $mb=null):array
    {
        $return = self::keysUpper($return,$mb);
        return self::valuesUpper($return);
    }


    // keysStrToArrs
    // permet de reformater un tableau assoc
    // toutes les entrées avec clés string sont transformés en array(key,...value)
    // retourne un tableau multidimensionnel séquentielle
    final public static function keysStrToArrs(array $array):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_string($key))
            $return[] = self::merge($key,$value);

            else
            $return[] = $value;
        }

        return $return;
    }


    // camelCaseParent
    // prend un tableau contenant des string camelCase et identifie le premier parent de chacun
    // retourne un tableau clé->valeur, les clés sont parents ont la valeur null mais sont retournés quand même
    final public static function camelCaseParent(array $array):array
    {
        $return = [];
        $camelCase = [];

        foreach ($array as $value)
        {
            if(is_string($value))
            $camelCase[$value] = Str::explodeCamelCase($value);
        }

        $copy = $camelCase;
        foreach ($camelCase as $key => $value)
        {
            $count = count($value);

            if($count === 1)
            $return[$key] = null;

            else
            {
                $splice = $value;
                while ($splice = self::spliceLast($splice))
                {
                    foreach ($copy as $k => $v)
                    {
                        if($key !== $k && $splice === $v)
                        {
                            $return[$key] = $k;
                            break 2;
                        }
                    }
                }

                if(empty($return[$key]))
                $return[$key] = null;
            }
        }

        return $return;
    }


    // combination
    // retourne toutes les combinaisons possibles des slices d'un tableau
    // retourne un tableau multidimensionnel
    final public static function combination(array $array,?bool $sort=true):array
    {
        $return = [[]];

        foreach (array_reverse($array) as $v)
        {
            foreach ($return as $combination)
            {
                $merge = array_merge([$v],$combination);

                if(!empty($merge))
                array_push($return,$merge);
            }
        }

        unset($return[0]);
        $return = array_values($return);

        if($sort !== null)
        $return = Column::sortByLength($return,$sort);

        return $return;
    }


    // methodSort
    // permet de faire un sort su un tableau unidimensionnel contenant des noms de classes ou des objets
    // le type doit être obj ou classe, ou possible de passer une closure
    final public static function methodSort($method,$sort=true,array $return,...$args):array
    {
        uasort($return, function($first,$second) use ($method,$sort,$args)
        {
            $return = 0;
            $a = 0;
            $b = 0;
            $sort = self::getSortAscDesc($sort);

            if(is_string($method) && is_object($first) && is_object($second))
            {
                $a = $first->$method(...$args);
                $b = $second->$method(...$args);
            }

            elseif(is_string($method) && is_string($first) && is_string($second))
            {
                $a = $first::$method(...$args);
                $b = $second::$method(...$args);
            }

            elseif($method instanceof \Closure)
            {
                $a = $method($first,...$args);
                $b = $method($second,...$args);
            }

            if($sort === 'asc')
            {
                if($a < $b)
                $return = -1;

                elseif($a > $b)
                $return = 1;
            }

            elseif($sort === 'desc')
            {
                if($a < $b)
                $return = 1;

                elseif($a > $b)
                $return = -1;
            }

            return $return;
        });

        return $return;
    }


    // methodSorts
    // permet de faire plusieurs sorts su un tableau unidimensionnel contenant des noms de classes ou des objets
    // le type doit être obj ou classe, il est aussi possible de passer des closures
    final public static function methodSorts(array $sorts,array $return):array
    {
        uasort($return, function($first,$second) use ($sorts)
        {
            $return = 0;
            $a = 0;
            $b = 0;

            foreach ($sorts as $array)
            {
                if(is_array($array) && count($array) >= 2)
                {
                    $array = array_values($array);
                    $method = $array[0];
                    $sort = self::getSortAscDesc($array[1]);
                    $args = (array_key_exists(2,$array))? $array[2]:[];
                    if(!is_array($args))
                    $args = [$args];

                    if(is_string($method) && is_object($first) && is_object($second))
                    {
                        $a = $first->$method(...$args);
                        $b = $second->$method(...$args);
                    }

                    elseif(is_string($method) && is_string($first) && is_string($second))
                    {
                        $a = $first::$method(...$args);
                        $b = $second::$method(...$args);
                    }

                    elseif($method instanceof \Closure)
                    {
                        $a = $method($first,...$args);
                        $b = $method($second,...$args);
                    }

                    // asc
                    if($sort === 'asc')
                    {
                        if($a < $b)
                        $return = -1;

                        elseif($a > $b)
                        $return = 1;
                    }

                    // desc
                    elseif($sort === 'desc')
                    {
                        if($a < $b)
                        $return = 1;

                        elseif($a > $b)
                        $return = -1;
                    }

                    if($return !== 0)
                    break;
                }
            }

            return $return;
        });

        return $return;
    }
}
?>