<!--Шаблон формы авторизации-->
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
            // переменные шаблона используемые формой
            loginForm: {
                user: '',
                password: ''
            }
        }
    },
    methods: {
        // метод отправки данных автоиризации и последующей маршрутизации
        login() {
            let app = this
            let redirect = this.$auth.redirect()
            this.$auth.login({
                data: {
                    // данные с формы для отправки
                    name: app.loginForm.user,
                    password: app.loginForm.password
                },
                rememberMe: true,
                // никуда не переходить в случаях неуспешной аутентификации
                redirect: null,
                // разрешить читать пользователя
                fetchUser: true,
            }).then(()=>{
                // после успешной аутентификации проводим авторизацию и в зависимости от роли кидаем каждого в свою панель
                const redirectTo = redirect ? redirect.from.name : this.$auth.user().role === 'admin' ?
                    'admin' : 'dashboard'
                this.$router.push({name: redirectTo})
            }).catch(()=>{
                // здесь ловим ошибки обмена с сервером
            })
        }
    //    ****
    }
}
// TODO: сделать валидацию данных в форме на лету без запроса к серверу
//

</script>
