// import bearer from '@websanova/vue-auth/drivers/auth/bearer'
// import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
// import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
// Auth base configuration some of this options
// can be override in method calls
const config = {
  auth: bearer,
  http: axios,
  router: router,
  tokenDefaultName: 'laravel-vue',
  tokenStore: ['localStorage'],
  rolesVar: 'role',
  registerData: {url: '/register', method: 'POST', redirect: '/login'},
  loginData: {url: '/login', method: 'POST', redirect: '', fetchUser: true},
  logoutData: {url: '/logout', method: 'POST', redirect: '/', makeRequest: true},
  fetchData: {url: '/user', method: 'GET', enabled: true},
  refreshData: {url: '/refresh', method: 'GET', enabled: true, interval: 30}
}
export default config