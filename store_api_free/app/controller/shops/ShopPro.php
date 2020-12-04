<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;

use app\model\Category as CategoryModel;
use app\services\SearchService;
use app\services\TokenService;
use bases\BaseController;
use app\model\shops\ShopPro as ShopProModel;

use app\model\Image;

class ShopPro extends BaseController
{
    public function UserGetProduct(int $id)
    {
        $res=ShopProModel::UserGetProduct($id);
        return app("json")->go($res);
    }




    public function hot_pro($lng="",$lat="")
    {
        $res = ShopProModel::getRecentAll("hot",$lng,$lat);
        return app("json")->go($res);
    }

    public function new_pro()
    {
        $res = ShopProModel::getRecentAll("new","","");
        return app("json")->go($res);
    }

    //列表页所有商品
    public function list_pro()
    {
        $res = ShopProModel::getRecentAll("","","");
        return app("json")->go($res);
    }

    //通过ID获店铺所有商品
    public function store_all_pro(int $shop_id)
    {
        $res=ShopProModel::with(['GoodsImgs'=>function($query){
            $query->field('goods_id,thumb_img');
        }])->where('shop_id',$shop_id)->order('create_time desc')->select()->toArray();
        return app("json")->go($res);
    }

    //通过ID获店下架上架商品
    public function store_all_pro1(int $shop_id,int $type)
    {
        $res=ShopProModel::with(['GoodsImgs'=>function($query){
            $query->field('goods_id,thumb_img');
        }])->where(['shop_id'=>$shop_id,'state'=>$type])->order('create_time desc')->select()->toArray();
        return app("json")->go($res);
    }

    /**
     * 获取某分类下所有商品
     * @param string $id
     * @return \think\response\Json
     */
    public function getCatePros(int $id)
    {
        $ids=CategoryModel::where('pid',$id)->column('category_id');
        if($ids){
            array_push($ids,$id);
        }else{
            $ids=$id;
        }
        $data = ShopProModel::with(['GoodsImgs'=>function($query){
            $query->field('goods_id,thumb_img');
        }])->where(['state'=>1,'category_id'=>$ids])->select();
        return app('json')->success($data);
    }




    /**
     * 搜索商品
     * @param $name
     * @return mixed
     */
    public function searchGoods($name)
    {
        $name=trim($name);
        $data=self::getProductByName($name);
        if(strlen($name)<6){
            SearchService::setSearchCache($name);
        }
        return app('json')->go($data);
    }


    public static function getProductByName($name)
    {
        $data = ShopProModel::with(['GoodsImgs'=>function($query){
            $query->field('goods_id,thumb_img');
        }])->where('state', 1)->where('goods_name', 'like', '%' . $name . '%')
            ->order('sales desc')->select();
        return $data;
    }

    //通过shopid和tag_id获店铺同标签商品
    public function tag_pros(int $shop_id,int $tag_id)
    {
        $res=ShopProModel::with('GoodsImgs')->where(['shop_id'=>$shop_id,'tag_ids'=>$tag_id])->order('sort desc')->select()->toArray();
        return app("json")->go($res);
    }

    //我店铺的所有商品
    public function my_shop_pro()
    {
        $shop_id=TokenService::getCurrentTokenVar("shop_id");
        return $this->store_all_pro($shop_id);
    }
    //我的分类商品
    public function getshoptype($type)
    {
        $shop_id=TokenService::getCurrentTokenVar("shop_id");
        return $this->store_all_pro1($shop_id,$type);


    }

    //库存预警
    public function shopStock()
    {
        $sid = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopProModel::where('shop_id', $sid)
            ->field('')
            ->where('stock','<','10')->order('goods_id desc')->select();
        return app("json")->go($res);
    }



    /**
     *  cms获取所有上架商品，包含分页
     * @param string $key
     * @return \think\response\Json
     */
    public function cms_pro_list($key = '')
    {
        $data=ShopProModel::getProductByPage($key);
        return app('json')->go($data);
    }
}