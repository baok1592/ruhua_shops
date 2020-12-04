<template>
	<view class="container">

		<!--header-->
		<view class="tui-header">
			<view class="notice" v-if="gundong">
				<uni-notice-bar scrollable="true" single="true" :text="gundong"></uni-notice-bar>
			</view>
			<view class="tui-header-dis">
				<view class="tui-rolling-search" @click="search">
					<!-- #ifdef APP-PLUS || MP -->
					<icon type="search" :size='13' color='#999'></icon>
					<!-- #endif -->
					<!-- #ifdef H5 -->
					<view>
						<tui-icon name="search" :size='16' color='#999'></tui-icon>
					</view>
					<!-- #endif -->

					<swiper vertical autoplay circular interval="8000" class="tui-swiper">
						<swiper-item v-for="(item,index) in hotSearch" :key="index" class="tui-swiper-item">
							<view class="tui-hot-item">{{item}}</view>
						</swiper-item>
					</swiper>

				</view>
			</view>
		</view>
		<!--header-->
		<view class="tui-header-banner">
			<view class="tui-hot-search" :style="gundong?'margin-top: 70px':'margin-top: 40px'">
				<view style="width: 50px;flex-shrink: 0;">热搜</view>
				<view class="tui-hot-tag" v-for="(item,index) of resou" :key="index" v-if="index<4">
					<view @click="tosearch(item)">{{item}}</view>
				</view>
			</view>
			<view class="tui-banner-bg">
				<view class="tui-primary-bg tui-route-left"></view>
				<view class="tui-primary-bg tui-route-right"></view>
				<!--banner-->
				<view class="tui-banner-box">
					<swiper :indicator-dots="true" :autoplay="true" :interval="5000" :duration="150" class="tui-banner-swiper"
					 :circular="true" indicator-color="rgba(255, 255, 255, 0.8)" indicator-active-color="#fff">
						<swiper-item v-for="(item,index) in banner" :key="index" @click="jump_article(item.jump_id,item.type)">
							<image :src="getimg+item.imgs" class="tui-slide-image" mode="scaleToFill" />
						</swiper-item>
					</swiper>
				</view>
			</view>
		</view>

		<view class="tui-product-category">
			<swiper :indicator-dots="true" :interval="5000" :duration="150" class="tui-banner-swiper" style='height:160px'
			 :circular="true" indicator-color="#ccc" indicator-active-color="rgba(251,88,106, 0.8)">
				<swiper-item>
					<view style="display: flex;flex-wrap: wrap;">
						<block v-for="(item,index) in category" :key="index" :data-key="item.name" v-if="index<8">
							<view class="tui-category-item" @click="jump(item.url,item.id,item.url_name)">
								<img :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></img>
								<view class="tui-category-name">{{item.nav_name}}</view>
							</view>
						</block>
					</view>
				</swiper-item>
				<swiper-item>
					<view style="display: flex;flex-wrap: wrap;">
						<block v-for="(item,index) in category" :key="index" :data-key="item.name" v-if="index>7">
							<view class="tui-category-item" @click="jump(item.url,item.id,item.url_name)">
								<img :src="getimg+item.img_id" class="tui-category-img" mode="scaleToFill"></img>
								<view class="tui-category-name">{{item.nav_name}}</view>
							</view>
						</block>
					</view>
				</swiper-item>
			</swiper>
			
		</view>

		
		<!-- #ifdef MP-WEIXIN -->
		<button class="btn1" open-type="contact">
			<image class="btnImg" src="../../static/images/kefu.png"></image>
			<!-- <view>客服</view> -->
		</button>
		<!-- #endif -->

		<view class="tui-product-box tui-pb-20 tui-bg-white">
			<view class="tui-group-name">
				<text>新品推荐</text>
			</view>
			<view class="tui-new-box">
				<view class="tui-new-item" :class="[index!=0 && index!=1 ?'tui-new-mtop':'']" v-for="(item,index) in newProduct_new"
				 :key="index" @tap="detail(item.goods_id)">
					<!-- <img src="@/static/imgs/6.jpg" class="tui-new-label" /> -->
					<view class="tui-title-box">
						<view class="tui-new-title">{{item.goods_name}}</view>
						<view class="tui-new-price">
							<text class="tui-new-present">￥{{item.price | new_price(item.price)}}</text>
							<!-- <text class="tui-new-original">￥{{item.market_price}}</text> -->
						</view>
					</view>
					<img v-if="item.goods_imgs" :src="getimg+item.goods_imgs.thumb_img" class="tui-new-img2" />
				</view>

			</view>

		</view>

		<view class="tui-product-box">
			<view class="tui-group-name">
				<text>热门推荐</text>
			</view>

		</view>
		<block v-if="shops_site.same == 1">
			<block v-for="(item,index) in newProduct" :key="index">
				<List :info="item"></List>
			</block>
		</block>
		<block v-else>
			<Listhot :productList="newProduct"></Listhot>
		</block>

		<!--加载loadding-->
		<tui-loadmore :visible="loadding" :index="3" type="red"></tui-loadmore>
		<!-- <tui-nomore visible="{{!pullUpOn}}"></tui-nomore> -->
		<!--加载loadding-->
		<view class="tui-safearea-bottom"></view>
		<!-- 弹出 -->
		<!-- #ifdef APP-PLUS -->
		<Xieyi></Xieyi>
		<!-- #endif -->
	</view>
