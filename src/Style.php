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

// style
// class with static methods to generate an HTML style attribute
class Style extends Listing
{
    // config
    public static array $config = [
        'option'=>[ // tableau d'options
            'implode'=>1, // index du séparateur à utiliser lors du implode
            'end'=>true, // ajoute le premier séparateur à la fin lors du implode
            'extension'=>'jpg', // extension par défaut pour uri si non fourni
            'uri'=>null], // option pour uri,
        'separator'=>[ // les séparateurs de style
            [';','; '],
            [':',': ']],
        'default'=>'background-image', // clé par défaut si style est string
        'shortcut'=>[ // shortcut pour clé style
            'bgimg'=>'background-image'],
        'unit'=>[ // unit par défaut si la valeur est int ou float
            'border-radius'=>'%',
            'padding'=>'px',
            'margin'=>'px',
            'border'=>'px',
            'outline'=>'px',
            'font-size'=>'%',
            'width'=>'%',
            'min-'=>'%',
            'max-'=>'%',
            'height'=>'%',
            'background'=>'%',
            'top'=>'%',
            'left'=>'%',
            'right'=>'%',
            'botton'=>'%']
    ];


    // parse
    // passe à travers un tableau style explosé
    // ajoute un unit pour certaines clés, tel que défini dans config unit
    // ajoute url pour background-image
    // enlève les clés->valeurs dans un mauvais format
    // les clés du tableau sont insensible à la case
    final public static function parse(array $array,array $option):array
    {
        $return = [];

        foreach ($array as $key => $value)
        {
            if(is_string($key) && (is_string($value) || is_numeric($value)))
            {
                if(array_key_exists($key,static::$config['shortcut']))
                $key = static::$config['shortcut'][$key];

                if((is_int($value) || is_float($value)))
                {
                    foreach (static::$config['unit'] as $k => $unit)
                    {
                        if(strpos($key,$k) !== false)
                        {
                            $return[$key] = $value.$unit;
                            break;
                        }
                    }
                }

                if(!array_key_exists($key,$return))
                {
                    $value = Str::cast($value);

                    if($key === 'background-image' && strpos($value,'url(') === false)
                    {
                        $value = static::parseUri($value,$option);
                        if(!empty($value))
                        $return[$key] = "url($value)";
                    }

                    else
                    $return[$key] = $value;
                }
            }
        }

        return $return;
    }


    // parseUri
    // parse un champ uri (background-image)
    final public static function parseUri($value,array $option):?string
    {
        $return = null;

        if(is_string($value))
        {
            if(is_string($option['extension']))
            {
                $extension = Path::extension($value);
                if(empty($extension))
                $value = Path::changeExtension($option['extension'],$value);
            }

            $return = Uri::output($value,$option['uri'] ?? null);
        }

        return $return;
    }


    // prepareStr
    // prépare une string dans la méthode arr
    final public static function prepareStr(string $value,array $option):array
    {
        $return = [];
        $separator = static::getSeparator(1,$option['explode']);

        if(!empty($separator) && strpos($value,$separator))
        $return = static::explodeStr($value,$option);

        else
        $return = [static::$config['default']=>$value];

        return $return;
    }


    // explodeStr
    // explode une string style
    final public static function explodeStr(string $value,array $option):array
    {
        $return = [];
        $separator = static::getSeparator(0,$option['explode']);
        $separator2 = static::getSeparator(1,$option['explode']);

        if(!empty($separator) && !empty($separator2))
        {
            $value = Str::explode($separator,$value,$option['limit']);

            if(!empty($value))
            $return = Arr::explodeKeyValue($separator2,$value);
        }

        return $return;
    }


    // getUriOption
    // retourne les options uri pour style
    final public static function getUriOption():array
    {
        return static::$config['option']['uri'];
    }


    // setUriOption
    // change les options uri pour style
    final public static function setUriOption(array $option):void
    {
        static::$config['option']['uri'] = Uri::option($option);

        return;
    }
}

// init
Style::__init();
?>