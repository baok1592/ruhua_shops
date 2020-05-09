
var data = []
import http from './axios.js'
export default {
	
	async get_bank_data(){
		return http.post('bank/list').then(res => {
			this.data = res.data.data
			localStorage.setItem("account_list", JSON.stringify(res.data.data))
			console.log('请求结果:',this.data)
			return this.data
		})
	},
	
	async check_cache() {
		console.log('开始判断缓存是否存在或是否过期')
		const that = this
		const cache = JSON.parse(localStorage.getItem("account_list"))
		if (cache) {
			console.log('缓存存在')
			this.data = cache
		}else{
			console.log('缓存不存在,重新请求')
			that.data = await that.get_bank_data()
			console.log(that.data)
		}
	}
	
	
}
