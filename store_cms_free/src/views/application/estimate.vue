<template>
	<div class="estimate">
		<el-container>
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="2"></Nav>
			</el-aside>
			
			<el-main>
				<el-breadcrumb separator-class="el-icon-arrow-right">
					<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
					<el-breadcrumb-item>应用</el-breadcrumb-item>
					<el-breadcrumb-item>营销应用</el-breadcrumb-item>
				</el-breadcrumb>
				<div class="H10"></div>
				<div class="zhang">
					<el-button  @click="jumpback">返回</el-button>
					<div style="height:20px;">&nbsp;</div>
					<div class="xiao">选择商品</div>
					<div class="add">
						<el-tabs type="border-card" style="margin-bottom: 20px" v-model="active_name">
							<el-tab-pane label="第一步：选择商品" name="one">
								<div class="choose">
									<div class="search">
										<!-- <el-select v-model="value" placeholder="所有分组">
											<el-option label="一" value="shanghai"></el-option>
											<el-option label="二" value="beijing"></el-option>
										</el-select>&emsp; -->
										<el-input size="small" style="width: 15%;margin-right: 10px;" placeholder="请输入商品名称" v-model="fx_search_key" @keyup.enter.native="search_fx(fx_search_key)"></el-input>
										<el-button type="primary"size="small" @click="search_fx(fx_search_key)">搜索</el-button>
										<el-button type="success"size="small" @click="reset">重置</el-button>
									</div>
									<el-table :data="tableDataA" style="width: 100%;">
										<el-table-column prop="" label="商品信息">
											<template slot-scope="scope">
												<div class="pro">
													<!-- <div class="pro_01">
														<el-checkbox></el-checkbox>
													</div> -->
													<div class="pro_02"><img :src="$getimg + scope.row.imgs" /></div>
													<div class="pro_03">
														<div class="pro_03_1">{{scope.row.goods_name}}</div>
														<div class="pro_03_2">¥{{scope.row.price}}</div>
													</div>
												</div>
											</template>

										</el-table-column>

										<el-table-column prop="operation" label="操作" width="300px">
											<template slot-scope="scope">
												<el-button v-if="scope.row.is_reseller == 0" type="primary" size="small" @click="join(scope.row,scope.$index)">添加评价</el-button>

												<el-button v-if="scope.row.is_reseller == 1" style="margin-left: 10px" type="danger" size="small" slot="reference" @click="cancel(scope.$index,scope.row.goods_id)">取消</el-button>
											</template>
										</el-table-column>
									</el-table>
								</div>
								<div class="quan">
									<div class="quan_l">
										<el-button style="margin-left: 10px" type="danger" size="small" @click="quanxuan">全选</el-button>
									</div>
									<!-- <div class="quan_r">共{{tableDataA.length}}条，每页10条</div> -->
								</div>
							</el-tab-pane>
							<el-tab-pane label="第二步：设置评价" name="two" v-if="is_two == 1">
								<div class="choose">
									<div class="xiao" style="display:flex;">
										<div style="line-height:40px">每个商品评价的范围数量：</div>
										<el-input v-model="esmInput" style="width:20%;margin-left:15px;" placeholder="如3或3,5" clearable></el-input>
									</div>
								</div>
								<div class="quan">
									<!-- 	<div class="quan_l">
										<el-checkbox v-model="checked"></el-checkbox>&nbsp;全选
									</div> -->
									<!-- <div class="quan_r">共{{tableDataB.length}}条，每页10条</div> -->
								</div>
							</el-tab-pane>
							<template v-if="is_next">
								<el-pagination background layout="prev, pager, next" :total="total"  @current-change="jump_page" :page-size="size">
								</el-pagination>
							</template>
						</el-tabs>

						<span slot="footer" class="dialog-footer ">
							<el-button @click="jumpback" v-if="is_next">取 消</el-button>
							<el-button type="primary" v-if="is_next" @click="next">下一步</el-button>
							<el-button  v-if="!is_next" @click="back">上一步</el-button>
							<el-button type="primary" v-if="!is_next" @click="onSubmit">提 交</el-button>
						</span>
					</div>
				</div>
			</el-main>
				
		</el-container>
	</div>

</template>

