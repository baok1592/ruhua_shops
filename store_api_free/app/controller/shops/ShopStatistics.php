<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/10 0010
 * Time: 11:22
 */

namespace app\controller\shops;


use app\model\Goods;
use bases\BaseController;
use app\model\Shop as ShopModel;
use app\model\User as UserModel;

class ShopStatistics extends BaseController
{
    /**店铺排名
     * @param int $type 0总订单数 1 总成交数
     */
    public function shop_sort($type=0)
    {
        $res=ShopModel::Statistics($type);
        return app('json')->go($res);
    }

    /**用户排名
     * @param int $type总订单数1总消费金额排名
     * @return mixed
     */
    public function user_sort($type=0)
    {
        $res=UserModel::Statistics($type);
        return app('json')->go($res);
    }

    /**
     * 商品销售量排名
     */
    public function goods_sort()
    {
        $res=Goods::Statistics();
        return app('json')->go($res);
    }
}