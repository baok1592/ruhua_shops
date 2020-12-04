<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 10:06
 */

namespace app\controller\cms;


use app\model\Order as OrderModel;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use bases\BaseController;
use services\QyFactory;
use think\facade\Log;
use think\facade\Request;

class OrderManage extends BaseController
{



    /**
     * CMS获取所有订单简要
     * @return string
     */
    public function getOrderAll()
    {
        $key = $this->request->param('keywords') ?: '';
        $order = (new QyFactory())->instance('CmsService');
        $order->set_param($key);
        $data = $order->get_order_list();
        return app('json')->success($data);
    }

    /**
     * mscs获取订单
     * @return string
     */
    public function mCmsGetOrder()
    {
        $data = OrderModel::with(['ordergoods.imgs', 'users' => function ($query) {
            $query->field('id,nickname,headpic');
        }])->where(['payment_state'=>1,'shipment_state'=>0])->where('state','in',[0,-1])->order('create_time desc')->field('order_id,order_num,user_id,state,payment_state,shipment_state,delete_time,update_time,pay_time,shipping_money,order_money,user_ip,message,create_time', true)
            ->limit(100)->select();
        return app('json')->success($data);
    }

    /**
     * 获取指定订单详细--CMS
     * @param string $id
     * @return mixed
     */
    public function GetOrderOne($id = '')
    {
        (new IDPostiveInt())->goCheck();
        $order = (new QyFactory())->instance('CmsService');
        $data = $order->get_order_detail($id);
        return app('json')->success($data);
    }


    /**
     * 发货--更新订单配送信息
     * @return bool
     */
    public function editCourier()
    {
        $rule = [
            'courier' => 'require',
            'courier_num' => 'require|min:5',
            'order_id' => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::editCourier($param);
    }

    /**
     * 添加订单备注信息
     * @return mixed
     */
    public function editRemark()
    {
        $rule = [
            'remark' => 'require',
            'order_id' => 'require|number',
        ];
        $param = Request::param();
        $this->validate($param, $rule);
        return OrderModel::up_remark_model($param);
    }


    /**
     * 手机端核销订单
     * @param $number
     * @return bool
     */
    public function hexiao($number)
    {
        $uid=TokenService::getCurrentUid();
        return OrderModel::hexiao($number,$uid);
    }

    public function deleteOrder($id)
    {

        $res=OrderModel::shop_delete($id);
        Log::error($res);

        return app('json')->go($res);
    }
}