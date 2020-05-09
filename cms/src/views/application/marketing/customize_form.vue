<template>
	<div class="order_export">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>应用</el-breadcrumb-item>
						<el-breadcrumb-item>营销应用</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang t_l">
						<template v-if="!show_form">
							<div class="t_l"><el-button type="primary" size="small"  @click="show_form=true">新增</el-button></div>
							<div class="H10"></div>
							<div class="H10"></div>
							<el-table :data="list" border style="width: 100%">
								<el-table-column type="index" label="序号" width="60">
									<el-input v-model="index"></el-input>
								</el-table-column>
								<el-table-column prop="title" label="表单标题" width="380">

								</el-table-column>
								
								<el-table-column label="类型" width="180"></el-table-column>
								<el-table-column label="类别" width="100%">
									
								</el-table-column>
								<el-table-column prop="create_time" label="创建时间" width="100%"></el-table-column>
								<el-table-column prop="is_hidden" label="隐藏" width="100%">
									<template slot-scope="scope">
										<el-switch @change="onswitch(scope.row.id)" v-model="scope.row.is_hidden" :active-value="1" active-color="#409EFF"
											inactive-color="#DCDFE6"></el-switch>
									</template>
								</el-table-column>

								<el-table-column prop="operation" label="操作" >
									<template slot-scope="scope">
										<el-button @click="edit_get(scope.row.id)" type="success" size="small">修改</el-button>
										<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.row.id,scope.$index)">删除</el-button>
									</template>
								</el-table-column>
								<strong></strong>
							</el-table>
						</template>
						<template v-if="show_form">
							<div class="t_l"><el-button type="primary" size="small"  @click="show_form=false">返回</el-button></div>
							<div class="H10"></div>
							<div class="H10"></div>
							<Form :edit="1"></Form>
						</template>
					</div>
				</template> 
			</el-main>
		</el-container>
	</div>
</template>

<script>
import {
		Loading
	} from "element-ui";
	import Form from "@/components/form.vue";
	import Nav from '../components/app_nav.vue'
	export default{
		components:{
			Nav,
			Form
		},
		data(){
			return{
				show_form:false,
				list:[]
			}
		},
		methods: {
			//获取所有文章
			getArticles() {
				var that = this;
				let loadingInstance = Loading.service({
					fullscreen: true
				}); //显示加载
				this.http.get("article/admin/get_all_article").then(res => {
					console.log(res.data);
					loadingInstance.close(); //关闭加载
					var res_code = res.status.toString();
					if (res_code.charAt(0) == 2) {
						that.list = res.data;

					}
				});
			},
			//删除商品
			del(id, index) {
				var that = this;
				this.$confirm("是否删除?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning"
				}).then(() => {
					this.http
						.put_show("article/admin/del_article", {
							id: id
						})
						.then(res => {
							that.$message({
								showClose: true,
								message: "删除成功",
								type: "success"
							});
							that.list.splice(index, 1);
						});
				});
			},
			edit_get(id) {
				var that = this;
				// -------------------------------------------文章类型为表单时的修改
				// this.tab_nav = 3
				// this.is_form  = true
				// this.form_btn = '返回'
				// this.is_form_edit = 1
				// -----------------------------------------------end
				
				
				// 此时没有接口,需要从接口中判断文章列表中的文章类型为表单还是文章,根据值跳转不同界面
				
				// -------------------------------------------------------------类型为文章时取消注释，勿删
				this.http.get_show("article/get_one_article?id=" + id).then(res => {
					// that.btn_title = "文章列表";
					// that.form.title = res.data.title;
					that.form.content = res.data.content;
					that.form.type = res.data.type;
					if (res.data.type == 2) {
						that.form.appid = res.data.summary;
						that.form.path = res.data.content;
					}
					this.img_list.push(res.data.img)
					that.isedit = true;
					that.oid = res.data.id;
					that.tab_nav = 2;
				});
				// ------------------------------------------------------------------end
			},
		},
		mounted() {
			
			this.getArticles();
		}
	}
</script>

<style lang="less">

</style>
