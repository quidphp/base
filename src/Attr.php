<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Base;

// attr
// class with static methods to generate HTML attributes
class Attr extends Listing
{
    // config
    public static $config = [
        'option'=>[ // tableau d'options
            'explode'=>1, // index du séparateur à utiliser lors du explode
            'trim'=>false, // chaque partie de attr est trim
            'clean'=>false, // une partie attr vide est retiré
            'alt'=>null, // option pour slug de alt
            'style'=>null, // option pour style
            'json'=>null, // option pour json
            'uri'=>null, // option pour uri, html passe un tableau différent pour chaque tag
            'encode'=>true, // si les attributs sont passés dans htmlspecialchars ou htmlentities lors du implode, true est specialchars
            'quote'=>false, // null est pas de quote, false est single quote, true est double quote
            'href'=>[
                'active'=>true, // permet d'activer ou désactiver les options href selected, targetExternal et lang
                'selected'=>'selected', // classe pour les selected href
                'targetExternal'=>'_blank', // target si le href est externe
                'lang'=>true, // détecte la lang pour href
                'mailto'=>true, // détecte les courriels
                'extension'=>null], // extension par défaut pour href si non fourni et uri relative
            'src'=>[
                'extension'=>'jpg'], // extension par défaut pour src si non fourni et uri relative
            'action'=>[
                'extension'=>null]], // extension par défaut pour action si non fourni et uri relative
        'separator'=>[ // les séparateurs de attr, le troisième index est pour explode
            [' ',' '],
            ['=','=']],
        'id'=>'#', // ce caractère sert à identifier un id
        'randomId'=>[10], // argument par défaut pour randomId
        'class'=>'.', // ce caractère sert à identifier une classe
        'oddEven'=>['odd','even'], // les classes pour oddEven
        'mirror'=>['multiple','selected','checked','required','disabled'], // attributs mirror
        'pattern'=>['pattern','data-pattern'], // noms d'attributs qui contiennent pattern et doivent être passés dans validate/pattern
        'method'=>'post', // si method est true
        'target'=>'_blank', // si target est true
        'group'=>[ // group d'attributs
            'ajax'=>['ajax','rel'=>'nofollow']],
        'style'=>[ // si ces clés se retrouvent au premier niveau de attr, ils sont envoyés dans un tableau style
            'color',
            'padding',
            'margin',
            'border',
            'outline',
            'background',
            'background-image',
            'bgimg',
            'background-position']
    ];


    // selectedUri
    protected static $selectedUri = []; // tableau qui contient les uri sélectionnés


    // isDataKey
    // retourne vrai si l'attribut est data
    final public static function isDataKey($value):bool
    {
        return (is_string($value) && strpos($value,'data-') === 0 && strlen($value) > 5)? true:false;
    }


    // isDataUri
    // retourne vrai si l'attribut est une data-uri -> string encodé en base 64
    final public static function isDataUri($value):bool
    {
        return (is_string($value) && strpos($value,'data:') === 0 && strpos($value,';base64,') !== false)? true:false;
    }


    // isSelectedUri
    // retourne vrai si l'uri fourni est sélectionné
    final public static function isSelectedUri($value):bool
    {
        return (is_string($value) && array_key_exists($value,static::$selectedUri))? true:false;
    }


    // hasId
    // retourne vrai si les attributs ont le id spécifiés
    final public static function hasId(string $value,$attr):bool
    {
        return (static::getId($attr) === $value)? true:false;
    }


    // hasClass
    // retourne vrai si les attributs ont la ou les classes spécifiés
    final public static function hasClass($value,$attr):bool
    {
        $return = false;
        $value = (array) static::parseClass($value);
        $class = (array) static::getClass($attr);

        if(!empty($value) && !empty($class) && Arr::ins($value,$class))
        $return = true;

        return $return;
    }


