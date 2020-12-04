<template>
	<div class="root">
		<div class="top-title">用户管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose">
			
				
				<el-menu-item index="1" @click="jump_to_a">
					<span slot="title">用户列表</span>
				</el-menu-item>
				<!-- <el-menu-item index="2" @click="jump_to_b">
					<span slot="title">用户等级</span>
				</el-menu-item> -->
				
			
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
					path:'/user/user_list'
				})
			},
			jump_to_b(){
				this.$router.push({
					path:'/user/user_level'
				})
			},
			jump_to(){
				this.$router.push({
					path:'/user/user_evaluate'
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
