<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/24 0024
 * Time: 14:00
 */

namespace app\model;


use bases\BaseModel;

class ShopCash  extends BaseModel
{
    public function getshop()
    {
        return $this->belongsTo('Shop','sid','shop_id');
    }

}