// router/index.js
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../Pages/Auth/Login.vue'
import Pricing from '../Pages/Pricing.vue'
import Dashboard from '../Pages/Dashboard.vue'

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard
    },
    {
        path: '/pricing',
        name: 'Pricing',
        component: Pricing
    }
]

const router = createRouter({ history: createWebHistory(), routes })
export default router
