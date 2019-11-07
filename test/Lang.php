<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// lang
// class for testing Quid\Base\Lang
class Lang extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        assert(Base\Lang::set('en',['fr','en','de']));

        // is
        assert(Base\Lang::is('en'));
        assert(!Base\Lang::is('za'));

        // isCurrent
        assert(Base\Lang::isCurrent('en'));
        assert(!Base\Lang::isCurrent('za'));

        // isOther
        assert(Base\Lang::isOther('fr'));
        assert(!Base\Lang::isOther('en'));

        // isCallable
        assert(!Base\Lang::isCallable('bla'));

        // hasCallable

        // current
        assert(Base\Lang::current() === 'en');

        // default
        assert(Base\Lang::default() === 'fr');

        // defaultConfig
        assert(Base\Lang::defaultConfig() === 'en');

        // other
        assert(Base\Lang::other(0) === 'fr');
        assert(Base\Lang::other(0,'fr') === 'en');

        // others
        assert(Base\Lang::others() === ['fr','de']);
        assert(Base\Lang::others('fr') === ['en','de']);

        // all
        assert(Base\Lang::all() === ['fr','en','de']);

        // count
        assert(Base\Lang::count() === 3);

        // code
        assert(Base\Lang::code('en') === 'en');
        assert(Base\Lang::code('enz') === 'en');
        assert(Base\Lang::code('fr') === 'fr');

        // prepareCode
        assert(Base\Lang::prepareCode('fr') === 'fr');
        assert(Base\Lang::prepareCode('FRz') === null);

        // set
        assert(Base\Lang::set('de',['de','fr','en']));
        assert(Base\Lang::default() === 'de');
        assert(Base\Lang::current() === 'de');

        // onChange

        // add
        assert(Base\Lang::add('ge') === ['ge'=>true]);
        assert(Base\Lang::add('ge') === ['ge'=>false]);
        assert(Base\Lang::change('ge'));
        assert(Base\Lang::current() === 'ge');
        assert(Base\Lang::add('le','ba') === ['le'=>true,'ba'=>true]);
        assert(Base\Lang::count() === 6);

        // remove
        assert(Base\Lang::remove('le') === ['le'=>true]);
        assert(Base\Lang::remove('le') === ['le'=>false]);
        assert(Base\Lang::current() === 'ge');
        assert(Base\Lang::remove('ge') === ['ge'=>false]);
        assert(Base\Lang::change('de'));
        assert(Base\Lang::remove('ge') === ['ge'=>true]);
        assert(Base\Lang::current() === 'de');
        assert(Base\Lang::remove('de','fr','en','le') === ['de'=>false,'fr'=>true,'en'=>true,'le'=>false]);
        assert(Base\Lang::current() === 'de');
        assert(Base\Lang::remove('ba') === ['ba'=>true]);
        assert(Base\Lang::count() === 1);
        assert(Base\Lang::all() === ['de']);
        assert(Base\Lang::current() === 'de');
        assert(Base\Lang::add('fr','en') === ['fr'=>true,'en'=>true]);
        assert(Base\Lang::change('fr'));
        assert(Base\Lang::remove('de') === ['de'=>true]);

        // change
        assert(Base\Lang::change('en'));
        assert(Base\Lang::change('fr'));
        assert(Base\Lang::change('en'));

        // getCallable

        // setCallable

        // unsetCallable

        // call

        // numberFormat

        // numberPercentFormat

        // numberMoneyFormat

        // numberPhoneFormat

        // numberSizeFormat

        // dateMonth

        // dateFormat

        // dateStr

        // datePlaceholder

        // dateDay

        // dateDayShort

        // headerResponseStatus

        // errorCode

        // validate

        // compare

        // required

        // unique

        // editable

        // content
        assert(Base\Lang::content([1,2]) === [1,2]);
        assert(Base\Lang::content(['test/james'=>2,'test/james2'=>3,'test/james'=>4]) === ['test'=>['james'=>4,'james2'=>3]]);

        // field
        assert(Base\Lang::change('en'));
        assert(Base\Lang::field('name') === 'name_en');
        assert(Base\Lang::field('name','fr','+') === 'name+fr');

        // arr
        $array = ['name_en'=>'test','name+fr'=>'OK'];
        assert(Base\Lang::arr('name',$array) === 'test');
        assert(Base\Lang::arr('name',$array,'fr','+') === 'OK');
        assert(Base\Lang::arr('name',$array,'fr') === null);

        // arrs
        $array = [['name_en'=>'ok','id'=>2],['name_en'=>'deuxie','id'=>3]];
        assert(Base\Lang::arrs('name',$array) === ['0/name_en'=>'ok','1/name_en'=>'deuxie']);

        // reformat
        $array = ['name_en'=>'ok','name_fr'=>'james','name'=>'LOL','ok'=>2];
        assert(Base\Lang::reformat($array) === ['name'=>'ok','ok'=>2]);

        // reformatColumn
        $array = [['name_en'=>'ok','name_fr'=>'james','name'=>'LOL','ok'=>2],['name_en'=>'ok2','name_fr'=>'james2','name'=>'LOL2','ok'=>3]];
        assert(Base\Lang::reformatColumn($array) === [['name'=>'ok','ok'=>2],['name'=>'ok2','ok'=>3]]);

        // cleanup
        assert(Base\Lang::set('en',['en','fr']));
        assert(Base\Lang::current() === 'en');
        assert(Base\Lang::all() === ['en','fr']);

        return true;
    }
}
?>