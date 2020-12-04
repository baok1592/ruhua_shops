<template>
	<view class="coupons">
		<block v-for="(item,index) of list" :key="index" v-if="leixing=='a'">
			<view class='coupon'>
				<view class='cou_t'>
					<view class='cou_t_l'>
						<view :class='class_name'><span>¥</span>{{Math.floor(item.reduce)}}</view>
						<!-- <view class='cou_t_l_02'>无使用门槛最多优惠12元</view> -->
					</view>
					<view class='cou_t_r'>
						<view class='cou_t_r_01' v-if="item.full==0">减{{Math.floor(item.reduce)}}</view>
						<view class='cou_t_r_01' v-else>满{{Math.floor(item.full)}} 减{{Math.floor(item.reduce)}}</view>
						<view class='cou_t_r_02'>有效期：{{item.end_time}}</view>
					</view>
					<view :class='btn_name' v-if="item.status==0">
						<view @click="jump_to(item.goods_ids)">{{types==2?'去使用':'领取'}}</view>
					</view>
				</view>
				<block v-if="item.goods_ids == 0">
					<view class='cou_d'>所有商品可用</view>
				</block>
				<block v-else>
					<view class='cou_d'>指定商品可用</view>
				</block>
		
				<view class="ysy" v-if="item.status==1"><img src="@/static/imgs/yi.png"></img></view>
				<view class="ysy" v-if="item.status==3"><img src="@/static/imgs/late.png"></img></view>
			</view>
		</block>
		<view class="con " v-if="leixing=='b'">
			<block v-for="(item,index) of list" :key="index" >
				
					<view class="quan  bg-yellow" :style="{'background-color':color }"  v-if="item.status==0 ">
						<view class="quan_l ">
							<view class="quan_l_01 qy-text-yellow" :style="{'color':color }">
								<span>{{Math.floor(item.reduce)}}</span> ¥
							</view>
							<view class="quan_l_02 qy-text-grey">
								<view class="quan_l_02_des">{{item.des}}</view>
								<view class="time">有效期{{item.end_time}}</view>
							</view>
							<view class="yuan-gary"></view>
						</view>
						<view class="quan_r qy-text-white" >
							<block v-if="types==2 ">立<br/>即<br/>使<br/>用</block>
							<block v-else>立<br/>即<br/>领<br/>取</block>
							<view class="yuan-yellow bg-yellow"  :style="{'background-color':color }"></view>
						</view>
						
					</view>
					<view class="quan  qy-bg-grey"   v-if="item.status!=0 ">
						<view class="quan_l ">
							<view class="quan_l_01 qy-text-yellow" :style="{'color':color }">
								<span>{{Math.floor(item.reduce)}}</span> ¥
							</view>
							<view class="quan_l_02 qy-text-grey">
								<view class="quan_l_02_des">{{item.des}}</view>
								<view class="time">有效期{{item.end_time}}</view>
							</view>
							<view class="yuan-gary"></view>
						</view>
						
						<view class="quan_r qy-text-grey"  >
							
							<block v-if="types!=2 && item.status==1">已<br/>领<br/>取</block>
							<block v-if="types!=2 && item.status==3">已<br/>过<br/>期</block>
							
							<block v-if="types==2 && item.status==1">已<br/>使<br/>用</block>
							<block v-if="types==2 && item.status==3">已<br/>过<br/>期</block>
							<view class="yuan-yellow qy-bg-grey" ></view>
						</view>
					</view>
				
			</block>
			
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		props:{
			leixing:{
				type:String,
				default:'b'
			},
			list:Array,
			status:Number,
			class_name:String,
			btn_name:String,
			color:String,
			types:{
				type:Number,
				default:2
			}
		},
		components: {
		},
		methods:{
			close_m(){
				this.$emit('close_m')
			}
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.coupons{font-size: 14px;
	.coupon {
			margin: 15px;
			background-color: #fff;
			border: 1px solid #EEEEEE;
			border-radius: 5px;
			box-shadow: 2px 2px 2px #EEEEEE;
			color: #9FA0A2;
			min-height: 10px;
			position: relative;

			.ysy {
				position: absolute;
				right: 10px;
				top: 0;

				img {
					width: 80px;
					height: 80px;
					z-index: 99;
				}
			}
			.cou_t {
				display: flex;
				padding: 20px 10px 10px;
				justify-content: space-between;
				font-size: 12px;
				width: 100%;
				box-sizing: border-box;
			}
			
			.cou_t_l {
				width: 20%;
				flex-shrink: 0;
			}
			
			.cou_t_r {
				width: 55%;
				box-sizing: border-box;
				padding-left: 10px;
				flex-grow: 1;
			}
			
			.cou_t_rr {
				width: 70px;
				background-color: #EB113E;
				color: #fff;
				height: 25px;
				line-height: 25px;
				font-size: 12px;
				text-align: center;
				margin: 15px 0 0 10px;
				border-radius: 20px;
			}
			
			.btn_grey {
				width: 70px;
				background-color: #9FA0A2;
				color: #fff;
				height: 25px;
				line-height: 25px;
				font-size: 12px;
				text-align: center;
				margin: 15px 0 0 10px;
				border-radius: 20px;
			}
			
			.cou_t_l_01 {
				color: #FF4444;
				font-size: 26px;
				padding-top: 8px;
			}
			
			.cou_t_l_01 span {
				font-size: 12px;
			}
			
			.cou_sss {
				font-size: 26px;
				padding-top: 8px;
			}
			
			.cou_sss span {
				font-size: 12px;
			}
			
			.cou_t_r_01 {
				font-size: 18px;
				color: #323233;
				padding: 3px 0 5px;
			}
			
			.cou_d {
				background-color: #FAFAFA;
				padding: 6px 15px;
				border-top: 1px dashed #EBEDF0;
				font-size: 12px;
			}
			
		}
	.con{padding: 20px;
		.quan{display: flex;border-radius: 8px;height: 94px;margin-bottom: 15px;
			.quan_l{border-radius: 7px;width: 85%;display: flex;padding: 10px;box-sizing: border-box;position: relative;
			background-color: #fff;
				.quan_l_01{width: 55%;text-align: center;padding-top: 10px;border-right: 1px solid #F4EFF3;
					span{font-size: 48px;padding-right: 5px;}
				}
				.quan_l_02{font-size: 12px;width: 45%;padding-top: 15px;display: flex;flex-direction: column;
				align-items: center;
					.quan_l_02_des{height: 34px;line-height: 17px;padding: 0 15px;}
					.time{padding-top: 6px;text-align: center;}
				}
				.yuan-gary{position: absolute;width: 20px;height:20px;border-radius: 50%;left: -10px;top: 37px;
				background-color: #F8F8F8;}
			}
			.quan_r{font-size: 12px;width: 15%;position: relative;display: flex;flex-direction: column;
			justify-content: center;align-items: center;
				.yuan-yellow{position: absolute;width: 20px;height:20px;border-radius: 50%;left: -10px;top: 37px;}
			}
		}
		.bg-yellow{background-color: #FA436A;}
		.qy-bg-grey{background-color: #EDECF2;}
		.qy-text-yellow{color: #FA436A;}
		.qy-text-white{color: #fff;}
		.qy-text-grey{color: #B3ADA9;}
		
	}
}
</style>
