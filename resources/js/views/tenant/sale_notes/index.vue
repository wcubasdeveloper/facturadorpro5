<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Notas de Venta</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a href="#" @click.prevent="clickCreate()" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
                <a href="#" @click.prevent="onOpenModalGenerateCPE" class="btn btn-custom btn-sm  mt-2 mr-2">Generar comprobante desde múltiples Notas</a>
                <a href="#" v-if="config.send_data_to_other_server === true" @click.prevent="onOpenModalMigrateNv" class="btn btn-custom btn-sm  mt-2 mr-2">Migrar Datos</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox @change="getColumnsToShow(1)" v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-right"  v-if="columns.seller_name.visible" >Vendedor</th>

                        <th class="text-center">Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Nota de Venta</th>
                        <th>Estado</th>
                        <th class="text-center">Moneda</th>
                        <th class="text-right" v-if="columns.due_date.visible">F. Vencimiento</th>
                        <th class="text-right" v-if="columns.total_exportation.visible">T.Exportación</th>
                        <th class="text-right" v-if="columns.total_free.visible">T.Gratuito</th>
                        <th class="text-right" v-if="columns.total_unaffected.visible">T.Inafecta</th>
                        <th class="text-right" v-if="columns.total_exonerated.visible">T.Exonerado</th>
                        <th class="text-right" v-if="columns.total_taxed.visible">T.Gravado</th>
                        <th class="text-right" v-if="columns.total_igv.visible">T.Igv</th>
                        <th class="text-right">Total</th>

                        <th class="text-center" v-if="columns.total_paid.visible">Pagado</th>
                        <th class="text-center" v-if="columns.total_pending_paid.visible">Por pagar</th>

                        <th class="text-center">Comprobantes</th>
                        <th class="text-center">Estado pago</th>
                        <th class="text-center">Orden de compra</th>

                        <th class="text-center">Pagos</th>
                        <th class="text-center">Descarga</th>
                        <th class="text-center" v-if="columns.recurrence.visible">
                            Recurrencia
                        </th>
                         <th class="text-center" v-if="columns.type_period.visible" >
                            Tipo Periodo
                        </th>
                        <th class="text-center" v-if="columns.quantity_period.visible" >
                            Cantidad Periodo
                        </th>
                        <th class="text-center" v-if="columns.paid.visible">
                            Estado de Pago
                        </th>
                        <th class="text-center" v-if="columns.license_plate.visible">
                            Placa
                        </th>
                        <th class="text-right">Acciones</th>
                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                    <td class="text-right"  v-if="columns.seller_name.visible" >{{ row.seller_name }}</td>


                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.full_number }}
                        </td>
                        <td>{{ row.state_type_description }}</td>
                        <td class="text-center">{{ row.currency_type_id }}</td>

                        <td class="text-right"  v-if="columns.due_date.visible" >{{ row.due_date }}</td>
                        <td class="text-right"  v-if="columns.total_exportation.visible" >{{ row.total_exportation }}</td>
                        <td class="text-right" v-if="columns.total_free.visible">{{ row.total_free }}</td>
                        <td class="text-right" v-if="columns.total_unaffected.visible">{{ row.total_unaffected }}</td>
                        <td class="text-right" v-if="columns.total_exonerated.visible">{{ row.total_exonerated }}</td>

                        <td class="text-right" v-if="columns.total_taxed.visible">{{ row.total_taxed }}</td>
                        <td class="text-right" v-if="columns.total_igv.visible">{{ row.total_igv }}</td>
                        <td class="text-right">{{ row.total }}</td>

                        <td class="text-center" v-if="columns.total_paid.visible">
                            {{row.total_paid}}
                        </td>
                        <td class="text-center" v-if="columns.total_pending_paid.visible">
                            {{row.total_pending_paid}}
                        </td>
                        <td>
                            <template v-for="(document,i) in row.documents">
                                <label :key="i" v-text="document.number_full" class="d-block"></label>
                            </template>
                        </td>
                        <td class="text-center">

                            <template v-if="row.state_type_id === '11'">
                                <span class="badge text-white bg-danger">{{row.state_type_description}}</span>
                            </template>
                            <template v-else>
                                <span class="badge text-white" :class="{'bg-success': (row.total_canceled), 'bg-warning': (!row.total_canceled)}">{{row.total_canceled ? 'Pagado':'Pendiente'}}</span>
                            </template>
                            
                        </td>

                        <td>{{ row.purchase_order }}</td>

                        <td class="text-center">
                            <button type="button" style="min-width: 41px" class="btn waves-effect waves-light btn-xs btn-primary"
                                    @click.prevent="clickPayment(row.id)" ><i class="fas fa-money-bill-alt"></i></button>
                        </td>

                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickDownload(row.external_id)"><i class="fas fa-file-pdf"></i></button>
                        </td>
                        <td class="text-right" v-if="columns.recurrence.visible">
                            <template v-if="row.type_period && row.quantity_period>0">
                                <el-switch :disabled="row.apply_concurrency" v-model="row.enabled_concurrency" active-text="Si" inactive-text="No" @change="changeConcurrency(row)"></el-switch>
                            </template>
                        </td>

                        <td class="text-right" v-if="columns.type_period.visible">
                            {{ row.type_period | period}}
                        </td>
                        <td class="text-right" v-if="columns.quantity_period.visible">
                            {{row.quantity_period}}
                        </td>

                        <td class="text-right" v-if="columns.paid.visible" >
                            {{row.paid ? 'Pagado' : 'Pendiente'}}
                        </td>

                        <td class="text-right" v-if="columns.license_plate.visible" >
                            {{row.license_plate}}
                        </td>

                        <td class="text-right">

                            <div class="dropdown">
                                <button class="btn btn-default btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">


                            <button
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Anular"
                                v-if="row.state_type_id != '11'"
                                type="button"
                                class="dropdown-item"
                             @click.prevent="clickVoided(row.id)">
