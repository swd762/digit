<!--компонент просмотра данных, полученных с УСПД-->
<template>
  <div>
    <Form ref="formFilter" :model="filterData" :disabled="isLoading" :rules="ruleValidate" :inline="true">
      <FormItem label="id УСПД" prop="moduleId">
        <Select :filterable="true" v-model="filterData.moduleId">
          <Option v-for="(module, index) in modules" :key="index" :value="module.id.toString()">{{ module.name }}</Option>
        </Select>
      </FormItem>
      <FormItem label="С" prop="dateFrom" style="width: 200px">
        <DatePicker format="dd-MM-yyyy HH:mm:ss" type="datetime" @on-change="(val) => (filterData.dateFrom = val)" placeholder="С" />
      </FormItem>
      <FormItem label="По" prop="dateTo" style="width: 200px">
        <DatePicker format="dd-MM-yyyy HH:mm:ss" type="datetime" @on-change="(val) => (filterData.dateTo = val)" placeholder="По" />
      </FormItem>
      <div style="display: flex; justify-content: start; margin-bottom: 10px">
        <Button type="primary" @click="getData">Показать</Button>
      </div>
    </Form>
    <div v-if="periods.length">
      <div>Доступны данные за периоды:</div>
      <div v-for="(period, index) in periods" :key="index">{{ period }}</div>
    </div>
    <div style="max-height: 400px; overflow: auto">
      <Table border :columns="table_columns" :data="data" :loading="isLoading">
        <template slot-scope="{ row }" slot="temperature">
          <strong>{{ row.temperature / 10 }}</strong>
        </template>
        <template slot-scope="{ row }" slot="date">
          <strong>{{ mtz(row.created_at, "DD-MM-YYYY HH:mm:ss") }}</strong>
        </template>
      </Table>
    </div>
  </div>
</template>

<script>
import dateFormat from "../mixins/dateFormat";

export default {
  name: "ModuleDataBrowser",
  data() {
    return {
      //Статус загрузки
      isLoading: false,
      //Наличие ошибок
      has_error: false,

      // Список УСПД
      modules: [],
      data: [],

      periods: [],

      filterData: {
        moduleId: null,
        dateFrom: null,
        dateTo: null,
      },

      // данные для валидации формы
      ruleValidate: {
        moduleId: [
          {
            required: true,
            message: "Выберите УСПД",
            trigger: "blur",
          },
        ],
      },

      table_columns: [
        {
          title: "Температура",
          slot: "temperature",
          key: "temperature",
          width: 170,
          align: "center",
        },
        {
          title: "Изгиб",
          key: "bend",
          width: 100,
          align: "center",
        },
        {
          title: "Дата",
          slot: "date",
          key: "created_at",
          align: "center",
        },
      ],
    };
  },
  mixins: [dateFormat],
  props: {},
  created() {
    this.getModules();
  },
  watch: {
    "filterData.moduleId": function (newVal) {
      this.periods = [];
      this.getPeriods();
    },
  },
  methods: {
    //Получаем список доступных УСПД
    getModules() {
      this.$http({
        url: "modules?all=1",
        method: "GET",
      })
        .then((res) => {
          this.modules = res.data;
        })
        .catch((err) => {
          this.has_error = true;
        });
    },
    // Получение данных по выбранным параметрам
    getData() {
      this.$refs["formFilter"].validate((valid) => {
        if (valid) {
          this.isLoading = true;
          this.$http
            .post("get_data", this.filterData)
            .then((res) => {
              this.data = res.data;
              this.has_error = false;
              this.isLoading = false;
            })
            .catch((err) => {
              this.has_error = true;
              this.isLoading = false;
            });
        }
      });
    },
    // Получение периодов наличия данных
    getPeriods() {
      this.isLoading = true;
      this.$http
        .post("get_periods", {
          moduleId: this.filterData.moduleId,
        })
        .then((res) => {
          this.periods = res.data;
          this.has_error = false;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },
  },
};
</script>


