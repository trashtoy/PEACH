<?php
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_HtmlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Html
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        Peach_Markup_Html::init();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * init() のテストです. 以下を確認します.
     * 
     * - 引数に true を指定した場合は XHTML 形式, false を指定した場合は HTML 形式のグローバル Helper が初期化されること
     * - 引数を省略した場合は HTML 形式で初期化されること
     * 
     * @covers Peach_Markup_Html::init
     */
    public function testInit()
    {
        $xr = Peach_Markup_XmlRenderer::getInstance();
        $sr = Peach_Markup_SgmlRenderer::getInstance();
        
        Peach_Markup_Html::init(true);
        $b1 = Peach_Markup_Html::getBuilder();
        $this->assertSame($xr, $b1->getRenderer());
        
        Peach_Markup_Html::init(false);
        $b2 = Peach_Markup_Html::getBuilder();
        $this->assertSame($sr, $b2->getRenderer());
        
        Peach_Markup_Html::init();
        $b3 = Peach_Markup_Html::getBuilder();
        $this->assertSame($sr, $b3->getRenderer());
    }
    
    /**
     * getHelper() のテストです.
     * @covers Peach_Markup_Html::getHelper
     */
    public function testGetHelper()
    {
        $breakControl   = new Peach_Markup_NameBreakControl(
            array("html", "head", "body", "ul", "ol", "dl", "table"),
            array("pre", "code", "textarea")
        );
        $emptyNodeNames = array("area", "base", "basefont", "br", "col", "command", "embed", "frame", "hr", "img", "input", "isindex", "keygen", "link", "meta", "param", "source", "track", "wbr");
        
        $b1  = new Peach_Markup_DefaultBuilder();
        $b1->setBreakControl($breakControl);
        $b1->setRenderer("HTML");
        $ex1 = new Peach_Markup_Helper($b1, $emptyNodeNames);
        Peach_Markup_Html::init();
        $this->assertEquals($ex1, Peach_Markup_Html::getHelper());
        
        $b2  = new Peach_Markup_DefaultBuilder();
        $b2->setBreakControl($breakControl);
        $b2->setRenderer("XHTML");
        $ex2 = new Peach_Markup_Helper($b2, $emptyNodeNames);
        Peach_Markup_Html::init(true);
        $this->assertEquals($ex2, Peach_Markup_Html::getHelper());
    }
    
    /**
     * getBuilder() のテストです.
     * 返り値の Builder に対する変更が, グローバル Helper に適用されることを確認します.
     * @covers Peach_Markup_Html::getBuilder
     */
    public function testGetBuilder()
    {
        $builder = Peach_Markup_Html::getBuilder();
        $builder->setRenderer("html");
        $builder->setIndent(new Peach_Markup_Indent(0, "  ", Peach_Markup_Indent::LF));
        $result  = Peach_Markup_Html::tag(Peach_Markup_TestUtil::getTestNode())->write();
        $this->assertSame(Peach_Markup_TestUtil::getCustomBuildResult(), $result);
    }
    
    /**
     * tag() のテストです. 引数によって, 生成される HelperObject が以下の Component をラップすることを確認します.
     * - 文字列の場合: 引数を要素名に持つ Element
     * - null または引数なしの場合: 空の NodeList
     * - Node オブジェクトの場合: 引数の Node 自身
     * 
     * また, HTML4.01 および最新の HTML5 の勧告候補 (2014-02-04 時点) の仕様を元に,
     * 以下の要素が「空要素」として生成されることを確認します.
     * - HTML4.01: area, base, basefont, br, col, frame, hr, img, input, isindex, link, meta, param
     * - HTML5: area, base, br, col, command, embed, hr, img, input, keygen, link, meta, param, source, track, wbr
     * 
     * @covers Peach_Markup_Html::tag
     */
    public function testTag()
    {
        $containerExamples = array("html", "body", "div", "span");
        foreach ($containerExamples as $name) {
            $obj = Peach_Markup_Html::tag($name);
            $this->assertInstanceOf("Peach_Markup_ContainerElement", $obj->getNode());
        }
        
        $nodeList  = new Peach_Markup_NodeList();
        $obj2      = Peach_Markup_Html::tag(null);
        $this->assertEquals($nodeList, $obj2->getNode());
        $obj3      = Peach_Markup_Html::tag();
        $this->assertEquals($nodeList, $obj3->getNode());
        
        $text      = new Peach_Markup_Text("SAMPLE TEXT");
        $obj4      = Peach_Markup_Html::tag($text);
        $this->assertSame($text, $obj4->getNode());
        
        $emptyHtml4 = array("area", "base", "basefont", "br", "col", "frame", "hr", "img", "input", "isindex", "link", "meta", "param");
        $emptyHtml5 = array("area", "base", "br", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr");
        $emptyList  = array_unique(array_merge($emptyHtml4, $emptyHtml5));
        foreach ($emptyList as $name) {
            $obj = Peach_Markup_Html::tag($name);
            $this->assertInstanceOf("Peach_Markup_EmptyElement", $obj->getNode());
        }
    }
    
    /**
     * comment() のテストです. 以下を確認します.
     * 
     * - 返り値の HelperObject が Comment ノードをラップしていること
     * - 第 1 引数に指定した値が, ラップしている Comment オブジェクトの子ノードになること
     * - 第 2, 第3 引数でコメントの接頭辞・接尾辞を指定できること
     * 
     * @covers Peach_Markup_Html::comment
     */
    public function testComment()
    {
        $c1  = Peach_Markup_Html::comment("SAMPLE COMMENT");
        $this->assertInstanceOf("Peach_Markup_Comment", $c1->getNode());
        $this->assertSame("<!--SAMPLE COMMENT-->", $c1->write());
        
        $obj = Peach_Markup_Html::tag("div")
                ->append(Peach_Markup_Html::tag("h1")->append("TEST"))
                ->append(Peach_Markup_Html::tag("p")->append("The Quick Brown Fox Jumps Over The Lazy Dogs"));
        $ex1 = implode("\r\n", array(
            "<!--",
            "<div>",
            "    <h1>TEST</h1>",
            "    <p>The Quick Brown Fox Jumps Over The Lazy Dogs</p>",
            "</div>",
            "-->",
        ));
        $c2  = Peach_Markup_Html::comment($obj);
        $this->assertSame($ex1, $c2->write());
        
        $ex2 = implode("\r\n", array(
            "<!--[start]",
            "<div>",
            "    <h1>TEST</h1>",
            "    <p>The Quick Brown Fox Jumps Over The Lazy Dogs</p>",
            "</div>",
            "[end]-->",
        ));
        $c3  = Peach_Markup_Html::comment($obj, "[start]", "[end]");
        $this->assertSame($ex2, $c3->write());
    }
    
    /**
     * conditionalComment() のテストです. 以下を確認します.
     * 
     * - 第 1 引数に指定された条件付きコメントの中に, 第 2 引数に定された文字列またはノードが含まれること
     * - 第 2 引数を省略した場合, 空の条件付きコメントが生成されること
     * 
     * @covers Peach_Markup_Html::conditionalComment
     */
    public function testConditionalComment()
    {
        $ex1 = "<!--[if lt IE 7]>He died on April 9, 2014.<![endif]-->";
        $c1  = Peach_Markup_Html::conditionalComment("lt IE 7", "He died on April 9, 2014.");
        $this->assertSame($ex1, $c1->write());
        
        $ex2 = implode("\r\n", array(
            "<!--[if IE 9]>",
            "<div>",
            "    <h1>TEST</h1>",
            "    <p>The Quick Brown Fox Jumps Over The Lazy Dogs</p>",
            "</div>",
            "<![endif]-->",
        ));
        $obj = Peach_Markup_Html::tag("div")
                ->append(Peach_Markup_Html::tag("h1")->append("TEST"))
                ->append(Peach_Markup_Html::tag("p")->append("The Quick Brown Fox Jumps Over The Lazy Dogs"));
        $c2  = Peach_Markup_Html::conditionalComment("IE 9", $obj);
        $this->assertSame($ex2, $c2->write());
        
        $ex3 = "<!--[if IE]><![endif]-->";
        $c3  = Peach_Markup_Html::conditionalComment("IE");
        $this->assertSame($ex3, $c3->write());
    }
    
    /**
     * createSelectElement() のテストです.
     * 以下を確認します.
     * 
     * - 第 1 引数をデフォルト選択肢, 第 2 引数を項目の一覧とする select 要素を返すこと
     * - 第 2 引数の値に配列が含まれる場合, その項目が optgroup になること
     * - 第 3 引数で select 要素の属性を指定できること
     * 
     * @covers Peach_Markup_Html::createSelectElement
     */
    public function testCreateSelectElement()
    {
        $candidates = array(
            "Apple"  => 1,
            "Orange" => 2,
            "Pear"   => 3,
            "Peach"  => 4,
        );
        $select1    = Peach_Markup_Html::createSelectElement(null, $candidates);
        $this->assertEquals($this->createSampleSelectNode(false, false), $select1);
        $select2    = Peach_Markup_Html::createSelectElement("4", $candidates);
        $this->assertEquals($this->createSampleSelectNode(true, false),  $select2);
        $select3    = Peach_Markup_Html::createSelectElement("", $candidates, array("id" => "test", "name" => "favorite"));
        $this->assertEquals($this->createSampleSelectNode(false, true), $select3);
        
        $candidates2 = array(
            "Fruit"   => $candidates,
            "Dessert" => array(
                "Chocolate" => 5,
                "Doughnut"  => 6,
                "Ice cream" => 7,
            ),
            "Others" => 8,
        );
        $select4    = Peach_Markup_Html::createSelectElement(6, $candidates2);
        $builder    = new Peach_Markup_DefaultBuilder();
        $this->assertEquals($this->createSampleSelectNode2(), $select4);
    }
    
    /**
     * @param  bool $selectFlag
     * @param  bool $attrFlag
     * @return Peach_Markup_ContainerElement
     */
    private function createSampleSelectNode($selectFlag, $attrFlag)
    {
        $select = new Peach_Markup_ContainerElement("select");
        
        $opt1   = new Peach_Markup_ContainerElement("option");
        $opt1->setAttribute("value", "1");
        $opt1->append("Apple");
        $select->append($opt1);
        
        $opt2   = new Peach_Markup_ContainerElement("option");
        $opt2->setAttribute("value", "2");
        $opt2->append("Orange");
        $select->append($opt2);
        
        $opt3   = new Peach_Markup_ContainerElement("option");
        $opt3->setAttribute("value", "3");
        $opt3->append("Pear");
        $select->append($opt3);
        
        $opt4   = new Peach_Markup_ContainerElement("option");
        $opt4->setAttribute("value", "4");
        $opt4->append("Peach");
        $select->append($opt4);
        if ($selectFlag) {
            $opt4->setAttribute("selected");
        }
        
        if ($attrFlag) {
            $select->setAttributes(array("id" => "test", "name" => "favorite"));
        }
        return $select;
    }
    
    /**
     * @return Peach_Markup_ContainerElement
     */
    private function createSampleSelectNode2()
    {
        $select = new Peach_Markup_ContainerElement("select");
        
        $grp1   = new Peach_Markup_ContainerElement("optgroup");
        $grp1->setAttribute("label", "Fruit");
        $select->append($grp1);
        $grp2   = new Peach_Markup_ContainerElement("optgroup");
        $grp2->setAttribute("label", "Dessert");
        $select->append($grp2);
        $other  = new Peach_Markup_ContainerElement("option");
        $other->setAttribute("value", "8");
        $other->append("Others");
        $select->append($other);
        
        $opt1   = new Peach_Markup_ContainerElement("option");
        $opt1->setAttribute("value", "1");
        $opt1->append("Apple");
        $grp1->append($opt1);
        
        $opt2   = new Peach_Markup_ContainerElement("option");
        $opt2->setAttribute("value", "2");
        $opt2->append("Orange");
        $grp1->append($opt2);
        
        $opt3   = new Peach_Markup_ContainerElement("option");
        $opt3->setAttribute("value", "3");
        $opt3->append("Pear");
        $grp1->append($opt3);
        
        $opt4   = new Peach_Markup_ContainerElement("option");
        $opt4->setAttribute("value", "4");
        $opt4->append("Peach");
        $grp1->append($opt4);
        
        $opt5   = new Peach_Markup_ContainerElement("option");
        $opt5->setAttribute("value", "5");
        $opt5->append("Chocolate");
        $grp2->append($opt5);
        
        $opt6   = new Peach_Markup_ContainerElement("option");
        $opt6->setAttribute("value", "6");
        $opt6->setAttribute("selected");
        $opt6->append("Doughnut");
        $grp2->append($opt6);
        
        $opt7   = new Peach_Markup_ContainerElement("option");
        $opt7->setAttribute("value", "7");
        $opt7->append("Ice cream");
        $grp2->append($opt7);
        
        return $select;
    }
    
    /**
     * select() のテストです. createSelectElement() の結果をラップした
     * HelperObject を返すことを確認します.
     * 
     * @covers Peach_Markup_Html::seect
     */
    public function testSelect()
    {
        $candidates = array(
            "Fruit"   => array(
                "Apple"  => 1,
                "Orange" => 2,
                "Pear"   => 3,
                "Peach"  => 4,
            ),
            "Dessert" => array(
                "Chocolate" => 5,
                "Doughnut"  => 6,
                "Ice cream" => 7,
            ),
            "Others" => 8,
        );
        $expected = implode("\r\n", array(
            '<select name="favorite">',
            '    <optgroup label="Fruit">',
            '        <option value="1">Apple</option>',
            '        <option value="2">Orange</option>',
            '        <option value="3">Pear</option>',
            '        <option value="4">Peach</option>',
            '    </optgroup>',
            '    <optgroup label="Dessert">',
            '        <option value="5">Chocolate</option>',
            '        <option value="6" selected>Doughnut</option>',
            '        <option value="7">Ice cream</option>',
            '    </optgroup>',
            '    <option value="8">Others</option>',
            '</select>',
        ));
        $select = Peach_Markup_Html::select(6, $candidates, array("name" => "favorite"));
        $this->assertInstanceOf("Peach_Markup_HelperObject", $select);
        $this->assertSame($expected, $select->write());
    }
    
    /**
     * alias() のテストです. 以下を確認します.
     * 
     * - 引数を省略した場合は array("tag" => "tag") と同様の結果になること
     * - 引数に指定した内容でエイリアスを定義できること
     */
    public function testAlias()
    {
        $this->assertFalse(function_exists("tag"));
        Peach_Markup_Html::alias();
        $this->assertTrue(function_exists("tag"));
        $this->assertSame("<p>Hello World!</p>", tag("p")->append("Hello World!")->write());

        $aliasList = array("t", "c", "cond", "s");
        foreach ($aliasList as $name) {
            $this->assertFalse(function_exists($name));
        }
        Peach_Markup_Html::alias(array("tag" => "t", "comment" => "c", "conditionalComment" => "cond", "select" => "s"));
        foreach ($aliasList as $name) {
            $this->assertTrue(function_exists($name));
        }
        $ex1 = implode("\r\n", array(
            '<div>',
            '    <!--TEST-->',
            '    <p>Hello World!</p>',
            '    <!--[if lt IE 9]>',
            '    <script src="ieonly.js"></script>',
            '    <![endif]-->',
            '    <select name="foo">',
            '        <option value="1">A</option>',
            '        <option value="2" selected>B</option>',
            '        <option value="3">C</option>',
            '    </select>',
            '</div>',
        ));
        $t = t("div")
            ->append(c("TEST"))
            ->append(t("p")->append("Hello World!"))
            ->append(cond("lt IE 9", t("script")->attr("src", "ieonly.js")))
            ->append(
                s(2, array("A" => 1, "B" => 2, "C" => 3), array("name" => "foo"))
            );
        $this->assertSame($ex1, $t->write());
    }
    
    /**
     * alias() の引数で既に存在する関数名を指定した場合,
     * InvalidArgumentException をスローすることを確認します.
     * @expectedException InvalidArgumentException
     */
    public function testAliasByAlreadyDefinedName()
    {
        $this->assertFalse(function_exists("t1"));
        Peach_Markup_Html::alias(array("tag" => "t1"));
        $this->assertTrue(function_exists("t1"));
        Peach_Markup_Html::alias(array("tag" => "t1"));
    }
    
    /**
     * alias() の引数に関数名として使用できない名前を指定した場合,
     * InvalidArgumentException をスローすることを確認します.
     */
    public function testAliasByInvalidName()
    {
        $invalidList = array(
            "123invalid",
            "hogehoge()",
            "bad-function",
        );
        foreach ($invalidList as $name) {
            try {
                Peach_Markup_Html::alias(array("tag" => $name));
                $this->fail();
            } catch (InvalidArgumentException $e) {}
        }
    }
    
    /**
     * alias() の引数に存在しないメソッド名を指定した場合,
     * InvalidArgumentException をスローすることを確認します.
     * @expectedException InvalidArgumentException
     */
    public function testAliasByUndefinedMethod()
    {
        Peach_Markup_Html::alias(array("tag" => "valid1", "hogehoge" => "invalid1"));
    }
}
