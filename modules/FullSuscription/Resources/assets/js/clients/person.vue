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
                    <el-tab-pane
                        name="first">
                        <span slot="label">
                            {{ titleTabDialog }}
                        </span>
                        <div class="row">


                            <!--host-->

                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.host}"
                                     class="form-group">
                                    <label class="control-label">
                                        Host
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                    <el-input v-model="form.host"
                                              :disabled="loading_data"
                                              dusk="host"></el-input>
                                    <small v-if="errors.host"
                                           class="form-control-feedback"
                                           v-text="errors.host[0]"></small>
                                </div>
                            </div>
<!--                            ip-->
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.ip}"
                                     class="form-group">
                                    <label class="control-label">
                                        Ip
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                    <el-input v-model="form.ip"
                                              :disabled="loading_data"
                                              dusk="ip"></el-input>
                                    <small v-if="errors.ip"
                                           class="form-control-feedback"
                                           v-text="errors.ip[0]"></small>
                                </div>
                            </div>
<!--                            user-->
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.user}"
                                     class="form-group">
                                    <label class="control-label">
                                        Usuario
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                    <el-input v-model="form.user"
                                              :disabled="loading_data"
                                              dusk="user"></el-input>
                                    <small v-if="errors.user"
                                           class="form-control-feedback"
                                           v-text="errors.user[0]"></small>
                                </div>
                            </div>
<!--                            password-->
                            <div class="col-md-6">
                                <div :class="{'has-danger': errors.password}"
                                     class="form-group">
                                    <label class="control-label">
                                        Contraseña
                                        <!-- <span class="text-danger">*</span> -->
                                    </label>
                                    <el-input v-model="form.password"
                                              :disabled="loading_data"
                                              dusk="user"></el-input>
                                    <small v-if="errors.password"
                                           class="form-control-feedback"
                                           v-text="errors.password[0]"></small>
                                </div>
                            </div>

                        </div>

                    </el-tab-pane>
                </el-tabs>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button :disabled="loading_data"
                           :loading="loading_submit"
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
        'userId',
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
            form: {
                id: null,
                host:null,
                ip:null,
                user:null,
                password:null,
            },
            indexitem: null,
            activeName: 'first',


        }
    },
    async created() {

        this.loadConfiguration()
        await this.initForm()

    },
    computed: {
        ...mapState([
            'config',
            'resource',
            'person',
            'parentPerson',
        ]),
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
                host:null,
                ip:null,
                user:null,
                password:null,
            }
        },
        async opened() {

        },
        create() {
            this.loadConfiguration()

            this.titleTabDialog = 'Datos del servidor'
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
            this.activeName = 'first'
            /*

            if (this.type === 'customers') {
                this.titleDialog = (this.recordId) ? 'Editar hijo' : 'Nuevo hijo'
                this.titleTabDialog = 'Datos de hijo';
                this.typeDialog = 'Tipo de hijo'
            }
            */

            if (this.recordId) {
                let param = {
                    person: this.recordId,
                }
                this.$http.post(`/full_suscription/${this.resource}/record/server`, param)
                    .then(response => {
                        this.form = {
                            ...response.data.data,
                            ...this.person,
                        };
                        if (this.person && this.person.indexi !== null) {
                            this.form.indexi = this.person.indexi;
                        }
                    }).then(() => {

                })
                    .finally(() => {
                        this.loading_data = false;
                    })
            } else {
                this.loading_data = false;

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
            this.form.person_id = parseInt(this.parent);
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
        },
        close() {
            this.loading_submit = false;
            this.$eventHub.$emit('initInputPerson')
            this.$emit('update:showDialog', false)
            this.initForm()
        },
        clickRemoveAddress(index) {
            this.form.addresses.splice(index, 1);
        }
    }
}
</script>