    // styleExists
    // retourne vrai si les attributs ont la clé style spécifié
    final public static function styleExists(string $key,$attr,?array $option=null):bool
    {
        return (($style = static::style($attr,$option)) && Arr::keyExists($key,$style,static::getSensitive()))? true:false;
    }


    // stylesExists
    // retourne vrai si les attributs ont les clés style spécifiés
    final public static function stylesExists(array $keys,$attr,?array $option=null):bool
    {
        return (($style = static::style($attr,$option)) && Arr::keysExists($keys,$style,static::getSensitive()))? true:false;
    }


    // dataExists
    // retourne vrai si les attributs ont la clé data spécifié
    final public static function dataExists(string $key,$attr,?array $option=null):bool
    {
        return (($data = static::data($attr,$option)) && Arr::keyExists(static::parseDataKey($key),$data,static::getSensitive()))? true:false;
    }


    // datasExists
    // retourne vrai si les attributs ont les clés data spécifié
    final public static function datasExists(array $keys,$attr,?array $option=null):bool
    {
        $return = false;
        $data = static::data($attr,$option);
        $dataKeys = static::parseDataKeys($keys);

        if(!empty($data) && count($keys) === count($dataKeys))
        $return = Arr::keysExists($dataKeys,$data,static::getSensitive());

        return $return;
    }


    // compare
    // compare différents chaînes attributs
    // doivent avoir même clé, même count, même valeur, même classe et même clés de style
    // l'ordre peut être différent entre les tableaux
    final public static function compare(...$values):bool
    {
        $return = false;

        if(count($values) > 1)
        {
            foreach ($values as $key => $value)
            {
                $values[$key] = static::arr($value);

                if(!empty($values[$key]['class']))
                $values[$key]['class'] = array_fill_keys($values[$key]['class'],true);

                $values[$key] = Arrs::keysSort($values[$key]);
            }

            if(Arr::sameKeyValue(...$values))
            $return = true;
        }

        return $return;
    }


    // prepareParse
    // prépare le tableau en provenant de arr avant parse
    final protected static function prepareParse(array $return,array $option):array
    {
        // prepareStyle
        if(array_key_exists('style',$return) && !is_array($return['style']))
        $return['style'] = static::parseStyle($return['style'],$option['style'] ?? null);

        // parseClass
        if(array_key_exists('class',$return) && !is_array($return['class']))
        $return['class'] = (array) static::parseClass($return['class']);

        // clé numérique
        foreach (array_keys($return) as $key)
        {
            if(is_numeric($key))
            {
                $basic = static::parseBasic($return[$key]);
                if(!empty($basic['id']))
                $return['id'] = $basic['id'];

                if(!empty($basic['class']))
                $return = static::parseMerge(['class'=>$basic['class']],$return);

                unset($return[$key]);
            }
        }

        foreach ($return as $key => $value)
        {
            if(is_string($key))
            {
                // style key
                if(in_array($key,static::$config['style'],true))
                {
                    $return['style'][$key] = $value;
                    unset($return[$key]);
                }

                // oddEven
                elseif($key === 'oddEven' && is_numeric($value))
                {
                    $oddEven = static::getOddEvenClass($value);
                    if(is_string($oddEven))
                    $return['class'][] = $oddEven;
                    unset($return[$key]);
                }

                // parse Uri + hrefTargetExternal, hrefSelected et hrefLang et hrefMailto
                elseif($key === 'href')
                $return = static::prepareParseHref($value,$return,$option);
            }
        }

        return $return;
    }


