<template>
    <div class="print">
        <el-container>
			<el-aside  width="160px" class="not-print bg-white erbian">
				<Nav ac="1"></Nav>
			</el-aside>
            <el-main >
                <template>
					<el-breadcrumb separator-class="el-icon-arrow-right">
						<el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
						<el-breadcrumb-item>订单</el-breadcrumb-item>
						<el-breadcrumb-item>订单列表</el-breadcrumb-item>
					</el-breadcrumb>
					<div class="H10"></div>
                    <div class="H10"></div>
					<div class="print_order">
                        <div class="print_btn">
                            <el-button type="primary" size="small" @click="jumpback">返回</el-button>
                            <el-button type="success" size="small" v-print="'#printf'">打印</el-button>
                        </div>
                        <template>
                            <!-- <div id="printf" v-for="(item,index) of list" :key="index"> -->
                            <div id="printf" :data="list">
                                <el-row type="flex" justify="center">
                                        <el-col :span="24" class="border_tl border_r col_flex">
                                            <template>
                                                <div style="flex:1;">
                                                    <img src="" alt="">
                                                </div>
                                            </template>
                                            <template>
                                                <div class="print_tit">
                                                    {{shop_name}}采购订单
                                                </div>
                                            </template>
                                            <template>
                                                <div style="flex:1;"></div>
                                            </template>
                                        </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">订单编号</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad" style="overflow:hidden;">{{list.order_num}}</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">下单时间</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad">{{list.update_time}}</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl border_r col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">送货时间</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad"></div>
                                        </template>
                                    </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">客户名称</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad">{{list.users.nickname ||''}}</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">预期时间</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad"></div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl border_r col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">送货人</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad"></div>
                                        </template>
                                    </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">收货人姓名</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad">{{list.receiver_name}}</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">联系方式</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad">{{list.receiver_mobile}}</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="8" class="border_tl border_r col_flex">
                                        <template>
                                            <div class="print_col_1 print_pad">送货人电话</div>
                                        </template>
                                        <template>
                                            <div class="print_col_2 print_pad"></div>
                                        </template>
                                    </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                    <el-col :span="3" class="border_tl">
                                        <template>
                                            <div class="print_col_1 border_r" style="padding:10px 2px;">收货人地址</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="22" class="border_tl border_r" style="border-left:none;">
                                        <template>
                                            <div class="print_pad">{{list.receiver_city+list.receiver_address}}</div>
                                        </template>
                                    </el-col>
                                    <!-- <el-col :span="4" class="border_tl border_r" style="border-left:none;"></el-col> -->
                                </el-row>
                                
                                <el-row type="flex" justify="center">
                                        <el-col :span="6" class="border_tl">
                                            <template>
                                                <div class="print_pad">上架商品名称</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad">规格</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad">数量</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad">单价（元）</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad">单位</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl border_r">
                                            <template>
                                                <div class="print_pad">金额（元）</div>
                                            </template>
                                        </el-col>
                                </el-row>

                                <el-row type="flex" justify="center" v-for="(item,index) in list.ordergoods" :key="index">
                                        <el-col :span="6" class="border_tl">
                                            <template>
                                                <div class="print_pad">{{item.goods_name}}</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad"></div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad">{{item.num}}</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad">{{item.price}}</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad"></div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl border_r">
                                            <template>
                                                <div class="print_pad">{{item.total_price}}</div>
                                            </template>
                                        </el-col>
                                </el-row>

                                <el-row type="flex" justify="center" >
                                        <el-col :span="6" class="border_tl">
                                            <template>
                                                <div class="print_pad">合计</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad">/</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="3" class="border_tl">
                                            <template>
                                                <div class="print_pad">/</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad">/</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl">
                                            <template>
                                                <div class="print_pad">/</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl border_r">
                                            <template>
                                                <div class="print_pad">{{list.order_money}}</div>
                                            </template>
                                        </el-col>
                                </el-row>
                                
                                <el-row type="flex" justify="center">
                                    <el-col :span="12" class="border_tl">
                                        <template>
                                            <div class="print_pad">当前最后一页/共1页</div>
                                        </template>
                                    </el-col>
                                    <el-col :span="12" class="border_tl border_r col_flex">
                                        <template>
                                            <div class="print_pad" style="text-align:left;width:30%;padding-left:30px;">
                                                打印时间
                                            </div>
                                        </template>
                                        <template>
                                            <div class="print_pad" style="width:70%;textAlign:left;marginLeft:30px;" >{{this.print_time}}</div>
                                        </template>
                                    </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                        <el-col :span="6" class="border_tl col_flex">
                                            <template>
                                                <div class="border_r print_pad" style="width:40%;">总价</div>
                                                <div class="print_pad" style="width:60%;">{{list.order_money}}元</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="6" class="border_tl">
                                            <template>
                                                <div class="print_pad">金额大写</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="12" class="border_tl border_r">
                                            <template>
                                                <div class="print_pad" >{{list.order_money|big_money}}</div>
                                            </template>
                                        </el-col>
                                </el-row>

                                <el-row type="flex" justify="center">
                                        <el-col :span="12" class="border_tl col_flex border_bt">
                                            <template>
                                                <div class="border_r" style="width:20%;padding:10px 5px;">供应商名称</div>
                                                <div class="print_pad" style="width:80%;"></div>
                                            </template>
                                        </el-col>
                                        <el-col :span="4" class="border_tl border_bt">
                                            <template>
                                                <div class="print_pad">供应商电话</div>
                                            </template>
                                        </el-col>
                                        <el-col :span="8" class="border_tl border_r border_bt">
                                            <template>
                                                <div class="print_pad"></div>
                                            </template>
                                        </el-col>
                                </el-row>
                            </div>
                        </template>
                    </div>
				</template>
            </el-main>
			
        </el-container>
    </div>
