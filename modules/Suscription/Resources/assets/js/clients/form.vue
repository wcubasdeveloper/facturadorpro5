<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create">
        <form
            autocomplete="off"
            @submit.prevent="submit">
            <el-tabs v-model="tabActive">


                <el-tab-pane class
                             name="first">
                    <span slot="label">
                        Datos del cliente
                    </span>
                    <div class="row">
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.identity_document_type_id}"
                                 class="form-group">
                                <label class="control-label">Tipo Doc. Identidad <span class="text-danger">*</span></label>
                                <el-select v-model="form.identity_document_type_id"
                                           dusk="identity_document_type_id"
                                           filterable
                                           popper-class="el-select-identity_document_type"
                                           @change="changeIdentityDocType">
                                    <el-option v-for="option in identity_document_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.identity_document_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.identity_document_type_id[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.number}"
                                 class="form-group">
                                <label class="control-label">
                                    Número
                                </label>
                                <div v-if="api_service_token != false">
                                    <x-input-service v-model="form.number"
                                                     :identity_document_type_id="form.identity_document_type_id"
                                                     @search="searchNumber"></x-input-service>
                                </div>
                                <div v-else>
                                    <el-input v-model="form.number"
                                              :maxlength="maxLength"
                                              dusk="number">
                                        <template
                                            v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                            <el-button slot="append"
                                                       :loading="loading_search"
                                                       icon="el-icon-search"
                                                       type="primary"
                                                       @click.prevent="searchCustomer">
                                                <template v-if="form.identity_document_type_id === '6'">
                                                    SUNAT
                                                </template>
                                                <template v-if="form.identity_document_type_id === '1'">
                                                    RENIEC
                                                </template>
                                            </el-button>
                                        </template>
                                    </el-input>
                                </div>

                                <small
                                    v-if="errors.number"
                                    class="form-control-feedback"
                                    v-text="errors.number[0]">
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div :class="{'has-danger': errors.name}"
                                 class="form-group">
                                <label class="control-label">Nombre <span class="text-danger">*</span></label>
                                <el-input v-model="form.name"
                                          dusk="name"></el-input>
                                <small v-if="errors.name"
                                       class="form-control-feedback"
                                       v-text="errors.name[0]"></small>
                            </div>
                        </div>
                        <!--
                        <div class="col-md-4">
                            <div :class="{'has-danger': errors.trade_name}"
                                 class="form-group">
                                <label class="control-label">Nombre comercial</label>
                                <el-input v-model="form.trade_name"
                                          dusk="trade_name"></el-input>
                                <small v-if="errors.trade_name"
                                       class="form-control-feedback"
                                       v-text="errors.trade_name[0]"></small>
                            </div>

                        </div>
                        -->

                        <div class="col-md-4">
                            <div :class="{'has-danger': errors.internal_code}"
                                 class="form-group">
                                <label class="control-label">Código interno</label>
                                <el-input v-model="form.internal_code"></el-input>
                                <small v-if="errors.internal_code"
                                       class="form-control-feedback"
                                       v-text="errors.internal_code[0]"></small>
                            </div>
                        </div>

                    </div>
                    <!--
                    <div class="row" v-if="type === 'customers'">
                        <div class="col-md-4">
                            <div class="form-group" :class="{'has-danger': errors.person_type_id}">
                                <label class="control-label">Tipo de cliente</label>
                                <el-select v-model="form.person_type_id" filterable  >
                                    <el-option v-for="option in person_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.person_type_id" v-text="errors.person_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group"  >
                                <label class="control-label">Comentario</label>
                                <el-input v-model="form.comment"></el-input>
                            </div>
                        </div>
                    </div>
                    -->

                    <!--
                    <div class="row">

                        <div class="col-md-3">
                            <div :class="{'has-danger': errors.credit_days}"
                                 class="form-group">
                                <label class="control-label">Dias de crédito</label>
                                <el-input-number
                                    v-model="form.credit_days"
                                    :controls="false"
                                    :min="0"
                                    :precision="0"></el-input-number>
                                <small v-if="errors.credit_days"
                                       class="form-control-feedback"
                                       v-text="errors.credit_days[0]"></small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div :class="{'has-danger': errors.person_type_id}"
                                 class="form-group">
                                <label class="control-label">
                                    {{ typeDialog }}
                                </label>
                                <el-select v-model="form.person_type_id"
                                           clearable
                                           filterable>
                                    <el-option v-for="option in person_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.person_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.person_type_id[0]"></small>
                            </div>
                        </div>

                        <div v-if="form.state"
                             class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Estado del Contribuyente</label>
                                <template v-if="form.state == 'ACTIVO'">
                                    <el-alert :closable="false"
                                              :title="`${form.state}`"
                                              show-icon
                                              type="success"></el-alert>
                                </template>
                                <template v-else>
                                    <el-alert :closable="false"
                                              :title="`${form.state}`"
                                              show-icon
                                              type="error"></el-alert>
                                </template>
                            </div>

                        </div>
                        <div v-if="form.condition"
                             class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Condición del Contribuyente</label>
                                <template v-if="form.condition == 'HABIDO'">
                                    <el-alert :closable="false"
                                              :title="`${form.condition}`"
                                              show-icon
                                              type="success"></el-alert>
                                </template>
                                <template v-else>
                                    <el-alert :closable="false"
                                              :title="`${form.condition}`"
                                              show-icon
                                              type="error"></el-alert>
                                </template>
                            </div>

                        </div>
                    </div>

                    <div v-if="type === 'suppliers'"
                         class="row mt-2">
                        <div class="col-md-6 center-el-checkbox">
                            <div :class="{'has-danger': errors.perception_agent}"
                                 class="form-group">
                                <el-checkbox v-model="form.perception_agent">¿Es agente de percepción?</el-checkbox>
                                <br>
                                <small v-if="errors.perception_agent"
                                       class="form-control-feedback"
                                       v-text="errors.perception_agent[0]"></small>
                            </div>
                        </div>
                        <div v-if="type === 'suppliers'"
                             v-show="form.perception_agent"
                             class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Porcentaje de percepción</label>

                                <el-input v-model="form.percentage_perception"></el-input>
                            </div>
                        </div>
                    </div>
                    -->
                </el-tab-pane>

                <el-tab-pane class
                             name="second">
                    <span slot="label">Dirección</span>
                    <div class="row">
                        <!-- País -->

                        <div class="col-md-3">
                            <div :class="{'has-danger': errors.country_id}"
                                 class="form-group">
                                <label class="control-label">País</label>
                                <el-select v-model="form.country_id"
                                           dusk="country_id"
                                           filterable>
                                    <el-option v-for="option in countries"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.country_id"
                                       class="form-control-feedback"
                                       v-text="errors.country_id[0]"></small>
                            </div>
                        </div>
                        <!-- Departamento -->
                        <div class="col-md-3">
                            <div :class="{'has-danger': errors.department_id}"
                                 class="form-group">
                                <label class="control-label">Departamento</label>
                                <el-select v-model="form.department_id"
                                           dusk="department_id"
                                           filterable
                                           popper-class="el-select-departments"
                                           @change="filterProvince">
                                    <el-option v-for="option in all_departments"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.department_id"
                                       class="form-control-feedback"
                                       v-text="errors.department_id[0]"></small>
                            </div>
                        </div>
                        <!-- Provincia -->
                        <div class="col-md-3">
                            <div :class="{'has-danger': errors.province_id}"
                                 class="form-group">
                                <label class="control-label">Provincia</label>
                                <el-select v-model="form.province_id"
                                           dusk="province_id"
                                           filterable
                                           popper-class="el-select-provinces"
                                           @change="filterDistrict">
                                    <el-option v-for="option in provinces"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.province_id"
                                       class="form-control-feedback"
                                       v-text="errors.province_id[0]"></small>
                            </div>
                        </div>
                        <!-- Distrito -->
                        <div class="col-md-3">
                            <div :class="{'has-danger': errors.province_id}"
                                 class="form-group">
                                <label class="control-label">Distrito</label>
                                <el-select v-model="form.district_id"
                                           dusk="district_id"
                                           filterable
                                           popper-class="el-select-districts">
                                    <el-option v-for="option in districts"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.district_id"
                                       class="form-control-feedback"
                                       v-text="errors.district_id[0]"></small>
                            </div>
                        </div>
                        <!-- Direccion -->
                        <div class="col-md-12">
                            <div :class="{'has-danger': errors.address}"
                                 class="form-group">
                                <label class="control-label">Dirección</label>
                                <el-input v-model="form.address"
                                          dusk="address"></el-input>
                                <small v-if="errors.address"
                                       class="form-control-feedback"
                                       v-text="errors.address[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Telefono -->
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.telephone}"
                                 class="form-group">
                                <label class="control-label">Teléfono</label>
                                <el-input v-model="form.telephone"
                                          dusk="telephone"></el-input>
                                <small v-if="errors.telephone"
                                       class="form-control-feedback"
                                       v-text="errors.telephone[0]"></small>
                            </div>
                        </div>
                        <!-- Correo electronico contacto -->
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.email}"
                                 class="form-group">
                                <label class="control-label">Correo electrónico</label>
                                <el-input v-model="form.email"
                                          dusk="email"></el-input>
                                <small v-if="errors.email"
                                       class="form-control-feedback"
                                       v-text="errors.email[0]"></small>
                            </div>
                        </div>

                        <!-- Correos electronicos alterno -->
                        <!--
                        <div class="col-6">
                            <div
                                class="form-group">
                                <label class="control-label">Correos opcionales </label>
                                <el-input

                                    v-model="temp_email"
                                    dusk="email"
                                    @change="checkEmail()"
                                >

                                </el-input>


                                <el-button
                                    v-if="temp_email != null && temp_email.length > 1 && checkEmail() == true"
                                    icon="el-icon-plus"
                                    size="mini"
                                    @click.prevent="clickAddMail()">
                                    Agregar Correo
                                </el-button>

                                <label v-if="temp_optional_email !== undefined &&
                                     temp_optional_email.length > 0"
                                       class="control-label">
                                    <el-tag
                                        v-for="mail in temp_optional_email"
                                        :key="mail.email"
                                        :closable="true"
                                        :type="'success'"
                                        @close="removeEmail(mail)"
                                    >
                                        {{ mail.email }}
                                    </el-tag>

                                </label>
                                <small v-if="errors.temp_email && errors.temp_email.length > 0"
                                       class="form-control-feedback"
                                       v-text="errors.temp_email"></small>


                                <small v-if="errors.email"
                                       class="form-control-feedback"
                                       v-text="errors.email[0]"></small>
                            </div>
                        </div>
                        -->
                        <!-- Correos electronicos alterno -->
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-12 text-center">
                            <el-button icon="el-icon-plus"
                                       size="mini"
                                       @click.prevent="clickAddAddress()">
                                Agregar dirección
                            </el-button>
                        </div>
                    </div>
                    <div v-for="(row, index) in form.addresses"
                         class="row m-t-10">
                        <div class="col-md-12">
                            <label v-if="index === 0"
                                   class="control-label">
                                Dirección principal
                            </label>
                            <label v-else
                                   class="control-label">
                                Dirección secundaria # {{ index }}
                                <el-button class="btn-default-danger"
                                           icon="el-icon-minus"
                                           size="mini"
                                           @click.prevent="clickRemoveAddress(index)">Eliminar dirección
                                </el-button>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div :class="{'has-danger': errors.country_id}"
                                 class="form-group">
                                <label class="control-label">País</label>
                                <el-select v-model="row.country_id"
                                           filterable>
                                    <el-option v-for="option in countries"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.country_id"
                                       class="form-control-feedback"
                                       v-text="errors.country_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div :class="{'has-danger': errors.location_id}"
                                 class="form-group">
                                <label class="control-label">Ubigeo</label>
                                <el-cascader v-model="row.location_id"
                                             :clearable="true"
                                             :options="locations"
                                             filterable></el-cascader>
                                <small v-if="errors.location_id"
                                       class="form-control-feedback"
                                       v-text="errors.location_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div :class="{'has-danger': errors.address}"
                                 class="form-group">
                                <label class="control-label">Dirección</label>
                                <el-input v-model="row.address"></el-input>
                                <small v-if="errors.address"
                                       class="form-control-feedback"
                                       v-text="errors.address[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.phone}"
                                 class="form-group">
                                <label class="control-label">Teléfono</label>
                                <el-input v-model="row.phone"></el-input>
                                <small v-if="errors.phone"
                                       class="form-control-feedback"
                                       v-text="errors.phone[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.email}"
                                 class="form-group">
                                <label class="control-label">Correo electrónico</label>
                                <el-input v-model="row.email"></el-input>
                                <small v-if="errors.email"
                                       class="form-control-feedback"
                                       v-text="errors.email[0]"></small>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane class
                             name="third">
                    <span slot="label">Otros Datos</span>
                    <div class="row ">
                        <div class="col-12">
                            <h4>Contacto</h4>
                        </div>
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.contact}"
                                 class="form-group">
                                <label class="control-label">Nombre y Apellido</label>
                                <el-input v-model="form.contact.full_name"></el-input>
                                <small v-if="errors.contact"
                                       class="form-control-feedback"
                                       v-text="errors.contact[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.contact}"
                                 class="form-group">
                                <label class="control-label">Teléfono</label>
                                <el-input v-model="form.contact.phone"></el-input>
                                <small v-if="errors.contact"
                                       class="form-control-feedback"
                                       v-text="errors.contact[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--Zona -->
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.zone }"
                                 class="form-group">
                                <label class="control-label">Zona</label>
                                <el-input v-model="form.zone"></el-input>
                                <small v-if="errors.zone"
                                       class="form-control-feedback"
                                       v-text="errors.zone[0]"></small>
                            </div>
                        </div>
                        <!--SitioWeb -->
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.website }"
                                 class="form-group">
                                <label class="control-label">Sitio Web</label>
                                <el-input v-model="form.website"></el-input>
                                <small v-if="errors.website"
                                       class="form-control-feedback"
                                       v-text="errors.website[0]"></small>
                            </div>
                        </div>
                        <!--Observaciones -->
                        <div class="col-md-6">
                            <div :class="{'has-danger': errors.observation }"
                                 class="form-group">
                                <label class="control-label">Observaciones</label>
                                <el-input v-model="form.observation"></el-input>
                                <small v-if="errors.observation"
                                       class="form-control-feedback"
                                       v-text="errors.observation[0]"></small>
                            </div>
                        </div>
                        <!--ID Días Crédito -->

                    </div>
                </el-tab-pane>



                <!--
                <el-tab-pane class
                             name="first">
                    <span slot="label">
                        Datos del cliente
                    </span>
                    <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    :class="{'has-danger': errors.identity_document_type_id}"
                                    class="form-group">
                                    <label class="control-label">
                                        Tipo Doc. Identidad
                                    </label>
                                    <el-select
                                        v-model="form.identity_document_type_id"
                                        filterable>
                                        <el-option
                                            v-for="option in identity_document_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.identity_document_type_id"
                                        class="form-control-feedback"
                                        v-text="errors.identity_document_type_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.number}"
                                     class="form-group">
                                    <label class="control-label">
                                        Número
                                    </label>
                                    <el-input
                                        v-model="form.number"
                                        :maxlength="maxLength">
                                        <template
                                            v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                            <el-button
                                                slot="append"
                                                :loading="loading_search"
                                                icon="el-icon-search"
                                                type="primary"
                                                @click.prevent="searchCustomer">
                                            </el-button>
                                        </template>
                                    </el-input>
                                    <small
                                        v-if="errors.number"
                                        class="form-control-feedback"
                                        v-text="errors.number[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div
                                    :class="{'has-danger': errors.name}"
                                    class="form-group">
                                    <label class="control-label">
                                        Nombre
                                    </label>
                                    <el-input v-model="form.name">
                                    </el-input>
                                    <small
                                        v-if="errors.name"
                                        class="form-control-feedback"
                                        v-text="errors.name[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.trade_name}"
                                     class="form-group">
                                    <label class="control-label">
                                        Nombre comercial
                                    </label>
                                    <el-input
                                        v-model="form.trade_name">
                                    </el-input>
                                    <small
                                        v-if="errors.trade_name"
                                        class="form-control-feedback"
                                        v-text="errors.trade_name[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.country_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        País
                                    </label>
                                    <el-select
                                        v-model="form.country_id"
                                        filterable>
                                        <el-option
                                            v-for="option in countries"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small v-if="errors.country_id"
                                           class="form-control-feedback"
                                           v-text="errors.country_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.department_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Departamento
                                    </label>
                                    <el-select
                                        v-model="form.department_id"
                                        filterable
                                        @change="filterProvince">
                                        <el-option
                                            v-for="option in all_departments"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.department_id"
                                        class="form-control-feedback"
                                        v-text="errors.department_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.province_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Provincia
                                    </label>
                                    <el-select
                                        v-model="form.province_id"
                                        filterable
                                        @change="filterDistrict">
                                        <el-option
                                            v-for="option in provinces"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.province_id"
                                        class="form-control-feedback"
                                        v-text="errors.province_id[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.province_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Distrito
                                    </label>
                                    <el-select
                                        v-model="form.district_id"
                                        filterable>
                                        <el-option
                                            v-for="option in districts"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>
                                    <small v-if="errors.district_id"
                                           class="form-control-feedback"
                                           v-text="errors.district_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div :class="{'has-danger': errors.address}"
                                     class="form-group">
                                    <label class="control-label">
                                        Dirección
                                    </label>
                                    <el-input
                                        v-model="form.address">
                                    </el-input>
                                    <small
                                        v-if="errors.address"
                                        class="form-control-feedback"
                                        v-text="errors.address[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.telephone}"
                                     class="form-group">
                                    <label class="control-label">
                                        Teléfono
                                    </label>
                                    <el-input
                                        v-model="form.telephone">
                                    </el-input>
                                    <small
                                        v-if="errors.telephone"
                                        class="form-control-feedback"
                                        v-text="errors.telephone[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.email}"
                                     class="form-group">
                                    <label class="control-label">
                                        Correo electrónico
                                    </label>
                                    <el-input
                                        v-model="form.email">
                                    </el-input>
                                    <small
                                        v-if="errors.email"
                                        class="form-control-feedback"
                                        v-text="errors.email[0]">
                                    </small>
                                </div>
                            </div>
                        </div>





                    </div>

                </el-tab-pane>
                -->
                <el-tab-pane class
                             name="four">
                    <span slot="label">Datos de los hijos </span>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="font-weight-bold">Tipo de documento</th>
                                            <th class="font-weight-bold">Número</th>
                                            <th class="font-weight-bold">Nombre</th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody v-if="form.childrens !== undefined && form.childrens.length > 0">
                                        <tr v-for="(row, index) in form.childrens"
                                            :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ getTextDocumentType(row.identity_document_type_id) }}</td>
                                            <td>{{ row.number }}</td>
                                            <td>{{ row.name }}</td>

                                            <td class="text-right">
                                                <button class="btn waves-effect waves-light btn-xs btn-info"
                                                        type="button"
                                                        @click="ediItem(row, index)">
                                                    <span style='font-size:10px;'>&#9998;</span>
                                                </button>
                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveItem(index)">
                                                    x
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="9"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12  text-center">
                                <div class="form-group">
                                    <button class="btn waves-effect waves-light btn-primary"
                                            type="button"
                                            @click="clickAddItem">+ Agregar Hijo
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </el-tab-pane>

            </el-tabs>
            <div class="form-actions text-right mt-4">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary">
                    Guardar
                </el-button>
            </div>
        </form>

        <person-form
            :parentId="form.id"
            :recordId="recordIdChildren"
            :reload-data="getData"
            :showDialog.sync="showDialogChildren"
            :type="'customers'"
            @add="addRow"

        >

        </person-form>
    </el-dialog>

