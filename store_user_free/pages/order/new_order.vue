<template>
	<view>
		<view class="wrap">
			<view class="u-tabs-box">
				<u-tabs-swiper activeColor="#f29100" ref="tabs" :list="list" :current="current" @change="change" :is-scroll="false" swiperWidth="750"></u-tabs-swiper>
			</view>
			<swiper class="swiper-box" :current="swiperCurrent" @transition="transition" @animationfinish="animationfinish">
				<swiper-item class="swiper-item" >
					<scroll-view scroll-y style="height: 100%;width: 100%;" @scrolltolower="reachBottom" v-if="orderList[0].length>0" >
						<view class="page-box">
							<view class="order" v-for="(res, index) in orderList[0]" :key="res.id"  >
								<view class="top">
									<view class="left">
										<u-icon name="home" :size="30" color="rgb(94,94,94)"></u-icon>
										<view class="store" @click="jump_shops(res.shop_id*1)">{{ res.shops.shop_name }}</view>
										<u-icon name="arrow-right" color="rgb(203,203,203)" :size="26"></u-icon>
									</view>
									<view class="right">待付款</view>
								</view>
								<view class="item" v-for="(item, index) in res.order_goods" :key="index" @click="jumo_tomyorder(res.order_id*1)">
									<view class="left"><image :src="item.goods_imgs.thumb_img" mode="aspectFill"></image></view>
									<view class="content">
										<view class="title u-line-2">{{ item.goods_name }}</view>
										<view class="type">{{ item.sku_name?item.sku_name:'无规格' }}</view>
										<view class="delivery-time">发货时间 下单后七天内发货</view>
									</view>
									<view class="right">
										<view class="price">
											￥{{ priceInt(item.price*1) }}
											<text class="decimal">.{{ priceDecimal(item.price*1) }}</text>
										</view>
										<view class="number">x{{ item.num }}</view>
									</view>
								</view>
								<view class="total">
									共{{ totalNum(res.order_goods) }}件商品 合计:
									<text class="total-price">
										￥{{ priceInt(totalPrice(res.order_goods)) }}
										<!-- <text class="decimal">{{ item.order_money }}</text> -->
									</text>
								</view>
								<view class="bottom">
									<view class="more"><u-icon name="more-dot-fill" color="rgb(203,203,203)"></u-icon></view>
									<!-- <view class="logistics btn">查看物流</view> -->
									<view class="exchange btn" @click="deleteOrder(res.order_id*1)">取消订单</view>
									
									<view class="evaluate btn" @click="pay_again(res.order_id*1)">去支付</view>
								</view>
							</view>
							<u-loadmore :status="loadStatus[0]" bgColor="#f2f2f2"></u-loadmore>
						</view>
					</scroll-view>
					<scroll-view scroll-y style="height: 100%;width: 100%;" v-else>
						<view class="page-box">
							<view>
								<view class="centre">
									<image style="margin-left: 40%;" src="https://cdn.uviewui.com/uview/template/taobao-order.png" mode=""></image>
									<view class="explain">
										您还没有相关的订单
										<view class="tips">可以去看看有那些想买的</view>
									</view>
									<view class="btn" @click="jump_index">随便逛逛</view>
								</view>
							</view>
						</view>
					</scroll-view>
				</swiper-item>
				<swiper-item class="swiper-item">
					<scroll-view scroll-y style="height: 100%;width: 100%;" @scrolltolower="reachBottom" v-if="orderList[1].length>0">
						<view class="page-box">
							<view class="order" v-for="(res, index) in orderList[1]" :key="res.id" >
								<view class="top">
									<view class="left">
										<u-icon name="home" :size="30" color="rgb(94,94,94)"></u-icon>
										<view class="store" @click="jump_shops(res.shop_id)">{{ res.shops.shop_name }}</view>
										<u-icon name="arrow-right" color="rgb(203,203,203)" :size="26"></u-icon>
									</view>
									<view class="right">待发货</view>
								</view>
								<view class="item" v-for="(item, index) in res.order_goods" :key="index" @click="jumo_tomyorder(res.order_id*1)">
									<view class="left"><image :src="item.goods_imgs.thumb_img" mode="aspectFill"></image></view>
									<view class="content">
										<view class="title u-line-2">{{ item.goods_name }}</view>
										<view class="type">{{ item.sku_name?item.sku_name:'无规格' }}</view>
										<view class="delivery-time">发货时间 下单后七天内发货</view>
									</view>
									<view class="right">
										<view class="price">
											￥{{ priceInt(item.price*1) }}
											<text class="decimal">.{{ priceDecimal(item.price*1) }}</text>
										</view>
										<view class="number">x{{ item.num }}</view>
									</view>
								</view>
								<view class="total">
									共{{ totalNum(res.order_goods) }}件商品 合计:
									<text class="total-price">
										￥{{ priceInt(totalPrice(res.order_goods)) }}
										<!-- <text class="decimal">{{ item.order_money }}</text> -->
									</text>
								</view>
								<view class="bottom">
									<view class="more"><u-icon name="more-dot-fill" color="rgb(203,203,203)"></u-icon></view>
									<!-- <view class="logistics btn">查看物流</view> -->
									<!-- <view class="exchange btn">卖了换钱</view> -->
									<!-- <view class="evaluate btn">去支付</view> -->
								</view>
							</view>
							<u-loadmore :status="loadStatus[0]" bgColor="#f2f2f2"></u-loadmore>
						</view>
					</scroll-view>
					<scroll-view scroll-y style="height: 100%;width: 100%;" v-else>
						<view class="page-box">
							<view>
								<view class="centre">
									<image style="margin-left: 40%;" src="https://cdn.uviewui.com/uview/template/taobao-order.png" mode=""></image>
									<view class="explain">
										您还没有相关的订单
										<view class="tips">可以去看看有那些想买的</view>
									</view>
									<view class="btn" @click="jump_index">随便逛逛</view>
								</view>
							</view>
						</view>
					</scroll-view>
				</swiper-item>
				<swiper-item class="swiper-item">
					<scroll-view scroll-y style="height: 100%;width: 100%;" @scrolltolower="reachBottom" v-if="orderList[2].length>0">
						<view class="page-box">
							<view class="order" v-for="(res, index) in orderList[2]" :key="res.id" >
								<view class="top">
									<view class="left">
										<u-icon name="home" :size="30" color="rgb(94,94,94)"></u-icon>
										<view class="store" @click="jump_shops(res.shop_id)">{{ res.shops.shop_name }}</view>
										<u-icon name="arrow-right" color="rgb(203,203,203)" :size="26"></u-icon>
									</view>
									<view class="right">待收货</view>
								</view>
								<view class="item" v-for="(item, index) in res.order_goods" :key="index" @click="jumo_tomyorder(res.order_id*1)">
									<view class="left"><image :src="item.goods_imgs.thumb_img" mode="aspectFill"></image></view>
									<view class="content">
										<view class="title u-line-2">{{ item.goods_name }}</view>
										<view class="type">{{ item.sku_name?item.sku_name:'无规格' }}</view>
										<view class="delivery-time">发货时间 下单后七天内发货</view>
									</view>
									<view class="right">
										<view class="price">
											￥{{ priceInt(item.price*1) }}
											<text class="decimal">.{{ priceDecimal(item.price*1) }}</text>
										</view>
										<view class="number">x{{ item.num }}</view>
									</view>
								</view>
								<view class="total">
									共{{ totalNum(res.order_goods) }}件商品 合计:
									<text class="total-price">
										￥{{ priceInt(totalPrice(res.order_goods)) }}
										<!-- <text class="decimal">{{ item.order_money }}</text> -->
									</text>
								</view>
								<view class="bottom">
									<view class="more"><u-icon name="more-dot-fill" color="rgb(203,203,203)"></u-icon></view>
									<view class="logistics btn" @click="jump_wuliu(res.order_id*1)">查看物流</view>
									<!-- <view class="exchange btn">卖了换钱</view> -->
									<!-- <view class="evaluate btn">去支付</view> -->
								</view>
							</view>
							<u-loadmore :status="loadStatus[0]" bgColor="#f2f2f2"></u-loadmore>
						</view>
					</scroll-view>
					<scroll-view scroll-y style="height: 100%;width: 100%;" v-else>
						<view class="page-box">
							<view>
								<view class="centre">
									<image style="margin-left: 40%;" src="https://cdn.uviewui.com/uview/template/taobao-order.png" mode=""></image>
									<view class="explain">
										您还没有相关的订单
										<view class="tips">可以去看看有那些想买的</view>
									</view>
									<view class="btn" @click="jump_index">随便逛逛</view>
								</view>
							</view>
						</view>
					</scroll-view>
				</swiper-item>
				<swiper-item class="swiper-item">
					<scroll-view scroll-y style="height: 100%;width: 100%;" @scrolltolower="reachBottom" v-if="orderList[3].length>0">
						<view class="page-box">
							<view class="order" v-for="(res, index) in orderList[3]" :key="res.id" >
								<view class="top">
									<view class="left">
										<u-icon name="home" :size="30" color="rgb(94,94,94)"></u-icon>
										<view class="store" @click="jump_shops(res.shop_id)">{{ res.shops.shop_name }}</view>
										<u-icon name="arrow-right" color="rgb(203,203,203)" :size="26"></u-icon>
									</view>
									<view class="right">待评价</view>
								</view>
								<view class="item" v-for="(item, index) in res.order_goods" :key="index" @click="jumo_tomyorder(res.order_id*1)">
									<view class="left"><image :src="item.goods_imgs.thumb_img" mode="aspectFill"></image></view>
									<view class="content">
										<view class="title u-line-2">{{ item.goods_name }}</view>
										<view class="type">{{ item.sku_name?item.sku_name:'无规格' }}</view>
										<view class="delivery-time">发货时间 下单后七天内发货</view>
									</view>
									<view class="right">
										<view class="price">
											￥{{ priceInt(item.price*1) }}
											<text class="decimal">.{{ priceDecimal(item.price*1) }}</text>
										</view>
										<view class="number">x{{ item.num }}</view>
									</view>
								</view>
								<view class="total">
									共{{ totalNum(res.order_goods) }}件商品 合计:
									<text class="total-price">
										￥{{ priceInt(totalPrice(res.order_goods)) }}
										<!-- <text class="decimal">{{ item.order_money }}</text> -->
									</text>
								</view>
								<view class="bottom">
									<view class="more"><u-icon name="more-dot-fill" color="rgb(203,203,203)"></u-icon></view>
									<!-- <view class="logistics btn">查看物流</view> -->
									<!-- <view class="exchange btn">卖了换钱</view> -->
									<view class="evaluate btn">去评价</view>
								</view>
							</view>
							<u-loadmore :status="loadStatus[0]" bgColor="#f2f2f2"></u-loadmore>
						</view>
					</scroll-view>
					<scroll-view scroll-y style="height: 100%;width: 100%;" v-else>
						<view class="page-box">
							<view>
								<view class="centre">
									<image style="margin-left: 40%;" src="https://cdn.uviewui.com/uview/template/taobao-order.png" mode=""></image>
									<view class="explain">
										您还没有相关的订单
										<view class="tips">可以去看看有那些想买的</view>
									</view>
									<view class="btn" @click="jump_index">随便逛逛</view>
								</view>
							</view>
						</view>
					</scroll-view>
				</swiper-item>
			</swiper>
		</view>
	</view>
