<template>
	<div class="root">
		<el-container>
			<el-aside width="160px" class="not-print bg-white erbian">
				<Nav ac="3"></Nav>
			</el-aside>

			<el-main class="main">
				<el-breadcrumb separator-class="el-icon-arrow-right">
					<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
					<el-breadcrumb-item>商品</el-breadcrumb-item>
					<el-breadcrumb-item>运费模板</el-breadcrumb-item>
				</el-breadcrumb>
				<div class="H10"></div>
				<div class="add">
					<div class="add_btn">
						<el-button type="primary" size="small" @click="jumpback">返回</el-button>
					</div>

					<el-form ref="form" :model="form" label-width="120px" class="demo-dynamic">
						<el-form-item label="模板名称">
							<el-input v-model="add_form.name" hide-required-asterisk style="width:500px"></el-input>
						</el-form-item>
						<el-form-item label="发货地区">
							<el-cascader :placeholder="default_address" style="color: #000000;" :props="{ value:'label' }" v-model="add_form.fa_address" :options="fa_data" @change="handleChange"></el-cascader>
						</el-form-item>
						<el-form-item label="模板类型" prop="resource">
							<el-radio-group v-model="add_form.tag">
								<el-radio label="同城">同城</el-radio>
								<el-radio disabled label="全国">全国</el-radio>
							</el-radio-group>
						</el-form-item>
					</el-form>
					<el-table :data="add_form.rules" border style="width: 47%;margin-left: 50px;margin-bottom: 20px;">
					
							<el-table-column prop="date" label="首件" width="180">
								<template slot-scope="scope">
									<el-input v-model="scope.row.first"></el-input>
								</template>
								
							</el-table-column>
							<el-table-column prop="name" label="运费" width="180">
								<template slot-scope="scope">
									<el-input v-model="scope.row.first_fee"></el-input>
								</template>
							</el-table-column>
							<el-table-column prop="address" label="续件" width="180">
								<template slot-scope="scope">
									<el-input v-model="scope.row.additional"></el-input>
								</template>
							</el-table-column>
							<el-table-column prop="address" label="运费" width="180">
								<template slot-scope="scope">
									<el-input v-model="scope.row.additional_fee"></el-input>
								</template>
							</el-table-column>
						
					</el-table>
					<!-- <div class="xiao">
						<div class="xiao_l">配送区域</div>
						<div class="xiao_r">
							<template>
								<el-table :data="list_show" border style="width: 100%">
									<el-table-column type="index" label="序号" width="50px"></el-table-column>

									<el-table-column prop="region" label="可配送区域" width="550px">
										<template slot-scope="scope">
											<template v-for="(item,index) of scope.row.region">
												{{city_list[item]}}
											</template>
										</template>
									</el-table-column>

									<el-table-column prop="first" label="首件（个）" width="100px">
										<template slot-scope="scope">
											<el-input v-model="scope.row.first" placeholder="请输入"></el-input>
										</template>

									</el-table-column>
									<el-table-column prop="first_fee" label="运费（元）" width="100px">
										<template slot-scope="scope">
											<el-input v-model="scope.row.first_fee" placeholder="请输入"></el-input>
										</template>
									</el-table-column>
									<el-table-column prop="additional" label="续件（个）" width="100px">
										<template slot-scope="scope">
											<el-input v-model="scope.row.additional" placeholder="请输入"></el-input>
										</template>
									</el-table-column>
									<el-table-column prop="additional_fee" label="续费（元）" width="100px">
										<template slot-scope="scope">
											<el-input v-model="scope.row.additional_fee" placeholder="请输入"></el-input>
										</template>
									</el-table-column>
									<el-table-column prop="operation" label="操作" width="300px">
										<template slot-scope="scope">

											<el-button style="margin-left: 10px" type="danger" size="small" slot="reference" @click="del(scope.$index)">删除</el-button>
										</template>
									</el-table-column><strong></strong>
								</el-table>
								<div class="zhi">
									<el-button type="primary" @click="setCheckedNodes">指定可配送区域和运费</el-button>
								</div>
							</template>
						</div>
					</div> -->


					<span slot="footer" class="dialog-footer " style="margin-left: 50px;">
						<el-button @click="jumpback">取 消</el-button>
						<el-button type="primary" v-if="type == 'edit'" @click="sub_edit">提交修改</el-button>
						<el-button type="primary" v-else @click="onSubmit">确 定</el-button>
					</span>
				</div>
			</el-main>
		</el-container>
		<!-- 添加弹出框 -->
		<el-dialog title="选择配送区域" :visible.sync="dialogVisibleadd" width="30%">
			<body style="height: 300px; overflow: scroll;">
				<el-tree :data="data" show-checkbox node-key="id" ref="tree" highlight-current :props="defaultProps" @check-change="handleCheckChange"
				 :accordion="true" :check-on-click-node="true" :render-after-expand="false">
				</el-tree>
			</body>
			<span slot="footer" class="dialog-footer">
				<el-button @click="cancel">取 消</el-button>

				<el-button type="primary" @click="getCheckedNodes">确 定</el-button>
			</span>
		</el-dialog>
	</div>
</template>

