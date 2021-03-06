<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// slugPath
// class with static methods to deal with URI slugs within an URI path
final class SlugPath extends Listing
{
    // config
    protected static array $config = [
        'option'=>[ // tableau d'options
            'slug'=>null],
        'separator'=>[ // les séparateurs de listing, le deuxième index est la version avec espace
            ['/','/'],
            ['-','-']]
    ];


    // is
    // retourne vrai si la valeur est un slugPath
    final public static function is($value):bool
    {
        return Validate::regex('alphanumericSlugPath',$value);
    }


    // parse
    // parse un tableau pathSlug
    final public static function parse(array $array,array $option):array
    {
        $return = [];
        $slugOption = $option['slug'] ?? null;

        foreach ($array as $key => $value)
        {
            if(is_numeric($key))
            $slug = $value;

            elseif(is_string($key))
            $slug = [$key,$value];

            $return[] = $slug;
        }

        $return = self::makeSlugs($return,$slugOption);

        return $return;
    }


    // prepareArr
    // prépare un array dans la méthode arr
    final public static function prepareArr(array $value,?array $option=null):array
    {
        return $value;
    }


    // makeSlugs
    // génère les slugs dans pour le pathSlug
    // traitement particulier de l'option totalLength qui est divisé par le nombre d'entrée dans le tableau
    final public static function makeSlugs(array $array,?array $option=null):array
    {
        $return = [];
        $option = Arr::plus(['totalLength'=>null],$option);

        if(!empty($array))
        {
            $count = count($array);

            if(is_int($option['totalLength']) && $option['totalLength'] > 0)
            {
                $option['totalLength'] -= $count;

                if($option['totalLength'] > 0)
                $option['totalLength'] = (int) floor($option['totalLength'] / $count);

                else
                $option['totalLength'] = null;
            }

            foreach ($array as $key => $value)
            {
                $slug = Slug::str($value,$option);

                if(strlen($slug))
                $return[$key] = $slug;
            }
        }

        return $return;
    }
}

// init
SlugPath::__init();
?>