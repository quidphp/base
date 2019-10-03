<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// cookie
// class for testing Quid\Base\Cookie
class Cookie extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // is
        assert(!Base\Cookie::is('test'));

        // get
        assert(Base\Cookie::get('test') !== 'blaz');

        // set
        assert(Base\Cookie::set('test','blaz'));

        // unset
        assert(Base\Cookie::unset('test'));

        // option
        assert(count(Base\Cookie::option('set')) === 6);
        assert(Base\Cookie::option('set',['lifetime'=>null,'expires'=>Base\Date::time() + 1]) === Base\Cookie::option('set',['lifetime'=>1,'expires'=>null]));
        assert(Base\Cookie::option('set',['lifetime'=>0])['expires'] === Base\Date::time());
        assert(!array_key_exists('lifetime',Base\Cookie::option('set',['lifetime'=>0])));
        assert(Base\Cookie::option('set')['expires'] > Base\Date::getTimestamp());
        assert(count(Base\Cookie::option('unset')) === 6);
        assert(Base\Cookie::option('unset')['expires'] < Base\Date::getTimestamp());
        assert(Base\Cookie::option('unset',['lifetime'=>0])['expires'] < Base\Date::time());
        assert(!array_key_exists('lifetime',Base\Cookie::option('unset',['lifetime'=>0])));
        assert(empty(Base\Cookie::option('removez')));
        assert(Base\Cookie::option('set',['lifetime'=>200])['expires'] > 200);
        assert(Base\Cookie::option('set',['expires'=>200])['expires'] === 200);

        return true;
    }
}
?>