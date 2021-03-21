<!--шаблон компонента- формы для создания устройства сбора и передачи данных (УСПД)-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="module_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Название УСПД" prop="name">
        <Input v-model="module_data.name" placeholder="Введите название" />
      </FormItem>
      <FormItem label="ID УСПД" prop="module_id">
        <Input v-model="module_data.module_id" placeholder="Введите ID" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="createModule">Создать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "CreateModule",
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
        module_id: [
          {
            required: true,
            message: "Необходимо ввести ID",
            trigger: "blur",
          },
        ],
      },
      // Ошибки при сохранении
      errors: {},
      //Данные УСПД
      module_data: {
        name: "",
        module_id: "",
      },
    };
  },
  mounted() {},
  methods: {
    // метод создания нового УСПД
    createModule() {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          this.loading = true;
          this.$http({
            url: "modules/create_module",
            method: "POST",
            data: this.module_data,
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("УСПД успешно создан");
              this.$router.push({
                name: "admin.dashboard.modules",
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
