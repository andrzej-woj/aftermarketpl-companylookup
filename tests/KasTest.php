<?php
declare(strict_types=1);

use Aftermarketpl\CompanyLookup\KasReader;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use PHPUnit\Framework\TestCase;

final class KasTest extends TestCase
{
    public static $reader = null;

    public static function setUpBeforeClass()
    {
        self::$reader = new KasReader();
    }

    public function testCorrectNip()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertTrue($response->valid);
    }

    public function testCorrectNipByDate()
    {
        $response = self::$reader->lookupByDate(
            'PL7282697380',
            date('Y-m-d', strtotime('-1day'))
        );
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('PL5252389922');
        $this->assertFalse($response->valid);
    }

    public function testEmptyReponse()
    {
        $this->expectExceptionMessage("Empty reponse");
        self::$reader->lookup('PL6422995563');
    }

    public function testAddress()
    {
        $response = self::$reader->lookup('PL7282697380');
        $mainAddress = $response->mainAddress;
        $this->assertNotEmpty($mainAddress);
        $this->assertNotEmpty($mainAddress->address);
        $this->assertNotEmpty($mainAddress->postalCode);
        $this->assertNotEmpty($mainAddress->city);
        $this->assertNotEmpty($mainAddress->country);
    }

    public function testVatIdentifierIsWithoutCountryCode()
    {
        $response = self::$reader->lookup('PL9121874990');
        $vatIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == "vat";
            }
        );
        $this->assertEquals("9121874990", reset($vatIdentifier)->id);
    }

    public function testRepresentatives()
    {
        $response = self::$reader->lookup('PL9121874990');
        $this->assertCount(1, $response->representatives);
        $this->assertEquals("WŁADYSŁAWA", $response->representatives[0]->firstName);
        $this->assertEquals("CYBULAK", $response->representatives[0]->lastName);
    }

    public function testEmptyKas()
    {
        $response = self::$reader->lookup('PL7282697380');
        $vatIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == "krs";
            }
        );

        $this->assertEmpty($vatIdentifier);
    }
}
