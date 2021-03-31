<!--Шаблон панели управления пользователя-->
<style scoped>
.card {
  padding: 0 0 20px 0;
}
</style>
<template>
  <div class="card card-default">
    <h2>Панель управления врача</h2>
    <h4>
      <strong>Врач:</strong>
      {{ this.$auth.user().first_name }}
    </h4>
    <div class="dashboard-group">
      <h3>Справочники</h3>
      <Button style="margin: 0 auto; display: block" type="primary" @click="$router.push('diagnoses')">Диагнозы</Button>
    </div>
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
    return {};
  },
};
</script>
