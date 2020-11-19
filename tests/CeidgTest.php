<?php
declare(strict_types=1);

use Aftermarketpl\CompanyLookup\Env;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use PHPUnit\Framework\TestCase;

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

    public function testPartnetship()
    {
        $response = self::$reader->lookupPartnership('PL6783053210');
        foreach ($response as $companyData) {
            $this->assertTrue($companyData->valid);
        }
    }

    public function testVatIdentifierIsWithoutCountryCode()
    {
        $response = self::$reader->lookup('PL7282697380');
        $vatIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == "vat";
            }
        );
        $this->assertEquals("7282697380", reset($vatIdentifier)->id);
    }

    public function testRepresentatives()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertCount(1, $response->representatives);
        $this->assertEquals("MICHAŁ", $response->representatives[0]->firstName);
        $this->assertEquals("MAZUR", $response->representatives[0]->lastName);
    }
}