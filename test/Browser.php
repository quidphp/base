<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// browser
// class for testing Quid\Base\Browser
class Browser extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        Base\Browser::emptyCacheStatic();

        // is
        assert(Base\Browser::is('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));

        // isDesktop
        assert(Base\Browser::isDesktop('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));

        // isMobile
        assert(Base\Browser::isMobile('Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36'));

        // isOldIe
        assert(Base\Browser::isOldIe('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0'));
        assert(Base\Browser::isOldIe('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0'));
        assert(Base\Browser::isOldIe('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)'));
        assert(!Base\Browser::isOldIe('Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko'));
        assert(!Base\Browser::isOldIe('Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0'));

        // isMac
        assert(Base\Browser::isMac('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));

        // isLinux
        assert(Base\Browser::isLinux('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1'));

        // isWindows
        assert(Base\Browser::isWindows('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'));

        // isBot
        assert(!Base\Browser::isBot('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert(Base\Browser::isBot('googlebot'));

        // cap
        assert(is_array(Base\Browser::cap('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8')));

        // name
        assert('Safari' === Base\Browser::name('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert('Safari' === Base\Browser::name('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert('Android WebView' === Base\Browser::name('Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36'));
        assert('Chrome' === Base\Browser::name('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'));
        assert('Firefox' === Base\Browser::name('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1'));
        assert('Safari' === Base\Browser::name('Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10'));
        assert(null === Base\Browser::name(''));
        assert('IE' === Base\Browser::name('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)'));
        assert('IE' === Base\Browser::name('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0'));

        // platform
        assert('MacOSX' === Base\Browser::platform('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert('Android' === Base\Browser::platform('Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36'));
        assert('Win7' === Base\Browser::platform('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'));
        assert('Linux' === Base\Browser::platform('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1'));
        assert('iOS' === Base\Browser::platform('Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10'));
        assert(null === Base\Browser::platform(''));

        // device
        assert('Desktop' === Base\Browser::device('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8'));
        assert('Tablet' === Base\Browser::device('Mozilla/5.0 (Linux; Android 6.0.1; SGP771 Build/32.2.A.0.253; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/52.0.2743.98 Safari/537.36'));
        assert('Desktop' === Base\Browser::device('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36'));
        assert('Desktop' === Base\Browser::device('Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1'));
        assert('Tablet' === Base\Browser::device('Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10'));
        assert(null === Base\Browser::device(''));

        // cacheStatic
        assert(count(Base\Browser::allCacheStatic()) === 12);
        assert(Base\Browser::emptyCacheStatic() === true);
        assert(Base\Browser::allCacheStatic() === []);

        // cacheFile

        return true;
    }
}
?>