<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\controller\shops;

use app\services\TokenService;
use bases\BaseController;
use app\model\shops\ShopCategory as ShopCategoryModel;

class ShopCategory extends BaseController
{
    //商家端-添加某商家分组
    public function addShopCategory(){
        $res=(new ShopCategoryModel)->add();
        return app("json")->go($res);
    }

    //商家端-删除某商家分组
    public function deleteShopCategory(int $id){
        $shop_id=TokenService::getCurrentTokenVar('shop_id');
        $res=ShopCategoryModel::where(['id'=>$id,'shop_id'=>$shop_id])->delete();
        return app("json")->go($res);
    }

    //商家端-获取某商家分组
    public function getShopCategory(){
        $shop_id=TokenService::getCurrentTokenVar('shop_id');
        $res=ShopCategoryModel::where('shop_id',$shop_id)->select();
        return app("json")->go($res);
    }
/**************************以下是用户端*************************************/
    //用户端--获取分组
    public function all_group(int $shop_id){
        $res=ShopCategoryModel::where('shop_id',$shop_id)->select();
        return app("json")->go($res);
    }

}