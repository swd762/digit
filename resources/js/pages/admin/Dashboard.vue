<template>
    <div class="card card-default">
        <div class="card-header">Dashboard</div>

        Admin.dash
        <userList></userList>
    </div>
</template>

<script>
import userList from '../../components/Users-list.vue'
export default {
    name: "Dashboard",
    components: {
        userList
    }
}
</script>

<style scoped>

</style>
