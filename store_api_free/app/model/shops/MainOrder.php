<?php

/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\model\shops;

use app\model\shops\Delivery as DeliveryShopModel;
use app\model\shops\ShopOrder as ShopOrderModel;
use app\model\Goods as GoodsModel;
use app\model\OrderGoods as OrderGoodsModel;
use app\model\SysConfig;
use app\model\UserAddress;
use app\Request;
use bases\BaseCommon;
use bases\BaseModel;
use exceptions\OrderException;
use GzhPay\JsApi;
use think\facade\Db;
use app\model\UserCoupon as UserCouponModel;
use think\facade\Log;
use app\model\GoodsSku;

class MainOrder extends BaseModel
{

    public function gzh_create_order($post, $uid,$openid)
    {
        Db::startTrans();
        try {
            $order_data = $this->CreateCartOrder($post, $uid); //创建订单
            $gzh['web_name'] = SysConfig::where(['key' => 'web_name'])->value('value');
            $gzh['api_url'] = SysConfig::where(['key' => 'api_url'])->value('value');
            $res = (new JsApi())->gzh_pay($openid, $order_data['order_data'], $gzh);
            $res = json_decode($res, true);
            Db::commit();
            return $res;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }

    }
    //创建订单--小程序+公众号
    public function CreateCartOrder($post, $uid)
    {
        Db::startTrans();
        try {
            $main_order = $this->setMainOrder($post, $uid); //设置主订单
            $main_order_id = $main_order->id;
            $shops=$post['json'];
            $orderGoodsModel = new OrderGoodsModel;
            foreach ($shops as $k=>$v){
                $order_data = $this->setOrderData($post,$v, $uid,$main_order_id );//组装订单数据
                $order=ShopOrderModel::create($order_data); //创建订单
                $oid = $order->id;
                if($oid<1){
                    throw new OrderException(['msg' => "拆分订单失败"]);
                }
                $goods = $this->setOrderGoods($oid, $v['pro'],$uid);//组装订单商品数据
                $orderGoodsModel->saveall($goods);
            }
            $data['id'] = $main_order_id;
            $data['order_data'] = $main_order;
            Db::commit();
            return $data;
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => $e->getMessage()]);
        }
    }

    //设置主订单
    public function setMainOrder($post, $uid)
    {
        $data = [];
        $orderSn = "M" . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        $data['order_num'] = $orderSn;  //订单号
        $data['uid'] = $uid;
        $data['order_money'] = $post['money'];
        $data['order_from'] = $post['order_from'];  //订单来源 0:小程序,1:wap
        $data['payment_type'] = $post['payment_type']; //支付方式
        $res=self::create($data);
        if(!$res->id){
            throw new OrderException(['msg' => '主订单创建失败']);
        }
        return $res;
    }

    /**
     * 拆分为店铺订单
     */
    public function setOrderData($post,$shop_pro,$uid,$main_order_id)
    {
        $data = [];
        $data['mid']=$main_order_id;
        $data['order_num'] = (new BaseCommon())->makeOrderNum();  //订单号
        $data['order_from'] = $post['order_from'];  //订单来源 0:小程序,1:wap
        $data['payment_type'] = $post['payment_type']; //支付方式
        $data['coupon_id'] = $post['coupon_id']; //支付方式
        $data['message'] = $post['msg'] ? $post['msg'] : ''; //留言
        $data['invite_code'] = array_key_exists('sfm',$post) ? $post['sfm'] :''; //身份码
        $data['invoice'] = array_key_exists('invoice',$post) ? $post['invoice'] : 0; //发票类型
        $data['user_id'] = $uid;
        $data['user_ip'] = (new Request())->ip(); //买家IP
        if(!$shop_pro['pro']){
            throw new OrderException(['msg' => '拆分订单数据错误']);
        }
        $data['order_money']=0;
        $sum_money=0;
        Db::startTrans();
        try {
        foreach ($shop_pro['pro'] as $k => $v) {
            $data['type'] = GoodsModel::where('goods_id', $v['goods_id'])->value('style');
            $price=GoodsModel::where('goods_id',$v['goods_id'])->value('price');


            Log::error($v);
            if(isset($v['sku'])){
                $price=$v['sku']['price'];
                $v['price']=$v['sku']['price'];
            }

            $sum_money+=$price* $v['num'];
            $data['goods_money'] = $v['price'];
            $data['order_money'] += $v['price'] * $v['num'];
        }
            Db::commit();
        } catch (\Exception  $ex) {
            Db::rollback();// 回滚事务
            Log::error('主订单与拆分订单更新失败:' . $ex->getMessage());
            return false;
        }


        $data['shop_id'] = $shop_pro['info']['shop_id'];

        $paymoney=$this->computeCouponMoney($post['coupon_id'],$uid,$shop_pro['pro']);

        /**价格验证**/

        //获取信息及用户地址
        $user_data = UserAddress::getUserInfo($uid);//直接用默认地址
        $dv_pro=$this->set_de_pro($post['json']);
        $data['shipping_money'] = DeliveryShopModel::computeShipping($user_data['region_id'],$dv_pro); //订单运费

        $data['order_money'] = $data['shipping_money'] + $data['order_money'];
        $sum_money+= $data['shipping_money'];
        if($paymoney>$data['order_money']){
            $data['order_money']=0;
        }else{
            $data['order_money']=$data['order_money']-$paymoney;

        }
        if ($data['order_money'] !==  $sum_money || $data['order_money'] <= 0) {
            Log::error("价格错误");
            throw new OrderException(['msg' => '价格错误x:'.$sum_money.'-'.$data['order_money']]);
        }


//        if ($data['order_money'] <= 0) {
//            throw new OrderException(['msg' => '价格错误']);
//        }
        if ($data['type']) {
            $data['yz_code'] = rand(100000, 999999);
        }
        //获取信息及用户地址
        $user_data = UserAddress::getUserInfo($uid);//直接用默认地址
        unset($user_data['region_id']);
        $arr = array_merge($data, $user_data);
        return $arr;
    }

    public function set_de_pro($data)
    {
        $arr=[];
        $x=0;
        foreach ($data as $k=>$v){
            foreach ($v['pro'] as $pro){
                $arr[$x]=$pro;
                $x++;
            }
        }
        return $arr;
    }
    /**
     * 组装普通商品订单的 商品数据
     */
    public function setOrderGoods($order_id, $pro, $uid)
    {
        $data = [];
        foreach ($pro as $k => $v) {
            $pinfo = GoodsModel::getProductByID($v['goods_id']);//获取商品及关联数据
            $data[$k]['goods_id'] = $v['goods_id'];
            $data[$k]['goods_name'] = $pinfo['goods_name'];
            $data[$k]['sku_id'] ="";
            if (isset($v['sku_id'])) {
                $data[$k]['sku_id'] = $v['sku_id'];
                foreach ($pinfo['sku'][0]['json']['list'] as $key => $value) {
                    if ($value['id'] == $v['sku_id']) {
                        $data[$k]['price'] = $value['price'];
                        $data[$k]['sku_name'] = (array_key_exists('s1_name', $value) ? $value['s1_name'] : '')
                            . ' ' . (array_key_exists('s2_name', $value) ? $value['s2_name'] : '')
                            . ' ' . (array_key_exists('s3_name', $value) ? $value['s3_name'] : '');
                    }
                }
            } else {
                $data[$k]['price'] = $pinfo['price'];
            }
            $data[$k]['num'] = $v['num'];
            $data[$k]['cost_price'] = $pinfo['cost_price'];
            $data[$k]['user_id'] = $uid;
            $data[$k]['order_id'] = $order_id;
            $data[$k]['total_price'] = $data[$k]['price'] * $data[$k]['num'];
            $data[$k]['pic_id'] = $pinfo['img_id'];  //商品图片ID
        }
        return $data;
    }


    //计算商品总价
    private function computeGoodsMoney($data)
    {
        $all_money = 0;
        foreach ($data as $k => $v) {
            $goods = GoodsModel::with(['goodsSku'])->where('goods_id', $v['goods_id'])->find()->toArray();
            if (isset($v['sku_id'])) {
                foreach ($goods['goods_sku']['json']['list'] as $key => $value) {
                    if ($v['sku_id'] == $value['id']) {
                        $money = $v['num'] * $value['price'];
                        $all_money += $money;
                    }
                }
            } else {
                $money = $v['num'] * $goods['price'];
                $all_money += $money;
            }
        }

        return round($all_money, 2);
    }

    //计算优惠券
    private function computeCouponMoney($coupon_id, $uid, $data)
    {

        if ($coupon_id) {
            $coupon = UserCouponModel::where('id', $coupon_id)->where('user_id', $uid)->where('status', 0)->whereTime('end_time', '>', time())->find();


            if (!$coupon) {
                throw new OrderException(['msg' => '优惠券使用错误']);
            }
            $all_money = 0;

            foreach ($data as $k => $v) {
                $goods = GoodsModel::with('goodsSku')->where('goods_id', $v['goods_id'])->find()->toArray();

                if (isset($v['sku_id'])) {
                    $skuPrice = GoodsSku::getSkuPrice($goods, $v['sku_id']);//获取规格价格
                    if ($skuPrice) {
                        $money = $this->getCouponMoney($coupon, $v['num'], $skuPrice, $v['goods_id']);
                        $all_money += $money;
                    }
                } else {

                    $money = $this->getCouponMoney($coupon, $v['num'], $goods['price'], $v['goods_id']);
                    $all_money += $money;
                }
            }
            if ($all_money < $coupon['full']) {
                throw new OrderException(['msg' => "优惠券使用错误$all_money-".$coupon['full']]);
            }
            $coupon->save(['status' => 1]);
            return $coupon['reduce'];
        }
        return 0;
    }


    //计算价格
    private function getCouponMoney($coupon, $num, $price, $goods_id)
    {

        $total_money = 0;
        if ($coupon['goods_ids'] != 0) {
            $coupon_ids = explode(',', $coupon['goods_ids']);
            if (in_array($goods_id, $coupon_ids)) {
                $money = $price * $num;
                $total_money += $money;
            }
        } else {
            $money = $price * $num;
            $total_money += $money;
        }
       
        return $total_money;
    }



}