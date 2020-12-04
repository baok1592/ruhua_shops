<template>
	<view class="list-f">
		<view class='f_01' v-for="item of product" @click="detail(item.goods_id)">
			
			<block v-if="xuan == 0">
				<view class='f_01_1'>
					<img v-if="item.goods_imgs" :src="url+item.goods_imgs.thumb_img"></img>
				</view>
				<view class='f_01_2'>{{item.goods_name}}</view>
				<view class='f_01_3'>{{item.description}}</view>
				<view class='f_01_4'>¥{{item.price}}</view>
			</block>
			<block v-if="xuan != 0 && xuan == item.tag_ids[0]">
				<view class='f_01_1'>
					<img :src='url + item.goods_imgs.thumb_img'></img>
				</view>
				<view class='f_01_2'>{{item.goods_name}}</view>
				<view class='f_01_3'>{{item.description}}</view>
				<view class='f_01_4'>¥{{item.price}}</view>
			</block>
		</view>

	</view>
</template>

<script>
	export default {
		name: 'List-f',
		props: {
			product: Array,
			xuan: String
		},

		data() {
			return {
				id: "",
				url: this.$getimg
			}
		},
		methods:{
			detail: function(id) {
				let url = ''
				// #ifdef H5
				let my = uni.getStorageSync('my')
				if (my && my.data && my.data.sfm) {
					url = url + '&sfm=' + my.data.sfm
				}
				// #endif
				if(this.$version == 'shop'){
					url = '/pages/productDetail/productDetail?id=' + id
				}
				if(this.$version == 'shops'){
					url = '/pages/productDetail/productDetail?id=' + id
				}
				uni.navigateTo({
					url: url
				})
			
			},
		}
	}
</script>

<style lang="less">
	.list-f {
		margin: 5px 3vw;
		border-radius: 8px;
		padding-bottom: 10px;
		box-shadow: 4px 4px 5px #DEDEDE;
		display: flex;
		background-color: #fff;
		
		flex-wrap: wrap;justify-content: space-between;

		.f_01 {
			font-size: 12px;
			width: 45vw;
			margin-bottom: 10px;
		}

		.f_01_1 {
			position: relative;width: 100%;
		}

		.f_01_1 img {
			width: 45vw;
			height:45vw;
			border-top-left-radius: 8px;
			border-top-right-radius: 8px;
		}

		.f_01_1_f {
			position: absolute;
			bottom: -5px;
			left: 14px;
			background-color: #FB5F52;
			color: #FBE7CF;
			font-size: 10px;
			padding: 3px 5px;
			border-top-right-radius: 8px;
			border-bottom-left-radius: 8px;
		}

		.f_01_2 {
			margin: 8px 10px 0px;
			height: 18px;
			overflow: hidden;
			line-height: 18px;
		}

		.f_01_3 {
			padding: 0 10px;
			height: 18px;
			color: #9D9B9B;
			overflow: hidden;
			line-height: 16px;
		}

		.f_01_4 {
			padding: 0 10px;
			color: #ED6657;
		}

		.blue {
			.f_01_1_f {
				background-color: #2A82E4;
			}

			.f_01_4 {
				color: #2A82E4;
			}
		}

		.green {
			.f_01_1_f {
				background-color: #34B07A;
			}

			.f_01_4 {
				color: #34B07A;
			}
		}

		.pink {
			.f_01_1_f {
				background-color: #FF729A;
			}

			.f_01_4 {
				color: #FF729A;
			}
		}

		.orange {
			.f_01_1_f {
				background-color: #FF8D1B;
			}

			.f_01_4 {
				color: #FF8D1B;
			}
		}

	}
</style>
