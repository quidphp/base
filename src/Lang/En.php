<?php
declare(strict_types=1);

/*
 * This file is part of the Quid 5 package | https://quid5.com
 * (c) Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quid5/base/blob/master/LICENSE
 */

namespace Quid\Base\Lang;
use Quid\Base;

// en
class En extends Base\Config
{
	// config
	public static $config = [ 
		// number
		'number'=>[

			// format
			'format'=>[
				'decimal'=>2,
				'separator'=>'.',
				'thousand'=>','
			],

			// moneyFormat
			'moneyFormat'=>[
				'decimal'=>2,
				'separator'=>'.',
				'thousand'=>',',
				'output'=>'$%v%'
			],

			// percentFormat
			'percentFormat'=>[
				'decimal'=>0,
				'separator'=>'.',
				'thousand'=>'',
				'output'=>'%v%%'
			],

			// phoneFormat
			'phoneFormat'=>[
				'parenthesis'=>true,
				'extension'=>'#'
			],

			// sizeFormat
			'sizeFormat'=>[
				'round'=>[
					0=>0,
					1=>0,
					2=>1,
					3=>2,
					4=>2,
					5=>3],
				'text'=>[
					0=>'Byte',
					1=>'KB',
					2=>'MB',
					3=>'GB',
					4=>'TB',
					5=>'PB']
				]
		],

		// date
		'date'=>[

			// format
			'format'=>[
				0=>'F j, Y',
				1=>'F j, Y H:i:s',
				2=>'F Y',
				3=>'m-d-Y',
				4=>'m-d-Y H:i:s',
				'dateToDay'=>'m-d-Y',
				'dateToMinute'=>'m-d-Y H:i',
				'dateToSecond'=>'m-d-Y H:i:s',
				'short'=>'F j, Y',
				'long'=>'F j, Y H:i:s',
				'calendar'=>'F Y'
			],

			// placeholder
			'placeholder'=>[
				'dateToDay'=>'MM-DD-YYYY',
				'dateToMinute'=>'MM-DD-YYYY HH:MM',
				'dateToSecond'=>'MM-DD-YYYY HH:MM:SS'
			],

			// str
			'str'=>[
				'year'=>'year',
				'month'=>'month',
				'day'=>'day',
				'hour'=>'hour',
				'minute'=>'minute',
				'second'=>'second',
				'and'=>'and'
			],

			// month
			'month'=>[
				1=>'January',
				2=>'February',
				3=>'March',
				4=>'April',
				5=>'May',
				6=>'June',
				7=>'July',
				8=>'August',
				9=>'September',
				10=>'October',
				11=>'November',
				12=>'December'
			],

			// dayShort
			'dayShort'=>[
				0=>'Su',
				1=>'Mo',
				2=>'Tu',
				3=>'We',
				4=>'Th',
				5=>'Fr',
				6=>'Sa'
			],

			// day
			'day'=>[
				0=>'Sunday',
				1=>'Monday',
				2=>'Tuesday',
				3=>'Wednesday',
				4=>'Thursday',
				5=>'Friday',
				6=>'Saturday'
			]
		],

		// header
		'header'=>[

			// responseStatus
			'responseStatus'=>[
				100=>'Continue',
				101=>'Switching Protocols',
				200=>'OK',
				201=>'Created',
				202=>'Accepted',
				203=>'Non-Authoritative Information',
				204=>'No Content',
				205=>'Reset Content',
				206=>'Partial Content',
				207=>'Multi-Status',
				208=>'Already Reported',
				226=>'IM Used',
				300=>'Multiple Choices',
				301=>'Moved Permanently',
				302=>'Found',
				303=>'See Other',
				304=>'Not Modified',
				305=>'Use Proxy',
				306=>'Switch Proxy',
				307=>'Temporary Redirect',
				308=>'Permanent Redirect',
				400=>'Bad Request',
				401=>'Unauthorized',
				402=>'Payment Required',
				403=>'Forbidden',
				404=>'Not Found',
				405=>'Method Not Allowed',
				406=>'Not Acceptable',
				407=>'Proxy Authentication Required',
				408=>'Request Time-out',
				409=>'Conflict',
				410=>'Gone',
				411=>'Length Required',
				412=>'Precondition Failed',
				413=>'Payload Too Large',
				414=>'URI Too Long',
				415=>'Unsupported Media Type',
				416=>'Requested Range Not Satisfiable',
				417=>'Expectation Failed',
				418=>"I'm a teapot",
				421=>'Misdirected Request',
				422=>'Unprocessable Entity',
				423=>'Locked',
				424=>'Failed Dependency',
				425=>'Too early',
				426=>'Upgrade required',
				428=>'Precondition Required',
				429=>'Too Many Requests',
				431=>'Request Header Fields Too Large',
				451=>'Unavailable For Legal Reasons',
				500=>'Internal Server Error',
				501=>'Not Implemented',
				502=>'Bad Gateway',
				503=>'Service Unavailable',
				504=>'Gateway Time-out',
				505=>'HTTP Version not supported',
				506=>'Variant Also Negotiates',
				507=>'Insufficient Storage',
				508=>'Loop Detected',
				510=>'Not Extended',
				511=>'Network Authentication Required'
			]
		],

		// error
		'error'=>[
			'code'=>[
				E_ERROR=>'E_ERROR',
				E_WARNING=>'E_WARNING',
				E_PARSE=>'E_PARSE',
				E_NOTICE=>'E_NOTICE',
				E_CORE_ERROR=>'E_CORE_ERROR',
				E_CORE_WARNING=>'E_CORE_WARNING',
				E_COMPILE_ERROR=>'E_COMPILE_ERROR',
				E_COMPILE_WARNING=>'E_COMPILE_WARNING',
				E_USER_ERROR=>'E_USER_ERROR',
				E_USER_WARNING=>'E_USER_WARNING',
				E_USER_NOTICE=>'E_USER_NOTICE',
				E_STRICT=>'E_STRICT',
				E_RECOVERABLE_ERROR=>'E_RECOVERABLE_ERROR',
				E_DEPRECATED=>'E_DEPRECATED',
				E_USER_DEPRECATED=>'E_USER_DEPRECATED',
				E_ALL=>'E_ALL'
			]
		],

		// validate
		'validate'=>[
			'array'=>'Must be an array',
			'bool'=>'Must be a boolean',
			'callable'=>'Must be callable',
			'float'=>'Must be a floating number',
			'int'=>'Must be an integer',
			'numeric'=>'Must be numeric',
			'null'=>'Must be null',
			'object'=>'Must be an object',
			'resource'=>'Must be a resource',
			'scalar'=>'Must be scalar',
			'string'=>'Must be a string',
			'instance'=>'Must be an instance of [%]',
			'closure'=>'Must validate the closure',
			'empty'=>'Must be empty',
			'notEmpty'=>'Cannot be empty',
			'reallyEmpty'=>'Must be empty (0 allowed)',
			'notReallyEmpty'=>'Cannot be empty (0 allowed)',
			'arrKey'=>'Must be an array key',
			'arrNotEmpty'=>'Must be a non-empty array',
			'dateToDay'=>'Must be a valid date (MM-DD-YYYY)',
			'dateToMinute'=>'Must be a valid date with time (MM-DD-YYYY HH:MM)',
			'dateToSecond'=>'Must be a valid date with time (MM-DD-YYYY HH:MM:SS)',
			'numberNotEmpty'=>'Must be a non-empty number',
			'numberPositive'=>'Must be a positive number',
			'numberNegative'=>'Must be a negative number',
			'numberOdd'=>'Must be an odd number',
			'numberEven'=>'Must be an even number',
			'numberWhole'=>'Must be an whole number',
			'numberWholeNotEmpty'=>'Must be an whole number not empty',
			'numberDecimal'=>'Must be a decimal number',
			'scalarNotBool'=>'Must be scalar but not boolean',
			'slug'=>'Must be an uri slug',
			'slugPath'=>'Must be an uri slug-path',
			'fragment'=>'Must be an uri fragment',
			'strLatin'=>'Must be a string only with latin characters',
			'strNotEmpty'=>'Must be a non-empty string',
			'uriRelative'=>'Must be a relative uri',
			'uriAbsolute'=>'Must be an absolute uri (http://xyz.com/xyz)',
			'length'=>'Length must be [%] character%s%',
			'minLength'=>'Length must be at minimum [%] character%s%',
			'maxLength'=>'Length must be at maximum [%] character%s%',
			'arrCount'=>'Array count must be [%]',
			'arrMinCount'=>'Array count must be at minimum [%]',
			'arrMaxCount'=>'Array count must be at maximum [%]',
			'dateFormat'=>'Must be date format [%]',
			'fileCount'=>'File count must be [%]',
			'fileMinCount'=>'File count must be at minimum [%]',
			'fileMaxCount'=>'File count must be at maximum [%]',
			'numberLength'=>'Number length must be [%]',
			'numberMinLength'=>'Number length must be at minimum [%]',
			'numberMaxLength'=>'Number length must be at maximum [%]',
			'jsonCount'=>'Must contain [%]',
			'jsonMinCount'=>'Must contain at minimum [%]',
			'jsonMaxCount'=>'Must contain at maximum [%]',
			'setCount'=>'Must contain [%]',
			'setMinCount'=>'Must contain at minimum [%]',
			'setMaxCount'=>'Must contain at maximum [%]',
			'setLength'=>'String length must be [%]',
			'strMinLength'=>'String length must be at minimum [%]',
			'strMaxLength'=>'String length must be at maximum [%]',
			'uriHost'=>'Domain must be [%]',
			'alpha'=>'Must be only alpha (A-z)',
			'alphanumeric'=>'Must be alphanumeric (A-z 0-9)',
			'alphanumericSlug'=> 'Must be alphanumeric (A-z 0-9 _-)',
			'alphanumericPlus'=> 'Must be alphanumeric (a-z 0-9 _-.@)',
			'alphanumericPlusSpace'=> 'Must be alphanumeric, space allowed (a-z 0-9 _-.@)',
			'username'=>'Must be a valid username with at least 4 characters (a-z 0-9 _-)',
			'usernameLoose'=>'Must be a valid username with at least 4 characters (a-z 0-9 _-@)',
			'password'=>'Must be a password with a letter, a number and at least 5 characters long.',
			'passwordLoose'=>'Must be a password at least 4 characters long.',
			'passwordHash'=>'Must be a password with a letter, a number and at least 5 characters long.',
			'passwordHashLoose'=>'Must be a password at least 4 characters long.',
			'email'=>'Must be a valid email (x@x.com)',
			'hex'=>'Must be a valid HEX code (ffffff)',
			'tag'=>'Must be a valid HTML tag (&lt;tag&gt;&lt;/tag&gt;)',
			'year'=>'Must be a valid year (YYYY)',
			'americanZipcode'=>'Must be a valid american zipcode (11111)',
			'canadianPostalcode'=>'Must be a valid canadian postal code (X1X1X1)',
			'northAmericanPhone'=>'Must be a valid north american phone number (111-111-1111)',
			'phone'=>'Must be a valid phone number ',
			'ip'=>'Must be a valid IP (1.2.3.4)',
			'date'=>'Must be a valid date (YYYY-MM-DD)',
			'datetime'=>'Must be a valid datetime (YYYY-MM-DD HH:MM:SS)',
			'time'=>'Must be a valid time (HH:MM:SS)',
			'path'=>'Must be a valid path (A-a 0-9 _-./*)',
			'fqcn'=>'Must be a valid FQCN (\)',
			'table'=>'Must be a valid table name (A-z 0-9 _)',
			'col'=>'Must be a valid col name (A-z 0-9 _)',
			'='=>'Must be equal to [%]',
			'=='=>'Must be equal to [%]',
			'==='=>'Must be equal to [%]',
			'>'=>'Must be larger than [%]',
			'>='=>'Must be equal or larger than [%]',
			'<'=>'Must be smaller than [%]',
			'<='=>'Must be equal or smaller than [%]',
			'!'=>'Must be different than [%]',
			'!='=>'Must be different than [%]',
			'!=='=>'Must be different than [%]',
			'fileUpload'=>'Must be an uploaded file',
			'fileUploads'=>'Must be one or many uploaded files',
			'fileUploadInvalid'=>'The file upload array is invalid.',
			'fileUploadSizeIni'=>'The size of the uploaded file is too big. See PHP Ini.',
			'fileUploadSizeForm'=>'The size of the uploaded file is too big. See the form.',
			'fileUploadPartial'=>'The file upload was partial. Please retry.',
			'fileUploadSizeEmpty'=>'The uploaded file is empty.',
			'fileUploadTmpDir'=>'Servor error: no temporary folder.',
			'fileUploadWrite'=>'Cannot write the uploaded file on the server.',
			'fileUploadExists'=>'The temporary uploaded file was not found.',
			'maxFilesize'=>'The uploaded file size must be smaller than [%]',
			'maxFilesizes'=>'The uploaded file(s) size must be smaller than [%]',
			'extension'=>'The extension of the file must be: [%]',
			'extensions'=>'The extension of the file(s) must be: [%]'
		],

		// required
		'required'=>[
			'common'=>'Cannot be empty'
		],

		// unique
		'unique'=>[
			'common'=>'Must be unique[%]'
		],

		// editable
		'editable'=>[
			'common'=>'Cannot be modified'
		],

		// compare
		'compare'=>[
			'='=>'Must be equal to [%]',
			'=='=>'Must be equal to [%]',
			'==='=>'Must be equal to [%]',
			'>'=>'Must be larger than [%]',
			'>='=>'Must be equal or larger than [%]',
			'<'=>'Must be smaller than [%]',
			'<='=>'Must be equal or smaller than [%]',
			'!'=>'Must be different than [%]',
			'!='=>'Must be different than [%]',
			'!=='=>'Must be different than [%]'
		]
	];
}
?>