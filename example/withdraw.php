<?php

require_once __DIR__.'/../vendor/autoload.php';

use MayijuntuanSdk\Client;

require_once __DIR__ . "/config.php";

$client = new Client( $app_key, $app_private_key, $gateway_url );


$currency = 'eth';
$protocol = 'eth';
$address = '0xbb731f25a8e2c75710ae354ff536b1426bb8e19f';
$amount = 0.001;

$res = $client->withdraw( $currency, $protocol, $address, $amount );
var_dump($res);

