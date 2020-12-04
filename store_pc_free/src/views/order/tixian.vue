<template>
	<div class="tixian">
		<el-container>
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="3"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>订单</el-breadcrumb-item>
						<el-breadcrumb-item>提现</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<div class="t_l">
							<el-button @click="dialogVisible = true" type="primary" size="small">提现申请</el-button>
						</div>
						<div class="H10"></div>
						<div class="H10"></div>
						<el-table :data="tableData" stripe style="width: 100%">
							<el-table-column type="index" label="序号" width="80">
							</el-table-column>
							<el-table-column prop="name" label="姓名" width="80"></el-table-column>
							<el-table-column prop="address" label="日期"></el-table-column>
							<el-table-column prop="address" label="提现金额"></el-table-column>
							<el-table-column prop="address" label="可提金额"></el-table-column>
							<el-table-column prop="address" label="提现后剩余"></el-table-column>
							<el-table-column prop="address" label="状态"></el-table-column>
							<el-table-column prop="address" label="操作">
								<template slot-scope="scope">
									<el-button size="small" type="primary">同意</el-button>
										<el-button size="small" type="danger">驳回</el-button>
								</template>
							</el-table-column>
						</el-table>
					</div>
				</template> 
			</el-main>
			<el-dialog title="提现申请" :visible.sync="dialogVisible" width="30%" :before-close="handleClosetg">
				<div class="cybl">
					<div class="cybl-l">可提现金额</div>
					<div class="cybl-l">300</div>
				</div>
				<div class="cybl">
					<div class="cybl-l">提现金额</div>
					<el-input v-model.number="txje" placeholder="请填写" ></el-input>
				</div>
				<span slot="footer" class="dialog-footer">
					<el-button @click="dialogVisible = false">取 消</el-button>
					<el-button type="primary" @click="subtg">确 定</el-button>
				</span>
			</el-dialog>
		</el-container>

	</div>
</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from "@/common/config";

	import Nav from './components/order_nav.vue'
	export default {
		data() {
			return {
				rules: {
					tx_money: [{
							required: true,
							message: "请输入提现金额",
							trigger: "blur"
						} //blur失去焦点
					]
				},
				tableData: [],
				dialogVisible: false,
				txje:''
			}
		},
		components: {
			Nav
		},
		mounted() {
			this._load()
		},
		methods: {
			_load() {
				this.http.get('get_cash').then(res=>{
					this.list = res.data
				})
			},
			handleClosetg(){
				this.dialogVisible = false
			}
		},

	}
</script>

<style lang="less">
	.tixian {
		.cybl{display: flex;margin-bottom: 15px;
			.cybl-l{width: 100px;flex-shrink: 0;line-height: 40px;}
		}
		.el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}
	}
</style>
