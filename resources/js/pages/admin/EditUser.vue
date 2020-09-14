<!--Шаблон страницы редактирования пользователя-->
<template>
    <div style="max-width: 400px">
        <Form ref="formValidate" :model="formValidate"  :label-width="80">
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин" disabled></Input>
            </FormItem>
            <FormItem label="E-mail" prop="mail">
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
            <FormItem label="Роль" prop="role">
                <Input v-model="user_data.role" placeholder="Введите отчество"></Input>
            </FormItem>
            <FormItem label="Role" prop="role">
                <RadioGroup v-model="user_data.role">
                    <Radio label="user">user</Radio>
                    <Radio label="admin">admin</Radio>
                </RadioGroup>
            </FormItem>
                    <FormItem>
                        <Button type="primary" @click="updateUser" >Записать</Button>
                        <Button @click="getUserData" style="margin-left: 8px">Прочитать данные</Button>
                    </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "EditUser",
    props: ['id'],
    data() {
        return {
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
            user_data: []

        }
    },
    mounted() {
        // когда страница загрузилась проверяем прилител ли параметр id, если нет возвращаемся в панель
        // если прилетел то читаем юзера для редактирования
        if (this.id == null) {
            this.$router.push({
                name: 'admin'
            })
        } else {
            this.getUserData(this.id)
        }
    },
    methods: {
        getUserData(id) {
            // читаем
            this.$http.get('users', {params: id

            }).then((res)=>{
                this.user_data = res.data.user
            })
            //     this.$http({
            //         url: 'get_user/5',
            //         method: 'GET'
            //     })
            //         .then((res) => {
            //             res.data.users.forEach((user, key)=>{
            //                 if(user.id == this.id) {
            //                     this.user_data = user
            //                 }
            //             })
            //         }, () => {
            //             this.has_error = true
            //         })
        },
        updateUser() {
            this.$http({
                url: `update_user`,
                method: 'POST',
                data: this.user_data
            }).then((res)=>{
                console.log(res)
                this.getUserData()
            })
        },
        deleteUser(id) {

        }

    }

}


</script>

<style scoped>

</style>