<!--                                <i class="fas fa-trash"></i>-->
                                Anular
                            </button>

                            <button data-toggle="tooltip"
                                    data-placement="top"
                                    title="Editar"
                                    type="button"
                                    class="dropdown-item"
                                    @click.prevent="clickCreate(row.id)"
                                    v-if="row.btn_generate && row.state_type_id != '11' && typeUser != 'seller'">
<!--                                        <i class="dropdown-item fas fa-file-signature"></i>-->
                                Editar
                            </button>

                            <button data-toggle="tooltip"
                                    data-placement="top"
                                    title="Generar comprobante"
                                    type="button"
                                    class="dropdown-item"
                                    @click.prevent="clickGenerate(row.id)"
                                    v-if="!row.changed && row.state_type_id != '11' && soapCompany != '03'">
<!--                                <i class="dropdown-item fas fa-file-excel"></i>-->
                                Generar comprobante
                            </button>

                            <el-tooltip class="item" effect="dark" content="Generar guía desde CPE" placement="top-start">
                                <template v-for="(document,i) in row.documents" >
                                    <a :href="`/dispatches/create/${document.id}`"
                                       class="dropdown-item"
                                        v-if="row.changed" :key="i">
<!--                                        <i class="dropdown-item fas fa-file-alt"></i>-->
                                        Generar guía desde

                                    </a>
                                </template>
                            </el-tooltip>

                            <el-tooltip
                                class="item"
                                effect="dark"
                                content="Generar guía desde Nota Venta"
                                placement="left">
                                <a :href="`/dispatches/generate/${row.id}`"
                                   class="dropdown-item"
                                >
                                    Generar guía
                                </a>
                            </el-tooltip>

                            <button
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Imprimir"
                                v-if="row.state_type_id != '11'"
                                type="button"
                                class="dropdown-item"
                                    @click.prevent="clickOptions(row.id)">
                                    <!--                                <i class="dropdown-item fas fa-print"></i>-->
                                    Imprimir
                            </button>
                            <button @click="duplicate(row.id)"
                                    title="Duplica la nota de venta"
                                    type="button"
                                    class="dropdown-item"
                                    >