</template>

<script>

import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import PersonForm from "./person.vue"
import {serviceNumber} from '../../../../../../resources/js/mixins/functions'

export default {
    components: {
        PersonForm
    },
    mixins: [
        serviceNumber
    ],
    props: [
        'showDialog',
        'recordId',
        'external'
    ],
    data() {
        return {
            recordIdChildren: null,
            loading_submit: false,
            titleDialog: null,
            showDialogChildren: false,
            parent: null,
            errors: {},
            type: 'customers',
            tabActive: 'first',
            form: {},
            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            identity_document_types: [],



            api_service_token : null,
            person_types: [],
            locations : []
        }
    },
    created() {
        this.initForm()
        this.$http
            .post(`/suscription/${this.resource}/tables`, {})
            .then(response => {
                this.countries = response.data.countries
                this.all_departments = response.data.departments
                this.all_provinces = response.data.provinces
                this.all_districts = response.data.districts
                this.identity_document_types = response.data.identity_document_types
            })

        this.$http.post(`/suscription/${this.resource}/tables`)
            .then(response => {
                this.api_service_token = response.data.api_service_token
                this.countries = response.data.countries
                this.all_departments = response.data.departments
                this.all_provinces = response.data.provinces
                this.all_districts = response.data.districts
                this.person_types = response.data.person_types
                this.identity_document_types= response.data.identity_document_types
                this.locations = response.data.locations
            }) .finally(()=>{
            if(this.api_service_token === false &&
                this.config.api_service_token !== undefined &&
                this.config.api_service_token !== null) {
                    this.api_service_token = this.config.api_service_token
                }
            })
    },
    computed: {
        ...mapState([
            'config',
            'resource',
            'person',
            'parentPerson',
        ]),
        maxLength: function () {
            if (this.form.identity_document_type_id === '066') {
                return 11
            }
            if (this.form.identity_document_type_id === '061') {
                return 8
            }
        }


    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        getContact(){
            if(this.form.contact=== undefined|| this.form.contact=== null){
                this.form.contact = {
                    full_name:'',
                    phone:'',
                }
            }
            if(this.form.contact.full_name === undefined||this.form.contact.full_name === null){
                this.form.contact.full_name = '';
            }
            if(this.form.contact.phone === undefined||this.form.contact.phone === null){
                this.form.contact.phone = '';
            }

                },
        initForm() {
            this.tabActive = 'first'
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '1',
                number: null,
                name: null,
                trade_name: null,
                country_id: 'PE',
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                email: null,
                type: 'customers',
                credit_days: 0,
                condition: null,
                state: null,
                perception_agent: false,
                percentage_perception: 0,
                person_type_id: null,
                comment: null,
                addresses: [],
                contact: {
                    full_name: null,
                    phone: null,
                },
                optional_email: []
            }
            this.$store.commit('setParentPerson', this.form)

        },
        create() {
            this.form.identity_document_type_id = "1"
            this.initForm()
            this.parent = this.form.id
            this.titleDialog = (this.recordId) ? 'Editar Cliente' : 'Nuevo Cliente'
            if (this.recordId) {
                this.getData()
            }
        },
        getData() {

            this.$http
                .post(`/suscription/${this.resource}/record`, {
                    person: this.recordId,
                })
                .then(response => {
                    this.form = response.data.data
                    this.getContact()
                    this.$store.commit('setParentPerson', response.data.data)
                    this.filterProvinces()
                    this.filterDistricts()
                })
        },

        submit() {
            this.loading_submit = true
            this.$http.post(`/suscription/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataCustomers', response.data.id)
                        } else {
                            this.$eventHub.$emit('reloadData')
                        }
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        console.log(error)
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
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        clickAddItem() {
            this.$store.commit('setPerson', {})

            this.recordItem = null;
            this.showDialogChildren = true;
        },
        ediItem(row, index) {
            row.indexi = index
            this.$store.commit('setPerson', row)

            this.recordIdChildren = row.id
            this.showDialogChildren = true

        },
        clickRemoveItem(index) {
            this.form.childrens.splice(index, 1)

        },
        getTextDocumentType(identity_document_type_id){

            let doc = _.find(this.identity_document_types, {'id': identity_document_type_id});
            if(doc !== undefined){
                return doc.description
            }
            return '-'

        },
        addRow(data) {
            if (this.form.childrens === undefined) this.form.childrens = []
            let child = data, hasChild = false;

            let index = null;
            if (data.indexi !== undefined) {
                index = data.indexi
            }

            if (index !== null) {
                this.form.childrens[index] = data
            } else {
                this.form.childrens.push(data)
            }


            this.$store.commit('setPerson', {})

        },
        setDataDefaultCustomer() {

            if (this.form.identity_document_type_id == '0') {
                this.form.number = '99999999'
                this.form.name = "Clientes - Varios"
            } else {
                this.form.number = ''
                this.form.name = null
            }

        },
        changeIdentityDocType() {
            (this.recordId == null) ? this.setDataDefaultCustomer() : null
        },
        searchNumber(data) {
            this.form.name = (this.form.identity_document_type_id === '1') ? data.nombre_completo : data.nombre_o_razon_social;
            this.form.trade_name = (this.form.identity_document_type_id === '6') ? data.nombre_o_razon_social : '';
            this.form.location_id = data.ubigeo;
            this.form.address = data.direccion;
            this.form.department_id = (data.ubigeo) ? (data.ubigeo[0] != '-' ? data.ubigeo[0] : null) : null;
            this.form.province_id = (data.ubigeo) ? (data.ubigeo[1] != '-' ? data.ubigeo[1] : null) : null;
            this.form.district_id = (data.ubigeo) ? (data.ubigeo[2] != '-' ? data.ubigeo[2] : null) : null;
            this.form.condition = data.condicion;
            this.form.state = data.estado;

            this.filterProvinces()
            this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
        },
    }
}
</script>
