<!--Шаблон страницы карточки пациента-->
<template>
  <div style="">
    <template v-if="isLoading">
      <Spin></Spin>
    </template>
    <template v-else>
      <Button type="primary" @click="returnBack">Назад</Button>
      <Button type="success" @click="showDiagnosSelectingWindow"> Добавить диагноз </Button>
      <Button type="success" @click="openReception"> Добавить прием </Button>
      <Button type="success" @click="showModuleDataWindow">Просмотр данных с УСПД</Button>
      <Button type="success" @click="showLogs">История</Button>
      <Button type="success" @click="showAssessment">Оценка</Button>

      <Card :dis-hover="true" style="margin-top: 10px">
        <p slot="title">Данные пациента <Button icon="md-create" shape="circle" size="small" type="success" @click="showPatientEditingWindow" /></p>
        <div class="patient-card-row">
          <h4>ФИО:</h4>
          <p>{{ patientData.name }}</p>
        </div>
        <div class="patient-card-row">
          <h4>Дата рождения:</h4>
          <p>{{ mtz(patientData.birth_date, "DD-MM-YYYY") }}</p>
        </div>
      </Card>

      <Card :dis-hover="true" style="margin: 10px 0">
        <p slot="title" style="height: auto">
          Диагнозы
          <Button icon="md-add" shape="circle" size="small" type="success" @click="showDiagnosSelectingWindow"> </Button>
        </p>

        <div v-for="(diagnos, index) in patientData.diagnoses" :key="index">
          {{ diagnos.title }}
          <Button icon="md-close" shape="circle" size="small" type="error" @click="removeDiagnos(diagnos.id)" />
          <Button icon="md-create" shape="circle" size="small" type="success" @click="runEditingDiagnos(diagnos)" />
          <div v-if="diagnos.pivot.product">
            <div style="margin-top: 10px">
              <p style="display: inline-block">
                Выданное ПОИ:
                <strong>{{ diagnos.pivot.product.name }}</strong>
              </p>
              <Button icon="md-close" shape="circle" size="small" type="error" @click="detachProduct(diagnos.id)" />
              <p>
                Дата выдачи: <strong>{{ diagnos.pivot.issue_date }}</strong>
              </p>
            </div>
            <div style="margin-top: 10px" v-if="diagnos.pivot.module">
              <p>
                Прикрепленный УСПД:
                <strong>{{ diagnos.pivot.module.name }}</strong>
              </p>
              <p>
                ID УСПД:
                <strong>{{ diagnos.pivot.module.module_id }}</strong>
              </p>
            </div>
            <div v-else>УСПД не прикреплено</div>
          </div>
          <div v-else>
            ПОИ по данному диагнозу не выдавалось
            <Button
              size="small"
              type="success"
              @click="
                selectedDiagnos = diagnos.id;
                productSelectingMode = true;
              "
              >Выдать
            </Button>
          </div>

          <div>Комментарий: {{ diagnos.pivot.comment ? diagnos.pivot.comment : "Комментарий не указан" }}</div>

          <Divider />
        </div>
      </Card>

      <Card :dis-hover="true" style="margin: 10px 0">
        <p slot="title" style="height: auto">
          Приемы
          <Button icon="md-add" shape="circle" size="small" type="success" @click="openReception()"> </Button>
        </p>

        <div v-for="(reception, index) in patientData.receptions" :key="index">
          Прием
          {{ reception.id }}
          <Button icon="md-close" shape="circle" size="small" type="error" @click="removeReception(index)" />
          <Button icon="md-open" shape="circle" size="small" type="success" @click="openReception(index)" />
          <br />
          Дата приема:
          {{ mtz(reception.receipt_date, "DD-MM-YYYY") }}
          <br /><br />
          Комментарий: {{ reception.receipt_description }}
        </div>
      </Card>
    </template>

    <diagnos-selecting :isShowing="diagnosSelectingMode" :patient-id="patientId" @finished="processResponse" />

    <Modal v-model="productSelectingMode" title="Выдача ПОИ" @on-ok="attachProduct">
      <Form :disabled="isLoading">
        <FormItem label="Выберите ПОИ" prop="device">
          <Select v-model="selectedProduct" :filterable="true">
            <Option v-for="(product, index) in products" :key="index" :value="product.id">{{ product.name }} </Option>
          </Select>
        </FormItem>
        <FormItem label="Выберите УСПД" prop="device">
          <Select v-model="selectedModule" :filterable="true">
            <Option v-for="(module, index) in modules" :key="index" :value="module.id">{{ module.name }} </Option>
          </Select>
        </FormItem>
      </Form>
    </Modal>

    <Modal v-model="patientEditingFlag" title="Редактирование пациента" :footer-hide="true">
      <Form ref="patientValidate" :model="changedPatientData" :rules="patientRulesValidate" label-position="top" :disabled="isLoading">
        <FormItem label="ФИО пациента" prop="name">
          <Input v-model="changedPatientData.name" />
        </FormItem>
        <FormItem label="Дата рождения пациента" prop="birth_date">
          <DatePicker
            format="dd-MM-yyyy"
            type="date"
            @on-change="(val) => (changedPatientData.birth_date = val)"
            :value="changedPatientData.birth_date"
            placeholder="Выберите дату"
          />
        </FormItem>
      </Form>
      <Button type="success" @click="savePatient">Сохранить</Button>
    </Modal>

    <Modal v-model="receptionSelectingMode" title="Прием врача" @on-ok="attachReception()" @on-cancel="resetReceptionData()">
      <p><strong>Пациент: </strong>{{ patientData.name }}</p>
      <p><strong>Дата приема: </strong>{{ mtz(receptionData.date, "DD-MM-YYYY") }}</p>
      <Form :disabled="isLoading">
        <FormItem label="Комментарий к осмотру врача">
          <Input v-model="receptionData.comment" type="textarea" :autosize="{ minRows: 3, maxRows: 10 }" />
        </FormItem>
        <Alert type="success" show-icon v-if="isModuleRead">{{ moduleStatusMessage }}</Alert>
        <Button type="success" @click="getModuleStatus"> Прочитать данные с УСПД </Button>
      </Form>
    </Modal>

    <Modal v-model="moduleDataMode" title="Просмотр данных с УСПД" cancelText="" okText="Закрыть">
      <module-data-browser />
    </Modal>

    <Modal v-model="LogDataMode" title="История" cancelText="" okText="Закрыть" width="1000">
      <Table border :columns="logColumns" :data="logs" :loading="isLoading">
        <template slot-scope="{ row }" slot="diagnos">
          <div>{{ row.diagnos.title }}</div>
          <div>Поставлен: {{ row.issue_date }}</div>
          <div v-if="row.detach_date">Снят: {{ row.detach_date }}</div>
        </template>
        <template slot-scope="{ row }" slot="product">
          <div v-if="row.product_id">
            <div>{{ row.product.name }}</div>
            <div v-if="row.product_attach_date">Выдано: {{ row.product_attach_date }}</div>
            <div v-if="row.product_detach_date">Изъято: {{ row.product_detach_date }}</div>
          </div>
          <div v-else>ПОИ не выдано</div>
        </template>
        <template slot-scope="{ row }" slot="module">
          <div v-if="row.module_id">
            <div>{{ row.module.name }}</div>
          </div>
          <div v-else>УСПД не устанавливалось</div>
        </template>
      </Table>
    </Modal>

    <Modal v-model="assessmentMode" title="Оценка данных УСПД" cancelText="" okText="Закрыть" width="1000" @on-ok="clearAssessment">
      <Form ref="formAssessment" :model="assessmentFilter" :disabled="isLoading" :rules="assessmentRuleValidate" :inline="true">
        <FormItem label="id УСПД" prop="moduleId">
          <Select :filterable="true" v-model="assessmentFilter.moduleId">
            <Option v-for="(module, index) in modules" :key="index" :value="module.id.toString()">{{ module.name }}</Option>
          </Select>
        </FormItem>
        <FormItem label="С" prop="dateFrom" style="width: 120px">
          <DatePicker format="dd-MM-yyyy" type="date" @on-change="(val) => (assessmentFilter.dateFrom = val)" placeholder="С" />
        </FormItem>
        <FormItem label="По" prop="dateTo" style="width: 120px">
          <DatePicker format="dd-MM-yyyy" type="date" @on-change="(val) => (assessmentFilter.dateTo = val)" placeholder="По" />
        </FormItem>
        <div style="display: flex; justify-content: start; margin-bottom: 10px">
          <Button type="primary" @click="getAssessmentResult">Оценить</Button>
        </div>
      </Form>
      <div v-if="isLoading"><Spin></Spin>Оценка данных в нейронной сети...</div>
      <div v-if="assessmentResult">{{ assessmentResult }}</div>
    </Modal>

    <Modal v-model="diagnosEditingMode" title="Редактирование" :footer-hide="true">
      <Form ref="formEditing" :model="selectedDiagnosForEdit" :disabled="isLoading" :rules="diagnosEditingRuleValidate">
        <FormItem label="Комментарий к диагнозу" prop="comment">
          <Input v-model="selectedDiagnosForEdit.comment" type="textarea" :autosize="{ minRows: 2, maxRows: 5 }" />
        </FormItem>
        <FormItem label="Дата постановки диагноза" prop="issueDate">
          <DatePicker type="date" :value="selectedDiagnosForEdit.issueDate" @on-change="(val) => (selectedDiagnosForEdit.issueDate = val)" placeholder="Дата постановки диагноза" />
        </FormItem>
        <FormItem label="УСПД" prop="moduleId">
          <Select :filterable="true" v-model="selectedDiagnosForEdit.moduleId">
            <Option v-for="(module, index) in modules" :key="index" :value="module.id">{{ module.name }}</Option>
          </Select>
        </FormItem>
        <FormItem label="ПОИ" prop="productId">
          <Select :filterable="true" v-model="selectedDiagnosForEdit.productId">
            <Option v-for="(product, index) in products" :key="index" :value="product.id">{{ product.name }}</Option>
          </Select>
        </FormItem>
        <div style="display: flex; justify-content: start; margin-bottom: 10px">
          <Button type="error" @click="closeEditingDiagnos" style="margin-right: 5px">Закрыть</Button>
          <Button type="primary" @click="saveEditedDiagnos">Сохранить</Button>
        </div>
      </Form>
    </Modal>
  </div>
