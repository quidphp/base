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

// response
// class for testing Quid\Base\Response
class Response extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $isCli = Base\Server::isCli();
        $content = Base\Buffer::getAll(false);
        $protocol = Base\Server::httpProtocol();
        Base\Buffer::cleanAll();

        // is200
        assert(Base\Response::is200());

        // isCodePositive
        assert(Base\Response::isCodePositive());

        // isCodeLoggable
        assert(!Base\Response::isCodeLoggable());

        // isCodeError
        assert(!Base\Response::isCodeError());
        assert(Base\Response::setCode(404));
        assert(Base\Response::isCodeIn(449));
        assert(Base\Response::isCodeError());
        assert(Base\Response::setCode(200));
        assert(!Base\Response::isCodeError());

        // isCodeServerError
        assert(Base\Response::setCode(500));
        assert(Base\Response::isCodeServerError());
        assert(Base\Response::setCode(200));
        assert(!Base\Response::isCodeServerError());

        // isHttp1
        assert(is_bool(Base\Response::isHttp1()));

        // isHttp2
        assert(is_bool(Base\Response::isHttp2()));

        // isHtml
        assert(Base\Response::setContentType('html'));
        assert(is_bool(Base\Response::isHtml('html')));

        // isHtmlOrAuto
        assert(is_bool(Base\Response::isHtmlOrAuto('html')));

        // isJson
        assert(Base\Response::setContentType('json'));
        assert(is_bool(Base\Response::isJson()));

        // isXml
        assert(Base\Response::setContentType('xml'));
        assert(is_bool(Base\Response::isXml()));
        assert(Base\Response::setContentType('html'));

        // isConnectionNormal
        assert(Base\Response::isConnectionNormal());

        // isConnectionAborted
        assert(Base\Response::isConnectionAborted() === false);

        // areHeadersSent
        assert(Base\Response::areHeadersSent() === false);

        // isCode
        assert(Base\Response::isCode(200));
        assert(Base\Response::isCode(404,200));
        assert(Base\Response::isCode('200'));
        assert(!Base\Response::isCode('202'));

        // isCodeBetween
        assert(Base\Response::isCodeBetween(190,210));
        assert(Base\Response::isCodeBetween(200,210));
        assert(!Base\Response::isCodeBetween(201,210));

        // isCodeIn
        assert(Base\Response::isCodeIn(200));
        assert(Base\Response::isCodeIn(299));
        assert(!Base\Response::isCodeIn(300));
        assert(!Base\Response::isCodeIn(100));

        // isContentType
        assert(Base\Response::setContentType('application/pdf'));
        assert(is_bool(Base\Response::isContentType('pdf')));
        assert(Base\Response::setContentType('html'));

        if($isCli === false)
        {
            assert(Base\Response::isContentType('html'));
            assert(Base\Response::isContentType('text/html'));
            assert(Base\Response::isContentType('text/html; charset=UTF-8'));
        }

        // headerExists
        assert(is_bool(Base\Response::headerExists('PRAGMA')));
        if($isCli === false)
        assert(Base\Response::headerExists('PRAgma'));
        assert(!Base\Response::headerExists('asdasdds'));

        // headersExists
        if($isCli === false)
        assert(Base\Response::headersExists(['PRAGMA']));
        assert(!Base\Response::headersExists(['PRAGMA','test']));

        // id
        assert(strlen(Base\Response::id()) === 10);

        // timeLimit

        // connectionStatus
        assert(Base\Response::connectionStatus() === 0);

        // ignoreUserAbort
        assert(Base\Response::ignoreUserAbort(true) === true);
        assert(Base\Response::ignoreUserAbort(true) === true);
        assert(Base\Response::ignoreUserAbort(false) === false);

        // headersSentFrom
        assert(Base\Response::headersSentFrom() === null);

        // protocol
        assert(Base\Response::protocol() === $protocol);

        // code
        assert(Base\Response::code() === 200);

        // statusText
        assert(Base\Response::statusText() === 'OK');

        // status
        assert(Base\Response::status() === $protocol.' 200 OK');

        // setCode
        assert(Base\Response::setCode(404));
        assert(Base\Response::setCode(404) === true);
        assert(Base\Response::code() === 404);
        assert(Base\Response::setCode(200));

        // setStatus
        assert(!Base\Response::setStatus(621,'LOL'));
        assert(Base\Response::setStatus(302));
        assert(Base\Response::code() === 302);
        assert(Base\Response::status() === $protocol.' 302 Found');

        // ok
        assert(Base\Response::ok());
        assert(Base\Response::code() === 200);

        // moved
        assert(Base\Response::moved());
        assert(Base\Response::code() === 302);
        assert(Base\Response::moved(false));
        assert(Base\Response::code() === 301);

        // movedCode
        assert(Base\Response::movedCode(false) === 301);
        assert(Base\Response::movedCode(true) === 302);
        assert(Base\Response::movedCode(304) === 304);
        assert(Base\Response::movedCode(404) === null);
        assert(Base\Response::movedCode(null) === 302);

        // error
        assert(Base\Response::error(400));
        assert(Base\Response::status() === $protocol.' 400 Bad Request');

        // notFound
        assert(Base\Response::notFound());
        assert(Base\Response::code() === 404);
        assert(Base\Response::ok());

        // serverError
        assert(Base\Response::serverError());
        assert(Base\Response::code() === 500);
        assert(Base\Response::ok());

        // redirect

        // redirectReferer

        // redirectSchemeHost

        // download

        // toScreen

        // downloadToScreen

        // headers
        assert(Base\Arr::isIndexed(Base\Response::headers(false)));
        assert(Base\Arr::isAssoc(Base\Response::headers()));

        // getHeader
        assert(Base\Response::getHeader('PRAGMAz') === null);
        if($isCli === false)
        {
            assert(is_string(Base\Response::getHeader('PRAGMA')));
            assert(is_string(Base\Response::getHeader('pragma')));
        }

        // getsHeader
        assert(count(Base\Response::getsHeader(['praGma'])) === 1);

        // contentType
        assert(Base\Response::setContentType('html'));
        if($isCli === false)
        {
            assert(Base\Response::contentType(0) === 'text/html; charset=UTF-8');
            assert(Base\Response::contentType(1) === 'text/html');
            assert(Base\Response::contentType(2) === 'html');
        }
        assert(Base\Response::setContentType('text/plain'));
        if($isCli === false)
        assert(Base\Response::contentType(0) === 'text/plain; charset=UTF-8');
        assert(Base\Response::setContentType('txt'));
        if($isCli === false)
        assert(Base\Response::contentType(0) === 'text/plain; charset=UTF-8');
        assert(Base\Response::setContentType('html'));

        // setHeader
        assert(Base\Response::setHeader('test','OK') === 1);
        assert(Base\Response::setHeader('test','OK2') === 1);
        assert(Base\Response::setHeader('test',['OK3','OK4'],false) === 2);
        if($isCli === false)
        {
            assert(Base\Response::getHeader('test') === ['OK2','OK3','OK4']);
            assert(Base\Response::headersExists(['test']));
        }
        else
        assert(Base\Response::getHeader('test') === null);

        // setsHeader
        assert(Base\Response::setsHeader(['test2'=>'ok']) === ['test2'=>1]);
        assert(Base\Response::setsHeader(['test2'=>['ok3','ok2']]) === ['test2'=>2]);
        if($isCli === false)
        {
            assert(Base\Response::getHeader('test2') === ['ok3','ok2']);
            assert(Base\Response::headerExists('test2'));
        }
        assert(Base\Response::setHeader('Last-Modified',Base\Datetime::gmt(Base\Datetime::now())) === 1);
        assert(Base\Response::setHeader('last-modified',Base\Datetime::gmt(Base\Datetime::now() + 1)) === 1);
        if($isCli === false)
        assert(Base\Response::getHeader('Last-Modified') === Base\Response::getHeader('last-modified'));

        // setContentType
        assert(Base\Response::setContentType('html'));

        // unsetHeader
        assert(is_bool(Base\Response::unsetHeader('test2')));
        assert(!Base\Response::headerExists('test2'));
        if($isCli === false)
        assert(Base\Response::unsetHeader('content-type'));

        // unsetsHeader
        assert(array_keys(Base\Response::unsetsHeader(['test','test2'])) === ['test','test2']);
        assert(Base\Response::getHeader('test2') === null);

        // emptyHeader

        // prepare

        // setHeaderCallback

        // prepareHeaderDefault

        // setsHeaderDefault

        // body
        echo 'YA';
        assert(empty(Base\Response::headers()['Content-Type']));
        assert(Base\Response::body() === 'YA');
        if($isCli === false)
        {
            assert(Base\Response::headers()['Content-Type'] === 'text/html; charset=UTF-8'); // ob_clean sur le premier buffer déclenche quand même le callback sur le premier buffer
            assert(Base\Response::unsetHeader('Content-type'));
        }
        assert(empty(Base\Response::headers()['Content-Type']));
        assert(Base\Response::body() === 'YA');
        if($isCli === false)
        assert(Base\Response::headers()['Content-Type'] === 'text/html; charset=UTF-8');

        // setBody
        assert(Base\Response::setBody('OK'));

        // prependBody
        assert(Base\Response::prependBody('BE'));

        // appendBody
        assert(Base\Response::appendBody('AF'));
        assert(Base\Response::body() === 'BEOKAF');

        // emptyBody
        assert(Base\Response::emptyBody());
        assert(Base\Response::body() === '');
        Base\Response::unsetHeader('Content-type');

        // sleep

        // sleepUntil

        // kill

        // onShutDown
        Base\Response::onShutDown(function($a) { assert($a === '5'); },'5');

        // onCloseDown

        // emptyCloseDown

        // closeDown

        // onShutDownCloseDown

        // onCloseBody

        // emptyCloseBody

        // closeBody

        // onCloseDownCloseBody

        // speedOnCloseBody

        // closeDownBody

        // getAutoContentType
        assert(Base\Response::getAutoContentType('[1,2,3]') === 'json');

        // autoContentType
        assert(Base\Response::contentType() === null);
        assert(is_string(Base\Response::autoContentType('')));
        if($isCli === false)
        {
            assert(Base\Response::contentType() === 'html');
            assert(Base\Response::unsetHeader('Content-Type'));
            assert(is_string(Base\Response::autoContentType('[1,2,3]')));
            assert(Base\Response::contentType() === 'json');
            assert(Base\Response::unsetHeader('Content-Type'));
            assert(is_string(Base\Response::autoContentType('{"test":"la"}')));
            assert(Base\Response::contentType() === 'json');
            assert(Base\Response::unsetHeader('Content-Type'));
            assert(is_string(Base\Response::autoContentType('<?xml ')));
            assert(Base\Response::contentType() === 'xml');
            assert(Base\Response::unsetHeader('Content-Type'));
        }

        // reoutput buffer
        echo $content;

        return true;
    }
}
?>