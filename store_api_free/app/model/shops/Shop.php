<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;


use app\model\Image;
use app\model\Order;
use app\services\TokenService;
use bases\BaseCommon;
use bases\BaseModel;
use exceptions\ShopsException;
use think\facade\Cache;

class Shop extends BaseModel
{


    //用户端获取店铺信息
    public function shop_id_info($shop_id)
    {
        $data = self::where(['shop_id'=>$shop_id])->field('shop_id,shop_name,shop_description,
        shop_address,position,xinxin,create_time,shop_pic_url')->find();
        if(!$data){
            throw new ShopsException(['msg'=>"商家店铺不存在"]);
        }
        $data['position'] = json_decode($data['position'], true);
        return $data;
    }

    //商家端获取店铺信息
    public function shops_id_info($shop_id)
    {
        $data = self::where(['shop_id'=>$shop_id])->find();
        if(!$data){
            throw new ShopsException(['msg'=>"商家店铺不存在"]);
        }
        $data['position'] = json_decode($data['position'], true);
        return $data;
    }


    //商家端获取店铺信息
    public function shop_info($shop_id)
    {
        $data = self::with(['imgs','card'])->where('shop_id', $shop_id)->find();
        if(!$data){
            throw new ShopsException(['msg'=>"商家店铺不存在"]);
        }
        $data['position'] = json_decode($data['position'], true);
        if (!empty($data['hj_imgs'])) {
            $imgs = Image::where('id', 'in', $data['hj_imgs'])->select();
            $data['hj_imgs_url'] = [];
            foreach ($imgs as $k => $v) {
                $data['hj_imgs_url'][$k] = $v['url'];
                $data['hj_imgs_list'][$k] = $v;
            }
        }
        if (!empty($data['other_imgs'])) {
            $imgst = Image::where('id', 'in', $data['other_imgs'])->select();
            $data['other_imgs_url'] = [];
            foreach ($imgst as $k => $v) {
                $data['other_imgs_url'][$k] = $v['url'];
                $data['other_imgs_list'][$k] = $v;
            }
        }
        return $data;
    }

    /**
     * 商家的所有顾客
     * @param $shop_id
     * @return mixed
     */
    public function shop_user_all($shop_id)
    {
        $res = ShopOrder::where('shop_id', $shop_id)->where('state', '>',0)
            ->group('user_id')
            ->field('user_id')
            ->select();
       $label = ShopLabel::where('shop_id', $shop_id)->field('id,label_name,user_ids')->select();
        foreach ($label as $key => $value) {
            $user_ids = explode(',', $value['user_ids']);
            foreach ($res as $k => $v) {
                if (in_array($v['user_id'], $user_ids)) {
                    $res[$k]['label_name'] = $value['label_name'];
                }
            }
        }
        return $res;
    }

    /**
     * 查看店铺用户详情
     * @param $id
     * @return mixed
     */
    public function user_info($id,$shop_id)
    {
        $res = Order::with('user_info')->where('shop_id', $shop_id)
            ->where('user_id', $id)
            ->where('state', 1)
            ->field('count(order_id) as order_num,sum(order_money) as total_money,user_id')
            ->find();
        $label = ShopLabel::where('shop_id', $shop_id)->field('id,label_name,user_ids')->select();
        foreach ($label as $key => $value) {
            $user_ids = explode(',', $value['user_ids']);
            if (in_array($res['user_id'], $user_ids)) {
                $res['label_name'] = $value['label_name'];
            }
        }
       /*
        $coupon = UserCoupon::where('shop_id', $shop_id)->where('user_id', $id)->where('status', 0)->count();
        $res['coupon_num'] = $coupon;
       */
        $res['coupon_num'] = 0;
        return $res;
    }

    //获取店铺与当前的距离
    public function store_distance($shop_ids, $lng, $lat)
    {
        $uid=TokenService::getCurrentUid();
        $cache=Cache::get('store_distance_uid_'.$uid);
        if($cache){
           return $cache;
        }
        $data = [];
        $res = self::where(['shop_id'=>$shop_ids])->field('shop_id,lng,lat')->select();
        foreach ($res as $k => $v) {
            if($v['lng']&&$v['lat']){
                $data[$k]['shop_id'] = $v['shop_id'];
                $data[$k]['distance'] = (new BaseCommon)->getDistance($v['lng'], $v['lat'], $lng, $lat);
            }else{
                $data[$k]['shop_id'] = $v['shop_id'];
                $data[$k]['distance'] = [];
            }
        }
        Cache::set('store_distance_uid_'.$uid,$data,300);//5分钟
        return $data;
    }

    //关联img
    public function imgs()
    {
        return $this->belongsTo('app\model\Image', 'img_id', 'id');
    }

    //关联地区
    public function region()
    {
        return $this->belongsTo('Region', 'region_id', 'id')
            ->field('id,shortname,name');
    }


    //关联card
    public function card()
    {
        return $this->belongsTo('ShopCard', 'shop_id','shop_id');
    }
}