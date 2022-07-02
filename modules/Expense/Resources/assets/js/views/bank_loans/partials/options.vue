<template>
    <el-dialog :close-on-click-modal="false"
               :close-on-press-escape="false"
               :show-close="false"
               :title="titleDialog"
               :visible="showDialog"
               width="30%"
               @open="create">

        <span slot="footer"
              class="dialog-footer">
            <template v-if="showClose">
                <el-button @click="clickClose">Cerrar</el-button>
            </template>
            <template v-else>
                <el-button @click="clickFinalize">Ir al listado</el-button>
                <el-button type="primary"
                           @click="clickNewDocument">{{ isUpdate ? 'Continuar' : 'Nuevo Prestamo Bancario' }}</el-button>
            </template>
        </span>
    </el-dialog>
</template>

<script>

export default {
    props: ['showDialog', 'recordId', 'showClose', 'isUpdate'],
    data() {
        return {
            titleDialog: null,
            loading: false,
            resource: 'bank_loan',
            errors: {},
            form: {},
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
                external_id: null,
                number: null,
                customer_email: null,
                download_pdf: null
            }
        },
        create() {
            this.$http.get(`/${this.resource}/record/${this.recordId}`)
                .then(response => {
                    this.form = response.data.data
                    this.titleDialog = ((this.isUpdate) ? 'Prestamo Bancario actualizado: ' : 'Prestamo Bancario registrado: ') + this.form.number
                })
        },

        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewDocument() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
