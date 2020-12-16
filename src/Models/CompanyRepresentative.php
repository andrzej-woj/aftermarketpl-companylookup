<?php

namespace Aftermarketpl\CompanyLookup\Models;

class CompanyRepresentative
{
    public $firstName;
    public $middleName;
    public $lastName;

    public function __construct($firstName, $middleName, $lastName)
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }

    public function equals(CompanyRepresentative $representative): bool
    {
        return $this->firstName === $representative->firstName
            && $this->middleName === $representative->middleName
            && $this->lastName === $representative->lastName;
    }
}
