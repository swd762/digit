// Список и описание маршрутов для Vue Router


import VueRouter from 'vue-router'

// Импорт шаблонов страниц для маршрутов
import Home from './pages/Home'
import Login from './pages/Login'
import Register from './pages/Register'
import UserDashboard from './pages/user/Dashboard'
import AdminDashboard from './pages/admin/Dashboard'
import AdminEditUser from './pages/admin/EditUser'
import AdminCreateUser from './pages/admin/CreateUser'
import AdminLayout from './pages/admin/AdminLayout'
import AdminEdit from './pages/admin/Edit'
import UserLayout from "./pages/user/UserLayout";
import PatientCard from "./pages/user/PatientCard";

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
            auth: false
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
    // Страницы пользователей
    {
        path: '/user',
        component: UserLayout,
        meta: {
            auth: true
        },
        children: [
            {
                path: 'dashboard',
                name: 'user.dashboard',
                component: UserDashboard

            },
            {
                path: ':patientId/card',
                name: 'user.card',
                component: PatientCard
            }
        ]
    },
    // Страницы администратора
    {
        path: '/admin',
        //name: 'admin',
        component: AdminLayout,
        meta: {
            auth: {
                roles: 'admin',
                redirect: {name: 'login'},
                forbiddenRedirect: 'admin.dashboard'
            }
        },
        children: [
            {
                path: '',
                component: Home
            },
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: AdminDashboard

            },
            {
                path: ':userId/edit',
                name: 'admin.edit',
                component: AdminEditUser
            },
            {
                path: 'create',
                name: 'admin.create',
                component: AdminCreateUser
            }
        ]
    },

    // страница редактирования пользователя
    // {
    //     path: '/edit_user',
    //     name: 'admin.edit_user',
    //     component: AdminEditUser,
    //     props: true,
    //     meta: {
    //         auth: {
    //             roles: 'admin',
    //             redirect: {name: 'login'}
    //         }
    //     },
    // },
    // страница создания пользователя
    {
        path: '/create_user',
        name: 'admin.create_user',
        component: AdminCreateUser,
        props: true,
        meta: {
            auth: {
                roles: 'admin',
                redirect: {name: 'login'}
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

