<?php 

namespace Aftermarketpl\CompanyLookup;

use Throwable;
use Aftermarketpl\CompanyLookup\Exceptions\CeidgReaderException;
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
     */
    public function lookup(string $vatid)
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


        if(preg_match("/Brak tokenu/i", $response->GetMigrationData201901Result))
        {
            throw new CeidgReaderException("Incorrect API KEY CEIDG");
        }

        
        if(!isset($response->GetMigrationData201901Result))
        {
            throw new CeidgReaderException(("CEIDG API unavailable: [" . substr($response, 0,100) . "...]"));
        }

        $parsedResponse = @simplexml_load_string($response->GetMigrationData201901Result);
        if(!$parsedResponse) 
        {
            throw new CeidgReaderException(("CEIDG API unknown response: [" . substr($response, 0,100) . "...]"));
        }
        
        // Find active company
        $resolvedCompany = false;
        foreach($parsedResponse->InformacjaOWpisie as $wpis)
        {
            if($wpis->DaneDodatkowe->Status == 'Aktywny')
            {
                $resolvedCompany = $wpis;
            }
        }

        if(!$resolvedCompany)
        {
            return [
                'result' => 'invalid',
                'country' => $country,
                'vatid' => $vatid
            ];
        }

        return [
            'result' => 'valid',
            'country' => $country,
            'vatid' => $vatid,
            'company' => (string) $resolvedCompany->DanePodstawowe->Firma,
            'address' => (string) $resolvedCompany->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->Ulica,
            'zip' => (string) $resolvedCompany->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->KodPocztowy,
            'city' => (string) $resolvedCompany->DaneAdresowe->AdresGlownegoMiejscaWykonywaniaDzialalnosci->Miejscowosc,
        ];
    }
}