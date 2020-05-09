<template>
	<div class="shop_list">
		<el-container class="container">
			<el-aside width="160px" class="not-print bg-white erbian">
				<Nav ac="1-3"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>商家</el-breadcrumb-item>
						<el-breadcrumb-item>店员列表</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang"> 
						<div style="padding-bottom:20px;">
							<el-row>
								<el-col :span="1">
									<el-button type="primary" size="small" @click="assistant_add">新增</el-button>
								</el-col>
							</el-row>
						</div>
						<transition appear appear-active-class="animated fadeInLeft">
							<el-table :data="list" border style="width: 100%">
								<el-table-column type="index" label="序号" width="70"></el-table-column>
								<el-table-column prop="username" label="店员姓名"></el-table-column>
								<el-table-column prop="mobile" label="联系电话" ></el-table-column>
								<el-table-column label="所属店铺" width="180">
									<template slot-scope="scope"> 
										{{scope.row.shop_name.shop_name}}
									</template>
								</el-table-column> 
								<el-table-column prop="login_time" label="登录日期" ></el-table-column>
								<el-table-column label="状态" >
									<template slot-scope="scope">
										{{scope.row.state == 1?'正常':'已禁用'}}
									</template>
								</el-table-column> 
							</el-table>
						</transition>
						<div class="H20"></div>
						<el-pagination background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page"></el-pagination> 
					</div>
				</template>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import StoreModel from '@/api/store'
	import City from '@/common/city'
	import Nav from '../components/shop_nav.vue'
	export default {
		components: {
			Nav
		},
		data() {
			return {
				imgurl: this.$getimg,
				list: "",
				all: "",
				size: 10,
				total: 0,
				keyword: "",
			}
		},
		mounted() {
			this.get_all_assistant()
		},
		methods: {
			//转换时间
			time(timestamp){
				var date = new Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
				var Y = date.getFullYear() + '-';
				var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
				var D = date.getDate() + ' ';
				var h = date.getHours() + ':';
				var m = date.getMinutes() + ':';
				var s = date.getSeconds();
				return Y + M + D+ ' '+h+m+s
			},
			get_all_assistant(){
				this.http.post('shops/cms/assistant_all').then(res=>{
					console.log(res.data)
					for(let k in res.data){
						let v = res.data[k]
						v.login_time = this.time(v.login_time)
					}
					this.list = res.data
				})
			},
			handleChange(value) {
				console.log(value[1])
				this.list = []
				for (let k in this.all) {
					let v = this.all[k]
					if (v.region.region_name == value[1]) {
						this.list.push(v)
					}
				}
				console.log(this.list)
			},
			//新增
			assistant_add() {
				this.$router.push({
					path: '/shops/assistant_add'
				})
			},

			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				this.list = this.all.slice(start, end);
			},
			on_edit(id) {
				this.$emit("cmp_edit", id);
			}
		},
	}
</script>

<style lang="less">
	.H20 {
		height: 20px;
	}

	.zs-img {
		height: 70px;
		width: 70px;
		margin: 0 5px;
	}
</style>
