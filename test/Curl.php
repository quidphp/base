<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// curl
// class for testing Quid\Base\Curl
class Curl extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaJpgUri = Base\Uri::relative($mediaJpg);
        $mediaPhp = '[assertMedia]/php.php';
        $http = Base\Res::open(Base\Uri::absolute($mediaJpgUri));
        $curl = Base\Curl::make(Base\Uri::absolute($mediaPhp));
        $curlOpen = Base\Curl::make(Base\Uri::absolute($mediaJpgUri));

        // is
        assert(Base\Curl::is($curl));
        assert(!Base\Curl::is($http));

        // open

        // close
        assert(Base\Curl::close($curlOpen) === null);

        // meta
        assert(count(Base\Curl::meta($curl)) === 4);

        // info
        assert(count(Base\Curl::info($curl)) > 26);
        assert(Base\Curl::info($http) === null);

        // make
        $res2 = Base\Curl::make('http://perdu.com',true)['resource'];
        assert(is_resource($res2));

        // exec
        $exec = Base\Curl::exec($curl,false);
        assert(count($exec) === 7);
        $res = $exec['resource'];
        assert(is_array(Base\Res::headers($res)));
        assert(Base\Res::mime($res) === 'text/html');
        assert(Base\Res::basename($res) === 'php.html');
        assert(Base\Res::uri($res) === 'php://temp');
        assert(Base\Res::path($res) === 'php.html');
        assert(!empty(Base\Res::size($res)));
        assert(count(Base\Res::responseMeta($res)) === 4);
        assert(Base\Res::isResponsable($res));

        return true;
    }
}
?>