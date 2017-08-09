import VueRouter    from 'vue-router'


import auth from './auth'
import Register from './components/auth/Register.vue'
import Users from './components/Users.vue'
import Dashboard from './components/Dashboard.vue'
import WorklogsSettings from './components/WorklogsSettings.vue'
import ListOfworklogs from './components/ListOfworklogs.vue'

import Login from './components/auth/Login.vue'

function requireAuth (to, from, next) {
    if (!auth.loggedIn()) {
        next({
            path: '/login',
            query: { redirect: to.fullPath }
        })
    } else {
        auth.setHeaders();
        next()
    }
}

export default new VueRouter({
mode: 'hash',
    base: __dirname,
      routes: [
          { path: '/dashboard', component: Dashboard},
          { path: '/list-of-worklogs', component: ListOfworklogs, beforeEnter: requireAuth },
          { path: '/settings', component: WorklogsSettings, beforeEnter: requireAuth },
          { path: '/users', component: Users, beforeEnter: requireAuth },
          { path: '/login', component: Login },
          { path: '/register-success', component: Login },
          { path: '/register', component: Register },
          { path: '/logout',
              beforeEnter (to, from, next) {
                  auth.logout()
                  next('/')
              }
          }
      ]
});
