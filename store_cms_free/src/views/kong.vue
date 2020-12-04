<template>
	<div class="root">
		<el-container>
			<el-aside width="120px">
				<div class="nav">
					<el-menu default-active="1" class="el-menu-vertical-demo" background-color="#545c64" text-color="#fff" @open="handleOpen"
					 @close="handleClose">
						<el-menu-item index="1" @click="to_index">
							<i class="el-icon-menu"></i>
							<span slot="title">首页</span>
						</el-menu-item>
						<el-menu-item index="2" @click="to_shops">
							<i class="el-icon-menu"></i>
							<span slot="title">商家</span>
						</el-menu-item>
						<el-menu-item index="3" @click="to_user">
							<i class="el-icon-document"></i>
							<span slot="title">用户</span>
						</el-menu-item>
						<el-menu-item index="4">
							<i class="el-icon-setting"></i>
							<span slot="title">订单</span>
						</el-menu-item>
						<el-menu-item index="5">
							<i class="el-icon-setting"></i>
							<span slot="title">财务</span>
						</el-menu-item>
						<el-menu-item index="6">
							<i class="el-icon-setting"></i>
							<span slot="title">应用</span>
						</el-menu-item>
						<el-menu-item index="7">
							<i class="el-icon-setting"></i>
							<span slot="title">设置</span>
						</el-menu-item>
					</el-menu>
				</div>

			</el-aside>
			<el-main style="padding: 0;">
				<el-row style="height: 100%;">
					<el-col :span="2">
						<el-menu default-active="1" class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose">
							<template v-for="(item,index) of list">
								<el-menu-item :index="item.value" @click="to_content(item)">
									<i class="el-icon-menu"></i>
									<span slot="title">{{item.name}}</span>
								</el-menu-item>
							</template>
						</el-menu>
					</el-col>
					<el-col :span="22">
						{{content}}
					</el-col>
				</el-row>
			</el-main>
		</el-container>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				list: [],
				content:''
			}
		},
		methods: {
			to_content(item){
				this.content = item.name
			},
			to_index() {
				this.list = []
			},
			to_shops() {
				this.list = [{
						name: '页面一',
						value: 1
					},
					{
						name: '页面二',
						value: 2
					},
				]
			},
			to_user() {
				this.list = [{
						name: '页面一',
						value: 1
					},
					{
						name: '页面二',
						value: 2
					},
					{
						name: '页面三',
						value: 3
					},
				]
			},
			handleOpen(key, keyPath) {
				console.log(key, keyPath);
			},
			handleClose(key, keyPath) {
				console.log(key, keyPath);
			}
		}
	}
</script>

<style lang="less">
	.nav_son {
		width: 120px;
	}
</style>
