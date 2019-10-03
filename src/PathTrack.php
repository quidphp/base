<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// pathTrack
// class with static methods to deal with filesystem paths (without a starting slash)
class PathTrack extends Path
{
    // config
    public static $config = [
        'option'=>[ // tableau d'options
            'start'=>null, // aucun changement au séparateur au début lors du implode
            'end'=>null] // aucun changement au séparateur à la fin lors du implode
    ];
}

// init
PathTrack::__init();
?>