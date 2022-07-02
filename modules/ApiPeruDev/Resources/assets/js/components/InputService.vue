<template>
    <el-input
        :value="value"
        :maxlength="maxLength"
        @input="handleInput($event)"
        show-word-limit>
        <template v-if="buttonText">
            <el-button type="primary"
                       slot="append"
                       :loading="loading"
                       icon="el-icon-search"
                       @click.prevent="clickSearch">{{ buttonText }}
            </el-button>
        </template>
    </el-input>
</template>
<script type="text/javascript">
    export default {
        name: 'ApiPeruDevInputService',
        props: {
            identity_document_type_id: {
                required: true,
                type: String
            },
            value: {
                required: true,
                type: String,
                default: ''
            }
        },
        data() {
            return {
                loading: false,
                resource_base: 'service',
                resource: null,
                maxLength: 20,
                buttonText: null
            }
        },
        created() {
            this.changeIdentityDocumentTypeId()
        },
        mounted() {
            this.$eventHub.$on('enableClickSearch',()=>{
                this.clickSearch()
            })
        },
        watch: {
            identity_document_type_id() {
                this.changeIdentityDocumentTypeId()
            },
        },
        methods: {
            changeIdentityDocumentTypeId() {
                this.buttonText = null;
                if(this.identity_document_type_id === '6') {
                    this.maxLength = 11;
                    this.buttonText = 'SUNAT';
                    this.resource = this.resource_base+'/ruc';
                }
                if(this.identity_document_type_id === '1') {
                    this.maxLength = 8;
                    this.buttonText = 'RENIEC';
                    this.resource = this.resource_base+'/dni';
                }
                if(this.identity_document_type_id !== '6' && this.identity_document_type_id !== '1') {
                    this.maxLength = 20
                }
            },
            handleInput (value) {
                this.$emit('input', value)
            },
            clickSearch() {
                this.loading = true;
                this.$http.get(`/${this.resource}/${this.value}`)
                    .then(response => {
                        let res = response.data;

                        if (res.success) {
                            let data_return = res.data
                            // Se añaden datos para que funcione en varias busqeudas internas
                            data_return.nombre_o_razon_social = null;
                            data_return.nombre_completo = null;
                            data_return.direccion_completa = null;
                            data_return.condicion = null;
                            data_return.estado = null;
                            data_return.ubigeo = [];
                            data_return.ubigeo[0] = null;// department_id
                            data_return.ubigeo[1] = null;// province_id
                            data_return.ubigeo[2] = null;// district_id
                            if (data_return.name !== undefined) {
                                data_return.nombre_completo = data_return.name
                                data_return.nombre_o_razon_social = data_return.name
                            }
                            if (
                                data_return.trade_name !== undefined  &&
                                data_return.trade_name !== null
                            ) {
                                if( data_return.trade_name != '') {
                                    data_return.nombre_o_razon_social = data_return.trade_name
                                }
                            }
                            if (data_return.address !== undefined) {
                                data_return.direccion_completa = data_return.address
                                data_return.direccion = data_return.address
                            }
                            if (data_return.condition !== undefined) {
                                data_return.condicion = data_return.condition
                            }
                            if (data_return.state !== undefined) {
                                data_return.estado = data_return.state
                            }
                            if (data_return.department_id !== undefined) {
                                data_return.ubigeo[0] = data_return.department_id
                            }
                            if (data_return.district_id !== undefined) {
                                data_return.ubigeo[2] = data_return.district_id
                            }
                            if (data_return.province_id !== undefined) {
                                data_return.ubigeo[1] = data_return.province_id
                            }
                            this.$emit('search', data_return)
                        } else {
                            this.$message.error(res.message)
                        }
                    })
                    .catch(error => {
                        console.log(error.response)
                    })
                    .finally(() => {
                        this.loading = false
                    })
            }
        }
    }
</script>
