<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;

use Aliyun\api_demo\SmsDemo;
use app\model\shops\User as UserModel;
use app\model\SysConfig;
use app\services\TokenService;
use bases\BaseController;
use exceptions\ShopsException;
use think\facade\Cache;

class User extends BaseController
{
    public function bind_phone()
    {
        $post=input('post.');
        $this->validate($post,['code'=>'require','mobile'=>'require|length:11']);
        $uid = TokenService::getCurrentUid();
        $res = UserModel::bindMobile($uid,$post['mobile'],$post['code']);
        return app("json")->go($res);
    }

    public function getYzm()
    {
        $yzm_tmp_id=SysConfig::where('key','yzm_tmp_id')->value('value');
        if(!$yzm_tmp_id){
            throw new ShopsException(['msg'=>"未配置短信接口"]);
        }
        $post=input('post.');
        $this->validate($post,['mobile'=>'require|length:11']);
        TokenService::getCurrentUid();
        $code = rand(10000, 999999);
        $time = time() + (60 * 5);
        Cache::set($post['mobile'],$code,$time);
        SmsDemo::sendSms($post['mobile'],$code,$yzm_tmp_id);
        return app("json")->go(true);
    }
}