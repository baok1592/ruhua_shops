<template> 
	<view class="tui-product-list">
		<view class="tui-product-container">
			<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2!=0">
				<!--商品列表-->
				<view class="tui-pro-item" @tap="detail(item.goods_id)">
					<view class='pic'>
						<image v-if="item.goods_imgs" :src="getimg+item.goods_imgs.thumb_img" class="tui-pro-img" 
						style="" />
						<view v-if="item.stock==0">
							<view class='cont-img'> </view>
							<view class='maiguang'>
								<img src='@/static/imgs/x.png'></img>
							</view>
						</view>
					</view>
					<view class="tui-pro-content">
						<view class="tui-pro-tit">{{item.goods_name}}</view>
						<view>
							<view class="tui-pro-price">
								<text class="tui-sale-price" v-if="is_vip">vip{{item.price}}</text>
								<text class="tui-sale-price" v-else>￥{{item.price}}</text>
								<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
								<xianshi v-if="item.discount && item.discount.reduce_price" title="限时" :price="item.price-item.discount.reduce_price*1"></xianshi>
								<xianshi v-if="item.pt && item.pt.price" title="拼团" :price="(item.price*100-item.pt.price*100)/100"></xianshi>
							</view>
							<!-- <view class="tui-pro-pay">{{item.sales}}人付款</view> -->
						</view>
					</view>
				</view>
				
				<!--商品列表-->
				<!-- <template is="productItem" data="{{item,index:index}}" /> -->
			</block>
		</view>
		<view class="tui-product-container">
			<block v-for="(item,index) in productList" :key="index" v-if="(index+1)%2==0">
				<!--商品列表-->
				<view class="tui-pro-item" hover-class="hover" :hover-start-time="150" @tap="detail(item.goods_id)">
	
					<view class='pic'>
						<image v-if="item.goods_imgs" :src="getimg+item.goods_imgs.thumb_img" class="tui-pro-img" 
						style="" />
						<view v-if="item.stock==0">
							<view class='cont-img'> </view>
							<view class='maiguang'>
								<img src='@/static/imgs/x.png'></img>
							</view>
						</view>
					</view>
					<view class="tui-pro-content">
						<view class="tui-pro-tit">{{item.goods_name}}</view>
						<view>
							<view class="tui-pro-price">
								<text class="tui-sale-price" v-if="is_vip">vip {{item.price}}</text>
								<text class="tui-sale-price" v-else>￥{{item.price}}</text>
								<text class="tui-factory-price" v-if="is_vip">￥{{item.market_price}}</text>
								<xianshi v-if="item.discount && item.discount.reduce_price" title="限时" :price="item.price-item.discount.reduce_price*1"></xianshi>
								<xianshi v-if="item.pt && item.pt.price" title="拼团" :price="item.price-item.pt.price*1"></xianshi>
							</view>
							<!-- <view class="tui-pro-pay">{{item.sales}}人付款</view> -->
						</view>
					</view>
				</view>
				<!--商品列表-->
				<!-- <template is="productItem" data="{{item,index:index}}" /> -->
			</block>
		</view>
	</view>
</template>

<script>
	export default{
		data(){
			return{
				getimg:this.$getimg,
				is_vip:false
			}
		},
		props:{
			productList:Array
		},
		onLoad() {
			console.log('xxxxx',this.productList)
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
		}
	}
</script>

<style lang="less">
	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
		/* padding-top: 20rpx; */padding: 0 10px;
	}
	
	.tui-product-container {
		flex: 1;
		margin-right: 2%;
	}
	
	.tui-product-container:last-child {
		margin-right: 0;
	}
	
	.tui-pro-item {
		width: 100%;
		margin-bottom: 10px;
		background: #fff;
		box-sizing: border-box;
		border-radius: 12rpx;
		overflow: hidden;
	
		.pic {
			position: relative;
	
			.maiguang {
				position: absolute;
				top: 50%;
				left: 50%;
				z-index: 199;
				width: 100px;
				height: 100px;
				margin: -50px 0 0 -50px;
			}
	
			.maiguang img {
				width: 100px;
				height: 100px;
			}
	
			.cont-img {
				background-color: #000000;
				opacity: 0.3;
				width: 100%;
				height: 100%;
				z-index: 99;
				position: absolute;
				top: 0;
				left: 0;
			}
		}
	}
	
	.tui-pro-img {
		height: 46vw;width: 46vw;border-radius: 6px 6px 0 0;
		display: block;
	}
	
	.tui-pro-content {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		box-sizing: border-box;
		padding: 20rpx;
	}
	
	.tui-pro-tit {
		color: #2e2e2e;
		font-size: 26rpx;
		line-height: 18px;
		height: 36px;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}
	
	.tui-pro-price {
		padding-top: 18rpx;
		display: flex;
		flex-wrap: wrap;
	}
	
	.tui-sale-price {
		/* #ifndef H5 */
		font-size: 14px;
		/* #endif */
		/* #ifdef H5 */
		font-size: 12px;
		/* #endif */
		margin-bottom: 5px;
		font-weight: 500;
		color: #FB586A;
	
	}
	
	.tui-factory-price {
		/* #ifndef H5 */
		font-size: 14px;
		/* #endif */
		/* #ifdef H5 */
		font-size: 12px;
		/* #endif */
		color: #a0a0a0;
		padding-left: 12rpx;
	}
	
	.tui-pro-pay {
		padding-top: 10rpx;
		font-size: 24rpx;
		color: #656565;
	}
</style>
