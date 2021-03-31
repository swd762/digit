<!--Компонент Список устройств сбора и передачи данных -->
<template>
  <div class="list-table">
    <h3>Список диагнозов</h3>
    <Table border :columns="table_columns" :data="diagnoses.data" :loading="loading">
      <template slot-scope="{ row }" slot="title">
        <strong>{{ row.title }}</strong>
      </template>
      <template slot-scope="{ row }" slot="diagnos_code">
        <strong>{{ row.code }}</strong>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="edit(row)"> Редактировать </Button>
        <Button type="error" size="small" @click="del(row)"> Удалить </Button>
      </template>
    </Table>
    <div style="margin: 10px; overflow: hidden">
      <div style="float: right">
        <Page
          :total="this.diagnoses.total"
          :page-size="diagnoses.per_page"
          :current="1"
          @on-change="
            (val) => {
              getDiagnoses(val);
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
            name: 'user.dashboard.diagnoses.create',
          })
        "
      >
        Добавить диагноз
      </Button>
    </div>
  </div>
</template>

<script>
export default {
  name: "DiagnosesList",
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
          title: "Диагноз",
          slot: "title",
          key: "title",
          width: 250,
          align: "center",
        },
        {
          title: "Код диагноза",
          slot: "diagnos_code",
          key: "code",
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
      diagnoses: [],
    };
  },
  created() {
    // запрос с сервера списка устройствах сбора и передачи данных после загрузки страницы
    this.getDiagnoses();
  },
  methods: {
    // метод получения списка диагнозов через api
    getDiagnoses(page = 1) {
      this.loading = true;
      this.$http({
        url: `diagnoses` + "?page=" + page,
        method: "GET",
      }).then(
        (res) => {
          this.loading = false;
          this.diagnoses = res.data;
        },
        () => {
          this.loading = false;
          this.$Message.error("Ошибка получения списка диагнозов");
          this.has_error = true;
        }
      );
    },
    // метод роутера на страницу редактирования
    edit(row) {
      this.$router.push({
        name: "user.dashboard.diagnoses.edit",
        params: { diagnosId: row.id },
      });
    },
    // метод удаления
    del(row) {
      let id = row.id;
      this.$Modal.confirm({
        title: "Подтверждение удаления диагноза",
        content: "<p>Вы уверены</p>",
        okText: "Да",
        cancelText: "Нет",
        onOk: () => {
          this.loading = true;
          this.$http({
            url: "diagnoses/" + id + "/remove",
            method: "delete",
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("диагноз удален");
              this.getDiagnoses();
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