</template>

<script>
import Nav from './components/order_nav.vue'

export default {
    data(){
        return{
            list:[],
            print_time:'', //时间
            index:this.$route.query.id,
        }
    },
    components:{
        Nav
    },
    mounted(){
        this._load();
        this.nowDate();
    },
    methods:{
        _load(){
            this.http.post('/get_order_one',{
                id:this.index
            }).then(res=>{
                this.list=res.data.order;
            })
        },
        jumpback(){
            this.$router.push({
                path:'./order_list'
            })
        },
        nowDate() {
            let dd=new Date();
            let Y = dd.getFullYear();
			let M = dd.getMonth() + 1;
            let D = dd.getDate();
            let h=dd.getHours();
            let m=dd.getMinutes();
            let s=dd.getSeconds();
			M = M < 10 ? "0" + M : M;
            D = D < 10 ? "0" + D : D;
            h = h < 10 ? "0" + h : h;
            m = m < 10 ? "0" + m : m;
            s = s < 10 ? "0" + s : s;
            this.print_time = Y + "-" + M + "-" + D +" "+ h +":"+ m +":"+ s;
            console.log(this.print_time);
		},
    },
}
</script>

<style lang="less">
        .print_btn{
            display: flex;
            flex-wrap: nowrap;
            justify-content: space-between;
            padding: 10px 15px;
            margin-bottom: 30px;
            background-color: #fff;
        }
        #printf{
            text-align: center;
            background-color: #fff;
            line-height: 30px;
            padding: 15px;
            width: 900px;
            height: auto;
            margin: 0 auto;
            font-size: 14px;
            
            .print_tit{
                flex: 2;
                line-height:60px;
                text-align:center;
                font-weight:700;
                font-size:16px;
            }
            .col_flex{
                display: flex;
            }
            
            .print_col_1{
                flex:1
            }
            .print_col_2{
                border-left: 1px solid #bdbdbd;
                flex:2;
            }

            .print_pad{
                padding: 10px;
            }

            .grid-content{
                min-width: 40px;
            }

            .border_tl{
                border-top: 1px solid #bdbdbd;
                border-left: 1px solid #bdbdbd;

            }
            .border_r{
                border-right: 1px solid #bdbdbd;
            }
            .border_bt{
                border-bottom: 1px solid #bdbdbd;
            }
        }

</style>