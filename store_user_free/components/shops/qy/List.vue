<template>
	<view class="list" @click="detail(info.goods_id)">
		<view class="list-l">
			<img v-if="info.goods_imgs" :src="getimg+info.goods_imgs.thumb_img"></img>
		</view>
		<view class="list-r">
			<view class="list-r-01">{{info.goods_name}}</view>
			<view class="list-r-02">
				<tui-rate :current="info.shops.xinxin== 0?current:info.shops.xinxin" active="#FB586A" :disabled="true" size="15"></tui-rate>
				<span v-if="info.shops.xinxin">{{info.shops.xinxin}}分</span>
				<span v-else>暂无</span>
			</view>
			<view class="list-r-03">
				<view class="list-r-03-l">
					{{info.shops.shop_name}}
					<view class="list-r-04">
						<view class="btn" v-if="!info.shops.shop_state">已打烊</view>
					</view>
				</view>
				<view class="list-r-03-r" v-if="info.distance">{{info.distance | filter_distance(info.distance)}}</view>
			</view>
			<view class="list-r-06">¥{{info.price}}</view>
			<!-- <view class="list-r-05">{{info.description}}</view> -->
		</view>
	</view>
</template>

<script>
	import tuiRate from "@/components/rate/rate"
	export default {
		name: 'List',
		props: ['info', 'ucid'],
		data() {
			return {
				current: 4,
				getimg: this.$getimg,
			}
		},

		components: {
			tuiRate
		},
		mounted() {},
		methods: {
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
	.list {
		font-size: 14px;
		background-color: #fff;
		margin-bottom: 10px;
		display: flex;
		padding: 10px;
		box-sizing: border-box;
		width: 100%;

		.list-l {
			img {
				width: 105px;
				height: 105px;
				border-radius: 5px;
			}
		}

		.list-r {
			padding-left: 10px;
			width: 70%;
			flex-grow: 1;

			.list-r-01 {
				font-size: 16px;
				font-weight: 600;
				line-height: 20px;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
				height: 20px;
			}

			.list-r-02 {
				font-size: 12px;
				color: #FB586A;
				padding: 4px 0 2px;

				span {
					padding-left: 5px;
				}
			}

			.list-r-03 {
				font-size: 12px;
				color: #818181;
				display: flex;
				justify-content: space-between;
				height: 20px;
				line-height: 20px;
				.list-r-03-l{display: flex;}
			}

			.list-r-04 {
				display: flex;
				padding:  0;margin-left: 3px;

				.btn {
					color: #FB586A;
					border: 1px solid #FB586A;
					border-radius: 3px;
					font-size: 12px;
					padding: 0px 5px;
				}
			}
			.list-r-06{color: #FB586A;}
			.list-r-05 {
				line-height: 20px;
				overflow: hidden;
				white-space: nowrap;
				text-overflow: ellipsis;
				height: 20px;
				color: #818181;
			}
		}
	}
</style>
