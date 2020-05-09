<template>
	<div class="shop_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-1"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>商家</el-breadcrumb-item>
						<el-breadcrumb-item>商家列表</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<div class="t_l"><el-button type="success" size="small" @click="back">返回</el-button></div>
						<div class="add-shop t_l">
							<el-form ref="box-a" :rules="rules" :model="form" label-width="150px" >
								<el-form-item label="店铺名称" prop="shop_name">
									<el-input v-model="form.shop_name" ></el-input>
								</el-form-item>
								<el-form-item label="商家类型"  prop="shop_group_id">
									<el-select v-model="form.shop_group_id" filterable placeholder="请选择">
										<el-option
										v-for="item in category_list"
										:key="item.category_id"
										:label="item.category_name"
										:value="item.category_id">
										</el-option>
									</el-select>
								</el-form-item>
								<el-form-item label="门店电话" prop="shop_phone">
									<el-input v-model.number="form.shop_phone" placeholder="请输入"></el-input>
								</el-form-item>
								<el-form-item label="营业时间" >
									<el-time-select
										placeholder="起始时间"
										v-model="form.yy_time[0]"
										:picker-options="{
										start: '08:30',
										step: '00:15',
										end: '18:30'
										}">
									</el-time-select>&emsp;
									<el-time-select
										placeholder="结束时间"
										v-model="form.yy_time[1]"
										:picker-options="{
										start: '08:30',
										step: '00:15',
										end: '18:30',
										minTime: form.yy_time[0]
										}">
									</el-time-select>
								</el-form-item>
								<!-- <el-form-item label="门店位置" >
									<div class="tian-r">
										<el-input placeholder="地理纬度"></el-input> &emsp;<el-button>选择坐标</el-button>
									</div> 
								</el-form-item> -->
								<el-form-item label="省市区" prop="sheng">
									<el-cascader
									size="large"
									ref="cascaderAddr"
									:options="citys" 
									v-model="selected_city"
									@change="cityChange">
									</el-cascader>
								</el-form-item>
								<el-form-item label="门店地址" prop="shop_address">
									<el-input v-model="address" placeholder="请输入"></el-input> 
								</el-form-item>
								<!-- <el-form-item label="联系人信息" prop="" >
									<div class="tian-r">
										<el-form-item label="姓名" prop="name" label-width="60px">
											<el-input v-model="form.name" placeholder="请输入"></el-input>
										</el-form-item>
										<el-form-item label="电话" prop="tell" label-width="60px">
											<el-input v-model.number="form.tell" placeholder="请输入"></el-input>
										</el-form-item>
									</div>
								</el-form-item> -->
								
								<el-form-item label="门店简介" prop="shop_description">
									<el-input v-model="form.shop_description" type="textarea" :rows="3"></el-input>
								</el-form-item>  
								<!-- <el-form-item label="店铺LOGO">
									<el-upload :action="upfile_url" :data="{back: 'idurl' }" :on-success="upimg_back_fun" :headers="upfile_head"
						 :limit="9" multiple :show-file-list="false" :file-list="upfile_banner_list" name="img" list-type="picture-card">
								<img v-if="img1" :src="$getimg + img1" class="avatar"> 
  								<i v-else class="el-icon-plus"></i>
						</el-upload> 
								</el-form-item>-->
								<div class="tian">
									<el-form-item label="身份证正">
										<el-upload :action="upfile_url" :data="{back: 'idurl' }" :on-success="upimg_back_fun1" :headers="upfile_head"
						 :limit="9" multiple :show-file-list="false" :file-list="upfile_banner_list" name="img" list-type="picture-card">
								<img v-if="img1" :src="$getimg + img1" class="avatar"> 
  								<i v-else class="el-icon-plus"></i>
						</el-upload>
									</el-form-item>
									<el-form-item label="身份证反">
										<el-upload :action="upfile_url" :data="{back: 'idurl' }" :on-success="upimg_back_fun2" :headers="upfile_head"
						 :limit="9" multiple :show-file-list="false" :file-list="upfile_banner_list" name="img" list-type="picture-card">
								<img v-if="img2" :src="$getimg + img2" class="avatar"> 
  								<i v-else class="el-icon-plus"></i>
						</el-upload>
									</el-form-item>
									<el-form-item label="营业执照">
										<el-upload :action="upfile_url" :data="{back: 'idurl' }" :on-success="upimg_back_fun3" :headers="upfile_head"
						 :limit="9" multiple :show-file-list="false" :file-list="upfile_banner_list" name="img" list-type="picture-card">
								<img v-if="img3" :src="$getimg + img3" class="avatar"> 
  								<i v-else class="el-icon-plus"></i>
						</el-upload>
									</el-form-item>
								
								</div>
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
	import { regionData } from 'element-china-area-data'
	import categoryModel from '@/api/category'
	import StoreModel from "@/api/store"
	import City from '@/common/city'
	import Nav from '../components/shop_nav.vue'
	import { Api_url } from '@/common/config';
	export default {
		components: {
			Nav
		},
		data() {
			return{
				form: {
					shop_name:'',
					shop_address:[], 
					shop_phone:'', 
					shop_description:'',
					shop_type:'',
					yy_time:['',''], 
					sheng:''
				},
				img1:"",
				img2:"",
				img3:"",
				address:"",
				citys: regionData, 
       			selected_city: [],
				category_list:[],
				value: '',				
				imageUrl: '',
				rules: {
					shop_name: [
					{ required: true, message: "请输入店铺名称", trigger: "blur" }, //blur失去焦点
					{ min: 2, max: 10, message: "长度在 2 到 10 个字符", trigger: "blur" }
					],
					shop_phone: [
					{ required: true, message: "请填写电话", trigger: "blur" },
					{type: 'number', message: '电话必须为数字值'}
					],   
					shop_address:[
						{ required: true, message: "请输入店铺地址", trigger: "blur" }
					],
					shop_group_id:[
						{ required: true, message: "请选择分类", trigger: "change" }
					]
				},
				upfile_url: Api_url + 'img_category/admin/upload/img',
				upfile_head: {
					token: localStorage.getItem('token')
				},
				upfile_list: [], //上传文件列表
				upfile_list_sku: [], //上传文件列表--规格
				upfile_banner_list: [], //上传幻灯片列表
			}
		},
		mounted() { 
			categoryModel.getAll().then(res=>{
				this.category_list=res.data
			});
		},
		methods: {
			cityChange (value) {
				const thsAreaCode = this.$refs['cascaderAddr'].getCheckedNodes()[0].pathLabels
				this.form.shop_address=thsAreaCode
			},
			handleAvatarSuccess(res, file) {
				this.imageUrl = URL.createObjectURL(file.raw);
			},
			beforeAvatarUpload(file) {
				const isJPG = file.type === 'image/jpeg';
				const isLt2M = file.size / 1024 / 1024 < 2;

				if (!isJPG) {
				this.$message.error('上传头像图片只能是 JPG 格式!');
				}
				if (!isLt2M) {
				this.$message.error('上传头像图片大小不能超过 2MB!');
				}
				return isJPG && isLt2M;
			},
			//返回
			back(){
				this.$router.push({
					path: '/shops/shop_list'
				})
			},
			clear_data(){
				this.form={
					shop_name:'',
					shop_address:[], 
					shop_phone:'', 
					shop_description:'',
					shop_type:'',
					yy_time:['',''], 
					sheng:''
				} 
				this.address=""
				this.selected_city=[],
				this.img1=""
				this.img2=""
				this.img3=""
			},
			onSubmit() { 
				this.form.shop_address.push(this.address); 
				this.form.license_url=this.img1
				this.form.user_sfz_url=this.img2
				this.form.shop_pic_url=this.img3 
				this.$refs['box-a'].validate((valid) => {
				if (valid) {
					StoreModel.add_store(this.form).then(res => {  
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
			//图片上传回调
			upimg_back_fun1(res) {
				this.$message({
					type: 'success',
					message: '上传成功'
				});
				this.img1=res.data.url; 
			},
			upimg_back_fun2(res) {
				this.$message({
					type: 'success',
					message: '上传成功'
				});
				this.img2=res.data.url; 
			},
			upimg_back_fun3(res) {
				this.$message({
					type: 'success',
					message: '上传成功'
				});
				this.img3=res.data.url; 
			},
		},
	}
</script>

<style lang="less">

	.add-shop{background-color: #fff;font-size: 14px;padding: 20px 20px 120px;margin: 10px 0 0;width: 60%;
	.tian{display: flex;padding:30px 10px 0;
		.tian-l{width: 100px;text-align: right;padding-right: 20px;display: flex;flex-direction: column;justify-content: center;}
		
	}
	.tian-r{min-width: 500px;display: flex;
			.tian-r-name{width: 50px;flex-shrink: 0;line-height: 40px;}
		}
	.btn{text-align: left;padding:50px 0 10px 130px;}
}
.avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 140px;
    height: 140px;
    line-height: 140px;
    text-align: center;
  }
  .avatar {
    width: 140px;
    height: 140px;
    display: block;
  }

</style>
