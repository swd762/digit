<!--шаблон компонента- формы для создания пользователя-->
<template>
  <div style="max-width: 400px">
    <Form ref="formValidate" :model="user_data" :rules="ruleValidate" label-position="top" :disabled="loading">
      <FormItem label="Логин" prop="name">
        <Input v-model="user_data.name" placeholder="Введите логин" />
      </FormItem>
      <FormItem label="Пароль" prop="password">
        <Input type="password" v-model="user_data.password" placeholder="Ввведите пароль" />
        <div style="line-height: 14px; color: #ed4014" v-for="(error, i) in errors.password" :key="i" v-show="errors.password">
          {{ error }}
        </div>
      </FormItem>
      <FormItem label="Подтверждение пароля" prop="password_confirmation">
        <Input type="password" v-model="user_data.password_confirmation" placeholder="Ввведите пароль" />
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
        <Button type="primary" @click="createUser">Создать</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
export default {
  name: "CreateUser",
  data() {
    return {
      //Статус загрузки
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
      // Ошибки при сохранении
      errors: {},
      //Данные пользователя
      user_data: {
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
  mounted() {},
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
              this.$Message.success("Пользователь успешно создан");
              this.$router.push({
                name: "admin.dashboard",
              });
            })
            .catch((err) => {
              this.$Message.error("Ошибка при создании пользователя");
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
