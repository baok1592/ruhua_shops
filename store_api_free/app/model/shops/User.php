<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;


use bases\BaseModel;
use think\facade\Cache;

class User extends BaseModel
{
    public static function bindMobile($uid, $mobile, $code)
    {
        $user = self::where(['id' => $uid])->find();
        if (!empty($user->mobile)) {
            return '已经绑定过了';
        }
        $cache_code=Cache::get($mobile);
        if ($cache_code != $code) {
            return '验证码错误';
        }
        $res=$user->save(['mobile' => $mobile]);
        return $res;
    }
}