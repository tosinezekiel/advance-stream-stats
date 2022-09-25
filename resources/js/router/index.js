// router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../Pages/Auth/Login.vue'
import Pricing from '../Pages/Pricing.vue'
import Dashboard from '../Pages/Dashboard.vue'
import Settings from '../Pages/Settings.vue'
import store from '../store'

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login,
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
    },
    {
        path: '/pricing',
        name: 'Pricing',
        component: Pricing
    },
    {
        path: '/settings',
        name: 'Settings',
        component: Settings
    }
]

const router = createRouter({ history: createWebHistory(), routes })

router.beforeEach((to, from, next) => {
    if (to.name !== 'Login' && !store.getters['auth/isLoggedIn']) {
        next({ name: 'Login' })
        return false
    }
    if(to.name == 'Login' && store.getters['auth/isLoggedIn']){
        next({ name: 'Dashboard' })
        return false
    }
    else next()
})

export default router
