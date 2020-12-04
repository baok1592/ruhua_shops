<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/15 0015
 * Time: 11:07
 */

namespace app\controller\common;


use bases\BaseController;
use app\model\Company as CompanyModel;
use exceptions\OrderException;

class Company extends BaseController
{
    public function add()
    {
        $post=input('post.');

        $rule=[
            'name'=>'require',
            'address'=>'require',
            'user'=>'require',
            'mobile'=>'require'
        ];
        $this->validate($post,$rule);
        $list=CompanyModel::where('name',$post['name'])->find();
        if($list){
            throw new OrderException(['msg'=>'单位已存在']);
        }
        $res=CompanyModel::create($post);
        return app('json')->go($res);

    }
    public function del($id)
    {
        $res=CompanyModel::destroy($id);
        return app('json')->go($res);
    }

    public function update()
    {
        $post=input('post.');
        $rule=[
            'name'=>'require',
            'address'=>'require',
            'user'=>'require',
            'mobile'=>'require'
        ];
        $list=CompanyModel::where('id',$post['id'])->find();
        if(!$list){
            throw new OrderException(['msg'=>'非法操作']);
        }
        $this->validate($post,$rule);
        $res=CompanyModel::update($post,['id'=>$post['id']]);
        return app('json')->go($res);
    }
    public function get_All()
    {
        $list=CompanyModel::select();
        return app('json')->go($list);
    }

    public function getById($id)
    {
        $list=CompanyModel::where('id',$id)->find();
        return app('json')->go($list);
    }




}