<?php 

namespace Aftermarketpl\CompanyLookup;

use Throwable;
use Aftermarketpl\CompanyLookup\Exceptions\ViesReaderException;

class ViesReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

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

        list($country, $number) = $this->resolveVatid($vatid);

        $client = new \GuzzleHttp\Client();        
        try
        {
            $response = $client->get("http://ec.europa.eu/taxation_customs/vies/vatResponse.html?locale=en&ms=$country&vat=$number");
        }
        catch(Throwable $e)
        {
            throw new ViesReaderException('Checking status currently not available');
        }

        if(preg_match("/service unavailable/", $response->getBody()))
        {
            return ["valid" => "unknown"];
        }
        if(preg_match("/<span class=\"invalidStyle\">/", $response->getBody()))
        {
            return ["valid" => "invalid"];
        }
        else if(preg_match("/<span class=\"validStyle\">/",$response->getBody()))
        {
            preg_match_all("|<td class=\"labelStyle\">(.*?)</td>.*?<td>(.*?)</td>|s", $response->getBody(), $a);
            $name = trim($a[2][3]);
            $addr = str_replace("<br />", "\n", trim($a[2][4]));

            try {
                list($address, $zip, $city) = Helpers\Address::extract($country, $addr);
            } catch(Throwable $e) {
                $address = $addr;
                $zip = '';
                $city = '';
            }
            
            $address = trim(str_replace("---", "", $address));

            return [
                    'result' => 'valid',
                    'country' => $country,
                    'vatid' => $vatid,
                    'company' => $name,
                    'address' => $address,
                    'zip' => $zip,
                    'city' => $city,
            ];
        }
        else
        {
            return [
                "valid" => "unknown",
                "country" => $country,
                "vatid" => $vatid
            ];
        }
    }
}