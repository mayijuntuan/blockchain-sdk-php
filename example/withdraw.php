<?php

require_once __DIR__.'/../vendor/autoload.php';

use Mayijuntuan\Blockchain\Client;

require_once __DIR__ . "/config.php";

$client = new Client( $app_key, $app_private_key );


$currency = 'eth';
$protocol = 'eth';
$address = '0x341d6a2abc090520edd2f61c1b5473448f439645';
$amount = 0.002;

$id = $client->withdraw( $currency, $protocol, $address, $amount );
var_dump($id);

