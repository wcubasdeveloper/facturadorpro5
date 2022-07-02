<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">
                {{title}}
            </h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div :class="{'has-danger': errors.name}"
                                 class="form-group">
                                <label class="control-label">
                                    Nombre
                                </label>
                                <el-input v-model="form.name"></el-input>
                                <small v-if="errors.name"
                                       class="form-control-feedback"
                                       v-text="errors.name[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div :class="{'has-danger': errors.description}"
                                 class="form-group">
                                <label class="control-label">
                                    Descripcion
                                </label>
                                <el-input v-model="form.description"></el-input>
                                <small v-if="errors.description"
                                       class="form-control-feedback"
                                       v-text="errors.description[0]"></small>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div :class="{'has-danger': errors.active}"
                                 class="form-group">
                                <label class="control-label">
                                    Activo
                                </label>
                                <br>
                                <el-switch v-model="form.active" active-text="Si" inactive-text="No" ></el-switch>

                                <small v-if="errors.active"
                                       class="form-control-feedback"
                                       v-text="errors.active[0]"></small>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button
                        @click.prevent="close()">
                        Cancelar
                    </el-button>
                    <el-button
                        :loading="loading_submit"
                        native-type="submit"
                        type="primary">
                        {{ (id) ? 'Actualizar' : 'Guardar' }}
                    </el-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>


export default {
    props: ['id'],
    components: {},
    data() {
        return {
            resource: 'machine-type-production',
            title: 'Nuevo tipo de maquina',
            showDialog: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            errors: {},
            form: {},
            aux_supplier_id: null,
            mill_types: [],
            unit_types: [],
            currency_types: [],
            suppliers: [],
            establishment: {},
            currency_type: {},
            mill_method_types: [],
            payment_destinations: [],
            mill_reasons: [],
            millNewId: null
        }
    },
    mounted() {
        this.initForm()
    },
    methods: {
        showAddItemModal() {
            this.showDialog = true;
        },
        isUpdate() {
            this.title = 'Nuevo tipo de maquina';
            if (this.id) {
                this.$http.get(`/${this.resource}/record/${this.id}`)
                    .then(response => {
                        this.title = 'Editar tipo de maquina';
                        this.form = response.data
                    })
            }

        },
        initForm() {
            this.errors = {}
            this.form = {
                id: this.id,
                name: null,
                description: null,
                active: false,
            }
            this.isUpdate()

        },
        submit() {

            this.loading_submit = true
            this.$http.post(`/${this.resource}/create`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.initForm()
                        this.$message.success(response.data.message)
                        this.onClose()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .finally(() => {
                    this.loading_submit = false
                })
        },
        onClose() {
            this.$emit("update:visible", false);
            window.location.href = '/machine-type-production'
        },
    }
}
</script>
