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
        <h3>Список пациентов</h3>
        <Table border :columns="table_columns" :data="users_data">
            <template slot-scope="{ row }" slot="birthDate">
                <strong>{{ row.birthDate }}</strong>
            </template>
            <template slot-scope="{ row }" slot="product">
                <p v-for="product in row.product ">
<!--                    {{ row.product }}-->
                    {{ product }}
                </p>
            </template>
            <template slot-scope="{ row }" slot="productDate">
                <p v-for="productDate in row.productDate">
                    <!--                    {{ row.product }}-->
                    {{ productDate }}
                </p>
            </template>

            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="editUser(row)">
                    Карточка пациента
                </Button>
<!--                <Button type="error" size="small" @click="delUser(row)">-->
<!--                    Удалить-->
<!--                </Button>-->
            </template>
        </Table>
<!--        <div class="buttons-cont">-->
<!--            <Button type="primary" @click="$router.push({-->
<!--                name: 'admin.create_user'-->
<!--            })">-->
<!--                Создать нового пользователя-->
<!--            </Button>-->
<!--        </div>-->
    </div>
</template>

<script>
export default {
    name: "PatientsList",
    data() {
        return {
            has_error: false,
            // users: null,

            table_columns: [
                {
                    title: 'ФИО',
                    key: 'fio',
                    width: 300,
                    align: 'center'
                },
                {
                    title: 'Дата рождения',
                    slot: 'birthDate',
                    key: 'birthDate',
                    width: 150,
                    align: 'center'
                },
                {
                    title: 'Изделие',
                    slot: 'product',
                    key: 'product',
                    width: 200,
                    align: 'center'
                },
                {
                    title: ' ',
                    slot: 'productDate',
                    key: 'productDate',
                    width: 200,
                    align: 'center'
                },
                {
                    title: ' ',
                    slot: 'action',
                    width: 250,
                    align: 'center'
                }
            ],
            // данные пользователей, полученные с сервера
            users_data: [
                {
                    fio : 'Иванов Иван Иванович',
                    birthDate : '01.02.1950',
                    product : ["изделие 1", "изделие 2", "изделие 3"],
                    productDate: ["01.02.2020", "01.01.2021", "05.07.2020"]
                },
                {
                    fio : 'Алексеев Алексей Алексеевич',
                    birthDate : '01.04.1970',
                    product : ["изделие 10", "изделие 5"],
                    productDate: ["01.01.2021", "01.02.2020"]
                }
            ]
        }
    },
    mounted() {
        // запрос с сервера списка пользователй после загрузки страницы
        // this.getUsers()
    },
    methods: {
        // метод получения данных пользователей сервера через api
    //     getUsers() {
    //         this.$http({
    //             url: `users`,
    //             method: 'GET'
    //         })
    //             .then((res) => {
    //                 this.users_data = res.data.users
    //             }, () => {
    //                 this.has_error = true
    //             })
    //     },
    //     // метод роутера на страницу редактирования пользователя
    //     editUser(row) {
    //         let id = row.id
    //         // console.log(id)
    //         this.$router.push({
    //             name: 'admin.edit',
    //             params: {userId: id}
    //         })
    //     },
    //     // метод удаления полльзвателя
    //     delUser(row) {
    //         let user = row.id
    //         this.$Modal.confirm({
    //             title: 'Подтверждени удаления пользователя',
    //             content: '<p>Вы уверены</p>',
    //             okText: 'Да',
    //             cancelText: 'Нет',
    //             onOk: () => {
    //                 this.$http({
    //                     url: 'delete_user/' + user,
    //                     method: 'post'
    //                 }).then(res => {
    //                     this.getUsers();
    //                 }).catch(e =>{
    //                     console.log(e);
    //                 })
    //                 // this.$Message.info('Пользователь удален' + user);
    //             },
    //             onCancel: () => {
    //                 // this.$Message.info('Clicked cancel');
    //             }
    //         });
    //     }
    }
}
</script>


