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
}
