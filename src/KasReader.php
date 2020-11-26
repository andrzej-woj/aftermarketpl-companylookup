<?php

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\KasReaderException;
use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use Aftermarketpl\CompanyLookup\Models\CompanyRepresentative;
use Aftermarketpl\CompanyLookup\Validators;

/**
 * https://www.gov.pl/web/kas/api-wykazu-podatnikow-vat
 */
class KasReader implements Reader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    private $ws_url = 'https://wl-api.mf.gov.pl';

    public function lookup(string $id, string $type = IdentifierType::NIP): Companydata
    {
        return $this->lookupDate($id, date('Y-m-d'), $type);
    }

    public function lookupDate(string $id, string $date, string $type = IdentifierType::NIP): Companydata
    {
        switch ($type) {
            case IdentifierType::NIP:
                return $this->lookupNipOnDate($id, $date);
            case IdentifierType::REGON:
                return $this->lookupRegonOnDate($id, $date);
            default:
                throw new KasReaderException(sprintf('Identifier type \'%s\' is not supported', $type));
        }
    }

    private function lookupNipOnDate(string $nip, string $date) : CompanyData
    {
        Validators\PL::checkNip($nip);

        return $this->callApi(sprintf('/api/search/nip/%s', $nip), ["date" => $date]);
    }

    private function lookupRegonOnDate(string $regon, string $date) : CompanyData
    {
        return $this->callApi(sprintf('/api/search/regon/%s', $regon), ["date" => $date]);
    }

    private function callApi(string $path, array $params): CompanyData
    {
        $url = sprintf("%s%s?%s", $this->ws_url, $path, http_build_query($params));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_error($curl)) {
            throw new KasReaderException(curl_error($curl), curl_errno($curl));
        }

        if ($httpCode !== 200) {
            throw new KasReaderException("Invalid http response code", $httpCode);
        }

        $responseData = @json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new KasReaderException("Invalid json response", $httpCode);
        }

        if (empty($responseData["result"]["subject"])) {
            throw new KasReaderException("Empty reponse", $httpCode);
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
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::NIP, $result["nip"]);
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::REGON, $result["regon"]);
        if($result["krs"]) {
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::KRS, $result["krs"]);
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

