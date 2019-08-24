<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base;

// nav
class Nav extends Root
{
	// config
	public static $config = [];


	// isPage
	// retourne vrai si la page existe
	// nav peut être un int ou un tableau
	public static function isPage(int $value,$nav,int $limit):bool
	{
		$return = false;
		$max = static::pageMax($nav,$limit);

		if(is_int($max) && $value > 0 && $value <= $max)
		$return = true;

		return $return;
	}


	// isPageFull
	// retourne vrai si la page existe et est pleine
	// nav peut être un int ou un tableau
	public static function isPageFull(int $value,$nav,int $limit):bool
	{
		return (static::pageSpecificCount($value,$nav,$limit) === $limit)? true:false;
	}


	// isSpecificInPage
	// retourne vrai si le specific est présent dans la page
	// nav doit être un array
	public static function isSpecificInPage($specific,int $value,array $nav,int $limit):bool
	{
		$return = false;
		$specificPage = static::specificPage($specific,$nav,$limit);

		if(is_int($specificPage) && $specificPage === $value)
		$return = true;

		return $return;
	}


	// parseLimit
	// prend une valeur offset, limit et retoure un tableau avec les clés offset, limit et page
	public static function parseLimit($value):?array
	{
		$return = null;
		$offset = null;
		$limit = null;
		$page = null;

		if(is_int($value))
		{
			$offset = 0;
			$limit = $value;
			$page = 1;
		}

		elseif(is_array($value) && count($value) === 1 && key($value) !== 0)
		{
			$page = key($value);
			$limit = current($value);

			if(is_int($page) && $page > 0 && is_int($limit) && $limit > 0)
			$offset = (($page * $limit) - $limit);
		}

		else
		{
			if(is_string($value))
			$value = Str::explode(',',$value);

			if(is_array($value))
			{
				$value = array_values($value);
				$value = Arr::cast($value);
				$count = count($value);

				if($count === 1)
				{
					$offset = 0;
					$limit = current($value);
				}

				elseif($count === 2)
				{
					$limit = $value[1];
					$offset = $value[0];
				}

				if(is_int($offset) && is_int($limit) && $limit > 0)
				{
					$page = 1;

					if($offset > 0)
					$page = (int) ceil((($offset + 1) / $limit));
				}
			}
		}

		if(is_int($offset) && is_int($limit) && is_int($page))
		$return = ['offset'=>$offset,'limit'=>$limit,'page'=>$page];

		return $return;
	}


	// limitPage
	// prend une page et une limite et retourne un tableau offset limit, compatible avec sql
	// utile pour page
	public static function limitPage(int $page,int $limit):?array
	{
		$return = null;

		if($page > 0 && $limit > 0)
		{
			$offset = ($page * $limit) - $limit;
			$return = [$offset,$limit];
		}

		return $return;
	}


	// pageSlice
	// comme pour limitPage mais retourne le tableau array slicé aux bons index
	public static function pageSlice(int $page,int $limit,array $array):array
	{
		$return = [];
		$limitPage = static::limitPage($page,$limit);

		if(!empty($limitPage))
		$return = Arr::sliceIndex($limitPage[0],$limitPage[1],$array);

		return $return;
	}


	// slice
	// comme pageSlice mais value peut être de n'importe quel format compatible avec limit dans sql
	public static function slice($value,array $array):array
	{
		$return = [];
		$page = 1;
		$value = static::parseLimit($value);

		if(!empty($value))
		$return = static::pageSlice($value['page'],$value['limit'],$array);

		return $return;
	}


	// pageMax
	// retourne le nombre maximum de page en fonction d'un count et d'une limite par page
	// nav peut être un int ou un tableau
	public static function pageMax($nav,int $limit):?int
	{
		$return = null;

		if(is_array($nav))
		$nav = count($nav);

		if(is_int($nav) && $nav > 0 && $limit > 0)
		$return = (int) ceil($nav / $limit);

		return $return;
	}


	// pageFromIndex
	// retourne le numéro de page d'un index
	// nav peut être un int ou un tableau
	public static function pageFromIndex(int $index,$nav,int $limit):?int
	{
		$return = null;
		$position = $index + 1;

		if(is_array($nav))
		$nav = count($nav);

		if(is_int($nav) && $position >= 1 && $position <= $nav)
		$return = static::pageMax($position,$limit);

		return $return;
	}


	// pages
	// retourne un tableau de toutes les pages en fonction d'un count et d'une limite par page
	// nav peut être un int ou un tableau
	public static function pages($nav,int $limit):array
	{
		$return = [];
		$max = static::pageMax($nav,$limit);

		if(is_int($max))
		{
			for ($i=1; $i <= $max; $i++)
			{
				$return[] = $i;
			}
		}

		return $return;
	}


