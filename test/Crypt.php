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

// crypt
// class for testing Quid\Base\Crypt
class Crypt extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $empty = Base\Crypt::passwordHash('');
        $hash = Base\Crypt::passwordHash('test010110',['options'=>['cost'=>5]]);
        $hash4 = Base\Crypt::passwordHash('test010110',['options'=>['cost'=>4]]);

        // passwordInfo
        assert(Base\Crypt::passwordInfo($hash)['algoName'] === 'bcrypt');

        // passwordHash
        assert(is_string($hash));
        assert(!empty($hash));
        assert(is_int(Base\Crypt::passwordInfo($hash)['options']['cost']));
        assert(Base\Crypt::passwordInfo($hash4)['options']['cost'] === 4);

        // passwordVerify
        assert(Base\Crypt::passwordVerify('test010110',$hash));
        assert(Base\Crypt::passwordVerify('test010110',$hash4));
        assert(!Base\Crypt::passwordVerify('',''));

        // passwordNeedsRehash
        assert(Base\Crypt::passwordNeedsRehash($hash,['options'=>['cost'=>4]]));

        // passwordNew
        assert(strlen(Base\Crypt::passwordNew()) === 10);
        assert(strlen(Base\Crypt::passwordNew(5)) === 5);
        assert(Base\Crypt::passwordNew(3) === null);

        // passwordValidate

        // passwordActivate
        assert(strlen(Base\Crypt::passwordActivate('dsadssda')) === 40);

        // passwordAlgos
        assert(count(Base\Crypt::passwordAlgos()) === 3);

        // md5
        assert(md5('test') === Base\Crypt::md5('test'));
        assert(strlen(Base\Crypt::md5('test')) === 32);
        assert(strlen(Base\Crypt::md5('test',true)) === 16);

        // hash
        assert(strlen(Base\Crypt::hash('sha1','test')) === 40);

        // sha
        assert(!empty(Base\Crypt::sha('')));
        assert(hash('sha256','test') === Base\Crypt::sha('test',256));
        assert(hash('sha1','test') === Base\Crypt::sha('test',1));
        assert(Base\Crypt::sha('test',256,true) !== Base\Crypt::sha('test',256,false));
        assert(!Base\Str::isPrintable(Base\Crypt::sha('test',256,true)));

        // hashHmac
        assert(strlen(Base\Crypt::hashHmac('sha1','test','bla')) === 40);

        // randomBytes
        assert(is_string(Base\Crypt::randomBytes()));
        assert(strlen(Base\Crypt::randomBytes()) === 11);

        // randomBool
        assert(Base\Crypt::randomBool(1,1));

        // randomInt
        assert(is_int(Base\Crypt::randomInt()));
        assert(strlen((string) Base\Crypt::randomInt(1)) === 1);
        assert(Base\Crypt::randomInt(1,1,1) === 1);

        // randomString
        assert('ééé' === Base\Crypt::randomString(3,'é',true));
        assert('aaa' === Base\Crypt::randomString(3,'a'));
        assert(strlen(Base\Crypt::randomString()) === 40);

        // getRandomString
        assert(strlen(Base\Crypt::getRandomString()) === 62);
        assert(Base\Crypt::getRandomString('abcde') === 'abcde');
        assert(strlen(Base\Crypt::getRandomString('alpha')) === 52);

        // randomArray
        assert(count(Base\Crypt::randomArray([1,2,3,4],2)) === 2);

        // microtime
        assert(strlen(Base\Crypt::microtime()) === 13);
        assert(strlen(Base\Crypt::microtime(null,true)) === 23);
        assert(strlen(Base\Crypt::microtime('zza')) === 16);
        assert(strlen(Base\Crypt::microtime(2)) === 15);
        assert(strlen(Base\Crypt::microtime(2,true)) === 25);

        // base64
        assert(Base\Crypt::base64('abcé') === 'YWJjw6k=');

        // base64Decode
        assert(Base\Crypt::base64Decode('YWJjw6k=') === 'abcé');

        // ssl
        assert(strlen(Base\Crypt::openssl('test','what')) === 32);
        assert(Base\Crypt::openssl('test','what') !== Base\Crypt::openssl('test','whatzzz'));
        assert(strlen(Base\Crypt::openssl('test','what','james')) === 32);
        assert(strlen(Base\Crypt::openssl('testxzxczzxczx@#@##!!##','what')) === 60);

        // sslDecrypt
        assert('test' === Base\Crypt::opensslDecrypt(Base\Crypt::openssl('test','what'),'what'));
        assert('test' === Base\Crypt::opensslDecrypt(Base\Crypt::openssl('test','what','zzz'),'what','zzz'));
        assert(null === Base\Crypt::opensslDecrypt(Base\Crypt::openssl('test','what','zz'),'what','zzz'));
        assert(Base\Crypt::opensslDecrypt(Base\Crypt::openssl('test','what'),'whatz') === null);

        // serialize
        $d = new \Datetime('now');
        $x = Base\Crypt::serialize($d);
        assert(is_string($x));

        // unserialize
        assert(Base\Crypt::unserialize($x) instanceof \DateTime);
        assert(Base\Crypt::unserialize($x,$d) instanceof \DateTime);
        assert(Base\Obj::isIncomplete(Base\Crypt::unserialize($x,"Quid\Base\Arr")));
        assert(Base\Obj::isIncomplete(Base\Crypt::unserialize($x,false)));
        assert(Base\Crypt::unserialize($x,['DateTime']) instanceof \DateTime);

        // onSetSerialize

        // onGetSerialize

        return true;
    }
}
?>