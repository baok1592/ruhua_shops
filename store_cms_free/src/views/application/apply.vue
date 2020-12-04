
<!--测试数据-->
<template>
	<div class="apply">
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
                <div class="article">
                    <el-button  @click="jumpback">返回</el-button> &emsp;<el-button type="primary" v-if="show==false" @click="increase">安装</el-button>
                    <div style="height:20px;">&nbsp;</div>
                    <!-- 进度条 -->
                    <div class="bar" v-show="show==false"> 
                        <!-- elementui组件 -->
                        <template>
                            <el-progress :percentage="percentage" :stroke-width="10" :color="customColorMethod"></el-progress>
                        </template>

                        <template v-if="progsuc==1">
                            <div style="width:100%;text-align:center;margin-top:60px;">
                                <el-button style="width:10%" type="primary" @click="jumpback">已完成</el-button> 
                            </div>
                        </template>
                        <template v-else-if="progsuc==0">
                            <div style="width:100%;text-align:center;margin-top:60px;">
                                <el-button style="width:10%" type="success">安装中</el-button> 
                            </div>
                        </template>
                        <template v-else>
                            <div style="width:100%;text-align:center;margin-top:60px;">
                                <el-button style="width:10%" type="info">未安装</el-button> 
                            </div>
                        </template>
                    </div>
                    <!--已完成-->
                    <div class="fix" v-show="show==true">
                        <img src="@/img/success2.png" alt="">
                        <div style="width:100%;text-align:center;margin-top:5px;">
                            <span style="display:inline-block;fontSize:14px;color:rgb(5,222,208)" @click="jumpback">已完成</span> 
                        </div>
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
                percentage: 0,
                customColor: '#409eff',
                customColors: [
                    {color: '#f56c6c', percentage: 20},
                    {color: '#e6a23c', percentage: 40},
                    {color: '#5cb87a', percentage: 60},
                    {color: '#1989fa', percentage: 80},
                    {color: '#6f7ad3', percentage: 100}
                ],
                progclass:false,
                progsuc:-1,
                show:false,
                cone:false,
			}
		},
		components: {
			Nav
        },
        mounted(){
            this._load()
        },
		methods: {
			//返回
			jumpback() {
				this.$router.push({
					path: '/application'
				})
            },
            customColorMethod(percentage) {
                if (percentage < 30) {
                    return '#909399';
                } else if (percentage < 70) {
                    return '#e6a23c';
                } else {
                    return '#67c23a';
                }
            },
            // 安装
            increase() { 
                this.progsuc=0
                let prog=setInterval(_=>{ 
                    this.percentage+=10;
                    if (this.percentage > 100) {
                        this.percentage = 100;
                        clearInterval(prog)
                        this.progsuc=1
                        this.show=true
                        this.disabled=true
                    }
                    if(this.cone!=true){
                        if(this.percentage > 90) {
                            this.percentage = 90;
                            this.progsuc=0
                            clearInterval(prog)
                        }
                    }
                },400);
                this.http.get_show('/index/admin/import').then(res=>{
                    if(res.status==200){
                        this.cone=true
                    }else{
                        this.cone=false
                    }
                });
            },
            // 检查是否安装
            _load(){ 
                this.http.get_show('plugIn/is_Install?lock=import_plug').then(res=>{
                    if(res.msg=="没有权限"){
                        this.show=false
                        this.$router.push({
                            path: '/application'
                        })
                        this.$message({
                            message:'没有权限',
                            type:'warning',
                        });
                    }else{
                        if(res.status==200){
                            if(res.data==true){
                                this.show=true
                            }else{
                                this.show=false
                            }
                        }
                    }
                })
            },

        },
    }
</script>

<style lang="less">
	.apply {
		background-color: #F3F3F3;

		.el-main {
			height: auto !important;
			background-color: #F3F3F3;
            padding: 15px;
            
		}

		.article {
			line-height: 30px;
			background-color: #fff;
			padding: 15px;
            text-align: left;
            // 进度条
            .bar{
                padding: 10px 15px;
                border: 1px solid #F0F0F0;
                padding-top:60px;
            }

            .fix{
                padding: 10px 15px;
                border: 1px solid #F0F0F0;
                padding-top:60px;
                text-align: center;
            }
        }
        [v-cloak]{
            display: none;
        }
	}
</style>