<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\CeidgReaderException;
use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use Aftermarketpl\CompanyLookup\Models\CompanyRepresentative;
use SoapClient;

/**
 * https://datastore.ceidg.gov.pl/CEIDG.DataStore/Styles/Regulations/API_Datastore_20190314.pdf
 * 
 */
class CeidgReader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    /**
     * Soap api endpoint
     */
    private $ws_url = 'https://datastore.ceidg.gov.pl/CEIDG.DataStore/services/DataStoreProvider201901.svc?wsdl';
    private $apikey = '';
    private $api = null;

    private $options = [];

    /**
     * 
     */
    public function __construct(string $apikey = '')
    {
        $this->apikey = $apikey;
        try {
            $this->api = new SoapClient($this->ws_url);
        } catch(\Throwable $e) {
            throw new CeidgReaderException('Checking status currently not available');
        }        
    }

    /**
     * API Lookup
     */
    public function lookup(string $vatid) : CompanyData
    {
        $vatid = $this->validateVatid($vatid, 'PL');
        list($country, $number) = $this->resolveVatid($vatid);

        try {
            $response = $this->api->GetMigrationData201901([
                'AuthToken' => $this->apikey,
                'NIP' => [$number]
            ]);
        } catch(\Throwable $e) {
            throw new CeidgReaderException('Checking status currently not available');
        }              


        if(preg_match("/Brak tokenu/i", $response->GetMigrationData201901Result)) {
            throw new CeidgReaderException("Incorrect API KEY CEIDG");
        }

        
        if(!isset($response->GetMigrationData201901Result)) {
            throw new CeidgReaderException(("CEIDG API unavailable: [" . @substr($response, 0,100) . "...]"));
        }
        $parsedResponse = @simplexml_load_string($response->GetMigrationData201901Result);
        if(!$parsedResponse) {
            return $this->createInvalidCompany($number);
        }

        // Find active company
        $resolvedCompany = false;
        $wpis = false;
        foreach($parsedResponse->InformacjaOWpisie as $wpis) {
            if($wpis->DaneDodatkowe->Status == 'Aktywny') {
                $resolvedCompany = $wpis;
            }
        }

        // Jeżeli nic nie zwrócono, budujemy wpis invalid
        if(!$resolvedCompany && !$wpis) {
            return $this->createInvalidCompany($number);
        } elseif(!$resolvedCompany) {
            $resolvedCompany = $wpis;
        }

        return $this->parseCompanyData($number, $resolvedCompany);
    }

    public function lookupPartnership(string $vatid): array
    {
        $vatid = $this->validateVatid($vatid, 'PL');
        list($country, $number) = $this->resolveVatid($vatid);

        try {
            $response = $this->api->GetMigrationData201901([
                'AuthToken' => $this->apikey,
                'NIP_SC' => [$number]
            ]);
        } catch(\Throwable $e) {
            throw new CeidgReaderException('Checking status currently not available');
        }

        if(preg_match("/Brak tokenu/i", $response->GetMigrationData201901Result)) {
            throw new CeidgReaderException("Incorrect API KEY CEIDG");
        }

        if(!isset($response->GetMigrationData201901Result)) {
            throw new CeidgReaderException(("CEIDG API unavailable: [" . @substr($response, 0,100) . "...]"));
        }

        $parsedResponse = @simplexml_load_string($response->GetMigrationData201901Result);
        if(!$parsedResponse) {
            return [$this->createInvalidCompany($number)];
        }

        $companies = [];
        foreach($parsedResponse->InformacjaOWpisie as $wpis) {
            $companies[] = $this->parseCompanyData($number, $wpis);
        }

        return $companies;
    }

    private function createInvalidCompany(string $number): CompanyData
    {
        $companyData = new CompanyData;
        $companyData->identifiers[] = new CompanyIdentifier('vat', $number);
        $companyData->valid = false;

        return $companyData;
    }

    private function parseCompanyData(string $number, \SimpleXMLElement $xml): CompanyData
    {
        $validStatuses = [
            'Aktywny',
            'Działalność jest prowadzona wyłącznie w formie spółki/spółek cywilnych',
        ];

        $companyData = new CompanyData;

        $companyData->mainAddress =  $this->parseAddress($xml->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci);
        $companyData->additionalAddresses[] = $this->parseAddress($xml->DaneAdresowe->AdresDoDoreczen);

        if(in_array((string) $xml->DaneDodatkowe->Status, $validStatuses)) {
            $companyData->valid = true;
        } else {
            $companyData->valid = false;
        }

        $companyData->name = (string) $xml->DanePodstawowe->Firma;

        $companyData->identifiers = [];
        $companyData->identifiers[] = new CompanyIdentifier('vat', $number);
        $companyData->identifiers[] = new CompanyIdentifier('regon', (string) $xml->DanePodstawowe->REGON);
        $companyData->startDate = (string)$xml->DaneDodatkowe->DataRozpoczeciaWykonywaniaDzialalnosciGospodarczej;
        $companyData->pkdCodes = mb_split(",", (string) $xml->DaneDodatkowe->KodyPKD);

        if((string)$xml->DaneKontaktowe->Telefon)
            $companyData->phoneNumbers = [(string)$xml->DaneKontaktowe->Telefon];

        if((string)$xml->DaneKontaktowe->AdresPocztyElektronicznej)
            $companyData->emailAddresses = [(string)$xml->DaneKontaktowe->AdresPocztyElektronicznej];

        if((string)$xml->DaneKontaktowe->AdresStronyInternetowej)
            $companyData->websiteAddresses = [(string)$xml->DaneKontaktowe->AdresStronyInternetowej];

        $companyData->representatives[] = new CompanyRepresentative(
            (string) $xml->DanePodstawowe->Imie,
            (string) $xml->DanePodstawowe->Nazwisko
        );

        return $companyData;
    }

    private function parseAddress($address) : CompanyAddress 
    {
        $companyAddress = new CompanyAddress;
        $companyAddress->country = 'PL';
        $companyAddress->postalCode = (string) $address->KodPocztowy;
        $companyAddress->address = (string) $address->Ulica;
        $companyAddress->city = (string) $address->Miejscowosc;

        if(!empty($address->Lokal))
            $companyAddress->address = sprintf("%s %s/%s", $address->Ulica, $address->Budynek, $address->Lokal);
        else
            $companyAddress->address = sprintf("%s %s", $address->Ulica, $address->Budynek);

        return $companyAddress;
    }
}