</template>

<script>
import DiagnosSelecting from "../../components/DiagnosSelecting.vue";
import ModuleDataBrowser from "../../components/ModuleDataBrowser.vue";
import dateFormat from "../../mixins/dateFormat";

export default {
  name: "PatientCard",
  components: {
    DiagnosSelecting,
    ModuleDataBrowser,
  },
  mixins: [dateFormat],
  data() {
    return {
      //Статус загрузки
      isLoading: false,
      //Статус проверки данных с УСПД
      isModuleRead: false,
      //Флаг режима выбора диагноза
      diagnosSelectingMode: false,
      //Флаг режима выбора ПОИ
      productSelectingMode: false,
      //Флаг режима работы с приемами
      receptionSelectingMode: false,
      //Флаг режима редактирования пациента
      patientEditingFlag: false,
      //Флаг режима просмотра данных с УСПД
      moduleDataMode: false,
      //Флаг режима просмотра истории
      LogDataMode: false,
      //Флаг режима оценки данных
      assessmentMode: false,
      //Флаг режима редактирования диагноза
      diagnosEditingMode: false,

      //id пациента
      patientId: this.$route.params.patientId,
      // данные пациента
      patientData: {},
      changedPatientData: {
        name: "",
        birth_date: "",
      },

      selectedDiagnosForEdit: {
        diagnosId: null,
        comment: null,
        issueDate: null,
        moduleId: null,
        productId: null,
      },

      // Период оценки
      assessmentFilter: {
        dateFrom: null,
        dateTo: null,
        moduleId: null,
      },
      // Результат оценки
      assessmentResult: null,

      diagnosEditingRuleValidate: {
        issueDate: [
          {
            required: true,
            message: "Выберите дату",
            trigger: "blur",
          },
        ],
      },

      patientRulesValidate: {
        name: [
          {
            required: true,
            message: "Необходимо ввести фио",
            trigger: "blur",
          },
        ],
        birth_date: [
          {
            required: true,
            message: "Необходимо ввести дату",
            trigger: "blur",
          },
        ],
      },
      // Правила валидации фильтра оценки
      assessmentRuleValidate: {
        dateFrom: [
          {
            required: true,
            message: "Выберите период",
            trigger: "blur",
          },
        ],
        dateTo: [
          {
            required: true,
            message: "Выберите период",
            trigger: "blur",
          },
        ],
      },
      //Описание таблицы для истории
      logColumns: [
        {
          title: "Диагноз",
          slot: "diagnos",
        },
        {
          title: "Выдано ПОИ",
          slot: "product",
        },
        {
          title: "Выдано УСПД",
          slot: "module",
        },
      ],
      //Записи истории
      logs: [],

      // Список диагнозов
      diagnoses: [],
      // Выбранный диагноз для добавления
      selectedDiagnos: null,
      // Комментарий к выбранному диагнозу
      selectedDiagnosComment: null,
      // Данные приема
      receptionData: {
        id: null,
        comment: null,
        date: null,
      },
      // Список приемов
      receptions: [],
      // Список доступных ПОИ
      products: [],
      // Список доступных УСПД
      modules: [],
      // Выбранное ПОИ
      selectedProduct: null,
      // Выбранное УСПД
      selectedModule: null,
      //Статус загрузки данных с УСПД
      moduleStatusMessage: null,
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
      this.getModules();
    }
  },
  methods: {
    // Метод получения данных пациента
    getPatientData(id) {
      this.isLoading = true;
      // читаем выбранного пациента get запросом с параметром id
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
    // Метод получения статуса УСПД
    getModuleStatus() {
      this.$http
        .post("patients/" + this.patientData.id + "/get_module_status")
        .then((res) => {
          this.isModuleRead = true;
          this.moduleStatusMessage = "данные загружены успешно " + this.mtz(res.data["date"], "DD-MM-YYYY");
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },
    /**
     * Метод получения списка ПОИ
     */
    getProducts() {
      this.isLoading = true;
      this.$http
        .get("products?all=1")
        .then((res) => {
          this.products = res.data;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
          console.log(err);
        });
    },
    /**
     * Метод получения списка УСПД
     */
    getModules() {
      this.isLoading = true;
      this.$http
        .get("modules?all=1")
        .then((res) => {
          this.modules = res.data;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
          console.log(err);
        });
    },

    // Отображает спискок диагнозов для выбора
    showDiagnosSelectingWindow() {
      this.diagnosSelectingMode = true;
    },

    // Отображает окно с формой загрузки данных, полученных с успд
    showModuleDataWindow() {
      this.moduleDataMode = true;
    },

    // Обработка ответа от компонента добавления диагноза
    processResponse(response) {
      this.diagnosSelectingMode = false;
      if (response) {
        this.getPatientData(this.patientData.id);
      }
    },

    /**
     * Метод для обновления данных пациента
     */
    savePatient() {
      this.$refs["patientValidate"].validate((valid) => {
        if (valid) {
          this.isLoading = true;
          this.$http
            .put("patients/" + this.patientData.id, this.changedPatientData)
            .then((res) => {
              this.$Message.success("Пациент успешно изменен");
              this.isLoading = false;
              this.patientEditingFlag = false;
              this.getPatientData(this.patientData.id);
            })
            .catch((err) => {
              this.$Message.error("Ошибка при изменении пациента");
              this.has_error = true;
              this.isLoading = false;
            });
        }
      });
    },

    /**
     * Метод для прикрепления ПОИ и УСПД к диагнозу
     */
    attachProduct() {
      this.$http
        .post("patients/" + this.patientData.id + "/diagnos/" + this.selectedDiagnos + "/attach_product", {
          productId: this.selectedProduct,
          moduleId: this.selectedModule,
        })
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
    // Метод для открепления ПОИ от диагноза
    detachProduct(diagnosId) {
      console.log(diagnosId);
      this.$http
        .post("patients/" + this.patientData.id + "/diagnos/" + diagnosId + "/detach_product", {
          params: {},
        })
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
    // Метод для удаления выбранного диагноза у пациента
    removeDiagnos(diagnosId) {
      let self = this;
      this.isLoading = true;
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
    // Метод для добавления приема врача пациенту, либо обновления уже существующего
    attachReception() {
      this.$http
        .post("patients/" + this.patientData.id + "/attach_reception", {
          comment: this.receptionData.comment,
          date: this.receptionData.date,
          id: this.receptionData.id,
        })
        .then((res) => {
          //todo show message
          this.isLoading = false;
          this.getPatientData(this.patientData.id);
          this.resetReceptionData();
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
      this.isModuleRead = false;
    },
    // Метод для отображения модального окна для просмотра или редактирования приема
    openReception(receiptId = -1) {
      this.receptionSelectingMode = true;
      if (receiptId != -1) {
        this.receptions = this.patientData.receptions;
        this.receptionData.comment = this.receptions[receiptId].receipt_description;
        this.receptionData.date = this.receptions[receiptId].receipt_date;
        this.receptionData.id = this.receptions[receiptId].id;
        console.log(this.receptionData.id);
      } else {
        let date = new Date();
        this.receptionData.date = date.toJSON().substr(0, 10);
      }
    },
    // Метод для удаления приема из списка
    removeReception(receiptId) {
      this.receptions = this.patientData.receptions;
      console.log(this.receptions[receiptId].id);
      this.$http
        .post("patients/" + this.patientData.id + "/remove_reception", {
          id: this.receptions[receiptId].id,
        })
        .then((res) => {
          //todo show message
          this.isLoading = false;
          this.getPatientData(this.patientData.id);
          this.resetReceptionData();
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },
    // Метод для очистки данных после закрытия окна приема
    resetReceptionData() {
      this.receptionData.comment = null;
      this.receptionData.date = null;
      this.receptionData.id = null;
      this.isModuleRead = false;
    },

    // возвращаемся на страницу назад
    returnBack() {
      this.$router.go(-1);
    },

    showPatientEditingWindow() {
      this.changedPatientData.name = this.patientData.name;
      this.changedPatientData.birth_date = this.mtz(this.patientData.birth_date, "DD-MM-YYYY");
      this.patientEditingFlag = true;
    },

    showLogs() {
      this.LogDataMode = true;
      this.getLogs();
    },

    getLogs() {
      this.isLoading = true;
      this.$http
        .get("logs/?patientId=" + this.patientData.id)
        .then((res) => {
          this.logs = res.data;
          this.isLoading = false;
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    showAssessment() {
      this.assessmentMode = true;
      //   this.getAssessmentResult();
    },

    getAssessmentResult() {
      this.assessmentResult = null;
      this.$refs["formAssessment"].validate((valid) => {
        if (valid) {
          this.isLoading = true;
          this.$http
            .post("run_assessment", {
              patientId: this.patientData.id,
              moduleId: this.assessmentFilter.moduleId,
              dateFrom: this.assessmentFilter.dateFrom,
              dateTo: this.assessmentFilter.dateTo,
            })
            .then((res) => {
              this.assessmentResult = res.data.msg;
              this.isLoading = false;
            })
            .catch((err) => {
              this.has_error = true;
              this.isLoading = false;
            });
        }
      });
    },

    clearAssessment() {
      this.assessmentResult = null;
    },

    runEditingDiagnos(diagnos) {
      this.selectedDiagnosForEdit.diagnosId = diagnos.id;
      this.selectedDiagnosForEdit.comment = diagnos.pivot.comment;
      this.selectedDiagnosForEdit.issueDate = diagnos.pivot.issue_date;
      this.selectedDiagnosForEdit.moduleId = diagnos.pivot.module_id;
      this.selectedDiagnosForEdit.productId = diagnos.pivot.product_id;
      this.diagnosEditingMode = true;
    },

    saveEditedDiagnos() {
      this.$refs["formEditing"].validate((valid) => {
        if (valid) {
          this.isLoading = true;
          this.$http
            .post("patients/" + this.patientData.id + "/diagnos/" + this.selectedDiagnosForEdit.diagnosId + "/update", {
              issue_date: this.selectedDiagnosForEdit.issueDate,
              comment: this.selectedDiagnosForEdit.comment,
              product_id: this.selectedDiagnosForEdit.productId,
              module_id: this.selectedDiagnosForEdit.moduleId,
            })
            .then((res) => {
              this.isLoading = false;
              this.closeEditingDiagnos();
              this.getPatientData(this.patientData.id);
            })
            .catch((err) => {
              this.has_error = true;
              this.isLoading = false;
            });
        }
      });
    },

    clearEditingDiagnos() {
      this.selectedDiagnosForEdit = {
        diagnosId: null,
        comment: null,
        issueDate: null,
        moduleId: null,
        productId: null,
      };
    },

    closeEditingDiagnos() {
      this.diagnosEditingMode = false;
      this.clearEditingDiagnos();
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
.ivu-card-head p {
  overflow: inherit;
}
</style>