    // prepareParseHref
    // méthode protégé pour parseHref
    final protected static function prepareParseHref($value,array $return,array $option):array
    {
        $key = 'href';
        $href = $option[$key];

        if($href['active'] === true && !empty($href['mailto']) && Email::is($value))
        $value = 'mailto:'.$value;

        else
        {
            $value = static::parseUri($value,$option[$key]['extension'] ?? null);

            if(is_string($value))
            {
                if($href['active'] === true)
                {
                    $isExternal = Uri::isExternal($value);

                    if(is_string($href['targetExternal']) && !array_key_exists('target',$return) && $isExternal === true)
                    $return['target'] = $href['targetExternal'];

                    if(!empty($href['selected']) && static::isSelectedUri($value))
                    {
                        $selected = static::getSelectedUri($value);
                        $selected = ($selected === true)? $href['selected']:$selected;
                        $return['class'][] = $selected;
                    }

                    if(!empty($href['lang']) && $isExternal === false)
                    {
                        $lang = Uri::lang($value);
                        if(!empty($lang))
                        $return['hreflang'] = $lang;
                    }
                }

                $value = static::outputUri($value,$option['uri']);
            }
        }

        if(is_string($value))
        $return[$key] = $value;

        return $return;
    }


    // prepareGroup
    // prépare et append les groups à l'intérieur d'un tableau d'attributs
    // retourne null si le tableau d'attribut ne contient pas de group
    final protected static function prepareGroup(array $array,array $option):?array
    {
        $return = null;

        if(array_key_exists('group',$array))
        {
            $groups = (array) $array['group'];
            unset($array['group']);

            if(!empty($groups))
            {
                $append = [];

                foreach ($groups as $group)
                {
                    if(array_key_exists($group,static::$config['group']))
                    $append[] = static::$config['group'][$group];
                }

                if(!empty($append))
                $return = static::prepend($array,...$append);
            }
        }

        return $return;
    }


    // parse
    // passe à travers un tableau attr explosé
    // si le tableau contient un ou des groupes, il n'est pas parse à nouveau
    final public static function parse(array $array,array $option):array
    {
        $return = [];
        $group = static::prepareGroup($array,$option);

        if(is_array($group))
        $return = $group;

        else
        {
            foreach (static::prepareParse($array,$option) as $key => $value)
            {
                if(is_string($key) && !empty($key))
                {
                    if($key === 'alt')
                    $value = static::parseAlt($value,$option['alt']);

                    elseif($key === 'id')
                    $value = static::parseId($value);

                    elseif($key === 'class')
                    $value = static::parseClass($value);

                    elseif(in_array($key,['src','action'],true) && static::isDataUri($value) === false)
                    {
                        $value = static::parseUri($value,$option[$key]['extension'] ?? null);

                        if(is_string($value))
                        $value = static::outputUri($value,$option['uri']);
                    }

                    elseif($key === 'style')
                    $value = static::parseStyle($value,$option['style'] ?? null);

                    elseif($key === 'data' && is_array($value))
                    $value = static::parseData($value,$option['case']);

                    elseif($key === 'method' && is_bool($value))
                    $value = ($value === true)? (static::$config['method'] ?? null):null;

                    elseif($key === 'target' && is_bool($value))
                    $value = ($value === true)? (static::$config['target'] ?? null):null;

                    elseif(in_array($key,static::$config['mirror'],true) && is_bool($value))
                    $value = ($value === true)? $key:null;

                    if(is_string($key) && !empty($key) && $value !== null)
                    $return = static::parseMerge([$key=>$value],$return);
                }
            }
        }

        return $return;
    }


    // parseBasic
    // parse une entrée basic du tableau attr
    // ceci signifie une valeur d'une clé numérique du tableau
    // peut seulement contenir id ou classe
    final public static function parseBasic($value):?array
    {
        $return = null;
        $id = static::$config['id'];
        $class = static::$config['class'];
        $classes = [];

        if(is_array($value))
        {
            if(Arrs::is($value))
            $value = Arrs::implode(' ',$value);

            else
            $value = implode(' ',$value);
        }

        if(is_string($value) && !empty($value))
        {
            if(!empty($id) && strpos($value,$id) !== false)
            $value = str_replace($id," $id",$value);

            if(!empty($class) && strpos($value,$class) !== false)
            $value = str_replace($class," $class",$value);

            foreach (Str::wordExplode($value) as $key => $value)
            {
                if(strlen($value) > 1)
                {
                    if(!empty($id) && strpos($value,$id) === 0)
                    {
                        $id = substr($value,strlen($id));
                        $parse = static::parseId($id);
                        if(!empty($parse))
                        $return = ['id'=>$parse];
                    }

                    else
                    {
                        if(!empty($class) && strpos($value,$class) === 0)
                        $value = substr($value,strlen($class));

                        $classes[] = $value;
                    }
                }
            }

            if(!empty($classes))
            {
                $classes = static::parseClass($classes);
                if(!empty($classes))
                $return['class'] = $classes;
            }
        }

        return $return;
    }


