<?php
require __DIR__ .'/vendor/autoload.php';

use Aftermarketpl\CompanyLookup\Env;

$vies = new Aftermarketpl\CompanyLookup\ViesReader();
$vat =  new Aftermarketpl\CompanyLookup\VatReader();
//$ceidg = new Aftermarketpl\CompanyLookup\CeidgReader(Env::$ceidgapikey);
//$gus = new Aftermarketpl\CompanyLookup\GusReader(Env::$gusapikey);
$kas =  new Aftermarketpl\CompanyLookup\KasReader();

var_dump($kas->lookup('7393600768'));
