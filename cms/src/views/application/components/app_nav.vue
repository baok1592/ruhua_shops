<template>
	<div class="shop_nav">
		<div class="top-title">设置管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose">
			
			<el-submenu index="1">
				<template slot="title">
					<span>基础应用</span>
				</template>
				<el-menu-item index="1-1" @click="jump_to_a">
					<span slot="title">导航设置</span>
				</el-menu-item>
				<el-menu-item index="1-2" @click="jump_to_b">
					<span slot="title">文章管理</span>
				</el-menu-item>
				<el-menu-item index="1-3" @click="jump_to_c">
					<span slot="title"> 广告管理</span>
				</el-menu-item>
				<el-menu-item index="1-4" @click="jump_to_d">
					<span slot="title"> 搜索管理</span>
				</el-menu-item>
			</el-submenu>
			<el-menu-item index="2" @click="jump_to_application">
				<span slot="title">营销应用</span>
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
			jump_to_application(){
				this.$router.push({
					path:'/application'
				})
			},
			jump_to_a(){
				this.$router.push({
					path:'/app_nav'
				})
			},
			jump_to_b(){
				this.$router.push({
					path:'/app_article'
				})
			},
			jump_to_c(){
				this.$router.push({
					path:'/app_ad'
				})
			},
			jump_to_d(){
				this.$router.push({
					path:'/app_search'
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
