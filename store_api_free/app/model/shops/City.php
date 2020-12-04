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
use think\Exception;
use think\facade\Cache;
use think\facade\Request;
use think\model\concern\SoftDelete;

class City extends BaseModel
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $hidden = ['delete_time','update_time'];

    public function Admin()
    {
        return $this->belongsTo('Admins','agent_id','id')->field('id,nickname');
    }

    //通过token修改region
    public static function upCity($value)
    {
        $token = Request::header('token');
        $vars = Cache::get($token);
        if (!$vars) {
            throw new ShopsException([
                'msg' => 'token错误'
            ]);
        }
        $data=Cache::pull($token);
        $data = json_decode($data,true);
        $data['city_id'] = $value*1;
        $cache=json_encode($data);
        Cache::set($token, $cache); //无限时间
        try{
            $rid = TokenService::getCurrentTokenVar('city_id');
        }catch(Exception $e){
            $rid=2;
        }
        $res = self::field('id,region_name')->find($rid);
        if (!$res) {
            $res = self::field('id,region_name')->find(1); //不存在则返回默认城市
        }
        return $res;
    }

}