<!--шаблон компонента- формы для создания пользователя-->
<template>
    <div style="max-width: 400px">

        <Form ref="user_data" :model="user_data" :rules="ruleInline" :label-width="80">
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин"></Input>
            </FormItem>
            <FormItem label="Password" prop="password">
                <Input type="password" v-model="user_data.password" placeholder="Ввведите пароль"></Input>
            </FormItem>
            <FormItem label="Password confirm" prop="password_confirmation">
                <Input type="password" v-model="user_data.password_confirmation" placeholder="Ввведите пароль"></Input>
            </FormItem>
            <FormItem label="E-mail" prop="email">
                <Input v-model="user_data.email" placeholder="Ввведите email"></Input>
            </FormItem>
            <FormItem label="Имя" prop="first_name">
                <Input v-model="user_data.first_name" placeholder="Введите имя"></Input>
            </FormItem>
            <FormItem label="Фамилия" prop="last_name">
                <Input v-model="user_data.last_name" placeholder="Введите фамилию"></Input>
            </FormItem>
            <FormItem label="Отчество" prop="middle_name">
                <Input v-model="user_data.middle_name" placeholder="Введите отчество"></Input>
            </FormItem>
            <FormItem label="Role">
                <Select v-model="user_data.role">
                    <Option value="user">user</Option>
                    <Option value="admin">admin</Option>
                </Select>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="createUser">Создать</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "CreateUser",
    data() {
        const validateLogin = (rule, value, callback) => {
            if (this.validServer.name) {
                callback(new Error(this.validServer.name));
            } else {
                callback();
            }
        };
        const validateFirstName = (rule, value, callback) => {
            if (this.validServer.first_name) {
                callback(new Error(this.validServer.first_name));
            } else {
                callback();
            }
        };
        const validatePassword = (rule, value, callback) => {
            if (this.validServer.password) {
                callback(new Error(this.validServer.password));
            } else {
                callback();
            }
        };
        const validatePasswordConfirm = (rule, value, callback) => {
            if (this.validServer.password) {
                callback(new Error(this.validServer.password));
            } else {
                callback();
            }
        };
        return {
            // данные для валидации формы
            user_data: {
                name: '',
                password: '',
                password_confirmation: '',
                email: '',
                first_name: '',
                last_name: '',
                middle_name: '',
                role: 'user'
            },
            validServer: [{
                name: '',
                first_name: '',
                password: '',
                password_confirmation: ''
            }],
            ruleInline: {
                name: [
                    {validator: validateLogin, trigger: 'blur'}
                ],
                first_name: [
                    {validator: validateFirstName, trigger: 'blur'}
                ],
                password: [
                    {validator: validatePassword, trigger: 'blur'}
                ],
                password_confirmation: [
                    {validator: validatePasswordConfirm, trigger: 'blur'}
                ],
            }
        }

    },
    mounted() {

    },
    methods: {
        // метод создания нового пользователя
        createUser() {
            this.$http({
                url: 'auth/register',
                method: 'POST',
                data: this.user_data
            }).then(res => {
                console.log('ready')
                this.$router.push({
                    name: 'admin.dashboard'
                })
            }).catch(e => {
                let errors = e.response.data.errors;
                this.validServer.name = null;
                this.validServer.first_name = null;
                this.validServer.password = null;
                this.validServer.password_confirmation = null;

                if (errors.name != null) {
                    this.validServer.name = errors.name;
                    // console.log(this.validServer.name)
                }
                if (errors.first_name != null) {
                    this.validServer.first_name = errors.first_name;
                    // console.log(this.validServer.first_name)
                }
                if (errors.password != null) {
                    this.validServer.password = errors.password;
                    // console.log(this.validServer.password)
                }
                if (errors.password_confirmation != null) {
                    this.validServer.password_confirmation = errors.password_confirmation;
                    // console.log(this.validServer.password_confirmation)
                }
                // делаем валидацию формы логин пароль
                this.$refs['user_data'].validate(() => {
                    this.$Message.error();
                });
            })
        }
    }
}
</script>

<style scoped>

</style>
