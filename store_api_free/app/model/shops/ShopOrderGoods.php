<?php

namespace app\model\shops;


use bases\BaseModel;
use think\model\concern\SoftDelete;

class ShopOrderGoods extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time'];
    protected $name="OrderGoods";

    //关联图片
    public function GoodsImgs()
    {
        return $this->hasOne('GoodsImgs', 'goods_id','goods_id')
            ->field('goods_id,thumb_img');
    }

}