<script>
	import City from '@/common/city' //城市
	import fa_address from '@/common/fa_address' //城市
	import Nav from '../components/shop_nav.vue'
	export default {
		data() {
			return {
				default_address:'请选择',
				tableData:[1],
				type: '',
				city_list: [],
				arr: [],
				choose: [],
				input: '',
				size: 10,
				total: '',
				list: [],
				list_show: [],
				dialogVisibleadd: false,
				data: City,
				fa_data: fa_address,
				defaultProps: {
					children: 'children',
					label: 'label'
				},
				add_form: {
					"name": "",
					"fa_address": "北京市市辖区东城区",
					"tag": "1",
					"rules": [{
						"first": "",
						"first_fee": "",
						"additional": "",
						"additional_fee": ""
					}]
				},
				form: {
					name: '',
					method: '',
					sort: 100,
					rule: {
						region: [],
						first: [],
						first_fee: [],
						additional: [],
						additional_fee: []
					}
				},
				fa_address:''
			}
		},
		components: {
			Nav
		},
		mounted() {
			this.type = this.$route.query.type
			this.eid = this.$route.query.id
			if (this.type == 'edit') {
				this.get_edit_data(this.$route.query.id)
			}
			this.get_all_city()
		},
		methods: {
			handleChange(label) {
				console.log(label);
				
			},
			sub_edit() {
				this.http.post_show('delivery/delivery_edit', this.add_form).then(res => {
					this.$message({
						message: '修改成功',
						type: 'success'
					});
					this.jumpback()
				})
			},
			get_edit_data(id) {
				this.http.get('/delivery/delivery_detail?id='+id).then(res=>{
					this.add_form = res.data
					this.default_address = res.data.fa_address
				})
				// let data = JSON.parse(localStorage.getItem('edit_data'))
				// console.log(data)
				// this.add_form.id = data.id
				// this.add_form.method = data.method
				// this.add_form.name = data.name
				// this.form.rule = data.rule
				// this.list_show = data.rule
				// this.list = data.rule
				// console.log(this.form)
			},
			//
			get_all_city() {
				let arr = []
				for (let k in City) {
					let v = City[k]
					arr[v.id] = v.label
					if (v.children) {
						for (let g in v.children) {
							let h = v.children[g]
							arr[h.id] = h.label
						}
					}
				}
				this.city_list = arr
			},
			show_key() {

			},
			handleCheckChange(data, checked, indeterminate) {
				console.log(data, checked, indeterminate);
				// let arr = []
				if (checked) {
					if (data.children) {
						this.arr.push(data)
						this.choose = this.arr
					} else {
						this.choose.push(data)
					}

				} else {
					for (let k in this.choose) {
						if (this.choose[k].id == data.id) {
							this.choose.splice(k, 1)
						}
					}
				}
				console.log(this.choose)

			},
			jumpback() {
				this.$router.push({
					path: './template'
				})
			},
			getCheckedNodes() {
				// console.log(this.$refs.tree.getCheckedNodes());
				this.dialogVisibleadd = false
				let obj = {
					region: [],
					first: '',
					first_fee: '',
					additional: '',
					additional_fee: '',
				}
				let obj_b = {
					region: [],
					first: '',
					first_fee: '',
					additional: '',
					additional_fee: '',
				}
				console.log(this.choose)
				for (let k in this.choose) {
					let v = this.choose[k]
					obj.region.push(v.id)
					obj_b.region.push(v.label + '、')
				}
				this.list.push(obj)
				// this.list_show.push(obj_b)
				// this.$refs.tree.setCheckedKeys([]);
				console.log(this.list)
				this.list_show = this.list
				this.form.rule = this.list
			},
			setCheckedNodes() {
				this.dialogVisibleadd = true
				this.$refs.tree.setCheckedNodes([])
			},
			// setCheckedKeys() {
			// 	this.$refs.tree.setCheckedKeys([]);

			// },
			onSubmit() {
				
				console.log(this.add_form)
				let data = {
					"name": this.add_form.name,
					"fa_address": '',
					"tag": "1",
					"rules": [{
						"first": this.add_form.rules[0].first,
						"first_fee": this.add_form.rules[0].first_fee,
						"additional": this.add_form.rules[0].additional,
						"additional_fee": this.add_form.rules[0].additional_fee,
						}]
				}
				for(let k in this.add_form.fa_address){
					data.fa_address += this.add_form.fa_address[k]
				}
				console.log(data)
				
				// this.form.rule = this.list
				// for (let k in this.list_show) {
				// 	let v = this.list_show[k]
				// 	this.list[k].first = v.first
				// 	this.list[k].first_fee = v.first_fee
				// 	this.list[k].additional = v.additional
				// 	this.list[k].additional_fee = v.additional_fee
				// }
				this.http.post_show('/delivery/delivery_add', data).then(res => {
					this.$message({
						message: '成功添加运费模板',
						type: 'success'
					});
					// this.list = []
					// this.list_show = []
					this.jumpback()
				})
			},
			del(index) {
				this.list_show.splice(index, 1)
				this.list = this.list_show
			},
			cancel() {
				this.dialogVisibleadd = false
				this.$refs.tree.setCheckedNodes([])
			}
			//获取
			// get_template() {
			// 	this.http.get('delivery /admin/get_delivery').then(res => {
			// 		this.list = res.data
			// 	})
			// },
		}
	}
</script>

<style lang="less">
	.add {
		line-height: 30px;
		background-color: #fff;
		text-align: left;
		margin-top: 10px;
		padding: 25px;

		.add_btn {
			padding: 5px 0 20px 0px;
			text-align: left;
		}

		.xiao {
			display: flex;
			width: 100%;

			.xiao_l {
				flex-shrink: 0;
				width: 110px;
				text-align: right;
				font-size: 13px;
				padding-right: 10px;
				color: #606266;
			}

			.zhi {
				margin: 10px 0 30px;
			}
		}
	}
</style>
