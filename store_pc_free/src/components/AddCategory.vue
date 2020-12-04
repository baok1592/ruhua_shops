<template>
	<div class="add-cate">
		<el-button type="primary" size="small" @click="addbox = true">添加商品分类</el-button>
		<!-- <el-button type="primary" size="small" @click="img_manage">图库管理</el-button> -->

		<el-dialog title :visible.sync="addbox" width="35%" center>
			<el-form :model="addform" :rules="rules" ref="box-a">
				<el-form-item label="商品分类名称" :label-width="formLabelWidth" style="width: 80%" prop="category_name">
					<el-input v-model="addform.category_name" auto-complete="off"></el-input>
				</el-form-item>
			</el-form>
			<div slot="footer" class="dialog-footer" style="text-align: center">
				<el-button @click="addbox = false">取 消</el-button>
				<el-button type="primary" @click="onSubmit">确 定</el-button>
			</div>
		</el-dialog>
		<Pic :drawer="drawer" :father_fun="get_img" :length="length"></Pic>
	</div>
</template>

<script>
	import {
		Api_url
	} from "@/common/config.js";
	import Pic from "../views/PicList.vue";
	import categoryModel from '@/api/category'
	export default {
		name: "Category",
		props: ["list"],
		data() {
			return {
				rules: {
					category_name: [{
							required: true,
							message: "请输入分类名称",
							trigger: "blur"
						} //blur失去焦点
					]
				},
				length: 1,
				drawer: false,
				getimg: this.$getimg,
				img_list: [],
				addbox: false,
				addform: {
					category_name: "",
				},
				formLabelWidth: "120px",
				upfile_url: Api_url + "/admin/upload/img",
				upfile_data: {
					use: "category"
				},
				upfile_head: {
					token: localStorage.getItem("token")
				},
				upfile_list: [] //上传文件列表
			};
		},
		components: {
			Pic
		},
		methods: {
			//图库管理
			img_manage() {
				this.drawer = !this.drawer;
				this.length = 50;
			},
			//新增分类
			onSubmit() {
				this.http.post_show('cate/add_category',{
					name:this.addform.category_name
				}).then(res=>{
					this.$message({
						message: '操作成功',
						type: 'success'
					})
					this.addbox = false
					this.addform.category_name = ''
					this.$emit('up_parent')
				})
			},

			to_choose_img() {
				this.length = 1;
				this.drawer = !this.drawer;
			},
			is_show() {
				this.drawer = !this.drawer;
			},
			get_img(e) {
				this.drawer = false;
				for (let k in e) {
					const v = e[k];
					this.img_list.push(v);
					this.addform.category_pic = v.id;
				}
				this.length = 6 - this.img_list.length;
				console.log("get_img_end:", e, this.img_list);
			},
			delimg(index) {
				this.img_list.splice(index, 1);
			},

			up_ok(res) {
				if (res.code == 201) {
					this.addform.category_pic = res.id;
				}
			}
		}
	};
</script>

<style lang="less" scoped="">
	.add-cate {
		.category {
			line-height: 30px;
			text-align: left;
		}

		.about {
			padding: 10px;
		}

		.btn {
			margin-bottom: 6px;
		}

		.picA {
			width: 148px;
			height: 148px;
			background-color: #fbfdff;
			border: 1px dashed #c0ccda;
			border-radius: 6px;
			box-sizing: border-box;
			vertical-align: top;
			text-align: center;
			margin: 6px;

			img {
				width: 144px;
				height: 144px;
				border: 1px solid #bfbfbf;
				border-radius: 3px;
			}
		}
	}
</style>
