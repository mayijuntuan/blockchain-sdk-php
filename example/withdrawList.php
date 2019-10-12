<?php

require_once __DIR__.'/../vendor/autoload.php';

use MayijuntuanSdk\Client;

require_once __DIR__ . "/config.php";

$client = new Client( $app_key, $app_private_key );


$currency = '';
$page = 1;
$pagesize = 20;
$res = $client->getWithdrawList( $currency, $page, $pagesize );
var_dump($res);

