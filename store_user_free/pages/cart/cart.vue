<template>
	<view class="cart">
		<view class="BH10"></view>
		<block v-for="(item,shop_id) of cart">
			<view class="bei">
				<view class="shop">
					<view class="shop-l cart-item">
						<view class="yticon icon-xuanzhong2 checkbox" :class="{checked: item.info.radio}"
						@click="_radio_shop(shop_id)"></view>
					</view>
					<!-- <view class="shop-m"><img :src="item.shop.img" /></view> -->
					<view class="shop-r" @click="jump_shop(shop_id)">{{item.info.shop_name}} <span>></span></view>
				</view>
				<!-- 列表 -->
				<view class="cart-list">
					<block v-for="(ite, pro_id) in item.pro" :key="ite.id">
						<view class="cart-item">
							<view class="yticon icon-xuanzhong2 chec" :class="{checked: ite.radio}"
							 @click="_radio(pro_id,shop_id)"></view>
							<view class="image-wrappers">
								<img :src="getimg+ite.imgs" @click="jump_detail(ite.goods_id*1)"></img>
								
							</view>
							<view class="item-right">
								<view class="dis-liang">
									<text class="clamp title" @click="jump_detail(ite.goods_id*1)">{{ite.goods_name}}</text>
									<text class="del-btn yticon icon-fork" @click="deleteCartItem(shop_id,pro_id)"></text>
								</view>
								<view class="dis-liang">
									<view class="guige " >
										<text class="clamp " >规格：{{ite.sku_name?ite.sku_name:""}}</text>
										<!-- <view class="xiangxia">
											<text class="del-btn unfold cuIcon-unfold" ></text>
										</view> -->										
									</view>
								</view>
								
									
								<view class="item-right-num ">
									<text class="price">¥{{ite.price}}</text>
									<tui-numberbox :max="ite.stock" :min="1" :value="ite.num"
									 @change="numberChange($event,shop_id,pro_id)"></tui-numberbox>
								</view>
							</view>
							
						</view>
					</block>
				</view>
				
			</view>
		</block>

		<!-- 底部菜单栏 -->
		<view class="action-section">
			<view class="checkbox">

				<!-- <image :src="allChecked?'/static/selected.png':'/static/select.png'" mode="aspectFit" @click="check('all')"></image> -->
				<view class="clear-btn" v-if="cart" @click="clearCart">
					清空
				</view>
			</view>
			<view class="total-box">
				<text class="price">¥{{total}}</text>
				<!-- <text class="coupon">
					已优惠
					<text>1</text>
					元
				</text> -->
			</view>
			<button type="primary" class="no-border confirm-btn" @click="createOrder">去结算</button>
		</view>

	</view>
</template>

