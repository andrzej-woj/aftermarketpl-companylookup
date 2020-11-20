<?php

namespace Aftermarketpl\CompanyLookup\Models;

class CompanyRepresentative
{
    public $firstName;
    public $lastName;

    public function __construct($firstName, $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function equals(CompanyRepresentative $representative): bool
    {
        return $this->firstName === $representative->firstName
            && $this->lastName === $representative->lastName;
    }
}
