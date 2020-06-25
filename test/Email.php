<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// email
// class for testing Quid\Base\Email
class Email extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // is
        assert(Base\Email::is('zwa@test.lz'));

        // isActive
        assert(Base\Email::isActive());

        // arr
        assert(Base\Email::arr('zwa@test.lz') === ['name'=>'zwa','host'=>'test.lz']);
        assert(Base\Email::arr('zwatest.lz') === null);

        // name
        assert(Base\Email::name('zwa@test.lz') === 'zwa');
        assert(Base\Email::name('zwatest.lz') === null);

        // host
        assert(Base\Email::host('zwa@test.lz') === 'test.lz');
        assert(Base\Email::host('zwatest.lz') === null);

        // send
        $msg = ['subject'=>'Testé ok ça va ?','cc'=>'wqz@hotmail.com','body'=>'L"ARTICLÉ <b>DE</b> LA MORTÉE ! WHAT@ @!','to'=>['zwa@test.lz'=>'EnFANT PRODIGE'],'from'=>['zac@olstuddd.co'=>'enFANT OUBLIÉ']];
        $msg['bcc'] = ['e@as.com','zames@as.ca'=>'Zierre'];
        $msg['from'] = ['vames@as.ca'=>'Wierre','e@as.com'];
        $msg['to'] = ['e@as.com','james@as.ca'=>'Pierre'];

        // sendTest

        // sendLoop

        // prepareMessage
        $msg = ['subject'=>'Test','body'=>'what','cc'=>'zwa@test.lz','replyTo'=>'jam@laz.iu','to'=>'zwa@test.lz','from'=>['zac@olstuddd.coo'=>'whattt']];
        assert(count(Base\Email::prepareMessage($msg)) === 15);
        assert(Base\Email::prepareMessage($msg)['replyTo'][0] === ['email'=>'jam@laz.iu','name'=>'jam']);
        assert(Base\Email::prepareMessage($msg)['from'] === ['email'=>'zac@olstuddd.coo','name'=>'whattt']);
        assert(Base\Email::prepareMessage($msg,false)['from'] === ['email'=>'zac@olstuddd.coo','name'=>'whattt']);
        $msg['bcc'] = ['e@as.com','james@as.ca'];
        $msg['from'] = ['e@as.com','james@as.ca'];
        assert(Base\Email::prepareMessage($msg)['from'] === ['email'=>'e@as.com','name'=>'e']);
        assert(Base\Arrs::is(Base\Email::prepareMessage($msg)['bcc']));
        $msg = ['contentType'=>2,'subject'=>'Test','body'=>'what','cc'=>'zwa@test.lz','replyTo'=>'jam@laz.iu','to'=>'zwa@test.lz','from'=>['zac@olstuddd.coo'=>'whattt']];
        assert(Base\Email::prepareMessage($msg)['charset'] === 'UTF-8');
        assert(Base\Email::prepareMessage($msg)['contentType'] === 'text/html');
        assert(Base\Email::prepareMessage($msg)['contentTypeCharset'] === 'text/html; charset=UTF-8');
        assert(Base\Email::prepareMessage($msg)['header']['Content-Type'] === 'text/html; charset=UTF-8');
        assert(Base\Email::prepareMessage($msg)['header']['MIME-Version'] === '1.0');
        $msg2 = $msg;
        $msg2['priority'] = 2;
        $msg2['header']['test'] = 'YEAH';
        $msg2['header']['test2 '] = 2;
        assert(Base\Email::prepareMessage($msg2)['header']['X-Priority'] === 2);
        assert(Base\Email::prepareMessage($msg2)['header']['test2'] === 2);
        assert(count(Base\Email::prepareMessage($msg2)['header']) === 10);
        assert(count(Base\Email::prepareMessage($msg2,false)['header']) === 2);

        // prepareTestMessage

        // prepareContentTypeCharset
        assert(Base\Email::prepareContentTypeCharset()['charset'] === 'UTF-8');
        assert(Base\Email::prepareContentTypeCharset('html')['contentTypeCharset'] === 'text/html; charset=UTF-8');
        assert(Base\Email::prepareContentTypeCharset('text/html','latin1')['contentTypeCharset'] === 'text/html');

        // prepareHeader
        assert(Base\Email::prepareHeader([]) === []);
        assert(Base\Email::prepareHeader(['cc'=>[['email'=>'zwa@test.lz','name'=>'Pierre'],['email'=>'qweqw2@gmail.com','name'=>'Pierre2']]])['Cc'] === 'Pierre <zwa@test.lz>, Pierre2 <qweqw2@gmail.com>');
        assert(Base\Email::prepareHeader(['from'=>[['email'=>'zwa@test.lz','name'=>'Pierre'],['email'=>'qweqw2@gmail.com','name'=>'Pierre2']]])['From'] === 'Pierre <zwa@test.lz>');
        assert(Base\Email::prepareHeader(['cc'=>['email'=>'zwa@test.lz','name'=>'Pierre']])['Cc'] === 'Pierre <zwa@test.lz>');
        assert(Base\Email::prepareHeader(['from'=>[['email'=>'zwa@test.lz','name'=>'Pierre']]])['From'] === 'Pierre <zwa@test.lz>');
        assert(count(Base\Email::prepareHeader(['from'=>[['email'=>'zwa@test.lz','name'=>'Pierre']]],false)) === 0);

        // prepareAddress
        assert(Base\Email::prepareAddress([['email'=>'test@gmail.com','name'=>'James'],['email'=>'test2@gmail.com','name'=>'James2']]) === 'James <test@gmail.com>, James2 <test2@gmail.com>');

        // addresses
        assert(count(Base\Email::addresses(['email'=>'james@james.com','name'=>'NAME','test@gmail.com','test2@gmail.com','james@gmail.com'=>'Test',['test4@gmail.com'],['email'=>'test5@gmail.com','name'=>'OK']])) === 6);
        assert(count(Base\Email::addresses(['e@as.com','james@as.com'])) === 2);
        assert(Base\Email::addresses('test') === []);
        assert(Base\Email::addresses(['test@gmail.com']) === [['email'=>'test@gmail.com','name'=>'test']]);
        assert(Base\Email::addresses('test@gmail.com') === [['email'=>'test@gmail.com','name'=>'test']]);
        assert(Base\Email::addresses(['test@gmail.com'=>'James']) === [['email'=>'test@gmail.com','name'=>'James']]);
        assert(Base\Email::addresses(['email'=>'test@gmail.com','name'=>'James']) === [['email'=>'test@gmail.com','name'=>'James']]);

        // address
        assert(Base\Email::address('test') === null);
        assert(Base\Email::address(['test@gmail.com']) === ['email'=>'test@gmail.com','name'=>'test']);
        assert(Base\Email::address('test@gmail.com') === ['email'=>'test@gmail.com','name'=>'test']);
        assert(Base\Email::address(['test@gmail.com'=>'James']) === ['email'=>'test@gmail.com','name'=>'James']);
        assert(Base\Email::address(['email'=>'test@gmail.com','name'=>'James']) === ['email'=>'test@gmail.com','name'=>'James']);

        // addressStr
        assert(Base\Email::addressStr('zwa@test.lz','Pierre') === 'Pierre <zwa@test.lz>');

        // setActive
        assert(Base\Email::setActive() === null);

        return true;
    }
}
?>