<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// cli
// class for testing Quid\Base\Cli
class Cli extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $escape = Base\Cli::getEscape();
        $eol = Base\Cli::eol();
        $eoll = strlen($eol);

        // is
        assert(is_bool(Base\Cli::is()));

        // isHtmlOverload
        assert(is_bool(Base\Cli::isHtmlOverload()));

        // parseLongOptions
        assert(Base\Cli::parseLongOptions('--james2=2','--lol','--james2=3','lo') === ['james2'=>3,'lol'=>'']);
        assert(Base\Cli::parseLongOptions('--','--z','--=','--é;é=4') === ['z'=>'','é;é'=>4]);
        assert(Base\Cli::parseLongOptions('--==3') === []);
        assert(Base\Cli::parseLongOptions('--=3') === []);

        // callStatic
        assert(Base\Cli::getPos('test') === $escape.'[0;32m[1m[40mtest[0m'.$eol);
        assert(Base\Cli::getNeg('test',2) === $escape.'[1;37m[1m[41mtest[0m'.$eol.$eol);
        assert(Base\Cli::getNeutral('test',0) === $escape.'[0;30m[47mtest[0m');

        // flush

        // flushPreset

        // flushEol

        // make
        assert(Base\Cli::make('test') === 'test'.$eol);
        assert(Base\Cli::make('test','black',null,'yellow') === $escape.'[0;30m[43mtest[0m'.$eol);
        assert(strlen(Base\Cli::make([1,2,3],'red',null,'gray')) === (65 + $eoll));

        // makeCli
        assert(strlen(Base\Cli::makeCli([1,2,3],'red','underline','gray')) === (69 + $eoll));

        // makeHtml
        assert(Base\Cli::makeHtml('meé','cyan','bold','white') === "<div style='color: cyan; font-weight: bold; padding: 5px;'>meé</div><br />");
        assert(strlen(Base\Cli::makeHtml([1,2,3],'red','underline','gray')) === 152);

        // preset
        assert(Base\Cli::preset('pos','test') === $escape.'[0;32m[1m[40mtest[0m'.$eol);
        assert(Base\Cli::preset('neg','test') === $escape.'[1;37m[1m[41mtest[0m'.$eol);
        assert(Base\Cli::preset('neutral','test') === $escape.'[0;30m[47mtest[0m'.$eol);

        // eol
        assert(Base\Cli::eol() === PHP_EOL);

        // prepareValue
        assert(strlen(Base\Cli::prepareValue(['test'=>2,3,4])) === 52);

        // getPreset
        assert(Base\Cli::getPreset('pos') === ['green','bold','black']);
        assert(Base\Cli::getPreset('asdas') === []);

        // setPreset
        Base\Cli::setPreset('james',['black','bold','yellow']);
        assert(Base\Cli::preset('james','test') === $escape.'[0;30m[1m[43mtest[0m'.$eol);

        // getEscape
        assert(Base\Cli::getEscape() === "\033");

        // getForegroundColor
        assert(Base\Cli::getForegroundColor('red') === '0;31');
        assert(Base\Cli::getForegroundColor('red',1) === ['color'=>'red']);

        // getBackgroundColor
        assert(Base\Cli::getBackgroundColor('black',0) === 40);
        assert(Base\Cli::getBackgroundColor('blackz') === null);
        assert(Base\Cli::getBackgroundColor('black',1) === ['background-color'=>'black']);

        // getStyle
        assert(Base\Cli::getStyle('bold') === 1);
        assert(Base\Cli::getStyle('blackz') === null);
        assert(Base\Cli::getStyle('bold',1) === ['font-weight'=>'bold']);

        // setHtmlOverload

        return true;
    }
}
?>