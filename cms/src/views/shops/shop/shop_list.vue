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
						
						<div style="padding-bottom:20px;">
							<el-row>
								<el-col :span="1">
									<el-button type="primary" size="small" @click="shop_add">新增</el-button>
								</el-col>
								<el-col :span="2">
									
									<el-button type="warning" size="small" @click="sort_sub">更新排序</el-button>
								</el-col>
								<el-col :span="4">
									<el-input placeholder="商家名称" style="width: 98%" size="small" v-model="keyword"></el-input>
								</el-col>
								<el-col :span="2">
									<el-button type="info" size="small" @click="shop_search">商家搜索</el-button>
								</el-col>
								<el-col :span="6">
									<el-cascader size="small" :placeholder="region_name_show" v-model="get_region" :options="options" :props="{ expandTrigger: 'hover',value:'label'}" @change="handleChange"></el-cascader>
									<el-button style="margin-left:20px ;" type="success" size="small" @click="reset">刷新</el-button>
								</el-col>
							</el-row>
						</div> 
						<transition appear appear-active-class="animated fadeInLeft">
							<el-table :data="list" border style="width: 100%">
								<el-table-column prop label="排序" width="70">
									<template slot-scope="scope">
										 <el-input v-model="scope.row.sort"></el-input>  
									</template> 
								</el-table-column>
								<el-table-column prop="shop_name" label="名称" width="180"></el-table-column>
								<el-table-column prop="shop_phone"  label="联系电话" width="180"></el-table-column>
								<el-table-column label="证件照" width="300">
									<template slot-scope="scope">
										<a :href="imgurl + scope.row.license_url" target="_blank" v-if="scope.row.license_url"><img :src="imgurl + scope.row.license_url"
											class="zs-img" /></a>
										<a :href="imgurl + scope.row.user_sfz_url" target="_blank" v-if="scope.row.user_sfz_url"><img :src="imgurl + scope.row.user_sfz_url"
											class="zs-img" /></a>
										<a :href="imgurl + scope.row.shop_pic_url" target="_blank" v-if="scope.row.shop_pic_url"><img :src="imgurl + scope.row.shop_pic_url"
											class="zs-img" /></a>
									</template>
								</el-table-column>
								<el-table-column prop="region_id" label="区域" width="180" :filters="region_list" :filter-method="filterHandler">
									<template slot-scope="scope">
										{{scope.row.region_id}}
									</template>
								</el-table-column>
								<el-table-column prop="create_time" label="加入日期" width="180"></el-table-column>
								<el-table-column label="操作">
									<template slot-scope="scope">
										<el-button v-if="scope.row.shop_state>0" style="margin-left: 10px" type="danger" size="small"
										 slot="reference" @click="close_store(scope.row.shop_id)">禁用</el-button>
										<span v-else>已禁用</span> 
									</template>
								</el-table-column> 
							</el-table>
						</transition>
						<div class="H20"></div>
						<el-pagination background layout="prev, pager, next" :total="total" :page-size="size" @current-change="jump_page"></el-pagination>
					
					</div>
				</template>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import StoreModel from '@/api/store'
	import City from '@/common/city'
	import Nav from '../components/shop_nav.vue'
	export default {
		components: {
			Nav
		},
		data() {
			return {
				region_name_show:'按地区筛选',
				options:City,
				get_region:'',
				imgurl: this.$getimg,
				list: "",
				all: "",
				size: 10,
				total: "",
				keyword: "",
				region_list: ''
			}
		},
		mounted() {
			this.get_shop_all(); 
		},
		methods: {
			handleChange(value){
				console.log(value[1])
				this.list = []
				for (let k in this.all) {
					let v = this.all[k]
					if(v.region.region_name == value[1]){
						this.list.push(v)
					}
				}
				console.log(this.list)
			},
			//地区过滤
			filterHandler(value, row, column) {
				const property = column['property'];
				return row[property] === value;
			},			
			get_shop_all() {
				const that = this;
				StoreModel.getAll().then(res => {
					that.all = res.data;
					that.list = res.data.slice(0, that.size);
					that.total = res.data.length;
					let arr = []
					for (let k in res.data) {
						let v = res.data[k]
						arr.push({
							text:v.region.name,
							value:v.region_id
						})
					}
					this.region_list = arr
				});
			},
			//关闭店铺
			close_store(id){
				this.$confirm("是否禁用?", "提示", {
					confirmButtonText: "确定",
					cancelButtonText: "取消",
					type: "warning"
				}).then(() => {
					StoreModel.close_store({id:id}).then(res=>{
						this.$message({
							message: '操作成功',
							type: 'success'
						});
						this.get_shop_all(); 
					}) 
				});
				 
			},
			//新增
			shop_add(){
				this.$router.push({
					path: '/shops/shop_list_add'
				})
			},
			//提交排序
			sort_sub() {
				let obj = {};
				const that = this;
				for (let value of that.list) {
					obj[value.shop_id] = value.sort;
				}
				this.http.post_show("/admin/set_shop_sort", obj).then(res => {
					this.$message({
						message: "操作成功",
						type: "success"
					});
				});
			},
			shop_search() {
				var that = this;
				this.http
					.post_show("/admin/search_shop", {
						keyword: that.keyword
					})
					.then(res => {
						that.all = res.data;
						that.list = res.data.slice(0, that.size);
						that.total = res.data.length;
					});
			},
			jump_page(e) {
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				this.list = this.all.slice(start, end);
			},
			 
			reset(){
				this.get_shop_all();
				this.get_region_all()
				this.region_name_show = '按地区筛选'
				this.get_region = []
			},
			on_edit(id) {
				this.$emit("cmp_edit", id);
			}
		},
	}
</script>

<style lang="less">
	.H20 {
		height: 20px;
	}
	.zs-img{
		height:70px;
		width:70px;
		margin:0 5px;
	}
</style>
