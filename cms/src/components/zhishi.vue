<template>
	<div :class="type+'bg'" >
		<div class="tit">
			<img src="../img/zhi.png" />操作提示
		</div>
		<div class="list" v-for="(item,index) of list" :key='index'>
			▶<span  :class="type+'text'">{{item}}</span>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				
			};
		},
		props: {
			type: String, 
			list: Object, 
		},
		mounted() {
			
		},
		methods: { 
			
		}
	};
</script>

<style lang="less">
.successbg{background-color: #EDFBF8;margin: 10px;padding: 10px;color: #148EFF;text-align: left;width: 60%;max-width: 1000px;}
.errorbg{background-color: #F3F4F4;margin: 10px;padding: 10px;color: #148EFF;text-align: left;width: 60%;max-width: 1000px;}
.defaultbg{background-color: #F3F4F4;margin: 10px;padding: 10px;color: #148EFF;text-align: left;width: 60%;max-width: 1000px;}
.tit{font-size: 14px;padding-bottom: 5px;
		img{width: 20px;height: 20px;margin-right: 5px;}
	}
	.list{height: 23px;line-height: 23px;font-size: 12px;
		.successtext{color: #41BFAE;padding-left: 10px;}
		.errortext{color:#FE0D0D;padding-left: 10px;}
		.defaulttext{padding-left: 10px;color: #333;}
	}

</style>
