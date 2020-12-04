<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/24 0024
 * Time: 16:55
 */

namespace app\model;


use bases\BaseModel;

class Shop extends BaseModel
{
    /**
     * @param $type 0总订单数 1 总成交数 2店铺总收益 3店铺商品总数 4好评数排名
     */
    public static function Statistics($type)
    {
        $shop=self::with(['ordercount','finish'])->select();
        foreach ($shop as $k=>$v){
            $count=count($v['ordercount']);
            $finish=count($v['finish']);
            unset($v['ordercount']);
            unset($v['finish']);
            $v['ordercount']=$count;
            $v['finish']=$finish;
            $shop[$k]=$v;
        }

        switch ($type){
            case 0:
                $tp="ordercount";
                break;
            default:
                $tp="finish";
                break;
        }
        return self::order_sort($shop,$tp);
    }
    /**
     * 总订单数排名
     */
    private static function order_sort($shop,$type)
    {
        for ($i=0;$i<count($shop);$i++)
            for($j=0;$j<$i;$j++){
                if($shop[$i][$type]>$shop[$j][$type]){
                    $v=$shop[$i];
                    unset($shop[$i]);
                    $shop[$i]=$shop[$j];
                    unset($shop[$j]);
                    $shop[$j]=$v;
                    unset($v);
                }
            }
        return $shop;
    }

    public function ordercount()
    {
        return $this->hasMany('app\model\Order','shop_id','shop_id');
    }

    public function finish()
    {
        return $this->hasMany('app\model\Order','shop_id','shop_id')
            ->where('state',1);
    }
}