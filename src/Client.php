<?php
namespace MayijuntuanSdk;

use Exception;


final class Client{

    private $app_key;
    private $app_private_key;
    private $gateway_url = 'http://api.mayijuntuan.com';


    public function __construct( $app_key, $app_private_key, $gateway_url='' ){

        if( empty($app_key) )
            throw new Exception('app_key can not empty' );
        $this->app_key = $app_key;

        if( empty($app_private_key) )
            throw new Exception('app_private_key can not empty' );
        $this->app_private_key = $app_private_key;

        if( !empty($gateway_url) )
            $this->gateway_url = $gateway_url;

    }


    //支付支持的币种列表
    public function getCurrencyList(){

        $action = '/asset/currency/list';
        return $this->api( $action );

    }

    //钱包余额列表
    public function getWalletList(){

        $action = '/asset/wallet/list';
        return $this->api( $action );

    }

    //财务记录列表
    public function getFinanceList( $currency='', $page=1, $pagesize=20 ){

        $action = '/asset/finance/list';
        $params = array(
            'currency' => $currency,
            'page' => $page,
            'pagesize' => $pagesize,
        );
        return $this->api( $action, $params );

    }

    //充值记录列表
    public function getDepositList( $currency='', $page=1, $pagesize=20 ){

        $action = '/asset/deposit/list';
        $params = array(
            'currency' => $currency,
            'page' => $page,
            'pagesize' => $pagesize,
        );
        return $this->api( $action, $params );

    }

    //充值地址
    public function getAddress( $currency, $app_uid=0 ){

        $action = '/asset/deposit/address';
        $params = array(
            'currency' => $currency,
            'app_uid' => $app_uid,
        );
        return $this->api( $action, $params );

    }

    //提现记录列表
    public function getWithdrawList( $currency='', $page=1, $pagesize=20 ){

        $action = '/asset/withdraw/list';
        $params = array(
            'currency' => $currency,
            'page' => $page,
            'pagesize' => $pagesize,
        );
        return $this->api( $action, $params );

    }

    //充值地址
    public function getProtocol( $currency ){

        $action = '/asset/withdraw/protocol';
        $params = array(
            'currency' => $currency,
        );
        return $this->api( $action, $params );

    }

    //提现
    public function withdraw( $currency, $protocol, $address, $amount ){

        $action = '/asset/withdraw/submit';
        $params = array(
            'currency' => $currency,
            'protocol' => $protocol,
            'address' => $address,
            'amount' => $amount,
        );
        return $this->api( $action, $params, 'post' );

    }

    //调用接口
    private function api( $action, $params=[], $method='get' ){

        $url = $this->gateway_url;
        $url .= $action;

        //app_key
        $app_key = $this->app_key;
        $params['app_key'] = $app_key;

        //加上时间戳
        $params['time'] = time();

        //待签名字符串
        $signContent = $this->getSignContent($params);

        //私钥
        $private_key = $this->app_private_key;

        //签名
        $sign = $this->sign( $signContent, $private_key );

        //加上签名
        $params['sign'] = $sign;

        $res = $this->request( $url, $params, $method );
        if( !empty($res->code) )
            throw new Exception('返回结果错误' . $res->message );

        return $res->data;

    }

    //生成待签名字符串
    private function getSignContent( $params ){

        ksort($params);

        $array = [];
        foreach( $params as $k => $v ){
            if( empty($v) )
                continue;
            $array[] = $k . '=' . $v;
        }//end foreach

        return implode('&', $array );

    }

    //签名
    private function sign( $data, $private_key ){

        $private_key = "-----BEGIN PRIVATE KEY-----\n" .
            wordwrap($private_key, 64, "\n", true) .
            "\n-----END PRIVATE KEY-----";

        $sign = '';
        openssl_sign( $data, $sign, $private_key, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);

    }

    //调用接口
    private function request( $url, $params, $method='get' ){

        $method = strtolower($method);

        //GET请求
        if( $method == 'get' && !empty($params) ){
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //POST请求
        if( $method == 'post' ){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params) );
        }

        $output = curl_exec($ch);//获取数据
        $errorno = curl_errno($ch);
        $error = curl_error($ch);
        $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        if( $errorno )
            throw new Exception('请求返回接口错误'.$errorno.$error );
        if( $code != 200 )
            throw new Exception('curl返回状态码错误'.$code );

        $res = json_decode($output);
        if( !is_object($res) )
            throw new Exception('curl返回内容格式错误' );

        return $res;

    }

}