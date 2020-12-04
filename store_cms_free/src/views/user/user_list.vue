<template>
	<div class="user_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>用户</el-breadcrumb-item>
						<el-breadcrumb-item>用户列表</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-collapse v-model="activeNames" @change="handleChange" >
							<el-collapse-item title="会员搜索" name="1">
								<div class="user_sear">
									<div class="sear_01">
										<div class="sear_01_01">
											<div class="sear_01_01_1">昵称：</div>
											<el-input v-model="nickname" size="small" placeholder="请输入昵称" @keyup.enter.native="search(nickname)"></el-input>
											<div style="margin-left: 20px;">
												<el-button type="success" size="small" @click="search(nickname)"><i class="el-icon-search"></i>搜索</el-button>
											</div>
											<div style="margin-left: 20px;">
												<el-button type="primary" size="small" @click="reset">重置</el-button>
											</div>

										</div>
									</div>
									<div class="sear_01">
										<!-- <div class="sear_01_01">
											<div class="sear_01_01_1">选择时间：</div>
											<el-date-picker type="date" placeholder="选择日期" v-model="form.date" style="width: 100%;"></el-date-picker>
										</div> -->
									</div>
									<!-- <div class="sea_02_02_r_2_1"><i class="el-icon-search"></i>搜索</div> -->
								</div>
							</el-collapse-item>
						</el-collapse>
						
						<div class="article">
							<div style="height:20px;">&nbsp;</div>
							<template>
								<el-table :data="list" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>
									<!-- <el-table-column prop="id" label="ID"></el-table-column> -->
									<el-table-column prop="nickname" label="昵称"></el-table-column>
									<el-table-column prop="create_time" label="创建时间"></el-table-column>
									<el-table-column prop="web_auth_id" label="前端管理权限">
										<template slot-scope="scope">
											<template v-if="scope.row.web_auth_id == 1">管理员</template>
											<template v-if="scope.row.web_auth_id == 0">普通用户</template>
											<template v-if="scope.row.web_auth_id == 2">员工</template>
										</template>
									</el-table-column>
									<strong></strong>
								</el-table>
							</template>
						</div>
						<el-pagination style="margin-top: 50px;" background layout="prev, pager, next" :total="total" :page-size="size"
						@current-change="jump_page">
						</el-pagination>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import Nav from './components/user_nav.vue'
	import UserModel from "@/api/user"
	export default{
		components:{
			Nav
		},
		data(){
			return{
				activeNames: ['1'],
				nickname: '',
				dialogImageUrl: '',
				oid: 0,
				list: [],
				all: '',
				size: 10,
				total: '',
			}
		},
		mounted() {
			this._load();
		},
		methods: {
			_load() {
				UserModel.getAll().then(res => { 
					this.all = res.data
					this.total = res.data.length
					this.list = res.data.slice(0, this.size)
				})
			},

			open(id, index) {
				let obj = [{
					msg: '取消权限，恢复普通用户？',
					auth: 0
				}, {
					msg: '将该用户设置为管理员？',
					auth: 1
				}, {
					msg: '设置为员工，仅有验证权限？',
					auth: 2
				}]
				let msg = obj[index].msg
				let auth = obj[index].auth
				this.$confirm(msg, '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show("cms/admin/set_web_auth", {
						id: id,
						auth_id: auth
					}).then(res => {
						this.$message({
							type: 'success',
							message: '操作成功!'
						});
						this._load()
					});
				})
			},


			reset() {
				this._load()
			},
			search(key) {
				const that = this
				that.list = []
				for (let k in that.all) {
					let v = that.all[k]
					if (v.nickname.indexOf(key) >= 0) {
						that.list.push(v)
					}
				}
				that.total = this.list.length;
				that.list = that.list.slice(0, that.size);
				this.nickname = []
			},
			handleChange() {

			},
			handleRemove(file, fileList) {
				console.log(file, fileList);
			},
			handlePictureCardPreview(file) {
				this.dialogImageUrl = file.url;
				this.dialogVisible = true;
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.list = this.all.slice(start, end);
			},
			sub() {},
			//删除权限
			del(id) {
				var that = this;
				this.$confirm('是否删除?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {

				})
			},

		},
	}
</script>

<style lang="less">
.user_sear {
			line-height: 40px;
			text-align: left;
			color: #6B6B6B;
			font-size: 14px;
			padding: 15px 15px ;
			border-top: 1px solid #F0F0F0;

			.sear_01 {
				display: flex;
				margin-bottom: 0px;

				.sear_01_01 {
					display: flex;
					margin-right: 30px;

					.sear_01_01_1 {
						flex-shrink: 0;
					}

					.el-input__inner {
						width: 200px;
					}
				}
			}

			.sea_02_02_r_2_1 {
				background-color: #008DE1;
				color: #fff;
				padding: 0 10px;
				margin-right: 10px;
				border-radius: 3px;
				width: 45px;
			}
		}
</style>
