<template>
    <el-dialog
        :show-close="false"
        :title="titleDialog"
        :visible="showDialogTransfer"
        @open="create">
        <div v-loading="!fullLoad">

            <div
                class="col-12 row">

                <div class="col-6">
                    <label class="control-label">
                        Origen
                    </label>
                    <el-select v-model="transfer_amount.from"
                               :disabled="!fullLoad"
                               filterable
                               @change="setMaxAmount"
                    >
                        <!-- Bancos -->
                        <el-option v-for="option in tempFrom"
                                   :key="option.id"
                                   :disabled="alreadyOnTo(option.id)"
                                   :label="option.description"
                                   :value="option.id">

                        </el-option>


                    </el-select>
                </div>
                <div class="col-6">
                    <label class="control-label">
                        Destino
                    </label>
                    <el-select v-model="transfer_amount.to"
                               :disabled="!fullLoad"
                               filterable
                               @change="convertTotal"
                    >
                        <el-option v-for="option in tempTo"
                                   :key="option.id"
                                   :disabled="alreadyOnFrom(option.id)"
                                   :label="option.description"
                                   :value="option.id">

                        </el-option>

                    </el-select>
                </div>

                <div class="col-6">
                    <label class="control-label">
                        Cantidad
                        <el-tooltip
                            class="item"
                            content="Ingresa el monto a transferir"
                            effect="dark"
                            placement="top-start">
                            <i class="fa fa-info-circle"></i>
                        </el-tooltip>
                    </label>

                    <el-input-number
                        v-model="transfer_amount.amount_transform_temp"
                        :disabled="!fullLoad"
                        :max="maxTransfer"
                        :min="0"
                        @change="convertTotal"
                    >

                    </el-input-number>
                </div>
                <div class="col-6">
                    <label class="control-label">
                        Monto a transferir
                        <el-tooltip
                            class="item"
                            content="Si la moneda de la cuenta bancaria difiere de soles, se realiza la conversion."
                            effect="dark"
                            placement="top-start">
                            <i class="fa fa-info-circle"></i>
                        </el-tooltip>
                    </label>

                    <el-input-number
                        v-model="transfer_amount.amount_transform"
                        :disabled="true"
                        :max="maxTransfer"
                        :min="0"
                    >
                    </el-input-number>
                </div>
                <div class="col-12">
                    &nbsp;
                </div>
                <div class="col-4">
                    &nbsp;
                </div>
                <div class="col-4">
                    <el-button
                        class="submit"
                        style="width: 100%;"
                        @click.prevent="clickClose">
                        Cerrar
                    </el-button>
                </div>
                <div class="col-4">
                    <el-button
                        :disabled="!fullLoad || transfer_amount.amount_transform == 0"
                        class="submit "
                        style="width: 100%;"
                        type="primary"
                        @click.prevent="transferAmount">
                        <i class="fa fa-cash-register"></i>
                        Transferir
                    </el-button>
                </div>


            </div>
        </div>
    </el-dialog>
</template>

<script>
import {mapActions, mapState} from "vuex/dist/vuex.mjs";

