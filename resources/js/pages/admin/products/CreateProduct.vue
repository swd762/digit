<!--шаблон формы для создания протезно-ортопедического изделия (ПОИ)-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="product_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Название ПОИ" prop="name">
        <Input v-model="product_data.name" placeholder="Введите название" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="createProduct">Создать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "CreateProduct",
  data() {
    return {
      //Статус загрузки
      loading: false,
      // данные для валидации формы
      ruleValidate: {
        name: [
          {
            required: true,
            message: "Необходимо ввести название",
            trigger: "blur",
          },
        ],
      },
      // Ошибки при сохранении
      errors: {},
      //Данные ПОИ
      product_data: {
        name: "",
      },
    };
  },
  mounted() {},
  methods: {
    // метод создания нового ПОИ
    createProduct() {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          this.loading = true;
          this.$http({
            url: "products/create_product",
            method: "POST",
            data: this.product_data,
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("ПОИ успешно создан");
              this.$router.push({
                name: "admin.dashboard.products",
              });
            })
            .catch((err) => {
              this.$Message.error("Ошибка при создании");
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
