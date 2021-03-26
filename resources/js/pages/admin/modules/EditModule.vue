<!--Шаблон страницы редактирования модуля-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="module_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Название" prop="name">
        <Input v-model="module_data.name" placeholder="Название модуля" />
      </FormItem>
      <FormItem label="ID УСПД" prop="module_id">
        <Input v-model="module_data.module_id" placeholder="ID модуля" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="updateModule(moduleId)">Сохранить</Button>
        <Button type="primary" @click="getModuleData(moduleId)">Прочитать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "EditModule",
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
        ],
        module_id: [
          {
            required: true,
            message: "Необходимо ввести ID",
            trigger: "blur",
          },
        ],
      },
      errors: {},
      // данные для валидации формы
      module_data: {},
      moduleId: null,
    };
  },
  created() {
    // когда страница загрузилась проверяем наличие параметра id, если нет возвращаемся в панель
    // если есть, то запрашиваем продукт для редактирования
    this.moduleId = this.$route.params.moduleId;
    if (this.moduleId == null) {
      this.$router.push({
        name: "admin.dashboard.modules",
      });
    } else {
      this.getModuleData(this.moduleId);
    }
  },
  methods: {
    getModuleData(id) {
      this.loading = true;
      console.log(id);
      // читаем модуль
      this.$http
        .get("modules/" + id)
        .then((res) => {
          // получаем от сервера массив и записываем его в module_data
          this.module_data = res.data;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
          this.$Message.error("Ошибка чтения");
          this.$router.push({
            name: "admin.dashboard.modules",
          });
        });
    },
    updateModule(id) {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          // записываем обновленную информацию о модуле
          this.loading = true;
          this.$http({
            url: "modules/" + id + "/update_module",
            method: "put",
            data: this.module_data,
          })
            .then((res) => {
              this.loading = false;
              this.$router.go(-1);
            })
            .catch(() => {
              this.loading = false;
              this.$Message.error("Ошибка чтения");
              this.$router.push({
                name: "admin.dashboard.modules",
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
