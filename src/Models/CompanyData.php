<?php 

namespace Aftermarketpl\CompanyLookup\Models;


class CompanyData
{
    public $valid;
    public $name;
    public $identifiers = [];
    public $startDate;
    public $mainAddress;
    public $additionalAddresses = [];
    public $phoneNumbers = [];
    public $faxNumbers = [];
    public $emailAddresses = [];
    public $websiteAddresses = [];
    public $pkdCodes = [];
    public $representatives = [];
}