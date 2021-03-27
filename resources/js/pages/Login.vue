<!--Шаблон формы авторизации-->
<template>
  <div style="padding: 20px; display: flex; justify-content: center">
    <Card :bordered="false" style="width: 375px">
      <p slot="title" style="text-align: center">Авторизация</p>
      <Form
        ref="loginForm"
        :model="loginForm"
        :rules="ruleInline"
        inline
        class="login-form"
        autocomplete="off"
        :disabled="loading"
      >
        <FormItem prop="user">
          <Input type="text" v-model="loginForm.user" placeholder="Логин">
            <Icon type="ios-person-outline" slot="prepend"></Icon>
          </Input>
        </FormItem>
        <FormItem prop="password">
          <Input
            type="password"
            v-model="loginForm.password"
            placeholder="Пароль"
            @on-enter="login()"
          >
            <Icon type="ios-lock-outline" slot="prepend"></Icon>
          </Input>
        </FormItem>
        <FormItem>
          <Button type="primary" @click="login()">Войти</Button>
        </FormItem>
      </Form>
    </Card>
  </div>
</template>
<style>
.login-form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
<script>
export default {
  data() {
    return {
      loading: false,
      // переменные шаблона используемые формой
      loginForm: {
        user: "",
        password: "",
      },
      ruleInline: {
        user: [
          {
            required: true,
            message: "Пожалуйста, введите логин",
            trigger: "blur",
          },
        ],
        password: [
          {
            required: true,
            message: "Пожалуйста, введите пароль.",
            trigger: "blur",
          },
          {
            type: "string",
            min: 6,
            message: "Длина пароля не меньше 6 символов",
            trigger: "blur",
          },
        ],
      },
    };
  },
  methods: {
    // метод отправки данных автоиризации и последующей маршрутизации
    login() {
      let app = this;

      // делаем валидацию формы логин пароль
      this.$refs["loginForm"].validate((valid) => {
        if (valid) {
          app.loading = true;

          let redirect = this.$auth.redirect();
          this.$auth
            .login({
              data: {
                // данные с формы для отправки
                name: app.loginForm.user,
                password: app.loginForm.password,
              },
              rememberMe: true,
              // никуда не переходить в случаях неуспешной аутентификации
              redirect: null,
              // разрешить читать пользователя
              fetchUser: true,
            })
            .then(() => {
              // после успешной аутентификации проводим авторизацию и в зависимости от роли кидаем каждого в свою панель
              const redirectTo = redirect
                ? redirect.from.name
                : this.$auth.user().role === "admin"
                ? "admin.dashboard"
                : "user.dashboard";
              this.$Message.success("Успешная авторизация");
              app.loading = false;
              this.$router.push({ name: redirectTo });
            })
            .catch((e) => {
              app.loading = false;
              this.$Message.error("Ошибка авторизации!");
            });
        }
      });
    },
    //    ****
  },
};
// TODO: сделать валидацию данных в форме на лету без запроса к серверу
//
</script>
