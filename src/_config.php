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

// _config
// trait that grants static methods to get or set data within static config
trait _config
{
    // config
    // retourne le tableau de config
    // possibilité de faire un merge sur la valeur de retour
    // par défaut, cette méthode écrit dans la variable statique (à l'inverse de option)
    final public static function config(?array $value=null,bool $write=true):?array
    {
        $return = null;
        $class = static::class;

        if(property_exists($class,'config') && is_array(static::$config))
        {
            $return = static::$config;

            if($value !== null)
            {
                $callable = static::getInitCallable();
                $return = $callable($class,$return,$value);

                if($write === true)
                static::$config = $return;
            }
        }

        return $return;
    }
}
?>