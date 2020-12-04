<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/18
 * Time: 10:52
 */

namespace app\controller\common;

require __DIR__ . '/../../../vendor/alipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';
require __DIR__ . '/../../../vendor/alipay/wappay/service/AlipayTradeService.php';
require __DIR__ . '/../../../vendor/alipay/aop/AopClient.php';


use app\model\Order as OrderModel;
use app\Request;
use bases\BaseController;
use exceptions\BaseException;
use think\facade\Log;
use app\model\SysConfig;

class ZfbPay extends BaseController
{

    public function get_order()
    {
        $order=OrderModel::select();
        return app('json')->go($order);

    }





    public function aliorder($order_id)
    {

        $order=OrderModel::where("order_id",$order_id)->find();
        log::error($order);
        if(!$order){
            throw new BaseException(['msg' => '订单不存在']);
        }
        if($order['payment_state']==1){
            throw new BaseException(['msg' => '订单已支付']);
        }

        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key','zfb_back'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }

        $out_trade_no = $order['order_num'];
        $total_amount = $order['order_money'];
        $aop=new \AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = $arr['zfb_appid'];
        $aop->rsaPrivateKey=$arr['zfb_private_key'];
        $aop->alipayrsaPublicKey=$arr['zfb_public_key'];
        $aop->apiVersion = '1.0';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $aop->signType='RSA2';
        $request=new \AlipayTradeWapPayRequest();
        $request->setBizContent("{" .
            "    \"body\":\"\"," .
            "    \"subject\":\"订单支付\"," .
            "    \"out_trade_no\":\"$out_trade_no\"," .
            "    \"timeout_express\":\"90m\"," .
            "    \"total_amount\":$total_amount," .
            "    \"product_code\":\"QUICK_WAP_WAY\"" .
            "  }");
        $request->setNotifyUrl($arr['zfb_back']."order/alinotify_order");
        $request->setReturnUrl($arr['zfb_back']."h5");
        $result = $aop->pageExecute($request);

        return $result;
    }

    public function ordernotify(Request $request)
    {
        $zfb_data=SysConfig::where('key','in',['zfb_appid','zfb_private_key','zfb_public_key'])->select()->toArray();
        if(!$zfb_data){
            throw new OrderException(['msg'=>'验证码秘钥错误']);
        }
        $arr=[];
        foreach ($zfb_data as $k=>$v){
            $arr[$v['key']]=$v['value'];
        }

        $config = array (
            //应用ID,您的APPID。
            'app_id' => $arr['zfb_appid'],

            //商户私钥，您的原始格式RSA私钥
            'merchant_private_key'=>$arr['zfb_public_key'],
            //异步通知地址
            'notify_url' => "",

            //同步跳转
            'return_url' => "",

            //编码格式
            'charset' => "UTF-8",

            //签名方式
            'sign_type'=>"RSA2",

            //支付宝网关
            'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key' => $arr['zfb_public_key'],

        );
        $arr=$request->param();
        $alipaySevice = new \AlipayTradeService($config);
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //商户订单号
            $out_trade_no = $arr['out_trade_no'];
            //支付宝交易号
            $trade_no = $arr['trade_no'];
            //交易状态
            $trade_status = $arr['trade_status'];


            //交易金额
            $total_amount = $arr['total_amount'];
            if($arr['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            if($arr['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                //如果有做过处理，不执行商户的业务程序
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //此处应该更新一下订单状态，商户自行增删操作
                // Order::where('id',1)->update(['type'=>1]);
                $order=OrderModel::update(['payment_state'=>1],['order_num'=> $trade_no]);

                echo 'success';
            }
        }else {
            //验证失败
            echo "fail";
        }
    }
}