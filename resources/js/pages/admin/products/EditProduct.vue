<!--Шаблон страницы редактирования изделия-->
<template>
    <div style="max-width: 400px">
        <Form
            ref="formValidate"
            :model="product_data"
            :rules="ruleValidate"
            label-position="top"
            :disabled="loading"
        >
            <FormItem label="Название" prop="name">
                <Input v-model="product_data.name" placeholder="Название изделия"/>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="updateProduct(productId)">Сохранить</Button>
                <Button type="primary" @click="getProductData(productId)">Прочитать</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "EditProduct",
    props: ["id"],
    data() {
        return {
            loading: false,
            // данные для валидации формы
            ruleValidate: {
                name: [
                    {
                        required: true,
                        message: "Необходимо ввести название",
                        trigger: "blur",
                    },
                ]
            },
            errors: {},
            // данные для валидации формы
            product_data: {},
            productId: null,
        };
    },
    created() {
        // когда страница загрузилась проверяем наличие параметра id, если нет возвращаемся в панель
        // если есть, то запрашиваем продукт для редактирования
        this.productId = this.$route.params.productId;
        if (this.productId == null) {
            this.$router.push({
                name: "admin.dashboard.product",
            });
        } else {
            this.getProductData(this.productId);
        }
    },
    methods: {
        getProductData(id) {
            this.loading = true;
            console.log(id)
            // читаем продукт
            this.$http
                .get(
                    "products/" + id
                )
                .then((res) => {
                    // получаем от сервера массив и записываем его в product_data
                    this.product_data = res.data;
                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                    this.$Message.error("Ошибка чтения");
                    this.$router.push({
                        name: "admin.dashboard.product",
                    });
                });
        },
        updateProduct(id) {
            this.errors = {};
            this.$refs["formValidate"].validate((valid) => {
                if (valid) {
                    // записываем обновленную информацию о продукте
                    this.loading = true;
                    this.$http({
                            url: "products/" + id + "/update_product",
                            method: "put",
                            data: this.product_data
                        }
                    ).then((res) => {
                        this.loading = false;
                    }).catch(() => {
                        this.loading = false;
                        this.$Message.error("Ошибка чтения");
                        this.$router.push({
                            name: "admin.dashboard.products",
                        });
                    });
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
