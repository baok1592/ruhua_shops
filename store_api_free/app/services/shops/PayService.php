<?php
 

namespace app\services\shops;


use app\model\Order as OrderModel;
use app\model\shops\MainOrder as MainOrderModel;
use app\model\SysConfig;
use app\services\TokenService;
use enum\OrderEnum;
use exceptions\BaseException;
use think\Exception;
use WxPay\WxPayApi;
use WxPay\WxPayData;
use WxPay\WxPayJsApiPay;
use WxPay\WxPayUnifiedOrder;


class PayService
{
    private $orderID;
    private $orderNO;

    function __construct($orderID)
    {
        if (!$orderID)
        {
            throw new Exception('订单号不允许为NULL');
        }
        $this->orderID = $orderID;
    }

    //验证订单、支付、改变状态
    public function pay()
    {
        $this->checkOrderValid();
        $order_money = MainOrderModel::where('id',$this->orderID)->value('order_money');//获取订单指定字段
        $order_money=100*$order_money;
        $result = $this->makeWxPreOrder($order_money);//进行微信支付
        return json($result);
    }

    //进行支付
    private function makeWxPreOrder($totalPrice)
    {
        $api_url=SysConfig::where(['key'=>'api_url'])->value('value');
        $openid = TokenService::getCurrentTokenVar('openid');
        if (!$openid)
        {
            throw new BaseException();
        }
        new WxPayData();
        $wxOrderData = new WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNO);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($totalPrice);
        $wxOrderData->SetBody('商城');
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url($api_url."/order/pay/notify");
        $res = $this->getPaySignature($wxOrderData);

        return $res;
    }

    //获取预支付订单
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = WxPayApi::unifiedOrder($wxOrderData);    //获取预支付id:prepay_id
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            \think\facade\Log::record($wxOrder, 'error');
            \think\facade\Log::record('获取预支付订单失败', 'error');
            throw new BaseException(['msg'=>$wxOrder['err_code_des']]);
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    //
    private function sign($wxOrder)
    {
        $jsApiPayData = new WxPayJsApiPay();
        $app_id = SysConfig::where('key','wx_app_id')->value('value');
        $jsApiPayData->SetAppid($app_id);
        $jsApiPayData->SetTimeStamp((string)time());

        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);

        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');

        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;

        unset($rawValues['appId']);

        return $rawValues;
    }

    private function recordPreOrder($wxOrder)
    {
        MainOrderModel::where('id', $this->orderID)
            ->update(['prepay_id' =>$wxOrder['prepay_id']]);
    }

    private function checkOrderValid()
    {
        $order = MainOrderModel::where('id', $this->orderID)->find();
        if (!$order){
            throw new BaseException(['msg' => '订单不存在或已过期']);
        }
        if (!TokenService::isValidOperate($order->uid)){
            throw new BaseException(['msg' => '非法操作']);
        }
        //非待支付状态，则终止
        if ($order->payment_state == 1)
        {
            throw new BaseException(
                [
                    'msg' => '订单已支付过啦',
                    'errorCode' => 60003,
                    'code' => 400
                ]);
        }
        $this->orderNO = $order->order_num;
        return true;
    }
}