</template>
<script>
	import uniNoticeBar from '@/components/uni/uni-notice-bar/uni-notice-bar.vue'
	import Check from '@/common/check.js'
	import Xieyi from "@/components/qy/xieyi"
	import tuiIcon from "@/components/icon/icon"
	import tuiTag from "@/components/tag/tag"
	import tuiLoadmore from "@/components/loadmore/loadmore"
	import tuiNomore from "@/components/nomore/nomore"
	import Cache from "@/common/cache.js"
	import xianshi from "@/components/qy/xianshi"
	import List from "@/components/shops/qy/List"
	import Listhot from "@/components/shops/qy/List_hot.vue"
	export default {
		components: {
			tuiIcon,
			tuiTag,
			tuiLoadmore,
			tuiNomore,
			xianshi,
			Xieyi,
			uniNoticeBar,
			List,
			Listhot
		},
		data() {
			return {
				gundong: '',
				resou: '',
				getimg: this.$getimg,
				current: 0,
				tabbar: [{
					icon: "home",
					text: "首页",
					size: 21
				}, {
					icon: "category",
					text: "分类",
					size: 24
				}, {
					icon: "cart",
					text: "购物车",
					size: 22
				}, {
					icon: "people",
					text: "我的",
					size: 24
				}],
				hotSearch: [],
				banner: [],
				category: [],
				newProduct: [],
				newProduct_new: [],
				productList: [],
				loadding: false,
				pullUpOn: true,
				switch_list: '',
				shops_site: ''
			}
		},
		watch: {

			shops_site(a, b) {
				console.log(a, b)
				console.log('shops_site变了')
				this._load()
			}

		},
		onShow() {



		},
		onLoad(options) {
		
			this.switch_list = uni.getStorageSync('switch')

			this._load()

		},
		methods: {
			jump_to() {
				uni.navigateTo({
					url: '/pages/address/selectCity'
				})
			},
			//nav跳转
			jump(url, id, name) {
				const that = this
				console.log(id, name)
				
				console.log(url)
				uni.navigateTo({
					url: url
				})

			},
			tabbarSwitch: function(e) {
				let index = e.currentTarget.dataset.index;
				this.current = index;
				if (index != 0) {
					if (index == 1) {
						this.classify();
					} else if (index == 2) {
						uni.switchTab({
							url: '/pages/cart/cart'
						})
					} else if (index == 3) {
						uni.switchTab({
							url: '/pages/user/user'
						})
					}
				}
			},
			_load() {
				console.log('开始获取数据')
				this.$api.http.get('article/type_article?type=3').then(res => {
					if (res.data && res.data[0]) {
						this.gundong = res.data[0].title
					}
				})
				this.$api.http.get('search/record').then(res => { //首页banner 
					this.resou = res.data
					if (res.data == 1) {
						return;
					}
					this.hotSearch = res.data.slice(0, 3)
					uni.setStorageSync('hotSearch', this.resou)
				})
				let position = uni.getStorageSync('position')
				if (this.shops_site.same == 1 && position) {
					this.$api.http.get('shops/hot_pro?lat=' + position.latitude + '&lng=' + position.longitude).then(res => {
						this.newProduct = res.data
					})
				} else {
					this.$api.http.get('shops/hot_pro').then(res => {
						this.newProduct = res.data
					})
				}
				let a = this.$api.http.get('product/get_recent', {
					type: 'hot'
				}) //热门推荐
				let b = this.$api.http.get('shops/new_pro') //新品推荐
				let c = this.$api.http.get('nav/get_nav') //导航
				let d = this.$api.http.get('banner/get_banner?id=1') //轮播图

				Promise.all([a, b, c, d]).then(res => {
					console.log('获取数据成功')
					this.productList = res[0].data
					this.newProduct_new = res[1].data
					this.category = res[2].data
					this.banner = res[3].data.items?res[3].data.items:[]
					uni.stopPullDownRefresh();
				})
			},
			detail: function(id) {
				let url = ''

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
			classify: function() {
				uni.switchTab({
					url: '/pages/category/category'
				})
			},
			more: function(e) {
				let key = e.currentTarget.dataset.key || "";
				uni.navigateTo({
					url: '/pages/extend-view/productList/productList?searchKey=' + key
				})

			},
			search: function() {
				console.log(1)
				uni.navigateTo({
					url: '/shops/extend-view/news-search/news-search'
				})
			},
			jump_article(id, type) {
				let url = '/pages/article/article?id=' + id
				if (type == 'goods') {
					url = '/pages/productDetail/productDetail?id=' + id
				}
				if (id == 0) {
					return
				}
				uni.navigateTo({
					url: url
				});
			},
			tosearch(item) {
				console.log(this.resou)
				console.log(item)
				uni.navigateTo({
					url: '/pages/productList/productList?key=' + item
				})
			}
		},
		onPullDownRefresh() {
			this._load()
			// setTimeout(function() {
			// 	uni.stopPullDownRefresh();
			// }, 2000);
		},

		//小程序右上角原生菜单分享按钮，也可是页面中放置的分享按钮
		onShareAppMessage(res) {
			let my = uni.getStorageSync('my')
			let path = "/pages/index/index"
			
			console.log('path:', path)
			return {
				title: '商城',
				path: path
			}
		},
	}
</script>

<style lang="scss">
	page {
		background: #f7f7f7;
	}

	.back {
		position: fixed;
		bottom: 80px;
		left: 0;
		background-color: #FF5266;
		color: #FFDFE9;
		display: flex;
		font-size: 14px;
		padding: 5px 10px 5px 5px;
		border-top-right-radius: 18px;
		border-bottom-right-radius: 18px;
		text-decoration: none
	}

	.back_01 {
		margin-top: -1px;
	}

	/* #ifdef MP-WEIXIN */
	.btn1 {
		width: 60rpx;
		height: 60rpx;
		font-size: 30rpx;
		position: fixed;
		padding: 0px;
		margin: 0px;
		right: 10rpx;
		z-index: 999;
		background: none !important;

	}

	.btnImg {
		width: 60rpx;
		height: 60rpx;
		opacity: 0.8;
	}

	.btn1::after {
		border: 0;
	}

	/* #endif */



	.container {
		padding-bottom: 100rpx;
		color: #333;
		/* #ifndef H5 */
		padding-top: 60px;
		/* #endif */
	}

	/*tabbar*/

	.tui-tabbar {
		/* 
		width: 100%;
		position: fixed;
		display: flex;
		align-items: center;
		justify-content: space-between;
		z-index: 99999;
		background: #fff;
		height: 100rpx;
		left: 0;
		bottom: 0;
		padding-bottom: env(safe-area-inset-bottom);
	 */
	}







	.tui-ptop-4 {
		padding-top: 4rpx;
	}

	.tui-scale {
		font-weight: bold;
		transform: scale(0.8);
		transform-origin: center 100%;
		line-height: 30rpx;
	}

	.tui-item-active {
		color: #FB586A !important;
	}

	/*tabbar*/
	.notice {
		width: 100%;
		height: 30px;
	}

	.tui-header {
		width: 100%;
		box-sizing: border-box;
		background: #FB586A;
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		justify-content: space-between;
		position: absolute;
		left: 0;
		top: 70px;
		/* #ifdef H5 */
		top: 0px;
		/* #endif */
		z-index: 999;

		.tui-header-dis {
			display: flex;
			width: 100%;

			padding: 0px 30rpx 0 20rpx;
			/* #ifdef H5 */
			padding-top: 10px;
			/* #endif */
			padding-bottom: 10px;
		}
	}

	.tui-rolling-search {
		width: 100%;
		height: 60rpx;
		border-radius: 35rpx;
		padding: 0 40rpx 0 30rpx;
		box-sizing: border-box;
		background: #fff;
		display: flex;
		align-items: center;
		flex-wrap: nowrap;
		color: #999;

	}

	.tui-category {
		width: 80px;
		font-size: 24rpx;
		color: #fff;
		display: flex;
		justify-content: space-between;
		align-self: center;
		margin: 0;
		margin-right: 22rpx;
		flex-shrink: 0;

		.tui-category-diming {
			max-height: 30px;
			line-height: 15px;
			overflow: hidden;
			text-align: center;
			width: 100%;
			text-overflow: ellipsis;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
		}

		.tui-category-xia {
			display: flex;
			align-self: center;
		}
	}

	.tui-category-scale {
		transform: scale(0.7);
		line-height: 24rpx;
	}

	.tui-icon-category {
		line-height: 20px !important;
		margin-bottom: 0 !important;
	}

	.tui-swiper {
		font-size: 26rpx;
		height: 60rpx;
		flex: 1;
		padding-left: 12rpx;
	}

	.tui-swiper-item {
		display: flex;
		align-items: center;
	}

	.tui-hot-item {
		line-height: 26rpx;
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}



	.tui-header-banner {
		box-sizing: border-box;
		padding: 10px 0 0px 0rpx;
		background: #FB586A;
	}

	.tui-hot-search {
		color: #fff;
		font-size: 24rpx;
		display: flex;
		align-items: center;
		padding: 0 20rpx;
		box-sizing: border-box;
		position: relative;
		z-index: 2;
		height: 28px;
	}

	.tui-hot-tag {
		background: rgba(255, 255, 255, 0.15);
		padding: 10rpx 24rpx;
		border-radius: 30rpx;
		display: flex;
		white-space: nowrap;
		align-items: center;
		margin-right: 15px;
		justify-content: center;
		line-height: 24rpx;
		/* margin-left: 20rpx; */
	}

	.tui-banner-bg {
		display: flex;
		height: 180rpx;
		background: #FB586A;
		position: relative;
	}

	.tui-primary-bg {
		width: 50%;
		display: inline-block;
		height: 224rpx;
		border: 1px solid transparent;
		position: relative;
		z-index: 1;
		background: #FB586A;
	}

	.tui-route-left {
		transform: skewY(8deg);
	}

	.tui-route-right {
		transform: skewY(-8deg);
	}

	.tui-banner-box {
		width: 100%;
		padding: 0 20rpx;
		box-sizing: border-box;
		position: absolute;
		/* overflow: hidden; */
		z-index: 99;
		bottom: -80rpx;
		left: 0;
	}

	.tui-banner-swiper {
		width: 100%;
		height: 240rpx;
		border-radius: 12rpx;
		overflow: hidden;
		transform: translateY(0);
	}

	.tui-slide-image {
		width: 100%;
		height: 240rpx;
		display: block;
	}

	/* #ifdef APP-PLUS || MP */
	// .tui-banner-swiper .wx-swiper-dot {
	// 	width: 8rpx;
	// 	height: 8rpx;
	// 	display: inline-flex;
	// 	background: none;
	// 	justify-content: space-between;
	// }

	// .tui-banner-swiper .wx-swiper-dot::before {
	// 	content: '';
	// 	flex-grow: 1;
	// 	background: rgba(255, 255, 255, 0.8);
	// 	border-radius: 16rpx;
	// 	overflow: hidden;
	// }

	// .tui-banner-swiper .wx-swiper-dot-active::before {
	// 	background: #fff;
	// }

	// .tui-banner-swiper .wx-swiper-dot.wx-swiper-dot-active {
	// 	width: 16rpx;
	// }

	/* #endif */

	/* #ifdef H5 */
	>>>.tui-banner-swiper .uni-swiper-dot {
		width: 8rpx;
		height: 8rpx;
		display: inline-flex;
		background: none;
		justify-content: space-between;
	}

	>>>.tui-banner-swiper .uni-swiper-dot::before {
		content: '';
		flex-grow: 1;
		background: rgba(255, 255, 255, 0.8);
		border-radius: 16rpx;
		overflow: hidden;
	}

	>>>.tui-banner-swiper .uni-swiper-dot-active::before {
		background: #fff;
	}

	>>>.tui-banner-swiper .uni-swiper-dot.uni-swiper-dot-active {
		width: 16rpx;
	}

	/* #endif */

	.tui-product-category {
		background: #fff;
		padding: 45px 20rpx 0rpx 20rpx;
		box-sizing: border-box;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		font-size: 24rpx;
		color: #555;
		margin-bottom: 20rpx;
	}

	.tui-category-item {
		width: 25%;
		height: 120rpx;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-direction: column;
		margin: 0px 0 0px;
		margin: 10px 0 0;

	}

	.tui-category-img {
		height: 80rpx;
		width: 80rpx;

	}

	.tui-category-name {
		line-height: 20px;
		text-align: center;
	}

	.tui-product-box {
		margin-top: 20rpx;
		padding: 0 20rpx;
		box-sizing: border-box;
	}

	.tui-pb-20 {
		padding-bottom: 20rpx;
	}

	.tui-bg-white {
		background: #fff;
	}

	.tui-group-name {
		font-size: 32rpx;
		font-weight: bold;
		text-align: center;
		padding: 24rpx 0;
	}

	.tui-activity-box {
		display: flex;
		border-radius: 12rpx;
		overflow: hidden;
	}

	.tui-activity-img {
		width: 50%;
		display: block;
	}

	.tui-new-box {
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
	}

	.tui-new-item {
		width: 49%;
		height: 200rpx;
		padding: 0px 7px 0;
		box-sizing: border-box;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background: #f5f2f9;
		position: relative;
		border-radius: 12rpx;
	}

	.tui-new-mtop {
		margin-top: 2%;
	}

	.tui-title-box {
		width: 55%;
		font-size: 24rpx;
	}

	.tui-new-title {
		line-height: 32rpx;
		word-break: break-all;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-box-orient: vertical;
		-webkit-line-clamp: 2;
	}

	.tui-new-price {
		padding-top: 18rpx;
	}

	.tui-new-present {
		color: #ff201f;
		font-weight: bold;
	}

	.tui-new-original {
		display: inline-block;
		color: #a0a0a0;
		text-decoration: line-through;
		padding-left: 12rpx;
		transform: scale(0.8);
		transform-origin: center center;
	}

	.tui-new-img {
		width: 140rpx;
		height: 140rpx;
		display: block;
		flex-shrink: 0;
		border-radius: 5px;
	}

	.tui-new-img2 {
		width: 125rpx;
		height: 125rpx;
		display: block;
		flex-shrink: 0;
		border-radius: 5px;
		margin-left: 2px;
	}

	.tui-new-label {
		width: 56rpx;
		height: 56rpx;
		border-top-left-radius: 12rpx;
		position: absolute;
		left: 0;
		top: 0;
	}

	.tui-product-list {
		display: flex;
		justify-content: space-between;
		flex-direction: row;
		flex-wrap: wrap;
		box-sizing: border-box;
		/* padding-top: 20rpx; */
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
		margin-bottom: 4%;
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
		width: 320rpx;
		height: 320rpx;
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
