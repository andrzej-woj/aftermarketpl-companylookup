<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Models\CompanyRepresentative;
use Throwable;
use Aftermarketpl\CompanyLookup\Exceptions\CeidgReaderException;
use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;

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
            $companyData = new CompanyData;
            $companyData->identifiers[] = new CompanyIdentifier('vat', $number);
            $companyData->valid = false;
            return $companyData;            
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
            $companyData = new CompanyData;
            $companyData->identifiers[] = new CompanyIdentifier('vat', $number);
            $companyData->valid = false;
            return $companyData;
        } elseif(!$resolvedCompany) {
            $resolvedCompany = $wpis;
        }

        $companyData = new CompanyData;

        $companyData->mainAddress =  $this->parseAddress($resolvedCompany->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci);
        $companyData->additionalAddresses[] = $this->parseAddress($resolvedCompany->DaneAdresowe->AdresDoDoreczen);

        if($resolvedCompany->DaneDodatkowe->Status == 'Aktywny') {
            $companyData->valid = true;
        } else {
            $companyData->valid = false;
        }

        $companyData->name = (string) $resolvedCompany->DanePodstawowe->Firma;

        $companyData->identifiers = [];
        $companyData->identifiers[] = new CompanyIdentifier('vat', $number);
        $companyData->identifiers[] = new CompanyIdentifier('regon', (string) $resolvedCompany->DanePodstawowe->REGON);
        $companyData->startDate = (string)$resolvedCompany->DaneDodatkowe->DataRozpoczeciaWykonywaniaDzialalnosciGospodarczej;
        $companyData->pkdCodes = mb_split(",", (string) $resolvedCompany->DaneDodatkowe->KodyPKD);
        
        if((string)$resolvedCompany->DaneKontaktowe->Telefon)
            $companyData->phoneNumbers = [(string)$resolvedCompany->DaneKontaktowe->Telefon];
        
        if((string)$resolvedCompany->DaneKontaktowe->AdresPocztyElektronicznej)
            $companyData->emailAddresses = [(string)$resolvedCompany->DaneKontaktowe->AdresPocztyElektronicznej];
        
        if((string)$resolvedCompany->DaneKontaktowe->AdresStronyInternetowej)
            $companyData->websiteAddresses = [(string)$resolvedCompany->DaneKontaktowe->AdresStronyInternetowej];

        $companyData->representatives[] = new CompanyRepresentative(
            (string) $resolvedCompany->DanePodstawowe->Imie,
            (string) $resolvedCompany->DanePodstawowe->Nazwisko
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
        return $companyAddress;
    }
}