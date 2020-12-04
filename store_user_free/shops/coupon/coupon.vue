<template>
	<view class="coupon">
		<view class='po'>
			<None v-if="list_empty"></None>
			<block  v-else>
				<Coupon leixing="a" types='1' :list="list"  :btn_name="btn_name" :class_name="class_name"
				></Coupon>
				
			</block>

		</view>
	</view>
</template>

<script>
	import Coupon from "@/components/qy/coupontwo.vue"
	import Check from '@/common/check.js'
	import None from "@/components/qy/none.vue"
	import {common} from "../../common/mixin.js"
	import {
		Api_url
	} from '@/common/config.js'
	export default {
		data() {
			return {
				btn_name: 'cou_t_rr',
				c_index: 0,
				list: [],
				class_name: 'cou_t_l_01',
				list_empty: false,
			};
		},
		mixins:[common],
		components: {
			None,
			Coupon
		},
		onLoad(options) {
			
		},
		methods: {
			
			_load() {
				//获取优惠券
				this.$api.http.get('coupon/get_coupon').then((res) => {
					console.log(res)
					if (res.data == '') {
						this.list_empty = true
					} else {
						this.list = res.data
					}
					uni.stopPullDownRefresh();
				})
			},
			//领取优惠券
			get_coupon(id) {
				this.$api.http.get('coupon/add_coupon', {
					id: id
				}).then(res => {
					this.$api.msg('领取成功')
					this._load();
				})
			},

		},
		onPullDownRefresh() {
			this._load();
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="scss">
	.coupon {
		background-color: #F8F8F8;padding-top: 1px;
		min-height: 100vh;
	}
</style>
