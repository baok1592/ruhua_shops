<?php

/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\model\shops;


use app\services\TokenService;
use bases\BaseModel;
use exceptions\ShopsException;
use app\model\shops\ShopCategory as ShopCategoryModel;

class ShopCategory extends BaseModel
{
    //添加分类
    public function add(){
        $shop=TokenService::getCurrentTokenVar('shop_id');
        if(!input('?post.name')){
            throw new ShopsException(['msg'=>'name必填']);
        }
        $name=input('post.name');
        $number=ShopCategoryModel::where('shop_id',$shop)->count();
        if($number>=10){
            throw new ShopsException(['msg'=>'已达上限']);
        }
        $res=ShopCategoryModel::create(['shop_id'=>$shop,'name'=>$name]);
        if(!$res){
            throw new ShopsException(['msg'=>'创建失败']);
        }
        return $res->id*1;
    }

}