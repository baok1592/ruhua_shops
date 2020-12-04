<?php

/**
 * 如花商城系统
 * =========================================================
 * 官方网址：http://www.ruhuashop.com
 * QQ 交流群：728615087
 * Version：1.2.0
 */

namespace app\http\middleware;

//中间件，验证token，检测权限

use app\services\GroupService;
use app\services\TokenService;

class CheckShops
{
    public function handle($request, \Closure $next)
    {
        $sid = TokenService::getCurrentTokenVar('shop_id');
        if ($sid > 0 ) {
            return $next($request);
        }
        return app('json')->fail('没有权限');
    }
}