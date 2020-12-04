<?php
namespace Aop\test;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/29 0029
 * Time: 15:37
 */
use Aop\AopClient;
use Aop\request\AlipayTradeWapPayRequest;
class AlipayEnter
{
    public function pay()
    {
        $aop = new AopClient ();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = 'your app_id';
        $aop->rsaPrivateKey = '请填写开发者私钥去头去尾去回车，一行字符串';
        $aop->alipayrsaPublicKey='请填写支付宝公钥，一行字符串';
        $aop->apiVersion = '1.0';
        $aop->postCharset='GBK';
        $aop->format='json';
        $aop->signType='RSA2';
        $request = new AlipayTradeWapPayRequest ();
        $request->setBizContent("{" .
            "    \"body\":\"对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。\"," .
            "    \"subject\":\"大乐透\"," .
            "    \"out_trade_no\":\"70501111111S001111219\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":9.00," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        $request->setNotifyUrl('');
        $result = $aop->pageExecute ($request);
        echo $result;
    }
    public function ali_notify_url()
    {

    }

}