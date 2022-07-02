<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Nueva Compra</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">

                    <div class="row">
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.document_type_id}"
                                 class="form-group">
                                <label class="control-label">Tipo comprobante</label>
                                <el-select v-model="form.document_type_id"
                                           @change="changeDocumentType">
                                    <el-option v-for="option in document_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.document_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.document_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.series}"
                                 class="form-group">
                                <label class="control-label">Serie <span class="text-danger">*</span></label>
                                <el-input v-model="form.series"
                                          :maxlength="4"
                                          @input="inputSeries"></el-input>

                                <small v-if="errors.series"
                                       class="form-control-feedback"
                                       v-text="errors.series[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.number}"
                                 class="form-group">
                                <label class="control-label">Número <span class="text-danger">*</span></label>
                                <el-input v-model="form.number"></el-input>

                                <small v-if="errors.number"
                                       class="form-control-feedback"
                                       v-text="errors.number[0]"></small>
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.date_of_issue}"
                                 class="form-group">
                                <label class="control-label">Fec Emisión</label>
                                <el-date-picker v-model="form.date_of_issue"
                                                :clearable="false"
                                                type="date"
                                                value-format="yyyy-MM-dd"
                                                :readonly="readonly_date_of_due"
                                                @change="changeDateOfIssue"></el-date-picker>
                                <small v-if="errors.date_of_issue"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.date_of_due}"
                                 class="form-group">
                                <label class="control-label">Fec. Vencimiento</label>
                                <el-date-picker v-model="form.date_of_due"
                                                :clearable="false"
                                                type="date"
                                                :readonly="readonly_date_of_due"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_of_due"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_due[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div :class="{'has-danger': errors.supplier_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Proveedor
                                    <a href="#"
                                       @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select ref="select_person"
                                           v-model="form.supplier_id"
                                           filterable
                                           @change="changeSupplier"
                                           @keyup.native="keyupSupplier"
                                           @keyup.enter.native="keyupEnterSupplier">
                                    <el-option v-for="option in suppliers"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.supplier_id"
                                       class="form-control-feedback"
                                       v-text="errors.supplier_id[0]"></small>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.payment_method_type_id}">
                                <label class="control-label">
                                    Forma de pago
                                </label>
                                <el-select v-model="form.payment_method_type_id" filterable @change="changePaymentMethodType">
                                    <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.payment_method_type_id" v-text="errors.payment_method_type_id[0]"></small>
                            </div>
                        </div> -->
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.currency_type_id}"
                                 class="form-group">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_id"
                                           @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.currency_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.currency_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.exchange_rate_sale}"
                                 class="form-group">
                                <label class="control-label">Tipo de cambio
                                    <el-tooltip class="item"
                                                content="Tipo de cambio del día, extraído de SUNAT"
                                                effect="dark"
                                                placement="top-end">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="form.exchange_rate_sale"></el-input>
                                <small v-if="errors.exchange_rate_sale"
                                       class="form-control-feedback"
                                       v-text="errors.exchange_rate_sale[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2"
                             v-if="purchase_order_id === null">
                            <div class="form-group">
                                <label>
                                    Orden de compra
                                </label>
                                <el-select v-model="form.purchase_order_id"
                                           :loading="loading_search"
                                           clearable
                                           filterable
                                           placeholder="Número de documento"
                                           >
                                    <!--
                                    :remote-method="searchPurchaseOrder"
                                    remote-->
                                    <el-option v-for="option in purchase_order_data"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12 col-md-6 col-lg-4 "
                            :class="{ 'has-danger': errors.created_at }"
                            >
                            <label>
                                Observaciones
                            </label>
                            <el-input v-model="form.observation"
                                      placeholder="Observaciones"></el-input>
                        </div>
                        <div class="col-12">&nbsp;</div>

                        <div class="col-md-8 mt-4">
                            <div class="form-group">
                                <el-checkbox v-model="form.has_client"
                                             @change="changeHasClient">¿Desea agregar el cliente para esta compra?
                                </el-checkbox>
                            </div>
                        </div>

                        <div class="col-md-8 mt-2 mb-2">
                            <div class="form-group">
                                <el-checkbox v-model="form.has_payment"
                                             @change="changeHasPayment">¿Desea agregar pagos a esta compra?
                                </el-checkbox>
                            </div>
                        </div>

                        <div class="col-md-8 mt-2 mb-2" v-if="config.enabled_global_igv_to_purchase === true">
                            <div class="form-group">
                                <el-checkbox v-model="localHasGlobalIgv"
                                             :disabled="(this.form.items.length != 0 && this.config.enabled_global_igv_to_purchase === true)"
                                             @change="changeHasGlobalIgv">¿La compra tiene igv?
                                    <el-tooltip class="item"
                                                content="Al estar la configuracion activa, sobreescribe el igv del item. Si no esta checado, el producto no tendra igv."
                                                effect="dark"
                                                placement="top-end">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </el-checkbox>
                            </div>
                        </div>

                        <div v-if="form.has_client"
                             class="col-lg-6 col-md-6">
                            <div class="form-group">
                                <label class="control-label">
                                    Clientes
                                </label>

                                <el-select v-model="form.customer_id"
                                           :loading="loading_search"
                                           :remote-method="searchRemotePersons"
                                           clearable
                                           filterable
                                           placeholder="Nombre o número de documento"
                                           popper-class="el-select-customers"
                                           remote>
                                    <el-option v-for="option in customers"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>

                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <template v-if="form.has_payment">

                            <div class="col-lg-2 col-md-2">
                                <div :class="{'has-danger': errors.payment_condition_id}"
                                    class="form-group">
                                    <label class="control-label">Condición de pago</label>
                                    <el-select v-model="form.payment_condition_id"
                                            @change="changePaymentCondition">
                                        <el-option v-for="option in payment_conditions"
                                                :key="option.id"
                                                :label="option.name"
                                                :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.payment_condition_id"
                                        class="form-control-feedback"
                                        v-text="errors.payment_condition_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-12 mt-2">
                                <!-- Contado -->
                                <template v-if="form.payment_condition_id === '01'">
                                    <table>
                                        <thead>
                                        <tr width="100%">
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Forma de pago
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Desde
                                                <el-tooltip class="item"
                                                            content="Aperture caja o cuentas bancarias"
                                                            effect="dark"
                                                            placement="top-start">
                                                    <i class="fa fa-info-circle"></i>
                                                </el-tooltip>
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Referencia
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Monto
                                            </th>
                                            <th width="15%"><a class="text-center font-weight-bold text-info"
                                                            href="#"
                                                            @click.prevent="clickAddPayment">[+ Agregar]</a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.payments"
                                            :key="index">
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_method_type_id"
                                                            @change="changePaymentMethodType(index)">
                                                        <el-option v-for="option in cashPaymentMethod"
                                                                :key="option.id"
                                                                :label="option.description"
                                                                :value="option.id"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_destination_id"
                                                            filterable>
                                                        <el-option v-for="option in payment_destinations"
                                                                :key="option.id"
                                                                :label="option.description"
                                                                :value="option.id"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-input v-model="row.reference"></el-input>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-input v-model="row.payment"></el-input>
                                                </div>
                                            </td>
                                            <td class="series-table-actions text-center">
                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickCancel(index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <br>
                                        </tr>
                                        </tbody>
                                    </table>
                                </template>

                                <!-- Credito -->
                                <template v-else-if="form.payment_condition_id === '02'">
                                    <table v-if="form.fee.length>0">
                                        <thead>
                                        <tr width="100%">
                                            <th class="pb-2" v-if="form.fee.length>0"
                                                >Método de pago
                                            </th>
                                            <th class="pb-2"
                                                >Fecha
                                            </th>
                                            <th class="pb-2"
                                                >Monto
                                            </th>
                                            <th class="pb-2" ></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.fee"
                                            :key="index">
                                            <td>
                                                <el-select
                                                    v-model="row.payment_method_type_id"
                                                    @change="changePaymentMethodType(index)">
                                                    <el-option
                                                        v-for="option in creditPaymentMethod"
                                                        :key="option.id"
                                                        :label="option.description"
                                                        :value="option.id"
                                                    ></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-date-picker
                                                    v-model="row.date"
                                                    :clearable="false"
                                                    format="dd/MM/yyyy"
                                                    type="date"
                                                    :readonly="readonly_date_of_due"
                                                    value-format="yyyy-MM-dd"></el-date-picker>
                                            </td>
                                            <td>
                                                <el-input v-model="row.amount"></el-input>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </template>

                                <!-- Crédito con cuotas -->
                                <template v-else>
                                    <table v-if="form.fee.length > 0">
                                        <thead>
                                            <tr width="100%">
                                                <th class="pb-2" >Fecha
                                                </th>
                                                <th class="pb-2" >Monto
                                                </th>
                                                <th class="pb-2" ></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in form.fee"
                                                :key="index">
                                                <td>
                                                    <el-date-picker v-model="row.date"
                                                                    :clearable="false"
                                                                    format="dd/MM/yyyy"
                                                                    type="date"
                                                                    value-format="yyyy-MM-dd"></el-date-picker>
                                                </td>
                                                <td>
                                                    <el-input v-model="row.amount"></el-input>
                                                </td>
                                                <td class="text-center">
                                                    <button v-if="index > 0"
                                                            class="btn waves-effect waves-light btn-xs btn-danger"
                                                            type="button"
                                                            @click.prevent="clickRemoveFee(index)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <label class="control-label">
                                                        <a class=""
                                                            href="#"
                                                            @click.prevent="clickAddFee"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                            <span style="color: #777777">Agregar cuota</span></a>
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </template>
                    </div>
                    <div class="row">

                        <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
                            <div class="form-group">
                                <button class="btn waves-effect waves-light btn-primary"
                                        type="button"
                                        @click.prevent="showDialogAddItem = true">+ Agregar Producto
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="form.items !== undefined && form.items.length > 0"
                         class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Almacén</th>
                                        <th>Lote</th>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Valor Unitario</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Descuento</th>
                                        <th class="text-right">Cargo</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items"
                                        :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{
                                                row.item.description
                                            }}<br/><small>{{ row.affectation_igv_type.description }}</small></td>
                                        <td class="text-left">{{ row.warehouse_description }}</td>
                                        <td class="text-left">{{ row.lot_code }}</td>
                                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                                        <td class="text-right">{{ row.quantity }}</td>
                                        <td class="text-right">{{ currency_type.symbol }}
                                                               {{ getFormatUnitPriceRow(row.unit_value) }}
                                        </td>
                                        <td class="text-right">{{ currency_type.symbol }}
                                                               {{ getFormatUnitPriceRow(row.unit_price) }}
                                        </td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_discount }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                        <td class="text-right">
                                            <button v-if="purchase_order_id && row.item.series_enabled"
                                                    class="btn waves-effect waves-light btn-xs btn-info"
                                                    type="button"
                                                    @click.prevent="clickOpenSeries(index, row.quantity, row.lots)">
                                                Series
                                            </button>
                                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                    type="button"
                                                    @click.prevent="clickRemoveItem(index)">x
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p v-if="form.total_exportation > 0"
                               class="text-right">OP.EXPORTACIÓN: {{ currency_type.symbol }}
                                                                 {{ form.total_exportation }}</p>
                            <p v-if="form.total_free > 0"
                               class="text-right">OP.GRATUITAS: {{ currency_type.symbol }} {{
                                    form.total_free
                                                          }}</p>
                            <p v-if="form.total_unaffected > 0"
                               class="text-right">OP.INAFECTAS: {{ currency_type.symbol }}
                                                                {{ form.total_unaffected }}</p>
                            <p v-if="form.total_exonerated > 0"
                               class="text-right">OP.EXONERADAS: {{ currency_type.symbol }}
                                                                {{ form.total_exonerated }}</p>
                            <p v-if="form.total_taxed > 0"
                               class="text-right">OP.GRAVADA: {{ currency_type.symbol }} {{
                                    form.total_taxed
                                                           }}</p>
                            <p v-if="form.total_igv > 0"
                               class="text-right">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                               
                            <p v-if="form.total_isc > 0"
                               class="text-right">ISC: {{ currency_type.symbol }} {{ form.total_isc }}</p>

                            <h3 v-if="form.total > 0"
                                class="text-right"><b>TOTAL COMPRAS: </b>{{ currency_type.symbol }} {{ form.total }}
                            </h3>

                            <template v-if="is_perception_agent">
                                <hr>
                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">NÚMERO PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div :class="{'has-danger': errors.perception_number}"
                                             class="form-group">
                                            <el-input v-model="form.perception_number"></el-input>

                                            <small v-if="errors.perception_number"
                                                   class="form-control-feedback"
                                                   v-text="errors.perception_number[0]"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">FEC EMISIÓN PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div :class="{'has-danger': errors.perception_date}"
                                             class="form-group">
                                            <el-date-picker v-model="form.perception_date"
                                                            :clearable="false"
                                                            type="date"
                                                            value-format="yyyy-MM-dd"
                                                            @change="changeDateOfIssue"></el-date-picker>
                                            <small v-if="errors.perception_date"
                                                   class="form-control-feedback"
                                                   v-text="errors.perception_date[0]"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">IMPORTE PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div :class="{'has-danger': errors.total_perception}"
                                             class="form-group">
                                            <el-input v-model="form.total_perception"
                                                      :readonly="true"
                                                      @input="inputTotalPerception"></el-input>

                                            <small v-if="errors.total_perception"
                                                   class="form-control-feedback"
                                                   v-text="errors.total_perception[0]"></small>
                                        </div>
                                    </div>
                                </div>
                                <h3 v-if="form.total > 0 && !hide_button"
                                    class="text-right"><b>MONTO TOTAL : </b>{{
                                        currency_type.symbol
                                                                                               }} {{ total_amount }}
                                </h3>


                            </template>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button v-if="form.items !== undefined && form.items.length > 0 && !hide_button"
                               :loading="loading_submit"
                               native-type="submit"
                               type="primary">Generar
                    </el-button>
                </div>
            </form>
        </div>

        <purchase-form-item :currency-type-id-active="form.currency_type_id"
                            :exchange-rate-sale="form.exchange_rate_sale"
                            :showDialog.sync="showDialogAddItem"
                            :localHasGlobalIgv="localHasGlobalIgv"
                            @add="addRow"></purchase-form-item>

        <person-form :external="true"
                     :input_person="input_person"
                     :showDialog.sync="showDialogNewPerson"
                     type="suppliers"></person-form>

        <purchase-options :recordId="purchaseNewId"
                          :showClose="false"
                          :showDialog.sync="showDialogOptions"></purchase-options>

        <series-form
            ref="series_form"
            @addRowLot="addRowLot">
        </series-form>

    </div>
