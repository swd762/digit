<!--Шаблон страницы редактирования модуля-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="diagnose_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Название" prop="title">
        <Input v-model="diagnose_data.title" placeholder="Название диагноза" />
      </FormItem>
      <FormItem label="Код" prop="code">
        <Input v-model="diagnose_data.code" placeholder="Код диагноза" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="updateDiagnose(diagnoseId)">Сохранить</Button>
        <Button type="primary" @click="getDiagnoseData(diagnoseId)">Прочитать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "EditDiagnose",
  props: ["id"],
  data() {
    return {
      loading: false,
      // данные для валидации формы
      ruleValidate: {
        title: [
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
      errors: {},
      // данные для валидации формы
      diagnose_data: {
        title: null,
        code: null,
      },
      diagnoseId: null,
    };
  },
  created() {
    // когда страница загрузилась проверяем наличие параметра id, если нет возвращаемся в панель
    // если есть, то запрашиваем продукт для редактирования
    this.diagnoseId = this.$route.params.diagnosId;
    if (this.diagnoseId == null) {
      this.$router.push({
        name: "admin.dashboard.diagnoses",
      });
    } else {
      this.getDiagnoseData(this.diagnoseId);
    }
  },
  methods: {
    getDiagnoseData(id) {
      this.loading = true;
      console.log(id);
      // читаем модуль
      this.$http
        .get("diagnoses/" + id)
        .then((res) => {
          // получаем от сервера массив и записываем его в diagnose_data
          this.diagnose_data.title = res.data.title;
          this.diagnose_data.code = res.data.code.toString();
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
          this.$Message.error("Ошибка чтения");
          this.$router.push({
            name: "admin.dashboard.diagnoses",
          });
        });
    },
    updateDiagnose(id) {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          // записываем обновленную информацию о модуле
          this.loading = true;
          this.$http({
            url: "diagnoses/" + id + "/update",
            method: "put",
            data: this.diagnose_data,
          })
            .then((res) => {
              this.loading = false;
              this.$router.go(-1);
            })
            .catch(() => {
              this.loading = false;
              this.$Message.error("Ошибка чтения");
              this.$router.push({
                name: "admin.dashboard.diagnoses",
              });
            });
        }
      });
    },
  },
};
</script>

<style scoped>
</style>
