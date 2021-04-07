<!--компонент списка пациента-->
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
        <Table :loading="isLoading" border :columns="tableColumns" :data="patients.data">
            <template slot-scope="{ row }" slot="birthDate">
                <strong>{{ new Date(row.birth_date).toLocaleDateString() }}</strong>
            </template>
            <template slot-scope="{ row }" slot="product">
                <p v-for="(diagnos, index) in row.diagnoses" :key="index">
                    <template v-if="diagnos.pivot.product">
                        {{ diagnos.pivot.product.name }}
                    </template>
                </p>
            </template>

            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="openPatientCard(row)"> Карточка
                    пациента
                </Button>
            </template>
        </Table>
        <div style="margin: 10px; overflow: hidden">
            <div style="float: right">
                <Page
                    :total="this.patients.total"
                    :page-size="patients.per_page"
                    :current="1"
                    @on-change="
            (val) => {
              getPatients(val);
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
            name: 'user.create',
          })
        "
            >
                Добавить пациента
            </Button>
        </div>
    </div>
</template>

<script>
export default {
    name: "PatientsList",
    data() {
        return {
            //Статус загрузки
            isLoading: false,
            //Наличие ошибок
            has_error: false,
            //Параметры отображения таблицы со списком
            tableColumns: [
                {
                    title: "ФИО",
                    key: "name",
                    width: 300,
                    align: "center",
                },
                {
                    title: "Дата рождения",
                    slot: "birthDate",
                    key: "birth_date",
                    width: 150,
                    align: "center",
                },
                {
                    title: "Выданные ПОИ",
                    slot: "product",
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
            // список пациентов
            patients: [],
        };
    },
    mounted() {
        // запрос с сервера списка пациентов после загрузки страницы
        this.getPatients();
    },
    methods: {
        // метод получения списка пациентов через api
        getPatients(page = 1) {
            this.isLoading = true;
            this.$http({
                url: `patients` + "?page=" + page,
                method: "GET",
            })
                .then((res) => {
                    this.patients = res.data;
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.has_error = true;
                    this.isLoading = false;
                });
        },
        // метод роутера на страницу редактирования пациента
        openPatientCard(row) {
            let id = row.id;
            // console.log(id)
            this.$router.push({
                name: "user.card",
                params: {patientId: id},
            });
        },
    },
};
</script>


