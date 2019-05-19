<?php
require __DIR__ .'/vendor/autoload.php';
$ceidgapikey = '';

$vies = new Aftermarketpl\CompanyLookup\ViesReader();
$vat =  new Aftermarketpl\CompanyLookup\VatReader();
$ceidg = new Aftermarketpl\CompanyLookup\CeidgReader($ceidgapikey);
print_r($ceidg->lookup('PL6783041098'));