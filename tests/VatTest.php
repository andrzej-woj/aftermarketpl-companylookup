<?php
declare(strict_types=1);

use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
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
        $response = self::$reader->lookup('7282697380');
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('5252389922');
        $this->assertFalse($response->valid);
    }

    public function testVatIdentifierIsWithoutCountryCode()
    {
        $response = self::$reader->lookup('7282697380');
        $vatIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == "vat";
            }
        );
        $this->assertEquals("7282697380", reset($vatIdentifier)->id);
    }
}