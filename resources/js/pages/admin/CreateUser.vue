<!--шаблон компонента- формы для создания пользователя-->
<template>
    <div style="max-width: 400px">

        <Form ref="formValidate" :model="formValidate" :label-width="80">
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин" ></Input>
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
        return {
            // данные для валидации формы
            formValidate: {
                name: '',
                mail: '',
                city: '',
                gender: '',
                interest: [],
                date: '',
                time: '',
                desc: ''
            },
            user_data: {
                name: '',
                password:'',
                password_confirmation:'',
                email:'',
                first_name:'',
                last_name:'',
                middle_name:'',
                role:'user'
            }
        }

    },
    mounted() {

    },
    methods: {
        // метод создания нового пользователя
        createUser() {
            this.$http({
                url:'auth/register',
                method: 'POST',
                data:this.user_data
            }).then(res => {
                console.log('ready')
                this.$router.push({
                    name: 'admin.dashboard'
                })
            })
        }
    }
}
</script>

<style scoped>

</style>