</template>

<script>

import PurchaseFormItem from './partials/item.vue'
import PersonForm from '../persons/form.vue'
import PurchaseOptions from './partials/options.vue'
import {exchangeRate, functions, fnPaymentsFee} from '../../../mixins/functions'
import {calculateRowItem} from '../../../helpers/functions'
import SeriesForm from './partials/series'
import {mapActions, mapState} from "vuex";

export default {
    props: ['purchase_order_id'],
    components: {PurchaseFormItem, PersonForm, PurchaseOptions, SeriesForm},
    mixins: [functions, exchangeRate, fnPaymentsFee],
    computed: {
        ...mapState([
            'config',
            'establishment',
            'hasGlobalIgv',
        ]),
        creditPaymentMethod: function () {
            return _.filter(this.payment_method_types, {'is_credit': true})
        },
        cashPaymentMethod: function () {
            return _.filter(this.payment_method_types, {'is_credit': false})
        },
        isCreditPaymentCondition: function () {
            return ['02', '03'].includes(this.form.payment_condition_id)
        },
    },
    data() {
        return {
            input_person: {},
            resource: 'purchases',
            showDialogAddItem: false,
            readonly_date_of_due: false,
            localHasGlobalIgv: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            hide_button: false,
            is_perception_agent: false,
            errors: {},
            form: {
                items:[]
            },
            aux_supplier_id: null,
            total_amount: 0,
            purchase_order_data: [],
            document_types: [],
            currency_types: [],
            discount_types: [],
            charges_types: [],
            payment_method_types: [],
            all_suppliers: [],
            suppliers: [],
            all_customers: [],
            customers: [],
            company: null,
            operation_types: [],
            all_series: [],
            series: [],
            payment_destinations: [],
            payment_conditions: [],
            currency_type: {},
            loading_search: false,
            purchaseNewId: null,
            showDialogLots: false,
        }
    },
    mounted() {
        this.initForm()
        this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                let data = response.data
                this.document_types = data.document_types_invoice
                this.currency_types = data.currency_types
                this.payment_conditions = data.payment_conditions
                // this.establishment = data.establishment


                this.all_suppliers = data.suppliers
                this.discount_types = data.discount_types
                this.payment_method_types = data.payment_method_types
                this.payment_destinations = data.payment_destinations
                this.all_customers = data.customers

                this.charges_types = data.charges_types
                this.$store.commit('setConfiguration', data.configuration);
                this.$store.commit('setEstablishment', data.establishment);
                this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                this.form.establishment_id = (this.establishment.id) ? this.establishment.id : null
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null


            })
            .then(() => {
                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeCurrencyType()
            })
        this.$eventHub.$on('reloadDataPersons', (supplier_id) => {
            this.reloadDataSuppliers(supplier_id)
        })
        this.$eventHub.$on('initInputPerson', () => {
            this.initInputPerson()
        })

        this.filterCustomers()
        this.isGeneratePurchaseOrder()
        this.changeHasPayment()
        this.changeHasClient()
    },
    created() {
        this.loadConfiguration()
        this.loadHasGlobalIgv()
        this.loadEstablishment()
        this.searchPurchaseOrder();
        // this.localHasGlobalIgv = this.hasGlobalIgv;
        this.initGlobalIgv()
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'loadEstablishment',
            'loadHasGlobalIgv',
        ]),
        changeHasGlobalIgv() {
            // if(this.form.items.length < 1 && this.config.enabled_global_igv_to_purchase === true) {
            //     this.$store.commit('sethasGlobalIgv', !this.hasGlobalIgv);
            //  this.loadHasGlobalIgv()
            // }
            // this.localHasGlobalIgv = this.hasGlobalIgv;

        },
        changeHasPayment() {

            if (!this.form.has_payment) {
                this.form.payments = []
                this.form.fee = []
                this.form.payment_condition_id = '01'

            }else{
                this.changePaymentCondition()
            }
        },
        changeHasClient() {

            if (!this.form.has_client) {
                this.form.customer_id = null
            }
        },
        searchRemotePersons(input) {

            if (input.length > 1) {

                this.loading_search = true
                let parameters = `input=${input}`

                this.$http.get(`/reports/data-table/persons/customers?${parameters}`)
                    .then(response => {
                        this.customers = response.data.persons
                        this.loading_search = false

                        if (this.customers.length == 0) {
                            this.filterCustomers()
                        }
                    })
            } else {
                this.filterCustomers()
            }

        },
        filterCustomers() {
            this.customers = this.all_customers
        },
        getFormatUnitPriceRow(unit_price) {
            return _.round(unit_price, 6)
            // return unit_price.toFixed(6)
        },
        async isGeneratePurchaseOrder() {
            if (this.purchase_order_id) {

                await this.$http.get(`/purchase-orders/record/${this.purchase_order_id}`)
                    .then(response => {
                        let purchase_order = response.data.data.purchase_order
                        let warehouse = response.data.data.warehouse
                        let supp = purchase_order.supplier

                        if (supp.identity_document_type_id == 6) {
                            this.form.document_type_id = "01"
                        } else if (supp.identity_document_type_id == 1) {
                            this.form.document_type_id = "03"
                        }

                        this.form.items = response.data.data.purchase_order.items
                        this.form.supplier_id = purchase_order.supplier_id
                        this.form.currency_type_id = purchase_order.currency_type_id
                        this.form.purchase_order_id = purchase_order.id
                        this.form.payments[0].payment_method_type_id = purchase_order.payment_method_type_id
                        this.form.payments[0].payment = purchase_order.total
                        this.form.total = purchase_order.total
                        this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})

                        this.form.items.forEach((it) => {
                            it.warehouse_id = warehouse.id
                            it.charges = it.charges ? Object.values(it.charges) : []
                            it.attributes = it.attributes ? Object.values(it.attributes) : []
                            it.discounts = it.discounts ? Object.values(it.discounts) : []
                            it.lots = it.item.lots ? it.item.lots : []
                        })
                        // this.changeDocumentType()

                    })

            }
        },
        async validate_payments() {

            let error_by_item = 0
            let acum_total = 0
            let q_affectation_free = 0

            await this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment)
                if (item.payment <= 0 || item.payment == null) error_by_item++;
            })

            //determinate affectation igv
            await this.form.items.forEach((item) => {
                if (item.affectation_igv_type.free) {
                    q_affectation_free++
                }
            })

            let all_free = (q_affectation_free == this.form.items.length) ? true : false

            if (!all_free && (acum_total > parseFloat(this.form.total) || error_by_item > 0)) {
                return {
                    success: false,
                    message: 'Los montos ingresados superan al monto a pagar o son incorrectos'
                }
            }

            if (this.form.has_client && !this.form.customer_id) {
                return {
                    success: false,
                    message: 'Debe seleccionar un cliente'
                }
            }

            if (this.form.has_payment) {

                if(this.form.payment_condition_id === '01' && this.form.payments.length == 0){

                    return {
                        success: false,
                        message: 'Debe registrar al menos un pago'
                    }

                }

                if(this.isCreditPaymentCondition && this.form.fee.length == 0){

                    return {
                        success: false,
                        message: 'Debe registrar al menos una cuota'
                    }

                }
            }

            return {
                success: true,
                message: null
            }
        },
        clickCancel(index) {
            this.form.payments.splice(index, 1);
            this.calculatePayments()
        },
        clickAddPayment() {

            this.form.payments.push({
                id: null,
                purchase_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: '01',
                reference: null,
                payment_destination_id: this.getPaymentDestinationId(),
                payment: 0,
            });

            this.calculatePayments()

        },
        getPaymentDestinationId() {

            if (this.config.destination_sale && this.payment_destinations.length > 0) {

                let cash = _.find(this.payment_destinations, {id: 'cash'})

                return (cash) ? cash.id : this.payment_destinations[0].id

            }

            return null

        },

        initInputPerson() {
            this.input_person = {
                number: '',
                identity_document_type_id: ''
            }
        },
        keyupEnterSupplier() {

            if (this.input_person.number) {

                if (!isNaN(parseInt(this.input_person.number))) {

                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = '1'
                            this.showDialogNewPerson = true
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                        default:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                    }
                }
            }
        },
        keyupSupplier(e) {

            if (e.key !== "Enter") {

                this.input_person.number = this.$refs.select_person.$el.getElementsByTagName('input')[0].value
                let exist_persons = this.suppliers.filter((supplier) => {
                    let pos = supplier.description.search(this.input_person.number);
                    return (pos > -1)
                })

                this.input_person.number = (exist_persons.length == 0) ? this.input_person.number : null
            }

        },
        inputSeries() {

            const pattern = new RegExp('^[A-Z0-9]+$', 'i');
            if (!pattern.test(this.form.series)) {
                this.form.series = this.form.series.substring(0, this.form.series.length - 1);
            } else {
                this.form.series = this.form.series.toUpperCase()
            }

        },
        changePaymentMethodType(index) {

            let id = '01'

            if (this.form.payments.length > 0) {
                id = this.form.payments[index].payment_method_type_id
            } else if (this.form.fee.length > 0) {
                id = this.form.fee[index].payment_method_type_id
            }

            let payment_method_type = _.find(this.payment_method_types, {'id': id})

            if (payment_method_type.number_days) {

                this.form.date_of_due = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                this.readonly_date_of_due = true

                let date = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')

                if (this.form.fee.length > 0) {
                    for (let index = 0; index < this.form.fee.length; index++) {
                        this.form.fee[index].date = date
                    }
                }

            } else {

                this.form.date_of_due = this.form.date_of_issue
                this.readonly_date_of_due = false

            }

        },
        inputTotalPerception() {
            this.total_amount = parseFloat(this.form.total) + parseFloat(this.form.total_perception)
            if (isNaN(this.total_amount)) {
                this.hide_button = true
            } else {
                this.hide_button = false

            }
        },
        changeSupplier() {
            this.calculatePerception()
        },
        filterSuppliers() {

            if (this.form.document_type_id === '01') {
                // this.suppliers = _.filter(this.all_suppliers, {'identity_document_type_id': '6'})
                this.suppliers = _.filter(this.all_suppliers, (item) => {
                    return ['6', '0'].includes(item.identity_document_type_id)
                })
                this.selectSupplier()

            } else {
                this.suppliers = this.all_suppliers  //_.filter(this.all_suppliers, (c) => { return c.identity_document_type_id !== '6' })
                this.selectSupplier()
            }
        },
        selectSupplier() {

            let supplier = _.find(this.suppliers, {'id': this.aux_supplier_id})
            this.form.supplier_id = (supplier) ? supplier.id : null
            this.aux_supplier_id = null

        },
        initForm() {
            this.errors = {}
            this.form = {
                establishment_id: null,
                document_type_id: null,
                series: null,
                number: null,
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                supplier_id: null,
                payment_method_type_id: '01',
                currency_type_id: null,
                purchase_order: null,
                exchange_rate_sale: 0,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                perception_date: null,
                perception_number: null,
                total_perception: 0,
                date_of_due: moment().format('YYYY-MM-DD'),
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                payments: [],
                customer_id: null,
                has_client: false,
                has_payment: false,
                payment_condition_id: '01',
                fee: [],

            }

            // this.clickAddPayment()

            this.initInputPerson()

            this.readonly_date_of_due = false

            this.initGlobalIgv()

        },
        initGlobalIgv(){
            this.localHasGlobalIgv = this.config.checked_global_igv_to_purchase
            // this.changeHasGlobalIgv()
        },
        resetForm() {
            this.initForm()
            this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
            this.form.establishment_id = this.establishment.id
            this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null

            this.changeDateOfIssue()
            this.changeDocumentType()
            this.changeCurrencyType()
        },
        changePaymentCondition(){

            this.form.fee = []
            this.form.payments = []

            if (this.form.payment_condition_id === '01') {

                this.clickAddPayment()
                this.initDataPaymentCondition()

            }
            if (this.form.payment_condition_id === '02') {
                this.clickAddFeeNew()
                this.readonly_date_of_due = true
            }
            if (this.form.payment_condition_id === '03') {
                this.clickAddFee()
                this.initDataPaymentCondition()
            }

        },
        changeDateOfIssue() {
            this.form.date_of_due = this.form.date_of_issue
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            })
        },
        changeDocumentType() {
            this.filterSuppliers()
        },
        addRow(row) {
            this.form.items.push(row)
            this.calculateTotal()
        },
        clickRemoveItem(index) {
            this.form.items.splice(index, 1)
            this.calculateTotal()
        },
        changeCurrencyType() {
            this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
            let items = []
            this.form.items.forEach((row) => {
                items.push(calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale))
            });
            this.form.items = items
            this.calculateTotal()
        },
        calculateTotal() {
            let total_discount = 0
            let total_charge = 0
            let total_exportation = 0
            let total_taxed = 0
            let total_exonerated = 0
            let total_unaffected = 0
            let total_free = 0
            let total_igv = 0
            let total_value = 0
            let total = 0
            let total_base_isc = 0
            let total_isc = 0

            this.form.items.forEach((row) => {
                total_discount += parseFloat(row.total_discount)
                total_charge += parseFloat(row.total_charge)

                if (row.affectation_igv_type_id === '10') {
                    total_taxed += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '20') {
                    total_exonerated += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '30') {
                    total_unaffected += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '40') {
                    total_exportation += parseFloat(row.total_value)
                }
                if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value)
                }

                total_value += parseFloat(row.total_value)
                total_igv += parseFloat(row.total_igv)
                total += parseFloat(row.total)
                
                // isc
                total_isc += parseFloat(row.total_isc)
                total_base_isc += parseFloat(row.total_base_isc)

            });

            // isc
            this.form.total_base_isc = _.round(total_base_isc, 2)
            this.form.total_isc = _.round(total_isc, 2)

            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            // this.form.total_taxes = _.round(total_igv, 2)
            
            //impuestos (isc + igv)
            this.form.total_taxes = _.round(total_igv + total_isc, 2)

            this.form.total = _.round(total, 2)

            this.calculatePerception()

            // this.form.payments[0].payment = this.form.total
            // this.setTotalDefaultPayment()
            this.calculatePayments()
            this.calculateFee()

        },
        setTotalDefaultPayment() {

            if (this.form.payments.length > 0) {

                this.form.payments[0].payment = this.form.total
            }
        },
        calculatePerception() {

            let supplier = _.find(this.all_suppliers, {'id': this.form.supplier_id})

            if (supplier) {

                if (supplier.perception_agent) {

                    let total_perception = 0
                    let quantity_item_perception = 0
                    let total_amount = 0
                    this.form.total_perception = 0

                    this.form.perception_date = moment().format('YYYY-MM-DD')

                    this.form.items.forEach((row) => {
                        quantity_item_perception += (row.item.has_perception) ? 1 : 0
                        total_perception += (row.item.has_perception) ? (parseFloat(row.unit_price) * parseFloat(row.quantity) * (parseFloat(row.item.percentage_perception) / 100)) : 0
                    });

                    this.is_perception_agent = (quantity_item_perception > 0) ? true : false
                    this.form.total_perception = _.round(total_perception, 2)
                    total_amount = this.form.total + parseFloat(this.form.total_perception)
                    this.total_amount = _.round(total_amount, 2)

                } else {

                    this.is_perception_agent = false
                    this.form.perception_date = null
                    this.form.perception_number = null
                    this.form.total_perception = null

                }
            }
        },
        validatePaymentDestination() {

            let error_by_item = 0

            this.form.payments.forEach((item) => {
                if (item.payment_destination_id == null) error_by_item++;
            })

            return {
                error_by_item: error_by_item,
            }

        },
        async submit() {
            let validate_item_series = await this.validationItemSeries()
            if (!validate_item_series.success) {
                return this.$message.error(validate_item_series.message);
            }

            let validate = await this.validate_payments()
            if (!validate.success) {
                return this.$message.error(validate.message);
            }

            let validate_payment_destination = await this.validatePaymentDestination()

            if (validate_payment_destination.error_by_item > 0) {
                return this.$message.error('El destino del pago es obligatorio');
            }

            this.loading_submit = true
            // await this.changePaymentMethodType(false)
            await this.$http.post(`/${this.resource}`, this.form)
                .then(response => {

                    if (response.data.success) {

                        if (this.purchase_order_id) {

                            this.$message({
                                showClose: true,
                                message: `Compra registrada : ${response.data.data.number_full}`,
                                duration: 2 * 3000,
                                type: "success"
                            });

                            this.close()

                        } else {

                            this.resetForm()
                            this.purchaseNewId = response.data.data.id
                            this.showDialogOptions = true

                        }

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
            location.href = '/purchases'
        },
        reloadDataSuppliers(supplier_id) {

            this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {

                this.aux_supplier_id = supplier_id
                this.all_suppliers = response.data
                this.filterSuppliers()

            })
        },
        clickOpenSeries(ind, qt, lt) {
            this.$refs.series_form.openDialog(ind, qt, lt)
        },
        addRowLot({lots, indexItem}) {
            this.form.items[indexItem].lots = lots
        },
        async validationItemSeries() {
            let error = 0

            await this.form.items.forEach((element) => {

                if (element.item.series_enabled) {
                    const count_lot = element.lots ? element.lots.length : 0
                    if (element.quantity != count_lot) {
                        error++;
                    }
                }
            })

            if (error > 0)
                return {success: false, message: 'Las series y la cantidad en los productos deben ser iguales.'}


            return {success: true, message: ''}
        },

        async searchPurchaseOrder(input){
            if(this.purchase_order_id !== null) return false;
            this.loading = true
            await this.$http
                .post(`/${this.resource}/search/purchase_order`,{input})
                .then((response) => {
                    this.purchase_order_data = response.data
                })
                .catch(error => {
                    console.error(error)

                })
                .finally(() => {
                    this.loading = false
                })


        },
    }
}
</script>
