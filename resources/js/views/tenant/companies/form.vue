<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Empresa</span></li>
            </ol>
        </div>
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="my-0">Datos de la Empresa</h3>
            </div>
            <div class="card-body">
                <form autocomplete="off"
                      @submit.prevent="submit">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.number}"
                                     class="form-group">
                                    <label class="control-label">Número</label>
                                    <el-input v-model="form.number"
                                              :disabled="true"
                                              :maxlength="11"></el-input>
                                    <small v-if="errors.number"
                                           class="form-control-feedback"
                                           v-text="errors.number[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.name}"
                                     class="form-group">
                                    <label class="control-label">Nombre <span class="text-danger">*</span></label>
                                    <el-input v-model="form.name"></el-input>
                                    <small v-if="errors.name"
                                           class="form-control-feedback"
                                           v-text="errors.name[0]"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.trade_name}"
                                     class="form-group">
                                    <label class="control-label">Nombre comercial
                                        <span class="text-danger">*</span></label>
                                    <el-input v-model="form.trade_name"></el-input>
                                    <small v-if="errors.trade_name"
                                           class="form-control-feedback"
                                           v-text="errors.trade_name[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Logo</label>
                                    <el-input v-model="form.logo"
                                              :readonly="true">
                                        <el-upload slot="append"
                                                   :data="{'type': 'logo'}"
                                                   :headers="headers"
                                                   :on-success="successUpload"
                                                   :show-file-list="false"
                                                   action="/companies/uploads">
                                            <el-button icon="el-icon-upload"
                                                       type="primary"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Logo Tienda Virtual</label>
                                    <el-input v-model="form.logo_store" :readonly="true">
                                        <el-upload slot="append"
                                                   :headers="headers"
                                                   :data="{'type': 'logo_store'}"
                                                   action="/companies/uploads"
                                                   :show-file-list="false"
                                                   :on-success="successUpload">
                                            <el-button type="primary" icon="el-icon-upload"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small></div>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.detraction_account}"
                                     class="form-group">
                                    <label class="control-label">N° Cuenta de detracción</label>
                                    <el-input v-model="form.detraction_account"></el-input>
                                    <small v-if="errors.detraction_account"
                                           class="form-control-feedback"
                                           v-text="errors.detraction_account[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Rúbrica (Firma digital)</label>
                                    <el-input v-model="form.img_firm"
                                              :readonly="true">
                                        <el-upload slot="append"
                                                   :data="{'type': 'img_firm'}"
                                                   :headers="headers"
                                                   :on-success="successUpload"
                                                   :show-file-list="false"
                                                   action="/companies/uploads">
                                            <el-button icon="el-icon-upload"
                                                       type="primary"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda resoluciones 700x300</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Favicon</label>
                                    <el-input v-model="form.favicon"
                                              :readonly="true">
                                        <el-upload slot="append"
                                                   :data="{'type': 'favicon'}"
                                                   :headers="headers"
                                                   :on-success="successUpload"
                                                   :show-file-list="false"
                                                   action="/companies/uploads">
                                            <el-button icon="el-icon-upload"
                                                       type="primary"></el-button>
                                        </el-upload>
                                    </el-input>
                                    <div class="sub-title text-danger"><small>Se recomienda una imagen con fondo
                                                                              transparente y cuadrada en formato
                                                                              PNG</small></div>
                                </div>
                            </div>
                            <div v-if="form.soap_type_id == '02'"
                                 class="col-md-6">
                                <div :class="{'has-danger': errors.certificate_due}"
                                     class="form-group">
                                    <label class="control-label">Vencimiento de Certificado</label>
                                    <el-date-picker v-model="form.certificate_due"
                                                    :clearable="true"
                                                    type="date"
                                                    value-format="yyyy-MM-dd"></el-date-picker>
                                    <small v-if="errors.certificate_due"
                                           class="form-control-feedback"
                                           v-text="errors.certificate_due[0]"></small>
                                </div>
                            </div>
                            <div v-show="false"
                                 class="col-md-6 mt-4">
                                <div :class="{'has-danger': errors.operation_amazonia}"
                                     class="form-group">
                                    <el-checkbox v-model="form.operation_amazonia">¿Emite en la Amazonía?</el-checkbox>
                                </div>
                            </div>
                        </div>
                        <!-- Datos de farmacia -->
                        <div v-show="form.is_pharmacy"
                             class="row">
                            <div class="col-md-12 mt-2">
                                <h4 class="border-bottom">Datos de farmacia</h4>
                            </div>
                        </div>
                        <div v-show="form.is_pharmacy"
                             class="row">
                            <div class="col-md-12">
                                <div :class="{'has-danger': errors.cod_digemid}"
                                     class="form-group">
                                    <label class="control-label">Código de observación DIGEMID</label>
                                    <!-- :disabled="!form.config_system_env" -->
                                    <el-input v-model="form.cod_digemid"></el-input>
                                    <!-- <div class="sub-title text-muted"><small>RUC + Usuario. Ejemplo: 01234567890ELUSUARIO</small></div>-->
                                    <small v-if="errors.cod_digemid"
                                           class="form-control-feedback"
                                           v-text="errors.cod_digemid[0]"></small>
                                </div>
                            </div>
                        </div>
                        <!-- Entorno del sistema -->
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <h4 class="border-bottom">Entorno del sistema</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.soap_type_id}"
                                     class="form-group">
                                    <label class="control-label">SOAP Tipo</label>
                                    <el-select v-model="form.soap_type_id"
                                               :disabled="!form.config_system_env">
                                        <el-option v-for="option in soap_types"
                                                   :key="option.id"
                                                   :label="option.description"
                                                   :value="option.id"></el-option>
                                    </el-select>

                                    <!-- <el-checkbox
                                           v-if="form.soap_send_id == '02' && form.soap_type_id == '01'"
                                           v-model="toggle"
                                           label="Ingresar Usuario">
                                    </el-checkbox> -->
                                    <small v-if="errors.soap_type_id"
                                           class="form-control-feedback"
                                           v-text="errors.soap_type_id[0]"></small>
                                </div>
                            </div>
                            <div v-if="form.soap_type_id != '03'"
                                 class="col-md-6">
                                <div :class="{'has-danger': errors.soap_send_id}"
                                     class="form-group">
                                    <label class="control-label">SOAP Envio</label>
                                    <el-select v-model="form.soap_send_id"
                                               :disabled="!form.config_system_env">
                                        <el-option v-for="(option, index) in soap_sends"
                                                   :key="index"
                                                   :label="option"
                                                   :value="index"></el-option>
                                    </el-select>
                                    <small v-if="errors.soap_send_id"
                                           class="form-control-feedback"
                                           v-text="errors.soap_send_id[0]"></small>
                                </div>
                            </div>
                        </div>
                        <template v-if="form.soap_type_id == '02' || form.soap_send_id == '02'">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <h4 class="border-bottom">Usuario Secundario Sunat/OSE</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.soap_username}"
                                         class="form-group">
                                        <label class="control-label">SOAP Usuario <span
                                            class="text-danger">*</span></label>
                                        <el-input v-model="form.soap_username"
                                                  :disabled="!form.config_system_env"></el-input>
                                        <div class="sub-title text-muted"><small>RUC + Usuario. Ejemplo:
                                                                                 01234567890ELUSUARIO</small></div>
                                        <small v-if="errors.soap_username"
                                               class="form-control-feedback"
                                               v-text="errors.soap_username[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.soap_password}"
                                         class="form-group">
                                        <label class="control-label">SOAP Password
                                            <span class="text-danger">*</span></label>
                                        <el-input v-model="form.soap_password"
                                                  :disabled="!form.config_system_env"></el-input>
                                        <small v-if="errors.soap_password"
                                               class="form-control-feedback"
                                               v-text="errors.soap_password[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div v-if="form.soap_send_id == '02'"
                             class="row">
                            <div class="col-md-12">
                                <div :class="{'has-danger': errors.soap_url}"
                                     class="form-group">
                                    <label class="control-label">SOAP Url</label>
                                    <el-input v-model="form.soap_url"></el-input>
                                    <small v-if="errors.soap_url"
                                           class="form-control-feedback"
                                           v-text="errors.soap_url[0]"></small>
                                </div>
                            </div>
                        </div>

                        <template v-if="form.soap_type_id == '02'">
                            <div class="row">
                                <div class="col-md-12 mt-2">
                                    <h4 class="border-bottom">Consulta integrada de CPE - Validador de documentos
                                        <el-tooltip class="item"
                                                    content="Obtener los datos desde el portal de Sunat"
                                                    effect="dark"
                                                    placement="top-start">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.integrated_query_client_id}"
                                         class="form-group">
                                        <label class="control-label">Client ID</label>
                                        <el-input v-model="form.integrated_query_client_id"></el-input>
                                        <small v-if="errors.integrated_query_client_id"
                                               class="form-control-feedback"
                                               v-text="errors.integrated_query_client_id[0]"></small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div :class="{'has-danger': errors.integrated_query_client_secret}"
                                         class="form-group">
                                        <label class="control-label">Client Secret (Clave)</label>
                                        <el-input v-model="form.integrated_query_client_secret"></el-input>
                                        <small v-if="errors.integrated_query_client_secret"
                                               class="form-control-feedback"
                                               v-text="errors.integrated_query_client_secret[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </template>

                    </div>
                    <div class="form-actions text-right pt-2">
                        <el-button :loading="loading_submit"
                                   native-type="submit"
                                   type="primary">Guardar
                        </el-button>
                    </div>
                </form>

            </div>
        </div>
        <TokenRucDni></TokenRucDni>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import TokenRucDni from './token_ruc_dni.vue'


