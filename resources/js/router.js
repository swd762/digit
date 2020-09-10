import VueRouter from 'vue-router'

// Pages
import Home from './pages/Home'
import Login from './pages/Login'
import Register from './pages/Register'
import Dashboard from './pages/user/Dashboard'
import AdminDashboard from './pages/admin/Dashboard'
import AdminEditUser from './pages/admin/EditUser'

//Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth:false
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false
        }
    },
    // User Dashboard
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            auth:true,
        }
    },
    // Admin pages
    {
        path:'/admin',
        name:'admin',
        component: AdminDashboard,
        meta: {
            auth: {
                roles: 'admin',
                redirect: {name:'login'},
                forbiddenRedirect:'dashboard'
            }
        },

    },
    {
        path: '/edit_user',
        name: 'admin.edit_user',
        component: AdminEditUser,
        props: true,
        meta: {
            auth: {
                roles: 'admin',
                redirect: {name:'login'}
            }
        },

    }
]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes
})

export default router

