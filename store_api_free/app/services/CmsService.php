<?php

namespace app\services;

use app\model\Coupon as CouponModel;
use app\model\Article as ArticleModel;
use app\model\BannerItem as BannerItemModel;
use app\model\Category as CategoryModel;
use app\model\Order as OrderModel;
use app\model\OrderGoods;
use app\model\OrderLog;
use app\model\User;
use interfaces\RoleInterface;

class CmsService implements RoleInterface
{
    private $param;

    public function set_param($param){
        $this->param=$param;
    }
    //文章列表
    public function get_article_list()
    {
        // TODO: Implement get_article_list() method.
        $data = ArticleModel::select();
        return $data;
    }

    //广告列表
    public function get_banner_list()
    {
        $data = BannerItemModel::with(['imgs', 'banner'])->select();
        return $data;
    }

    //分类列表
    public function get_category_list()
    {
        $data=CategoryModel::with('imgs')->order('sort asc')->select();
        return $data;
    }

    //优惠券列表
    public function get_coupon_list()
    {
        $coupon = CouponModel::select();
        return $coupon;
    }

    //订单列表
    public function get_order_list()
    {
        $post=input('post.');
        $where=[];
        if(isset($post['num']) && !empty($post['num'])) {
            $where[] = ['order_num', 'like', '%' . trim($post['num']) . '%'];
        }
        if(isset($post['user_name']) && !empty($post['user_name'])) {
            $name=base64_encode(trim($post['user_name']));
            $uid=User::where('nickname','like', $name)->value('id');
            $where[] = ['user_id','=',$uid];
    }
        if(isset($post['pro_name']) && !empty($post['pro_name'])) {
            $order_list=OrderGoods::where('goods_name','like', '%' . trim($post['pro_name']) . '%')->column('order_id');
            $where[] = ['order_id', 'in', $order_list];
        }
        $data = OrderModel::with(['ordergoods.imgs', 'users' => function ($query) {
            $query->field('id,nickname,headpic');
        }])->where($where)
            ->order('create_time desc')->field('order_id,order_num,user_id,state,payment_state,shipment_state,delete_time,update_time,pay_time,shipping_money,order_money,user_ip,message,create_time', true)
            ->limit(300)->select();
        return $data;
    }

    //订单详情
    public function get_order_detail($id)
    {
        $data['order'] = OrderModel::with(['ordergoods.imgs','imgs','users' => function ($query) {
                $query->field('id,nickname,headpic');
            }])->where(['order_id' => $id])->find();
        $data['log'] = OrderLog::where(['order_id' => $id])->order('create_time desc')->select();
        return $data;
    }

}