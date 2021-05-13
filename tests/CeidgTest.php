<?php
declare(strict_types=1);

use Aftermarketpl\CompanyLookup\Env;
use Aftermarketpl\CompanyLookup\IdentifierType;
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
        $response = self::$reader->lookup('7282697380');
        $this->assertTrue($response->valid);
    }

    public function testCorrectRegon()
    {
        $response = self::$reader->lookupPartnership('382365180', IdentifierType::REGON);
        $this->assertCount(2, $response);
        $this->assertTrue($response[0]->valid);
        $this->assertTrue($response[1]->valid);
    }

    public function testInvalidType()
    {
        $this->expectExceptionMessage('Identifier type \'KRS\' is not supported');
        self::$reader->lookup('022434610', IdentifierType::KRS);
    }

    public function testIncorrectRegon()
    {
        $response = self::$reader->lookup('022434610111', IdentifierType::REGON);
        $this->assertFalse($response->valid);
    }

    public function testIncorrectNipWithPolishPrefix()
    {
        $this->expectExceptionMessage('NIP should have 10 digits, 12 given');
        $response = self::$reader->lookup('PL7282697380');
        $this->assertTrue($response->valid);
    }

    public function testIncorrectNip()
    {
        $response = self::$reader->lookup('5252389922');
        $this->assertFalse($response->valid);
    }

    public function testMultiCompanies()
    {
        $response = self::$reader->lookup('6422995563');
        $this->assertTrue($response->valid);
    }

    public function testPartnetship()
    {
        $response = self::$reader->lookupPartnership('6783053210');
        foreach ($response as $companyData) {
            $this->assertTrue($companyData->valid);
        }
    }

    public function testNIPIdentifierIsWithoutCountryCode()
    {
        $response = self::$reader->lookup('7282697380');
        $nipIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == IdentifierType::NIP;
            }
        );
        $this->assertEquals("7282697380", reset($nipIdentifier)->id);
    }

    public function testRepresentatives()
    {
        $response = self::$reader->lookup('5990200923');
        $this->assertCount(1, $response->representatives);
        $this->assertEquals("Andrzej", $response->representatives[0]->firstName);
        $this->assertEquals(null, $response->representatives[0]->middleName);
        $this->assertEquals("Kubzdyl", $response->representatives[0]->lastName);
    }

    public function testWebsiteAddress(): void
    {
        $response = self::$reader->lookup('8381841171');
        $this->assertTrue($response->valid);
        $this->assertEquals(['alfabeta.com.pl'], $response->websiteAddresses);
    }

    public function testSearchByName(): void
    {
        $response = self::$reader->search(['name' => 'SKYEUROPE MICHAŁ MAZUR']);
        $this->assertCount(1, $response);
        $this->assertTrue($response[0]->valid);
        $this->assertEquals($response[0]->identifiers[0]->id, '7282697380');
    }
}
