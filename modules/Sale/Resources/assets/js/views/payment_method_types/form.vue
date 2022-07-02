<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group" :class="{'has-danger': errors.id}">
                        <label class="control-label">Código</label>
                        <el-input v-model="form.id" :readonly="recordId !== null" :maxlength="2"></el-input>
                        <small class="form-control-feedback" v-if="errors.id" v-text="errors.id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.description}">
                            <label class="control-label">Descripción</label>
                            <el-input v-model="form.description"></el-input>
                            <small class="form-control-feedback" v-if="errors.description" v-text="errors.description[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" :class="{'has-danger': errors.is_credit}">
                            <label class="control-label">Tipo de pago</label>

                            <el-select
                                v-model="form.is_credit"
                                clearable
                                placeholder=""
                            >
                                <el-option
                                    v-for="of in is_credit_text"
                                    :key="of.id"
                                    :value="of.id"
                                    :label="of.name"
                                ></el-option>
                            </el-select>

                            <small
                                class="form-control-feedback"
                                v-if="errors.is_credit"
                                v-text="errors.is_credit[0]"
                            ></small>
                        </div>
                    </div>
                    <div class="col-md-3" v-show="form.is_credit">
                        <label class="control-label">Número de dias</label>
                        <el-input v-model="form.number_days" :maxlength="3"></el-input>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                is_credit_text: [
                    {
                        'id': 0,
                        'name': 'Contado',
                    },
                    {
                        'id': 1,
                        'name': 'Crédito',
                    }
                ],
                resource: 'payment-method-types',
                errors: {},
                form: {},
                options: [],
            }
        },
        created() {
            this.initForm()
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
                    id: null,
                    description: null,
                    is_credit: 0,
                    number_days: 0,
                }
            },
            create() {
                this.titleDialog = (this.recordId)? 'Editar método de pago':'Nuevo método de pago'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data
                        })
                }
            },
            submit() {
                this.loading_submit = true
                this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },
        }
    }
</script>
