<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;


use bases\BaseModel;
use exceptions\BaseException;
use think\facade\Db;
use think\facade\Log;
use exceptions\ShopsException;

class Delivery extends BaseModel
{



    /**
     * 运费模板计算快递费
     * @param $region_id
     * @param $data
     * @return int
     * @throws BaseException
     */
    public static function computeShipping($region_id, $data)
    {
        $arr = [];
        $shipping = 0;
        foreach ($data as $k => $v) {
            $goods = ShopPro::with('delivery')->where('goods_id', $v['goods_id'])
                ->field('goods_id,delivery_id')->find();

            if(!$goods){
                throw new ShopsException(['msg'=>'商品不存在']);
            }
        //    if ($goods['delivery']['tag']=='同城') {
            if ($goods['delivery']['tag']!=null) {
                foreach ($goods['delivery']['rule'] as $key => $value) {
                    //if (in_array($region_id, $value['region'])) {
                    if (!$arr) {
                        $value['num'] = $v['num'];
                        array_push($arr, $value);
                    } else {
                        $state = 0;
                        foreach ($arr as $key1 => $item) {
                            if ($value['rule_id'] == $item['rule_id']) {
                                $arr[$key1]['num'] += $v['num'];
                                $state = 1;
                            }
                            if ((count($arr) - 1) == $key1) {
                                if ($state == 0) {
                                    $value['num'] = $v['num'];
                                    array_push($arr, $value);
                                }
                            }
                            unset($arr[$key1]['region']);
                        }
                    }
                    //}
                }
            }
        }
        foreach ($arr as $k => $v) {

            if ($v['additional'] && $v['num'] > $v['first']) {
                $money = $v['first_fee'] + ceil(($v['num'] - $v['first']) / $v['additional']) * $v['additional_fee'];
            } else {
                $money = $v['first_fee'];
            }
            $shipping += $money;
        }

        return $shipping;
    }


    public static function add($data)
    {
        $res=self::create($data);
        return $res->id;
    }

    public static function edit($id,$shop_id,$data)
    {
        $res=self::where(['id'=>$id,'shop_id'=>$shop_id])->save($data);
        return $res;
    }

    public static function del($id,$shop_id)
    {
        Db::startTrans();
        try{
            $res=self::where(['id'=>$id,'shop_id'=>$shop_id])->delete();
            if(!$res) {
                throw new ShopsException(['msg' =>'操作失败']);
            }
            DeliveryRule::where(['delivery_id' => $id])->delete();
            Db::commit();
            return true;
        }catch (Exception $e){
            Db::rollback();// 回滚事务
            throw new ShopsException(['msg'=>$e->getMessage()]);
        }
    }

    //关联rules
    public function Rules()
    {
        return $this->hasMany('DeliveryRule', 'delivery_id','id');
    }


}