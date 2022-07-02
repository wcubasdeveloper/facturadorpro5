<template>
    <el-dialog :append-to-body="true"
               :close-on-click-modal="false"
               :title="titleDialog"
               :visible="showDialog"
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
                                               :disabled="loading_data"
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
                                        <span class="text-danger">*</span>
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
                                                           :disabled="loading_data"
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
                                              :disabled="loading_data"
                                              dusk="name"></el-input>
                                    <small v-if="errors.name"
                                           class="form-control-feedback"
                                           v-text="errors.name[0]"></small>
                                </div>
                            </div>
                            <!--

                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.trade_name}"
                                     class="form-group">
                                    <label class="control-label">
                                        Grado
                                    </label>
                                    <el-input v-model="form.trade_name"
                                              dusk="trade_name"></el-input>
                                    <small v-if="errors.trade_name"
                                           class="form-control-feedback"
                                           v-text="errors.trade_name[0]"></small>
                                </div>
                            </div>
                            -->

                            <div class="col-md-3">
                                <div :class="{'has-danger': errors.internal_code}"
                                     class="form-group">
                                    <label class="control-label">Código interno</label>
                                    <el-input
                                        :disabled="loading_data"
                                        v-model="form.internal_code"></el-input>
                                    <small v-if="errors.internal_code"
                                           class="form-control-feedback"
                                           v-text="errors.internal_code[0]"></small>
                                </div>
                            </div>
                        </div>

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
                    <!--
                    <el-tab-pane class
                                 name="second">
                        <span slot="label">Dirección</span>
                        <div class="row">
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
                        </div>
                    </el-tab-pane>
                    -->
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :loading="loading_submit"
                           :disabled="loading_data"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

import {serviceNumber} from '../../../../../../resources/js/mixins/functions'

export default {
    mixins: [
        serviceNumber
    ],
    props: [
        'showDialog',
        'type',
        'recordId',
        'external',
        'input_person',
        'parentId',


        /*

        */
    ],
    data() {
        return {
            document_type_id: "1",
            parent: null,
            loading_submit: false,
            loading_data: false,
            titleDialog: null,
            titleTabDialog: null,
            typeDialog: null,
            // resource: 'persons',
            errors: {},
            api_service_token: false,
            form: {
                optional_email: []
            },
            temp_optional_email: [],
            temp_email: null,
            indexitem: null,
            provinces: [],
            districts: [],
            activeName: 'first',


            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            person_types: [],
            identity_document_types: [],
            locations: [],
        }
    },
    async created() {

        this.loadConfiguration()
        await this.initForm()
        await this.$http.post(`/suscription/${this.resource}/tables`)
            .then(response => {
                this.api_service_token = response.data.api_service_token
                // console.log(this.api_service_token)
                /*
                this.$store.commit('setCountries', response.data.countries);
                this.$store.commit('setAllDepartments', response.data.departments);
                this.$store.commit('setAllProvinces', response.data.provinces);
                this.$store.commit('setAllDistricts', response.data.districts);
                this.$store.commit('setIdentityDocumentTypes', response.data.identity_document_types);
                this.$store.commit('setLocations', response.data.locations);
                this.$store.commit('setPersonTypes', response.data.person_types);
                */

                this.countries = response.data.countries
                this.all_departments = response.data.departments
                this.all_provinces = response.data.provinces
                this.all_districts = response.data.districts
                this.person_types = response.data.person_types
                this.identity_document_types = response.data.identity_document_types
                this.locations = response.data.locations

            }) .finally(()=>{
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
            'resource',
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
            this.indexitem = null
            this.errors = {}
            this.form = {
                id: null,
                type: this.type,
                credit_days: 0,
                identity_document_type_id: '1',
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
this.loading_data = true;
            this.indexitem = null
            if (this.person) {
                let index = this.person.indexi;
                if (isNaN(index) !== true) {
                    this.indexitem = index;
                }
            }
            if (this.indexitem == null) {
                delete (this.indexitem)
            }

            this.changeIdentityDocType();
            this.activeName = 'first'
            this.parent = 0;
            if (this.parentId !== undefined) {
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
                this.titleDialog = (this.recordId) ? 'Editar hijo' : 'Nuevo hijo'
                this.titleTabDialog = 'Datos de hijo';
                this.typeDialog = 'Tipo de hijo'
            }
            if (this.type === 'suppliers') {
                this.titleDialog = (this.recordId) ? 'Editar Proveedor' : 'Nuevo Proveedor'
                this.titleTabDialog = 'Datos del proveedor';
                this.typeDialog = 'Tipo de proveedor'
            }

            if (this.recordId) {
                let param = {
                    person: this.recordId,
                }
                this.$http.post(`/suscription/${this.resource}/record`, param)
                    .then(response => {
                        this.form = {
                            ...response.data.data,
                            ...this.person,

                        };
                        if (this.person && this.person.indexi !== null) {
                            this.form.indexi = this.person.indexi;
                        }
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
                .finally(()=>{
                    this.loading_data = false;
                })
            }else{
                this.loading_data = false;

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
            // emitir, no guardar
            if (this.person && this.person.indexi) {
                this.form.indexi = this.person.indexi;
            }

            if (this.indexitem !== null) {
                this.form.indexi = this.indexitem
                delete (this.indexitem)
            }
            this.$store.commit('setPerson', this.form)
            this.$emit('add', this.form);
            this.close()
            return null;
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
            this.loading_submit = false;
            this.$eventHub.$emit('initInputPerson')
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        searchCustomer() {
            this.searchServiceNumberByType()
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
        clickRemoveAddress(index) {
            this.form.addresses.splice(index, 1);
        }
    }
}
</script>
