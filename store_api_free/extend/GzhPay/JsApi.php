<?php

namespace GzhPay;

use app\services\TokenService;
use exceptions\OrderException;
use GzhPay\lib\WxPayApi;
use GzhPay\lib\WxPayUnifiedOrder;
use think\Exception;
use think\facade\Log;
use GzhPay\lib\WxPayDataBase;
use exceptions\BaseException;


class JsApi
{

    public static function gzh_pay($openid, $order_data, $gzh)
    {
        Log::error('start');
        $jsApiParameters = "";
        try {
            $tools = new JsApiPay();
            new WxPayDataBase();
            $input = new WxPayUnifiedOrder();
            $input->SetBody($gzh['web_name']);
            $input->SetAttach("test");
            $input->SetOut_trade_no($order_data['order_num']);
            $input->SetTotal_fee($order_data['order_money'] * 100);
            $input->SetTime_start(date("YmdHis"));
            //$input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag("test");
            $input->SetNotify_url($gzh['api_url'] . "/order/back");
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openid);
            $config = new WxPayConfig();
            $order = WxPayApi::unifiedOrder($config, $input);
            Log::error(json_encode($order,JSON_UNESCAPED_UNICODE));
            $jsApiParameters = $tools->GetJsApiParameters($order);
        } catch (Exception $e) { 
            throw new OrderException(['msg' => $e->getMessage()]);
        }
        return $jsApiParameters;
    }


}

