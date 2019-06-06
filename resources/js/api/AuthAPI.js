import Request from './BaseRequest'

export function login({ data }) {
  return Request.post({ url: 'api/v1/login', data })
}
