<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// html
// class with static methods for easily generating HTML
class Html extends Root
{
    // config
    public static $config = [
        'default'=>'specialChars', // méthode par défaut pour encode/décode
        'entities'=>[ // pour htmlentities
            'flag'=>ENT_QUOTES | ENT_SUBSTITUTE, // flag utilisé par la fonction
            'doubleEncode'=>true], // active ou non le double encodage
        'specialChars'=>[ // pour specialchars
            'flag'=>ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE, // flag utilisé par la fonction
            'doubleEncode'=>true], // active ou non le double encodage
        'excerptSuffix'=>"<span class='excerptSuffix'>...</span>", // suffix utilisé par excerpt
        'alias'=>[ // alias de tag
            'anchor'=>'a'],
        'wrap'=>[ // liste de wrap
            'divele'=>["<div class='element'>",'</div>'],
            'divtable'=>["<div class='table'><div class='table-row'><div class='table-cell'>",'</div></div></div>'],
            'ie8'=>['<!--[if lte IE 8>','<![endif]-->']],
        'formWrap'=>[ // liste de wrap pour form avec replace
            'default'=>'%label%%form%',
            'reverse'=>'%form%%label%',
            'br'=>'%label%<br/>%form%',
            'table'=>'<table><tr><td>%label%</td><td>%form%</td></tr></table>',
            'divtable'=>"<div class='table'><div class='table-row'><div class='table-cell label-cell'>%label%</div><div class='table-cell form-cell'>%form%</div></div></div>",
            'divtableClass'=>"<div class='table %class%'><div class='table-row'><div class='table-cell label-cell'>%label%</div><div class='table-cell form-cell'>%form%</div></div></div>",
            'div'=>'<div>%label%</div><div>%form%</div>'],
        'static'=>[ // terme accepté pour callStatic
            'special'=>['cond','many','or','loop'],
            'openClose'=>['open','close','op','cl']],
        'multi'=>'[]', // caractère pour nom multi
        'clickOpen'=>['popup'=>'popup','trigger'=>'trigger','title'=>'title','icon'=>'ico'], // classes pour clickOpen
        'fakeSelect'=>['selected'=>'selected'], // classes pour fakeSelect
        'separator'=>"\n", // caractère séparateur new line
        'genuine'=>'-genuine-', // nom pour l'input genuine
        'randomNameWrap'=>'-', // caractère utilisé pour wrapper un name qui est true
        'bool'=>[ // relation bool
            0=>[
                0=>'false',
                1=>'true']],
        'option'=>[ // tableau d'options
            'attr'=>null, // option pour attr
            'html'=>null, // wrap html autour de la tag
            'encode'=>null], // si les valeurs sont passés dans htmlspecialchars ou htmlentities, true est specialchars
        'docOpen'=>[
            'default'=>[ // défaut pour docOpen
                'doctype'=>true,
                'html'=>true,
                'head'=>[
                    'meta'=>[
                        'charset'=>true]],
                'body'=>true],
            'order'=>['doctype','html','head','body']], // ordre pour docOpen
        'docClose'=>[ // défaut pour docClose
            'default'=>[
                'body'=>true,
                'html'=>true],
            'order'=>['script','js','body','html']], // order pour docClose
        'tag'=>[ // config pour les tags
            'title'=>[ // config pour title
                'valueCallable'=>[self::class,'titleValue'],
                'option'=>[
                    'excerpt'=>[
                        'encode'=>null],
                    'case'=>'upperFirst',
                    'maxLength'=>75,
                    'separator'=>' | ',
                    'encode'=>true]],
            'a'=>[ // config pour a
                'scalarAttr'=>['bool'=>'target','true'=>'_blank'],
                'option'=>['attr'=>['uri'=>[]]]],
            'br'=>[ // config pour br
                'valueAttr'=>'data-value',
                'selfClosing'=>true],
            'hr'=>[ // config pour hr
                'valueAttr'=>'data-value',
                'selfClosing'=>true],
            'img'=>[ // config pour img
                'valueCallable'=>[self::class,'imgValue'],
                'valueAttr'=>'src',
                'selfClosing'=>true,
                'attrCallable'=>[self::class,'imgAttr'],
                'option'=>['attr'=>['uri'=>[]]]],
            'label'=>[ // config pour label
                'scalarAttr'=>'for'],
            'head'=>[ // config pour head
                'valueCallable'=>[self::class,'headValue'],
                'option'=>[
                    'separator'=>null],
                'order'=>['title','description','keywords','meta','link','script','css','js']],
            'table'=>[ // config pour table
                'option'=>[
                    'strict'=>false]],
            'meta'=>[ // config pour meta
                'attrCallable'=>[self::class,'metaAttr'],
                'valueCallable'=>[self::class,'metaValue'],
                'selfClosing'=>true,
                'valueAttr'=>'content',
                'scalarAttr'=>'name',
                'typeAttr'=>'name',
                'property'=>['og:title','og:description','og:url','og:type','og:image','fb:app_id'], // liste de meta qui utilise property plutôt que name
                'uri'=>[
                    'key'=>['og:url','og:image'], // liste de meta qui repésente des uri
                    'option'=>['absolute'=>true]], // option pour les uri
                'description'=>[
                    'option'=>[ // option meta description
                        'case'=>'upperFirst',
                        'maxLength'=>150,
                        'separator'=>' - ']],
                'keywords'=>[
                    'option'=>[ // option pour meta keywords
                        'maxLength'=>150,
                        'separator'=>', ',
                        'excerpt'=>['suffix'=>false]]]],
            'iframe'=>[
                'valueAttr'=>'srcdoc'],
            'link'=>[ // config pour link
                'selfClosing'=>true,
                'valueAttr'=>'href',
                'scalarAttr'=>['rel','true'=>'stylesheet'],
                'typeAttr'=>'rel',
                'prev'=>[
                    'option'=>['absolute'=>true]], // option pour rel prev
                'next'=>[
                    'option'=>['absolute'=>true]], // option pour rel next
                'stylesheet'=>[
                    'option'=>[
                        'attr'=>['href'=>['active'=>false,'extension'=>'css']]],
                    'attr'=>[ // attr par défaut pour stylesheet
                        'rel'=>'stylesheet',
                        'type'=>'text/css',
                        'media'=>'screen,print']],
                'option'=>[
                    'attr'=>[
                        'uri'=>[],
                        'href'=>['active'=>false]]]],
            'script'=>[ // config pour script
                'valueCallable'=>[self::class,'scriptValue'],
                'option'=>[
                    'var'=>null,
                    'attr'=>['uri'=>[],'src'=>['extension'=>'js']]],
                'attr'=>[]],
            'form'=>[ // config pour form
                'attrCallable'=>[self::class,'formAttr'],
                'valueCallable'=>[self::class,'formValue'],
                'scalarAttr'=>['method','true'=>'post'],
                'all'=>['input'=>'text','textarea'=>'text','select'=>'enum','radio'=>'enum','multiselect'=>'set','checkbox'=>'set'],
                'option'=>[ // à ajouter après l'ouverture du form
                    'method'=>'post',
                    'attr'=>['uri'=>[]],
                    'csrf'=>['get'=>false,'post'=>true],
                    'genuine'=>['get'=>false,'post'=>true],
                    'enctype'=>['get'=>null,'post'=>'multipart/form-data']]],
            'button'=>[
                'attrCallable'=>[self::class,'buttonAttr'],
                'scalarAttr'=>['name'],
                'typeAttr'=>'type'],
            'input'=>[ // config pour input
                'attrCallable'=>[self::class,'inputAttr'],
                'selfClosing'=>true,
                'valueAttr'=>['default'=>'value','image'=>'src'],
                'scalarAttr'=>['name'],
                'typeAttr'=>'type',
                'attr'=>[ // attribut par défaut
                    'type'=>'text'],
                'option'=>[
                    'label'=>null, // si le input a un label
                    'position'=>1,
                    'multi'=>null],
                'text'=>[
                    'attr'=>['maxlength'=>255]],
                'email'=>[
                    'attr'=>['maxlength'=>255]],
                'radio'=>[
                    'option'=>[
                        'position'=>2,
                        'checked'=>null]],
                'checkbox'=>[
                    'option'=>[
                        'position'=>2,
                        'multi'=>true,
                        'checked'=>null]],
                'default'=>'text', // type de input par défaut
                'all'=>[ // type de input en clé et groupe en valeur
                    'submit'=>null,
                    'image'=>null,
                    'hidden'=>'hidden',
                    'text'=>'text',
                    'email'=>'text',
                    'date'=>'text',
                    'radio'=>'enum',
                    'checkbox'=>'set',
                    'file'=>null,
                    'password'=>'text',
                    'button'=>null]], // tous les types de input
            'select'=>[ // config pour select
                'attrCallable'=>[self::class,'selectAttr'],
                'valueCallable'=>[self::class,'selectValue'],
                'scalarAttr'=>['name'],
                'option'=>[ // option à merge aux options de base
                    'label'=>null,
                    'position'=>1,
                    'multi'=>null,
                    'title'=>null,
                    'selected'=>null]],
            'option'=>[ // config pour option
                'attrCallable'=>[self::class,'optionAttr'],
                'scalarAttr'=>'value',
                'option'=>[ // option à merge aux options de base
                    'title'=>null,
                    'selected'=>null]],
            'textarea'=>[ // config pour textarea
                'attrCallable'=>[self::class,'textareaAttr'],
                'scalarAttr'=>['name'],
                'option'=>[ // option à merge aux options de base
                    'encode'=>true,
                    'label'=>null,
                    'position'=>1,
                    'multi'=>null]]]
    ];


    // is
    // retourne vrai si la chaîne est du html
    public static function is($value):bool
    {
        $return = false;

        if(is_string($value))
        {
            $value = trim($value);

            if(Str::isStart('<',$value) && !Str::isStart('<?xml',$value))
            $return = true;
        }

        return $return;
    }


    // isWrap
    // retourne vrai si la valeur est un wrap valide
    public static function isWrap($value):bool
    {
        $return = false;

        if(is_string($value) && array_key_exists($value,static::$config['wrap']) && is_array(static::$config['wrap'][$value]))
        {
            if(array_key_exists(0,static::$config['wrap'][$value]) && is_string(static::$config['wrap'][$value][0]))
            {
                if(array_key_exists(1,static::$config['wrap'][$value]) && is_string(static::$config['wrap'][$value][1]))
                $return = true;
            }
        }

        return $return;
    }


    // isAlias
    // retourne vrai si la valeur est un alias de tag
    public static function isAlias($value):bool
    {
        return (is_string($value) && !empty(static::$config['alias'][$value]))? true:false;
    }


    // isInputType
    // retourne vrai si la valeur est un type de input
    public static function isInputType($value):bool
    {
        return (is_string($value) && array_key_exists($value,static::$config['tag']['input']['all']))? true:false;
    }


    // isInputMethod
    // retourne vrai si la méthode en est une de input
    public static function isInputMethod($value):bool
    {
        return (is_string($value) && !empty(static::getTypeFromInputMethod($value)))? true:false;
    }


