<!--Компонент навигационное меню-->
<template>
    <Menu mode="horizontal" theme="primary">
        <div class="system-title">
            <!-- <img src="/imgs/logo.png" alt="digit logo"> -->
            <span
            >Информационная система сбора, обработки и анализа данных об
        использовании протезно-ортопедических изделий</span
            >
            <!-- лого Цифра про -->
        </div>
        <div class="layout-nav">
            <MenuItem
                v-if="!$auth.check()"
                name="{name: login}"
                :to="{ name: 'login' }"
            >
                <Icon type="ios-navigate"></Icon>
                Вход
            </MenuItem>
            <MenuItem
                v-if="!$auth.check()"
                name="{name: register}"
                :to="{ name: 'register' }"
            >
                <Icon type="ios-navigate"></Icon>
                Регистрация
            </MenuItem>
            <MenuItem
                v-if="$auth.check('admin')"
                name="admin.dashboard"
                :to="{ name: 'admin.dashboard' }"
            >
                <Icon type="ios-desktop-outline"/>
                Панель
            </MenuItem>
            <MenuItem
                v-if="$auth.check('user')"
                name="user.dashboard"
                :to="{ name: 'user.dashboard' }"
            >
                <Icon type="ios-desktop-outline"/>
                Панель
            </MenuItem>
            <MenuItem
                v-if="$auth.check()"
                name="logout"
                @click.native="$auth.logout()"
            >
                <Icon type="ios-exit-outline"/>
                Выход
            </MenuItem>
        </div>
    </Menu>
</template>

<script>
export default {
    data() {
        return {};
    },
    mounted() {
        //
    },
};
</script>
