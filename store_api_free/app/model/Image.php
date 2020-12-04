<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 14:28
 */

namespace app\model;


use app\services\TokenService;
use bases\BaseModel;
use exceptions\BaseException;
use think\facade\Db;
use think\facade\Log;

class Image extends BaseModel
{
    public static function shop_del_imgs($ids)
    {
        $sid=TokenService::getShopId();


        Db::startTrans();
        try {
            for($i=0;$i<count($ids);$i++){

                $img=self::where('id',$ids[$i])->find();
                if (!$img){
                    throw new BaseException(['msg'=>'图片不存在，操作失败']);

                }
                $res=self::where(['sid'=>$sid,'id'=>$ids[$i]])->delete();

                if($res){
                    $path=ROOT.$img['url'];

                    if(file_exists($path))
                        unlink($path);//删除文件
                }

            }

            Db::commit();
            return ['status'=>'删除完成','data'=>true];
        } catch (\Exception $e) {
            Db::rollback();
            return false;
        }
    }
}