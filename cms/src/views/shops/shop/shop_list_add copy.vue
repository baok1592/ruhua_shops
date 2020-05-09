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
								<el-form-item label="商家类型"  prop="shop_type_id">
									<el-select v-model="form.shop_type_id" filterable placeholder="请选择">
										<el-option
										v-for="item in shoplx"
										:key="item.value"
										:label="item.label"
										:value="item.value">
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
								<el-form-item label="省市区" prop="sheng">
									<el-cascader
									size="large"
									:options="citys"
									v-model="selected_city"
									@change="cityChange">
									</el-cascader>
								</el-form-item>
								<el-form-item label="门店位置" >
									<div class="tian-r">
										<el-input placeholder="地理纬度"></el-input> &emsp;<el-button>选择坐标</el-button>
									</div>
									
								</el-form-item>
								<el-form-item label="门店地址" prop="shop_address">
									<el-input v-model="form.shop_address" placeholder="请输入"></el-input> 
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
								<el-form-item label="店铺LOGO">
									<el-upload 
										class="avatar-uploader"
										action="https://jsonplaceholder.typicode.com/posts/"
										:show-file-list="false"
										:on-success="handleAvatarSuccess"
										:before-upload="beforeAvatarUpload">
										<img v-if="imageUrl" :src="imageUrl" class="avatar">
										<i v-else class="el-icon-plus avatar-uploader-icon"></i>
									</el-upload>
								</el-form-item>
								<div class="tian">
									<el-form-item label="身份证正">
										<el-upload 
											class="avatar-uploader"
											action="https://jsonplaceholder.typicode.com/posts/"
											:show-file-list="false"
											:on-success="handleAvatarSuccess"
											:before-upload="beforeAvatarUpload">
											<img v-if="imageUrl" :src="imageUrl" class="avatar">
											<i v-else class="el-icon-plus avatar-uploader-icon"></i>
										</el-upload>
									</el-form-item>
									<el-form-item label="身份证反">
										<el-upload 
											class="avatar-uploader"
											action="https://jsonplaceholder.typicode.com/posts/"
											:show-file-list="false"
											:on-success="handleAvatarSuccess"
											:before-upload="beforeAvatarUpload">
											<img v-if="imageUrl" :src="imageUrl" class="avatar">
											<i v-else class="el-icon-plus avatar-uploader-icon"></i>
										</el-upload>
									</el-form-item>
									<el-form-item label="营业执照">
										<el-upload 
											class="avatar-uploader"
											action="https://jsonplaceholder.typicode.com/posts/"
											:show-file-list="false"
											:on-success="handleAvatarSuccess"
											:before-upload="beforeAvatarUpload">
											<img v-if="imageUrl" :src="imageUrl" class="avatar">
											<i v-else class="el-icon-plus avatar-uploader-icon"></i>
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
	import StoreModel from "@/api/store"
	import City from '@/common/city'
	import Nav from '../components/shop_nav.vue'
	export default {
		components: {
			Nav
		},
		data() {
			return{
				form: {
					shop_name:'',
					shop_address:'', 
					shop_phone:'', 
					shop_description:'',
					shop_type:'',
					yy_time:['',''], 
					sheng:''
				},
				citys: regionData,
       			selected_city: [],
				shoplx: [{
					value: '选项1',
					label: '黄金糕'
					}, {
					value: '选项2',
					label: '双皮奶'
					}, {
					value: '选项3',
					label: '蚵仔煎'
					}, {
					value: '选项4',
					label: '龙须面'
					}, {
					value: '选项5',
					label: '北京烤鸭'
				}],
				value: '',
				
				imageUrl: '',
				options: [{
					value: 'zhinan',
					label: '指南',
					children: [{
						value: 'shejiyuanze',
						label: '设计原则',
						children: [{
						value: 'yizhi',
						label: '一致'
						}, {
						value: 'fankui',
						label: '反馈'
						}, {
						value: 'xiaolv',
						label: '效率'
						}, {
						value: 'kekong',
						label: '可控'
						}]
					}, {
						value: 'daohang',
						label: '导航',
						children: [{
						value: 'cexiangdaohang',
						label: '侧向导航'
						}, {
						value: 'dingbudaohang',
						label: '顶部导航'
						}]
					}]
					}, {
					value: 'zujian',
					label: '组件',
					children: [{
						value: 'basic',
						label: 'Basic',
						children: [{
						value: 'layout',
						label: 'Layout 布局'
						}, {
						value: 'color',
						label: 'Color 色彩'
						}, {
						value: 'typography',
						label: 'Typography 字体'
						}, {
						value: 'icon',
						label: 'Icon 图标'
						}, {
						value: 'button',
						label: 'Button 按钮'
						}]
					}, {
						value: 'form',
						label: 'Form',
						children: [{
						value: 'radio',
						label: 'Radio 单选框'
						}, {
						value: 'checkbox',
						label: 'Checkbox 多选框'
						}, {
						value: 'input',
						label: 'Input 输入框'
						}, {
						value: 'input-number',
						label: 'InputNumber 计数器'
						}, {
						value: 'select',
						label: 'Select 选择器'
						}, {
						value: 'cascader',
						label: 'Cascader 级联选择器'
						}, {
						value: 'switch',
						label: 'Switch 开关'
						}, {
						value: 'slider',
						label: 'Slider 滑块'
						}, {
						value: 'time-picker',
						label: 'TimePicker 时间选择器'
						}, {
						value: 'date-picker',
						label: 'DatePicker 日期选择器'
						}, {
						value: 'datetime-picker',
						label: 'DateTimePicker 日期时间选择器'
						}, {
						value: 'upload',
						label: 'Upload 上传'
						}, {
						value: 'rate',
						label: 'Rate 评分'
						}, {
						value: 'form',
						label: 'Form 表单'
						}]
					}, {
						value: 'data',
						label: 'Data',
						children: [{
						value: 'table',
						label: 'Table 表格'
						}, {
						value: 'tag',
						label: 'Tag 标签'
						}, {
						value: 'progress',
						label: 'Progress 进度条'
						}, {
						value: 'tree',
						label: 'Tree 树形控件'
						}, {
						value: 'pagination',
						label: 'Pagination 分页'
						}, {
						value: 'badge',
						label: 'Badge 标记'
						}]
					}, {
						value: 'notice',
						label: 'Notice',
						children: [{
						value: 'alert',
						label: 'Alert 警告'
						}, {
						value: 'loading',
						label: 'Loading 加载'
						}, {
						value: 'message',
						label: 'Message 消息提示'
						}, {
						value: 'message-box',
						label: 'MessageBox 弹框'
						}, {
						value: 'notification',
						label: 'Notification 通知'
						}]
					}, {
						value: 'navigation',
						label: 'Navigation',
						children: [{
						value: 'menu',
						label: 'NavMenu 导航菜单'
						}, {
						value: 'tabs',
						label: 'Tabs 标签页'
						}, {
						value: 'breadcrumb',
						label: 'Breadcrumb 面包屑'
						}, {
						value: 'dropdown',
						label: 'Dropdown 下拉菜单'
						}, {
						value: 'steps',
						label: 'Steps 步骤条'
						}]
					}, {
						value: 'others',
						label: 'Others',
						children: [{
						value: 'dialog',
						label: 'Dialog 对话框'
						}, {
						value: 'tooltip',
						label: 'Tooltip 文字提示'
						}, {
						value: 'popover',
						label: 'Popover 弹出框'
						}, {
						value: 'card',
						label: 'Card 卡片'
						}, {
						value: 'carousel',
						label: 'Carousel 走马灯'
						}, {
						value: 'collapse',
						label: 'Collapse 折叠面板'
						}]
					}]
					}, {
					value: 'ziyuan',
					label: '资源',
					children: [{
						value: 'axure',
						label: 'Axure Components'
					}, {
						value: 'sketch',
						label: 'Sketch Templates'
					}, {
						value: 'jiaohu',
						label: '组件交互文档'
					}]
				}],
				rules:{},
				rules2: {
					shopname: [
					{ required: true, message: "请输入店铺名称", trigger: "blur" }, //blur失去焦点
					{ min: 2, max: 10, message: "长度在 2 到 10 个字符", trigger: "blur" }
					],
					shoptell: [
					{ required: true, message: "请填写电话", trigger: "blur" },
					{type: 'number', message: '电话必须为数字值'}
					],  
					shop_type: [
					{ required: true, message: "请选择店铺类型", trigger: "change" }
					],  
					sheng:[
					{ required: true, message: "请选择店铺类型", trigger: "change" }],
					address:[
						{ required: true, message: "请输入店铺地址", trigger: "blur" }
					],
					des:[
						{ required: true, message: "请输入店铺简介", trigger: "blur" },
						{ min: 10, message: "长度不得少于10 个字符", trigger: "blur" }
					],
					name: [
					{ required: true, message: "请输入名字", trigger: "blur" }, //blur失去焦点
					{ min: 2, max: 10, message: "长度在 2 到 10 个字符", trigger: "blur" }
					],
					tell: [
					{ required: true, message: "请填写电话", trigger: "blur" },
					{type: 'number', message: '电话必须为数字值'}
					],  
				}
			}
		},
		mounted() {
			
		},
		methods: {
			handleChange (value) {
				console.log(value)
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
					shop_address:'',
					name: '',
					shop_phone:'',
					shop: '', 
					shop_description:'',
					shop_type:'',
					yy_time:'', 
					sheng:''
				} 
			},
			onSubmit() {
				this.$refs['box-a'].validate((valid) => {
				if (valid) {
					StoreModel.add_store(this.form).then(res => { 
						this.clear_data()
						this.$message({
							message: '操作成功',
							type: 'success'
						});
					})
				} else {
					console.log('未通过检测');
					return false;
				}
				});
			}
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
