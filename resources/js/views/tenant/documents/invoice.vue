<template>
    <div>
        <Keypress
            key-event="keyup"

            @success="checkKey"
        />
        <Keypress key-event="keyup" :multiple-keys="multiple" @success="checkKeyWithAlt" />

        <div v-if="loading_form">
            <form autocomplete="off"
                  class="row no-gutters"
                  @submit.prevent="submit">
                <div class="col-xl-9 col-md-9 col-12">
                    <div class="row card-header no-gutters align-items-start"
                         style="background-color: #FFFFFF !important;">
                        <div class="col-xl-2 col-md-2 col-12">
                            <logo :path_logo="(company.logo != null) ? `/storage/uploads/logos/${company.logo}` : ''"
                                  :position_class="'text-left'"
                                  url="/"></logo>
                        </div>
                        <div class="col-xl-6 col-md-6 col-12 pl-2 align-self-center">
                            <address class="mb-0"
                                     style="line-height: initial;">
                                <span class="font-weight-bold">{{ company.name }}</span>
                                <br>
                                <span v-if="establishment.address != '-'">{{ establishment.address }} </span>
                                <br>
                                <span v-if="establishment.email != '-'">{{
                                        establishment.email
                                                                        }} </span><span v-if="establishment.telephone != '-'">- {{
                                    establishment.telephone
                                                                                                                              }}</span>
                            </address>
                        </div>
                        <div class="col-xl-4 col-md-4 col-12 align-self-end">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-6 align-self-end">
                                        <div :class="{'has-danger': errors.date_of_issue}"
                                             class="form-group">
                                            <label class="control-label">Fec. Emisión</label>
                                            <el-date-picker v-model="form.date_of_issue"
                                                            :clearable="false"
                                                            :picker-options="datEmision"
                                                            :readonly="readonly_date_of_due"
                                                            type="date"
                                                            value-format="yyyy-MM-dd"
                                                            @change="changeDateOfIssue"></el-date-picker>
                                            <small v-if="errors.date_of_issue"
                                                   class="form-control-feedback"
                                                   v-text="errors.date_of_issue[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 align-self-end">
                                        <div :class="{'has-danger': errors.date_of_due}"
                                             class="form-group">
                                            <label class="control-label">Fec. Vencimiento</label>
                                            <el-date-picker v-model="form.date_of_due"
                                                            :clearable="false"
                                                            :readonly="readonly_date_of_due"
                                                            type="date"
                                                            value-format="yyyy-MM-dd"></el-date-picker>
                                            <small v-if="errors.date_of_due"
                                                   class="form-control-feedback"
                                                   v-text="errors.date_of_due[0]"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body no-gutters">
                        <div class="row">
                            <div class="col-lg-4 align-self-end">
                                <div :class="{'has-danger': errors.document_type_id}"
                                     class="form-group">
                                    <label class="control-label font-weight-bold text-info">Tipo comprobante</label>
                                    <el-select v-model="form.document_type_id"
                                               class="border-left rounded-left border-info"
                                               dusk="document_type_id"
                                               popper-class="el-select-document_type"
                                               @change="changeDocumentType"
                                               :disabled="isUpdateDocument">
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
                            <div class="col-lg-2 d-none">
                                <div :class="{'has-danger': errors.establishment_id}"
                                     class="form-group">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id"
                                               @change="changeEstablishment">
                                        <el-option v-for="option in establishments"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.establishment_id"
                                           class="form-control-feedback"
                                           v-text="errors.establishment_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2 align-self-end">
                                <div :class="{'has-danger': errors.series_id}"
                                     class="form-group">
                                    <label class="control-label">Serie</label>
                                    <el-select v-model="form.series_id">
                                        <el-option v-for="option in series"
                                                   :key="option.id"
                                                   :label="option.number"
                                                   :disabled="option.disabled"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.series_id"
                                           class="form-control-feedback"
                                           v-text="errors.series_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2 align-self-end">
                                <div :class="{'has-danger': errors.operation_type_id}"
                                     class="form-group">
                                    <label class="control-label">Tipo Operación
                                        <template v-if="(form.operation_type_id == '1001' || form.operation_type_id == '1004') && has_data_detraction">
                                            <a class="text-center font-weight-bold text-info"
                                               href="#"
                                               @click.prevent="showDialogDocumentDetraction = true"> [+ Ver datos]</a>
                                        </template>

                                    </label>
                                    <el-select v-model="form.operation_type_id"
                                               @change="changeOperationType">
                                        <el-option v-for="option in operation_types"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.operation_type_id"
                                           class="form-control-feedback"
                                           v-text="errors.operation_type_id[0]"></small>
                                </div>
                            </div>
                            <div class="col-lg-2 align-self-end">
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
                            <div class="col-lg-2 align-self-end">
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
                                    <!-- <el-input :disabled="isUpdate" v-model="form.exchange_rate_sale"></el-input> -->
                                    <small v-if="errors.exchange_rate_sale"
                                           class="form-control-feedback"
                                           v-text="errors.exchange_rate_sale[0]"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top no-gutters">
                        <div class="row">
                            <div :class="{'has-danger': errors.customer_id}"
                                 class="form-group col-sm-6 mb-0">
                                <label class="control-label font-weight-bold text-info">
                                    Cliente
                                    <a href="#"
                                       @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select v-model="form.customer_id"
                                           :loading="loading_search"
                                           :remote-method="searchRemoteCustomers"
                                           class="border-left rounded-left border-info"
                                           dusk="customer_id"
                                           filterable

                                           @focus="focus_on_client = true"
                                           @blur="focus_on_client = false"
                                           placeholder="Escriba el nombre o número de documento del cliente"
                                           popper-class="el-select-customers"
                                           remote
                                           @change="changeCustomer"
                                           @keyup.enter.native="keyupCustomer">

                                    <el-option v-for="option in customers"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>

                                </el-select>
                                <small v-if="errors.customer_id"
                                       class="form-control-feedback"
                                       v-text="errors.customer_id[0]"></small>
                            </div>
                            <div v-if="customer_addresses.length > 0"
                                 class="form-group col-sm-6 mb-0">
                                <label class="control-label font-weight-bold text-info">Dirección</label>
                                <el-select v-model="form.customer_address_id">
                                    <el-option v-for="option in customer_addresses"
                                               :key="option.id"
                                               :label="option.address"
                                               :value="option.id"></el-option>
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top no-gutters p-0">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th class="font-weight-bold"
                                        width="30%">Descripción
                                    </th>
                                    <th class="text-center font-weight-bold">Unidad</th>
                                    <th class="text-right font-weight-bold">Cantidad</th>
                                    <th class="text-right font-weight-bold">Valor Unitario</th>
                                    <th class="text-right font-weight-bold">Precio Unitario</th>
                                    <th class="text-right font-weight-bold">Subtotal</th>
                                    <!--<th class="text-right font-weight-bold">Cargo</th>-->
                                    <th class="text-right font-weight-bold">Total</th>
                                    <th width="8%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.items"
                                    :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ setDescriptionOfItem(row.item) }}
                                        {{
                                        row.item.presentation.hasOwnProperty('description') ?
                                        row.item.presentation.description : ''
                                        }}

                                        <template v-if="row.total_plastic_bag_taxes > 0">
                                            <br/><small>ICBPER: {{ currency_type.symbol }} {{ row.total_plastic_bag_taxes }}</small>
                                        </template>

                                        <br/><small>{{ row.affectation_igv_type.description }}</small>
                                    </td>
                                    <td class="text-center">{{ row.item.unit_type_id }}</td>

                                    <td class="text-right">{{ row.quantity }}</td>

                                    <td class="text-right">{{ currency_type.symbol }}
                                                           {{ getFormatUnitPriceRow(row.unit_value) }}
                                    </td>
                                    <td class="text-right">{{ currency_type.symbol }}
                                                           {{ getFormatUnitPriceRow(row.unit_price) }}
                                    </td>


                                    <td class="text-right">{{ currency_type.symbol }} {{ row.total_value }}</td>
                                    <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                    <td class="text-right">
                                        <template v-if="config.change_free_affectation_igv">
                                            <el-tooltip class="item"
                                                        content="Modificar afectación Gravado – Bonificaciones"
                                                        effect="dark"
                                                        placement="top-start">
                                                <el-checkbox v-model="row.item.change_free_affectation_igv"
                                                             @change="changeRowFreeAffectationIgv(row, index)"></el-checkbox>
                                            </el-tooltip>
                                        </template>

                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="clickRemoveItem(index)"><i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn waves-effect waves-light btn-xs btn-info"
                                                type="button"
                                                @click="ediItem(row, index)">
                                            <span style='font-size:10px;'>&#9998;</span></button>

                                    </td>
                                </tr>
                                <!-- @todo: Mejorar evitando duplicar codigo -->
                                <!-- Ocultar en cel -->
                                <tr>

                                    <td class="text-center pt-3"
                                        colspan="2">
                                        <el-popover
                                            placement="top-start"
                                            :open-delay="1000"
                                            width="145"
                                            trigger="hover"
                                            content="Presiona F2">
                                            <el-button slot="reference"
                                                       class="btn waves-effect waves-light btn-primary btn-sm hidden-sm-down"
                                                       style="width: 180px;"
                                                       type="button"
                                                       @click.prevent="clickAddItemInvoice">
                                                + Agregar Producto
                                            </el-button>
                                        </el-popover>
                                    </td>
                                    <td colspan="2"></td>
                                    <td class="p-0"
                                        colspan="5">
                                        <div class="row table-responsive">
                                            <table class="table-sm text-right hidden-sm-down"
                                                   style="width: 100%;">
                                                <tr v-if="form.total > 0 && enabled_discount_global">
                                                    <td>
                                                        <el-tooltip class="item"
                                                            :content="global_discount_type.description"
                                                            effect="dark"
                                                            placement="top">
                                                            <i class="fa fa-info-circle"></i>
                                                        </el-tooltip>

                                                        DESCUENTO
                                                        <template v-if="is_amount"> MONTO</template>
                                                        <template v-else> %</template>
                                                        <el-checkbox v-model="is_amount"
                                                                     class="ml-1 mr-1"
                                                                     @change="changeTypeDiscount"></el-checkbox>
                                                        :
                                                    </td>
                                                    <td>

                                                        <el-input-number v-model="total_global_discount"
                                                                         :min="0"
                                                                         class="input-custom"
                                                                         controls-position="right"
                                                                         @change="changeTotalGlobalDiscount"></el-input-number>

                                                        <!-- <el-input v-model="total_global_discount"
                                                                  class="input-custom"
                                                                  @input="calculateTotal"></el-input> -->
                                                    </td>
                                                </tr>

                                                <template v-if="form.detraction">
                                                    <tr v-if="form.detraction.amount > 0">
                                                        <td width="60%">M. DETRACCIÓN:</td>
                                                        <td>S/ {{ form.detraction.amount }}</td>
                                                        <!-- <td>{{ currency_type.symbol }} {{ form.detraction.amount }}</td> -->
                                                    </tr>
                                                </template>

                                                <template v-if="form.retention">
                                                    <tr v-if="form.retention.amount > 0">
                                                        <td>M. RETENCIÓN ({{form.retention.percentage * 100}}%):</td>
                                                        <td>{{ currency_type.symbol }} {{ form.retention.amount }}</td>
                                                    </tr>
                                                </template>

                                                <tr v-if="form.total_exportation > 0">
                                                    <td>OP.EXPORTACIÓN:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_exportation }}</td>
                                                </tr>
                                                <tr v-if="form.total_free > 0">
                                                    <td>OP.GRATUITAS:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_free }}</td>
                                                </tr>
                                                <tr v-if="form.total_unaffected > 0">
                                                    <td>OP.INAFECTAS:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_unaffected }}</td>
                                                </tr>
                                                <tr v-if="form.total_exonerated > 0">
                                                    <td>OP.EXONERADAS:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_exonerated }}</td>
                                                </tr>
                                                <tr v-if="form.total_taxed > 0">
                                                    <td>OP.GRAVADA:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_taxed }}</td>
                                                </tr>
                                                <tr v-if="form.total_prepayment > 0">
                                                    <td>ANTICIPOS:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_discount }}</td>
                                                    <!-- <td>{{ currency_type.symbol }} {{ form.total_prepayment }}</td> -->
                                                </tr>
                                                <tr v-if="form.total_igv > 0">
                                                    <td>IGV:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_igv }}</td>
                                                </tr>
                                                <tr v-if="form.total_isc > 0">
                                                    <td>ISC:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_isc }}</td>
                                                </tr>
                                                <tr v-if="form.total_plastic_bag_taxes > 0">
                                                    <td>ICBPER:</td>
                                                    <td>{{ currency_type.symbol }} {{
                                                        form.total_plastic_bag_taxes
                                                        }}
                                                    </td>
                                                </tr>

                                                <tr v-if="form.subtotal > 0 && form.total_discount > 0">
                                                    <td>SUBTOTAL:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.subtotal }}</td>
                                                </tr>

                                                <tr v-if="form.total_discount > 0">
                                                    <td>DESCUENTOS TOTALES:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total_discount }}</td>
                                                </tr>

                                                <tr v-if="form.total > 0">
                                                    <td>OTROS CARGOS:</td>
                                                    <td>{{ currency_type.symbol }}
                                                        <el-input-number v-model="total_global_charge"
                                                                         :disabled="config.active_allowance_charge == true ? true:false"
                                                                         :min="0"
                                                                         class="input-custom"
                                                                         controls-position="right"
                                                                         @change="calculateTotal"></el-input-number>
                                                    </td>
                                                </tr>

                                                <tr v-if="form.total > 0">
                                                    <td><strong>TOTAL A PAGAR</strong>:</td>
                                                    <td>{{ currency_type.symbol }} {{ form.total }}</td>
                                                </tr>

                                                <tr v-if="form.total > 0">
                                                    <td>CONDICIÓN DE PAGO:</td>
                                                    <td>
                                                        <el-select v-model="form.payment_condition_id"
                                                                   dusk="document_type_id"
                                                                   popper-class="el-select-document_type"
                                                                   style="max-width: 200px;"
                                                                   @change="changePaymentCondition">
                                                            <el-option label="Crédito con cuotas"
                                                                       value="03"></el-option>
                                                            <el-option label="Crédito"
                                                                       value="02"></el-option>
                                                            <el-option label="Contado"
                                                                       value="01"></el-option>
                                                        </el-select>
                                                    </td>
                                                </tr>


                                                <!-- <template v-if="form.detraction">
                                                    <tr v-if="form.detraction.amount > 0 && form.total_pending_payment > 0">
                                                        <td width="60%">M. PENDIENTE:</td>
                                                        <td>{{ currency_type.symbol }} {{ form.total_pending_payment }}</td>
                                                    </tr>
                                                </template> -->

                                                <template v-if="form.detraction || form.retention">
                                                    <tr v-if="form.total_pending_payment > 0">
                                                        <!-- <tr v-if="form.detraction.amount > 0 && form.total_pending_payment > 0"> -->
                                                        <td>M. PENDIENTE:</td>
                                                        <td>{{ currency_type.symbol }} {{ form.total_pending_payment
                                                            }}
                                                        </td>
                                                    </tr>
                                                </template>


                                                <tr v-if="form.total > 0">
                                                    <!-- Metodos de pago -->
                                                    <td class="p-0"
                                                        colspan="2">
                                                        <!-- Crédito con cuotas -->
                                                        <div v-if="form.payment_condition_id === '03'" class="table-responsive">
                                                            <table v-if="form.fee.length>0"
                                                                   class="text-left table"
                                                                   width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th class="text-left"
                                                                        style="width: 100px">Fecha
                                                                    </th>
                                                                    <th class="text-left"
                                                                        style="width: 100px">Monto
                                                                    </th>
                                                                    <th style="width: 30px"></th>
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
                                                        </div>
                                                        <!-- Credito -->
                                                        <div v-if="form.payment_condition_id === '02'"  class="table-responsive">
                                                            <table v-if="form.fee.length>0"
                                                                   class="text-left table"
                                                                   width="100%">
                                                                <thead>
                                                                <tr>
                                                                    <th v-if="form.fee.length>0"
                                                                        style="width: 120px">Método de pago
                                                                    </th>
                                                                    <th class="text-left"
                                                                        style="width: 100px">Fecha
                                                                    </th>
                                                                    <th class="text-left"
                                                                        style="width: 100px">Monto
                                                                    </th>
                                                                    <th style="width: 30px"></th>
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
                                                                                v-for="option in credit_payment_metod"
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
                                                        </div>
                                                        <!-- Contado -->
                                                        <div v-if="!is_receivable && form.payment_condition_id === '01'"  class="table-responsive">
                                                            <table class="text-left table">
                                                                <thead>
                                                                <tr>
                                                                    <th v-if="form.payments.length>0"
                                                                        style="width: 120px">Método de pago
                                                                    </th>
                                                                    <template v-if="enabled_payments">
                                                                        <th v-if="form.payments.length>0"
                                                                            style="width: 120px">Destino
                                                                            <el-tooltip class="item"
                                                                                        content="Aperture caja o cuentas bancarias"
                                                                                        effect="dark"
                                                                                        placement="top-start">
                                                                                <i class="fa fa-info-circle"></i>
                                                                            </el-tooltip>
                                                                        </th>
                                                                        <th v-if="form.payments.length>0"
                                                                            style="width: 100px">Referencia
                                                                        </th>
                                                                        <th v-if="form.payments.length>0"
                                                                            style="width: 100px">Monto
                                                                        </th>
                                                                        <th style="width: 30px"></th>
                                                                    </template>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <tr v-for="(row, index) in form.payments"
                                                                    :key="index">
                                                                    <td>
                                                                        <el-select
                                                                            v-model="row.payment_method_type_id"
                                                                            @change="changePaymentMethodType(index)">
                                                                            <el-option
                                                                                v-for="option in cash_payment_metod"
                                                                                :key="option.id"
                                                                                :label="option.description"
                                                                                :value="option.id"></el-option>
                                                                        </el-select>
                                                                    </td>
                                                                    <template v-if="enabled_payments">
                                                                        <td>
                                                                            <el-select v-model="row.payment_destination_id"
                                                                                       filterable>
                                                                                <el-option v-for="option in payment_destinations"
                                                                                           :key="option.id"
                                                                                           :label="option.description"
                                                                                           :value="option.id"></el-option>
                                                                            </el-select>
                                                                        </td>
                                                                        <td>
                                                                            <el-input v-model="row.reference"></el-input>
                                                                        </td>
                                                                        <td>
                                                                            <el-input v-model="row.payment"></el-input>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                                                    type="button"
                                                                                    @click.prevent="clickCancel(index)">
                                                                                <i class="fa fa-trash"></i>
                                                                            </button>
                                                                        </td>
                                                                    </template>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="5">
                                                                        <label class="control-label">
                                                                            <a class=""
                                                                               href="#"
                                                                               @click.prevent="clickAddPayment"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                                                <span style="color: #777777">Agregar pago</span></a>

                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <!-- @todo: Mejorar evitando duplicar codigo -->
                                <!-- Ocultar en cel -->
                                </tbody>
                            </table>
                        </div>
                        <!-- @todo: Mejorar evitando duplicar codigo -->
                        <!-- Mostrar en cel -->
                        <div class="row hidden-md-up">
                            <div class="col-12 text-center">

                                <button class="btn waves-effect waves-light btn-primary btn-sm"
                                        style="width: 180px;"
                                        type="button"
                                        @click.prevent="clickAddItemInvoice">+ Agregar Producto
                                </button>
                            </div>

                            <div class="col-12 text-center table-responsive">
                                <table class="table table-sm text-right"
                                       style="width: 100%;">
                                    <tr v-if="form.total > 0 && enabled_discount_global">
                                        <td>
                                            <el-tooltip class="item"
                                                :content="global_discount_type.description"
                                                effect="dark"
                                                placement="top">
                                                <i class="fa fa-info-circle"></i>
                                            </el-tooltip>
                                            DESCUENTO
                                            <template v-if="is_amount"> MONTO</template>
                                            <template v-else> %</template>
                                            <el-checkbox v-model="is_amount"
                                                         class="ml-1 mr-1"
                                                         @change="changeTypeDiscount"></el-checkbox>
                                            :
                                        </td>
                                        <td>

                                            <el-input-number v-model="total_global_discount"
                                                             :min="0"
                                                             class="input-custom"
                                                             controls-position="right"
                                                             @change="changeTotalGlobalDiscount"></el-input-number>

                                            <!-- <el-input v-model="total_global_discount"
                                                      class="input-custom"
                                                      @input="calculateTotal"></el-input> -->
                                        </td>
                                    </tr>

                                    <template v-if="form.detraction">
                                        <tr v-if="form.detraction.amount > 0">
                                            <td width="60%">M. DETRACCIÓN:</td>
                                            <td>S/ {{ form.detraction.amount }}</td>
                                        </tr>
                                    </template>

                                    <template v-if="form.retention">
                                        <tr v-if="form.retention.amount > 0">
                                            <td>M. RETENCIÓN ({{form.retention.percentage * 100}}%):</td>
                                            <td>{{ currency_type.symbol }} {{ form.retention.amount }}</td>
                                        </tr>
                                    </template>

                                    <tr v-if="form.total_exportation > 0">
                                        <td>OP.EXPORTACIÓN:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_exportation }}</td>
                                    </tr>
                                    <tr v-if="form.total_free > 0">
                                        <td>OP.GRATUITAS:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_free }}</td>
                                    </tr>
                                    <tr v-if="form.total_unaffected > 0">
                                        <td>OP.INAFECTAS:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_unaffected }}</td>
                                    </tr>
                                    <tr v-if="form.total_exonerated > 0">
                                        <td>OP.EXONERADAS:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_exonerated }}</td>
                                    </tr>
                                    <tr v-if="form.total_taxed > 0">
                                        <td>OP.GRAVADA:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_taxed }}</td>
                                    </tr>
                                    <tr v-if="form.total_prepayment > 0">
                                        <td>ANTICIPOS:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_discount }}</td>
                                    </tr>
                                    <tr v-if="form.total_igv > 0">
                                        <td>IGV:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_igv }}</td>
                                    </tr>
                                    <tr v-if="form.total_isc > 0">
                                        <td>ISC:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_isc }}</td>
                                    </tr>
                                    <tr v-if="form.total_plastic_bag_taxes > 0">
                                        <td>ICBPER:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_plastic_bag_taxes }}</td>
                                    </tr>

                                    <tr v-if="form.subtotal > 0 && form.total_discount > 0">
                                        <td>SUBTOTAL:</td>
                                        <td>{{ currency_type.symbol }} {{ form.subtotal }}</td>
                                    </tr>

                                    <tr v-if="form.total_discount > 0">
                                        <td>DESCUENTOS TOTALES:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total_discount }}</td>
                                    </tr>

                                    <tr v-if="form.total > 0">
                                        <td>OTROS CARGOS:</td>
                                        <td>{{ currency_type.symbol }}
                                            <el-input-number v-model="total_global_charge"
                                                             :disabled="config.active_allowance_charge == true ? true:false"
                                                             :min="0"
                                                             class="input-custom"
                                                             controls-position="right"
                                                             @change="calculateTotal"></el-input-number>
                                        </td>
                                    </tr>

                                    <tr v-if="form.total > 0">
                                        <td><strong>TOTAL A PAGAR</strong>:</td>
                                        <td>{{ currency_type.symbol }} {{ form.total }}</td>
                                    </tr>

                                    <tr v-if="form.total > 0">
                                        <td>CONDICIÓN DE PAGO:</td>
                                        <td>
                                            <el-select v-model="form.payment_condition_id"
                                                       dusk="document_type_id"
                                                       popper-class="el-select-document_type"
                                                       style="max-width: 200px;"
                                                       @change="changePaymentCondition">
                                                <el-option label="Crédito con cuotas"
                                                           value="03"></el-option>
                                                <el-option label="Crédito"
                                                           value="02"></el-option>
                                                <el-option label="Contado"
                                                           value="01"></el-option>
                                            </el-select>
                                        </td>
                                    </tr>

                                    <!-- <template v-if="form.detraction">
                                        <tr v-if="form.detraction.amount > 0 && form.total_pending_payment > 0">
                                            <td width="60%">M. PENDIENTE:</td>
                                            <td>{{ currency_type.symbol }} {{ form.total_pending_payment }}</td>
                                        </tr>
                                    </template> -->

                                    <template v-if="form.detraction || form.retention">
                                        <tr v-if="form.total_pending_payment > 0">
                                            <td>M. PENDIENTE:</td>
                                            <td>{{ currency_type.symbol }} {{ form.total_pending_payment }}</td>
                                        </tr>
                                    </template>

                                    <tr v-if="form.total > 0">
                                        <!-- Metodos de pago -->
                                        <td class="p-0"
                                            colspan="2">
                                            <!-- Crédito con cuotas -->
                                            <div v-if="form.payment_condition_id === '03'">
                                                <table v-if="form.fee.length>0"
                                                       class="text-left"
                                                       width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-left"
                                                            style="width: 100px">Fecha
                                                        </th>
                                                        <th class="text-left"
                                                            style="width: 100px">Monto
                                                        </th>
                                                        <th style="width: 30px"></th>
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
                                            </div>
                                            <!-- Credito -->
                                            <div v-if="form.payment_condition_id === '02'">
                                                <table v-if="form.fee.length>0"
                                                       class="text-left"
                                                       width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th v-if="form.fee.length>0"
                                                            style="width: 120px">Método de pago
                                                        </th>
                                                        <th class="text-left"
                                                            style="width: 100px">Fecha
                                                        </th>
                                                        <th class="text-left"
                                                            style="width: 100px">Monto
                                                        </th>
                                                        <th style="width: 30px"></th>
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
                                                                    v-for="option in credit_payment_metod"
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
                                            </div>
                                            <!-- Contado -->
                                            <div v-if="!is_receivable && form.payment_condition_id === '01'">
                                                <table class="text-left">
                                                    <thead>
                                                    <tr>
                                                        <th v-if="form.payments.length>0"
                                                            style="width: 120px">Método de pago
                                                        </th>
                                                        <template v-if="enabled_payments">
                                                            <th v-if="form.payments.length>0"
                                                                style="width: 120px">Destino
                                                                <el-tooltip class="item"
                                                                            content="Aperture caja o cuentas bancarias"
                                                                            effect="dark"
                                                                            placement="top-start">
                                                                    <i class="fa fa-info-circle"></i>
                                                                </el-tooltip>
                                                            </th>
                                                            <th v-if="form.payments.length>0"
                                                                style="width: 100px">Referencia
                                                            </th>
                                                            <th v-if="form.payments.length>0"
                                                                style="width: 100px">Monto
                                                            </th>
                                                            <th style="width: 30px"></th>
                                                        </template>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr v-for="(row, index) in form.payments"
                                                        :key="index">
                                                        <td>
                                                            <el-select
                                                                v-model="row.payment_method_type_id"
                                                                @change="changePaymentMethodType(index)">
                                                                <el-option
                                                                    v-for="option in cash_payment_metod"
                                                                    :key="option.id"
                                                                    :label="option.description"
                                                                    :value="option.id"></el-option>
                                                            </el-select>
                                                        </td>
                                                        <template v-if="enabled_payments">
                                                            <td>
                                                                <el-select v-model="row.payment_destination_id"
                                                                           filterable>
                                                                    <el-option v-for="option in payment_destinations"
                                                                               :key="option.id"
                                                                               :label="option.description"
                                                                               :value="option.id"></el-option>
                                                                </el-select>
                                                            </td>
                                                            <td>
                                                                <el-input v-model="row.reference"></el-input>
                                                            </td>
                                                            <td>
                                                                <el-input v-model="row.payment"></el-input>
                                                            </td>
                                                            <td class="text-center">
                                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                                        type="button"
                                                                        @click.prevent="clickCancel(index)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </template>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5">
                                                            <label class="control-label">
                                                                <a class=""
                                                                   href="#"
                                                                   @click.prevent="clickAddPayment"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                                    <span style="color: #777777">Agregar pago</span></a>

                                                            </label>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                        </div>
                        <!-- @todo: Mejorar evitando duplicar codigo -->
                        <!-- Mostrar en cel -->
                    </div>
                    <!-- @todo: Mejorar evitando duplicar codigo -->
                    <!-- Ocultar en cel -->
                    <div class="card-footer text-right  hidden-sm-down">
                        <button class="btn btn-default"
                                style="min-width: 180px"
                                @click.prevent="close()">Cancelar
                        </button>
                        <el-popover
                            placement="top-start"
                            :open-delay="1000"
                            width="145"
                            trigger="hover"
                            content="Presiona ALT + G">
                            <el-button slot="reference"
                                v-if="form.items.length > 0 && this.dateValid"
                                :loading="loading_submit"
                                class="submit btn btn-primary"
                                native-type="submit"
                                style="min-width: 180px">
                                    {{ btnText }}
                            </el-button>
                        </el-popover>
                    </div>
                    <!-- @todo: Mejorar evitando duplicar codigo -->
                    <!-- Ocultar en cel -->

                    <!-- @todo: Mejorar evitando duplicar codigo -->
                    <!-- Mostrar en cel -->
                    <div class="card-footer   hidden-md-up ">
                        <div class="col-12 row text-center">
                            <div class="col-6 text-center">
                                <button
                                    class="btn btn-default form-control"
                                    @click.prevent="close()">Cancelar
                                </button>
                            </div>
                            <div class="col-6 text-center">

                                <el-button v-if="form.items.length > 0 && this.dateValid"
                                           :loading="loading_submit"
                                           class="submit btn btn-primary form-control"
                                           native-type="submit"
                                >{{ btnText }}
                                </el-button>
                            </div>
                        </div>
                    </div>
                    <!-- Mostrar en cel -->
                    <!-- @todo: Mejorar evitando duplicar codigo -->

                </div>
                <div class="card card-transparent col-xl-3 col-md-3 col-12 pl-md-2 mt-0">
                    <div class="card-body d-flex align-items-start no-gutters">
                        <div class="col-12">
                            <div class="card-body p-2">
                                <div class="col-12 py-2 px-0">
                                    <div class="form-group">
                                        <label class="control-label">Vendedor</label>
                                        <el-select v-model="form.seller_id"
                                                   :disabled="typeUser == 'seller'">
                                            <el-option v-for="option in sellers"
                                                       :key="option.id"
                                                       :label="option.name"
                                                       :value="option.id"></el-option>
                                        </el-select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2 border-top">
                                <div class="col-12 py-2 px-0">
                                    <div class="row no-gutters">
                                        <div class="col-10">¿Es comprobante de contingencia?</div>
                                        <div class="col-2">
                                            <el-switch v-model="is_contingency"
                                                       @change="changeEstablishment"></el-switch>
                                        </div>
                                    </div>
                                </div>
                                <template v-if="!is_client">
                                    <div class="col-12 py-2 px-0">
                                        <div class="row no-gutters">
                                            <div class="col-10">¿Es un pago anticipado?</div>
                                            <div class="col-2">
                                                <el-switch v-if="!prepayment_deduction"
                                                           v-model="form.has_prepayment"
                                                           @change="changeHasPrepayment"></el-switch>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 py-2 px-0">
                                        <div class="row no-gutters">
                                            <div class="col-10">Deducción de los pagos anticipados</div>
                                            <div class="col-2">
                                                <el-switch v-if="!form.has_prepayment"
                                                           v-model="prepayment_deduction"
                                                           @change="changePrepaymentDeduction"></el-switch>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <el-select v-if="form.has_prepayment || prepayment_deduction"
                                                       v-model="form.affectation_type_prepayment"
                                                       class="mb-2"
                                                       @change="changeAffectationTypePrepayment">
                                                <el-option :key="10"
                                                           :value="10"
                                                           label="Gravado"></el-option>
                                                <el-option :key="20"
                                                           :value="20"
                                                           label="Exonerado"></el-option>
                                                <el-option :key="30"
                                                           :value="30"
                                                           label="Inafecto"></el-option>
                                            </el-select>
                                        </div>
                                    </div>
                                    <template v-if="!is_client">
                                        <div v-if="prepayment_deduction"
                                             class="">
                                            <div class="form-group">
                                                <table style="width: 100%">
                                                    <tr v-for="(row,index) in form.prepayments"
                                                        :key="index">
                                                        <td>
                                                            <el-select v-model="row.document_id"
                                                                       filterable
                                                                       @change="changeDocumentPrepayment(index)">
                                                                <el-option v-for="option in prepayment_documents"
                                                                           :key="option.id"
                                                                           :label="option.description"
                                                                           :value="option.id"></el-option>
                                                            </el-select>
                                                        </td>
                                                        <td>
                                                            <el-input v-model="row.amount"
                                                                      @input="inputAmountPrepayment(index)"></el-input>
                                                        </td>
                                                        <td align="right">

                                                            <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                                    type="button"
                                                                    @click.prevent="clickRemovePrepayment(index)">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <label class="control-label">
                                                    <a class=""
                                                       href="#"
                                                       @click.prevent="clickAddPrepayment"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                        <span style="color: #777777">Agregar comprobante anticipado</span></a>
                                                </label>
                                            </div>
                                        </div>
                                    </template>

                                    <div v-if="config.active_allowance_charge && form.total > 0"
                                         class="col-12 py-2 px-0">
                                        <div class="row no-gutters">
                                            <div class="col-8"><strong>Porcentaje otros cargos</strong></div>
                                            <div class="col-4">
                                                <el-input-number v-model="config.percentage_allowance_charge"
                                                                 :min="0"
                                                                 controls-position="right"
                                                                 size="mini"
                                                                 @change="calculateTotal"></el-input-number>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 py-2 px-0"
                                         v-if="show_has_retention">
                                        <div class="row no-gutters">
                                            <div class="col-10">¿Tiene retención de igv?</div>
                                            <div class="col-2">
                                                <el-switch v-model="form.has_retention"
                                                           @change="changeRetention"></el-switch>
                                            </div>
                                        </div>
                                    </div>

                                </template>
                            </div>
                            <el-collapse v-model="activePanel"
                                         accordion>
                                <el-collapse-item name="1">
                                    <template slot="title">
                                        <span class="ml-2">Información Adicional</span>
                                    </template>
                                    <div class="row p-2 border-top">
                                        <div class="col-12">
                                            <template v-if="!isActiveBussinessTurn('tap')">
                                                <template v-if="!is_client">
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            Guías
                                                        </label>
                                                        <table style="width: 100%">
                                                            <tr v-for="(guide,index) in form.guides">
                                                                <td>
                                                                    <el-select v-model="guide.document_type_id">
                                                                        <el-option v-for="option in document_types_guide"
                                                                                   :key="option.id"
                                                                                   :label="option.description"
                                                                                   :value="option.id"></el-option>
                                                                    </el-select>
                                                                </td>
                                                                <td>
                                                                    <el-input v-model="guide.number"></el-input>
                                                                </td>
                                                                <td align="right">
                                                                    <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                                            type="button"
                                                                            @click.prevent="clickRemoveGuide(index)">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <label class="control-label">
                                                                        <a class=""
                                                                           href="#"
                                                                           @click.prevent="clickAddGuide"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                                            <span style="color: #777777">Agregar guía</span></a>

                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </template>
                                            </template>
                                            <template v-else>
                                                <template v-if="!is_client">
                                                    <div class="form-group">
                                                        <label class="control-label">
                                                            Guías
                                                        </label>
                                                        <table style="width: 100%">
                                                            <tr v-for="(guide,index) in form.guides">
                                                                <td>
                                                                    <el-select v-model="guide.document_type_id">
                                                                        <el-option v-for="option in document_types_guide"
                                                                                   :key="option.id"
                                                                                   :label="option.description"
                                                                                   :value="option.id"></el-option>
                                                                    </el-select>
                                                                </td>
                                                                <td>
                                                                    <el-input v-model="guide.number"></el-input>
                                                                </td>
                                                                <td align="right">
                                                                    <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                                            type="button"
                                                                            @click.prevent="clickRemoveGuide(index)">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3">
                                                                    <label class="control-label">
                                                                        <a class=""
                                                                           href="#"
                                                                           @click.prevent="clickAddGuide"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                                            <span style="color: #777777">Agregar guía</span></a>

                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </template>
                                            </template>
                                        </div>
                                        <div class="col-12 py-2 border-top">
                                            <div :class="{'has-danger': errors.purchase_order}"
                                                 class="form-group">
                                                <label class="control-label">Orden de Compra</label>
                                                <el-input
                                                    v-model="form.purchase_order"
                                                    type="textarea">
                                                </el-input>
                                                <small v-if="errors.purchase_order"
                                                       class="form-control-feedback"
                                                       v-text="errors.purchase_order[0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-12 py-2 border-top">
                                            <div class="form-group">
                                                <label class="control-label">Observaciones</label>
                                                <el-input
                                                    v-model="form.additional_information"
                                                    autosize
                                                    type="textarea">
                                                </el-input>
                                            </div>
                                        </div>
                                        <div class="col-md-12 py-2 border-top">
                                            <div :class="{'has-danger': errors.plate_number}"
                                                 class="form-group">
                                                <label class="control-label">N° Placa</label>
                                                <el-input v-model="form.plate_number"
                                                          type="textarea">
                                                </el-input>
                                                <small v-if="errors.plate_number"
                                                       class="form-control-feedback"
                                                       v-text="errors.plate_number[0]"></small>
                                            </div>
                                        </div>
                                        <div class="col-12 py-2 border-top">
                                            <span class="mr-3">Mostrar términos y condiciones.</span>
                                            <el-switch v-model="form.show_terms_condition"></el-switch>
                                        </div>
                                    </div>
                                </el-collapse-item>
                            </el-collapse>
                            <div v-if="isActiveBussinessTurn('hotel')"
                                 class="">
                                <el-tooltip class="item my-2"
                                            content="Datos personales para reserva de hospedaje"
                                            effect="dark"
                                            placement="bottom-end">
                                    <button class="btn btn-primary btn-block"
                                            @click.prevent="clickAddDocumentHotel">Datos de reserva
                                    </button>
                                </el-tooltip>
                            </div>
                            <div v-if="isActiveBussinessTurn('transport')"
                                 class="">
                                <el-tooltip class="item my-2"
                                            content="Datos para transporte de pasajeros"
                                            effect="dark"
                                            placement="bottom-end">
                                    <button class="btn btn-primary btn-block"
                                            @click.prevent="clickAddDocumentTransport">Datos de transporte
                                    </button>
                                </el-tooltip>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <document-form-item
            :configuration="config"
            :currency-type-id-active="form.currency_type_id"
            :documentId="documentId"
            :editNameProduct="config.edit_name_product"
            :exchange-rate-sale="form.exchange_rate_sale"
            :isEditItemNote="false"
            :operation-type-id="form.operation_type_id"
            :recordItem="recordItem"
            :showDialog.sync="showDialogAddItem"
            :typeUser="typeUser"
            :customer-id="form.customer_id"
            :currency-types="currency_types"
            :is-from-invoice="true"
            @add="addRow"></document-form-item>

        <person-form :document_type_id=form.document_type_id
                     :external="true"
                     :input_person="input_person"
                     :showDialog.sync="showDialogNewPerson"
                     type="customers"></person-form>

        <document-options :configuration="config"
                          :isContingency="is_contingency"
                          :isUpdate="isUpdate"
                          :recordId="documentNewId"
                          :showClose="false"
                          :showDialog.sync="showDialogOptions"></document-options>


        <document-hotel-form
            :hotel="form.hotel"
            :showDialog.sync="showDialogFormHotel"
            @addDocumentHotel="addDocumentHotel"
        ></document-hotel-form>

        <document-transport-form
            :showDialog.sync="showDialogFormTransport"
            :transport="form.transport"
            @addDocumentTransport="addDocumentTransport"
        ></document-transport-form>

        <document-detraction
            :currency-type-id-active="form.currency_type_id"
            :detraction="form.detraction"
            :exchange-rate-sale="form.exchange_rate_sale"
            :operation-type-id="form.operation_type_id"
            :showDialog.sync="showDialogDocumentDetraction"
            :total="form.total"
            :isUpdateDocument="isUpdateDocument"
            :detractionDecimalQuantity="detractionDecimalQuantity"
            @addDocumentDetraction="addDocumentDetraction"></document-detraction>
    </div>
