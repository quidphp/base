<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// set
// class with static methods to deal with set strings (test, test2)
class Set extends Root
{
    // trait
    use _option;


    // config
    public static $config = [
        'option'=>[ // tableau d'options
            'implode'=>0, // index du séparateur à utiliser lors du implode
            'explode'=>0, // index du séparateur à utiliser lors du explode
            'case'=>null, // les valeurs sont ramenés dans cette case lors du explode
            'caseImplode'=>null, // les valeurs sont ramenés dans cette case lors du implode
            'cast'=>null, // cast lors de l'explosion
            'limit'=>null, // limit par défaut lors du explode
            'trim'=>true, // chaque partie du set est trim
            'clean'=>true, // une partie du set vide est retiré
            'start'=>false, // ajoute le séparateur au début lors du implode
            'end'=>false, // ajoute le séparateur à la fin lors du implode
            'sort'=>null], // les valeurs sont sort
        'separator'=>[',',', '], // séparateur du set
        'sensitive'=>true // la classe est sensible ou non à la case
    ];


    // isSeparatorStart
    // retourne vrai si le set a un separator au début
    public static function isSeparatorStart(string $set):bool
    {
        return Str::isStart(static::getSeparator(),$set,static::getSensitive());
    }


    // isSeparatorEnd
    // retourne vrai si le set a un separator à la fin
    public static function isSeparatorEnd(string $set):bool
    {
        return ($set !== ($separator = static::getSeparator()) && Str::isEnd($separator,$set,static::getSensitive()))? true:false;
    }


    // hasSeparatorDouble
    // retourne vrai si le set contient un double séparateur
    public static function hasSeparatorDouble(string $set):bool
    {
        return (!empty($separator = static::getSeparator()) && Str::posIpos($separator.$separator,$set,static::getSensitive()) !== null)? true:false;
    }


    // hasSegment
    // retourne vrai si le set contient un segment
    public static function hasSegment(string $set):bool
    {
        return Segment::has(null,$set);
    }


    // exist
    // retourne vrai si l'index existe dans le set
    public static function exist(int $index,$set,?array $option=null):bool
    {
        return Arr::indexExists($index,static::arr($set,$option));
    }


    // exists
    // retourne vrai si les index existent dans le set
    public static function exists(array $indexes,$set,?array $option=null):bool
    {
        return Arr::indexesExists($indexes,static::arr($set,$option));
    }


    // in
    // retourne vrai si la valeur est dans le set
    public static function in(string $value,$set,?array $option=null):bool
    {
        return Arr::in($value,static::arr($set,$option),static::getSensitive());
    }


    // ins
    // retourne vrai si les valeurs sont dans le set
    public static function ins(array $values,$set,?array $option=null):bool
    {
        return Arr::ins($values,static::arr($set,$option),static::getSensitive());
    }


    // isCount
    // retourne vrai si le count du set est égal à la valeur donné
    public static function isCount(int $count,$set,?array $option=null):bool
    {
        return Arr::isCount($count,static::arr($set,$option));
    }


    // isMinCount
    // retourne vrai si le count du set est plus grand ou égal que celui spécifié
    public static function isMinCount(int $count,$set,?array $option=null):bool
    {
        return Arr::isMinCount($count,static::arr($set,$option));
    }


    // isMaxCount
    // retourne vrai si le count du set est plus petit ou égal que celui spécifié
    public static function isMaxCount(int $count,$set,?array $option=null):bool
    {
        return Arr::isMaxCount($count,static::arr($set,$option));
    }


