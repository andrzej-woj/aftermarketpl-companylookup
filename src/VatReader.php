<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\VatReaderException;
use SoapClient;

class VatReader
{
    static public $ws_url = 'https://sprawdz-status-vat.mf.gov.pl/?wsdl';

    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;


    /**
     */
    public static function lookup(string $vatid)
    {
        self::validateVatid($vatid);
        $vatid = mb_ereg_replace("[^0-9]", "" , $vatid);

        try {
            $api = new SoapClient(self::$ws_url);
            $response = $api->sprawdzNIP($vatid);
        } catch(\Throwable $e) {
            print_r($e->getMessage());
            throw new VatReaderException('Checking status currently not available');
        }

        return self::handleResponse($response);
    }

    /**
     */
    public static function lookupDate(string $vatid, string $date)
    {
        self::validateVatid($vatid);
        $vatid = mb_ereg_replace("[^0-9]", "" , $vatid);
        try {
            $api = new SoapClient(self::$ws_url);
            $response = $api->sprawdzNIPNaDzien($vatid, $date);
        } catch(\Throwable $e) {
            throw new VatReaderException('Checking status currently not available');
        }

        return self::handleResponse($response);
    }

    /**
     * 
     */
    public static function handleResponse($response) 
    {
        if(!isset($response->Kod)) 
        {
            return ['result' => 'unknown'];
        }

        if($response->Kod == 'C') 
        {
            return ['result' => 'valid'];
        }

        if($response->Kod != "C") 
        {
            return ['result' => 'invalid'];
        }
    }
}