<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

//直播
Route::group('player', function () {
    Route::post('list', 'common.Live/index');
});


Route::get('', function () {
    echo '如花商城-多商户系统'.VAE_VERSION.'(开源版)';
});

Route::get('test', 'common.Common/sms');

Route::get('oss', 'cms.Oss/list_object');

Route::get('api_check', 'install.Check/index');


//系统安装
Route::group('install', function () {
    Route::get('', 'install.Index/step1');
    Route::get('step2', 'install.Index/step2');
    Route::get('step3', 'install.Index/step3');
    Route::post('create_data', 'install.Index/createData');
});
Route::get('test_s', 'install.UpDate/test');//验证权限


//APP系统更新
Route::group('', function () {
    Route::get('up_version', 'common.Common/up_version');//app端强制升级
    Route::get('up_sd_version', 'common.Common/up_sd_version');//app端手动升级
});

//CMS系统更新
Route::group('update', function () {
    Route::get('get_rh_file', 'install.UpDate/getRhFile');//获取更新包
    Route::get('get_version', 'install.UpDate/getVersion');//获取版本信息
    Route::get('get_url', 'install.UpDate/getResource');//获取下载url
})->middleware('CheckCms');

//定时任务
Route::group('task', function () {
    Route::get('day_task', 'common.Task/getDayRefresh');  //1天定时任务
    Route::get('refresh', 'common.Task/getRefresh');  //3小时定时任务
    Route::get('order_task', 'common.Task/getOrderTask');  //关闭未支付订单、拼团失败订单定时任务
    Route::get('check_order_task', 'common.Task/checkLoopTask');  //查看系统自动关闭订单运行状态
    Route::get('loop_task', 'common.Task/getLoopTask');  //循环任务，关闭未支付订单、拼团失败订单
});

//微信授权获取token
Route::group('auth', function () {
    Route::get('wxcode_url', 'auth.Auth/wxcodeUrl');   //请求公众号code
    Route::post('get_xcx_token', 'auth.Auth/getXcxToken');   //小程序获取用户token
    Route::post('token_verify', 'auth.Auth/verifyToken');   //验证用户token
    Route::get('gzh_token', 'auth.Auth/getToken');   //异步接收公众号code,获取openid，返回token
    Route::post('upinfo', 'auth.Auth/xcx_upinfo');   //更新用户昵称、头像
    Route::post('app_wx_login', 'auth.Auth/app_wx_login');   //app微信登陆
    Route::post('get_app_token', 'auth.Auth/getAppToken');   //app获取用户token

    Route::post('get_login_code', 'auth.Mobile/getLoginCode');   //获取手机登录的验证码
    Route::post('get_mobile_token', 'auth.Mobile/getMobileToken');   //获取手机登录的token

    Route::post('get_code', 'user.UserMobile/getMobileCode');   //获取手机绑定的验证码
    Route::post('bind_mobile', 'user.UserMobile/addMobileBind');   //绑定用户

    Route::post('bind_wx_mobile', 'user.UserMobile/getWxMobile');   //获取微信的手机号并绑定
//    Route::get('test_get_token', 'auth.Auth/testGetToken');   //合并测试
});

//公共
Route::group('index', function () {


    Route::group('', function () {
        Route::post('/get_code_img', 'common.Common/gitCodeImg');   //生成二维码
        Route::post('/upload/img', 'cms.Common/uploadImg');   //上传图片
        Route::post('/upload/img_id', 'cms.Common/uploadImgID');   //上传图片返还ID
        Route::post('/upload/down_img', 'cms.Common/downImg');   //下载图片
        Route::get('get_file', 'common.Common/getFile');  //获取文件
    });

    //银行卡
    Route::group('bk_card', function () {
        Route::post('add_bk_card', 'common.BankCard/add');//添加银行卡
        Route::delete('del_bk_card', 'common.BankCard/del');//删除银行卡
        Route::get('get_bk_card', 'common.BankCard/getByType');//用户获取银行卡
        Route::post('update_bk_card', 'common.BankCard/update');//修改获取银行卡
    });

    //用户
    Route::group('user', function () {
        Route::get('sys_config', 'common.common/getSysConfig');//前端获取部分配置信息
    });

    //管理员
    Route::group('admin', function () {
        Route::post('login', 'cms.Admin/login');//管理员登录
        Route::get('check_login', 'cms.Common/checkLogin');//管理员检查是否登录
        Route::get('get_code', 'cms.Admin/getCode');//获取验证码


        Route::any('ue_uploads', 'cms.Common/ue_uploads');
    });
});