    // sameCount
    // retourne vrai si les set ont tous le même count
    public static function sameCount(...$values):bool
    {
        $return = false;
        $count = null;

        if(!empty($values))
        {
            $return = true;

            foreach ($values as $value)
            {
                $value = static::arr($value);

                if($count === null)
                $count = count($value);

                elseif(count($value) !== $count)
                {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }


    // sameWithSegments
    // retourne vrai si le pattern et la string sont identiques en ignorant les segments
    // plus rapide que sameWithReplaceSegments
    public static function sameWithSegments($pattern,$set,?array $option=null):bool
    {
        $return = false;

        if(is_string($pattern) && is_string($set) && strlen($set))
        {
            $pattern = static::stripWrap($pattern,false,false);
            $pattern = static::arr($pattern,$option);
            $set = static::stripWrap($set,false,false);
            $set = static::arr($set,$option);

            if(count($pattern) === count($set))
            {
                foreach ($pattern as $key => $value)
                {
                    if(Segment::isWrapped(null,$value))
                    $pattern[$key] = $set[$key];
                }

                if($pattern === $set)
                $return = true;
            }
        }

        return $return;
    }


    // getSeparator
    // retourne le delimiter, par défaut ,
    // comme cette classe peut être étendu, le délimiteur par défaut peut varier
    // possiblité de retourner la version avec espace
    public static function getSeparator(int $index=0):string
    {
        $return = null;

        if(array_key_exists($index,static::$config['separator']) && is_string(static::$config['separator'][$index]))
        $return = static::$config['separator'][$index];

        return $return;
    }


    // getSensitive
    // retourne si la classe est sensible à la case
    public static function getSensitive():bool
    {
        return static::$config['sensitive'];
    }


    // prepend
    // ajoute des sets un en arrière de l'autre
    // le delimiteur est celui par défaut de la classe
    // input string ou array
    public static function prepend(...$values):string
    {
        return static::append(...array_reverse($values));
    }


    // append
    // ajoute des sets un après l'autre
    // le delimiteur est celui par défaut de la classe
    // input string ou array
    // si start ou end sont null, essaie de déterminer s'il y a un séparateur dans le premier et dernier argument
    public static function append(...$values):string
    {
        $return = '';
        $array = [];

        if(!empty($values))
        {
            $option = static::option();
            $first = Arr::valueFirst($values);
            $last = Arr::valueLast($values);

            if(is_string($first) && static::getOption('start') === null)
            $option['start'] = static::isSeparatorStart($first);

            if(is_string($last) && static::getOption('end') === null)
            $option['end'] = static::isSeparatorEnd($last);

            foreach ($values as $k => $value)
            {
                $value = static::arr($value,$option);

                if(is_array($value))
                $array = Arr::append($array,$value);
            }

            $return = static::str($array,$option);
        }

        return $return;
    }


    // count
    // count le nombre d'éléments dans le set
    public static function count($set,?array $option=null):int
    {
        return count(static::arr($set,$option));
    }


    // get
    // retourne un index de l'explosion du set ou null si n'existe pas
    public static function get(int $index,$set,?array $option=null):?string
    {
        return Arr::index($index,static::arr($set,$option),static::getSensitive());
    }


    // gets
    // retourne un tableau des index existants dans le set
    public static function gets(array $indexes,$set,?array $option=null):array
    {
        return Arr::indexes($indexes,static::arr($set,$option),static::getSensitive());
    }


    // set
    // change une slice du set via index
    public static function set(int $index,$value,$set,?array $option=null):string
    {
        return static::str(Arr::set($index,$value,array_values(static::arr($set,$option)),static::getSensitive()),$option);
    }


    // sets
    // change plusieurs slices du set via index
    public static function sets(array $values,$set,?array $option=null):string
    {
        return static::str(Arr::sets($values,array_values(static::arr($set,$option)),static::getSensitive()),$option);
    }


    // unset
    // enlève une slice du set via index
    public static function unset(int $index,$set,?array $option=null):string
    {
        return static::str(Arr::indexStrip($index,static::arr($set,$option),static::getSensitive()),$option);
    }


    // unsets
    // enlève plusieurs slices du set via index
    public static function unsets(array $indexes,$set,?array $option=null):string
    {
        return static::str(Arr::indexesStrip($indexes,static::arr($set,$option),static::getSensitive()),$option);
    }


    // slice
    // tranche des slices d'un set en utilisant offset et length
    public static function slice(int $offset,?int $length,$set,?array $option=null):array
    {
        return Arr::sliceIndex($offset,$length,static::arr($set,$option),static::getSensitive());
    }


    // splice
    // efface et remplace des slices d'un set en utilisant offset et length
    public static function splice(int $offset,?int $length,$set,$replace=null,?array $option=null):string
    {
        return static::str(Arr::spliceIndex($offset,$length,static::arr($set,$option),static::arr($replace,$option),static::getSensitive()),$option);
    }


    // spliceFirst
    // efface et remplace la première slice d'un set
    public static function spliceFirst($set,$replace=null,?array $option=null):string
    {
        return static::str(Arr::spliceFirst(static::arr($set,$option),static::arr($replace,$option),static::getSensitive()),$option);
    }


    // spliceLast
    // efface et remplace la dernière slice d'un set
    public static function spliceLast($set,$replace=null,?array $option=null):string
    {
        return static::str(Arr::spliceLast(static::arr($set,$option),static::arr($replace,$option),static::getSensitive()),$option);
    }


    // insert
    // ajoute un élément dans le set sans ne rien effacer
    public static function insert(int $offset,$replace,$set,?array $option=null):string
    {
        return static::str(Arr::insertIndex($offset,static::arr($replace,$option),static::arr($set,$option),static::getSensitive()),$option);
    }


    // str
    // explose et implose une valeur
    // retourne une string correctement formattée
    // si start ou end sont null, essaie de déterminer s'il y a un séparateur dans la valeur
    public static function str($value,?array $option=null):string
    {
        $return = '';
        $option = static::option($option);

        if(is_string($value))
        {
            if(array_key_exists('start',$option) && $option['start'] === null)
            $option['start'] = static::isSeparatorStart($value);

            if(array_key_exists('end',$option) && $option['end'] === null)
            $option['end'] = static::isSeparatorEnd($value);
        }

        $return = static::implode(static::arr($value,$option),$option);

        return $return;
    }


    // parse
    // parse un tableau arr
    // pas utilisé dans set
    public static function parse(array $return,array $option):array
    {
        return $return;
    }


    // arr
    // explose un set selon un delimiter
    // de même si set est déjà un array, retourne le simplement
    public static function arr($value,?array $option=null):array
    {
        $return = [];
        $option = static::option($option);
        $value = Obj::cast($value);

        if(is_array($value))
        $value = static::prepareArr($value,$option);

        if(is_scalar($value))
        {
            $value = Str::cast($value);
            $value = static::prepareStr($value,$option);
        }

        if(is_array($value) && !empty($value))
        {
            if($option['case'] !== null)
            $value = Arr::valuesChangeCase($option['case'],$value);

            $return = static::parse($value,$option);

            if($option['sort'] !== null)
            $return = Arr::valuesSort($return,$option['sort']);

            if(array_key_exists('cast',$option) && $option['cast'] === true)
            $return = Arr::cast($return);
        }

        return $return;
    }


    // prepareStr
    // prépare une string dans la méthode arr
    public static function prepareStr(string $value,array $option):array
    {
        $return = [];
        $separator = static::getSeparator($option['explode']);
        $return = Str::explode($separator,$value,$option['limit'],$option['trim'],$option['clean']);

        return $return;
    }


    // prepareArr
    // prépare un array dans la méthode arr
    public static function prepareArr(array $value,array $option):array
    {
        $return = [];
        $separator = static::getSeparator($option['explode']);
        $return = Arr::explode($separator,$value,$option['limit'],$option['trim'],$option['clean']);

        return $return;
    }


    // implode
    // implose un tableau dans une string set
    public static function implode(array $value,?array $option=null):string
    {
        $return = '';
        $option = static::option($option);

        if(Arr::isIndexed($value))
        {
            if($option['caseImplode'] !== null)
            $value = Arr::valuesChangeCase($option['caseImplode'],$value);

            $return = implode(static::getSeparator($option['implode']),$value);
            $return = static::stripWrap($return,$option['start'],$option['end']);
        }

        return $return;
    }


    // first
    // retourne la première valeur du set
    public static function first($set,?array $option=null):?string
    {
        return Arr::index(0,static::arr($set,$option));
    }


    // last
    // retourne la dernière valeur du set
    public static function last($set,?array $option=null):?string
    {
        return Arr::index(-1,static::arr($set,$option));
    }


    // valueIndex
    // retourne tous les index du set contenant la valeur donnée
    public static function valueIndex(string $value,$set,?array $option=null):array
    {
        return Arr::valueIndex($value,static::arr($set,$option),static::getSensitive());
    }


    // valuesIndex
    // retourne tous les index du set contenant les valeurs données
    public static function valuesIndex(array $values,$set,?array $option=null):array
    {
        return Arr::valuesIndex($values,static::arr($set,$option),static::getSensitive());
    }


    // valueSlice
    // retourne toutes les slices du set contenant la valeur donnée
    public static function valueSlice(string $value,$set,?array $option=null):array
    {
        return Arr::valueSlice($value,static::arr($set,$option),static::getSensitive());
    }


    // valuesSlice
    // retourne toutes les slices du set contenant les valeurs données
    public static function valuesSlice(array $values,$set,?array $option=null):array
    {
        return Arr::valuesSlice($values,static::arr($set,$option),static::getSensitive());
    }


    // valueStrip
    // retourne le set sans toutes les slices avec la valeur donnée
    public static function valueStrip(string $value,$set,?array $option=null):string
    {
        return static::str(Arr::valueStrip($value,static::arr($set,$option),static::getSensitive()));
    }


    // valuesStrip
    // retourne le set sans toutes les slices avec les valeurs données
    public static function valuesStrip(array $values,$set,?array $option=null):string
    {
        return static::str(Arr::valuesStrip($values,static::arr($set,$option),static::getSensitive()),$option);
    }


    // valuesChange
    // changement de valeur dans un set
    public static function valuesChange(string $value,string $change,$set,?int $amount=null,?array $option=null):string
    {
        return static::str(Arr::valuesChange($value,$change,static::arr($set,$option),$amount),$option);
    }


    // valuesReplace
    // str_replace sur les valeurs du set
    public static function valuesReplace(array $replace,$set,?array $option=null):string
    {
        return static::str(Arr::valuesReplace($replace,static::arr($set,$option),static::getSensitive()),$option);
    }


    // sliceLength
    // retourne le set avec les entrées ayant une longueur entre min et max
    public static function sliceLength(int $min,?int $max,$set,?array $option=null):string
    {
        return static::str(Arr::valuesSliceLength($min,$max,static::arr($set,$option)),$option);
    }


    // stripLength
    // retourne le set sans les entrées ayant une longueur entre min et max
    public static function stripLength(int $min,?int $max,$set,?array $option=null):string
    {
        return static::str(Arr::valuesStripLength($min,$max,static::arr($set,$option)),$option);
    }


    // totalLength
    // retourne le set avec les entrées rentrant dans une longueur totale
    // va retourne une entrée truncate si la premier entrée est plus courte que length
    public static function totalLength(int $length,$set,?array $option=null):string
    {
        return static::str(Arr::valuesTotalLength($length,static::arr($set,$option)),$option);
    }


    // getSegments
    // retourne tous les segments dans set, tel que décrit dans pattern
    // retourne null si les deux string ne sont pas compatibles
    public static function getSegments(string $pattern,string $set,?array $option=null):?array
    {
        $return = null;
        $pattern = static::stripWrap($pattern,false,false);
        $pattern = static::arr($pattern,$option);
        $set = static::stripWrap($set,false,false);
        $set = static::arr($set,$option);

        if(count($pattern) === count($set))
        {
            $return = [];

            foreach ($pattern as $key => $value)
            {
                if(Segment::isWrapped(null,$value))
                {
                    $value = Segment::strip(null,$value);
                    $return[$value] = $set[$key];
                }
            }
        }

        return $return;
    }


    // stripWrap
    // ajoute ou enlève le séparateur en début ou fin de chaîne
    public static function stripWrap(string $set,?bool $start=null,?bool $end=null):string
    {
        return Str::stripWrap(static::getSeparator(),$set,$start,$end,static::getSensitive());
    }


    // stripStart
    // retourne le set sans le séparateur du début
    public static function stripStart(string $set):string
    {
        return Str::stripStart(static::getSeparator(),$set,static::getSensitive());
    }


    // stripEnd
    // retourne le set sans le séparateur de la fin
    public static function stripEnd(string $set):string
    {
        return Str::stripEnd(static::getSeparator(),$set,static::getSensitive());
    }


    // wrapStart
    // wrap un set au début s'il ne l'est pas déjà
    public static function wrapStart(string $set):string
    {
        return Str::wrapStart(static::getSeparator(),$set,static::getSensitive());
    }


    // wrapEnd
    // wrap un set à la fin s'il ne l'est pas déjà
    public static function wrapEnd(string $set):string
    {
        return Str::wrapEnd(static::getSeparator(),$set,static::getSensitive());
    }


    // onSet
    // helper pour une méthode onSet de colonne
    // fait un set si array
    public static function onSet($return)
    {
        if(is_array($return))
        $return = static::str($return);

        return $return;
    }


    // onGet
    // helper pour une méthode onGet de colonne
    // explose le set si scalar, utilise option cast
    public static function onGet($return)
    {
        if(is_scalar($return))
        $return = static::arr($return,['cast'=>true]);

        return $return;
    }
}
?>