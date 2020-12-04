<template>
	<div class="integral">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="4"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>财务</el-breadcrumb-item>
						<el-breadcrumb-item>积分</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from './components/finance_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				
			}
		}
	}
</script>

<style lang="less">

</style>
