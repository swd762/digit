<!--шаблон компонента- формы для создания устройства сбора и передачи данных (УСПД)-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="diagnos_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Название" prop="name">
        <Input v-model="diagnos_data.name" placeholder="Введите название" />
      </FormItem>
      <FormItem label="Код диагноза" prop="code">
        <Input v-model="diagnos_data.code" placeholder="Введите код" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="createDiagnose">Создать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "CreateDiagnose",
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
        code: [
          {
            required: true,
            message: "Необходимо ввести код",
            trigger: "blur",
          },
        ],
      },
      // Ошибки при сохранении
      errors: {},
      //Данные УСПД
      diagnos_data: {
        name: "",
        code: "",
      },
    };
  },
  mounted() {},
  methods: {
    // метод создания нового УСПД
    createDiagnose() {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          this.loading = true;
          this.$http({
            url: "diagnoses/create",
            method: "POST",
            data: this.diagnos_data,
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("Диагноз успешно создан");
              this.$router.push({
                name: "admin.dashboard.diagnoses",
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
