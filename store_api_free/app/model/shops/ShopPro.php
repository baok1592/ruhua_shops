<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/16 0016
 * Time: 13:00
 */

namespace app\model\shops;


use app\controller\cms\Oss;
use app\model\DiscountGoods;
use app\model\FxGoods;
use app\model\GoodsSku;
use app\model\Image;
use app\model\PtGoods;
use app\model\Rate;
use app\model\SysConfig as SysConfigModel;
use app\model\User;
use app\services\TokenService;
use bases\BaseModel;
use exceptions\BaseException;
use exceptions\OrderException;
use exceptions\ProductException;
use exceptions\ShopsException;
use think\Exception;
use think\facade\Db;
use think\facade\Log;
use think\model\concern\SoftDelete;

class ShopPro extends BaseModel
{
    use SoftDelete;
    protected $name="Goods";
    protected $deleteTime = 'delete_time';
    protected $hidden = ['is_stock_visible', 'is_pre_sale', 'shipping_fee', 'is_bill'];

    private static function filter_imgs($post,$shop_id)
    {
        $banner_imgs=[];
        $detail_imgs=[];
        $is_oss=SysConfigModel::where('key','upload_oss')->value('value');

        if(!empty($post['banner_imgs']) && is_array($post['banner_imgs'])) {
            foreach ($post['banner_imgs'] as $k=>$v){
                $banner_imgs[$k]=$v;
            }
        }else{
            throw new ShopsException(['msg'=>'请上传缩略图']);
        }
        if(!empty($post['detail_imgs']) && is_array($post['detail_imgs'])) {
            foreach ($post['detail_imgs'] as $k=>$v){
                $detail_imgs[$k]=$v;
            }
        }
        $imgs['shop_id']=$shop_id;
       /* if($is_oss){
            $oss=new Oss();
            $thumb_img=$oss->uploadFile(ROOT.$banner_imgs[0]);
            $banner_imgs=$oss->uploadFiles($banner_imgs);
            $detail_imgs=$oss->uploadFiles($detail_imgs);
            $imgs['thumb_img']=$thumb_img;
            $imgs['banner_imgs'] = $banner_imgs;
            $imgs['detail_imgs'] = $detail_imgs;
        }else{*/
            $imgs['thumb_img']=$banner_imgs[0];
            $imgs['banner_imgs'] = implode(",", $banner_imgs);
            $imgs['detail_imgs'] = implode(",", $detail_imgs);
      //  }

        $imgs['content']=$post['content'];

        return $imgs;
    }
    /**
     * 添加商品
     * @param $post
     * @return int
     * @throws BaseException
     */
    public static function addProduct($post,$shop_id)
    {
        Db::startTrans();// 启动事务
        try {
            $imgs=self::filter_imgs($post,$shop_id);
            unset($post['banner_imgs'],$post['detail_imgs'],$post['content']);
            if(is_array($post['tag_ids'])) {
                $post['tag_ids']=implode(",",$post['tag_ids']);
            }
            $post['shop_id'] = $shop_id;
            $res = self::create($post);
            if (!empty($post['sku'])) {
                (new GoodsSku())->addSku($res['id'], $post['sku']);//添加sku
            }
            if(!$res){
                throw new ProductException(['msg' => '添加失败']);
            }
            $imgs['goods_id']=$res->id;
            $res_img=GoodsImgs::create($imgs);
            if(!$res_img){
                throw new ProductException(['msg' => '商品图片添加失败']);
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback(); // 回滚事务
            throw new ProductException(['msg' => '商品添加失败'.$e->getMessage()]);
        }
    }

    /**
     * 修改商品
     * @param $post
     * @return int
     * @throws BaseException
     */
    public static function editProduct($post,$shop_id)
    {
        Db::startTrans();// 启动事务
        try {
            $imgs=self::filter_imgs($post,$shop_id);
            GoodsImgs::where('goods_id', $post['goods_id'])->update($imgs);
            if(is_array($post['tag_ids'])) {
                $post['tag_ids']=implode(",",$post['tag_ids']);
            }
            $sku=$post['sku'];
            unset($post['sku'],$post['sku_arr']);
            unset($post['banner_imgs'],$post['detail_imgs'],$post['content']);
            $res = self::where(['goods_id' => $post['goods_id'],'shop_id'=>$shop_id])->update($post);
            if (!$res && $res!=0) {
                throw new ProductException(['msg' => '-基础']);
            }
            //删除未填写价格的规格参数行
            //如果用request()->param 数据对sku的操作会有问题
            (new GoodsSku())->editSku($post['goods_id'], $sku); //修改sku
            Db::commit();
            return true;
        } catch (Exception $e) {
            // 回滚事务
            Db::rollback();
            throw new ProductException(['msg' => '商品修改失败' . $e->getMessage()]);
        }
    }


    /**
     * 用户获取某商品详情
     * @param $id
     * @return \think\response\Json
     */
    public static function UserGetProduct($id)
    {
        $res = self::with(['GoodsImgs','sku', 'delivery'])->where(['goods_id'=>$id])->find();
        $url = [];
        $list = [];
        if (!empty($res['banner_imgs'])) {
            $imgs = Image::where('id', 'in', $res['banner_imgs'])->select();
            foreach ($imgs as $k => $v) {
                $url[$k] = $v['url'];
                $list[$k] = $v;
            }
        }
        $res['tags_name']="";
        //获取分组名称
        if(!empty($res->tag_ids)) {
            $tags_name = ShopCategory::where(['id' => $res->tag_ids])->column('name');
            $res['tags_name'] = implode(",", $tags_name);
        }
        if (!empty($res['sku']) && count($res['sku']) > 0) {
            $sku_arr = $res['sku'][0]['json'];
            if ($sku_arr['tree']) {
                foreach ($sku_arr['tree'][0]['v'] as $k => $v) {
                    if (isset($v['img_id'])) {
                        $img_url = Image::where('id', $v['img_id'])->find();
                        $sku_arr['tree'][0]['v'][$k]['imgUrl'] = $img_url['url'];
                    }
                }
            }
            $res['sku_arr'] = $sku_arr;
        } else {
            $res['sku_arr'] = [];
        }
        return $res;
    }


    /**
     * 商家获取某商品详情
     * @param $id
     * @return \think\response\Json
     */
    public static function getProduct($id)
    {
        $shop_id=TokenService::getShopId();
        $res = self::with(['GoodsImgs','sku', 'delivery'])->where(['goods_id'=>$id,'shop_id'=>$shop_id])->find();
        $url = [];
        $list = [];

        if (!empty($res['banner_imgs'])) {
            $imgs = Image::where('id', 'in', $res['banner_imgs'])->select();
            foreach ($imgs as $k => $v) {
                $url[$k] = $v['url'];
                $list[$k] = $v;
            }
        }
        $res['tags_name']="";
        //获取分组名称
        if(!empty($res->tag_ids)) {
            $tags_name = ShopCategory::where(['id' => $res->tag_ids, 'shop_id' => $shop_id])->column('name');
            $res['tags_name'] = implode(",", $tags_name);
        }
        if (!empty($res['sku']) && count($res['sku']) > 0) {
            $sku_arr = $res['sku'][0]['json'];
            if ($sku_arr['tree']) {
                foreach ($sku_arr['tree'][0]['v'] as $k => $v) {
                    if (isset($v['img_id'])) {
                        $img_url = Image::where('id', $v['img_id'])->find();
                        $sku_arr['tree'][0]['v'][$k]['imgUrl'] = $img_url['url'];
                    }
                }
            }
            $res['sku_arr'] = $sku_arr;
        } else {
            $res['sku_arr'] = [];
        }
        return $res;
    }

    /**
     * 获取所有、最新、最热、推荐商品
     * @param $type
     * @return \think\Collection|void
     */
    public static function getRecentAll($type,$lng,$lat)
    {
        $where['state'] = 1;
        if ($type == 'new') {
            $data = self::with(['GoodsImgs','shops'])->where('is_new', 1)->where($where)->order('sort desc')->select();
        } else if ($type == 'hot') {
            $data = self::with(['GoodsImgs','shops'])->where('is_hot', 1)->where($where)->order('sort desc')->select();
        } else if ($type == 'recommend') {
            $data = self::with(['GoodsImgs','shops'])->where('is_recommend', 1)->where($where)->order('sort desc')->select();
        } else {
            $data = self::with(['GoodsImgs','shops'])->where($where)->order('sort desc')->select();
        }
        if (!$data || count($data) < 1) {
            throw new ShopsException(['msg'=>'获取商品失败或无数据']);
        }
        $is_citys=SysConfigModel::where('key','is_same')->value('value');
        if(!$is_citys){
            return $data;
        }
        $shops = Shop::where('shop_state', 1)->column('shop_id');//应该加上城市条件
        if(!empty($lng) && !empty($lat) && !empty($shops)){
            $shopmodel = new Shop();
            $distance =$shopmodel->store_distance($shops, $lng, $lat);
            foreach ($data as $k => $v) {
                foreach ($distance as $key => $value) {
                    if ($v['shop_id'] == $value['shop_id']) {
                        $data[$k]['distance']=floor($value['distance']);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * name获取某商品详情
     * @param $name
     * @return \think\Collection
     */
    public static function getProductByName($name)
    {
        $data = self::with(['imgs','video'])->where('state', 1)->where('goods_name', 'like', '%' . $name . '%')
            ->order('sales desc')->select();
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['discount'] = DiscountGoods::getDiscountGoods($v['goods_id']);
            }
        }
        if (config('setting.is_business') == 1) {
            foreach ($data as $k => $v) {
                $data[$k]['pt'] = PtGoods::getPtGoods($v['goods_id']);
            }
        }
        return $data;
    }

    /**
     * 获取所有上架商品，包含分页
     * @param int $page
     * @param int $size
     * @param string $key
     * @return \think\response\Json
     */
    public static function getProductByPage($key = '')
    {
        if (!empty($key)) {
            $data = self::with(['GoodsImgs'=>function($query){
                $query->field('goods_id,thumb_img');
            },'shops','shops.region'])->where(['state' => 1])->where('goods_name', 'like', '%' . $key . '%')
                ->order('create_time desc')->select();
        } else {
            $data = self::with(['GoodsImgs'=>function($query){
                $query->field('goods_id,thumb_img');
            },'shops','shops.region'])->where(['state' => 1])->
            order('create_time desc')->limit('300')->select();
        }
        return $data;
    }


    public function setImgsAttr($v)
    {
        return $v['url'];
    }

    public function getIsHotAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getIsRecommendAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getIsNewAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getTagIdsAttr($v)
    {
        return explode(",",$v);
    }

    public function getStateAttr($v)
    {
        return $v == 1 ? true : false;
    }

    public function getDetailImgsAttr($v)
    {
        return json_decode($v,true);
    }

    //关联店铺
    public function shops()
    {
        return $this->belongsTo('Shop', 'shop_id', 'shop_id')
            ->field('shop_id,shop_name,region_id,xinxin,shop_state');
    }



    //关联图片
    public function GoodsImgs()
    {
        return $this->hasOne('GoodsImgs', 'goods_id','goods_id');
    }

    //关联视频
    public function video()
    {
        return $this->belongsTo('app\model\Video', 'video_id', 'id');
    }


    //关联评价
    public function rate()
    {
        return $this->belongsTo('app\model\Rate', 'rate_id', 'id');
    }

    //关联规格
    public function sku()
    {
        return $this->hasMany('app\model\GoodsSku', 'goods_id', 'goods_id');
    }

    public function goodsSku()
    {
        return $this->hasOne('app\model\GoodsSku', 'goods_id', 'goods_id');
    }

    //关联物流
    public function delivery()
    {
        return $this->belongsTo('app\model\Delivery', 'delivery_id', 'id')
            ->field('id,name,tag,method');
    }

    //关联限时优惠
    public function discountGoods()
    {
        return $this->hasOne('app\model\DiscountGoods', 'goods_id', 'goods_id');
    }

    //关联拼团
    public function ptGoods()
    {
        return $this->hasOne('app\model\PtGoods', 'goods_id', 'goods_id');
    }

    //关联分销
    public function fxGoods()
    {
        return $this->hasOne('app\model\FxGoods', 'goods_id', 'goods_id');
    }

}