<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Aftermarketpl\CompanyLookup\Env;

final class CeidgTest extends TestCase
{
    public static $reader = null;
    
    /**
     * Bootstrap VAT reader class
     */
    public static function setUpBeforeClass()
    {
        self::$reader = new Aftermarketpl\CompanyLookup\CeidgReader(Env::$ceidgapikey);
    }

    public function testCorrectNip()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('PL5252389922');
        $this->assertFalse($response->valid);
    }

    public function testMultiCompanies()
    {
        $response = self::$reader->lookup('PL6422995563');
        $this->assertTrue($response->valid);
    }
}