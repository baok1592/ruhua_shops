<template>
	<div class="shop">
		<view class="shop_01" @click="jump">
			<view class="shop_01_l">
				<img src="@/static/imgs/vip.png"></img>
			</view>
			<view class="shop_01_m">
				<view class="shop_01_m_tit">{{info.shop_name}}</view>
				<view class="shop_01_m_ying">{{info.shop_description}}</view>
			</view>
			<view class="shop_01_r">
				进入店铺<span>></span>
			</view>
		</view>
		<view class="shop_02"></view>
		<view class="product">
			<view class="pro-tit">猜你喜欢</view>

			<scroll-view class="scroll-view_x" scroll-x style="width: auto;overflow:hidden;">
				<view class="pro">
					<view class="pro-01" v-for="(item,index) of product" :key="index" @click="detail(item.goods_id)" v-if="index <= 8">
						<img v-if="item.goods_imgs" :src="url+item.goods_imgs.thumb_img" ></img>
						<p>{{item.goods_name}}</p>
						<view class="pro-01-p">¥{{item.price}}</view>
					</view>
				</view>
			</scroll-view>

		</view>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				url: this.$getimg
			};
		},
		props: {
			info: Object,
			product: Array,

		},
		components: {},
		methods: {
			jump() {
				this.$emit("jump");
			},
			detail: function(id) {
				let url = ''
				// #ifdef H5
				let my = uni.getStorageSync('my')
				if (my && my.data && my.data.sfm) {
					url = url + '&sfm=' + my.data.sfm
				}
				// #endif
				if (this.$version == 'shop') {
					url = '/pages/productDetail/productDetail?id=' + id
				}
				if (this.$version == 'shops') {
					url = '/pages/productDetail/productDetail?id=' + id
				}
				uni.navigateTo({
					url: url
				})

			},
		},
	}
</script>

<style lang="less">
	.shop {
		background-color: #fff;
		font-size: 14px;
		margin-top: 10px;

		.shop_01 {
			display: flex;
			padding: 10px;

			.shop_01_l img {
				width: 40px;
				height: 40px;
				border-radius: 5px;
				box-shadow: 4px 4px 5px #F0F0F1;
			}

			.shop_01_m {
				flex-grow: 1;
				padding: 0 20px 0 10px;

				.shop_01_m_tit {
					font-weight: 600;
					margin: 0px 0 6px;
					height: 20px;
					line-height: 20px;
					overflow: hidden;
					font-size: 16px;
				}

				.shop_01_m_ying { 
					color: #989897; 
					height: 20px; 
					width: 11rem;
					overflow: hidden;
					text-overflow: ellipsis;
					white-space: nowrap; //单行超出显示省略号
				}
			}

			.shop_01_r {
				height: 40px;
				line-height: 40px;
				color: #E15253;
				display: flex;
				justify-content: space-between;
				width: 80px;
				flex-shrink: 0;

				span {
					color: #D6D6D6;
				}
			}
		}

		.shop_02 {
			background-color: #F5F5F5;
			height: 8px;
		}

		.product {
			.pro-tit {
				padding: 15px 10px;
				font-weight: 600;
				font-size: 15px;
			}

			.pro {
				display: flex;
				padding: 0 0 10px 10px;
				width: 100%;

				.pro-01 {
					width: 28%;
					padding-right: 1%;
					flex-shrink: 0;

					img {
						width: 100%;
						height: 105px;
					}

					p {
						margin: 5px 0;
						max-height: 36px;
						line-height: 18px;
						font-size: 12px;
						overflow: hidden;
						text-overflow: ellipsis;
						display: -webkit-box;
						-webkit-line-clamp: 2;
						-webkit-box-orient: vertical;
					}

					.pro-01-p {
						color: #E31436;
						text-align: center;
					}
				}
			}
		}
	}
</style>
