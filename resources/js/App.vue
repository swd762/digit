<template>
    <div id="app">
        <div class="heading">
            <h1>Cruds</h1>
        </div>


    </div>
</template>

<script>
export default {
    data() {
        return {
            test: []
        }
    },
    methods: {
        async read() {
            const { data } = await window.axios.get('api/dashboard');
            data.forEach();
        }
    },
    created() {
        this.read();
    }
}
</script>

<style scoped>

</style>
