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
  //获取所有分类
  getAll(data={}) { 
    return request({
      url: '/category/admin/all_category',
      method: 'get',
      data
    })
  },

  //新增分类
  add(data) {
    if (IsDemo) {
      check_demo()
      return;
    }
    return request({
      url: 'category/admin/add_category',
      method: 'post',
      data
    })
  }
}


