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

Requirements
------------

- PHP 5.1.1 Later

That's all.


How to use
------------
1. Upload 'src' directory to the web server.
2. Include load.php of the module to use. Here is an example.  
   `require_once("/path/to/PEACH/src/Module/load.php");`  
   or  
   `require_once("C:/path/to/PEACH/src/Module/load.php");`

Documentation
-------------

See docs/index.html (Japanese version only.)


Roadmap
-------

Package | Description
--------|------------
Markup  | Assisting output of markup language such as HTML, RSS, XML and so on.
RB      | Object-oriented i18n module. (RB represents ResourceBundle.)


Issues
------

English documentation is not available.

今のところ、日本語版のドキュメントしか用意していません。英語版のドキュメントを作成するには

1. 英語版ソースコードのブランチを作成し、コードはそのままでコメント行をすべて英語で書きなおす
2. 編集したソースコードを phpDocumentor にかけてドキュメント一式を作成

という手順を踏む必要があります。英語版のソースコード内コメントを整備してくださる方を募集しています。