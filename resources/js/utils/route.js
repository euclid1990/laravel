import env from '@/utils/env'

const URL_PREFIX = '/' + env('MIX_API_PREFIX', 'api') + '/' + env('MIX_API_VERSION', 'v1') + '/'

const ROUTE_PREFIX = env('MIX_API_PREFIX', 'api') + '.' + env('MIX_API_VERSION', 'v1') + '.'

const route = (name, parameters = {}) => {
  return window.endpoint.route(ROUTE_PREFIX + name, parameters)
}

const url = (url, parameters = {}) => {
  return window.endpoint.url(URL_PREFIX + url, parameters)
}

export {
  route,
  url
}
