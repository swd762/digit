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
        <Table border :columns="table_columns" :data="patients_data">
            <template slot-scope="{ row }" slot="birthDate">
                <strong>{{ row.birth_date }}</strong>
            </template>
            <template slot-scope="{ row }" slot="product">
                <p v-for="product in row.products ">
                    {{ product.name }}
                </p>
            </template>
            <template slot-scope="{ row }" slot="productDate">
                <p v-for="product in row.products">
                    {{ product.date }}
                </p>
            </template>

            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="openPatientCard(row)">
                    Карточка пациента
                </Button>
            </template>
        </Table>
    </div>
</template>

<script>
export default {
    name: "PatientsList",
    data() {
        return {
            has_error: false,
            table_columns: [
                {
                    title: 'ФИО',
                    key: 'name',
                    width: 300,
                    align: 'center'
                },
                {
                    title: 'Дата рождения',
                    slot: 'birthDate',
                    key: 'birth_date',
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
            patients_data: []
        }
    },
    mounted() {
        // запрос с сервера списка пользователй после загрузки страницы
        this.getPatients()
    },
    methods: {
        // метод получения данных пользователей сервера через api
        getPatients() {
            this.$http({
                url: `patients`,
                method: 'GET'
            })
                .then((res) => {
                    this.patients_data = res.data
                }, () => {
                    this.has_error = true
                })
        },
        // метод роутера на страницу редактирования пользователя
        openPatientCard(row) {
            let id = row.id
            // console.log(id)
            this.$router.push({
                name: 'user.card',
                params: {patientId: id}
            })
        }
    }
}
</script>


