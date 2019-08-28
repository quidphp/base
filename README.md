# Quid\Base
<p align='center'>
  <a href='https://packagist.org/packages/quidphp/base'><img src='https://img.shields.io/github/v/release/quidphp/base' alt='Release' /></a>
  <a href='https://github.com/quidphp/base/blob/master/LICENSE'><img src='https://img.shields.io/github/license/quidphp/base' alt='License' /></a>
  <a href='https://www.php.net'><img src='https://img.shields.io/packagist/php-v/quidphp/base' alt='PHP Version' /></a>
  <a href='https://styleci.io'><img src='https://styleci.io/repos/203664262/shield' alt='Style CI' /></a>
  <a href='https://github.com/quidphp/base'><img src='https://img.shields.io/github/languages/code-size/quidphp/base' alt='Code Size' /></a>
</p>

## About
**Quid\Base** is a low-level library of static methods for PHP. It is part of the QuidPHP framework and CMS but it can be used standalone. This library requires PHP 7.2+. It is available as an open-source software under the [MIT license](LICENSE).

## Composer
**Quid\Base** can be installed through [Composer](https://getcomposer.org). 
``` bash
$ composer require quidphp/base
```

## Testing
**Quid\Base** testsuite can be runned by creating a new [Quid\Project](https://github.com/quidphp/project). All tests and assertions are part of the [Quid\Test](https://github.com/quidphp/test) repository.

## Convention
**Quid\Base** is built on the following conventions:
- *Filename*: Traits filenames start with an underscore (_).
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Type*: Files, function arguments and return types are strict typed.
- *Static*: All class constructors are private, thus all methods are static and there is no object instantiation.
- *Error*: The only exceptions that are thrown are related to function arguments and return types.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.

## Overview
Lorem ipsum lorem ipsum
- [Arr](src/Arr.php) | Lorem ipsum
- [Arrs](src/Arrs.php) | Lorem ipsum
- [Assert](src/Assert.php) | Lorem ipsum
- [Assoc](src/Assoc.php) | Lorem ipsum
- [Attr](src/Attr.php) | Lorem ipsum
- [Autoload](src/Autoload.php) | Lorem ipsum
- [Boolean](src/Boolean.php) | Lorem ipsum
- [Browser](src/Browser.php) | Lorem ipsum
- [Buffer](src/Buffer.php) | Lorem ipsum
- [Call](src/Call.php) | Lorem ipsum
- [Classe](src/Classe.php) | Lorem ipsum
- [Column](src/Column.php) | Lorem ipsum
- [Config](src/Config.php) | Lorem ipsum
- [Constant](src/Constant.php) | Lorem ipsum
- [Cookie](src/Cookie.php) | Lorem ipsum
- [Crypt](src/Crypt.php) | Lorem ipsum
- [Csv](src/Csv.php) | Lorem ipsum
- [Date](src/Date.php) | Lorem ipsum
- [Debug](src/Debug.php) | Lorem ipsum
- [Dir](src/Dir.php) | Lorem ipsum
- [Email](src/Email.php) | Lorem ipsum
- [Encoding](src/Encoding.php) | Lorem ipsum
- [Error](src/Error.php) | Lorem ipsum
- [Exception](src/Exception.php) | Lorem ipsum
- [Extension](src/Extension.php) | Lorem ipsum
- [File](src/File.php) | Lorem ipsum
- [Finder](src/Finder.php) | Lorem ipsum
- [Fqcn](src/Fqcn.php) | Lorem ipsum
- [Func](src/Func.php) | Lorem ipsum
- [Globals](src/Globals.php) | Lorem ipsum
- [Header](src/Header.php) | Lorem ipsum
- [Html](src/Html.php) | Lorem ipsum
- [Http](src/Http.php) | Lorem ipsum
- [ImageRaster](src/ImageRaster.php) | Lorem ipsum
- [Ini](src/Ini.php) | Lorem ipsum
- [Ip](src/Ip.php) | Lorem ipsum
- [Json](src/Json.php) | Lorem ipsum
- [Lang](src/Lang.php) | Lorem ipsum
- [Listing](src/Listing.php) | Lorem ipsum
- [Mime](src/Mime.php) | Lorem ipsum
- [Nav](src/Nav.php) | Lorem ipsum
- [Network](src/Network.php) | Lorem ipsum
- [Number](src/Number.php) | Lorem ipsum
- [Obj](src/Obj.php) | Lorem ipsum
- [Path](src/Path.php) | Lorem ipsum
- [PathTrack](src/PathTrack.php) | Lorem ipsum
- [Request](src/Request.php) | Lorem ipsum
- [Res](src/Res.php) | Lorem ipsum
- [Response](src/Response.php) | Lorem ipsum
- [Root](src/Root.php) | Lorem ipsum
- [Scalar](src/Scalar.php) | Lorem ipsum
- [Segment](src/Segment.php) | Lorem ipsum
- [Server](src/Server.php) | Lorem ipsum
- [Session](src/Session.php) | Lorem ipsum
- [Set](src/Set.php) | Lorem ipsum
- [Slug](src/Slug.php) | Lorem ipsum
- [SlugPath](src/SlugPath.php) | Lorem ipsum
- [Sql](src/Sql.php) | Lorem ipsum
- [Str](src/Str.php) | Lorem ipsum
- [Style](src/Style.php) | Lorem ipsum
- [Superglobal](src/Superglobal.php) | Lorem ipsum
- [Symlink](src/Symlink.php) | Lorem ipsum
- [Test](src/Test.php) | Lorem ipsum
- [Timezone](src/Timezone.php) | Lorem ipsum
- [Uri](src/Uri.php) | Lorem ipsum
- [Validate](src/Validate.php) | Lorem ipsum
- [Xml](src/Xml.php) | Lorem ipsum

Lorem ipsum lorem ipsum
- [_cacheFile](src/_cacheFile.php) | Lorem ipsum
- [_cacheStatic](src/_cacheStatic.php) | Lorem ipsum
- [_config](src/_config.php) | Lorem ipsum
- [_option](src/_option.php) | Lorem ipsum
- [_root](src/_root.php) | Lorem ipsum
- [_shortcut](src/_shortcut.php) | Lorem ipsum
