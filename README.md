
## 安装

$ composer require mayijuntuan/sdk-php
```

## 运行环境

| Qiniu SDK版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          7.x         |  cURL extension,   5.3 - 5.6,7.0 |
|          6.x         |  cURL extension,   5.2 - 5.6 |

## 使用方法

### 

use MayijuntuanSdk\Client;
...
    $client = new Client( $app_key, $app_private_key );

    $currencyList = $client->getCurrencyList();
    $walletList = $client->getWalletList();
    $financeList = $client->getFinanceList();
    $depositList = $client->getDepositList( $currency, $page, $pagesize );
    $addressList = $client->getAddress( $currency, $uid );
    $withdrawList = $client->getWithdrawList( $currency, $page, $pagesize );
    $protocolList = $client->getProtocol( $currency );
    $hash = $client->withdraw( $currency, $protocol, $address, $amount );
...
```
