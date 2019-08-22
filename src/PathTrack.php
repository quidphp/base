<?php 
declare(strict_types=1);
namespace Quid\Base;

// pathTrack
class PathTrack extends Path
{
	// config
	public static $config = array(
		'option'=>array( // tableau d'options
			'start'=>null, // aucun changement au séparateur au début lors du implode
			'end'=>null) // aucun changement au séparateur à la fin lors du implode
	);
}

// config
PathTrack::__config();
?>