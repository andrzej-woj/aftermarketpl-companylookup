<?php

namespace Aftermarketpl\CompanyLookup\Traits;

use Aftermarketpl\CompanyLookup\Exceptions\ValidatorException;

trait ValidatesVatid {

    public static function validateVatid($vatid, $default_country = '') 
    {
        if(!is_string($vatid)) 
            throw new ValidatorException("Incorrect vatid");
        
        $country = false;
        preg_match("/^[a-zA-Z]{2}/", $vatid, $matches);
        if(isset($matches[0]))
        {
            $country = $matches[0];
        }

        if(!$country && $default_country) 
        {
            $vatid = $default_country . $vatid;
            $country = $default_country;
        }
        // Validator factory
        $validator = "\\Aftermarketpl\\CompanyLookup\Validators\\" . $country;
        if(class_exists($validator))
        {
            return $validator::resolve($vatid);
        }
        else
        {
            return $vatid;
        }

    }
}