<script>
	// import {
	// 	mapState
	// } from 'vuex';
	//import uniNumberBox from '@/components/uni-number-box.vue'
	import tuiNumberbox from "@/components/numberbox/numberbox"
	export default {
		components: {
			//uniNumberBox,
			tuiNumberbox
		},
		data() {
			return {
				x:0,	//简单粗暴的页面数据变动
				check_shop: false,
				cart: {},
				getimg: this.$getimg,
				allChecked: false, //全选状态  true|false
				empty: false, //空白页现实  true|false
			};
		},
		onLoad() {

		},
		onShow() {
			this._load()
		},
		watch: {
			check_shop() {
				for (let k in this.data) {
					let v = this.data[k]
					for (let j in v.goods) {
						let h = v.goods[j]
						if (h.radio) {
							v.shop.radio = true
						} else {
							v.shop.radio = false
							break
						}
					}
				}
			}
		},
		computed: {
			//计算总价
			total() {
				let total = 0;
				let cart=this.cart
				if(!cart){ 
					this.empty = true;
					return total;
				}  
				let checked = true;
				for(let sid in cart){
					const shop=cart[sid].pro					
					for(let k in shop){
						const pros=shop[k]
						console.log("pros:",pros)
						if (pros.radio === true) {
							total += pros.price * pros.num;
						}
					}
				} 
				total = Number(total.toFixed(2));
				return total;
			}
		},
		methods: {
			_load() {
				let arr = []
				let cache = uni.getStorageSync('cart')
				if (cache) {
					this.cart = cache
					// console.log(this.cart[1].pro)
				}
			},
			jump_detail(id) {
				console.log("jum_pro",id)
				uni.navigateTo({
					url: '/pages/productDetail/productDetail?id=' + id
				})
			},
			jump_shop(id){
				uni.navigateTo({
					url: '/pages/shop/shop?id=' + id
				})
			},
			//数量
			numberChange(e, shop_id,pro_id) {
				console.log('num:', e.value)
				this.$set(this.cart[shop_id].pro[pro_id], 'num', e.value)
				uni.removeStorageSync('cart') 
				uni.setStorageSync('cart', this.cart)
			},
			// 监听image加载完成
			onImageLoad(key, index) {
				this.$set(this[key][index], 'loaded', 'loaded');
			},
			//监听image加载失败
			onImageError(key, index) {
				this[key][index].image = '/static/errorImage.jpg';
			},
			//选中状态处理
			_radio(pro_id, shop_id) { 
				let cart=uni.getStorageSync('cart')
				this.check_shop = !this.check_shop
				cart[shop_id].pro[pro_id].radio = !cart[shop_id].pro[pro_id].radio
				uni.removeStorageSync('cart')
				uni.setStorageSync('cart', cart)
				this.cart=cart
				this.x++
			},
			//店铺全选
			_radio_shop(shop_id) { 			 
				let cart=uni.getStorageSync('cart')
				uni.removeStorageSync('cart')
				let radio=!cart[shop_id].info.radio  //选中结果
				cart[shop_id].info.radio = radio
				let pros=cart[shop_id].pro
				for (let k in pros) {
					const v=pros[k]
					pros[k].radio=radio
				}
				cart[shop_id].pro=pros
				uni.setStorageSync('cart', cart) 
				this.cart=cart
				this.x++
			},

			//删除
			deleteCartItem(shop_id,pro_id) {
				let cart = this.cart
				const that=this
				uni.showModal({
					title: '提示',
					content: '是否删除？',
					success: function (res) {
						if (res.confirm) {
							 delete cart[shop_id].pro[pro_id]
							 const arr = Object.keys(cart[shop_id].pro);  
							 if(arr.length<1){
							 	delete cart[shop_id]
							 }
							 that.cart = []
							 that.cart = cart
							 uni.setStorageSync('cart', cart)
						}  
					}
				});					
			},
			//清空
			clearCart() {
				uni.showModal({
					content: '清空购物车？',
					success: (e) => {
						if (e.confirm) {
							this.cart = {};
							uni.removeStorageSync('cart')
						}
					}
				})
			},

			//创建订单
			createOrder() {
				let cart = uni.getStorageSync('cart')
				let is_jump=false
				for(let sid in cart){
					const shop=cart[sid].pro					
					for(let k in shop){
						const pros=shop[k] 
						if (pros.radio === true) {
							is_jump=true
						}
					}
				} 
				if (is_jump) { 
					uni.navigateTo({
						url: '/pages/order/createOrder?state=car'
					})
				}
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang='scss'>
	.cart {background-color: #F2F2F2;min-height: 100vh;
		padding-bottom: 134upx;
		/* #ifndef H5 */
		padding-top: 50px;
		/* #endif */
		.BH10 {
			
			height: 10px;
		}
		.bei{background-color: #fff;margin: 10px;border-radius: 10px;}
		.shop {
			display: flex;
			padding: 10px;
			font-size: 14px;
			border-bottom: 1px solid #F8F8F8;

			.shop-l radio {
				height: 15px;
				width: 15px;
			}

			.shop-m {
				padding: 0 10px 0 20px;

				img {
					width: 20px;
					height: 20px;
				}
			}

			.shop-r {font-size: 16px;font-weight: 600;padding-left: 10px;
				span {
					color: #ACACAC;
					padding-left: 10px;
				}
			}
		}

		/* 空白页 */
		.empty {
			position: fixed;
			left: 0;
			top: 0;
			width: 100%;
			height: 100vh;
			padding-bottom: 100upx;
			display: flex;
			justify-content: center;
			flex-direction: column;
			align-items: center;
			background: #fff;

			image {
				width: 240upx;
				height: 160upx;
				margin-bottom: 30upx;
			}

			.empty-tips {
				display: flex;
				font-size: $font-sm+2upx;
				color: $font-color-disabled;

				.navigator {
					color: $uni-color-primary;
					margin-left: 16upx;
				}
			}
		}

		/* 购物车列表项 */
		.cart-item {
			display: flex;
			position: relative;
			padding: 30upx 20upx;

			.image-wrappers {
				width: 200upx !important;
				height: 200upx !important;
				flex-shrink: 0;

				img {
					width: 200upx !important;
					height: 200upx !important;
					border-radius: 8upx;
				}
			}

			.checkbox {
				position: absolute;
				left: -16upx;
				top: -16upx;
				z-index: 8;
				font-size: 44upx;
				line-height: 1;
				padding: 14upx;
				color: $font-color-disabled;
				background: #fff;
				border-radius: 50px;
			}
			.chec {
				
				font-size: 44upx;
				line-height: 1;
				padding:80upx 14upx 0 0;
				color: $font-color-disabled;
				background: #fff;
				
			}

			.item-right {
				display: flex;
				flex-direction: column;
				flex: 1;
				overflow: hidden;
				position: relative;
				padding-left: 30upx;
				.dis-liang{display: flex;justify-content: space-between;}
				.title,
				.price {
					font-size: $font-base + 2upx;
					color: $font-color-dark;
					height: 40upx;
					line-height: 40upx;
				}
				.guige{background-color: #F9F9F9;padding-left: 5px;border-radius: 3px;margin-top: 5px;
					font-size: $font-sm + 2upx;
					color: $font-color-light;
					height: 50upx;overflow: hidden;
					line-height: 50upx;display: flex;
					.clamp{min-width: 80px;}
					.xiangxia{padding: 0 5px 5px 5px;}
				}
				

				.price {
					height: 50upx;
					line-height: 50upx;
				}

				.item-right-num {
					display: flex;justify-content: space-between;margin-top: 15px;
				}
			}

			.del-btn {
				padding: 4upx 10upx;
				font-size: 34upx;
				height: 50upx;
				color: $font-color-light;
			}
		}

		/* 底部栏 */
		.action-section {
			/* #ifdef H5 */
			margin-bottom: 100upx;
			/* #endif */
			position: fixed;
			left: 30upx;
			bottom: 30upx;
			z-index: 95;
			display: flex;
			align-items: center;
			width: 690upx;
			height: 100upx;
			padding: 0 30upx;
			background: rgba(255, 255, 255, .9);
			box-shadow: 0 0 20upx 0 rgba(0, 0, 0, .5);
			border-radius: 16upx;

			.checkbox {
				height: 52upx;
				position: relative;

				image {
					width: 52upx;
					height: 100%;
					position: relative;
					z-index: 5;
				}
			}

			.clear-btn {
				position: absolute;
				left: 26upx;
				top: 0;
				z-index: 4;
				width: 110upx;
				height: 52upx;
				line-height: 52upx;
				padding-left: 20upx;
				font-size: $font-base;
				color: #fff;
				background: $font-color-disabled;
				border-radius: 0 50px 50px 0;

			}

			.total-box {
				flex: 1;
				display: flex;
				flex-direction: column;
				text-align: right;
				padding-right: 40upx;

				.price {
					font-size: $font-lg;
					color: $font-color-dark;
				}

				.coupon {
					font-size: $font-sm;
					color: $font-color-light;

					text {
						color: $font-color-dark;
					}
				}
			}

			.confirm-btn {
				padding: 0 38upx;
				margin: 0;
				border-radius: 100px;
				height: 76upx;
				line-height: 76upx;
				font-size: $font-base + 2upx;
				background: $uni-color-primary;
				box-shadow: 1px 2px 5px rgba(217, 60, 93, 0.72)
			}
		}

		/* 复选框选中状态 */
		.action-section  .checkbox.checked,
		.cart-item  .chec.checked,
		.cart-item  .checkbox.checked {
			color: $uni-color-primary;
		}

	}
</style>
