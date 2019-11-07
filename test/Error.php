<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// error
// class for testing Quid\Base\Error
class Error extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $storage = Base\Finder::normalize('[assertCurrent]');
        assert(Base\Dir::reset($storage));

        // setHandler

        // restoreHandler

        // last
        assert(Base\Error::last() === null);

        // clearLast
        assert(Base\Error::clearLast() === null);
        assert(Base\Error::last() === null);

        // log
        assert(Base\Error::log('test'));

        // logEmail
        assert(Base\Error::logEmail('LOLZ','aew@gmailz') === false);

        // logFile
        assert(Base\Error::logFile('what',$storage.'/error.txt') === true);
        $tmp = Base\Res::tmpFile();
        assert(Base\Error::logFile('what',$tmp));
        assert(Base\File::unlink($tmp));

        // logPrepareMessage
        assert(Base\Error::logPrepareMessage(['lol','ok']) === 'lol ok');
        assert(Base\Error::logPrepareMessage('lol') === 'lol');

        // trigger

        // triggers

        // reporting
        assert(Base\Error::reporting() === -1);

        // getCodes
        assert(count(Base\Error::getCodes()) === 16);

        // code
        assert(Base\Error::code(2) === 'E_WARNING');

        // init

        // remove temp folder
        Base\Dir::empty('[assertCurrent]');

        return true;
    }
}
?>