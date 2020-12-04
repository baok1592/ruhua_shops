<template>
	<div class="login">

		<div class="mima" v-if="logintype != 'saoma'">
			<div class="mima-01" v-if="logintype=='mima'">
				<div class="mima-01-01">
					密码登录<span @click="yzm_login">验证码登录</span>
				</div>
				<div class="mima-01-02">
					<div class="mima-01-01-1">
						扫码登录更安全
						<div class="jian">▶</div>
					</div>
				</div>
				<div class="mima-01-03" @click="Logintype('saoma')">
					<img src="../../img/bg.png" />
				</div>
			</div>
			<div class="mima-01" v-if="logintype=='yzm'">
				<div class="mima-01-01">
					<span style="margin-right: 30px;padding-left: 0;" @click="mima_login">密码登录</span>验证码登录
				</div>
				<div class="mima-01-02">
					<div class="mima-01-01-1">
						扫码登录更安全
						<div class="jian">▶</div>
					</div>
				</div>
				<div class="mima-01-03" @click="Logintype('saoma')">
					<img src="../../img/bg.png" />
				</div>
			</div>
			<!-- 密码登录 -->
			<template v-if="logintype == 'mima'">
				<div class="mima-02">
					<input v-model="user" placeholder="请输入" />
				</div>
				<div class="mima-02">
					<div class="mima-02-1">
						<input placeholder="请输入" :type="type" v-model="password" v-on:keyup.enter="sub(password)" />
					</div>
					<div class="mima-02-2" @click="change(type)">
						<i v-if="type=='text'" class="el-icon-view" style="color:#889AA4;padding:5px 0 0 0"></i>
						<img v-if="type=='password'" src="../../img/eye.png" />
					</div>
				</div>
			</template>
			<!-- 验证码登录 -->
			<template v-if="logintype == 'yzm'">
				<div class="mima-02">
					<input v-model="mobile" placeholder="请输入手机号" />
				</div>
				<div class="mima-02">
					<div class="mima-02-1">
						<input placeholder="请输入验证码" :type="type" v-model="yzm_code" v-on:keyup.enter="sub(password)" />

					</div>
					<div class="mima-02-2">
						<el-button type="primary" size="medium" :disabled="btn_state" @click="get_yzm_code">{{btn_name}}</el-button>
						<!-- <div class="get_code" @click="get_yzm_code">{{btn_name}}</div> -->
					</div>

				</div>
			</template>

			<div class="mima-03">
				<!-- <el-checkbox v-model="checked3">3天内自动登录</el-checkbox> -->
			</div>
			<div class="mima-04">
				<el-button type="primary" class="denglu" @click="sub(password)">登 陆</el-button>
			</div>
			<div class="mima-05">
				<div class="mima-05-l">
					<!-- <el-checkbox v-model="checked"></el-checkbox> -->
					 <!-- 我已阅读并同意<span>《用户使用协议》</span> -->
					<!-- 账号：13012345678&emsp;密码：123456 -->
				</div>
				<!-- <div class="mima-05-r">
					<span>忘记密码</span>&nbsp;&nbsp;| &nbsp;&nbsp;<span>免费注册</span>
				</div> -->
			</div>
		</div>
		<div class="mima" v-if="logintype=='saoma'">
			<div class="mima-01">
				<div class="mima-01-01">
					扫码登录
				</div>
				<div class="mima-01-02">
					<div class="mima-01-01-1">
						密码登录
						<div class="jian">▶</div>
					</div>
				</div>
				<div class="mima-01-03" @click="Logintype('mima')">
					<img src="../../img/9.png" />
				</div>
			</div>
			<div class="mima-06">
				<img :src="getimg + code_img" />
			</div>

			<div class="mima-05">
				<div class="mima-05-l">

				</div>
				<div class="mima-05-r">
					<span>免费注册</span>
				</div>
			</div>
		</div>

		<!-- <div class="zhong">
      <div class="btn">账号：admin&emsp;密码：123456</div>
    </div>-->
	</div>
</template>