</template>

<script>
export default {
	data() {
		return {
			orderList: [[], [], [], []],
			dataList: [
				{
					id: 1,
					store: '夏日流星限定贩卖',
					deal: '交易成功',
					goodsList: [
						{
							goodsUrl: '//img13.360buyimg.com/n7/jfs/t1/103005/7/17719/314825/5e8c19faEb7eed50d/5b81ae4b2f7f3bb7.jpg',
							title: '【冬日限定】现货 原创jk制服女2020冬装新款小清新宽松软糯毛衣外套女开衫短款百搭日系甜美风',
							type: '灰色;M',
							deliveryTime: '付款后30天内发货',
							price: '348.58',
							number: 2
						},
						{
							goodsUrl: '//img12.360buyimg.com/n7/jfs/t1/102191/19/9072/330688/5e0af7cfE17698872/c91c00d713bf729a.jpg',
							title: '【葡萄藤】现货 小清新学院风制服格裙百褶裙女短款百搭日系甜美风原创jk制服女2020新款',
							type: '45cm;S',
							deliveryTime: '付款后30天内发货',
							price: '135.00',
							number: 1
						}
					]
				},
				{
					id: 2,
					store: '江南皮革厂',
					deal: '交易失败',
					goodsList: [
						{
							goodsUrl: '//img14.360buyimg.com/n7/jfs/t1/60319/15/6105/406802/5d43f68aE9f00db8c/0affb7ac46c345e2.jpg',
							title: '【冬日限定】现货 原创jk制服女2020冬装新款小清新宽松软糯毛衣外套女开衫短款百搭日系甜美风',
							type: '粉色;M',
							deliveryTime: '付款后7天内发货',
							price: '128.05',
							number: 1
						}
					]
				},
				{
					id: 3,
					store: '三星旗舰店',
					deal: '交易失败',
					goodsList: [
						{
							goodsUrl: '//img11.360buyimg.com/n7/jfs/t1/94448/29/2734/524808/5dd4cc16E990dfb6b/59c256f85a8c3757.jpg',
							title: '三星（SAMSUNG）京品家电 UA65RUF70AJXXZ 65英寸4K超高清 HDR 京东微联 智能语音 教育资源液晶电视机',
							type: '4K，广色域',
							deliveryTime: '保质5年',
							price: '1998',
							number: 3
						},
						{
							goodsUrl: '//img14.360buyimg.com/n7/jfs/t6007/205/4099529191/294869/ae4e6d4f/595dcf19Ndce3227d.jpg!q90.jpg',
							title: '美的(Midea)639升 对开门冰箱 19分钟急速净味 一级能效冷藏双开门杀菌智能家用双变频节能 BCD-639WKPZM(E)',
							type: '容量大，速冻',
							deliveryTime: '保质5年',
							price: '2354',
							number: 1
						}
					]
				},
				{
					id: 4,
					store: '三星旗舰店',
					deal: '交易失败',
					goodsList: [
						{
							goodsUrl: '//img10.360buyimg.com/n7/jfs/t22300/31/1505958241/171936/9e201a89/5b2b12ffNe6dbb594.jpg!q90.jpg',
							title: '法国进口红酒 拉菲（LAFITE）传奇波尔多干红葡萄酒750ml*6整箱装',
							type: '4K，广色域',
							deliveryTime: '珍藏10年好酒',
							price: '1543',
							number: 3
						},
						{
							goodsUrl: '//img10.360buyimg.com/n7/jfs/t1/107598/17/3766/525060/5e143aacE9a94d43c/03573ae60b8bf0ee.jpg',
							title: '蓝妹（BLUE GIRL）酷爽啤酒 清啤 原装进口啤酒 罐装 500ml*9听 整箱装',
							type: '一打',
							deliveryTime: '口感好',
							price: '120',
							number: 1
						}
					]
				},
				{
					id: 5,
					store: '三星旗舰店',
					deal: '交易成功',
					goodsList: [
						{
							goodsUrl: '//img12.360buyimg.com/n7/jfs/t1/52408/35/3554/78293/5d12e9cfEfd118ba1/ba5995e62cbd747f.jpg!q90.jpg',
							title: '企业微信 中控人脸指纹识别考勤机刷脸机 无线签到异地多店打卡机WX108',
							type: '识别效率高',
							deliveryTime: '使用方便',
							price: '451',
							number: 9
						}
					]
				}
			],
			list: [
				{
					name: '待付款'
				},
				{
					name: '待发货'
				},
				{
					name: '待收货'
				},
				{
					name: '待评价'
				}
			],
			current: 0,
			swiperCurrent: 0,
			tabsHeight: 0,
			dx: 0,
			loadStatus: ['loadmore','loadmore','loadmore','loadmore'],
		};
	},
	onLoad(option) {
		console.log(option)
		if(!option.state){
			this.current = 0
		}else{
			this.current = option.state*1
		}
		console.log(this.current)
		this.getOrderList(0);
		// this.getOrderList(1);
		// this.getOrderList(3);
	},
	computed: {
		// 价格小数
		priceDecimal() {
			return val => {
				if (val !== parseInt(val)) return val.slice(-2);
				else return '00';
			};
		},
		// 价格整数
		priceInt() {
			return val => {
				if (val !== parseInt(val)) return val.split('.')[0];
				else return val;
			};
		}
	},
	methods: {
		jump_index(){
			uni.switchTab({
				url:'../index/index'
			})
		},
		//删除订单
		deleteOrder(id) {
			const that = this
			uni.showModal({
				title: '提示',
				content: '确定删除订单？',
				success: function(res) {
					if (res.confirm) {
						console.log('用户点击确定');
						that.$api.http.put('order/user/del_order?id=' + id).then(res => {
							uni.showToast({
								title: '删除成功',
								duration: 2000
							});
							that.getOrderList()
						})
					} else if (res.cancel) {
						console.log('用户点击取消');
					}
				}
			});
		},
		jump_shops(id){
			uni.navigateTo({
				url:'../shop/shop?id='+id
			})
		},
		jump_wuliu(id){
				console.log(id);
				uni.navigateTo({
					url:'../../shops/user/myorder/drive/drive?id='+id
				})
		},
		jumo_tomyorder(id) {
			uni.navigateTo({
				url: '../myorder/myorder?id=' + id
			})
		},
		//二次支付
		async pay_again(id){
			console.log(id)
			let url='order/second_pay'
			// #ifdef MP-WEIXIN
				url='order/pay/pre_order'
			// #endif
			const pay_data = await this.$api.http.post(url, {
				id: id
			}).then(res => { 
				return res
			})
			//#ifdef MP-WEIXIN
				await this.onpay(pay_data)
			//#endif
			//#ifdef H5
				await this.wxPay(pay_data)
			//#endif
		},
		//支付
		onpay(data) {
			const that = this
			uni.requestPayment({
				provider: "wxpay",
				timeStamp: data.timeStamp,
				nonceStr: data.nonceStr,
				package: data.package,
				signType: data.signType,
				paySign: data.paySign,
				success: function(res) {
					console.log('success:' + JSON.stringify(res));
					that.getOrderList()
				},
				fail: function(err) {
					console.log('fail:' + JSON.stringify(err));
				}
			});
		},
		//公众号支付
		wxPay(json) { 
			if (typeof WeixinJSBridge == "undefined") { 
				if (document.addEventListener) {
					document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
				} else if (document.attachEvent) {
					document.attachEvent("WeixinJSBridgeReady", jsApiCall);
					document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
				}
			} else {
				this.jsApiCall(json);
			}
		},
		jsApiCall(json) { 
			const that = this;
			WeixinJSBridge.invoke("getBrandWCPayRequest", json, function(res) {
				WeixinJSBridge.log('a:', res.err_msg);
				if (res.err_msg == "get_brand_wcpay_request:ok") {
					that.$api.msg("支付成功!");
					uni.navigateTo({
						url: '../invite/invite?id=' + that.pid
					})
					return
				} else if (res.err_msg == "get_brand_wcpay_request:cancel") {
					that.$api.msg("取消支付");
				} else {
					that.$api.msg("支付失败");
				}
				setTimeout(() => {
					uni.redirectTo({
						url: '/pages/order/order'
					});
				}, 1000);
			});
		},
		reachBottom() {
			
		},
		// 页面数据
		getOrderList(idx) {
			this.orderList = [[],[],[],[]]
			const that = this
			// for(let i = 0; i < 5; i++) {
			// 	let index = this.$u.random(0, this.dataList.length - 1);
			// 	console.log(index)
			// 	let data = JSON.parse(JSON.stringify(this.dataList[index]));
			// 	data.id = this.$u.guid();
			// 	console.log(data)
			// 	this.orderList[idx].push(data);
				
			// }
			// this.loadStatus.splice(this.current,1,"loadmore")
			
			this.$api.http.post('shops/user/all_order').then(res=>{
				for (let k in res.data) {
					let v = res.data[k]
					if(v.payment_state == 0){
						that.orderList[0].push(v)
					}
					else if(v.payment_state == 1 && v.shipment_state==0){
						that.orderList[1].push(v)
					}
					else if(v.payment_state == 1 && v.shipment_state==1){
						that.orderList[2].push(v)
					}
					else if(v.state == 1){
						that.orderList[3].push(v)
					}
				}
				console.log(that.orderList)
			})
			
			
			
		},
		// 总价
		totalPrice(item) {
			let price = 0;
			item.map(val => {
				price += parseFloat(val.price);
			});
			return price.toFixed(2);
		},
		// 总件数
		totalNum(item) {
			let num = 0;
			for (let k in item) {
				let v = item[k]
				num += v.num
			}
			
			return num;
		},
		// tab栏切换
		change(index) {
			this.orderList = [[],[],[],[]]
			this.swiperCurrent = index;
			this.getOrderList(index);
		},
		transition({ detail: { dx } }) {
			this.$refs.tabs.setDx(dx);
		},
		animationfinish({ detail: { current } }) {
			this.$refs.tabs.setFinishCurrent(current);
			this.swiperCurrent = current;
			this.current = current;
		}
	}
};
</script>

