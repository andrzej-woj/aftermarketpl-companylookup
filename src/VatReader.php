<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\VatReaderException;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use Aftermarketpl\CompanyLookup\Validators;
use SoapClient;

class VatReader implements Reader
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

    public function lookup(string $nip, string $type = IdentifierType::NIP): Companydata
    {
        if ($type !== IdentifierType::NIP) {
            throw new VatReaderException('Invalid identifier type, only NIP is supported');
        }

        Validators\PL::checkNip($nip);

        try {
            $api = new SoapClient($this->ws_url);
            $response = $api->sprawdzNIP($nip);
        } catch(\Throwable $e) {
            throw new VatReaderException('Checking status currently not available', 0, $e);
        }

        return $this->handleResponse($response, ['vatid' => $nip, 'date' => date("Y-m-d")]);
    }

    /**
     */
    public function lookupDate(string $nip, string $date, string $type = IdentifierType::NIP)
    {
        if ($type !== IdentifierType::NIP) {
            throw new VatReaderException('Checking status currently not available');
        }

        Validators\PL::checkNip($nip);

        try {
            $api = new SoapClient($this->ws_url);
            $response = $api->sprawdzNIPNaDzien($nip, $date);
        } catch(\Throwable $e) {
            throw new VatReaderException('Checking status currently not available', 0, $e);
        }

        return $this->handleResponse($response, ['vatid' => $nip, 'date' => $date]);
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
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::NIP, $request['vatid']);

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