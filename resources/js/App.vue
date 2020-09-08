<!--<template>-->
<!--    <div id="main">-->
<!--        <header id="header">-->
<!--            <h1>-->
<!--                <router-link :to="{name: 'home'}">-->
<!--                    Laravel Vue SPA-->
<!--                </router-link>-->
<!--            </h1>-->
<!--            <navigationMenu></navigationMenu>-->
<!--        </header>-->
<!--        <div id="content">-->
<!--            <router-view></router-view>-->
<!--        </div>-->
<!--    </div>-->
<!--</template>-->

<template>
    <div class="layout">
        <Layout>
            <Header>
                <Menu mode="horizontal" theme="dark">
                    <div class="layout-logo">
                        ЦИФРА
                    </div>
                    <div class="layout-nav">
                        <MenuItem v-if="!$auth.check()" v-for="(route, key) in routes.unlogged"
                                   v-bind:key="route.path" name="{name: route.path}"
                                  :to="{ name : route.path }" :key="key">
                            <Icon type="ios-navigate"></Icon>
<!--                            <router-link :to="{ name : route.path }" :key="key">-->
                                {{ route.name }}
<!--                            </router-link>-->
                        </MenuItem>
<!--                        <MenuItem v-if="$auth.check(1)" v-for="(route, key) in routes.unlogged" v-bind:key="route.path">-->
<!--                            <Icon type="ios-navigate"></Icon>-->
<!--                            <router-link :to="{ name : route.path }" :key="key">-->
<!--                                {{ route.name }}-->
<!--                            </router-link>-->
<!--                        </MenuItem>-->
<!--                        <MenuItem v-if="$auth.check(2)" v-for="(route, key) in routes.unlogged" v-bind:key="route.path">-->
<!--                            <Icon type="ios-navigate"></Icon>-->
<!--                            <router-link :to="{ name : route.path }" :key="key">-->
<!--                                {{ route.name }}-->
<!--                            </router-link>-->
<!--                        </MenuItem>-->
                        <MenuItem v-if="$auth.check()"  name="logout" @click.native="$auth.logout()">
                            <Icon type="ios-navigate"></Icon>
                                logout
                        </MenuItem>
                    </div>
                </Menu>
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
<!--                <Card>-->

                        <router-view></router-view>

<!--                </Card>-->
            </Content>
            <Footer class="layout-footer-center">2020 &copy; Цифра</Footer>
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
}
</style>


<script>
// import navigationMenu from './components/Menu.vue'
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
    }
}
</script>