<style>
/* #ifndef H5 */
page {
	height: 100%;
	background-color: #f2f2f2;
}
/* #endif */
</style>

<style lang="scss" scoped>
.order {
	width: 710rpx;
	background-color: #ffffff;
	margin: 20rpx auto;
	border-radius: 20rpx;
	box-sizing: border-box;
	padding: 20rpx;
	font-size: 28rpx;
	.top {
		display: flex;
		justify-content: space-between;
		.left {
			display: flex;
			align-items: center;
			.store {
				margin: 0 10rpx;
				font-size: 32rpx;
				font-weight: bold;
			}
		}
		.right {
			color: $u-type-warning-dark;
		}
	}
	.item {
		display: flex;
		margin: 20rpx 0 0;
		.left {
			margin-right: 20rpx;
			image {
				width: 200rpx;
				height: 200rpx;
				border-radius: 10rpx;
			}
		}
		.content {
			.title {
				font-size: 28rpx;
				line-height: 50rpx;
			}
			.type {
				margin: 10rpx 0;
				font-size: 24rpx;
				color: $u-tips-color;
			}
			.delivery-time {
				color: #e5d001;
				font-size: 24rpx;
			}
		}
		.right {
			margin-left: 10rpx;
			padding-top: 20rpx;
			text-align: right;
			.decimal {
				font-size: 24rpx;
				margin-top: 4rpx;
			}
			.number {
				color: $u-tips-color;
				font-size: 24rpx;
			}
		}
	}
	.total {
		margin-top: 20rpx;
		text-align: right;
		font-size: 24rpx;
		.total-price {
			font-size: 32rpx;
		}
	}
	.bottom {
		display: flex;
		margin-top: 40rpx;
		padding: 0 10rpx;
		justify-content: space-between;
		align-items: center;
		.btn {
			line-height: 52rpx;
			width: 160rpx;
			border-radius: 26rpx;
			border: 2rpx solid $u-border-color;
			font-size: 26rpx;
			text-align: center;
			color: $u-type-info-dark;
		}
		.evaluate {
			color: $u-type-warning-dark;
			border-color: $u-type-warning-dark;
		}
	}
}
.centre {
	text-align: center;
	margin: 200rpx auto;
	font-size: 32rpx;
	image {
		width: 164rpx;
		height: 164rpx;
		border-radius: 50%;
		margin-bottom: 20rpx;
	}
	.tips {
		font-size: 24rpx;
		color: #999999;
		margin-top: 20rpx;
	}
	.btn {
		margin: 80rpx auto;
		width: 200rpx;
		border-radius: 32rpx;
		line-height: 64rpx;
		color: #ffffff;
		font-size: 26rpx;
		background: linear-gradient(270deg, rgba(249, 116, 90, 1) 0%, rgba(255, 158, 1, 1) 100%);
	}
}
.wrap {
	display: flex;
	flex-direction: column;
	height: calc(100vh - var(--window-top));
	width: 100%;
}
.swiper-box {
	flex: 1;
}
.swiper-item {
	height: 100%;
}
</style>
