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
use app\model\shops\ShopCard as ShopCardModel;

class ShopCard extends BaseModel
{
    protected $hidden = ['create_time'];

    public function add($post)
    {
        $shop_id=TokenService::getCurrentTokenVar('shop_id');
        $num=ShopCardModel::where('shop_id',$shop_id)->count();
        if($num>=3){
            throw new ShopsException(['msg'=>'绑定达上限']);
        }
        $arr=['人民银行','农业银行','工商银行','建设银行'];
        foreach ($arr as $k=>$v){
            if(strchr($post['bank_name'],$v)){
                $data['type']=$k+1;
                break;
            }else{
                $data['type']=0;
            }
        }
        $data['bank_name'] =$post['bank_name'];
        $data['bank_num'] =$post['bank_num'];
        $data['username'] =$post['username'];
        $data['shop_id']=$shop_id;
        $res=ShopCardModel::create($data);
        return $res->id*1;
    }

}