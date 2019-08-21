<?php
declare(strict_types=1);
namespace Quid\Base\Test;
use Quid\Base;

// csv
class Csv extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$currentFile = Base\Finder::path("[assertCommon]/class.php");
		$mediaCsv = "[assertMedia]/csv.csv";
		$storage = "[assertCurrent]";
		$csvRes = Base\Csv::open($mediaCsv);
		$fileRes = Base\File::open($currentFile);
		$temp = Base\File::prefix("[assertCurrent]");
		$res = Base\File::resource($temp);
		$_file_ = Base\Finder::shortcut("[assertCommon]/class.php");
		$_dir_ = \dirname($_file_);
		\assert(Base\Dir::reset($storage));
		\assert(Base\File::set("[assertCurrent]/test.php","WHAT"));
		\assert(Base\Csv::open($currentFile) === null);
		
		// getFormat
		\assert(\count(Base\Csv::getFormat()) === 3);

		// same
		$get = Base\Csv::getLines($csvRes);
		\assert(Base\Csv::same($get));
		\assert(!Base\Csv::same([[1],[2,3]]));
		\assert(\count($get) === 15);
		
		// clean
		\assert(\count(Base\Csv::clean($get,true)) === 10);
		
		// assoc
		$assoc = Base\Csv::assoc($get);
		\assert($assoc[0]['Item Code'] === '15v3-01');
		$splice = Base\Arr::splice(1,10,$assoc);
		
		// list
		\assert(Base\Csv::list($assoc) === $get);
		
		// str
		\assert(Base\Csv::str("test;test2é;\"test3\ntest4\";\"testqu;ote\"") === ["test","test2é","test3\ntest4","testqu;ote"]);
		\assert(Base\Csv::str("") === null);
		\assert(\count(Base\Csv::str(["test;test2é;\"test3\ntest4\";\"testqu;ote\"","","test;test2é;\"test3\ntest4\";\"testqu;ote\""])) === 2);
		
		// put
		\assert(\strlen(Base\Csv::put($splice[0])) === 83);
		\assert(\strlen(Base\Csv::put($splice)) > 100);
		
		// prepareContent
		\assert(Base\Csv::prepareContent('ok') === [['ok']]);
		\assert(Base\Csv::prepareContent(['ok','what']) === [['ok','what']]);
		$line = [['ok','what']];
		
		// prepareContentPrepend
		\assert(Base\Csv::prepareContentPrepend($line,$csvRes)[0] === ['ok','what']);
		\assert(Base\Res::seekRewind($csvRes));
		
		// resLine
		\assert(\is_array(Base\Csv::resLine($csvRes)));
		
		// resWrite
		\assert(Base\Csv::resWrite($line,$res) === false);
		
		// file
		\assert(Base\Csv::is($csvRes));
		\assert(Base\Csv::isResource($csvRes));
		\assert(!Base\Csv::isResource($fileRes));
		\assert(!Base\Csv::isReadable($currentFile));
		\assert(!Base\Csv::isReadable($_dir_));
		\assert(Base\Csv::isReadable($mediaCsv));
		\assert(Base\Csv::isReadable($csvRes));
		\assert(!Base\Csv::isWritable($currentFile));
		\assert(!Base\Csv::isWritable($_dir_));
		\assert(Base\Csv::isWritable($mediaCsv));
		\assert(!Base\Csv::isEmpty($currentFile));
		\assert(!Base\Csv::isEmpty($_dir_));
		\assert(!Base\Csv::isEmpty($mediaCsv));
		\assert(!Base\Csv::isEmpty($csvRes));
		\assert(!Base\Csv::isNotEmpty($currentFile));
		\assert(!Base\Csv::isNotEmpty($_dir_));
		\assert(Base\Csv::isNotEmpty($mediaCsv));
		\assert(Base\Csv::isNotEmpty($csvRes));
		\assert(Base\Csv::option(['test'=>'deux']) === ['csv'=>true,'test'=>'deux','useIncludePath'=>true]);
		\assert(Base\Csv::stat($_dir_) === null);
		\assert(\is_array(Base\Csv::stat($csvRes)));
		\assert(Base\Csv::stat($fileRes) === null);
		\assert(Base\Csv::stat($currentFile) === null);
		\assert(Base\Csv::mime($currentFile) === null);
		\assert(Base\Csv::info($currentFile) === null);
		\assert(Base\Csv::mime($mediaCsv) === 'text/csv');
		\assert(\count(Base\Csv::info($mediaCsv)) === 13);
		\assert(Base\Path::extension(Base\Csv::prefix()) === 'csv');
		\assert(Base\Csv::getLoadPath("view/test.php") === '');
		\assert(Base\Csv::getLoadPath("view/test.csv") === "view/test.csv");
		\assert(!empty(Base\Csv::path("[assertCurrent]/test.csv")));
		\assert(Base\Csv::path("[assertCurrent]/test.php") === null);
		\assert(\count(Base\Csv::lines(0,3,$mediaCsv)) === 3);
		\assert(Base\Csv::getLines($mediaCsv,0,3) === Base\Csv::lines(0,3,$csvRes));
		\assert(\count(Base\Csv::lineFirst($mediaCsv)) === 12);
		\assert(\count(Base\Csv::lineLast($mediaCsv)) === 12);
		\assert(\is_string(Base\Csv::get($mediaCsv)));
		\assert(\strlen(Base\Csv::read(0,200,$mediaCsv)) === 200);
		\assert(Base\Csv::path($mediaCsv) === Base\Csv::shortcut($mediaCsv));
		\assert(Base\Csv::path($mediaCsv.".php") === null);
		\assert(\is_array(Base\Csv::line($csvRes)) && Base\Csv::line($csvRes) !== Base\Csv::line($csvRes));
		\assert(Base\Csv::lineCount($mediaCsv) < Base\File::lineCount($mediaCsv));
		\assert(Base\Csv::subCount("King",$csvRes) === Base\File::subCount("King",$csvRes));
		\assert(\count(Base\Csv::lineChunk(10,$csvRes)) === 2);
		\assert(Base\Csv::set("[assertCurrent]/test.php") === null);
		\assert(Base\Csv::set("[assertCurrent]/test.csv",[['WHAT','ok'],['TE" ST','LOL']]));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 22);
		\assert(Base\File::set("[assertCurrent]/test.txt",[['WHAT','ok'],['TE" ST','LOL']]));
		\assert(\strlen(Base\File::get("[assertCurrent]/test.txt")) === 33);
		\assert(Base\Path::isExtension("csv",Base\Csv::setFilenameExtension("[assertCurrent]","filenameext")));
		\assert(Base\Csv::write(['WRITE','OKzzzdasds'],"[assertCurrent]/test.csv"));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 22);
		\assert(Base\Csv::overwrite([['WRITEwqewe','OKzzzdasds'],['LOL','OUI']],"[assertCurrent]/test.csv"));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 30);
		\assert(Base\Csv::prepend(['pré','deux'],"[assertCurrent]/test.csv"));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 40);
		\assert(Base\Csv::append(['appé','troi'],"[assertCurrent]/test.csv"));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 51);
		\assert(Base\Csv::appendNewline(['appé','troi'],"[assertCurrent]/test.csv"));
		\assert(\strlen(Base\Csv::get("[assertCurrent]/test.csv")) === 62);
		\assert(Base\Csv::lineSplice(0,1,"[assertCurrent]/test.csv",['testzzz','test2zzz'])[0] === ['testzzz','test2zzz']);
		\assert(Base\Csv::lineSplice(1,3,"[assertCurrent]/test.csv",[['test','test2'],['test3','test4']],true)[1] === ['test','test2']);
		\assert(Base\Csv::getLines("[assertCurrent]/test.csv")[1] === ['test','test2']);
		\assert(\count(Base\Csv::lineSpliceFirst("[assertCurrent]/test.csv",null,true)) === 3);
		\assert(\count(Base\Csv::lineSpliceLast("[assertCurrent]/test.csv",null,true)) === 2);
		\assert(\count(Base\Csv::getLines("[assertCurrent]/test.csv")) === 2);
		\assert(Base\Csv::lineInsert(0,[['what',2],['ok',['TEST']]],"[assertCurrent]/test.csv",false)[1][1] === ['TEST']);
		\assert(\count(Base\Csv::lineFilter(function($v,$k,$lines) {
			\assert(\is_array($v));
			return true;
		},"[assertCurrent]/test.csv")) === 2);
		\assert(Base\Csv::lineMap(function($v,$k,$lines) {
			$v[2] = 'TEST';
			return $v;
		},"[assertCurrent]/test.csv")[0] === ['test','test2','TEST']);
		\assert(Base\Csv::changeBasename("test2.csv","[assertCurrent]/test.csv"));
		\assert(Base\Csv::empty("[assertCurrent]/test2.csv"));
		\assert(!Base\Csv::is($currentFile));
		\assert(!Base\Csv::is($_dir_));
		\assert(Base\Csv::is($mediaCsv));
		\assert(Base\Csv::is($csvRes));
		
		return true;
	}
}
?>