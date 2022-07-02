<template>
    <div class="card mb-0 pt-2 pt-md-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Nueva Guía de Remisión</h3>
        </div>
        <div class="card-body">
            <form autocomplete="off"
                  @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.establishment}"
                                 class="form-group">
                                <label class="control-label">Establecimiento<span class="text-danger"> *</span></label>
                                <el-select v-model="form.establishment_id"
                                           @change="changeEstablishment">
                                    <el-option v-for="option in establishments"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.establishment"
                                       class="form-control-feedback"
                                       v-text="errors.establishment[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.series}"
                                 class="form-group">
                                <label class="control-label">Serie<span class="text-danger"> *</span></label>
                                <el-select v-model="form.series_id">
                                    <el-option v-for="option in series"
                                               :key="option.id"
                                               :label="option.number"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.series_id"
                                       class="form-control-feedback"
                                       v-text="errors.series_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.date_of_issue}"
                                 class="form-group">
                                <label class="control-label">Fecha de emisión<span class="text-danger"> *</span></label>
                                <el-date-picker v-model="form.date_of_issue"
                                                :clearable="false"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_of_issue"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.date_of_shipping}"
                                 class="form-group">
                                <label class="control-label">Fecha de
                                                             traslado<span class="text-danger"> *</span></label>
                                <el-date-picker v-model="form.date_of_shipping"
                                                :clearable="false"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_of_shipping"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_shipping[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.customer_id}"
                                 class="form-group">
                                <label class="control-label">
                                    Cliente<span class="text-danger"> *</span>
                                    <a href="#"
                                       @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select v-model="form.customer_id"
                                           :loading="loading_search"
                                           :remote-method="searchRemoteCustomers"
                                           dusk="customer_id"
                                           filterable
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
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.transport_mode_type_id}"
                                 class="form-group">
                                <label class="control-label">Modo de traslado<span class="text-danger"> *</span></label>
                                <el-select v-model="form.transport_mode_type_id">
                                    <el-option v-for="option in transportModeTypes"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.transport_mode_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.transport_mode_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.transfer_reason_type_id}"
                                 class="form-group">
                                <label class="control-label">Motivo de
                                                             traslado<span class="text-danger"> *</span></label>
                                <el-select v-model="form.transfer_reason_type_id" @change="changeTransferReasonType">
                                    <el-option v-for="option in transferReasonTypes"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.transfer_reason_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.transfer_reason_type_id[0]"></small>
                            </div>
                        </div>

                        <!-- numero de DAM -->
                        <template v-if="form.transfer_reason_type_id === '09'">

                            <div class="col-lg-3">
                                <div :class="{'has-danger': errors['related.number']}"
                                    class="form-group">
                                    <label class="control-label">Número de documento (DAM)
                                        <el-tooltip class="item"
                                                    content="Formato del campo: XXXX-XX-XXX-XXXXXX, Ejemplo: 0001-01-002-001234"
                                                    effect="dark"
                                                    placement="top">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <el-input v-model="form.related.number" placeholder="0001-01-002-001234"></el-input>
                                    <small v-if="errors['related.number']" class="form-control-feedback" v-text="errors['related.number'][0]"></small>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div :class="{'has-danger': errors['related.document_type_id']}"
                                    class="form-group">
                                    <label class="control-label">Tipo documento relacionado<span class="text-danger"> *</span></label>
                                    <el-select v-model="form.related.document_type_id" disabled>
                                        <el-option v-for="option in related_document_types"
                                                :key="option.id"
                                                :label="option.description"
                                                :value="option.id"></el-option>
                                    </el-select> 
                                    <small v-if="errors['related.document_type_id']" class="form-control-feedback" v-text="errors['related.document_type_id'][0]"></small>
                                </div>
                            </div>
                        </template>
                        <!-- numero de DAM -->

                        <!-- <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.port_code}">
                                <label class="control-label">Codigo del Puerto</label>
                                <el-input v-model="form.port_code" maxlength="3"></el-input>
                                <small class="form-control-feedback" v-if="errors.port_code" v-text="errors.port_code[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.transshipment_indicator}">
                                <label class="control-label">Transbordo</label>
                                <div class="form-group">
                                    <el-radio v-model="form.transshipment_indicator" label="1">Si</el-radio>
                                    <el-radio v-model="form.transshipment_indicator" label="0">No</el-radio>
                                </div>
                                <small class="form-control-feedback" v-if="errors.transshipment_indicator" v-text="errors.transshipment_indicator[0]"></small>
                            </div>
                        </div> -->

                        <div :class="form.transfer_reason_type_id === '09' ? 'col-lg-12' : 'col-lg-6'">
                            <div :class="{'has-danger': errors.transfer_reason_description}"
                                 class="form-group">
                                <label class="control-label">Descripción de motivo de traslado</label>
                                <el-input v-model="form.transfer_reason_description"
                                          :rows="3"
                                          maxlength="100"
                                          placeholder="Descripción de motivo de traslado..."
                                          type="textarea"></el-input>
                                <small v-if="errors.transfer_reason_description"
                                       class="form-control-feedback"
                                       v-text="errors.transfer_reason_description[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.unit_type_id}"
                                 class="form-group">
                                <label class="control-label">Unidad de medida<span class="text-danger"> *</span></label>
                                <el-select v-model="form.unit_type_id">
                                    <el-option v-for="option in unitTypes"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.unit_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.unit_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.total_weight}"
                                 class="form-group">
                                <label class="control-label">Peso total<span class="text-danger"> *</span></label>
                                <el-input-number v-model="form.total_weight"
                                                 :max="9999999999"
                                                 :min="0"
                                                 :precision="2"
                                                 :step="1"></el-input-number>
                                <small v-if="errors.total_weight"
                                       class="form-control-feedback"
                                       v-text="errors.total_weight[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.packages_number}"
                                 class="form-group">
                                <label class="control-label">Número de
                                                             paquetes
                                                             <!-- <span class="text-danger"> *</span> -->
                                </label>
                                <el-input-number v-model="form.packages_number"
                                                 :max="9999999999"
                                                 :min="0"
                                                 :precision="0"
                                                 :step="1"></el-input-number>
                                <small v-if="errors.packages_number"
                                       class="form-control-feedback"
                                       v-text="errors.packages_number[0]"></small>
                            </div>
                        </div>
                        <!-- <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.container_number}">
                                <label class="control-label">Número de contenedor</label>
                                <el-input-number v-model="form.container_number" :precision="0" :step="1" :min="0" :max="9999999999"></el-input-number>
                                <small class="form-control-feedback" v-if="errors.container_number" v-text="errors.container_number[0]"></small>
                            </div>
                        </div> -->
                        <div class="col-lg-6">
                            <div :class="{'has-danger': errors.observations}"
                                 class="form-group">
                                <label class="control-label">Observaciones</label>
                                <el-input v-model="form.observations"
                                          :rows="3"
                                          maxlength="250"
                                          placeholder="Observaciones..."
                                          type="textarea"></el-input>
                                <small v-if="errors.observations"
                                       class="form-control-feedback"
                                       v-text="errors.observations[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2" v-if="!order_form_id">
                            <div :class="{'has-danger': errors.order_form_external}"
                                 class="form-group">
                                <label class="control-label">Orden de pedido
                                    <el-tooltip class="item"
                                                content="Pedidos externos"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="form.order_form_external"></el-input>
                                <small v-if="errors.order_form_external" class="form-control-feedback" v-text="errors.order_form_external[0]"></small>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                    </div>
                    <div class="row">
                    </div>
                    <hr>
                    <h4>Datos envío</h4>
                    <h6>Dirección partida</h6>
                    <div class="row">
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.origin}"
                                 class="form-group">
                                <label class="control-label">País<span class="text-danger"> *</span></label>
                                <el-select v-model="form.origin.country_id"
                                           filterable>
                                    <el-option v-for="option in countries"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.origin"
                                       class="form-control-feedback"
                                       v-text="errors.origin.country_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.origin}"
                                 class="form-group">
                                <label class="control-label">Ubigeo<span class="text-danger"> *</span></label>
                                <el-cascader v-model="form.origin.location_id"
                                             :options="locations"
                                             filterable></el-cascader>
                                <!--<el-select v-model="form.delivery.department_id" filterable @change="filterProvince(false)">-->
                                <!--<el-option v-for="option in departments" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                <!--</el-select>-->
                                <small v-if="errors.origin"
                                       class="form-control-feedback"
                                       v-text="errors.origin.location_id[0]"></small>
                            </div>
                        </div>
                        <!-- <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.origin}">
                                <label class="control-label">Provincia</label>
                                <el-select v-model="form.origin.province_id" filterable @change="filterDistrict">
                                    <el-option v-for="option in provincesOrigin" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.origin" v-text="errors.origin.province_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.origin}">
                                <label class="control-label">Distrito</label>
                                <el-select v-model="form.origin.location_id" filterable>
                                    <el-option v-for="option in districtsOrigin" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.origin" v-text="errors.origin.location_id[0]"></small>
                            </div>
                        </div> -->
                        <div class="col-lg-6">
                            <div :class="{'has-danger': errors['origin.address']}"
                                 class="form-group">
                                <label class="control-label">Dirección<span class="text-danger"> *</span></label>
                                <el-input v-model="form.origin.address"
                                          :maxlength="100"
                                          placeholder="Dirección..."></el-input>
                                <small v-if="errors['origin.address']"
                                       class="form-control-feedback"
                                       v-text="errors['origin.address'][0]"></small>
                            </div>
                        </div>
                    </div>
                    <h6>Dirección llegada</h6>
                    <div class="row">
                        <div class="col-lg-2">
                            <div :class="{'has-danger': errors.delivery}"
                                 class="form-group">
                                <label class="control-label">País<span class="text-danger"> *</span></label>
                                <el-select v-model="form.delivery.country_id"
                                           filterable>
                                    <el-option v-for="option in countries"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.delivery"
                                       class="form-control-feedback"
                                       v-text="errors.delivery.country_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.delivery}"
                                 class="form-group">
                                <label class="control-label">Ubigeo<span class="text-danger"> *</span></label>
                                <el-cascader v-model="form.delivery.location_id"
                                             :options="locations"
                                             filterable></el-cascader>
                                <!--<el-select v-model="form.delivery.department_id" filterable @change="filterProvince(false)">-->
                                <!--<el-option v-for="option in departments" :key="option.id" :value="option.id" :label="option.description"></el-option>-->
                                <!--</el-select>-->
                                <small v-if="errors.delivery"
                                       class="form-control-feedback"
                                       v-text="errors.delivery.location_id[0]"></small>
                            </div>
                        </div>
                        <div v-if="config.dispatches_address_text"
                             class="col-lg-6">
                            <div :class="{'has-danger': errors['delivery.address']}"
                                 class="form-group">
                                <label class="control-label">Dirección<span class="text-danger"> *</span></label>
                                <el-input v-model="form.delivery.address"
                                          :maxlength="100"
                                          placeholder="Dirección..."
                                >
                                </el-input>
                                <!-- <el-input v-model="form.delivery.address" :maxlength="100" placeholder="Dirección..."></el-input> -->
                                <small v-if="errors['delivery.address']"
                                       class="form-control-feedback"
                                       v-text="errors['delivery.address'][0]"></small>
                            </div>
                        </div>
                        <div v-if="!config.dispatches_address_text"
                             class="col-lg-6">
                            <div :class="{'has-danger': errors['delivery.address']}"
                                 class="form-group">
                                <label class="control-label">Dirección<span class="text-danger"> *</span></label>
                                <el-select v-model="form.delivery.address_id"
                                           filterable
                                           placeholder="Dirección..."
                                           @change="onChangeAddress">
                                    <el-option v-for="(ad, i) in customerAddresses"
                                               :key="i"
                                               :label="ad.address"
                                               :value="ad.address"></el-option>
                                </el-select>
                                <small v-if="errors['delivery.address']"
                                       class="form-control-feedback"
                                       v-text="errors['delivery.address'][0]"></small>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <h4>Datos transportista</h4>
                    <div class="row">
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.dispacher}"
                                 class="form-group">
                                <label class="control-label">Selección rápida de transportista</label>
                                <el-select v-model="dispacher"
                                           clearable
                                           @change="changeTransport">
                                    <el-option
                                        v-for="option in dispachers"
                                        :key="option.id"
                                        :label="option.number +' - '+ option.name"
                                        :value="option.id"
                                    ></el-option><!--
                                     'identity_document_type_id',
                                    'number',
                                    'name',
                                    'address',
                                    -->
                                </el-select>
                                <small v-if="errors.dispacher"
                                       class="form-control-feedback"
                                       v-text="errors.dispacher[0]"></small>
                            </div>
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors['dispatcher.identity_document_type_id']}"
                                 class="form-group">
                                <label class="control-label">Tipo Doc.
                                                             Identidad<span class="text-danger"> *</span></label>
                                <el-select v-model="form.dispatcher.identity_document_type_id"
                                           filterable>
                                    <el-option v-for="option in identityDocumentTypes"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors['dispatcher.identity_document_type_id']"
                                       class="form-control-feedback"
                                       v-text="errors['dispatcher.identity_document_type_id'][0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors['dispatcher.number']}"
                                 class="form-group">
                                <label class="control-label">Número<span class="text-danger"> *</span></label>
                                <el-input v-model="form.dispatcher.number"
                                          :maxlength="11"
                                          placeholder="Número..."
                                ></el-input>
                                <small v-if="errors['dispatcher.number']"
                                       class="form-control-feedback"
                                       v-text="errors['dispatcher.number'][0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors['dispatcher.name']}"
                                 class="form-group">
                                <label class="control-label">Nombre y/o razón social<span class="text-danger"> *</span></label>
                                <el-input v-model="form.dispatcher.name"
                                          :maxlength="100"
                                          placeholder="Nombre y/o razón social..."
                                ></el-input>
                                <small v-if="errors['dispatcher.name']"
                                       class="form-control-feedback"
                                       v-text="errors['dispatcher.name'][0]"></small>
                            </div>
                        </div>
                    </div>
                    <h4>Datos conductor</h4>
                    <div class="row">
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.driver}"
                                 class="form-group">
                                <label class="control-label">Selección rápida de conductor</label>
                                <el-select v-model="driver"
                                           clearable
                                           @change="changeDriver">
                                    <el-option
                                        v-for="option in drivers"
                                        :key="option.id"
                                        :label="option.number +' - '+ option.name"
                                        :value="option.id"
                                    ></el-option><!--
                                    'identity_document_type_id',
                                    'number',
                                    'name',
                                    'license',
                                    'telephone',
                                    -->
                                </el-select>
                                <small v-if="errors.dispacher"
                                       class="form-control-feedback"
                                       v-text="errors.dispacher[0]"></small>
                            </div>
                        </div>
                        <div class="col-12">&nbsp;</div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors['driver.identity_document_type_id']}"
                                 class="form-group">
                                <label class="control-label">Tipo Doc.
                                                             Identidad
                                                             <!-- <span class="text-danger"> *</span> -->
                                </label>
                                <el-select v-model="form.driver.identity_document_type_id"
                                           filterable>
                                    <el-option v-for="option in identityDocumentTypes"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors['driver.identity_document_type_id']"
                                       class="form-control-feedback"
                                       v-text="errors['driver.identity_document_type_id'][0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors['driver.number']}"
                                 class="form-group">
                                <label class="control-label">Número
                                    <!-- <span class="text-danger"> *</span> -->
                                </label>
                                <el-input v-model="form.driver.number"
                                          :maxlength="11"
                                          placeholder="Número..."></el-input>
                                <small v-if="errors['driver.number']"
                                       class="form-control-feedback"
                                       v-text="errors['driver.number'][0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div :class="{'has-danger': errors.license_plate}"
                                 class="form-group">
                                <label class="control-label">Numero de placa del
                                                             vehiculo
                                                             <!-- <span class="text-danger"> *</span> -->
                                </label>
                                <el-input v-model="form.license_plate"
                                          :maxlength="8"
                                          placeholder="Numero de placa del vehiculo..."></el-input>
                                <small v-if="errors.license_plate"
                                       class="form-control-feedback"
                                       v-text="errors.license_plate[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label">Licencia del conductor</label>
                                <el-input v-model="form.driver.license"
                                ></el-input>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label">N° placa semirremolque</label>
                                <el-input v-model="form.secondary_license_plates.semitrailer"></el-input>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="font-weight-bold">Unidad</th>
                                <th class="font-weight-bold">Descripción</th>
                                <th class="text-right font-weight-bold">Cantidad</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody v-if="form.items.length > 0">
                            <tr v-for="(row, index) in form.items">
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.unit_type_id }}</td>
                                <td>{{ row.description }}</td>
                                <td class="text-right">{{ getFormatQuantity(row.quantity) }}</td>
                                <!-- <td class="text-right">{{ row.quantity }}</td> -->
                                <td class="text-right">
                                    <button class="btn waves-effect waves-light btn-xs btn-danger"
                                            type="button"
                                            @click.prevent="clickRemoveItem(index)">x
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td class="text-right hidden-sm-down"
                                    colspan="2">
                                    <label class="control-label">
                                        Producto
                                        <a v-if="can_add_new_product"
                                           href="#"
                                           @click.prevent="showDialogNewItem = true"
                                        >[+ Nuevo]</a>
                                    </label>
                                </td>
                                <td class="hidden-sm-down"
                                    colspan="2">
                                    <div class="row">
                                        <div class="col-8">
                                            <!-- Selector para item -->
                                            <div :class="{'has-danger': errors.items}"
                                                 class="form-group" id="custom-select">

                                                <el-input id="custom-input">

                                                    <el-select v-model="current_item"
                                                            id="select-width"
                                                            :loading="loading_search"
                                                            :remote-method="searchRemoteItems"
                                                            popper-class="el-select-items"
                                                            filterable
                                                            remote
                                                            ref="selectItem"
                                                            slot="prepend"
                                                            @change="onChangeItem">

                                                        <el-option
                                                            v-for="option in items"
                                                            :key="option.id"
                                                            :label="option.full_description"
                                                            :value="option.id"></el-option>
                                                    </el-select>

                                                    <el-tooltip
                                                        slot="append"
                                                        class="item"
                                                        content="Ver Stock del Producto"
                                                        effect="dark"
                                                        placement="bottom">
                                                        <el-button
                                                            @click.prevent="clickWarehouseDetail()">
                                                            <i class="fa fa-search"></i>
                                                        </el-button>
                                                    </el-tooltip>

                                                </el-input>

                                                <small v-if="errors.items"
                                                       class="form-control-feedback"
                                                       v-text="errors.items[0]"></small>
                                            </div>
                                            <template v-if="item">
                                                <div v-if="item.lots_enabled && item.lots_group.length > 0"
                                                     class="col-12 mt-2">
                                                    <a class="text-center font-weight-bold text-info"
                                                       href="#"
                                                       @click.prevent="clickLotGroup">
                                                        [&#10004; Seleccionar lote]
                                                    </a>
                                                </div>
                                            </template>
                                            <!-- Selector para item -->
                                        </div>
                                        <div class="col-4">
                                            <!-- Aqui colocar cantidad -->
                                            <div :class="{'has-danger': errors.quantity}"
                                                 class="form-group">
                                                <!--
                                                <label class="control-label">Cantidad</label>
                                                -->
                                                <el-input-number
                                                    v-model="quantity"
                                                    :max="99999999"
                                                    :min="min_qty"
                                                    :precision="4"
                                                    :step="1"
                                                    placeholder="Cantidad"></el-input-number>
                                                <small v-if="errors.quantity"
                                                       class="form-control-feedback"
                                                       v-text="errors.quantity[0]"></small>
                                            </div>
                                            <!-- Aqui colocar cantidad -->
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right hidden-sm-down">
                                    <!-- Agregar -->
                                    <el-button style="width:100%"
                                               type="primary"
                                               @click="addAItemInRow">Agregar
                                    </el-button>
                                    <!-- Agregar -->

                                    <!--
                                    <button class="btn waves-effect waves-light btn-xs btn-danger"
                                            type="button"
                                            @click.prevent="clickRemoveItem(index)">x
                                    </button>
                                    -->
                                </td>
                            </tr>
                            <tr>
                                <!-- Mostrar en movil -->
                                <td class="text-center hidden-md-up"
                                    colspan="5">
                                    <button class="btn waves-effect waves-light btn-primary"
                                            type="button"
                                            @click.prevent="showDialogAddItems = true">+ Agregar Producto
                                    </button>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-12">
                    &nbsp;
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button v-if="(form.items.length > 0)"
                               :loading="loading_submit"
                               native-type="submit"
                               type="primary">Generar
                    </el-button>
                </div>
            </form>
        </div>

        <person-form :external="true"
                     :showDialog.sync="showDialogNewPerson"
                     type="customers"></person-form>

        <items
            :dialogVisible.sync="showDialogAddItems"
            @addItem="addItem"></items>

        <dispatch-options :isUpdate="(order_form_id) ? true:false"
                          :recordId="recordId"
                          :showClose="false"
                          :showDialog.sync="showDialogOptions"></dispatch-options>
        <item-form :external="true"
                   :showDialog.sync="showDialogNewItem"></item-form>
        <lots-group
            v-if="item"
            :lots_group="item.lots_group"
            :quantity="quantity"
            :showDialog.sync="showDialogLots"
            @addRowLotGroup="addRowLotGroup">
        </lots-group>
        
        <warehouses-detail
            :showDialog.sync="showWarehousesDetail"
            :warehouses="warehousesDetail">
        </warehouses-detail>

    </div>
</template>

<script>
import PersonForm from '../persons/form.vue';
import Items from './items.vue';
import itemForm from '../items/form.vue';
import LotsGroup from '../documents/partials/lots_group.vue';

import DispatchOptions from './partials/options.vue'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import WarehousesDetail from '@components/WarehousesDetail.vue'

export default {
    props: [
        'order_form_id',
        'configuration',
    ],
    components: {
        itemForm,
        LotsGroup,
        PersonForm,
        Items,
        DispatchOptions,
        WarehousesDetail,
    },
    computed: {
        ...mapState([
            'config',
            'item',
            'items',
            'all_items',
        ]),

    },
    data() {
        return {
            can_add_new_product: false,
            showDialogNewItem: false,
            IdLoteSelected: false,
            showDialogLots: false,
            min_qty: 0.0001,
            // min_qty: 0.1,
            showDialogOptions: false,
            showDialogNewPerson: false,
            identityDocumentTypes: [],
            showDialogAddItems: false,
            transferReasonTypes: [],
            related_document_types: [],
            transportModeTypes: [],
            resource: 'dispatches',
            loading_submit: false,
            provincesDelivery: [],
            districtsDelivery: [],
            provincesOrigin: [],
            districtsOrigin: [],
            establishments: [],
            districtsAll: [],
            provincesAll: [],
            departments: [],
            drivers: [],
            driver: null,
            dispachers: [],
            dispacher: null,
            countries: [],
            seriesAll: [],
            unitTypes: [],
            all_customers: [],
            loading_search: false,
            search_item_by_barcode: false,
            customers: [],
            code: null,
            locations: [],
            series: [],
            current_item: null,
            quantity: 1,
            errors: {
                errors: {}
            },
            form: {
                operation_type_id: null,
                driver: {
                    number: null,
                    name: null,
                    license: null,
                    identity_document_type_id: null,
                },
                dispatcher: {
                    number: null,
                    name: null,
                    identity_document_type_id: null,
                },
                establishment_id: null,
                document_type_id: '09',
                series_id: null,
                number: '#',
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                date_of_shipping: moment().format('YYYY-MM-DD'),
                customer_id: null,
                observations: '',
                transport_mode_type_id: null,
                transfer_reason_type_id: null,
                transfer_reason_description: null,
                transshipment_indicator: false,
                port_code: null,
                unit_type_id: null,
                total_weight: 0,
                packages_number: null,
                container_number: null,
                delivery: {
                    country_id: 'PE',
                    location_id: [],
                    address: null,
                },
                origin: {
                    country_id: 'PE',
                    location_id: [],
                    address: null,
                },
                items: [],
                reference_order_form_id: null,
                license_plate: null,
                secondary_license_plates: {
                    semitrailer: null
                }
            },
            recordId: null,
            company: {},
            customerAddresses: [],
            showWarehousesDetail: false,
            warehousesDetail: [],
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
        this.canCreateProduct();
    },
    mounted() {
        // this.clean();
        this.initForm()
        const itemsFromSummary = localStorage.getItem('items');
        const payload = {}
        if (itemsFromSummary) {
            const items = JSON.parse(itemsFromSummary);
            payload.itemIds = items.map(i => i.id);
        }
        this.$http.post(`/${this.resource}/tables`, payload).then(response => {
            this.company = response.data.company;
            this.identityDocumentTypes = response.data.identityDocumentTypes;
            this.transferReasonTypes = response.data.transferReasonTypes;
            this.related_document_types = response.data.related_document_types
            
            this.transportModeTypes = response.data.transportModeTypes;
            this.establishments = response.data.establishments;
            this.departments = response.data.departments;
            this.provincesAll = response.data.provinces;
            this.districtsAll = response.data.districts;
            this.unitTypes = response.data.unitTypes;
            this.customers = response.data.customers;
            this.all_customers = this.customers;
            this.countries = response.data.countries;
            this.locations = response.data.locations;
            this.seriesAll = response.data.series;
            this.drivers = response.data.drivers;
            this.dispachers = response.data.dispachers;
            if (itemsFromSummary) {
                this.onLoadItemsFromSummary(response.data.itemsFromSummary, JSON.parse(itemsFromSummary));
            }
            this.changeEstablishment()
        }).then(() => {
            this.setDefaultCustomer();
        });

        this.createFromOrderForm()

        this.$eventHub.$on('reloadDataPersons', (customer_id) => {
            this.reloadDataCustomers(customer_id)
        })
    },
    methods: {
        clickWarehouseDetail(){

            if (!this.current_item) {
                return this.$message.error('Seleccione un producto');
            }

            const item = _.find(this.items, {'id': this.current_item});

            this.warehousesDetail = item.warehouses
            this.showWarehousesDetail = true
        },
        changeTransferReasonType(){

            // exportacion
            if(this.form.transfer_reason_type_id === '09')
            {
                this.form.related = {
                    number: null,
                    document_type_id: '01'
                }

            }else
            {
                this.form.related = {}
            }

        },
        getFormatQuantity(quantity){
            return _.round(quantity, 4)
        },
        ...mapActions([
            'loadItems',
            'loadConfiguration',
        ]),
        canCreateProduct() {
            if (this.config.typeUser === 'admin') {
                this.can_add_new_product = true
            } else if (this.config.typeUser === 'seller' && this.config.seller_can_create_product !== undefined) {
                this.can_add_new_product = this.config.seller_can_create_product;
            }
            return this.can_add_new_product;
        },
        getAllItems() {
            this.$http.post(`/${this.resource}/tables`).then(response => {
                // this.items = response.data.items;
                this.all_items = this.items
                this.$store.commit('setItems', response.data.items)
                this.$store.commit('setAllItems', response.data.items)

            });
        },
        addRowLotGroup(id) {
            this.IdLoteSelected = id;
        },
        clickLotGroup() {
            this.showDialogLots = true
        },
        async searchRemoteItems(input) {
            if (input.length > 2) {
                this.loading_search = true

                const params = {
                    'input': input,
                    'search_by_barcode': this.search_item_by_barcode ? 1 : 0
                }
                await this.$http.get(`/documents/search-items`, {params})
                    .then(response => {
                        // this.items = response.data.items
                        this.$store.commit('setItems', response.data.items)
                        this.loading_search = false
                        // this.enabledSearchItemsBarcode()
                        if (this.items.length == 0) {
                            this.filterItems()
                        }
                    })
            } else {
                await this.filterItems()
            }
        },
        onChangeItem() {
            this.IdLoteSelected = null;
            this.$store.commit('setItem', this.items.find(it => it.id == this.current_item))
        },
        filterItems() {
            this.$store.commit('setItems', this.all_items)
        },
        addAItemInRow() {
            this.errors = {};
            if (this.item.lots_enabled) {
                if (!this.IdLoteSelected)
                    return this.$message.error('Debe seleccionar un lote.');
            }

            if ((this.current_item != null) && (this.quantity != null)) {
                this.quantity = Math.abs(this.quantity)
                if (isNaN(this.quantity)) {
                    this.quantity = 1;
                }
                const item = this.items.find((item) => item.id == this.current_item)
                item.IdLoteSelected = this.IdLoteSelected;

                this.addItem({
                    item: item,
                    quantity: this.quantity,
                })
                this.$store.commit('setItem', {})
                this.quantity = 1
                this.focusDescription()
                return null;
            }

            if (this.current_item == null) {
                this.$set(this.errors, 'items', ['Seleccione el producto']);
            }

            if (this.quantity == null) {
                this.$set(this.errors, 'quantity', ['Digite la cantidad']);
            }

            this.IdLoteSelected = null;
        },
        reloadDataCustomers(customer_id) {
            this.$http.get(`/documents/search/customer/${customer_id}`).then((response) => {
                this.customers = response.data.customers
                this.form.customer_id = customer_id
            })
        },
        changeTransport() {
            let v = _.find(this.dispachers, {'id': this.dispacher})
            if (v !== undefined) {
                this.form.dispatcher.number = v.number;
                this.form.dispatcher.name = v.name;
                this.form.dispatcher.identity_document_type_id = v.identity_document_type_id;
            }
        },
        changeDriver() {
            let v = _.find(this.drivers, {'id': this.driver})
            if (v !== undefined) {
                this.form.driver.number = v.number;
                this.form.driver.license = v.license;
                this.form.driver.identity_document_type_id = v.identity_document_type_id;
            }
        },
        onChangeAddress() {
            const address = this.customerAddresses.find(ad => ad.address == this.form.delivery.address_id);

            this.form.delivery.address = address.address;
            if (address.country_id) {
                this.form.delivery.country_id = address.country_id;
            }

            if (address.department_id && address.province_id && address.district_id) {
                this.form.delivery.location_id = [address.department_id, address.province_id, address.district_id];
            }
        },
        changeCustomer() {
            this.customerAddresses = [];
            const customer = this.customers.find(i => i.id === this.form.customer_id);
            this.customerAddresses = customer.addresses;
            if (customer.address) {
                this.customerAddresses.unshift({
                    id: null,
                    address: customer.address,
                    country_id: customer.country_id,
                    department_id: customer.department_id,
                    province_id: customer.province_id,
                    district_id: customer.district_id,
                })
            }
        },
        onLoadItemsFromSummary(items, itemsFromStorage) {

            items.map(it => {
                // const itemWithQuantity = itemsFromStorage.find(i => i.id == it.id);

                const quantityByItems = _.sumBy(itemsFromStorage.filter(i => i.id == it.id), function(row){
                    return parseFloat(row.quantity)
                })

                if (quantityByItems) {
                    this.addItem({
                        item: it,
                        quantity: quantityByItems
                        // quantity: itemWithQuantity.quantity
                    });
                }
            });
            localStorage.removeItem('items');
        },
        searchRemoteCustomers(input) {
            if (input.length > 0) {
                this.loading_search = true
                let parameters = `input=${input}&document_type_id=${this.form.document_type_id}&searchBy=${this.resource}`;
                if (this.form.operation_type_id !== undefined) {
                    parameters = parameters + `&operation_type_id=${this.form.operation_type_id}`
                }
                this.$http.get(`/${this.resource}/search/customers?${parameters}`)
                    .then(response => {
                        this.customers = response.data.customers
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
            // if (['0101', '1001', '1004'].includes(this.form.operation_type_id)) {

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

            /*
        } else {
            this.customers = this.all_customers
        }
        */
        },
        setDefaultCustomer() {
            /*

            let customer = _.find(this.customers, {number: this.company.number})

            if (customer) {
                this.form.customer_id = customer.id
            }

            */
            if (this.config.establishment.customer_id) {
                let temp_customers = this.customers;
                let customer_id = this.config.establishment.customer_id;
                let custom = temp_customers.find(l => l.id == customer_id);
                if (custom === undefined) {
                    this.$http.get(`/${this.resource}/search/customer/${customer_id}`).then((response) => {
                        let data_customer = response.data.customers
                        temp_customers = temp_customers.push(...data_customer)
                    })
                    temp_customers = this.customers.filter((item, index, self) =>
                            index === self.findIndex((t) => (
                                t.id === item.id
                            ))
                    )
                    this.customers = temp_customers;
                }
                let alt = _.find(this.customers, {'id': customer_id});
                if (alt !== undefined) {
                    this.form.customer_id = customer_id
                    this.changeCustomer();
                }
            }
        },
        createFromOrderForm() {

            if (this.order_form_id) {

                this.$http.get(`/order-forms/record/${this.order_form_id}`)
                    .then(response => {

                        let order_form = response.data.data.order_form
                        // this.form = order_form

                        this.form.establishment_id = order_form.establishment_id
                        this.form.establishment = order_form.establishment
                        this.form.date_of_issue = order_form.date_of_issue
                        this.form.customer_id = order_form.customer_id
                        this.form.customer = order_form.customer
                        this.form.observations = order_form.observations
                        this.form.transport_mode_type_id = order_form.transport_mode_type_id
                        this.form.transfer_reason_type_id = order_form.transfer_reason_type_id
                        this.form.transfer_reason_description = order_form.transfer_reason_description
                        this.form.date_of_shipping = order_form.date_of_shipping
                        this.form.transshipment_indicator = order_form.transshipment_indicator
                        this.form.port_code = order_form.port_code
                        this.form.unit_type_id = order_form.unit_type_id
                        this.form.total_weight = order_form.total_weight
                        this.form.packages_number = order_form.packages_number
                        this.form.container_number = order_form.container_number
                        this.form.origin = order_form.origin
                        this.form.delivery = order_form.delivery

                        //aqui
                        this.form.dispatcher = {
                            name: order_form.dispatcher.name,
                            number: order_form.dispatcher.number,
                            identity_document_type_id: order_form.dispatcher.identity_document_type_id,
                        }
                        this.form.driver = {
                            number: order_form.driver.number,
                            identity_document_type_id: order_form.driver.identity_document_type_id,
                        }

                        this.form.license_plate = order_form.license_plates.license_plate_1
                        this.form.reference_order_form_id = order_form.id
                        this.form.items = order_form.items

                        this.form.items.forEach(element => {
                            element.description = element.item.description
                            element.unit_type_id = element.item.unit_type_id
                        });

                        this.changeEstablishment()

                    })

            }
        },
        setDefaultSerie() {
            let series_id = parseInt(this.config.user.serie);
            if (isNaN(series_id)) series_id = null;
            let searchSerie = _.filter(this.series, {
                'establishment_id': this.form.establishment_id,
                'document_type_id': this.form.document_type_id,
                'id': series_id
            });
            if (searchSerie !== undefined && searchSerie.length > 0) {
                this.form.series_id = series_id;
            }
        },
        initForm() {

            this.errors = {}
            let customer_id = parseInt(this.config.establishment.customer_id);
            let establishment_id = parseInt(this.config.establishment.id);
            if (isNaN(customer_id)) customer_id = null;
            if (isNaN(establishment_id)) establishment_id = null;

            this.form = {
                establishment_id: establishment_id,
                document_type_id: '09',
                series_id: null,
                number: '#',
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                date_of_shipping: moment().format('YYYY-MM-DD'),
                customer_id: customer_id,
                observations: '',
                transport_mode_type_id: null,
                transfer_reason_type_id: null,
                transfer_reason_description: null,
                transshipment_indicator: false,
                port_code: null,
                unit_type_id: this.config.unit_type_id,
                total_weight: 1,
                packages_number: 0,
                container_number: null,
                dispatcher: {
                    identity_document_type_id: null
                },
                driver: {
                    identity_document_type_id: null,
                    license: null,
                },
                delivery: {
                    country_id: 'PE',
                    location_id: [],
                    address: null,
                },
                origin: {
                    country_id: 'PE',
                    location_id: [],
                    address: null,
                },
                // optional: {
                //     invoice_number: this.document.series+'-'+this.document.number
                // },
                items: [],
                reference_order_form_id: null,
                license_plate: null,
                secondary_license_plates: {
                    semitrailer: null
                },
                related: {},
                order_form_external: null,
            }

            this.changeEstablishment();


        },
        changeEstablishment() {
            this.series = _.filter(this.seriesAll, {
                'establishment_id': this.form.establishment_id,
                'document_type_id': this.form.document_type_id
            });

            this.code = this.form.establishment_id;
            this.form.series_id = null;
            this.setDefaultSerie();
            this.setOriginAddressByEstablishment()
        },
        setOriginAddressByEstablishment() {


            if (this.configuration.set_address_by_establishment) {

                let establishment = _.find(this.establishments, {id: this.form.establishment_id})

                if (this.form.origin && establishment) {

                    this.form.origin.address = establishment.address
                    this.form.origin.location_id = [
                        establishment.department_id,
                        establishment.province_id,
                        establishment.district_id
                    ]

                }

            }

        },
        filterProvince(origin = true) {
            if (origin) {
                this.provincesOrigin = _.filter(this.provincesAll, {
                    'department_id': this.form.origin.department_id
                });

                this.$set(this.form.origin, 'province_id', null);
                this.$set(this.form.origin, 'location_id', null);

                return;
            }

            this.provincesDelivery = _.filter(this.provincesAll, {
                'department_id': this.form.delivery.department_id
            });

            this.$set(this.form.delivery, 'province_id', null);
            this.$set(this.form.delivery, 'location_id', null);
        },
        filterDistrict(origin = true) {
            if (origin) {
                this.districtsOrigin = _.filter(this.districtsAll, {
                    'province_id': this.form.origin.province_id
                });

                this.$set(this.form.origin, 'location_id', null);

                return;
            }

            this.districtsDelivery = _.filter(this.districtsAll, {
                'province_id': this.form.delivery.province_id
            });

            this.$set(this.form.delivery, 'location_id', null);
        },
        addItem(form) {
            let it = form.item;
            let qty = form.quantity;
            let exist = this.form.items.find((item) => item.id == it.id);

            let attributes = null

            if (it.attributes) {
                attributes = it.attributes
                this.incrementValueAttr(form)
            }

            if (exist) {
                exist.quantity += form.quantity;
                return;
            }
            let lot_group = null;
            if (it.IdLoteSelected) {
                lot_group = it.lots_group.find(l => l.id == it.IdLoteSelected);
            }
            this.form.items.push({
                attributes: attributes,
                description: it.description,
                internal_id: it.internal_id,
                quantity: form.quantity,
                item_id: it.id,
                unit_type_id: it.unit_type_id,
                id: it.id,
                IdLoteSelected: it.IdLoteSelected || '',
                lot_group: lot_group || null,
            });
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
        decrementValueAttr(form) {


            let it = form
            let attrib = it.attributes
            let qty = parseFloat(it.quantity)

            //this.form.packages_number -= parseFloat(form.quantity)
            this.form.packages_number -= qty

            let total_weight = 0


            if (attrib) {
                for (const [key, value] of Object.entries(attrib)) {
                    if (key === 'attributes' && value !== null) {
                        let attr = JSON.parse(value)
                        if (attr !== null) {
                            attr.forEach(attr => {
                                if (attr.attribute_type_id === '5032') {
                                    total_weight -= parseFloat(attr.value) * qty
                                }
                            });
                        }
                    }
                }
            }

            this.form.total_weight += total_weight
        },
        incrementValueAttr(form) {

            let qty = parseFloat(form.quantity)
            let it = form.item
            let attrib = it.attributes
            this.form.packages_number += qty
            let total_weight = 0
            if (attrib) {
                for (const [key, value] of Object.entries(attrib)) {
                    if (key === 'attributes' && value !== null) {
                        let attr = JSON.parse(value)
                        if (attr !== null) {
                            attr.forEach(attr => {
                                if (attr.attribute_type_id === '5032') {
                                    total_weight += parseFloat(attr.value) * qty
                                }
                            });
                        }
                    }
                }
                /*
                attrib.attributes.forEach(attr => {
                    if(attr.attribute_type_id === '5032'){
                        total_weight += parseFloat(attr.value) * qty
                    }
                });
                */
            }

            this.form.total_weight += total_weight
        },
        clickRemoveItem(index) {
            this.decrementValueAttr(this.form.items[index])
            this.form.items.splice(index, 1);
        },
        validateRelatedNumber(){

            // if(this.form.transfer_reason_type_id === "09")
            // {
            //     if(_.isEmpty(this.form.related.number)){
            //         return {
            //             success: false,
            //             message: 'El campo Número de documento (DAM) es obligatorio'
            //         }
            //     }

            //     const pattern = new RegExp('^[0-9]{4}-[0-9]{2}-[0-9]{3}-[0-9]{6}$', 'i');

            //     if (!pattern.test(this.form.related.number)) {
            //         return {
            //             success: false,
            //             message: 'El campo Número de documento (DAM) no cumple con el formato establecido - XXXX-XX-XXX-XXXXXX'
            //         }
            //     } 

            // }

            // return {
            //     success: true
            // }
        },
        async submit() {

            const validateQuantity = await this.verifyQuantityItems()
            if (!validateQuantity.validate) {
                return this.$message.error('Los productos no pueden tener cantidad 0.')
            }

            if (this.form.origin.location_id.length != 3 || this.form.delivery.location_id.length != 3)
                return this.$message.error('El campo ubigeo es obligatorio')

            this.loading_submit = true;

            this.$http.post(`/${this.resource}`, this.form).then(response => {
                if (response.data.success) {
                    this.initForm();

                    // this.$message.success(response.data.message)
                    this.recordId = response.data.data.id
                    this.showDialogOptions = true

                    // if(this.order_form_id){
                    //     this.close()
                    // }
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                this.loading_submit = false;

                if (error.response.status === 422) {
                    this.errors = error.response.data;
                } else {
                    this.$message.error(error.response.data.message);
                }
            }).then(() => {
                this.setDefaultCustomer();
                this.loading_submit = false;
            });
        },
        clean() {
            this.form = {
                time_of_issue: moment().format('HH:mm:ss'),
                dispatcher: {
                    identity_document_type_id: null
                },
                driver: {
                    identity_document_type_id: null
                },
                document_type_id: '09',
                delivery: {
                    country_id: 'PE'
                },
                origin: {
                    country_id: 'PE'
                },
                number: '#',
                items: [],
                total_weight: null,
                packages_number: null,
                container_number: null
            }
        },
        close() {
            location.href = '/dispatches';
        },
        verifyQuantityItems() {
            let validate = true
            let v = 0;
            this.form.items.forEach((element) => {
                v = parseFloat(element.quantity);
                if (isNaN(v)) {
                    validate = false
                } else if (v < this.min_qty) {
                    validate = false
                }
            })
            return {validate}
        },
        focusDescription() {
                this.$refs.selectItem.$el.getElementsByTagName('input')[0].focus()
        },
    }
}
</script>
