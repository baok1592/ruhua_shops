<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/17 0017
 * Time: 13:39
 */

namespace app\controller\shops;

use app\model\UserAddress;
use app\services\TokenService;
use app\validate\shops\DeliveryValidate;
use bases\BaseController;
use app\model\shops\Delivery as DeliveryModel;
use app\model\shops\DeliveryRule as DeliveryRuleModel;
use exceptions\ShopsException;
use think\Exception;
use think\facade\Db;

class Delivery extends BaseController
{

    public function get_list()
    {
        $res=DeliveryModel::select();
        return app('json')->go($res);
    }

    public function get_one(int $id)
    {
        $res=DeliveryModel::with('Rules')->find($id);
        return app('json')->go($res);
    }

    public function add()
    {

        $shop_id=TokenService::getShopId();

        $validate = new DeliveryValidate();
        $validate->goCheck();

        $post = $validate->getDataByRule(input('post.'));
        $rules=$post['rules'];
        unset($post['rules']);
        $post['shop_id']=$shop_id;

        Db::startTrans();
        try{

            $id=DeliveryModel::add($post);

            DeliveryRuleModel::add($id,$rules);

            Db::commit();
        }catch (Exception $e){
            Db::rollback();// 回滚事务
            throw new ShopsException(['msg'=>$e->getMessage()]);
        }
        return app('json')->go(true);
    }

    public function del($id)
    {
        $shop_id=TokenService::getCurrentTokenVar('shop_id');
        $res=DeliveryModel::del($id,$shop_id);
        return app("json")->go($res);
    }

    public function edit()
    {
        $shop_id=TokenService::getShopId();
        $validate = new DeliveryValidate();
        $validate->goCheck();
        $oid=input('post.id');
        $post = $validate->getDataByRule(input('post.'));
        $rules=$post['rules'];
        unset($post['rules']);
        $post['shop_id']=$shop_id;
        Db::startTrans();
        try{
            DeliveryModel::edit($oid,$shop_id,$post);
            DeliveryRuleModel::edit($oid,$rules);
            Db::commit();
        }catch (Exception $e){
            Db::rollback();// 回滚事务
            throw new ShopsException(['msg'=>$e->getMessage()]);
        }
        return app('json')->go(true);
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