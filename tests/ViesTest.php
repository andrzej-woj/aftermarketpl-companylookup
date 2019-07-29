<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ViesTest extends TestCase
{
    public static $reader = null;
    
    /**
     * Bootstrap reader class
     */
    public static function setUpBeforeClass()
    {
        self::$reader = new Aftermarketpl\CompanyLookup\ViesReader();
    }

    public function testCorrectNip()
    {
        $response = self::$reader->lookup('PL9121875009');
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('PL5252389922');
        $this->assertFalse($response->valid);
    }

    public function testInvalidCountry()
    {
        $this->expectException(Aftermarketpl\CompanyLookup\Exceptions\ValidatorException::class);
        self::$reader->lookup('XX6783041098');
    }

    public function testValidES()
    {
        $response = self::$reader->lookup('ESB64724131');
        $this->assertTrue($response->valid);
    }

    public function testValidEL()
    {
        $response = self::$reader->lookup('EL036719745');
        $this->assertTrue($response->valid);
    }
    
    public function testValidIE()
    {
        $response = self::$reader->lookup('IE3232319JH');
        $this->assertTrue($response->valid);
    }
}