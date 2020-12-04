<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/13 0013
 * Time: 9:26
 */

namespace bases;


use exceptions\BaseException;
use app\controller\auth\Token;
use think\facade\Request;

class BaseCommon
{
    function curl_get($url, &$httpCode = 0)
    {
        $web_url=Request::rootDomain();
        $token=(new Token())->getCloudToken();
        $header=array('url:'.$web_url,'otoken:'.$token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //不做证书校验,部署在linux环境下请改为true
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $file_contents = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $file_contents;
    }

    function curl_post($url, array $params = array())
    {
        $data_string = json_encode($params);
        $token=(new Token())->getCloudToken();
        $web_url=Request::rootDomain();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'token:'.$token,
                'url:'.$web_url
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }

    //判断系统是否完成安装
    function vae_is_installed()
    {
        static $vaeIsInstalled;
        if (empty($vaeIsInstalled)) {
            $vaeIsInstalled = file_exists(VAE_ROOT . 'data/install.lock');
        }
        return $vaeIsInstalled;
    }


    //生成订单编号
    function makeOrderNum()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J','K');
        $orderSn =
            $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    //生成拼图订单编号
    function makePtOrderNum()
    {
        $orderSn =
            "P". strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    /**
     * 子孙树 用于菜单整理
     * @param $param
     * @param int $pid
     */
    function subTree($param, $pid = 0)
    {
        static $res = [];
        foreach($param as $key=>$vo){

            if( $pid == $vo['pid'] ){
                $res[] = $vo;
                if($vo['is_visible']==1){
                    $param[$key]['is_visible']=true;
                }else{
                    $param[$key]['is_visible']=false;
                }
                $this->subTree($param, $vo['category_id']);
            }
        }
        return $res;
    }

    /**
     * 管理员密码加密方式
     * @param $password  密码
     * @return string
     */
    function password($password)
    {
        $password_code=config('setting.psw_code');
        return md5(md5($password) . md5($password_code));
    }

    /**
     * 商家端密码加密方式
     * @param $password  密码
     * @return string
     */
    function store_password($password)
    {
        $password_code=config('setshops.shops_psw_code');
        return md5(md5($password) . md5($password_code));
    }


    function unlock($txt,$key='str'){
        //快递物流
        $txt = base64_decode(urldecode($txt));
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-=+";
        $ch = $txt[0];
        $nh = strpos($chars,$ch);
        $mdKey = md5($key.$ch);
        $mdKey = substr($mdKey,$nh%8, $nh%8+7);
        $txt = substr($txt,1);
        $tmp = '';
        $i=0;$j=0; $k = 0;
        for ($i=0; $i<strlen($txt); $i++) {
            $k = $k == strlen($mdKey) ? 0 : $k;
            $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);
            while ($j<0) $j+=64;
            $tmp .= $chars[$j];
        }
        return trim(base64_decode($tmp),$key);
    }

    function getDistance($lng1, $lat1, $lng2, $lat2) {
        // 将角度转为狐度
        $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
        $radLat2 = deg2rad($lat2);
        $radLng1 = deg2rad($lng1);
        $radLng2 = deg2rad($lng2);
        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
        return $s;
    }
}