<?php

namespace app\validate\shops;


use bases\BaseValidate;

class ShopsOrderValidate extends BaseValidate
{
    protected $rule = [
        'order_from' => 'require', //订单来源,0:小程序,1:wap
        'payment_type' => 'require', //支付方式 wx
        'json'=>'require',
        'money'=>'require'
    ];
    protected $message = [
        'msg.chsAlphaNum'        => '备注只能是汉字、字母和数字',
    ];
}
