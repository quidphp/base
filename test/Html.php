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

// html
// class for testing Quid\Base\Html
class Html extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        Base\Attr::addSelectedUri(['/test/laa.php'=>true]);
        $uriAppend = Base\Attr::getOption('uri/append');
        $styleAppend = Base\Style::getOption('uri/append');
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaJpgUri = Base\Uri::relative($mediaJpg);
        $captcha = Base\ImageRaster::captcha('test','[assertCommon]/ttf.ttf');
        $res = Base\Res::open($mediaJpg);
        Base\Attr::setOption('uri/append',false);
        Base\Style::setOption('uri/append',false);

        // is
        assert(!Base\Html::is('<?xml'));
        assert(Base\Html::is('<div>'));
        assert(Base\Html::is(' <div>'));

        // isWrap
        assert(Base\Html::isWrap('divele'));
        assert(!Base\Html::isWrap('divelez'));

        // isAlias
        assert(Base\Html::isAlias('anchor'));
        assert(!Base\Html::isAlias('anchorz'));

        // isInputType
        assert(!Base\Html::isInputType('select'));
        assert(!Base\Html::isInputType('input'));
        assert(Base\Html::isInputType('text'));
        assert(Base\Html::isInputType('email'));
        assert(!Base\Html::isInputType('textz'));

        // isInputMethod
        assert(Base\Html::isInputMethod('inputText'));
        assert(!Base\Html::isInputMethod('input'));
        assert(!Base\Html::isInputMethod('select'));
        assert(Base\Html::isInputMethod('inputHidden'));
        assert(!Base\Html::isInputMethod('inputHiddenz'));

        // isFormTag
        assert(Base\Html::isFormTag('input'));
        assert(Base\Html::isFormTag('select'));
        assert(!Base\Html::isFormTag('email'));
        assert(Base\Html::isFormTag('radio'));
        assert(Base\Html::isFormTag('checkbox'));
        assert(Base\Html::isFormTag('multiselect'));
        assert(Base\Html::isFormTag('textarea'));
        assert(!Base\Html::isFormTag('inputText',false));
        assert(Base\Html::isFormTag('inputText'));
        assert(Base\Html::isFormTag('inputFile'));
        assert(Base\Html::isFormTag('inputHidden'));

        // isTextTag
        assert(Base\Html::isTextTag('input'));
        assert(Base\Html::isTextTag('textarea'));
        assert(!Base\Html::isTextTag('select'));
        assert(!Base\Html::isTextTag('inputHidden'));
        assert(Base\Html::isTextTag('inputText'));
        assert(!Base\Html::isTextTag('inputText',false));

        // isHiddenTag
        assert(Base\Html::isHiddenTag('inputHidden'));
        assert(Base\Html::isHiddenTag('hidden'));
        assert(!Base\Html::isHiddenTag('inputText'));

        // isRelationTag
        assert(!Base\Html::isRelationTag('input'));
        assert(Base\Html::isRelationTag('select'));
        assert(Base\Html::isRelationTag('checkbox'));

        // isEnumTag
        assert(!Base\Html::isEnumTag('input'));
        assert(Base\Html::isEnumTag('select'));
        assert(Base\Html::isEnumTag('radio'));
        assert(Base\Html::isEnumTag('inputRadio'));
        assert(!Base\Html::isEnumTag('inputRadio',false));

        // isSetTag
        assert(!Base\Html::isSetTag('input'));
        assert(!Base\Html::isSetTag('select'));
        assert(Base\Html::isSetTag('multiselect'));
        assert(Base\Html::isSetTag('checkbox'));
        assert(Base\Html::isSetTag('inputCheckbox'));
        assert(!Base\Html::isSetTag('inputCheckbox',false));

        // isNameMulti
        assert(!Base\Html::isNameMulti('text'));
        assert(Base\Html::isNameMulti('text[]'));

        // isSelfClosing
        assert(!Base\Html::isSelfClosing('div'));
        assert(Base\Html::isSelfClosing('meta'));

        // isMetaProperty
        assert(!Base\Html::isMetaProperty('description'));
        assert(Base\Html::isMetaProperty('og:title'));

        // isMetaUri
        assert(!Base\Html::isMetaUri('og:title'));
        assert(Base\Html::isMetaUri('og:image'));

        // isUriOption
        assert(Base\Html::isUriOption('a'));
        assert(Base\Html::isUriOption('img'));
        assert(Base\Html::isUriOption('form'));
        assert(Base\Html::isUriOption('script'));
        assert(Base\Html::isUriOption('link'));
        assert(!Base\Html::isUriOption('div'));

        // isInputGroup
        assert(Base\Html::isInputGroup('text','inputText'));
        assert(Base\Html::isInputGroup('set','inputCheckbox'));
        assert(!Base\Html::isInputGroup('set','checkbox'));
        assert(Base\Html::isInputGroup('enum','inputRadio'));
        assert(Base\Html::isInputGroup('text','inputPassword'));
        assert(Base\Html::isInputGroup('text','inputEmail'));

        // isMultipartFormData
        assert(Base\Html::isMultipartFormData('multipart/form-data'));

        // inputsFromGroups
        assert(Base\Html::inputsFromGroups(['enum','set']) === ['select','radio','multiselect','checkbox']);

        // relationTag
        assert(Base\Html::relationTag() === ['select','radio','multiselect','checkbox']);
        assert(Base\Html::relationTag('multiselect') === ['select','radio','checkbox']);

        // encode
        assert(Base\Html::encode("<la vie>estbellé '&la</>",'specialChars') === '&lt;la vie&gt;estbellé &apos;&amp;la&lt;/&gt;');
        assert(Base\Html::encode("<la vie>est\nbellé '&la</>",'entities') === "&lt;la vie&gt;est\nbell&eacute; &#039;&amp;la&lt;/&gt;");

        // decode
        assert(Base\Html::decode('la &apos; &quot; vié &lt;script&gt;&lt;/script&gt;👦🏼👦👦','specialChars') === 'la \' " vié <script></script>👦🏼👦👦');
        assert(Base\Html::decode("&lt;la vie&gt;est\nbell&eacute; &#039;&amp;la&lt;/&gt;",'entities') === "<la vie>est\nbellé '&la</>");

        // specialChars
        assert(Base\Html::specialChars("<la vie>est\nbellé '&la</>") === "&lt;la vie&gt;est\nbellé &apos;&amp;la&lt;/&gt;");

        // specialCharsDecode
        assert(Base\Html::specialCharsDecode("&lt;la vie&gt;est\nbellé &apos;&amp;la&lt;/&gt;") === "<la vie>est\nbellé '&la</>");
        assert(Base\Html::specialCharsDecode('la &apos; &quot; vié &lt;script&gt;&lt;/script&gt;👦🏼👦👦') === 'la \' " vié <script></script>👦🏼👦👦');

        // specialCharsTable
        assert(count(Base\Html::specialCharsTable()) === 5);

        // entities
        assert(Base\Html::entities("<la vie>est\nbellé '&la</>") === "&lt;la vie&gt;est\nbell&eacute; &#039;&amp;la&lt;/&gt;");

        // entitiesDecode
        assert(Base\Html::entitiesDecode("&lt;la vie&gt;est\nbell&eacute; &#039;&amp;la&lt;/&gt;") === "<la vie>est\nbellé '&la</>");

        // entitiesTable
        assert(count(Base\Html::entitiesTable()) > 200);

        // nl2br
        assert(Base\Html::nl2br("test\n\nbla",false) === "test<br />\n<br />\nbla");
        assert(Base\Html::nl2br("test\n\nbla") === 'test<br /><br />bla');
        assert(Base\Html::nl2br("test\n\nbla",true) === 'test<br /><br />bla');
        assert(Base\Html::nl2br("test\n\nbla",true,false) === 'test<br><br>bla');

        // brs
        assert(Base\Html::brs(3) === '<br /><br /><br />');
        assert(Base\Html::brs(0) === '');

        // stripTags
        assert(Base\Html::stripTags('<h1><b><u>test</u></b><span>ok</span></h1>') === 'testok');
        assert(Base\Html::stripTags('<h1><b><u>test</u></b><span>ok</span></h1>','h1') === '<h1>testok</h1>');
        assert(Base\Html::stripTags('<h1><b><u>test</u></b><span>ok</span></h1>','<h1>') === '<h1>testok</h1>');
        assert(Base\Html::stripTags('<h1><b><u>test</u></b><span>ok</span></h1>','<h1><b>') === '<h1><b>test</b>ok</h1>');
        assert(Base\Html::stripTags('<h1><b><u>test</u></b><span>ok</span></h1>',['h1','b','<u>']) === '<h1><b><u>test</u></b>ok</h1>');

        // getDefaultInputType
        assert(Base\Html::getDefaultInputType() === 'text');

        // getBool
        assert(Base\Html::getBool(0) === ['false','true']);
        assert(Base\Html::getBool(true) === ['false','true']);

        // getTypeFromInputMethod
        assert(Base\Html::getTypeFromInputMethod('inputEmail') === 'email');
        assert(Base\Html::getTypeFromInputMethod('inputCheckbox') === 'checkbox');
        assert(Base\Html::getTypeFromInputMethod('textarea') === null);

        // getFormTagFromMethod
        assert(Base\Html::getFormTagFromMethod('divTextarea') === 'textarea');
        assert(Base\Html::getFormTagFromMethod('inputText') === 'input');
        assert(Base\Html::getFormTagFromMethod('divSpan') === null);

        // getTypeAttr
        assert(Base\Html::getTypeAttr('link') === 'rel');

        // getTypeFromAttr
        assert(Base\Html::getTypeFromAttr('link',['rel'=>'prev']) === 'prev');

        // getAttr
        assert(Base\Html::getAttr('script') === []);
        assert(Base\Html::getAttr('input','text') === ['type'=>'text','maxlength'=>255]);
        assert(Base\Html::getAttr('input','email') === ['type'=>'email','maxlength'=>255]);
        assert(Base\Html::getAttr('input',null,['type'=>'email']) === ['type'=>'email','maxlength'=>255]);
        assert(Base\Html::getAttr('div',null,'#open') === ['#open']);
        assert(Base\Html::getAttr('a',null,'#open') === ['#open']);

        // getAttrScalar
        assert(Base\Html::getAttrScalar('a',true) === ['target'=>'_blank']);
        assert(Base\Html::getAttrScalar('div','james') === ['james']);
        assert(Base\Html::getAttrScalar('link',true) === ['rel'=>'stylesheet']);

        // getValueAttr
        assert(Base\Html::getValueAttr('div') === null);
        assert(Base\Html::getValueAttr('meta') === 'content');
        assert(Base\Html::getValueAttr('input','image') === 'src');
        assert(Base\Html::getValueAttr('input','text') === 'value');

        // getOption
        assert(Base\Html::getOption('input','radio')['position'] === 2);
        assert(count(Base\Html::getOption('input','radio')) === 7);
        assert(count(Base\Html::getOption('input','text')) === 6);
        assert(count(Base\Html::getOption('div')) === 3);

        // getValueCallable
        assert(Base\Html::getValueCallable('div') === null);
        assert(is_callable(Base\Html::getValueCallable('form')));

        // getAttrCallable
        assert(!empty(Base\Html::getAttrCallable('meta')));
        assert(Base\Html::getAttrCallable('div') === null);

        // parseCallStatic
        assert(Base\Html::parseCallStatic(['li','Cond','Open']) === ['tag'=>['li'],'special'=>'cond','openClose'=>'open']);
        assert(Base\Html::parseCallStatic(['input','Text']) === ['tag'=>['input'],'arg'=>['text']]);

        // getCallStatic
        assert(Base\Html::getCallStatic(['li','Open'])['method'] === 'open');
        assert(Base\Html::getCallStatic(['li','Close'])['method'] === 'close');
        assert(Base\Html::getCallStatic(['li'])['method'] === 'make');
        assert(Base\Html::getCallStatic(['divele','Open'])['method'] === 'open');
        assert(Base\Html::getCallStatic(['divele','Close'])['method'] === 'close');
        assert(Base\Html::getCallStatic(['divele'])['method'] === 'make');
        assert(Base\Html::getCallStatic(['input','Email','Open'])['method'] === 'open');
        assert(Base\Html::getCallStatic(['input','Text','Close'])['method'] === 'close');
        assert(Base\Html::getCallStatic(['input','Date'])['method'] === 'make');
        assert(Base\Html::getCallStatic(['div','Cond'])['method'] === 'cond');
        assert(Base\Html::getCallStatic(['div','Cond','Open'])['method'] === 'condOpen');
        assert(Base\Html::getCallStatic(['div','Cond','Close'])['method'] === 'condClose');
        assert(Base\Html::getCallStatic(['div','Many'])['method'] === 'many');
        assert(Base\Html::getCallStatic(['div','Many','Open'])['method'] === 'manyOpen');
        assert(Base\Html::getCallStatic(['div','Many','Close'])['method'] === 'manyClose');
        assert(Base\Html::getCallStatic(['div','Or'])['method'] === 'or');
        assert(Base\Html::getCallStatic(['div','Or','Open'])['method'] === 'orOpen');
        assert(Base\Html::getCallStatic(['div','Or','Close'])['method'] === 'orClose');
        assert(Base\Html::getCallStatic(['li','img'])['method'] === 'make');
        assert(Base\Html::getCallStatic(['li','Img'])['arg'] === [['li','img']]);
        assert(Base\Html::getCallStatic(['input','Email'])['arg'] === [['input'],'email']);
        assert(Base\Html::getCallStatic(['input','Email','Many'],['ok','yeah'])['arg'][1] === null);
        assert(Base\Html::getCallStatic(['input','Email','Many'],['ok','yeah'])['arg'][2] === ['email','ok']);

        // callStatic
        assert(Base\Html::div('strlen','bla') === "<div class='bla'>strlen</div>");
        assert(Base\Html::a('/test/laa.php','titlé') === "<a href='/test/laa.php' class='selected'>titlé</a>");
        assert(Base\Html::a('/test/laa.php','titlé',null,['attr'=>['href'=>['selected'=>'asd']]]) === "<a href='/test/laa.php' class='asd'>titlé</a>");
        assert(Base\Html::a('/test/laa.php','titlé',null,['attr'=>['href'=>['selected'=>null]]]) === "<a href='/test/laa.php'>titlé</a>");
        assert(Base\Html::a('/test/laa.php',true) === "<a href='/test/laa.php' class='selected'>/test/laa.php</a>");
        assert(Base\Html::liOpen('what','#haha') === "<li id='haha'>what");
        assert(Base\Html::diveleUlClose(['html'=>'divele']) === '</ul></div></div></div>');
        assert(Base\Html::liCond() === '');
        assert(Base\Html::liCond('ok') === '<li>ok</li>');
        assert(Base\Html::aCond('/test/laa.php','title') === "<a href='/test/laa.php' class='selected'>title</a>");
        assert(Base\Html::aCondOpen('/test/laa.php','title','#open') === "<a href='/test/laa.php' id='open' class='selected'>title");
        assert(Base\Html::diveleAnchorCondOpen('/test/laa.php') === "<div class='element'><a href='/test/laa.php' class='selected'>");
        assert(Base\Html::diveleAnchorCondClose('/test/laa.php') === '</a></div>');
        assert(Base\Html::liImg('source.jpg') === "<li><img alt='source' src='/source.jpg'/></li>");
        assert(Base\Html::liImg('source.jpg','OKé asddsa') === "<li><img alt='oke-asddsa' src='/source.jpg'/></li>");
        assert(Base\Html::liImgOpen('source.jpg','OKé asddsa') === "<li><img alt='oke-asddsa' src='/source.jpg'/>");
        assert(Base\Html::divMany('what','ok',['james','#id']) === "<div>what</div><div>ok</div><div id='id'>james</div>");
        assert(Base\Html::divManyOpen('what','ok',['james','#id']) === "<div>what<div>ok<div id='id'>james");
        assert(Base\Html::divLiOp('ok',['james','#id']) === "<div><li class='ok'>");
        assert(Base\Html::aManyOpen('what','ok',['james','titlé','#id']) === "<a href='/what'><a href='/ok' hreflang='ok'><a href='/james' id='id'>titlé");
        assert(Base\Html::divOr('http://google.com','value') === "<a href='http://google.com' target='_blank'>value</a>");
        assert(Base\Html::divOr('sadas','value') === '<div>value</div>');
        assert(Base\Html::divOrOpen('/test.jpg','value') === "<a href='/test.jpg'>value");
        assert(Base\Html::divOrOpen('sadas','value') === '<div>value');
        assert(Base\Html::divOrClose('/test.jpg') === '</a>');
        assert(Base\Html::spanOrClose('sadas') === '</span>');
        assert(Base\Html::div() === '<div></div>');
        assert(Base\Html::divOpen() === '<div>');
        assert(Base\Html::divClose() === '</div>');
        assert(Base\Html::divCl() === '</div>');
        assert(Base\Html::divtableOpen('LOLÉ') === "<div class='table'><div class='table-row'><div class='table-cell'>LOLÉ");
        assert(Base\Html::divtableClose() === '</div></div></div>');
        assert(Base\Html::divtable('CENTERTHIS') === "<div class='table'><div class='table-row'><div class='table-cell'>CENTERTHIS</div></div></div>");
        assert(Base\Html::divele('OKÉ') === "<div class='element'>OKÉ</div>");
        assert(Base\Html::form('james.php',['method'=>'get'],['csrf'=>false,'genuine'=>false]) === "<form action='/james.php' method='get'></form>");
        assert(Base\Html::form('james.php',['method'=>'post'],['csrf'=>false,'genuine'=>false]) === "<form action='/james.php' method='post' enctype='multipart/form-data'></form>");
        assert(Base\Html::inputText('tést','namé') === "<input name='namé' type='text' maxlength='255' value='tést'/>");
        assert(Base\Html::inputEmail('tést',['name'=>'namé','placeholder'=>'jamés','maxlength'=>200]) === "<input name='namé' placeholder='jamés' maxlength='200' type='email' value='tést'/>");
        assert(Base\Html::inputEmail('tést',['name'=>'namé','placeholder'=>'jamés','maxlength'=>2000]) === "<input name='namé' placeholder='jamés' maxlength='2000' type='email' value='tést'/>");
        assert(Base\Html::textarea('tést','namé') === "<textarea name='namé'>tést</textarea>");
        assert(Base\Html::inputEmail('valuezé','namez') === "<input name='namez' type='email' maxlength='255' value='valuezé'/>");
        assert(Base\Html::inputHidden('ok','important') === "<input name='important' type='hidden' value='ok'/>");
        assert(Base\Html::inputSubmit('OK','submitIt') === "<input name='submitIt' type='submit' value='OK'/>");
        assert(Base\Html::inputImage('james','blabla') === "<input name='blabla' type='image' src='/james.jpg' alt='james'/>");
        assert(Base\Html::inputImage('james',['alt'=>'asdsa','name'=>'ok']) === "<input alt='asdsa' name='ok' type='image' src='/james.jpg'/>");
        assert(Base\Html::divSpanA('test.jpg') === "<div><span><a href='/test.jpg'></a></span></div>");
        assert(Base\Html::divSpanAnchorOpen('test.jpg') === "<div><span><a href='/test.jpg'>");
        assert(Base\Html::diveleDivSpanOr('bla','LOO') === "<div class='element'><div><span>LOO</span></div></div>");
        assert(Base\Html::diveleDivSpanOr('/bla','LOO') === "<div class='element'><div><a href='/bla'>LOO</a></div></div>");
        assert(Base\Html::ie8DiveleTable([['col1']],[[['data1']]],null,'#id') === "<!--[if lte IE 8><div class='element'><table id='id'><thead><tr><th>col1</th></tr></thead><tbody><tr><td>data1</td></tr></tbody></table></div><![endif]-->");
        assert(Base\Html::div('valeur','.james.james2.james.jamés#id') === "<div id='id' class='james james2'>valeur</div>");
        assert(Base\Html::inputRadio(1,'myname',['checked'=>1]) === "<input name='myname' type='radio' checked='checked' value='1'/>");
        assert(Base\Html::inputCheckbox(1,'myname',['checked'=>1]) === "<input name='myname[]' type='checkbox' checked='checked' value='1'/>");
        assert(Base\Html::inputCheckboxMany([1,'myname',['checked'=>1]],[2,'myname',['checked'=>1]]) === "<input name='myname[]' type='checkbox' checked='checked' value='1'/><input name='myname[]' type='checkbox' value='2'/>");
        assert(Base\Html::inputFile(1,'myname') === "<input name='myname' type='file' value='1'/>");
        assert(Base\Html::inputFile(1,['name'=>'myName','maxlength'=>200]) === "<input name='myName' type='file' value='1'/>");
        assert(Base\Html::select('<option>james</option>','ok') === "<select name='ok'><option>james</option></select>");
        assert(Base\Html::select([1=>'james',2=>'james2'],'my',['title'=>'','selected'=>2]) === "<select name='my'><option value=''></option><option value='1'>james</option><option value='2' selected='selected'>james2</option></select>");
        assert(Base\Html::option('value','nameé') === "<option value='nameé'>value</option>");
        assert(Base\Html::option('value') === "<option value='value'>value</option>");
        assert(Base\Html::inputText('val','name',['multi'=>true]) === "<input name='name[]' type='text' maxlength='255' value='val'/>");
        assert(Base\Html::label('james','ok') === "<label for='ok'>james</label>");
        assert(Base\Html::htmlOpen(null,['lang'=>'fr','data-route'=>'home']) === "<html lang='fr' data-route='home'>");
        assert(Base\Html::bodyOpen(null,'home') === "<body class='home'>");
        assert(Base\Html::script(function() { return 'YEAH'; }) === '<script>YEAH</script>');
        assert(Base\Html::span('strlen','james') === "<span class='james'>strlen</span>");
        assert(Base\Html::linkOpen('http://google.com/test.jpg','prev') === "<link rel='prev' href='http://google.com/test.jpg'/>");
        assert(Base\Html::linkOpen('/test/bla','stylesheet') === "<link rel='stylesheet' type='text/css' media='screen,print' href='/test/bla.css'/>");
        assert(Base\Html::linkOpen('/test/bla',true) === "<link rel='stylesheet' type='text/css' media='screen,print' href='/test/bla.css'/>");
        assert(Base\Html::linkOpen('//google.com/test/bla',['rel'=>true]) === "<link rel='stylesheet' type='text/css' media='screen,print' href='".Base\Request::scheme()."://google.com/test/bla'/>");
        assert(Base\Html::link('james','what') === "<link rel='what' href='/james'/>");
        assert(Base\Html::head(['title'=>'OK']) === '<head><title>OK</title></head>');
        assert(Base\Html::head('<title>OK</title><meta name="nothing">') === '<head><title>OK</title><meta name="nothing"></head>');
        assert(Base\Html::div('ok','.mon .ma #ok',['html'=>['span','LOL']]) === "<span class='LOL'><div id='ok' class='mon ma'>ok</div></span>");
        assert(Base\Html::inputText('ok','name') === "<input name='name' type='text' maxlength='255' value='ok'/>");
        assert(Base\Html::aImg('/test','/james.jpg') === "<a href='/test'><img alt='james' src='/james.jpg'/></a>");
        assert(Base\Html::aImgOpen('/test','/james.jpg') === "<a href='/test'><img alt='james' src='/james.jpg'/>");
        assert(Base\Html::divOp('ok') === "<div class='ok'>");
        assert(Base\Html::divCl() === '</div>');
        assert(Base\Html::inputEmail('ok','well') === "<input name='well' type='email' maxlength='255' value='ok'/>");
        assert(Base\Html::div(true) === '<div>&nbsp;</div>');
        assert(Base\Html::div('test','ok',['html'=>[['span','p'],'my-class']]) === "<span><p class='my-class'><div class='ok'>test</div></p></span>");
        assert(Base\Html::div('test','ok',['html'=>'span']) === "<span><div class='ok'>test</div></span>");
        assert(Base\Html::div('test','ok',['html'=>'%</li></ol>']) === "<div class='ok'>test</div></li></ol>");
        assert(Base\Html::div('test','ok',['html'=>'<ol><li>%']) === "<ol><li><div class='ok'>test</div>");
        assert(Base\Html::div('test','classe',['conditional'=>true]) === "<!--[if lte IE 8]><div class='classe'>test</div><![endif]-->");
        assert(Base\Html::iframe('test') === "<iframe srcdoc='test'></iframe>");
        assert(Base\Html::style('#id { ok: lol; }') === '<style>#id { ok: lol; }</style>');
        assert(Base\Html::div('test','my-class',['html'=>'sdasaddsa>!%!<span>']) === "<div class='my-class'>test</div>");
        assert(Base\Html::divLoop(['test','test2'],'field') === "<div class='field'>test</div><div class='field'>test2</div>");
        assert(Base\Html::divSpanLoop(['test','test2'],'field') === "<div><span class='field'>test</span></div><div><span class='field'>test2</span></div>");
        assert(strlen(Base\Html::select(0,'withTitle',['title'=>'JAMES','value'=>1])) === 148);
        assert(strlen(Base\Html::select(0,'withTitle',['title'=>'JAMES','checked'=>1])) === 128);
        assert(strlen(Base\Html::select(0,'withTitle',['title'=>'JAMES','selected'=>1])) === 148);
        assert(Base\Html::select(0,'myName') === "<select name='myName'><option value='0'>false</option><option value='1'>true</option></select>");
        assert(Base\Html::select(0,'withTitle',['title'=>'JAMES']) === "<select name='withTitle'><option value=''>JAMES</option><option value='0'>false</option><option value='1'>true</option></select>");
        assert(Base\Html::select(0,'test',['title'=>true]) === "<select name='test'><option value=''></option><option value='0'>false</option><option value='1'>true</option></select>");
        assert(strlen(Base\Html::select([1=>'james',2=>'Édouaard'],'my-select',['title'=>'test'])) === 132);
        assert(strlen(Base\Html::select([1=>'james',2=>'Édouaard'],'my-select',['title'=>true])) === 128);
        assert(strlen(Base\Html::select([1=>'james',2=>'Édouaard'],'my-select',['title'=>false])) === 102);
        assert(strlen(Base\Html::select([1=>'james',2=>'Édouaard'],'my-select',['title'=>null])) === 102);
        assert(strlen(Base\Html::select([1=>'james',2=>'Édouaard'],'my-select',['title'=>''])) === 128);
        $closure = function() {
            return 'a';
        };
        $closureAttr = function() {
            return 'b';
        };
        $closureArr = function() {
            return ['test'=>'ok','james'=>2];
        };
        assert(Base\Html::div($closure,['data-test'=>$closureAttr]) === "<div data-test='b'>a</div>");
        assert(strlen(Base\Html::div([Base\Str::class,'lower'],['data-test'=>[Base\Str::class,'lower']])) === 91);
        assert(Base\Html::div($closure,['data'=>$closureArr]) === "<div data-test='ok' data-james='2'>a</div>");
        assert(Base\Html::div($closure,$closureArr) === "<div test='ok' james='2'>a</div>");
        assert(Base\Html::divAnchorOpen('test') === "<div><a href='/test'>");
        assert(Base\Html::inputTel('5145090202') === "<input type='tel' value='5145090202'/>");
        assert(Base\Html::inputTel(5145090202) === "<input type='tel' value='5145090202'/>");
        assert(Base\Html::button([1,2,3]) === "<button type='button'>1, 2, 3</button>");
        assert(Base\Html::button(['test'=>'OKÉÉÉ', 'ble'=> 'MEH']) === "<button type='button'>OKÉÉÉ, MEH</button>");

        // get
        assert(Base\Html::get('a') === 'a');
        assert(Base\Html::get('A') === 'a');
        assert(Base\Html::get('anchor') === 'a');
        assert(Base\Html::get('ANCHOR') === 'anchor');
        assert(Base\Html::get(null) === null);
        assert(Base\Html::get(['b','anchor']) === 'a');

        // changeLast
        assert(Base\Html::changeLast('a','b') === ['a']);
        assert(Base\Html::changeLast('anchor',null) === ['a']);
        assert(Base\Html::changeLast('a',['div','span']) === ['div','a']);

        // arg
        assert(Base\Html::arg('valuer','div') === '<div>valuer</div>');
        assert(Base\Html::arg('valuer','divele') === "<div class='element'>valuer</div>");
        assert(Base\Html::arg('valuer',['div','#monId']) === "<div id='monId'>valuer</div>");
        assert(Base\Html::arg('valuer',[['div','span'],'#monId .james']) === "<div><span id='monId' class='james'>valuer</span></div>");

        // argOpen
        assert(Base\Html::argOpen('valuer','div') === '<div>valuer');
        assert(Base\Html::argOpen('valuer',[['div','span']]) === '<div><span>valuer');
        assert(Base\Html::argOpen('value','<div class="test">%<span>') === '<div class="test">value');
        assert(Base\Html::argOpen('test','<ol><li>!%SAD') === '');

        // argClose
        assert(Base\Html::argClose('div') === '</div>');
        assert(Base\Html::argClose(['div','.james']) === '</div>');
        assert(Base\Html::argClose([['div','span']]) === '</span></div>');
        assert(Base\Html::argClose('<div class="test">%<span>') === '<span>');
        assert(Base\Html::argClose('<ol><li>!%SAD') === '');

        // argStrExplode
        assert(Base\Html::argStrExplode('<div>%<span>') === ['<div>','<span>']);
        assert(Base\Html::argStrExplode('test%ok') === null);
        assert(Base\Html::argStrExplode('<div>ok%ok</div><li>%<span>') === ['<div>ok%ok</div><li>','<span>']);
        assert(Base\Html::argStrExplode('%<span>') === ['','<span>']);
        assert(Base\Html::argStrExplode('%span>') === null);
        assert(Base\Html::argStrExplode('<span>%') === ['<span>','']);

        // make
        assert(Base\Html::make(['div','span'],'testé','#id') === "<div><span id='id'>testé</span></div>");
        assert(Base\Html::make('div','testé') === '<div>testé</div>');
        assert(Base\Html::make('div','testé',['data-test'=>'JAMÉS','james','ok','#general']) === "<div data-test='JAMÉS' class='james ok' id='general'>testé</div>");
        assert(Base\Html::make('br','WHAT') === "<br data-value='WHAT'/>");
        assert(Base\Html::make('meta','testé','jamés') === "<meta name='jamés' content='testé'/>");
        assert(Base\Html::make('span',['jamés',2,3],'#ok') === "<span id='ok'>jamés, 2, 3</span>");
        assert(Base\Html::make(['divele','span'],'testé','#james') === "<div class='element'><span id='james'>testé</span></div>");
        assert(Base\Html::make('script',null,null,['src'=>'/path/jquery.js']) === "<script src='/path/jquery.js'></script>");
        assert(Base\Html::make('img','/testé-l.img') === "<img alt='teste-l' src='/test%C3%A9-l.img'/>");
        assert(Base\Html::make('a','/test/laa.php') === "<a href='/test/laa.php' class='selected'></a>");
        assert(Base\Html::make('form','/form/to',null,['csrf'=>false,'genuine'=>false]) === "<form action='/form/to' method='post' enctype='multipart/form-data'></form>");
        assert(Base\Html::make('A','/test/laa.php') === "<a href='/test/laa.php' class='selected'></a>");

        // makes
        assert(Base\Html::makes([['div','a'],'/test/laa.php'],[['span','b'],'test']) === "<div><a href='/test/laa.php' class='selected'></a></div><span><b>test</b></span>");
        assert(Base\Html::makes(['a','/test/laa.php'],['form','/form/to',null,['csrf'=>false,'genuine'=>false]]) === "<a href='/test/laa.php' class='selected'></a><form action='/form/to' method='post' enctype='multipart/form-data'></form>");
        assert(Base\Html::makes('div','span','b') === '<div></div><span></span><b></b>');

        // open
        assert(Base\Html::open(['div','a'],'/test/laa.php',true) === "<div><a href='/test/laa.php' class='selected'>/test/laa.php");
        assert(Base\Html::open('a','/test/laa.php',true) === "<a href='/test/laa.php' class='selected'>/test/laa.php");
        assert(Base\Html::open(['divele','a'],'/test/laa.php',true,'#james') === "<div class='element'><a href='/test/laa.php' id='james' class='selected'>/test/laa.php");

        // opens
        assert(Base\Html::opens(['a','/test/laa.php'],['img','jpg.jpg'],['div','bla']) === "<a href='/test/laa.php' class='selected'><img alt='jpg' src='/jpg.jpg'/><div>bla");
        assert(Base\Html::opens('div','span','b') === '<div><span><b>');

        // op
        assert(Base\Html::op('div','ok') === "<div class='ok'>");
        assert(Base\Html::op('a','text') === '<a>text');

        // ops
        assert(Base\Html::ops(['div','ok'],['span','#id']) === "<div class='ok'><span id='id'>");

        // close
        assert(Base\Html::close(['a','div']) === '</div></a>');
        assert(Base\Html::close('a') === '</a>');
        assert(Base\Html::close(['divele','a']) === '</a></div>');

        // closes
        assert(Base\Html::closes('a','br','img','div') === '</a></div>');
        assert(Base\Html::closes([['a','div']],[['span','b']]) === '</div></a></b></span>');

        // cl
        assert(Base\Html::cl(['a','div']) === '</div></a>');

        // cls
        assert(Base\Html::cls('a','br','img','div') === '</a></div>');

        // loop
        assert(Base\Html::loop('div',['test','test2'],'field') === "<div class='field'>test</div><div class='field'>test2</div>");
        assert(Base\Html::loop(['div','span'],['test','test2'],'field') === "<div><span class='field'>test</span></div><div><span class='field'>test2</span></div>");

        // many
        assert(Base\Html::many('img',null,'james.jpg','lala.ok',['lol.jpg','altok']) === "<img alt='james' src='/james.jpg'/><img alt='lala' src='/lala.ok'/><img alt='altok' src='/lol.jpg'/>");
        assert(Base\Html::many(['div','img'],null,'james.jpg','OK') === "<div><img alt='james' src='/james.jpg'/></div><div><img alt='ok' src='/OK.jpg'/></div>");
        assert(Base\Html::many('script',null,['jquery[lang]'],['//test']) === '<script>jquery[lang]</script><script>//test</script>'); // ok, valeur pas remplacé par shortcut
        assert(Base\Html::many('js',null,['jquery[lang]'],['//test']) === "<script src='/jqueryen.js'></script><script src='".Base\Request::scheme()."://test'></script>");

        // manyOpen
        assert(Base\Html::manyOpen('div',null,'james',['james2','#id'],'LOL') === "<div>james<div id='id'>james2<div>LOL");
        assert(Base\Html::manyOpen(['div','span'],null,'1','2','é') === '<div><span>1<div><span>2<div><span>é');

        // manyClose
        assert(Base\Html::manyClose('div',null,'james',['james2','#id'],'LOL') === '</div></div></div>');
        assert(Base\Html::manyClose('div',null,3) === '</div>');
        assert(Base\Html::manyClose(['div','span'],null,1,2) === '</span></div></span></div>');

        // cond
        assert(Base\Html::cond('div',false) === '');
        assert(Base\Html::cond('div',true) === '<div>&nbsp;</div>');
        assert(Base\Html::cond('div','') === '');
        assert(Base\Html::cond('div',null) === '');
        assert(Base\Html::cond('a','/test/laa.php') === "<a href='/test/laa.php' class='selected'></a>");
        assert(Base\Html::cond('a','') === '');
        assert(Base\Html::cond('a',null) === '');
        assert(Base\Html::cond('a',true) === "<a href='/1'></a>");
        assert(Base\Html::cond('a',false) === '');
        assert(Base\Html::cond('a',0) === "<a href='/0'></a>");
        assert(Base\Html::cond('a','0') === "<a href='/0'></a>");
        assert(Base\Html::cond(['div','span'],true,'#id') === "<div><span id='id'>&nbsp;</span></div>");
        assert(Base\Html::cond(['div','span'],false,'#id') === '');

        // condOpen
        assert(Base\Html::condOpen('a','bla',true) === "<a href='/bla'>bla");
        assert(Base\Html::condOpen('a','',true) === '');
        assert(Base\Html::condOpen(['div','span'],'jamés','classe') === "<div><span class='classe'>jamés");

        // condClose
        assert(Base\Html::condClose('a','') === '');
        assert(Base\Html::condClose('a','z') === '</a>');
        assert(Base\Html::condClose('br','z') === '');
        assert(Base\Html::condClose(['div','span'],'james') === '</span></div>');

        // or
        assert(Base\Html::or(['div','span'],'google.com','lol','#id') === "<div><span id='id'>lol</span></div>");
        assert(Base\Html::or(['div','span'],'http://google.com','lol','#id') === "<div><a href='http://google.com' id='id' target='_blank'>lol</a></div>");
        assert(Base\Html::or('div','http://google.com','lol','#id') === "<a href='http://google.com' id='id' target='_blank'>lol</a>");
        assert(Base\Html::or('div','google.com','lol','#id') === "<div id='id'>lol</div>");

        // orOpen
        assert(Base\Html::orOpen('div','http://google.com','lol','#id') === "<a href='http://google.com' id='id' target='_blank'>lol");
        assert(Base\Html::orOpen('div','google.com','lol','#id') === "<div id='id'>lol");
        assert(Base\Html::orOpen(['div','span'],'google.com','lol','#id') === "<div><span id='id'>lol");
        assert(Base\Html::orOpen(['div','span'],'http://google.com','lol','#id') === "<div><a href='http://google.com' id='id' target='_blank'>lol");

        // orClose
        assert(Base\Html::orClose('div','http://google.com') === '</a>');
        assert(Base\Html::orClose('div','google.com') === '</div>');
        assert(Base\Html::orClose(['div','span'],'google.com') === '</span></div>');
        assert(Base\Html::orClose(['div','span'],'http://google.com') === '</a></div>');

        // metaAttr

        // formAttr

        // formInitialNameAttr

        // inputAttr

        // textareaAttr

        // buttonAttr

        // selectAttr

        // optionAttr

        // imgAttr

        // value

        // metaValue

        // imgValue

        // scriptValue

        // formValue

        // selectValue

        // headValue

        // titleDescriptionKeywordsValue

        // titleValue
        assert(Base\Html::titleValue('éala') === 'Éala');
        assert(Base\Html::titleValue('bla') === 'Bla');
        assert(Base\Html::titleValue(['bla',' james','OKÉ ']) === 'Bla | James | OKÉ');
        assert(strlen(Base\Html::titleValue('blaasddsablaasddsablaasddsabsab sddsablaasd asddsabsab asddsabsab laasddsa adsdsa')) === 75);
        assert(Base\Html::titleValue(null) === null);
        assert(Base\Html::titleValue("Notre conseil d'administration") === "Notre conseil d'administration");

        // metaDescriptionValue
        assert(Base\Html::metaDescriptionValue(['bla','jamés','ok']) === 'Bla - Jamés - Ok');
        assert(Base\Html::metaDescriptionValue(null) === null);
        assert(Base\Html::metaDescriptionValue('<strong>OK mon ami</strong>') === 'OK mon ami');

        // metaKeywordsValue
        assert(Base\Html::metaKeywordsValue(['bla','ok']) === 'bla, ok');
        assert(Base\Html::metaKeywordsValue(null) === null);

        // metaUriValue
        assert(Base\Html::metaUriValue('/test.jpg') !== '/test.jpg');
        assert(Base\Html::metaUriValue('http://google.com') === 'http://google.com');
        assert(Base\Html::metaUriValue(null) === null);

        // div
        assert(Base\Html::div('ok') === '<div>ok</div>');

        // span
        assert(Base\Html::span('ok') === '<span>ok</span>');

        // start
        assert(Base\Html::start('br') === '<br/>');
        assert(Base\Html::start('div','jamés','ok') === "<div class='ok'>jamés");
        assert(Base\Html::start('divele','OK') === "<div class='element'>OK");
        assert(Base\Html::start('input','vale',['type'=>'email','name'=>'name']) === "<input type='email' name='name' maxlength='255' value='vale'/>");
        assert(Base\Html::start('div','ok','classe',['conditional'=>['lte',9,true]]) === "<!--[if lte IE 9]><!--><div class='classe'>ok");

        // end
        assert(Base\Html::end('br') === '');
        assert(Base\Html::end('div') === '</div>');
        assert(Base\Html::end('divtable') === '</div></div></div>');
        assert(Base\Html::end('input') === '');

        // metaOpen
        assert(Base\Html::metaOpen(['value','ok'],'description') === "<meta name='description' content='Value - Ok'/>");
        assert(Base\Html::metaOpen('LOL','og:description') === "<meta property='og:description' content='LOL'/>");
        assert(Base\Html::metaOpen(['LOL','oké'],'og:description') === "<meta property='og:description' content='LOL - Oké'/>");
        assert(Base\Html::metaOpen(['LOL','éé'],'og:title') === "<meta property='og:title' content='LOL | Éé'/>");
        assert(Base\Html::metaOpen('http://google.com/template/share.jpg','og:image') === "<meta property='og:image' content='http://google.com/template/share.jpg'/>");
        assert(Base\Html::metaOpen('IE=edge',['http-equiv'=>'X-UA-Compatible']) === "<meta http-equiv='X-UA-Compatible' content='IE=edge'/>");
        assert(Base\Html::meta(true,'charset') === "<meta name='charset' content='UTF-8'/>");

        // metaCharset
        assert(Base\Html::metaCharset() === "<meta name='charset' content='UTF-8'/>");

        // metaDescription
        assert(Base\Html::metaDescription(['bla','jamés','ok']) === "<meta name='description' content='Bla - Jamés - Ok'/>");

        // metaKeywords
        assert(Base\Html::metaKeywords(['bla','jamés','Ok']) === "<meta name='keywords' content='bla, jamés, Ok'/>");

        // metaOg
        assert(Base\Html::metaOg(['bla','jamés','Ok'],'title') === "<meta property='og:title' content='Bla | Jamés | Ok'/>");

        // cssOpen
        assert(Base\Html::cssOpen('james') === "<link rel='stylesheet' type='text/css' media='screen,print' href='/james.css'/>");
        assert(Base\Html::cssOpen('james',['media'=>'screen']) === "<link rel='stylesheet' media='screen' type='text/css' href='/james.css'/>");
        assert(Base\Html::cssOpen('/path/[lang]/james') === "<link rel='stylesheet' type='text/css' media='screen,print' href='/path/en/james.css'/>");
        assert(Base\Html::css('james',null,['html'=>'ie8']) === "<!--[if lte IE 8><link rel='stylesheet' type='text/css' media='screen,print' href='/james.css'/><![endif]-->");
        assert(Base\Html::css('test.css',null,['conditional'=>true]) === "<!--[if lte IE 8]><link rel='stylesheet' type='text/css' media='screen,print' href='/test.css'/><![endif]-->");

        // scriptOpen
        assert(Base\Html::scriptOpen(null,null,['src'=>'/path/jquery']) === "<script src='/path/jquery.js'>");
        assert(Base\Html::scriptOpen(null,null,['test'=>'OKÉÉÉ','src'=>'/path/jquery']) === "<script test='OKÉÉÉ' src='/path/jquery.js'>");
        assert(Base\Html::scriptOpen([1,2,3],'app') === '<script>app = [1,2,3];');
        assert(Base\Html::scriptOpen('app = [1,2,3];') === '<script>app = [1,2,3];');
        assert(Base\Html::script('test','app') === '<script>app = test;</script>');

        // jsOpen
        assert(Base\Html::jsOpen('//google.com/jquery') === "<script src='".Base\Request::scheme()."://google.com/jquery'>");
        assert(Base\Html::js('test.js',null,['conditional'=>true]) === "<!--[if lte IE 8]><script src='/test.js'></script><![endif]-->");
        assert(Base\Html::js('test.js',null,['conditional'=>['lte',9,true]]) === "<!--[if lte IE 9]><!--><script src='/test.js'></script><!--<![endif]-->");

        // jsClose
        assert(Base\Html::jsClose() === '</script>');

        // aOpen
        assert(Base\Html::aOpen('/test/laa.php','titlé') === "<a href='/test/laa.php' class='selected'>titlé");
        assert(Base\Html::aOpen('/test/laa.php',null) === "<a href='/test/laa.php' class='selected'>");
        assert(Base\Html::aOpen('/test/laa.php',true) === "<a href='/test/laa.php' class='selected'>/test/laa.php");
        assert(Base\Html::aOpen('/test/laa.php',true,true) === "<a href='/test/laa.php' target='_blank' class='selected'>/test/laa.php");
        assert(Base\Html::aOpen('http://google.com') === "<a href='http://google.com' target='_blank'>");
        assert(Base\Html::aOpen('http://google.com',null,['target'=>'parent']) === "<a href='http://google.com' target='parent'>");
        assert(Base\Html::aOpen('http://google.com',null,['target'=>false]) === "<a href='http://google.com'>");
        assert(Base\Html::aOpen('http://google.com',null,['group'=>'ajax','james']) === "<a rel='nofollow' class='ajax james' href='http://google.com' target='_blank'>");
        assert(Base\Html::aOpen('test','titlé') === "<a href='/test'>titlé");
        assert(Base\Html::a($res,true) === "<a href='".$mediaJpgUri."'>".$mediaJpgUri.'</a>');
        assert(Base\Html::aOpen($res,'well') === "<a href='".$mediaJpgUri."'>well");
        assert(Base\Html::aOpen($res) === "<a href='".$mediaJpgUri."'>");
        assert(Base\Html::aOpen('#','test') === "<a href='#'>test");
        assert(Base\Html::aOpen('https://google.com/in/what','test') === "<a href='https://google.com/in/what' target='_blank'>test");

        // imgOpen
        assert(Base\Html::imgOpen('james2.jpg','mon alt loé','#james') === "<img alt='mon-alt-loe' src='/james2.jpg' id='james'/>");
        assert(Base\Html::imgOpen('james2.jpg',true,'#james') === "<img alt='james2' src='/james2.jpg' id='james'/>");
        assert(Base\Html::imgOpen('james2.jpg','','#james') === "<img alt='james2' src='/james2.jpg' id='james'/>");
        assert(strlen(Base\Html::imgOpen($captcha)) > 2500);
        assert(strpos(Base\Html::imgOpen($captcha,'test'),"alt='test' src='data:image/png;base64,") !== false);
        assert(Base\Html::img($res) === "<img alt='jpg' src='".$mediaJpgUri."'/>");
        assert(Base\Html::img($res,'james') === "<img alt='james' src='".$mediaJpgUri."'/>");
        assert(strlen(Base\Html::imgOpen($res,null,null,['base64'=>true])) > 5000);
        assert(strlen(Base\Html::imgOpen('[assertMedia]/jpg.jpg',null,null,['base64'=>true])) > 5000);

        // aImgOpen
        assert(Base\Html::aImgOpen('/test/laa.php','lala.jpg','JAMES','#lavie') === "<a href='/test/laa.php' id='lavie' class='selected'><img alt='james' src='/lala.jpg'/>");
        assert(strlen(Base\Html::aImgOpen('/test',$captcha,'myAlt',['data-james'=>$captcha])) > 5000);

        // alt
        assert(Base\Html::alt('jamés','ok') === 'jamés');
        assert(Base\Html::alt('','james/james2.jpg') === 'james2');

        // img64
        assert(strlen(Base\Html::img64('[assertMedia]/jpg.jpg')) > 5000);
        assert(strlen(Base\Html::img64(Base\Request::schemeHost().$mediaJpgUri)) > 5000);
        assert(Base\Html::img64('https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png') === "<img alt='googlelogo-color-272x92dp' src='https://www.google.com/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png'/>");

        // tableOpen
        assert(strlen(Base\Html::tableOpen([['col1','col2']],[[['data1',['data2','#id']]],[['data11','data12']]],[['foot1','foot2']])) === 208);
        assert(strlen(Base\Html::table([['col1','col2']],[[['data1',['data2','#id']]],[['data11','data12']]],[['foot1','foot2'],'tableNow'])) === 233);

        // tableStrict
        assert(strlen(Base\Html::tableStrict([['col1']],[[['data1']],[['data2']]],[['foot1']])) === 151);
        assert(Base\Html::tableStrict([['col1','Col2']],[[['data1']]],[['foot1']]) === '');
        assert(Base\Html::tableStrict([['col1']],[[['data1'],['data2']]],[['foot1','foot2']]) === '');
        assert(strlen(Base\Html::tableStrict([['col1','col2']],[[['data1','data12']],[['data2','data22']]],[['foot1','foot2']])) === 208);

        // theadOpen
        assert(Base\Html::theadOpen(['what',['james','classe']]) === "<thead><tr><th>what</th><th class='classe'>james</th>");

        // theadClose
        assert(Base\Html::theadClose() === '</tr></thead>');

        // tbodyOpen
        assert(Base\Html::tbody([['data1','data2']],[['data3',['data4','classe']]]) === "<tbody><tr><td>data1</td><td>data2</td></tr><tr><td>data3</td><td class='classe'>data4</td></tr></tbody>");
        assert(Base\Html::tbody() === '<tbody></tbody>');

        // tbodyStrict
        assert(Base\Html::tbodyStrict([['col1','col2']],[['col3','col4']]) === '<tbody><tr><td>col1</td><td>col2</td></tr><tr><td>col3</td><td>col4</td></tr></tbody>');
        assert(Base\Html::tbodyStrict([['col1','col2']],[['col3','col4','col5']]) === '');

        // trOpen
        assert(Base\Html::trOpen(['what',['james','classe']]) === "<tr><td>what</td><td class='classe'>james</td>");

        // trThOpen
        assert(Base\Html::trThOpen(['what',['james','classe']]) === "<tr><th>what</th><th class='classe'>james</th>");

        // tfootOpen
        assert(Base\Html::tfootOpen(['what',['james','classe'],[]]) === "<tfoot><tr><td>what</td><td class='classe'>james</td><td></td>");

        // tfootClose
        assert(Base\Html::tfootClose() === '</tr></tfoot>');

        // tableSameCount
        assert(Base\Html::tableSameCount([[1,2,3]]));
        assert(!Base\Html::tableSameCount([[1,2,3]],[[1,3]]));
        assert(Base\Html::tableSameCount([[1,2,3]],[[1,2,3]]));

        // formOpen
        assert(Base\Html::formOpen('test.php',true,['genuine'=>false,'csrf'=>false]) === "<form action='/test.php' method='post' enctype='multipart/form-data'>");
        assert(Base\Html::formClose() === '</form>');
        assert(Base\Html::formOpen('test.php','get') === "<form action='/test.php' method='get'>");

        // inputOpen
        assert(Base\Html::inputOpen('date','oké') === "<input type='date' value='oké'/>");
        assert(Base\Html::inputOpen('datez','oké') === "<input type='text' maxlength='255' value='oké'/>");
        assert(Base\Html::inputOpen('email','oké',['name'=>'oké','maxlength'=>5]) === "<input name='oké' maxlength='5' type='email' value='oké'/>");
        assert(strlen(Base\Html::inputOpen('text','ok','james',['label'=>'Click this'])) === 128);
        assert(strlen(Base\Html::inputOpen('text','ok','james',['label'=>'Click this','after'=>true])) === 128);
        assert(Base\Str::isEnd('/label>',Base\Html::inputOpen('radio','ok','james',['label'=>'NOW'])));
        assert(Base\Html::inputOpen('checkbox',null) === "<input type='checkbox'/>");
        assert(Base\Html::inputOpen('text','ok',['required'=>true]) === "<input required='required' type='text' maxlength='255' value='ok'/>");
        assert(Base\Html::inputButton('ok','monnom') === "<input name='monnom' type='button' value='ok'/>");
        assert(strlen(Base\Html::inputText('',true)) === 65);
        assert(strlen(Base\Html::inputText(true,true)) === 66);
        assert(Base\Html::inputText(true,'ok') === "<input name='ok' type='text' maxlength='255' value='1'/>");
        assert(Base\Html::inputText('','ok') === "<input name='ok' type='text' maxlength='255' value=''/>");
        assert(strlen(Base\Html::inputSubmit(true,['name'=>true])) === 52);
        assert(strlen(Base\Html::inputSubmit('',['name'=>true])) === 51);
        assert(Base\Html::email('test','ok') === "<email class='ok'>test</email>");

        // buttonOpen
        assert(Base\Html::buttonOpen('test','ok') === "<button type='button' class='ok'>test");
        assert(Base\Html::button('test','ok') === "<button type='button' class='ok'>test</button>");
        assert(Base\Html::buttonOp('ok') === "<button type='button' class='ok'>");
        assert(Base\Html::buttonCl() === '</button>');

        // submitOpen
        assert(Base\Html::submitOpen('test','what') === "<button name='what' type='submit'>test");
        assert(strlen(Base\Html::submit('test',true)) === 55);
        assert(strlen(Base\Html::submit('test','name')) === 47);
        assert(strlen(Base\Html::submit('test',['name'=>true,'ok'])) === 66);

        // submitClose
        assert(Base\Html::submitClose() === '</button>');

        // inputMaxFilesize
        assert(Base\Html::inputMaxFilesize(100,'myFile') === "<input name='MAX_FILE_SIZE' type='hidden' value='100'/>");
        assert(Base\Html::inputMaxFilesize('30MB','myFile') === "<input name='MAX_FILE_SIZE' type='hidden' value='31457280'/>");

        // makeLabel
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID') === "<label for='ID'>james</label><textarea id='ID'></textarea>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID','before') === "<label for='ID'>james</label><textarea id='ID'></textarea>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID','after') === "<textarea id='ID'></textarea><label for='ID'>james</label>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID','wrap') === "<label for='ID'>james<textarea id='ID'></textarea></label>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID',1) === "<label for='ID'>james</label><textarea id='ID'></textarea>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",'ID',2) === "<textarea id='ID'></textarea><label for='ID'>james</label>");
        assert(Base\Html::makeLabel('james',"<textarea id='ID'></textarea>",null,0) === "<label>james<textarea id='ID'></textarea></label>");
        assert(Base\Html::makeLabel(null,"<textarea id='ID'></textarea>",null,0) === '');
        assert(Base\Html::makeLabel(['james','monspan'],"<textarea id='ID'></textarea>",'ID') === "<label for='ID'>james</label><span>monspan</span><textarea id='ID'></textarea>");
        assert(Base\Html::makeLabel(['james','monspan'],"<textarea id='ID'></textarea>",'ID',1) === "<label for='ID'>james</label><span>monspan</span><textarea id='ID'></textarea>");
        assert(Base\Html::makeLabel(['james','monspan'],"<textarea id='ID'></textarea>",'ID',2) === "<textarea id='ID'></textarea><label for='ID'>james</label><span>monspan</span>");
        assert(Base\Html::makeLabel(['james','monspan'],"<textarea id='ID'></textarea>",'ID',0) === "<label for='ID'>james<textarea id='ID'></textarea></label><span>monspan</span>");

        // formWrap
        assert(strlen(Base\Html::formWrap(['test'],['select','bla'])) === 39);
        assert(strlen(Base\Html::formWrap(['test'],['textarea','bla'])) === 76);
        assert(strlen(Base\Html::formWrap(['test'],['textarea','bla'],'br')) === 81);
        assert(strlen(Base\Html::formWrap(['test'],['inputText','bla'],'br')) === 105);
        assert(strlen(Base\Html::formWrap(['test'],['textarea','bla','myName'],'br')) === 107);
        assert(strlen(Base\Html::formWrap(['test'],['textarea','bla',['name'=>'myName']],'br')) === 107);
        assert(strlen(Base\Html::formWrap(['test'],['inputText','bla','myName'],'br')) === 131);
        assert(strlen(Base\Html::formWrap(['test'],['inputText','bla',['name'=>'myName']],'br')) === 131);
        assert(strlen(Base\Html::formWrap(['test'],['div','bla','myName'],'br')) === 86);
        assert(strlen(Base\Html::formWrap(['test'],['divInputEmail','bla','name'],'br')) === 137);
        assert(Base\Html::formWrap(['test'],['divInputEmail','bla','name'],'br',null,false) === "<label>test</label><br/><div><input name='name' type='email' maxlength='255' value='bla'/></div>");

        // formWrapStr
        assert(Base\Html::formWrapStr('LABEL','FORM','table',null,'forId') === "<table><tr><td><label for='forId'>LABEL</label></td><td>FORM</td></tr></table>");
        assert(Base\Html::formWrapStr('LABEL','FORM','table',null,null) === '<table><tr><td><label>LABEL</label></td><td>FORM</td></tr></table>');
        assert(Base\Html::formWrapStr('zx%form%zx','FORM','table',null,'forId') === "<table><tr><td><label for='forId'>zx%form%zx</label></td><td>FORM</td></tr></table>");

        // formWrapArray
        $array = ['label'=>'label','description'=>'description','type'=>'inputText','required'=>true];
        $wrap = "<div class='labelDescription'>%label%%description%</div>%form%";
        assert(strlen(Base\Html::formWrapArray('test',$array,$wrap)) === 239);
        $array = ['label'=>'label','type'=>'select','required'=>true,'choices'=>[1,'deux',3]];
        assert(strlen(Base\Html::formWrapArray(1,$array,$wrap)) === 255);
        $array = ['label'=>'label','description'=>'description','type'=>'checkbox','required'=>true,'choices'=>[1,'deux',3]];
        assert(strlen(Base\Html::formWrapArray(1,$array,$wrap,'myname')) === 613);
        assert(strlen(Base\Html::formWrapArray(1,$array,$wrap,'myname',null,null,['autoHidden'=>false])) === 576);

        // hidden
        assert(Base\Html::hidden([2,3,4],'name') === "<input name='name' type='hidden' value='2'/>");
        assert(Base\Html::hidden([2,3,4],'name',['multi'=>true]) === "<input name='name[]' type='hidden' value='2'/><input name='name[]' type='hidden' value='3'/><input name='name[]' type='hidden' value='4'/>");
        assert(Base\Html::hidden(2,'name') === "<input name='name' type='hidden' value='2'/>");

        // autoHidden

        // radio
        assert(strlen(Base\Html::radio([1=>'james',2=>'Édouaard'],'radioList')) === 242);

        // radios
        assert(strlen(Base\Html::radios([1=>'james',2=>'Édouaard'],'radioList',['value'=>2])) === 260);
        assert(strlen(Base\Html::radios([1=>'james',2=>'Édouaard'],'radioList',['checked'=>2])) === 260);
        assert(strlen(Base\Html::radios([1=>'james',2=>'Édouaard'],'radioList',['checked'=>2])) === 260);
        assert(strlen(Base\Html::radios([1=>'james',2=>'Édouaard'],'radioList',['checked'=>2,'autoHidden'=>true])) === 299);
        assert(strlen(Base\Html::radios(0,'bool',['checked'=>1,'position'=>1])) === 225);
        assert(strlen(Base\Html::radios(0,'bool',['value'=>1,'position'=>1])) === 225);
        assert(Base\Html::radios([],'radioTest',['autoHidden'=>true]) === "<input type='hidden' name='radioTest'/>");

        // radiosWithHidden
        assert(strlen(Base\Html::radiosWithHidden([1=>'james',2=>'Édouaard'],'my-name')) === 263);

        // checkbox
        assert(strlen(Base\Html::checkbox([1=>'james',2=>'Édouaard',3=>'MEH'],'radioList',['value'=>[1,2]])) === 410);

        // checkboxes
        assert(strlen(Base\Html::checkboxes([1=>'james',2=>'Édouaard',3=>'MEH'],'radioList',['value'=>'1,2'])) === 410);
        assert(strlen(Base\Html::checkboxes([1=>'james',2=>'Édouaard'],'radioList')) === 252);
        assert(strlen(Base\Html::checkboxes([1=>'james',2=>'Édouaard'],'radioList',['value'=>2])) === 270);
        assert(strlen(Base\Html::checkboxes([1=>'james',2=>'Édouaard'],'radioList',['checked'=>2])) === 270);
        assert(strlen(Base\Html::checkboxes([1=>'james',2=>'Édouaard'],'radioList',['checked'=>2,'autoHidden'=>true,'position'=>'after'])) === 309);
        assert(strlen(Base\Html::checkboxes(0,'bool',['value'=>1])) === 235);
        assert(strlen(Base\Html::checkboxes(0,'bool',['checked'=>1])) === 235);
        assert(strlen(Base\Html::checkboxes(0,'bool',['checked'=>1,'position'=>0])) === 235);
        assert(Base\Html::checkboxes([],'radioTest',['autoHidden'=>true]) === "<input type='hidden' name='radioTest'/>");

        // checkboxesWithHidden
        assert(strlen(Base\Html::checkboxesWithHidden([1=>'james',2=>'Édouaard'],'my-name')) === 273);

        // options
        assert(Base\Html::options([1=>'james',2=>'james2'],['selected'=>2]) === "<option value='1'>james</option><option value='2' selected='selected'>james2</option>");
        assert(Base\Html::options([1=>'james',2=>'james2'],['value'=>2]) === "<option value='1'>james</option><option value='2' selected='selected'>james2</option>");
        assert(Base\Html::options(0) === "<option value='0'>false</option><option value='1'>true</option>");

        // selectWithTitle
        assert(Base\Html::selectWithTitle('james',['test','ok'],'my-select') === "<select name='my-select'><option value=''>james</option><option value='0'>test</option><option value='1'>ok</option></select>");

        // multiselect
        assert(strlen(Base\Html::multiselect([1=>'james',2=>'james2'],'name',['value'=>2])) === 136);
        assert(strlen(Base\Html::multiselect([1=>'james',2=>'james2'],'name')) === 116);
        assert(Base\Html::multiselect([1=>'james',2=>'james2'],'name') === "<select multiple='multiple' name='name[]'><option value='1'>james</option><option value='2'>james2</option></select>");

        // captcha
        assert(strlen(Base\Html::captcha('abcde','[assertCommon]/ttf.ttf')) > 3000);

        // captchaFormWrap
        assert(strlen(Base\Html::captchaFormWrap('Clique ici','br',['abc','[assertCommon]/ttf.ttf'])) > 3000);
        assert(strlen(Base\Html::captchaFormWrap('Clique ici','table',['abc','[assertCommon]/ttf.ttf'])) > 3000);

        // csrf
        assert(Base\Html::csrf('bcde') === "<input name='-csrf-' data-csrf='1' type='hidden' value='bcde'/>");

        // genuine
        assert(Base\Html::genuine() === "<input name='-genuine-' data-genuine='1' type='text' maxlength='255'/>");

        // getGenuineName
        assert(Base\Html::getGenuineName() === '-genuine-');
        assert(Base\Html::getGenuineName(2) === '-genuine-2-');

        // wrap
        assert(Base\Html::wrap('divele','tést') === "<div class='element'>tést</div>");

        // wrapOpen
        assert(Base\Html::wrapOpen('divele') === "<div class='element'>");
        assert(Base\Html::wrapOpen('divelez') === '');
        assert(Base\Html::wrapOpen('divele','OKé') === "<div class='element'>OKé");

        // wrapClose
        assert(Base\Html::wrapClose('divele') === '</div>');

        // doctype
        assert(Base\Html::doctype() === '<!DOCTYPE html>');

        // conditionalComments
        assert(Base\Html::conditionalComments('test') === '<!--[if lte IE 8]>test<![endif]-->');
        assert(Base\Html::conditionalComments('test','lte',9,true) === '<!--[if lte IE 9]><!-->test<!--<![endif]-->');

        // conditionalCommentsOpen
        assert(Base\Html::conditionalCommentsOpen() === '<!--[if lte IE 8]>');

        // conditionalCommentsClose
        assert(Base\Html::conditionalCommentsClose() === '<![endif]-->');

        $head = [
            'title'=>'<OKÉz>',
            'meta'=>[
                'charset'=>true,
                'description'=>['jaems','deux'],
                [['jaems','deux'],'keywords'],
                'og:title'=>function() { return '<OKÉz>'; },
                [['jaems','deux'],'og:description'],
                'og:url'=>'http://google.com/test.jpg',
                'viewport'=>'width=device-width, initial-scale=1',
                'fb:app_id'=>function() { return 12345; },
                ['http://google.com/test.jpg','og:image']],
            'link'=>[
                ['http://google.com/test.jpg','prev'],
                ['/test.css','stylesheet'],
                ['test2','stylesheet'],
                'test3'],
            'script'=>[
                'james: test;',
                function() { return 'YEAH'; },
                [[1,2,3],'app']],
            'css'=>[
                'james',
                'lang_[lang]'],
            'js'=>[
                'jquery',
                'lang_[lang]'
            ]
        ];

        // docOpen
        assert(Base\Html::docOpen(['body'=>'well','html'=>'ok'],false) === "<html class='ok'>\n<body class='well'>");
        assert(strlen(Base\Html::docOpen(null)) === 83);
        assert(Base\Html::docOpen(null,false) === '');
        assert(strlen(Base\Html::docOpen([
            'doctype'=>true,
            'html'=>['lang'=>'fr','data-route'=>'home'],
            'head'=>$head,
            'body'=>['homePage'],
        ])) === 1130);
        assert(strlen(Base\Html::docOpen([
            'doctype'=>true,
            'body'=>true
        ],false)) === 22);
        assert(strlen(Base\Html::docOpen([
            'doctype'=>true,
            'body'=>['test']
        ])) === 96);

        // headFromArray
        assert(strlen(Base\Html::headFromArray($head)) === 1040);
        assert(Base\Html::headFromArray(['js'=>'test.js','title'=>'OK']) === "<title>OK</title>\n<script src='/test.js'></script>");
        assert(Base\Html::headFromArray(['js'=>['test'=>['test.js',null]]]) === "<script src='/test.js'></script>");
        assert(Base\Html::headFromArray(['js'=>'test.js']) === "<script src='/test.js'></script>");
        assert(Base\Html::headFromArray(['meta'=>['description'=>'ok','keywords'=>null]]) === "<meta name='description' content='Ok'/>");
        assert(Base\Html::headFromArray(['js'=>[['test.js',null,['conditional'=>true]]]]) === "<!--[if lte IE 8]><script src='/test.js'></script><![endif]-->");
        assert(Base\Html::headFromArray(['js'=>['test'=>['test.js',null,['conditional'=>true]]]]) === "<!--[if lte IE 8]><script src='/test.js'></script><![endif]-->");

        // headArgReformat

        // docClose
        assert(Base\Html::docClose(['html'=>true,'body'=>true],false,false) === "</body>\n</html>");
        assert(Base\Html::docClose(null,true,false) === "</body>\n</html>");
        assert(strlen(Base\Html::docClose(null,true,false)) === 15);
        assert(Base\Html::docClose(null,false,false) === '');
        assert(strlen(Base\Html::docClose([
            'script'=>[
                'james: test;',
                function() { return 'YEAH'; },
                [[1,2,3],'app']],
            'js'=>[
                'jquery',
                'lang_[lang]'],
            'body'=>true,
            'html'=>true
        ],false,false)) === 170);

        // docSimple
        assert(Base\Html::docSimple('test','ok') === '<!DOCTYPE html><html><head><title>test</title></head><body>ok</body></html>');
        assert(Base\Html::docSimple('test','ok',['html'=>"data-ok='1'",'head'=>"<link rel='stylesheet' type='text/css' href='/css/app.css'/>"]) === "<!DOCTYPE html><html data-ok='1'><head><title>test</title><link rel='stylesheet' type='text/css' href='/css/app.css'/></head><body>ok</body></html>");

        // excerpt
        assert(Base\Html::excerpt(30,"la👦🏼👦👦 vie ést <b>belle</b> l'article\"deux lorem ipsuma ") === "la vie ést belle lorem ipsu<span class='excerptSuffix'>...</span>");
        assert(Base\Html::excerpt(30,"la👦🏼👦👦 vie ést <b>belle</b> l'article\"deux lorem ipsum, ") === "la vie ést belle lorem ipsu<span class='excerptSuffix'>...</span>");
        assert(Base\Html::excerpt(30,"la👦🏼👦👦    vie ést <b>belle</b> l'article\"deux lorem ipsum, ") === "la vie ést belle lorem ipsu<span class='excerptSuffix'>...</span>");
        assert(Base\Html::excerpt(20,"Centre d'hébergement Cécile-Godin ok la vioe ") === "Centre d&apos;hébergem<span class='excerptSuffix'>...</span>");
        assert(Base\Html::excerpt(10,'emo.ndpph@gmail.com') === "emo.ndp<span class='excerptSuffix'>...</span>");
        assert(strlen(Base\Html::excerpt(75,'Accueillir un stagiaire – des avantages à découvrir | Intranet du wwwwwww')) === 77);

        // excerptEntities
        assert(Base\Html::excerptEntities(30,"la👦🏼👦👦 vie ést <b>belle</b> l'article\"deux lorem ipsuma ") === "la vie &eacute;st belle lorem ipsu<span class='excerptSuffix'>...</span>");

        // excerptStrSuffix
        assert(Base\Html::excerptStrSuffix(30,"la👦🏼👦👦 vie ést <b>belle</b> l'article\"deux lorem ipsuma ") === 'la vie ést belle lorem ipsu...');

        // getExcerptSuffix
        assert(Base\Html::getExcerptSuffix() === "<span class='excerptSuffix'>...</span>");

        // output
        assert(Base\Html::output("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &apos; &quot; vié &lt;script&gt;&lt;/script&gt;');
        assert(Base\Html::output('tést     test') === 'tést     test');

        // outputEntities
        assert(Base\Html::outputEntities("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &#039; &quot; vi&eacute; &lt;script&gt;&lt;/script&gt;');

        // outputStripTags
        assert(Base\Html::outputStripTags("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &apos; &quot; vié');

        // unicode
        assert(Base\Html::unicode("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &apos; &quot; vié &lt;script&gt;&lt;/script&gt;👦🏼👦👦🏼');

        // unicodeEntities
        assert(Base\Html::unicodeEntities("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &#039; &quot; vi&eacute; &lt;script&gt;&lt;/script&gt;👦🏼👦👦🏼');

        // unicodeStripTags
        assert(Base\Html::unicodeStripTags("la ' \"\n vié <script></script>👦🏼👦👦🏼") === 'la &apos; &quot; vié 👦🏼👦👦🏼');

        // getUriOption
        $x = $x = Base\Html::getUriOption('a');
        assert(is_array($x));

        // setUriOption
        Base\Html::setUriOption('a',$x);

        // cleanup
        Base\Attr::removeSelectedUri('/test/laa.php');
        Base\Attr::setOption('uri/append',$uriAppend);
        Base\Style::setOption('uri/append',$styleAppend);

        return true;
    }
}
?>