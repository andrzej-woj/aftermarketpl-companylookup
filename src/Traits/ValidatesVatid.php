<?php

namespace Aftermarketpl\CompanyLookup\Traits;

use Aftermarketpl\CompanyLookup\Exceptions\ValidatorException;

trait ValidatesVatid {

    public static function validateVatid($vatid) 
    {
        if(!is_string($vatid)) 
            throw new ValidatorException("Incorrect vatid");
        
        $country = strtoupper(substr($vatid, 0, 2));


        // Validator factory
        $validator = "\\Aftermarketpl\\CompanyLookup\Validators\\" . $country;
        if(class_exists($validator))
        {
            $validator::resolve($vatid);
        }

    }
}