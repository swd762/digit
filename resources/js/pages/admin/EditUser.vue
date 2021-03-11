<!--Шаблон страницы редактирования пользователя-->
<template>
  <div style="max-width: 400px">
    <Form
      ref="formValidate"
      :model="user_data"
      :rules="ruleValidate"
      label-position="top"
      :disabled="loading"
    >
      <FormItem label="Логин" prop="name">
        <Input v-model="user_data.name" placeholder="Введите логин" disabled />
      </FormItem>
      <FormItem label="E-mail" prop="email">
        <Input v-model="user_data.email" placeholder="Ввведите email" />
      </FormItem>
      <FormItem label="Имя" prop="first_name">
        <Input v-model="user_data.first_name" placeholder="Введите имя" />
      </FormItem>
      <FormItem label="Фамилия" prop="last_name">
        <Input v-model="user_data.last_name" placeholder="Введите фамилию" />
      </FormItem>
      <FormItem label="Отчество" prop="middle_name">
        <Input v-model="user_data.middle_name" placeholder="Введите отчество" />
      </FormItem>
      <FormItem label="Роль">
        <Select v-model="user_data.role">
          <Option value="user">Врач</Option>
          <Option value="admin">Администратор</Option>
        </Select>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="updateUser">Сохранить</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "EditUser",
  props: ["id"],
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
        email: [
          {
            required: true,
            message: "Необходимо ввести email",
            trigger: "change",
          },
          {
            type: "email",
            message: "Введен некорректный email",
            trigger: "blur",
          },
        ],
      },
      errors: {},
      // данные для валидации формы
      user_data: {},
      userId: null,
    };
  },
  created() {
    // когда страница загрузилась проверяем наличие параметра id, если нет возвращаемся в панель
    // если есть, то запрашиваем пользователя для редактирования
    this.userId = this.$route.params.userId;
    if (this.userId == null) {
      this.$router.push({
        name: "admin.dashboard",
      });
    } else {
      this.getUserData(this.userId);
    }
  },
  methods: {
    // Метод чтения нужного пользователя из БД
    getUserData(id) {
      this.loading = true;
      // читаем выбранного пользователя get запросом с параметром id
      this.$http
        .get(
          "users/" + id
          //{params: id}
        )
        .then((res) => {
          // получаем от сервера массив и записываем его в наш user_data
          this.user_data = res.data.user;
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
          this.$Message.error("Ошибка чтения пользователя");
          this.$router.push({
            name: "admin.dashboard",
          });
        });
    },
    // метод обновления данных пользовтеля в БД
    updateUser() {
      this.errors = {};
      this.$refs["formValidate"].validate((valid) => {
        if (valid) {
          this.loading = true;
          // отправляем массив с данными пользователя на сервер для последующей записи в БД
          this.$http({
            url: "users/" + this.userId + "/update_user",
            method: "put",
            data: this.user_data,
          })
            .then((res) => {
              // получаем ответ и обновлем данные формы для данного пользователя
              this.loading = false;
              this.$Message.success("Пользователь успешно обновлен");
              this.$router.push({
                name: "admin.dashboard",
              });
            })
            .catch((err) => {
              this.loading = false;
              this.$Message.error("Ошибка сохранения пользователя");
              if (err.response) {
                if (err.response.status == 422) {
                  this.errors = err.response.data.errors;
                }
              }
            });
        }
      });
    },
  },
};
</script>

<style scoped>
</style>
