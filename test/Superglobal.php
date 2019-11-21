<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 * Readme: https://github.com/quidphp/base/blob/master/README
 */

namespace Quid\Test\Base;
use Quid\Base;

// superglobal
// class for testing Quid\Base\Superglobal
class Superglobal extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $_GET['test'] = 2;
        $_POST['tes2t'] = 2;
        $_REQUEST['test3'] = 2;

        // hasSession

        // getExists
        assert(Base\Superglobal::getExists('test'));
        assert(!Base\Superglobal::getExists('TEST'));

        // getsExists
        assert(Base\Superglobal::getsExists(['test']));
        assert(!Base\Superglobal::getsExists(['TEST']));

        // postExists
        assert(Base\Superglobal::postExists('tes2t'));

        // postsExists
        assert(Base\Superglobal::postsExists(['tes2t']));
        assert(!Base\Superglobal::postsExists(['Tes2t']));

        // cookieExists
        assert(!Base\Superglobal::cookieExists('tes2t'));

        // sessionExists

        // sessionsExists

        // fileExists
        assert(!Base\Superglobal::fileExists('tes2t'));

        // envExists

        // requestExists
        assert(Base\Superglobal::requestExists('test3'));

        // serverExists
        assert(Base\Superglobal::serverExists('http_host',false));
        assert(Base\Superglobal::serverExists('HTTP_HOST'));

        // hasServerLength
        assert(!Base\Superglobal::hasServerLength());

        // hasServerLengthWithoutPost
        assert(!Base\Superglobal::hasServerLengthWithoutPost());

        // get
        assert(Base\Superglobal::get() === $_GET);
        $x = &Base\Superglobal::get();
        $x['whga'] = true;
        assert($_GET['whga'] === true);

        // post
        assert(Base\Superglobal::post() === $_POST);

        // cookie
        assert(Base\Superglobal::cookie() === $_COOKIE);

        // session

        // files
        assert(Base\Superglobal::files() === $_FILES);

        // env
        assert(Base\Superglobal::env() === $_ENV);

        // request
        assert(Base\Superglobal::request() === $_REQUEST);

        // server
        assert(Base\Superglobal::server() === $_SERVER);

        // getGet
        assert(Base\Superglobal::getGet('test') === 2);

        // getPost
        assert(Base\Superglobal::getPost('tes2t') === 2);

        // getCookie
        assert(Base\Superglobal::getCookie('tes2t') === null);

        // getSession

        // getFiles
        assert(Base\Superglobal::getFiles('tes2t') === null);

        // getEnv

        // getRequest
        assert(Base\Superglobal::getRequest('test3') === 2);

        // getServer
        assert(!empty(Base\Superglobal::getServer('HTTP_HOST')));
        assert(!empty(Base\Superglobal::getServer('http_host',false)));

        // getServerStart
        assert(count(Base\Superglobal::getServerStart('HTTP')) >= 4);
        assert(Base\Superglobal::getServerStart('HTTP') === Base\Superglobal::getServerStart('http',false));
        assert(!empty(Base\Superglobal::getServerStart('HTTP',false,true)['Host']));

        // getServerHeader
        assert(Base\Superglobal::getServerHeader() === Base\Superglobal::getServerStart('HTTP_'));

        // reformatServer
        assert(!empty(Base\Superglobal::reformatServer($_SERVER)['Name']));
        assert(count(Base\Superglobal::reformatServer($_SERVER)) < count($_SERVER));

        // setGet
        Base\Superglobal::setGet('bla','ok');
        assert(Base\Superglobal::getGet('bla') === 'ok');

        // setPost
        Base\Superglobal::setPost('bla',3);
        assert(Base\Superglobal::getPost('bla') === 3);

        // setCookie
        Base\Superglobal::setCookie('blaw','ok');
        assert($_COOKIE['blaw'] === 'ok');

        // setSession

        // setFiles
        Base\Superglobal::setFiles('bla','ok');
        assert($_FILES['bla'] === 'ok');

        // setEnv
        Base\Superglobal::setEnv('bla','ok');
        assert($_ENV['bla'] === 'ok');

        // setRequest
        Base\Superglobal::setRequest('bla','ok');
        assert($_REQUEST['bla'] === 'ok');

        // setServer
        Base\Superglobal::setServer('blaz',2);
        assert($_SERVER['blaz'] === 2);
        Base\Superglobal::setServer('BLAZ',2,false);
        assert(empty($_SERVER['BLAZ']));
        assert(!empty($_SERVER['blaz']));

        // unsetGet
        Base\Superglobal::unsetGet('bla');
        assert(empty($_GET['bla']));

        // unsetPost
        Base\Superglobal::unsetPost('bla');
        assert(empty($_POST['bla']));

        // unsetCookie
        Base\Superglobal::unsetCookie('blaz');
        assert(empty($_COOKIE['bla']));

        // unsetSession

        // unsetFiles
        Base\Superglobal::unsetFiles('bla');
        assert(empty($_FILES['bla']));

        // unsetEnv
        Base\Superglobal::unsetEnv('bla');
        assert(empty($_ENV['bla']));

        // unsetRequest
        Base\Superglobal::unsetRequest('bla');
        assert(empty($_REQUEST['bla']));

        // unsetServer
        Base\Superglobal::unsetServer('blaz',false);
        assert(empty($_SERVER['bla']));

        // unsetServerStart
        Base\Superglobal::setServer('TEST_OK',2);
        assert(Base\Superglobal::getServer('TEST_OK') === 2);
        Base\Superglobal::unsetServerStart('TEST');
        assert(Base\Superglobal::getServer('TEST_OK') === null);

        // unsetServerHeader

        // formatServerKey
        assert(Base\Superglobal::formatServerKey('test-James') === 'TEST_JAMES');

        // postReformat
        $post = ['-test-'=>'non','oui'=>'OK','MAX_FILE_SIZE'=>200];
        assert(Base\Superglobal::postReformat($post,true,true) === ['oui'=>'OK']);

        // filesReformat
        $single = ['ok'=>['name'=>'test.jpg','error'=>2],'ok2'=>['name'=>'test2.jpg','error'=>3]];
        $multi = ['ok'=>['name'=>['test.jpg','ok.lala'],'error'=>[2,3]]];
        $multis = ['ok'=>['name'=>['test.jpg','ok.lala'],'error'=>[2,3]],'ok3'=>['name'=>'test.jpg','error'=>2],'ok2'=>['name'=>['test2.jpg','ok2.lala'],'error'=>[22,32]]];
        $singleR = Base\Superglobal::filesReformat($single);
        $multiR = Base\Superglobal::filesReformat($multi);
        $multisR = Base\Superglobal::filesReformat($multis);
        assert($singleR['ok2'] === ['name'=>'test2.jpg','error'=>3]);
        assert($multiR['ok'][0] === ['name'=>'test.jpg','error'=>2]);
        assert(count($multisR) === 3);
        assert(Base\Superglobal::filesReformat($singleR) === $singleR);
        assert(Base\Superglobal::filesReformat($multiR) === $multiR);
        assert(Base\Superglobal::filesReformat($multisR) === $multisR);

        // cleanup
        $_GET = [];
        $_POST = [];
        $_REQUEST = [];
        $_FILES = [];

        return true;
    }
}
?>