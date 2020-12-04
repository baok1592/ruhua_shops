<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/17 0017
 * Time: 13:39
 */

namespace app\controller\cms;


use app\model\Region;
use app\model\UserAddress;
use app\services\TokenService;
use bases\BaseController;
use app\model\Delivery as DeliveryModel;

class Delivery extends BaseController
{

    public function getRegion(){
        $data=Region::getRegion(2);
       return  json($data);
    }

    //计算运费
    public function getShipmentPrice(){
        $post= input('post.');
        $uid=TokenService::getCurrentUid();
        $user_data = UserAddress::where('user_id', $uid)->where('is_default', 1)->find();
        $price= DeliveryModel::computeShipping($user_data['region_id'],$post);  //某城市运费
        return app('json')->success($price);
    }
}