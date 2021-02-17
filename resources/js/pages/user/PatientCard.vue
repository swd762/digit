<template>
    <div style="">
        <template v-if="isLoading">
            <Spin></Spin>
        </template>
        <template v-else>
            <Button type="primary" @click="returnBack">Назад</Button>
            <Button type="success" @click="showDiagnosSelectingWindow">
                Добавить диагноз
            </Button>
            <Button type="success" @click="openReception()">
                Добавить прием
            </Button>

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
                <p slot="title" style="height: auto">
                    Диагнозы
                    <Button icon="md-add" shape="circle" size="small" type="success"
                            @click="showDiagnosSelectingWindow">
                    </Button>
                </p>

                <div v-for="(diagnos, index) in patientData.diagnoses" :key="index">
                    {{ diagnos.title }}
                    <Button
                        icon="md-close"
                        shape="circle"
                        size="small"
                        type="error"
                        @click="removeDiagnos(diagnos.id)"
                    />
                    <div v-if="diagnos.pivot.product">
                        <div style="margin-top: 10px">
                            <p style="display: inline-block">Выданное изделие: <strong>{{
                                diagnos.pivot.product.name
                                }}</strong>
                            </p>
                            <Button
                                icon="md-close"
                                shape="circle"
                                size="small"
                                type="error"
                                @click="detachProduct(diagnos.id)"
                            />
                            <p>
                                Дата выдачи: <strong>{{ diagnos.pivot.issue_date }}</strong>
                            </p>
                        </div>


                        <template
                            v-if="
                diagnos.pivot.product.modules &&
                diagnos.pivot.product.modules.length
              "
                        >
                            <br/>
                            <p>Прикрепленные модули:</p>
                            <ul>
                                <li
                                    v-for="(module, index) in diagnos.pivot.product.modules"
                                    :key="index"
                                >
                                    {{ module.name }}
                                </li>
                            </ul>
                            <br/>
                        </template>
                        <p v-else>Прикрепленные модули отсутствуют</p>
                    </div>
                    <div v-else>
                        Изделие по данному диагнозу не выдавалось
                        <Button
                            size="small"
                            type="success"
                            @click="
                selectedDiagnos = diagnos.id;
                productSelectingMode = true;
              "
                        >Выдать
                        </Button
                        >
                    </div>

                    <Divider/>
                </div>
            </Card>

            <Card :dis-hover="true" style="margin: 10px 0">
                <p slot="title" style="height: auto">
                    Приемы
                    <Button icon="md-add" shape="circle" size="small" type="success"
                            @click="openReception()">
                    </Button>
                </p>

                <div v-for="(reception, index) in patientData.receptions" :key="index">
                    Прием
                    {{ reception.id }}
                    <Button
                        icon="md-close"
                        shape="circle"
                        size="small"
                        type="error"
                        @click="removeReception(index)"
                    />
                    <Button
                        icon="md-open"
                        shape="circle"
                        size="small"
                        type="success"
                        @click="openReception(index)"
                    />
                    <br>
                    Дата приема:
                    {{ reception.receipt_date }}
                    <br><br>
                </div>
            </Card>
        </template>

        <diagnos-selecting
            v-if="diagnosSelectingMode"
            :isShowing="diagnosSelectingMode"
            :patient-id="patientId"
            @finished="processResponse"
        />

        <Modal
            v-model="productSelectingMode"
            title="Выдача изделия"
            @on-ok="attachProduct"
        >
            <Form :disabled="isLoading">
                <FormItem label="Выберите изделие" prop="device">
                    <Select v-model="selectedProduct" :filterable="true">
                        <Option
                            v-for="(product, index) in products"
                            :key="index"
                            :value="product.id"
                        >{{ product.name }}
                        </Option
                        >
                    </Select>
                </FormItem>
            </Form>
        </Modal>

        <Modal
            v-model="receptionSelectingMode"
            title="Прием врача"
            @on-ok="attachReception()"
            @on-cancel="resetReceptionData()"
        >
            <p><strong>Пациент: </strong>{{ patientData.name }}</p>
            <p><strong>Дата приема: </strong>{{ receptionData.date }}</p>
            <Form :disabled="isLoading">
                <FormItem label="Комментарий к осмотру врача">
                    <Input
                        v-model="receptionData.comment"
                        type="textarea"
                        :autosize="{ minRows: 3, maxRows: 10 }"
                    />
                </FormItem>
                <Alert type="success" show-icon v-if="isModuleRead">{{moduleStatusMessage}}</Alert>
                <Button type="success" @click="getModuleStatus">
                    Прочитать данные с модуля
                </Button>
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
            isModuleRead: false,
            diagnosSelectingMode: false,
            productSelectingMode: false,
            receptionSelectingMode: false,

            patientId: this.$route.params.patientId,
            patientData: {},

            diagnoses: [],
            selectedDiagnos: null,
            selectedDiagnosComment: null,
            receptionData: {
                id: null,
                comment: null,
                date: null,
            },
            receptions: [],
            products: [],
            selectedProduct: null,
            moduleStatusMessage:null
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
        disableModuleStatusMessage() {

        },
        getModuleStatus() {
            this.isModuleRead = true;
            this.$http
                .post(
                    "patients/" +
                    this.patientData.id +
                    "/get_module_status"
                )
                .then((res) => {
                    this.moduleStatusMessage = "данные загружены успешно " + res.data['date'];
                })
                .catch((err) => {
                    this.has_error = true;
                    this.isLoading = false;
                });

        },

        getProducts() {
            this.isLoading = true;
            // Получаем список изделий
            this.$http.post({
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
        // открепляет продукт от диагноза
        detachProduct(diagnosId) {
            console.log(diagnosId)
            this.$http.post(
                "patients/" +
                this.patientData.id +
                "/diagnos/" +
                diagnosId +
                "/detach_product", {
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
                })
        },
        // Удаляем выбранный диагноз у пациента
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
        // добавляем осмотр врача пациенту, либо обновляем уже существующий
        attachReception() {
            this.$http.post("patients/" + this.patientData.id + "/attach_reception", {
                comment: this.receptionData.comment,
                date: this.receptionData.date,
                id: this.receptionData.id
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
                })
            this.isModuleRead = false;
        },
        // открываем модальное окно с для просмотра или редактирования приема
        openReception(receiptId = -1) {
            this.receptionSelectingMode = true;
            if (receiptId != -1) {
                this.receptions = this.patientData.receptions
                this.receptionData.comment = this.receptions[receiptId].receipt_description
                this.receptionData.date = this.receptions[receiptId].receipt_date
                this.receptionData.id = this.receptions[receiptId].id
                console.log(this.receptionData.id)
            } else {
                let date = new Date()
                this.receptionData.date = date.toJSON().substr(0, 10)
            }
        },
        // удаляем прием из списка
        removeReception(receiptId) {
            this.receptions = this.patientData.receptions
            console.log(this.receptions[receiptId].id)
            this.$http.post("patients/" + this.patientData.id + "/remove_reception", {
                id: this.receptions[receiptId].id
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
                })
        },
        // очищаем данные после закрытия окна приема
        resetReceptionData() {
            this.receptionData.comment = null
            this.receptionData.date = null
            this.receptionData.id = null
            this.isModuleRead = false;
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
