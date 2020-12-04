<template>
	<view class="kai">
		<view class="kaika">
			<view class="tou">
				<img src="@/static/imgs/8.jpg"></img>
			</view>
			<view class="tit">奶茶店</view>
			<view class="money">¥<span>1200</span></view>
			<view class="xinxi">
				<view class="xx">
					<view class="xx-l">开卡号</view>
					<view class="xx-r">1234212111</view>
				</view>
				<view class="xx bor-none">
					<view class="xx-l">开卡号</view>
					<view class="xx-r " @click="dropDownList(-1)"><span>翡翠卡</span>▼</view>
				</view>
				<view class="tui-drop-input-box" >
					<tui-dropdown-list :show="dropdownShow" :top="5" :height="400">
						<template v-slot:dropdownbox>
							<view class="tui-selected-list">
								<scroll-view scroll-y class="tui-dropdown-scroll">
									<block v-for="(item,index) in dropdownlistData" :key="index">
										<tui-list-cell @click="dropDownList(index)" :last="dropdownlistData.length-1==index">
											<tui-icon :name="item.icon" :size="item.size" :color="item.color"></tui-icon>
											<text class="tui-ml-20">{{item.name}}</text>
										</tui-list-cell>
									</block>
								</scroll-view>
							</view>
						</template>
					</tui-dropdown-list>
				</view>
				
			</view>
			<view class="qdkk">
				<tui-button type="primary" shape="circle">确定开卡</tui-button>
			</view>
			
			<view class="tixing">＊别忘了哦＊</view>
			<view class="close" @click="close">×</view>
			
		</view>
		<div class="container"></div>
	</view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiTopDropdown from "@/components/shops/top-dropdown/top-dropdown"
	import tuiListCell from "@/components/shops/list-cell/list-cell"
	import tuiDropdownList from "@/components/shops/dropdown-list/dropdown-list"
	import tuiBottomPopup from "@/components/bottom-popup/bottom-popup"
	import tuiButton from "@/components/button/button"
	export default {
		data() {
			return {
				dropdownlistData: [{
					name: "微信支付",
					icon: "wechat",
					color: "#80D640",
					size: 30
				}, {
					name: "支付宝支付",
					icon: "alipay",
					color: "#00AAEE",
					size: 30
				}, {
					name: "银行卡支付",
					icon: "bankcard-fill",
					color: "#ff7900",
					size: 28
				}, {
					name: "微信支付",
					icon: "wechat",
					color: "#80D640",
					size: 30
				}, {
					name: "支付宝支付",
					icon: "alipay",
					color: "#00AAEE",
					size: 30
				}, {
					name: "银行卡支付",
					icon: "bankcard-fill",
					color: "#ff7900",
					size: 28
				}],
				dropdownShow: false,
			};
		},
		props:{
			name:String
		},
		components: {
			tuiIcon,
			tuiButton,
			tuiTopDropdown,
			tuiListCell,
			tuiDropdownList,
			tuiBottomPopup
		},
		methods:{
			close(){
				this.$emit("close");
			},
			dropDownList(index) {
				if (index !== -1) {
					this.tui.toast("index：" + index)
				}
				this.dropdownShow = !this.dropdownShow
			},
		},
		mounted() {
		}
	}
</script>

<style lang="less">
.kai{
	.kaika{position: fixed;top: 120px;left: 8%;width: 84%;border-radius: 15px;background-color: #fff;box-sizing: border-box;
	z-index: 1001;text-align: center;padding: 85px 20px 15px;
		.tou{position: absolute;z-index: 1002;top: -20px;left: 50%;margin-left: -65px;
			img{width: 130px;height: 100px;border-radius: 5px;}
		}
		.tit{font-weight: 600;padding: 10px 0 25px;font-size: 16px;}
		.money{font-weight: 600;
			span{font-size: 36px;}
		}
		.xinxi{padding: 25px 0;
			.xx{border-bottom: 1px solid #F4F4F4;display: flex;justify-content: space-between;color: #6A6A6A;line-height: 40px;
				.xx-r{color: #C9C9C9;
					span{color: #49A8FF;}
				}
			}
			.bor-none{border-bottom: none;}
		}
		.qdkk{padding: 10px 15% 15px;}
		.tixing{color: #FFA196;}
		.close{position: absolute;left: 50%;bottom: -70px;margin-left: -20px;color: #fff;font-size: 30px;width: 35px;height: 35px;
		border: 1px solid #fff;border-radius: 50%;line-height: 30px;}
	}
	.container {
	    background-color: #000000;
	    position: fixed;
	    top: 0;
	    opacity: 0.6;
	    width: 100%;
	    height: 100%;
	    z-index: 999;
	} 
	.tui-dropdown-scroll {
		height: 400upx;
	}
}
</style>
