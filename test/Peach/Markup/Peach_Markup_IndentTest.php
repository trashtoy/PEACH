<?php
class Peach_Markup_IndentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Indent
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_Indent();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * getLevel(), stepUp(), stepDown() のテストです. 以下を確認します.
     * 
     * - 初期化時点では, コンストラクタに指定したレベルを返すこと
     * - 引数なしで初期化した場合は 0 を返すこと
     * - stepUp() を行うたびに level が 1 ずつ増加すること
     * - stepDown() を行うたびに level が 1 ずつ減少すること
     * 
     * @covers Peach_Markup_Indent::__construct
     * @covers Peach_Markup_Indent::getLevel
     * @covers Peach_Markup_Indent::stepUp
     * @covers Peach_Markup_Indent::stepDown
     */
    public function testGetLevelAndStepUpAndStepDown()
    {
        $i1 = new Peach_Markup_Indent(3);
        $this->assertSame(3, $i1->getLevel());
        
        $i2 = $this->object;
        $this->assertSame(0, $i2->getLevel());
        
        $i3 = new Peach_Markup_Indent(- 5);
        for ($i = 0; $i < 12; $i++) {
            $i3->stepUp();
        }
        $this->assertSame(7, $i3->getLevel());
        for ($i = 0; $i < 5; $i++) {
            $i3->stepDown();
        }
        $this->assertSame(2, $i3->getLevel());
    }
    
    /**
     * getUnit() のテストです. 以下を確認します.
     * - コンストラクタに指定した文字列を返すこと
     * - コンストラクタ引数を省略した場合は, 半角スペース 4 文字を返すこと
     * @covers Peach_Markup_Indent::getUnit
     */
    public function testGetUnit()
    {
        $i1 = new Peach_Markup_Indent(0, Peach_Markup_Indent::TAB);
        $this->assertSame("\t", $i1->getUnit());
        
        $i2 = $this->object;
        $this->assertSame("    ", $i2->getUnit());
    }
    
    /**
     * indent() のテストです. 以下を確認します.
     * 
     * - unit に指定された文字列を, 現在の level の回数だけ繰り返した文字列を返すこと
     * - 現在の level が 0 以下の場合は空文字列を返すこと
     * 
     * @covers Peach_Markup_Indent::indent
     * @covers Peach_Markup_Indent::handleIndent
     */
    public function testIndent()
    {
        $i1 = new Peach_Markup_Indent(3, "  ");
        $this->assertSame("      ", $i1->indent());
        
        $i2 = new Peach_Markup_Indent(0, "  ");
        $this->assertSame("", $i2->indent());
        
        $i3 = new Peach_Markup_Indent(-3, "  ");
        $this->assertSame("", $i3->indent());
    }
    
    /**
     * breakCode() のテストです. 以下を確認します.
     * 
     * - コンストラクタに指定した文字列を返すこと
     * - コンストラクタ引数を省略した場合は, CRLF を返すこと
     * 
     * @covers Peach_Markup_Indent::breakCode
     */
    public function testBreakCode()
    {
        $i1 = new Peach_Markup_Indent(0, Peach_Markup_Indent::TAB, Peach_Markup_Indent::LF);
        $this->assertSame("\n", $i1->breakCode());
        
        $i2 = $this->object;
        $this->assertSame("\r\n", $i2->breakCode());
    }
}
