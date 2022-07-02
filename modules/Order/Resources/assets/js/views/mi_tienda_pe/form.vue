<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Avanzado</span></li>
                <li><span class="text-muted">MiTienda.Pe</span></li>
            </ol>
        </div>
        <template>
            <div >
                <el-tabs v-model="activeName"
                         class="rounded"
                         type="border-card">
                    <el-tab-pane class="mb-3"
                                 name="first">
                        <span slot="label">Pedidos</span>

                        <div class="row">
                            <div class="col-11 offset-1">
                                Al importar los pedidos, se ajustara por defecto los siguientes elementos
                            </div>
                            <!-- estableciieinto -->
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.establishment_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id"
                                               filterable
                                               @change="getSeries">
                                        <el-option
                                            v-for="option in establishments"
                                            :key="option.id"
                                            :label="option.name"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.establishment_id"
                                        class="form-control-feedback"
                                        v-text="errors.establishment_id[0]"
                                    ></small>
                                </div>
                            </div>


                            <!-- Serie por defecto -->
                            <!--
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.series_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">Serie para Pedido</label>
                                    <el-select v-model="form.series_order_note_id"
                                               clearable
                                               filterable>
                                        <el-option
                                            v-for="option in series_order"
                                            :key="option.id"
                                            :label="option.number"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.series_id"
                                        class="form-control-feedback"
                                        v-text="errors.series_id[0]"
                                    ></small>
                                </div>
                            </div>
                            -->
                            <!--factura -->
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.series_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Serie para Factura
                                    </label>
                                    <el-select v-model="form.series_document_ft_id"
                                               clearable
                                               filterable>
                                        <el-option
                                            v-for="option in series_document_ft"
                                            :key="option.id"
                                            :label="option.number"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.series_id"
                                        class="form-control-feedback"
                                        v-text="errors.series_id[0]"
                                    ></small>
                                </div>
                            </div>
                            <!-- boletas -->
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.series_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Serie para Boleta
                                    </label>
                                    <el-select v-model="form.series_document_bt_id"
                                               clearable
                                               filterable>
                                        <el-option
                                            v-for="option in series_document_bt"
                                            :key="option.id"
                                            :label="option.number"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.series_id"
                                        class="form-control-feedback"
                                        v-text="errors.series_id[0]"
                                    ></small>
                                </div>
                            </div>
                            <!-- Destino de pago-->
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.series_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Destino de pago
                                    </label>
                                    <el-select v-model="form.payment_destination_id"
                                               clearable
                                               filterable>
                                        <el-option
                                            v-for="option in payment_destinations"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.series_id"
                                        class="form-control-feedback"
                                        v-text="errors.series_id[0]"
                                    ></small>
                                </div>
                            </div>
                            <!-- Moneda -->
                            <div class="col-md-6 mt-4">
                                <div
                                    :class="{ 'has-danger': errors.series_id }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Moneda por defecto
                                    </label>
                                    <el-select v-model="form.currency_type_id"
                                               clearable
                                               filterable>
                                        <el-option
                                            v-for="option in currency_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id"
                                        ></el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.series_id"
                                        class="form-control-feedback"
                                        v-text="errors.series_id[0]"
                                    ></small>
                                </div>
                            </div>
                            <div class="col-md-6 mt-4">

                                <label class="control-label">
                                    ¿Generar automaticamente las Boleta/Factura?

                                    <el-tooltip
                                        class="item"
                                        content="Si esta activado, se generará automaticamente el documento"
                                        effect="dark"
                                        placement="top-start">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <div :class="{'has-danger': errors.autogenerate}"
                                     class="form-group">
                                    <el-switch v-model="form.autogenerate"
                                               active-text="Si"
                                               inactive-text="No"></el-switch>
                                    <small v-if="errors.autogenerate"
                                           class="form-control-feedback"
                                           v-text="errors.autogenerate[0]"></small>
                                </div>
                            </div>

                            <div class="col-12 text-right mt-4">
                                <el-button :loading="loading_submit"

                                           type="primary"
                                           @click.prevent="submit">Guardar
                                </el-button>

                            </div>


                        </div>

                    </el-tab-pane>
                    <!--
                    <el-tab-pane class="mb-3"
                                 name="second">
                        <span slot="label">Pedidos</span>
                        <div class="row">
                            <div class="col-md-6 mt-4">
                                <label class="control-label">
                                    Habilitar importación de MiTienda.Pe

                                    <el-tooltip
                                        class="item"
                                        content="Requiere modificaciones en configuracion -> avanzado -> pedidos"
                                        effect="dark"
                                        placement="top-start">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <div :class="{'has-danger': errors.mi_tienda_pe}"
                                     class="form-group">
                                    <el-switch v-model="form.mi_tienda_pe"
                                               active-text="Si"
                                               inactive-text="No"
                                               @change="submit"></el-switch>
                                    <small v-if="errors.mi_tienda_pe"
                                           class="form-control-feedback"
                                           v-text="errors.mi_tienda_pe[0]"></small>
                                </div>
                            </div>


                        </div>

                    </el-tab-pane>
                    -->
                </el-tabs>


            </div>
        </template>
    </div>
