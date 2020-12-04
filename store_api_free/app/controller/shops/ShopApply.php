<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\controller\shops;


use app\model\shops\ShopApply as ShopApplyModel;
use app\services\TokenService;
use bases\BaseController;
use think\Db;


class ShopApply extends BaseController
{

    //上传/修改申请
    public function applyShop()
    {
        $uid = TokenService::getCurrentUid();
        $validate = new ApplyValidate();
        $validate->goCheck();
        $post = $validate->getDataByRule(input('post.'));
        $user = UserModel::where('id', $uid)->find();
        $shop_user = ShopUserModel::where(['mobile' => $user['mobile']])->find();
      	$shop_name = ShopModel::where(['shop_name' => $post['shop_name']])->find();
        $shop_apply = ShopApplyModel::where(['user_mobile' => $user['mobile']])->find();
        if ($shop_user || $shop_apply|| $shop_name) {
            throw new BaseException(['msg' => '重复申请','code'=>201]);
        }
        $vip_type=$user->vip_type;
        if($vip_type<2 || $user->vip_status!=1){
            throw new BaseException(['msg' => '非商户']);
        }
        $type = VipTypeModel::where('id', $vip_type)->find();
        if (!$type) {
            throw new BaseException(['msg' => '类型错误']);
        }
        Db::startTrans();
        try {
            $shop_id=ShopApplyModel::createApply($user, $post,$vip_type);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new BaseException(['msg' => $e]);
        }

        return $shop_id;
    }
  
   //修改申请
    public function editApplyShop()
    {
        (new IDPostiveInt)->goCheck();
        $uid=TokenService::getCurrentUid();
        $post=input('post.');
        $shop=ShopApplyModel::updateApply($uid, $post);
        return $shop;
    }
  
    //商家我的申请
    public function getApply(){
        $uid = TokenService::getCurrentUid();
        $user = UserModel::where('id', $uid)->find();
      	$res=ShopApplyModel::where('user_mobile',$user['mobile'])->find(); 
        return json($res);
    }

    //待审批的商家申请
    public function get_list()
    {
        $res=ShopApplyModel::where('apply_status', 1)->order('id','desc')->select()->toArray();
        return app("json")->go($res);
    }

    //审核申请
    public function examineApply()
    {
        $post=input('post.');
        $res=ShopApplyModel::approve_Apply($post);
        return app("json")->go($res);
    }
}