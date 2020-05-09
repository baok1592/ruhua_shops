<template>
	<div class="root">
		<el-menu :default-active="Act" class="el-menu-vertical-demo" @open="handleOpen"
		 @close="handleClose" background-color="#545c64"
		 text-color="#fff" active-text-color="#ffd04b" @select="handleselect">
			<el-menu-item index="111" disabled>
				<span slot="title"></span>
			</el-menu-item>
			<el-menu-item index="1" @click="jump_to_index">
				<i class="el-icon-s-home"></i>
				<span slot="title">首页</span>
			</el-menu-item>
			<el-menu-item index="2" @click="jump_to_shops">
				<i class="el-icon-s-shop"></i>
				<span slot="title">商家</span>
			</el-menu-item>
			<el-menu-item index="3" @click="jump_to_user">
				<i class="el-icon-user"></i>
				<span slot="title">用户</span>
			</el-menu-item>
			<el-menu-item index="4" @click="jump_to_order">
				<i class="el-icon-document"></i>
				<span slot="title">订单</span>
			</el-menu-item>
			<el-menu-item index="7" @click="jump_to_tongji">
				<i class="el-icon-s-marketing"></i>
				<span slot="title">统计</span>
			</el-menu-item>
			<el-menu-item index="5" @click="jump_to_caiwu">
				<i class="el-icon-s-check"></i>
				<span slot="title">财务</span>
			</el-menu-item>
			<el-menu-item index="6" @click="jump_to_app">
				<i class="el-icon-menu"></i>
				<span slot="title">应用</span>
			</el-menu-item>
			
			<el-menu-item index="8" @click="jump_to_set">
				<i class="el-icon-setting"></i>
				<span slot="title">设置</span>
			</el-menu-item>
		</el-menu>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				Act: ''
			}
		},
		mounted() {
			this.Act = localStorage.getItem("Act")
		},
		methods: {
			handleselect(key, keyPath) {
				this.Act = key
				localStorage.setItem("Act", key);
				localStorage.setItem("act", 1);
			},
			jump_to_app() {
				this.$router.push({
					path: '/app_nav'
				})
			},
			jump_to_caiwu() {
				this.$router.push({
					path: '/finance/refund'
				})
			},
			jump_to_tongji() {
				this.$router.push({
					path: '/statistics/shop_ranking'
				})
			},
			jump_to_index() {
				this.$router.push({
					path: '/'
				})
			},
			jump_to_order() {
				this.$router.push({
					path: '/order/order_list'
				})
			},
			jump_to_set() {
				this.$router.push({
					path: '/set/admin'
				})
			},
			jump_to_shops() {
				this.$router.push({
					path: '/shops/shop_list'
				})
			},
			jump_to_user() {
				this.$router.push({
					path: '/user/user_list'
				})
			},
			handleOpen(key, keyPath) {
				console.log(key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			}
		}
	}
</script>

<style>
</style>
