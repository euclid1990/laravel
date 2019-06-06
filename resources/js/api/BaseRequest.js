import { ACCESS_TOKEN } from '@/constants'

export function requestHelper({ method, options }) {
  return axios({
    ...options,
    baseURL: 'https://localhost:8443',
    method,
    headers: {
      Bearer_Authorization: `Bearer ${localStorage.getItem(ACCESS_TOKEN)}`,
      Authorization: `Basic ${btoa('web:123456')}`,
    },
  }).then(response => Promise.resolve(options.originData ? response : response.data))
}


function request(options) {
  return requestHelper(options)
    .then(response => response)
    .catch(error => Promise.reject(error.response.data))
}

const Request = {
  get(options) {
    return request({ method: 'GET', options })
  },

  post(options) {
    return request({ method: 'POST', options })
  },

  put(options) {
    return request({ method: 'PUT', options })
  },

  patch(options) {
    return request({ method: 'PATCH', options })
  },

  delete(options) {
    return request({ method: 'DELETE', options })
  },
}

export default Request