export default {
    components: {
        TokenRucDni
    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            loading_submit: false,
            headers: headers_token,
            resource: 'companies',
            errors: {},
            form: {},
            soap_sends: [],
            soap_types: [],
            toggle: false, //Creando el objeto a retornar con v-model
        }
    },
    async created() {
        await this.initForm()
        await this.$http.get(`/${this.resource}/tables`)
            .then(response => {
                this.soap_sends = response.data.soap_sends
                this.soap_types = response.data.soap_types
                console.log(1)
            })
        await this.$http.get(`/${this.resource}/record`)
            .then(response => {
                if (response.data !== '') {
                    this.form = response.data.data
                }
                console.log(2)

            })
        
        this.events()
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        events(){

            this.$eventHub.$on('reloadDataCompany', () => {
                this.getRecord()
            })

        },
        async getRecord(){
            
            await this.$http.get(`/${this.resource}/record`)
                    .then(response => {
                        if (response.data !== '') {
                            this.form = response.data.data
                        }
                    })
        },
        initForm() {
            this.errors = {}
            this.form = {
                id: null,
                identity_document_type_id: '06000006',
                number: null,
                name: null,
                trade_name: null,
                soap_send_id: '01',
                soap_type_id: '01',
                soap_username: null,
                soap_password: null,
                soap_url: null,
                certificate: null,
                certificate_due: null,
                logo: null,
                logo_store: null,
                detraction_account: null,
                operation_amazonia: false,
                toggle: false,
                config_system_env: false,
                img_firm: null,
                is_pharmacy: false,
                cod_digemid: null,
                integrated_query_client_id: null,
                integrated_query_client_secret: null,

            }
        },
        submit() {
            this.loading_submit = true
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
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
        successUpload(response, file, fileList) {
            if (response.success) {
                this.$message.success(response.message)
                this.form[response.type] = response.name
            } else {
                this.$message({message: 'Error al subir el archivo', type: 'error'})
            }
        },
    }
}
</script>
