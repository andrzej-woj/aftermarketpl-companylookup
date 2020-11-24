<?php

declare(strict_types=1);

namespace Aftermarketpl\CompanyLookup;

use Aftermarketpl\CompanyLookup\Models\CompanyData;

interface Reader
{
    public function lookup(string $id, string $type = IdentifierType::NIP) : Companydata;
}