<script>
	import commonModel from "@/api/common";
	export default {
		data() {
			return {
				btn_name: '获取验证码',
				yzm_code: '',
				mobile: '',
				code_img: '',
				getimg: this.$getimg,
				user: "",
				password: "",
				show: true,
				type: "password",
				logintype: 'mima',
				checked3: '',
				checked: '',
				inter: '',
				code: '',
				btn_state: false,
				state:''
			};
		},
		mounted() {},
		methods: {
			//获取短信验证码
			get_yzm_code() {
				const that = this
				let time = 60
				if (!this.mobile) {
					that.$message({
						showClose: true,
						message: "未填写手机号",
						type: "warning"
					});

					return
				}
				this.http.post('boss/get_yzm', {
					mobile: this.mobile
				}).then(res => {
					let state = setInterval(function() {
						time -= 1
						that.btn_name = time + '秒后重新获取'
						that.btn_state = true
						if (time == 0) {
							clearInterval(state)
							that.btn_name = "重新获取"
							that.btn_state = false
						}
					}, 1000)
				})

			},
			get_shop_info(){
				// this.http.get('shop_store_info').then(res=>{
				// 	localStorage.setItem('shop_info',JSON.stringify(res.data))
				// })
			},
			mima_login() {
				this.logintype = 'mima'
				clearInterval(this.state)
			},
			yzm_login() {
				this.logintype = 'yzm'
				clearInterval(this.state)
			},
			Logintype(type) {
				clearInterval(this.state)
				this.logintype = type
				if (type == 'saoma') {
					this.get_code()
				}
			},
			get_code() {
				const that = this
				this.http.get('shopscms/getcodeimg').then(res => {
					this.code_img = res.data.url
					this.code = res.data.data
				})
				this.state = setInterval(function() {
					that.http.get('shopscms/check_login?code=' + that.code).then(res => {
						if (res.status == 200) {
							clearInterval(this.state)
							that.$message({
								showClose: true,
								message: "登陆成功",
								type: "success"
							});
							that.get_shop_info()
							localStorage.setItem("shops_pc_token", res.data.token);
							that.$router.push({
								path: "/"
							});
						}
					})
				}, 1000)
			},
			sub(psw) {

				var that = this;
				const username = this.user;
				if (this.logintype == 'mima') {
					this.http.post('/shopscms/login', {
						username: username,
						psw: psw,
						// code:'sadasf'
					}).then(res => {
						if (res.status == 200) {
							console.log('登录成功')
							localStorage.setItem("shops_pc_token", res.data.token);
							localStorage.setItem("admin_name", that.user);
							that.get_shop_info()
							that.$message({
								showClose: true,
								message: "登陆成功",
								type: "success"
							});
							this.$router.push({
								path: "/"
							});
						} else {
							this.$message.error(res.msg);
							return;
						}
					})
				}
				if(this.logintype =='yzm'){
					if(!this.yzm_code){
						that.$message({
							showClose: true,
							message: "未输入验证码",
							type: "warning"
						});
						return
					}
					this.http.post('boss/yzm_login',{
						mobile:this.mobile,
						code:this.yzm_code
					}).then(res=>{
						if (res.status == 200) {
							that.get_shop_info()
							localStorage.setItem("shops_pc_token", res.data.token);
							that.$message({
								showClose: true,
								message: "登陆成功",
								type: "success"
							});
							this.$router.push({
								path: "/"
							});
						}
					})
				}

			},
			login() {
				const token = localStorage.getItem("shops_pc_token");
				if (token) {
					this.$router.push({
						path: "/"
					});
				} else {
					return;
					this.$router.push({
						path: "/login"
					});
				}
			},
			change(e) {
				if (e == "password") {
					this.type = "text";
				}
				if (e == "text") {
					this.type = "password";
				}
			}
		}
	};
</script>

<style lang="less">
	.login {
		background-color: #F2F3F5;
		width: 100%;
		height: 100vh;
		padding-top: 150px;
		display: flex;
		justify-content: center;
		width: 100%;

		.mima {
			width: 550px;
			height: 520px;
			background: #fff;

			.mima-01 {
				display: flex;

				.mima-01-01 {
					font-size: 26px;
					padding: 52px 70px 55px 55px;
					width: 430px;
					box-sizing: border-box;
					text-align: left;

					span {
						color: #A8A8AA;
						padding-left: 24px;
						font-size: 18px;
					}
				}

				.mima-01-02 {
					padding-top: 25px;
					width: 120px;
					display: flex;
					justify-content: flex-end;

					.mima-01-01-1 {
						background-color: #155BD4;
						color: #fff;
						height: 30px;
						line-height: 30px;
						padding: 0 8px;
						border-radius: 3px;
						position: relative;
						font-size: 14px;

						.jian {
							color: #155BD4;
							position: absolute;
							right: -8px;
							top: 0px;
						}
					}
				}

				.mima-01-03 {
					width: 80px;

					img {
						width: 80px;
						height: 80px;
					}
				}
			}

			.mima-02 {
				border-bottom: 1px solid #E4E6E7;
				margin: 0 55px 30px;
				display: flex;

				input {
					border: none;
					width: 100%;
					height: 25px;
					line-height: 45px;
					font-size: 18px;
					padding: 20px;
				}

				.mima-02-1 {
					flex-grow: 1;

					input {
						border: none;
						width: 90%;
						height: 45px;
						line-height: 45px;
						font-size: 16px;
						padding: 10px;
					}
				}

				.mima-02-2 {
					margin: 12px 10px 0;

					.get_code {
						padding: 10px;
						border: 1px solid #0066CC;
						border-radius: 15px;
					}

					img {
						width: 20px;
						height: 20px;

					}
				}
			}

			.mima-03 {
				padding: 35px 55px 17px;
				text-align: left;
			}

			.mima-04 {
				padding: 0px 55px 17px;
				display: flex;
				width: 100%;
				box-sizing: border-box;

				.denglu {
					width: 100%;
					height: 48px;
					line-height: 48px;
					padding: 0;
					font-size: 18px;
					background-color: #155BD4;
				}
			}

			.mima-05 {
				padding: 0 55px;
				display: flex;
				justify-content: space-between;
				font-size: 14px;

				span {
					color: #155BD4;
				}

				.mima-05-r {
					color: #E5E5E5;
				}
			}

			.mima-06 {
				padding: 40px 55px 86px;

				img {
					width: 146px;
					height: 146px;
					padding: 7px;
					border: 1px solid #E5E5E5;
				}
			}
		}

	}
</style>
