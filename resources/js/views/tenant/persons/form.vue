<template>
    <el-dialog :close-on-click-modal="false"
               :title="titleDialog"
               :visible="showDialog"
               :append-to-body="true"
               @close="close"
               @open="create"
               @opened="opened">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <el-tabs v-model="activeName">
                    <el-tab-pane class
                                 name="first">
                        <span slot="label">{{ titleTabDialog }}</span>
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
                                    <label class="control-label">Número <span class="text-danger">*</span></label>

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

                                    <small v-if="errors.number"
                                           class="form-control-feedback"
                                           v-text="errors.number[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
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
                            <div class="col-md-6">
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
                        </div>

                        <!-- <div class="row" v-if="type === 'customers'">
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
                        </div> -->

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
                            <div class="col-md-3">
                                <div :class="{'has-danger': errors.internal_code}"
                                     class="form-group">
                                    <label class="control-label">Código interno</label>
                                    <el-input v-model="form.internal_code"></el-input>
                                    <small v-if="errors.internal_code"
                                           class="form-control-feedback"
                                           v-text="errors.internal_code[0]"></small>
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
                            <div class="col-md-3">
                                <div :class="{'has-danger': errors.barcode}"
                                    class="form-group">
                                    <label class="control-label">Código de barra</label>
                                    <el-input v-model="form.barcode"></el-input>
                                    <small v-if="errors.barcode"
                                        class="form-control-feedback"
                                        v-text="errors.barcode[0]"></small>
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
                        <div class="row">
                            <div class="col-lg-4 col-md-4">

                                <div :class="{'has-danger': errors.seller_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Vendedor
                                    </label>
                                    <el-select v-model="form.seller_id"
                                               clearable>
                                        <el-option v-for="option in sellers"
                                                   :key="option.id"
                                                   :label="option.name"
                                                   :value="option.id">{{ option.name }}
                                        </el-option>
                                    </el-select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.zone_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        Zona
                                    </label>

                                    <a v-if="form_zone.add == false"
                                       class="control-label font-weight-bold text-info"
                                       href="#"
                                       @click="form_zone.add = true"> [ + Nuevo]</a>
                                    <a v-if="form_zone.add == true"
                                       class="control-label font-weight-bold text-info"
                                       href="#"
                                       @click="saveZone()"> [ + Guardar]</a>
                                    <a v-if="form_zone.add == true"
                                       class="control-label font-weight-bold text-danger"
                                       href="#"
                                       @click="form_zone.add = false"> [ Cancelar]</a>
                                    <el-input v-if="form_zone.add == true"
                                              v-model="form_zone.name"
                                              dusk="item_code"
                                              style="margin-bottom:1.5%;"></el-input>

                                    <el-select v-if="form_zone.add == false"
                                               v-model="form.zone_id"
                                               clearable
                                               filterable>
                                        <el-option v-for="option in zones"
                                                   :key="option.id"
                                                   :label="option.name"
                                                   :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.zone_id"
                                           class="form-control-feedback"
                                           v-text="errors.zone_id[0]"></small>
                                </div>
                            </div>


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
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

import {serviceNumber} from '../../../mixins/functions'