</template>

<style>
.input-custom {
    width: 50% !important;
}

.el-textarea__inner {
    height: 65px !important;
    min-height: 65px !important;
}

.card-header + .card-body {
    border-radius: 0px;
}

.card-body {
    border-radius: 0px;
}

.el-collapse-item__content {
    padding-bottom: 0px;
}

.content-body {
    padding: 20px;
}
</style>
<script>
import DocumentFormItem from './partials/item.vue'
import PersonForm from '../persons/form.vue'
import DocumentOptions from '../documents/partials/options.vue'
import {exchangeRate, functions} from '../../../mixins/functions'
import {calculateRowItem, showNamePdfOfDescription} from '../../../helpers/functions'
import Logo from '../companies/logo.vue'
import DocumentHotelForm from '../../../../../modules/BusinessTurn/Resources/assets/js/views/hotels/form.vue'
import DocumentTransportForm from '../../../../../modules/BusinessTurn/Resources/assets/js/views/transports/form.vue'
import DocumentDetraction from './partials/detraction.vue'
import moment from 'moment'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import Keypress from "vue-keypress";

export default {
    props: [
        'idUser',
        'typeUser',
        'configuration',
        'documentId',
        'isUpdate'
    ],
    components: {
        DocumentFormItem,
        PersonForm,
        DocumentOptions,
        Logo,
        DocumentHotelForm,
        Keypress,
        DocumentDetraction,
        DocumentTransportForm
    },
    mixins: [functions, exchangeRate],
    data() {
        return {
            datEmision: {
                disabledDate(time) {
                    return time.getTime() > moment();
                }
            },
             multiple: [
                {
                    keyCode: 78, // N
                    modifiers: ['altKey'],
                    preventDefault: true,
                },
                {
                    keyCode: 71, // g
                    modifiers: ['altKey'],
                    preventDefault: true,
                },
            ],
            // default_document_type: null,
            // default_series_type: null,
            focus_on_client: false,
            dateValid: false,
            input_person: {},
            showDialogDocumentDetraction: false,
            has_data_detraction: false,
            showDialogFormHotel: false,
            showDialogFormTransport: false,
            is_client: false,
            recordItem: null,
            resource: 'documents',
            showDialogAddItem: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            loading_submit: false,
            loading_form: false,
            errors: {},
            form: {},
            document_types: [],
            currency_types: [],
            discount_types: [],
            charges_types: [],
            all_customers: [],
            business_turns: [],
            form_payment: {},
            document_types_guide: [],
            customers: [],
            sellers: [],
            company: null,
            document_type_03_filter: null,
            operation_types: [],
            establishments: [],
            payment_method_types: [],
            establishment: null,
            // all_series: [],
            // series: [],
            prepayment_documents: [],
            currency_type: {},
            documentNewId: null,
            prepayment_deduction: false,
            activePanel: 0,
            total_global_discount: 0,
            total_global_charge: 0,
            loading_search: false,
            is_amount: true,
            enabled_discount_global: false,
            user: null,
            is_receivable: false,
            is_contingency: false,
            cat_payment_method_types: [],
            select_first_document_type_03: false,
            detraction_types: [],
            all_detraction_types: [],
            customer_addresses: [],
            payment_destinations: [],
            form_cash_document: {},
            enabled_payments: true,
            readonly_date_of_due: false,
            seller_class: 'col-lg-6 pb-2',
            btnText: 'Generar',
            payment_conditions: [],
            affectation_igv_types: [],
            total_discount_no_base: 0,
            show_has_retention: true,
            global_discount_types: [],
            global_discount_type: {},
            error_global_discount: false,

        }
    },
    computed: {
        ...mapState([
            'config',
            'series',
            'all_series',
        ]),
        credit_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': true})
        },
        cash_payment_metod: function () {
            return _.filter(this.payment_method_types, {'is_credit': false})
        },
        existDiscountsNoBase: function () {
            return this.total_discount_no_base > 0 ? true : false
        },
        isUpdateDocument: function () {
            return (this.documentId) ? true : false
        },
        isCreditPaymentCondition: function () {
            return ['02', '03'].includes(this.form.payment_condition_id)
        },
        detractionDecimalQuantity: function () {
            return (this.configuration.detraction_amount_rounded_int) ? 0 : 2
        },
        isGlobalDiscountBase: function () {
            return (this.configuration.global_discount_type_id === '02')
        },
    },
    async created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.document_types = response.data.document_types_invoice;
                this.document_types_guide = response.data.document_types_guide;
                this.currency_types = response.data.currency_types
                this.business_turns = response.data.business_turns
                this.establishments = response.data.establishments
                this.operation_types = response.data.operation_types
                this.$store.commit('setAllSeries',response.data.series)
                // this.all_series = response.data.series
                this.all_customers = response.data.customers
                this.sellers = response.data.sellers
                this.discount_types = response.data.discount_types
                this.charges_types = response.data.charges_types
                this.payment_method_types = response.data.payment_method_types
                this.enabled_discount_global = response.data.enabled_discount_global
                this.company = response.data.company;
                this.user = response.data.user;
                this.document_type_03_filter = response.data.document_type_03_filter;
                this.select_first_document_type_03 = response.data.select_first_document_type_03
                // this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null;
                this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null;
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null;
                this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null;
                this.form.seller_id = (this.sellers.length > 0) ? this.idUser : null;
                this.affectation_igv_types = response.data.affectation_igv_types
                // this.prepayment_documents = response.data.prepayment_documents;
                this.is_client = response.data.is_client;
                // this.cat_payment_method_types = response.data.cat_payment_method_types;
                // this.all_detraction_types = response.data.detraction_types;
                this.payment_destinations = response.data.payment_destinations
                this.payment_conditions = response.data.payment_conditions;

                this.global_discount_types = response.data.global_discount_types

                this.seller_class = (this.user == 'admin') ? 'col-lg-4 pb-2' : 'col-lg-6 pb-2';

                // this.default_document_type = response.data.document_id;
                // this.default_series_type = response.data.series_id;
                this.selectDocumentType()

                this.changeEstablishment()
                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeDestinationSale()
                this.changeCurrencyType()
                this.setDefaultDocumentType();
                this.setConfigGlobalDiscountType()
            })
        this.loading_form = true
        this.$eventHub.$on('reloadDataPersons', (customer_id) => {
            this.reloadDataCustomers(customer_id)
        })
        this.$eventHub.$on('initInputPerson', () => {
            this.initInputPerson()
        });
        if (this.documentId) {
            this.btnText = 'Actualizar';
            this.loading_submit = true;
            await this.$http.get(`/documents/${this.documentId}/show`).then(response => {
                this.onSetFormData(response.data.data);
            }).finally(() => this.loading_submit = false);
        }

        const itemsFromDispatches = localStorage.getItem('items');
        if (itemsFromDispatches) {
            const itemsParsed = JSON.parse(itemsFromDispatches);
            const items = itemsParsed.map(i => i.item_id);
            const params = {
                items_id: items
            }
            localStorage.removeItem('items');
            await this.$http.get('/documents/search-items', {params}).then(response => {
                const itemsResponse = response.data.items.map(i => {
                    return this.setItemFromResponse(i, itemsParsed);
                });
                this.form.items = itemsResponse.map(i => {
                    return calculateRowItem(i, this.form.currency_type_id, this.form.exchange_rate_sale)
                });
            });
        }

        const itemsFromNotes = localStorage.getItem('itemsForNotes');
        if (itemsFromNotes) {
            const itemsParsed = JSON.parse(itemsFromNotes);
            const items = itemsParsed.map(i => i.id);
            const params = {
                items_id: items
            }
            localStorage.removeItem('itemsForNotes');
            await this.$http.get('/documents/search-items', {params}).then(response => {
                const itemsResponse = response.data.items.map(i => {
                    return this.setItemFromResponse(i, itemsParsed);
                });
                this.form.items = itemsResponse.map(i => {
                    return calculateRowItem(i, this.form.currency_type_id, this.form.exchange_rate_sale)
                });
            });
        }

        //parse items from multiple sale notes not group
        this.processItemsForNotesNotGroup()

        const clientfromDispatchesOrNotes = localStorage.getItem('client');
        if (clientfromDispatchesOrNotes) {
            const client = JSON.parse(clientfromDispatchesOrNotes);
            if (client.identity_document_type_id == 1) {
                this.form.document_type_id = '03'
            } else if (client.identity_document_type_id == 6) {
                this.form.document_type_id = '01'
            }
            this.searchRemoteCustomers(client.number);
            this.form.customer_id = client.id;
            this.changeEstablishment();
            this.filterSeries();
            this.filterCustomers();
            this.changeCurrencyType()
            localStorage.removeItem('client');
        }
        const dispatchesNumbersFromDispatches = localStorage.getItem('dispatches');
        if (dispatchesNumbersFromDispatches) {
            this.form.dispatches_relateds = JSON.parse(dispatchesNumbersFromDispatches);
            localStorage.removeItem('dispatches')
        }
        const notesNumbersFromNotes = localStorage.getItem('notes');
        if (notesNumbersFromNotes) {
            this.form.sale_notes_relateds = JSON.parse(notesNumbersFromNotes);
            localStorage.removeItem('notes')
        }

    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        async changeRowFreeAffectationIgv(row, index) {

            if (row.item.change_free_affectation_igv) {

                this.form.items[index].affectation_igv_type_id = '15'
                this.form.items[index].affectation_igv_type = await _.find(this.affectation_igv_types, {id: this.form.items[index].affectation_igv_type_id})

            } else {

                this.form.items[index].affectation_igv_type_id = this.form.items[index].item.original_affectation_igv_type_id
                this.form.items[index].affectation_igv_type = await _.find(this.affectation_igv_types, {id: this.form.items[index].affectation_igv_type_id})
            }

            this.form.items[index] = await calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale)
            await this.calculateTotal()

        },
        async processItemsForNotesNotGroup() {

            let itemsNotGroupForNotes = localStorage.getItem('itemsNotGroupForNotes')

            if (itemsNotGroupForNotes) {

                let itemsParsed = JSON.parse(itemsNotGroupForNotes)

                // prepare - validate prop presentation and others
                this.form.items = await this.onPrepareItems(itemsParsed).map(element => {
                    element.item.presentation = element.item.presentation ? element.item.presentation : []
                    return element
                });

                await this.calculateTotal()
                localStorage.removeItem('itemsNotGroupForNotes');
            }

        },
        setItemFromResponse(item, itemsParsed) {
            /* Obtiene el igv del item, si no existe, coloca el gravado*/
            if (item.sale_affectation_igv_type !== undefined) {
                item.affectation_igv_type = item.sale_affectation_igv_type
            } else {
                item.affectation_igv_type = {
                    active: 1,
                    description: "Gravado - Operación Onerosa",
                    exportation: 0,
                    free: 0,
                    id: "10",
                }
            }
            item.presentation = {};
            item.unit_price = item.sale_unit_price;
            item.item = {
                amount_plastic_bag_taxes: item.amount_plastic_bag_taxes,
                attributes: item.attributes,
                brand: item.brand,
                calculate_quantity: item.calculate_quantity,
                category: item.category,
                currency_type_id: item.currency_type_id,
                currency_type_symbol: item.currency_type_symbol,
                description: item.description,
                full_description: item.full_description,
                has_igv: item.has_igv,
                has_plastic_bag_taxes: item.has_plastic_bag_taxes,
                id: item.id,
                internal_id: item.internal_id,
                item_unit_types: item.item_unit_types,
                lots: item.lots,
                lots_enabled: item.lots_enabled,
                lots_group: item.lots_group,
                model: item.model,
                presentation: {},
                purchase_affectation_igv_type_id: item.purchase_affectation_igv_type_id,
                purchase_unit_price: item.purchase_unit_price,
                sale_affectation_igv_type_id: item.sale_affectation_igv_type_id,
                sale_unit_price: item.sale_unit_price,
                series_enabled: item.series_enabled,
                stock: item.stock,
                unit_price: item.sale_unit_price,
                unit_type_id: item.unit_type_id,
                warehouses: item.warehouses,
            };
            item.IdLoteSelected = null;
            if (item.affectation_igv_type_id === undefined) {
                item.affectation_igv_type_id = item.affectation_igv_type.id;
                // item.affectation_igv_type_id = "10";
            }
            item.discounts = [];
            item.charges = [];
            item.item_id = item.id;
            item.unit_price_value = item.sale_unit_price;
            item.input_unit_price_value = item.sale_unit_price;

            item.quantity = 1;

            let tempItem = itemsParsed.find(ip => (ip.item_id == item.id) || (ip.id == item.id));
            if (tempItem !== undefined) {
                item.quantity = tempItem.quantity
            }
            // item.quantity = itemsParsed.find(ip => ip.item_id == item.id).quantity;
            item.warehouse_id = null;

            return item
        },
        // #307 Ajuste para seleccionar automaticamente el tipo de comprobante y serie
        setDefaultDocumentType(from_function ) {
            this.default_series_type = this.config.user.serie;
            this.default_document_type = this.config.user.document_id;
            // if (this.default_document_type === undefined) this.default_document_type = null;
            // if (this.default_series_type === undefined) this.default_series_type = null;

            let alt = _.find(this.document_types, {'id': this.default_document_type});
            if (this.default_document_type !== null && alt !== undefined) {
                this.form.document_type_id = this.default_document_type;
                    this.changeDocumentType()
                alt = _.find(this.series, {'id': this.default_series_type});
                if (this.default_series_type !== null && alt !== undefined) {
                    this.form.series_id = this.default_series_type;
                }
            }
        },
        async onSetFormData(data) {

            this.currency_type = await _.find(this.currency_types, {'id': data.currency_type_id})

            this.form.establishment_id = data.establishment_id;
            this.form.document_type_id = data.document_type_id;
            this.form.id = data.id;
            this.form.hash = data.hash;
            this.form.number = data.number;
            this.form.date_of_issue = moment(data.date_of_issue).format('YYYY-MM-DD');
            this.form.time_of_issue = data.time_of_issue;
            this.form.customer_id = data.customer_id;
            this.form.currency_type_id = data.currency_type_id;
            this.form.exchange_rate_sale = data.exchange_rate_sale;
            this.form.additional_information = this.onPrepareAdditionalInformation(data.additional_information);
            this.form.external_id = data.external_id;
            this.form.filename = data.filename;
            this.form.group_id = data.group_id;
            this.form.perception = data.perception;
            this.form.note = data.note;
            this.form.plate_number = data.plate_number;
            this.form.payments = data.payments;
            this.form.prepayments = data.prepayments || [];
            this.form.legends = [];
            // this.form.detraction = data.detraction;
            this.form.detraction = data.detraction ? data.detraction : {}

            this.form.sale_notes_relateds = data.sale_notes_relateds ? data.sale_notes_relateds : null

            this.form.affectation_type_prepayment = data.affectation_type_prepayment;
            this.form.purchase_order = data.purchase_order;
            this.form.pending_amount_prepayment = data.pending_amount_prepayment || 0;
            this.form.payment_method_type_id = data.payment_method_type_id;
            this.form.charges = data.charges || [];
            this.form.discounts = data.discounts || [];
            this.form.seller_id = data.seller_id;
            this.form.items = this.onPrepareItems(data.items);
            // this.form.series = data.series; //form.series no llena el selector
            this.$store.commit('setSeries', this.onSetSeries(data.document_type_id, data.series))
            // this.series = this.onSetSeries(data.document_type_id, data.series);
            this.form.state_type_id = data.state_type_id;
            this.form.total_discount = parseFloat(data.total_discount);
            this.form.total_exonerated = parseFloat(data.total_exonerated);
            this.form.total_exportation = parseFloat(data.total_exportation);
            this.form.total_free = parseFloat(data.total_free);
            this.form.total_igv = parseFloat(data.total_igv);
            this.form.total_isc = parseFloat(data.total_isc);
            this.form.total_base_isc = parseFloat(data.total_base_isc);
            this.form.total_base_other_taxes = parseFloat(data.total_base_other_taxes);
            this.form.total_other_taxes = parseFloat(data.total_other_taxes);
            this.form.total_plastic_bag_taxes = parseFloat(data.total_plastic_bag_taxes);
            this.form.total_prepayment = parseFloat(data.total_prepayment);
            this.form.total_taxed = parseFloat(data.total_taxed);
            this.form.total_taxes = parseFloat(data.total_taxes);
            this.form.total_unaffected = parseFloat(data.total_unaffected);
            this.form.total_value = parseFloat(data.total_value);
            this.form.total_charge = parseFloat(data.total_charge);
            this.form.total = parseFloat(data.total);
            this.form.subtotal = parseFloat(data.subtotal);
            this.form.total_igv_free = parseFloat(data.total_igv_free);
            this.form.series_id = this.onSetSeriesId(data.document_type_id, data.series);
            this.form.operation_type_id = data.invoice.operation_type_id;
            this.form.terms_condition = data.terms_condition || '';
            this.form.guides = data.guides || [];
            this.form.show_terms_condition = data.terms_condition ? true : false;
            this.form.attributes = [];
            this.form.customer = data.customer;
            this.form.has_prepayment = false;
            this.form.actions = {
                format_pdf: 'a4',
            };
            this.form.hotel = {};
            this.form.transport = {};
            this.form.customer_address_id = null;
            this.form.type = 'invoice';
            this.form.invoice = {
                operation_type_id: data.invoice.operation_type_id,
                date_of_due: data.invoice.date_of_due,
            };
            // this.form.payment_condition_id = '01';

            let is_credit_installments = await _.find(data.fee, {payment_method_type_id: null})
            this.form.payment_condition_id = (is_credit_installments) ? '03' : data.payment_condition_id;
            this.form.fee = data.fee;
            this.form.retention = data.retention

            // this.form.fee = [];
            this.prepareDataDetraction()
            this.prepareDataRetention()

            if (!data.guides) {
                this.clickAddInitGuides();
            }

            await this.reloadDataCustomers(this.form.customer_id)

            this.establishment = data.establishment;

            this.changeDateOfIssue();
            // await this.filterCustomers();
            this.updateChangeDestinationSale();

            this.prepareDataCustomer()

            this.calculateTotal();
            // this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
        },
        async prepareDataCustomer(){

            this.customer_addresses = [];
            let customer = await _.find(this.customers, {'id': this.form.customer_id})
            this.customer_addresses = customer.addresses

            this.form.customer_address_id = this.form.customer ? this.form.customer.address_id : null

            if (customer.address) {
                this.customer_addresses.unshift({
                    id: null,
                    address: customer.address
                })
            }

        },
        prepareDataRetention() {

            this.form.has_retention = !_.isEmpty(this.form.retention)

            if (this.form.has_retention) {
                this.setTotalPendingAmountRetention(this.form.retention.amount)
            }

        },
        async prepareDataDetraction() {

            // this.has_data_detraction = (this.form.detraction) ? true : false
            this.has_data_detraction = !_.isEmpty(this.form.detraction)

            if (this.has_data_detraction) {

                let legend_value = (this.form.operation_type_id === '1001') ? 'Operación sujeta a detracción' : 'Operación Sujeta a Detracción - Servicios de Transporte - Carga'
                let legend = await _.find(this.form.legends, {'code': '2006'})
                if (!legend) this.form.legends.push({code: '2006', value: legend_value})

            }

        },
        updateChangeDestinationSale() {

            if (this.form.payment_condition_id == '01') {

                if (this.config.destination_sale && this.payment_destinations.length > 0) {
                    let cash = _.find(this.payment_destinations, {id: 'cash'})
                    if (cash) {
                        if (this.form.payments[0] !== undefined) {
                            this.form.payments[0].payment_destination_id = cash.id
                        } else {
                            // this.form.payments.push({
                            //     payment_destination_id: cash.id, //genera error al editar cpe enviado desde api
                            // })

                        }
                    } else {
                        this.form.payment_destination_id = this.payment_destinations[0].id
                        this.form.payments[0].payment_destination_id = this.payment_destinations[0].id
                    }
                }

            }
        },
        onPrepareAdditionalInformation(data) {

            let obs = null

            if (Array.isArray(data)) {

                if (data.length > 0) {
                    if (data[0] == '') {
                        return obs;
                    }
                }

                obs = data.join('|')

            }
            // if (typeof data === 'object') {
            //     if (data[0]) {
            //         return data;
            //     }
            //     return null;
            // }

            return obs;
        },
        onPrepareItems(items) {
            return items.map(i => {

                i.unit_price_value = i.unit_value;
                i.input_unit_price_value = (i.item.has_igv) ? i.unit_price : i.unit_value;

                // i.input_unit_price_value = i.unit_price;
                i.discounts = (i.discounts) ? Object.values(i.discounts) : []
                // i.discounts = i.discounts || [];
                i.charges = i.charges || [];
                i.attributes = i.attributes || [];
                i.item.id = i.item_id;
                i.additional_information = this.onPrepareAdditionalInformation(i.additional_information);
                i.item = this.onPrepareIndividualItem(i);
                return i;
            });
        },
        onPrepareIndividualItem(data) {

            let new_item = data.item
            let currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})

            new_item.currency_type_id = currency_type.id
            new_item.currency_type_symbol = currency_type.symbol

            new_item.sale_affectation_igv_type_id = data.affectation_igv_type_id
            new_item.sale_unit_price = data.unit_price
            new_item.unit_price = data.unit_price

            return new_item
        },
        onSetSeriesId(documentType, serie) {
            const find = this.all_series.find(s => s.document_type_id == documentType && s.number == serie);
            if (find) {
                return find.id;
            }
            return null;
        },
        onSetSeries(documentType, serie) {
            const find = this.all_series.find(s => s.document_type_id == documentType && s.number == serie);
            if (find) {
                return [find];
            }
            return [];
        },
        getPrepayment(index) {
            return _.find(this.prepayment_documents, {id: this.form.prepayments[index].document_id})
        },
        inputAmountPrepayment(index) {

            let prepayment = this.getPrepayment(index)

            if (parseFloat(this.form.prepayments[index].amount) > parseFloat(prepayment.amount)) {

                this.form.prepayments[index].amount = prepayment.amount
                this.$message.error('El monto debe ser menor o igual al del anticipo');

            }

            this.form.prepayments[index].total = (this.form.affectation_type_prepayment == 10) ? _.round(this.form.prepayments[index].amount * 1.18, 2) : this.form.prepayments[index].amount

            this.changeTotalPrepayment()

        },
        changeDestinationSale() {
            if (this.config.destination_sale && this.payment_destinations.length > 0) {
                let cash = _.find(this.payment_destinations, {id: 'cash'})
                if (cash) {
                    this.form.payments[0].payment_destination_id = cash.id
                } else {
                    this.form.payment_destination_id = this.payment_destinations[0].id
                    this.form.payments[0].payment_destination_id = this.payment_destinations[0].id
                }
            }
        },
        changePaymentDestination(index) {
            // if(this.form.payments[index].payment_method_type_id=='01'){
            //     this.payment_destinations = this.cash
            // }else{
            //     this.payment_destinations = this.payment_destinations
            // }
        },
        changeEnabledPayments() {
            // this.clickAddPayment()
            // this.form.date_of_due = this.form.date_of_issue
            // this.readonly_date_of_due = false
            // this.form.payment_method_type_id = null
        },
        changePaymentMethodType(index) {

            let id = '01';
            if (this.form.payments[index] !== undefined &&
                this.form.payments[index].payment_method_type_id !== undefined) {
                id = this.form.payments[index].payment_method_type_id;
            } else if (this.form.fee[index] !== undefined &&
                this.form.fee[index].payment_method_type_id !== undefined) {
                id = this.form.fee[index].payment_method_type_id;
            }
            let payment_method_type = _.find(this.payment_method_types, {'id': id});

            if (payment_method_type.number_days) {

                this.form.date_of_due = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                // this.form.payments = []
                this.enabled_payments = false
                this.readonly_date_of_due = true
                this.form.payment_method_type_id = payment_method_type.id

                let date = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')

                // let date = moment()
                //     .add(payment_method_type.number_days, 'days')
                //     .format('YYYY-MM-DD')

                if (this.form.fee !== undefined) {
                    for (let index = 0; index < this.form.fee.length; index++) {
                        this.form.fee[index].date = date;
                    }
                }

            } else if (payment_method_type.id == '09') {

                this.form.payment_method_type_id = payment_method_type.id
                this.form.date_of_due = this.form.date_of_issue
                // this.form.payments = []
                this.enabled_payments = false

            } else {

                this.form.date_of_due = this.form.date_of_issue
                this.readonly_date_of_due = false
                this.form.payment_method_type_id = null
                this.enabled_payments = true

            }

        },
        selectDocumentType() {
            this.form.document_type_id = (this.select_first_document_type_03) ? '03' : '01'
        },
        keyupCustomer() {

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
        addDocumentDetraction(detraction) {
            this.form.detraction = detraction
            // this.has_data_detraction = (detraction.pay_constancy || detraction.detraction_type_id || detraction.payment_method_id || (detraction.amount && detraction.amount >0)) ? true:false
            this.has_data_detraction = (detraction) ? detraction.has_data_detraction : false

            this.changeDetractionType()
        },
        clickAddItemInvoice() {
            this.recordItem = null
            this.showDialogAddItem = true
        },
        getFormatUnitPriceRow(unit_price) {
            return _.round(unit_price, 6)
            // return unit_price.toFixed(6)
        },
        discountGlobalPrepayment() {

            let global_discount = 0
            let sum_total_prepayment = 0

            this.form.prepayments.forEach((item) => {
                global_discount += parseFloat(item.amount)
                sum_total_prepayment += parseFloat(item.total)
            })

            // let base = (this.form.affectation_type_prepayment == 10) ? parseFloat(this.form.total_taxed):parseFloat(this.form.total_exonerated)
            let base = 0

            switch (this.form.affectation_type_prepayment) {
                case 10:
                    base = parseFloat(this.form.total_taxed) + global_discount
                    // base = parseFloat(this.form.total_taxed)
                    break;
                case 20:
                    base = parseFloat(this.form.total_exonerated) + global_discount
                    break;
                case 30:
                    base = parseFloat(this.form.total_unaffected) + global_discount
                    break;
            }

            let amount = _.round(global_discount, 2)
            let factor = _.round(amount / base, 5)

            this.form.total_prepayment = _.round(sum_total_prepayment, 2)
            // this.form.total_prepayment = _.round(global_discount, 2)

            if (this.form.affectation_type_prepayment == 10) {


                let discount = _.find(this.form.discounts, {'discount_type_id': '04'})

                if (global_discount > 0 && !discount) {

                    this.form.total_discount = _.round(amount, 2)
                    this.form.total_taxed = _.round(this.form.total_taxed - amount, 2)
                    this.form.total_igv = _.round(this.form.total_taxed * 0.18, 2)
                    this.form.total_taxes = _.round(this.form.total_igv, 2)
                    this.form.total = _.round(this.form.total_taxed + this.form.total_taxes, 2)

                    this.form.discounts.push({
                        discount_type_id: "04",
                        description: "Descuentos globales por anticipos gravados que afectan la base imponible del IGV/IVAP",
                        factor: factor,
                        amount: amount,
                        base: base
                    })

                } else {

                    let pos = this.form.discounts.indexOf(discount);

                    if (pos > -1) {

                        this.form.total_discount = _.round(amount, 2)
                        this.form.total_taxed = _.round(this.form.total_taxed - amount, 2)
                        this.form.total_igv = _.round(this.form.total_taxed * 0.18, 2)
                        this.form.total_taxes = _.round(this.form.total_igv, 2)
                        this.form.total = _.round(this.form.total_taxed + this.form.total_taxes, 2)

                        this.form.discounts[pos].base = base
                        this.form.discounts[pos].amount = amount
                        this.form.discounts[pos].factor = factor

                    }

                }

            } else if (this.form.affectation_type_prepayment == 20) {

                let exonerated_discount = _.find(this.form.discounts, {'discount_type_id': '05'})

                this.form.total_discount = _.round(amount, 2)
                this.form.total_exonerated = _.round(this.form.total_exonerated - amount, 2)
                this.form.total = this.form.total_exonerated

                if (global_discount > 0 && !exonerated_discount) {
                    this.form.discounts.push({
                        discount_type_id: '05',
                        description: 'Descuentos globales por anticipos exonerados',
                        factor: factor,
                        amount: amount,
                        base: base
                    })

                } else {

                    let position = this.form.discounts.indexOf(exonerated_discount);

                    if (position > -1) {

                        this.form.discounts[position].base = base
                        this.form.discounts[position].amount = amount
                        this.form.discounts[position].factor = factor

                    }

                }

            } else if (this.form.affectation_type_prepayment == 30) {

                let unaffected_discount = _.find(this.form.discounts, {'discount_type_id': '06'})

                this.form.total_discount = _.round(amount, 2)
                this.form.total_unaffected = _.round(this.form.total_unaffected - amount, 2)
                this.form.total = this.form.total_unaffected

                if (global_discount > 0 && !unaffected_discount) {
                    this.form.discounts.push({
                        discount_type_id: '06',
                        description: 'Descuentos globales por anticipos inafectos',
                        factor: factor,
                        amount: amount,
                        base: base
                    })
                } else {
                    let position = this.form.discounts.indexOf(unaffected_discount);
                    if (position > -1) {
                        this.form.discounts[position].base = base
                        this.form.discounts[position].amount = amount
                        this.form.discounts[position].factor = factor

                    }

                }
            }

        },
        async changeDocumentPrepayment(index) {

            let prepayment = await _.find(this.prepayment_documents, {id: this.form.prepayments[index].document_id})

            this.form.prepayments[index].number = prepayment.description
            this.form.prepayments[index].document_type_id = prepayment.document_type_id
            this.form.prepayments[index].amount = prepayment.amount
            this.form.prepayments[index].total = prepayment.total

            await this.changeTotalPrepayment()


        },
        clickAddPrepayment() {
            this.form.prepayments.push({
                document_id: null,
                number: null,
                document_type_id: null,
                amount: 0,
                total: 0,
            });

            this.changeTotalPrepayment()
        },
        clickRemovePrepayment(index) {

            this.form.prepayments.splice(index, 1)
            this.changeTotalPrepayment()
            if (this.form.prepayments.length == 0)
                this.deletePrepaymentDiscount()

        },
        async changePrepaymentDeduction() {

            this.form.prepayments = []
            this.form.total_prepayment = 0
            await this.deletePrepaymentDiscount()

            if (this.prepayment_deduction) {

                await this.initialValueATPrepayment()
                await this.changeTotalPrepayment()
                await this.getDocumentsPrepayment()

            } else {

                // this.form.total_prepayment = 0
                // await this.deletePrepaymentDiscount()
                this.cleanValueATPrepayment()
            }

        },
        setPendingAmount() {
            this.form.pending_amount_prepayment = this.form.has_prepayment ? this.form.total : 0
        },
        initialValueATPrepayment() {
            this.form.affectation_type_prepayment = (!this.form.affectation_type_prepayment) ? 10 : this.form.affectation_type_prepayment
        },
        cleanValueATPrepayment() {
            this.form.affectation_type_prepayment = null
        },
        changeHasPrepayment() {

            if (this.form.has_prepayment) {
                this.initialValueATPrepayment()
            } else {
                this.cleanValueATPrepayment()
            }

            this.setPendingAmount()

        },
        async changeAffectationTypePrepayment() {

            await this.initialValueATPrepayment()

            if (this.prepayment_deduction) {

                this.form.total_prepayment = 0
                await this.deletePrepaymentDiscount()
                await this.changePrepaymentDeduction()
            }

        },
        async deletePrepaymentDiscount() {

            let discount = await _.find(this.form.discounts, {'discount_type_id': '04'})
            let discount_exonerated = await _.find(this.form.discounts, {'discount_type_id': '05'})
            let discount_unaffected = await _.find(this.form.discounts, {'discount_type_id': '06'})

            let pos = this.form.discounts.indexOf(discount)
            if (pos > -1) {
                this.form.discounts.splice(pos, 1)
                this.changeTotalPrepayment()
            }

            let pos_exonerated = this.form.discounts.indexOf(discount_exonerated)
            if (pos_exonerated > -1) {
                this.form.discounts.splice(pos_exonerated, 1)
                this.changeTotalPrepayment()
            }

            let pos_unaffected = this.form.discounts.indexOf(discount_unaffected)
            if (pos_unaffected > -1) {
                this.form.discounts.splice(pos_unaffected, 1)
                this.changeTotalPrepayment()
            }

        },
        getDocumentsPrepayment() {
            this.$http.get(`/${this.resource}/prepayments/${this.form.affectation_type_prepayment}`).then((response) => {
                this.prepayment_documents = response.data
            })
        },
        changeTotalPrepayment() {
            this.calculateTotal()
        },
        isActiveBussinessTurn(value) {
            return (_.find(this.business_turns, {'value': value})) ? true : false
        },
        clickAddDocumentHotel() {
            this.showDialogFormHotel = true
        },
        clickAddDocumentTransport() {
            this.showDialogFormTransport = true
        },

        addDocumentHotel(hotel) {
            this.form.hotel = hotel
        },
        addDocumentTransport(transport) {
            this.form.transport = transport
        },
        changeIsReceivable() {

        },
        clickAddPayment() {

            let id = '01';
            if (this.cash_payment_metod !== undefined &&
                this.cash_payment_metod[0] !== undefined) {
                id = this.cash_payment_metod[0].id
            }
            let total = 0;
            if (this.form.total !== undefined) {
                total = this.form.total
            }
            this.form.date_of_due = moment().format('YYYY-MM-DD');
            this.form.payments.push({
                id: null,
                document_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: id,
                reference: null,
                payment_destination_id: this.getPaymentDestinationId(),
                payment: total,
            });
            this.calculatePayments()

        },
        getPaymentDestinationId() {

            if (this.config.destination_sale &&
                this.payment_destinations.length > 0) {

                let cash = _.find(this.payment_destinations, {id: 'cash'})

                return (cash) ? cash.id : this.payment_destinations[0].id

            }

            return null

        },
        clickCancel(index) {
            this.form.payments.splice(index, 1);
            this.calculatePayments()
        },
        async ediItem(row, index) {
            row.indexi = index
            this.recordItem = row
            this.showDialogAddItem = true
        },
        searchRemoteCustomers(input) {

            if (input.length > 0) {
                this.loading_search = true
                let parameters = `input=${input}&document_type_id=${this.form.document_type_id}&operation_type_id=${this.form.operation_type_id}`

                this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                    .then(response => {
                        this.customers = response.data.customers
                        this.loading_search = false
                        this.input_person.number = null

                        if (this.customers.length == 0) {
                            this.filterCustomers()
                            this.input_person.number = input
                        }
                    })
            } else {
                this.filterCustomers()
                this.input_person.number = null
            }

        },
        initForm() {
            this.errors = {}
            this.form = {
                establishment_id: null,
                document_type_id: null,
                series_id: null,
                seller_id: this.idUser,
                number: '#',
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                customer_id: null,
                currency_type_id: this.config.currency_type_id,
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
                total_plastic_bag_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                subtotal: 0,
                total_igv_free: 0,
                operation_type_id: null,
                date_of_due: moment().format('YYYY-MM-DD'),
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                payments: [],
                prepayments: [],
                legends: [],
                detraction: {},
                additional_information: null,
                plate_number: null,
                has_prepayment: false,
                affectation_type_prepayment: null,
                actions: {
                    format_pdf: 'a4',
                },
                hotel: {},
                transport: {},
                customer_address_id: null,
                pending_amount_prepayment: 0,
                payment_method_type_id: null,
                show_terms_condition: true,
                terms_condition: '',
                payment_condition_id: '01',
                fee: [],
                total_pending_payment: 0,
                has_retention: false,
                retention: {},
            }

            this.form_cash_document = {
                document_id: null,
                sale_note_id: null
            }

            this.clickAddPayment()
            this.clickAddInitGuides()
            this.is_receivable = false
            this.total_global_discount = 0
            this.total_global_charge = 0
            this.is_amount = true
            this.prepayment_deduction = false
            this.imageDetraction = {}
            this.$eventHub.$emit('eventInitForm')

            this.initInputPerson()

            if (!this.config.restrict_receipt_date) {
                this.datEmision = {}
            }

            this.enabled_payments = true
            this.readonly_date_of_due = false
            this.total_discount_no_base = 0

        },
        changeRetention() {

            if (this.form.has_retention) {

                let base = this.form.total
                let percentage = _.round(parseFloat(this.config.igv_retention_percentage) / 100, 5)
                let amount = _.round(base * percentage, 2)

                this.form.retention = {
                    base: base,
                    code: '62', //Código de Retención del IGV
                    amount: amount,
                    percentage: percentage
                }

                this.setTotalPendingAmountRetention(amount)

            } else {

                this.form.retention = {}
                this.form.total_pending_payment = 0
                this.calculateAmountToPayments()
            }

        },
        setTotalPendingAmountRetention(amount) {

            //monto neto pendiente aplica si la condicion de pago es credito
            this.form.total_pending_payment = ['02', '03'].includes(this.form.payment_condition_id) ? this.form.total - amount : 0
            this.calculateAmountToPayments()

        },
        initInputPerson() {
            this.input_person = {
                number: null,
                identity_document_type_id: null
            }
        },
        resetForm() {
            this.activePanel = 0
            this.initForm()
            // this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
            this.form.establishment_id = (this.establishments.length > 0) ? this.establishments[0].id : null
            this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null
            this.form.operation_type_id = (this.operation_types.length > 0) ? this.operation_types[0].id : null
            this.form.seller_id = (this.sellers.length > 0) ? this.idUser : null;
            this.selectDocumentType()
            this.changeEstablishment()
            this.changeDocumentType()
            this.changeDateOfIssue()
            this.changeCurrencyType()
            // this.changeDestinationSale()

        },
        async changeOperationType() {
            this.form.customer_id = null
            await this.filterCustomers();
            await this.setDataDetraction();
        },
        // async filterDetractionTypes(){
        //     this.detraction_types =  await _.filter(this.all_detraction_types, {'operation_type_id':this.form.operation_type_id})
        // },
        async setDataDetraction() {

            if (this.form.operation_type_id === '1001') {

                this.showDialogDocumentDetraction = true

                // this.$message.warning('Sujeta a detracción');
                // await this.filterDetractionTypes();
                let legend = await _.find(this.form.legends, {'code': '2006'})
                if (!legend) this.form.legends.push({code: '2006', value: 'Operación sujeta a detracción'})
                this.form.detraction.bank_account = this.company.detraction_account
                // this.form.detraction.detraction_type_id = undefined

            } else if (this.form.operation_type_id === '1004') {

                this.showDialogDocumentDetraction = true
                let legend = await _.find(this.form.legends, {'code': '2006'})
                if (!legend) this.form.legends.push({
                    code: '2006',
                    value: 'Operación Sujeta a Detracción - Servicios de Transporte - Carga'
                })
                this.form.detraction.bank_account = this.company.detraction_account

            } else {

                _.remove(this.form.legends, {'code': '2006'})
                this.form.detraction = {}

            }

            this.calculateAmountToPayments()
        },
        async changeDetractionType() {

            if (this.form.detraction) {

                if (this.form.currency_type_id == 'PEN') {

                    // this.form.detraction.amount = _.round(parseFloat(this.form.total) * (parseFloat(this.form.detraction.percentage) / 100), 2)
                    this.form.detraction.amount = _.round(parseFloat(this.form.total) * (parseFloat(this.form.detraction.percentage) / 100), this.detractionDecimalQuantity)

                    this.form.total_pending_payment = this.form.total - this.form.detraction.amount

                } else {

                    // this.form.detraction.amount = _.round((parseFloat(this.form.total) * this.form.exchange_rate_sale) * (parseFloat(this.form.detraction.percentage) / 100), 2)
                    this.form.detraction.amount = _.round((parseFloat(this.form.total) * this.form.exchange_rate_sale) * (parseFloat(this.form.detraction.percentage) / 100), this.detractionDecimalQuantity)

                    this.form.total_pending_payment = _.round(this.form.total - (this.form.detraction.amount / this.form.exchange_rate_sale), 2)

                }

                this.calculateAmountToPayments()

            }
        },
        calculateAmountToPayments() {

            // if(this.form.payments.length > 0){
            //     // this.form.payments[0].payment = this.form.total_pending_payment
            // }
            this.calculatePayments()
            this.calculateFee()
        },
        validateDetraction() {

            if (['1001', '1004'].includes(this.form.operation_type_id)) {

                let detraction = this.form.detraction

                let tot = (this.form.currency_type_id == 'PEN') ? this.form.total : (this.form.total * this.form.exchange_rate_sale)
                let total_restriction = (this.form.operation_type_id == '1001') ? 700 : 400

                if (tot <= total_restriction)
                    return {
                        success: false,
                        message: `El importe de la operación debe ser mayor a S/ ${total_restriction}.00 o equivalente en USD`
                    }

                if (!detraction.detraction_type_id)
                    return {success: false, message: 'El campo bien o servicio sujeto a detracción es obligatorio'}

                if (!detraction.payment_method_id)
                    return {success: false, message: 'El campo método de pago - detracción es obligatorio'}

                if (!detraction.bank_account)
                    return {success: false, message: 'El campo cuenta bancaria es obligatorio'}

                if (detraction.amount <= 0)
                    return {success: false, message: 'El campo total detracción debe ser mayor a cero'}

            }

            return {success: true}

        },
        changeEstablishment() {
            this.establishment = _.find(this.establishments, {'id': this.form.establishment_id})
            this.filterSeries()
            this.selectDefaultCustomer()
        },
        async selectDefaultCustomer() {

            if (this.establishment.customer_id) {

                let temp_all_customers = this.all_customers;
                let temp_customers = this.customers;
                await this.$http.get(`/${this.resource}/search/customer/${this.establishment.customer_id}`).then((response) => {
                    let data_customer = response.data.customers
                    temp_all_customers = temp_all_customers.push(...data_customer)
                    temp_customers = temp_customers.push(...data_customer)
                })
                temp_all_customers = this.all_customers.filter((item, index, self) =>
                    index === self.findIndex((t) => (
                        t.id === item.id
                    ))
                )
                temp_customers = this.customers.filter((item, index, self) =>
                    index === self.findIndex((t) => (
                        t.id === item.id
                    ))
                )
                this.all_customers = temp_all_customers;
                this.customers = temp_customers;
                await this.filterCustomers()
                // this.form.customer_id = (this.customers.length > 0) ? this.establishment.customer_id : null
                let alt = _.find(this.customers, {'id': this.establishment.customer_id});
                // console.log(alt)

                if (alt !== undefined) {
                    this.form.customer_id = this.establishment.customer_id
                    this.validateCustomerRetention(alt.identity_document_type_id)
                    let seller = this.sellers.find(element => element.id == alt.seller_id)
                    if(seller !== undefined){
                        this.form.seller_id = seller.id
                    }
                }


            }
        },
        changeDocumentType() {

            this.validateDateOfIssue()
            this.filterSeries();
            this.cleanCustomer();
            this.filterCustomers();
        },
        cleanCustomer() {
            this.form.customer_id = null
        },
        dateValidError() {

            this.$message.error(`No puede seleccionar una fecha menor a ${this.configuration.shipping_time_days} día(s).`);
            // this.$message.error('No puede seleccionar una fecha menor a 6 días.');
            this.dateValid = false

        },
        validateDateOfIssue() {

            let minDate = moment().subtract(this.configuration.shipping_time_days, 'days')
            // let minDate = moment().subtract(7, 'days')

            // validar fecha de factura sin considerar configuracion
            if (moment(this.form.date_of_issue) < minDate && this.form.document_type_id === '01') {

                this.dateValidError()
            } else if (moment(this.form.date_of_issue) < minDate && this.config.restrict_receipt_date) {

                this.dateValidError()

            } else {
                this.dateValid = true
            }

        },
        changeDateOfIssue() {

            this.validateDateOfIssue()

            this.form.date_of_due = this.form.date_of_issue
            // if (! this.isUpdate) {
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            });
            // }
        },
        assignmentDateOfPayment() {
            this.form.payments.forEach((payment) => {
                payment.date_of_payment = this.form.date_of_issue
            })
        },
        filterSeries() {
            this.form.series_id = null
            let series = _.filter(this.all_series, {
                'establishment_id': this.form.establishment_id,
                'document_type_id': this.form.document_type_id,
                'contingency': this.is_contingency
            });
            if(this.form.document_type_id === this.config.user.document_id && this.typeUser == 'seller'){
                // Se filtra si el documento es el mismo que el establecido para el usuario.
                series = _.filter(this.all_series, {
                    'establishment_id': this.form.establishment_id,
                    'document_type_id': this.form.document_type_id,
                    'contingency': this.is_contingency,
                    'id': this.config.user.serie,

                });
            }


            this.$store.commit('setSeries',series)
            this.form.series_id = (this.series.length > 0) ? this.series[0].id : null
        },
        filterCustomers() {
            if (['0101', '1001', '1004'].includes(this.form.operation_type_id)) {

                if (this.form.document_type_id === '01') {
                    this.customers = _.filter(this.all_customers, {'identity_document_type_id': '6'})
                } else {
                    if (this.document_type_03_filter) {
                        this.customers = _.filter(this.all_customers, (c) => {
                            return c.identity_document_type_id !== '6'
                        })
                    } else {
                        this.customers = this.all_customers
                    }
                }

            } else {
                this.customers = this.all_customers
            }
        },
        clickAddInitGuides() {
            this.form.guides.push({
                document_type_id: '09',
                number: null
            }, {
                document_type_id: '31',
                number: null
            })
        },
        clickAddGuide() {
            this.form.guides.push({
                document_type_id: null,
                number: null
            })
        },
        clickRemoveGuide(index) {
            this.form.guides.splice(index, 1)
        },
        addRow(row) {
            if (this.recordItem) {
                //this.form.items.$set(this.recordItem.indexi, row)
                this.form.items[this.recordItem.indexi] = row
                this.recordItem = null
            } else {
                this.form.items.push(JSON.parse(JSON.stringify(row)));
            }

            this.calculateTotal();
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
            let total_plastic_bag_taxes = 0
            this.total_discount_no_base = 0

            let total_igv_free = 0
            let total_base_isc = 0
            let total_isc = 0

            // let total_free_igv = 0

            this.form.items.forEach((row) => {

                // console.log(row)

                total_discount += parseFloat(row.total_discount)
                total_charge += parseFloat(row.total_charge)

                if (row.affectation_igv_type_id === '10') {
                    // total_taxed += parseFloat(row.total_value)
                    if(row.total_value_without_rounding) {
                        total_taxed += parseFloat(row.total_value_without_rounding)
                    } else {
                        total_taxed += parseFloat(row.total_value)
                    }
                }

                if (
                    row.affectation_igv_type_id === '20'  // 20,Exonerado - Operación Onerosa
                    // || row.affectation_igv_type_id === '21' // 21,Exonerado – Transferencia Gratuita
                ) {

                    // total_exonerated += parseFloat(row.total_value)

                    total_exonerated += (row.total_value_without_rounding) ? parseFloat(row.total_value_without_rounding) : parseFloat(row.total_value)
                }

                if (
                    row.affectation_igv_type_id === '30'  // 30,Inafecto - Operación Onerosa
                    || row.affectation_igv_type_id === '31'  // 31,Inafecto – Retiro por Bonificación
                    || row.affectation_igv_type_id === '32'  // 32,Inafecto – Retiro
                    || row.affectation_igv_type_id === '33'  // 33,Inafecto – Retiro por Muestras Médicas
                    || row.affectation_igv_type_id === '34'  // 34,Inafecto - Retiro por Convenio Colectivo
                    || row.affectation_igv_type_id === '35'  // 35,Inafecto – Retiro por premio
                    || row.affectation_igv_type_id === '36' // 36,Inafecto - Retiro por publicidad
                    // || row.affectation_igv_type_id === '37'  // 37,Inafecto - Transferencia gratuita
                ) {
                    total_unaffected += parseFloat(row.total_value)
                }

                if (row.affectation_igv_type_id === '40') {
                    total_exportation += parseFloat(row.total_value)
                }

                if (['10',
                    // '20', '21',
                    '20',
                    '30', '31', '32', '33', '34', '35', '36',
                    '40'].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value)
                }

                if (['10',
                    '20', '21',
                    '30', '31', '32', '33', '34', '35', '36',
                    '40'].indexOf(row.affectation_igv_type_id) > -1) {
                    // total_igv += parseFloat(row.total_igv)
                    // total += parseFloat(row.total)
                    if(row.total_igv_without_rounding) {
                        total_igv += parseFloat(row.total_igv_without_rounding)
                    } else {
                        total_igv += parseFloat(row.total_igv)
                    }

                    // row.total_value_without_rounding = total_value
                    // row.total_base_igv_without_rounding = total_base_igv
                    // row.total_igv_without_rounding = total_igv
                    // row.total_taxes_without_rounding = total_taxes
                    // row.total_without_rounding = total

                    if(row.total_without_rounding) {
                        total += parseFloat(row.total_without_rounding)
                    } else {
                        total += parseFloat(row.total)
                    }


                }

                // console.log(row.total_value)

                if (!['21', '37'].includes(row.affectation_igv_type_id)) {
                    // total_value += parseFloat(row.total_value)
                    if(row.total_value_without_rounding) {
                        total_value += parseFloat(row.total_value_without_rounding)
                    } else {
                        total_value += parseFloat(row.total_value)
                    }
                }

                total_plastic_bag_taxes += parseFloat(row.total_plastic_bag_taxes)


                if (['11', '12', '13', '14', '15', '16'].includes(row.affectation_igv_type_id)) {

                    let unit_value = row.total_value / row.quantity
                    let total_value_partial = unit_value * row.quantity
                    // row.total_taxes = row.total_value - total_value_partial
                    row.total_taxes = row.total_value - total_value_partial + parseFloat(row.total_plastic_bag_taxes) //sumar icbper al total tributos

                    row.total_igv = total_value_partial * (row.percentage_igv / 100)
                    row.total_base_igv = total_value_partial
                    total_value -= row.total_value

                    total_igv_free += row.total_igv
                    total += parseFloat(row.total) //se agrega suma al total para considerar el icbper

                }

                //sum discount no base
                this.total_discount_no_base += this.sumDiscountsNoBaseByItem(row)

                // isc
                total_isc += parseFloat(row.total_isc)
                total_base_isc += parseFloat(row.total_base_isc)
            });

            // isc
            this.form.total_base_isc = _.round(total_base_isc, 2)
            this.form.total_isc = _.round(total_isc, 2)

            this.form.total_igv_free = _.round(total_igv_free, 2)
            this.form.total_discount = _.round(total_discount, 2)
            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            // this.form.total_igv = _.round(total_igv + total_free_igv, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            // this.form.total_taxes = _.round(total_igv, 2)

            //impuestos (isc + igv + icbper)
            this.form.total_taxes = _.round(total_igv + total_isc + total_plastic_bag_taxes, 2);

            this.form.total_plastic_bag_taxes = _.round(total_plastic_bag_taxes, 2)

            this.form.subtotal = _.round(total, 2)
            this.form.total = _.round(total - this.total_discount_no_base, 2)

            // this.form.subtotal = _.round(total + this.form.total_plastic_bag_taxes, 2)
            // this.form.total = _.round(total + this.form.total_plastic_bag_taxes - this.total_discount_no_base, 2)

            if (this.enabled_discount_global)
                this.discountGlobal()

            if (this.prepayment_deduction)
                this.discountGlobalPrepayment()

            if (['1001', '1004'].includes(this.form.operation_type_id))
                this.changeDetractionType()

            if (this.form.has_retention) {
                this.changeRetention()
            }

            this.setTotalDefaultPayment()
            this.setPendingAmount()

            this.calculateFee();

            this.chargeGlobal()

        },
        sumDiscountsNoBaseByItem(row) {

            let sum_discount_no_base = 0

            if (row.discounts) {
                // if(row.discounts.length > 0){
                sum_discount_no_base = _.sumBy(row.discounts, function (discount) {
                    return (discount.discount_type_id == '01') ? discount.amount : 0
                })
                // }
            }

            return sum_discount_no_base
        },
        setTotalDefaultPayment() {

            // if (this.form.payments.length > 0) {

            //     this.form.payments[0].payment = this.form.total
            // }
            this.calculatePayments()
        },
        chargeGlobal() {

            let base = parseFloat(this.form.total)

            if (this.config.active_allowance_charge) {
                let percentage_allowance_charge = parseFloat(this.config.percentage_allowance_charge)
                this.total_global_charge = _.round(base * (percentage_allowance_charge / 100), 2)
            }

            if (this.total_global_charge == 0) {
                this.deleteChargeGlobal()
                return
            }


            let amount = parseFloat(this.total_global_charge)
            // let base = this.form.total_taxed + amount
            let factor = _.round(amount / base, 5)

            // console.log(base,factor, amount)

            let charge = _.find(this.form.charges, {charge_type_id: '50'})

            if (amount > 0 && !charge) {

                this.form.total_charge = _.round(amount, 2)
                this.form.total = _.round(this.form.total + this.form.total_charge, 2)

                this.form.charges.push({
                    charge_type_id: '50',
                    description: 'Cargos globales que no afectan la base imponible del IGV/IVAP',
                    factor: factor,
                    amount: amount,
                    base: base
                })

            } else {

                let pos = this.form.charges.indexOf(charge);

                if (pos > -1) {

                    this.form.total_charge = _.round(amount, 2)
                    this.form.total = _.round(this.form.total + this.form.total_charge, 2)

                    this.form.charges[pos].base = base
                    this.form.charges[pos].amount = amount
                    this.form.charges[pos].factor = factor

                }
            }


        },
        deleteChargeGlobal() {

            let charge = _.find(this.form.charges, {charge_type_id: '50'})
            let index = this.form.charges.indexOf(charge)

            if (index > -1) {
                this.form.charges.splice(index, 1)
                this.form.total_charge = 0
            }

        },
        changeTypeDiscount() {
            this.calculateTotal()
        },
        changeTotalGlobalDiscount() {
            this.calculateTotal()
        },
        deleteDiscountGlobal() {

            let discount = _.find(this.form.discounts, {'discount_type_id': this.configuration.global_discount_type_id})
            // let discount = _.find(this.form.discounts, {'discount_type_id': '03'})
            let index = this.form.discounts.indexOf(discount)

            if (index > -1) {
                this.form.discounts.splice(index, 1)
                this.form.total_discount = 0
            }

        },
        setConfigGlobalDiscountType()
        {
            this.global_discount_type = _.find(this.global_discount_types, { id : this.configuration.global_discount_type_id})
        },
        setGlobalDiscount(factor, amount, base)
        {
            this.form.discounts.push({
                discount_type_id: this.global_discount_type.id,
                description: this.global_discount_type.description,
                factor: factor,
                amount: amount,
                base: base
            })
        },
        discountGlobal() {

            this.deleteDiscountGlobal()

            //input donde se ingresa monto o porcentaje
            let input_global_discount = parseFloat(this.total_global_discount)

            if (input_global_discount > 0)
            {
                const percentage_igv = 18
                let base = (this.isGlobalDiscountBase) ? parseFloat(this.form.total_taxed) : parseFloat(this.form.total)
                let amount = 0
                let factor = 0

                if (this.is_amount)
                {
                    amount = input_global_discount
                    factor = _.round(amount / base, 5)
                }
                else
                {
                    factor = _.round(input_global_discount / 100, 5)
                    amount = factor * base
                }

                this.form.total_discount = _.round(amount, 2)

                // descuentos que afectan la bi
                if(this.isGlobalDiscountBase)
                {
                    this.form.total_taxed = _.round(base - this.form.total_discount, 2)
                    this.form.total_value = this.form.total_taxed
                    this.form.total_igv = _.round(this.form.total_taxed * (percentage_igv / 100), 2)

                    //impuestos (isc + igv + icbper)
                    this.form.total_taxes = _.round(this.form.total_igv + this.form.total_isc + this.form.total_plastic_bag_taxes, 2);
                    this.form.total = _.round(this.form.total_taxed + this.form.total_taxes, 2)
                    this.form.subtotal = this.form.total

                    if (this.form.total <= 0) this.$message.error("El total debe ser mayor a 0, verifique el tipo de descuento asignado (Configuración/Avanzado/Contable)")

                }
                // descuentos que no afectan la bi
                else
                {
                    this.form.total = _.round(this.form.total - amount, 2)
                }

                this.setGlobalDiscount(factor, _.round(amount, 2), base)

            }

        },
        async deleteInitGuides() {
            await _.remove(this.form.guides, {'number': null})
        },
        async asignPlateNumberToItems() {

            if (this.form.plate_number) {

                await this.form.items.forEach(item => {

                    let at = _.find(item.attributes, {'attribute_type_id': '5010'})

                    if (!at) {
                        item.attributes.push({
                            attribute_type_id: '5010',
                            description: "Numero de Placa",
                            value: this.form.plate_number,
                            start_date: null,
                            end_date: null,
                            duration: null,
                        })
                    } else {

                        if (this.isUpdate) {
                            at.value = this.form.plate_number
                        }
                    }

                });

            }
        },
        async validateAffectationTypePrepayment() {

            let not_equal_affectation_type = 0

            await this.form.items.forEach(item => {
                if (item.affectation_igv_type_id != this.form.affectation_type_prepayment) {
                    not_equal_affectation_type++
                }
            });

            return {
                success: (not_equal_affectation_type > 0) ? false : true,
                message: 'Los items deben tener tipo de afectación igual al seleccionado en el anticipo'
            }
        },
        validatePaymentDestination() {

            let error_by_item = 0

            this.form.payments.forEach((item) => {

                if (!['05', '08', '09'].includes(item.payment_method_type_id)) {
                    if (item.payment_destination_id == null) error_by_item++;
                }

            })

            return {
                error_by_item: error_by_item,
            }

        },
        async submit() {
            if (this.form.show_terms_condition) {
                this.form.terms_condition = this.config.terms_condition_sale;
            }
            if (this.form.has_prepayment || this.prepayment_deduction) {
                let error_prepayment = await this.validateAffectationTypePrepayment()
                if (!error_prepayment.success)
                    return this.$message.error(error_prepayment.message);
            }


            if (this.is_receivable) {
                this.form.payments = []
            } else {
                let validate = await this.validate_payments()
                if (validate.acum_total > parseFloat(this.form.total) || validate.error_by_item > 0) {
                    return this.$message.error('Los montos ingresados superan al monto a pagar o son incorrectos');
                }

                let validate_payment_destination = await this.validatePaymentDestination()

                if (validate_payment_destination.error_by_item > 0) {
                    return this.$message.error('El destino del pago es obligatorio');
                }

            }

            await this.deleteInitGuides()
            await this.asignPlateNumberToItems()

            let val_detraction = await this.validateDetraction()
            if (!val_detraction.success)
                return this.$message.error(val_detraction.message);

            if (!this.enabled_payments) {
                this.form.payments = []
            }

            this.loading_submit = true
            let path = `/${this.resource}`;
            if (this.isUpdate) {
                path = `/${this.resource}/${this.form.id}/update`;
            }
            let temp = this.form.payment_condition_id;
            // Condicion de pago Credito con cuota pasa a credito
            if (this.form.payment_condition_id === '03') this.form.payment_condition_id = '02';
            this.$http.post(path, this.form).then(response => {
                if (response.data.success) {
                    this.$eventHub.$emit('reloadDataItems', null)
                    this.resetForm();
                    this.documentNewId = response.data.data.id;
                    this.showDialogOptions = true;

                    this.form_cash_document.document_id = response.data.data.id;

                    // this.savePaymentMethod();
                    this.saveCashDocument();
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    this.$message.error(error.response.data.message);
                }
                if (temp === '03') this.form.payment_condition_id = '03';
            }).finally(() => {
                this.loading_submit = false;
                this.setDefaultDocumentType();
            });
        },
        saveCashDocument() {
            this.$http.post(`/cash/cash_document`, this.form_cash_document)
                .then(response => {
                    if (!response.data.success) {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => console.log(error))
        },
        validate_payments() {

            //eliminando items de pagos
            for (let index = 0; index < this.form.payments.length; index++) {
                if (parseFloat(this.form.payments[index].payment) === 0)
                    this.form.payments.splice(index, 1)
            }

            let error_by_item = 0
            let acum_total = 0

            this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment)
                if (item.payment <= 0 || item.payment == null) error_by_item++;
            })

            return {
                error_by_item: error_by_item,
                acum_total: acum_total
            }

        },

        close() {
            location.href = (this.is_contingency) ? `/contingencies` : `/${this.resource}`
        },
        async reloadDataCustomers(customer_id) {
            // this.$http.get(`/${this.resource}/table/customers`).then((response) => {
            //     this.customers = response.data
            //     this.form.customer_id = customer_id
            // })
            await this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                this.customers = response.data.customers
                this.form.customer_id = customer_id
            })
        },
        changeCustomer() {

            this.customer_addresses = [];
            this.form.customer_address_id = null;

            let customer = _.find(this.customers, {'id': this.form.customer_id});
            this.customer_addresses = customer.addresses;
            if (customer.address) {
                this.customer_addresses.unshift({
                    id: null,
                    address: customer.address
                })
            }


            let seller = this.sellers.find(element => element.id == customer.seller_id)
            if(seller !== undefined){
                this.form.seller_id = seller.id

            }

            // retencion para clientes con ruc
            this.validateCustomerRetention(customer.identity_document_type_id)

            /*if(this.customer_addresses.length > 0) {
                let address = _.find(this.customer_addresses, {'main' : 1});
                this.form.customer_address_id = address.id;
            }*/
        },
        validateCustomerRetention(identity_document_type_id) {

            if (identity_document_type_id != '6') {

                if (this.form.has_retention) {
                    this.form.has_retention = false
                    this.changeRetention()
                }

                this.show_has_retention = false

            } else {
                this.show_has_retention = true
            }

        },
        initDataPaymentCondition01() {

            this.readonly_date_of_due = false
            this.enabled_payments = true
            this.form.date_of_due = this.form.date_of_issue
            this.form.payment_method_type_id = null

        },
        changePaymentCondition() {
            this.form.fee = [];
            this.form.payments = [];
            if (this.form.payment_condition_id === '01') {

                this.clickAddPayment();
                this.initDataPaymentCondition01()

            }
            if (this.form.payment_condition_id === '02') {
                this.clickAddFeeNew();
                this.readonly_date_of_due = true
            }
            if (this.form.payment_condition_id === '03') {
                this.clickAddFee();
            }

            // if(this.isCreditPaymentCondition){
            // this.changeRetention()
            // }

            if (!_.isEmpty(this.form.retention)) {
                this.setTotalPendingAmountRetention(this.form.retention.amount)
            }


        },
        clickAddFee() {
            this.form.date_of_due = moment().format('YYYY-MM-DD');
            this.form.fee.push({
                id: null,
                date: moment().format('YYYY-MM-DD'),
                currency_type_id: this.form.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },
        clickAddFeeNew() {
            let first = {
                id: '05',
                number_days: 0,
            };
            if (this.credit_payment_metod[0] !== undefined) {
                first = this.credit_payment_metod[0];
            }

            // let date = moment()
            //     .add(first.number_days, 'days')
            //     .format('YYYY-MM-DD')

            let date = moment(this.form.date_of_issue).add(first.number_days, 'days').format('YYYY-MM-DD')

            this.form.date_of_due = date;
            this.form.fee.push({
                id: null,
                document_id: null,
                payment_method_type_id: first.id,
                // reference: null,
                // payment_destination_id: this.getPaymentDestinationId(),
                // payment: 0,

                date: date,
                currency_type_id: this.form.currency_type_id,
                amount: 0,
            });
            this.calculateFee();
        },
        clickRemoveFee(index) {
            this.form.fee.splice(index, 1);
            this.calculateFee();
        },
        calculatePayments() {
            let payment_count = this.form.payments.length;
            // let total = this.form.total;
            let total = this.getTotal()

            let payment = 0;
            let amount = _.round(total / payment_count, 2);
            // console.log(amount);
            _.forEach(this.form.payments, row => {
                payment += amount;
                if (total - payment < 0) {
                    amount = _.round(total - payment + amount, 2);
                }
                row.payment = amount;
                // console.error(row.payment)
            })
        },
        calculateFee() {
            let fee_count = this.form.fee.length;
            // let total = this.form.total;
            let total = this.getTotal()

            let accumulated = 0;
            let amount = _.round(total / fee_count, 2);
            _.forEach(this.form.fee, row => {
                accumulated += amount;
                if (total - accumulated < 0) {
                    amount = _.round(total - accumulated + amount, 2);
                }
                row.amount = amount;
            })
        },
        getTotal() {

            if (!_.isEmpty(this.form.detraction) && this.form.total_pending_payment > 0) {
                return this.form.total_pending_payment
            }

            if (!_.isEmpty(this.form.retention) && this.form.total_pending_payment > 0) {
                return this.form.total_pending_payment
            }

            return this.form.total
        },
        setDescriptionOfItem(item) {
            return showNamePdfOfDescription(item, this.config.show_pdf_name)
        },
        checkKeyWithAlt(e){
            let code = e.event.code;
            if(
                this.showDialogOptions === true &&
                code === 'KeyN'
            ){
                this.showDialogOptions = false
            }

            if(
                code === 'KeyG'  // key G
                && !this.showDialogAddItem   // Modal hidden
                && this.form.items.length > 0  // with items
                && this.focus_on_client  === false // not client search
            )
            {
                this.submit()
            }
        },
        checkKey(e){
            let code = e.event.code;
            if(code === 'F2'){
                //abrir el modal de agergar producto
                if(!this.showDialogAddItem ) this.showDialogAddItem = true
            }
            if(code === 'Escape'){
                if(this.showDialogAddItem ) this.showDialogAddItem = false
            }

        }
    }
}
</script>
