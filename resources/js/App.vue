<!--Шаблон приложения Vue-->
<template>
    <div class="layout">
        <Layout>
            <!--Хэдер с меню-->
            <Header>
                <navigationMenu></navigationMenu>
            </Header>
            <!-- ****  -->
            <Content :style="{padding: '0 50px'}">
                <!-- хлебные крошки с возможностью перехода по маршруту-->
                <Breadcrumb :style="{margin: '20px 0'}">
                    <BreadcrumbItem>
                        <router-link :to="{ name : 'home' }">
                            Home
                        </router-link>
                    </BreadcrumbItem>
                    <BreadcrumbItem v-if="this.$route.name != 'home'">
                        {{this.$route.name}}
                    </BreadcrumbItem>
                </Breadcrumb>
                <!-- ****  -->
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
    width: 200px;
    height: 30px;
    border-radius: 3px;
    float: left;
    position: relative;
    top: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 31px;
}

.layout-logo img {
    width: 100%;
}

.ivu-layout-header {
    background: #2d8cf0 !important;
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
    background: #818ba1 !important;
    color: #fff !important;
    padding: 15px 50px !important;
}
</style>


<script>
// подгрузка компонента меню
import navigationMenu from './components/Menu.vue'

export default {
    data() {
        return {}
    },
    // объявление компонента меню
    components: {
        navigationMenu
    }
}
</script>


