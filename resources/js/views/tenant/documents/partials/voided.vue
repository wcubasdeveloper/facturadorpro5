<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors[`documents.0.description`]}">
                            <label class="control-label">Descripción del motivo de anulación</label>
                            <el-input v-model="form.documents[0].description" dusk="description"></el-input>

                            <template v-if="errors[`documents.0.description`]">
                                <small class="form-control-feedback"  v-text="errors[`documents.0.description`][0]"></small>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row form-actions mt-4">
                <div class="col-md-8">
                    <!--
                    <span v-if="hasSaleNote">
                        <label> Tiene asociado la(s) siguiente(s) nota de venta(s): </label>
                        <template v-for="(row,index) in form.sales_note">
                            <label class="d-block" :key="index">{{ row.number_full }} Fecha: {{row.date_of_issue}}</label><br>
                        </template>
                    </span>
                    -->
                </div>
                <div class="col-4 text-right">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button type="danger" native-type="submit" dusk="annulment-voided" :loading="loading_submit">
                        Anular
                    </el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props: ['showDialog', 'recordId'],
    data() {
        return {
            titleDialog: null,
            loading_submit: false,
            resource: null,
            errors: {},
            form: {
                sales_note: [{}],
            },
            group_id: null,
        }
    },
    computed: {
        hasSaleNote: function () {
            if (
                this.form !== undefined &&
                this.form.sales_note !== undefined &&
                this.form.sales_note.length > 0
            ) {
                return true;
            }

            return false;
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        initForm() {
            this.loading_submit = false,
                this.group_id = null,
                this.errors = {},
                this.form = {
                    date_of_reference: null,
                    summary_status_type_id: '3',
                    sales_note: [{}],
                    documents: [
                        {
                            document_id: null,
                            description: null,
                        }
                    ]
                }
        },
        create() {
            this.$http.get(`/documents/record/${this.recordId}`)
                .then(response => {
                    let document = response.data.data
                    this.group_id = document.group_id
                    this.form.date_of_reference = document.date_of_issue
                    this.form.documents[0].document_id = document.id
                    this.titleDialog = 'Comprobante: ' + document.number
                    this.form.sales_note = document.sales_note;
                })
        },
        submit() {
            this.loading_submit = true
            this.resource = (this.group_id === '01') ? 'voided' : 'summaries'
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$eventHub.$emit('reloadData')
                        this.$message.success(response.data.message)
                        this.close()
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
