<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */

namespace app\validate\shops;


use bases\BaseValidate;

class DeliveryValidate extends BaseValidate
{
    protected $rule=[
        'name'=>'require',
        'fa_address'=>'require',
        'tag'=>'require',
        'rules'=>'require'
    ];
}