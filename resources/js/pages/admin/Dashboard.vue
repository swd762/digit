<template>
    <div class="card">
        <h2>Панель Администратора</h2>
        <div class="table">
            <usersList></usersList>
        </div>

    </div>
</template>

<script>
import usersList from '../../components/Users-list.vue'
export default {
    name: "Dashboard",
    components: {
        usersList
    }
}
</script>

<style>
.card {
    display: flex;
    flex-direction: column;
    /*justify-content: center;*/
    align-items: center;

}
.card h2 {
    margin: 10px;
    align-self: left;
}

.table {
    display: flex;
}

</style>
