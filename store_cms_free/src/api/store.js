import http from '@/common/axios'
import { IsDemo } from '@/common/config'
import { Message } from 'element-ui'

const request=http.service
const check_demo = () => {
  Message({
    message: "演示版",
    type: 'error',
    duration: 5 * 1000
  })
}
 
export default {
  //获取所有店铺 
  getAll(data={}) {
    return request({
      url: '/shops/cms/store_all',
      method: 'post',
      data
    })
  },

  //新增店铺
  add_store(data) { 
    if (IsDemo) {
      check_demo()
      return;
    }
    return request({
      url: 'shops/cms/store_add',
      method: 'post',
      data
    })
  },

  //新增店员
  add_assistant(data) {
    if (IsDemo) {
      check_demo()
      return;
    }
    return request({
      url: 'shops/cms/assistant_add',
      method: 'post',
      data
    })
  },

   //禁用店铺
   close_store(data) {
    if (IsDemo) {
      check_demo()
      return;
    }
    return request({
      url: 'shops/cms/stop_store',
      method: 'put',
      data
    })
  },

  //恢复店铺
  open_store(data) {
    if (IsDemo) {
      check_demo()
      return;
    }
    return request({
      url: 'shops/cms/recover_store',
      method: 'put',
      data
    })
  }
}


