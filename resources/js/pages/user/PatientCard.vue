<template>
  <div style="">
    <template v-if="isLoading">
      <Spin></Spin>
    </template>
    <template v-else>
      <Button type="primary" @click="returnBack">Назад</Button>
      <Button type="success" @click="showDiagnosSelectingWindow"
        >Добавить диагноз</Button
      >

      <Card :dis-hover="true" style="margin-top: 10px">
        <p slot="title">Данные пациента</p>
        <div class="patient-card-row">
          <h4>ФИО:</h4>
          <p>{{ patientData.name }}</p>
        </div>
        <div class="patient-card-row">
          <h4>Дата рождения:</h4>
          <p>{{ patientData.birth_date }}</p>
        </div>
      </Card>

      <Card :dis-hover="true" style="margin: 10px 0">
        <p slot="title">Диагнозы</p>

        <div v-for="(diagnos, index) in patientData.diagnoses" :key="index">
          {{ diagnos.title }}
          <Button
            size="small"
            shape="circle"
            icon="md-close"
            type="error"
            @click="removeDiagnos(diagnos.id)"
          />
          <div v-if="diagnos.pivot.product">
            <p style="margin-top: 10px">
              Выданное изделие: <strong>{{ diagnos.pivot.product.name }}</strong
              ><br />
              Дата выдачи: <strong>{{ diagnos.pivot.issue_date }}</strong>
            </p>
            <template
              v-if="
                diagnos.pivot.product.modules &&
                diagnos.pivot.product.modules.length
              "
            >
              <br />
              <p>Прикрепленные модули:</p>
              <ul>
                <li
                  v-for="(module, index) in diagnos.pivot.product.modules"
                  :key="index"
                >
                  {{ module.name }}
                </li>
              </ul>
              <br />
            </template>
            <p v-else>Прикрепленные модули отсутствуют</p>
          </div>
          <div v-else>
            Изделие по данному диагнозу не выдавалось
            <Button
              type="success"
              size="small"
              @click="
                selectedDiagnos = diagnos.id;
                productSelectingMode = true;
              "
              >Выдать</Button
            >
          </div>

          <Divider />
        </div>
      </Card>
    </template>

    <diagnos-selecting
      v-if="diagnosSelectingMode"
      :patient-id="patientId"
      :isShowing="diagnosSelectingMode"
      @finished="processResponse"
    />

    <Modal
      v-model="productSelectingMode"
      title="Выдача изделия"
      @on-ok="attachProduct"
    >
      <Form :disabled="isLoading">
        <FormItem label="Выберите изделие" prop="device">
          <Select :filterable="true" v-model="selectedProduct">
            <Option
              v-for="(product, index) in products"
              :key="index"
              :value="product.id"
              >{{ product.name }}</Option
            >
          </Select>
        </FormItem>
      </Form>
    </Modal>
  </div>
</template>

<script>
import DiagnosSelecting from "../../components/DiagnosSelecting.vue";

export default {
  name: "PatientCard",
  components: {
    DiagnosSelecting,
  },
  data() {
    return {
      isLoading: false,
      diagnosSelectingMode: false,
      productSelectingMode: false,

      patientId: this.$route.params.patientId,
      patientData: {},

      diagnoses: [],
      selectedDiagnos: null,
      selectedDiagnosComment: null,

      products: [],
      selectedProduct: null,
    };
  },
  mounted() {
    // когда страница загрузилась проверяем прилител ли параметр id, если нет возвращаемся в панель
    // если прилетел то читаем юзера для редактирования
    this.patientId = this.$route.params.patientId;
    if (this.patientId == null) {
      this.$router.push({
        name: "user.dashboard",
      });
    } else {
      this.getPatientData(this.patientId);
      this.getProducts();
    }
  },
  methods: {
    // Метод чтения нужного пользователя из БД
    getPatientData(id) {
      this.isLoading = true;
      // читаем выбранного пользователя get запросом с параметром id
      this.$http({
        url: "patients/" + id,
        method: "GET",
      })
        .then((res) => {
          this.patientData = res.data;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    getProducts() {
      this.isLoading = true;
      // Получаем список изделий
      this.$http({
        url: "products",
        method: "GET",
      })
        .then((res) => {
          this.products = res.data;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    // Отображает списко диагнозов для выбора
    showDiagnosSelectingWindow() {
      this.diagnosSelectingMode = true;
    },

    // Обработка ответа от компонента добавления диагноза
    processResponse(response) {
      this.diagnosSelectingMode = false;
      if (response) {
        this.getPatientData(this.patientData.id);
      }
    },

    // Прикрепляет продукт к диагнозу пациента
    attachProduct() {
      this.$http
        .post(
          "patients/" +
            this.patientData.id +
            "/diagnos/" +
            this.selectedDiagnos +
            "/attach_product",
          {
            productId: this.selectedProduct,
          }
        )
        .then((res) => {
          //todo show message
          this.isLoading = false;
          this.getPatientData(this.patientData.id);
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    removeDiagnos(diagnosId) {
      let self = this;
      this.isLoading = true;
      // Добавляем выбранный диагноз пациенту
      this.$http
        .delete("patients/" + self.patientData.id + "/detach_diagnos", {
          params: {
            diagnosId: diagnosId,
          },
        })
        .then((res) => {
          //todo show message
          this.isLoading = false;
          this.getPatientData(self.patientData.id);
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    returnBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.patient-card-row {
  display: flex;
  margin-bottom: 10px;
}
.patient-card-row h4 {
  width: 100px;
}
.patient-card-row p {
  display: flex;
  align-items: center;
}
</style>
