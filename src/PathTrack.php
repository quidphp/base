<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// pathTrack
class PathTrack extends Path
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'start'=>null, // aucun changement au séparateur au début lors du implode
			'end'=>null] // aucun changement au séparateur à la fin lors du implode
	];
}

// config
PathTrack::__config();
?>