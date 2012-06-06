PEACH
=====

PHP Extension leading your ACHIEVEMENT.


Features
--------

### Util
Object-oriented array manipulation modules like 
[Java Collections Framework] (http://docs.oracle.com/javase/7/docs/technotes/guides/collections/index.html).

- Map interface like java.util.HashMap. You can use objects as key.
- sorting arrays which contain objects.
- Some other utility classes.


Requirements
------------

- PHP 5.1.1 Later

That's all.


Installation
------------
1. Place the 'src' directory in the Web server.
2. Add 'src' directory to include path in your PHP.  
   `set_include_path(get_include_path() . PATH_SEPARATOR . "/path/to/PEACH/src");`  
   or  
   `set_include_path(get_include_path() . PATH_SEPARATOR . "C:/path/to/PEACH/src");`


Documentation
-------------

See docs/index.html (Japanese version only.)


Roadmap
-------

Package | Description
--------|------------
DT      | Object-oriented date-time management module.
Markup  | Assisting output of markup language such as HTML or XML.
RB      | Object-oriented i18n module. (RB represents ResourceBundle.)


Issues
------

English documentation is not available.

今のところ、日本語版のドキュメントしか用意していません。英語版のドキュメントを作成するには

1. 英語版ソースコードのブランチを作成し、コードはそのままでコメント行をすべて英語で書きなおす
2. 編集したソースコードを phpDocumentor にかけてドキュメント一式を作成

という手順を踏む必要があります。英語版のソースコードを整備してくださる方を募集しています。