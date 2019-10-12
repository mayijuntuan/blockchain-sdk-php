<?php

require_once __DIR__.'/../vendor/autoload.php';

use MayijuntuanSdk\Client;

require_once __DIR__ . "/config.php";


$client = new Client( $app_key, $app_private_key );
$params = $_POST;

$res = $client->notify($params);
if( false === $res )
    exit("签名错误");

switch( $params['type'] ){
    case 'deposit':
            echo "子用户". $params['app_uid'] ."使用协议". $params['protocol'] ."充值". $params['amount'] . " ". $params['currency'] .",充值交易id:" . $params['id'] . '，交易hash:' . $params['hash'];
        break;
    case 'withdrawSend':
            echo "提现交易id:" . $params['id'] . '已发送，交易hash:' . $params['hash'];
        break;
    case 'withdrawComplete':
            echo "提现交易id:" . $params['id'] . '已完成';
        break;
}//end switch

