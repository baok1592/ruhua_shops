<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/29 0029
 * Time: 15:42
 */

namespace app\controller\common;


use bases\BaseController;
use Aop\test\AlipayEnter;

class Alipay extends BaseController
{
    public function pay()
    {
        $pay=new AlipayEnter();
        return $pay->pay();
    }

}