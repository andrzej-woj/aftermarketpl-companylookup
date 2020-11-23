<?php

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\KasException;
use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use Aftermarketpl\CompanyLookup\Models\CompanyRepresentative;

/**
 * https://www.gov.pl/web/kas/api-wykazu-podatnikow-vat
 */
class KasReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    private $ws_url = 'https://wl-api.mf.gov.pl';

    public function lookup(string $vatid) : CompanyData
    {
        return $this->lookupByDate($vatid, date('Y-m-d'));
    }

    public function lookupByDate(string $vatid, string $date) : CompanyData
    {
        $vatid = $this->validateVatid($vatid, 'PL');
        list($country, $number) = $this->resolveVatid($vatid);

        $params = ["date" => $date];
        $url = sprintf("%s/api/search/nip/%s?%s", $this->ws_url, $number, http_build_query($params));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_error($curl)) {
            throw new KasException(curl_error($curl), curl_errno($curl));
        }

        if ($httpCode !== 200) {
            throw new KasException("Invalid http response code", $httpCode);
        }

        $responseData = @json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new KasException("Invalid json response", $httpCode);
        }

        if (empty($responseData["result"]["subject"])) {
            throw new KasException("Empty reponse", $httpCode);
        }

        $result = $responseData["result"]["subject"];

        $companyData = new CompanyData();
        $companyData->name = $result["name"];

        if($result["statusVat"] == 'Czynny') {
            $companyData->valid = true;
        } else {
            $companyData->valid = false;
        }

        $companyData->mainAddress = $this->getAddress($result["residenceAddress"]);
        if (!$companyData->mainAddress) {
            $companyData->mainAddress = $this->getAddress($result["workingAddress"]);
        } else {
            $address = $this->getAddress($result["workingAddress"]);
            if ($address) {
                $companyData->additionalAddresses[] = $address;
            }
        }

        $companyData->startDate = $result["registrationLegalDate"];

        $companyData->identifiers = [];
        $companyData->identifiers[] = new CompanyIdentifier('vat', $result["nip"]);
        $companyData->identifiers[] = new CompanyIdentifier('regon', $result["regon"]);
        if($result["krs"]) {
            $companyData->identifiers[] = new CompanyIdentifier('krs', $result["krs"]);
        }

        foreach ($result["representatives"] as $representative) {
            $companyData->representatives[] = new CompanyRepresentative(
                (string) $representative["firstName"],
                (string) $representative["lastName"]
            );
        }

        return $companyData;
    }

    private function getAddress(?string $address): ?CompanyAddress
    {
        if(!preg_match('/^(?P<address>.*?)(,)?\s+(?P<postcode>\d{2}-\d{3})\s+(?P<city>.*)$/', $address, $matches)) {
            return null;
        }

        $address = new CompanyAddress();
        $address->country = 'PL';
        $address->postalCode = $matches["postcode"];
        $address->city = $matches["city"];
        $address->address = $matches["address"];
        return $address;
    }
}
