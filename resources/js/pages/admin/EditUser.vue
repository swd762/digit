<!--Шаблон страницы редактирования пользователя-->
<template>
    <div style="max-width: 400px">
        <Form ref="formValidate" :model="formValidate" :label-width="80">
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин" disabled></Input>
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
                <Button type="primary" @click="updateUser">Записать</Button>
                <Button @click="getUserData(userId)" style="margin-left: 8px">Прочитать данные</Button>
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
            user_data: [],
            userId: null
        }
    },
    mounted() {
        // когда страница загрузилась проверяем прилител ли параметр id, если нет возвращаемся в панель
        // если прилетел то читаем юзера для редактирования
        this.userId = this.$route.params.userId
        //console.log(this.$route.params.userId)
        if (this.userId == null) {
            this.$router.push({
                name: 'admin'
            })
        } else {
            // this.userId = this.id
            this.getUserData(this.userId)

            //console.log(this.ids)
        }
    },
    methods: {
        // Метод чтения нужного пользователя из БД
        getUserData(id) {
            // читаем выбранного пользователя get запросом с параметром id
            this.$http.get(
                'users',
                {params: id}
            ).then((res) => {
                // получаем от сервера массив и записываем его в наш user_data
                this.user_data = res.data.user
            }).catch(() => {
                this.$router.push({
                    name: 'admin'
                })
            })

        },
        // метод обновления данных пользовтеля в БД
        updateUser() {
            // отправляем массив с данными пользователя на сервер для последующей записи в БД
            this.$http({
                url: `update_user`,
                method: 'POST',
                data: this.user_data
            }).then((res) => {
                // получаем ответ и обновлем данные формы для данного пользователя
                this.getUserData(this.id)
            })
        },
        deleteUser(id) {
            // TODO
        }
    }

}
</script>

<style scoped>

</style>
