<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
namespace app\controller\shops;


use app\model\shops\ShopPro as ShopProModel;
use app\model\shops\Shop as ShopModel;
use app\services\TokenService;
use app\validate\shops\ProductValidate;
use bases\BaseController;
use exceptions\BaseException;
use exceptions\ShopsException;
use app\model\Rate as RateModel;
use app\model\Order as OrderModel;
use app\services\CommonServices;
use app\model\shops\ShopUser as ShopUserModel;
use app\model\Favorites as FavoritesModel;
use app\model\shops\Goods as GoodsModel;

//商铺类
class Shop extends BaseController
{

/************************以下是商家端************************************/
    //商家自己添加商品
    public function add_product()
    {
        $validate = new ProductValidate();
        $validate->goCheck();
        $post = $validate->getDataByRule(input('post.'));
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopProModel::addProduct($post,$shop_id);
        return app("json")->go($res);
    }

    //商户自己删除商品
    public function del_product( $id)
    { 
        $shop_id = TokenService::getCurrentTokenVar('shop_id');  
        $good_one = ShopProModel::where(['goods_id' => $id, 'shop_id' => $shop_id])->find();
        if (!$good_one) {
            throw new ShopsException(['msg' => '商品不存在']);
        }
        $res = $good_one->delete();
        return app("json")->go($res);
    }
    
    //更新商品
    public function edit_product()
    {
        $validate = new ProductValidate();
        $validate->goCheck();
        $goods_id=input('post.goods_id');
        $post = $validate->getDataByRule(input('post.'));
        $post['goods_id']=$goods_id;
        $shop_id = TokenService::getCurrentTokenVar('shop_id');
        $res = ShopProModel::editProduct($post,$shop_id);
        return app("json")->go($res);
    }



    /**
     * 商品详情
     * @param string $id
     * @return mixed
     */
    public function getProduct(int $id)
    {
        $res = ShopProModel::getProduct($id);
        return app('json')->go($res);
    }

    //商品上架
    public function up_pro(int $id)
    {
        $where['goods_id'] = $id;
        $where['shop_id'] = TokenService::getCurrentTokenVar('shop_id');
        $where['state'] = 0;
        $res=GoodsModel::update(['state' => 1],$where);
        return app("json")->go($res);
    }

    //商品下架
    public function down_pro(int $id)
    {
        $where['goods_id'] = $id;
        $where['shop_id'] = TokenService::getCurrentTokenVar('shop_id');
        $where['state'] = 1;
        $res=GoodsModel::update(['state' => 0],$where);
       // $res = Goods::where($where)->update(['state' => 0]);
        return app("json")->go($res);
    }


/************************以下是用户端************************************/

    //通过ID获店铺信息
    public function get_store_info(int $id)
    {
        $res=(new ShopModel)->shop_id_info($id);
        return app("json")->go($res);
    }


    //通过ID获店铺资质
    public function store_zizi(int $shop_id)
    {
        $license=(new ShopModel)->where('shop_id',$shop_id)->value('license_url');
        if(!$license){
            throw new ShopsException(['msg'=>'信息错误']);
        }
        $res['license']=$license;
        return app("json")->go($res);
    }


/************************以下是CMS端************************************/

    //所有店铺
    public function all_store()
    {
        $res=ShopModel::with('region')->field('shop_id,region_id,shop_name,shop_phone,shop_description,uid,
        shop_state,create_time,license_url,user_sfz_url,shop_pic_url,chouyong,sort')->select()->toArray();
        return app("json")->go($res);
    }

    //通过ID获店铺信息
    public function shop_store_info()
    {
        $sid=TokenService::getShopId();
        $res=(new ShopModel)->shops_id_info($sid);
        return app("json")->go($res);
    }

    //新增店铺
    public function add_store()
    {
        $post=input('post.');
        $post['yy_time']=implode("--",$post['yy_time']);
        $post['shop_address']=implode(",",$post['shop_address']);
        $post['shop_state']=1;
        $res=ShopModel::create($post);
        return app("json")->go($res);
    }

    //禁用店铺
    public function stop_store(int $id)
    {
        $res=ShopModel::where('shop_id',$id)->save(['shop_state'=>0,'shop_close_info'=>'管理员关闭']);
        return app("json")->go($res);
    }

    //禁用店铺
    public function recover_store(int $id)
    {
        $res=ShopModel::where('shop_id',$id)->save(['shop_state'=>1,'shop_close_info'=>'']);
        return app("json")->go($res);
    }

    //商家获取所有评论
    public function get_rate_shop_all()
    {
        $sid=TokenService::getShopId();
        $data = RateModel::with(['user','goods'])->where('aid',$sid)->order('create_time desc')->select();
        return app('json')->go($data);
    }
    //商家回复评论
    public function rate_answer()
    {
        $post=input('post.');
        $uid=TokenService::getShopId();
        $rate=RateModel::where('id',$post['id'])->find();
        if(!$rate){
            throw new ShopsException(['msg'=>'该条评论已被删除']);
        }
        if($rate['reply_content']!=null){
            throw new ShopsException(['msg'=>'已评论']);
        }
        $res=RateModel::update(['reply_content'=>$post['reply_content'],'reply_time'=>time(),'aid'=>$uid],['id'=>$post['id']]);
        return app('json')->go($res);
    }

    /**删除评价
     * @param $id
     * @return mixed
     */
    public function del_rate($id)
    {
        $uid=TokenService::getCurrentUid();
        $res=RateModel::destroy($id);
        return app('json')->go($res);
    }

    public function set_pj()
    {
        $uid=TokenService::getCurrentUid();
        $rule=[
            'id' => 'require|number',
            'goods_id' => 'require',
            'rate' => 'require',
            'content' => 'require',
            'imgs' => 'min:0',
        ];
        $post = input('post.');
        $this->validate($post,$rule);
        $post['aid']=$uid;
        return OrderModel::setPj($uid,$post);
    }

    public function getshopcodeimg()
    {
        $num = 8;//位数
        $code = "";
        for($i=0;$i<$num;$i++){
            $code .= chr(mt_rand(97, 122));      //小写a-z
        }

        $url=CommonServices::getShopCodeUrl($code);
        $data=array();
        $data['type']='login';
        $data['data']=$code;
        $data['url']=$url;

        return app('json')->go($data);
    }

    public function upload_code($code)
    {
        $uid=TokenService::getShopUid();
        $res=ShopUserModel::update(['check'=>$code],['id'=>$uid]);
        return app('json')->go($res);
    }

    public function get_shop_data()
    {
        $sid=TokenService::getShopId();
        $shop=ShopModel::where('shop_id',$sid)->find();
        if(!$shop){
            throw new BaseException(['msg'=>'店铺不存在']);
        }
        $data=array();
        $data['chouyong']=$shop['chouyong'];
        $data['favorite']=FavoritesModel::where(['type'=>'shop','fav_id'=>$sid])->count();
        $data['good_rate']=RateModel::where('aid',$sid)->where('rate','>',3)->count();
        $data['bad_rade']=RateModel::where('aid',$sid)->where('rate','<',4)->count();
        $data['avg']=RateModel::where('aid',$sid)->avg('rate');
        return app('json')->go($data);




    }








}
