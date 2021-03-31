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
// products
import AdminProducts from './pages/admin/products/Products'
import AdminProductEdit from './pages/admin/products/EditProduct'
import AdminProductCreate from './pages/admin/products/CreateProduct'
// modules
import AdminModules from './pages/admin/modules/Modules'
import AdminModulesEdit from './pages/admin/modules/EditModule'
import AdminModulesCreate from './pages/admin/modules/CreateModule'

// diagnoses
import AdminDiagnoses from './pages/admin/diagnoses/Diagnoses'
import AdminDiagnosesEdit from './pages/admin/diagnoses/EditDiagnose'
import AdminDiagnosesCreate from './pages/admin/diagnoses/CreateDiagnose'

import UserDiagnoses from './pages/user/diagnoses/Diagnoses'
import UserDiagnosesEdit from './pages/user/diagnoses/EditDiagnose'
import UserDiagnosesCreate from './pages/user/diagnoses/CreateDiagnose'

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
            },
            // diagnoses
            {
                path: 'diagnoses',
                name: 'user.dashboard.diagnoses',
                component: UserDiagnoses,
                meta: {
                    name: "Справочник диагнозов"
                }
            },
            {
                path: ':diagnosId/edit_diagnos',
                name: 'user.dashboard.diagnoses.edit',
                component: UserDiagnosesEdit,
                meta: {
                    name: "Редактирование диагноза"
                }
            },
            {
                path: 'create_diagnos',
                name: 'user.dashboard.diagnoses.create',
                component: UserDiagnosesCreate,
                meta: {
                    name: "Создание диагноза"
                }
            },
            // end diagnoses
        ]
    },
    // Страницы администратора
    {
        path: '/admin',
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
            // products
            {
                path: 'products',
                name: 'admin.dashboard.products',
                component: AdminProducts,
                meta: {
                    name: "Справочник ПОИ"
                }
            },
            {
                path: ':productId/edit_product',
                name: 'admin.dashboard.product.edit',
                component: AdminProductEdit,
                meta: {
                    name: "Редактирование ПОИ"
                }
            },
            {
                path: 'create_product',
                name: 'admin.dashboard.product.create',
                component: AdminProductCreate,
                meta: {
                    name: "Создание ПОИ"
                }
            },
            // end products

            // modules
            {
                path: 'modules',
                name: 'admin.dashboard.modules',
                component: AdminModules,
                meta: {
                    name: "Справочник УСПД"
                }
            },
            {
                path: ':moduleId/edit_module',
                name: 'admin.dashboard.modules.edit',
                component: AdminModulesEdit,
                meta: {
                    name: "Редактирование УСПД"
                }
            },
            {
                path: 'create_module',
                name: 'admin.dashboard.modules.create',
                component: AdminModulesCreate,
                meta: {
                    name: "Создание УСПД"
                }
            },
            // end modules

            // diagnoses
            {
                path: 'diagnoses',
                name: 'admin.dashboard.diagnoses',
                component: AdminDiagnoses,
                meta: {
                    name: "Справочник диагнозов"
                }
            },
            {
                path: ':diagnosId/edit_diagnos',
                name: 'admin.dashboard.diagnoses.edit',
                component: AdminDiagnosesEdit,
                meta: {
                    name: "Редактирование диагноза"
                }
            },
            {
                path: 'create_diagnos',
                name: 'admin.dashboard.diagnoses.create',
                component: AdminDiagnosesCreate,
                meta: {
                    name: "Создание диагноза"
                }
            },
            // end diagnoses

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

