import http from '@/common/axios'
const request=http.service

export default {
  login(data) {
    return request({
      url: '/index/admin/login',
      method: 'post',
      data
    })
  }
}