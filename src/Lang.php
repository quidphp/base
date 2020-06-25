<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// lang
// class to manage language text and translations
final class Lang extends Root
{
    // config
    protected static array $config = [
        'default'=>'en', // langue par défaut à appliquer au chargement de la classe
        'field'=>'_', // délimiteur pour les méthodes field
    ];


    // static
    protected static ?string $current = null; // langue courante
    protected static array $all = []; // toute les langues, la première est la langue par défaut
    protected static $callable; // callable d'un objet lang


    // is
    // retourne vrai si la langue est valide
    final public static function is($value):bool
    {
        $return = false;
        $value = self::prepareCode($value);

        if(!empty($value) && in_array($value,self::all(),true))
        $return = true;

        return $return;
    }


    // isCurrent
    // retourne vrai si la langue courante est la valeur
    final public static function isCurrent($value):bool
    {
        return self::prepareCode($value) === self::$current;
    }


    // isOther
    // retourne vrai si la langue est valide, et n'est pas la langue courante
    final public static function isOther($value):bool
    {
        return self::is($value) && !self::isCurrent($value);
    }


    // hasCallable
    // retourne vrai s'il y a une callable lang
    final public static function hasCallable():bool
    {
        return !empty(self::$callable);
    }


    // current
    // retourne la langue courante
    final public static function current():string
    {
        return self::$current;
    }


    // default
    // retourne la langue par défaut, la première déclaré dans le tableau all
    final public static function default():?string
    {
        return (!empty(self::$all))? current(self::$all):null;
    }


    // defaultConfig
    // retourne la langue par defaut de config
    final public static function defaultConfig():string
    {
        return self::$config['default'];
    }


    // other
    // retourne une langue autre par index ou string
    // ne peut pas retourner la courante
    final public static function other($arg=0,?string $value=null):?string
    {
        $return = null;
        $others = self::others($value);
        $arg = ($arg === true)? 0:$arg;

        if(is_int($arg) && array_key_exists($arg,$others))
        $return = $others[$arg];

        elseif(is_string($arg) && in_array($arg,$others,true))
        $return = $arg;

        return $return;
    }


    // others
    // retourne un tableau avec toutes les autres langues
    final public static function others(?string $value=null):array
    {
        $return = [];
        $value = self::code($value);
        $return = Arr::valueStrip($value,self::all());
        $return = array_values($return);

        return $return;
    }


    // all
    // retourne toutes les langues
    final public static function all():array
    {
        return self::$all;
    }


    // count
    // count le nombre de langues déclarés
    final public static function count():int
    {
        return count(self::all());
    }


    // code
    // retourne le code formatté ou la langue courante si le code formatté est invalide
    final public static function code(?string $value=null):string
    {
        $return = self::prepareCode($value);

        if(!is_string($return))
        $return = self::current();

        return $return;
    }


    // prepareCode
    // retourne le code de langue formatté ou null
    // doit être une string avec deux caractères
    final public static function prepareCode(?string $value):?string
    {
        return (is_string($value) && strlen($value) === 2)? $value:null;
    }


    // set
    // permet d'ajouter les langues et de changer la langue courante
    // vide le tableau des langues courantes avant de faire l'ajout et changement
    final public static function set(?string $value,$all):bool
    {
        $return = false;
        $current = self::prepareCode($value);

        if($all === true)
        $all = self::defaultConfig();

        if(is_string($all))
        $all = [$all];

        if(is_array($all) && !empty($all) && ($value === null || in_array($value,$all,true)))
        {
            $return = true;
            self::$all = [];
            self::add(...array_values($all));

            if($value === null)
            $value = self::default();

            self::change($value);
        }

        return $return;
    }


    // onChange
    // callback après un ajout, rettait ou changement de langue
    final protected static function onChange():void
    {
        if(is_string(self::$current))
        {
            $current = self::current();
            Request::setLangs(self::all());
            Finder::setShortcut('lang',$current);
            Uri::setShortcut('lang',$current);
        }
    }


