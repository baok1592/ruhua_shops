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
  //获取所有用户
  getAll(data={}) {
    return request({
      url: 'user/admin/get_all_user',
      method: 'get',
      data
    })
  } 
   
}