</template>

<style>
.el-tabs__header,
.el-tabs__nav-wrap {
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}
</style>

<script>

import {mapActions, mapState} from "vuex";

export default {
    props: [
        'configuration',
        'establishments',
    ],
    components: {},
    computed: {
        ...mapState([
            'config',
            'mi_tienda_pe',
        ]),
    },
    data() {
        return {
            // establishments:[],
            series_order: [],
            series_document_ft: [],
            series_document_bt: [],
            payment_destinations: [],
            currency_types: [],


            headers: headers_token,
            showDialogTermsCondition: false,
            showDialogTermsConditionSales: false,
            showDialogAllowanceCharge: false,
            loading_submit: false,
            resource: 'mi_tienda_pe',
            errors: {},
            form: {
                finances: {},
                visual: {},
                dispatches_address_text: false,
            },
            affectation_igv_types: [],
            placeholder: '',
            activeName: 'first'
        }
    },
    created() {
        this.$store.commit('setConfiguration', this.configuration)
        this.loadConfiguration()
        this.form = this.mi_tienda_pe;
        this.getData();
        this.form = this.mi_tienda_pe;


    },
    mounted() {

        this.initForm();
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.errors = {};
            this.form = this.mi_tienda_pe;
        },
        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/${this.resource}/save`, this.form)
                .then(response => {

                    let data = response.data;
                    //configurationMiTienda
                    if (data.success) {
                        this.$message.success(data.message);
                    } else {

                        if(data.configurationMiTienda !== undefined){
                            this.$message.success("Se han guardado los datos");
                            this.$store.commit('setMiTiendaPe', data.configurationMiTienda)

                        }else{
                        this.$message.error(data.message);
                    }
                    }
                    //   this.$store.commit('setConfiguration', data.configuration)

                }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }).finally(() => {
                this.loading_submit = false;
            });
        },
        getData(){
            this.$http.post(`/${this.resource}/getdata`)
                .then((response) => {
                    let data = response.data
                    this.$store.commit('setMiTiendaPe', data.configurationMiTienda)
                    this.form = this.mi_tienda_pe
                    data = data.data
                    this.series_order = data.series_order
                    this.series_document_ft = data.series_document_ft
                    this.series_document_bt = data.series_document_bt
                    this.payment_destinations = data.payment_destinations
                    this.currency_types = data.currency_types
                    this.form = this.mi_tienda_pe

                });

        },
        getSeries() {
            this.$http.post(`/${this.resource}`, this.form)
                .then((response) => {
                    let data = response.data.data
                    this.series_order = data.series_order
                    this.series_document_ft = data.series_document_ft
                    this.series_document_bt = data.series_document_bt
                    this.payment_destinations = data.payment_destinations
                    this.currency_types = data.currency_types

                });
        },
    }
}
</script>
