<template>
    <el-dialog :close-on-click-modal="false"
               :close-on-press-escape="false"
               :title="titleDialog"
               :visible="showDialog"
               @close="close">
        <form autocomplete="off"
              @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">

                    <div class="col-md-12">
                        <div :class="{'has-danger': errors.description}"
                             class="form-group">
                            <label class="control-label">
                                Descripción
                            </label>
                            <el-input v-model="form.description"
                                      autosize="autosize"
                                      type="textarea">
                            </el-input>
                            <small v-if="errors.description"
                                   class="form-control-feedback"
                                   v-text="errors.description[0]">
                            </small>
                        </div>
                    </div>
                    <!-- total_interest -->
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.total_interest}"
                             class="form-group">
                            <label class="control-label">
                                Interes a pagar
                            </label>
                            <el-input-number
                                v-model="form.total_interest"

                            @change="fixTotal"
                            >
                                <template v-if="currencyType"
                                          slot="prepend">{{ currencyType.symbol }}
                                </template>
                            </el-input-number>
                            <small v-if="errors.total_interest"
                                   class="form-control-feedback"
                                   v-text="errors.total_interest[0]">
                            </small>
                        </div>
                    </div>
                    <!-- total_ingress -->
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.total_ingress}"
                             class="form-group">
                            <label class="control-label">
                                Total otorgado
                            </label>
                            <el-input-number v-model="form.total_ingress"
                                      @change="fixTotal">
                                <template v-if="currencyType"
                                          slot="prepend">{{ currencyType.symbol }}
                                </template>
                            </el-input-number>
                            <small v-if="errors.total_ingress"
                                   class="form-control-feedback"
                                   v-text="errors.total_ingress[0]">
                            </small>
                        </div>
                    </div>

                    <!--total -->
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.total}"
                             class="form-group">
                            <label class="control-label">
                                Total a pagar
                            </label>
                            <el-input v-model="form.total"
                                      :disabled="true">
                                <template v-if="currencyType"
                                          slot="prepend">{{ currencyType.symbol }}
                                </template>
                            </el-input>
                            <small v-if="errors.total"
                                   class="form-control-feedback"
                                   v-text="errors.total[0]">
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">
                    Cerrar
                </el-button>
                <el-button native-type="submit"
                           type="primary">Agregar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>


export default {
    props: ['showDialog', 'currencyType', 'exchangeRateSale'],
    data() {
        return {
            titleDialog: 'Agregar Detalle',
            autosize: {
                minRows: 1,
                maxRows: 10
            },
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
                description: null,
                total: null,
                total_original: null,
                currency_type_id: null,
                total_interest: null,
                total_ingress: null,
            }
        },
        close() {
            this.initForm()
            this.$emit('update:showDialog', false)
        },
        fixTotal(){
            this.form.total_interest = parseFloat(this.form.total_interest)
            if(isNaN(this.form.total_interest)) this.form.total_interest = 0
            this.form.total_ingress = parseFloat(this.form.total_ingress)
            if(isNaN(this.form.total_ingress)) this.form.total_ingress = 0
            this.form.total_original = parseFloat(this.form.total_interest + this.form.total_ingress)
            if(isNaN(this.form.total_original)) this.form.total_original = 0

            this.form.total = this.form.total_original
        },
        clickAddItem() {
            delete(this.errors.description)
            if(!this.form.description) this.form.description = '';
            if(this.form.description.length < 1 ) {
                this.errors.description = [];
                this.errors.description[0] = 'No puede estar vacio';
                return null;
            }
            this.form.currency_type_id = this.currencyType.id
            this.fixTotal()
            // if (this.currencyType.id === 'USD')
            // {
            //     total = this.form.total / this.exchangeRateSale;
            // }
            // else{
            //     total = this.form.total;
            // }
            // this.form.total = _.round(total,4)
            this.$emit('add', this.form)
            this.initForm()
        },
    }
}

</script>
