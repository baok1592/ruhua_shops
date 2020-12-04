<template>
	<div class="user_level">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>用户</el-breadcrumb-item>
						<el-breadcrumb-item>用户等级</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
					<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<!-- <el-table-column prop="id" label="ID"></el-table-column> -->
									<el-table-column prop="nickname" label="昵称"></el-table-column>
									<el-table-column prop="web_auth_id" label="等级">
										 
									</el-table-column>
									<el-table-column prop="points" label="积分"></el-table-column>
									<el-table-column prop="vip" label="是否会员">
										<template slot-scope="scope">
											{{scope.row.vip == 1?'是':'否'}}
										</template>
									</el-table-column>
									
									<el-table-column prop="create_time" label="创建时间"></el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										 
									</el-table-column> 
								</el-table>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from './components/user_nav.vue'
	import UserModel from "@/api/user"
	export default{
		components:{
			Nav
		},
		data(){
			return{
				list: [],
			} 
		},
		beforeCreate(){
			UserModel.getAll().then(res => { 
				this.all = res.data
				this.total = res.data.length
				this.list = res.data.slice(0, this.size)
			})
		}
	}
</script>

<style lang="less">

</style>