//文章、公告Article
Route::group('article', function () {
    //公共
    Route::group('', function () {
        Route::get('get_all_article', 'common.Article/getAllArticle');//获取所有的文章
        Route::get('get_article', 'common.Article/getArticle');//获取一篇公告
        Route::get('get_one_article', 'common.Article/getOneArticle');//获取文章详情
        Route::get('user_article', 'common.Article/userArticle');//用户获取文章
        Route::get('type_article', 'common.Article/getTypeArticle');//用户获取某个类型的文章
    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_all_article', 'cms.ArticleManage/adminGetAllArticle');//获取所有的文章
        Route::get('all_article_name', 'cms.ArticleManage/allArticleName');//获取所有的文章
        Route::post('add_article', 'cms.ArticleManage/addArticle');//添加文章
        Route::post('edit_article', 'cms.ArticleManage/editArticle');//修改文章
        Route::put('del_article', 'cms.ArticleManage/deleteArticle');//删除文章

    })->middleware('CheckCms');
});

//广告banner
Route::group('banner', function () {
    //公共
    Route::group('', function () {
        Route::get('get_banner', 'common.Banner/getBannerItem');//获取某个广告
        Route::get('get_all_banner', 'common.Banner/getAllBanner');//获取所有广告位
        Route::get('banner_all_item', 'common.Banner/banner_all_item');//获取所有广告
        Route::get('get_banner_content', 'common.Banner/get_banner_content');//获取所有广告详情
    });

    //管理员
    Route::group('admin', function () {
        Route::get('banner_all_item', 'cms.BannerManage/adminAllBanner');//cms获取所有广告
        Route::post('add_banner', 'cms.BannerManage/addBanner');//添加广告
        Route::post('edit_banner', 'cms.BannerManage/editBanner');//修改广告
        Route::put('del_banner', 'cms.BannerManage/deleteBanner');//删除广告
        Route::post('set_sort', 'cms.BannerManage/setSort');//更新广告排序

    })->middleware('CheckCms');
});

//分类Category
Route::group('category', function () {
    //公共
    Route::group('', function () {
        Route::get('get_category', 'common.Category/getCateLevel');//获取X级分类信息
        Route::get('all_category', 'common.Category/getAllCategory');//获取所有分类信息
        Route::get('category_cid', 'common.Category/getCateChildImg');//获取分类下所有子类与广告图
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_category', 'cms.CategoryManage/addCategory');//添加广告
        Route::post('edit_category', 'cms.CategoryManage/editCategory');//修改广告
        Route::put('del_category', 'cms.CategoryManage/deleteCategory');//删除广告
        Route::get('all_category', 'cms.CategoryManage/getCateSort');//cms 获取所有分类并排好序，包括隐藏
        Route::post('set_sort', 'cms.CategoryManage/setSort');//更新广告排序
    })->middleware('CheckCms');
});

//导航
Route::group('nav', function () {
    //公共
    Route::group('', function () {
        Route::get('get_nav', 'cms.NavManage/getNav');//获取X级分类信息

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_nav', 'cms.NavManage/addNav');//新增导航
        Route::post('edit_nav', 'cms.NavManage/editNav');//修改导航
        Route::put('del_nav', 'cms.NavManage/deleteNav');//删除导航
        Route::get('all_nav', 'cms.NavManage/getNav');//cms 获取所有导航
        Route::post('set_sort', 'cms.NavManage/setSort');//更新排序导航
    })->middleware('CheckCms');
});

//视频
Route::group('video', function () {
    //公共}
    //管理员
    Route::group('admin', function () {
        Route::post('add_video', 'cms.Common/uploadVideo');   //上传视频
        Route::get('get_all_video', 'cms.VideoManage/getAllVideo');//获取所有视频
        Route::post('edit_video', 'cms.VideoManage/editVideo');//隐藏视频
    })->middleware('CheckCms');
});


//图片
Route::group('img_category', function () {
    //公共
    Route::group('', function () {
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_category', 'cms.ImageManage/addImageCategory');//添加分类
        Route::put('del_category', 'cms.ImageManage/deleteImageCategory');//删除分类
        Route::get('get_category', 'cms.ImageManage/getImageCategory');//获取所有分类
        Route::get('get_all_img', 'cms.ImageManage/cmsgetAllImage');//获取所有图片
        Route::post('edit_image', 'cms.ImageManage/editImage');//隐藏图片
        Route::post('/upload/img', 'cms.Common/uploadImg');   //上传图片
    })->middleware('CheckCms');
});

