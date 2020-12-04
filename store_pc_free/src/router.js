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
			path: '/shops/pro_group',
			name: 'pro_group',
			component: () => import('./views/shops/pro/pro_group')
		},
		{
			path: '/order/order_list',
			name: 'order_list',
			component: () => import('./views/order/order_list.vue')
		},
		{
			path: '/order/evaluate',
			name: 'evaluate',
			component: () => import('./views/order/evaluate.vue')
		},
		{
			path: '/order/tixian',
			name: 'tixian',
			component: () => import('./views/order/tixian.vue')
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
			path: '/order/pro_ranking',
			name: 'pro_ranking',
			component: () => import('./views/order/pro_ranking.vue')
		},
		{
			path: '/user/user_list',
			name: 'user_list',
			component: () => import('./views/user/user_list.vue')
		},
		{
			path: '/user/user_level',
			name: 'user_level',
			component: () => import('./views/user/user_level.vue')
		},
		{
			path: '/user/user_evaluate',
			name: 'user_evaluate',
			component: () => import('./views/user/user_evaluate.vue')
		},
		{
			path: '/extend/addreseller',
			name: 'addreseller',
			component: () => import('./views/extend/addreseller.vue')
		},
		{
			path: '/extend/addcoupon',
			name: 'addcoupon',
			component: () => import('./views/extend/addcoupon.vue')
		},
		{
			path: '/extend/edit_pt',
			name: 'edit_pt',
			component: () => import('./views/extend/edit_pt.vue')
		},
		{
			path: '/extend/add_pt',
			name: 'add_pt',
			component: () => import('./views/extend/add_pt.vue')
		},
		{
			path: '/extend/editdiscount',
			name: 'editdiscount',
			component: () => import('./views/extend/editdiscount.vue')
		},
		{
			path: '/extend/adddiscount',
			name: 'adddiscount',
			component: () => import('./views/extend/adddiscount.vue')
		},
		{
			path: '/shops/template',
			name: 'template',
			component: () => import('./views/shops/pro/template.vue')
		},
		{
			path: '/shops/edittemplate',
			name: 'edittemplate',
			component: () => import('./views/shops/pro/edittemplate.vue')
		},
		{
			path: '/shops/addtemplate',
			name: 'addtemplate',
			component: () => import('./views/shops/pro/addtemplate.vue')
		},
		{
			path: '/order/print',
			name: 'print',
			component: () => import('./views/order/print.vue')
		}
	]
})
