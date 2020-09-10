<template>
    <div style="max-width: 400px">
        <p>вы будете редактипровать юзера с id = {{ id }} </p>
        <Form ref="formValidate" :model="formValidate"  :label-width="80">
            <FormItem label="Name" prop="name">
                <Input v-model="user_data.name" placeholder="Enter your name"></Input>
            </FormItem>
            <FormItem label="E-mail" prop="mail">
                <Input v-model="user_data.email" placeholder="Enter your e-mail"></Input>
            </FormItem>

            <!--        <FormItem>-->
            <!--            <Button type="primary" @click="handleSubmit('formValidate')">Submit</Button>-->
            <!--            <Button @click="handleReset('formValidate')" style="margin-left: 8px">Reset</Button>-->
            <!--        </FormItem>-->
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
            user_data: [],

        }
    },
    mounted() {
         console.log(this.id)
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
                            console.log(user)
                            console.log(key)
                            console.log(user.id)
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
        }
    }

}


</script>

<style scoped>

</style>
