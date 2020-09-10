<template>
    <div>
        <h3>Список пользователей</h3>

        <Table  border :columns="table_columns" :data="users_data">
<!--            <template slot-scope="{ row }" slot="ID">-->
<!--                <strong>{{ row.name }}</strong>-->
<!--            </template>-->
            <template slot-scope="{ row, index }" slot="action">
                <Button type="primary" size="small" style="margin-right: 5px" @click="editUser(row)">Edit
                </Button>
                <Button type="error" size="small" @click="remove(index)">Delete</Button>
            </template>
        </Table>
<!--        <router-view></router-view>-->
    </div>
</template>

<script>
export default {
name: "Users-list",
    data() {
        return {
            has_error: false,
            users: null,
            table_columns: [
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
            users_data: [
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
                    this.users_data = res.data.users
                    // console.log(this.users_data)
                }, () => {
                    this.has_error = true
                })

        },
        editUser(row) {
            let id = row.id
            // console.log(id)
            this.$router.push({
                name:'admin.edit_user',
                params: {id: row.id}

            })
        }

    }
}
</script>

<style scoped>

</style>
