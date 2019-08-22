<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// attr
class Attr extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$html = "data-href='test' class='what  ok  lala' href='test2' style='color: #000; padding: 20px;'";
		$all = array('selected'=>true,'data-test4'=>null,'james'=>true,'style'=>'padding: 10px; color: #000;','class'=>'james ok test','href'=>'bla','alt'=>'LA vie est belle','data'=>array('test'=>2),'data-test2'=>array(1,2,3));
		$attr = "data-href='test' class='what  ok  lala' id='JAMES' href='test2' style='color: #000; padding: 20px;'";
		$captcha = Base\ImageRaster::captcha("test","[assertCommon]/ttf.ttf");

		// prepare
		$image = Base\Uri::relative("[assertMedia]/jpg.jpg");
		Base\Attr::addSelectedUri(array('/test/laa.php'=>true));
		$uriAppend = Base\Attr::getOption('uri/append');
		$styleAppend = Base\Attr::getOption('style/uri/append');
		$styleExists = Base\Attr::getOption('style/uri/exists');
		Base\Attr::setOption('uri/append',false);
		Base\Attr::setOption('style/uri/append',false);
		Base\Attr::setOption('style/uri/exists',false);

		// isDataKey
		assert(Base\Attr::isDataKey("data-test"));
		assert(!Base\Attr::isDataKey("Data-Test"));
		assert(Base\Attr::isDataKey("data-Test"));
		assert(!Base\Attr::isDataKey("href"));

		// isDataUri
		assert(Base\Attr::isDataUri("data:image/png;base64,iVBORw0KGgoAA"));
		assert(!Base\Attr::isDataUri("data:"));
		assert(Base\Attr::isDataUri(Base\Attr::arr(array('test','src'=>$captcha))['src']));
		assert(Base\Attr::isDataUri(Base\Attr::arr(array('test','data-test'=>$captcha))['data-test']));
		assert(!Base\Attr::isDataUri(Base\Attr::arr(array('test','href'=>$captcha))['href']));

		// isSelectedUri
		assert(Base\Attr::isSelectedUri('/test/laa.php'));
		assert(!Base\Attr::isSelectedUri('test/laa.php'));

		// hasId
		assert(Base\Attr::hasId('JAMES',$attr));

		// hasClass
		assert(Base\Attr::hasClass('what ok',$attr));
		assert(Base\Attr::hasClass('what',$attr));
		assert(Base\Attr::hasClass(array('what','ok'),$attr));

		// styleExists
		assert(Base\Attr::styleExists('padding',$attr));

		// stylesExists
		assert(Base\Attr::stylesExists(array('padding','color'),$attr));

		// dataExists
		assert(Base\Attr::dataExists('href',$html));
		assert(!Base\Attr::dataExists("sdaasdasd","class='what'"));

		// datasExists
		assert(Base\Attr::datasExists(array('href'),$html));
		assert(!Base\Attr::datasExists(array('href',false),$html));

		// compare
		assert(Base\Attr::compare(array('test'=>2,'class'=>'test test2','style'=>array('color'=>'#000')),array('test'=>2,'class'=>'test2 test','style'=>array('color'=>'#000'))));
		assert(Base\Attr::compare(array('style'=>array('color'=>'#000'),'test'=>2,'class'=>'test test2'),array('test'=>2,'class'=>array('test2','test'),'style'=>array('color'=>'#000'))));
		assert(!Base\Attr::compare(array('style'=>array('color'=>'#fff'),'test'=>2,'class'=>'test test2'),array('test'=>2,'class'=>array('test2','test'),'style'=>array('color'=>'#000'))));

		// prepareParse

		// prepareParseHref

		// prepareGroup

		// parse
		assert(Base\Attr::parse(array(array(".james.james2#id"),array(".james2.james3#id2")),Base\Attr::option()) === array('id'=>'id2','class'=>array('james','james2','james3')));
		assert(Base\Attr::parse(array('color'=>'#000','style'=>array('padding'=>10)),Base\Attr::option()) === array('style'=>array('padding'=>'10px','color'=>'#000')));
		assert(Base\Attr::parse(array('bgimg'=>'test'),Base\Attr::option()) === array('style'=>array('background-image'=>'url(/test.jpg)')));
		assert(Base\Attr::parse(array('href'=>'/test.jpg'),Base\Attr::option()) === array('href'=>'/test.jpg'));
		assert(Base\Attr::parse(array('href'=>'/test/laa.php'),Base\Attr::option()) === array('href'=>'/test/laa.php','class'=>array('selected')));
		assert(Base\Attr::parse(array('href'=>'https://google.com'),Base\Attr::option()) === array('href'=>'https://google.com','target'=>'_blank'));
		assert(Base\Attr::parse(array("test"),Base\Attr::option()) === array('class'=>array('test')));
		assert(Base\Attr::parse(array('test','test2 test4','#ids'),Base\Attr::option()) === array('class'=>array('test','test2','test4'),'id'=>'ids'));
		assert(Base\Attr::parse(array('#id james','#id2','test test4'),Base\Attr::option()) === array('id'=>'id2','class'=>array('james','test','test4')));
		assert(Base\Attr::parse(array('bgimg'=>'test'),Base\Attr::option()) === array('style'=>array('background-image'=>'url(/test.jpg)')));
		assert(Base\Attr::parse(array('target'=>true),Base\Attr::option()) === array('target'=>'_blank'));
		assert(Base\Attr::parse(array('method'=>true),Base\Attr::option()) === array('method'=>'post'));
		assert(Base\Attr::parse(array('src'=>'test'),Base\Attr::option()) === array('src'=>'/test.jpg'));
		assert(Base\Attr::parse(array('href'=>"https://google.com/en/test.jpg"),Base\Attr::option()) === array('href'=>"https://google.com/en/test.jpg",'target'=>'_blank','hreflang'=>'en'));
		assert(Base\Attr::parse(array('class'=>'test test2','oddEven'=>1),Base\Attr::option()) === array('class'=>array('test','test2','odd')));
		assert(Base\Attr::parse(array('class'=>array('test','test2'),'oddEven'=>2),Base\Attr::option()) === array('class'=>array('test','test2','even')));
		assert(Base\Attr::parse(array('james'=>false,'value'=>false,'data-ok'=>false),Base\Attr::option()) === array('value'=>false,'data-ok'=>false));
		assert(Base\Attr::parse(array('src'=>'http://google.com/james'),Base\Attr::option()) === array('src'=>'http://google.com/james'));
		assert(Base\Attr::parse(array('src'=>'//google.com/james'),Base\Attr::option()) === array('src'=>Base\Request::scheme().'://google.com/james'));
		assert(Base\Attr::parse(array('href'=>"https://google.com/en/test.jpg"),Base\Attr::option(array('href'=>array('active'=>false)))) === array('href'=>'https://google.com/en/test.jpg'));
		assert(Base\Attr::parse(array('href'=>'test@gmail.com'),Base\Attr::option()) === array('href'=>'mailto:test@gmail.com'));
		assert(Base\Attr::parse(array('value'=>''),Base\Attr::option()) === array('value'=>''));

		// parseBasic
		assert(Base\Attr::parseBasic(array("test.test2.test3#test4")) === array('id'=>'test4','class'=>array('test','test2','test3')));
		assert(Base\Attr::parseBasic(array(".test.test2.test3#test4")) === array('id'=>'test4','class'=>array('test','test2','test3')));
		assert(Base\Attr::parseBasic(array("#test4.test.test2.test3")) === array('id'=>'test4','class'=>array('test','test2','test3')));
		assert(Base\Attr::parseBasic("test","#") === array('class'=>array('test')));
		assert(Base\Attr::parseBasic(array('test','test2','test3'),"#") === array('class'=>array('test','test2','test3')));
		assert(Base\Attr::parseBasic("#test","#") === array('id'=>'test'));

		// parseId
		assert(strlen(Base\Attr::parseId(true)) === 10);
		assert(Base\Attr::parseId("test") === "test");
		assert(Base\Attr::parseId("test2") === "test2");
		assert(Base\Attr::parseId("test2 a") === null);
		assert(Base\Attr::parseId(array('test2')) === null);
		assert(Base\Attr::parseId('james-ok') === 'james-ok');

		// parseAlt
		assert(Base\Attr::parseAlt("La vie est belle") === 'la-vie-est-belle');

		// parseClass
		assert(Base\Attr::parseClass("test") === array('test'));
		assert(Base\Attr::parseClass("123") === null);
		assert(Base\Attr::parseClass(array('test','TEST','test',false,true,'123','test3')) === array('test','TEST','test3'));

		// parseUri
		assert(Base\Attr::parseUri("test",'jpg') === '/test.jpg');
		assert(Base\Attr::parseUri("test") === 'test');

		// outputUri
		assert(Base\Attr::outputUri("test") === '/test');
		assert(Base\Attr::outputUri("test",array('append'=>array('t'=>'ok'))) === '/test?t=ok');
		assert(Base\Attr::outputUri("http://google.com/test",array('append'=>array('t'=>'ok'))) === 'http://google.com/test');
		assert(Base\Attr::outputUri("//google.com/test",array('append'=>array('t'=>'ok'))) === Base\Request::scheme().'://google.com/test');

		// parseStyle
		assert(Base\Attr::parseStyle("padding: 10px; margin-left: 10px;") === array('padding'=>'10px','margin-left'=>'10px'));
		assert(Base\Attr::parseStyle(array('background-image'=>'test.jpg','padding'=>10,'color'=>'fff'))['background-image'] === 'url(/test.jpg)');

		// parseData
		assert(Base\Attr::parseData(array('test'=>2,'lat'=>array('test','test2')))['data-test'] === 2);
		assert(Base\Attr::parseData(array('data-test'=>2,'lat'=>array('test','test2')))['data-test'] === 2);
		assert(Base\Attr::parseData(array('test'=>2,'TEST'=>3),CASE_LOWER) === array('data-test'=>3));
		assert(Base\Attr::parseData(array('test'=>2,'TEST'=>3)) === array('data-test'=>2,'data-t-e-s-t'=>3));
		assert(Base\Attr::parseData(array('data-test'=>2,'DATA-TEST'=>3),CASE_LOWER) === array('data-test'=>3));
		assert(Base\Attr::parseData(array('data-test'=>2,'DATA-TEST2'=>3),CASE_LOWER) === array('data-test'=>2,'data-test2'=>3));
		assert(Base\Attr::parseData(array('data-test'=>2,'DATA-TEST'=>3),null) === array('data-test'=>2,'data-d-a-t-a-t-e-s-t'=>3));
		assert(Base\Attr::parseData(array('data-testJamesLol'=>2,'jamesOk'=>true,'lolOkJames'=>4)) === array('data-test-james-lol'=>2,'data-james-ok'=>true,'data-lol-ok-james'=>4));

		// parseDataKey
		assert(Base\Attr::parseDataKey("test") === 'data-test');
		assert(Base\Attr::parseDataKey("data-test") === 'data-test');
		assert(Base\Attr::parseDataKey("data-testOk") === 'data-test-ok');
		assert(Base\Attr::parseDataKey("testOk") === 'data-test-ok');

		// parseDataKeys
		assert(Base\Attr::parseDataKeys(array('test','test2Ok')) === array('data-test','data-test2-ok'));

		// parseMerge
		assert(Base\Attr::parseMerge(array('class'=>array('test3','test')),array('class'=>array('test','test2')))['class'] === array('test','test2','test3'));
		assert(Base\Attr::parseMerge(array('class'=>array('test','test2'),'style'=>array('color'=>'#fff')),array('class'=>array('test'),'style'=>array('color'=>'#000','padding'=>'10px')))['style'] === array('color'=>'#fff','padding'=>'10px'));
		assert(Base\Attr::parseMerge(array('data'=>array('data-test'=>'2','data-test2'=>'3')),array('data-test'=>'1')) === array('data-test'=>'2','data-test2'=>'3'));

		// append
		assert(Base\Attr::append("test","test2","#james",array('test','test3','test4')) === array('class'=>array('test','test2','test3','test4'),'id'=>'james'));
		assert(Base\Attr::append(array('data-test'=>2,'data-test3'=>5),array('data'=>array('test'=>3,'test2'=>4))) === array('data-test'=>3,'data-test3'=>5,'data-test2'=>4));
		assert(Base\Attr::append(array('beurk',null,false,'data-test'=>2,'data-test3'=>5),array('data'=>array('test'=>3,'test2'=>4)))['class'] = array('beurk'));
		assert(Base\Attr::append(array('style'=>'test.jpg'),array('style'=>array('color'=>'#000','padding'=>10),'bam'))['style'] === array('background-image'=>'url(/test.jpg)','color'=>'#000','padding'=>'10px'));

		// list
		assert(count(Base\Attr::list($html)) === 4);
		assert(Base\Attr::list($html)[0] === "data-href='test'");
		assert(count(Base\Attr::list($all)) === 7);
		assert(Base\Attr::list($all,array('caseImplode'=>'ucfirst'))[0] === "Selected='selected'");

		// prepareStr
		assert(count(Base\Attr::prepareStr($html,Base\Attr::option())) === 4);
		assert(Base\Attr::prepareStr("#test ",Base\Attr::option()) === array('#test '));
		assert(Base\Attr::prepareStr(' test test2 ',Base\Attr::option()) === array(' test test2 '));

		// prepareArr
		assert(Base\Attr::prepareArr(array('test '=>'test'),Base\Attr::option()) === array('test '=>'test'));

		// explodeStr
		assert(Base\Attr::explodeStr("class='james ok lala'") === array('class'=>'james ok lala'));
		assert(Base\Attr::explodeStr('class="ok lol" style="color:#000; " data-href="J\'y crois"')['data-href'] === 'J');
		assert(Base\Attr::explodeStr('class="ok lol" style="color:#000; " data-href="J"y crois"')['data-href'] === 'J');

		// explodeClass
		assert(Base\Attr::explodeClass("test") === array('test'));
		assert(Base\Attr::explodeClass("test test2 2 test3  test4") === array('test','test2','test3','test4'));
		assert(Base\Attr::explodeClass("test test2 2 test3  test4",array('test','test5')) === array('test','test5','test2','test3','test4'));
		assert(Base\Attr::explodeClass("test TEST test2 TEST2") === array('test','TEST','test2','TEST2'));

		// classImplode
		assert(Base\Attr::classImplode(array('test','test2')) === 'test test2');
		assert(Base\Attr::classImplode(array('test','test2','test2')) === 'test test2 test2');

		// prepareClass
		assert(Base\Attr::prepareClass('test-test2  ok test3_4') === 'test-test2 ok test3_4');
		assert(Base\Attr::prepareClass('test test2  test3 4') === 'test test2 test3');
		assert(Base\Attr::prepareClass(array('test','test2 test3','4')) === 'test test2 test3');

		// uni
		assert(Base\Arr::validate('string',Base\Attr::keyValue(Base\Attr::arr($all),Base\Attr::option())));
		assert(count(Base\Attr::keyValue(Base\Attr::arr($all),Base\Attr::option())) === 7);
		assert(!empty(Base\Attr::keyValue($all,Base\Attr::option(array('caseImplode'=>function($key) { return ucwords($key,'-'); })))['Data-Test2']));

		// getId
		assert(Base\Attr::getId($attr) === 'JAMES');
		assert(Base\Attr::getId(array('#james')) === 'james');

		// setId
		assert(count(Base\Attr::setId('ok',$attr)) === 5);

		// randomId
		assert(strlen(Base\Attr::randomId(null)) === 10);
		assert(strlen(Base\Attr::randomId("ok")) === 12);
		assert(strlen(Base\Attr::randomId("ok[]")) === 12);

		// getClass
		assert(Base\Attr::getClass($attr) === array('what','ok','lala'));

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
		assert(Base\Attr::setClass(array("james","james2"),$attr)['class'] === array('james','james2'));

		// addClass
		assert(count(Base\Attr::addClass(array('james','what2','what',false),$attr)['class']) === 5);

		// removeClass
		assert(count(Base\Attr::removeClass("ok",$attr)['class']) === 2);

		// toggleClass
		assert(count(Base\Attr::toggleClass('ok what lala2',$attr)['class']) === 2);

		// style
		assert(Base\Attr::style($attr) === array('color'=>'#000','padding'=>'20px'));
		assert(Base\Attr::style("data-href='lala'") === null);

		// getStyle
		assert(Base\Attr::getStyle('color',$attr) === '#000');

		// getsStyle
		assert(Base\Attr::getsStyle(array('color','padding','z'),$attr) === array('color'=>'#000','padding'=>'20px','z'=>null));

		// setStyle
		assert(Base\Attr::str(Base\Attr::setStyle('backgrounéd-color<gam>"','#000',$attr)) === "data-href='test' class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px; backgrounéd-color&lt;gam&gt;&quot;: #000;'");

		// setsStyle
		assert(Base\Attr::str(Base\Attr::setsStyle(array('background-color'=>'#000','color'=>'red'),$attr)) === "data-href='test' class='what ok lala' id='JAMES' href='/test2' style='color: red; padding: 20px; background-color: #000;'");

		// unsetStyle
		assert(Base\Attr::unsetStyle('color',$attr)['style'] === array('padding'=>'20px'));

		// unsetsStyle
		assert(empty(Base\Attr::unsetsStyle(array('color','padding'),$attr)['style']));

		// emptyStyle
		assert(empty(Base\Attr::emptyStyle($all)['style']));

		// data
		assert(Base\Attr::data($attr) === array('data-href'=>'test'));

		// getData
		assert(Base\Attr::getData('href',$attr) === 'test');
		assert(Base\Attr::getData('data-href',$attr) === 'test');

		// getsData
		assert(Base\Attr::getsData(array('href','ok'),$attr) === array('data-href'=>'test','data-ok'=>null));

		// setData
		assert(Base\Attr::implode(Base\Attr::setData("href","james",$attr)) === "data-href='james' class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

		// setsData
		assert(Base\Attr::setsData(array("href"=>'james','data-ok'=>'bla'),$attr)['data-href'] === 'james');

		// unsetData
		assert(empty(Base\Attr::unsetData("href",$attr)['data-href']));
		assert(Base\Attr::implode(Base\Attr::unsetData("data-href",$attr)) === "class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

		// unsetsData
		assert(Base\Attr::implode(Base\Attr::unsetsData(array("href"),$attr)) === "class='what ok lala' id='JAMES' href='/test2' style='color: #000; padding: 20px;'");

		// emptyData
		assert(count(Base\Attr::emptyData($all)) === 5);

		// selectedUri
		assert(Base\Attr::selectedUri() === array('/test/laa.php'=>true));

		// selectedUriArray
		assert(Base\Attr::selectedUriArray() === array('/test/laa.php'));

		// getSelectedUri
		assert(Base\Attr::getSelectedUri('/test/laa.php') === true);

		// addSelectedUri
		Base\Attr::addSelectedUri(array('/test.php'=>'james'));
		assert(Base\Attr::str(array('href'=>'/test.php')) === "href='/test.php' class='james'");

		// removeSelectedUri
		Base\Attr::removeSelectedUri('/test.php');
		assert(Base\Attr::str(array('href'=>'/test.php')) === "href='/test.php'");

		// getUriOption
		assert(is_array(Base\Attr::getUriOption()));

		// setUriOption
		Base\Attr::setUriOption(Base\Attr::getUriOption());

		// other
		assert(Base\Attr::arr(array('value'=>'')) === array('value'=>''));
		assert(Base\Attr::str(array("-james-ok","well-é","ok_well")) === "class='-james-ok ok_well'");
		assert(strlen(Base\Attr::str(array('asd',$captcha,'id'=>$captcha,'src'=>$captcha,'data'=>array('test'=>$captcha)))) > 5000);
		assert(Base\Attr::str($captcha) === '');
		assert(Base\Attr::arr(array("test","test","test")) === array('class'=>array('test')));
		assert(Base\Attr::arr("test test test") === array('class'=>array('test')));
		assert(Base\Attr::arr(array('data-pattern'=>'password')) === array('data-pattern'=>'^(?=.{5,30})(?=.*\d)(?=.*[A-z]).*'));
		assert(Base\Attr::arr(array('data-pattern'=>array('minLength'=>4,'username'))) === array('data-pattern'=>'.{4,}'));
		assert(Base\Attr::arr(array('data'=>array('pattern'=>array('minLength'=>4,'username')))) === array('data-pattern'=>'.{4,}'));
		assert(Base\Attr::arr(array('bla'=>'ok[lang]','data-bla'=>'ok[lang]','href'=>'/monfichier_[lang].jpg'))['bla'] === 'ok[lang]');
		assert(Base\Attr::arr(array('href'=>'/monfichier_[lang].jpg'))['href'] === '/monfichier_en.jpg');
		assert(Base\Attr::arr(array('src'=>'/monfichier_[lang].jpg'))['src'] === '/monfichier_en.jpg');
		assert(Base\Attr::arr(array('href'=>'http://google.com','target'=>false)) === array('href'=>'http://google.com'));
		assert(Base\Attr::arr(array('href'=>'http://google.com')) === array('href'=>'http://google.com','target'=>'_blank'));
		assert(Base\Attr::str(array('style'=>array('color'=>null,'background'=>false))) === "");
		assert(Base\Attr::arr($html)['class'] === array('what','ok','lala'));
		assert(Base\Attr::arr($html)['style'] === array('color'=>'#000','padding'=>'20px'));
		assert(Base\Attr::arr(array('color'=>'#000','padding'=>'20px')) === array('style'=>array('color'=>'#000','padding'=>'20px')));
		assert(Base\Attr::arr(array('james'=>'#000','james2'=>'20px')) === array('james'=>'#000','james2'=>'20px'));
		assert(Base\Attr::arr("#test") === array('id'=>'test'));
		assert(Base\Attr::arr(array('#test')) === array('id'=>'test'));
		assert(Base\Attr::arr("test ") === array('class'=>array('test')));
		assert(Base\Attr::arr(array('test',false,null,true,' test2','test3  test4')) === array('class'=>array('test','test2','test3','test4')));
		assert(Base\Attr::arr(array('data-test'=>2,'class'=>'james'),array('sort'=>true)) === array('class'=>array('james'),'data-test'=>2));
		assert(Base\Attr::arr(array('data-test'=>2,'DATA-TEST'=>3),array('case'=>null)) === array('data-test'=>2,'DATA-TEST'=>3));
		assert(Base\Attr::arr(array('data'=>array('test'=>2),'DATA'=>array('TEST'=>3))) === array('data-test'=>2,'DATA'=>array('TEST'=>3)));
		assert(Base\Attr::arr(array('data'=>array('test'=>2),'DATA'=>array('TEST'=>3)),array('case'=>null)) === array('data-test'=>2,'DATA'=>array('TEST'=>3)));
		assert(Base\Arrs::count(Base\Attr::arr($all)) === 15);
		assert(Base\Attr::arr(array('id'=>array('test','test2'))) === array());
		assert(Base\Attr::arr(array('id'=>'test test2')) === array());
		assert(Base\Attr::arr(array('class'=>'TEST','#JAMES','OK','STYLE'=>array('PADDING'=>10),'DATA'=>array('james'=>true))) === array('class'=>array('TEST','OK'),'STYLE'=>array('PADDING'=>10),'DATA'=>array('james'=>true),'id'=>'JAMES'));
		assert(Base\Attr::arr(array('selected'=>true)) === array('selected'=>'selected'));
		assert(Base\Attr::arr($attr));
		$array = array('class'=>array('test','test2'),'style'=>array('color'=>'#fff'),'data-test'=>2,'id'=>'test');
		assert(Base\Attr::implode($array) === "class='test test2' style='color: #fff;' data-test='2' id='test'");
		assert(Base\Attr::implode($array,array('start'=>true)) === " class='test test2' style='color: #fff;' data-test='2' id='test'");
		assert(Base\Attr::implode(Base\Attr::list($html)) === Base\Attr::implode(Base\Attr::arr($html)));
		assert(Base\Attr::implode(Base\Attr::list($all)) === Base\Attr::implode(Base\Attr::arr($all)));
		assert(Base\Attr::implode(array('data-href'=>'ok','class'=>array(),'style'=>array())) === "data-href='ok'");
		assert(Base\Attr::get('style/color',$attr) === '#000');
		assert(Base\Attr::get(array('style','color'),$attr) === '#000');
		assert(Base\Attr::gets(array(array('style','color')),$attr) === array('style/color'=>'#000'));
		assert(Base\Attr::set('style/margin',10,$attr)['style'] === array('color'=>'#000','padding'=>'20px','margin'=>'10px'));
		assert(Base\Attr::unset('style/padding',$attr)['style'] === array('color'=>'#000'));
		assert(empty(Base\Attr::unsets(array('style/padding','style/color'),$attr)['style']));
		assert(Base\Attr::set('style',array('color'=>'#000','padding'=>10),'') === array('style'=>array('color'=>'#000','padding'=>'10px')));
		assert(Base\Attr::set('data',array('color'=>'#000','padding'=>10),'') === array('data-color'=>'#000','data-padding'=>10));
		assert(Base\Attr::arr(array('data'=>array('color'=>'#000','padding'=>10),'')) === array('data-color'=>'#000','data-padding'=>10));
		assert(Base\Attr::str($html) === "data-href='test' class='what ok lala' href='/test2' style='color: #000; padding: 20px;'");
		assert(Base\Attr::str(array('href'=>'/test/laa.php')) === "href='/test/laa.php' class='selected'");
		assert(Base\Attr::str(array('href'=>'/test/ok','src'=>'what/lala'),array('uri'=>array('append'=>array('v'=>200)))) === "href='/test/ok?v=200' src='/what/lala.jpg?v=200'");
		assert(Base\Attr::str(array('james'=>2,'jami'=>true,'noway'=>false,'li'=>'','data-jami'=>true,'style'=>array('ok'=>'','color'=>'#000','padding'=>false))) === "james='2' li='' data-jami='1' style='color: #000;'");
		assert(Base\Attr::str(array('src'=>$image),array('uri'=>array('exists'=>true))) === "src='".$image."'");
		assert(Base\Attr::str(array('src'=>Base\Request::schemeHost().$image),array('uri'=>array('exists'=>true))) === "src='".$image."'");
		assert(Base\Attr::str(array('href'=>'https://goog.com')) === "href='https://goog.com' target='_blank'");
		assert(Base\Attr::str(array('james'=>false,'value'=>false,'data-ok'=>false)) === "value='0' data-ok='0'");
		assert(Base\Attr::str(array('james'=>'','value'=>'','data-ok'=>'')) === "james='' value='' data-ok=''");
		assert(Base\Attr::str(array('href'=>'http://google.com','target'=>false)) === "href='http://google.com'");
		assert(Base\Attr::str(array('href'=>'http://google.com','target'=>true)) === "href='http://google.com' target='_blank'");
		assert(Base\Attr::str(array('data-test'=>'OKÉÉ<b>I like it</b>')) === "data-test='OKÉÉ&lt;b&gt;I like it&lt;/b&gt;'");
		assert(Base\Attr::str(array('data-test'=>'OKÉÉ<b>I like it</b>'),array('encode'=>null)) === "data-test='OKÉÉ<b>I like it</b>'");
		assert(Base\Attr::str(array('james','href'=>'test@gmail.com')) === "href='mailto:test@gmail.com' class='james'");
		assert(strlen(Base\Attr::str(array(true,'james','href'=>'test@gmail.com'))) === 42);
		assert(strlen(Base\Attr::str(array('id'=>true,'james','href'=>'test@gmail.com'))) === 58);

		// cleanup
		Base\Attr::removeSelectedUri('/test/laa.php');
		Base\Attr::setOption('uri/append',$uriAppend);
		Base\Attr::setOption('style/uri/append',$styleAppend);
		Base\Attr::setOption('style/uri/exists',$styleExists);
		
		return true;
	}
}
?>