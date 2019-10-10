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
    public static function trigger(array $data):bool
    {
        // prepare
        $escape = Base\Cli::getEscape();

        // write

        // preset

        // eol

        // pos

        // neg

        // neutral

        // make
        assert(Base\Cli::make('test') === 'test');
        assert(Base\Cli::make('test','black',null,'yellow') === $escape.'[0;30m[43mtest[0m');
        assert(strlen(Base\Cli::make([1,2,3],'red',null,'gray')) === 65);

        // makePreset
        assert(Base\Cli::makePreset('pos','test') === $escape.'[0;32m[4mtest[0m');
        assert(Base\Cli::makePreset('neg','test') === $escape.'[0;31m[4mtest[0m');
        assert(Base\Cli::makePreset('neutral','test') === $escape.'[0;30m[47mtest[0m');

        // prepareValue
        assert(strlen(Base\Cli::prepareValue(['test'=>2,3,4])) === 52);

        // getPreset
        assert(Base\Cli::getPreset('pos') === ['green','underline',null]);
        assert(Base\Cli::getPreset('asdas') === []);

        // setPreset
        Base\Cli::setPreset('james',['black','bold','yellow']);
        assert(Base\Cli::makePreset('james','test') === $escape.'[0;30m[1m[43mtest[0m');

        // getEol
        assert(Base\Cli::getEol() === PHP_EOL);

        // getEscape
        assert(Base\Cli::getEscape() === "\033");

        // getForegroundColor
        assert(Base\Cli::getForegroundColor('red') === '0;31');

        // getBackgroundColor
        assert(Base\Cli::getBackgroundColor('black') === 40);
        assert(Base\Cli::getBackgroundColor('blackz') === null);

        // getStyle
        assert(Base\Cli::getStyle('bold') === 1);
        assert(Base\Cli::getStyle('blackz') === null);

        return true;
    }
}
?>