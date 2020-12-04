<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/24 0024
 * Time: 14:26
 */

namespace app\controller\common;


use app\model\SysConfig;
use app\services\TokenService;
use bases\BaseController;
use app\model\ShopRecord as ShopRecrodModel;
use exceptions\OrderException;
use app\model\BankCard as BankCardModel;
use app\model\ShopCash as ShopCashModel;

class Shop extends BaseController
{
    public function get_shop_record()
    {
        $sid=TokenService::getCurrentTokenVar('shop_id');
        $money=ShopRecrodModel::where('shop_id',$sid)->find();
        $data=array();
        $data['shop']=$money;
        return app('json')->go($data);
    }
    public function add_tx()
    {
        $data=input('post.');
        $sid=TokenService::getCurrentTokenVar('shop_id');
        $shoprecord=ShopRecrodModel::where('shop_id',$sid)->find();
        if(!$shoprecord){
            throw new OrderException(['msg'=>"暂无分销信息"]);
        }
        $min=SysConfig::where('key','min_boss')->value('value');
        if($shoprecord['price']<$min){
            throw new OrderException(['msg'=>"最低金额要高于$min 才能提现".$shoprecord['price']]);
        }

        if($shoprecord['price']<$data['money']){
            throw new OrderException(['msg'=>"提现余额不够"]);
        }
        $card_id=$data['cid'];
        $bk=BankCardModel::where('id',$card_id)->find();
        if(!$bk){
            throw new OrderException(['msg'=>"银行卡不存在"]);
        }
        $bk['sid']=$sid;
        $bk['money']=$data['money'];
        $cash=[
            'sid'=>$sid,
            'money'=>$data['money'],
            'card'=>$bk['card'],
            'bk_name'=>$bk['bk_name'],
            'bk_uname'=>$bk['bk_uname'],
            'card_type'=>$bk['card_type']
        ];
        $res=ShopCashModel::create($cash);
        if($res){
            ShopRecrodModel::update(['price'=>($shoprecord['price']-$data['money'])],['shop_id'=>$sid]);
        }
        return app('json')->go($res);
    }

    public function getcashsid()
    {
        $sid=TokenService::getCurrentTokenVar('shop_id');
        $list=ShopCashModel::where('sid',$sid)->select();
        return app('json')->go($list);
    }

    public function trial_cash($id,$type)
    {
        $cash=ShopCashModel::where('id',$id)->find();

        if(!$cash){
            throw new OrderException(['msg'=>"申请错误"]);
        }
        if($cash['state']>0){
            throw new OrderException(['msg'=>"申请已审核"]);
        }
        $res=ShopCashModel::update(['state'=>$type],['id'=>$id]);
        if($type==2){

            $record=ShopRecrodModel::where('shop_id',$cash['sid'])->find();
            ShopRecrodModel::update(['price'=>($record['price']+$cash['money'])],['id'=>$record['id']]);
        }
        return app('json')->go($res);
    }

    public function get_all_cash()
    {
        $list=ShopCashModel::with('getshop')->visible(['getshop'=>['shop_name']])->select();
        return app('json')->go($list);

    }

    public function del_cash($id)
    {
        $reg=ShopCashModel::destroy($id);
        return app('json')->go($reg);
    }

    public function get_cash_id($id)
    {
        $list=ShopCashModel::where('id',$id)->select();
        return app('json')->go($list);
    }



}