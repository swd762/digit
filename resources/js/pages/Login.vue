<template>

    <div style="padding: 20px;display: flex;justify-content: center">
        <Card :bordered="false" style="width: 375px">
            <p slot="title" style="text-align:center">Авторизация</p>
            <Form ref="loginForm" :model="loginForm"  inline class="login-form" autocomplete="off">
                <FormItem prop="user">
                    <Input type="text" v-model="loginForm.user" placeholder="Логин">
                        <Icon type="ios-person-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem prop="password">
                    <Input type="password" v-model="loginForm.password" placeholder="Пароль">
                        <Icon type="ios-lock-outline" slot="prepend"></Icon>
                    </Input>
                </FormItem>
                <FormItem>
                    <Button type="primary" @click="login()">Войти</Button>
                </FormItem>
            </Form>
        </Card>
    </div>

</template>
<style>
.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

</style>
<script>
export default {
    data() {
        return {
            loginForm: {
                user: '',
                password: ''
            }
        }
    },
    methods: {
        login() {
            let app = this
            let redirect = this.$auth.redirect()
            this.$auth.login({
                data: {
                    name: app.loginForm.user,
                    password: app.loginForm.password
                },
                rememberMe: true,
                redirect: null,
                fetchUser: true,
            }).then(()=>{
                const redirectTo = redirect ? redirect.from.name : this.$auth.user().role === 'admin' ?
                    'admin' : 'dashboard'
                this.$router.push({name: redirectTo})
            })
            //     .catch(error => {
            //     this.$Message.error('Ошибка авторизации');
            // });
        },

    }
}
</script>
