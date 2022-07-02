<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuraciones</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="control-label">Venta con restricción de stock</label>
                            <div class="form-group" :class="{'has-danger': errors.stock_control}">
                                <el-switch v-model="form.stock_control" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.stock_control" v-text="errors.stock_control[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <!-- migracion desarrollo sin terminar #1401 -->
                            <label class="control-label">Generar automaticamente codigo interno del producto</label>
                            <div class="form-group" :class="{'has-danger': errors.generate_internal_id}">
                                <el-switch v-model="form.generate_internal_id" active-text="Si" inactive-text="No" @change="submit"></el-switch>
                                <small class="form-control-feedback" v-if="errors.generate_internal_id" v-text="errors.generate_internal_id[0]"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                loading_submit: false,
                resource: 'inventories/configuration',
                errors: {},
                form: {}
            }
        },
        async created() {
            await this.initForm();
            await this.getRecord()
        },
        methods: {
            initForm() {
                this.errors = {};

                this.form = {
                    id: null,
                    stock_control: false,
                };
            },
            async getRecord() {
                await this.$http.get(`/${this.resource}/record`) .then(response => {
                    if (response.data !== '') this.form = response.data.data;
                });
            },
            submit() {
                this.loading_submit = true;

                this.$http.post(`/${this.resource}`, this.form).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                    }
                    else {
                        this.$message.error(response.data.message);
                        this.getRecord()
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
                        console.log(error);
                    }
                }).then(() => {
                    this.loading_submit = false;
                });
            }
        }
    }
</script>
