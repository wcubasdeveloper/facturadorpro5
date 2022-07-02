<template>
    <el-dialog
        :close-on-click-modal="false"
        :title="titleDialog"
        :visible="showDialog"
        @close="close"
        @open="create">
        <form
            autocomplete="off"
            @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div
                            :class="{'has-danger': errors.number}"
                            class="form-group">
                            <label class="control-label">RUC</label>
                            <!-- <el-input :disabled="form.is_update" v-model="form.number" :maxlength="11" dusk="number">
                                <el-button :disabled="form.is_update" type="primary" slot="append" :loading="loading_search" icon="el-icon-search" @click.prevent="searchSunat">
                                    SUNAT
                                </el-button>
                            </el-input> -->

                            <!-- apiperu -->
                            <x-input-service v-model="form.number"
                                             :identity_document_type_id="form.identity_document_type_id"
                                             @search="searchNumber"></x-input-service>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.name}"
                             class="form-group">
                            <label class="control-label">Nombre de la Empresa</label>
                            <el-input
                                v-model="form.name"
                                :disabled="form.is_update"
                                dusk="name">
                            </el-input>
                            <small
                                v-if="errors.name"
                                class="form-control-feedback"
                                v-text="errors.name[0]">
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div v-if="form.is_update"
                             :class="{'has-danger': (errors.subdomain || errors.uuid)}"
                             class="form-group">
                            <label class="control-label">
                                Nombre de Subdominio
                            </label>
                            <el-input
                                v-model="form.hostname"
                                :disabled="form.is_update"
                                dusk="name">
                            </el-input>
                        </div>
                        <div v-else
                             :class="{'has-danger': (errors.subdomain || errors.uuid)}"
                             class="form-group">
                            <label class="control-label">
                                Nombre de Subdominio
                            </label>
                            <el-input
                                v-model="form.subdomain"
                                dusk="subdomain">
                                <template slot="append">{{ url_base }}</template>
                            </el-input>
                            <small
                                v-if="errors.subdomain"
                                class="form-control-feedback"
                                v-text="errors.subdomain[0]">
                            </small>
                            <small
                                v-if="errors.uuid"
                                class="form-control-feedback"
                                v-text="errors.uuid[0]">
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.email}"
                             class="form-group">
                            <label class="control-label">
                                Correo de Acceso
                            </label>
                            <el-input
                                v-model="form.email"
                                :disabled="form.is_update"
                                dusk="email">
                            </el-input>
                            <small
                                v-if="errors.email"
                                class="form-control-feedback"
                                v-text="errors.email[0]">
                            </small>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div v-if="!form.is_update"
                         class="col-md-6">
                        <div :class="{'has-danger': (errors.password)}"
                             class="form-group">
                            <label class="control-label">
                                Contraseña
                            </label>
                            <el-input
                                v-model="form.password"
                                :disabled="form.is_update"
                                dusk="password"
                                type="password">
                            </el-input>
                            <small
                                v-if="errors.password"
                                class="form-control-feedback"
                                v-text="errors.password[0]">
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div :class="{'has-danger': errors.plan_id}"
                             class="form-group">
                            <label class="control-label">
                                Plan
                            </label>
                            <el-select
                                v-model="form.plan_id"
                                dusk="plan_id">
                                <el-option
                                    v-for="option in plans"
                                    :key="option.id"
                                    :label="option.name"
                                    :value="option.id">
                                </el-option>
                            </el-select>
                            <small
                                v-if="errors.plan_id"
                                class="form-control-feedback"
                                v-text="errors.plan_id[0]">
                            </small>
                        </div>
                    </div>

                    <div v-if="!form.is_update"
                         class="col-md-6">
                        <div :class="{'has-danger': errors.type}"
                             class="form-group">
                            <label class="control-label">
                                Perfil
                            </label>
                            <el-select
                                v-model="form.type"
                                :disabled="form.is_update">
                                <el-option
                                    v-for="option in types"
                                    :key="option.type"
                                    :label="option.description"
                                    :value="option.type">
                                </el-option>
                            </el-select>
                            <small
                                v-if="errors.type"
                                class="form-control-feedback"
                                v-text="errors.type[0]">
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6 center-el-checkbox mt-4">
                        <div :class="{'has-danger': errors.locked_emission}"
                             class="form-group">
                            <el-checkbox
                                v-model="form.locked_emission"
                                :disabled="form.is_update">
                                Limitar emisión de documentos
                            </el-checkbox>
                            <br>
                            <small
                                v-if="errors.locked_emission"
                                class="form-control-feedback"
                                v-text="errors.locked_emission[0]">
                            </small>
                        </div>
                    </div>
                </div>
                <el-collapse
                    v-model="collapse">
                    <el-collapse-item
                        name="1"
                        title="Módulos">
                        <div class="row">
                            <span class="ml-4">Giro de negocio <small>(opcional)</small></span>
                            <div class="col-12">
                                <el-radio-group v-model="business" @change="changeModules">
                                    <el-radio :label="1">Básico</el-radio>
                                    <el-radio :label="2">Farmacia</el-radio>
                                    <el-radio :label="3">Hotel</el-radio>
                                    <el-radio :label="4">Restaurante</el-radio>
                                </el-radio-group>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <span>
                                    Habilitar módulos
                                </span>
                                <div class="form-group tree-container-admin">
                                    <el-tree
                                        ref="tree"
                                        :check-strictly="true"
                                        :data="modules"
                                        :props="defaultProps"
                                        accordion
                                        highlight-current
                                        node-key="id"
                                        show-checkbox
                                        @check="FixChildren">
                                    </el-tree>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span>
                                    Habilitar apps
                                </span>
                                <div class="form-group tree-container-admin">
                                    <el-tree
                                        ref="Apptree"
                                        :check-strictly="true"
                                        :data="apps"
                                        :props="defaultAppsProps"
                                        accordion
                                        highlight-current
                                        node-key="id"
                                        show-checkbox
                                        @check="FixAppChildren">
                                    </el-tree>
                                </div>
                            </div>
                        </div>

                    </el-collapse-item>
                    <el-collapse-item
                        name="2"
                        title="Entorno del sistema">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.soap_send_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        SOAP Envio
                                    </label>
                                    <el-select
                                        v-model="form.soap_send_id">
                                        <el-option
                                            v-for="(option, index) in soap_sends"
                                            :key="index"
                                            :label="option.text"
                                            :value="option.value">
                                        </el-option>
                                    </el-select>
                                    <small
                                        v-if="errors.soap_send_id"
                                        class="form-control-feedback"
                                        v-text="errors.soap_send_id[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.soap_type_id}"
                                     class="form-group">
                                    <label class="control-label">
                                        SOAP Tipo
                                    </label>
                                    <el-select
                                        v-model="form.soap_type_id">
                                        <el-option
                                            v-for="option in soap_types"
                                            :key="option.id"
                                            :label="option.description"
                                            :value="option.id">
                                        </el-option>
                                    </el-select>

                                    <el-checkbox
                                        v-if="form.soap_send_id == '02' && form.soap_type_id == '01'"
                                        v-model="toggle"
                                        label="Ingresar Usuario">
                                    </el-checkbox>
                                    <small
                                        v-if="errors.soap_type_id"
                                        class="form-control-feedback"
                                        v-text="errors.soap_type_id[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <template v-if="form.soap_type_id == '02' || toggle == true ">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <h4 class="border-bottom">
                                        Usuario Secundario Sunat
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.soap_username}"
                                         class="form-group">
                                        <label class="control-label">
                                            SOAP Usuario
                                            <span class="text-danger">*</span>
                                        </label>
                                        <el-input
                                            v-model="form.soap_username">
                                        </el-input>
                                        <div class="sub-title text-muted">
                                            <small>
                                                RUC + Usuario. Ejemplo: 01234567890ELUSUARIO
                                            </small>
                                        </div>
                                        <small
                                            v-if="errors.soap_username"
                                            class="form-control-feedback"
                                            v-text="errors.soap_username[0]">
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.soap_password}"
                                         class="form-group">
                                        <label class="control-label">
                                            SOAP Password
                                            <span class="text-danger">*</span>
                                        </label>
                                        <el-input
                                            v-model="form.soap_password">
                                        </el-input>
                                        <small
                                            v-if="errors.soap_password"
                                            class="form-control-feedback"
                                            v-text="errors.soap_password[0]">
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div v-if="form.soap_send_id == '02'"
                             class="row">
                            <div class="col-md-12">
                                <div :class="{'has-danger': errors.soap_url}"
                                     class="form-group">
                                    <label class="control-label">
                                        SOAP Url
                                    </label>
                                    <el-input
                                        v-model="form.soap_url">
                                    </el-input>
                                    <small
                                        v-if="errors.soap_url"
                                        class="form-control-feedback"
                                        v-text="errors.soap_url[0]">
                                    </small>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div :class="{'has-danger': errors.password_certificate}"
                                     class="form-group">
                                    <label class="control-label">
                                        Contraseña certificado
                                    </label>
                                    <el-input
                                        v-model="form.password_certificate">
                                    </el-input>
                                    <small
                                        v-if="errors.password_certificate"
                                        class="form-control-feedback"
                                        v-text="errors.password_certificate[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div :class="{'has-danger': errors.certificate}"
                                     class="form-group">
                                    <label class="control-label">
                                        Certificado pfx
                                    </label>
                                    <el-upload
                                        ref="upload"
                                        :action="`/${resource}/upload`"
                                        :data="{'type': 'certificate'}"
                                        :headers="headers"
                                        :multiple="false"
                                        :on-error="errorUpload"
                                        :on-success="successUpload"
                                        :show-file-list="false">
                                        <el-button slot="trigger"
                                                   type="primary">
                                            Selecciona un archivo
                                        </el-button>
                                    </el-upload>
                                    <small
                                        v-if="errors.certificate"
                                        class="form-control-feedback"
                                        v-text="errors.certificate[0]">
                                    </small>
                                </div>
                            </div>
                            <div v-show="form.is_update == false && certificate_admin"
                                 class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">
                                        Archivo cargado (Administrador)
                                    </label>
                                    <el-input
                                        v-model="certificate_admin"
                                        :disabled="true">
                                    </el-input>

                                </div>
                            </div>
                            <div v-show="form.is_update == true"
                                 class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">
                                        Archivo cargado (Cliente) {{ form.certificate ? '(1)' : '(0)' }}
                                    </label>
                                    <el-input
                                        v-model="form.certificate"
                                        :disabled="true">
                                    </el-input>

                                </div>
                            </div>
                        </div>
                    </el-collapse-item>
                    <!-- Configuracion de correo -->

                    <el-collapse-item name="3"
                                      title="Configuracion de correo">
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.smtp_host}"
                                     class="form-group">
                                    <label class="control-label">
                                        Dirección del host de correo
                                    </label>
                                    <el-input
                                        v-model="form.smtp_host">
                                    </el-input>
                                    <small
                                        v-if="errors.smtp_host"
                                        class="form-control-feedback"
                                        v-text="errors.smtp_host[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.smtp_port}"
                                     class="form-group">
                                    <label class="control-label">
                                        Puerto del host de correo
                                    </label>
                                    <el-input
                                        v-model="form.smtp_port">
                                    </el-input>
                                    <small
                                        v-if="errors.smtp_port"
                                        class="form-control-feedback"
                                        v-text="errors.smtp_port[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.smtp_user}"
                                     class="form-group">
                                    <label class="control-label">
                                        Nombre de usuario de correo
                                    </label>
                                    <el-input
                                        v-model="form.smtp_user">
                                    </el-input>
                                    <small
                                        v-if="errors.smtp_user"
                                        class="form-control-feedback"
                                        v-text="errors.smtp_user[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.smtp_password}"
                                     class="form-group">
                                    <label class="control-label">
                                        Contraseña del usuario de correo
                                    </label>
                                    <el-input
                                        v-model="form.smtp_password"
                                        dusk="password"
                                        type="password">
                                    </el-input>
                                    <small
                                        v-if="errors.smtp_password"
                                        class="form-control-feedback"
                                        v-text="errors.smtp_password[0]">
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.smtp_encryption}"
                                     class="form-group">
                                    <label class="control-label">
                                        Encriptación de correo
                                    </label>
                                    <el-input
                                        v-model="form.smtp_encryption">
                                    </el-input>
                                    <small
                                        v-if="errors.smtp_encryption"
                                        class="form-control-feedback"
                                        v-text="errors.smtp_encryption[0]">
                                    </small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group p-t-20">
                                    <a
                                        :href="'https://docs.google.com/document/d/1ix2vPsiqSoK9jNAOF2gPjWhNa3BdajU5x8I5aBvEz0o/edit?usp=sharing'"
                                        class="control-label"
                                        target="_new"
                                    >
                                        Para correos Gmail verificar el manual
                                    </a>
                                </div>
                            </div>
                        </div>
                    </el-collapse-item>
                    <!-- Configuracion de correo -->

                </el-collapse>

                <div class="row">
                    <div class="col-md-6 center-el-checkbox mt-4">
                        <div class="form-group">
                            <el-checkbox
                                v-model="form.config_system_env">
                                ¿ Permitir a la empresa cambiar la configuración de producción ?
                            </el-checkbox>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    dusk="submit"
                    native-type="submit"
                    type="primary">
                    <template
                        v-if="loading_submit">
                        {{ button_text }}
                    </template>
                    <template
                        v-else>
                        Guardar
                    </template>
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import {serviceNumber} from '../../../mixins/functions'

