<template>
    <el-dialog :close-on-click-modal="false"
               :title="titleDialog"
               :visible="showDialog"
               :append-to-body="true"
               @close="close"
               @open="create">

        <form autocomplete="off"
              @submit.prevent="submit">
              
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.identity_document_type_id}"
                                class="form-group">
                            <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                            <el-select v-model="form.identity_document_type_id"
                                        dusk="identity_document_type_id"
                                        filterable
                                        popper-class="el-select-identity_document_type"
                                        @change="changeIdentityDocType">
                                <el-option v-for="option in identity_document_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.identity_document_type_id"
                                    class="form-control-feedback"
                                    v-text="errors.identity_document_type_id[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.number}"
                                class="form-group">
                            <label class="control-label">Número <span class="text-danger">*</span></label>

                            <el-input v-model="form.number"
                                        :maxlength="maxLength">
                            </el-input>

                            <small v-if="errors.number"
                                    class="form-control-feedback"
                                    v-text="errors.number[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.name}"
                                class="form-group">
                            <label class="control-label">Nombre <span class="text-danger">*</span></label>
                            <el-input v-model="form.name"
                                        dusk="name"></el-input>
                            <small v-if="errors.name"
                                    class="form-control-feedback"
                                    v-text="errors.name[0]"></small>
                        </div>
                    </div> 


                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.birth_date}"
                                class="form-group">
                            <label class="control-label">Fecha nacimiento <span class="text-danger">*</span></label>

                            <el-date-picker v-model="form.birth_date"
                                            type="date"
                                            :clearable="false"
                                            value-format="yyyy-MM-dd"></el-date-picker>

                            <small v-if="errors.birth_date"
                                    class="form-control-feedback"
                                    v-text="errors.birth_date[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.admission_date}"
                                class="form-group">
                            <label class="control-label">Fecha ingreso <span class="text-danger">*</span></label>

                            <el-date-picker v-model="form.admission_date"
                                            :clearable="false"
                                            type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>

                            <small v-if="errors.admission_date"
                                    class="form-control-feedback"
                                    v-text="errors.admission_date[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.occupation}"
                                class="form-group">
                            <label class="control-label">Cargo <span class="text-danger">*</span></label>
                            <el-input v-model="form.occupation"></el-input>

                            <small v-if="errors.occupation"
                                    class="form-control-feedback"
                                    v-text="errors.occupation[0]"></small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div :class="{'has-danger': errors.address}"
                                class="form-group">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.address"
                                        dusk="address"></el-input>
                            <small v-if="errors.address"
                                    class="form-control-feedback"
                                    v-text="errors.address[0]"></small>
                        </div>
                    </div>

                    <!-- Telefono -->
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.telephone}"
                                class="form-group">
                            <label class="control-label">Teléfono</label>
                            <el-input v-model="form.telephone"
                                        dusk="telephone"></el-input>
                            <small v-if="errors.telephone"
                                    class="form-control-feedback"
                                    v-text="errors.telephone[0]"></small>
                        </div>
                    </div>

                    <!-- Correo electronico contacto -->
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.email}"
                                class="form-group">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="form.email"
                                        dusk="email"></el-input>
                            <small v-if="errors.email"
                                    class="form-control-feedback"
                                    v-text="errors.email[0]"></small>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>

export default {
    props: [
        'showDialog',
        'recordId',
    ],
    data() {
        return {
            parent: null,
            loading_submit: false,
            titleDialog: null,
            titleTabDialog: null,
            typeDialog: null,
            resource: 'workers',
            errors: {},
            form: {
            },
            identity_document_types: [],
            activeName: 'first'
        }
    },
    async created() {

        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.identity_document_types = response.data.identity_document_types;
            })

    },
    computed: {
        maxLength: function () {
            if (this.form.identity_document_type_id === '6') {
                return 11
            }
            if (this.form.identity_document_type_id === '1') {
                return 8
            }
        },
    },
    methods: {
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '1',
                number: null,
                name: null,
                birth_date: moment().format('YYYY-MM-DD'),
                admission_date: moment().format('YYYY-MM-DD'),
                occupation: null,
                address: null,
                email: null,
                telephone: null,
            }

        },
        create() {

            this.titleDialog = (this.recordId) ? 'Editar empleado' : 'Nuevo empleado'

            if (this.recordId) 
            {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                    })
            }
        },
        async submit() {

            this.loading_submit = true
            await this.$http.post(`/${this.resource}`, this.form)
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
                .finally(() => {
                    this.loading_submit = false
                })
        },
        changeIdentityDocType() {
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
