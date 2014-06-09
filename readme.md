PEACH
=====

PHP Extension leading your ACHIEVEMENT.


Features
--------

### Util
Object-oriented array manipulation modules like 
[Java Collections Framework](http://docs.oracle.com/javase/7/docs/technotes/guides/collections/index.html).

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

Requirements
------------

- PHP 5.1.1 Later

That's all.


How to use
------------
Require autoload.php  
`require_once("/path/to/PEACH/src/autoload.php");`  
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
