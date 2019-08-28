# Quid\Base

## About
**Quid\Base** is a low-level library of static methods for PHP. It is part of the QuidPHP framework and CMS but it can be used standalone. This library requires PHP 7.2+. It is available as an open-source software under the [MIT license](LICENSE).

## Composer
**Quid\Base** can be installed through [Composer](https://getcomposer.org). 
``` bash
$ composer require quidphp/base
```

## Testing
**Quid\Base** testsuite can bu runned by creating a new [Quid\Project](https://github.com/quidphp/project). All tests and assertions are part of the [Quid\Test](https://github.com/quidphp/test) repository.

## Convention
**Quid\Base** is built on the following conventions:
- Type: Files, arguments and return types are strict typed.
- Filename: Traits filenames start with an underscore _.
- Coding: No curly braces are used in a IF statement if the condition can be resolved in only one statement.
- Config: A special $config static property exists in all classes. This property gets recursively merged with the parents' property on initialization.

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