export default {
    props: [
        'showDialogTransfer',
    ],
    computed: {
        ...mapState([
            'config',
            'exchange_rate_sale',
        ]),
    },
    data() {
        return {
            titleDialog: "Transferencia entre cuentas",
            loading: false,
            resource: 'finances/balance',
            maxTransfer: 99999,
            tempFrom: [],
            tempTo: [],
            banks: [],
            cashs: [],
            fullLoad: false,
            bankLoad: false,
            cashLoad: false,
            transfer_amount: {
                amount_transform_temp: 0,
                amount_transform: 0,
                from: null,
                to: null,
            },
        }
    },
    created() {
        this.loadConfiguration();
        this.clearForm()
    },
    methods: {

        ...mapActions([
            'loadConfiguration',
        ]),
        alreadyOnTo(id) {
            return id == this.transfer_amount.to;
        },
        alreadyOnFrom(id) {
            return id == this.transfer_amount.from;
        },
        setMaxAmount() {
            let max = this.maxTransfer;
            let temporalBank = this.banks;
            let temporalCash = this.cashs
            let check = this.transfer_amount.from;

            let tempB = temporalBank.find((item) => item.id === check);
            if (tempB === undefined) {
                tempB = temporalCash.find((item) => item.id === check);
                // No es posible obtener el total de caja en este momento
                max = 9999
            } else {
                max = parseFloat(tempB.balance)
            }
            if (isNaN(max)) {
                max = 0;
            }
            if (max !== 0) {
                this.maxTransfer = max
            }
            this.convertTotal()
        },
        clearForm() {
            this.transfer_amount.amount_transform = 0;
            this.transfer_amount.amount_transform_temp = 0;

            this.transfer_amount.from = null;
            this.transfer_amount.to = null;

            this.tempFrom = [];
            this.tempTo = [];
        },

        create() {
            this.tempFrom = [];
            this.tempTo = [];
            this.getBankAccounts();
            this.getCashAccounts();
        },
        getBankAccounts() {

            this.bankLoad = false;
            this.fullLoad = false;
            this.$http.post(`/${this.resource}/bank_accounts`, {}).then((resource) => {
                let data = resource.data;
                this.banks = data.banks

                for (var j = 0; j < data.banks.length; j++) {
                    this.tempFrom.push(data.banks[j]);
                    this.tempTo.push(data.banks[j]);
                }
            }).finally(() => {
                this.bankLoad = true;
                if (this.bankLoad && this.cashLoad) {
                    this.fullLoad = true;
                }
            })

        },

        getCashAccounts() {
            this.cashLoad = false;
            this.fullLoad = false;

            this.$http.post(`/${this.resource}/cash`, {}).then((resource) => {
                let data = resource.data;
                this.cashs = data.cash

                for (var j = 0; j < data.cash.length; j++) {
                    this.tempFrom.push(data.cash[j]);
                    this.tempTo.push(data.cash[j]);
                }
            }).finally(() => {
                this.cashLoad = true;
                if (this.bankLoad && this.cashLoad) {
                    this.fullLoad = true;
                }
            })
        },
        transferAmount() {
            this.$http.post(`/${this.resource}/transfer`, {data: this.transfer_amount}).then((resource) => {
                let data = resource.data;
                this.cashs = data.cash
            }).then(() => {
                this.$emit('reloadAccounts', true)
                this.clickClose()
            })

        },
        clickFinalize() {
            location.href = `/${this.resource}`
        },
        clickNewDocument() {
            this.clickClose()
        },
        clickClose() {
            this.$emit('update:showDialogTransfer', false)
            this.clearForm()
        },
        convertTotal() {
            let fromAccount = _.find(this.tempFrom, {id: this.transfer_amount.from});
            let toAccount = _.find(this.tempTo, {id: this.transfer_amount.to});
            // se buscan las cuentas primero
            if (fromAccount !== undefined && toAccount !== undefined) {
                let coinIn = this.config.currency_type_id;
                let coinOut = this.config.currency_type_id;
                // para caja, se define la moneda por defecto del sistema

                if (fromAccount.currency_type_id !== undefined) {
                    coinIn = fromAccount.currency_type_id;
                }
                if (toAccount.currency_type_id !== undefined) {
                    coinOut = toAccount.currency_type_id;
                }
                let total = this.transfer_amount.amount_transform_temp
                if (coinIn !== coinOut) {
                    if (coinIn === 'PEN') {
                        // si la moneda de ingreso es Soles, se divide
                        total = total / this.exchange_rate_sale;
                    } else {
                        total = total * this.exchange_rate_sale;

                    }
                }
                this.transfer_amount.amount_transform = total;
            }


        }
    }
}
</script>
