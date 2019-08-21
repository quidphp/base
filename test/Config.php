<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// config
class Config extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		return true;
	}
}
?>