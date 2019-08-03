<?php
require __DIR__ .'/vendor/autoload.php';

use Aftermarketpl\CompanyLookup\Env;

$vies = new Aftermarketpl\CompanyLookup\ViesReader();
$vat =  new Aftermarketpl\CompanyLookup\VatReader();
$ceidg = new Aftermarketpl\CompanyLookup\CeidgReader(Env::$ceidgapikey);
$gus = new Aftermarketpl\CompanyLookup\GusReader(Env::$gusapikey);

var_dump($gus->lookup('6422995563'));