//分组group
Route::group('group', function () {
    //公共
    Route::group('', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_group', 'cms.Group/addGroup');//添加分组
        Route::post('edit_group', 'cms.Group/editGroup');//修改分组
        Route::put('del_group', 'cms.Group/deleteGroup');//删除分组
        Route::get('get_all_group', 'cms.Group/getAllGroup');//获取所有的分组
        Route::get('get_one_group', 'cms.Group/getOneGroup');//获取分组详情
        Route::get('get_all_rule', 'cms.Group/getAllGroupRule');//获取分组详情

    })->middleware('CheckCms');
});

//收藏favorite
Route::group('favorite', function () {
    Route::post('/get_one_fav', 'user.UserFavorites/scFavGood'); //查询商品是否被某用户收藏,参数type=shop为查询收藏商铺
    Route::get('/get_all_fav', 'user.UserFavorites/getFavorite');   //查询某用户收藏的所有商品与商铺
    Route::post('/add_fav', 'user.UserFavorites/addFavorite');  //添加收藏商品或店铺，fav_id,type,price,img_id}
    Route::put('/del_fav', 'user.UserFavorites/deleteFavorite');  //删除收藏，参数为id
});

//评价Rate
Route::group('rate', function () {
    //公共
    Route::group('', function () {
        Route::get('goods_rate', 'user.UserRate/getGoodsRate');//获取某个商品的所有评价

    });

    //管理员
    Route::group('admin', function () {
        Route::put('del_rate', 'cms.RateManage/deleteRate');//删除评价
        Route::get('get_all_rate', 'cms.RateManage/getAllRate');//获取所有评价
    })->middleware('CheckCms');
});











//运费模板
Route::group('delivery', function () {
    //公共
    Route::group('', function () {
        Route::get('get_region', 'cms.Delivery/getRegion');//获取所有地区
    });
    //手机管理员
    Route::group('mcms', function () {
        Route::get('get_delivery', 'cms.Delivery/selectDelivery');//获取所有模板
    })->middleware('CheckMCMS');


});

//用户地址Address
Route::group('address', function () {
    Route::post('add_address', 'user.UserAddress/addAddress');//添加地址
    Route::post('edit_address', 'user.UserAddress/editAddress');//修改地址
    Route::put('del_address', 'user.UserAddress/deleteAddress');//删除地址
    Route::get('get_all_address', 'user.UserAddress/getAllAddress');//获取用户所有的地址
    Route::get('get_one_address', 'user.UserAddress/getOneAddress');//获取用户某个地址详情
    Route::get('get_default_address', 'user.UserAddress/getAddressDefault');//获取默认地址
    Route::post('set_default_address', 'user.UserAddress/setUserAddressDefault');//设置默认地址
});

//搜索
Route::group('search', function () {

    Route::group('', function () {
        Route::get('record', 'common.Search/getSearchRecord');//搜索记录

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_record', 'common.Search/addSearchGoods');//新增
        Route::put('del_record', 'common.Search/deleteSearchGoods');//删除
    })->middleware('CheckCms');
});

//订单
Route::group('order', function () {

    Route::group('', function () {
        Route::post('/create', 'common.Order/CreateXcxOrder'); //小程序商品下单
        Route::post('/create_cart', 'common.Order/CreateCartOrder');//公众号下单

        Route::post('/second_pay', 'common.Pay/gzhPaySecond');   //公众号第二次支付
        Route::post('/back', 'common.Pay/gzh_back'); //公众号支付回调

        Route::post('/pay/pre_order', 'common.Pay/getPreOrder');//小程序支付
        Route::post('/pay/notify', 'common.Pay/receiveNotify');//小程序支付回调

        Route::post('/pay/pre_app', 'common.Pay/getAppPayData');//app支付
        Route::post('/pay/app_notify', 'common.Pay/appNotify');//app支付回调
        Route::get('ali_order', 'common.ZfbPay/aliorder');//支付宝订单支付
        Route::post('alinotify_order', 'common.ZfbPay/ordernotify');//支付宝订单支付回调

        Route::post('get_kd', 'user.UserOrder/getCourier');//快递查询
        Route::get('pay_test', 'common.Pay/back_test');//支付回调测试


        Route::get('test_order', 'common.ZfbPay/get_order');//测试
    });

    Route::group('user', function () {
        Route::post('/all_order', 'user.UserOrder/getUserOrderAll'); //获取我的所有订单信息
        Route::post('/order_date', 'user.UserOrder/getOrderDate'); //统计订单数据
        Route::get('/get_order_one', 'user.UserOrder/getUserOrderOne'); //获取用户指定的一条订单信息
        Route::put('/del_order', 'user.UserOrder/deleteOrder'); //删除一条自己未支付的订单
        Route::post('/search', 'user.UserOrder/SearchOrder'); //搜索订单
        Route::post('/set_pj', 'user.UserOrder/set_pj'); //提交评价
        Route::post('/tui_kuan', 'user.UserOrder/tuikuan_approve'); //提交退款申请
        Route::post('/receive', 'user.UserOrder/receive'); //确认收货
    });


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
});