export default {
    mixins: [serviceNumber],
    props: ['showDialog', 'recordId'],
    data() {
        return {
            defaultProps: {
                children: 'childrens',
                label: 'description'
            },
            defaultAppsProps: {
                children: 'childrens',
                label: 'description'
            },
            headers: headers_token,
            loading_submit: false,
            loading_search: false,
            titleDialog: null,
            button_text: null,
            resource: 'clients',
            error: {},
            errors: {},
            form: {},
            url_base: null,
            plans: [],
            modules: [],
            apps: [],
            types: [],
            soap_sends: [{value: '01', text: 'Sunat'}, {value: '02', text: 'Ose'}],
            soap_types: [{id: "01", description: "Demo"}, {id: "02", description: "Producción"}],
            toggle: false,
            certificate_admin: '',
            soap_username: null,
            soap_password: null,
            collapse: 1,
            business: null,
        }
    },
    updated() {
        // Set default values ​​for multiple selection trees
        if (this.modules !== undefined && this.$refs.tree !== undefined) {
            // this.$refs.tree.setCheckedKeys(this.modules)
        }
    },
    async created() {
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.url_base = response.data.url_base
                this.plans = response.data.plans
                this.modules = response.data.modules
                this.apps = response.data.apps
                this.types = response.data.types
                this.certificate_admin = response.data.certificate_admin
                this.soap_username = response.data.soap_username
                this.soap_password = response.data.soap_password
                this.group_basic = response.data.group_basic
                this.group_hotel = response.data.group_hotel
                this.group_pharmacy = response.data.group_pharmacy
                this.group_restaurant = response.data.group_restaurant
                this.group_hotel_apps = response.data.group_hotel_apps
                this.group_pharmacy_apps = response.data.group_pharmacy_apps
                this.group_restaurant_apps = response.data.group_restaurant_apps
            })

        await this.initForm()

        this.form.soap_username = this.soap_username
        this.form.soap_password = this.soap_password
    },
    methods: {
        FixChildren(currentObj, treeStatus) {
            let element = this.$refs.tree
            if (currentObj !== undefined) {
                let selected = treeStatus.checkedKeys.indexOf(currentObj.id) // -1 is unchecked
                if (selected !== -1) {
                    this.SelectParent(currentObj, element)
                    this.FixSameValueToChild(currentObj, true, element)
                } else {
                    if (currentObj.childrens !== undefined && currentObj.childrens.length !== 0) {
                        this.FixSameValueToChild(currentObj, false, element)
                    }
                }
            }
        },
        FixAppChildren(currentObj, treeStatus) {
            let element = this.$refs.Apptree
            if (currentObj !== undefined) {
                let selected = treeStatus.checkedKeys.indexOf(currentObj.id) // -1 is unchecked
                if (selected !== -1) {
                    this.SelectParent(currentObj, element)
                    this.FixSameValueToChild(currentObj, true, element)
                } else {
                    if (currentObj.childrens !== undefined && currentObj.childrens.length !== 0) {
                        this.FixSameValueToChild(currentObj, false, element)
                    }
                }
            }
        },
        //funcion fusion fixchildren
        FixSameValueToChild(treeList, isSelected, element) {
            // console.dir(element);
            if (treeList !== undefined && element !== undefined) {
                element.setChecked(treeList.id, isSelected)
                if (treeList.childrens !== undefined) {
                    for (let i = 0; i < treeList.childrens.length; i++) {
                        this.FixSameValueToChild(treeList.childrens[i], isSelected, element)
                    }
                }
            }
        },
        SelectParent(currentObj, element) {
            // console.error(element);
            if (currentObj !== undefined) {
                let currentNode = element.getNode(currentObj)
                if (currentNode.parent.key !== undefined) {
                    element.setChecked(currentNode.parent, true)
                    this.SelectParent(currentNode.parent, element)
                }
            }
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                name: null,
                email: null,
                identity_document_type_id: '6',
                number: '',
                password: null,
                plan_id: null,
                locked_emission: false,
                type: null,
                is_update: false,
                modules: [],
                apps: [],
                levels: [],
                config_system_env: true,
                soap_send_id: '01',
                soap_type_id: '01',
                soap_username: null,
                soap_password: null,
                soap_url: null,
                password_certificate: null,
                certificate: null,
                temp_path: null,
                /** Mail */
                smtp_host: 'smtp.gmail.com',
                smtp_port: 465,
                smtp_user: 'username',
                smtp_password: null,
                smtp_encryption: 'ssl',
            }
        },
        create() {
            if (this.recordId) {
                this.titleDialog = 'Editar Cliente';
            } else {
                this.titleDialog = 'Nuevo Cliente';
                const preSelecteds = [];
                this.modules.map(m => {
                    preSelecteds.push(m.id);
                    m.childrens.map(c => {
                        preSelecteds.push(c.id);
                    });
                });

                const preAppSelecteds = [];
                this.apps.map(m => {
                    preAppSelecteds.push(m.id);
                    m.childrens.map(c => {
                        preAppSelecteds.push(c.id);
                    });
                });
                setTimeout(() => {
                    this.$refs.tree.setCheckedKeys(preSelecteds);
                    this.$refs.Apptree.setCheckedKeys(preAppSelecteds);
                }, 1000);
            }

            if (this.recordId) {
                this.$http.get(`/${this.resource}/record/${this.recordId}`)
                    .then(response => {
                        this.$refs.tree.setCheckedKeys([]);
                        this.$refs.Apptree.setCheckedKeys([]);
                        this.form = response.data.data;
                        this.form.is_update = true;
                        const preSelecteds = [];
                        const preSelectedsModules = this.form.modules;
                        const preSelectedsLevels = this.form.levels;

                        const preAppSelecteds = [];
                        const preSelectedsApps = this.form.apps;
                        this.modules.map(m => {
                            if (preSelectedsModules.includes(m.id)) {
                                preSelecteds.push(m.id);
                            }
                            m.childrens.map(c => {
                                const idArray = c.id.split('-');
                                if (preSelectedsLevels.includes(parseInt(idArray[1]))) {
                                    preSelecteds.push(c.id);
                                }
                            })
                        });

                        this.apps.map(m => {
                            if (preSelectedsApps.includes(m.id)) {
                                preAppSelecteds.push(m.id);
                            }
                            m.childrens.map(c => {
                                const idArray = c.id.split('-');
                                if (preSelectedsLevels.includes(parseInt(idArray[1]))) {
                                    preAppSelecteds.push(c.id);
                                }
                            })
                        });


                        setTimeout(() => {
                            this.$refs.tree.setCheckedKeys(preSelecteds);
                            this.$refs.Apptree.setCheckedKeys(preAppSelecteds);
                        }, 1000);
                    })
            }
        },
        async submit() {
            const modulesAndLevelsSelecteds = this.$refs.tree.getCheckedNodes();
            const appsAndLevelsSelecteds = this.$refs.Apptree.getCheckedNodes();
            const modules = [];
            modulesAndLevelsSelecteds.map(m => {
                if (m.is_parent) {
                    modules.push(m.id);
                }
            });
            appsAndLevelsSelecteds.map(m => {
                if (m.is_parent) {
                    modules.push(m.id);
                }
            });
            const levels = [];
            modulesAndLevelsSelecteds.filter(l => {
                if (!l.is_parent) {
                    const idArray = l.id.split('-');
                    levels.push(idArray[1]);
                }
            })
            appsAndLevelsSelecteds.filter(l => {
                if (!l.is_parent) {
                    const idArray = l.id.split('-');
                    levels.push(idArray[1]);
                }
            })
            this.form.modules = modules;
            this.form.levels = levels;

            if (modules.length < 1) {
                return this.$message.error('Debe seleccionar al menos un módulo')
            }

            if (!this.form.is_update) {
                if (this.form.certificate && !this.form.password_certificate) {
                    return this.$message.error('Si carga un certificado, es necesario ingresar el password del certificado')
                }
            } else {
                if (this.form.temp_path && !this.form.password_certificate) {
                    return this.$message.error('Si carga un certificado, es necesario ingresar el password del certificado')
                }
            }

            this.button_text = (this.form.is_update) ? 'Actualizando cliente...' : 'Creando base de datos...'
            this.loading_submit = true
            await this.$http.post(`${this.resource}${(this.form.is_update ? '/update' : '')}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else if (error.response.status === 500) {
                        this.$message.error(error.response.data.message);
                    } else {
                        console.log(error.response)
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
        searchSunat() {
            this.searchServiceNumber()
        },
        errorUpload(r) {
            console.log(r)
        },
        successUpload(response) {
            if (response.success) {
                this.form.certificate = response.data.filename
                this.form.temp_path = response.data.temp_path
            } else {
                this.$message.error(response.message)
            }
        },
        searchNumber(data) {
            this.form.name = data.name;
        },
        changeModules() {
            var group = {
                modules: [],
                apps: [],
            };
            if(this.business == 1){
                group.modules = this.getIds(this.group_basic);
            }
            if(this.business == 2){
                group.modules = this.getIds(this.group_pharmacy);
                group.apps = this.getIds(this.group_pharmacy_apps);
            }
            if(this.business == 3){
                group.modules = this.getIds(this.group_hotel);
                group.apps = this.getIds(this.group_hotel_apps);
            }
            if(this.business == 4){
                group.modules = this.getIds(this.group_restaurant);
                group.apps = this.getIds(this.group_restaurant_apps);
            }
            this.$refs.tree.setCheckedKeys(group.modules);
            this.$refs.Apptree.setCheckedKeys(group.apps);
        },
        getIds(modules) {
            const preSelecteds = [];
            modules.map(m => {
                preSelecteds.push(m.id);
                m.childrens.map(c => {
                    preSelecteds.push(c.id);
                });
            });
            return preSelecteds
        }
    }
}
</script>