	// pagesPosition
	// retourne un tableau contenant toutes les pages et la position par rapport à la page courante
	// nav peut être un int ou un tableau
	// retorne null si la page n'existe pas, sinon un tableau
	public static function pagesPosition(int $value,$nav,int $limit):?array
	{
		$return = null;
		$max = static::pageMax($nav,$limit);

		if(is_int($max) && $value > 0 && $value <= $max)
		{
			$return = [];

			for ($i=1; $i <= $max; $i++)
			{
				$return[$i] = $i - $value;
			}
		}

		return $return;
	}


	// pagesClose
	// retourne un tableau contenant les pages entourant la page courante
	// nav peut être un int ou un tableau
	// retorne null si la page n'existe pas, sinon un tableau
	public static function pagesClose(int $value,$nav,int $limit,int $amount=3):?array
	{
		$return = null;

		if($amount > 0)
		{
			$pages = static::pagesPosition($value,$nav,$limit);

			if(!empty($pages))
			{
				foreach (Arr::range(-$amount,$amount) as $v)
				{
					if(in_array($v,$pages,true))
					$return[] = array_search($v,$pages,true);
				}
			}
		}

		return $return;
	}


	// pageSpecificCount
	// retourne le nombre d'éléments contenu dans une page
	// nav peut être un int ou un tableau
	public static function pageSpecificCount(int $value,$nav,int $limit):?int
	{
		$return = null;

		if($value > 0)
		{
			$pageMax = static::pageMax($nav,$limit);

			if(is_int($pageMax))
			{
				if($value < $pageMax)
				$return = $limit;

				elseif($value === $pageMax)
				{
					if(is_array($nav))
					$nav = count($nav);

					if(is_int($nav))
					$return = ($limit * $pageMax) - $nav;
				}
			}
		}

		return $return;
	}


	// pageFirst
	// retourne la première page
	// nav peut être un int ou un tableau
	public static function pageFirst($nav,int $limit):?int
	{
		return (is_int(static::pageMax($nav,$limit)))? 1:null;
	}


	// pagePrev
	// retourne la page précédente
	// nav peut être un int ou un tableau
	public static function pagePrev(int $value,$nav,int $limit):?int
	{
		return (is_int(static::pageMax($nav,$limit)) && ($value - 1) >= 1)? ($value - 1):null;
	}


	// pageNext
	// retourne la page suivante
	// nav peut être un int ou un tableau
	public static function pageNext(int $value,$nav,int $limit):?int
	{
		$return = null;
		$max = static::pageMax($nav,$limit);

		if(is_int($max))
		$return = (($value + 1) <= $max)? ($value + 1):null;

		return $return;
	}


	// pageLast
	// retourne la dernière page
	// nav peut être un int ou un tableau
	public static function pageLast($nav,int $limit):?int
	{
		$return = null;
		$pageMax = static::pageMax($nav,$limit);

		if(is_int($pageMax))
		$return = $pageMax;

		return $return;
	}


	// general
	// retourne un tableau contenant un maximum d'informations relatives aux pages
	// nav peut être un int ou un tableau
	// first et last seulement retourné si différent de prev/next
	public static function general(int $value,$nav,int $limit,int $amount=3):?array
	{
		$return = null;

		if(static::isPage($value,$nav,$limit))
		{
			$return = [];
			$first = static::pageFirst($nav,$limit);
			$last = static::pageLast($nav,$limit);
			$prev = static::pagePrev($value,$nav,$limit);
			$next = static::pageNext($value,$nav,$limit);

			$return['first'] = ($first !== $value && $first !== $prev)? $first:null;
			$return['prev'] = $prev;
			$return['current'] = $value;
			$return['next'] = $next;
			$return['last'] = ($last !== $value && $last !== $next)? $last:null;
			$return['total'] = $last;
			$return['limit'] = $limit;
			$return['isFull'] = static::isPageFull($value,$nav,$limit);
			$return['closest'] = static::pagesClose($value,$nav,$limit,$amount);
		}

		return $return;
	}


	// pagesWithSpecific
	// retourne un tableau multidimensionnel avec les pages et les éléments contenus dans chaque page
	// nav doit être un array
	public static function pagesWithSpecific(array $nav,int $limit):array
	{
		$return = [];

		if($limit > 0)
		{
			$page = 1;
			$int = 0;

			foreach ($nav as $i)
			{
				if($int === $limit)
				{
					$int = 0;
					$page++;
				}

				$return[$page][] = $i;

				$int++;
			}
		}

		return $return;
	}


