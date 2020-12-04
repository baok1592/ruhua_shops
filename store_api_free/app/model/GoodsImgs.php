<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/28 0028
 * Time: 9:59
 */

namespace app\model;


use bases\BaseModel;

class GoodsImgs extends BaseModel
{
    public function getBannerImgsAttr($v)
    {
        return explode(",",$v);
    }

    public function getDetailImgsAttr($v)
    {
        if (!empty($v)) {
            return explode(",", $v);
        } else {
            return "";
        }
    }

}