    // parseId
    // parse une valeur qui pourrait être un id
    // si value est true, envoie à randomId
    final public static function parseId($value):?string
    {
        $return = null;

        if($value === true)
        $return = static::randomId();

        elseif(is_string($value) && Validate::isAlphanumericDash($value))
        $return = $value;

        return $return;
    }


    // parseAlt
    // parse un champ alt
    // le retour est une slug
    final public static function parseAlt($value,?array $option=null):?string
    {
        return (is_string($value) && !empty($value))? Slug::str($value,$option):null;
    }


    // parseClass
    // parse une valeur classe, peut être string ou array
    // si return est fourni, les classes uniques sont ajoutés à return
    final public static function parseClass($value,?array $return=null):?array
    {
        if(!empty($value))
        {
            if(is_string($value) && !is_numeric($value))
            $value = [$value];

            if(is_array($value))
            {
                foreach ($value as $v)
                {
                    if(is_string($v))
                    $return = static::explodeClass($v,$return);
                }
            }
        }

        return $return;
    }


    // parseUri
    // parse un champ uri (href, action ou src)
    // extension par défaut seulement ajouté si uri relative
    final public static function parseUri($value,?string $extension=null):?string
    {
        $return = null;

        if(is_scalar($value))
        {
            $return = Str::cast($value);

            if(is_string($extension) && !Uri::isAbsolute($return))
            {
                $ext = Path::extension($return);
                if(empty($ext))
                $return = Path::changeExtension($extension,$return);
            }
        }

        return $return;
    }


    // outputUri
    // méthode utilisé pour faire un output d'uri
    // option append est enlevé si l'uri est absolute
    final public static function outputUri(string $value,?array $option=null):string
    {
        $return = null;
        $option = Arr::plus(['append'=>null],$option);

        if(!empty($option['append']) && Uri::isAbsolute($value))
        $option['append'] = null;

        $return = Uri::output($value,$option);

        return $return;
    }


    // parseStyle
    // parse un champ style
    final public static function parseStyle($value,array $option=null):?array
    {
        $return = null;

        if(!empty($value) && (is_string($value) || is_array($value)))
        $return = Style::arr($value,$option);

        return $return;
    }


    // parseData
    // parse un champ data qui est un tableau avec multiples clés datas
    final public static function parseData(array $value,$case=null):?array
    {
        $return = null;

        if(!empty($value))
        {
            $return = [];

            if($case !== null)
            $value = Arr::keysChangeCase($case,$value);

            foreach ($value as $k => $v)
            {
                $k = static::parseDataKey($k);
                if(is_string($k))
                $return[$k] = $v;
            }
        }

        return $return;
    }


    // parseDataKey
    // parse une clé data, ajoute data- au besoin
    // traite aussi les entrées data camelCase
    final public static function parseDataKey(string $return):string
    {
        if(!static::isDataKey($return))
        $return = 'data-'.$return;

        if(strtolower($return) !== $return)
        $return = Str::fromCamelCase('-',$return);

        return $return;
    }


    // parseDataKeys
    // parse un tableau de clés data
    final public static function parseDataKeys(array $keys):array
    {
        $return = [];

        foreach ($keys as $i => $key)
        {
            if(is_string($key))
            $return[$i] = static::parseDataKey($key);
        }

        return $return;
    }


