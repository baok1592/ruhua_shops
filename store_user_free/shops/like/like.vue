<template>
	
		<view class="like">
			<!-- <uni-segmented-control :current="current" :values="items" @clickItem="onClickItem" style-type="text" active-color="#4cd964"></uni-segmented-control> -->
			<view class="tab">
				<view :class="xuan==0?'tab-02':'tab-01'" @click="num(0)">商品收藏</view><span>|</span>
				<view :class="xuan==1?'tab-02':'tab-01'" @click="num(1)">店铺收藏</view><span>|</span>
				<view :class="xuan==2?'tab-02':'tab-01'" @click="num(2)">我的足迹</view>
			</view>
			<view class="content" v-if="xuan==0">
				<view v-show="current === 0">
					<view>
						<None v-if="list_empty"></None>
						<view v-for="(item,index) of likeList" :key="index" v-else>
							<view class="des">
								<view class="des_1" @click="jump_toporduct(item.fav_id)">
									<img :src="getimg + item.img" />
								</view>
								<view class="des_2">
									<view class="msg">
										<view class="name">
											<!-- {{item.product.goods_name}} -->
											{{item.product.goods_name}}
										</view>
										<view class="int">{{item.int}}</view>
										<view class="price">¥{{item.product.price}}</view>
									</view>
									<view class="btn1">
										<view class="btn" @click="del(item.fav_id)">取消收藏</view>
										<view class="btn" @click="jump_toporduct(item.fav_id)">去购买</view>
									</view>
								</view>
							</view>
							<view class="kong"></view>
						</view>
					</view>
				</view>
				<view v-show="current === 1">
					<None v-if="list_empty"></None>
				</view>
			</view>
			<view class="shop" v-if="xuan==1">
				<view v-show="current === 0" class="shop-01" v-for="item of like_shop">
					<view class="shop-l">
						<img src="@/static/imgs/9.jpg"></img>
					</view>
					<view class="shop-m">{{item.shop_name}}</view>
					<view class="shop-r" @click="jump_shop(item.fav_id)">进入店铺</view>
					<!-- <view class="shop-x" @click="del_fav_shop(item.fav_id)">删除收藏</view> -->
					<text class="del-btn yticon icon-iconfontshanchu1" style="padding: 8px;margin-top: 5px;margin-left: 10px;" @click="del(item.fav_id)"></text>
				</view>
				<view v-show="current === 1">
					<None v-if="list_empty"></None>
				</view><strong></strong>
			</view>
			<view class="content" v-if="xuan==2">
				<view v-show="current === 0">
					<view>
						<None v-if="list_empty"></None>
						<view  v-else>
							<view class="time">3月10日</view>
							<block v-for="(item,index) of likeList" :key="index">
							<view class="des" >
								<view class="des_1" @click="jump_toporduct(item.fav_id)">
									<img :src="getimg + item.imgs" />
								</view>
								<view class="des_2">
									<view class="msg">
										<view class="name">
											<!-- {{item.product.goods_name}} -->
											12
										</view>
										<view class="int">{{item.int}}2</view>
										<view class="price">¥{{item.price}}3</view>
									</view>
									<view class="btn1">
										<view class="btn" @click="del(item.fav_id)">取消收藏</view>
										<view class="btn" @click="jump_toporduct(item.fav_id)">去购买</view>
									</view>
								</view>
							</view>
							<view class="kong"></view>
							</block>
						</view>
					</view>
				</view>
				<view v-show="current === 1">
					<None v-if="list_empty"></None>
				</view>
			</view>
		</view>
	</view>
</template>

