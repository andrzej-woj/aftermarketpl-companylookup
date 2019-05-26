<?php 

namespace Aftermarketpl\CompanyLookup;

use Throwable;
use Aftermarketpl\CompanyLookup\Exceptions\ViesReaderException;
use SoapClient;

class ViesReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    
    public $ws_url = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl?wsdl';
    private $api = null;

    /**
     * 
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    
        try {
            $this->api = new SoapClient($this->ws_url);
        } catch(\Throwable $e) {
            throw new ViesReaderException('Checking status currently not available');
        }
    }

    /**
     */
    public function lookup(string $vatid)
    {
        $vatid = $this->validateVatid($vatid);

        list($country, $number) = $this->resolveVatid($vatid);

        try {
            $response = $this->api->checkVat([
                'countryCode' => 'ES',
                'vatNumber' => 'B64724131'
            ]);
        } catch(Throwable $e) {
            throw new ViesReaderException('Checking status currently not available [' . $e->getMessage() . ']');
        }

        if(!$response->valid)
        {
            return [
                'valid' => false,
                'vatid' => $vatid,
                'country' => $country
            ];            
        }
        else
        {
            try {
                list($address, $zip, $city) = Helpers\Address::extract($country, $response->address);
            } catch(Throwable $e) {
                $address = $response->address;
                $zip = '';
                $city = '';
            }

            $address = trim(preg_replace("/^\-\-\-$/", "", $address));

            return [
                'result' => 'valid',
                'valid' => true,
                'country' => $country,
                'vatid' => $vatid,
                'company' => $response->name,
                'address' => $address,
                'zip' => $zip,
                'city' => $city,
                'date' => $response->requestDate
            ];
        }
    }
}