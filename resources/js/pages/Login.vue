<template>


        <div style="background:#eee;padding: 20px;display: flex;justify-content: center">
            <Card :bordered="false" style="width: 375px">
                <p slot="title" style="text-align:center">Авторизация</p>
                <Form ref="formInline" :model="formInline" :rules="ruleInline" inline class="login-form">
                    <FormItem prop="user">
                        <Input type="text" v-model="formInline.user" placeholder="Логин">
                            <Icon type="ios-person-outline" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem prop="password">
                        <Input type="password" v-model="formInline.password" placeholder="Пароль">
                            <Icon type="ios-lock-outline" slot="prepend"></Icon>
                        </Input>
                    </FormItem>
                    <FormItem>
                        <Button type="primary" @click="handleSubmit('formInline')">Войти</Button>
                    </FormItem>
                </Form>
            </Card>
        </div>

</template>
<style>
.login-form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

</style>
<script>
export default {
    data () {
        return {
            formInline: {
                user: '',
                password: ''
            },
            ruleInline: {
                user: [
                    { required: true, message: 'Пожайлуста, введите логин.', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: 'Пожайлуста, введите пароль.', trigger: 'blur' },
                    { type: 'string', min: 6, message: 'Минимальная длина пароля - 6 символов', trigger: 'blur' }
                ]
            }
        }
    },
    methods: {
        handleSubmit(name) {
            this.$refs[name].validate((valid) => {
                if (valid) {
                    this.$Message.success('Успешно!');
                } else {
                    this.$Message.error('Ошибка!');
                }
            })
        }
    }
}
</script>
