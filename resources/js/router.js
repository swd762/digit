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
import AdminProducts from './pages/admin/Products'
import UserLayout from "./pages/user/UserLayout";
import PatientCard from "./pages/user/PatientCard";
import CreatePatient from "./pages/user/CreatePatient";

//Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: {
            auth: undefined,
            name: "Главная"
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            auth: false,
            name: "Регистрация"
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            auth: false,
            name: "Авторизация"
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
                component: UserDashboard,
                meta: {
                    name: "Панель врача"
                }
            },
            {
                path: ':patientId/card',
                name: 'user.card',
                component: PatientCard,
                meta: {
                    name: "Карточка пациента"
                }
            },
            {
                path: 'create',
                name: 'user.create',
                component: CreatePatient,
                meta: {
                    name: "Добавление пациента"
                }
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
                component: AdminDashboard,
                meta: {
                    name: "Панель администратора"
                },



            },
            {
                path: 'products',
                name: 'admin.products',
                component: AdminProducts,
                meta: {
                    name: "Редактирование изделий"
                }
            },
            {
                path: ':userId/edit',
                name: 'admin.edit',
                component: AdminEditUser,
                meta: {
                    name: "Редактирование пользователя"
                }
            },
            {
                path: 'create',
                name: 'admin.create',
                component: AdminCreateUser,
                meta: {
                    name: "Создание пользователя"
                }
            }
        ]
    },
]

const router = new VueRouter({
    history: true,
    mode: 'history',
    routes
})

export default router

