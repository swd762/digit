<template>
    <div>
        <h3>Liste de utilisateurs</h3>

        <Table  border :columns="columns12" :data="data6">
<!--            <template slot-scope="{ row }" slot="ID">-->
<!--                <strong>{{ row.name }}</strong>-->
<!--            </template>-->
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="show(index)">View</Button>
                <Button type="error" size="small" @click="remove(index)">Delete</Button>
            </template>
        </Table>
    </div>
</template>

<script>
export default {
name: "Users-list",
    data() {
        return {
            has_error: false,
            users: null,
            columns12: [
                {
                    title: 'ID',
                    key: 'id',
                    width:50
                },
                {
                    title: 'Login',
                    key: 'name',
                    width: 150
                },
                {
                    title: 'E-mail',
                    key: 'email'
                },
                {
                    title: 'Action',
                    slot: 'action',
                    width: 150,
                    align: 'center'
                }
            ],
            data6: [
                // {
                //     name: 'John Brown',
                //     age: 18,
                //     address: 'New York No. 1 Lake Park'
                // },
                // {
                //     name: 'Jim Green',
                //     age: 24,
                //     address: 'London No. 1 Lake Park'
                // },
                // {
                //     name: 'Joe Black',
                //     age: 30,
                //     address: 'Sydney No. 1 Lake Park'
                // },
                // {
                //     name: 'Jon Snow',
                //     age: 26,
                //     address: 'Ottawa No. 2 Lake Park'
                // }
            ]
        }
    },
    mounted() {
        this.getUsers()
    },
    methods: {
        getUsers() {
            this.$http({
                url: `users`,
                method: 'GET'
            })
                .then((res) => {
                    this.data6 = res.data.users
                    console.log(this.data6)
                }, () => {
                    this.has_error = true
                })

        }

    }
}
</script>

<style scoped>

</style>
