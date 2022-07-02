<template>
    <div>
        
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <div class="short-div col-md-12">
                        <h4><b>Titular:</b> {{ payment_configuration.name_yape}}</h4>
                    </div>
                    <div class="short-div col-md-12">
                        <h4><b>Número:</b> {{ payment_configuration.telephone_yape}}</h4>
                    </div>
                    <div class="short-div col-md-12">
                        <h4><b>Total a pagar:</b> S/ {{ total }}</h4>
                    </div>
                </div>
            </div>
            
            <div class="col-md-7">
                <img class="img-fluid img-yape" :src="payment_configuration.image_url_yape" alt="Yape">
            </div> 
        </div> 

    </div>
</template>

<style scoped>
    .img-yape{
        width: 90%;
    }
</style>

<script>

    export default {
        props: [
            'payment_link',
            'payment_configuration',
            'total',
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
