<!--Шаблон панели администратора-->
<template>
    <div class="card">
        <h2>Панель Администратора</h2>
<!--        <p>-->
<!--            Здесь будут различные настройки системы, управление пользователями и-->
<!--            прочее-->
<!--        </p>-->
        <div class="dashboard-group">
            <h3>Справочники</h3>
            <Button type="primary" @click="$router.push('products')">Протезно-ортопедические изделия</Button>
            <Button type="primary">Устройств сбора и передачи данных (УСПД)</Button>

        </div>
        <div class="table">
            <usersList></usersList>
        </div>
    </div>
</template>

<script>
// подгрузка компонента списка пользователей
import usersList from "../../components/Users-list.vue";

export default {
    name: "Dashboard",
    // регистрация компонента списка пользователей
    components: {
        usersList,
    },
};
</script>
<style>
.dashboard-group h3 {
    text-align: center;
    margin: 10px 0;
}

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