    // parseMerge
    // merge un tableau parse sur un tableau existant
    // seules les clés data peuvent accepter une valeur bool, pas les attributs normaux
    final public static function parseMerge(array $array,array $return=[]):array
    {
        foreach ($array as $key => $value)
        {
            if(is_string($key) && !empty($key) && $value !== null)
            {
                if(($key === 'class' || $key === 'style') && array_key_exists($key,$return) && is_array($value) && is_array($return[$key]))
                {
                    if($key === 'class')
                    $return[$key] = Arr::appendUnique($return[$key],$value);

                    elseif($key === 'style')
                    $return[$key] = Arr::replace($return[$key],$value);
                }

                elseif($key === 'data' && is_array($value))
                {
                    foreach ($value as $k => $v)
                    {
                        if(is_string($k))
                        {
                            if(in_array($k,static::$config['pattern'],true))
                            $v = Validate::pattern($v);

                            $return[$k] = $v;
                        }
                    }
                }

                elseif(in_array($key,static::$config['pattern'],true))
                $return[$key] = Validate::pattern($value);

                else
                $return[$key] = $value;
            }
        }

        return $return;
    }


    // append
    // append plusieurs valeurs attributes et retourne un grand tableau parsed
    final public static function append(...$values):array
    {
        $return = [];

        foreach ($values as $value)
        {
            if(!empty($value))
            {
                $value = static::arr($value);

                if(!empty($value))
                $return = static::parseMerge($value,$return);
            }
        }

        $return = static::arr($return);

        return $return;
    }


    // list
    // les options de list peuvent être null
    // explose et implose une valeur
    // retourne un tableau unidimensionnel avec clé numérique
    final public static function list($array,?array $option=null):array
    {
        $return = [];
        $option = static::option($option);
        $separator = static::getSeparator(1,$option['implode']);
        $array = static::arr($array,$option);
        $array = static::keyValue($array,$option);

        if(is_array($array) && !empty($array))
        {
            foreach ($array as $key => $value)
            {
                if(is_string($key) && (is_scalar($value) || is_array($value)))
                {
                    $value = (array) $value;

                    foreach ($value as $v)
                    {
                        if(is_scalar($v))
                        {
                            $v = Str::cast($v);
                            $return[] = implode($separator,[$key,$v]);
                        }
                    }
                }
            }
        }

        return $return;
    }


    // prepareStr
    // prépare une string dans la méthode arr
    final public static function prepareStr(string $value,array $option):array
    {
        $return = [];
        $separator = static::getSeparator(1,$option['explode']);

        if(strpos($value,$separator) === false)
        $return = [$value];

        else
        $return = static::explodeStr($value,$option);

        return $return;
    }


    // prepareArr
    // prépare un array dans la méthode arr
    final public static function prepareArr(array $value,?array $option=null):array
    {
        return $value;
    }


    // explodeStr
    // explode une string attr avec preg_match_all
    // ne peut pas fonctionner si un attribut contient un single ou double quote à l'intérieur des quotes
    final public static function explodeStr(string $value):array
    {
        $return = [];
        $match = [];
        $value = Str::doubleToSingleQuote($value);
        preg_match_all("%(.*)=\'(.*)\'%Uis",$value,$match,PREG_SET_ORDER);

        if(!empty($match))
        {
            foreach ($match as $key => $value)
            {
                if(is_array($value) && count($value) === 3)
                {
                    $value = Arr::trim($value);
                    $k = Str::unquote($value[1],true,false);
                    $v = Str::unquote($value[2],true,false);
                    $return[$k] = $v;
                }
            }
        }

        return $return;
    }


