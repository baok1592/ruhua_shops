<template>
	<view class="tancoupon">
		<view class="mask" :class="maskState===0 ? 'none' : maskState===1 ? 'show' : ''" >
			<view class="mask-content">
				<!-- 优惠券页面，仿mt -->
				<scroll-view class="scroll" scroll-y>
					<view class="tab">
						<view class="tab-l" @click="num(0)"><span :class="tab?'':'boe-dott'">可用优惠券（1）</span></view>
						<view class="tab-l" @click="num(1)"><span :class="tab?'boe-dott':''">不可用优惠券（1）</span></view>
					</view>
					
					<Coupon leixing="a" :list="c_list"  :btn_name="btn_name" :class_name="class_name"></Coupon>
					
				</scroll-view>
				<view class="btn" @click="cancel_use">不使用优惠券</view>
			</view>
		</view>
		
	</view>
</template>

<script>
	import Coupon from "@/components/qy/coupontwo";
	export default {
		data() {
			return {
				btn_name: 'cou_t_rr',
				tab:0,
				class_name: 'cou_t_l_01',
				c_list:''
			};
		},
		props:{
			maskState:String,
			list:String
		},
		components:{
			Coupon
		}, 
		watch:{ 
			'list': function(newVal){
				console.log("watch")
				this.num(0)
			}
		},
		methods:{
			num(e) {
				console.log('ssss',this.list)
				if (e == 0) {  
					let arr=[]
					let x=0
					for(let k in this.list){
						let v=this.list[k]
						if(v.status==0){
							arr[x]=v
							x++
						}
					}
					this.c_list=arr
					
					this.tab = e
					this.class_name = "cou_t_l_01"
					this.btn_name = "cou_t_rr"
					
				}
				if (e == 1) {
					let arr=[]
					let x=0
					for(let k in this.list){
						let v=this.list[k]
						if(v.status!=0 ){
							arr[x]=v
							x++
						}
					}
					this.c_list=arr
					this.tab = e
					this.class_name = "cou_sss"
					this.btn_name = "btn_grey"
				}
				
			},
			//取消使用优惠券
			cancel_use() {
				this.maskState = 0
			},
		},
		
	}
</script>

<style lang="less">
.tancoupon{font-size: 14px;
	/* 优惠券面板 */
	.mask {
		display: flex;
		align-items: flex-end;
		position: fixed;
		left: 0;
		top: var(--window-top);
		bottom: 0;
		width: 100%;
		background: rgba(0, 0, 0, 0);
		z-index: 9999;
		transition: .3s;
	
		.mask-content {
			width: 100%;
			min-height: 30vh;
			max-height: 90vh;
			padding: 0px 0px 60px;
			background: #f3f3f3;
			transform: translateY(100%);
			transition: .3s;
			overflow-y: scroll;
		}
	
		.btn {
			position: fixed;
			bottom: 0;
			width: 95%;
			text-align: center;
			border: 1px solid #FA436A;
			background-color: #FA436A;
			color: #FFFFFF;
			border-radius: 20px;
			margin: 10px;
			padding: 10px;box-sizing: border-box;
		}
	
	
		&.none {
			display: none;
		}
	
		&.show {
			background: rgba(0, 0, 0, .4);
	
			.mask-content {
				transform: translateY(0);
			}
		}
	}
	
	.scroll {
		height: 80vh;
	}
	
	.tab{display: flex;justify-content: space-around;background-color: #fff;height: 40px;line-height: 40px;
		.tab-l{text-align: center;}
		.boe-dott{border-bottom: 2px solid #FA436A;display: block;}
	}
	

}
</style>
