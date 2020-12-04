<template>
	<view class="shop">
		<view class="head">
			<view class='head_l' @click="jump_basic"><img src='@/static/imgs/vip.png'></img></view>
			<view class='head_m' @click="jump_basic">
				<view class='head_m_01'>{{info.shop_name}}</view>
				<view class='head_m_02'>{{info.shop_description}}</view>
			</view>
			<view class='head_r' @click="like" v-if="!is_like">关注</view>
			<view class='head_r_01' @click="del_like" v-if="is_like">已关注</view>
		</view>
		<view class='tab'>
			<view :class="xuan==0?'xz':'bb' " @click="num" id=0>首页</view>
			<view v-for="(item,index) of shop_group" :class="xuan==item.id?'xz':'bb' " @click="num" :id='item.id'>{{item.name}}</view>
		</view>
		
		<view class='pro'>
			<ListF :product="product" :xuan="cate"></ListF>
		</view>

		<view class='back' @click="jump_back">
			<view class='back_01'>
				<!-- <uni-icon type="arrowleft" size="16" color="#fff"></uni-icon> -->
			</view>
			<view class='back_02'>返回首页</view>
		</view>
	</view>
</template>

<script>
	import ListF from "@/components/qy/List-f";
	import uniIcon from "@/components/uni/uni-icon/uni-icon.vue"
	export default {

		data() {
			return {
				list: [],
				xuan: 0,
				shop_id: '',
				info: '',
				product: '',
				shop_group: '',
				cate: '',
				is_like: false,
				all:''
			};
		},
		watch: {
			xuan(a, b) {
				this.cate = a
			}
		},
		computed: {

		},
		components: {
			ListF,
			uniIcon
		},
		onLoad(options) {
			this.shop_id = options.id
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('shops/store?id=' + this.shop_id).then(res => {
					this.info = res.data
				})
				this.$api.http.get('shops/store/all_pro?shop_id=' + this.shop_id).then(res => {
					this.product = res.data
					this.all = res.data
				})
				this.$api.http.get('shops/store/all_group?shop_id=' + this.shop_id).then(res => {
					this.shop_group = res.data
				})
				
			},
			jump_back() {
				uni.switchTab({
					url: '/pages/index/index'
				})
			},
			//收藏店铺
			like() {
				let data = {
					fav_id: this.shop_id,
					price: 0,
					type: 'shop'
				}
				this.$api.http.post('favorite/add_fav', data).then(res => {
					this.$api.msg('收藏成功')
					this.is_like = true
				})
			},
			//取消收藏店铺
			del_like() {
				this.is_like = !this.is_like
				this.$api.http.put('favorite/del_fav', {
					id: this.shop_id
				}).then(res => {
					this.$api.msg('取消收藏')
				})
			},
			jump_basic() {
				uni.navigateTo({
					url: '/pages/basic/basic?id=' + this.shop_id
				});
			},
			num(event) {
				let arr = []
				console.log(event)
				var i = event.currentTarget.id;
				this.xuan = i
				if (this.xuan != 0) {
					for (let k in this.all) {
						let v = this.all[k]
						if (v.tag_ids[0] == this.xuan) {
							arr.push(v)
						}
					}
					this.product = arr
					console.log(arr,i)
				}else{
					this.product = this.all
				}

			}
		},
		onShareAppMessage(res) {
			let my = uni.getStorageSync('my')
			let path = "/pages/shop/shop?id=" + this.shop_id
			if (my && my.data && my.data.sfm) {
				path = path + '&sfm=' + my.data.sfm
			}
			return {
				title: this.info.shop_name,
				path: path
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	};
</script>

<style lang="less">
	.shop {
		font-size: 14px;

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

		.sw {
			display: flex;
			width: 100%;
		}

		.head {
			padding: 20px 10px;
			display: flex;
		}

		.head_l {
			width: 48px;
			height: 48px;


			display: flex;
			justify-content: center;
			align-items: center;
			box-sizing: border-box;
			margin-top: 5px;
		}

		.head_l img {
			width: 38px;
			height: 38px;
			border-radius: 15px;
		}

		.head_m {
			width: 50%;
			padding: 8px 0 0 10px;
			flex-grow: 1;
		}

		.head_m_01 {
			font-weight: bold;
			font-size: 16px;
		}

		.head_m_02 {
			color: #B2B2B2;
			font-size: 11px;
			padding-top: 3px;
			display: -webkit-box !important;
			text-overflow: ellipsis;
			overflow: hidden;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			word-break: break-all;
		}

		.head_r {
			margin: 5px;
			padding: 0 15px;
			background-color: #FF4444;
			color: #fff;
			text-align: center;
			height: 30px;
			line-height: 30px;
			border-radius: 20px;
			font-size: 12px;
			width: 70px;
			flex-shrink: 0;
		}

		.head_r_01 {
			margin: 5px;
			padding: 0 15px;
			background-color: #F1F1F1;
			color: black;
			text-align: center;
			height: 30px;
			line-height: 30px;
			border-radius: 20px;
			font-size: 12px;
			width: 70px;
			flex-shrink: 0;
		}

		.tab {
			padding: 10px 5%;
			display: flex;
			justify-content: space-around;
		}

		.bb {
			padding-bottom: 5px;
		}

		.xz {
			border-bottom: 2px solid red;
			padding-bottom: 5px;
		}

		.pro {
			padding-top: 15px;
		}

		.coupon {
			padding: 10px;
		}

		.coupon_01 {
			background-color: #f1f1f1;
			display: flex;
			width: 140px;
			height: 60px;
			margin: 10px 5px;
			flex: 0 0 140px;
		}

		.coupon_01_l {
			width: 40%;
			text-align: center;
			line-height: 60px;
			color: #FE475C;
		}

		.coupon_01_l span {
			font-size: 22px;
		}

		.coupon_01_r {
			width: 60%;
			text-align: center;
			padding-top: 10px;
			line-height: 20px;
		}

		.coupon_01_r_02 {
			color: #9A9A9A;
		}

		.sw {
			display: flex;
			width: 100%;
		}

		.back a {
			position: fixed;
			bottom: 80px;
			left: 0;
			background-color: #FF5266;
			color: #FFDFE9;
			display: flex;
			padding: 5px 10px 5px 5px;
			border-top-right-radius: 18px;
			border-bottom-right-radius: 18px;
		}

		.back_01 {
			margin-top: -1px;
		}

		a {
			text-decoration: none
		}
	}
</style>
