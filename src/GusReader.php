<?php 

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Exceptions\GusReaderException;
use Aftermarketpl\CompanyLookup\Models\CompanyAddress;
use Aftermarketpl\CompanyLookup\Models\CompanyData;
use Aftermarketpl\CompanyLookup\Models\CompanyIdentifier;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use SoapClient;
use Throwable;

/**
 * https://api.stat.gov.pl/Home/RegonApi/
 */
class GusReader implements Reader
{
    use Traits\ResolvesVatid;
    use Traits\ValidatesVatid;

    /**
     * API key
     */
    private $apikey = '';

    /**
     * WSDL Url - The same for Test and Production
     */
    private $wsdl_url = "https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/wsdl/UslugaBIRzewnPubl.xsd";

    /**
     * Service Url
     * Test Service: https://Wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc
     */
    private $service_url = "https://wyszukiwarkaregon.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc";

    private $options = [];

    private $SOAP;

    private $sid;

    /**
     * 
     */
    public function __construct(string $apikey = '')
    {
        $this->apikey = $apikey;

        try {
            $this->send("Zaloguj", ["key" => $this->apikey]);
        } catch(Throwable $e) {
            throw new GusReaderException('Checking status currently not available');
        }
    }

    /**
     * Lookup company
     */
    public function lookup(string $id, string $type = IdentifierType::NIP) : Companydata
    {
        switch ($type) {
            case IdentifierType::NIP:
                return $this->lookupNIP($id);
            case IdentifierType::REGON:
                return $this->lookupREGON($id);
            case IdentifierType::KRS:
                return $this->lookupKRS($id);
            default:
                throw new GusReaderException(sprintf('Identifier type \'%s\' is not supported', $type));
        }
    }

    /**
     * Lookup company by vatid
     */
    private function lookupNIP(string $nip) : Companydata
    {
        Validators\PL::checkNip($nip);

        $companyData = new CompanyData;
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::NIP, $nip);

