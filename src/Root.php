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

// root
// abstract class extended by almost all others
abstract class Root
{
    // trait
    use _root;


    // config
    public static array $config = [];


    // static
    // remet une copie des propriétés des traits incluent car c'est plus facile à gérer
    protected static array $initStaticProp = []; // voir _init
    protected static $initCallable; // voir _init
    protected static array $cacheStatic = []; // voir _cacheStatic
    protected static string $cacheFile; // voir _cacheFile


    // construct
    // constructeur privé
    private function __construct() { }
}
?>