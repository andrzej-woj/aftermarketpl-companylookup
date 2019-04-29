<?php

namespace Aftermarketpl\CompanyLookup\Traits;

trait ValidatesVatid {

    public static function validateVatid($vatid) 
    {
        if(!is_string($vatid)) 
            throw new ViesException("Incorrect vatid");
        
        $country = strtoupper(substr($vatid, 0, 2));


        // Validator factory
        $validator = "\\Aftermarketpl\\CompanyLookup\Validators\\" . $country;
        if(class_exists($validator))
        {
            $validator::resolve($vatid);
        }

    }
}