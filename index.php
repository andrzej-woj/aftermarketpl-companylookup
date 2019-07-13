<?php
require __DIR__ .'/vendor/autoload.php';

use Aftermarketpl\CompanyLookup\Env;

$vies = new Aftermarketpl\CompanyLookup\ViesReader();
$vat =  new Aftermarketpl\CompanyLookup\VatReader();
$ceidg = new Aftermarketpl\CompanyLookup\CeidgReader(Env::$ceidgapikey);
$gus = new Aftermarketpl\CompanyLookup\GusReader(Env::$gusapikey);

print_r($gus->lookup('5342532004'));
print_r($gus->fetchPKD());

print_r($gus->lookupRegon('365810667'));
print_r($gus->fetchPKD());

print_r($gus->lookupKrs('0000645598'));
print_r($gus->fetchPKD());