        try {
            $report = $this->send("DaneSzukaj", ["nip" => $nip]);
            if(!$report)
            {
                $companyData->valid = false;
                return $companyData;
            }

            $report["nip"] = $nip;

            $companyData = $this->mapCompanyData($report);
            if(!$companyData)
                $companyData->valid = false;

            return $companyData;
        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');
        
        } catch (NotFoundException $e) {
            $companyData->valid = false;
            return $companyData;
        }
    }

    /**
     * Lookup company by REGON
     */
    private function lookupREGON(string $regon)
    {
        $companyData = new CompanyData;
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::REGON, $regon);

        try {
            $report = $this->send("DaneSzukaj", ["regon" => $regon]);
            if(!$report)
            {
                $companyData->valid = false;
                return $companyData;
            }

            $report["regon"] = $regon;

            $companyData = $this->mapCompanyData($report);
            if(!$companyData)
                $companyData->valid = false;

            return $companyData;
        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');

        } catch (NotFoundException $e) {
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::REGON, $regon);
            $companyData->valid = false;
            return $companyData;
        }
    }

    /**
     * Lookup company by KRS
     */
    private function lookupKRS(string $krs)
    {
        $companyData = new CompanyData;
        $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::KRS, $krs);

        try {
            $report = $this->send("DaneSzukaj", ["krs" => $krs]);
            if(!$report)
            {
                $companyData->valid = false;
                return $companyData;
            }

            $report["krs"] = $krs;

            $companyData = $this->mapCompanyData($report);
            if(!$companyData)
                $companyData->valid = false;

            return $companyData;
        } catch (InvalidUserKeyException $e) {
            throw new GusReaderException('Checking status currently not available [Invalid Api key]');
        
        } catch (NotFoundException $e) {
            $companyData->valid = false;
            return $companyData;        
        }
    }

    protected function mapCompanyData(array $gusReport): ?CompanyData
    {
        $report = $gusReport;

        if (isset($gusReport[0])) {
            $tmp_index = null;
            foreach ($gusReport as $index => $entry) {
                if (!is_array($entry))
                    continue;

                if ($tmp_index != null || (isset($entry["SilosID"]) && $entry["SilosID"] == 6))
                    $tmp_index = $index;
            }

            if (!$tmp_index)
                $report = $gusReport[0];
            else
                $report = $gusReport[$tmp_index];
        }

        if (!$report)
            return null;

        $activityReport = $this->getActivityReport($report);

        $companyAddress = new CompanyAddress;
        $companyAddress->country = 'PL';
        $companyAddress->postalCode = $report["KodPocztowy"];
        if($activityReport && isset($activityReport["ulica"]) && isset($activityReport["numer"]))
            $companyAddress->address = ($activityReport["ulica"] != "" ? $activityReport["ulica"] : $report["Miejscowosc"]) . ' ' . $activityReport["numer"] . (isset($activityReport["lokal"]) && trim($activityReport["lokal"]) !== "" ? "/" . $activityReport["lokal"]: "");
        else
            $companyAddress->address = $report["Ulica"];
        $companyAddress->city = $report["Miejscowosc"];

        $companyData = new CompanyData;
        if(isset($activityReport["dataZakonczeniaDzialalnosci"]) && $activityReport["dataZakonczeniaDzialalnosci"] != "")
            $companyData->valid = false;
        else 
            $companyData->valid = true;

        $companyData->name = $report["Nazwa"];
        $companyData->startDate = $activityReport["dataRozpoczeciaDzialalnosci"] ?? null;
        $companyData->endDate = $activityReport["dataZakonczeniaDzialalnosci"] ?? null;
        $companyData->representatives = [];

        $companyData->identifiers = [];
        if(isset($report["nip"]))
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::NIP, $report["nip"]);
        if(isset($report["regon"]))
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::REGON, $report["regon"]);
        if(isset($report["krs"]))
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::KRS, $report["krs"]);

        $krsIdentifier = $activityReport["numerWRejestrzeEwidencji"] ?? null;
        if ($krsIdentifier) {
            $companyData->identifiers[] = new CompanyIdentifier(IdentifierType::KRS, $krsIdentifier);
        }

        $websiteAddress = $activityReport["adresStronyinternetowej"] ?? null;
        if ($websiteAddress) {
            $companyData->websiteAddresses[] = $websiteAddress;
        }
        $emailAddress = $activityReport["adresEmail"] ?? null;
        if ($emailAddress) {
            $companyData->emailAddresses[] = $emailAddress;
        }
        $faxNumber = $activityReport["numerFaksu"] ?? null;
        if ($faxNumber) {
            $companyData->faxNumbers[] = $faxNumber;
        }

        $companyData->mainAddress = $companyAddress;

        $companyData->pkdCodes = explode(",", $activityReport["kod_pkd"] ?? "");

        return $companyData;
    }

    protected function getActivityReport(array $report): array
    {
        switch($report["Typ"])
        {
            default:
            case "P":
            case "LP":
                $prefix = "praw_";
                $prefix2 = false;
                $raport_type = "PublDaneRaportPrawna";
                $raport_pkd = "PublDaneRaportDzialalnosciPrawnej";
                break;
            case "F":
            case "LF":
                $prefix = "fiz_";
                $prefix2 = "fizC_";
                $raport_pkd = "PublDaneRaportDzialalnosciFizycznej";
                break;
        }

        switch($report["SilosID"])
        {
            case 1:
                $raport_type = "PublDaneRaportDzialalnoscFizycznejCeidg";
                break;
            case 2:
                $raport_type = "PublDaneRaportDzialalnoscFizycznejRolnicza";
                break;
            case 3:
                $raport_type = "PublDaneRaportDzialalnoscFizycznejPozostala";
                break;
            case 4:
                $raport_type = "PublDaneRaportDzialalnoscFizycznejWKrupgn";
                break;
            case 6:
                //$raport_type = "PublDaneRaportDzialalnosciPrawnej";
                $raport_type = "PublDaneRaportPrawna";
                break;

            default:
                throw new GusReaderException("Invalid SiloId");
        }

        $ret = $this->send("DanePobierzPelnyRaport", ["regon" => $report["Regon"], "raport_type" => $raport_type]);

        if(!$ret)
            throw new GusReaderException(_("Nie można pobrać informacji"));

        $pkd_codes = [];
        $raport_pkd_data = $this->send("DanePobierzPelnyRaport", ["regon" => $report["Regon"], "raport_type" => $raport_pkd]);

        if(is_iterable($raport_pkd_data))
        {
            foreach($raport_pkd_data as $key => $pkd_entry)
            {
                if(isset($pkd_entry[$prefix . "pkd_Przewazajace"]) && $pkd_entry[$prefix . "pkd_Przewazajace"])
                    $pkd_codes[] = $pkd_entry[$prefix . "pkd_Kod"];
            }
        }

        $data_mapping = [
            //"regon" => "regon9",
            "nip" => "nip",
            "nazwa" => "nazwa",
            "wojewodztwo" => "adSiedzWojewodztwo_Nazwa",
            "powiat" => "adSiedzPowiat_Nazwa",
            "gmina" => "adSiedzGmina_Nazwa",
            "ulica" => "adSiedzUlica_Nazwa",
            "numer" => "adSiedzNumerNieruchomosci",
            "lokal" => "adSiedzNumerLokalu",
            "miasto" => "adSiedzMiejscowosc_Nazwa",
            //"kod_pocztowy" => "adSiedzKodPocztowy",
            "poczta" => "adSiedzMiejscowoscPoczty_Nazwa",
            "kod_1" => "podstawowaFormaPrawna_Symbol",
            "kod_2" => "szczegolnaFormaPrawna_Symbol",
            "kod_3" => "formaWlasnosci_Symbol",
            //"kod_pkd" => "",
            "rejestr" => "rodzajRejestruEwidencji_Nazwa",
            "data" => "dataPowstania",
            "data2" => "dataZakonczeniaDzialalnosci",
        ];

        $data = [];
        foreach ($ret as $key => $value)
        {
            $key2 = $key;
            if(substr($key, 0, strlen($prefix)) == $prefix)
                $key2 = substr($key, strlen($prefix));
            elseif($prefix2 && substr($key, 0, strlen($prefix2)) == $prefix2)
                $key2 = substr($key, strlen($prefix2));

            if($value == [])
                $value = "";

            $data[$key2] = $value;
        }

        $data["kod_pocztowy"] = is_array($report["KodPocztowy"]) ? ($report["KodPocztowy"][0] ?? "") : $report["KodPocztowy"];
        $data["kod_pkd"] = $pkd_codes ? implode(",", $pkd_codes) : "";

        foreach ($data_mapping as $field => $value)
        {
            if(!isset($data[$value]) || is_array($data[$value]))
            {
                $data[$field] = "";
                continue;
            }

            $data[$field] = trim($data[$value]);
        }

        $data["nip"] = (isset($data["nip"]) && $data["nip"]) ? $data["nip"] : ($report["nip"] ?? "");
        $data["name"] = $data["nazwa"];
        if(isset($data["ulica"]) && isset($data["numer"]))
            $data["address"] = ($data["ulica"] != "" ? $data["ulica"] : $data["miasto"]) . " " . $data["numer"] . ($data["lokal"] ? "/" . $data["lokal"] : '');
        else
            $data["address"] = $report["miasto"];

        $data["zip"] = $data["kod_pocztowy"];
        $data["city"] = $data["miasto"];

        return $data;
    }

    protected function send($action, $data)
    {
        $cmd = "";
        switch($action)
        {
            case "Zaloguj":
                $cmd = $this->getGUSTemplate("Zaloguj", $data);
                break;

            case "DaneSzukaj":
                $cmd = $this->getGUSTemplate("DaneSzukaj", $data);
                break;
            case "DanePobierzPelnyRaport":
                $cmd = $this->getGUSTemplate("DanePobierzPelnyRaport", $data);
                break;
        }

        try
        {
            if(!$this->sid)
                $this->SOAP = new SoapClient($this->wsdl_url, ["exceptions" => true, "trace" => true, "soap_version" => SOAP_1_2, "cache_wsdl" => WSDL_CACHE_DISK]);

            $ret = $this->SOAP->__doRequest($cmd, $this->service_url, "http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/$action", SOAP_1_2);
        }
        catch (Throwable $e)
        {
            throw new GusReaderException($e->getMessage());
        }

        $ret = preg_replace("/^.*?(?=<s:Envelope )/s", "", $ret);
        $ret = preg_replace("/(?<=<\/s:Envelope>).*?$/s", "", $ret);

        $ret = @simplexml_load_string($ret);

        $ret->registerXPathNamespace("g", "http://CIS/BIR/PUBL/2014/07");

        if($action == "Zaloguj")
        {
            $this->sid = (string) ($ret->xpath("//g:ZalogujResponse/g:ZalogujResult")[0] ?? "");

            $context = stream_context_create(['http'=> ['header' => "sid: {$this->sid}\r\n"]]);

            $this->SOAP = new SoapClient($this->wsdl_url, ["exceptions" => true, "trace" => true, "soap_version" => SOAP_1_2, "stream_context" => $context]);
        }
        else
        {
            $tmp = (string) ($ret->xpath("//g:{$action}Response/g:{$action}Result")[0] ?? "");

            $result = json_decode(json_encode(simplexml_load_string($tmp, "SimpleXMLElement", LIBXML_NOCDATA)), true);
            $ret = $result["dane"] ?? null;
        }

        libxml_clear_errors();
        return $ret;
    }

    private static function getGUSTemplate(string $action, array $params): string
    {
        switch ($action) {
            case "Zaloguj":
                $key = $params["key"];

                $body = '<ns:Zaloguj>
            <ns:pKluczUzytkownika>' . $key . '</ns:pKluczUzytkownika>
        </ns:Zaloguj>';
                break;

            case "DaneSzukaj":
                $body = '

      <ns:DaneSzukaj>
         <ns:pParametryWyszukiwania>
            ' . (isset($params['krs']) ? '<dat:Krs>' . $params['krs'] . '</dat:Krs>' : '') . '
            ' . (isset($params['krsy']) ? '<dat:Krsy>' . $params['krsy'] . '</dat:Krsy>' : '') . '
            ' . (isset($params['nip']) ? '<dat:Nip>' . $params['nip'] . '</dat:Nip>' : '') . '
            ' . (isset($params['nipy']) ? '<dat:Nipy>' . $params['nipy'] . '</dat:Nipy>' : '') . '
            ' . (isset($params['regon']) ? '<dat:Regon>' . $params['regon'] . '</dat:Regon>' : '') . '
            ' . (isset($params['regon14']) ? '<dat:Regony14zn>' . $params['regon14'] . '</dat:Regony14zn>' : '') . '
            ' . (isset($params['regon9']) ? '<dat:Regony9zn>' . $params['regon9'] . '</dat:Regony9zn>' : '') . '
         </ns:pParametryWyszukiwania>
      </ns:DaneSzukaj>';

                break;

            case "DanePobierzPelnyRaport":
                $regon = $params["regon"];
                $raport_type = $params["raport_type"];

                $body = '

        <ns:DanePobierzPelnyRaport>
            <ns:pRegon>' . $regon. '</ns:pRegon>
            <ns:pNazwaRaportu>' .  $raport_type . '</ns:pNazwaRaportu>
        </ns:DanePobierzPelnyRaport>
';
                break;
        }

        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ns="http://CIS/BIR/PUBL/2014/07" ' . ($action == "DaneSzukaj" ? 'xmlns:dat="http://CIS/BIR/PUBL/2014/07/DataContract"' : "") . '>
    <soap:Header xmlns:wsa="http://www.w3.org/2005/08/addressing">
        <wsa:Action>http://CIS/BIR/PUBL/2014/07/IUslugaBIRzewnPubl/' . $action . '</wsa:Action>
        <wsa:To>https://wyszukiwarkaregontest.stat.gov.pl/wsBIR/UslugaBIRzewnPubl.svc</wsa:To>
    </soap:Header>
    <soap:Body>
        ' . $body . '
    </soap:Body>
</soap:Envelope>';
    }
}