<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\services\shops;


use exceptions\ShopsException;
use think\Image;
use  app\model\shops\Image as ImageModel;
use app\model\SysConfig as SysConfigModel;
use app\controller\cms\Oss;

class CommonService
{
    public static function upImgSquare($shop_id)
    {
        $file = Image::open(request()->file('img'));
        if (!$file) {
            throw new ShopsException(['msg'=>'请上传文件img']);
        }
        $dir='./uploads/product/s' . $shop_id;
        self::check_dir($dir);
        $name = uniqid() . '.png';
        $path=$dir . '/' . $name;



        //1同比例，3居中裁剪
        $res=$file->thumb(500, 500, 3)->save($path);
        $is_oss=SysConfigModel::where('key','upload_oss')->value('value');
        if($is_oss){
            $oss=new Oss();
            $path=$oss->uploadFile($path);
        }

        return $res?['url'=>$path]:false;
    }

    public static function upImgRectangle($shop_id)
    {
        $file = Image::open(request()->file('img'));
        if (!$file) {
            throw new ShopsException(['msg'=>'请上传文件img']);
        }
        $dir='./uploads/product/s' . $shop_id;
        self::check_dir($dir);
        $name = uniqid() . '.png';
        $path=$dir . '/' . $name;
        $res=$file->thumb(700, 700, 1)->save($path);
        $is_oss=SysConfigModel::where('key','upload_oss')->value('value');
        if($is_oss){
            $oss=new Oss();
            $path=$oss->uploadFile($path);
        }
        return $res?['url'=>$path]:false;
    }

    public static function check_dir($dir)
    {
        if(file_exists($dir) && is_dir($dir)){
           return true;
        }else{
            mkdir ($dir,0777);
        }
    }

}