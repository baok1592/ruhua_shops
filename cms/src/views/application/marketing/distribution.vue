<template>
	<div class="order_export">
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

							<el-tab-pane label="商品销量排行" name="first">
								<el-table :data="list" border>
									<el-table-column type="index" label="排名" width="50px"></el-table-column>
									<el-table-column prop="goods_info.goods_name" label="商品名称" width="400"></el-table-column>
									<el-table-column prop="goods_info.imgs" label="图片" width="110">
										<template slot-scope="scope">
											<template v-if="scope.row.goods_info">
												<img :src="$getimg + scope.row.goods_info.imgs" />
											</template>
										</template>
									</el-table-column>
									<el-table-column prop="goods_info.price" label="商品价格" width="100"></el-table-column>
									<el-table-column prop="goods_info.sale" label="销量" width="100"></el-table-column>
									<el-table-column prop="price" label="分销佣金" width="150"></el-table-column>
									<el-table-column prop="operation" label="操作">
										<template slot-scope="scope">
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">禁用</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="分销商排行" name="second">
								<el-table :data="list" border>
									<el-table-column type="index" label="排名" width="50px"></el-table-column>
									<el-table-column prop="goods_info.goods_name" label="姓名" ></el-table-column>
									<el-table-column prop="tell" label="电话" ></el-table-column>
									<el-table-column prop="goods_info.sale" label="销量" ></el-table-column>
									<el-table-column prop="zongyj" label="总佣金" ></el-table-column>
									<el-table-column prop="weitx" label="未提现佣金" ></el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="佣金记录" name="three">
								<el-table :data="tableData" stripe style="width: 100%" border="">
									<el-table-column type="index" label="序号" width="80">
									</el-table-column>
									<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
									<el-table-column prop="user_id" label="用户ID"></el-table-column>
									<el-table-column prop="agent_id" label="分销商ID"></el-table-column>
									<el-table-column prop="money" label="佣金"></el-table-column>
									<el-table-column prop="create_time" label="日期"></el-table-column>
									<el-table-column prop="status" label="申请提现">
										<template slot-scope="scope">
											<span v-if="scope.row.status == 0">未申请</span>
											<span v-if="scope.row.status == 1">申请中</span>
											<span v-if="scope.row.status == 2">已完成</span>
										</template>
									</el-table-column>
									<el-table-column label="提现状态">
										<template slot-scope="scope">
											<span v-if="scope.row.cpy_pay_status == 0">未打款</span>
											<span v-if="scope.row.cpy_pay_status == 1">打款成功</span>
											<span v-if="scope.row.cpy_pay_status == 2">打款失败</span>
											<span v-if="scope.row.cpy_pay_status == 3">当天未打款</span>
										</template>
									</el-table-column>
								</el-table>
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
				tableData: [ 
					{'order_num':'23451234','user_id':'tale','agent_id':'12312','money':'99','create_time':'2020-5-2','status':'0','cpy_pay_status':'0'},
					{'order_num':'23451234','user_id':'tale','agent_id':'12312','money':'99','create_time':'2020-5-2','status':'1','cpy_pay_status':'1'},
					{'order_num':'23451234','user_id':'tale','agent_id':'12312','money':'99','create_time':'2020-5-2','status':'2','cpy_pay_status':'2'},
					{'order_num':'23451234','user_id':'tale','agent_id':'12312','money':'99','create_time':'2020-5-2','status':'2','cpy_pay_status':'3'}
				],
				activeName: 'first',
				fenxiap:[],
				list: [
					{'price':'20.00','goods_info':{'goods_name':'织锦床单','imgs':'','price':'2.00','sale':'10'},'zongyj':'299','weitx':'99','tell':'13222222223'},
					{'price':'2000.00','zongyj':'299','weitx':'99','tell':'13222222222',
					'goods_info':{'goods_name':'Huawei/华为Mate 30 Pro超级快充麒麟990徕卡四摄4G智能手机mate30pro官方旗舰店','imgs':'','price':'2999.00','sale':'10'},},
				],
				
			}
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
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
			//删除商品
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('fx/admin/del_goods', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_reseller()
						// that.list.splice(index, 1);
					});
				})
			},
			
		},
		mounted() {
			this.get_reseller();
		}
	}
</script>

<style lang="less">

</style>
