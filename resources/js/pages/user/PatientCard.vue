<template>
  <div style="">
    <template v-if="isLoading">
      <Spin></Spin>
    </template>
    <template v-else>
      <Button type="success" @click="selectDiagnos">Добавить диагноз</Button>

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
          <div>
            <p>
              Изделие: {{ diagnos.pivot.product.name }}<br />
              Дата выдачи: {{ diagnos.pivot.issue_date }}
            </p>
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
          </div>

          <Divider />
        </div>
      </Card>
    </template>

    <!-- <div class="patient-card-row" v-for="product in patients_data.products">
      <h4>Название изделия:</h4>
      <p>{{ product.name }}</p>
    </div>
    <div class="patient-card-row">
      <h4>ID модуля:</h4>
      <p>0000000000000000000001</p>
    </div>
    <div class="patient-card-row">
      <h4>Дата установки:</h4>
      <p>01.01.1999</p>
    </div> -->
  </div>
</template>

<script>
export default {
  name: "PatientCard",
  data() {
    return {
      isLoading: false,
      patientData: {},

      diagnoses: [],
      selectedDiagnos: null,
      selectedDiagnosComment: null,

      products: [
        {
          id: 1,
          title: "Изделие 1",
          modules: [
            {
              id: 1,
              title: "Модуль 1",
            },
            {
              id: 2,
              title: "Модуль 2",
            },
          ],
        },
        {
          id: 2,
          title: "Изделие 2",
          modules: [
            {
              id: 1,
              title: "Модуль 1",
            },
            {
              id: 2,
              title: "Модуль 2",
            },
          ],
        },
      ],
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
      this.getDiagnoses();
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

    //Получаем список диагнозов на случай назначения
    getDiagnoses() {
      this.$http({
        url: "diagnoses",
        method: "GET",
      })
        .then((res) => {
          this.diagnoses = res.data;
        })
        .catch((err) => {
          this.has_error = true;
        });
    },
    // Отображает списко диагнозов для выбора
    selectDiagnos() {
      let self = this;
      this.$Modal.confirm({
        title: "Добавление диагноза",
        onOk: () => {
          self.attachDiagnos();
        },
        onCancel: () => {
          self.clearSelectedDiagnos();
        },
        render: (h) => {
          return h("div", {}, [
            h(
              "Select",
              {
                props: {
                  size: "small",
                  filterable: true,
                },
                on: {
                  "on-change": (value) => {
                    self.selectedDiagnos = value;
                  },
                },
              },
              self.diagnoses.map((item) => {
                return h("Option", {
                  props: {
                    value: item.id,
                    label: item.title,
                  },
                });
              })
            ),
            h("p", {}, "Комментарий"),
            h("Input", {
              props: {
                type: "textarea",
                // autosize: "{minRows: 2,maxRows: 5}",
              },
              on: {
                input: (value) => {
                  self.selectedDiagnosComment = value;
                },
              },
            }),
          ]);
        },
      });
    },

    attachDiagnos() {
      let self = this;
      this.isLoading = true;
      // Добавляем выбранный диагноз пациенту
      this.$http
        .post("patients/" + self.patientData.id + "/attach_diagnos", {
          diagnosId: self.selectedDiagnos,
          diagnosComment: self.selectedDiagnosComment,
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

    clearSelectedDiagnos() {
      this.selectedDiagnos = null;
      this.selectedDiagnosComment = null;
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
