<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Base;

// path
// class with static methods to deal with filesystem paths
class Path extends Set
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'start'=>true], // ajoute le séparateur au début lors du implode
		'separator'=>['/'], // sépareur de chemin, n'utilise pas directorySeparator
		'safe'=>[
			'length'=>250, // longueur maximale permise pour un path
			'extension'=>null, // extension permises, tout est permis si null
			'pattern'=>['./','/.','..','//','?',' ']], // pattern de chemin non sécuritaire
		'safeBasenameReplace'=>[' '=>'_','-'=>'_','.'=>'_',','=>'_'], // caractère à remplacer sur un safebasename
		'extensionReplace'=>['jpeg'=>'jpg'], // gère le remplacement d'extension, utiliser par safeBasename
		'build'=>[ // pour reconstuire à partir d'un array
			'dirname'=>'/',
			'basename'=>null,
			'filename'=>null,
			'extension'=>'.'],
		'lang'=>[ // option par défaut pour détection de la langue d'un path, index de langue dans le path est 0
			'length'=>2, // longueur de lang
			'all'=>null] // possibilité de lang
	];


	// is
	// retourne vrai si la valeur est un path
	public static function is($value):bool
	{
		return (is_string($value) && Validate::regex('path',$value))? true:false;
	}


	// hasExtension
	// retourne vrai si le chemin a une extension
	public static function hasExtension(string $path):bool
	{
		return (!empty(static::extension($path)))? true:false;
	}


	// hasLang
	// retourne vrai si le path a une lang
	public static function hasLang(string $path,?array $option=null):bool
	{
		return (static::lang($path,$option) !== null)? true:false;
	}


	// isSafe
	// retourne vrai si le chemin est sécuritaire
	public static function isSafe(string $path,?array $option=null):bool
	{
		$return = false;
		$option = Arr::plus(static::$config['safe'],$option);

		if(static::is($path))
		{
			$return = true;

			// length
			if(!empty($option['length']) && is_int($option['length']))
			{
				if(!Str::isMaxLength($option['length'],$path))
				$return = false;
			}

			// extension
			if(!empty($return) && !empty($option['extension']))
			{
				$extension = static::extension($path);

				if(!empty($extension) && !in_array($extension,(array) $option['extension'],true))
				$return = false;
			}

			// unsafePattern
			if(!empty($return) && !empty($option['pattern']))
			{
				$patterns = (array) $option['pattern'];

				foreach ($patterns as $v)
				{
					if(strpos($path,$v) !== false)
					{
						$return = false;
						break;
					}
				}
			}
		}

		return $return;
	}


	// isLangCode
	// retourne vrai si la valeur est un code de langue
	public static function isLangCode(string $value,?array $option=null):bool
	{
		$return = false;
		$option = Arr::plus(static::$config['lang'],$option);

		if(!empty($value))
		{
			$return = true;

			if(is_int($option['length']) && strlen($value) !== $option['length'])
			$return = false;

			elseif(is_array($option['all']) && !in_array($value,$option['all'],true))
			$return = false;
		}

		return $return;
	}


	// isParent
	// retourne vrai si la valeur parent est un sous-directoire dans le chemin de path
	public static function isParent(string $parent,string $path):bool
	{
		$return = false;
		$parent = static::str($parent);
		$parents = static::parents($path);

		if(in_array($parent,$parents,true))
		$return = true;

		return $return;
	}


	// isExtension
	// retourne vrai si le chemin à une extension du type
	// possibilité de mettre une ou plusieurs target
	// insensible à la case
	public static function isExtension($target,string $path):bool
	{
		$return = false;

		if(is_string($target) || is_array($target))
		{
			$extension = static::extension($path);

			if(!empty($extension) && Arr::in($extension,(array) $target,false))
			$return = true;
		}

		return $return;
	}


	// isLang
	// retourne vrai si le path à la langue spécifié
	public static function isLang($value,string $path,?array $option=null):bool
	{
		return (is_string($value) && $value === static::lang($path,$option))? true:false;
	}


	// isMimeGroup
	// retourne vrai si le mime est du group spécifé
	public static function isMimeGroup($group,$value):bool
	{
		return (is_string($value) && is_string($group) && static::mimeGroup($value) === $group)? true:false;
	}


	// isMimeFamily
	// retourne vrai si le mime est de la famille spécifiée
	public static function isMimeFamily($family,$value):bool
	{
		return (is_string($value) && is_string($family) && in_array($family,static::mimeFamilies($value),true))? true:false;
	}


	// isInterface
	// retourne vrai si le chemin semble pointer vers des interfaces
	public static function isInterface($value):bool
	{
		return (is_string($value) && Str::isEnd('/contract',dirname($value),false))? true:false;
	}


	// info
	// retourne le tableau pathinfo
	// dirname est passé dans separator si pas false, '', ou '.'
	public static function info(string $path):?array
	{
		$return = pathinfo($path);

		if(array_key_exists('dirname',$return))
		{
			if(in_array($return['dirname'],[false,'','.'],true))
			unset($return['dirname']);

			elseif(is_string($return['dirname']))
			$return['dirname'] = static::separator($return['dirname']);
		}

		return $return;
	}


	// infoOne
	// retourne une entrée de pathinfo
	public static function infoOne(int $key,string $path):?string
	{
		$return = pathinfo($path,$key);

		if($return === false || $return === '' || ($key === PATHINFO_DIRNAME && $return === '.'))
		$return = null;

		elseif($key === PATHINFO_DIRNAME && is_string($return))
		$return = static::separator($return);

		return $return;
	}


	// build
	// construit un path à partir d'un tableau info
	// note: build et les méthodes de path ajoute un slash en début de la chaîne de retour
	// cette classe ajoute toujours un slash au début et enlève celui de la fin
	// utiliser la classe track pour générer des paths respectant les slashs fournis en argument
	public static function build(array $parse):string
	{
		$return = '';

		// remove basename
		if(!empty($parse['filename']) && !empty($parse['extension']) && !empty($parse['basename']))
		unset($parse['basename']);

		$i = 0;
		$previous = false;
		foreach (static::$config['build'] as $k => $v)
		{
			if(array_key_exists($k,$parse) && is_string($parse[$k]) && !empty($parse[$k]))
			{
				if($previous === 'dirname' && !empty($return) && !empty(static::$config['build'][$previous]))
				$return .= static::$config['build'][$previous];

				if($k === 'extension' && !empty(static::$config['build'][$k]))
				$return .= static::$config['build'][$k];

				$return .= $parse[$k];

				if($k === 'basename')
				break;

				$previous = $k;
				$i++;
			}
		}

		$return = static::separator($return);

		return $return;
	}


	// rebuild
	// reconstruit un path à partir d'une string
	public static function rebuild(string $return):string
	{
		$info = static::info($return);
		$return = static::build($info);

		return $return;
	}


	// change
	// change une ou plusieurs partis d'un path
	public static function change(array $change,string $return):string
	{
		$info = static::info($return);

		if(!empty($change) && !empty($info))
		{
			$info = Arr::replace($info,$change);
			$return = static::build($info);
		}

		return $return;
	}


	// keep
	// garde une ou plusieurs partis d'un path
	public static function keep($change,string $return):string
	{
		$info = static::info($return);

		if(!empty($change) && !empty($info))
		{
			$info = Arr::keysSlice((array) $change,$info);
			$return = static::build($info);
		}

		return $return;
	}


	// remove
	// enlève une ou plusieurs partis d'un path
	public static function remove($change,string $return):string
	{
		$info = static::info($return);

		if(!empty($change) && !empty($info))
		{
			$change = (array) $change;

			if(array_key_exists('basename',$info) && Arr::inFirst(['filename','extension'],$change) !== null)
			unset($info['basename']);

			$info = Arr::keysStrip($change,$info);
			$return = static::build($info);
		}

		return $return;
	}


	// dirname
	// retourne le dirname du path
	// ne tient pas compte des dirname .
	public static function dirname(string $path):?string
	{
		return static::infoOne(PATHINFO_DIRNAME,$path);
	}


	// changeDirname
	// change le dirname d'un path
	public static function changeDirname(string $change,string $path):string
	{
		$return = '';

		$build['dirname'] = $change;
		$build['basename'] = static::basename($path);
		$return = static::build($build);

		return $return;
	}


	// addDirname
	// ajoute un dirname à un path, le dirname s'ajoute au dirname existant
	public static function addDirname(string $change,string $path):string
	{
		$return = '';

		$dirname = static::dirname($path);
		$build['dirname'] = static::append($dirname,$change);
		$build['basename'] = static::basename($path);
		$return = static::build($build);

		return $return;
	}


	// removeDirname
	// enlève un dirname à un path
	public static function removeDirname(string $path):string
	{
		return static::separator(static::basename($path) ?? '');
	}


	// parent
	// retourne le chemin absolut du parent
	public static function parent(string $path):string
	{
		return static::dirname($path);
	}


	// parents
	// retourne les chemins absolus de tous les parents
	public static function parents(string $path):array
	{
		$return = [];
		$path = static::stripStart($path);
		$x = static::arr($path);

		if(!empty($x))
		{
			while (!empty($x))
			{
				array_pop($x);
				$return[] = static::str($x);
			}
		}

		return $return;
	}


	// basename
	// retourne le basename du path
	public static function basename(string $path):?string
	{
		return static::infoOne(PATHINFO_BASENAME,$path);
	}


	// changeBasenameExtension
	// ajoute une extension à un basename, ne considère pas comme un path
	// possible de mettre l'extension lowerCase
	public static function changeBasenameExtension(string $extension,string $basename,bool $lowerCase=false):string
	{
		return static::makeBasename(static::filename($basename),$extension,$lowerCase);
	}


	// makeBasename
	// fait un basename à partir d'un filename et d'une extension
	// l'extension peut être ramené en lowercase
	public static function makeBasename(string $return,?string $extension=null,bool $lowerCase=false):string
	{
		if(is_string($extension))
		{
			$return .= '.';
			if($lowerCase === true)
			$extension = strtolower($extension);

			$return .= $extension;
		}

		return $return;
	}


	// safeBasename
	// retourne un nom de fichier sécuritaire à partir d'un path ou basename
	// l'extension est toujours mise en lowercase et est passé dans extensionReplace
	public static function safeBasename(string $value,?string $extension=null):?string
	{
		$return = null;

		if($extension === null)
		$extension = static::extension($value);

		$filename = static::filename($value);

		if(is_string($filename))
		{
			$return = $filename;
			$replace = static::$config['safeBasenameReplace'];
			$return = Str::replace($replace,$return);
			$return = Str::clean($return,'_');
			$return = Str::trim($return,'_');
			$return = Str::removeConsecutive('_',$return);

			if(is_string($extension) && !empty($return))
			{
				$extension = static::extensionReplace($extension);
				$return = static::makeBasename($return,$extension,true);
			}
		}

		return $return;
	}


	// parentBasename
	// fonction pour obtenir rapidement le basename du parent
	public static function parentBasename(string $path):?string
	{
		$return = null;

		$dirname = static::dirname($path);
		if(!empty($dirname))
		$return = static::basename($dirname);

		return $return;
	}


	// addBasename
	// ajoute le basename à un path
	public static function addBasename(string $change,string $path):string
	{
		$return = '';

		$build['basename'] = static::basename($change);
		$build['dirname'] = $path;
		$return = static::build($build);

		return $return;
	}


	// changeBasename
	// change le basename d'un path
	public static function changeBasename(string $change,string $path):string
	{
		$return = '';

		$build['basename'] = static::basename($change);
		$build['dirname'] = static::dirname($path);
		$return = static::build($build);

		return $return;
	}


	// removeBasename
	// enlève un basename à un path
	public static function removeBasename(string $path):?string
	{
		return static::separator(static::dirname($path) ?? '');
	}


	// filename
	// retourne le filename du path
	public static function filename(string $path):?string
	{
		return static::infoOne(PATHINFO_FILENAME,$path);
	}


	// addFilename
	// ajoute le filename à un path
	public static function addFilename(string $change,string $path):string
	{
		$return = '';

		$build['filename'] = static::filename($change);
		$build['dirname'] = $path;
		$return = static::build($build);

		return $return;
	}


	// changeFilename
	// change le filename d'un path
	public static function changeFilename(string $change,string $path):string
	{
		$return = '';

		$build['filename'] = static::filename($change);
		$build['dirname'] = static::dirname($path);
		$build['extension'] = static::extension($path);
		$return = static::build($build);

		return $return;
	}


	// removeFilename
	// enlève un filename à un path
	public static function removeFilename(string $path):string
	{
		return static::remove('filename',$path);
	}


	// extension
	// retourne l'extension du path
	public static function extension(string $path,bool $lowerCase=false):?string
	{
		$return = static::infoOne(PATHINFO_EXTENSION,$path);

		if(is_string($return) && $lowerCase === true)
		$return = strtolower($return);

		return $return;
	}


	// extensionLowerCase
	// retourne le chemin avec l'extension en lowercase
	public static function extensionLowerCase(string $return):?string
	{
		$extension = static::extension($return);
		if(is_string($extension))
		{
			$lowerCase = strtolower($extension);
			if($lowerCase !== $extension)
			$return = static::changeExtension($lowerCase,$return);
		}

		return $return;
	}


	// extensionReplace
	// permet de remplacer une extension par une autre, utiliser par safeBasename
	public static function extensionReplace(string $return):string
	{
		$return = strtolower($return);

		if(array_key_exists($return,static::$config['extensionReplace']))
		$return = static::$config['extensionReplace'][$return];

		return $return;
	}


	// addExtension
	// ajoute l'extension à un path
	public static function addExtension(string $change,string $path):string
	{
		$return = '';

		$extension = static::extension($change);
		if(empty($extension))
		$extension = static::filename($change);

		$build['extension'] = $extension;
		$build['dirname'] = $path;
		$return = static::build($build);

		return $return;
	}


	// changeExtension
	// change l'extension d'un path
	public static function changeExtension(string $change,string $path):string
	{
		$return = '';

		$extension = static::extension($change);
		if(empty($extension))
		$extension = static::filename($change);

		$build['extension'] = $extension;
		$build['dirname'] = static::dirname($path);
		$build['filename'] = static::filename($path);
		$return = static::build($build);

		return $return;
	}


	// removeExtension
	// enlève une extension à un path
	public static function removeExtension(string $path):string
	{
		return static::remove(['extension'],$path);
	}


	// mime
	// retourne le mimetype du fichier à partir de son chemin ou extension
	// si path est seulement l'extension, la fonction retourne également le mime type
	// pratique pour les fichiers qui n'existent pas
	public static function mime(string $value):?string
	{
		return Mime::fromPath($value);
	}


	// mimeGroup
	// retourne le groupe mimetype du fichier à partir de son extension
	// pour les fichiers qui n'existent pas
	public static function mimeGroup(string $value):?string
	{
		$return = null;
		$mime = static::mime($value);

		if(!empty($mime))
		$return = Mime::group($mime);

		return $return;
	}


	// mimeFamilies
	// retourne les familles du mime type du chemin
	// pour les fichiers qui n'existent pas
	public static function mimeFamilies(string $value):?array
	{
		$return = null;
		$group = static::mimeGroup($value);

		if(!empty($group))
		$return = Mime::families($group);

		return $return;
	}


	// mimeFamily
	// retourne la première famille du mime type du chemin
	// pour les fichiers qui n'existent pas
	public static function mimeFamily(string $value):?string
	{
		$return = null;
		$group = static::mimeGroup($value);

		if(!empty($group))
		$return = Mime::family($group);

		return $return;
	}


	// lang
	// retourne la lang de l'uri ou null si non existante
	public static function lang(string $path,?array $option=null):?string
	{
		$return = null;
		$value = static::get(0,$path);

		if(is_string($value) && static::isLangCode($value,$option))
		$return = $value;

		return $return;
	}


	// addLang
	// ajoute un code de langue à un path
	// ajoute même si le code existe déjà
	public static function addLang(string $value,string $path,?array $option=null):?string
	{
		$return = null;

		if(static::isLangCode($value,$option))
		$return = static::insert(0,$value,$path,$option);

		return $return;
	}


	// changeLang
	// ajoute ou remplace un code de langue à un path
	public static function changeLang(string $value,string $path,?array $option=null):?string
	{
		$return = null;

		if(static::isLangCode($value,$option))
		{
			$lang = static::lang($path,$option);
			if(empty($lang))
			$return = static::insert(0,$value,$path,$option);

			else
			$return = static::splice(0,1,$path,$value,$option);
		}

		return $return;
	}


	// removeLang
	// enlève un code de langue à un path
	// retourne le chemin dans tous les cas
	public static function removeLang(string $return,?array $option=null):string
	{
		$lang = static::lang($return,$option);

		if(!empty($lang))
		$return = static::splice(0,1,$return,null,$option);

		return $return;
	}


	// match
	// retourne le chemin sans le code de langue et sans le wrap du début
	// si pas de lang, retourne pathStripStart quand même
	public static function match(string $return,?array $option=null):string
	{
		$return = static::removeLang($return,$option);
		$return = static::stripStart($return);

		return $return;
	}


	// separator
	// régularise la situation des separator dans un path
	// les options de la classe sont utilisés
	public static function separator(string $path):string
	{
		$separator = static::getSeparator();

		if(!empty($separator))
		{
			$return = preg_replace('#'.$separator.'+#',$separator,$path);
			$return = static::stripWrap($return,static::getOption('start'),static::getOption('end'));
		}

		return $return;
	}


	// redirect
	// retourne le chemin de redirection si le path présente des défauts
	// par exemple path unsafe, double slash, slash à la fin ou manque pathLang
	public static function redirect(string $path,?array $safeOpt=null,?array $langOpt=null):?string
	{
		$return = null;
		$path = static::stripStart($path);

		if(!static::hasExtension($path))
		{
			// protection double slash ou slash à la fin
			if(static::hasSeparatorDouble($path) || static::isSeparatorEnd($path))
			$return = $path = static::str($path);

			// protection s'il manque lang uri (sauf si vide)
			if(strlen($path))
			{
				$lang = static::lang($path,$langOpt);
				$langDefault = Lang::default();

				if(empty($lang) && !empty($langDefault))
				{
					$path = static::str([$langDefault,$path]);
					$return = (static::isSafe($path,$safeOpt))? $path:static::wrapStart('');
				}
			}
		}

		return $return;
	}
}

// config
Path::__config();
?>