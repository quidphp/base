<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README.md
 */

namespace Quid\Test\Base;
use Quid\Base;

// session
// class for testing Quid\Base\Session
class Session extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $cacheExpire = Base\Session::getCacheExpire();
        $cacheLimiter = Base\Session::getCacheLimiter();
        $savePath = Base\Session::getSavePath();
        $time = Base\Datetime::time();
        $boot = $data['boot'];
        $type = $boot->type();
        $default = ['type'=>$type,'env'=>$boot->env(),'version'=>$boot->version()];
        Base\Session::setDefault($default);
        Base\Session::destroy();
        assert(count(Base\Session::setDefault()) === 8);

        // is
        assert(!Base\Session::is('bla'));

        // isLang
        assert(!Base\Session::isLang(Base\Request::lang()));

        // isIp
        assert(!Base\Session::isIp(Base\Request::ip()));

        // isCsrf
        assert(!Base\Session::isCsrf('sad'));

        // isCaptcha
        assert(!Base\Session::isCaptcha('sad'));

        // isDesktop
        assert(!Base\Session::isDesktop());

        // isMobile
        assert(!Base\Session::isMobile());

        // isOldIe
        assert(!Base\Session::isOldIe());

        // isMac
        assert(!Base\Session::isMac());

        // isLinux
        assert(!Base\Session::isLinux());

        // isWindows
        assert(!Base\Session::isWindows());

        // isBot
        assert(!Base\Session::isBot());

        // isStarted
        assert(!Base\Session::isStarted());
        assert(Base\Session::get('what') === null);
        assert(Base\Session::set('what','bla') === null);
        assert(Base\Session::unset('what') === null);

        // isEmpty
        assert(!Base\Session::isEmpty());

        // isNotEmpty
        assert(!Base\Session::isNotEmpty());

        // hasSaveHandler
        assert(is_bool(Base\Session::hasSaveHandler()));

        // isStructureValid
        assert(!Base\Session::isStructureValid(true));

        // status
        assert(Base\Session::status() === PHP_SESSION_NONE);

        // ini
        assert(count(Base\Session::ini()) === 30);

        // getSid

        // setSid

        // createSid
        assert(strlen(Base\Session::createSid()) === 40);
        assert(strlen(Base\Session::createSid('bla')) === 43);

        // validateId
        assert(Base\Session::validateId(Base\Session::createSid()));
        assert(Base\Session::validateId('abc'.Base\Session::createSid(),'abc'));
        assert(!Base\Session::validateId('abc'.Base\Session::createSid(),'abd'));

        // getPrefix
        assert(Base\Session::getPrefix() === $type.'-');

        // name
        assert(Base\Session::name() === Base\Ini::get('session.name'));

        // setName
        assert(Base\Session::setName('Quid'));

        // getCacheExpire
        assert(Base\Session::getCacheExpire() === Base\Ini::get('session.cache_expire'));

        // setCacheExpire
        assert(Base\Session::setCacheExpire(90));
        assert(Base\Session::setCacheExpire(0));
        assert(Base\Session::setCacheExpire($cacheExpire));

        // getCacheLimiter
        assert(Base\Session::getCacheLimiter() === Base\Ini::get('session.cache_limiter'));

        // setCacheLimiter
        assert(Base\Session::setCacheLimiter('public'));
        assert(!Base\Session::setCacheLimiter('publicz'));
        assert(Base\Session::setCacheLimiter(''));
        assert(Base\Session::setCacheLimiter($cacheLimiter));

        // getModule
        assert(Base\Session::getModule() === Base\Ini::get('session.save_handler'));

        // setModule
        assert(!Base\Session::setModule(''));
        assert(Base\Session::setModule('files'));

        // getSaveHandler

        // setSaveHandler

        // getSerializeHandler
        assert(Base\Session::getSerializeHandler() === 'php_serialize');

        // setSerializeHandler
        assert(Base\Session::setSerializeHandler('php_serialize'));
        assert(Base\Session::setSerializeHandler('php'));

        // getSavePath
        assert(Base\Session::getSavePath() === Base\Ini::get('session.save_path'));

        // setSavePath
        assert(Base\Session::setSavePath('/tmpasddsa'));
        assert(Base\Session::setSavePath(''));

        // getLifetime
        assert(Base\Session::getLifetime() < $time);

        // setLifetime
        assert(Base\Session::setLifetime(10));
        assert(Base\Session::getLifetime() === 10);

        // getExpire
        assert(Base\Session::getExpire() > $time);

        // setExpire
        assert(Base\Session::setExpire($time + 10,10));
        assert(Base\Session::getExpire() === $time + 10);
        assert(Base\Session::getGarbageCollect()['lifetime'] === 20);

        // getGarbageCollect
        assert(count(Base\Session::getGarbageCollect()) === 3);

        // setGarbageCollect
        assert(Base\Session::setGarbageCollect(['probability'=>555,'divisor'=>666,'lifetime'=>777]));
        assert(Base\Session::setGarbageCollect(['probability'=>1,'divisor'=>1000,'lifetime'=>7200]));
        assert(Base\Session::setGarbageCollect(['probability'=>1,'divisor'=>1000,'expires'=>$time + 20,'buffer'=>10]));
        assert(Base\Session::getGarbageCollect()['lifetime'] === 30);
        assert(Base\Session::setGarbageCollect(['probability'=>1,'divisor'=>1000,'lifetime'=>1000,'buffer'=>10]));
        assert(Base\Session::getGarbageCollect()['lifetime'] === 1010);

        // getCookieParams
        assert(count(Base\Session::getCookieParams()) === 6);
        assert(Base\Session::getCookieParams()['samesite'] === 'Lax');

        // setCookieParams
        assert(Base\Session::setCookieParams(['domain'=>Base\Request::host()]));
        assert(Base\Session::setCookieParams(['domain'=>'']));

        // setDefault

        // info
        assert(count(Base\Session::info()) === 16);

        // getStructure
        assert(Base\Session::getStructure(null) === []);
        assert(count(Base\Session::getStructure(true)) === 13);

        // prepareStructure

        // structureEnv

        // structureType

        // structureVersion

        // structureExpire

        // structureTimestamp

        // structureRequestCount

        // structureUserAgent

        // structureIp

        // structureFingerprint

        // structureLang

        // structureCsrf

        // structureCaptcha

        // structureRemember

        // start
        assert(Base\Session::start(true,true));
        assert(Base\Session::isStructureValid(true));
        assert(Base\Session::destroy());
        assert(Base\Session::start(true,true));
        assert(Base\Session::isNotEmpty());
        assert($_SESSION['requestCount'] === 1);
        assert(count(Base\Session::getStructure()) === 13);
        assert(Base\Session::isLang(Base\Session::lang()));
        assert(Base\Session::isIp(Base\Request::ip()));
        assert(Base\Session::isCsrf(Base\Session::csrf()));

        // restart

        // setCookie

        // regenerateId

        // encode
        $_SESSION['test'] = 'what';
        $encode = Base\Session::encode();
        assert(is_string($encode));
        $_SESSION['test'] = 'what2';

        // decode
        assert(Base\Session::decode($encode));
        assert($_SESSION['test'] === 'what');

        // reset
        assert(Base\Session::reset());
        $_SESSION['test'] = 'what3';
        assert(Base\Session::isStarted());

        // abort
        $_SESSION['test'] = 'what4';
        assert(Base\Session::abort());
        assert(!Base\Session::abort(true));
        assert(!Base\Session::isStarted());
        assert(empty($_SESSION));
        assert(Base\Session::start());

        // commit
        $_SESSION['test'] = 'what4';
        assert(Base\Session::isStarted());
        assert(Base\Session::commit(true));
        assert(!Base\Session::isStarted());
        assert(empty($_SESSION));
        assert(Base\Session::start());

        // empty
        assert(Base\Session::empty());
        assert(Base\Session::isStarted());
        assert(empty($_SESSION));
        $_SESSION['test'] = 'what4';

        // unsetArray
        Base\Session::unsetArray();
        assert(empty($_SESSION));
        $_SESSION['test'] = 'what4';

        // unsetCookie

        // destroy

        // garbageCollect
        assert(is_int(Base\Session::garbageCollect()));

        // data
        $dataz =& Base\Session::data();
        $dataz['whaszzzx'] = true;
        assert($_SESSION['whaszzzx'] === true);
        assert(Base\Session::get('whaszzzx') === true);
        assert(!Base\Session::isStructureValid(true));
        assert(Base\Session::prepareStructure('insert',true));

        // get
        assert(Base\Session::get('sadas') === null);

        // env
        assert(is_string(Base\Session::env()));

        // type
        assert(is_string(Base\Session::type()));

        // version
        assert(Base\Session::version() === $boot->version());

        // expire
        assert(!empty(Base\Session::expire()));

        // timestampCurrent
        assert(is_int(Base\Session::timestampCurrent()));

        // timestampPrevious
        assert(Base\Session::timestampPrevious() === null);

        // timestampDifference
        assert(Base\Session::timestampDifference() === null);

        // requestCount
        assert(is_int(Base\Session::requestCount()));

        // resetRequestCount
        Base\Session::resetRequestCount();
        assert(Base\Session::requestCount() === 1);

        // userAgent
        assert(is_string(Base\Session::userAgent()));

        // browserCap
        assert(count(Base\Session::browserCap()) === 10);

        // browserName
        assert(!empty(Base\Session::browserName()));

        // browserPlatform
        assert(!empty(Base\Session::browserPlatform()));

        // browserDevice
        assert(!empty(Base\Session::browserDevice()));

        // ip
        assert(is_string(Base\Session::ip()));

        // fingerprint
        assert(Base\Session::fingerprint() === null);

        // lang
        assert(is_string(Base\Session::lang()));

        // setLang
        assert(!Base\Session::setLang('bla'));
        assert(Base\Session::setLang(Base\Session::lang()));

        // csrf
        assert(is_string(Base\Session::csrf()));
        assert(Base\Session::csrf() !== Base\Session::csrf(true));

        // getCsrfOption
        assert(count(Base\Session::getCsrfOption()) === 4);

        // getCsrfName
        assert(Base\Session::getCsrfName() === '-csrf-');

        // makeCsrf
        assert(strlen(Base\Session::makeCsrf()) === 40);

        // refreshCsrf
        Base\Session::refreshCsrf();

        // captcha
        assert(Base\Session::captcha() === null);
        assert(is_string(Base\Session::captcha(true)));

        // getCaptchaOption
        assert(count(Base\Session::getCaptchaOption()) === 5);

        // getCaptchaName
        assert(Base\Session::getCaptchaName() === '-captcha-');

        // makeCaptcha
        assert(strlen(Base\Session::makeCaptcha()) === 6);

        // refreshCaptcha
        assert(Base\Session::refreshCaptcha() === null);
        assert(Base\Session::isCaptcha(Base\Session::captcha()));
        assert(!Base\Session::isCaptcha(strtolower(Base\Session::captcha()),true));
        assert(Base\Session::isCaptcha(strtolower(Base\Session::captcha()),false));
        assert(Base\Session::isCaptcha(strtolower(Base\Session::captcha())));

        // emptyCaptcha
        assert(Base\Session::emptyCaptcha() === null);
        assert(Base\Session::captcha() === null);

        // remember
        assert(Base\Session::remember() === null);

        // setRemember
        Base\Session::setRemember('username','test');
        assert(Base\Session::remember('username') === 'test');

        // setsRemember
        Base\Session::setsRemember(['username'=>'test2']);
        assert(Base\Session::remember('username') === 'test2');
        assert(Base\Session::remember() === ['username'=>'test2']);

        // unsetRemember
        Base\Session::unsetRemember('username');
        assert(Base\Session::remember() === []);

        // rememberEmpty
        Base\Session::rememberEmpty();
        assert(Base\Session::remember() === null);
        Base\Session::setRemember('username','test');
        assert(Base\Session::remember('username') === 'test');
        Base\Session::rememberEmpty();

        // set
        Base\Session::set('sadas','bvla');
        assert(Base\Session::get('sadas') === 'bvla');
        assert($_SESSION['sadas'] === 'bvla');
        assert(Base\Session::is('sadas'));

        // unset
        Base\Session::unset('sadas');
        assert(Base\Session::get('sadas') === null);
        assert(!Base\Session::is('sadas'));

        // cleanup
        assert(Base\Session::destroy());
        Base\Session::setSavePath($savePath);
        $boot->session()->setLang('en');

        return true;
    }
}
?>