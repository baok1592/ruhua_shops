<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/27 0027
 * Time: 13:41
 */

namespace app\controller\cms;


use app\services\TokenService;
use bases\BaseController;
use app\services\AdminService;
use app\model\Order as  OrderModel;
use app\model\Goods as GoodsModel;
use app\model\ShopRecord as ShopRecordModel;

class ShopUser extends BaseController
{
    public function Login()
    {
        $post=input('post.');
        $rule = [
            'username' => 'require',
            'psw' => 'require',
        ];
        $this->validate($post, $rule);
        return Adminservice::getInstance()->shopLogin($post['username'],$post['psw']);
    }

    public function get_home()
    {
        $sid=TokenService::getShopId();
        $data=array();
        $data['all_order']=OrderModel::where('shop_id',$sid)->count();
        $data['back']=OrderModel::where(['shop_id'=>$sid,'state'=>1])->count();
        $data['goods_sum']=GoodsModel::where('shop_id',$sid)->sum('stock');
        $data['tx']=ShopRecordModel::where('shop_id',$sid)->value('price');
        if($data['tx']==null){
            $data['tx']=0;
        }
        $today = strtotime(date("Y-m-d"),time());
        $data['sid']=$sid;
        $data['today_count']=OrderModel::where(['shop_id'=>$sid,'payment_state'=>1])
            ->whereTime('update_time','>=',$today)->sum('order_money');
        $month=strtotime(date("Y-m-1"),time());
        $data['month_count']=OrderModel::where(['shop_id'=>$sid,'payment_state'=>1])
            ->whereTime('update_time','>=',$month)->sum('order_money');

        return app('json')->go($data);
    }


}