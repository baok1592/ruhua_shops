<?php
/**
 * Created by 如花商城-多商户系统
 * User: 黔域科技
 * QQ群: 728615087
 */
use think\facade\Route;


Route::group('shops', function () {
    Route::get('shops_cms_check_log', 'shops.Common/shopsCms_checkLog');  //检查商家是否登录
    Route::get('token', 'common.Test/load');

    Route::group('boss', function () {
        Route::post('get_yzm','shops.Boss/get_login_yzm');//验证码登陆方式的获取验证码
        Route::post('yzm_login', 'shops.Boss/yzm_login');//提交验证码登录返回Token
        Route::post('login', 'shops.Boss/login');//商家密码登录返回Token
        Route::get('info', 'shops.Boss/get_shop_info');  //商家详细信息

        Route::group('', function () {
            Route::post('edit_psw', 'shops.Boss/edit_psw');   //商家修改密码
            Route::post('edit_info', 'shops.Boss/edit_myshop_info');  //修改我的商家信息
        })->middleware('CheckShops');

    });

    //运费模板
    Route::group('delivery', function () {
        Route::get('delivery_list','shops.Delivery/get_list');//某商户所有运费模板
        Route::get('delivery_detail','shops.Delivery/get_one');//某商户指定一条运费模板
        Route::post('delivery_add','shops.Delivery/add');//某商户新增运费模板
        Route::delete('delivery_del','shops.Delivery/del');//某商户删除运费模板
        Route::post('delivery_edit','shops.Delivery/edit');//某商户修改运费模板
    })->middleware('CheckShops');

    //cms独有
    Route::group('cms', function () {
        Route::get('apply','shops.ShopApply/get_list');//待审批的商家申请
        Route::post('approve_store','shops.ShopApply/examineApply');//审批入驻申请
        Route::post('all_store','shops.Shop/all_store');//所有店铺
    });


    //商品
    Route::group('pro', function () {

        //商家端
        Route::group('', function () {
            Route::post('/up_img/fang', 'shops.Common/upImgSquare');   //商家上传商品缩略图，裁剪正方形
            Route::post('/up_img/chang', 'shops.Common/upImgRectangle');   //商家上传商品详情图，不裁剪只压缩
            Route::get('get_shop_data', 'shops.Shop/get_shop_data');//商家获取店铺详细信息
            Route::post('pro_add', 'shops.Shop/add_product');//商家自己添加产品
            Route::delete('pro_del', 'shops.Shop/del_product');//商家自己删除产品
            Route::post('pro_up', 'shops.Shop/edit_product'); //商家自己更新产品
            Route::get('my_pro_all', 'shops.ShopPro/my_shop_pro'); //某商家所有商品
            Route::put('up_pro', 'shops.Shop/up_pro');   //商家商品上架
            Route::put('down_pro', 'shops.Shop/down_pro');   //商家商品下架
            Route::post('shop_stock', 'shops.ShopPro/shopStock'); //某商家所有商品库存警报
            Route::get('get_all', 'shops.FxManage/get_all');//获取分销商品
            Route::post('add_fx', 'shops.FxManage/add_goods');//添加分销商品
            Route::delete('del_fx', 'shops.FxManage/del_fx');//添加分销商品
            Route::post('update_fx', 'shops.FxManage/update_fx');//更新分销商品
            Route::get('get_no_good', 'shops.FxManage/get_no_fx');//获取未分销商品
        })->middleware('CheckShops');

        Route::group('cms', function () {
            Route::post('pro_list', 'shops.ShopPro/cms_pro_list');   //商家商品下架
        });

        Route::get('pro_detail', 'shops.Shop/getProduct');  //获取某商品详细信息
        Route::get(':id/product', 'shops.Product/getShopProduct'); //指定ID商家所有商品
        Route::post('show_product', 'shops.Product/getShowProduct'); //首页显示所有商品，随机40条
    });

    //店铺分类
    Route::group('category', function () {
        Route::get('all', 'common.Category/getAllCategory');  //检查商家是否登录
    })->middleware('CheckShops');

    //店铺分组
    Route::group('cate', function () {
        Route::post('add_category', 'shops.ShopCategory/addShopCategory'); //添加店铺内分类
        Route::delete('del_category', 'shops.ShopCategory/deleteShopCategory'); //删除店铺内分类
        Route::get('get_category_all', 'shops.ShopCategory/getShopCategory'); //获取店铺内所有分类
    })->middleware('CheckShops');

    //店铺用户和标签
    Route::group('user', function () {
        Route::get('get_all_user', 'shops.ShopMember/StoreAllUser'); //查看店铺用户
        Route::get('user_info', 'shops.ShopMember/getUserInfo'); //查看店铺用户详情

        Route::get('all_label', 'shops.ShopMember/allLabel'); //查看店铺标签
        Route::post('add_label', 'shops.ShopMember/addLabel'); //添加店铺标签
        Route::post('edit_label', 'shops.ShopMember/editLabel'); //修改店铺标签
        Route::delete('del_label', 'shops.ShopMember/deleteLabel'); //删除店铺标签
    });

    //订单
    Route::group('order', function () {

        //商家端
        Route::group('', function () {
            Route::post('order_list', 'shops.ShopOrder/order_list');//商家订单列表
            Route::post('order_detail', 'shops.ShopOrder/shopGetOrderOne');//商家订单详情
            Route::post('fahuo', 'shops.ShopOrder/fahuo');//商家发货
        })->middleware('CheckShops');



        //管理员
        Route::group('admin', function () {
            Route::put('/del_order', 'cms.OrderManage/deleteOrder'); //cms删除订单
            Route::post('/get_order', 'cms.OrderManage/getOrderAll'); //CMS获取所有订单简要
            Route::post('/get_order_one', 'cms.OrderManage/GetOrderOne'); //获取指定订单详细--CMS
            Route::post('/edit_courier', 'cms.OrderManage/editCourier'); //更新订单配送信息
            Route::post('/edit_remark', 'cms.OrderManage/editRemark'); //添加订单备注信息
            Route::post('/edit_price', 'cms.OrderManage/edit_price'); //修改订单价格
            Route::get('/get_tui_all', 'cms.TuiManage/getTuiAll'); //cms 获取所有退款信息
            Route::post('/tui_money', 'cms.TuiManage/TuiMoney'); //微信退款
            Route::post('/tui_bohui', 'cms.TuiManage/TuiBoHui'); //退款申请驳回
            Route::put('/update', 'cms.Common/upValue');   //更新某模型下的某布尔字段,如上下级架
        })->middleware('CheckCms');

        Route::post('count_order', 'shops.Shop/countShopData');//商家统计销售信息
        Route::post('all_data', 'shops.Shop/shopData');//商家统计经营数据
        Route::post('order_count', 'shops.Shop/countOrder');//商家统计订单
        Route::get('order_remind', 'shops.Shop/checkDrive');//待发货提醒
        Route::post('yz_code', 'shops.Shop/yzCode');   //某商家验证券
        Route::post('rate_all', 'shops.Shop/get_rate_shop_all');   //商家所有评价
        Route::post('rate_answer', 'shops.Shop/rate_answer');   //商家回复评价
        Route::post('set_courier', 'shops.Shop/setCourier');   //商家录入快递
        Route::get('order_tx', 'shops.Shop/order_tx');
        Route::post('get_pro_info', 'shops.Shop/get_pro_info');   //商家验证券获取套餐信息
        Route::post('get_sale_data', 'shops.Shop/getSaleData');   //获取商家销售数据
        Route::post('get_sale_money', 'shops.Shop/getMoneyData');   //获取商家资金数据
        Route::post('get_order_list', 'shops.Shop/getOrders');   //获取商家所有订单
    });




});

