<template>
	<div class="root">
		<div class="top-title">统计管理</div>
		<el-menu
			:default-active="act"
			class="el-menu-vertical-demo"
			@open="handleOpen"
			@close="handleClose">
			<el-submenu index="1">
				<template slot="title">
					<span>排名</span>
				</template>
				
				<el-menu-item index="1-2" @click="jump_to_b">
					<span slot="title">店铺排名</span>
				</el-menu-item>
				
				<el-menu-item index="1-4" @click="jump_to">
					<span slot="title"> 用户排名</span>
				</el-menu-item>
				<el-menu-item index="1-3" @click="jump_to_c">
					<span slot="title"> 商品销量排名</span>
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
				
			},
			jump_to_b(){
				this.$router.push({
					path:'/statistics/shop_ranking'
				})
			},
			jump_to_c(){
				this.$router.push({
					path:'/statistics/pro_ranking'
				})
			},
			jump_to(){
				this.$router.push({
					path:'/statistics/user_ranking'
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
