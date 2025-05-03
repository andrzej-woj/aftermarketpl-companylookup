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
class CeidgReader implements Reader
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
    public function lookup(string $id, string $type = IdentifierType::NIP) : Companydata
    {
        switch ($type) {
            case IdentifierType::NIP:
                return $this->lookupNIP($id);
            case IdentifierType::REGON:
                return $this->lookupREGON($id);
            default:
                throw new CeidgReaderException(sprintf('Identifier type \'%s\' is not supported', $type));
        }
    }

    /**
     * @return CompanyData[]
     */
    public function lookupPartnership(string $id, string $type = IdentifierType::NIP): array
    {
        switch ($type) {
            case IdentifierType::NIP:
                return $this->lookupPartnershipNIP($id);
            case IdentifierType::REGON:
                return $this->lookupPartnershipREGON($id);
            default:
                throw new CeidgReaderException(sprintf('Identifier type \'%s\' is not supported', $type));
        }
    }

    /**
     * @return CompanyData[]
     */
    public function search(array $parameters): array
    {
        $parameters = array_filter($parameters);
        if (empty($parameters)) {
            throw new CeidgReaderException('Empty search parameters');
        }

        $supportedParameters = ['name'];

        $unknownParameters = array_diff(array_keys($parameters), $supportedParameters);
        if (!empty($unknownParameters)) {
            throw new CeidgReaderException(sprintf('Unsupported paremeters: %s, supported are: %s', implode(',', $unknownParameters), implode(',', $supportedParameters)));
        }

        $apiCallParameters = [];
        if (!empty($parameters['name'])) {
            $apiCallParameters['Name'] = [$parameters['name']];
        }

        $xml = $this->callApi($apiCallParameters);
        if(!$xml) {
            $companyData = new CompanyData;
            $companyData->valid = false;
            return [$companyData];
        }

        $companies = [];
        foreach($xml->InformacjaOWpisie as $wpis) {
            $companies[] = $this->parseCompanyData($wpis);
        }

        return $companies;
    }

    private function lookupNIP(string $nip) : Companydata
    {
        Validators\PL::checkNip($nip);

        $xml = $this->callApi([
            'NIP' => [$nip]
        ]);

        if(!$xml) {
            return $this->createInvalidCompany(new CompanyIdentifier(IdentifierType::NIP, $nip));
        }

        // Find active company
        $resolvedCompany = false;
        $wpis = false;
        foreach($xml->InformacjaOWpisie as $wpis) {
            if($wpis->DaneDodatkowe->Status == 'Aktywny') {
                $resolvedCompany = $wpis;
            }
        }

        // Jeżeli nic nie zwrócono, budujemy wpis invalid
        if(!$resolvedCompany && !$wpis) {
            return $this->createInvalidCompany(new CompanyIdentifier(IdentifierType::NIP, $nip));
        } elseif(!$resolvedCompany) {
            $resolvedCompany = $wpis;
        }

        return $this->parseCompanyData($resolvedCompany);
    }

    private function lookupREGON(string $regon): Companydata
    {
        $xml = $this->callApi([
            'REGON' => [$regon],
        ]);

        if(!$xml) {
            return $this->createInvalidCompany(new CompanyIdentifier(IdentifierType::REGON, $regon));
        }

        // Find active company
        $resolvedCompany = false;
        $wpis = false;
        foreach($xml->InformacjaOWpisie as $wpis) {
            if($wpis->DaneDodatkowe->Status == 'Aktywny') {
                $resolvedCompany = $wpis;
            }
        }

        // Jeżeli nic nie zwrócono, budujemy wpis invalid
        if(!$resolvedCompany && !$wpis) {
            return $this->createInvalidCompany(new CompanyIdentifier(IdentifierType::REGON, $regon));
        } elseif(!$resolvedCompany) {
            $resolvedCompany = $wpis;
        }

        return $this->parseCompanyData($resolvedCompany);
    }

    /**
     * @return CompanyData[]
     */
    private function lookupPartnershipNIP(string $nip): array
    {
        Validators\PL::checkNip($nip);

        $xml = $this->callApi([
            'NIP_SC' => [$nip]
        ]);

        if(!$xml) {
            return [$this->createInvalidCompany(new CompanyIdentifier(IdentifierType::NIP, $nip))];
        }

        $companies = [];
        foreach($xml->InformacjaOWpisie as $wpis) {
            $companies[] = $this->parseCompanyData($wpis);
        }

        return $companies;
    }

    /**
     * @return CompanyData[]
     */
    private function lookupPartnershipREGON(string $regon): array
    {
        $xml = $this->callApi([
            'REGON_SC' => [$regon]
        ]);

        if(!$xml) {
            return [$this->createInvalidCompany(new CompanyIdentifier(IdentifierType::REGON, $regon))];
        }

        $companies = [];
        foreach($xml->InformacjaOWpisie as $wpis) {
            $companies[] = $this->parseCompanyData($wpis);
        }

        return $companies;
    }

    private function callApi(array $parameters): ?\SimpleXMLElement
    {
        $parameters = array_merge(['AuthToken' => $this->apikey], $parameters);

        try {
            $response = $this->api->GetMigrationData201901($parameters);
        } catch(\Throwable $e) {
            throw new CeidgReaderException('Checking status currently not available', 0, $e);
        }

        if(preg_match("/Brak tokenu/i", $response->GetMigrationData201901Result)) {
            throw new CeidgReaderException("Incorrect API KEY CEIDG");
        }

        if(!isset($response->GetMigrationData201901Result)) {
            throw new CeidgReaderException(("CEIDG API unavailable: [" . @substr($response, 0,100) . "...]"));
        }

        $parsedResponse = @simplexml_load_string($response->GetMigrationData201901Result);

        return $parsedResponse ?: null;
    }

    private function createInvalidCompany(CompanyIdentifier $identifier): CompanyData
    {
        $companyData = new CompanyData;
        $companyData->identifiers[] = $identifier;
        $companyData->valid = false;

        return $companyData;
    }

    private function parseCompanyData(\SimpleXMLElement $xml): CompanyData
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
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::NIP, (string) $xml->DanePodstawowe->NIP);
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::REGON, (string) $xml->DanePodstawowe->REGON);
        $companyData->startDate = (string)$xml->DaneDodatkowe->DataRozpoczeciaWykonywaniaDzialalnosciGospodarczej;
        $companyData->pkdCodes = mb_split(",", (string) $xml->DaneDodatkowe->KodyPKD);

        if((string)$xml->DaneKontaktowe->Telefon)
            $companyData->phoneNumbers = [(string)$xml->DaneKontaktowe->Telefon];

        if((string)$xml->DaneKontaktowe->Faks)
            $companyData->faxNumbers = [(string)$xml->DaneKontaktowe->Faks];

        if((string)$xml->DaneKontaktowe->AdresPocztyElektronicznej)
            $companyData->emailAddresses = [(string)$xml->DaneKontaktowe->AdresPocztyElektronicznej];

        if((string)$xml->DaneKontaktowe->AdresStronyInternetowej)
            $companyData->websiteAddresses = [(string)$xml->DaneKontaktowe->AdresStronyInternetowej];

        $companyData->representatives[] = new CompanyRepresentative(
            (string) $xml->DanePodstawowe->Imie,
            null,
            (string) $xml->DanePodstawowe->Nazwisko
        );

        return $companyData;
    }

    private function parseAddress($address) : CompanyAddress 
    {
        $companyAddress = new CompanyAddress;
        $companyAddress->country = 'PL';
        $companyAddress->postalCode = (string) ($address['kod'] ?? "");
        $companyAddress->address = (string) ($address['ulica'] ?? "");
        $companyAddress->city = (string) ($address['miasto'] ?? "");

        if(!empty($address['ulica']) && !empty($address['budynek']) && !empty($address['lokal']))
            $companyAddress->address = sprintf("%s %s m. %s", $address['ulica'], $address['budynek'], $address['lokal']);
        else if(!empty($address['ulica']) && !empty($address['budynek']))
            $companyAddress->address = sprintf("%s %s", $address['ulica'], $address['budynek']);
        else if(!empty($address['budynek']) && !empty($address['lokal']))
            $companyAddress->address = sprintf("%s %s m. %s", $address['miasto'], $address['budynek'], $address['lokal']);
        else if(!empty($address['budynek']))
            $companyAddress->address = sprintf("%s %s", $address['miasto'], $address['budynek']);

        return $companyAddress;
    }
}