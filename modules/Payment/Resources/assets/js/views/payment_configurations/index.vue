<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">Configuración de pagos
            </h3>
            
            <div class="card-actions white-text">
                <a href="#" class="card-action card-action-toggle text-white" data-card-toggle=""></a>
            </div>
        </div>
        <div class="card-body"> 
            
            <el-tabs v-model="form.type" @tab-click="handleClick">

                <el-tab-pane label="Yape" name="01">
                    <div class="row pt-1">
                        <div class="col-md-6">
                            <h4 class="control-label">Habilitar</h4>
                            <div :class="{'has-danger': errors.enabled_yape}"
                                    class="form-group">
                                <el-switch v-model="form.enabled_yape"
                                            active-text="Si"
                                            inactive-text="No"></el-switch>
                                <small v-if="errors.enabled_yape"
                                        class="form-control-feedback"
                                        v-text="errors.enabled_yape[0]"></small>
                            </div>
                        </div>
                        
                        <template v-if="form.enabled_yape">

                            <div class="col-md-6 mt-3">
                                <div class="form-group" :class="{'has-danger': errors.telephone_yape}">
                                    <label class="control-label">Número de teléfono <span class="text-danger">*</span></label>
                                    <el-input v-model="form.telephone_yape" placeholder="977523641"></el-input>
                                    <small class="form-control-feedback" v-if="errors.telephone_yape" v-text="errors.telephone_yape[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="form-group" :class="{'has-danger': errors.name_yape}">
                                    <label class="control-label">Nombres y Apellidos <span class="text-danger">*</span></label>
                                    <el-input v-model="form.name_yape"></el-input>
                                    <small class="form-control-feedback" v-if="errors.name_yape" v-text="errors.name_yape[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-6  mt-3">
                                <div class="form-group" :class="{'has-danger': errors.qrcode_yape}">
                                    <label class="control-label">Adjuntar código QR (Imágen) <span class="text-danger">*</span></label>
                                    
                                    <el-upload class="uploader"
                                            :headers="headers"
                                            :action="`/${resource}/upload-qrcode-yape`"
                                            :show-file-list="false"
                                            :on-success="onSuccess">
                                        <img v-if="form.image_url_yape" :src="form.image_url_yape" class="avatar">
                                        <i v-else class="el-icon-plus uploader-icon"></i>
                                    </el-upload>
                                    <small class="form-control-feedback" v-if="errors.qrcode_yape" v-text="errors.qrcode_yape[0]"></small>
                                </div>
                            </div>

                        </template>
        
                    </div>
                    <!-- <div class="form-actions text-right mt-3">
                        <el-button type="primary" @click="submit" :loading="loading_submit">Guardar</el-button>
                    </div> -->
                </el-tab-pane>


                <el-tab-pane label="Mercado Pago" name="02">

                    <div class="row pt-1">
                        <div class="col-md-6">
                            <h4 class="control-label">Habilitar</h4>
                            <div :class="{'has-danger': errors.enabled_mp}"
                                    class="form-group">
                                <el-switch v-model="form.enabled_mp"
                                            active-text="Si"
                                            inactive-text="No"></el-switch>
                                <small v-if="errors.enabled_mp"
                                        class="form-control-feedback"
                                        v-text="errors.enabled_mp[0]"></small>
                            </div>
                        </div>
                        
                        <template v-if="form.enabled_mp">

                            <div class="col-md-12 mt-3">
                                <div class="form-group" :class="{'has-danger': errors.public_key_mp}">
                                    <label class="control-label">Token público <span class="text-danger">*</span></label>
                                    <el-input v-model="form.public_key_mp"></el-input>
                                    <small class="form-control-feedback" v-if="errors.public_key_mp" v-text="errors.public_key_mp[0]"></small>
                                </div>
                            </div>


                            <div class="col-md-12 mt-3">
                                <div class="form-group" :class="{'has-danger': errors.access_token_mp}">
                                    <label class="control-label">Token de acceso (privado) <span class="text-danger">*</span>
                                        <el-tooltip class="item" effect="dark" content="El token de acceso no es visible, si desea modificarlo ingrese un valor" placement="top-end">
                                            <i class="fa fa-info-circle"></i>
                                        </el-tooltip>
                                    </label>
                                    <el-input v-model="form.access_token_mp" show-password></el-input>
                                    <small class="form-control-feedback" v-if="errors.access_token_mp" v-text="errors.access_token_mp[0]"></small>
                                </div>
                            </div>

                        </template>
        
                    </div>
                </el-tab-pane>

                <div class="form-actions text-right mt-3">
                    <el-button type="primary" @click="submit" :loading="loading_submit">Guardar</el-button>
                </div>
            </el-tabs>

        </div> 
    </div>
</template>

<style>

    .uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 208px;
        height: 208px;
        line-height: 208px;
        text-align: center;
    }

</style>

<script>

    export default {
        data() {
            return {
                headers: headers_token,
                resource: 'payment-configurations',
                recordId: null,
                form: {},
                errors: {},
                loading_submit: false,
            }
        },
        async created() {
            await this.initForm()
            await this.getData()
        },
        methods: {
            handleClick(){

            },
            submit(){

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
            onSuccess(response) { 

                if (response.success) {
                    this.form.qrcode_yape = response.data.filename
                    this.form.image_url_yape = response.data.temp_image
                    this.form.temp_path_yape = response.data.temp_path 
                } else {
                    this.$message.error(response.message)
                }
            },
            initForm(){

                this.form = {
                    enabled_yape : false,
                    name_yape : null,
                    telephone_yape: null,
                    type: '01',

                    qrcode_yape : null,
                    image_url_yape: null,
                    temp_path_yape: null,

                    
                    enabled_mp : false,
                    access_token_mp: null,
                    public_key_mp: null,
                }

                this.errors = {}

            },
            async getData() {
                await this.$http.get(`/${this.resource}/record`)
                    .then(response => {
                        this.form = response.data.data
                        this.form.type = '01'
                    })
            }, 
        }
    }
</script>