<script>
	import uniSegmentedControl from "@/components/uni/uni-segmented-control/uni-segmented-control.vue"
	import None from "@/components/qy/none.vue"
	export default {
		data() {
			return {
				xuan:0,
				getimg:this.$getimg,
				likeList: [],
				id: '',
				list_empty: false,
				items: ['商品', '商店'],
				current: 0,
				like_shop:[]
			}
		},
		components: {
			None,
			uniSegmentedControl
		},
		onLoad(option) {
			this._load()
			this.id = option.id
		},
		onShow(option) {
			this._load()
		},
		methods: {
			_load() {
				this.$api.http.get('favorite/get_all_fav').then(res=>{
					// this.likeList = res.data.goods
					if(!res.data.goods){
						this.list_empty=true
					}else{
						this.likeList = res.data.goods
					}
					if(res.data && res.data.shop){
						this.like_shop = res.data.shop
					}else{
						this.like_shop = []
						this.list_empty=true
						this.current = 1
					}
					
					console.log(this.likeList)
				})
			},
			jump_shop(id){
				uni.navigateTo({
					url: '/pages/shop/shop?id=' + id
				});
			},
			del(id) {
				
				const that = this
				uni.showModal({
					title: '提示',
					content: '确定取消？',
					success: function(res) {
						if (res.confirm) {
							that.$api.http.put('/favorite/del_fav', {
								id:id
							}).then(res => {
								that.$api.msg('取消成功')
								that._load()
								
							})
							console.log('用户点击确定');
						} else if (res.cancel) {
							console.log('用户点击取消');
						}
					}
				});


			},
			jump_toporduct(id) {
				uni.navigateTo({
					url: '/pages/productDetail/productDetail?id=' + id,
				})
			},
			onClickItem(index) {
				if (this.current !== index) {
					this.current = index;
				}
			},
			num(e){
				console.log(e)
				this.xuan=e
			}
		},
		onPullDownRefresh() {
			console.log('refresh');
			this._load();
			setTimeout(function() {
				uni.stopPullDownRefresh();
			}, 2000);
		}
	}
</script>

<style lang="less">
.like{background-color: #F0F0F0;min-height: 100vh; font-size: 14px;
	.tab{display: flex;border-bottom: 1px solid #F0F0F0;border-top: 1px solid #F0F0F0;background-color: #fff;
	width: 100%;color: #9E9E9E;
		.tab-01{width: 33%;text-align: center;border-bottom: 0px solid #fff;height: 40px;line-height: 40px;}
		span{color: #D4D4D4;display:block;padding-top: 10px;}
		.tab-02{color: #FF2B5E;border-bottom: 1px solid #ff2b5e;width: 33%;text-align: center;height: 39px;line-height: 40px;}
	}
	.shop{background-color: #F0F0F0;min-height: 100vh;
		.shop-01{display: flex;padding: 10px;
			.shop-l{
				img{width: 40px; height: 40px;}
			}
			.shop-m{padding: 0 10px;height: 40px;line-height: 40px;font-weight: 600;font-size: 15px;flex-grow: 1;}
			.shop-r{background-color: #FF0E48;color: #FFECF1;margin-top: 8px;font-size: 12px;height: 26px;line-height: 26px;
			padding: 0 18px;border-radius: 3px;}
			.shop-x{background-color: #FFFFFF;margin-top: 8px;font-size: 12px;height: 26px;line-height: 26px;
			padding: 0 18px;border-radius: 3px;}
		}
	}
	.time{background-color: #fff;padding: 10px;color: #9C9C9C;}
	.des {
		display: flex;
		padding-top: 10px;
		background-color: #fff;
		padding-bottom: 5px;
		justify-content: space-between
	}

	.des_1 {
		padding: 10px 5px
	}

	.des_1 img {
		width: 30vw;
		height: 30vw;
	}

	.des_2 {
		display: flex;
		flex-direction: column;
		padding-top: 10px;
		width: 63%
	}

	.box {
		display: flex;
		justify-content: space-around;
		background-color: #F8F8FF;
		height: 200px;
		width: 100%
	}

	.msg {
		height: 80px
	}

	.productImg img {
		width: 120px;
		height: 120px;
		padding-left: 10px;
		padding-right: 10px;
	}

	.btn1 {
		height: 30px;
		display: flex;
		justify-content: flex-end;
		padding-right: 10px;
	}

	.btn {
		display: flex;
		font-size: 14px;
		border: 1px solid #C0C0C0;
		height: 25px;
		width: 70px;
		border-radius: 15px;
		justify-content: center;
		align-items: center;
		margin: 5px
	}

	.name {
		font-size: 15px;
	}

	.int {
		font-size: 15px;
		color: #A8A8A8
	}

	.price {
		font-size: 15px;
		color: #FF6600
	}

	.kong {
		height: 20upx
	}
}
</style>