Route::group('shops/cms/', function () {


    Route::post('store_all', 'shops.Shop/all_store');//所有商家
    Route::post('examine_apply', 'shops.ShopApply/examineApply');//审批商家
    Route::post('assistant_add', 'shops.Boss/add_assistant');//新增店员
    Route::post('assistant_all', 'shops.Boss/all_assistant');//所有店员
    Route::post('store_add', 'shops.Shop/add_store');//新增店铺
    Route::put('stop_store', 'shops.Shop/stop_store');//禁用店铺
    Route::put('recover_store', 'shops.Shop/recover_store');//恢复店铺


});

//用户端
Route::group('shops/', function () {
    Route::post('product/admin/set_sort', 'cms.ProductManage/setSort');//商品排序
    Route::post('/get_order_one', 'cms.OrderManage/GetOrderOne'); //获取指定订单详细--CMS
    Route::put('/del_order', 'cms.OrderManage/deleteOrder'); //cms删除订单


    //采集商品
    Route::group('product/copy', function () {
        Route::post('get_info', 'cms.ProductManage/getCopyProductInfo');//采集商品
    });

    Route::get('category/get_category', 'common.Category/getCateLevel');//获取X级分类信息
    Route::get('article/type_article', 'common.Article/getTypeArticle');//用户获取某个类型的文章
    Route::get('article/detail', 'common.Article/getOneArticle');//获取某文章
    Route::get('hot_pro', 'shops.ShopPro/hot_pro');//获取首页商品
    Route::get('new_pro', 'shops.ShopPro/new_pro');//获取首页商品
    Route::get('list_pro', 'shops.ShopPro/list_pro');//获取首页商品
    Route::get('get_cate_pros', 'shops.ShopPro/getCatePros');//获取某分类下所有商品
    Route::get('search', 'shops.ShopPro/searchGoods');//搜索商品
    Route::get('pro/pro_detail', 'shops.Shop/getProduct');  //商家端获取某商品详细信息
    Route::get('user/pro_detail', 'shops.ShopPro/UserGetProduct');  //用户获取某商品详细信息
    Route::delete('del_rate', 'shops.Shop/del_rate');//用户删除评价
    Route::post('del_rate', 'shops.Shop/del_rate');//用户删除评价



    Route::group('common', function () {
        Route::get('cfg','shops.Common/shops_cfg');//前端获取站点配置
        Route::get('get_product', 'common.Product/getProduct');//获取某商品详情
    });


    Route::group('reg', function () {
        Route::get('check_reg','shops.Boss/check_shops_reg');//检查该用户是否已注册商家
    });

    //区域
    Route::group('city', function () {
        Route::get('get_city', 'shops.city/getCity');//获取用户缓存所在地区
        Route::put('up_city', 'shops.city/updateCity');//修改地区,传入地区ID
        Route::put('up_city_name', 'shops.city/upCityName');//修改地区,传入地区名字
        Route::get('web_city', 'shops.city/AllCity');//获取所有地区
        Route::get('position', 'shops.city/position');//定位获取当前地区：传入坐标
        Route::get('SK_position', 'shops.city/SK_position');//sn定位当前牵位置

        Route::get('select_city', 'shops.city/selectCity');//cms获取所有地区
    });

    Route::group('store', function () {
        Route::get('', 'shops.Shop/get_store_info');  //店铺详细信息
        Route::get('all_group', 'shops.ShopCategory/all_group');  //店铺所有分组
        Route::get('all_pro', 'shops.ShopPro/store_all_pro');  //店铺所有商品
        Route::get('tag_pros', 'shops.ShopPro/tag_pros');  //店铺同标签商品
        Route::get('store_zizi', 'shops.Shop/store_zizi');  //某店铺资质
    });

    Route::group('order', function () {
        Route::post('create_order', 'shops.ShopOrder/create_order');  //小程序创建订单
        Route::post('pay', 'shops.ShopOrder/xcx_pay');  //小程序付款
        Route::post('gzh_create_order', 'shops.ShopOrder/gzh_create_order');  //公众号创建订单
        Route::post('gzh_pay', 'shops.ShopOrder/gzh_pay');  //公众号付款
        Route::get('user_order_one', 'shops.ShopOrder/UserOrderOne'); //获取用户指定的一条订单信息
        Route::post('second_pay', 'shops.ShopPay/gzhPaySecond');   //公众号第二次支付
        Route::post('back', 'shops.ShopPay/gzh_back'); //公众号支付回调
        Route::post('pay/notify', 'shops.ShopPay/receiveNotify');//小程序支付回调


    });




    Route::group('user', function () {
        Route::post('/all_order', 'shops.ShopOrder/getUidOrderAll'); //获取我的所有订单信息
        Route::post('/get_yzm', 'shops.User/getYzm'); //单纯的获取验证码
        Route::post('/bind_phone', 'shops.User/bind_phone'); //单纯的绑定手机号

    });
});


