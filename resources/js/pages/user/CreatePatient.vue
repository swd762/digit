<template>
    <div style="max-width: 400px">
        <Form
            ref="formValidate"
            :model="patient_data"
            :rules="ruleValidate"
            label-position="top"
            :disabled="loading"
        >
            <FormItem label="Имя" prop="first_name">
                <Input v-model="patient_data.first_name" placeholder="Введите имя"/>
            </FormItem>
            <FormItem label="Фамилия" prop="last_name">
                <Input v-model="patient_data.last_name" placeholder="Введите фамилию"/>
            </FormItem>
            <FormItem label="Отчество" prop="middle_name">
                <Input v-model="patient_data.middle_name" placeholder="Введите отчество"/>
            </FormItem>
            <FormItem label="Дата рождения">
                <Row>
                    <Col span="11">
                        <DatePicker type="date" placeholder="Выберите дату" v-model="patient_data.date"></DatePicker>
                    </Col>
                </Row>
            </FormItem>

            <FormItem>
                <Button type="primary" @click="createUser">Создать</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "CreatePatient",
    data() {
        return {
            loading: false,
            // данные для валидации формы
            ruleValidate: {
                first_name: [
                    {
                        required: true,
                        message: "Необходимо ввести имя",
                        trigger: "blur",
                    },
                ],
                last_name: [
                    {
                        required: true,
                        message: "Необходимо ввести фамилию",
                        trigger: "blur",
                    },
                ],
                middle_name: [
                    {
                        required: true,
                        message: "Необходимо ввести отчество",
                        trigger: "blur",
                    },
                ],
                name: [
                    {
                        required: true,
                        message: "Необходимо ввести логин",
                        trigger: "blur",
                    },
                ],
                password: [
                    {
                        required: true,
                        message: "Необходимо ввести пароль",
                        trigger: "blur",
                    },
                ],
                password_confirmation: [
                    {
                        required: true,
                        message: "Необходимо ввести пароль",
                        trigger: "blur",
                    },
                    {
                        validator(rule, value, callback) {
                            return value === this.user_data.password;
                        },
                        message: "Пароли должны совпадать",
                    },
                ],
                email: [
                    {
                        required: true,
                        message: "Необходимо ввести email",
                        trigger: "blur",
                    },
                ],
            },
            errors: {},
            patient_data: {
                first_name: "",
                last_name: "",
                name: "",
                password: "",
                password_confirmation: "",
                email: "",

                middle_name: "",
                role: "user",
            },
        };
    },
    mounted() {
    },
    methods: {
        // метод создания нового пользователя
        createUser() {
            this.errors = {};
            this.$refs["formValidate"].validate((valid) => {
                if (valid) {
                    this.loading = true;
                    this.$http({
                        url: "auth/register",
                        method: "POST",
                        data: this.user_data,
                    })
                        .then((res) => {
                            this.loading = false;
                            this.$router.push({
                                name: "admin.dashboard",
                            });
                        })
                        .catch((err) => {
                            if (err.response) {
                                if (err.response.status == 422) {
                                    this.errors = err.response.data.errors;
                                }
                            }
                            this.loading = false;
                        });
                }
            });
        },
    },
};

</script>

<style scoped>

</style>
