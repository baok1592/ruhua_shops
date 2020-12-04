import axios from 'axios'
import {Api_url,IsDemo} from './config' 
import { Message } from 'element-ui' 

const check_demo = () => {
  Message({
    message: "演示版",
    type: 'error',
    duration: 5 * 1000
  })
}

//创建axios实例
var service = axios.create({
	baseURL: Api_url,
	timeout: 5000
})

//添加请求拦截器 
service.interceptors.request.use(function(config) { 
	//改为缓存token 
	config.headers.token =localStorage.getItem("token"); 
	return config
}, function(error) {
	//catch 自定义异常
	return Promise.reject(error)
})


//添加响应拦截器
service.interceptors.response.use(function(response) { 
  const status = response.status.toString();  
  const res = response.data 
    if (status.charAt(0) != 2) {
      const errMsg = res.msg || '请求失败'
      Message({
        message: errMsg,
        type: 'error',
        duration: 5 * 1000
      }) 
      return Promise.reject(new Error(errMsg))
    } else {
      return res
    }
}, function(err) { 
    console.log("请求失败")
    let msg =''
    msg = "请求错误"
    if (err.response) {  
      if (err.response.status == 401) {
        localStorage.clear(); //结果为401，表示无权限，或token过期；返回login页面
        location.href = './#/login'; 
      }			
      if(err.response.data && err.response.data.msg){		  
        msg = err.response.data.msg
      }
    }	 				
    Message({
      showClose: true,
      message: msg,
      type: 'error'
    });
    return Promise.reject(err)
})
  
export default { 
  service,
  
	//get请求
  get(url, data) {
    return service({
      url: url,
      method: 'get',
      data
    })
  },  
  //便于切换演示版
  get_show(url, data) { 
    return service({
      url: url,
      method: 'get',
      data
    })
  },  
  //post请求
	post(url, data) { 
    return service({
      url: url,
      method: 'post',
      data
    })
  }, 
  //便于切换演示版
	post_show(url, data) {
    if (IsDemo) {
      check_demo()
      return;
    } 
    return service({
      url: url,
      method: 'post',
      data
    })
  },
   //put请求
	put(url, data) {
    return service({
      url: url,
      method: 'put',
      data
    })
  }, 
  //便于切换演示版
	put_show(url, data) { 
    if (IsDemo) {
      check_demo()
      return;
    }  
    return service({
      url: url,
      method: 'put',
      data
    })
  } 
}
