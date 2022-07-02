<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Comprobantes</span></li>
                <li><span class="text-muted">Facturas - Notas <small>(crédito y débito)</small> - Boletas - Anulaciones</span>
                </li>
            </ol>
            <div class="right-wrapper pull-right"
                 v-if="typeUser != 'integrator'">
                <span v-if="import_documents == true">
                    <button type="button"
                            class="btn btn-custom btn-sm  mt-2 mr-2"
                            @click.prevent="clickImport()"><i
                        class="fa fa-upload"></i> Importar Formato 1</button>
                </span>
                <span v-if="import_documents_second == true">
                    <button type="button"
                            class="btn btn-custom btn-sm  mt-2 mr-2"
                            @click.prevent="clickImportSecond()"><i
                        class="fa fa-upload"></i> Importar Formato 2</button>
                </span>
                <a :href="`/${resource}/create`"
                   class="btn btn-custom btn-sm  mt-2 mr-2"><i
                    class="fa fa-plus-circle"></i> Nuevo</a>
                <div class="btn-group flex-wrap">
                    <button type="button"
                            class="btn btn-custom btn-sm  mt-2 mr-2 dropdown-toggle"
                            data-toggle="dropdown"
                            aria-expanded="false"><i class="fa fa-money-bill-wave-alt"></i>
                        Reporte de Pagos <span class="caret"></span></button>
                    <!-- validadores apiperu  -->
                    <a href="#" @click.prevent="showDialogApiPeruDevValidate = true" v-if="view_apiperudev_validator_cpe"
                       class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-check"></i> Validación masiva</a>
                    <a href="#" @click.prevent="showDialogValidate = true" v-if="view_validator_cpe"
                       class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-file"></i> Validar CPE</a>

                    <div class="dropdown-menu"
                         role="menu"
                         x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 42px, 0px);">
                        <a class="dropdown-item text-1"
                           href="#"
                           @click.prevent="clickReportPayments()">Generar
                                                                  Reporte</a>
                        <a class="dropdown-item text-1"
                           href="#"
                           @click.prevent="clickDownloadReportPagos()">Descargar
                                                                       Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <!--
            <div class="data-table-visible-columns">

                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns"
                                          :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            -->
            <div class="card-body ">
                <data-table :resource="resource">

                        <el-dropdown :hide-on-click="false" slot="showhide">
                            <el-button type="primary">
                                Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item v-for="(column, index) in columns"
                                                  :key="index">
                                    <el-checkbox
                                        @change="getColumnsToShow(1)"
                                        v-model="column.visible">{{ column.title }}</el-checkbox>
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>

                    <tr slot="heading">
                        <th>#</th>
                        <th v-if="columns.soap_type.visible">SOAP</th>
                        <th class="text-center" style="min-width: 95px;">Emisión</th>
                        <th class="text-center"
                            v-if="columns.date_of_due.visible">Fecha Vencimiento
                        </th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th v-if="columns.notes.visible">Notas C/D</th>
                        <th v-if="columns.dispatch.visible">Guía de Remisión</th>
                        <th v-if="columns.sales_note.visible">Nota de venta</th>
                        <th v-if="columns.order_note.visible">Pedidos</th>
                        <th v-if="columns.send_it.visible">Email Enviado</th>
                        <th>Estado</th>
                        <th v-if="columns.user_name.visible">Usuario</th>
                        <th class="text-center" v-if="columns.currency_type_id.visible"  >Moneda</th>
                        <th class="text-right"
                            v-if="columns.guides.visible">Guia
                        </th>

                        <th class="text-center" v-if="columns.plate_numbers.visible">Placa</th>

                        <th class="text-right"
                            v-if="columns.total_exportation.visible">T.Exportación
                        </th>
                        <th class="text-right"
                            v-if="columns.total_free.visible">T.Gratuita
                        </th>
                        <th class="text-right"
                            v-if="columns.total_unaffected.visible">T.Inafecta
                        </th>
                        <th class="text-right"
                            v-if="columns.total_exonerated.visible">T.Exonerado
                        </th>
                        <th class="text-right"
                            v-if="columns.total_charge.visible">{{columns.total_charge.title}}
                        </th>
                        <th class="text-right">T.Gravado</th>
                        <th class="text-right">T.Igv</th>
                        <th class="text-right" v-if="columns.total.visible" >Total</th>
                        <th class="text-center" v-if="columns.balance.visible">Saldo</th>
                        <th class="text-center" style="min-width: 95px;" v-if="columns.purchase_order.visible"  >Orden de compra</th>
                        <th class="text-center"></th>
                        <th class="text-right" v-if="typeUser != 'integrator'">
                        </th>
                    </tr>
                    <tr slot-scope="{ index, row }"
                        :class="{'text-danger': (row.state_type_id === '11'),
                            'text-warning': (row.state_type_id === '13'),
                            'border-light': (row.state_type_id === '01'),
                            'border-left border-info': (row.state_type_id === '03'),
                            'border-left border-success': (row.state_type_id === '05'),
                            'border-left border-secondary': (row.state_type_id === '07'),
                            'border-left border-dark': (row.state_type_id === '09'),
                            'border-left border-danger': (row.state_type_id === '11'),
                            'border-left border-warning': (row.state_type_id === '13')}">
                        <td>{{ index }}</td>
                        <td v-if="columns.soap_type.visible"> {{ row.soap_type_description }}</td>
                        <td class="text-center">{{ row.date_of_issue }}</td>
                        <td class="text-center"
                            v-if="columns.date_of_due.visible">{{ row.date_of_due }}
                        </td>
                        <td>{{ row.customer_name }}<br/><small v-text="row.customer_number"></small></td>
                        <td>{{ row.number }}<br/>
                            <small v-text="row.document_type_description"></small><br/>
                            <small v-if="row.affected_document"
                                   v-text="row.affected_document"></small>
                        </td>
                        <td v-if="columns.notes.visible">
                            <template v-for="(row,index) in row.notes">
                                <label class="d-block"
                                       :key="index">{{ row.note_type_description }}:
                                                    {{ row.description }}</label>
                            </template>
                        </td>
                        <td v-if="columns.dispatch.visible">
                            <template v-for="(row,index) in row.dispatches">
                                <label class="d-block"
                                       :key="index">{{ row.description }}</label>
                            </template>
                        </td>
                        <td v-if="columns.sales_note.visible">
                            <template v-for="(row,index) in row.sales_note">
                                <label class="d-block"
                                       :key="index">{{ row.number_full }} ({{ row.state_type_description }})</label>
                            </template>
                        </td>
                        <td v-if="columns.order_note.visible">
                            <template v-if="row.order_note && row.order_note.identifier">
                                {{ row.order_note.identifier }}
                            </template>
                        </td>
                        <td v-if="columns.send_it.visible">
                            <!--
                            <el-tooltip
                                        class="item"
                                        effect="dark"
                                        placement="bottom">
                                <div slot="content">
                                    <span v-for="(item, i) in row.email_send_it_array"
                                          :key="i">
                                        {{ (item.email_send_it === false)?'No enviado':'Enviado' }} - {{ item.email }}  - {{ item.send_date }} <br>
                                    </span>
                                </div>
                                <span class="badge "
                                      :class="
                                      {'text-danger': (row.email_send_it === false), 'text-success': (row.email_send_it === true), }">
                                    <i class="fas fa-lg"
                                       :class="{ 'fa-times': (row.email_send_it === false), 'fa-check': (row.email_send_it === true), }"
                                    ></i>
                                </span>
                            </el-tooltip>-->

                            <span class="badge "
                                  :class="
                                      {'text-danger': (row.email_send_it === false), 'text-success': (row.email_send_it === true), }">
                                    <i class="fas fa-lg"
                                       :class="{ 'fa-times': (row.email_send_it === false), 'fa-check': (row.email_send_it === true), }"
                                    ></i>
                                </span>

                        </td>

                        <td>
                            <el-tooltip v-if="tooltip(row, false)"
                                        class="item"
                                        effect="dark"
                                        placement="bottom">
                                <div slot="content">{{ tooltip(row) }}</div>
                                <span class="badge bg-secondary text-white"
                                      :class="{'bg-danger': (row.state_type_id === '11'), 'bg-warning': (row.state_type_id === '13'), 'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}">
                                    {{ row.state_type_description }}
                                </span>
                            </el-tooltip>
                            <span v-else
                                  class="badge bg-secondary text-white"
                                  :class="{'bg-danger': (row.state_type_id === '11'), 'bg-warning': (row.state_type_id === '13'), 'bg-secondary': (row.state_type_id === '01'), 'bg-info': (row.state_type_id === '03'), 'bg-success': (row.state_type_id === '05'), 'bg-secondary': (row.state_type_id === '07'), 'bg-dark': (row.state_type_id === '09')}">
                                {{ row.state_type_description }}
                            </span>
                            <template v-if="row.regularize_shipping && row.state_type_id === '01'">
                                <el-tooltip class="item"
                                            effect="dark"
                                            :content="row.message_regularize_shipping"
                                            placement="top-start">
                                    <i class="fas fa-exclamation-triangle fa-lg"
                                       style="color: #D2322D !important"></i>
                                </el-tooltip>
                            </template>
                        </td>
                        <td v-if="columns.user_name.visible">
                            {{ row.user_name }}
                            <br/><small v-text="row.user_email"></small>
                        </td>
                        <td class="text-center" v-if="columns.currency_type_id.visible">{{ row.currency_type_id }}</td>
                        <td class="text-center"
                            v-if="columns.guides.visible">
                        <span v-for="(item, i) in row.guides"
                              :key="i">
                            {{ item.number }} <br>
                        </span>
                        </td>

                        <td class="text-center" v-if="columns.plate_numbers.visible">
                            <span v-for="(item, i) in row.plate_numbers" :key="i">
                                {{ item.description }} <br>
                            </span>
                        </td>

                        <td class="text-right"
                            v-if="columns.total_exportation.visible">{{ row.total_exportation }}
                        </td>

                        <td class="text-right"
                            v-if="columns.total_free.visible">{{ row.total_free }}
                        </td>

                        <td class="text-right"
                            v-if="columns.total_unaffected.visible">{{ row.total_unaffected }}
                        </td>
                        <td class="text-right"
                            v-if="columns.total_exonerated.visible">{{ row.total_exonerated }}
                        </td>
                        <td class="text-right"
                            v-if="columns.total_charge.visible">{{ row.total_charge }}
                        </td>
                        <td class="text-right">{{ row.total_taxed }}</td>
                        <td class="text-right">{{ row.total_igv }}</td>
                        <td class="text-right" v-if="columns.total.visible">{{ row.total }}</td>
                        <td class="text-right" v-if="columns.balance.visible">{{ row.balance }}</td>
                        <td v-if="columns.purchase_order.visible"  >{{ row.purchase_order }}</td>
                        <td class="text-center">
                            <button type="button"
                                    style="min-width: 41px"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_xml)"
                                    v-if="row.has_xml">XML
                            </button>
                            <button type="button"
                                    style="min-width: 41px"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_pdf)"
                                    v-if="row.has_pdf">PDF
                            </button>
                            <button type="button"
                                    style="min-width: 41px"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickDownload(row.download_cdr)"
                                    v-if="row.has_cdr">CDR
                            </button>
                        </td>

                        <td class="text-right"
                            v-if="typeUser != 'integrator'">
                            <div class="dropdown">
                                <button class="btn btn-default btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div v-if="configuration.permission_to_edit_cpe">
                                        <a :href="`/documents/${row.id}/edit`"
                                            class="dropdown-item"
                                            v-if="row.state_type_id === '01' && userPermissionEditCpe && row.is_editable">
                                            Editar
                                        </a>
                                    </div>
                                    <div v-else>
                                        <a :href="`/documents/${row.id}/edit`"
                                            class="dropdown-item"
                                            v-if="row.state_type_id === '01' && userId == row.user_id && row.is_editable">
                                            Editar
                                        </a>
                                    </div>
                                    <button class="dropdown-item"
                                            @click.prevent="clickResend(row.id)"
                                            v-if="row.btn_resend && !isClient">
                                        Reenviar
                                    </button>
                                    <button class="dropdown-item" @click.prevent="clickReStore(row.id)" v-if="row.btn_recreate_document">
                                        Volver a recrear
                                    </button>
                                    <button class="dropdown-item"
                                            @click.prevent="clickChangeToRegisteredStatus(row.id)"
                                            v-if="row.btn_change_to_registered_status">
                                        Cambiar a estado registrado
                                    </button>
                                    <a :href="`/${resource}/note/${row.id}`"
                                        class="dropdown-item"
                                        v-if="row.btn_note">
                                        Nota
                                    </a>
                                    <a :href="`/dispatches/create/${row.id}`"
                                        class="dropdown-item"
                                        v-if="row.btn_guide">
                                        Guía
                                    </a>
                                    <button class="dropdown-item"
                                        @click.prevent="clickVoided(row.id)"
                                        v-if="row.btn_voided">
                                        Anular
                                    </button>
                                    <a type="button"
                                            class="dropdown-item"
                                            @click.prevent="clickDeleteDocument(row.id)"
                                            v-if="row.btn_delete_doc_type_03">
                                        Eliminar
                                    </a>
                                    <a class="dropdown-item"
                                            @click.prevent="clickSendOnline(row.id)"
                                            v-if="isClient && !row.send_server">
                                        Enviar Servidor
                                    </a>
                                    <a class="dropdown-item"
                                            @click.prevent="clickCheckOnline(row.id)"
                                            v-if="isClient && row.send_server && (row.state_type_id === '01' || row.state_type_id === '03')">
                                        Consultar Servidor
                                    </a>
                                    <a v-if="row.btn_constancy_detraction"
                                            class="dropdown-item"
                                            @click.prevent="clickCDetraction(row.id)">
                                        C. Detracción
                                    </a>
                                    <button class="dropdown-item"
                                            @click.prevent="clickOptions(row.id)">
                                        Opciones
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item"
                                            @click.prevent="clickPayment(row.id)">
                                        Pagos
                                    </button>
                                </div>
                            </div>
                            <!-- funciona pero con funciones para cada boton, parametro command -->
                            <!-- <el-dropdown trigger="click" size="small">
                                <el-button size="mini" type="default" class="el-dropdown-selfdefine">
                                    <i class="fas fa-ellipsis-v"></i>
                                </el-button>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item v-if="row.btn_recreate_document">Recrear</el-dropdown-item>
                                    <el-dropdown-item>Action 2</el-dropdown-item>
                                    <el-dropdown-item>Action 3</el-dropdown-item>
                                    <el-dropdown-item>Action 4</el-dropdown-item>
                                    <el-dropdown-item>Action 5</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown> -->


                            <!-- <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-danger m-1__2"
                                    @click.prevent="clickDeleteDocument(row.id)"
                                    v-if="row.btn_delete_doc_type_03">
                                Eliminar
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickChangeToRegisteredStatus(row.id)"
                                    v-if="row.btn_change_to_registered_status">
                                Cambiar a estado registrado
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickReStore(row.id)"
                                    v-if="row.btn_recreate_document">
                                Volver a recrear
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-danger m-1__2"
                                    @click.prevent="clickVoided(row.id)"
                                    v-if="row.btn_voided">
                                Anular
                            </button>
                            <a :href="`/${resource}/note/${row.id}`"
                               class="btn waves-effect waves-light btn-xs btn-warning m-1__2"
                               v-if="row.btn_note">
                                Nota
                            </a>
                            <a :href="`/dispatches/create/${row.id}`"
                               class="btn waves-effect waves-light btn-xs btn-warning m-1__2"
                               v-if="row.btn_guide">
                                Guía
                            </a>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickResend(row.id)"
                                    v-if="row.btn_resend && !isClient">
                                Reenviar
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickSendOnline(row.id)"
                                    v-if="isClient && !row.send_server">
                                Enviar Servidor
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickCheckOnline(row.id)"
                                    v-if="isClient && row.send_server && (row.state_type_id === '01' || row.state_type_id === '03')">
                                Consultar Servidor
                            </button>
                            <button type="button"
                                    class="btn waves-effect waves-light btn-xs btn-info m-1__2"
                                    @click.prevent="clickOptions(row.id)">
                                Opciones
                            </button>
                            <button type="button"
                                    v-if="row.btn_constancy_detraction"
                                    class="btn waves-effect waves-light btn-xs btn-success m-1__2"
                                    @click.prevent="clickCDetraction(row.id)">
                                C. Detracción
                            </button> -->
                        </td>
                    </tr>
                </data-table>
            </div>

            <documents-voided :showDialog.sync="showDialogVoided"
                              :recordId="recordId"></documents-voided>

            <items-import :showDialog.sync="showImportDialog"></items-import>

            <document-import-second :showDialog.sync="showImportSecondDialog"></document-import-second>

            <document-options :showDialog.sync="showDialogOptions"
                              :recordId="recordId"
                              :showClose="true"
                              :configuration="configuration"></document-options>

            <document-payments :showDialog.sync="showDialogPayments"
                               :documentId="recordId"></document-payments>


            <document-constancy-detraction :showDialog.sync="showDialogCDetraction"
                                           :recordId="recordId"></document-constancy-detraction>
            <report-payment :showDialog.sync="showDialogReportPayment"></report-payment>

            <report-payment-complete :showDialog.sync="showDialogReportPaymentComplete"></report-payment-complete>

            <DocumentValidate :showDialogValidate.sync="showDialogValidate"></DocumentValidate>

            <massive-validate-cpe :showDialogValidate.sync="showDialogApiPeruDevValidate"></massive-validate-cpe>
        </div>
    </div>
