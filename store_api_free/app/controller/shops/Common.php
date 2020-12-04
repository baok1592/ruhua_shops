<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;


use app\model\SysConfig as SysConfigModel;
use app\model\shops\Shop as ShopModel;
use app\services\shops\CommonService;
use app\services\TokenService;
use bases\BaseController;

class Common extends BaseController
{
    //前端获取站点配置
    public function shops_cfg()
    {
        $res = SysConfigModel::where(['key' => ['is_citys','is_same']])->field('key,desc,value')->select();
        return app('json')->success($res);
    }

    public function shopsCms_checkLog()
    {
        $sid=TokenService::getCurrentTokenVar('shop_id');
        $res=ShopModel::where(['shop_state'=>1,'shop_id'=>$sid])->find();
        return app('json')->go($res);
    }

    //商家上传商品缩略图，裁剪正方形
    public function upImgSquare()
    {
        $shop_id=TokenService::getShopId();
        $res=CommonService::upImgSquare($shop_id);
        return app('json')->go($res);

    }

    //商家上传商品详情图，不裁剪只压缩
    public function upImgRectangle()
    {
        $shop_id=TokenService::getShopId();
        $res=CommonService::upImgRectangle($shop_id);
        return app('json')->go($res);
    }
}