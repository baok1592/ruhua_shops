<template>
	<div class="order_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-4"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>应用</el-breadcrumb-item>
						<el-breadcrumb-item>搜索管理</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang t_l">
						<el-button type="primary" size="small"  @click="add_hot">新增热门词</el-button>
						<div class="H10"></div>
						<div class="H10"></div>
						<el-table :data="tableData" stripe style="width: 100%">
							<el-table-column type="index" label="排序" width="80">
							</el-table-column>
							<el-table-column prop="name" label="热门词">
								<template slot-scope="scope">{{scope.row}}</template>
							</el-table-column>
							<el-table-column prop="address" label="操作">
								<template slot-scope="scope">
									<el-button type="danger" size="medium" @click="del(scope.row)">删除</el-button>
								</template>

							</el-table-column>
						</el-table>
					</div>
				</template> 
			</el-main>
		</el-container>
		<el-dialog title="提示" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
			<el-form ref="form" :model="form" label-width="80px">
				<el-form-item label="热门词">
					<el-input v-model="form.name"></el-input>
				</el-form-item>
				<el-form-item label="搜索次数">
					<el-input v-model="form.num"></el-input>
				</el-form-item>
			</el-form>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>
				<el-button type="primary" @click="sub">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
    import {Api_url} from "@/common/config";
	import Nav from './components/app_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				form: {
					name: '',
					num: ''
				},
				dialogVisible: false,
				tableData: []
			}
		},
		mounted() {
			this.get_hot_data()
		},
		methods: {
			get_hot_data() {
				this.http.get('search/record').then(res => {
					this.tableData = res.data
				})
			},
			del(item) {
				console.log(item)
				this.$confirm('确定删除该热门搜索词', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('search/admin/del_record?name=' + item).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.get_hot_data()
					})

				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消删除'
					});
				});
			},
			add_hot() {
				this.dialogVisible = true
			},
			sub() {
				this.http.post_show('search/admin/add_record', this.form).then(res => {
					this.$message({
						type: 'success',
						message: '添加成功!'
					});
					this.form = {
						name: '',
						num: ''
					}
					this.get_hot_data()
					this.dialogVisible = false
				})

			},
			cancel() {
				this.form = {
					name: '',
					num: ''
				}
				this.dialogVisible = false
			},
			handleClose(){
				this.cancel()
			}
		}

	}
</script>

<style lang="less">

</style>
