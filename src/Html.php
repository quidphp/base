<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// html
// class with static methods for easily generating HTML
final class Html extends Root
{
    // config
    protected static array $config = [
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
            'form'=>'%form%',
            'table'=>'<table><tr><td>%label%</td><td>%form%</td></tr></table>',
            'divtable'=>"<div class='table'><div class='table-row'><div class='table-cell label-cell'>%label%</div><div class='table-cell form-cell'>%form%</div></div></div>",
            'div'=>'<div>%label%</div><div>%form%</div>'],
        'static'=>[ // terme accepté pour callStatic
            'special'=>['cond','many','or','loop'],
            'openClose'=>['open','close','op','cl']],
        'multi'=>'[]', // caractère pour nom multi
        'separator'=>"\n", // caractère séparateur new line
        'genuine'=>'-genuine-', // nom pour l'input genuine
        'timestamp'=>'-timestamp-', // nom pour l'input timestamp
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
                    'timestamp'=>['get'=>false,'post'=>true],
                    'enctype'=>['get'=>null,'post'=>'multipart/form-data']]],
            'button'=>[
                'attrCallable'=>[self::class,'buttonAttr'],
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
                    'attr'=>['maxlength'=>250]],
                'email'=>[
                    'attr'=>['maxlength'=>250]],
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
                    'url'=>'text',
                    'tel'=>'text',
                    'search'=>'text',
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
    final public static function is($value):bool
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
    final public static function isWrap($value):bool
    {
        $return = false;

        if(is_string($value) && array_key_exists($value,self::$config['wrap']) && is_array(self::$config['wrap'][$value]))
        {
            if(array_key_exists(0,self::$config['wrap'][$value]) && is_string(self::$config['wrap'][$value][0]))
            {
                if(array_key_exists(1,self::$config['wrap'][$value]) && is_string(self::$config['wrap'][$value][1]))
                $return = true;
            }
        }

        return $return;
    }


    // isAlias
    // retourne vrai si la valeur est un alias de tag
    final public static function isAlias($value):bool
    {
        return is_string($value) && !empty(self::$config['alias'][$value]);
    }


    // isInputType
    // retourne vrai si la valeur est un type de input
    final public static function isInputType($value):bool
    {
        return is_string($value) && array_key_exists($value,self::$config['tag']['input']['all']);
    }


    // isInputMethod
    // retourne vrai si la méthode en est une de input
    final public static function isInputMethod($value):bool
    {
        return is_string($value) && !empty(self::getTypeFromInputMethod($value));
    }


    // isFormTag
    // retourne vrai si la tag est une tag de form
    // si inputMethod est true et que la tag n'est pas une tag standard, envoie à inputMethod
    final public static function isFormTag($value,bool $inputMethod=true):bool
    {
        $return = (is_string($value) && array_key_exists($value,self::$config['tag']['form']['all']));

        if($return === false && $inputMethod === true)
        $return = self::isInputMethod($value);

        return $return;
    }


    // isTextTag
    // retourne vrai si la tag est une tag text comme input ou textarea
    final public static function isTextTag($value,bool $inputMethod=true):bool
    {
        $return = (self::isFormTag($value,false) && self::$config['tag']['form']['all'][$value] === 'text');

        if($return === false && $inputMethod === true)
        $return = self::isInputGroup('text',$value);

        return $return;
    }


    // isHiddenTag
    // retourne vrai si la méthode en est une de input hidden
    final public static function isHiddenTag($value,bool $inputMethod=true):bool
    {
        $return = false;

        if(is_string($value) && array_key_exists($value,self::$config['tag']['input']['all']) && self::$config['tag']['input']['all'][$value] === 'hidden')
        $return = true;

        if($return === false && $inputMethod === true)
        $return = self::isInputGroup('hidden',$value);

        return $return;
    }


    // isRelationTag
    // retourne vrai si la tag en est une de relation
    final public static function isRelationTag($value,bool $inputMethod=true):bool
    {
        return self::isEnumTag($value,$inputMethod) || self::isSetTag($value,$inputMethod);
    }


    // isEnumTag
    // retourne vrai si la tag en est une de relation enum
    final public static function isEnumTag($value,bool $inputMethod=true):bool
    {
        $return = (self::isFormTag($value,false) && self::$config['tag']['form']['all'][$value] === 'enum');

        if($return === false && $inputMethod === true)
        $return = self::isInputGroup('enum',$value);

        return $return;
    }


    // isSetTag
    // retourne vrai si la tag en est une de relation enum set
    final public static function isSetTag($value,bool $inputMethod=true):bool
    {
        $return = (self::isFormTag($value,false) && self::$config['tag']['form']['all'][$value] === 'set');

        if($return === false && $inputMethod === true)
        $return = self::isInputGroup('set',$value);

        return $return;
    }


    // isNameMulti
    // retourne vrai si le nom du input est multi
    final public static function isNameMulti($value):bool
    {
        return is_string($value) && Str::isEnd(self::$config['multi'],$value);
    }


    // isSelfClosing
    // retourne vrai si la valeur est un tag self closing
    final public static function isSelfClosing($value):bool
    {
        return is_string($value) && array_key_exists($value,self::$config['tag']) && !empty(self::$config['tag'][$value]['selfClosing']);
    }


    // isMetaProperty
    // retourne vrai si le nom meta doit utilisé property plutôt que name
    final public static function isMetaProperty($value):bool
    {
        return is_string($value) && in_array($value,self::$config['tag']['meta']['property'],true);
    }


    // isMetaUri
    // retourne vrai si la balise meta représente une uri
    final public static function isMetaUri($value):bool
    {
        return is_string($value) && in_array($value,self::$config['tag']['meta']['uri']['key'],true);
    }


    // isUriOption
    // retourne vrai si la tag a des options d'uri
    final public static function isUriOption($tag):bool
    {
        return is_string($tag) && isset(self::$config['tag'][$tag]['option']['attr']['uri']);
    }


    // isInputGroup
    // retourne vrai si le group du input type est celui donné en argument
    final public static function isInputGroup($group,$value):bool
    {
        $return = false;

        if(is_string($value) && is_string($group))
        {
            $type = self::getTypeFromInputMethod($value);

            if(!empty($type) && array_key_exists($type,self::$config['tag']['input']['all']))
            {
                if(self::$config['tag']['input']['all'][$type] === $group)
                $return = true;
            }
        }

        return $return;
    }


    // isMultipartFormData
    // retourne si le type d'encodage est multipart form-data, pour formulaire avec fichier
    final public static function isMultipartFormData($value):bool
    {
        return is_string($value) && strtolower($value) === 'multipart/form-data';
    }


    // inputsFromGroups
    // retourne tous les inputs à partir d'un ou plusieurs groupes
    final public static function inputsFromGroups($groups,$not=null):array
    {
        $return = [];
        $groups = (array) $groups;
        $not = (array) $not;

        if(!empty($groups))
        {
            foreach (self::$config['tag']['form']['all'] as $name => $group)
            {
                if(in_array($group,$groups,true) && !in_array($name,$return,true) && !in_array($name,$not,true))
                $return[] = $name;
            }
        }

        return $return;
    }


    // relationTag
    // retourne tous les tag de relation
    final public static function relationTag($not=null):array
    {
        return self::inputsFromGroups(['enum','set'],$not);
    }


    // encode
    // encode les caractères spéciaux html via htmlspecialchars ou htmlentities
    final public static function encode(string $value,$method=true,?array $option=null):string
    {
        $return = '';

        if($method === true)
        $method = self::$config['default'];

        if($method === 'specialChars')
        $return = self::specialChars($value,$option);

        elseif($method === 'entities')
        $return = self::entities($value,$option);

        return $return;
    }


    // decode
    // decode les caractères spéciaux html via htmlspecialchars_decode ou htmlentities_decode
    final public static function decode(string $value,$method=true,?array $option=null):string
    {
        $return = '';

        if($method === true)
        $method = self::$config['default'];

        if($method === 'specialChars')
        $return = self::specialCharsDecode($value,$option);

        elseif($method === 'entities')
        $return = self::entitiesDecode($value,$option);

        return $return;
    }


    // specialChars
    // encode les caractères spéciaux html
    final public static function specialChars(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['specialChars'],$option);

        if(!empty($option))
        $return = htmlspecialchars($value,$option['flag'],$option['charset'],$option['doubleEncode']);

        return $return;
    }


    // specialCharsDecode
    // décode les caractères spéciaux html
    final public static function specialCharsDecode(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['specialChars'],$option);

        if(!empty($option))
        $return = htmlspecialchars_decode($value,$option['flag']);

        return $return;
    }


    // specialCharsTable
    // retourne la table de traduction pour specialChars
    final public static function specialCharsTable(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['specialChars'],$option);
        $return = get_html_translation_table(HTML_SPECIALCHARS,$option['flag'],$option['charset']);

        return $return;
    }


    // entities
    // encode les entities sur une string
    final public static function entities(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['entities'],$option);

        if(!empty($option))
        $return = htmlentities($value,$option['flag'],$option['charset'],$option['doubleEncode']);

        return $return;
    }


    // entitiesDecode
    // decode les entities sur une string
    final public static function entitiesDecode(string $value,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['entities'],$option);

        if(!empty($option))
        $return = html_entity_decode($value,$option['flag'],$option['charset']);

        return $return;
    }


    // entititesTable
    // retourne la table de traduction pour entities
    final public static function entitiesTable(?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(['charset'=>Encoding::getCharset()],self::$config['entities'],$option);
        $return = get_html_translation_table(HTML_ENTITIES,$option['flag'],$option['charset']);

        return $return;
    }


    // nl2br
    // converti les sauts de ligne en br
    final public static function nl2br(string $value,bool $removeLineBreaks=true,bool $xhtml=true):string
    {
        $return = nl2br($value,$xhtml);

        if($removeLineBreaks === true)
        $return = Str::removeLineBreaks($return);

        return $return;
    }


    // brs
    // génère un ou plusieurs balises br
    final public static function brs(int $amount):string
    {
        return self::nl2br(Str::eol($amount));
    }


    // stripTags
    // supprime les balises html d'une string
    final public static function stripTags(string $value,$keep=null):string
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
    final public static function getDefaultInputType():?string
    {
        return self::$config['tag']['input']['default'] ?? null;
    }


    // getBool
    // retourne la relation bool selon l'index
    // si index est true, utilise 0
    final public static function getBool($index=true):?array
    {
        $return = null;

        if($index === true)
        $index = 0;

        if(array_key_exists($index,self::$config['bool']))
        $return = self::$config['bool'][$index];

        return $return;
    }


    // getTypeFromInputMethod
    // retourne le type de input à partir d'une méthode inputType
    final public static function getTypeFromInputMethod(string $method):?string
    {
        $return = null;

        if(stripos($method,'input') === 0)
        {
            $type = strtolower(substr($method,5));
            if(self::isInputType($type))
            $return = $type;
        }

        return $return;
    }


    // getFormTagFromMethod
    // retourne la première tag de form dans un nom de méthode
    final public static function getFormTagFromMethod(string $method):?string
    {
        $return = null;

        if(!empty(self::$config['tag']['form']['all']))
        {
            foreach (self::$config['tag']['form']['all'] as $value => $type)
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
    final public static function getTypeAttr(string $tag):?string
    {
        return self::$config['tag'][$tag]['typeAttr'] ?? null;
    }


    // getTypeFromAttr
    // retourne le type du tag en fonction d'un tableau d'attribut
    final public static function getTypeFromAttr(string $tag,array $attr):?string
    {
        $return = null;
        $type = self::getTypeAttr($tag);

        if(!empty($type) && !empty($attr[$type]))
        {
            if($attr[$type] === true)
            $return = self::$config['tag'][$tag]['scalarAttr']['true'] ?? null;

            else
            $return = $attr[$type];
        }

        return $return;
    }


    // getAttr
    // retourne le tableau d'attributs par défaut selon un tag
    // le type dans le tableau attribut, si présent, a priorité sur celui fourni en deuxième argument
    // possibilité de mettre un type
    final public static function getAttr(string $tag,?string $type=null,$attr=null):array
    {
        $return = [];
        $attr = Obj::cast($attr);

        if(is_scalar($attr))
        $attr = self::getAttrScalar($tag,$attr);

        if(is_array($attr))
        {
            $t = self::getTypeFromAttr($tag,$attr);
            if(!empty($t))
            {
                $type = $t;
                $attr[self::getTypeAttr($tag)] = $type;
            }
        }

        if(!empty(self::$config['tag'][$tag]['attr']))
        $merge[] = self::$config['tag'][$tag]['attr'];

        if(is_string($type) && !empty(self::$config['tag'][$tag][$type]['attr']))
        $merge[] = self::$config['tag'][$tag][$type]['attr'];

        if($tag === 'input' || $tag === 'button')
        {
            if(is_string($type) && self::isInputType($type))
            $attr['type'] = $type;

            if(empty($attr['type']) || !self::isInputType($attr['type']))
            $attr['type'] = self::getDefaultInputType();
        }

        $merge[] = $attr;
        $return = Arr::plus(...$merge);

        return $return;
    }


    // getAttrScalar
    // traite un attribut scalar
    // retourne toujours un tableau
    final public static function getAttrScalar(string $tag,$return):array
    {
        if(is_scalar($return) && !empty(self::$config['tag'][$tag]['scalarAttr']))
        {
            $scalar = self::$config['tag'][$tag]['scalarAttr'];

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
    final public static function getValueAttr(string $tag,?string $type=null):?string
    {
        $return = null;

        if((!empty(self::$config['tag'][$tag]['valueAttr'])))
        {
            $return = self::$config['tag'][$tag]['valueAttr'];

            if(is_array($return) && array_key_exists('default',$return))
            $return = (!empty($type) && array_key_exists($type,$return))? $return[$type]:$return['default'];
        }

        return $return;
    }


    // getOption
    // retourne le tableau d'option selon un tag
    // possibilité de mettre un type
    final public static function getOption(string $tag,?string $type=null,?array $option=null):array
    {
        $return = [];
        $merge[] = self::$config['option'];

        if(!empty(self::$config['tag'][$tag]['option']))
        $merge[] = self::$config['tag'][$tag]['option'];

        if(is_string($type) && !empty(self::$config['tag'][$tag][$type]['option']))
        $merge[] = self::$config['tag'][$tag][$type]['option'];

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
    final public static function getValueCallable(string $tag):?callable
    {
        return (!empty(self::$config['tag'][$tag]['valueCallable']))? self::$config['tag'][$tag]['valueCallable']:null;
    }


    // getAttrCallable
    // retourne la callable attr du tag ou null si non existant
    final public static function getAttrCallable(string $tag):?callable
    {
        return (!empty(self::$config['tag'][$tag]['attrCallable']))? self::$config['tag'][$tag]['attrCallable']:null;
    }


    // parseCallStatic
    // passe à travers le tableau en provenance de getCallStatic
    final public static function parseCallStatic(array $array):array
    {
        $return = [];

        foreach ($array as $value)
        {
            if(is_string($value) && !empty($value))
            {
                $value = strtolower($value);

                if(in_array($value,self::$config['static']['special'],true))
                $return['special'] = $value;

                elseif(in_array($value,self::$config['static']['openClose'],true))
                $return['openClose'] = $value;

                elseif(!empty($return) && self::isInputType($value))
                $return['arg'][] = $value;

                else
                $return['tag'][] = $value;
            }
        }

        return $return;
    }


    // getCallStatic
    // retourne la méthode et les arguments à partir d'un tableau camelCase
    final public static function getCallStatic($value,array $args=[]):array
    {
        $return = [];

        if(!is_array($value))
        $value = [$value];

        $value = self::parseCallStatic($value);
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
                    if(!empty($value['openClose']) && in_array($value['openClose'],self::$config['static']['openClose'],true))
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
                            $args[$k] = Arr::merge($value['arg'],$args[$k]);
                        }
                    }

                    else
                    $args = Arr::merge($value['arg'],$args);
                }

                if(!empty($args))
                $return['arg'] = Arr::merge($arg,$args);
            }
        }

        return $return;
    }


    // callStatic
    // permet de générer des tag de façon dynamique
    final public static function __callStatic(string $key,array $arg):?string
    {
        $return = null;
        $camelCase = null;
        $lower = strtolower($key);

        if($lower !== $key)
        $camelCase = Str::explodeCamelCase($key);

        if(is_array($camelCase) && !empty($camelCase))
        $callable = self::getCallStatic($camelCase,$arg);
        else
        $callable = self::getCallStatic($key,$arg);

        if(!empty($callable['method']) && !empty($callable['arg']))
        $return = self::{$callable['method']}(...$callable['arg']);

        return $return;
    }


    // get
    // retourne la tag, gère les alias
    // si tag est un array, retourne la dernière tag
    final public static function get($tag):?string
    {
        $return = null;

        if(!empty($tag))
        {
            if(is_array($tag))
            $tag = Arr::valueLast($tag);

            if(is_string($tag) && strpos($tag,'<') === false && strpos($tag,'>') === false)
            {
                if(self::isAlias($tag))
                $return = self::$config['alias'][$tag];

                else
                $return = strtolower($tag);
            }
        }

        return $return;
    }


    // changeLast
    // permet de changer le dernier tag d'un groupe de tag si existant
    // sinon retourne le tag dans un array, gère les alias
    final public static function changeLast(string $tag,$tags):?array
    {
        $return = null;
        $tag = self::get($tag);

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
    final public static function arg($value,$arg):string
    {
        return self::argOpen($value,$arg).self::argClose($arg);
    }


    // argOpen
    // ouvre une ou un groupe de tag à partir d'une valeur et un tableau d'argument
    // utile pour les callback
    final public static function argOpen($value,$arg):string
    {
        $return = '';
        $explode = null;

        if(is_string($arg))
        {
            $explode = self::argStrExplode($arg);
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

            $return = self::open($tag,$value,...array_values($arg));
        }

        return $return;
    }


    // argClose
    // ferme une ou un groupe de tag à partir d'une valeur et un tableau d'argument
    // utile pour les callback
    final public static function argClose($arg):string
    {
        $return = '';
        $explode = null;

        if(is_string($arg))
        {
            $explode = self::argStrExplode($arg);
            $arg = [$arg];
        }

        if(!empty($explode))
        $return = $explode[1];

        elseif(is_array($arg) && !empty($arg))
        {
            $tag = current($arg);
            $return = self::close($tag);
        }

        return $return;
    }


    // argStrExplode
    // utilisé par les méthodes arg
    // explose une string au caractère % si entouré par une balise html
    final public static function argStrExplode(string $value):?array
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
    final public static function make($tags,...$arg):string
    {
        $return = '';
        $tags = (array) $tags;

        if(!empty($tags))
        {
            $value = self::open($tags,...$arg);
            if(!empty($value))
            {
                $return .= $value;
                $return .= self::close($tags,(is_array($opt = Arr::valueLast($arg)))? $opt:null);
            }
        }

        return $return;
    }


    // makes
    // permet de générer plusieurs tags ou groupe de tags
    final public static function makes(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= self::make(...$value);
        }

        return $return;
    }


    // open
    // ouvre une ou plusieurs groupes de tags, seul la dernière tag a les arguments -> sauf si la combinaison des tags avec open a une méthode défini
    // choisit dynamiquement la méthode d'ouverture selon la tag
    final public static function open($tags,...$arg):string
    {
        $return = '';
        $tags = (array) $tags;

        if(count($tags) > 1 && method_exists(self::class,($method = implode('',$tags).'Open')))
        $return = self::$method(...$arg);

        elseif(!empty($tags))
        {
            $count = (count($tags) - 1);
            foreach ($tags as $i =>$tag)
            {
                $tag = self::get($tag);

                if(!empty($tag))
                {
                    $method = $tag.'Open';

                    if(method_exists(self::class,$method))
                    {
                        if($i === $count)
                        $return .= self::$method(...$arg);
                        else
                        $return .= self::$method();
                    }

                    else
                    {
                        if($i === $count)
                        $return .= self::start($tag,...$arg);
                        else
                        $return .= self::start($tag);
                    }
                }
            }
        }

        return $return;
    }


    // opens
    // permet d'ouvrir plusieurs tags ou groupe de tags
    final public static function opens(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= self::open(...$value);
        }

        return $return;
    }


    // op
    // ouvre une ou plusieurs groupes de tags, seul la dernière tag a les arguments
    // choisit dynamiquement la méthode d'ouverture selon la tag
    // la valeur est toujours null, donc le premier argument est attr
    final public static function op($tags,...$arg):string
    {
        return self::open($tags,null,...$arg);
    }


    // ops
    // permet d'ouvrir plusieurs tags ou groupe de tags
    // les valeurs sont toujours null, donc le premier argument est attr
    final public static function ops(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= self::op(...$value);
        }

        return $return;
    }


    // close
    // ferme une tag ou groupe de tag
    // choisit dynamiquement la méthode de fermeture selon la tag
    final public static function close($tags,?array $option=null):string
    {
        $return = '';
        $tags = (array) $tags;

        if(count($tags) > 1 && method_exists(self::class,($method = implode('',$tags).'Close')))
        $return = self::$method($option);

        elseif(!empty($tags))
        {
            $count = (count($tags) - 1);

            if($count > 0)
            $tags = array_reverse($tags);

            foreach ($tags as $i => $tag)
            {
                $tag = self::get($tag);

                if(!empty($tag))
                {
                    $method = $tag.'Close';

                    if(method_exists(self::class,$method))
                    $return .= self::$method($option);
                    else
                    $return .= self::end($tag,$option);
                }
            }
        }

        return $return;
    }


    // closes
    // permet de fermer plusieurs tags ou groupe de tag
    final public static function closes(...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= self::close(...$value);
        }

        return $return;
    }


    // cl
    // alias de close
    final public static function cl($tags,?array $option=null):string
    {
        return self::close($tags,$option);
    }


    // cls
    // alias de closes
    final public static function cls(...$array):string
    {
        return self::closes(...$array);
    }


    // loop
    // méthode pour générer des tags identiques à partir d'un tableau
    // les attributs et options sont partagés
    final public static function loop($tags,array $values,$attr=null,?array $option=null):string
    {
        $return = '';

        foreach ($values as $value)
        {
            $return .= self::make($tags,$value,$attr,$option);
        }

        return $return;
    }


    // many
    // permet de générer plusieurs tags ou groupes de tags du même type
    // possible de mettre un séparateur entre chaque
    final public static function many($tags,?string $separator=null,...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= (strlen($return) && is_string($separator))? $separator:'';
            $return .= self::make($tags,...$value);
        }

        return $return;
    }


    // manyOpen
    // permet d'ouvrir plusieurs tags ou groupes de tags du même type
    // possible de mettre un séparateur entre chaque
    final public static function manyOpen($tags,?string $separator=null,...$array):string
    {
        $return = '';

        foreach ($array as $value)
        {
            if(!is_array($value))
            $value = [$value];

            $return .= (strlen($return) && is_string($separator))? $separator:'';
            $return .= self::open($tags,...$value);
        }

        return $return;
    }


    // manyClose
    // permet de fermer plusieurs tags ou groupe de tags du même type
    // possible de mettre un séparateur entre chaque
    final public static function manyClose($tags,?string $separator=null,...$array):string
    {
        $return = '';
        $count = count($array);

        if($count > 0)
        {
            for ($i=0; $i < $count; $i++)
            {
                $return .= (strlen($return) && is_string($separator))? $separator:'';
                $return .= self::close($tags);
            }
        }

        return $return;
    }


    // cond
    // ouvre et ferme un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    final public static function cond($tags,$value='',...$arg):string
    {
        $return = self::condOpen($tags,$value,...$arg);

        if(!empty($return))
        $return .= self::condClose($tags,$value,(is_array($opt = Arr::valueLast($arg)))? $opt:null);

        return $return;
    }


    // condOpen
    // ouvre un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    final public static function condOpen($tags,$value='',...$arg):string
    {
        $return = '';
        $tag = self::get($tags);

        if(!empty($tag) && self::value($value,$tag,self::getAttr($tag),self::getOption($tag)) !== '')
        $return = self::open($tags,$value,...$arg);

        return $return;
    }


    // condClose
    // ferme un tag ou groupe de tag, conditionnelle à ce que value ne soit pas vide
    final public static function condClose($tags,$value='',?array $option=null):string
    {
        $return = '';
        $tag = self::get($tags);

        if(!empty($tag) && self::value($value,$tag,self::getAttr($tag),self::getOption($tag)) !== '')
        $return = self::close($tags,$option);

        return $return;
    }


    // or
    // ouvre et ferme un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    final public static function or($tags,$href,$value='',...$arg):string
    {
        $href = Obj::cast($href);
        return (Uri::is($href))? self::make(self::changeLast('a',$tags),$href,$value,...$arg):self::make($tags,$value,...$arg);
    }


    // orOpen
    // ouvre un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    final public static function orOpen($tags,$href,$value='',...$arg):string
    {
        $href = Obj::cast($href);
        return (Uri::is($href))? self::open(self::changeLast('a',$tags),$href,$value,...$arg):self::open($tags,$value,...$arg);
    }


    // orClose
    // ferme un tag ou groupe de tag, le dernier tag du groupe sera remplacé par a si href est une uri valable
    final public static function orClose($tags,$href,...$arg):string
    {
        $return = '';
        $option = (is_array($opt = Arr::valueLast($arg)))? $opt:null;
        $href = Obj::cast($href);

        if(Uri::is($href))
        $return = self::close(self::changeLast('a',$tags),$option);
        else
        $return = self::close($tags,$option);

        return $return;
    }


    // metaAttr
    // attributs initiaux pour tag meta
    final protected static function metaAttr(string $type,array $return,$value,array $option)
    {
        if($type === 'initial')
        {
            if(is_string($return) && strlen($return))
            {
                $key = (self::isMetaProperty($return))? 'property':'name';
                $return = [$key=>$return];
            }

            elseif(is_array($return))
            {
                if(array_key_exists('name',$return) && self::isMetaProperty($return['name']))
                {
                    $return['property'] = $return['name'];
                    unset($return['name']);
                }

                if(array_key_exists('property',$return) && !self::isMetaProperty($return['property']))
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
    final protected static function formAttr(string $type,array $return,$value,array $option):array
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
    final protected static function formInitialNameAttr(array $return,bool $multi=false)
    {
        if(array_key_exists('name',$return))
        {
            if($return['name'] === true)
            {
                $wrap = self::$config['randomNameWrap'] ?? null;
                $return['name'] = Attr::randomId();

                if(is_string($wrap))
                $return['name'] = $wrap.$return['name'].$wrap;
            }

            if($multi === true && !self::isNameMulti($return['name']))
            $return['name'] .= self::$config['multi'];
        }

        return $return;
    }


    // inputAttr
    // génère les attributs initiaux et finaux pour input
    final protected static function inputAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        {
            $return = self::formInitialNameAttr($return,$option['multi'] ?? false);

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
                $return['alt'] = self::alt($return['alt'] ?? null,$return['src'] ?? null);
            }
        }

        return $return;
    }


    // textareaAttr
    // attributs initiaux pour textarea
    final protected static function textareaAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = self::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // buttonAttr
    // attributs initiaux pour button
    final protected static function buttonAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = self::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // selectAttr
    // attributs initiaux pour textarea
    final protected static function selectAttr(string $type,array $return,$value,array $option):array
    {
        if($type === 'initial')
        $return = self::formInitialNameAttr($return,$option['multi'] ?? false);

        return $return;
    }


    // optionAttr
    // attributs initiaux pour option
    final protected static function optionAttr(string $type,array $return,$value,array $option):array
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
    // génère les attributs initiaux et finaux pour img
    final protected static function imgAttr(string $type,array $return,$value,array $option):array
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

            $return['alt'] = self::alt($return['alt'] ?? null,$src);
        }

        return $return;
    }


    // value
    // prépare la valeur avant l'écriture dans la tag
    // possible que la tag soit lié à un callback pour préparer la valeur
    // si value est toujours true après value callable, remplace par &nbsp ou espace
    // value est cast en string, et les forbiddenCodePoint sont retirés
    final protected static function value($value,string $tag,array $attr,array $option,bool $isAttr=false):?string
    {
        $return = null;
        $value = Obj::cast($value);

        if(is_string($tag))
        {
            $callable = self::getValueCallable($tag);

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
            $return = self::encode($return,...(array) $option['encode']);
        }

        return $return;
    }


    // metaValue
    // fonction de callback pour la valeur de la tag meta
    final protected static function metaValue($return,array $attr,array $option)
    {
        $key = null;

        if(array_key_exists('name',$attr))
        $key = $attr['name'];

        elseif(array_key_exists('property',$attr))
        $key = $attr['property'];

        if($key === 'charset' && (empty($return) || !is_string($return)))
        $return = Encoding::getCharset();

        elseif($key === 'og:title')
        $return = self::titleValue($return,$option);

        elseif(in_array($key,['description','og:description'],true))
        $return = self::metaDescriptionValue($return,$option);

        elseif($key === 'keywords')
        $return = self::metaKeywordsValue($return,$option);

        elseif(is_string($key) && self::isMetaUri($key))
        $return = self::metaUriValue($return,$option);

        return $return;
    }


    // imgValue
    // fonction de callback pour la valeur de la tag img
    final protected static function imgValue($return,array $attr,array $option)
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
    // fonction de callback pour la valeur de la tag script
    final protected static function scriptValue($return,array $attr,array $option)
    {
        if(is_array($return) || is_object($return))
        $return = Json::encode($return,$option['json'] ?? null);

        if(is_scalar($return) && !empty($option['var']))
        $return = Json::var($option['var'],$return);

        return $return;
    }


    // formValue
    // note: form n'a pas de valeur ajoutable via formOpen -> ca va dans action
    // fonction de callback pour la valeur de la tag form
    // csrf, genuine et timestamp s'ajoute automatiquement si true ou via la méthode
    final protected static function formValue($return,array $attr,array $option):string
    {
        $return = '';
        $method = $attr['method'];

        // csrf
        if(!empty($option['csrf']))
        {
            if($option['csrf'] === true || (is_array($option['csrf']) && !empty($option['csrf'][$method])))
            {
                $csrf = Session::csrf();
                $return .= self::csrf($csrf);
            }
        }

        // genuine
        if(!empty($option['genuine']))
        {
            if($option['genuine'] === true || (is_array($option['genuine']) && !empty($option['genuine'][$method])))
            $return .= self::genuine();
        }

        // timestamp
        if(!empty($option['timestamp']))
        {
            if($option['timestamp'] === true || (is_array($option['timestamp']) && !empty($option['timestamp'][$method])))
            $return .= self::timestamp();
        }

        return $return;
    }


    // selectValue
    // fonction de callback pour la valeur de la tag select
    final protected static function selectValue($value,array $attr,array $option):string
    {
        $return = '';
        $title = $option['title'] ?? null;

        if($title === true)
        $title = '';

        if(is_string($title))
        $return .= self::option($title,'');

        if($value === true || is_int($value))
        $value = self::getBool($value);

        if(is_array($value))
        $return .= self::options($value,$option);

        else
        $return .= Str::cast($value);

        return $return;
    }


    // headValue
    // fonction de callback pour la valeur de la tag head
    final protected static function headValue($return,array $attr,array $option)
    {
        if(is_array($return))
        {
            $return = self::headFromArray($return);

            if(array_key_exists('separator',$option) && is_string($option['separator']) && !empty($return))
            $return = $option['separator'].$return.$option['separator'];
        }

        return $return;
    }


    // titleDescriptionKeywordsValue
    // méthode protégé pour générer la valeur de meta title, description et keywords
    final protected static function titleDescriptionKeywordsValue($value,array $option):?string
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
                {
                    $method = $option['case'];
                    $value = Arr::map($value,fn(string $v) => Str::$method($v,true));
                }

                $return = implode($option['separator'],$value);
            }

            if(is_string($return) && strlen($return))
            $return = self::excerptStrSuffix($maxLength,$return,$option['excerpt'] ?? null);
        }

        return $return;
    }


    // titleValue
    // prépare la valeur pour la tag title ou meta title
    final public static function titleValue($value,?array $option=null):?string
    {
        return self::titleDescriptionKeywordsValue($value,self::getOption('title',null,$option));
    }


    // metaDescriptionValue
    // prépare la valeur pour la tag meta description
    final public static function metaDescriptionValue($value,?array $option=null):?string
    {
        return self::titleDescriptionKeywordsValue($value,self::getOption('meta','description',$option));
    }


    // metaKeywordsValue
    // prépare la valeur pour la tag meta keywords
    final public static function metaKeywordsValue($value,?array $option=null):?string
    {
        return self::titleDescriptionKeywordsValue($value,self::getOption('meta','keywords',$option));
    }


    // metaUriValue
    // prépare la valeur pour une tag meta uri
    final public static function metaUriValue($value,?array $option=null):?string
    {
        return (is_string($value))? Uri::output($value,self::getOption('meta','uri',$option)):null;
    }


    // div
    // raccourci pour ouvrir et fermer une div
    final public static function div($value='',$attr=null,?array $option=null):string
    {
        return self::start('div',$value,$attr,$option).self::end('div',$option);
    }


    // span
    // raccourci pour ouvrir et fermer une span
    final public static function span($value='',$attr=null,?array $option=null):string
    {
        return self::start('span',$value,$attr,$option).self::end('span',$option);
    }


    // start
    // ouvre une tag html
    // les self-closing tag se ferme dans le open
    // méthode de base, ne fait pas appel au méthode dynamique selon la tag
    final public static function start(string $tag,$value=null,$attr=null,?array $option=null):string
    {
        $return = '';
        $tag = self::get($tag);

        if(self::isWrap($tag))
        $return = self::wrapOpen($tag,$value);

        elseif(!empty($tag))
        {
            $attr = self::getAttr($tag,null,$attr);
            $type = ($typeAttr = self::getTypeAttr($tag))? $attr[$typeAttr] ?? null:null;
            $option = self::getOption($tag,$type,$option);
            $callable = self::getAttrCallable($tag);

            if(!empty($callable))
            $attr = $callable('initial',$attr,$value,$option);

            $valueAttr = self::getValueAttr($tag,$attr['type'] ?? null);
            if(!empty($valueAttr))
            $attr[$valueAttr] = self::value($value,$tag,$attr,$option,true);
            else
            $value = self::value($value,$tag,$attr,$option);

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

            if(self::isSelfClosing($tag))
            $return = "<$tag".$attrStr.'/>';

            else
            {
                $return = "<$tag".$attrStr.'>';

                if(empty($valueAttr))
                {
                    if(!empty($option['excerptMin']) && is_int($option['excerptMin']) && strlen($value) > $option['excerptMin'])
                    $value = self::excerpt($option['excerptMin'],$value);

                    $return .= $value;
                }
            }
        }

        if(is_array($option))
        {
            if(array_key_exists('label',$option) && !in_array($option['label'],['',null],true))
            $return = self::makeLabel($option['label'],$return,$attr['id'] ?? null,$option['position'] ?? null);

            if(!empty($option['html']))
            {
                $open = self::argOpen($return,$option['html']);
                $return = (strlen($open))? $open:$return;
            }

            if(!empty($option['conditional']))
            {
                $arg = (is_array($option['conditional']))? $option['conditional']:[];
                $return = self::conditionalCommentsOpen(...$arg).$return;
            }
        }

        return $return;
    }


    // end
    // ferme une tag html
    // n'a aucun effet sur les self-closing tag
    // méthode de base, ne fait pas appel au méthode dynamique selon la tag
    final public static function end(string $tag,?array $option=null):string
    {
        $return = '';
        $tag = self::get($tag);

        if(self::isWrap($tag))
        $return = self::wrapClose($tag);

        elseif(!empty($tag) && !self::isSelfClosing($tag))
        $return = "</$tag>";

        if(!empty($option['html']))
        $return .= self::argClose($option['html']);

        if(!empty($option['conditional']))
        {
            $arg = (is_array($option['conditional']))? Arr::valueLast($option['conditional']):false;
            $arg = (!is_bool($arg))? false:$arg;
            $return .= self::conditionalCommentsClose($arg);
        }

        return $return;
    }


    // metaCharset
    // génère un tag metaCharset
    final public static function metaCharset($value=null,$attr=null,?array $option=null):string
    {
        return self::meta($value,Arr::plus($attr,['name'=>'charset']),$option);
    }


    // metaDescription
    // génère un tag meta description
    final public static function metaDescription($value,$attr=null,?array $option=null):string
    {
        return self::meta($value,Arr::plus($attr,['name'=>'description']),$option);
    }


    // metaKeywords
    // génère un tag meta keywords
    final public static function metaKeywords($value,$attr=null,?array $option=null):string
    {
        return self::meta($value,Arr::plus($attr,['name'=>'keywords']),$option);
    }


    // metaOg
    // génère un tag meta og
    final public static function metaOg($value,string $name,$attr=null,?array $option=null):string
    {
        return self::meta($value,Arr::plus($attr,['property'=>'og:'.$name]),$option);
    }


    // cssOpen
    // ouvre un tag css -> link
    final public static function cssOpen(string $value,$attr=null,?array $option=null):string
    {
        return self::linkOpen($value,Arr::plus($attr,['rel'=>'stylesheet']),$option);
    }


    // cssClose
    // ferme une tag css -> link
    final public static function cssClose(?array $option=null):string
    {
        return self::linkClose($option);
    }


    // scriptOpen
    // ouvre un tag script
    // possible de spécifier une valeur pour transférer la variable php en javascript
    final public static function scriptOpen($value=null,?string $var=null,$attr=null,?array $option=null):string
    {
        return self::start('script',$value,$attr,Arr::plus($option,['var'=>$var]));
    }


    // jsOpen
    // fait un tag js -> script
    final public static function jsOpen(string $value,$attr=null,?array $option=null):string
    {
        return self::scriptOpen(null,null,Arr::plus(['src'=>$value],$attr),$option);
    }


    // jsClose
    // ferme une tag js -> script
    final public static function jsClose(?array $option=null):string
    {
        return self::scriptClose($option);
    }


    // aOpen
    // ouvre un tag a
    final public static function aOpen($href=null,$title=null,$attr=null,?array $option=null):string
    {
        return self::start('a',($title === true)? $href:$title,Arr::plus(self::getAttrScalar('a',$attr),['href'=>$href]),$option);
    }


    // imgOpen
    // ouvre un tag img
    final public static function imgOpen($src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return self::start('img',$src,Arr::plus($attr,['alt'=>$alt]),$option);
    }


    // aImgOpen
    // ouvre un tag a avec une image à l'intérieur
    // note: à la différence des autres méthodes les attributs et options sont appliqués au tag A et non pas le dernier, IMG
    final public static function aImgOpen($href=null,$src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return self::aOpen($href,self::imgOpen($src,$alt),$attr,$option);
    }


    // alt
    // prépare l'attribut alt en fonction de la valeur ou de la src
    final public static function alt($value,$src=null):?string
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
    final public static function img64($src=null,$alt=null,$attr=null,?array $option=null):string
    {
        return self::img($src,$alt,$attr,Arr::plus($option,['base64'=>true]));
    }


    // tableOpen
    // ouvre une table avec thead, tbody et tfoot à l'intérieur
    // si option strict est true, affiche seulement si toutes les rangés ont le même nombre de colonne
    final public static function tableOpen(?array $thead=null,?array $tbody=null,?array $tfoot=null,$attr=null,?array $option=null):string
    {
        $return = '';
        $option = self::getOption('table',null,$option);
        $table = '';
        $make = true;

        if($option['strict'] === true)
        {
            $rows = [];

            if(!empty($thead))
            $rows[] = $thead;

            if(!empty($tbody))
            $rows = Arr::merge($rows,$tbody);

            if(!empty($tfoot))
            $rows[] = $tfoot;

            $make = self::tableSameCount(...$rows);
        }

        if($make === true)
        {
            if(!empty($thead))
            $table .= self::thead(...$thead);

            if(!empty($tbody) && Column::is($tbody))
            $table .= self::tbody(...$tbody);

            if(!empty($tfoot))
            $table .= self::tfoot(...$tfoot);

            $return = self::start('table',$table,$attr,$option);
        }

        return $return;
    }


    // tableStrict
    // ouvre et ferme une table seulement si toutes les rangées ont le même nombre de colonne
    final public static function tableStrict(?array $thead=null,?array $tbody=null,?array $tfoot=null,$attr=null,?array $option=null):string
    {
        return self::table($thead,$tbody,$tfoot,$attr,Arr::plus($option,['strict'=>true]));
    }


    // theadOpen
    // ouvre un thead et une tr avec des th
    final public static function theadOpen(array $value,$attr=null,?array $option=null):string
    {
        return self::start('thead',self::trThOpen($value,$attr,$option));
    }


    // theadClose
    // ferme une thead et la tr
    final public static function theadClose():string
    {
        return self::end('tr').self::end('thead');
    }


    // tbodyOpen
    // ouvre un tbody et ouvre/ferme plusieurs tr avec td
    final public static function tbodyOpen(array ...$value):string
    {
        $return = self::start('tbody');

        foreach ($value as $v)
        {
            $return .= self::tr(...$v);
        }

        return $return;
    }


    // tbodyStrict
    // ouvre un tbody et ouvre/ferme plusieurs tr avec td seulement si toutes les rangées ont le même nombre de colonnes
    final public static function tbodyStrict(array ...$value):string
    {
        $return = '';
        $sameCount = self::tableSameCount(...$value);

        if($sameCount === true)
        $return = self::tbody(...$value);

        return $return;
    }


    // trOpen
    // ouvre un tr avec plusieurs td
    final public static function trOpen(array $value,$attr=null,?array $option=null):string
    {
        return self::start('tr',self::many('td',null,...$value),$attr,$option);
    }


    // trThOpen
    // ouvre un tr avec plusieurs th
    final public static function trThOpen(array $value,$attr=null,?array $option=null):string
    {
        return self::start('tr',self::many('th',null,...$value),$attr,$option);
    }


    // tfootOpen
    // ouvre un tfoot et une tr avec des td
    final public static function tfootOpen(array $value,$attr=null,?array $option=null):string
    {
        return self::start('tfoot',self::trOpen($value,$attr,$option));
    }


    // tfootClose
    // ferme une tfoot et la tr
    final public static function tfootClose():string
    {
        return self::end('tr').self::end('tfoot');
    }


    // tableSameCount
    // retourne vrai si les lignes de la table ont toutes le même count
    final public static function tableSameCount(...$values):bool
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
                $return = ((count($current) === $count));
            }

            if($return === false)
            break;
        }

        return $return;
    }


    // formOpen
    // ouvre un tag form
    // note: pas de valeur, mais csrf, genuine et timestamp peuvent s'ajouter automatiquement à la valeur si les options sont activés
    final public static function formOpen($action=null,$attr=null,?array $option=null):string
    {
        return self::start('form',null,Arr::plus(self::getAttrScalar('form',$attr),['action'=>$action]),$option);
    }


    // inputOpen
    // ouvre un tag input
    // à la différence de plusieurs autres tags, le type est avant la valeur
    // cette méthode est utilisé pour générer un input via un inputMethod du genre inputEmail
    final public static function inputOpen(string $type='text',$value='',$attr=null,?array $option=null):string
    {
        return self::start('input',$value,self::getAttr('input',$type,$attr),self::getOption('input',$type,$option));
    }


    // buttonOpen
    // ouvre un tag button, le type du button est button donc ne soumet pas le formulaire
    // contrairement à submit, une string dans attr est un nom de classe
    final public static function buttonOpen($value=null,$attr=null,?array $option=null):string
    {
        return self::start('button',$value,self::getAttr('button','button',$attr),self::getOption('button','button',$option));
    }


    // submitOpen
    // ouvre un tag button, le type du button est submit donc soumet le formulaire
    // attr utilise input/submit donc une string est name
    final public static function submitOpen($value=null,$attr=null,?array $option=null):string
    {
        return self::start('button',$value,self::getAttr('input','submit',$attr),self::getOption('button','submit',$option));
    }


    // submitClose
    // ferme un tag button submit
    final public static function submitClose(array $option=null):string
    {
        return self::end('button',$option);
    }


    // inputDecimal
    // génère un input text avec un input mode à decimal
    final public static function inputDecimal($value=null,$attr=null,?array $option=null):string
    {
        $value = (is_numeric($value))? (int) $value:null;
        return static::input('text',$value,Arr::plus($attr,['inputmode'=>'decimal']),$option);
    }


    // inputMaxFilesize
    // fait un input input hidden max file size, en lien avec inputFile
    final public static function inputMaxFilesize($value='',$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === null)
        $value = Ini::uploadMaxFilesize(1);

        if(is_string($value))
        $value = Num::fromSizeFormat($value);

        if(is_int($value))
        {
            $attr = self::getAttr('input','hidden',$attr);
            $attr['name'] = 'MAX_FILE_SIZE';
            $return .= self::inputHidden($value,$attr,$option);
        }

        return $return;
    }


    // makeLabel
    // construit un label
    // si attr est scalar c'est for
    // position 1 = before, position 2 = after, sinon c'est wrap
    // si array est de longeur 2, la deuxième valeur est un span à ajouter après le label
    // le span est un élément après le label, mais hors du label
    final public static function makeLabel($label,string $value,$attr=null,$position=1,?array $option=null):string
    {
        $return = '';
        $span = null;

        if(is_array($label) && count($label) === 2)
        {
            $span = Arr::valueLast($label);
            $span = self::spanCond($span);
            $label = current($label);
        }

        $label = Obj::cast($label);
        $label = Str::cast($label);

        if(is_string($label) && strlen($label))
        {
            if(in_array($position,[1,'before'],true))
            $return = self::label($label,$attr,$option).$span.$value;

            elseif(in_array($position,[2,'after'],true))
            $return = $value.self::label($label,$attr,$option).$span;

            else
            {
                $label = $label.$value;
                $return = self::label($label,$attr,$option).$span;
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
    final public static function formWrap($label,$value,?string $wrap=null,?array $replace=null,$id=null):string
    {
        $return = '';

        if(!empty($label) && !empty($value))
        {
            $value = array_values((array) $value);
            $method = $value[0];
            unset($value[0]);
            $value = array_values($value);
            $tag = self::getFormTagFromMethod($method) ?? $method;

            if(!array_key_exists(0,$value))
            $value[0] = null;

            if(array_key_exists(1,$value) && !is_array($value[1]))
            $value[1] = self::getAttrScalar($tag,$value[1]);

            $name = (!empty($value[1]['name']))? $value[1]['name']:null;

            if(in_array($id,[null,true],true) && !self::isRelationTag($tag))
            $id = Attr::randomId($name ?? null);

            if(is_string($id))
            $value[1]['id'] = $id;

            $value = self::$method(...$value);

            if(is_string($value))
            $return = self::formWrapStr($label,$value,$wrap,$replace,$id);
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
    final public static function formWrapStr($label,string $value,?string $wrap=null,?array $replace=null,$id=null):string
    {
        $return = '';

        if($wrap === null)
        $wrap = key(self::$config['formWrap']);

        if(!empty($wrap))
        {
            $label = array_values((array) $label);

            if(array_key_exists(1,$label) && !is_array($label[1]))
            $label[1] = self::getAttrScalar('label',$label[1]);
            if(is_string($id))
            $label[1]['for'] = $id;
            $label = self::label(...$label);

            if(is_string($label))
            {
                $replace = (array) $replace;
                $formWrap = (array_key_exists($wrap,self::$config['formWrap']))? self::$config['formWrap'][$wrap]:$wrap;
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
    final public static function formWrapArray($value,array $array,?string $wrap=null,$attr=null,?array $replace=null,$id=null,?array $option=null):string
    {
        $return = '';
        $option = Arr::plus(['descriptionClass'=>'description','requiredClass'=>'required','choiceClass'=>'choice'],$option);

        if(!empty($array))
        {
            $attr = (array) $attr;
            $replace = (array) $replace;
            $type = $array['type'] ?? 'inputText';
            $required = (!empty($array['required']));
            $label = $array['label'] ?? null;
            $replace['description'] = $array['description'] ?? null;
            $isRelation = self::isRelationTag($type);
            $htmlLabel = '';
            $htmlForm = '';

            if(in_array($id,[null,true],true) && !self::isRelationTag($type))
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
            $replace['description'] = self::divCond($replace['description'],$option['descriptionClass']);

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

                $htmlForm .= self::$type($choices,$attr,$option);
            }

            else
            $htmlForm .= self::$type($value,$attr,$option);

            $return .= self::formWrapStr($htmlLabel,$htmlForm,$wrap,$replace,$id);
        }

        return $return;
    }


    // hidden
    // génère un ou une série de input hiddens
    // n'affiche rien si attr est null
    final public static function hidden($value,$attr=null,?array $option=null):string
    {
        $return = '';
        $value = Obj::cast($value);

        if(!is_array($value))
        $value = [$value];

        if($attr !== null)
        {
            foreach ($value as $v)
            {
                $return .= self::inputHidden($v,$attr,$option);

                if(empty($option['multi']))
                break;
            }
        }

        return $return;
    }


    // autoHidden
    // génère le input hidden pour les checkbox ou radio
    final protected static function autoHidden($attr=null,?array $option=null):string
    {
        $return = '';
        $autoAttr = Arr::plus($attr,['type'=>'hidden','id'=>null,'data-required'=>null]);
        $autoOpt = Arr::plus($option,['html'=>null,'multi'=>false]);
        $return .= self::inputHidden(null,$autoAttr,$autoOpt);

        return $return;
    }


    // radio
    // alias de radios
    final public static function radio($value,$attr=null,?array $option=null):string
    {
        return self::radios($value,$attr,$option);
    }


    // radios
    // construit une série de bouton radio avec un tableau valeur => label
    // si value est int ou true, utilise une relation bool
    // si attr est scalar c'est name
    final public static function radios($value,$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = self::getBool($value);

        $option = self::getOption('input','radio',$option);
        $attr = self::getAttr('input','radio',$attr);

        if(!empty($option['autoHidden']))
        $return .= self::autoHidden($attr,$option);

        if(is_array($value))
        {
            foreach ($value as $val => $label)
            {
                $return .= self::inputRadio($val,$attr,Arr::plus($option,['label'=>$label]));
            }
        }

        return $return;
    }


    // radiosWithHidden
    // construit une série de bouton radio avec un tableau valeur => label
    // un champ hidden est ajouté au début du html
    final public static function radiosWithHidden($value,$attr=null,?array $option=null):string
    {
        return self::radios($value,$attr,Arr::plus($option,['autoHidden'=>true]));
    }


    // checkbox
    // alias de checkboxes
    final public static function checkbox($value,$attr=null,?array $option=null):string
    {
        return self::checkboxes($value,$attr,$option);
    }


    // checkboxes
    // construit une série de checkbox avec un tableau valeur => label
    // si value est int ou true, utilise une relation bool
    // si attr est scalar c'est name
    // le nom multi est ajouté automatiquement
    final public static function checkboxes($value,$attr=null,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = self::getBool($value);

        $option = self::getOption('input','checkbox',$option);
        $attr = self::getAttr('input','checkbox',$attr);

        if(!empty($option['autoHidden']))
        $return .= self::autoHidden($attr,$option);

        if(is_array($value))
        {
            foreach ($value as $val => $label)
            {
                $return .= self::inputCheckbox($val,$attr,Arr::plus($option,['label'=>$label]));
            }
        }

        return $return;
    }


    // checkboxesWithHidden
    // construit une série de checkbox un tableau valeur => label
    // un champ hidden est ajouté au début du html
    final public static function checkboxesWithHidden($value,$attr=null,?array $option=null):string
    {
        return self::checkboxes($value,$attr,Arr::plus($option,['autoHidden'=>true]));
    }


    // options
    // construit une série d'option de select avec un tableau attr => label
    // si attr est scalar c'est value
    final public static function options($value,?array $option=null):string
    {
        $return = '';

        if($value === true || is_int($value))
        $value = self::getBool($value);

        if(is_array($value))
        {
            foreach ($value as $attr => $label)
            {
                $return .= self::option($label,$attr,$option);
            }
        }

        return $return;
    }


    // selectWithTitle
    // permet de générer un select avec une option title
    final public static function selectWithTitle($title=true,$value,$attr=null,?array $option=null):string
    {
        $return = '';

        if(!is_string($title))
        $title = true;

        $option = Arr::plus($option,['title'=>$title]);
        $return = self::select($value,$attr,$option);

        return $return;
    }


    // multiselect
    // construit un menu de sélection avec multiple choix
    final public static function multiselect($value,$attr=null,?array $option=null):string
    {
        return self::select($value,Arr::plus(self::getAttrScalar('select',$attr),['multiple'=>true]),Arr::plus($option,['multi'=>true]));
    }


    // captcha
    // génère une balise image captcha
    // si value est true, refreshCaptcha
    // si value est null, utilise captcha courant
    final public static function captcha($value=true,?string $font=null,$alt=null,$attr=null,?array $option=null):string
    {
        $return = '';

        if(!is_string($value))
        $value = Session::captcha(($value === true));

        if(is_string($value) && strlen($value))
        {
            if(is_string($value))
            $value = ImageRaster::captcha($value,$font,null,$option);

            if(is_resource($value))
            $return = self::img($value,$alt,$attr);
        }

        return $return;
    }


    // captchaFormWrap
    // génère une balise image captcha avec le input de formulaire dans un formWrap
    final public static function captchaFormWrap(?string $placeholder=null,?string $wrap=null,$captcha=null,?array $replace=null):string
    {
        $return = '';
        $name = Session::getCaptchaName();

        if(is_string($name))
        {
            $label = array_values((array) $captcha);
            $captcha = self::captcha(...$label);
            $attr = ['name'=>$name,'placeholder'=>$placeholder,'data-required'=>true];
            $input = ['inputText',null,$attr];

            if(is_string($captcha))
            $return = self::formWrap($captcha,$input,$wrap,$replace);
        }

        return $return;
    }


    // csrf
    // génère un tag input avec code csrf
    final public static function csrf(?string $value=null,string $type='hidden',$attr=null,?array $option=null):string
    {
        $return = '';
        $csrf = Session::getCsrfOption();

        if(!empty($csrf))
        {
            $value = ($value === null)? Session::csrf():$value;
            $attr = Arr::plus($attr,['name'=>$csrf['name']]);
            $attr['data-csrf'] = true;

            if(!empty($value))
            $return = self::input($type,$value,$attr,$option);
        }

        return $return;
    }


    // genuine
    // génère un tag input de type genuine
    final public static function genuine(string $type='text',?array $option=null):string
    {
        $return = '';
        $genuine = self::getGenuineName();

        if(!empty($genuine))
        {
            $attr['name'] = $genuine;
            $attr['data-genuine'] = true;
            $return = self::input($type,null,$attr,$option);
        }

        return $return;
    }


    // getGenuineName
    // retourne le nom pour l'input genuine
    final public static function getGenuineName(?int $value=null):string
    {
        $return = self::$config['genuine'];

        if(is_int($value))
        $return .= $value.'-';

        return $return;
    }


    // timestamp
    // génère un tag input hidden de type timestamp
    final public static function timestamp(string $type='hidden',?array $option=null):string
    {
        $return = '';
        $timestamp = self::getTimestampName();

        if(!empty($timestamp))
        {
            $value = Datetime::now();
            $attr['name'] = $timestamp;
            $attr['data-timestamp'] = true;
            $return = self::input($type,$value,$attr,$option);
        }

        return $return;
    }


    // getTimestampName
    // retourne le nom pour l'input timestamp
    final public static function getTimestampName():string
    {
        return self::$config['timestamp'];
    }


    // wrap
    // ouvre et ferme un wrap
    final public static function wrap(string $wrap,?string $value=''):string
    {
        $return = self::wrapOpen($wrap,$value);

        if(strlen($return))
        $return .= self::wrapClose($wrap);

        return $return;
    }


    // wrapOpen
    // ouvre un wrap
    final public static function wrapOpen(string $wrap,$value=''):string
    {
        $return = '';

        if(self::isWrap($wrap))
        {
            $return = self::$config['wrap'][$wrap][0];
            $value = Obj::cast($value);
            $return .= Str::castFix($value);
        }

        return $return;
    }


    // wrapClose
    // ferme un wrap
    final public static function wrapClose(string $wrap):string
    {
        $return = '';

        if(self::isWrap($wrap))
        $return = self::$config['wrap'][$wrap][1];

        return $return;
    }


    // doctype
    // génère le doctype
    final public static function doctype():string
    {
        return '<!DOCTYPE html>';
    }


    // conditionalComments
    // génère les commentaires conditionnels pour ie
    final public static function conditionalComments(string $value,string $type='lte',int $version=8,bool $all=false):string
    {
        return self::conditionalCommentsOpen($type,$version,$all).$value.self::conditionalCommentsClose($all);
    }


    // conditionalCommentsOpen
    // ouvre les commentaires conditionnels pour ie
    final public static function conditionalCommentsOpen(string $type='lte',int $version=8,bool $all=false):string
    {
        $return = "<!--[if $type IE $version]>";

        if($all === true)
        $return .= '<!-->';

        return $return;
    }


    // conditionalCommentsClose
    // ferme les commentaires conditionnels pour ie
    final public static function conditionalCommentsClose(bool $all=false):string
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
    final public static function docOpen(?array $value=null,bool $default=true,?string $separator=null,bool $separatorAfter=false):string
    {
        $return = '';
        $separator = ($separator === null)? self::$config['separator']:$separator;

        if($default === true)
        $value = Arrs::replace(self::$config['docOpen']['default'],$value);

        if(!empty($value))
        {
            foreach (self::$config['docOpen']['order'] as $k)
            {
                if(array_key_exists($k,$value))
                {
                    $r = '';
                    $arg = $value[$k];

                    // doctype
                    if($k === 'doctype')
                    $r = self::doctype();

                    // html
                    elseif($k === 'html')
                    $r = self::htmlOpen(null,$arg);

                    // head
                    elseif($k === 'head')
                    $r = self::head($arg,null,['separator'=>$separator]);

                    // body
                    elseif($k === 'body')
                    $r = self::bodyOpen(null,$arg);

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
    final public static function headFromArray(?array $value=null,$separator=null):string
    {
        $return = '';
        $separator = ($separator === null)? self::$config['separator']:$separator;

        if(!empty($value))
        {
            foreach (self::$config['tag']['head']['order'] as $k)
            {
                if(array_key_exists($k,$value) && $value[$k] !== false)
                {
                    $arg = (array) $value[$k];
                    $removeAssocKey = in_array($k,['link','script','css','js'],true);
                    $arg = self::headArgReformat($arg,$removeAssocKey);

                    if(!empty($arg))
                    {
                        $r = '';

                        // title
                        if($k === 'title')
                        $r = self::title(...$arg);

                        // description
                        elseif($k === 'description')
                        $r = self::metaDescription(...$arg);

                        // keywords
                        elseif($k === 'keywords')
                        $r = self::metaKeywords(...$arg);

                        // meta
                        elseif($k === 'meta')
                        $r = self::many($k,$separator,...$arg);

                        // link, script, css et js
                        elseif($removeAssocKey === true)
                        $r = self::many($k,$separator,...$arg);

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
    final protected static function headArgReformat(array $array,bool $removeAssocKey):array
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
    final public static function docClose(?array $value=null,bool $default=true,bool $closeBody=true,?string $separator=null,bool $separatorBefore=false):string
    {
        $return = '';
        $separator = ($separator === null)? self::$config['separator']:$separator;

        if($default === true)
        $value = Arrs::replace(self::$config['docClose']['default'],$value);

        if(!empty($value))
        {
            foreach (self::$config['docClose']['order'] as $k)
            {
                if(array_key_exists($k,$value))
                {
                    $r = '';
                    $arg = $value[$k];

                    // script et js
                    if(in_array($k,['script','js'],true) && is_array($arg) && !empty($arg))
                    $r = self::many($k,$separator,...array_values($arg));

                    // body
                    elseif($k === 'body')
                    {
                        if($closeBody === true)
                        $r = (string) Buffer::startCallGet([Response::class,'closeBody']);

                        $r .= self::bodyClose((array) $arg);
                    }

                    // html
                    elseif($k === 'html')
                    $r = self::htmlClose((array) $arg);

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


    // docSimple
    // ouvre et ferme un document html très simplement
    // possible de fournir un title, un body et des attributs pour le html
    final public static function docSimple(?string $title=null,?string $body=null,?array $option=null):string
    {
        $return = '';
        $option = (array) $option;
        $html = (!empty($option['html']) && is_string($option['html']))? ' '.$option['html']:'';
        $head = $option['head'] ?? null;

        $return .= '<!DOCTYPE html>';
        $return .= "<html$html>";
        $return .= '<head>';

        if(is_string($title))
        $return .= "<title>$title</title>";

        if(is_string($head))
        $return .= $head;

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
    final public static function excerpt(?int $length,string $return,?array $option=null):string
    {
        $option = Arr::plus(['mb'=>true,'removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true,'stripTags'=>true,'rtrim'=>null,'suffix'=>self::getExcerptSuffix(),'encode'=>'specialChars'],$option);
        $option['encode'] = (is_string($option['encode']) || $option['encode'] === true)? [$option['encode']]:$option['encode'];
        $mb = (is_bool($option['mb']))? $option['mb']:Encoding::getMb($option['mb'],$return);
        $suffix = null;

        // stripTags
        if(!empty($option['stripTags']))
        $return = self::stripTags($return,$option['stripTags']);

        // removeLineBreaks, removeUnicode, trim
        $return = Str::output($return,$option,$mb);

        // length
        if(!empty($length))
        {
            $suffixStrip = (is_string($option['suffix']) && strlen($option['suffix']))? self::stripTags($option['suffix']):null;
            $lts = Str::lengthTrimSuffix($length,$return,Arr::plus($option,['suffix'=>$suffixStrip]));
            $return = $lts['str'];

            if(is_string($lts['suffix']))
            $suffix = $option['suffix'];
        }

        // encode
        if(!empty($option['encode']) && is_array($option['encode']))
        $return = self::encode($return,...$option['encode']);

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
    final public static function excerptEntities(?int $length,string $return,?array $option=null):string
    {
        return self::excerpt($length,$return,Arr::plus($option,['encode'=>'entities']));
    }


    // excerptStrSuffix
    // comme excerpt mais le suffix est ... (pas de html)
    final public static function excerptStrSuffix(?int $length,string $return,?array $option=null):string
    {
        return self::excerpt($length,$return,Arr::plus($option,['suffix'=>Str::getConfig('excerpt/suffix')]));
    }


    // getExcerptSuffix
    // retourne le suffix pour l'excerpt
    final public static function getExcerptSuffix():string
    {
        return self::$config['excerptSuffix'] ?? '';
    }


    // output
    // output une string html de façon sécuritaire
    // removeLineBreaks, removeUnicode, trim et encode (specialchars)
    // mb est true par défaut
    final public static function output(string $return,?array $option=null):string
    {
        $option = Arr::plus(['mb'=>true,'removeLineBreaks'=>true,'removeUnicode'=>true,'trim'=>true,'stripTags'=>false,'encode'=>'specialChars'],$option);
        $option['encode'] = (is_string($option['encode']) || $option['encode'] === true)? [$option['encode']]:$option['encode'];
        $return = Str::output($return,$option);

        // stripTags
        if(!empty($option['stripTags']))
        $return = self::stripTags($return,$option['stripTags']);

        // encode
        if(!empty($option['encode']) && is_array($option['encode']))
        $return = self::encode($return,...$option['encode']);

        // trim les espaces
        if(!empty($option['trim']))
        $return = trim($return);

        return $return;
    }


    // outputEntities
    // removeLineBreaks, removeUnicode, trim et convert (entities)
    final public static function outputEntities(string $return,?array $option=null):string
    {
        return self::output($return,Arr::plus($option,['encode'=>'entities']));
    }


    // outputStripTags
    // removeLineBreaks, removeUnicode, trim, stripTags et convert (specialchars)
    final public static function outputStripTags(string $return,?array $option=null):string
    {
        return self::output($return,Arr::plus($option,['stripTags'=>true]));
    }


    // unicode
    // removeLineBreaks, trim et convert (specialchars)
    final public static function unicode(string $return,?array $option=null):string
    {
        return self::output($return,Arr::plus($option,['removeUnicode'=>false]));
    }


    // unicodeEntities
    // removeLineBreaks, trim et convert (entities)
    final public static function unicodeEntities(string $return,?array $option=null):string
    {
        return self::output($return,Arr::plus($option,['removeUnicode'=>false,'encode'=>'entities']));
    }


    // unicodeStripTags
    // removeLineBreaks, trim, stripTags et convert (specialchars)
    final public static function unicodeStripTags(string $return,?array $option=null):string
    {
        return self::output($return,Arr::plus($option,['removeUnicode'=>false,'stripTags'=>true]));
    }


    // getUriOption
    // retourne les options uri pour une tag
    final public static function getUriOption(string $tag):?array
    {
        return self::$config['tag'][$tag]['option']['attr']['uri'] ?? null;
    }


    // setUriOption
    // change les options uri pour une tag
    final public static function setUriOption(string $tag,array $option):void
    {
        self::$config['tag'][$tag]['option']['attr']['uri'] = Uri::option($option);
    }
}
?>