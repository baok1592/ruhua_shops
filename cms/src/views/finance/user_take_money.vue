<template>
	<div class="user_take_money">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="3"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>财务</el-breadcrumb-item>
						<el-breadcrumb-item>个人提现</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-table :data="tableData" border style="width: 100%;">
							<el-table-column type="index" label="序号" width="50">
							</el-table-column>
							<el-table-column prop="shop_name" label="用户名" width="220">
							</el-table-column>
							<el-table-column prop="user_mobile" label="电话" width="150">
							</el-table-column> 
							<el-table-column label="提现金额" width="150" prop="price"></el-table-column>
							<el-table-column label="日期" prop="date"></el-table-column>
							<el-table-column prop="status" label="状态">
								<template slot-scope="scope">
									<template v-if="scope.row.status == 0">待审核</template>
									<template v-if="scope.row.status == -1">已驳回</template>
									<template v-if="scope.row.status == 1">提现成功</template>
								</template>
							</el-table-column>
							<el-table-column prop="operation" label="操作">
								<template slot-scope="scope" v-if="scope.row.status != 1">
									<el-button type="success" size="small" @click="agree(scope.row.id)">同意</el-button>
									<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="refuse(scope.row.id)">驳回</el-button>
								</template>
							</el-table-column>
						</el-table>
					</div>
					<el-dialog title="驳回原因" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
						<el-input v-model="msg"></el-input>
						<span slot="footer" class="dialog-footer">
							<el-button @click="cancel">取 消</el-button>
							<el-button type="primary" @click="sub_refuse">确 定</el-button>
						</span>
					</el-dialog>
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
				dialogVisible: false,
				msg: '',
				id: '',
				num: '',
				tableData: [
					{'shop_address':'贵州省黔西南州兴义市桔山大道','shop_name':'山花烂漫','user_mobile':'13233333333','price':'32','date':'2020-9-1','status':0},
					{'shop_address':'贵州省黔西南州兴义市兴义大道','shop_name':'净值由我','user_mobile':'13233333332','price':'99','date':'2020-9-2','status':-1},
					{'shop_address':'贵州省黔西南州兴义市兴义大道桔山广场','shop_name':'孙成','user_mobile':'13233333222','price':'23','date':'2020-9-3','status':1}
				],
			}
		},
		methods:{
			agree(id) {
				this.$confirm('确定同意该退款', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('order/admin/tui_money', {
						id: id
					}).then(res => {
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
				})
			},
			refuse(id) {
				this.dialogVisible = true
				this.rid = id
			},
			sub_refuse() {
				let refuse_data = {
					id: this.rid,
					msg: this.msg
				}
				if (!refuse_data.msg) {
					this.$message({
						message: '未填写驳回原因',
						type: 'warning'
					});
					return
				}
				this.http.post_show('order/admin/tui_bohui', refuse_data).then(res => {
					this.$message({
						message: '驳回成功',
						type: 'success'
					});
					this.msg = ''
					this.dialogVisible = false
					this._load()
				})
			},
			cancel() {
				this.dialogVisible = false
				this.msg = ''
			},
			handleClose() {
				this.dialogVisible = false
				this.msg = ''
			}
		}
	}
</script>

<style lang="less">

</style>
