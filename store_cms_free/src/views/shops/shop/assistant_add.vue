<template>
	<div class="assistant bg-white">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>商家</el-breadcrumb-item>
						<el-breadcrumb-item>店员管理</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang t_l ">
						<div style="padding-bottom:20px;">
							<el-row>
								<el-col :span="1">
									<el-button type="success" size="mini" @click="back">返回</el-button>
								</el-col>
						
							</el-row>
						</div>
						<div class="widt">
							<el-form ref="box-a" :rules="rules" :model="form" label-width="150px" >
								<el-form-item label="店员姓名" prop="username">
									<el-input v-model="form.username" width="60%"></el-input>
								</el-form-item>
								<el-form-item label="手机号" prop="mobile">
									<el-input v-model.number="form.mobile" placeholder="请输入"></el-input>
								</el-form-item>
								<el-form-item label="密码" prop="password">
									<el-input v-model="form.password" placeholder="请输入"></el-input>
								</el-form-item>
								<el-form-item  label="所属门店" prop="shop_id">
									<el-select v-model="form.shop_id" placeholder="请选择">
										<el-option v-for="item in store_list"
										:key="item.shop_id"
										:label="item.shop_name"
										:value="item.shop_id">
										</el-option>
									</el-select>
									</el-form-item>
								<el-form-item>
									<el-button type="primary" @click="onSubmit">立即创建</el-button>
									<el-button>取消</el-button>
								</el-form-item>
							</el-form>
						</div>
					</div>
				</template>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import StoreModel from '@/api/store'
	import Nav from '../components/shop_nav.vue'
	export default {
		components: {
			Nav
		},
		data() {
			return {
				form: {
					username: '',
					mobile:'',
					shop_id: '',
					password:""
				},
				store_list: [],
				value: '',
				rules: {
					username: [
					{ required: true, message: "请输入活动名称", trigger: "blur" }, //blur失去焦点
					{ min: 2, max: 10, message: "长度在 2 到 10 个字符", trigger: "blur" }
					],
					mobile: [
					{ required: true, message: "请填写电话", trigger: "blur" },
					{type: 'number', message: '电话必须为数字值'}
					],  
					shop_id: [
						{ required: true, message: "请选择店铺", trigger: "change" }
					],  
					password: [
						{ required: true, message: "请输入密码", trigger: "blur" }
					]
				}
			}
		}, 
		mounted() {
			StoreModel.getAll().then(res => { 
				this.store_list=res.data 
			})
		},
		methods:{ 
			onSubmit() {
				this.$refs['box-a'].validate((valid) => {
					if (valid) {
						StoreModel.add_assistant(this.form).then(res => { 
							this.clear_data()
							this.$message({
								message: '操作成功',
								type: 'success'
							});
							this.back()
						})
					} else {
						console.log('未通过检测');
						return false;
					}
				});
			},
			back(){
				this.$router.go(-1)
			},
			clear_data(){
				this.form={
					username: '',
					mobile:'',
					shop_id: '',
					password:""
				}
			}
		}
	}
</script>

<style lang="less">
	.add-yuan{background-color: #fff;font-size: 14px;padding: 20px 20px 10px;margin: 10px 0 0;
		.tian{display: flex;padding:40px 10px 0;text-align: left;
			.tian-l{width: 100px;text-align: right;padding-right: 20px;display: flex;flex-direction: column;justify-content: center;}
			.tian-r{min-width: 500px;;}
		}
		.btn{text-align: left;padding:50px 0 10px 130px;}
	}
	.widt{width: 60%;}
</style>
