<template>
    <div>
        <!-- Basado en resources/js/views/tenant/sale_notes/index.vue -->
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard">
                    <i class="fas fa-tachometer-alt">
                    </i>
                </a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active">
                    <span>Recibos de pago</span>
                </li>
            </ol>
            <!--
            <div class="right-wrapper pull-right">
                <a class="btn btn-custom btn-sm  mt-2 mr-2"
                   href="#"
                   @click.prevent="clickCreate()">
                    <i class="fa fa-plus-circle">
                    </i> Nuevo</a>
                <a class="btn btn-custom btn-sm  mt-2 mr-2"
                   href="#"
                   @click.prevent="onOpenModalGenerateCPE">Generar comprobante desde múltiples Notas</a>
                <a v-if="config.send_data_to_other_server === true"
                   class="btn btn-custom btn-sm  mt-2 mr-2"
                   href="#"
                   @click.prevent="onOpenModalMigrateNv">Migrar Datos</a>
            </div>
            -->
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right">
                    </i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns"
                                          :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table-payment-receipt
                    :resource="resource"
                    :extraquery="{onlySuscription:1}"
                >
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Hijo</th>
                        <th>Grado</th>
                        <th>Sección</th>
                        <th>Recibo de pago</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th
                            class="text-right">F. Vencimiento
                        </th>
                        <!--
                        <th v-if="columns.total_exportation.visible"
                            class="text-right">T.Exportación
                        </th>
                        <th v-if="columns.total_free.visible"
                            class="text-right">T.Gratuito
                        </th>
                        <th v-if="columns.total_unaffected.visible"
                            class="text-right">T.Inafecta
                        </th>
                        - ->
                        <th v-if="columns.total_exonerated.visible"
                            class="text-right">T.Exonerado
                        </th>
                        <!--
                        <th v-if="columns.total_taxed.visible"
                            class="text-right">T.Gravado
                        </th>
                        <th v-if="columns.total_igv.visible"
                            class="text-right">T.Igv
                        </th>
                        -->
                        <th class="text-right">Total</th>

                        <th v-if="columns.total_paid.visible"
                            class="text-center">Pagado
                        </th>
                        <th v-if="columns.total_pending_paid.visible"
                            class="text-center">Por pagar
                        </th>

                        <th class="text-center">Comprobantes</th>
                        <th class="text-center">Estado pago</th>
<!--                        <th class="text-center">Orden de compra</th>-->
                        <th class="text-center">Pagos</th>
                        <th class="text-center">Descarga</th>
<!--                        <th class="text-center"> Recurrencia </th>-->
                       <!--
                         <th v-if="columns.type_period.visible"
                            class="text-center">
                            Tipo Periodo
                        </th>
                        <th v-if="columns.quantity_period.visible"
                            class="text-center">
                            Cantidad Periodo
                        </th>
                        <th v-if="columns.paid.visible"
                            class="text-center">
                            Estado de Pago
                        </th>
                        <!--
                        <th v-if="columns.license_plate.visible"
                            class="text-center">
                            Placa
                        </th>
                        -->
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <!-- # -->
                        <td>{{ index }}</td>
                        <!-- Fecha Emisión -->
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <!-- Cliente -->
                        <td>{{ row.customer_name }}<br/>
                            <small v-text="row.customer_number">
                            </small>
                        </td>
                        <!-- Hijo -->
                        <td>{{ row.children_name }}<br/>
                            <small v-text="row.children_number"></small>
                        </td>
<!--                        Grado-->
                    <td>{{ row.grade }} </td>
