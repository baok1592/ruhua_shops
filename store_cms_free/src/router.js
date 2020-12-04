import Vue from 'vue'
import Router from 'vue-router' 

Vue.use(Router)

export default new Router({
	routes: [
		{  
			path: '/',
			name: 'index',
			component: () => import('./views/index/index.vue')
		},
		{  
			path: '/b',
			name: 'b',
			component: () => import('./views/b.vue')
		},
		{
			path: '/shops/shop_list',
			name: 'A',
			component: () => import('./views/shops/shop/shop_list.vue')
		},
		{
			path: '/shops/shop_list_add',
			name: 'shop_list_add',
			component: () => import('./views/shops/shop/shop_list_add.vue')
		},
		{
			path: '/shops/pro_list',
			name: 'pro_list',
			component: () => import('./views/shops/pro/pro_list.vue')
		},
		{
			path: '/shops/pro_category',
			name: 'pro_category',
			component: () => import('./views/shops/pro/pro_category.vue')
		},
		{
			path: '/shops/assistant_add',
			name: 'assistant_add',
			component: () => import('./views/shops/shop/assistant_add.vue')
		},
		{
			path: '/shops/assistant_list',
			name: 'assistant_list',
			component: () => import('./views/shops/shop/assistant_list.vue')
		},
		{
			path: '/user/user_list',
			name: 'user_list',
			component: () => import('./views/user/user_list.vue')
		},
		{
			path: '/user/user_evaluate',
			name: 'user_evaluate',
			component: () => import('./views/user/user_evaluate.vue')
		},
		{
			path: '/order/order_list',
			name: 'order_list',
			component: () => import('./views/order/order_list.vue')
		},
		{
			path: '/order/order_protection',
			name: 'order_protection',
			component: () => import('./views/order/order_protection.vue')
		},
		{
			path: '/statistics/shop_ranking',
			name: 'shop_ranking',
			component: () => import('./views/statistics/shop_ranking.vue')
		},
		{
			path: '/statistics/pro_ranking',
			name: 'pro_ranking',
			component: () => import('./views/statistics/pro_ranking.vue')
		},
		{
			path: '/statistics/user_ranking',
			name: 'user_ranking',
			component: () => import('./views/statistics/user_ranking.vue')
		},
		{
			path: '/finance/refund',
			name: 'refund',
			component: () => import('./views/finance/refund.vue')
		},
		{
			path: '/finance/shop_take_money',
			name: 'shop_take_money',
			component: () => import('./views/finance/shop_take_money.vue')
		},
		{
			path: '/finance/user_take_money',
			name: 'user_take_money',
			component: () => import('./views/finance/user_take_money.vue')
		},
		
		{
			path: '/finance/order_download',
			name: 'order_download',
			component: () => import('./views/finance/order_download.vue')
		},
		
		{
			path: '/app_ad',
			name: 'app_ad',
			component: () => import('./views/application/app_ad.vue')
		},
		{
			path: '/app_article',
			name: 'app_article',
			component: () => import('./views/application/app_article.vue')
		},
		{
			path: '/app_search',
			name: 'app_search',
			component: () => import('./views/application/app_search.vue')
		},
		{
			path: '/app_nav',
			name: 'app_nav',
			component: () => import('./views/application/app_nav.vue')
		},
		{
			path: '/set/admin',
			name: 'admin',
			component: () => import('./views/set/admin.vue')
		},
		{
			path: '/set/basic_config',
			name: 'basic_config',
			component: () => import('./views/set/basic_config.vue')
		},
		{
			path: '/set/pay_config',
			name: 'pay_config',
			component: () => import('./views/set/pay_config.vue')
		},
		{
			path: '/set/other_config',
			name: 'other_config',
			component: () => import('./views/set/other_config.vue')
		},
		{
			path: '/login',
			name: 'Login',
			component: () => import('./views/login/login.vue')
		},		 
		{
			path: '/lout',
			name: 'lout',
			component: () => import('./views/login/lout.vue')
		},
		{
			path: '/b',
			name: 'b',
			component: () => import('./views/b.vue')
		},
		{
			path: '/order/order/print',
			name: 'print',
			component: () => import('./views/order/print.vue')
		},
		{
			path: '/application/estimate',
			name: 'estimate',
			component: () => import('./views/application/estimate.vue')
		},
		{
			path: '/application/apply',
			name: 'apply',
			component: () => import('./views/application/apply.vue')
		}
	]
})
