<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;


use app\model\shops\ShopUser as ShopUserModel;
use app\model\User as UserModel;
use app\model\shops\ShopApply as ShopApplyModel;
use app\services\TokenService;
use bases\BaseCommon;
use bases\BaseController;
use exceptions\ShopsException;
use think\facade\Request;
use app\model\shops\Shop as ShopModel;

class Boss extends BaseController
{

    //商家获取登录验证码
    public function get_login_yzm()
    {
        if(input("?post.mobile")) {
            $res = ShopUserModel::set_login_yzm(input("post.mobile"));
            return app("json")->go($res);
        }
    }
    //商家扫码登录
    public function check_login($code)
    {
        $list=ShopUserModel::where('check',$code)->find();
        if(!$list){
            return app("json")->fail(['msg'=>'','code'=>'400']);
        }else{
            $res=ShopUserModel::check_login($list);
            $tk=['token'=>$res,'code'=>200,'msg'=>'操作成功'];
            return app("json")->go($tk);
        }

    }



   //提交验证码登录返回Token
    public function yzm_login()
    {
        $post=Request::param();
        if(strlen($post['code'])<5){
            throw new ShopsException(['msg'=>'验证码长度错误']);
        }
        $res['token'] = ShopUserModel::yzm_login($post);
        return app("json")->go($res);
    }

    //商家密码登录返回Token
    public function login()
    {
        $post=Request::param();
        if(strlen($post['password'])<6){
            throw new ShopsException(['msg'=>'密码长度错误']);
        }
        $res['token'] = ShopUserModel::login($post);
        return app("json")->go($res);
    }

    //商家修改密码
    public function edit_psw()
    {
        $post=Request::param();
        if(strlen($post['new_psw'])<6){
            throw new ShopsException(['msg'=>'密码长度错误']);
        }
        $res = ShopUserModel::edit_psw($post);
        return app("json")->go($res);
    }

    //商家详细信息
    public function get_shop_info()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res=(new ShopModel)->shop_info($shop_id);
        return app("json")->go($res);
    }

    //修改我的商家信息
    public function edit_myshop_info()
    {
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $post = Request::param();
        $data = [];
        $data['shop_phone'] = $post['shop_phone'];
        $data['yy_time'] = $post['yy_time'];
        $data['shop_description'] = $post['shop_description'];
        $data['shop_state'] = $post['shop_state'];
        $data['lat'] = $post['position']['latitude'];
        $data['lng'] = $post['position']['longitude'];
        $data['position'] = json_encode($post['position'], JSON_UNESCAPED_UNICODE);
        $res = ShopModel::where('shop_id', $shop_id)->update($data);
        return $res;
    }

    //获取所有商家管理员
    public function getuser()
    {
        $users = db('shop_user')->select();
        $shopmodel = new ShopModel;
        foreach ($users as $k => $v) {
            $name = $shopmodel->where('shop_id', $v['shop_id'])->value('shop_name');
            if ($name) {
                $users[$k]['shop_name'] = $name;
            }
        }
        return json($users, 201);
    }

    //新增店员
    public function add_assistant()
    {
        $post=input('post.');
        $post['password']=(new BaseCommon)->store_password($post['password']);
        $res=ShopUserModel::create($post);
        return app('json')->go($res);
    }

    //所有店员
    public function all_assistant()
    {
        $res=ShopUserModel::with('shopName')->withoutField('password')->select();
        return app('json')->go($res);
    }

    //查看该用户是否已注册商家
    public function check_shops_reg()
    {
        $res['state']=0;    //0未注册1申请中2已注册-1已驳回
        $uid = TokenService::getCurrentUid();
        $apply=ShopApplyModel::where('uid',$uid)->find();
        if($apply){
            if($apply->apply_status==1) {
                $res['state'] = 1;
            }
            if($apply->apply_status==0) {
                $res['state'] = -1;
            }
            if($apply->apply_status==2) {
                $res['state']=2;
                return app('json')->go($res);   //2
            }
            return app('json')->go($res);   // 1 or -1
        }
        $mobile=UserModel::where('id',$uid)->value('mobile');   //用户
        $shop_user=ShopUserModel::where('mobile',$mobile)->find();   //店员
        if($shop_user) {
            $res['state']=2;
            return app('json')->go($res);   //2
        }
        if(!$mobile){
            $res['state']=0;
            return app('json')->go($res);   //0
        }
    }
}