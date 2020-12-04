<template>
  <view class="evaluate" >
        <view class='con'>
        	<view class='con_1'>
        		<view class='con_1_l'>
        			<!-- <img :src='item.user.headpic' /> -->
					<img src="@/static/imgs/8.jpg"></img>
        			<view class='name'>123
						<!-- {{item.user.nickname}} -->
						<span></span>
        				<view class='con_1_r'>2020-1-1
							<!-- {{item.create_time}} -->
						</view>
        			</view>
        		</view>
        		<view class='con_1_r'>
        			<view class="ping">
        				<tui-rate :current="current" @change="changedd" active="#F37B1D" :disabled="true"></tui-rate>
        			</view>
        		</view>
        	</view>
        	<view class='con_2'>
        		<!-- {{item.content}} -->33
        		<view class="img">
        			<!-- <block v-for="(item,index) of item.imgs" :key="index" v-if="index<9">
        				<img :src="getimg + item" @click="ViewImage"></img>
        			</block> -->
        		</view>
        	</view>
			<view class="con-4" v-if="hui">
				商家回复：这里是商家回复。
			</view>
			<view class="con-3" v-if="answer" @click="jump">
				<view class="hui"><tui-icon name="message" size="20" ></tui-icon> 回复</view>
			</view>
        </view>
  </view>
</template>

<script>
	import tuiIcon from "@/components/icon/icon"
	import tuiRate from "@/components/rate/rate"
	export default {
	  name: 'List-f', 
	  props: ['answer','hui'], 
	  data() {
		return {
			id:"",
			current: 5,
			index: 5,
		}	
	  },
	  components:{
		  tuiRate,
		  tuiIcon
	  },
	  methods:{
		  changedd: function(e) {
		  	this.index = e.index;
		  	this.current = e.index
		  },
		  jump(){
			  uni.navigateTo({
			  	url: '/shops/reply/reply',
			  });
		  }
	  }
	}
</script>

<style lang="less">
.evaluate{
	.con {
		background-color: #fff;
		padding: 10px;
	}
	
	.con_1 {
		display: flex;
		justify-content: space-between;
	}
	
	.con_1_l {
		display: flex;
		line-height: 20px;
	}
	
	.con_1_l img {
		height: 40px;
		width: 40px;
		border-radius: 30px;
		margin-right: 10px;
	}
	
	.name {
		font-size: 16px;
	
		span {
			padding-left: 5px;
		}
	}
	
	.con_1_r {
		color: #B3B3B3;
		font-size: 12px;
	}
	
	.ping {
		padding-top: 5px;
	}
	
	.con_2 {
		padding: 10px 0px 10px 0px;line-height: 20px;
	
		.img {
			padding-top: 8px;
	
			img {
				width: 27vw;
				height: 24vw;
				border-radius: 5px;
				margin: 0 2.5vw 10px 0;
			}
		}
	}
	.con-3{display: flex;justify-content: flex-end;margin-top: 10px;
		.hui{color: #A4A4A4;border: 1px solid #E9E9E9;border-radius: 30px;padding: 3px 12px 1px;}
	}
	.con-4{background-color: #F9F9F9;font-size: 12px;color: #707070;padding: 8px;border-radius: 3px;}
}
   
</style>