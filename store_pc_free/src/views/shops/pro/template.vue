<template>
	<div class="root">
		<el-container>
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="3"></Nav>
			</el-aside>
			
			<el-main class="main">
				<el-breadcrumb separator-class="el-icon-arrow-right">
					<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
					<el-breadcrumb-item>商品</el-breadcrumb-item>
					<el-breadcrumb-item>运费模板</el-breadcrumb-item>
				</el-breadcrumb>
				<div class="H10"></div>
				<div class="zhang t_l">
					<el-button type="primary" size="small" @click="add">新建运费模板</el-button>
					<div style="height:20px;">&nbsp;</div>
					<template>
						<el-table :data="list" border style="width: 100%">
							<el-table-column type="index" label="序号" width="50px"></el-table-column>
							<el-table-column prop="name" label="模板名称"></el-table-column>
							<el-table-column prop="fa_address" label="发货地址"></el-table-column>
							<el-table-column prop="tag" label="模板类型"></el-table-column>
							<!-- <el-table-column prop="rule[0].first" label="首件（个）"></el-table-column>
							<el-table-column prop="rule[0].first_fee" label="运费（元）"></el-table-column>
							<el-table-column prop="goods_ids" label="续件（个）"></el-table-column>
							<el-table-column prop="full" label="续费（元）"></el-table-column> -->
							<el-table-column prop="operation" label="操作" width="300px">
								<template slot-scope="scope">
									<el-button @click="edit(scope.row.id)" type="success" size="small">修改</el-button>
									<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id)">删除</el-button>
								</template>
							</el-table-column><strong></strong>
						</el-table>
					</template>
				</div>
				<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
					@current-change="jump_page">
				</el-pagination>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from '../components/shop_nav.vue'
	export default {
		data() {
			return {
				size: 10,
				total: 0,
				list: [],
			}
		},
		components: {
			Nav
		},
		mounted() {
			this.get_template();
		},
		methods: {
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			//获取
			get_template() {
				this.http.get('delivery/delivery_list').then(res => {
					this.list = res.data
				})
			},
			//删除优惠券
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.Del('delivery/delivery_del', {
						id: id
					}).then(res => {
						if (res.status == 200) {
							that.$message({
								showClose: true,
								message: '删除成功',
								type: 'success'
							});
						} else {
							that.$message({
								showClose: true,
								message: res.msg,
								type: 'error'
							});
						}
						this.get_template()
						// that.list.splice(index, 1);
					});
				})
			},
			add() {
				this.$router.push({
					path: './addtemplate'
				})
			},
			edit(id) {
				// localStorage.setItem("edit_data", JSON.stringify(item))
				this.$router.push({
					path: './addtemplate',
					query: {
						type:'edit',
						id:id
					}
				});
			},
		}
	}
</script>

<style>
	.article {
		line-height: 30px;
		background-color: #fff;
		padding: 15px;
		text-align: left;
	}
</style>
