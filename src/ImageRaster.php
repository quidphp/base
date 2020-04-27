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

// imageRaster
// class with static methods to work with pixelated images
final class ImageRaster extends File
{
    // config
    protected static array $config = [
        'mimeGroup'=>'imageRaster', // mime groupe de la classe
        'load'=>null, // extension permise pour la méthode imageRaster::load
        'prefix'=>[ // option image file::temp
            'extension'=>'jpg']
    ];


    // captcha
    // construit une image captcha et retourne une resource temporaire php
    // si font est null, prend la police par défaut
    final public static function captcha(string $value,?string $font=null,$res=null,?array $option=null)
    {
        $return = null;
        $option = Arr::plus(['background'=>[0,0,0],'line'=>[155,155,155],'pixel'=>[155,155,155],'text'=>[255,255,255]],$option);
        $image = null;
        $length = strlen($value);

        if($length > 0 && is_string($font) && is_array($option['text']) && count($option['text']) === 3 && File::is($font))
        {
            $font = File::normalize($font);

            $return = (self::isResource($res))? $res:Res::temp('png');
            $width = $length * 40;
            $str = Str::split(1,$value);
            $image = imagecreatetruecolor($width,50);

            // background
            if(is_array($option['background']) && count($option['background']) === 3)
            {
                $backgroundColor = imagecolorallocate($image,...$option['background']);
                imagefilledrectangle($image,0,0,$width,50,$backgroundColor);
            }

            // line
            if(is_array($option['line']) && count($option['line']) === 3)
            {
                $lineColor = imagecolorallocate($image,...$option['line']);
                for ($i=0; $i < 10; $i++)
                {
                    imageline($image,0,mt_rand() % 50,$width,mt_rand() % 50,$lineColor);
                }
            }

            // pixel
            if(is_array($option['pixel']) && count($option['pixel']) === 3)
            {
                $pixelColor = imagecolorallocate($image,...$option['pixel']);
                for ($i=0; $i < 1000; $i++)
                {
                    imagesetpixel($image,mt_rand() % $width,mt_rand() % 50,$pixelColor);
                }
            }

            // text
            $textColor = imagecolorallocate($image,...$option['text']);
            foreach ($str as $k => $z)
            {
                imagettftext($image,25,0,20 + ($k * 35),35,$textColor,$font,$z);
            }

            // render
            imagepng($image,$return);
            imagedestroy($image);
        }

        return $return;
    }


    // bestFit
    // génère le calcul de résolution pour le redimensionnement best fit
    final public static function bestFit(int $maxWidth,int $maxHeight,int $width,int $height,bool $expand=false):?array
    {
        $image = null;

        if($maxWidth > 0 && $maxHeight > 0 && $width > 0 && $height > 0)
        {
            $newWidth = $width;
            $newHeight = $height;

            if($newWidth > $maxWidth)
            {
                $ratio = $maxWidth / $newWidth;
                $newWidth *= $ratio;
                $newHeight *= $ratio;

                if($newHeight > $maxHeight)
                {
                    $ratio = $maxHeight / $newHeight;
                    $newWidth *= $ratio;
                    $newHeight *= $ratio;
                }
            }

            elseif($newHeight > $maxHeight)
            {
                $ratio = $maxHeight / $newHeight;
                $newWidth *= $ratio;
                $newHeight *= $ratio;

                if($newWidth > $maxWidth)
                {
                    $ratio = $maxWidth / $newWidth;
                    $newWidth *= $ratio;
                    $newHeight *= $ratio;
                }
            }

            $newWidth = (int) $newWidth;
            $newHeight = (int) $newHeight;

            if($expand === true)
            $image = self::bestFitExpand($maxWidth,$maxHeight,$newWidth,$newHeight);
            else
            $image = ['width'=>$newWidth,'height'=>$newHeight];
        }

        return $image;
    }


    // bestFitExpand
    // permet d'agrandir une image qui a été passé dans bestfit pour qu'elle prenne l'espace maximale
    final public static function bestFitExpand(int $maxWidth,int $maxHeight,int $width,int $height):?array
    {
        $image = null;

        if($maxWidth > 0 && $maxHeight > 0 && $width > 0 && $height > 0)
        {
            $newWidth = $width;
            $newHeight = $height;

            $ratioY = ($maxHeight > $newHeight)? ($maxHeight / $newHeight):1;
            $ratioX = ($maxWidth > $newWidth)? ($maxWidth / $newWidth):1;
            $ratio = ($ratioY < $ratioX)? $ratioY:$ratioX;

            if($ratio > 1)
            {
                $newWidth *= $ratio;
                $newHeight *= $ratio;
            }

            $image = ['width'=>(int) $newWidth,'height'=>(int) $newHeight];
        }

        return $image;
    }
}

// init
ImageRaster::__init();
?>