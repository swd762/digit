<style scoped>
.buttons-cont {
    margin: 15px;
}
.list-table {
    display: block;
}
.list-table h3 {
    text-align: center;
    margin: 10px 0;
}
</style>
<template>
    <div class="list-table">
        <h3>Список пользователей</h3>
        <Table  border :columns="table_columns" :data="users_data" >
            <template slot-scope="{ row }" slot="login">
                <strong>{{ row.name }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="editUser(row)">
                    Редактировать
                </Button>
<!--                <Button type="error" size="small" @click="remove(index)">Delete</Button>-->
            </template>
        </Table>
        <div class="buttons-cont">
            <Button type="primary" @click="handleSubmit('formDynamic')">Создать нового пользователя</Button>
            <Button type="error"  @click="remove(index)">Удалить пользователя</Button>
        </div>
    </div>
</template>

<script>
export default {
name: "Users-list",
    data() {
        return {
            has_error: false,
            // users: null,

            table_columns: [
                {
                    title: 'ID',
                    key: 'id',
                    width:50,
                    align: 'center'
                },
                {
                    title: 'Login',
                    slot: 'login',
                    key: 'name',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'E-mail',
                    key: 'email',
                    width: 200,
                    align: 'center'
                },
                {
                    title: ' ',
                    slot: 'action',
                    width: 150,
                    align: 'center'
                }
            ],
            // данные пользователей, полученные с сервера
            users_data: []
        }
    },
    mounted() {
    // запрос с сервера списка пользователй после загрузки страницы
        this.getUsers()
    },
    methods: {
    // метод получения данных пользователей сервера через api
        getUsers() {
            this.$http({
                url: `users`,
                method: 'GET'
            })
                .then((res) => {
                    this.users_data = res.data.users
                }, () => {
                    this.has_error = true
                })
        },
        // метод роутера на страницу редактирования пользователя
        editUser(row) {
            let id = row.id
            // console.log(id)
            this.$router.push({
                name:'admin.edit_user',
                params: {id: id}
            })
        },
        // метод удаления полльзвателя
        delUser(row) {
            let id = row.id
        }
    }
}
</script>