//商家财务
Route::group('shops', function () {
    Route::get('get_shops_info', 'common.Shop/get_shop_record');//获取财务结算
    Route::get('shop_store_info', 'shops.Shop/shop_store_info');//商家获取店铺信息
    Route::get('get_cash', 'common.Shop/getcashsid');//商家获取提现申请记录
    Route::put('trial_cash', 'common.Shop/trial_cash');//管理员审核商家提现申请
    Route::put('del_cash', 'common.Shop/del_cash');//删除提现申请
    Route::get('get_cash_id', 'common.Shop/get_cash_id');//id获取提现申请记录



    //视频
    Route::group('video', function () {
        //公共}
        //管理员
        Route::group('admin', function () {
            Route::post('add_video', 'cms.Common/uploadVideo');   //上传视频
            Route::get('get_all_video', 'cms.VideoManage/getAllVideo');//获取所有视频
            Route::post('edit_video', 'cms.VideoManage/editVideo');//隐藏视频
        })->middleware('CheckShops');
    });


    Route::group('index', function () {
        Route::post('/get_code_img', 'common.Common/gitCodeImg');   //生成二维码
        Route::post('/upload/img', 'cms.Common/uploadImg');   //上传图片
        Route::post('/upload/del_img', 'cms.Common/del_img');   //删除图片
        Route::post('/upload/img_id', 'cms.Common/uploadImgID');   //上传图片返还ID
        Route::post('/upload/down_img', 'cms.Common/downImg');   //下载图片
        Route::get('get_file', 'common.Common/getFile');  //获取文件
    });




})->middleware('CheckShops');

