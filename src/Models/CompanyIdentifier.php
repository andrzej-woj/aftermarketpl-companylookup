<?php 

namespace Aftermarketpl\CompanyLookup\Models;


class CompanyIdentifier
{
    public $type; // vat, regon, krs
    public $id;

    /**
     * 
     */
    public function __construct($type, $id)
    {
        $this->type = $type;
        $this->id = $id;
    }
}