    // isFormTag
    // retourne vrai si la tag est une tag de form
    // si inputMethod est true et que la tag n'est pas une tag standard, envoie à inputMethod
    public static function isFormTag($value,bool $inputMethod=true):bool
    {
        $return = (is_string($value) && array_key_exists($value,static::$config['tag']['form']['all']))? true:false;

        if($return === false && $inputMethod === true)
        $return = static::isInputMethod($value);

        return $return;
    }


    // isTextTag
    // retourne vrai si la tag est une tag text comme input ou textarea
    public static function isTextTag($value,bool $inputMethod=true):bool
    {
        $return = (static::isFormTag($value,false) && static::$config['tag']['form']['all'][$value] === 'text')? true:false;

        if($return === false && $inputMethod === true)
        $return = static::isInputGroup('text',$value);

        return $return;
    }


    // isHiddenTag
    // retourne vrai si la méthode en est une de input hidden
    public static function isHiddenTag($value,bool $inputMethod=true):bool
    {
        $return = false;

        if(is_string($value) && array_key_exists($value,static::$config['tag']['input']['all']) && static::$config['tag']['input']['all'][$value] === 'hidden')
        $return = true;

        if($return === false && $inputMethod === true)
        $return = static::isInputGroup('hidden',$value);

        return $return;
    }


    // isRelationTag
    // retourne vrai si la tag en est une de relation
    public static function isRelationTag($value,bool $inputMethod=true):bool
    {
        return (static::isEnumTag($value,$inputMethod) || static::isSetTag($value,$inputMethod))? true:false;
    }


    // isEnumTag
    // retourne vrai si la tag en est une de relation enum
    public static function isEnumTag($value,bool $inputMethod=true):bool
    {
        $return = (static::isFormTag($value,false) && static::$config['tag']['form']['all'][$value] === 'enum')? true:false;

        if($return === false && $inputMethod === true)
        $return = static::isInputGroup('enum',$value);

        return $return;
    }


    // isSetTag
    // retourne vrai si la tag en est une de relation enum set
    public static function isSetTag($value,bool $inputMethod=true):bool
    {
        $return = (static::isFormTag($value,false) && static::$config['tag']['form']['all'][$value] === 'set')? true:false;

        if($return === false && $inputMethod === true)
        $return = static::isInputGroup('set',$value);

        return $return;
    }


    // isNameMulti
    // retourne vrai si le nom du input est multi
    public static function isNameMulti($value):bool
    {
        return (is_string($value) && Str::isEnd(static::$config['multi'],$value))? true:false;
    }


    // isSelfClosing
    // retourne vrai si la valeur est un tag self closing
    public static function isSelfClosing($value):bool
    {
        return (is_string($value) && array_key_exists($value,static::$config['tag']) && !empty(static::$config['tag'][$value]['selfClosing']))? true:false;
    }


    // isMetaProperty
    // retourne vrai si le nom meta doit utilisé property plutôt que name
    public static function isMetaProperty($value):bool
    {
        return (is_string($value) && in_array($value,static::$config['tag']['meta']['property'],true))? true:false;
    }


    // isMetaUri
    // retourne vrai si la balise meta représente une uri
    public static function isMetaUri($value):bool
    {
        return (is_string($value) && in_array($value,static::$config['tag']['meta']['uri']['key'],true))? true:false;
    }


    // isUriOption
    // retourne vrai si la tag a des options d'uri
    public static function isUriOption($tag):bool
    {
        return (is_string($tag) && isset(static::$config['tag'][$tag]['option']['attr']['uri']))? true:false;
    }


    // isInputGroup
    // retourne vrai si le group du input type est celui donné en argument
    public static function isInputGroup($group,$value):bool
    {
        $return = false;

        if(is_string($value) && is_string($group))
        {
            $type = static::getTypeFromInputMethod($value);

            if(!empty($type) && array_key_exists($type,static::$config['tag']['input']['all']))
            {
                if(static::$config['tag']['input']['all'][$type] === $group)
                $return = true;
            }
        }

        return $return;
    }


    // isMultipartFormData
    // retourne si le type d'encodage est multipart form-data, pour formulaire avec fichier
    public static function isMultipartFormData($value):bool
    {
        return (is_string($value) && strtolower($value) === 'multipart/form-data')? true:false;
    }


    // inputsFromGroups
    // retourne tous les inputs à partir d'un ou plusieurs groupes
    public static function inputsFromGroups($groups,$not=null):array
    {
        $return = [];
        $groups = (array) $groups;
        $not = (array) $not;

        if(!empty($groups))
        {
            foreach (static::$config['tag']['form']['all'] as $name => $group)
            {
                if(in_array($group,$groups,true) && !in_array($name,$return,true) && !in_array($name,$not,true))
                $return[] = $name;
            }
        }

        return $return;
    }


    // relationTag
    // retourne tous les tag de relation
    public static function relationTag($not=null):array
    {
        return static::inputsFromGroups(['enum','set'],$not);
    }


    // encode
    // encode les caractères spéciaux html via htmlspecialchars ou htmlentities
    public static function encode(string $value,$method=true,?array $option=null):string
    {
        $return = '';

        if($method === true)
        $method = static::$config['default'];

        if($method === 'specialChars')
        $return = static::specialChars($value,$option);

        elseif($method === 'entities')
        $return = static::entities($value,$option);

        return $return;
    }


    // decode
    // decode les caractères spéciaux html via htmlspecialchars_decode ou htmlentities_decode
    public static function decode(string $value,$method=true,?array $option=null):string
    {
        $return = '';

        if($method === true)
        $method = static::$config['default'];

        if($method === 'specialChars')
        $return = static::specialCharsDecode($value,$option);

        elseif($method === 'entities')
        $return = static::entitiesDecode($value,$option);

        return $return;
    }


    // specialChars
    // encode les caractères spéciaux html
    public static function specialChars(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['specialChars'],$option);

        if(!empty($option))
        $return = htmlspecialchars($value,$option['flag'],$option['charset'],$option['doubleEncode']);

