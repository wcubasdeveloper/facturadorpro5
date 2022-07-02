<template>
    <el-dialog :visible="showDialog" class="dialog-import" title="Excel DIGEMID" @close="close">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-12">
                        <template>
                            <label class="control-label">
                                Se generará un excel para importarlo en DIGEMID

                            </label>

                        </template>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button :loading="loading_submit" native-type="submit" type="primary">Procesar</el-button>
                </div>
            </div>
        </form>
    </el-dialog>
</template>

<script>

export default {
    props: [
        'showDialog',
        'pharmacy',
    ],
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            resource: 'items',
            errors: {},
            form: {
            },
            max_item: 1,
            fromPharmacy: false,
        }
    },
    created() {
        if (this.pharmacy !== undefined && this.pharmacy === true) {
            this.fromPharmacy = true;
        }
        this.initForm()
    },
    methods: {
        initForm() {
            this.errors = {}
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        submit() {
            this.loading_submit = true

            window.open(`/${this.resource}/export/digemid`, '_blank');

            this.loading_submit = false
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
