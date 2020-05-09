<template>
	<div class="refund">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>财务</el-breadcrumb-item>
						<el-breadcrumb-item>退款</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<div class="article">
							<template>
								<el-tabs v-model="activeName" type="card" @tab-click="handleClick" style="background-color: #FFFFFF;">
									<el-tab-pane label="全部" name="first">
										<el-table :data="list" border style="width: 100%">
											<el-table-column type="index" label="序号" width="50px"></el-table-column>
											<el-table-column prop="order_num" label="订单号"></el-table-column>
											<el-table-column prop="nickname" label="用户昵称"></el-table-column>
											<el-table-column prop="because" label="退款原因"></el-table-column>
											<el-table-column prop="message" label="客户留言"></el-table-column>
											<el-table-column prop="ip" label="IP"></el-table-column>
											<el-table-column prop="money" label="退款金额"></el-table-column>
											<el-table-column prop="status" label="状态">
												<template slot-scope="scope">
													<template v-if="scope.row.status == 0">待审核</template>
													<template v-if="scope.row.status == -1">已驳回</template>
													<template v-if="scope.row.status == 1">退款成功</template>
												</template>
											</el-table-column>
											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope" v-if="scope.row.status != 1">
													<el-button type="success" size="small" @click="agree(scope.row.id)">同意</el-button>
													<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="refuse(scope.row.id)">驳回</el-button>
												</template>
											</el-table-column><strong></strong>
										</el-table>
									</el-tab-pane>
									<el-tab-pane label="待退款" name="second">
										<el-table :data="dai_tui" border style="width: 100%">
											<el-table-column type="index" label="序号" width="50px"></el-table-column>
											<el-table-column prop="order_num" label="订单号"></el-table-column>
											<el-table-column prop="nickname" label="用户昵称"></el-table-column>
											<el-table-column prop="because" label="退款原因"></el-table-column>
											<el-table-column prop="message" label="客户留言"></el-table-column>
											<el-table-column prop="ip" label="IP"></el-table-column>
											<el-table-column prop="money" label="退款金额"></el-table-column>
											<el-table-column prop="status" label="状态">
												<template slot-scope="scope">
													<template v-if="scope.row.status == 0">待审核</template>
													<template v-if="scope.row.status == -1">已驳回</template>
													<template v-if="scope.row.status == 1">退款成功</template>
												</template>
											</el-table-column>
											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope">
													<el-button type="success" size="small" @click="agree(scope.row.id)">同意</el-button>
													<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="refuse(scope.row.id)">驳回</el-button>
												</template>
											</el-table-column><strong></strong>
										</el-table>
									</el-tab-pane>
									<el-tab-pane label="已退款" name="three">
										<el-table :data="yi_tui" border style="width: 100%">
											<el-table-column type="index" label="序号" width="50px"></el-table-column>
											<el-table-column prop="order_num" label="订单号"></el-table-column>
											<el-table-column prop="nickname" label="用户昵称"></el-table-column>
											<el-table-column prop="because" label="退款原因"></el-table-column>
											<el-table-column prop="message" label="客户留言"></el-table-column>
											<el-table-column prop="ip" label="IP"></el-table-column>
											<el-table-column prop="money" label="退款金额"></el-table-column>
											<el-table-column prop="status" label="状态">
												<template slot-scope="scope">
													<template v-if="scope.row.status == 0">待审核</template>
													<template v-if="scope.row.status == -1">已驳回</template>
													<template v-if="scope.row.status == 1">退款成功</template>
												</template>
											</el-table-column>
											<el-table-column prop="operation" label="操作" width="300px">
												<template slot-scope="scope" v-if="scope.row.status != 1">
													<el-button type="success" size="small" @click="agree(scope.row.id)">同意</el-button>
													<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="refuse(scope.row.id)">驳回</el-button>
												</template>
											</el-table-column><strong></strong>
										</el-table>
									</el-tab-pane>
								</el-tabs>

							</template>
						</div>
					</div>
				</template> 
			</el-main>
		</el-container>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">

			<el-input v-model="msg" placeholder="请输入驳回原因"></el-input>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub_refuse">确 定</el-button>
			</span>
		</el-dialog>
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
				activeName: 'first',
				dialogVisible: false,
				list: '',
				msg: '',
				rid: '',
				dai_tui:[],
				yi_tui:[]
			}
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('order/admin/get_tui_all').then(res => {
					// this.list = res.data
					let arr = []
					let brr = []
					let crr = []
					for (let k in res.data) {
						let v = res.data[k]
						if (v.status != -1) {
							arr.push(v)
						}
						if(v.status != -1 && v.status == 1){
							brr.push(v)
						}
						if(v.status != -1 && v.status == 0){
							crr.push(v)
						}
					}
					this.list = arr
					this.yi_tui = brr
					this.dai_tui = crr
				})
			},
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
		},

	}
</script>

<style lang="less">
.article {
			line-height: 30px;
			background-color: #fff;
			text-align: left;
		}

</style>
