<!--Компонент Список протезно-ортопедических изделий (ПОИ) -->
<template>
  <div class="list-table">
    <h3>Список протезно-ортопедических изделий (ПОИ)</h3>
    <Table border :columns="table_columns" :data="products_data.data" :loading="loading">
      <template slot-scope="{ row }" slot="name">
        <strong>{{ row.name }}</strong>
      </template>
      <template slot-scope="{ row, index }" slot="action">
        <Button type="primary" size="small" style="margin-right: 5px" @click="edit(row)"> Редактировать </Button>
        <Button type="error" size="small" @click="del(row)"> Удалить </Button>
      </template>
    </Table>
    <div style="margin: 10px; overflow: hidden">
      <div style="float: right">
        <Page
          :total="this.products_data.total"
          :page-size="products_data.per_page"
          :current="1"
          @on-change="
            (val) => {
              getProducts(val);
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
            name: 'admin.dashboard.product.create',
          })
        "
      >
        Добавить ПОИ
      </Button>
    </div>
  </div>
</template>

<script>
export default {
  name: "ProductsList",
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
          title: "Название ПОИ",
          slot: "name",
          key: "name",
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
      products_data: [],
    };
  },
  created() {
    // запрос с сервера списка ПОИ после загрузки страницы
    this.getProducts();
  },
  methods: {
    // метод получения списка ПОИ сервера через api
    getProducts(page = 1) {
      this.loading = true;
      this.$http({
        url: `products` + "?page=" + page,
        method: "GET",
      }).then(
        (res) => {
          this.loading = false;
          this.products_data = res.data;
        },
        () => {
          this.loading = false;
          this.$Message.error("Ошибка получения списка ПОИ");
          this.has_error = true;
        }
      );
    },
    // метод роутера на страницу редактирования ПОИ
    edit(row) {
      this.$router.push({
        name: "admin.dashboard.product.edit",
        params: { productId: row.id },
      });
    },
    // метод удаления ПОИ
    del(row) {
      let product = row.id;
      this.$Modal.confirm({
        title: "Подтверждение удаления ПОИ",
        content: "<p>Вы уверены</p>",
        okText: "Да",
        cancelText: "Нет",
        onOk: () => {
          this.loading = true;
          this.$http({
            url: "products/" + product + "/remove_product",
            method: "delete",
          })
            .then((res) => {
              this.loading = false;
              this.$Message.success("ПОИ удалено");
              this.getProducts();
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
