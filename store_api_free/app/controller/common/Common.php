<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 15:31
 */

namespace app\controller\common;


use app\model\SysConfig as SysConfigModel;
use app\services\CommonServices;
use bases\BaseController;

class Common extends BaseController
{

    //app版本必须更新
    public function up_version()
    {
        $appid = $_GET["appid"];
        $version = $_GET["version"]; //客户端版本号
        $rsp = array("status" => 0); //默认返回值，不需要升级
        if (isset($appid) && isset($version)) {
            if ($appid === "__UNI__434B608") { //校验appid
                if (!in_array($version, ["1.1.0", "1.1.1", "1.0.7"])) { //这里是示例代码，真实业务上，最新版本号及relase notes可以存储在数据库或文件中
                    $rsp["status"] = 1;
                    $rsp["note"] = "新增部分功能，请升级;"; //release notes
                    $rsp["url"] = "https://test.phps.shop/aa.apk"; //应用升级包下载地址
                }
            }
        }
        return json_encode($rsp);
    }

    //app版本手动更新
    public function up_sd_version()
    {
        $appid = $_GET["appid"];
        $version = $_GET["version"]; //客户端版本号
        $rsp = array("status" => 0); //默认返回值，不需要升级
        if (isset($appid) && isset($version)) {
            if ($appid === "__UNI__434B608") { //校验appid
                if ($version !== "1.0.1") { //这里是示例代码，真实业务上，最新版本号及relase notes可以存储在数据库或文件中
                    $rsp["status"] = 1;
                    $rsp["note"] = "新增部分功能，请升级;"; //release notes
                    $rsp["url"] = "https://test.phps.shop/aa.apk"; //应用升级包下载地址
                }
            }
        }
        return json_encode($rsp);
    }


    /**
     * 返回二维码
     * @return string
     */
    public function gitCodeImg()
    {
        $post = input('post.');
        $this->validate($post, ['path' => 'require']);
        return CommonServices::getCodeImg($post);
    }



    /**
     * 获取文件
     * @param $type
     * @return array|false|int
     */
    public function getFile($type)
    {
        $file = [];
        if ($type == 1) {
            $file = file_get_contents("./files/fw.txt", "r");
        }
        if ($type == 2) {
            $file = file_get_contents("./files/ys.txt", "r");
        }
        if ($type == 3) {
            $file = file_get_contents("./files/sq.txt", "r");
        }
        return $file;
    }

    /**
     * 前端获取部分配置信息
     */
    public function getSysConfig()
    {
        // 购物车16、客服18、分销25、会员36、登录方式37、发票38
        $res = SysConfigModel::where(['id' => [16, 18, 25, 36, 37,38]])->field('key,desc,value')->select();
        return app('json')->success($res);
    }


}