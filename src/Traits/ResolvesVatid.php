<?php

namespace Aftermarketpl\CompanyLookup\Traits;

use Aftermarketpl\CompanyLookup\Helpers\Validator;

trait ResolvesVatid {

    public static function resolveVatid($vatid) 
    {
        $country = '';
        preg_match("/^[a-zA-Z]{2}/", $vatid, $matches);
        if(isset($matches[0]))
        {
            $country = $matches[0];
        }

        Validator::validateCountry($country);

        return [
            $country,
            str_replace(" ", "", str_replace($country, '', $vatid)),
        ];
    }

}