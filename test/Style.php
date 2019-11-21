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

// style
// class for testing Quid\Base\Style
class Style extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaJpgUri = Base\Uri::relative($mediaJpg);
        $styleAppend = Base\Style::getOption('uri/append');
        Base\Style::setOption('uri/append',false);

        // parse
        $array = ['background-image'=>'url(/test.jpg)','padding-left'=>'10px','margin'=>'10px','left'=>'20.4%','border'=>'20px'];
        assert(Base\Style::parse(['bgimg'=>'test'],Base\Style::option(['uri'=>['append'=>['v'=>200]]])) === ['background-image'=>'url(/test.jpg?v=200)']);
        assert(Base\Style::parse(['background-image'=>'test.jpg','padding-left'=>5,'padding-left'=>10,'margin'=>'10px','left'=>'5%','left'=>20.4,'border'=>20],Base\Style::option()) === $array);
        assert(Base\Style::parse(['bgimg'=>'/test.jpg'],Base\Style::option()) === ['background-image'=>'url(/test.jpg)']);

        // parseUri
        assert(Base\Style::parseUri('test',Base\Style::option()) === '/test.jpg');

        // prepareStr
        assert(Base\Style::prepareStr('padding: 10px; color   :#000;',Base\Style::option()) === ['padding'=>' 10px',' color   '=>'#000']);
        assert(Base\Style::prepareStr('test.jpg',Base\Style::option()) === ['background-image'=>'test.jpg']);

        // explodeStr
        assert(Base\Style::explodeStr('padding: 10px; color   :#000;',Base\Style::option()) === ['padding'=>' 10px',' color   '=>'#000']);

        // getUriOption
        assert(is_array(Base\Style::getUriOption()));

        // setUriOption
        Base\Style::setUriOption(Base\Style::getUriOption());

        // other
        assert(Base\Style::str(['bgimg'=>'[media]/test.jpg']) === 'background-image: url(/media/test.jpg);');
        assert(Base\Style::str(['background-image'=>'[media]/test.jpg']) === 'background-image: url(/media/test.jpg);');
        assert(Base\Style::arr(['color: #000','padding: 10px']) === ['color'=>'#000','padding'=>'10px']);
        assert(Base\Style::str(['color'=>null,'bla'=>false,'ok'=>true]) === '');
        assert(Base\Style::append('test.jpg',['padding'=>10],['color'=>'#fff','padding'=>12]) === ['background-image'=>'url(/test.jpg)','padding'=>'12px','color'=>'#fff']);
        assert(Base\Style::arr('test.jpg') === ['background-image'=>'url(/test.jpg)']);
        assert(Base\Style::arr("color:#fff; COLOR: #000;\n\r PADDING: 10px; width: 10; height: 20px;") === ['color'=>'#fff','COLOR'=>'#000','PADDING'=>'10px','width'=>'10','height'=>'20px']);
        assert(Base\Style::arr('padding: 10px;') === ['padding'=>'10px']);
        assert(Base\Style::arr(['margin'=>10,'color'=>'#fff'],['sort'=>true]) === ['color'=>'#fff','margin'=>'10px']);
        assert(Base\Style::arr(['color'=>'#fff']) === ['color'=>'#fff']);
        assert(Base\Style::arr(['color'=>'#fff','COLOR'=>'#000']) === ['color'=>'#fff','COLOR'=>'#000']);
        assert(Base\Style::arr(['color'=>'#fff','COLOR'=>'#000']) === ['color'=>'#fff','COLOR'=>'#000']);
        assert(Base\Style::arr(['padding'=>10,'PADDING'=>12]) === ['padding'=>'10px','PADDING'=>'12']);
        $string = 'color:#000;padding:5px;';
        assert(Base\Style::count($string) === 2);
        assert(Base\Style::append($string,'padding:10px') === ['color'=>'#000','padding'=>'10px']);
        assert(Base\Style::slice('color',true,$string));
        assert(Base\Style::splice('color',true,$string,['margin'=>10,'background-image'=>'test.jpg']) === ['margin'=>'10px','background-image'=>'url(/test.jpg)','padding'=>'5px']);
        assert(Base\Style::keysStart('col',$string) === ['color'=>'#000']);
        assert(Base\Style::implode(['color'=>'#fff','padding'=>'10px','james'=>'10px']) === 'color: #fff; padding: 10px; james: 10px;');
        assert(Base\Style::implode(['color'=>'#fff','COLOR'=>'#000','padding'=>'10px','james'=>'10px'],['caseImplode'=>null]) === 'color: #fff; COLOR: #000; padding: 10px; james: 10px;');
        assert(Base\Style::str(['color'=>'#fff','padding'=>'10px','james'=>'10px']) === 'color: #fff; padding: 10px; james: 10px;');
        assert(Base\Style::str(['color'=>'#fff','COLOR'=>'#000','padding'=>'10px','james'=>'10px']) === 'color: #fff; COLOR: #000; padding: 10px; james: 10px;');
        assert(Base\Style::str("color:#fff; COLOR: #000;\n\r PADDING: 10px; width: 10; height: 20px;") === 'color: #fff; COLOR: #000; PADDING: 10px; width: 10; height: 20px;');
        assert(Base\Style::list(['padding'=>10,'background-image'=>'test.jpg'])[0] === 'padding: 10px');
        assert(Base\Style::implode(Base\Style::list(['padding'=>10,'background-image'=>'test.jpg'])) === 'padding: 10px; background-image: url(/test.jpg);');
        assert(Base\Style::str(['bgimg'=>$mediaJpg],['uri'=>['exists'=>true]]) === 'background-image: url('.$mediaJpgUri.');');

        // cleanup
        Base\Style::setOption('uri/append',$styleAppend);

        return true;
    }
}
?>