<script>
	import {
		Loading
	} from 'element-ui';
	import {
		Api_url
	} from "@/common/config";

	import Nav from './components/app_nav.vue'
	export default {
		data() {
			return {
				fx_search_key:'',
				is_reseller:1,
				is_two:0,
				active_name: 'one',
				is_next: true,
				choose: true,
				price: '',
				obj: {
					id: [],
					num: '',
				},
				checked: '',
				tableDataA: [], //未分销
				tableDataB: {
					ids:[],
					num:'',
				}, //已分销产品
				oid: 0,
				size: 8,
				total: 0,
                value: '',
                esmInput:'',
			}
		},
		watch:{
			active_name(){
				if(this.active_name == 'one'){
					this.is_two = 0
					this.is_next = true
				}
				if(this.active_name == 'two'){
					this.is_two = 1
					this.is_next = false
				}
			}
		},
		components: {
			Nav
		},
		mounted() {
			// this._load();
		},
		methods: {
			_load() { // 请求全部商品
				this.http.post('product/admin/all_goods_info').then(res => {
					for(let k in res.data){
						let v = res.data[k]
						v.is_reseller = 0
					}
					this.all = res.data
					this.total = res.data.length 
					this.tableDataA = res.data.slice(0,this.size)
				})
			},
			search_fx(key){ // 查询搜索
				let arr = []
				for (let k in this.all) {
					let v = this.all[k]
					if(v.goods_name.indexOf(key) > -1 ){
						arr.push(v)
					}
				}
				this.tableDataA = arr
			},
			reset(){ // 重置
				this._load()
				this.fx_search_key = ''
			},
			jump_page(e) { //
				console.log(e)
				const that = this;
				let start = (e - 1) * that.size;
				let end = e * that.size;
				console.log(start, end)
				this.tableDataA = this.all.slice(start, end);
			},
			quanxuan() { // 全选
				if(this.tableDataA!=''){
					// this.tableDataB = this.tableDataA
					console.log('a',this.tableDataA)
					let len=this.tableDataA.length
					let good=[];
					for(let i=0;i<len;i++){
						good.push(this.tableDataA[i].goods_id);
						this.tableDataA[i].is_reseller = 1;
					}
					console.log(good)
					this.tableDataB['ids']=good
					// 进行全选之后将所有发商品id加入到tableDataB中
					this.$message({
						message: '已全部添加',
						type: 'success'
					});
					console.log('b',this.tableDataB)
				}else{
					this.$message({
						message: '请确认商品',
						type: 'warning'
					});
				}
			},
			next() { //上一步
				this.is_two = 1
				this.active_name = 'two'
				this.is_next = false
			},
			back() { // 下一步
				this.is_two = 0
				this.active_name = 'one';
				this.is_next = true;
			},
			join(item,index) { // 单选加入
				this.tableDataA[index].is_reseller = 1;
				const that = this;
				let list=item;
				if (this.tableDataB.ids=='') { // 查询tableDataB中是否有商品id
					this.tableDataB.ids.push(list.goods_id);
					this.$message({
						message: '添加成功',
						type: 'success'
					});
				} else if (this.tableDataB.ids.indexOf(list.goods_id) == -1) { // 返回-1 表示没有
					//判断数组中对象是否重复需要保证对象中值的顺序一致	
					this.tableDataB.ids.push(list.goods_id)
					this.$message({
						message: '添加成功',
						type: 'success'
					});
				} else {
					this.$message({
						message: '请勿重复添加',
						type: 'warning'
					});
				}

			},
			// 取消要添加评价的商品
			cancel(index,id) {
				let goods_id=id;
				if(this.tableDataB.ids!=''){
					let len=this.tableDataB.ids.length;
					let list=this.tableDataB.ids;
					// console.log(list[list.indexOf(goods_id)])
					for(let i=0;i<len;i++){
						if(list.indexOf(goods_id) != -1){
							let n=list.indexOf(goods_id);
							list.splice(n,1);
							console.log(list);
							break;
						}
					}
					this.tableDataA[index].is_reseller = 0;
				}
			},
			// 提交 添加评价
			onSubmit() {
				let num=this.esmInput
				if(num=='' && num<0){
					this.$message({
						message: '范围数值要大于0',
						type: 'warning'
					});
					return false;
				}else if(this.tableDataB.ids==''){
					this.$message({
						message:"请添加商品",
						type:'warning'
					})
					return false;
				}else{
					this.tableDataB['num']=num;
					let arr=this.tableDataB;
					let url='/rate/admin/key_rate';
					this.http.post_show(url,arr).then(res=>{
						if(res.status==200){
							this.$message({
								message:'提交成功',
								type:'success'
							});
							this.$router.push({
								path:'/application'
							})
						}
					}).catch(res=>{
						return res;
					})
				}
			},
			jumpback() {
				this.$router.push({
					path: '/application'
				})
			},
		},
    }
</script>

<style lang="less">
	.estimate {
	
		text-align: left;
        .el-table__row {
			line-height: 40px !important;

			img {
				width: 80px !important;
				height: 80px !important;
			}
		}

		.search {
			text-align: left;
			border-bottom: 1px solid #E6E6E6;
			padding-bottom: 15px
		}

		.xiao {
			padding: 10px 10px 15px 15px;
			text-align: left;
			font-size: 15px;
			font-weight: 600;
		}

		.quan {
			display: flex;
			justify-content: space-between;
			padding: 15px 10px;

			.quan_r {
				font-size: 13px;
			}
		}

		

		.pro {
			display: flex;

			.pro_01 {
				padding-right: 10px;
				display: flex;
				flex-direction: column;
				justify-content: center;
			}

			.pro_02 img {
				height: 60px !important;
				;
				width: 60px !important;
				padding-right: 10px
			}

			.pro_03 {
				display: flex;
				flex-direction: column;
				justify-content: space-between;

				.pro_03_1 {
					color: #2768D7
				}

				.pro_03_2 {
					color: #FF6600
				}
			}
		}


		.add {
			background-color: #fff;
			padding: 15px;
            text-align: center;
			.add_btn {
				padding: 0 0 20px 20px;
				text-align: left;
			}

			.zhang {
				padding-left: 10px
			}

			.ts {
				text-align: left;
				font-size: 12px;
				color: #A6A7A8;
				padding-left: 130px;
				margin-top: -18px;
				padding-bottom: 18px;
			}

			.jian {
				padding-right: 10px
			}

			.line {
				padding: 0 15px;
			}

			.xzsp {
				color: #155BD4;
				text-align: left;
				padding: 0 0 15px 130px;
				margin-top: -20px;
				font-size: 14px;
			}

			.spxz {
				padding: 0 0 25px 130px;
				width: 40%;

				.shiyong {
					display: flex;

					.sy_01 img {
						height: 40px !important;
						;
						width: 40px !important;
						padding-right: 10px
					}

					.sy_02 {
						display: flex;
						flex-direction: column;
						justify-content: space-between;

						.sy_01_1 {
							color: #2768D7
						}

						.sy_01_2 {
							color: #FF6600
						}
					}
				}
			}
		}

		.article {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
			text-align: left;
        }
        .list-head {
			padding-bottom: 10px
		}

		.el-form-item__content {
			display: flex;
			justify-content: flex-start;
		}

	}
</style>