//管理员
Route::group('admin', function () {
    Route::put('trial_cash', 'common.Shop/trial_cash');//管理员审核商家提现申请
    Route::get('del_cash', 'common.Shop/del_cash');//获取申请记录
})->middleware('CheckCms');


//商家后台
Route::group('shops/shopscms', function () {
    Route::post('login', 'cms.ShopUser/Login');//商家登录
    Route::get('get_home', 'cms.ShopUser/get_home');//商家获取首页数据
    Route::get('getcodeimg', 'shops.Shop/getshopcodeimg');//商家获取二维码
    Route::get('check_login', 'shops.Boss/check_login');//定时获取验证数据
    Route::put('upload_code', 'shops.Shop/upload_code');//保存获取的验证码
    Route::get('get_all_img', 'cms.ImageManage/getAllImage');//获取所有图片
    Route::get('get_type_goods', 'shops.ShopPro/getshoptype');//获取下架商品
    Route::group('img', function () {
        Route::get('get_all_img', 'shops.ImageManage/get_all_img');//获取商家所有图片


    })->middleware('CheckShops');


});


//cms统计
Route::group('shops/cms/', function () {
    Route::get('shops_sort', 'shops.ShopStatistics/shop_sort');//店铺排行
    Route::get('user_sort', 'shops.ShopStatistics/user_sort');//用户排行
    Route::get('goods_sort', 'shops.ShopStatistics/goods_sort');//商品销售排行

})->middleware('CheckCms');