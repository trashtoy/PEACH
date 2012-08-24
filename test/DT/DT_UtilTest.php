<?php
require_once 'DT/load.php';

/**
 * Test class for DT_Time.
 */
class DT_TimeTest extends PHPUnit_Framework_TestCase {
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * - 正しく比較が行えることを確認します
     * - 異なる型を比較した場合, 共通のフィールドのみ比較します.
     *   
     * 
     * @todo Implement testCompareTime().
     */
    public function testCompareTime() {
        
    }

    /**
     * @todo Implement testOldest().
     */
    public function testOldest() {
        
    }

    /**
     * @todo Implement testLatest().
     */
    public function testLatest() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>