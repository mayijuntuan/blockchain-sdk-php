<?php

require_once __DIR__.'/../vendor/autoload.php';

use MayijuntuanSdk\Client;

require_once __DIR__ . "/config.php";

$client = new Client( $app_key, $app_private_key, $gateway_url );


$currency = 'eth';
$res = $client->getAddress( $currency );
var_dump($res);