export default {
    mixins: [serviceNumber],
    props: [
        'showDialog',
        'type',
        'recordId',
        'external',
        'document_type_id',
        'input_person',
        'parentId',
    ],
    data() {
        return {
            form_zone: {add: false, name: null, id: null},
            parent: null,
            loading_submit: false,
            titleDialog: null,
            titleTabDialog: null,
            typeDialog: null,
            resource: 'persons',
            errors: {},
            api_service_token: false,
            form: {
                optional_email: []
            },
            temp_optional_email: [],
            temp_email: null,
            countries: [],
            zones: [],
            sellers: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            locations: [],
            person_types: [],
            identity_document_types: [],
            activeName: 'first'
        }
    },
    async created() {

        this.loadConfiguration()
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.api_service_token = response.data.api_service_token
                // console.log(this.api_service_token)

                this.countries = response.data.countries
                this.zones = response.data.zones
                this.sellers = response.data.sellers
                this.all_departments = response.data.departments;
                this.all_provinces = response.data.provinces;
                this.all_districts = response.data.districts;
                this.identity_document_types = response.data.identity_document_types;
                this.locations = response.data.locations;
                this.person_types = response.data.person_types;
            })
        .finally(()=>{
            if(this.api_service_token === false){
                if(this.config.api_service_token !== undefined){
                    this.api_service_token = this.config.api_service_token
                }
            }
        })

    },
    computed: {
        ...mapState([
            'config',
            'person',
            'parentPerson',
        ]),
        maxLength: function () {
            if (this.form.identity_document_type_id === '6') {
                return 11
            }
            if (this.form.identity_document_type_id === '1') {
                return 8
            }
        },
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                type: this.type,
                credit_days: 0,
                identity_document_type_id: '6',
                number: '',
                name: null,
                trade_name: null,
                country_id: 'PE',
                department_id: null,
                province_id: null,
                district_id: null,
                address: null,
                telephone: null,
                condition: null,
                state: null,
                email: null,
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
            this.updateEmail()

        },
        async opened() {

            if (this.external && this.input_person) {
                if (this.form.number.length === 8 || this.form.number.length === 11) {
                    if (this.api_service_token != false) {
                        await this.$eventHub.$emit('enableClickSearch')
                    } else {
                        this.searchCustomer()
                    }
                }
            }

        },
        create() {
            // console.log(this.input_person)
            this.parent = 0;
            if(this.parentId !== undefined){
                this.parent = this.parentId;
            }
            /*

            'person',
            'parentPerson',
            */
            if (this.external) {
                if (this.document_type_id === '01') {
                    this.form.identity_document_type_id = '6'
                }
                if (this.document_type_id === '03') {
                    this.form.identity_document_type_id = '1'
                }

                if (this.input_person) {
                    this.form.identity_document_type_id = (this.input_person.identity_document_type_id) ? this.input_person.identity_document_type_id : this.form.identity_document_type_id
                    this.form.number = (this.input_person.number) ? this.input_person.number : ''
                }
            }
            if (this.type === 'customers') {
                this.titleDialog = (this.recordId) ? 'Editar Cliente' : 'Nuevo Cliente'
                this.titleTabDialog = 'Datos de Cliente';
                this.typeDialog = 'Tipo de cliente'
            }
            if (this.type === 'suppliers') {
                this.titleDialog = (this.recordId) ? 'Editar Proveedor' : 'Nuevo Proveedor'
                this.titleTabDialog = 'Datos del proveedor';
                this.typeDialog = 'Tipo de proveedor'
            }

            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        if (response.data.data.contact == null) {
                            this.form.contact = {
                                full_name: null,
                                phone: null,
                            }
                        }
                        this.filterProvinces()
                        this.filterDistricts()
                    }).then(() => {
                    this.updateEmail()

                })
            }
        },
        clickAddAddress() {
            /* this.form.more_address.push({
                 location_id: [],
                 address: null,
             })*/

            this.form.addresses.push({
                'id': null,
                'country_id': 'PE',
                'location_id': [],
                'address': null,
                'email': null,
                'phone': null,
                'main': false,
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
            if (this.form.optional_email === undefined) this.form.optional_email = []
            this.form.optional_email = this.form.optional_email.filter(function (item) {
                return item.email !== email.email;
            });

            this.updateEmail()

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
                    let tem = _.find(this.form.optional_email, {'email': email});
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
            if (this.form.optional_email === undefined) this.form.optional_email = []
            if (this.checkEmail() === true) {
                let email = this.temp_email;
                this.form.optional_email.push(
                    {email: email,}
                )
                this.updateEmail()
                this.temp_email = null;
            }

        },
        validateDigits() {

            const pattern_number = new RegExp('^[0-9]+$', 'i');

            if (this.form.identity_document_type_id === '6') {

                if (this.form.number.length !== 11) {
                    return {
                        success: false,
                        message: `El campo número debe tener 11 dígitos.`
                    }
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    }
                }

            }


            if (this.form.identity_document_type_id === '1') {

                if (this.form.number.length !== 8) {
                    return {
                        success: false,
                        message: `El campo número debe tener 8 dígitos.`
                    }
                }

                if (!pattern_number.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número debe contener solo números`
                    }
                }
            }


            if (['4', '7', '0'].includes(this.form.identity_document_type_id)) {

                const pattern = new RegExp('^[A-Z0-9\-]+$', 'i');

                if (!pattern.test(this.form.number)) {
                    return {
                        success: false,
                        message: `El campo número no cumple con el formato establecido`
                    }
                }

            }


            return {
                success: true
            }
        },
        async submit() {

            let val_digits = await this.validateDigits()
            if (!val_digits.success) {
                return this.$message.error(val_digits.message)
            }

            this.loading_submit = true
            this.form.parent_id = parseInt(this.parent);
            await this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        if (this.external) {
                            this.$eventHub.$emit('reloadDataPersons', response.data.id)
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
                .finally(() => {
                    this.loading_submit = false
                })
        },
        changeIdentityDocType() {
            (this.recordId == null) ? this.setDataDefaultCustomer() : null
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
        close() {
            this.$eventHub.$emit('initInputPerson')
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        searchCustomer() {
            this.searchServiceNumberByType()
        },
        searchNumber(data) {
            //cambios apiperu
            this.form.name = data.name;
            this.form.trade_name = data.trade_name;
            this.form.location_id = data.ubigeo;
            this.form.address = data.address;
            this.form.department_id = data.department_id;
            this.form.province_id = data.province_id;
            this.form.district_id = data.district_id;
            this.form.condition = data.condition;
            this.form.state = data.state;
            this.filterProvinces()
            this.filterDistricts()
//                this.form.addresses[0].telephone = data.telefono;
        },
        clickRemoveAddress(index) {
            this.form.addresses.splice(index, 1);
        },

        saveZone() {
            this.form_zone.add = false

            this.$http.post(`/zones`, this.form_zone)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.zones.push(response.data.data)
                        this.form_zone.name = null

                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })


        },


    }
}
</script>
