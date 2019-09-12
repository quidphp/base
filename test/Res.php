<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// res
// class for testing Quid\Base\Res
class Res extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $storagePath = Base\Finder::normalize('[storage]');
        $publicPath = Base\Finder::normalize('[public]');
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');
        $_dir_ = dirname($_file_);
        $public = $publicPath.'/media/base_res';
        $mediaJpg = '[assertMedia]/jpg.jpg';
        $mediaJpgUri = Base\Uri::relative($mediaJpg);
        $mediaCsv = '[assertMedia]/csv.csv';
        $mediaHash = '[assertMedia]/hash#.php';
        $mediaPhp = '[assertMedia]/php.php';
        $storage = '[assertCurrent]';
        assert(Base\Dir::reset($storage));
        $array = Base\Res::open(Base\File::makeUploadArray($_file_));
        $fp = Base\Res::tmpFile();
        $current = Base\File::open('file://'.$_file_);
        $currentNoIP = Base\File::open('file://'.$_file_,['useIncludePath'=>false]);
        $output = Base\Res::open('php://output');
        $input = Base\Res::open('php://input');
        $temp = Base\Res::open('php://temp');
        $memory = Base\Res::open('php://memory');
        $curl = Base\Res::curl(Base\Uri::absolute($mediaPhp));
        $http = Base\Res::open(Base\Uri::absolute($mediaJpgUri));
        $dir = Base\Res::open($_dir_);
        $sym = $storage.'/sym';
        assert(Base\Symlink::set($_file_,$sym));
        $true = Base\Res::open(true);
        $symRes = Base\Res::open($sym);
        $write = Base\Res::open($storage.'/write.txt',['create'=>true]);
        $splice = Base\Res::open($storage.'/splice.txt',['create'=>true]);
        $csvPublic = Base\Res::open($mediaCsv,['create'=>true]);
        $moveAround = Base\Res::open($storage.'/moveAround.txt',['create'=>true]);
        $context = stream_context_create(['http'=>[]]);
        $finfo = finfo_open();
        $captcha = Base\ImageRaster::captcha('test','[assertCommon]/ttf.ttf');
        $zip = Base\Res::open('[assertCommon]/zip.zip');
        $hash = Base\Res::open($mediaHash);
        $vector = Base\Res::open('[assertCommon]/svg.svg');

        // is
        assert(!Base\Res::is('bla'));
        assert(Base\Res::is($fp));
        assert(Base\Res::is($curl));

        // isEmpty
        assert(Base\Res::isEmpty($fp));
        assert(Base\Res::isEmpty($input));
        assert(Base\Res::isEmpty($output));
        assert(!Base\Res::isEmpty($dir));
        assert(Base\Res::isEmpty($curl));

        // isNotEmpty
        assert(Base\Res::isNotEmpty($current));
        assert(Base\Res::isNotEmpty($http));
        assert(Base\Res::isNotEmpty($dir));
        assert(!Base\Res::isNotEmpty($curl));
        assert(Base\Res::isNotEmpty($array));

        // isReadable
        assert(Base\Res::isReadable($fp));
        assert(Base\Res::isReadable($current));
        assert(!Base\Res::isReadable($output));
        assert(Base\Res::isReadable($input));
        assert(Base\Res::isReadable($temp));
        assert(Base\Res::isReadable($memory));
        assert(Base\Res::isReadable($http));
        assert(Base\Res::isReadable($dir));
        assert(!Base\Res::isReadable($curl));
        assert(Base\Res::isReadable($array));

        // isWritable
        assert(Base\Res::isWritable($fp));
        assert(Base\Res::isWritable($output));
        assert(!Base\Res::isWritable($input));
        assert(Base\Res::isWritable($temp));
        assert(Base\Res::isWritable($memory));
        assert(!Base\Res::isWritable($http));
        assert(!Base\Res::isWritable($dir));
        assert(!Base\Res::isBinary($curl));

        // isBinary
        assert(is_bool(Base\Res::isBinary($fp)));
        assert(!Base\Res::isBinary($current));
        assert(Base\Res::isBinary($output));
        assert(Base\Res::isBinary($input));
        assert(Base\Res::isBinary($temp));
        assert(Base\Res::isBinary($memory));
        assert(!Base\Res::isBinary($http));
        assert(!Base\Res::isBinary($dir));
        assert(!Base\Res::isBinary($curl));

        // isStream
        assert(Base\Res::isStream($fp));
        assert(Base\Res::isStream($current));
        assert(Base\Res::isStream($output));
        assert(Base\Res::isStream($input));
        assert(Base\Res::isStream($temp));
        assert(Base\Res::isStream($memory));
        assert(Base\Res::isStream($http));
        assert(Base\Res::isStream($dir));
        assert(!Base\Res::isStream($curl));

        // isRegularType
        assert(Base\Res::isRegularType($fp));
        assert(!Base\Res::isRegularType($curl));
        assert(!Base\Res::isRegularType($finfo));

        // isCurl
        assert(!Base\Res::isCurl($http));
        assert(Base\Res::isCurl($curl));

        // isFinfo
        assert(!Base\Res::isFinfo($curl));
        assert(Base\Res::isFinfo($finfo));

        // isContext
        assert(!Base\Res::isContext($fp));
        assert(Base\Res::isContext($context));
        assert(!Base\Res::isContext($curl));

        // isStreamMetaFile
        $meta = Base\Res::meta($current);
        assert(Base\Res::isStreamMetaFile($meta));
        $meta2 = Base\Res::meta($curl);
        assert(!Base\Res::isStreamMetaFile($meta2));

        // isFile
        assert(Base\Res::isFile($fp));
        assert(Base\Res::isFile($current));
        assert(!Base\Res::isFile($output));
        assert(!Base\Res::isFile($input));
        assert(!Base\Res::isFile($temp));
        assert(!Base\Res::isFile($memory));
        assert(!Base\Res::isFile($http));
        assert(!Base\Res::isFile($dir));
        assert(!Base\Res::isFile($curl));
        assert(Base\Res::isFile($array));
        assert(Base\Res::isFile($hash));

        // isFileExists
        assert(Base\Res::isFileExists($fp));

        // isFileUploaded
        assert(!Base\Res::isFileUploaded($fp));
        assert(!Base\Res::isFileUploaded($current));

        // isFileVisible
        assert(Base\Res::isFileVisible($current));

        // isFilePathToUri
        assert(!Base\Res::isFilePathToUri($current));
        assert(Base\Res::isFilePathToUri($csvPublic));

        // isFileParentExists
        assert(Base\Res::isFileParentExists($current));

        // isFileParentReadable
        assert(Base\Res::isFileParentReadable($current));

        // isFileParentWritable
        assert(is_bool(Base\Res::isFileParentWritable($current)));

        // isFileParentExecutable

        // isFileLike
        assert(Base\Res::isFileLike($fp));
        assert(Base\Res::isFileLike($current));
        assert(!Base\Res::isFileLike($output));
        assert(!Base\Res::isFileLike($input));
        assert(Base\Res::isFileLike($temp));
        assert(Base\Res::isFileLike($memory));
        assert(!Base\Res::isFileLike($http));
        assert(!Base\Res::isFileLike($dir));
        assert(!Base\Res::isFileLike($curl));

        // isDir
        assert(!Base\Res::isDir($current));
        assert(Base\Res::isDir($dir));
        assert(!Base\Res::isDir($curl));

        // isHttp
        assert(!Base\Res::isHttp($fp));
        assert(!Base\Res::isHttp($current));
        assert(!Base\Res::isHttp($output));
        assert(!Base\Res::isHttp($input));
        assert(!Base\Res::isHttp($temp));
        assert(!Base\Res::isHttp($memory));
        assert(Base\Res::isHttp($http));
        assert(!Base\Res::isHttp($dir));
        assert(!Base\Res::isHttp($curl));

        // isPhp
        assert(!Base\Res::isPhp($fp));
        assert(!Base\Res::isPhp($current));
        assert(Base\Res::isPhp($output));
        assert(Base\Res::isPhp($input));
        assert(Base\Res::isPhp($temp));
        assert(Base\Res::isPhp($memory));
        assert(!Base\Res::isPhp($http));
        assert(!Base\Res::isPhp($dir));
        assert(!Base\Res::isPhp($curl));

        // isPhpWritable
        assert(Base\Res::isPhpWritable($output));
        assert(!Base\Res::isPhpWritable($input));
        assert(Base\Res::isPhpWritable($temp));
        assert(Base\Res::isPhpWritable($memory));
        assert(!Base\Res::isPhpWritable($http));
        assert(!Base\Res::isPhpWritable($dir));
        assert(Base\Res::isPhpWritable($true));

        // isPhpInput
        assert(!Base\Res::isPhpInput($output));
        assert(Base\Res::isPhpInput($input));

        // isPhpOutput
        assert(Base\Res::isPhpOutput($output));
        assert(!Base\Res::isPhpOutput($input));

        // isPhpTemp
        assert(!Base\Res::isPhpTemp($output));
        assert(!Base\Res::isPhpTemp($input));
        assert(Base\Res::isPhpTemp($temp));
        assert(!Base\Res::isPhpTemp($memory));

        // isPhpMemory
        assert(!Base\Res::isPhpMemory($output));
        assert(!Base\Res::isPhpMemory($input));
        assert(!Base\Res::isPhpMemory($temp));
        assert(Base\Res::isPhpMemory($memory));
        assert(!Base\Res::isPhpMemory($curl));

        // isResponsable
        assert(Base\Res::isResponsable($fp));
        assert(Base\Res::isResponsable($current));
        assert(Base\Res::isResponsable($output));
        assert(!Base\Res::isResponsable($input));
        assert(Base\Res::isResponsable($temp));
        assert(Base\Res::isResponsable($memory));
        assert(Base\Res::isResponsable($http));
        assert(!Base\Res::isResponsable($dir));
        assert(!Base\Res::isResponsable($curl));

        // isLocal
        assert(Base\Res::isLocal($fp));
        assert(Base\Res::isLocal($current));
        assert(Base\Res::isLocal($output));
        assert(Base\Res::isLocal($input));
        assert(Base\Res::isLocal($temp));
        assert(Base\Res::isLocal($memory));
        assert(!Base\Res::isLocal($http));
        assert(Base\Res::isLocal($dir));
        assert(!Base\Res::isLocal($curl));

        // isRemote
        assert(!Base\Res::isRemote($fp));
        assert(Base\Res::isRemote($http));
        assert(!Base\Res::isRemote($dir));
        assert(!Base\Res::isRemote($curl));

        // isTimedOut
        assert(!Base\Res::isTimedOut($fp));
        assert(!Base\Res::isTimedOut($temp));
        assert(!Base\Res::isTimedOut($dir));
        assert(!Base\Res::isTimedOut($curl));

        // isBlocked
        assert(Base\Res::isBlocked($memory));
        assert(Base\Res::isBlocked($http));
        assert(Base\Res::isBlocked($dir));
        assert(!Base\Res::isBlocked($curl));

        // isSeekable
        assert(!Base\Res::isSeekable($output));
        assert(Base\Res::isSeekable($current));
        assert(Base\Res::isSeekable($dir));
        assert(Base\Res::isSeekable($dir));
        assert(!Base\Res::isSeekable($curl));

        // isSeekableTellable
        assert(!Base\Res::isSeekableTellable($output));
        assert(Base\Res::isSeekableTellable($current));
        assert(!Base\Res::isSeekableTellable($dir));
        assert(!Base\Res::isSeekableTellable($dir));
        assert(!Base\Res::isSeekableTellable($curl));

        // isLockable
        assert(Base\Res::isLockable($current));
        assert(!Base\Res::isLockable($dir));
        assert(!Base\Res::isLockable($http));
        assert(!Base\Res::isLockable($input));
        assert(!Base\Res::isLockable($output));
        assert(!Base\Res::isLockable($memory));
        assert(!Base\Res::isLockable($temp));
        assert(!Base\Res::isLockable($dir));
        assert(!Base\Res::isLockable($curl));

        // isStart
        assert(Base\Res::isStart($fp));
        assert(Base\Res::isStart($input));
        assert(!Base\Res::isStart($dir));
        assert(!Base\Res::isStart($curl));

        // isEnd
        assert(!Base\Res::isEnd($fp));
        assert(!Base\Res::isEnd($input));
        assert(!Base\Res::isEnd($dir));

        // canStat
        assert(Base\Res::canStat($fp));
        assert(Base\Res::canStat($current));
        assert(!Base\Res::canStat($output));
        assert(!Base\Res::canStat($input));
        assert(Base\Res::canStat($temp));
        assert(Base\Res::canStat($memory));
        assert(!Base\Res::canStat($http));
        assert(!Base\Res::canStat($dir));
        assert(!Base\Res::canStat($curl));

        // canLocal
        assert(Base\Res::canLocal($fp));
        assert(!Base\Res::canLocal($curl));

        // canMeta
        assert(Base\Res::canMeta($fp));
        assert(!Base\Res::canMeta($curl));

        // canContext
        assert(Base\Res::canContext($fp));
        assert(!Base\Res::canContext($curl));

        // hasScheme
        assert(Base\Res::hasScheme($http));
        assert(Base\Res::hasScheme($output));
        assert(!Base\Res::hasScheme($currentNoIP));
        assert(!Base\Res::hasScheme($fp));
        assert(!Base\Res::hasScheme($dir));

        // hasExtension
        assert(Base\Res::hasExtension($http));
        assert(!Base\Res::hasExtension($output));
        assert(Base\Res::hasExtension($current));
        assert(Base\Res::hasExtension($fp));
        assert(!Base\Res::hasExtension($dir));

        // isScheme
        assert(!Base\Res::isScheme('file',$fp));
        assert(Base\Res::isScheme(Base\Request::scheme(),$http));
        assert(Base\Res::isScheme('php',$output));

        // isExtension
        assert(Base\Res::isExtension('jpg',$http));
        assert(!Base\Res::isExtension('',$fp));
        assert(Base\Res::isExtension('php',$current));

        // isMimeGroup
        assert(Base\Res::isMimeGroup('imageRaster',$http));

        // isMimeFamily
        assert(Base\Res::isMimeFamily('image',$http));

        // isFilePermission
        assert(Base\Res::isFilePermission('readable',$current));

        // isOwner
        assert(Base\Res::isOwner($current,Base\File::owner($current)));

        // isGroup
        assert(Base\Res::isGroup($current,Base\File::group($current)));

        // stat
        assert(Base\Res::stat($http) === null);
        assert(count(Base\Res::stat($current)) === 26);
        assert(count(Base\Res::stat($current,true)) === 13);
        assert(!empty(Base\Res::stat($temp)));

        // statValue
        assert(is_int(Base\Res::statValue('size',$current)));
        assert(is_string(Base\Res::statValue('size',$current,true,true)));
        assert(is_int(Base\Res::statValue('size',$current,true)));
        assert(Base\Res::statValue('size',$http) === null);

        // inode
        assert(is_int(Base\Res::inode($current)));

        // permission
        assert(is_int(Base\Res::permission($current)));
        assert(is_array(Base\Res::permission($current,true)));

        // owner
        assert(is_int(Base\Res::owner($current)));

        // group
        assert(is_int(Base\Res::group($current)));

        // permissionChange
        assert(Base\Res::permissionChange(777,$moveAround));

        // ownerChange

        // groupChange

        // dateAccess
        assert(is_int(Base\Res::dateAccess($current)));
        assert(is_string(Base\Res::dateAccess($current,true)));

        // dateModify
        assert(is_int(Base\Res::dateModify($current)));
        assert(is_string(Base\Res::dateModify($current,true)));

        // dateInodeModify
        assert(is_int(Base\Res::dateInodeModify($current)));
        assert(is_string(Base\Res::dateInodeModify($current,true)));

        // info
        assert(count(Base\Res::info($http)) === 18);
        assert(count(Base\Res::info($output)) === 18);
        assert(count(Base\Res::info($current)) === 18);
        assert(Base\Res::info($dir)['uri'] === null);
        assert(Base\Res::info($symRes)['path'] === $_file_);
        assert(count(Base\Res::info($context)) === 18);
        assert(count(Base\Res::info($curl)) === 18);
        assert(count(Base\Res::info($finfo)) === 18);
        assert(count(Base\Res::info($zip)) === 18);

        // responseMeta
        assert(count(Base\Res::responseMeta($current)) === 4);
        assert(count(Base\Res::responseMeta($http)) === 4);
        assert(Base\Res::responseMeta($output) === null);
        $output2 = Base\Res::output('jpg','test.jpg',['clean'=>false,'binary'=>false]);
        assert(count(Base\Res::responseMeta($output2)) === 4);
        assert(Base\Res::close($output2));
        assert(Base\Res::responseMeta($curl) === null);
        assert(Base\Res::setContextBasename('testa.log',$current));
        assert(Base\Res::setContextMime('jpg',$current));
        assert(Base\Res::responseMeta($current)['mime'] === 'image/jpeg');
        assert(Base\Res::responseMeta($current)['basename'] === 'testa.log');
        assert(Base\Res::responseMeta($current,false)['basename'] === 'class.php');
        assert(strpos(Base\Res::responseMeta($current,false)['mime'],'text/x-php; ') === 0);

        // type
        assert(Base\Res::type($fp) === 'stream');
        assert(Base\Res::type($http) === 'stream');
        assert(Base\Res::type($current) === 'stream');

        // kind
        assert(Base\Res::kind($fp) === 'file');
        assert(Base\Res::kind($current) === 'file');
        assert(Base\Res::kind($output) === 'phpOutput');
        assert(Base\Res::kind($input) === 'phpInput');
        assert(Base\Res::kind($temp) === 'phpTemp');
        assert(Base\Res::kind($memory) === 'phpMemory');
        assert(Base\Res::kind($http) === 'http');
        assert(Base\Res::kind($context) === 'context');
        assert(Base\Res::kind($curl) === 'curl');
        assert(Base\Res::kind($finfo) === 'finfo');

        // meta
        assert(count(Base\Res::meta($fp)) === 9);
        assert(count(Base\Res::meta($http)) >= 10);

        // metaValue
        assert(is_string(Base\Res::metaValue('uri',$fp)));
        assert(is_string(Base\Res::metaValue('wrapper_type',$http)));

        // mode
        assert(in_array(Base\Res::mode($fp),['r+','r+b'],true));
        assert(Base\Res::mode($fp,true) === 'r+');
        assert(Base\Res::mode($output) === 'wb');
        assert(Base\Res::mode($output,true) === 'w');
        assert(Base\Res::mode($http) === 'r');

        // wrapperType
        assert(Base\Res::wrapperType($fp) === 'plainfile');

        // wrapperData
        assert(count(Base\Res::wrapperData($http)) > 5);
        assert(Base\Res::wrapperData($current) === null);

        // streamType
        assert(Base\Res::streamType($fp) === 'STDIO');

        // unreadBytes
        assert(Base\Res::unreadBytes($fp) === 0);

        // uri
        assert(is_string(Base\Res::uri($fp)));
        assert(is_string(Base\Res::uri($current)));
        assert(is_string(Base\Res::uri($http)));
        assert(Base\Res::uri($output) === 'php://output');
        assert(Base\Res::uri($dir) === null);
        assert(!empty(Base\Res::uri($curl)));

        // uriRemoveScheme
        assert(strpos(Base\Res::uriRemoveScheme($hash),'#') !== false);

        // headers
        assert(Base\Arr::isAssoc(Base\Res::headers($http)));

        // parse
        assert(count(Base\Res::parse($fp)) === 8);
        assert(count(Base\Res::parse($current)) === 8);
        assert(count(Base\Res::parse($http)) === 8);
        assert(count(Base\Res::parse($output)) === 8);
        assert(Base\Res::parse($dir) === null);
        assert(Base\Res::parse($hash)['fragment'] === null);

        // parseOne
        assert(is_string(Base\Res::parseOne(PHP_URL_PATH,$http)));
        assert(Base\Res::parseOne('fragment',$hash) === null);

        // scheme
        assert(Base\Res::scheme($fp) === null);
        assert(Base\Res::scheme($currentNoIP) === null);
        assert(Base\Res::scheme($http) === Base\Request::scheme());
        assert(Base\Res::scheme($output) === 'php');
        assert(Base\Res::scheme($dir) === null);

        // host
        assert(Base\Res::host($fp) === null);
        assert(Base\Res::host($current) === null);
        assert(is_string(Base\Res::host($http)));
        assert(Base\Res::host($output) === 'output');

        // path
        assert(is_string(Base\Res::path($fp)));
        assert(is_string(Base\Res::path($current)));
        assert(Base\Res::path($http) === $mediaJpgUri);
        assert(Base\Res::path($output) === null);
        assert(Base\Res::path($temp) === null);
        assert(strpos(Base\Res::path($hash),'#') !== false);

        // pathinfo
        assert(count(Base\Res::pathinfo($fp)) === 4);
        assert(count(Base\Res::pathinfo($current)) === 4);
        assert(count(Base\Res::pathinfo($http)) === 4);
        assert(Base\Res::pathinfo($output) === null);
        assert(Base\Res::pathinfo($hash)['basename'] === 'hash#.php');

        // pathinfoOne
        assert(Base\Res::pathinfoOne(PATHINFO_FILENAME,$http) === 'jpg');
        assert(Base\Res::pathinfoOne('filename',$http) === 'jpg');

        // dirname
        assert(is_string(Base\Res::dirname($fp)));
        assert(is_string(Base\Res::dirname($current)));
        assert(Base\Res::dirname($http) === dirname($mediaJpgUri));
        assert(Base\Res::dirname($output) === null);

        // basename
        assert(is_string(Base\Res::basename($fp)));
        assert(Base\Res::basename($current) === 'class.php');
        assert(Base\Res::basename($http) === 'jpg.jpg');
        assert(Base\Res::basename($output) === null);

        // safeBasename
        assert(Base\Res::safeBasename($current) === 'class.php');

        // mimeBasename
        assert(Base\Res::mimeBasename($current) === 'class.php');
        assert(Base\Res::mimeBasename($current,'bla.jpg') === 'bla.php');

        // filename
        assert(is_string(Base\Res::filename($fp)));
        assert(Base\Res::filename($current) === 'class');
        assert(Base\Res::filename($http) === 'jpg');
        assert(Base\Res::filename($output) === null);

        // extension
        assert(Base\Res::extension($fp) === 'tmp');
        assert(Base\Res::extension($current) === 'php');
        assert(Base\Res::extension($http) === 'jpg');
        assert(Base\Res::extension($output) === null);

        // size
        assert(Base\Res::size($fp) === 0);
        assert(Base\Res::size($current) > 2000);
        assert(Base\Res::size($http) > 3000);
        assert(Base\Res::size($output) === 0);

        // mime
        assert(Base\Res::mime($fp) === 'inode/x-empty; charset=binary');
        assert(strpos(Base\Res::mime($current),'text/x-php;') === 0);
        assert(Base\Res::mime($http) === 'image/jpeg');
        assert(Base\Res::mime($output) === null);

        // mimeGroup
        assert(Base\Res::mimeGroup($fp) === null);
        assert(Base\Res::mimeGroup($http) === 'imageRaster');

        // mimeFamilies
        assert(Base\Res::mimeFamilies($http) === ['image','binary']);

        // mimeFamily
        assert(Base\Res::mimeFamily($http) === 'image');

        // mimeExtension
        assert(Base\Res::mimeExtension($http) === 'jpg');

        // param
        assert(is_array(Base\Res::param($fp)));

        // option
        assert(is_array(Base\Res::contextOption($fp)));

        // all
        assert($z = count(Base\Res::all()));
        unset($fp);
        assert(count(Base\Res::all()) < $z);

        // transport
        assert(count(Base\Res::transport()) > 5);

        // wrapper
        assert(count(Base\Res::wrapper()) > 5);

        // open
        assert(Base\Res::open($storage.'/testa.php') === null);
        assert(Base\Res::isFile(Base\Res::open('file://'.$_file_)));
        assert(Base\Res::isPhpOutput(Base\Res::open('php://output')));
        assert(Base\Res::isPhpInput(Base\Res::open('php://input')));
        assert(Base\Res::isPhpTemp(Base\Res::open('php://temp')));
        assert(Base\Res::isPhpMemory(Base\Res::open('php://memory')));
        assert(Base\Res::isHttp($http));
        assert(Base\Res::isDir(Base\Res::open($_dir_)));

        // openFromKind

        // openKind
        assert(Base\Res::openKind($_file_) === 'file');
        assert(Base\Res::openKind('file://'.$_file_) === 'file');
        assert(Base\Res::openKind($_dir_) === 'dir');
        assert(Base\Res::openKind('asdasdass') === 'file');
        assert(Base\Res::openKind('http://google.com') === 'http');
        assert(Base\Res::openKind('https://google.com') === 'http');
        assert(Base\Res::openKind('php://output') === 'phpOutput');

        // openMode
        assert(Base\Res::openMode($_file_,'file',['mode'=>'readWrite']) === 'r+');
        assert(Base\Res::openMode($_file_,'file',['mode'=>'r+']) === 'r+');
        assert(Base\Res::openMode("$storage/what",'file',['create'=>true]) === 'c+');
        assert(Base\Res::openMode($storage,'file') === null);
        assert(Base\Res::openMode($_dir_,'dir') === 'r');
        assert(Base\Res::openMode($_dir_,'dir',['binary'=>true]) === 'rb');

        // binary
        $binary = Base\Res::binary($_file_);
        assert(Base\Res::isBinary($binary));
        assert(!Base\Res::isBinary($current));

        // create
        assert(Base\Res::isFile(Base\Res::create($storage.'/testa.php')));

        // setPhpContextOption

        // setContextMime

        // setContextBasename

        // setContextEol

        // getPhpContextOption
        assert(count(Base\Res::getPhpContextOption(null,$current)) === 2);

        // getContextMime
        assert(is_string(Base\Res::getContextMime($current)));

        // getContextBasename
        assert(is_string(Base\Res::getContextBasename($current)));

        // getContextEol
        assert(Base\Res::getContextEol($current) === null);
        assert(is_string(Base\Res::findEol($current)));
        assert(is_string(Base\Res::getContextEol($current)));

        // setContextBasename
        $tempCon = Base\Res::phpWritable('temp');
        assert(!empty(Base\Res::basename($tempCon)));
        assert(Base\Res::setContextBasename('james.log',$tempCon) === true);
        assert(Base\Res::basename($tempCon) === 'james.log');
        assert(Base\Res::setContextBasename('testa.jpg',$current));
        assert(Base\Res::basename($current,true) === 'testa.jpg');
        assert(Base\Res::basename($current) === 'class.php');

        // output
        $output = Base\Res::output('jpg','test.jpg',['clean'=>false]);
        assert(Base\Res::isPhp($output));
        assert(Base\Res::isPhpOutput($output));
        assert(Base\Res::isResponsable($output));
        assert(Base\Res::basename($output) === 'test.jpg');
        assert(Base\Res::close($output));

        // temp
        $temp = Base\Res::temp('jpg','test.jpg',['write'=>['test'=>'ok','test2'=>[true]]]);
        assert(Base\Res::write('bla',$temp));
        assert(Base\Res::mime($temp) === 'image/jpeg');
        assert(Base\Res::size($temp) === 3);
        assert(Base\Res::basename($temp) === 'test.jpg');
        assert(Base\Res::extension($temp) === 'jpg');
        assert(Base\Res::isPhpTemp($temp));
        assert(Base\Res::empty($temp));

        // memory
        $memory = Base\Res::memory('jpg','test.jpg',['write'=>['test'=>'ok','test2'=>[true]]]);
        assert(Base\Res::isPhpMemory($memory));

        // tmpFile
        assert(Base\Res::extension(Base\Res::tmpFile()) === 'tmp');

        // http

        // curl

        // context
        $context = Base\Res::context(['post'=>['test'=>2,'whaté'=>'JEMBAQEUÉÉ'],'header'=>['Header'=>'ok','James'=>'LOL']],'http');
        assert(Base\Res::isContext($context));
        assert(is_string(Base\Res::contextOption($context)['http']['header']));
        assert(is_string(Base\Res::contextOption($context)['http']['content']));
        assert(Base\Res::contextOption($context)['http']['method'] === 'POST');

        // curlExec
        $exec = Base\Res::curlExec($curl,false);
        assert(count($exec) === 6);
        $res = $exec['resource'];
        assert(is_array(Base\Res::headers($res)));
        assert(Base\Res::mime($res) === 'text/html');
        assert(Base\Res::basename($res) === 'php.html');
        assert(Base\Res::uri($res) === 'php://temp');
        assert(Base\Res::path($res) === 'php.html');
        assert(!empty(Base\Res::size($res)));
        assert(count(Base\Res::responseMeta($res)) === 4);
        assert(Base\Res::isResponsable($res));
        $res2 = Base\Res::curl('http://perdu.com',true)['resource'];

        // curlInfo
        assert(count(Base\Res::curlInfo($curl)) >= 26);
        if($res2 !== null) // seulement si online
        assert(count(Base\Res::curlInfo($res2)) >= 26);
        assert(Base\Res::curlInfo($http) === null);

        // position
        assert(Base\Res::position($dir) === null);
        assert(!Base\Res::isStart($dir));
        assert(Base\Res::position($current) === 0);
        assert(Base\Res::isStart($current));
        assert(!Base\Res::isEnd($current));
        assert(Base\Res::position($input) === 0);
        assert(Base\Res::position($memory) === 0);
        assert(Base\Res::position($temp) === 0);
        assert(Base\Res::position($http) === null);

        // seek
        assert(Base\Res::seek(100,$dir));
        assert(Base\Res::position($dir) === null);
        assert(!Base\Res::isStart($dir));
        assert(!Base\Res::isEnd($dir));
        assert(Base\Res::seek(100,$current));
        assert(!Base\Res::isStart($current));
        assert(!Base\Res::isEnd($current));
        assert(Base\Res::seekEnd(0,$current));
        assert(!Base\Res::isStart($current));
        assert(Base\Res::isEnd($current));
        assert(Base\Res::position($output) === null);
        assert(Base\Res::seek(0,$current));
        assert(Base\Res::isStart($current));
        assert(Base\Res::seek(2,$current));
        assert(Base\Res::position($current) === 2);

        // seekDir

        // seekCurrent
        assert(Base\Res::seekRewind($dir));
        assert(Base\Res::seek(2,$dir));
        assert(Base\Res::seekCurrent(2,$dir));
        $file = readdir($dir);
        assert(Base\Res::seekRewind($dir));
        assert(Base\Res::seek(4,$dir));
        assert($file === readdir($dir));
        assert(Base\Res::seekRewind($current));
        assert(Base\Res::seekCurrent(1,$current));
        assert(!Base\Res::isStart($current));
        assert(Base\Res::seekCurrent(3,$current));
        assert(Base\Res::position($current) === 4);
        assert(Base\Res::seekCurrent(2156731327861,$current));
        assert(Base\Res::isEnd($current));

        // seekEnd
        assert(Base\Res::seekRewind($dir));
        assert(!Base\Res::isStart($dir));
        assert(Base\Res::seekEnd(0,$current));
        assert(!Base\Res::isStart($current));
        assert(Base\Res::isEnd($current));
        assert(Base\Res::seekEnd(-1,$current));
        assert(!Base\Res::isStart($current));
        assert(!Base\Res::isEnd($current));
        assert(Base\Res::seekEnd(0,$current));
        assert(Base\Res::isEnd($current));
        assert(Base\Res::seekCurrent(2156731327861,$current));
        assert(Base\Res::isEnd($current));

        // seekRewind
        assert(Base\Res::seekRewind($dir));
        assert(Base\Res::position($dir) === null);
        assert(!Base\Res::isStart($dir));
        assert(Base\Res::seekRewind($current));
        assert(Base\Res::isStart($current));
        assert(!Base\Res::isEnd($current));

        // lock
        assert(Base\Res::lock($current));
        assert(Base\Res::lock($current,true));
        assert(!Base\Res::unlock($http));

        // unlock
        assert(Base\Res::unlock($current));
        assert(!Base\Res::unlock($http));

        // passthru

        // passthruChunk

        // flush
        assert(Base\Res::flush($write));

        // line
        assert(!empty(Base\Res::line($current)));
        assert(Base\Res::line($current) !== Base\Res::line($current));
        assert(is_string(Base\Res::line($csvPublic)));
        assert(is_array(Base\Res::line($csvPublic,['csv'=>true])));

        // lineRef
        $i = 6;
        assert(!empty(Base\Res::lineRef($csvPublic,true,true,$i,['csv'=>true])));

        // get
        assert(count(Base\Res::get($dir,0,true)) > 5);
        assert(Base\Res::get($mediaPhp) === 'lorem ipsum lorem ipsum');

        // read
        assert(count(Base\Res::read(0,true,$dir)) > 5);
        assert(Base\Res::read(false,true,$dir) === []);
        assert(strlen(Base\Res::get($current)) > 500);
        assert(Base\Res::read(true,20,$current) === "<?php\ndeclare(strict");
        assert(Base\Res::read(true,20,$current) === "<?php\ndeclare(strict");
        assert(Base\Res::read(null,20,$current) === "_types=1);\nnamespace");
        assert(Base\Res::read(21,20,$current) === "types=1);\nnamespace ");
        assert(Base\Res::get($curl) === 'lorem ipsum lorem ipsum');
        assert(Base\Res::get($curl) !== '');
        assert(is_string(Base\Res::read(true,5550,$http)));
        assert(Base\Res::read(null,2,$current,['callback'=>[Base\Str::class,'upper']]) === 'QU');

        // readDir

        // findEol
        assert(Base\Res::findEol($current) === "\n");

        // getEolLength
        assert(in_array(Base\Res::getEolLength($current),[1,2],true));

        // parseEol
        $pos = Base\Res::position($current);
        assert(is_string(Base\Res::parseEol($current)));
        assert(Base\Res::position($current) === $pos);

        // getLines
        assert(count(Base\Res::getLines($current)) > 40);

        // lines
        assert(count(Base\Res::lines(2,10,$current)) === 10);
        assert(count(Base\Res::lines(-5,6,$current)) === 5);

        // lineCount
        assert(Base\Res::lineCount($current) > 60);

        // subCount
        assert(Base\Res::subCount('assert(',$current) > 40);

        // base64
        assert(strlen(Base\Res::base64($captcha)) > 3000);
        assert(Base\Res::base64($dir) === null);
        assert(is_string(Base\Res::base64($captcha)));
        assert(is_string(Base\Res::base64($current)));
        assert(Base\Res::base64($temp) === null);
        assert(is_string(Base\Res::base64($csvPublic)));
        assert(Base\Str::isStart('data:image/svg;base64,',Base\Res::base64($vector,true,false)));
        assert(Base\Str::isStart('data:image/svg+xml;base64,',Base\Res::base64($vector,true,true)));
        assert(Base\Str::isStart('data:image/svg+xml;base64,',Base\Res::base64($vector,true,true)));

        // lineFirst
        assert(strpos(Base\Res::lineFirst($current),'<?') === 0);

        // lineLast
        assert(strpos(Base\Res::lineLast($current),'?>') === 0);

        // lineChunk
        assert(Base\Arrs::is(Base\Res::lineChunk(24,$current)));

        // lineChunkWalk
        assert(Base\Arrs::is(Base\Res::lineChunkWalk(function($line,$key) {
            if(strpos(trim($line),'//') === 0)
            return true;
            if(empty(trim($line)))
            return false;
        },$current)));

        // prepareType

        // prepareContent
        assert(Base\Res::prepareContent('test') === 'test');
        assert(Base\Res::prepareContent(2) === '2');
        assert(Base\Res::prepareContent(true) === '1');
        assert(Base\Res::prepareContent(null) === '');
        assert(Base\Res::prepareContent(['test','test2']) === 'test'.PHP_EOL.'test2');
        assert(Base\Res::prepareContent(['test','test2',['test3','ok'=>'test4']]) === Base\Json::encode(['test','test2',['test3','ok'=>'test4']]));
        $x = new \DateTime('now');
        assert(Base\Res::prepareContent($x) === serialize($x));
        assert(strlen(Base\Res::prepareContent(Base\File::open($_file_))) > 2000);
        assert(Base\Res::prepareContent('test',['replace'=>true]) === ['test']);
        $x = new \DateTime('now');
        assert(Base\Res::prepareContent($x,['replace'=>true]) === [serialize($x)]);
        assert(is_string(Base\Res::prepareContent([2,new \DateTime('now'),[$dir,'OK']])));
        assert(is_array(Base\Res::prepareContent([2,new \DateTime('now'),[$dir,'OK']],['csv'=>true])));
        assert(is_array(Base\Res::prepareContent([2,new \DateTime('now'),[$dir,'OK']],['replace'=>true])));

        // set
        assert(Base\Res::set($storage.'/set.txt','WHAT'));
        assert(Base\Res::get($storage.'/set.txt') === 'WHAT');
        assert(Base\Res::set($storage.'/set.txt','TEST'));
        assert(Base\Res::get($storage.'/set.txt') === 'TEST');
        assert(Base\Res::set($storage.'/set.txt','WHAT',true));
        assert(Base\Res::get($storage.'/set.txt') === 'TESTWHAT');

        // write
        $output = Base\Res::output('jpg','test.jpg',['clean'=>false]);
        assert(Base\Res::seekRewind($write));
        assert(Base\Res::write('TEST',$write) === true);
        assert(Base\Res::write('TEST2',$write,['amount'=>2,'lock'=>true,'flush'=>true]) === true);
        assert(Base\Res::get($write) === 'TESTTE');
        assert(Base\Res::isEnd($write));
        $csv = Base\Res::open($storage.'/csv.csv',['create'=>true]);
        assert(Base\Res::write(['test','test2','test3;test4','test5','"quote"','test6'],$csv,['csv'=>true]));
        assert(Base\Res::get($csv) === 'test;test2;"test3;test4";test5;"""quote""";test6'."\n".'');
        assert(Base\Res::write($captcha,$write));
        assert(Base\Res::get($write) !== 'TESTTE');
        assert(Base\Res::empty($write));
        assert(Base\Res::write('TEsTTE',$write,['callback'=>[Base\Str::class,'upper']]));
        assert(Base\Res::seekEnd(0,$csv));
        assert(Base\Res::write([['testz','test2','test3;test4','test5','"quote"','test6']],$csv,['csv'=>true]));
        assert(count(Base\Res::lines(true,true,$csv,['csv'=>true])) === 2);
        assert(Base\Res::write(['testz','test2','test3;test4','test5','"quote"','test6'],$csv,['csv'=>true]));
        assert(count(Base\Res::lines(true,true,$csv,['csv'=>true])) === 3);

        // writeStream
        assert(Base\Res::get($write) === 'TESTTE');
        assert(Base\Res::writeStream('blabla',$write,['newline'=>true,'separator'=>PHP_EOL]));
        assert(Base\Res::get($write) === 'TESTTE'.PHP_EOL.'blabla');
        assert(Base\Res::writeStream('za'.PHP_EOL.'as'.PHP_EOL.''.PHP_EOL.''.PHP_EOL.'dsa',$write));
        assert(count(Base\Res::lines(true,true,$write,[])) === 6);
        assert(count(Base\Res::lines(true,true,$write,['skipEmpty'=>true])) === 4);

        // overwrite
        assert(Base\Res::overwrite('TEST',$write) === true);
        assert(Base\Res::isEnd($write));
        assert(Base\Res::get($write) === 'TEST');
        assert(Base\Res::overwrite('TEST',$write,['amount'=>2]) === true);
        assert(Base\Res::isEnd($write));
        assert(Base\Res::get($write) === 'TE');
        assert(Base\Res::overwrite('TESTTEST2',$write) === true);

        // prepend
        assert(Base\Res::prepend('TEST0',$write) === true);
        assert(Base\Res::isEnd($write));
        assert(Base\Res::get($write) === 'TEST0TESTTEST2');
        assert(Base\Res::prepend('TEST-1',$write,['lock'=>true,'flush'=>true]) === true);
        assert(Base\Res::get($write) === 'TEST-1TEST0TESTTEST2');
        assert(Base\Res::isEnd($write));
        assert(!Base\Res::prepend('Test',$output) === true);
        assert(Base\Res::empty($write));
        assert(Base\Res::prepend(['TEST','TEST2',new \Datetime('now'),2,1.2,true,false,null,'ok'],$write) === true);
        assert(Base\Res::prepend(['zTEST','zTEST2'],$write,['newline'=>true]) === true);
        assert(count(Base\Res::getLines($write)) === 11);
        assert(in_array(strlen(Base\Res::get($write)),[166,176], true));
        assert(Base\Res::seekEnd(0,$csv));
        assert(count(Base\Res::getLines($csv,true,true,['csv'=>true])) === 3);
        assert(Base\Res::prepend([['test','test2èz'],['ztest','y@tesét2z']],$csv,['csv'=>true]));
        assert(count(Base\Res::getLines($csv,true,true,['csv'=>true])) === 5);

        // append
        assert(Base\Res::empty($write));
        assert(Base\Res::append('TEST3',$write) === true);
        assert(Base\Res::append('TEST4',$write) === true);
        assert(Base\Res::isEnd($write));
        assert(Base\Res::get($write) === 'TEST3TEST4');
        assert(Base\Res::append('TEST4',$write,['newline'=>true,'lock'=>true,'flush'=>true]) === true);
        assert(Base\Res::get($write) === 'TEST3TEST4'.PHP_EOL.'TEST4');
        assert(Base\Res::isEnd($write));
        assert(Base\Res::seekEnd(0,$csv));
        assert(Base\Res::append([['test','test2èz'],['ztest','y@tesét2z']],$csv,['csv'=>true]));
        assert(Base\Res::append(['what','sadad'],$csv,['csv'=>true]));
        assert(count(Base\Res::getLines($csv,true,true,['csv'=>true])) === 8);

        // concatenate
        $concatTemp = Base\Res::temp();
        assert(Base\Res::concatenate($concatTemp,null,PHP_EOL,$current,$csvPublic,$captcha));
        assert(strlen(Base\Res::get($concatTemp)) > 1000);

        // concatenateString
        assert(strlen(Base\Res::concatenateString(null,PHP_EOL,$current,$csvPublic,$captcha)) > 5000);
        assert(Base\Res::concatenateString(null,PHP_EOL) === null);

        // lineSplice
        assert(Base\Res::overwrite(Base\File::get($_file_),$splice));
        $original = Base\Res::read(0,true,$splice);
        assert(Base\Res::lineSplice(-100,100,$splice,['OK','LOL'],true));
        assert(strlen(Base\Res::read(0,true,$splice)) < strlen($original));
        assert(Base\Res::lineSplice(0,1,$splice,['OK'],true));
        assert(strpos(Base\Res::read(0,true,$splice),'OK') === 0);
        assert(Base\Res::lineSplice(1,1,$csv,[['plat','james'],['lala','garf','"OK"']],true,['csv'=>true]) === Base\Res::getLines($csv,true,true,['csv'=>true]));
        assert(Base\Res::lineSplice(-2,1,$csv,['avantderi','james'],true,['csv'=>true])[7][0] === 'avantderi');

        // lineSpliceFirst
        assert(Base\Res::lineSpliceFirst($splice,null,true));
        assert(Base\Res::lineSpliceFirst($splice,['WHAT'],true));
        assert(strpos(Base\Res::read(0,true,$splice),'WHAT') === 0);
        assert(Base\Res::lineSpliceFirst($csv,[['spliceFirs','james'],['un autre LOL']],true,['csv'=>true])[1][0] === 'un autre LOL');

        // lineSpliceLast
        assert(Base\Res::lineSpliceLast($splice,null,true));
        assert(Base\Res::lineSpliceLast($splice,['LOL'],true));
        assert(strrpos(Base\Res::read(0,true,$splice),'LOL') !== false);
        assert(Base\Res::lineSpliceLast($csv,[['LAST','james'],['what!']],true,['csv'=>true])[10][0] === 'what!');

        // lineInsert
        assert(Base\Res::lineInsert(2,['LOLI'],$splice,true));
        assert(Base\Res::lineInsert(0,['LOLI2','BLA'],$splice,true));
        assert(strpos(Base\Res::read(0,true,$splice),'LOLI2') === 0);
        assert(Base\Res::lineInsert(4,['QUATREINSERT','james'],$csv,true,['csv'=>true])[4][0] === 'QUATREINSERT');
        assert(strlen(Base\Res::get($csv)) === 277);

        // lineFilter
        assert(count(Base\Res::lineFilter(function($v) {
            if(empty(trim($v)))
            return false;
            return true;
        },$splice,false)) < 900);
        assert(count(Base\Res::lineFilter(function($v,$k) {
            if($k === 2 || $k === 4)
            return true;
        },$csv,true,['csv'=>true])) === 2);

        // lineMap
        assert(Base\Res::lineMap(function($v,$k) {
            if($k === 0)
            return 'FIRST';
            if(empty(trim($v)))
            return 'VIDE!';
            return $v;
        },$splice,true)[6] === 'VIDE!');
        assert(strpos(Base\Res::read(0,true,$splice),'FIRST') === 0);
        assert(count(Base\Res::lineMap(function($v,$k) {
            $v[2] = 'WHAT';
            return $v;
        },$csv,true,['csv'=>true])[0]) === 3);

        // empty
        Base\File::set('[assertCurrent]/empty.txt','aaaWHATÉWHATWHATÉWHATWHATÉWHATWHATÉWHATWHATÉWHATbbb');
        $resource = Base\Res::open("$storage/empty.txt");
        assert(Base\Res::empty($resource,9) === true);
        assert(Base\Res::get($resource) === 'aaaWHATÉ');
        assert(Base\Res::empty($resource) === true);
        assert(Base\Res::empty($csv) === true);
        assert(Base\Res::isStart($resource));
        assert(Base\Res::get($resource) === '');

        // download

        // toScreen

        // toFile
        assert(Base\Res::toFile('[assertCurrent]/toFile.txt',$current) === true);

        // pathToUri
        assert(!empty(Base\Res::pathToUri($csvPublic)));
        assert(strpos(Base\Res::pathToUri($hash),'#') !== false);

        // pathToUriOrBase64
        assert(!empty(Base\Res::pathToUriOrBase64($csvPublic)));

        // touch
        assert(Base\Res::touch($moveAround));

        // rename
        $moveAround = Base\Res::rename(Base\Res::path($moveAround).'2',$moveAround);
        assert(is_resource($moveAround));

        // changeDirname
        $moveAround = Base\Res::changeDirname(Base\Res::dirname($moveAround).'/higher',$moveAround);
        assert(is_resource($moveAround));

        // changeBasename
        $moveAround = Base\Res::changeBasename('moveAround2',$moveAround);
        assert(is_resource($moveAround));

        // changeExtension
        $moveAround = Base\Res::changeExtension('jpg',$moveAround);
        assert(is_resource($moveAround));

        // removeExtension
        $moveAround = Base\Res::removeExtension($moveAround);
        assert(is_resource($moveAround));

        // moveUploaded
        assert(Base\Res::moveUploaded(Base\Res::path($moveAround).'2',$moveAround) === null);

        // copy
        assert(Base\Res::copy(Base\Res::path($moveAround).'copy',$moveAround));

        // copyInDirname
        assert(Base\Res::copyInDirname('well',$moveAround));

        // copyWithBasename
        assert(Base\Res::copyWithBasename(Base\Res::dirname($moveAround).'/up',$moveAround));

        // unlink
        assert(Base\Res::write('ok',$moveAround));
        assert(Base\Res::unlink($moveAround));
        assert(!Base\Res::write('ok',$moveAround));
        assert(!Base\Res::touch($moveAround));
        assert(!Base\Res::unlink($moveAround));

        // close
        assert(Base\Res::close($output));
        $x2 = Base\Res::open($_dir_);
        $curlOpen = Base\Res::curl(Base\Uri::absolute($mediaJpgUri));
        $file2 = Base\Res::open($_file_);
        assert(Base\Res::close($x2));
        assert(!Base\Res::close($x2));
        assert(Base\Res::close($curlOpen));
        assert(!Base\Res::close($curlOpen));
        assert(Base\Res::close($curl));
        assert(!Base\Res::close($curl));
        assert(Base\Res::close($finfo));
        assert(!Base\Res::close($finfo));
        assert(Base\Res::close($dir));

        // closes
        Base\Res::closes($current,$output,$input,$temp,$memory,$http,$dir);

        // uriSchemeNotWindowsDrive

        // cleanup
        Base\Dir::empty('[assertCurrent]');

        return true;
    }
}
?>