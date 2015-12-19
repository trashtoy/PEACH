PEACH
=====

PHP Extension leading your ACHIEVEMENT.


Features
--------

### Util
Object-oriented array manipulation modules like 
[Java Collections Framework](http://docs.oracle.com/javase/8/docs/technotes/guides/collections/).

- Map interface like java.util.HashMap. You can use objects as key.
- Sorting arrays which contain objects.
- Some other utility classes.

### DT
Object-oriented datetime management API.

- Datetime objects consisting of various scopes. (DATE, DATETIME and TIMESTAMP)
- Easy to sort and compare.
- Library which is designed by immutable classes.
- Loosely-coupled API between datetime manipulation and format/parse.

### Markup
This module helps you to markup HTML or XML dynamically.

- DOM-like usability.
- Various output customization.
- Helper class enables more simple coding.

### DF
Data format encoding / decoding API.
All the classes of this module implement interface Codec.

- Utf8Codec: dealing with the interconversion of unicode codepoints and UTF-8 string
    - example: `'süß'` (byte sequence: 73 C3 BC C3 9F) => decode => `array(0x73, 0xFC, 0xDF)` => encode => `'süß'`
- JsonCodec: alternative of [json_encode](http://php.net/manual/function.json-encode.php) and [json_decode](http://php.net/manual/function.json-decode.php)
- Base64Codec: wrapping [base64_encode](http://php.net/manual/function.base64-encode.php) and [base64_decode](http://php.net/manual/function.base64-decode.php)
- SerializationCodec: wrapping [serialize](http://php.net/manual/function.serialize.php) and [unserialize](http://php.net/manual/function.unserialize.php)
- CodecChain: concatenating multiple Codec instances

Requirements
------------

- PHP 5.1.1 Later

That's all.


How to use
------------
Require autoload.php  
`require_once("/path/to/PEACH/autoload.php");`  
or set up autoload manually.

Documentation
-------------

See docs/index.html. (Japanese version only)

[Online documentation](http://trashtoy.github.io/peach/) is also available.

Roadmap
-------

Package | Description
--------|------------
RB      | Object-oriented i18n module. (RB represents ResourceBundle.)
DB      | A reinvented O/R mapper.
App     | Various components about web application development. (Forms, validations, etc.)

Issues
------

English documentation is not available.

今のところ, 日本語版のドキュメントしか用意していません. 英語版のドキュメントを作成するには

1. 英語版ソースコードのブランチを作成し, コードはそのままでコメント行をすべて英語で書きなおす.
2. 編集したソースコードを phpDocumentor にかけてドキュメント一式を作成.

という手順を踏む必要があります. 英語版のソースコード内コメントを整備してくださる方を募集しています.
