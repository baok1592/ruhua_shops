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

							<el-tab-pane label="拼团商品" name="first">
								<el-table :data="pin" border>
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<el-table-column prop="userid" label="商品名称" width="500px"></el-table-column>
									<el-table-column prop="name" label="价格" ></el-table-column>
									<el-table-column prop="jifen" label="拼团价格" ></el-table-column>
									<el-table-column prop="type" label="方式" ></el-table-column>
									<el-table-column prop="time" label="时间" ></el-table-column>
									<el-table-column prop="operation" label="操作">
										<template slot-scope="scope">
											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">禁用</el-button>
										</template>
									</el-table-column>
								</el-table>
							</el-tab-pane>
							<el-tab-pane label="拼团订单" name="second">
								<div v-if="!detail_show" style="padding: 15px;background-color: #fff">
									<el-table :data="list" border style="width: 100%" @filter-change="xxx">
										<el-table-column type="index" label="序号" width="50"></el-table-column>
										<el-table-column prop="order_num" label="订单号" width="180"></el-table-column>
										<el-table-column label="商品名称" prop="goods_id" width="280" :filters="goods_list" :filter-method="filterHandler">
											<template slot-scope="scope">
												<div v-for="(item,key) of scope.row.ordergoods">{{item.goods_name | ellipsis}}</div>
											</template>
										</el-table-column>
										<el-table-column label="订单价格" width="80">
											<template slot-scope="scope">
												<template v-if="scope.row.payment_state == 1">{{scope.row.order_money}}</template>
												<el-button v-else type="text" @click="open(scope.row.order_money,scope.row.order_id)">{{scope.row.order_money}}</el-button>
											</template>
										</el-table-column>
										<el-table-column prop="message" label="客户备注" width="160">
										</el-table-column>
										<el-table-column prop="users.nickname" label="用户" width="160"></el-table-column>
										<el-table-column prop="create_time" label="创建日期" width="125"></el-table-column>
										<el-table-column label="支付状态" width="100" :filters="[{ text: '已支付', value: 1 }, { text: '未支付', value: 0 }]"
										:filter-method="filter_pay" filter-placement="bottom-end" column-key="zf">
											<template slot-scope="scope">
												<p v-if="scope.row.payment_state == 1">已支付</p>
												<p v-else style="color:Red;">未支付</p>
											</template>
										</el-table-column>
										<!-- <el-table-column label="退货状态" width="100" :filters="[{ text: '已退货', value: 1 }, { text: '未退货', value: 0 }]" :filter-method="filter_tui">
											<template slot-scope="scope">
												<p v-if="scope.row.tui_status == 1">已退货</p>
												<p v-else style="color:Red;">未退货</p>
											</template>
										</el-table-column> -->
										<el-table-column label="订单状态" width="100" :filters="[{ text: '已退款', value: -2 }, { text: '退款中', value: -1 },{ text: '未完成', value: 0 },{ text: '已完成', value: 1 },{ text: '已评价', value: 2 },]"
										filter-placement="bottom-end" :column-key="'dd'">
											<template slot-scope="scope">
												<p v-if="scope.row.state == -2">已退款</p>
												<p v-if="scope.row.state == -1">退款中</p>
												<p v-if="scope.row.state == 0">未完成</p>
												<p v-if="scope.row.state == 1">已完成</p>
												<p v-if="scope.row.state == 2">已评价</p>
												<!-- <p v-else style="color:#E6A23C;">未收货</p> -->
											</template>
										</el-table-column>

										<el-table-column label="运输状态" width="100" :filters="[{ text: '待发货', value: 0 }, { text: '已发货', value: 1 },{ text: '已收货', value: 2 }]"
										:filter-method="filter_send" filter-placement="bottom-end">
											<template slot-scope="scope">
												<p style="color:#E6A23C" v-if="scope.row.shipment_state == 0">待发货</p>
												<p style="color:#909399" v-if="scope.row.shipment_state == 1">已发货</p>
												<p style="color:#909399" v-if="scope.row.shipment_state == 2">已收货</p>
												<!-- <p style="color:#67C23A" v-else-if="scope.row.drive_status == 1 && scope.row.receive_status == 0">已发货</p>
												<p style="color:#E6A23C" v-else>未发货</p> -->
											</template>
										</el-table-column>
										<el-table-column prop="operation" label="操作" >
											<template slot-scope="scope">
												<el-button @click="show_order(scope.row.order_id)" type="primary" size="small">
													<!-- <a href="#top" style="text-decoration:none;color: #FFFFFF;">查看</a> -->
													查看
												</el-button>

												<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.order_id,scope.$index)">删除</el-button>
											</template>
										</el-table-column>
									</el-table>
									<el-pagination v-if="fy_show == 1" style="margin-top: 50px;" background layout="prev, pager, next" :total="total"
									:page-size="size" @current-change="jump_page">
									</el-pagination>
								</div>
								<div v-if="detail_show"  style="padding: 15px;background-color: #fff">
									<div class="order-back t_l" >
										<el-button type="primary" size="small" @click="back">返回</el-button>
									</div>
									<div class="H10"></div>
									<order-details :order_id="order_id"></order-details> 
								</div> 
							</el-tab-pane>
						</el-tabs>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import OrderDetails from "@/components/Details.vue";
	import Nav from '../components/app_nav.vue'
	export default{
		components:{
			Nav,
			OrderDetails
		},
		data(){
			return{
				detail_show:false,
				activeName: 'first',
				form: {
					name: '',
				},
				list:[],
				pin:[
					{'userid':'Huawei/华为Mate 30 Pro超级快充麒麟990徕卡四摄4G智能手机mate30pro官方旗舰店','name':'122','jifen':'100','type':'2人','time':'2020-9-1'},
					{'userid':'【戴尔Ins3670R3848S/R38N8S】戴尔(DELL)灵越3670英特尔酷睿i5','name':'998','jifen':'990','type':'3人','time':'2020-9-2'}
				],
				order_id:0,
				goods_list: [],
			}
		},
		methods: {
			handleClick(tab, event) {
				console.log(tab, event);
			},
			show_order(row) { 
				this.order_id = row;
				this.detail_show=true
				// console.log('roe',this.order_id)
			},
			get_reseller() {
				const that = this
				this.http.post('order/admin/get_order').then(res => {
					that.all = res.data
					that.list = res.data;
					that.list = res.data.slice(0, that.size);
					that.total = res.data.length;
					for (let v in that.all) {
						let time = that.all[v].pay_time * 1000
						this.all[v].pay_time = this.Conversiontime(time)
					}
					let arr = []
					let brr = []
					for (let k in this.all) {
						let v = this.all[k]
						if (v.users) {
							arr[v.user_id] = v.users.nickname
						}
						for (let g in v.ordergoods) {
							let h = v.ordergoods[g]
							brr[h.goods_id] = h.goods_name
							// brr.push({
							// 	value: h.goods_name,
							// 	id: h.goods_id
							// })
						}
					}
					let crr = []
					for (let k in brr) {
						crr.push({
							text: brr[k],
							value: k
						})
					}
					this.user_list = arr
					this.goods_list = crr
				})
			},
			back() {
				this.order_id = 0;
				this.detail_show=false
			},
			//删除
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('order/admin/del_order', {
						id: id
					}).then(() => {
						that.$message({
							showClose: true,
							message: '删除成功',
							type: 'success'
						});
						this.get_all_order()
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
