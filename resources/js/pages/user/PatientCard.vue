<template>
    <div style="">
        <div class="patient-card-row">
            <h4>Врач: </h4>
            <p>{{this.$auth.user().first_name}}</p>
        </div>
        <div class="patient-card-row">
            <h4>Пациент: </h4>
            <p>{{patients_data.name}}</p>
        </div>
        <div class="patient-card-row">
            <h4>Дата рождения: </h4>
            <p>{{patients_data.birth_date}}</p>
        </div>
        <div class="patient-card-row" v-for="product in patients_data.products">
            <h4>Название изделия: </h4>
            <p>{{product.name}}</p>
        </div>
        <div class="patient-card-row">
            <h4>ID модуля: </h4>
            <p >0000000000000000000001</p>
        </div>
        <div class="patient-card-row">
            <h4>Дата установки: </h4>
            <p>01.01.1999</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "PatientCard",
    data() {
        return {
            patients_data: [],
            patientId: null
        }
    },
    mounted() {
        // когда страница загрузилась проверяем прилител ли параметр id, если нет возвращаемся в панель
        // если прилетел то читаем юзера для редактирования
        this.patientId = this.$route.params.patientId
        console.log(this.$route.params.patientId)
        if (this.patientId == null) {
            this.$router.push({
                name: 'user.dashboard'
            })
        } else {
            this.getPatientData(this.patientId)
        }
    },
    methods: {
        // Метод чтения нужного пользователя из БД
        getPatientData(id) {
            // читаем выбранного пользователя get запросом с параметром id
            let db_index = id - 1;
            this.$http({
                url: 'patients/' + id,
                method: 'GET'
            })
                .then((res) => {
                    this.patients_data = res.data[db_index]
                }, () => {
                    this.has_error = true
                })
        }
    }
}
</script>

<style scoped>
.patient-card-row {
    display: flex;
    margin-bottom: 10px;
}
.patient-card-row h4 {
    width: 100px;
}
.patient-card-row p {
    display: flex;
    align-items: center;
}
</style>
