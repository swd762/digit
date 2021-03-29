<!--компонент выбора диагноза-->
<template>
  <Modal :value="isShowing" title="Назначение диагноза" @on-ok="attachDiagnos" @on-cancel="closeModal">
    <Form ref="formDiagnos" :model="newDiagnosData" :disabled="isLoading">
      <FormItem label="Диагноз" prop="diagnos">
        <Select :filterable="true" v-model="newDiagnosData.diagnos">
          <Option v-for="(diagnos, index) in diagnoses" :key="index" :value="diagnos.id">{{ diagnos.title }}</Option>
        </Select>
      </FormItem>
      <FormItem label="Комментарий к диагнозу" prop="comment">
        <Input v-model="newDiagnosData.comment" type="textarea" :autosize="{ minRows: 2, maxRows: 5 }" />
      </FormItem>
    </Form>
  </Modal>
</template>

<script>
export default {
  name: "DiagnosSelecting",
  data() {
    return {
      //Статус загрузки
      isLoading: false,
      //Наличие ошибок
      has_error: false,

      // Список диагнозов
      diagnoses: [],

      //Данные диагноза для привязки
      newDiagnosData: {
        diagnos: null,
        comment: null,
      },
    };
  },
  props: {
    //id пациента
    patientId: {
      required: true,
    },
    // Флаг для отображения компонента
    isShowing: {
      type: Boolean,
      default: false,
    },
  },
  created() {
    // запрос с сервера списка пользователй после загрузки страницы
    this.getDiagnoses();
  },
  methods: {
    //Получаем список диагнозов на случай назначения
    getDiagnoses() {
      this.$http({
        url: "diagnoses?all=1",
        method: "GET",
        params: {
          patientId: this.patientId,
        },
      })
        .then((res) => {
          this.diagnoses = res.data;
        })
        .catch((err) => {
          this.has_error = true;
        });
    },
    // Привязка диагноза
    attachDiagnos() {
      this.isLoading = true;
      // Добавляем выбранный диагноз пациенту
      this.$http
        .post("patients/" + this.patientId + "/attach_diagnos", {
          diagnosId: this.newDiagnosData.diagnos,
          diagnosComment: this.newDiagnosData.comment,
        })
        .then((res) => {
          //todo show message
          this.isLoading = false;
          this.clearSelectedDiagnos();
          // Возвращаем событие об окончании и true, чтобы были обновлены данные
          this.$emit("finished", true);
          //   this.getPatientData(self.patientData.id);
        })
        .catch((err) => {
          this.has_error = true;
          this.isLoading = false;
        });
    },

    // Закрытие модального окна
    closeModal() {
      this.clearSelectedDiagnos();
      this.$emit("finished", false);
    },
    // Очистка данных диагноза
    clearSelectedDiagnos() {
      this.newDiagnosData = {
        diagnos: null,
        comment: null,
      };
    },
  },
};
</script>


