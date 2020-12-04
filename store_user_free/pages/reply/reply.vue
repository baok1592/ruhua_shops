<template>
	<view class="reply">
		<Evaluate answer="" hui=""></Evaluate>
		<view class="mai">
			购买了规格套餐：
		</view>
		<view class="pro">
			<view class="pro-l">
				<view class="pro-l-l">
					<img src="@/static/imgs/8.jpg"></img>
				</view>
				<view class="pro-l-r">
					这里说五斤麻辣小龙虾五斤五斤五斤
					<br/><span>¥98</span>
				</view>
			</view>
		</view>
		<view class="tit">商家回复</view>
		<view class="huifu">
			<textarea placeholder="请输入..."></textarea>
		</view>
		<view class="btn">
			<tui-button type="primary">提交</tui-button>
		</view>
	</view>
</template>

<script>
	import tuiButton from "@/components/button/button"
	import Evaluate from "@/components/shops/qy/evaluate.vue"
	export default {
		data() {
			return {
				
			};
		},
		components:{
			Evaluate,
			tuiButton
		}
	}
</script>

<style lang="scss">
.reply{font-size: 14px;
	.mai{color: #D2D2D2;padding:0 10px 10px;font-size: 12px;}
	.pro{padding: 10px;display: flex;margin: 10px;box-shadow: 0px 0px 8px #F4F3F4;
		.pro-l{display: flex;
			.pro-l-l img{width: 50px;height: 50px;border-radius: 2px;}
			.pro-l-r{font-size: 14px;font-weight: 600;padding: 0px 10px;line-height: 20px;display: flex;flex-direction: column;
			justify-content: space-around;
				span{color: #EB0000;font-size: 14px;font-weight: 600;}
			}
		}
		
	}
	.tit{padding: 20px 10px 10px;font-size: 16px;font-weight: 600;}
	.huifu{padding: 0 10px;
		textarea{background-color: #F5F5F5;width: 100%;border-radius: 5px;padding: 10px;font-size: 14px;height: 100px;line-height: 20px;}
	}
	.btn{padding: 50px 10px 0;}
}
</style>