    // explodeClass
    // explose une string de classe
    // sensible à la case
    final public static function explodeClass(string $value,?array $return=[]):array
    {
        $return = ($return === null)? []:$return;

        if(strpos($value,' ') !== false)
        {
            $value = explode(' ',$value);

            if(!empty($value))
            {
                foreach ($value as $v)
                {
                    if(!is_numeric($v) && !empty($v) && Validate::isAlphanumericDash($v) && !in_array($v,$return,true))
                    $return[] = $v;
                }
            }
        }

        elseif(!is_numeric($value) && !empty($value) && Validate::isAlphanumericDash($value) && !in_array($value,$return,true))
        $return[] = $value;

        return $return;
    }


    // classImplode
    // retourne une string class à partir d'un array
    final public static function classImplode(array $value):?string
    {
        $return = null;

        $value = Str::wordImplode($value);
        if(!empty($value))
        $return = $value;

        return $return;
    }


    // prepareClass
    // retourne une string class correctement formatté
    final public static function prepareClass($value):?string
    {
        $return = null;
        $value = static::parseClass($value);

        if(is_array($value))
        $return = static::classImplode($value);

        return $return;
    }


    // keyValue
    // ramène le tableau arr à un tableau unidimensionnel
    // toutes les clés et valeurs du tableau sont des string
    // les valeurs sont passés dans quote et entities ou specialchars par défaut
    final public static function keyValue(array $array,array $option):array
    {
        $return = [];
        $option['encode'] = (is_string($option['encode']) || $option['encode'] === true)? [$option['encode']]:$option['encode'];

        if($option['caseImplode'] !== null)
        $array = Arr::keysChangeCase($option['caseImplode'],$array);

        foreach ($array as $key => $value)
        {
            if(is_string($key) && !empty($key))
            {
                if(is_scalar($value))
                $value = Str::cast($value);

                elseif(is_array($value) && !empty($value))
                {
                    if($key === 'class')
                    $value = static::classImplode($value);

                    elseif($key === 'style')
                    $value = Style::implode($value,$option['style']);

                    else
                    $value = Json::encode($value,$option['json']);
                }

                elseif(is_object($value))
                $value = Json::encode($value,$option['json']);

                if(is_string($value))
                {
                    if(!empty($option['encode']) && is_array($option['encode']))
                    $value = Html::encode($value,...$option['encode']);

                    if(is_bool($option['quote']))
                    $value = Str::quote($value,$option['quote']);

                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }


    // getId
    // retourne le id à partir d'une valeur attribut
    final public static function getId($assoc,?array $option=null):?string
    {
        return static::get('id',$assoc,$option);
    }


    // setId
    // change le id d'une valeur attribut
    final public static function setId(string $value,$assoc,?array $option=null):?array
    {
        $return = null;

        $value = static::parseId($value);
        if(!empty($value))
        $return = static::set('id',$value,$assoc,$option);

        return $return;
    }


    // randomId
    // génère un id random
    final public static function randomId(?string $name=null,?array $option=null):string
    {
        return Str::randomPrefix((is_string($name))? $name:'',...array_values(Arr::plus(static::$config['randomId'],$option)));
    }


    // getClass
    // retourne le tableau des classes uniques à partir d'une valeur attribut
    final public static function getClass($assoc,?array $option=null):?array
    {
        return static::get('class',$assoc,$option);
    }


    // getOddEvenClass
    // retourne le nom de la classe dépendamment si la valeur est numérique odd ou even
    final public static function getOddEvenClass($value):?string
    {
        $return = null;

        if(is_numeric($value))
        {
            if(Num::isOdd($value))
            $return = static::getOddClass();

            elseif(Num::isEven($value))
            $return = static::getEvenClass();
        }

        return $return;
    }


    // getOddClass
    // retourne le nom de la classe pour odd
    final public static function getOddClass():?string
    {
        return (!empty(static::$config['oddEven'][0]))? static::$config['oddEven'][0]:null;
    }


    // getEvenClass
    // retourne le nom de la classe pour even
    final public static function getEvenClass():?string
    {
        return (!empty(static::$config['oddEven'][1]))? static::$config['oddEven'][1]:null;
    }


    // setClass
    // change les classes d'une valeur attribut
    final public static function setClass($value,$assoc,?array $option=null):?array
    {
        return static::set('class',static::parseClass($value),$assoc,$option);
    }


    // addClass
    // ajoute une ou plusieurs classes à une valeur attribut
    final public static function addClass($value,$assoc,?array $option=null):?array
    {
        return static::set('class',static::parseClass($value,static::getClass($assoc,$option)),$assoc,$option);
    }


    // removeClass
    // enlève une ou plusieurs classes à une valeur attribut
    final public static function removeClass($value,$assoc,?array $option=null):?array
    {
        $return = null;
        $value = (array) static::parseClass($value);
        $class = (array) static::getClass($assoc,$option);

        if(!empty($class))
        $return = static::set('class',Arr::valuesStrip($value,$class,static::getSensitive()),$assoc,$option);

        return $return;
    }


    // toggleClass
    // ajoute une ou plusieurs classes non existante dans la valeur attribut
    // enlève une ou plusieurs classes existante dans la valeur attribut
    final public static function toggleClass($value,$assoc,?array $option=null):?array
    {
        $return = false;
        $value = (array) static::parseClass($value);
        $class = (array) static::getClass($assoc,$option);

        if(is_array($value))
        {
            foreach ($value as $v)
            {
                if(in_array($v,$class,true))
                $class = Arr::valueStrip($v,$class,static::getSensitive());

                else
                $class[] = $v;
            }

            $return = static::set('class',$class,$assoc,$option);
        }

        return $return;
    }


    // style
    // retourne un tableau contenant l'ensemble des styles d'un attribut ou null si non existant
    final public static function style($assoc,?array $option=null):?array
    {
        return static::get('style',$assoc,$option);
    }


    // getStyle
    // retourne un style à partir d'un attribut
    final public static function getStyle(string $key,$assoc,?array $option=null)
    {
        return Arr::get($key,static::style($assoc,$option),static::getSensitive());
    }


    // getsStyle
    // retourne plusieurs styles à partir d'un attribut
    final public static function getsStyle(array $keys,$assoc,?array $option=null):array
    {
        return Arr::gets($keys,static::style($assoc,$option),static::getSensitive());
    }


    // setStyle
    // change un style à l'intérieur d'un attribut
    final public static function setStyle(string $key,$value,$assoc,?array $option=null):?array
    {
        return static::set('style',Arr::set($key,$value,static::style($assoc,$option),static::getSensitive()),$assoc,$option);
    }


    // setsStyle
    // change plusieurs styles à l'intérieur d'un attribut
    final public static function setsStyle(array $values,$assoc,?array $option=null):?array
    {
        return static::set('style',Arr::sets($values,static::style($assoc,$option),static::getSensitive()),$assoc,$option);
    }


    // unsetStyle
    // enlève un style à l'intérieur d'un attribut
    final public static function unsetStyle(string $key,$assoc,?array $option=null):?array
    {
        return static::set('style',Arr::unset($key,static::style($assoc,$option),static::getSensitive()),$assoc,$option);
    }


    // unsetsStyle
    // enlève plusieurs styles à l'intérieur d'un attribut
    final public static function unsetsStyle(array $keys,$assoc,?array $option=null):?array
    {
        return static::set('style',Arr::unsets($keys,static::style($assoc,$option),static::getSensitive()),$assoc,$option);
    }


    // emptyStyle
    // enlève tous les styles d'un attribut
    final public static function emptyStyle($assoc,?array $option=null):?array
    {
        return static::unset('style',$assoc,$option);
    }


    // data
    // retourne un tableau contenant l'ensemble des datas d'un attribut
    // retourne null si non existant
    final public static function data($assoc,?array $option=null):?array
    {
        $return = null;

        foreach (static::arr($assoc,$option) as $key => $value)
        {
            if(static::isDataKey($key))
            $return[$key] = $value;
        }

        return $return;
    }


    // getData
    // retourne un data à partir d'un attribut
    // la clé est passé dans parseDataKey pour ajouter data- si manquant
    final public static function getData(string $key,$assoc,?array $option=null)
    {
        return static::get(static::parseDataKey($key),$assoc,$option);
    }


    // getsData
    // retourne plusieurs datas à partir d'un attribut
    // les clés sont passés dans parseDataKeys pour ajouter data- si manquant
    final public static function getsData(array $keys,$assoc,?array $option=null):array
    {
        return static::gets(static::parseDataKeys($keys),$assoc,$option);
    }


    // setData
    // change un data à l'intérieur d'un attribut
    // la clé est passé dans parseDataKey pour ajouter data- si manquant
    final public static function setData(string $key,$value,$assoc,?array $option=null):?array
    {
        return static::set(static::parseDataKey($key),$value,$assoc,$option);
    }


    // setsData
    // change plusieurs datas à l'intérieur d'un attribut
    // les clés sont passés dans parseDataKeys pour ajouter data- si manquant
    final public static function setsData(array $values,$assoc,?array $option=null):?array
    {
        return static::set('data',Arr::sets($values,static::data($assoc,$option),static::getSensitive()),$assoc,$option);
    }


    // unsetData
    // enlève un data à l'intérieur d'un attribut
    // la clé est passé dans parseDataKey pour ajouter data- si manquant
    final public static function unsetData(string $key,$assoc,?array $option=null):?array
    {
        return static::unset(static::parseDataKey($key),$assoc,$option);
    }


    // unsetsData
    // enlève plusieurs datas à l'intérieur d'un attribut
    // les clés sont passés dans parseDataKeys pour ajouter data- si manquant
    final public static function unsetsData(array $keys,$assoc,?array $option=null):?array
    {
        return static::unsets(static::parseDataKeys($keys),$assoc,$option);
    }


    // emptyData
    // enlève toutes les clés data des attributs
    final public static function emptyData($assoc,?array $option=null):?array
    {
        $return = null;
        $data = static::data($assoc,$option);

        if(!empty($data))
        $return = static::unsets(array_keys($data),$assoc,$option);

        return $return;
    }


    // selectedUri
    // retourne le tableau des uri sélectionnés
    final public static function selectedUri():array
    {
        return static::$selectedUri;
    }


    // selectedUriArray
    // retourne le tableau des uri sélectionnés, sans les classes
    final public static function selectedUriArray():array
    {
        return array_keys(static::$selectedUri);
    }


    // getSelectedUri
    // retourne la classe à utiliser pour une uri sélectionnée
    final public static function getSelectedUri(string $key)
    {
        return (array_key_exists($key,static::$selectedUri))? static::$selectedUri[$key]:null;
    }


    // addSelectedUri
    // ajoute ou plusieurs uri sélectionné
    // il faut soumettre un tableau uri->class, si class est true, la classe par défaut sera utilisé
    final public static function addSelectedUri(array $values):void
    {
        static::$selectedUri = Arr::replace(static::$selectedUri,$values);

        return;
    }


    // removeSelectedUri
    // enlève une ou plusieurs uri sélectionné
    final public static function removeSelectedUri(string ...$uris):void
    {
        $return = false;

        foreach ($uris as $uri)
        {
            if(array_key_exists($uri,static::$selectedUri))
            unset(static::$selectedUri[$uri]);
        }

        return;
    }


    // getUriOption
    // retourne les options uri pour attr
    final public static function getUriOption():array
    {
        return static::$config['option']['uri'];
    }


    // setUriOption
    // change les options uri pour attr
    final public static function setUriOption(array $option):void
    {
        static::$config['option']['uri'] = Uri::option($option);

        return;
    }
}

// init
Attr::__init();
?>