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
                        <DatePicker  type="date" @on-change="(val) => patient_data.date = val" placeholder="Выберите дату" v-model="patient_data.date"></DatePicker>
                    </Col>
                </Row>
            </FormItem>

            <FormItem>
                <Button type="primary" @click="createPatient">Создать</Button>
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
                ]
            },
            errors: {},
            patient_data: {
                first_name: "",
                middle_name: "",
                last_name: "",
                date: ""
            },
        };
    },
    mounted() {
    },
    methods: {
        // метод создания нового пользователя
        createPatient() {
            this.errors = {};
            console.log(this.patient_data.date)

            this.$http({
                url:"patients/add",
                method:"POST",
                data:this.patient_data
            }).then((res) =>{

            }).catch((err)=>{
                console.log(err)
            })

            // this.$refs["formValidate"].validate((valid) => {
            //     if (valid) {
            //         this.loading = true;
            //         this.$http({
            //             url: "auth/register",
            //             method: "POST",
            //             data: this.user_data,
            //         })
            //             .then((res) => {
            //                 this.loading = false;
            //                 this.$router.push({
            //                     name: "admin.dashboard",
            //                 });
            //             })
            //             .catch((err) => {
            //                 if (err.response) {
            //                     if (err.response.status == 422) {
            //                         this.errors = err.response.data.errors;
            //                     }
            //                 }
            //                 this.loading = false;
            //             });
            //     }
            // });
        },
    },
};

</script>

<style scoped>

</style>
