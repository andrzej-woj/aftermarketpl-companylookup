<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\VatReaderException;
use SoapClient;

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
        $this->validateVatid($vatid);
        $vatid = mb_ereg_replace("[^0-9]", "" , $vatid);

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
        $this->validateVatid($vatid);
        $vatid = mb_ereg_replace("[^0-9]", "" , $vatid);
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
            return ['result' => 'unknown', 'vatid' => $request['vatid']];
        }

        if($response->Kod == 'X')  // Usługa nieaktywna, brak dostepu API
        {
            return ['result' => 'unknown', 'vatid' => $request['vatid']];
        }

        if($response->Kod == 'C') 
        {
            return ['result' => 'valid', 'vatid' => $request['vatid']];
        }

        if($response->Kod != "C") 
        {
            return ['result' => 'invalid', 'vatid' => $request['vatid']];
        }
    }
}