<!--                        Sección -->
                    <td>{{ row.section }} </td>
                        <!-- Recibo de pago -->
                        <td>{{ row.full_number }} </td>
                        <!-- Estado  -->
                        <td>{{ row.state_type_description }}</td>
                        <!--Moneda -->
                        <td class="text-center">{{ row.currency_type_id }}</td>

                        <!-- F. Vencimiento -->
                        <td
                            class="text-right">{{ row.due_date }}
                        </td>
                        <!-- -- >
                        <td v-if="columns.total_exportation.visible"
                            class="text-right">{{ row.total_exportation }}
                        </td>
                        <! -- -- >
                        <td v-if="columns.total_free.visible"
                            class="text-right">{{ row.total_free }}
                        </td>
                        <! -- -- >
                        <td v-if="columns.total_unaffected.visible"
                            class="text-right">{{ row.total_unaffected }}
                        </td>
                        <! -- T.Exonerado - ->
                        <td v-if="columns.total_exonerated.visible"
                            class="text-right">{{ row.total_exonerated }}
                        </td>

                        <!-- -- >
                        <td v-if="columns.total_taxed.visible"
                            class="text-right">{{ row.total_taxed }}
                        </td>
                        <!-- - ->
                        <td v-if="columns.total_igv.visible"
                            class="text-right">{{ row.total_igv }}
                        </td>
                        <!-- Total -->
                        <td class="text-right">{{ row.total }}</td>

                        <!--Pagado -->
                        <td v-if="columns.total_paid.visible"
                            class="text-center">
                            {{ row.total_paid }}
                        </td>
                        <!-- Por pagar -->
                        <td v-if="columns.total_pending_paid.visible"
                            class="text-center">
                            {{ row.total_pending_paid }}
                        </td>
                        <!--Comprobantes -->
                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i"
                                       class="d-block"
                                       v-text="document.number_full">
                                </label>
                            </template>
                        </td>
                        <!-- Estado pago -->
                        <td class="text-center">
                            <span
                                :class="{'bg-success': (row.total_canceled), 'bg-warning': (!row.total_canceled)}"
                                class="badge text-white">{{ row.total_canceled ? 'Pagado' : 'Pendiente' }}
                            </span>
                        </td>

                        <!-- -- >
                        <td>{{ row.purchase_order }}</td>

                        <!-- Pagos -->
                        <td class="text-center">
                            <button class="btn waves-effect waves-light btn-xs btn-primary"
                                    style="min-width: 41px"
                                    type="button"
                                    @click.prevent="clickPayment(row.id)">
                                <i class="fas fa-money-bill-alt">
                                </i>
                            </button>
                        </td>

                        <!-- Descarga -->
                        <td class="text-right">
                            <button class="btn waves-effect waves-light btn-xs btn-info"
                                    type="button"
                                    @click.prevent="clickDownload(row.external_id)">
                                <i class="fas fa-file-pdf">
                                </i>
                            </button>
                        </td>
                        <!-- -- >
                        <td class="text-right">
                            <template v-if="row.type_period && row.quantity_period>0">
                                <el-switch v-model="row.enabled_concurrency"
                                           :disabled="row.apply_concurrency"
                                           active-text="Si"
                                           inactive-text="No"
                                           @change="changeConcurrency(row)">
                                </el-switch>
                            </template>
                        </td>

                        <!-- type_period -- >
                        <td v-if="columns.type_period.visible"
                            class="text-right">
                            {{ row.type_period | period }}
                        </td>
                        <!-- quantity_period -- >
                        <td v-if="columns.quantity_period.visible"
                            class="text-right">
                            {{ row.quantity_period }}
                        </td>
                         - >
                        <td v-if="columns.paid.visible"
                            class="text-right">
                            {{ row.paid ? 'Pagado' : 'Pendiente' }}
                        </td>
                        <!-- license_plate-->
                    <!--
                        <td v-if="columns.license_plate.visible"
                            class="text-right">
                            {{ row.license_plate }}
                        </td>
                    -->
                        <td class="text-right">
                            <button v-if="row.state_type_id != '11'"
                                    class="btn waves-effect waves-light btn-xs btn-danger"
                                    data-placement="top"
                                    data-toggle="tooltip"
                                    title="Anular"
                                    type="button"
                                    @click.prevent="clickVoided(row.id)">
                                <i class="fas fa-trash">
                                </i>
                            </button>

                            <!--
                            <button v-if="row.btn_generate && row.state_type_id != '11' && typeUser != 'seller'"
                                    class="btn waves-effect waves-light btn-xs btn-primary"
                                    data-placement="top"
                                    data-toggle="tooltip"
                                    title="Editar"
                                    type="button"
                                    @click.prevent="clickCreate(row.id)">
                                <i class="fas fa-file-signature">
                                </i>
                            </button>
                            -->

                            <button v-if="!row.changed && row.state_type_id != '11' && soapCompany != '03'"
                                    class="btn waves-effect waves-light btn-xs btn-success"
                                    data-placement="top"
                                    data-toggle="tooltip"
                                    title="Generar comprobante"
                                    type="button"
                                    @click.prevent="clickGenerate(row.id)">
                                <i class="fas fa-file-excel">
                                </i>
                            </button>
                            <!--
                            <el-tooltip class="item"
                                        content="Generar guía desde CPE"
                                        effect="dark"
                                        placement="top-start">
                                <template v-for="(document,i) in row.documents">
                                    <a v-if="row.changed"
                                       :key="i"
                                       :href="`/dispatches/create/${document.id}`"
                                       class="btn waves-effect waves-light btn-xs btn-warning m-1__2">
                                        <i class="fas fa-file-alt">
                                        </i>
                                    </a>
                                </template>
                            </el-tooltip>
                            <el-tooltip class="item"
                                        content="Generar guía desde Nota Venta"
                                        effect="dark"
                                        placement="top-start">
                                <a :href="`/dispatches/generate/${row.id}`"
                                   class="btn waves-effect waves-light btn-xs btn-primary m-1__2">
                                    <i class="fas fa-file-alt">
                                    </i>
                                </a>
                            </el-tooltip>
                            -->

                            <button v-if="row.state_type_id != '11'"
                                    class="btn waves-effect waves-light btn-xs btn-info"
                                    data-placement="top"
                                    data-toggle="tooltip"
                                    title="Imprimir"
                                    type="button"
                                    @click.prevent="clickOptions(row.id)">
                                <i class="fas fa-print">
                                </i>
                            </button>
                            <!--
                            <button class="btn waves-effect waves-light btn-xs btn-info"
                                    title="Duplica la Recibo de pago"
                                    type="button"
                                    @click="duplicate(row.id)">
                                <i class="fas fa-copy">
                                </i>
                            </button>
                            <button
                                v-if="row.state_type_id != '11' && row.send_other_server=== true"
                                class="btn waves-effect waves-light btn-xs btn-inverse"
                                data-placement="top"
                                data-toggle="tooltip"
                                title="Enviar a otro servidor"
                                type="button"
                                @click.prevent="sendToServer(row.id)">
                                <i class="fas fa-wifi">
                                </i>
                            </button>
                            -->

                        </td>


                    </tr>
                </data-table-payment-receipt>
            </div>
        </div>

        <sale-note-payments :documentId="recordId"
                            :showDialog.sync="showDialogPayments">
        </sale-note-payments>

        <sale-notes-options :configuration="config"
                            :recordId="saleNotesNewId"
                            :showClose="true"
                            :showDialog.sync="showDialogOptions">
        </sale-notes-options>

        <sale-note-generate :recordId="recordId"
                            :show.sync="showDialogGenerate"
                            :showClose="false"
                            :showGenerate="true">
        </sale-note-generate>
        <ModalGenerateCPE :show.sync="showModalGenerateCPE">
        </ModalGenerateCPE>
        <!--
        <UploadToOtherServer
            :configuration="config"
            :showMigrate.sync="showMigrateNv"
        >
        </UploadToOtherServer>
        -->
    </div>