</template>

<script>

import DocumentsVoided from './partials/voided.vue'
import DocumentOptions from './partials/options.vue'
import DocumentPayments from './partials/payments.vue'
import DocumentImportSecond from './partials/import_second.vue'
import DataTable from '../../../components/DataTableDocuments.vue'
import ItemsImport from './import.vue'
import {deletable} from '../../../mixins/deletable'
import DocumentConstancyDetraction from './partials/constancy_detraction.vue'
import ReportPayment from './partials/report_payment.vue'
import ReportPaymentComplete from './partials/report_payment_complete.vue'
import DocumentValidate from './partials/validate.vue';
import MassiveValidateCpe from '../../../../../modules/ApiPeruDev/Resources/assets/js/components/MassiveValidateCPE';
import {mapActions, mapState} from "vuex/dist/vuex.mjs";


export default {
    mixins: [deletable],
    props: [
        'isClient',
        'typeUser',
        'import_documents',
        'import_documents_second',
        'userId',
        'configuration',
        'userPermissionEditCpe',
        'view_apiperudev_validator_cpe',
        'view_validator_cpe'
    ],
    computed: {
        ...mapState([
            'config',
        ]),
    },
    components: {
        DocumentsVoided,
        ItemsImport,
        DocumentImportSecond,
        DocumentOptions,
        DocumentPayments,
        DataTable,
        DocumentConstancyDetraction,
        ReportPayment,
        ReportPaymentComplete,
        DocumentValidate,
        MassiveValidateCpe
    },
    data() {
        return {
            showDialogApiPeruDevValidate: false,
            showDialogValidate: false,
            showDialogReportPayment: false,
            showDialogReportPaymentComplete: false,
            showDialogVoided: false,
            showImportDialog: false,
            showDialogCDetraction: false,
            showImportSecondDialog: false,
            resource: 'documents',
            recordId: null,
            showDialogOptions: false,
            showDialogPayments: false,
            columns: {
                notes: {
                    title: 'Notas C/D',
                    visible: false
                },
                dispatch: {
                    title: 'Guía de Remisión',
                    visible: false
                },
                plate_numbers: {
                    title: 'Placa',
                    visible: false
                },
                user_name: {
                    title: 'Usuario',
                    visible: false
                },
                total_exportation: {
                    title: 'T.Exportación',
                    visible: false
                },
                total_free: {
                    title: 'T.Gratuito',
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
                date_of_due: {
                    title: 'F. Vencimiento',
                    visible: false
                },
                guides: {
                    title: 'Guias',
                    visible: false
                },
                sales_note: {
                    title: 'Nota de ventas',
                    visible: false
                },
                order_note: {
                    title: 'Pedidos',
                    visible: false
                },
                send_it: {
                    title: 'Correo enviado al destinatario',
                    visible: false
                },
                total: {
                    title: 'Total',
                    visible: false
                },
                currency_type_id: {
                    title: 'Moneda',
                    visible: false
                },
                purchase_order: {
                    title: 'Orden de Compra',
                    visible: false
                },
                soap_type: {
                    title: 'Soap',
                    visible: false
                },
                balance: {
                    title: 'Saldo',
                    visible: true
                },
                total_charge: {
                    title: 'T.Cargos',
                    visible: false
                },

            }
        }
    },
    created() {
        this.$store.commit('setConfiguration',this.configuration)
        this.loadConfiguration();
        this.getColumnsToShow();

    },
    methods: {
        ...mapActions(['loadConfiguration']),

        getColumnsToShow(updated){

            this.$http.post('/validate_columns',{
                columns : this.columns,
                report : 'document_index', // Nombre del reporte.
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
        clickVoided(recordId = null) {
            this.recordId = recordId
            this.showDialogVoided = true
        },
        clickDownload(download) {
            window.open(download, '_blank');
        },
        clickResend(document_id) {
            this.$http.get(`/${this.resource}/send/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message)
                })
        },
        clickSendOnline(document_id) {
            this.$http.get(`/${this.resource}/send_server/${document_id}/1`).then(response => {
                if (response.data.success) {
                    this.$message.success('Se envio satisfactoriamente el comprobante.');
                    this.$eventHub.$emit('reloadData');

                    this.clickCheckOnline(document_id);
                } else {
                    this.$message.error(response.data.message);
                }
            }).catch(error => {
                this.$message.error(error.response.data.message)
            });
        },
        clickCheckOnline(document_id) {
            this.$http.get(`/${this.resource}/check_server/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success('Consulta satisfactoria.')
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message)
                })
        },
        clickCDetraction(recordId) {
            this.recordId = recordId
            this.showDialogCDetraction = true
        },
        clickOptions(recordId = null) {
            this.recordId = recordId
            this.showDialogOptions = true
        },
        clickReStore(document_id) {
            this.$http.get(`/${this.resource}/re_store/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message)
                })
        },
        tooltip(row, message = true) {
            if (message) {
                if (row.shipping_status) return row.shipping_status.message;

                if (row.sunat_shipping_status) return row.sunat_shipping_status.message;

                if (row.query_status) return row.query_status.message;
            }

            if ((row.shipping_status) || (row.sunat_shipping_status) || (row.query_status)) return true;

            return false;
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickChangeToRegisteredStatus(document_id) {
            this.$http.get(`/${this.resource}/change_to_registered_status/${document_id}`)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
                        this.$eventHub.$emit('reloadData')
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    this.$message.error(error.response.data.message)
                })
        },
        clickImport() {
            this.showImportDialog = true
        },
        clickDownloadReportPagos() {

            this.showDialogReportPaymentComplete = true
        },
        clickImportSecond() {
            this.showImportSecondDialog = true
        },
        clickDeleteDocument(document_id) {
            this.destroy(`/${this.resource}/delete_document/${document_id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickReportPayments() {
            this.showDialogReportPayment = true
        }
    }
}
</script>
