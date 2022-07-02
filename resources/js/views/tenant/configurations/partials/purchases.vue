<template>
    <div class="row">
        <div class="col-md-6 mt-4">
            <label class="control-label">
                Poder cambiar el IGV global de los items en la compra.
            </label>
            <div :class="{'has-danger': errors.enabled_global_igv_to_purchase}"
                 class="form-group">
                <el-switch
                    v-model="config.enabled_global_igv_to_purchase"
                    active-text="Si"
                    inactive-text="No"
                    @change="ChangeEnabledGlobalIgvToPurchase"></el-switch>
                <small v-if="errors.enabled_global_igv_to_purchase"
                       class="form-control-feedback"
                       v-text="errors.enabled_global_igv_to_purchase[0]"></small>
            </div>
        </div>

        <div class="col-md-6 mt-4">
            <label class="control-label">
                Seleccionar por defecto <b>Poder cambiar el IGV global de los items en la compra</b>
                <el-tooltip class="item"
                            content="Solo aplica si la configuración 'Poder cambiar el IGV global de los items en la compra' se encuentra habilitada"
                            effect="dark"
                            placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </label>
            <div :class="{'has-danger': errors.checked_global_igv_to_purchase}"
                 class="form-group">
                <el-switch
                    v-model="form.checked_global_igv_to_purchase"
                    active-text="Si"
                    inactive-text="No"
                    @change="submitConfigPurchase"></el-switch>
                <small v-if="errors.checked_global_igv_to_purchase"
                       class="form-control-feedback"
                       v-text="errors.checked_global_igv_to_purchase[0]"></small>
            </div>
        </div>

        <div class="col-md-6 mt-4">
            <label class="control-label">
                Seleccionar por defecto <b>Actualizar precio de compra</b>
                <el-tooltip class="item"
                            content="Disponible en compras"
                            effect="dark"
                            placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </label>
            <div :class="{'has-danger': errors.checked_update_purchase_price}"
                 class="form-group">
                <el-switch
                    v-model="form.checked_update_purchase_price"
                    active-text="Si"
                    inactive-text="No"
                    @change="submitConfigPurchase"></el-switch>
                <small v-if="errors.checked_update_purchase_price"
                       class="form-control-feedback"
                       v-text="errors.checked_update_purchase_price[0]"></small>
            </div>
        </div>
        
        
        <div class="col-md-6 mt-4">
            <label class="control-label">
                Asignar moneda global de la compra a los items
                <el-tooltip class="item"
                            effect="dark"
                            content="Al agregar el item se seleccionará por defecto la moneda global elegida"
                            placement="top-start">
                    <i class="fa fa-info-circle"></i>
                </el-tooltip>
            </label>
            <div :class="{'has-danger': errors.set_global_purchase_currency_items}"
                 class="form-group">
                <el-switch
                    v-model="form.set_global_purchase_currency_items"
                    active-text="Si"
                    inactive-text="No"
                    @change="submitConfigPurchase"></el-switch>
                <small v-if="errors.set_global_purchase_currency_items"
                       class="form-control-feedback"
                       v-text="errors.set_global_purchase_currency_items[0]"></small>
            </div>
        </div>
    </div>
</template>

<script>

import {mapActions, mapState} from "vuex";

export default {
    props: [
        'errors',
        'form',
    ],
    data() {
        return {}
    },
    created() {
        this.loadConfiguration()
    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    methods: {
        submitConfigPurchase(){
            this.$emit('submitConfigPurchase')
        },
        ...mapActions([
            'loadConfiguration',
        ]),
        ChangeEnabledGlobalIgvToPurchase() {
            let conf = this.config.enabled_global_igv_to_purchase;
            // Para cada configuracion. Guarsdarla ebn el global.
            this.$store.commit('setEnabledGlobalIgvToPurchase', conf)
            this.$emit('EmitChange', conf)

        }
    }
}
</script>
