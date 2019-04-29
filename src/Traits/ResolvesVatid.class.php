<?php
namespace CompanyLookup\Traits;

trait ResolvesVatid {

    public static function resolveVatid($vatid) 
    {
        return [
            substr($vatid, 0, 2),
            str_replace(" ", "", substr($vatid, 2)),
        ];
    }

}