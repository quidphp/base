<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// style
class Style extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$mediaJpg = "[assertMedia]/jpg.jpg";
		$mediaJpgUri = Base\Uri::relative($mediaJpg);
		$styleAppend = Base\Style::getOption('uri/append');
		Base\Style::setOption('uri/append',false);

		// parse
		$array = array('background-image'=>'url(/test.jpg)','padding-left'=>'10px','margin'=>'10px','left'=>'20.4%','border'=>'20px');
		assert(Base\Style::parse(array('bgimg'=>'test'),Base\Style::option(array('uri'=>array('append'=>array('v'=>200))))) === array('background-image'=>'url(/test.jpg?v=200)'));
		assert(Base\Style::parse(array('background-image'=>'test.jpg','padding-left'=>5,'padding-left'=>10,'margin'=>'10px','left'=>'5%','left'=>20.4,'border'=>20),Base\Style::option()) === $array);
		assert(Base\Style::parse(array('bgimg'=>'/test.jpg'),Base\Style::option()) === array('background-image'=>'url(/test.jpg)'));

		// parseUri
		assert(Base\Style::parseUri("test",Base\Style::option()) === '/test.jpg');

		// prepareStr
		assert(Base\Style::prepareStr("padding: 10px; color   :#000;",Base\Style::option()) === array('padding'=>' 10px',' color   '=>'#000'));
		assert(Base\Style::prepareStr("test.jpg",Base\Style::option()) === array('background-image'=>'test.jpg'));

		// explodeStr
		assert(Base\Style::explodeStr("padding: 10px; color   :#000;",Base\Style::option()) === array('padding'=>' 10px',' color   '=>'#000'));

		// getUriOption
		assert(is_array(Base\Style::getUriOption()));

		// setUriOption
		Base\Style::setUriOption(Base\Style::getUriOption());

		// other
		assert(Base\Style::arr(array('color: #000','padding: 10px')) === array('color'=>'#000','padding'=>'10px'));
		assert(Base\Style::str(array('color'=>null,'bla'=>false,'ok'=>true)) === "");
		assert(Base\Style::append("test.jpg",array('padding'=>10),array('color'=>'#fff','padding'=>12)) === array('background-image'=>'url(/test.jpg)','padding'=>'12px','color'=>'#fff'));
		assert(Base\Style::arr("test.jpg") === array('background-image'=>'url(/test.jpg)'));
		assert(Base\Style::arr("color:#fff; COLOR: #000;\n\r PADDING: 10px; width: 10; height: 20px;") === array('color'=>'#fff','COLOR'=>'#000','PADDING'=>'10px','width'=>'10','height'=>'20px'));
		assert(Base\Style::arr("padding: 10px;") === array('padding'=>'10px'));
		assert(Base\Style::arr(array('margin'=>10,'color'=>'#fff'),array('sort'=>true)) === array('color'=>'#fff','margin'=>'10px'));
		assert(Base\Style::arr(array('color'=>'#fff')) === array('color'=>'#fff'));
		assert(Base\Style::arr(array('color'=>'#fff','COLOR'=>'#000')) === array('color'=>'#fff','COLOR'=>'#000'));
		assert(Base\Style::arr(array('color'=>'#fff','COLOR'=>'#000')) === array('color'=>'#fff','COLOR'=>'#000'));
		assert(Base\Style::arr(array('padding'=>10,'PADDING'=>12)) === array('padding'=>'10px','PADDING'=>'12'));
		$string = "color:#000;padding:5px;";
		assert(Base\Style::count($string) === 2);
		assert(Base\Style::append($string,"padding:10px") === array('color'=>'#000','padding'=>'10px'));
		assert(Base\Style::slice("color",true,$string));
		assert(Base\Style::splice("color",true,$string,array('margin'=>10,'background-image'=>'test.jpg')) === array('margin'=>'10px','background-image'=>'url(/test.jpg)','padding'=>'5px'));
		assert(Base\Style::keysStart("col",$string) === array('color'=>'#000'));
		assert(Base\Style::implode(array('color'=>'#fff','padding'=>'10px','james'=>'10px')) === 'color: #fff; padding: 10px; james: 10px;');
		assert(Base\Style::implode(array('color'=>'#fff','COLOR'=>'#000','padding'=>'10px','james'=>'10px'),array('caseImplode'=>null)) === 'color: #fff; COLOR: #000; padding: 10px; james: 10px;');
		assert(Base\Style::str(array('color'=>'#fff','padding'=>'10px','james'=>'10px')) === 'color: #fff; padding: 10px; james: 10px;');
		assert(Base\Style::str(array('color'=>'#fff','COLOR'=>'#000','padding'=>'10px','james'=>'10px')) === 'color: #fff; COLOR: #000; padding: 10px; james: 10px;');
		assert(Base\Style::str("color:#fff; COLOR: #000;\n\r PADDING: 10px; width: 10; height: 20px;") === "color: #fff; COLOR: #000; PADDING: 10px; width: 10; height: 20px;");
		assert(Base\Style::list(array('padding'=>10,'background-image'=>'test.jpg'))[0] === 'padding: 10px');
		assert(Base\Style::implode(Base\Style::list(array('padding'=>10,'background-image'=>'test.jpg'))) === "padding: 10px; background-image: url(/test.jpg);");
		assert(Base\Style::str(array('bgimg'=>$mediaJpg),array('uri'=>array('exists'=>true))) === "background-image: url(".$mediaJpgUri.");");

		// cleanup
		Base\Style::setOption('uri/append',$styleAppend);
		
		return true;
	}
}
?>