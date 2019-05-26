<?php
require __DIR__ .'/vendor/autoload.php';

use Aftermarketpl\CompanyLookup\Env;

$vies = new Aftermarketpl\CompanyLookup\ViesReader();
$vat =  new Aftermarketpl\CompanyLookup\VatReader();
$ceidg = new Aftermarketpl\CompanyLookup\CeidgReader(Env::$ceidgapikey);

print_r($vat->lookup('5342532004'));