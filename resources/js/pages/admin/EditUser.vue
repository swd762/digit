<template>
    <div style="max-width: 400px">
        <p>вы будете редактипровать юзера с id = {{ this.id }} </p>
        <Form ref="formValidate" :model="formValidate"  :label-width="80">
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин" disabled></Input>
            </FormItem>
            <FormItem label="E-mail" prop="mail">
                <Input v-model="user_data.email" placeholder="Ввведите email"></Input>
            </FormItem>
            <FormItem label="Имя" prop="first_name">
                <Input v-model="user_data.first_name" placeholder="Введите имя"></Input>
            </FormItem>
            <FormItem label="Фамилия" prop="last_name">
                <Input v-model="user_data.last_name" placeholder="Введите фамилию"></Input>
            </FormItem>
            <FormItem label="Отчество" prop="middle_name">
                <Input v-model="user_data.middle_name" placeholder="Введите отчество"></Input>
            </FormItem>
            <FormItem label="Роль" prop="role">
                <Input v-model="user_data.role" placeholder="Введите отчество"></Input>
            </FormItem>
            <FormItem label="Role" prop="role">
                <RadioGroup v-model="user_data.role">
                    <Radio label="user">user</Radio>
                    <Radio label="admin">admin</Radio>
                </RadioGroup>
            </FormItem>
                    <FormItem>
                        <Button type="primary" @click="updateUser" >Записать</Button>
                        <Button @click="getUserData" style="margin-left: 8px">Прочитать данные</Button>
                    </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "EditUser",
    props: ['id'],
    data() {
        return {
            formValidate: {
                name: '',
                mail: '',
                city: '',
                gender: '',
                interest: [],
                date: '',
                time: '',
                desc: ''
            },
            user_data: []

        }
    },
    mounted() {
         // console.log(this.id)
        if (this.id == null) {
            this.$router.push({
                name: 'admin'
            })
        } else {
            this.getUserData()
        }
    },
    methods: {
        getUserData() {
            //     let user_id = this.props.id
            //  console.log (this.id)
            // let user_id = this.id-1
                this.$http({
                    url: `users`,
                    method: 'GET'
                })
                    .then((res) => {
                        res.data.users.forEach((user, key)=>{
                            // console.log(user)
                            // console.log(key)
                            // console.log(user.id)
                            if(user.id == this.id) {
                                this.user_data = user
                                 console.log(this.user_data)
                            }
                        })

                        // this.user_data = res.data.users[user_id]
                        //   console.log(res.data.users)
                    }, () => {
                        this.has_error = true
                    })
        },
        updateUser() {
            this.$http({
                url: `update_user`,
                method: 'POST',
                data: this.user_data
            }).then((res)=>{
                console.log(res)
                this.getUserData()
            })
        },
        deleteUser(id) {

        }

    }

}


</script>

<style scoped>

</style>
