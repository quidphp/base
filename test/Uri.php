<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// uri
// class for testing Quid\Base\Uri
class Uri extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        Base\Uri::setShortcut('tdn','https://tdn.google.com');
        Base\Uri::setShortcut('extra','https://tdn.google.com/relative/ok');
        assert(!empty(Base\Uri::schemeStatic(['scheme.com'=>true,'james.com'=>443,'notScheme.com'=>'http'])));

        // is
        assert(Base\Uri::is('/test.php?test=yeah'));
        assert(!Base\Uri::is(''));
        assert(!Base\Uri::is('//'));
        assert(!Base\Uri::is('http://'));
        assert(!Base\Uri::is('http:'));
        assert(!Base\Uri::is('test.php'));
        assert(Base\Uri::is('/test.php'));
        assert(Base\Uri::is('/testééèèè!@*#(@(@`~\)).php'));
        assert(Base\Uri::is('/test.php?lol=yeah'));
        assert(Base\Uri::is('http://google.com/test.php'));
        assert(Base\Uri::is('//google.com/test.php'));
        assert(Base\Uri::is('http://google.com'));
        assert(Base\Uri::is('//google.com'));
        assert(Base\Uri::is('http://google.com/test.com'));
        assert(Base\Uri::is('http://google.com/test.php?bla=jaja'));
        assert(!Base\Uri::is('google.com/test.php?bla=jaja'));
        assert(!Base\Uri::is('zx'));
        assert(Base\Uri::is('/james.jom'));
        assert(!Base\Uri::is(''));
        assert(!Base\Uri::is([]));
        assert(Base\Uri::is('/'));
        assert(Base\Uri::is('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true));

        // isRelative
        assert(Base\Uri::isRelative('/test.php?james=oui'));
        assert(!Base\Uri::isRelative('//test.php'));
        assert(Base\Uri::isRelative('/'));
        assert(!Base\Uri::isRelative('test.php'));
        assert(Base\Uri::isRelative('/test.php'));
        assert(Base\Uri::isRelative('/test.php?lol=yeah'));
        assert(!Base\Uri::isRelative('http://google.com/test.php'));
        assert(!Base\Uri::isRelative('//google.com/test.php'));
        assert(Base\Uri::isRelative('/test/[ok]-1/test3/[four]/[five]'));

        // isAbsolute
        assert(!Base\Uri::isAbsolute('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4'));
        assert(Base\Uri::isAbsolute('https://google.com/test.php'));
        assert(Base\Uri::isAbsolute('//google.com/test.php'));
        assert(!Base\Uri::isAbsolute('/google.com/test.php'));
        assert(Base\Uri::isAbsolute('http://google.com'));
        assert(Base\Uri::isAbsolute('//google.com'));
        assert(Base\Uri::isAbsolute('http://google.com/test.com'));
        assert(Base\Uri::isAbsolute('http://google.com/test.php?bla=jaja'));
        assert(!Base\Uri::isAbsolute('google.com/test.php?bla=jaja'));
        assert(!Base\Uri::isAbsolute('zx'));
        assert(!Base\Uri::isAbsolute('/james.jom'));
        assert(Base\Uri::isAbsolute('http://www.test/[ok]-1/test3/[four]/[five]'));
        assert(Base\Uri::isAbsolute('https://www.test/[ok]-1/test3/[four]/[five]'));
        assert(Base\Uri::isAbsolute('//test/[ok]-1/test3/[four]/[five]'));
        assert(!Base\Uri::isAbsolute('/test/[ok]-1/test3/[four]/[five]'));

        // isSsl
        assert(Base\Uri::isSsl('https://www.google.com'));
        assert(!Base\Uri::isSsl('http://www.google.com'));

        // isSchemeValid
        assert(Base\Uri::isSchemeValid('https'));
        assert(Base\Uri::isSchemeValid('HTTP'));
        assert(!Base\Uri::isSchemeValid('FTps'));

        // isSchemeProtocolRelative
        assert(true === Base\Uri::isSchemeProtocolRelative('//www.google.com/test'));
        assert(true === Base\Uri::isSchemeProtocolRelative('//'));
        assert(true === Base\Uri::isSchemeProtocolRelative('///'));
        assert(false === Base\Uri::isSchemeProtocolRelative('www.google.com/test'));

        // hasScheme
        assert(Base\Uri::hasScheme('http://test.com'));
        assert(!Base\Uri::hasScheme('test.com'));
        assert(Base\Uri::hasScheme('//test.com'));
        assert(Base\Uri::hasScheme('https://test.com'));

        // hasExtension
        assert(false === Base\Uri::hasExtension('http://www.google.com/test'));
        assert(true === Base\Uri::hasExtension('http://www.google.com/test.php'));

        // hasQuery
        assert(Base\Uri::hasQuery('http://www.google.com/test/ta?bla=lala#asdas'));
        assert(!Base\Uri::hasQuery('http://www.google/test'));

        // hasLang
        assert(Base\Uri::hasLang('https://google.com/en/test'));

        // isInternal
        assert(Base\Uri::isInternal('/test.jpg'));
        assert(!Base\Uri::isInternal('http://google.com/test'));
        assert(Base\Uri::isInternal('http://google.com/test','google.com'));
        assert(Base\Uri::isInternal('http://google.com/test',['abc.dev','google.com']));
        assert(!Base\Uri::isInternal('http://google.com/test',['abc.dev','googlez.com']));
        assert(Base\Uri::isInternal(Base\Request::absolute()));

        // isExternal
        assert(!Base\Uri::isExternal('/test.jpg'));
        assert(Base\Uri::isExternal('http://google.com/test'));
        assert(!Base\Uri::isExternal('http://google.com/test','google.com'));
        assert(!Base\Uri::isExternal(Base\Request::absolute()));
        assert(Base\Uri::isExternal('//google.tdn.com/test.js'));
        assert(!Base\Uri::isExternal('http://google.com/test',['abc.dev','google.com']));
        assert(Base\Uri::isExternal('http://google.com/test',['abc.dev','googlez.com']));

        // isScheme
        assert(Base\Uri::isScheme('https','https://www.google.com'));
        assert(Base\Uri::isScheme(true,'https://www.google.com'));
        assert(!Base\Uri::isScheme(true,'http://www.google.com'));
        assert(Base\Uri::isScheme(false,'http://test.com'));

        // isHost
        assert(Base\Uri::isHost('google.com','https://google.com'));
        assert(!Base\Uri::isHost('google.ca','http://google.com'));
        assert(!Base\Uri::isHost('google.com','https://www.google.com'));
        assert(Base\Uri::isHost(['google.com','www.google.com'],'https://www.google.com'));
        assert(Base\Uri::isHost(['google.com','www.GOOgle.com'],'https://www.google.com'));

        // isSchemeHost
        assert(Base\Uri::isSchemeHost('https://google.com','https://google.com'));
        assert(!Base\Uri::isSchemeHost('http://google.com','https://google.com'));

        // isExtension
        assert(true === Base\Uri::isExtension('php','http://www.google.com/test.php#test'));
        assert(false === Base\Uri::isExtension(['php','jpgz'],'http://www.google.com/test.jpg?what=blabla'));
        assert(true === Base\Uri::isExtension(['php','jpg'],'http://www.google.com/test.jpg?what=blabla'));
        assert(true === Base\Uri::isExtension('PHP','http://www.google.com/test.php#test'));

        // isQuery
        assert(Base\Uri::isQuery('bla','http://www.google.com/test/ta?bla=lala#asdas'));
        assert(!Base\Uri::isQuery('blaz','http://www.google.com/test/ta?bla=lala#asdas'));
        assert(Base\Uri::isQuery(['bla','lala'],'http://www.google.com/test/ta?bla=lala&lala=bla'));

        // isLang
        assert(Base\Uri::isLang('en','https://google.com/en/test'));

        // sameSchemeHost
        assert(Base\Uri::sameSchemeHost('http://google.com/test','http://google.com/ok'));
        assert(!Base\Uri::sameSchemeHost('http://google.com/test','https://google.com/ok'));
        assert(!Base\Uri::sameSchemeHost('http://google.com/test','http://googlez.com/ok'));

        // type
        assert('absolute' === Base\Uri::type('http:'));
        assert('absolute' === Base\Uri::type('http://www.google.com/test'));
        assert('relative' === Base\Uri::type('/test'));

        // output
        assert('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%20uy#hash%20a' === $uri = Base\Uri::output('http://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a'));
        assert(Base\Request::scheme().'://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%20uy#hash%20a' === Base\Uri::output('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a'));
        assert(Base\Request::scheme().'://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a' === Base\Uri::output('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a',['encode'=>false]));
        assert('https://tdn.google.com/what/now.js' === Base\Uri::output('[tdn]/what/now.js'));
        assert(Base\Uri::output('[tdn]/what/now.js',['absolute'=>false]) === '/what/now.js');
        assert(Base\Uri::output('[tdn]/what/now.js',['absolute'=>true]) === 'https://tdn.google.com/what/now.js');
        assert('/%5Btdn2%5D/what/now.js' === Base\Uri::output('[tdn2]/what/now.js'));
        assert(Base\Uri::output('/test.jpg',['schemeHost'=>'https://google.com']) === 'https://google.com/test.jpg');
        assert(Base\Uri::output('0') === '/0');
        assert(Base\Uri::output('#test') === '#test');
        assert(Base\Uri::output(Base\Request::absolute()) === Base\Request::relative());
        assert(Base\Uri::output('[public]') === '/');
        assert(Base\Uri::output('[public]/james') === '/james');
        assert(Base\Uri::output('[media]') === '/media');

        // outputExists
        assert(Base\Uri::outputExists('/index.php') === '/index.php');

        // relative
        assert('/what/ok' === Base\Uri::relative('http://google.com/what/ok'));
        assert('/what/ok' === Base\Uri::relative('https://google.com/what/ok'));
        assert('/ok?get=lala' === Base\Uri::relative('//what.com/ok?get=lala'));
        assert('/relative/path' === Base\Uri::relative('/relative//path'));
        assert('/' === Base\Uri::relative('/'));
        assert('/' === Base\Uri::relative('//'));
        assert('/' === Base\Uri::relative('///'));
        assert('/' === Base\Uri::relative(''));
        assert('/oups.php' === Base\Uri::relative('oups.php'));
        assert('/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%20uy#hash%20a' === Base\Uri::relative('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a'));
        assert('/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a' === Base\Uri::relative('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a',['encode'=>false]));
        assert(Base\Uri::relative('[tdn]/test.jpg') === '/test.jpg');
        assert(Base\Uri::relative('[extra]/test.jpg') === '/relative/ok/test.jpg');
        assert(Base\Uri::relative('http://google.com/') === '/');
        assert(Base\Uri::relative('http://google.com') === '/');
        assert(Base\Uri::relative('[media]/james.jpg') === '/media/james.jpg');

        // absolute
        assert(Base\Request::schemeHost().'/what/ok/go' === Base\Uri::absolute('/what/ok/go'));
        assert(Base\Uri::absolute('//google.com/test.jpg') === Base\Request::scheme().'://google.com/test.jpg');
        assert('http://google.com/what/ok/go?test=ca' === Base\Uri::absolute('what//ok/go?test=ca','http://google.com'));
        assert(Base\Request::scheme().'://james.com/what/ok' === Base\Uri::absolute('/what//ok','//james.com'));
        assert('http://james.com/what/ok' === Base\Uri::absolute('/what//ok','http://james.com'));
        assert(Base\Request::scheme().'://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%20uy#hash%20a' === Base\Uri::absolute('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a'));
        assert(Base\Request::scheme().'://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a' === Base\Uri::absolute('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a',null,['encode'=>false]));
        assert('https://tdn.google.com/what/now.js' === Base\Uri::absolute('[tdn]/what/now.js'));
        assert(Base\Request::schemeHost().'/%5Btdn2%5D/what/now.js' === Base\Uri::absolute('[tdn2]/what/now.js'));
        assert(Base\Uri::absolute('https://google.com/[tdn]/what/now.js') === 'https://google.com/https%3A/tdn.google.com/what/now.js');
        assert(Base\Uri::absolute('//google.com/ok',null,['encode'=>false]) === Base\Request::scheme().'://google.com/ok');
        assert(Base\Uri::absolute('/test/ok','google.com') === Base\Request::scheme().'://google.com/test/ok');
        assert(Base\Uri::absolute('/test/ok','https://google.com') === 'https://google.com/test/ok');
        assert(Base\Uri::absolute('http://gogle.com/test/slash/') === 'http://gogle.com/test/slash/');
        assert(Base\Uri::absolute('http://gogle.com/test/slash/',null,['encode'=>false]) === 'http://gogle.com/test/slash/');
        assert(Base\Uri::absolute('http://gogle.com/test/slash/',null,['append'=>['q'=>2]]) === 'http://gogle.com/test/slash/?q=2');
        assert(Base\Uri::absolute('/test/slash/','google.com',['append'=>['q'=>2]]) === Base\Request::scheme().'://google.com/test/slash/?q=2');

        // existsCallable

        // append
        assert(strlen(Base\Uri::append(true,Base\Request::schemeHost().'/what/now.js')) >= 40);
        assert(strlen(Base\Uri::append(true,Base\Request::schemeHost().'/what/now.js?j=true')) >= 45);
        assert(strlen(Base\Uri::append(true,Base\Request::schemeHost().'/what/now.js?j=true')) >= 45);
        assert(strlen(Base\Uri::append(['b'=>'érikaz'],Base\Request::schemeHost().'/what/now.js?j=true')) >= 40);

        // encode
        assert(rawurlencode('test é ') === Base\Uri::encode('test é '));
        assert(urlencode('test é ') === Base\Uri::encode('test é ',1));
        assert(urlencode('test é ') !== Base\Uri::encode('test é '));

        // decode
        assert('http://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la+uy#hash+a' === Base\Uri::decode('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la+uy#hash+a'));
        assert('http://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a' === Base\Uri::decode('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la+uy#hash+a',1));
        assert('http://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la+uy#hash+a' === Base\Uri::decode('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&amp;space=la+uy#hash+a'));

        // encodeAll
        assert('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%20uy#hash%20a' === Base\Uri::encodeAll('http://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a'));
        assert('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la%2Buy#hash%2Ba' === Base\Uri::encodeAll('http://google.com/test/%C3%A9ol/la%20vie/i.php?james=lala&ka=%C3%A9o&space=la+uy#hash+a',true));
        assert(Base\Uri::encodeAll('https://maps.googleapis.com/maps/api/geocode/json?address=Montrea asd&key=AIzaSyA') === 'https://maps.googleapis.com/maps/api/geocode/json?address=Montrea%20asd&key=AIzaSyA');

        // encodePath
        assert('/test/%C3%A9ol/la%20vie/i.php' === Base\Uri::encodePath('/test/éol/la vie/i.php'));

        // encodeQuery
        assert('james=lala&ka=%C3%A9o&space=la%20uy' === Base\Uri::encodeQuery('james=lala&ka=éo&space=la uy'));

        // parseQuery
        assert(Base\Uri::parseQuery('géèßæ2v=æß∂∑œ¡',true,false) === ['géèßæ2v'=>'æß∂∑œ¡']);
        assert(Base\Uri::parseQuery('géèßæ2v=æß∂∑œ¡',true,true) === ['géèßæ2v'=>'æß∂∑œ¡']);
        assert(['g'=>2,'v'=>'a'] === Base\Uri::parseQuery('g=2&v=a'));
        assert(['g'=>2,'v'=>'a'] === Base\Uri::parseQuery('g=2&v=a'));
        assert(['g'=>2,'v'=>'true'] === Base\Uri::parseQuery('g=2&v=true'));
        assert(Base\Uri::parseQuery('g=2&v=test%20%2B%20james') === ['g'=>2,'v'=>'test + james']);
        
        // buildQuery
        assert(Base\Uri::buildQuery(['g'=>2,'bla'=>'stringé','space'=>'wh + as']) === 'g=2&bla=stringé&space=wh + as');
        assert(Base\Uri::buildQuery(['g'=>2,'bla'=>'stringé','space'=>'wh + as'],true) === 'g=2&bla=string%C3%A9&space=wh%20%2B%20as');
        assert('g=2&bla=stringé&space=wh as' === Base\Uri::buildQuery(['g'=>2,'bla'=>'stringé','space'=>'wh as']));
        assert('g=2&bla=string%C3%A9&space=wh%20as' === Base\Uri::buildQuery(['g'=>2,'bla'=>'stringé','space'=>'wh as'],true));

        // parse
        assert(Base\Uri::parse('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true)['path'] === '/ test.php');
        assert(count(Base\Uri::parse('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true)) === 8);
        assert(Base\Uri::parse("C:/ok/well/what")['path'] === "/ok/well/what");
        assert(Base\Uri::parse("C:\\ok\\well\\what")['path'] === "\\ok\\well\\what");

        // parseOne
        assert(Base\Uri::parseOne(PHP_URL_PATH,'http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true) === '/ test.php');
        assert(Base\Uri::parseOne('path','http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true) === '/ test.php');

        // getParseConstant
        assert(Base\Uri::getParseConstant('path') === PHP_URL_PATH);
        
        // getEmptyParse
        assert(current(Base\Uri::getEmptyParse()) === null);
        assert(count(Base\Uri::getEmptyParse()) === 8);
        
        // info
        assert(Base\Uri::info('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true)['parse']['path'] === '/ test.php');
        assert(Base\Uri::info('http%3A%2F%2Fwww.google.com%2F%20test.php%3Fg%3D3%26test%C3%A9%3D4',true)['parse']['query'] === 'g=3&testé=4');
        assert(Base\Uri::info('https://www.google.com/test.jpg')['pathinfo']['extension'] === 'jpg');
        assert(Base\Uri::info('https://www.google.com/chemin/vers/image/test.jpg')['ssl'] === true);
        assert(Base\Uri::info('http://www.google.com/test?test=oui')['parse']['scheme'] === 'http');
        assert(Base\Uri::info('/test')['parse']['path'] === '/test');
        assert(Base\Uri::info('/')['type'] === 'relative');
        assert(count(Base\Uri::info('//')) === 6);
        assert(Base\Uri::info('//')['source'] === '//');
        assert(count(Base\Uri::info('http://')));
        assert(Base\Uri::info('//test.com')['parse']['host'] === 'test.com');
        assert(Base\Uri::info('//test.com')['parse']['scheme'] === Base\Request::scheme());
        assert(Base\Uri::info('//test.com')['protocolRelative'] === true);
        assert(Base\Uri::info('//test.com')['type'] === 'absolute');
        assert(Base\Uri::info('http://domain')['parse']['host'] === 'domain');
        assert(Base\Uri::info('test.com')['parse']['path'] === 'test.com');
        assert(Base\Uri::info('http://domain.com/index.php?test=oui#what')['parse']['fragment'] === 'what');
        assert(Base\Uri::info('http://www.google.com/test')['type'] === 'absolute');
        assert(Base\Uri::info('http://www.google.com/test#test2')['parse']['host'] === 'www.google.com');
        assert(Base\Uri::info('/test/test')['type'] === 'relative');
        assert(count(Base\Uri::info('/test/test;2/test2')) === 6);
        assert(Base\Uri::info('/test/test;2/test2')['pathinfo']['basename'] === 'test2');
        assert(count(Base\Uri::info('/test/test:2/test2')) === 6);
        assert(count(Base\Uri::info('https://www.google.com/test/test3/tow#test2')) === 6);
        assert(count(Base\Uri::info('//test.com/lala/oui/ok.jpg?james=2.1&lala=true#hashhh')) === 6);
        assert(Base\Uri::info('//test.com/lala/oui/ok.jpg?james=2.1&lala=true#hashhh')['parse']['query'] === 'james=2.1&lala=true');
        assert(Base\Uri::info('//test.com/lala/oui/ok.jpg?james=2.1&lala=true#hashhh')['protocolRelative'] === true);
        assert(Base\Uri::info('http://www.exemple.com:80/chemin/')['parse']['port'] = 80);
        assert(count(Base\Uri::info('http://username:password@hostname.com:9090/path?arg=value#anchor')) === 6);
        assert(Base\Uri::info('http://username:password@hostname.com:9090/path?arg=value#anchor')['parse']['user'] === 'username');
        assert(Base\Uri::info('http://username:password@hostname.com:9090/path?arg=value#anchor')['parse']['pass'] === 'password');

        // scheme
        assert('http' === Base\Uri::scheme('http://www.google.com/test'));
        assert('https' === Base\Uri::scheme('https://www.google.com/test'));
        assert(null === Base\Uri::scheme('www.google.com/test'));
        assert('http' === Base\Uri::scheme('http'));
        assert('https' === Base\Uri::scheme('https'));
        assert('http' === Base\Uri::scheme('http:'));
        assert('https' === Base\Uri::scheme('https:/'));
        assert(null === Base\Uri::scheme('https://'));
        assert(Base\Request::scheme() === Base\Uri::scheme('//www.google.com/test'));
        assert('https' === Base\Uri::scheme(true));
        assert('http' === Base\Uri::scheme(false));
        assert(Base\Request::scheme() === Base\Uri::scheme('//'));

        // changeScheme
        assert('https://test.com' === Base\Uri::changeScheme(true,'http://test.com'));
        assert('https://test.com' === Base\Uri::changeScheme('https','http://test.com'));
        assert('http://tdn.google.com' === Base\Uri::changeScheme('http','[tdn]'));
        assert(Base\Uri::changeScheme(true,'google.com') === 'https://google.com');
        assert(Base\Uri::changeScheme(80,'google.com') === 'http://google.com');

        // changeProtocolRelativeScheme
        assert(Base\Uri::changeProtocolRelativeScheme('//google.com/test.js') === Base\Request::scheme().'://google.com/test.js');
        assert(Base\Uri::changeProtocolRelativeScheme('http://google.com/test.js') === 'http://google.com/test.js');

        // removeScheme
        assert('test.com/lba' === Base\Uri::removeScheme('http://test.com/lba'));
        assert('www.google.com/test.php?g=3&testé=4' === Base\Uri::removeScheme('http://www.google.com/test.php?g=3&testé=4'));

        // user
        assert(Base\Uri::user('http://usernamez:password@hostname.com:9090/path?arg=value#anchor') === 'usernamez');
        assert(Base\Uri::user('http://password@hostname.com:9090/path?arg=value#anchor') === 'password');
        assert(Base\Uri::user('http://@hostname.com:9090/path?arg=value#anchor') === null);

        // changeUser
        assert(Base\Uri::changeUser('what','http://usernamez:password@hostname.com:9090') === 'http://what:password@hostname.com:9090');

        // removeUser
        assert(Base\Uri::removeUser('http://usernamez:password@hostname.com:9090') === 'http://:password@hostname.com:9090');

        // pass
        assert(Base\Uri::pass('http://username:passwordz@hostname.com:9090/path?arg=value#anchor') === 'passwordz');
        assert(Base\Uri::pass('http://username@hostname.com:9090/path?arg=value#anchor') === null);

        // changePass
        assert(Base\Uri::changePass('what','http://usernamez:password@hostname.com:9090') === 'http://usernamez:what@hostname.com:9090');

        // removePass
        assert(Base\Uri::removePass('http://usernamez:password@hostname.com:9090') === 'http://usernamez@hostname.com:9090');

        // host
        assert('www.google.com' === Base\Uri::host('http://www.google.com/test/ta'));
        assert('google' === Base\Uri::host('http://google/test'));
        assert(null === Base\Uri::host('/test'));

        // changeHost
        assert(Base\Uri::changeHost('test.com','http://google.com/test/ta') === 'http://test.com/test/ta');

        // removeHost
        assert(Base\Uri::removeHost('http://google.com/test/ta') === 'http:///test/ta');

        // port
        assert(Base\Uri::port('http://username:passwordz@hostname.com:9090/path?arg=value#anchor') === 9090);
        assert(Base\Uri::port('http://username@hostname.com:/path?arg=value#anchor') === null);
        assert(Base\Uri::port('http://host.com/path?arg=value#anchor') === null);

        // changePort
        assert(Base\Uri::changePort('200','http://usernamez:password@hostname.com:9090') === 'http://usernamez:password@hostname.com:200');

        // removePort
        assert(Base\Uri::removePort('http://usernamez:password@hostname.com:9090') === 'http://usernamez:password@hostname.com');

        // path
        assert('/test/ta' === Base\Uri::path('http://www.google.com/test/ta?bla=lala'));
        assert('/test' === Base\Uri::path('http://www.google/test'));
        assert('/' === Base\Uri::path('/'));
        assert(null === Base\Uri::path('//'));

        // pathStripStart
        assert('test/ta' === Base\Uri::pathStripStart('http://www.google.com/test/ta?bla=lala',true));

        // changePath
        assert(Base\Uri::changePath('haha','http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/haha?bla=lala');

        // removePath
        assert(Base\Uri::removePath('http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/?bla=lala');
        assert(Base\Uri::removePath('[tdn]/test/ta?bla=lala') === 'https://tdn.google.com/?bla=lala');

        // pathinfo
        assert(count(Base\Uri::pathinfo('http://www.google.com/test/ta.jpg')) === 4);
        assert(Base\Uri::pathinfo('http://www.google.com/test/ta.jpg')['extension'] === 'jpg');
        assert(Base\Uri::pathinfo('') === null);

        // pathinfoOne
        assert(Base\Uri::pathinfoOne(PHP_URL_PATH,'http://www.google.com/test/ta.jpg') === '/test');

        // changePathinfo
        assert(Base\Uri::changePathinfo(['extension'=>'jpg'],'http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/test/ta.jpg?bla=lala');

        // keepPathinfo
        assert(Base\Uri::keepPathinfo(['dirname'],'http://www.google.com/test/ta.jpg?bla=lala') === 'http://www.google.com/test?bla=lala');
        assert(Base\Uri::keepPathinfo('basename','http://www.google.com/test/ta.jpg?bla=lala') === 'http://www.google.com/ta.jpg?bla=lala');

        // removePathinfo
        assert(Base\Uri::removePathinfo('extension','http://www.google.com/test/ta.jpg?bla=lala') === 'http://www.google.com/test/ta?bla=lala');
        assert(Base\Uri::removePathinfo(['filename','extension'],'http://www.google.com/test/ta.jpg?bla=lala') === 'http://www.google.com/test?bla=lala');

        // dirname
        assert(Base\Uri::dirname('http://www.google.com/test/taa/ta.jpg') === '/test/taa');
        assert(Base\Uri::dirname('ta.jpg') === null);

        // addDirname
        assert(Base\Uri::addDirname('bla/bla','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/bla/bla/ta.jpg');

        // changeDirname
        assert(Base\Uri::changeDirname('bla/bla','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/bla/bla/ta.jpg');
        assert(Base\Uri::changeDirname('/bla/bla','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/bla/bla/ta.jpg');

        // removeDirname
        assert(Base\Uri::removeDirname('http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/ta.jpg');

        // basename
        assert(Base\Uri::basename('http://www.google.com/test/ta.jpg') === 'ta.jpg');
        assert(Base\Uri::basename('http://www.google.com/test/') === 'test');

        // addBasename
        assert(Base\Uri::addBasename('bla.jit','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.jpg/bla.jit');

        // changeBasename
        assert(Base\Uri::changeBasename('bla.jit','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/bla.jit');
        assert(Base\Uri::changeBasename('bla.jit','[tdn]/test/taa/ta.jpg') === 'https://tdn.google.com/test/taa/bla.jit');

        // removeBasename
        assert(Base\Uri::removeBasename('http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa');

        // filename
        assert(Base\Uri::filename('http://www.google.com/test/ta.jpg') === 'ta');
        assert(Base\Uri::filename('http://www.google.com/test/') === 'test');

        // addFilename
        assert(Base\Uri::addFilename('bla','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.jpg/bla');

        // changeFilename
        assert(Base\Uri::changeFilename('bla','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/bla.jpg');

        // removeFilename
        assert(Base\Uri::removeFilename('http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/.jpg');

        // extension
        assert(null === Base\Uri::extension('http://www.google.com/test'));
        assert('php' === Base\Uri::extension('http://www.google.com/test.php'));

        // addExtension
        assert(Base\Uri::addExtension('xls','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.jpg/.xls');
        assert(Base\Uri::addExtension('/path/to/hell.xls','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.jpg/.xls');

        // changeExtension
        assert(Base\Uri::changeExtension('png','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.png');
        assert(Base\Uri::changeExtension('test.png','http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta.png');

        // removeExtension
        assert(Base\Uri::removeExtension('http://www.google.com/test/taa/ta.jpg') === 'http://www.google.com/test/taa/ta');
        assert(Base\Uri::removeExtension('http://www.google.com/test/taa/ta') === 'http://www.google.com/test/taa/ta');

        // mime
        assert(Base\Uri::mime('http://www.google.com/test/taa/ta.jpg?test=james') === 'image/jpeg');
        assert(Base\Uri::mime('') === null);

        // addLang
        assert(Base\Uri::addLang('fr','http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/fr/test/taa/ta.jpg?test=james');
        assert(Base\Uri::addLang('frz','http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/?test=james');

        // changeLang
        assert(Base\Uri::changeLang('fr','http://www.google.com/en/test/taa/ta.jpg?test=james') === 'http://www.google.com/fr/test/taa/ta.jpg?test=james');
        assert(Base\Uri::changeLang('frz','http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/?test=james');

        // removeLang
        assert(Base\Uri::removeLang('http://www.google.com/en/test/taa/ta.jpg?test=james') === 'http://www.google.com/test/taa/ta.jpg?test=james');
        assert(Base\Uri::removeLang('http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/test/taa/ta.jpg?test=james');

        // pathPrepend
        assert(Base\Uri::pathPrepend('http://www.google.com/test/taa/ta.jpg?test=james','ok//what') === 'http://www.google.com/ok/what/test/taa/ta.jpg?test=james');
        assert(Base\Uri::pathPrepend('http://www.google.com/test/taa/ta.jpg','bla/bla') === 'http://www.google.com/bla/bla/test/taa/ta.jpg');
        assert(Base\Uri::pathPrepend('http://www.google.com/test/taa/ta.jpg','bla/bla','ok','noWay/merdier') === 'http://www.google.com/noWay/merdier/ok/bla/bla/test/taa/ta.jpg');

        // pathAppend
        assert(Base\Uri::pathAppend('http://www.google.com/test/taa/ta.jpg?test=james','ok//what') === 'http://www.google.com/test/taa/ta.jpg/ok/what?test=james');
        assert(Base\Uri::pathAppend('[tdn]','ok//what') === 'https://tdn.google.com/ok/what');
        assert(Base\Uri::pathAppend('[tdn]','ok//what','test','ok.jpg') === 'https://tdn.google.com/ok/what/test/ok.jpg');

        // pathExplode
        assert(count(Base\Uri::pathExplode('http://www.google.com/test/taa/ta.jpg?test=james')) === 3);

        // pathGet
        assert(Base\Uri::pathGet(0,'http://www.google.com/test/taa/ta.jpg?test=james') === 'test');
        assert(Base\Uri::pathGet(1,'http://www.google.com/test/taa/ta.jpg?test=james') === 'taa');

        // pathCount
        assert(Base\Uri::pathCount('http://www.google.com/test/taa/ta.jpg?test=james') === 3);

        // pathSlice
        assert(Base\Uri::pathSlice(0,2,'http://www.google.com/test/taa/ta.jpg?test=james') === ['test','taa']);
        assert(Base\Uri::pathSlice(-1,1,'http://www.google.com/test/taa/ta.jpg?test=james') === [2=>'ta.jpg']);

        // pathSplice
        assert(Base\Uri::pathSplice(0,1,'http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/taa/ta.jpg?test=james');
        assert(Base\Uri::pathSplice(1,2,'http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/test?test=james');
        assert(Base\Uri::pathSplice(1,1,'http://www.google.com/test/taa/ta.jpg?test=james','ok') === 'http://www.google.com/test/ok/ta.jpg?test=james');
        assert(Base\Uri::pathSplice(1,1,'http://www.google.com/test/taa/ta.jpg?test=james',['ok','yeah']) === 'http://www.google.com/test/ok/yeah/ta.jpg?test=james');

        // pathInsert
        assert(Base\Uri::pathInsert(0,'haha','http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/haha/test/taa/ta.jpg?test=james');
        assert(Base\Uri::pathInsert(0,['hi','ho'],'http://www.google.com/test/taa/ta.jpg?test=james') === 'http://www.google.com/hi/ho/test/taa/ta.jpg?test=james');

        // query
        assert('bla=lala' === Base\Uri::query('http://www.google.com/test/ta?bla=lala#asdas'));
        assert(null === Base\Uri::query('http://www.google/test'));

        // queryArray
        assert(count(Base\Uri::queryArray('http://www.google.com?g=3&teste=4')) === 2);
        assert(Base\Uri::queryArray('http://www.google.com?g=3&teste=4')['teste'] === 4);
        assert(Base\Uri::queryArray('http://www.google.com?g=3&teste=true')['teste'] === 'true');

        // changeQuery
        assert(Base\Uri::changeQuery('ko=yes','http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/test/ta?ko=yes');
        assert(Base\Uri::changeQuery(['ko'=>'yes','o'=>2],'http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/test/ta?ko=yes&o=2');
        assert(Base\Uri::changeQuery(['t'=>'/'],'/james.php/ok') === '/james.php/ok?t=/');

        // removeQuery
        assert(Base\Uri::removeQuery('http://www.google.com/test/ta?bla=lala') === 'http://www.google.com/test/ta');

        // getQuery
        assert(4 === Base\Uri::getQuery('teste','http://www.google.com?g=3&teste=4'));
        assert(3 === Base\Uri::getQuery('g','http://www.google.com?g=3&teste=4'));
        assert(null === Base\Uri::getQuery('v','http://www.google.com?g=3&teste=4'));

        // getsQuery
        assert(['g'=>3,'teste'=>4] === Base\Uri::getsQuery(['g','teste'],'http://www.google.com?g=3&teste=4'));
        assert(['g'=>3,'testez'=>null] === Base\Uri::getsQuery(['g','testez'],'http://www.google.com?g=3&teste=4'));

        // setQuery
        $uri = 'http://www.google.com';
        assert('http://www.google.com/?g=3' === $uri = Base\Uri::setQuery('g',3,$uri));
        assert('http://www.google.com/?g=1' === Base\Uri::setQuery('g',true,$uri));
        assert('http://www.google.com/?g=0' === Base\Uri::setQuery('g',false,$uri));
        assert('http://www.google.com/?g=0' === Base\Uri::setQuery('g',0,$uri));
        assert('http://www.google.com/?g=3&testé=4' === Base\Uri::setQuery('testé',4,$uri));
        assert('http://www.google.com/?g=3&=2' === Base\Uri::setQuery('',2,$uri));
        assert($uri === Base\Uri::setQuery('what',null,$uri));

        // setsQuery
        $uri = 'http://www.google.com';
        assert('http://www.google.com/?g=3' === $uri = Base\Uri::setsQuery(['g'=>3],$uri));
        assert('http://www.google.com' === $uri = Base\Uri::setsQuery(['g'=>null],$uri));
        assert('http://www.google.com/?g=3&blaé=1' === $uri = Base\Uri::setsQuery(['g'=>3,'blaé'=>true],$uri));

        // unsetQuery
        assert('http://www.google.com/test.php?g=3' === Base\Uri::unsetQuery('t','http://www.google.com/test.php?g=3&t=3'));
        assert('http://www.google.com/test.php?g=3&bla=2' === Base\Uri::unsetQuery('t','http://www.google.com/test.php?g=3&t=3&bla=2'));
        assert('https://tdn.google.com/test.php?g=3&bla=2' === Base\Uri::unsetQuery('t','[tdn]/test.php?g=3&t=3&bla=2'));

        // unsetsQuery
        assert('http://www.google.com/test.php' === Base\Uri::unsetsQuery(['g','t'],'http://www.google.com/test.php?g=3&t=3'));

        // fragment
        assert('asdas' === Base\Uri::fragment('http://www.google.com/test/ta?bla=lala#asdas'));
        assert(null === Base\Uri::fragment('http://www.google/test'));

        // changeFragment
        assert('http://www.google.com/ta?bla=lala#haha' === Base\Uri::changeFragment('haha','http://www.google.com/ta?bla=lala#asdas'));

        // removeFragment
        assert('http://www.google.com/test/ta?bla=lala' === Base\Uri::removeFragment('http://www.google.com/test/ta?bla=lala#asdas'));

        // schemeHost
        assert('http://www.google.com' === Base\Uri::schemeHost('http://www.google.com/test.php?g=3&testé=4'));

        // schemeHostPath
        assert('http://www.google.com/test.php' === Base\Uri::schemeHostPath('http://www.google.com/test.php?g=3&testé=4'));

        // hostPath
        assert('www.google.com/test.php' === Base\Uri::hostPath('http://www.google.com/test.php?g=3&testé=4'));
        assert(Base\Uri::hostPath('[tdn]/test.php?g=3&testé=4') === 'tdn.google.com/test.php');

        // pathQuery
        assert(Base\Uri::pathQuery('http://www.google.com/test.php?g=3&testé=4') === '/test.php?g=3&testé=4');

        // lang
        assert(Base\Uri::lang('https://google.com') === null);
        assert(Base\Uri::lang('https://google.com/en/test') === 'en');

        // build
        $info = Base\Uri::info('https://bla.com/path/to/heaven?what=lala#yeah');
        assert($info['source'] === Base\Uri::build($info['parse']));
        assert('http://bla.com' === Base\Uri::build(['scheme'=>'http','host'=>'bla.com']));
        assert('http://bla.com/test' === Base\Uri::build(['path'=>'test','host'=>'bla.com','scheme'=>'http']));
        assert('http://bla.com' === Base\Uri::build(['scheme'=>'http','host'=>'bla.com','query'=>'']));
        assert(strlen(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','query'=>['test'=>'deux','james'=>'ok']])) === 34);
        assert(strlen(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','path'=>'/ookkk/','query'=>['test'=>'deux','james'=>'ok']])) === 40);
        assert(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','port'=>80,'path'=>'/test','query'=>'']) === 'http://bla.com/test');
        assert(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','port'=>81,'path'=>'/test','query'=>'']) === 'http://bla.com:81/test');
        assert(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','path'=>'/test/slash/']) === 'http://bla.com/test/slash/');
        assert(Base\Uri::build(['scheme'=>'http','host'=>'bla.com','port'=>80,'path'=>'/','query'=>'']) === 'http://bla.com');
        assert(Base\Uri::build(['fragment'=>'test']) === '#test');
        assert(Base\Uri::build(['scheme'=>'http','host'=>'google.com','path'=>'/','query'=>null]) === 'http://google.com');

        // rebuild
        assert(Base\Request::scheme().'://google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a' === Base\Uri::rebuild('//google.com/test/éol/la vie/i.php?james=lala&ka=éo&space=la uy#hash a',false));

        // change
        assert(Base\Request::scheme().'://ma.com/path/to/heaven?what=lala#yeah' === Base\Uri::change(['host'=>'ma.com','scheme'=>'//'],'https://bla.com/path/to/heaven?what=lala#yeah'));
        assert('http://ma.com/path/to/heaven?what=lala#yeah' === Base\Uri::change(['host'=>'ma.com','scheme'=>'http'],'https://bla.com/path/to/heaven?what=lala#yeah'));
        assert('https://bla.com/path/to/heaven?what=lala#bla' === Base\Uri::change(['fragment'=>'bla'],'https://bla.com/path/to/heaven?what=lala#yeah'));
        assert(Base\Uri::change(['fragment'=>'bla'],'[tdn]/heaven?what=lala#yeah') === 'https://tdn.google.com/heaven?what=lala#bla');

        // keep
        assert('http://username:passwordz:9090/path#anchor' === Base\Uri::keep(['scheme','user','pass','path','port','fragment'],'http://username:passwordz@hostname.com:9090/path?arg=value#anchor'));

        // remove
        assert('http://username:passwordz@hostname.com/path?arg=value' === Base\Uri::remove(['port','fragment'],'http://username:passwordz@hostname.com:9090/path?arg=value#anchor'));
        assert('http://username:passwordz@hostname.com:9090/?arg=value#anchor' === Base\Uri::remove('path','http://username:passwordz@hostname.com:9090/path?arg=value#anchor'));
        assert('http://www.google.com/test.php?g=3&testé=4' === Base\Uri::remove('fragment','http://www.google.com/test.php?g=3&testé=4#blabla'));
        assert('http://www.google.com/test.php' === Base\Uri::remove(['fragment','query'],'http://www.google.com/test.php?g=3&testé=4'));
        assert('http://www.google.com' === Base\Uri::remove(['fragment','query','path'],'http://www.google.com/test.php?g=3&testé=4'));
        assert('http' === Base\Uri::remove(['fragment','query','path','host'],'http://www.google.com/test.php?g=3&testé=4'));
        assert('http://www.google.com/test.php?g=3&testé=4' === Base\Uri::remove('fragment','http://www.google.com/test.php?g=3&testé=4#blabla'));
        assert('http://username:passwordz@hostname.com:9090/path?arg=value' === Base\Uri::remove('fragment','http://username:passwordz@hostname.com:9090/path?arg=value#anchor'));
        assert('http://username@hostname.com:9090/path?arg=value#anchor' === Base\Uri::remove('pass','http://username:passwordz@hostname.com:9090/path?arg=value#anchor'));

        // removeBefore
        assert('http://username:passwordz@hostname.com:9090/path#anchor' === Base\Uri::removeBefore('scheme','http://username:passwordz@hostname.com:9090/path#anchor'));
        assert(Base\Uri::removeBefore('host','[tdn]/test') === 'tdn.google.com/test');
        assert(Base\Uri::removeBefore('scheme','[tdn]/test') === 'https://tdn.google.com/test');

        // removeAfter
        assert('http://username:passwordz@hostname.com:9090/path' === Base\Uri::removeAfter('path','http://username:passwordz@hostname.com:9090/path#anchor'));
        assert('http://username:passwordz@hostname.com:9090/path' === Base\Uri::removeAfter('query','http://username:passwordz@hostname.com:9090/path#anchor'));
        assert(Base\Uri::removeAfter('path','[tdn]/test?v=2') === 'https://tdn.google.com/test');

        // combine
        assert(Base\Uri::combine('[tdn]','ok/yeah') === 'https://tdn.google.com/ok/yeah');
        assert('http://google.com/ok/yes' === Base\Uri::combine('http://google.com','http://what.ca/ok/yes'));
        assert('http://google.com/ok/yes' === Base\Uri::combine('http://google.com','/ok/yes'));
        assert('http://google.com' === Base\Uri::combine('http://google.com',''));
        assert('http://google.com' === Base\Uri::combine('http://google.com','//'));
        assert('http://google.com' === Base\Uri::combine('http://google.com','///'));
        assert('http://google.com' === Base\Uri::combine('http://google.com','/'));
        assert('https://google.com/what' === Base\Uri::combine('https://googlez.com','http://google.com/what','host'));

        // redirection
        $array = ['test2'=>'test3','test2/ok*'=>'test3*','test4*'=>'https://google.com','80.12.9.2'=>'https://google.com/ok'];
        assert(Base\Uri::redirection('test2',$array) === 'test3');
        assert(Base\Uri::redirection('test2/ok/james/lavie',$array) === 'test3/james/lavie');
        assert(Base\Uri::redirection('test2/ok',$array) === 'test3');
        assert(Base\Uri::redirection('test4',$array) === 'https://google.com');
        assert(Base\Uri::redirection('test4/james',$array) === 'https://google.com');
        assert(Base\Uri::redirection('http://80.12.9.2',$array) === null);
        assert(Base\Uri::redirection('80.12.9.2',$array) === 'https://google.com/ok');

        // makeHostPort
        assert(Base\Uri::makeHostPort('proxy.com',8080) === 'proxy.com:8080');

        // getSchemeStatic
        assert(Base\Uri::getSchemeStatic('scheme.com') === 'https');
        assert(Base\Uri::getSchemeStatic('james.com') === 'https');
        assert(Base\Uri::getSchemeStatic('notScheme.com') === 'http');
        assert(Base\Uri::getSchemeStatic('test.comz') === null);

        // getSchemeHostStatic
        assert(Base\Uri::getSchemeHostStatic('scheme.com') === 'https://scheme.com');
        assert(Base\Uri::getSchemeHostStatic('notScheme.com') === 'http://notScheme.com');
        assert(Base\Uri::getSchemeHostStatic('notScheme.comz') === null);

        // schemeStatic
        assert(Base\Uri::output('http://scheme.com/ok',['schemeHost'=>'scheme.com']) === 'http://scheme.com/ok');
        assert(Base\Uri::output('/ok',['schemeHost'=>'scheme.com']) === 'https://scheme.com/ok');
        assert(Base\Uri::scheme('//scheme.com/ok') === 'https');
        assert(Base\Uri::scheme('//notScheme.com/ok') === 'http');
        assert(Base\Uri::changeProtocolRelativeScheme('//scheme.com/ok') === 'https://scheme.com/ok');
        assert(Base\Uri::changeProtocolRelativeScheme('//notScheme.com/ok') === 'http://notScheme.com/ok');
        assert(Base\Uri::absolute('//scheme.com/ok') === 'https://scheme.com/ok');
        assert(Base\Uri::absolute('//notScheme.com/ok') === 'http://notScheme.com/ok');
        assert(Base\Uri::absolute('/ok','scheme.com') === 'https://scheme.com/ok');
        assert(Base\Uri::absolute('/ok','notScheme.com') === 'http://notScheme.com/ok');
        assert(Base\Uri::absolute('http://scheme.com/ok') === 'http://scheme.com/ok');
        assert(Base\Uri::absolute('https://notScheme.com/ok') === 'https://notScheme.com/ok');

        // setNotFound

        // shortcut
        assert(Base\Uri::isShortcut('tdn'));
        assert(Base\Uri::isShortcut('lang'));
        assert(!Base\Uri::isShortcut('langz'));
        assert(Base\Uri::shortcut('[tdn]/james.js') === 'https://tdn.google.com/james.js');
        assert(Base\Uri::shortcut('[tdn2]/james.js') === '[tdn2]/james.js');
        assert(Base\Uri::shortcut('/media/logo_[lang].jpg') === '/media/logo_en.jpg');
        assert(Base\Uri::getShortcut('tdn') === 'https://tdn.google.com');
        assert(Base\Uri::getShortcut('tdnz') === null);

        // options
        assert(count(Base\Uri::option()) === 7);

        // cleanup
        Base\Uri::unsetShortcut('tdn');
        Base\Uri::unsetShortcut('extra');

        return true;
    }
}
?>