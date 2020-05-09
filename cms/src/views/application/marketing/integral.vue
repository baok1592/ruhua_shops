<template>
	<div class="integral_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>应用</el-breadcrumb-item>
						<el-breadcrumb-item>营销应用</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-tabs v-model="activeName" type="card" @tab-click="handleClick" style="background-color: #FFFFFF;">

							<el-tab-pane label="积分记录" name="first">
								<el-table :data="list" border>
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="userid" label="用户ID" ></el-table-column>
									<el-table-column prop="name" label="用户名" ></el-table-column>
									<el-table-column prop="jifen" label="积分" ></el-table-column>
									<el-table-column prop="type" label="方式" ></el-table-column>
									<el-table-column prop="time" label="时间" ></el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="积分设置" name="second">
								<el-form ref="form" label-width="100px">
									<el-form-item  style='width: 70%' label="签到获得积分">
										<template>
											<el-input v-model="form.name"></el-input>
										</template>
									</el-form-item>
									<el-form-item  style='width: 70%' label="购买获得积分">
										<template>
											<el-input v-model="form.name"></el-input>
										</template>
									</el-form-item>
									<el-form-item  style='width: 70%' label="邀请获得积分">
										<template>
											<el-input v-model="form.name"></el-input>
										</template>
									</el-form-item>
									<el-form-item  style='width: 70%' label="推荐获得积分">
										<template>
											<el-input v-model="form.name"></el-input>
										</template>
									</el-form-item>
									<el-form-item  style='width: 70%' label="积分比例">
										<template>
											<el-input v-model="form.name"></el-input>
										</template>
									</el-form-item>
									<el-form-item style='width: 80%'>
										<el-button type="primary" size="medium" @click="onSubmit">提交修改</el-button>

									</el-form-item>
								</el-form>
							</el-tab-pane>
						</el-tabs>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from '../components/app_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				activeName: 'first',
				form: {
					name: '',
				},
				
				list:[
					{'userid':'mess','name':'王华','jifen':'+10','type':'签到','time':'2020-9-1 12:30:30'},
					{'userid':'mess','name':'二话','jifen':'+30','type':'购买','time':'2020-9-2 15:30:30'}
				],
			}
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
			},
			onSubmit() {
				this.http.post_show('admin/add_goods', this.form_pro).then(() => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					});
					this.form = {}
					this.upfile_banner_list = []
					this.form_pro = {
							goods_name: '',
							content: '',
							img_id: [],
							stock: '',
							points: ''
						},
						this.dialogVisibleadd = false
					this.get_reseller()
				})

			},
			
			get_reseller() {
				//获取商品列表
				// this.http.get('fx/admin/get_goods?type=1').then(res => {
				// 	this.list = res.data
				// })
				//获取佣金记录
				// this.http.get('fx/admin/get_record').then(res => {
				// 	this.tableData = res.data
				// })
			},
			
		},
		mounted() {
			this.get_reseller();
		}
	}
</script>

<style lang="less">

</style>
