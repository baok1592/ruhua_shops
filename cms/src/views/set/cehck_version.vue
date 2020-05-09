<template>
	<div class="order_list">
		<el-container class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2-2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>设置</el-breadcrumb-item>
						<el-breadcrumb-item>检测版本</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang t_l">
						<el-button @click="up" type="primary" size='small' style="margin-bottom: 20px;">获取新版本</el-button>
						<el-table :data="list" stripe style="width: 100%" border=""> 							
							<el-table-column type="index" label="序号" width="90"></el-table-column>
							<el-table-column prop="now" label="当前系统版本" width="200">
							</el-table-column>
							<el-table-column prop="version" label="最新系统版本" width="200">
							</el-table-column>
							<el-table-column prop="time" label="发布时间" width="200">
							</el-table-column>
							<el-table-column prop="content" label="版本描述" width="600">
								<template slot-scope="scope">
									<div v-html="scope.row.content"></div>
								</template>
							</el-table-column>
							<el-table-column prop="url" label="操作">
								<template slot-scope="scope">
									<a @click="download">下载</a>
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
    import {Api_url} from "@/common/config";
	import Nav from './components/set_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				list:[], 
				new_version:''
			}
		},
		methods: {
			up(){  
				this.http.get_show('update/get_version').then(res=>{
					console.log('xx:',res)
					if(res.status==200){ 
						this.$message({
							message:'获取成功',
							type: 'success'
						});
						this.list=[res.data]
						this.new_version=res.data.version
					}else{
						this.$message({
							message:'非授权站点，请购买商业授权，避免法律纠纷',
							type: 'error',
							duration:7000
						});
						this.list=[]
					}
					console.log(this.list)
				})
			},
			download(){
				this.http.get_show('update/get_url').then(res=>{
					if(res.status!=200){
						this.$message({
							message:res.msg,
							type: 'error',
							duration:7000
						});	
						return;
					}
					const aLink = document.createElement('a');
					aLink.href = res.data.data
					aLink.target = '_blank'
					aLink.download = this.new_version+'.zip';
					aLink.click();
					aLink.remove();
				})
			}
		}

	}
</script>

<style lang="less">

</style>
