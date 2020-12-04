<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\controller\shops;


use app\model\shops\City as CityModel;
use app\model\SysConfig as SysConfigModel;
use app\services\TokenService;
use bases\BaseCommon;
use bases\BaseController;
use exceptions\ShopsException;
use think\facade\Db;
use think\Exception;

class City extends BaseController
{
    //获取用户缓存所在地区
    public function getCity()
    {
        try{
            $data['city_id'] = TokenService::getCurrentTokenVar('city_id');
            $data['name'] = self::get_region_name($data['city_id']);
            return app("json")->go($data);
        }catch (Exception $e){
            return app("json")->go(false);
        }
    }

    //通过ID修改所在地区
    public function updateCity($id)
    {
        $res = CityModel::upCity($id);
        return app("json")->go($res);
    }

    //通过名称修改所在地区
    public function upCityName($name)
    {
        $city_id=CityModel::where('region_name',$name)->value('id');
        if(!$city_id){
            $res = CityModel::field('id,region_name')->find(1); //不存在则返回默认城市
            return app("json")->go($res);
        }
        return $this->updateCity($city_id);
    }

    //获取所有地区的基本信息
    public function AllCity()
    {
        $res = CityModel::field('id,region_name')->select();
        return app("json")->go($res);
    }

    //获取定位
    public function position($coords){
        $key=SysConfigModel::where('key','map_ak')->value('value');
        $htp_url='http://api.map.baidu.com/reverse_geocoding/v3/?ak='.$key.'&output=json&coordtype=wgs84ll&location='.$coords;
        $data=(new BaseCommon)->curl_get($htp_url);
        // 返回数据

        $region=json_decode($data,true);
        if(empty($region['result'])){
            throw new ShopsException(['msg'=>'地址错误']);
        }
        $res['province']=$region['result']['addressComponent']['province'];
        $res['district']=$region['result']['addressComponent']['district'];
        return app("json")->go($res);
    }


    /**
     *SK获取定位
     */
    public function SK_position()
    {
        $ak="";
        //API控制台申请得到的ak（此处ak值仅供验证参考使用）
        $sk = '';//以Geocoding服务为例，地理编码的请求url，参数待填
        $url = "http://api.map.baidu.com/geocoder/v2/?address=%s&output=%s&ak=%s&sn=%s";//get请求uri前缀
        $uri = '/geocoder/v2/';//地理编码的请求中address参数
        $address = '贵州';//地理编码的请求output参数
        $output = 'json';//构造请求串数组
        $querystring_arrays = array (//调用sn计算函数，默认get请求
            'address' => $address,
            'output' => $output,
            'ak' => $ak
        );
        $sn =$this->caculateAKSN($ak, $sk, $uri, $querystring_arrays);//请求参数中有中文、特殊字符等需要进行urlencode，确保请求串与sn对应

        $target = sprintf($url, urlencode($address), $output, $ak, $sn);
  //     $web_url="http://api.map.baidu.com/geocoder/v2/?address=$address&output=json&ak=$ak&sn=$sn";
        $data=(new BaseCommon)->curl_get($target);
        $region=json_decode($data,true);
        $region['url']=$target;
        return app("json")->success($region);


    }

    function caculateAKSN($ak, $sk, $url, $querystring_arrays, $method = 'GET')
    {
        if ($method === 'POST'){
            ksort($querystring_arrays);
        }
        $querystring = http_build_query($querystring_arrays);
        return md5(urlencode($url.'?'.$querystring.$sk));
    }

  
    //获取地区名称
    public static function get_region_name($city_id)
    {
        $name = CityModel::where('id', $city_id)->value('region_name');
        if (!$name) {
            throw new ShopsException(['msg' => '地区错误']);
        }
        return $name;
    }


  

  



    //前端获取所有地区的基本信息
    public function webRegion()
    {
        $res = CityModel::where('id', '>', 1)->field('id,region_name as name')->select();
        return $res;
    }

    //cms获取所有地区的基本信息
    public function selectRegion()
    {
        $res = CityModel::where('id', '>', 1)->with('Admin')->select();
        return $res;
    }



    //cms修改地区
    public function up()
    {
        $post = input('post.');
        $region = new CityModel;
        $one = $region->find($post['id']);
        $AdminModel = new AdminsModel;
        $couponVip=new CouponVipModel();
        Db::startTrans();
        try {
            $id = $post['id'];
            unset($post['id']);
            if ($post['agent_id'] != $one->agent_id) {
                $admin = $AdminModel->where('id', $post['agent_id'])->where('region_id', 0)->find();
                $admin->save(['region_id' => 1]);
                if (!$admin) {
                    throw new ShopsException(['msg' => '一人只能绑定一个地区']);
                }
                $AdminModel->where('id', $one->agent_id)->update(['region_id' => 0]);
            }
            $region->where('id', $id)->update($post);
            $couponVip->where('region_id',$region)->update(['agent_id'=>$one->agent_id]);
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
            throw new ShopsException(['msg' => $e->getMessage()]);
        }
        return 1;
    }

    //cms 删除地区
    public function del(int $id)
    {
        $region = CityModel::find($id);
        $res = $region->delete();
        return $res ? 1 : 0;
    }
}