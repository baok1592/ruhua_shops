<template> 

	<view>
		<!-- 地址 -->
		<view class="address-section" @click="jump_address()"> 
			<view class="order-content">
				<text class="yticon icon-shouhuodizhi"></text>
				<view class="cen" v-if="address">
					<view class="top">
						<text class="name">{{address.name}}</text>
						<text class="mobile">{{address.mobile}}</text>
					</view>
					<text class="address">{{address.province}} {{address.city}}{{address.county}}</text>
				</view>
				<text class="yticon icon-you"></text>
			</view> 
		</view> 
		<view class="goods-section" v-for="(shop,shop_id) of buy_data" :key="shop_id"> 
			<view class="g-header ">
				<text class="cuIcon-shop"></text>
				<text class="name">{{shop.info.shop_name}}</text>
			</view>
			<!-- 商品列表 -->
			<view class="g-item" v-if="shop.pro" v-for="(item,index) of shop.pro" :key="index">
				<image :src="getimg + item.imgs"></image>
				<view class="right">
					<text class="title clamp">{{item.goods_name}}</text>
					<text class="spec">{{item.sku_name?item.sku_name:''}}</text>
					<view class="price-box">
						
						<text class="price" v-if="item.vip_price">￥{{item.price - item.vip_price}}</text>
						<text class="price" v-else>￥{{item.price}}</text>
						<!-- <text class="price">￥{{item.price}}</text> -->
						<text class="number">x {{item.num}}</text>
					</view>
				</view>
			</view>
			<view class="msg">
				<view class="msg-l">买家留言：</view>
				<input placeholder="留言内容" />
			</view>
		</view>
		
	 	
		<!-- 优惠明细 -->
		<!-- <view class="yt-list">
			<view class="yt-list-cell b-b" @click="toggleMask('show')">
				<view class="cell-icon">
					券
				</view>
				<text class="cell-tit clamp">优惠券</text>
				<text class="cell-tip active">
					{{coupon_text}}
				</text>
				<text class="cell-more wanjia wanjia-gengduo-d"></text>
			</view> 
		</view> -->
		
		<!-- 金额明细 -->
		<view class="yt-list">
			<view class="yt-list-cell b-b">
				<text class="cell-tit clamp">商品金额</text>
				<text class="cell-tip">￥{{goods_money}}</text>
			</view>
			<view class="yt-list-cell b-b" v-if="coupon_money>0">
				<text class="cell-tit clamp">优惠金额</text>
				<text class="cell-tip red">-￥{{coupon_money}}</text>
			</view>
			<view class="yt-list-cell b-b">
				<text class="cell-tit clamp">运费</text>
				<text class="cell-tip" v-if="yunfei_money">￥ {{yunfei_money}}</text>
				<text class="cell-tip" v-else>免运费</text>
			</view> 
		</view>

		<!-- 底部 -->
		<view class="footer">
			<view class="price-content">
				<text>实付款</text>
				<text class="price-tip">￥</text>
				<text class="price">{{pay_money}}</text>
			</view>
			<text class="submit" @click="submit">提交订单</text>
		</view>
 

		<!-- 购买前的提示，不需要可以删除 -->
		<view class="tan" v-if="xieyi">
			<div class="black">
				<!-- 弹窗蒙层 -->
				<div class="container"></div>	
			</div>
			<view class="t_con"> 
				<view class="t_c_con"><rich-text :nodes="content"></rich-text> </view> 
				
				<view class="t_c_btn">
					<view class="t_c_btn_01 bg_gery" @click="xy_approve(false)">不同意</view>
					<view class="t_c_btn_01 bg_red" @click="xy_approve(true)">同意</view>
				</view>
			</view>
		</view>
		
		<view class="pay">
			<radio-group class="radio-group" name="sex" @change="radioChange">
				<view class="pay-01">
					<view class="pay-l"><img src="@/static/imgs/zhi.png"></img></view>
					<view class="pay-m">支付宝<span>(首单随机立减，最高免单)</span></view>
					<view class="pay-r" >
						<radio value="0" name="pay" color="#09BB07" disabled />
					</view>
				</view>
				<view class="pay-01">
					<view class="pay-l"><img src="@/static/imgs/wxzf.png"></img></view>
					<view class="pay-m">微信支付</view>
					<view class="pay-r" >
						<radio value="1" name="pay" color="#09BB07" checked />
					</view>
				</view>
				<view class="pay-01">
					<view class="pay-l"><img src="@/static/imgs/hua.png"></img></view>
					<view class="pay-m">花呗</view>
					<view class="pay-r" >
						<radio value="2" name="pay" color="#09BB07" disabled />
					</view>
				</view> 
			</radio-group>
			
		</view>
		
	</view>
