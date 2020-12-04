<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8 0008
 * Time: 9:49
 */

namespace app\validate\shops;


use bases\BaseValidate;

class ApplyValidate extends BaseValidate
{
    protected $rule = [
        'shop_name'=>'require',
        'shop_address'=>'require',
        'region_id'=>'require',
        'type_id'=>'require|number', 
        'imgs'=>'min:0'
    ];
}