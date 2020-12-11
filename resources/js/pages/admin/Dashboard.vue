<!--Шаблон панели администратора-->
<template>
    <div class="card">
        <h2>Панель Администратора</h2>
        <div class="table">
            <usersList></usersList>
        </div>
    </div>
</template>

<script>
// подгрузка компонента списка пользователей
import usersList from '../../components/Users-list.vue'
export default {
    name: "Dashboard",
    // регистрация компонента списка пользователей
    components: {
        usersList
    }
}
</script>
<style>
.card {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.card h2 {
    margin: 10px;
}

.table {
    display: flex;
}
</style>
