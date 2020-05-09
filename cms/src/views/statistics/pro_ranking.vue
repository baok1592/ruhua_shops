<template>
	<div class="pro_ranking">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-3"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>统计</el-breadcrumb-item>
						<el-breadcrumb-item>商品销量排名</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-table :data="tableData" border style="width: 100%;">
							<el-table-column type="index" label="排名" width="50">
							</el-table-column>
							<el-table-column prop="pro_name" label="商品名称" width="350">
							</el-table-column>
							<el-table-column prop="shop_name" label="店铺名称" width="220">
							</el-table-column>
							<el-table-column prop="shop_address" label="价格" width="120">
							</el-table-column>
							<el-table-column label="总成交量" width="150" prop="price"></el-table-column>
							<el-table-column label="总成交额" width="150" prop="price"></el-table-column>
							<el-table-column label="上架时间" prop="date"></el-table-column>
							<el-table-column prop="operation" label="操作">
								<el-button type="success" size="small" @click="agree()">查看</el-button>
							</el-table-column>
						</el-table>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from './components/statistics_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
			tableData: [
					{'shop_address':'123','shop_name':'山花烂漫','pro_name':'Huawei/华为Mate 30 Pro超级快充麒麟990徕卡四摄4G智能手机mate30pro官方旗舰店','price':'32','date':'2020-9-1','status':0},
					{'shop_address':'23','shop_name':'净值由我','pro_name':'4G智能手机mate30pro官方旗舰店','price':'99','date':'2020-9-2','status':1},
					{'shop_address':'56','shop_name':'山花烂漫','pro_name':'Huawei/华为Mate 30 Pro超级快充麒麟990徕卡四摄','price':'32','date':'2020-9-1','status':-1},
				],
			}
		},
		methods:{
			agree() {
				
					this.http.post_show('order/admin/tui_money').then(res => {
						if (res.status == 400) {
							this.$message({
								message: res.msg,
								type: 'warning'
							});
						} else {
							this.$message({
								message: '退款成功',
								type: 'success'
							});
						}
						this._load()
					})
				
			},
		}
	}
</script>

<style lang="less">

</style>
