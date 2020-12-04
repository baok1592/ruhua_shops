<template>
	<view class="whole">
		<view class="pro">
			<view class="pro-l">
				<view class="pro-l-l">
					<img src="@/static/imgs/8.jpg"></img>
				</view>
				<view class="pro-l-r">
					这里说五斤麻辣小龙虾五斤五斤五斤
				</view>
			</view>
			<view class="pro-r">
				<span>5.0</span><br/>综合评分
			</view>
		</view>
		<view class="tit">全部评价<span>(20条)</span></view>
		<view class="fen">
			<tui-tag type="light-warning" class=" tui-danger margin-bottom" shape="circle" >全部</tui-tag>
			<block v-for="(item,index) in hot" :key="index"><span></span>
				<tui-tag type="light-blue" class="margin-bottom" v-if="index%4==0"  shape="circle" >{{item}}</tui-tag>
				<tui-tag type="light-brownish" class="margin-bottom" v-if="index%4==1"  shape="circle" >{{item}}</tui-tag>
				<tui-tag type="light-green" class="margin-bottom" v-if="index%4==2"  shape="circle" >{{item}}</tui-tag>
				<tui-tag type="" class="margin-bottom tui-gray" v-if="index%4==3"  shape="circle" >{{item}}</tui-tag>
			</block>
		</view>
		<Evaluate answer="1" hui=""></Evaluate>
		<view class="BH8"></view>
		<Evaluate answer="1" hui="1"></Evaluate>
	</view>
</template>

<script>
	import Evaluate from "@/components/shops/qy/evaluate.vue"
	import tuiTag from "@/components/tag/tag"
	export default {
		data() {
			return {
				hot:[1,2,3,4,]
			};
		},
		components:{
			Evaluate,
			tuiTag
		}
	}
</script>

<style lang="scss">
.whole{font-size: 14px;
	
	.pro{padding: 10px;display: flex;margin: 10px;box-shadow: 0px 0px 8px #F4F3F4;
		.pro-l{width: 80%;display: flex;
			.pro-l-l img{width: 50px;height: 50px;border-radius: 2px;}
			.pro-l-r{font-size: 15px;font-weight: 600;padding: 5px 10px;line-height: 20px;}
		}
		.pro-r{width: 20%;border-left: 1px solid #FAFAFA;text-align: center;color: #9C9C9C;padding-top: 5px;
			span{color: #EB0000;font-size: 18px;font-weight: 600;}
		}
	}
	.tit{padding: 20px 10px 15px;font-size: 16px;font-weight: 600;
		span{color: #A1A1A1;font-weight: 500;font-size: 14px;}
	}
	.BH8{background-color: #F8F8F8;height: 8px;}
	.fen{display: flex;flex-wrap: wrap;padding: 5px 10px 10px;
		span{padding-left: 10px;}
		.margin-bottom{margin-bottom: 10px;}
	}
}
</style>
