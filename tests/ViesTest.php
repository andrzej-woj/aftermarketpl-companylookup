<?php
declare(strict_types=1);

use Aftermarketpl\CompanyLookup\Exceptions\ValidatorException;
use Aftermarketpl\CompanyLookup\Exceptions\ViesReaderException;
use Aftermarketpl\CompanyLookup\IdentifierType;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use PHPUnit\Framework\TestCase;

final class ViesTest extends TestCase
{
    public static $reader = null;
    
    /**
     * Bootstrap reader class
     */
    public static function setUpBeforeClass(): void
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
        $this->expectException(ValidatorException::class);
        self::$reader->lookup('XX6783041098');
    }

    public function testVATIdentifierIsWithoutCountryCode()
    {
        $response = self::$reader->lookup('PL9121875009');
        $vatIdentifier = array_filter(
            $response->identifiers,
            function (CompanyIdentifier $identifier) {
                return $identifier->type == IdentifierType::VAT;
            }
        );
        $this->assertEquals("PL9121875009", reset($vatIdentifier)->id);
    }

    public function testValidG1()
    {
        try {

            $response = self::$reader->lookup("ATU14449603");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE1000596075");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10057240I");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ01596497");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE301515571");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DK26688248");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE100772102");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ESY8922826J");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI19992126");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR32831817234");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL998622621");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR05305527416");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU11851862");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE1425425VA");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT00880811005");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100001265611");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LU21380224");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LV40003417394");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("MT19661023");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL866592271B01");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("PT517567229");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO14080808");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE556732533601");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI64212530");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK2020095605");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG2()
    {

        try {

            $response = self::$reader->lookup("ATU16128401");
            $this->assertTrue($response->valid);
            $this->expectException(Aftermarketpl\CompanyLookup\Exceptions\ValidatorException::class);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("UIDNRATU65415936");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE0436648765");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BG109605681");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10007614C");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ01526022");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE157145861");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DK20055472");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE100032350");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ESB64724131");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI14550062");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR30453207383");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL036462775");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR05269329087");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU11087120");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE1206075N");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT00096570429");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100001162116");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LU19406747");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LV40003411866");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("MT18363612");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL866592271B01");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("PT135448930");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO13015658");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE556729241101");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI19333439");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK1030272837");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG3()
    {

        try {

            $response = self::$reader->lookup("ATU39010707");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE0892117710");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10244958W");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ08356513");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE815522473");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DK42282561");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE100892817");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ESZ0105028S");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI21980140");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR33412721524");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL999563116");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR44463824608");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU12746015");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE2276406O");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT12485671007");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100001835315");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO6334441");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG4()
    {

        try {

            $response = self::$reader->lookup("AT125713321");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE1001786108");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10371671M");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ6905131629");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE815501496");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DK1015754199");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE102729195");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ES0339742");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI28366127");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR34918356981");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL2564");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR57749534320");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU64352305");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE3227500PH");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT13422340151");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100002092218");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO1231");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE5565955019");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK888888888889");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG5()
    {

        try {

            $response = self::$reader->lookup("ATU42970308");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE459418625");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10405892B");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ8602103554");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE000000000");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DK389604415");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE102767157");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ES09336478L");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI29119515");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR12345");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL7389389209");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR66341509836");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU12120064242");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE3435559MH");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT13735361001");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100002710016");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO41654355");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE55");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK36035092");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG6()
    {

        try {

            $response = self::$reader->lookup("AT4228018");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BE553888014");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY10412509E");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ08397635");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DEHRB256498");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE12989387");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("ES38734698R");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FI100382081M");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR36000052679");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EL036719745");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR76297885507");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU65934005");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE000");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT00330230426");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT101727413");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO45478235");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE449097448801");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK33723991");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG7()
    {

        try {

            $response = self::$reader->lookup("ATU6491903191");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BG205567049");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY18009164Y");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ5532290337");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE04324996545");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("EE14415207");
            $this->assertFalse($response->valid);
            $this->expectException(Aftermarketpl\CompanyLookup\Exceptions\ValidatorException::class);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("X3671017X");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR20");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR78843912793");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU127176617");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE9824403U");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT11476761009");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT249495410");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LU184553336");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL2122");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("PT6572081356");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO42401202");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE08709097");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI90089197");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK2020210687");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT1000011786711");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG8()
    {

        try {

            $response = self::$reader->lookup("ATU65502244");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("BG205614844");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY30013245G");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ5708191852");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE25100500000190246286");
            $this->assertFalse($response->valid);
            $this->expectException(Aftermarketpl\CompanyLookup\Exceptions\ValidatorException::class);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("Y2021143");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR12312312312321");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR87994051602");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU13719069443");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE3232319JH");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IT1234");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT358673716");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LU18819145");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LV55403037351");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL123456");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("PT7389389209");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO41276829");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE931123025501");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI86718312");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK2121827851");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT100002440");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG9()
    {

        try {

            $response = self::$reader->lookup("BG207488547");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY60015353X");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CZ83088643000");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE3012034567890");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("FR02529245318");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HR29206029807");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("HU23473706");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE1637875NA");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT359229917");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LV85030365615");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("MT52232");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL864128915B01");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO4517523");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE916642393001");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI80468853");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK10");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT14185");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }

    public function testValidG10()
    {

        try {

            $response = self::$reader->lookup("BG831742114");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("CY60017620T");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("DE62102024010000040201668425");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("IE3285482Q");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT827052716");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("MT19702324");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("NL06INGB0004899576");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("RO45647148");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SE556817919501");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SI66625041");
            $this->assertTrue($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("SK2020");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }

        try {

            $response = self::$reader->lookup("LT1000010692210");
            $this->assertFalse($response->valid);

        } catch (ViesReaderException $e) {
            if ($e->getMessage() == "Checking status currently not available [MS_MAX_CONCURRENT_REQ]" || $e->getMessage() == "Checking status currently not available [TIMEOUT]")
                $this->addWarning($e->getMessage());
            else
                throw $e;
        }
    }


}