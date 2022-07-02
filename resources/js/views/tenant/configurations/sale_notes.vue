<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="#"><i class="fas fa-cogs"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Configuración</span></li>
                <li><span class="text-muted">Avanzado</span></li>
            </ol>
        </div>
        <div class="card card-dashboard border">
            <div class="card-body">
                <template>
                    <div  v-if="canEdit">
                        <el-tabs v-model="activeName">
                            <el-tab-pane class="mb-3" name="first">
                                <span slot="label"><h3>Servidor De Destino</h3></span>
                                <div class="row">
                                    <div class="col-md-4 mt-4">
                                        <label class="control-label">Url del servidor</label>
                                        <div class="form-group" :class="{'has-danger': errors.url}">
                                            <el-input v-model="form.url"></el-input>
                                            <small class="form-control-feedback"
                                                   v-if="errors.url"
                                                   v-text="errors.url[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label class="control-label">Api del servidor</label>
                                        <div class="form-group" :class="{'has-danger': errors.apiKey}">
                                            <el-input v-model="form.apiKey"></el-input>
                                            <small class="form-control-feedback"
                                                   v-if="errors.apiKey"
                                                   v-text="errors.apiKey[0]"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-4">
                                        <label class="control-label">
                                            Envio desde Nota de venta
                                        </label>
                                        <div class="form-group" :class="{'has-danger': errors.send_data_to_other_server}">
                                            <el-switch v-model="form.send_data_to_other_server"
                                                       active-text="Si"
                                                       inactive-text="No"
                                            >

                                            </el-switch>
                                            <small class="form-control-feedback"
                                                   v-if="errors.send_data_to_other_server"
                                                   v-text="errors.send_data_to_other_server[0]"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <el-button
                                                   @click ="submit"
                                                   class="pull-right">Guardar</el-button>

                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions, mapState} from "vuex";

export default {
    props: [
        'url',
        'apiKey',
        'configuration',
        'typeUser',
    ],
    data() {
        return {
            loading_submit: false,
            resource: 'configurations/sale-notes',
            errors: {},
            form: {
                url:'',
                apiKey:'',
                send_data_to_other_server:false,
            },
            activeName: 'first',
        }
    },

    computed: {
        ...mapState([
            'config',
        ]),
    },
    created() {
        this.$store.commit('setConfiguration', this.configuration);
        this.loadConfiguration()
        this.form.url = this.url;
        this.form.apiKey = this.apiKey;
        this.form.send_data_to_other_server = this.config.send_data_to_other_server;
    },

    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),

        canEdit: function () {
            return this.typeUser === 'admin';

        },
        submit() {
            console.log('hola')
            this.loading_submit = true;

            this.$http.post(`/${this.resource}`, this.form).then(response => {
                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.form = response.data;
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }).then(() => {
                this.loading_submit = false;
            });
        },
    }
}
</script>