</template>

<script>
// import DataTable from '../../../components/DataTableSaleNote.vue'
// import UploadToOtherServer from './partials/upload_other_server_group.vue'
import SaleNotePayments from './partials/payments.vue'
import SaleNotesOptions from './partials/options.vue'
import SaleNoteGenerate from './partials/option_documents'
import ModalGenerateCPE from './ModalGenerateCPE'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import {deletable} from "../../../../../../resources/js/mixins/deletable";

export default {
    // tenant-index-payment-receipt
    props: [
        'soapCompany',
        // 'typeUser',
        'configuration'
    ],
    mixins: [deletable],
    components: {
       //  DataTable,
        SaleNotePayments,
        SaleNotesOptions,
        SaleNoteGenerate,
        ModalGenerateCPE,
        // UploadToOtherServer
    },
    computed: {
        ...mapState([
            'config',
        ]),
    },
    data() {
        return {
            showModalGenerateCPE: false,
            showMigrateNv: false,
            resource: 'sale-notes',
            showDialogPayments: false,
            showDialogOptions: false,
            showDialogGenerate: false,
            saleNotesNewId: null,
            recordId: null,
            columns: {
                total_paid: {
                    title: 'Pagado',
                    visible: false
                },

                total_pending_paid: {
                    title: 'Por pagar',
                    visible: false
                },
                /*
                paid: {
                    title: 'Estado de Pago',
                    visible: false
                },
                /*

                due_date: {
                    title: 'Fecha de Vencimiento',
                    visible: false
                },
                total_free: {
                    title: 'T.Gratuito',
                    visible: false
                },
                total_exportation: {
                    title: 'T.Exportación',
                    visible: false
                },
                total_unaffected: {
                    title: 'T.Inafecto',
                    visible: false
                },
                total_exonerated: {
                    title: 'T.Exonerado',
                    visible: false
                },
                total_taxed: {
                    title: 'T.Gravado',
                    visible: false
                },
                total_igv: {
                    title: 'T.IGV',
                    visible: false
                },
                type_period: {
                    title: 'Tipo Periodo',
                    visible: true
                },
                quantity_period: {
                    title: 'Cantidad Periodo',
                    visible: true
                },
                license_plate: {
                    title: 'Placa',
                    visible: true
                },
                */

            }
        }
    },
    created() {
        this.loadConfiguration()
        this.$store.commit('setConfiguration', this.configuration)
    },
    filters: {
        period(name) {
            let res = ''
            switch (name) {
                case 'month':
                    res = 'Mensual'
                    break
                case 'year':
                    res = 'Anual'
                    break
                default:

                    break;
            }

            return res
        }
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
        ]),
        duplicate(id) {
            this.$http.post(`${this.resource}/duplicate`, {id})
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('Se guardaron los cambios correctamente.')
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error('No se guardaron los cambios')
                    }
                })
                .catch(error => {

                })
            this.$eventHub.$emit('reloadData')
        },
        onOpenModalGenerateCPE() {
            this.showModalGenerateCPE = true;
        },
        onOpenModalMigrateNv() {
            this.showMigrateNv = true;
        },
        clickDownload(external_id) {
            window.open(`/sale-notes/downloadExternal/${external_id}`, '_blank');
        },
        clickOptions(recordId) {
            this.saleNotesNewId = recordId
            this.showDialogOptions = true
        },
        sendToServer(recordId) {
            this.$http.post('/sale-notes/UpToOther', {'sale_note_id': recordId}).then(response => {
                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.$eventHub.$emit('reloadData')
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                if (
                    error.response !== undefined &&
                    error.response.status !== undefined &&
                    error.response.status.errors !== undefined &&
                    error.response.status === 422) {
                    this.errors = error.response.data.errors;
                } else {
                    console.log(error);
                }
            }).then(() => {
            });
        },
        clickGenerate(recordId) {
            this.recordId = recordId
            this.showDialogGenerate = true
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickCreate(id = '') {
            location.href = `/${this.resource}/create/${id}`
        },

        changeConcurrency(row) {
            this.$http.post(`/${this.resource}/enabled-concurrency`, row).then(response => {
                if (response.data.success) {
                    this.$message.success(response.data.message);
                    this.$eventHub.$emit('reloadData')
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
            });
        },
        clickVoided(id) {
            this.anular(`/${this.resource}/anulate/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },

    }
}
</script>
