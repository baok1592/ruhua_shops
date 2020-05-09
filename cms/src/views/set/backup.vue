<template>
	<div class="order_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2-1"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>设置</el-breadcrumb-item>
						<el-breadcrumb-item>备份</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang t_l">
						<el-button type="primary" size='small' style="margin-bottom: 20px;" @click="add_backup"
						v-loading.fullscreen.lock="fullscreenLoading">备份数据库</el-button>
						<el-table :data="tableData" stripe style="width: 100%;" border="">
							<el-table-column type="index" label="序号">
							</el-table-column>
							<el-table-column prop="name" label="文件名称">
							</el-table-column>
							<el-table-column prop="size" label="文件大小">
							</el-table-column>
							<el-table-column prop="url" label="文件地址">
							</el-table-column>
							<el-table-column prop="create_time" label="备份时间">
							</el-table-column>
							<el-table-column label="操作">
								<template slot-scope="scope">
									<el-button type="danger" size="medium" @click="del_backup(scope.row.id)">删除</el-button>
									<el-button type="primary" size="medium">
										<a style="text-decoration: none;color: #FFFFFF;" :href="surl + scope.row.url" target="_blank">下载</a>
									</el-button>
									
									
								</template>
							</el-table-column>
						</el-table>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
import {
		Api_url
	} from "@/common/config";
	import Nav from './components/set_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				backup:Api_url + 'backup/get_backup',
				surl:Api_url,
				tableData: '',
				fullscreenLoading: false,
			}
		},
		mounted() {
			this.get_backup()
		},
		methods: {
			
			get_backup() {
				this.http.get('backup/get_backup').then(res => {
					this.tableData = res.data
				})
			},
			add_backup() {
				// this.$message({
				// 	type: 'info',
				// 	message: '仅演示'
				// });
				// return;
				this.fullscreenLoading = true;                   //正式版解开注释
				this.http.get_show('backup/add_backup').then(res => {
					this.fullscreenLoading = false;
					this.get_backup()
				})
			},
			del_backup(id) {
				this.$confirm('确定删除该备份?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.put_show('backup/del_backup?id=' + id).then(res => {
						this.$message({
							type: 'success',
							message: '删除成功!'
						});
						this.get_backup()
					})
				}).catch(() => {
					this.$message({
						type: 'info',
						message: '已取消删除'
					});
				});
			}
		},
	}
</script>

<style lang="less">
.pro-list {
		line-height: 30px;
		text-align: left;
	}
</style>
