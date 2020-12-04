<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;


use app\model\shops\ShopOrder as ShopOrderModel;
use app\model\shops\MainOrder as MainOrderModel;
use app\services\shops\PayService;
use app\services\TokenService;
use app\validate\shops\ShopsOrderValidate;
use bases\BaseController;


class ShopOrder extends BaseController
{

    //商家查看自己所有订单
    public function order_list()
    {
        $sid = TokenService::getCurrentTokenVar('shop_id');
        $order = ShopOrderModel::with(['ordergoods','users'])->where('shop_id', $sid)->where('payment_state', 1)
            ->field('order_id,order_num,order_money,shipment_state,pay_time,payment_state,state,message,create_time,user_id')
            ->select();
        return app('json')->go($order);
    }

    public function shopGetOrderOne(int $id)
    {
        $sid = TokenService::getCurrentTokenVar('shop_id');
        $order = ShopOrderModel::with(['ordergoods','userInfo'])->where(['shop_id'=>$sid,'order_id'=>$id])->where('payment_state', 1)->find();
        return app('json')->go($order);
    }

    //发货
    public function fahuo()
    {
        $param=input('post.');
        $res = ShopOrderModel::editCourier($param);
        return app('json')->go($res);
    }

    //小程序创建订单
    public function create_order()
    {
        (new ShopsOrderValidate())->goCheck();
        $post=input('post.');
        $uid = TokenService::getCurrentUid();
        $res = (new MainOrderModel)->CreateCartOrder($post, $uid);//创建订单
        return app('json')->go($res);
    }

    //小程序支付
    public function xcx_pay(int $id)
    {
        $pay = new PayService($id);
        return $pay->pay();
    }

    //公众号创建订单
    public function gzh_create_order()
    {
        (new ShopsOrderValidate())->goCheck();
        $post=input('post.');
        $uid = TokenService::getCurrentUid();
        $openid = TokenService::getCurrentTokenvar('openid');
        $res = (new MainOrderModel)->gzh_create_order($post, $uid,$openid);//创建订单
        return app('json')->go($res);
    }

    //公众号支付
    public function gzh_pay(int $id)
    {
        $pay = new PayService($id);
        return $pay->pay();
    }

     public function getUidOrderAll()
    {
        $uid = TokenService::getCurrentUid();
        $data = ShopOrderModel::with(['shops','OrderGoods.GoodsImgs'=>function($q){
            return $q->field('id,order_id,goods_id,goods_name,sku_name,price,num,total_price as goods_money,pic_id');
        }])->where(['user_id' => $uid,'delete_time'=>null])
            ->field('order_id,order_num,user_id,type,state,payment_state,shipping_money,shipment_state,order_money,
            shop_id,activity_type,create_time')
            ->order('order_id desc')->select()->toArray();
        return app('json')->success($data);
    }

    //用户端某订单详情
    public function UserOrderOne(int $id)
    {
        $res=(new ShopOrderModel)->UserOrderOne($id);
        return app('json')->go($res);
    }
}