<template>
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="my-0">{{ company.name }} - {{ company.number }}</h3>
        </div>
        <div class="card-body"> 

            <template v-if="apply_conversion">
                <div class="row mb-3">
                    <div class="col-md-12">
                         <el-alert
                            title="Se aplicó conversión al tipo de cambio"
                            type="info"
                            show-icon>
                        </el-alert>
                    </div>
                </div>
            </template>

            <template v-if="payment_link.payment_link_type_id == '01'">
                
                <form-yape 
                    :payment_link="payment_link"
                    :payment_configuration="payment_configuration"
                    :total="total"
                    >
                </form-yape>

            </template>
            <template v-else-if="payment_link.payment_link_type_id == '02'">
                
                <form-mercado-pago 
                    :payment_link="payment_link"
                    :payment_configuration="payment_configuration"
                    :total="total"
                    >
                </form-mercado-pago>

            </template>
             
        </div> 
    </div>
</template>

<script>

    import FormYape from './partials/yape.vue'
    import FormMercadoPago from '@viewsModuleMercadoPago/transactions/index.vue'

    export default {
        components: {
            FormYape,
            FormMercadoPago
        },
        props: [
            'payment_link',
            'company',
            'payment_configuration',
            'total',
            'apply_conversion',
        ],
        data() {
            return {
                resource: 'public-payment-links',
                form: {},
                errors: {},
                loading_submit: false,
            }
        },
        async created() {
            await this.initForm()
        },
        methods: {
            initForm(){

                this.form = {
                }

                this.errors = {}

            },
        }
    }
</script>
