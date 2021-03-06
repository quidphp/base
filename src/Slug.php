<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// slug
// class with static methods to deal with URI slugs
final class Slug extends Set
{
    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'caseImplode'=>'lower', // les valeurs sont ramenés dans cette case lors du implode
            'replaceAccent'=>true, // replace les accents par les caractères non accentés
            'prepend'=>null, // prepend une valeur au slug
            'append'=>null, // append une valeur au slug
            'sliceLength'=>[2,20], // garde seulement les slugs entre une certaine longueur
            'keepLast'=>true, // garde le dernier slice peu importe la longueur
            'keepNum'=>true, // garde toutes les slices numériques
            'totalLength'=>null], // longueur total du slug admise
        'separator'=>['-','-'] // séparateur pour les slug
    ];


    // is
    // retourne vrai si la valeur est un slug
    final public static function is($value):bool
    {
        return Validate::regex('alphanumericSlug',$value);
    }


    // keepAlphanumeric
    // enleve tous les caractères non alphanumérique et garde - et _
    // keep permet de garder des caractères supplémentaires
    final public static function keepAlphanumeric(string $value,string $keep=''):string
    {
        return preg_replace("/[^A-Za-z0-9_\-$keep]/", '', $value);
    }


    // implode
    // passe tous les éléments dans keepAlphanumeric (pour éviter une slug avec caractère segment [])
    public static function implode(array $value,?array $option=null):string
    {
        foreach ($value as $k => $v)
        {
            $value[$k] = self::keepAlphanumeric($v);
        }

        return parent::implode($value,$option);
    }


    // parse
    // parse le tableau arr de slug
    final public static function parse(array $array,array $option):array
    {
        $return = [];
        $separator = self::getSeparator(1);
        $segment = Segment::getDelimiter(null,true);

        if(is_string($option['prepend']))
        {
            $prepend = self::parseValue($option['prepend'],$option['replaceAccent']);
            $return[] = $prepend;
        }

        foreach ($array as $key => $value)
        {
            $value = preg_split('~[^\\pL\d'.$segment[0].$segment[1].']+~u',$value,-1,PREG_SPLIT_NO_EMPTY);

            if(!empty($value))
            {
                foreach ($value as $v)
                {
                    if(is_string($v))
                    {
                        $v = self::parseValue($v,$option['replaceAccent']);

                        if(!empty($v))
                        $return[] = $v;
                    }
                }
            }
        }

        if(is_string($option['append']))
        {
            $append = self::parseValue($option['append'],$option['replaceAccent']);
            $return[] = $append;
        }

        if(is_array($option['sliceLength']) && count($option['sliceLength']) === 2)
        {
            $keep = $return;

            $option['sliceLength'] = array_values($option['sliceLength']);
            $return = Arr::valuesSliceLength($option['sliceLength'][0],$option['sliceLength'][1],$return);

            if($option['keepNum'] === true)
            {
                foreach ($keep as $k => $v)
                {
                    if(!array_key_exists($k,$return) && is_numeric($v))
                    $return[$k] = $v;
                }

                ksort($return);
            }

            if($option['keepLast'] === true)
            {
                if(Arr::keyLast($keep) !== Arr::keyLast($return))
                $return[] = Arr::valueLast($keep);
            }
        }

        if(is_numeric($option['totalLength']) && !empty($option['totalLength']))
        {
            $reverse = Arr::valuesTotalLength($option['totalLength'],array_reverse($return));
            $return = array_reverse($reverse);
        }

        return $return;
    }


    // parseValue
    // parse une valeur string déjà explosé
    final public static function parseValue(string $return,?bool $replaceAccent=null):string
    {
        $segment = Segment::getDelimiter(null,true);

        if($replaceAccent === true)
        $return = Str::replaceAccent($return);

        $return = self::keepAlphanumeric($return,$segment[0].$segment[1]);

        return $return;
    }
}

// init
Slug::__init();
?>