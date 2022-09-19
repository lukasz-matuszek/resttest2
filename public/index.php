<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . "/../vendor/autoload.php";

use Lib\CurlService;
use Lib\RestClient;

//=======================
echo "REST TEST<br>";

$curlService = new CurlService();
$restClient = new  RestClient($curlService);

//$restClient->setJwtAuthMethod();
$result = $restClient->get(['drilldowns' => 'Nation', 'measures' => 'Population'], 'data');

print_r($result);

exit;
//=============================================
