<!--Компонент Список пользователей -->
<template>
    <div class="list-table">
        <h3>Список пользователей</h3>
        <Table border :columns="table_columns" :data="users_data.data" :loading="loading">
            <template slot-scope="{ row }" slot="login">
                <strong>{{ row.name }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="editUser(row)"> Редактировать
                </Button>
                <Button type="error" size="small" @click="delUser(row)"> Удалить</Button>
            </template>
        </Table>
        <div style="margin: 10px; overflow: hidden">
            <div style="float: right">
                <Page
                    :total="this.users_data.total"
                    :page-size="users_data.per_page"
                    :current="users_data.current_page"
                    @on-change="
            (val) => {
              getUsers(val);
            }
          "
                ></Page>
            </div>
        </div>
            <div class="buttons-cont">
                <Button
                    type="primary"
                    @click="
          $router.push({
            name: 'admin.create',
          })
        "
                >
                    Создать нового пользователя
                </Button>
            </div>
        </div>
</template>

<script>
export default {
    name: "Users-list",
    data() {
        return {
            //Статус загрузки
            loading: false,
            //Наличие ошибок
            has_error: false,
            //Параметры отображения таблицы со списком
            table_columns: [
                {
                    title: "ID",
                    key: "id",
                    width: 100,
                    align: "center",
                },
                {
                    title: "Логин",
                    slot: "login",
                    key: "name",
                    width: 150,
                    align: "center",
                },
                {
                    title: "Роль",
                    key: "role",
                    width: 200,
                    align: "center",
                },
                {
                    title: " ",
                    slot: "action",
                    width: 250,
                    align: "center",
                },
            ],
            // данные пользователей, полученные с сервера
            users_data: [],
        };
    },
    created() {
        // запрос с сервера списка пользователй после загрузки страницы
        this.getUsers();
    },
    methods: {
        // метод получения данных пользователей сервера через api
        getUsers(page = 1) {
            this.loading = true;
            this.$http({
                url: "users" + "?page=" + page,
                method: "GET",
            }).then(
                (res) => {
                    this.loading = false;
                    this.users_data = res.data.users;
                },
                () => {
                    this.loading = false;
                    this.$Message.error("Ошибка получения пользователей");
                    this.has_error = true;
                }
            );
        },
        // метод роутера на страницу редактирования пользователя
        editUser(row) {
            this.$router.push({
                name: "admin.edit",
                params: {userId: row.id},
            });
        },
        // метод удаления пользвателя
        delUser(row) {
            let user = row.id;
            this.$Modal.confirm({
                title: "Подтверждение удаления пользователя",
                content: "<p>Вы уверены</p>",
                okText: "Да",
                cancelText: "Нет",
                onOk: () => {
                    this.loading = true;
                    this.$http({
                        url: "users/" + user + "/delete_user",
                        method: "delete",
                    })
                        .then((res) => {
                            this.loading = false;
                            this.$Message.success("Пользователь удален");
                            this.getUsers();
                        })
                        .catch((e) => {
                            this.loading = false;
                            this.$Message.error("Ошибка при удалении пользователя");
                            console.log(e);
                        });
                },
                onCancel: () => {
                    // this.$Message.info('Clicked cancel');
                },
            });
        },
    },
};
</script>

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

