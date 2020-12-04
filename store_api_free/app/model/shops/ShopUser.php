<?php

/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\model\shops;


use Aliyun\api_demo\SmsDemo;
use app\controller\auth\Token;
use bases\BaseCommon;
use bases\BaseModel;
use exceptions\ShopsException;
use app\model\SysConfig;
use app\controller\cms\Oss;

class ShopUser extends BaseModel
{
    //商家验证码登陆
    public static function yzm_login($post)
    {
        $user = self::where(['mobile' => $post['mobile'], 'code' => $post['code']])->find();
        if (!$user) {
            throw new ShopsException(['msg' => '验证码错误']);
        }

        $is_oss=SysConfig::where('key','upload_oss')->value('value');
        if($is_oss){
            (new Oss())->createObjectDir($user['shop_id']);
        }
        $cache['uid'] = $user['id'];
        $cache['shop_user_id'] = $user['id'];
        $cache['shop_id'] = $user['shop_id'];
        $cache['scope'] = $user['scope'];  // 推荐用枚举
        $token = (new Token())->saveCache($cache);
        $user->save(['code'=>'']);
        return $token;
    }

    public static function check_login($user)
    {
        $cache['uid'] = $user['id'];
        $cache['shop_id'] = $user['shop_id'];
        $cache['scope'] = $user['scope'];  // 推荐用枚举
        $token = (new Token())->saveCache($cache);
        $user->save(['check'=>'']);
        return $token;

    }

    //设置验证码
    public static function set_login_yzm($mobile)
    {
        $user = self::where(['mobile' => $mobile])->find();
        if (!$user) {
            throw new ShopsException(['msg' => '手机号不存在']);
        }
        $code = rand(100000, 999999);
        $user->code = $code;
        $res=$user->save();
        if($res && $code) {
            SmsDemo::sendSms($mobile, $code);
            return true;
        }
        return false;
    }

    //商家密码登陆
    public static function login($post)
    {
        $psw=trim($post['password']);
        $password=(new BaseCommon)->store_password($psw);
        $user = self::where(['mobile' => $post['mobile'], 'password' => $password])->find();
        if (!$user) {
            throw new ShopsException(['msg' => '用户或密码错误']);
        }
        $is_oss=SysConfig::where('key','upload_oss')->value('value');
        if($is_oss){
            (new Oss())->createObjectDir($user['shop_id']);
        }
        $cache['uid'] = $user['id'];
        $cache['shop_user_id'] = $user['id'];
        $cache['shop_id'] = $user['shop_id'];
        $cache['scope'] = $user['scope'];  // 推荐用枚举
        $token = (new Token())->saveCache($cache);
        $user->save(['login_time'=>time()]);
        return $token;
    }
    //修改密码
    public static function edit_psw($post)
    {
        $psw=trim($post['password']);
        $new_psw=trim($post['new_psw']);
        $password_code=config('setshops.shops_psw_code');
        $password=md5(md5($psw) . md5($password_code));
        $user = self::where(['mobile' => $post['mobile'], 'password' => $password])->find();
        if (!$user) {
            throw new ShopsException(['msg' => '用户或密码错误']);
        }
        $new_psw=md5(md5($new_psw) . md5($password_code));
        $res=$user->save(['password'=>$new_psw]);
        return $res?1:0;
    }

    //关联商家名称
    public function shopName()
    {
        return $this->belongsTo('Shop', 'shop_id', 'shop_id')->field('shop_id,shop_name');
    }




}