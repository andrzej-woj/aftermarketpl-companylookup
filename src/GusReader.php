<?php 

namespace Aftermarketpl\CompanyLookup;

use Throwable;
use Aftermarketpl\CompanyLookup\Exceptions\GusReaderException;
use SoapClient;

use GusApi\BulkReportTypes;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use GusApi\ReportTypes;
use DateTimeImmutable;
use GusApi\SearchReport;

/**
 * https://api.stat.gov.pl/Home/RegonApi/
 */
class GusReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    /**
     * API key
     */
    private $apikey = '';
    private $api = null;

    private $options = [];
    
    /**
     * Handle current report
     */
    private $report = false;


    /**
     * 
     */
    public function __construct(string $apikey = '')
    {
        $this->apikey = $apikey;

        try {
            $this->api = new GusApi($apikey);
            $this->api->login();
        } catch(\Throwable $e) {
            throw new CeidgReaderException('Checking status currently not available');
        }           
        
    }

    /**
     * Lookup company by vatid
     */
    public function lookup(string $vatid)
    {
        $vatid = $this->validateVatid($vatid, 'PL');
        list($country, $number) = $this->resolveVatid($vatid);

        try {
            $gusReports = $this->api->getByNip($number);

            foreach ($gusReports as $gusReport) {
                if($gusReport->getActivityEndDate())
                    continue; // ommit inactive
                
                $this->report = $gusReport;
                return $this->mapCompanyData($gusReport);
            }

        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');
        
        } catch (NotFoundException $e) {
            return [
                'result' => 'invalid',
                'vatid' => $vatid
            ];            
        }
    }

    /**
     * Lookup company by KRS
     */
    public function lookupKRS(string $krs)
    {
        try {
            $gusReports = $this->api->getByKrs($krs);

            foreach ($gusReports as $gusReport) {
                if($gusReport->getActivityEndDate())
                    continue; // ommit inactive              
                
                $this->report = $gusReport;
                return $this->mapCompanyData($gusReport);
            }

        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');
        
        } catch (NotFoundException $e) {
            return [
                'result' => 'invalid',
                'krs' => $krs
            ];            
        }
    }

    /**
     * Lookup company by REGON
     */
    public function lookupREGON(string $regon)
    {
        try {
            $gusReports = $this->api->getByRegon($regon);

            foreach ($gusReports as $gusReport) {
                if($gusReport->getActivityEndDate())
                    continue; // ommit inactive

                $this->report = $gusReport;
                return $this->mapCompanyData($gusReport);
            }

        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');
        
        } catch (NotFoundException $e) {
            return [
                'result' => 'invalid',
                'regon' => $regon
            ];            
        }
    }


    /**
     * 
     */
    protected function mapCompanyData(SearchReport $gusReport) {
        return [
            'result' => 'valid',
            'country' => 'PL',
            'vatid' => $gusReport->getNip(),
            'regon' => $gusReport->getRegon(),
            'company' => (string) $gusReport->getName(),
            'address' => (string) $gusReport->getStreet().' '.$gusReport->getPropertyNumber() . ( $gusReport->getApartmentNumber() ? '/'.$gusReport->getApartmentNumber() : ''),
            'zip' => (string) $gusReport->getZipCode(),
            'city' => (string) $gusReport->getCity(),
        ]; 
    }

    public  function fetchPKD() {
        if(! ($this->report instanceof SearchReport)) {
            throw new GusReaderException('No company, please lookup company');
        }
        
        switch($this->report->getType())
        {
            case 'p': // osoba prawna
                $reportType = ReportTypes::REPORT_ACTIVITY_LAW_PUBLIC;
                break;

            case 'f': // osoba fizyczna
                $reportType = ReportTypes::REPORT_LOCALS_PHYSIC_PUBLIC;
                break; 
            
            default:
                throw new GusReaderException('Uknown company type');
        }
        
        return $this->api->getFullReport($this->report, $reportType);
    }

    /**
     * 
     */
    protected function handleFullReport(SearchReport $report) {
        switch($report->getType())
        {
            case 'p': // osoba prawna
                $reportType = ReportTypes::REPORT_PUBLIC_LAW;
                break;

            case 'f': // osoba fizyczna
                $reportType = ReportTypes::REPORT_ACTIVITY_PHYSIC_PERSON;
                break;                    
        }
        return $this->api->getFullReport($report, $reportType);
    }
}