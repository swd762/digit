<!--Компонент Список устройств сбора и передачи данных -->
<template>
  <div class="list-table">
    <h3>Список устройств сбора и передачи данных (УСПД)</h3>
    <Table border :columns="table_columns" :data="modules_data.data" :loading="loading">
      <template slot-scope="{ row }" slot="name">
        <strong>{{ row.name }}</strong>
      </template>
      <template slot-scope="{ row }" slot="module_id">
        <strong>{{ row.module_id }}</strong>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="edit(row)"> Редактировать </Button>
        <Button type="error" size="small" @click="del(row)"> Удалить </Button>
      </template>
    </Table>
    <div style="margin: 10px; overflow: hidden">
      <div style="float: right">
        <Page
          :total="this.modules_data.total"
          :page-size="modules_data.per_page"
          :current="1"
          @on-change="
            (val) => {
              getModules(val);
            }
          "
        ></Page>
      </div>
    </div>
    <div class="buttons-cont">
      <Button
        type="primary"
        @click="
          $router.push({
            name: 'admin.dashboard.modules.create',
          })
        "
      >
        Добавить УСПД
      </Button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ModulesList",
  data() {
    return {
      //Статус загрузки
      loading: false,
      //Наличие ошибок
      has_error: false,
      //Параметры отображения таблицы со списком
      table_columns: [
        {
          title: "ID",
          key: "id",
          width: 100,
          align: "center",
        },
        {
          title: "Название УСПД",
          slot: "name",
          key: "name",
          width: 250,
          align: "center",
        },
        {
          title: "ID УСПД",
          slot: "module_id",
          key: "module_id",
          width: 250,
          align: "center",
        },
        {
          title: " ",
          slot: "action",
          width: 250,
          align: "center",
        },
      ],
      modules_data: [],
    };
  },
  created() {
    // запрос с сервера списка устройствах сбора и передачи данных после загрузки страницы
    this.getModules();
  },
  methods: {
    // метод получения списка устройствах сбора и передачи данных сервера через api
    getModules(page = 1) {
      this.loading = true;
      this.$http({
        url: `modules` + "?page=" + page,
        method: "GET",
      }).then(
        (res) => {
          this.loading = false;
          this.modules_data = res.data;
        },
        () => {
          this.loading = false;
          this.$Message.error("Ошибка получения списка УСПД");
          this.has_error = true;
        }
      );
    },
    // метод роутера на страницу редактирования
    edit(row) {
      this.$router.push({
        name: "admin.dashboard.modules.edit",
        params: { moduleId: row.id },
      });
    },
    // метод удаления
    del(row) {
      let id = row.id;
      this.$Modal.confirm({
        title: "Подтверждение удаления УСПД",
        content: "<p>Вы уверены</p>",
        okText: "Да",
        cancelText: "Нет",
        onOk: () => {
          this.loading = true;
          this.$http({
            url: "modules/" + id + "/remove_module",
            method: "delete",
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("УСПД удалено");
              this.getModules();
            })
            .catch((e) => {
              this.loading = false;
              this.$Message.error("Ошибка при удалении");
              console.log(e);
            });
        },
        onCancel: () => {
          // this.$Message.info('Clicked cancel');
        },
      });
    },
  },
};
</script>

<style scoped>
.buttons-cont {
  margin: 15px;
}

.list-table {
  display: block;
}

.list-table h3 {
  text-align: center;
  margin: 10px 0;
}
</style>
