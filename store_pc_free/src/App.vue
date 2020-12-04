<template>
	<div id="app">

		<el-container style="width: 100%;margin: 0;">
			<el-aside width="120px" :class="{qaside:HideLeftNav}">
				<Nav></Nav>
			</el-aside>
			<el-container>
				<el-header style="border-bottom: 1px solid #BEBEBE;" :class="{qaside:HideLeftNav}">
					<Header></Header>
				</el-header>
				<el-main style="background-color: #F5F7F9;margin: 0px;padding: 0px;">
					<router-view />
				</el-main>
			</el-container>

		</el-container>
	</div>
</template>

<script>
	import Header from "@/components/header.vue";
	import Nav from './components/nav/nav.vue'
	export default {
		components: {
			Nav,
			Header
		},   
		watch: {
			// 方法1
			'$route' (to, from) { //监听路由是否变化 
				console.log(to)
				if(to.name == "Login"){ //跳转到哪个页面 
					this.HideLeftNav=true
				}else{
					this.HideLeftNav=false
				}
			},
		},
		data() {
			return {
				HideLeftNav:false,
				list: [],
				is_nav: false //左侧导航
			}
		}
	}
</script>

<style> 
	.erbian{width: 160px;overflow: hidden !important;}
	.el-aside {
		background-color: rgb(84, 92, 100);
		min-height: calc(100vh);
	}
	.qaside{
		display:none;
	}
	#app {
		font-family: 'Avenir', Helvetica, Arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		text-align: center;
		color: #2c3e50;
	}

	#nav {

		a {
			font-weight: bold;
			color: #2c3e50;

			&.router-link-exact-active {
				color: #42b983;
			}
		}
	}
	.bg-white{background-color: #fff;}
	/* .main{background: #F5F7F9;margin: 0px;padding: 0px;} */
	.container{width: 100%;margin: 0;}
	.H10{height: 10px;}
	.top-title{line-height: 75px;height: 75px;}
	.zhang{background-color: #fff;font-size: 14px;padding:30px 25px 10px;margin: 10px 0 0;min-height: 500px;}
	.t_l{text-align: left;}
</style>
