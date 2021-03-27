<template>
    <div class="table">
        <ModulesList></ModulesList>
    </div>
</template>

<script>
import ModulesList from "../../../components/ModulesList";

export default {
    name: "Modules",
    components: {
        ModulesList,

    }
}
</script>

<style scoped>
.table {
    display: flex;
    justify-content: center;
}
</style>
