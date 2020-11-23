<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Aftermarketpl\CompanyLookup\Env;

final class GusTest extends TestCase
{
    public static $reader = null;
    
    /**
     * Bootstrap VAT reader class
     */
    public static function setUpBeforeClass()
    {
        self::$reader = new Aftermarketpl\CompanyLookup\GusReader(Env::$gusapikey);
    }

    public function testCorrectNip()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertTrue($response->valid);
    }

    public function testCompanyClosedNip()
    {
        $response = self::$reader->lookup('PL5252389922');
        $this->assertFalse($response->valid);
    }

    public function testCorrectRegon()
    {
        $response = self::$reader->lookupRegon('022434610');
        $this->assertTrue($response->valid);
    } 
    
    public function testIncorrectRegon()
    {
        $response = self::$reader->lookupRegon('022434610111');
        $this->assertFalse($response->valid);
    }

    public function testCorrectKrs()
    {
        $response = self::$reader->lookupKrs('0000513708');
        $this->assertTrue($response->valid);
    } 
    
    public function testIncorrectKrs()
    {
        $response = self::$reader->lookupKrs('513708111');
        $this->assertFalse($response->valid);
    }

    public function testStartDate()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertTrue($response->valid);
        $this->assertTrue(strtotime($response->startDate) !== false);
    }

    public function testStartDate2()
    {
        $response = self::$reader->lookup('PL9121874990');
        $this->assertTrue($response->valid);
        $this->assertTrue(strtotime($response->startDate) !== false);
    }

    public function testRepresentatives()
    {
        $response = self::$reader->lookup('PL7282697380');
        $this->assertCount(1, $response->representatives);
        $this->assertEquals("MICHAŁ", $response->representatives[0]->firstName);
        $this->assertEquals("MAZUR", $response->representatives[0]->lastName);
    }

    public function testOrganizationRepresentatives()
    {
        $response = self::$reader->lookup('PL7252285833');
        $this->assertCount(2, $response->representatives);
        $this->assertEquals("MICHAŁ", $response->representatives[0]->firstName);
        $this->assertEquals("MAZUR", $response->representatives[0]->lastName);
        $this->assertEquals("KLAUDIA", $response->representatives[1]->firstName);
        $this->assertEquals("GORZKOWSKA", $response->representatives[1]->lastName);
    }
}