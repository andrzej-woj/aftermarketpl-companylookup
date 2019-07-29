<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\VatReaderException;
use SoapClient;

use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;

class VatReader
{
    use Traits\ResolvesVatid, Traits\ValidatesVatid;

    public $ws_url = 'https://sprawdz-status-vat.mf.gov.pl/?wsdl';
    private $options = [];

    /**
     * 
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     */
    public function lookup(string $vatid)
    {
        $vatid = $this->validateVatid($vatid, 'PL');

        try {
            $api = new SoapClient($this->ws_url);
            $response = $api->sprawdzNIP($vatid);
        } catch(\Throwable $e) {
            throw new VatReaderException('Checking status currently not available');
        }

        return $this->handleResponse($response, ['vatid' => $vatid, 'date' => strftime("%Y-%m-%d", mktime())]);
    }

    /**
     */
    public function lookupDate(string $vatid, string $date)
    {
        $vatid = $this->validateVatid($vatid, 'PL');

        try {
            $api = new SoapClient($this->ws_url);
            $response = $api->sprawdzNIPNaDzien($vatid, $date);
        } catch(\Throwable $e) {
            throw new VatReaderException('Checking status currently not available');
        }

        return $this->handleResponse($response, ['vatid' => $vatid, 'date' => $date]);
    }

    /**
     * 
     */
    public function handleResponse($response, $request = []) 
    {
        if(!isset($response->Kod)) 
        {
            throw new VatReaderException('Unknown response');
        }

        if($response->Kod == 'X')  // Usługa nieaktywna, brak dostepu API
        {
            throw new VatReaderException('Checking status currently not available');
        }

        $companyData = new CompanyData;
        $companyData->identifiers[] = new CompanyIdentifier('vat', $request['vatid']);

        if($response->Kod == 'C') 
        {
            $companyData->valid = true;
        }

        if($response->Kod != "C") 
        {
            $companyData->valid = false;
        }

        return $companyData;
    }
}