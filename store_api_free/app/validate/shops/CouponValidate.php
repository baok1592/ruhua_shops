<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/9/20 0020
 * Time: 10:37
 */

namespace app\validate\shops;


use bases\BaseValidate;

class CouponValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require|max:10',
        'stock_type'=>'require|number',
        'full'=>'require|float',
        'reduce'=>'require|float',
        'stock'=>'require',
        'status' => 'require'
    ];
}