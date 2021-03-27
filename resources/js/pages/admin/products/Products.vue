<template>
    <div class="table">
        <productsList></productsList>
    </div>
</template>

<script>
import productsList from "../../../components/ProductsList.vue";

export default {
    name: "Products",
    components: {
        productsList
    }
}
</script>

<style scoped>
.table {
    display: flex;
    justify-content: center;
}
</style>
