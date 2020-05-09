<template>
	<div class="login">
		<div class="tit">系统登录</div>
		<div class="zhong">
			<div class="shu">
				<div class="shu-l"><i class="el-icon-user-solid" style="color:#889AA4;padding:5px 0 0 0"></i></div>
				<div class="shu-m">
					<input type="text" v-model="user">
				</div>
			</div>
		</div>
		<div class="zhong">
			<div class="shu">
				<div class="shu-l">
					<img src="../../img/Lock.png" />
				</div>
				<div class="shu-m">
					<input :type="type" v-model="password" v-on:keyup.enter="sub(password)">
				</div>
				<div class="shu-r" @click="change(type)">
					<i v-if="type=='text'" class="el-icon-view" style="color:#889AA4;padding:5px 0 0 0"></i>
					<img v-if="type=='password'" src="../../img/eye.png" />
				</div>
			</div>
		</div>
		<div class="zhong">
			<div class="btn"><el-button type="primary" style="width:100%" size="medium" @click="sub(password)">登 录</el-button></div>
		</div>
		<div class="zhong">
			<div class="btn">账号：admin&emsp;密码：随便填</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				user: "",
				password: "",
				show: true,
				type:'password'
			}
		},
		mounted() {
			
		},
		methods: {	
			sub(psw) {
				console.log(psw)
				var that = this;
				const username = this.user
				this.http.post_show('index/admin/login', {
						username: username,
						psw: psw
					})
					.then((res) => {
						if (res.status == 200) {
							localStorage.setItem("token", res.data.token);
							localStorage.setItem("oauth", res.data.oauth);
							localStorage.setItem("admin_name", that.user);
							that.$message({
								showClose: true,
								message: '登陆成功',
								type: 'success'
							});
							this.$router.push({
								path: '/'
							});
						}
						if (res.status == 400) {
							this.$message.error(res.msg);
							return
						}
					});
			},
			login() {
				const token = localStorage.getItem("token");
				if (token) {
					this.$router.push({
						path: '/'
					});
				} else {
					return
					this.$router.push({
						path: '/login'
					});
				}
			},
			change(e){
				if(e=='password'){
					this.type="text"
				}
				if(e=='text'){
					this.type="password"
				}
			}
		},
	}
</script>

<style lang="less">
	.login{
		background-color: #2D3A4B;width: 100%;height: 100vh;color: #fff;text-align: center;
		.tit{font-size: 20px;font-weight: 600;padding: 200px 0 40px;text-align: center;}
		.zhong{display: flex;justify-content: center;width: 100%;margin-bottom: 20px;
			.shu{display: flex;border: 1px solid #3D4856;background-color: #283443;justify-content: space-between;width: 350px;padding:8px 10px; box-sizing: border-box;
				.shu-l img{width: 17px;height: 17px;margin-top: 5px;}
				.shu-m{flex-grow: 1;padding: 0 10px;
					input{background-color: #283443;color: #fff;width: 100%;border: none;height: 25px;line-height: 25px;}
				} 
				.shu-r img{width: 17px;height: 17px;margin-top: 5px;}
			}
			.btn{width: 350px;text-align: left;font-size: 14px;}
		}
		
	} 
</style>
