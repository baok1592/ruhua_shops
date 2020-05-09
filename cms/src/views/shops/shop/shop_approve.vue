<template>
	<div class="shop_approve">
		<el-container  class="container">
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1-2"></Nav>
			</el-aside>
			<el-main class="main">
				<template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>商家</el-breadcrumb-item>
						<el-breadcrumb-item>商家申请</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
					<div class="zhang">
						<el-table :data="tableData" border style="width: 100%;">
							<el-table-column type="index" label="序号" width="100">
							</el-table-column>
							<el-table-column prop="user_mobile" label="申请人电话" width="150">
							</el-table-column>
							<el-table-column prop="shop_name" label="店铺名称" width="220">
							</el-table-column>
							<el-table-column prop="shop_address" label="店铺地址" width="220">
							</el-table-column>
							<el-table-column label="所在城市" width="150" prop="region_id"></el-table-column>
							<el-table-column label="证件照片">
								<template slot-scope="scope">
									<a :href="imgurl + scope.row.license_url" target="_blank"><img :src="imgurl + scope.row.license_url" class="zs-img" /></a>
									<a :href="imgurl + scope.row.user_sfz_url" target="_blank"><img :src="imgurl + scope.row.user_sfz_url" class="zs-img" /></a>
									<a :href="imgurl + scope.row.shop_pic_url" target="_blank"><img :src="imgurl + scope.row.shop_pic_url" class="zs-img" /></a>
								</template>
							</el-table-column>
							<el-table-column prop="operation" label="操作">
								<template slot-scope="scope">
									<el-button type="success" size="small" @click="open(scope.row.id,2)">通过审核</el-button>
									<el-button type="info" size="small" @click="open(scope.row.id,0)">驳回申请</el-button>
								</template>
							</el-table-column>
						</el-table>
					</div>
					<el-dialog title="驳回原因" :visible.sync="dialogVisible" width="30%" :before-close="handleClose">
						<el-input v-model="message"></el-input>
						<span slot="footer" class="dialog-footer">
							<el-button @click="dialogVisible = false">取 消</el-button>
							<el-button type="primary" @click="sub">确 定</el-button>
						</span>
					</el-dialog>
				</template>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	import {Api_url} from "@/common/config.js"
	import Nav from '../components/shop_nav.vue'
	export default{
		components:{
			Nav
		},
		data(){
			return{
				dialogVisible: false,
				imgurl: Api_url,
				tableData: [],
				region_list: [],
				message: '',
				id: '',
				num: '',
			}
		},
		mounted() {
			// this.get_region()
			this.get_all_apply()
		},
		methods: {
			get_region() {
				this.http.get('select_region').then(res => {
					this.region_list = this.set_id_index(res.data)
					console.log(this.region_list)
				})
			},
			//id转index
			set_id_index(data) {
				let arr = []
				for (let k in data) {
					let v = data[k]
					arr[v.id] = v
				}
				return arr;
			},
			sub() {
				this.$confirm('确定操作?', '提示', {
					confirmButtonText: '确定',
					cancelButtonText: '取消',
					type: 'warning'
				}).then(() => {
					this.http.post_show('shops/cms/approve_store', {
						id: this.id,
						state: this.num,
						message: this.message
					}).then(() => {
						this.get_all_apply()
						this.$message({
							type: 'success',
							message: '操作成功!'
						});
						this.message = ''
						this.dialogVisible = false
					})
				})
			},
			handleClose() {
				this.dialogVisible = false
			},
			get_all_apply() {
				this.http.get('shops/cms/apply').then(res => {
					this.tableData = res.data
					console.log(res.data)
				})
			},
			open(id, num) {
				this.id = id
				this.num = num
				if (num == 0) {
					this.dialogVisible = true
				} else {
					this.$confirm('确定操作?', '提示', {
						confirmButtonText: '确定',
						cancelButtonText: '取消',
						type: 'warning'
					}).then(() => {
						this.http.post_show('shops/cms/examine_apply', {
							id: id,
							value: num,
							message: this.message
						}).then(() => {
							this.get_all_apply()
							this.$message({
								type: 'success',
								message: '操作成功!'
							});
						})
					})
				}

			},
		}
	}
</script>

<style lang="less">
	.zs-img {
		width: 60px;
		height: 60px;
		margin: 0 10px;
	}
</style>
