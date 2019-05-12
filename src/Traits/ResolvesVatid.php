<?php

namespace Aftermarketpl\CompanyLookup\Traits;

use Aftermarketpl\CompanyLookup\Helpers\Validator;

trait ResolvesVatid {

    public static function resolveVatid($vatid) 
    {
        $country = substr($vatid, 0, 2);
        Validator::validateCountry($country);
        return [
            $country,
            str_replace(" ", "", substr($vatid, 2)),
        ];
    }

}