</template> 

<script>	 
	export default { 
		data() {
			return {
				content:'演示商品，请勿购买。如需测试支付功能，购买后不发货不退款',
				xieyi:false,	//购买前的弹窗提示 
				sku_id:'',
				head: '',
				is_kai: 0,
				pid: '',
				is_pt: 0,
				pt_data: '',
				coupon_id: 0,
				save_cache: {},
				order_id: 0,
				getimg: this.$getimg,
				maskState: 0, //优惠券面板显示状态
				desc: '', //备注
				payType: 1, //1微信 2支付宝
				yunfei_money: 0, //运费
				couponList: [],
				addressData: {},
				state: '',
				buy_data: '',
				address: '',
				paying: '', //防止多次提交订单
				coupon_text: '选择优惠券',
				coupon_money: 0, //优惠金额				
				obj: {
					
					msg: "",
					order_from: "xcx",
					payment_type: "wx",
					total_price: "",
					shoping_price: "",
					coupon_price: "",
					coupon_id: 0,
					discount: "",
					switch_list:'',
					invoice_switch:false,
					sfm:uni.getStorageSync('level_one')
				}

			}
		},
		onLoad(option) {
			this.state = option.state
			this.switch_list = uni.getStorageSync('switch')
			this.check_switch()
			if (option.state == 'buy') {
				let buy_data = uni.getStorageSync('buy')
				this.buy_data = buy_data
			}
			if (option.state == 'car') {
				console.log("car:")
				let cart = uni.getStorageSync('cart')
				let arr = {}
				let cache = {}
				let x = 0
				if(!cart){
					return ;
				}
				for(let sid in cart){
					const shop_pros=cart[sid].pro
					arr[x]={}
					arr[x]['info']=cart[sid].info 
					let s_pros={} 
					for(let k in shop_pros){
						const pro=shop_pros[k]  
						if (pro.radio === true) { 
							s_pros[k] = pro //购物车中选中的商品	
						}else {
							cache[sid] = cart[sid] //购物车未选中的商品 
						}					
					}  	
					arr[x]['pro']=s_pros
					x++
				}  
				console.log("srr:",arr)
				this.buy_data = arr
				this.save_cache = cache
			} 
			let id = uni.getStorageSync('pid')
			if (id) {
				this.pid = id
				console.log(id)
				this.is_pt = 1
				this.get_pid(id)
			}
			this.is_kai = uni.getStorageSync('is_kai')
			uni.removeStorageSync('is_kai') 
			this._load() 
		},
		onShow() {
			this.$api.http.get('address/get_default_address').then(res => {
				this.address = res.data
			})
		},
		computed: {
			//商品金额
			goods_money() {
				return this.set_goods_money() 
			},
			//订单应付金额
			pay_money() { 
				let a = 0
				if(this.goods_money){
					a = this.goods_money*1 + this.yunfei_money*1 - this.coupon_money*1;
					let count = a
					console.log('>>>>>>>>',count)
					return Math.round(count*100)/100
				}else{
					return 0;
				}
			}
		}, 
		methods: {
			
			jump_address(){
				console.log("xxxa")
				uni.navigateTo({
					url:"/pages/address/address?source=1"
				})
			},
			set_goods_money() {
				const cart = this.buy_data 
				let total = 0
				if(!cart){
					return total;
				}
				console.log("js:",cart)				 
				for(let sid in cart){
					const shop=cart[sid].pro					
					for(let k in shop){
						const pro=shop[k] 
						total += pro.price * pro.num
						if(pro.vip_price){
							total = total - pro.vip_price
						}
					}
				}  
				return total;
			},
			check_switch(){
				const that = this
				that.invoice_switch = this.switch_list.is_invoice == 1?true:false
			},
			_load() {
				
				this.get_yunfei()
			},
			jump_invoices(){
				uni.navigateTo({
					url: '/pages/invoices/invoices',
				});
			},
			get_pid(id) {
				this.$api.http.get('pt/get_one_item', {
					id: id
				}).then(res => {
					this.pt_data = res.data
					uni.removeStorageSync('pid');
				})
			},
			//获取运费
			get_yunfei() {
				console.log('get_yunfei', this.buy_data)
				const buy_data = this.buy_data
				let obj = {}
				for (let key in buy_data) {
					const pro = buy_data[key].pro
					for (let k in pro) {
						const v=pro[k]
						obj[k] = {}
						obj[k]['goods_id'] = v.goods_id
						obj[k]['num'] = v.num
					}
				}
				console.log('v:', obj)
				//计算运费
				this.$api.http.post('product/get_shipment_price', obj).then(res => {
					this.yunfei_money = res.data
				}) 
			},
			
			numberChange(data) {
				this.number = data.number;
			},
			changePayType(type) {
				this.payType = type;
			},
			//判断商品中是否有分销商品
			check_fx_pro() {
				
			},
			//设置订单数据
			set_order_data() {
				console.log(this.buy_data) 
				let obj = this.obj   			
				obj.json = this.buy_data
				obj.order_from = 0
				obj.payment_type = 'xcx'
				obj.money = this.pay_money  
				obj.total_price = this.pay_money
				const level_one = uni.getStorageSync('level_one')
				
				return obj;
			},
			//设置创建订单的url
			check_sub_data() {
				
				if (!this.address) {
					this.$api.msg('未填写地址')
					return;
				}
				if (this.paying) {
					return;
				}
				let url = ''
				//#ifdef MP-WEIXIN || APP-PLUS
				url = 'shops/order/create_order'
				//#endif
				//#ifdef H5
				// url = 'order/create_cart'
					url =  'shops/order/gzh_create_order'
				//#endif 
				return url
			},
			xy_approve(e){
				this.xieyi=false
				if(!e){
					uni.navigateBack({						
					})
					return;
				}
			},
			async submit() {
				let is_pin = uni.getStorageSync('is_item')
				uni.removeStorageSync('is_item')
				const url = this.check_sub_data() 
				if (!url) {
					return;
				}
				let obj = this.set_order_data() 
				if(this.obj.total_price<=0){
					this.$api.msg('订单金额必须大于0')
					return;
				}
				console.log('订单数据',obj)
				//普通下单
				const order_id = await this.$api.http.post(url, obj).then(res => {
					if(res.data && res.data.id){
						return res.data.id
					}else{
						return res.data;
					}
				}) 
				if(order_id<1){
					return;
				}
				this.order_id = order_id
				
				this.paying = true  
				if (this.state == 'buy') {
					uni.removeStorageSync('buy')
				} else {
					uni.removeStorageSync('cart')
					uni.setStorageSync('cart', this.save_cache)
				} 
		
				//#ifdef MP-WEIXIN
					const pay_data = await this.$api.http.post('shops/order/pay', {
						id: order_id
					}).then(res => {
						console.log('pay:', res)
						return res
					})
					await this.pay(pay_data)
				//#endif
		
				//#ifdef APP-PLUS 
					console.log('id:', order_id)
					const app_data = await this.$api.http.post('order/pay/pre_app', {
						id: order_id
					}).then(res => {
						console.log('app-pay:', res)
						return res
					})
					console.log('获取到app支付参数：', app_data)
					await this.app_pay(app_data)
				//#endif
		
		
				//#ifdef H5
					console.log("order_id",order_id)
					this.wxPay(order_id)
				//#endif
			},
			//公众号支付
			wxPay(json) {
				console.log("a1")
				if (typeof WeixinJSBridge == "undefined") {
					console.log("a2")
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
						uni.redirectTo({
							url: '/pages/order/order'
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
						})
					}, 1000);
				});
			},
			//小程序支付
			pay(data) {
				uni.requestPayment({
					provider: "wxpay",
					timeStamp: data.timeStamp,
					nonceStr: data.nonceStr,
					package: data.package,
					signType: data.signType,
					paySign: data.paySign,
					success: function(res) {
						console.log('success:', res);
						uni.redirectTo({
							url: '/pages/order/order'
						})
					},
					fail: function(err) {
						console.log('fail:' + JSON.stringify(err));
						uni.redirectTo({
							url: '/pages/order/order'
						})
					}
				});
			},
			//APP支付
			app_pay(data) {
				const order_id = this.order_id
				uni.requestPayment({
					provider: "wxpay",
					orderInfo: JSON.stringify(data),
					success: function(res) {
						console.log('success:', res);
						uni.redirectTo({
							url: '/pages/user/myorder/myorder?id=' + order_id
						})
					},
					fail: function(err) {
						console.log('fail:' + JSON.stringify(err));
						uni.redirectTo({
							url: '/pages/user/myorder/myorder?id=' + order_id
						})
					}
				});
			}
		},
		onPullDownRefresh() {
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
	page {
		background: $page-color-base;
		padding-bottom: 100upx;
	}
	.cuIcon-shop{font-size: 20px;}
	.pay{margin: 10px;background-color: #fff;border-radius: 5px;font-size: 14px;padding: 10px;
		.pay-01{display: flex;padding: 5px 0;
			.pay-l img{width: 18px;height: 18px;}
			.pay-m{flex-grow: 1;padding: 0 10px;
				span{color: #E63E62;font-size: 12px;}
			}
			.pay-r checkbox{width: 20px;height: 20px;}
		}
	}
	.black{
			.container {
			    background-color: #000000;
			    position: fixed;
			    top: 0;
			    opacity: 0.6;
			    width: 100%;
			    height: 100%;
			    z-index: 999;
			} 
		
		}
	.t_con{background-color: #fff;position:fixed;top: 120px;left: 10%;width: 80%;padding: 20px;border-radius: 5px;
			z-index: 1999;overflow: hidden;
			.t_c_tit{font-size: 16px;text-align: center;font-weight: 600;padding-bottom: 10px;}
			.t_c_con{font-size: 14px;max-height: 280px;min-height:120px;overflow-y: scroll;width: 100%;overflow-x: hidden;}
			.t_c_more{text-align: center;color: #868686;padding: 15px 0 10px;font-size: 14px;
				span{color: #4B73CE;}
			}
			.t_c_btn{display: flex;justify-content: space-between;
				.t_c_btn_01{height: 35px;line-height: 35px;color: #fff;border-radius: 20px;text-align: center;width: 47%;
				font-size: 14px;margin-top: 10px;}
				.bg_red{background-color: #FF7900;}
				.bg_gery{background-color: #CCCCCC;}
			}
		}
	.tuanzhang{background: #fff;margin-top: 10px;padding: 10px;display: flex;font-size: 12px;
		.tz-l{width: 30px;
			input{border: 1px solid #000;}
		}
		.tz-m{flex-grow: 1;color: #FF8D42;padding-top: 2px;}
		.tz-r{padding-top: 3px;}
	}
	.jr {
		padding: 10px;
		font-size: 14px;

		.jr_tit {
			font-weight: 600;
			text-align: center;
			padding-bottom: 10px;
		}

		.jr_img {
			display: flex;
			justify-content: center;

			.img_01 {
				position: relative;
				border-radius: 50%;
				margin: 0 7px;
				width: 44px;
				height: 44px;

				img {
					width: 40px;
					height: 40px;
					border-radius: 50%;
				}

				.zhicheng {
					position: absolute;
					bottom: -5px;
					left: 0;
					background-color: #E93B3D;
					color: #fff;
					font-size: 12px;
					border-radius: 10px;
					width: 40px;
					text-align: center;
				}
			}

			.img_01_border {
				border: 2px solid #E93B3D;
			}

			.img_01_borderx {
				border: 2px dashed #E6E6E6;
				text-align: center;
				line-height: 44px;
				color: #E6E6E6;
			}
		}
	}

	.address-section {
		padding: 30upx 0;
		background: #fff;
		position: relative;

		.order-content {
			display: flex;
			align-items: center;
		}

		.icon-shouhuodizhi {
			flex-shrink: 0;
			display: flex;
			align-items: center;
			justify-content: center;
			width: 90upx;
			color: #888;
			font-size: 44upx;
		}

		.cen {
			display: flex;
			flex-direction: column;
			flex: 1;
			font-size: 28upx;
			color: $font-color-dark;
		}

		.name {
			font-size: 34upx;
			margin-right: 24upx;
		}

		.address {
			margin-top: 16upx;
			margin-right: 20upx;
			color: $font-color-light;
		}

		.icon-you {
			font-size: 32upx;
			color: $font-color-light;
			margin-right: 30upx;
		}

		.a-bg {
			position: absolute;
			left: 0;
			bottom: 0;
			display: block;
			width: 100%;
			height: 5upx;
		}
	}

	.goods-section {
		margin-top: 16upx;
		background: #fff;
		padding-bottom: 1px;
		.msg{display: flex;padding: 10px;font-size: 12px;
			.msg-l{padding-top: 5px;}
			input{background-color: #F8F8F8;font-size: 12px;flex-grow: 1;height: 25px;padding: 5px 10px;}
		}
		.g-header {
			display: flex;
			align-items: center;
			height: 84upx;
			padding: 0 30upx;
			position: relative;
		}

		.logo {
			display: block;
			width: 50upx;
			height: 50upx;
		}

		.name {
			font-size: 30upx;
			color: $font-color-base;
			margin-left: 24upx;
		}

		.g-item {
			display: flex;
			margin: 20upx 30upx;

			image {
				flex-shrink: 0;
				display: block;
				width: 140upx;
				height: 140upx;
				border-radius: 4upx;
			}

			.right {
				flex: 1;
				padding-left: 24upx;
				overflow: hidden;
			}

			.title {
				font-size: 30upx;
				color: $font-color-dark;
			}

			.spec {
				font-size: 26upx;
				color: $font-color-light;
			}

			.price-box {
				display: flex;
				align-items: center;
				font-size: 32upx;
				color: $font-color-dark;
				padding-top: 10upx;

				.price {
					margin-bottom: 4upx;
				}

				.number {
					font-size: 26upx;
					color: $font-color-base;
					margin-left: 20upx;
				}
			}

			.step-box {
				position: relative;
			}
		}
	}

	.yt-list {
		margin-top: 16upx;
		background: #fff;
	}

	.yt-list-cell {
		display: flex;
		align-items: center;
		padding: 10upx 30upx 10upx 40upx;
		line-height: 70upx;
		position: relative;

		&.cell-hover {
			background: #fafafa;
		}

		&.b-b:after {
			left: 30upx;
		}

		.cell-icon {
			height: 32upx;
			width: 32upx;
			font-size: 22upx;
			color: #fff;
			text-align: center;
			line-height: 32upx;
			background: #f85e52;
			border-radius: 4upx;
			margin-right: 12upx;

			&.hb {
				background: #ffaa0e;
			}

			&.lpk {
				background: #3ab54a;
			}

		}

		.cell-more {
			align-self: center;
			font-size: 24upx;
			color: $font-color-light;
			margin-left: 8upx;
			margin-right: -10upx;
		}

		.cell-tit {
			flex: 1;
			font-size: 26upx;
			color: $font-color-light;
			margin-right: 10upx;
		}

		.cell-tip {
			font-size: 26upx;
			color: $font-color-dark;

			&.disabled {
				color: $font-color-light;
			}

			&.active {
				color: $base-color;
			}

			&.red {
				color: $base-color;
			}
		}

		&.desc-cell {
			.cell-tit {
				max-width: 90upx;
			}
		}

		.desc {
			flex: 1;
			font-size: $font-base;
			color: $font-color-dark;
		}
	}

	/* 支付列表 */
	.pay-list {
		padding-left: 40upx;
		margin-top: 16upx;
		background: #fff;

		.pay-item {
			display: flex;
			align-items: center;
			padding-right: 20upx;
			line-height: 1;
			height: 110upx;
			position: relative;
		}

		.icon-weixinzhifu {
			width: 80upx;
			font-size: 40upx;
			color: #6BCC03;
		}

		.icon-alipay {
			width: 80upx;
			font-size: 40upx;
			color: #06B4FD;
		}

		.icon-xuanzhong2 {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 60upx;
			height: 60upx;
			font-size: 40upx;
			color: $base-color;
		}

		.tit {
			font-size: 32upx;
			color: $font-color-dark;
			flex: 1;
		}
	}

	.footer {
		position: fixed;
		left: 0;
		bottom: 0;
		z-index: 995;
		display: flex;
		align-items: center;
		width: 100%;
		height: 90upx;
		justify-content: space-between;
		font-size: 30upx;
		background-color: #fff;
		z-index: 998;
		color: $font-color-base;
		box-shadow: 0 -1px 5px rgba(0, 0, 0, .1);

		.price-content {
			padding-left: 30upx;
		}

		.price-tip {
			color: $base-color;
			margin-left: 8upx;
		}

		.price {
			font-size: 36upx;
			color: $base-color;
		}

		.submit {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 280upx;
			height: 100%;
			color: #fff;
			font-size: 32upx;
			background-color: $base-color;
		}
	}

	/* 优惠券面板 */
	.mask {
		display: flex;
		align-items: flex-end;
		position: fixed;
		left: 0;
		top: var(--window-top);
		bottom: 0;
		width: 100%;
		background: rgba(0, 0, 0, 0);
		z-index: 9995;
		transition: .3s;

		.mask-content {
			width: 100%;
			min-height: 30vh;
			max-height: 70vh;
			padding: 20px 0px 60px;
			background: #f3f3f3;
			transform: translateY(100%);
			transition: .3s;
			overflow-y: scroll;
		}

		.btn {
			position: fixed;
			bottom: 0;
			width: 95%;
			text-align: center;
			border: 1px solid #FA436A;
			background-color: #FA436A;
			color: #FFFFFF;
			border-radius: 20px;
			margin: 10px;
			padding: 5px;
		}


		&.none {
			display: none;
		}

		&.show {
			background: rgba(0, 0, 0, .4);

			.mask-content {
				transform: translateY(0);
			}
		}
	}

	.scroll {
		max-height: 55vh;
	}

	/* 优惠券列表 */
	.coupon-item {
		display: flex;
		flex-direction: column;
		margin: 3upx 24upx;
		background: #fff;

		.con {
			display: flex;
			align-items: center;
			position: relative;
			height: 120upx;
			padding: 0 30upx;


			/* &:after {
				position: absolute;
				left: 0;
				bottom: 10;
				content: '';
				width: 100%;
				height: 0;
				border-bottom: 1px dashed #f3f3f3;
				transform: scaleY(50%);
			} */
		}

		.left {
			display: flex;
			flex-direction: column;
			justify-content: center;
			flex: 1;
			overflow: hidden;
			height: 100upx;
		}

		.title {
			font-size: 32upx;
			color: $font-color-dark;
			margin-bottom: 10upx;
		}

		.time {
			font-size: 24upx;
			color: $font-color-light;
		}

		.right {
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			font-size: 26upx;
			color: $font-color-base;
			height: 100upx;
		}

		.price {
			font-size: 44upx;
			color: $base-color;

			&:before {
				content: '￥';
				font-size: 34upx;
			}
		}

		.tips {
			font-size: 24upx;
			color: $font-color-light;
			line-height: 60upx;
			white-space: nowrap;
			padding-left: 30upx;
		}

		.tips2 {
			font-size: 24upx;
			color: $font-color-light;
			line-height: 50upx;
			;
			height: 50upx;
			;
			text-align: center;
			margin: 5upx 10px 0 0;
			width: 80px;
			border: 1px solid #FA436A;
			background-color: #FA436A;
			color: #FFFFFF;
			border-radius: 25px;
		}

		.circle {
			position: absolute;
			left: -6upx;
			bottom: -10upx;
			z-index: 10;
			width: 20upx;
			height: 20upx;
			background: #f3f3f3;
			border-radius: 100px;

			&.r {
				left: auto;
				right: -6upx;
			}
		}

	}
</style>
