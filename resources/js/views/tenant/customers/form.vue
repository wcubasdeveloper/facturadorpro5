<template>
    <el-dialog :append-to-body="true"
               :title="titleDialog"
               :visible="showDialog"
               @close="close"
               @open="create">
        <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.identity_document_type_id}"
                             class="form-group">
                            <label class="control-label">Tipo Doc. Identidad</label>
                            <el-select v-model="form.identity_document_type_id"
                                       filterable>
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
                            <label class="control-label">Número</label>
                            <el-input v-model="form.number"
                                      :maxlength="maxLength">
                                <template v-if="form.identity_document_type_id === '6' || form.identity_document_type_id === '1'">
                                    <el-button slot="append"
                                               :loading="loading_search"
                                               icon="el-icon-search"
                                               type="primary"
                                               @click.prevent="searchCustomer"></el-button>
                                </template>
                            </el-input>
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
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name"></el-input>
                            <small v-if="errors.name"
                                   class="form-control-feedback"
                                   v-text="errors.name[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.trade_name}"
                             class="form-group">
                            <label class="control-label">Nombre comercial</label>
                            <el-input v-model="form.trade_name"></el-input>
                            <small v-if="errors.trade_name"
                                   class="form-control-feedback"
                                   v-text="errors.trade_name[0]"></small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.country_id}"
                             class="form-group">
                            <label class="control-label">País</label>
                            <el-select v-model="form.country_id"
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
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.department_id}"
                             class="form-group">
                            <label class="control-label">Departamento</label>
                            <el-select v-model="form.department_id"
                                       filterable
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
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.province_id}"
                             class="form-group">
                            <label class="control-label">Provincia</label>
                            <el-select v-model="form.province_id"
                                       filterable
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
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div :class="{'has-danger': errors.province_id}"
                             class="form-group">
                            <label class="control-label">Distrito</label>
                            <el-select v-model="form.district_id"
                                       filterable>
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
                    <div class="col-md-8">
                        <div :class="{'has-danger': errors.address}"
                             class="form-group">
                            <label class="control-label">Dirección</label>
                            <el-input v-model="form.address"></el-input>
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
                            <el-input v-model="form.telephone"></el-input>
                            <small v-if="errors.telephone"
                                   class="form-control-feedback"
                                   v-text="errors.telephone[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.email}"
                             class="form-group">
                            <label class="control-label">Correo electrónico</label>
                            <el-input v-model="form.email"></el-input>
                            <small v-if="errors.email"
                                   class="form-control-feedback"
                                   v-text="errors.email[0]"></small>
                        </div>
                    </div>
                </div>

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

import {serviceNumber} from '../../../mixins/functions'

export default {
    mixins: [serviceNumber],
    props: [
        'showDialog',
        'recordId',
        'external',
        'parentId',
    ],
    data() {
        return {
            loading_submit: false,
            titleDialog: null,
            parent: null,
            resource: 'customers',
            errors: {},
            form: {},
            countries: [],
            all_departments: [],
            all_provinces: [],
            all_districts: [],
            provinces: [],
            districts: [],
            identity_document_types: []
        }
    },
    created() {
        this.initForm()
        this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.countries = response.data.countries
                this.all_departments = response.data.departments
                this.all_provinces = response.data.provinces
                this.all_districts = response.data.districts
                this.identity_document_types = response.data.identity_document_types
            })
    },
    computed: {
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
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '6',
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
                more_address: [],
                parent_id: this.parent
            }
        },
        create() {
            this.parent = 0;
            if(this.parentId !== undefined){
                this.parent = this.parentId;

            }
            this.titleDialog = (this.recordId) ? 'Editar Cliente' : 'Nuevo Cliente'
            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.form = response.data.data
                        this.filterProvinces()
                        this.filterDistricts()
                    })
            }
        },

        submit() {
            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
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
        }
    }
}
</script>
