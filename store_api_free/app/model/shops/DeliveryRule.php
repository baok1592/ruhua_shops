<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\model\shops;



use bases\BaseModel;
use exceptions\ShopsException;
use think\facade\Log;

class DeliveryRule extends BaseModel
{

    public static function add($id,$rules)
    {
        if(!isset($rules[0])){
            throw new ShopsException(['msg'=>'运费模板规则错误']);
        }
        $data=$rules[0];
        Log::error($rules);
        $data['delivery_id']=$id;

        $res=self::create($data);
        return $res->id;
    }

    public static function edit($id,$rules)
    {
        if(!isset($rules[0])){
            throw new ShopsException(['msg'=>'运费模板规则错误']);
        }
        self::where(['delivery_id'=>$id])->delete();
        $data=$rules[0];
        $data['delivery_id']=$id;
        $res=self::create($data);
        return $res->id;
    }

}