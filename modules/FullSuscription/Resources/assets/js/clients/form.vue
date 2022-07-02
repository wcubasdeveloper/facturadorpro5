<template>
    <el-dialog
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create"
    >
        <form autocomplete="off"
              @submit.prevent="submit">
            <el-tabs v-model="tabActive">
                <!--Tipo Doc. Identidad-->
                <!--Número-->
                <!--Gitlab user-->
                <!--Nombre-->
                <!--Nombre comercial-->
                <!--Código interno-->
                <!--Dias de crédito-->
                <!-- person_types  -->
                <!-- Estado del Contribuyente -->
                <!--  Condición del Contribuyente -->
                <el-tab-pane class
                             name="first">
                    <span slot="label">
                        Datos del cliente
                    </span>
                    <!--Tipo Doc. Identidad-->
                    <!--Número-->
                    <!--Nombre-->
                    <!--Gitlab user-->
                    <!--Usuario de discord-->
                    <!--Código interno-->
                    <!--Nombre comercial-->
                    <div class="row">
                        <!--Tipo Doc. Identidad-->
                        <div class="col-md-6">
                            <div
                                :class="{
                                    'has-danger':
                                        errors.identity_document_type_id
                                }"
                                class="form-group"
                            >
                                <label class="control-label">
                                    Tipo Doc. Identidad
                                    <span class="text-danger">*</span></label
                                >
                                <el-select
                                    v-model="form.identity_document_type_id"
                                    dusk="identity_document_type_id"
                                    filterable
                                    popper-class="el-select-identity_document_type"
                                    @change="changeIdentityDocType"
                                >
                                    <el-option
                                        v-for="option in identity_document_types"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.identity_document_type_id"
                                    class="form-control-feedback"
                                    v-text="errors.identity_document_type_id[0]"
                                ></small>
                            </div>
                        </div>
                        <!--Número-->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.number }"
                                class="form-group"
                            >
                                <label class="control-label">
                                    Número
                                </label>
                                <div v-if="api_service_token != false">
                                    <x-input-service
                                        v-model="form.number"
                                        :identity_document_type_id="
                                            form.identity_document_type_id
                                        "
                                        @search="searchNumber"
                                    ></x-input-service>
                                </div>
                                <div v-else>
                                    <el-input
                                        v-model="form.number"
                                        :maxlength="maxLength"
                                        dusk="number"
                                    >
                                        <template
                                            v-if="
                                                form.identity_document_type_id ===
                                                    '6' ||
                                                    form.identity_document_type_id ===
                                                        '1'
                                            "
                                        >
                                            <el-button
                                                slot="append"
                                                :loading="loading_search"
                                                icon="el-icon-search"
                                                type="primary"
                                                @click.prevent="searchCustomer"
                                            >
                                                <template
                                                    v-if="
                                                        form.identity_document_type_id ===
                                                            '6'
                                                    "
                                                >
                                                    SUNAT
                                                </template>
                                                <template
                                                    v-if="
                                                        form.identity_document_type_id ===
                                                            '1'
                                                    "
                                                >
                                                    RENIEC
                                                </template>
                                            </el-button>
                                        </template>
                                    </el-input>
                                </div>

                                <small
                                    v-if="errors.number"
                                    class="form-control-feedback"
                                    v-text="errors.number[0]"
                                >
                                </small>
                            </div>
                        </div>
                        <!--Nombre-->
                        <div class="col-md-12">
                            <div
                                :class="{ 'has-danger': errors.name }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Nombre
                                    <span class="text-danger">*</span></label
                                >
                                <el-input
                                    v-model="form.name"
                                    dusk="name"
                                ></el-input>
                                <small
                                    v-if="errors.name"
                                    class="form-control-feedback"
                                    v-text="errors.name[0]"
                                ></small>
                            </div>
                        </div>
                        <!--Código interno-->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.internal_code }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Código interno</label
                                >
                                <el-input
                                    v-model="form.internal_code"
                                ></el-input>
                                <small
                                    v-if="errors.internal_code"
                                    class="form-control-feedback"
                                    v-text="errors.internal_code[0]"
                                ></small>
                            </div>
                        </div>
                        <!--Nombre comercial-->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.trade_name }"
                                class="form-group"
                            >
                                <label class="control-label">
                                    Nombre Comercial
                                </label
                                >
                                <el-input
                                    v-model="form.trade_name"
                                    dusk="trade_name"
                                ></el-input>
                                <small
                                    v-if="errors.trade_name"
                                    class="form-control-feedback"
                                    v-text="errors.trade_name[0]"
                                ></small>
                            </div>
                        </div>


                    </div>
                    <!--Dias de crédito-->
                    <!-- person_types  -->
                    <!-- Estado del Contribuyente -->
                    <!--  Condición del Contribuyente -->
                    <div class="row">
                        <!--Dias de crédito-->
                        <!--
                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.credit_days }"
                                class="form-group"
                            >
                                <label class="control-label"
                                    >Dias de crédito</label
                                >
                                <el-input-number
                                    v-model="form.credit_days"
                                    :controls="false"
                                    :min="0"
                                    :precision="0"
                                ></el-input-number>
                                <small
                                    v-if="errors.credit_days"
                                    class="form-control-feedback"
                                    v-text="errors.credit_days[0]"
                                ></small>
                            </div>
                        </div>
                        -->
                        <!-- person_types  -->
                        <!--
                        <div class="col-md-4">
                            <div
                                :class="{ 'has-danger': errors.person_type_id }"
                                class="form-group"
                            >
                                <label class="control-label">
                                    {{ typeDialog }}
                                </label>

                                <el-select
                                    v-model="form.person_type_id"
                                    clearable
                                    filterable
                                >
                                    <el-option
                                        v-for="option in person_types"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.person_type_id"
                                    class="form-control-feedback"
                                    v-text="errors.person_type_id[0]"
                                ></small>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.barcode }"
                                class="form-group"
                            >
                                <label class="control-label"
                                    >Código de barra</label
                                >
                                <el-input v-model="form.barcode"></el-input>
                                <small
                                    v-if="errors.barcode"
                                    class="form-control-feedback"
                                    v-text="errors.barcode[0]"
                                ></small>
                            </div>
                        </div>
                        -->
                        <!-- Estado del Contribuyente -->
                        <div v-if="form.state"
                             class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                >Estado del Contribuyente</label
                                >
                                <template v-if="form.state == 'ACTIVO'">
                                    <el-alert
                                        :closable="false"
                                        :title="`${form.state}`"
                                        show-icon
                                        type="success"
                                    ></el-alert>
                                </template>
                                <template v-else>
                                    <el-alert
                                        :closable="false"
                                        :title="`${form.state}`"
                                        show-icon
                                        type="error"
                                    ></el-alert>
                                </template>
                            </div>
                        </div>
                        <!--  Condición del Contribuyente -->
                        <div v-if="form.condition"
                             class="col-md-6">
                            <div class="form-group">
                                <label class="control-label"
                                >Condición del Contribuyente</label
                                >
                                <template v-if="form.condition == 'HABIDO'">
                                    <el-alert
                                        :closable="false"
                                        :title="`${form.condition}`"
                                        show-icon
                                        type="success"
                                    ></el-alert>
                                </template>
                                <template v-else>
                                    <el-alert
                                        :closable="false"
                                        :title="`${form.condition}`"
                                        show-icon
                                        type="error"
                                    ></el-alert>
                                </template>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <!-- País -->
                <!-- Departamento -->
                <!-- Provincia -->
                <!-- Distrito -->
                <!-- Direccion -->
                <!-- Telefono -->
                <!-- Correo electronico contacto -->
                <!-- Correos electronicos alterno -->
                <!-- direcciones -->
                <el-tab-pane class
                             name="second">
                    <span slot="label">Dirección</span>
                    <!-- País -->
                    <!-- Departamento -->
                    <!-- Provincia -->
                    <!-- Distrito -->
                    <!-- Direccion -->
                    <div class="row">
                        <!-- País -->

                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.country_id }"
                                class="form-group"
                            >
                                <label class="control-label">País</label>
                                <el-select
                                    v-model="form.country_id"
                                    dusk="country_id"
                                    filterable
                                >
                                    <el-option
                                        v-for="option in countries"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.country_id"
                                    class="form-control-feedback"
                                    v-text="errors.country_id[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Departamento -->
                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.department_id }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Departamento</label
                                >
                                <el-select
                                    v-model="form.department_id"
                                    dusk="department_id"
                                    filterable
                                    popper-class="el-select-departments"
                                    @change="filterProvince"
                                >
                                    <el-option
                                        v-for="option in all_departments"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.department_id"
                                    class="form-control-feedback"
                                    v-text="errors.department_id[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Provincia -->
                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.province_id }"
                                class="form-group"
                            >
                                <label class="control-label">Provincia</label>
                                <el-select
                                    v-model="form.province_id"
                                    dusk="province_id"
                                    filterable
                                    popper-class="el-select-provinces"
                                    @change="filterDistrict"
                                >
                                    <el-option
                                        v-for="option in provinces"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.province_id"
                                    class="form-control-feedback"
                                    v-text="errors.province_id[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Distrito -->
                        <div class="col-md-3">
                            <div
                                :class="{ 'has-danger': errors.province_id }"
                                class="form-group"
                            >
                                <label class="control-label">Distrito</label>
                                <el-select
                                    v-model="form.district_id"
                                    dusk="district_id"
                                    filterable
                                    popper-class="el-select-districts"
                                >
                                    <el-option
                                        v-for="option in districts"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.district_id"
                                    class="form-control-feedback"
                                    v-text="errors.district_id[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Direccion -->
                        <div class="col-md-12">
                            <div
                                :class="{ 'has-danger': errors.address }"
                                class="form-group"
                            >
                                <label class="control-label">Dirección</label>
                                <el-input
                                    v-model="form.address"
                                    dusk="address"
                                ></el-input>
                                <small
                                    v-if="errors.address"
                                    class="form-control-feedback"
                                    v-text="errors.address[0]"
                                ></small>
                            </div>
                        </div>
                    </div>
                    <!-- Telefono -->
                    <!-- Correo electronico contacto -->
                    <!-- Correos electronicos alterno -->
                    <div class="row">
                        <!-- Telefono -->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.telephone }"
                                class="form-group"
                            >
                                <label class="control-label">Teléfono</label>
                                <el-input
                                    v-model="form.telephone"
                                    dusk="telephone"
                                ></el-input>
                                <small
                                    v-if="errors.telephone"
                                    class="form-control-feedback"
                                    v-text="errors.telephone[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Correo electronico contacto -->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.email }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Correo electrónico</label
                                >
                                <el-input
                                    v-model="form.email"
                                    dusk="email"
                                ></el-input>
                                <small
                                    v-if="errors.email"
                                    class="form-control-feedback"
                                    v-text="errors.email[0]"
                                ></small>
                            </div>
                        </div>
                        <!-- Correos electronicos alterno -->
                        <!--
                        <div class="col-6">

                            <div class="form-group">
                                <label class="control-label"
                                    >Correos opcionales
                                </label>
                                <el-input
                                    v-model="form.temp_email"
                                    dusk="email"
                                    @change="checkEmail()"
                                >
                                </el-input>

                                <el-button
                                    v-if="
                                        temp_email != null &&
                                            temp_email.length > 1 &&
                                            checkEmail() == true
                                    "
                                    icon="el-icon-plus"
                                    size="mini"
                                    @click.prevent="clickAddMail()"
                                >
                                    Agregar Correo
                                </el-button>

                                <label
                                    v-if="
                                        temp_optional_email !== undefined &&
                                            temp_optional_email.length > 0
                                    "
                                    class="control-label"
                                >
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
                                <small
                                    v-if="
                                        errors.temp_email &&
                                            errors.temp_email.length > 0
                                    "
                                    class="form-control-feedback"
                                    v-text="errors.temp_email"
                                ></small>

                                <small
                                    v-if="errors.email"
                                    class="form-control-feedback"
                                    v-text="errors.email[0]"
                                ></small>
                            </div>
                        </div>
                    --></div>
                    <!-- direcciones -->
                    <div class="row m-t-10">
                        <div class="col-md-12 text-center">
                            <el-button
                                icon="el-icon-plus"
                                size="mini"
                                @click.prevent="clickAddAddress()"
                            >
                                Agregar dirección
                            </el-button>
                        </div>
                    </div>
                    <div
                        v-for="(row, index) in form.addresses"
                        class="row m-t-10"
                    >
                        <div class="col-md-12">
                            <label v-if="index === 0"
                                   class="control-label">
                                Dirección principal
                            </label>
                            <label v-else
                                   class="control-label">
                                Dirección secundaria # {{ index }}
                                <el-button
                                    class="btn-default-danger"
                                    icon="el-icon-minus"
                                    size="mini"
                                    @click.prevent="clickRemoveAddress(index)"
                                >Eliminar dirección
                                </el-button>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <div
                                :class="{ 'has-danger': errors.country_id }"
                                class="form-group"
                            >
                                <label class="control-label">País</label>
                                <el-select v-model="row.country_id"
                                           filterable>
                                    <el-option
                                        v-for="option in countries"
                                        :key="option.id"
                                        :label="option.description"
                                        :value="option.id"
                                    ></el-option>
                                </el-select>
                                <small
                                    v-if="errors.country_id"
                                    class="form-control-feedback"
                                    v-text="errors.country_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div
                                :class="{ 'has-danger': errors.location_id }"
                                class="form-group"
                            >
                                <label class="control-label">Ubigeo</label>
                                <el-cascader
                                    v-model="row.location_id"
                                    :clearable="true"
                                    :options="locations"
                                    filterable
                                ></el-cascader>
                                <small
                                    v-if="errors.location_id"
                                    class="form-control-feedback"
                                    v-text="errors.location_id[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div
                                :class="{ 'has-danger': errors.address }"
                                class="form-group"
                            >
                                <label class="control-label">Dirección</label>
                                <el-input v-model="row.address"></el-input>
                                <small
                                    v-if="errors.address"
                                    class="form-control-feedback"
                                    v-text="errors.address[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.phone }"
                                class="form-group"
                            >
                                <label class="control-label">Teléfono</label>
                                <el-input v-model="row.phone"></el-input>
                                <small
                                    v-if="errors.phone"
                                    class="form-control-feedback"
                                    v-text="errors.phone[0]"
                                ></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.email }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Correo electrónico</label
                                >
                                <el-input v-model="row.email"></el-input>
                                <small
                                    v-if="errors.email"
                                    class="form-control-feedback"
                                    v-text="errors.email[0]"
                                ></small>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <!--Nombre y Apellido-->

                <!--Teléfono-->

                <el-tab-pane class
                             name="third">
                    <span slot="label">Otros Datos</span>
                    <!--Nombre y Apellido-->
                    <!--Teléfono-->
                    <!--Zona -->
                    <!--SitioWeb -->
                    <!--Observaciones -->
                    <!--ID Días Crédito -->
                    <div class="row ">
                        <div class="col-12">
                            <h4>Contacto</h4>
                        </div>
                        <!--Nombre y Apellido-->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.contact }"
                                class="form-group"
                            >
                                <label class="control-label"
                                >Nombre y Apellido</label
                                >
                                <el-input
                                    v-model="form.contact.full_name"
                                ></el-input>
                                <small
                                    v-if="errors.contact"
                                    class="form-control-feedback"
                                    v-text="errors.contact[0]"
                                ></small>
                            </div>
                        </div>
                        <!--Teléfono-->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.contact }"
                                class="form-group"
                            >
                                <label class="control-label">Teléfono</label>
                                <el-input
                                    v-model="form.contact.phone"
                                ></el-input>
                                <small
                                    v-if="errors.contact"
                                    class="form-control-feedback"
                                    v-text="errors.contact[0]"
                                ></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!--Zona -->
                        <!--
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
                        -->
                        <!--SitioWeb -->
                        <div class="col-md-6">
                            <div
                                :class="{ 'has-danger': errors.website }"
                                class="form-group"
                            >
                                <label class="control-label">Sitio Web</label>
                                <el-input v-model="form.website"></el-input>
                                <small
                                    v-if="errors.website"
                                    class="form-control-feedback"
                                    v-text="errors.website[0]"
                                ></small>
                            </div>
                        </div>
                        <!--Observaciones -->
                        <!--
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
                        -->
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

                <!-- cambiar aqui -->


                <el-tab-pane class
                             name="four">

                    <span slot="label">
                        Datos Servidor
                    </span>

                    <div class="form-body">
                        <div class="row">

                            <!--Gitlab user-->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.gitlab_user }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Usuario Gitlab
                                    </label>
                                    <el-input
                                        v-model="form.gitlab_user"
                                        dusk="gitlab_user"
                                    >
                                    </el-input>
                                    <small
                                        v-if="errors.gitlab_user"
                                        class="form-control-feedback"
                                        v-text="errors.gitlab_user[0]"
                                    >
                                    </small>
                                </div>
                            </div>
                            <!-- Nombre del canal Slack -->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.slack_channel }"
                                    class="form-group">
                                    <label class="control-label">
                                        Nombre del canal Slack
                                    </label>
                                    <el-input
                                        v-model="form.slack_channel"
                                        dusk="slack_channel"
                                    ></el-input>
                                    <small
                                        v-if="errors.slack_channel"
                                        class="form-control-feedback"
                                        v-text="errors.slack_channel[0]"
                                    ></small>
                                </div>
                            </div>
                            <!--Usuario de discord-->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.discord_user }"
                                    class="form-group"
                                >
                                    <label class="control-label">
                                        Usuario de discord
                                    </label>
                                    <el-input
                                        v-model="form.discord_user"
                                        dusk="discord_user"
                                    >
                                    </el-input>
                                    <small
                                        v-if="errors.discord_user"
                                        class="form-control-feedback"
                                        v-text="errors.discord_user[0]"
                                    >
                                    </small>
                                </div>
                            </div>
                            <!-- Nombre del canal Discord -->
                            <div class="col-md-6">
                                <div
                                    :class="{ 'has-danger': errors.discord_channel }"
                                    class="form-group">
                                    <label class="control-label">
                                        Nombre del canal Discord
                                    </label>
                                    <el-input
                                        v-model="form.discord_channel"
                                        dusk="discord_channel"
                                    ></el-input>
                                    <small
                                        v-if="errors.discord_channel"
                                        class="form-control-feedback"
                                        v-text="errors.discord_channel[0]"
                                    ></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="font-weight-bold">
                                                Host
                                            </th>
                                            <th class="font-weight-bold">
                                                IP
                                            </th>
                                            <th class="font-weight-bold">
                                                User
                                            </th>
                                            <th class="font-weight-bold">
                                                Password
                                            </th>

                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody
                                            v-if="form.servers !== undefined && form.servers.length > 0 "
                                        >
                                        <tr
                                            v-for="(row, index)
                                             in form.servers"
                                            :key="index"
                                        >
                                            <td>{{ index + 1 }}</td>
                                            <td>
                                                {{ row.host}}
                                            </td>
                                            <td>{{ row.ip }}</td>
                                            <td>{{ row.user }}</td>
                                            <td>{{ row.password }}</td>
                                            <td class="text-right">
                                                <button
                                                    class="btn waves-effect waves-light btn-xs btn-info"
                                                    type="button"
                                                    @click="
                                                            ediItem(row, index)
                                                        "
                                                >
                                                        <span
                                                            style="font-size:10px;"
                                                        >&#9998;</span
                                                        >
                                                </button>
                                                <button
                                                    class="btn waves-effect waves-light btn-xs btn-danger"
                                                    type="button"
                                                    @click.prevent="
                                                            clickRemoveItem(
                                                                index
                                                            )
                                                        "
                                                >
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
                                    <button
                                        class="btn waves-effect waves-light btn-primary"
                                        type="button"
                                        @click="clickAddItem"
                                    >
                                        + Agregar Servidor
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
            </el-tabs>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    native-type="submit"
                    type="primary"
                >
                    Guardar
                </el-button>
            </div>
        </form>

        <person-form
            :userId="form.id"
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
import PersonForm from "./person.vue";
import {serviceNumber} from "../../../../../../resources/js/mixins/functions";

