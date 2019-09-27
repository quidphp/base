<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/base/blob/master/LICENSE
 */

namespace Quid\Test\Base;
use Quid\Base;

// symlink
// class for testing Quid\Base\Symlink
class Symlink extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $storagePath = Base\Finder::normalize('[storage]');
        $storage = '[assertCurrent]';
        $common = '[assertCommon]';
        $_file_ = Base\Finder::normalize('[assertCommon]/class.php');
        $_dir_ = dirname($_file_);
        assert(Base\Dir::reset($storage));
        $sym = $storage.'/sym';
        $sym2 = $storage.'/sym2';
        $sym3 = $storage.'/sym3';
        symlink($_file_,Base\Symlink::normalize($sym));

        // is
        assert(Base\Symlink::is($sym));
        assert(!Base\Symlink::is($_file_));
        assert(!Base\Symlink::is($_file_.'.jpg'));

        // isReadable
        assert(Base\Symlink::isReadable($sym));
        assert(!Base\Symlink::isReadable($_file_));

        // isWritable
        assert(Base\Symlink::isWritable($sym));

        // isExecutable

        // inode

        // permission
        assert(Base\Symlink::permission($sym) !== Base\Finder::permission($sym));
        assert(Base\Symlink::permission($sym,true) !== Base\Finder::permission($sym,true));

        // owner

        // ownerChange

        // group
        assert(is_int(Base\Symlink::group($sym)));

        // groupChange
        assert(!Base\Symlink::groupChange(123,$_file_));

        // size
        assert(Base\Symlink::size($sym) !== Base\Finder::size($sym));
        assert(is_string(Base\Symlink::size($sym,true)));

        // dateAccess
        assert(is_numeric(Base\Symlink::dateAccess($sym)));
        assert(is_string(Base\Symlink::dateAccess($sym,true)));

        // dateModify
        assert(is_numeric(Base\Symlink::dateModify($sym)));
        assert(is_string(Base\Symlink::dateModify($sym,true)));

        // dateInodeModify
        assert(is_numeric(Base\Symlink::dateInodeModify($sym)));
        assert(is_string(Base\Symlink::dateInodeModify($sym,true)));

        // stat
        assert(Base\Finder::stat($sym,true) !== Base\Symlink::stat($sym,true));
        assert(Base\Finder::stat($sym) !== Base\Symlink::stat($sym));

        // info
        assert(count(Base\Symlink::info($sym)) === 11);
        assert(count(Base\Finder::info($sym)) === 11);

        // get
        assert(Base\Symlink::get($_file_) === null);
        assert(Base\Symlink::get($sym) === $_file_);

        // getStat
        assert(Base\Symlink::getStat($_file_) === null);
        assert(count(Base\Symlink::getStat($sym)) === 13);

        // getInfo
        assert(Base\Symlink::getInfo($_file_) === null);
        assert(Base\Symlink::getInfo($sym)['path'] === $_file_);
        assert(count(Base\Symlink::getInfo($sym)) === 11);

        // set
        $prefix = Base\File::prefix();
        assert(Base\Symlink::set($sym2,$prefix));
        assert(!Base\Symlink::set($sym2,$_file_));

        // sets

        // touch
        assert(Base\Symlink::touch($sym));
        assert(Base\Symlink::touch($sym));
        assert(Base\Symlink::touch($sym2));
        assert(Base\Finder::touch($sym2));

        // rename
        assert(Base\Symlink::set($sym3,$_file_));
        assert(Base\Symlink::rename($storage.'/rename/deep/symRename',$sym3));
        assert(Base\Symlink::set($sym3,$_file_));
        assert(Base\Symlink::changeBasename('renameBasename',$sym3));
        assert(Base\Symlink::set($sym3,$_file_));
        assert(Base\Symlink::changeDirname($storage.'/rename/deep',$sym3));

        // copy
        $dname = dirname($sym);
        assert(Base\Symlink::copy($dname.'/whatz/ok',$sym));
        assert(Base\Symlink::unset($dname.'/whatz/ok'));
        assert(Base\Symlink::info($dname.'/whatz/ok') === null);
        assert(!Base\Symlink::copy($dname.'/whatz/ok',$_file_));

        // unset
        $file = Base\File::prefix('[assertCurrent]');
        assert(!Base\Symlink::unset($file));
        assert(Base\Finder::unlink($file));
        assert(Base\Symlink::unset($sym));

        // reset
        assert(Base\Symlink::reset($_dir_,$sym2));
        assert(Base\Symlink::get($sym2) === $_dir_);
        assert(Base\Symlink::unset($sym2));

        // cleanup
        Base\Dir::empty('[assertCurrent]');
        Base\Finder::unlink($prefix);

        return true;
    }
}
?>