<template>
    <el-dialog :title="titleDialog" :visible="showDialog"   @close="close" @open="create"   append-to-body top="7vh" width="30%">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="control-label">Aplicar cargo automático
                            </label>
                            <el-switch v-model="form.active_allowance_charge" active-text="Si" inactive-text="No"></el-switch>
                            <small class="form-control-feedback" ></small>
                        </div>
                    </div>
                    <div class="col-md-4" v-show="form.active_allowance_charge">
                        <label class="control-label">Porcentaje</label>
                        <div class="form-group">
                            <el-input-number v-model="form.percentage_allowance_charge" :min="0.01" :precision="2" :max="99"></el-input-number>
                            <small class="form-control-feedback"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2 mt-3">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button type="primary" @click.prevent="clickSubmit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>


    export default {
        props:['showDialog', 'form'],
        data() {
            return {
                showImportDialog: false,
                recordId: null,
                titleDialog: 'Configurar cargo',
            }
        },
        created() {
        },
        methods: {
            clickSubmit(){
                this.$eventHub.$emit('submitFormConfigurations', this.form)
                this.close()
            },
            create(){
                // console.log(this.form)
            },
            close() {
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
