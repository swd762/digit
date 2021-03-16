<template>
    <div style="max-width: 400px">
        <Form
            ref="formValidate"
            :model="user_data"
            :rules="ruleValidate"
            label-position="top"
            :disabled="loading"
        >
            <FormItem label="Логин" prop="name">
                <Input v-model="user_data.name" placeholder="Введите логин" disabled />
            </FormItem>
            <FormItem label="E-mail" prop="email">
                <Input v-model="user_data.email" placeholder="Ввведите email" />
            </FormItem>
            <FormItem label="Имя" prop="first_name">
                <Input v-model="user_data.first_name" placeholder="Введите имя" />
            </FormItem>
            <FormItem label="Фамилия" prop="last_name">
                <Input v-model="user_data.last_name" placeholder="Введите фамилию" />
            </FormItem>
            <FormItem label="Отчество" prop="middle_name">
                <Input v-model="user_data.middle_name" placeholder="Введите отчество" />
            </FormItem>
            <FormItem label="Роль">
                <Select v-model="user_data.role">
                    <Option value="user">Врач</Option>
                    <Option value="admin">Администратор</Option>
                </Select>
            </FormItem>
            <FormItem>
                <Button type="primary" @click="updateUser">Сохранить</Button>
            </FormItem>
        </Form>
    </div>
</template>

<script>
export default {
    name: "EditProduct"

}
</script>

<style scoped>

</style>
