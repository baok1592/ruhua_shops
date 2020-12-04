<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 11:12
 */

namespace app\controller\cms;


use app\model\Rate as RateModel;
use app\validate\IDPostiveInt;
use bases\BaseController;

class RateManage extends BaseController
{
    /**
     * cms 获取所有评价
     * @return \think\Collection
     */
    public function getAllRate(){
        $data = RateModel::with(['user','goods'])->order('create_time desc')->select();
        return app('json')->success($data);
    }

    /**
     * cms 删除评论
     * @param $id
     * @return int
     */
    public function deleteRate($id)
    {
        (new IDPostiveInt())->goCheck();
        $result= RateModel::where('id',$id)->delete();//软删除
        if(!$result){
            return app('json')->fail();
        }
        return app('json')->success();
    }



}