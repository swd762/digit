<!--Шаблон панели управления пользователя-->
<style scoped>
.card {
    padding: 0 0 20px 0;
}
</style>
<template>
    <div class="card card-default">
        <h2>Панель управления Врача</h2>
        <h4>
            <strong>Врач:</strong>
            {{this.$auth.user().first_name}}
        </h4>
        <!--    <p>Здесь будут ссылки на пациентов, данные модулей и прочее.</p>-->
        <div class="table">
            <patientsList></patientsList>
        </div>
    </div>
</template>

<script>
import patientsList from "../../components/PatientsList.vue";

export default {
    name: "User.Dashboard",
    components: {
        patientsList,
    },
    data() {
        return {
        };
    },

};
</script>
