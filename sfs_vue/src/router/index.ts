import { createRouter, createWebHistory } from 'vue-router'
import { useAccount } from '@/store/Account'


import Homepage from '../views/Homepage.vue'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue'

import Approver from '../views/Approver.vue'
import Staff from '../views/Staff.vue'


import Invalid from '../views/Invalid.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),

  linkActiveClass: 'active',

  routes: [
    { path: '/', name: 'SFS', component: Homepage },
    { path: '/register', name: 'Register', component: Register },
    {
      path: '/login',
      beforeEnter: (to, from, next) => {
        const account = useAccount()
        if (account.state.role) {
          next({ path: '/' + account.state.role });
        }
        else {
          next();
        }
      },
      name: 'Login',
      component: Login
    },

    {
      path: '/approver',
      beforeEnter: (to, from, next) => {
        const account = useAccount()
        if (account.state.role != 'approver') {
          next({ path: '/login' });
        }
        else {
          next();
        }
      },
      name: 'Approver',
      component: Approver
    },

    {
      path: '/staff',
      beforeEnter: (to, from, next) => {
        const account = useAccount()
        if (account.state.role != 'staff') {
          next({ path: '/login' });
        }
        else {
          next();
        }
      },
      name: 'Staff',
      component: Staff
    },



    {
      path: '/:pathMatch(.*)*',
      name: '404',
      component: Invalid
    },
  ],

  scrollBehavior(to) {
    return {
      top: 0,
      behavior: 'smooth'
    }
  },
})

router.afterEach((to) => {
  document.title = 'SFS | ' + to.name?.toString();
})

export default router
