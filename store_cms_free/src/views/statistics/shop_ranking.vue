<template>
	<div class="shop_ranking">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>统计</el-breadcrumb-item>
						<el-breadcrumb-item>店铺排名</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-table :data="tableData" border style="width: 100%;">
							<el-table-column type="index" label="排名" width="50">
							</el-table-column>
							<el-table-column prop="user_mobile" label="联系方式" width="150">
							</el-table-column>
							<el-table-column prop="shop_name" label="店铺名称" width="220">
							</el-table-column>
							<el-table-column prop="shop_address" label="店铺地址" width="420">
							</el-table-column>
							<el-table-column label="总订单数" width="150" prop="price"></el-table-column>
							<el-table-column label="总成交额" width="150" prop="price"></el-table-column>
							<el-table-column label="注册日期" prop="date"></el-table-column>
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
					{'shop_address':'贵州省黔西南州兴义市桔山大道','shop_name':'山花烂漫','user_mobile':'13233333333','price':'32','date':'2020-9-1','status':0},
					{'shop_address':'贵州省黔西南州兴义市兴义大道','shop_name':'净值由我','user_mobile':'13233333332','price':'99','date':'2020-9-2','status':1},
					{'shop_address':'贵州省黔西南州兴义市桔山大道','shop_name':'山花烂漫','user_mobile':'13233333333','price':'32','date':'2020-9-1','status':-1},
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
