<template>
    <div class="card card-default">
<!--        <div class="card-header">Dashboard</div>-->

<!--        Admin.dash-->

        <usersList></usersList>
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

<style scoped>
.card {
    display: flex;
    justify-content: center;
}

</style>
