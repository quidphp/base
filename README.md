# Quid\Base
[![Release](https://img.shields.io/github/v/release/quidphp/base)](https://packagist.org/packages/quidphp/base)
[![License](https://img.shields.io/github/license/quidphp/base)](https://github.com/quidphp/base/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/quidphp/base)](https://www.php.net)
[![Style CI](https://styleci.io/repos/203664262/shield)](https://styleci.io)
[![Code Size](https://img.shields.io/github/languages/code-size/quidphp/base)](https://github.com/quidphp/base)

## About
**Quid\Base** is a low-level library of static methods for PHP. It is part of the [QuidPHP](https://github.com/quidphp/project) package and can also be used standalone.

## License
**Quid\Base** is available as an open-source software under the [MIT license](LICENSE).

## Installation
**Quid\Base** can be easily installed with [Composer](https://getcomposer.org). It is available on [Packagist](https://packagist.org/packages/quidphp/base).
``` bash
$ composer require quidphp/base
```

## Requirement
**Quid\Base** requires the following:
- PHP 7.2+ with fileinfo, curl, openssl and posix

## Dependency
**Quid\Base** has no dependency.

## Testing
**Quid\Base** testsuite can be run by creating a new [Quid\Project](https://github.com/quidphp/project). All tests and assertions are part of the [Quid\Test](https://github.com/quidphp/test) repository.

## Comment
**Quid\Base** code is commented and all methods are explained. However, the method and property comments are currently written in French.

## Convention
**Quid\Base** is built on the following conventions:
- *Traits*: Traits filenames start with an underscore (_).
- *Coding*: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- *Type*: Files, function arguments and return types are strict typed.
- *Static*: All class constructors are private, thus all methods are static and there is no object instantiation.
- *Error*: The only exceptions that are thrown are related to function arguments and return types.
- *Config*: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.

## Overview
**Quid\Base** contains more than 70 classes and traits. Here is an overview:
- [Arr](src/Arr.php) | Class with static methods to work with unidimensionnal arrays
- [Arrs](src/Arrs.php) | Class with static methods to work with multidimensional arrays (an array containing at least another array)
- [Assert](src/Assert.php) | Class with methods a layer over the native PHP assert functions
- [Assoc](src/Assoc.php) | Class to deal with associative strings and arrays
- [Attr](src/Attr.php) | Class with static methods to generate HTML attributes
- [Autoload](src/Autoload.php) | Class with methods a layer over the native PHP autoload logic
- [Boolean](src/Boolean.php) | Class with static methods to deal with boolean type
- [Browser](src/Browser.php) | Class with methods a layer over the native PHP get_browser function
- [Buffer](src/Buffer.php) | Class with methods a layer over the native PHP output buffering functions
- [Call](src/Call.php) | Class with static methods to manage callables and callbacks
- [Classe](src/Classe.php) | Class with static methods to deal with classes with fully qualified class name strings (does not use Reflection)
- [Column](src/Column.php) | Class with static methods to work with multidimensional column arrays (like a database query result array)
- [Config](src/Config.php) | Class with config property, used for extending other classes
- [Constant](src/Constant.php) | Class with static methods to work with PHP constants
- [Cookie](src/Cookie.php) | Class with static methods to add and remove cookies
- [Crypt](src/Crypt.php) | Class which contains methods to deal with the most common PHP cryptography functions
- [Csv](src/Csv.php) | Class with static methods to easily work with CSV files
- [Date](src/Date.php) | Class with static methods to generate, format and parse dates
- [Debug](src/Debug.php) | Class with tools to help for debugging, also injects some helper functions
- [Dir](src/Dir.php) | Class with static methods to list, open and parse directories
- [Email](src/Email.php) | Class with methods a layer over the native PHP send_mail function, handles mail headers
- [Encoding](src/Encoding.php) | Class which contains methods related to string encoding (manages mb overload)
- [Error](src/Error.php) | Class with methods a layer over the native PHP error functions and handler
- [Exception](src/Exception.php) | Class with methods a layer over the native PHP exception functions and handler
- [Extension](src/Extension.php) | Class which contains methods to deal with PHP extensions
- [File](src/File.php) | Class with static methods to create, read and write files (accepts path strings and resources)
- [Finder](src/Finder.php) | Class that provides methods to deal with the filesystem (used by Dir, File and Symlink)
- [Fqcn](src/Fqcn.php) | Class with static methods to deal with fully qualified class name strings
- [Func](src/Func.php) | Class with static methods to work with simple functions
- [Globals](src/Globals.php) | Class with static methods to manage global variables
- [Header](src/Header.php) | Class with static methods to work with HTTP headers
- [Html](src/Html.php) | Class with static methods for easily generating HTML
- [Http](src/Http.php) | Class with static methods related to the HTTP protocol
- [ImageRaster](src/ImageRaster.php) | Class with static methods to easily work with pixelated images
- [Ini](src/Ini.php) | Class with methods a layer over the native PHP ini functions
- [Ip](src/Ip.php) | Class with static methods to work with IP strings
- [Json](src/Json.php) | Class with static methods to encode and decode JSON
- [Lang](src/Lang.php) | Class to manage language text and translations
    - [En](src/Lang/En.php) | English language content used by this namespace
    - [Fr](src/Lang/Fr.php) | French language content used by this namespace
- [Listing](src/Listing.php) | Class to deal with associative strings and arrays (test: 1, test2: 2)
- [Mime](src/Mime.php) | Class with static methods to get or guess mime types
- [Nav](src/Nav.php) | Class which contains methods to build a complex pagination engine
- [Network](src/Network.php) | Class with static network-related methods (dns, mx, ping, hostname and more)
- [Number](src/Number.php) | Class with static methods to work with strings, ints and floats numbers
- [Obj](src/Obj.php) | Class with static methods to deal with objects, does not accept fqcn strings (does not use Reflection)
- [Path](src/Path.php) | Class with static methods to deal with filesystem paths
- [PathTrack](src/PathTrack.php) | Class with static methods to deal with filesystem paths (without a starting slash)
- [Request](src/Request.php) | Class with static methods to analyze the current request
- [Res](src/Res.php) | Class with static methods to create and modify resources of all types
- [Response](src/Response.php) | Class with static methods to change the current response
- [Root](src/Root.php) | Class extended by all others
- [Scalar](src/Scalar.php) | Class with static methods to deal with scalar types
- [Segment](src/Segment.php) | Class that provides the logic to replace bracket segment within a string
- [Server](src/Server.php) | Class that provides a set of methods to analyze the current server
- [Session](src/Session.php) | Class with static methods to manage a session (built over the native PHP session functions)
- [Set](src/Set.php) | Class with static methods to deal with set strings (test, test2)
- [Slug](src/Slug.php) | Class with static methods to deal with URI slugs (test-test2)
- [SlugPath](src/SlugPath.php) | Class with static methods to deal with URI slugs within an URI path (test/test2-test3)
- [Sql](src/Sql.php) | Class with static methods to generate SQL strings (compatible with MySQL and MariaDB)
- [Str](src/Str.php) | Class with static methods to work with strings
- [Style](src/Style.php) | Class with static methods to generate an HTML style attribute
- [Superglobal](src/Superglobal.php) | Class with static methods to deal with superglobal variables
- [Symlink](src/Symlink.php) | Class with static methods to manage symlinks
- [Test](src/Test.php) | Class used to create a testsuite for a class
- [Timezone](src/Timezone.php) | Class with static methods to deal with timezone
- [Uri](src/Uri.php) | Class with static methods to generate URI (absolute and relative)
- [Validate](src/Validate.php) | Class that provides validation logic and methods
- [Xml](src/Xml.php) | Class with some static methods related to XML
- [_cacheFile](src/_cacheFile.php) | Trait that provides methodsto get or set a cached value from a file
- [_cacheStatic](src/_cacheStatic.php) | Trait that provides methods to get or set a cached value from a static property
- [_config](src/_config.php) | Trait that provides the logic to recursively merge the $config static property with the parent's property
- [_option](src/_option.php) | Trait that grants static methods to deal with static options (within the $config static property)
- [_root](src/_root.php) | Trait that provides some basic fqcn methods
- [_shortcut](src/_shortcut.php) | Trait that grants static methods to declare and replace shortcuts (bracketed segments within strings)