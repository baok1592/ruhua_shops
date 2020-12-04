<template>
	<div class="order_download">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="5"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>财务</el-breadcrumb-item>
						<el-breadcrumb-item>下载对账单</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<Zhishi :type="type" :list="list"></Zhishi>
						<div class="H10"></div>
						<div class="tian">
							<div class="tian-l">账单类型:</div>
							<div class="tian-r">
								<el-radio v-model="radio" label="1">所有账单</el-radio>
								<el-radio v-model="radio" label="2">支付账单</el-radio>
								<el-radio v-model="radio" label="3">退款账单</el-radio>
							</div>
						</div>
						<div class="tian">
							<div class="tian-l">数据类型:</div>
							<div class="tian-r">
								<el-radio v-model="num" label="1">所有账单</el-radio>
								<el-radio v-model="num" label="2">仅商城账单</el-radio>
							</div>
						</div>
						<div class="tian">
							<div class="tian-l">账单时间:</div>
							<div class="tian-r">
								<el-date-picker
									v-model="value1"
									type="daterange"
									range-separator="至"
									start-placeholder="开始日期"
									end-placeholder="结束日期">
								</el-date-picker>

							</div>
						</div>
						<div class="btn">
							<el-button size="medium" type="primary">下载对账单</el-button>
						</div>
					</div>
				</template>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Zhishi from '@/components/zhishi.vue'
	import Nav from './components/finance_nav.vue'
	export default {
		components: {
			Nav,
			Zhishi
		},
		data() {
			return {
				list:[1,2,3],
				type:"default",
				radio: '1',
				num:'1',
				value1: '',
				value2: ''
			}
		}
	}
</script>

<style lang="less">
	.nav_head {
		height: 70px;
	}
	
	.tian{display: flex;padding:40px 10px 0;text-align: left;
		.tian-l{width: 100px;text-align: right;padding-right: 20px;display: flex;flex-direction: column;justify-content: center;}
	}
	.btn{text-align: left;padding:50px 0 10px 130px;}
	
</style>
