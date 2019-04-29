<?php

namespace Aftermarketpl\CompanyLookup\Validators;

use Aftermarketpl\CompanyLookup\Exceptions\ValidatorException;

class PL 
{
    public static function resolve($value) 
    {
        $value = str_replace("-", "", $value);
        if(!is_string($value) && !is_int($value) && !is_float($value))
            throw new ValidatorException("Incorrect value type");

        if(strlen($value) != 10 && strlen($value) != 12)
            throw new ValidatorException("Incorrect value length");

        if(strlen($value) == 12)
        {
            $country = substr($value, 0, 2);
            if(!in_array(strtoupper($country), ['PL']))
                throw new ValidatorException("Incorrect value");

            $value = substr($value, 2);
        }
        $arrSteps = array(6, 5, 7, 2, 3, 4, 5, 6, 7);
        $intSum=0;
        for ($i = 0; $i < 9; $i++)
        {
            $intSum += $arrSteps[$i] * $value[$i];
        }
        $int = $intSum % 11;

        $intControlNr=($int == 10)?0:$int;

        if ($intControlNr != $value[9])
            throw new ValidatorException("Incorrect self sign value");
    }
}