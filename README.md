# QuidPHP/Base
[![Release](https://img.shields.io/github/v/release/quidphp/base)](https://packagist.org/packages/quidphp/base)
[![License](https://img.shields.io/github/license/quidphp/base)](https://github.com/quidphp/base/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/quidphp/base)](https://www.php.net)
[![Style CI](https://styleci.io/repos/203664262/shield)](https://styleci.io)
[![Code Size](https://img.shields.io/github/languages/code-size/quidphp/base)](https://github.com/quidphp/base)

## About
**QuidPHP/Base** is a PHP library that provides a set of low-level static methods. It is part of the [QuidPHP](https://github.com/quidphp/project) package and can also be used standalone.

## License
**QuidPHP/Base** is available as an open-source software under the [MIT license](LICENSE).

## Documentation
**QuidPHP/Base** documentation is being written. Once ready, it will be available at [QuidPhp/Docs](https://quidphp.github.io/docs).

## Installation
**QuidPHP/Base** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/base).
``` bash
$ composer require quidphp/base
```
Once installed, the **Quid\Base** namespace will be available within your PHP application.

## Requirement
**QuidPHP/Base** requires the following:
- PHP 7.4, 8.0 or 8.1 with these extensions:
    - ctype
    - curl
    - date
    - fileinfo
    - gd
    - iconv
    - json
    - mbstring
    - pcre
    - openssl
    - session
    - SimpleXML
    - zip
    
## Comment
**QuidPHP/Base** code is commented and all methods are explained. However, most of the comments are written in French.

## Convention
**QuidPHP/Base** is built on the following conventions:
- *Traits*: Traits filenames start with an underscore (_).
- *Type*: Files, function arguments and return types are strict typed.
- *Static*: All class constructors are private, thus all methods are static and there is no object instantiation.
- *Error*: The only exceptions that are thrown are related to function arguments and return types.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.

## Overview
**QuidPHP/Base** contains 81 classes and traits. Here is an overview:
- [Arr](src/Arr.php) - Class with static methods to work with unidimensional arrays
- [Arrs](src/Arrs.php) - Class with static methods to work with multidimensional arrays (an array containing at least another array)
- [Assert](src/Assert.php) - Class with methods a layer over the native PHP assert functions
- [Assoc](src/Assoc.php) - Class to deal with associative strings and arrays
- [Attr](src/Attr.php) - Class with static methods to generate HTML attributes
- [Autoload](src/Autoload.php) - Class with methods a layer over the native PHP autoload logic
- [Boolean](src/Boolean.php) - Class with static methods to deal with boolean type
- [Buffer](src/Buffer.php) - Class with methods a layer over the native PHP output buffering functions
- [Call](src/Call.php) - Class with static methods to manage callables and callbacks
- [Classe](src/Classe.php) - Class with static methods to deal with classes using fully qualified class name strings
- [Cli](src/Cli.php) - Class with static methods to generate output for cli
- [Column](src/Column.php) - Class with static methods to work with multidimensional column arrays (like a database query result array)
- [Config](src/Config.php) - Class with config property, used for extending other classes
- [Constant](src/Constant.php) - Class with static methods to work with PHP constants
- [Cookie](src/Cookie.php) - Class with static methods to add and remove cookies
- [Crypt](src/Crypt.php) - Class which contains methods to deal with the most common PHP cryptography functions
- [Csv](src/Csv.php) - Class with static methods to easily work with CSV files
- [Curl](src/Curl.php) - Class with basic logics for managing requests via curl
- [Datetime](src/Datetime.php) - Class with static methods to generate, format and parse dates
- [Debug](src/Debug.php) - Class with tools to help for debugging, also injects some helper functions
- [Dir](src/Dir.php) - Class with static methods to list, open and parse directories
- [Email](src/Email.php) - Class with methods a layer over the native PHP send_mail function, handles mail headers
- [Encoding](src/Encoding.php) - Class which contains methods related to string encoding (manages mb overload)
- [Error](src/Error.php) - Class with methods a layer over the native PHP error functions and handler
- [Exception](src/Exception.php) - Class with methods a layer over the native PHP exception functions and handler
- [Extension](src/Extension.php) - Class which contains methods to deal with PHP extensions
- [File](src/File.php) - Class with static methods to create, read and write files (accepts path strings and resources)
- [Finder](src/Finder.php) - Class that provides methods to deal with the filesystem (used by Dir, File and Symlink)
- [Finfo](src/Finfo.php) - Class with basic logics for managing the finfo extension
- [Floating](src/Floating.php) - Class with static methods to work with floating numbers
- [Fqcn](src/Fqcn.php) - Class with static methods to deal with fully qualified class name strings
- [Func](src/Func.php) - Class with static methods to work with simple functions
- [Globals](src/Globals.php) - Class with static methods to manage global variables
- [Header](src/Header.php) - Class with static methods to work with HTTP headers
- [Html](src/Html.php) - Class with static methods for easily generating HTML
- [Http](src/Http.php) - Class with static methods related to the HTTP protocol
- [ImageRaster](src/ImageRaster.php) - Class with static methods to work with pixelated images
- [Ini](src/Ini.php) - Class with methods a layer over the native PHP ini functions
- [Integer](src/Integer.php) - Class with static methods to work with integers
- [Ip](src/Ip.php) - Class with static methods to work with IP strings
- [Json](src/Json.php) - Class with static methods to encode and decode JSON
- [Lang](src/Lang.php) - Class to manage language text and translations
    - [En](src/Lang/En.php) - English language content used by this namespace
    - [Fr](src/Lang/Fr.php) - French language content used by this namespace
- [Listing](src/Listing.php) - Class to deal with associative strings and arrays
- [Mime](src/Mime.php) - Class with static methods to get or guess mime types
- [Nav](src/Nav.php) - Class which contains methods to build a complex pagination engine
- [Network](src/Network.php) - Class with static network-related methods (dns, mx, ping, hostname and more)
- [Num](src/Num.php) - Class with static methods to work with strings, ints and floats numbers
- [Obj](src/Obj.php) - Class with static methods to deal with objects, does not accept fqcn strings
- [Path](src/Path.php) - Class with static methods to deal with filesystem paths
- [PathTrack](src/PathTrack.php) - Class with static methods to deal with filesystem paths (without a starting slash)
- [Request](src/Request.php) - Class with static methods to analyze the current request
- [Res](src/Res.php) - Class with static methods to create and modify resources of all types
- [Response](src/Response.php) - Class with static methods to alter the current response
- [Root](src/Root.php) - Abstract class extended by almost all others
- [Scalar](src/Scalar.php) - Class with static methods to deal with scalar types
- [Segment](src/Segment.php) - Class that provides the logic to replace bracket segment within a string
- [Server](src/Server.php) - Class that provides a set of methods to analyze the current server
- [Session](src/Session.php) - Class with static methods to manage a session (built over the native PHP session functions)
- [Set](src/Set.php) - Class with static methods to deal with set strings
- [Slug](src/Slug.php) - Class with static methods to deal with URI slugs
- [SlugPath](src/SlugPath.php) - Class with static methods to deal with URI slugs within an URI path
- [Str](src/Str.php) - Class with static methods to work with strings
- [Style](src/Style.php) - Class with static methods to generate an HTML style attribute
- [Superglobal](src/Superglobal.php) - Class with static methods to deal with superglobal variables
- [Symlink](src/Symlink.php) - Class with static methods to manage symlinks
- [Test](src/Test.php) - Abstract class used to create a testsuite for a class
- [Timezone](src/Timezone.php) - Class with static methods to deal with timezone
- [Uri](src/Uri.php) - Class with static methods to generate URI (absolute and relative)
- [UserAgent](src/UserAgent.php) - Class with methods related to useragent
- [Validate](src/Validate.php) - Class that provides validation logic and methods
- [Vari](src/Vari.php) - Class with some general static methods related to variables
- [Xml](src/Xml.php) - Class with some static methods related to XML
- [_cacheFile](src/_cacheFile.php) - Trait that provides methods to get or set a cached value from a file
- [_cacheStatic](src/_cacheStatic.php) - Trait that provides methods to get or set a cached value from a static property
- [_config](src/_config.php) - Trait that grants static methods to get or set data within static config
- [_init](src/_init.php) - Trait that provides the logic to recursively merge the static properties with the parent's properties
- [_option](src/_option.php) - Trait that grants static methods to deal with static options (within the $config static property)
- [_root](src/_root.php) - Trait that provides some basic fqcn methods
- [_shortcut](src/_shortcut.php) - Trait that grants static methods to declare and replace shortcuts (bracketed segments within strings)

## Testing
**QuidPHP/Base** contains 70 test classes:
- [Arr](test/Arr.php) - Class for testing Quid\Base\Arr
- [Arrs](test/Arrs.php) - Class for testing Quid\Base\Arrs
- [Assert](test/Assert.php) - Class for testing Quid\Base\Assert
- [Assoc](test/Assoc.php) - Class for testing Quid\Base\Assoc
- [Attr](test/Attr.php) - Class for testing Quid\Base\Attr
- [Autoload](test/Autoload.php) - Class for testing Quid\Base\Autoload
- [Boolean](test/Boolean.php) - Class for testing Quid\Base\Boolean
- [Browser](test/Browser.php) - Class for testing Quid\Base\Browser
- [Buffer](test/Buffer.php) - Class for testing Quid\Base\Buffer
- [Call](test/Call.php) - Class for testing Quid\Base\Call
- [Classe](test/Classe.php) - Class for testing Quid\Base\Classe
- [Cli](test/Cli.php) - Class for testing Quid\Base\Cli
- [Column](test/Column.php) - Class for testing Quid\Base\Column
- [Constant](test/Constant.php) - Class for testing Quid\Base\Constant
- [Cookie](test/Cookie.php) - Class for testing Quid\Base\Cookie
- [Crypt](test/Crypt.php) - Class for testing Quid\Base\Crypt
- [Csv](test/Csv.php) - Class for testing Quid\Base\Csv
- [Curl](test/Curl.php) - Class for testing Quid\Base\Curl
- [Datetime](test/Datetime.php) - Class for testing Quid\Base\Datetime
- [Debug](test/Debug.php) - Class for testing Quid\Base\Debug
- [Dir](test/Dir.php) - Class for testing Quid\Base\Dir
- [Email](test/Email.php) - Class for testing Quid\Base\Email
- [Encoding](test/Encoding.php) - Class for testing Quid\Base\Encoding
- [Error](test/Error.php) - Class for testing Quid\Base\Error
- [Exception](test/Exception.php) - Class for testing Quid\Base\Exception
- [Extension](test/Extension.php) - Class for testing Quid\Base\Extension
- [File](test/File.php) - Class for testing Quid\Base\File
- [Finder](test/Finder.php) - Class for testing Quid\Base\Finder
- [Finfo](test/Finfo.php) - Class for testing Quid\Base\Finfo
- [Floating](test/Floating.php) - Class for testing Quid\Base\Floating
- [Fqcn](test/Fqcn.php) - Class for testing Quid\Base\Fqcn
- [Func](test/Func.php) - Class for testing Quid\Base\Func
- [Globals](test/Globals.php) - Class for testing Quid\Base\Globals
- [Header](test/Header.php) - Class for testing Quid\Base\Header
- [Html](test/Html.php) - Class for testing Quid\Base\Html
- [Http](test/Http.php) - Class for testing Quid\Base\Http
- [ImageRaster](test/ImageRaster.php) - Class for testing Quid\Base\ImageRaster
- [Ini](test/Ini.php) - Class for testing Quid\Base\Ini
- [Integer](test/Integer.php) - Class for testing Quid\Base\Integer
- [Ip](test/Ip.php) - Class for testing Quid\Base\Ip
- [Json](test/Json.php) - Class for testing Quid\Base\Json
- [Lang](test/Lang.php) - Class for testing Quid\Base\Lang
- [Listing](test/Listing.php) - Class for testing Quid\Base\Listing
- [Mime](test/Mime.php) - Class for testing Quid\Base\Mime
- [Nav](test/Nav.php) - Class for testing Quid\Base\Nav
- [Network](test/Network.php) - Class for testing Quid\Base\Network
- [Num](test/Num.php) - Class for testing Quid\Base\Num
- [Obj](test/Obj.php) - Class for testing Quid\Base\Obj
- [Path](test/Path.php) - Class for testing Quid\Base\Path
- [PathTrack](test/PathTrack.php) - Class for testing Quid\Base\PathTrack
- [Request](test/Request.php) - Class for testing Quid\Base\Request
- [Res](test/Res.php) - Class for testing Quid\Base\Res
- [Response](test/Response.php) - Class for testing Quid\Base\Response
- [Root](test/Root.php) - Class for testing Quid\Base\Root
- [Scalar](test/Scalar.php) - Class for testing Quid\Base\Scalar
- [Segment](test/Segment.php) - Class for testing Quid\Base\Segment
- [Server](test/Server.php) - Class for testing Quid\Base\Server
- [Session](test/Session.php) - Class for testing Quid\Base\Session
- [Set](test/Set.php) - Class for testing Quid\Base\Set
- [Slug](test/Slug.php) - Class for testing Quid\Base\Slug
- [SlugPath](test/SlugPath.php) - Class for testing Quid\Base\SlugPath
- [Str](test/Str.php) - Class for testing Quid\Base\Str
- [Style](test/Style.php) - Class for testing Quid\Base\Style
- [Superglobal](test/Superglobal.php) - Class for testing Quid\Base\Superglobal
- [Symlink](test/Symlink.php) - Class for testing Quid\Base\Symlink
- [Timezone](test/Timezone.php) - Class for testing Quid\Base\Timezone
- [Uri](test/Uri.php) - Class for testing Quid\Base\Uri
- [Validate](test/Validate.php) - Class for testing Quid\Base\Validate
- [Vari](test/Vari.php) - Class for testing Quid\Base\Vari
- [Xml](test/Xml.php) - Class for testing Quid\Base\Xml

**QuidPHP/Base** PHP testsuite can be run by creating a new [quidphp/assert](https://github.com/quidphp/assert) project..