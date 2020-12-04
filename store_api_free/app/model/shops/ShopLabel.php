<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;

use app\services\TokenService;
use exceptions\ShopsException;
use think\facade\Validate;
use bases\BaseModel;

class ShopLabel extends BaseModel
{
    //商家查看标签及人数
    public function all()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopLabel::where('shop_id', $shop_id)->select();
        foreach ($res as $k => $v) {
            if ($v['user_ids']) {
                $user_ids = explode(',', $v['user_ids']);
                $res[$k]['user_num'] = count($user_ids);
            } else {
                $res[$k]['user_num'] = 0;
            }
        }
        return $res;
    }

    //商家添加标签规则
    public function add()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $validate = Validate::rule([
            'label_name' => 'require',
            'order_num' => 'require|number',
            'money_num' => 'require|number',
        ]);
        $post = input('post.');
        if (!$validate->check($post)) {
            throw new ShopsException(['msg' => $validate->getError()]);
        }
        $label = self::where('shop_id', $shop_id)->count();
        if ($label >= 5) {
            throw new ShopsException(['msg' => '已达上限']);
        }
        $data['shop_id'] = $shop_id;
        $data['label_name'] = $post['label_name'];
        $data['order_num'] = $post['order_num'];
        $data['money_num'] = $post['money_num'];

        $res = self::create($data);
        return $res;
    }

    //商家修改标签
    public function edit()
    {
        $post = input('post.');
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $label = ShopLabel::where(['id'=>$post['id'],'shop_id'=>$shop_id])->find();
        if(!$label){
            throw new ShopsException(['msg'=>'标签不存在']);
        }
        $res = $label->save($post);
        return $res;
    }


    //商家查看自己的客户
    public function selectShopUser()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = OrderModel::with('user_info')->where('shop_id', $shop_id)
            ->where('order_status', 5)
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
        return $res;
    }

    //商家查看客户详情
    public function getUserInfo($id)
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');

        $res = OrderModel::with('user_info')->where('shop_id', $shop_id)
            ->where('user_id', $id)
            ->where('order_status', 5)
            ->field('count(order_id) as order_num,sum(order_money) as total_money,user_id')
            ->find();
        $label = ShopLabelModel::where('shop_id', $shop_id)->field('id,label_name,user_ids')->select();
        foreach ($label as $key => $value) {
            $user_ids = explode(',', $value['user_ids']);
            if (in_array($res['user_id'], $user_ids)) {
                $res['label_name'] = $value['label_name'];
            }
        }
        $coupon = UserCoupon::where('shop_id', $shop_id)->where('user_id', $id)->where('status', 0)->count();
        $res['coupon_num'] = $coupon;
        return $res;
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