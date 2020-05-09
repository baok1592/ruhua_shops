<template>
	<div class="root">
		<div class="top-title">财务管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose">
				<el-menu-item index="1" @click="jump_to_a">
					<span slot="title"> 退款</span>
				</el-menu-item>
				
				<el-menu-item index="2" @click="jump_to_b">
					<span slot="title">商家提现</span>
				</el-menu-item>
				<el-menu-item index="3" @click="jump_to_c">
					<span slot="title"> 个人提现</span>
				</el-menu-item>
				
				<el-menu-item index="5" @click="jump_to">
					<span slot="title"> 下载对账单</span>
				</el-menu-item>
			
		</el-menu>
	</div>
</template>

<script>
	export default{
		data(){
			return{
				act:''
			}
		},
		props:{
			ac:String
		},
		mounted() {
			this.act=this.ac //localStorage.getItem("act");
		},
		methods:{
			jump_to_a(){
				this.$router.push({
					path:'/finance/refund'
				})
			},
			jump_to_b(){
				this.$router.push({
					path:'/finance/shop_take_money'
				})
			},
			jump_to_c(){
				this.$router.push({
					path:'/finance/user_take_money'
				})
			},
			jump_to_d(){
				this.$router.push({
					path:'/finance/integral'
				})
			},
			jump_to(){
				this.$router.push({
					path:'/finance/order_download'
				})
			},
			handleOpen(key, keyPath) {
				console.log(key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			},
			handleselect(key,keyPath){
				this.act=key
				localStorage.setItem("act",key);
			},
			
			
		}
	}
</script>

<style>
</style>
