<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;


use Aliyun\api_demo\SmsDemo;
use app\controller\cms\Oss;
use app\model\SysConfig;
use app\validate\shops\ApplyValidate;
use app\model\User;
use app\services\TokenService;
use bases\BaseCommon;
use bases\BaseModel;
use exceptions\ShopsException;
use think\facade\Db;

class ShopApply extends BaseModel
{
    //申请入驻第一步
    public function reg_one()
    {
        $uid = TokenService::getCurrentUid();
        $validate = new ApplyValidate();
        $validate->goCheck();
        $post = $validate->getDataByRule(input('post.'));
        $user = User::find($uid);
        $shop_user = ShopUser::where(['mobile' => $user['mobile']])->find();
        $shop_name = Shop::where(['shop_name' => $post['shop_name']])->find();
        $shop_apply = self::where(['user_mobile' => $user['mobile']])->find();
        if ($shop_user || $shop_apply|| $shop_name) {
            throw new ShopsException(['msg' => '重复申请','code'=>201]);
        }
        Db::startTrans();
        try {
            $data=$this->createApply($user, $post);
            $res=ShopApply::create($data);
            Db::commit();
            return $res?true:false;
        } catch (\Exception $e) {
            Db::rollback();
            throw new ShopsException(['msg' => $e->getMessage()]);
        }
    }

    //组装数据并创建申请数据
    public function createApply($user, $post)
    {
        $data['uid'] = $user['id'];
        $data['user_mobile'] = $user['mobile'];
        $data['user_name'] = $user['nickname'];
        $data['shop_name'] = $post['shop_name'];
        $data['shop_address'] = $post['shop_address']['address'];
        $data['lat'] = $post['shop_address']['latitude'];
        $data['lng'] = $post['shop_address']['longitude'];
        $data['region_id'] = $post['region_id'];
        $data['shop_type_id'] = $post['type_id'];    //新增字段
        $data['vip_type'] = 0;    //新增字段
        $data['license_url'] = $post['imgs'][0];    //新增字段
        $data['user_sfz_url'] = $post['imgs'][1];    //新增字段
        $data['shop_pic_url'] = $post['imgs'][2];    //新增字段
        $data['apply_status'] = 1;
        return $data;
    }

    //审核申请
    public static function approve_Apply($post)
    {

      //  $state=$post['value'];
        $id=$post['id'];
        $chouyong=$post['chouyong'];
        if(isset($post['value']))
            $value=$post['value'];
        else{
            $value=0;
        }
        Db::startTrans();
        try {
            $shopApply = self::find($id);
            if (!$shopApply) {
                throw new ShopsException(['msg' => '修改失败']);
            }
            if($value==0){
                $msg=input('post.message');
                $data['message']=$msg;
            }
            $data['apply_status' ]= $value;
            $shopApply->save($data);

            if ($value == 2) {

                $sid = self::createShop($id,$chouyong);
                self::createShopUser($id, $sid);
                //SmsDemo::sendMsgSms($shopApply['user_mobile']);  //短信通知入驻成功
                $shopApply->delete();
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();// 回滚事务
            throw new ShopsException(['msg'=>$e->getMessage()]);
        }
        return true;
    }

    //写入新增店铺
    public static function createShop($id,$chouyong)
    {



        $shop = self::where(['id' => $id, 'apply_status' => 2])->find();
        $data['shop_phone'] = $shop['user_mobile'];
        $data['shop_name'] = $shop['shop_name'];
        $data['chouyong']=$chouyong;
        $data['shop_address'] = $shop['shop_address'];
        $data['lat'] = $shop['lat'];
        $data['lng'] = $shop['lng'];
        $data['license_url'] = $shop['license_url'];
        $data['user_sfz_url'] = $shop['user_sfz_url'];
        $data['shop_pic_url'] = $shop['shop_pic_url'];
        $data['region_id'] = $shop['region_id'];
        $data['shop_time'] = date(strtotime("+1 year"));
        $res = Shop::create($data);
        return $res->id;
    }

    //写入新增店主
    public static function createShopUser($id, $sid)
    {
        $shop = self::where(['id' => $id, 'apply_status' => 2])->find();
        $data['shop_id'] = $sid;
        $data['user_id'] = $shop['user_id'];
        $data['mobile'] = $shop['user_mobile'];
        $data['password'] = (new BaseCommon)->store_password('123456');
        $data['username'] = $shop['user_name'];
        $data['shop_time'] = date(strtotime("+1 year"));
        $res=ShopUser::create($data);
        return $res;
    }





}