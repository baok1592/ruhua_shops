<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;


use app\services\TokenService;
use bases\BaseController;
use app\model\shops\ShopOrder as ShopOrderModel;
use app\model\shops\ShopLabel as ShopLabelModel;
use app\model\Order as OrderModel;

class ShopMember extends BaseController
{
    //商家查看标签及人数
    public function allLabel()
    {
        $res=(new ShopLabelModel)->all();
        return app('json')->go($res);
    }

    //商家添加标签规则
    public function addLabel()
    {
        $res=(new ShopLabelModel)->add();
        return app('json')->go($res);
    }

    //商家修改标签
    public function editLabel()
    {
        $res=(new ShopLabelModel)->edit();
        return app('json')->go($res);
    }

    //商家删除标签
    public function deleteLabel($id)
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopLabelModel::where('id', $id)->where('shop_id', $shop_id)->delete();
        return app('json')->go($res);
    }


    //商家查看自己的客户
    public function StoreAllUser()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopOrderModel::with('user_info')->where('shop_id', $shop_id)
            ->where('state','>', 0)
            ->group('user_id')
            ->field('user_id')
            ->select();
        $label = ShopLabelModel::where('shop_id', $shop_id)->field('id,label_name,user_ids')->select();
        foreach ($label as $key => $value) {
            $user_ids = explode(',', $value['user_ids']);
            foreach ($res as $k => $v) {
                if (in_array($v['user_id'], $user_ids)) {
                    $res[$k]['label_name'] = $value['label_name'];
                }
            }
        }

        return app('json')->go($res);
    }

    //商家查看客户详情
    public function getUserInfo($id)
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');

        $res = ShopOrderModel::with('user_info')->where('shop_id', $shop_id)
            ->where('user_id', $id)
            ->where('state', '>',0)
            ->field('count(order_id) as order_num,sum(order_money) as total_money,user_id')
            ->find();
        $label = ShopLabelModel::where('shop_id', $shop_id)->field('id,label_name,user_ids')->select();
        foreach ($label as $key => $value) {
            $user_ids = explode(',', $value['user_ids']);
            if (in_array($res['user_id'], $user_ids)) {
                $res['label_name'] = $value['label_name'];
            }
        }
        return app('json')->go($res);
    }

    //自动添加标签
    public static function autoLabel($user_id, $shop_id)
    {
        $max_order = 0;
        $max_money = 0;
        $res = OrderModel::with('user_info')->where('shop_id', $shop_id)
            ->where('user_id', $user_id)
            ->where('order_status', 5)
            ->field('count(order_id) as order_num,sum(order_money) as total_money,user_id')
            ->find();
        $label = ShopLabelModel::where('shop_id', $shop_id)->select();
        $label = $label->toArray();
        if(!$label){
            return 0;
        }
        foreach ($label as $k => $v) {
            if ($v['user_ids']) {
                $user_ids = explode(',', $v['user_ids']);
                if (in_array($res['user_id'], $user_ids)) {
                    $key = array_search($res['user_id'], $user_ids);
                    if ($key !== false) {
                        array_splice($user_ids, $key, 1);
                    }
                }
            } else {
                $user_ids = [];
            }

            if ($res['order_num'] >= $v['order_num'] || $res['total_money'] >= $v['money_num']) {
                if ($res['order_num'] > $max_order || $res['total_money'] > $max_money) {
                    $max_order = $v['order_num'];
                    $max_money = $v['money_num'];
                    $max_id = $v['id'];
                    $max_key = $k;
                }
            }
            $user_ids = implode(',', $user_ids);
            ShopLabelModel::where('id', $v['id'])->update(['user_ids' => $user_ids]);
            if (count($label) - 1 == $k) {
                $user_ids = ShopLabelModel::where('id', $max_id)->value('user_ids');
                if ($user_ids) {
                    $user_ids = explode(',', $user_ids);
                    array_push($user_ids, $res['user_id']);
                } else {
                    $user_ids[] = $res['user_id'];
                }
                $user_ids = implode(',', $user_ids);
                $data = ShopLabelModel::where('id', $max_id)->update(['user_ids' => $user_ids]);
            }
        }
        return $data ? 1 : 0;
    }


}