	// pageWithSpecific
	// retourne les éléments contenus dans une page
	// nav doit être un array
	public static function pageWithSpecific(int $value,array $nav,int $limit):?array
	{
		$return = null;

		if($value > 0)
		{
			$pages = static::pagesWithSpecific($nav,$limit);

			if(array_key_exists($value,$pages) && is_array($pages[$value]))
			$return = $pages[$value];
		}

		return $return;
	}


	// pageFirstSpecific
	// retourne le premier élément contenu dans la page
	// nav doit être un array
	public static function pageFirstSpecific(int $value,array $nav,int $limit)
	{
		$return = null;
		$content = static::pageWithSpecific($value,$nav,$limit);

		if(is_array($content) && !empty($content))
		$return = current($content);

		return $return;
	}


	// pageLastSpecific
	// retourne le dernier élément contenu dans la page
	// nav doit être un array
	public static function pageLastSpecific(int $value,array $nav,int $limit)
	{
		$return = null;
		$content = static::pageWithSpecific($value,$nav,$limit);

		if(is_array($content) && !empty($content))
		$return = Arr::valueLast($content);

		return $return;
	}


	// specificIndex
	// retourne le offset, ou index d'un spécifique
	// nav doit être array
	public static function specificIndex($value,array $nav):?int
	{
		$return = null;
		$index = array_search($value,$nav,true);

		if(is_int($index))
		$return = $index;

		return $return;
	}


	// specificPage
	// retourne le numéro de page d'une valeur specific dans un tableau
	// nav doit être un array
	public static function specificPage($value,array $nav,int $limit):?int
	{
		$return = null;
		$index = static::specificIndex($value,$nav);

		if(is_int($index))
		$return = static::pageFromIndex($index,$nav,$limit);

		return $return;
	}


	// specificFirst
	// retourne le premier contenu du tableau
	// nav doit être un array
	public static function specificFirst(array $nav)
	{
		return Arr::valueFirst($nav);
	}


	// specificPrev
	// retourne le contenu précédent du tableau
	// nav doit être un array
	public static function specificPrev($specific,array $nav)
	{
		return Arr::valueNav($specific,-1,$nav);
	}


	// specificPrevInPage
	// retourne le contenu précédant dans la page
	// nav doit être un array
	public static function specificPrevInPage($specific,array $nav,int $limit)
	{
		$return = null;
		$pageNumber = static::specificPage($specific,$nav,$limit);

		if(is_int($pageNumber))
		{
			$pageSpecific = static::pageWithSpecific($pageNumber,$nav,$limit);

			if(!empty($pageSpecific) && in_array($specific,$pageSpecific,true))
			$return = Arr::valueNav($specific,-1,$pageSpecific);
		}

		return $return;
	}


	// specificNext
	// retourne le contenu suivant du tableau
	// nav doit être un array
	public static function specificNext($specific,array $nav)
	{
		return Arr::valueNav($specific,1,$nav);
	}


	// specificNextInPage
	// retourne le contenu suivant dans la page
	// nav doit être un array
	public static function specificNextInPage($specific,array $nav,int $limit)
	{
		$return = null;
		$pageNumber = static::specificPage($specific,$nav,$limit);
		if(is_int($pageNumber))
		{
			$pageSpecific = static::pageWithSpecific($pageNumber,$nav,$limit);

			if(!empty($pageSpecific) && in_array($specific,$pageSpecific,true))
			$return = Arr::valueNav($specific,1,$pageSpecific);
		}

		return $return;
	}


	// specificLast
	// retourne le dernier contenu du tableau
	// nav doit être un array
	public static function specificLast(array $nav)
	{
		return Arr::valueLast($nav);
	}


	// specific
	// retourne un ensemble d'information sur une entrée spécifique du tableau de nav
	// nav doit être un array
	// first et last seulement retourné si différent de prev/next
	public static function specific($specific,array $nav,int $limit):?array
	{
		$return = null;
		$index = static::specificIndex($specific,$nav);

		if(is_int($index))
		{
			$return = [];
			$first = static::specificFirst($nav);
			$prev = static::specificPrev($specific,$nav);
			$next = static::specificNext($specific,$nav);
			$last = static::specificLast($nav);

			$return['value'] = $specific;
			$return['index'] = $index;
			$return['position'] = ($index + 1);
			$return['total'] = count($nav);
			$return['page'] = static::specificPage($specific,$nav,$limit);

			$return['first'] = ($first !== $specific && $first !== $prev)? $first:null;
			$return['prev'] = $prev;
			$return['next'] = $next;
			$return['last'] = ($last !== $specific && $last !== $next)? $last:null;
		}

		return $return;
	}
}
?>