export default {
    components: {
        PersonForm
    },
    mixins: [serviceNumber],
    props: ["showDialog", "recordId", "external"],
    data() {
        return {
            recordIdChildren: null,
            loading_submit: false,
            titleDialog: null,
            showDialogChildren: false,
            errors: {},
            type: "customers",
            tabActive: "first",
            form: {
                number: ""
            },
            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            identity_document_types: [],

            api_service_token: null,
            person_types: [],
            locations: []
        };
    },
    created() {
        this.initForm();
        this.$http
            .post(`/full_suscription/${this.resource}/tables`, {})
            .then(response => {
                this.$store.commit('setConfiguration', response.data.configuration);
                this.countries = response.data.countries;
                this.all_departments = response.data.departments;
                this.all_provinces = response.data.provinces;
                this.all_districts = response.data.districts;
                this.identity_document_types =
                    response.data.identity_document_types;
            /*});
        this.$http
            .post(`/full_suscription/${this.resource}/tables`)
            .then(response => {*/
                this.api_service_token = response.data.api_service_token;
                this.countries = response.data.countries;
                this.all_departments = response.data.departments;
                this.all_provinces = response.data.provinces;
                this.all_districts = response.data.districts;
                this.person_types = response.data.person_types;
                this.identity_document_types =
                    response.data.identity_document_types;
                this.locations = response.data.locations;
            })
            .finally(() => {
                if (
                    this.api_service_token === false &&
                    this.config.api_service_token !== undefined &&
                    this.config.api_service_token !== null
                ) {
                    this.api_service_token = this.config.api_service_token;
                }
            });
    },
    computed: {
        ...mapState([
            "config",
            "resource",
            "person",
            "parentPerson"
        ]),
        maxLength: function () {
            if (this.form.identity_document_type_id === "066") {
                return 11;
            }
            if (this.form.identity_document_type_id === "061") {
                return 8;
            }
        }
    },
    methods: {
        ...mapActions([
            "loadConfiguration"
            ]),
        getContact() {
            if (this.form.contact === undefined || this.form.contact === null) {
                this.form.contact = {
                    full_name: "",
                    phone: ""
                };
            }
            if (
                this.form.contact.full_name === undefined ||
                this.form.contact.full_name === null
            ) {
                this.form.contact.full_name = "";
            }
            if (
                this.form.contact.phone === undefined ||
                this.form.contact.phone === null
            ) {
                this.form.contact.phone = "";
            }
        },
        initForm() {
            this.tabActive = "first";
            this.errors = {};
            this.form = {
                id: null,
                identity_document_type_id: "1",
                number: "",
                name: null,
                trade_name: null,
                country_id: "PE",
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                email: null,
                type: "customers",
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
                    phone: null
                },
                optional_email: [],


                discord_user: null,
                slack_channel: null,
                discord_channel: null,
                gitlab_user: null,
            };
            this.$store.commit("setParentPerson", this.form);
        },
        create() {
            this.form.identity_document_type_id = "1";
            this.initForm();
            this.parent = this.form.id;
            this.titleDialog = this.recordId
                ? "Editar Cliente"
                : "Nuevo Cliente";
            if (this.recordId) {
                this.getData();
            }
        },
        getData() {
            this.$http
                .post(`/full_suscription/${this.resource}/record`, {
                    person: this.recordId
                })
                .then(response => {
                    this.form = response.data.data;
                    this.getContact();
                    this.$store.commit("setParentPerson", response.data.data);
                    this.filterProvinces();
                    this.filterDistricts();
                });
        },

        submit() {
            this.loading_submit = true;
            this.$http
                .post(`/full_suscription/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        if (this.external) {
                            this.$eventHub.$emit(
                                "reloadDataCustomers",
                                response.data.id
                            );
                        } else {
                            this.$eventHub.$emit("reloadData");
                        }
                        this.close();
                    } else {
                        this.$message.error(response.data.message);
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data;
                    } else {
                        console.log(error);
                    }
                })
                .then(() => {
                    this.loading_submit = false;
                });
        },
        close() {
            this.$emit("update:showDialog", false);
            this.initForm();
        },
        searchCustomer() {
            this.searchServiceNumberByType();
        },
        clickAddItem() {
            this.$store.commit("setPerson", {});

            this.recordItem = null;
            this.showDialogChildren = true;
        },
        ediItem(row, index) {
            row.indexi = index;
            this.$store.commit("setPerson", row);

            this.recordIdChildren = row.id;
            this.showDialogChildren = true;
        },
        clickRemoveItem(index) {
            this.form.servers.splice(index, 1);
        },
        getTextDocumentType(identity_document_type_id) {
            let doc = _.find(this.identity_document_types, {
                id: identity_document_type_id
            });
            if (doc !== undefined) {
                return doc.description;
            }
            return "-";
        },
        addRow(data) {
            if (this.form.servers === undefined) this.form.servers = [];
            let index = null;
            if (data.indexi !== undefined) {
                index = data.indexi;
            }

            if (index !== null) {
                this.form.servers[index] = data;
            } else {
                this.form.servers.push(data);
            }

            this.$store.commit("setPerson", {});
        },
        setDataDefaultCustomer() {
            if (this.form.identity_document_type_id == "0") {
                this.form.number = "99999999";
                this.form.name = "Clientes - Varios";
            } else {
                this.form.number = "";
                this.form.name = null;
            }
        },
        changeIdentityDocType() {
            this.recordId == null ? this.setDataDefaultCustomer() : null;
        },
        searchNumber(data) {
            this.form.name =
                this.form.identity_document_type_id === "1"
                    ? data.nombre_completo
                    : data.nombre_o_razon_social;
            this.form.trade_name =
                this.form.identity_document_type_id === "6"
                    ? data.nombre_o_razon_social
                    : "";
            this.form.location_id = data.ubigeo;
            this.form.address = data.direccion;
            this.form.department_id = data.ubigeo
                ? data.ubigeo[0] != "-"
                    ? data.ubigeo[0]
                    : null
                : null;
            this.form.province_id = data.ubigeo
                ? data.ubigeo[1] != "-"
                    ? data.ubigeo[1]
                    : null
                : null;
            this.form.district_id = data.ubigeo
                ? data.ubigeo[2] != "-"
                    ? data.ubigeo[2]
                    : null
                : null;
            this.form.condition = data.condicion;
            this.form.state = data.estado;

            this.filterProvinces();
            this.filterDistricts();
            //                this.form.addresses[0].telephone = data.telefono;
        },

        clickAddAddress() {
            /* this.form.more_address.push({
                 location_id: [],
                 address: null,
             })*/

            this.form.addresses.push({
                id: null,
                country_id: "PE",
                location_id: [],
                address: null,
                email: null,
                phone: null,
                main: false
            });
        },
        validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        },
        updateEmail() {
            this.temp_optional_email = this.form.optional_email;
        },
        removeEmail(email) {
            if (this.form.optional_email === undefined)
                this.form.optional_email = [];
            this.form.optional_email = this.form.optional_email.filter(function (
                item
            ) {
                return item.email !== email.email;
            });

            this.updateEmail();
        },
        checkEmail() {
            this.errors.temp_email = null;
            if (this.temp_email === null) {
                return false;
            }
            if (this.temp_email === undefined) {
                return false;
            }
            let email = this.temp_email;

            if (this.validateEmail(email)) {
                if (this.form.optional_email !== undefined) {
                    let tem = _.find(this.form.optional_email, {
                        email: email
                    });
                    if (tem === undefined) {
                        return true;
                    } else {
                        // this.errors.temp_email = "Correo ya registrado"
                    }
                }
            } else {
                // this.errors.temp_email = "No es un correo valido"
            }
            return false;
        },
        clickAddMail() {
            if (this.form.optional_email === undefined)
                this.form.optional_email = [];
            if (this.checkEmail() === true) {
                let email = this.temp_email;
                this.form.optional_email.push({email: email});
                this.updateEmail();
                this.temp_email = null;
            }
        },
        validateDigits() {
            const pattern_number = new RegExp("^[0-9]+$", "i");

            if (this.form.identity_document_type_id === "6") {
                if (this.form.number.length !== 11) {
                    return {
                        success: false,
                        message: `El campo número debe tener 11 dígitos.`
                    };
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    };
                }
            }

            if (this.form.identity_document_type_id === "1") {
                if (this.form.number.length !== 8) {
                    return {
                        success: false,
                        message: `El campo número debe tener 8 dígitos.`
                    };
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    };
                }
            }

            if (["4", "7", "0"].includes(this.form.identity_document_type_id)) {
                const pattern = new RegExp("^[A-Z0-9\-]+$", "i");

                if (!pattern.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número no cumple con el formato establecido`
                    };
                }
            }

            return {
                success: true
            };
        },
        clickRemoveAddress(index) {
            this.form.addresses.splice(index, 1);
        },
        saveZone() {
            this.form_zone.add = false;

            this.$http
                .post(`/zones`, this.form_zone)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.zones.push(response.data.data);
                        this.form_zone.name = null;
                    } else {
                        this.$message.error("No se guardaron los cambios");
                    }
                })
                .catch(error => {
                });
        }
    }
};
</script>
