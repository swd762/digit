<template>
    <div class="layout">
        <Layout>
            <Header>
                <navigationMenu></navigationMenu>
            </Header>
            <Content :style="{padding: '0 50px'}">
                <Breadcrumb :style="{margin: '20px 0'}">
                    <BreadcrumbItem>Home</BreadcrumbItem>
<!--                    <BreadcrumbItem v-if="$auth.check()">-->
<!--                        <router-link :to="{ name : routes.unlogged.path }" :key="key">-->
<!--                            {{ route.name }}-->
<!--                        </router-link>-->
<!--                    </BreadcrumbItem>-->
                    <!--            <BreadcrumbItem>Layout</BreadcrumbItem>-->
                </Breadcrumb>
                        <router-view></router-view>
            </Content>
            <Footer class="layout-footer-center">2020 &copy; Цифра.ПРО</Footer>
        </Layout>
    </div>
</template>
<style>
.layout {
    border: 1px solid #d7dde4;
    background: #f5f7f9;
    position: relative;
    border-radius: 4px;
    overflow: hidden;
}
.layout-logo {
    width: 100px;
    height: 30px;
    /*background: #5b6270;*/
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    /*left: 20px;*/
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 31px;
}
.ivu-layout-header {
    background: #2d8cf0!important;
}

.layout-nav {
    width: 420px;
    margin: 0 auto;
    margin-right: 20px;
}
.layout-nav a {
    color: #fff;
}

.layout-footer-center {
    text-align: center;
    background: #818ba1!important;
    color: #fff!important;
    padding: 15px 50px!important;
}
</style>


<script>
// подгрузка компонента меню
import navigationMenu from './components/Menu.vue'
export default {
    data() {
        return {
            routes: {
                // UNLOGGED
                unlogged: [
                    {
                        name: 'Register',
                        path: 'register'
                    },
                    {
                        name: 'Login',
                        path: 'login'
                    }
                ],

                // LOGGED USER
                user: [
                    {
                        name: 'Dashboard',
                        path: 'dashboard'
                    }
                ],
                // LOGGED ADMIN
                admin: [
                    {
                        name: 'Dashboard',
                        path: 'admin.dashboard'
                    }
                ]
            }
        }
    },
    // объявление компонента меню
    components: {
        navigationMenu
    }
}
</script>


