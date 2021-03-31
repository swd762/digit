<template>
  <div class="table">
    <DiagnosesList />
  </div>
</template>

<script>
import DiagnosesList from "./DiagnosesList";

export default {
  name: "Diagnoses",
  components: {
    DiagnosesList,
  },
};
</script>

<style scoped>
.table {
  display: flex;
  justify-content: center;
}
</style>