    // add
    // ajoute une ou plusieurs langues
    final public static function add(string ...$values):array
    {
        $return = [];
        $change = false;

        foreach ($values as $value)
        {
            $value = self::prepareCode($value);

            if(is_string($value))
            {
                $return[$value] = false;

                if(!self::is($value))
                {
                    $return[$value] = true;
                    self::$all[] = $value;
                    self::$all = array_values(self::$all);
                    $change = true;
                }
            }
        }

        if($change === true)
        self::onChange();

        return $return;
    }


    // remove
    // enlève une ou plusieurs langues
    // la langue doit exister et ne pas être la courante
    final public static function remove(string ...$values):array
    {
        $return = [];
        $change = false;

        foreach ($values as $value)
        {
            $value = self::prepareCode($value);

            if(is_string($value))
            {
                $return[$value] = false;

                if(self::is($value) && !self::isCurrent($value))
                {
                    $return[$value] = true;
                    self::$all = array_values(Arr::valueStrip($value,self::$all));
                    $change = true;
                }
            }
        }

        if($change === true)
        self::onChange();

        return $return;
    }


    // change
    // change la langue courante si la nouvelle lang existe
    // callback déclenché s'il y a un changement
    final public static function change(string $value):bool
    {
        $return = false;
        $value = self::prepareCode($value);
        $current = self::$current;

        if(self::is($value))
        {
            $return = true;

            if($value !== $current)
            {
                self::$current = $value;
                self::onChange();
            }
        }

        return $return;
    }


    // getCallable
    // retourne la callable lang liée
    final public static function getCallable():?callable
    {
        return self::$callable;
    }


    // setCallable
    // lie une callable lang à la classe
    final public static function setCallable(callable $callable):void
    {
        self::$callable = $callable;
    }


    // unsetCallable
    // délie la callable de la classe
    final public static function unsetCallable():void
    {
        self::$callable = null;
    }


    // call
    // utilise la callable lié pour faire une requête de contenu langue
    // aucun envoie d'erreur si contenu inexistant
    final public static function call(string $value,...$args)
    {
        $return = null;
        $callable = self::getCallable();

        if(!empty($callable))
        $return = $callable($value,...$args);

        return $return;
    }


    // numberFormat
    // retourne le tableau format numérique de la langue, si callable lié
    final public static function numberFormat(...$args)
    {
        return self::call('numberFormat',...$args);
    }


    // numberPercentFormat
    // retourne le tableau format numérique en pourcentage de la langue, si callable lié
    final public static function numberPercentFormat(...$args)
    {
        return self::call('numberPercentFormat',...$args);
    }


    // numberMoneyFormat
    // retourne le tableau format monétaire de la langue, si callable lié
    final public static function numberMoneyFormat(...$args)
    {
        return self::call('numberMoneyFormat',...$args);
    }


    // numberPhoneFormat
    // retourne le tableau format de size de phone, si callable lié
    final public static function numberPhoneFormat(...$args)
    {
        return self::call('numberPhoneFormat',...$args);
    }


    // numberSizeFormat
    // retourne le tableau format de size de la langue, si callable lié
    final public static function numberSizeFormat(...$args)
    {
        return self::call('numberSizeFormat',...$args);
    }


    // dateLocale
    // retourne la locale pour la date
    final public static function dateLocale(...$args)
    {
        return self::call('dateLocale',...$args);
    }


    // dateMonth
    // retourne le tableau des mois, si callable lié
    final public static function dateMonth(...$args)
    {
        return self::call('dateMonth',...$args);
    }


    // dateFormat
    // retourne le tableau des formats de date, si callable lié
    final public static function dateFormat(...$args)
    {
        return self::call('dateFormat',...$args);
    }


    // dateStr
    // retourne le tableau pour date str, si callable lié
    final public static function dateStr(...$args)
    {
        return self::call('dateStr',...$args);
    }


    // datePlaceholder
    // retourne le tableau pour date placeholder, si callable lié
    final public static function datePlaceholder(...$args)
    {
        return self::call('datePlaceholder',...$args);
    }


    // dateDay
    // retourne le tableau pour date day, si callable lié
    final public static function dateDay(...$args)
    {
        return self::call('dateDay',...$args);
    }


    // dateDayShort
    // retourne le tableau pour date dayShort, si callable lié
    final public static function dateDayShort(...$args)
    {
        return self::call('dateDayShort',...$args);
    }


