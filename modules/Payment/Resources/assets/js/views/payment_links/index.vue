<template>
<div>
    <div class="page-header pr-0">
        <h2>
            <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
        </h2>
        <ol class="breadcrumbs">
            <li class="active"><span>Links de pago</span></li>
        </ol>
        <div class="right-wrapper pull-right">
            <button class="btn btn-custom btn-sm mt-2 mr-2" type="button" @click.prevent="clickCreate()">
                <i class="fa fa-plus-circle"></i>Nuevo
            </button>
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-header bg-info">
            <h3 class="my-0">Generador de links de pago</h3>
        </div>
        <div class="card-body">
            <data-table :resource="resource">

                <tr slot="heading" width="100%">
                    <th>#</th>
                    <th>Identificador</th>
                    <th>Pago asociado</th>
                    <th>Tipo</th>
                    <th>Link</th>
                    <th>Total</th>
                    <th class="text-right"></th>
                </tr>

                <tr></tr>
                <tr slot-scope="{ index, row }">
                    <td>{{ index }}</td>
                    <td>{{ row.uuid }}</td>
                    <td>{{ row.payment_number_full }}</td>
                    <td>{{ row.payment_link_type_description }}</td>
                    <td>

                        <button type="button"
                                style="min-width: 41px"
                                class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                v-clipboard:copy="row.user_payment_link"
                                v-clipboard:success="onCopyText"
                                v-clipboard:error="onErrorCopyText">
                                Copiar
                        </button>

                    </td>
                    <td>{{ row.total }}</td>

                    <td class="text-right">
                        <div class="dropdown">
                            <button id="dropdownMenuButton" aria-expanded="false" aria-haspopup="true" class="btn btn-default btn-sm" data-toggle="dropdown" type="button">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">

                                <template v-if="typeUser === 'admin'">

                                    <template v-if="row.payment_link_type_id == '02'">
                                        <button class="dropdown-item" @click.prevent="clickShowTransactions(row.id)">
                                            Transacciones
                                        </button>
                                    </template>

                                    <template v-if="!row.has_payment">
                                        <button class="dropdown-item" @click.prevent="clickCreate(row.id)">
                                            Editar
                                        </button>
                                    </template>

                                    <button class="dropdown-item" @click.prevent="clickDelete(row.id)">
                                        Eliminar
                                    </button>
                                </template>
                            </div>
                        </div>
                    </td>
                </tr>
            </data-table>
        </div>

        <payment-link-form 
            :recordId="recordId" 
            :showDialog.sync="showDialog"
            ></payment-link-form>

        <payment-link-transactions 
            :recordId="recordId" 
            :showDialog.sync="showDialogTransactions"
            ></payment-link-transactions>
    </div>
</div>
</template>

<script>

    import PaymentLinkForm from "./form.vue";
    import PaymentLinkTransactions from "./partials/transactions.vue";
    import DataTable from "@components/DataTable.vue";
    import {deletable} from "@mixins/deletable";

    export default {
        props: [
            'typeUser'
        ],
        mixins: [deletable],
        components: {
            PaymentLinkForm,
            PaymentLinkTransactions,
            DataTable
        },
        data() {
            return { 
                resource: 'payment-links',
                recordId: null, 
                showDialog: false,
                showDialogTransactions: false,
            }
        },
        async created() {
        },
        methods: { 
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit("reloadData")
                );
            },
            clickShowTransactions(recordId){
                this.recordId = recordId
                this.showDialogTransactions = true
            },
            onCopyText: function(e) {
                this.$message.success('Texto copiado al portapapeles')
            },
            onErrorCopyText: function(e) {
                this.$message.error('No se pudo copiar el texto al portapapeles')
                console.log(e)
            },
            clickCreate(recordId = null){
                this.recordId = recordId
                this.showDialog = true
            }
        }
    }
</script>
