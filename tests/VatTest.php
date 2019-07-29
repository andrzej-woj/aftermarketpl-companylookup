<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class VatTest extends TestCase
{
    public static $reader = null;
    
    /**
     * Bootstrap  reader class
     */
    public static function setUpBeforeClass()
    {
        self::$reader = new Aftermarketpl\CompanyLookup\VatReader();
    }

    public function testCorrectNip()
    {
        $response = self::$reader->lookup('PL5342487137');
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('PL5252389922');
        $this->assertFalse($response->valid);
    }
}