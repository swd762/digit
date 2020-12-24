<!--Шаблон домашней страницы-->
<style scoped>
.card {
  text-align: center;
  padding: 0 0 20px 0;
}
.card h2 {
  margin-bottom: 10px;
}
</style>
<template>
  <div class="card card-default">
    <h2>Домашняя страница</h2>
    <p v-if="!$auth.check()">
      Стартовая страница для неавторизованного пользователя
    </p>
    <p v-else>Стартовая страница для авторизованного пользователя</p>
  </div>
</template>

<script>
</script>