//商品
Route::group('product', function () {
    //用户
    Route::group('', function () {
        Route::get('get_product', 'common.Product/getProduct');//获取某商品详情
        Route::get('get_recent', 'common.Product/getRecent');//获取最新商品
        Route::get('get_shop_product', 'common.Product/getShopProduct');//获取某商家所有商品
        Route::get('get_cate_pros', 'common.Product/getCatePros');//获取某分类下所有商品
        Route::get('search', 'common.Search/searchGoods');//搜索商品
        Route::get('get_evaluate', 'common.Product/getEvaluate');//获取某个商品的所有评价
        Route::post('get_shipment_price', 'shops.Delivery/getShipmentPrice');//获取商品需要的运费
    });


    //采集商品
    Route::group('copy', function () {
        Route::post('get_info', 'cms.ProductManage/getCopyProductInfo');//采集商品
    });

    //管理员
    Route::group('admin', function () {
        Route::put('del_product', 'cms.ProductManage/deleteProduct');//删除商品
        Route::post('set_sort', 'cms.ProductManage/setSort');//商品排序
        Route::post('get_product', 'cms.ProductManage/getProduct');//获取所有上架商品，包含分页
        Route::post('get_products_down', 'cms.ProductManage/getProductsDown');//获取所有下架商品，包含分页
        Route::post('all_goods_info', 'cms.ProductManage/all_goods_info');//获取所有商品简略信息
        Route::get('all_goods_name', 'cms.ProductManage/allGoodsName');//获取所有商品id+名称
    })->middleware('CheckCms');
});

//用户
Route::group('user', function () {

    Route::group('', function () {
        Route::put('/login', 'user.User/userLogin'); //模拟用户登录
        Route::get('/info', 'user.User/getInfo'); //获取用户基础信息

        Route::get('/get_cpy', 'user.User/getCpy'); //获取用户发票信息
        Route::post('/edit_cpy', 'user.User/editCpy'); //修改用户发票信息

        Route::post('/get_xcx_code', 'user.User/getXcxCode'); //获取用户小程序码
        Route::post('/get_web_code', 'user.User/getWebCode'); //获取用户二维码
        Route::post('/get_all_code', 'user.User/getAllCode'); //获取3端码

    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_all_user', 'cms.UserManage/getAllUser');//获取所有用户信息
    })->middleware('CheckCms');
});

//统计
Route::group('statistic', function () {
    Route::group('', function () {

    });
    //用户
    Route::group('user', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_index_data', 'cms.Statistic/getCmsIndexData');//获取首页数据
        Route::post('get_table', 'cms.Statistic/getTableData');//获取首页图表数据
        Route::post('get_money', 'cms.Statistic/getMoneyData');//cms订单统计销售额
        Route::post('get_order', 'cms.Statistic/getOrderData');//cms统计订单数据
        Route::get('remind', 'cms.Statistic/remind');//获取首页图表数据
    })->middleware('CheckCms');
});


//cms管理员
Route::group('cms', function () {

    Route::group('', function () {
        Route::post('/get_config', 'cms.System/GetConfig');   //获取商城配置信息
        Route::post('/edit_config', 'cms.System/edit_config');  //修改配置信息
        Route::post('/edit_template', 'cms.System/editTemplate');  //修改配置信息
        Route::put('/update', 'cms.Common/upValue');   //更新某模型下的某布尔字段,如上下级架
    });

    Route::group('admin', function () {
        Route::post('edit_psw', 'cms.Admin/editPSW');//管理员修改密码
        Route::post('edit_admin', 'cms.AdminManage/editAdmin');//更新管理员
        Route::post('add_admin', 'cms.AdminManage/addAdmin');//添加管理员
        Route::get('get_all_admin', 'cms.AdminManage/getAdminAll');//获取所有管理员
        Route::put('del_admin', 'cms.AdminManage/deleteAdmin');//删除管理员
        Route::post('set_web_auth', 'cms.AdminManage/setWebAuth');//设置前端管理员
    });



})->middleware('CheckCms');



