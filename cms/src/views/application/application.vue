<template>
<div>
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
					<div class="zhang">
						<div class="ying">
							<Zhishi :type="type" :list="list"></Zhishi>
							<div class="biao">工具类</div>
							<div class="gong">
								<div class="gong-01" @click="jump_fx">
									<div class="gong-01-bor">
										<div class="gong-01-l color4">
											<img src="../../img/fenxiao1.png" />
										</div>
										<div class="gong-01-r">
											分销<br/>
											<span>分销</span>
										</div>
									</div>
								</div>
								<div class="gong-01" @click="jump_zdy">
									<div class="gong-01-bor">
										<div class="gong-01-l color1">
											<img src="../../img/biao.png" />
										</div>
										<div class="gong-01-r">
											自定义表单<br/>
											<span>自定义表单</span>
										</div>
									</div>
								</div>
								<div class="gong-01" @click="jump_pt">
									<div class="gong-01-bor">
										<div class="gong-01-l color2">
											<img src="../../img/pintuan.png" />
										</div>
										<div class="gong-01-r">
											拼团<br/>
											<span>拼团</span>
										</div>
									</div>
								</div>
								<div class="gong-01"  @click="jump_jfqd">
									<div class="gong-01-bor">
										<div class="gong-01-l color3">
											<img src="../../img/jifen.png" />
										</div>
										<div class="gong-01-r">
											积分签到<br/>
											<span>积分签到</span>
										</div>
									</div>
								</div>
								
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l color5">
											<img src="../../img/quyu.png" />
										</div>
										<div class="gong-01-r">
											区域代理<br/>
											<span>区域代理</span>
										</div>
									</div>
								</div>
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l">
											<img src="../../img/qun.png" />
										</div>
										<div class="gong-01-r">
											消息群发<br/>
											<span>消息群发</span>
										</div>
									</div>
								</div>
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l color6">
											<img src="../../img/zhibo.png" />
										</div>
										<div class="gong-01-r">
											小程序直播<br/>
											<span>小程序直播</span>
										</div>
									</div>
								</div>
								<div class="kong"></div>
								<div class="kong">	</div>
							</div>
							<div class="biao">营销类</div>
							<div class="gong">
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l color7">
											<img src="../../img/shu.png" />
										</div>
										<div class="gong-01-r">
											种树<br/>
											<span>种树</span>
										</div>
									</div>
								</div>
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l color8">
											<img src="../../img/zhuan.png" />
										</div>
										<div class="gong-01-r">
											大转盘<br/>
											<span>大转盘</span>
										</div>
									</div>
								</div>
								<div class="gong-01">
									<div class="gong-01-bor">
										<div class="gong-01-l ">
											<img src="../../img/jiu.png" />
										</div>
										<div class="gong-01-r">
											九宫格<br/>
											<span>九宫格</span>
										</div>
									</div>
								</div>
								
								<div class="kong"></div>
								<div class="kong">	</div>
							</div>
						</div>
					</div>
				</template>
				
			</el-main>
		</el-container>
</div>
</template>

<script>
	import Nav from './components/app_nav.vue'
	import Zhishi from '@/components/zhishi.vue'
	export default {
		name: "",
		data() {
			return {
				list:[
					"免费版无营销应用",
					"部分营销应用为saas版",
					"更多营销应用开发中，敬请期待",
				],
				type:"default"	
			}
		},
		components:{
			Zhishi,
			Nav
		},
		mounted() {
			
		},
		methods: {		
			jump_fx(){
				this.$router.push({
					path: '/application/marketing/distribution'
				})
			},
			jump_zdy(){
				this.$router.push({
					path: '/application/marketing/customize_form'
				})
			},
			jump_jfqd(){
				this.$router.push({
					path: '/application/marketing/integral'
				})
			},
			jump_pt(){
				this.$router.push({
					path: '/application/marketing/assemble'
				})
			}
		},
	};
</script>

<style lang="less" >
.bg-main{background-color: #F5F7F9;margin: 0px;padding: 20px;}
	.ying{background-color: #fff;font-size: 14px;padding: 10px 10px 120px;
		.biao{padding: 10px;text-align: left;}
	}
	.gong{display: flex;flex-wrap: wrap;justify-content: space-between;padding: 0 10px;
		.gong-01{width: 23%;border: 1px solid #F0F0F0;border-radius: 5px;padding: 10px 10px 20px;margin-bottom: 15px;
			.gong-01-bor{border-bottom: 1px solid #E8E8E8;padding:10px 0 5px;display: flex;
				.gong-01-l{background-color: #25B6D3;height: 80px;width: 80px;text-align: center;border-radius: 5px;
					img{width: 50px;height: 50px;margin-top: 15px;}
				}
				.gong-01-r{padding-left: 10px;font-weight: 600;line-height: 25px;
					span{color: #807C80;font-size: 13px;font-weight: 300;}
				}
			}
		}
		.kong{width: 24%;height: 0;}
		.color1{background-color: #F15D5B !important;}
		.color2{background-color: #96EBFF !important;}
		.color3{background-color: #FFBF00 !important;}
		.color4{background-color: #BCF2D8 !important;}
		.color5{background-color: #198CC3 !important;}
		.color6{background-color: #2FBDA5 !important;}
		.color7{background-color: #BFF5F0 !important;}
		.color8{background-color: #FABFC9 !important;}
	}
</style>
