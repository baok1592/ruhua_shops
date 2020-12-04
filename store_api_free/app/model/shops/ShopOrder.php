<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;


use app\model\Order;
use app\model\OrderLog;
use app\model\Tui;
use app\services\QrcodeServer;
use app\services\TokenService;
use bases\BaseModel;
use exceptions\OrderException;
use think\facade\Db;
use think\model\concern\SoftDelete;

class ShopOrder extends BaseModel
{
    protected $name="Order";
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];

    public function UserOrderOne($id)
    {
        $uid = TokenService::getCurrentUid();
        $auth = User::where('id', $uid)->value('web_auth_id');
        $where['order_id'] = $id;
        if ($auth == 0) {
            $where['user_id'] = $uid;
        }
        $data = ShopOrder::with(['rate', 'ordergoods.GoodsImgs'])->where($where)->find(); //常规查询自动不包含软删除的数据
        if (!$data) {
            return app('json')->fail('获取订单数据失败');
        }
        $tui_data = Tui::where('order_id', $id)->select();
//        $data['rate'] = RateModel::where('order_id', $id)->find();
        if ($data['yz_code']) {
            $data['qrcode'] = (new QrcodeServer())->get_qrcode($data['yz_code']);
        }
        if ($tui_data) {
            $data['tui'] = $tui_data;
        }
        return $data;
    }
    //发货
    public static function editCourier($param)
    {
        $pay = self::where('order_id', $param['order_id'])->value('payment_state');
        if ($pay != 1) {
            throw new OrderException(['msg' => '未支付的订单无法发货']);
        }
        Db::startTrans();
        try {
            $courier['courier'] = $param['courier'];
            $courier['courier_num'] = $param['courier_num'];
            $courier['shipment_state'] = 1;
            self::where('order_id', $param['order_id'])->Update($courier);
            $save['order_id'] = $param['order_id'];
            $save['type_name'] = '录入快递单号';
            $save['content'] = $param['courier'] . '，' . $param['courier_num'];
            OrderLog::create($save);
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new OrderException(['msg' => '快递信息录入失败']);
        }
    }

    //多商户关联会员
    public function userInfo()
    {
        return $this->belongsTo('app\model\User','user_id','id')
            ->field('id,nickname,headpic,mobile');
    }

    //关联模型
    public function rate()
    {
        return $this->hasMany('app\model\Rate', 'order_id', 'order_id');
    }

    //关联订单商品模型
    public function ordergoods()
    {
        return $this->hasMany('ShopOrderGoods', 'order_id', 'order_id')
            ->field('order_id,goods_id,goods_name,sku_name,num,price');
    }

    //关联店铺
    public function shops()
    {
        return $this->belongsTo('Shop', 'shop_id', 'shop_id')
            ->field('shop_id,shop_name,region_id,xinxin,shop_state');
    }

    public function users()
    {
        return $this->belongsTo('app\model\User','user_id','id');
    }
}