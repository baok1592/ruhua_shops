<?php

namespace app\validate\shops;


use bases\BaseValidate;

class ProductValidate extends BaseValidate
{
    protected $rule = [
        'goods_name' => 'require|isNotEmpty',
        'category_id' => 'require|isPositiveInteger',  //分类id
        'description' => 'max:200', //描述
        'sales' => 'max:200',  //销量
        'market_price' => 'require', //市场价格
        'price' => 'require', //单价
        'vip_price' => 'min:0', //vip价格
        'style' => 'max:5',  //商品类型
        'stock' => 'require',  //库存
        'sku' => 'min:0',  //SKU
        'content' => 'min:0',  //商品详情
        'tag_ids' => 'min:0',  //商品分组
        'detail_imgs' => 'min:0',  //商品详情
        'banner_imgs'=> 'min:0',  //商品详情图
        'video_id'=> 'min:0',  //视频id
        'delivery_id'=>'require', //物流快递模板ID

    ];
}
