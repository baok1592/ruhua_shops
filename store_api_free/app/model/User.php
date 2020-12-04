<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/17 0017
 * Time: 9:03
 */

namespace app\model;


use Aliyun\api_demo\SmsDemo;
use app\services\CommonServices;
use app\services\MergeService;
use app\services\QrcodeServer;
use app\services\TokenService;
use bases\BaseModel;
use WxCode\demoWxCode;

class User extends BaseModel
{
    public static function onBeforeInsert($value)
    {
        $value['invite_code'] = rand(100000, 999999);
    }

    /**
     * 获取所有用户信息
     * @return mixed
     */
    public static function getAllUser()
    {
        $res = self::field('id,nickname,headpic,points,web_auth_id,create_time')->select();
        return app('json')->success($res);
    }

    /**
     * 微信获取手机号并绑定
     * @param $uid
     * @param $post
     * @return mixed
     */
    public static function addWxMobile($uid, $post)
    {
        $session_key = TokenService::getCurrentTokenVar('session_key');
        $data = (new demoWxCode())->decryptData($post['encryptedData'], $post['iv'], $session_key);
        if (isset($data['phoneNumber'])) {
            self::where(['id' => $uid])->update(['mobile' => $data['phoneNumber']]);
            return app('json')->success();
        }
        return app('json')->success($data);
    }

    /**
     * 手机绑定获取验证码
     * @param $uid
     * @param $mobile
     * @return mixed
     */
    public static function addMobile($uid, $mobile)
    {
        $res = self::where('id', $uid)->where('mobile', $mobile)->find();
        if ($res) {
            return app('json')->fail('已绑定');
        }
        $mobile_user = self::where('mobile', $mobile)->find();
        if ($mobile_user) {
            $data['code'] = rand(10000, 999999);
            $data['code_time'] = time() + (60 * 5);
            $mobile_user->save($data);
            SmsDemo::sendSms($mobile, $data['code']);
            return app('json')->success();
        }
        $user = self::where('id', $uid)->find();
        $data['mobile'] = $mobile;
        $data['code'] = rand(10000, 999999);
        $data['code_time'] = time() + (60 * 5);
        $user->save($data);
        SmsDemo::sendSms($mobile, $data['code']);
        return app('json')->success();
    }

    /**
     * 绑定手机号
     * @param $type
     * @param $uid
     * @param $mobile
     * @param $code
     * @return mixed
     */
    public static function bindMobile($type, $uid, $mobile, $code)
    {
        $res = self::where(['mobile' => $mobile, 'code' => $code])->whereTime('code_time', '>', time())->find();
        if (!$res) {
            return app('json')->fail('验证码错误');
        }
        if ($res['id'] == $uid) {
            return app('json')->success();
        }
        switch ($type) {
            case 'xcx':
                $field = 'openid';
                break;
            case 'gzh':
                $field = 'openid_gzh';
                break;
            case 'app':
                $field = 'openid_app';
                break;
        }
        $openid = self::where('id', $uid)->value($field);
        $res->save([$field => $openid]);
        (new MergeService())->mergeUser($res['id'], $field, $openid, 1);//合并
        return app('json')->success('绑定成功');
    }

    public static function editCpy($post, $uid)
    {
        $user = self::where('id', $uid)->find();
        $data['user_name'] = $post['user_name'];
        $data['cpy_name'] = $post['cpy_name'];
        $data['cpy_num'] = $post['cpy_num'];
        $data['email'] = $post['email'];
        $user->save($data);
        return app('json')->success();
    }

    public static function getCpyInfo($uid)
    {
        $user = self::where('id', $uid)->find();
        $data['user_name'] = $user['user_name'];
        $data['cpy_name'] = $user['cpy_name'];
        $data['cpy_num'] = $user['cpy_num'];
        $data['email'] = $user['email'];
        return $data;
    }

    public function getXcxInviteUrl($uid, $path, $scene)
    {
        $user = self::where('id', $uid)->find();
        if (!$user['invite_url']) {
            $user['invite_url'] = ',,';
        }
        if(!$user['invite_code']){
            $user['invite_code']=rand(100000, 999999);
        }
        $invite_url = explode(',', $user['invite_url']);
        if ($path && $path!="") {
            $url = (new CommonServices())->getXcxCodeImg($path, $scene, $user['invite_code']);
        } else {
            if ($invite_url[0]) {
                $url = $invite_url[0];
            } else {
                $url = (new CommonServices())->getXcxCodeImg($path, $scene, $user['invite_code']);
                $invite_url[0] = $url;
                $invite_url = implode(',', $invite_url);
                $user->save(['invite_url' => $invite_url, 'invite_code' => $user['invite_code']]);
            }
        }
        return app('json')->success('操作成功', $url);
    }

    public function getWebInviteUrl($uid, $path)
    {
        $user = self::where('id', $uid)->find();
        if (!$user['invite_url']) {
            $user['invite_url'] = ',,';
        }
        if(!$user['invite_code']){
            $user['invite_code']=rand(100000, 999999);
        }
        $invite_url = explode(',', $user['invite_url']);
        $api = app('system')->getValue('api_url');
        if ($path) {
            $code = $api . 'h5/#/' . $path . '&sf=' . $user['invite_code'];
            //$url = (new QrcodeServer())->get_qrcode($code);
            $url = (new QrcodeServer())->getCodeUrl($code);
        } else {
            if ($invite_url[1]) {
                $url = $invite_url[1];
            } else {
                $code = $api . 'h5?sfm=' . $user['invite_code'];
                $url = (new QrcodeServer())->getCodeUrl($code);
                $invite_url[1] = $url;
                $invite_url = implode(',', $invite_url);
                $user->save(['invite_url' => $invite_url, 'invite_code' => $user['invite_code']]);
            }
        }
        return app('json')->success('操作成功', $url);
    }

    public function vip()
    {
        return $this->belongsTo('VipUser', 'id', 'user_id');
    }

    public function getNicknameAttr($v)
    {
        return base64_decode($v);
    }

    /**
     * 用户排名
     * type 0总订单数1总消费金额排名
     */
    public static function Statistics($type=0)
    {
        $user=self::with(['order'])->select();
        foreach ($user as $k=>$v){
            $count=count($v['order']);
            $v['countmoney']=self::getCountMoney($v['order']);
            //  $finish=count($v['finish']);
            unset($v['order']);
            //    unset($v['finish']);
            $v['order']=$count;
            //  $v['finish']=$finish;
            $user[$k]=$v;
        }
        switch ($type){
            case 0:
                $tp='order';
                break;
            default:
                $tp='countmoney';

        }
        $user=self::user_sort($user,$tp);
        return $user;
    }


    /**
     * 总订单数排名
     */
    private static function user_sort($shop,$type)
    {
        for ($i=0;$i<count($shop);$i++)
            for($j=0;$j<$i;$j++){
                if($shop[$i][$type]>$shop[$j][$type]){
                    $v=$shop[$i];
                    unset($shop[$i]);
                    $shop[$i]=$shop[$j];
                    unset($shop[$j]);
                    $shop[$j]=$v;
                    unset($v);


                }
            }
        return $shop;
    }


    public function order()
    {
        return $this->hasMany('Order','user_id','id');
    }

    public static function getCountMoney($data)
    {
        $sum=0;
        foreach ($data as $k=>$v){
            if($v['state']==1||$v['state']==2){
                $sum+=$v['order_money'];
            }
        }
        return $sum;
    }
}