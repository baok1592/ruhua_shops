<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/22 0022
 * Time: 9:41
 */

namespace app\services;

use app\controller\auth\Auth;
use app\model\Order as OrderModel;
use app\model\User as UserModel;
use app\model\Template as TemplateModel;
use think\facade\Log;

//小程序模板消息
class XcxMessage
{
    /**发过推送订单消息
     * @param $order_num
     */
    public function send_order_massage($order_id)
    {
        $uid=OrderModel::where('order_id',$order_id)->value('user_id');
        $open_id=UserModel::where('id',$uid)->value('openid');
        $order=OrderModel::where('order_id',$open_id)->find();
        $total=$order['order_money'];
        $date=Date('Y-m-d h:m:s');
        $recive_name=$order['receiver_name'];
        $order_num=$order['order_num'];
        $address=$order['receiver_city']." ".$order['receiver_address'];
        $token=(new Auth())->access_token();
        $template_id=TemplateModel::where('id',7)->value('temp_id');
        $txt="{
  \"touser\": \"$open_id\",
  \"template_id\": \"$template_id\",
  \"data\": {
      \"character_string1\": {
          \"value\": \"$order_num\"
      },
      \"date5\": {
          \"value\": \"$date\"
      },
      \"amount10\": {
          \"value\": \"$total\"
      } ,
      \"name12\": {
          \"value\": \"$recive_name\"
      },
       \"thing11\": {
          \"value\": \"$address\"
      }
  }
}";

        $url="https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=$token";
        $res=$this->https_post($url,$txt);
        Log::error("订阅消息通知");
        Log::error($res);
        Log::error("订阅消息通知");
        return $res;
    }

    private function https_post($url,$data)
    {
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $res=curl_exec($ch);
        if(curl_errno($ch)){
            return 'Error'.curl_error($ch);
        }
        curl_close($ch);
        return $res;

    }

}