<!--                                <i class="dropdown-item fas fa-copy"></i>-->
                                Duplica la nota de venta
                            </button>
                            <button
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Enviar a otro servidor"
                                v-if="row.state_type_id != '11' && row.send_other_server=== true"
                                type="button"
                                class="dropdown-item"
                                @click.prevent="sendToServer(row.id)">
<!--                                <i class="dropdown-item fas fa-wifi"></i>-->
                                Enviar a otro servidor
                            </button>

                                </div>
                            </div>

                        </td>


                    </tr>
                </data-table>
            </div>
        </div>

        <sale-note-payments :showDialog.sync="showDialogPayments"
                            :documentId="recordId"></sale-note-payments>

        <sale-notes-options :showDialog.sync="showDialogOptions"
                          :recordId="saleNotesNewId"
                          :showClose="true"
                          :configuration="config"></sale-notes-options>

        <sale-note-generate :show.sync="showDialogGenerate"
                           :recordId="recordId"
                           :showGenerate="true"
                           :showClose="false"></sale-note-generate>
        <ModalGenerateCPE :show.sync="showModalGenerateCPE"></ModalGenerateCPE>
        <UploadToOtherServer
            :configuration="config"
            :showMigrate.sync="showMigrateNv"
        ></UploadToOtherServer>
    </div>
</template>

<script>
    import DataTable from '../../../components/DataTableSaleNote.vue'
    import UploadToOtherServer from './partials/upload_other_server_group.vue'
    import SaleNotePayments from './partials/payments.vue'
    import SaleNotesOptions from './partials/options.vue'
    import SaleNoteGenerate from './partials/option_documents'
    import {deletable} from '../../../mixins/deletable'
    import ModalGenerateCPE from './ModalGenerateCPE'
    import {mapActions, mapState} from "vuex/dist/vuex.mjs";

    export default {
        props: [
            'soapCompany',
            'typeUser',
            'configuration'
        ],
        mixins: [deletable],
        components: {
            DataTable,
            SaleNotePayments,
            SaleNotesOptions,
            SaleNoteGenerate,
            ModalGenerateCPE,
            UploadToOtherServer
        },
        computed:{
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
                    paid: {
                        title: 'Estado de Pago',
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
                    license_plate:{
                        title: 'Placa',
                        visible: true
                    },
                    total_paid:{
                        title: 'Pagado',
                        visible: false
                    },
                    total_pending_paid:{
                        title: 'Por pagar',
                        visible: false
                    },
                    seller_name:{
                        title: 'Vendedor',
                        visible: false
                    },
                    recurrence: {
                        title: 'Recurrencia',
                        visible: false
                    }

                }
            }
        },
        created() {
            this.loadConfiguration()
            this.$store.commit('setConfiguration', this.configuration)
            this.getColumnsToShow();
        },
        filters:{
            period(name)
            {
                let res = ''
                switch(name)
                {
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
            getColumnsToShow(updated){

                this.$http.post('/validate_columns',{
                    columns : this.columns,
                    report : 'sale_notes_index', // Nombre del reporte.
                    updated : (updated !== undefined),
                })
                    .then((response)=>{
                        if(updated === undefined){
                            let currentCols = response.data.columns;
                            if(currentCols !== undefined) {
                                this.columns = currentCols
                            }
                        }
                    })
                    .catch((error)=>{
                        console.error(error)
                    })
            },
            duplicate(id){
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
                this.$http.post('/sale-notes/UpToOther',{'sale_note_id':recordId}).then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message);
                        this.$eventHub.$emit('reloadData')
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if(
                        error.response!== undefined &&
                        error.response.status !== undefined &&
                        error.response.status.errors !== undefined &&
                        error.response.status === 422 ) {
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
                    }
                    else {
                        this.$message.error(response.data.message);
                    }
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                    else {
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
