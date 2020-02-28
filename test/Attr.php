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

// attr
// class for testing Quid\Base\Attr
class Attr extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $html = "data-href='test' class='what  ok  lala' href='test2' style='color: #000; padding: 20px;'";
        $all = ['selected'=>true,'data-test4'=>null,'james'=>true,'style'=>'padding: 10px; color: #000;','class'=>'james ok test','href'=>'bla','alt'=>'LA vie est belle','data'=>['test'=>2],'data-test2'=>[1,2,3]];
        $attr = "data-href='test' class='what  ok  lala' id='JAMES' href='test2' style='color: #000; padding: 20px;'";
        $captcha = Base\ImageRaster::captcha('test','[assertCommon]/ttf.ttf');

        // prepare
        $image = Base\Uri::relative('[assertMedia]/jpg.jpg');
        Base\Attr::addSelectedUri(['/test/laa.php'=>true]);
        $uriAppend = Base\Attr::getOption('uri/append');
        $styleAppend = Base\Attr::getOption('style/uri/append');
        $styleExists = Base\Attr::getOption('style/uri/exists');
        Base\Attr::setOption('uri/append',false);
        Base\Attr::setOption('style/uri/append',false);
        Base\Attr::setOption('style/uri/exists',false);

        // isDataKey
        assert(Base\Attr::isDataKey('data-test'));
        assert(!Base\Attr::isDataKey('Data-Test'));
        assert(Base\Attr::isDataKey('data-Test'));
        assert(!Base\Attr::isDataKey('href'));

        // isDataUri
        assert(Base\Attr::isDataUri('data:image/png;base64,iVBORw0KGgoAA'));
        assert(!Base\Attr::isDataUri('data:'));
        assert(Base\Attr::isDataUri(Base\Attr::arr(['test','src'=>$captcha])['src']));
        assert(Base\Attr::isDataUri(Base\Attr::arr(['test','data-test'=>$captcha])['data-test']));
        assert(!Base\Attr::isDataUri(Base\Attr::arr(['test','href'=>$captcha])['href']));

        // isSelectedUri
        assert(Base\Attr::isSelectedUri('/test/laa.php'));
        assert(!Base\Attr::isSelectedUri('test/laa.php'));

        // hasId
        assert(Base\Attr::hasId('JAMES',$attr));

        // hasClass
        assert(Base\Attr::hasClass('what ok',$attr));
        assert(Base\Attr::hasClass('what',$attr));
        assert(Base\Attr::hasClass(['what','ok'],$attr));

        // styleExists
        assert(Base\Attr::styleExists('padding',$attr));

        // stylesExists
        assert(Base\Attr::stylesExists(['padding','color'],$attr));

        // dataExists
        assert(Base\Attr::dataExists('href',$html));
        assert(!Base\Attr::dataExists('sdaasdasd',"class='what'"));

        // datasExists
        assert(Base\Attr::datasExists(['href'],$html));
        assert(!Base\Attr::datasExists(['href',false],$html));

        // compare
        assert(Base\Attr::compare(['test'=>2,'class'=>'test test2','style'=>['color'=>'#000']],['test'=>2,'class'=>'test2 test','style'=>['color'=>'#000']]));
        assert(Base\Attr::compare(['style'=>['color'=>'#000'],'test'=>2,'class'=>'test test2'],['test'=>2,'class'=>['test2','test'],'style'=>['color'=>'#000']]));
        assert(!Base\Attr::compare(['style'=>['color'=>'#fff'],'test'=>2,'class'=>'test test2'],['test'=>2,'class'=>['test2','test'],'style'=>['color'=>'#000']]));

        // prepareParse

        // prepareParseHref

        // prepareGroup

        // parse
        assert(Base\Attr::parse([['.james.james2#id'],['.james2.james3#id2']],Base\Attr::option()) === ['id'=>'id2','class'=>['james','james2','james3']]);
        assert(Base\Attr::parse(['color'=>'#000','style'=>['padding'=>10]],Base\Attr::option()) === ['style'=>['padding'=>'10px','color'=>'#000']]);
        assert(Base\Attr::parse(['bgimg'=>'test'],Base\Attr::option()) === ['style'=>['background-image'=>'url(/test.jpg)']]);
        assert(Base\Attr::parse(['href'=>'/test.jpg'],Base\Attr::option()) === ['href'=>'/test.jpg']);
        assert(Base\Attr::parse(['href'=>'/test/laa.php'],Base\Attr::option()) === ['href'=>'/test/laa.php','class'=>['selected']]);
        assert(Base\Attr::parse(['href'=>'https://google.com'],Base\Attr::option()) === ['href'=>'https://google.com','target'=>'_blank']);
        assert(Base\Attr::parse(['test'],Base\Attr::option()) === ['class'=>['test']]);
        assert(Base\Attr::parse(['test','test2 test4','#ids'],Base\Attr::option()) === ['class'=>['test','test2','test4'],'id'=>'ids']);
        assert(Base\Attr::parse(['#id james','#id2','test test4'],Base\Attr::option()) === ['id'=>'id2','class'=>['james','test','test4']]);
        assert(Base\Attr::parse(['bgimg'=>'test'],Base\Attr::option()) === ['style'=>['background-image'=>'url(/test.jpg)']]);
        assert(Base\Attr::parse(['target'=>true],Base\Attr::option()) === ['target'=>'_blank']);
        assert(Base\Attr::parse(['method'=>true],Base\Attr::option()) === ['method'=>'post']);
        assert(Base\Attr::parse(['src'=>'test'],Base\Attr::option()) === ['src'=>'/test.jpg']);
        assert(Base\Attr::parse(['href'=>'https://google.com/en/test.jpg'],Base\Attr::option()) === ['href'=>'https://google.com/en/test.jpg','target'=>'_blank']);
        assert(Base\Attr::parse(['class'=>'test test2','oddEven'=>1],Base\Attr::option()) === ['class'=>['test','test2','odd']]);
        assert(Base\Attr::parse(['class'=>['test','test2'],'oddEven'=>2],Base\Attr::option()) === ['class'=>['test','test2','even']]);
        assert(Base\Attr::parse(['james'=>false,'value'=>false,'data-ok'=>false],Base\Attr::option()) === ['james'=>false,'value'=>false,'data-ok'=>false]);
        assert(Base\Attr::parse(['src'=>'http://google.com/james'],Base\Attr::option()) === ['src'=>'http://google.com/james']);
        assert(Base\Attr::parse(['src'=>'//google.com/james'],Base\Attr::option()) === ['src'=>Base\Request::scheme().'://google.com/james']);
        assert(Base\Attr::parse(['href'=>'https://google.com/en/test.jpg'],Base\Attr::option(['href'=>['active'=>false]])) === ['href'=>'https://google.com/en/test.jpg']);
        assert(Base\Attr::parse(['href'=>'test@gmail.com'],Base\Attr::option()) === ['href'=>'mailto:test@gmail.com']);
        assert(Base\Attr::parse(['value'=>''],Base\Attr::option()) === ['value'=>'']);
        assert(Base\Attr::parse(['href'=>'#'],Base\Attr::option()) === ['href'=>'#']);

        // parseBasic
        assert(Base\Attr::parseBasic(['test.test2.test3#test4']) === ['id'=>'test4','class'=>['test','test2','test3']]);
        assert(Base\Attr::parseBasic(['.test.test2.test3#test4']) === ['id'=>'test4','class'=>['test','test2','test3']]);
        assert(Base\Attr::parseBasic(['#test4.test.test2.test3']) === ['id'=>'test4','class'=>['test','test2','test3']]);
        assert(Base\Attr::parseBasic('test','#') === ['class'=>['test']]);
        assert(Base\Attr::parseBasic(['test','test2','test3'],'#') === ['class'=>['test','test2','test3']]);
        assert(Base\Attr::parseBasic('#test','#') === ['id'=>'test']);

        // parseId
        assert(strlen(Base\Attr::parseId(true)) === 10);
        assert(Base\Attr::parseId('test') === 'test');
        assert(Base\Attr::parseId('test2') === 'test2');
        assert(Base\Attr::parseId('test2 a') === null);
        assert(Base\Attr::parseId(['test2']) === null);
        assert(Base\Attr::parseId('james-ok') === 'james-ok');

        // parseAlt
        assert(Base\Attr::parseAlt('La vie est belle') === 'la-vie-est-belle');

        // parseClass
        assert(Base\Attr::parseClass('test') === ['test']);
        assert(Base\Attr::parseClass('123') === null);
        assert(Base\Attr::parseClass(['test','TEST','test',false,true,'123','test3']) === ['test','TEST','test3']);

        // parseUri
        assert(Base\Attr::parseUri('test','jpg') === '/test.jpg');
        assert(Base\Attr::parseUri('test') === 'test');
        assert(Base\Attr::parseUri('#') === '#');

        // outputUri
        assert(Base\Attr::outputUri('test') === '/test');
        assert(Base\Attr::outputUri('test',['append'=>['t'=>'ok']]) === '/test?t=ok');
        assert(Base\Attr::outputUri('http://google.com/test',['append'=>['t'=>'ok']]) === 'http://google.com/test');
        assert(Base\Attr::outputUri('//google.com/test',['append'=>['t'=>'ok']]) === Base\Request::scheme().'://google.com/test');

        // parseStyle
        assert(Base\Attr::parseStyle('padding: 10px; margin-left: 10px;') === ['padding'=>'10px','margin-left'=>'10px']);
        assert(Base\Attr::parseStyle(['background-image'=>'test.jpg','padding'=>10,'color'=>'fff'])['background-image'] === 'url(/test.jpg)');

        // parseData
        assert(Base\Attr::parseData(['test'=>2,'lat'=>['test','test2']])['data-test'] === 2);
        assert(Base\Attr::parseData(['data-test'=>2,'lat'=>['test','test2']])['data-test'] === 2);
        assert(Base\Attr::parseData(['test'=>2,'TEST'=>3],CASE_LOWER) === ['data-test'=>3]);
        assert(Base\Attr::parseData(['test'=>2,'TEST'=>3]) === ['data-test'=>2,'data-t-e-s-t'=>3]);
        assert(Base\Attr::parseData(['data-test'=>2,'DATA-TEST'=>3],CASE_LOWER) === ['data-test'=>3]);
        assert(Base\Attr::parseData(['data-test'=>2,'DATA-TEST2'=>3],CASE_LOWER) === ['data-test'=>2,'data-test2'=>3]);
        assert(Base\Attr::parseData(['data-test'=>2,'DATA-TEST'=>3],null) === ['data-test'=>2,'data-d-a-t-a-t-e-s-t'=>3]);
        assert(Base\Attr::parseData(['data-testJamesLol'=>2,'jamesOk'=>true,'lolOkJames'=>4]) === ['data-test-james-lol'=>2,'data-james-ok'=>true,'data-lol-ok-james'=>4]);

        // parseDataKey
        assert(Base\Attr::parseDataKey('test') === 'data-test');
        assert(Base\Attr::parseDataKey('data-test') === 'data-test');
        assert(Base\Attr::parseDataKey('data-testOk') === 'data-test-ok');
        assert(Base\Attr::parseDataKey('testOk') === 'data-test-ok');

        // parseDataKeys
        assert(Base\Attr::parseDataKeys(['test','test2Ok']) === ['data-test','data-test2-ok']);

        // parseMerge
        assert(Base\Attr::parseMerge(['class'=>['test3','test']],['class'=>['test','test2']])['class'] === ['test','test2','test3']);
        assert(Base\Attr::parseMerge(['class'=>['test','test2'],'style'=>['color'=>'#fff']],['class'=>['test'],'style'=>['color'=>'#000','padding'=>'10px']])['style'] === ['color'=>'#fff','padding'=>'10px']);
        assert(Base\Attr::parseMerge(['data'=>['data-test'=>'2','data-test2'=>'3']],['data-test'=>'1']) === ['data-test'=>'2','data-test2'=>'3']);

        // append
        assert(Base\Attr::append('test','test2','#james',['test','test3','test4']) === ['class'=>['test','test2','test3','test4'],'id'=>'james']);
        assert(Base\Attr::append(['data-test'=>2,'data-test3'=>5],['data'=>['test'=>3,'test2'=>4]]) === ['data-test'=>3,'data-test3'=>5,'data-test2'=>4]);
        assert(Base\Attr::append(['beurk',null,false,'data-test'=>2,'data-test3'=>5],['data'=>['test'=>3,'test2'=>4]])['class'] = ['beurk']);
        assert(Base\Attr::append(['style'=>'test.jpg'],['style'=>['color'=>'#000','padding'=>10],'bam'])['style'] === ['background-image'=>'url(/test.jpg)','color'=>'#000','padding'=>'10px']);

        // list
        assert(count(Base\Attr::list($html)) === 4);
        assert(Base\Attr::list($html)[0] === "data-href='test'");
        assert(count(Base\Attr::list($all)) === 8);
        assert(Base\Attr::list($all,['caseImplode'=>'ucfirst'])[0] === "Selected='selected'");

        // prepareStr
        assert(count(Base\Attr::prepareStr($html,Base\Attr::option())) === 4);
        assert(Base\Attr::prepareStr('#test ',Base\Attr::option()) === ['#test ']);
        assert(Base\Attr::prepareStr(' test test2 ',Base\Attr::option()) === [' test test2 ']);

        // prepareArr
        assert(Base\Attr::prepareArr(['test '=>'test'],Base\Attr::option()) === ['test '=>'test']);

        // explodeStr
        assert(Base\Attr::explodeStr("class='james ok lala'") === ['class'=>'james ok lala']);
        assert(Base\Attr::explodeStr('class="ok lol" style="color:#000; " data-href="J\'y crois"')['data-href'] === 'J');
        assert(Base\Attr::explodeStr('class="ok lol" style="color:#000; " data-href="J"y crois"')['data-href'] === 'J');

        // explodeClass
        assert(Base\Attr::explodeClass('test') === ['test']);
        assert(Base\Attr::explodeClass('test test2 2 test3  test4') === ['test','test2','test3','test4']);
        assert(Base\Attr::explodeClass('test test2 2 test3  test4',['test','test5']) === ['test','test5','test2','test3','test4']);
        assert(Base\Attr::explodeClass('test TEST test2 TEST2') === ['test','TEST','test2','TEST2']);

        // classImplode
        assert(Base\Attr::classImplode(['test','test2']) === 'test test2');
        assert(Base\Attr::classImplode(['test','test2','test2']) === 'test test2 test2');

        // prepareClass
        assert(Base\Attr::prepareClass('test-test2  ok test3_4') === 'test-test2 ok test3_4');
        assert(Base\Attr::prepareClass('test test2  test3 4') === 'test test2 test3');
        assert(Base\Attr::prepareClass(['test','test2 test3','4']) === 'test test2 test3');

        // uni
        assert(Base\Arr::validate('string',Base\Attr::keyValue(Base\Attr::arr($all),Base\Attr::option())));
        assert(count(Base\Attr::keyValue(Base\Attr::arr($all),Base\Attr::option())) === 8);
        assert(!empty(Base\Attr::keyValue($all,Base\Attr::option(['caseImplode'=>function($key) { return ucwords($key,'-'); }]))['Data-Test2']));

        // getId
        assert(Base\Attr::getId($attr) === 'JAMES');
        assert(Base\Attr::getId(['#james']) === 'james');

        // setId
        assert(count(Base\Attr::setId('ok',$attr)) === 5);

        // randomId
        assert(strlen(Base\Attr::randomId(null)) === 10);
        assert(strlen(Base\Attr::randomId('ok')) === 12);
        assert(strlen(Base\Attr::randomId('ok[]')) === 12);

        // getClass
        assert(Base\Attr::getClass($attr) === ['what','ok','lala']);

        // getOddEvenClass
        assert(Base\Attr::getOddEvenClass(1) === 'odd');
        assert(Base\Attr::getOddEvenClass(2) === 'even');
        assert(Base\Attr::getOddEvenClass(1.1) === null);
        assert(Base\Attr::getOddEvenClass(0) === 'even');

        // getOddClass
        assert(Base\Attr::getOddClass() === 'odd');

        // getEvenClass
        assert(Base\Attr::getEvenClass() === 'even');

        // setClass
        assert(Base\Attr::setClass(['james','james2'],$attr)['class'] === ['james','james2']);

        // addClass
        assert(count(Base\Attr::addClass(['james','what2','what',false],$attr)['class']) === 5);

        // removeClass
        assert(count(Base\Attr::removeClass('ok',$attr)['class']) === 2);

        // toggleClass
        assert(count(Base\Attr::toggleClass('ok what lala2',$attr)['class']) === 2);

        // style
        assert(Base\Attr::style($attr) === ['color'=>'#000','padding'=>'20px']);
        assert(Base\Attr::style("data-href='lala'") === null);

        // getStyle
        assert(Base\Attr::getStyle('color',$attr) === '#000');

        // getsStyle
        assert(Base\Attr::getsStyle(['color','padding','z'],$attr) === ['color'=>'#000','padding'=>'20px','z'=>null]);

        // setStyle
        assert(Base\Attr::str(Base\Attr::setStyle('backgrounéd-color<gam>"','#000',$attr)) === "data-href='test' class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px; backgrounéd-color&lt;gam&gt;&quot;: #000;'");

        // setsStyle
        assert(Base\Attr::str(Base\Attr::setsStyle(['background-color'=>'#000','color'=>'red'],$attr)) === "data-href='test' class='what ok lala' id='JAMES' href='/test2' style='color: red; padding: 20px; background-color: #000;'");

        // unsetStyle
        assert(Base\Attr::unsetStyle('color',$attr)['style'] === ['padding'=>'20px']);

        // unsetsStyle
        assert(empty(Base\Attr::unsetsStyle(['color','padding'],$attr)['style']));

        // emptyStyle
        assert(empty(Base\Attr::emptyStyle($all)['style']));

        // data
        assert(Base\Attr::data($attr) === ['data-href'=>'test']);

        // getData
        assert(Base\Attr::getData('href',$attr) === 'test');
        assert(Base\Attr::getData('data-href',$attr) === 'test');

        // getsData
        assert(Base\Attr::getsData(['href','ok'],$attr) === ['data-href'=>'test','data-ok'=>null]);

        // setData
        assert(Base\Attr::implode(Base\Attr::setData('href','james',$attr)) === "data-href='james' class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

        // setsData
        assert(Base\Attr::setsData(['href'=>'james','data-ok'=>'bla'],$attr)['data-href'] === 'james');

        // unsetData
        assert(empty(Base\Attr::unsetData('href',$attr)['data-href']));
        assert(Base\Attr::implode(Base\Attr::unsetData('data-href',$attr)) === "class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

        // unsetsData
        assert(Base\Attr::implode(Base\Attr::unsetsData(['href'],$attr)) === "class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

        // emptyData
        assert(count(Base\Attr::emptyData($all)) === 6);

        // selectedUri
        assert(Base\Attr::selectedUri() === ['/test/laa.php'=>true]);

        // selectedUriArray
        assert(Base\Attr::selectedUriArray() === ['/test/laa.php']);

        // getSelectedUri
        assert(Base\Attr::getSelectedUri('/test/laa.php') === true);

        // addSelectedUri
        Base\Attr::addSelectedUri(['/test.php'=>'james']);
        assert(Base\Attr::str(['href'=>'/test.php']) === "href='/test.php' class='james'");

        // removeSelectedUri
        Base\Attr::removeSelectedUri('/test.php');
        assert(Base\Attr::str(['href'=>'/test.php']) === "href='/test.php'");

        // getUriOption
        assert(is_array(Base\Attr::getUriOption()));

        // setUriOption
        Base\Attr::setUriOption(Base\Attr::getUriOption());

        // other
        assert(Base\Attr::str(['james'=>2,'class'=>false,'id'=>false]) === "james='2'");
        assert(Base\Attr::str(['src'=>'[media]/ok.jpg','style'=>['bgimg'=>'[media]/test.jpg']]) === "src='/media/ok.jpg' style='background-image: url(/media/test.jpg);'");
        assert(Base\Attr::str(['action'=>'[media]/ok.jpg']) === "action='/media/ok.jpg'");
        assert(Base\Attr::str(['href'=>'[media]/ok.jpg']) === "href='/media/ok.jpg'");
        assert(Base\Attr::str(['test'=>'[media]/ok.jpg']) === "test='[media]/ok.jpg'");
        assert(Base\Attr::arr(['value'=>'']) === ['value'=>'']);
        assert(Base\Attr::str(['-james-ok','well-é','ok_well']) === "class='-james-ok ok_well'");
        assert(strlen(Base\Attr::str(['asd',$captcha,'id'=>$captcha,'src'=>$captcha,'data'=>['test'=>$captcha]])) > 5000);
        assert(Base\Attr::str($captcha) === '');
        assert(Base\Attr::arr(['test','test','test']) === ['class'=>['test']]);
        assert(Base\Attr::arr('test test test') === ['class'=>['test']]);
        assert(Base\Attr::arr(['data-pattern'=>'password']) === ['data-pattern'=>'^(?=.{5,30})(?=.*\d)(?=.*[A-z]).*']);
        assert(Base\Attr::arr(['data-pattern'=>['minLength'=>4,'username']]) === ['data-pattern'=>'.{4,}']);
        assert(Base\Attr::arr(['data'=>['pattern'=>['minLength'=>4,'username']]]) === ['data-pattern'=>'.{4,}']);
        assert(Base\Attr::arr(['bla'=>'ok[lang]','data-bla'=>'ok[lang]','href'=>'/monfichier_[lang].jpg'])['bla'] === 'ok[lang]');
        assert(Base\Attr::arr(['href'=>'/monfichier_[lang].jpg'])['href'] === '/monfichier_en.jpg');
        assert(Base\Attr::arr(['src'=>'/monfichier_[lang].jpg'])['src'] === '/monfichier_en.jpg');
        assert(Base\Attr::arr(['href'=>'http://google.com','target'=>false]) === ['href'=>'http://google.com']);
        assert(Base\Attr::arr(['href'=>'http://google.com']) === ['href'=>'http://google.com','target'=>'_blank']);
        assert(Base\Attr::str(['style'=>['color'=>null,'background'=>false]]) === '');
        assert(Base\Attr::arr($html)['class'] === ['what','ok','lala']);
        assert(Base\Attr::arr($html)['style'] === ['color'=>'#000','padding'=>'20px']);
        assert(Base\Attr::arr(['color'=>'#000','padding'=>'20px']) === ['style'=>['color'=>'#000','padding'=>'20px']]);
        assert(Base\Attr::arr(['james'=>'#000','james2'=>'20px']) === ['james'=>'#000','james2'=>'20px']);
        assert(Base\Attr::arr('#test') === ['id'=>'test']);
        assert(Base\Attr::arr(['#test']) === ['id'=>'test']);
        assert(Base\Attr::arr('test ') === ['class'=>['test']]);
        assert(Base\Attr::arr(['test',false,null,true,' test2','test3  test4']) === ['class'=>['test','test2','test3','test4']]);
        assert(Base\Attr::arr(['data-test'=>2,'class'=>'james'],['sort'=>true]) === ['class'=>['james'],'data-test'=>2]);
        assert(Base\Attr::arr(['data-test'=>2,'DATA-TEST'=>3],['case'=>null]) === ['data-test'=>2,'DATA-TEST'=>3]);
        assert(Base\Attr::arr(['data'=>['test'=>2],'DATA'=>['TEST'=>3]]) === ['data-test'=>2,'DATA'=>['TEST'=>3]]);
        assert(Base\Attr::arr(['data'=>['test'=>2],'DATA'=>['TEST'=>3]],['case'=>null]) === ['data-test'=>2,'DATA'=>['TEST'=>3]]);
        assert(Base\Arrs::count(Base\Attr::arr($all)) === 16);
        assert(Base\Attr::arr(['id'=>['test','test2']]) === []);
        assert(Base\Attr::arr(['id'=>'test test2']) === []);
        assert(Base\Attr::arr(['class'=>'TEST','#JAMES','OK','STYLE'=>['PADDING'=>10],'DATA'=>['james'=>true]]) === ['class'=>['TEST','OK'],'STYLE'=>['PADDING'=>10],'DATA'=>['james'=>true],'id'=>'JAMES']);
        assert(Base\Attr::arr(['selected'=>true]) === ['selected'=>'selected']);
        assert(Base\Attr::arr($attr));
        $array = ['class'=>['test','test2'],'style'=>['color'=>'#fff'],'data-test'=>2,'id'=>'test'];
        assert(Base\Attr::implode($array) === "class='test test2' style='color: #fff;' data-test='2' id='test'");
        assert(Base\Attr::implode($array,['start'=>true]) === " class='test test2' style='color: #fff;' data-test='2' id='test'");
        assert(Base\Attr::implode(Base\Attr::list($html)) === Base\Attr::implode(Base\Attr::arr($html)));
        assert(Base\Attr::implode(Base\Attr::list($all)) === Base\Attr::implode(Base\Attr::arr($all)));
        assert(Base\Attr::implode(['data-href'=>'ok','class'=>[],'style'=>[]]) === "data-href='ok'");
        assert(Base\Attr::get('style/color',$attr) === '#000');
        assert(Base\Attr::get(['style','color'],$attr) === '#000');
        assert(Base\Attr::gets([['style','color']],$attr) === ['style/color'=>'#000']);
        assert(Base\Attr::set('style/margin',10,$attr)['style'] === ['color'=>'#000','padding'=>'20px','margin'=>'10px']);
        assert(Base\Attr::unset('style/padding',$attr)['style'] === ['color'=>'#000']);
        assert(empty(Base\Attr::unsets(['style/padding','style/color'],$attr)['style']));
        assert(Base\Attr::set('style',['color'=>'#000','padding'=>10],'') === ['style'=>['color'=>'#000','padding'=>'10px']]);
        assert(Base\Attr::set('data',['color'=>'#000','padding'=>10],'') === ['data-color'=>'#000','data-padding'=>10]);
        assert(Base\Attr::arr(['data'=>['color'=>'#000','padding'=>10],'']) === ['data-color'=>'#000','data-padding'=>10]);
        assert(Base\Attr::str($html) === "data-href='test' class='what ok lala' href='/test2' style='color: #000; padding: 20px;'");
        assert(Base\Attr::str(['href'=>'/test/laa.php']) === "href='/test/laa.php' class='selected'");
        assert(Base\Attr::str(['href'=>'/test/ok','src'=>'what/lala'],['uri'=>['append'=>['v'=>200]]]) === "href='/test/ok?v=200' src='/what/lala.jpg?v=200'");
        assert(Base\Attr::str(['james'=>2,'jami'=>true,'noway'=>false,'li'=>'','data-jami'=>true,'style'=>['ok'=>'','color'=>'#000','padding'=>false]]) === "james='2' jami='1' noway='0' li='' data-jami='1' style='color: #000;'");
        assert(Base\Attr::str(['src'=>$image],['uri'=>['exists'=>true]]) === "src='".$image."'");
        assert(Base\Attr::str(['src'=>Base\Request::schemeHost().$image],['uri'=>['exists'=>true]]) === "src='".$image."'");
        assert(Base\Attr::str(['href'=>'https://goog.com']) === "href='https://goog.com' target='_blank'");
        assert(Base\Attr::str(['james'=>false,'value'=>false,'data-ok'=>false]) === "james='0' value='0' data-ok='0'");
        assert(Base\Attr::str(['james'=>'','value'=>'','data-ok'=>'']) === "james='' value='' data-ok=''");
        assert(Base\Attr::str(['href'=>'http://google.com','target'=>false]) === "href='http://google.com'");
        assert(Base\Attr::str(['href'=>'http://google.com','target'=>true]) === "href='http://google.com' target='_blank'");
        assert(Base\Attr::str(['data-test'=>'OKÉÉ<b>I like it</b>']) === "data-test='OKÉÉ&lt;b&gt;I like it&lt;/b&gt;'");
        assert(Base\Attr::str(['data-test'=>'OKÉÉ<b>I like it</b>'],['encode'=>null]) === "data-test='OKÉÉ<b>I like it</b>'");
        assert(Base\Attr::str(['james','href'=>'test@gmail.com']) === "href='mailto:test@gmail.com' class='james'");
        assert(strlen(Base\Attr::str([true,'james','href'=>'test@gmail.com'])) === 42);
        assert(strlen(Base\Attr::str(['id'=>true,'james','href'=>'test@gmail.com'])) === 58);
        assert(Base\Attr::str(['multiple'=>true]) === "multiple='multiple'");
        assert(Base\Attr::str(['multiple'=>false]) === '');

        // cleanup
        Base\Attr::removeSelectedUri('/test/laa.php');
        Base\Attr::setOption('uri/append',$uriAppend);
        Base\Attr::setOption('style/uri/append',$styleAppend);
        Base\Attr::setOption('style/uri/exists',$styleExists);

        return true;
    }
}
?>