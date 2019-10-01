<?php

namespace Aftermarketpl\CompanyLookup\Helpers;

use Aftermarketpl\CompanyLookup\Exceptions\AddressException;
use Aftermarketpl\CompanyLookup\Exceptions\ValidatorException;

class Validator {

    public static function getZipRegex(string $country): string
    {
        $pattern = [
            "DZ" => "\d{5}",
            "SA" => "\d{5}",
            "AU" => "\d{4}",
            "AT" => "(AT-)?\d{4}", //Austria - [AT-]NNNN
            "CN" => "\d{6}",
            "CY" => "\d{4}",
            "DK" => "\d{4}",
            "PH" => "\d{4}",
            "FI" => "\d{5}",
            "FR" => "\d{5}",
            "GR" => "\d{3}(\s)?\d{2}", //Greece - NNN[ ]NN
            "ES" => "\d{5}",
            "IN" => "\d{6}",
            "IS" => "\d{3}",
            "JP" => "\d{7}",
            "CR" => "\d{5}",
            "KW" => "\d{5}",
            "LS" => "\d{3}",
            "LI" => "\d{4}",
            "MY" => "\d{5}",
            "MX" => "\d{5}",
            "MC" => "\d{5}",
            "DE" => "\d{5}",
            "NO" => "\d{4}",
            "NZ" => "\d{4}",
            "ZA" => "\d{4}",
            "RU" => "\d{6}",
            "SG" => "\d{6}",
            "CH" => "\d{4}",
            "TH" => "\d{5}",
            "TN" => "\d{4}",
            "TR" => "\d{5}",
            "HU" => "\d{4}",
            "VN" => "\d{6}",
            "IT" => "\d{5}",
            "FO" => "\d{3}",
            "IR" => "\d{10}",
            "ID" => "\d{5}",
            "BR" => "\d{5}\-\d{3}",
            "NL" => "\d{4}(\s)?[a-zA-Z]{2}", //Netherlands - NNNN[ ]AA
            "CA" => "[a-zA-Z]\d[a-zA-Z](\s)?\d[a-zA-Z]\d",
            "PL" => "\d{2}\-\d{3}",
            "SE" => "\d{3}(\s)?\d{2}", //Sweden - NNN[ ]NN
            "CZ" => "\d{3}(\s)?\d{2}", //Czech R. - NNN[ ]NN
            "SK" => "\d{3}(\s)?\d{2}", //Slovakia - NNN[ ]NN
            "BE" => "\d{4}", //Belgium - NNNN
            "BG" => "\d{4}", //Bulgaria - NNNN
            "EE" => "\d{5}", //Estonia - NNNNN
            "GB" => "[a-zA-Z]([a-zA-Z])?\d([a-zA-Z\d])?((\s)?\d[a-zA-Z]{2})?", //Great Britain - A[A]N[A/N] NAA
            "MT" => "[a-zA-Z]{3}(\s)?\d{2}(\d{2})?", //Malta - AAA NN[NN]
            "LT" => "LT-\d{5}", //Lithuania - LT-NNNN
            "LU" => "L-\d{4}", //Luxemburg - L-NNNN
            "LV" => "LV-\d{4}", //Latvia - LV-NNNN
            "RO" => "\d{6}", //Romania - NNNNNN
            "SI" => "(SI-)?\d{4}", //Slovenia - [CC-]NNNN
            "HR" => "\d{5}", //Croatia - NNNNN
            "PT" => "\d{4}-\d{3}", //Portugal - NNNN-NNN
        ];

        if(!isset($pattern[$country]))
            return "";
        else
            return $pattern[$country];
    }

    public static function checkZip($zip, $country)
    {
        $format = array(
            "DZ" => "12345",
            "SA" => "12345",
            "AU" => "1234",
            "AT" => "1234",
            "CN" => "123456",
            "CY" => "1234",
            "DK" => "1234",
            "PH" => "1234",
            "FI" => "12345",
            "FR" => "12345",
            "GR" => "12345",
            "ES" => "12345",
            "IN" => "123456",
            "IS" => "123",
            "JP" => "1234567",
            "CR" => "12345",
            "KW" => "12345",
            "LS" => "123",
            "LI" => "1234",
            "MY" => "12345",
            "MX" => "12345",
            "MC" => "12345",
            "DE" => "12345",
            "NO" => "1234",
            "NZ" => "1234",
            "ZA" => "1234",
            "RU" => "123456",
            "SG" => "123456",
            "CH" => "1234",
            "TH" => "12345",
            "TN" => "1234",
            "TR" => "12345",
            "HU" => "1234",
            "VN" => "123456",
            "IT" => "12345",
            "FO" => "123",
            "IR" => "1234567890",
            "ID" => "12345",
            "BR" => "12345-678",
            "NL" => "1234 AB",
            "CA" => "A1B 2C3",
            "PL" => "12-345",
            "SE" => "123 45",
            "CZ" => "123 45",
            "SK" => "123 45",
            "BE" => "1234",
            "BG" => "1234",
            "EE" => "12345",
            "MT" => "ABC 12",
            "LT" => "LT-12345",
            "LU" => "L-1234",
            "LV" => "LV-1234",
            "RO" => "123456",
            "SI" => "1234",
            "HR" => "12345",
            "PT" => "1234-567",
        );

        $regex = self::getZipRegex($country);
        if($regex && !preg_match("/^$regex$/", $zip))
        {
            if ($format[$country])
                throw new AddressException('Incorrect zip code, try' . $format[$country]);
            else
                throw new AddressException('Incorrect zip code');
        }
        
        if(!preg_match("/[\p{L}0-9]/u", $zip))
            throw new AddressException("Zip code must contain letter or number");
        
        if(mb_strlen($zip) > 16)
            throw new AddressException("Zip code too long");

        if(preg_match("/[^a-zA-Z0-9_ ,\.\(\)\s\/\-]/", $zip))
            throw new AddressException("Zip code has incorrect chars");
        
    }

    public static function validateCountry($country)
    {
        $allowedCountryCodes = ["ES","EL","IE","DZ","SA","AU","AT","CN","CY","DK","PH","FI","FR","GB","GR","IN","IS","JP","CR","KW","LS","LI","MY","MX","MC","DE","NO","NZ","ZA","RU","SG","CH","TH","TN","TR","HU","VN","IT","FO","IR","ID","BR","NL","CA","PL","SE","CZ","SK","BE","BG","EE","MT","LT","LU","LV","RO","SI","HR","PT"];
        if(! in_array($country, $allowedCountryCodes))
        {
            throw new ValidatorException("Incorrect country code");
        }
        return (true);
    }
}