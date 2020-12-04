<template>
	<view class="basic">
		<view class="name">
			<img src="@/static/imgs/vip.png"></img>
			{{info.shop_name}}
		</view>
		<view class="jcxx">
			<view class="jx">基础信息</view>
			<!-- <view class="jx-01">
				<view class="jx-01-l">掌柜名</view>
				<view class="jx-01-r">{{}}</view>
			</view> -->
			<view class="jx-01">
				<view class="jx-01-l">服务电话</view>
				<view class="jx-01-r">12312312</view>
			</view>
			<view class="jx-01">
				<view class="jx-01-l">所在地</view>
				<view class="jx-01-r" >{{info.shop_address}}</view>
			</view>
			<view class="jx-01" @click="jump_qyzz">
				<view class="jx-01-l">企业资质</view>
				<view class="jx-01-r">
					<img src="@/static/imgs/jingwuicon.png"></img> >
				</view>
			</view>
			<view class="jx-01">
				<view class="jx-01-l">开店时间</view>
				<view class="jx-01-r">2019-07-11 12:09</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				shop_id: '',
				info: ""
			};
		},
		onLoad(options) {
			this.shop_id = options.id
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('shops/store?id=' + this.shop_id).then(res => {
					this.info = res.data
					console.log(this.info)
				})
			},
			jump_qyzz() {
				uni.navigateTo({
					// url: '/shops/yanzz/yanzz?id='+this.shop_id,
					url: '/pages/license/license?img='+this.info.shop_pic_url,
				});
			}
		},
		onPullDownRefresh() {
			this._load()
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		},
	}
</script>

<style lang="scss">
	.basic {
		background-color: #F8F8F8;
		min-height: 100vh;
		font-size: 14px;

		.name {
			display: flex;
			padding: 20px 10px;
			font-size: 16px;
			font-weight: 600;
			line-height: 20px;
			background-color: #fff;
			margin-bottom: 10px;

			img {
				width: 40px;
				height: 20px;
				margin-right: 10px;
				border-radius: 10px;
			}
		}

		.jcxx {
			background-color: #fff;
			margin: 10px;
			border-radius: 3px;

			.jx {
				border-bottom: 1px solid #F5F5F5;
				padding: 10px;
				color: #727272;
			}

			.jx-01 {
				display: flex;
				justify-content: space-between;
				border-bottom: 1px dashed #F5F5F5;
				padding: 10px;
				color: #727272;
				.jx-01-l{
					width: 70px;
					margin-right: 50px;
				}
				.jx-01-r img {
					width: 18px;
					height: 18px;
					margin-right: 5px;
				}
			}
		}
	}
</style>
