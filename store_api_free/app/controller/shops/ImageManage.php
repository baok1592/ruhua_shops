<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/10 0010
 * Time: 16:37
 */

namespace app\controller\shops;


use app\services\TokenService;
use bases\BaseController;
use app\model\Image;

class ImageManage extends BaseController
{
    public function get_all_img()
    {
        $sid=TokenService::getShopId();
        $res=Image::where(['is_visible'=>1,'sid'=>$sid])->order('id','desc')->select();
        return app('json')->success($res);
    }

   /* public function add()
    {  $data = CommonServices::uploadImg('other', 'idurl', 1, $cid);
        return app('json')->success($data);

    }*/

}