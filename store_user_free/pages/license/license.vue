<template>
	<view class="yyzz">
		<view class="title">网店经营者营业执照信息</view>
		<view class="gen">根据国家工商总局《中华人民共和国电子商务法》要求对网店营业执照信息公示如下：</view>
		<view class="img">
			<img :src="url + license"></img>
		</view>
		<view class="zhu">注：以上营业执照信息来源于卖家自行申报或工商系统数据，具体以工商部门登记为准。经营者需确保信息真实有效，
		平台也将定期核查。如与实际不符，为避免违规，请联系当地工商部门或平台客服更新。</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				shop_id:'',
				license:'',
				url:this.$getimg
			};
		},
		onLoad(options) {
			this.license = options.img
			// this._load()
		},
		methods:{
			// _load() {
			// 	this.$api.http.get('shops/store/store_zizi?shop_id=' + this.shop_id).then(res => {
			// 		this.license = res.data.license
			// 	})
			// },
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
.yyzz{padding: 10px;font-size: 14px;
	.title{font-size: 16px;font-weight: 600;text-align: center;padding: 20px 0px 25px;}
	.gen{padding-bottom: 15px;line-height: 20px;}
	.img{text-align: center;
		img{width: 90%;height: 300px;}
	} 
	.zhu{color: #AAAAAA;font-size: 12px;padding-top: 20px;line-height: 15px;}
}
</style>
