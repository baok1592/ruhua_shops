<template>
	<div class="shop_nav">
		<div class="top-title">商家管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose" 
			>
			<el-submenu index="1">
				<template slot="title">
					<span>商家</span>
				</template>
				<el-menu-item index="1-1" @click="jump_to_a">
					<span slot="title">商家列表</span>
				</el-menu-item>
				<el-menu-item index="1-2" @click="jump_to_assistant">
					<span slot="title"> 店员管理</span>
				</el-menu-item>
				
			</el-submenu>
			<el-submenu index="2">
				<template slot="title">
					<span>商品</span>
				</template>
				<el-menu-item index="2-1" @click="jump_pro_list">
					<span slot="title">商品列表</span>
				</el-menu-item>
				<el-menu-item index="2-2" @click="jump_pro_category">
					<span slot="title">商品分类</span>
				</el-menu-item>
			</el-submenu>
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
					path:'/shops/shop_list'
				})
			},
			jump_to_b(){
				this.$router.push({
					path:'/shops/shop_approve'
				})
			},
			jump_to_assistant(){
				this.$router.push({
					path:'/shops/assistant_list'
				})
			},
			jump_pro_list(){
				this.$router.push({
					path:'/shops/pro_list'
				})
			},
			jump_pro_category(){
				this.$router.push({
					path:'/shops/pro_category'
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
