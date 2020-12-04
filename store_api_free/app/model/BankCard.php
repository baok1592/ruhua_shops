<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/24 0024
 * Time: 10:12
 */

namespace app\model;


use app\services\TokenService;
use bases\BaseModel;
use app\model\User as UserModel;
use exceptions\OrderException;
use app\model\shops\ShopUser as ShopUserModel;
use exceptions\TokenException;
use app\model\Shop as ShopModel;

class BankCard extends BaseModel
{
    public static function is_auth($type,$uid)
    {
        if($type==0)
        {
            $user=UserModel::where('id',$uid)->find();
            if(!$user){
                throw new OrderException(['msg'=>'非法操作']);
            }else{
                return app('json')->go($user);
            }
        }
        elseif($type==1){
            $user=ShopModel::where('shop_id',$uid)->find();
            if(!$user){
                throw new OrderException(['msg'=>'非法操作']);

            }else{
                return app('json')->go($user);
            }
        }
        else{
            throw new OrderException(['msg'=>'用户类型不对']);
        }
        return true;
    }

    public static function isLogin($type)
    {
        if ($type==0){
            $uid=TokenService::getCurrentUid();
        }else{
            $shop_id= TokenService::getCurrentTokenVar('shop_id');
        }
    }

}