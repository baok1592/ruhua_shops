<template>
	<view class="pnone">
		<view class="tell bottom-border" >
			<view class="cu-form-group ">
				<view class="title">手机号码</view>
				<input v-model="phone_number" placeholder="请输入手机号码" name="input"></input>
				<view class="cu-capsule radius">
					<view class='cu-tag bg-blue '>
						+86
					</view>
					<view class="cu-tag line-blue">
						中国大陆
					</view>
				</view>
			</view>
			<view class="cu-form-group" >
				<view class="title">验证码</view>
				<input placeholder="请输入验证码" v-model="code" name="input"></input>
				<button class='cu-btn bg-green shadow' @click="get_code"  v-if="is_code">验证码</button>
				<view style="color: #6D6D72;font-size: 15px; padding-top: 15px;margin-right: 20px;" v-if="!is_code">{{btn_name}}</view>
			</view>
			
		</view>
		<view class="btn flex flex-direction">
			<button class='cu-btn bg-green shadow lg' @click="login">确定</button>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				phone_number: '',
				code: '',
				is_code: true,
				btn_name: '',
				cutdown: 60,
			};
		},
		methods:{
			settime() {
				if (this.cutdown == 0) {
					this.btn_name = '获取验证码'
					this.is_code = true
					this.cutdown = 60
					return
				} else {
					this.cutdown--
					this.btn_name = '(' + this.cutdown + ')'
				}
				setTimeout(()=>{
					this.settime()
				},1000)
			},
			get_code() {
			
				if (!this.phone_number || this.phone_number.length != 11) {
					this.$api.msg('请输入正确的手机号码')
					return
				}
				
				this.$api.http.post('shops/user/get_yzm', {
					mobile: this.phone_number
				}).then(res => {
					console.log(res)
					this.is_code = false
					this.yz_code = res.data
					this.settime()
				})
			},
			login() {
				
				if (!this.phone_number || !this.code) {
					this.$api.msg('未填写手机号或验证码')
					return
				}
				if (this.phone_number.length != 11) {
					this.$api.msg('请输入正确的手机号')
					return
				}
				this.$api.http.post('shops/user/bind_phone', {
					mobile: this.phone_number,
					code: this.code
				}).then(res => {
					if (res.status == 400) {
						this.$api.msg(res.msg)
						return;
					}
					this.$api.http.get('/user/info').then(res => {
						let arr={}
						arr['data'] = res.data
						arr['save_time']=Date.parse(new Date()) / 1000
						uni.setStorageSync('my', arr);//放入缓存 		
					}) 
					setTimeout(() => {
						uni.switchTab({
							url: '/pages/user/user'
						});
					}, 2000);
				})
			}
		}
	}
</script>

<style lang="scss">
.pnone{
	.tell{padding: 20px 0 0 0;}
	.bottom-border{border-bottom: 1px solid #EEEEEE;}
	.btn{position: fixed;left: 0;bottom: 0;background-color: #fff;padding: 10px;width: 100%;box-sizing: border-box;}
}
</style>
