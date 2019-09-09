<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// buffer
// class for testing Quid\Base\Buffer
class Buffer extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $content = Base\Buffer::getAll(false);
        Base\Buffer::cleanAll();

        // has
        assert(Base\Buffer::has());

        // count
        assert(Base\Buffer::count() === 1);

        // status
        assert(count(Base\Buffer::status()) === 1);

        // handler
        assert(count(Base\Buffer::handler()) === 1);

        // size
        assert(Base\Buffer::size() === 0);
        echo 'YA';
        assert(Base\Buffer::size() === 2);

        // start
        assert(Base\Buffer::start());
        assert(Base\Buffer::count() === 2);

        // startEcho
        assert(Base\Buffer::startEcho('OK'));
        assert(Base\Buffer::count() === 3);
        assert(Base\Buffer::get() === 'OK');
        assert(Base\Buffer::endClean());
        assert(Base\Buffer::count() === 2);

        // startCallGet
        assert(Base\Buffer::startCallGet('print_r',['JAMES']) === 'JAMES');

        // get
        echo 'TEST';
        assert(Base\Buffer::get() === 'TEST');
        assert(Base\Buffer::start());
        assert(Base\Buffer::count() === 3);
        assert(Base\Buffer::get() === '');

        // getAll
        echo 'JAMES';
        assert(Base\Buffer::getAll(true) === 'YATESTJAMES');
        assert(Base\Buffer::count() === 1);
        assert(Base\Buffer::getAll(false) === 'YATESTJAMES');
        assert(Base\Buffer::getAll(false) === '');
        assert(Base\Buffer::count() === 1);
        echo 'YA';
        assert(Base\Buffer::start());
        assert(Base\Buffer::start());

        // getClean
        assert(Base\Buffer::getClean() === '');
        assert(Base\Buffer::count() === 2);
        assert(Base\Buffer::start());
        echo 'BLA';

        // getCleanAll
        assert(Base\Buffer::getCleanAll()[2] === 'YA');
        assert(Base\Buffer::start());
        assert(Base\Buffer::start());
        assert(Base\Buffer::start());

        // getCleanAllEcho

        // flush

        // keepFlush

        // endFlush

        // endFlushAll

        // endFlushAllStart

        // clean
        echo 'BLA';
        assert(Base\Buffer::count() === 3);
        assert(Base\Buffer::get() === 'BLA');
        assert(Base\Buffer::clean());
        assert(Base\Buffer::get() === '');
        assert(Base\Buffer::count() === 3);

        // cleanAll
        echo 'BLA';
        assert(Base\Buffer::cleanAll() === [3=>true,2=>true,1=>true]);
        assert(Base\Buffer::count() === 1);
        assert(Base\Buffer::start());
        assert(Base\Buffer::start());

        // cleanEcho
        echo 'WHAT';
        assert(Base\Buffer::count() === 3);
        assert(Base\Buffer::cleanEcho('OK'));
        assert(Base\Buffer::get() === 'OK');
        assert(Base\Buffer::count() === 3);

        // cleanAllEcho
        assert(Base\Buffer::cleanAllEcho('OKz'));
        assert(Base\Buffer::get() === 'OKz');
        assert(Base\Buffer::count() === 1);
        assert(Base\Buffer::start());
        assert(Base\Buffer::start());

        // endClean
        assert(Base\Buffer::endClean());
        assert(Base\Buffer::count() === 2);

        // endCleanAll
        assert(Base\Buffer::endCleanAll());
        assert(Base\Buffer::count() === 0);
        assert(Base\Buffer::size() === null);
        Base\Buffer::start([Base\Response::class,'autoContentType']);
        assert(Base\Buffer::count() === 1);

        // startEchoEndFlush

        // startEchoEndFlushAllStart

        // prependEcho
        echo 'YA';
        assert(Base\Buffer::prependEcho('BLA'));

        // appendEcho
        assert(Base\Buffer::prependEcho('AF'));
        assert(Base\Buffer::count() === 1);
        assert(Base\Buffer::getAll(false) === 'AFBLAYA');
        assert(Base\Buffer::getAll(false) === '');
        assert(Base\Buffer::count() === 1);

        // reoutput buffer
        echo $content;

        return true;
    }
}
?>