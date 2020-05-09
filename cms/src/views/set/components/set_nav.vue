<template>
	<div class="shop_nav">
		<div class="top-title">设置管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose">
			<el-menu-item index="3" @click="jump_to_admin">
				<span slot="title">管理员</span>
			</el-menu-item>
			<el-submenu index="1">
				<template slot="title">
					<span>参数配置</span>
				</template>
				<el-menu-item index="1-1" @click="jump_to_a">
					<span slot="title">基础配置</span>
				</el-menu-item>
				<el-menu-item index="1-2" @click="jump_to_b">
					<span slot="title">微信配置</span>
				</el-menu-item>
				<el-menu-item index="1-3" @click="jump_to_c">
					<span slot="title"> 其他功能</span>
				</el-menu-item>
			</el-submenu>
			<el-submenu index="2">
				<template slot="title">
					<span>更新&备份</span>
				</template>
				<el-menu-item index="2-1" @click="jump_backup">
					<span slot="title">备份</span>
				</el-menu-item>
				<el-menu-item index="2-2" @click="jump_cehck_version">
					<span slot="title">检测版本</span>
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
			jump_to_admin(){
				this.$router.push({
					path:'/set/admin'
				})
			},
			jump_to_a(){
				this.$router.push({
					path:'/set/basic_config'
				})
			},
			jump_to_b(){
				this.$router.push({
					path:'/set/pay_config'
				})
			},
			jump_to_c(){
				this.$router.push({
					path:'/set/other_config'
				})
			},
			jump_backup(){
				this.$router.push({
					path:'/set/backup'
				})
			},
			jump_cehck_version(){
				this.$router.push({
					path:'/set/cehck_version'
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
