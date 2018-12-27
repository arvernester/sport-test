import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  { path: '/', name: 'team-index', component: require('./components/teams/index').default }
]

export default new VueRouter({
  routes
})