        return $return;
    }


    // specialCharsDecode
    // décode les caractères spéciaux html
    public static function specialCharsDecode(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['specialChars'],$option);

        if(!empty($option))
        $return = htmlspecialchars_decode($value,$option['flag']);

        return $return;
    }


    // specialCharsTable
    // retourne la table de traduction pour specialChars
    public static function specialCharsTable(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['specialChars'],$option);
        $return = get_html_translation_table(HTML_SPECIALCHARS,$option['flag'],$option['charset']);

        return $return;
    }


    // entities
    // encode les entities sur une string
    public static function entities(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['entities'],$option);

        if(!empty($option))
        $return = htmlentities($value,$option['flag'],$option['charset'],$option['doubleEncode']);

        return $return;
    }


    // entitiesDecode
    // decode les entities sur une string
    public static function entitiesDecode(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['entities'],$option);

        if(!empty($option))
        $return = html_entity_decode($value,$option['flag'],$option['charset']);

        return $return;
    }


    // entititesTable
    // retourne la table de traduction pour entities
    public static function entitiesTable(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(['charset'=>Encoding::getCharset()],static::$config['entities'],$option);
        $return = get_html_translation_table(HTML_ENTITIES,$option['flag'],$option['charset']);

        return $return;
    }


    // nl2br
    // converti les sauts de ligne en br
    public static function nl2br(string $value,bool $removeLineBreaks=true,bool $xhtml=true):string
    {
        $return = nl2br($value,$xhtml);

        if($removeLineBreaks === true)
        $return = Str::removeLineBreaks($return);

        return $return;
    }


    // brs
    // génère un ou plusieurs balises br
    public static function brs(int $amount):string
    {
        return static::nl2br(Str::eol($amount));
    }


    // stripTags
    // supprime les balises html d'une string
    public static function stripTags(string $value,$keep=null):string
    {
        $return = false;
        $keepStr = '';

        if(is_string($keep))
        $keep = [$keep];

        if(is_array($keep))
        {
            foreach ($keep as $v)
            {
                if(is_string($v) && !empty($v))
                {
                    if(Str::isStartEnd('<',$v,'>'))
                    $keepStr .= $v;
                    else
                    $keepStr .= "<$v>";
                }
            }
        }

        $return = strip_tags($value,$keepStr);

        return $return;
    }


    // getDefaultInputType
    // retourne le type de input par défaut
    public static function getDefaultInputType():?string
    {
        return static::$config['tag']['input']['default'] ?? null;
    }


    // getBool
    // retourne la relation bool selon l'index
    // si index est true, utilise 0
    public static function getBool($index=true):?array
    {
        $return = null;

        if($index === true)
        $index = 0;

        if(array_key_exists($index,static::$config['bool']))
        $return = static::$config['bool'][$index];

        return $return;
    }


    // getTypeFromInputMethod
    // retourne le type de input à partir d'une méthode inputType
    public static function getTypeFromInputMethod(string $method):?string
    {
        $return = null;

        if(stripos($method,'input') === 0)
        {
            $type = strtolower(substr($method,5));
            if(static::isInputType($type))
            $return = $type;
        }

        return $return;
    }


    // getFormTagFromMethod
    // retourne la première tag de form dans un nom de méthode
    public static function getFormTagFromMethod(string $method):?string
    {
        $return = null;

        if(!empty(static::$config['tag']['form']['all']))
        {
            foreach (static::$config['tag']['form']['all'] as $value => $type)
            {
                if(is_string($value) && stripos($method,$value) !== false)
                {
                    $return = $value;
                    break;
                }
            }
        }

        return $return;
    }


    // getTypeAttr
    // retourne l'attribut typeAttr pour la tag
    public static function getTypeAttr(string $tag):?string
    {
        return static::$config['tag'][$tag]['typeAttr'] ?? null;
    }


    // getTypeFromAttr
    // retourne le type du tag en fonction d'un tableau d'attribut
    public static function getTypeFromAttr(string $tag,array $attr):?string
    {
        $return = null;
        $type = static::getTypeAttr($tag);

        if(!empty($type) && !empty($attr[$type]))
        {
            if($attr[$type] === true)
            $return = static::$config['tag'][$tag]['scalarAttr']['true'] ?? null;

            else
            $return = $attr[$type];
        }

        return $return;
    }


    // getAttr
    // retourne le tableau d'attributs par défaut selon un tag
    // le type dans le tableau attribut, si présent, a priorité sur celui fourni en deuxième argument
    // possibilité de mettre un type
    public static function getAttr(string $tag,?string $type=null,$attr=null):array
    {
        $return = [];
        $attr = Obj::cast($attr);

        if(is_scalar($attr))
        $attr = static::getAttrScalar($tag,$attr);

        if(is_array($attr))
        {
            $t = static::getTypeFromAttr($tag,$attr);
            if(!empty($t))
            {
                $type = $t;
                $attr[static::getTypeAttr($tag)] = $type;
            }
        }

        if(!empty(static::$config['tag'][$tag]['attr']))
        $merge[] = static::$config['tag'][$tag]['attr'];

        if(is_string($type) && !empty(static::$config['tag'][$tag][$type]['attr']))
        $merge[] = static::$config['tag'][$tag][$type]['attr'];

        if($tag === 'input' || $tag === 'button')
        {
            if(is_string($type) && static::isInputType($type))
            $attr['type'] = $type;

            if(empty($attr['type']) || !static::isInputType($attr['type']))
            $attr['type'] = static::getDefaultInputType();
        }

        $merge[] = $attr;
        $return = Arr::plus(...$merge);

        return $return;
    }


    // getAttrScalar
    // traite un attribut scalar
    // retourne toujours un tableau
    public static function getAttrScalar(string $tag,$return):array
    {
        if(is_scalar($return) && !empty(static::$config['tag'][$tag]['scalarAttr']))
        {
            $scalar = static::$config['tag'][$tag]['scalarAttr'];

            if(is_array($scalar))
            {
                $value = ($return === true && array_key_exists('true',$scalar))? $scalar['true']:$return;

                if(is_string($return) && array_key_exists('string',$scalar))
                $return = [$scalar['string']=>$value];

                elseif(is_bool($return) && array_key_exists('bool',$scalar))
                $return = [$scalar['bool']=>$value];

                elseif(array_key_exists(0,$scalar))
                $return = [$scalar[0]=>$value];
            }

            elseif(is_string($scalar))
            $return = [$scalar=>$return];
        }

        if(!is_array($return))
        $return = (array) $return;

        return $return;
    }


    // getValueAttr
    // retourne l'attribut de valeur du tag ou null si non existant
    // support un value attr par type
    public static function getValueAttr(string $tag,?string $type=null):?string
    {
        $return = null;

        if((!empty(static::$config['tag'][$tag]['valueAttr'])))
        {
            $return = static::$config['tag'][$tag]['valueAttr'];

            if(is_array($return) && array_key_exists('default',$return))
            $return = (!empty($type) && array_key_exists($type,$return))? $return[$type]:$return['default'];
        }

        return $return;
    }


    // getOption
    // retourne le tableau d'option selon un tag
    // possibilité de mettre un type
    public static function getOption(string $tag,?string $type=null,?array $option=null):array
    {
        $return = [];
        $merge[] = static::$config['option'];

        if(!empty(static::$config['tag'][$tag]['option']))
        $merge[] = static::$config['tag'][$tag]['option'];

        if(is_string($type) && !empty(static::$config['tag'][$tag][$type]['option']))
        $merge[] = static::$config['tag'][$tag][$type]['option'];

        if(!empty($option))
        {
            $option = Obj::cast($option);

            if($tag === 'input' && array_key_exists('value',$option))
            $option['checked'] = $option['value'];

            if($tag === 'option' && array_key_exists('value',$option))
            $option['selected'] = $option['value'];

            foreach (['checked'=>'input','selected'=>'option'] as $k => $v)
            {
                if(array_key_exists($k,$option))
                {
                    if(!is_array($option[$k]))
                    {
                        if(is_string($option[$k]))
                        $option[$k] = Set::arr($option[$k]);

                        else
                        $option[$k] = [$option[$k]];
                    }

                    $option[$k] = Arr::cast($option[$k]);
                }
            }

            $merge[] = $option;
        }

        $return = Arrs::replace(...$merge);

        return $return;
    }


    // getValueCallable
    // retourne les callables de valeur du tag ou null si non existant
    public static function getValueCallable(string $tag):?callable
    {
        return (!empty(static::$config['tag'][$tag]['valueCallable']))? static::$config['tag'][$tag]['valueCallable']:null;
    }


    // getAttrCallable
    // retourne la callable attr du tag ou null si non existant
    public static function getAttrCallable(string $tag):?callable
    {
        return (!empty(static::$config['tag'][$tag]['attrCallable']))? static::$config['tag'][$tag]['attrCallable']:null;
    }


    // parseCallStatic
    // passe à travers le tableau en provenance de getCallStatic
    public static function parseCallStatic(array $array):array
    {
        $return = [];

        foreach ($array as $value)
        {
            if(is_string($value) && !empty($value))
            {
                $value = strtolower($value);

                if(in_array($value,static::$config['static']['special'],true))
                $return['special'] = $value;

                elseif(in_array($value,static::$config['static']['openClose'],true))
                $return['openClose'] = $value;

                elseif(!empty($return) && static::isInputType($value))
                $return['arg'][] = $value;

                else
                $return['tag'][] = $value;
            }
        }

        return $return;
    }


    // getCallStatic
    // retourne la méthode et les arguments à partir d'un tableau camelCase
    public static function getCallStatic($value,array $args=[]):array
    {
        $return = [];

        if(!is_array($value))
        $value = [$value];

        $value = static::parseCallStatic($value);
        $method = null;
        $arg = [];

        if(!empty($value))
        {
            if(!empty($value['tag']))
            {
                $count = count($value['tag']);
                $special = $value['special'] ?? null;

                if(!empty($special))
                {
                    $method = $special;

                    if(!empty($value['openClose']))
                    $method .= ucfirst($value['openClose']);

                    $arg[] = $value['tag'];

                    if($special === 'many')
                    $arg[] = null;
                }

                else
                {
                    if(!empty($value['openClose']) && in_array($value['openClose'],static::$config['static']['openClose'],true))
                    $method = $value['openClose'];

                    else
                    $method = 'make';

                    $arg[] = $value['tag'];
                }
            }

            if(!empty($method) && !empty($arg))
            {
                $return['method'] = $method;
                $return['arg'] = $arg;

                if(!empty($value['arg']))
                {
                    if($special === 'many')
                    {
                        foreach ($args as $k => $v)
                        {
                            $args[$k] = Arr::append($value['arg'],$args[$k]);
                        }
                    }

                    else
                    $args = Arr::append($value['arg'],$args);
                }

                if(!empty($args))
                $return['arg'] = Arr::append($arg,$args);
            }
        }

        return $return;
    }


    // callStatic
    // permet de générer des tag de façon dynamique
    public static function __callStatic(string $key,array $arg):?string
    {
        $return = null;
        $camelCase = null;
        $lower = strtolower($key);

        if($lower !== $key)
        $camelCase = Str::fromCamelCase($key);

        if(is_array($camelCase) && !empty($camelCase))
        $callable = static::getCallStatic($camelCase,$arg);
        else
        $callable = static::getCallStatic($key,$arg);

        if(!empty($callable['method']) && !empty($callable['arg']))
        $return = static::{$callable['method']}(...$callable['arg']);

        return $return;
    }


    // get
    // retourne la tag, gère les alias
    // si tag est un array, retourne la dernière tag
    public static function get($tag):?string
    {
        $return = null;

        if(!empty($tag))
        {
            if(is_array($tag))
            $tag = Arr::valueLast($tag);

            if(is_string($tag) && strpos($tag,'<') === false && strpos($tag,'>') === false)
            {
                if(static::isAlias($tag))
                $return = static::$config['alias'][$tag];

                else
                $return = strtolower($tag);
            }
        }

        return $return;
    }


    // changeLast
    // permet de changer le dernier tag d'un groupe de tag si existant
    // sinon retourne le tag dans un array, gère les alias
    public static function changeLast(string $tag,$tags):?array
    {
        $return = null;
        $tag = static::get($tag);

        if(!empty($tag))
        {
            $return = [$tag];

            if(is_array($tags))
            $return = Arr::spliceLast($tags,$return);
        }

        return $return;
    }


    // arg
    // ouvre et ferme une ou un groupe de tag à partir d'une valeur et un tableau d'argument
    // utile pour les callback
    public static function arg($value,$arg):string
    {
        return static::argOpen($value,$arg).static::argClose($arg);
    }


    // argOpen
    // ouvre une ou un groupe de tag à partir d'une valeur et un tableau d'argument
    // utile pour les callback
    public static function argOpen($value,$arg):string
    {
        $return = '';
        $explode = null;

        if(is_string($arg))
        {
            $explode = static::argStrExplode($arg);
            $arg = [$arg];
        }

        if(!empty($explode))
        {
            $return = $explode[0];
            $value = Obj::cast($value);
            $return .= Str::castFix($value,', ');
        }

        elseif(is_array($arg) && !empty($arg))
        {
            $tag = current($arg);

            $key = key($arg);
            unset($arg[$key]);

            $return = static::open($tag,$value,...array_values($arg));
        }

        return $return;
    }


    // argClose
    // ferme une ou un groupe de tag à partir d'une valeur et un tableau d'argument
    // utile pour les callback
    public static function argClose($arg):string
    {
        $return = '';
        $explode = null;

        if(is_string($arg))
        {
            $explode = static::argStrExplode($arg);
            $arg = [$arg];
        }

        if(!empty($explode))
        $return = $explode[1];

        elseif(is_array($arg) && !empty($arg))
        {
            $tag = current($arg);
            $return = static::close($tag);
        }

        return $return;
    }


    // argStrExplode
    // utilisé par les méthodes arg
    // explose une string au caractère % si entouré par une balise html
    public static function argStrExplode(string $value):?array
    {
        $return = null;

        foreach ([1=>'%<',2=>'>%'] as $key => $char)
        {
            $pos = strpos($value,$char);

            if($pos !== false)
            {
                $return = [];
                $return[] = substr($value,0,($pos + $key - 1));
                $return[] = substr($value,($pos + $key));

                break;
            }
        }

        return $return;
    }


    // make
    // ouvre et ferme une ou un groupe de tag, seul la dernière tag a les arguments
    // choisit dynamiquement la méthode d'ouverture et fermeture selon la tag
    public static function make($tags,...$arg):string
    {
        $return = '';
        $tags = (array) $tags;

        if(!empty($tags))
        {
            $value = static::open($tags,...$arg);
            if(!empty($value))
            {
                $return .= $value;
                $return .= static::close($tags,(is_array($opt = Arr::valueLast($arg)))? $opt:null);
            }
        }

        return $return;
    }


    // makes
    // permet de générer plusieurs tags ou groupe de tags
    public static function makes(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= static::make(...$value);
        }

        return $return;
    }


    // open
    // ouvre une ou plusieurs groupes de tags, seul la dernière tag a les arguments -> sauf si la combinaison des tags avec open a une méthode défini
    // choisit dynamiquement la méthode d'ouverture selon la tag
    public static function open($tags,...$arg):string
    {
        $return = '';
        $tags = (array) $tags;

        if(count($tags) > 1 && method_exists(static::class,($method = implode('',$tags).'Open')))
        $return = static::$method(...$arg);

        elseif(!empty($tags))
        {
            $count = (count($tags) - 1);
            foreach ($tags as $i =>$tag)
            {
                $tag = static::get($tag);

                if(!empty($tag))
                {
                    $method = $tag.'Open';

                    if(method_exists(static::class,$method))
                    {
                        if($i === $count)
                        $return .= static::$method(...$arg);
                        else
                        $return .= static::$method();
                    }

                    else
                    {
                        if($i === $count)
                        $return .= static::start($tag,...$arg);
                        else
                        $return .= static::start($tag);
                    }
                }
            }
        }

        return $return;
    }


    // opens
    // permet d'ouvrir plusieurs tags ou groupe de tags
    public static function opens(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= static::open(...$value);
        }

        return $return;
    }


    // op
    // ouvre une ou plusieurs groupes de tags, seul la dernière tag a les arguments
    // choisit dynamiquement la méthode d'ouverture selon la tag
    // la valeur est toujours null, donc le premier argument est attr
    public static function op($tags,...$arg):string
    {
        return static::open($tags,null,...$arg);
    }


    // ops
    // permet d'ouvrir plusieurs tags ou groupe de tags
    // les valeurs sont toujours null, donc le premier argument est attr
    public static function ops(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= static::op(...$value);
        }

        return $return;
    }


    // close
    // ferme une tag ou groupe de tag
    // choisit dynamiquement la méthode de fermeture selon la tag
    public static function close($tags,?array $option=null):string
    {
        $return = '';
        $tags = (array) $tags;

        if(count($tags) > 1 && method_exists(static::class,($method = implode('',$tags).'Close')))
        $return = static::$method($option);

        elseif(!empty($tags))
        {
            $count = (count($tags) - 1);

            if($count > 0)
            $tags = array_reverse($tags);

            foreach ($tags as $i => $tag)
            {
                $tag = static::get($tag);

                if(!empty($tag))
                {
                    $method = $tag.'Close';

                    if(method_exists(static::class,$method))
                    $return .= static::$method($option);
                    else
                    $return .= static::end($tag,$option);
                }
            }
        }

        return $return;
    }


    // closes
    // permet de fermer plusieurs tags ou groupe de tag
    public static function closes(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= static::close(...$value);
        }

        return $return;
    }


    // cl
    // alias de close
    public static function cl($tags,?array $option=null):string
    {
        return static::close($tags,$option);
    }


    // cls
    // alias de closes
    public static function cls(...$array):string
    {
        return static::closes(...$array);
    }


    // loop
    // méthode pour générer des tags identiques à partir d'un tableau
    // les attributs et options sont partagés
    public static function loop($tags,array $values,$attr=null,?array $option=null):string
    {
        $return = '';

        foreach ($values as $value)
        {
            $return .= static::make($tags,$value,$attr,$option);
        }

        return $return;
    }


    // many
    // permet de générer plusieurs tags ou groupes de tags du même type
    // possible de mettre un séparateur entre chaque
    public static function many($tags,?string $separator=null,...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= (strlen($return) && is_string($separator))? $separator:'';
            $return .= static::make($tags,...$value);
        }

        return $return;
    }


    // manyOpen
    // permet d'ouvrir plusieurs tags ou groupes de tags du même type
    // possible de mettre un séparateur entre chaque
    public static function manyOpen($tags,?string $separator=null,...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= (strlen($return) && is_string($separator))? $separator:'';
            $return .= static::open($tags,...$value);
        }

        return $return;
    }


    // manyClose
    // permet de fermer plusieurs tags ou groupe de tags du même type
    // possible de mettre un séparateur entre chaque
    public static function manyClose($tags,?string $separator=null,...$array):string
    {
        $return = '';
        $count = count($array);

        if($count > 0)
        {
            for ($i=0; $i < $count; $i++)
            {
                $return .= (strlen($return) && is_string($separator))? $separator:'';
                $return .= static::close($tags);
            }
        }

        return $return;
    }


    // cond
    // ouvre et ferme un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    public static function cond($tags,$value='',...$arg):string
    {
        $return = static::condOpen($tags,$value,...$arg);

        if(!empty($return))
        $return .= static::condClose($tags,$value,(is_array($opt = Arr::valueLast($arg)))? $opt:null);

        return $return;
    }


    // condOpen
    // ouvre un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    public static function condOpen($tags,$value='',...$arg):string
    {
        $return = '';
        $tag = static::get($tags);

        if(!empty($tag) && static::value($value,$tag,static::getAttr($tag),static::getOption($tag)) !== '')
        $return = static::open($tags,$value,...$arg);

        return $return;
    }


    // condClose
    // ferme un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    public static function condClose($tags,$value='',?array $option=null):string
    {
        $return = '';
        $tag = static::get($tags);

        if(!empty($tag) && static::value($value,$tag,static::getAttr($tag),static::getOption($tag)) !== '')
        $return = static::close($tags,$option);

        return $return;
    }


    // or
    // ouvre et ferme un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    public static function or($tags,$href,$value='',...$arg):string
    {
        $href = Obj::cast($href);
        return (Uri::is($href))? static::make(static::changeLast('a',$tags),$href,$value,...$arg):static::make($tags,$value,...$arg);
    }


    // orOpen
    // ouvre un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    public static function orOpen($tags,$href,$value='',...$arg):string
    {
        $href = Obj::cast($href);
        return (Uri::is($href))? static::open(static::changeLast('a',$tags),$href,$value,...$arg):static::open($tags,$value,...$arg);
    }


    // orClose
    // ferme un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    public static function orClose($tags,$href,...$arg):string
    {
        $return = '';
        $option = (is_array($opt = Arr::valueLast($arg)))? $opt:null;
        $href = Obj::cast($href);

        if(Uri::is($href))
        $return = static::close(static::changeLast('a',$tags),$option);
        else
        $return = static::close($tags,$option);

        return $return;
    }


    // metaAttr
    // méthode protégé
    // attributs initiaux pour tag meta
    protected static function metaAttr(string $type,array $return,$value,array $option)
    {
        if($type === 'initial')
        {
            if(is_string($return) && strlen($return))
            {
                $key = (static::isMetaProperty($return))? 'property':'name';
                $return = [$key=>$return];
            }

            elseif(is_array($return))
            {
                if(array_key_exists('name',$return) && static::isMetaProperty($return['name']))
                {
                    $return['property'] = $return['name'];
                    unset($return['name']);
                }

                if(array_key_exists('property',$return) && !static::isMetaProperty($return['property']))
                {
                    $return['name'] = $return['property'];
                    unset($return['property']);
                }
            }
        }

        return $return;
    }


    // formAttr
    // attributs initiaux pour tag form
    // méthode protégé
    protected static function formAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        {
            if(!empty($option['method']))
            {
                $method = $option['method'];

                if(!array_key_exists('method',$return))
                $return['method'] = $method;

                $return['method'] = strtolower($return['method']);
            }

            if(empty($return['enctype']) && !empty($option['enctype'][$return['method']]))
            $return['enctype'] = $option['enctype'][$return['method']];
        }

        return $return;
    }


    // formInitialNameAttr
    // méthode protégé utilisé par inputAttr, textareaAttr, selectAttr et buttonAttr
    // si name est true, donne un nom au hasard et wrap le de -
    protected static function formInitialNameAttr(array $return,bool $multi=false)
    {
        if(array_key_exists('name',$return))
        {
            if($return['name'] === true)
            {
                $wrap = static::$config['randomNameWrap'] ?? null;
                $return['name'] = Attr::randomId();

                if(is_string($wrap))
                $return['name'] = $wrap.$return['name'].$wrap;
            }

            if($multi === true && !static::isNameMulti($return['name']))
            $return['name'] .= static::$config['multi'];
        }

        return $return;
    }


    // inputAttr
    // méthode protégé
    // génère les attributs initiaux et finaux pour input
    protected static function inputAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        {
            $return = static::formInitialNameAttr($return,$option['multi'] ?? false);

            if(array_key_exists('checked',$option) && is_array($option['checked']) && is_scalar($value))
            {
                if(in_array(Scalar::cast($value),$option['checked'],true))
                $return['checked'] = true;
            }
        }

        elseif($type === 'final')
        {
            if(array_key_exists('type',$return))
            {
                if(in_array($return['type'],['hidden','file'],true) && array_key_exists('maxlength',$return))
                unset($return['maxlength']);

                if($return['type'] === 'image')
                $return['alt'] = static::alt($return['alt'] ?? null,$return['src'] ?? null);
            }
        }

        return $return;
    }


    // textareaAttr
    // méthode protégé
    // attributs initiaux pour textarea
    protected static function textareaAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = static::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // buttonAttr
    // méthode protégé
    // attributs initiaux pour button
    protected static function buttonAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = static::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // selectAttr
    // méthode protégé
    // attributs initiaux pour textarea
    protected static function selectAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = static::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // optionAttr
    // méthode protégé
    // attributs initiaux pour option
    protected static function optionAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        {
            if(!array_key_exists('value',$return))
            $return['value'] = (is_scalar($value))? $value:null;

            if(array_key_exists('selected',$option) && is_array($option['selected']) && is_scalar($value))
            {
                if(in_array(Scalar::cast($return['value']),$option['selected'],true))
                $return['selected'] = true;
            }
        }

        return $return;
    }


    // imgAttr
    // méthode protégé
    // génère les attributs initiaux et finaux pour img
    protected static function imgAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        {
            if(empty($return['alt']) && is_resource($value))
            {
                $filename = Res::filename($value);
                if(is_string($filename))
                $return['alt'] = $filename;
            }
        }

        if($type === 'final')
        {
            $src = null;

            if(is_string($return['src']) && !Attr::isDataUri($return['src']))
            $src = $return['src'];

            elseif(is_string($value))
            $src = $value;

            $return['alt'] = static::alt($return['alt'] ?? null,$src);
        }

        return $return;
    }


    // value
    // méthode protégé
    // prépare la valeur avant l'écriture dans la tag
    // possible que la tag soit lié à un callback pour préparer la valeur
    // si value est toujours true après value callable, remplace par &nbsp ou espace
    // value est cast en string, et les forbiddenCodePoint sont retirés
    protected static function value($value,string $tag,array $attr,array $option,bool $isAttr=false):?string
    {
        $return = null;
        $value = Obj::cast($value);

        if(is_string($tag))
        {
            $callable = static::getValueCallable($tag);

            if(!empty($callable))
            $value = $callable($value,$attr,$option);
        }

        if($value === true)
        $value = ($isAttr === true)? '1':'&nbsp;';

        elseif($value === false)
        $value = ($isAttr === true)? '0':'';

        if($isAttr === false || $value !== null)
        {
            $return = Str::castFix($value,', ');

            if(!empty($option['encode']))
            $return = static::encode($return,...(array) $option['encode']);
        }

        return $return;
    }


    // metaValue
    // méthode protégé
    // fonction de callback pour la valeur de la tag meta
    protected static function metaValue($return,array $attr,array $option)
    {
        $key = null;

        if(array_key_exists('name',$attr))
        $key = $attr['name'];

        elseif(array_key_exists('property',$attr))
        $key = $attr['property'];

        if($key === 'charset' && (empty($return) || !is_string($return)))
        $return = Encoding::getCharset();

        elseif($key === 'og:title')
        $return = static::titleValue($return,$option);

        elseif(in_array($key,['description','og:description'],true))
        $return = static::metaDescriptionValue($return,$option);

        elseif($key === 'keywords')
        $return = static::metaKeywordsValue($return,$option);

        elseif(is_string($key) && static::isMetaUri($key))
        $return = static::metaUriValue($return,$option);

        return $return;
    }


    // imgValue
    // méthode protégé
    // fonction de callback pour la valeur de la tag img
    protected static function imgValue($return,array $attr,array $option)
    {
        if(array_key_exists('base64',$option) && $option['base64'] === true)
        {
            $res = null;

            if(is_string($return))
            {
                $schemeHost = Request::schemeHost();
                $path = Finder::uriToPath($return,$schemeHost);

                if(!empty($path))
                $res = Res::open($path);
            }

            elseif(is_resource($return))
            $res = $return;

            if(!empty($res))
            $return = Res::base64($res);
        }

        elseif(is_resource($return))
        $return = Res::pathToUriOrBase64($return);

        return $return;
    }


    // scriptValue
    // méthode protégé
    // fonction de callback pour la valeur de la tag script
    protected static function scriptValue($return,array $attr,array $option)
    {
        if(is_array($return) || is_object($return))
        $return = Json::encode($return,$option['json'] ?? null);

        if(is_scalar($return) && !empty($option['var']))
        $return = Json::var($option['var'],$return);

        return $return;
    }


    // formValue
    // méthode protégé
    // note: form n'a pas de valeur ajoutable via formOpen -> ca va dans action
    // fonction de callback pour la valeur de la tag form
    // csrf et genuine s'ajoute automatiquement si true ou via la méthode
    protected static function formValue($return,array $attr,array $option):string
    {
        $return = '';
        $method = $attr['method'];

        // genuine
        if(!empty($option['genuine']))
        {
            if($option['genuine'] === true || (is_array($option['genuine']) && !empty($option['genuine'][$method])))
            $return .= static::genuine();
        }

        // csrf
        if(!empty($option['csrf']))
        {
            if($option['csrf'] === true || (is_array($option['csrf']) && !empty($option['csrf'][$method])))
            {
                $csrf = Session::csrf();
                $return .= static::csrf($csrf);
            }
        }

        return $return;
    }


    // selectValue
    // méthode protégé
    // fonction de callback pour la valeur de la tag select
    protected static function selectValue($value,array $attr,array $option):string
    {
        $return = '';
        $title = $option['title'] ?? null;

        if($title === true)
        $title = '';

        if(is_string($title))
        $return .= static::option($title,'');

        if($value === true || is_int($value))
        $value = static::getBool($value);

        if(is_array($value))
        $return .= static::options($value,$option);

        else
        $return .= Str::cast($value);

        return $return;
    }


    // headValue
    // méthode protégé
    // fonction de callback pour la valeur de la tag head
    protected static function headValue($return,array $attr,array $option)
    {
        if(is_array($return))
        {
            $return = static::headFromArray($return);

            if(array_key_exists('separator',$option) && is_string($option['separator']) && !empty($return))
            $return = $option['separator'].$return.$option['separator'];
        }

        return $return;
    }


    // titleDescriptionKeywordsValue
    // méthode protégé pour générer la valeur de meta title, description et keywords
    protected static function titleDescriptionKeywordsValue($value,array $option):?string
    {
        $return = null;
        $maxLength = $option['maxLength'] ?? null;

        if(!empty($option['separator']))
        {
            if(is_scalar($value))
            $value = [Str::castFix($value)];

            if(is_array($value))
            {
                $value = Arr::trimClean($value);
                if(!empty($option['case']))
                $value = Arr::map([Str::class,$option['case']],$value,true);

                $return = implode($option['separator'],$value);
            }

            if(is_string($return) && strlen($return))
            $return = static::excerptStrSuffix($maxLength,$return,$option['excerpt'] ?? null);
        }

        return $return;
    }


    // titleValue
    // prépare la valeur pour la tag title ou meta title
    public static function titleValue($value,?array $option=null):?string
    {
        return static::titleDescriptionKeywordsValue($value,static::getOption('title',null,$option));
    }


    // metaDescriptionValue
    // prépare la valeur pour la tag meta description
    public static function metaDescriptionValue($value,?array $option=null):?string
    {
        return static::titleDescriptionKeywordsValue($value,static::getOption('meta','description',$option));
    }


    // metaKeywordsValue
    // prépare la valeur pour la tag meta keywords
    public static function metaKeywordsValue($value,?array $option=null):?string
    {
        return static::titleDescriptionKeywordsValue($value,static::getOption('meta','keywords',$option));
    }


    // metaUriValue
    // prépare la valeur pour une tag meta uri
    public static function metaUriValue($value,?array $option=null):?string
    {
        return (is_string($value))? Uri::output($value,static::getOption('meta','uri',$option)):null;
    }


    // div
    // raccourci pour ouvrir et fermer une div
    public static function div($value='',$attr=null,?array $option=null):string
    {
        return static::start('div',$value,$attr,$option).static::end('div',$option);
    }


    // span
    // raccourci pour ouvrir et fermer une span
    public static function span($value='',$attr=null,?array $option=null):string
    {
        return static::start('span',$value,$attr,$option).static::end('span',$option);
    }


    // start
    // ouvre une tag html
    // les self-closing tag se ferme dans le open
    // méthode de base, ne fait pas appel au méthode dynamique selon la tag
    public static function start(string $tag,$value=null,$attr=null,?array $option=null):string
    {
        $return = '';
        $tag = static::get($tag);

        if(static::isWrap($tag))
        $return = static::wrapOpen($tag,$value);

        elseif(!empty($tag))
        {
            $attr = static::getAttr($tag,null,$attr);
            $type = ($typeAttr = static::getTypeAttr($tag))? $attr[$typeAttr] ?? null:null;
            $option = static::getOption($tag,$type,$option);
            $callable = static::getAttrCallable($tag);

            if(!empty($callable))
            $attr = $callable('initial',$attr,$value,$option);

            $valueAttr = static::getValueAttr($tag,$attr['type'] ?? null);
            if(!empty($valueAttr))
            $attr[$valueAttr] = static::value($value,$tag,$attr,$option,true);
            else
            $value = static::value($value,$tag,$attr,$option);

            if(!empty($option['label']))
            $attr['id'] = Attr::randomId($attr['name'] ?? null);

            if(!empty($callable))
            $attr = $callable('final',$attr,$value,$option);

            $attrStr = '';
            if(!empty($attr))
            {
                $optionAttr = $option['attr'] ?? null;

                $attrStr = Attr::str($attr,$optionAttr);
                if(!empty($attrStr))
                $attrStr = ' '.$attrStr;
            }

            if(static::isSelfClosing($tag))
            $return = "<$tag".$attrStr.'/>';

            else
            {
                $return = "<$tag".$attrStr.'>';

                if(empty($valueAttr))
                {
                    if(!empty($option['excerptMin']) && is_int($option['excerptMin']) && strlen($value) > $option['excerptMin'])
                    $value = static::excerpt($option['excerptMin'],$value);

                    $return .= $value;
                }
            }
        }

        if(is_array($option))
        {
            if(array_key_exists('label',$option) && !in_array($option['label'],['',null],true))
            $return = static::makeLabel($option['label'],$return,$attr['id'] ?? null,$option['position'] ?? null);

            if(!empty($option['html']))
            {
                $open = static::argOpen($return,$option['html']);
                $return = (strlen($open))? $open:$return;
            }

            if(!empty($option['conditional']))
            {
                $arg = (is_array($option['conditional']))? $option['conditional']:[];
                $return = static::conditionalCommentsOpen(...$arg).$return;
            }
        }

        return $return;
    }


    // end
    // ferme une tag html
    // n'a aucun effet sur les self-closing tag
    // méthode de base, ne fait pas appel au méthode dynamique selon la tag
    public static function end(string $tag,?array $option=null):string
    {
        $return = '';
        $tag = static::get($tag);

        if(static::isWrap($tag))
        $return = static::wrapClose($tag);

        elseif(!empty($tag) && !static::isSelfClosing($tag))
        $return = "</$tag>";

        if(!empty($option['html']))
        $return .= static::argClose($option['html']);

        if(!empty($option['conditional']))
        {
            $arg = (is_array($option['conditional']))? Arr::valueLast($option['conditional']):false;
            $arg = (!is_bool($arg))? false:$arg;
            $return .= static::conditionalCommentsClose($arg);
        }

        return $return;
    }


    // metaCharset
    // génère un tag metaCharset
    public static function metaCharset($value=null,$attr=null,?array $option=null):string
    {
        return static::meta($value,Arr::plus($attr,['name'=>'charset']),$option);
    }


    // metaDescription
    // génère un tag meta description
    public static function metaDescription($value,$attr=null,?array $option=null):string
    {
        return static::meta($value,Arr::plus($attr,['name'=>'description']),$option);
    }


    // metaKeywords
    // génère un tag meta keywords
    public static function metaKeywords($value,$attr=null,?array $option=null):string
    {
        return static::meta($value,Arr::plus($attr,['name'=>'keywords']),$option);
    }


    // metaOg
    // génère un tag meta og
    public static function metaOg($value,string $name,$attr=null,?array $option=null):string
    {
        return static::meta($value,Arr::plus($attr,['property'=>'og:'.$name]),$option);
    }


    // cssOpen
    // ouvre un tag css -> link
    public static function cssOpen(string $value,$attr=null,?array $option=null):string
    {
        return static::linkOpen($value,Arr::plus($attr,['rel'=>'stylesheet']),$option);
    }


    // cssClose
    // ferme une tag css -> link
    public static function cssClose(?array $option=null):string
    {
        return static::linkClose($option);
    }


    // scriptOpen
    // ouvre un tag script
    // possible de spécifier une valeur pour transférer la variable php en javascript
    public static function scriptOpen($value=null,?string $var=null,$attr=null,?array $option=null):string
    {
        return static::start('script',$value,$attr,Arr::plus($option,['var'=>$var]));
    }


    // jsOpen
    // fait un tag js -> script
    public static function jsOpen(string $value,$attr=null,?array $option=null):string
    {
        return static::scriptOpen(null,null,Arr::plus(['src'=>$value],$attr),$option);
    }


    // jsClose
    // ferme une tag js -> script
    public static function jsClose(?array $option=null):string
    {
        return static::scriptClose($option);
    }


    // aOpen
    // ouvre un tag a
    public static function aOpen($href=null,$title=null,$attr=null,?array $option=null):string
    {
        return static::start('a',($title === true)? $href:$title,Arr::plus(static::getAttrScalar('a',$attr),['href'=>$href]),$option);
    }


    // imgOpen
    // ouvre un tag img
    public static function imgOpen($src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return static::start('img',$src,Arr::plus($attr,['alt'=>$alt]),$option);
    }


    // aImgOpen
    // ouvre un tag a avec une image à l'intérieur
    // note: à la différence des autres méthodes les attributs et options sont appliqués au tag A et non pas le dernier, IMG
    public static function aImgOpen($href=null,$src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return static::aOpen($href,static::imgOpen($src,$alt),$attr,$option);
    }


    // alt
    // prépare l'attribut alt en fonction de la valeur ou de la src
    public static function alt($value,$src=null):?string
    {
        $return = null;

        if(is_string($value) && !empty($value))
        $return = $value;

        elseif(is_string($src) && !empty($src))
        {
            $filename = Path::filename($src);
            if(!empty($filename))
            $return = $filename;
        }

        return $return;
    }


    // img64
    // ouvre une tag img, le contenu est forcé en base64
    public static function img64($src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return static::img($src,$alt,$attr,Arr::plus($option,['base64'=>true]));
    }


    // tableOpen
    // ouvre une table avec thead, tbody et tfoot à l'intérieur
    // si option strict est true, affiche seulement si toutes les rangés ont le même nombre de colonne
    public static function tableOpen(?array $thead=null,?array $tbody=null,?array $tfoot=null,$attr=null,?array $option=null):string
    {
        $return = '';
        $option = static::getOption('table',null,$option);
        $table = '';
        $make = true;

        if($option['strict'] === true)
        {
            $rows = [];

            if(!empty($thead))
            $rows[] = $thead;

            if(!empty($tbody))
            $rows = Arr::append($rows,$tbody);

            if(!empty($tfoot))
            $rows[] = $tfoot;

            $make = static::tableSameCount(...$rows);
        }

        if($make === true)
        {
            if(!empty($thead))
            $table .= static::thead(...$thead);

            if(!empty($tbody) && Column::is($tbody))
            $table .= static::tbody(...$tbody);

            if(!empty($tfoot))
            $table .= static::tfoot(...$tfoot);

            $return = static::start('table',$table,$attr,$option);
        }

        return $return;
    }


    // tableStrict
    // ouvre et ferme une table seulement si toutes les rangées ont le même nombre de colonne
    public static function tableStrict(?array $thead=null,?array $tbody=null,?array $tfoot=null,$attr=null,?array $option=null):string
    {
        return static::table($thead,$tbody,$tfoot,$attr,Arr::plus($option,['strict'=>true]));
    }


    // theadOpen
    // ouvre un thead et une tr avec des th
    public static function theadOpen(array $value,$attr=null,?array $option=null):string
    {
        return static::start('thead',static::trThOpen($value,$attr,$option));
    }


    // theadClose
    // ferme une thead et la tr
    public static function theadClose():string
    {
        return static::end('tr').static::end('thead');
    }


    // tbodyOpen
    // ouvre un tbody et ouvre/ferme plusieurs tr avec td
    public static function tbodyOpen(array ...$value):string
    {
        $return = static::start('tbody');

        foreach ($value as $v)
        {
            $return .= static::tr(...$v);
        }

        return $return;
    }


    // tbodyStrict
    // ouvre un tbody et ouvre/ferme plusieurs tr avec td seulement si toutes les rangées ont le même nombre de colonnes
    public static function tbodyStrict(array ...$value):string
    {
        $return = '';
        $sameCount = static::tableSameCount(...$value);

        if($sameCount === true)
        $return = static::tbody(...$value);

        return $return;
    }


    // trOpen
    // ouvre un tr avec plusieurs td
    public static function trOpen(array $value,$attr=null,?array $option=null):string
    {
        return static::start('tr',static::many('td',null,...$value),$attr,$option);
    }


    // trThOpen
    // ouvre un tr avec plusieurs th
    public static function trThOpen(array $value,$attr=null,?array $option=null):string
    {
        return static::start('tr',static::many('th',null,...$value),$attr,$option);
    }


    // tfootOpen
    // ouvre un tfoot et une tr avec des td
    public static function tfootOpen(array $value,$attr=null,?array $option=null):string
    {
        return static::start('tfoot',static::trOpen($value,$attr,$option));
    }


    // tfootClose
    // ferme une tfoot et la tr
    public static function tfootClose():string
    {
        return static::end('tr').static::end('tfoot');
    }


    // tableSameCount
    // retourne vrai si les lignes de la table ont toutes le même count
    public static function tableSameCount(...$values):bool
    {
        $return = false;
        $count = null;
        $sameCount = true;

        foreach ($values as $key => $value)
        {
            $current = current($value);

            if(is_array($current))
            {
                if($count === null)
                {
                    $return = true;
                    $count = count($current);
                }

                else
                $return = ((count($current) === $count))? true:false;
            }

            if($return === false)
            break;
        }

        return $return;
    }


    // formOpen
    // ouvre un tag form
    // note: pas de valeur, mais csrf et genuine peuvent s'ajouter automatiquement à la valeur si les options sont activés
    public static function formOpen($action=null,$attr=null,?array $option=null):string
    {
        return static::start('form',null,Arr::plus(static::getAttrScalar('form',$attr),['action'=>$action]),$option);
    }


    // inputOpen
    // ouvre un tag input
    // à la différence de plusieurs autres tags, le type est avant la valeur
    // cette méthode est utilisé pour générer un input via un inputMethod du genre inputEmail
    public static function inputOpen(string $type='text',$value='',$attr=null,?array $option=null):string
    {
        return static::start('input',$value,static::getAttr('input',$type,$attr),static::getOption('input',$type,$option));
    }


    // buttonOpen
    // ouvre un tag button, le type du button est button donc ne soumet pas le formulaire
    public static function buttonOpen($value=null,$attr=null,?array $option=null):string
    {
        return static::start('button',$value,static::getAttr('button','button',$attr),static::getOption('button','button',$option));
    }


    // submitOpen
    // ouvre un tag button, le type du button est submit donc soumet le formulaire
    public static function submitOpen($value=null,$attr=null,?array $option=null):string
    {
        return static::start('button',$value,static::getAttr('button','submit',$attr),static::getOption('button','submit',$option));
    }


    // submitClose
    // ferme un tag button submit
    public static function submitClose(array $option=null):string
    {
        return static::end('button',$option);
    }


    // inputMaxFilesize
    // fait un input input hidden max file size, en lien avec inputFile
    public static function inputMaxFilesize($value='',$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === null)
        $value = Ini::uploadMaxFilesize(1);

        if(is_string($value))
        $value = Number::fromSizeFormat($value);

        if(is_int($value))
        {
            $attr = static::getAttr('input','hidden',$attr);
            $attr['name'] = 'MAX_FILE_SIZE';
            $return .= static::inputHidden($value,$attr,$option);
        }

        return $return;
    }


    // makeLabel
    // construit un label
    // si attr est scalar c'est for
    // position 1 = before, position 2 = after, sinon c'est wrap
    // si array est de longeur 2, la deuxième valeur est un span à ajouter après le label
    // le span est un élément après le label, mais hors du label
    public static function makeLabel($label,string $value,$attr=null,$position=1,?array $option=null):string
    {
        $return = '';
        $span = null;

        if(is_array($label) && count($label) === 2)
        {
            $span = Arr::valueLast($label);
            $span = static::spanCond($span);
            $label = current($label);
        }

        $label = Obj::cast($label);
        $label = Str::cast($label);

        if(is_string($label) && strlen($label))
        {
            if(in_array($position,[1,'before'],true))
            $return = static::label($label,$attr,$option).$span.$value;

            elseif(in_array($position,[2,'after'],true))
            $return = $value.static::label($label,$attr,$option).$span;

            else
            {
                $label = $label.$value;
                $return = static::label($label,$attr,$option).$span;
            }
        }

        return $return;
    }


    // formWrap
    // permet de générer un label et un élément de formulaire et de les insérer dans un formWrap
    // value sera envoyé dans make
    // un id sera généré et ajouté à value, il sera envoyé à formWrapStr pour l'ajouter comme for au label
    // note: un id n'est pas généré pour les tags de relation
    // les formWrap sont définis dans les config, par défaut utilise le premier
    // si la value de l'élément de formulaire n'est pas spécifié, utilise null
    // possibilité de spécifier des clés de remplacement additionnelles
    // pour les input, il faut utiliser une méthode input genre inputText
    public static function formWrap($label,$value,?string $wrap=null,?array $replace=null,$id=null):string
    {
        $return = '';

        if(!empty($label) && !empty($value))
        {
            $value = array_values((array) $value);
            $method = $value[0];
            unset($value[0]);
            $value = array_values($value);
            $tag = static::getFormTagFromMethod($method) ?? $method;

            if(!array_key_exists(0,$value))
            $value[0] = null;

            if(array_key_exists(1,$value) && !is_array($value[1]))
            $value[1] = static::getAttrScalar($tag,$value[1]);

            $name = (!empty($value[1]['name']))? $value[1]['name']:null;

            if(in_array($id,[null,true],true) && !static::isRelationTag($tag))
            $id = Attr::randomId($name ?? null);

            if(is_string($id))
            $value[1]['id'] = $id;

            $value = static::$method(...$value);

            if(is_string($value))
            $return = static::formWrapStr($label,$value,$wrap,$replace,$id);
        }

        return $return;
    }


    // formWrapStr
    // permet de générer un label et un élément de formulaire et de les insérer dans un formWrap
    // value doit être une string qui a déjà été généré
    // le id doit doit être fourni sous forme de string et sera automatiquement ajouté à label
    // les formWrap sont définis dans les config, par défaut utilise le premier
    // possibilité de spécifier des clés de remplacement additionnelles
    // si wrap est null, prend la première clé
    public static function formWrapStr($label,string $value,?string $wrap=null,?array $replace=null,$id=null):string
    {
        $return = '';

        if($wrap === null)
        $wrap = key(static::$config['formWrap']);

        if(!empty($wrap))
        {
            $label = array_values((array) $label);

            if(array_key_exists(1,$label) && !is_array($label[1]))
            $label[1] = static::getAttrScalar('label',$label[1]);
            if(is_string($id))
            $label[1]['for'] = $id;
            $label = static::label(...$label);

            if(is_string($label))
            {
                $replace = (array) $replace;
                $formWrap = (array_key_exists($wrap,static::$config['formWrap']))? static::$config['formWrap'][$wrap]:$wrap;
                $replace['label'] = $label;
                $replace['form'] = $value;
                $replace = Arr::keysWrap('%','%',$replace);

                $return = Str::replace($replace,$formWrap);
            }
        }

        return $return;
    }


    // formWrapArray
    // retourne un élément de formulaire à partir d'un tableau
    // le tableau peut contenir type, label, description required et choices
    // renvoie à formWrap str
    public static function formWrapArray($value,array $array,?string $wrap=null,$attr=null,?array $replace=null,$id=null,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['descriptionClass'=>'description','requiredClass'=>'required','choiceClass'=>'choice'],$option);

        if(!empty($array))
        {
            $attr = (array) $attr;
            $replace = (array) $replace;
            $type = $array['type'] ?? 'inputText';
            $required = (!empty($array['required']))? true:false;
            $label = $array['label'] ?? null;
            $replace['description'] = $array['description'] ?? null;
            $isRelation = static::isRelationTag($type);
            $htmlLabel = '';
            $htmlForm = '';

            if(in_array($id,[null,true],true) && !static::isRelationTag($type))
            {
                $slug = (!empty($label))? Slug::str($label):null;
                $id = Attr::randomId($slug);
            }
            if(is_string($id))
            $attr['id'] = $id;

            if($required === true)
            $attr['data-required'] = true;

            if(is_string($label))
            {
                $htmlLabel .= $label;

                if($required === true)
                $htmlLabel .= self::span('*',$option['requiredClass']);
            }

            if(!empty($replace['description']))
            $replace['description'] = static::divCond($replace['description'],$option['descriptionClass']);

            if($isRelation === true)
            {
                $choices = $array['choices'] ?? [];
                $option['value'] = $value;

                if(in_array($type,['select','multiselect'],true) && !array_key_exists('title',$option))
                $option['title'] = true;

                elseif(in_array($type,['radio','checkbox'],true))
                {
                    if(!array_key_exists('autoHidden',$option))
                    $option['autoHidden'] = true;

                    if(!array_key_exists('html',$option))
                    $option['html'] = ['div',$option['choiceClass']];
                }

                $htmlForm .= static::$type($choices,$attr,$option);
            }

            else
            $htmlForm .= static::$type($value,$attr,$option);

            $return .= static::formWrapStr($htmlLabel,$htmlForm,$wrap,$replace,$id);
        }

        return $return;
    }


    // hidden
    // génère un ou une série de input hiddens
    // n'affiche rien si attr est null
    public static function hidden($value,$attr=null,?array $option=null):string
    {
        $return = '';

        if(!is_array($value))
        $value = [$value];

        if($attr !== null)
        {
            foreach ($value as $v)
            {
                $return .= static::inputHidden($v,$attr,$option);

                if(empty($option['multi']))
                break;
            }
        }

        return $return;
    }


    // autoHidden
    // génère le input hidden pour les checkbox ou radio
    // méthode protégé
    protected static function autoHidden($attr=null,?array $option=null):string
    {
        $return = '';
        $autoAttr = Arr::plus($attr,['type'=>'hidden','id'=>null,'data-required'=>null]);
        $autoOpt = Arr::plus($option,['html'=>null,'multi'=>false]);
        $return .= static::inputHidden(null,$autoAttr,$autoOpt);

        return $return;
    }


    // radio
    // alias de radios
    public static function radio($value,$attr=null,?array $option=null):string
    {
        return static::radios($value,$attr,$option);
    }


    // radios
    // construit une série de bouton radio avec un tableau valeur => label
    // si value est int ou true, utilise une relation bool
    // si attr est scalar c'est name
    public static function radios($value,$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = static::getBool($value);

        $option = static::getOption('input','radio',$option);
        $attr = static::getAttr('input','radio',$attr);

        if(!empty($option['autoHidden']))
        $return .= static::autoHidden($attr,$option);

        if(is_array($value))
        {
            foreach ($value as $val => $label)
            {
                $return .= static::inputRadio($val,$attr,Arr::plus($option,['label'=>$label]));
            }
        }

        return $return;
    }


    // radiosWithHidden
    // construit une série de bouton radio avec un tableau valeur => label
    // un champ hidden est ajouté au début du html
    public static function radiosWithHidden($value,$attr=null,?array $option=null):string
    {
        return static::radios($value,$attr,Arr::plus($option,['autoHidden'=>true]));
    }


    // checkbox
    // alias de checkboxes
    public static function checkbox($value,$attr=null,?array $option=null):string
    {
        return static::checkboxes($value,$attr,$option);
    }


    // checkboxes
    // construit une série de checkbox avec un tableau valeur => label
    // si value est int ou true, utilise une relation bool
    // si attr est scalar c'est name
    // le nom multi est ajouté automatiquement
    public static function checkboxes($value,$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = static::getBool($value);

        $option = static::getOption('input','checkbox',$option);
        $attr = static::getAttr('input','checkbox',$attr);

        if(!empty($option['autoHidden']))
        $return .= static::autoHidden($attr,$option);

        if(is_array($value))
        {
            foreach ($value as $val => $label)
            {
                $return .= static::inputCheckbox($val,$attr,Arr::plus($option,['label'=>$label]));
            }
        }

        return $return;
    }


    // checkboxesWithHidden
    // construit une série de checkbox un tableau valeur => label
    // un champ hidden est ajouté au début du html
    public static function checkboxesWithHidden($value,$attr=null,?array $option=null):string
    {
        return static::checkboxes($value,$attr,Arr::plus($option,['autoHidden'=>true]));
    }


    // options
    // construit une série d'option de select avec un tableau attr => label
    // si attr est scalar c'est value
    public static function options($value,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = static::getBool($value);

        if(is_array($value))
        {
            foreach ($value as $attr => $label)
            {
                $return .= static::option($label,$attr,$option);
            }
        }

        return $return;
    }


    // selectWithTitle
    // permet de générer un select avec une option title
    public static function selectWithTitle($title=true,$value,$attr=null,?array $option=null):string
    {
        $return = '';

        if(!is_string($title))
        $title = true;

        $option = Arr::plus($option,['title'=>$title]);
        $return = static::select($value,$attr,$option);

        return $return;
    }


    // multiselect
    // construit un menu de sélection avec multiple choix
    public static function multiselect($value,$attr=null,?array $option=null):string
    {
        return static::select($value,Arr::plus(static::getAttrScalar('select',$attr),['multiple'=>true]),Arr::plus($option,['multi'=>true]));
    }


    // clickOpen
    // génère une balise clickOpen, qui contient un container
    // est la base pour un fakeSelect
    public static function clickOpen(?string $value=null,?string $title=null,?string $after=null,$attr=null,?array $option=null):string
    {
        $return = '';
        $option = Arrs::replace(['class'=>static::$config['clickOpen']],$option);
        $class = $option['class'];
        $return .= static::divOpen(null,$attr);

        if(!empty($class['trigger']))
        {
            $return .= static::divOpen(null,$class['trigger']);

            $attrTitle = (array) $class['title'];

            if(is_string($title))
            {
                $dataTitle = strip_tags($title);
                if(!empty($dataTitle))
                $attrTitle['data-title'] = $dataTitle;
            }

            $return .= static::div($title,$attrTitle);

            if(!empty($class['icon']))
            $return .= static::div(null,$class['icon']);

            $return .= static::divClose();
        }

        $return .= static::divOpen(null,$class['popup']);

        if(is_string($value))
        $return .= $value;

        $return .= static::divClose();

        if(is_string($after))
        $return .= $after;

        $return .= static::divClose();

        return $return;
    }


    // fakeselect
    // génère un input fakeselect, une relation dans un div et une structure ul > li
    // un input hidden est générer avec les attributs pour donner un nom au formulaire
    // la classe de la div est déterminé dans les config de la classe
    public static function fakeselect($value=null,$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = static::getBool($value);

        $option = Arrs::replace(['class'=>static::$config['fakeSelect']],$option);
        $option = static::getOption('option',null,$option);
        $selectedClass = $option['class']['selected'] ?? null;
        $selected = (array_key_exists('selected',$option))? $option['selected']:null;
        $selected = (!is_array($selected))? [$selected]:$selected;
        $multi = (array_key_exists('multi',$option) && $option['multi'] === true)? true:false;
        $attr = static::getAttrScalar('input',$attr);
        $attr['data-fakeselect'] = true;
        $after = static::hidden($selected,$attr,['multi'=>$multi]);
        $title = $option['title'] ?? null;
        $divAttr = ($multi === true)? ['fakemultiselect','data-multiple'=>true]:'fakeselect';
        $divAttr = Attr::append($option['attr'] ?? null,$divAttr);

        if(is_string($title))
        $return .= static::li($title,['data-value'=>'']);

        if(!empty($value))
        {
            foreach ($value as $val => $label)
            {
                $val = Scalar::cast($val);
                $class = (in_array($val,$selected,true))? $selectedClass:null;
                $return .= static::li($label,[$class,'data-value'=>$val]);
            }
        }

        if(!empty($return))
        $return = static::ul($return);

        if(!empty($return))
        $return = static::clickOpen($return,$title,$after,$divAttr,$option);

        return $return;
    }


    // fakemultiselect
    // génère un fakemultiselect à partir d'un tableau de relation
    public static function fakemultiselect(array $value,$attr=null,?array $option=null):string
    {
        return static::fakeselect($value,$attr,Arr::plus($option,['multi'=>true]));
    }


    // captcha
    // génère une balise image captcha
    // si value est true, refreshCaptcha
    // si value est null, utilise captcha courant
    public static function captcha($value=true,?string $font=null,$alt=null,$attr=null,?array $option=null):string
    {
        $return = '';

        if(!is_string($value))
        $value = Session::captcha(($value === true)? true:false);

        if(is_string($value) && strlen($value))
        {
            if(is_string($value))
            $value = ImageRaster::captcha($value,$font,$option);

            if(is_resource($value))
            $return = static::img($value,$alt,$attr);
        }

        return $return;
    }


    // captchaFormWrap
    // génère une balise image captcha avec le input de formulaire dans un formWrap
    public static function captchaFormWrap(?string $placeholder=null,?string $wrap=null,$captcha=null,?array $replace=null):string
    {
        $return = '';
        $name = Session::getCaptchaName();

        if(is_string($name))
        {
            $label = array_values((array) $captcha);
            $captcha = static::captcha(...$label);
            $attr = ['name'=>$name,'placeholder'=>$placeholder,'data-required'=>true];
            $input = ['inputText',null,$attr];

            if(is_string($captcha))
            $return = static::formWrap($captcha,$input,$wrap,$replace);
        }

        return $return;
    }


    // csrf
    // génère un tag input avec code csrf
    public static function csrf(?string $value=null,string $type='hidden',$attr=null,?array $option=null):string
    {
        $return = '';
        $csrf = Session::getCsrfOption();

        if(!empty($csrf))
        {
            $value = ($value === null)? Session::csrf():$value;
            $attr = Arr::plus($attr,['name'=>$csrf['name']]);
            $attr['data-csrf'] = true;

            if(!empty($value))
            $return = static::input($type,$value,$attr,$option);
        }

        return $return;
    }


    // genuine
    // génère un tag input de type genuine
    public static function genuine(string $type='text',?array $option=null):string
    {
        $return = '';
        $genuine = static::getGenuineName();

        if(!empty($genuine))
        {
            $attr['name'] = $genuine;
            $attr['data-genuine'] = true;
            $return = static::input($type,null,$attr,$option);
        }

        return $return;
    }


    // getGenuineName
    // retourne le nom pour l'input genuine
    public static function getGenuineName(?int $value=null):string
    {
        $return = static::$config['genuine'];

        if(is_int($value))
        $return .= $value.'-';

        return $return;
    }


    // wrap
    // ouvre et ferme un wrap
    public static function wrap(string $wrap,?string $value=''):string
    {
        $return = static::wrapOpen($wrap,$value);

        if(strlen($return))
        $return .= static::wrapClose($wrap);

        return $return;
    }


    // wrapOpen
    // ouvre un wrap
    public static function wrapOpen(string $wrap,$value=''):string
    {
        $return = '';

        if(static::isWrap($wrap))
        {
            $return = static::$config['wrap'][$wrap][0];
            $value = Obj::cast($value);
            $return .= Str::castFix($value);
        }

        return $return;
    }


    // wrapClose
    // ferme un wrap
    public static function wrapClose(string $wrap):string
    {
        $return = '';

        if(static::isWrap($wrap))
        $return = static::$config['wrap'][$wrap][1];

        return $return;
    }


    // doctype
    // génère le doctype
    public static function doctype():string
    {
        return '<!DOCTYPE html>';
    }


    // conditionalComments
    // génère les commentaires conditionnels pour ie
    public static function conditionalComments(string $value,string $type='lte',int $version=8,bool $all=false):string
    {
        return static::conditionalCommentsOpen($type,$version,$all).$value.static::conditionalCommentsClose($all);
    }


    // conditionalCommentsOpen
    // ouvre les commentaires conditionnels pour ie
    public static function conditionalCommentsOpen(string $type='lte',int $version=8,bool $all=false):string
    {
        $return = "<!--[if $type IE $version]>";

        if($all === true)
        $return .= '<!-->';

        return $return;
    }


    // conditionalCommentsClose
    // ferme les commentaires conditionnels pour ie
    public static function conditionalCommentsClose(bool $all=false):string
    {
        $return = '';

        if($all === true)
        $return .= '<!--';

        $return .= '<![endif]-->';

        return $return;
    }


    // docOpen
    // ouvre le document
    // un séparateur entre chaque ligne est ajouté si séparateur est null ou string
    // par défaut merge les défauts
    // l'ordre des éléments est prédéterminé dans config
    // possible d'ajouter un séparateur à la fin
    public static function docOpen(?array $value=null,bool $default=true,?string $separator=null,bool $separatorAfter=false):string
    {
        $return = '';
        $separator = ($separator === null)? static::$config['separator']:$separator;

        if($default === true)
        $value = Arrs::replace(static::$config['docOpen']['default'],$value);

        if(!empty($value))
        {
            foreach (static::$config['docOpen']['order'] as $k)
            {
                if(array_key_exists($k,$value))
                {
                    $r = '';
                    $arg = $value[$k];

                    // doctype
                    if($k === 'doctype')
                    $r = static::doctype();

                    // html
                    elseif($k === 'html')
                    $r = static::htmlOpen(null,$arg);

                    // head
                    elseif($k === 'head')
                    $r = static::head($arg,null,['separator'=>$separator]);

                    // body
                    elseif($k === 'body')
                    $r = static::bodyOpen(null,$arg);

                    if(strlen($r))
                    {
                        $return .= (strlen($return) && is_string($separator))? $separator:'';
                        $return .= $r;
                    }
                }
            }
        }

        if($separatorAfter === true && strlen($return) && is_string($separator))
        $return .= $separator;

        return $return;
    }


    // headFromArray
    // génère le contenu d'une balise head
    // un séparateur entre chaque ligne est ajouté si séparateur est null ou string
    // note link, script, css et js passe dans arr::sortNumbersFirst
    // l'ordre des éléments est prédéterminé dans config
    public static function headFromArray(?array $value=null,$separator=null):string
    {
        $return = '';
        $separator = ($separator === null)? static::$config['separator']:$separator;

        if(!empty($value))
        {
            foreach (static::$config['tag']['head']['order'] as $k)
            {
                if(array_key_exists($k,$value) && $value[$k] !== false)
                {
                    $arg = (array) $value[$k];
                    $removeAssocKey = in_array($k,['link','script','css','js'],true);
                    $arg = static::headArgReformat($arg,$removeAssocKey);

                    if(!empty($arg))
                    {
                        $r = '';

                        // title
                        if($k === 'title')
                        $r = static::title(...$arg);

                        // description
                        elseif($k === 'description')
                        $r = static::metaDescription(...$arg);

                        // keywords
                        elseif($k === 'keywords')
                        $r = static::metaKeywords(...$arg);

                        // meta
                        elseif($k === 'meta')
                        $r = static::many($k,$separator,...$arg);

                        // link, script, css et js
                        elseif($removeAssocKey === true)
                        $r = static::many($k,$separator,...$arg);

                        if(strlen($r))
                        {
                            $return .= (strlen($return) && is_string($separator))? $separator:'';
                            $return .= $r;
                        }
                    }
                }
            }
        }

        return $return;
    }


    // headArgReformat
    // méthode utilisé pour reformater un tableau d'argument dans heads
    // méthode protégé
    protected static function headArgReformat(array $array,bool $removeAssocKey):array
    {
        $return = [];
        $array = Arr::clean($array);
        $array = Arr::sortNumbersFirst($array);

        foreach ($array as $key => $value)
        {
            if(is_string($key) && $removeAssocKey === false)
            $return[] = [$value,$key];

            else
            $return[] = $value;
        }

        return $return;
    }


    // docClose
    // ferme le document
    // un séparateur entre chaque ligne est ajouté si séparateur est null ou string
    // par défaut merge les défauts
    // support pour le callback de response closeBody
    // l'ordre des éléments est prédéterminé dans config
    // possible d'ajouter un séparateur au début
    public static function docClose(?array $value=null,bool $default=true,bool $closeBody=true,?string $separator=null,bool $separatorBefore=false):string
    {
        $return = '';
        $separator = ($separator === null)? static::$config['separator']:$separator;

        if($default === true)
        $value = Arrs::replace(static::$config['docClose']['default'],$value);

        if(!empty($value))
        {
            foreach (static::$config['docClose']['order'] as $k)
            {
                if(array_key_exists($k,$value))
                {
                    $r = '';
                    $arg = $value[$k];

                    // script et js
                    if(in_array($k,['script','js'],true) && is_array($arg) && !empty($arg))
                    $r = static::many($k,$separator,...array_values($arg));

                    // body
                    elseif($k === 'body')
                    {
                        if($closeBody === true)
                        $r = (string) Buffer::startCallGet([Response::class,'closeBody']);

                        $r .= static::bodyClose((array) $arg);
                    }

                    // html
                    elseif($k === 'html')
                    $r = static::htmlClose((array) $arg);

                    if(strlen($r))
                    {
                        $return .= (strlen($return) && is_string($separator))? $separator:'';
                        $return .= $r;
                    }
                }
            }
        }

        if($separatorBefore === true && strlen($return) && is_string($separator))
        $return = $separator.$return;

        return $return;
    }


    // docTitleBody
    // ouvre et ferme un document html très simplement
    // possible de fournir un title et un body
    public static function docTitleBody(?string $title=null,?string $body = null):string
    {
        $return = '<html>';
        $return .= '<head>';

        if(is_string($title))
        $return .= "<title>$title</title>";

        $return .= '</head>';
        $return .= '<body>';

        if(is_string($body))
        $return .= $body;

        $return .= '</body>';
        $return .= '</html>';

        return $return;
    }


    // excerpt
    // fonction pour faire un résumé sécuritaire
    // removeLineBreaks, removeUnicode, excerpt par length (rtrim et suffix), trim, stripTags, encode (specialChars)
    // important: mb est true par défaut
    // prendre note que le suffix sans le html (strip tags) est maintenant comptabilisé dans la longueur de la string
    public static function excerpt(?int $length,string $return,?array $option=null):string
    {
        $option = Arr::plus(['mb'=>true,'removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true,'stripTags'=>true,'rtrim'=>null,'suffix'=>static::getExcerptSuffix(),'encode'=>'specialChars'],$option);
        $option['encode'] = (is_string($option['encode']) || $option['encode'] === true)? [$option['encode']]:$option['encode'];
        $mb = (is_bool($option['mb']))? $option['mb']:Encoding::getMb($option['mb'],$return);
        $suffix = null;

        // stripTags
        if(!empty($option['stripTags']))
        $return = static::stripTags($return,$option['stripTags']);

        // removeLineBreaks, removeUnicode, trim
        $return = Str::output($return,$option,$mb);

        // length
        if(!empty($length))
        {
            $suffixStrip = (is_string($option['suffix']) && strlen($option['suffix']))? static::stripTags($option['suffix']):null;
            $lts = Str::lengthTrimSuffix($length,$return,Arr::plus($option,['suffix'=>$suffixStrip]));
            $return = $lts['str'];

            if(is_string($lts['suffix']))
            $suffix = $option['suffix'];
        }

        // encode
        if(!empty($option['encode']) && is_array($option['encode']))
        $return = static::encode($return,...$option['encode']);

        // suffix
        if(!empty($suffix))
        $return .= $suffix;

        // trim les espaces
        if(!empty($option['trim']))
        $return = Str::removeWhiteSpace($return);

        return $return;
    }


    // excerptEntities
    // removeLineBreaks, removeUnicode, excerpt par length (rtrim et suffix), trim, stripTags, convert (entities)
    public static function excerptEntities(?int $length,string $return,?array $option=null):string
    {
        return static::excerpt($length,$return,Arr::plus($option,['encode'=>'entities']));
    }


    // excerptStrSuffix
    // comme excerpt mais le suffix est ... (pas de html)
    public static function excerptStrSuffix(?int $length,string $return,?array $option=null):string
    {
        return static::excerpt($length,$return,Arr::plus($option,['suffix'=>Str::$config['excerpt']['suffix']]));
    }


    // getExcerptSuffix
    // retourne le suffix pour l'excerpt
    public static function getExcerptSuffix():string
    {
        return static::$config['excerptSuffix'] ?? '';
    }


    // output
    // output une string html de façon sécuritaire
    // removeLineBreaks, removeUnicode, trim et encode (specialchars)
    // mb est true par défaut
    public static function output(string $return,?array $option=null):string
    {
        $option = Arr::plus(['mb'=>true,'removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true,'stripTags'=>false,'encode'=>'specialChars'],$option);
        $option['encode'] = (is_string($option['encode']) || $option['encode'] === true)? [$option['encode']]:$option['encode'];
        $return = Str::output($return,$option);

        // stripTags
        if(!empty($option['stripTags']))
        $return = static::stripTags($return,$option['stripTags']);

        // encode
        if(!empty($option['encode']) && is_array($option['encode']))
        $return = static::encode($return,...$option['encode']);

        // trim les espaces
        if(!empty($option['trim']))
        $return = trim($return);

        return $return;
    }


    // outputEntities
    // removeLineBreaks, removeUnicode, trim et convert (entities)
    public static function outputEntities(string $return,?array $option=null):string
    {
        return static::output($return,Arr::plus($option,['encode'=>'entities']));
    }


    // outputStripTags
    // removeLineBreaks, removeUnicode, trim, stripTags et convert (specialchars)
    public static function outputStripTags(string $return,?array $option=null):string
    {
        return static::output($return,Arr::plus($option,['stripTags'=>true]));
    }


    // unicode
    // removeLineBreaks, trim et convert (specialchars)
    public static function unicode(string $return,?array $option=null):string
    {
        return static::output($return,Arr::plus($option,['removeUnicode'=>false]));
    }


    // unicodeEntities
    // removeLineBreaks, trim et convert (entities)
    public static function unicodeEntities(string $return,?array $option=null):string
    {
        return static::output($return,Arr::plus($option,['removeUnicode'=>false,'encode'=>'entities']));
    }


    // unicodeStripTags
    // removeLineBreaks, trim, stripTags et convert (specialchars)
    public static function unicodeStripTags(string $return,?array $option=null):string
    {
        return static::output($return,Arr::plus($option,['removeUnicode'=>false,'stripTags'=>true]));
    }


    // getUriOption
    // retourne les options uri pour une tag
    public static function getUriOption(string $tag):?array
    {
        $return = null;

        if(static::isUriOption($tag))
        $return = static::$config['tag'][$tag]['option']['attr']['uri'];

        return $return;
    }


    // setUriOption
    // change les options uri pour une tag
    public static function setUriOption(string $tag,array $option):void
    {
        if(static::isUriOption($tag))
        static::$config['tag'][$tag]['option']['attr']['uri'] = Uri::option($option);

        return;
    }
}
?>