    // headerResponseStatus
    // retourne le tableau headerResponseStatus, si callable lié
    final public static function headerResponseStatus(...$args)
    {
        return self::call('headerResponseStatus',...$args);
    }


    // errorCode
    // retourne le tableau pour error codes, si callable lié
    final public static function errorCode(...$args)
    {
        return self::call('errorCode',...$args);
    }


    // validate
    // retourne le tableau pour validate, si callable lié
    final public static function validate(...$args)
    {
        return self::call('validate',...$args);
    }


    // compare
    // retourne le tableau pour compare, si callable lié
    final public static function compare(...$args)
    {
        return self::call('compare',...$args);
    }


    // required
    // retourne le tableau pour required, si callable lié
    final public static function required(...$args)
    {
        return self::call('required',...$args);
    }


    // unique
    // retourne le tableau pour unique, si callable lié
    final public static function unique(...$args)
    {
        return self::call('unique',...$args);
    }


    // editable
    // retourne le tableau pour editable, si callable lié
    final public static function editable(...$args)
    {
        return self::call('editable',...$args);
    }


    // content
    // retourne le contenu en vue d'un overwrite ou replace
    // si le tableau est unidimensionnel, il est passé dans arrs::sets avant le retour
    final public static function content($value)
    {
        $return = null;

        if(is_array($value))
        $return = $value;

        elseif(is_string($value))
        $return = File::load($value);

        if(Arr::isUni($return))
        $return = Arrs::sets($return,[]);

        return $return;
    }


    // field
    // retourne une valeur dans le format: valeur delimiteur lang
    final public static function field(string $value,?string $lang=null,?string $delimiter=null):?string
    {
        $return = null;
        $lang = self::code($lang);
        $delimiter = (is_string($delimiter))? $delimiter:self::$config['field'];

        if(strlen($value) && !empty($lang) && is_string($delimiter) && strlen($delimiter))
        $return = $value.$delimiter.$lang;

        return $return;
    }


    // arr
    // retourne une clé de champ dans un tableau après avoir formatté la valeur via la méthode field
    final public static function arr(string $value,array $array,?string $lang=null,?string $delimiter=null)
    {
        $return = null;
        $field = self::field($value,$lang,$delimiter);

        if(is_string($field) && array_key_exists($field,$array))
        $return = $array[$field];

        return $return;
    }


    // arrs
    // retourne une valeur de champ dans un tableau multidimensionnel après avoir formatté la valeur via la méthode field
    // retourne un array multi de type crush
    final public static function arrs(string $value,array $array,?string $lang=null,?string $delimiter=null):?array
    {
        $return = null;
        $field = self::field($value,$lang,$delimiter);

        if(is_string($field))
        $return = Arrs::keyValues($field,$array);

        return $return;
    }


    // reformat
    // cette méthode garde les clés finissant par _lang et retire la lang du nom de la clé
    // la méthode enlève les clés finissant par _autrelangs
    // accepte un tableau unidimensionnel seulement
    final public static function reformat(array $array,?string $lang=null,?string $delimiter=null)
    {
        $return = [];
        $lang = self::code($lang);
        $others = self::others();
        $delimiter = (is_string($delimiter))? $delimiter:self::$config['field'];
        $not = [];

        if(!empty($lang) && !empty($others) && strlen($delimiter) && !empty($array))
        {
            foreach ($array as $key => $value)
            {
                $keep = true;

                if(is_string($key))
                {
                    if(in_array($key,$not,true))
                    $keep = false;

                    elseif(Str::isEnd($delimiter.$lang,$key))
                    {
                        $key = Str::stripEnd($delimiter.$lang,$key);
                        $not[] = $key;
                    }

                    else
                    {
                        foreach ($others as $l)
                        {
                            if(Str::isEnd($delimiter.$l,$key))
                            $keep = false;
                        }
                    }
                }

                if($keep === true)
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // reformatColumn
    // cette méthode garde les clés finissant par _lang et retire la lang du nom de la clé
    // la méthode enlève les clés finissant par _autrelangs
    // accepte seulement un tableau column
    final public static function reformatColumn(array $array,?string $lang=null,?string $delimiter=null)
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_array($value))
            $return[$key] = self::reformat($value,$lang,$delimiter);
        }

        return $return;
    }
}

// set
Lang::set(null,true);
?>