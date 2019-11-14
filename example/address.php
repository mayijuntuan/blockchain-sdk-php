<?php

require_once __DIR__.'/../vendor/autoload.php';

use Mayijuntuan\Blockchain\Client;

require_once __DIR__ . "/config.php";


$client = new Client( $app_key, $app_private_key );


$currency = 'eth';
$res = $client->getAddress( $currency );
var_dump($res);

