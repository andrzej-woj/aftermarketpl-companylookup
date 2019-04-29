<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\ViesException;

class ViesReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    /**
     */
    public static function lookup(string $vatid)
    {
        self::validateVatid($vatid);

        list($country, $number) = self::resolveVatid($vatid);

        $client = new \GuzzleHttp\Client();        
        try
        {
            $response = $client->get("http://ec.europa.eu/taxation_customs/vies/vatResponse.html?locale=en&ms=$country&vat=$number");
        }
        catch(Throwable $e)
        {
            return ["valid" => "unknown"];
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

            list($address, $zip, $city) = Helpers\Address::extract($country, $addr);
            return [
                    'result' => 'valid',
                    'nip' => $vatid,
                    'company' => $name,
                    'address' => $address,
                    'zip' => $zip,
                    'city' => $city
            ];
        }
        else
        {
            return ["valid" => "unknown"];
        }
    }
}